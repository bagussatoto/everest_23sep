<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:42 PM
 */
require_once "Modul_Controller.php";

class Printing extends Modul_Controller
{
    // private $template;
    // private $jenisTr;
    // private $jenisTrName;
    // private $trConfig;
    // private $tableInConfig;
    // private $tableInConfig_static;
    // private $arrButtonAction;
    // private $dates = array();

    public function __construct()
    {

        parent::__construct();

        $this->load->config("heWebs");
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        // $this->load->model("Mdls/MdlMongoMother");
        $this->load->helper('he_angka');
        $this->load->helper('he_inword');
        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");

        // cekOrange($this->uri->segment_array());
        $this->nomerTr = $this->uri->segment(5);
        $this->idTr = $this->uri->segment(6);
        $this->jenisException = array("9911", "9912");
    }

    public function viewProformaReceipt()
    {

        //        die();
        $globalVars = array();
        $transaksi_jenis = $this->uri->segment(3);
        $transaksi_id = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("transaksi.id='" . $transaksi_id . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview
        //        cekHitam("cetak TMPR");
        //                        arrPrint($tmpTr);
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number == 0 ? 1 : $tmpTr[0]->step_number;

            // $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            //            cekHitam($currentStepNum);
            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum];
            //            $itemNumLabels2 = isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }
            // $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();

            //            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();
            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;


            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            //endregion

            //            cekHijau($receiptDetailFieldsReplacerConfig);


            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");
            $tmpTop = $tr->lookupMainTransaksi()->result();

            // $arrKey = array(
            //   $this->jenisTr
            // );
            // $cb = customCounters($tmpTr,$arrKey);
            // $cc = customCounters($tmpTop,"582pkd");
            // arrPrint($cc);
            // arrPrint($cb);
            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }
            // arrPrint($globalVars);
            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }
            //region take from registries

            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $itemsRegistries = array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    if ($row->values == null) {
                        $row->values = blobEncode(array());
                    }
                    switch ($row->param) {

                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields"://
                            $receiptDetailFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields2"://
                            $receiptDetailFields2 = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields2"://
                            $receiptSumFields2 = unserialize(base64_decode($row->values));
                            break;
                        case "items"://
                            $itemsRegistries = unserialize(base64_decode($row->values));
                            break;
                        case "items2"://
                            $items2Registries = unserialize(base64_decode($row->values));
                            break;
                        case "items2_sum"://
                            $items2_sumRegistries = unserialize(base64_decode($row->values));
                            break;
                        case "main"://
                            $main = unserialize(base64_decode($row->values));
                            break;

                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion


            $itemLabels = $receiptDetailFields;
            $itemLabels2 = $receiptDetailFields2;
            foreach ($tmpTr as $row) {

                $id = $row->produk_id;
                $tmp = array();

                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
                        if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                            $chars = explode("+", $key);
                            $colValue = "";
                            foreach ($chars as $key2) {
                                $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                            }
                        }
                        else {
                            $colValue = isset($row->$key) ? $row->$key : "";
                        }
                        $tmp[$key] = $colValue;
                        if (!isset($childTableInValueParams[$id][$key])) {
                            $childTableInValueParams[$id][$key] = $colValue;
                        }


                    }
                }

                //                arrPrint($subAmountConfig);
                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                }
                else {
                    $subtotal = 0;
                }

                //                arrPrint($rawItems);
                //                arrPrint($childTableInValueParams[$row->produk_id]);
                $tmp["subtotal"] = $subtotal;
                //                arrPrint($tmp);
                //                arrPrint($childTableInValueParams[$id]);
                //                arrPrint($childTableInValueParams);
                $rawItems[$row->produk_id] = $tmp;
                //                arrPrint($rawItems);

                //                $rawItems[$row->produk_id] = array_merge(array_filter($rawItems[$row->produk_id]), array_filter($childTableInValueParams[$row->produk_id]));
                $rawItems[$row->produk_id] = array_replace(array_filter($childTableInValueParams[$row->produk_id]), array_filter($rawItems[$row->produk_id]));
                //                arrPrint($rawItems);

                //arrPrint($receiptDetailFieldsReplacerConfig);
                if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                    foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                        if (array_key_exists($key, $rawItems[$row->produk_id])) {
                            $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                        }
                    }
                }

                //                arrprint($rawItems[$row->produk_id]);
                foreach ($availValueKeys as $ak) {
                    //                        echo "checking $ak: ";
                    if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                        //                        echo "ada ";
                        $valueKey = $ak;
                    }
                    else {
                        //                        echo "none ";
                    }
                }

            }
        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion


        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;
        //        $items=array_merge($items,$childTableInValueParams);
        //        arrprint($items);

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        //                arrPrint($mainProp);
        //                die("0000");
        //        $globalVars = $globalVars + (array)$mainProp;

        $globalVars = array_merge($globalVars, (array)$mainProp);
        //         arrPrint($globalVars);
        //endregion
        //        arrPrint($masterTableInParams);
        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion


        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                // cekHitam($eKey);
                //                arrPrint($eSpec);
                //                                cekHitam($eKey);
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHijau($relElementType);
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    // arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);

                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key ");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                    //                                $vTmp["nik"]="45245829";
                                    //                                    arrPrint($val);
                                    //                                                                arrPrint($vTmp);
                                    //                                                                cekBiru($key);
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        //                                        cekKuning($key."||".$val);
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    // cekHitam($vTmp);
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }


                $elements[$eKey] = $eTmp;
            }
            //            arrPrint($elements);
        }


        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams);
        //        arrPrint($globalVars);
        //        arrPrint($masterTableInParams);
        if (isset($globalVars['nomer'])) {
            //            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode']) ? "-" . digit_5($globalVars['countersTrID']['stepCode']) : "";
            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        if (isset($globalVars['nomer_top'])) {
            //            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
        }
        //arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        //        arrPrint($masterTableInValueParams);
        $dpValueDetails = array();
        $dpFieldName = array();
        if (isset($masterTableInValueParams['dp_value']) && $masterTableInValueParams['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams['dp_value'],
                "ppn_dp" => $masterTableInValueParams['dp_ppn_value'],
                "dp" => $masterTableInValueParams['dp'],
                "due_amount" => $masterTableInValueParams['tagihan'],
            );

            $dpFieldName = array(
                "dpp_dp" => "DPP Dp",
                "ppn_dp" => "vat Dp",
                "dp" => "Downpayment",
                "due_amount" => "Due amount",
            );
        }


        //endregion

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //arrPrint($signValues);
        //endregion

        //region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();
        // arrPrint($elementFixedConfig);

        //        arrPrint($globalVars);
        //        die();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                //                cekHere($key);
                //                cekHere($globalVars[$key]);
            }
        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($globalVars);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion


        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            $in_word = "";
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }
        else {
            $in_word = "";
        }

        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : "";

        //region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] . $staticNotes : "$staticNotes";
        $fixedElements['noteDetails']['label'] = "NOTES" . " <span class='text-white'>$trID</span>";
        //endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }


        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();


        $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array("subtotal" => "subtotal");
        $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        //        arrPrint($itemSubTotal);
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "subtotal");

        //        echo $row->step_number;

        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
        }

        //        arrprint($itemLabels);
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number] : array();

        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceipt";
        $tempData = array(

            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $masterTableInValueParams,
            "detailValues" => $childTableInValueParams,
            "itemLabels" => $itemLabels,
            "items" => $items,
            "items2" => $items2,
            "itemsRegistries" => $itemsRegistries,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            "sumRows" => $receiptSumFields,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "signHeader" => $signHeader,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,

        );

        $data = array(
            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configLayout[$jenisTr]["print_lable"]["steps"][$currentStepNum]["labelPre"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $main,
            "detailValues" => $childTableInValueParams,
            "itemLabels" => $itemLabels,
            "itemLabels2" => isset($itemLabels2) ? $itemLabels2 : array(),
            "noteEnabled" => $noteEnabled,
            "items" => $items,
            "items2" => isset($items2_sumRegistries) ? $items2_sumRegistries : $items2,
            "itemsRegistries" => $itemsRegistries,
            "inWord" => $in_word,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            "sumRows" => $receiptSumFields,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "signHeader" => $signHeader,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
            "tempData" => $tempData,
            "zeroAllowed" => $zeroAllowed,
            "dpFieldName" => $dpFieldName,
            "dpValueDetils" => $dpValueDetails,
        );
        //endregion

        //        arrPrint($data);
        $this->load->view("transaksi", $data);

    }

    public function viewReceipt()
    {
        $userGroupCenter = $this->config->item("userGroup");
        $userGroupBranch = array_merge($this->config->item("userGroup_cabang"), $this->config->item("userGroup_gudang"));

        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookUpAll()->result();
        $indTransksiID = $tmp1[0]->id;
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;

        $trep = new MdlTransaksi();
        $trep->setFilters(array());
        $trep->addFilter("id='$indTransksiID'");
        $tmpTr = $trep->LookUpJoined();

        $cabang_id_transaksi = $tmpTr[0]->cabang_id;

        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        //arrPrint($tmpSign);
        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                if ($cabang_id_transaksi == CB_ID_PUSAT) {
                    if (array_key_exists($row->group_code, $userGroupCenter)) {
                        $signValues['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                }
                else {
                    cekHitam("BAWAH");
                    if (array_key_exists($row->group_code, $userGroupBranch)) {
                        $signValues['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                }

            }
        }
        //        arrPrintWebs($signValues);
        //endregion

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview

        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number <= 0 ? 1 : $tmpTr[0]->step_number;
            $mainJenis = $tmpTr[0]->transaksi_jenis2;
            //cekHitam($currentStepNum);
            $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            //            cekHitam($currentStepNum);
            //            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]; //imi di of kan karena suapya gak ke overwrite dari he_transksi_ui. jika ada fields yang diperlukan untuk nota silahkan tambahi config di layout
            //            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum] : array();
            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum] : $this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum];
            //            $itemNumLabels2 = isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            if (isset($this->configLayout[$this->jenisTr]['receiptCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receiptCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }
            // $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();

            //            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();

            $printHitungConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['print_hitung'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['print_hitung'][$currentStepNum] : false;
            $printItemRecapConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_itemRecap'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_itemRecap'][$currentStepNum] : array();
            $printMainReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_mainReplacer'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_mainReplacer'][$currentStepNum] : array();
            $printUnsetSumFieldsConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_unsetSumFields'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_unsetSumFields'][$currentStepNum] : array();
            $printRoundDownConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_roundDown'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['print_hitung_roundDown'][$currentStepNum] : array();

            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
            $pairReceiptItemRegistries = isset($this->configUi[$this->jenisTr]['pairReceiptItemRegistries']) ? $this->configUi[$this->jenisTr]['pairReceiptItemRegistries'] : array();
            $receiptInWordConfig2 = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword2'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword2'][$currentStepNum]['in_word'] : array();
            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID);
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID);
            //endregion

            //            cekHijau($receiptDetailFieldsReplacerConfig);


            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");
            //            $tr->addFilter("id='$topID'");
            $tmpTop = $tr->lookUpAll()->result();

            // $arrKey = array(
            //   $this->jenisTr
            // );
            // $cb = customCounters($tmpTr,$arrKey);
            // $cc = customCounters($tmpTop,"582pkd");
            // arrPrint($cc);
            // arrPrint($cb);
            // arrPrint($tmpTr);
            $id_prevs = isset($tmpTr->ids_prev) ? $tmpTr->ids_prev : "";
            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }
            // arrPrint($globalVars);
            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }

            //region take from registries
            $tmpReg = $tr->lookupDataRegistriesByMasterID($trID)->result();
            $regFields = $tr->getFields()["dataRegistry"];
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $itemsRegistries = array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regFields as $param) {
                        if (isset($row->$param)) {
                            if ($row->$param == null) {
                                $row->$param = blobEncode(array());
                            }
                            switch ($param) {

                                case "tableIn_master"://
                                    $masterTableInParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_detail"://
                                    $childTableInParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_master_values"://
                                    $masterTableInValueParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_detail_values"://
                                    $childTableInValueParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_add_values"://
                                    $masterAddValues = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_add_fields"://
                                    $masterAddFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_elements"://
                                    $mainElements = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptDetailFields"://
                                    $receiptDetailFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptSumFields"://
                                    $receiptSumFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptDetailFields2"://
                                    $receiptDetailFields2 = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptSumFields2"://
                                    $receiptSumFields2 = unserialize(base64_decode($row->$param));
                                    break;
                                case "items"://
                                    $itemsRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items2"://
                                    $items2Registries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items2_sum"://
                                    $items2_sumRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "main"://
                                    $main = unserialize(base64_decode($row->$param));
                                    break;

                            }
                        }
                    }

                }

                if ($printHitungConfig == true) {
                    $rslt = array();
                    if (sizeof($childTableInValueParams) > 0) {
                        foreach ($childTableInValueParams as $pKey => $pVal) {
                            foreach ($pVal as $key => $val) {
                                if (is_numeric($val)) {
                                    $childTableInValueParams[$pKey][$key] = round($val);
                                    if (sizeof($printItemRecapConfig) > 0) {
                                        if (array_key_exists($key, $printItemRecapConfig)) {
                                            if (!isset($rslt[$key])) {
                                                $rslt[$key] = 0;
                                            }
                                            $anu = makeValue($printItemRecapConfig[$key], $childTableInValueParams[$pKey], $childTableInValueParams[$pKey], 0);
                                            $rslt[$key] += $anu;
                                        }
                                    }
                                }
                                else {
                                    $childTableInValueParams[$pKey][$key] = $val;
                                }
                            }
                        }
                        if (sizeof($rslt) > 0) {
                            foreach ($rslt as $key => $val) {
                                $main[$key] = $val;
                            }
                        }
                    }
                    if (sizeof($main) > 0) {
                        foreach ($main as $pKey => $pVal) {

                            if (is_numeric($pVal)) {
                                $main[$pKey] = round($pVal);
                            }
                            else {
                                $main[$pKey] = $pVal;
                            }

                            if ((sizeof($printMainReplacerConfig) > 0) && array_key_exists($pKey, $printMainReplacerConfig)) {
                                foreach ($printMainReplacerConfig as $key => $val) {
                                    $main[$key] = makeValue($val, $main, $main, 0);
                                    $masterTableInValueParams[$key] = makeValue($val, $main, $main, 0);
                                    //                                    cekHere($key . " rumus $val :: dihitung ulang " . $main[$key]);
                                }
                            }

                            //                            if ((sizeof($printRoundDownConfig) > 0) && in_array($pKey, $printRoundDownConfig)) {
                            //                                $main[$pKey] = floor($main[$pKey]);
                            //                                $masterTableInValueParams[$pKey] = floor($masterTableInValueParams[$pKey]);
                            //                                cekHere(":: $pKey -> " . $main[$pKey]);
                            //                            }
                        }
                        if ((sizeof($printRoundDownConfig) > 0)) {
                            foreach ($printRoundDownConfig as $rSpec) {
                                $main[$rSpec] = floor($main[$rSpec]);
                                $masterTableInValueParams[$rSpec] = floor($masterTableInValueParams[$rSpec]);
                                //                                cekHere(":: $rSpec -> " . $main[$rSpec]);
                            }
                        }
                    }
                    if (sizeof($printUnsetSumFieldsConfig) > 0) {

                        foreach ($printUnsetSumFieldsConfig as $val) {

                            if (isset($receiptSumFields[$val])) {
                                unset($receiptSumFields[$val]);
                            }
                        }
                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion
            //cekBiru(count($tmpTr));
            //arrPrint($itemsRegistries);
            //arrPrint($main);
            //arrPrint($masterTableInValueParams);

            $itemLabels = sizeof($receiptDetailFields) > 0 ? $receiptDetailFields : $itemLabels;
            $itemLabels2 = $receiptDetailFields2;
            if ($mainJenis == "paket") {
                //                arrPrint($itemLabels);
                $rawItems[] = array(
                    "produk_kode" => "",
                    "produk_nama" => $tmpTr[0]->transaksi_jenis2_label,
                    "nett1" => $main['new_net1'],
                    "subtotal" => $main['grand_total_ui'],
                );
            }
            else {
                foreach ($tmpTr as $row) {

                    $id = $row->produk_id;
                    $tmp = array();

                    if (sizeof($itemLabels) > 0) {
                        foreach ($itemLabels as $key => $val) {
                            if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                                $chars = explode("+", $key);
                                $colValue = "";
                                foreach ($chars as $key2) {
                                    $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                                }
                            }
                            else {
                                $colValue = isset($row->$key) ? $row->$key : "";
                            }
                            $colValue = ($printHitungConfig == true && is_numeric($colValue)) ? round($colValue) : $colValue;
                            $tmp[$key] = $colValue;
                            if (!isset($childTableInValueParams[$id][$key])) {
                                $childTableInValueParams[$id][$key] = $colValue;
                            }
                        }
                    }

                    //                arrPrint($subAmountConfig);
                    if ($subAmountConfig != null) {
                        $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                        $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                        $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                    }
                    else {
                        $subtotal = 0;
                    }

                    $tmp["subtotal"] = $subtotal;

                    $rawItems[$row->produk_id] = $tmp;

                    //                arrPrint($rawItems);
                    //                $rawItems[$row->produk_id] = array_merge(array_filter($rawItems[$row->produk_id]), array_filter($childTableInValueParams[$row->produk_id]));

                    $rawItems[$row->produk_id] = array_replace(array_filter($childTableInValueParams[$row->produk_id]), array_filter($rawItems[$row->produk_id]));
                    //                arrPrint($rawItems);

                    //arrPrint($receiptDetailFieldsReplacerConfig);
                    if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                        foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                            if (array_key_exists($key, $rawItems[$row->produk_id])) {
                                $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                            }
                        }
                    }

                    //                arrprint($rawItems[$row->produk_id]);
                    foreach ($availValueKeys as $ak) {
                        //                        echo "checking $ak: ";
                        if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                            //                        echo "ada ";
                            $valueKey = $ak;
                        }
                        else {
                            //                        echo "none ";
                        }
                    }

                }
            }

        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion


        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        $globalVars = array_merge($globalVars, (array)$mainProp);

        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion


        $elementInjectorConfig = isset($this->configLayout[$this->jenisTr]['receiptElementInjector']) ? $this->configLayout[$this->jenisTr]['receiptElementInjector'] : array();
        $elementInjectorUsedFieldsConfig = isset($this->configLayout[$this->jenisTr]['receiptElementInjector']['source']['usedFields']) ? $this->configLayout[$this->jenisTr]['receiptElementInjector']['source']['usedFields'] : array();
        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {

                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHitam(":: $eKey :: $elementType ::");
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    //                                    arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);
                                        //                                        cekMerah("hapuslah nik");
                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                        //                                        cekMerah("hapuslah nik");
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }

                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }
                $elements[$eKey] = $eTmp;
            }

            if (sizeof($elementInjectorConfig) > 0) {
                if (isset($elementInjectorConfig['source'])) {
                    $srcElement = $elementInjectorConfig['source']['element'];
                    $srcFields = $elementInjectorConfig['source']['fields'];
                    $arrSource = array();
                    if (array_key_exists($srcElement, $elements)) {
                        foreach ($srcFields as $key => $label) {
                            $arrSource[$label] = $elements[$srcElement]['contents'][$key];
                        }
                    }
                }
            }
            if (isset($elementInjectorConfig['target'])) {
                $targetElement = $elementInjectorConfig['target']['element'];
                if (array_key_exists($targetElement, $elements)) {
                    foreach ($elements[$targetElement] as $ii => $eSpec) {
                        if ($ii == "contents") {
                            $addContens = array();
                            foreach ($arrSource as $key => $val) {
                                $addContens[$key] = $val;
                            }
                            $elements[$targetElement][$ii] = $addContens + $eSpec;
                        }
                    }
                }
            }

        }


        $blacklist_key = array("dtime");
        foreach ($blacklist_key as $list) {
            if (isset($main[$list])) {
                unset($main[$list]);
            }
        }
        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams, $main);


        if (isset($globalVars['nomer'])) {
            //            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode']) ? "-" . digit_5($globalVars['countersTrID']['stepCode']) : "";
            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        if (isset($globalVars['nomer_top'])) {
            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
            // $num_top = showHistoriGlobalNumbers($globalVars["ids_his"],1);
            // $globalVars['nomer_top'] = $num_top."**";
        }
        else {
            $globalVars['nomer_top'] = "-";
        }


        if (isset($globalVars["ids_his"])) {
            $stepPrev = $globalVars["step_number"] - 1;
            if ($stepPrev > 0) {
                // $cHist = blobDecode($globalVars["ids_his"])[$stepPrev];
                // $counterhists = blobDecode($cHist["counters"]);
                // $cNomer = $cHist["nomer"];
                // $cNomerExpl = explode(".", $cNomer);
                // $cTrcode = $cNomerExpl[0];
                // $cTrplace = $cNomerExpl[1];
                // $cgJenis = "$cTrcode|$cTrplace";
                //
                // $cgHist = digit_5($counterhists["stepCode|placeID"]["$cgJenis"]);
                //
                // $nomer_prev = $cNomer . "-" . $cgHist;

                // $globalVars['nomers_prev'] = formatField("nomer", $nomer_prev);

                //                arrPrint($stepPrev);
                $globalVars['nomers_prev'] = showHistoriGlobalNumbers($globalVars["ids_his"], $stepPrev, true);
            }
            else {
                $globalVars['nomers_prev'] = "-";
            }
        }
        else {
            $ids_prev = blobDecode($tmpTr[0]->ids_prev);
            // arrPrint($tmpTr[0]->ids_prev);
            $trx = new MdlTransaksi();
            $trx->setFilters(array());
            $trx->addFilter("id='" . $ids_prev[0] . "'");
            $tmpTrx = $trx->lookupMainTransaksi()->result();
            $counterTrID = blobDecode($tmpTrx[0]->counters);
            foreach ($counterTrID as $key_d => $val_d) {
                foreach ($val_d as $val_dd) {
                    $tmpTrx['countersTrID'][$key_d] = $val_dd;
                }
            }
            //            arrPrint($tmpTrx);
            $addTrIdCounter = isset($tmpTrx['countersTrID']['stepCode|placeID']) ? "-" . digit_5($tmpTrx['countersTrID']['stepCode|placeID']) : "";
            //            cekHere($addTrIdCounter);
            $globalVars['nomers_prev'] = isset($tmpTrx[0]->nomer) ? formatField("nomer", $tmpTrx[0]->nomer . $addTrIdCounter) : "-";
        }


        // arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        //        arrPrint($masterTableInValueParams);
        $dpValueDetails = array();
        $dpFieldName = array();
        if (isset($masterTableInValueParams['dp_value']) && $masterTableInValueParams['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams['dp_value'],
                "ppn_dp" => $masterTableInValueParams['dp_ppn_value'],
                "dp" => $masterTableInValueParams['dp'],
                "due_amount" => $masterTableInValueParams['tagihan'],
            );

            $dpFieldName = array(
                "dpp_dp" => "DPP Dp",
                "ppn_dp" => "vat Dp",
                "dp" => "Downpayment",
                "due_amount" => "Due amount",
            );
        }


        //endregion

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //arrPrint($signValues);
        //endregion

        //region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();
        // arrPrint($elementFixedConfig);

        //        arrPrint($globalVars);
        //        die();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                if (is_array($label)) {
                    $dataHist = blobDecode($globalVars[$label['source']]);
                    $counters = blobDecode($dataHist[$label['step']]['counters']);
                    $countersUrut = array_values($counters['stepCode|placeID'])[0];
                    $countersUrut = "-" . digit_5($countersUrut);
                    $fixedElements['fixedElements']['contents'][$label['label']] = isset($dataHist[$label['step']][$label['target']]) ? formatField($label['target'], $dataHist[$label['step']][$label['target']]) . $countersUrut : "";
                    $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                }
                else {

                    //                    $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                    if (isset($globalVars[$key])) {
                        if (is_array($globalVars[$key])) {
                            if (sizeof($globalVars[$key])) {
                                //                                arrPrint($globalVars[$key]);
                                $ii_result = "";
                                foreach ($globalVars[$key] as $ii => $ii_val) {
                                    if ($ii_result == "") {
                                        $ii_result = formatField($key, $ii_val);
                                    }
                                    else {
                                        $ii_result .= "<br>" . formatField($key, $ii_val);
                                    }
                                }
                                $fixedElements['fixedElements']['contents'][$label] = $ii_result;
                            }
                            else {
                                $fixedElements['fixedElements']['contents'][$label] = "";
                            }
                        }
                        else {
                            $fixedElements['fixedElements']['contents'][$label] = formatField($key, $globalVars[$key]);
                        }
                    }
                    else {
                        $fixedElements['fixedElements']['contents'][$label] = "";
                    }
                    $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                }


            }
        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($globalVars);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion

        //region add fields2 main
        $addMainData2Fields = isset($this->configLayout[$this->jenisTr]["receiptAddMain"][$row->step_number]) ? $this->configLayout[$this->jenisTr]["receiptAddMain"][$row->step_number] : array();
        $addMainData2 = array();
        if (sizeof($addMainData2Fields) > 0) {
            foreach ($addMainData2Fields as $keys => $keysLabel) {
                $addMainData2[$keys] = isset($globalVars[$keys]) ? $globalVars[$keys] : "";
            }
        }
        //endregion


        $in_word = "";
        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            //            cekHitam();
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        matiHere($val_convert);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }

        $in_word2 = "";
        if (sizeof($receiptInWordConfig2) > 0) {
            $this->load->helper("he_inword");
            foreach ($receiptInWordConfig2 as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {
                    $val_convert = $masterTableInValueParams[$fieldsSelected];
                    $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                    //                        $val_convert1=formatField("tagihan",$val_convert);
                    $val_convert1 = number_format(0 + $val_convert);

                    //                        echo $val_convert1;
                    $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                    //                        cekHitam($val_convert. " ||". $val_convert1);
                    $he_word2 = $he_loader($val_convert2, $currency);
                    //                        $he_word = $he_loader($val_convert, $currency);
                    $in_word2 .= "$he_word2";


                }
            }
        }
        else {
            $in_word2 = "";
        }

        //region load Mdl static note
        //        arrPrint($globalVars);
        $this->load->model("Mdls/MdlStaticNotes");
        $sn = new MdlStaticNotes();
        $cbSelected = $globalVars['cabang_id'];
        $sn->addFilter("cabang_id=$cbSelected");
        $availNotes = $sn->lookupAll()->result();
        //        cekLime($this->db->last_query());
        //        matiHere();
        $temNotes = array();
        if (sizeof($availNotes) > 0) {
            foreach ($availNotes as $tempNotes) {
                $jn = $tempNotes->jenis;
                $val = $tempNotes->nilai;
                $temNotes[$jn] = $val;

            }
        }
        //        arrPrint($temNotes);

        //endregion

        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : false;
        $listedDif = array("584");


        //region notes element
        if ($staticNotes) {
            if ($tmpTr[0]->jenis_master == "382") {
                $notesfill = $temNotes["382"];
            }
            else {
                $notesfill = $temNotes["582"];
            }

        }
        else {
            $notesfill = "";
        }

        $fixedElements['noteDetails'] = array();

        if (isset($globalVars['description'])) {
            $catatan = $globalVars['description'];
            $fixedElements['noteDetails']['contents'][] = $catatan;
        }
        if (isset($globalVars['description_additional']) && (sizeof($globalVars['description_additional']) > 0)) {
            $ii_rslt = "";
            foreach ($globalVars['description_additional'] as $ii => $iiVal) {
                if ($ii_rslt == "") {
                    $ii_rslt = "<br />" . $iiVal;
                }
                else {
                    $ii_rslt .= "<br />" . $iiVal;
                }
            }
            $catatan = $ii_rslt;
            $fixedElements['noteDetails']['contents'][] = $catatan;
        }


        $fixedElements['noteDetails']['contents'][] = "<br><br>" . $notesfill;

        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array("subtotal" => "subtotal");

        //        cekMerah("cetak items label [$currentStepNum]");
        //        arrPrint($itemLabels);
        //        cekMerah("cetak items num label");
        //        arrPrint($itemNumLabels);
        //        cekMerah("cetak sub total");
        //        arrPrint($itemSubTotal);

        $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "subtotal");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
        }
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number] : array();
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceipt";


        $receiptSumFieldsReplacer = isset($this->configLayout[$this->jenisTr]['receiptSumFieldsReplacer']) ? $this->configLayout[$this->jenisTr]['receiptSumFieldsReplacer'] : array();
        if (sizeof($receiptSumFieldsReplacer) > 0) {
            foreach ($receiptSumFields as $key => $val) {
                $receiptSumFields[$key] = (isset($receiptSumFieldsReplacer[$val]) && isset($main[$receiptSumFieldsReplacer[$val]])) ? $main[$receiptSumFieldsReplacer[$val]] : $val;
            }
        }


        $data = array(
            //            "mode"       => $this->uri->segment(2),
            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            //            "mainValues" => $mainValues,
            //            "detailValues" => $detailValues,
            "mainValues" => $main,
            //            "mainValues" => $masterTableInValueParams,
            "detailValues" => $childTableInValueParams,
            //            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            //            "itemLabels" => $itemLabels + $itemNumLabels,
            "itemLabels" => $itemLabels,
            "itemLabels2" => isset($itemLabels2) ? $itemLabels2 : array(),
            "noteEnabled" => $noteEnabled,
            "items" => $items,
            "items2" => isset($items2_sumRegistries) ? $items2_sumRegistries : $items2,
            "itemsRegistries" => $itemsRegistries,
            "inWord" => $in_word,
            "inWord2" => $in_word2,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            //            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "sumRows" => $receiptSumFields,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            //            "grandTotal"     => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "elementUsedFieldsConfigs" => $elementInjectorUsedFieldsConfig,
            "signHeader" => $signHeader,
            //            "dataTemp" => $dataTemp,
            //            "fixedElements" => $fixedElements,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
            //            "tempData" => $tempData,
            "zeroAllowed" => $zeroAllowed,
            "dpFieldName" => $dpFieldName,
            "dpValueDetils" => $dpValueDetails,
            "mainData2Fields" => $addMainData2Fields,
            "mainData2" => $addMainData2,
        );
        //endregion

        //        arrPrint($globalVars);
        $this->load->view("printing", $data);

    }

    public function viewReceiptReg_ori()
    {
        $userGroupCenter = $this->config->item("userGroup");
        $userGroupBranch = array_merge($this->config->item("userGroup_cabang"), $this->config->item("userGroup_gudang"));

        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookUpAll()->result();
        $indTransksiID = $tmp1[0]->id;
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $tmpTr = $tr->lookupJoined();
        // arrPrint($tmpTr);
        $cabang_id_transaksi = $tmpTr[0]->cabang_id;
        $replaceStepPic[$tmpTr[0]->step_number] = array(
            "olehID" => $tmpTr[0]->oleh_id,
            "olehNama" => $tmpTr[0]->oleh_nama,
        );
        //        arrPrint($replaceStepPic);
        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        // arrPrint($tmpSign);
        // matiHEre();
        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                if ($cabang_id_transaksi == CB_ID_PUSAT) {
                    if (array_key_exists($row->group_code, $userGroupCenter)) {
                        $signValues['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                    else {
                        $signValues['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                }
                else {
                    // cekHitam("BAWAH");
                    if (array_key_exists($row->group_code, $userGroupBranch)) {
                        $signValues['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                    else {
                        $signValues['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                }
            }
            //            arrPrint($signValues);
            foreach ($replaceStepPic as $stepTr => $replaceStepTmp) {
                if (isset($signValues['sign_' . $stepTr])) {
                    $signValues['sign_' . $stepTr]['contents'] = $replaceStepTmp['olehNama'];
                }
            }
        }
        //endregion
        //        arrPrint($signValues);
        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview
        //        cekBiru("66 Line " . __LINE__);
        if (sizeof($tmpTr) > 0) {
            //            cekBiru("66 Line " . __LINE__);
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number <= 0 ? 1 : $tmpTr[0]->step_number;
            $mainJenis = $tmpTr[0]->transaksi_jenis2;
            //            cekHitam($this->jenisTr);
            $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            //            cekHitam($currentStepNum);
            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum];
            //            $itemNumLabels2 = isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }
            // $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();

            //            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();
            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
            $pairReceiptItemRegistries = isset($this->configUi[$this->jenisTr]['pairReceiptItemRegistries']) ? $this->configUi[$this->jenisTr]['pairReceiptItemRegistries'] : array();
            $receiptInWordConfig2 = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword2'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword2'][$currentStepNum]['in_word'] : array();
            $receiptDetailSrcFields = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailSrcFields'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailSrcFields'][$currentStepNum] : array();
            $shoppingCartSubDetailFields = isset($this->configUi[$tmpTr[0]->jenis_master]['shoppingCartSubDetailFields'][$currentStepNum]) ? $this->configUi[$tmpTr[0]->jenis_master]['shoppingCartSubDetailFields'][$currentStepNum] : array();


            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID);
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID);
            //endregion
            $headerField = isset($this->configLayout[$this->jenisTr]['headerField']) ? $this->configLayout[$this->jenisTr]['headerField'] : "registry";
            //            cekHijau($receiptDetailFieldsReplacerConfig);


            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");

            $tmpTop = $tr->lookupMainTransaksi()->result();

            // $arrKey = array(
            //   $this->jenisTr
            // );
            // $cb = customCounters($tmpTr,$arrKey);
            // $cc = customCounters($tmpTop,"582pkd");
            // arrPrint($cc);
            // arrPrint($cb);
            // arrPrint($tmpTr);
            $id_prevs = $tmpTr[0]->ids_prev;
            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }
            // arrPrint($globalVars);
            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }
            //region switcher noyta pembatalan
            if (in_array($this->jenisTr, $this->jenisException)) {
                $injectedFields = $this->configLayout[$this->jenisTr]["receiptNumFields"][$currentStepNum];
                // arrPrint($injectedFields);
                // matiHEre($this->jenisTr);
            }
            else {
                $injectedFields = array();
            }


            //endregion
            //region take from registries
            $tmpReg = $tr->lookupDataRegistriesByMasterID($trID)->result();
            $mongoFields = $tr->getFields()["dataRegistry"];
            // arrPrint($tmpReg);
            // arrPrint($mongoFields);
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $itemsRegistries = array();
            $itemSrc = array();
            $receiptRsltItems = array();
            // arrPrint($tmpReg);
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($mongoFields as $param) {
                        if (isset($row->$param)) {
                            switch ($param) {

                                case "tableIn_master"://
                                    $masterTableInParams = unserialize(base64_decode($row->$param));
                                    unset($masterTableInParams['dtime']);
                                    break;
                                case "tableIn_detail"://
                                    $childTableInParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_master_values"://
                                    $masterTableInValueParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_detail_values"://
                                    $childTableInValueParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_add_values"://
                                    $masterAddValues = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_add_fields"://
                                    $masterAddFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_elements"://
                                    $mainElements = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptDetailFields"://
                                    $receiptDetailFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptSumFields"://
                                    $receiptSumFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptDetailFields2"://
                                    $receiptDetailFields2 = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptSumFields2"://
                                    $receiptSumFields2 = unserialize(base64_decode($row->$param));
                                    break;
                                case "items"://
                                    $itemsRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items2"://
                                    $items2Registries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items2_sum"://
                                    $items2_sumRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items3_sum"://
                                    $items3_sumRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "main"://
                                    $main = unserialize(base64_decode($row->$param));

                                    unset($main['dtime']);
                                    break;
                                case "itemSrc"://
                                    $itemSrc = unserialize(base64_decode($row->$param));
                                    break;
                                case "rsltItems":
                                    $receiptRsltItems = unserialize(base64_decode($row->$param));
                                    break;

                            }
                        }
                    }

                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion
            // matiHEre("stopppdulu brooo");
            //cekBiru(count($tmpTr));
            //arrPrint($receiptDetailSrcFields);
            // arrPrintPink($main);
            // arrPrintPink($itemsRegistries);
            // arrPrintPink($receiptDetailFields);

            $sumItemsRslt = array();
            $valMainInject = 0;
            if (sizeof($injectedFields) > 0) {


                foreach ($receiptRsltItems as $receiptRsltItems_0) {
                    if (!isset($sumItemsRslt[$receiptRsltItems_0['id']]['sisa'])) {
                        $sumItemsRslt[$receiptRsltItems_0['id']]['sisa'] = 0;
                    }
                    if (!isset($sumItemsRslt[$receiptRsltItems_0['id']]['subtotal'])) {
                        $sumItemsRslt[$receiptRsltItems_0['id']]['subtotal'] = 0;
                    }
                    $sumItemsRslt[$receiptRsltItems_0['id']]['sisa'] += $receiptRsltItems_0['sub_hpp'];
                    $sumItemsRslt[$receiptRsltItems_0['id']]['subtotal'] += $receiptRsltItems_0['sub_hpp'];
                    $valMainInject += $receiptRsltItems_0['sub_hpp'];
                }

                $main['tagihan'] = $valMainInject;
                // arrPrint($valMainInject);
                // matiHere();
            }
            //matiHere();
            //            cekKuning($tmpTr[0]->jenis_master);
            //            cekKuning($mainJenis);

            //            matiHEre();
            /* -------------------------------------------------
            * switcher registry ke layout
            * -------------------------------------------------*/
            if ($headerField == "registry") {
                $itemLabels = sizeof($receiptDetailFields) > 0 ? $receiptDetailFields : $itemLabels;
                $mainElements = $mainElements;
            }
            else {
                $itemLabels = $itemLabels;
                $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
            }
            // arrPrintHijau($mainElements);
            // arrPrint($elementConfigs);
            // $itemLabels = $receiptDetailFields;
            $itemLabels2 = $receiptDetailFields2;
            $itemSrcLabel = $receiptDetailSrcFields;
            if (($mainJenis == "paket") && ($tmpTr[0]->jenis_master == "1582")) {
                // cekBiru("MASUK ATAS");
                //                arrPrint($itemsRegistries);
                $rawItems = array();
                foreach ($itemsRegistries as $tmpItem) {
                    $tmpItemList = array();
                    //                    arrPrint($tmpItem);
                    //                    die();
                    foreach ($itemLabels as $key => $alias) {
                        $tmpItemList[$key] = $tmpItem[$key];
                    }
                    $injectParam = array(
                        "nett1_bulat" => $tmpItem['harga'],
                        "subtotal" => $tmpItem['subtotal'],
                    );
                    //                    cekMerah("ctak injector");
                    //                    arrPrint($injectParam);
                    $rawItems[] = $tmpItemList + $injectParam;
                }
            }
            else {
                foreach ($tmpTr as $row) {
                    $id = $row->produk_id;
                    //                    cekBiru($id);
                    $tmp = array();

                    if (sizeof($itemLabels) > 0) {
                        foreach ($itemLabels as $key => $val) {
                            if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                                $chars = explode("+", $key);
                                $colValue = "";
                                foreach ($chars as $key2) {
                                    $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                                }
                            }
                            else {
                                $colValue = isset($row->$key) ? $row->$key : "";
                            }
                            $tmp[$key] = $colValue;
                            if (!isset($childTableInValueParams[$id][$key])) {
                                $childTableInValueParams[$id][$key] = $colValue;
                            }
                        }
                    }


                    //                    arrPrint($childTableInValueParams[$id]);
                    //                    arrPrint($subAmountConfig);
                    if ($subAmountConfig != null) {
                        // cekLime("masukk" . $id);
                        $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                        $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                        //                         arrPrint($subAmountConfig);
                        $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                        //                        cekBiru($subtotal);
                    }
                    else {
                        $subtotal = 0;
                    }
                    // cekHere($subtotal . " --- " . $id);
                    //                    matiHEre();
                    if (isset($itemsRegistries[$id])) {
                        foreach ($itemLabels as $key => $labels) {
                            if (isset($itemsRegistries[$id][$key])) {
                                $tmp[$key] = $itemsRegistries[$id][$key];
                            }
                        }
                    }
                    // arrPrint($tmp);

                    $tmp["subtotal"] = $subtotal;

                    $rawItems[$row->produk_id] = $tmp;
                    $rawItems[$row->produk_id] = array_replace(array_filter($childTableInValueParams[$row->produk_id]), array_filter($rawItems[$row->produk_id]));
                    // arrPrint($rawItems);

                    //arrPrint($receiptDetailFieldsReplacerConfig);
                    //                    matiHEre();
                    if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                        foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                            if (array_key_exists($key, $rawItems[$row->produk_id])) {
                                $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                            }
                        }
                    }

                    //                                    arrprint($rawItems);
                    foreach ($availValueKeys as $ak) {
                        //                        echo "checking $ak: ";
                        if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                            //                        echo "ada ";
                            $valueKey = $ak;
                        }
                        else {
                            //                        echo "none ";
                        }
                    }

                }
            }

        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion
        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;
        //region injector jika pembatalan melibatkan fifo
        if (sizeof($sumItemsRslt) > 0) {
            $valMainInject = 0;
            foreach ($rawItems as $pid => $pidData) {
                if (isset($sumItemsRslt[$pid])) {
                    foreach ($sumItemsRslt[$pid] as $kKey => $value_rslt) {
                        // cekMerah($pidData['produk_nama']."".$kKey."---".$value_rslt);
                        $items[$pid][$kKey] = $value_rslt;
                        $itemsRegistries[$pid][$kKey] = $value_rslt;
                    }
                }

            }

        }

        //endregion

        //region replace main labels with properties from future/next step

        $mainProp = $tmpTr[0];
        $globalVars = array_merge($globalVars, (array)$mainProp, $main);
        //        arrPrint((array)$mainProp);
        //        arrPrintWebs($globalVars);
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion

        // arrPrint($items);

        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                // cekHitam($eKey);
                //                arrPrint($eSpec);
                //                                cekHitam($eKey);
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHijau($relElementType);
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    // arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);

                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key ");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                    //                                $vTmp["nik"]="45245829";
                                    //                                    arrPrint($val);
                                    //                                                                arrPrint($vTmp);
                                    //                                                                cekBiru($key);
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        //                                        cekKuning($key."||".$val);
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    // cekHitam($vTmp);
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }


                $elements[$eKey] = $eTmp;
            }
            //            arrPrint($elements);
        }


        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams);

        //        arrPrint($globalVars);
        //        arrPrint($masterTableInParams);
        //        arrPrint();


        if (isset($globalVars['nomer'])) {
            //            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode']) ? "-" . digit_5($globalVars['countersTrID']['stepCode']) : "";
            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        if (isset($globalVars['nomer_top'])) {
            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
            // $num_top = showHistoriGlobalNumbers($globalVars["ids_his"],1);
            // $globalVars['nomer_top'] = $num_top."**";
        }
        else {
            $globalVars['nomer_top'] = "-";
        }


        if (isset($globalVars["ids_his"])) {
            $stepPrev = $globalVars["step_number"] - 1;
            if ($stepPrev > 0) {
                // $cHist = blobDecode($globalVars["ids_his"])[$stepPrev];
                // $counterhists = blobDecode($cHist["counters"]);
                // $cNomer = $cHist["nomer"];
                // $cNomerExpl = explode(".", $cNomer);
                // $cTrcode = $cNomerExpl[0];
                // $cTrplace = $cNomerExpl[1];
                // $cgJenis = "$cTrcode|$cTrplace";
                //
                // $cgHist = digit_5($counterhists["stepCode|placeID"]["$cgJenis"]);
                //
                // $nomer_prev = $cNomer . "-" . $cgHist;

                // $globalVars['nomers_prev'] = formatField("nomer", $nomer_prev);

                //                arrPrint($stepPrev);
                $globalVars['nomers_prev'] = showHistoriGlobalNumbers($globalVars["ids_his"], $stepPrev, true);
            }
            else {
                $globalVars['nomers_prev'] = "-";
            }
        }
        else {
            $ids_prev = blobDecode($tmpTr[0]->ids_prev);
            // arrPrint($tmpTr[0]->ids_prev);
            $trx = new MdlTransaksi();
            $trx->setFilters(array());
            $trx->addFilter("id='" . $ids_prev[0] . "'");
            $tmpTrx = $trx->lookupMainTransaksi()->result();
            $counterTrID = blobDecode($tmpTrx[0]->counters);
            foreach ($counterTrID as $key_d => $val_d) {
                foreach ($val_d as $val_dd) {
                    $tmpTrx['countersTrID'][$key_d] = $val_dd;
                }
            }
            //            arrPrint($tmpTrx);
            $addTrIdCounter = isset($tmpTrx['countersTrID']['stepCode|placeID']) ? "-" . digit_5($tmpTrx['countersTrID']['stepCode|placeID']) : "";
            //            cekHere($addTrIdCounter);
            $globalVars['nomers_prev'] = isset($tmpTrx[0]->nomer) ? formatField("nomer", $tmpTrx[0]->nomer . $addTrIdCounter) : "-";
        }


        // arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        $dpValueDetails = array();
        $dpFieldName = array();
        $dpValueDetailsINV = array();
        $dpFieldNameINV = array();
        if (isset($masterTableInValueParams['dp_value']) && $masterTableInValueParams['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams['dp_value'],
                "ppn_dp" => $masterTableInValueParams['dp_ppn_value'],
                "dp" => $masterTableInValueParams['dp'],
                "due_amount" => $masterTableInValueParams['tagihan'],
            );
            $dpFieldName = array(
                "dpp_dp" => "DPP Dp",
                "ppn_dp" => "vat Dp",
                "dp" => "Downpayment",
                "due_amount" => "Due amount",
            );

            $dpValueDetailsINV = array(
                "dp_saldo_awal" => $masterTableInValueParams['dp_saldo_awal'],
                "dp_dipakai" => $masterTableInValueParams['dp_dipakai'],
                //                "dp" => $masterTableInValueParams['dp'],
                "dp_saldo_akhir" => $masterTableInValueParams['dp_saldo_akhir'],
            );
            $dpFieldNameINV = array(
                "dp_saldo_awal" => "Saldo DP",
                "dp_dipakai" => "DP dipakai",
                //                "dp" => "Downpayment",
                "dp_saldo_akhir" => "Sisa DP",
            );
        }


        //endregion

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        // arrPrint($signValues);
        //endregion

        //region fixed element
        $elementHideFixedConfig = isset($this->configLayout[$this->jenisTr]['hideFixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['hideFixedElements'][$currentStepNum] : array();
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();
        //         arrPrint($elementFixedConfig);
        //
        //                        arrPrintPink($globalVars);
        //        die();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                if (is_array($label)) {
                    //                    cekHitam("masuk atas $label");
                    $dataHist = blobDecode($globalVars[$label['source']]);
                    $counters = blobDecode($dataHist[$label['step']]['counters']);
                    $countersUrut = array_values($counters['stepCode|placeID'])[0];
                    $countersUrut = "-" . digit_5($countersUrut);
                    $fixedElements['fixedElements']['contents'][$label['label']] = isset($dataHist[$label['step']][$label['target']]) ? formatField($label['target'], $dataHist[$label['step']][$label['target']]) . $countersUrut : "";
                    $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                }
                else {
                    //                    $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                    if (isset($globalVars[$key])) {
                        if (is_array($globalVars[$key])) {
                            if (sizeof($globalVars[$key])) {
                                $ii_result = "";
                                foreach ($globalVars[$key] as $ii => $ii_val) {
                                    if ($ii_result == "") {
                                        $ii_result = formatField($key, $ii_val);
                                    }
                                    else {
                                        $ii_result .= "<br>" . formatField($key, $ii_val);
                                    }
                                }
                                $fixedElements['fixedElements']['contents'][$label] = $ii_result;
                            }
                            else {
                                $fixedElements['fixedElements']['contents'][$label] = "";
                            }
                        }
                        else {
                            $fixedElements['fixedElements']['contents'][$label] = formatField($key, $globalVars[$key]);
                        }
                    }
                    else {
                        $fixedElements['fixedElements']['contents'][$label] = "";
                    }
                    $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                }
            }
            //            cekKuning($elementHideFixedConfig);
            if (sizeof($elementHideFixedConfig) > 0) {
                foreach ($elementHideFixedConfig as $hideSpec) {
                    $keyResult = isset($globalVars[$hideSpec['key']]) ? $globalVars[$hideSpec['key']] : "";
                    if (in_array($keyResult, $hideSpec['keyResult'])) {
                        //                        cekBiru("HILANGKAN/HIDE kan");
                        if (isset($hideSpec['label']) && (sizeof($hideSpec['label']) > 0)) {
                            foreach ($hideSpec['label'] as $kk => $vv) {
                                if (isset($fixedElements['fixedElements']['contents'][$vv])) {
                                    $fixedElements['fixedElements']['contents'][$vv] = NULL;
                                    unset($fixedElements['fixedElements']['contents'][$vv]);
                                }
                            }
                        }
                    }
                }
            }

        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($fixedElements['fixedElements']);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion

        //region add fields2 main
        $addMainData2Fields = isset($this->configLayout[$this->jenisTr]["receiptAddMain"][$row->step_number]) ? $this->configLayout[$this->jenisTr]["receiptAddMain"][$row->step_number] : array();
        $addMainData2 = array();
        if (sizeof($addMainData2Fields) > 0) {
            foreach ($addMainData2Fields as $keys => $keysLabel) {
                $addMainData2[$keys] = isset($globalVars[$keys]) ? $globalVars[$keys] : "";
            }
        }
        //endregion

        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            $in_word = "";
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }
        else {
            $in_word = "";
        }

        $in_word2 = "";

        if (sizeof($receiptInWordConfig2) > 0) {
            $this->load->helper("he_inword");

            foreach ($receiptInWordConfig2 as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {
                    $val_convert = $masterTableInValueParams[$fieldsSelected];
                    $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                    //                        $val_convert1=formatField("tagihan",$val_convert);
                    $val_convert1 = number_format(0 + $val_convert);

                    //                        echo $val_convert1;
                    $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                    //                        cekHitam($val_convert. " ||". $val_convert1);
                    $he_word2 = $he_loader($val_convert2, $currency);
                    //                        $he_word = $he_loader($val_convert, $currency);
                    $in_word2 .= "$he_word2";


                }
            }
        }
        else {
            $in_word = "";
        }


        //region load Mdl static note
        $this->load->model("Mdls/MdlStaticNotes");
        $sn = new MdlStaticNotes();
        $cbSelected = $globalVars['cabang_id'];
        $sn->addFilter("cabang_id=$cbSelected");
        $availNotes = $sn->lookupAll()->result();
        //                cekLime($this->db->last_query());
        $temNotes = array();
        if (sizeof($availNotes) > 0) {
            foreach ($availNotes as $tempNotes) {
                $jn = $tempNotes->jenis;
                $untuk = $tempNotes->untuk;
                $val = $tempNotes->nilai;
                $temNotes[$jn] = $val;
                $temNotes[$untuk] = $val;

            }
        }
        //endregion
        //arrPrintPink($temNotes);
        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : false;
        $listedDif = array("584");

        //region notes element
        //        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : "";
        if ($staticNotes) {
            switch ($tmpTr[0]->jenis_master) {
                case "582":
                    $notesfill = $temNotes["582"];
                    break;
                case "382":
                    $notesfill = $temNotes["382"];
                    break;
                case "463":
                    $notesfill = $temNotes["463"];
                    break;
                case "466":
                    $notesfill = $temNotes["466"];
                    break;
                case "3466":
                    $notesfill = $temNotes["3466"];
                    break;
                default:
                    $notesfill = "";
                    break;
            }

            // if ($tmpTr[0]->jenis_master == "382") {
            //                $notesfill = $temNotes["382"];
            // }
            // else {
            //                $notesfill = $temNotes["582"];
            // }

        }
        else {
            $notesfill = "";
        }

        $fixedElements['noteDetails'] = array();
        //        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] . $staticNotes : "$staticNotes";

        if (isset($globalVars['description'])) {
            $catatan = $globalVars['description'];
            $fixedElements['noteDetails']['contents'][] = $catatan;
        }
        if (isset($globalVars['description_additional']) && (sizeof($globalVars['description_additional']) > 0)) {
            $ii_rslt = "";
            foreach ($globalVars['description_additional'] as $ii => $iiVal) {
                if ($ii_rslt == "") {
                    $ii_rslt = "<br />" . $iiVal;
                }
                else {
                    $ii_rslt .= "<br />" . $iiVal;
                }
            }
            $catatan = $ii_rslt;
            $fixedElements['noteDetails']['contents'][] = $catatan;
        }

        $fixedElements['noteDetails']['contents'][] = "<br /><br />" . $notesfill;
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        // arrPrint($elements);
        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        // $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array("subtotal" => "subtotal");
        $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "subtotal");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
        }
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number] : array();
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceiptReg";

        //--------------------
        $receiptSumFieldsLayout = isset($this->configLayout[$tmpTr[0]->jenis_master]['receipSumFields'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receipSumFields'][$row->step_number] : array();
        $receiptSumFieldsLayoutReplacer = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumReplacer'] : array();
        if (sizeof($receiptSumFieldsLayoutReplacer) > 0) {

        }
        //--------------------
        $showCabangInvoice = isset($this->configLayout[$this->jenisTr]['showCabangInvoice'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['showCabangInvoice'][$currentStepNum] : true;
        //--------------------
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            arrPrintPink($shoppingCartSubDetailFields);
            $arrSubDetailDataKolom = array();
            foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                $gerbang = $kolomSpec["gate"];
                $source = $kolomSpec["source"] . "Registries";
                $source_new = $$source;
                $arrHasil = array();
                $hasil = "";
                foreach ($source_new as $sdata) {
                    $data = $sdata[$gerbang];
                    if ($arrHasil[$sdata["id"]] == "") {
                        $arrHasil[$sdata["id"]] = $data;
                    }
                    else {
                        $arrHasil[$sdata["id"]] .= ", $data";
                    }
                    //                    $arrHasil[$sdata["id"]] = $hasil;
                }

                $arrSubDetailDataKolom[$kolom] = $arrHasil;
            }
        }

        //endregion
        //arrPrintHijau($items);
        // arrPrintHijau($itemLabels);
        //          arrPrint($mainProp);
        $receiptSumFields = isset($this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum] : true;
        //        cekHijau($mode);
        $data = array(
            //            "mode"       => $this->uri->segment(2),
            "mode" => $mode,
            // "mode"                     => "viewReceiptReg",
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label_nota"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $main,
            "cb_nama" => "<div class='text-uppercase'>(" . $mainProp->cabang_nama . ")</div>",
            "detailValues" => $childTableInValueParams,
            "noteEnabled" => $noteEnabled,
            "itemLabels" => $itemLabels,
            "items" => $items,
            "itemLabels2" => isset($itemLabels2) ? $itemLabels2 : array(),
            "items2" => isset($items2_sumRegistries) ? $items2_sumRegistries : $items2,
            "itemsRegistries" => $itemsRegistries,
            "itemSrcLabel" => $itemSrcLabel,
            "itemSrc" => $itemSrc,
            "inWord" => $in_word,
            "inWord2" => $in_word2,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            "sumRows" => sizeof($receiptSumFieldsLayout) > 0 ? $receiptSumFieldsLayout : $receiptSumFields,
            "sumRows2" => $receiptSumFields2,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs, //UI
            "signHeader" => $signHeader,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
            "zeroAllowed" => $zeroAllowed,
            "dpFieldName" => $dpFieldName,
            "dpValueDetils" => $dpValueDetails,
            "mainData2Fields" => $addMainData2Fields,
            "mainData2" => $addMainData2,
            "showCabangInvoice" => $showCabangInvoice,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
        );

        $this->load->view("printing", $data);
    }

    public function viewReceiptReg()
    {
        // arrPrintHijau(url_segment());
        $userGroupCenter = $this->config->item("userGroup");
        $userGroupBranch = array_merge($this->config->item("userGroup_cabang"), $this->config->item("userGroup_gudang"));

        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookUpAll()->result();
        $indTransksiID = $tmp1[0]->id;
        $step_number = $tmp1[0]->step_number;
        $pembayaran_sys = $tmp1[0]->pembayaran_sys;
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $tmpTr = $tr->lookupJoined();
        // showLast_query("merah");
        // arrPrint($tmpTr);
        $cabang_id_transaksi = $tmpTr[0]->cabang_id;
        $replaceStepPic[$tmpTr[0]->step_number] = array(
            "olehID" => $tmpTr[0]->oleh_id,
            "olehNama" => $tmpTr[0]->oleh_nama,
        );
        //        arrPrint($replaceStepPic);

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();

        if (sizeof($tmpTr) > 0) {

            if (isset($_GET['mod']) && $_GET['mod'] == 1) {
                $this->jenisTr = $tmpTr[0]->jenis_master . "_mod";
                $qrCode_print = false;
            }
            else {
                $this->jenisTr = $tmpTr[0]->jenis_master;
                $qrCode_print = true;
            }
            //cekHere($this->jenisTr);

            $itemHadiahSrcFields = isset($this->configLayout[$this->jenisTr]['receiptHadiahFields']) ? $this->configLayout[$this->jenisTr]['receiptHadiahFields'] : array();
            $receiptItemHadiahKey = isset($this->configLayout[$this->jenisTr]['receiptItemHadiahKey']) ? $this->configLayout[$this->jenisTr]['receiptItemHadiahKey'] : array();
            //cekBiru($this->jenisTr);
            $trID = $tmpTr[0]->transaksi_id;
            // cekHere("$trID");
            // matiDisini(__LINE__);
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number <= 0 ? 1 : $tmpTr[0]->step_number;
            $mainJenis = $tmpTr[0]->transaksi_jenis2;
            $kirimMetodeID = $tmpTr[0]->kirim_metode_id;
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;

            $globalVars["pengirim_id"] = $pengirimID;
            $globalVars["pengirim_nama"] = $pengirimName;

            //                        cekHitam($this->jenisTr . " || $currentStepNum");
            $receiptDetailFields = $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            // $itemLabels = isset($this->configLayout[$this->jenisTr."_mod"]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr."_mod"]['receiptDetailFields'][$currentStepNum] : array();
            //            cekHitam($currentStepNum);
            $receiptNumFields = $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptNumFields'][$currentStepNum] : array();
            $receiptSumDetailFields = $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array();
            $receiptSumFieldsConf = isset($this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum] : array();
            //            $itemNumLabels2 = isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }
            // $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();

            //            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();
            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
            $pairReceiptItemRegistries = isset($this->configUi[$this->jenisTr]['pairReceiptItemRegistries']) ? $this->configUi[$this->jenisTr]['pairReceiptItemRegistries'] : array();
            $receiptInWordConfig2 = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword2'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword2'][$currentStepNum]['in_word'] : array();
            $receiptDetailSrcFields = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailSrcFields'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailSrcFields'][$currentStepNum] : array();
            $shoppingCartSubDetailFields = isset($this->configUi[$tmpTr[0]->jenis_master]['shoppingCartSubDetailFields'][$currentStepNum]) ? $this->configUi[$tmpTr[0]->jenis_master]['shoppingCartSubDetailFields'][$currentStepNum] : array();


            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID);
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID);
            //endregion
            $headerField = isset($this->configLayout[$this->jenisTr]['headerField']) ? $this->configLayout[$this->jenisTr]['headerField'] : "registry";
            //            cekHijau($receiptDetailFieldsReplacerConfig);
            //cekBiru($headerField);
            //arrPrintPink($itemLabels);
            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");

            $tmpTop = $tr->lookupMainTransaksi()->result();

            // $arrKey = array(
            //   $this->jenisTr
            // );
            // $cb = customCounters($tmpTr,$arrKey);
            // $cc = customCounters($tmpTop,"582pkd");
            // arrPrint($cc);
            // arrPrint($cb);
            // arrPrint($tmpTr);
            $id_prevs = $tmpTr[0]->ids_prev;
            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }
            // arrPrint($globalVars);
            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }
            //region switcher noyta pembatalan
            if (in_array($this->jenisTr, $this->jenisException)) {
                $injectedFields = $this->configLayout[$this->jenisTr]["receiptNumFields"][$currentStepNum];
                // arrPrint($injectedFields);
                // matiHEre($this->jenisTr);
            }
            else {
                $injectedFields = array();
            }


            //endregion
            //region take from registries
            $tmpReg = $tr->lookupDataRegistriesByMasterID($trID)->result();
            $mongoFields = $tr->getFields()["dataRegistry"];
            // arrPrint($tmpReg);
            // arrPrint($mongoFields);
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $itemsRegistries = array();
            $itemSrc = array();
            $receiptRsltItems = array();
            // arrPrint($tmpReg);
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($mongoFields as $param) {
                        if ($row->$param == null) {
                            $row->$param = blobEncode(array());
                        }
                        if (isset($row->$param)) {
                            switch ($param) {

                                case "tableIn_master"://
                                    $masterTableInParams = unserialize(base64_decode($row->$param));
                                    unset($masterTableInParams['dtime']);
                                    break;
                                case "tableIn_detail"://
                                    $childTableInParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_master_values"://
                                    $masterTableInValueParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "tableIn_detail_values"://
                                    $childTableInValueParams = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_add_values"://
                                    $masterAddValues = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_add_fields"://
                                    $masterAddFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "main_elements"://
                                    $mainElements = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptDetailFields"://
                                    $receiptDetailFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptSumFields"://
                                    $receiptSumFields = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptDetailFields2"://
                                    $receiptDetailFields2 = unserialize(base64_decode($row->$param));
                                    break;
                                case "receiptSumFields2"://
                                    $receiptSumFields2 = unserialize(base64_decode($row->$param));
                                    break;
                                case "items"://
                                    $itemsRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items2"://
                                    $items2Registries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items2_sum"://
                                    $items2_sumRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "items3_sum"://
                                    $items3_sumRegistries = unserialize(base64_decode($row->$param));
                                    break;
                                case "main"://
                                    $main = unserialize(base64_decode($row->$param));

                                    unset($main['dtime']);
                                    break;
                                case "itemSrc"://
                                    $itemSrc = unserialize(base64_decode($row->$param));
                                    break;
                                case "rsltItems":
                                    $receiptRsltItems = unserialize(base64_decode($row->$param));
                                    break;

                            }
                        }
                    }

                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion
            // matiHEre("stopppdulu brooo");
            //cekBiru(count($tmpTr));
            // cekBiru($mainElements);
            // cekBiru($receiptSumFields);
            //arrPrint($receiptDetailSrcFields);
            // arrPrintPink($main);
            // matiDisini(__LINE__);
            //             arrPrintPink($items3_sumRegistries);
            // arrPrintPink($receiptDetailFields);

            $sumItemsRslt = array();
            $valMainInject = 0;
            if (sizeof($injectedFields) > 0) {


                foreach ($receiptRsltItems as $receiptRsltItems_0) {
                    if (!isset($sumItemsRslt[$receiptRsltItems_0['id']]['sisa'])) {
                        $sumItemsRslt[$receiptRsltItems_0['id']]['sisa'] = 0;
                    }
                    if (!isset($sumItemsRslt[$receiptRsltItems_0['id']]['subtotal'])) {
                        $sumItemsRslt[$receiptRsltItems_0['id']]['subtotal'] = 0;
                    }
                    $sumItemsRslt[$receiptRsltItems_0['id']]['sisa'] += $receiptRsltItems_0['sub_hpp'];
                    $sumItemsRslt[$receiptRsltItems_0['id']]['subtotal'] += $receiptRsltItems_0['sub_hpp'];
                    $valMainInject += $receiptRsltItems_0['sub_hpp'];
                }

                $main['tagihan'] = $valMainInject;
                // arrPrint($valMainInject);
                // matiHere();
            }
            //matiHere();
            //            cekKuning($tmpTr[0]->jenis_master);
            //            cekKuning($mainJenis);

            //            matiHEre();
            /* -------------------------------------------------
            * switcher registry ke layout
            * -------------------------------------------------*/
            if ($headerField == "registry") {
                $itemLabels = sizeof($receiptDetailFields) > 0 ? $receiptDetailFields : $itemLabels;
                $mainElements = $mainElements;
            }
            else {
                $itemLabels = $itemLabels;
                $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
                $receiptSumFields = $receiptSumFieldsConf;
            }
            // arrPrintHijau($mainElements);
            // arrPrint($elementConfigs);
            //            $itemLabels = $receiptDetailFields;
            //            arrPrint($itemHadiahSrcFields);
            $itemHadiah = array();
            $itemLabels2 = $receiptDetailFields2;
            $itemSrcLabel = $receiptDetailSrcFields;
            if (($mainJenis == "paket") && ($tmpTr[0]->jenis_master == "1582")) {
                // cekBiru("MASUK ATAS");
                //                arrPrint($itemsRegistries);
                $rawItems = array();
                foreach ($itemsRegistries as $tmpItem) {
                    $tmpItemList = array();
                    //                    arrPrint($tmpItem);
                    //                    die();
                    foreach ($itemLabels as $key => $alias) {
                        $tmpItemList[$key] = $tmpItem[$key];
                    }
                    $injectParam = array(
                        "nett1_bulat" => $tmpItem['harga'],
                        "subtotal" => $tmpItem['subtotal'],
                    );
                    //                    cekMerah("ctak injector");
                    //                    arrPrint($injectParam);
                    $rawItems[] = $tmpItemList + $injectParam;
                }
            }
            else {
                foreach ($tmpTr as $row) {
                    $id = $row->produk_id;
                    //                    cekBiru($id);
                    $tmp = array();
                    if (isset($itemsRegistries[$id])) {
                        /**
                         * handling ui jika da hadiah
                         */
                        $src_key = $receiptItemHadiahKey["key"];
                        $src_key_value = $receiptItemHadiahKey["value"];
                        //cekHitam($src_key);
                        if (isset($itemsRegistries[$id][$src_key]) && $itemsRegistries[$id][$src_key] == $src_key_value) {
                            cekMErah(__LINE__);
                            $tmpHadiah = array();
                            foreach ($itemHadiahSrcFields as $k_h => $k_l) {
                                $tmpHadiah[$k_h] = $itemsRegistries[$id][$k_h];
                            }
                            $itemHadiah[$row->produk_id] = $tmpHadiah;
                        }
                        else {
                            if (sizeof($itemLabels) > 0) {
                                foreach ($itemLabels as $key => $val) {
                                    if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                                        $chars = explode("+", $key);
                                        $colValue = "";
                                        foreach ($chars as $key2) {
                                            $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                                        }
                                    }
                                    else {
                                        $colValue = isset($row->$key) ? $row->$key : "";
                                    }
                                    $tmp[$key] = $colValue;
                                    if (!isset($childTableInValueParams[$id][$key])) {
                                        $childTableInValueParams[$id][$key] = $colValue;
                                    }
                                }
                            }
                            if ($subAmountConfig != null) {
                                // cekLime("masukk" . $id);
                                $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                                $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                                //                         arrPrint($subAmountConfig);
                                $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                                //                        cekBiru($subtotal);
                            }
                            else {
                                $subtotal = 0;
                            }
                            // cekHere($subtotal . " --- " . $id);
                            //                    matiHEre();
                            if (isset($itemsRegistries[$id])) {
                                foreach ($itemLabels as $key => $labels) {
                                    if (isset($itemsRegistries[$id][$key])) {
                                        $tmp[$key] = $itemsRegistries[$id][$key];
                                    }
                                }
                            }
                            // arrPrint($tmp);

                            $tmp["subtotal"] = $subtotal;

                            $rawItems[$row->produk_id] = $tmp;
                            $rawItems[$row->produk_id] = array_replace(array_filter($childTableInValueParams[$row->produk_id]), array_filter($rawItems[$row->produk_id]));
                            // arrPrint($rawItems);

                            //arrPrint($receiptDetailFieldsReplacerConfig);
                            //                    matiHEre();
                            if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                                foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                                    if (array_key_exists($key, $rawItems[$row->produk_id])) {
                                        $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                                    }
                                }
                            }

                            //                                    arrprint($rawItems);
                            foreach ($availValueKeys as $ak) {
                                //                        echo "checking $ak: ";
                                if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                                    //                        echo "ada ";
                                    $valueKey = $ak;
                                }
                                else {
                                    //                        echo "none ";
                                }
                            }
                        }
                    }
                }
            }

        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }
        //        arrPrint($rawItems);
        // arrprint($itemLabels);
        $globalVars = array_merge($globalVars, $main);
        //        arrPrintPink($globalVars);


        /* ----------------------------------------------------------------------------
         * signature
         * ----------------------------------------------------------------------------*/
        //region signatures
        $signNumbers = array();
        $signValues_0 = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        // arrPrint($tmpSign);
        // arrPrintkuning($userGroupCenter);
        // matiHEre();
        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                // arrPrint($row);
                if ($cabang_id_transaksi == CB_ID_PUSAT) {
                    // cekHijau($row->group_code);
                    if (array_key_exists($row->group_code, $userGroupCenter)) {
                        $signValues_0['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                    else {
                        // cekBiru();
                        $signValues_0['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            // "contents"           => "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                }
                else {
                    // cekHitam("BAWAH " . __FILE__);
                    if (array_key_exists($row->group_code, $userGroupBranch)) {
                        $signValues_0['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                    else {
                        $signValues_0['sign_' . $row->step_number] = array(
                            "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                            "contents" => "",
                            "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                        );
                    }
                }
            }
            // arrPrint($signValues);
            foreach ($replaceStepPic as $stepTr => $replaceStepTmp) {
                if (isset($signValues_0['sign_' . $stepTr])) {
                    $signValues_0['sign_' . $stepTr]['contents'] = $replaceStepTmp['olehNama'];
                }
            }
        }
        //endregion
        // arrPrint($globalVars);
        // arrPrint($signValues_0);
        $gudang_status_jenis = $tmp1[0]->gudang_status_jenis;
        $gudang_status_id = $tmp1[0]->gudang_status_id;
        $cabang_id = $tmp1[0]->cabang_id;

        /* ----------------------------------------------------------------------------
         * engambilan nama ka logistik untuk masing2 cabang
         * ----------------------------------------------------------------------------*/
        // $gudang_spv = $gudang_status_jenis == "pusat" ? "c_gudang_spv" : "o_gudang_spv"; // normal
        $gudang_spv = $gudang_status_jenis == "pusat" ? "c_gudang_spv" : "c_gudang_spv"; // permintaan karena cabang hanya ada jkt semua dipegang martin

        $this->load->model("mdls/MdlEmployee");
        $em = new MdlEmployee();

        $emp = $em->callMembershipAll();
        // arrPrintHijau($emp[$gudang_spv]);
        $kaLogistiks = $emp[$gudang_spv];
        // ------------------------------------temporary handling jika ada lebih dari 1 yg punya  gudang_spv
        $allowed_ids['c_gudang_spv'] = 982;
        $allowed_ids['o_gudang_spv'] = 982;
        //--------------------------------------------------------------------------------------------------
        $allowed_id = isset($allowed_ids[$gudang_spv]) ? $allowed_ids[$gudang_spv] : 0;
        if (count($kaLogistiks) == 1) {
            foreach ($kaLogistiks as $kaLogistik) {
                $nama_objek = $kaLogistik['nama'];
            }
        }
        else {
            // cekHijau("ada : " . count($kaLogistiks));
            foreach ($kaLogistiks as $ka_id => $kaLogistik) {

                if ($ka_id == $allowed_id) {
                    $nama_objek = $kaLogistik['nama'];
                    break;
                }
                else {
                    $nama_objek = "";
                }
            }

        }
        // ---------------------------------------------------------------------------------end
        // cekLime(__LINE__ . " $nama_objek");

        $signValues = array();
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                // $nama_objek = "";
                if ($key == "logistik") {
                    // cekLime(CB_ID_PUSAT ."//////". my_cabang_id() . " $gudang_status_jenis $gudang_status_id");
                    $nama_objeke = $nama_objek;
                }
                else {
                    $nama_objeke = "";
                }
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }

                // cekHere("$key");
                if (array_key_exists($key, $signValues_0)) {
                    $signValues[$key] = $signValues_0[$key];
                }
                else {

                    $signValues[$key]['label'] = $label;
                    $signValues[$key]['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : $nama_objeke;
                    $signValues[$key]['caption_department'] = "";
                }
            }
        }


        // arrPrint($signValues);
        /*----------status lunas/belum lunas----------*/
        $pembayaran_status = "";
        if ($step_number == 2 && $pembayaran_sys = 'cash') {

            // cekHitam($indTransksiID);
            $srcPayments = $trs->lookupPaymentSrcByTransID($indTransksiID)->row();
            $pm_sisa = $srcPayments->sisa;
            $pm_sisa_f = number_format($pm_sisa);
            // showLast_query("kuning");
            // arrPrintHijau($srcPayments);
            // cekHere(count($srcPayments));
            $strStatus = "";
            if (count($srcPayments) == 1) {
                $strStatus .= "status ";
                $strStatus .= $pm_sisa == 0 ? ": <b>LUNAS</b>" : " kurang : <b>$pm_sisa_f</b>";
            }
            else {
                $strStatus .= ".";
            }
            $pembayaran_status .= "<div id='status_pembayaran'>";
            $pembayaran_status .= $strStatus;
            // $pembayaran_status .= $pm_sisa == 0 ? "<b>LUNAS</b>" : "<b>$pm_sisa</b>";
            $pembayaran_status .= "</div>";
        }
        elseif ($step_number == 2 && $pembayaran_sys == 'credit') {
            $trs->setFilters(array());
            $trs->addFilter("extern2_id=$indTransksiID");
            $trs->setTableName('transaksi_payment_source');
            $srcPaymentsUmuka = $trs->lookupAll()->row();
            // showLast_query("hijau");
            // arrPrint($srcPaymentsUmuka);
            $dpp_ppnUmuka = $srcPaymentsUmuka->dpp_ppn;
            $ppnUmuka = $srcPaymentsUmuka->ppn;
            $totalUmuka = $dpp_ppnUmuka + $ppnUmuka;
            $main['pym_src_ppn_dipakai'] = $ppnUmuka;
            $main['pym_src_total_dipakai'] = $totalUmuka;
        }
        // -------------------------------------------------------------------------------end

        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;
        // arrPrintwebs($items);
        // arrPrintwebs($main);
        $arrKombinasi = array(
            "nett1" => array(
                "new_ky" => "nett1",
                "new_label" => "dpp",
                "attr" => "class='lysimpel'",
            ),
            "xppn" => array(
                "old_ky" => "nett1",
                "new_ky" => "xppn",
                "new_label" => "ppn",
                "new_nilai" => 'ppn',
                "attr" => "class='lysimpel'",
            ),
            "nett1nppn" => array(
                "old_ky" => "nett1",
                "new_ky" => "nett1nppn",
                "new_label" => "harga",
                "new_nilai" => "nett1nppn",
            ),
            "sub_nett1" => array(
                "new_ky" => "sub_nett1",
                "new_label" => "jumlah",
                "new_nilai" => "subnett1nppn",
            ),
            // "produk_kode" => array(
            //     "new_ky"    => "produk_kode",
            //     "new_label" => "sku",
            //     "attr"      => "class='lysku'",
            // ),
        );

        if ($currentStepNum == 3) {
            $arrKombinasi['nett1nppn']['attr'] = "class='lysimpel'";
            $arrKombinasi['sub_nett1']['attr'] = "class='lysimpel'";
            $arrKombinasi['produk_kode']['attr'] = "class='lysku'";
        }

        /* ----------------------------------------------------------------- -----------------------------------
         * transaksi sebelum tgl (25 nop 2024) tidak punya gerbang ppnFactorDesimal/Include jadi dipastikan ppn 11%
         * ----------------------------------------------------------------- ----------------------------------*/
        $ppnFactorDesimal = isset($main["ppnFactorDesimal"]) ? $main["ppnFactorDesimal"] : 0.11;
        $ppnFactorInclude = isset($main["ppnFactorInclude"]) ? $main["ppnFactorInclude"] : 1.11;
        $itemNumLabels = array();
        $itemSubTotal = array();
        foreach ($rawItems as $pids => $item) {
            // arrPrintKuning($item);
            foreach ($arrKombinasi as $old_ky => $new_items) {

                // arrPrint($new_items);
                $old_ky2 = isset($new_items['old_ky']) ? $new_items['old_ky'] : $old_ky;
                $new_ky = $new_items['new_ky'];
                $new_label = $new_items['new_label'];
                $attr = isset($new_items['attr']) ? $new_items['attr'] : '';

                $old_nilai1 = $item[$old_ky2];
                $old_qty = $item['jml'];
                $new_nilai = isset($new_items['new_nilai']) ? $old_nilai1 . $new_items['new_nilai'] : $old_nilai1;
                if (isset($new_items['new_nilai'])) {
                    switch ($new_items['new_nilai']) {
                        case "ppn":
                            $new_nilai = $old_nilai1 * $ppnFactorDesimal;
                            break;
                        case "nett1nppn":
                            $new_nilai = $old_nilai1 * $ppnFactorInclude;
                            break;
                        case "subnett1nppn":
                            $new_nilai = ($old_nilai1 * $ppnFactorInclude);
                            //                            $new_nilai = 9999999999;
                            break;
                        default:
                            $new_nilai = $old_nilai1 * $ppnFactorInclude;
                            break;
                    }
                }
                else {
                    $new_nilai = $old_nilai1;
                }

                //                if (isset($new_items['shadow_nilai'])) {
                //                    switch ($new_items['shadow_nilai']) {
                //                        case "subnett1nppn":
                //                            $shadow_nilai = $old_nilai1 * 1.11 * $old_qty;
                //                            break;
                //                        default:
                //                            $shadow_nilai = $old_nilai1 * 1.11;
                //                            break;
                //                    }
                //                }
                //                else {
                //                    $new_nilai = $old_nilai1;
                //                }

                // cekHere("$new_nilai");

                //                $items[$item['id']][$new_ky] = $new_nilai;
                $items[$pids][$new_ky] = $new_nilai;

                $itemNumLabels[$new_ky]['label'] = $new_label;
                $itemNumLabels[$new_ky]['attr'] = $attr;
            }
        }

        //region injector jika pembatalan melibatkan fifo
        if (sizeof($sumItemsRslt) > 0) {
            $valMainInject = 0;
            foreach ($rawItems as $pid => $pidData) {
                if (isset($sumItemsRslt[$pid])) {
                    foreach ($sumItemsRslt[$pid] as $kKey => $value_rslt) {
                        // cekMerah($pidData['produk_nama']."".$kKey."---".$value_rslt);
                        $items[$pid][$kKey] = $value_rslt;
                        $itemsRegistries[$pid][$kKey] = $value_rslt;
                    }
                }

            }

        }

        //endregion

        //region replace main labels with properties from future/next step

        $mainProp = $tmpTr[0];
        $globalVars = array_merge($globalVars, (array)$mainProp, $main);
        //        arrPrint((array)$mainProp);
        //        arrPrintWebs($globalVars);
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->row();
        // showLast_query("merah");
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion

        // arrPrint($arrCompanyProfile);

        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                // cekHitam($eKey);
                //                arrPrint($eSpec);
                //                                cekHitam($eKey);
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHijau($relElementType);
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    // arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);

                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key ");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                    //                                $vTmp["nik"]="45245829";
                                    //                                    arrPrint($val);
                                    //                                                                arrPrint($vTmp);
                                    //                                                                cekBiru($key);
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        //                                        cekKuning($key."||".$val);
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    // cekHitam($vTmp);
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }


                $elements[$eKey] = $eTmp;
            }
            //            arrPrint($elements);
        }


        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams);

        //        arrPrint($globalVars);
        //        arrPrint($masterTableInParams);
        //        arrPrint();

        /** ---------------------------------------------------------
         * counter global
         * ---------------------------------------------------------*/
        $counterGlobalOverride = null;
        if (!empty($this->configUiJenis['counter_global'])) {
            $key = $this->configUiJenis['counter_global'];
            $counterGlobalOverride = isset($masterTableInParams[$key]) ? "-" . digit_5($masterTableInParams[$key]) : null;
        }

        if (isset($globalVars['nomer'])) {
            $addTrIdCounterOld = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $addTrIdCounter = ($counterGlobalOverride !== null) ? $counterGlobalOverride : $addTrIdCounterOld;

            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        if (isset($globalVars['nomer_top'])) {
            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
            // $num_top = showHistoriGlobalNumbers($globalVars["ids_his"],1);
            // $globalVars['nomer_top'] = $num_top."**";
        }
        else {
            $globalVars['nomer_top'] = "-";
        }


        if (isset($globalVars["ids_his"])) {
            $stepPrev = $globalVars["step_number"] - 1;
            if ($stepPrev > 0) {
                // $cHist = blobDecode($globalVars["ids_his"])[$stepPrev];
                // $counterhists = blobDecode($cHist["counters"]);
                // $cNomer = $cHist["nomer"];
                // $cNomerExpl = explode(".", $cNomer);
                // $cTrcode = $cNomerExpl[0];
                // $cTrplace = $cNomerExpl[1];
                // $cgJenis = "$cTrcode|$cTrplace";
                //
                // $cgHist = digit_5($counterhists["stepCode|placeID"]["$cgJenis"]);
                //
                // $nomer_prev = $cNomer . "-" . $cgHist;

                // $globalVars['nomers_prev'] = formatField("nomer", $nomer_prev);

                //                arrPrint($stepPrev);
                $globalVars['nomers_prev'] = showHistoriGlobalNumbers($globalVars["ids_his"], $stepPrev, true);
            }
            else {
                $globalVars['nomers_prev'] = "-";
            }
        }
        else {
            $ids_prev = blobDecode($tmpTr[0]->ids_prev);
            // arrPrint($tmpTr[0]->ids_prev);
            $trx = new MdlTransaksi();
            $trx->setFilters(array());
            $trx->addFilter("id='" . $ids_prev[0] . "'");
            $tmpTrx = $trx->lookupMainTransaksi()->result();
            $counterTrID = blobDecode($tmpTrx[0]->counters);
            foreach ($counterTrID as $key_d => $val_d) {
                foreach ($val_d as $val_dd) {
                    $tmpTrx['countersTrID'][$key_d] = $val_dd;
                }
            }
            //            arrPrint($tmpTrx);
            $addTrIdCounter = isset($tmpTrx['countersTrID']['stepCode|placeID']) ? "-" . digit_5($tmpTrx['countersTrID']['stepCode|placeID']) : "";
            //            cekHere($addTrIdCounter);
            $globalVars['nomers_prev'] = isset($tmpTrx[0]->nomer) ? formatField("nomer", $tmpTrx[0]->nomer . $addTrIdCounter) : "-";
        }


        // arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        $dpValueDetails = array();
        $dpFieldName = array();
        $dpValueDetailsINV = array();
        $dpFieldNameINV = array();
        if (isset($masterTableInValueParams['dp_value']) && $masterTableInValueParams['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams['dp_value'],
                "ppn_dp" => $masterTableInValueParams['dp_ppn_value'],
                "dp" => $masterTableInValueParams['dp'],
                "due_amount" => $masterTableInValueParams['tagihan'],
            );
            $dpFieldName = array(
                "dpp_dp" => "DPP Dp",
                "ppn_dp" => "vat Dp",
                "dp" => "Downpayment",
                "due_amount" => "Due amount",
            );

            $dpValueDetailsINV = array(
                "dp_saldo_awal" => $masterTableInValueParams['dp_saldo_awal'],
                "dp_dipakai" => $masterTableInValueParams['dp_dipakai'],
                //                "dp" => $masterTableInValueParams['dp'],
                "dp_saldo_akhir" => $masterTableInValueParams['dp_saldo_akhir'],
            );
            $dpFieldNameINV = array(
                "dp_saldo_awal" => "Saldo DP",
                "dp_dipakai" => "DP dipakai",
                //                "dp" => "Downpayment",
                "dp_saldo_akhir" => "Sisa DP",
            );
        }


        //endregion

        //region fixed element
        $receiptElements = isset($this->configLayout[$this->jenisTr]['receiptElements']) ? $this->configLayout[$this->jenisTr]['receiptElements'] : array();
        $elementHideFixedConfig = isset($this->configLayout[$this->jenisTr]['hideFixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['hideFixedElements'][$currentStepNum] : array();
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();

        if (count($elementHideFixedConfig) > 0) {
            $elementFixedConfig = array_diff_key($elementFixedConfig, $elementHideFixedConfig);
        }
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                if (is_array($label)) {
                    //                    cekHitam("masuk atas $label");
                    $dataHist = blobDecode($globalVars[$label['source']]);
                    $counters = blobDecode($dataHist[$label['step']]['counters']);
                    $countersUrut = array_values($counters['stepCode|placeID'])[0];
                    $countersUrut = "-" . digit_5($countersUrut);
                    $fixedElements['fixedElements']['contents'][$label['label']] = isset($dataHist[$label['step']][$label['target']]) ? formatField($label['target'], $dataHist[$label['step']][$label['target']]) . $countersUrut : "";
                    $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                }
                else {
                    //                    $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                    if (isset($globalVars[$key])) {
                        if (is_array($globalVars[$key])) {
                            if (sizeof($globalVars[$key])) {
                                $ii_result = "";
                                foreach ($globalVars[$key] as $ii => $ii_val) {
                                    if ($ii_result == "") {
                                        $ii_result = formatField($key, $ii_val);
                                    }
                                    else {
                                        $ii_result .= "<br>" . formatField($key, $ii_val);
                                    }
                                }
                                $fixedElements['fixedElements']['contents'][$label] = $ii_result;
                            }
                            else {
                                $fixedElements['fixedElements']['contents'][$label] = "";
                            }
                        }
                        else {
                            $fixedElements['fixedElements']['contents'][$label] = formatField($key, $globalVars[$key]);
                        }
                    }
                    else {
                        $fixedElements['fixedElements']['contents'][$label] = "";
                    }
                    // $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                    $fixedElements['fixedElements']['label'] = "data invoice";
                }
            }
            //            cekKuning($elementHideFixedConfig);
            if (sizeof($elementHideFixedConfig) > 0) {
                foreach ($elementHideFixedConfig as $hideSpec) {
                    $keyResult = isset($globalVars[$hideSpec['key']]) ? $globalVars[$hideSpec['key']] : "";
                    if (in_array($keyResult, $hideSpec['keyResult'])) {
                        //                        cekBiru("HILANGKAN/HIDE kan");
                        if (isset($hideSpec['label']) && (sizeof($hideSpec['label']) > 0)) {
                            foreach ($hideSpec['label'] as $kk => $vv) {
                                if (isset($fixedElements['fixedElements']['contents'][$vv])) {
                                    $fixedElements['fixedElements']['contents'][$vv] = NULL;
                                    unset($fixedElements['fixedElements']['contents'][$vv]);
                                }
                            }
                        }
                    }
                }
            }

        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($fixedElements['fixedElements']);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion

        //region add fields2 main
        $addMainData2Fields = isset($this->configLayout[$this->jenisTr]["receiptAddMain"][$row->step_number]) ? $this->configLayout[$this->jenisTr]["receiptAddMain"][$row->step_number] : array();
        $addMainData2 = array();
        if (sizeof($addMainData2Fields) > 0) {
            foreach ($addMainData2Fields as $keys => $keysLabel) {
                $addMainData2[$keys] = isset($globalVars[$keys]) ? $globalVars[$keys] : "";
            }
        }
        //endregion

        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            $in_word = "";
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }
        else {
            $in_word = "";
        }

        $in_word2 = "";

        if (sizeof($receiptInWordConfig2) > 0) {
            $this->load->helper("he_inword");

            foreach ($receiptInWordConfig2 as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {
                    $val_convert = $masterTableInValueParams[$fieldsSelected];
                    $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                    //                        $val_convert1=formatField("tagihan",$val_convert);
                    $val_convert1 = number_format(0 + $val_convert);

                    //                        echo $val_convert1;
                    $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                    //                        cekHitam($val_convert. " ||". $val_convert1);
                    $he_word2 = $he_loader($val_convert2, $currency);
                    //                        $he_word = $he_loader($val_convert, $currency);
                    $in_word2 .= "$he_word2";


                }
            }
        }
        else {
            $in_word = "";
        }


        //region load Mdl static note
        $this->load->model("Mdls/MdlStaticNotes");
        $sn = new MdlStaticNotes();
        $cbSelected = $globalVars['cabang_id'];
        $sn->addFilter("cabang_id=$cbSelected");
        $availNotes = $sn->lookupAll()->result();
        //                cekLime($this->db->last_query());
        $temNotes = array();
        if (sizeof($availNotes) > 0) {
            foreach ($availNotes as $tempNotes) {
                $jn = $tempNotes->jenis;
                $untuk = $tempNotes->untuk;
                $val = $tempNotes->nilai;
                $temNotes[$jn] = $val;
                $temNotes[$untuk] = $val;

            }
        }
        //endregion
        //arrPrintPink($temNotes);
        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : false;
        $listedDif = array("584");

        //region notes element
        //        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : "";
        if ($staticNotes) {
            switch ($tmpTr[0]->jenis_master) {
                case "582":
                    $notesfill = $temNotes["582"];
                    break;
                case "382":
                    $notesfill = $temNotes["382"];
                    break;
                case "463":
                    $notesfill = $temNotes["463"];
                    break;
                case "466":
                    $notesfill = $temNotes["466"];
                    break;
                case "3466":
                    $notesfill = $temNotes["3466"];
                    break;
                default:
                    $notesfill = "";
                    break;
            }

            // if ($tmpTr[0]->jenis_master == "382") {
            //                $notesfill = $temNotes["382"];
            // }
            // else {
            //                $notesfill = $temNotes["582"];
            // }

        }
        else {
            $notesfill = "";
        }

        $fixedElements['noteDetails'] = array();
        //        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] . $staticNotes : "$staticNotes";

        if (isset($globalVars['description'])) {
            $catatan = $globalVars['description'];
            $fixedElements['noteDetails']['contents'][] = $catatan;
        }
        if (isset($globalVars['description_additional']) && (sizeof($globalVars['description_additional']) > 0)) {
            $ii_rslt = "";
            foreach ($globalVars['description_additional'] as $ii => $iiVal) {
                if ($ii_rslt == "") {
                    $ii_rslt = "<br />" . $iiVal;
                }
                else {
                    $ii_rslt .= "<br />" . $iiVal;
                }
            }
            $catatan = $ii_rslt;
            $fixedElements['noteDetails']['contents'][] = $catatan;
        }

        $fixedElements['noteDetails']['contents'][] = "<br/><br/>" . $notesfill;
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        // arrPrint($elements);
        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        // $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        //         arrPrintKuning($itemLabels);
        //         arrPrintKuning($itemNumLabels);
        // arrPrintKuning($itemSubTotal);
        //         arrPrintKuning($receiptDetailFields);

        // $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        $itemLabels = $receiptDetailFields + $receiptNumFields + $receiptSumDetailFields;
        //        arrPrintWebs($itemLabels);
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "subtotal");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
        }
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number] : array();
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceiptReg";

        //--------------------
        // $receiptSumFieldsLayout = isset($this->configLayout[$tmpTr[0]->jenis_master]['receipSumFields'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receipSumFields'][$row->step_number] : array();
        $receiptSumFieldsLayoutReplacer = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumReplacer'] : array();
        if (sizeof($receiptSumFieldsLayoutReplacer) > 0) {

        }
        //--------------------
        $showCabangInvoice = isset($this->configLayout[$this->jenisTr]['showCabangInvoice'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['showCabangInvoice'][$currentStepNum] : true;
        //--------------------
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            arrPrintPink($shoppingCartSubDetailFields);
            $arrSubDetailDataKolom = array();
            foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                $gerbang = $kolomSpec["gate"];
                $source = $kolomSpec["source"] . "Registries";
                $source_new = $$source;
                $arrHasil = array();
                $hasil = "";
                foreach ($source_new as $sdata) {
                    $data = $sdata[$gerbang];
                    if ($arrHasil[$sdata["id"]] == "") {
                        $arrHasil[$sdata["id"]] = $data;
                    }
                    else {
                        $arrHasil[$sdata["id"]] .= ", $data";
                    }
                    //                    $arrHasil[$sdata["id"]] = $hasil;
                }

                $arrSubDetailDataKolom[$kolom] = $arrHasil;
            }
        }

        //endregion
        // arrPrintHijau($mainElements);
        // arrPrintHijau($elementConfigs);
        // arrPrintHijau($elements);
        // arrPrintHijau($items);
        //         arrPrintHijau($itemLabels);
        //          arrPrint($mainProp);
        // $receiptSumFields = isset($this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum] : true;
        // $receiptSumFields = isset($this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum] : true;
        //        cekHijau($mode);
        //--------------------

        if (sizeof($shoppingCartSubDetailFields) > 0) {
            $arrSubDetailDataKolom = array();
            foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                //                arrPrint($kolomSpec);
                $gerbang = $kolomSpec["gate"];
                $source = $kolomSpec["source"] . "Registries";
                $source_new = $$source;
                //                cekHere($source);
                $arrHasil = array();
                $hasil = "";
                foreach ($source_new as $sdata) {
                    $key_data_id = $sdata["id"];
                    $key_data = $key_data_number = $sdata["produk_sku_part_nama"];
                    if (in_array($key_data, $itemsRegistries[$key_data_id])) {
                        $anu_flip = array_flip($itemsRegistries[$key_data_id]);
                        $key_data_arr = explode("_", $anu_flip[$key_data]);
                        $key_data = $key_data_arr[0] . "($key_data_number)";
                    }
                    $data = $sdata[$gerbang];
                    if ($arrHasil[$sdata["id"]][$key_data] == "") {
                        $arrHasil[$sdata["id"]][$key_data] = $data;
                    }
                    else {
                        $arrHasil[$sdata["id"]][$key_data] .= ", $data";
                    }

                }

                $arrSubDetailDataKolom[$kolom] = $arrHasil;
            }
        }

        //---- jika penjualan ini dikirim
        if (($kirimMetodeID == 1) && ($currentStepNum < 4)) {
            if ($qrCode_print == true) {
                $this->load->library("Ciqrcode");
                $qr = new Ciqrcode();
                $qrcode = $qr->get_qrcode_umum("$trID");
                $qrfile = base_url() . $qrcode['file'];
                $qrcode_f = "<img src='$qrfile' title='$xID' class='img-thumbnail' style='width:125px;' oonclick=\"popBig('$link_print');\">";
                $qrCodeNota = "<div class='text-center'>";
                $qrCodeNota .= $qrcode_f;
                $qrCodeNota .= "</div>";
            }
            else {
                $qrCodeNota = "";
            }
        }
        else {
            $qrCodeNota = "";
        }
        //arrPrint($signValues);
        //        arrprint($arrSubDetailDataKolom);
        //        matiHere();
        // cekHere($this->configUi[$jenisTr]["steps"][$currentStepNum]["label_nota"]);
        //        arrPrintWebs($receiptSumFields);
        //        arrPrintWebs($main);

        $showGudangStatus = isset($this->configLayout[$jenisTr]["showGudangStatus"][$currentStepNum]) ? $this->configLayout[$jenisTr]["showGudangStatus"][$currentStepNum] : false;
        $gudang_status = "";
        if ($showGudangStatus == true) {
            $gudang_status .= "<div id='status_pembayaran'>";
            $gudang_status .= "PENGAMBILAN BARANG di GUDANG " . $globalVars["gudangStatusDetails__nama"];
            $gudang_status .= "</div>";
        }

        // if(ipadd() != "202.65.117.72"){
        if (isset($_GET['fix']) && $_GET['fix'] > 1) {

            $step_number = $_GET['fix'];
            // $step_number = 7;
        }

        // cekHere($this->jenisTr);
        // cekHere($step_number);
        $confLayouts = $this->configLayout[$this->jenisTr];
        $receiptView = $confLayouts["receiptView"];
        // arrPrint($receiptView);
        $receiptViewDefaults = $receiptView["default"];
        $receiptViewDefault = $receiptViewDefaults['viewer'];
        $receiptViewDefaultTpl = $receiptViewDefaults['template'];


        $receiptViewConfs = isset($receiptView[$step_number]) ? $receiptView[$step_number] : "";
        $receiptViewConf = $receiptViewConfs['viewer'];
        $receiptViewConfTpl = $receiptViewConfs['template'];

        $mode = isset($receiptView[$step_number]) ? $receiptViewConf : $receiptViewDefault;

        $template = isset($receiptView[$step_number]) ? $receiptViewConfTpl : $receiptViewDefaultTpl;

        if ((isset($_GET['tpl']) && (strlen($_GET['tpl']) > 5))) {
            $template = "template/" . $_GET['tpl'] . ".html";
        }

        // cekHere($template);
        // arrPrint($itemLabels);
        // arrPrintKuning($items);
        //         cekHijau("$receiptViewConf $mode");
        // matiHere(__LINE__);
        // $template = $confLayouts["receiptTemplate"][$step_number];
        $oldTemplate = $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum];
        //        arrPrint($main);
        $tipe_penjualan = $main["pihakTipeID"];
        $marketplace_nama = $main["pihakMarketplaceName"];

        //        cekHere();
        //        cekHere();
        //        cekHere();

        //        arrprint($itemLabels);
        //        arrprint($this->configLayout[$jenisTr]["receiptMainFields"]);

        $receiptCustomMembership = isset($this->configLayout[$jenisTr]["receiptCustomMembership"]) ? $this->configLayout[$jenisTr]["receiptCustomMembership"] : array();
        $receiptDetailFieldsCustom = isset($this->configLayout[$jenisTr]["receiptDetailFieldsCustom"][$currentStepNum]) ? $this->configLayout[$jenisTr]["receiptDetailFieldsCustom"][$currentStepNum] : array();
        $receiptSumFieldsCustom = isset($this->configLayout[$jenisTr]["receiptSumFieldsCustom"][$currentStepNum]) ? $this->configLayout[$jenisTr]["receiptSumFieldsCustom"][$currentStepNum] : array();
        $counterGlobalShow = isset($this->configUiJenis["counter_global_show"]) ? $this->configUiJenis["counter_global_show"] : false;
        // arrPrint($this->configUiJenis);
        if (sizeof($receiptCustomMembership) > 0) {
            foreach ($receiptCustomMembership as $membership) {
                if (in_array($membership, $this->session->login["membership"])) {
                    if (sizeof($receiptDetailFieldsCustom) > 0) {
                        $itemLabels = $receiptDetailFieldsCustom;
                        $receiptSumFields = $receiptSumFieldsCustom;
                    }
                }
            }
        }

        cekHere("[mode: $mode] [template: $template] [jenisTr: $jenisTr]");
        $receiptKeyIsiTambahan = isset($this->configLayout[$jenisTr]["receiptKeyIsiTambahan"][$currentStepNum]) ? $this->configLayout[$jenisTr]["receiptKeyIsiTambahan"][$currentStepNum] : array();
        $receiptKeyTambahan = isset($this->configLayout[$jenisTr]["receiptKeyTambahan"][$currentStepNum]) ? $this->configLayout[$jenisTr]["receiptKeyTambahan"][$currentStepNum] : array();
        $receiptPolosConfig = isset($this->configLayout[$jenisTr]["receiptPolos"][$currentStepNum]) ? $this->configLayout[$jenisTr]["receiptPolos"][$currentStepNum] : array();
        $receiptPolos = false;
        if (sizeof($receiptPolosConfig) > 0) {
            if (isset($receiptPolosConfig["enabled"]) && ($receiptPolosConfig["enabled"] == true)) {
                $template = $receiptPolosConfig["template"];
                $receiptPolos = $receiptPolosConfig["enabled"];
            }
        }


        $data = array(
            //            "mode"       => $this->uri->segment(2),
            // "mode"                     => $mode,
            // "mode" => "viewReceiptReg_mod",
            "mode" => $mode,
            "template" => $template,
            "confLayout" => $this->configLayout[$jenisTr],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label_nota"],
            "title_alt" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label_nota_alt"],
            "jenisTr" => $jenisTr,
            "companyProfile" => $arrTmpCompany,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            // "transaksi" => $tmpTop[0],
            "transaksi" => $tmp1[0],
            "main" => $mainProp,
            "mainValues" => $main,
            "pembayaran_status" => $pembayaran_status,
            "shippingMethod" => $main['shippingMethod'],
            "itemLabels" => $itemLabels,
            "items" => $items,
            "itemsRegistries" => $itemsRegistries,
            "receiptSumFieldsLayout" => $receiptSumFields,
            "signValues" => $signValues,
            "fixedSignConfig" => $fixedSignConfig,
            "elementFixedConfig" => $elementFixedConfig,
            "fixedElements" => $fixedElements,
            "elementHideFixedConfig" => $elementHideFixedConfig,
            "mainElements" => $mainElements,
            "elementConfigs" => $elementConfigs,
            "layoutElementConfigs" => isset($receiptElements) ? $receiptElements : array(),
            // "elementFixedConfig"     => $elementFixedConfig,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "qrCodeNota" => isset($qrCodeNota) ? $qrCodeNota : "",
            "gudang_status" => isset($gudang_status) ? $gudang_status : "",
            "strStatus" => $strStatus,
            "kirimMetodeID" => $kirimMetodeID,
            // ----------------------
            "pihakTipeID" => $tipe_penjualan,
            "pihakMarketplaceName" => $marketplace_nama,
            "itemHadiahFields" => $itemHadiahSrcFields,
            "itemHadiah" => $itemHadiah,
            "counterGlobalShow" => $counterGlobalShow,

            "receiptPolos" => $receiptPolos,
            "receiptKeyTambahan" => $receiptKeyTambahan,
            "receiptKeyIsiTambahan" => $receiptKeyIsiTambahan,

        );

        $this->load->view("printing", $data);
    }

    public function viewReceiptCashIn()
    {

        //        die();
        $globalVars = array();
        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tr->addFilter("transaksi_data.trash='0'");
        $tmpTr = $tr->lookupJoined()->result();
        //        cekHitam($this->db->last_query());
        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview

        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number <= 0 ? 1 : $tmpTr[0]->step_number;
            //cekHitam($currentStepNum);
            // $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            //            cekHitam($currentStepNum);
            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum];
            //            $itemNumLabels2 = isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }
            // $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();

            //            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();
            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
            $pairReceiptItemRegistries = isset($this->configUi[$this->jenisTr]['pairReceiptItemRegistries']) ? $this->configUi[$this->jenisTr]['pairReceiptItemRegistries'] : array();
            $receiptDetailFields = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFields'][$currentStepNum] : array();
            $receiptDetailFields2 = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFields2'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFields2'][$currentStepNum] : array();
            $receiptSumFields2 = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptOverWriteSum'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptOverWriteSum'][$currentStepNum] : $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFields'][$currentStepNum];
            //           cekBiru($tmpTr[0]->jenis_master." ".$currentStepNum);
            //           arrPrint($receiptDetailFields);
            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            //endregion

            //            cekHijau($receiptDetailFieldsReplacerConfig);


            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");
            $tmpTop = $tr->lookupMainTransaksi()->result();
            //            cekHere($this->db->last_query());

            // $arrKey = array(
            //   $this->jenisTr
            // );
            // $cb = customCounters($tmpTr,$arrKey);
            // $cc = customCounters($tmpTop,"582pkd");
            // arrPrint($cc);
            // arrPrint($cb);
            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }
            // arrPrint($globalVars);
            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }
            //region take from registries

            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptSumFields = array();
            $itemsRegistries = array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {

                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($row->values));
                            break;
                        case "items"://
                            $itemsRegistries = unserialize(base64_decode($row->values));
                            break;
                        case "main"://
                            $main = unserialize(base64_decode($row->values));
                            break;

                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            $refIDs = implode("", $main['refIDs']);
            $itemsRegistries2 = array();
            $childTableInValueParams2 = array();
            $main2 = array();
            $tmpTri = array();
            if (strlen($refIDs) > 0) {
                $this->load->model("MdlTransaksi");
                $tri = new MdlTransaksi();
                $tri->addFilter("transaksi.id='" . $refIDs . "'");
                $tri->addFilter("transaksi_data.trash='0'");
                $tmpTri = $tri->lookupJoined()->result();

                if (isset($tmpTri[0]->counters)) {
                    $counterTrIDS = blobDecode($tmpTri[0]->counters);
                    foreach ($counterTrIDS as $key_d => $val_d) {
                        foreach ($val_d as $val_dd) {
                            $globalVars['countersTrIDS'][$key_d] = $val_dd;
                        }
                    }
                }

                $tmpReg2 = $tr->lookupRegistriesByMasterID($refIDs)->result();

                foreach ($tmpReg2 as $row2) {
                    switch ($row2->param) {
                        //                        case "receiptSumFields"://
                        //                            $receiptSumFields2 = unserialize(base64_decode($row2->values));
                        //                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams2 = unserialize(base64_decode($row2->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams2 = unserialize(base64_decode($row2->values));
                            break;
                        case "items"://
                            $itemsRegistries2 = unserialize(base64_decode($row2->values));
                            break;
                        case "main"://
                            $main2 = unserialize(base64_decode($row2->values));
                            break;

                    }
                }

            }
            //            arrPrint($main2);
            //            matiHere();
            $itemLabels = $receiptDetailFields;
            $itemLabels2 = $receiptDetailFields2;
            //            arrPrint($itemLabels2);
            foreach ($tmpTr as $row) {

                $id = $row->produk_id;
                $tmp = array();

                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
                        if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                            $chars = explode("+", $key);
                            $colValue = "";
                            foreach ($chars as $key2) {
                                $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                            }
                        }
                        else {
                            $colValue = isset($row->$key) ? $row->$key : "";
                        }
                        $tmp[$key] = $colValue;
                        if (!isset($childTableInValueParams[$id][$key])) {
                            $childTableInValueParams[$id][$key] = $colValue;
                        }


                    }
                }

                //                if ($subAmountConfig != null) {
                //                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                //                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                //                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                //                } else {
                //                    $subtotal = 0;
                //                }
                //                $tmp["subtotal"] = $subtotal;
                //                arrPrint($tmp);
                //                arrPrint($childTableInValueParams[$id]);
                //                arrPrint($childTableInValueParams);
                //                arrPrint($tmp);
                $rawItems[$row->produk_id] = $tmp;
                $rawItems[$row->produk_id] = array_replace(array_filter($childTableInValueParams[$row->produk_id]), array_filter($rawItems[$row->produk_id]));

                if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                    foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                        if (array_key_exists($key, $rawItems[$row->produk_id])) {
                            $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                        }
                    }
                }

                if (sizeof($pairReceiptItemRegistries) > 0) {
                    foreach ($pairReceiptItemRegistries as $k) {
                        $rawItems[$row->produk_id][$key] = isset($itemsRegistries[$row->produk_id][$k]) ? $itemsRegistries[$row->produk_id][$k] : "";
                    }
                }

                foreach ($availValueKeys as $ak) {
                    //                        echo "checking $ak: ";
                    if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                        //                        echo "ada ";
                        $valueKey = $ak;
                    }
                    else {
                        //                        echo "none ";
                    }
                }

            }

            foreach ($tmpTri as $row2) {

                $id = $row2->produk_id;
                $tmp2 = array();

                if (sizeof($itemLabels2) > 0) {
                    foreach ($itemLabels2 as $key => $val) {
                        if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                            $chars = explode("+", $key);
                            $colValue = "";
                            foreach ($chars as $key2) {
                                $colValue .= isset($row2->$key2) ? $row2->$key2 . "<br>" : "";
                            }
                        }
                        else {
                            $colValue = isset($row2->$key) ? $row2->$key : "";
                        }
                        $tmp2[$key] = $colValue;
                        if (!isset($childTableInValueParams2[$id][$key])) {
                            $childTableInValueParams2[$id][$key] = $colValue;
                        }


                    }
                }

                //                arrPrint($childTableInValueParams2[$id]);
                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams2[$id], $childTableInValueParams2[$id], 0);
                    //                    cekHere($subtotal);
                }
                else {
                    $subtotal = 0;
                }
                //                matiHere($subtotal);
                $tmp2["subtotal"] = $subtotal;
                //                arrPrint($tmp);
                //                arrPrint($childTableInValueParams[$id]);
                //                arrPrint($childTableInValueParams);
                //                arrPrint($tmp);
                $rawItems2[$row2->produk_id] = $tmp2;
                $rawItems2[$row2->produk_id] = array_replace(array_filter($childTableInValueParams2[$row2->produk_id]), array_filter($rawItems2[$row2->produk_id]));

                if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                    foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                        if (array_key_exists($key, $rawItems2[$row2->produk_id])) {
                            $rawItems2[$row->produk_id][$key] = isset($vSpec[$rawItems2[$row2->produk_id][$key]]) ? $vSpec[$rawItems2[$row2->produk_id][$key]] : "";
                        }
                    }
                }

                if (sizeof($pairReceiptItemRegistries) > 0) {
                    foreach ($pairReceiptItemRegistries as $k) {
                        $rawItems2[$row2->produk_id][$key] = isset($itemsRegistries2[$row2->produk_id][$k]) ? $itemsRegistries2[$row2->produk_id][$k] : "";
                    }
                }

                foreach ($availValueKeys as $ak) {
                    //                        echo "checking $ak: ";
                    if (isset($rawItems2[$row2->produk_id][$ak]) && is_numeric($rawItems2[$row2->produk_id][$ak])) {
                        //                        echo "ada ";
                        $valueKey = $ak;
                    }
                    else {
                        //                        echo "none ";
                    }
                }

            }

        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion


        $items = array();
        $items2 = array();

        $jenisTr = $this->jenisTr;
        $items = $rawItems;
        $items2 = $rawItems2;

        //        arrPrint($items2);
        //        die();
        //        $items=array_merge($items,$childTableInValueParams);


        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];

        $globalVars = array_merge($globalVars, (array)$mainProp);
        //         arrPrint($globalVars);
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion


        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                // cekHitam($eKey);
                //                arrPrint($eSpec);
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHijau($relElementType);
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    // arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);

                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key ");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                    //                                $vTmp["nik"]="45245829";
                                    //                                    arrPrint($val);
                                    //                                                                arrPrint($vTmp);
                                    //                                                                cekBiru($key);
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        //                                        cekKuning($key."||".$val);
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    // cekHitam($vTmp);
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }


                $elements[$eKey] = $eTmp;
            }
            //            arrPrint($elements);
        }


        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams);


        if (isset($globalVars['nomer'])) {
            //            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode']) ? "-" . digit_5($globalVars['countersTrID']['stepCode']) : "";
            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        //        arrPrint($main2);
        if (isset($globalVars['nomer_top'])) {
            if (isset($globalVars['nomer_top'])) {
                $globalVars['nomer_top'] = $main2['nomer'];
                $globalVars['payment_method'] = $main2['paymentMethod__name'];
                //                $globalVars['nomer_top']= $main2['nomer'];
            }
            //            arrPrint($globalVars['countersTrIDS']);
            //            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            // $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            // $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
            $num_top = showHistoriGlobalNumbers($globalVars["ids_his"], 1);//tak matiin
            $addTrIdCounter = isset($globalVars['countersTrIDS']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrIDS']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTrIdCounter;
        }


        if (isset($globalVars["ids_his"])) {
            $stepPrev = $globalVars["step_number"] - 1;
            if ($stepPrev > 0) {
                // $cHist = blobDecode($globalVars["ids_his"])[$stepPrev];
                // $counterhists = blobDecode($cHist["counters"]);
                // $cNomer = $cHist["nomer"];
                // $cNomerExpl = explode(".", $cNomer);
                // $cTrcode = $cNomerExpl[0];
                // $cTrplace = $cNomerExpl[1];
                // $cgJenis = "$cTrcode|$cTrplace";
                //
                // $cgHist = digit_5($counterhists["stepCode|placeID"]["$cgJenis"]);
                //
                // $nomer_prev = $cNomer . "-" . $cgHist;

                // $globalVars['nomers_prev'] = formatField("nomer", $nomer_prev);
                $globalVars['nomers_prev'] = showHistoriGlobalNumbers($globalVars["ids_his"], $stepPrev, true);
            }
        }


        // arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        //        arrPrint($masterTableInValueParams);
        //        arrPrint($masterTableInValueParams);
        $dpValueDetails = array();
        $dpFieldName = array();
        if (isset($masterTableInValueParams2['dp_value']) && $masterTableInValueParams2['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams2['dp_value'],
                "ppn_dp" => $masterTableInValueParams2['dp_ppn_value'],
                "dp" => $masterTableInValueParams2['dp'],
                //                "due_amount" => $masterTableInValueParams2['tagihan'],
            );

            $dpFieldName = array(
                "dpp_dp" => "Downpayment",
                "ppn_dp" => "vat (10%)",
                "dp" => "Total Downpayment",
                //                "due_amount" => "Due amount",
            );
        }


        //endregion

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        //       arrPrint($fixedSignConfig);
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //arrPrint($signValues);
        //endregion

        //region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();

        if (sizeof($elementFixedConfig) > 0) {


            foreach ($elementFixedConfig as $key => $label) {
                //                cekHitam($key);
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];

            }


        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($globalVars);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion


        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            $in_word = "";
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }
        else {
            $in_word = "";
        }

        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : "";

        //region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] . $staticNotes : "$staticNotes";
        //        $fixedElements['noteDetails']['label'] = "NOTES" . " <span class='text-white'>$trID</span>";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array("subtotal" => "subtotal");
        $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        $itemLabels2 = $itemLabels2 + array("sub_nett1" => "subtotal");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
        }
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number] : array();
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : $this->uri->segment(2);


        $data = array(
            //            "mode"       => $this->uri->segment(2),
            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            //            "mainValues" => $mainValues,
            //            "detailValues" => $detailValues,
            "mainValues" => $main,
            "mainValues2" => $main2,
            //            "mainValues" => $masterTableInValueParams,
            "detailValues" => $childTableInValueParams,
            //            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            //            "itemLabels" => $itemLabels + $itemNumLabels,
            "itemLabels" => $itemLabels2,
            "itemLabels2" => isset($itemLabels2) ? $itemLabels2 : array(),
            "noteEnabled" => $noteEnabled,
            "items" => $items2,
            "items2" => $items2,
            "itemsRegistries" => $itemsRegistries,
            "inWord" => $in_word,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            //            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            //            "sumRows" => $receiptSumFields,
            "sumRows" => $receiptSumFields2,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            //            "grandTotal"     => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "signHeader" => $signHeader,
            //            "dataTemp" => $dataTemp,
            //            "fixedElements" => $fixedElements,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
            "zeroAllowed" => $zeroAllowed,
            "dpFieldName" => $dpFieldName,
            "dpValueDetils" => $dpValueDetails,
        );
        //endregion

        //        arrPrint($globalVars);
        $this->load->view("transaksi", $data);

    }

    public function viewReceiptOpname()
    {


        $globalVars = array();
        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tr->addFilter("transaksi_data.trash='0'");
        $tmpTr = $tr->lookupJoined()->result();
        //        cekHitam($this->db->last_query());
        //endregion

        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview

        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number <= 0 ? 1 : $tmpTr[0]->step_number;
            $mainJenis = $tmpTr[0]->transaksi_jenis2;


            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum];

            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }


            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();
            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
            $pairReceiptItemRegistries = isset($this->configUi[$this->jenisTr]['pairReceiptItemRegistries']) ? $this->configUi[$this->jenisTr]['pairReceiptItemRegistries'] : array();

            $itemLabelsConfig = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartFields'][$currentStepNum];
            $itemLabelsConfig2 = isset($this->configLayout[$this->jenisTr]['receiptDetailFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields2'][$currentStepNum] : isset($this->configUi[$this->jenisTr]['shoppingCartFields2'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartFields2'][$currentStepNum] : array();
            $itemLabelsConfig3 = isset($this->configLayout[$this->jenisTr]['receiptDetailFields3'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields3'][$currentStepNum] : isset($this->configUi[$this->jenisTr]['shoppingCartFields3'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartFields3'][$currentStepNum] : array();

            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            //endregion


            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");
            $tmpTop = $tr->lookupMainTransaksi()->result();


            $id_prevs = $tmpTr[0]->ids_prev;
            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }

            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }
            //region take from registries


            // $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $tmpReg = array();
            foreach ($this->registryFields as $param_key) {
                if ($tmpTr[0]->$param_key != '') {
                    $tmpReg[$param_key] = $tmpTr[0]->$param_key;
                }

            }
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $itemsRegistries = array();
            $rsltItemsRegistries = array();
            // arrPrint($tmpReg);

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row => $values) {
                    switch ($row) {

                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($values));
                            break;
                        case "receiptDetailFields"://
                            $receiptDetailFields = unserialize(base64_decode($values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($values));
                            break;
                        case "receiptDetailFields2"://
                            $receiptDetailFields2 = unserialize(base64_decode($values));
                            break;
                        case "receiptSumFields2"://
                            $receiptSumFields2 = unserialize(base64_decode($values));
                            break;
                        case "items"://
                            $itemsRegistries = unserialize(base64_decode($values));
                            break;
                        case "items2"://
                            $items2Registries = unserialize(base64_decode($values));
                            break;
                        case "items2_sum"://
                            $items2_sumRegistries = unserialize(base64_decode($values));
                            break;
                        case "main"://
                            $main = unserialize(base64_decode($values));
                            break;
                        case "rsltItems"://
                            $rsltItemsRegistries = unserialize(base64_decode($values));
                            break;
                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            //arrPrint($itemNumLabels);
            //arrPrint($itemsRegistries);
            //            $itemLabels = $receiptDetailFields;
            //            $itemLabels2 = $receiptDetailFields2;
            $itemLabels = $itemLabelsConfig;
            $itemLabels2 = $itemLabelsConfig2;
            $itemsReg = sizeof($rsltItemsRegistries) > 0 ? $rsltItemsRegistries : $itemsRegistries;
            $itemsRegs = sizeof($rsltItemsRegistries) > 0 ? true : false;

            if ($mainJenis == "paket") {
                //                arrPrint($itemLabels);
                $rawItems[] = array(
                    "produk_kode" => "",
                    "produk_nama" => $tmpTr[0]->transaksi_jenis2_label,
                    "nett1" => $main['new_net1'],
                    "subtotal" => $main['grand_total_ui'],
                );
            }
            else {
                //                foreach ($tmpTr as $row) {
                foreach ($itemsReg as $ctr => $row) {
                    //                    $id = $row->produk_id;
                    $id = $row['id'];
                    $row['produk_id'] = $id;
                    //                    $rowCtr = $id;
                    $rowCtr = $ctr;
                    //                    arrPrint($itemsRegistries[$id]);
                    if ($itemsRegs == true) {
                        $row['qty_kredit'] = $row['qty'];
                    }
                    //                    arrPrint($row);
                    $tmp = array();
                    if (sizeof($itemsRegistries[$id]) > 0) {
                        foreach ($itemsRegistries[$id] as $k_items => $v_items) {
                            if (!isset($row[$k_items])) {
                                //                                cekHere(" mengisi $k_items dengan $v_items");
                                $row[$k_items] = $v_items;
                            }
                        }
                    }

                    if (sizeof($itemLabels) > 0) {
                        foreach ($itemLabels as $key => $val) {
                            if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                                $chars = explode("+", $key);
                                $colValue = "";
                                foreach ($chars as $key2) {
                                    $colValue .= isset($row[$key2]) ? $row[$key2] . "<br>" : "";
                                }
                            }
                            else {
                                $colValue = isset($row[$key]) ? $row[$key] : "";
                                //                                $colValue = isset($row->$key) ? makeValue($key, (array)$row, (array)$row, 0) : "";
                            }
                            $tmp[$key] = $colValue;
                            if (!isset($childTableInValueParams[$id][$key])) {
                                $childTableInValueParams[$id][$key] = $colValue;
                            }
                        }
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $val) {
                            $tmp[$key] = makeValue($key, $row, $row, 0);
                        }
                    }

                    if ($subAmountConfig != null) {
                        //                        $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                        //                        $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                        //                        $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                        $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                    }
                    else {
                        $subtotal = 0;
                    }

                    $tmp["subtotal"] = $subtotal;
                    //arrPrint($tmp);
                    //                    $rawItems[$row['produk_id']] = $tmp;
                    //                    $rawItems[$row['produk_id']] = array_replace(array_filter($childTableInValueParams[$row['produk_id']]), array_filter($rawItems[$row['produk_id']]));
                    $rawItems[$rowCtr] = $tmp;
                    //                    $rawItems[$rowCtr] = array_replace(array_filter($childTableInValueParams[$row['produk_id']]), array_filter($rawItems[$row['produk_id']]));
                    //                    $rawItems[$rowCtr] = array_replace(array_filter($childTableInValueParams[$row['produk_id']]), array_filter($rawItems[$row['produk_id']]));

                    if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                        foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                            if (array_key_exists($key, $rawItems[$row['produk_id']])) {
                                $rawItems[$rowCtr][$key] = isset($vSpec[$rawItems[$row['produk_id']][$key]]) ? $vSpec[$rawItems[$row['produk_id']][$key]] : "";
                            }
                        }
                    }

                    foreach ($availValueKeys as $ak) {

                        if (isset($rawItems[$rowCtr][$ak]) && is_numeric($rawItem[$rowCtr][$ak])) {
                            $valueKey = $ak;
                        }
                        else {
                        }
                    }
                }
            }
        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion

        // matiHere(__LINE__);
        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;

        foreach ($rawItems as $pid => $specs) {
            if (($specs['qty_debet'] > 0) || ($specs['qty_kredit'] > 0)) {
                $items[$pid] = $specs;
            }
        }
        //arrPrint($items);

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        //                arrPrint($mainProp);
        //                die("0000");
        //        $globalVars = $globalVars + (array)$mainProp;

        $globalVars = array_merge($globalVars, (array)$mainProp);
        //         arrPrint($globalVars);
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion


        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                // cekHitam($eKey);
                //                arrPrint($eSpec);
                //                                cekHitam($eKey);
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHijau($relElementType);
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    // arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);

                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key ");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                    //                                $vTmp["nik"]="45245829";
                                    //                                    arrPrint($val);
                                    //                                                                arrPrint($vTmp);
                                    //                                                                cekBiru($key);
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        //                                        cekKuning($key."||".$val);
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    // cekHitam($vTmp);
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }


                $elements[$eKey] = $eTmp;
            }
            //            arrPrint($elements);
        }


        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams);


        if (isset($globalVars['nomer'])) {
            //            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode']) ? "-" . digit_5($globalVars['countersTrID']['stepCode']) : "";
            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        if (isset($globalVars['nomer_top'])) {
            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
            // $num_top = showHistoriGlobalNumbers($globalVars["ids_his"],1);
            // $globalVars['nomer_top'] = $num_top."**";
        }
        else {
            $globalVars['nomer_top'] = "-";
        }


        if (isset($globalVars["ids_his"])) {
            $stepPrev = $globalVars["step_number"] - 1;
            if ($stepPrev > 0) {
                // $cHist = blobDecode($globalVars["ids_his"])[$stepPrev];
                // $counterhists = blobDecode($cHist["counters"]);
                // $cNomer = $cHist["nomer"];
                // $cNomerExpl = explode(".", $cNomer);
                // $cTrcode = $cNomerExpl[0];
                // $cTrplace = $cNomerExpl[1];
                // $cgJenis = "$cTrcode|$cTrplace";
                //
                // $cgHist = digit_5($counterhists["stepCode|placeID"]["$cgJenis"]);
                //
                // $nomer_prev = $cNomer . "-" . $cgHist;

                // $globalVars['nomers_prev'] = formatField("nomer", $nomer_prev);

                //                arrPrint($stepPrev);
                $globalVars['nomers_prev'] = showHistoriGlobalNumbers($globalVars["ids_his"], $stepPrev, true);
            }
            else {
                $globalVars['nomers_prev'] = "-";
            }
        }
        else {
            $ids_prev = blobDecode($tmpTr[0]->ids_prev);
            // arrPrint($tmpTr[0]->ids_prev);
            $trx = new MdlTransaksi();
            $trx->setFilters(array());
            $trx->addFilter("id='" . $ids_prev[0] . "'");
            $tmpTrx = $trx->lookupMainTransaksi()->result();
            $counterTrID = blobDecode($tmpTrx[0]->counters);
            foreach ($counterTrID as $key_d => $val_d) {
                foreach ($val_d as $val_dd) {
                    $tmpTrx['countersTrID'][$key_d] = $val_dd;
                }
            }
            //            arrPrint($tmpTrx);
            $addTrIdCounter = isset($tmpTrx['countersTrID']['stepCode|placeID']) ? "-" . digit_5($tmpTrx['countersTrID']['stepCode|placeID']) : "";
            //            cekHere($addTrIdCounter);
            $globalVars['nomers_prev'] = isset($tmpTrx[0]->nomer) ? formatField("nomer", $tmpTrx[0]->nomer . $addTrIdCounter) : "-";
        }


        // arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        //        arrPrint($masterTableInValueParams);
        $dpValueDetails = array();
        $dpFieldName = array();
        if (isset($masterTableInValueParams['dp_value']) && $masterTableInValueParams['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams['dp_value'],
                "ppn_dp" => $masterTableInValueParams['dp_ppn_value'],
                "dp" => $masterTableInValueParams['dp'],
                "due_amount" => $masterTableInValueParams['tagihan'],
            );

            $dpFieldName = array(
                "dpp_dp" => "DPP Dp",
                "ppn_dp" => "vat Dp",
                "dp" => "Downpayment",
                "due_amount" => "Due amount",
            );
        }


        //endregion

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //arrPrint($signValues);
        //endregion

        //region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();
        // arrPrint($elementFixedConfig);

        //        arrPrint($globalVars);
        //        die();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                //                cekHere($key);
                //                cekHere($globalVars[$key]);
            }
        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($globalVars);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion


        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            $in_word = "";
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }
        else {
            $in_word = "";
        }

        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$tmpTr[0]->step_number] : "";

        //region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] . $staticNotes : "$staticNotes";
        //        $fixedElements['noteDetails']['label'] = "NOTES" . " <span class='text-white'>$trID</span>";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$tmpTr[0]->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array("subtotal" => "subtotal");
        $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "subtotal");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$tmpTr[0]->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$tmpTr[0]->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
            //            cekHere(":: buang subtotal");
        }
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$tmpTr[0]->step_number] : array();
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceipt";
        //arrPrint($itemLabels);

        $data = array(
            "mode" => "viewReceiptOpname",
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $main,
            "detailValues" => $childTableInValueParams,
            "itemLabels" => $itemLabels,
            "itemLabels2" => isset($itemLabels2) ? $itemLabels2 : array(),
            "noteEnabled" => $noteEnabled,
            "items" => $items,
            "items2" => isset($items2_sumRegistries) ? $items2_sumRegistries : $items2,
            "itemsRegistries" => $itemsRegistries,
            "inWord" => $in_word,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            "sumRows" => $receiptSumFields,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "signHeader" => $signHeader,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
            // "tempData"                 => $tempData,
            "zeroAllowed" => $zeroAllowed,
            "dpFieldName" => $dpFieldName,
            "dpValueDetils" => $dpValueDetails,
        );
        //endregion

        //        arrPrint($items);
        //        cekHere(__LINE__);
        $this->load->view("printing", $data);

    }

    public function viewReceiptBT()
    {
        $globalVars = array();
        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;

            //            $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();
            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;


            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            //endregion

            //region take from registries
            //==ambil value-gate
            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {

                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields"://
                            $receiptDetailFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($row->values));
                            break;
                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            //arrPrint($tmpTr);


            $itemLabels = $receiptDetailFields;
            foreach ($tmpTr as $row) {

                $id = $row->produk_id;
                $tmp = array();

                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
                        if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                            $chars = explode("+", $key);
                            $colValue = "";
                            foreach ($chars as $key2) {
                                $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                            }
                        }
                        else {
                            $colValue = isset($row->$key) ? $row->$key : "";
                        }
                        $tmp[$key] = $colValue;
                        if (!isset($childTableInValueParams[$id][$key])) {
                            $childTableInValueParams[$id][$key] = $colValue;
                        }


                    }
                }

                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                }
                else {
                    $subtotal = 0;
                }


                $tmp["subtotal"] = $subtotal;


                $rawItems[$row->produk_id] = $tmp;

                $rawItems[$row->produk_id] = array_merge(array_filter($rawItems[$row->produk_id]), array_filter($childTableInValueParams[$row->produk_id]));

                //arrPrint($receiptDetailFieldsReplacerConfig);
                if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                    foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                        //                        cekHere(":: siap siap...");
                        if (array_key_exists($key, $rawItems[$row->produk_id])) {
                            $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                        }
                    }
                }

                //                arrprint($rawItems[$row->produk_id]);
                foreach ($availValueKeys as $ak) {
                    //                        echo "checking $ak: ";
                    if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                        //                        echo "ada ";
                        $valueKey = $ak;
                    }
                    else {
                        //                        echo "none ";
                    }
                }

            }
        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion

        //        arrprint($availValueKeys);
        //        echo($valueKey);

        //arrprint($childTableInValueParams);
        //        arrprint($rawItems);

        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;
        //        $items=array_merge($items,$childTableInValueParams);
        //        arrprint($items);

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        $globalVars = $globalVars + (array)$mainProp;
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion

        //        arrprint($itemLabels);
        //        arrprint($items);
        //        arrprint($rawItems);
        //        die();
        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                switch ($elementType) {
                    case "dataModel":
                        foreach ($eSpec as $key => $val) {
                            if ($key == "contents") {
                                $vTmp = $val != null ? unserialize(base64_decode($val)) : "-";
                            }
                            else {
                                $vTmp = $val != null ? $val : "-";
                            }
                            $eTmp[$key] = $vTmp;
                            if (is_array($vTmp)) {
                                foreach ($vTmp as $key => $val) {
                                    $elementsGate[$eKey . "_$key"] = $val;
                                }
                            }
                            else {
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                        }

                        break;
                    case "dataField":
                        $eTmp = array();
                        foreach ($eSpec as $key => $val) {
                            $vTmp = $val != null ? $val : "-";
                            $eTmp[$key] = $vTmp;
                            $eTmp['contents']['nama'] = $eSpec['value'];
                            $elementsGate[$eKey . "_$key"] = $vTmp;
                        }
                        break;
                }
                $elements[$eKey] = $eTmp;
            }
        }

        $globalVars = $globalVars + $arrCompanyProfile;
        if (isset($elementsGate)) {
            $globalVars = $globalVars + $elementsGate;
        }

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //arrPrint($signValues);
        //endregion

        //region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? $globalVars[$key] : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
            }
        }
        else {
            $fixedElements = array();
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();
        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];
            foreach ($globalVars as $key => $val) {
                $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
            }
            $companyProfile['companyProfile']['contents'][] = $companyStr;
        }
        //endregion

        //region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] : "-";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }
        // arrprint($elements);die();

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        //        $temp = array(
        //            "mode" => $this->uri->segment(2),
        //            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
        //            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
        //            "jenisTr" => $jenisTr,
        //            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
        //            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
        //            "main" => $mainProp,
        //            "mainValues" => $mainValues,
        //            "detailValues" => $detailValues,
        //            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
        //            "items" => $items,
        //            "items2" => $items2,
        //            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
        //            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
        //            "mainAddValues" => $masterAddValues,
        //            "mainAddFields" => $masterAddFields,
        //            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
        //            "grandTotal"     => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
        //            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
        //            "signature" => $signValues,
        //            "companyProfile" => $arrCompanyProfile,
        //            "mainElements" => $elements,
        //            "footer" => $footer,
        //            "fixedElements" => $fixedElements,
        //        );


        $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        //        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount']) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][[$row->step_number]] == true) {
            unset($itemLabels['subtotal']);
            //            unset($itemLabels2['subtotal']);
        }

        //        arrprint($itemLabels);


        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceipt";

        $data = array(
            //            "mode"       => $this->uri->segment(2),
            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            //            "mainValues" => $mainValues,
            //            "detailValues" => $detailValues,

            "mainValues" => $masterTableInValueParams,
            "detailValues" => $childTableInValueParams,


            //            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            //            "itemLabels" => $itemLabels + $itemNumLabels,
            "itemLabels" => $itemLabels,
            "items" => $items,
            "items2" => $items2,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,
            //            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "sumRows" => $receiptSumFields,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            //            "grandTotal"     => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "signHeader" => $signHeader,
            //            "dataTemp" => $dataTemp,
            //            "fixedElements" => $fixedElements,
            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
        );


        //endregion


        $this->load->view("transaksi", $data);

    }

    public function viewSmallReceipt()
    {

        $globalVars = array();
        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion

        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion
        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {

            //            arrPrint($tmpTr);

            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $paymentMethod = $tmpTr[0]->pembayaran_sys;

            $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();
            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;

            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
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
            //endregion

            //region take from registries
            //==ambil value-gate
            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {

                    switch ($row->param) {
                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields"://
                            $receiptDetailFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($row->values));
                            break;
                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }


            //            arrPrint($masterGates);

            //endregion
            foreach ($tmpTr as $row) {
                $id = $row->produk_id;
                $tmp = array();
                foreach ($itemLabels as $key => $val) {

                    if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $key);
                        $colValue = "";
                        foreach ($chars as $key2) {
                            //                            $colValue .= isset($row->$key2) ? formatField($key, $row->$key2) . "<br>" : "";
                        }
                        //                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        //                        $colValue = isset($row->$key) ? formatField($key, $row->$key) : "";
                        $colValue = isset($row->$key) ? $row->$key : "";
                    }

                    //                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                    //                        if (isset($detailValues[$row->produk_id][$key])) {
                    //                            $fieldValue = $detailValues[$row->produk_id][$key];
                    //                        } else {
                    //                            if (isset($row->$key)) {
                    //                                $fieldValue = $row->$key;
                    //                            }
                    //                        }

                    //                        $tmp[$key] = formatField($key, $fieldValue);
                    //                        $tmp[$key] = $fieldValue;
                    $tmp[$key] = $colValue;
                    if (!isset($childTableInValueParams[$id][$key])) {
                        $childTableInValueParams[$id][$key] = $colValue;
                    }
                }

                //region calculate subtotal
                //===perhitungan subtotal


                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                }
                else {
                    $subtotal = 0;
                }
                $tmp["subtotal"] = $subtotal;
                //endregion
                $rawItems[$row->produk_id] = $tmp;
                $rawItems[$row->produk_id] = array_merge(array_filter($rawItems[$row->produk_id]), array_filter($childTableInValueParams[$row->produk_id]));

                foreach ($availValueKeys as $ak) {
                    //                        echo "checking $ak: ";
                    if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                        //                        echo "ada ";
                        $valueKey = $ak;
                    }
                    else {
                        //                        echo "none ";
                    }
                }

            }

        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }
        //endregion

        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        $globalVars = $globalVars + (array)$mainProp;
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion

        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                switch ($elementType) {
                    case "dataModel":
                        foreach ($eSpec as $key => $val) {
                            if ($key == "contents") {
                                $vTmp = $val != null ? unserialize(base64_decode($val)) : "-";
                            }
                            else {
                                $vTmp = $val != null ? $val : "-";
                            }
                            $eTmp[$key] = $vTmp;


                            if (is_array($vTmp)) {
                                foreach ($vTmp as $key => $val) {
                                    $elementsGate[$eKey . "_$key"] = $val;
                                }
                            }
                            else {
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                        }

                        break;
                    case "dataField":
                        $eTmp = array();
                        foreach ($eSpec as $key => $val) {
                            $vTmp = $val != null ? $val : "-";

                            $eTmp[$key] = $vTmp;
                            $eTmp['contents']['nama'] = $eSpec['value'];

                            $elementsGate[$eKey . "_$key"] = $vTmp;
                        }
                        break;
                }
                $elements[$eKey] = $eTmp;
            }
        }

        $globalVars = $globalVars + $elementsGate + $arrCompanyProfile;
        $globalVars = $globalVars + $arrCompanyProfile;
        if (isset($elementsGate)) {
            $globalVars = $globalVars + $elementsGate;
        }

        //        region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //        //endregion

        //        region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? $globalVars[$key] : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
            }
        }
        else {
            $fixedElements = array();
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();
        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];
            foreach ($globalVars as $key => $val) {
                $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
            }
            $companyProfile['companyProfile']['contents'][] = $companyStr;
        }
        //        endregion

        //        region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] : "-";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //        endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['staticFooter']) ? $this->configLayout[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['staticFooter'] : "";

        //region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();


        //$main = array_merge(array_filter((array)$mainProp), array_filter($masterTableInParams), array_filter($masterTableInValueParams), array_filter($main));

        $temp = array(
            "mode" => $this->uri->segment(2),
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $mainValues,
            "detailValues" => $detailValues,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "footer" => $footer,
        );
        //        arrPrint($mainProp);
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceiptBT";
        $data = array(
            //                        "mode"       => $this->uri->segment(2),
            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $masterTableInValueParams,
            "detailValues" => $childTableInValueParams,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "sumBottom" => isset($this->configLayout[$this->jenisTr]['receiptSumBottom']) ? $this->configLayout[$this->jenisTr]['receiptSumBottom'] : array(),
            "headerTablesSmall" => isset($this->configLayout[$this->jenisTr]['headerTablesSmall']) ? $this->configLayout[$this->jenisTr]['headerTablesSmall'] : array(),
            "receiptNonTunai" => isset($this->configLayout[$this->jenisTr]['receiptNonTunai']) ? $this->configLayout[$this->jenisTr]['receiptNonTunai'] : array(),
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "masterGates" => $masterGates,
            "signHeader" => $signHeader,
            "valueKey" => $valueKey,
            "temp" => $temp,
        );
        //endregion
        $this->load->view("transaksi", $data);

    }

    // viewReceipt ini dipakai untuk produksi/convertion, semua yang ditampilkan diambil dari registry...
    public function viewReceiptProduksi()
    {

        //        die();
        $globalVars = array();
        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        $valas = array();

        if (isset($_GET['type'])) {

        }

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion

        $availValueKeys = array(
            "harga_nppn",
            "harga_nett3",
            "harga",
            "harga_nett1",
            "harga_nett2",
            "harga_nett",
            "nett",
            "nett1",
            "nett2",
        );
        $valueKey = "";
        $rawItems = array();
        //region detail elements of preview

        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number <= 0 ? 1 : $tmpTr[0]->step_number;


            $itemNumLabels = isset($this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receipNumFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum];
            $itemLabelsConfig = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : $this->configUi[$this->jenisTr]['shoppingCartFields'][$currentStepNum];
            $itemLabelsConfig2 = isset($this->configLayout[$this->jenisTr]['receiptDetailFields2'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields2'][$currentStepNum] : isset($this->configUi[$this->jenisTr]['shoppingCartFields2'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartFields2'][$currentStepNum] : array();
            $itemLabelsConfig3 = isset($this->configLayout[$this->jenisTr]['receiptDetailFields3'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields3'][$currentStepNum] : isset($this->configUi[$this->jenisTr]['shoppingCartFields3'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartFields3'][$currentStepNum] : array();

            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configLayout[$this->jenisTr]['receipCartNumFields2'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum])) {
                $itemNumLabels2 = $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$currentStepNum];
            }
            else {
                $itemNumLabels2 = array();
            }
            if (isset($this->configLayout[$this->jenisTr]['receipCartNumFields3'][$currentStepNum])) {
                $itemNumLabels3 = $this->configLayout[$this->jenisTr]['receipCartNumFields3'][$currentStepNum];
            }
            elseif (isset($this->configUi[$this->jenisTr]['shoppingCartNumFields3'][$currentStepNum])) {
                $itemNumLabels3 = $this->configUi[$this->jenisTr]['shoppingCartNumFields3'][$currentStepNum];
            }
            else {
                $itemNumLabels3 = array();
            }

            $subAmountConfig = isset($this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['subAmountValue'][$currentStepNum] : null;
            $receiptDetailFieldsReplacerConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptDetailFieldsReplacer'] : null;
            $receiptInWordConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word']) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptInword'][$currentStepNum]['in_word'] : array();
            $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
            $pairReceiptItemRegistries = isset($this->configUi[$this->jenisTr]['pairReceiptItemRegistries']) ? $this->configUi[$this->jenisTr]['pairReceiptItemRegistries'] : array();

            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            //endregion


            //  take from transaksi top
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id='$topID'");
            $tmpTop = $tr->lookupMainTransaksi()->result();


            if (sizeof($tmpTop)) {
                if (isset($tmpTop[0]->counters)) {
                    $counterTop = blobDecode($tmpTop[0]->counters);
                    foreach ($counterTop as $c_key => $c_val) {
                        foreach ($c_val as $cc_val) {
                            $globalVars['countersTop'][$c_key] = $cc_val;
                        }
                    }
                }
            }
            // arrPrint($globalVars);
            if (isset($tmpTr[0]->counters)) {
                $counterTrID = blobDecode($tmpTr[0]->counters);
                foreach ($counterTrID as $key_d => $val_d) {
                    foreach ($val_d as $val_dd) {
                        $globalVars['countersTrID'][$key_d] = $val_dd;
                    }
                }
            }
            //region take from registries

            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            $receiptDetailFields = array();
            $receiptSumFields = array();
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $itemsRegistries = array();


            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {

                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields"://
                            $receiptDetailFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields2"://
                            $receiptDetailFields2 = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields2"://
                            $receiptSumFields2 = unserialize(base64_decode($row->values));
                            break;
                        case "items"://
                            $itemsRegistries = unserialize(base64_decode($row->values));
                            break;
                        case "items2"://
                            $items2Registries = unserialize(base64_decode($row->values));
                            break;
                        case "items2_sum"://
                            $items2_sumRegistries = unserialize(base64_decode($row->values));
                            break;
                        case "items3_sum"://
                            $items3_sumRegistries = unserialize(base64_decode($row->values));
                            break;
                        case "main"://
                            $main = unserialize(base64_decode($row->values));
                            break;

                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion


            //            $itemLabels = $receiptDetailFields;
            //            $itemLabels2 = $receiptDetailFields2;
            //            $itemLabels3 = $receiptDetailFields3;
            $itemLabels = $itemLabelsConfig;
            $itemLabels2 = $itemLabelsConfig2;
            $itemLabels3 = $itemLabelsConfig3;
            foreach ($tmpTr as $row) {

                $id = $row->produk_id;
                $tmp = array();

                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
                        if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                            $chars = explode("+", $key);
                            $colValue = "";
                            foreach ($chars as $key2) {
                                $colValue .= isset($row->$key2) ? $row->$key2 . "<br>" : "";
                            }
                        }
                        else {
                            $colValue = isset($row->$key) ? $row->$key : "";
                        }
                        $tmp[$key] = $colValue;
                        if (!isset($childTableInValueParams[$id][$key])) {
                            $childTableInValueParams[$id][$key] = $colValue;
                        }


                    }
                }


                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                }
                else {
                    $subtotal = 0;
                }

                $tmp["subtotal"] = $subtotal;

                $rawItems[$row->produk_id] = $tmp;


                $rawItems[$row->produk_id] = array_replace(array_filter($childTableInValueParams[$row->produk_id]), array_filter($rawItems[$row->produk_id]));


                if ((is_array($receiptDetailFieldsReplacerConfig) && (sizeof($receiptDetailFieldsReplacerConfig) > 0))) {
                    foreach ($receiptDetailFieldsReplacerConfig as $key => $vSpec) {
                        if (array_key_exists($key, $rawItems[$row->produk_id])) {
                            $rawItems[$row->produk_id][$key] = isset($vSpec[$rawItems[$row->produk_id][$key]]) ? $vSpec[$rawItems[$row->produk_id][$key]] : "";
                        }
                    }
                }

                if (sizeof($pairReceiptItemRegistries) > 0) {
                    foreach ($pairReceiptItemRegistries as $k) {
                        $rawItems[$row->produk_id][$key] = isset($itemsRegistries[$row->produk_id][$k]) ? $itemsRegistries[$row->produk_id][$k] : "";
                    }
                }

                foreach ($availValueKeys as $ak) {
                    //                        echo "checking $ak: ";
                    if (isset($rawItems[$row->produk_id][$ak]) && is_numeric($rawItems[$row->produk_id][$ak])) {
                        //                        echo "ada ";
                        $valueKey = $ak;
                    }
                    else {
                        //                        echo "none ";
                    }
                }

            }
        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }

        //endregion


        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        //                arrPrint($mainProp);
        //                die("0000");
        //        $globalVars = $globalVars + (array)$mainProp;

        $globalVars = array_merge($globalVars, (array)$mainProp);
        //         arrPrint($globalVars);
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion


        $relElementConfig = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relElementData = array();
        if (sizeof($relElementConfig) > 0) {
            foreach ($relElementConfig as $tempRel) {
                foreach ($tempRel as $relKey => $relTemp) {
                    //                    cekHitam($relKey);
                    //                    $relElemenType=$relTemp;
                    foreach ($relTemp as $rKey => $rTemp) {
                        //                        $relElemenType[$relKey][$rKey]=$rTemp;
                        $relElementData[$rKey] = $rTemp;
                        //                        arrPrint($relTemp);
                    }
                    //
                }
            }
        }


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                // cekHitam($eKey);
                //                arrPrint($eSpec);
                //                                cekHitam($eKey);
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                $relElementType = isset($relElementData[$eKey]['elementType']) ? $relElementData[$eKey]['elementType'] : array();
                //cekHijau($relElementType);
                if ($eKey == "billingDetails") {

                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key || $val");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";

                                    // arrPrint($vTmp);
                                    //                                    die();
                                    if (isset($vTmp['npwp']) && sizeof($vTmp['npwp']) > 0) {
                                        unset($vTmp["nik"]);

                                    }
                                    else {
                                        unset($vTmp["npwp"]);
                                    }

                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                // arrPrint($vTmp);
                                if (is_array($vTmp)) {

                                    foreach ($vTmp as $vTmpKey => $vTmpVal) {
                                        $vTmp[$vTmpKey] = formatField($vTmpKey, $vTmpVal);
                                    }
                                }
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }
                }
                else {
                    switch ($elementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {
                                //                            cekHitam("$key ");
                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                    //                                $vTmp["nik"]="45245829";
                                    //                                    arrPrint($val);
                                    //                                                                arrPrint($vTmp);
                                    //                                                                cekBiru($key);
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        //                                        cekKuning($key."||".$val);
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    // cekHitam($vTmp);
                                    if (isset($relElementType[$vTmp])) {
                                        // arrPrint($relElementType[$vTmp]);
                                        //                                        foreach($relElementType[$vTmp] as $relKey =>$relTemp){
                                        //arrPrint($relTemp);
                                        //                                        }
                                        //                                        $relType = isset($relElementType[$vTmp][$eKey]['elementType']) ? $relElementType[$vTmp][$eKey]['elementType']: "";
                                        //                                        cekLime("$eKey hooo ." .$relType);
                                    }
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                    switch ($relElementType) {
                        case "dataModel":
                            foreach ($eSpec as $key => $val) {

                                if ($key == "contents") {
                                    $vTmp = $val != null ? blobDecode($val) : "-";
                                }
                                else {
                                    $vTmp = $val != null ? $val : "-";
                                }
                                //                                cekHitam($key);
                                //                                                            arrPrint($vTmp);
                                $eTmp[$key] = $vTmp;
                                if (is_array($vTmp)) {
                                    foreach ($vTmp as $key => $val) {
                                        $elementsGate[$eKey . "_$key"] = $val;
                                    }
                                }
                                else {
                                    $elementsGate[$eKey . "_$key"] = $vTmp;
                                }
                            }

                            break;
                        case "dataField":
                            $eTmp = array();
                            foreach ($eSpec as $key => $val) {
                                $vTmp = $val != null ? $val : "-";
                                $eTmp[$key] = $vTmp;
                                $eTmp['contents']['nama'] = $eSpec['value'];
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                            break;
                    }

                }


                $elements[$eKey] = $eTmp;
            }
            //            arrPrint($elements);
        }


        $globalVars = array_merge($globalVars, $arrCompanyProfile, $masterTableInParams);


        if (isset($globalVars['nomer'])) {
            //            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode']) ? "-" . digit_5($globalVars['countersTrID']['stepCode']) : "";
            $addTrIdCounter = isset($globalVars['countersTrID']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTrID']['stepCode|placeID']) : "";
            $globalVars['nomer'] = $globalVars['nomer'] . $addTrIdCounter;
        }
        if (isset($globalVars['nomer_top'])) {
            //            $addTopCounter = isset($globalVars['countersTop']['stepCode']) ? "-" . digit_5($globalVars['countersTop']['stepCode']) : "";
            $addTopCounter = isset($globalVars['countersTop']['stepCode|placeID']) ? "-" . digit_5($globalVars['countersTop']['stepCode|placeID']) : "";
            $globalVars['nomer_top'] = $globalVars['nomer_top'] . $addTopCounter;
        }


        if (isset($globalVars["ids_his"])) {
            $stepPrev = $globalVars["step_number"] - 1;
            if ($stepPrev > 0) {
                $cHist = blobDecode($globalVars["ids_his"])[$stepPrev];
                $counterhists = blobDecode($cHist["counters"]);
                $cNomer = $cHist["nomer"];
                $cNomerExpl = explode(".", $cNomer);
                $cTrcode = $cNomerExpl[0];
                $cTrplace = $cNomerExpl[1];
                $cgJenis = "$cTrcode|$cTrplace";

                $cgHist = digit_5($counterhists["stepCode|placeID"]["$cgJenis"]);

                $nomer_prev = $cNomer . "-" . $cgHist;

                $globalVars['nomers_prev'] = formatField("nomer", $nomer_prev);
            }
        }


        // arrPrint($globalVars);
        if (isset($elementsGate)) {
            //            $globalVars = $globalVars + $elementsGate;
            $globalVars = array_merge($globalVars, $elementsGate);
        }


        //region downpayment
        //        arrPrint($masterTableInValueParams);
        $dpValueDetails = array();
        $dpFieldName = array();
        if (isset($masterTableInValueParams['dp_value']) && $masterTableInValueParams['dp_value'] > 0) {
            $dpValueDetails = array(
                "dpp_dp" => $masterTableInValueParams['dp_value'],
                "ppn_dp" => $masterTableInValueParams['dp_ppn_value'],
                "dp" => $masterTableInValueParams['dp'],
                "due_amount" => $masterTableInValueParams['tagihan'],
            );

            $dpFieldName = array(
                "dpp_dp" => "DPP Dp",
                "ppn_dp" => "vat Dp",
                "dp" => "Downpayment",
                "due_amount" => "Due amount",
            );
        }


        //endregion

        //region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //arrPrint($signValues);
        //endregion

        //region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        $elementFixedNumberSO = isset($this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['elementFixedNumberSO'][$currentStepNum] : array();
        // arrPrint($elementFixedConfig);

        //        arrPrint($globalVars);
        //        die();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
                //                cekHere($key);
                //                cekHere($globalVars[$key]);
            }
        }
        else {
            $fixedElements = array();
        }

        //        arrPrint($globalVars);

        if (sizeof($elementFixedNumberSO) > 0) {
            foreach ($elementFixedNumberSO as $key => $label) {
                $fixedElements['so_number']['contents'][$label] = isset($globalVars[$key]) ? formatField($key, $globalVars[$key]) : "";
            }
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();

        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];

            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {

                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
                //                cekHere($key);
                //                arrPrint($companyStr);
            }
            //            arrPrint($globalVars);
            $companyProfile['companyProfile']['contents'][] = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }

        //endregion


        if (sizeof($receiptInWordConfig) > 0) {
            $this->load->helper("he_inword");
            $in_word = "";
            foreach ($receiptInWordConfig as $he_loader => $fieldsSelected) {
                if (isset($masterTableInValueParams[$fieldsSelected])) {

                    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
                        $type = blobDecode($_GET['type']);
                        $fkali = blobDecode($_GET['f']);
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "";
                        if ($currency != '') {
                            $val_convert = $masterTableInValueParams[$fieldsSelected];
                        }
                        else {
                            $val_convert = $fkali > 0 ? ($masterTableInValueParams[$fieldsSelected] / $fkali) : $masterTableInValueParams[$fieldsSelected];
                        }
                        $he_word = inWordEng($val_convert, $type);
                        $in_word .= "$he_word";
                    }
                    else {
                        $val_convert = $masterTableInValueParams[$fieldsSelected];
                        $currency = isset($masterTableInValueParams['valasDetails']) ? $masterTableInValueParams['valasDetails'] : "IDR";
                        //                        $val_convert1=formatField("tagihan",$val_convert);
                        $val_convert1 = number_format(0 + $val_convert);

                        //                        echo $val_convert1;
                        $val_convert2 = preg_replace('/[$\,\.]/', '', $val_convert1);
                        //                        cekHitam($val_convert. " ||". $val_convert1);
                        $he_word = $he_loader($val_convert2, $currency);
                        //                        $he_word = $he_loader($val_convert, $currency);
                        $in_word .= "$he_word";
                    }

                }
            }
        }
        else {
            $in_word = "";
        }

        $staticNotes = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticNotes'][$row->step_number] : "";

        //region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] . $staticNotes : "$staticNotes";
        //        $fixedElements['noteDetails']['label'] = "NOTES" . " <span class='text-white'>$trID</span>";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";

        // region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $itemSubTotal = isset($this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptSumDetailFields'][$currentStepNum] : array("subtotal" => "subtotal");
        $itemLabels = $itemLabels + $itemNumLabels + $itemSubTotal;
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "subtotal");
        $itemLabels3 = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "subtotal");
        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number]) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$row->step_number] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
            unset($itemLabels3['subtotal']);
        }
        $zeroAllowed = isset($this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['receiptSumFieldsZeroAllowed'][$row->step_number] : array();
        $mode = isset($_GET['mobMode']) && $_GET['mobMode'] == "1" ? "viewReceiptBT" : "viewReceipt";

        $data = array(
            "mode" => $mode,
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $main,
            "detailValues" => $childTableInValueParams,
            "itemLabels" => $itemLabels,
            "itemLabels2" => isset($itemLabels2) ? $itemLabels2 : array(),
            "itemLabels3" => isset($itemLabels3) ? $itemLabels3 : array(),
            "noteEnabled" => $noteEnabled,
            //            "items" => $items,
            "items" => $itemsRegistries,
            "items2" => isset($items2_sumRegistries) ? $items2_sumRegistries : $items2,
            "items3" => isset($items3_sumRegistries) ? $items3_sumRegistries : array(),
            "itemsRegistries" => $itemsRegistries,
            "items3Registries" => isset($items3_sumRegistries) ? $items3_sumRegistries : array(),
            "inWord" => $in_word,
            "childTableInValueParams" => $childTableInValueParams,
            "masterTableInValueParams" => $masterTableInValueParams,

            "sumRows" => $receiptSumFields,
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",

            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "signHeader" => $signHeader,

            "footer" => $footer,
            "availValueKeys" => $availValueKeys,
            "valueKey" => $valueKey,
            "tempData" => isset($tempData) ? $tempData : array(),
            "zeroAllowed" => $zeroAllowed,
            "dpFieldName" => $dpFieldName,
            "dpValueDetils" => $dpValueDetails,
        );
        //endregion

        //        arrPrint($itemsRegistries);
        $this->load->view("transaksi", $data);

    }

    public function viewSmallReceiptBT()
    {

        $globalVars = array();
        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion

        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion

        $rawItems = array();
        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {

            //            arrPrint($tmpTr);

            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $paymentMethod = $tmpTr[0]->pembayaran_sys;

            $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();
            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;

            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
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
            //endregion

            //region take from registries
            //==ambil value-gate
            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {
                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "receiptDetailFields"://
                            $receiptDetailFields = unserialize(base64_decode($row->values));
                            break;
                        case "receiptSumFields"://
                            $receiptSumFields = unserialize(base64_decode($row->values));
                            break;
                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }


            //            arrPrint($masterGates);

            //endregion
            foreach ($tmpTr as $row) {
                $id = $row->produk_id;
                $tmp = array();
                foreach ($itemLabels as $key => $val) {

                    if (strpos($key, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $key);
                        $colValue = "";
                        foreach ($chars as $key2) {
                            //                            $colValue .= isset($row->$key2) ? formatField($key, $row->$key2) . "<br>" : "";
                        }
                        //                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        //                        $colValue = isset($row->$key) ? formatField($key, $row->$key) : "";
                        $colValue = isset($row->$key) ? $row->$key : "";
                    }

                    //                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                    //                        if (isset($detailValues[$row->produk_id][$key])) {
                    //                            $fieldValue = $detailValues[$row->produk_id][$key];
                    //                        } else {
                    //                            if (isset($row->$key)) {
                    //                                $fieldValue = $row->$key;
                    //                            }
                    //                        }

                    //                        $tmp[$key] = formatField($key, $fieldValue);
                    //                        $tmp[$key] = $fieldValue;
                    $tmp[$key] = $colValue;
                    if (!isset($childTableInValueParams[$id][$key])) {
                        $childTableInValueParams[$id][$key] = $colValue;
                    }
                }

                //region calculate subtotal
                //===perhitungan subtotal


                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $subtotal = makeValue($subAmountConfig, $childTableInValueParams[$id], $childTableInValueParams[$id], 0);
                }
                else {
                    $subtotal = 0;
                }
                $tmp["subtotal"] = $subtotal;
                //endregion
                $rawItems[$row->produk_id] = $tmp;
                $rawItems[$row->produk_id] = array_merge(array_filter($rawItems[$row->produk_id]), array_filter($childTableInValueParams[$row->produk_id]));
            }

        }
        else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";
            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";
            echo "</div class='alert alert-danger'>";
            die();
        }
        //endregion

        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;

        //region header small nota
        $smallPrint = isset($this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number]) ? $this->configLayout[$tmpTr[0]->jenis_master]['smallPrint'][$tmpTr[0]->step_number] : array();
        $signHeader = array();
        if (sizeof($smallPrint) > 0) {
            $tempSign0 = $tmpSign[0];
            foreach ($smallPrint as $kolom => $label) {
                if (array_key_exists($kolom, $tempSign0)) {
                    $signHeader['smallPrint'][$label] = $tempSign0->$kolom;
                }
            }
        }
        //endregion

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        $globalVars = $globalVars + (array)$mainProp;
        //endregion

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion

        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                $elementType = isset($this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->configUi[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                switch ($elementType) {
                    case "dataModel":
                        foreach ($eSpec as $key => $val) {
                            if ($key == "contents") {
                                $vTmp = $val != null ? unserialize(base64_decode($val)) : "-";
                            }
                            else {
                                $vTmp = $val != null ? $val : "-";
                            }
                            $eTmp[$key] = $vTmp;


                            if (is_array($vTmp)) {
                                foreach ($vTmp as $key => $val) {
                                    $elementsGate[$eKey . "_$key"] = $val;
                                }
                            }
                            else {
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                        }

                        break;
                    case "dataField":
                        $eTmp = array();
                        foreach ($eSpec as $key => $val) {
                            $vTmp = $val != null ? $val : "-";

                            $eTmp[$key] = $vTmp;
                            $eTmp['contents']['nama'] = $eSpec['value'];

                            $elementsGate[$eKey . "_$key"] = $vTmp;
                        }
                        break;
                }
                $elements[$eKey] = $eTmp;
            }
        }

        $globalVars = $globalVars + $elementsGate + $arrCompanyProfile;
        $globalVars = $globalVars + $arrCompanyProfile;
        if (isset($elementsGate)) {
            $globalVars = $globalVars + $elementsGate;
        }

        //        region fixed signature
        $fixedSignConfig = isset($this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->configLayout[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                }
                else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
        //        //endregion

        //        region fixed element
        $elementFixedConfig = isset($this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->configLayout[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? $globalVars[$key] : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
            }
        }
        else {
            $fixedElements = array();
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();
        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];
            foreach ($globalVars as $key => $val) {
                $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
            }
            $companyProfile['companyProfile']['contents'][] = $companyStr;
        }
        //        endregion

        //        region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] : "-";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //        endregion notes element

        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->configLayout[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['staticFooter']) ? $this->configLayout[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['staticFooter'] : "";

        //region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues']) && sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->configCore[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        }
        else {

        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $temp = array(
            "mode" => $this->uri->segment(2),
            //"template"       => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $mainValues,
            "detailValues" => $detailValues,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "footer" => $footer,
        );
        //arrPrint($mainProp);
        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->configLayout[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->configUi[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->configUi[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->configLayout[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $masterTableInValueParams,
            "detailValues" => $childTableInValueParams,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "sumBottom" => isset($this->configLayout[$this->jenisTr]['receiptSumBottom']) ? $this->configLayout[$this->jenisTr]['receiptSumBottom'] : array(),
            "headerTablesSmall" => isset($this->configLayout[$this->jenisTr]['headerTablesSmall']) ? $this->configLayout[$this->jenisTr]['headerTablesSmall'] : array(),
            "receiptNonTunai" => isset($this->configLayout[$this->jenisTr]['receiptNonTunai']) ? $this->configLayout[$this->jenisTr]['receiptNonTunai'] : array(),
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            "masterGates" => $masterGates,
            "signHeader" => $signHeader,
            "temp" => $temp,
        );
        //endregion
        $this->load->view("transaksi", $data);

    }

    public function editorPrint()
    {

        $this->load->model("Mdls/MdlSettingStruk");
        $sst = new MdlSettingStruk();
        $db_srcs = $sst->lookupAll()->result();
        showLast_query("merah");
        // arrPrint($db_srcs);

        $link_save = MODUL_PATH . "Printing/doSaveEditor";

        /* ----------------------------------------------------------------
         * form inputan
         * ----------------------------------------------------------------*/
        $var_form = "";
        $var_form .= "<div >";
        // header 1
        $var_form .= "<textarea name='header1' class='form-control'></textarea>";

        // footer 1
        $var_form .= "<textarea name='footer1' class='form-control'></textarea>";

        // footer 2
        $var_form .= "<textarea name='footer2' class='form-control'></textarea>";
        $var_form .= "</div>";

        /* ----------------------------------------------------------------
         * etalase
         * ----------------------------------------------------------------*/
        $var = "";
        $var .= "<div class='wraper' style='padding:20px;'>";
        $var .= "<div class='row overflow-h'>";
        $var .= "<div class='col-md-12'>";
        $var .= "<form method='post' action='$link_save' target='_blank'>";

        $var .= $var_form;

        $var .= "<div style='margin-top: 10px;'>";
        $var .= "<button type='submit' class='btn btn-primary pull-right'>Simpan</button>";
        $var .= "</div>";

        $var .= "</form>";
        $var .= "</div>";
        $var .= "</div>";
        $var .= "</div>";

        echo $var;
    }

    public function doSaveEditor()
    {
        arrPrint($_POST);
    }


}
