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

        $modePymSrc = "";
        if (isset($_SESSION[$cCode]['main']['modePymSrc']) && ($_SESSION[$cCode]['main']['modePymSrc'] != NULL)) {
            $modePymSrc = $_SESSION[$cCode]['main']['modePymSrc'];
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

        $avoidRemoveAll_items = isset($this->configUi[$this->jenisTr]['shoppingCartAvoidRemoveAll_items']) ? $this->configUi[$this->jenisTr]['shoppingCartAvoidRemoveAll_items'] : false;

        $unionSelectors = isset($this->configUi[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber] : array();
        $keyUpEvents = isset($this->configUi[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber] : array();
        $selectedPrices = isset($this->configUi[$this->jenisTr]['selectedPrice']['key_label']) ? $this->configUi[$this->jenisTr]['selectedPrice']['key_label'] : array();
        $showItems = isset($this->configUi[$this->jenisTr]['showItems']) && $this->configUi[$this->jenisTr]['showItems'] == "false" ? "false" : "true";

        $fixedNote = isset($this->configLayout[$this->jenisTr]['fixedNote']) ? $this->configLayout[$this->jenisTr]['fixedNote'] : null;
        $fixedNoteTop = isset($this->configLayout[$this->jenisTr]['fixedNoteTop']) ? $this->configLayout[$this->jenisTr]['fixedNoteTop'] : null;
        $shopingCartParamForceEditable = isset($this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber] : array();
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $fieldSrcsPym = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrcPym']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrcPym'] : array();
        //----------------------------------------------
        $isSettlement = isset($this->configUi[$this->jenisTr]['isSettlement']) ? $this->configUi[$this->jenisTr]['isSettlement'] : false;
        //----------------------------------------------
        $shopingCartTaxAdd = isset($this->configUi[$this->jenisTr]['shopingCartAddTax']) ? $this->configUi[$this->jenisTr]['shopingCartAddTax'] : array();
        $shopingCartTaxOption = isset($this->configUi[$this->jenisTr]['shopingCartAddOption']) ? $this->configUi[$this->jenisTr]['shopingCartAddOption'] : array();
        $shopingCartFaktur = isset($this->configUi[$this->jenisTr]['addMainSource'][1]) ? $this->configUi[$this->jenisTr]['addMainSource'][1] : array();

        //----------------------------------------------
        $shopingCartPaymentItemsColorEnabled = isset($this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']['enabled']) ? $this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']['enabled'] : false;
        $shopingCartPaymentItemsColor = isset($this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']) ? $this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor'] : array();
        $shopingCartView = isset($this->configUi[$this->jenisTr]['shopingCartView']) ? $this->configUi[$this->jenisTr]['shopingCartView'] : "shoppingCart";
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
                // arrPrint($_SESSION[$cCode]['items']);
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
                        "merk" => isset($iSpec['merk']) ? $iSpec['merk'] : "",
                        "serial_no" => isset($iSpec['serial_no']) ? $iSpec['serial_no'] : "",
                        "extern2_nama" => isset($iSpec['extern2_nama']) ? $iSpec['extern2_nama'] : "",
                        "valas_nama" => isset($iSpec['valas_nama']) ? $iSpec['valas_nama'] : "",
                        "valas_nilai" => isset($iSpec['valas_nilai']) ? $iSpec['valas_nilai'] : "",

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
                    if (sizeof($fieldSrcsPym) > 0) {
                        foreach ($fieldSrcsPym as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                    }

                    //---------------
                    if ($shopingCartPaymentItemsColorEnabled == true) {
                        if ($iSpec['new_sisa'] > 0) {
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

                    //                    $tmp[$shoppingCartAmountValue] = isset($iSpec[$shoppingCartAmountValue]) ? $iSpec[$shoppingCartAmountValue] : 0;
                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;
                    $tmp['sub_harga'] = isset($iSpec['sub_harga']) ? $iSpec['sub_harga'] : 0;

                    $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "kas/", $iSpec['handler']) : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "kas/", $iSpec['handler']) : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;

                    $items[] = $tmp;

                    //                    arrPrint($shoppingCartAmountValue);


                    //                    if (sizeof($notifications) > 0) {
                    //                        $plafon = $lv->callPlafonHutangBank($id);
                    //
                    //                        // cekHitam("$plafon");
                    //                        if (($pihakID > 0) && ($plafon == 0)) {
                    //                            echo "<div class='alert alert-danger'>";
                    //                            // echo "<a href='#' class='close' data-dismiss='alert' aria-label='close' style='text-decoration: none;'>&times;</a>";
                    //                            echo "<span class='blink'><i class='fa fa-warning'></i></span> ";
                    //                            echo "$pihakName :: " . $notifications[$stepNow];
                    //                            echo "</div>";
                    //                        }
                    //                    }
                }
            }
        }
//        arrPrintCyan($items);
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
                        "diskon" => isset($iSpec['diskon_nama']) ? $iSpec['diskon_nama'] : "",
                        "diskon_nama" => isset($iSpec['diskon_nama']) ? $iSpec['diskon_nama'] : "",
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


        if (isset($this->configUi[$this->jenisTr]['shoppingCartSubamount2']) && $this->configUi[$this->jenisTr]['shoppingCartSubamount2'][$stepNumber] == true) {
            $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels = $itemLabels + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = $itemLabels2 + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
        }
        else {
            $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "Subtotal");
            $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Subtotal");
            $itemLabels3 = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "Subtotal");
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

        //region elements & inputs (if any)

        $elStr = array();
        $elements = array();
        $inputs = array();
        $addRows = array();
        $addRowLabels = array();
        $addRowHiddens = array();

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

                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relElementConfigs[$eName][$currentValue] as $rKey => $rcSpec) {
                                $elKey = $rKey;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rKey];
                            }
                        }
                    }
                }
                if (array_key_exists($eName, $relOptionConfigs)) {
                    if (isset($relOptionConfigs[$eName][$currentValue])) {
                        if (sizeof($relOptionConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relOptionConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {
                                    $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldInput/" . $this->jenisTr . "/$oValueName/?val='+this.value";
                                    if (isset($oValSpec['inputMode'])) {

                                        $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldInput/" . $this->jenisTr . "/$oValueName/?val='+this.value";
                                        $defVal = isset($_SESSION[$cCode]['main_inputs'][$oValueName]) ? $_SESSION[$cCode]['main_inputs'][$oValueName] : "";
                                        $inputs[$oValueName] = "<input type=text id='$oValueName' class='form-control text-center' placeholder='$oValueName' value='$defVal' onfocus='this.select()'  
                                                                    onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}\"
                                                                    onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src =$relInputTarget;}\"
                                                                >";
                                        $_SESSION[$cCode]['main_inputs'][$oValueName] = $defVal;
                                        $inputLabels[$oValueName] = $oValSpec['label'];
                                    }
                                    else {
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
                                        $inputLabels[$oValueName] = $oValSpec['label'] . "<small> (max: " . number_format($maxValue) . ")</small>";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //==memproses awal elemen2 yang terlibat, jika ada yang bisa dipre-process
        //==misalnya yang pilihannya cuma satu atau yang ada config nilai defaultnya
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                $elementConfigs[$eName]['autoSelect'] = false;
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    if (isset($eSpec['defaultValue'])) {
                        //==cek apakah ada seting defaultValue
                        $defValueSrc = makeValue($eSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $eSpec['defaultValue']);
                        cekHere("[$eName] [$defValueSrc]");
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
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
                                    $amdlName = $eSpec['mdlName'];
                                    $this->load->model("Mdls/" . $amdlName);
                                    $labelSrc = $eSpec['labelSrc'];
                                    $keySrc = $eSpec['key'];
                                    $oo = new $amdlName();
                                    $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                    if (sizeof($aFilter) > 0) {
                                        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                    }
                                    $tmpo = $oo->lookupAll()->result();
                                    if (sizeof($tmpo) == 1) {
                                        $usedKey = $eSpec['key'];
                                        $defValueSrc = $tmpo[0]->$usedKey;
                                        $configUiJenis = $this->configUi[$this->jenisTr];
                                        heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                    }
                                    break;
                                case "dataField":
                                    break;
                            }
                        }
                    }
                }
                else {
                    if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                    }
                    else {
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                $amdlName = $eSpec['mdlName'];
                                $this->load->model("Mdls/" . $amdlName);
                                $labelSrc = $eSpec['labelSrc'];
                                $keySrc = $eSpec['key'];
                                $oo = new $amdlName();
                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                if (sizeof($aFilter) > 0) {
                                    $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                }
                                $tmpo = $oo->lookupAll()->result();
                                if (sizeof($tmpo) == 1) {
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    $configUiJenis = $this->configUi[$this->jenisTr];
//                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                }
                                if (isset($eSpec["pairMethod"]["calculate"]["recalculate"]) && $eSpec["pairMethod"]["recom"]["calculate"]["recalculate"] == true) {
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    $sourceCalculate = $eSpec["pairMethod"]["calculate"]["source"];
                                    if (isset($_SESSION[$cCode]['main'][$sourceCalculate]) && ($_SESSION[$cCode]['main'][$sourceCalculate] > 0)) {
                                        $defValueSrc = $_SESSION[$cCode]['main'][$sourceCalculate];
//                                        cekHitam("** --- [$usedKey] --- " . $this->jenisTr . " [$eName] " . $eSpec['mdlName'] . " --- [$defValueSrc]");
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
//                                    matiHEre(__LINE__);
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
//        cekMerah($this->jenisTr);
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
//                cekHere("[$eName]");
                //reset dulu kalau yg tidak ada
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

                            //                            cekkuning("chek if i should reset $valID..");

                            if ($currentValue == $valID) {
                                //                                cekkuning("i wont reset $valID..");
                            }
                            else {


                            }
                        }
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relElementConfigs[$eName][$currentValue] as $rKey => $rcSpec) {
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
//                                        echo("$eName TIDAK ada dalam elementConfig<br>");
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
                            foreach ($addRowsConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
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
                                    $defVal = isset($_SESSION[$cCode]['main'][$oValueName]) && $_SESSION[$cCode]['main'][$oValueName] > 0 ? number_format(($_SESSION[$cCode]['main'][$oValueName] + 0)) : number_format($origDefValue);
                                    if (isset($addRowsConfigs[$eName][$currentValue][$oValueName]['role']) && $addRowsConfigs[$eName][$currentValue][$oValueName]['role'] == "minus") {
                                        $defVal = "(" . $defVal . ")";
                                    }
                                    //region auto hide row
                                    if (isset($oValSpec["validate_reference"])) {
                                        $validate_reference = $oValSpec["validate_reference"];
                                        $src_reference = $validate_reference["src_reference"];
                                        $cek_prevals = $_SESSION[$cCode]['main'][$src_reference];
                                        $inputType = isset($validate_reference["defaultView"][$cek_prevals]) ? "text" : "hidden";
                                    }
                                    else {
                                        $inputType = "text";
                                    }
                                    //endregion


                                    $addLabel = "";
                                    if (isset($oValSpec['saldo'])) {
                                        $saldo = makeValue($oValSpec['saldo'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        $addLabel = "<br>Saldo : <span class='strong'>" . number_format($saldo, 0, ",", ".") . "</span>";
                                        if ($saldo == 0) {
                                            $disabled = "disabled";
                                        }
                                    }

                                    $addRows[$oValueName] = "<input type=$inputType autocomplete='off' id='$oValueName' class='form-control text-right' style='font-size:17px;' $disabled placeholder='$oValueName' value='$defVal' $minValStr $maxValStr 
                                                                onfocus='this.select()' 
                                                                oonkeyup=\"$keyupStr\" 
                                                                onfocus=\"$keyupStr\"
                                                                onblur=\"$blurStr\"
                                                                onmouseout=\"$blurStr\"
                                                            >";


                                    $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                    $addRowLabels[$oValueName] = $oValSpec['label'] . $addLabel;
                                    //region auto hide row
                                    if (isset($oValSpec["validate_reference"])) {
                                        $validate_reference = $oValSpec["validate_reference"];
                                        $src_reference = $validate_reference["src_reference"];
                                        $cek_prevals = $_SESSION[$cCode]['main'][$src_reference];
                                        if (isset($validate_reference["defaultView"][$cek_prevals])) {

                                        }
                                        else {
                                            //hidden
                                            unset($addRowLabels[$oValueName]);
                                        }
                                    }
                                    //endregion
                                }
                            }
                        }
                    }
                }
                switch ($eSpec['elementType']) {
                    case "dataModel":
                        $addStr = "";
                        $editStr = "";
                        $amdlName = $eSpec['mdlName'];
                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
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
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
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
                        $tmpo = $oo->lookupAll()->result();
//                        cekMerah("query dari viewCart [$amdlName]");
//                        showLast_query("biru");
                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";

                        $hiddenSelect = "";
                        if (isset($eSpec['hiddenSelect']) && $eSpec['hiddenSelect'] == 1) {
                            $hiddenSelect = "hidden";
                        }
                        $hiddenBox = "";
                        if (isset($eSpec['hiddenBox']) && $eSpec['hiddenBox'] == 1) {
                            $hiddenBox = "hidden";
                        }

                        $elStr[$eName] .= "<div class='box-body $hiddenSelect'>";

                        switch ($eSpec['inputType']) {
                            case "combo":
                                $elStr[$eName] .= "<select class='form-control select2' data-style='btn-primary' data-live-search='true' data-size='10' data-container='body'onchange=\"hiliteDiv(this);document.getElementById('result').src=$selectorTarget;\">";
                                $elStr[$eName] .= "<option value=''>-select-</option>";
                                if (sizeof($tmpo) > 0) {
                                    if ($eName == "cash_account") {
                                        foreach ($tmpo as $row) {
                                            $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                            if (sizeof($ex) > 1) {
                                                $labelSrcAlias = isset($eSpec['labelSrcAlias']) ? $eSpec['labelSrcAlias'] : array();
                                                $optionSetting = isset($eSpec['optionSetting']) ? $eSpec['optionSetting'] : array();
                                                $labelValue = "";
                                                foreach ($ex as $col) {
                                                    $labelValue .= (isset($labelSrcAlias[$col]) && $labelSrcAlias[$col] != "" ? $labelSrcAlias[$col] . ":&nbsp;" . formatField_he_format($col, $row->$col) : formatField_he_format($col, $row->$col)) . " / ";
                                                }
                                                $labelValue = rtrim($labelValue, " / ");
                                                $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                            }
                                            else {
                                                $disabled = "";
                                                $disable_label = "";
                                                if (isset($row->jenis) && $row->jenis == "466") {
                                                    if ($row->fulldate <= "2025-03-03" && $row->_temp == "0") {
                                                        if ($this->jenisTr == "4643") {
                                                            $disabled = "disabled";
                                                            $disable_label = "<span>(PO ini dilakukan sebelum perubahan modul. Silahkan hubungi admin untuk dibantu update.)</span>";
                                                        }

                                                    }
                                                }
                                                $key_frmt = $labelSrc == "extern2_nama" ? "nomer" : $keySrc;
                                                $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                $labelAccount = isset($row->$labelSrc) ? formatField_he_format($key_frmt, $row->$labelSrc) : '-';
                                                if (isset($elementConfigs[$eName]['labelSrcFields']) && (sizeof($elementConfigs[$eName]['labelSrcFields']) > 0)) {
                                                    $anu = "";
                                                    foreach ($elementConfigs[$eName]['labelSrcFields'] as $field_account) {
                                                        $anu .= formatField_he_format($key_frmt, $row->$field_account) . " &nbsp; ";
                                                    }
                                                    $labelAccount = $anu;
                                                }
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
//                                                $elStr[$eName] .= "<option $disabled value='" . $row->$keySrc . "' $selected>" . formatField_he_format($key_frmt, $row->$labelSrc) . " $disable_label</option>";
                                                $elStr[$eName] .= "<option $disabled value='" . $row->$keySrc . "' $selected>" . $labelAccount . " $disable_label</option>";
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($tmpo as $row) {
                                            $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                            if (sizeof($ex) > 1) {
                                                $labelSrcAlias = isset($eSpec['labelSrcAlias']) ? $eSpec['labelSrcAlias'] : array();
                                                $optionSetting = isset($eSpec['optionSetting']) ? $eSpec['optionSetting'] : array();
                                                $labelValue = "";
                                                foreach ($ex as $col) {
                                                    $labelValue .= (isset($labelSrcAlias[$col]) && $labelSrcAlias[$col] != "" ? $labelSrcAlias[$col] . ":&nbsp;" . formatField_he_format($col, $row->$col) : formatField_he_format($col, $row->$col)) . " / ";
                                                }
                                                $labelValue = rtrim($labelValue, " / ");
                                                $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                            }
                                            else {
//                                            arrPrint($row);
                                                $disabled = "";
                                                $disable_label = "";
                                                if (isset($row->jenis) && $row->jenis == "466") {
                                                    if ($row->fulldate <= "2025-03-03" && $row->_temp == "0") {
                                                        if ($this->jenisTr == "4643") {
                                                            $disabled = "disabled";
                                                            $disable_label = "<span>(PO ini dilakukan sebelum perubahan modul. Silahkan hubungi admin untuk dibantu update.)</span>";
                                                        }

                                                    }
                                                }
                                                $key_frmt = $labelSrc == "extern2_nama" ? "nomer" : $keySrc;
                                                $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                $elStr[$eName] .= "<option $disabled value='" . $row->$keySrc . "' $selected>" . formatField_he_format($key_frmt, $row->$labelSrc) . " $disable_label</option>";
                                            }
                                        }
                                    }
                                }
                                $elStr[$eName] .= "</select>";
                                break;
                            case "radio":
                                if (sizeof($tmpo) > 0) {
                                    switch ($eName) {
                                        case "cash_account":
                                        case "cash_account_source":
                                        case "cash_account_target":
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
                                                    $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                                <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
                                            onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelValue . " </label>\n";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                    $validate_element = isset($eSpec["validate"]) ? $eSpec["validate"] : array();

                                                    if (($eName == "referensiNota") && (array_key_exists($row->$keySrc, $validate_element))) {
                                                        $selectorTargetCancel = MODUL_PATH . "_shoppingCart/clearContent/" . $this->jenisTr;
                                                        $warning = isset($eSpec["validate"][$row->$keySrc]) ? $eSpec["validate"][$row->$keySrc] : "";
                                                        $arrAlert = array(
                                                            "html" => "$warning<br><button id=cancel class=btn btn-danger style=font-size:20px;background-color:red;color:#ffffff;>CANCEL</button>",
                                                        );
                                                        $arrAlertFix = array(
                                                            "type" => "warning",
                                                            "title" => "<span style=color: red;>Perhatian</span>",
                                                            "showCloseButton" => false,
                                                            "allowOutsideClick" => false,
                                                            "allowEscapeKey" => false,
                                                            "confirmButtonText" => "YA, LANJUTKAN",
//                                                    "cancelButtonText" => "CANCEL",
//                                                    "showCancelButton" => true,
//                                                    "background" => "#737574",
                                                        );
                                                        $arrAlertFinal = $arrAlert + $arrAlertFix;
                                                        $hasil = "";
                                                        foreach ($arrAlertFinal as $ky => $val) {
                                                            $var = "$ky: '$val'";
                                                            if ($hasil == "") {
                                                                $hasil = "$var";
                                                            }
                                                            else {
                                                                $hasil = "$hasil, $var";
                                                            }
                                                        }
                                                        $hasil .= ", onOpen:function(){
                                                    top.$('#cancel').on('click',function(){
                                                        document.getElementById('result').src='$selectorTargetCancel';
                                                    })
                                                    }";
                                                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key=" . $row->$keySrc . "'";

                                                        $labelAccount = isset($row->$labelSrc) ? $labelValue : '-';
                                                        if (isset($elementConfigs[$eName]['labelSrcFields']) && (sizeof($elementConfigs[$eName]['labelSrcFields']) > 0)) {
                                                            $anu = "";
                                                            foreach ($elementConfigs[$eName]['labelSrcFields'] as $field_account) {
                                                                $anu .= $row->$field_account . " &nbsp; ";
                                                            }
                                                            $labelAccount = $anu;
                                                        }


                                                        $arrBankRekening[$row->folders][] = "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                                    <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
                                                    onclick=\"hiliteDiv(this);swal({" . $hasil . "}).then(function(isConfirm) {
                                                    if (isConfirm) {
                                                            document.getElementById('result').src=$selectorTarget
                                                    }

                                                }
                                                );
                                                \">" . $labelAccount . "</label><br>\n";
                                                    }
                                                    else {
                                                        $labelAccount = isset($row->$labelSrc) ? $labelValue : '-';
                                                        if (isset($elementConfigs[$eName]['labelSrcFields']) && (sizeof($elementConfigs[$eName]['labelSrcFields']) > 0)) {
                                                            $anu = "";
                                                            foreach ($elementConfigs[$eName]['labelSrcFields'] as $field_account) {
                                                                $anu .= $row->$field_account . " &nbsp; ";
                                                            }
                                                            $labelAccount = $anu;
                                                        }
                                                        $arrBankRekening[$row->folders][] = "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                                <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
                                                onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelAccount . "</label><br>\n";
                                                    }
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
                                            break;
                                        default:
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
                                                    $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                                <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
                                            onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelValue . " </label>\n";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                    $validate_element = isset($eSpec["validate"]) ? $eSpec["validate"] : array();

                                                    if (($eName == "referensiNota") && (array_key_exists($row->$keySrc, $validate_element))) {
                                                        $selectorTargetCancel = MODUL_PATH . "_shoppingCart/clearContent/" . $this->jenisTr;
                                                        $warning = isset($eSpec["validate"][$row->$keySrc]) ? $eSpec["validate"][$row->$keySrc] : "";
                                                        $arrAlert = array(
                                                            "html" => "$warning<br><button id=cancel class=btn btn-danger style=font-size:20px;background-color:red;color:#ffffff;>CANCEL</button>",
                                                        );
                                                        $arrAlertFix = array(
                                                            "type" => "warning",
                                                            "title" => "<span style=color: red;>Perhatian</span>",
                                                            "showCloseButton" => false,
                                                            "allowOutsideClick" => false,
                                                            "allowEscapeKey" => false,
                                                            "confirmButtonText" => "YA, LANJUTKAN",
//                                                    "cancelButtonText" => "CANCEL",
//                                                    "showCancelButton" => true,
//                                                    "background" => "#737574",
                                                        );
                                                        $arrAlertFinal = $arrAlert + $arrAlertFix;
                                                        $hasil = "";
                                                        foreach ($arrAlertFinal as $ky => $val) {
                                                            $var = "$ky: '$val'";
                                                            if ($hasil == "") {
                                                                $hasil = "$var";
                                                            }
                                                            else {
                                                                $hasil = "$hasil, $var";
                                                            }
                                                        }
                                                        $hasil .= ", onOpen:function(){
                                                    top.$('#cancel').on('click',function(){
                                                        document.getElementById('result').src='$selectorTargetCancel';
                                                    })
                                                    }";
                                                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key=" . $row->$keySrc . "'";

                                                        $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                                    <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
                                                    onclick=\"hiliteDiv(this);swal({" . $hasil . "}).then(function(isConfirm) {
                                                    if (isConfirm) {
                                                            document.getElementById('result').src=$selectorTarget
                                                    }

                                                }
                                                );
                                                \">" . (isset($row->$labelSrc) ? $row->$labelSrc : '-') . "</label>\n";
                                                    }
                                                    else {
                                                        $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                                <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
                                                onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . (isset($row->$labelSrc) ? $row->$labelSrc : '-') . "</label>\n";
                                                    }
                                                    //-------
//                                            if(isset($eSpec["elementResetor"]) && (array_key_exists($row->$keySrc, $eSpec["elementResetor"]))){
//                                                mati_disini("reset bro...");
//                                            }
                                                    //-------
                                                }
                                            }
                                            break;
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
                                $defValue .= "<div class='panel-body'>";
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
                                            $newValue_r = $fieldLabel == "" && $showNull == true && $nullValue != "" ? ($nullSrc == $label ? $nullValue : $fieldLabel) : $fieldLabel;
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
//                                            $defValue .= "<td $label align='left' fieldLabel='$fieldLabel' key#1='$src' class='text-bold text-uppercase'>$semicolonnbsp" . $newValue_r;
                                            $defValue .= "<td $label align='left' fieldLabel='$fieldLabel' key#1='$src' class='text-bold text-uppercase'>$semicolonnbsp" . formatField_he_format($src, $newValue_r);
                                            $defValue .= "</td>";
                                        }
                                        else {
                                            $defValue .= "<td align='left' colspan='2' key#2='$src' class='text-bold text-uppercase'>$semicolonnbsp " . formatField($src, $fieldLabel);
                                            $defValue .= "</td>";
                                        }
                                    }
                                    $defValue .= "</tr>";
                                }
                                $defValue .= "</table>";
                                $defValue .= "</div class='panel-body'>";
                            }
                        }
                        else {
                            //menentukan nilai default
                        }

                        if ($defKey > 0) {
                            if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                    $editClick = "
                                        BootstrapDialog.show({
                                            title:'New " . $eSpec['label'] . "',
                                            message: $('<div></div>').load('" . $editLink . "'),
                                            draggable:true,
                                            size:BootstrapDialog.SIZE_WIDE,
                                            closable:true,
                                            type:top.BootstrapDialog.TYPE_SUCCESS,
                                        });";
                                    $editStr = "<a href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                }
                            }
                        }

                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue ";
                        $elStr[$eName] .= "</div id='el$amdlName'>";

                        $elements[$eName] = array(
                            "hiddenBox" => $hiddenBox,
                            "hiddenSelect" => $hiddenSelect,
                            "type" => $eSpec['inputType'],
                            "mdlName" => $eSpec['mdlName'],
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                            "editStr" => $editStr,
                            "addStr" => $addStr,
                            "bgColor" => $defValue == "" ? "#fcfce0" : "#f5fff9",
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

                        $hiddenSelect = "";
                        if (isset($eSpec['hiddenSelect']) && $eSpec['hiddenSelect'] == 1) {
                            $hiddenSelect = "hidden";
                        }
                        $hiddenBox = "";
                        if (isset($eSpec['hiddenBox']) && $eSpec['hiddenBox'] == 1) {
                            $hiddenBox = "hidden";
                        }

                        $elStr[$eName] .= "<div class='box-body $hiddenSelect'>";
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


                        $elements[$eName] = array(
                            "hiddenSelect" => $hiddenSelect,
                            "hiddenBox" => $hiddenBox,
                            "mdlName" => null,
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
                        switch ($eSpec['elementType']) {
                            case "dataModel":
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
//                                cekmerah($this->db->last_query());


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

        if ($showScheme) {
            $awal_pinjaman = isset($_SESSION[$cCode]['main']['awal_pinjaman']) ? $_SESSION[$cCode]['main']['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($_SESSION[$cCode]['main']['jatuh_tempo']) ? $_SESSION[$cCode]['main']['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($_SESSION[$cCode]['main']['harga']) ? $_SESSION[$cCode]['main']['harga'] : 0;
            $rate_bunga = isset($_SESSION[$cCode]['main']['persen_bunga']) ? $_SESSION[$cCode]['main']['persen_bunga'] : 0;
            $npwp = "";
            $pph_nilai = strlen($npwp) > 10 && $pph_nilai == 15 ? 15 : 15; //dipaksa 15% untuk pemegang saham
            $valid_bunga = ($nilai_pinjaman / 12);
            $nilai_bunga = ($valid_bunga * $rate_bunga) / 100;
            $nilai_pph23 = ($nilai_bunga * $pph_nilai) / 100;
            $period = new DatePeriod(new DateTime($awal_pinjaman), new DateInterval('P1D'), new DateTime($jatuh_tempo));
            $periodNow = new DatePeriod(new DateTime($awal_pinjaman), new DateInterval('P1D'), new DateTime(date('Y-m-d')));
            $arrBulan = array();
            $arrBulanNow = array();
            $arrHarian = array();
            $arrWaktu = array();
            foreach ($period as $key => $value) {
                if (!isset($arrBulan[$value->format('Y-m')])) {
                    $arrBulan[$value->format('Y-m')] = array();
                }
                $arrBulan[$value->format('Y-m')][] = $value->format('Y-m-d');
            }
            foreach ($periodNow as $key => $value) {
                if (!isset($arrBulanNow[$value->format('Y-m')])) {
                    $arrBulanNow[$value->format('Y-m')] = array();
                }
                $arrBulanNow[$value->format('Y-m')][] = $value->format('Y-m-d');
            }
            $hariPadaBulanJatuhTempo = isset($arrBulan[date('Y-m', strtotime($jatuh_tempo))]) ? count($arrBulan[date('Y-m', strtotime($jatuh_tempo))]) : 0;
            $arrBulan[date('Y-m', strtotime($jatuh_tempo))][$hariPadaBulanJatuhTempo] = date('Y-m-d', strtotime($jatuh_tempo));
            $total_hari = 0;
            $total_bulan = 0;
            foreach ($arrBulan as $thnbln => $thblntgl) {
                $tmp = array("thnbln" => $thnbln,
                    "jml_hari_dbln" => count($arrBulan[$thnbln]),
                    "nilai_pinjaman" => $nilai_pinjaman,
                    "rate_bunga" => $rate_bunga,
                    "valid_bunga" => $valid_bunga * (count($arrBulan[$thnbln]) / 30),
                    "nilai_bunga" => $nilai_bunga * (count($arrBulan[$thnbln]) / 30),
                    "nilai_pph23" => $nilai_pph23 * (count($arrBulan[$thnbln]) / 30),
                    "nett_bunga" => $nilai_bunga * (count($arrBulan[$thnbln]) / 30) - ($nilai_pph23 * (count($arrBulan[$thnbln]) / 30)),
                    "silangan" => isset($arrBulanNow[$thnbln]) ? ($thnbln != date('Y-m') ? "hijau" : "berjalan") : "merah",
                );
                if (!isset($arrCons[$thnbln])) {
                    $arrCons[$thnbln] = array();
                }
                $arrCons[$thnbln] = $tmp;
                $total_bulan++;
                $total_hari += count($arrBulan[$thnbln]);
            }
            $nmPemengangSaham = "belum memilih kreditur";
            foreach ($items as $ids => $data) {
                $nmPemengangSaham = isset($data['nama']) ? $data['nama'] : "<span class='text-bol text-red'>belum ditentunkan</span>";
            }
            $headerScheme = array("nama" => "$nmPemengangSaham",
                "jml_pinjaman" => "$nilai_pinjaman",
                "bunga_tahunan" => "$rate_bunga",
                "awal_meminjam" => date('d F Y', strtotime($awal_pinjaman)),
                "pelunasan_pinjaman" => date('d F Y', strtotime($jatuh_tempo)),
                "lama_pinjaman" => "$total_hari hari ($total_bulan bln)",
            );
        }

        $detilSizeBar = array();
        if (isset($elements['detilSize'])) {
            $detilSizeBar = array(
                //CKD
                "volume_gross" => isset($main['volume_gross']) ? number_format(conv_mmc_mc($main['volume_gross']), 2) : 0,
                "berat_gross" => isset($main['berat_gross']) ? conv_g_kg($main['berat_gross']) : 0,
                //CBU
                "volume" => isset($main['volume']) ? number_format(conv_mmc_mc($main['volume']), 2) : 0,
                "berat" => isset($main['berat']) ? conv_g_kg($main['berat']) : 0,
            );
        }

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


        //-------------------------------------------------------------------------------
        $elementMutasiConfig = $this->config->item("accountElementMutasi") != NULL ? $this->config->item("accountElementMutasi") : array();
        $elementMutasi = array();
        if (sizeof($elementMutasiConfig) > 0) {
            if ($this->session->login['cabang_id'] < 0) {
                $subjectID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : "";
                $elementMutasiTmp = $elementMutasiConfig["center"];
                foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                    $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID?o=" . $this->session->login['cabang_id'];
                }
            }
            else {
                $elementMutasiTmp = $elementMutasiConfig["branch"];
                foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                    $sourceID = isset($el_spec['source']) ? $el_spec['source'] : "";
                    $subjectID = isset($_SESSION[$cCode]['main'][$sourceID]) ? $_SESSION[$cCode]['main'][$sourceID] : "";
                    $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID?o=" . $this->session->login['cabang_id'];
                }
            }

        }
        //-------------------------------------------------------------------------------


        //-------------------------------------------------------------------------------
        $shoppingCartDetail = isset($this->configUi[$this->jenisTr]['shoppingCartDetail']) ? $this->configUi[$this->jenisTr]['shoppingCartDetail'] : array();
        if (sizeof($shoppingCartDetail) > 0) {
            if (isset($shoppingCartDetail["enabled"]) && ($shoppingCartDetail["enabled"] == true)) {
                $targetGate = isset($shoppingCartDetail["targetGate"]) ? $shoppingCartDetail["targetGate"] : NULL;
                $shoppingCartDetailData = ($targetGate != NULL) ? $_SESSION[$cCode][$targetGate] : array();
                $shoppingCartDetailHeader = $shoppingCartDetail["shoppingCartDetailFields"][1] + $shoppingCartDetail["shoppingCartDetailNumFields"][1] + array("subtotal" => "Subtotal");
                $shoppingCartDetailSumFields = $shoppingCartDetail["shoppingCartDetailSumFields"][1];

            }
        }
        //-------------------------------------------------------------------------------

        //region efaktur
        $dataFaktur = array();
        if (count($shopingCartFaktur) > 0) {
//            arrPrint($shopingCartFaktur);
//            cekMerah(__LINE__);
//            if ($_SESSION[$cCode]["main"]["dpp_final"] == 0) {
//                cekHitam("masuk sini");
////                $_SESSION[$cCode]["main"]["skip_faktur"]="true";
//            }
//            else {
            foreach ($shopingCartFaktur["fields"] as $key_faktur => $faktur_labels) {
                $dataFaktur[$key_faktur] = isset($_SESSION[$cCode]["main"][$key_faktur]) ? $_SESSION[$cCode]["main"][$key_faktur] : "";
            }
//            }

            $cekPPN = isset($shopingCartFaktur["enabledPpn"]) ? $shopingCartFaktur["enabledPpn"] : NULL;
            $show = true;
            if ($cekPPN != NULL) {
                $cekPPN_nilai = isset($_SESSION[$cCode]["main"][$cekPPN]) ? $_SESSION[$cCode]["main"][$cekPPN] : 0;
//                $show = ($cekPPN_nilai > 0) ? true : false;
            }
        }

        //endregion

        //region untuk menampilkan nota yang dipilih
        $master_item_label = isset($this->configUi[$this->jenisTr]['master_item_label']) ? $this->configUi[$this->jenisTr]['master_item_label'] : array();
        $master_item = array();
        foreach ($master_item_label as $ky => $kyLabel) {
            if (isset($_SESSION[$cCode]["main"][$ky]) && ($_SESSION[$cCode]["main"][$ky] != NULL)) {
                $master_item[$ky] = $_SESSION[$cCode]["main"][$ky];
            }
        }
        //endregion


        //meta produk
        $this->load->model("Mdls/MdlProduk");
        $mp = new MdlProduk();
        $tmpMetaProduk = $mp->lookupAll()->result();

        $metaProduk = array();
        if (!empty($tmpMetaProduk)) {
            foreach ($tmpMetaProduk as $prd) {
                $metaProduk[$prd->id]['satuan'] = $prd->size_nama;
                $metaProduk[$prd->id]['kategori'] = $prd->kategori_nama;
                $metaProduk[$prd->id]['kode'] = $prd->kode;
            }
        }

        $elementResetTarget = MODUL_PATH . get_class($this) . "/resetElement/" . $this->jenisTr . "/";
        $itemsElementResetTarget = MODUL_PATH . get_class($this) . "/resetItemsElement/" . $this->jenisTr . "/";

        if (isset($modePymSrc) && ($modePymSrc == "pymSrc")) {
            $itemLabels = isset($this->configUi[$this->jenisTr]['shoppingCartFieldsPymSrc'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFieldsPymSrc'][$stepNumber] : array();
            $editableFields = array();
        }

        $data = array(
            "jenisTr" => $this->jenisTr,
            "main" => $main,
            "metaProduk" => $metaProduk,
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
            "items5" => isset($_SESSION[$cCode]['items5_new']) ? $_SESSION[$cCode]['items5_new'] : array(),
            "tagihan_um" => isset($_SESSION[$cCode]['main']['tagihan']) ? $_SESSION[$cCode]['main']['tagihan'] : array(),
            "project__harga" => isset($_SESSION[$cCode]['main']['referensi_so_project__harga']) ? $_SESSION[$cCode]['main']['referensi_so_project__harga'] : 0,
            "pph23" => isset($_SESSION[$cCode]['main']['pph23']) ? $_SESSION[$cCode]['main']['pph23'] : 0,
            "kas_nilai" => isset($_SESSION[$cCode]['main']['kas_nilai']) ? $_SESSION[$cCode]['main']['kas_nilai'] : 0,
            "biaya_transfer" => isset($_SESSION[$cCode]['main']['biaya_transfer']) ? $_SESSION[$cCode]['main']['biaya_transfer'] : 0,
            "itemLabels" => $itemLabels,
            "itemLabels2" => $itemLabels2,
            "itemLabels3" => $itemLabels3,
            "numLabels" => $itemNumLabels,
            "detilSizeBar" => $detilSizeBar,
            //            "itemLabels"=>$itemLabels,
            "sumRows" => isset($this->configUi[$this->jenisTr]['shoppingCartSumFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartSumFields'][$stepNumber] : $this->configLayout[$this->jenisTr]['receiptSumFields'][$stepNumber],
            "sumRows2" => $sumRows2,
            "sumRows3" => isset($this->configUi[$this->jenisTr]['shoppingCartSumFields3'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartSumFields3'][$stepNumber] : isset($this->configLayout[$this->jenisTr]['receiptSumFields3'][$stepNumber]) ? $this->configLayout[$this->jenisTr]['receiptSumFields3'][$stepNumber] : array(),
            "sumSpec2" => $sumSpec2,
            "sumType2" => $sumType2,
            "sumRowAction" => base_url() . "ValueGate/evalFees/" . $this->jenisTr,
            "editableFields" => $editableFields,
            "editableFields2" => $editableFields2,
            //            "applets"        => $applets,
            "elements" => $elements,
            "elementConfigs" => $elementConfigs,
            "elementConfigMutasi" => isset($elementMutasi) ? $elementMutasi : array(),
            "inputs" => $inputs,
            "inputLabels" => $inputLabels,
            "grandTotal" => isset($_SESSION[$cCode]['main']['grand_total']) ? $_SESSION[$cCode]['main']['grand_total'] : 0,

            //            "appletConfig" => $appletConfigs,
            "resetLink" => MODUL_PATH . get_class($this) . "/reset/" . $this->jenisTr,
            "minValues" => $minValue,
            "addRows" => $addRows,
            "addRowLabels" => $addRowLabels,
            "addRowHiddens" => $addRowHiddens,
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
            //            "settlementByProduk" => "",
            "settlementByBahan" => isset($_SESSION[$cCode]['items9_sum']) ? $_SESSION[$cCode]['items9_sum'] : array(),
            "settlementByPayment" => isset($_SESSION[$cCode]['items7_sum']) ? $_SESSION[$cCode]['items7_sum'] : array(),
            "settlementTotal" => array(),
            //--------------------------------------------
            "arrHeaderElement" => isset($arrHeaderElement) ? $arrHeaderElement : array(),
            "arrHeaderElementJenis" => $this->jenisTr,

            "subItemLabels" => $shoppingCartFieldsSubItems,
            "avoidRemoveAll_items" => $avoidRemoveAll_items,
            "items2_komposisi" => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),

            "itemElements" => isset($itemsElements) ? $itemsElements : array(),
            "itemElementConfigs" => isset($elementItemsAutoConfigs) ? $elementItemsAutoConfigs : array(),
            "itemElementResetTarget" => isset($itemsElementResetTarget) ? $itemsElementResetTarget : "",
            "shopingCartAddTax" => $shopingCartTaxAdd,
            "shopingCartAddOption" => $shopingCartTaxOption,
            "shopingCartAddTaxAction" => MODUL_PATH . "_processPihak/selectTaxes/" . $this->jenisTr,
            "checkTaxes" => isset($_SESSION[$cCode]['main']['selectedType_konsumen']) ? $_SESSION[$cCode]['main']['selectedType_konsumen'] : "",
            "checkOption" => isset($_SESSION[$cCode]['main']['selectedType_uangmuka']) ? $_SESSION[$cCode]['main']['selectedType_uangmuka'] : "",
            "default_description" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            "columnRecorderTarget" => MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr,
            "viewDescriptionNote" => isset($this->configUi[$this->jenisTr]['viewDescriptionNote']) ? $this->configUi[$this->jenisTr]['viewDescriptionNote'] : false,
            //--------------------------------------------
            "shoppingCartDetailData" => isset($shoppingCartDetailData) ? $shoppingCartDetailData : array(),
            "shoppingCartDetailHeader" => isset($shoppingCartDetailHeader) ? $shoppingCartDetailHeader : array(),
            "shoppingCartDetailSumFields" => isset($shoppingCartDetailSumFields) ? $shoppingCartDetailSumFields : array(),
            "shopingCartFakturParam" => $shopingCartFaktur,
            "shopingCartFakturItems" => $dataFaktur,
            "shopingCartFakturTarget" => isset($shopingCartFaktur["editProcess"]) ? $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr : "",
            "showFormulirFaktur" => isset($show) ? $show : false,

            //--------------
            "arrItemTidakDibayar" => $arrItemTidakDibayar,
            "shopingCartPaymentItemsColor" => $shopingCartPaymentItemsColor,
            "master_item_label" => $master_item_label,
            "masterItems" => $master_item,
            "modePymSrc" => isset($_SESSION[$cCode]["main"]["modePymSrc"]) ? $_SESSION[$cCode]["main"]["modePymSrc"] : NULL,
        );

        //==selector for pairedItem
        if (sizeof($pairedItem) > 0) {
            if (isset($pairedItem['enabled']) && ($pairedItem['enabled'] == true)) {
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

        $shopingCartReload = isset($this->configUi[$this->jenisTr]["shopingCartReload"]) ? $this->configUi[$this->jenisTr]["shopingCartReload"] : false;
        $kurangBayarValidator = isset($this->configUi[$this->jenisTr]["kurangBayarValidator"]) ? $this->configUi[$this->jenisTr]["kurangBayarValidator"] : array();


        if ($shopingCartReload == true) {
            //----
            if (!isset($_SESSION[$cCode]["main"]["nilai_entry"])) {
                $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
            }
//            else{
//                //untuk sementara
//                if($_SESSION[$cCode]["main"]["nilai_entry"] > $_SESSION[$cCode]["main"]["nilai_bayar_netto"]){
//                    $_SESSION[$cCode]["main"]["nilai_entry"]=$_SESSION[$cCode]["main"]["nilai_bayar_netto"];
//                }
//            }

            //----
            if (isset($kurangBayarValidator["enabled"]) && ($kurangBayarValidator["enabled"] == true)) {
                if ($_SESSION[$cCode]["main"]["nilai_entry"] > $_SESSION[$cCode]["main"]["nilai_bayar_netto"]) {
                    if (isset($_SESSION[$cCode]["main"]["cash_account__jenis"]) && ($_SESSION[$cCode]["main"]["cash_account__jenis"] == "account_cash")) {
                        $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
                    }
                    else {
                        cekMerah("BUKAN account_cash");
                        if ($_SESSION[$cCode]["main"]["nilai_entry"] > $_SESSION[$cCode]["main"]["nilai_bayar_netto"]) {

                        }
                    }
                }
                else {
                    cekMerah("dicicil...");
                }
            }
            else {
                if (isset($_SESSION[$cCode]["main"]["cash_account__jenis"]) && ($_SESSION[$cCode]["main"]["cash_account__jenis"] == "account_cash")) {
                    $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
                }
                else {
                    if ($_SESSION[$cCode]["main"]["nilai_entry"] > $_SESSION[$cCode]["main"]["nilai_bayar_netto"]) {

                    }
                    else {
                        $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
                    }
                }
            }
            //----
            if (isset($_SESSION[$cCode]["main"]["replace_nilai_entry"]) && ($_SESSION[$cCode]["main"]["replace_nilai_entry"] == 1)) {
                $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
            }
            //----
            if ($_SESSION[$cCode]["main"]["nilai_entry"] > $_SESSION[$cCode]["main"]["nilai_bayar_netto"]) {
                $_SESSION[$cCode]["main"]["lebih_bayar"] = $_SESSION[$cCode]["main"]["nilai_entry"] - $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
            }
            else {
                $_SESSION[$cCode]["main"]["lebih_bayar"] = 0;
            }


//            if (isset($_SESSION[$cCode]["main"]["cash_account__jenis"]) && ($_SESSION[$cCode]["main"]["cash_account__jenis"] == "account_cash")) {
//                if($_SESSION[$cCode]["main"]["nilai_entry"] > $_SESSION[$cCode]["main"]["nilai_bayar_netto"]){
//
//                    $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
//                }
////                $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];
//            }


            $this->load->helper("he_value_builder");
            fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, array(), my_ppn_factor());
            if (isset($_GET['stop1']) && $_GET['stop1'] == 1) {
            }
            else {
                echo "<script>";
                echo "setTimeout(function(){top.$('div#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0&stop1=1');},1000) ";
                echo "</script>";
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

        $this->load->view($shopingCartView, $data);

    }

    public function fetchModelDescription()
    {
        $jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);
        $id = $this->uri->segment(6);
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
        $relShopingCartInjected = isset($this->configUi[$this->jenisTr]['receiptElements'][$elName]) ? $this->configUi[$this->jenisTr]['receiptElements'][$elName] : array();
        $relativeElement = isset($this->configUi[$this->jenisTr]['relativeElements'][$elName]) ? $this->configUi[$this->jenisTr]['relativeElements'][$elName] : array();
        $key = $_GET['key'];

        if (count($relShopingCartInjected) > 0 && isset($relShopingCartInjected["relInject"])) {
            if (isset($relShopingCartInjected["relInject"][$key])) {
                foreach ($relShopingCartInjected["relInject"][$key] as $injectedKey => $valInjected) {
                    $_SESSION[$cCode]["main"][$injectedKey] = $valInjected;
                }
            }

//            cekHitam("inject nilai ".$key);
        }
        //unset dulu semua yang berelasi
        if (count($relativeElement) > 0) {
            if (isset($relativeElement[$key])) {
                //reset elemt terkait biar gak salah gerbang
                $key_reset = array_keys($relativeElement[$key])[0];
                unset($_SESSION[$cCode]["main_elements"][$key_reset]);
            }
            //reset juga gerbang main yang lama
            foreach ($relativeElement as $key_rel => $dataRel) {
                foreach ($dataRel as $m_key => $mDatas) {
                    if (isset($_SESSION[$cCode]["main"][$m_key])) {
//                        matiHere("data main belum direst ");
                        unset($_SESSION[$cCode]["main"][$m_key]);
                    }
//                    foreach($mDatas as )
                    if (isset($mDatas["usedFields"])) {
                        if (isset($_SESSION[$cCode]["main"][$key_reset . "__label"])) {
                            unset($_SESSION[$cCode]["main"][$key_reset . "__label"]);
                        }
                        foreach ($mDatas["usedFields"] as $keys => $key_labels) {
                            if (isset($_SESSION[$cCode]["main"][$key_reset . "__" . $keys])) {
                                unset($_SESSION[$cCode]["main"][$key_reset . "__" . $keys]);
                            }
                        }
                    }
//                    matiHere(__LINE__);
                }
//                arrPrint($dataRel);

            }

        }

        if (count($relShopingCartInjected) > 0 && isset($relShopingCartInjected["elementResetor"][$key])) {
            foreach ($relShopingCartInjected["elementResetor"][$key] as $mval_reset) {
                $c_reset = strlen($mval_reset);
                foreach ($_SESSION[$cCode]['main'] as $mkey => $mxx) {
                    if (substr($mkey, 0, $c_reset) == $mval_reset) {
                        $_SESSION[$cCode]['main'][$mkey] = NULL;
                        unset($_SESSION[$cCode]['main'][$mkey]);

                        $_SESSION[$cCode]['tableIn_master_values'][$mkey] = NULL;
                        unset($_SESSION[$cCode]['tableIn_master_values'][$mkey]);
                    }
                }
            }
        }

        switch ($jenisTr) {
            case "4656":
                switch ($mdlName) {
                    case "MdlProdukProjectItems":
                        $this->load->model("Mdls/$mdlName");
                        $pr = new $mdlName();
                        $pr->setFilters(array());
                        $pr->addFilter("id=" . $key);
                        $tmps = $pr->lookupAll()->result();
                        $quot_id = $tmps[0]->quot_id;
                        $master_id = $tmps[0]->transaksi_id;
                        $uangmuka_rp = $tmps[0]->uangmuka_rp;
                        $uangmuka_persen = $tmps[0]->uangmuka_persen;
                        $tmpUmProject = $uangmuka_rp;
                        $sumber_nilai = $_SESSION[$cCode]['main']['source_main_harga'];
                        $bisa_pakai = $tmpUmProject > $sumber_nilai ? $sumber_nilai : $tmpUmProject;

                        if (!empty($_SESSION[$cCode]['items'])) {
                            foreach ($_SESSION[$cCode]['items'] as $itemID => $dts) {
                                $_SESSION[$cCode]['items'][$itemID]['harga'] = $bisa_pakai;
                                $_SESSION[$cCode]['items'][$itemID]['harga_bisa_pakai'] = $bisa_pakai;
                                $_SESSION[$cCode]['items'][$itemID]['subtotal'] = $_SESSION[$cCode]['items'][$itemID]['jml'] * $bisa_pakai;
                            }
                        }
                        break;
                }
                break;
        }

        // arrPrint("jenisTr: $jenisTr");
        // arrPrint("elName: $elName");
        // arrPrint("mdlName: $mdlName");
        // arrPrint("key: $key");
        // arrPrint($this->configUiJenis);

//        cekMerah("($jenisTr, $elName, $mdlName, $key, ".arrPrint($this->configUiJenis).")");
//        matihere(__LINE__);

        heFetchElement_modul($jenisTr, $elName, $mdlName, $key, $this->configUiJenis);

//        $nilai = $_SESSION[$cCode]['main']["uang_muka_dipakai"] + $_SESSION[$cCode]['main']["credit_note_dipakai"] + $_SESSION[$cCode]['main']["pph23"];
//        $_SESSION[$cCode]['main']['nilai_entry'] = $_SESSION[$cCode]['main']["sisa"] - $nilai;
//        matiHEre();

        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


        //build items5 jika milih UM PROJECT
        if ($elName == "referensi_so_project" && $jenisTr == 4467) {

            $project_selected_id = $_SESSION[$cCode]['main']['referensi_so_project'];
            $project_selected_name = $_SESSION[$cCode]['main']['referensi_so_project__label'];

            $this->buildProjectDatas($project_selected_id, $cCode);

//            cekHere($elName);
//            cekHere($project_selected_id . " || " . $project_selected_name);
//            matiHere(__LINE__);
        }


        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function recordFieldElement()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $val = ($_GET['val']);
        $elementConfigs = isset($this->configUi[$jenisTr]['receiptElements']) ? $this->configUi[$jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->configUi[$jenisTr]['relativeElements']) ? $this->configUi[$jenisTr]['relativeElements'] : array();
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;

        heRecordElement_modul($jenisTr, $elName, $val, $configUiJenis);
        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
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
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;


        $_SESSION[$cCode]['main'][$elName] = null;
        $_SESSION[$cCode]['main'][$elName . "__label"] = null;

        //==reset kloningan elemen di main dan kroni2nya
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            foreach ($_SESSION[$cCode]['main'] as $key => $val) {
                if (strpos($key, $elName . "__") !== false) {
                    $_SESSION[$cCode]['main'][$key] = null;
                    unset($_SESSION[$cCode]['main'][$key]);
                }
            }
        }

        if (sizeof($configUiJenis["relativeElements"]) > 0) {

            foreach ($configUiJenis["relativeElements"] as $ee => $eeSpec) {
                foreach ($eeSpec as $ff => $ffSpec) {
                    foreach ($ffSpec as $gg => $ggSpec) {
                        if ($gg == $elName) {
                            if (isset($ggSpec["resetor"])) {
                                foreach ($ggSpec["resetor"] as $resetor) {
                                    $_SESSION[$cCode]['main'][$resetor] = null;
                                    unset($_SESSION[$cCode]['main'][$resetor]);
                                }
                            }
                        }
                    }
                }
            }
        }

        unset($_SESSION[$cCode]['main'][$elName]);
        unset($_SESSION[$cCode]['main_elements'][$elName]);

        if ($elName == "referensi_so_project" && $jenisTr == 4467) {
            unset($_SESSION[$cCode]['items4_new']);
            unset($_SESSION[$cCode]['items4_sum']);
            unset($_SESSION[$cCode]['items5_new']);
            unset($_SESSION[$cCode]['items5_sum']);
        }

        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function reset($show = 1)
    {
        $cCode = $this->cCode;
        $jenisTr = $this->jenisTr;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $mdlName = isset($lockerConfig['mdlName']) ? $lockerConfig['mdlName'] : "MdlLockerStock";
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;

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


                cekkuning('memulai reset');
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
                    cekkuning("resetting $sSName");
                    $_SESSION[$cCode][$sSName][$id] = null;
                    unset($_SESSION[$cCode][$sSName][$id]);
                }
            }

        }


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
        );
        foreach ($detailResetList as $sSName) {
            $_SESSION[$cCode][$sSName] = null;
            unset($_SESSION[$cCode][$sSName]);
        }

        /** ------------------------------------------------------------------------------
         * pengenal khusus untuk tidak membersihkan session main
         * digunakan pada saat klik dari daftar hutang ke-konsumen
         * ------------------------------------------------------------------------------*/
        if (!isset($_GET['cl'])) {
            //reset main juga
            if (isset($_SESSION[$cCode]['main'])) {
                unset($_SESSION[$cCode]);
            }
        }
        else {
            if (isset($_SESSION[$cCode]['items'])) {

                $link_shoppingcart = base_url() . "kas/_shoppingCart/buildValues/$jenisTr?gr=cGVtYmF5YXJhbg?ohyes=ohno";
                echo "<script>
                    $('#shopping_cart').load('$link_shoppingcart');
                 </script>";

            }
            die();
        }

        //reset mode juga
        if (isset($_SESSION[$cCode]['mode'])) {
            unset($_SESSION[$cCode]);
        }


        //init sesssion
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        foreach ($initMasterValues as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
            //            $_SESSION[$cCode]['main'][$key] = $val;
        }
        cekkuning("done resetting");

        if ($show) {
            echo "<script>";
            echo "top.fillBoxes();";
            echo "</script>";

            echo topReload(100);
        }


        cekkuning("done re-filling");
    }

    public function recordFieldInput()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $elementConfigs = isset($this->configUi[$jenisTr]['receiptElements']) ? $this->configUi[$jenisTr]['receiptElements'] : array();


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
        //        $_SESSION[$cCode]["main"][$elName] = $val;

        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
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


        $additionalRowsValidate = isset($this->configUi[$jenisTr]['additionalRowsValidate']) ? $this->configUi[$jenisTr]['additionalRowsValidate'] : array();
        $additionalRowsValidatePersen = isset($this->configUi[$jenisTr]['additionalRowsValidatePersen']) ? $this->configUi[$jenisTr]['additionalRowsValidatePersen'] : array();

        $_SESSION[$cCode]['main']['um_noppn_nonrelasi_cek'] = 1;
        $_SESSION[$cCode]['add_rows']['um_noppn_nonrelasi_cek'] = 1;

        $_SESSION[$cCode]['main']['replace_nilai_entry'] = 0;

        switch ($rowName) {
            case "add_source_uang_muka_dipakai":
                if ($rowName == "add_source_uang_muka_dipakai") {
                    $saldo_um_norelasi = isset($_SESSION[$cCode]['main']['uangMukaNonRelasi__sisa']) ? $_SESSION[$cCode]['main']['uangMukaNonRelasi__sisa'] : 0;
                    $harga = isset($_SESSION[$cCode]['main']['harga']) ? $_SESSION[$cCode]['main']['harga'] : 0;
                    // inputan um pakai vs saldo um no relasi
                    if ($val > $saldo_um_norelasi) {
                        $msg = "Titipan Non Relasi yang akan dipakai melebihi saldo Titipan Non Relasi. Silahkan diperiksa lagi.";
                        echo "<script>
                top.document.getElementById('add_source_uang_muka_dipakai').value=0;
                </script>";
                        mati_disini($msg);
                    }
                    // inputan um pakai vs nilai um yang akan dibuat
                    if ($val > $harga) {
                        $msg = "Titipan Non Relasi yang akan dipakai melebihi nilai Titipan dengan Relasi yang akan dibuat. Silahkan diperiksa lagi.";
                        echo "<script>
                top.document.getElementById('add_source_uang_muka_dipakai').value=0;
                </script>";
                        mati_disini($msg);
                    }
                    // inputan um pakai vs optionReference
                    if (($val > 0) && ($_SESSION[$cCode]['main']['optionReference'] != 2)) {
                        $msg = "Titipan Non Relasi hanya bisa dipakai bila anda membuat Titipan dengan Relasi PO. Silahkan diperiksa lagi.";
                        echo "<script>
                top.document.getElementById('add_source_uang_muka_dipakai').value=0;
                </script>";
                        mati_disini($msg);
                    }

                    $_SESSION[$cCode]['main']['um_noppn_nonrelasi_cek'] = -1;
                    $_SESSION[$cCode]['add_rows']['um_noppn_nonrelasi_cek'] = -1;
                }
                break;
            case "add_source_creditnote_dipakai":
                $saldo_cn_noppn_konsumen = isset($_SESSION[$cCode]['main']['creditAmount__saldoUangMuka']) ? $_SESSION[$cCode]['main']['creditAmount__saldoUangMuka'] : 0;
                $grand_total = isset($_SESSION[$cCode]['main']['grand_total']) ? $_SESSION[$cCode]['main']['grand_total'] : 0;
                // inputan um pakai vs nilai um yang akan dibuat
                if ($val > $grand_total) {
                    $msg = "Titipan Non Relasi yang akan dipakai melebihi nilai Titipan dengan Relasi yang akan dibuat. Silahkan diperiksa lagi. code: " . __LINE__;
                    echo "<script>
                        top.document.getElementById('add_source_creditnote_dipakai').value=0;
                        </script>";
                    mati_disini($msg);
                }
                break;
            case "uang_muka_dipakai":
                $saldo_um_noppn_konsumen = isset($_SESSION[$cCode]['main']['uangMuka__sisa']) ? $_SESSION[$cCode]['main']['uangMuka__sisa'] : 0;
                $sisa_total = isset($_SESSION[$cCode]['main']['nilai_sisa']) ? $_SESSION[$cCode]['main']['nilai_sisa'] : 0;
                $_SESSION[$cCode]['main']['replace_nilai_entry'] = 1;
                // inputan um pakai vs nilai um yang akan dibuat
                if ($val > $sisa_total) {
                    $selisih = $val - $sisa_total;
                    if ($selisih > -2) {
                        $sisa_total_f = number_format($sisa_total);
                        $val_f = number_format($val);
                        $msg = "Uang Muka konsumen yang akan dipakai melebihi tagihan. Total tagihan $sisa_total_f, Anda mengisi $val_f. Silahkan diperiksa lagi. code: " . __LINE__;
//                        $msg = "Uang Muka konsumen yang diisi $val_f melebihi total tagihan $sisa_total_f. Silahkan masukkan uang muka maksimal $sisa_total_f. code: " . __LINE__;
                        echo "<script>
                        top.document.getElementById('uang_muka_dipakai').value=0;
                        </script>";
                        mati_disini($msg);
                    }
                }

                if ($val > $saldo_um_noppn_konsumen) {
                    $selisih = $val - $saldo_um_noppn_konsumen;
                    if ($selisih > -2) {
                        $msg = "Uang Muka konsumen yang akan dipakai melebihi Saldo Uang Muka Konsumen. Silahkan diperiksa lagi. code: " . __LINE__;
                        echo "<script>
                        top.document.getElementById('uang_muka_dipakai').value=0;
                        </script>";
                        mati_disini($msg);
                    }
                }
                break;

            default:
                if (isset($_SESSION[$cCode]['main']['add_source_uang_muka_dipakai']) && ($_SESSION[$cCode]['main']['add_source_uang_muka_dipakai'] > 0)) {
                    $_SESSION[$cCode]['main']['um_noppn_nonrelasi_cek'] = -1;
                    $_SESSION[$cCode]['add_rows']['um_noppn_nonrelasi_cek'] = -1;
                }
                break;
        }


        if (!isset($_SESSION[$cCode]['add_rows'])) {
            $_SESSION[$cCode]['add_rows'] = array();
        }
        $_SESSION[$cCode]['add_rows'][$rowName] = $val;
        $_SESSION[$cCode]['add_rows']['total_disc'] = $total_diskon;
        if ($rowName == "pph23") {
//            $_SESSION[$cCode]['main']['nilai_entry'] = $_SESSION[$cCode]['main']["sisa"] - $_SESSION[$cCode]['main']["credit_note_dipakai"] - $val;
            $_SESSION[$cCode]['main']['replace_nilai_entry'] = 1;
        }
        if ($rowName == "credit_amount") {
            $_SESSION[$cCode]['main']['replace_nilai_entry'] = 1;
            $credit_amount = isset($_SESSION[$cCode]['main']['creditAmount__sisa']) ? $_SESSION[$cCode]['main']['creditAmount__sisa'] : 0;
            if ($val > $credit_amount) {
                $msg = "Credit Note yang akan dipakai melebihi saldo credit note. Silahkan diperiksa lagi.";
                echo "<script>
                top.document.getElementById('credit_amount').value=0;
                </script>";
                mati_disini($msg);
            }
        }
        if ($rowName == "nilai_biaya") {
            $_SESSION[$cCode]['main']['replace_nilai_entry'] = 1;
            if (sizeof($additionalRowsValidate) > 0) {
                if (isset($additionalRowsValidate[$rowName]) && ($additionalRowsValidate[$rowName] > 0)) {

                    if ($additionalRowsValidatePersen) {
                        $persen_validate = $additionalRowsValidatePersen[$rowName];
                        $nilai_validate = 0;
                        if (isset($_SESSION[$cCode]["main"]["terbayar"]) && $_SESSION[$cCode]["main"]["terbayar"] > 0) {
                            $nilai_validate = $_SESSION[$cCode]["main"]["terbayar"];
                        }
                        else {
                            $nilai_validate = $_SESSION[$cCode]["main"]["tagihan"];
                        }
                        $nilai_max = ($persen_validate / 100) * $nilai_validate;
                        $nilai_max_ff = number_format($additionalRowsValidate[$rowName], 0, ".", ",");
                        $val_ff = number_format($val, 0, ".", ",");
                        if ($val > $nilai_max) {
                            $msg = "Nilai input diskon (biaya support) sebesar $val_ff, melebihi ketentuan/nilai maksimal yang diperbolehkan ($nilai_max_ff). Silahkan diperiksa lagi.";
                            echo "<script>
                        top.document.getElementById('nilai_biaya').value=0;
                        </script>";
                            mati_disini($msg);
                        }
                    }
                    else {
                        $nilai_max = $additionalRowsValidate[$rowName];
                        $nilai_max_ff = number_format($additionalRowsValidate[$rowName], 0, ".", ",");
                        $val_ff = number_format($val, 0, ".", ",");
                        if ($val > $nilai_max) {
                            $msg = "Nilai input diskon (biaya support) sebesar $val_ff, melebihi ketentuan/nilai maksimal yang diperbolehkan ($nilai_max_ff). Silahkan diperiksa lagi.";
                            echo "<script>
                        top.document.getElementById('nilai_biaya').value=0;
                        </script>";
                            mati_disini($msg);
                        }
                    }
//                    $nilai_max = $additionalRowsValidate[$rowName];
//                    $nilai_max_ff = number_format($additionalRowsValidate[$rowName], 0, ".", ",");
//                    $val_ff = number_format($val, 0, ".", ",");
//                    if ($val > $nilai_max) {
//                        $msg = "Nilai input diskon (biaya support) sebesar $val_ff, melebihi ketentuan/nilai maksimal yang diperbolehkan ($nilai_max_ff). Silahkan diperiksa lagi.";
//                        echo "<script>
//                        top.document.getElementById('nilai_biaya').value=0;
//                        </script>";
//                        mati_disini($msg);
//                    }
                }
            }
        }
        if ($rowName == "kas_nilai") {
            if (sizeof($additionalRowsValidate) > 0) {
                if (isset($additionalRowsValidate[$rowName])) {
                    $nilai_max = $_SESSION[$cCode]['main'][$additionalRowsValidate[$rowName]];
                    $nilai_max_ff = number_format($nilai_max, 0, ".", ",");
                    $val_ff = number_format($val, 0, ".", ",");
                    if ($val > $nilai_max) {
                        $msg = "Nilai Kas/Uang yang diterima melebihi nilai Pajak sebesar $nilai_max_ff. Silahkan diperiksa lagi.";
                        echo "<script>
                        top.document.getElementById('kas_nilai').value=0;
                        </script>";
                        mati_disini($msg);
                    }
                }
            }
        }

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$rowName] = $val;


        $extTools = $this->configUi[$jenisTr]['extTool'];
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
        // matiHere("cek " . $rowName);
//arrPrint($_SESSION[$cCode]["main"]["lebih_bayar"]);
//        matiHEre("UNDER MAINTENANCE");
        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


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
        showLast_query("lime");
        arrPrintWebs($tmps);
        $params = blobDecode($tmps[0]->datas);
        arrPrint($params);
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
        arrPrintWebs($tmpSaver);
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
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
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

        $key = $_GET['key'];


        heFetchItemsElement_modul($jenisTr, $elName, $mdlName, $key, $helpName, $configUiJenis);
        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

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

        heRecordElement_modul($jenisTr, $elName, $val, $configUiJenis);
        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

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

        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        //        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    //-----------------------------------
    public function recordItemColumn()
    {

        $cCode = $this->cCode;
        $id = $iID = $_GET['iid'];
        $colName = $this->uri->segment(5);
        $val = rawurldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? rawurldecode($_GET['valValue']) : null;
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;


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
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";


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


        $id = $iID = $_GET['iid'];
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
//                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $this->db->where_in("jenis_value", $priceConfig['label']);
                        $tmpH = $h->lookupAll($val)->result();
                        cekHere(":: $mdlName ::");
                        showLast_query("biru");
                        cekHere(sizeof($tmpH));
                        arrPrintPink($tmpH);
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
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
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

        $id = $iID = $_GET['iid'];
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;


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
                                    cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                }
                                else {
                                    $newSrc = str_replace($val2, "0", $newSrc);
                                    cekKuning("$val2 direplace dengan NOL");
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
                    cekUngu("BAWAH, ganti jumlah bawah items target");
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
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";
    }

    public function recordImage()
    {
        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $id = $iID = $this->uri->segment(6);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;

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
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function recordPairedItemOther()
    {

        $cCode = $this->cCode;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


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


        $id = $iID = $_GET['iid'];
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
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
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

    public function recordColumn()
    {

        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;


        $_SESSION[$cCode]['main'][$colName] = $val;
        $_SESSION[$cCode]['main'][$colName] = $val;
        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
        }
        $id = 0;
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        // $initMasterValues['key'] = 'values';
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";


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

    public function clearContent()
    {
        $trID = $this->uri->segment(5);
        $cCode = $this->cCode;
        //==================================================================================================
        //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
        if ($trID > 0) {
            $this->load->model("Mdls/MdlLockerTransaksi");
            $lt = New MdlLockerTransaksi();
            $lt->execLocker($_SESSION[$cCode]['main'], 0, $trID, NULL);
        }
        //==================================================================================================

        $_SESSION[$cCode] = null;
        unset($_SESSION[$cCode]);
//        echo "<script>";
//        echo "if(top.document.getElementById('shopping_cart')){";
//        echo "$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0')";
//        echo "}";
//        echo "</script>";
        topRedirect(MODUL_PATH . "Transaksi/index/" . $this->jenisTr);
    }


    //-----------------------------------
    public function buildValues()
    {

        $cCode = $this->cCode;
        $jenisTr = $this->jenisTr;

        $configUiJenis = $this->configUi[$jenisTr];
        $configCoreJenis = $this->configCore[$jenisTr];
        $elementConfigs = isset($this->configUiJenis['receiptElements']) ? $this->configUiJenis['receiptElements'] : array();
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $elName => $elSpec) {
                if (isset($elSpec["reset"]) && ($elSpec["reset"] == true)) {
                    if (isset($_SESSION[$cCode]["main_elements"][$elName])) {
                        $_SESSION[$cCode]["main_elements"][$elName] = NULL;
                        unset($_SESSION[$cCode]["main_elements"][$elName]);
                    }
                }
            }
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
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
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $id = 0;
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
        echo "</script>";
    }

    public function buildProjectDatas($project_id, $cCode)
    {

        // Baca ke table project
        $this->load->model("Mdls/MdlProdukProject");
        $prdprj = new MdlProdukProject();

        $columnFilter = "id";
        // Gunakan variabel kolom yang sudah dinamis
        $prdprj->addFilter("$columnFilter=$project_id");
        $prevProdukProject = $prdprj->lookUpAll()->result();

        showLast_query("biru");

        //project sudah closing
        if ($prevProdukProject[0]->closing_status > 0 || $prevProdukProject[0]->trash > 0) {
            $closing_oleh_nama = $prevProdukProject[0]->closing_oleh_nama;
            $closing_dtime = $prevProdukProject[0]->closing_dtime;
            matiHere("PROJECT SUDAH DI CLOSING OLEH: $closing_oleh_nama || $closing_dtime");
        }

        $arrProdukProject = array();
        if (!empty($prevProdukProject)) {
            foreach ($prevProdukProject as $ky => $prjVal) {
                $arrProdukProject[] = (array)$prjVal;
            }
        }

        $unDoneTask = array();
        if (!empty($prevProdukProject)) {
            $id_project = $prevProdukProject[0]->id;
            //bahan baku dari BOM
            $arrBahanBaku = array();
            $this->db->select("*");
            $this->db->where("status='1' and trash='0' and produk_id='$id_project' and jenis_transaksi='5582'");
            $bahanBakuBOM = $this->db->get("project_komposisi_sub_workoder")->result();
            $kelebihan = array();
            if (!empty($bahanBakuBOM)) {
                foreach ($bahanBakuBOM as $k => $tskSub) {
                    $arrBahanBaku[$id_project][$tskSub->jenis][$tskSub->produk_dasar_id] = (array)$tskSub;
                }
            }
            //bahan baku
            if (!empty($prevProdukProject)) {
                foreach ($prevProdukProject as $ky => $tsk) {
                    $tsk->bahan_baku = isset($arrBahanBaku[$id_project]) ? $arrBahanBaku[$id_project] : array();
                    $unDoneTask[$tsk->id] = (array)$tsk;
                }
            }
        }

//        arrPrint($prevProdukProject);
//        matiHere(__LINE__);
        //reset session undonetask
        if (!isset($_SESSION[$cCode]['items4_new'])) {
            $_SESSION[$cCode]['items4_new'] = array();
        }
        $_SESSION[$cCode]['items4_new'] = $arrProdukProject;

        if (!isset($_SESSION[$cCode]['items5_new'])) {
            $_SESSION[$cCode]['items5_new'] = array();
        }
        $_SESSION[$cCode]['items5_new'] = $unDoneTask;

    }
}

