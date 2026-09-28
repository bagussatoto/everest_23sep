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
        $avoidRemoveAllItems = isset($this->configUi[$this->jenisTr]['shoppingCartAvoidRemoveAll_items']) ? $this->configUi[$this->jenisTr]['shoppingCartAvoidRemoveAll_items'] : false;
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

                                    $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+removeCommas(this.value)";


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
            "avoidRemoveAll_items" => $avoidRemoveAllItems,
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

        $start_time = microtime(true);
//        $this->jenisTr = $this->uri->segment(3);
        $cCode = $this->cCode;
        $this->load->helper('he_angka');

        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "main" => array(),
                "items" => array(),
            );
        }

//        cekBiru($_SESSION[$cCode]["main"]["mode"]);


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
        if (isset($_SESSION[$cCode]['main']['tipe_penjualan']) && ($_SESSION[$cCode]['main']['tipe_penjualan'] == 1)) {
            $itemLabels = isset($this->configUi[$this->jenisTr]['shoppingCartFieldsMarketplace'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFieldsMarketplace'][$stepNumber] : $itemLabels;
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
        $receiptElementsDeleter = isset($this->configUi[$this->jenisTr]['receiptElementsDeleter']) ? $this->configUi[$this->jenisTr]['receiptElementsDeleter'] : array();
        $receiptElementsReplacer = isset($this->configUi[$this->jenisTr]['receiptElementsReplacer']) ? $this->configUi[$this->jenisTr]['receiptElementsReplacer'] : array();
        $elementItemsAutoConfigs = isset($this->configUi[$this->jenisTr]['receiptElementsItemsAuto']) ? $this->configUi[$this->jenisTr]['receiptElementsItemsAuto'] : array();
        $arrHeaderElement = isset($this->configUi[$this->jenisTr]['shoppingCartHeaderElement'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartHeaderElement'][$stepNumber] : array();
        $relElementConfigs = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $addRowsConfigs = isset($this->configUi[$this->jenisTr]['additionalRows']) ? $this->configUi[$this->jenisTr]['additionalRows'] : array();
        $additionalRowsDeleter = isset($this->configUi[$this->jenisTr]['additionalRowsDeleter']) ? $this->configUi[$this->jenisTr]['additionalRowsDeleter'] : array();
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
        $shopingCartTicBox = isset($this->configUi[$this->jenisTr]['shopingcartAddTicbox']) ? $this->configUi[$this->jenisTr]['shopingcartAddTicbox'] : array();

        //----------------------------------------------
        $isSettlement = isset($this->configUi[$this->jenisTr]['isSettlement']) ? $this->configUi[$this->jenisTr]['isSettlement'] : false;
        //----------------------------------------------

        //----------------------------------------------
        $shopingCartPaymentItemsColorEnabled = isset($this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']['enabled']) ? $this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']['enabled'] : false;
        $shopingCartPaymentItemsColor = isset($this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor']) ? $this->configUi[$this->jenisTr]['shopingCartPaymentItemsColor'] : array();
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

                    //---------------
                    $iSpec['new_sisa'] = reformatExponent($iSpec['new_sisa']);
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


                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;
                    $tmp['sub_harga'] = isset($iSpec['sub_harga']) ? $iSpec['sub_harga'] : 0;
                    // cekLime(isset($iSpec['handler']) ? $iSpec['handler'] : '0');
                    // $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    // $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? $iSpec['handler'] : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;

                    //direwrite karena ada transaksi lama yg non modul, seingga selector nyasar
                    $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "penerimaan/", $iSpec['handler']) : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "penerimaan/", $iSpec['handler']) : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;

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


        //--------------------
        if (sizeof($additionalRowsDeleter) > 0) {
            if (isset($additionalRowsDeleter["enabled"]) && ($additionalRowsDeleter["enabled"] == true)) {
//                if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == $additionalRowsDeleter["tipe_penjualan"])) {
                if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && (array_key_exists($_SESSION[$cCode]["main"]["tipe_penjualan"], $additionalRowsDeleter["tipe_penjualan"]))) {
//                    foreach ($additionalRowsDeleter["additionalRows"] as $elementNama) {
                    foreach ($additionalRowsDeleter["tipe_penjualan"][$_SESSION[$cCode]["main"]["tipe_penjualan"]] as $elementNama) {
                        if (isset($addRowsConfigs["dummyElement"]["yes"][$elementNama])) {
                            $addRowsConfigs["dummyElement"]["yes"][$elementNama]["hideRow"] = true;
                        }
                    }
                }
            }
        }
        //--------------------


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
                                    if (isset($oValSpec['inputMode'])) {
                                        //region default value
//                                        if (isset($oValSpec['defaultValue'])) {
//                                            $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
//                                        }
                                        //endregion
                                        $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldInput/" . $this->jenisTr . "/$oValueName/?val='+this.value";
                                        $defVal = isset($_SESSION[$cCode]['main_inputs'][$oValueName]) ? $_SESSION[$cCode]['main_inputs'][$oValueName] : "";
                                        $inputs[$oValueName] = "<input type=text id='$oValueName' class='form-control text-center' placeholder='$oValueName' value='$defVal' onfocus='this.select()'  
onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}\"
onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src =$relInputTarget;}\"
>";
                                        $_SESSION[$cCode]['main_inputs'][$oValueName] = $defVal;
//                                        matiHere($defVal);
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
//                                matiHEre(__LINE__);
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
//                                        cekHitam($keyFilter);
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
//                                    cekOrange($this->db->last_query());
//                                    matiHEre(__LINE__);
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
//                                cekHitam($this->db->last_query());
//                                $end_time = microtime(true);
//                                $exec_time = $end_time-$start_time;
//                                cekMErah(__LINE__."$amdlName ::exec time ::".$exec_time);
//                                                                cekmerah($this->db->last_query());
//                                arrPrint($tmpo);
                                if (sizeof($tmpo) == 1) {
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    //cekBiru(":: $defValueSrc :: $usedKey ::");
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

//        $end_time = microtime(true);
//        $exec_time = $end_time-$start_time;
//        matiHEre(__LINE__."::exec time ::".$exec_time);

        //==menciptakan selektor/pilihan berdasarkan jenis elemen
        if (sizeof($elementConfigs) > 0) {

            if (sizeof($receiptElementsDeleter) > 0) {
//                cekHitam($_SESSION[$cCode]["main"]["tipe_penjualan"]);
//                arrPrintCyan($receiptElementsDeleter["tipe_penjualan"]);
//                if(array_key_exists($_SESSION[$cCode]["main"]["tipe_penjualan"], $receiptElementsDeleter["tipe_penjualan"])){
//                    arrPrintKuning($receiptElementsDeleter["tipe_penjualan"][$_SESSION[$cCode]["main"]["tipe_penjualan"]]);
//                }
                if (isset($receiptElementsDeleter["enabled"]) && ($receiptElementsDeleter["enabled"] == true)) {
//                    if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == $receiptElementsDeleter["tipe_penjualan"])) {
                    if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && (array_key_exists($_SESSION[$cCode]["main"]["tipe_penjualan"], $receiptElementsDeleter["tipe_penjualan"]))) {
//                        foreach ($receiptElementsDeleter["element"] as $elementNama) {
//                        arrPrintKuning($receiptElementsDeleter["tipe_penjualan"][$_SESSION[$cCode]["main"]["tipe_penjualan"]]);
                        foreach ($receiptElementsDeleter["tipe_penjualan"][$_SESSION[$cCode]["main"]["tipe_penjualan"]] as $elementNama) {
//                            cekHere("[$elementNama]");
                            if (isset($elementConfigs[$elementNama])) {
//                                $elementConfigs[$elementNama]["inputType"] = "hidden";
                                $elementConfigs[$elementNama]["hiddenBox"] = true;//"hiddenBox" => true,
                            }
                        }
                    }
                }
            }

            if (sizeof($receiptElementsReplacer) > 0) {
                if (isset($receiptElementsReplacer["enabled"]) && ($receiptElementsReplacer["enabled"] == 1)) {
                    if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == $receiptElementsReplacer["tipe_penjualan"])) {
                        foreach ($receiptElementsReplacer["element"] as $elementNama => $elementSpec) {
                            $elementConfigs[$elementNama] = $elementSpec;
                        }
                    }
                }
            }

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

                                    $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+removeCommas(this.value)";

                                    //==init value and params
                                    //region default value
                                    if (isset($oValSpec['defaultValue'])) {
                                        $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    }
                                    //endregion

                                    //cekmerah("$oValueName = ".$origDefValue);

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
                                    //region auto hide row
                                    if (isset($oValSpec["validate_reference"])) {
                                        $validate_reference = $oValSpec["validate_reference"];
                                        $src_reference = $validate_reference["src_reference"];
                                        $cek_prevals = $_SESSION[$cCode]['main'][$src_reference];
//                                        matiHere($cek_prevals);
                                        if (isset($validate_reference["defaultView"][$cek_prevals])) {
                                            //tampilkan
                                            $inputType = "text";
                                        }
                                        else {
                                            //hidden
                                            $inputType = "hidden";
                                        }
//                                        matiHEre($cek_prevals);
                                    }
                                    else {
                                        $inputType = "text";
                                    }
                                    //endregion

                                    $addRows[$oValueName] = "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text autocomplete='off' id='$oValueName' class='form-control text-right' style='font-size:17px;' $disabled placeholder='$oValueName' value='" . number_format($defVal, 0) . "' $minValStr $maxValStr
                                        onfocus='this.select()' 
                                        onkeyup=\"$keyupStr if(parseFloat(removeCommas(this.value))>0){this.value=addCommas(this.value)}else{this.value=0}\" 
                                        onfocus=\"$keyupStr\"
                                        onblur=\"$blurStr\"
                                        onmouseout=\"$blurStr\"
                                        >";
                                    $addLabel = "";
                                    if (isset($oValSpec['saldo'])) {
                                        $saldo = makeValue($oValSpec['saldo'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        $addLabel = "<br>Saldo : <span class='strong' id='saldo_$oValueName'>" . number_format($saldo, 0, ",", ".") . "</span>";
                                    }
                                    if (isset($oValSpec['option']) && (sizeof($oValSpec['option']) > 0)) {
                                        $pihakID_parent = (isset($_SESSION[$cCode]['main'][$oValSpec['option']['source']]) && $_SESSION[$cCode]['main'][$oValSpec['option']['source']] > 0) ? $_SESSION[$cCode]['main'][$oValSpec['option']['source']] : 0;
                                        if ($pihakID_parent > 0) {
                                            $pihakID_parent_source = $oValSpec['option']['source'];
                                            $pihakID_parent_key = $oValSpec['option']['key'];
                                            $pihakID_parent_key_value = $oValSpec['option']['key_value'];
                                            $pihakID_parent_label = $oValSpec['option']['label'];
//                                            $checked = ($pihakID_parent == $_SESSION[$cCode]['main'][$pihakID_parent_key]) ? "checked" : "";
                                            $checked = ($_SESSION[$cCode]['main'][$pihakID_parent_key] == 1) ? "checked" : "";
                                            $relInputTargetCek = "'" . MODUL_PATH . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$pihakID_parent_key_value/?val='+removeCommas(this.value)+'&cek=$pihakID_parent_key&state='+this.checked";
                                            $cekboxlink = "hiliteDiv(this);top.$('#result').load($relInputTargetCek);";
                                            $cekbox = "<input type='checkbox' value='$defVal' $checked
                                                onclick=\"$cekboxlink\"
                                                >";
                                            $addLabel .= "<br>$cekbox  <span class='meta'>$pihakID_parent_label **//**</span>";
                                        }
                                    }

                                    $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                    $addRowLabels[$oValueName] = $oValSpec['label'] . $addLabel;
                                    //region auto hide row
                                    if (isset($oValSpec["validate_reference"])) {
                                        $validate_reference = $oValSpec["validate_reference"];
                                        $src_reference = $validate_reference["src_reference"];
                                        $cek_prevals = $_SESSION[$cCode]['main'][$src_reference];
//                                        $defValues =
                                        if (isset($validate_reference["defaultView"][$cek_prevals])) {

                                        }
                                        else {
                                            //hidden
                                            unset($addRowLabels[$oValueName]);
                                        }
                                    }
                                    else {
                                    }
                                    //endregion
                                }
                            }
                        }
                    }
                    else {
                        // cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        // cekmerah("aturan untuk $currentValue TIDAK ada");
                    }
                }
                else {
                    // cekKuning("$eName TIDAK terdaftar pada relInputs");
                }
                switch ($eSpec['elementType']) {
                    case "dataModel":
                        $addStr = "";
                        $editStr = "";
                        $amdlName = $eSpec['mdlName'];
                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                        //                        arrPrint($aFilter);
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

                                $elStr[$eName] .= "<select " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " class='form-control' onchange=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">";
                                $elStr[$eName] .= "<option value=''>-select-</option>";

                                if (sizeof($tmpo) > 0) {
                                    if ($eName == "cash_account") {
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
                                                $labelAccount = isset($row->$labelSrc) ? $row->$labelSrc : '-';
                                                if (isset($elementConfigs[$eName]['labelSrcFields']) && (sizeof($elementConfigs[$eName]['labelSrcFields']) > 0)) {
                                                    $anu = "";
                                                    foreach ($elementConfigs[$eName]['labelSrcFields'] as $field_account) {
                                                        $anu .= $row->$field_account . " &nbsp; ";
                                                    }
                                                    $labelAccount = $anu;
                                                }

                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelAccount . "</option>";
                                            }
                                        }
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
                                                    }
                                                    $labelAccount = $anu;
                                                }
                                                $arrBankRekening[$row->folders][] = "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
<input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected 
onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelAccount . "</label><br>\n";

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
                                                $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $labelValue . "</label>\n";
                                            }
                                            else {
                                                $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                                $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $row->$labelSrc . "</label>\n";
                                            }
                                        }
                                    }
                                }
                                break;
                            case "multicheckboxform":
                                if (sizeof($tmpo) > 0) {
                                    $elStr[$eName] .= "<div class='row'>";
                                    foreach ($tmpo as $row) {
                                        $elStr[$eName] .= "<div class='col col-md-12 text-left'>";
                                        $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                        if (sizeof($ex) > 1) {
                                            $labelValue = "";
                                            foreach ($ex as $col) {
                                                $val = $row->$col;
                                                if (is_numeric($val)) {
                                                    $val = number_format($val, 0, ',', '.');
                                                }
                                                $labelValue .= $val . " / ";
                                            }
                                            $labelValue = rtrim($labelValue, " / ");
                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]['multi']) && isset($_SESSION[$cCode]['main_elements'][$eName]['multi'][$row->$keySrc]) ? "checked" : "";
                                            $elStr[$eName] .= "
                                            <label class='badge text-uppercase' styles='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                            <input type='checkbox' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget+'&multi=1'+'&state_multi=' + $(this).is(':checked') );\">" . $labelValue . "
                                            </label>\n";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
//                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]['multi']) && isset($_SESSION[$cCode]['main_elements'][$eName]['multi'][$row->$keySrc]) ? "checked" : "";
                                            $elStr[$eName] .= "
                                            <label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                                            <input type='checkbox' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget+'&multi=1'+'&state_multi=' + $(this).is(':checked') );\">" . $row->$labelSrc . "
                                            </label>\n";
                                        }
                                        $elStr[$eName] .= "</div>";
                                    }
                                    $elStr[$eName] .= "</div>";
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

                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;ffont-size:smaller;'>$defValue";
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

//        matiHEre(__LINE__."::exec time ::".$exec_time);
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
                                                // $oo->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                                            }
                                            else {
                                                if (isset($iSpec[$exFilter[1]])) {
                                                    // $oo->addFilter($exFilter[0] . "='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                                    $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $iSpec[$exFilter[1]];
                                                }
                                                else {
                                                    // $oo->addFilter($exFilter[0] . "='none'");
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
                                    top.BootstrapDialog.show({
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
                                $elPair[$amdlName] = array();
                                $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchItemsElement/" . $this->jenisTr . "/$eName/$amdlName/$helpName?key='+this.value";
                                $elStr[$eName] .= "<div class='box-body'>";

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
                $subjectNama = isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : "";
                $elementMutasiTmp = $elementMutasiConfig["branch"];
                foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                    if (isset($el_spec["sub_rekening"])) {
                        $blob_ext = $el_spec["label"] . " $subjectNama ";
                        $blob_ext = blobEncode($blob_ext);
                        $sub_rek = $el_spec["sub_rekening"];
                        $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID/$sub_rek?o=" . $this->session->login['cabang_id'] . "&main_ext2_id=$subjectID&blob_ext=$blob_ext";
                    }
                    else {
                        $elementMutasi[$el_nama] = base_url() . $el_spec['link'] . "$subjectID?o=" . $this->session->login['cabang_id'];
                    }
                }
            }
        }
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

        if (count($shopingCartTicBox) > 0) {
            $main["validate_biaya"] = isset($_SESSION[$cCode]["main"]["validate_biaya"]) ? $_SESSION[$cCode]["main"]["validate_biaya"] : false;
        }


        //--tambahan bila marketplace maka nilai_bayar -> nilai_bayar_marketplace--------
        $cloneGateItems = isset($this->configUi[$this->jenisTr]['cloneGateItems']) ? $this->configUi[$this->jenisTr]['cloneGateItems'] : array();
        if (sizeof($cloneGateItems) > 0) {
            if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == 1)) {
                if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    foreach ($_SESSION[$cCode]['items'] as $ii => $iiSpec) {
                        foreach ($cloneGateItems as $gkey => $gval) {
                            $_SESSION[$cCode]['items'][$ii][$gkey] = isset($iiSpec[$gval]) ? $iiSpec[$gval] : 0;
                        }
                    }
                }
            }
        }


        //region efaktur
        $shopingCartFaktur = isset($this->configUi[$this->jenisTr]['addMainSource'][1]) ? $this->configUi[$this->jenisTr]['addMainSource'][1] : array();
        $dataFaktur = array();
        if (count($shopingCartFaktur) > 0) {
//            if ($_SESSION[$cCode]["main"]["dpp_final"] == 0) {
////                $_SESSION[$cCode]["main"]["skip_faktur"]="true";
//                if (isset($shopingCartFaktur["gateTarget"])) {
//                    $gateTarget = $shopingCartFaktur["gateTarget"];
//                    if (isset($_SESSION[$cCode][$gateTarget])) {
//                        $_SESSION[$cCode][$gateTarget] = NULL;
//                        unset($_SESSION[$cCode][$gateTarget]);
//                    }
//                }
//            }
//            else {
//            }

            $showKey = isset($shopingCartFaktur["showKey"]) ? $shopingCartFaktur["showKey"] : NULL;
            if (isset($_SESSION[$cCode]["main"][$showKey]) && ($_SESSION[$cCode]["main"][$showKey] == 1)) {
                $show = isset($shopingCartFaktur["show"]) ? $shopingCartFaktur["show"] : false;
            }
            else {
                $show = false;
            }
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
                if (!isset($_SESSION[$cCode][$gateTarget])) {
                    $_SESSION[$cCode][$gateTarget][0] = $dataFaktur;
                }
                if (count($_SESSION[$cCode][$gateTarget]) == 1) {
//                        $_SESSION[$cCode][$gateTarget][0] = $dataFaktur;
//                    foreach ($dataFaktur as $aa => $bb) {
//                        $_SESSION[$cCode][$gateTarget][0][$aa] = $bb;
//                    }
                }
            }

//            $cekPPN = isset($shopingCartFaktur["enabledPpn"]) ? $shopingCartFaktur["enabledPpn"] : NULL;
//            $show = true;
//            if ($cekPPN != NULL) {
//                $cekPPN_nilai = isset($_SESSION[$cCode]["main"][$cekPPN]) ? $_SESSION[$cCode]["main"][$cekPPN] : 0;
//                $show = ($cekPPN_nilai > 0) ? true : false;
//            }
//            elseif ($_SESSION[$cCode]["main"]["ppnTransaksi"] == "0") {
//                $show = false;
//            }
//
//            if (sizeof($_SESSION[$cCode][$gateTarget]) > 0) {
//                $ppn_netto = isset($_SESSION[$cCode]["main"]["ppn_netto"]) ? $_SESSION[$cCode]["main"]["ppn_netto"] : 0;
//                $total_ppn = 0;
//                foreach ($_SESSION[$cCode][$gateTarget] as $ii => $iiSpec) {
//                    $total_ppn += $iiSpec["ppn_final"];
//                }
//                $selisih_ppn = $ppn_netto - $total_ppn;
//                $selisih_ppn = ($selisih_ppn < 0) ? ($selisih_ppn * -1) : $selisih_ppn;
//                if ($selisih_ppn > 100) {
//                    $formulirFakturStyle = array(
//                        "bgcolor" => "yellow",
//                    );
//                }
//                else {
//                    $formulirFakturStyle = array(
//                        "bgcolor" => "#00a65a",
//                    );
//                }
//            }
//

        }
        //endregion

        $modeLabelPenerimaan = isset($this->configUi[$this->jenisTr]['modeLabelPenerimaan']) ? $this->configUi[$this->jenisTr]['modeLabelPenerimaan'] : array();
        if (sizeof($modeLabelPenerimaan) > 0) {
            if ($modeLabelPenerimaan[$_SESSION[$cCode]["main"]["mode"]]) {
                $infoModeLabelPenerimaan = $modeLabelPenerimaan[$_SESSION[$cCode]["main"]["mode"]];
            }
        }

        $showDetailUangMuka = isset($this->configUi[$this->jenisTr]['showDetailUangMuka']) ? $this->configUi[$this->jenisTr]['showDetailUangMuka'] : array();
        if (sizeof($showDetailUangMuka) > 0) {
            if (isset($showDetailUangMuka[$_SESSION[$cCode]["main"]["mode"]]["enabled"]) && ($showDetailUangMuka[$_SESSION[$cCode]["main"]["mode"]]["enabled"] == true)) {
                $headers_inv = $showDetailUangMuka[$_SESSION[$cCode]["main"]["mode"]]["headers"];
                $gateTarget_inv = $showDetailUangMuka[$_SESSION[$cCode]["main"]["mode"]]["gateTarget"];
                $label_inv = $showDetailUangMuka[$_SESSION[$cCode]["main"]["mode"]]["label"];
                foreach ($_SESSION[$cCode][$gateTarget_inv] as $ii => $gSpec) {
                    foreach ($headers_inv as $key => $label) {
                        $data_inv[$ii][$key] = isset($gSpec[$key]) ? $gSpec[$key] : "";
                        if (is_array($gSpec[$key])) {
                            $anu = "";
                            foreach ($gSpec[$key] as $gsSpec) {
                                if ($anu == "") {
                                    $anu = $gsSpec["nama"];
                                }
                                else {
                                    $anu .= ", " . $gsSpec["nama"];
                                }
                            }
                            $data_inv[$ii][$key] = $anu;
                        }
                    }
                }
            }
        }
//arrPrint($data_inv);

        $this->load->helper("he_value_builder");
        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());

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
            "addBackground" => $addRowBackground,
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
//            "checkTaxes" => isset($_SESSION[$cCode]['main']['selectedType_konsumen']) ? $_SESSION[$cCode]['main']['selectedType_konsumen'] : "",
            "checkTaxes" => isset($_SESSION[$cCode]['main']['selectedType_konsumen']) ? $_SESSION[$cCode]['main']['selectedType_konsumen'] : "reguler",
            "default_description" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            "columnRecorderTarget" => MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr,
            "viewDescriptionNote" => isset($this->configUi[$this->jenisTr]['viewDescriptionNote']) ? $this->configUi[$this->jenisTr]['viewDescriptionNote'] : false,

            //--------------
            "arrItemTidakDibayar" => $arrItemTidakDibayar,
            "shopingCartPaymentItemsColor" => $shopingCartPaymentItemsColor,
            "shopingCartTicBox" => $shopingCartTicBox,

            "tipe_penjualan" => isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) ? $_SESSION[$cCode]["main"]["tipe_penjualan"] : 0,
            "mode_penerimaan" => isset($_SESSION[$cCode]["main"]["mode"]) ? $_SESSION[$cCode]["main"]["mode"] : "reguler",

            "shopingCartFakturParam" => $shopingCartFaktur,
            "shopingCartFakturItems" => $dataFaktur,
            "shopingCartFakturTarget" => isset($shopingCartFaktur["editProcess"]) ? $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr : "",
            "showFormulirFaktur" => isset($show) ? $show : false,
            "formulirFaktur" => isset($_SESSION[$cCode][$gateTarget]) ? $_SESSION[$cCode][$gateTarget] : array(),

            "infoModeLabelPenerimaan" => isset($infoModeLabelPenerimaan) ? $infoModeLabelPenerimaan : NULL,
            "headers_inv" => isset($headers_inv) ? $headers_inv : array(),
            "data_inv" => isset($data_inv) ? $data_inv : array(),
            "label_inv" => isset($label_inv) ? $label_inv : array(),
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
//            $previewJurnal = previewJurnal($this->jenisTr);
            $previewJurnal = previewJurnal_he_accounting($this->jenisTr, $this->configUi[$this->jenisTr], $this->configCore[$this->jenisTr], $_SESSION[$cCode]["main"]["mode"]);
            $previewJurnal['header'] = array(
//                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            if($_SESSION[$cCode]["main"]["mode"] != "reguler"){
                $data['previewJurnal'] = $previewJurnal;
            }
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

    public function viewCart_New()
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
                    $tmp["editTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "penerimaan/", $iSpec['handler']) : '0') . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    $tmp["removeTarget"] = base_url() . (isset($iSpec['handler']) ? str_replace("Selectors/", "penerimaan/", $iSpec['handler']) : '0') . "/remove/" . $this->jenisTr . "?id=" . $id;

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
                    $sumSpec2[$key] = "<input type='text' class='form-control form-control-sm text-right' name=$key id=$key value='$defaultValue'
                    onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                    onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                    >";
                    $sumType2[$key] = "number";
                    //endregion
                    if ($vSpec['taxFactor'] > 0) {
                        $key = $vName . "_tax";
                        $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                        $sumRows2[$key] = "tax for " . $vSpec['label'];
                        $sumSpec2[$key] = "<input type='text' class='form-control form-control-sm text-right' name=$key id=$key value='$defaultValue'
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
                        $sumSpec2[$key] = "<input type='text' readonly class='form-control form-control-sm text-right' name=$key id=$key value='$defaultValue'
                        onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                        onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);top.$('#result').load('" . $sumRowAction . "?key=$key&value='+removeCommas(this.value));}\"
                        >";
                        $sumType2[$key] = "number";
                        //endregion
                        if ($vSpec['taxFactor'] > 0) {
                            $key = $vName . "_tax";
                            $defaultValue = isset($addValues[$key]) ? $addValues[$key] : 0;
                            $sumRows2[$key] = "tax for " . $vSpec['label'];
                            $sumSpec2[$key] = "<input type='text' readonly class='form-control form-control-sm text-right' name=$key id=$key value='$defaultValue'
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
                                    if (isset($oValSpec['inputMode'])) {
                                        //region default value
//                                        if (isset($oValSpec['defaultValue'])) {
//                                            $origDefValue = makeValue($oValSpec['defaultValue'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
//                                        }
                                        //endregion
                                        $relInputTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldInput/" . $this->jenisTr . "/$oValueName/?val='+this.value";
                                        $defVal = isset($_SESSION[$cCode]['main_inputs'][$oValueName]) ? $_SESSION[$cCode]['main_inputs'][$oValueName] : "";
                                        $inputs[$oValueName] = "<input type=text id='$oValueName' class='form-control form-control-sm text-center' placeholder='$oValueName' value='$defVal' onfocus='this.select()'
onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src=$relInputTarget;}\"
onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src =$relInputTarget;}\"
>";
                                        $_SESSION[$cCode]['main_inputs'][$oValueName] = $defVal;
//                                        matiHere($defVal);
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
                                        $inputs[$oValueName] = "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text id='$oValueName' class='form-control form-control-sm text-center' placeholder='$oValueName' value='" . number_format($defVal) . "' min='$minValue' max='$maxValue' onfocus='this.select()'
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

                                    //region auto hide row
                                    if (isset($oValSpec["validate_reference"])) {
                                        $validate_reference = $oValSpec["validate_reference"];
                                        $src_reference = $validate_reference["src_reference"];
                                        $cek_prevals = $_SESSION[$cCode]['main'][$src_reference];
                                        if (isset($validate_reference["defaultView"][$cek_prevals])) {
                                            //tampilkan
                                            $inputType = "text";
                                        }
                                        else {
                                            //hidden
                                            $inputType = "hidden";
                                        }
                                    }
                                    else {
                                        $inputType = "text";
                                    }
                                    //endregion
                                    $addRows[$oValueName] = "<input " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " type=text autocomplete='off' id='$oValueName' class='form-control form-control-sm text-right' style='font-size:17px;' $disabled placeholder='$oValueName' value='" . $defVal . "' $minValStr $maxValStr
                                        onfocus='this.select()' onkeyup=\"$keyupStr if(parseFloat(removeCommas(this.value))>0){this.value=addCommas(this.value)}else{this.value=0}\" onfocus=\"$keyupStr\"
                                        onblur=\"$blurStr\"
                                        onmouseout=\"$blurStr\"
                                        >";
                                    $addLabel = "";
                                    if (isset($oValSpec['saldo'])) {
                                        $saldo = makeValue($oValSpec['saldo'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        $addLabel = "<br>Saldo : <span class='strong'>" . number_format($saldo, 0, ",", ".") . "</span>";
                                    }

                                    $_SESSION[$cCode]['add_rows'][$oValueName] = $defVal;
                                    $addRowLabels[$oValueName] = $oValSpec['label'] . $addLabel;
                                    //region auto hide row
                                    if (isset($oValSpec["validate_reference"])) {
                                        $validate_reference = $oValSpec["validate_reference"];
                                        $src_reference = $validate_reference["src_reference"];
                                        $cek_prevals = $_SESSION[$cCode]['main'][$src_reference];
//                                        $defValues =
                                        if (isset($validate_reference["defaultView"][$cek_prevals])) {
                                        }
                                        else {
                                            //hidden
                                            unset($addRowLabels[$oValueName]);
                                        }
                                    }
                                    else {
                                    }
                                    //endregion
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

                        $hiddenSelect = "";
                        if (isset($eSpec['hiddenSelect']) && $eSpec['hiddenSelect'] == 1) {
                            $hiddenSelect = " hidden";
                        }
                        $hiddenBox = "";
                        if (isset($eSpec['hiddenBox']) && $eSpec['hiddenBox'] == 1) {
                            $hiddenBox = " hidden";
                        }

                        $elStr[$eName] .= "<div style='margin-top: 8px;' class='box-body$hiddenSelect no-padding'>";

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

//                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
//                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";

                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";

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

                                            if (is_numeric($labelValue)) {
                                                $labelssss = number_format($labelValue, 0);
                                            }
                                            else {
                                                $labelssss = $labelValue;
                                            }

                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $labelssss . "</label>\n";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";

                                            if (is_numeric($row->$labelSrc)) {
                                                $labelssss = number_format($row->$labelSrc, 0);
                                            }
                                            else {
                                                $labelssss = $row->$labelSrc;
                                            }

                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $labelssss . "</label>\n";
                                        }

                                    }
                                }
                                break;
                            case "table":

                                $selectorTargetTable = "'" . MODUL_PATH . get_class($this) . "/fetchElementTable/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";
                                $saveTargetTable = "'" . MODUL_PATH . get_class($this) . "/saveElementTable/" . $this->jenisTr . "/$eName/$amdlName";
                                $closeTargetTable = "'" . MODUL_PATH . get_class($this) . "/deleteElementTable/" . $this->jenisTr . "/$eName/$amdlName";

                                //BEGIN OF MULTI PAYMENT
                                $elStr[$eName] .= "<div class='container-fluid'>";

                                //VIEW JIKA SUDAH ADA PEMBAYARAN
                                $elStr[$eName] .= "<div class='row'>";

                                $arrTunai = array();

                                if (isset($_SESSION[$cCode]['payment']) && count($_SESSION[$cCode]['payment']) != 0) {

                                    $elStr[$eName] .= "<div class='text-left'><b>KONSUMEN:</b> " . $_SESSION[$cCode]['main']['pihakName'] . "</div>";
                                    $elStr[$eName] .= "<div class='text-left'><b>CST TYPE:</b> " . (!isset($_SESSION[$cCode]['main']['selectedType_konsumen']) ? "<b class='blink'><r><i class='fa fa-warning'></i> BELUM MEMILIH JENIS KONSUMEN <i class='fa fa-warning'></i></r></b>" : $_SESSION[$cCode]['main']['selectedType_konsumen']) . "</div>";

                                    if (!isset($_SESSION[$cCode]['main']['selectedType_konsumen'])) {
                                        $elStr[$eName] .= "<div class='text-left cont_tipe_konsumen'></div>";
                                        $elStr[$eName] .= "<script>

                                        $('.cont_tipe_konsumen').html( $('.tipe_konsumen_label').parent() );

                                        </script>";
                                    }

                                    $elStr[$eName] .= "<div class='text-left'>&nbsp;</div>";
                                    $elStr[$eName] .= "<div class='text-left'><b>NILAI HARUS DIBAYAR:</b> <span class='pull-right text-bold'>" . number_format($_SESSION[$cCode]['main']['harus_bayar']) . "</span></div>";
                                    $elStr[$eName] .= "<div class='text-left'><b>PEMBAYARAN TERPILIH:</b> <span class='pull-right text-bold'>" . number_format($_SESSION[$cCode]['main']['nilai_entry']) . "</span></div>";

                                    if ($_SESSION[$cCode]['main']['selectedType_konsumen'] == "bendahara_negara") {
                                        $elStr[$eName] .= "<div class='text-left text-yellow'><b>PPN DIBAYAR BENDAHARA NEGARA :</b> <span class='pull-right text-bold'>" . number_format($_SESSION[$cCode]['main']['ppn_nilai_dibayar']) . "</span></div>";
                                    }

                                    if ($_SESSION[$cCode]['main']['lebih_bayar'] * 1 > 0) {
                                        $elStr[$eName] .= "<div class='text-left text-green'><h4><b>LEBIH BAYAR:</b> <span class='pull-right text-bold'>" . number_format($_SESSION[$cCode]['main']['lebih_bayar']) . "</span></h4></div>";
                                    }
                                    else {
                                        $colorText = $_SESSION[$cCode]['main']['new_sisa'] * 1 == 0 ? "green" : "red";
                                        $elStr[$eName] .= "<div class='text-left text-$colorText'><h4><b>KURANG BAYAR:</b> <span class='pull-right text-bold'>" . number_format($_SESSION[$cCode]['main']['new_sisa']) . "</span></h4></div>";
                                    }

                                    $elStr[$eName] .= "<table class='table dataTable compact table-bordered'>";
                                    $elStr[$eName] .= "<caption class='text-left text-muted text-bold no-padding'><span class='btn btn-xs btn-flat btn-default' disabled ><i class='fa fa-money'></i></span> Jenis Pembayaran</caption>";
                                    $elStr[$eName] .= "<tbody>";
                                    $nu = 0;
                                    $tot = 0;
                                    foreach ($_SESSION[$cCode]['payment'] as $rowID => $pymrow) {
                                        $nu++;
                                        $elStr[$eName] .= "<tr>";
                                        $elStr[$eName] .= "<td>$nu</td>";

                                        //switch style nama
                                        switch ($pymrow['payment_jenis']) {
                                            case "tunai":
                                                $elStr[$eName] .= "<td class='text-left'>" . $pymrow["nama"] . "</td>";
                                                break;
                                            case "qris":
                                                $elStr[$eName] .= "<td class='text-left'>";
                                                $elStr[$eName] .= "<div class='text-blue text-bold'>QRIS</div>";
                                                $elStr[$eName] .= "<div>Details:</div>";
                                                $elStr[$eName] .= "<div>" . $pymrow['folders_nama'] . " | " . $pymrow['nama'] . "</div>";
                                                $elStr[$eName] .= "</td>";
                                                break;
                                            case "banktransfer":
                                                $elStr[$eName] .= "<td class='text-left'>";
                                                $elStr[$eName] .= "<div class='no-padding col-xs-12 text-blue text-bold'>Transfer Bank</div>";

                                                $elStr[$eName] .= "<div class='no-padding col-xs-6'>";
                                                $elStr[$eName] .= "<div class='text-bold text-warning'>From:</div>";
                                                $elStr[$eName] .= "<div>" . $pymrow['bank_pengirim'] . " | " . $pymrow['rekening_pengirim'] . "</div>";
                                                $elStr[$eName] .= "<div>A/n. " . $pymrow['alias_pengirim'] . "</div>";
                                                $elStr[$eName] .= "<div>refno. " . $pymrow['refno'] . "</div>";
                                                $elStr[$eName] .= "</div>";

                                                $elStr[$eName] .= "<div class='no-padding col-xs-6'>";
                                                $elStr[$eName] .= "<div class='text-bold text-green'>Tujuan:</div>";
                                                $elStr[$eName] .= "<div>" . $pymrow['folders_nama'] . " | " . $pymrow['nama'] . "</div>";
                                                $elStr[$eName] .= "<div>A/n. " . $pymrow['alias'] . "</div>";
                                                $elStr[$eName] .= "</div>";

                                                $elStr[$eName] .= "</td>";
                                                break;
                                            case "kartukredit":
                                                $elStr[$eName] .= "<td class='text-left'>";
                                                $elStr[$eName] .= "<div class='text-blue text-bold'>Kartu Kredit</div>";
                                                $elStr[$eName] .= "<div>Details:</div>";
                                                $elStr[$eName] .= "<div>" . $pymrow['folders_nama'] . " | " . $pymrow['nama'] . "</div>";
                                                $elStr[$eName] .= "</td>";
                                                break;
                                            case "kartudebit":
                                                $elStr[$eName] .= "<td class='text-left'>";
                                                $elStr[$eName] .= "<div class='text-blue text-bold'>Kartu Debit</div>";
                                                $elStr[$eName] .= "<div>To:</div>";
                                                $elStr[$eName] .= "<div>" . $pymrow['folders_nama'] . " | " . $pymrow['nama'] . "</div>";
                                                $elStr[$eName] .= "</td>";
                                                break;
                                        }


                                        $elStr[$eName] .= "<td class='text-right text-bold'>" . number_format($pymrow["nilai"]) . "</td>";
                                        $elStr[$eName] .= "<td><span id='btnRemoveTable' idpayment='$rowID' class='btn btn-xs btn-danger'><i class='fa fa-close'></i></span></td>";
                                        $elStr[$eName] .= "</tr>";
                                        $tot += $pymrow["nilai"] * 1;

                                        if ($pymrow["nama"] == "tunai") {
                                            $arrTunai[] = $pymrow["nama"];
                                        }
                                    }

                                    $elStr[$eName] .= "<tr>";
                                    $elStr[$eName] .= "<td>-</td>";
                                    $elStr[$eName] .= "<td class='text-left'>-</td>";
                                    $elStr[$eName] .= "<td class='text-right text-bold'>" . number_format($tot) . "</td>";
                                    $elStr[$eName] .= "<td>-</td>";
                                    $elStr[$eName] .= "</tr>";

                                    $elStr[$eName] .= "<tr>";
                                    $elStr[$eName] .= "<td colspan='4'>";
                                    $elStr[$eName] .= "<div class='text-bold text-left'><i>Terbilang:</i></div>";
                                    $elStr[$eName] .= "<div class='terbilang text-left'></div>";
                                    $elStr[$eName] .= "</td>";
                                    $elStr[$eName] .= "</tr>";

                                    $elStr[$eName] .= "</tbody>";
                                    $elStr[$eName] .= "</table>";

                                    $elStr[$eName] .= "<script>
                                            top.$('#btnRemoveTable').on('click', function(){
                                                var idpayment = $(this).attr('idpayment')
                                                top.$('#result').load($closeTargetTable?key=remove', {id: idpayment});
                                            });
                                            $('#nilai_entry').val($tot).triggerAll('blur');
                                            $('.terbilang').html('---' + pembilang($tot,1) +' ---');
                                        </script>";

                                }
                                else {
                                    $elStr[$eName] .= "<div class='alert bg-red text-bold'>
                                        <i class='fa fa-warning'></i> ANDA BELUM MEMILIH PEMBAYARAN <i class='fa fa-warning'></i>
                                        <div class='text-bold'><br><h4>" . $_SESSION[$cCode]['main']['pihakName'] . "</h4></div>
                                        <div class='text-bold text-uppercase'><h3>TAGIHAN SENILAI <b class='text-black' style='font-size: 25px;'>" . number_format($_SESSION[$cCode]['main']['sisa']) . "</b> DARI " . count($_SESSION[$cCode]['items']) . " INVOICE</h3></div>
                                    ";
                                    $elStr[$eName] .= "</div>";

                                    $elStr[$eName] .= "<script>
                                            $('#nilai_entry').val(0).triggerAll('blur');
                                        </script>";
                                }

                                $elStr[$eName] .= "</div>";

                                $elStr[$eName] .= "<div style='margin-bottom: 10px;' class='row'>";
                                $elStr[$eName] .= "<div style='' class='text-capitalize text-left text-bold text-muted col-md-12 no-padding'><span class='btn btn-xs btn-flat btn-default' disabled ><i class='fa fa-plus'></i></span> tambah metode pembayaran";
                                $elStr[$eName] .= "</div>";

                                $elStr[$eName] .= "<div class='text-left col col-md-12 no-padding'>";
                                $elStr[$eName] .= "<select class='form-control form-control-sm' onchange=\"hiliteDiv(this);top.$('#result').load($selectorTargetTable);\">";

                                $newSelectID = "null";

                                $elStr[$eName] .= "<option eName='$eName' mainElName='$mainElName' selected value=''>-- pilih pembayaran --</option>\n";

                                if (sizeof($tmpo) > 0) {
                                    foreach ($tmpo as $row) {
                                        $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                        $cTunai = count($arrTunai);
                                        if (sizeof($ex) > 1) {
                                            $labelValue = "";
                                            foreach ($ex as $col) {
                                                $labelValue .= $row->$col . " / ";
                                            }
                                            $labelValue = rtrim($labelValue, " / ");
                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $newSelectID = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? $row->$keySrc : $newSelectID;
                                            $mainElName = $_SESSION[$cCode]['main_elements'][$eName];
//                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $labelValue . "</label>\n";
                                            $elStr[$eName] .= "<option eName='$eName' mainElName='$mainElName' $selected value='" . $row->$keySrc . "'>" . $labelValue . "</option>\n";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $newSelectID = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? $row->$keySrc : $newSelectID;
                                            $tunaiDis = $cTunai > 0 && $row->$keySrc == "tunai" ? "disabled" : "";
//                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'><input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected onclick=\"hiliteDiv(this);top.$('#result').load($selectorTarget);\">" . $row->$labelSrc . "</label>\n";
                                            $elStr[$eName] .= "<option  $selected value='" . $row->$keySrc . "' $tunaiDis>" . $row->$labelSrc . "</option>\n";
                                        }
                                    }
                                }

                                $elStr[$eName] .= "</select>";
                                $elStr[$eName] .= "</div>";

                                $defKey = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : 0;
                                $showNull = isset($elementConfigs[$eName]['showNull']) ? $elementConfigs[$eName]['showNull'] : false;
                                $nullValue = isset($elementConfigs[$eName]['nullValue']) ? $elementConfigs[$eName]['nullValue'] : "";
                                $nullSrc = isset($elementConfigs[$eName]['nullSrc']) ? $elementConfigs[$eName]['nullSrc'] : "";

                                $defValue = "";
                                if (isset($_SESSION[$cCode]['main_elements'][$eName]['key']) && $_SESSION[$cCode]['main_elements'][$eName]['contents']) {
                                    if (isset($elementConfigs[$eName]['usedFields']) && sizeof($elementConfigs[$eName]['usedFields']) > 0) {
//                                        $defValue .= "<div class='panel-body'>";
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
                                                    $newValue_r = ($fieldLabel == "" && $showNull == true && $nullValue != "") ? ($nullSrc == $label ? $nullValue : $fieldLabel) : $fieldLabel;
                                                    if ($src == 'saldo') {
                                                        $arrNewValue_r = explode('+', $newValue_r);
                                                        $newSaldo = 0;
                                                        if (sizeof($arrNewValue_r) > 0) {
                                                            foreach ($arrNewValue_r as $k => $kVal) {
                                                                $newSaldo += $kVal;
                                                            }
                                                        }
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
//                                        $defValue .= "</div class='panel-body'>";
                                    }
                                }
                                else {//menentukan nilai default

                                }

                                if ($defKey > 0) {
                                    if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                        $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                        if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                            $editClick = "
                                            top.BootstrapDialog.show({
                                                title:'New " . $eSpec['label'] . "',
                                                message: top.$('<div></div>').load('" . $editLink . "'),
                                                draggable:true,
                                                size:BootstrapDialog.SIZE_WIDE,
                                                closable:true,
                                                type:top.BootstrapDialog.TYPE_SUCCESS,
                                            });";
                                            $editStr = "<a " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                        }
                                    }
                                }

                                $elStr[$eName] .= "<div class='col col-md-12 no-padding' id='divel_$eName' style='padding:2px;font-size:smaller;'>";
                                $elStr[$eName] .= "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='margin-top: 10px;' class='table dataTable compact nowrap'>";

                                switch ($newSelectID) {
                                    case "tunai":

                                        $amdlName = "MdlBankAccount_cash";
                                        $this->load->model("Mdls/" . $amdlName);
                                        $bt = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
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

                                                        }
                                                    }
                                                }
                                            }
                                            $bt = makeFilter($aFilter, $_SESSION[$cCode]['main'], $bt);
                                        }

                                        $tmpBt = $bt->lookupAll()->result();

                                        if (sizeof($tmpBt) > 0) {
                                            foreach ($tmpBt as $row) {
                                                $elStr[$eName] .= "<input name='conts_$newSelectID' eName='$eName' id='$keySrc' class='hidden' value='" . $row->$keySrc . "'>";
                                                $labelSrc = $eSpec["paymentType"][$newSelectID]['labelSrc'];
                                                $ex = $elementConfigs[$eName]["paymentType"][$newSelectID]['labelSrc'];
                                                if (count($ex) > 1) {
                                                    foreach ($ex as $col) {
                                                        $elStr[$eName] .= "<input name='conts_$newSelectID' eName='$eName' id='$col' class='hidden' value='" . $row->$col . "'>";
                                                    }
                                                }
                                                else {
                                                    $elStr[$eName] .= "<input name='conts_$newSelectID' eName='$eName' id='$labelSrc' class='hidden' value='" . $row->$labelSrc . "'>";
                                                }
                                            }
                                        }

                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>MASUKAN JUMLAH UANG TUNAI</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='js_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "<td class='float-right'><span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-block btn-success'><i class='fa fa-save'></i></span></td>";

                                        $elStr[$eName] .= "<script>
                                            top.$('#js_$newSelectID').on('keyup', function(){
                                                $(this).val( addCommas(removeCommas(this.value)) )
                                            });
                                            top.$('#btnSave_$newSelectID').on('click', function(){
                                                if( $('#js_$newSelectID').val() != '' ){
                                                    var nilai = removeCommas( $('#js_$newSelectID').val() );
                                                    var arrInput = $(\"input[name='conts_$newSelectID']\")
                                                    var newObj = {};
                                                    jQuery.each(arrInput, function(a,b){
                                                        newObj[$(b).attr('id')] = $(b).val()
                                                    })
                                                    var obj = Object.assign({payment_jenis: '$newSelectID', nilai: nilai}, newObj);
                                                    top.$('#result').load($saveTargetTable?key=$newSelectID', obj);
                                                }
                                            });
                                            top.$('#js_$newSelectID').focus();
                                            top.$('#js_$newSelectID').on('click', function(){
                                                $(this).select()
                                            });

                                            top.$('#js_$newSelectID').on('keyup', function (e) {
                                                if (e.key === 'Enter' || e.keyCode === 13) {
                                                    $('#btnSave_$newSelectID').trigger('click')
                                                }
                                            });

                                        </script>";

                                        break;
                                    case "banktransfer":
                                        $amdlName = "MdlListAllBank";
                                        $labelSelect = "bank tujuan";
                                        $this->load->model("Mdls/" . $amdlName);
                                        $bt = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
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

                                                        }
                                                    }
                                                }
                                            }
                                            $bt = makeFilter($aFilter, $_SESSION[$cCode]['main'], $bt);
                                        }

                                        $tmpBt = $bt->lookupAll()->result();

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>BANK & REK PENGIRIM</span></td>";

                                        $elStr[$eName] .= "<td colspan=''>";

                                        $elStr[$eName] .= "<div class='input-group input-group-sm'>";
                                        $elStr[$eName] .= "<div class='col-xs-5 no-padding'>";
                                        $elStr[$eName] .= "<select id='bank_pengirim_$newSelectID' class='form-control form-control-sm'>";
                                        $elStr[$eName] .= "<option value=''>- PILIH BANK PENGIRIM -</option>";
                                        if (sizeof($tmpBt) > 0) {
                                            foreach ($tmpBt as $row) {
                                                $labelSrc = "nama";
                                                $ex = explode("/", $elementConfigs[$eName]["paymentType"][$newSelectID]['listallbank']['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {
                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                                }
                                            }
                                        }
                                        $elStr[$eName] .= "</select>";
                                        $elStr[$eName] .= "</div>";
                                        $elStr[$eName] .= "<div class='col-xs-7 no-padding'>";
                                        $elStr[$eName] .= "<input type='text' eName='$eName' id='rekening_pengirim_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nomer rekening pengirim'>";
                                        $elStr[$eName] .= "</div>";
                                        $elStr[$eName] .= "</div>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NAMA PENGIRIM</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='alias_pengirim_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nama/alias pengirim'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $amdlName = "MdlBankAccount_in";
                                        $labelSelect = "bank tujuan";
                                        $this->load->model("Mdls/" . $amdlName);
                                        $bt = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
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

                                                        }
                                                    }
                                                }
                                            }
                                            $bt = makeFilter($aFilter, $_SESSION[$cCode]['main'], $bt);
                                        }

                                        $tmpBt = $bt->lookupAll()->result();

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>TUJUAN</span></td>";

                                        $elStr[$eName] .= "<td colspan=''>";
                                        $elStr[$eName] .= "<select id='el_$newSelectID' class='form-control form-control-sm'>";
                                        $elStr[$eName] .= "<option value=''>- BANK & REK TUJUAN -</option>";
                                        if (sizeof($tmpBt) > 0) {
                                            foreach ($tmpBt as $row) {
                                                $labelSrc = $eSpec["paymentType"][$newSelectID]['labelSrc'];
                                                $ex = explode("/", $elementConfigs[$eName]["paymentType"][$newSelectID]['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {
                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                                }
                                            }
                                        }
                                        $elStr[$eName] .= "</select>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>WAKTU TRANSFER</span></td>";
                                        $elStr[$eName] .= "<td>
                                            <div class='input-group input-group-sm'>
                                                <div class='col-md-4 no-padding'>
                                                    <input type='text' eName='$eName' id='tgl_$newSelectID' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='TANGGAL'>
                                                </div>
                                                <div class='col-md-1 no-padding'><span class='form-control form-control-sm form-control-borderless text-bold text-center'>/</span></div>
                                                <div class='col-md-3 no-padding'>
                                                    <input type='text' eName='$eName' id='bln_$newSelectID' onkeypress='return checkBulan(event)' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='BULAN'>
                                                </div>
                                                <div class='col-md-1 no-padding'><span class='form-control form-control-sm form-control-borderless text-bold text-center'>/</span></div>
                                                <div class='col-md-3 no-padding'>
                                                    <input type='text' eName='$eName' id='thn_$newSelectID' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='TAHUN'>
                                                </div>
                                            </div>
                                        </td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>REF.NO</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='refno_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nomor referensi transfer'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NILAI</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='nilai_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td>&nbsp;</td>";
                                        $elStr[$eName] .= "<td class='float-right'>";
                                        $elStr[$eName] .= "<span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-blocsk btn-success'><i class='fa fa-save'></i> save</span>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";


//                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='js_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
//                                        $elStr[$eName] .= "<td class='float-right'><span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-success'><i class='fa fa-save'></i></span></td>";

                                        $elStr[$eName] .= "<script>
                                            top.$('#nilai_$newSelectID').on('keyup', function(){
                                                $(this).val( addCommas(removeCommas(this.value))  )
                                            });
                                            top.$('#btnSave_$newSelectID').on('click', function(){
                                                var minimalValue = 10000;
                                                if( removeCommas($('#nilai_$newSelectID').val())*1 > minimalValue  && $('#el_$newSelectID').val()*1 > 0 ){
                                                    var nilai = removeCommas( $('#nilai_$newSelectID').val() );
                                                    var childData = $('#el_$newSelectID option:selected').attr('child');
                                                    var arrInput = JSON.parse(atob(childData))
                                                    var newObj = {};
                                                    jQuery.each(arrInput, function(a,b){
                                                        newObj[a] = b
                                                    });

                                                    newObj['bank_pengirim'] = $('#bank_pengirim_$newSelectID').val();
                                                    newObj['rekening_pengirim'] = $('#rekening_pengirim_$newSelectID').val();
                                                    newObj['alias_pengirim'] = $('#alias_pengirim_$newSelectID').val();

                                                    var tgl = $('#tgl_$newSelectID').val();
                                                    var bln = $('#bln_$newSelectID').val();
                                                    var thn = $('#thn_$newSelectID').val();

                                                    newObj['waktu_pengirim'] = thn+'-'+bln+'-'+tgl;

                                                    var obj = Object.assign({payment_jenis: '$newSelectID', nilai: nilai}, newObj);

                                                    top.$('#result').load($saveTargetTable?key=$newSelectID', obj);
                                                }
                                                else{
                                                    var selector = $('#el_$newSelectID').val();
                                                    var input = $('#nilai_$newSelectID').val();
                                                    var textError = ''
                                                    if(selector==''){
                                                        textError += '<div>!! Bank Tujuan Belum ditentukan !!</div>'
                                                    }
                                                    if(input == ''){
                                                        textError += '<div>!! Nilai Transfer belum di isi !!</div>'
                                                    }
                                                    else if( removeCommas(input)*1 < minimalValue ){
                                                        textError += '<div>!! Nilai Transfer yang dimasukan salah !!</div>'
                                                    }
                                                    swal('mohon cek kembali', textError, 'warning');
                                                }
                                            });

                                            top.$('#js_$newSelectID').on('keyup', function (e) {
                                                if (e.key === 'Enter' || e.keyCode === 13) {
                                                    $('#btnSave_$newSelectID').trigger('click')
                                                }
                                            });
                                        </script>";

                                        break;
                                    case "qris":

                                        $amdlName = "MdlListQris";
                                        $this->load->model("Mdls/" . $amdlName);
                                        $bt = new $amdlName();
                                        $tmpBt = $bt->lookupAll()->result();

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>TENTUKAN QRIS TUJUAN</span></td>";

                                        $elStr[$eName] .= "<td colspan=''>";
                                        $elStr[$eName] .= "<select id='qris_$newSelectID' class='form-control form-control-sm'>";
                                        $elStr[$eName] .= "<option value=''>- QRIS TUJUAN -</option>";
                                        if (sizeof($tmpBt) > 0) {
                                            foreach ($tmpBt as $row) {
                                                $labelSrc = $eSpec["paymentType"][$newSelectID]['labelSrc'];
                                                $ex = explode("/", $elementConfigs[$eName]["paymentType"][$newSelectID]['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {
                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                                }
                                            }
                                        }
                                        $elStr[$eName] .= "</select>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NILAI</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='nilai_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td>&nbsp;</td>";
                                        $elStr[$eName] .= "<td class='float-right'>";
                                        $elStr[$eName] .= "<span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-blocsk btn-success'><i class='fa fa-save'></i> save</span>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<script>
                                            top.$('#nilai_$newSelectID').on('keyup', function(){
                                                $(this).val( addCommas(removeCommas(this.value))  )
                                            });
                                            top.$('#btnSave_$newSelectID').on('click', function(){
                                                if( $('#nilai_$newSelectID').val() != '' ){
                                                    var nilai = removeCommas( $('#nilai_$newSelectID').val() );
                                                    var childData = $('#qris_$newSelectID option:selected').attr('child');
                                                    var arrInput = JSON.parse(atob(childData));
                                                    var newObj = {};
                                                    jQuery.each(arrInput, function(a,b){
                                                        newObj[a] = b
                                                    })
                                                    var obj = Object.assign({payment_jenis: '$newSelectID', nilai: nilai}, newObj);
                                                    top.$('#result').load($saveTargetTable?key=$newSelectID', obj);
                                                }
                                            });

                                            top.$('#nilai_$newSelectID').on('keyup', function (e) {
                                                if (e.key === 'Enter' || e.keyCode === 13) {
                                                    $('#btnSave_$newSelectID').trigger('click')
                                                }
                                            });
                                        </script>";


                                        break;
                                    case "ewallet":

                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='js_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "<td>&nbsp;</td>";
                                        $elStr[$eName] .= "<td class='float-right'><span class='btn btn-sm btn-flat btn-block btn-success'><i class='fa fa-save'></i></span></td>";

                                        $elStr[$eName] .= "<script>
                                            top.$('#js_$newSelectID').on('keyup', function(){
                                                $(this).val( addCommas(removeCommas(this.value))  )
                                            });
                                        </script>";

                                        break;
                                    case "kartukredit":

                                        $amdlName = "MdlBankAccount_in";
                                        $labelSelect = "PILIH EDC";
                                        $this->load->model("Mdls/" . $amdlName);
                                        $bt = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
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

                                                        }
                                                    }
                                                }
                                            }
                                            $bt = makeFilter($aFilter, $_SESSION[$cCode]['main'], $bt);
                                        }

                                        $tmpBt = $bt->lookupAll()->result();

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>PILIH EDC</span></td>";

                                        $elStr[$eName] .= "<td colspan=''>";
                                        $elStr[$eName] .= "<select eName='$eName' id='edc_$newSelectID' class='form-control form-control-sm'>";
                                        $elStr[$eName] .= "<option value=''>- $labelSelect -</option>";
                                        if (sizeof($tmpBt) > 0) {
                                            foreach ($tmpBt as $row) {
                                                $labelSrc = $eSpec["paymentType"][$newSelectID]['labelSrc'];
                                                $ex = explode("/", $elementConfigs[$eName]["paymentType"][$newSelectID]['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {
                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['new_main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['new_main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                                }
                                            }
                                        }
                                        $elStr[$eName] .= "</select>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NO KARTU</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='no_$newSelectID' class='form-control form-control-sm text-bold text-right' onkeypress='return checkDigit(event)' value='' placeholder='nomor kartu'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NAMA KARTU</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='nama_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nama pemegang kartu'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>JENIS KARTU</span></td>";
                                        $elStr[$eName] .= "<td>
                                            <div class='input-group input-group-sm'>
                                                <div class='input-group-addon'>
                                                    <i class='fa fa-credit-card'></i>
                                                </div>
                                                <input type='text' eName='$eName' id='jenis_$newSelectID' class='form-control text-bold text-right' value='' placeholder='silahkan mengisi nomor kartu Anda' disabled>
                                            </div>
                                        </td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>MASA BERLAKU</span></td>";
                                        $elStr[$eName] .= "<td>
                                            <div class='input-group input-group-sm'>
                                                <div class='col-md-4 no-padding'>
                                                    <input type='text' eName='$eName' id='thn_$newSelectID' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='TAHUN'>
                                                </div>
                                                <div class='col-md-1 no-padding'><span class='form-control form-control-sm form-control-borderless text-bold text-center'>/</span></div>
                                                <div class='col-md-3 no-padding'>
                                                    <input type='text' eName='$eName' id='bln_$newSelectID' onkeypress='return checkBulan(event)' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='BULAN'>
                                                </div>
                                                <div class='col-md-4 no-padding'>
                                                    &nbsp;
                                                </div>
                                            </div>
                                        </td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>APPR-CODE</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='appr_$newSelectID' class='form-control form-control-sm text-bold text-center' value='' placeholder='APPR-CODE'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NILAI</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='nilai_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td>&nbsp;</td>";
                                        $elStr[$eName] .= "<td class='float-right'>";
                                        $elStr[$eName] .= "<span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-blocsk btn-success'><i class='fa fa-save'></i> save</span>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<script>
                                            top.$('#nilai_$newSelectID').on('keyup', function(){
                                                $(this).val( addCommas(removeCommas(this.value))  )
                                            });
                                            top.$('#btnSave_$newSelectID').on('click', function(){
                                                var minimalValue = 10000;
                                                var selector = $('#edc_$newSelectID').val();
                                                var input    = $('#nilai_$newSelectID').val();
                                                var nomor    = $('#no_$newSelectID').val();
                                                var nama     = $('#nama_$newSelectID').val();
                                                var tahun    = $('#thn_$newSelectID').val();
                                                var bulan    = $('#bln_$newSelectID').val();
                                                var jenis    = $('#jenis_$newSelectID').val();
                                                var apprcode = $('#appr_$newSelectID').val();

                                                if( removeCommas(input)*1 > minimalValue && selector*1 > 0 && jenis !='' && apprcode !='' && tahun != '' && bulan != '' && nama != '' && nomor!='' ){
                                                    var nilai = removeCommas( $('#nilai_$newSelectID').val() );
                                                    var childData = $('#edc_$newSelectID option:selected').attr('child');
                                                    var arrInput = JSON.parse(atob(childData))
                                                    var newObj = {};
                                                    jQuery.each(arrInput, function(a,b){
                                                        newObj[a] = b
                                                    })

                                                    newObj['nomor'] = nomor;
                                                    newObj['cst_nama'] = nama;
                                                    newObj['tahun'] = tahun;
                                                    newObj['bulan'] = bulan;
                                                    newObj['jenis'] = jenis;
                                                    newObj['apprcode'] = apprcode;

                                                    var obj = Object.assign({payment_jenis: '$newSelectID', nilai: nilai}, newObj);

                                                    console.log(obj);
                                                    top.$('#result').load($saveTargetTable?key=$newSelectID', obj);
                                                }
                                                else{

                                                    var textError = ''

                                                    if(selector==''){
                                                        textError += '<div>!! EDC belum ditentukan !!</div>'
                                                    }

                                                    if(input == ''){
                                                        textError += '<div>!! NILAI belum di isi !!</div>'
                                                    }
                                                    else if( removeCommas(input)*1 < minimalValue ){
                                                        textError += '<div>!! NILAI yang dimasukan salah (dibawah minimal)!!</div>'
                                                    }

                                                    if(nomor == ''){
                                                        textError += '<div>!! NOMOR Kartu belum diisi !!</div>'
                                                    }
                                                    else if( jenis == '' ){
                                                        textError += '<div>!! JENIS KARTU tidak dikenal atau nomor salah !!</div>'
                                                    }

                                                    if(tahun == ''){
                                                        textError += '<div>!! TAHUN belum diisi !!</div>'
                                                    }
                                                    if(bulan == ''){
                                                        textError += '<div>!! BULAN belum diisi !!</div>'
                                                    }

                                                    if(apprcode == ''){
                                                        textError += '<div>!! APPR-CODE belum diisi !!</div>'
                                                    }
                                                    if(nama == ''){
                                                        textError += '<div>!! NAMA PEMEGANG belum diisi !!</div>'
                                                    }
                                                    swal('mohon cek kembali', textError, 'warning');
                                                }
                                            });

                                            top.$('#no_$newSelectID').on('keyup', function(){
                                                if( creditCardType(this.value) != undefined ){
                                                    var jenis = creditCardType(this.value);
                                                    $('#jenis_$newSelectID').val(jenis);
                                                    $(this).css('color', 'green')
                                                }
                                                else{
                                                    console.log('nomor kartu salah / invalid');
                                                    $('#jenis_$newSelectID').val('');
                                                    $(this).css('color', 'red')
                                                }
                                                $(this).val(cc_format(this.value));
                                            });


                                            top.$('#nilai_$newSelectID').on('keyup', function (e) {
                                                if (e.key === 'Enter' || e.keyCode === 13) {
                                                    $('#btnSave_$newSelectID').trigger('click')
                                                }
                                            });

                                        </script>";

                                        break;
                                    case "kartudebit":

                                        $amdlName = "MdlBankAccount_in";
                                        $labelSelect = "PILIH EDC";
                                        $this->load->model("Mdls/" . $amdlName);
                                        $bt = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
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

                                                        }
                                                    }
                                                }
                                            }
                                            $bt = makeFilter($aFilter, $_SESSION[$cCode]['main'], $bt);
                                        }

                                        $tmpBt = $bt->lookupAll()->result();

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>PILIH EDC</span></td>";

                                        $elStr[$eName] .= "<td colspan=''>";
                                        $elStr[$eName] .= "<select eName='$eName' id='edc_$newSelectID' class='form-control form-control-sm'>";
                                        $elStr[$eName] .= "<option value=''>- $labelSelect -</option>";
                                        if (sizeof($tmpBt) > 0) {
                                            foreach ($tmpBt as $row) {
                                                $labelSrc = $eSpec["paymentType"][$newSelectID]['labelSrc'];
                                                $ex = explode("/", $elementConfigs[$eName]["paymentType"][$newSelectID]['labelSrc']);
                                                if (sizeof($ex) > 1) {
                                                    $labelValue = "";
                                                    foreach ($ex as $col) {
                                                        $labelValue .= $row->$col . " / ";
                                                    }
                                                    $labelValue = rtrim($labelValue, " / ");
                                                    $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                                    $selected = isset($_SESSION[$cCode]['new_main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                                }
                                                else {
                                                    $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                                    $selected = isset($_SESSION[$cCode]['new_main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                                    $elStr[$eName] .= "<option child='" . base64_encode(json_encode($row)) . "' value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                                }
                                            }
                                        }
                                        $elStr[$eName] .= "</select>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NO KARTU</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='no_$newSelectID' class='form-control form-control-sm text-bold text-right' onkeypress='return checkDigit(event)' value='' placeholder='nomor kartu'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NAMA KARTU</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='nama_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nama pemegang kartu'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>JENIS KARTU</span></td>";
                                        $elStr[$eName] .= "<td>
                                            <div class='input-group input-group-sm'>
                                                <div class='input-group-addon'>
                                                    <i class='fa fa-credit-card'></i>
                                                </div>
                                                <input type='text' eName='$eName' id='jenis_$newSelectID' class='form-control text-bold text-right' value='' placeholder='silahkan mengisi nomor kartu Anda' disabled>
                                            </div>
                                        </td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>MASA BERLAKU</span></td>";
                                        $elStr[$eName] .= "<td>
                                            <div class='input-group input-group-sm'>
                                                <div class='col-md-4 no-padding'>
                                                    <input type='text' eName='$eName' id='thn_$newSelectID' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='TAHUN'>
                                                </div>
                                                <div class='col-md-1 no-padding'><span class='form-control form-control-sm form-control-borderless text-bold text-center'>/</span></div>
                                                <div class='col-md-3 no-padding'>
                                                    <input type='text' eName='$eName' id='bln_$newSelectID' onkeypress='return checkBulan(event)' class='form-control form-control-sm form-control-borderless text-bold text-center' value='' placeholder='BULAN'>
                                                </div>
                                                <div class='col-md-4 no-padding'>
                                                    &nbsp;
                                                </div>
                                            </div>
                                        </td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>APPR-CODE</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='appr_$newSelectID' class='form-control form-control-sm text-bold text-center' value='' placeholder='APPR-CODE'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td><span class='form-control form-control-sm form-control-borderless text-bold text-left'>NILAI</span></td>";
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='nilai_$newSelectID' class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<tr style=''>";
                                        $elStr[$eName] .= "<td>&nbsp;</td>";
                                        $elStr[$eName] .= "<td class='float-right'>";
                                        $elStr[$eName] .= "<span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-blocsk btn-success'><i class='fa fa-save'></i> save</span>";
                                        $elStr[$eName] .= "</td>";
                                        $elStr[$eName] .= "</tr>";

                                        $elStr[$eName] .= "<script>
                                            top.$('#nilai_$newSelectID').on('keyup', function(){
                                                $(this).val( addCommas(removeCommas(this.value))  )
                                            });
                                            top.$('#btnSave_$newSelectID').on('click', function(){
                                                var minimalValue = 10000;
                                                var selector = $('#edc_$newSelectID').val();
                                                var input    = $('#nilai_$newSelectID').val();
                                                var nomor    = $('#no_$newSelectID').val();
                                                var nama     = $('#nama_$newSelectID').val();
                                                var tahun    = $('#thn_$newSelectID').val();
                                                var bulan    = $('#bln_$newSelectID').val();
                                                var jenis    = $('#jenis_$newSelectID').val();
                                                var apprcode = $('#appr_$newSelectID').val();

                                                if( removeCommas(input)*1 > minimalValue && selector*1 > 0 && jenis !='' && apprcode !='' && tahun != '' && bulan != '' && nama != '' && nomor!='' ){
                                                    var nilai = removeCommas( $('#nilai_$newSelectID').val() );
                                                    var childData = $('#edc_$newSelectID option:selected').attr('child');
                                                    var arrInput = JSON.parse(atob(childData))
                                                    var newObj = {};
                                                    jQuery.each(arrInput, function(a,b){
                                                        newObj[a] = b
                                                    })

                                                    newObj['nomor'] = nomor;
                                                    newObj['cst_nama'] = nama;
                                                    newObj['tahun'] = tahun;
                                                    newObj['bulan'] = bulan;
                                                    newObj['jenis'] = jenis;
                                                    newObj['apprcode'] = apprcode;

                                                    var obj = Object.assign({payment_jenis: '$newSelectID', nilai: nilai}, newObj);

                                                    console.log(obj);
                                                    top.$('#result').load($saveTargetTable?key=$newSelectID', obj);
                                                }
                                                else{

                                                    var textError = ''

                                                    if(selector==''){
                                                        textError += '<div>!! EDC belum ditentukan !!</div>'
                                                    }

                                                    if(input == ''){
                                                        textError += '<div>!! NILAI belum di isi !!</div>'
                                                    }
                                                    else if( removeCommas(input)*1 < minimalValue ){
                                                        textError += '<div>!! NILAI yang dimasukan salah (dibawah minimal)!!</div>'
                                                    }

                                                    if(nomor == ''){
                                                        textError += '<div>!! NOMOR Kartu belum diisi !!</div>'
                                                    }
                                                    else if( jenis == '' ){
                                                        textError += '<div>!! JENIS KARTU tidak dikenal atau nomor salah !!</div>'
                                                    }

                                                    if(tahun == ''){
                                                        textError += '<div>!! TAHUN belum diisi !!</div>'
                                                    }
                                                    if(bulan == ''){
                                                        textError += '<div>!! BULAN belum diisi !!</div>'
                                                    }

                                                    if(apprcode == ''){
                                                        textError += '<div>!! APPR-CODE belum diisi !!</div>'
                                                    }
                                                    if(nama == ''){
                                                        textError += '<div>!! NAMA PEMEGANG belum diisi !!</div>'
                                                    }
                                                    swal('mohon cek kembali', textError, 'warning');
                                                }
                                            });

                                            top.$('#no_$newSelectID').on('keyup', function(){
                                                if( creditCardType(this.value) != undefined ){
                                                    var jenis = creditCardType(this.value);
                                                    $('#jenis_$newSelectID').val(jenis);
                                                    $(this).css('color', 'green')
                                                }
                                                else{
                                                    console.log('nomor kartu salah / invalid');
                                                    $('#jenis_$newSelectID').val('');
                                                    $(this).css('color', 'red')
                                                }
                                                $(this).val(cc_format(this.value));
                                            });

                                            top.$('#nilai_$newSelectID').on('keyup', function (e) {
                                                if (e.key === 'Enter' || e.keyCode === 13) {
                                                    $('#btnSave_$newSelectID').trigger('click')
                                                }
                                            });

                                        </script>";

                                        break;
                                    default:
                                        $elStr[$eName] .= "<td><input type='text' eName='$eName' id='js_$newSelectID' disabled class='form-control form-control-sm text-bold text-right' value='' placeholder='nilai'></td>";
                                        $elStr[$eName] .= "<td>&nbsp;</td>";
                                        $elStr[$eName] .= "<td class='float-right'><span id='btnSave_$newSelectID' class='btn btn-sm btn-flat btn-block btn-success' disabled ><i class='fa fa-save'></i></span></td>";
                                        break;
                                }

                                $elStr[$eName] .= "</table>";

                                $elStr[$eName] .= "</div>";
                                $elStr[$eName] .= "</div>"; //row1

                                //notif bawah
                                switch ($newSelectID) {
                                    case "tunai":
                                        $elStr[$eName] .= "";
                                        break;
                                    case "banktransfer":
                                        $elStr[$eName] .= "<div style='padding: 10px 10px 10px 10px;' class='alert row bg-yellow no-paddings'>";
                                        $elStr[$eName] .= "<div class='text-bold text-red text-left'>Catatan: </div>";
                                        $elStr[$eName] .= "<ol>";
                                        $elStr[$eName] .= "<li class='text-left'><i>Harap memasukan informasi sesuai dengan bukti pembayaran konsumen.</i></li>";
                                        $elStr[$eName] .= "<li class='text-left'><i>Jika ada kelebihan transfer, harap memilih untuk dijadikan saldo konsumen atau menjadi pendapatan lain-lain.</i></li>";
                                        $elStr[$eName] .= "<li class='text-left'><i>Pengembalian kelebihan transfer bisa dilakukan setelah tahapan ini.</i></li>";
                                        $elStr[$eName] .= "</ol>";
                                        $elStr[$eName] .= "</div>";
                                        break;
                                    case "qris":
                                        $elStr[$eName] .= "";
                                        break;
                                    case "ewallet":
                                        $elStr[$eName] .= "";
                                        break;
                                    case "kartukredit":
                                        $elStr[$eName] .= "<div style='padding: 10px 10px 10px 10px;' class='alert row bg-yellow no-paddings'>";
                                        $elStr[$eName] .= "<div class='text-bold text-red text-left'>Catatan: </div>";
                                        $elStr[$eName] .= "<ol>";
                                        $elStr[$eName] .= "<li class='text-left'><i>Harap memasukan seluruh informasi yang dibutuhkan.</i></li>";
                                        $elStr[$eName] .= "</ol>";
                                        $elStr[$eName] .= "</div>";
                                        break;
                                    case "kartudebit":
                                        $elStr[$eName] .= "<div style='padding: 10px 10px 10px 10px;' class='alert row bg-yellow no-paddingc'>";
                                        $elStr[$eName] .= "<div class='text-bold text-red text-left'>Catatan: </div>";
                                        $elStr[$eName] .= "<ol>";
                                        $elStr[$eName] .= "<li class='text-left'><i>Harap memasukan seluruh informasi yang dibutuhkan.</i></li>";
                                        $elStr[$eName] .= "</ol>";
                                        $elStr[$eName] .= "</div>";
                                        break;
                                    default:
                                        $elStr[$eName] .= "";
                                        break;
                                }

                                $elStr[$eName] .= "</div>"; //container
                                // END OF MULTI PAYMENT

                                break;
                        }

                        $elStr[$eName] .= "</div class='box-header'>";

                        if ($eSpec['inputType'] != "table") {

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
                                        top.BootstrapDialog.show({
                                            title:'New " . $eSpec['label'] . "',
                                            message: top.$('<div></div>').load('" . $editLink . "'),
                                            draggable:true,
                                            size:BootstrapDialog.SIZE_WIDE,
                                            closable:true,
                                            type:top.BootstrapDialog.TYPE_SUCCESS,
                                        });";
                                        $editStr = "<a " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . " href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                    }
                                }
                            }

                            $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                            $elStr[$eName] .= "</div id='el$amdlName'>";
                        }

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
//            }
//        }
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


        $this->load->helper("he_value_builder");
        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());


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
            "addBackground" => $addRowBackground,
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
            "default_description" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            "columnRecorderTarget" => MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr,
            "viewDescriptionNote" => isset($this->configUi[$this->jenisTr]['viewDescriptionNote']) ? $this->configUi[$this->jenisTr]['viewDescriptionNote'] : false,
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
        echo "top.getData('" . MODUL_PATH . "_shoppingCart/viewCart/$jenisTr/?kAhHJASAGHSGfags=kak','shopping_cart')";
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
        $keyMulti = $_GET['multi'];

        heFetchElement_modul($this->jenisTr, $elName, $mdlName, $key, $this->configUiJenis, $_GET);

        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        //        mati_disini("kakakaak");
        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function fetchElementTable()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $mdlName = $this->uri->segment(6);
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];
        $stepNum = $_SESSION[$cCode]['main']['stepNumber'] > 0 ? $_SESSION[$cCode]['main']['stepNumber'] : 1;

        $key = $_GET['key'];

        heFetchElement_modul($this->jenisTr, $elName, $mdlName, $key, $this->configUiJenis);


        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        //        mati_disini("kakakaak");
        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function saveElementTable()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $mdlName = $this->uri->segment(6);
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $key = $_GET['key'];
        $time = strtotime(date("Y-m-d H:i:s"));
        $_SESSION[$cCode]["payment"][$time] = $_POST;

//        switch($key){
//            case "tunai":
//                $time = strtotime(date("Y-m-d H:i:s"));
//                $_SESSION[$cCode]["payment"][$time] = $_POST;
//                break;
//            case "banktransfer":
//                $time = strtotime(date("Y-m-d H:i:s"));
//                $_SESSION[$cCode]["payment"][$time] = $_POST;
//                break;
//            case "kartudebit":
//                $time = strtotime(date("Y-m-d H:i:s"));
//                $_SESSION[$cCode]["payment"][$time] = $_POST;
//                break;
//            case "kartukredit":
//                $time = strtotime(date("Y-m-d H:i:s"));
//                $_SESSION[$cCode]["payment"][$time] = $_POST;
//                break;
//            case "ewallet":
//                break;
//            case "qris":
//                break;
//        }

        //main element di reset dlu
        unset($_SESSION[$cCode]['main_elements']["multiPayment"]);
        //heFetchElement_modul($this->jenisTr, $elName, $mdlName, $key, $this->configUiJenis);

        $id = 0;
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
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }

    public function deleteElementTable()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $elName = $this->uri->segment(5);
        $mdlName = $this->uri->segment(6);
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $key = $_GET['key'];

        $idpayment = $_POST["id"];

        unset($_SESSION[$cCode]["payment"][$idpayment]);

        $id = 0;
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
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
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

        heRecordElement_modul($this->jenisTr, $elName, $val, $configUiJenis);
        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
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

        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
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
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            "jenisTrName" => $this->jenisTrName,
            "stepNumber" => 1,
            "stepCode" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            "dtime" => date("Y-m-d H:i:s"),
            "fulldate" => date("Y-m-d"),

            "sellerID" => $this->session->login['id'],
            "sellerName" => $this->session->login['nama'],
        );
        foreach ($initMaster as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
            //            $_SESSION[$cCode]['main'][$key] = $val;
        }

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

        echo "<script>";
        echo "top.fillBoxes();";
        echo "</script>";

        echo topReload(100);

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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
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
        //-----------------------------------
        if (isset($_SESSION[$cCode]['main']['selectedType_konsumen']) && ($_SESSION[$cCode]['main']['selectedType_konsumen'] == "bendahara_negara") && ($rowName == "nilai_entry")) {
//            $max_nilai = $_SESSION[$cCode]['main']['nilai_round'] - $_SESSION[$cCode]['main']['ppn_nilai_dibayar'] - $_SESSION[$cCode]['main']['pph22_nilai'] - $_SESSION[$cCode]['main']['nilai_biaya'];
            $balance_sisa = $_SESSION[$cCode]['main']['new_sisa_before_entry'];
            if (($val < $balance_sisa) || ($val > $balance_sisa)) {
                $msg = "Tipe konsumen yang dipilih Bendahara Negara. Silahkan mengisikan cash receive sesuai Balance Invoice sebesar $balance_sisa.";
                die(lgShowAlertBiru($msg));
            }
        }
        //-----------------------------------
        if ($rowName == "point_konsumen_qtt") {
            if ($val > $_SESSION[$cCode]['main']['pointKonsumen__saldo']) {
                $saldo = $_SESSION[$cCode]['main']['pointKonsumen__saldo'];
                $msg = "Saldo Point " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                $msg .= " Saldo saat ini $saldo, anda mengisi sebesar $val. Silahkan dikoreksi.";
                die(lgShowAlertBiru($msg));
            }
        }
        if ($rowName == "credit_amount") {
            if ($val > $_SESSION[$cCode]['main']['creditAmount__sisa']) {
                $saldo = $_SESSION[$cCode]['main']['creditAmount__sisa'];
                $msg = "Saldo Credit Note " . $_SESSION[$cCode]['main']['pihakName'] . " tidak cukup.";
                $msg .= " Saldo saat ini $saldo, anda mengisi sebesar $val. Silahkan dikoreksi.";
                die(lgShowAlertBiru($msg));
            }
        }
        //-----------------------------------
        if ($rowName == "nilai_dijadikan_credit_note") {
//            mati_disini($_GET['state']);
            if ($_GET['state'] == "true") {
//                $_SESSION[$cCode]["main"][$_GET['cek']] = $_SESSION[$cCode]["main"]["customerDetails__parent"];
                $_SESSION[$cCode]["main"][$_GET['cek']] = 1;
            }
            else {
                $val = 0;
                $_SESSION[$cCode]["main"][$_GET['cek']] = 0;
            }
        }
        //-----------------------------------

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

        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
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


        heFetchItemsElement_modul($this->jenisTr, $elName, $mdlName, $key, $helpName, $configUiJenis);
        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes&populate=1');";
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

        heRecordElement_modul($this->jenisTr, $elName, $val, $configUiJenis);
        $id = 0;
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
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
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        echo "<script>";
        //        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?epreketek=yes&populate=1');";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
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
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $id = 0;
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "?epreketek=yes');";
        echo "</script>";
    }

    public function viewLepasRelasi_old()
    {
        $cCode = $this->cCode;
        $curSess = $_SESSION[$cCode];
        $id = $curSess['main']["pihakID"];
        $name = $curSess['main']["pihakName"];

        $modulTarget = "kas";
        $subModulTarget = "4656";

        /*
         * Pihak Items nota nya
         * MdlUangMukaReference
         */
//        $this->load->model("Mdls/MdlUangMukaReference");
        $this->load->model("Mdls/MdlCustomerUangMuka");
        $td = new MdlCustomerUangMuka();
        $td->addFilter("extern_id=" . $id);
//        $td->addFilter("rekening=1010050010");
//        $td->db->order_by("debet desc");
//        $td->addFilter("debet>0");
        $tmps = $td->lookupAll()->result();

        showLast_query("biru");
        $selectedUm = array();
        $targetSoUm = array();
        if (!empty($curSess['items'])) {
            foreach ($curSess['items'] as $iID => $iDat) {
                $selectedUm[$iDat['refID']] = $iDat['src_id'];
                $targetSoUm[$iDat['nomer_top']] = $iID;
            }
        }

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("extern_id=$id");
        $tr->addFilter("extern2_id>0");

        if (!empty($selectedUm)) {
            $notIn = implode(",", $selectedUm);
            $this->db->where("extern2_id NOT IN($notIn)");
        }

        $tr->addFilter("sisa>0");
        $tmpsUMSrc = $tr->lookUpAllPaymentSrc()->result();

        arrPrint($selectedUm);
        showLast_query("merah");

        $totalDebet = 0;
        foreach ($tmpsUMSrc as $row) {
            $totalDebet += $row->sisa;
        }

        $items = array();
        foreach ($tmpsUMSrc as $row) {
            $TmpItems['id'] = $row->id;
            $TmpItems['name'] = $row->extern2_nama;
            $TmpItems['nilai'] = $row->sisa;
            $items[] = $TmpItems;
        }

        $customers = [
            $id => [
                'name' => $name,
                'items' => $items
            ]
        ];

        $cusJson = json_encode($customers);
        $targetSoJson = json_encode($targetSoUm);

        $simple_view = "";
        $simple_view .= "
            <div class='content-wrapper no-margin'>
                <section class='content'>
                    <div class='row'>
                        <div class='col-md-8 col-md-offset-2'>
                            <div class='form-container'>

                                <div class='form-header'>
                                    <h3 class='box-title'><i class='fa fa-money'></i> Pilih Uang Muka yang akan di pindah relasi.</h3>
                                    <p class='text-muted'>-----------------------------------------------</p>
                                </div>

                                <form id='customerForm'>

                                    <div class='form-group'>
                                        <label class='required' for='customerSelect'>Konsumen</label>
                                        <select class='form-control select2' id='customerSelect' style='width: 100%;'>
                                            <option value=''>-- Pilih Konsumen --</option>
                                        </select>
                                    </div>

                                    <div class='form-group'>
                                        <label class='required' for='totalDebet'>Total UM (relasi) yang tersedia</label>
                                        <select disabled class='form-control select2' id='totalDebet' style='width: 100%;'>
                                            <option value=''>" . number_format($totalDebet) . "</option>
                                        </select>
                                    </div>

                                    <div class='form-group'>
                                        <label class='required' for='itemSelect'>Pilih Nomer SO yang akan dipindah</label>
                                        <select class='form-control select2' id='itemSelect' style='width: 100%;' disabled>
                                            <option value=''>-- Pilih --</option>
                                        </select>
                                    </div>

                                    <div class='form-group radioJenisAction pilihJenisAction hiddens'>
                                        <label class='required d-block'>Pilih Jenis Action</label>
                                        <div id='itemTargetRadios' class='inline-radio'>
                                            <label style='margin-right: 10px;'><input type='radio' name='itemTarget' value='removeRelasi'> Remove Relasi (jadi titipan konsumen)</label>
                                            <label><input type='radio' name='itemTarget' value='pindahRelas'> Pindah Relasi ke SO Lain</label>
                                        </div>
                                    </div>

                                    <div class='form-group radioTargetAction pilihTargetAction hidden'>
                                        <label class='required' for='itemTargetSelect'>Pilih SO Target (hanya SO dipilih pada penerimaan yg akan keluar)</label>
                                        <select class='form-control select2' id='itemTargetSelect' style='width: 100%;' disabled>
                                            <option value=''>-- Pilih --</option>
                                        </select>
                                    </div>

                                    <div class='info-box' id='selectionInfo' style='display: none;'>
                                        <h4><i class='fa fa-info-circle'></i> Pilihan Anda</h4>
                                        <p><strong>Konsumen:</strong> <span id='selectedCustomer'>-</span></p>
                                        <p><strong>UM Dari Nomer:</strong> <span id='selectedNomer'>-</span></p>
                                        <p><strong>Nilai UM:</strong> <span id='selectedNilai'>-</span></p>
                                        <p class='showPPN hidden'><strong>Nilai PPN:</strong> <span id='selectedPPNNilai'>-</span> (<span id='selectedPPNNilaiPersen'>-</span>)</p>
                                        <p class='showPPN hidden'><strong>Nilai UM+PPN:</strong> <span id='selectedUMPPNNilai'>-</span></p>
                                        <p class='hidden'><strong>Nilai UM Baru: <r>(abaikan jika tidak perlu perubahan)</r></strong> <span id='selectedNilaiNew'>-</span></p>
                                        <p><strong>Catatan Perubahan UM:</strong> <span id='selectedNilaiNotes'>-</span></p>
                                    </div>

                                    <div style='margin-top: 60px;' class='form-footer'>
                                        <button type='reset' class='btn btn-default'>
                                            <i class='fa fa-refresh'></i> Clear Pilihan
                                        </button>
                                        <button type='submit' class='btn btn-primary'>
                                            <i class='fa fa-check'></i> Pindah Relasi SO
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
    const trgetSoJson = $targetSoJson;

    console.log('customers: ', customers);
    console.log('trgetSoJson: ', trgetSoJson);
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
            
            $('#itemTargetSelect').prop('disabled', false);
        }
        else {
            $('#itemTargetSelect').prop('disabled', true);
            $('#selectionInfo').hide();
        }
    });

    $('#itemTargetSelect').on('change', function() {
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
            });

            $('#formItemNotes').val('transaksi ini melepas relasi SO Lain ('+selectedItem.name+') menjadi titipan non-relasi').on('click', function(){ this.select() });
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
        const modul = \"$modulTarget\";
        const subModul = \"$subModulTarget\";
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
        } 
        catch (error) {
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
    
    function closeTopBootstrapModal() {
      const close = document.querySelectorAll('.close');
      if (close.length > 0) {
        const topClose = close[close.length - 1];
            $(topClose).click();
      }
    }
                
</script>

        ";

        echo $simple_view;

//        echo json_encode($curSess);
    }

    public function recordColumn()
    {

        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;


        $_SESSION[$cCode]['main'][$colName] = $val;
        if ($valValue != null && $valCol != null) {
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

    public function viewLepasRelasi()
    {
        $cCode = $this->cCode;
        $curSess = $_SESSION[$cCode];
        $id = $curSess['main']["pihakID"];
        $name = $curSess['main']["pihakName"];

        $modulTarget = "kas";
        $subModulTarget = "4656";

        // ==== ASLI, TIDAK DIUBAH ====
        $this->load->model("Mdls/MdlCustomerUangMuka");
        $td = new MdlCustomerUangMuka();
        $td->addFilter("extern_id=" . $id);
        $tmps = $td->lookupAll()->result();

        showLast_query("biru");
        $selectedUm = array();
        $targetSoUm = array();
        if (!empty($curSess['items'])) {
            foreach ($curSess['items'] as $iID => $iDat) {
                $selectedUm[$iDat['refID']] = $iDat['src_id'];
//                $targetSoUm[$iDat['nomer_top']] = $iDat['src_id'];
                $targetSoUm[$iDat['reference_so_nomer']] = $iDat['reference_so_id'];
            }
        }

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("extern_id=$id");
        $tr->addFilter("extern2_id>0");

        if (!empty($selectedUm)) {
            $notIn = implode(",", $selectedUm);
            $this->db->where("extern2_id NOT IN($notIn)");
        }

        $tr->addFilter("sisa>0");
        $tmpsUMSrc = $tr->lookUpAllPaymentSrc()->result();

        arrPrint($selectedUm);
        showLast_query("merah");

        $totalDebet = 0;
        foreach ($tmpsUMSrc as $row) {
            $totalDebet += $row->sisa;
        }

        $items = array();
        foreach ($tmpsUMSrc as $row) {
            $TmpItems['id'] = $row->id;
            $TmpItems['name'] = $row->extern2_nama;
            $TmpItems['nilai'] = $row->sisa;
            $items[] = $TmpItems;
        }

        $customers = [
            $id => [
                'name' => $name,
                'items' => $items
            ]
        ];

        $cusJson = json_encode($customers);
        $targetSoJson = json_encode($targetSoUm);

        // ==== BAGIAN VIEW / FRONTEND ====
        $simple_view = "
            <div class='content-wrapper no-margin'>
                <section class='content'>
                    <div class='row'>
                        <div class='col-md-8 col-md-offset-2'>
                            <div class='form-container'>
                                <div class='form-header'>
                                    <h3 class='box-title'><i class='fa fa-money'></i> Pindah / Lepas Relasi Uang Muka</h3>
                                    <p class='text-muted'>-----------------------------------------------</p>
                                </div>
                                <form id='customerForm'>
                                    <!-- Customer otomatis -->
                                    <div class='form-group'>
                                        <label>Konsumen</label>
                                        <input type='text' class='form-control' id='customerName' disabled>
                                    </div>
                                    <!-- Pilih SO asal -->
                                    <div class='form-group'>
                                        <label for='itemSelect'>Pilih Nomer SO yang akan dipindah</label>
                                        <select class='form-control select2' id='itemSelect' style='width:100%;'>
                                            <option value=''>-- Pilih --</option>
                                        </select>
                                    </div>
                                    <!-- Pilih jenis aksi -->
                                    <div class='form-group pilihJenisAction hidden'>
                                        <label>Pilih Jenis Aksi</label>
                                        <div id='itemTargetRadios' class='inline-radio'>
                                            <!-- <label style='margin-right:10px;'>
                                                <input type='radio' name='itemTarget' value='removeRelasi'> Remove Relasi (jadi titipan konsumen)
                                            </label> -->
                                            <label>
                                                <input type='radio' name='itemTarget' value='pindahRelasi'> Pindah Relasi ke SO lain
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Pilih target SO -->
                                    <div class='form-group pilihTargetAction hidden'>
                                        <label for='itemTargetSelect'>Pilih SO Target <r>(hanya SO dipilih pada penerimaan yg akan keluar)</r></label>
                                        <select class='form-control select2' id='itemTargetSelect' style='width:100%;'>
                                            <option value=''>-- Pilih SO Target --</option>
                                        </select>
                                    </div>
                                    <!-- Ringkasan akhir -->
                                    <div id='summaryBox' class='info-box hidden'>
                                        <h4><i class='fa fa-info-circle'></i> Ringkasan Pilihan</h4>
                                        <p><strong>Konsumen:</strong> <span id='sumCustomer'>-</span></p>
                                        <p><strong>SO Asal:</strong> <span id='sumSOAsal'>-</span></p>
                                        <p><strong>Nilai UM:</strong> <span id='sumNilai'>-</span></p>
                                        <p><strong>Aksi:</strong> <span id='sumAction'>-</span></p>
                                        <div class='sumRemove hidden'>
                                            <p><strong>NOTES :</strong> <textarea id=\"formItemNotes\" rows=\"3\" class=\"form-control\" placeholder=\"Catatan otomatis akan muncul di sini...\"></textarea></p>
                                        </div>
                                        <div class='sumPindah hidden'>
                                            <p><strong>SO Tujuan:</strong> <span id='sumTarget'>-</span></p>
                                            <p><strong>NOTES :</strong> <textarea id=\"formItemNotes\" rows=\"3\" class=\"form-control\" placeholder=\"Catatan otomatis akan muncul di sini...\"></textarea></p>
                                        </div>
                                    </div>
                                    <div style='margin-top:40px;' class='form-footer'>
                                        <button type='reset' class='btn btn-default'><i class='fa fa-refresh'></i> Reset</button>
                                        <button type='submit' class='btn btn-primary'><i class='fa fa-check'></i> Proses</button>
                                        <button type='button' onclick=\"closeTopBootstrapModal()\" class='btn btn-default pull-right'><i class='fa fa-times'></i> Tutup</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
<script>
const customers = $cusJson;
const targetSO = $targetSoJson;

$(document).ready(function(){
    // auto select single customer
    const cusId = Object.keys(customers)[0];
    const cus = customers[cusId];
    $('#customerName').val(cus.name);
    // isi SO asal
    const select = $('#itemSelect');
    cus.items.forEach(it => {
        console.log(it.nilai);
        select.append(new Option(it.name + ' - ' + formatRupiah(it.nilai), it.id));
    });
});

// Step 1: pilih SO
$('#itemSelect').on('change', function(){
    const val = $(this).val();
    if(val){
        $('.pilihJenisAction').removeClass('hidden');
        $('input[name=\"itemTarget\"]').prop('checked', false);
        $('.pilihTargetAction, #summaryBox').addClass('hidden');
        //selalu pilih pindah
        $('input[name=\"itemTarget\"][value=\"pindahRelasi\"]').prop('checked', true).trigger('change');
    } 
    else {
        $('.pilihJenisAction, .pilihTargetAction, #summaryBox').addClass('hidden');
    }
});

// Step 2: pilih jenis aksi
$('input[name=\"itemTarget\"]').on('change', function(){
    const act = $(this).val();
    $('.pilihTargetAction, #summaryBox').addClass('hidden');
    if(act === 'pindahRelasi'){
        const sel = $('#itemTargetSelect');
        sel.empty().append('<option value=\"\">-- Pilih SO Target --</option>');
        Object.keys(targetSO).forEach(so => sel.append(new Option(so, so)));
        $('.pilihTargetAction').removeClass('hidden');
    } 
    else {
        showSummary();
    }
});

// Step 3: pilih SO target
$('#itemTargetSelect').on('change', function(){
    if($(this).val()) showSummary();
    else $('#summaryBox').addClass('hidden');
});

// Step 4: tampilkan ringkasan
function showSummary() {
    const cusId = Object.keys(customers)[0];
    const cus = customers[cusId];
    const soAsal = $('#itemSelect option:selected').text();
    const act = $('input[name=\"itemTarget\"]:checked').val();
    const itemId = $('#itemSelect').val();
    const itemData = cus.items.find(i => i.id === itemId);
    const nilai = itemData ? itemData.nilai : 0;

    // console.log('cusId:', cusId);
    // console.log('cus:', cus);
    // console.log('soAsal:', soAsal);
    // console.log('act:', act);
    // console.log('itemId:', itemId);
    // console.log('itemData:', itemData);
    // console.log('nilai:', nilai);

    // update ringkasan dasar
    $('#sumCustomer').text(cus.name);
    $('#sumSOAsal').text(soAsal);
    $('#sumNilai').text(formatRupiah(nilai));
    $('#sumAction').text(act === 'removeRelasi' ? 'Remove Relasi (masuk UM Titipan)' : 'Pindah Relasi');

    // siapkan variabel tambahan
    let noteText = '';
    let nilaiText = formatRupiah(nilai);

    if (act === 'removeRelasi') {
        // tampilkan bagian remove
        $('.sumRemove').removeClass('hidden');
        $('.sumPindah').addClass('hidden');
        // isi catatan otomatis
        //noteText = 'Uang muka dari '+cus.name+' (SO '+soAsal+') akan dipindahkan ke rekening titipan konsumen.';
        noteText = '<i>SO '+soAsal+' akan dihapus relasinya dan dimasukkan ke rekening <b>Titipan Konsumen</b>.</i>';
        // update isi textarea formItemNotes
        $('.sumRemove #formItemNotes').val(noteText);
    } 
    else if (act === 'pindahRelasi') {
        const soTgt = $('#itemTargetSelect').val();
        const idTgt = targetSO[soTgt];
        // console.log('soTgt:', soTgt);
        // console.log('idTgt:', idTgt);
        // tampilkan bagian pindah
        $('.sumRemove').addClass('hidden');
        $('.sumPindah').removeClass('hidden');
        $('#sumTarget').text(soTgt+' (ID: '+idTgt+')');
        // isi catatan otomatis
        noteText = 'Uang muka dari SO '+ soAsal + ' akan dipindahkan ke SO '+soTgt+' sejumlah '+nilaiText+'.';
        // update isi textarea formItemNotes
        $('.sumPindah #formItemNotes').val(noteText);
    }
    // tampilkan box ringkasan
    $('#summaryBox').removeClass('hidden').css('padding', '10px');
}

$('#customerForm').on('reset', function(e){
    e.preventDefault(); // cegah reset bawaan biar bisa kontrol manual
    // reset select SO asal
    $('#itemSelect').val('').trigger('change');
    // reset radio action
    $('input[name=\"itemTarget\"]').prop('checked', false);
    // reset SO target
    $('#itemTargetSelect').val('').trigger('change');
    // sembunyikan semua step selanjutnya
    $('.pilihJenisAction').addClass('hidden');
    $('.pilihTargetAction').addClass('hidden');
    $('#summaryBox').addClass('hidden');
    // tampilkan ulang form ke kondisi awal
    $('.select2').select2(); // re-init select2 biar gak error tampilan
});

    function closeTopBootstrapModal() {
      const close = document.querySelectorAll('.close');
      if (close.length > 0) {
        const topClose = close[close.length - 1];
            $(topClose).click();
      }
    }
    
    async function doLepasRelasi() {
        const modul = \"$modulTarget\";
        const subModul = \"$subModulTarget\";
        
        const cusId = Object.keys(customers)[0];
        const cus = customers[cusId];
    
        const customerId = cusId;
        const itemId = $('#itemSelect').val();
        const itemNilai = $('#formItemNilai').val();
        const sumPindah_formItemNotes = $('.sumPindah #formItemNotes').val();
        const sumRemove_formItemNotes = $('.sumRemove #formItemNotes').val();
        const baseUrl = '" . base_url() . "';
        const debug = '&debuger=0';
        let targetValue = $('#itemTargetRadios input[name=\"itemTarget\"]:checked').val();
    
        // console.log('modul:', modul);
        // console.log('subModul:', subModul);
        // console.log('customerId:', customerId);
        // console.log('itemId:', itemId);
        // console.log('itemNilai:', itemNilai);
        // console.log('sumPindah_formItemNotes:', sumPindah_formItemNotes);
        // console.log('sumRemove_formItemNotes:', sumRemove_formItemNotes);
        // console.log('baseUrl:', baseUrl);
        // console.log('debug:', debug);
        // console.log('targetValue:', targetValue);

        //fungsi removeRelasi masih belum diaktifkan di modul kas
        //karena masih pertimbangan melepas PPN Masukan juga

        if( targetValue == 'removeRelasi'){
            
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
//                const saveResponse = await fetch(`\${baseUrl}\${modul}/Create/save/\${subModul}?1=1\${debug}`, {
//                    credentials: 'include'
//                });
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
            } 
            catch (error) {
                console.error(\"Terjadi kesalahan:\", error);
                // Tambahkan penanganan error di sini
            }
        
        }
        else if(targetValue == 'pindahRelasi'){

        const soTgt = $('#itemTargetSelect').val();
        const idTgt = targetSO[soTgt];

            try {
                //Reset shopping cart
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/reset/\${subModul}?1=1\${debug}`, {
                    credentials: 'include' // Bawa session cookie
                });

                //Pilih konsumen
                await fetch(`\${baseUrl}\${modul}/_processPihak/select/\${subModul}/MdlCustomer?id=\${customerId}\${debug}`, {
                    credentials: 'include'
                });

                //Pilih nota sumber / akan di pindah / remove
                await fetch(`\${baseUrl}\${modul}/_processSelectBiaya/selectReplaceUm/\${subModul}/MdlPaymentSource?selector&id=\${itemId}&minValue=1\${debug}`, {
                    credentials: 'include'
                });

                //viewCart dulu
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/viewCart/\${subModul}?selID=\${itemId}`, {
                    credentials: 'include'
                });

                //element: pilih mode 
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/fetchElement/\${subModul}/actionType2/MdlUangMukaOptionStaticType2/?key=reguler\${debug}`, {
                    credentials: 'include'
                });

                //viewCart dulu
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/viewCart/\${subModul}?selID=\${itemId}`, {
                    credentials: 'include'
                });

                // pilih nota target
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/fetchElement/\${subModul}/actionType2_referensi_so_reguler/MdlTransaksi2/?key=\${idTgt}\${debug}`, {
                    credentials: 'include'
                });

                //viewCart dulu
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/viewCart/\${subModul}?selID=\${itemId}`, {
                    credentials: 'include'
                });

                //Simpan catatan jika ada
                if (sumPindah_formItemNotes.trim() != '') {
                    await fetch(`\${baseUrl}\${modul}/Create/recordColumn/\${subModul}/description?val=\${encodeURIComponent(sumPindah_formItemNotes.trim())}\${debug}`, {
                        credentials: 'include'
                    });
                }

                //viewCart dulu
                await fetch(`\${baseUrl}\${modul}/_shoppingCart/viewCart/\${subModul}?selID=\${itemId}`, {
                    credentials: 'include'
                });

                //Validate data session
                await fetch(`\${baseUrl}\${modul}/Transaksi/validate/\${subModul}?1=1\${debug}`, {
                    credentials: 'include'
                });

                //Simpan transaksi
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

            } 
            catch (error) {
                console.error(\"Terjadi kesalahan:\", error);
                // Tambahkan penanganan error di sini
            }
        }
        
    }

    async function doLepasRelasi_ori() {
        const modul = \"$modulTarget\";
        const subModul = \"$subModulTarget\";
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
        } 
        catch (error) {
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
    
 

</script>
";

        echo $simple_view;
    }


}


