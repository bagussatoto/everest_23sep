<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 10:41 PM
 */
class _shoppingCart extends CI_Controller
{
    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
    }

    public function viewCart()
    {
        $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        $this->load->helper('he_angka');

        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "main" => array(),
                "items" => array(),
            );
        }

//        arrPrint(isset($_SESSION[$cCode]) ? $_SESSION[$cCode] : " - ");

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

//        arrPrint($_SESSION[$cCode]['mode']);
// cekLime("hitam");
        $inputLabels = array();

        $main = array();
        $items = array();
        $items2 = array();
        $items3 = array();
        $minValue = array();
        $itemLabels = array();
        $itemLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields'][$stepNumber] : array();
        $itemLabels2 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields2'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields2'][$stepNumber] : array();
        $itemsLabelReplacer = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartFieldsReplacer']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartFieldsReplacer'] : array();
        if (isset($_SESSION[$cCode]['main']['references']) || isset($_SESSION[$cCode]['main']['singleReference'])) {
            $itemLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFieldsExt'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFieldsExt'][$stepNumber] : $itemLabels;
        }

        $itemLabels3 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields3'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields3'][$stepNumber] : array();
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$stepNumber] : array();
        $itemNumLabels2 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields2'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields2'][$stepNumber] : array();
        $itemNumLabels3 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields3'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields3'][$stepNumber] : array();
        $editableFields = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber] : array();
        $editableFields2 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartEditableFields2'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartEditableFields2'][$stepNumber] : array();
        $editableFieldsCompare = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartEditableCompare'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartEditableCompare'][$stepNumber] : array();

        $shoppingCartMainEditableFields = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartMainEditableFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartMainEditableFields'][$stepNumber] : array();
        $arrHeaderElement = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartHeaderElement'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartHeaderElement'][$stepNumber] : array();

        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements'] : array();
        $relOptionConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions'] : array();
        $addRowsConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['additionalRows']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['additionalRows'] : array();
        $editHandlerMethod = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['editHandlerMethod']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['editHandlerMethod'] : "blabla";
        $editHandlerMethod2 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['editHandlerMethod2']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['editHandlerMethod2'] : "";

        $editMainHandlerMethod = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['editMainHandlerMethod']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['editMainHandlerMethod'] : NULL;
        $editMainHandlerMethod2 = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['editMainHandlerMethod2']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['editMainHandlerMethod2'] : NULL;


        $noteEnabled = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
        $noteType = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNoteType']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNoteType'] : "text";
        $imageEnable = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartImageEnabled']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartImageEnabled'] : false;
        $showScheme = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartShowScheme']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartShowScheme'] : false;
        $imageType = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartImageType']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartImageType'] : "blaa";

        $pairedItemEnabled = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItem']['enabled']) && $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItem']['enabled'] == true ? true : false;
        $pairedItem = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItem']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItem'] : array();
        $pairedItemTarget = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItem']['targetGateName']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItem']['targetGateName'] : "items2";

        $pairedItemField = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFieldsPairedItem'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFieldsPairedItem'][$stepNumber] : array();
        $pairedItemRecorder = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItemRecorder']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartPairedItemRecorder'] : "";


        $pairedMoq = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartMinFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartMinFields'][$stepNumber] : array();
        $avoidRemove = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAvoidRemove']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAvoidRemove'] : false;
        $avoidRemoveAll_items = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAvoidRemoveAll_items']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAvoidRemoveAll_items'] : false;

        $unionSelectors = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartUnionSelectors'][$stepNumber] : array();
        $keyUpEvents = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartKeyUpEvents'][$stepNumber] : array();
        $selectedPrices = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']['key_label']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']['key_label'] : array();
        $showItems = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['showItems']) && $this->config->item('heTransaksi_ui')[$this->jenisTr]['showItems'] == "false" ? "false" : "true";

        $fixedNote = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['fixedNote']) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['fixedNote'] : null;
        $fixedNoteTop = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['fixedNoteTop']) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['fixedNoteTop'] : null;
        $shopingCartParamForceEditable = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shopingCartParamForceEditable'][$stepNumber] : array();
        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $shopingCartReload = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartReload']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartReload'] : false;
        $shopingCartTaxAdd = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartAddTax']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartAddTax'] : array();

        // arrPrint($shopingCartTaxAdd);

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


        if (isset($_SESSION[$cCode])) {
            $total_quantity = 0;
            if (isset($_SESSION[$cCode]['items'])) {
                $no = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $no++;
                    $tmp = array(
                        //                        "id"     => $iSpec['id'],
                        "id" => $id,
                        "nama" => isset($iSpec['nama']) ? $iSpec['nama'] : "",
                        "kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "-",
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                        "jml" => $iSpec['jml'],
                        "moq" => isset($iSpec['moq']) ? $iSpec['moq'] : 0,
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "",

                        "sub_berat_gross" => isset($iSpec['sub_berat_gross']) ? $iSpec['sub_berat_gross'] : "",
                        "sub_lebar_gross" => isset($iSpec['sub_lebar_gross']) ? $iSpec['sub_lebar_gross'] : "",

                        "sub_panjang_gross" => isset($iSpec['sub_panjang_gross']) ? $iSpec['sub_panjang_gross'] : "",
                        "sub_tinggi_gross" => isset($iSpec['sub_tinggi_gross']) ? $iSpec['sub_tinggi_gross'] : "",
                        "sub_volume_gross" => isset($iSpec['sub_volume_gross']) ? $iSpec['sub_volume_gross'] : "",

                        "sub_volume" => isset($iSpec['sub_volume']) ? $iSpec['sub_volume'] : "",
                        "sub_berat" => isset($iSpec['sub_berat']) ? $iSpec['sub_berat'] : "",
                        "request_jml" => isset($iSpec['request_jml']) ? $iSpec['request_jml'] : 0,
                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "stok_avail" => isset($iSpec['stok_avail']) ? $iSpec['stok_avail'] : 0,
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

                    $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/" . $editHandlerMethod . "/" . $this->jenisTr . "?id=" . $id . "&newQty=";
                    $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $id;

                    $items[] = $tmp;

                    $total_quantity += $iSpec['qty_opname'];
                }
            }
        }

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
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "",
                        "jml" => $iSpec['jml'],
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
                        "sisa" => $sisa,
                        "produk_id" => isset($iSpec['id']) ? $iSpec['id'] : "",
                        "qty" => isset($iSpec['jml']) ? $iSpec['jml'] : "",
                        "produk_nama" => isset($iSpec['nama']) ? $iSpec['nama'] : $iSpec['nama'],
                        "produk_ord_jml" => $iSpec['jml'],
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

//        matiHEre();
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items3_sum'])) {
                $no = 0;
                foreach ($_SESSION[$cCode]['items3_sum'] as $iSpec) {
                    $no++;
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => $iSpec['satuan'],
                        "jml" => $iSpec['jml'],
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "",

                        "referensi" => isset($iSpec['pihakName']) ? $iSpec['pihakName'] : "",
                        "harga" => isset($iSpec['harga']) ? $iSpec['harga'] : "",
                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "sisa" => isset($iSpec['stok']) && isset($iSpec['jml']) ? $iSpec['stok'] - $iSpec['jml'] : "",
                        "sub_nilai" => $iSpec['sub_nilai'],
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


        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSubamount2']) && $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSubamount2'][$stepNumber] == true) {
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

        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartHideSubamount']) && $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartHideSubamount'][$stepNumber] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
            unset($itemLabels3['subtotal']);
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
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'])) {
            if (sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
                $iterator = $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'];
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
        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['referenceJenisTr']) && strlen($this->config->item("heTransaksi_ui")[$this->jenisTr]['referenceJenisTr']) > 1) {

            $refJenisTr = $this->config->item("heTransaksi_ui")[$this->jenisTr]['referenceJenisTr'];
            //            cekKuning("berasal dari $refJenisTr");
            $cCode2 = "_TR_" . $refJenisTr;
            $addValues = isset($_SESSION[$cCode2]['main_add_values']) ? $_SESSION[$cCode2]['main_add_values'] : array();
            $addValues2 = isset($_SESSION[$cCode2]['main_add_fields']) ? $_SESSION[$cCode2]['main_add_fields'] : array();
            $mainFields = array();
            $sumRows2 = array();
            $sumSpec2 = array();
            $sumType2 = array();
            if (isset($this->config->item("heTransaksi_core")[$refJenisTr]['externalValues'])) {
                if (sizeof($this->config->item("heTransaksi_core")[$refJenisTr]['externalValues']) > 0) {
                    $iterator = $this->config->item("heTransaksi_core")[$refJenisTr]['externalValues'];
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


        //==iterasi untuk memasukkan element relatif
        if (!isset($_SESSION[$cCode]['main_inputs'])) {
            $_SESSION[$cCode]['main_inputs'] = array();
        }
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
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
                            if ($currentValue == $valID) {

                            }
                            else {


                            }
                        }

                    }
                    //					$currentValue = "";

                    //                    cekbiru("$eName, currentValue: $currentValue");

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

                                    $relInputTarget = "'" . base_url() . get_class($this) . "/recordFieldInput/" . $this->jenisTr . "/$oValueName/?val='+removeCommas(this.value)";


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


        //        arrprint($elementConfigs);

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
                                heFetchElement($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc);
                                break;
                            case "dataField":
                                heRecordElement($this->jenisTr, $eName, $defValueSrc);
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
                                    cekOrange($this->db->last_query());
//                                                                        mati_disini($this->db->last_query());
                                    if (sizeof($tmpo) == 1) {
                                        $usedKey = $eSpec['key'];

                                        $defValueSrc = $tmpo[0]->$usedKey;
                                        heFetchElement($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc);
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
                                    heFetchElement($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc);
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
        //        arrPrint($_SESSION[$cCode]['main_elements']);
//        matiHEre("setelah element config ".__LINE__);
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

                                    $relInputTarget = "'" . base_url() . get_class($this) . "/recordAddRow/" . $this->jenisTr . "/$oValueName/?val='+removeCommas(this.value)";


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
//                                                cekmerah($this->db->last_query());
                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . base_url() . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";

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
                                            $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
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
                                $defValue .= "<div class='panel-body'>";
                                $defValue .= "<table cellspacing='0' cellpadding='0' border='0'>";
                                $contents[$eName] = unserialize(base64_decode($_SESSION[$cCode]['main_elements'][$eName]['contents']));
                                $semicolonnbsp = "";
                                foreach ($elementConfigs[$eName]['usedFields'] as $src => $label) {
                                    $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                    $defValue .= "<tr " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . "'>";
//cekHere(":: $fieldLabel || $src => $label ::");
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
                        $selectorTarget = "'" . base_url() . get_class($this) . "/recordFieldElement/" . $this->jenisTr . "/$eName/$amdlName/?val='+this.value";
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
//            matiHEre();
        }

        //endregion

        $tmpMasterValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();

        $main = $main + $tmpMasterValues;
//        arrPrint($_SESSION[$cCode]['main_elements']);
//        matiHere(__LINE__);
//foreach($_SESSION[$cCode]['main_elements'] as $elName => $elSpec){
//    cekLime($elName);
//}
//        matiHere();
        //bersihkan elemen yang tidak relevan
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $elName => $elSpec) {
                cekPink($elName);
                if (!array_key_exists($elName, $elementConfigs)) {
//                    matiHere($elName);
                    $_SESSION[$cCode]['main_elements'][$elName] = null;
                    unset($_SESSION[$cCode]['main_elements'][$elName]);

                    $mainResetList = array($elName, $elName . "__label");
                    foreach ($mainResetList as $kk) {
                        //                                            cekhijau("resetting $kk from main gate");
                        if (isset($_SESSION[$cCode]['main'][$kk])) {
                            $_SESSION[$cCode]['main'][$kk] = null;
                            unset($_SESSION[$cCode]['main'][$kk]);
                        }
                    }
                }
            }
        }

//        matiHere();
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
        //-------------------------------------------------------------------------------

        $elementResetTarget = base_url() . get_class($this) . "/resetElement/" . $this->jenisTr . "/";


        //--tambahan pernyataan--------
        $checkOpname = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['checkOpname']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['checkOpname'] : false;
        $checkNote = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['checkNote']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['checkNote'] : array();
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
        //-------------

        $data = array(
            "main" => $main,
            "showScheme" => $arrCons,
            "showItems" => $showItems,
            "headerScheme" => $headerScheme,
            "noteEnabled" => $noteEnabled,
            "noteType" => $noteType,
            "noteRecorder" => base_url() . "ValueGate/recordItemColumn/" . $this->jenisTr . "/note",
            "imageEnable" => $imageEnable,
            "imageType" => $imageType,
            "imageRecorder" => base_url() . "ValueGate/recordImage/" . $this->jenisTr . "/images",
            "pairedItemEnabled" => isset($pairedItemEnabled) ? $pairedItemEnabled : array(),
            //            "pairedItemRecorder" => base_url() . "ValueGate/recordPaireditem/" . $this->jenisTr . "/note",
            "pairedItemRecorder" => base_url() . "ValueGate/$pairedItemRecorder/" . $this->jenisTr . "/note",
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
            "sumRows" => isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSumFields'][$stepNumber]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSumFields'][$stepNumber] : $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][$stepNumber],
//            "sumRows2" => $sumRows2,
            "sumRows2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][$stepNumber]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][$stepNumber] : $sumRows2,
            "sumRows3" => isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSumFields3'][$stepNumber]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSumFields3'][$stepNumber] : isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields3'][$stepNumber]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields3'][$stepNumber] : array(),
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
            "resetLink" => base_url() . get_class($this) . "/reset/" . $this->jenisTr,
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
            "keyupAction" => isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['keyupAction']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['keyupAction'] : false,
            "fixedNote" => $fixedNote,
            "fixedNoteTop" => $fixedNoteTop,
            "items2_sum_kurang" => isset($items2_sum_kurang) ? $items2_sum_kurang : array(),
            "paramsForceEditable" => $paramForceEditable,
            "arrHeaderElement" => isset($arrHeaderElement) ? $arrHeaderElement : array(),
            "arrHeaderElementJenis" => $this->jenisTr,
            //--------------
            "checkOpname" => isset($checkOpname) ? $checkOpname : array(),
            "checkOpnamePaired" => base_url() . "ValueGate/checklistOpname/" . $this->jenisTr,
            //--------------
            "checkOpnameCek1" => isset($_SESSION[$cCode]['main']['opnameNoteCeklist_1']) ? $_SESSION[$cCode]['main']['opnameNoteCeklist_1'] : 0,
            "checkOpnameCek2" => isset($_SESSION[$cCode]['main']['opnameNoteCeklist_2']) ? $_SESSION[$cCode]['main']['opnameNoteCeklist_2'] : 0,
            "checkOpnameEnabled" => isset($checkNoteEnabled) ? $checkNoteEnabled : "",
            "checkOpnameNote1" => isset($pernyataanNote_1) ? $pernyataanNote_1 : "",
            "checkOpnameNote2" => isset($pernyataanNote_2) ? $pernyataanNote_2 : "",
            "checkOpnameNotePaired" => base_url() . "ValueGate/checklistOpnameNote/" . $this->jenisTr,
            "shopingCartAddTax" => $shopingCartTaxAdd,
            "shopingCartAddTaxAction" => base_url() . "Selectors/_processPihak/selectTaxes/" . $this->jenisTr,
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
            }
        }


        $previewJurnalConfig = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["previewJurnal"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["previewJurnal"] : array();
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


        $columnRecorderTarget = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["columnRecorderTarget"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["columnRecorderTarget"] : false;
        if ($columnRecorderTarget == true) {
            $data["columnRecorderTargetStatus"] = true;
            $data["columnRecorderTarget"] = base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/";
            $data["columnRecorderTargetIsi"] = isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "";
        }

        if ($shopingCartReload) {
            //shopingCartReload
            $this->load->helper("he_value_builder");
            fillValues($this->jenisTr, 1, 1);
            if (isset($_GET['stop1']) && $_GET['stop1'] == 1) {

            }
            else {
                echo "<script>";
                echo "setTimeout(function(){top.$('div#shopping_cart').load('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0&stop1=1');},2000) ";
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
        $_SESSION[$cCode]['main_applets'][$mdlName] = array(
            "key" => $id,
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
        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $elName = $this->uri->segment(4);
        $mdlName = $this->uri->segment(5);


        $key = $_GET['key'];


        heFetchElement($jenisTr, $elName, $mdlName, $key);


        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }


    public function recordFieldElement()
    {

        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $elName = $this->uri->segment(4);
        $val = ($_GET['val']);
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['relativeElements'] : array();


        heRecordElement($jenisTr, $elName, $val);

        //        die();
        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }


    public function resetElement()
    {

        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $elName = $this->uri->segment(4);
        $mdlName = $this->uri->segment(5);

        $connectedRecpm = isset($this->config->item("heTransaksi_ui")[$jenisTr]["receiptElements"][$elName]["pairMethod"]["calculate"]) ? $this->config->item("heTransaksi_ui")[$jenisTr]["receiptElements"][$elName]["pairMethod"]["calculate"] : array();

        //        $key = $_GET['key'];

//        arrPrint($connectedRecpm);
        if (sizeof($connectedRecpm) > 0) {
            if (isset($connectedRecpm["target"])) {
                $key = $connectedRecpm["target"];
                $_SESSION[$cCode]['main'][$key] = null;
                $_SESSION[$cCode]['tableIn_master_values'][$key] = null;
                unset($_SESSION[$cCode]['main'][$key]);
                unset($_SESSION[$cCode]['tableIn_master_values'][$key]);
            }
        }
//matiHEre();

//        cekmerah("resetting element $elName on $cCode...");
//        matiHEre($elName);

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

        //        die();

        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes&populate=1');";
        echo "</script>";

    }


    public function __reset()
    {
        $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
        if (isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = null;
            unset($_SESSION[$cCode]);
        }
        echo "<script>";
        echo "top.location.reload()";
        echo "</script>";
    }

    public function reset()
    {
        $this->jenisTr = $this->uri->segment(3);
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $mdlName = isset($lockerConfig['mdlName']) ? $lockerConfig['mdlName'] : "MdlLockerStock";

        $cCode = "_TR_" . $this->jenisTr;
        $this->load->model("Mdls/" . $mdlName);
        if (isset($_SESSION[$cCode]['items'])) {
            foreach ($_SESSION[$cCode]['items'] as $id => $item) {
                if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {

                    $this->db->trans_start();

                    $c = new $mdlName();
                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                    $where = array(
                        "id" => $array_hold_sebelumnya['id'],
                    );
                    $data_hold = array(
                        "jumlah" => 0,
                    );

                    $c->updateData($where, $data_hold);
                    cekBiru($this->db->last_query());


                    $c = new $mdlName();
                    $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id']);
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

            "items_komposisi",
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
            "sellerID" => $this->session->login['id'],
            "sellerName" => $this->session->login['nama'],
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
            "jenisTrTop" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
            "jenisTrName" => $this->jenisTrName,
            "stepNumber" => $stepNum,
            "stepCode" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['target'],
            "dtime" => date("Y-m-d H:i:s"),
            "fulldate" => date("Y-m-d"),
        );
        foreach ($initMaster as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
//            $_SESSION[$cCode]['main'][$key] = $val;
        }


        cekkuning("done resetting");

        echo "<script>";
        echo "top.fillBoxes();";
        echo "</script>";

        cekkuning("done re-filling");
    }


    public function recordFieldInput()
    {

        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $elName = $this->uri->segment(4);
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();

        $val = ($_GET['val']);

        if (!isset($_SESSION[$cCode]['main_inputs'])) {
            $_SESSION[$cCode]['main_inputs'] = array();
        }
        $_SESSION[$cCode]['main_inputs'][$elName] = $val;

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $val;
        //        $_SESSION[$cCode]["main"][$elName] = $val;


        //        echo "<script>";
        //        echo "top.getData('" . base_url() . "_shoppingCart/viewCart/$jenisTr/?kAhHJASAGHSGfags=kak','shopping_cart')";
        //        echo "</script>";

        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes');";
        echo "</script>";

    }

    public function recordAddRow()
    {

        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $rowName = $this->uri->segment(4);


        $val = ($_GET['val']);

        if (!isset($_SESSION[$cCode]['add_rows'])) {
            $_SESSION[$cCode]['add_rows'] = array();
        }
        $_SESSION[$cCode]['add_rows'][$rowName] = $val;

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$rowName] = $val;
        //        $_SESSION[$cCode]["main"][$rowName] = $val;


        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "/" . $_SESSION[$cCode]["main"]['stepNumber'] . "/?epreketek=yes');";
        echo "</script>";

    }


}