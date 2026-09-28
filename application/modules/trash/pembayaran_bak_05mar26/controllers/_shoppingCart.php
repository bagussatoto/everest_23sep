<?php

require_once "Modul_Controller.php";

class _shoppingCart extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("MobileDetect");

    }

    public function viewCart()
    {

        $cCode = $this->cCode;
        $this->load->helper('he_angka');

        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array("main" => array(),
                "items" => array(),
            );
        }


        if (isset($_SESSION[$cCode]['mode']['edit']) && sizeof($_SESSION[$cCode]['mode']['edit']) > 0) {
            $stepNumberTemp = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
            $stepNumber = $stepNumberTemp > 1 ? $stepNumberTemp - 1 : $stepNumberTemp;
        }
        else {
            if (isset($_SESSION[$cCode]['mode']['cancel']) && sizeof($_SESSION[$cCode]['mode']['cancel']) > 0) {
                $stepNumberTemp = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
                $stepNumber = $stepNumberTemp > 1 ? $stepNumberTemp - 1 : $stepNumberTemp;
            }
            else {
                $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
            }
        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();


        $inputLabels = array();

        $main = array();
        $items = array();
        $items2 = array();
        $items3 = array();
        $minValue = array();
        $itemLabels = array();
        $shoppingCartFieldSrc = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $itemLabels = isset($this->configUi[$this->jenisTr]['shoppingCartFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFields'][$stepNumber] : array();
        $itemLabels2 = isset($this->configUi[$this->jenisTr]['shoppingCartFields2'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFields2'][$stepNumber] : array();
        $itemsLabelReplacer = isset($this->configUi[$this->jenisTr]['shopingCartFieldsReplacer']) ? $this->configUi[$this->jenisTr]['shopingCartFieldsReplacer'] : array();

        if (isset($_SESSION[$cCode]['main']['references']) || isset($_SESSION[$cCode]['main']['singleReference'])) {
            $itemLabels = isset($this->configUi[$this->jenisTr]['shoppingCartFieldsExt'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFieldsExt'][$stepNumber] : $itemLabels;
        }

        $shoppingCartFieldsSubItems = isset($this->configUi[$this->jenisTr]['shoppingCartFieldsSubItems'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFieldsSubItems'][$stepNumber] : array();
        $itemLabels3 = isset($this->configUi[$this->jenisTr]['shoppingCartFields3'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFields3'][$stepNumber] : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$stepNumber] : array();
        $itemNumLabels2 = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][$stepNumber] : array();
        $itemNumLabels3 = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields3'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields3'][$stepNumber] : array();
        $editableFields = isset($this->configUi[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber] : array();
        $editableFields2 = isset($this->configUi[$this->jenisTr]['shoppingCartEditableFields2'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableFields2'][$stepNumber] : array();
        $shoppingCartEditableFieldsType = isset($this->configUi[$this->jenisTr]['shoppingCartEditableFieldsType'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableFieldsType'][$stepNumber] : array();
        $editableFieldsCompare = isset($this->configUi[$this->jenisTr]['shopingCartEditableCompare'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shopingCartEditableCompare'][$stepNumber] : array();

        $shoppingCartMainEditableFields = isset($this->configUi[$this->jenisTr]['shoppingCartMainEditableFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartMainEditableFields'][$stepNumber] : array();
        $shoppingCartAmountValue = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$stepNumber] : array();

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $elementItemsAutoConfigs = isset($this->configUi[$this->jenisTr]['receiptElementsItemsAuto']) ? $this->configUi[$this->jenisTr]['receiptElementsItemsAuto'] : array();
        $arrHeaderElement = isset($this->configUi[$this->jenisTr]['shoppingCartHeaderElement'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartHeaderElement'][$stepNumber] : array();
        $relElementConfigs = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $addRowsConfigs = isset($this->configUi[$this->jenisTr]['additionalRows']) ? $this->configUi[$this->jenisTr]['additionalRows'] : array();
        $editHandlerMethod = isset($this->configUi[$this->jenisTr]['editHandlerMethod']) ? $this->configUi[$this->jenisTr]['editHandlerMethod'] : "blabla";
        $editHandlerMethod2 = isset($this->configUi[$this->jenisTr]['editHandlerMethod2']) ? $this->configUi[$this->jenisTr]['editHandlerMethod2'] : "";

        $editMainHandlerMethod = isset($this->configUi[$this->jenisTr]['editMainHandlerMethod']) ? $this->configUi[$this->jenisTr]['editMainHandlerMethod'] : NULL;
        $editMainHandlerMethod2 = isset($this->configUi[$this->jenisTr]['editMainHandlerMethod2']) ? $this->configUi[$this->jenisTr]['editMainHandlerMethod2'] : NULL;

        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
        $noteType = isset($this->configUi[$this->jenisTr]['shoppingCartNoteType']) ? $this->configUi[$this->jenisTr]['shoppingCartNoteType'] : "text";
        $imageEnable = isset($this->configUi[$this->jenisTr]['shoppingCartImageEnabled']) ? $this->configUi[$this->jenisTr]['shoppingCartImageEnabled'] : false;
        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;
        $imageType = isset($this->configUi[$this->jenisTr]['shoppingCartImageType']) ? $this->configUi[$this->jenisTr]['shoppingCartImageType'] : "blaa";

        $pairedItemEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartPairedItem']['enabled']) && $this->configUi[$this->jenisTr]['shoppingCartPairedItem']['enabled'] == true ? true : false;
        $pairedItem = isset($this->configUi[$this->jenisTr]['shoppingCartPairedItem']) ? $this->configUi[$this->jenisTr]['shoppingCartPairedItem'] : array();
        $pairedItemTarget = isset($this->configUi[$this->jenisTr]['shoppingCartPairedItem']['targetGateName']) ? $this->configUi[$this->jenisTr]['shoppingCartPairedItem']['targetGateName'] : "items2";

        $pairedItemField = isset($this->configUi[$this->jenisTr]['shoppingCartFieldsPairedItem'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFieldsPairedItem'][$stepNumber] : array();
        $pairedItemRecorder = isset($this->configUi[$this->jenisTr]['shoppingCartPairedItemRecorder']) ? $this->configUi[$this->jenisTr]['shoppingCartPairedItemRecorder'] : "";

        $pairedMoq = isset($this->configUi[$this->jenisTr]['shoppingCartMinFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartMinFields'][$stepNumber] : array();
        $avoidRemove = isset($this->configUi[$this->jenisTr]['shoppingCartAvoidRemove']) ? $this->configUi[$this->jenisTr]['shoppingCartAvoidRemove'] : false;
        $tmpSaver = isset($this->configUi[$this->jenisTr]['tmpSave']) ? $this->configUi[$this->jenisTr]['tmpSave'] : array();
        $reqDiskon = isset($this->configUi[$this->jenisTr]['reqDiskon']) ? $this->configUi[$this->jenisTr]['reqDiskon'] : array();

        $unionSelectors = isset($this->configUi[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber] : array();
        $keyUpEvents = isset($this->configUi[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber] : array();
        $shoppingCartEditableFieldsOptions = isset($this->configUi[$this->jenisTr]['shoppingCartEditableFieldsOptions'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableFieldsOptions'][$stepNumber] : array();
        $selectedPrices = isset($this->configUi[$this->jenisTr]['selectedPrice']['key_label']) ? $this->configUi[$this->jenisTr]['selectedPrice']['key_label'] : array();
        $showItems = isset($this->configUi[$this->jenisTr]['showItems']) && $this->configUi[$this->jenisTr]['showItems'] == "false" ? "false" : "true";

        $fixedNote = isset($this->configLayout[$this->jenisTr]['fixedNote']) ? $this->configLayout[$this->jenisTr]['fixedNote'] : null;
        $fixedNoteTop = isset($this->configLayout[$this->jenisTr]['fixedNoteTop']) ? $this->configLayout[$this->jenisTr]['fixedNoteTop'] : null;
        $fixedNoteLink = isset($this->configLayout[$this->jenisTr]['fixedNoteLink']) ? $this->configLayout[$this->jenisTr]['fixedNoteLink'] : null;
        $shopingCartParamForceEditable = isset($this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber] : array();
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $shopingCartReload = isset($this->configUi[$this->jenisTr]['shopingCartReload']) ? $this->configUi[$this->jenisTr]['shopingCartReload'] : false;
        $shopingCartPairProdukSrc = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukSrc']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukSrc'] : array();
        //----------------------------------------------
        $shoppingCartMode = isset($this->configUi[$this->jenisTr]['mode']) ? $this->configUi[$this->jenisTr]['mode'] : NULL;
        //----------------------------------------------
        $isSettlement = isset($this->configUi[$this->jenisTr]['isSettlement']) ? $this->configUi[$this->jenisTr]['isSettlement'] : false;
        $shopingCartFaktur = isset($this->configUi[$this->jenisTr]['addMainSource'][1]) ? $this->configUi[$this->jenisTr]['addMainSource'][1] : array();
        $shopingCartFakturInsentif = isset($this->configUi[$this->jenisTr]['addMainSourceInsentif'][1]) ? $this->configUi[$this->jenisTr]['addMainSourceInsentif'][1] : array();
        $pairPajakData = $this->config->item("pairPajak");
        $pairPajakValueDefault = $pairPajakData[my_jenis_usaha()]['value']['default'];
        //----------------------------------------------
        $shopingCartPaymentItemsColorEnabled = isset($this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']['enabled']) ? $this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']['enabled'] : false;
        $shopingCartPaymentItemsColor = isset($this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']) ? $this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor'] : array();
        $shopingCartEmbededTaxItem = isset($this->configUi[$this->jenisTr]['shopingCartEmbededTaxItem']) ? $this->configUi[$this->jenisTr]['shopingCartEmbededTaxItem'] : array();
        //----------------------------------------------

        if ($editMainHandlerMethod != NULL) {
            if (isset($_SESSION[$cCode]['main']['pihakMainName'])) {
                $editHandlerMethod = isset($editMainHandlerMethod[$_SESSION[$cCode]['main']['pihakMainName']]) ? $editMainHandlerMethod[$_SESSION[$cCode]['main']['pihakMainName']] : "edit";
            }
        }
        if ($editMainHandlerMethod2 != NULL) {
            if (isset($_SESSION[$cCode]['main']['pihakMainName'])) {
                $editHandlerMethod2 = isset($editMainHandlerMethod2[$_SESSION[$cCode]['main']['pihakMainName']]) ? $editMainHandlerMethod2[$_SESSION[$cCode]['main']['pihakMainName']] : "edit";
            }
        }

        if (sizeof($shoppingCartMainEditableFields) > 0) {
            if (isset($_SESSION[$cCode]['main']['pihakMainName'])) {

                if (isset($shoppingCartMainEditableFields[$_SESSION[$cCode]['main']['pihakMainName']]) && sizeof($shoppingCartMainEditableFields[$_SESSION[$cCode]['main']['pihakMainName']]) > 0) {
                    $editableFields = $shoppingCartMainEditableFields[$_SESSION[$cCode]['main']['pihakMainName']];
                }
            }
        }

        $arrItemTidakDibayar = array();
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items'])) {
                $no = 0;
                $sesItems = $_SESSION[$cCode]['items'];
                $sesItemsReferencePO = array();
                $sesItemsReferenceGRN_PO = array();
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $pihakID = $id;
                    $no++;
                    $pihakName = isset($sesMains['pihakName']) ? $sesMains['pihakName'] : "";
                    $tmp = array(//                        "id"     => $iSpec['id'],
                        "id" => $id,
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "",
                        "nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "kode" => isset($iSpec['kode']) ? "'" . strval($iSpec['kode']) : "-",
                        "code" => isset($iSpec['code']) ? "'" . strval($iSpec['code']) : "-",
                        "lokasi_nama" => isset($iSpec['lokasi_nama']) ? $iSpec['lokasi_nama'] : "-",
                        "merek_nama" => isset($iSpec['merek_nama']) ? $iSpec['merek_nama'] : "-",
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                        "jml" => $iSpec['jml'],
                        "stok_all" => isset($iSpec['stok_all']) ? $iSpec['stok_all'] : 0,
                        "moq" => isset($iSpec['moq']) ? $iSpec['moq'] : 0,
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "",
                        // -----------
                        "sub_berat_gross" => isset($iSpec['sub_berat_gross']) ? $iSpec['sub_berat_gross'] : "",
                        "sub_lebar_gross" => isset($iSpec['sub_lebar_gross']) ? $iSpec['sub_lebar_gross'] : "",
                        // -----------
                        "sub_panjang_gross" => isset($iSpec['sub_panjang_gross']) ? $iSpec['sub_panjang_gross'] : "",
                        "sub_tinggi_gross" => isset($iSpec['sub_tinggi_gross']) ? $iSpec['sub_tinggi_gross'] : "",
                        "sub_volume_gross" => isset($iSpec['sub_volume_gross']) ? $iSpec['sub_volume_gross'] : "",
                        // -----------
                        "sub_volume" => isset($iSpec['sub_volume']) ? $iSpec['sub_volume'] : "",
                        "sub_berat" => isset($iSpec['sub_berat']) ? $iSpec['sub_berat'] : "",
                        "request_jml" => isset($iSpec['request_jml']) ? $iSpec['request_jml'] : 0,
                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "0",
                        "stok_hold" => isset($iSpec['stok_hold']) ? $iSpec['stok_hold'] : "0",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "stok_avail" => isset($iSpec['stok_avail']) ? $iSpec['stok_avail'] : 0,
                        "stok_rekening" => isset($iSpec['stok_rekening']) ? $iSpec['stok_rekening'] : 0,
                        "sent_jml" => isset($iSpec['sent_jml']) ? $iSpec['sent_jml'] : "",
                        "packed_jml" => isset($iSpec['packed_jml']) ? $iSpec['packed_jml'] : "",
                        "cancel_jml" => isset($iSpec['cancel_jml']) ? $iSpec['cancel_jml'] : "",
                        "req_cancel_jml" => isset($iSpec['req_cancel_jml']) ? $iSpec['req_cancel_jml'] : "",
                        "cancel_qty" => isset($iSpec['cancel_qty']) ? $iSpec['cancel_qty'] : "",
                        "req_cancel_qty" => isset($iSpec['req_cancel_qty']) ? $iSpec['req_cancel_qty'] : "",
                        "max_jml" => isset($iSpec['max_jml']) ? $iSpec['max_jml'] : "",
                        "outstanding" => isset($iSpec['outstanding']) ? $iSpec['outstanding'] : 0,
                        "ppn_persen" => isset($iSpec['ppn_persen']) ? $iSpec['ppn_persen'] : 0,
                        "dpp_ppn" => isset($iSpec['dpp_ppn']) ? $iSpec['dpp_ppn'] : 0,
                        "ppn_sisa" => isset($iSpec['ppn_sisa']) ? $iSpec['ppn_sisa'] : 0,
                        "ppn_approved" => isset($iSpec['ppn_approved']) ? $iSpec['ppn_approved'] : 0,
                        "merk" => isset($iSpec['merk']) ? $iSpec['merk'] : "",
                        "serial_no" => isset($iSpec['serial_no']) ? $iSpec['serial_no'] : "",
                        "extern2_nama" => isset($iSpec['extern2_nama']) ? $iSpec['extern2_nama'] : "",
                        "valas_nama" => isset($iSpec['valas_nama']) ? $iSpec['valas_nama'] : "",
                        "valas_nilai" => isset($iSpec['valas_nilai']) ? $iSpec['valas_nilai'] : "",
                        "koreksi" => isset($iSpec['koreksi']) ? $iSpec['koreksi'] : 0,
                        "extern_nilai2" => isset($iSpec['extern_nilai2']) ? $iSpec['extern_nilai2'] : 0,

                    );
                    if ($noteEnabled) {
                        $tmp['note'] = isset($iSpec['note']) ? $iSpec['note'] : "";
                    }
                    if ($imageEnable) {
                        $tmp['images'] = isset($iSpec['images']) ? $iSpec['images'] : "";
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            if (!isset($main[$key])) {
                                $main[$key] = 0;
                            }
                            $main[$key] += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;

                        }

                    }

                    if (sizeof($pairedMoq) > 0) {
                        foreach ($pairedMoq as $key => $label) {
                            $minValue[$key][$iSpec['id']] = isset($iSpec[$key]) ? $iSpec[$key] : 1;
                        }
                    }
                    if (sizeof($fieldSrcs) > 0) {
                        foreach ($fieldSrcs as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                    }

                    //---------------
                    if ($shopingCartPaymentItemsColorEnabled == true) {
//                        $sisa_bulat = reformatExponent($iSpec['new_sisa']);
//                        cekMerah($sisa_bulat);
                        if ($iSpec['new_sisa'] > 1) {
//                        if ($sisa_bulat > 1) {

                            if (isset($iSpec['nilai_bayar']) && ($iSpec['nilai_bayar'] > 0)) {
                                // dibayar, tidak lunas
                                $tmp['background_pembayaran'] = $shopingCartPaymentItemsColor['colorCode'][1]['color'];
                            }
                            elseif (isset($iSpec['nilai_bayar']) && ($iSpec['nilai_bayar'] == 0)) {
                                // tidak dibayar
                                $tmp['background_pembayaran'] = $shopingCartPaymentItemsColor['colorCode'][2]['color'];
                                $arrItemTidakDibayar[$id] = $id;
                            }
                            else {
                                // tidak jelas
//                                $tmp['background_pembayaran'] = "";
                                $tmp['background_pembayaran'] = $shopingCartPaymentItemsColor['colorCode'][2]['color'];
                                $arrItemTidakDibayar[$id] = $id;
                            }
                        }
                        else {
                            // lunas
                            $tmp['background_pembayaran'] = $shopingCartPaymentItemsColor['colorCode'][0]['color'];
                        }
                    }
                    //---------------

                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;
                    $tmp['sub_harga'] = isset($iSpec['sub_harga']) ? $iSpec['sub_harga'] : 0;

                    $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=$id&transaksi_id=$id&newQty=";
                    $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/remove/" . $this->jenisTr . "?id=$id";

                    $items[] = $tmp;
                    switch ($this->jenisTr) {
                        case "1487":
                            //hutang bpjs perlu ngeluarin data rincian bpjs yang akan dibayar
                            $sesItemsReferencePO[$id] = $id;
                            break;
                        default:
                            $sesItemsReferencePO[$iSpec["transaksi_ref_id_po"]] = $iSpec["transaksi_ref_id_po"];
                            break;
                    }

                    $sesItemsReferenceGRN_PO[$iSpec["transaksi_ref_id_po"]] = $id;
                }
            }
        }
        $shopingCartRebate = isset($this->configUi[$this->jenisTr]['shopingCartRebate']) ? $this->configUi[$this->jenisTr]['shopingCartRebate'] : array();

        //item2 sum
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items2_sum'])) {
                $items2_sum_kurang = array();
                $no = 0;
                $valItem = 0;
                foreach ($_SESSION[$cCode]['items2_sum'] as $iSpec) {
                    $sisa = isset($iSpec['stok']) && isset($iSpec['jml']) ? $iSpec['stok'] - $iSpec['jml'] : 0;

                    $no++;
                    $tmp = array("id" => $iSpec['id'],
                        "nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "",
                        "jml" => isset($iSpec['jml']) ? $iSpec['jml'] : 0,
                        "jual" => isset($iSpec['jual']) ? $iSpec['jual'] : 0,
                        "disc_value" => isset($iSpec['disc_value']) ? $iSpec['disc_value'] : 0,
                        "disc_persent" => isset($iSpec['disc_persent']) ? $iSpec['disc_persent'] : 0,
                        "subtotal" => isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0,
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : isset($iSpec['kode']) ? $iSpec['kode'] : "",
                        // --
                        "kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "",
                        "referensi" => isset($iSpec['pihakName']) ? $iSpec['pihakName'] : "",
                        "harga_ori" => isset($iSpec['harga_ori']) ? $iSpec['harga_ori'] : "",
                        "harga" => isset($iSpec['harga']) ? $iSpec['harga'] : "",
                        "harga2" => isset($iSpec['harga']) ? $iSpec['harga'] : "",
                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        //                        "sisa" => isset($iSpec['stok']) && isset($iSpec['jml']) ? $iSpec['stok'] - $iSpec['jml'] : "",
                        "sisa" => isset($sisa) ? $sisa : 0,
                        "produk_id" => isset($iSpec['id']) ? $iSpec['id'] : "",
                        "qty" => isset($iSpec['jml']) ? $iSpec['jml'] : "",
                        "produk_nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "produk_ord_jml" => isset($iSpec['jml']) ? $iSpec['jml'] : 0,
                        "produk_ord_hrg" => isset($iSpec['jual']) ? $iSpec['jual'] : "",
                        //                        "produk_kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "",
                        "produk_label" => isset($iSpec['label']) ? $iSpec['label'] : "",
                        "harga_last" => isset($iSpec['harga_last']) ? $iSpec['harga_last'] : "",
                        "sub_harga_last" => isset($iSpec['sub_harga_last']) ? $iSpec['sub_harga_last'] : "",
                    );
                    if ($noteEnabled) {
                        $tmp['note'] = isset($iSpec['note']) ? $iSpec['note'] : "";
                    }
                    if ($imageEnable) {
                        $tmp['images'] = isset($iSpec['images']) ? $iSpec['images'] : "";
                    }
                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            if (!isset($main[$key])) {
                                $main[$key] = 0;
                            }
                            //                            cekHitam($key);
                            $valItem += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;
                            $main[$key] = $valItem;
                            //                            $main[$key] += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;

                        }
                    }
                    if (isset($iSpec['subtotal'])) {
                        $tmp['subtotal'] = $iSpec['subtotal'];
                    }
                    if (isset($iSpec['sub_harga'])) {
                        $tmp['sub_harga'] = $iSpec['sub_harga'];
                    }

                    if (isset($editHandlerMethod2)) {
                        if (isset($iSpec['handler'])) {
                            $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/" . $editHandlerMethod2 . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                            $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $id;
                        }
                        else {
                            $tmp["editTarget"] = "";
                            $tmp["removeTarget"] = "";
                        }
                    }
                    else {
                        $tmp["editTarget"] = "";
                        $tmp["removeTarget"] = "";
                    }
                    if (sizeof($fieldSrcs) > 0) {
                        foreach ($fieldSrcs as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                    }


                    $items2[] = $tmp;


                }
            }
        }

        //item3 sum
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items3_sum'])) {
                $no = 0;
                foreach ($_SESSION[$cCode]['items3_sum'] as $iSpec) {
                    $no++;
                    $tmp = array("id" => $iSpec['id'],
                        "nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "",
                        "jml" => isset($iSpec['jml']) ? $iSpec['jml'] : 0,
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "",
                        "referensi" => isset($iSpec['pihakName']) ? $iSpec['pihakName'] : "",
                        "harga" => isset($iSpec['harga']) ? $iSpec['harga'] : "",
                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "sisa" => isset($iSpec['stok']) && isset($iSpec['jml']) ? $iSpec['stok'] - $iSpec['jml'] : "",
                        "sub_nilai" => isset($iSpec['sub_nilai']) ? $iSpec['sub_nilai'] : 0,
                    );
                    if ($noteEnabled) {
                        $tmp['note'] = isset($iSpec['note']) ? $iSpec['note'] : "";
                    }
                    if ($imageEnable) {
                        $tmp['images'] = isset($iSpec['images']) ? $iSpec['images'] : "";
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            if (!isset($main[$key])) {
                                $main[$key] = 0;
                            }
                            $main[$key] += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;

                        }
                    }
                    if (isset($iSpec['subtotal'])) {
                        $tmp['subtotal'] = $iSpec['subtotal'];
                    }
                    if (isset($iSpec['sub_harga'])) {
                        $tmp['sub_harga'] = $iSpec['sub_harga'];
                    }

                    $tmp["editTarget"] = "";
                    $tmp["removeTarget"] = "";

                    $items3[] = $tmp;


                }
            }
        }


        //----------------------------------------------
        $shoppingCartAdvanceItems = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceItems']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceItems'] : false;
        if ($shoppingCartAdvanceItems == true) {
            $shoppingCartAdvanceItemsKey = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsKey']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsKey'] : NULL;
            $shoppingCartAdvanceFields = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceFields']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceFields'] : array();
            $shoppingCartAdvanceNumFields = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceNumFields'] : array();
//            $shoppingCartAdvanceAmountValue = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceAmountValue']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceAmountValue'] : array();
            $shoppingCartAdvanceSubFields = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceSubFields']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceSubFields'] : array();
            $shoppingCartAdvanceSubNumFields = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceSubNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceSubNumFields'] : array();
            $shoppingCartAdvanceSubEditableFields = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceSubEditableFields']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceSubEditableFields'] : array();
            $shoppingCartAdvanceSubEditableAdditional = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceSubEditableAdditional']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceSubEditableAdditional'] : array();
            $shoppingCartAdvanceSubEditableAdditionalSelector = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceSubEditableAdditionalSelector']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceSubEditableAdditionalSelector'] : NULL;
            $shoppingCartAdvanceItemsSelector = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsSelector']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsSelector'] : NULL;
            $shoppingCartAdvanceItemsRemove = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsRemove']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsRemove'] : NULL;
            $shoppingCartAdvanceItemsAdd = isset($this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsAdd']) ? $this->configUi[$this->jenisTr]['shoppingCartAdvanceItemsAdd'] : NULL;

            if (isset($_SESSION[$cCode]['items2_sum'])) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $id => $iSpec) {
                    $key_result = isset($iSpec[$shoppingCartAdvanceItemsKey]) ? $iSpec[$shoppingCartAdvanceItemsKey] : 0;
                    $advanceItemsLabel = isset($shoppingCartAdvanceFields[$key_result][$stepNumber]) ? $shoppingCartAdvanceFields[$key_result][$stepNumber] : array();
                    $advanceNumItemsLabel = isset($shoppingCartAdvanceNumFields[$key_result][$stepNumber]) ? $shoppingCartAdvanceNumFields[$key_result][$stepNumber] : array();
//                    $advanceAmountItemsLabel = isset($shoppingCartAdvanceAmountValue[$key_result][$stepNumber]) ? $shoppingCartAdvanceAmountValue[$key_result][$stepNumber] : array();
                    $advanceSubItemsLabel = isset($shoppingCartAdvanceSubFields[$key_result][$stepNumber]) ? $shoppingCartAdvanceSubFields[$key_result][$stepNumber] : array();
                    $advanceSubNumItemsLabel = isset($shoppingCartAdvanceSubNumFields[$key_result][$stepNumber]) ? $shoppingCartAdvanceSubNumFields[$key_result][$stepNumber] : array();
                    $advanceSubEditableField = isset($shoppingCartAdvanceSubEditableFields[$key_result][$stepNumber]) ? $shoppingCartAdvanceSubEditableFields[$key_result][$stepNumber] : array();

                    $shoppingCartAdvanceItemsLabel[$id] = $advanceItemsLabel + $advanceNumItemsLabel;
                    $shoppingCartAdvanceItemsLabel[$id] = (count($shoppingCartAdvanceItemsLabel[$id]) > 0) ? $shoppingCartAdvanceItemsLabel[$id] + array("subtotal" => "total price") : array();

                    $shoppingCartAdvanceSubItemsLabel[$id] = $advanceSubItemsLabel + $advanceSubNumItemsLabel;
                    $shoppingCartAdvanceSubItemsLabel[$id] = (count($shoppingCartAdvanceSubItemsLabel[$id]) > 0) ? $shoppingCartAdvanceSubItemsLabel[$id] + array("subtotal" => "subtotal") : array();
                    $shoppingCartAdvanceNumType[$id] = (count($advanceSubNumItemsLabel) > 0) ? $advanceSubNumItemsLabel + array("subtotal" => "subtotal") : array();
                    $shoppingCartAdvanceEditableField[$id] = $advanceSubEditableField;
                }
            }
        }
        //----------------------------------------------
        //        arrprint($itemLabels2);
        if (isset($this->configUi[$this->jenisTr]['shoppingCartSubamount2']) && $this->configUi[$this->jenisTr]['shoppingCartSubamount2'][$stepNumber] == true) {
            $itemLabels = ($itemLabels + $itemNumLabels);
            $itemLabels = (sizeof($itemLabels) > 0) ? $itemLabels + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>") : array();
            $itemLabels = $itemLabels + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = (sizeof($itemLabels2 + $itemNumLabels2) > 0) ? $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>") : array();
            $itemLabels2 = $itemLabels2 + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
        }
        else {
            $itemLabels = (sizeof($itemLabels + $itemNumLabels) > 0) ? $itemLabels + $itemNumLabels + array("subtotal" => "Subtotal") : array();
            $itemLabels2 = (sizeof($itemLabels2 + $itemNumLabels2) > 0) ? $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Subtotal") : array();
            $itemLabels3 = (sizeof($itemLabels3 + $itemNumLabels3) > 0) ? $itemLabels3 + $itemNumLabels3 + array("subtotal" => "Subtotal") : array();
        }

        if (isset($this->configUi[$this->jenisTr]['shoppingCartHideSubamount']) && $this->configUi[$this->jenisTr]['shoppingCartHideSubamount'][$stepNumber] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
            unset($itemLabels3['subtotal']);
            unset($itemLabels[$shoppingCartAmountValue]);
        }

        //region ======additional rows======
        $sumRowAction = base_url() . "ValueGate/evalFees/" . $this->jenisTr;
        $sumRowAction2 = base_url() . "ValueGate/evalVals/" . $this->jenisTr;
        $addValues = isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array();
        $addValues2 = isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array();
        $mainFields = array();
        $sumRows2 = array();
        $sumSpec2 = array();
        $sumType2 = array();
        if (isset($this->configCore[$this->jenisTr]['externalValues'])) {
            if (sizeof($this->configCore[$this->jenisTr]['externalValues']) > 0) {
                $iterator = $this->configCore[$this->jenisTr]['externalValues'];
                foreach ($iterator as $vName => $vSpec) {
                    //region sepasang combobox
                    if (isset($vSpec['mdlName']) && strlen($vSpec['mdlName']) > 0) {
                        $key = $vName . "_src";
                        $sumRows2[$key] = "select " . $vSpec['label'];
                        $sumSpec2[$key] = "<select id='$key' name='$key' class='form-control' onchange=\"document.getElementById('result').src='" . $sumRowAction2 . "?key=$key&value='+this.value;\">";
                        $sumSpec2[$key] .= "<option value=''>-select-</option>";
                        $mdlName9 = $vSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $defaultValue = isset($addValues2[$key]) ? $addValues2[$key] : "";
                                $selected = $row9->id == $defaultValue ? "selected" : "";
                                $sumSpec2[$key] .= "<option value='" . $row9->id . "' $selected>" . $row9->nama . "</option>";
                            }
                        }
                        $sumSpec2[$key] .= "</select>";
                        $sumType2[$key] = "text";
                    }
                    //endregion
                    //region sepasang text
                    $key = $vName;
                    $sumRows2[$vName] = $vSpec['label'];
                    $color = "343434";
                    if (in_array($key, $editableFields)) {
                        $readOnly = "";
                    }
                    else {
                        $readOnly = "readonly";
                    }
                    $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                    $sumSpec2[$key] = "<input type='text' class='form-control text-right' name=$key id=$key value='$defaultValue' 
                    onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                    onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                    >";
                    $sumType2[$key] = "number";
                    //endregion
                    if ($vSpec['taxFactor'] > 0) {
                        $key = $vName . "_tax";
                        $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                        $sumRows2[$key] = "tax for " . $vSpec['label'];
                        $sumSpec2[$key] = "<input type='text' class='form-control text-right' name=$key id=$key value='$defaultValue' 
                        onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                        onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                        >";
                        $sumType2[$key] = "number";
                    }
                }
            }
        }
        //endregion

        //region ======additional rows from reference (if this is a RETURN)======
        if (isset($this->configUi[$this->jenisTr]['referenceJenisTr']) && strlen($this->configUi[$this->jenisTr]['referenceJenisTr']) > 1) {

            $refJenisTr = $this->configUi[$this->jenisTr]['referenceJenisTr'];
            //            cekKuning("berasal dari $refJenisTr");
            $cCode2 = "_TR_" . $refJenisTr;
            $addValues = isset($_SESSION[$cCode2]['main_add_values']) ? $_SESSION[$cCode2]['main_add_values'] : array();
            $addValues2 = isset($_SESSION[$cCode2]['main_add_fields']) ? $_SESSION[$cCode2]['main_add_fields'] : array();
            $mainFields = array();
            $sumRows2 = array();
            $sumSpec2 = array();
            $sumType2 = array();
            if (isset($this->configCore[$refJenisTr]['externalValues'])) {
                if (sizeof($this->configCore[$refJenisTr]['externalValues']) > 0) {
                    $iterator = $this->configCore[$refJenisTr]['externalValues'];
                    foreach ($iterator as $vName => $vSpec) {
                        //region sepasang combobox
                        if (isset($vSpec['mdlName']) && strlen($vSpec['mdlName']) > 0) {
                            $key = $vName . "_src";
                            $sumRows2[$key] = "select " . $vSpec['label'];
                            $sumSpec2[$key] = "<select id='$key' name='$key' class='form-control' onchange=\"document.getElementById('result').src='" . $sumRowAction2 . "?key=$key&value='+this.value;\">";
                            $sumSpec2[$key] .= "<option value=''>-select-</option>";
                            $mdlName9 = $vSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $defaultValue = isset($addValues2[$key]) ? $addValues2[$key] : "";
                                    $selected = $row9->id == $defaultValue ? "selected" : "";
                                    $sumSpec2[$key] .= "<option value='" . $row9->id . "' $selected>" . $row9->nama . "</option>";
                                }
                            }
                            $sumSpec2[$key] .= "</select>";
                            $sumType2[$key] = "text";
                        }
                        //endregion
                        //region sepasang text
                        $key = $vName;
                        $sumRows2[$vName] = $vSpec['label'];
                        $color = "343434";
                        if (in_array($key, $editableFields)) {
                            $readOnly = "";
                        }
                        else {
                            $readOnly = "readonly";
                        }
                        $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                        $sumSpec2[$key] = "<input type='text' readonly class='form-control text-right' name=$key id=$key value='$defaultValue' 
                        onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                        onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                        >";
                        $sumType2[$key] = "number";
                        //endregion
                        if ($vSpec['taxFactor'] > 0) {
                            $key = $vName . "_tax";
                            $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                            $sumRows2[$key] = "tax for " . $vSpec['label'];
                            $sumSpec2[$key] = "<input type='text' readonly class='form-control text-right' name=$key id=$key value='$defaultValue' 
                            onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                            onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $sumRowAction . "?key=$key&value='+this.value;}\"
                            >";
                            $sumType2[$key] = "number";
                        }
                    }
                }
            }
        }

        //endregion

        $tmpMasterValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();
        $addValues = array_merge(array_filter($addValues), array_filter($tmpMasterValues));


        //-------------------------------------------------------------------------------
        $elementMutasiConfig = $this->config->item("accountElementMutasi") != NULL ? $this->config->item("accountElementMutasi") : array();
        $elementMutasi = array();
        if (sizeof($elementMutasiConfig) > 0) {
            if ($this->session->login['cabang_id'] < 0) {
                $subjectID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : "";
                $subjectNama = isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : "";
                $elementMutasiTmp = $elementMutasiConfig["center"];
                foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                    if (isset($el_spec["sub_rekening"])) {
                        $blob_ext = $el_spec["label"] . " $subjectNama ";
                        $blob_ext = blobEncode($blob_ext);
                        $sub_rek = $el_spec["sub_rekening"];
                        $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID/$sub_rek?o=" . $this->session->login['cabang_id'] . "&main_ext2_id=$subjectID&blob_ext=$blob_ext";
                    }
                    else {
                        if (isset($elementConfigs[$el_nama]["aliasRekening"])) {
                            $elementMutasi[$el_nama] = base_url() . $elementConfigs[$el_nama]['link'] . "$subjectID?o=" . $this->session->login['cabang_id'];
                        }
                        else {
                            $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID?o=" . $this->session->login['cabang_id'];
                        }
                    }
                }
            }
        }
        //-------------------------------------------------------------------------------
//arrPrint($elementMutasi);

        //region elements & inputs (if any)
        $elStr = array();
        $elements = array();
        $inputs = array();
        $addRows = array();
        $addRowLabels = array();
        $addRowHiddens = array();
        $addRowBackground = array();
        $currentValue = "";
        //==iterasi untuk memasukkan element relatif
        if (!isset($_SESSION[$cCode]['main_inputs'])) {
            $_SESSION[$cCode]['main_inputs'] = array();
        }
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
                if (array_key_exists($eName, $relElementConfigs)) {
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (sizeof($relElementConfigs[$eName]) > 0) {
                        foreach ($relElementConfigs[$eName] as $valID => $valSpec) {
                            if ($currentValue == $valID) {

                            }
                            else {


                            }
                        }

                    }

                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //                        echo("-- $currentValue ada dalam elementConfig $eName<br>");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            //                            echo("---- memeriksa $eName, $currentValue<br>");
                            //                            $rcCtr=0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rKey => $rcSpec) {
                                //                                $elKey = $eName . "_" . $currentValue . "_" . $rKey;
                                $elKey = $rKey;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rKey];


                            }
                        }
                        else {
                            //                            echo("---- TIDAK PERLU memeriksa $eName, $currentValue<br>");
                        }

                    }
                    else {
                        //                        echo("-- $currentValue TIDAK ada dalam elementConfig $eName<br>");
                    }
                }
                else {
                    //                    echo("$eName TIDAK ada dalam elementConfig<br>");
                }
                if (array_key_exists($eName, $relOptionConfigs)) {

                    if (isset($relOptionConfigs[$eName][$currentValue])) {
                        //						cekHijau("option $currentValue pada $eName $currentValue ada pilihannya");
                        if (sizeof($relOptionConfigs[$eName][$currentValue]) > 0) {
                            //							arrprint($relOptionConfigs[$eName][$currentValue]);
                            foreach ($relOptionConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {

                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {

                                    $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldInput/" . $this->jenisTr . "/$oValueName/?val='+this.value";


                                    //==init value and params
                                    //region default value
                                    if (isset($oValSpec['defaultValue'])) {
                                        $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    }
                                    //endregion


                                    //region max-value
                                    $maxValue = $origDefValue;
                                    if (isset($oValSpec['maxValue'])) {
                                        $maxValue = makeValue($oValSpec['maxValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

                                    }
                                    //endregion


                                    //region min-value
                                    $minValue = $origDefValue;
                                    if (isset($oValSpec['minValue'])) {
                                        $minValue = makeValue($oValSpec['minValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    }
                                    //endregion


                                    $defVal = isset($_SESSION[$cCode]['main_inputs'][$oValueName]) && $_SESSION[$cCode]['main_inputs'][$oValueName] > 0 ? $_SESSION[$cCode]['main_inputs'][$oValueName] : $origDefValue;
                                    $inputs[$oValueName] = "<input type=number id='$oValueName' class='form-control text-center' placeholder='$oValueName' value='$defVal' min='$minValue' max='$maxValue' onfocus='this.select()' onkeyup=\"if(parseInt(this.value)>$maxValue || parseInt(this.value)<$minValue){this.value='$origDefValue';}\"  
onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}\"
onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src =$relInputTarget;}\"
>";
                                    $_SESSION[$cCode]['main_inputs'][$oValueName] = $defVal;
                                    //								cekmerah("$oValueName : $origDefValue");

                                    $inputLabels[$oValueName] = $oValSpec['label'] . "<small> (max: " . number_format($maxValue) . ")</small>";
                                }

                            }
                        }
                    }
                    else {
                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                    }

                }
                else {
                    //					cekKuning("$eName TIDAK terdaftar pada relInputs");
                }

            }
        }


        //==memproses awal elemen2 yang terlibat, jika ada yang bisa dipre-process
        //==misalnya yang pilihannya cuma satu atau yang ada config nilai defaultnya
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
//                 cekHere(":: HAHAHA ::".$eName);
                $elementConfigs[$eName]['autoSelect'] = false;
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    // cekHere(":: HIHIHI ::");
                    if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                        $defValueSrc = $eSpec['defaultValue'];
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                $elementTimeStart = microtime(true);

                                heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
//                                cekKuning("#".__LINE__."#");
//                                cekUngu("#".$eName."#");
//                                cekMerah($this->db->last_query());
//                                heGetTimedQuery($elementTimeStart, __LINE__);
//                                arrPrint($tmpo);
//                                cekHitam("============ #1 ===========");
                                break;
                            case "dataField":
                                heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $this->configUiJenis);
                                break;
                        }
                        $elementConfigs[$eName]['autoSelect'] = true;
                    }
                    else {
                        //==cek apakah pilihannya cuma satu
                        if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                        }
                        else {
                            switch ($eSpec['elementType']) {
                                case "dataModel":
//                                    cekHere(":: HAHAHA ::".$eName);
                                    $elementTimeStart = microtime(true);
                                    $amdlName = $eSpec['mdlName'];
                                    $this->load->model("Mdls/" . $amdlName);
                                    $labelSrc = $eSpec['labelSrc'];
                                    $keySrc = $eSpec['key'];
                                    $oo = new $amdlName();
                                    $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                    $resetFilter = isset($eSpec['mdlFilterReset']) ? $eSpec['mdlFilterReset'] : false;
                                    if ($resetFilter == true) {
                                        $oo->setFilters(array());
                                    }
                                    if (sizeof($aFilter) > 0) {
                                        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                    }
                                    $tmpo = $oo->lookupAll()->result();
//                                    cekKuning("#".__LINE__."#");
//                                    cekUngu("#".$eName."#");
//                                    cekMerah($this->db->last_query());
//                                    heGetTimedQuery($elementTimeStart, __LINE__);
//                                    arrPrint($tmpo);
//                                    cekHitam("============ #2 ===========");
                                    if (sizeof($tmpo) == 1) {
                                        $usedKey = $eSpec['key'];

                                        $defValueSrc = $tmpo[0]->$usedKey;
//                                        cekUngu(":: $eName :: $defValueSrc ::");
                                        $configUiJenis = $this->configUi[$this->jenisTr];
                                        heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                        //                                        $elementConfigs[$eName]['autoSelect']=true;
                                    }
                                    else {

                                    }


                                    break;
                                case "dataField":
                                    break;
                            }
                        }
                    }
                }
                else {
//                     cekHere(":: HOHOHO ::".$eName);
                    if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                    }
                    else {
                        switch ($eSpec['elementType']) {
                            case "dataModel":
//                                cekHere(":: HOHOHO ::".$eName);
                                $elementTimeStart = microtime(true);
                                $amdlName = $eSpec['mdlName'];
                                $this->load->model("Mdls/" . $amdlName);
                                $labelSrc = $eSpec['labelSrc'];
                                $keySrc = $eSpec['key'];
                                $oo = new $amdlName();
                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                $resetFilter = isset($eSpec['mdlFilterReset']) ? $eSpec['mdlFilterReset'] : false;
                                if ($resetFilter == true) {
                                    $oo->setFilters(array());
                                }
                                if (sizeof($aFilter) > 0) {
                                    $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                }
                                //                                cekmerah("pre..");
                                $tmpo = $oo->lookupAll()->result();
//                                showLast_query("biru");
//                                cekKuning("#".__LINE__."#");
//                                cekUngu("#" . $eName . "#");
//                                cekUngu("#" . $amdlName . "#");
//                                cekMerah($this->db->last_query());
//                                cekKuning(count($tmpo));
//                                 heGetTimedQuery($elementTimeStart, __LINE__);
//                                cekKuning($this->db->last_query());
//                                arrPrint($tmpo);
//                                cekHitam("============ 3# ===========");
                                if (sizeof($tmpo) == 1) {
                                    $elementTimeStart = microtime(true);
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    //                                    cekBiru(":: $defValueSrc :: $usedKey ::");
                                    $configUiJenis = $this->configUi[$this->jenisTr];
                                    // heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
//                                    cekKuning($this->db->last_query());
                                    // heGetTimedQuery($elementTimeStart, __LINE__);
                                    //                                    $elementConfigs[$eName]['autoSelect']=true;
//                                    matiHere(__LINE__);
                                }
                                else {
                                    if (sizeof($tmpo) > 1) {

                                    }
                                    else {
                                        unset($_SESSION[$cCode]['main_elements'][$eName]);

//                                    cekKuning("#".__LINE__."#");
//                                    cekUngu("#".$eName."#");
//                                    cekMerah($this->db->last_query());
//                                    arrPrint($tmpo);
//                                    cekHitam("============ 4# ===========");

                                    }
                                }
                                if (isset($eSpec["pairMethod"]["calculate"]["recalculate"]) && $eSpec["pairMethod"]["recom"]["calculate"]["recalculate"] == true) {
//                                    arrPrint($eSpec);
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    $sourceCalculate = $eSpec["pairMethod"]["calculate"]["source"];
                                    if (isset($_SESSION[$cCode]['main'][$sourceCalculate]) && ($_SESSION[$cCode]['main'][$sourceCalculate] > 0)) {
                                        $defValueSrc = $_SESSION[$cCode]['main'][$sourceCalculate];
                                        heRecomCalculate($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                        $this->load->library("ValueGate");
                                        $vg = new ValueGate();
                                        $vg->setConfigUiJenis($this->configUiJenis);
                                        $vg->setConfigCoreJenis($this->configCoreJenis);
                                        $vg->setConfigValuesJenis($this->configValuesJenis);
                                        $vg->setPpnFactor(my_ppn_factor());
                                        $initMasterValues = array(
                                            "olehID" => my_id(),
                                            "olehName" => my_name(),
                                            "sellerID" => my_id(),
                                            "sellerName" => my_name(),
                                            "placeID" => my_cabang_id(),
                                            "placeName" => my_cabang_nama(),
                                            "divID" => my_div_id(),
                                            "divName" => my_div_nama(),
                                            "cabangID" => my_cabang_id(),
                                            "cabangName" => my_cabang_nama(),
                                            "gudangID" => my_gudang_id(),
                                            "gudangName" => my_gudang_nama(),
                                            "jenis_usaha" => my_jenis_usaha(),
                                            "tokoID" => my_toko_id(),
                                            "tokoNama" => my_toko_nama(),
                                            "ppnFactor" => my_ppn_factor(),
                                            "jenisTr" => $this->jenisTr,
                                            "jenisTrMaster" => $this->jenisTr,
                                            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                                            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                                            "stepNumber" => 1,
                                            "stepCode" => $this->configUiJenis['steps'][1]['target'],
                                            "dtime" => dtimeNow(),
                                            "fulldate" => dtimeNow("Y-m-d"),
                                            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
                                        );
                                        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
                                    }
                                }


                                break;
                            case "dataField":
                                break;
                        }
                    }
                }
            }
        }


        //==menciptakan selektor/pilihan berdasarkan jenis elemen
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                //reset dulu kalau yg tidak ada
                if (array_key_exists($eName, $relElementConfigs)) {
                    //                    cekkuning("$eName ada dalam elementConfig, reset dulu adik2nya<br>");
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (sizeof($relElementConfigs[$eName]) > 0) {
                        foreach ($relElementConfigs[$eName] as $valID => $valSpec) {

                            //                            cekkuning("chek if i should reset $valID..");

                            if ($currentValue == $valID) {
                                //                                cekkuning("i wont reset $valID..");
                            }
                            else {


                            }
                        }

                    }
                    //					$currentValue = "";

                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //                        echo("-- $currentValue ada dalam elementConfig $eName<br>");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            //                            echo("---- memeriksa $eName, $currentValue<br>");
                            //                            $rcCtr=0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rKey => $rcSpec) {
                                //                                $elKey = $eName . "_" . $currentValue . "_" . $rKey;
                                $elKey = $rKey;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rKey];
                                //                                echo "elKey: $elKey";
                                //                                $rcCtr++;


                            }
                        }
                        else {
                            //                            echo("---- TIDAK PERLU memeriksa $eName, $currentValue<br>");
                        }

                    }
                    else {
                        //                        echo("-- $currentValue TIDAK ada dalam elementConfig $eName<br>");
                    }
                }
                else {
                    //                    echo("$eName TIDAK ada dalam elementConfig<br>");
                }

                if (array_key_exists($eName, $addRowsConfigs)) {
                    switch ($elementConfigs[$eName]['elementType']) {
                        case "dataModel":
                            $currentValue = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : "";
                            break;
                        case "dataField":
                            $currentValue = $_SESSION[$cCode]['main_elements'][$eName]['value'];
                            break;
                    }
                    if (isset($addRowsConfigs[$eName][$currentValue])) {
                        if (sizeof($addRowsConfigs[$eName][$currentValue]) > 0) {
//                            cekhitam(":: $eName ::");
                            foreach ($addRowsConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
//                                cekMerah("::: $oValueName :::");
                                if (isset($oValSpec["detail"]) && ($oValSpec["detail"] == true)) {
//                                    arrPrintWebs($oValSpec);
                                    if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {
                                        $elementTimeStart = microtime(true);
                                        $kolomDetail = $oValSpec['detailKolom'];
//                                        arrPrintPink($kolomDetail);
//                                        cekHitam($_SESSION[$cCode]["main"]["pihakID"]);
                                        $mainLabel = $oValSpec['label'];
                                        $mdlDetail = $oValSpec['detailModel'];
                                        $mdlFilter = $oValSpec['detailFilter'];
                                        $comDetail = $oValSpec['detailModelSaldo'];
                                        $comFilter = $oValSpec['detailModelFilter'];
//                                        cekBiru("## $oValueName ## $mdlDetail ##");
                                        $this->load->model("Mdls/$mdlDetail");
                                        $dm = New $mdlDetail();
                                        if (sizeof($mdlFilter) > 0) {
                                            makeFilter($mdlFilter, $_SESSION[$cCode]["main"], $dm);
                                        }
                                        $dmTmp = $dm->lookupAll()->result();
//                                        showLast_query("biru");
//                                        arrPrintPink($dmTmp);
//                                        cekMerah($this->db->last_query());
//                                        heGetTimedQuery($elementTimeStart, __LINE__);
                                        $this->load->model("Coms/$comDetail");
                                        $cdm = New $comDetail();
                                        if (sizeof($comFilter) > 0) {
                                            makeFilter($comFilter, $_SESSION[$cCode]["main"], $cdm);
                                        }
                                        $cdmTmp = $cdm->lookupAll()->result();
//                                        cekMerah($this->db->last_query());
//                                        arrPrintWebs($cdmTmp);
//                                        matiHere(__LINE__);
//                                        heGetTimedQuery($elementTimeStart, __LINE__);
//                                        showLast_query("biru");
                                        $cdmData = array();
                                        if (sizeof($cdmTmp) > 0) {
                                            foreach ($cdmTmp as $cdmSpec) {
                                                $cdmData[$cdmSpec->extern_id] = $cdmSpec->$oValSpec['detailModelKey'];
                                            }
                                        }
//                                        arrPrintPink($cdmData);
                                        if (sizeof($dmTmp) > 0) {
                                            foreach ($dmTmp as $dmSpec) {
//                                                arrPrintPink($dmSpec);
                                                $oValueID = isset($kolomDetail["id"]) ? $dmSpec->$kolomDetail["id"] : $dmSpec->id;
                                                $oValueName = isset($kolomDetail["nama"]) ? $dmSpec->$kolomDetail["nama"] : $dmSpec->nama;
                                                $oValueLabel = isset($kolomDetail["label"]) ? $dmSpec->$kolomDetail["label"] : $dmSpec->label;
                                                $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+this.value";
                                                //==init value and params
                                                //region default value
                                                if (isset($oValSpec['defaultValue'])) {
                                                    $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                                }
                                                //endregion
                                                if (isset($oValSpec['hideRow']) && $oValSpec['hideRow'] == true) {
                                                    $addRowHiddens[$oValueName] = "hidden";
                                                }
                                                if (isset($oValSpec['addBackground']) && $oValSpec['addBackground'] != "") {
                                                    $addRowBackground[$oValueName] = $oValSpec['addBackground'];
                                                }
                                                //region max-value
                                                $maxValue = $origDefValue;
                                                if (isset($oValSpec['maxValue'])) {
                                                    $maxValue = makeValue($oValSpec['maxValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                                }
                                                else {
                                                    $maxValue = "";
                                                }
                                                //endregion
                                                //region min-value
                                                $minValue = $origDefValue;
                                                if (isset($oValSpec['minValue'])) {
                                                    $minValue = makeValue($oValSpec['minValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                                }
                                                else {
                                                    $minValue = "";
                                                }
                                                //endregion
                                                $minValStr = $minValue != "" ? "min='$minValue'" : "";
                                                $maxValStr = $maxValue != "" ? "max='$maxValue'" : "";
                                                //region inisiasi keystroke
                                                $keyupAct = "";
                                                if (isset($oValSpec['keyupAction']) && strlen($oValSpec['keyupAction']) > 0) {
                                                    $keyupAct = $oValSpec['keyupAction'];
                                                }
                                                $keyupStr = "";
                                                if ($maxValue != "") {
                                                    $keyupStr .= "if(parseInt(this.value)>$maxValue){this.value='$origDefValue';this.select();} ";
                                                }
                                                $keyupStr .= $keyupAct;
                                                $disabled = "";
                                                if (isset($oValSpec['disabled'])) {
                                                    $disabled = $oValSpec['disabled'];
                                                }
                                                $blurStr = "";
                                                if ($minValue != "") {
                                                    $blurStr = "if(this.value!=this.defaultValue){if(parseInt(this.value)>=$minValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}else{this.value='$minValue';this.focus();}}";
                                                }
                                                else {
                                                    $blurStr = "if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}";
                                                }
                                                //endregion
                                                $defVal = isset($_SESSION[$cCode]['main'][$oValueName]) && $_SESSION[$cCode]['main'][$oValueName] > 0 ? ($_SESSION[$cCode]['main'][$oValueName] + 0) : $origDefValue;
                                                if (isset($addRowsConfigs[$eName][$currentValue][$oValueName]['role']) && $addRowsConfigs[$eName][$currentValue][$oValueName]['role'] == "minus") {
                                                    $defVal = "(" . $defVal . ")";
                                                }
                                                $addRows[$oValueName] = "<input
                                                                    type=text
                                                                    autocomplete='off'
                                                                    id='$oValueName'
                                                                    class='form-control text-right'
                                                                    style='font-size:17px;' $disabled
                                                                    placeholder='$oValueName'
                                                                    value='" . number_format(floor($defVal)) . "'
                                                                    $minValStr $maxValStr
                                                                    onfocus='this.select()'
                                                                    onkeyup=\"$keyupStr\"
                                                                    onfocus=\"$keyupStr\"
                                                                    onblur=\"$blurStr\"
                                                                    onmouseout=\"$blurStr\"
                                                                >";
                                                $addLabel = "";
//                                                if (isset($oValSpec['saldo'])) {
//                                                    $saldo = makeValue($oValSpec['saldo'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
//                                                    $addLabel = "<br>Saldo : <span class='strong'>" . number_format($saldo, 0, ",", ".") . "</span>";
//                                                }
//                                                if (isset($cdmData[$dmSpec->id])) {
//                                                    $saldo = $cdmData[$dmSpec->id];
//                                                    $addLabel = "<br>Saldo : <span class='strong'>" . number_format($saldo, 0, ",", ".") . "</span>";
//                                                }
//                                                cekHitam("ID: $oValueID");
                                                if (isset($cdmData[$oValueID])) {
                                                    $saldo = $cdmData[$oValueID];
                                                    $addLabel = "<div>Saldo : <span masuk_detail class='strong' id='saldo_$oValueName'>" . number_format(floor($saldo), 0, ",", ".") . "</span></div>";
                                                    // max pakai...
                                                }
                                                $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                                $addRowLabels[$oValueName] = "$mainLabel, " . $oValueLabel . $addLabel;

                                            }
                                        }
                                    }
                                }
                                else {
                                    if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {
                                        $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+removeCommas(this.value)";
                                        //==init value and params
                                        //region default value
                                        if (isset($oValSpec['defaultValue'])) {
                                            $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        }
                                        //endregion

                                        if (isset($oValSpec['hideRow']) && $oValSpec['hideRow'] == true) {
                                            $addRowHiddens[$oValueName] = "hidden";
                                        }
                                        if (isset($oValSpec['addBackground']) && $oValSpec['addBackground'] != "") {
                                            $addRowBackground[$oValueName] = $oValSpec['addBackground'];
                                        }
                                        //region max-value
                                        $maxValue = $origDefValue;
                                        if (isset($oValSpec['maxValue'])) {
                                            $maxValue = makeValue($oValSpec['maxValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        }
                                        else {
                                            $maxValue = "";
                                        }
                                        //endregion

                                        //region min-value
                                        $minValue = $origDefValue;
                                        if (isset($oValSpec['minValue'])) {
                                            $minValue = makeValue($oValSpec['minValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        }
                                        else {
                                            $minValue = "";
                                        }
                                        //endregion

                                        $minValStr = $minValue != "" ? "min='$minValue'" : "";
                                        $maxValStr = $maxValue != "" ? "max='$maxValue'" : "";

                                        //region inisiasi keystroke
                                        $keyupAct = "";
                                        if (isset($oValSpec['keyupAction']) && strlen($oValSpec['keyupAction']) > 0) {
                                            $keyupAct = $oValSpec['keyupAction'];
                                        }

                                        $keyupStr = "";
                                        if ($maxValue != "") {
                                            // $keyupStr .= "if(parseInt(this.value)>$maxValue){this.value='$origDefValue';this.select();} ";
                                            $keyupStr .= "";
                                        }
                                        $keyupStr .= $keyupAct;

                                        $disabled = "";
                                        if (isset($oValSpec['disabled'])) {
                                            $disabled = $oValSpec['disabled'];
                                        }

                                        $blurStr = "";
                                        if ($minValue != "") {
                                            $blurStr = "if(this.value!=this.defaultValue){if(parseInt(this.value)>=$minValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}else{this.value='$minValue';this.focus();}}";
                                        }
                                        else {
                                            $blurStr = "if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}";

                                        }
                                        //endregion
                                        /**
                                         * $defVal diganti tanpa logic > 0 untuk mengakomodasi jika input memang beisi 0. jika amau di gunakan pertimbagakan hal ini
                                         */
//                                        $defVal = isset($_SESSION[$cCode]['main'][$oValueName]) && $_SESSION[$cCode]['main'][$oValueName] > 0 ? ($_SESSION[$cCode]['main'][$oValueName] + 0) : $origDefValue;
                                        $defVal = isset($_SESSION[$cCode]['main'][$oValueName]) ? ($_SESSION[$cCode]['main'][$oValueName] + 0) : $origDefValue;
                                        if (isset($addRowsConfigs[$eName][$currentValue][$oValueName]['role']) && $addRowsConfigs[$eName][$currentValue][$oValueName]['role'] == "minus") {
                                            $defVal = "(" . $defVal . ")";
                                        }

                                        $addLabel = "";
                                        if (isset($oValSpec['saldo'])) {
                                            $saldo = makeValue($oValSpec['saldo'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                            $addLabel = "<div>Saldo : <span else_detail class='strong' id='saldo_$oValueName'>" . number_format(floor($saldo), 0, ",", ".") . "</span></div>";
                                            if ($saldo == 0) {
                                                $disabled = "disabled";
                                            }
                                        }
                                        if (isset($oValSpec['label_warning'])) {
                                            if ($defVal > 0) {
                                                $addLabel = "<br><span class='strong'>" . $oValSpec['label_warning'] . "</span>";
                                            }
                                        }
                                        if (isset($oValSpec['mutasiLink']['link_key'])) {
                                            $subjectID = $_SESSION[$cCode]["main"]["pihakID"];
                                            $subjectNama = $_SESSION[$cCode]["main"]["pihakName"];
                                            $subject_ext = isset($oValSpec['mutasiLink']['ext_label']) ? $oValSpec['mutasiLink']['ext_label'] : "";
                                            $blob_ext = " $subject_ext $subjectNama ";
                                            $blob_ext = blobEncode($blob_ext);
                                            $mtslink = isset($elementMutasi[$oValSpec['mutasiLink']['link_key']]) ? $elementMutasi[$oValSpec['mutasiLink']['link_key']] : NULL;
                                            $mtslink = $mtslink . "&main_ext2_id=$subjectID&blob_ext=$blob_ext";
//                                            cekHere($mtslink);
                                            $dModalDialog = modalDialogBtn('&nbsp;', $mtslink, $auto_close = 0, 'saldo');
                                            $addLabel .= "<button href='javascript:void(0)' class='btn btn-xs btn-info'
                                                    onclick=\"$dModalDialog\" 
                                                    ><span class='glyphicon glyphicon-time'></span></button>";
                                        }

                                        $addRows[$oValueName] = "<input
                                                                    type=text
                                                                    autocomplete='off'
                                                                    id='$oValueName'
                                                                    class='form-control text-right hh'
                                                                    style='font-size:17px;' $disabled
                                                                    placeholder='$oValueName $defVal'
                                                                    value='" . number_format(floor($defVal), 0) . "'
                                                                    $minValStr $maxValStr
                                                                    onfocus='this.select()'
                                                                    onkeyup=\"$keyupStr\"
                                                                    onfocus=\"$keyupStr\"
                                                                    onblur=\"$blurStr\"
                                                                    onmouseout=\"$blurStr\"
                                                                >";

//                                        $addRows[$oValueName] = "<input
//                                                                    type=text
//                                                                    autocomplete='off'
//                                                                    id='$oValueName'
//                                                                    class='form-control text-right hh'
//                                                                    style='font-size:17px;' $disabled
//                                                                    placeholder='$oValueName'
//                                                                    value='$defVal'
//                                                                    $minValStr $maxValStr
//                                                                    onfocus='this.select()'
//                                                                    onkeyup=\"$keyupStr\"
//                                                                    onfocus=\"$keyupStr\"
//                                                                    onblur=\"$blurStr\"
//                                                                    onmouseout=\"$blurStr\"
//                                                                >";

                                        $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                        $addRowLabels[$oValueName] = $oValSpec['label'] . $addLabel;

                                    }
                                }
                            }
                        }
                    }
                    else {
                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        //                        cekmerah("aturan untuk $currentValue TIDAK ada");
                    }

                }
                else {
                    //					cekKuning("$eName TIDAK terdaftar pada relInputs");
                }

                //--------------
                if (isset($eSpec["noValidateReplacer"]["key"])) {
                    $key_validate = $eSpec["noValidateReplacer"]["key"];
                    if (isset($_SESSION[$cCode]["main"][$key_validate]) && ($_SESSION[$cCode]["main"][$key_validate] > 0)) {
                        $eSpec["noValidate"] = false;
                    }
                    else {
                        $eSpec["noValidate"] = true;
                    }
                }
                //--------------

                switch ($eSpec['elementType']) {
                    case "dataModel":
                        $elementTimeStart = microtime(true);
                        $addStr = "";
                        $editStr = "";
                        $amdlName = $eSpec['mdlName'];
                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                        $aFilterAll = isset($eSpec['mdlFilterAll']) ? $eSpec['mdlFilterAll'] : array();
                        $labelTop = isset($eSpec['labelTop']) ? $eSpec['labelTop'] : array();
                        $keyMainGate = isset($eSpec['keyMainGate']) ? $eSpec['keyMainGate'] : "pairPihakName";

                        $elStr[$eName] = "";
                        $this->load->model("Mdls/" . $amdlName);
                        $labelSrc = $eSpec['labelSrc'];
                        $keySrc = $eSpec['key'];
                        $oo = new $amdlName();
                        $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $amdlName);
                        $resetFilter = isset($eSpec['mdlFilterReset']) ? $eSpec['mdlFilterReset'] : false;
                        if ($resetFilter == true) {
                            $oo->setFilters(array());
                        }
                        if (sizeof($aFilter) > 0) {
                            foreach ($aFilter as $filter) {
                                $exFilter = explode("=", $filter);
                                if (sizeof($exFilter) > 1) {
                                    if (substr($exFilter[1], 0, 1) == ".") {
                                        //                                        $oo->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                            //                                            $oo->addFilter($exFilter[0] . "='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                            $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                        }
                                        else {
                                            //                                            $oo->addFilter($exFilter[0] . "='none'");
                                        }
                                    }
                                }
                            }
                            $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                        }


                        if (sizeof($aFilterAll) > 0) {
                            $ooAll = new $amdlName();
                            foreach ($aFilterAll as $filter) {
                                $exFilter = explode("=", $filter);
                                if (sizeof($exFilter) > 1) {
                                    if (substr($exFilter[1], 0, 1) == ".") {

                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                            $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                        }
                                        else {
                                        }
                                    }
                                }
                            }
                            $ooAll = makeFilter($aFilterAll, $_SESSION[$cCode]['main'], $ooAll);
                        }

                        $addClick = "";
                        $dataAccess = isset($this->config->item('heDataBehaviour')[$amdlName]) ? $this->config->item('heDataBehaviour')[$amdlName] : array("viewers" => array(),
                            "creators" => array(),
                            "creatorAdmins" => array(),
                            "updaters" => array(),
                            "updaterAdmins" => array(),
                            "deleters" => array(),
                            "deleterAdmins" => array(),
                            "historyViewers" => array(),
                        );
                        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                        if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
                            if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                                $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
                                $addStr = "<a href='javascript:void(0)' class='btn btn-tool' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
                            }
                        }

                        //                        cekmerah("pre..");
                        $tmpo = $oo->lookupAll()->result();

//                        cekMerah($this->db->last_query());
//                        heGetTimedQuery($elementTimeStart, __LINE__);

                        $countRel = count($tmpo);
                        $totalSaldo = 0;
                        $tmpoAll = array();
                        if (sizeof($aFilterAll) > 0) {
                            $tmpoAll = $ooAll->lookupAll()->result();
                            $countNonRel = count($tmpoAll);
                            if (!empty($labelTop) && !empty($tmpoAll)) {
                                foreach ($tmpoAll as $ky => $tmpoData) {
                                    $totalSaldo += $tmpoData->$labelTop["srcVal"] * 1;
                                }
                            }
                        }

//                        cekMerah($this->db->last_query());
//                        heGetTimedQuery($elementTimeStart, __LINE__);

//                        cekHere("totalSaldo: " . $totalSaldo);

                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";

                        if (!empty($labelTop)) {
                            $elStr[$eName] .= "<div class='box-header text-left'>";
                            $elStr[$eName] .= "<div style='font-size: 14px;' class='text-bold text-danger'>" . $labelTop['label'] . ":</div>";
                            $elStr[$eName] .= "<div style='font-size: 16px;' class='text-bold text-danger'>" . number_format($totalSaldo) . "</div>";
                            $elStr[$eName] .= "</div>";
                        }

                        $elStr[$eName] .= "<div class='box-body'>";

                        switch ($eSpec['inputType']) {
                            case "combo":
                                $elStr[$eName] .= "<select data-style='btn btn-md btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='--silahkan pilih--' data-size='15' data-container='.modal' class='selectpicker form-controlx btn-block select2 show-tick' onchange=\"hiliteDiv(this);document.getElementById('result').src=$selectorTarget;\">";

                                $elStr[$eName] .= "<option value=''>-select-</option>";

                                if (sizeof($aFilterAll) > 0 && $countRel == 0) {
                                    $elStr[$eName] .= "<option disabled data-style='text-bold' class='text-bold text-red' value=''>tidak ada uang muka yang berelasi dengan tagihan ini. (" . formatField_he_format("nomer_nolink", $_SESSION[$cCode]['main']['pairPihakName']) . ")</option>";
//                                    $elStr[$eName] .= "<option disabled data-style='text-bold' class='text-bold text-red' value=''>*tidak ada uang muka yang berelasi dengan tagihan ini. (" . $_SESSION[$cCode]['main']['pairPihakName'] . ")</option>";
                                }

//                                if (sizeof($tmpo) > 0) {
//                                    foreach ($tmpo as $row) {
//                                        $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
//                                        $disabledRowOption = "";
//                                        $nonRelation = "";
//                                        if (sizeof($aFilterAll) > 0) {
//                                            $nonRelation = $row->extern2_nama == "" ? "(uangmuka)" : "";
//                                            $disabledRowOption = $row->extern2_nama != $_SESSION[$cCode]['main']['pairPihakName'] ? "disabled class='bg-gray text-white' title='tidak bisa di gunakan karena...' " : " class='text-bold text-green'";
//                                        }
//
//                                        if (sizeof($ex) > 1) {
//                                            $labelValue = "";
//                                            foreach ($ex as $col) {
//                                                if(strpos($row->$col,".0000")){
//                                                    $labelValue .= number_format($row->$col) . " / ";
//                                                }
//                                                else{
//                                                    $labelValue .= $row->$col . " / ";
//                                                }
//                                            }
//                                            $labelValue = rtrim($labelValue, " / ");
//                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
//                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
//                                            $elStr[$eName] .= "<option $disabledRowOption value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
//                                        }
//                                        else {
//                                            $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
//                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
//                                            $labelValue = "";
//                                            if(strpos($row->$labelSrc,".0000")){
//                                                $labelValue .= number_format($row->$labelSrc);
//                                            }
//                                            else{
//                                                $labelValue .= $row->$labelSrc;
//                                            }
//
//                                            $elStr[$eName] .= "<option $disabledRowOption value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
//                                        }
//                                    }
//                                }

                                if (sizeof($tmpoAll) > 0) {
                                    foreach ($tmpoAll as $row) {
                                        $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                        $disabledRowOption = "";
                                        $nonRelation = "";
                                        if (sizeof($aFilterAll) > 0) {
                                            $nonRelation = $row->extern2_nama == "" ? "(uangmuka)" : "";
//                                            $disabledRowOption = ($row->extern2_nama != $_SESSION[$cCode]['main']['pairPihakName']) ? "disabled class='bg-gray text-white' title='tidak bisa di gunakan karena tidak berelasi dengan tagihan ini.' " : " class='text-bold text-green'";
                                            $disabledRowOption = ($row->extern2_nama != $_SESSION[$cCode]['main'][$keyMainGate]) ? "disabled class='bg-gray text-white' title='tidak bisa di gunakan karena tidak berelasi dengan tagihan ini.' " : " class='text-bold text-green'";
                                        }

                                        if (sizeof($ex) > 1) {
                                            $labelValue = "";
                                            foreach ($ex as $col) {
                                                if (strpos($row->$col, ".0000")) {
                                                    $labelValue .= number_format($row->$col) . " / ";
                                                }
                                                else {
                                                    if ($col == "extern2_nama") {
                                                        $val_nolink = formatField_he_format("nomer_nolink", $row->$col);
                                                    }
                                                    else {
                                                        $val_nolink = $row->$col;
                                                    }
//                                                    $labelValue .= $row->$col . " / ";
                                                    $labelValue .= $val_nolink . " / ";
                                                }
                                            }
                                            $labelValue = rtrim($labelValue, " / ");
                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected class='text-bold text-red'" : "";
                                            $elStr[$eName] .= "<option $disabledRowOption value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected class='text-bold text-red'" : "";
                                            $labelValue = "";
                                            if (strpos($row->$labelSrc, ".0000")) {
                                                $labelValue .= number_format($row->$labelSrc);
                                            }
                                            else {
                                                $labelValue .= $row->$labelSrc;
                                            }

                                            $elStr[$eName] .= "<option $disabledRowOption value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                        }
                                    }
                                }

                                $elStr[$eName] .= "</select>";
                                break;
                            case "radio":
                                if (sizeof($tmpo) > 0) {
                                    if ($eName == "cash_account") {
                                        foreach ($tmpo as $row) {
                                            $arrBank[$row->folders] = $row->folders_nama;
                                            $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                            if (sizeof($ex) > 1) {
                                                $labelValue = "";
                                                foreach ($ex as $col) {
                                                    $labelValue .= $row->$col . " / ";
                                                }
                                                $labelValue = rtrim($labelValue, " / ");
                                                $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelValue . "</label>\n";
                                            }
                                            else {
                                                $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $labelValue = "";
                                                if (strpos($row->$labelSrc, ".0000")) {
                                                    $labelValue .= number_format($row->$labelSrc);
                                                }
                                                else {
                                                    $labelValue .= $row->$labelSrc;
                                                }
                                                $labelAccount = isset($row->$labelSrc) ? $labelValue : '-';
                                                if (isset($elementConfigs[$eName]['labelSrcFields']) && (sizeof($elementConfigs[$eName]['labelSrcFields']) > 0)) {
                                                    $anu = "";
                                                    foreach ($elementConfigs[$eName]['labelSrcFields'] as $field_account) {
                                                        $anu .= $row->$field_account . " &nbsp; ";
//                                                        $anu .= makeValue($field_account, (array)$row, $_SESSION[$cCode]["main"], "") . " &nbsp; ";
                                                    }
                                                    $labelAccount = $anu;
                                                }

                                                $arrBankRekening[$row->folders][] = "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
<input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\"> $labelFront" . $labelAccount . "</label><br>\n";

//                                                $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
//<input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected
//onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelAccount . "</label><br>\n";
//
                                            }
                                        }
                                        $elStr[$eName] .= "<div style='text-align: left;'>";
                                        if (isset($arrBank) && (sizeof($arrBank) > 0)) {
                                            $anu = "";
                                            foreach ($arrBank as $fid => $fnama) {
                                                if ($fnama != NULL) {
                                                    $anu .= "<span class='text-bold' style='font-size:15px;'>$fnama</span><br>";
                                                }
                                                if (isset($arrBankRekening[$fid]) && (sizeof($arrBankRekening[$fid]) > 0)) {
                                                    foreach ($arrBankRekening[$fid] as $fdata) {
                                                        $anu .= $fdata;
                                                    }
                                                }
                                            }
                                            $elStr[$eName] .= $anu;
                                        }
                                        $elStr[$eName] .= "</div>";


                                    }
                                    else {
                                        foreach ($tmpo as $row) {
                                            $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                            if (sizeof($ex) > 1) {
                                                $labelValue = "";
                                                foreach ($ex as $col) {
                                                    $labelValue .= $row->$col . " / ";
                                                }
                                                $labelValue = rtrim($labelValue, " / ");
                                                $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelValue . "</label>\n";
                                            }
                                            else {
                                                $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $labelValue = "";
                                                if (strpos($row->$labelSrc, ".0000")) {
                                                    $labelValue .= number_format($row->$labelSrc);
                                                }
                                                else {
                                                    $labelValue .= $row->$labelSrc;
                                                }

                                                $labelFront = "";
                                                if (isset($elementConfigs[$eName]['labelSrcFront']) && (sizeof($elementConfigs[$eName]['labelSrcFront']) > 0)) {
//                                                    cekHere("[$eName]");
                                                    foreach ($elementConfigs[$eName]['labelSrcFront'] as $field_account) {
//                                                        $anu .= $row->$field_account . " &nbsp; ";
                                                        $labelFront .= makeValue($field_account, (array)$row, $_SESSION[$cCode]["main"], "") . " &nbsp; ";
                                                    }
//                                                    $labelAccount = $anu;
                                                }
//                                                cekHitam("[$labelFront]");

                                                $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
<input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\"> $labelFront" . (isset($row->$labelSrc) ? $labelValue : '-') . "</label>\n";
                                            }
                                        }
                                    }
                                }
                                break;
                        }

                        $elStr[$eName] .= "</div class='box-header'>";

                        $defKey = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : 0;
                        $showNull = isset($elementConfigs[$eName]['showNull']) ? $elementConfigs[$eName]['showNull'] : false;
                        $nullValue = isset($elementConfigs[$eName]['nullValue']) ? $elementConfigs[$eName]['nullValue'] : "";
                        $nullSrc = isset($elementConfigs[$eName]['nullSrc']) ? $elementConfigs[$eName]['nullSrc'] : "";

                        $defValue = "";
                        if (isset($_SESSION[$cCode]['main_elements'][$eName]['key']) && $_SESSION[$cCode]['main_elements'][$eName]['contents']) {
                            if (isset($elementConfigs[$eName]['usedFields']) && sizeof($elementConfigs[$eName]['usedFields']) > 0) {
                                $defValue .= "<div class='panel-body' style='font-size:15px;'>";
                                $defValue .= "<table cellspacing='0' cellpadding='0' border='0'>";
                                $contents[$eName] = unserialize(base64_decode($_SESSION[$cCode]['main_elements'][$eName]['contents']));
                                $semicolonnbsp = "";
                                foreach ($elementConfigs[$eName]['usedFields'] as $src => $label) {
                                    $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                    $defValue .= "<tr " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . "'>";
                                    if (strlen($fieldLabel) > 0 || $showNull == true) {
                                        if (strlen($label) > 0) {
                                            $defValue .= "<td class='text-capitalize' align='left'>$label";
                                            $defValue .= "&nbsp;</td>";
                                            $semicolonnbsp = ":&nbsp; ";
//                                            $newValue_r = ($fieldLabel == "" && $showNull == true && $nullValue != "") ? ($nullSrc == $label ? $nullValue : formatField($src, $fieldLabel)) : formatField($src, $fieldLabel);
                                            $newValue_r = ($fieldLabel == "" && $showNull == true && $nullValue != "") ? ($nullSrc == $label ? $nullValue : ($fieldLabel)) : ($fieldLabel);
//                                            cekKuning("=== $newValue_r [$fieldLabel] ===");
//                                            cekBiru("=== $newValue_r ===");
                                            if ($src == 'saldo') {
                                                $arrNewValue_r = explode('+', $newValue_r);
                                                $newSaldo = 0;
                                                if (sizeof($arrNewValue_r) > 0) {
                                                    foreach ($arrNewValue_r as $k => $kVal) {
                                                        $newSaldo += $kVal;
                                                    }
                                                }
                                                $newValue_r = ($newSaldo > 0) ? "<span class='text-bold text-green'>" . number_format($newSaldo) . "</span>" : "<span class='text-bold text-red'>SALDO KOSONG</span>";
                                            }
                                            $defValue .= "<td $label align='left' class='text-bold text-uppercase'>$semicolonnbsp" . $newValue_r;
                                            $defValue .= "</td>";
                                        }
                                        else {
                                            $defValue .= "<td align='left' colspan='2' class='text-bold text-uppercase'>$semicolonnbsp " . formatField($src, $fieldLabel);
                                            $defValue .= "</td>";
                                        }
                                    }
                                    $defValue .= "</tr>";
                                }
                                $defValue .= "</table>";
                                $defValue .= "</div class='panel-body'>";
                            }
                        }
                        else {//menentukan nilai default

                        }

                        if ($defKey > 0) {
                            if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                    $editClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $editLink . "'),
                                        draggable:true,
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

                                    $editStr = "<a href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                }
                            }
                        }

                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                        $elStr[$eName] .= "</div id='el$amdlName'>";

                        $elements[$eName] = array(
                            "type" => $eSpec['inputType'],
                            "mdlName" => $eSpec['mdlName'],
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                            "editStr" => $editStr,
                            "addStr" => $addStr,
//                            "bgColor" => $defValue == "" ? "#fcfce0" : "#f5fff9", //original
                            "bgColor" => $defValue == "" && isset($eSpec['noValidate']) && $eSpec['noValidate'] == 1 ? "#fcfce0" : ($defValue == "" ? "#ffcccc" : "#f5fff9"),
                        );


                        break;
                    case "dataField":
                        $elStr[$eName] = "";
                        $initValue = isset($eSpec['defaultValue']) ? $eSpec['defaultValue'] : "";
                        //                        $defaultValue = isset($_SESSION[$cCode]['main_elements'][$eName]['value']) ? $_SESSION[$cCode]['main_elements'][$eName]['value'] : "";
                        $defaultValue = isset($_SESSION[$cCode]['main'][$eName]) ? $_SESSION[$cCode]['main'][$eName] : 0;
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldElement/" . $this->jenisTr . "/$eName/$amdlName/?val='+this.value";
                        //                        $elStr[$eName] .="<div class='box'>";

                        $maxValue = isset($eSpec['maxValue']) && isset($_SESSION[$cCode]['main'][$eSpec['maxValue']]) ? $_SESSION[$cCode]['main'][$eSpec['maxValue']] : "";

                        $elStr[$eName] .= "<div class='box-body'>";
                        switch ($eSpec['inputType']) {
                            case "text":
                                $elStr[$eName] .= "<input type=text class='form-control' value='$defaultValue' onfocus='this.select()' oonclick=\"this.value='$defaultValue';\" 
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);document.getElementById('result').src=$selectorTarget;}\">";
                                break;
                            case "number":
                                $maxValStr = $maxValue != "" ? " max='" . $maxValue . "''" : "";
                                $maxValValidator = $maxValue != "" ? " onkeyup=\"if(this.value>$maxValue){this.value='$maxValue';}\" " : "";
                                $elStr[$eName] .= "<input type=number class='form-control' value='$defaultValue' onfocus='this.select()' $maxValStr $maxValValidator oonclick=\"this.value='$defaultValue';\" 
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);document.getElementById('result').src=$selectorTarget;}\">";
                                break;
                            case "date":
                                $elStr[$eName] .= "<input type=date class='form-control' value='$defaultValue' onfocus='this.select()' oonclick=\"this.value='$defaultValue';\" 
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);document.getElementById('result').src=$selectorTarget;}\">";
                                break;
                        }
                        $elStr[$eName] .= "</div class='box-body'>";

                        $elements[$eName] = array("mdlName" => null,
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                            "editStr" => "",
                            "addStr" => "",
                            "bgColor" => $defaultValue == "" ? "#fcfce0" : "#fcfcff",
                        );

                        break;
                }
            }
        }


        //endregion


        //region items element auto build.
        if (sizeof($elementItemsAutoConfigs) > 0) {
            if (isset($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {

                    foreach ($elementItemsAutoConfigs as $eSpec) {
                        //                        cekHere("$iID element specification");
                        //                        arrPrintWebs($eSpec);
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                $elementTimeStart = microtime(true);
                                $addStr = "";
                                $editStr = "";
                                $amdlName = $eSpec['mdlName'];
                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();

                                $iLabel = $iSpec['nama'];
                                $eName = $iID;
                                $helpName = "pembantu";

                                $elStr[$eName] = "";
                                $this->load->model("Mdls/" . $amdlName);
                                $labelSrc = $eSpec['labelSrc'];
                                $keySrc = $eSpec['key'];
                                $oo = new $amdlName();
                                $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $amdlName);

                                if (sizeof($aFilter) > 0) {
                                    foreach ($aFilter as $filter) {
                                        $exFilter = explode("=", $filter);
                                        if (sizeof($exFilter) > 1) {
                                            if (substr($exFilter[1], 0, 1) == ".") {
                                                //                                        $oo->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                                            }
                                            else {
                                                if (isset($iSpec[$exFilter[1]])) {
                                                    //                                            $oo->addFilter($exFilter[0] . "='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                                    $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $iSpec[$exFilter[1]];
                                                }
                                                else {
                                                    //                                            $oo->addFilter($exFilter[0] . "='none'");
                                                }
                                            }
                                        }
                                    }
                                    $oo = makeFilter($aFilter, $iSpec, $oo);
                                }

                                $addClick = "";
                                $dataAccess = isset($this->config->item('heDataBehaviour')[$amdlName]) ? $this->config->item('heDataBehaviour')[$amdlName] : array("viewers" => array(),
                                    "creators" => array(),
                                    "creatorAdmins" => array(),
                                    "updaters" => array(),
                                    "updaterAdmins" => array(),
                                    "deleters" => array(),
                                    "deleterAdmins" => array(),
                                    "historyViewers" => array(),
                                );
                                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                                if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
                                    if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                                        $addClick = "
                                    top.BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                            message: top.$('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
                                        $addStr = "<a " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " href='javascript:void(0)' class='btn btn-tool' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
                                    }
                                }

                                $tmpo = $oo->lookupAll()->result();
                                // cekmerah($this->db->last_query());

//                                cekMerah($this->db->last_query());
//                                heGetTimedQuery($elementTimeStart, __LINE__);

                                $elPair[$amdlName] = array();
                                $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchItemsElement/" . $this->jenisTr . "/$eName/$amdlName/$helpName?key='+this.value";

                                $elStr[$eName] .= "<div class='box-body'>";
                                //                                cekHitam(": $eName :");
                                switch ($eSpec['inputType']) {
                                    case "combo":
                                        $elStr[$eName] .= "<select " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " class='form-control' onchange=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">";
                                        $elStr[$eName] .= "<option value=''>-select-</option>";
                                        if (sizeof($tmpo) > 0) {
                                            foreach ($tmpo as $row) {
                                                //                                                cekPink2($row->$keySrc);
                                                $ex = explode("/", $eSpec['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {

                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['items'][$eName][$helpName]) && $_SESSION[$cCode]['items'][$eName][$helpName] == $row->$keySrc ? "checked" : "";

                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['items'][$eName][$helpName]) && $_SESSION[$cCode]['items'][$eName][$helpName] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";

                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['items'][$eName][$helpName]) && $_SESSION[$cCode]['items'][$eName][$helpName] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                                }


                                            }
                                        }
                                        $elStr[$eName] .= "</select>";
                                        break;
                                    case "radio":

                                        if (sizeof($tmpo) > 0) {
                                            foreach ($tmpo as $row) {
                                                $ex = explode("/", $eSpec['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {
                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['items'][$eName][$helpName]) && $_SESSION[$cCode]['items'][$eName][$helpName] == $row->$keySrc ? "checked" : "";
                                                    $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $labelValue . "</label>\n";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['items'][$eName][$helpName]) && $_SESSION[$cCode]['items'][$eName][$helpName] == $row->$keySrc ? "checked" : "";
                                                    $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $row->$labelSrc . "</label>\n";
                                                }

                                            }
                                        }
                                        break;
                                }


                                $elStr[$eName] .= "</div class='box-header'>";

                                $defKey = isset($_SESSION[$cCode]['items'][$eName][$helpName]) ? $_SESSION[$cCode]['items'][$eName][$helpName] : 0;
                                $showNull = isset($eSpec['showNull']) ? $eSpec['showNull'] : false;
                                $nullValue = isset($eSpec['nullValue']) ? $eSpec['nullValue'] : "";
                                $nullSrc = isset($eSpec['nullSrc']) ? $eSpec['nullSrc'] : "";

                                $defValue = "";
                                if (isset($_SESSION[$cCode]['items_elements'][$eName]['key']) && $_SESSION[$cCode]['items_elements'][$eName]['contents']) {
                                    if (isset($eSpec['usedFields']) && sizeof($eSpec['usedFields']) > 0) {

                                        $defValue .= "<div class='panel-body'>";
                                        $defValue .= "<table cellspacing='0' cellpadding='0' border='0'>";
                                        $contents[$eName] = unserialize(base64_decode($_SESSION[$cCode]['items_elements'][$eName]['contents']));
                                        $semicolonnbsp = "";
                                        foreach ($eSpec['usedFields'] as $src => $label) {
                                            $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                            $defValue .= "<tr " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . "'>";
                                            if (strlen($fieldLabel) > 0 || $showNull == true) {
                                                if (strlen($label) > 0) {
                                                    $defValue .= "<td class='text-capitalize' align='left'>$label";
                                                    $defValue .= "&nbsp;</td>";
                                                    $semicolonnbsp = ":&nbsp;";
                                                    $newValue_r = $fieldLabel == "" && $showNull == true && $nullValue != "" ? ($nullSrc == $label ? $nullValue : formatField($src, $fieldLabel)) : formatField($src, $fieldLabel);
                                                    if ($src == 'saldo') {
                                                        $arrNewValue_r = explode('+', $newValue_r);
                                                        $newSaldo = 0;
                                                        if (sizeof($arrNewValue_r) > 0) {
                                                            foreach ($arrNewValue_r as $k => $kVal) {
                                                                $newSaldo += $kVal;
                                                            }
                                                        }
                                                        $newValue_r = $newSaldo > 0 ? "<span class='text-bold text-green'>" . number_format($newSaldo) . "</span>" : "<span class='text-bold text-red'>SALDO KOSONG</span>";
                                                    }
                                                    $defValue .= "<td $label align='left' class='text-bold text-uppercase'>$semicolonnbsp" . $newValue_r;
                                                    $defValue .= "</td>";
                                                }
                                                else {
                                                    $defValue .= "<td align='left' colspan='2' class='text-bold text-uppercase'>$semicolonnbsp" . formatField($src, $fieldLabel);
                                                    $defValue .= "</td>";
                                                }
                                            }
                                            $defValue .= "</tr>";
                                        }
                                        $defValue .= "</table>";
                                        $defValue .= "</div class='panel-body'>";
                                    }
                                }
                                else {//menentukan nilai default

                                }

                                //                                if ($defKey > 0) {
                                //                                    if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                //                                        $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                //                                        if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                //                                            $editClick = "
                                //                    top.BootstrapDialog.show(
                                //                                   {
                                //                                        title:'New " . $eSpec['label'] . "',
                                //                                        message: top.$('<div></div>').load('" . $editLink . "'),
                                //                                        draggable:true,
                                //                                        size:BootstrapDialog.SIZE_WIDE,
                                //                                        closable:true,
                                //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                //                                        }
                                //                                        );";
                                //
                                //                                            $editStr = "<a " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                //                                        }
                                //                                    }
                                //                                }

                                $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                                $elStr[$eName] .= "</div id='el$amdlName'>";


                                $nonStr[$eName] = "";
                                $nonStr[$eName] .= "<div class='alert alert-warning' style='font-weight: bold;'>";
                                $nonStr[$eName] .= $eSpec['nullValue'];
                                $nonStr[$eName] .= "</div>";

                                $itemsElements[$eName] = array("type" => $eSpec['inputType'],
                                    "mdlName" => $eSpec['mdlName'],
                                    "label" => $eSpec['label'] . " $iLabel ",
                                    //                                    "string" => $elStr[$eName],
                                    "string" => sizeof($tmpo) > 0 ? $elStr[$eName] : $nonStr[$eName],
                                    "editStr" => $editStr,
                                    "addStr" => $addStr,
                                    "bgColor" => $defValue == "" ? "#fcfce0" : "#f5fff9",
                                    "helpName" => $helpName,
                                );


                                break;
                            case "dataField":
                                $iLabel = $iSpec['nama'];
                                $eName = $iID;

                                $elStr[$eName] = "";
                                $initValue = isset($eSpec['defaultValue']) ? $eSpec['defaultValue'] : "";
                                $defaultValue = isset($iSpec[$eName]) ? $iSpec[$eName] : 0;
                                $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldItemsElement/" . $this->jenisTr . "/$eName/$amdlName/$helpName?val='+this.value";

                                $maxValue = isset($eSpec['maxValue']) && isset($iSpec[$eSpec['maxValue']]) ? $iSpec[$eSpec['maxValue']] : "";

                                $elStr[$eName] .= "<div class='box-body'>";
                                switch ($eSpec['inputType']) {
                                    case "text":
                                        $elStr[$eName] .= "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text class='form-control' value='$defaultValue' onfocus='this.select()' oonclick=\"this.value='$defaultValue';\"
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);top.$('#result').load($selectorTarget);}\">";
                                        break;
                                    case "number":
                                        $maxValStr = $maxValue != "" ? " max='" . $maxValue . "''" : "";
                                        $maxValValidator = $maxValue != "" ? " onkeyup=\"if(this.value>$maxValue){this.value='$maxValue';}\" " : "";
                                        $elStr[$eName] .= "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text class='form-control' value='$defaultValue' onfocus='this.select()' $maxValStr $maxValValidator oonclick=\"this.value='$defaultValue';\"
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);top.$('#result').load($selectorTarget);}\">";
                                        break;
                                    case "date":
                                        $elStr[$eName] .= "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=date class='form-control' value='$defaultValue' onfocus='this.select()' oonclick=\"this.value='$defaultValue';\"
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);top.$('#result').load($selectorTarget);}\">";
                                        break;
                                }
                                $elStr[$eName] .= "</div class='box-body'>";

                                $itemsElements[$eName] = array("mdlName" => null,
                                    "label" => $eSpec['label'] . " $iLabel ",
                                    "string" => $elStr[$eName],
                                    "editStr" => "",
                                    "addStr" => "",
                                    "bgColor" => $defaultValue == "" ? "#fcfce0" : "#fcfcff",
                                    "helpName" => $helpName,
                                );

                                break;
                        }
                    }

                }
            }
        }


        //endregion
        $tmpMasterValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();


        $main = $main + $tmpMasterValues;

        //bersihkan elemen yang tidak relevan
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $elName => $elSpec) {

                if (!array_key_exists($elName, $elementConfigs)) {
                    $_SESSION[$cCode]['main_elements'][$elName] = null;
                    unset($_SESSION[$cCode]['main_elements'][$elName]);

                    $mainResetList = array($elName,
                        $elName . "__label"
                    );
                    foreach ($mainResetList as $kk) {
                        if (isset($_SESSION[$cCode]['main'][$kk])) {
                            $_SESSION[$cCode]['main'][$kk] = null;
                            unset($_SESSION[$cCode]['main'][$kk]);
                        }
                    }
                }
            }
        }


        $arrCons = array();
        $headerScheme = array();

        $detilSizeBar = array();
        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            if (sizeof($itemsLabelReplacer) > 0) {
                foreach ($itemsLabelReplacer as $key => $keyVal) {
                    $itemLabels[$key] = $_SESSION[$cCode]['items'][$id][$keyVal];
                }
            }
        }

        $paramForceEditable = array();
        if (sizeof($shopingCartParamForceEditable) > 0) {
            foreach ($shopingCartParamForceEditable as $paramsKey => $paramGate) {
                $paramForceEditable = isset($_SESSION[$cCode][$paramsKey]) ? $_SESSION[$cCode][$paramsKey] : array();
                //                arrPrint($paramForceEditable);
                //                cekHitam("key ".$paramsKey." gate ".$paramGate);
            }
        }

        //region efaktur
        $dataFaktur = array();
        if (count($shopingCartFaktur) > 0) {
            if ($_SESSION[$cCode]["main"]["dpp_final"] == 0) {
//                $_SESSION[$cCode]["main"]["skip_faktur"]="true";
                if (isset($shopingCartFaktur["gateTarget"])) {
                    $gateTarget = $shopingCartFaktur["gateTarget"];
                    if (isset($_SESSION[$cCode][$gateTarget])) {
                        $_SESSION[$cCode][$gateTarget] = NULL;
                        unset($_SESSION[$cCode][$gateTarget]);
                    }
                }
            }
            else {
                foreach ($shopingCartFaktur["fields"] as $key_faktur => $faktur_labels) {
                    $dataFaktur[$key_faktur] = isset($_SESSION[$cCode]["main"][$key_faktur]) ? $_SESSION[$cCode]["main"][$key_faktur] : "";
                }
                if (isset($shopingCartFaktur["addFields"])) {
                    $skip_faktur = false;
                    if (isset($_SESSION[$cCode]["main"]["skip_faktur"]) && ($_SESSION[$cCode]["main"]["skip_faktur"] == "true")) {
                        $skip_faktur = true;
                    }
                    foreach ($shopingCartFaktur["addFields"] as $key_faktur_add => $faktur_src_add) {
                        $dataFaktur[$key_faktur_add] = (($skip_faktur == false) && isset($_SESSION[$cCode]["main"][$faktur_src_add])) ? $_SESSION[$cCode]["main"][$faktur_src_add] : "";
                    }
                }

                if (isset($shopingCartFaktur["gateTarget"])) {
                    $gateTarget = $shopingCartFaktur["gateTarget"];
                    $dtp_factor = (isset($_SESSION[$cCode]["main"]["pajak_ditanggung_pemerintah"])) ? $_SESSION[$cCode]["main"]["pajak_ditanggung_pemerintah"] : 0;

                    // Independent Buyer Logic (items6_sum)
                    if (!isset($_SESSION[$cCode][$gateTarget])) {
                        $_SESSION[$cCode][$gateTarget][0] = $dataFaktur;
                    }

                    // Always sync calculated values for Buyer
                    $_SESSION[$cCode][$gateTarget][0]["ppn_final"] = isset($_SESSION[$cCode]["main"]["ppn_netto"]) ? $_SESSION[$cCode]["main"]["ppn_netto"] : 0;
                    $_SESSION[$cCode][$gateTarget][0]["nilai_entry"] = (isset($_SESSION[$cCode][$gateTarget][0]["dpp_final"]) ? $_SESSION[$cCode][$gateTarget][0]["dpp_final"] : 0) + $_SESSION[$cCode][$gateTarget][0]["ppn_final"];

                    // Sync fixed fields
                        foreach ($dataFaktur as $aa => $bb) {
                        if (!in_array($aa, ["eFaktur", "dateFaktur", "skip_faktur"])) {
                            $_SESSION[$cCode][$gateTarget][0][$aa] = $bb;
                        }
                    }
                }

                if (isset($shopingCartFakturInsentif["gateTarget"])) {
                    $gateTargetInsentif = $shopingCartFakturInsentif["gateTarget"];
                    $dtp_factor = (isset($_SESSION[$cCode]["main"]["pajak_ditanggung_pemerintah"])) ? $_SESSION[$cCode]["main"]["pajak_ditanggung_pemerintah"] : 0;

                    // Independent Incentive Logic (items7_sum)
                    if ($dtp_factor > 0) {
                        if (!isset($_SESSION[$cCode][$gateTargetInsentif])) {
                            // Initialize DTP portion
                            $dataFakturInsentif = array();
                            foreach ($shopingCartFakturInsentif["fields"] as $key_faktur => $faktur_labels) {
                                $dataFakturInsentif[$key_faktur] = isset($_SESSION[$cCode]["main"][$key_faktur]) ? $_SESSION[$cCode]["main"][$key_faktur] : "";
                            }
                            $_SESSION[$cCode][$gateTargetInsentif][0] = $dataFakturInsentif;
                        }

                        // Sync calculated values for DTP
                        $_SESSION[$cCode][$gateTargetInsentif][0]["ppn_final"] = isset($_SESSION[$cCode]["main"]["ppn_dpt_nilai"]) ? $_SESSION[$cCode]["main"]["ppn_dpt_nilai"] : 0;
                        $_SESSION[$cCode][$gateTargetInsentif][0]["nilai_entry"] = (isset($_SESSION[$cCode][$gateTargetInsentif][0]["dpp_final"]) ? $_SESSION[$cCode][$gateTargetInsentif][0]["dpp_final"] : 0) + $_SESSION[$cCode][$gateTargetInsentif][0]["ppn_final"];

                        // Sync other fields
                        foreach ($shopingCartFakturInsentif["fields"] as $key_faktur => $faktur_labels) {
                            if (!in_array($key_faktur, ["eFaktur", "dateFaktur", "skip_faktur", "ppn_final", "nilai_entry"])) {
                                if (isset($_SESSION[$cCode]["main"][$key_faktur])) {
                                    $_SESSION[$cCode][$gateTargetInsentif][0][$key_faktur] = $_SESSION[$cCode]["main"][$key_faktur];
                                }
                            }
                        }
                    } else {
                        // Clear DTP portion if inactive
                        if (isset($_SESSION[$cCode][$gateTargetInsentif])) {
                            unset($_SESSION[$cCode][$gateTargetInsentif]);
                        }
                    }
                }
            }

            $cekPPN = isset($shopingCartFaktur["enabledPpn"]) ? $shopingCartFaktur["enabledPpn"] : NULL;
            $show = true;
            if ($cekPPN != NULL) {
                $cekPPN_nilai = isset($_SESSION[$cCode]["main"][$cekPPN]) ? $_SESSION[$cCode]["main"][$cekPPN] : 0;
                $show = ($cekPPN_nilai > 0) ? true : false;
            }
            elseif ($_SESSION[$cCode]["main"]["ppnTransaksi"] == "0") {
                $show = false;
            }

            if (sizeof($_SESSION[$cCode][$gateTarget]) > 0) {
                $ppn_netto = isset($_SESSION[$cCode]["main"]["ppn_netto"]) ? $_SESSION[$cCode]["main"]["ppn_netto"] : 0;
                $total_ppn = 0;
                foreach ($_SESSION[$cCode][$gateTarget] as $ii => $iiSpec) {
                    $total_ppn += $iiSpec["ppn_final"];
                }
                $selisih_ppn = $ppn_netto - $total_ppn;
                $selisih_ppn = ($selisih_ppn < 0) ? ($selisih_ppn * -1) : $selisih_ppn;
                if ($selisih_ppn > 100) {
                    $formulirFakturStyle = array(
                        "bgcolor" => "yellow",
                    );
                }
                else {
                    $formulirFakturStyle = array(
                        "bgcolor" => "#00a65a",
                    );
                }
            }


        }
        //endregion

        //-------------------------------------------------------------------------------
        $items4Label = array();
        if (count($shopingCartPairProdukSrc) > 0) {
//            $addDiskonItems4Label = array(
//                "diskon_persen"=>"DISKON (%)",
//                "diskon_rupiah"=>"DISKON (Rp)",
//            );
            $items4Label = $this->jenisTr == "3675" ? $shopingCartPairProdukSrc : $shopingCartPairProdukSrc + array("subtotal" => "subtotal");
        }
        $elementResetTarget = MODUL_PATH . get_class($this) . "/resetElement/" . $this->jenisTr . "/";
        $itemsElementResetTarget = MODUL_PATH . get_class($this) . "/resetItemsElement/" . $this->jenisTr . "/";
        //-------------------------------------------------------------------------------
        $shoppingCartNoFaktur = isset($this->configUi[$this->jenisTr]['shoppingCartNoFaktur']) ? $this->configUi[$this->jenisTr]['shoppingCartNoFaktur'] : array();
        if (sizeof($shoppingCartNoFaktur) > 0) {
            $shoppingCartNoFakturLabelInfo = $shoppingCartNoFaktur["label_info"];

        }
        //-------------------------------------------------------------------------------

        //-----------------------------------------------------
        $elementTimeStart = microtime(true);
        $rebateData = array();
        if (count($shopingCartRebate) > 0) {
            $mainKey = $shopingCartRebate["mainKey"];
            $this->load->library("Diskon");
            $dc = New Diskon();
            $allowedCadangan = $dc->diskonCadanganSupplierMember($_SESSION[$cCode]["main"][$mainKey]);
//            diskon_khusus_didapat
            if ($allowedCadangan) {
                $_SESSION[$cCode]["main"]["pihakDiskonKhususCode"] = 1;
            }
            else {
                $_SESSION[$cCode]["main"]["pihakDiskonKhususCode"] = 0;
            }
            if (isset($_SESSION[$cCode]["main"]["pihakDiskonKhususCode"]) && $_SESSION[$cCode]["main"]["pihakDiskonKhususCode"] == 1) {
                foreach ($shopingCartRebate["headerFields"] as $h => $hv) {
                    $rebateData[$hv] = isset($_SESSION[$cCode]["main"]["diskon_khusus_didapat"]) ? number_format($_SESSION[$cCode]["main"]["diskon_khusus_didapat"], 0) : 0;
                }

            }
        }


//matiHEre(__LINE__);
        //-----------------------------------------------------
        $shopingCartPairProdukGateLabel = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGateLabel']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGateLabel'] : NULL;
        //-----------------------------------------------------
        $receiptFieldsReference = isset($this->configUi[$this->jenisTr]['receiptFieldsReference']) ? $this->configUi[$this->jenisTr]['receiptFieldsReference'] : array();

        if (sizeof($receiptFieldsReference) > 0) {
            if (isset($receiptFieldsReference["enabled"]) && ($receiptFieldsReference["enabled"] == true)) {
                // PO dari GRN yang dipilih...
                $pairGate = isset($receiptFieldsReference["pairGate"]) ? $receiptFieldsReference["pairGate"] : NULL;
                $receiptDetailFieldsReference = isset($this->configUi[$this->jenisTr]['receiptDetailFieldsReference'][1]) ? $this->configUi[$this->jenisTr]['receiptDetailFieldsReference'][1] : array();
                $receipCartNumFieldsReference = isset($this->configUi[$this->jenisTr]['receipCartNumFieldsReference'][1]) ? $this->configUi[$this->jenisTr]['receipCartNumFieldsReference'][1] : array();
                $receiptSumFieldsReference = isset($this->configUi[$this->jenisTr]['receiptSumFieldsReference'][1]) ? $this->configUi[$this->jenisTr]['receiptSumFieldsReference'][1] : array();
//                arrPrint($receiptSumFieldsReference);
//                arrPrint($receipCartNumFieldsReference);
//                arrPrint($receiptDetailFieldsReference);
                if ($pairGate != NULL) {
                    $this->load->model("MdlTransaksi");
                    $tr = New MdlTransaksi();
                    $tr->setFilters(array());
                    cekHitam($this->jenisTr);

                    $tr->addFilter("transaksi_id in ('" . implode("','", $sesItemsReferencePO) . "')");
                    $trReg = $tr->lookupDataRegistries()->result();
                    if (sizeof($trReg) > 0) {
                        foreach ($trReg as $trRegSpec) {
                            $ref_id = $trRegSpec->transaksi_id;
                            $ref_main = blobDecode($trRegSpec->main);
                            $ref_items = blobDecode($trRegSpec->items);
                            $ref_tableIn_master = blobDecode($trRegSpec->tableIn_master);
                            if (isset($this->configUi[$this->jenisTr]['shopingCartForceItemSelected'])) {
//                                cekBiru(__LINE__);
//                                arrPrint(blobDecode($trRegSpec->items));
                                $item_ref = array();
                                foreach (blobDecode($trRegSpec->items) as $ixs => $iiRefItem) {
                                    foreach ($this->configUi[$this->jenisTr]['shopingCartForceItemSelected'] as $k_ref => $reg_param_val) {
                                        if (in_array($iiRefItem[$k_ref], $reg_param_val)) {
                                            $item_ref[$ixs] = $iiRefItem;
                                        }
                                    }
                                    $_SESSION[$cCode]["items2_sum"] = $item_ref;
                                }
                            }
                            else {
                                $item_ref = blobDecode($trRegSpec->items);
                            }
//                            arrprintwebs($item_ref);
                            $arrRefData[$trRegSpec->transaksi_id] = array(
                                "main" => blobDecode($trRegSpec->main),
                                "items" => $item_ref,
                            );
                            $counters = blobDecode($ref_tableIn_master["counters"]);
                            $id_grn = $sesItemsReferenceGRN_PO[$ref_id];
                            if (isset($counters["stepCode|placeID"])) {
                                foreach ($counters["stepCode|placeID"] as $cc_val) {
                                    $c_val_global = $cc_val;
                                    $arrRefData[$trRegSpec->transaksi_id]["main"]["global_number_reference"] = digit_5($c_val_global);
                                }
                            }
                        }
                    }
                    else {
                        cekKuning("none");
                    }

                }
                else {
                    cekHitam("pair kosong");
                }

                // PO belum GRN dari GRN yang dipilih...
                $tr = new MdlTransaksi();
                $tr->addFilter("next_substep_code<>''");
                $tr->addFilter("sub_step_number>0");
                $tr->addFilter("valid_qty>0");
                $tr->addFilter("transaksi.id in ('" . implode("','", $sesItemsReferencePO) . "')");
                $tmpHist = $tr->lookupRecentUndoneEntriesNoGroup_joined(replaceSession())->result();
                if (sizeof($tmpHist) > 0) {
                    $sub_total = array();
                    foreach ($tmpHist as $row) {
                        $harga = $row->produk_ord_hrg;
//                        $qty = $row->produk_ord_jml;
                        $qty = $row->valid_qty;
                        $sub_harga = $harga * $qty;
                        if (!isset($sub_total[$row->transaksi_id])) {
                            $sub_total[$row->transaksi_id] = 0;
                        }
                        $sub_total[$row->transaksi_id] += $sub_harga;
                        $modul = isset($this->configMasterUi[$row->jenis_master]['modul']) ? $this->configMasterUi[$row->jenis_master]['modul'] : NULL;
                        $ppnFactorTransaksi = ($arrRefData[$row->transaksi_id]["main"]["ppnFactor"] > 0) ? $arrRefData[$row->transaksi_id]["main"]["ppnFactor"] : 0;
                        $mainBelumGrn[$row->transaksi_id] = array(
                            "nomer" => NULL,
                            "nomer_top" => $row->nomer_top,
                            "transaksi_id" => 0,
                            "transaksi_id_po" => $row->transaksi_id,
                            "transaksi_no" => $row->nomer,
                            "refNum" => 0,
                            "fulldate_po" => $row->fulldate,
                            "tagihan" => $sub_total[$row->transaksi_id],
                            "refValue" => 0,
                            "terbayar" => 0,
                            "diskon" => 0,
                            "sisa" => $sub_total[$row->transaksi_id],
                            "notes" => "<span class=meta>PO belum GRN, silahkan hubungi pihak Pembelian.</span>",
                            "referensi_po_uangmuka" => 0,
                            "referensi_titipan_po_uangmuka" => 0,
                            "background_color" => "yellow",
                            "jenis_master" => $row->jenis_master,
                            "modul_path" => base_url() . "$modul/",
                            "harga" => $sub_total[$row->transaksi_id],
                            "ppn" => ($ppnFactorTransaksi / 100) * $sub_total[$row->transaksi_id],
                            "hpp_nppn" => ((100 + $ppnFactorTransaksi) / 100) * $sub_total[$row->transaksi_id],
                        );
                        $itemsBelumGrn[$row->transaksi_id][$row->produk_id] = array(
//                            "nomer" => NULL,
//                            "nomer_top" => $row->nomer_top,
//                            "transaksi_id" => 0,
//                            "transaksi_id_po" => $row->transaksi_id,
//                            "transaksi_no" => $row->nomer,
//                            "refNum" => 0,
//                            "fulldate_po" => $row->fulldate,
//                            "tagihan" => $sub_total[$row->transaksi_id],
//                            "refValue" => 0,
//                            "terbayar" => 0,
//                            "diskon" => 0,
//                            "sisa" => $sub_total[$row->transaksi_id],
//                            "notes" => "<span class=meta>PO belum GRN, silahkan hubungi pihak Pembelian.</span>",
//                            "referensi_po_uangmuka" => 0,
//                            "referensi_titipan_po_uangmuka" => 0,
//                            "background_color" => "yellow",
//                            "jenis_master" => $row->jenis_master,
//                            "modul_path" => base_url() . "$modul/",
                            "harga" => $harga,
                            "ppn" => 0,
                            "hpp_nppn" => $harga,
                            "id" => $row->produk_id,
                            "produk_kode" => $row->produk_kode,
                            "kode" => $row->produk_kode,
                            "nama" => $row->produk_nama,
                            "jml" => $qty,
                            "satuan" => $row->satuan,
                            "subtotal" => $harga * $qty,
                        );
                    }
                }
            }
        }
        //-----------------------------------------------------
        $receiptAddDpp = isset($this->configUi[$this->jenisTr]["receiptAddDpp"][1]) ? $this->configUi[$this->jenisTr]["receiptAddDpp"][1] : array();
        //-----------------------------------------------------
//arrPrintCyan($elementMutasi);
//arrPrintPink($addRows);

        $sumRows = isset($this->configUi[$this->jenisTr]['shoppingCartSumFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartSumFields'][$stepNumber] : $this->configLayout[$this->jenisTr]['receiptSumFields'][$stepNumber];

        $data = array(
            "jenisTr" => $this->jenisTr,
            "main" => $main,
            "showScheme" => $arrCons,
            "showItems" => $showItems,
            "headerScheme" => $headerScheme,
            "noteEnabled" => $noteEnabled,
            "noteType" => $noteType,
            "noteRecorder" => MODUL_PATH . get_class($this) . "/recordItemColumn/" . $this->jenisTr . "/note",
            "imageEnable" => $imageEnable,
            "imageType" => $imageType,
            "imageRecorder" => MODUL_PATH . get_class($this) . "/recordImage/" . $this->jenisTr . "/images",
            "pairedItemEnabled" => isset($pairedItemEnabled) ? $pairedItemEnabled : array(),
            "pairedItemRecorder" => MODUL_PATH . get_class($this) . "/$pairedItemRecorder/" . $this->jenisTr . "/note",
            "addValues" => $addValues,
            "items" => $items,
            "items2" => $items2,
            "items3" => $items3,
            "items4Label" => $items4Label,
            "items4" => isset($_SESSION[$cCode]["items4_sum"]) ? $_SESSION[$cCode]["items4_sum"] : array(),
            "itemLabels" => $itemLabels,
            "itemLabels2" => $itemLabels2,
            "itemLabels3" => $itemLabels3,
            "numLabels" => $itemNumLabels,
            "detilSizeBar" => $detilSizeBar,
            "sumRows" => $sumRows,
            "sumRows2" => $sumRows2,
            "sumRows3" => isset($this->configUi[$this->jenisTr]['shoppingCartSumFields3'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartSumFields3'][$stepNumber] : isset($this->configLayout[$this->jenisTr]['receiptSumFields3'][$stepNumber]) ? $this->configLayout[$this->jenisTr]['receiptSumFields3'][$stepNumber] : array(),
            "sumSpec2" => $sumSpec2,
            "sumType2" => $sumType2,
            "sumRowAction" => base_url() . "ValueGate/evalFees/" . $this->jenisTr,
            "editableFields" => $editableFields,
            "editableFields2" => $editableFields2,
            "elements" => $elements,
            "elementConfigs" => $elementConfigs,
            "elementConfigMutasi" => isset($elementMutasi) ? $elementMutasi : array(),
            "inputs" => $inputs,
            "inputLabels" => $inputLabels,
            "grandTotal" => isset($_SESSION[$cCode]['main']['grand_total']) ? $_SESSION[$cCode]['main']['grand_total'] : 0,

            "resetLink" => MODUL_PATH . get_class($this) . "/reset/" . $this->jenisTr,
            "minValues" => $minValue,
            "addRows" => $addRows,
            "addRowLabels" => $addRowLabels,
            "addRowHiddens" => $addRowHiddens,
            "addBackground" => $addRowBackground,
            "avoidRemove" => $avoidRemove,
            "elementResetTarget" => $elementResetTarget,
            "pairedItemField" => isset($pairedItemField) ? $pairedItemField : array(),
            "pairedValue" => isset($_SESSION[$cCode]['pairs']) ? $_SESSION[$cCode]['pairs'] : array(),
            "unionSelectors" => $unionSelectors,
            "keyUpEvents" => $keyUpEvents,
            "selectedPrices" => $selectedPrices,
            "isi_modal" => "",
            "keyupAction" => isset($this->configUi[$this->jenisTr]['keyupAction']) ? $this->configUi[$this->jenisTr]['keyupAction'] : false,
            "fixedNote" => $fixedNote,
            "fixedNoteTop" => $fixedNoteTop,
            "fixedNoteLink" => $fixedNoteLink,

            "items2_sum_kurang" => isset($items2_sum_kurang) ? $items2_sum_kurang : array(),
            "paramsForceEditable" => $paramForceEditable,
            //---MILIK SETTLEMENT-----------------------------
            "isSettlement" => $isSettlement,

            "headerMainByTransaksi" => isset($this->configUi[$this->jenisTr]['settlementHeaderByTransaksi'][$stepNumber]) ? $this->configUi[$this->jenisTr]['settlementHeaderByTransaksi'][$stepNumber] : array(),

            "headerByTransaksi" => isset($this->configUi[$this->jenisTr]['settlementByTransaksi'][$stepNumber]) ? $this->configUi[$this->jenisTr]['settlementByTransaksi'][$stepNumber] : array(),
            "headerByMenu" => isset($this->configUi[$this->jenisTr]['settlementByMenu'][$stepNumber]) ? $this->configUi[$this->jenisTr]['settlementByMenu'][$stepNumber] : array(),
            "headerByProduk" => "",
            "headerByBahan" => isset($this->configUi[$this->jenisTr]['settlementByBahan'][$stepNumber]) ? $this->configUi[$this->jenisTr]['settlementByBahan'][$stepNumber] : array(),
            "headerByPayment" => isset($this->configUi[$this->jenisTr]['settlementByPayment'][$stepNumber]) ? $this->configUi[$this->jenisTr]['settlementByPayment'][$stepNumber] : array(),

            "summaryByTransaksi" => isset($this->configUi[$this->jenisTr]['summaryByTransaksi'][$stepNumber]) ? $this->configUi[$this->jenisTr]['summaryByTransaksi'][$stepNumber] : array(),
            "labelByMenu" => isset($this->configUi[$this->jenisTr]['labelByMenu'][$stepNumber]) ? $this->configUi[$this->jenisTr]['labelByMenu'][$stepNumber] : array(),
            "labelByTransaksi" => isset($this->configUi[$this->jenisTr]['labelByTransaksi'][$stepNumber]) ? $this->configUi[$this->jenisTr]['labelByTransaksi'][$stepNumber] : array(),
            "labelByPayment" => isset($this->configUi[$this->jenisTr]['labelByPayment'][$stepNumber]) ? $this->configUi[$this->jenisTr]['labelByPayment'][$stepNumber] : array(),
            "labelByBahan" => isset($this->configUi[$this->jenisTr]['labelByBahan'][$stepNumber]) ? $this->configUi[$this->jenisTr]['labelByBahan'][$stepNumber] : array(),
            "labelTotal" => "",
            "settlementByTransaksi" => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
            "settlementByTransaksiProduk" => isset($_SESSION[$cCode]['items5_sum']) ? $_SESSION[$cCode]['items5_sum'] : array(),
            "settlementByMenu" => isset($_SESSION[$cCode]['items6_sum']) ? $_SESSION[$cCode]['items6_sum'] : array(),
            "settlementByBahan" => isset($_SESSION[$cCode]['items9_sum']) ? $_SESSION[$cCode]['items9_sum'] : array(),
            "settlementByPayment" => isset($_SESSION[$cCode]['items7_sum']) ? $_SESSION[$cCode]['items7_sum'] : array(),
            "settlementTotal" => array(),
            //--------------------------------------------
            "arrHeaderElement" => isset($arrHeaderElement) ? $arrHeaderElement : array(),
            "arrHeaderElementJenis" => $this->jenisTr,

            "subItemLabels" => $shoppingCartFieldsSubItems,
            "items2_komposisi" => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),

            "itemElements" => isset($itemsElements) ? $itemsElements : array(),
            "itemElementConfigs" => isset($elementItemsAutoConfigs) ? $elementItemsAutoConfigs : array(),
            "itemElementResetTarget" => isset($itemsElementResetTarget) ? $itemsElementResetTarget : "",
            //--------------
            "shoppingCartAdvanceItems" => $shoppingCartAdvanceItems,
            "shoppingCartAdvanceItemsLabel" => isset($shoppingCartAdvanceItemsLabel) ? $shoppingCartAdvanceItemsLabel : array(),
            "shoppingCartAdvanceSubItemsLabel" => isset($shoppingCartAdvanceSubItemsLabel) ? $shoppingCartAdvanceSubItemsLabel : array(),
            "shoppingCartAdvanceNumType" => isset($shoppingCartAdvanceNumType) ? $shoppingCartAdvanceNumType : array(),
            "shoppingCartAdvanceEditableField" => isset($shoppingCartAdvanceEditableField) ? $shoppingCartAdvanceEditableField : array(),
            "shoppingCartAdvanceItemsSelector" => isset($shoppingCartAdvanceItemsSelector) ? MODUL_PATH . $shoppingCartAdvanceItemsSelector . "/" . $this->jenisTr : array(),
            "shoppingCartAdvanceItemsRemove" => isset($shoppingCartAdvanceItemsRemove) ? MODUL_PATH . $shoppingCartAdvanceItemsRemove . "/" . $this->jenisTr : array(),
            "shoppingCartAdvanceItemsAdd" => isset($shoppingCartAdvanceItemsAdd) ? MODUL_PATH . $shoppingCartAdvanceItemsAdd . "/" . $this->jenisTr : array(),
            "subAdvanceItems" => isset($_SESSION[$cCode]['items4'][$_SESSION[$cCode]['main']['refID']]) ? $_SESSION[$cCode]['items4'][$_SESSION[$cCode]['main']['refID']] : array(),// khusus bayar po jasa projek subdetail dimasukkan ke session items4
            //--------------
            "showNotes" => isset($this->configUi[$this->jenisTr]['showNotes']) ? $this->configUi[$this->jenisTr]['showNotes'] : false,
            "column_recorder" => MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr,
            "default_description" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            //--------------
            "shoppingCartMode" => $shoppingCartMode,
            "shopingCartFakturParam" => $shopingCartFaktur,
            "shopingCartFakturInsentifParam" => $shopingCartFakturInsentif,
            "shopingCartFakturItems" => $dataFaktur,
            "shopingCartFakturTarget" => isset($shopingCartFaktur["editProcess"]) ? $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr : "",
            "formulirFaktur" => isset($_SESSION[$cCode][$gateTarget]) ? $_SESSION[$cCode][$gateTarget] : array(),
            "formulirFakturInsentif" => (isset($shopingCartFakturInsentif["gateTarget"]) && isset($_SESSION[$cCode][$shopingCartFakturInsentif["gateTarget"]])) ? $_SESSION[$cCode][$shopingCartFakturInsentif["gateTarget"]] : array(),
            "columnRecorderTarget" => MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr,
            "viewDescriptionNote" => isset($this->configUi[$this->jenisTr]['viewDescriptionNote']) ? $this->configUi[$this->jenisTr]['viewDescriptionNote'] : false,
            "tipe_transaksi_sumber" => $_SESSION[$cCode]["main"]["tipe_transaksi_sumber"],
            "jenis_transaksi_sumber" => $_SESSION[$cCode]["main"]["jenisTr"],
            "transaksi_injected_entry" => $_SESSION[$cCode]["main"]["transaksi_injected_entry"],
            "koreksiRecorderTarget" => MODUL_PATH . get_class($this) . "/recordKoreksi/" . $this->jenisTr,
            "link_reload" => MODUL_PATH . get_class($this) . "/buildValues/" . $this->jenisTr . "?ohyes=ohno",

            "showFormulirFaktur" => isset($show) ? $show : false,
            "transaksi_jenis" => $this->jenisTr,

            "cloneFormulirFaktur" => MODUL_PATH . get_class($this) . "/cloneFormulirFaktur/" . $this->jenisTr,
            "cloneFormulirFakturDelete" => MODUL_PATH . get_class($this) . "/deleteCloneFormulirFaktur/" . $this->jenisTr,
            "formulirFakturStyle" => isset($formulirFakturStyle) ? $formulirFakturStyle : array(),

            "shoppingCartEditableFieldsType" => isset($shoppingCartEditableFieldsType) ? $shoppingCartEditableFieldsType : array(),
            "shoppingCartNoFakturLabelInfo" => isset($shoppingCartNoFakturLabelInfo) ? $shoppingCartNoFakturLabelInfo : NULL,

            "additionalDiskonRecorderTarget" => MODUL_PATH . get_class($this) . "/recordAdditionalDiskon/" . $this->jenisTr,

            "shopingCartPairProdukGateLabel" => isset($shopingCartPairProdukGateLabel) ? $shopingCartPairProdukGateLabel : "Produk yang telah diterima",

            "receiptDetailFieldsReference" => isset($receiptDetailFieldsReference) ? $receiptDetailFieldsReference : array(),
            "receipCartNumFieldsReference" => isset($receipCartNumFieldsReference) ? $receipCartNumFieldsReference : array(),
            "receiptSumFieldsReference" => isset($receiptSumFieldsReference) ? $receiptSumFieldsReference : array(),
            "arrRefData" => isset($arrRefData) ? $arrRefData : array(),
            "mainBelumGrn" => isset($mainBelumGrn) ? $mainBelumGrn : array(),
            "itemsBelumGrn" => isset($itemsBelumGrn) ? $itemsBelumGrn : array(),
            /*--------ppnFactor--------------------------------------------------------------
             * untuk membuat nilai true false lakukan dari heAccounting pairPajak/value
             * ------------------------------------------------------------------------------*/
            "optionalPpn" => count($pairPajakValueDefault) > 1 ? true : false,
            "pairPajakValueDefault" => $pairPajakValueDefault,
            "ppnFactor" => isset($_SESSION[$cCode]['main']['ppnFactor']) ? $_SESSION[$cCode]['main']['ppnFactor'] : '',
            "ppnFactorInclude" => isset($_SESSION[$cCode]['main']['ppnFactorInclude']) ? $_SESSION[$cCode]['main']['ppnFactorInclude'] : '',
            //-----
            "shopingCartPairSubItemSrc" => isset($this->configUi[$this->jenisTr]['shopingCartPairSubItemSrc']) ? $this->configUi[$this->jenisTr]['shopingCartPairSubItemSrc'] : array(),
            "shopingCartPairSubItemSrcData" => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
            //-----
            "shoppingCartAdvanceSubEditableAdditional" => isset($shoppingCartAdvanceSubEditableAdditional) ? $shoppingCartAdvanceSubEditableAdditional : array(),
            "shoppingCartAdvanceSubEditableAdditionalSelector" => isset($shoppingCartAdvanceSubEditableAdditionalSelector) ? MODUL_PATH . $shoppingCartAdvanceSubEditableAdditionalSelector . "/" . $this->jenisTr : NULL,

            //--------------
            "arrItemTidakDibayar" => $arrItemTidakDibayar,
            "shopingCartPaymentItemsColor" => $shopingCartPaymentItemsColor,
            "selectedPihak" => $_SESSION[$cCode]["main"]["pihakID"],
            "showReabate" => $rebateData,

            "receiptAddDpp" => $receiptAddDpp,
            "inputOptions" => $shoppingCartEditableFieldsOptions,
            "shopingCartTaxSrc" => isset($_SESSION[$cCode]["itemSrc"]) ? $_SESSION[$cCode]["itemSrc"] : array(),
            "shopingCartTaxtarget" => isset($_SESSION[$cCode]["itemsTarget1"]) ? $_SESSION[$cCode]["itemsTarget1"] : array(),
            "shopingCartEmbededTaxItem" => $shopingCartEmbededTaxItem,

            "optionPembayaran" => isset($_SESSION[$cCode]["main"]["optionPembayaran"]) ? $_SESSION[$cCode]["main"]["optionPembayaran"] : 0,
            "dtp_config" => isset($this->configUi[$this->jenisTr]["pajak_ditanggung_pemerintah"]) ? $this->configUi[$this->jenisTr]["pajak_ditanggung_pemerintah"] : array(),
            "shopingCartFakturInsentifParam" => $shopingCartFakturInsentif,
//            "pajak_ditanggung_pemerintah" => isset($this->config[]["pajak_ditanggung_pemerintah"]) ? $_SESSION[$cCode]["main"]["optionPembayaran"] : 0,


        );

        //==selector for pairedItem
        if (sizeof($pairedItem) > 0) {
            if (isset($pairedItem['enabled']) && ($pairedItem['enabled'] == true)) {
                $elementTimeStart = microtime(true);
                $mdlName = $pairedItem['mdlName'];
                $srcKey = isset($pairedItem['srcKey']) ? $pairedItem['srcKey'] : "";
                $srcLabel = isset($pairedItem['srcLabel']) ? $pairedItem['srcLabel'] : array();

                $this->load->model("Mdls/$mdlName");
                $pro = new $mdlName();

                if (isset($pairedItem['mdlFilter']) && (sizeof($pairedItem['mdlFilter']) > 0)) {
                    foreach ($pairedItem['mdlFilter'] as $filter) {
                        $pro->addFilter($filter);
                    }
                }

                $selItems = array();
                $tmp = $pro->lookupAll()->result();

//                cekMerah($this->db->last_query());
//                heGetTimedQuery($elementTimeStart, __LINE__);

                //                cekmerah($this->db->last_query());
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $row) {
                        if (sizeof($srcLabel) > 0) {
                            foreach ($srcLabel as $label) {
                                $kode = isset($row->kode) ? $row->kode : "--";
                                $folder = isset($row->folders_nama) ? $row->folders_nama : "--";
                                $keterangan = isset($row->keterangan) ? $row->keterangan : "--";
                                $barcode = isset($row->barcode) ? $row->barcode : "--";
                                //                                $selItems[$row->$srcKey] = $row->$label . " " . $kode;
                                $selItems[$row->$srcKey] = $row->$label;
                                $selItemsKode[$row->$srcKey] = $kode;
                                $selItemsFolder[$row->$srcKey] = $folder;
                                $selItemsKeterangan[$row->$srcKey] = $keterangan;
                                $selItemsBarcode[$row->$srcKey] = $barcode;
                            }
                        }
                    }
                }

                //                arrPrint($tmp);

                $data['selItems'] = $selItems;
                $data['selItemsKode'] = $selItemsKode;
                $data['selItemsFolder'] = $selItemsFolder;
                $data['selItemsKeterangan'] = $selItemsKeterangan;
                $data['selItemsBarcode'] = $selItemsBarcode;
                //                $data['pairedItems'] = isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array();
                $data['pairedItems'] = isset($_SESSION[$cCode][$pairedItemTarget]) ? $_SESSION[$cCode][$pairedItemTarget] : array();
                //                $data['pairedItems'] = isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array();
                // arrPrintWebs($data);
            }
        }


        $previewJurnalConfig = isset($this->configUi[$this->jenisTr]["previewJurnal"]) ? $this->configUi[$this->jenisTr]["previewJurnal"] : array();
        if (isset($previewJurnalConfig["enabled"]) && $previewJurnalConfig["enabled"] == true) {
            $previewJurnal = previewJurnal($this->jenisTr);
            $previewJurnal['header'] = array(//                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $data['previewJurnal'] = $previewJurnal;
        }

        $columnRecorderTarget = isset($this->configUi[$this->jenisTr]["columnRecorderTarget"]) ? $this->configUi[$this->jenisTr]["columnRecorderTarget"] : false;
        if ($columnRecorderTarget == true) {
            $data["columnRecorderTargetStatus"] = true;
            $data["columnRecorderTarget"] = MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr . "/";
            $data["columnRecorderTargetIsi"] = isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "";
        }

        if ($_SESSION[$cCode]['main']['tipe_transaksi_sumber'] == 1) {// ini pembayaran pemindahbukuan
            foreach ($_SESSION[$cCode]['add_rows'] as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }
            $shopingCartReload = true;
        }

        if ($shopingCartReload) {
            //shopingCartReload
            $elementTimeStart = microtime(true);
            $this->load->helper("he_value_builder");
            fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, array(), my_ppn_factor());
//            heGetTimedQuery($elementTimeStart, __LINE__);
//            cekMerah("get: " . $_GET['stop1']);
//            cekMerah("get: " . $_GET['stop2']);
            if (isset($_GET['stop1']) && $_GET['stop1'] == 1) {
                // custom dulu....
//                if($this->jenisTr == "483"){
//                    if (isset($_GET['stop2']) && $_GET['stop2'] == 1) {
//
//                    }
//                    else{
//                        if($_SESSION[$cCode]["main"]["nilai_entry"] != $_SESSION[$cCode]["main"]["tagihan_bayar_after_uang_muka_norelasi"]){
//                            echo "<script>";
//                            echo "setTimeout(function(){top.$('div#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0&stop1=1&stop2=1');},400) ";
//                            echo "</script>";
//                        }
//                    }
//
//                }
            }
            else {
                echo "<script>";
                echo "setTimeout(function(){top.$('div#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0&stop1=1');},400) ";
                echo "</script>";
            }
        }

//        arrPrint($_SESSION[$cCode]['main_elements']);
//        matiHere(__LINE__);
//        $this->buildValues();
        if (sizeof($_SESSION[$cCode]["items"]) > 0) {
            foreach ($_SESSION[$cCode]["items"] as $trid => $spec) {
                if ($trid == 455563) {
                    // pph tidak ada, 0
                    $_SESSION[$cCode]["main"]["pph21_nilai"] = 0;
                    $_SESSION[$cCode]["main"]["pph23_nilai"] = 0;
                }
            }
        }


        if ($isMob) {
            if (isset($this->configUi[$this->jenisTr]['isMobile_redirect'])) {

                echo "<script>
                        top.window.location.replace('" . base_url() . "MobileDev/createForm/" . $this->jenisTr . "');
                    </script>";
                die();
                redirect(base_url() . "MobileDev/createForm/" . $this->jenisTr);
            }
        }

        $this->load->view("shoppingCart", $data);

    }

    public function fetchModelDescription()
    {
        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(4);
        $id = $this->uri->segment(5);
        $label = unserialize(base64_decode($_GET['label']));
        $labelSrc = unserialize(base64_decode($_GET['labelSrc']));
        $descSrc = unserialize(base64_decode($_GET['desc']));

        $this->load->model("Mdls/" . $mdlName);
        $oo = new $mdlName();
        $oo->addFilter("id='$id'");
        $tmp = $oo->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            //==pecahkan label
            $strLabel = "";
            if (strlen($labelSrc) > 0) {
                $exLabel = explode("+", $labelSrc);
                foreach ($exLabel as $f) {
                    $strLabel .= $tmp[0]->$f . " ";
                }
            }

            //==pecahkan desc
            $strDesc = "";
            if (strlen($descSrc) > 0) {
                $exLabel = explode("+", $descSrc);
                foreach ($exLabel as $f) {
                    $strDesc .= $tmp[0]->$f . " ";
                }
            }

        }

        if (!isset($_SESSION[$cCode]['main_applets'])) {
            $_SESSION[$cCode]['main_applets'] = array();
        }
        $_SESSION[$cCode]['main_applets'][$mdlName] = array("key" => $id,
            "label" => $label,
            "labelValue" => $strLabel,
            "description" => $strDesc,
        );
        echo "<script>";
        echo "top.getData('" . base_url() . "_shoppingCart/viewCart/$jenisTr/?kAhHJASAGHSGfags=kak','shopping_cart')";
        echo "</script>";


    }

    public function fetchElement()
    {

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $mdlName = $this->uri->segment(6);
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        $key = $_GET['key'];
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        heFetchElement_modul($jenisTr, $elName, $mdlName, $key, $this->configUiJenis);
// matiHEre();
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        // $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        // heInitGates_he_cart($this->jenisTr, $initMasterValues);
        //        mati_disini("kakakaak");
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }

    public function recordFieldElement()
    {
        $id = isset($_GET["id"]) ? $_GET["id"] : 0;
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $val = ($_GET['val']);
        $elementConfigs = isset($this->configUi[$jenisTr]['receiptElements']) ? $this->configUi[$jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->configUi[$jenisTr]['relativeElements']) ? $this->configUi[$jenisTr]['relativeElements'] : array();
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        heRecordElement_modul($jenisTr, $elName, $val, $configUiJenis);

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    public function resetElement()
    {

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $mdlName = $this->uri->segment(6);

        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $elementConfig = $configUiJenis["receiptElements"];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        $_SESSION[$cCode]['main'][$elName] = null;
        $_SESSION[$cCode]['main'][$elName . "__label"] = null;
        $_SESSION[$cCode]['tableIn_master_values'][$elName] = null;
        $_SESSION[$cCode]['tableIn_master_values'][$elName . "__label"] = null;

        //==reset kloningan elemen di main dan kroni2nya
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            foreach ($_SESSION[$cCode]['main'] as $key => $val) {
                if (strpos($key, $elName . "__") !== false) {
                    $_SESSION[$cCode]['main'][$key] = null;
                    unset($_SESSION[$cCode]['main'][$key]);
                }
            }
        }
        if (sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
            foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                if (strpos($key, $elName . "__") !== false) {
                    $_SESSION[$cCode]['tableIn_master_values'][$key] = null;
                    unset($_SESSION[$cCode]['tableIn_master_values'][$key]);
                }
            }
        }


        unset($_SESSION[$cCode]['main'][$elName]);
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
// arrPrint($elementConfig[$elName]);

        if (isset($elementConfig[$elName]["pairMethod"]["calculate"]["target"])) {
            $keyPairMethode = $elementConfig[$elName]["pairMethod"]["calculate"]["target"];
            // matiHEre($keyPairMethode);
            unset($_SESSION[$cCode]['main'][$keyPairMethode]);
            unset($_SESSION[$cCode]['add_rows'][$keyPairMethode]);
            unset($_SESSION[$cCode]['tableIn_master_values'][$keyPairMethode]);
            // matiHere($elName);
        }
        // matiHEre($elName);

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        // echo "<script>";
        // echo "top.fillBoxes();";
        // echo "</script>";
        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function reset()
    {
        $cCode = $this->cCode;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $mdlName = isset($lockerConfig['mdlName']) ? $lockerConfig['mdlName'] : "MdlLockerStock";
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;

        $id = isset($_GET["id"]) ? $_GET["id"] : "0";
        $cCode = $this->cCode;
        $this->load->model("Mdls/" . $mdlName);
        if (isset($_SESSION[$cCode]['items'])) {
            foreach ($_SESSION[$cCode]['items'] as $id => $item) {
                if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {

                    $this->db->trans_start();

                    $c = new $mdlName();
                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id'], $this->session->login['toko_id']);
                    $where = array(
                        "id" => $array_hold_sebelumnya['id'],
                    );
                    $data_hold = array(
                        "jumlah" => 0,
                    );

                    $c->updateData($where, $data_hold);
                    cekBiru($this->db->last_query());


                    $c = new $mdlName();
                    $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id'], $this->session->login['toko_id']);
                    cekHijau($this->db->last_query());


                    $where = array(
                        "id" => $array_active_sebelumnya['id'],
                    );
                    $data_active = array(
                        "jumlah" => $array_active_sebelumnya['jumlah'] + $array_hold_sebelumnya['jumlah'],
                    );
                    $c->updateData($where, $data_active);

                    $this->db->trans_complete() or die("Gagal bro");
                }


//                cekkuning('memulai reset');
                $detailResetList = array(
                    "items",
                    "items2",
                    "items2_sum",
                    "tableIn_detail",
                    "tableIn_detail2",
                    "tableIn_detail2_sum",
                    "tableIn_detail_values",
                    "tableIn_detail_values2",
                    "tableIn_detail_values2_sum",
                );
                foreach ($detailResetList as $sSName) {
//                    cekkuning("resetting $sSName");
                    $_SESSION[$cCode][$sSName][$id] = null;
                    unset($_SESSION[$cCode][$sSName][$id]);
                }
            }

        }
//matiHere(__LINE__);

        $detailResetList = array(
            "items",
            "items2",
            "items2_sum",
            "items3",
            "items3_sum",
            "rsltItems",
            "tableIn_detail",
            "tableIn_detail2",
            "tableIn_detail2_sum",
            "tableIn_detail_rsltItems",
            "tableIn_detail_values",
            "tableIn_detail_values2",
            "tableIn_detail_values2_sum",
            "tableIn_detail_values_rsltItems",
            "tableIn_master",
            "tableIn_master_values",
        );
        foreach ($detailResetList as $sSName) {
            $_SESSION[$cCode][$sSName] = null;
            unset($_SESSION[$cCode][$sSName]);
        }

        //reset main juga
        if (isset($_SESSION[$cCode]['main'])) {
            unset($_SESSION[$cCode]);

        }

        //reset mode juga
        if (isset($_SESSION[$cCode]['mode'])) {
            unset($_SESSION[$cCode]);
        }

        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        //init sesssion
        $initMaster = array(
            "olehID" => $this->session->login['id'],
            "olehName" => $this->session->login['nama'],
            "placeID" => $this->session->login['cabang_id'],
            "placeName" => $this->session->login['cabang_nama'],
            "divID" => $this->session->login['div_id'],
            "divName" => $this->session->login['div_nama'],
            "cabangID" => $this->session->login['cabang_id'],
            "cabangName" => $this->session->login['cabang_nama'],
            "gudangID" => $this->session->login['gudang_id'],
            "gudangName" => $this->session->login['gudang_nama'],
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            "jenisTrName" => $this->jenisTrName,
            "stepNumber" => $stepNum,
            "stepCode" => $this->configUi[$this->jenisTr]['steps'][$stepNum]['target'],
            "dtime" => date("Y-m-d H:i:s"),
            "fulldate" => date("Y-m-d"),

            "sellerID" => $this->session->login['id'],
            "sellerName" => $this->session->login['nama'],
        );
        foreach ($initMaster as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
            //            $_SESSION[$cCode]['main'][$key] = $val;
        }
//        cekkuning("done resetting");
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        //-----------------------------------------------------
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == NULL)) {
            $cCode = $this->cCode;
            $dtime_now = dtimeNow();
            $dtime_now_ex = explode(" ", $dtime_now);
            $date_now = str_replace("-", "", $dtime_now_ex[0]);
            $time_now = str_replace(":", "", $dtime_now_ex[1]);
            $bookingNumber = "$date_now" . "$time_now";
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------
// matiHere(__LINE__);
        if (isset($_GET["noreload"]) && $_GET["noreload"] == 1) {

        }
        else {
            echo "<script>";
            echo "if(typeof top.fillBoxes !=='undefined'){ top.fillBoxes() }";
            echo "else{ top.location.reload() };";
            echo "</script>";
        }


//        cekkuning("done re-filling");
    }

    public function recordFieldInput()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $elementConfigs = isset($this->configUi[$jenisTr]['receiptElements']) ? $this->configUi[$jenisTr]['receiptElements'] : array();
        $id = isset($_GET["id"]) ? $_GET["id"] : "0";

        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;


        $val = ($_GET['val']);

        if (!isset($_SESSION[$cCode]['main_inputs'])) {
            $_SESSION[$cCode]['main_inputs'] = array();
        }
        $_SESSION[$cCode]['main_inputs'][$elName] = $val;

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $val;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        //        $_SESSION[$cCode]["main"][$elName] = $val;


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    public function recordAddRow()
    {

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $rowName = $this->uri->segment(5);
        $val = ($_GET['val']);
        $mains = $_SESSION[$cCode]['main'];
        $mainGates = $_SESSION[$cCode];
        $total_diskon = $mains['akumDisc'] + $val;

        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $tipe_transaksi_sumber = isset($_SESSION[$cCode]['main']['tipe_transaksi_sumber']) ? $_SESSION[$cCode]['main']['tipe_transaksi_sumber'] : 0;

        if ($tipe_transaksi_sumber == 1) {

        }
        else {
            $shopingCartPairProdukCheckedValidate = isset($configUiJenis["shopingCartPairProdukCheckedValidate"]) ? $configUiJenis["shopingCartPairProdukCheckedValidate"] : array();
            if (sizeof($shopingCartPairProdukCheckedValidate) > 0) {
                if (isset($shopingCartPairProdukCheckedValidate["enabled"]) && ($shopingCartPairProdukCheckedValidate["enabled"] == true)) {
                    $source = $shopingCartPairProdukCheckedValidate["source"];
                    $label = $shopingCartPairProdukCheckedValidate["label"];
                    if (!isset($_SESSION[$cCode]["main"][$source]) || ($_SESSION[$cCode]["main"][$source] == 0)) {
                        $msg = "$label Code: " . __LINE__;
                        die(lgShowAlertMerah($msg));
                    }
                }
            }
        }

        // validasi
        $val_f = number_format($val, 0, ",", ".");

        switch ($rowName) {
            case "credit_note_dipakai":
                if ($rowName == "credit_note_dipakai") {
                    if ($val > $_SESSION[$cCode]['main']['creditAmount__sisa']) {
                        $saldo = $_SESSION[$cCode]['main']['creditAmount__sisa'];
                        $saldo_f = number_format($saldo, 0, ",", ".");
                        $msg = "Saldo Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                        $msg .= " Saldo saat ini $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                        die(lgShowAlertMerah($msg));
                    }
//                    if ($val > $_SESSION[$cCode]['main']['sisa']) {
//                        $saldo = $_SESSION[$cCode]['main']['sisa'];
//                        $saldo_f = number_format($saldo, 0, ",", ".");
//                        $msg = "Penggunaan Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " melebihi tagihan yang akan dibayar.";
//                        $msg .= " Nilai tagihan $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
//                        die(lgShowAlertMerah($msg));
//                    }
                    if ($val > $_SESSION[$cCode]['main']['tagihan_bayar_after_biaya']) {
                        $saldo = $_SESSION[$cCode]['main']['tagihan_bayar_after_biaya'];
                        $saldo_f = number_format($saldo, 0, ",", ".");
                        $msg = "Penggunaan Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " melebihi tagihan yang akan dibayar.";
                        $msg .= " Nilai tagihan $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                        die(lgShowAlertMerah($msg));
                    }
                }
                break;
            case "credit_note_diskon":
                if ($val > $_SESSION[$cCode]['main']['creditAmountKlaim__saldo']) {
                    $saldo = $_SESSION[$cCode]['main']['creditAmountKlaim__saldo'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Saldo Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                    $msg .= " Saldo saat ini $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    die(lgShowAlertMerah($msg));
                }
                if ($val > $_SESSION[$cCode]['main']['tagihan_bayar_after_return']) {
                    $saldo = $_SESSION[$cCode]['main']['tagihan_bayar_after_return'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Penggunaan Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " melebihi tagihan yang akan dibayar.";
                    $msg .= " Nilai tagihan $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    die(lgShowAlertMerah($msg));
                }
//                if (($_SESSION[$cCode]['main']['nilai_entry']>0) && ($val > $_SESSION[$cCode]['main']['nilai_entry'])) {
//                    $saldo = $_SESSION[$cCode]['main']['nilai_entry'];
//                    $saldo_f = number_format($saldo, 0, ",", ".");
//                    $msg = "Penggunaan Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " melebihi nilai kewajiban yang akan dibayar.";
//                    $msg .= " Nilai kewajiban $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
//                    die(lgShowAlertBiru($msg));
//                }
                break;
            case "uang_muka_dipakai_ppn":
                // saldo
                if ($val > $_SESSION[$cCode]['main']['uangMukaPpn__debet']) {
                    $saldo = $_SESSION[$cCode]['main']['uangMukaPpn__debet'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Saldo Uang Muka dengan PPN " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                    $msg .= " Saldo saat ini $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    echo "<script>console.log('$val')</script>";
                    die(lgShowAlertMerah($msg));

                }
                else {
                    if (isset($_SESSION[$cCode]['items6_sum'])) {
                        foreach ($_SESSION[$cCode]['items6_sum'] as $ii => $spec) {
                            if ($ii > 0) {
                                unset($_SESSION[$cCode]['items6_sum'][$ii]);
                            }
                            else {
                                $_SESSION[$cCode]['items6_sum'][0] = $spec;
                                $_SESSION[$cCode]['items6_sum'][0]["ppn_sudah_faktur"] = 0;
                                $_SESSION[$cCode]['items6_sum'][0]["dpp_final"] = 0;
                                $_SESSION[$cCode]['items6_sum'][0]["ppn_final"] = 0;
                                $_SESSION[$cCode]['items6_sum'][0]["tagihan_bayar"] = 0;
                            }
                        }
                    }
                }
                if ($val > $_SESSION[$cCode]['main']['after_koreksi']) {
                    $saldo = $_SESSION[$cCode]['main']['after_koreksi'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Penggunaan Uang Muka dengan PPN " . $_SESSION[$cCode]['main']['pihakName'] . " melebihi tagihan yang akan dibayar.";
                    $msg .= " Nilai tagihan $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    die(lgShowAlertMerah($msg));
                }

                break;
            case "uang_muka_nonrelasi_dipakai":
//                mati_disini(__LINE__);
                // saldo
                if ($val > $_SESSION[$cCode]['main']['uangMukaNonRelasi__sisa']) {
                    $saldo = $_SESSION[$cCode]['main']['uangMukaNonRelasi__sisa'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Saldo Titipan tanpa relasi PO " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                    $msg .= " Saldo saat ini $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    die(lgShowAlertMerah($msg));
                }
                if ($val > $_SESSION[$cCode]['main']['tagihan_bayar_after_titipan']) {
                    $selisih = $val - $_SESSION[$cCode]['main']['tagihan_bayar_after_titipan'];
                    $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
                    if ($selisih > 1) {
                        $saldo = $_SESSION[$cCode]['main']['tagihan_bayar_after_titipan'];
                        $saldo_f = number_format($saldo, 0, ",", ".");
                        $msg = "Penggunaan Titipan tanpa relasi " . $_SESSION[$cCode]['main']['pihakName'] . " melebihi tagihan yang akan dibayar.";
                        $msg .= " Nilai tagihan <b class='blink'>$saldo_f</b>, anda mengisi sebesar $val_f. Silahkan dikoreksi, menjadi $saldo_f";
                        die(lgShowAlertMerah($msg));
                    }
                }
                break;
            case "nilai_diskon_pembulatan":
//                if ($val > MAX_DISKON_PEMBULATAN) {
//                    $msg = "Maksimal diskon/pembulatan yang diijinkan sebesar " . MAX_DISKON_PEMBULATAN . ". ";
//                    $msg .= "Diskon/pembulatan yang diisi sebesar $val. ";
//                    mati_disini($msg);

//                }
                $gate_max_source_diskon = isset($this->configUi[$this->jenisTr]["maxDiskonSource"]) ? $this->configUi[$this->jenisTr]["maxDiskonSource"] : "sisa";
                if (isset($_SESSION[$cCode]['main'][$gate_max_source_diskon])) {
                    $val_max = (MAX_DISKON_PEMBULATAN / 100) * $_SESSION[$cCode]['main'][$gate_max_source_diskon];
                    if ($val > $val_max) {
//                        "diskon maksimal  yg diijinkan xxx
//Diskon anda yyy tidak diijinkan karena > dari xxx"
                        $msg = "Diskon maksimal  yg diijinkan " . number_format($val_max, 0) . ". ";
                        $msg .= "<br>Diskon anda " . number_format($val, 0) . " tidak diijinkan <br> karena > dari " . number_format($val_max, 0) . ".";
                        mati_disini($msg);
                    }
                }
                break;

            case "creditAmount_dipakai":
                if ($val > $_SESSION[$cCode]['main']['creditAmount']) {
                    $saldo = $_SESSION[$cCode]['main']['creditAmount'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Saldo Potongan PPh21/BPJS Ditanggung Karyawan tidak cukup.";
                    $msg .= " Saldo saat ini $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    die(lgShowAlertMerah($msg));
                }
                if ($val > $_SESSION[$cCode]['main']['sisa']) {
                    $saldo = $_SESSION[$cCode]['main']['sisa'];
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $msg = "Penggunaan Saldo Potongan PPh21/BPJS Ditanggung Karyawan melebihi tagihan yang akan dibayar.";
                    $msg .= " Nilai tagihan $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";
                    die(lgShowAlertMerah($msg));
                }
                break;
        }

        //-----------------------------------
        $arrDiskonSupplier = array(
            "diskon_1",
            "diskon_2",
            "diskon_3",
            "diskon_4",
            "diskon_5",
        );
        if (in_array($rowName, $arrDiskonSupplier)) {
            $this->load->model("Mdls/MdlSupplierDiskon");
            $ds = New MdlSupplierDiskon();
            $ds->addFilter("nama='$rowName'");
            $dsTmp = $ds->lookupAll()->result();
            $data_id = $dsTmp[0]->id;
            $data_nama = $dsTmp[0]->nama;
            $data_label = $dsTmp[0]->label;
            $_SESSION[$cCode]["items4_sum"][$data_id] = array();
            $_SESSION[$cCode]["items4_sum"][$data_id] = array(
                "id" => $data_id,
                "nama" => $data_nama,
                "name" => $data_nama,
                "qty" => 1,
                "jml" => 1,
                "nilai_diskon_dipakai" => $val,
            );
        }
        //-----------------------------------

        $id = isset($_GET["id"]) ? $_GET["id"] : 0;
        if (!isset($_SESSION[$cCode]['add_rows'])) {
            $_SESSION[$cCode]['add_rows'] = array();
        }
        $_SESSION[$cCode]['add_rows'][$rowName] = $val;
        $_SESSION[$cCode]['add_rows']['total_disc'] = $total_diskon;

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$rowName] = $val;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();

        $extTools = $this->configUi[$jenisTr]['extTool'];
        $_SESSION[$cCode]["main"]["transaksi_injected_entry"] = 0;//buat matiin onblur default value nilai entry
        /* --------------------------------
         * memasukan request diskon ke tbl untuk diorisasi atau dikirim otp
         * ---------------------------*/
        //        if (sizeof($extTools) > 0) {
        //            if ($rowName == $extTools['field_id']) {
        //                arrPrint($extTools);
        //                $this->load->model("Mdls/MdlTmpDiskon");
        //                $td = new MdlTmpDiskon();
        //
        //                //region validasi nama konsumen
        //                if ($mains['customerID'] == 0) {
        //                    $alerts = array(
        //                        "html" => "data konsumen harap dilengkapi dulu"
        //                    );
        //                    echo swalAlert($alerts);
        //                    die();
        //                }
        //                //endregion
        //

        if ($rowName == 'add_disc') {
            if (!isset($_SESSION[$cCode]['main']['oto_diskon'])) {
                $_SESSION[$cCode]['main']['oto_diskon'] = 0;
            }
            if ($mains['oto_diskon'] == 1) {
                $_SESSION[$cCode]['main']['oto_diskon'] = 0;
                // topReload();
                //                cekHere($rowName);
                //                matiHere(__LINE__);
            }
        }

        //
        //
        //                $total_diskon = $mains['disc'] + $mains['add_disc'];
        //                $datas = blobEncode($mainGates);
        //                $otp = randomNumber(6);
        //                $dTmpDiskons = array(
        //                    "datas"         => $datas,
        //                    "jenis"         => "diskon_penjualan",
        //                    "cabang_id"     => $mains['placeID'],
        //                    "cabang_nama"   => $mains['placeName'],
        //                    "oleh_id"       => $mains['olehID'],
        //                    "oleh_nama"     => $mains['olehName'],
        //                    "customer_id"   => $mains['customerID'],
        //                    "customer_nama" => $mains['customerName'],
        //                    "otp"           => $otp,
        //                );
        //
        //                /* ----------------------
        //                 * membaca tmpDiskon
        //                 * ------------------------------*/
        //                $condites = array(
        //                    "cabang_id"   => $mains['placeID'],
        //                    "oleh_id"     => $mains['olehID'],
        //                    "customer_id" => $mains['customerID'],
        //                );
        //                $cekData = $td->lookupByCondition($condites)->num_rows();
        //                // $cekData = $td->lookupByCondition($condites);
        //
        //                // showLast_query("lime");
        //                // arrPrint($mains);
        //                // arrPrintPink($dTmpDiskons);
        //                // arrPrintWebs($cekData->num_rows());
        //                if ($cekData > 0) {
        //                    $delete = $td->deleteData($condites);
        //                    showLast_query("orange");
        //                }
        //
        //                $td->addData($dTmpDiskons);
        //                topReload(100);
        //                /* ----------------------------------------
        //                 * ON-OFF OTORISASI DISKON
        //                 * disable dan pop up button otorisasi merefresh main body di cont/Transaksi/createForm
        //                 * ------------------------------------------*/
        //
        //            }
        //        }

        //        echo "<script>";
        //        echo "top.getData('" . base_url() . "_shoppingCart/viewCart/$jenisTr/?kAhHJASAGHSGfags=kak','shopping_cart')";
        //        echo "</script>";
        // arrPrint($_GET);


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);
        // $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        // heInitGates_he_cart($this->jenisTr, $initMasterValues);

//        arrPrintPink($_SESSION[$cCode]["main"]);
//        matiHere("cek " . $rowName);


        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";
    }

    public function requestOtoDiskon()
    {
        $cCode = "_TR_582";
        $_SESSION[$cCode]['main']['oto_diskon'] = 0;
        // matiHere();
        topReload(100);
    }

    public function restoreCart()
    {
        // matiHere();
        $dbId = $this->uri->segment(3);
        $jenis = $this->uri->segment(4);
        $cCode = "_TR_" . $jenis;
        $this->load->model("Mdls/MdlTmpDiskon");
        $td = new MdlTmpDiskon();

        $td->addFilter("id=" . $dbId);
        $tmps = $td->lookupAll()->result();
//        showLast_query("lime");
//        arrPrintWebs($tmps);
        $params = blobDecode($tmps[0]->datas);
//        arrPrint($params);
        $_SESSION[$cCode] = $params;
        // $_SESSION[$cCode]['main']['reload'] = 1;
        $_SESSION[$cCode]['main']['oto_diskon'] = 0;

        topReload(100);

    }

    public function tmpSave()
    {
        $jenisTr = $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        $tmpSaver = isset($this->configUi[$this->jenisTr]['tmpSave']) ? $this->configUi[$this->jenisTr]['tmpSave'] : array();
//        arrPrintWebs($tmpSaver);
        $mdlName = $tmpSaver['mdlName'];

        // $jenisTr = $this->uri->segment(3);
        // $cCode = "_TR_" . $jenisTr;
        $rowName = $this->uri->segment(4);
        // $val = ($_GET['val']);
        $mains = $_SESSION[$cCode]['main'];
        $mainGates = $_SESSION[$cCode];
        /* --------------------------------
 * memasukan request diskon ke tbl untuk diorisasi atau dikirim otp
 * ---------------------------*/ // arrPrintPink($_SESSION[$cCode]);
        // matiHere(__LINE__);
        // if (sizeof($extTools) > 0) {
        //     if ($rowName == $extTools['field_id']) {
        // arrPrint($extTools);
        $this->load->model("Mdls/" . $mdlName);
        $td = new $mdlName();

        //region validasi nama konsumen
        if ($mains['customerID'] == 0) {
            $alerts = array("html" => "data konsumen harap dilengkapi dulu");
            echo swalAlert($alerts);
            die();
        }
        //endregion

        if (!isset($_SESSION[$cCode]['main']['oto_diskon'])) {
            $_SESSION[$cCode]['main']['oto_diskon'] = 0;
        }
        if ($mains['oto_diskon'] == 1) {

            $_SESSION[$cCode]['main']['oto_diskon'] = 0;
            // topReload();
            // matiHere(__LINE__);
        }


        $total_diskon = $mains['disc'] + $mains['add_disc'];
        $datas = blobEncode($mainGates);
        $otp = randomNumber(6);
        $dTmpDiskons = array("datas" => $datas,
            "jenis" => "diskon_penjualan",
            "cabang_id" => $mains['placeID'],
            "cabang_nama" => $mains['placeName'],
            "oleh_id" => $mains['olehID'],
            "oleh_nama" => $mains['olehName'],
            "customer_id" => $mains['customerID'],
            "customer_nama" => $mains['customerName'],
            "otp" => $otp,
        );

        /* ----------------------
         * membaca tmpDiskon
         * ------------------------------*/
        $condites = array("cabang_id" => $mains['placeID'],
            "oleh_id" => $mains['olehID'],
            "customer_id" => $mains['customerID'],
        );
        $cekData = $td->lookupByCondition($condites)->num_rows();
        // $cekData = $td->lookupByCondition($condites);

        // showLast_query("lime");
        // arrPrint($mains);
        // arrPrintPink($dTmpDiskons);
        // arrPrintWebs($cekData->num_rows());
        if ($cekData > 0) {
            $delete = $td->deleteData($condites);
            showLast_query("orange");
        }

        $this->db->trans_begin();
        $td->addData($dTmpDiskons);
        showLast_query("lime");
        // matiHere("belum commit " . __LINE__);

        $this->db->trans_complete();

        topReload(100);
        /* ----------------------------------------
         * ON-OFF OTORISASI DISKON
         * disable dan pop up button otorisasi merefresh main body di cont/Transaksi/createForm
         * ------------------------------------------*/

        // }
        // }
    }

    public function addReff()
    {
        $key = $_GET['key'];
        $val = $_GET['val'];
        $id = isset($_GET["id"]) ? $_GET["id"] : "0";
        //        $this->jenisTr = $this->uri->segment(3);
        //        $cCode = "_TR_" . $this->jenisTr;
        $cCode = $this->cCode;
        $jenisTr = $this->jenisTr;
        $_SESSION[$cCode]['main'][$key] = $val;
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        //        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?epreketek=yes');";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";
    }

    //-----------------------------------
    public function fetchItemsElement()
    {
        //        $jenisTr = $this->uri->segment(3);
        //        $cCode = "_TR_" . $jenisTr;

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $mdlName = $this->uri->segment(6);
        $helpName = $this->uri->segment(7);
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        $key = $_GET['key'];


// matiHere();
        heFetchItemsElement_modul($jenisTr, $elName, $mdlName, $key, $helpName, $configUiJenis);

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function recordFieldItemsElement()
    {

        //        $jenisTr = $this->uri->segment(3);
        //        $cCode = "_TR_" . $jenisTr;
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $val = ($_GET['val']);
        $elementConfigs = isset($this->configUi[$jenisTr]['receiptElements']) ? $this->configUi[$jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->configUi[$jenisTr]['relativeElements']) ? $this->configUi[$jenisTr]['relativeElements'] : array();
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        heRecordElement_modul($jenisTr, $elName, $val, $configUiJenis);


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    public function resetItemsElement()
    {

        //        $jenisTr = $this->uri->segment(3);
        //        $cCode = "_TR_" . $jenisTr;
        $cCode = $this->cCode;
        $jenisTr = $this->jenisTr;
        $elName = $this->uri->segment(5);
        //        $mdlName = $this->uri->segment(6);
        $helpName = $this->uri->segment(7);
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;

        //        cekmerah("resetting element $elName on $cCode...");

        $_SESSION[$cCode]['main'][$helpName] = null;
        $_SESSION[$cCode]['main'][$helpName . "__label"] = null;

        //==reset kloningan elemen di main dan kroni2nya
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            foreach ($_SESSION[$cCode]['main'] as $key => $val) {
                if (strpos($key, $helpName . "__") !== false) {
                    $_SESSION[$cCode]['main'][$key] = null;
                    unset($_SESSION[$cCode]['main'][$key]);
                }
            }
        }

        //==reset kloningan elemen di items dan kroni2nya
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $ii => $spec) {
                $_SESSION[$cCode]['items'][$ii][$helpName] = null;
                unset($_SESSION[$cCode]['items'][$ii][$helpName]);

                foreach ($spec as $key => $val) {
                    if (strpos($key, $helpName . "__") !== false) {
                        $_SESSION[$cCode]['items'][$ii][$key] = null;
                        unset($_SESSION[$cCode]['items'][$ii][$key]);
                    }
                    if (strpos($key, "sub_" . $helpName . "__") !== false) {
                        $_SESSION[$cCode]['items'][$ii][$key] = null;
                        unset($_SESSION[$cCode]['items'][$ii][$key]);
                    }
                }
            }
        }

        unset($_SESSION[$cCode]['main'][$helpName]);
        unset($_SESSION[$cCode]['items_elements'][$elName]);
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        //        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    //-----------------------------------
    public function recordItemColumn()
    {

        $cCode = $this->cCode;
        $iID = $_GET['iid'];
        $colName = $this->uri->segment(5);

        $val = rawurldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? rawurldecode($_GET['valValue']) : null;
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();

        $_SESSION[$cCode]['items'][$iID][$colName] = $val;

        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['items'][$iID][$valCol] = $valValue;
        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $iID, $initMasterValues, $this->modul);

        if (isset($_GET['nr'])) {

        }
        else {
            echo "<script>";
            echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
            echo "</script>";
        }

    }

    public function recordPairedItem()
    {

        $cCode = $this->cCode;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $clonerConfig = isset($this->configUi[$this->jenisTr]['cloner']) ? $this->configUi[$this->jenisTr]['cloner'] : array();
        $mainClonerConfig = isset($this->configUi[$this->jenisTr]['mainCloner']['rsltItems']) ? $this->configUi[$this->jenisTr]['mainCloner']['rsltItems'] : array();
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        if (!isset($_SESSION[$cCode]["items2_sum"])) {
            $_SESSION[$cCode]["items2_sum"] = array();
        }


        $iID = $_GET['iid'];
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;


        if (isset($_SESSION[$cCode]["items2_sum"][$iID])) {
            $_SESSION[$cCode]["items2_sum"][$iID] = null;
            unset($_SESSION[$cCode]["items2_sum"][$iID]);
        }

        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        $tmpB = $b->lookupByID($val)->result();
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {

                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = $_SESSION[$cCode]['items'][$iID]['jml'];

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($val, $_SESSION[$cCode]["items2_sum"])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $val,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$val'");
                        $h->addFilter("status='1'");
//                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $this->db->where_in("jenis_value", $priceConfig['label']);
                        $tmpH = $h->lookupAll($val)->result();
//                        cekHere(":: $mdlName ::");
//                        showLast_query("biru");
//                        cekHere(sizeof($tmpH));
//                        arrPrintPink($tmpH);
                        if (sizeof($tmpH) > 0) {
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $tmp[$val] = isset($hSpec->nilai) ? ($hSpec->nilai + 0) : 0;
                                    }
                                }
                            }
                        }
                        else {
                            $errMsgs = array();
                            foreach ($priceConfig['key_label'] as $key => $val) {
                                $errMsgs[] = "$key " . $row->nama . " belum ditentukan. Silahkan diseting dahulu.";
                            }
                            if (sizeof($errMsgs) > 0) {
                                $_SESSION['errMsg'] = implode("<br>", $errMsgs);
                                die(lgShowAlertBiru($_SESSION['errMsg']));
                            }
                        }
                    }

                    if (sizeof($clonerConfig) > 0) {
                        if (sizeof($clonerConfig['cloneLabel'])) {
                            foreach ($clonerConfig['cloneLabel'] as $label) {
                                $tmp[$label] = $_SESSION[$cCode][$clonerConfig['srcGateName']][$iID][$label];
                            }
                        }
                    }


                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }


                    //region perhitungan subtotal items
                    $cal = new FieldCalculator();

                    if ($subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                        cekHijau("subtotal NOL");
                    }
                    $tmp["subtotal"] = $subtotal;
                    $_SESSION[$cCode]["items2_sum"][$iID] = $tmp;
                    //endregion

                }
                else {
                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]["items2_sum"][$iID][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }

                        }

                        foreach ($itemNumLabels as $key => $label) {
                            $_SESSION[$cCode]["items2_sum"][$iID]["sub_" . $key] = ($_SESSION[$cCode]["items2_sum"][$iID][$key] * $_SESSION[$cCode]["items2_sum"][$iID]["jml"]);
                        }
                        $_SESSION[$cCode]["items2_sum"][$iID]['sub_nett'] = ($_SESSION[$cCode]["items2_sum"][$iID]['nett'] * $_SESSION[$cCode]["items2_sum"][$iID]['jml']);
                        $_SESSION[$cCode]["items2_sum"][$iID]['subtotal'] = ($_SESSION[$cCode]["items2_sum"][$iID]['jml'] * $_SESSION[$cCode]["items2_sum"][$iID]['harga']);
                    }
                }

                if (sizeof($mainClonerConfig) > 0) {
                    foreach ($mainClonerConfig as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $row->$val;
                    }
                }
            }
        }


        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]["items2_sum"][$iID][$valCol] = $valValue;
        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function recordPairedItemSatuan()
    {

        $cCode = $this->cCode;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $clonerConfig = isset($this->configUi[$this->jenisTr]['cloner']) ? $this->configUi[$this->jenisTr]['cloner'] : array();
        $mainClonerConfig = isset($this->configUi[$this->jenisTr]['mainCloner']['items2']) ? $this->configUi[$this->jenisTr]['mainCloner']['items2'] : array();
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        $iID = $_GET['iid'];
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;

        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        if (!isset($_GET['newQty'])) {
            if (isset($_SESSION[$cCode]['items2_sum'][$iID])) {
                $_SESSION[$cCode]['items2_sum'][$iID] = null;
                unset($_SESSION[$cCode]['items2_sum'][$iID]);
            }
//            if (isset($_SESSION[$cCode]['rsltItems'][$iID])) {
//                $_SESSION[$cCode]['rsltItems'][$iID] = null;
//                unset($_SESSION[$cCode]['rsltItems'][$iID]);
//            }
        }


        $tmpB = $b->lookupByID($val)->result();

        if (sizeof($tmpB) > 0) {

            foreach ($tmpB as $row) {

                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = $_SESSION[$cCode]['items'][$iID]['jml'];


                $_SESSION[$cCode]['items'][$iID]['targetID'] = isset($row->id) ? $row->id : 0;
                $_SESSION[$cCode]['items'][$iID]['targetName'] = isset($row->nama) ? $row->nama : "";
                $_SESSION[$cCode]['items'][$iID]['targetNama'] = isset($row->nama) ? $row->nama : "";


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");


                if (!array_key_exists($iID, $_SESSION[$cCode]['items2_sum'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $val,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "produk_jenis" => "into",
                        "id_src" => "$iID",
                        "jml_per_satuan" => $tmpJml,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$val'");
                        $h->addFilter("status='1'");
//                        $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($val)->result();
//                        cekMerah($this->db->last_query());
                        if (sizeof($tmpH) > 0) {
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $tmp[$val] = isset($hSpec->nilai) ? ($hSpec->nilai + 0) : 0;
//                                        $tmp['hpp'] = isset($hSpec->nilai) ? ($hSpec->nilai + 0) : 0;
//                                        if(isset($hSpec->nilai)){
//                                            $tmp[$val] = ($hSpec->nilai + 0);
//                                        }
//                                        else{
//                                            mati_disini("HAHAHAAHA");
//                                            die(lgShowAlert("$key belum didefine. silahkan hubungi admin."));
//                                        }
                                    }
                                }
                            }
                        }
//                        $tmp['harga'] = isset($tmpH[0]->nilai) ? ($tmpH[0]->nilai + 0) : 0;
//                        $tmp['hpp'] = isset($tmpH[0]->nilai) ? ($tmpH[0]->nilai + 0) : 0;
                    }

                    if (sizeof($clonerConfig) > 0) {
                        if (sizeof($clonerConfig['cloneLabel'])) {
                            foreach ($clonerConfig['cloneLabel'] as $label) {
                                $tmp[$label] = $_SESSION[$cCode][$clonerConfig['srcGateName']][$iID][$label];
                            }
                        }
                    }


                    foreach ($fieldSrcs as $key => $src) {
                        $tmpEx = $cal->multiExplode($src);
                        if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                            cekBiru("$key perhitungan");
                            $newSrc = $src;
                            foreach ($tmpEx as $key2 => $val2) {

                                if (!is_numeric($val2)) {
                                    if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
                                        $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                    }
                                    else {
                                        $newSrc = str_replace($val2, 0, $newSrc);
                                    }
                                }
//                                else {
//                                    if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
//                                        $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
//                                    } else {
//                                        if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
//                                            $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
//                                        } else {
//                                            $newSrc = str_replace($val2, 0, $newSrc);
//                                        }
//                                    }
//                                }
                            }
                            cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
                            $tmp[$key] = $cal->calculate($newSrc);
                        }
                        else {
                            cekBiru("$key BUKAN perhitungan");
                            $tmp[$key] = $row->$src;
                        }
                    }


                    //region perhitungan subtotal items
                    $cal = new FieldCalculator();
                    if ($subAmountConfig != null) {
                        $tmpEx = $cal->multiExplode($subAmountConfig);
                        if (sizeof($tmpEx) > 1) {
                            $newSrc = $subAmountConfig;
                            foreach ($tmpEx as $key2 => $val2) {
                                if (isset($tmp[$val2])) {
                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                    cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                }
                                else {
                                    $newSrc = str_replace($val2, "0", $newSrc);
//                                    cekKuning("$val2 direplace dengan NOL");
                                }

                            }
                            $subtotal = $cal->calculate($newSrc);
                            cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                        }
                        else {
                            $subtotal = 0;
                            cekHijau("subtotal dari perhitungan yang gak ada");
                        }
                    }
                    else {
                        $subtotal = 0;
                        cekHijau("subtotal NOL");
                    }
                    $tmp["subtotal"] = $subtotal;
                    $_SESSION[$cCode]['items2_sum'][$iID] = $tmp;


                    //endregion

                }
                else {
//                    cekUngu("BAWAH, ganti jumlah bawah items target");
                    if (isset($_GET['newQty'])) {
                        $_SESSION[$cCode]['items2_sum'][$iID]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items2_sum'][$iID]['subtotal'] = ($_SESSION[$cCode]['items2_sum'][$iID]['jml'] * $_SESSION[$cCode]['items2_sum'][$iID]['harga']);

                        if ($_GET['newQty'] >= $tmpJml) {
                            $jml_satuan = $_GET['newQty'] / $tmpJml;
                            $jml_satuan_ex = explode(".", $jml_satuan);
                            if (sizeof($jml_satuan_ex) > 1) {
                                die(lgShowAlert("konversi salah. silahkan cek ulang jumlah per-item konversinya."));
                            }
                        }
                        else {
                            die(lgShowAlert("konversi salah. silahkan cek ulang jumlah per-item konversinya."));
                        }
                        $_SESSION[$cCode]['items2_sum'][$iID]['jml_per_satuan'] = $jml_satuan;
                        $_SESSION[$cCode]['items'][$iID]['jml_per_satuan'] = $jml_satuan;
                    }


                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items2_sum'][$iID][$key] = $newValue;
//                                $_SESSION[$cCode]['rsltItems'][$iID][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }
                        }

                        foreach ($itemNumLabels as $key => $label) {
                            $_SESSION[$cCode]['items2_sum'][$iID]["sub_" . $key] = ($_SESSION[$cCode]['items2_sum'][$iID][$key] * $_SESSION[$cCode]['items2_sum'][$iID]["jml"]);
//                            $_SESSION[$cCode]['rsltItems'][$iID]["sub_" . $key] = ($_SESSION[$cCode]['rsltItems'][$iID][$key] * $_SESSION[$cCode]['rsltItems'][$iID]["jml"]);
                        }
                        $_SESSION[$cCode]['items2_sum'][$iID]['sub_nett'] = ($_SESSION[$cCode]['items2_sum'][$iID]['nett'] * $_SESSION[$cCode]['items2_sum'][$iID]['jml']);
                        $_SESSION[$cCode]['items2_sum'][$iID]['subtotal'] = ($_SESSION[$cCode]['items2_sum'][$iID]['jml'] * $_SESSION[$cCode]['items2_sum'][$iID]['harga']);
                    }
                }


                if (sizeof($mainClonerConfig) > 0) {
                    foreach ($mainClonerConfig as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $row->$val;
                    }
                }
            }
        }


        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['items2_sum'][$iID][$valCol] = $valValue;

        }
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";
    }

    public function recordImage()
    {
        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $iID = $this->uri->segment(6);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        $files = $_FILES['file'];

        if ($files['error'] == 0) {

            $cUrl_result = upload_image($files);

            $_SESSION[$cCode]['items'][$iID][$colName] = $cUrl_result->full_url;
        }
        $addQS = "";


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function recordPairedItemOther()
    {

        $cCode = $this->cCode;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();

        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModelTarget'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModelTarget'];

        $clonerConfig = isset($this->configUi[$this->jenisTr]['cloner']) ? $this->configUi[$this->jenisTr]['cloner'] : array();
        $mainClonerConfig = isset($this->configUi[$this->jenisTr]['mainCloner']['rsltItems']) ? $this->configUi[$this->jenisTr]['mainCloner']['rsltItems'] : array();
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        if (!isset($_SESSION[$cCode]["items2_sum"])) {
            $_SESSION[$cCode]["items2_sum"] = array();
        }


        $iID = $_GET['iid'];
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;


        if (isset($_SESSION[$cCode]["items2_sum"][$iID])) {
            $_SESSION[$cCode]["items2_sum"][$iID] = null;
            unset($_SESSION[$cCode]["items2_sum"][$iID]);
        }


        $tmpB = $b->lookupByID($val)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {


                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = $_SESSION[$cCode]['items'][$iID]['jml'];

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($val, $_SESSION[$cCode]["items2_sum"])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $val,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$val'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($val)->result();
//                        cekMerah($this->db->last_query());
//mati_disini("pID: $val");
                        if (sizeof($tmpH) > 0) {
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $tmp[$val] = isset($hSpec->nilai) ? ($hSpec->nilai + 0) : 0;
                                    }
                                }
                            }
                        }
                        else {
                            $errMsgs = array();
                            foreach ($priceConfig['key_label'] as $key => $val) {
                                $errMsgs[] = "$key " . $row->nama . " is required (" . __LINE__ . ")";
                            }
                            if (sizeof($errMsgs) > 0) {
                                $_SESSION['errMsg'] = implode("<br>", $errMsgs);
//                                echo lgShowAlert($_SESSION['errMsg']);
                                die(lgShowAlert($_SESSION['errMsg']));
                            }
//                            mati_disini("---");
                        }
                    }

                    if (sizeof($clonerConfig) > 0) {
                        if (sizeof($clonerConfig['cloneLabel'])) {
                            foreach ($clonerConfig['cloneLabel'] as $label) {
                                $tmp[$label] = $_SESSION[$cCode][$clonerConfig['srcGateName']][$iID][$label];
                            }
                        }
                    }


                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }


                    //region perhitungan subtotal items
                    $cal = new FieldCalculator();

                    if ($subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                        cekHijau("subtotal NOL");
                    }
                    $tmp["subtotal"] = $subtotal;
                    $_SESSION[$cCode]["items2_sum"][$iID] = $tmp;
                    //endregion

                }
                else {
                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]["items2_sum"][$iID][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }

                        }

                        foreach ($itemNumLabels as $key => $label) {
                            $_SESSION[$cCode]["items2_sum"][$iID]["sub_" . $key] = ($_SESSION[$cCode]["items2_sum"][$iID][$key] * $_SESSION[$cCode]["items2_sum"][$iID]["jml"]);
                        }
                        $_SESSION[$cCode]["items2_sum"][$iID]['sub_nett'] = ($_SESSION[$cCode]["items2_sum"][$iID]['nett'] * $_SESSION[$cCode]["items2_sum"][$iID]['jml']);
                        $_SESSION[$cCode]["items2_sum"][$iID]['subtotal'] = ($_SESSION[$cCode]["items2_sum"][$iID]['jml'] * $_SESSION[$cCode]["items2_sum"][$iID]['harga']);
                    }
                }

                if (sizeof($mainClonerConfig) > 0) {
                    foreach ($mainClonerConfig as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $row->$val;
                    }
                }
            }
        }


        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]["items2_sum"][$iID][$valCol] = $valValue;
        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    //----------------
    public function checklistOpname()
    {

        $pID = $_GET['id'];
        $cCode = $this->cCode;

        if (isset($_SESSION[$cCode]['items'][$pID])) {
            //---- masuk gerbang ceklist, di items
            $_SESSION[$cCode]['items'][$pID]['ceklist_opname'] = 1;
        }

//        mati_disini("cek pID: $pID");
    }

    public function checklistOpnameNote()
    {
//        arrPrint($_GET);
        $cCode = $this->cCode;
        $note1 = blobDecode($_GET['note1']);
        $note2 = blobDecode($_GET['note2']);

        if (isset($_SESSION[$cCode]['main'])) {
            if (isset($_GET['note1'])) {

                $_SESSION[$cCode]['main']['opnameNote_1'] = $note1;
                $_SESSION[$cCode]['main']['opnameNoteCeklist_1'] = 1;
            }
            if (isset($_GET['note2'])) {

                $_SESSION[$cCode]['main']['opnameNote_2'] = $note2;
                $_SESSION[$cCode]['main']['opnameNoteCeklist_2'] = 1;
            }
        }
    }

    //----------------
    public function recordColumn()
    {

        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;

        $_SESSION[$cCode]['main'][$colName] = $val;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/

        if (isset($_GET['nr'])) {

        }
        else {
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }


//        $addQS = "";
//        if (isset($_GET['populate']) && $_GET['populate'] == 1) {
////            $addQS .= "&populate=1&popValue=" . $_GET['popValue'] . "&popAcuanSrc=" . $_GET['popAcuanSrc'] . "&popAcuanTarget=" . $_GET['popAcuanTarget'];
//
//            $this->populateValues();
//
//            echo "<script>";
//            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0$addQS';";
//            echo "</script>";
//        }
//        else {
//            echo "<script>";
//            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1';";
//            echo "</script>";
//        }

    }

    public function recordKoreksi()
    {

        $id = isset($_GET["id"]) ? $_GET["id"] : "0";
        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $pid = $this->uri->segment(6);
        $val = urldecode($_GET['val']);
        $check = urldecode($_GET['check']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        $shopingCartRebate = isset($this->configUi[$this->jenisTr]['shopingCartRebate']) ? $this->configUi[$this->jenisTr]['shopingCartRebate'] : array();
//arrPrintPink($_GET);
//mati_disini("[val: $val] [pid: $pid] [colName: $colName]");

        $_SESSION[$cCode]['items4_sum'][$pid]["checked"] = $check; //untuk mengatur autocheck saat shoppingcard di load
//        cekMErah($check);
        if ($check * 1) {
            if ($val > $_SESSION[$cCode]['items4_sum'][$pid]["sub_harga"]) {
//                cekKuning($_SESSION[$cCode]['items4_sum'][$pid][$colName]);
//                cekHitam("jalan plussss");
                $_SESSION[$cCode]['items4_sum'][$pid][$colName] = $val;
                $_SESSION[$cCode]['items4_sum'][$pid]["harga_x"] = 0;
                $_SESSION[$cCode]['items4_sum'][$pid]["origvalue"] = $_SESSION[$cCode]['items4_sum'][$pid]["jml"] * $_SESSION[$cCode]['items4_sum'][$pid]["harga"];
                $_SESSION[$cCode]['items4_sum'][$pid]["harga_plus_x"] = $val / $_SESSION[$cCode]['items4_sum'][$pid]["jml"];
                $_SESSION[$cCode]['items4_sum'][$pid]["selisih_koreksi"] = 0;
                $_SESSION[$cCode]['items4_sum'][$pid]["selisih_koreksi_plus"] = ($val - $_SESSION[$cCode]['items4_sum'][$pid]["sub_harga"]) / $_SESSION[$cCode]['items4_sum'][$pid]["jml"];

            }
            else {
//                matiHEre(__LINE__);
//                cekKuning($_SESSION[$cCode]['items4_sum'][$pid][$colName]);
//                cekHitam("jalan minuussss");
                $_SESSION[$cCode]['items4_sum'][$pid][$colName] = $val;
                $_SESSION[$cCode]['items4_sum'][$pid]["origvalue"] = $_SESSION[$cCode]['items4_sum'][$pid]["jml"] * $_SESSION[$cCode]['items4_sum'][$pid]["harga"];
                $_SESSION[$cCode]['items4_sum'][$pid]["harga_x"] = $val / $_SESSION[$cCode]['items4_sum'][$pid]["jml"];
                $_SESSION[$cCode]['items4_sum'][$pid]["selisih_koreksi"] = ($_SESSION[$cCode]['items4_sum'][$pid]["sub_harga"] - $val) / $_SESSION[$cCode]['items4_sum'][$pid]["jml"];;
                $_SESSION[$cCode]['items4_sum'][$pid]["selisih_koreksi_plus"] = 0;
                $_SESSION[$cCode]['items4_sum'][$pid]["harga_plus_x"] = 0;

            }

        }
        else {

            $_SESSION[$cCode]['items4_sum'][$pid][$colName] = $_SESSION[$cCode]['items4_sum'][$pid]["jml"] * $_SESSION[$cCode]['items4_sum'][$pid]["harga"];
            $_SESSION[$cCode]['items4_sum'][$pid]["origvalue"] = $_SESSION[$cCode]['items4_sum'][$pid]["jml"] * $_SESSION[$cCode]['items4_sum'][$pid]["harga"];
            $_SESSION[$cCode]['items4_sum'][$pid]["harga_x"] = 0;
            $_SESSION[$cCode]['items4_sum'][$pid]["harga_plus_x"] = 0;
            $_SESSION[$cCode]['items4_sum'][$pid]["selisih_koreksi"] = 0;
            $_SESSION[$cCode]['items4_sum'][$pid]["selisih_koreksi_plus"] = 0;
        }


        foreach ($_SESSION[$cCode]['items4_sum'] as $datasAllSub) {
//            arrprint($datasAllSub);
            $refID = $datasAllSub["refID"];
            if (!isset($totalReff[$refID]["koreksi"])) {
                $totalReff[$refID]["koreksi"] = 0;
            }
            if (!isset($totalReff[$refID]["koreksi_plus"])) {
                $totalReff[$refID]["koreksi_plus"] = 0;
            }
            //checker harganaik atau turun

            $totalReff[$refID]["koreksi"] += $datasAllSub["sub_harga_x"];
            $totalReff[$refID]["koreksi_plus"] += $datasAllSub["harga_plus_x"];

//            $totalReff[$refID]["subtotal"] += $datasAllSub["sub_harga_x"];
//            if(isset($_SESSION[$cCode]["items"][])
        }
        if (count($totalReff) > 0) {
            foreach ($totalReff as $totalReff_id => $value) {
                foreach ($value as $k => $val) {
                    unset($_SESSION[$cCode]['items'][$totalReff_id][$k]);
                    $_SESSION[$cCode]['items'][$totalReff_id][$k] = $val;
                }
            }
        }

        if (count($shopingCartRebate) > 0) {
            /*
 *tipe diskon unit: berlaku per unit, akan disummary mejadi total diskon unit
 *                 yang akan menjadi dasar rebate
 *
 * diskon kelompok dihitung jml pertotal
 *
 * tipe diskon absolut : berlaku secara akumulasi/ total nota + ppn jika tercapai akan menambah rebate dengan dpp netto
 *  dpp =nilai nota - diskon unit
 *   rebate absolute = dpp * tarif(persen)
 *
 */
            $mainKey = $shopingCartRebate["mainKey"];
            $itemsGate = $shopingCartRebate["itemsGate"];
            $this->load->library("Diskon");
            $dc = New Diskon();
            $dc_val = $dc->diskonCadanganSupplier($_SESSION[$cCode][$itemsGate], $_SESSION[$cCode]["main"][$mainKey], $_SESSION[$cCode]["main"], $shopingCartRebate);

//            $allowedCadangan = $dc->diskonCadanganSupplierMember($_SESSION[$cCode]["main"][$mainKey]);
////            diskon_khusus_didapat
//            if($allowedCadangan){
//                $_SESSION[$cCode]["main"]["pihakDiskonKhususCode"] = 1;
//            }
//            else{
//                $_SESSION[$cCode]["main"]["pihakDiskonKhususCode"] = 0;
//            }
            if ($dc_val > 0) {
//                $_SESSION[$cCode]["main"]["diskon_khusus_persen"] = 0;//tetap ditulis biar tidak error, value diabaikan

                $_SESSION[$cCode]["main"]["diskon_khusus_didapat"] = $dc_val;
            }
            else {
                $_SESSION[$cCode]["main"]["diskon_khusus_didapat"] = 0;

            }
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }


    //-----------------------------------
    public function buildValues()
    {

        $cCode = $this->cCode;
        $jenisTr = $this->jenisTr;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");

        $configUiJenis = $this->configUi[$jenisTr];
        $configCoreJenis = $this->configCore[$jenisTr];
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $id = 0;
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        cekHijau("[seelsai buildValue]");
        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
        echo "</script>";
    }

    public function cloneFormulirFaktur()
    {
        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $jenisTr;
        $gate = "items6_sum";
        $source = "items";

        $nota_selected = count($_SESSION[$cCode][$source]);
        $formulir_faktur = count($_SESSION[$cCode][$gate]);
        if ($formulir_faktur >= $nota_selected) {
            $msg = "Jumlah formulir faktur melebihi nota GRN yang dipilih. ";
            $msg .= "Anda memilih $nota_selected GRN, maksimal $formulir_faktur faktur PPN Masukan.";
            mati_disini($msg);
        }

        $clone = array(
            "dpp_final" => 0,
            "ppn_final" => 0,
            "nilai_entry" => 0,
            "dateFaktur" => "",
            "eFaktur" => "",
            "skip_faktur" => "",
        );
        $keys = array_keys($_SESSION[$cCode][$gate]);
        $keys_flip = array_flip($keys);
        $lastkey = end($keys_flip);
        $count_next = ($lastkey) + 1;
        foreach ($clone as $key => $val) {

            $_SESSION[$cCode][$gate][$count_next][$key] = $val;
        }

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    public function deleteCloneFormulirFaktur()
    {
        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $jenisTr;
        $gate = "items6_sum";
        $count_next = $this->uri->segment(5);

        $_SESSION[$cCode][$gate][$count_next] = null;
        unset($_SESSION[$cCode][$gate][$count_next]);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    //-----------------------------------
    public function recordAdditionalDiskon()
    {
//        die();

        $id = isset($_GET["id"]) ? $_GET["id"] : "0";
        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $pid = $this->uri->segment(6);
        $val = urldecode($_GET['val']);
        $check = urldecode($_GET['check']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
//        matiHEre($colName);
//        "additionalDiskonValidate"
        $additionalDiskonValidate = isset($this->configUi[$this->jenisTr]['additionalDiskonValidate']) ? $this->configUi[$this->jenisTr]['additionalDiskonValidate'] : false;


        if ($_SESSION[$cCode]["main"]["pihakDiskonKhususCode"] == 1) {
            $saldo_cadangan_diskon = isset($_SESSION[$cCode]["main"]["diskonKhusus__saldo"]) ? $_SESSION[$cCode]["main"]["diskonKhusus__saldo"] : 0;
            if ($additionalDiskonValidate == true) {
                $val2 = $val * 1;
                if ($val2 > $saldo_cadangan_diskon) {
                    $saldo = $saldo_cadangan_diskon;
                    $saldo_f = number_format($saldo, 0, ",", ".");
                    $val_f = number_format($val2, 0, ",", ".");
                    $msg = "Saldo Cadangan Diskon " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                    $msg .= " Saldo saat ini $saldo_f, anda mengisi sebesar $val_f. Silahkan dikoreksi.";

                    $returnData = array(
                        "msg" => $msg,
                        "status" => 1,
                        "val" => $val2,
                        "cdangan" => $saldo_cadangan_diskon,
                    );
                    echo json_encode($returnData);
//                    die();
//                    die(lgShowAlertMerah($msg));
                }
                else {
                    /*
                    * supaya tidak error saat direquest ajax dan bisa divalidasi  keluar notif swalnya
                     * pastikan debuger off, jika debuger on akan menyebabakn error yang gak perlu
                    */
                    $returnData = array(
                        "msg" => "",
                        "status" => 1,
                        "val" => $val2,
                        "cdangan" => $saldo_cadangan_diskon,
                    );
                    echo json_encode($returnData);
//                    echo "1";
                }


            }

            $_SESSION[$cCode]['main'][$colName] = $val;
        }
        else {
            /*
             * supaya tidak error saat direquest ajax dan bisa divalidasi  keluar notif walnya
             */
            $returnData = array(
                "msg" => "",
                "status" => 1,
                "val" => 0,
                "cdangan" => 0,
            );
            echo json_encode($returnData);
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
    }


    public function viewLepasRelasi()
    {
        $cCode = $this->cCode;
        $curSess = $_SESSION[$cCode];
        $id = $curSess['main']["pihakID"];
        $name = $curSess['main']["pihakName"];

        $modulTarget = "kas";
        $subModulTarget = "4644";

        /*
         * Pihak Items nota nya
         * MdlUangMukaReference
         */
        $this->load->model("Mdls/MdlUangMukaReference");
        $td = new MdlUangMukaReference();
        $td->addFilter("extern_id=" . $id);
        $td->addFilter("rekening=1010050010");
//        $td->db->where("extern2_nama !=''");
        $td->db->order_by("debet desc");
//        $td->addFilter("extern4_nama=466");
        $td->addFilter("debet>0");
        $tmps = $td->lookupAll()->result();

//        echo json_encode($tmps);
//        showLast_query("biru");
//        die();

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("extern_id=$id");
        $tr->addFilter("extern2_id>0");
        $tr->addFilter("sisa>0");
        $tmpsUMSrc = $tr->lookupAllUangMukaSrc()->result();
//        showLast_query("biru");
//        cekHere( count($trTmpSrc) );
//        die();
        $totalDebet = 0;
        foreach ($tmpsUMSrc as $row) {
            $totalDebet += $row->sisa;
        }

        $items = array();
        foreach ($tmps as $row) {
            $TmpItems['id'] = $row->id;
            $TmpItems['name'] = $row->extern2_nama;
            $TmpItems['nilai'] = $row->debet;
            $items[] = $TmpItems;
        }

        $customers = [
            $id => [
                'name' => $name,
                'items' => $items
            ]
        ];

        $cusJson = json_encode($customers);

        $simple_view = "";
        $simple_view .= "
<div class='content-wrapper no-margin'>
        <section class='content'>
            <div class='row'>
                <div class='col-md-8 col-md-offset-2'>
                    <div class='form-container'>
                        <div class='form-header'>
                            <h3 class='box-title'><i class='fa fa-money'></i> Pilih Uang Muka yang akan di lepas relasi.</h3>
                            <p class='text-muted'>-----------------------------------------------</p>
                        </div>
                        <form id='customerForm'>
                            <div class='form-group'>
                                <label class='required' for='customerSelect'>Supplier / Vendor</label>
                                <select class='form-control select2' id='customerSelect' style='width: 100%;'>
                                    <option value=''>-- Pilih Vendor/Supplier --</option>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label class='required' for='totalDebet'>Total UM yang tersedia</label>
                                <select disabled class='form-control select2' id='totalDebet' style='width: 100%;'>
                                    <option value=''>" . number_format($totalDebet) . "</option>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label class='required' for='itemSelect'>Pilih Nomer PO</label>
                                <select class='form-control select2' id='itemSelect' style='width: 100%;' disabled>
                                    <option value=''>-- Pilih Vendor/Supplier Terlebih Dahulu --</option>
                                </select>
                            </div>
                            <div class='info-box' id='selectionInfo' style='display: none;'>
                                <h4><i class='fa fa-info-circle'></i> Pilihan Anda</h4>
                                <p><strong>Supplier/Vendor:</strong> <span id='selectedCustomer'>-</span></p>
                                <p><strong>Nomer:</strong> <span id='selectedNomer'>-</span></p>
                                <p><strong>Nilai UM:</strong> <span id='selectedNilai'>-</span></p>
                                <p class='hidden'><strong>Nilai UM Baru: <r>(abaikan jika tidak perlu perubahan)</r></strong> <span id='selectedNilaiNew'>-</span></p>
                                <p><strong>Catatan Perubahan UM:</strong> <span id='selectedNilaiNotes'>-</span></p>
                            </div>
                            <div style='margin-top: 60px;' class='form-footer'>

                                <button type='reset' class='btn btn-default'>
                                    <i class='fa fa-refresh'></i> Clear Pilihan
                                </button>

                                <button type='submit' class='btn btn-primary'>
                                    <i class='fa fa-check'></i> Lepas Relasi PO
                                </button>

                                <button type='button' onclick=\"closeTopBootstrapModal()\" class='btn btn-default pull-right'>
                                    <i class='fa fa-times'></i> Close
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

            </div>
        <script>


    // Customer data with their items
    const customers = $cusJson;

    console.log($cusJson);
    // Populate customer dropdown
    function populateCustomers() {
        const customerSelect = $('#customerSelect');
        customerSelect.empty();
        customerSelect.append('<option value=\"\">-- Pilih Customer --</option>');

        // Add customers to dropdown
        for (const [id, customer] of Object.entries(customers)) {
            customerSelect.append(new Option(customer.name, id));
        }

        // Update customer count badge
        $('#customerCount').text(Object.keys(customers).length);

        // Check if there's only one customer
        if (Object.keys(customers).length === 1) {
            const singleCustomerId = Object.keys(customers)[0];
            $('#autoSelectNotice').show();
            customerSelect.val(singleCustomerId).trigger('change');
            customerSelect.prop('disabled', true);
        }
    }

    // Initial population
    setTimeout(function(){
        populateCustomers();
    }, 200);

    $('#customerSelect').on('change', function() {

        const customerId = $(this).val();
        const itemSelect = $('#itemSelect');

        itemSelect.val(null).trigger('change');
        $('#selectionInfo').hide();

        if (customerId) {
            itemSelect.prop('disabled', false);
            itemSelect.empty();
            itemSelect.append(new Option('-- Pilih Item --', ''));
            customers[customerId].items.forEach(item => {
                const displayText = item.name + ' - ' + addCommas(item.nilai);
                itemSelect.append(new Option(displayText, item.id));
            });
            $('#selectedCustomer').text(customers[customerId].name);
        }
        else {
            itemSelect.prop('disabled', true);
            itemSelect.empty().append(new Option('-- Pilih Customer Terlebih Dahulu --', ''));
        }
    });

    $('#itemSelect').on('change', function() {
        const itemId = $(this).val();
        if (itemId) {
            const customerId = $('#customerSelect').val();
            const selectedItem = customers[customerId].items.find(item => item.id === itemId);
            $('#selectedNomer').text(selectedItem.name);
            $('#selectedNilai').text(addCommas(selectedItem.nilai));
            $('#selectedNilaiNew').html('<input id=\"formItemNilai\" onclick=\"select()\" class=\"form-control\" value='+addCommas(selectedItem.nilai)+'>');
            $('#selectedNilaiNotes').html('<textarea id=\"formItemNotes\" class=\"form-control\"></textarea>');
            $('#selectionInfo').show();
            $('#selectionInfo').css('padding', '10px');
            $('#formItemNilai').on('keyup', function(){
                this.value = addCommas( removeCommas(this.value) );
            })

            $('#formItemNotes').val('transaksi ini melepas relasi titipan PO ('+selectedItem.name+') menjadi titipan non-relasi').on('click', function(){ this.select() })
        }
        else {
            $('#selectionInfo').hide();
        }
    });

    $('button[type=\"reset\"]').click(function() {
        $('#customerSelect').val(null).trigger('change');
        $('#itemSelect').val(null).trigger('change').prop('disabled', true);
        $('#selectionInfo').hide();
        $('#customerSelect').prop('disabled', false);
        $('#autoSelectNotice').hide();
    });


    async function doLepasRelasi() {
        const modul = \"kas\";
        const subModul = \"4644\";
        const customerId = $('#customerSelect').val();
        const itemId = $('#itemSelect').val();
        const itemNilai = $('#formItemNilai').val();
        const formItemNotes = $('#formItemNotes').val();
        const baseUrl = '" . base_url() . "';
        const debug = '&debuger=0';

        try {
            // 1. Reset shopping cart
            await fetch(`\${baseUrl}\${modul}/_shoppingCart/reset/\${subModul}?1=1\${debug}`, {
                credentials: 'include' // Bawa session cookie
            });

            // 2. Pilih vendor/supplier
            await fetch(`\${baseUrl}\${modul}/_processPihak/select/\${subModul}/MdlSupplier?id=\${customerId}\${debug}`, {
                credentials: 'include'
            });

            // 3. Pilih biaya (replace)
            await fetch(`\${baseUrl}\${modul}/_processSelectBiaya/selectReplace/\${subModul}?selector&id=\${itemId}&minValue=1\${debug}`, {
                credentials: 'include'
            });

            // 4. Simpan catatan jika ada
            if (formItemNotes.trim() != '') {
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/recordItemColumn/\${subModul}/note?val=\${encodeURIComponent(formItemNotes.trim())}&iid=\${itemId}\${debug}`, {
                    credentials: 'include'
                });
            }

            // 5. Update qty dan harga
            await fetch(`\${baseUrl}\${modul}/_processSelectBiaya/select/\${subModul}?id=\${itemId}&newQty=0&harga=`+removeCommas(`\${itemNilai}`)+`\${debug}`, {
                credentials: 'include'
            });

            // 6. element: pilih mode  relasi po
            await fetch(`\${baseUrl}\${modul}/_shoppingCart/fetchElement/\${subModul}/actionType/MdlStaticRelasiTitipan/?key=remove\${debug}`, {
                credentials: 'include'
            });

            // 7. element: pilih mode dummyelement
            await fetch(`\${baseUrl}\${modul}/_shoppingCart/fetchElement/\${subModul}/dummyElement/MdlDummyElement/?key=yes\${debug}`, {
                credentials: 'include'
            });

            // 8. element: pilih mode vendorDetails
            await fetch(`\${baseUrl}\${modul}/_shoppingCart/fetchElement/\${subModul}/vendorDetails/MdlSupplier/?key=\${customerId}\${debug}`, {
                credentials: 'include'
            });

            // 9. Validate data session
            await fetch(`\${baseUrl}\${modul}/Transaksi/validate/\${subModul}?1=1\${debug}`, {
                credentials: 'include'
            });

            // 10. Simpan transaksi
            const saveResponse = await fetch(`\${baseUrl}\${modul}/Create/save/\${subModul}?1=1\${debug}`, {
                credentials: 'include'
            });
            const result = await saveResponse.text();

            if (result.includes(\"under maintenance\")) {
                const divMatches = result.match(/<div[^>]*>.*?<\/div>/gis);
                if (divMatches && divMatches.length > 0) {
                    const lastDiv = divMatches[divMatches.length - 1];
                    top.swal('Transaksi Berhasil', $(lastDiv).html(),'info');
                    document.body.insertAdjacentHTML('beforeend', lastDiv);
                }
                else {
                    console.warn(\"Tidak ditemukan tag <div> dalam response.\");
                }
            }
            else if (result.includes(\"transaction entry has been saved\")) {
                showSuccessAlert();
            }
            else {
                top.swal('Transaksi Gagal', 'Jika terjadi terus-menerus, segera hubungi Admin..', 'error');
            }

            // Tambahkan logika sukses di sini (e.g. tampilkan alert/redirect)

        } catch (error) {
            console.error(\"Terjadi kesalahan:\", error);
            // Tambahkan penanganan error di sini
        }
    }


    $('#customerForm').on('submit', function(e) {
        e.preventDefault();

            swal('sedang memproses....');
            swal.enableLoading();

            doLepasRelasi();

    });


    $('#simulateSingle').click(function() {
        for (const key of Object.keys(customers)) {
            if (key !== \"1\") {
                delete customers[key];
            }
        }
        populateCustomers();
    });
</script>

        ";

        echo $simple_view;

//        echo json_encode($curSess);
    }

    public function doLepasRelasi()
    {
        $modul = "kas";
        $subModul = "4644";
        $cCode = "_TR_" . $subModul;
        $sessionId = session_id();
        $customerId = $_POST['customerId'];
        $itemId = $_POST['itemId'];
        $itemNilai = $_POST['itemNilai'];

        $debug = 1;
        $txtDebug = "";
        if ($debug) {
            $txtDebug = "&debuger=1";
        }
//        echo json_encode($_POST);
//die();
        session_write_close();

        $ch1 = curl_init(base_url() . "$modul/_shoppingCart/reset/$subModul?1=1$txtDebug");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_COOKIE, session_name() . "=" . session_id());
        $response1 = curl_exec($ch1);
        curl_close($ch1);
        echo $response1 . "<br>";

//        session_write_close();

        $ch1 = curl_init(base_url() . "$modul/_processPihak/select/$subModul/MdlSupplier?id=$customerId$txtDebug");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_COOKIE, session_name() . "=" . session_id());
        $response1 = curl_exec($ch1);
        curl_close($ch1);
        echo $response1 . "<br>";

//        session_write_close();
        $ch1 = curl_init(base_url() . "$modul/_processSelectBiaya/selectReplace/$subModul?selector&id=$itemId&minValue=1$txtDebug");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_COOKIE, session_name() . "=" . session_id());
        $response1 = curl_exec($ch1);
        curl_close($ch1);
        echo $response1 . "<br>";

//        session_write_close();
//        cekUngu(base_url() . "$modul/_shoppingCart/fetchElement/$subModul/actionType/MdlStaticRelasiTitipan/?key=remove$txtDebug");
//        arrPrintWebs($_SESSION['login']);
//        session_start();

//        $ch1 = curl_init(base_url() . "$modul/_shoppingCart/fetchElement/$subModul/actionType/MdlStaticRelasiTitipan/?key=remove$txtDebug");
//        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch1, CURLOPT_COOKIE , session_name() . "=" . $sessionId);
//        $response1 = curl_exec($ch1);
//        curl_close($ch1);
//        echo $response1 . "<br>";
//
//        session_write_close();


////        session_start();
//        $ch1 = curl_init(base_url() . "$modul/_shoppingCart/fetchElement/$subModul/vendorDetails/MdlSupplier/?key=$customerId$txtDebug");
//        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch1, CURLOPT_COOKIE , session_name() . "=" . $sessionId);
//        $response1 = curl_exec($ch1);
//        curl_close($ch1);
//
//        session_write_close();
////        session_start();
//        $ch1 = curl_init(base_url() . "$modul/_shoppingCart/fetchElement/$subModul/dummyElement/MdlDummyElement/?key=yes$txtDebug");
//        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch1, CURLOPT_COOKIE , session_name() . "=" . $sessionId);
//        $response1 = curl_exec($ch1);
//        curl_close($ch1);
//        session_write_close();
//
////        session_write_close();
////        session_start();
//        echo $response1 . "<br>";
//        cekUngu(base_url() . "$modul/_shoppingCart/fetchElement/$subModul/actionType/MdlStaticRelasiTitipan/?key=remove$txtDebug");
//        arrPrintWebs($_SESSION['login']);
//        arrPrint($_SESSION[$cCode]);
//        matiHere(__LINE__);
//
//        $ch1 = curl_init(base_url() . "$modul/_shoppingCart/buildValues/$subModul?ohyes=ohno$txtDebug");
//        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch1, CURLOPT_COOKIE , session_name() . "=" . $sessionId);
////        $response1 = curl_exec($ch1);
//        curl_close($ch1);

//        session_write_close();

        if (isset($_POST['formItemNotes']) && $_POST['formItemNotes'] != "") {
            $ch1 = curl_init(base_url() . "$modul/_shoppingCart/recordItemColumn/$subModul/note?val=" . trim($_POST['formItemNotes']) . "&iid=$itemId$txtDebug");
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_COOKIE, session_name() . "=" . session_id());
            $response1 = curl_exec($ch1);
            curl_close($ch1);
            echo $response1 . "<br>";
        }

//        session_write_close();

        $ch1 = curl_init(base_url() . "$modul/_processSelectBiaya/select/$subModul?id=$itemId&newQty=0&harga=$itemNilai$txtDebug");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_COOKIE, session_name() . "=" . session_id());
        $response1 = curl_exec($ch1);
        curl_close($ch1);
        echo $response1 . "<br>";

        session_write_close();

//        $ch1 = curl_init(base_url() . "$modul/_shoppingCart/buildValues/$subModul?ohyes=ohno$txtDebug");
//        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch1, CURLOPT_COOKIE , session_name() . "=" . $sessionId);
//        $response1 = curl_exec($ch1);
//        curl_close($ch1);
//        echo $response1 . "<br>";
//
//        session_write_close();

//        session_write_close();
//        $ch1 = curl_init(base_url() . "$modul/Transaksi/validate/$subModul?1=1$txtDebug");
//        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch1, CURLOPT_COOKIE , session_name() . "=" . session_id());
//        $response1 = curl_exec($ch1);
//        curl_close($ch1);
//        echo $response1 . "<br>";

//        session_write_close();
//        session_write_close();


//        session_write_close();
        $ch1 = curl_init(base_url() . "$modul/Create/save/$subModul?1=1$txtDebug");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_COOKIE, session_name() . "=" . session_id());
        $response1 = curl_exec($ch1);
        curl_close($ch1);
        echo $response1 . "<br>";

        cekHijau("======== FINISH LAPORAN DARI SESI SAAT INI ========");
        arrPrint($_SESSION[$cCode]);
        matiHere(__LINE__);
//        session_start();

        echo $response1 . "<br>";

    }

    public function doLepasRelasi__AI()
    {
        $modul = "kas";
        $subModul = "4644";

        // Pastikan session sudah dimulai
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionId = session_id();
        $customerId = $_POST['customerId'];
        $itemId = $_POST['itemId'];
        $itemNilai = $_POST['itemNilai'];

        // Simpan semua data session yang diperlukan SEBELUM write close
        $sessionData = $_SESSION;

        // Lepaskan lock session TAPI tetap simpan datanya
        session_write_close();

        // Buat handler CURL yang reusable
        $executeCurl = function ($url) use ($sessionId, $sessionData) {
            // Simpan cookie ke file temporary
            $cookieFile = tempnam(sys_get_temp_dir(), 'CURLCOOKIE');
            file_put_contents($cookieFile, session_name() . '=' . $sessionId);

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_COOKIEFILE => $cookieFile, // Baca cookie dari file
                CURLOPT_COOKIEJAR => $cookieFile,   // Simpan cookie ke file
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            unlink($cookieFile); // Hapus file temp

            return [
                'response' => $response,
                'error' => $error,
                'httpCode' => $httpCode
            ];
        };

        // Eksekusi urutan request
        $steps = [
            ["url" => base_url() . "$modul/_shoppingCart/reset/$subModul"],
            ["url" => base_url() . "$modul/_processPihak/select/$subModul/MdlSupplier?id=$customerId"],
            ["url" => base_url() . "$modul/_processSelectBiaya/selectReplace/$subModul?selector&id=$itemId&minValue=0"],
            ["url" => base_url() . "$modul/_shoppingCart/fetchElement/$subModul/actionType/MdlStaticRelasiTitipan/?key=remove"],
        ];

        // Tambahkan step untuk notes jika ada
        if (isset($_POST['formItemNotes']) && $_POST['formItemNotes'] != "") {
            $notes = urlencode(trim($_POST['formItemNotes']));
            $steps[] = ["url" => base_url() . "$modul/_shoppingCart/recordItemColumn/$subModul/note?val=$notes&iid=$itemId"];
        }

        // Tambahkan step terakhir
        $steps = array_merge($steps, [
            ["url" => base_url() . "$modul/_processSelectBiaya/select/$subModul?id=$itemId&newQty=0&harga=$itemNilai"],
            ["url" => base_url() . "$modul/_shoppingCart/buildValues/$subModul?ohyes=ohno"],
            ["url" => base_url() . "$modul/Transaksi/validate/$subModul"],
            ["url" => base_url() . "$modul/Create/save/$subModul"],
        ]);

        // Eksekusi semua step
        $output = "";
        foreach ($steps as $step) {
            $result = $executeCurl($step['url']);

            if ($result['httpCode'] !== 200 || $result['error']) {
                $output .= "ERROR at {$step['url']}: " . $result['error'] . "<br>";
                // Handle error sesuai kebutuhan
            }
            else {
                $output .= $result['response'] . "<br>";
            }

            // Tidak perlu sleep karena cookie sudah dikelola dengan benar
        }

        // Restart session untuk kebutuhan selanjutnya
        session_start();
        // Restore session data jika perlu
        $_SESSION = array_merge($_SESSION, $sessionData);

        echo $output;
    }

    public function dummy()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $sessionId = session_id();

        echo $sessionId;
    }
}


