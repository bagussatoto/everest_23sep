<?php


require_once "Modul_Controller.php";

class _shoppingCart extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("MobileDetect");

    }

    public function viewCart_OLD()
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
        $selectedPrices = isset($this->configUi[$this->jenisTr]['selectedPrice']['key_label']) ? $this->configUi[$this->jenisTr]['selectedPrice']['key_label'] : array();
        $showItems = isset($this->configUi[$this->jenisTr]['showItems']) && $this->configUi[$this->jenisTr]['showItems'] == "false" ? "false" : "true";

        $fixedNote = isset($this->configLayout[$this->jenisTr]['fixedNote']) ? $this->configLayout[$this->jenisTr]['fixedNote'] : null;
        $fixedNoteTop = isset($this->configLayout[$this->jenisTr]['fixedNoteTop']) ? $this->configLayout[$this->jenisTr]['fixedNoteTop'] : null;
        $shopingCartParamForceEditable = isset($this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber] : array();
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        //----------------------------------------------
        $isSettlement = isset($this->configUi[$this->jenisTr]['isSettlement']) ? $this->configUi[$this->jenisTr]['isSettlement'] : false;
        //----------------------------------------------
        // mati_disini(__LINE__);
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

        // mati_disini(__LINE__);
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


                    //                    $tmp[$shoppingCartAmountValue] = isset($iSpec[$shoppingCartAmountValue]) ? $iSpec[$shoppingCartAmountValue] : 0;
                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;
                    $tmp['sub_harga'] = isset($iSpec['sub_harga']) ? $iSpec['sub_harga'] : 0;
                    // cekLime(isset($iSpec['handler']) ? $iSpec['handler'] : '0');
                    $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;

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
        // arrPrintWebs($items);

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


        //        arrprint($itemLabels2);
        if (isset($this->configUi[$this->jenisTr]['shoppingCartSubamount2']) && $this->configUi[$this->jenisTr]['shoppingCartSubamount2'][$stepNumber] == true) {
            $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels = $itemLabels + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = $itemLabels2 + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
        }
        else {
            $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "Total Price");
            $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Total Price");
            $itemLabels3 = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "Total Price");
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

        // matiHere(__LINE__ . __METHOD__);
        //==memproses awal elemen2 yang terlibat, jika ada yang bisa dipre-process
        //==misalnya yang pilihannya cuma satu atau yang ada config nilai defaultnya
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                //                cekHere(":: HAHAHA ::");
                $elementConfigs[$eName]['autoSelect'] = false;
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    //                    cekHere(":: HIHIHI ::");
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
                        $elementConfigs[$eName]['autoSelect'] = true;
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
                                        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                    }

                                    $tmpo = $oo->lookupAll()->result();
                                    //                                                                        cekOrange($this->db->last_query());
                                    if (sizeof($tmpo) == 1) {
                                        $usedKey = $eSpec['key'];

                                        $defValueSrc = $tmpo[0]->$usedKey;
                                        $configUiJenis = $this->configUi[$this->jenisTr];
                                        heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                        //                                        $elementConfigs[$eName]['autoSelect']=true;
                                    }


                                    break;
                                case "dataField":
                                    break;
                            }


                        }
                    }
                }
                else {
                    //                    cekHere(":: HOHOHO ::");
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
                                //                                cekmerah("pre..");
                                $tmpo = $oo->lookupAll()->result();
                                //                                echo($this->db->last_query());
                                //                                                                cekmerah($this->db->last_query());
                                //                                arrPrint($tmpo);
                                if (sizeof($tmpo) == 1) {
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    //                                    cekBiru(":: $defValueSrc :: $usedKey ::");
                                    $configUiJenis = $this->configUi[$this->jenisTr];
                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                    //                                    $elementConfigs[$eName]['autoSelect']=true;
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
                    //					cekhijau("$eName terdaftar pada addRows");
                    switch ($elementConfigs[$eName]['elementType']) {
                        case "dataModel":
                            $currentValue = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : "";
                            break;
                        case "dataField":
                            $currentValue = $_SESSION[$cCode]['main_elements'][$eName]['value'];
                            break;
                    }
                    //                    cekhijau("currentValue: $currentValue");
                    if (isset($addRowsConfigs[$eName][$currentValue])) {
                        //                        cekmerah("aturan untuk $currentValue ada");
                        if (sizeof($addRowsConfigs[$eName][$currentValue]) > 0) {

                            foreach ($addRowsConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                //                                cekhijau($oValueName);
                                //                                arrprint($oValSpec);
                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {

                                    $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+this.value";


                                    //==init value and params
                                    //region default value
                                    if (isset($oValSpec['defaultValue'])) {
                                        $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    }
                                    //endregion

                                    //                                    cekmerah("$oValueName = ".$origDefValue);

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

                                    $defVal = isset($_SESSION[$cCode]['main'][$oValueName]) && $_SESSION[$cCode]['main'][$oValueName] > 0 ? ($_SESSION[$cCode]['main'][$oValueName] + 0) : $origDefValue;
                                    if (isset($addRowsConfigs[$eName][$currentValue][$oValueName]['role']) && $addRowsConfigs[$eName][$currentValue][$oValueName]['role'] == "minus") {
                                        $defVal = "(" . $defVal . ")";
                                    }
                                    //                                    $defVal = $origDefValue;
                                    $addRows[$oValueName] = "<input type=text autocomplete='off' id='$oValueName' class='form-control text-right' style='font-size:17px;' $disabled placeholder='$oValueName' value='$defVal' $minValStr $maxValStr 
onfocus='this.select()' onkeyup=\"$keyupStr\" onfocus=\"$keyupStr\"
onblur=\"$blurStr\"
onmouseout=\"$blurStr\"
>";
                                    $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                    $addRowLabels[$oValueName] = $oValSpec['label'];

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
                        //                        cekmerah($this->db->last_query());
                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";

                        $elStr[$eName] .= "<div class='box-body'>";

                        switch ($eSpec['inputType']) {
                            case "combo":
                                $elStr[$eName] .= "<select class='form-control' onchange=\"hiliteDiv(this);document.getElementById('result').src=$selectorTarget;\">";
                                $elStr[$eName] .= "<option value=''>-select-</option>";
                                if (sizeof($tmpo) > 0) {
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

                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                            //                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $$labelValue . "</option>";

                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                        }


                                    }
                                }
                                $elStr[$eName] .= "</select>";
                                break;
                            case "radio":

                                if (sizeof($tmpo) > 0) {
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
                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . (isset($row->$labelSrc) ? $row->$labelSrc : '-') . "</label>\n";
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
                                //								$defValue .= "<table class='table table-condensed no-padding' style='padding:0px;margin:0px;'>";
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

                        $elements[$eName] = array("type" => $eSpec['inputType'],
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
                                cekmerah($this->db->last_query());


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

        // mati_disini(__LINE__);
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

            //            $detilSizeBar .= "<div class='row bg-danger' style='background: #ffa988;padding: 7px;'>";
            //            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
            //                    <div class='input-group'>
            //                    <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
            //                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
            //                    </div>
            //                 </div>";
            //            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
            //                    <div class='input-group'>
            //                    <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
            //                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
            //                    </div>
            //                 </div>";
            //            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
            //                    <div class='input-group'>
            //                    <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
            //                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='". number_format(conv_mmc_mc($main['volume_gross']),2) ."' disabled=''>
            //                    </div>
            //                 </div>";
            //            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
            //                    <div class='input-group'>
            //                    <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
            //                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='". conv_g_kg($main['berat_gross']) ."' disabled=''>
            //                    </div>
            //                 </div>";
            //            $detilSizeBar .= "</div>";
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
        }
        //arrPrint($elementMutasi);
        //-------------------------------------------------------------------------------

        $elementResetTarget = MODUL_PATH . get_class($this) . "/resetElement/" . $this->jenisTr . "/";
        $itemsElementResetTarget = MODUL_PATH . get_class($this) . "/resetItemsElement/" . $this->jenisTr . "/";
        $data = array(
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
            "items2_komposisi" => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),

            "itemElements" => isset($itemsElements) ? $itemsElements : array(),
            "itemElementConfigs" => isset($elementItemsAutoConfigs) ? $elementItemsAutoConfigs : array(),
            "itemElementResetTarget" => isset($itemsElementResetTarget) ? $itemsElementResetTarget : "",
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

    public function viewCart()
    {
//        $this->jenisTr = $this->uri->segment(3);
        $cCode = $this->cCode;
        $this->load->helper('he_angka');

        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "main" => array(),
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
        $avoidRemoveAll_items = isset($this->configUi[$this->jenisTr]['shoppingCartAvoidRemoveAll_items']) ? $this->configUi[$this->jenisTr]['shoppingCartAvoidRemoveAll_items'] : false;
        $tmpSaver = isset($this->configUi[$this->jenisTr]['tmpSave']) ? $this->configUi[$this->jenisTr]['tmpSave'] : array();
        $reqDiskon = isset($this->configUi[$this->jenisTr]['reqDiskon']) ? $this->configUi[$this->jenisTr]['reqDiskon'] : array();

        $unionSelectors = isset($this->configUi[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber] : array();
        $keyUpEvents = isset($this->configUi[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber] : array();
        $selectedPrices = isset($this->configUi[$this->jenisTr]['selectedPrice']['key_label']) ? $this->configUi[$this->jenisTr]['selectedPrice']['key_label'] : array();
        $showItems = isset($this->configUi[$this->jenisTr]['showItems']) && $this->configUi[$this->jenisTr]['showItems'] == "false" ? "false" : "true";

        $fixedNote = isset($this->configLayout[$this->jenisTr]['fixedNote']) ? $this->configLayout[$this->jenisTr]['fixedNote'] : null;
        $fixedNoteTop = isset($this->configLayout[$this->jenisTr]['fixedNoteTop']) ? $this->configLayout[$this->jenisTr]['fixedNoteTop'] : null;
        $shopingCartParamForceEditable = isset($this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber] : array();
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $shopingCartReload = isset($this->configUi[$this->jenisTr]['shopingCartReload']) ? $this->configUi[$this->jenisTr]['shopingCartReload'] : false;
        $shopingCartTaxAdd = isset($this->configUi[$this->jenisTr]['shopingCartAddTax']) ? $this->configUi[$this->jenisTr]['shopingCartAddTax'] : array();

        //----------------------------------------------
        $isSettlement = isset($this->configUi[$this->jenisTr]['isSettlement']) ? $this->configUi[$this->jenisTr]['isSettlement'] : false;
        //----------------------------------------------
        // mati_disini(__LINE__);
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

        // mati_disini(__LINE__);
        if (isset($_SESSION[$cCode])) {
            $total_quantity = 0;
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

                        "ceklist_opname" => isset($iSpec['ceklist_opname']) ? $iSpec['ceklist_opname'] : "",
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


                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;
                    $tmp['sub_harga'] = isset($iSpec['sub_harga']) ? $iSpec['sub_harga'] : 0;
                    // cekLime(isset($iSpec['handler']) ? $iSpec['handler'] : '0');
                    // $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    // $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;
                    //direwrite karena ada transaksi lama yg non modul, seingga selector nyasar
                    $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "requeststok/", $iSpec['handler']) : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "requeststok/", $iSpec['handler']) : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;

                    $items[] = $tmp;

                    $total_quantity += isset($iSpec['qty_opname']) ? $iSpec['qty_opname'] : 0;
                }
            }
        }
        // arrPrintWebs($items);

        //item2 sum
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items2_sum'])) {
                $items2_sum_kurang = array();
                $no = 0;
                $valItem = 0;
                foreach ($_SESSION[$cCode]['items2_sum'] as $iSpec) {
                    $sisa = isset($iSpec['stok']) && isset($iSpec['jml']) ? $iSpec['stok'] - $iSpec['jml'] : 0;
                    if (isset($iSpec['produk_kode'])) {
                        $produk_kode = $iSpec['produk_kode'];
                    }
                    else {
                        if (isset($iSpec['kode'])) {
                            $produk_kode = $iSpec['kode'];
                        }
                        else {
                            $produk_kode = "";
                        }
                    }

                    $no++;
                    $tmp = array("id" => $iSpec['id'],
                        "nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "",
                        "jml" => isset($iSpec['jml']) ? $iSpec['jml'] : 0,
                        "jual" => isset($iSpec['jual']) ? $iSpec['jual'] : 0,
                        "disc_value" => isset($iSpec['disc_value']) ? $iSpec['disc_value'] : 0,
                        "disc_persent" => isset($iSpec['disc_persent']) ? $iSpec['disc_persent'] : 0,
                        "subtotal" => isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0,
//                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : isset($iSpec['kode']) ? $iSpec['kode'] : "",
                        "produk_kode" => $produk_kode,
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "",

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
                        "produk_nama" => isset($iSpec['nama']) ? $iSpec['nama'] : $iSpec['nama'],
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

                            $main[$key] += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;
//                            $valItem += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;
//                            $main[$key] = $valItem;

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
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "",
                        "jml" => isset($iSpec['jml']) ? $iSpec['jml'] : 0,
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "",

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


        //        arrprint($itemLabels2);
        if (isset($this->configUi[$this->jenisTr]['shoppingCartSubamount2']) && $this->configUi[$this->jenisTr]['shoppingCartSubamount2'][$stepNumber] == true) {
            $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels = $itemLabels + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Total<br><r>(incl)</r><br><r>(VAT & DISC)</r>");
            $itemLabels2 = $itemLabels2 + array("sub_harga" => "Total<br><r>(excl)</r><br><r>(VAT & DISC)</r>");
        }
        else {
            $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "Total Price");
            $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "Total Price");
            $itemLabels3 = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "Total Price");
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
                        $sumSpec2[$key] = "<select id='$key' name='$key' class='form-control' onchange=\"top.$('#result').load('" . $sumRowAction2 . "?key=$key&value='+removeCommas(this.value));\">";
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
                    onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                    onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                    >";
                    $sumType2[$key] = "number";
                    //endregion
                    if ($vSpec['taxFactor'] > 0) {
                        $key = $vName . "_tax";
                        $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                        $sumRows2[$key] = "tax for " . $vSpec['label'];
                        $sumSpec2[$key] = "<input type='text' class='form-control text-right' name=$key id=$key value='$defaultValue' 
                        onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                        onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
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
                            $sumSpec2[$key] = "<select id='$key' name='$key' class='form-control' onchange=\"top.$('#result').load('" . $sumRowAction2 . "?key=$key&value='+removeCommas(this.value));\">";
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
                        onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                        onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                        >";
                        $sumType2[$key] = "number";
                        //endregion
                        if ($vSpec['taxFactor'] > 0) {
                            $key = $vName . "_tax";
                            $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                            $sumRows2[$key] = "tax for " . $vSpec['label'];
                            $sumSpec2[$key] = "<input type='text' readonly class='form-control text-right' name=$key id=$key value='$defaultValue' 
                            onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                            onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
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
                                    $inputs[$oValueName] = "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text id='$oValueName' class='form-control text-center' placeholder='$oValueName' value='" . number_format($defVal) . "' min='$minValue' max='$maxValue' onfocus='this.select()'
                                        onkeyup=\"if(parseInt(removeCommas(this.value))>$maxValue || parseInt(removeCommas(this.value))<$minValue){this.value='" . number_format($origDefValue) . "'};if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"
                                        onblur=\"if(removeCommas(this.value)!=this.defaultValue){hiliteDiv(this);top.$('#result').load($relInputTarget);}\"
                                        onmouseouts=\"if(removeCommas(this.value)!=this.defaultValue){hiliteDiv(this);top.$('#result').load($relInputTarget);}\">";

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

        // matiHere(__LINE__ . __METHOD__);
        //==memproses awal elemen2 yang terlibat, jika ada yang bisa dipre-process
        //==misalnya yang pilihannya cuma satu atau yang ada config nilai defaultnya
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                //                cekHere(":: HAHAHA ::");
                $elementConfigs[$eName]['autoSelect'] = false;
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    //                    cekHere(":: HIHIHI ::");
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
                        $elementConfigs[$eName]['autoSelect'] = true;
                    }
                    else {//==cek apakah pilihannya cuma satu
                        if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                        }
                        else {
                            //                            cekHere(__LINE__);
//                            matiHEre("isset ".__LINE__);
//                            $autoFilter = isset($eSpec['autoFilter']) ? $eSpec['autoFilter'] :array();
                            $autoFilter = isset($eSpec['autoFilter']) ? $eSpec['autoFilter'] : array();
                            $addFilterSrc = array();
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

                                    if (sizeof($autoFilter) > 0) {
                                        $keyFilter = $autoFilter['key'];
                                        cekHitam($keyFilter);
                                        $keySrcVAl = $_SESSION[$cCode]['main'][$keyFilter];
                                        $mdlSrc = $autoFilter['srcRef']['mdl'];
                                        $srcField = $autoFilter['srcRef']['srcField'];
                                        $srcFieldKey = $autoFilter['srcRef']['filter'];
                                        $this->load->model("Mdls/" . $mdlSrc);
                                        $src = new $mdlSrc();

                                        $srcData = $src->lookupByID($_SESSION[$cCode]['main'][$srcFieldKey])->result();
                                        $valSrc = $srcData[0]->$srcField;
                                        $pairSrc = $autoFilter['pairKey'];
//                                        $validateKey = $pairSrc['validate'];
                                        if (strlen($valSrc) > 2) {
                                            $validate = "true";
                                        }
                                        else {
                                            $validate = "false";
                                        }
                                        $pairTargetKey = $pairSrc['methode'][$keySrcVAl][$validate];
//                                        arrprint($pairTargetKey);
//                                        mati_disini();
                                        $oo->setAddKey($pairTargetKey);

                                    }
                                    $tmpo = $oo->lookupAll()->result();
                                    //                                                                        cekOrange($this->db->last_query());
                                    if (sizeof($tmpo) == 1) {
                                        $usedKey = $eSpec['key'];

                                        $defValueSrc = $tmpo[0]->$usedKey;
                                        $configUiJenis = $this->configUi[$this->jenisTr];
                                        heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                        //                                        $elementConfigs[$eName]['autoSelect']=true;
                                    }


                                    break;
                                case "dataField":
                                    break;
                            }


                        }
                    }
                }
                else {
                    //                    cekHere(":: HOHOHO ::");
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
                                $autoFilter = isset($eSpec['autoFilter']) ? $eSpec['autoFilter'] : array();

                                if (sizeof($aFilter) > 0) {

                                    $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                }
                                //                                cekmerah("pre..");
                                $tmpo = $oo->lookupAll()->result();
                                //                                echo($this->db->last_query());
//                                                                cekmerah($this->db->last_query());
//                                arrPrint($tmpo);
                                if (sizeof($tmpo) == 1) {
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    //                                    cekBiru(":: $defValueSrc :: $usedKey ::");
                                    $configUiJenis = $this->configUi[$this->jenisTr];
                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                    //                                    $elementConfigs[$eName]['autoSelect']=true;
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
                    //					cekhijau("$eName terdaftar pada addRows");
                    switch ($elementConfigs[$eName]['elementType']) {
                        case "dataModel":
                            $currentValue = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : "";
                            break;
                        case "dataField":
                            $currentValue = $_SESSION[$cCode]['main_elements'][$eName]['value'];
                            break;
                    }
                    //                    cekhijau("currentValue: $currentValue");
                    if (isset($addRowsConfigs[$eName][$currentValue])) {
                        //                        cekmerah("aturan untuk $currentValue ada");
                        if (sizeof($addRowsConfigs[$eName][$currentValue]) > 0) {

                            foreach ($addRowsConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                //                                cekhijau($oValueName);
                                //                                arrprint($oValSpec);
                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {

                                    $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+this.value";


                                    //==init value and params
                                    //region default value
                                    if (isset($oValSpec['defaultValue'])) {
                                        $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    }
                                    //endregion

                                    //                                    cekmerah("$oValueName = ".$origDefValue);

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
                                        $keyupStr .= "if(parseInt(removeCommas(this.value))>$maxValue){this.value='" . number_format($origDefValue) . "';this.select();}else{if(parseInt(removeCommas(this.value))>0){this.value=addCommas(this.value)}else{this.value=0}}";
                                    }
                                    $keyupStr .= $keyupAct;


                                    $disabled = "";
                                    if (isset($oValSpec['disabled'])) {
                                        $disabled = $oValSpec['disabled'];
                                    }


                                    $blurStr = "";
                                    if ($minValue != "") {
                                        $blurStr = "if(removeCommas(this.value)!=this.defaultValue){if(parseInt(removeCommas(this.value))>=$minValue){hiliteDiv(this);top.$('#result').load($relInputTarget);}else{this.value='$minValue';this.focus();}}";
                                    }
                                    else {
                                        $blurStr = "if(removeCommas(this.value)!=this.defaultValue){hiliteDiv(this);top.$('#result').load($relInputTarget);}";

                                    }
                                    //endregion

                                    $defVal = isset($_SESSION[$cCode]['main'][$oValueName]) && $_SESSION[$cCode]['main'][$oValueName] > 0 ? ($_SESSION[$cCode]['main'][$oValueName] + 0) : $origDefValue;
                                    if (isset($addRowsConfigs[$eName][$currentValue][$oValueName]['role']) && $addRowsConfigs[$eName][$currentValue][$oValueName]['role'] == "minus") {
                                        $defVal = "(" . $defVal . ")";
                                    }
                                    //                                    $defVal = $origDefValue;
                                    $addRows[$oValueName] = "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text autocomplete='off' id='$oValueName' class='form-control text-right' style='font-size:17px;' $disabled placeholder='$oValueName' value='" . $defVal . "' $minValStr $maxValStr
onfocus='this.select()' onkeyup=\"$keyupStr if(parseFloat(removeCommas(this.value))>0){this.value=addCommas(this.value)}else{this.value=0}\" onfocus=\"$keyupStr\"
onblur=\"$blurStr\"
onmouseout=\"$blurStr\"
>";
                                    $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                    $addRowLabels[$oValueName] = $oValSpec['label'];

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
                        $autoFilter = isset($eSpec['autoFilter']) ? $eSpec['autoFilter'] : array();
                        if (sizeof($autoFilter) > 0) {
                            $keyFilter = $autoFilter['key'];
                            cekHitam($keyFilter);
                            $keySrcVAl = $_SESSION[$cCode]['main'][$keyFilter];
                            $mdlSrc = $autoFilter['srcRef']['mdl'];
                            $srcField = $autoFilter['srcRef']['srcField'];
                            $srcFieldKey = $autoFilter['srcRef']['filter'];
                            $this->load->model("Mdls/" . $mdlSrc);
                            $src = new $mdlSrc();

                            $srcData = $src->lookupByID($_SESSION[$cCode]['main'][$srcFieldKey])->result();
                            $valSrc = $srcData[0]->$srcField;
                            $pairSrc = $autoFilter['pairKey'];
//                                        $validateKey = $pairSrc['validate'];
                            if (strlen($valSrc) > 10) {
                                $validate = "true";
                            }
                            else {
                                $validate = "false";
                            }
                            $pairTargetKey = $pairSrc['methode'][$keySrcVAl][$validate];
//                            cekPink("g");
                            $oo->setAddKey($pairTargetKey);

                        }
                        $addClick = "";
                        $dataAccess = isset($this->config->item('heDataBehaviour')[$amdlName]) ? $this->config->item('heDataBehaviour')[$amdlName] : array(
                            "viewers" => array(),
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

                        //                        cekmerah("pre..");
                        $tmpo = $oo->lookupAll()->result();
                        //                        cekmerah($this->db->last_query());
                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";

                        $elStr[$eName] .= "<div class='box-body'>";

                        switch ($eSpec['inputType']) {
                            case "combo":
                                $elStr[$eName] .= "<select " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " class='form-control' onchange=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">";
                                $elStr[$eName] .= "<option value=''>-select-</option>";
                                if (sizeof($tmpo) > 0) {
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

                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                            //                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $$labelValue . "</option>";

                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                        }


                                    }
                                }
                                $elStr[$eName] .= "</select>";
                                break;
                            case "radio":

                                if (sizeof($tmpo) > 0) {
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
                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $labelValue . "</label>\n";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $row->$labelSrc . "</label>\n";
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
                                //								$defValue .= "<table class='table table-condensed no-padding' style='padding:0px;margin:0px;'>";
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
//                                            cekPink2("$nullSrc == $label");
//                                            $newValue_r = $fieldLabel == "" && $showNull == true && $nullValue != "" ? ($nullSrc == $label ? $nullValue : formatField($src, $fieldLabel)) : formatField($src, $fieldLabel);
                                            $newValue_r = ($fieldLabel == "" && $showNull == true && $nullValue != "") ? ($nullSrc == $label ? $nullValue : $fieldLabel) : $fieldLabel;
                                            if ($src == 'saldo') {
                                                $arrNewValue_r = explode('+', $newValue_r);
//                                                arrPrintWebs($arrNewValue_r);
                                                $newSaldo = 0;
                                                if (sizeof($arrNewValue_r) > 0) {
                                                    foreach ($arrNewValue_r as $k => $kVal) {
                                                        $newSaldo += $kVal;
                                                    }
                                                }
//                                                cekHere($newSaldo);
//                                                $newValue_r = $newSaldo > 0 ? "<span class='text-bold text-green'>" . number_format($newSaldo) . "</span>" : "<span class='text-bold text-red'>SALDO KOSONG</span>";
                                                $newValue_r = $newSaldo > 0 ? "<span class='text-bold text-green'>" . $newSaldo . "</span>" : "<span class='text-bold text-red'>SALDO KOSONG $newSaldo</span>";
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
                    top.BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: top.$('<div></div>').load('" . $editLink . "'),
                                        draggable:true,
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

                                    $editStr = "<a " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
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

                        $elements[$eName] = array(
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
//            }
//        }
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
                                cekmerah($this->db->last_query());


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

        // mati_disini(__LINE__);
        $main = $main + $tmpMasterValues;

        //bersihkan elemen yang tidak relevan
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $elName => $elSpec) {

                if (!array_key_exists($elName, $elementConfigs)) {
                    $_SESSION[$cCode]['main_elements'][$elName] = null;
                    unset($_SESSION[$cCode]['main_elements'][$elName]);

                    $mainResetList = array($elName, $elName . "__label");
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

            $period = new DatePeriod(
                new DateTime($awal_pinjaman),
                new DateInterval('P1D'),
                new DateTime($jatuh_tempo)
            );

            $periodNow = new DatePeriod(
                new DateTime($awal_pinjaman),
                new DateInterval('P1D'),
                new DateTime(date('Y-m-d'))
            );

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
                $tmp = array(
                    "thnbln" => $thnbln,
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

            $headerScheme = array(
                "nama" => "$nmPemengangSaham",
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

//            $detilSizeBar .= "<div class='row bg-danger' style='background: #ffa988;padding: 7px;'>";
//            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
//                    <div class='input-group'>
//                    <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
//                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
//                    </div>
//                 </div>";
//            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
//                    <div class='input-group'>
//                    <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
//                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
//                    </div>
//                 </div>";
//            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
//                    <div class='input-group'>
//                    <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
//                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='". number_format(conv_mmc_mc($main['volume_gross']),2) ."' disabled=''>
//                    </div>
//                 </div>";
//            $detilSizeBar .= "<div class='col-md-3 col-lg-3'>
//                    <div class='input-group'>
//                    <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
//                    <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='". conv_g_kg($main['berat_gross']) ."' disabled=''>
//                    </div>
//                 </div>";
//            $detilSizeBar .= "</div>";
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
                $subjectID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : "";
                $elementMutasiTmp = $elementMutasiConfig["branch"];
                foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                    $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID?o=" . $this->session->login['cabang_id'];
                }
            }
        }
        //arrPrint($elementMutasi);
        //-------------------------------------------------------------------------------

        $elementResetTarget = MODUL_PATH . get_class($this) . "/resetElement/" . $this->jenisTr . "/";
        $itemsElementResetTarget = MODUL_PATH . get_class($this) . "/resetItemsElement/" . $this->jenisTr . "/";

        //--tambahan pernyataan--------
        $checkOpname = isset($this->configUi[$this->jenisTr]['checkOpname']) ? $this->configUi[$this->jenisTr]['checkOpname'] : false;
        $checkNote = isset($this->configUi[$this->jenisTr]['checkNote']) ? $this->configUi[$this->jenisTr]['checkNote'] : array();
        $checkNoteEnabled = false;
        $pernyataanNote = "";
        if (isset($checkNote['enabled']) && ($checkNote['enabled'] == true)) {
            $checkNoteEnabled = $checkNote['enabled'];
            $nama_login = strtoupper($this->session->login['nama']);

            $total_baris = count($_SESSION[$cCode]['items']);
            $total_quantities = isset($total_quantity) ? $total_quantity : 0;

            $pernyataanNote_1 = str_replace("{total_baris}", $total_baris, $checkNote['label_1']);
            $pernyataanNote_2 = str_replace("{total_qty}", $total_quantities, $checkNote['label_2']);
//            cekHijau($pernyataanNote);
        }


        $data = array(
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
            "itemLabels" => $itemLabels,
            "itemLabels2" => $itemLabels2,
            "itemLabels3" => $itemLabels3,
            "numLabels" => $itemNumLabels,
            "detilSizeBar" => $detilSizeBar,
            //            "itemLabels"=>$itemLabels,
            "sumRows" => isset($this->configUi[$this->jenisTr]['shoppingCartSumFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartSumFields'][$stepNumber] : $this->configLayout[$this->jenisTr]['receiptSumFields'][$stepNumber],
//            "sumRows2" => $sumRows2,
            "sumRows2" => isset($this->configLayout[$this->jenisTr]['receiptSumFields2'][$stepNumber]) ? $this->configLayout[$this->jenisTr]['receiptSumFields2'][$stepNumber] : $sumRows2,
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
            "avoidRemoveAll_items" => $avoidRemoveAll_items,
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
            "items2_komposisi" => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),

            "itemElements" => isset($itemsElements) ? $itemsElements : array(),
            "itemElementConfigs" => isset($elementItemsAutoConfigs) ? $elementItemsAutoConfigs : array(),
            "itemElementResetTarget" => isset($itemsElementResetTarget) ? $itemsElementResetTarget : "",
            //--------------
            "checkOpname" => isset($checkOpname) ? $checkOpname : array(),
            "checkOpnamePaired" => base_url() . "ValueGate/checklistOpname/" . $this->jenisTr,
            //--------------
            "checkOpnameCek1" => isset($_SESSION[$cCode]['main']['opnameNoteCeklist_1']) ? $_SESSION[$cCode]['main']['opnameNoteCeklist_1'] : 0,
            "checkOpnameCek2" => isset($_SESSION[$cCode]['main']['opnameNoteCeklist_2']) ? $_SESSION[$cCode]['main']['opnameNoteCeklist_2'] : 0,
            "checkOpnameEnabled" => isset($checkNoteEnabled) ? $checkNoteEnabled : "",
            "checkOpnameNote1" => isset($pernyataanNote_1) ? $pernyataanNote_1 : "",
            "checkOpnameNote2" => isset($pernyataanNote_2) ? $pernyataanNote_2 : "",
            "checkOpnameNotePaired" => MODUL_PATH . get_class($this) . "/checklistOpnameNote/" . $this->jenisTr,
            "shopingCartAddTax" => $shopingCartTaxAdd,
            "shopingCartAddTaxAction" => MODUL_PATH . "_processPihak/selectTaxes/" . $this->jenisTr,
            "checkTaxes" => isset($_SESSION[$cCode]['main']['selectedType_konsumen']) ? $_SESSION[$cCode]['main']['selectedType_konsumen'] : "",
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
            $previewJurnal['header'] = array(
//                "dtime" => "date",
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

        if ($shopingCartReload) {
            //shopingCartReload
            $this->load->helper("he_value_builder");
            fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis);
            if (isset($_GET['stop1']) && $_GET['stop1'] == 1) {

            }
            else {
                echo "<script>";
                echo "setTimeout(function(){top.$('div#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0&stop1=1');},2000) ";
                echo "</script>";
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

        $key = $_GET['key'];


        heFetchElement_modul($jenisTr, $elName, $mdlName, $key, $this->configUiJenis);

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        //        mati_disini("kakakaak");
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

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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


        unset($_SESSION[$cCode]['main'][$elName]);
        unset($_SESSION[$cCode]['main_elements'][$elName]);


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


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

        //reset main juga
        if (isset($_SESSION[$cCode]['main'])) {
            unset($_SESSION[$cCode]);
        }

        //reset mode juga
        if (isset($_SESSION[$cCode]['mode'])) {
            unset($_SESSION[$cCode]);
        }


        //init sesssion
//        $initMaster = array(
//            "olehID" => $this->session->login['id'],
//            "olehName" => $this->session->login['nama'],
//            "placeID" => $this->session->login['cabang_id'],
//            "placeName" => $this->session->login['cabang_nama'],
//            "divID" => $this->session->login['div_id'],
//            "divName" => $this->session->login['div_nama'],
//            "cabangID" => $this->session->login['cabang_id'],
//            "cabangName" => $this->session->login['cabang_nama'],
//            "gudangID" => $this->session->login['gudang_id'],
//            "gudangName" => $this->session->login['gudang_nama'],
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
//            "jenisTrName" => $this->jenisTrName,
//            "stepNumber" => $stepNum,
//            "stepCode" => $this->configUi[$this->jenisTr]['steps'][$stepNum]['target'],
//            "dtime" => date("Y-m-d H:i:s"),
//            "fulldate" => date("Y-m-d"),
//
//            "sellerID" => $this->session->login['id'],
//            "sellerName" => $this->session->login['nama'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        foreach ($initMasterValues as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
            //            $_SESSION[$cCode]['main'][$key] = $val;
        }
        cekkuning("done resetting");

        echo "<script>";
        echo "top.fillBoxes();";
        echo "</script>";

        echo topReload(100);

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


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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

        //        cekOrange("seg-4:: $rowName --- $val");

        if (!isset($_SESSION[$cCode]['add_rows'])) {
            $_SESSION[$cCode]['add_rows'] = array();
        }
        $_SESSION[$cCode]['add_rows'][$rowName] = $val;
        $_SESSION[$cCode]['add_rows']['total_disc'] = $total_diskon;

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


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        //        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "/" . $_SESSION[$cCode]["main"]['stepNumber'] . "/?epreketek=yes');";
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

        $key = $_GET['key'];


        heFetchItemsElement_modul($jenisTr, $elName, $mdlName, $key, $helpName, $configUiJenis);

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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
        $iID = $_GET['iid'];
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
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
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
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

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
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
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
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes&populate=1');";
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
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

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

    //-----------------------------------
    public function buildValues()
    {
        $jenisTr = $this->jenisTr;
        $configUiJenis = $this->configUi[$jenisTr];
        $configCoreJenis = $this->configCore[$jenisTr];
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $configUiJenis['steps'][1]['label'],
//            "stepNumber" => 1,
//            "stepCode" => $configUiJenis['steps'][1]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";
    }

}