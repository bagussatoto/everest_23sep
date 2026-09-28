<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ToolsGeneratorInvoice extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");


        $this->cabang_id = CB_ID_PUSAT;
        $this->harga_jenis = "jual_reseller";
        $this->pph23 = 15;


    }


    //----------------------------------------------
    public function followupPrePreviewInvoicing($arrKiriman)
    {

        $no = $arrKiriman["transaksi_id"];
        $stepNumber = $arrKiriman["step_number"];
        $currentStepNum = $arrKiriman["currentStepNumber"];
        $this->jenisTr = $arrKiriman["jenisTr"];
        $this->configUiJenis = $arrKiriman["uiJenis"];
        $this->configCoreJenis = $arrKiriman["coreJenis"];
        $this->configLayoutJenis = $arrKiriman["layoutJenis"];
        $this->configValuesJenis = $arrKiriman["valuesJenis"];


        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("id in (" . implode(",", explode("-", $no)) . ")");
        $tmpTr = $tr->lookupJoined();
        cekBiru($this->db->last_query());
        //endregion


        $cancelPackingId = isset($tmpTr[0]->cancel_packing_source_id) ? $tmpTr[0]->cancel_packing_source_id : 0;
        $tmpTrCancelPacking = array();
        $id_top_source_cancel_packing = array();
        if ($cancelPackingId > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in (" . implode(",", explode("-", $cancelPackingId)) . ")");
            $tmpTrCancelPacking = $tr->lookupJoined();
            $id_top_source_cancel_packing = $tmpTrCancelPacking[0]->id_top;
        }

        $signNumbers = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($no)->result();
        if (sizeof($tmpSign) > 0) {
            $sCtr = 0;
            foreach ($tmpSign as $row) {
                $signNumbers[$sCtr] = "" . $row->step_number;
                $sCtr++;
            }
        }

        $rawItems = array();
        if (sizeof($tmpTr) > 0) {
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($sessionData[$cCode])) {
                $sessionData[$cCode] = null;
                unset($sessionData[$cCode]);
            }

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
                heInitGates_he_cart($this->jenisTr, $initMasterValues);
            }
            else {
                $this->load->Model("Mdls/MdlCompany");
                $comPro = new MdlCompany();
                $tmpCompanyProfile = $comPro->lookupAll()->result();
                $jn_usaha = $tmpCompanyProfile[0]->jenis_usaha;
                $masterPpnData = $this->config->item("pairPajak");
                $masterPPN = $masterPpnData[$jn_usaha]["value"]["default"];
                $initMasterValues = array(
                    "olehID" => $tmpTr[0]->oleh_id,
                    "olehName" => $tmpTr[0]->oleh_nama,
                    "sellerID" => $tmpTr[0]->seller_id,
                    "sellerName" => $tmpTr[0]->seller_nama,
                    "placeID" => $tmpTr[0]->cabang_id,
                    "placeName" => $tmpTr[0]->cabang_nama,
                    "divID" => $tmpTr[0]->div_id,
                    "divName" => $tmpTr[0]->div_nama,
                    "cabangID" => $tmpTr[0]->cabang_id,
                    "cabangName" => $tmpTr[0]->cabang_nama,
                    "gudangID" => $tmpTr[0]->gudang_id,
                    "gudangName" => $tmpTr[0]->gudang_nama,
                    "jenis_usaha" => $jn_usaha,
                    "tokoID" => $tmpTr[0]->toko_id,
                    "tokoNama" => $tmpTr[0]->toko_nama,
                    "jenisTr" => $this->jenisTr,
                    "jenisTrMaster" => $this->jenisTr,
                    "jenisTrTop" => $this->configUiJenis['steps'][$stepNumber]['target'],
                    "jenisTrName" => $this->configUiJenis['steps'][$stepNumber]['label'],
                    "stepNumber" => $stepNumber,
                    "stepCode" => isset($this->configUiJenis['steps'][$stepNumber]['target']) ? $this->configUiJenis['steps'][$stepNumber]['target'] : 0,
                    "dtime" => dtimeNow(),
                    "fulldate" => dtimeNow("Y-m-d"),
                    "ppnFactor" => $masterPPN["ppnFactor"],
                );
                $sessionData = heInitGates_ns_he_cart($this->jenisTr, $initMasterValues);
            }


            //region session init
            if (!isset($sessionData[$cCode])) {
                $sessionData[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($sessionData[$cCode]['main'])) {
                $sessionData[$cCode]['main'] = array();
            }
            if (!isset($sessionData[$cCode]['items'])) {
                $sessionData[$cCode]['items'] = array();
            }
            //endregion

            $trID = $tmpTr[0]->transaksi_id;
            $itemLabels = isset($this->configLayoutJenis['receiptDetailFields'][$stepNumber]) ? $this->configLayoutJenis['receiptDetailFields'][$stepNumber] : array();
            $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields'][$stepNumber]) ? $this->configUiJenis['shoppingCartNumFields'][$stepNumber] : array();
            $subAmountConfig = isset($this->configUiJenis['shoppingCartAmountValue'][$stepNumber]) ? $this->configUiJenis['shoppingCartAmountValue'][$stepNumber] : null;
            $measurementDetails = isset($this->configUiJenis["receiptMesurementRows"]) ? $this->configUiJenis["receiptMesurementRows"] : array();
            $validatePaymentLocker = isset($this->configUiJenis["validatePaymentSource"][$stepNumber]) ? $this->configUiJenis["validatePaymentSource"][$stepNumber] : array();
            $itemsChild = isset($this->configUiJenis["shopingCartDetailFields"][$stepNumber]['fields']) ? $this->configUiJenis["shopingCartDetailFields"][$stepNumber]['fields'] : array();//dipake detil pembelian aset
            $itemsChildGate = isset($this->configUiJenis["shopingCartDetailFields"][$stepNumber]['gate']) ? $this->configUiJenis["shopingCartDetailFields"][$stepNumber]['gate'] : array();//dipake detil pembelian aset/penambahan aset dari supplies sebagai switcer baca item atau main

            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $afterTargetStepNum = ($currentStepNum + 1);
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;
            //--------------------------------
            $gudangStatusJenis = $tmpTr[0]->gudang_status_jenis;
            $idsHis = ($tmpTr[0]->ids_his != null) ? blobDecode($tmpTr[0]->ids_his) : array();

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                //region periksa locker value;
                $tempLocker = array();
                $tempBtnUndo = array();
                if (sizeof($validatePaymentLocker) > 0) {
                    $mdlName = "Mdls/" . $validatePaymentLocker;
                    $this->load->model($mdlName);
                    $l = new $validatePaymentLocker();
                    $l->addFilter("transaksi_id='$no'");
                    $l->addFilter("state='active'");
                    $l->addFilter("nilai > 0");
                    $tempLocker = $l->lookupAll()->result();
                    //cekUngu($this->db->last_query() . " >> $validatePaymentLocker");
                    //arrPrint($tempLocker);
                    if (sizeof($tempLocker) > 0) {
                        $tempBtnUndo = array(
                            "allowedUndone" => false,//tidak boleh di undo/reject
                            "allowedFollow" => false,//boleh di followup
                        );
                    }
                    else {
                        //                    arrPrint($this->config->item('payment_source')[$this->jenisTr]);
                        //                    cekKuning(":: $currentStepNum ::");
                        $jnTarget = isset($this->config->item('payment_source')[$this->jenisTr][$currentStepNum][0]['jenisTarget']) ? $this->config->item('payment_source')[$this->jenisTr][$currentStepNum][0]['jenisTarget'] : "";
                        //                    cekHitam(":: ** $jnTarget ** ::");
                        $tempBtnUndo = array(
                            "allowedUndone" => true,// boleh di undo/reject
                            "allowedFollow" => true,//tidak boleh di followup
                            "label" => isset($this->configUi[$jnTarget]['label']) ? $this->configUi[$jnTarget]['label'] : "",
                        );
                    }
                }


                //            arrPrint($tempLocker);
                //endregion

                //==periksa apakah ada ganjalan
                $trA = new MdlTransaksi();
                $extSteps = $trA->lookupExtSteps($masterID);
                if (sizeof($extSteps) > 0) {
                    //                cekmerah("ada ganjalan step sebanyak " . sizeof($extSteps));
                }
                else {
                    //                cekhijau("TAK ada ganjalan step");
                }
                $paySrcs = $trA->lookupPaymentSrcs($masterID, $this->jenisTr . "_");
                if (sizeof($paySrcs) > 0) {
                    //                cekmerah("ada ganjalan paymentSrc sebanyak " . sizeof($paySrcs));
                }
                else {
                    //                cekhijau("TAK ada ganjalan paymentSrc");
                }

                $allowEdit = isset($this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowEdit']) ? $this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowEdit'] : false;
                $allowCancel = isset($this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowCancel']) ? $this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowCancel'] : false;
                $editableFields = isset($this->configUi[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber] : array();

                //region valid items
                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $tr->addFilter("id in (" . implode(",", explode("-", $no)) . ")");
                $tr->addFilterJoin("sub_step_number='" . $currentStepNum . "'");
                $tr->addFilterJoin("next_substep_code='" . $this->configUi[$this->jenisTr]['steps'][$stepNumber]['target'] . "'");
                $tr->addFilterJoin("next_substep_num='$stepNumber'");
                $tr->addFilterJoin("valid_qty>0");
                $tmpTr = $tr->lookupJoined();
                //            cekhitam($this->db->last_query());

                $id_top = isset($tmpTr[0]->id_top) ? $tmpTr[0]->id_top : "";


                //            arrPrint($tmpTr);
                //            cekHere($id_top);
                //            cekHere($no);
                //            matiHere($no);


                if ($id_top != "") {
                    $idTr = isset($tmpTr[0]->id) ? $tmpTr[0]->id : "";
                    $trPack = new MdlTransaksi();
                    $trPack->setFilters(array());
                    $trPack->addFilter("id_top in (" . implode(",", explode("-", $id_top)) . ")");
                    $trPack->addFilterJoin("valid_qty>0");
                    $tmpTrPacked = $trPack->lookupJoined();
                }


                $tmpTrPrePacked = array();
                if (sizeof($id_top_source_cancel_packing) > 0) {
                    $trPrePack = new MdlTransaksi();
                    $trPrePack->setFilters(array());
                    $trPrePack->addFilter("id_top in (" . implode(",", explode("-", $id_top_source_cancel_packing)) . ")");
                    $trPrePack->addFilterJoin("valid_qty>0");
                    $trPrePack->addFilter("trash_4<1");
                    $tmpTrPrePacked = $trPrePack->lookupJoined();
                }
                else {
                    $trPrePack = new MdlTransaksi();
                    $trPrePack->setFilters(array());
                    $trPrePack->addFilter("cancel_packing_source_id in (" . implode(",", explode("-", $no)) . ")");
                    $trPrePack->addFilterJoin("valid_qty>0");
                    $trPrePack->addFilter("trash_4<1");
                    $tmpTrPrePacked = $trPrePack->lookupJoined();
                }

                $arrPreTmp__ = array();
                if (!empty($tmpTrPrePacked)) {
                    foreach ($tmpTrPrePacked as $y => $dd) {
                        if (!isset($arrPreTmp__[$dd->jenis][$dd->produk_id])) {
                            $arrPreTmp__[$dd->jenis][$dd->produk_id] = 0;
                        }
                        $arrPreTmp__[$dd->jenis][$dd->produk_id] += $dd->produk_ord_jml;
                    }
                }

                $arrTmp__ = array();

                if (sizeof($tmpTrPacked) > 0) {
                    foreach ($tmpTrPacked as $y => $dd) {
                        if (!isset($arrTmp__[$dd->jenis][$dd->produk_id])) {
                            $arrTmp__[$dd->jenis][$dd->produk_id] = 0;
                        }
                        $arrTmp__[$dd->jenis][$dd->produk_id] += $dd->produk_ord_jml;
                    }
                }


                $extractedItems = array();//==untuk urusan update transaksi referer
                $validItems = array();
                $validItemSends = array();
                $validItemReqCancels = array();
                $validItemCancels = array();
                $validItemPreCancels = array();
                $validItemSents = array();

                if (sizeof($tmpTr) > 0) {
                    cekmerah("ada yang mau diekstrak");
                    foreach ($tmpTr as $row) {
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
                            "cancel_qty" => isset($row->cancel_qty) ? $row->cancel_qty : 0,
                            "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->valid_qty),
                        );
                    }
                }
                else {
                    cekmerah("TIDAK ada yang mau diekstrak");
                }
                cekBiru($validItems);


                //endregion

                //
                //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
                $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
                $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
                //            //cekMerah($this->db->last_query());
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
                //            arrPrint($detailValues);
                //endregion

            }


            //region take from registries
            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
            $tmpReg = $trr->lookupDataRegistries()->result();
            cekKuning($this->db->last_query());
            //            matiHere();
            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4 = array();
            $items4_sum = array();
            $items5_sum = array();
            $items6 = array();
            $items6_sum = array();
            $items7 = array();
            $items7_sum = array();
            $items8_sum = array();
            $items9_sum = array();
            $items10_sum = array();
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
                        if ($val_reg == NULL) {
                            $val_reg = blobEncode(array());
                        }
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
                            case "items4"://
                                $items4 = $items4 + unserialize(base64_decode($val_reg));
                                break;
                            case "items4_sum"://
                                $items4_sum = $items4_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items5_sum"://
                                $items5_sum = $items5_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items6"://
//                                arrPrint($items6);
//                                arrPrint(unserialize(base64_decode($val_reg)));
//                                cekHere($val_reg);
                                $items6 = $items6 + unserialize(base64_decode($val_reg));
                                break;
                            case "items6_sum"://
                                $items6_sum = $items6_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items7"://
                                $items7 = $items7 + unserialize(base64_decode($val_reg));
                                break;
                            case "items7_sum"://
                                $items7_sum = $items7_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items8_sum"://
                                $items8_sum = $items8_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items9_sum"://
                                $items9_sum = $items9_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items10_sum"://
                                $items10_sum = $items10_sum + unserialize(base64_decode($val_reg));
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

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                //region replacer Downpayment avail
                if (isset($mainInputs['dp'])) {
                    if (isset($this->configUi[$this->jenisTr]["updateDownpayment"][$stepNumber])) {
                        //                    arrPrint($mainInputs);
                        $this->load->model("Coms/ComLockerValue");
                        $mi = new ComLockerValue();
                        $mi->addFilter("produk_id='" . $main['masterID'] . "'");
                        $mainInputAvail = $mi->fetchBalances("downpayment");
                        $lockerVal = $mainInputAvail[0]->nilai;
                        //                    $mainValTmp = reversePpn($mainInputs['dp'],"10");
                        $mainValTmp_dp = $main['dp_value'];
                        $mainValTmp_dp_ppn = $main['dp_ppn_value'];

                        if (isset($main['valid_dp']) && $main['valid_dp'] > 0) {
                            cekOrange();
                            $validate = round($mainInputAvail[0]->nilai - $mainValTmp_dp, 2);
                            if ($lockerVal > 0) {
                                $valnew = $main['new_net1'] - $lockerVal > 0 ? 0 : $main['new_net1'];
                                $main['dp_value'] = $valnew;
                                $main['dp_ppn_value'] = $valnew * 10 / 100;
                            }
                            else {
                                $main['dp_value'] = 0;
                                $main['dp_ppn_value'] = 0;
                                //                        $main['valid_dp'] = 0;
                            }

                            if ($validate > 0) {
                                $mainInputs['dp'] = $mainInputAvail[0]->nilai;

                            }
                            else {
                                if ($validate < 0) {
                                    if (isset($main['valid_dp']) && $main['valid_dp'] > 0) {
                                        $main['downpayment'] = $mainInputs['dp'];
                                        $mainInputs['downpayment'] = $mainInputs['dp'];
                                        $main['total_ui'] = $masterTableInValueParams['tagihan'] - $masterTableInValueParams['dp_ppn_value'];

                                    }
                                    unset($mainInputs['dp']);
                                    //                                cekhere("sini po");
                                }
                                //                        if(!isset($main['valid_dp'])){
                                //                            $main['valid_dp'] = $mainInputAvail[0]->nilai;
                                //                        }

                            }
                        }
                        else {
                            //                         $mainInputs['dp'] = 0;
                            $main['dp_value'] = 0;
                            $main['dp_ppn_value'] = 0;
                            unset ($mainInputs['dp']);
                        }

                        if (!isset($main['valid_dp'])) {
                            $main['valid_dp'] = $mainInputAvail[0]->nilai;
                        }
                    }


                }
                else {
                    if (isset($main['valid_dp']) && $main['valid_dp'] > 0) {
                        $main['downpayment'] = $main['dp'];
                        $mainInputs['downpayment'] = $main['dp'];
                        //                    $main['total_ui'] = $masterTableInValueParams['tagihan'] - $masterTableInValueParams['nilai_tambah_ppn_out'];
                        $main['total_ui'] = $masterTableInValueParams['new_net1'] - $main['valid_dp'];
                        cekBiru($masterTableInValueParams['new_net1'] . " - " . $main['valid_dp']);
                    }
                    else {
                        if (isset($masterTableInValueParams['new_net1'])) {

                            $main['total_ui'] = $masterTableInValueParams['new_net1'];
                        }
                        else {
                            $main['total_ui'] = isset($masterTableInValueParams['nett1']) ? $masterTableInValueParams['nett1'] : 0;
                            $main['new_grand_ppn'] = isset($masterTableInValueParams['grand_ppn']) ? $masterTableInValueParams['grand_ppn'] : 0;
                        }
                    }
                }
                //endregion

                $masterReplacers = array(
                    "jenisTrMaster" => $this->jenisTr,
                    "jenisTrTop" => $masterTableInParams['jenis_top'],
                    "harga" => 0,
                    "masterID" => $masterID,
                );
                foreach ($masterReplacers as $key => $src) {
                    $main[$key] = $src;
                    $mainValues[$key] = $src;
                    $masterGates[$key] = $src;
                }

            }

            //==revalidate items
            $this->load->library("FieldCalculator");
            $this->load->helper("he_angka");
            $cal = new FieldCalculator();

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                $itemChildData = array();
                if (sizeof($items) > 0) {
                    foreach ($items as $xid => $iSpec) {
                        $id = $iSpec['id'];
                        $tipeSize = isset($iSpec['detilSize']) && sizeof($iSpec['detilSize']) > 0 ? $iSpec['detilSize'] : "";

                        if (array_key_exists($id, $validItems)) {
                            $items[$id]['jml'] = $validItems[$id];
                            $items[$id]['max_jml'] = $validItems[$id];
                            $items[$id]['packed_jml'] = $validItemPackeds[$id];
                            $items[$id]['sent_jml'] = $validItemSends[$id];
                            $items[$id]['cancel_jml'] = $validItemCancels[$id];
                            $items[$id]['req_cancel_jml'] = $validItemPreCancels[$id];
                            if (sizeof($editableFields) > 0) {
                                foreach ($editableFields as $fName) {
                                    $items[$id]["max_$fName"] = isset($iSpec[$fName]) ? $iSpec[$fName] : 0;
                                }
                            }

                            if (sizeof($measurementDetails)) {
                                if (in_array($stepNumber, $measurementDetails["allowView"]) && isset($measurementDetails[$tipeSize])) {
                                    $selectedColl = $measurementDetails[$tipeSize];
                                    foreach ($selectedColl as $colSelected => $tempHelper) {
                                        foreach ($tempHelper as $newKey => $heAngka) {
                                            $items[$id][$newKey] = $heAngka($iSpec[$colSelected]);
                                        }
                                    }
                                }
                            }


                            if ($subAmountConfig != null) {
                                $tmpEx = $cal->multiExplode($subAmountConfig);
                                if (sizeof($tmpEx) > 1) {
                                    //                            echo lgShowAlert("menghitung subtotal pakai rumus $subAmountConfig di step ke # $stepNumber");
                                    $newSrc = $subAmountConfig;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($items[$id][$val2])) {
                                            $newSrc = str_replace($val2, $items[$id][$val2], $newSrc);

                                        }
                                        else {
                                            if (isset($tmp[$val2])) {
                                                $newSrc = str_replace($val2, $items[$val2], $newSrc);

                                            }
                                            else {
                                                $newSrc = str_replace($val2, "0", $newSrc);

                                            }
                                        }


                                    }
                                    $subtotal = $cal->calculate($newSrc);


                                }
                                else {
                                    //                            echo lgShowAlert("memasang subtotal dari $subAmountConfig");
                                    $subtotal = $items[$id][$subAmountConfig];

                                }
                            }
                            else {
                                //                        echo lgShowAlert("tidak mengapa-apakan subtotal");
                                $subtotal = 0;

                            }

                            $items[$id]['subtotal'] = $subtotal;
                            //region item child

                            if (sizeof($itemsChild) > 0 && ($itemsChildGate == 'detail')) {
                                //                        if (sizeof($itemsChild)  > 0 ) {
                                for ($x = 1; $x <= $validItems[$id]; $x++) {
                                    foreach ($itemsChild as $col => $col_label) {
                                        $itemChildData[$id][$x][$col] = isset($items[$id][$col]) ? $items[$id][$col] : "";
                                        $itemChildData[$id][$x]["jml"] = 1;
                                        $itemChildData[$id][$x]["qty"] = 1;
                                        $itemChildData[$id][$x]["folders"] = $main['pihakMainID'];
                                    }

                                }
                                //                            arrPrint($itemsChild);
                                //                        foreach ($itemsChild as )
                            }

                            //endregion
                            cekBiru($itemsKomposisi);
                            if (sizeof($itemsKomposisi) > 0) {
                                if (array_key_exists($id, $itemsKomposisi)) {
                                    foreach ($items2[$id] as $jenis_komposisi => $iiSpec) {
                                        foreach ($iiSpec as $ee => $eeSpec) {
                                            $komposisi = $itemsKomposisi[$id][$jenis_komposisi][$ee];
                                            // re-kalkulasi gerbang items2
                                            $items2[$id][$jenis_komposisi][$ee]['jml'] = $komposisi->jml * $validItems[$id];
                                            $items2[$id][$jenis_komposisi][$ee]['sub_nilai'] = $komposisi->nilai * $validItems[$id];
                                            cekhijau("pID: $id [], jml: " . $komposisi->jml . " validItems: " . $validItems[$id]);
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            unset($items[$id]);
                            unset($items2[$id]);
                            unset($childGates[$id]);
                            unset($childTableInParams[$id]);
                            unset($childTableInValueParams[$id]);
                        }
                    }

                    if (sizeof($itemsKomposisi) > 0) {
                        $items2_sum = array();// supplies-nya...
                        $items3_sum = array();// biaya-nya...
                        foreach ($items2 as $pID => $pSpec) {
                            foreach ($pSpec as $jenis => $jSpec) {
                                foreach ($jSpec as $eSpec) {
                                    if ($jenis == "produk") {
                                        if (!isset($items2_sum[$eSpec['id']])) {
                                            $items2_sum[$eSpec['id']] = $eSpec;
                                            $items2_sum[$eSpec['id']]['jml'] = 0;
                                            $items2_sum[$eSpec['id']]['produk_ids'] = array();
                                        }
                                        $items2_sum[$eSpec['id']]['jml'] += $eSpec['jml'];
                                        $items2_sum[$eSpec['id']]['produk_ids'][$pID] = $pID;

                                        cekBiru("pID: " . $eSpec['id'] . " jml: " . $eSpec['jml']);
                                    }
                                    if ($jenis == "biaya") {
                                        if (!isset($items3_sum[$eSpec['id']])) {
                                            $items3_sum[$eSpec['id']] = $eSpec;
                                            $items3_sum[$eSpec['id']]['jml'] = 0;
                                            $items3_sum[$eSpec['id']]['sub_nilai'] = 0;
                                            $items3_sum[$eSpec['id']]['produk_ids'] = array();
                                        }
                                        $items3_sum[$eSpec['id']]['jml'] += $eSpec['jml'];
                                        $items3_sum[$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                        $items3_sum[$eSpec['id']]['produk_ids'][$pID] = $pID;
                                    }
                                }
                            }
                        }
                    }

                }
                if (sizeof($itemsChild) > 0 && ($itemsChildGate == 'main')) {

                    $fieldAlias = isset($this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['fieldAlias']) ? $this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['fieldAlias'] : $itemsChild;//dipake detil pembelian aset
                    foreach ($fieldAlias as $col => $col_label) {
                        $itemChildData[$main['pihakMainRulesID']][1][$col] = isset($main[$col_label]) ? $main[$col_label] : "";
                    }
                    $itemChildData[$main['pihakMainRulesID']][1]["jml"] = 1;
                    $itemChildData[$main['pihakMainRulesID']][1]["qty"] = 1;
                    $itemChildData[$main['pihakMainRulesID']][1]["folders"] = $main['pihakID'];
                    //                cekBiru("main");
                }

            }

            //region session-swapper
            unset($main["nilai_pembulatan"]);
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;

            if (isset($sessionData[$cCode]["main"])) {
                $sessionData[$cCode]["main"]["olehID"] = "-100";
                $sessionData[$cCode]["main"]["olehName"] = "system";
                foreach ($sessionData[$cCode]["main"] as $mkey => $mval) {
                    $main[$mkey] = $mval;
                }
            }

            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4" => $items4,
                "items4_sum" => $items4_sum,
                "items5_sum" => $items5_sum,
                "items6" => $items6,
                "items6_sum" => $items6_sum,
                "items7" => $items7,
                "items7_sum" => $items7_sum,
                "items8_sum" => $items8_sum,
                "items9_sum" => $items9_sum,
                "items10_sum" => $items10_sum,
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
                //        ""=>$childAddValues ,
                "main_add_fields" => $masterAddFields,
                //                "main_applets"          => $mainApplets,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                //
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
                "lockerPayment" => $tempBtnUndo,
                "items_komposisi" => $itemsKomposisi,
            );
            foreach ($swappers as $targetVar => $src) {
                $sessionData[$cCode][$targetVar] = $src;

            }
            //endregion

            if (sizeof($idsHis) > 0) {
                foreach ($idsHis as $step_his => $data_his) {
                    $sessionData[$cCode]['main']['referenceID_' . $step_his] = $data_his["trID"];
                    $sessionData[$cCode]['main']['referenceNumber' . $step_his] = $data_his["nomer"];
                }
            }
            $sessionData[$cCode]['main']['referenceID_current'] = $tmpTr[0]->id;
            $sessionData[$cCode]['main']['referenceNumber_current'] = $tmpTr[0]->nomer;

            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == "11" ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("error on build values on PrePrev " . __LINE__ . " silahkan relogin");

            $this->load->helper("he_value_builder");

            //-------------------------------------------
            $receiptElementsInjector = isset($this->configUiJenis["receiptElementsInjector"]) ? $this->configUiJenis["receiptElementsInjector"] : array();
            if (sizeof($receiptElementsInjector) > 0) {
                foreach ($receiptElementsInjector as $eName => $eSpec) {

                    if ((!isset($main[$eName])) || (!isset($mainElements[$eName]))) {
                        //                        cekhitam("tidak kenal ppv, maka diinjeckkan...");
                        if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                            //                        cekmerah("default value for $eName is: " . $eSpec['defaultValue']);
                            $defValueSrc = $eSpec['defaultValue'];
                            switch ($eSpec['elementType']) {
                                case "dataModel":
                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
                                    break;
                                case "dataField":
                                    heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $this->configUiJenis);
                                    break;
                            }
                            $sessionData[$cCode]['main_elements'][$eName]['autoSelect'] = true;
                        }
                        else {//==cek apakah pilihannya cuma satu
                            if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                            }
                            else {
                                //                            cekHere(__LINE__);
                                switch ($eSpec['elementType']) {
                                    case "dataModel":
                                        $amdlName = $eSpec['mdlName'];
                                        $this->load->model("Mdls/" . $amdlName);
                                        $labelSrc = $eSpec['labelSrc'];
                                        $keySrc = $eSpec['key'];
                                        $oo = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                        //                                    cekHitam($amdlName);
                                        //                                    arrPrint($aFilter);
                                        if (sizeof($aFilter) > 0) {
                                            $oo = makeFilter($aFilter, $sessionData[$cCode]['main'], $oo);
                                        }
                                        $tmpo = $oo->lookupAll()->result();
                                        if (sizeof($tmpo) == 1) {
                                            $usedKey = $eSpec['key'];
                                            $defValueSrc = $tmpo[0]->$usedKey;
                                            heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
                                        }
                                        break;
                                    case "dataField":
                                        break;
                                }
                            }
                        }

                        resetValues($this->jenisTr);
                        $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $this->uri->segment(7), $this->uri->segment(6), $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);

                    }
                }
            }

            //==init replacer
            //==recover nilai HARGA master
            $sessionData[$cCode]['main']['harga'] = 0;
            $sessionData[$cCode]['main']['currentID'] = $no;

            //==default load dari nota, maka dianggap langsung done
            $sessionData[$cCode]['main']['status_4'] = 1;
            $sessionData[$cCode]['main']['trash_4'] = 0;
            if (sizeof($sessionData[$cCode]['items']) > 0) {
                foreach ($sessionData[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $sessionData[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                    /*---untuk keperluan mobile view---*/
                    $sessionData[$cCode]['items'][$xid]['jml_target_scan'] = $iSpec['jml'];
                }
            }


            //overwriter ppn facrot
//            if (isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == $this->session->login["ppnFactor"]) {
//
//            }
//            else {
//                $sessionData[$cCode]["main"]["ppnFactor"] = $this->session->login["ppnFactor"];//baca dari session login
//            }
            cekHitam("PPN_FACTOR: " . $sessionData[$cCode]["main"]["ppnFactor"]);
            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == "11" ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("error on build values on PrePrev " . __LINE__ . " silahkan relogin");


            /* ----------------------------------------------------------------------
             * deteksi mobile auto atau hanya orang tertentu,
             * diatur di heWeb mobile
             * ----------------------------------------------------------------------*/
//            $isMob0 = isMobile_he_misc();
//            $isMob = isset($_GET['ismob']) ? $_GET['ismob'] : $isMob0;
//            cekHere("mob: $isMob");
//

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                // region reload data produk sesuai config dari shoppingcart-----------------------------------
                $arrItemsKey = array_keys($sessionData[$cCode]["items"]);
                $arrDataTambahan = $this->dataTambahan;

                $selectorSrcModel = isset($sessionData[$cCode]['main']['pihakMdlNameSrc']) ? $sessionData[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                $this->load->model("Mdls/" . $selectorSrcModel);
                $b = new $selectorSrcModel();
                $b->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
                $tmpB = $b->lookupAll()->result();
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $rows = $row;
                        $tmp = (array)$row;
                        $produk_id = $idp = $row->id;
                        $produk_jenis = $row->kategori_nama;

                        $arrCat = array();
                        $arrCode = array();
                        if ($produk_jenis == "unit") {
                            foreach ($arrDataTambahan as $cat => $catSpec) {
                                foreach ($catSpec as $dkey => $dval) {
                                    if (isset($rows->$dval) && ($rows->$dval != NULL)) {
//                                        $sessionData[$cCode]['items2'][$produk_id][$rows->$dval] = array();
                                        //--------------
                                        if (!isset($arrCat[$cat])) {
                                            $arrCat[$cat] = 0;
                                        }
                                        $arrCat[$cat] += 1;
                                        //--------------
                                        if (!isset($arrCode[$rows->$dval])) {
                                            $arrCode[$rows->$dval] = 0;
                                        }
                                        $arrCode[$rows->$dval] += 1;
                                        //--------------
                                    }
                                }
                            }

                        }
                        else {
                            //handle serial 1
                            $jml_serial = $rows->jml_serial;
                            $tmp['jml_serial'] = $jml_serial;
                            $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                            if ($jml_serial * 1 == 1) {
                                $d_kode = $rows->kode;
//                                $sessionData[$cCode]['items2'][$produk_id][$d_kode] = array();
                            }
                        }
                        $keterangan = "";
                        $static_keterangan = "";
                        if (!empty($arrCat)) {
                            foreach ($arrCat as $kcat => $vcat) {
                                $new_vcat = $vcat * $sessionData[$cCode]['items'][$idp]["jml"];
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
                                $sessionData[$cCode]['items'][$idp][$new_keyy] = $vcat;
                            }
                        }
                        if (!empty($arrCode)) {
                            foreach ($arrCode as $kcat => $vcat) {
                                $new_vcat = $vcat * $sessionData[$cCode]['items'][$idp]["jml"];
                                $sessionData[$cCode]['items'][$idp][$kcat] = $new_vcat;
                            }
                        }
                        $sessionData[$cCode]['items'][$idp]['keterangan'] = $keterangan;
                        $sessionData[$cCode]['items'][$idp]['static_keterangan'] = $static_keterangan;
                    }
                }
                // endregion reload data produk sesuai config dari shoppingcart-----------------------------------
            }

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                // region copy gerbang serial dari distribusi
                $shoppingCartCopySerialNumber = isset($this->configUi[$this->jenisTr]["shoppingCartCopySerialNumber"][$stepNumber]) ? $this->configUi[$this->jenisTr]["shoppingCartCopySerialNumber"][$stepNumber] : array();
                if (sizeof($shoppingCartCopySerialNumber) > 0) {
                    $statusGudangConfig = $shoppingCartCopySerialNumber["statusGudang"];
                    $copyGateConfig = $shoppingCartCopySerialNumber["copyGate"];
                    $copyJenisConfig = $shoppingCartCopySerialNumber["copyJenis"];
                    if ($gudangStatusJenis == $statusGudangConfig) {
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
                                    $sessionData[$cCode][$key_reg] = blobDecode($val_reg);
                                }
                            }
                        }
                    }

                }
                // endregion copy gerbang serial dari distribusi
            }


//            resetValues($this->jenisTr);
            $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $currentStepNum, $stepNumber, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);

            return $sessionData;
        }
        else {
            mati_disini(("No such transaction. You may want to refresh the browser to re-fetch actual content."));
        }

    }

    public function save($sessionData, $arrKiriman)
    {
//        echo "<script>top.writeProgress('MEMULAI PROSES SAVING....');</script>";

//        if ($this->transaksiMaintenance === true) {
//            $msg = $this->transaksiMaintenanceMsg['title'] . "<br>" . $this->transaksiMaintenanceMsg['mesage'];
//            mati_disini($msg);
//        }
//arrPrintOrange($sessionData);
        //        $this->jenisTr = $this->uri->segment(3);
//        $cCode = $this->cCode;
//        $configUi = $this->configUi;
        $no = $arrKiriman["transaksi_id"];
        $stepNumber = $arrKiriman["step_number"];
        $currentStepNum = $arrKiriman["currentStepNumber"];
        $this->jenisTr = $arrKiriman["jenisTr"];
        $this->configUiJenis = $arrKiriman["uiJenis"];
        $this->configCoreJenis = $arrKiriman["coreJenis"];
        $this->configLayoutJenis = $arrKiriman["layoutJenis"];
        $this->configValuesJenis = $arrKiriman["valuesJenis"];
        $transaksi_current_dtime = $arrKiriman["dtime"];
        $transaksi_current_fulldate = $arrKiriman["fulldate"];
        $transaksi_current_cabang_id = $arrKiriman["cabang_id"];
        $transaksi_current_cabang_nama = $arrKiriman["cabang_nama"];
        $transaksi_current_gudang_id = $arrKiriman["gudang_id"];
        $transaksi_current_gudang_nama = $arrKiriman["gudang_nama"];
        $cCode = "_TR_" . $this->jenisTr;
        cekHitam(":::: jenisTR : " . $this->jenisTr);
        $modul_transaksi = $this->config->item("heTransaksi_ui")[$this->jenisTr]["modul"];
        $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($this->configUiJenis["steps"][1]["target"]) ? $this->configUiJenis["steps"][1]["target"] : NULL;
        $relOptionConfigs = isset($this->configUiJenis['relativeOptions']) ? $this->configUiJenis['relativeOptions'] : array();
        $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin");
        $inputLabels = array();
        $inputAuthConfigs = array();

        $this->load->model("MdlTransaksi");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $mongoList = array();
        $mongRegID = array();
        if (isset($sessionData[$cCode])) {
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //            unset($sessionData[$cCode]["main"]["bookingNumber"]);
                if (!isset($sessionData[$cCode]["main"]["bookingNumber"])) {
                    $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                    mati_disini($msg);
                }
                elseif ($sessionData[$cCode]["main"]["bookingNumber"] == null) {
                    $msg = "Nomer/Kode Booking transaksi baru belum terdaftar. Silahkan referesh halaman ini. code: " . __LINE__;
                    mati_disini($msg);
                }
                $nomerBookingTransaksi = $sessionData[$cCode]["main"]["bookingNumber"];
            }

            if (!isset($sessionData[$cCode]['items'])) {
                mati_disini("belum ada item yang dipilih");
            }
            else {
                if (sizeof($sessionData[$cCode]['items']) < 1) {
                    mati_disini("belum ada item yang dipilih");
                }
            }
            echo("now processing your transaction..<br>");

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region build table rekening
                $buildTablesMaster = isset($this->configCoreJenis['components'][$jenisTrTarget]['master']) ? $this->configCoreJenis['components'][$jenisTrTarget]['master'] : array();
                $buildTablesDetail = isset($this->configCoreJenis['components'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['components'][$jenisTrTarget]['detail'] : array();
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
                    $bCtr = 0;
                    foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                        $bCtr++;
                        $mdlName = $buildTablesMaster_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $sessionData[$cCode]['main'][$mdlName], $mdlName);
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
                                    $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
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
                        foreach ($sessionData[$cCode]['items'] as $itemSpec) {
                            //arrPrint($itemSpec);
                            $mdlName = $buildTablesDetail_specs['comName'];
                            cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                                //                        $mdlName = str_replace($mdlName, $sessionData[$cCode]['main'][$mdlName], $mdlName);
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
                                        //                                    $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
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
            }

//
//            $this->db->trans_start();

            cekMerah("start pre-processor...");
            //
            //region pre-processors (item)
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['preProc']['detail']) ? $sessionData[$cCode]['revert']['preProc']['detail'] : array();
                cekMerah(":: iterator preprocc dari gerbang revert ::");
                arrPrintWebs($iterator);
            }
            else {
                $iterator = isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
            }

            if (sizeof($iterator) > 0) {


                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields']) ? $this->configUiJenis['shoppingCartNumFields'] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub-preproc: $comName, initializing values <br>";

                        foreach ($sessionData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();

                            //                            $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }


                                $subParams['static']["fulldate"] = $transaksi_current_fulldate;
                                $subParams['static']["dtime"] = $transaksi_current_dtime;
                                $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " oleh ";
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
                                            if (!isset($sessionData[$cCode][$gateName])) {
                                                $sessionData[$cCode][$gateName] = array();
                                                //                                    cekhijau("building the session: $gateName");
                                            }
                                            else {
                                                //                                    cekhijau("NOT building the session: $gateName");
                                            }

                                            foreach ($paramSpec as $id => $gSpec) {
                                                //										$id=$gSpec['id'];


                                                if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                    $sessionData[$cCode][$gateName][$id] = array();
                                                }


                                                if (isset($sessionData[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                            $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                        }

                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                if (isset($sessionData[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $sessionData[$cCode][$srcGateName][$id][$key] = $val;
                                                        }

                                                    }
                                                }

                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        //cekHere("$id === $key => $label");
                                                        if (isset($sessionData[$cCode][$gateName][$id][$key])) {
                                                            $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
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
                if (isset($sessionData[$cCode]["revert"]["preProc"]["replacer"])) {
                    $replace = $sessionData[$cCode]["revert"]["preProc"]["replacer"];
                    $jenisTrReference = $sessionData[$cCode]["main"]["jenisTr_reference"];
                    switch ($jenisTrReference) {
                        case "460":
                            $tempCalculate = array(
                                //                                "selisih" => ($sessionData[$cCode]["main"]["hpp_riil"] + $sessionData[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($sessionData[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                //                                "exchange__harga" => $sessionData[$cCode]["main"]["hpp_riil"],//riil
                                //                                "exchange__hpp_nppv" => $sessionData[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                //                                "exchange__ppv" => $sessionData[$cCode]["main"]["ppv_riil"],//riil+ppv
                            );
                            break;
                        default:
                            $tempCalculate = array(
                                "selisih" => ($sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"]) - ($sessionData[$cCode]["main"]["nett"] + $sessionData[$cCode]["main"]["ppv"]),
                                "hpp_nppv" => $sessionData[$cCode]["main"]["hpp"],
                                "hpp_nppn" => $sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"],
                            );
                            break;
                    }
                    //                    $tempCalculate = array(
                    //                        "selisih" => ($sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"]) - ($sessionData[$cCode]["main"]["nett"] + $sessionData[$cCode]["main"]["ppv"]),
                    //                        "hpp_nppv" => $sessionData[$cCode]["main"]["hpp"],
                    //                        "hpp_nppn" => $sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"],
                    //                    );

                    //arrPrintWebs($tempCalculate);
                    foreach ($replace['recalculate'] as $iKey => $gate) {
                        $sessionData[$cCode]["main"][$gate] = $tempCalculate[$gate];
                    }

                    cekLime($sessionData[$cCode]["main"]["hpp"] . "+" . $sessionData[$cCode]["main"]["ppn"] . "-" . $sessionData[$cCode]["main"]["nett"]);

                }

                //endregion


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            //region pre-processors (master)
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['preProc']['master']) ? $sessionData[$cCode]['revert']['preProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['master']) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {

                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields']) ? $this->configUiJenis['shoppingCartNumFields'] : array();


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
                                $realValue = makeValue($value, $sessionData[$cCode]['main'], $sessionData[$cCode]['main'], 0);
                                $subParams['static'][$key] = $realValue;

                                //                                cekPink2("$comName == $value || $realValue");
                                //                                cekPink2("valas_harga " . $sessionData[$cCode]['main']['valas_harga']);
                                //                                cekPink2("uang_muka_valas_harga " . $sessionData[$cCode]['main']['uang_muka_valas_harga']);
                            }

                            if (!isset($subParams['static']["transaksi_id"])) {
                                //									$subParams['static']["transaksi_id"] = $masterID;
                            }

                            $subParams['static']["fulldate"] = $transaksi_current_fulldate;
                            $subParams['static']["dtime"] = $transaksi_current_dtime;
                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " oleh ";
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
                                            if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                $sessionData[$cCode][$gateName][$id] = array();
                                            }

                                            if (isset($sessionData[$cCode][$gateName][$id])) {
                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                    foreach ($ggSpec as $key => $val) {
                                                        $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($sessionData[$cCode][$gateName][$id][$key])) {
                                                        $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                    }
                                                }
                                            }

                                        }
                                    }
                                    else {
                                        if (isset($sessionData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($sessionData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                cekHere("$id === $key => $label");
                                                if (isset($sessionData[$cCode]['main'][$key])) {
                                                    $sessionData[$cCode]['main']['sub_' . $key] = ($sessionData[$cCode]['main']['jml'] * $sessionData[$cCode]['main'][$key]);
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
                        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);


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
            $vd->setCCode($cCode);
            $vd->setConfigUiJenis($this->configUiJenis);
            $step = $sessionData[$cCode]['main']['step_number'];
            $vd->midValidate_ns($sessionData, $step);
            $vd->unionValidate_ns($sessionData);

            //===finalisasi sebelum masuk tabel beneran
            //===isinya ada pembentukan nomor nota dll


            //region penomoran receipt
            //<editor-fold desc="==========penomoran">
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $counterForNumber = array($this->configCoreJenis['formatNota']);
            echo "format_nota";
            arrPrintPink($counterForNumber);
            if (!in_array($counterForNumber[0], $this->configCoreJenis['counters'])) {
                mati_disini(__LINE__ . " Used number should be registered in 'counters' config as well");
            }
            echo "<div style='background:#ff7766;'>";
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
//                arrPrintKuning($cParams);
//                arrPrintHijau($cValues);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

            }
            echo "</div style='background:#ff7766;'>";
            arrPrintWebs($paramSpec);
            // mati_disini("hahaha " . __LINE__);

            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];
            $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);
            if (isset($this->configUiJenis['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUiJenis['steps'][2]['target'],
                    "label" => $this->configUiJenis['steps'][2]['label'],
                    "groupID" => $this->configUiJenis['steps'][2]['userGroup'],
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
            $configCustomParams = $this->configCoreJenis['counters'];
            $configCustomParams[] = "stepCode";
            //arrPrint($configCustomParams);
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
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
                    showLast_query("orange");
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);
            //mati_disini();

            // arrPrintKuning($paramSpec);
            //region addition on master


            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'nomer2' => $tmpNomorNotaAlias,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUiJenis['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUiJenis['steps'][1]['target'],


            );
            foreach ($addValues as $key => $val) {
                $sessionData[$cCode]['tableIn_master'][$key] = $val;
            }
            //endregion

            //
            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->configUiJenis['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUiJenis['steps'][1]['target'],


            );
            foreach ($sessionData[$cCode]['tableIn_detail'] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $sessionData[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }
            //endregion
            // </editor-fold>
            //endregion


            //region numbering tambahan jasmanto
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
            $ccn->setMainGate($sessionData[$cCode]['main']);
            $ccn->setItemsGate($sessionData[$cCode]['items']);
            $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
            $new_counter = $ccn->getCounterNumber();
            cekHitam("jenistr yang disett dari create " . $this->jenisTr);
            echo "___counter";
            arrPrintHijau($new_counter);


            $costum_counter = array(
                "_dtime",
                "_company",
                "_company_stepCode"
            );

            foreach ($costum_counter as $item) {
                $counter_nilai = $new_counter['main'][$item];

                $var = $counter_nilai;
                if ($hasil == "") {
                    $hasil .= "$var";
                }
                else {
                    $hasil = "$hasil" . "-" . "$var";
                }

            }
            // cekBiru("hasil $hasil");
            // matiHere(__LINE__);
            if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                foreach ($new_counter['main'] as $ckey => $cval) {
                    $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                    $sessionData[$cCode]['main'][$ckey] = $cval;
                }
            }
            if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                foreach ($new_counter['items'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion


            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (isset($sessionData[$cCode]['tableIn_master']) && sizeof($sessionData[$cCode]['tableIn_master']) > 0) {

                $sessionData[$cCode]['tableIn_master']['status_4'] = 11;
                $sessionData[$cCode]['tableIn_master']['trash_4'] = 0;
                $sessionData[$cCode]['tableIn_master']['cli'] = 1;
                $sessionData[$cCode]['tableIn_master']['dtime'] = $transaksi_current_dtime;
                $sessionData[$cCode]['tableIn_master']['fulldate'] = $transaksi_current_fulldate;


                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $transaksi_current_cabang_id . "'");
                $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                cekHitam($this->db->last_query());
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData[$cCode]['tableIn_master']);
                $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                $sessionData[$cCode]['main']['nomer'] = $insertNum;
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
                    $sessionData[$cCode]['main'][$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        if (isset($sessionData[$cCode][$target])) {
                            foreach ($sessionData[$cCode][$target] as $xid => $iSpec) {
                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                if (isset($sessionData[$cCode][$target][$id])) {
                                    $sessionData[$cCode][$target][$id][$key] = $val;
                                }
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $sessionData[$cCode]['main']['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUiJenis['steps'][1]['label'],
                    "group_code" => $this->configUiJenis['steps'][1]['userGroup'],
                    "oleh_id" => "-100",
                    "oleh_nama" => "system",
                    "keterangan" => $this->configUiJenis['steps'][1]['label'] . " oleh system",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");
                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $sessionData[$cCode]['main']['olehID'],
                        "olehName" => $sessionData[$cCode]['main']['olehName'],
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
                    "nomer_top" => $sessionData[$cCode]['main']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,

                )) or die("Failed to update tr next-state!");
                cekHijau($this->db->last_query());

                arrPrintWebs($sessionData[$cCode]['tableIn_master']);

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $sessionData[$cCode]['main']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $sessionData[$cCode]['tableIn_master'][$key] = $val;
                }

            }
            if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                $inserMainValues = array();
                if (isset($this->configCoreJenis['tableIn']['mainValues'])) {
                    //matiHEre("hooppp");
                    $inserMainValues = array();
                    foreach ($this->configCoreJenis['tableIn']['mainValues'] as $key => $src) {
                        if (isset($sessionData[$cCode]['tableIn_master_values'][$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $sessionData[$cCode]['tableIn_master_values'][$key],
                            ));

                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }
                    }
                }

                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }

            }
            if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                $inserMainValues = array();
                foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }

                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    //                    cekkuning("making a clone for input key $key / $val");
                    //                    $tmpTableIn=$sessionData[$cCode]['tableIn_master'];
                    //                    $replacers=array(
                    //                        "nomer"=>$sessionData[$cCode]['tableIn_master']['nomer']."_$key",
                    //                    );
                    //                    foreach($replacers as $key=>$val){
                    //                        $tmpTableIn[$key]=$val;
                    //                    }
                    //                    $subInputInsertID = $tr->writeMainEntries($tmpTableIn);
                }
            }
            if (isset($sessionData[$cCode]['main_add_fields']) && sizeof($sessionData[$cCode]['main_add_fields']) > 0) {
                foreach ($sessionData[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($sessionData[$cCode]['main_applets']) && sizeof($sessionData[$cCode]['main_applets']) > 0) {
                foreach ($sessionData[$cCode]['main_applets'] as $amdl => $aSpec) {
                    $tr->writeMainApplets($insertID, array(
                        "mdl_name" => $amdl,
                        "key" => $aSpec['key'],
                        "label" => $aSpec['labelValue'],
                        "description" => $aSpec['description'],
                    ));
                }
            }
            if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
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
            if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {

                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail'] as $dSpec) {
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
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
            }
            if (isset($sessionData[$cCode]['tableIn_detail2']) && sizeof($sessionData[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
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
            if (isset($sessionData[$cCode]['tableIn_detail_rsltItems']) && sizeof($sessionData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
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
            if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {

                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->configCoreJenis['tableIn']['detailValues'])) {
                        foreach ($this->configCoreJenis['tableIn']['detailValues'] as $key => $src) {
                            if (isset($sessionData[$cCode]['tableIn_detail'][$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
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
                    $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                }

            }
            if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->configCoreJenis['tableIn']['detailValues2_sum'])) {
                        $insertIDs = array();
                        foreach ($this->configCoreJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
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
            $steps = $this->configUiJenis['steps'];

            //region processing sub-components, if in single step geser ke CLI

            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            //            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['jurnal']['detail']) ? $sessionData[$cCode]['revert']['jurnal']['detail'] : array();
                $revertedTarget = $sessionData[$cCode]['main']['pihakExternID'];
            }
            else {
                $iterator = isset($this->configCoreJenis['components'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['components'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
            }
            $componentConfig['detail'] = $iterator;
            //            //region processing sub-components
            //            if (sizeof($iterator) > 0) {
            //                foreach ($iterator as $cCtr => $tComSpec) {
            //                    $tmpOutParams[$cCtr] = array();
            //                    $gg = 0;
            //                    $srcGateName = $tComSpec['srcGateName'];
            //                    foreach ($sessionData[$cCode][$srcGateName] as $id => $dSpec) {
            //                        $srcRawGateName = $tComSpec['srcRawGateName'];
            //                        $comName = $tComSpec['comName'];
            //                        if (substr($comName, 0, 1) == "{") {
            //                            $comName = trim($comName, "{");
            //                            $comName = trim($comName, "}");
            //                            //                            $comName = str_replace($comName, $sessionData[$cCode]['main'][$comName], $comName);
            //                            cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
            //                            $comName = str_replace($comName, $sessionData[$cCode][$srcGateName][$id][$comName], $comName);
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
            //                                    //                                    $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
            //                                    $key = str_replace($key, $sessionData[$cCode][$srcGateName][$id][$key], $key);
            //                                }
            //
            //                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
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
            //                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
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
            //                            $subParams['static']["fulldate"] = $transaksi_current_fulldate;
            //                            $subParams['static']["dtime"] = $transaksi_current_dtime;
            //                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
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
            //                    foreach ($sessionData[$cCode][$srcGateName] as $id => $dSpec) {
            //
            //                        $srcRawGateName = $tComSpec['srcRawGateName'];
            //                        $comName = $tComSpec['comName'];
            //                        if (substr($comName, 0, 1) == "{") {
            //                            $comName = trim($comName, "{");
            //                            $comName = trim($comName, "}");
            //                            $comName = str_replace($comName, $sessionData[$cCode][$srcGateName][$id][$comName], $comName);
            //                            //                        $comName = str_replace($comName, $sessionData[$cCode]['main'][$comName], $comName);
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

            //endregion

            //region processing main components, if in single step
            $componentJurnal = array();
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['jurnal']['master']) ? $sessionData[$cCode]['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($this->configCoreJenis['components'][$jenisTrTarget]['master']) ? $this->configCoreJenis['components'][$jenisTrTarget]['master'] : array();
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
                        $comName = str_replace($comName, $sessionData[$cCode]['main'][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component # $cCtr: $comName<br>";

                    $dSpec = $sessionData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
                            }

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                            //                            cekKuning("LOOP $key diisi dengan $realValue");
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = $transaksi_current_fulldate;
                        $tmpOutParams['static']["dtime"] = $transaksi_current_dtime;
                        $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";


                    }

                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cCtr], $sessionData[$cCode][$srcGateName][$cCtr], 0);
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
                        $tmpOutParams['static2']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


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
                    if ($comName == "Jurnal") {
                        $componentJurnal[] = $tmpOutParams;
                    }
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion


            cekHitam(":: START POST PROCC DETAIL... ::");

            //region processing sub-post-processors, always
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['postProc']['detail']) ? $sessionData[$cCode]['revert']['postProc']['detail'] : array();
                cekHitam("post procc pakai revert");
            }
            else {
                $iterator = isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
                cekHitam("post procc pakai config core");
            }
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();
                    if (isset($sessionData[$cCode][$srcGateName]) && (sizeof($sessionData[$cCode][$srcGateName]) > 0)) {
                        arrPrint($sessionData[$cCode][$srcGateName]);
                        foreach ($sessionData[$cCode][$srcGateName] as $xid => $dSpec) {
                            //                            $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    cekHitam("gate: $srcGateName, dengan key $id");
                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                $subParams['static']["fulldate"] = $transaksi_current_fulldate;
                                $subParams['static']["dtime"] = $transaksi_current_dtime;
                                if (isset($sessionData[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $sessionData[$cCode]['main']['pihakExternID'];
                                }

                                $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
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
                    if (isset($sessionData[$cCode][$srcGateName])) {
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
            //            arrPrint($sessionData[$cCode]["revert"]);
            if (isset($sessionData[$cCode]["revert"]["connectedPaymentsource"]) && $sessionData[$cCode]["revert"]["connectedPaymentsource"] == true) {
                $keyRel = $sessionData[$cCode]["main"]["referenceID"];
                $keyRelRef = $sessionData[$cCode]["main"]["pihakExternID"];
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
                                $tmp["static"]["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";

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
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['postProc']['detail']) ? $sessionData[$cCode]['revert']['postProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]['master']) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>LINE: " . __LINE__;

                    $dSpec = $sessionData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static']["fulldate"] = $transaksi_current_fulldate;
                        $tmpOutParams['static']["dtime"] = $transaksi_current_dtime;
                        $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cCtr], $sessionData[$cCode][$srcGateName][$cCtr], 0);
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
                        $tmpOutParams['static2']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


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

            //cekHijau(":: HALLOOO ::");

            //region updater main transaksi rejection jurnal next step exist
            $mongUpdateList = array();
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {

                $tr->setFilters(array());
                $tr->addFilter("id='" . $sessionData[$cCode]["main"]["referenceID"] . "'");
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
                        "id" => $sessionData[$cCode]["main"]["referenceID"],
                    ), $udpate) or die("Failed to update tr next-state!");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $sessionData[$cCode]["main"]["referenceID"]),
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
                    $rslt = $td->lookupJoinedByID($sessionData[$cCode]["main"]["referenceID"])->result();
                    if (sizeof($rslt) > 0) {
                        foreach ($rslt as $rsltSpec) {
                            if (array_key_exists($rsltSpec->produk_id, $sessionData[$cCode]["items"])) {
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
                                    "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ), $arrData_detail) or die("Failed to update tr next-state!");
                                $mongUpdateList['update']['detail'][] = array(
                                    "where" => array(
                                        "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
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
                        "oleh_id" => SYS_ID,
                        "oleh_nama" => SYS_NAMA,
                        "keterangan" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'] . " oleh ",
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
                        "id" => $sessionData[$cCode]["main"]["referenceID"],
                    ), $udpate) or die("Failed to update tr next-state!");
                    cekHijau("UPDATE transaksi step sebelumnya...");
                    cekHijau($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $sessionData[$cCode]["main"]["referenceID"]),
                        "value" => array(
                            "trash_4" => "1",
                        ),
                    );

                    //update validqty 0 supaya gak bisa difollowup
                    $arrData_detail["valid_qty"] = $new_valid_qty;
                    $td = new MdlTransaksi();
                    $td->setFilters(array());
                    $rslt = $td->lookupJoinedByID($sessionData[$cCode]["main"]["referenceID"])->result();
                    if (sizeof($rslt) > 0) {
                        foreach ($rslt as $rsltSpec) {
                            if (array_key_exists($rsltSpec->produk_id, $sessionData[$cCode]["items"])) {
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
                                    "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ), $arrData_detail) or die("Failed to update tr next-state!");
                                cekKuning("UPDATE transaksi data...");
                                cekKuning($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                                $mongUpdateList['update']['detail'][] = array(
                                    "where" => array(
                                        "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
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
                        "oleh_id" => SYS_ID,
                        "oleh_nama" => SYS_NAMA,
                        "keterangan" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'] . " oleh ",
                        //            "transaksi_id" => $no,
                    )) or die("Failed to write signature");
                    $mongoList['sign'][] = $dwsign;
                    cekKuning($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                    //                    matiHEre("uhuu");
                }
            }

            //endregion

            // region berlaku pembatalan transaksi bila ada config revertStep di model MdlRevertJurnal (true)
            if (isset($sessionData[$cCode]['main']['pihakExternRevertStep']) && ($sessionData[$cCode]['main']['pihakExternRevertStep'] == true)) {
                $referenceNextProp = (isset($sessionData[$cCode]['main']['referenceNextProp']) && (sizeof($sessionData[$cCode]['main']['referenceNextProp']) > 0)) ? $sessionData[$cCode]['main']['referenceNextProp'] : array();
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
                    if (isset($sessionData[$cCode][$referenceNextProp['detailGate']]) && ($sessionData[$cCode][$referenceNextProp['detailGate']] != NULL)) {

                        foreach ($sessionData[$cCode][$referenceNextProp['detailGate']] as $itemsSpec) {
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


            //
            //region nulis paymentSource
            $stepCode = $this->configUiJenis['steps'][1]['target'];
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {

                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs[1] as $paymentSrcConfig) {
                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
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
                                $currValue = isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0;
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
                                    "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $sessionData[$cCode]['main']['nomer'],
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                    "terbayar" => 0,
                                    "sisa" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                    "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                    "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                    "oleh_id" => SYS_ID,
                                    "oleh_nama" => SYS_NAMA,
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                    "valas_id" => (isset($externSrc['valasId']) && isset($sessionData[$cCode]['main'][$externSrc['valasId']])) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => (isset($externSrc['valasLabel']) && isset($sessionData[$cCode]['main'][$externSrc['valasLabel']])) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => (isset($externSrc['valasValue']) && isset($sessionData[$cCode]['main'][$externSrc['valasValue']])) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : 0,
                                    "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']])) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                    "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($sessionData[$cCode]['main'][$externSrc['valasTerbayar']])) ? $sessionData[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                    "sisa_valas" => (isset($externSrc['valasSisa']) && isset($sessionData[$cCode]['main'][$externSrc['valasSisa']])) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                    "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData[$cCode]['main'][$externSrc['extern_label2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_label2']] : "",
                                    "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
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

                            //-----------------------
                            cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
                            $this->load->helper("he_payment_source");
                            paymentSource($this->jenisTr, $componentJurnal, $sessionData[$cCode]['main'], $valueLabel, $valueSrc);
                            //-----------------------

                            $arrDataPym = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                "nomer" => $sessionData[$cCode]['main']['nomer'],
                                "label" => $paymentSrcConfig['label'],
                                "tagihan" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                "terbayar" => 0,
                                "sisa" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                "oleh_id" => SYS_ID,
                                "oleh_nama" => SYS_NAMA,
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "valas_id" => (isset($externSrc['valasId']) && isset($sessionData[$cCode]['main'][$externSrc['valasId']])) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => (isset($externSrc['valasLabel']) && isset($sessionData[$cCode]['main'][$externSrc['valasLabel']])) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => (isset($externSrc['valasValue']) && isset($sessionData[$cCode]['main'][$externSrc['valasValue']])) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : 0,
                                "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']])) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($sessionData[$cCode]['main'][$externSrc['valasTerbayar']])) ? $sessionData[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                "sisa_valas" => (isset($externSrc['valasSisa']) && isset($sessionData[$cCode]['main'][$externSrc['valasSisa']])) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData[$cCode]['main'][$externSrc['extern_label2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_label2']] : "",
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($sessionData[$cCode]['main'][$externSrc['payment_locked']])) ? $sessionData[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($sessionData[$cCode]['main'][$externSrc['cash_account']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($sessionData[$cCode]['main'][$externSrc['cash_account_nama']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($sessionData[$cCode]['main'][$externSrc['extern2_id']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_id']] : 0,
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($sessionData[$cCode]['main'][$externSrc['extern2_nama']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_nama']] : 0,
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


            //
            //region nulis paymentAntiSource
            $stepCode = $this->configUiJenis['steps'][1]['target'];
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
                            "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                            "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                            "nomer" => $sessionData[$cCode]['main']['nomer'],
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                            "terbayar" => 0,
                            "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                            "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                            "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                            "oleh_id" => SYS_ID,
                            "oleh_nama" => SYS_NAMA,
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                            "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                            "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                            "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                            "terbayar_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTerbayar']]) ? $sessionData[$cCode]['main'][$externSrc['valasTerbayar']] : '',
                            "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',

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
            if (isset($this->configCoreJenis['components'][$jenisTrTarget]) && sizeof($this->configCoreJenis['components'][$jenisTrTarget])) {
                $jurnalIndex = $this->configCoreJenis['components'][$jenisTrTarget];
            }
            else {
                if (isset($sessionData[$cCode]["revert"]["jurnal"]) && sizeof($sessionData[$cCode]["revert"]["jurnal"]) > 0) {
                    $jurnalIndex = $sessionData[$cCode]["revert"]["jurnal"];
                }
                else {
                    $jurnalIndex = array();
                }
            }
            //---------------------------------------------------
            if (isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]) && sizeof($this->configCoreJenis['postProcessor'][$jenisTrTarget])) {
                $jurnalPostProc = $this->configCoreJenis['postProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($sessionData[$cCode]["revert"]["postProc"]) && sizeof($sessionData[$cCode]["revert"]["postProc"]) > 0) {
                    $jurnalPostProc = $sessionData[$cCode]["revert"]["postProc"];
                }
                else {
                    $jurnalPostProc = array();
                }
            }
            //---------------------------------------------------
            if (isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]) && sizeof($this->configCoreJenis['preProcessor'][$jenisTrTarget])) {
                $jurnalPreProc = $this->configCoreJenis['preProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($sessionData[$cCode]["revert"]["preProc"]) && sizeof($sessionData[$cCode]["revert"]["preProc"]) > 0) {
                    $jurnalPreProc = $sessionData[$cCode]["revert"]["preProc"];
                }
                else {
                    $jurnalPreProc = array();
                }
            }
            //---------------------------------------------------


            $baseRegistries = array(
                'main' => isset($sessionData[$cCode]['main']) ? $sessionData[$cCode]['main'] : array(),
                'items' => isset($sessionData[$cCode]['items']) ? $sessionData[$cCode]['items'] : array(),
                'items2' => isset($sessionData[$cCode]['items2']) ? $sessionData[$cCode]['items2'] : array(),
                'items2_sum' => isset($sessionData[$cCode]['items2_sum']) ? $sessionData[$cCode]['items2_sum'] : array(),
                'itemSrc' => isset($sessionData[$cCode]['itemSrc']) ? $sessionData[$cCode]['itemSrc'] : array(),
                'itemSrc_sum' => isset($sessionData[$cCode]['itemSrc_sum']) ? $sessionData[$cCode]['itemSrc_sum'] : array(),
                'items3' => isset($sessionData[$cCode]['items3']) ? $sessionData[$cCode]['items3'] : array(),
                'items3_sum' => isset($sessionData[$cCode]['items3_sum']) ? $sessionData[$cCode]['items3_sum'] : array(),
                'items4' => isset($sessionData[$cCode]['items4']) ? $sessionData[$cCode]['items4'] : array(),
                'items4_sum' => isset($sessionData[$cCode]['items4_sum']) ? $sessionData[$cCode]['items4_sum'] : array(),
                'items5_sum' => isset($sessionData[$cCode]['items5_sum']) ? $sessionData[$cCode]['items5_sum'] : array(),
                'items6_sum' => isset($sessionData[$cCode]['items6_sum']) ? $sessionData[$cCode]['items6_sum'] : array(),
                'items7_sum' => isset($sessionData[$cCode]['items7_sum']) ? $sessionData[$cCode]['items7_sum'] : array(),
                'items8_sum' => isset($sessionData[$cCode]['items8_sum']) ? $sessionData[$cCode]['items8_sum'] : array(),
                'items9_sum' => isset($sessionData[$cCode]['items9_sum']) ? $sessionData[$cCode]['items9_sum'] : array(),
                'items10_sum' => isset($sessionData[$cCode]['items10_sum']) ? $sessionData[$cCode]['items10_sum'] : array(),
                'rsltItems' => isset($sessionData[$cCode]['rsltItems']) ? $sessionData[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($sessionData[$cCode]['rsltItems2']) ? $sessionData[$cCode]['rsltItems2'] : array(),
                'rsltItems3' => isset($sessionData[$cCode]['rsltItems3']) ? $sessionData[$cCode]['rsltItems3'] : array(),
                'tableIn_master' => isset($sessionData[$cCode]['tableIn_master']) ? $sessionData[$cCode]['tableIn_master'] : array(),
                'tableIn_detail' => isset($sessionData[$cCode]['tableIn_detail']) ? $sessionData[$cCode]['tableIn_detail'] : array(),
                'tableIn_detail2_sum' => isset($sessionData[$cCode]['tableIn_detail2_sum']) ? $sessionData[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($sessionData[$cCode]['tableIn_master_values']) ? $sessionData[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($sessionData[$cCode]['tableIn_detail_values']) ? $sessionData[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($sessionData[$cCode]['tableIn_detail_values2_sum']) ? $sessionData[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($sessionData[$cCode]['main_add_values']) ? $sessionData[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($sessionData[$cCode]['main_add_fields']) ? $sessionData[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($sessionData[$cCode]['main_elements']) ? $sessionData[$cCode]['main_elements'] : array(),
                'main_inputs' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                "receiptDetailFields" => isset($this->configLayoutJenis['receiptDetailFields'][1]) ? $this->configLayoutJenis['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->configLayoutJenis['receiptSumFields'][1]) ? $this->configLayoutJenis['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->configLayoutJenis['receiptDetailFields2'][1]) ? $this->configLayoutJenis['receiptDetailFields2'][1] : array(),
                "receiptDetailSrcFields" => isset($this->configLayoutJenis['receiptDetailSrcFields'][1]) ? $this->configLayoutJenis['receiptDetailSrcFields'][1] : array(),
                "receiptSumFields2" => isset($this->configLayoutJenis['receiptSumFields2'][1]) ? $this->configLayoutJenis['receiptSumFields2'][1] : array(),
                "jurnal_index" => $jurnalIndex,
                "postProcessor" => $jurnalPostProc,
                "preProcessor" => $jurnalPreProc,
                "revert" => isset($sessionData[$cCode]['revert']) ? $sessionData[$cCode]['revert'] : array(),
                "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                "items_noapprove" => isset($sessionData[$cCode]['items_noapprove']) ? $sessionData[$cCode]['items_noapprove'] : array(),
                "jurnalItems" => isset($sessionData[$cCode]['jurnalItems']) ? $sessionData[$cCode]['jurnalItems'] : array(),
                "componentsBuilder" => isset($sessionData[$cCode]['componentsBuilder']) ? $sessionData[$cCode]['componentsBuilder'] : array(),
            );
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $mongRegID = $doWriteReg;
            cekHitam($this->db->last_query());

            //========extended steps (if any)
            //region extended steps
            if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                foreach ($sessionData[$cCode]['main_inputs'] as $iKey => $iVal) {
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
                                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => $sessionData[$cCode]['main']['nomer'],
                                                "label" => $paymentSrcConfig['label'],
                                                "tagihan" => $sessionData[$cCode]['main_inputs'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $sessionData[$cCode]['main_inputs'][$valueSrc],
                                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                                "oleh_id" => SYS_ID,
                                                "oleh_nama" => SYS_NAMA,
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                                "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                                "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                                "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                                                "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                                "terbayar_valas" => 0,
                                                "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
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
                                        "proposed_by" => SYS_ID,
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
                $lt->execLocker($sessionData[$cCode]['main'], $nextProp['num'], NULL, $insertID);
            }

            //==========================================================================================================

            $masterID = $insertID;

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                // region auto approve
                $autoMasterApprove = (isset($this->configUiJenis['autoApprove'][1]) && ($this->configUiJenis['autoApprove'][1] == true)) ? $this->configUiJenis['autoApprove'][1] : false;
                if ($autoMasterApprove == true) {

                    $autoApprove = false;
                    //            if (isset($sessionData[$cCode]["main"]["add_diskon"]) && ($sessionData[$cCode]["main"]["add_diskon"] > 0)) {
                    if (($sessionData[$cCode]["main"]["add_diskon"] > 0) || (($sessionData[$cCode]["main"]["disc"] > 0))) {
                        // ada diskon tambahan, maka approval manual step 2
                        //                    $autoApprove = true;
                    }
                    else {
                        $autoApprove = true;
                    }
                    if ($autoApprove == true) {
                        // tidak ada diskon tambahan, maka langsung auto approval step 2
                        cekHitam(":: AUTO APPROVAL STEP 2 ::");
                        $transaksiID_reference = $no = $masterID;
                        $stepNum = 2;
                        $stepNumCurrent = 1;
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
                            //                    arrPrintKuning($tmpTr);
                            //                    mati_disini(__LINE__);
                            $this->jenisTr = $tmpTr[0]->jenis_master;
                            $cCode = "_TR_" . $this->jenisTr;
                            //region session init
                            //                    if (!isset($sessionData[$cCode])) {
                            //                        $sessionData[$cCode] = array(
                            //                            "items" => array(),
                            //                            "main"  => array(),
                            //                        );
                            //                    }
                            //                    if (!isset($sessionData[$cCode]['main'])) {
                            //                        $sessionData[$cCode]['main'] = array();
                            //                    }
                            //                    if (!isset($sessionData[$cCode]['items'])) {
                            //                        $sessionData[$cCode]['items'] = array();
                            //                    }
                            //                    $sessionData[$cCode]['rsltItems'] = array();
                            //                    $sessionData[$cCode]['rsltItems2'] = array();
                            //endregion
                            $sessionData[$cCode]['extractedItems'] = $extractedItems;

                            $jenisTrTarget = isset($this->configUiJenis["steps"][$stepNum]["target"]) ? $this->configUiJenis["steps"][$stepNum]["target"] : NULL;
                            $detailValuesConfig = isset($this->configCoreJenis['tableIn']['detailValues']) ? $this->configCoreJenis['tableIn']['detailValues'] : array();
                            $additionalData = isset($this->configUiJenis["addDetailData"][$stepNum]) ? $this->configUiJenis["addDetailData"][$stepNum] : array();

                            //                    $masterID = $sessionData[$cCode]['main']['masterID'];
                            $topID = $tmpTr[0]->id_top;
                            $tmpNomorNota = $tmpTr[0]->nomer;
                            $origJenis = $tmpTr[0]->jenis_master;
                            $trID = $tmpTr[0]->transaksi_id;

                            $totalSteps = sizeof($this->configUiJenis['steps']);

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
                                    if (isset($sessionData[$cCode]['items'][$row->produk_id])) {
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
//                                        echo "<script>top.writeProgress('$key akan ambil nilai dari $src');</script>";
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
//                                        echo "<script>top.writeProgress('dan sekarang nilainya: " . $items[$row->produk_id][$key] . "');</script>";
                                    }
                                }
                            }

                            //region pembulatan replacer disini
                            $injectBulat = isset($this->configCoreJenis['valuePembulatan'][$stepNum]) ? $this->configCoreJenis['valuePembulatan'][$stepNum] : array();
                            if (sizeof($injectBulat) > 0) {
//                                echo "<script>top.writeProgress('PEMBULATAN', 'HEAD');</script>";
                                //            arrPrint($injectBulat);
                                $selectedSource = $injectBulat['source'];
                                $injectSource = makeDppBulat($sessionData[$cCode]['main'][$selectedSource]);
                                foreach ($injectBulat['replacer'] as $k => $fields) {
                                    $sessionData[$cCode]['main'][$fields] = $injectSource[$k];
//                                    echo "<script>top.writeProgress('PEMBULATAN ($fields)');</script>";
                                }

                            }
                            //endregion

                            cekMerah(":: MEMULAI PRE-PROCC ITEMS...");
                            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin");

                            //region pre-processors (item)
                            if (isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail'])) {
                                $iterator = isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
                                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields'][$stepNum]) ? $this->configUiJenis['shoppingCartNumFields'][$stepNum] : array();
                                echo "ITEM NUM LABELS";

                                if (sizeof($iterator) > 0) {
//                                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                                    foreach ($iterator as $cCtr => $tComSpec) {
                                        $comName = $tComSpec['comName'];
                                        $srcGateName = $tComSpec['srcGateName'];
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
                                        echo __LINE__ . " :: sub-preproc: $comName, initializing values <br>";

                                        foreach ($sessionData[$cCode][$srcGateName] as $xid => $dSpec) {
                                            $tmpOutParams[$cCtr] = array();
                                            //                        $id = $dSpec['id'];
                                            $id = $xid;
                                            $subParams = array();

                                            if (isset($tComSpec['static'])) {
                                                foreach ($tComSpec['static'] as $key => $value) {

                                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
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
                                                    $jenis = $sessionData[$cCode]['main']['jenis'];
                                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                    }
                                                }

                                                $subParams['static']["fulldate"] = date("Y-m-d");
                                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                                $subParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
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

                                                        if (!isset($sessionData[$cCode][$gateName])) {
                                                            $sessionData[$cCode][$gateName] = array();
                                                            //                                    cekhijau("building the session: $gateName");
                                                        }
                                                        else {
                                                            //                                    cekhijau("NOT building the session: $gateName");
                                                        }

                                                        foreach ($paramSpec as $id => $gSpec) {
                                                            //                                        $id = $gSpec['id'];
                                                            if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                                $sessionData[$cCode][$gateName][$id] = array();
                                                            }

                                                            if (isset($sessionData[$cCode][$gateName][$id])) {
                                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                    foreach ($gSpec as $key => $val) {
                                                                        $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                                    }
                                                                }
                                                            }
                                                            //==inject gotParams to child gate
                                                            if ($gateName == $srcGateName) {
                                                                if (isset($sessionData[$cCode][$srcGateName][$id])) {
                                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                        foreach ($gSpec as $key => $val) {
                                                                            $sessionData[$cCode][$srcGateName][$id][$key] = $val;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            //cekMerah("REBUILDING VALUES..");
                                                            if (sizeof($itemNumLabels) > 0) {
                                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                                foreach ($itemNumLabels as $key => $label) {
                                                                    //cekHere("$id === $key => $label");
                                                                    $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                                    //                                        die();
                                                                }
                                                            }
                                                        }
                                                        //                                    arrPrint($sessionData[$cCode][$gateName]);die();
                                                    }
                                                }

                                            }
                                            else {
                                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                            }
                                        }

                                        $this->load->helper("he_value_builder");
                                        fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);
                                    }
                                }
                                else {
                                    //cekKuning("sub-preproc is not set");
                                }


                                $this->load->helper("he_value_builder");
                                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);
                            }
                            else {
                                echo("no processor defined. skipping preprocessor..<br>");
                            }

                            //endregion

                            //region pre-processors (master)
                            if (isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['master'])) {
                                $iterator = isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['master']) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields']) ? $this->configUiJenis['shoppingCartNumFields'] : array();

                                echo "ITEM NUM LABELS";

                                if (sizeof($iterator) > 0) {
//                                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
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

                                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
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
                                                $jenis = $sessionData[$cCode]['main']['jenis'];
                                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }

                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
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
                                                            if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                                $sessionData[$cCode][$gateName][$id] = array();
                                                            }
                                                            if (isset($sessionData[$cCode][$gateName][$id])) {
                                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                                    foreach ($ggSpec as $key => $val) {
                                                                        $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                                    }
                                                                }
                                                            }
                                                            //cekMerah("REBUILDING VALUES..");
                                                            if (sizeof($itemNumLabels) > 0) {
                                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                                foreach ($itemNumLabels as $key => $label) {
                                                                    //cekHere("$id === $key => $label");
                                                                    if (isset($sessionData[$cCode][$gateName][$id][$key])) {
                                                                        $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else {

                                                        if (isset($sessionData[$cCode]['main'])) {
                                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                foreach ($gSpec as $key => $val) {
                                                                    cekbiru("injecting param $key with $val");
                                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                                }
                                                            }
                                                        }
                                                        //==inject gotParams to child gate
                                                        if (isset($sessionData[$cCode]['main'])) {
                                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                foreach ($gSpec as $key => $val) {
                                                                    $sessionData[$cCode]['main'][$key] = $val;
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
                                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);


                            }
                            else {
                                echo("no processor defined. skipping preprocessor..<br>");
                            }

                            //endregion

                            //region pre-proc value injector items2 items2_sum dari gerbang main
                            $injectValues = isset($this->configCoreJenis['preInjectValue'][$stepNum]) ? $this->configCoreJenis['preInjectValue'][$stepNum] : array();
                            if (sizeof($injectValues) > 0) {
                                $iterator = isset($this->configCoreJenis['preInjectValue'][$stepNum]['master']) ? $this->configCoreJenis['preInjectValue'][$stepNum]['master'] : array();
                                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields']) ? $this->configUiJenis['shoppingCartNumFields'] : array();
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

                                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
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
                                                $jenis = $sessionData[$cCode]['main']['jenis'];
                                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }

                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
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
                                                            $sessionData[$cCode]['main'][$key] = $val;
                                                        }
                                                    }
                                                    if ($gateName == "items2") {
                                                        foreach ($sessionData[$cCode]['items2'] as $k => $tmpSes) {
                                                            foreach ($gSpec as $key => $val) {
                                                                foreach ($tmpSes as $y => $sesData) {
                                                                    if (array_key_exists($key, $sesData)) {
                                                                        $sessionData[$cCode]['items2'][$k][$y][$key] = $val;
                                                                    }
                                                                }
                                                            }
                                                        }

                                                    }
                                                    if ($gateName == "items2_sum") {
                                                        foreach ($sessionData[$cCode]['items2_sum'] as $k => $tmpSes) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData[$cCode]['items2_sum'][$k][$key] = $val;
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
                                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);

                            }
                            //endregion

                            $this->load->library("Validator");
                            $va = new Validator();
                            $va->setConfigUiJenis($this->configUiJenis);
                            $va->setCCode($this->cCode);
                            $va->midValidate($stepNum);
                            $va->unionValidate();

                            //region update step2an
                            if (isset($this->configUiJenis['steps'][$nextStepNum])) {//===masih ada langkah selanjutnya
                                echo "authorizing to next step..<br>";
                                $nextProp = array(
                                    "num" => $nextStepNum,
                                    "code" => $this->configUiJenis['steps'][$nextStepNum]['target'],
                                    "label" => $this->configUiJenis['steps'][$nextStepNum]['label'],
                                    "groupID" => $this->configUiJenis['steps'][$nextStepNum]['userGroup'],
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
                            //arrPrintHijau($nextProp);
                            //mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");
                            //==tulis signature
                            $dwsign = $tr->writeSignature($masterID, array(
                                "nomer" => $tmpNomorNota,
                                "step_number" => $stepNum,
                                "step_code" => $this->configUi[$origJenis]['steps'][$stepNum]['target'],
                                "step_name" => $this->configUi[$origJenis]['steps'][$stepNum]['label'],
                                "group_code" => $this->configUi[$origJenis]['steps'][$stepNum]['userGroup'],
                                "oleh_id" => SYS_ID,
                                "oleh_nama" => SYS_NAMA,
                                "keterangan" => $this->configUi[$origJenis]['steps'][$stepNum]['label'] . " oleh ",
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

                                "partial" => isset($sessionData[$cCode]['main']['partial']) ? $sessionData[$cCode]['main']['partial'] : 0,

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
                                "partial" => isset($sessionData[$cCode]['main']['partial']) ? $sessionData[$cCode]['main']['partial'] : 0,
                            )) or die("Failed to update tr next-state!");
                            $mongUpdateList['update']['main'][] = array(
                                "where" => array("id" => "$trID"),
                                "value" => array(
                                    "partial" => isset($sessionData[$cCode]['main']['partial']) ? $sessionData[$cCode]['main']['partial'] : 0,
                                ),
                            );


                            //mati_disini("==== ==== ====");
                            //endregion

                            $tCode = $this->configUi[$origJenis]['steps'][$stepNum]['target'];
                            $tCodeName = $this->configUi[$origJenis]['steps'][$stepNum]['label'];
                            $masterReplacers = array(
                                //            "referensi_id" => $masterID, (dimatikan)
                                //            "id_master"       => $masterID,
                                //            "id_top"          => $topID,
                                "inv" => $tmpNomorNota,
                                //            "jenis_top"           => $tCode,
                                "jenis" => $tCode,
                                "jenis_label" => $tCodeName,
                                "transaksi_jenis" => $tCode,
                                "cabang_id" => selectedTransactionSession() ? $sessionData[$cCode]['main']['cabangID'] : $transaksi_current_cabang_id,
                                "cabang_nama" => selectedTransactionSession() ? $sessionData[$cCode]['main']['cabangName'] : $transaksi_current_cabang_nama,
                                "oleh_id" => SYS_ID,
                                "oleh_nama" => SYS_NAMA,
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
                                "nomer_top2" => isset($sessionData[$cCode]['main']['nomer_top2']) ? $sessionData[$cCode]['main']['nomer_top2'] : "",
                                "nomer_top" => $sessionData[$cCode]['tableIn_master']['nomer_top'],
                                "nomers_prev" => base64_encode(serialize($prevNos)),
                                "nomers_prev_intext" => print_r($prevNos, true),
                                //            "jenis_top"           => $this->jenisTr,
                                "jenises_prev" => base64_encode(serialize(array($prevProp['jenis']))),
                                "jenises_prev_intext" => print_r(array($prevProp['jenis']), true),
                                "tail_number" => $stepNum,
                                "tail_code" => $this->configUiJenis['steps'][$stepNum]['target'],
                            );

                            foreach ($masterReplacers as $key => $val) {
                                $sessionData[$cCode]['tableIn_master'][$key] = $val;
                            }

                            $childTableRepaclers = array(
                                "sub_step_number" => $stepNum,
                                "sub_step_current" => $stepNum,
                                "sub_step_avail" => sizeof($this->configUiJenis['steps']),
                                "next_substep_num" => $nextProp['num'],
                                "next_substep_code" => $nextProp['code'],
                                "next_substep_label" => $nextProp['label'],
                                "next_subgroup_code" => $nextProp['groupID'],
                            );
                            foreach ($sessionData[$cCode]['tableIn_detail'] as $id => $dSpec) {
                                //			$id = $dSpec['id'];
                                foreach ($childTableRepaclers as $key => $val) {
                                    $sessionData[$cCode]['tableIn_detail'][$id][$key] = $val;
                                }
                            }


                            $masterReplacersO = array(

                                "jenisTr" => $tCode,
                                "jenisTrName" => $tCodeName,
                                "olehID" => SYS_ID,
                                "olehName" => SYS_NAMA,
                                "stepNumber" => $stepNum,
                                "stepCode" => $tCode,
                            );
                            foreach ($masterReplacersO as $key => $val) {
                                $sessionData[$cCode]['main'][$key] = $val;
                            }

                            //region menimbulkan nilai tagihan
                            $unpaidList = null != $this->config->item('tr_unpaidList') ? $this->config->item('tr_unpaidList') : array();
                            //        arrprint($sessionData[$cCode]['tableIn_master']);
                            if (in_array($tCode, $unpaidList)) {
                                $sessionData[$cCode]['tableIn_master']["transaksi_nilai_tagihan"] = $sessionData[$cCode]['tableIn_master']['transaksi_nilai'];
                                $sessionData[$cCode]['tableIn_master']["transaksi_nilai_terbayar"] = 0;
                                $sessionData[$cCode]['tableIn_master']["transaksi_nilai_sisa"] = ($sessionData[$cCode]['tableIn_master']['transaksi_nilai_tagihan'] - $sessionData[$cCode]['tableIn_master']['transaksi_nilai_terbayar']);
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

                            $counterForNumber = array($this->configCore[$origJenis]['formatNota']);
                            if (!in_array($counterForNumber[0], $this->configCore[$origJenis]['counters'])) {
                                die(__LINE__ . " Used number should be registered in 'counters' config as well");
                            }

                            foreach ($counterForNumber as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                                }
                                $cRawValues = implode("|", $cValues[$i]);
                                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                            }
                            $tmpNomorNota2_current = $tmpNomorNota2 = $paramSpec['paramString'];
                            $tmpNomorNota2Alias_current = $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);

                            //endregion

                            //region dynamic counters #1
//                            echo "<script>top.writeProgress('sedang membuat penomoran');</script>";
                            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                            $cn = new CustomCounter("transaksi");
                            $cn->setType("transaksi");
                            $configCustomParams = $this->configCore[$origJenis]['counters'];
                            $configCustomParams[] = "stepCode";
                            if (sizeof($configCustomParams) > 0) {
                                $cContent = array();
                                foreach ($configCustomParams as $i => $cRawParams) {
                                    $cParams = explode("|", $cRawParams);
                                    $cValues = array();
                                    foreach ($cParams as $param) {
                                        $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
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
                                $sessionData[$cCode]['tableIn_master'][$key] = $val;
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
                                $sessionData[$cCode]['tableIn_master'][$key] = $val;
                            }

                            // </editor-fold>
                            //endregion

                            //region numbering tambahan
                            $this->load->library("CounterNumber");
                            $ccn = new CounterNumber();
                            $ccn->setCCode($this->cCode);
                            $ccn->setJenisTr($this->jenisTr);
                            $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
                            $ccn->setMainGate($sessionData[$cCode]['main']);
                            $ccn->setItemsGate($sessionData[$cCode]['items']);
                            $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
                            $new_counter = $ccn->getCounterNumber();
                            cekHitam("jenistr yang disett dari create " . $this->jenisTr);

                            if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                                foreach ($new_counter['main'] as $ckey => $cval) {
                                    $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                                    $sessionData[$cCode]['main'][$ckey] = $cval;
                                }
                            }
                            if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                                foreach ($new_counter['items'] as $ikey => $iSpec) {
                                    foreach ($iSpec as $iikey => $iival) {
                                        $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                                    }
                                }
                            }
                            if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                                foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                                    foreach ($iSpec as $iikey => $iival) {
                                        $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                                    }
                                }
                            }
                            //endregion
                            //==tulis kloningan transaksi

                            //region write entries
                            if (sizeof($sessionData[$cCode]['tableIn_master']) > 0) {

                                // region locker transaksi---------------------------------
                                $pakai_ini = 0;
                                if ($pakai_ini == 1) {
                                    if ($this->session->login['ghost'] == 0) {
                                        //                $followUpValidator = isset($this->configUi[$origJenis]['followUpValidator'][$stepNum]) ? $this->configUi[$origJenis]['followUpValidator'][$stepNum] : false;
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

                                $sessionData[$cCode]['tableIn_master']['status_4'] = 11;
                                $sessionData[$cCode]['tableIn_master']['trash_4'] = 0;
                                $sessionData[$cCode]['main']['status_4'] = 1;
                                $sessionData[$cCode]['main']['trash_4'] = 0;


                                $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                                $midmaster = $insertID;
                                cekBiru("master invoice " . $insertID);
                                $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $sessionData[$cCode]['tableIn_master']);
                                $mongoList['main'] = array($insertID, $epID);
                                $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                                $mNumMaster = $insertNum;
                                $mJenisMaster = $sessionData[$cCode]['tableIn_master']['jenis'];
                                $sessionData[$cCode]['main']['nomer'] = $insertNum;
                                if ($insertID < 1) {
                                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                                }


                                if (isset($sessionData[$cCode]['tableIn_master']['ids_his'])) {
                                    $idHis_decode = blobDecode($sessionData[$cCode]['tableIn_master']['ids_his']);
                                    $idHis_decode[$stepNum] = array(
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                        "olehID" => $sessionData[$cCode]['main']['olehID'],
                                        "olehName" => $sessionData[$cCode]['main']['olehName'],
                                        "step" => $stepNum,
                                        "trID" => $insertID,
                                        "nomer" => $tmpNomorNota2,
                                        "nomer2" => $tmpNomorNota2Alias,
                                        "counters" => $appliedCounters2,
                                        "counters_intext" => $appliedCounters_inText2,
                                    );
                                    $idHis_blob = blobEncode($idHis_decode);
                                    $idHis_intext = print_r($idHis_decode, true);

                                    $sessionData[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                                    $sessionData[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;


                                    $tr = new MdlTransaksi();
                                    $dup = $tr->updateData(array("id" => $insertID), array(
                                        "ids_his" => $idHis_blob,
                                        "ids_his_intext" => $idHis_intext,

                                    )) or die("Failed to update tr next-state!");
                                    cekUngu($this->db->last_query());
                                }


                                cekUngu(":: insertID => $insertID ::");
                                if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                                    $inserMainValues = array();
                                    $mongoList['mainValues'] = array();
                                    foreach ($sessionData[$cCode]['tableIn_master_values'] as $key => $val) {
                                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                        $inserMainValues[] = $dd;
                                        $mongoList['mainValues'][] = $dd;
                                    }
                                    if (sizeof($inserMainValues) > 0) {
                                        $arrBlob = blobEncode($inserMainValues);
                                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                    }
                                }
                                if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                                    foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                        $mongoList['mainValues'][] = $dd;
                                    }
                                }
                                if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                                    foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                        $mongoList['mainValues'][] = $dd;
                                    }
                                }
                                if (isset($sessionData[$cCode]['main_add_fields']) && sizeof($sessionData[$cCode]['main_add_fields']) > 0) {
                                    foreach ($sessionData[$cCode]['main_add_fields'] as $key => $val) {
                                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                                    }
                                }


                                if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                                    //                cekMerah("ada mainElements $cCode");
                                    //                arrprint($sessionData[$cCode]['main_elements']);die();
                                    foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
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

                                if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                                    $insertIDs = array();
                                    foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                                        if (isset($this->configCoreJenis['tableIn']['detailValues'])) {
                                            foreach ($this->configCoreJenis['tableIn']['detailValues'] as $key => $src) {
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
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
                                if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                                    foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                                        if (isset($this->configCoreJenis['tableIn']['detailValues2_sum'])) {
                                            foreach ($this->configCoreJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
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
                                if (isset($sessionData[$cCode]['tableIn_detail_rsltItems']) && sizeof($sessionData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                                    foreach ($sessionData[$cCode]['tableIn_detail_rsltItems'] as $pID => $dSpec) {
                                        if (isset($this->configCoreJenis['tableIn']['detail_rsltItems'])) {
                                            foreach ($this->configCoreJenis['tableIn']['detail_rsltItems'] as $key => $src) {
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail_rsltItems'][$pID]['produk_jenis'],
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
//                                echo "<script>top.writeProgress('EXTRACT ITEMS...','head');</script>";
                                $seluruhnya = true;
                                $prevTrID = 0;
                                $arrvalidQtySisa = array();
                                if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                                    $closedRequest = isset($this->configCore[$origJenis]['closedRequest'][$stepNum]['enabled']) ? $this->configCore[$origJenis]['closedRequest'][$stepNum]['enabled'] : false;
                                    $insertIDs = array();
                                    $insertDeIDs = array();
                                    foreach ($sessionData[$cCode]['tableIn_detail'] as $iID => $dSpec) {
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
//                                        echo "<script>top.writeProgress('" . strtoupper($dSpec['produk_nama']) . "');</script>";


                                        if (isset($sessionData[$cCode]['extractedItems'])) {
                                            if (array_key_exists($iID, $sessionData[$cCode]['extractedItems'])) {
                                                $itemFulfilledJml = 0;
                                                foreach ($sessionData[$cCode]['extractedItems'][$iID] as $triID => $triSpec) {
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
                                                            "sub_tail_code" => $this->configUiJenis['steps'][$stepNum]['target'],
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
                                        if (isset($sessionData[$cCode]['extractedItems'])) {
                                            foreach ($sessionData[$cCode]['extractedItems'] as $iIDex => $exSpec) {
                                                if (!array_key_exists($iIDex, $sessionData[$cCode]['tableIn_detail'])) {
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
                                                            "sub_tail_code" => $this->configUiJenis['steps'][$stepNum]['target'],
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
                                    $lastStepPartialApprove = isset($this->configUiJenis['lastStepPartialApprove']) ? $this->configUiJenis['lastStepPartialApprove'] : false;
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
                                        "tail_code" => $this->configUiJenis['steps'][$stepNum]['target'],
                                        "status_4" => $sessionData[$cCode]['main']['status_4'],
                                        "trash_4" => $sessionData[$cCode]['main']['trash_4'],
                                    )) or die("Failed to update tr next-state!");
                                    cekHijau(":: UOPDATE transaksi dengan trID -> $prevTrID");
                                    $mongUpdateList['update']['main'][] = array(
                                        "where" => array(
                                            "id" => "$prevTrID",
                                        ),
                                        "value" => array(
                                            "tail_number" => $stepNum,
                                            "tail_code" => $this->configUiJenis['steps'][$stepNum]['target'],
                                            "status_4" => $sessionData[$cCode]['main']['status_4'],
                                            "trash_4" => $sessionData[$cCode]['main']['trash_4'],
                                        ),
                                    );
                                    cekHijau($this->db->last_query());
                                }
                                //endregion

                                //region cloner items to item_child
                                if (sizeof($additionalData) > 0) {
//                                    echo "<script>top.writeProgress('CLONING ITEMS TO ITEM CHILD...','head');</script>";
                                    cekHitam("ini data");
                                    $dataMdl = $additionalData["mdlName"];
                                    $this->load->model("Mdls/" . $dataMdl);
                                    $da = new $dataMdl();
                                    $arrColl = $da->getFields();
                                    $selectedCol = array();
                                    foreach ($arrColl as $colSpec) {
                                        $selectedCol[] = $colSpec['kolom'];
                                    }

                                    if (isset($sessionData[$cCode]['items_child']) && sizeof($sessionData[$cCode]['items_child'])) {
                                        $gateData = isset($this->configUiJenis['shopingCartDetailFields'][$stepNum]['gate']) ? $this->configUiJenis['shopingCartDetailFields'][$stepNum]['gate'] : "detail";

                                        $arrBlacklist = array(
                                            "jml", "max_jml", "qty",
                                        );
                                        if (isset($sessionData[$cCode]["items2_sum"])) {
                                            unset($sessionData[$cCode]["items2_sum"]);
                                            unset($sessionData[$cCode]["items2"]);
                                            unset($sessionData[$cCode]["tableIn_detail_values2_sum"]);
                                        }
                                        foreach ($sessionData[$cCode]['items_child'] as $mainProdsID => $defData) {
                                            if ($gateData == "detail") {
                                                $itemsMain = isset($sessionData[$cCode]['items'][$mainProdsID]) ? $sessionData[$cCode]['items'][$mainProdsID] : array();
                                            }
                                            else {
                                                $forceMainToItems = isset($this->configUiJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData]) ? $this->configUiJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData] : array();
                                                if (sizeof($forceMainToItems) > 0) {
                                                    foreach ($forceMainToItems as $key1 => $key2) {
                                                        $keyForce = strlen($key2) > 2 ? $key2 : $key1;
                                                        $itemsMain[$key1] = isset($sessionData[$cCode]['main'][$keyForce]) ? $sessionData[$cCode]['main'][$keyForce] : "";
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
//                                                    echo "<script>top.writeProgress('PENGAJUAN DATA (TRID:$insertDataID)');</script>";
                                                    $sessionData[$cCode]["items2_sum"][$insertDataID] = $paramDetil;
                                                    $sessionData[$cCode]["items2"][$mainProdsID][$insertDataID] = $paramDetil;
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

                                if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                                    $insertIDs = array();
                                    foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $iID => $dSpec) {
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
                                if (isset($sessionData[$cCode]['tableIn_detail2']) && sizeof($sessionData[$cCode]['tableIn_detail2']) > 0) {
                                    $insertIDs = array();
                                    foreach ($sessionData[$cCode]['tableIn_detail2'] as $iID => $dSpec) {
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


                                if (isset($this->configUiJenis['updateDueDate'][$stepNum])) {
                                    $dueDateConf = $this->configUiJenis['updateDueDate'][$stepNum];
                                    $sourceDue = $dueDateConf['source'];
                                    $targetDue = $dueDateConf['target'];
                                    $datenow = date("Y-m-d");
                                    foreach ($sourceDue as $key => $val) {
                                        $indexVal = isset($sessionData[$cCode]['main_elements'][$key][$val]) ? $sessionData[$cCode]['main_elements'][$key][$val] : 14;
                                        $dueDate = dueDate($datenow, $indexVal);
                                    }
                                    $fieldDue = $tr->getFields()["dueDate"];
                                    $dataDue = array();
                                    foreach ($fieldDue as $kol) {
                                        if (isset($sessionData[$cCode]['tableIn_master'][$kol])) {
                                            $dataDue[$kol] = $sessionData[$cCode]['tableIn_master'][$kol];
                                        }
                                    }
                                    $dataDue['due_date'] = $dueDate;
                                    $validateDue = validateDueDate($sessionData[$cCode]['main']['customerID'], $sessionData[$cCode]['main']['dtime']);

                                    arrPrint($validateDue);
                                    if ($validateDue['allow_create'] == "true") {
                                        if (isset($sessionData[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']) && $sessionData[$cCode]['main']['nilai_tambah_hutang_ke_konsumen'] > 0) {
                                            cekBiru($sessionData[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']);

                                            $tr->writeDueDate($insertID, $dataDue);
                                        }
                                    }
                                    else {
                                        $allowedOver = validateOverDue($sessionData[$cCode]['main']['customerID']);
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
                                        $sessionData[$cCode]['main_elements'][$keyTarget][$valTarget] = $dueDate;
                                        $sessionData[$cCode]['main']['dueDate'] = $dueDate;
                                    }
                                }
                                arrPrintPink($sessionData[$cCode]['tableIn_master']);

                                $baseRegistries = array(
                                    'main' => isset($sessionData[$cCode]['main']) ? $sessionData[$cCode]['main'] : array(),
                                    'items' => isset($sessionData[$cCode]['items']) ? $sessionData[$cCode]['items'] : array(),
                                    'items2' => isset($sessionData[$cCode]['items2']) ? $sessionData[$cCode]['items2'] : array(),
                                    'items2_sum' => isset($sessionData[$cCode]['items2_sum']) ? $sessionData[$cCode]['items2_sum'] : array(),
                                    'itemSrc' => isset($sessionData[$cCode]['itemSrc']) ? $sessionData[$cCode]['itemSrc'] : array(),
                                    'itemSrc_sum' => isset($sessionData[$cCode]['itemSrc_sum']) ? $sessionData[$cCode]['itemSrc_sum'] : array(),
                                    'items3' => isset($sessionData[$cCode]['items3']) ? $sessionData[$cCode]['items3'] : array(),
                                    'items3_sum' => isset($sessionData[$cCode]['items3_sum']) ? $sessionData[$cCode]['items3_sum'] : array(),
                                    'items4' => isset($sessionData[$cCode]['items4']) ? $sessionData[$cCode]['items4'] : array(),
                                    'items4_sum' => isset($sessionData[$cCode]['items4_sum']) ? $sessionData[$cCode]['items4_sum'] : array(),
                                    'items5_sum' => isset($sessionData[$cCode]['items5_sum']) ? $sessionData[$cCode]['items5_sum'] : array(),
                                    'items6_sum' => isset($sessionData[$cCode]['items6_sum']) ? $sessionData[$cCode]['items6_sum'] : array(),
                                    'items7_sum' => isset($sessionData[$cCode]['items7_sum']) ? $sessionData[$cCode]['items7_sum'] : array(),
                                    'items8_sum' => isset($sessionData[$cCode]['items8_sum']) ? $sessionData[$cCode]['items8_sum'] : array(),
                                    'items9_sum' => isset($sessionData[$cCode]['items9_sum']) ? $sessionData[$cCode]['items9_sum'] : array(),
                                    'items10_sum' => isset($sessionData[$cCode]['items10_sum']) ? $sessionData[$cCode]['items10_sum'] : array(),
                                    'items_noapprove' => isset($sessionData[$cCode]['items_noapprove']) ? $sessionData[$cCode]['items_noapprove'] : array(),

                                    'rsltItems' => isset($sessionData[$cCode]['rsltItems']) ? $sessionData[$cCode]['rsltItems'] : array(),
                                    'rsltItems2' => isset($sessionData[$cCode]['rsltItems2']) ? $sessionData[$cCode]['rsltItems2'] : array(),
                                    'rsltItems3' => isset($sessionData[$cCode]['rsltItems3']) ? $sessionData[$cCode]['rsltItems3'] : array(),

                                    'tableIn_master' => isset($sessionData[$cCode]['tableIn_master']) ? $sessionData[$cCode]['tableIn_master'] : array(),
                                    'tableIn_detail' => isset($sessionData[$cCode]['tableIn_detail']) ? $sessionData[$cCode]['tableIn_detail'] : array(),
                                    'tableIn_detail2_sum' => isset($sessionData[$cCode]['tableIn_detail2_sum']) ? $sessionData[$cCode]['tableIn_detail2_sum'] : array(),
                                    'tableIn_detail_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_rsltItems'] : array(),
                                    'tableIn_detail_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                                    'tableIn_master_values' => isset($sessionData[$cCode]['tableIn_master_values']) ? $sessionData[$cCode]['tableIn_master_values'] : array(),
                                    'tableIn_detail_values' => isset($sessionData[$cCode]['tableIn_detail_values']) ? $sessionData[$cCode]['tableIn_detail_values'] : array(),
                                    'tableIn_detail_values_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                                    'tableIn_detail_values_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                                    'tableIn_detail_values2_sum' => isset($sessionData[$cCode]['tableIn_detail_values2_sum']) ? $sessionData[$cCode]['tableIn_detail_values2_sum'] : array(),
                                    'main_add_values' => isset($sessionData[$cCode]['main_add_values']) ? $sessionData[$cCode]['main_add_values'] : array(),
                                    'main_add_fields' => isset($sessionData[$cCode]['main_add_fields']) ? $sessionData[$cCode]['main_add_fields'] : array(),
                                    'main_elements' => isset($sessionData[$cCode]['main_elements']) ? $sessionData[$cCode]['main_elements'] : array(),
                                    'main_inputs' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                                    'main_inputs_orig' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                                    "receiptDetailFields" => isset($this->configLayoutJenis['receiptDetailFields'][$stepNum]) ? $this->configLayoutJenis['receiptDetailFields'][$stepNum] : array(),
                                    "receiptSumFields" => isset($this->configLayoutJenis['receiptSumFields'][$stepNum]) ? $this->configLayoutJenis['receiptSumFields'][$stepNum] : array(),
                                    "receiptDetailFields2" => isset($this->configLayoutJenis['receiptDetailFields2'][$stepNum]) ? $this->configLayoutJenis['receiptDetailFields2'][$stepNum] : array(),
                                    "receiptSumFields2" => isset($this->configLayoutJenis['receiptSumFields2'][$stepNum]) ? $this->configLayoutJenis['receiptSumFields2'][$stepNum] : array(),
                                    "receiptDetailSrcFields" => isset($this->configLayoutJenis['receiptDetailSrcFields'][$stepNum]) ? $this->configLayoutJenis['receiptDetailSrcFields'][$stepNum] : array(),
                                    "jurnal_index" => isset($this->configCoreJenis['components'][$jenisTrTarget]) ? $this->configCoreJenis['components'][$jenisTrTarget] : array(),
                                    "preProcessor" => isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget] : array(),
                                    "postProcessor" => isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget] : array(),
                                    "revert" => isset($sessionData[$cCode]['revert']) ? $sessionData[$cCode]['revert'] : array(),
                                    "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                                    "componentsBuilder" => isset($sessionData[$cCode]['componentsBuilder']) ? $sessionData[$cCode]['componentsBuilder'] : array(),
                                    "jurnalItems" => isset($sessionData[$cCode]['jurnalItems']) ? $sessionData[$cCode]['jurnalItems'] : array(),

                                );
                                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                                $mongRegID = $doWriteReg;
//                                echo "<script>top.writeProgress('MENULIS KE-REGISTRY....');</script>";
                            }
                            else {
                                die(lgShowAlert("Transaksi gagal disimpan, silahkan cek kembali transaksi ini."));
                            }
                            //endregion
                            //mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");
                            //region processing sub-post-processors, always
                            //<editor-fold desc="----------sub postProc">
                            // matiHEre();
                            $iterator = isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
                            if (sizeof($iterator) > 0) {
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    echo "sub-postProcessor: $comName, initializing values <br>";
//                                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";

                                    $tmpOutParams[$cCtr] = array();
                                    foreach ($sessionData[$cCode][$srcGateName] as $cnt => $dSpec) {
                                        $subParams = array();
                                        if (isset($tComSpec['loop'])) {
                                            foreach ($tComSpec['loop'] as $key => $value) {

                                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt], $sessionData[$cCode][$srcGateName][$cnt], 0);
                                                $subParams['loop'][$key] = $realValue;

                                            }
                                        }
                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {

                                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt], $sessionData[$cCode][$srcGateName][$cnt], 0);
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
                                                $jenis = $sessionData[$cCode]['main']['jenis'];
                                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                                }
                                            }

                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                                        }

                                        if (sizeof($subParams) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
//                                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                                    }
                                }

                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    echo "sub-postProcessor: $comName, sending values <br>";
//                                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                                    $mdlName = "Com" . ucfirst($comName);
                                    $this->load->model("Coms/" . $mdlName);
                                    $m = new $mdlName();

                                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    cekBiru($this->db->last_query());
                                }
                            }

                            //endregion
                            //
                            //region processing main-post-processors, always
                            //<editor-fold desc="----------postProc">

                            $iterator = isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]['master']) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget]['master'] : array();
                            if (sizeof($iterator) > 0) {
//                                echo "<script>top.writeProgress('MEMPROSES MAIN-PROCESSORS...', 'head');</script>";
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    echo "post-processor: $comName<br>";

                                    $dSpec = $sessionData[$cCode][$srcGateName];
                                    $tmpOutParams = array();
                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                                            $tmpOutParams['loop'][$key] = $realValue;

                                        }
                                    }
                                    if (isset($tComSpec['static'])) {
                                        //cekHere("DISINI OIII");
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                                            $tmpOutParams['static'][$key] = $realValue;

                                        }
                                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                            foreach ($paramPatchers[$comName] as $k => $v) {
                                                if (!isset($tmpOutParams['static'][$k])) {
                                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
//                                                    echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                                }
                                            }
                                        }
                                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                            $jenis = $sessionData[$cCode]['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
//                                                echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                            }
                                        }
                                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


                                    }
                                    if (isset($tComSpec['static2'])) {
                                        //cekHere("DISINI OIII");
                                        foreach ($tComSpec['static2'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cCtr], $sessionData[$cCode][$srcGateName][$cCtr], 0);
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
                                            $jenis = $sessionData[$cCode]['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                        $tmpOutParams['static2']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


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
                            //
                            //region ----------subcomponents GESER KE CLI

                            //        $componentGate['detail'] = array();
                            //        //arrPrint($paramForceFillers);
                            $iterator = isset($this->configCoreJenis['components'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['components'][$jenisTrTarget]['detail'] : array();
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
                            //                foreach ($sessionData[$cCode][$srcGateName] as $id => $dSpec) {
                            //                    cekmerah("mengevaluasi $srcGateName..");
                            //                    $comName = $tComSpec['comName'];
                            //                    if (substr($comName, 0, 1) == "{") {
                            //                        $comName = trim($comName, "{");
                            //                        $comName = trim($comName, "}");
                            //                        $comName = str_replace($comName, $sessionData[$cCode][$srcGateName][$id][$comName], $comName);
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
                            //                                $key = str_replace($key, $sessionData[$cCode][$srcGateName][$id][$key], $key);
                            //                            }
                            //                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
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
                            //                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
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
                            //                            $jenis = $sessionData[$cCode]['main']['jenis'];
                            //                            foreach ($paramForceFillers[$comName] as $k => $v) {
                            //                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                            //                                cekOrange("fillforce :: $comName :: $k => " . $subParams['static'][$k]);
                            //                            }
                            //                        }
                            //                        $subParams['static']["fulldate"] = date("Y-m-d");
                            //                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            //                        $subParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
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
                            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                                $iterator = isset($sessionData[$cCode]['revert']['jurnal'][$stepNum]['master']) ? $sessionData[$cCode]['revert']['jurnal'][$stepNum]['master'] : array();
                            }
                            else {
                                if (isset($sessionData[$cCode]['componentsBuilder'][$stepNum]['master'])) {
                                    $iterator = $sessionData[$cCode]['componentsBuilder'][$stepNum]['master'];
                                }
                                elseif (isset($this->configCoreJenis['components'][$jenisTrTarget]['master'])) {
                                    $iterator = $this->configCoreJenis['components'][$jenisTrTarget]['master'];
                                }
                                else {
                                    $iterator = array();
                                }
                            }


                            if (sizeof($iterator) > 0) {
//                                echo "<script>top.writeProgress('KOMPONEN...', 'head');</script>";
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

                                    $dSpec = $sessionData[$cCode][$srcGateName];
                                    $tmpOutParams = array();
                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {
                                            if (substr($key, 0, 1) == "{") {
                                                $key = trim($key, "{");
                                                $key = trim($key, "}");
                                                //                            $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
                                                $key = str_replace($key, $sessionData[$cCode][$srcGateName][$key], $key);
                                            }
                                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                                            if ($key != null) {
                                                $tmpOutParams['loop'][$key] = $realValue;
                                            }

                                        }
                                    }
                                    //                cekBiru($tmpOutParams);
                                    //                mati_disini(__LINE__);
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
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
                                            $jenis = $sessionData[$cCode]['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekHijau(":: FORCEFILL :: $key => $realValue ::");
                                            }
                                        }
                                        $tmpOutParams['static']["urut"] = $cCtr;
                                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


                                    }
                                    if (isset($tComSpec['static2'])) {
                                        foreach ($tComSpec['static2'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
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
                                            $jenis = $sessionData[$cCode]['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                        $tmpOutParams['static2']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


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
                            $stepCode = $this->configUiJenis['steps'][$stepNum]['target'];
                            $paymentSources = $this->config->item("payment_source");
                            if (array_key_exists($stepCode, $paymentSources)) {
                                $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                                if (sizeof($payConfigs) > 0) {
                                    foreach ($payConfigs as $paymentSrcConfig) {
                                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                                        $valueSrc = $paymentSrcConfig['valueSrc'];
                                        $externSrc = $paymentSrcConfig['externSrc'];
                                        $valueAdd = isset($sessionData[$cCode]['main'][$paymentSrcConfig['addValueValidator']]) ? $sessionData[$cCode]['main'][$paymentSrcConfig['addValueValidator']] : 0;
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

                                        if (isset($sessionData[$cCode]['main'][$valueSrc]) && $sessionData[$cCode]['main'][$valueSrc] > 0) {
                                            if (isset($externSrc['extern_label2'])) {
                                                //cek ada isinya atau kosong
                                                $cek = strlen($sessionData[$cCode]['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
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
                                            //                        paymentSource($this->jenisTr, $componentJurnal, $sessionData[$cCode]['main'], $valueLabel, $valueSrc, $valueAdd);
                                            //-----------------------

                                            $arrPymSrc = array(
                                                "jenis" => $stepCode,
                                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                                "extern_id" => isset($sessionData[$cCode]['main'][$externSrc['id']]) ? $sessionData[$cCode]['main'][$externSrc['id']] : "",
                                                "extern_nama" => isset($sessionData[$cCode]['main'][$externSrc['nama']]) ? $sessionData[$cCode]['main'][$externSrc['nama']] : "",
                                                "nomer" => $tmpNomorNota2,
                                                "label" => $paymentSrcConfig['label'],

                                                "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $sessionData[$cCode]['main'][$valueSrc],

                                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                                "oleh_id" => SYS_ID,
                                                "oleh_nama" => SYS_NAMA,
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                                "valas_id" => isset($externSrc['valasId']) && isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                                "valas_nama" => isset($externSrc['valasLabel']) && isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                                "valas_nilai" => isset($externSrc['valasValue']) && isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',

                                                "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                                "terbayar_valas" => 0,
                                                "sisa_valas" => isset($externSrc['valasSisa']) && isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',

                                                //                            "extern_label2" => isset($sessionData[$cCode]['main']['pihakMainName']) ? $sessionData[$cCode]['main']['pihakMainName'] : "",
                                                "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData[$cCode]['main'][$externSrc['extern_label2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_label2']] : "",

                                                "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($sessionData[$cCode]['main'][$externSrc['dpp_ppn']])) ? $sessionData[$cCode]['main'][$externSrc['dpp_ppn']] : 0,
                                                "ppn" => (isset($externSrc['ppn']) && ($sessionData[$cCode]['main'][$externSrc['ppn']])) ? $sessionData[$cCode]['main'][$externSrc['ppn']] : 0,
                                                "ppn_approved" => (isset($externSrc['ppn_approved']) && ($sessionData[$cCode]['main'][$externSrc['ppn_approved']])) ? $sessionData[$cCode]['main'][$externSrc['ppn_approved']] : 0,
                                                "ppn_sisa" => (isset($externSrc['ppn']) && ($sessionData[$cCode]['main'][$externSrc['ppn']])) ? $sessionData[$cCode]['main'][$externSrc['ppn']] : "",
                                                "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                                "extern_date2" => (isset($externSrc['extern_date2']) && ($sessionData[$cCode]['main'][$externSrc['extern_date2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_date2']] : "",
                                                "pph_23" => (isset($externSrc['pph_23']) && ($sessionData[$cCode]['main'][$externSrc['pph_23']])) ? $sessionData[$cCode]['main'][$externSrc['pph_23']] : "",

                                                "npwp" => (isset($externSrc['npwp']) && ($sessionData[$cCode]['main'][$externSrc['npwp']])) ? $sessionData[$cCode]['main'][$externSrc['npwp']] : "",
                                                "extern2_id" => (isset($externSrc['extern2_id']) && ($sessionData[$cCode]['main'][$externSrc['extern2_id']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_id']] : "",
                                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($sessionData[$cCode]['main'][$externSrc['extern2_nama']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_nama']] : "",
                                                "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($sessionData[$cCode]['main'][$externSrc['ppn_pph_faktor']])) ? $sessionData[$cCode]['main'][$externSrc['ppn_pph_faktor']] : "",
                                                "extern_jenis" => (isset($externSrc['extern_jenis']) && ($sessionData[$cCode]['main'][$externSrc['extern_jenis']])) ? $sessionData[$cCode]['main'][$externSrc['extern_jenis']] : "",
                                                "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai3']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai3']] : "",
                                                "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai4']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai4']] : "",
                                                "npwp" => (isset($externSrc['npwp']) && ($sessionData[$cCode]['main'][$externSrc['npwp']])) ? $sessionData[$cCode]['main'][$externSrc['npwp']] : "",
                                                //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : "",
                                                "payment_locked" => (isset($externSrc['payment_locked']) && ($sessionData[$cCode]['main'][$externSrc['payment_locked']])) ? $sessionData[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                                "cash_account" => (isset($externSrc['cash_account']) && ($sessionData[$cCode]['main'][$externSrc['cash_account']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account']] : 0,
                                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($sessionData[$cCode]['main'][$externSrc['cash_account_nama']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
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

                            $addPaymentSource = isset($this->configUiJenis['steps'][$stepNum]['additionalStep']['shippingService']) ? $this->configUiJenis['steps'][$stepNum]['additionalStep']['shippingService'] : array();

                            //endregion

                            //region nulis paymentAntiSource
                            $stepCode = $this->configUiJenis['steps'][$stepNum]['target'];
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
                                            "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                            "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                            "nomer" => $tmpNomorNota2,
                                            "label" => $paymentSrcConfig['label'],
                                            "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                            "terbayar" => 0,
                                            "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                            "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                            "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                            "oleh_id" => SYS_ID,
                                            "oleh_nama" => SYS_NAMA,
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
                            $stepCode = $this->configUiJenis['steps'][$stepNum]['target'];
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
                                        $l->addFilter("extern_id='" . $sessionData[$cCode]['main'][$externSrc['id']] . "'");
                                        $l->addFilter("extern_label2='" . $externSrc['extLabel'] . "'");
                                        $tmpUm = $l->lookupAll()->result();
                                        //                    arrPrint($tmpUm);
                                        if (sizeof($tmpUm) > 0) {
                                            //update here broo
                                            $preTagihan = $tmpUm[0]->tagihan;
                                            $preSisa = $tmpUm[0]->sisa;

                                            $newTahigan = $preTagihan + $sessionData[$cCode]['main'][$valueSrc];
                                            $newsisa = $preSisa + $sessionData[$cCode]['main'][$valueSrc];
                                            $update = array(
                                                "tagihan" => $newTahigan,
                                                "sisa" => $newsisa,
                                            );
                                            $where = array(
                                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
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
                                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => "",
                                                "note" => "",
                                                "label" => $uangMukaSrcConfig['label'],
                                                "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                                "oleh_id" => SYS_ID,
                                                "oleh_nama" => SYS_NAMA,
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

                }
                // endregion auto approve

                //region connecting antar cabang
                $mongoListConnect = array();
                $mongRegIDConnect = array();
                $connector = isset($this->configUiJenis['connectTo']) ? $this->configUiJenis['connectTo'] : "";
                $preReplacer = isset($this->configUiJenis['replacerConnectTo']) ? $this->configUiJenis['replacerConnectTo'] : array();
                if (strlen($connector) > 0) {
                    cekMerah(":: CONNECTING BEGIN...");
                    //cekMerah("to be connected to $connector");
                    if (sizeof($steps) == 1) {
                        //cekMerah("now connecting to $connector");
                        if (!array_key_exists($connector, $this->configUi)) {
                            die("kode connector tidak dikenali!");
                        }
                        if (sizeof($this->configUi[$connector]['steps']) < 2) {
                            die("konfigurasi connector harus memiliki step lebih dari satu!");
                        }


                        $oldCode = $cCode;
                        $cCode = "_TR_" . $connector;

                        if (isset($sessionData[$cCode])) {
                            $sessionData[$cCode] = null;
                            unset($sessionData[$cCode]);
                            $sessionData[$cCode] = array();
                        }

                        //region detector cloner dalam satu cabang
                        $oldStep = $sessionData[$oldCode]['main']["step_number"];
                        $clonerTransaction = isset($this->configUiJenis['clonerTransaction'][$oldStep]) ? $this->configUiJenis['clonerTransaction'][$oldStep] : array();


                        //endregion
                        if (sizeof($clonerTransaction) && isset($clonerTransaction['main']['cloner'])) {
                            cekHere("i am here");
                            $replacerTableinDetail = array(
                                "dtime" => "dtime",
                                "produk_id" => "id",
                                "produk_kode" => "kode",
                                "produk_label" => "label",
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "produk_ord_jml",
                                "produk_ord_hrg" => "harga1",
                                "satuan" => "satuan",
                                "produk_jenis" => "produk_jenis",
                                "harga" => "harga1",
                                "valid_qty" => "produk_ord_jml",
                            );
                            $replacerStepItems2 = array(
                                "sub_tail_number" => "",
                                "sub_tail_code" => "",
                                "sub_step_avail" => "",
                                "sub_step_current" => "",
                                "sub_step_number" => "",
                                "next_substep_num" => "",
                                "next_substep_code" => "",
                                "next_substep_label" => "",
                                "next_subgroup_code" => "",
                            );

                            $itesmNewTmp = array();
                            foreach ($sessionData[$oldCode]['items2'] as $indexItems2) {
                                foreach ($indexItems2 as $itemsDetail) {

                                    $itesmNewTmp[$itemsDetail['id']] = array_merge($itemsDetail, $replacerStepItems2);
                                    foreach ($replacerTableinDetail as $selCol => $xAlias) {
                                        $sessionData[$oldCode]['tableIn_detail2'][$itemsDetail['id']][$selCol] = $itemsDetail[$selCol];
                                    }
                                    foreach ($replacerStepItems2 as $stepItems2 => $tempItems2Val) {
                                        $sessionData[$oldCode]['tableIn_detail2'][$itemsDetail['id']][$stepItems2] = $tempItems2Val;
                                    }

                                }

                                //                            arrPrint($itesmNew);
                            }
                            $itesmNew = array();
                            foreach ($itesmNewTmp as $itemsID => $itemData) {
                                if (isset($itemData['harga'])) {
                                    $itemData['harga'] = $itemData['produk_ord_hrg'];
                                }
                                if (!isset($itemData['pihakName'])) {
                                    $itemData['pihakName'] = $sessionData[$oldCode]['main']['pihakName'];
                                }

                                $itesmNew[$itemsID] = $itemData;
                            }

                            //region itemsToMaster
                            $itemTomasterStatic = isset($clonerTransaction['staticItemToMaster']) ? $clonerTransaction['staticItemToMaster'] : array();
                            $itemToMaster = isset($clonerTransaction['itemToMaster']) ? $clonerTransaction['itemToMaster'] : array();
                            //                        arrPrint($itemToMaster);
                            if (sizeof($itemToMaster) > 0) {
                                foreach ($sessionData[$oldCode]['items'] as $itemsTemp) {
                                    foreach ($itemToMaster as $colItems => $aliasMaster) {
                                        if (isset($itemsTemp[$colItems])) {
                                            $sessionData[$oldCode]['main'][$aliasMaster] = $itemsTemp[$colItems];
                                            $sessionData[$oldCode]['tableIn_master'][$aliasMaster] = $itemsTemp[$colItems];
                                        }
                                    }
                                }
                                if (sizeof($itemTomasterStatic) > 0) {
                                    foreach ($itemTomasterStatic as $kolStatic => $valStatic) {
                                        $sessionData[$oldCode]['main'][$kolStatic] = $valStatic;
                                        $sessionData[$oldCode]['tableIn_master'][$kolStatic] = $valStatic;
                                    }
                                }

                            }


                            //endregion

                            $sessionData[$cCode] = array(
                                "main" => $sessionData[$oldCode]['main'],
                                "items" => $itesmNew,
                                'items2' => isset($sessionData[$oldCode]['items2']) ? $sessionData[$oldCode]['items2'] : array(),
                                'items2_sum' => isset($sessionData[$oldCode]['items2_sum']) ? $sessionData[$oldCode]['items2_sum'] : array(),
                                'items3' => isset($sessionData[$oldCode]['items3']) ? $sessionData[$oldCode]['items3'] : array(),
                                'items3_sum' => isset($sessionData[$oldCode]['items3_sum']) ? $sessionData[$oldCode]['items3_sum'] : array(),
                                'items4_sum' => isset($sessionData[$oldCode]['items4_sum']) ? $sessionData[$oldCode]['items4_sum'] : array(),
                                "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                                "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail2'],
                                'tableIn_detail2_sum' => isset($sessionData[$oldCode]['tableIn_detail2_sum']) ? $sessionData[$oldCode]['tableIn_detail2_sum'] : array(),
                                "rsltItems" => $sessionData[$oldCode]['rsltItems'],
                                'rsltItems2' => isset($sessionData[$oldCode]['rsltItems2']) ? $sessionData[$oldCode]['rsltItems2'] : array(),
                                "tableIn_detail_rsltItems" => $sessionData[$oldCode]['tableIn_detail_rsltItems'],
                                'tableIn_detail_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_rsltItems2'] : array(),
                                "main_add_values" => $sessionData[$oldCode]['main_add_values'],
                                "main_add_fields" => $sessionData[$oldCode]['main_add_fields'],
                                "main_elements" => $sessionData[$oldCode]['main_elements'],
                                //
                                "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                                "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values2_sum'],
                                "tableIn_detail_values_rsltItems" => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems'] : array(),
                                'tableIn_detail_values_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems2'] : array(),
                                'tableIn_detail_values2_sum' => isset($sessionData[$oldCode]['tableIn_detail_values2_sum']) ? $sessionData[$oldCode]['tableIn_detail_values2_sum'] : array(),
                            );

                            //region unset sessio details2
                            //                        unset($sessionData[$cCode]['tableIn_detail_values2_sum']);
                            if (isset($clonerTransaction['resetGate']) && (sizeof($clonerTransaction['resetGate']) > 0)) {
                                foreach ($clonerTransaction['resetGate'] as $gate) {
                                    unset($sessionData[$cCode][$gate]);
                                }
                            }
                            //endregion

                            $masterReplacers = array(
                                //                        "referensi_id" => $masterID, (dimatikan)
                                "inv" => $tmpNomorNota,
                                //                        "jenis_master"    => $connector,

                                "jenis_master" => $connector,
                                "jenis_top" => $this->configUi[$connector]['steps'][1]['target'],
                                //                        "jenis_top" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis_label" => $this->configUi[$connector]['steps'][1]['label'],
                                "transaksi_jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "div_id" => "18",
                                "div_nama" => "default",
                                //                            "cabang_id"       => $sessionData[$oldCode]['tableIn_master']['cabang2_id'],
                                //                            "cabang_nama"     => $sessionData[$oldCode]['tableIn_master']['cabang2_nama'],
                                //                            "cabang2_id"      => $sessionData[$oldCode]['tableIn_master']['cabang_id'],
                                //                            "cabang2_nama"    => $sessionData[$oldCode]['tableIn_master']['cabang_nama'],
                                //                            "gudang_id"       => $sessionData[$oldCode]['tableIn_master']['gudang2_id'],
                                //                            "gudang_nama"     => $sessionData[$oldCode]['tableIn_master']['gudang2_nama'],
                                //                            "gudang2_id"      => $sessionData[$oldCode]['tableIn_master']['gudang_id'],
                                //                            "gudang2_nama"    => $sessionData[$oldCode]['tableIn_master']['gudang_nama'],

                                "step_avail" => sizeof($this->configUi[$connector]['steps']),
                                "step_current" => 1,
                                "step_number" => 1,
                                "next_step_code" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['target'] : "",
                                "next_step_label" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['label'] : "",
                                "next_group_code" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['userGroup'] : "",
                                "next_step_num" => isset($this->configUi[$connector]['steps'][2]) ? 2 : "0",


                            );

                            //                        arrPrint( $sessionData[$cCode]);
                            $masterReplacersO = array(
                                "jenisTr" => $connector,
                                "div_id" => "18",
                                "jenisTrMaster" => $connector,
                                "jenisTrTop" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis_label" => $this->configUi[$connector]['steps'][1]['label'],
                                "transaksi_jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "stepCode" => $this->configUi[$connector]['steps'][1]['target'],
                                //                            "placeID"         => $sessionData[$oldCode]['main']['place2ID'],


                                //                            "placeName"       => $sessionData[$oldCode]['main']['place2Name'],
                                //                            "place2ID"        => $sessionData[$oldCode]['main']['placeID'],
                                //                            "place2Name"      => $sessionData[$oldCode]['main']['placeName'],
                                //                            "cabangID"        => $sessionData[$oldCode]['main']['place2ID'],
                                //                            "cabangName"      => $sessionData[$oldCode]['main']['place2Name'],
                                //                            "cabang2ID"       => $sessionData[$oldCode]['main']['placeID'],
                                //                            "cabang2Name"     => $sessionData[$oldCode]['main']['placeName'],
                                //                            //
                                //                            "gudang2ID"       => $sessionData[$cCode]['main']['gudangID'],
                                //                            "gudang2Name"     => $sessionData[$cCode]['main']['gudangName'],
                                //                            "gudangID"        => $sessionData[$cCode]['main']['gudang2ID'],
                                //                            "gudangName"      => $sessionData[$cCode]['main']['gudang2Name'],
                            );
                        }
                        else {
                            $sessionData[$cCode] = array(
                                "main" => $sessionData[$oldCode]['main'],
                                "items" => $sessionData[$oldCode]['items'],
                                'items2' => isset($sessionData[$oldCode]['items2']) ? $sessionData[$oldCode]['items2'] : array(),
                                'items2_sum' => isset($sessionData[$oldCode]['items2_sum']) ? $sessionData[$oldCode]['items2_sum'] : array(),
                                'items3' => isset($sessionData[$oldCode]['items3']) ? $sessionData[$oldCode]['items3'] : array(),
                                'items3_sum' => isset($sessionData[$oldCode]['items3_sum']) ? $sessionData[$oldCode]['items3_sum'] : array(),
                                'items4_sum' => isset($sessionData[$oldCode]['items4_sum']) ? $sessionData[$oldCode]['items4_sum'] : array(),

                                "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                                "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],
                                'tableIn_detail2_sum' => isset($sessionData[$oldCode]['tableIn_detail2_sum']) ? $sessionData[$oldCode]['tableIn_detail2_sum'] : array(),
                                "rsltItems" => $sessionData[$oldCode]['rsltItems'],
                                'rsltItems2' => isset($sessionData[$oldCode]['rsltItems2']) ? $sessionData[$oldCode]['rsltItems2'] : array(),

                                "tableIn_detail_rsltItems" => $sessionData[$oldCode]['tableIn_detail_rsltItems'],
                                'tableIn_detail_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_rsltItems2'] : array(),
                                "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                                "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values'],
                                "tableIn_detail_values_rsltItems" => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems'] : array(),
                                'tableIn_detail_values_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems2'] : array(),
                                'tableIn_detail_values2_sum' => isset($sessionData[$oldCode]['tableIn_detail_values2_sum']) ? $sessionData[$oldCode]['tableIn_detail_values2_sum'] : array(),
                            );
                            $masterReplacers = array(
                                //                        "referensi_id" => $masterID, (dimatikan)
                                "inv" => $tmpNomorNota,
                                //                        "jenis_master"    => $connector,

                                "jenis_master" => $connector,
                                "jenis_top" => $this->configUi[$connector]['steps'][1]['target'],
                                //                        "jenis_top" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis_label" => $this->configUi[$connector]['steps'][1]['label'],
                                "transaksi_jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$oldCode]['tableIn_master']['cabang2_id'],
                                "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $sessionData[$oldCode]['tableIn_master']['cabang2_nama'],
                                "cabang2_id" => $sessionData[$oldCode]['tableIn_master']['cabang_id'],
                                "cabang2_nama" => $sessionData[$oldCode]['tableIn_master']['cabang_nama'],
                                "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$oldCode]['tableIn_master']['gudang2_id'],
                                "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$oldCode]['tableIn_master']['gudang2_nama'],
                                "gudang2_id" => $sessionData[$oldCode]['tableIn_master']['gudang_id'],
                                "gudang2_nama" => $sessionData[$oldCode]['tableIn_master']['gudang_nama'],

                                "step_avail" => sizeof($this->configUi[$connector]['steps']),
                                "step_current" => 1,
                                "step_number" => 1,
                                "next_step_code" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['target'] : "",
                                "next_step_label" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['label'] : "",
                                "next_group_code" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['userGroup'] : "",
                                "next_step_num" => isset($this->configUi[$connector]['steps'][2]) ? 2 : "0",
                            );
                            $masterReplacersO = array(
                                "jenisTr" => $connector,
                                "jenisTrMaster" => $connector,
                                "jenisTrTop" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "jenis_label" => $this->configUi[$connector]['steps'][1]['label'],
                                "transaksi_jenis" => $this->configUi[$connector]['steps'][1]['target'],
                                "stepCode" => $this->configUi[$connector]['steps'][1]['target'],
                                "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $sessionData[$oldCode]['main']['place2ID'],
                                "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$oldCode]['main']['place2Name'],
                                "place2ID" => $sessionData[$oldCode]['main']['placeID'],
                                "place2Name" => $sessionData[$oldCode]['main']['placeName'],
                                "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$oldCode]['main']['place2ID'],
                                "cabangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$oldCode]['main']['place2Name'],
                                "cabang2ID" => $sessionData[$oldCode]['main']['placeID'],
                                "cabang2Name" => $sessionData[$oldCode]['main']['placeName'],
                                //
                                "gudang2ID" => $sessionData[$cCode]['main']['gudangID'],
                                "gudang2Name" => $sessionData[$cCode]['main']['gudangName'],
                                "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['main']['gudang2ID'],
                                "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['main']['gudang2Name'],
                                "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",
                            );
                        }

                        //==replace pertama
                        foreach ($masterReplacersO as $key => $val) {
                            $sessionData[$cCode]['main'][$key] = $val;
                        }
                        foreach ($masterReplacers as $key => $val) {
                            $sessionData[$cCode]['tableIn_master'][$key] = $val;
                        }


                        //region penomoran receipt #2
                        //<editor-fold desc="==========penomoran">
                        $this->load->model("CustomCounter");
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");

                        $counterForNumber = array($this->configCore[$connector]['formatNota']);
                        if (!in_array($counterForNumber[0], $this->configCore[$connector]['counters'])) {
                            die(__LINE__ . " Used number should be registered in 'counters' config as well");
                        }

                        foreach ($counterForNumber as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        }

                        $tmpNomorNota2 = $paramSpec['paramString'];
                        $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);


                        //</editor-fold>
                        //endregion

                        //region dynamic counters #2
                        // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");
                        $configCustomParams = $this->configCore[$connector]['counters'];
                        $configCustomParams[] = "stepCode";
                        if (sizeof($configCustomParams) > 0) {
                            $cContent = array();
                            foreach ($configCustomParams as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
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

                        //region numbering tambahan
                        $this->load->library("CounterNumber");
                        $ccn = new CounterNumber();
                        $ccn->setCCode($cCode);
                        $ccn->setJenisTr($connector);
                        $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
                        $ccn->setMainGate($sessionData[$cCode]['main']);
                        $ccn->setItemsGate($sessionData[$cCode]['items']);
                        $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
                        $new_counter = $ccn->getCounterNumber();
                        cekHitam("jenistr yang disett dari create " . $this->jenisTr);

                        if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                            foreach ($new_counter['main'] as $ckey => $cval) {
                                $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                                $sessionData[$cCode]['main'][$ckey] = $cval;
                            }
                        }
                        if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                            foreach ($new_counter['items'] as $ikey => $iSpec) {
                                foreach ($iSpec as $iikey => $iival) {
                                    $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                                }
                            }
                        }
                        if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                            foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                                foreach ($iSpec as $iikey => $iival) {
                                    $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                                }
                            }
                        }
                        //endregion
                        $addValues = array(
                            'counters' => $appliedCounters,
                            'counters_intext' => $appliedCounters_inText,
                            'nomer' => $tmpNomorNota2,
                            'nomer2' => $tmpNomorNota2Alias,
                            'dtime' => date("Y-m-d H:i:s"),
                            'fulldate' => date("Y-m-d"),
                        );
                        foreach ($addValues as $key => $val) {
                            $sessionData[$cCode]['tableIn_master'][$key] = $val;
                        }

                        //===cloning nota cab1 ke cab2
                        //===daftar perbedaan
                        //== referensi_id, inv, jenis, nomer, counters, counters_inText, cabang_id, cabang_nama, cabang2_id, cabang2_nama,


                        //==replace kedua

                        $masterReplacers = array(
                            "nomer" => $tmpNomorNota2,
                            "nomer2" => $tmpNomorNota2Alias,
                            "counters" => $appliedCounters2,
                            "counters_intext" => $appliedCounters_inText2,
                        );
                        foreach ($masterReplacers as $key => $val) {
                            $sessionData[$cCode]['tableIn_master'][$key] = $val;
                        }

                        //===cloning detail/items cabang1 ke cabang2
                        //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                        $detailReplacers = array(
                            "sub_step_avail" => sizeof($this->configUi[$connector]['steps']),
                            "sub_step_current" => 1,
                            "sub_step_number" => 1,
                            "next_substep_num" => $sessionData[$cCode]['tableIn_master']['next_step_num'],
                            "next_substep_code" => $sessionData[$cCode]['tableIn_master']['next_step_code'],
                            "next_substep_label" => $sessionData[$cCode]['tableIn_master']['next_step_label'],
                            "next_subgroup_code" => $sessionData[$cCode]['tableIn_master']['next_group_code'],
                            //                    "next_substep_code" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['target'] : "",
                            //                    "next_substep_label" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['label'] : "",
                            //                    "next_subgroup_code" => isset($this->configUi[$connector]['steps'][2]) ? $this->configUi[$connector]['steps'][2]['userGroup'] : "",
                        );
                        if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                            foreach ($sessionData[$cCode]['tableIn_detail'] as $k => $dSpec) {
                                foreach ($dSpec as $key => $val) {
                                    $sessionData[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                                }
                            }
                        }

                        //region ----------write transaksi & transaksi_data #2
                        if (isset($sessionData[$cCode]['tableIn_master']) && sizeof($sessionData[$cCode]['tableIn_master']) > 0) {
                            $tr = new MdlTransaksi();
                            $tr->addFilter("transaksi.cabang_id='" . $transaksi_current_cabang_id . "'");
                            $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                            cekHitam($this->db->last_query());
                            $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData[$cCode]['tableIn_master']);
                            $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                            $sessionData[$cCode]['main']['nomer'] = $insertNum;
                            if ($insertID < 1) {
                                die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                            }
                            $mongoListConnect['main'] = array($insertID, $epID);
                        }
                        $inserMainValues = array();
                        if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                            foreach ($sessionData[$cCode]['tableIn_master_values'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] = $dd;
                                $mongoListConnect['mainValues'][] = $dd;
                            }
                        }

                        if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                            foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] = $dd;
                                $mongoListConnect['mainValues'][] = $dd;
                            }
                        }

                        if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                            cekkuning("main_inputs detected");
                            $inserMainValues = array();
                            foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] = $dd;
                                $mongoListConnect['mainValues'][] = $dd;
                                //                            cekkuning("making a clone for input key $key / $val");
                                //                            $subInputInsertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                            }
                        }

                        if (isset($sessionData[$cCode]['main_add_fields']) && sizeof($sessionData[$cCode]['main_add_fields']) > 0) {
                            foreach ($sessionData[$cCode]['main_add_fields'] as $key => $val) {
                                $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                            }
                        }


                        if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                            foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
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

                        if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                            $insertIDs = array();
                            $insertDeIDs = array();
                            foreach ($sessionData[$cCode]['tableIn_detail'] as $dSpec) {
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
                                    cekOrange($this->db->last_query());
                                }
                            }
                        }

                        if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                            $insertIDs = array();
                            foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                                $insertIDDetail = $tr->writeDetailEntries($insertID, $dSpec);
                                $insertIDs[] = $insertIDDetail;
                                $mongoListConnect['detail'][] = $insertIDDetail;
                                if ($epID != 999) {
                                    $insertIDDetail = $tr->writeDetailEntries($epID, $dSpec);
                                    $insertIDs[] = $insertIDDetail;
                                    $mongoListConnect['detail'][] = $insertIDDetail;
                                }
                            }
                        }

                        if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                            $insertIDs = array();
                            foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                                if (isset($this->configCoreJenis['tableIn']['detailValues'])) {
                                    foreach ($this->configCoreJenis['tableIn']['detailValues'] as $key => $src) {
                                        if (isset($sessionData[$cCode]['tableIn_detail'][$pID])) {
                                            $dd = $tr->writeDetailValues($insertID, array(
                                                "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                                "produk_id" => $pID,
                                                "key" => $key,
                                                "value" => $dSpec[$src],
                                            ));
                                            $insertIDs[$pID][] = $dd;
                                            $mongoListConnect['detailValues'][] = $dd;
                                        }
                                    }
                                }
                            }
                            if (sizeof($insertIDs) > 0) {
                                $arrBlob = blobEncode($insertIDs);
                                $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                            }
                        }

                        if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                            foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                                if (isset($this->configCoreJenis['tableIn']['detailValues2_sum'])) {
                                    foreach ($this->configCoreJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                        $dd = $tr->writeDetailValues($insertID, array(
                                            "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                            "produk_id" => $pID,
                                            "key" => $key,
                                            "value" => $dSpec[$src],
                                        ));
                                        $insertIDs[] = $dd;
                                        $mongoListConnect['detailValues'][] = $dd;
                                    }
                                }


                            }
                        }


                        $idHis = array(
                            $stepNumber => array(
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "olehID" => $sessionData[$cCode]['main']['olehID'],
                                "olehName" => $sessionData[$cCode]['main']['olehName'],
                                "step" => $stepNumber,
                                "trID" => $insertID,
                                "nomer" => $tmpNomorNota2,
                                "nomer2" => $tmpNomorNota2Alias,
                                "counters" => $appliedCounters2,
                                "counters_intext" => $appliedCounters_inText2,
                            ),
                        );
                        $idHis_blob = blobEncode($idHis);
                        $idHis_intext = print_r($idHis, true);

                        $tr = new MdlTransaksi();
                        $dupState = $tr->updateData(array("id" => $insertID), array(
                            "id_master" => $masterID,
                            "id_top" => $insertID,

                            "ids_his" => $idHis_blob,
                            "ids_his_intext" => $idHis_intext,

                        )) or die("Failed to update tr next-state!");

                        $baseRegistries = array(
                            'main' => isset($sessionData[$cCode]['main']) ? $sessionData[$cCode]['main'] : array(),
                            'items' => isset($sessionData[$cCode]['items']) ? $sessionData[$cCode]['items'] : array(),
                            'items2' => isset($sessionData[$cCode]['items2']) ? $sessionData[$cCode]['items2'] : array(),
                            'items2_sum' => isset($sessionData[$cCode]['items2_sum']) ? $sessionData[$cCode]['items2_sum'] : array(),
                            'items3' => isset($sessionData[$cCode]['items3']) ? $sessionData[$cCode]['items3'] : array(),
                            'items3_sum' => isset($sessionData[$cCode]['items3_sum']) ? $sessionData[$cCode]['items3_sum'] : array(),
                            'items4_sum' => isset($sessionData[$cCode]['items4_sum']) ? $sessionData[$cCode]['items4_sum'] : array(),

                            'rsltItems' => isset($sessionData[$cCode]['rsltItems']) ? $sessionData[$cCode]['rsltItems'] : array(),
                            'rsltItems2' => isset($sessionData[$cCode]['rsltItems2']) ? $sessionData[$cCode]['rsltItems2'] : array(),
                            'rsltItems3' => isset($sessionData[$cCode]['rsltItems3']) ? $sessionData[$cCode]['rsltItems3'] : array(),

                            'tableIn_master' => isset($sessionData[$cCode]['tableIn_master']) ? $sessionData[$cCode]['tableIn_master'] : array(),
                            'tableIn_detail' => isset($sessionData[$cCode]['tableIn_detail']) ? $sessionData[$cCode]['tableIn_detail'] : array(),
                            'tableIn_detail2_sum' => isset($sessionData[$cCode]['tableIn_detail2_sum']) ? $sessionData[$cCode]['tableIn_detail2_sum'] : array(),
                            'tableIn_detail_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_rsltItems'] : array(),
                            'tableIn_detail_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                            'tableIn_master_values' => isset($sessionData[$cCode]['tableIn_master_values']) ? $sessionData[$cCode]['tableIn_master_values'] : array(),
                            'tableIn_detail_values' => isset($sessionData[$cCode]['tableIn_detail_values']) ? $sessionData[$cCode]['tableIn_detail_values'] : array(),
                            'tableIn_detail_values_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                            'tableIn_detail_values_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                            'tableIn_detail_values2_sum' => isset($sessionData[$cCode]['tableIn_detail_values2_sum']) ? $sessionData[$cCode]['tableIn_detail_values2_sum'] : array(),
                            'main_add_values' => isset($sessionData[$cCode]['main_add_values']) ? $sessionData[$cCode]['main_add_values'] : array(),
                            'main_add_fields' => isset($sessionData[$cCode]['main_add_fields']) ? $sessionData[$cCode]['main_add_fields'] : array(),
                            'main_elements' => isset($sessionData[$cCode]['main_elements']) ? $sessionData[$cCode]['main_elements'] : array(),
                            'main_inputs' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                            'main_inputs_orig' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                            "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$connector]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$connector]['receiptDetailFields'][1] : array(),
                            "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$connector]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$connector]['receiptSumFields'][1] : array(),
                            "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$connector]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$connector]['receiptDetailFields2'][1] : array(),
                            "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$connector]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$connector]['receiptSumFields2'][1] : array(),
                            "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                        );
                        //                    arrPrint($baseRegistries);

                        $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                        $mongRegIDConnect = $doWriteReg;
                        cekBiru($this->db->last_query());
                        //endregion

                        //
                        //region nulis paymentSource
                        //                    $stepCode = $connector;
                        $stepCode = $this->configUi[$connector]['steps'][1]['target'];
                        $paymentSources = $this->config->item("payment_source");
                        cekHitam(":: $stepCode ::");
                        if (array_key_exists($stepCode, $paymentSources)) {

                            $payConfigs = $paymentSources[$stepCode];
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                                    $valueSrc = $paymentSrcConfig['valueSrc'];
                                    $externSrc = $paymentSrcConfig['externSrc'];
                                    $tr->writePaymentSrc($insertID, array(
                                        "jenis" => $connector,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                        "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $sessionData[$cCode]['main']['nomer'],
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                        "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                        "oleh_id" => SYS_ID,
                                        "oleh_nama" => SYS_NAMA,
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                        "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                        "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                        "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                                        "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                        "terbayar_valas" => 0,
                                        "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
                                    ));
                                    //cekMerah($this->db->last_query());
                                }
                            }


                        }
                        else {
                            //cekMerah("TIDAK nulis paymentSrc");
                        }
                        //endregion


                        //region nulis paymentAntiSource
                        //                    $stepCode = $connector;
                        $stepCode = $this->configUi[$connector]['steps'][1]['target'];
                        $paymentSources = $this->config->item("payment_antiSource");
                        if (array_key_exists($stepCode, $paymentSources)) {

                            $payConfigs = $paymentSources[$stepCode];
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                                    $valueSrc = $paymentSrcConfig['valueSrc'];
                                    $externSrc = $paymentSrcConfig['externSrc'];
                                    $tr->writePaymentAntiSrc($insertID, array(
                                        "jenis" => $connector,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                        "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $sessionData[$cCode]['main']['nomer'],
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                        "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                        "oleh_id" => SYS_ID,
                                        "oleh_nama" => SYS_NAMA,
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


                        //==================================================================================================
                        //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                        // bila step lebih dari 1
                        $nextStepConnector = sizeof($this->configUi[$connector]['steps']);
                        if ($nextStepConnector > 1) {
                            $this->load->model("Mdls/MdlLockerTransaksi");
                            $lt = New MdlLockerTransaksi();
                            $lt->execLocker($sessionData[$cCode]['main'], $nextStepConnector, NULL, $insertID);
                        }


                    }
                    else {
                        //cekMerah("to be delayed to connect to $connector");
                    }
                }
                else {
                    //cekKuning("not connecting to any tCode");
                }

                //endregion

                //region --Bagian connectToStep, membuat koneksi ke cabang berdasarkan step
                $connectToStepByOptionData = isset($this->configUiJenis['connectToStepByOption'][1]) ? $this->configUiJenis['connectToStepByOption'][1] : NULL;
//                arrPrintKuning($connectToStepByOptionData);
                if ($connectToStepByOptionData != NULL) {
                    $keyMethode = $sessionData[$cCode]["main"]["uangMukaMethod"];
                    cekHitam("MULAI CONNECT TO BY STEP [$keyMethode]");
                    // $connectToStepByOption = $connectToStepByOptionData[$keyMethode];
                    if (isset($connectToStepByOptionData[$keyMethode])) {
                        $connectToStep = $connectToStepByOptionData[$keyMethode];
                        cekHitam(":: connectToStep : $connectToStep ::");
                        $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiUi");
                        $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiCore");
                        $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiLayout");
                        $preReplacer = isset($this->configUiJenis['replacerConnectToStep']) ? $this->configUiJenis['replacerConnectToStep'] : array();
                        $connectToStepMainBuilder = isset($this->configUiJenis['connectToStepMainBuilder'][1]) ? $this->configUiJenis['connectToStepMainBuilder'][1] : array();

                        if (sizeof($configUiMasterModulJenis) == 0) {
                            die("kode connector tidak dikenali!");
                        }
                        if (sizeof($configUiMasterModulJenis['steps']) < 2) {
                            die("konfigurasi connector harus memiliki step lebih dari satu!");
                        }


                        $oldCode = $cCode;
                        $cCode = "_TR_" . $connectToStep;

                        $sessionData[$cCode] = array();

                        $oldStep = $sessionData[$oldCode]['main']["step_number"];
                        $clonerTransaction = isset($this->configUiJenis['clonerTransaction'][1]) ? $this->configUiJenis['clonerTransaction'][1] : array();
                        if (sizeof($clonerTransaction) && isset($clonerTransaction['main']['cloner'])) {
                            cekHere("i am here");
                            $rebuildGate = array();
                            if (isset($clonerTransaction['resetGate']) && (sizeof($clonerTransaction['resetGate']) > 0)) {
                                foreach ($clonerTransaction['resetGate'] as $gate) {
                                    //                                unset($sessionData[$oldCode][$gate]);
                                }
                                if (isset($clonerTransaction['rebuildGate']) && (sizeof($clonerTransaction['rebuildGate']) > 0)) {
                                    foreach ($clonerTransaction['rebuildGate'] as $gate => $gateSpec) {
                                        $gateKey = $gateSpec["key"];
                                        foreach ($gateSpec["isi"] as $gkey => $gval) {
                                            $rebuildGate[$gate][$gateKey][$gkey] = makeValue($gval, $sessionData[$oldCode]["main"], $sessionData[$oldCode]["main"], 0);
                                        }
                                    }
                                }
                            }
                            else {
                                $rebuildGate = array(
                                    "items" => $sessionData[$oldCode]['items'],
                                    "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],
                                );
                            }
                            //arrPrintWebs($rebuildGate);
                            //mati_disini();
                            $replacerTableinDetail = array(
                                "dtime" => "dtime",
                                "produk_id" => "id",
                                "produk_kode" => "kode",
                                "produk_label" => "label",
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "produk_ord_jml",
                                "produk_ord_hrg" => "harga1",
                                "satuan" => "satuan",
                                "produk_jenis" => "produk_jenis",
                                "harga" => "harga1",
                                "valid_qty" => "produk_ord_jml",
                            );
                            $replacerStepItems2 = array(
                                "sub_tail_number" => "",
                                "sub_tail_code" => "",
                                "sub_step_avail" => "",
                                "sub_step_current" => "",
                                "sub_step_number" => "",
                                "next_substep_num" => "",
                                "next_substep_code" => "",
                                "next_substep_label" => "",
                                "next_subgroup_code" => "",
                            );

                            $itesmNewTmp = array();
                            //                foreach ($sessionData[$oldCode]['items2'] as $indexItems2) {
                            //                    foreach ($indexItems2 as $itemsDetail) {
                            //
                            //                        $itesmNewTmp[$itemsDetail['id']] = array_merge($itemsDetail, $replacerStepItems2);
                            //                        foreach ($replacerTableinDetail as $selCol => $xAlias) {
                            //                            $sessionData[$oldCode]['tableIn_detail2'][$itemsDetail['id']][$selCol] = $itemsDetail[$selCol];
                            //                        }
                            //                        foreach ($replacerStepItems2 as $stepItems2 => $tempItems2Val) {
                            //                            $sessionData[$oldCode]['tableIn_detail2'][$itemsDetail['id']][$stepItems2] = $tempItems2Val;
                            //                        }
                            //
                            //                    }
                            //
                            //                    //                            arrPrint($itesmNew);
                            //                }
                            $itesmNew = array();
                            //                foreach ($itesmNewTmp as $itemsID => $itemData) {
                            //                    if (isset($itemData['harga'])) {
                            //                        $itemData['harga'] = $itemData['produk_ord_hrg'];
                            //                    }
                            //                    if (!isset($itemData['pihakName'])) {
                            //                        $itemData['pihakName'] = $sessionData[$oldCode]['main']['pihakName'];
                            //                    }
                            //
                            //                    $itesmNew[$itemsID] = $itemData;
                            //                }

                            //region itemsToMaster

                            //                $itemTomasterStatic = isset($clonerTransaction['staticItemToMaster']) ? $clonerTransaction['staticItemToMaster'] : array();
                            //                $itemToMaster = isset($clonerTransaction['itemToMaster']) ? $clonerTransaction['itemToMaster'] : array();
                            //                //                        arrPrint($itemToMaster);
                            //                if (sizeof($itemToMaster) > 0) {
                            //                    foreach ($sessionData[$oldCode]['items'] as $itemsTemp) {
                            //                        foreach ($itemToMaster as $colItems => $aliasMaster) {
                            //                            if (isset($itemsTemp[$colItems])) {
                            //                                $sessionData[$oldCode]['main'][$aliasMaster] = $itemsTemp[$colItems];
                            //                                $sessionData[$oldCode]['tableIn_master'][$aliasMaster] = $itemsTemp[$colItems];
                            //                            }
                            //                        }
                            //                    }
                            //                    if (sizeof($itemTomasterStatic) > 0) {
                            //                        foreach ($itemTomasterStatic as $kolStatic => $valStatic) {
                            //                            $sessionData[$oldCode]['main'][$kolStatic] = $valStatic;
                            //                            $sessionData[$oldCode]['tableIn_master'][$kolStatic] = $valStatic;
                            //                        }
                            //                    }
                            //
                            //                }

                            //endregion

                            $sessionData[$cCode] = array(
                                "main" => $sessionData[$oldCode]['main'],
                                //                            "items" => isset($sessionData[$oldCode]['items']) ? $sessionData[$oldCode]['items'] : array(),
                                "items" => isset($rebuildGate['items']) ? $rebuildGate['items'] : array(),
                                'items2' => isset($sessionData[$oldCode]['items2']) ? $sessionData[$oldCode]['items2'] : array(),
                                'items2_sum' => isset($sessionData[$oldCode]['items2_sum']) ? $sessionData[$oldCode]['items2_sum'] : array(),
                                'items3' => isset($sessionData[$oldCode]['items3']) ? $sessionData[$oldCode]['items3'] : array(),
                                'items3_sum' => isset($sessionData[$oldCode]['items3_sum']) ? $sessionData[$oldCode]['items3_sum'] : array(),
                                'items4' => isset($sessionData[$oldCode]['items4']) ? $sessionData[$oldCode]['items4'] : array(),
                                'items4_sum' => isset($sessionData[$oldCode]['items4_sum']) ? $sessionData[$oldCode]['items4_sum'] : array(),
                                'items5_sum' => isset($sessionData[$oldCode]['items5_sum']) ? $sessionData[$oldCode]['items5_sum'] : array(),
                                'items6_sum' => isset($sessionData[$oldCode]['items6_sum']) ? $sessionData[$oldCode]['items6_sum'] : array(),
                                'items7_sum' => isset($sessionData[$oldCode]['items7_sum']) ? $sessionData[$oldCode]['items7_sum'] : array(),
                                'items8_sum' => isset($sessionData[$oldCode]['items8_sum']) ? $sessionData[$oldCode]['items8_sum'] : array(),
                                'items9_sum' => isset($sessionData[$oldCode]['items9_sum']) ? $sessionData[$oldCode]['items9_sum'] : array(),
                                'items10_sum' => isset($sessionData[$oldCode]['items10_sum']) ? $sessionData[$oldCode]['items10_sum'] : array(),

                                "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                                //                            "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],
                                "tableIn_detail" => $rebuildGate['tableIn_detail'],
                                'tableIn_detail2_sum' => isset($sessionData[$oldCode]['tableIn_detail2_sum']) ? $sessionData[$oldCode]['tableIn_detail2_sum'] : array(),
                                "rsltItems" => $sessionData[$oldCode]['rsltItems'],
                                'rsltItems2' => isset($sessionData[$oldCode]['rsltItems2']) ? $sessionData[$oldCode]['rsltItems2'] : array(),
                                "tableIn_detail_rsltItems" => $sessionData[$oldCode]['tableIn_detail_rsltItems'],
                                'tableIn_detail_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_rsltItems2'] : array(),
                                "main_add_values" => $sessionData[$oldCode]['main_add_values'],
                                "main_add_fields" => $sessionData[$oldCode]['main_add_fields'],
                                "main_elements" => $sessionData[$oldCode]['main_elements'],
                                //
                                "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                                "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values_sum'],
                                "tableIn_detail_values_rsltItems" => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems'] : array(),
                                'tableIn_detail_values_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems2'] : array(),
                                'tableIn_detail_values2_sum' => isset($sessionData[$oldCode]['tableIn_detail_values2_sum']) ? $sessionData[$oldCode]['tableIn_detail_values2_sum'] : array(),

                                'itemSrc' => isset($sessionData[$oldCode]['itemSrc']) ? $sessionData[$oldCode]['itemSrc'] : array(),
                                'itemSrc_sum' => isset($sessionData[$oldCode]['itemSrc_sum']) ? $sessionData[$oldCode]['itemSrc_sum'] : array(),
                                'rsltItems3' => isset($sessionData[$oldCode]['rsltItems3']) ? $sessionData[$oldCode]['rsltItems3'] : array(),
                                "items_komposisi" => isset($sessionData[$oldCode]['items_komposisi']) ? $sessionData[$oldCode]['items_komposisi'] : array(),

                            );

                            //region unset sessio details2
                            //                //                        unset($sessionData[$cCode]['tableIn_detail_values2_sum']);
                            //                if (isset($clonerTransaction['resetGate']) && (sizeof($clonerTransaction['resetGate']) > 0)) {
                            //                    foreach ($clonerTransaction['resetGate'] as $gate) {
                            //                        unset($sessionData[$cCode][$gate]);
                            //                    }
                            //                }
                            //endregion

                            $masterReplacers = array(
                                //                        "referensi_id" => $masterID, (dimatikan)
                                "inv" => $tmpNomorNota,
                                "jenis_master" => $connectToStep,
                                "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                                "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "div_id" => "18",
                                "div_nama" => "default",
                                //                            "cabang_id"       => $sessionData[$oldCode]['tableIn_master']['cabang2_id'],
                                //                            "cabang_nama"     => $sessionData[$oldCode]['tableIn_master']['cabang2_nama'],
                                //                            "cabang2_id"      => $sessionData[$oldCode]['tableIn_master']['cabang_id'],
                                //                            "cabang2_nama"    => $sessionData[$oldCode]['tableIn_master']['cabang_nama'],
                                //                            "gudang_id"       => $sessionData[$oldCode]['tableIn_master']['gudang2_id'],
                                //                            "gudang_nama"     => $sessionData[$oldCode]['tableIn_master']['gudang2_nama'],
                                //                            "gudang2_id"      => $sessionData[$oldCode]['tableIn_master']['gudang_id'],
                                //                            "gudang2_nama"    => $sessionData[$oldCode]['tableIn_master']['gudang_nama'],

                                "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                                "step_current" => 1,
                                "step_number" => 1,
                                "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                                "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                                "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                                "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",

                            );
                            $masterReplacersO = array(
                                "jenisTr" => $connectToStep,
                                "div_id" => "18",
                                "jenisTrMaster" => $connectToStep,
                                "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                                "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                                //                            "placeID"         => $sessionData[$oldCode]['main']['place2ID'],
                                //                            "placeName"       => $sessionData[$oldCode]['main']['place2Name'],
                                //                            "place2ID"        => $sessionData[$oldCode]['main']['placeID'],
                                //                            "place2Name"      => $sessionData[$oldCode]['main']['placeName'],
                                //                            "cabangID"        => $sessionData[$oldCode]['main']['place2ID'],
                                //                            "cabangName"      => $sessionData[$oldCode]['main']['place2Name'],
                                //                            "cabang2ID"       => $sessionData[$oldCode]['main']['placeID'],
                                //                            "cabang2Name"     => $sessionData[$oldCode]['main']['placeName'],
                                //                            //
                                //                            "gudang2ID"       => $sessionData[$cCode]['main']['gudangID'],
                                //                            "gudang2Name"     => $sessionData[$cCode]['main']['gudangName'],
                                //                            "gudangID"        => $sessionData[$cCode]['main']['gudang2ID'],
                                //                            "gudangName"      => $sessionData[$cCode]['main']['gudang2Name'],
                            );
                        }
                        else {
                            //==replace pertama
                            $sessionData[$cCode] = array(
                                "main" => $sessionData[$oldCode]['main'],
                                "items" => $sessionData[$oldCode]['items'],
                                "items2" => $sessionData[$oldCode]['items2'],
                                "items2_sum" => $sessionData[$oldCode]['items2_sum'],
                                "items3" => $sessionData[$oldCode]['items3'],
                                "items3_sum" => $sessionData[$oldCode]['items3_sum'],
                                "items4_sum" => $sessionData[$oldCode]['items4_sum'],
                                "items_noapprove" => isset($sessionData[$oldCode]['items_noapprove']) ? $sessionData[$oldCode]['items_noapprove'] : array(),

                                "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                                "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],

                                "rsltItems" => $sessionData[$oldCode]['rsltItems'],
                                "tableIn_detail_rsltItems" => $sessionData[$oldCode]['tableIn_detail_rsltItems'],

                                "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                                "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values'],
                                "tableIn_detail_values_rsltItems" => $sessionData[$oldCode]['tableIn_detail_values_rsltItems'],
                            );
                            $masterReplacersO = array(
                                "jenisTr" => $connectToStep,
                                "jenisTrMaster" => $connectToStep,
                                "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                                "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                                "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $sessionData[$cCode]['main']['place2ID'],
                                "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$cCode]['main']['place2Name'],
                                "place2ID" => $sessionData[$cCode]['main']['placeID'],
                                "place2Name" => $sessionData[$cCode]['main']['placeName'],
                                "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$cCode]['main']['place2ID'],
                                "cabangName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$cCode]['main']['place2Name'],
                                "cabang2ID" => $sessionData[$cCode]['main']['placeID'],
                                "cabang2Name" => $sessionData[$cCode]['main']['placeName'],
                                //
                                "gudang2ID" => $sessionData[$cCode]['main']['gudangID'],
                                "gudang2Name" => $sessionData[$cCode]['main']['gudangName'],
                                "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['main']['gudang2ID'],
                                "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['main']['gudang2Name'],
                                "pihakID" => isset($sessionData[$cCode]['main']['placeID']) ? $sessionData[$cCode]['main']['placeID'] : "",
                                "pihakName" => isset($sessionData[$cCode]['main']['placeName']) ? $sessionData[$cCode]['main']['placeName'] : "",
                                "pihakName2" => $sessionData[$cCode]['main']['placeName'],
                                "gudang" => $sessionData[$cCode]['main']['gudangID'],
                                "gudang__name" => $sessionData[$cCode]['main']['gudangName'],
                                "gudang__label" => $sessionData[$cCode]['main']['gudangName'],
                                "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",

                            );
                            $masterReplacers = array(
                                //                    "referensi_id" => $masterID, (dimatikan)
                                "inv" => $tmpNomorNota,
                                "jenis_master" => $connectToStep,
                                "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                                "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$cCode]['tableIn_master']['cabang2_id'],
                                "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $sessionData[$cCode]['tableIn_master']['cabang2_nama'],
                                "cabang2_id" => $sessionData[$cCode]['tableIn_master']['cabang_id'],
                                "cabang2_nama" => $sessionData[$cCode]['tableIn_master']['cabang_nama'],
                                "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['tableIn_master']['gudang2_id'],
                                "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['tableIn_master']['gudang2_nama'],
                                "gudang2_id" => $sessionData[$cCode]['tableIn_master']['gudang_id'],
                                "gudang2_nama" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
                                "gudang" => $sessionData[$cCode]['tableIn_master']['gudang_id'],
                                "gudang__name" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
                                "gudang__label" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],

                                "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                                "step_current" => 1,
                                "step_number" => 1,
                                "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                                "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                                "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                                "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                                "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",

                            );
                        }


                        foreach ($masterReplacersO as $key => $val) {
                            $sessionData[$cCode]['main'][$key] = $val;
                        }
                        foreach ($masterReplacers as $key => $val) {
                            $sessionData[$cCode]['tableIn_master'][$key] = $val;
                        }


                        if (sizeof($connectToStepMainBuilder) > 0) {
                            foreach ($connectToStepMainBuilder as $r_key => $r_val) {
                                $sessionData[$cCode]['main'][$r_key] = isset($sessionData[$cCode]['main'][$r_val]) ? $sessionData[$cCode]['main'][$r_val] : "";
                            }
                        }

                        //region penomoran receipt #2
                        //<editor-fold desc="==========penomoran">
                        $this->load->model("CustomCounter");
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");

                        $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
                        if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
                            die(__LINE__ . " Used number should be registered in 'counters' config as well");
                        }

                        foreach ($counterForNumber as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                //                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                                //                    echo "filling $param with " . $sessionData[$cCode]['main'][$param] . "<br>";
                                $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                                //                    echo "filling $param with " . $sessionData[$cCode]['main'][$param] . "<br>";
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        }

                        $tmpNomorNota2 = $paramSpec['paramString'];
                        $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);


                        //</editor-fold>
                        //endregion

                        //region dynamic counters #2
                        // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");
                        $configCustomParams = $configCoreMasterModulJenis['counters'];
                        $configCustomParams[] = "stepCode";
                        if (sizeof($configCustomParams) > 0) {
                            $cContent = array();
                            foreach ($configCustomParams as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
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


                        $addValues = array(
                            'counters' => $appliedCounters2,
                            'counters_intext' => $appliedCounters_inText2,
                            'nomer' => $tmpNomorNota2,
                            'nomer_top' => $tmpNomorNota2,
                            'nomer2' => $tmpNomorNota2Alias,
                            'dtime' => date("Y-m-d H:i:s"),
                            'fulldate' => date("Y-m-d"),
                        );
                        foreach ($addValues as $key => $val) {
                            $sessionData[$cCode]['tableIn_master'][$key] = $val;
                        }

                        //===cloning nota cab1 ke cab2
                        //===daftar perbedaan
                        //== referensi_id, inv, jenis, nomer, counters, counters_inText, cabang_id, cabang_nama, cabang2_id, cabang2_nama,

                        //==replace kedua
                        $masterReplacers = array(
                            "nomer" => $tmpNomorNota2,
                            "nomer2" => $tmpNomorNota2Alias,
                            "counters" => $appliedCounters2,
                            "counters_intext" => $appliedCounters_inText2,
                        );
                        foreach ($masterReplacers as $key => $val) {
                            $sessionData[$cCode]['tableIn_master'][$key] = $val;
                        }


                        //===cloning detail/items cabang1 ke cabang2
                        //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                        $detailReplacers = array(
                            "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                            "sub_step_current" => 1,
                            "sub_step_number" => 1,
                            "next_substep_num" => $sessionData[$cCode]['tableIn_master']['next_step_num'],
                            "next_substep_code" => $sessionData[$cCode]['tableIn_master']['next_step_code'],
                            "next_substep_label" => $sessionData[$cCode]['tableIn_master']['next_step_label'],
                            "next_subgroup_code" => $sessionData[$cCode]['tableIn_master']['next_group_code'],
                        );
                        if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                            //                    cekmerah("tulis rincian transaksi kedua");
                            foreach ($sessionData[$cCode]['tableIn_detail'] as $k => $dSpec) {
                                foreach ($dSpec as $key => $val) {
                                    $sessionData[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                                }
                            }
                        }
                        else {
                            //                    cekmerah("GAGAL tulis rincian transaksi kedua");
                        }

                        //                    arrPrintPink($sessionData[$cCode]['tableIn_master']);
                        //                    arrPrintHijau($sessionData[$cCode]['tableIn_detail']);

                        //region ----------write transaksi & transaksi_data #2
                        if (isset($sessionData[$cCode]['tableIn_master']) && sizeof($sessionData[$cCode]['tableIn_master']) > 0) {

                            $tr = new MdlTransaksi();
                            $tr->addFilter("transaksi.cabang_id='" . $transaksi_current_cabang_id . "'");
                            $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                            cekUngu($this->db->last_query());
                            $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $sessionData[$cCode]['tableIn_master']);
                            $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                            $sessionData[$cCode]['main']['nomer'] = $insertNum;
                            $mongoListConnect['main'] = array($insertID, $epID);
                            cekmerah("tulis transaksi kedua :: trID $insertID");
                            cekmerah($this->db->last_query());
                            if ($insertID < 1) {
                                die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                            }
                        }
                        else {
                            cekmerah("GAGAL tulis transaksi kedua");
                        }
                        if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                            $inserMainValues = array();
                            foreach ($sessionData[$cCode]['tableIn_master_values'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] = $dd;
                                $mongoListConnect['mainValues'][] = $dd;
                            }
                            if (sizeof($inserMainValues) > 0) {
                                $arrBlob = blobEncode($inserMainValues);
                                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                            }
                        }
                        if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                            foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $mongoListConnect['mainValues'][] = $dd;
                            }
                        }
                        if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                            foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] = $dd;
                                $mongoListConnect['mainValues'][] = $dd;
                            }
                        }
                        if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                            //                    cekMerah("ada mainElements");
                            foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
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
                        if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                            $insertIDs = array();
                            $insertDeIDs = array();
                            foreach ($sessionData[$cCode]['tableIn_detail'] as $dSpec) {
                                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                showLast_query("ungu");
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
                                    cekOrange($this->db->last_query());
                                }
                            }
                        }
                        if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                            $insertIDs = array();
                            foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                $mongoListConnect['detail'] = $insertIDs;
                                if ($epID != 999) {
                                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                    $mongoListConnect['detail'] = $mongoListConnect['detail'] = $insertIDs;;
                                }
                            }
                        }
                        if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                            $insertIDs = array();
                            foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                                if (isset($this->configCoreJenis['tableIn']['detailValues'])) {
                                    foreach ($this->configCoreJenis['tableIn']['detailValues'] as $key => $src) {
                                        $dd = $tr->writeDetailValues($insertID, array(
                                            "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
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
                        if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                            foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                                if (isset($this->configCoreJenis['tableIn']['detailValues2_sum'])) {
                                    foreach ($this->configCoreJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                        $insertIDs[] = $tr->writeDetailValues($insertID, array(
                                            "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                            "produk_id" => $pID,
                                            "key" => $key,
                                            "value" => $dSpec[$src],
                                        ));

                                    }
                                }
                            }
                        }

                        //
                        //region nulis paymentSource
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
                                        "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $tmpNomorNota2,
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                        "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                        "oleh_id" => SYS_ID,
                                        "oleh_nama" => SYS_NAMA,
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                        "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                        "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                        "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                                        "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                        "terbayar_valas" => 0,
                                        "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
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
                                        "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $tmpNomorNota2,
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                        "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                        "oleh_id" => SYS_ID,
                                        "oleh_nama" => SYS_NAMA,
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

                        $idHis_decode = array();
                        $idHis_decode[1] = array(
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "olehID" => $sessionData[$cCode]['main']['olehID'],
                            "olehName" => $sessionData[$cCode]['main']['olehName'],
                            //                "step" => $stepNum,
                            "step" => 1,
                            "trID" => $insertID,
                            "nomer" => $tmpNomorNota2,
                            "nomer2" => $tmpNomorNota2Alias,
                            "counters" => $appliedCounters2,
                            "counters_intext" => $appliedCounters_inText2,
                        );
                        $idHis_blob = blobEncode($idHis_decode);
                        $idHis_intext = print_r($idHis_decode, true);

                        $sessionData[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                        $sessionData[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;

                        $tr = new MdlTransaksi();
                        $dupState = $tr->updateData(array("id" => $insertID), array(
                            "id_master" => $masterID,
                            "id_top" => $insertID,

                            "ids_his" => $idHis_blob,
                            "ids_his_intext" => $idHis_intext,

                        )) or die("Failed to update tr next-state!");


                        arrPrintPink($sessionData[$cCode]['tableIn_master']);// diganti manjadi gerbang uang muka
                        arrPrintHijau($sessionData[$cCode]['tableIn_detail']);// diganti manjadi gerbang uang muka


                        $baseRegistries = array(
                            'main' => isset($sessionData[$cCode]['main']) ? $sessionData[$cCode]['main'] : array(),
                            'items' => isset($sessionData[$cCode]['items']) ? $sessionData[$cCode]['items'] : array(),
                            'items2' => isset($sessionData[$cCode]['items2']) ? $sessionData[$cCode]['items2'] : array(),
                            'items2_sum' => isset($sessionData[$cCode]['items2_sum']) ? $sessionData[$cCode]['items2_sum'] : array(),
                            'itemSrc' => isset($sessionData[$cCode]['itemSrc']) ? $sessionData[$cCode]['itemSrc'] : array(),
                            'itemSrc_sum' => isset($sessionData[$cCode]['itemSrc_sum']) ? $sessionData[$cCode]['itemSrc_sum'] : array(),
                            'items3' => isset($sessionData[$cCode]['items3']) ? $sessionData[$cCode]['items3'] : array(),
                            'items3_sum' => isset($sessionData[$cCode]['items3_sum']) ? $sessionData[$cCode]['items3_sum'] : array(),
                            'items4' => isset($sessionData[$cCode]['items4']) ? $sessionData[$cCode]['items4'] : array(),
                            'items4_sum' => isset($sessionData[$cCode]['items4_sum']) ? $sessionData[$cCode]['items4_sum'] : array(),
                            'items5_sum' => isset($sessionData[$cCode]['items5_sum']) ? $sessionData[$cCode]['items5_sum'] : array(),
                            'items6_sum' => isset($sessionData[$cCode]['items6_sum']) ? $sessionData[$cCode]['items6_sum'] : array(),
                            'items7_sum' => isset($sessionData[$cCode]['items7_sum']) ? $sessionData[$cCode]['items7_sum'] : array(),
                            'items8_sum' => isset($sessionData[$cCode]['items8_sum']) ? $sessionData[$cCode]['items8_sum'] : array(),
                            'items9_sum' => isset($sessionData[$cCode]['items9_sum']) ? $sessionData[$cCode]['items9_sum'] : array(),
                            'items10_sum' => isset($sessionData[$cCode]['items10_sum']) ? $sessionData[$cCode]['items10_sum'] : array(),
                            'items_noapprove' => isset($sessionData[$cCode]['items_noapprove']) ? $sessionData[$cCode]['items_noapprove'] : array(),

                            'rsltItems' => isset($sessionData[$cCode]['rsltItems']) ? $sessionData[$cCode]['rsltItems'] : array(),
                            'rsltItems2' => isset($sessionData[$cCode]['rsltItems2']) ? $sessionData[$cCode]['rsltItems2'] : array(),
                            'rsltItems3' => isset($sessionData[$cCode]['rsltItems3']) ? $sessionData[$cCode]['rsltItems3'] : array(),

                            'tableIn_master' => isset($sessionData[$cCode]['tableIn_master']) ? $sessionData[$cCode]['tableIn_master'] : array(),
                            'tableIn_detail' => isset($sessionData[$cCode]['tableIn_detail']) ? $sessionData[$cCode]['tableIn_detail'] : array(),
                            'tableIn_detail2_sum' => isset($sessionData[$cCode]['tableIn_detail2_sum']) ? $sessionData[$cCode]['tableIn_detail2_sum'] : array(),
                            'tableIn_detail_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_rsltItems'] : array(),
                            'tableIn_detail_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                            'tableIn_master_values' => isset($sessionData[$cCode]['tableIn_master_values']) ? $sessionData[$cCode]['tableIn_master_values'] : array(),
                            'tableIn_detail_values' => isset($sessionData[$cCode]['tableIn_detail_values']) ? $sessionData[$cCode]['tableIn_detail_values'] : array(),
                            'tableIn_detail_values_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                            'tableIn_detail_values_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                            'tableIn_detail_values2_sum' => isset($sessionData[$cCode]['tableIn_detail_values2_sum']) ? $sessionData[$cCode]['tableIn_detail_values2_sum'] : array(),
                            'main_add_values' => isset($sessionData[$cCode]['main_add_values']) ? $sessionData[$cCode]['main_add_values'] : array(),
                            'main_add_fields' => isset($sessionData[$cCode]['main_add_fields']) ? $sessionData[$cCode]['main_add_fields'] : array(),
                            'main_elements' => isset($sessionData[$cCode]['main_elements']) ? $sessionData[$cCode]['main_elements'] : array(),
                            'main_inputs' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                            'main_inputs_orig' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                            "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                            "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                            "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                            "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                            "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                            "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                            "componentsBuilder" => isset($sessionData[$cCode]['componentsBuilder']) ? $sessionData[$cCode]['componentsBuilder'] : array(),
                            "jurnalItems" => isset($sessionData[$cCode]['jurnalItems']) ? $sessionData[$cCode]['jurnalItems'] : array(),
                            "jurnal_index" => isset($sessionData[$cCode]['jurnal_index']) ? $sessionData[$cCode]['jurnal_index'] : array(),
                            "postProcessor" => isset($sessionData[$cCode]['postProcessor']) ? $sessionData[$cCode]['postProcessor'] : array(),
                            "preProcessor" => isset($sessionData[$cCode]['preProcessor']) ? $sessionData[$cCode]['preProcessor'] : array(),
                            "revert" => isset($sessionData[$cCode]['revert']) ? $sessionData[$cCode]['revert'] : array(),

                        );
                        $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                        showLast_query("biru");
                        $mongRegIDConnect = $doWriteReg;
                        //endregion


                        //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                        $this->load->model("Mdls/MdlLockerTransaksi");
                        $lt = New MdlLockerTransaksi();
                        $lt->execLocker($sessionData[$cCode]['main'], $nextProp['num'], NULL, $insertID);
                    }
                    else {
                        cekHitam("tidak ditulis");
                    }
                    matiHEre();
                    cekMerah("to be connected by Step to $connectToStepByOption |$stepNum|");
                    cekMerah("NOW CONNECTING to $connectToStep");

                    //                mati_disini();
                    //==================================================================================================

                }
                //endregion --Bagian connectToStep, membuat koneksi ke cabang berdasarkan step

                //region Com pre purchase
                //split to Pre Pre Purchase Supplies dari Request Cabang untuk stok yang tidak ada/kurang diPusat.
                //sekaligus check jika valid_qty sudah nol maka update next_substep_code transaksi bayangannya.
                //dimatikan sementara untuk TEST
                $iterator = isset($this->configUiJenis['comPrePurchase'][1]['detail']) ? $this->configUiJenis['comPrePurchase'][1]['detail'] : array();
                $aliasMainTrans = isset($this->configUiJenis['aliasMainTrans']) ? $this->configUiJenis['aliasMainTrans'] : 999;
                if (sizeof($iterator) > 0) {
                    //            matiHere();
                    $tmp = array();
                    $this->load->model("MdlTransaksi");
                    $l = new MdlTransaksi();
                    $l->setFilters(array());
                    $l->addFilter("transaksi.link_id=0");
                    $l->addFilterJoin("transaksi_data.valid_qty>0");
                    $l->addFilterJoin("transaksi_data.next_substep_code='" . $aliasMainTrans . "'");
                    $l->addFilterJoin("transaksi_data.produk_id in (" . implode(",", array_keys($sessionData[$cCode]['items'])) . ")");
                    $tmp = $l->lookupJoined();
                    //            cekHitam($this->db->last_query());
                    //            arrPrint( implode(",",array_keys($sessionData[$cCode]['items'])));
                    //matiHEre(sizeof($tmp). " iki ".__LINE__);
                    if (sizeof($tmp) > 0) {
                        //                matiHEre("masukk");
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];

                            echo "post-processor: $comName<br>LINE: " . __LINE__;
                            $dSpec = $sessionData[$cCode][$srcGateName];
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    $tmpOutParams['loop'][$key] = $value;
                                }
                            }
                            if (sizeof($dSpec) > 0) {
                                foreach ($dSpec as $pid => $arrValue) {
                                    $tmpOutParams[$srcGateName][$pid] = $arrValue['jml'];
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                                    $tmpOutParams['static'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static']["jenis"])) {
                                    $tmpOutParams['static']["jenis"] = $aliasMainTrans;
                                }
                                if (!isset($tmpOutParams['static']["jenis_master"])) {
                                    $tmpOutParams['static']["jenis_master"] = $this->jenisTr;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                }
                                $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                            }
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                    }

                }
                else {
                    cekHere("LINE: " . __LINE__ . " || jenis: " . $this->jenisTr . " Tidak masuk Iterator PRE PURCHASE SUPPLIES");
                }

                //endregion Com pre purchase

            }

            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $sessionData[$cCode]['main']['jenisTrName'],
                "sub_title" => "Saving new transaction",
                "uid" => SYS_ID,
                "uname" => SYS_NAMA,
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => base64_encode(serialize($sessionData[$cCode])),
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


//            cekKuning(":: mulai cek rek besar dan rek pembantu");
            $cabangID_validate = $transaksi_current_cabang_id;
//            validateAllBalances();

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                // cek ulang sesi bookingnumber masih ada atau hilang atau kosong... bila hilang/kosong maka hentikan.
                if (!isset($sessionData[$cCode]["main"]["bookingNumber"])) {
                    $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                    mati_disini($msg);
                }
                elseif ($sessionData[$cCode]["main"]["bookingNumber"] == null) {
                    $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                    mati_disini($msg);
                }
                if ($nomerBookingTransaksi != $sessionData[$cCode]["main"]["bookingNumber"]) {
                    $msg = "Nomer/Kode Booking transaksi baru belum terdaftar. Silahkan referesh halaman ini. code: " . __LINE__;
                    mati_disini($msg);
                }
            }

//            arrPrintCyan($sessionData[$cCode]);

            return true;
        }
        else {
            mati_disini("the gate index you want to debug has not been formed yet!");
        }
    }

    public function generateInvoicing()
    {
//        mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID:.... ");
//        header("refresh:2");

//        if (!isset($this->session->login)) {
//            mati_disini("SETOP... " . __LINE__);
//        }
//        if ($this->session->login['id'] == CB_ID_PUSAT) {
//            mati_disini("SETOP... HARUS LOGIN CABANG " . __LINE__);
//        }
//        if ($this->session->login['id'] == 0) {
//            mati_disini("SETOP... HARUS LOGIN CABANG " . __LINE__);
//        }

        $start = microtime(true);
        $this->load->helper("he_session_replacer");
        $this->load->model("MdlTransaksi");
        $jenisTr = $this->jenisTr = "4822";
        $batas_tanggal = "2024-02-18";
        $batas_tanggal = "2025-12-20";
        $cCode = "_TR_" . $this->jenisTr;
        $sessionData = array();
        if (isset($sessionData[$cCode])) {
            $sessionData[$cCode] = null;
            unset($sessionData[$cCode]);
        }

        $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
        $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
        $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
        $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");


        $this->db->trans_start();

        $tr = new MdlTransaksi();
//        $tr->addFilter("transaksi.status_inv='0'");
//        $tr->addFilter("transaksi.fulldate>='$batas_tanggal'");
//        $tr->addFilter("transaksi.cabang_id>'0'");
//        $this->db->limit(1);
//        $this->db->order_by("transaksi.id", "ASC");
//        $this->db->group_start();

        $where_1 = "transaksi.id='661725'";
//        $where_1 = "transaksi.jenis='5822spd' and transaksi.pembayaran in ('credit','cod')";
//        $this->db->group_start();
        $this->db->where($where_1);
//        $this->db->group_end();

//        $where_2 = "transaksi.jenis='4464'";
//        $where_3 = "transaksi.jenis='7499'";// termin
//        $this->db->or_where($where_2);
//        $this->db->or_where($where_3);
//        $this->db->group_end();

//        $this->db->group_start();
//        $this->db->where(
//            array(
//                "transaksi.jenis" => "5822spd",
//                "transaksi.pembayaran" => "credit",
//            )
//        );
//        $this->db->group_end();
//        $this->db->or_where(
//            array(
//                "transaksi.jenis" => "4464"
//            )
//        );
//        $this->db->group_end();


//        $sesionReplacer = replaceSession();
        $sesionReplacer = array(//            "cabang_id" => ">0",
        );
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
//        arrPrintPink($tmpHist);
//        mati_disini(__LINE__);


        if (sizeof($tmpHist) > 0) {
            $transaksi_id = $tmpHist[0]->transaksi_id;
            $dtime = $tmpHist[0]->dtime;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $cabang_id = $tmpHist[0]->cabang_id;
            $gudang_id = $tmpHist[0]->gudang_id;
            cekHitam("[trid: $transaksi_id] [dtime: $dtime] [jenisTr: $jenisTransaksi] [cb: $cabang_id] [gd: $gudang_id]");


            if ($cabang_id == CB_ID_PUSAT) {
                mati_disini("SETOP... HARUS LOGIN CABANG " . __LINE__);
            }
            if ($cabang_id == 0) {
                mati_disini("SETOP... HARUS LOGIN CABANG " . __LINE__);
            }
//            mati_disini("SETOP... DULU... " . __LINE__);

            $arrKiriman = array(
                "transaksi_id" => $transaksi_id,
                "step_number" => 1,
                "currentStepNumber" => 1,
                "jenisTr" => $jenisTr,
                "uiJenis" => $configUiMasterModulJenis,
                "coreJenis" => $configCoreMasterModulJenis,
                "layoutJenis" => $configLayoutMasterModulJenis,
                "valuesJenis" => $configValuesMasterModulJenis,
                "dtime" => $tmpHist[0]->dtime,
                "fulldate" => $tmpHist[0]->fulldate,
            );
            $sessionData = $this->followupPrePreviewInvoicing($arrKiriman);
//            arrPrint($sessionData);


            cekHijau("MULAI EKSEKUTOR SAVE");
            $this->save($sessionData, $arrKiriman);
//            mati_disini(__LINE__);

            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $transaksi_id,
            );
            $data = array(
                "status_inv" => 1,
            );
            $tr->updateData($where, $data);
            showLast_query("orange");

        }


        $end = microtime(true);
        $selisih = $end - $start;

//        mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID:.... [$selisih]");


        if (isset($sessionData[$cCode])) {
            unset($sessionData[$cCode]);
        }
        if (isset($oldCode)) {
            if (isset($sessionData[$oldCode])) {
                unset($sessionData[$oldCode]);
            }
        }

//matiHere(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI... [$selisih]</h3>");


    }


}

?>