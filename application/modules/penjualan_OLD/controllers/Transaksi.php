<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Transaksi extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $tmpJenis = $this->jenisTr;
        $this->allSteps = isset($this->configUi[$tmpJenis]['steps']) ? $this->configUi[$tmpJenis]['steps'] : array();
        $this->mobile = $this->config->item("mobile");
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        //        arrPrint($this->session->login);
        $this->reject = true;
        $this->reject_all = true;
        $this->notAllowEdit = array(
            "66771",
        );
    }

    public function validate()
    {
        $fieldOpnameEntryValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartFieldOpnameEntryValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartFieldOpnameEntryValidators"] : array();
        $fieldOpnameValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartFieldOpnameValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartFieldOpnameValidators"] : array();

        $fieldValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartFieldValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartFieldValidators"] : array();
        $fieldValidatorRulesExt = isset($this->configUi[$this->jenisTr]["shopingCartValidatorExt"]) ? $this->configUi[$this->jenisTr]["shopingCartValidatorExt"] : array();
        $rowValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartRowValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartRowValidators"] : array();
        $rowNumValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartRowNumValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartRowNumValidators"] : array();
        $rowOptValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartRowOptionalValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartRowOptionalValidators"] : array();
        $validateReceiveElement = isset($this->configUi[$this->jenisTr]["validateReceiveElement"][1]) ? $this->configUi[$this->jenisTr]["validateReceiveElement"][1] : array();
        $appletConfigs = isset($this->configUi[$this->jenisTr]['applets']) ? $this->configUi[$this->jenisTr]['applets'] : array();
        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->configUi[$this->jenisTr]['relativeElements']) ? $this->configUi[$this->jenisTr]['relativeElements'] : array();
        $availPayments = isset($this->configUi[$this->jenisTr]['availPayments']) ? $this->configUi[$this->jenisTr]['availPayments'] : array();
        $validateAdditional = isset($this->configUi[$this->jenisTr]['shopingCartAddValidator']['additional']) ? $this->configUi[$this->jenisTr]['shopingCartAddValidator']['additional'] : array();
        $fieldValidatorsComparison = isset($this->configUi[$this->jenisTr]['shoppingCartFieldValidatorsComparison']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldValidatorsComparison'] : array();

        $fieldValidatorPairedItem = isset($this->configUi[$this->jenisTr]["shoppingCartValidatorsPairedItem"]) ? $this->configUi[$this->jenisTr]["shoppingCartValidatorsPairedItem"] : array();
        $unionValidateComparison = isset($this->configUi[$this->jenisTr]["shopingCartUnionComparison"]) ? $this->configUi[$this->jenisTr]["shopingCartUnionComparison"] : array();
        $unionValidate = isset($this->configUi[$this->jenisTr]["shoppingCartUnionValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartUnionValidators"] : array();
        $balanceValidate = isset($this->configUi[$this->jenisTr]["shoppingCartBalanceValidate"]) ? $this->configUi[$this->jenisTr]["shoppingCartBalanceValidate"] : array();
        $previewCtr = isset($this->configUi[$this->jenisTr]["previewCtr"]) ? $this->configUi[$this->jenisTr]["previewCtr"] : matiHere('controller preview belum diset');
        $descriptionValidate = isset($this->configUi[$this->jenisTr]["descriptionValidate"]) ? $this->configUi[$this->jenisTr]["descriptionValidate"] : array();
        $selectorValidator = isset($this->configUi[$this->jenisTr]["selectorValidator"][1]) ? $this->configUi[$this->jenisTr]["selectorValidator"][1] : array();
        $shoppingCartRowAdditionalValidators = isset($this->configUi[$this->jenisTr]["shoppingCartRowAdditionalValidators"][1]) ? $this->configUi[$this->jenisTr]["shoppingCartRowAdditionalValidators"][1] : array();

        $cCode = $this->cCode;

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        // mati_disini(__CLASS__ . " $cCode");
        //==iterasi untuk memasukkan element relatif
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            //            //cekBiru("hendak memeriksa relative impacts");
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
                //                //cekBiru("memeriksa $eName:");
                if (array_key_exists($eName, $relElementConfigs)) {
                    //                    //cekHijau("$eName memiliki relative impacts");
                    $currentValue = "";
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //                        //cekHijau("memenuhi syarat");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            //                            //cekMerah("memeriksa $eName, $currentValue");
                            //                            $rcCtr = 0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                //                                $elKey = $eName . "_" . $currentValue . "_" . $rcID;
                                $elKey = $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
                                //                                $rcCtr++;
                            }
                        }

                    }
                    else {
                        //                        //cekMerah("TIDAK memenuhi syarat");
                    }
                }
            }
        }
        $arrySegment = $this->uri->segment_array();
        if (isset($_SESSION[$cCode]['mode']['edit']) && sizeof($_SESSION[$cCode]['mode']['edit']) > 0) {
            $prevAction = $previewCtr . "/editPreview";
            $main_id = "/" . $_SESSION[$cCode]['mode']['edit'];
        }
        else {
            if (isset($_SESSION[$cCode]['mode']['cancel']) && sizeof($_SESSION[$cCode]['mode']['cancel']) > 0) {
                $prevAction = "$previewCtr/preCancelPackingPreview";
                $main_id = "/" . $_SESSION[$cCode]['mode']['cancel'];
            }
            else {
                $prevAction = $previewCtr . "/preview";
                $main_id = "";
            }
        }

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        $errErrorException = array();
        if (sizeof($rowValidatorRules) > 0) {

            foreach ($rowValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label wajib diisi";

                    $errRows[] = $field;
                }

            }

        }
        if (sizeof($rowNumValidatorRules) > 0) {

            foreach ($rowNumValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label wajib dilengkapi";

                    $errRows[] = $field;
                }
                else {
                    if (0 + ($_SESSION[$cCode]['main'][$field]) < 0.1) {
                        $errMsgs[] = "$label should be a number";

                        $errRows[] = $field;
                    }
                }

            }

        }
        if (sizeof($rowOptValidatorRules) > 0) {
            foreach ($rowOptValidatorRules as $srcName => $srcSpec) {
                foreach ($srcSpec as $value => $pair) {
                    if (isset($_SESSION[$cCode]['main'][$srcName]) && $_SESSION[$cCode]['main'][$srcName] == $value) {
                        foreach ($pair as $k => $v) {
                            if (!isset($_SESSION[$cCode]['main'][$k])) {
                                $errMsgs[] = "$k is required";

                                $errRows[] = $k;
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($fieldValidatorRules) > 0) {

            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $nama = htmlspecialchars($iSpec['name']);
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "$label wajib diisi";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "$label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ((int)$iSpec[$field] < 0.5) {
                                //                                $errMsgs[] = "$label must be > 0";
                                $errMsgs[] = "$label belum ditentukan. silahkan diperiksa lagi atau hubungi admin.";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }

        }
        if (sizeof($fieldValidatorRulesExt) > 0) {

            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    // $id = $iSpec['id'];
                    // $nama = htmlspecialchars($iSpec['name']);
                    // if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                    //     if (!isset($errFields[$id])) {
                    //         $errFields[$id] = array();
                    //     }
                    //     foreach ($fieldValidatorRulesExt as $field => $label) {
                    //         if (!isset($iSpec[$field])) {
                    //             $errMsgs[] = "$label wajib diisi";
                    //             $errLines[] = $id;
                    //             $errFields[$id][] = $field;
                    //         }
                    //         if (!is_numeric($iSpec[$field])) {
                    //             $errMsgs[] = "$label must be a valid number";
                    //             $errLines[] = $id;
                    //             $errFields[$id][] = $field;
                    //         }
                    //         if ((int)$iSpec[$field] < 0.5) {
                    //             $errMsgs[] = "$label must be > 0";
                    //             $errLines[] = $id;
                    //             $errFields[$id][] = $field;
                    //         }
                    //     }
                    // }

                }
            }

        }
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $aSpec) {
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                    }
                    else {
                        $errMsgs[] = ($aSpec['label'] . " wajib dipilih!");
                        echo "<script>";
                        echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left'};";
                        echo "</script>\n";
                    }
                }
                else {
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            if (isset($validateReceiveElement[$eName]) && sizeof($validateReceiveElement[$eName])) {
                                $valid = 0;
                                $check = array();
                                foreach ($validateReceiveElement[$eName] as $tLabel => $textWarning) {
                                    if (strlen($_SESSION[$cCode]['main'][$eName . '__' . $tLabel]) > 10) {
                                    }
                                    else {
                                        $check[] = $tLabel;
                                    }
                                }
                                if (sizeof($check) > 1) {
                                    $errMsgs[] = ("Harap lengkapi salah satu NPWP/KTP diBillingDetails customer");
                                    foreach ($check as $nLabel) {
                                        $errMsgs[] = ("lengkapi $nLabel!");
                                    }
                                }
                            }

                            if (strlen($_SESSION[$cCode]['main_elements'][$eName]['key']) < 0.5) {
                                $errMsgs[] = ("element " . $aSpec['label'] . " must be filled with one entry!");
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left'};";
                                echo "</script>\n";
                            }
                            else {
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left'};";
                                echo "</script>\n";
                            }
                            break;
                        case "dataField":
                            if (strlen($_SESSION[$cCode]['main_elements'][$eName]['value']) < 0.5) {
                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                                }
                                else {
                                    $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!***");
                                    echo "<script>";
                                    echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left'};";
                                    echo "</script>\n";
                                }

                            }
                            else {
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left'};";
                                echo "</script>\n";
                            }
                            break;
                    }

                }
            }
        }
        if (sizeof($fieldValidatorsComparison) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldValidatorsComparison as $field => $label) {
                $labels[$label] = $field;
                $result[$label] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }

            if (round($result["sumber"], 2) != round($result["target"], 2)) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai total tidak sama dari nilai akumulasi nota yang dipilih.";
                $errRows[] = "test";
            }


        }
        if (sizeof($unionValidateComparison) > 0) {
            $result = array();
            $labels = array();
            $validate = 0;
            $val = array();
            $valsValidate = array();

            foreach ($unionValidateComparison as $fieldData) {

                if (isset($_SESSION[$cCode]['main']["nilai_entry"])) {
                    if ($_SESSION[$cCode]['main']["nilai_entry"] > 0) {

                    }
                    else {
                        if ($_SESSION[$cCode]['main']["nilai_entry"] == 0) {
                            unset($_SESSION[$cCode]['main']["nilai_entry"]);
                        }
                        else {

                            if (sizeof($unionValidate) > 0) {

                                foreach ($unionValidate as $fieldUnion) {
                                    $validateUnion = 0;
                                    foreach ($fieldUnion as $field => $fieldLabel) {
                                        if (isset($_SESSION[$cCode]['main'][$field]) && $_SESSION[$cCode]['main'][$field] > 0) {

                                        }
                                        else {
                                            $validateUnion++;

                                        }
                                    }
                                    if (sizeof($fieldUnion) == $validateUnion) {
                                        $errMsgs[] = $fieldData["nilai_entry"];
                                    }
                                }
                            }
                            else {
                                $errMsgs[] = $fieldData["nilai_entry"];
                            }
                        }

                    }
                }
                else {
                    if (sizeof($unionValidate) > 0) {

                        foreach ($unionValidate as $fieldUnion) {
                            $validateUnion = 0;
                            $err = array();
                            foreach ($fieldUnion as $field => $fieldLabel) {
                                if (isset($_SESSION[$cCode]['main'][$field]) && $_SESSION[$cCode]['main'][$field] > 0) {

                                }
                                else {
                                    $validateUnion++;
                                    $err[] = $fieldLabel;

                                }
                            }
                            if (sizeof($err) == sizeof($fieldUnion)) {
                                $errMsgs[] = "metode pembayaran tidak ada yang dipilih, silahkan periksa kembali";
                            }
                        }
                    }
                    else {
                        $errMsgs[] = $fieldData["nilai_entry"];
                    }
                }

                foreach ($fieldData as $field => $label) {

                    //                    if (isset($_SESSION[$cCode]['main'][$field]) && ($_SESSION[$cCode]['main'][$field] > 0)) {
                    if (isset($_SESSION[$cCode]['main'][$field])) {
                        //cekHere("ada $field dan nilai $field tidak sama dengan nol ");
                        $validate++;
                        $val["source"] = $field;
                        $valsValidate["source"][] = $field;
                    }
                    else {
                        //cekHere("TIDAK ada $field  ");
                        $val["target"] = $field;
                        $valsValidate["target"][] = $field;
                    }
                }

            }
            //arrPrint($unionValidateComparison);
            //arrPrint($valsValidate);


            if (array_key_exists("source", $val)) {
                $cekval = $val["target"];
                if (!isset($_SESSION[$cCode]['main'][$cekval])) {
                    foreach ($unionValidateComparison as $data) {
                        //                        //cekHere(":: sampai sini $cekval :: " . $data[$cekval]);
                        if (isset($data[$cekval])) {
                            $errMsgs[] = $data[$cekval];
                            $errErrorException[] = $data[$cekval];
                        }
                    }
                }
                //arrPrint($errMsgs);
            }
            // arrPrint($valsValidate);
            if (!isset($valsValidate["source"]) || (sizeof($valsValidate["source"]) == 0)) {
                // mati_disini("tidak memilih cash account, credit note, uang muka");
                //  $errMsgs[] = "cash account, credit note, uang muka tidak ada yang dipilih.";
            }

        }
        if (sizeof($fieldValidatorPairedItem) > 0) {
            $label = $this->configUi[$this->jenisTr]["label"];
            $source = isset($_SESSION[$cCode][$fieldValidatorPairedItem['sumber']]) ? $_SESSION[$cCode][$fieldValidatorPairedItem['sumber']] : "";
            $target = isset($_SESSION[$cCode][$fieldValidatorPairedItem['target']]) ? $_SESSION[$cCode][$fieldValidatorPairedItem['target']] : "";
            if (sizeof($source) > 0) {
                foreach ($source as $pID => $pSpec) {
                    $pTarget = isset($target[$pID]) ? $target[$pID] : array();

                    if (!array_key_exists($pID, $target)) {
                        $errMsgs[] = "$label " . $pSpec['name'] . " is required.";
                    }
                    if (isset($pTarget['jml']) && isset($pSpec['jml'])) {
                        if ($pTarget['jml'] < $pSpec['jml']) {
                            $errMsgs[] = "konversi " . $pSpec['name'] . " salah. silahkan cek ulang jumlah konversinya.";
                        }
                        $jml_satuan = $pTarget['jml'] / $pSpec['jml'];
                        $jml_satuan_ex = explode(".", $jml_satuan);
                        if (sizeof($jml_satuan_ex) > 1) {
                            $errMsgs[] = "konversi " . $pSpec['name'] . " salah. silahkan cek ulang jumlah konversinya.";
                        }
                    }
                }
            }
        }
        if (sizeof($fieldOpnameValidatorRules) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = htmlspecialchars($iSpec['nama']);
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        foreach ($fieldOpnameValidatorRules as $field => $label) {
                            if (isset($iSpec['qty_selisih']) && ($iSpec['qty_selisih'] > 0)) {
                                if ((int)$iSpec[$field] <= 0) {
                                    $errMsgs[] = "$label $name must be > 0";
                                    $errLines[] = $id;
                                    $errFields[$id][] = $field;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($fieldOpnameEntryValidatorRules) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $no = 0;
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $no++;
                    $id = $iSpec['id'];
                    $kode = $iSpec['produk_kode'];
                    $label = $iSpec['label'];

                    $name = htmlspecialchars($iSpec['nama']);
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        foreach ($fieldOpnameEntryValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field]) && ($iSpec['stok'] > 0)) {
                                $errMsgs[] = "$label $name harus diketik ulang, baris nomer $no.";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;

                            }
                        }
                    }

                    $stokBuku = isset($iSpec->stok) ? $iSpec->stok : 0;
                    $stokOpname = $iSpec->qty_opname;
                    $selisih = $stokOpname - $stokBuku;
                    $stokBukuHitung = $iSpec->qty_opname - $iSpec->qty_debet + $iSpec->qty_kredit;
                    if ($selisih < 0) {
                        if ($selisih != $iSpec->qty_kredit) {
                            $msg = "Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.";
                            $errMsgs[] = $msg;
                        }
                    }
                    elseif ($selisih > 0) {
                        if ($selisih != $iSpec->qty_debet) {
                            $msg = "Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.";
                            $errMsgs[] = $msg;
                        }
                    }
                    elseif ($stokBukuHitung != $stokBuku) {
                        $msg = "Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.";
                        $errMsgs[] = $msg;
                    }
                    //                    else{
                    //                        mati_disini("Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.");
                    //                    }
                }
            }
        }
        //----------------------
        if (sizeof($descriptionValidate) > 0) {
            if (isset($descriptionValidate["enabled"]) && ($descriptionValidate["enabled"] == true)) {
                foreach ($descriptionValidate["key"] as $dkey => $dlabel) {
                    $deskripsi_note = (isset($_SESSION[$cCode]["main"][$dkey]) && strlen($_SESSION[$cCode]["main"][$dkey]) > 5) ? $_SESSION[$cCode]["main"][$dkey] : NULL;
                    if ($deskripsi_note == NULL) {
                        $errMsgs[] = "$dlabel";
                    }
                }
            }
        }
        //----------------------
        if (sizeof($selectorValidator) > 0) {
            $selKosong = array();
            foreach ($selectorValidator as $ii => $spec) {
                foreach ($spec["keys"] as $key) {
                    if (!isset($_SESSION[$cCode]['main'][$key])) {
                        $selKosong[$ii] = $key;
                    }
                    elseif (isset($_SESSION[$cCode]['main'][$key]) && ($_SESSION[$cCode]['main'][$key] == null)) {
                        $selKosong[$ii] = $key;
                    }
                }
            }
            if (sizeof($selKosong) > 0) {
                foreach ($selKosong as $iii => $x) {
                    $errMsgs[] = $selectorValidator[$iii]["label"];
                }
            }
        }
        //----------------------
        if (sizeof($shoppingCartRowAdditionalValidators) > 0) {
            if ($_SESSION[$cCode]['main']['pihakTipeID'] == 1) {// marketplace

                foreach ($shoppingCartRowAdditionalValidators as $key => $label) {
                    if (!isset($_SESSION[$cCode]['main'][$key])) {
                        $errMsgs[] = $label;
                    }
                    elseif ($_SESSION[$cCode]['main'][$key] == NULL) {
                        $errMsgs[] = $label;
                    }
                }

            }
        }


        //region validate additional
        if (sizeof($validateAdditional) > 0) {
            $selectedAdd = $_SESSION[$cCode]["main"]["additional"];
            if (isset($validateAdditional[$selectedAdd])) {
                $valKey = $validateAdditional[$selectedAdd];
                if ($_SESSION[$cCode]["main"][$valKey] == 0) {
                    $errMsgs[] = "additional kurs must be filled or select none on additional for additional kurs 0 ";
                }
            }

        }
        //        matiHEre();

        //endregion

        if (sizeof($balanceValidate) > 0) {
            foreach ($balanceValidate as $kk => $kSpec) {
                if ($kk == "key") {
                    $debet = $kSpec[0];
                    $kredit = $kSpec[1];
                    $debetNilai = isset($_SESSION[$cCode]['main'][$debet]) ? $_SESSION[$cCode]['main'][$debet] : 0;
                    $kreditNilai = isset($_SESSION[$cCode]['main'][$kredit]) ? $_SESSION[$cCode]['main'][$kredit] : 0;
                    if ($debetNilai != $kreditNilai) {
                        $errMsgs[] = $balanceValidate['label'];
                    }
                }
            }
        }
        // arrPrintBlue($_SESSION[$cCode]['tableIn_detail']);
        // mati_disini(__LINE__);
        // always validation active
        $gateNameDetail = "tableIn_detail";
        if (!isset($_SESSION[$cCode][$gateNameDetail]) || sizeof($_SESSION[$cCode][$gateNameDetail]) == 0) {

            $msg = "Transaksi gagal dilanjutkan karena rincian transaksi kosong.<br>Silahkan merubah qty/unit price dahulu dan masukkan data yang benar supaya transaksi bisa dilanjutkan. @" . __LINE__ . __METHOD__;
            mati_disini($msg);
        }
        else {
            foreach ($_SESSION[$cCode][$gateNameDetail] as $spec) {
                if (!isset($spec['valid_qty'])) {
                    $hidden_msg = "<br><span style='color:#ffffff;'>gerbang valid_qty tidak ada</span>";
                    $msg = "Transaksi gagal dilanjutkan karena rincian transaksi kosong.<br>Silahkan merubah qty/unit price dahulu dan masukkan data yang benar supaya transaksi bisa dilanjutkan. $hidden_msg";
                    mati_disini($msg);
                }
                else {
                    if ($spec['valid_qty'] == 0) {
                        $hidden_msg = "<br><span style='color:#ffffff;'>gerbang valid_qty bernilai 0</span>";
                        $msg = "Transaksi gagal dilanjutkan karena rincian transaksi kosong.<br>Silahkan merubah qty/unit price dahulu dan masukkan data yang benar supaya transaksi bisa dilanjutkan. $hidden_msg";
                        mati_disini($msg);
                    }
                }
            }
        }


        /* ------------------------------
         * validasi stok saat mau produksi
         * ------------------------------*/
        $mains = $_SESSION[$cCode];

        $items2_sum = isset($mains['items2_sum']) ? $mains['items2_sum'] : "";
        switch ($this->jenisTr) {
            case "776":

                $kurangStoks = array();
                $hasil = "";
                $no = 0;
                foreach ($items2_sum as $pId => $item) {

                    $nama = $item['nama'];
                    $sisa = $item['stok'] - $item['jml'];

                    if ($sisa < 0) {
                        $no++;
                        $kurangStoks[$pId] = $sisa;
                        //
                        $sisa_f = $sisa * -1;
                        $nama_f = htmlspecialchars($nama);
                        $var = "<span class='font-size-0-7'>$no. $nama_f: <span class='text-bold'>$sisa_f</span></span>";
                        if ($hasil == "") {
                            $hasil = "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }
                }


                if (sizeof($kurangStoks) > 0) {
                    // //cekhitam(sizeof($kurangStoks));
                    $alerts = array("type" => "warning",
                        "title" => "Persediaan tidak cukup",
                        "html" => $hasil,
                    );
                    echo swalAlert($alerts);
                    die();
                }

                break;
            case "1982":
                break;
            case "961":
            case "967":
                $items = $mains['items'];
                $kurangStoks = array();
                $hasil = "";
                $no = 0;
                foreach ($items as $pId => $item) {
                    if (isset($item['stok'])) {
                        $nama = $item['nama'];
                        $sisa = $item['stok'] - $item['jml'];

                        if ($sisa < 0) {
                            $no++;
                            $kurangStoks[$pId] = $sisa;

                            $sisa_f = $sisa * -1;
                            $stok_f = $item['stok'];
                            $jml_f = $item['jml'];
                            $nama_f = htmlspecialchars($nama);
                            $var = "<span class='font-size-0-7'>$no. <span style='font-weight:bold;'>$nama_f</span>, stok tersedia $stok_f, jumlah input <span style='font-weight:bold;'>$jml_f</span> melebihi stok yang tersedia. Silahkan dikoreksi lagi.</span>";
                            if ($hasil == "") {
                                $hasil = "$var";
                            }
                            else {
                                $hasil = "$hasil<br>$var";
                            }
                        }
                    }
                }
                if (sizeof($kurangStoks) > 0) {

                    $alerts = array("type" => "warning",
                        "title" => "Insufficient stock",
                        "html" => $hasil,
                    );
                    echo swalAlert($alerts);
                    die();
                }

                break;
            case "749":
                if ($_SESSION[$cCode]['main']['lebih_bayar'] <= 0) {
                    //                    matiHEre("iki opoo ".$_SESSION[$cCode]['main']['lebih_bayar']);
                    $_SESSION[$cCode]['main']['nilai_cash'] = $_SESSION[$cCode]['main']['nilai_entry'];
                    //arrPrint($errErrorException);
                    if (sizeof($errErrorException) > 0) {
                        foreach ($errErrorException as $errKey => $errVal) {
                            if (array_key_exists($errKey, $errMsgs)) {
                                unset($errMsgs[$errKey]);
                            }
                        }
                    }

                }

                break;
        }

        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);

            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }

            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }


            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        else {

            //             mati_disini("LOLOS, tidak kena VALIDATE :: $prevAction");
            // $shoppingCart =  base_url() . $this->modul . "/" . $prevAction . "/" . $this->jenisTr . "?rawPrev=$rawPrevURL";

            $actionTarget = "top.BootstrapDialog.show({
                                title:'preview',
                                message: " . 'top.$' . "('<div></div>').load('" . base_url() . $this->modul . "/" . $prevAction . "/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
                                draggable:false,
                                size:top.BootstrapDialog.SIZE_WIDE,
                                type:top.BootstrapDialog.TYPE_DEFAULT,
                                closable:true,
                                }
                             );";

            echo "<script>top.close_holdon();$actionTarget</script>";

        }
    }

    public function viewUndoneItems()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        //        arrPrintHijau($this->mobile);

        $historyFields = $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $dueDateAllow = isset($this->configUi[$jenisTr]['updateDueDate']) ? $this->configUi[$jenisTr]['updateDueDate'] : array();
        /* ----------------------------------------------------------------------
         * deteksi mobile auto atau hanya orang tertentu,
         * diatur di heWeb mobile
         * ----------------------------------------------------------------------*/
        $isMob = isMobile_he_misc();

        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();
        $glanceHistoryFields = isset($this->configUi[$jenisTr]['glanceHistoryFields']) ? $this->configUi[$jenisTr]['glanceHistoryFields'] : array("nomer" => "receipt no");
        $glanceHistoryFields2 = array("nomer" => "receipt no"); //init;
        $selectorProcessor = base_url() . $this->configUi[$jenisTr]['selectorProcessor'] . "/$jenisTr";
        $kreditLimitValidate = isset($this->configUi[$jenisTr]["kreditLimitValidate"]) ? $this->configUi[$jenisTr]["kreditLimitValidate"] : array();
//        matiHere(__LINE__);
        // --------------------------------------------------------------
        $this->load->model("Coms/ComTransaksiKreditLimit");
        $kl = New ComTransaksiKreditLimit();
        $arrKreditLimitDataKredit = $kl->fetchBalancesKredit();
        //        arrPrintWebs($arrKreditLimitDataKredit);
        // --------------------------------------------------------------


        //region lookup on-going transactions
        $arrayOnprogress = array();
        $progressFields = $historyFields;

        // $progressFields['jenis_label'] = "activity";
        $progressFields['oleh_nama'] = "started by";
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";

        $steps = $this->configUi[$jenisTr]['steps'];

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        if (sizeof($steps) > 1) {

            $stepCodes = array();
            $jmlStep = count($steps);
            if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList) > 0) {
                $arrFilters = array();
                $indsteps = "(";
                foreach ($this->accessList[$jenisTr] as $stepNumber => $stepSpec) {
                    if ($stepNumber <= $jmlStep) {
                        foreach ($stepSpec as $targetCode => $filters) {
                            $indsteps .= "'$targetCode',";
                            $stepCodes[] = $targetCode;
                            if ($filters['allowFollowUp'] == "true") {
                                $arrFilters["allowFollowUp"][] = $targetCode;
                            }

                        }
                    }
                }
                $indsteps = rtrim($indsteps, ",");
                $indsteps .= ")";
                if (sizeof($arrFilters) > 0) {
                    $tr->addFilter("next_step_code in $indsteps");
                }
                else {
                    $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                }
            }
            else {
                foreach ($steps as $stepNumber => $stepSpec) {
                    if ($stepNumber < $jmlStep) {
                        $stepCodes[] = $stepSpec['target'];
                    }

                }
            }

        }


        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("transaksi_data.trash=0");
        $tr->addFilter("transaksi_data.valid_qty>0");
        if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList) > 0) {
        }
        else {
            if (sizeof($this->session->login['membership']) > 0) {
                $this->db->group_start();
                $mCtr = 0;
                foreach ($this->session->login['membership'] as $gID) {
                    if ($mCtr == 0) {
                        $this->db->where(array("next_subgroup_code" => $gID));

                    }
                    else {
                        $this->db->or_where(array("next_subgroup_code" => $gID));
                    }
                    $mCtr++;
                }
                $this->db->group_end();
            }
        }

        $tmpHist = $tr->lookupUndoneEntries_joined(replaceSession())->result();
        cekLime($this->db->last_query());

        //region due date
        $tr->setFilters(array());
        $listedDue = $tr->lookupAllDueDate()->result();
        $arrDue = array();
        $overDue = array();
        foreach ($listedDue as $dueData) {

            $due_date = strtotime($dueData->due_date);
            $date_now = strtotime(date("Y-m-d"));
            if ($due_date < $date_now) {
                $arrDue[$dueData->transaksi_id] = array(
                    "due_date" => $dueData->due_date,
                    "aging" => umurDay($dueData->due_date),
                );
            }

        }
        //arrPrint($arrDue);
        //arrPrint($tmpHist);
        //endregion
        $arrOver_due = array();


        if (sizeof($tmpHist) > 0) {
            $arrTransID = array();
            $arrTransTopID = array();
            $arrIdsHist = array();
            $arrTransHist = array();
            foreach ($tmpHist as $row) {
                $arrTransID[] = $row->transaksi_id;
                $arrTransTopID[] = $row->id_top;

                if ($row->ids_his != "") {
                    $hist = blobDecode($row->ids_his);
                    foreach ($hist as $hisSpec) {
                        $arrIdsHist[$row->id][$hisSpec['step']] = array(
                            "step" => $hisSpec['step'],
                            "trID" => $hisSpec['trID'],
                            "nomer" => $hisSpec['nomer'],
                        );
                        $arrTransHist[] = $hisSpec['trID'];
                    }
                }
            }
            $tmpReg_result = array();
            $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";

            // membaca payment source label uang muka, untuk kas yang harus diterima dulu.
            $psrc = New MdlTransaksi();
            $psrc->setFilters(array());
            $psrc->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $psrc->addFilter("terbayar=0");
            $psrcTmp = $psrc->lookUpAllPaymentSrc()->result();
            //            showLast_query("biru");
            $psrcData = array();
            if (sizeof($psrcTmp) > 0) {
                foreach ($psrcTmp as $psrcSpec) {
                    //                    arrPrintPink($psrcSpec);
                    $psrcData[$psrcSpec->transaksi_id] = array(
                        "extern_id" => $psrcSpec->extern_id,
                        "extern_nama" => $psrcSpec->extern_nama,
                        "nomer" => $psrcSpec->nomer,
                        "terbayar" => $psrcSpec->terbayar,
                        "sisa" => $psrcSpec->sisa,
                        "label" => "transaksi ini sedang ditindaklanjuti oleh pihak finance.",
                    );
                }
            }


            $trReg = new MdlTransaksi();
            $trReg->setFilters(array());
            //            $trReg->addFilter("param='main'");
            $trReg->setJointSelectFields($selectKolom);
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $tmpReg = $trReg->lookupDataRegistries()->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    //                    arrPrintWebs($regRow);
                    foreach ($regRow as $key_reg => $val_reg) {
                        if ($val_reg == null) {
                            $val_reg = blobEncode(array());
                        }
                        if ($key_reg != "transaksi_id") {
                            $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                        }
                    }

                }
            }
            //            arrPrintWebs($tmpReg_result);
            //            mati_disini();
            if (sizeof($arrIdsHist) > 0) {
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                $tmpTransHist = $tr->lookupAll()->result();


                if (sizeof($tmpTransHist) > 0) {
                    foreach ($tmpTransHist as $histSpec) {
                        $tmpTransHist_result[$histSpec->id] = array(
                            "oleh_id" => $histSpec->oleh_id,
                            "oleh_nama" => $histSpec->oleh_nama,
                        );
                    }
                }

                foreach ($arrIdsHist as $trID => $histSpec) {
                    foreach ($histSpec as $step => $detailSpec) {
                        if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                            $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                        }
                        $arrTransMainHist[$trID][$step] = $detailSpec;
                    }
                }
            }
            $numb = 0;
            foreach ($tmpHist as $row) {
                $step_current = $row->step_current;
                $extHistoryFields2 = isset($arrExtHistoryFields2[$row->step_number]) ? $arrExtHistoryFields2[$row->step_number] : array();

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                        foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                            switch ($param) {
                                case "main":
                                    foreach ($eReg as $k => $v) {
                                        if (!isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                    break;
                                case "items":
                                    if (sizeof($extHistoryFields2) > 0) {
                                        foreach ($extHistoryFields2 as $k1 => $v1) {
                                            if (is_array($v1)) {
                                                $kolom = $v1['kolom'];
                                                $format = $v1['format'];
                                                //                                                cekHitam(":: $kolom :: $format ::");
                                                if (!isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                            else {
                                                if (!isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }

                $tmp = array();
                $numb++;

                foreach ($historyFields as $fName => $fLabel) {

                    if (isset($row->$fName)) {
                        if (is_numeric($row->$fName)) {
                            if (!isset($sumFooter[$fName])) {
                                $sumFooter[$fName] = 0;
                            }
                            $sumFooter[$fName] += $row->$fName;
                        }
                    }

                    if (is_array($fLabel)) {
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                                $tNomer = $id_hist[$hisStep][$hisKey];

                        if (isset($row->ids_his)) {
                            if ($hisKey == "nomer") {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $ids_his_decode = blobDecode($row->ids_his);
                                if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                    $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                        }
                        else {
                            $tmp[$fName] = "-";
                        }
                    }
                    else {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                    }

                    if ($fName == "no") {
                        $tmp[$fName] = formatField_he_format($fName, $numb);
                    }
                }

                if ($row->sub_step_number > 0) {
                    $tmp['state'] = createStateHorizontal($row->sub_step_number, $row->step_avail, $jenisTr);
                }
                else {
                    $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                }


                $tmp['action'] = "";
                $nextStepNum = ($row->next_substep_num);
                $currentStepNum = ($row->sub_step_number);
                $nextStepCode = ($row->next_step_code);
                $allowJoin = isset($this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;


                $allowFollowup = false;
                if ($row->sub_step_number > 0) {
                    $allowFollowup = true;
                    $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                }

                if (isset($this->configUi[$jenisTr]['steps'][$nextStepNum])) {
                    if (isset($this->accessList[$jenisTr])) {
                        if (isset($this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                            $allowFollowup = $this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }
                    }
                    else {
                        if (placeCanFollowupTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $nextStepNum, "", $this->configUiJenis)) {
                            $allowFollowup = true;
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];

                        }
                    }

                }

                if ($allowFollowup) {
                    $stepLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['label'];
                    $isCancelPacking = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                    $allowCancel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel'] : false;

                    $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                    $followupLink = "disableShopCart();top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                    $class_button = "btn-primary";
                    $numberValidate = $row->step_number + 1;
                    $disabled_button_kreditlimit = "";
                    $disabled_button = "";
                    $add_note = "";
                    if (isset($psrcData[$row->transaksi_id])) {
                        $disabled_button = "disabled";
                        $add_note = "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                        $class_button = "btn-default";
                    }
                    if (isset($kreditLimitValidate[$numberValidate])) {
                        if (isset($arrKreditLimitDataKredit[$row->customers_id]["kredit"]) && ($arrKreditLimitDataKredit[$row->customers_id]["kredit"] > 0)) {
                            $disabled_button_kreditlimit = "disabled";
                            $add_note = "<br><span class='meta'>" . $kreditLimitValidate[$numberValidate]["label"] . "</span>";
                            $class_button = "btn-default";
                        }
                    }


                    $tmp['action'] = "<div class='input-group'>";
                    $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' 
                        data-toggle='tooltip' href='javascript:void(0)' 
                        onClick =\"top.open_holdon();$followupLink\">
                        <span class='glyphicon glyphicon-ok'></span> " . $actionLabel . "</a>";
                    $tmp['action'] .= "</div class='input-group'>";
                    $tmp['action'] .= $add_note;
                    if ($allowJoin) {
                        $tmp['action'] .= "<span class='input-group-addon'>";
                        $tmp['action'] .= "<a title='process many items at once' href='" . MODUL_PATH . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                        $tmp['action'] .= "</span class='input-group-addon'>";
                    }
                }

                $arrayOnprogress[] = $tmp;

            }
        }
        //endregion


        $sumFooterResult = array();
        if (isset($sumFooter) && sizeof($sumFooter) > 0) {
            foreach ($sumFooter as $key => $val) {
                $sumFooterResult[$key] = formatField_he_format($key, $val);
            }
        }

        $data = array(
            "mode" => "viewUndoneItems",
            "isMobile" => $isMob,
            "arrayProgressLabels" => $progressFields,
            "arrayProgress2Labels" => $glanceHistoryFields2,
            "arrayOnProgress" => $arrayOnprogress,
            "arrayOnProgress2" => isset($arrayOnprogress2) ? $arrayOnprogress2 : array(),
            "needToClear" => isset($needToClear) ? $needToClear : "",
            "reqFormTarget" => isset($reqFormTarget) ? $reqFormTarget : "",
            "allowMultiSelect" => isset($allowMultiSelect) ? $allowMultiSelect : "",
            "overDue" => $arrOver_due,
            "clearCartTarget" => MODUL_PATH . "/_shoppingCart/reset/" . $this->jenisTr,

            "sumFooter" => $sumFooterResult,
            "jenisTr" => $jenisTr,
        );

        $this->load->view("transaksi", $data);

    }

    public function viewUndoneItemsIndex()
    {

        session_write_close();
        $sisa_pembayaran = 100;
        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $starttime = microtime(true);
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $scriptBottom = "";
        $sesionReplacer = replaceSession();

        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $paymentConfig = isset($this->configUi[$jenisTr]['paymentConfig']) ? $this->configUi[$jenisTr]['paymentConfig'] : false;
        $myPaymentConfig = isset($this->configUi[$jenisTr]['myPaymentConfig']) ? $this->configUi[$jenisTr]['myPaymentConfig'] : false;
        $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $stepHistoryFields = isset($this->configUi[$jenisTr]['shortStepHistoryFields']) ? $this->configUi[$jenisTr]['shortStepHistoryFields'] : array();
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();
        $kreditLimitValidate = isset($this->configUi[$jenisTr]["kreditLimitValidate"]) ? $this->configUi[$jenisTr]["kreditLimitValidate"] : array();
        $allowPrintQr = isset($this->configUi[$jenisTr]["steps"][$jenisTrsub]["allowPrintQr"]) ? $this->configUi[$jenisTr]["steps"][$jenisTrsub]["allowPrintQr"] : 0;
        $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ? $this->configUi[$jenisTr]["shortItemsFields"] : array();
        $followUpLiveEditField = isset($this->configUi[$jenisTr]["followUpLiveEditField"]) ? $this->configUi[$jenisTr]["followUpLiveEditField"] : array();
        $allowReleaseLockerTrans = isset($this->configUi[$jenisTr]["allowReleaseLockerTrans"]) ? $this->configUi[$jenisTr]["allowReleaseLockerTrans"] : array();
        $link_releaser_trans = isset($this->configUi[$jenisTr]["link_releaser_trans"]) ? $this->configUi[$jenisTr]["link_releaser_trans"] : NULL;

        $checkerPaymentSource = isset($this->configUi[$jenisTr]["checkerPaymentSource"]) ? $this->configUi[$jenisTr]["checkerPaymentSource"] : array();

        /* ----------------------------------------------------------------------
         * deteksi mobile auto atau hanya orang tertentu,
         * diatur di heWeb mobile
         * ----------------------------------------------------------------------*/
        $isMob = isMobile_he_misc();
        // --------------------------------------------------------------
        $this->load->model("Coms/ComTransaksiKreditLimit");
        $kl = New ComTransaksiKreditLimit();
        $arrKreditLimitDataKredit = $kl->fetchBalancesKredit();
        //        arrPrintWebs($arrKreditLimitDataKredit);
        // --------------------------------------------------------------
        if (ipadd() == "202.65.117.72") {
            $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFieldsCek']) ? $this->configUi[$jenisTr]['shortHistoryFieldsCek'] : $historyFields;
        }

        if ($isMob) {
            // $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }
        $swapJenisTr = isset($this->configUi[$jenisTr]['requestCode']['masterCode']) ? $this->configUi[$jenisTr]['requestCode']['masterCode'] : array();
        $stateCode = isset($this->configUi[$jenisTr]['requestCode']['stateCode']) ? $this->configUi[$jenisTr]['requestCode']['stateCode'] : array();
        $historyFieldsPre = isset($this->configUi[$swapJenisTr]['shortHistoryFields']) ? $this->configUi[$swapJenisTr]['shortHistoryFields'] : array();
        $arrItemShowPre = isset($this->configUi[$swapJenisTr]["shortItemsFields"]) ? $this->configUi[$swapJenisTr]["shortItemsFields"] : array();
        $prePreFields = $historyFieldsPre;
        $preFollowUpLiveEditField = isset($this->configUi[$swapJenisTr]["followUpLiveEditField"]) ? $this->configUi[$swapJenisTr]["followUpLiveEditField"] : array();
        $prePreFields['state'] = "status";
        $prePreFields['action'] = "action";

        // region 5823
        $arrayOnprePre = array();
        $arrayOnprePreGroup = array();
        if (sizeof($swapJenisTr) > 0) {
            $steps = $this->configUi[$swapJenisTr]['steps'];
            $preStep = $steps;
            $arrFilters = array();
            if (sizeof($steps) > 1) {
                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $stepCodes = array();
                $jmlStep = count($steps);
                if (isset($this->accessList[$swapJenisTr]) && sizeof($this->accessList) > 0) {
                    $indsteps = "(";
                    foreach ($this->accessList[$swapJenisTr] as $stepNumber => $stepSpec) {
                        if ($stepNumber <= $jmlStep) {
                            foreach ($stepSpec as $targetCode => $filters) {
                                $indsteps .= "'$targetCode',";
                                $stepCodes[] = $targetCode;
                                if ($filters['allowFollowUp'] == "true") {
                                    $arrFilters["allowFollowUp"][] = $targetCode;
                                }
                            }
                        }
                    }
                    $indsteps = rtrim($indsteps, ",");
                    $indsteps .= ")";
                    if (sizeof($arrFilters) > 0) {
                        $tr->addFilter("next_step_code in $indsteps");
                    }
                    else {
                        //                        $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                    }
                }
                else {

                }
                //                $tr->setFilters(array());
                $tmpHist = array();
                $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
                $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
                //                $tr->addFilter("jenis='$stateCode'");// sementara yang tampil hanya stateCode
                //                if(isset($_GET["prestep"])){
                //                    $tr->addFilter("step_number='".$_GET["prestep"]."'");
                //                }
                $tr->addFilter("next_substep_code<>''");
                $tr->addFilter("sub_step_number>0");
                $tr->addFilter("valid_qty>0");
                $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
                //                cekHitam($this->db->last_query());
                //                matiHere();
                //                arrPrint($tmpHist);
                if (sizeof($tmpHist) > 0) {
                    $arrTransID = array();
                    $arrTransTopID = array();
                    $arrIdsHist = array();
                    $arrTransHist = array();
                    foreach ($tmpHist as $row) {
                        $arrTransID[] = $row->transaksi_id;
                        $arrTransTopID[] = $row->id_top;

                        if ($row->ids_his != "") {
                            $hist = blobDecode($row->ids_his);
                            foreach ($hist as $hisSpec) {
                                $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                    "step" => $hisSpec['step'],
                                    "trID" => $hisSpec['trID'],
                                    "nomer" => $hisSpec['nomer'],
                                );
                                $arrTransHist[] = $hisSpec['trID'];
                            }
                        }
                    }

                    // region membaca payment source label uang muka, untuk kas yang harus diterima dulu.
                    $psrc = New MdlTransaksi();
                    $psrc->setFilters(array());
                    $psrc->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $psrc->addFilter("sisa>$sisa_pembayaran");
                    $psrcTmp = $psrc->lookUpAllPaymentSrc()->result();
                    //                    cekBiru($this->db->last_query());
                    $psrcData = array();
                    if (sizeof($psrcTmp) > 0) {
                        foreach ($psrcTmp as $psrcSpec) {
                            $psrcData[$psrcSpec->transaksi_id] = array(
                                "extern_id" => $psrcSpec->extern_id,
                                "extern_nama" => $psrcSpec->extern_nama,
                                "nomer" => $psrcSpec->nomer,
                                "terbayar" => $psrcSpec->terbayar,
                                "sisa" => $psrcSpec->sisa,
                                "label" => "* Transaksi belum dibayar/belum lunas.",
                            );
                        }
                    }
                    //                    cekMerah(count($psrcTmp));
                    //                    matiHere(__LINE__);
                    // endregion membaca payment source label uang muka, untuk kas yang harus diterima dulu.

                    //-------------------------
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $tmpTrData = $tr->lookupDetailTransaksiAll($arrTransID);
                    //-------------------------

                    $tmpReg_result = array();
                    $trReg = new MdlTransaksi();
                    $trReg->setFilters(array());
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();

                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            //                    arrPrintWebs($regRow);
                            foreach ($regRow as $key_reg => $val_reg) {
                                if ($val_reg == null) {
                                    $val_reg = blobEncode(array());
                                }
                                if ($key_reg != "transaksi_id") {
                                    $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                                }
                            }

                        }
                    }

                    //region tambahan untuk view rincian dikolom
                    $detailShow = array();
                    if (count($arrItemShowPre) > 0) {
                        $tr->setFilters(array());
                        $detailShow = $tr->lookupDetailTransaksi($arrTransID);
                    }

                    //                    matiHere(__LINE__);
                    //endregion

                    foreach ($tmpHist as $row) {
                        if (sizeof($pairRegistries) > 0) {
                            if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                                foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                                    switch ($param) {
                                        case "main":
                                            foreach ($eReg as $k => $v) {
                                                if (($k != null) && !isset($row->$k)) {
                                                    $row->$k = $v;
                                                }
                                            }
                                            break;
                                        case "items":
                                            if (sizeof($extHistoryFields2) > 0) {
                                                foreach ($extHistoryFields2 as $k1 => $v1) {
                                                    if (is_array($v1)) {
                                                        $kolom = $v1['kolom'];
                                                        $format = $v1['format'];
                                                        if (($k1 != null) && !isset($row->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $row->$k1 = $tmpDetail;
                                                        }
                                                    }
                                                    else {
                                                        if (($k1 != null) && !isset($row->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $row->$k1 = $tmpDetail;
                                                        }
                                                    }
                                                }
                                            }
                                            break;
                                    }
                                }
                            }
                        }

                        $tmp = array();
                        foreach ($prePreFields as $fName => $fLabel) {
                            //                            $tmp[$fName] = isset($row->$fName) ? formatField($fName, $row->$fName) : formatField($fName, 0);
                            if (isset($row->$fName)) {
                                if (is_numeric($row->$fName)) {
                                    if (!isset($sumFooter[$fName])) {
                                        $sumFooter[$fName] = 0;
                                    }
                                    $sumFooter[$fName] += $row->$fName;
                                }
                            }

                            if (is_array($fLabel)) {
                                $hisStep = isset($fLabel['step']) ? $fLabel['step'] : 0;
                                $hisKey = isset($fLabel['key']) ? $fLabel['key'] : 0;

                                if (isset($row->ids_his)) {
                                    if ($hisKey == "nomer") {
                                        $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                        if ($returnVal == "") {
                                            $tmp[$fName] = "-";
                                        }
                                        else {
                                            $tmp[$fName] = $returnVal;
                                        }
                                    }
                                    else {
                                        $ids_his_decode = blobDecode($row->ids_his);
                                        if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                            $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                        }
                                        else {
                                            $tmp[$fName] = "-";
                                        }
                                    }
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                            else {
                                if (isset($preFollowUpLiveEditField[$fName])) {
                                    $disabled_hold = "";
                                    $add_note_title = "";
                                    if (isset($transaksiHold[$row->transaksi_id])) {
                                        $hold_name = $transaksiHoldLabel[$row->transaksi_id];
                                        $disabled_hold = "disabled";
                                        $add_note_title = "sedang difollowup oleh ($hold_name) fitur update tidak dapat digunakan";
                                        /**
                                         * blocking menghindari sabotase
                                         * - transaksi difollowup up oleh orang lain
                                         * - transaksi sudah masuk shopingcart distribusi
                                         * - transaksi sudah antri followup distribusi/otorisasi distribusi
                                         */
                                    }
                                    $nextStepNume = ($row->next_substep_num);
                                    $disabled_allow = "";
                                    if (isset($this->configUi[$jenisTr]['steps'][$nextStepNume])) {
                                        if (isset($this->accessList[$jenisTr])) {
                                            if (isset($this->accessList[$jenisTr][$nextStepNume][$nextStepCode]["allowFollowUp"])) {

                                            }

                                        }
                                        else {
                                            if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNume]['userGroup'], $this->session->login['membership'])) {

                                            }
                                            else {
                                                $disabled_allow = "disabled";

                                            }
                                        }
                                    }
                                    else {
                                        $disabled_allow = "disabled";
                                    }
                                    $selector = $preFollowUpLiveEditField[$fName]["selector"];
                                    $key_id = $preFollowUpLiveEditField[$fName]["key"];
                                    $mdlName = $preFollowUpLiveEditField[$fName]["mdlName"];
                                    $this->load->model("Mdls/" . $mdlName);
                                    $m = new $mdlName();
                                    $tmpEditFields = $m->lookUpAll()->result();
                                    $editField = "<select $disabled_hold $disabled_allow class='form-control' title='$add_note_title' id='" . $fName . "_" . $row->id . "' onchange=\"document.getElementById('result').src='" . MODUL_PATH . $selector . "?key=$fName&jenistr=$swapJenisTr&trid=" . $row->transaksi_id . "&val=' + encodeURIComponent(this.value);\">";
                                    foreach ($tmpEditFields as $ii => $ixTmpData) {
                                        $selected = $ixTmpData->id == $row->$key_id ? "selected" : "";
                                        $editField .= "<option $selected value='" . $ixTmpData->id . "'>" . $ixTmpData->nama . "</option>";
                                    }
                                    $editField .= "</select>";


                                    //                            if($allow_live_edit){
                                    $tmp[$fName] = $editField;
                                    //                            }

                                }
                                else {
                                    $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                                }

                            }

                            if ($fName == "no") {
                                $tmp[$fName] = formatField_he_format($fName, $numb);
                            }
                        }
                        $showPoStatus = isset($this->configUi[$swapJenisTr]['showPoStatus']) ? $this->configUi[$swapJenisTr]['showPoStatus'] : array();
                        $cekStateLocation = ($this->session->login['cabang_id'] > 0) ? "cabang" : "pusat";
                        //region show items
                        $tblItems = "";
                        $pakai_ini = 0;
                        if ($pakai_ini == 1) {
                            if (sizeof($arrItemShow) > 0) {
                                $tblItems .= "<table class='table table-bordered'>";
                                $tblItems .= "<tr>";
                                $tblItems .= "<th>No</th>";
                                foreach ($arrItemShow as $k => $iLabel) {
                                    if (is_array($iLabel)) {
                                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                                    }
                                    else {
                                        $tblItems .= "<th >$iLabel</th>";
                                    }
                                }
                                $tblItems .= "</tr>";


                                if (isset($detailShow[$row->transaksi_id])) {
                                    $numb = 0;
                                    foreach ($detailShow[$row->transaksi_id] as $row_1) {
                                        $numb++;
                                        $tblItems .= "<tr>";
                                        $tblItems .= "<td>$numb</td>";
                                        foreach ($arrItemShow as $k => $iLabel) {
                                            $class = "";
                                            if (is_numeric($row->$k)) {
                                                $class = "text-center";
                                            }
                                            $tblItems .= "<td>" . $row_1->$k . "</td>";
                                        }
                                        $tblItems .= "</tr>";
                                    }

                                }


                                $tblItems .= "</table >";

                            }
                        }
                        else {
                            if (isset($this->configUi[$jenisTr]["shortItemsFieldsSteps"][$row->sub_step_number])) {
                                $arrItemShowPre = $this->configUi[$jenisTr]["shortItemsFieldsSteps"][$row->sub_step_number];
                            }
                            $tmpTrDataTambahan = isset($tmpTrData[$row->transaksi_id]) ? $tmpTrData[$row->transaksi_id] : array();
                            $detail = viewDetailTransaksi($tmpReg_result[$row->transaksi_id], $arrItemShowPre, $row->jenis_master, true, $tmpTrDataTambahan);
                            $tblItems = $detail;
                        }

                        $tmp['item_fields'] = $tblItems;
                        //endregion
                        if ($row->sub_step_number > 0) {

                            $tmp['state'] = "<div class='panel panel-warning' style='padding: 3px;margin-bottom: 5px;' ><span style='color:" . $this->configUi[$swapJenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$swapJenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                            $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $swapJenisTr) . "</div>";

                            // cek PO
                            $arrTransactionSource = array();
                            $id_master = $row->id_master;
                            $id_top1 = $row->id_top;
                            $this->load->model("MdlTransaksi");
                            $l = new MdlTransaksi();
                            $l->setFilters(array());
                            $l->addFilterJoin("transaksi_data.valid_qty>0");
                            $l->addFilter("id_master='" . $id_master . "'");
                            $l->addFilter("jenis_master='" . $swapJenisTr . "'");
                            $l->addFilter("link_id=0");
                            $tmpTS = $l->lookupJoined();

                            $arrsub_step_number = array();
                            $arrstep_avail = array();
                            $arrext_blob = array();
                            $arrjenis_master = array();
                            $arrtransaksi_no = array();
                            $arrketerangan = array();

                            if (sizeof($tmpTS) > 0) {
                                foreach ($tmpTS as $kk => $arVL) {
                                    $id = $arVL->id;
                                    $id_master = $arVL->id_master;
                                    $id_top2 = $arVL->id_top;
                                    $produk_id = $arVL->produk_id;
                                    $arrTransactionSource[$id_top1] = $produk_id;
                                    $arrsub_step_number[$id_top1] = $arVL->sub_step_number;
                                    $arrstep_avail[$id_top1] = $arVL->step_avail;
                                    $arrext_blob[$id_top1][$kk] = isset($arVL->ext_blob) ? ($arVL->ext_blob != "" ? blobDecode($arVL->ext_blob) : "") : "";
                                }

                                if (sizeof($arrext_blob[$id_top1]) > 0) {
                                    foreach ($arrext_blob[$id_top1] as $ky => $aVal) {
                                        if ($aVal != "") {
                                            foreach ($arrext_blob[$id_top1][$ky]['static'] as $numb => $numData) {
                                                $arrtransaksi_no[$id_top1][$numb] = formatField_he_format("nomer", $numb);
                                                $arrjenis_master[$id_top1][$numb] = $numData['jenis'];
                                                $arrketerangan[$id_top1][$numb] = $numData['keterangan'];
                                            }
                                        }
                                    }
                                }
                            }

                            if ($arrext_blob[$id_top1][$ky] != "") {
                                $tmp['state'] .= "<div class='panel panel-danger bg-green' style='padding: 3px;margin-bottom: 5px;'>";
                                if ($cekStateLocation == "cabang") {
                                    $tmp['state'] .= "<div><b>diproses oleh PUSAT</b></div>";
                                }
                                if (sizeof($arrtransaksi_no[$id_top1]) > 0) {
                                    foreach ($arrtransaksi_no[$id_top1] as $numb_) {
                                        $tmp['state'] .= "<div><span class='fa fa-check-circle text-warning'></span> " . $numb_ . "</div>";
                                    }
                                }
                                $tmp['state'] .= "</div>";
                            }

                        }
                        else {
                            $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                        }

                        $tmp['action'] = "";
                        $nextStepNum = ($row->next_substep_num);
                        $currentStepNum = ($row->sub_step_number);
                        $currentStepCode = ($row->jenis);
                        $nextStepCode = ($row->next_step_code);

                        if ($row->sub_step_number > 0) {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                        }

                        if (isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum])) {
                            if (isset($this->accessList[$jenisTr])) {
                                if (isset($this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                                    $allowFollowup = $this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                                    $actionLabel = $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                            }
                            else {
                                if (in_array($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                                    $allowFollowup = true;
                                    $actionLabel = $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                                else {
                                    $allowFollowup = true;
                                    $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                                }
                            }
                        }

                        if ($allowFollowup) {
                            $allowJoin = isset($this->configUi[$swapJenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$swapJenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                            $stepLabel = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['label'] : "";
                            $isCancelPacking = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                            $allowCancel = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowCancel'] : false;
                            $allowScaner = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowScaner']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowScaner'] : false;
                            $allowNextStepOtorisasi = isset($this->configUi[$swapJenisTr]['allowNextStepOtorisasi'][$nextStepNum]) ? $this->configUi[$swapJenisTr]['allowNextStepOtorisasi'][$nextStepNum] : array();

                            $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                            $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$swapJenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                            if ($isMob) {
                                $btn_block = "";
                            }
                            else {
                                $btn_block = "btn-block";
                            }
                            $class_button = "btn-primary";
                            $numberValidate = $row->step_number + 1;
                            $disabled_button_kreditlimit = "";
                            $disabled_button = "";
                            $disabled_kirim = "";
                            $disabled_hold = "";
                            $add_note = "";
                            $strPengirim = "";
                            $disabled_button_reject = "";
                            if (isset($psrcData[$row->transaksi_id])) {
                                $disabled_button = "disabled";
                                //                        $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                                //                        $class_button = "btn-default";
                                $class_button = "btn-primary";
                                $actionLabel = "menunggu pelunasan";


                                // belum dibayar
                                if ($psrcData[$row->transaksi_id]["sisa"] > $sisa_pembayaran) {
                                    $disabled_button = "disabled";
                                    $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                                    $class_button = "btn-primary";
                                    $actionLabel = "menunggu pelunasan";
                                }
                                if ($psrcData[$row->transaksi_id]["sisa"] <= $sisa_pembayaran) {
                                    $disabled_button = "";
                                    $disabled_button_reject = "disabled";
                                    $add_note .= "<br><button disabled class='btn btn-success btn-block btn-sm fa fa-money' style='margin-top:5px;'> LUNAS</button>";
                                    $class_button = "btn-primary";
                                    $actionLabel = "siap dikirim dari cabang";
                                }

                            }
                            if (isset($kreditLimitValidate[$numberValidate])) {
                                if (isset($arrKreditLimitDataKredit[$row->customers_id]["kredit"]) && ($arrKreditLimitDataKredit[$row->customers_id]["kredit"] > 0)) {
                                    $disabled_button_kreditlimit = "disabled";
                                    $add_note .= "<br><span class='meta'>" . $kreditLimitValidate[$numberValidate]["label"] . "</span>";
                                    //                            $class_button = "btn-default";
                                    $class_button = "btn-primary";
                                }
                            }
                            if ($row->kirim_metode_id == 1) {
                                if (($row->pengirim_id == 0) || ($row->pengirim_id == null)) {
                                    $followupPengirimLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/doFollowupPengirim/$swapJenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "?pengirim=pengirim');";
                                    $disabled_kirim = ($row->step_number >= 3) ? "disabled" : "";
                                    $add_note .= "<br><span class='meta'>Pengirim belum terdaftar pada transaksi ini.</span>";
                                    //                            $strPengirim = "<div class='input-group'>";
                                    //                            $strPengirim .= "<button class='btn $btn_block btn-warning pull-right' title='turn this entry into $stepLabel'
                                    //                                href='javascript:void(0)'
                                    //                                onClick =\"top.open_holdon();$followupPengirimLink\">Ambil untuk dikirim</button>";
                                    //                            $strPengirim .= "</div class='input-group'>";
                                }
                            }

                            if ($this->session->login["employee_type"] == "employee_kirim") {
                                $tmp['action'] = "";
                            }
                            else {
                                //-------------------------
                                if ($row->cabang_id > 0) {// hanya bila transaksi cabang
                                    if (($row->gudang_status_jenis != NULL) && ($row->gudang_status_jenis == "pusat")) {
                                        $allowScaner = false;
                                        if (isset($allowNextStepOtorisasi[$row->gudang_status_jenis])) {
                                            $disabled_button = $allowNextStepOtorisasi[$row->gudang_status_jenis];
                                            $add_note .= "<br><span class='meta'>* " . $allowNextStepOtorisasi["label"] . "</span>";
                                            $actionLabel = "* siap dikirim dari dc/pusat";
                                            //                                            $disabled_hold = "disabled";//dimatiin dulu
                                            if (isset($psrcData[$row->transaksi_id])) {
                                                $actionLabel = "menunggu pelunasan";

                                            }
                                            if ($currentStepNum == "3") {
                                                $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                                            }
                                        }
                                    }
                                }
                                //-------------------------
                                if (isset($transaksiHold[$row->transaksi_id])) {
                                    $disabled_hold = "disabled";
                                    $add_note .= "<br><span class='meta'>* Barang siap dikirim.<br>Edit/Reject SO tidak bisa digunakan.</span>";
                                }
                                //-------------------------
                                $tmp['action'] = "<div class='input-group'>";
                                if ($allowScaner == false || $isMob == 1) {
                                    //                            $disabled_button
                                    //                                $disabled_button_kreditlimit
                                    //                                $disabled_kirim
                                    //                            cekHere("[$disabled_button] [$disabled_button_kreditlimit] [$disabled_kirim]");

                                    //                                    if ($row->transaksi_id == 6399) {
                                    //                                        cekHere("[$disabled_hold]");;
                                    //                                    }
                                    $tmp['action'] .= "<button class='btn $btn_block $class_button' title='turn this entry into $stepLabel' 
                                $disabled_hold
                                href='javascript:void(0)' 
                                onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</button>";
                                }
                                else {
                                    $actionLabel = "QR untuk Handphone";
                                    $targetFollowupLink = "followupDariHp";
                                    $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$swapJenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                                    $fpLink = MODUL_PATH . "FollowUp/$targetFollowupLink/$swapJenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "/" . $jenisTr;
                                    $followupLink = "BootstrapDialog.show(
                                   {
                                        title:'" . $actionLabel . "',
                                        message: $('<div></div>').load('" . $fpLink . "'),
                                        draggable:true,
                                        closable:true,
                            }
                                    );";
                                    $tmp['action'] .= "<button class='btn btn-primary btn-block' $disabled_button title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</button>";
                                }
                                $tmp['action'] .= isset($strPengirim) ? $strPengirim : "";
                                $tmp['action'] .= "</div class='input-group'>";
                                $tmp['action'] .= $add_note;

                                if ($req_cancel_qty > 0 && $valid_qty == 0) {
                                    $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
                                    $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>menuggu approve cancel</button>";
                                    //                            $tmp['action'] .= "<button type='button' class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>x</button>";
                                    $tmp['action'] .= "</div>";
                                }
                            }
                        }
                        else {

                        }

                        //EDIT------------------------------
                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            $arrStepAllow = array(1, 2);
                            if (in_array($row->sub_step_number, $arrStepAllow)) {
                                if ($allowFollowup) {
                                    $transaksiID_reject = $row->transaksi_id;
                                    $evPre = evaluatePreProcessors_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                    $evPost = evaluatePostProcessors_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                    $evCom = evaluateComponents_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                    $evMaster = evaluateMain_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                    //                        cekHitam("evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                                    if ($evPre == null && $evPost == null && $evCom == null && $evMaster == null) {
                                        $transaksiID_edit = $row->transaksi_id;
                                        $transaksiCustomerNama_f = "<strong>" . $row->customers_nama . "</strong>";
                                        $transaksiNomer = $row->nomer;
                                        $transaksiNomer_f = formatField_he_format("nomer_nolink", $row->nomer);
                                        //                                $link_edit = MODUL_PATH . "FollowUp/doPreEdit/" . $swapJenisTr . "/$transaksiID_edit/$currentStepNum?mode=autoPrePreview";
                                        $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $swapJenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=edit";
                                        $actionLabel_edit = "Edit";
                                        $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='background-color:#000000;color:#ffffff;'
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
                                    }
                                }
                            }
                        }
                        //------------------------------

                        //REJECT------------------------------
                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            //                            $arrStepAllow = array(1);
                            $arrStepAllow = array(1, 2, 3);
                            if (in_array($row->sub_step_number, $arrStepAllow)) {
                                $evPre = evaluatePreProcessors_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                $evPost = evaluatePostProcessors_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                $evCom = evaluateComponents_he_menu($swapJenisTr, $currentStepNum, $this->configCore[$swapJenisTr], $this->configUi[$swapJenisTr]);
                                //                                cekHere("[$evPre -- $evPost -- $evCom]");
                                if ($evPre == null && $evPost == null && $evCom == null) {
                                    $transaksiID_reject = $row->transaksi_id;
                                    $masterRevertStep = ($currentStepNum - 1);
                                    $childRevertStep = -($currentStepNum);

                                    if ($this->reject == true) {
                                        $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $swapJenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=reject";
                                        $actionLabel_edit = "Reject 1 step";
                                        $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                        $disabled_hold $disabled_button_reject
                                        onclick =\"document.getElementById('result').src='$link_reject'\">" . $actionLabel_edit . " </button>";
                                    }
                                    if ($this->reject_all == true) {
                                        $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $swapJenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=rejectall";
                                        $actionLabel_edit = "Reject all step";
                                        $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                        style='background-color:#000000;color:#ffffff;'
                                        $disabled_hold $disabled_button_reject
                                        onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";

                                    }
                                }
                            }
                        }
                        //------------------------------
                        //FULLFILLMENT------------------------------
                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            $xShipmentBtn = array();
                            $xShipmentConfig = isset($this->configUi[$swapJenisTr]['xShipmentConfig']) ? $this->configUi[$swapJenisTr]['xShipmentConfig'] : array();
                            if ($xShipmentConfig) {
                                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                                foreach ($xShipmentConfig as $num => $val) {
                                    if ($num == $currentStepNum) {
                                        $allowed = false;
                                        foreach ($val['allowedGroups'] as $group) {
                                            if (in_array($group, $mems)) {
                                                $allowed = true;
                                                break;
                                            }
                                        }
                                        //                                cekHitam("[allowed: $allowed] [currentStepNum: $currentStepNum]");
                                        if (($val['enabled'] == true) && ($allowed == true)) {
                                            $labelx = $val['label'];
                                            $targetJenisMaster = $val['targetJenisMaster'];
                                            $warningx = $val['warning'];
                                            $transaksiID_fullfill = $row->transaksi_id;
                                            $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $swapJenisTr . "/$transaksiID_fullfill/$nextStepNum/" . $row->sub_step_number . "?getmode=fullfill";
                                            //                                    cekHere($link_reject);
                                            //                                    $xShipmentBtn_str = array(
                                            //                                        "label" => $labelx,
                                            ////                                        "targetUrl" => MODUL_PATH . __CLASS__ . "/preCancelPacking/$jenisTr/$no/$targetJenisMaster/$currentStepNum",
                                            //                                        "targetUrl" => $link_reject,
                                            //                                        "warning" => $warningx,
                                            //                                    );
                                            //                                    $tmp['action'] .= $xShipmentBtn_str;
                                            $tmp['action'] .= "<button class='btn btn-warning btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                    onclick =\"document.getElementById('result').src='$link_reject'\">" . $labelx . " </button>";
                                        }
                                        else {

                                        }
                                    }
                                }
                            }
                        }
                        //------------------------------

                        $arrayOnprePre[] = $tmp;
                        $arrayOnprePreGroup[$currentStepNum][] = $tmp;
                    }
                }
            }
            else {
                $arrayOnprePre = array();
            }
        }
        else {
            $arrayOnprePre = array();
        }
        //endregion prePre

        //region lookup on-going transactions 5822
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->configUi[$jenisTr]['steps'];

        $arrFilters = array();
        $this->load->model("Mdls/MdlTransaksiImageReference");
        $this->load->model("MdlTransaksi");
        $ti = new MdlTransaksiImageReference();
        $tr = new MdlTransaksi();
        $stepCodes = array();
        $jmlStep = count($steps);

        if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList) > 0) {
            $indsteps = "(";
            foreach ($this->accessList[$jenisTr] as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    foreach ($stepSpec as $targetCode => $filters) {

                        $stepCodes[] = $targetCode;
                        if ($filters['allowCreate'] == "true") {
                            //berarti step 1 boleh edit
                            $next = $stepNumber + 1;
                            $target2 = $steps[$next]["target"];
                            $indsteps .= "'$target2',";
                            $arrFilters["allowFollowUp"][] = $target2;
                        }
                        if ($filters['allowFollowUp'] == "true") {
                            $indsteps .= "'$targetCode',";
                            $arrFilters["allowFollowUp"][] = $targetCode;
                        }

                    }
                    //                    foreach ($stepSpec as $targetCode => $filters) {
                    //                        $indsteps .= "'$targetCode',";
                    //                        $stepCodes[] = $targetCode;
                    //                        if ($filters['allowFollowUp'] == "true") {
                    //                            $arrFilters["allowFollowUp"][] = $targetCode;
                    //                        }
                    //
                    //                    }
                }
            }
            $indsteps = rtrim($indsteps, ",");
            $indsteps .= ")";
            if (sizeof($arrFilters) > 0) {
                $tr->addFilter("next_step_code in $indsteps");
            }
            else {
                $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                $tr->addFilter("next_step_code!=''");
            }
        }
        else {

        }

        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        if ($this->session->login['employee_type'] == "employee_freelance") {
            $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
        }
        if ($this->session->login['employee_type'] == "employee_kirim") {
            $tr->addFilter("kirim_metode_id='1'");
            //            $sesionReplacer = array();
        }

        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        //arrPrint($sesionReplacer);
        showLast_query("biru");
        $arrayOnprogress = array();
        $arrayOnprogressGroup = array();
        $arrayOnprogressPartialMark = array();
        $arrayOnprogressGroupPartialMark = array();

        $extData = "";
        $extact = "";
        $selectProcessor = "";
        if (sizeof($tmpHist) > 0) {
            $arrTransID = array();
            $arrTransTopID = array();
            $arrIdsHist = array();
            $arrTransHist = array();
            $arrNextAction = array();
            $arrID_master = array();
            foreach ($tmpHist as $row) {
                $arrTransID[] = $row->transaksi_id;
                $arrID_master[$row->id_master] = $row->id_master;
                $arrTransTopID[] = $row->id_top;
                $arrNextAction[$row->transaksi_id] = array(
                    "next_step_num" => $row->next_substep_num,
                    "next_step_code" => $row->next_substep_code,
                );
                if ($row->ids_his != "") {
                    $hist = blobDecode($row->ids_his);
                    foreach ($hist as $hisSpec) {
                        $arrIdsHist[$row->id][$hisSpec['step']] = array(
                            "step" => $hisSpec['step'],
                            "trID" => $hisSpec['trID'],
                            "nomer" => $hisSpec['nomer'],
                        );
                        $arrTransHist[] = $hisSpec['trID'];
                    }
                }
                //                    arrPrint($row);
            }

            // region locker transaksi
            $this->load->model("Mdls/MdlLockerTransaksi");
            $lt = New MdlLockerTransaksi();
            $lt->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $lt->addFilter("state='hold'");
            $lt->addFilter("jumlah='1'");
            $ltTmp = $lt->lookupAll()->result();
            $transaksiHold = array();
            $transaksiHoldLabel = array();
            $arrTransaksiHoldLabel = array();
            if (sizeof($ltTmp) > 0) {
                foreach ($ltTmp as $ltSpec) {
                    $hold_oleh_id = $ltSpec->oleh_id;
                    if ($hold_oleh_id == my_id()) {

                    }
                    else {
                        $transaksiHold[$ltSpec->transaksi_id] = $ltSpec->transaksi_id;
                        $transaksiHoldLabel[$ltSpec->transaksi_id] = $ltSpec->oleh_nama;

                        $hold_nama = $ltSpec->oleh_nama;
                        $hold_dtime = indonesian_date($ltSpec->last_access);
                        $pic_releaser = implode(",", $allowReleaseLockerTrans);
                        $hold_labels = "Status Transaksi dikunci oleh $hold_nama pada $hold_dtime. ";
                        if (!array_key_exists(my_id(), $allowReleaseLockerTrans)) {
                            $hold_labels .= "<br>Hubungi $pic_releaser untuk membuka transaksi ini. ";
                        }
                        elseif (array_key_exists(my_id(), $allowReleaseLockerTrans)) {
                            $link_releaser_locker_trans = $link_releaser_trans . $this->jenisTr . "/?trid=" . $ltSpec->transaksi_id;
                            $hold_labels .= "<br><button id='btn_releaser_locker_trans' class='btn btn-sm btn-warning'
                            oonclick=\"$('#result').load('$link_releaser_locker_trans');\"
                            onclick=\"confirm_alert_result_disabled('Peringatan...', 'Transaksi akan dibuka untuk otorisasi', '$link_releaser_locker_trans', 'OK', this.id);\"
                            >";// tombol realeaser locker transaksi
                            $hold_labels .= "Buka Transaksi Ini";// tombol realeaser locker transaksi
                            $hold_labels .= "</button>";// tombol realeaser locker transaksi
                        }
                        else {
                            $hold_labels .= "";
                        }
                        $arrTransaksiHoldLabel[$ltSpec->transaksi_id] = $hold_labels;
                    }
                }
            }
            // endregion locker transaksi

            // region membaca payment source label uang muka, untuk kas yang harus diterima dulu.
            $psrc = New MdlTransaksi();
            $psrc->setFilters(array());
            $psrc->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $psrc->addFilter("sisa>$sisa_pembayaran");
            $psrcTmp = $psrc->lookUpAllPaymentSrc()->result();
            $psrcData = array();
            if (sizeof($psrcTmp) > 0) {
                foreach ($psrcTmp as $psrcSpec) {
                    $psrcData[$psrcSpec->transaksi_id] = array(
                        "extern_id" => $psrcSpec->extern_id,
                        "extern_nama" => $psrcSpec->extern_nama,
                        "nomer" => $psrcSpec->nomer,
                        "terbayar" => $psrcSpec->terbayar,
                        "sisa" => $psrcSpec->sisa,
                        "label" => "* Transaksi belum dibayar/belum lunas.",
                    );
                }
            }
            // endregion membaca payment source label uang muka, untuk kas yang harus diterima dulu.

            // region $checkerPaymentSource pajak hadiah
            if (sizeof($checkerPaymentSource) > 0) {
                if (isset($checkerPaymentSource["enabled"]) && ($checkerPaymentSource["enabled"] == true)) {
                    $targetJenisCek = $checkerPaymentSource["target_jenis"];
                    $psrcd = New MdlTransaksi();
                    $psrcd->setFilters(array());
                    $psrcd->addFilter("transaksi_id in ('" . implode("','", $arrID_master) . "')");
                    $psrcd->addFilter("target_jenis=$targetJenisCek");
//                    $psrcd->addFilter("sisa>$sisa_pembayaran");
                    $psrcdTmp = $psrcd->lookUpAllPaymentSrc()->result();
                    $psrcdData = array();
                    if (sizeof($psrcdTmp) > 0) {
                        foreach ($psrcdTmp as $psrcdSpec) {
                            $psrcdData[$psrcdSpec->transaksi_id] = array(
                                "extern_id" => $psrcdSpec->extern_id,
                                "extern_nama" => $psrcdSpec->extern_nama,
                                "nomer" => $psrcdSpec->nomer,
                                "terbayar" => $psrcdSpec->terbayar,
                                "sisa" => $psrcdSpec->sisa,
                                "label" => "*Uang pelunasan pajak hadiah belum diterima/belum lunas.",
                            );
                            if ($psrcdSpec->sisa > $sisa_pembayaran) {
                                $psrcdData[$psrcdSpec->transaksi_id]["label"] = "*Uang pelunasan pajak hadiah belum diterima/belum lunas.";
                            }
                            elseif ($psrcdSpec->sisa <= $sisa_pembayaran) {
                                $psrcdData[$psrcdSpec->transaksi_id]["label"] = "*Uang pelunasan pajak hadiah sudah diterima/lunas.";
                            }
                        }
                    }
                }
            }
            // endregion $checkerPaymentSource pajak hadiah


            //-------------------------
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tmpTrData = $tr->lookupDetailTransaksiAll($arrTransID);
            //-------------------------

            $tmpReg_result = array();
            if (sizeof($pairRegistries) > 0) {
                $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());
                //            $trReg->addFilter("param='main'");
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        //                    arrPrintWebs($regRow);
                        foreach ($regRow as $key_reg => $val_reg) {
                            if ($val_reg == null) {
                                $val_reg = blobEncode(array());
                            }
                            if ($key_reg != "transaksi_id") {
                                $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                            }
                        }

                    }
                }
            }

            $relImages = array();
            if (count($arrID_master) > 0) {

                $ti->addFilter("id_master in ('" . implode("','", $arrID_master) . "')");
                $tmpImages = $ti->lookUpAll()->result();
                if (count($tmpImages) > 0) {
                    foreach ($tmpImages as $tmpImages_0) {
                        $relImages[$tmpImages_0->id_master] = array(
                            "nama" => $tmpImages_0->nama,
                            "link_img" => $tmpImages_0->cdn_link,
                            "dtime" => $tmpImages_0->dtime,
                        );
                    }
                }
                //                arrPrint($tmpImages);
                //                matiHEre();
            }

            $arrNextPIC = callNextPIC($arrNextAction);

            if (sizeof($arrIdsHist) > 0) {
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                $tmpTransHist = $tr->lookupAll()->result();


                if (sizeof($tmpTransHist) > 0) {
                    foreach ($tmpTransHist as $histSpec) {
                        $tmpTransHist_result[$histSpec->id] = array(
                            "oleh_id" => $histSpec->oleh_id,
                            "oleh_nama" => $histSpec->oleh_nama,
                        );
                    }
                }

                foreach ($arrIdsHist as $trID => $histSpec) {
                    foreach ($histSpec as $step => $detailSpec) {
                        if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                            $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                        }
                        $arrTransMainHist[$trID][$step] = $detailSpec;
                    }
                }
            }

            //region tambahan untuk view rincian dikolom
            $detailShow = array();
            if (count($arrItemShow) > 0) {
                $detailShow = $tr->lookupDetailTransaksi($arrTransID);
            }


            //endregion


            $numb = 0;
            foreach ($tmpHist as $row) {
                $transaksi_idd = $row->transaksi_id;
                $extHistoryFields2 = isset($arrExtHistoryFields2[$row->step_number]) ? $arrExtHistoryFields2[$row->step_number] : array();
                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                        foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                            switch ($param) {
                                case "main":
                                    foreach ($eReg as $k => $v) {
                                        if (($k != null) && !isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                    break;
                                case "items":
                                    if (sizeof($extHistoryFields2) > 0) {
                                        foreach ($extHistoryFields2 as $k1 => $v1) {
                                            if (is_array($v1)) {
                                                $kolom = $v1['kolom'];
                                                $format = $v1['format'];
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                            else {
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }

                $numb++;
                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (isset($row->$fName)) {
                        if (is_numeric($row->$fName)) {
                            if (!isset($sumFooter[$fName])) {
                                $sumFooter[$fName] = 0;
                            }
                            $sumFooter[$fName] += $row->$fName;
                        }
                    }

                    if (is_array($fLabel)) {
                        $hisStep = isset($fLabel['step']) ? $fLabel['step'] : 0;
                        $hisKey = isset($fLabel['key']) ? $fLabel['key'] : 0;

                        if (isset($row->ids_his)) {
                            if ($hisKey == "nomer") {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $ids_his_decode = blobDecode($row->ids_his);
                                if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                    $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                        }
                        else {
                            $tmp[$fName] = "-";
                        }
                    }
                    else {
                        //                        arrPrint($followUpLiveEditField);
                        //                        matiHere();
                        if (isset($followUpLiveEditField[$fName])) {
                            $disabled_hold = "";
                            $add_note_title = "";
                            if (isset($transaksiHold[$row->transaksi_id])) {
                                $hold_name = $transaksiHoldLabel[$row->transaksi_id];
                                $disabled_hold = "disabled";
                                $add_note_title = "sedang difollowup oleh ($hold_name) fitur update tidak dapat digunakan";
                                /**
                                 * blocking menghindari sabotase
                                 * - transaksi difollowup up oleh orang lain
                                 * - transaksi sudah masuk shopingcart distribusi
                                 * - transaksi sudah antri followup distribusi/otorisasi distribusi
                                 */
                            }
                            $nextStepNume = ($row->next_substep_num);
                            $disabled_allow = "";
                            if (isset($this->configUi[$jenisTr]['steps'][$nextStepNume])) {
                                if (isset($this->accessList[$jenisTr])) {
                                    if (isset($this->accessList[$jenisTr][$nextStepNume][$nextStepCode]["allowFollowUp"])) {

                                    }

                                }
                                else {
                                    if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNume]['userGroup'], $this->session->login['membership'])) {

                                    }
                                    else {
                                        $disabled_allow = "disabled";

                                    }
                                }
                            }
                            else {
                                $disabled_allow = "disabled";
                            }
                            $selector = $followUpLiveEditField[$fName]["selector"];
                            $key_id = $followUpLiveEditField[$fName]["key"];
                            $mdlName = $followUpLiveEditField[$fName]["mdlName"];
                            $this->load->model("Mdls/" . $mdlName);
                            $m = new $mdlName();
                            $tmpEditFields = $m->lookUpAll()->result();
                            $editField = "<select $disabled_hold $disabled_allow class='form-control' title='$add_note_title' id='" . $fName . "_" . $row->id . "' onchange=\"document.getElementById('result').src='" . MODUL_PATH . $selector . "?key=$fName&jenistr=$jenisTr&trid=" . $row->transaksi_id . "&val=' + encodeURIComponent(this.value);\">";
                            foreach ($tmpEditFields as $ii => $ixTmpData) {
                                $selected = $ixTmpData->id == $row->$key_id ? "selected" : "";
                                $editField .= "<option $selected value='" . $ixTmpData->id . "'>" . $ixTmpData->nama . "</option>";
                            }
                            $editField .= "</select>";


                            //                            if($allow_live_edit){
                            $tmp[$fName] = $editField;
                            //                            }


                            //                            $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                            //                            matiHere();
                        }
                        else {
                            $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                        }

                    }

                    if ($fName == "no") {
                        $tmp[$fName] = formatField_he_format($fName, $numb);
                    }

                }

                if (sizeof($row->cancel_packing_source_id) > 0) {
                    //                    arrPrint($row->cancel_packing_source_id);
                    $trx = new MdlTransaksi();
                    $trx->addFilter("id='" . $row->cancel_packing_source_id . "'");
                    $tmpTrx = $trx->lookupAll()->result();

                    //                        $tmp['nomer_top'] = $tmpTrx[0]->nomer;
                    $tmp['nomer_top'] = formatField_he_format("nomer_top", $tmpTrx[0]->nomer);
                    //                        arrPrint($tmpTrx[0]->nomer);
                }

                if ($row->sub_step_number > 0) {
                    $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                    $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $jenisTr);
                }
                else {
                    $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                }

                //-------------------------
                if ($row->sub_step_number == 1) {
                    if (isset($arrTransaksiHoldLabel[$row->transaksi_id])) {
                        $hold_label = $arrTransaksiHoldLabel[$row->transaksi_id];
                        $tmp['state'] .= "<br>";
                        $tmp['state'] .= "<div class='panel panel-info ' style='margin-top:15px;font-size:15px;'>";
                        $tmp['state'] .= "<span class='meta text-bold'>* $hold_label</span>";
                        $tmp['state'] .= "</div>";
                    }
                }
                //-------------------------

                //region show items
                $tblItems = "";
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if (sizeof($arrItemShow) > 0) {
                        $tblItems .= "<table class='table table-bordered'>";
                        $tblItems .= "<tr>";
                        $tblItems .= "<th>No</th>";
                        foreach ($arrItemShow as $k => $iLabel) {
                            if (is_array($iLabel)) {
                                $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                            }
                            else {
                                $tblItems .= "<th >$iLabel</th>";
                            }
                        }
                        $tblItems .= "</tr>";


                        if (isset($detailShow[$row->transaksi_id])) {
                            $numb = 0;
                            foreach ($detailShow[$row->transaksi_id] as $row_1) {
                                $numb++;
                                $tblItems .= "<tr>";
                                $tblItems .= "<td>$numb</td>";
                                foreach ($arrItemShow as $k => $iLabel) {
                                    $class = "";
                                    if (is_numeric($row->$k)) {
                                        $class = "text-center";
                                    }
                                    $tblItems .= "<td>" . $row_1->$k . "</td>";
                                }
                                $tblItems .= "</tr>";
                            }

                        }


                        $tblItems .= "</table >";

                    }
                }
                else {
                    if (isset($this->configUi[$jenisTr]["shortItemsFieldsSteps"][$row->sub_step_number])) {
                        $arrItemShow = $this->configUi[$jenisTr]["shortItemsFieldsSteps"][$row->sub_step_number];
                    }
                    $tmpTrDataTambahan = isset($tmpTrData[$row->transaksi_id]) ? $tmpTrData[$row->transaksi_id] : array();
                    $detail = viewDetailTransaksi($tmpReg_result[$row->transaksi_id], $arrItemShow, $row->jenis_master, true, $tmpTrDataTambahan);
                    $tblItems = $detail;
                }

                $tmp['item_fields'] = $tblItems;
                //endregion


                $tmp['next_pic'] = "-";
                if (sizeof($arrNextPIC) > 0) {
                    if (isset($arrNextPIC[$row->next_substep_code][$row->next_substep_num])) {
                        $next_pic = "";
                        $nob = 1;
                        foreach ($arrNextPIC[$row->next_substep_code][$row->next_substep_num] as $spec) {
                            //                            arrPrint($spec);
                            //                            cekHere($row->cabang_id . " :: " . $spec['cabang_id'] . " :: " . $spec['nama']);
                            $cabangNamaPIC = "<span class='meta'>(" . $spec['cabang_nama'] . ")</span>";
                            if ($row->cabang_id == $spec['cabang_id']) {
                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                            }
                            if (($spec['cabang_id'] == CB_ID_PUSAT)) {
                                //                            if ((my_cabang_id() == CB_ID_PUSAT) && ($spec['cabang_id'] == CB_ID_PUSAT)) {
                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                            }

                        }
                        $tmp['next_pic'] = $next_pic;
                        //                            cekHitam($next_pic);
                    }
                }


                $tmp['action'] = "";
                $nextStepNum = ($row->next_substep_num);
                $currentStepNum = ($row->sub_step_number);
                $currentStepCode = ($row->jenis);
                $nextStepCode = ($row->next_step_code);


                if ($row->sub_step_number > 0) {
                    $allowFollowup = true;
                    $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                }

                if (isset($this->configUi[$jenisTr]['steps'][$nextStepNum])) {
                    if (isset($this->accessList[$jenisTr])) {
                        if (isset($this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                            $allowFollowup = $this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }


                    }
                    else {
                        if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }
                        else {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                        }
                    }
                }
                $req_cancel_qty = $row->req_cancel_qty != '' ? $row->req_cancel_qty : 0;
                $valid_qty = $row->valid_qty != '' ? $row->valid_qty : 0;


                if ($allowFollowup) {
                    $allowJoin = isset($this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                    $stepLabel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['label'] : "";
                    $isCancelPacking = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                    $allowCancel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel'] : false;
                    $allowScaner = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowScaner']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowScaner'] : false;
                    $allowNextStepOtorisasi = isset($this->configUi[$jenisTr]['allowNextStepOtorisasi'][$nextStepNum]) ? $this->configUi[$jenisTr]['allowNextStepOtorisasi'][$nextStepNum] : array();

                    $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                    $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                    if ($isMob) {
                        $btn_block = "";
                    }
                    else {
                        $btn_block = "btn-block";
                    }
                    $class_button = "btn-primary";
                    $numberValidate = $row->step_number + 1;
                    $disabled_button_kreditlimit = "";
                    $disabled_button = "";
                    $disabled_kirim = "";
                    $disabled_hold = "";
                    $add_note = "";
                    $strPengirim = "";
                    $disabled_button_reject = "";
                    $btn_title = "turn this entry into $stepLabel";
                    if (isset($psrcData[$row->transaksi_id])) {
                        $disabled_button = "disabled";
                        //                        $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                        //                        $class_button = "btn-default";
                        $class_button = "btn-primary";
                        $actionLabel = "menunggu pelunasan";


                        // belum dibayar
                        if ($psrcData[$row->transaksi_id]["sisa"] > $sisa_pembayaran) {
                            $disabled_button = "disabled";
                            $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                            $class_button = "btn-primary";
                            $actionLabel = "menunggu pelunasan";
                        }
                        if ($psrcData[$row->transaksi_id]["sisa"] <= $sisa_pembayaran) {
                            $disabled_button = "";
                            $disabled_button_reject = "disabled";
                            $add_note .= "<br><button disabled class='btn btn-success btn-block btn-sm fa fa-money' style='margin-top:5px;'> LUNAS</button>";
                            $class_button = "btn-primary";
                            $actionLabel = "siap dikirim dari cabang";
                        }

                    }
                    // dibawah ini daftar PL hadiah (Rekomendasi 5.1, 5.2, & 5.3)
                    if (isset($psrcdData[$row->id_master])) {
                        $disabled_button = "disabled";
                        $class_button = "btn-primary";
                        $actionLabel = "menunggu pelunasan";

                        // belum dibayar
                        if ($psrcdData[$row->id_master]["sisa"] > $sisa_pembayaran) {
                            $disabled_button = "disabled";
                            $btn_title = "Tidak dapat memproses penyerahan - Pelunasan Pajak Hadiah Belum Diterima / Belum Lunas";
                            $class_button = "btn-primary";
                            $actionLabel = "menunggu pelunasan";

                            // Step Tracker 3 Langkah (Rekomendasi 5.3): Pajak Belum Lunas
                            $stateSign3Step = "<span class='fa fa-check-circle text-black'> Request Produk Hadiah</span><br>";
                            $stateSign3Step .= "<span class='fa fa-clock-o text-yellow' style='color:#f39c12;font-weight:bold;'> Pelunasan Pajak Hadiah</span><br>";
                            $stateSign3Step .= "<span class='fa fa-lock text-grey'> Penyerahan Produk Hadiah</span><br><br>";
                            $stateSign3Step .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
                            $stateSign3Step .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>Menunggu Pelunasan Pajak</span><br>";
                            $stateSign3Step .= "<span class='label label-danger' style='display:inline-block; margin-top:5px; padding:3px 6px; font-weight:bold; font-size:15px;'>PAJAK BELUM LUNAS</span>";

                            $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span><br>" . $stateSign3Step;
                        }
                        if ($psrcdData[$row->id_master]["sisa"] <= $sisa_pembayaran) {
                            cekHere("MASUK DISINI");
                            $disabled_button = "";
                            $disabled_button_reject = "disabled";
                            $class_button = "btn-primary";
                            $actionLabel = "siap dikirim dari cabang";

                            // Step Tracker 3 Langkah (Rekomendasi 5.3): Pajak Lunas
                            $stateSign3Step = "<span class='fa fa-check-circle text-black'> Request Produk Hadiah</span><br>";
                            $stateSign3Step .= "<span class='fa fa-check-circle text-green' style='color:#27ae60;font-weight:bold;'> Pelunasan Pajak Hadiah</span><br>";
                            $stateSign3Step .= "<span class='fa fa-circle text-grey'> Penyerahan Produk Hadiah</span><br><br>";
                            $stateSign3Step .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
                            $stateSign3Step .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>Penyerahan Produk Hadiah</span><br>";
                            $stateSign3Step .= "<span class='label label-success' style='display:inline-block; margin-top:5px; padding:3px 6px; font-weight:bold; font-size:15px;'>PAJAK LUNAS</span>";

                            $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span><br>" . $stateSign3Step;
                        }

                    }
                    if (isset($kreditLimitValidate[$numberValidate])) {
                        if (isset($arrKreditLimitDataKredit[$row->customers_id]["kredit"]) && ($arrKreditLimitDataKredit[$row->customers_id]["kredit"] > 0)) {
                            $disabled_button_kreditlimit = "disabled";
                            $add_note .= "<br><span class='meta'>" . $kreditLimitValidate[$numberValidate]["label"] . "</span>";
                            $class_button = "btn-primary";
                        }
                    }
                    if ($row->kirim_metode_id == 1) {
                        if (($row->pengirim_id == 0) || ($row->pengirim_id == null)) {
                            $followupPengirimLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/doFollowupPengirim/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "?pengirim=pengirim');";
                            $disabled_kirim = ($row->step_number >= 3) ? "disabled" : "";
                            $add_note .= "<br><span class='meta'>Pengirim belum terdaftar pada transaksi ini.</span>";
                        }
                    }

                    if ($this->session->login["employee_type"] == "employee_kirim") {
                        $tmp['action'] = "";
                    }
                    else {
                        //-------------------------
                        if ($row->cabang_id > 0) {// hanya bila transaksi cabang
                            if (($row->gudang_status_jenis != NULL) && ($row->gudang_status_jenis == "pusat")) {
                                $allowScaner = false;
                                if (isset($allowNextStepOtorisasi[$row->gudang_status_jenis])) {
                                    $disabled_button = $allowNextStepOtorisasi[$row->gudang_status_jenis];
                                    $add_note .= "<br><span class='meta'>* " . $allowNextStepOtorisasi["label"] . "</span>";
                                    $actionLabel = "* siap dikirim dari dc/pusat";
                                    if (isset($psrcData[$row->transaksi_id])) {
                                        $actionLabel = "menunggu pelunasan";
                                    }
                                }
                            }
                        }
                        //-------------------------
                        if (isset($transaksiHold[$row->transaksi_id])) {
                            $disabled_hold = "disabled";
                            $add_note .= "<br><span class='meta'>* Barang siap dikirim.<br>Edit/Reject SO tidak bisa digunakan.</span>";
                        }
                        $tmp['action'] = "<div class='input-group'>";
                        if ($allowScaner == false || $isMob == 1) {
                            if ($row->transaksi_id == 6399) {
                                cekHere("[$disabled_hold]");;
                            }
                            if ($disabled_hold == "disabled" || $disabled_button == "disabled") {
                                $tmp['action'] .= "<span data-toggle='tooltip' data-placement='top' title='$btn_title' style='display:block;'><button id='btn_approve_$transaksi_idd' class='btn $btn_block $class_button' $disabled_hold href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</button></span>";
                            }
                            else {
                                $tmp['action'] .= "<button id='btn_approve_$transaksi_idd' class='btn $btn_block $class_button' title='$btn_title' $disabled_hold href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</button>";
                            }
                        }
                        else {
                            $actionLabel = "QR untuk Handphone";
                            $targetFollowupLink = "followupDariHp";
                            $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                            $fpLink = MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number;
                            $followupLink = "BootstrapDialog.show(
                                   {
                                        title:'" . $actionLabel . "',
                                        message: $('<div></div>').load('" . $fpLink . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                    );";
                            if ($disabled_button == "disabled" || $disabled_hold == "disabled") {
                                $tmp['action'] .= "<span data-toggle='tooltip' data-placement='top' title='$btn_title' style='display:block;'><button id='btn_approve_$transaksi_idd' class='btn btn-primary btn-block' $disabled_button href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</button></span>";
                            }
                            else {
                                $tmp['action'] .= "<button id='btn_approve_$transaksi_idd' class='btn btn-primary btn-block' $disabled_button title='$btn_title' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</button>";
                            }
                        }
                        $tmp['action'] .= isset($strPengirim) ? $strPengirim : "";
                        $tmp['action'] .= "</div class='input-group'>";
                        $tmp['action'] .= $add_note;

                        if ($req_cancel_qty > 0 && $valid_qty == 0) {
                            $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
                            $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>menuggu approve cancel</button>";
                            //                            $tmp['action'] .= "<button type='button' class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>x</button>";
                            $tmp['action'] .= "</div>";
                        }
                    }

                }
                else {

                }

                //EDIT------------------------------
                $pakai_ini = !in_array($this->jenisTr, $this->notAllowEdit) ? 1 : 0;
//                cekHere("edit: $pakai_ini || " . $this->jenisTr);
                if ($pakai_ini == 1) {
                    $arrStepAllow = array(1, 2);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        if ($allowFollowup) {
                            $transaksiID_reject = $row->transaksi_id;
                            $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evMaster = evaluateMain_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            //                        cekHitam("evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                            if ($evPre == null && $evPost == null && $evCom == null && $evMaster == null) {
                                $transaksiID_edit = $row->transaksi_id;
                                $transaksiCustomerNama_f = "<strong>" . $row->customers_nama . "</strong>";
                                $transaksiNomer = $row->nomer;
                                $transaksiNomer_f = formatField_he_format("nomer_nolink", $row->nomer);
                                $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=edit";
                                $actionLabel_edit = "Edit";
                                $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='background-color:#000000;color:#ffffff;'
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
                            }
                        }

                    }
                }
                //------------------------------
                //REJECT------------------------------
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    $arrStepAllow = array(1, 2, 3);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        if ($evPre == null && $evPost == null && $evCom == null) {
                            $transaksiID_reject = $row->transaksi_id;
                            $masterRevertStep = ($currentStepNum - 1);
                            $childRevertStep = -($currentStepNum);
                            if ($this->reject == true) {
                                $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=reject";
                                $actionLabel_edit = "Reject 1 step";
                                $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject'\">" . $actionLabel_edit . " </button>";
                            }
                            if ($this->reject_all == true) {
                                $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=rejectall";
                                $actionLabel_edit = "Reject all step";
                                $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='background-color:#000000;color:#ffffff;'
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
                            }
                        }
                    }
                }
                //------------------------------
                //UPLOAD IMAGES
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    //shippingMethod
                    //                    arrPrint($tmp);
                    $arrStepAllow = array(1, 2);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        if ($row->shippingMethod == 3) {
                            //ini installation
                            $file_e = "";
                            $imaster_id = $row->id_master;
                            $imageRecorder = MODUL_PATH . get_class() . "/recordImage";
                            $iID = $numb;
                            if (isset($relImages[$row->id_master])) {
                                //                                $label_image = preg_replace('/\d{10}/', '...', $relImages[$row->id_master]["nama"]);
                                $label_image = strlen($relImages[$row->id_master]["nama"]) > 10 ? substr($relImages[$row->id_master]["nama"], 0, 10) . "..." : $relImages[$row->id_master]["nama"];
                                $label_ori = $relImages[$row->id_master]["nama"];
                                $linkDelete = "$imageRecorder/$imaster_id?iID=$iID&rel=delete";

                                $tmp['action'] .= "<div style='margin-top: 5px;position:relative;min-height: 30px;padding: 5px;' class='_div_$iID'>";
                                $tmp['action'] .= "<span id='overlay_c' class='overlay_$iID'><i class='fa fa-refresh fa-spin'></i></span>";
                                $tmp['action'] .= "<label title='$label_ori'><i class='fa fa-image'></i> $label_image</label>";
                                $tmp['action'] .= "<button type='button' class='btn btn-xs btn-danger' title='hapus foto' onclick =\"top.$('.overlay_$iID').show();document.getElementById('result').src='$linkDelete'\"> hapus</button>";
                                $tmp['action'] .= "</div>";
                            }
                            else {
                                $file = isset($row->images) ? $row->images : "";
                                $file_e = urlencode($file);

                                $tmp['action'] .= "<div style='margin-top: 5px;position:relative;min-height: 30px;padding: 5px;' class='_div_$iID'>";
                                $tmp['action'] .= "<span id='overlay_c' class='overlay_$iID'><i class='fa fa-refresh fa-spin'></i></span>";
                                $tmp['action'] .= "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$imaster_id?iID=$iID&valValue=$file_e' target='result'>";
                                $tmp['action'] .= "<input type='file' class='btn btn-warning btn-block btn-xs' id='file-upload_$iID' style='border: none;padding:5px;' name='file' class='file' onchange=\"top.$('.overlay_$iID').show();document.getElementById('myForm_$iID').submit();\">";
                                $tmp['action'] .= "<div>Resolusi 1600 x 1204</div>";
                                $tmp['action'] .= "</form>";
                                $tmp['action'] .= "</div>";
                            }
                        }
                        else {

                        }
                    }
                }
                //FULLFILLMENT------------------------------
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    $xShipmentBtn = array();
                    $xShipmentConfig = isset($this->configUi[$this->jenisTr]['xShipmentConfig']) ? $this->configUi[$this->jenisTr]['xShipmentConfig'] : array();
                    if ($xShipmentConfig) {
                        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                        foreach ($xShipmentConfig as $num => $val) {
                            if ($num == $currentStepNum) {
                                $allowed = false;
                                foreach ($val['allowedGroups'] as $group) {
                                    if (in_array($group, $mems)) {
                                        $allowed = true;
                                        break;
                                    }
                                }
                                //                                cekHitam("[allowed: $allowed] [currentStepNum: $currentStepNum]");
                                if (($val['enabled'] == true) && ($allowed == true)) {
                                    $labelx = $val['label'];
                                    $targetJenisMaster = $val['targetJenisMaster'];
                                    $warningx = $val['warning'];
                                    $transaksiID_fullfill = $row->transaksi_id;
                                    $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_fullfill/$nextStepNum/" . $row->sub_step_number . "?getmode=fullfill";
                                    //                                    cekHere($link_reject);
                                    //                                    $xShipmentBtn_str = array(
                                    //                                        "label" => $labelx,
                                    ////                                        "targetUrl" => MODUL_PATH . __CLASS__ . "/preCancelPacking/$jenisTr/$no/$targetJenisMaster/$currentStepNum",
                                    //                                        "targetUrl" => $link_reject,
                                    //                                        "warning" => $warningx,
                                    //                                    );
                                    //                                    $tmp['action'] .= $xShipmentBtn_str;
                                    $tmp['action'] .= "<button class='btn btn-warning btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                    onclick =\"document.getElementById('result').src='$link_reject'\">" . $labelx . " </button>";
                                }
                                else {

                                }
                            }
                        }
                    }
                }
                //------------------------------
                $tmpMark = array();
                $tmp['keterangan'] = "-";
                if ($row->partial == 1) {
                    $tmpMark['style'] = "background-color:yellow;";
                    $tmp['keterangan'] = "<span style='color:red;'>transaksi diproses sebagian</span>";
                }


                $arrayOnprogress[] = $tmp;
                $arrayOnprogressGroup[$currentStepNum][] = $tmp;

                $arrayOnprogressPartialMark[] = $tmpMark;
                $arrayOnprogressGroupPartialMark[$currentStepNum][] = $tmpMark;

            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            $isDisableMakeTrans = isset($this->configUi[$this->jenisTr]['isDisableMakeTrans']) ? $this->configUi[$this->jenisTr]['isDisableMakeTrans'] : false;
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            if ($isDisableMakeTrans) {
                $addLink = null;
            }
            else {
                $addLink = array(
                    "link" => $targetUrl,
                    "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
                );
            }

        }
        else {
            $addLink = null;
        }
        //endregion

        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $historyFieldsDt = isset($this->configUi[$jenisTr]['historyFields'][$jenisTrsub]) ? $this->configUi[$jenisTr]['historyFields'][$jenisTrsub] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $availDbTable = $tr->getAvailTable($historyFieldsDt);

        //-----------------------------------------------------
        $arrJenisTrCek = array("587", "687", "1587", "1687");
        if (in_array($jenisTr, $arrJenisTrCek)) {
            $transaksiName = isset($this->configUi[$jenisTr]['label']) ? $this->configUi[$jenisTr]['label'] : NULL;
            $subplace = isset($this->configUi[$jenisTr]['steps'][1]['subplace']) ? $this->configUi[$jenisTr]['steps'][1]['subplace'] : NULL;
            if ($subplace != NULL) {
                if (($subplace == "warehouse") && ($this->session->login['gudang_id'] < 0)) {
                    //                    cekPink("SESUAI");
                    $msg = NULL;
                }
                elseif (($subplace == "warehouse_ng") && ($this->session->login['gudang_id'] > 0)) {
                    //                    cekPink("SESUAI NOT GOOD");
                    $msg = NULL;
                }
                else {
                    $msg = "Anda tidak memiliki kewenangan untuk membuat request $transaksiName";
                    $addLink = array();
                }
            }
        }

        //-----------------------------------------------------
        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        //        cekMerah("Grabing data " . $val . " Seconds");

        $link_undoneList_kurir = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";

        $data = array(
            "mode" => isset($this->configUi[$jenisTr]["mode"]) ? $this->configUi[$jenisTr]["mode"] : $this->uri->segment(3),
            "isMobile" => $isMob,
            "errMsg" => $this->session->errMsg,
            "template" => $this->configUi[$jenisTr]["template"],
            "title" => $this->configUi[$jenisTr]["label"],
            "subTitle" => $this->configUi[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            'addLink' => $addLink,
            "allSteps" => $this->allSteps,
            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> recent " . $this->configUi[$jenisTr]["label"] . " histories",
            "arrayHistoryLabels" => array("dtime" => "time") + $historyFields,
            "arrayHistoryLabelsDt" => $historyFieldsDt,
            "availDbTable" => $availDbTable,
            "arrayHistory" => isset($arrayHistory) ? $arrayHistory : array(),
            "onprogressTitlePOS" => "<span class='glyphicon glyphicon-alert'></span> TRANSAKSI POS YANG PERLU ACTION ",
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> TRANSAKSI YANG PERLU ACTION ",
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgressToPay" => $paymentConfig,
            "itemLabels" => isset($itemLabels) ? $itemLabels : "",
            "srcLabel" => isset($srcLabel) ? $srcLabel : "",
            //            "arrayOnProgress" => array_merge($arrayOnprogress, $arrayOnprePre, $arrayOnpreDistribution),
            "steps" => $isMob == true ? array(2 => $steps[2]) : $steps,
            "arrayOnProgress" => (isset($arrayOnprogress) && sizeof($arrayOnprogress) > 0) ? $arrayOnprogress : array(),
            "arrayOnprogressGroup" => (isset($arrayOnprogressGroup) && (sizeof($arrayOnprogressGroup) > 0)) ? $arrayOnprogressGroup : array(),

            "presteps" => $isMob == true ? array(2 => $steps[2]) : $steps,
            "arrayProgressPreLabels" => $prePreFields,
            "arrayOnprePre" => $arrayOnprePre,
            "arrayOnprePreGroup" => $arrayOnprePreGroup,

            "arrayOnpreDistribution" => isset($arrayOnpreDistribution) ? $arrayOnpreDistribution : array(),
            "arrayOnpreDistributionGroup" => isset($arrayOnpreDistributionGroup) ? $arrayOnpreDistributionGroup : array(),

            "entities" => isset($entities) ? $entities : array(),
            "recapTitle" => "<span class='fa fa-newspaper-o'></span> today " . $this->configUi[$jenisTr]["label"] . " reports",
            "arrayRecapLabels" => isset($recapLabels) ? $recapLabels : array(),
            "arrayRecap" => isset($arrayRecap) ? $arrayRecap : array(),

            "onprogressViewTitle" => "<span class='fa fa-eye'></span> show incomplete step " . $this->configUi[$jenisTr]["label"],
            "onprogressViewSubTitle" => "<span class='text-black'>(daftar transaksi yang masih stanby di cabang tujuan)</span>",
            "arrayOnProgressView" => isset($arrayOnProgressView) ? $arrayOnProgressView : array(),
            "stepHistoryFields" => isset($stepHistoryFields) ? $stepHistoryFields : array(),

            "selectProcessor" => $selectProcessor,
            "sumFooter" => (isset($sumFooterResult) && sizeof($sumFooterResult) > 0) ? $sumFooter : array(),
            "scriptBottom" => $scriptBottom,
            "btnLabel" => $extData,
            "actionTarget" => $extact,
            "sumFooter" => isset($sumFooter) ? $sumFooter : "",

            "arrayOnprogressPartialMark" => (isset($arrayOnprogressPartialMark) && sizeof($arrayOnprogressPartialMark) > 0) ? $arrayOnprogressPartialMark : array(),
            "arrayOnprogressGroupPartialMark" => (isset($arrayOnprogressGroupPartialMark) && sizeof($arrayOnprogressGroupPartialMark) > 0) ? $arrayOnprogressGroupPartialMark : array(),

            "defaultItemTrgEditable" => isset($defaultItemTrgEditable) ? $defaultItemTrgEditable : array(),
            "editItemTrg" => MODUL_PATH . "_followupLiveEdit/editEfaktur/" . $this->jenisTr . "/",


            "link_scan_mobile" => ($this->session->login["employee_type"] == "employee_kirim") ? MODUL_PATH . "FollowUp/followupScanMobile/$jenisTr" : NULL,
            "link_undoneList_kurir" => $link_undoneList_kurir,
            "preStep" => count($preStep) > 0 ? $preStep : array(),
            "followUpLiveEditField" => $followUpLiveEditField,
        );
        $this->load->view("transaksi", $data);
    }

    public function viewUndoneItemsIndexTimeline()
    {
        $sisa_pembayaran = 100;
        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $starttime = microtime(true);
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $scriptBottom = "";
        $sesionReplacer = replaceSession();
        //        $jenisTr = $this->uri->segment(3);
        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $paymentConfig = isset($this->configUi[$jenisTr]['paymentConfig']) ? $this->configUi[$jenisTr]['paymentConfig'] : false;
        $myPaymentConfig = isset($this->configUi[$jenisTr]['myPaymentConfig']) ? $this->configUi[$jenisTr]['myPaymentConfig'] : false;
        $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $stepHistoryFields = isset($this->configUi[$jenisTr]['shortStepHistoryFields']) ? $this->configUi[$jenisTr]['shortStepHistoryFields'] : array();
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();
        $kreditLimitValidate = isset($this->configUi[$jenisTr]["kreditLimitValidate"]) ? $this->configUi[$jenisTr]["kreditLimitValidate"] : array();
        $allowPrintQr = isset($this->configUi[$jenisTr]["steps"][$jenisTrsub]["allowPrintQr"]) ? $this->configUi[$jenisTr]["steps"][$jenisTrsub]["allowPrintQr"] : 0;

        /* ----------------------------------------------------------------------
         * deteksi mobile auto atau hanya orang tertentu,
         * diatur di heWeb mobile
         * ----------------------------------------------------------------------*/
        $isMob = isMobile_he_misc();
        // --------------------------------------------------------------
        $this->load->model("Coms/ComTransaksiKreditLimit");
        $kl = New ComTransaksiKreditLimit();
        $arrKreditLimitDataKredit = $kl->fetchBalancesKredit();
        //        arrPrintWebs($arrKreditLimitDataKredit);
        // --------------------------------------------------------------


        if ($isMob) {
            // $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }
        $prePreFields = $historyFields;
        $prePreFields['state'] = "status";
        $prePreFields['action'] = "action";
        $swapJenisTr = isset($this->configUi[$jenisTr]['requestCode']['masterCode']) ? $this->configUi[$jenisTr]['requestCode']['masterCode'] : array();
        $arrayOnprePre = array();
        $arrayOnprePreGroup = array();
        if (sizeof($swapJenisTr) > 0) {
            $steps = $this->configUi[$swapJenisTr]['steps'];
            $arrFilters = array();
            if (sizeof($steps) > 1) {
                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $stepCodes = array();
                $jmlStep = count($steps);
                if (isset($this->accessList[$swapJenisTr]) && sizeof($this->accessList) > 0) {
                    $indsteps = "(";
                    foreach ($this->accessList[$swapJenisTr] as $stepNumber => $stepSpec) {
                        if ($stepNumber <= $jmlStep) {
                            foreach ($stepSpec as $targetCode => $filters) {
                                $indsteps .= "'$targetCode',";
                                $stepCodes[] = $targetCode;
                                if ($filters['allowFollowUp'] == "true") {
                                    $arrFilters["allowFollowUp"][] = $targetCode;
                                }
                            }
                        }
                    }
                    $indsteps = rtrim($indsteps, ",");
                    $indsteps .= ")";
                    if (sizeof($arrFilters) > 0) {
                        $tr->addFilter("next_step_code in $indsteps");
                    }
                    else {
                        $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                    }
                }
                else {

                }
                $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
                $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
                $tr->addFilter("next_substep_code<>''");
                $tr->addFilter("sub_step_number>0");
                $tr->addFilter("valid_qty>0");
                $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
                if (sizeof($tmpHist) > 0) {
                    $arrTransID = array();
                    $arrTransTopID = array();
                    $arrIdsHist = array();
                    $arrTransHist = array();
                    foreach ($tmpHist as $row) {
                        $arrTransID[] = $row->id;
                        $arrTransTopID[] = $row->id_top;

                        if ($row->ids_his != "") {
                            $hist = blobDecode($row->ids_his);
                            foreach ($hist as $hisSpec) {
                                $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                    "step" => $hisSpec['step'],
                                    "trID" => $hisSpec['trID'],
                                    "nomer" => $hisSpec['nomer'],
                                );
                                $arrTransHist[] = $hisSpec['trID'];
                            }
                        }
                    }
                    $tmpReg_result = array();
                    $trReg = new MdlTransaksi();
                    $trReg->setFilters(array());
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();

                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            if ($regRow->values == null) {
                                $regRow->values = blobEncode(array());
                            }
                            $param = $regRow->param;
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
                        }
                    }

                    foreach ($tmpHist as $row) {
                        if (sizeof($pairRegistries) > 0) {
                            //                            $trReg = new MdlTransaksi();
                            //                            $trReg->setFilters(array());
                            //                            $trReg->addFilter("param in ('" . implode("','", $pairRegistries) . "')");
                            //                            $trReg->addFilter("transaksi_id='" . $row->transaksi_id . "'");
                            //                            $tmpReg = $trReg->lookupRegistries()->result();
                            //                            if (sizeof($tmpReg) > 0) {
                            //                                foreach ($tmpReg as $regRow) {
                            //                                    $param = $regRow->param;
                            //                                    $$param = blobDecode($regRow->values);
                            //                                }
                            //                                foreach ($pairRegistries as $eReg) {
                            //                                    foreach ($$eReg as $k => $v) {
                            //                                        if (!isset($row->$k)) {
                            //                                            $row->$k = $v;
                            //                                        }
                            //                                    }
                            //                                }
                            //                            }
                            if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                                foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                                    foreach ($eReg as $k => $v) {
                                        if (!isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                }
                            }
                        }

                        $tmp = array();
                        foreach ($historyFields as $fName => $fLabel) {
                            $tmp[$fName] = isset($row->$fName) ? formatField($fName, $row->$fName) : formatField($fName, 0);
                        }

                        //                        cekHijau('$swapJenisTr: ' . $swapJenisTr);

                        $showPoStatus = isset($this->configUi[$swapJenisTr]['showPoStatus']) ? $this->configUi[$swapJenisTr]['showPoStatus'] : array();
                        $cekStateLocation = ($this->session->login['cabang_id'] > 0) ? "cabang" : "pusat";

                        if ($row->sub_step_number > 0) {

                            $tmp['state'] = "<div class='panel panel-warning' style='padding: 3px;margin-bottom: 5px;' ><span style='color:" . $this->configUi[$swapJenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$swapJenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                            $tmp['state'] = createStateHorizontal($row->sub_step_number, $row->step_avail, $jenisTr);
                            //                            $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $swapJenisTr) . "</div>";

                            // cek PO
                            $arrTransactionSource = array();
                            $id_master = $row->id_master;
                            $id_top1 = $row->id_top;
                            $this->load->model("MdlTransaksi");
                            $l = new MdlTransaksi();
                            $l->setFilters(array());
                            $l->addFilterJoin("transaksi_data.valid_qty>0");
                            $l->addFilter("id_master='" . $id_master . "'");
                            $l->addFilter("jenis_master='" . $swapJenisTr . "'");
                            $l->addFilter("link_id=0");
                            $tmpTS = $l->lookupJoined();

                            $arrsub_step_number = array();
                            $arrstep_avail = array();
                            $arrext_blob = array();
                            $arrjenis_master = array();
                            $arrtransaksi_no = array();
                            $arrketerangan = array();

                            if (sizeof($tmpTS) > 0) {
                                foreach ($tmpTS as $kk => $arVL) {
                                    $id = $arVL->id;
                                    $id_master = $arVL->id_master;
                                    $id_top2 = $arVL->id_top;
                                    $produk_id = $arVL->produk_id;
                                    $arrTransactionSource[$id_top1] = $produk_id;
                                    $arrsub_step_number[$id_top1] = $arVL->sub_step_number;
                                    $arrstep_avail[$id_top1] = $arVL->step_avail;
                                    $arrext_blob[$id_top1][$kk] = isset($arVL->ext_blob) ? ($arVL->ext_blob != "" ? blobDecode($arVL->ext_blob) : "") : "";
                                }

                                if (sizeof($arrext_blob[$id_top1]) > 0) {
                                    foreach ($arrext_blob[$id_top1] as $ky => $aVal) {
                                        if ($aVal != "") {
                                            foreach ($arrext_blob[$id_top1][$ky]['static'] as $numb => $numData) {
                                                $arrtransaksi_no[$id_top1][$numb] = formatField_he_format("nomer", $numb);
                                                $arrjenis_master[$id_top1][$numb] = $numData['jenis'];
                                                $arrketerangan[$id_top1][$numb] = $numData['keterangan'];
                                            }
                                        }
                                    }
                                }
                            }

                            if ($arrext_blob[$id_top1][$ky] != "") {
                                $tmp['state'] .= "<div class='panel panel-danger bg-green' style='padding: 3px;margin-bottom: 5px;'>";
                                if ($cekStateLocation == "cabang") {
                                    $tmp['state'] .= "<div><b>diproses oleh PUSAT</b></div>";
                                }
                                if (sizeof($arrtransaksi_no[$id_top1]) > 0) {
                                    foreach ($arrtransaksi_no[$id_top1] as $numb_) {
                                        $tmp['state'] .= "<div><span class='fa fa-check-circle text-warning'></span> " . $numb_ . "</div>";
                                    }
                                }
                                $tmp['state'] .= "</div>";
                            }

                        }
                        else {
                            $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                        }

                        $tmp['action'] = "";
                        $nextStepNum = ($row->next_substep_num);
                        $currentStepNum = ($row->sub_step_number);
                        $currentStepCode = ($row->jenis);
                        $nextStepCode = ($row->next_step_code);

                        if ($row->sub_step_number > 0) {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                        }

                        if (isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum])) {
                            if (isset($this->accessList[$jenisTr])) {
                                if (isset($this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                                    $allowFollowup = $this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                                    $actionLabel = $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                            }
                            else {
                                if (in_array($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                                    $allowFollowup = true;
                                    $actionLabel = $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                                else {
                                    $allowFollowup = true;
                                    $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                                }
                            }
                        }

                        if ($allowFollowup) {
                            $allowJoin = isset($this->configUi[$swapJenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$swapJenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                            $stepLabel = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['label'] : "";
                            $isCancelPacking = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                            $allowCancel = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowCancel'] : false;

                            $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                            $followupLink = "top.$('#result').load('" . base_url() . "Transaksi/$targetFollowupLink/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                            $tmp['action'] = "<div class='input-group'>";
                            $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                            if ($allowJoin) {
                                $tmp['action'] .= "<span class='input-group-addon'>";
                                $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                                $tmp['action'] .= "</span class='input-group-addon'>";
                            }
                            $tmp['action'] .= "</div class='input-group'>";

                            if ($allowCancel) {
                                $tmp['action'] .= "<div class='input-group'>";
                                $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                                if ($allowJoin) {
                                    $tmp['action'] .= "<span class='input-group-addon'>";
                                    $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                                    $tmp['action'] .= "</span class='input-group-addon'>";
                                }
                                $tmp['action'] .= "</div class='input-group'>";
                            }
                        }
                        else {

                        }
                        $arrayOnprePre[] = $tmp;
                        $arrayOnprePreGroup[$currentStepNum][] = $tmp;
                    }
                }
            }
            else {
                $arrayOnprePre = array();
            }
        }
        else {
            $arrayOnprePre = array();
        }
        //endregion prePre


        //region lookup on-going transactions
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->configUi[$jenisTr]['steps'];

        $arrFilters = array();

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $stepCodes = array();
        $jmlStep = count($steps);

        if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList) > 0) {
            $indsteps = "(";
            foreach ($this->accessList[$jenisTr] as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    foreach ($stepSpec as $targetCode => $filters) {
                        $indsteps .= "'$targetCode',";
                        $stepCodes[] = $targetCode;
                        if ($filters['allowFollowUp'] == "true") {
                            $arrFilters["allowFollowUp"][] = $targetCode;
                        }

                    }
                }
            }
            $indsteps = rtrim($indsteps, ",");
            $indsteps .= ")";
            if (sizeof($arrFilters) > 0) {
                $tr->addFilter("next_step_code in $indsteps");
            }
            else {
                $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                $tr->addFilter("next_step_code!=''");
            }
        }
        else {

        }

        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        if ($this->session->login['employee_type'] == "employee_freelance") {
            $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
        }
        if ($this->session->login['employee_type'] == "employee_kirim") {
            $tr->addFilter("kirim_metode_id='1'");
            //            $sesionReplacer = array();
        }

        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        //arrPrint($sesionReplacer);
        //showLast_query("biru");
        $arrayOnprogress = array();
        $arrayOnprogressGroup = array();
        $arrayOnprogressPartialMark = array();
        $arrayOnprogressGroupPartialMark = array();

        $extData = "";
        $extact = "";
        $selectProcessor = "";
        if (sizeof($tmpHist) > 0) {
            $arrTransID = array();
            $arrTransTopID = array();
            $arrIdsHist = array();
            $arrTransHist = array();
            $arrNextAction = array();
            foreach ($tmpHist as $row) {
                $arrTransID[] = $row->transaksi_id;
                $arrTransTopID[] = $row->id_top;
                $arrNextAction[$row->transaksi_id] = array(
                    "next_step_num" => $row->next_substep_num,
                    "next_step_code" => $row->next_substep_code,
                );
                if ($row->ids_his != "") {
                    $hist = blobDecode($row->ids_his);
                    foreach ($hist as $hisSpec) {
                        $arrIdsHist[$row->id][$hisSpec['step']] = array(
                            "step" => $hisSpec['step'],
                            "trID" => $hisSpec['trID'],
                            "nomer" => $hisSpec['nomer'],
                        );
                        $arrTransHist[] = $hisSpec['trID'];
                    }
                }
                //                    arrPrint($row);
            }

            // region locker transaksi
            $this->load->model("Mdls/MdlLockerTransaksi");
            $lt = New MdlLockerTransaksi();
            $lt->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $lt->addFilter("state='hold'");
            $lt->addFilter("jumlah='1'");
            $ltTmp = $lt->lookupAll()->result();
            $transaksiHold = array();
            if (sizeof($ltTmp) > 0) {
                foreach ($ltTmp as $ltSpec) {
                    $transaksiHold[$ltSpec->transaksi_id] = $ltSpec->transaksi_id;
                }
            }
            // endregion locker transaksi


            // region membaca payment source label uang muka, untuk kas yang harus diterima dulu.
            $psrc = New MdlTransaksi();
            $psrc->setFilters(array());
            $psrc->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $psrc->addFilter("sisa>100");
            $psrcTmp = $psrc->lookUpAllPaymentSrc()->result();
            $psrcData = array();
            if (sizeof($psrcTmp) > 0) {
                foreach ($psrcTmp as $psrcSpec) {
                    $psrcData[$psrcSpec->transaksi_id] = array(
                        "extern_id" => $psrcSpec->extern_id,
                        "extern_nama" => $psrcSpec->extern_nama,
                        "nomer" => $psrcSpec->nomer,
                        "terbayar" => $psrcSpec->terbayar,
                        "sisa" => $psrcSpec->sisa,
                        "label" => "* Transaksi belum dibayar/belum lunas.",
                    );
                }
            }
            // endregion membaca payment source label uang muka, untuk kas yang harus diterima dulu.


            $tmpReg_result = array();
            if (sizeof($pairRegistries) > 0) {
                $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());
                //            $trReg->addFilter("param='main'");
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        //                    arrPrintWebs($regRow);
                        foreach ($regRow as $key_reg => $val_reg) {
                            if ($val_reg == null) {
                                $val_reg = blobEncode(array());
                            }
                            if ($key_reg != "transaksi_id") {
                                $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                            }
                        }

                    }
                }
            }


            $arrNextPIC = callNextPIC($arrNextAction);

            if (sizeof($arrIdsHist) > 0) {
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                $tmpTransHist = $tr->lookupAll()->result();


                if (sizeof($tmpTransHist) > 0) {
                    foreach ($tmpTransHist as $histSpec) {
                        $tmpTransHist_result[$histSpec->id] = array(
                            "oleh_id" => $histSpec->oleh_id,
                            "oleh_nama" => $histSpec->oleh_nama,
                        );
                    }
                }

                foreach ($arrIdsHist as $trID => $histSpec) {
                    foreach ($histSpec as $step => $detailSpec) {
                        if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                            $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                        }
                        $arrTransMainHist[$trID][$step] = $detailSpec;
                    }
                }
            }

            $numb = 0;
            foreach ($tmpHist as $row) {
                $extHistoryFields2 = isset($arrExtHistoryFields2[$row->step_number]) ? $arrExtHistoryFields2[$row->step_number] : array();

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                        foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                            switch ($param) {
                                case "main":
                                    foreach ($eReg as $k => $v) {
                                        if (($k != null) && !isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                    break;
                                case "items":
                                    if (sizeof($extHistoryFields2) > 0) {
                                        foreach ($extHistoryFields2 as $k1 => $v1) {
                                            if (is_array($v1)) {
                                                $kolom = $v1['kolom'];
                                                $format = $v1['format'];
                                                //                                                cekHitam(":: $kolom :: $format ::");
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                            else {
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
                //                arrPrintPink($row);


                $numb++;

                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (isset($row->$fName)) {
                        if (is_numeric($row->$fName)) {
                            if (!isset($sumFooter[$fName])) {
                                $sumFooter[$fName] = 0;
                            }
                            $sumFooter[$fName] += $row->$fName;
                        }
                    }

                    if (is_array($fLabel)) {
                        $hisStep = isset($fLabel['step']) ? $fLabel['step'] : 0;
                        $hisKey = isset($fLabel['key']) ? $fLabel['key'] : 0;

                        if (isset($row->ids_his)) {
                            if ($hisKey == "nomer") {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $ids_his_decode = blobDecode($row->ids_his);
                                if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                    $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                        }
                        else {
                            $tmp[$fName] = "-";
                        }
                    }
                    else {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                    }

                    if ($fName == "no") {
                        $tmp[$fName] = formatField_he_format($fName, $numb);
                    }

                }

                if (sizeof($row->cancel_packing_source_id) > 0) {
                    arrPrint($row->cancel_packing_source_id);
                    $trx = new MdlTransaksi();
                    $trx->addFilter("id='" . $row->cancel_packing_source_id . "'");
                    $tmpTrx = $trx->lookupAll()->result();

                    //                        $tmp['nomer_top'] = $tmpTrx[0]->nomer;
                    $tmp['nomer_top'] = formatField_he_format("nomer_top", $tmpTrx[0]->nomer);
                    //                        arrPrint($tmpTrx[0]->nomer);
                }


                if ($row->sub_step_number > 0) {
                    $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                    $tmp['state'] = createStateHorizontal($row->sub_step_number, $row->step_avail, $jenisTr);
                    //                    $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $jenisTr);
                }
                else {
                    $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                }


                $tmp['next_pic'] = "-";
                if (sizeof($arrNextPIC) > 0) {
                    if (isset($arrNextPIC[$row->next_substep_code][$row->next_substep_num])) {
                        $next_pic = "";
                        $nob = 1;
                        foreach ($arrNextPIC[$row->next_substep_code][$row->next_substep_num] as $spec) {
                            //                            arrPrint($spec);
                            //                            cekHere($row->cabang_id . " :: " . $spec['cabang_id'] . " :: " . $spec['nama']);
                            $cabangNamaPIC = "<span class='meta'>(" . $spec['cabang_nama'] . ")</span>";
                            if ($row->cabang_id == $spec['cabang_id']) {
                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                            }
                            if (($spec['cabang_id'] == CB_ID_PUSAT)) {
                                //                            if ((my_cabang_id() == CB_ID_PUSAT) && ($spec['cabang_id'] == CB_ID_PUSAT)) {
                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                            }

                        }
                        $tmp['next_pic'] = $next_pic;
                        //                            cekHitam($next_pic);
                    }
                }


                $tmp['action'] = "";
                $nextStepNum = ($row->next_substep_num);
                $currentStepNum = ($row->sub_step_number);
                $currentStepCode = ($row->jenis);
                $nextStepCode = ($row->next_step_code);


                if ($row->sub_step_number > 0) {
                    $allowFollowup = true;
                    $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                }

                if (isset($this->configUi[$jenisTr]['steps'][$nextStepNum])) {
                    if (isset($this->accessList[$jenisTr])) {
                        if (isset($this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                            $allowFollowup = $this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }


                    }
                    else {
                        if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }
                        else {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                        }
                    }
                }
                $req_cancel_qty = $row->req_cancel_qty != '' ? $row->req_cancel_qty : 0;
                $valid_qty = $row->valid_qty != '' ? $row->valid_qty : 0;


                if ($allowFollowup) {
                    $allowJoin = isset($this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                    $stepLabel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['label'] : "";
                    $isCancelPacking = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                    $allowCancel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel'] : false;
                    $allowScaner = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowScaner']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowScaner'] : false;
                    $allowNextStepOtorisasi = isset($this->configUi[$jenisTr]['allowNextStepOtorisasi'][$nextStepNum]) ? $this->configUi[$jenisTr]['allowNextStepOtorisasi'][$nextStepNum] : array();

                    $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                    $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                    if ($isMob) {
                        $btn_block = "";
                    }
                    else {
                        $btn_block = "btn-block";
                    }
                    $class_button = "btn-primary";
                    $numberValidate = $row->step_number + 1;
                    $disabled_button_kreditlimit = "";
                    $disabled_button = "";
                    $disabled_kirim = "";
                    $disabled_hold = "";
                    $add_note = "";
                    $strPengirim = "";
                    $disabled_button_reject = "";
                    if (isset($psrcData[$row->transaksi_id])) {
                        $disabled_button = "disabled";
                        //                        $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                        //                        $class_button = "btn-default";
                        $class_button = "btn-primary";
                        $actionLabel = "menunggu pelunasan";


                        // belum dibayar
                        if ($psrcData[$row->transaksi_id]["sisa"] > $sisa_pembayaran) {
                            $disabled_button = "disabled";
                            $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                            $class_button = "btn-primary";
                            $actionLabel = "menunggu pelunasan";
                        }
                        if ($psrcData[$row->transaksi_id]["sisa"] <= $sisa_pembayaran) {
                            $disabled_button = "";
                            $disabled_button_reject = "disabled";
                            $add_note .= "<br><button disabled class='btn btn-success btn-block btn-sm fa fa-money' style='margin-top:5px;'> LUNAS</button>";
                            $class_button = "btn-primary";
                            $actionLabel = "";
                        }

                    }
                    if (isset($kreditLimitValidate[$numberValidate])) {
                        if (isset($arrKreditLimitDataKredit[$row->customers_id]["kredit"]) && ($arrKreditLimitDataKredit[$row->customers_id]["kredit"] > 0)) {
                            $disabled_button_kreditlimit = "disabled";
                            $add_note .= "<br><span class='meta'>" . $kreditLimitValidate[$numberValidate]["label"] . "</span>";
                            //                            $class_button = "btn-default";
                            $class_button = "btn-primary";
                        }
                    }
                    if ($row->kirim_metode_id == 1) {
                        if (($row->pengirim_id == 0) || ($row->pengirim_id == null)) {
                            $followupPengirimLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/doFollowupPengirim/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "?pengirim=pengirim');";
                            $disabled_kirim = ($row->step_number >= 3) ? "disabled" : "";
                            $add_note .= "<br><span class='meta'>Pengirim belum terdaftar pada transaksi ini.</span>";
                            //                            $strPengirim = "<div class='input-group'>";
                            //                            $strPengirim .= "<button class='btn $btn_block btn-warning pull-right' title='turn this entry into $stepLabel'
                            //                                href='javascript:void(0)'
                            //                                onClick =\"top.open_holdon();$followupPengirimLink\">Ambil untuk dikirim</button>";
                            //                            $strPengirim .= "</div class='input-group'>";
                        }
                    }


                    if ($this->session->login["employee_type"] == "employee_kirim") {
                        $tmp['action'] = "";
                    }
                    else {
                        //-------------------------
                        if ($row->cabang_id > 0) {// hanya bila transaksi cabang
                            if (($row->gudang_status_jenis != NULL) && ($row->gudang_status_jenis == "pusat")) {
                                $allowScaner = false;
                                if (isset($allowNextStepOtorisasi[$row->gudang_status_jenis])) {
                                    $disabled_button = $allowNextStepOtorisasi[$row->gudang_status_jenis];
                                    $add_note .= "<br><span class='meta'>* " . $allowNextStepOtorisasi["label"] . "</span>";
                                    $actionLabel = "* siap dikirim dari dc/pusat";
                                    if (isset($psrcData[$row->transaksi_id])) {
                                        $actionLabel = "menunggu pelunasan";
                                    }
                                }
                            }
                        }
                        //-------------------------
                        if (isset($transaksiHold[$row->transaksi_id])) {
                            $disabled_hold = "disabled";
                            $add_note .= "<br><span class='meta'>* Barang siap dikirim.<br>Edit/Reject SO tidak bisa digunakan.</span>";
                        }
                        //-------------------------
                        $tmp['action'] = "<div class='input-group'>";
                        if ($allowScaner == false || $isMob == 1) {
                            //                            $disabled_button
                            //                                $disabled_button_kreditlimit
                            //                                $disabled_kirim
                            //                            cekHere("[$disabled_button] [$disabled_button_kreditlimit] [$disabled_kirim]");
                            $tmp['action'] .= "<button class='btn $btn_block $class_button' title='turn this entry into $stepLabel' 
                                $disabled_hold
                                href='javascript:void(0)' 
                                onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</button>";
                        }
                        else {
                            $actionLabel = "QR untuk Handphone";
                            $targetFollowupLink = "followupDariHp";
                            $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                            $fpLink = MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number;
                            $followupLink = "BootstrapDialog.show(
                                   {
                                        title:'" . $actionLabel . "',
                                        message: $('<div></div>').load('" . $fpLink . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                    );";
                            $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</a>";
                        }
                        $tmp['action'] .= isset($strPengirim) ? $strPengirim : "";
                        $tmp['action'] .= "</div class='input-group'>";
                        $tmp['action'] .= $add_note;

                        if ($req_cancel_qty > 0 && $valid_qty == 0) {
                            $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
                            $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>menuggu approve cancel</button>";
                            //                            $tmp['action'] .= "<button type='button' class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>x</button>";
                            $tmp['action'] .= "</div>";
                        }
                    }

                }
                else {

                }

                //EDIT------------------------------
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    $arrStepAllow = array(1);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        if ($row->seller_id == my_id()) {
                            //                        cekHijau("bisa edit... denagn auto reject...");
                            $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evMaster = evaluateMain_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            //                        cekHitam("evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                            if ($evPre == null && $evPost == null && $evCom == null && $evMaster == null) {
                                $transaksiID_edit = $row->transaksi_id;
                                $transaksiCustomerNama_f = "<strong>" . $row->customers_nama . "</strong>";
                                $transaksiNomer = $row->nomer;
                                $transaksiNomer_f = formatField_he_format("nomer_nolink", $row->nomer);
                                //                                $link_edit = MODUL_PATH . "FollowUp/doPreEdit/" . $this->jenisTr . "/$transaksiID_edit/$currentStepNum?mode=autoPrePreview";
                                $link_edit = MODUL_PATH . "FollowUp/doPreEdit/" . $this->jenisTr . "/$transaksiID_edit/$currentStepNum";
                                $actionLabel_edit = "Edit";
                                $tmp['action'] .= "<a class='btn btn-warning btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='margin-top:5px;'
                                onclick=\"confirm_alert_result('Peringatan...', 'Anda akan merubah/edit transaksi an. $transaksiCustomerNama_f ?', '$link_edit');\">" . $actionLabel_edit . "</a>";
                            }
                        }
                    }
                }
                //------------------------------
                //REJECT------------------------------
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    $arrStepAllow = array(1, 2);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        //                        if ($row->seller_id == my_id()) {
                        //                        cekHijau("bisa edit... denagn auto reject...");
                        $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        //                        cekHitam("evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                        if ($evPre == null && $evPost == null && $evCom == null) {
                            $transaksiID_reject = $row->transaksi_id;
                            $masterRevertStep = ($currentStepNum - 1);
                            $childRevertStep = -($currentStepNum);
                            //                                $link_reject = MODUL_PATH . "FollowUp/doRevert/" . $this->jenisTr . "/$transaksiID_reject/$masterRevertStep/$childRevertStep/$currentStepNum?mode=autoPrePreview";
                            $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=reject";
                            $actionLabel_edit = "Reject 1 step";
                            $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject'\">" . $actionLabel_edit . " </button>";


                            //                                $link_reject_all = MODUL_PATH . "FollowUp/doRevertAll/" . $this->jenisTr . "/$transaksiID_reject/$currentStepNum?mode=autoPrePreview";
                            $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=rejectall";
                            $actionLabel_edit = "Reject all step";
                            $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='background-color:#000000;color:#ffffff;'
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
                        }
                        //                        }
                    }
                }
                //------------------------------


                $tmpMark = array();
                $tmp['keterangan'] = "-";
                if ($row->partial == 1) {
                    $tmpMark['style'] = "background-color:yellow;";
                    $tmp['keterangan'] = "<span style='color:red;'>transaksi diproses sebagian</span>";
                }


                $arrayOnprogress[] = $tmp;
                $arrayOnprogressGroup[$currentStepNum][] = $tmp;

                $arrayOnprogressPartialMark[] = $tmpMark;
                $arrayOnprogressGroupPartialMark[$currentStepNum][] = $tmpMark;

            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            $isDisableMakeTrans = isset($this->configUi[$this->jenisTr]['isDisableMakeTrans']) ? $this->configUi[$this->jenisTr]['isDisableMakeTrans'] : false;
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            if ($isDisableMakeTrans) {
                $addLink = null;
            }
            else {
                $addLink = array(
                    "link" => $targetUrl,
                    "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
                );
            }

        }
        else {
            $addLink = null;
        }
        //endregion

        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $historyFieldsDt = isset($this->configUi[$jenisTr]['historyFields'][$jenisTrsub]) ? $this->configUi[$jenisTr]['historyFields'][$jenisTrsub] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $availDbTable = $tr->getAvailTable($historyFieldsDt);

        //-----------------------------------------------------
        $arrJenisTrCek = array("587", "687", "1587", "1687");
        if (in_array($jenisTr, $arrJenisTrCek)) {
            $transaksiName = isset($this->configUi[$jenisTr]['label']) ? $this->configUi[$jenisTr]['label'] : NULL;
            $subplace = isset($this->configUi[$jenisTr]['steps'][1]['subplace']) ? $this->configUi[$jenisTr]['steps'][1]['subplace'] : NULL;
            if ($subplace != NULL) {
                if (($subplace == "warehouse") && ($this->session->login['gudang_id'] < 0)) {
                    //                    cekPink("SESUAI");
                    $msg = NULL;
                }
                elseif (($subplace == "warehouse_ng") && ($this->session->login['gudang_id'] > 0)) {
                    //                    cekPink("SESUAI NOT GOOD");
                    $msg = NULL;
                }
                else {
                    $msg = "Anda tidak memiliki kewenangan untuk membuat request $transaksiName";
                    $addLink = array();
                }
            }
        }

        //-----------------------------------------------------
        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        //        cekMerah("Grabing data " . $val . " Seconds");

        $link_undoneList_kurir = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";

        $data = array(
            //            "mode" => isset($this->configUi[$jenisTr]["mode"]) ? $this->configUi[$jenisTr]["mode"] : $this->uri->segment(3),
            "mode" => "viewUndoneItems",
            "isMobile" => $isMob,
            "errMsg" => $this->session->errMsg,
            "template" => $this->configUi[$jenisTr]["template"],
            "title" => $this->configUi[$jenisTr]["label"],
            "subTitle" => $this->configUi[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            'addLink' => $addLink,
            "allSteps" => $this->allSteps,
            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> recent " . $this->configUi[$jenisTr]["label"] . " histories",
            "arrayHistoryLabels" => array("dtime" => "time") + $historyFields,
            "arrayHistoryLabelsDt" => $historyFieldsDt,
            "availDbTable" => $availDbTable,
            "arrayHistory" => isset($arrayHistory) ? $arrayHistory : array(),
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> TRANSAKSI YANG PERLU ACTION ",
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgressToPay" => $paymentConfig,
            "itemLabels" => isset($itemLabels) ? $itemLabels : "",
            "srcLabel" => isset($srcLabel) ? $srcLabel : "",
            //            "arrayOnProgress" => array_merge($arrayOnprogress, $arrayOnprePre, $arrayOnpreDistribution),
            "steps" => $isMob == true ? array(2 => $steps[2]) : $steps,
            "arrayOnProgress" => (isset($arrayOnprogress) && sizeof($arrayOnprogress) > 0) ? $arrayOnprogress : array(),
            "arrayOnprogressGroup" => (isset($arrayOnprogressGroup) && (sizeof($arrayOnprogressGroup) > 0)) ? $arrayOnprogressGroup : array(),

            "arrayOnprePre" => $arrayOnprePre,
            "arrayOnprePreGroup" => $arrayOnprePreGroup,

            "arrayOnpreDistribution" => isset($arrayOnpreDistribution) ? $arrayOnpreDistribution : array(),
            "arrayOnpreDistributionGroup" => isset($arrayOnpreDistributionGroup) ? $arrayOnpreDistributionGroup : array(),

            "entities" => isset($entities) ? $entities : array(),
            "recapTitle" => "<span class='fa fa-newspaper-o'></span> today " . $this->configUi[$jenisTr]["label"] . " reports",
            "arrayRecapLabels" => isset($recapLabels) ? $recapLabels : array(),
            "arrayRecap" => isset($arrayRecap) ? $arrayRecap : array(),

            "onprogressViewTitle" => "<span class='fa fa-eye'></span> show incomplete step " . $this->configUi[$jenisTr]["label"],
            "onprogressViewSubTitle" => "<span class='text-black'>(daftar transaksi yang masih stanby di cabang tujuan)</span>",
            "arrayOnProgressView" => isset($arrayOnProgressView) ? $arrayOnProgressView : array(),
            "stepHistoryFields" => isset($stepHistoryFields) ? $stepHistoryFields : array(),

            "selectProcessor" => $selectProcessor,
            "sumFooter" => (isset($sumFooterResult) && sizeof($sumFooterResult) > 0) ? $sumFooter : array(),
            "scriptBottom" => $scriptBottom,
            "btnLabel" => $extData,
            "actionTarget" => $extact,
            "sumFooter" => isset($sumFooter) ? $sumFooter : "",

            "arrayOnprogressPartialMark" => (isset($arrayOnprogressPartialMark) && sizeof($arrayOnprogressPartialMark) > 0) ? $arrayOnprogressPartialMark : array(),
            "arrayOnprogressGroupPartialMark" => (isset($arrayOnprogressGroupPartialMark) && sizeof($arrayOnprogressGroupPartialMark) > 0) ? $arrayOnprogressGroupPartialMark : array(),

            "defaultItemTrgEditable" => isset($defaultItemTrgEditable) ? $defaultItemTrgEditable : array(),
            "editItemTrg" => MODUL_PATH . "_followupLiveEdit/editEfaktur/" . $this->jenisTr . "/",


            "link_scan_mobile" => ($this->session->login["employee_type"] == "employee_kirim") ? MODUL_PATH . "FollowUp/followupScanMobile/$jenisTr" : NULL,
            "link_undoneList_kurir" => $link_undoneList_kurir,
        );
        $this->load->view("transaksi", $data);
    }

    /* ---------------------------------
     * kurir job
     * --------------------------------------*/
    public function viewUndoneItemsIndexKurir()
    {
        /* ----------------------------------------------------------------------
        * deteksi mobile auto atau hanya orang tertentu,
        * diatur di heWeb mobile
        * ----------------------------------------------------------------------*/
        $isMob = isMobile_he_misc();
        $jenisTr = $this->jenisTr;

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $condites = array(
            "link_id" => 0,
            "pengirim_id" => my_id(),
            "jenis_master" => $jenisTr,
            "jenis" => "582spd",
            "kirim_bukti" => 0,

        );
        $this->db->where($condites);
        $tr_saya = $tr->lookupAll()->result();
        //        showLast_query("orange");
        //        cekHere(sizeof($tr_saya));

        $str_data = "";
        foreach ($tr_saya as $item) {
            $tr_id = $item->id;
            $customers_nama = $item->customers_nama;
            $nomer_top = $item->nomer_top;
            $nomer = $item->nomer;
            $nomer_top_f = formatField_he_format("nomer_nolink", $nomer_top);
            $nomer_f = formatField_he_format("nomer_nolink", $nomer);

            $str_data .= "<li>";
            $str_data .= "<button type='button' class='btn btn-primary btn-block' onclick=\"kirim_bukti($tr_id);\">";
            $str_data .= "Upload bukti pengiriman $customers_nama";
            $str_data .= "<br>Nomer SO: $nomer_top_f";
            $str_data .= "<br>Nomer Pengiriman: $nomer_f";
            $str_data .= "</button>";
            $str_data .= "</li>";

        }
        $view_btn_back = "<button type='button' class='btn btn-danger' id='btnback'>back</button>";
        $view_data = "";
        $view_data .= "<ol>";
        $view_data .= $str_data;
        $view_data .= "</ol>";

        $p = New Layout();
        $p->setLayoutBoxHeading("Daftar Pengiriman", $view_btn_back);
        $p->setLayoutBoxBody(true);
        $render = $p->layout_box($view_data);

        $link_index_kurir = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr";
        $link_kirim_bukti = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurirForm/$jenisTr";
        $render .= "<script>
            function kirim_bukti(trid){                
                $('#job_kurir').load('$link_kirim_bukti/'+trid);
            }
            
            function btn_back(){                
                $('#job_kurir').load('$link_index_kurir');
            }
            
            // $('#btnback').click(function() {
            //     $('#undoneList').load('$link_index_kurir');
            // });
        </script>";

        // arrPrintPink(count($tr_saya));
        // arrPrintPink($tr_saya);

        echo "$render";
    }

    public function viewUndoneItemsIndexKurirForm()
    {
        //        arrPrintPink(url_segment());
        //        arrPrintPink($_GET);
        $jenisTr = url_segment(4);
        $trid = url_segment(5);
        $link_upload = MODUL_PATH . "Transaksi/doUploadBukti/$jenisTr";
        $str = "";
        $str .= "<form method='post' id='kirim_bukti' enctype='multipart/form-data' action='$link_upload' target='result'>";
        $str .= "<input type='file' name='userfile'>";
        $str .= "<input type='hidden' name='trid' value='$trid'>";
        $str .= "</form>";

        $view_btn_back = "<button type='button' class='btn btn-danger' id='btnback' onclick='btn_back();'>back</button>";
        $p = New Layout();
        $p->setLayoutBoxHeading("upload foto", $view_btn_back);
        $p->setLayoutBoxBody(true);
        $btn_footer = "<button type='button' class='btn btn-primary' onclick=\"$('#kirim_bukti').submit();\">Upload</button>";
        $p->setLayoutBoxFooter($btn_footer);
        $render = $p->layout_box($str);

        echo $render;
    }

    public function doUploadBukti()
    {
        //        arrPrintPink(url_segment());
        // arrPrint($_REQUEST);
        // arrPrint($_FILES);
        // arrPrint($_POST);
        $jenisTr = url_segment(4);
        $trid = $_POST['trid'];
        $userfile = $_FILES['userfile'];
        // cekBiru($userfile);

        $balikan = upload_image($userfile);

        arrPrint($balikan);

        $img_cdn = $balikan->full_url;
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $updDatas = array(
            "kirim_bukti" => $img_cdn,
            "kirim_bukti_dtime" => dtimeNow(),
        );
        $condite = array(
            "id" => $trid
        );
        $tr->updateData($condite, $updDatas);

        $link_index_kurir = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr";
        // $link_kirim_bukti = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurirForm/$jenisTr";
        $render = "<script>
            
                top.$('#undoneList').load('$link_index_kurir');
            // function btn_back(){                
            // }
            
        </script>";

        echo $render;
    }

    // ----------------------------------------------------end kurir

    public function viewRequestItems()
    {

        $origJenisTr = $this->uri->segment(4);
        $jenisTr = isset($this->configUi[$origJenisTr]['aliasMainTrans']) ? $this->configUi[$origJenisTr]['aliasMainTrans'] : $origJenisTr;

        $this->jenisTr = $jenisTr;
        // $cCode = $this->cCode;
        $cCode = "_TR_" . $this->jenisTr;
        $cCodeOrig = "_TR_" . $origJenisTr;

        $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array();
        $tabHistoryFields = isset($this->configUi[$jenisTr]['tabHistoryFields']) ? $this->configUi[$jenisTr]['tabHistoryFields'] : array();
        $tabFieldsItems = isset($this->configUi[$jenisTr]['tabFieldsItems']) ? $this->configUi[$jenisTr]['tabFieldsItems'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $dueDateAllow = isset($this->configUi[$jenisTr]['updateDueDate']) ? $this->configUi[$jenisTr]['updateDueDate'] : array();


        $mb = New MobileDetect();
        $isMob = $mb->isMobile();

        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }
        $glanceHistoryFields = isset($this->configUi[$jenisTr]['glanceHistoryFields']) ? $this->configUi[$jenisTr]['glanceHistoryFields'] : array("nomer" => "receipt no");
        $glanceHistoryFields2 = array("nomer" => "receipt no"); //init;

        //region lookup on-going transactions
        $arrayOnprogress = array();
        $arrOver_due = array();
        $progressFields = $historyFields;


        //region lookup on-going from connected requests
        if (!isset($_SESSION['undoneQty'])) {
            $_SESSION['undoneQty'] = array();
        }
        if (!isset($_SESSION['undoneQty'][$this->jenisTr])) {
            $_SESSION['undoneQty'][$this->jenisTr] = 0;
        }

        $progress2Fields = array();
        $arrayOnprogress2 = array();
        $tmpTabData = array();
        $reqFormTarget = "";
        $allowMultiSelect = false;
        $needToClear = false;
        $allowMultiSelect = isset($this->configUi[$origJenisTr]['tabRequestCode']['allowMultiSelect']) ? $this->configUi[$origJenisTr]['tabRequestCode']['allowMultiSelect'] : false;


        if (isset($this->configUi[$this->uri->segment(4)]['tabRequestCode'])) {
            $masterRefCode = $this->configUi[$this->uri->segment(4)]['tabRequestCode']['masterCode'];
            $stateRefCode = $this->configUi[$this->uri->segment(4)]['tabRequestCode']['stateCode'];
            $stateRefNum = $this->configUi[$this->uri->segment(4)]['tabRequestCode']['stepNumber'];
            //            cekHere("$masterRefCode :: $stateRefCode :: $stateRefNum");
            $progress2Fields = isset($this->configUi[$masterRefCode]['shortHistoryFields']) ? $this->configUi[$masterRefCode]['shortHistoryFields'] : "";
            $glanceHistoryFields2 = isset($this->configUi[$masterRefCode]['glanceHistoryFields']) ? $this->configUi[$masterRefCode]['glanceHistoryFields'] : array("nomer" => "receipt no");

            $reqFormTarget = MODUL_PATH . get_class($this) . "/swapFrom/" . $this->uri->segment(4);

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("transaksi.jenis_master='" . $masterRefCode . "'");
            $tr->addFilter("transaksi.jenis='" . $stateRefCode . "'");
            $tr->addFilter("transaksi.step_current='" . $stateRefNum . "'");
            // if (isset($_GET['test']) && ($_GET['test'] == 1)) {
            //                $tmpByReq = $tr->lookupRecentHistories()->result();

            $tr->addFilterJoin("transaksi_data.next_substep_code='" . $masterRefCode . "'");
            $tr->addFilterJoin("transaksi_data.produk_jenis='supplies'");
            $tr->addFilterJoin("transaksi_data.valid_qty>0");
            $tmpByReq = $tr->lookupJoined();
            // }
            // else {
            //     $tmpByReq = array();
            // }
            //            cekHijau(__LINE__ . "<br>" . $this->db->last_query());
            //           matiHere(sizeof($tmpByReq));

            $needToClear = false;
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $needToClear = true;
            }

            $_SESSION['undoneQty'][$this->jenisTr] = sizeof($tmpByReq);

            //arrPrint($tmpByReq);
            if (sizeof($tmpByReq) > 0) {
                $arrIds = array();
                foreach ($tmpByReq as $row) {
                    $arrIds[] = $row->id;
                }

                //                $tr->setFilters(array());
                //                $tr->addFilterJoin("transaksi_data.transaksi_id IN ('" . implode("','", $arrIds) . "')");
                //                $tr->addFilterJoin("transaksi_data.next_substep_code='" . $masterRefCode . "'");
                //                $tr->addFilter("transaksi.link_id=0");
                //                $tr->addFilterJoin("transaksi_data.produk_jenis='supplies'");
                //                $tr->addFilterJoin("transaksi_data.valid_qty>0");
                //                $trTr = $tr->lookupJoined();
                //                cekMerah(__LINE__ . " -/- " . $this->db->last_query());
                //arrPrint($trTr);
                $trTr = $tmpByReq;
                $arrayTabHistory = array();
                if (sizeof($trTr) > 0) {
                    $tmpItems = array();
                    $sumValidQty = array();
                    $sumPrdOrdJml = array();
                    $listTrxByProd = array();
                    $reqCtr = 0;
                    foreach ($trTr as $ky => $rows) {
                        $reqCtr++;
                        $totalSumPrdOrdJmlTR = 0;
                        if (!isset($sumValidQtyID[$rows->produk_id])) {
                            $sumValidQtyID[$rows->produk_id] = 0;
                        }
                        if (!isset($sumPrdOrdJmlID[$rows->produk_id])) {
                            $sumPrdOrdJmlID[$rows->produk_id] = 0;
                        }
                        if (!isset($sumValidQtyTR[$rows->transaksi_id][$rows->produk_id])) {
                            $sumValidQtyTR[$rows->transaksi_id][$rows->produk_id] = 0;
                        }
                        if (!isset($sumPrdOrdJmlTR[$rows->transaksi_id][$rows->produk_id])) {
                            $sumPrdOrdJmlTR[$rows->transaksi_id][$rows->produk_id] = 0;
                        }
                        $sumValidQtyID[$rows->produk_id] += $rows->valid_qty;
                        $sumValidQtyTR[$rows->transaksi_id][$rows->produk_id] += $rows->valid_qty;
                        $listTrxByProd[$rows->produk_id][] = $rows->nomer_top;
                        $sumPrdOrdJmlID[$rows->produk_id] += $rows->valid_qty;
                        $sumPrdOrdJmlTR[$rows->transaksi_id][$rows->produk_id] += $rows->valid_qty;
                        $arrProduk[$rows->transaksi_id][$rows->produk_id] = "<div>" . $rows->produk_nama . "</div>";
                        $needToClear = false;
                        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                            $needToClear = true;
                        }

                        foreach ($tabHistoryFields as $key => $row) {
                            //cekHere($key);
                            switch ($key) {
                                case "vendor_id":
                                    break;
                                case "transaksi_id":
                                    $totalSumPrdOrdJmlTR = 0;
                                    $inputType = $allowMultiSelect == true ? "checkbox" : "radio";
                                    foreach ($sumPrdOrdJmlTR[$rows->transaksi_id] as $pid => $val) {
                                        $totalSumPrdOrdJmlTR += $val;
                                    };
                                    $produk_btn = "<span href='" . base_url() . "Addons/ViewDetails/index/" . $rows->transaksi_id . "' name='qtips' class='btn btn-sm'> berisi " . sizeof($sumPrdOrdJmlTR[$rows->transaksi_id]) . " item, Total " . $totalSumPrdOrdJmlTR . " unit  </span>";
                                    $clickEvent = "";
                                    if ($inputType == "radio" && !$needToClear) {
                                        $clickEvent = "onclick=\"document.getElementById('$key').submit()\"";
                                    }

                                    $transaksi_id = array(
                                        "select" => "<input type=$inputType name='trID[]' value='" . $rows->transaksi_id . "' id='select_" . $key . "_" . $reqCtr . "' $clickEvent>",
                                        "nomer" => formatField_he_format('nomer', $rows->nomer),
                                        "nomer_top" => formatField_he_format('nomer_top', $rows->nomer_top),
                                        "produk_id" => $rows->transaksi_id,
                                        "dtime" => formatField_he_format('dtime', $rows->dtime) . "<br><span class='meta'>" . timeSince(strtotime($rows->dtime)) . "</span>",
                                        "oleh_nama" => $rows->oleh_nama,
                                        "cabang2_nama" => $rows->cabang2_nama,
                                        "purchased" => 0,
                                        "arrProduk" => $produk_btn,
                                        "produk_nama" => $rows->produk_nama,
                                        "valid_qty" => $sumValidQtyTR[$rows->transaksi_id][$rows->produk_id],
                                        "produk_ord_jml" => $sumPrdOrdJmlTR[$rows->transaksi_id][$rows->produk_id],
                                    );
                                    $nextStepNum = $rows->next_step_num;
                                    $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/" . $rows->transaksi_id . "/$nextStepNum/" . $rows->sub_step_number . "');";

                                    $actionLabel = "reject";
                                    $tmp = "<div class='input-group'>";
                                    $tmp .= "<a class='btn btn-danger btn-block' title='' href='javascript:void(0)' 
                                        onClick =\"$followupLink\">" . $actionLabel . "</a>";

                                    $tmp .= "</div class='input-group'>";
                                    $transaksi_id['action'] = $tmp;

                                    $tmpTabData[$key][$rows->transaksi_id] = $transaksi_id;
                                    break;
                                case "produk_id":
                                    $inputType = $allowMultiSelect == true ? "checkbox" : "radio";
                                    $arrTrxId = $listTrxByProd[$rows->produk_id];
                                    $listingTrx = array();
                                    foreach ($arrTrxId as $pid => $nomer) {
                                        $listingTrx[] = formatField_he_format('nomer_top', $nomer);
                                    }
                                    $clickEvent = "";
                                    if ($inputType == "radio" && !$needToClear) {
                                        $clickEvent = "onclick=\"document.getElementById('$key').submit()\"";
                                    }

                                    $produk_id = array(
                                        "select" => "<input type=$inputType name='prdID[]' value='" . $rows->produk_id . "' id='select_" . $key . "_" . $reqCtr . "' $clickEvent>",
                                        "produk_id" => $rows->produk_id,
                                        "dtime" => formatField_he_format('dtime', $rows->dtime) . "<br><span class='meta'>" . timeSince(strtotime($rows->dtime)) . "</span>",
                                        "produk_nama" => $rows->produk_nama,
                                        "nomer_top" => implode("<br>", $listingTrx),
                                        "produk_ord_jml" => $sumPrdOrdJmlID[$rows->produk_id],
                                    );

                                    $tmpTabData[$key][$rows->produk_id] = $produk_id;
                                    break;
                            }

                        }

                    }

                    $arrayOnprogress2 = $tmpTabData;
                }
            }
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $needToClear = true;
            }
        }
        //endregion
        cekHitam(":: cetak onProgress2 ::");
        arrPrintWebs($arrayOnprogress2);


        $data = array(
            "mode" => $this->uri->segment(3),
            "isMobile" => $isMob,
            "arrayProgressLabels" => $progressFields,
            "arrayProgress2Labels" => $glanceHistoryFields2,
            "arrayOnProgress" => $arrayOnprogress,
            "arrayOnProgress2" => $arrayOnprogress2,
            "needToClear" => $needToClear,
            "reqFormTarget" => $reqFormTarget,
            "allowMultiSelect" => $allowMultiSelect,
            "overDue" => $arrOver_due,
            "tabFieldsItems" => $tabFieldsItems,
            "tabHistoryFields" => $tabHistoryFields,
            "clearCartTarget" => MODUL_PATH . "_shoppingCart/reset/" . $this->jenisTr,
            "scriptBottom" => "",
        );

        $this->load->view("transaksi", $data);

    }

    //done update modul
    public function viewResumeTimeLine()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1[0]) > 0) {
            foreach ($tmp1[0] as $tmpKey => $tmpVal) {
                $rkey_field = $tmpKey;
                if ($rkey_field == "nomer") {
                    $rkey_field = "nomer_nolink";
                }
                //                $globalDatas[$rkey] = formatField_he_format($rkey_field, $rval, "", "");
                $globalVars[$tmpKey] = formatField_he_format($rkey_field, $tmpVal, "", "");
            }
        }
        $jenisMasterID = $tmp1[0]->id_master;
        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = $tmp1[0]->step_number < 1 ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items", "main_elements");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;
        //cekHere("[$transaksiID]");
        // membaca status edit di TOPID
        $tr = new MdlTransaksi();
        $tr->addFilter("id='$transaksiTopID'");
        $tmpTop = $tr->lookupAll()->result();
        $arrTopEdit = array();
        if (sizeof($tmpTop) > 0) {
            if ((isset($tmpTop[0]->status_edit)) && ($tmpTop[0]->status_edit == 1)) {
                $arrTopEdit[$transaksiTopID] = array(
                    "status_edit" => $tmpTop[0]->status_edit,
                    "edit_dtime" => $tmpTop[0]->edit_dtime,
                    "edit_id" => $tmpTop[0]->edit_id,
                    "edit_name" => $tmpTop[0]->edit_name,
                );
            }
        }

        //        cekHere("[$transaksiID]");
        //region swap from registry
        $pairAddRegistry = array("main_elements");
        foreach ($pairAddRegistry as $regkey) {
            $pairRegistry[] = $regkey;
        }
        //        arrPrint($pairRegistry);
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();
        showlast_query("hitam");
        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {

                        if ($eParams->$param == null) {
                            $eParams->$param = blobEncode(array());
                        }
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion
        // arrPrint($main_elements);
        $deliveryDetail = blobDecode($main_elements["deliveryDetails"]["contents"]);
        // arrPrint($main_elements["deliveryDetails"]["contents"]);
        arrPrint($deliveryDetail);

        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $tmpSign = $trs->lookupSignaturesByMasterID($masterID)->result();
        //         cekLime($this->db->last_query());
        // arrPrint($tmpSign);
        $arrCounters = blobDecode($tmp1[0]->counters);
        $urutCounters = NULL;
        if (sizeof($arrCounters) > 0) {
            $key2 = $tmp1[0]->jenis . "|" . $tmp1[0]->cabang_id;
            $urutCounters = digit_5($arrCounters["stepCode|placeID"][$key2]);
        }
        if ($urutCounters != NULL) {
            $urutCounters = "-$urutCounters";
        }

        if (isset($this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames'])) {
            $resumeFields = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['selectFields'];
            $resumeTitle = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['title'];
            $resumeName = isset($tmp1[0]->$resumeFields) ? $tmp1[0]->$resumeFields : "";
        }
        else {
            $resumeTitle = "";
            $resumeName = "";
        }

        $arrSignName = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $ii => $row) {
                $signValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "label" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "dtime" => $row->dtime,

                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,

                    "oleh" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                    "prev_id" => isset($row->prev_id) ? $row->prev_id : 0,
                    "link_id" => isset($row->transaksi_id) ? $row->transaksi_id : 0,
                );
                $arrSignName[$ii] = array(
                    "nama" => $row->oleh_nama,
                    "dtime" => $row->dtime,
                );
            }
        }
        $signStr = "";
        //endregion

        //entry points
        $trep = new MdlTransaksi();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
        $epValues = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;


                //                $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
                $epValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    //                    "dtime" => isset($arrSignName[$ii]['dtime']) ? $arrSignName[$ii]['dtime'] : $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    //                    "oleh" => isset($arrSignName[$ii]['nama']) ? $arrSignName[$ii]['nama'] : $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                );
            }
        }

        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;

        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel table-responsive'>";
            $signStr .= "<h4 class='text-blue'><span class='fa fa-sign-in'></span> entry points </h4>";

            $signStr .= "<h5 class='text-blue'><span class='fa fa-tasks'></span> $resumeTitle  $resumeName</h5>";
            $signStr .= "<table class='table table-condensed'>";
            $signStr .= "<tr bgcolor='#f0f0f0'>";
            $signStr .= "<td class='text-muted'>date</td>";
            $signStr .= "<td class='text-muted'>title</td>";
            $signStr .= "<td class='text-muted'>branch</td>";
            $signStr .= "<td class='text-muted'>transaction number</td>";
            $signStr .= "<td class='text-muted'>person</td>";
            $signStr .= "<td class='text-muted'>tool</td>";
            foreach ($signArray as $k => $sSpec) {
                //                arrPrintPink($sSpec);
                $jenis = $sSpec['jenis'];
                $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                $nomerOrig = $sSpec['nomer_orig'];
                $nomerOrigEx = explode("_", $nomerOrig);
                $nomerOrigNota = $nomerOrigEx[0];
                $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;

                $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];


                $edit_description = "";
                if (isset($arrTopEdit[$sSpec['link_id']])) {
                    $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                    $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                    $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                }


                $signStr .= "<tr>";
                $signStr .= "<td>" . formatField_he_format("date_time", $sSpec['dtime']);
                $signStr .= "</td>";
                $signStr .= "<td>" . ($sSpec['label']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                $signStr .= "<td>" . (formatField_he_format("nomer_nolink", $nomerViewFinal)) . $signDeskripsi . "<br>$edit_description";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $oleh_nama . "";
                $signStr .= "</td>";

                $signStr .= "<td>";
                $signStr .= formatField_he_format("print_label", $nomerOrigNota, $this->jenisTr, MODUL_PATH);
                $signStr .= "</td>";

                $signStr .= "</tr>";
            }
            $signStr .= "</table class='table table-condensed'>";

            $signStr .= "</div class='panel'>";
        }

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $allowedView = $this->config->item('userGroup_jurnal') != null ? $this->config->item('userGroup_jurnal') : array();
        $rekeningAlias = $this->config->item('accountAlias') != null ? $this->config->item('accountAlias') : array();

        $lItems = array();
        $headers = array();
        $arrCabang = array();
        if (array_intersect($allowedView, $mems)) {
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Mdls/MdlCabang");

            $cb = New MdlCabang();
            $tmpCb = $cb->lookupAll()->result();
            $arrCabang = array();
            if (sizeof($tmpCb) > 0) {
                foreach ($tmpCb as $spec) {
                    $arrCabang[$spec->id] = $spec->nama;
                }
            }

            $j = new ComJurnal();
            $j->addFilter("transaksi_no='$no'");
            if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

            }
            else {
                // dimatikan dulu untuk checking jurnal lengkapnya...........
                //                $j->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            //            $j->setSortBy(array("kolom" => "urut", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            if (sizeof($tmp) > 0) {
                /* ---------------------------------------
                 * array link untuk mengarahkan ke halaman mutasi yg dikendaki
                 * -----------------------------------*/

                //region Description
                $mutasiLinks = array();
                foreach ($accountChildsLinks as $rekNama => $rekLink) {
                    $mutasiLinks[$rekNama] = $rekLink . "/" . $main['pihakID'] . "?o=" . $main['cabangID'] . "&trID=$transaksiID";
                }
                //endregion

                foreach ($tmp as $row) {
                    $d_start = backCustomDate("30", $row->dtime);
                    $d_stop = $row->dtime;
                    if ($row->j_jenis == "items") {
                        $blob_ext = blobEncode($row->rekening);
                        //                        Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/1076?o=-1&ext2_id=&main_ext2_id=
                        $defLink = "Ledger/viewMoveDetails/RekeningPembantuProduk/" . $row->rekening_main . "/" . $row->extern_id . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop&blob_ext=$blob_ext";
                    }
                    else {
                        $defLink = "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                        //                    $defLink = "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                    }

                    //                    $rekening_alias = isset($rekeningAlias[$row->rekening]) ? $rekeningAlias[$row->rekening] : $row->rekening;
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        //$strLink = key_exists($row->rekening, $mutasiLinks) ? $mutasiLinks[$row->rekening] : $defLink;
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }
                //                arrPrint($lItems);
                ksort($lItems);
            }


        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $mobMode = $isMob == true ? "1" : "0";

        $printSettings = isset($this->configLayout[$jenisMasterTrans]['allowPrint']['1']) ? $this->configLayout[$jenisMasterTrans]['allowPrint']['1'] : array();
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Transaksi/viewReceipt/";

        $valasType = (isset($main['currencyDetails__label'])) ? "&type=" . blobEncode($main['currencyDetails__label']) : "";
        $valasFKali = "&f=" . blobEncode(1);

        if (sizeof($tmpSign) > 0) {
            if (isset($printSettings['size'])) {
                switch ($printSettings['size']) {
                    case "normal":
                        $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=1$valasType$valasFKali');";
                        break;
                    case "small":
                        $receiptLink = "top.popSmall('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=2');";
                        break;
                    default:
                        //                                cekkuning("size: UNKNOWN");
                        break;
                }
            }
            else {
                $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=3$valasType$valasFKali');";
            }
        }
        else {
            $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?st=4');";
        }


        // ============== TAMBAHAN ============== ============== ==============
        $itemLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields'][1] : array();
        $itemLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1] : array();
        $itemLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1] : array();
        $itemNumLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1] : array();
        $itemNumLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1] : array();
        $itemNumLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1] : array();
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1];
        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;


        $arrCons = array();
        $headerScheme = array();

        if ($showScheme) {

            $awal_pinjaman = isset($main['awal_pinjaman']) ? $main['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($main['jatuh_tempo']) ? $main['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($main['harga']) ? $main['harga'] : 0;
            $rate_bunga = isset($main['persen_bunga']) ? $main['persen_bunga'] : 0;

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

        $itemLabelsTop = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $item2LabelsTop = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $item3LabelsTop = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemLabelBlacklist = array(
            "stok_center",
            "stok",
        );

        foreach ($itemLabelsTop as $key => $val) {
            if (in_array($key, $itemLabelBlacklist)) {
                unset($itemLabelsTop[$key]);
            }
        }
        if (isset($this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount']) && $this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount'][1] == true) {
            unset($itemLabelsTop['subtotal']);
            unset($item2LabelsTop['subtotal']);
            unset($item3LabelsTop['subtotal']);
        }
        $blacklistView = $this->config->item('userGroup_blacklist') != null ? $this->config->item('userGroup_blacklist') : array();
        $addFildRowFields = array();


        $allowedViewTop = false;
        foreach ($mems as $memsVal) {
            if (!in_array($memsVal, $blacklistView)) {
                $allowedViewTop = true;
            }
        }

        if ($allowedViewTop == true) {


            $m = new MdlTransaksi();
            $m->setFilters(array());
            $tmpReg = $m->lookupDataRegistriesByMasterID($transaksiTopID)->result();
            $regfields = $m->getFields()["dataRegistry"];
            $addRow = isset($this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes']) ? $this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes'] : array();
            $itemNotApprove = isset($this->configUi[$jenisMasterTrans]['closedRequest'][1]) ? $this->configUi[$jenisMasterTrans]['closedRequest'][1] : true;
            $detailCore = isset($this->configCore[$jenisMasterTrans]['valueGates']['detail']) ? $this->configCore[$jenisMasterTrans]['valueGates']['detail'] : array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regfields as $params) {
                        // cekHitam($params);
                        if (isset($row->$params) && ($params != "transaksi_id")) {

                            $var = $params . "_reg";
                            $$var = unserialize(base64_decode($row->$params));
                        }

                    }
                    // $$var = unserialize(base64_decode($row->values));
                }
                if (sizeof($addRow) > 0) {
                    foreach ($addRow as $gateKey => $mpFields) {

                        if (isset($mpFields['hideRow']) && $mpFields['hideRow'] == true) {

                        }
                        else {
                            $addFildRowFields[$gateKey] = $mpFields['label'];
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    //                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID)->result();
                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID);

                    $notApprove = array();
                    foreach ($dTmp as $spec) {
                        if ((isset($spec->valid_qty_no_approve)) && ($spec->valid_qty_no_approve > 0)) {
                            $notApprove[$spec->produk_id] = $spec->valid_qty_no_approve;
                        }
                    }
                    $mainNotApprove = array();
                    $itemsNotApprove = array();
                    if (sizeof($notApprove) > 0) {
                        $itemsNotApprove = $items_reg;
                        foreach ($notApprove as $pid => $pqty_not_approve) {
                            $itemsNotApprove[$pid]['qty'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['jml'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['subtotal'] = $pqty_not_approve * $itemsNotApprove[$pid]['harga'];
                            if (sizeof($detailCore) > 0) {
                                foreach ($detailCore as $key_c => $val_c) {
                                    $itemsNotApprove[$pid]['sub_' . $key_c] = $pqty_not_approve * $itemsNotApprove[$pid][$key_c];
                                    if (!isset($mainNotApprove[$key_c])) {
                                        $mainNotApprove[$key_c] = 0;
                                    }
                                    $mainNotApprove[$key_c] += ($pqty_not_approve * $itemsNotApprove[$pid][$key_c]);
                                }
                            }
                            if (!isset($mainNotApprove['harga'])) {
                                $mainNotApprove['harga'] = 0;
                            }
                            $mainNotApprove['harga'] += ($pqty_not_approve * $itemsNotApprove[$pid]['harga']);
                        }
                    }
                }
            }

            //tambahan jika ada result item
            // arrPrint($main_reg);
            if (sizeof($rsltItems_reg) > 0) {
                //region reference detil
                $m->setFilters(array());
                $tmpRegRef = $m->lookupDataRegistriesByMasterID($main_reg['referenceID']);
                foreach ($tmpRegRef as $refRow) {
                    foreach ($regfields as $params) {
                        if (isset($refRow->$params)) {
                            $varR = $params . "_ref";
                            $$varR = unserialize(base64_decode($refRow->$params));
                        }
                    }
                }

                $tmpRowRslts_ref = array();
                $totalNilaiRef = 0;
                if (sizeof($rsltItems_ref) > 0) {
                    foreach ($rsltItems_ref as $rowRefID => $rowref_data) {
                        // $hpp = number_format($rowref_data['hpp'],0);
                        $hpp = $rowref_data['hpp'];
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] = 0;

                        }
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] = 0;
                        }

                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] += $rowref_data['qty'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] += $rowref_data['sub_hpp'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['harga'] = $rowref_data['hpp'];
                        $totalNilaiRef += $rowref_data['sub_hpp'];
                    }
                    $rowDetilRslt_label[$main_reg['pihakExternID']] = array(
                        "harga" => "harga",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                    );
                }
                //endregion

                //region current detil
                $tmpRowRslts = array();
                $tmpRowRsltsnilai = 0;
                foreach ($rsltItems_reg as $rowFifo => $fifoData) {
                    // $hpp_0 = number_format($fifoData['hpp'],0);
                    $hpp_0 = $fifoData['hpp'];
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] = 0;
                    }
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] = 0;
                    }
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] += $fifoData['qty'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] += $fifoData['sub_hpp'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['harga'] = $fifoData['hpp'];
                    $tmpRowRsltsnilai += $fifoData['sub_hpp'];
                }
                $tmpRowRslts_label = array(
                    "nama" => "produk",
                    "qty" => "qty",
                    $main_reg['pihakExternID'] => $main_reg['pihakExternName'],
                    $main_reg['jenisTr'] => $main_reg['jenisTrName'],
                );
                $rowDetilRslt_label[$main_reg['jenisTr']] = array(
                    "harga" => "harga",
                    "qty" => "qty",
                    "subtotal" => "subtotal",
                );

                //endregion
            }
        }

        $transaksiJenisTopLabel = isset($main_reg['jenisTrName']) ? $main_reg['jenisTrName'] : "";
        //-------------------------------------
        if (($configNotes != NULL) && ($globalVars['trash_4'] == 1)) {
            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {
                    $configNotes = str_replace("{" . $key . "}", $val, $configNotes);
                }
            }
            $notes_final = $configNotes;
        }
        else {
            $notes_final = NULL;
        }
        //-------------------------------------
        $shoppingCartSubDetailFields = isset($this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3] : array();
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            cekHere(":: $jenisMasterID ::");
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$jenisMasterID'");
            $tr->addFilter("jenis='5822pkd'");
            $tr->addFilter("trash_4='0'");
            $tmp0 = $tr->lookupAll()->result();
            $trid_pkd = $tmp0[0]->id;
            $tr->setFilters(array());
            $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
            $tr->addFilter("transaksi_id='$trid_pkd'");
            $tmpReg = $tr->lookupDataRegistries()->result();
            showLast_query("biru");
            if (sizeof($tmpReg) > 0) {
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $eParams) {
                        foreach ($pairRegistry as $param) {
                            if ($eParams->$param == null) {
                                $eParams->$param = blobEncode(array());
                            }
                            $$param = blobDecode($eParams->$param);
                        }
                    }
                }
            }


            $arrSubDetailDataKolom = array();
            foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                //arrPrint($kolomSpec);
                $gerbang = $kolomSpec["gate"];
                $source = $kolomSpec["source"];
                $source_new = $$source;
                //arrPrintWebs($source_new);
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
        else {
            //            cekMerah("::: KOSONG :::");
        }
        //        arrPrintHijau($arrSubDetailDataKolom);
        //-------------------------------------
        //        cekKuning("$notes_final");
        $imagesReference = getTransaksiImageReference($jenisMasterID)[$jenisMasterID];

        // arrPrintHijau($main);

        $data = array(
            //            "mode" => $this->uri->segment(3),
            "mode" => count($imagesReference) > 0 ? "viewResumeImages" : $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
            "items" => $lItems,
            "receiptLink" => $receiptLink,
            "signStr" => $signStr,
            "cabangData" => $arrCabang,

            "urutCounter" => $urutCounters,
            "underMaintenance" => underConstruction(),

            // ===== ======
            "detail_title" => "Pengajuan $transaksiJenisTopLabel",

            "itemLabels" => isset($itemLabelsTop) ? $itemLabelsTop : array(),
            "item2Labels" => isset($item2LabelsTop) ? $item2LabelsTop : array(),
            "item3Labels" => isset($item3LabelsTop) ? $item3LabelsTop : array(),

            "detail_main" => isset($main_reg) ? $main_reg : array(),
            "detail_items" => isset($items_reg) ? $items_reg : array(),
            "detil_item_rslt" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            // "detil_item_rslt_ref" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            "detil_item_rslt_label" => isset($tmpRowRslts_label) ? $tmpRowRslts_label : array(),
            "detil_item_rslt_label2" => isset($rowDetilRslt_label) ? $rowDetilRslt_label : array(),
            "detail2_items" => isset($items2_sum_reg) ? $items2_sum_reg : array(),
            "detail3_items" => isset($items3_sum_reg) ? $items3_sum_reg : array(),

            "detail_sumRows" => $sumRows,
            "detail2_sumRows" => isset($sumRows2) ? $sumRows2 : array(),
            "detail3_sumRows" => isset($sumRows3) ? $sumRows3 : array(),
            "noteEnabled" => $noteEnabled,
            "addRows" => $addFildRowFields,
            //--------------------
            "mainNotApprove" => isset($mainNotApprove) ? $mainNotApprove : array(),
            "itemsNotApprove" => isset($itemsNotApprove) ? $itemsNotApprove : array(),
            "detail_not_approve_title" => "Pengajuan $transaksiJenisTopLabel (tidak diapprove)",
            //--------------------
            "notes_final" => $notes_final,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "referenceImages" => $imagesReference,
            "elementStr" => isset($elementStr) ? $elementStr : "",
            "deliveryDetail" => isset($deliveryDetail) ? $deliveryDetail : array(),
        );
        $this->load->view("transaksi", $data);
    }

    public function viewResume_()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1[0]) > 0) {
            foreach ($tmp1[0] as $tmpKey => $tmpVal) {
                $rkey_field = $tmpKey;
                if ($rkey_field == "nomer") {
                    $rkey_field = "nomer_nolink";
                }
                //                $globalDatas[$rkey] = formatField_he_format($rkey_field, $rval, "", "");
                $globalVars[$tmpKey] = formatField_he_format($rkey_field, $tmpVal, "", "");
            }
        }
        $jenisMasterID = $tmp1[0]->id_master;
        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = $tmp1[0]->step_number < 1 ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items", "main_elements");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;
        $configConnectEdit = isset($configUiJenis["connectToEdit"]) ? $configUiJenis["connectToEdit"] : NULL;
        $fullfil_id = is_null($tmp1[0]->fullfillment_id) ? 0 : $tmp1[0]->fullfillment_id;
        cekHere("[$fullfil_id]");
        // membaca status edit di TOPID
        $tr = new MdlTransaksi();
        $tr->addFilter("id='$transaksiTopID'");
        $tmpTop = $tr->lookupAll()->result();
        $arrTopEdit = array();
        $epEdit = array();
        if (sizeof($tmpTop) > 0) {
            if ((isset($tmpTop[0]->status_edit)) && ($tmpTop[0]->status_edit == 1)) {
                $arrTopEdit[$transaksiTopID] = array(
                    "status_edit" => $tmpTop[0]->status_edit,
                    "edit_dtime" => $tmpTop[0]->edit_dtime,
                    "edit_id" => $tmpTop[0]->edit_id,
                    "edit_name" => $tmpTop[0]->edit_name,
                );
                $epEdit[$tmpTop[0]->id] = $tmpTop[0]->id->master;
            }
        }

        //        cekHere("[$transaksiID]");
        //region swap from registry
        $pairAddRegistry = array("main_elements");
        foreach ($pairAddRegistry as $regkey) {
            $pairRegistry[] = $regkey;
        }
        //        arrPrint($pairRegistry);
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();
        showlast_query("hitam");
        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {

                        if ($eParams->$param == null) {
                            $eParams->$param = blobEncode(array());
                        }
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion
        // arrPrint($main_elements);
        $deliveryDetail = blobDecode($main_elements["deliveryDetails"]["contents"]);
        // arrPrint($main_elements["deliveryDetails"]["contents"]);
        //        arrPrint($deliveryDetail);

        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $tmpSign = $trs->lookupSignaturesByMasterID($masterID)->result();
        //         cekLime($this->db->last_query());
        // arrPrint($tmpSign);
        $arrCounters = blobDecode($tmp1[0]->counters);
        $urutCounters = NULL;
        if (sizeof($arrCounters) > 0) {
            $key2 = $tmp1[0]->jenis . "|" . $tmp1[0]->cabang_id;
            $urutCounters = digit_5($arrCounters["stepCode|placeID"][$key2]);
        }
        if ($urutCounters != NULL) {
            $urutCounters = "-$urutCounters";
        }

        if (isset($this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames'])) {
            $resumeFields = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['selectFields'];
            $resumeTitle = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['title'];
            $resumeName = isset($tmp1[0]->$resumeFields) ? $tmp1[0]->$resumeFields : "";
        }
        else {
            $resumeTitle = "";
            $resumeName = "";
        }

        $arrSignName = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $ii => $row) {
                $signValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "label" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "dtime" => $row->dtime,

                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,

                    "oleh" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                    "prev_id" => isset($row->prev_id) ? $row->prev_id : 0,
                    "link_id" => isset($row->transaksi_id) ? $row->transaksi_id : 0,
                );
                $arrSignName[$ii] = array(
                    "nama" => $row->oleh_nama,
                    "dtime" => $row->dtime,
                );
            }
        }
        $signStr = "";
        //endregion

        //entry points
        $trep = new MdlTransaksi();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
        //      cekMerah($this->db->last_query());
        $epValues = array();
        $rowStepNumber = array();
        $epDetail = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
                //                arrprint($row);
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $id_master = $row->id_master;

                if (empty($row->deskripsi)) {

                    //                        switch ()

                    if (!isset($rowStepNumber["referenceID__" . $row->step_number])) {
                        $rowStepNumber["referenceID__" . $row->step_number] = array(
                            "id" => $row->id,
                            "link_id" => $row->link_id,
                            "step_num" => $row->step_number,

                        );
                    }

                    $epValues['sign_' . $row->id . '_' . $row->step_number] = array(
                        "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => $row->jenis_label,
                        "dtime" => $row->dtime,
                        //                    "dtime" => isset($arrSignName[$ii]['dtime']) ? $arrSignName[$ii]['dtime'] : $row->dtime,
                        "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->oleh_nama,
                        //                    "oleh" => isset($arrSignName[$ii]['nama']) ? $arrSignName[$ii]['nama'] : $row->oleh_nama,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
                        "global_counter" => $cGlobals,
                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
                    );

                }
                else {
                    cekHitam($row->deskripsi);
                    //terpaksa baca registry untuk lihat transksi yang dibatalkan
                    //                    $tr->addFilter("transaksi_id='".$row->link_id."'");
                    //                    cekBiru($jenis);
                    $tmpReg = $tr->lookupDataRegistriesByMasterID($row->link_id)->result();
                    $main = blobDecode($tmpReg[0]->main);
                    //                    switch ($jenis){
                    //                        case "5822spo":
                    //                        case "5822so":
                    //                        case "5822pkd":
                    //                        case "5822spd":
                    //                        $main["referenceID__4"] = $main["referenceID"];
                    //                            break;
                    //                        default:
                    //                            break;
                    //
                    //                    }
                    if ($jenis == "5822spd") {
                        $main["referenceID__4"] = $main["referenceID"];
                    }
                    //arrPrint($main);
                    //matiHere();
                    foreach ($main as $m_key => $mval) {
                        //                        matihere($m_key);
                        if (isset($rowStepNumber[$m_key])) {
                            cekMerah($m_key . "::" . $mval . " ::*" . $rowStepNumber[$m_key]["id"]);
                            switch ($jenis) {
                                case "9112":
                                case "9111":
                                    break;
                                default:
                                    break;
                            }

                            $epValues['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = $row->deskripsi;
                            $epDetail['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = array(
                                "oleh_nama" => $row->oleh_nama,
                                "dtime" => $row->dtime,
                                "nomer" => $row->nomer2,
                            );
                        }
                        else {
                            //                            cekBiru("lolosss");
                        }
                    }

                    //                    arrPrint($main);
                    //                    matiHere(__LINE__);
                    //                    cekHitam($jenis);
                }

                //                if(empty($row->deskripsi) || !isset($epEdit[$row->id])){
                ////                    cekBiru($jenis);
                //
                //                }
                //                else{
                //                    cekHitam($jenis);
                //                }
            }
        }

        //lihat entrypoint jika ada fullfilment
        //        if($fullfil_id >0){
        //            $trep = new MdlTransaksi();
        //            $tmpEPFullfilment = $trep->lookupEntryPoints($fullfil_id)->result();
        //            cekHitam($this->db->last_query());
        //            arrPrint($tmpEPFullfilment);
        //        }
        //        arrPrint($fullfil_id);

        //lanjut jika $fullfil_id > 0 berarti ada fullfilment

        //        arrPrint($rowStepNumber);
        //        arrPrint($epValues1);
        //        matiHere(__LINE__);
        //        arrprintWebs($epDetail);

        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;

        //        arrprint($signArray);
        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel table-responsive'>";
            $signStr .= "<h4 class='text-blue'><span class='fa fa-sign-in'></span> entry points </h4>";

            $signStr .= "<h5 class='text-blue'><span class='fa fa-tasks'></span> $resumeTitle  $resumeName</h5>";
            $signStr .= "<table class='table table-condensed'>";
            $signStr .= "<tr bgcolor='#f0f0f0'>";
            $signStr .= "<td class='text-muted'>date</td>";
            $signStr .= "<td class='text-muted'>title</td>";
            $signStr .= "<td class='text-muted'>branch</td>";
            $signStr .= "<td class='text-muted'>transaction number</td>";
            $signStr .= "<td class='text-muted'>person</td>";
            $signStr .= "<td class='text-muted'>status</td>";
            $signStr .= "<td class='text-muted'>tool</td>";
            foreach ($signArray as $k => $sSpec) {
                //                arrPrintPink($sSpec);
                $jenis = $sSpec['jenis'];
                $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                $nomerOrig = $sSpec['nomer_orig'];
                $nomerOrigEx = explode("_", $nomerOrig);
                $nomerOrigNota = $nomerOrigEx[0];
                $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;

                $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";


                $edit_description = "";
                if (isset($arrTopEdit[$sSpec['link_id']])) {
                    $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                    $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                    $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                }

                $rejection_label = "";
                $reject_description = "";
                if (isset($epDetail[$k])) {
                    $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                    $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                    $reject_description = "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                }


                $signStr .= "<tr>";
                $signStr .= "<td>" . formatField_he_format("date_time", $sSpec['dtime']);
                $signStr .= "</td>";
                $signStr .= "<td>" . ($sSpec['label']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                $signStr .= "<td>" . (formatField_he_format("nomer_nolink", $nomerViewFinal)) . $signDeskripsi . "<br>$edit_description";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $oleh_nama . "";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $status . "<br>" . $reject_description . "";
                $signStr .= "</td>";

                $signStr .= "<td>";
                $signStr .= formatField_he_format("print_label", $nomerOrigNota, $this->jenisTr, MODUL_PATH);
                $signStr .= "</td>";

                $signStr .= "</tr>";
            }
            $signStr .= "</table class='table table-condensed'>";

            $signStr .= "</div class='panel'>";
        }

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $allowedView = $this->config->item('userGroup_jurnal') != null ? $this->config->item('userGroup_jurnal') : array();
        $rekeningAlias = $this->config->item('accountAlias') != null ? $this->config->item('accountAlias') : array();

        $lItems = array();
        $headers = array();
        $arrCabang = array();
        if (array_intersect($allowedView, $mems)) {
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Mdls/MdlCabang");

            $cb = New MdlCabang();
            $tmpCb = $cb->lookupAll()->result();
            $arrCabang = array();
            if (sizeof($tmpCb) > 0) {
                foreach ($tmpCb as $spec) {
                    $arrCabang[$spec->id] = $spec->nama;
                }
            }

            $j = new ComJurnal();
            $j->addFilter("transaksi_no='$no'");
            $j->addFilter("status='1'");
            $j->addFilter("trash='0'");
            if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

            }
            else {
                // dimatikan dulu untuk checking jurnal lengkapnya...........
                //                $j->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            //            $j->setSortBy(array("kolom" => "urut", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            if (sizeof($tmp) > 0) {
                /* ---------------------------------------
                 * array link untuk mengarahkan ke halaman mutasi yg dikendaki
                 * -----------------------------------*/

                //region Description
                $mutasiLinks = array();
                foreach ($accountChildsLinks as $rekNama => $rekLink) {
                    $mutasiLinks[$rekNama] = $rekLink . "/" . $main['pihakID'] . "?o=" . $main['cabangID'] . "&trID=$transaksiID";
                }
                //endregion

                foreach ($tmp as $row) {
                    $d_start = backCustomDate("30", $row->dtime);
                    $d_stop = $row->dtime;
                    if ($row->j_jenis == "items") {
                        $blob_ext = blobEncode($row->rekening);
                        //                        Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/1076?o=-1&ext2_id=&main_ext2_id=
                        $defLink = "Ledger/viewMoveDetails/RekeningPembantuProduk/" . $row->rekening_main . "/" . $row->extern_id . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop&blob_ext=$blob_ext";
                    }
                    else {
                        $defLink = "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                        //                    $defLink = "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                    }

                    //                    $rekening_alias = isset($rekeningAlias[$row->rekening]) ? $rekeningAlias[$row->rekening] : $row->rekening;
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        //$strLink = key_exists($row->rekening, $mutasiLinks) ? $mutasiLinks[$row->rekening] : $defLink;
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }
                //                arrPrint($lItems);
                ksort($lItems);
            }


        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $mobMode = $isMob == true ? "1" : "0";

        $printSettings = isset($this->configLayout[$jenisMasterTrans]['allowPrint']['1']) ? $this->configLayout[$jenisMasterTrans]['allowPrint']['1'] : array();
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Transaksi/viewReceipt/";

        $valasType = (isset($main['currencyDetails__label'])) ? "&type=" . blobEncode($main['currencyDetails__label']) : "";
        $valasFKali = "&f=" . blobEncode(1);

        if (sizeof($tmpSign) > 0) {
            if (isset($printSettings['size'])) {
                switch ($printSettings['size']) {
                    case "normal":
                        $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=1$valasType$valasFKali');";
                        break;
                    case "small":
                        $receiptLink = "top.popSmall('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=2');";
                        break;
                    default:
                        //                                cekkuning("size: UNKNOWN");
                        break;
                }
            }
            else {
                $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=3$valasType$valasFKali');";
            }
        }
        else {
            $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?st=4');";
        }


        // ============== TAMBAHAN ============== ============== ==============
        $itemLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields'][1] : array();
        $itemLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1] : array();
        $itemLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1] : array();
        $itemNumLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1] : array();
        $itemNumLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1] : array();
        $itemNumLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1] : array();
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1];
        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;


        $arrCons = array();
        $headerScheme = array();

        if ($showScheme) {

            $awal_pinjaman = isset($main['awal_pinjaman']) ? $main['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($main['jatuh_tempo']) ? $main['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($main['harga']) ? $main['harga'] : 0;
            $rate_bunga = isset($main['persen_bunga']) ? $main['persen_bunga'] : 0;

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

        $itemLabelsTop = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $item2LabelsTop = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $item3LabelsTop = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemLabelBlacklist = array(
            "stok_center",
            "stok",
        );

        foreach ($itemLabelsTop as $key => $val) {
            if (in_array($key, $itemLabelBlacklist)) {
                unset($itemLabelsTop[$key]);
            }
        }
        if (isset($this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount']) && $this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount'][1] == true) {
            unset($itemLabelsTop['subtotal']);
            unset($item2LabelsTop['subtotal']);
            unset($item3LabelsTop['subtotal']);
        }
        $blacklistView = $this->config->item('userGroup_blacklist') != null ? $this->config->item('userGroup_blacklist') : array();
        $addFildRowFields = array();


        $allowedViewTop = false;
        foreach ($mems as $memsVal) {
            if (!in_array($memsVal, $blacklistView)) {
                $allowedViewTop = true;
            }
        }

        if ($allowedViewTop == true) {


            $m = new MdlTransaksi();
            $m->setFilters(array());
            $tmpReg = $m->lookupDataRegistriesByMasterID($transaksiTopID)->result();
            $regfields = $m->getFields()["dataRegistry"];
            $addRow = isset($this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes']) ? $this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes'] : array();
            $itemNotApprove = isset($this->configUi[$jenisMasterTrans]['closedRequest'][1]) ? $this->configUi[$jenisMasterTrans]['closedRequest'][1] : true;
            $detailCore = isset($this->configCore[$jenisMasterTrans]['valueGates']['detail']) ? $this->configCore[$jenisMasterTrans]['valueGates']['detail'] : array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regfields as $params) {
                        // cekHitam($params);
                        if (isset($row->$params) && ($params != "transaksi_id")) {

                            $var = $params . "_reg";
                            $$var = unserialize(base64_decode($row->$params));
                        }

                    }
                    // $$var = unserialize(base64_decode($row->values));
                }
                if (sizeof($addRow) > 0) {
                    foreach ($addRow as $gateKey => $mpFields) {

                        if (isset($mpFields['hideRow']) && $mpFields['hideRow'] == true) {

                        }
                        else {
                            $addFildRowFields[$gateKey] = $mpFields['label'];
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    //                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID)->result();
                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID);
                    $notApprove = array();
                    foreach ($dTmp as $spec) {
                        if ((isset($spec->valid_qty_no_approve)) && ($spec->valid_qty_no_approve > 0)) {
                            $notApprove[$spec->produk_id] = $spec->valid_qty_no_approve;
                        }
                    }
                    $mainNotApprove = array();
                    $itemsNotApprove = array();
                    if (sizeof($notApprove) > 0) {
                        $itemsNotApprove = $items_reg;
                        foreach ($notApprove as $pid => $pqty_not_approve) {
                            $itemsNotApprove[$pid]['qty'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['jml'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['subtotal'] = $pqty_not_approve * $itemsNotApprove[$pid]['harga'];
                            if (sizeof($detailCore) > 0) {
                                foreach ($detailCore as $key_c => $val_c) {
                                    $itemsNotApprove[$pid]['sub_' . $key_c] = $pqty_not_approve * $itemsNotApprove[$pid][$key_c];
                                    if (!isset($mainNotApprove[$key_c])) {
                                        $mainNotApprove[$key_c] = 0;
                                    }
                                    $mainNotApprove[$key_c] += ($pqty_not_approve * $itemsNotApprove[$pid][$key_c]);
                                }
                            }
                            if (!isset($mainNotApprove['harga'])) {
                                $mainNotApprove['harga'] = 0;
                            }
                            $mainNotApprove['harga'] += ($pqty_not_approve * $itemsNotApprove[$pid]['harga']);
                        }
                    }
                }
            }

            //tambahan jika ada result item
            // arrPrint($main_reg);
            if (sizeof($rsltItems_reg) > 0) {
                //region reference detil
                $m->setFilters(array());
                $tmpRegRef = $m->lookupDataRegistriesByMasterID($main_reg['referenceID']);
                foreach ($tmpRegRef as $refRow) {
                    foreach ($regfields as $params) {
                        if (isset($refRow->$params)) {
                            $varR = $params . "_ref";
                            $$varR = unserialize(base64_decode($refRow->$params));
                        }
                    }
                }

                $tmpRowRslts_ref = array();
                $totalNilaiRef = 0;
                if (sizeof($rsltItems_ref) > 0) {
                    foreach ($rsltItems_ref as $rowRefID => $rowref_data) {
                        // $hpp = number_format($rowref_data['hpp'],0);
                        $hpp = $rowref_data['hpp'];
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] = 0;

                        }
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] = 0;
                        }

                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] += $rowref_data['qty'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] += $rowref_data['sub_hpp'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['harga'] = $rowref_data['hpp'];
                        $totalNilaiRef += $rowref_data['sub_hpp'];
                    }
                    $rowDetilRslt_label[$main_reg['pihakExternID']] = array(
                        "harga" => "harga",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                    );
                }
                //endregion

                //region current detil
                $tmpRowRslts = array();
                $tmpRowRsltsnilai = 0;
                foreach ($rsltItems_reg as $rowFifo => $fifoData) {
                    // $hpp_0 = number_format($fifoData['hpp'],0);
                    $hpp_0 = $fifoData['hpp'];
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] = 0;
                    }
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] = 0;
                    }
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] += $fifoData['qty'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] += $fifoData['sub_hpp'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['harga'] = $fifoData['hpp'];
                    $tmpRowRsltsnilai += $fifoData['sub_hpp'];
                }
                $tmpRowRslts_label = array(
                    "nama" => "produk",
                    "qty" => "qty",
                    $main_reg['pihakExternID'] => $main_reg['pihakExternName'],
                    $main_reg['jenisTr'] => $main_reg['jenisTrName'],
                );
                $rowDetilRslt_label[$main_reg['jenisTr']] = array(
                    "harga" => "harga",
                    "qty" => "qty",
                    "subtotal" => "subtotal",
                );

                //endregion
            }
        }

        $transaksiJenisTopLabel = isset($main_reg['jenisTrName']) ? $main_reg['jenisTrName'] : "";
        //-------------------------------------
        if (($configNotes != NULL) && ($globalVars['trash_4'] == 1)) {
            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {
                    $configNotes = str_replace("{" . $key . "}", $val, $configNotes);
                }
            }
            $notes_final = $configNotes;
        }
        else {
            $notes_final = NULL;
        }
        //-------------------------------------
        $shoppingCartSubDetailFields = isset($this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3] : array();
        $shopingCartPairFieldResume = isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume'] : array();
        //        arrprint($shopingCartPairFieldResume);
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            cekHere(":: $jenisMasterID ::");
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$jenisMasterID'");
            $tr->addFilter("jenis='5822pkd'");
            $tr->addFilter("trash_4='0'");
            $tmp0 = $tr->lookupAll()->result();
            $trid_pkd = $tmp0[0]->id;
            $tr->setFilters(array());
            $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
            $tr->addFilter("transaksi_id='$trid_pkd'");
            $tmpReg = $tr->lookupDataRegistries()->result();
            //            showLast_query("biru");
            if (sizeof($tmpReg) > 0) {
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $eParams) {
                        foreach ($pairRegistry as $param) {
                            if ($eParams->$param == null) {
                                $eParams->$param = blobEncode(array());
                            }
                            $$param = blobDecode($eParams->$param);
                        }
                    }
                }
            }


            $arrSubDetailDataKolom = array();
            foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                //arrPrint($kolomSpec);
                $gerbang = $kolomSpec["gate"];
                $source = $kolomSpec["source"];
                $source_new = $$source;
                //arrPrintWebs($source_new);
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
            $shipment = array();
            if (count($shopingCartPairFieldResume) > 0) {
                foreach ($items as $pid => $data) {
                    foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                        $shipment[$pid][$field_shipment] = $data[$field_shipment];
                    }
                }
                //
            }
        }
        else {
            //            cekMerah("::: KOSONG :::");
        }
        //        arrPrintHijau($shipment);
        //-------------------------------------
        //        cekKuning("$notes_final");
        $imagesReference = getTransaksiImageReference($jenisMasterID)[$jenisMasterID];

        //         arrPrintHijau($lItems);

        $data = array(
            //            "mode" => $this->uri->segment(3),
            "mode" => count($imagesReference) > 0 ? "viewResumeImages" : $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
            "items" => $lItems,
            "receiptLink" => $receiptLink,
            "signStr" => $signStr,
            "cabangData" => $arrCabang,

            "urutCounter" => $urutCounters,
            "underMaintenance" => underConstruction(),

            // ===== ======
            "detail_title" => "Pengajuan $transaksiJenisTopLabel",

            "itemLabels" => isset($itemLabelsTop) ? $itemLabelsTop : array(),
            "item2Labels" => isset($item2LabelsTop) ? $item2LabelsTop : array(),
            "item3Labels" => isset($item3LabelsTop) ? $item3LabelsTop : array(),

            "detail_main" => isset($main_reg) ? $main_reg : array(),
            "detail_items" => isset($items_reg) ? $items_reg : array(),
            "detil_item_rslt" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            // "detil_item_rslt_ref" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            "detil_item_rslt_label" => isset($tmpRowRslts_label) ? $tmpRowRslts_label : array(),
            "detil_item_rslt_label2" => isset($rowDetilRslt_label) ? $rowDetilRslt_label : array(),
            "detail2_items" => isset($items2_sum_reg) ? $items2_sum_reg : array(),
            "detail3_items" => isset($items3_sum_reg) ? $items3_sum_reg : array(),

            "detail_sumRows" => $sumRows,
            "detail2_sumRows" => isset($sumRows2) ? $sumRows2 : array(),
            "detail3_sumRows" => isset($sumRows3) ? $sumRows3 : array(),
            "noteEnabled" => $noteEnabled,
            "addRows" => $addFildRowFields,
            //--------------------
            "mainNotApprove" => isset($mainNotApprove) ? $mainNotApprove : array(),
            "itemsNotApprove" => isset($itemsNotApprove) ? $itemsNotApprove : array(),
            "detail_not_approve_title" => "Pengajuan $transaksiJenisTopLabel (tidak diapprove)",
            //--------------------
            "notes_final" => $notes_final,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "referenceImages" => $imagesReference,
            "elementStr" => isset($elementStr) ? $elementStr : "",
            "deliveryDetail" => isset($deliveryDetail) ? $deliveryDetail : array(),
        );
        $this->load->view("transaksi", $data);
    }

    public function viewResume_03mei2026()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1[0]) > 0) {
            foreach ($tmp1[0] as $tmpKey => $tmpVal) {
                $rkey_field = $tmpKey;
                if ($rkey_field == "nomer") {
                    $rkey_field = "nomer_nolink";
                }
                //                $globalDatas[$rkey] = formatField_he_format($rkey_field, $rval, "", "");
                $globalVars[$tmpKey] = formatField_he_format($rkey_field, $tmpVal, "", "");
            }
        }
        $jenisMasterID = $tmp1[0]->id_master;
        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = $tmp1[0]->step_number < 1 ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items", "main_elements", "transaksi_id");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;
        $configConnectEdit = isset($configUiJenis["connectToEdit"]) ? $configUiJenis["connectToEdit"] : NULL;
        $fullfil_id = 0;
        $jenisTr_110r = array("110r_1");
        $jenisTr_spd = "5822spd";
        $jenisTr_spd_link = array("5822spd_4");

        // membaca status edit di TOPID
        $tr = new MdlTransaksi();
        $tr->addFilter("id='$transaksiTopID'");
        $tmpTop = $tr->lookupAll()->result();
        $arrTopEdit = array();
        $epEdit = array();
        if (sizeof($tmpTop) > 0) {
            if ((isset($tmpTop[0]->status_edit)) && ($tmpTop[0]->status_edit == 1)) {
                $arrTopEdit[$transaksiTopID] = array(
                    "status_edit" => $tmpTop[0]->status_edit,
                    "edit_dtime" => $tmpTop[0]->edit_dtime,
                    "edit_id" => $tmpTop[0]->edit_id,
                    "edit_name" => $tmpTop[0]->edit_name,
                );
                $epEdit[$tmpTop[0]->id] = $tmpTop[0]->id->master;
            }
            $fullfil_id = is_null($tmpTop[0]->fullfillment_id) ? 0 : $tmpTop[0]->fullfillment_id;
        }

        //region swap from registry
        $pairAddRegistry = array("main_elements");
        foreach ($pairAddRegistry as $regkey) {
            $pairRegistry[] = $regkey;
        }
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();
        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {

                        if ($eParams->$param == null) {
                            $eParams->$param = blobEncode(array());
                        }
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion

        $deliveryDetail = blobDecode($main_elements["deliveryDetails"]["contents"]);

        //region signatures
        //ambil data fullfill dulu bro biar bisa digabung sign
        if ($fullfil_id > 0) {
            $trf = new MdlTransaksi();
            $trf->setFilters(array());
            $trf->addFilter("id='$fullfil_id'");
            $tmp_master_fulfill = $trf->lookupMainTransaksi()->result();
            $masterID_fullfil = $tmp_master_fulfill[0]->id_master;
        }
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if ($fullfil_id > 0) {
                $trs->addFilter("transaksi_id in ($masterID,$masterID_fullfil)");
            }
            else {
                $trs->addFilter("transaksi_id='$masterID'");
            }
        }
        else {
            $trs->addFilter("transaksi_id='$masterID'");
        }


        //        $tmpSign = $trs->lookupSignatureResumeByMasterID()->result();//belum tak matiin
        $tmpSign = $trs->lookupSignaturesByMasterID($masterID)->result();
        //                 cekLime($this->db->last_query());
        //         arrPrint($tmpSign);
        $arrCounters = blobDecode($tmp1[0]->counters);
        $urutCounters = NULL;
        if (sizeof($arrCounters) > 0) {
            $key2 = $tmp1[0]->jenis . "|" . $tmp1[0]->cabang_id;
            $urutCounters = digit_5($arrCounters["stepCode|placeID"][$key2]);
        }
        if ($urutCounters != NULL) {
            $urutCounters = "-$urutCounters";
        }

        if (isset($this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames'])) {
            $resumeFields = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['selectFields'];
            $resumeTitle = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['title'];
            $resumeName = isset($tmp1[0]->$resumeFields) ? $tmp1[0]->$resumeFields : "";
        }
        else {
            $resumeTitle = "";
            $resumeName = "";
        }

        $arrSignName = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $ii => $row) {
                $signValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "label" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    //                    "title" => $row->step_name,
                    //                    "label" => $row->step_name,
                    "dtime" => $row->dtime,

                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,

                    "oleh" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                    "prev_id" => isset($row->prev_id) ? $row->prev_id : 0,
                    "link_id" => isset($row->transaksi_id) ? $row->transaksi_id : 0,
                );
                $arrSignName[$ii] = array(
                    "nama" => $row->oleh_nama,
                    "dtime" => $row->dtime,
                );
            }
        }


        $signStr = "";
        //endregion

        //-------------counter global------------
        $counterGlobalOverride = null;
        if (!empty($this->configUiJenis['counter_global'])) {
            $key = $this->configUiJenis['counter_global'];
            $counterGlobalOverride = isset($tmp1[0]->$key) ? $tmp1[0]->$key : null;
        }

        //entry points
        $trep = new MdlTransaksi();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
        showLast_query("biru");
        $epValues = array();
        $rowStepNumber = array();
        $epDetail = array();
        $terimaAr = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;

                $counterGlobalOld = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterGlobal = ($counterGlobalOverride !== null) ? $counterGlobalOverride : $counterGlobalOld;

                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $id_master = $row->id_master;
                if (empty($row->deskripsi)) {
                    if (!isset($rowStepNumber["referenceID__" . $row->step_number])) {
                        $rowStepNumber["referenceID__" . $row->step_number] = array(
                            "id" => $row->id,
                            "link_id" => $row->link_id,
                            "step_num" => $row->step_number,

                        );
                    }
                    $epValues['sign_' . $row->id . '_' . $row->step_number] = array(
                        "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => $row->jenis_label,
                        "dtime" => $row->dtime,
                        //                    "dtime" => isset($arrSignName[$ii]['dtime']) ? $arrSignName[$ii]['dtime'] : $row->dtime,
                        "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->oleh_nama,
                        //                    "oleh" => isset($arrSignName[$ii]['nama']) ? $arrSignName[$ii]['nama'] : $row->oleh_nama,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
                        "global_counter" => $cGlobals,
                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
//                        "date_faktur" => isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL,
//                        "nomer_faktur" => isset($row->efaktur) ? $row->efaktur : NULL,
//                        "gunggungan_mode" => isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0,
//
                    );
                    if ($row->efaktur != 0) {
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["date_faktur"] = isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["nomer_faktur"] = isset($row->efaktur) ? $row->efaktur : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["gunggungan_mode"] = isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0;
                    }
                }
                else {
                    $tmpReg = $tr->lookupDataRegistriesByMasterID($row->link_id)->result();
                    $main = blobDecode($tmpReg[0]->main);
//                    arrPrint($main);
                    if ($jenis == "5822spd") {
                        $main["referenceID__4"] = $main["referenceID"];
                    }
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        foreach ($main as $m_key => $mval) {
                            if (isset($rowStepNumber[$m_key])) {
                                cekMerah($m_key . "::" . $mval . " ::*" . $rowStepNumber[$m_key]["id"]);
//                                switch ($jenis) {
//                                    case "9112":
//                                    case "9111":
//                                        break;
//                                    default:
//                                        break;
//                                }
//
                                $epValues['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = $row->deskripsi;
                                $epDetail['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                );
                            }
                            else {
                                //                            cekBiru("lolosss");
                            }
                        }
                    }
                    else {

                        switch ($main["jenisTr"]) {
                            case "9912":
                            case "9911":
//                                cekUngu($main["referenceStepNumber"]);
                                $selectedTrid = $main["transaksi"] + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["referenceStepNumber"]]["status"] = array(
                                    "oleh_nama" => $main["olehName"],
                                    "dtime" => $main["dtime"],
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                break;
                            default:
                                $selectedTrid = isset($main["transaksi"]) ? $main["transaksi"] : $main["currentID"];
                                $selectedTrid = $selectedTrid + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["stepNumber"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                break;
                        }
//
                    }
                }
                if (in_array($row->jenis, $jenisTr_spd_link)) {
                    $terimaAr[$row->link_id] = $row->link_id;
                }
                if (in_array($row->jenis, $jenisTr_110r)) {
                    $inputFaktur[$row->link_id] = $row->link_id;
                }

            }
        }

        //------LINK KE AR
        if (sizeof($terimaAr) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis='749'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $terimaAr) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
            showLast_query("kuning");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
                    "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                );
            }
        }
        //------
        if (sizeof($inputFaktur) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis_master='110'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $inputFaktur) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
            showLast_query("kuning");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
                    "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                    "date_faktur" => $row->efaktur_dtime,
                    "nomer_faktur" => $row->efaktur,
                    "gunggungan_mode" => $row->gunggungan_mode,
                );
            }
        }

        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;
        $steps = $configUiJenis;
        foreach ($steps as $step => $keis) {
            $autoNextStep = isset($keis['autoNextStep']) ? $keis['autoNextStep'] : 0;
//            cekBiru("$step $autoNextStep");
        }

        $signArrayKeys = array_keys($signArray);
        foreach ($signArrayKeys as $keyy) {
            $keyy_ex = explode("_", $keyy);
            $signArrayKeysTrid[$keyy_ex[1]] = $keyy;
        }
        ksort($signArrayKeysTrid);
//        arrPrintPink($signArrayKeysTrid);
//        arrPrintCyan($signArray);
//        arrPrintHitam($epDetail);
        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel table-responsive'>";
            $signStr .= "<h4 class='text-blue'><span class='fa fa-sign-in'></span> entry points </h4>";

            $signStr .= "<h5 class='text-blue'><span class='fa fa-tasks'></span> $resumeTitle  $resumeName</h5>";
            $signStr .= "<table class='table table-condensed table-striped table-hover-color-red dataTable'>";
            $signStr .= "<tr bgcolor='#f0f0f0'>";
            $signStr .= "<td class='text-muted'>date</td>";
            $signStr .= "<td class='text-muted'>title</td>";
            $signStr .= "<td class='text-muted'>branch</td>";
            $signStr .= "<td class='text-muted'>transaction number</td>";
            $signStr .= "<td class='text-muted'>person</td>";
            $signStr .= "<td class='text-muted'>status</td>";
            $signStr .= "<td class='text-muted'>tool</td>";
            foreach ($signArrayKeysTrid as $k_ii => $v_ii) {
                $k = $v_ii;
                $sSpec = $signArray[$v_ii];
//            foreach ($signArray as $k => $sSpec) {

                $jenis = $sSpec['jenis'];
                $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                $nomerOrig = $sSpec['nomer_orig'];
                $nomerOrigEx = explode("_", $nomerOrig);
                $nomerOrigNota = $nomerOrigEx[0];
                $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;

                $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";


                $edit_description = "";
                if (isset($arrTopEdit[$sSpec['link_id']])) {
                    $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                    $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                    $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                }

                $rejection_label = "";
                $reject_description = "";
                if (isset($epDetail[$k])) {
                    $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                    $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                    $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                    $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                }
                //-----
                $date_faktur = "";
                $nomer_faktur = "";
                $gunggungan_mode = 0;
                $gunggungan_mode_bgcolor = "";
                if (isset($sSpec["date_faktur"])) {
                    $date_faktur = $sSpec["date_faktur"];
                }
                if (isset($sSpec["nomer_faktur"])) {
                    $nomer_faktur = $sSpec["nomer_faktur"];
                }
                if (isset($sSpec["gunggungan_mode"])) {
                    $gunggungan_mode = $sSpec["gunggungan_mode"];
                    $gunggungan_mode_bgcolor = "yellow";
                }

                $signStr .= "<tr style='background-color:$gunggungan_mode_bgcolor;'>";
                $signStr .= "<td>" . formatField_he_format("date_time", $sSpec['dtime']);
                $signStr .= "</td>";
                $signStr .= "<td>" . ($sSpec['label']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                $signStr .= "<td>" . (formatField_he_format("nomer_nolink", $nomerViewFinal)) . $signDeskripsi . "";
                if ($edit_description != "") {
                    $signStr .= "<br>$edit_description";
                }
                if ($date_faktur != "") {
                    $signStr .= "<br>tanggal faktur: $date_faktur";
                }
                if ($nomer_faktur != "") {
                    $signStr .= "<br>Nomer faktur: $nomer_faktur";
                }
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $oleh_nama . "";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $status . "<br>" . $reject_description . "";
                $signStr .= "</td>";

                $signStr .= "<td title='" . $sSpec['label'] . "'>";
                $signStr .= formatField_he_format("print_label", $nomerOrigNota, $this->jenisTr, MODUL_PATH);
                $signStr .= "</td>";

                $signStr .= "</tr>";
            }
            $signStr .= "</table class='table table-condensed'>";

            $signStr .= "</div class='panel'>";
        }


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $allowedView = $this->config->item('userGroup_jurnal') != null ? $this->config->item('userGroup_jurnal') : array();
        $rekeningAlias = $this->config->item('accountAlias') != null ? $this->config->item('accountAlias') : array();

        $lItems = array();
        $headers = array();
        $arrCabang = array();
        if (array_intersect($allowedView, $mems)) {
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Mdls/MdlCabang");

            $cb = New MdlCabang();
            $tmpCb = $cb->lookupAll()->result();
            $arrCabang = array();
            if (sizeof($tmpCb) > 0) {
                foreach ($tmpCb as $spec) {
                    $arrCabang[$spec->id] = $spec->nama;
                }
            }

            $j = new ComJurnal();
            $j->addFilter("transaksi_no='$no'");
            $j->addFilter("status='1'");
            $j->addFilter("trash='0'");
            if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

            }
            else {
                // dimatikan dulu untuk checking jurnal lengkapnya...........
                //                $j->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            //            $j->setSortBy(array("kolom" => "urut", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            if (sizeof($tmp) > 0) {
                /* ---------------------------------------
                 * array link untuk mengarahkan ke halaman mutasi yg dikendaki
                 * -----------------------------------*/

                //region Description
                $mutasiLinks = array();
                foreach ($accountChildsLinks as $rekNama => $rekLink) {
                    $mutasiLinks[$rekNama] = $rekLink . "/" . $main['pihakID'] . "?o=" . $main['cabangID'] . "&trID=$transaksiID";
                }
                //endregion

                foreach ($tmp as $row) {
                    $d_start = backCustomDate("30", $row->dtime);
                    $d_stop = $row->dtime;
                    if ($row->j_jenis == "items") {
                        $blob_ext = blobEncode($row->rekening);
                        //                        Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/1076?o=-1&ext2_id=&main_ext2_id=
                        $defLink = "Ledger/viewMoveDetails/RekeningPembantuProduk/" . $row->rekening_main . "/" . $row->extern_id . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop&blob_ext=$blob_ext";
                    }
                    else {
                        $defLink = "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                        //                    $defLink = "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                    }

                    //                    $rekening_alias = isset($rekeningAlias[$row->rekening]) ? $rekeningAlias[$row->rekening] : $row->rekening;
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        //$strLink = key_exists($row->rekening, $mutasiLinks) ? $mutasiLinks[$row->rekening] : $defLink;
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }
                //                arrPrint($lItems);
                ksort($lItems);
            }


        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $mobMode = $isMob == true ? "1" : "0";

        $printSettings = isset($this->configLayout[$jenisMasterTrans]['allowPrint']['1']) ? $this->configLayout[$jenisMasterTrans]['allowPrint']['1'] : array();
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Transaksi/viewReceipt/";

        $valasType = (isset($main['currencyDetails__label'])) ? "&type=" . blobEncode($main['currencyDetails__label']) : "";
        $valasFKali = "&f=" . blobEncode(1);

        if (sizeof($tmpSign) > 0) {
            if (isset($printSettings['size'])) {
                switch ($printSettings['size']) {
                    case "normal":
                        $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=1$valasType$valasFKali');";
                        break;
                    case "small":
                        $receiptLink = "top.popSmall('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=2');";
                        break;
                    default:
                        //                                cekkuning("size: UNKNOWN");
                        break;
                }
            }
            else {
                $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=3$valasType$valasFKali');";
            }
        }
        else {
            $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?st=4');";
        }


        // ============== TAMBAHAN ============== ============== ==============
        $itemLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields'][1] : array();
        $itemLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1] : array();
        $itemLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1] : array();
        $itemNumLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1] : array();
        $itemNumLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1] : array();
        $itemNumLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1] : array();
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1];
        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;


        $arrCons = array();
        $headerScheme = array();

        if ($showScheme) {

            $awal_pinjaman = isset($main['awal_pinjaman']) ? $main['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($main['jatuh_tempo']) ? $main['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($main['harga']) ? $main['harga'] : 0;
            $rate_bunga = isset($main['persen_bunga']) ? $main['persen_bunga'] : 0;

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

        $itemLabelsTop = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $item2LabelsTop = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $item3LabelsTop = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemLabelBlacklist = array(
            "stok_center",
            "stok",
        );

        foreach ($itemLabelsTop as $key => $val) {
            if (in_array($key, $itemLabelBlacklist)) {
                unset($itemLabelsTop[$key]);
            }
        }
        if (isset($this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount']) && $this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount'][1] == true) {
            unset($itemLabelsTop['subtotal']);
            unset($item2LabelsTop['subtotal']);
            unset($item3LabelsTop['subtotal']);
        }
        $blacklistView = $this->config->item('userGroup_blacklist') != null ? $this->config->item('userGroup_blacklist') : array();
        $addFildRowFields = array();


        $allowedViewTop = false;
        foreach ($mems as $memsVal) {
            if (!in_array($memsVal, $blacklistView)) {
                $allowedViewTop = true;
            }
        }

        if ($allowedViewTop == true) {

            //untuk melihat data order/spo nya
            $m = new MdlTransaksi();
            $m->setFilters(array());
            $tmpReg = $m->lookupDataRegistriesByMasterID($transaksiTopID)->result();
            $regfields = $m->getFields()["dataRegistry"];
            $addRow = isset($this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes']) ? $this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes'] : array();
            $itemNotApprove = isset($this->configUi[$jenisMasterTrans]['closedRequest'][1]) ? $this->configUi[$jenisMasterTrans]['closedRequest'][1] : true;
            $detailCore = isset($this->configCore[$jenisMasterTrans]['valueGates']['detail']) ? $this->configCore[$jenisMasterTrans]['valueGates']['detail'] : array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regfields as $params) {
                        // cekHitam($params);
                        if (isset($row->$params) && ($params != "transaksi_id")) {

                            $var = $params . "_reg";
                            $$var = unserialize(base64_decode($row->$params));
                        }

                    }
                    // $$var = unserialize(base64_decode($row->values));
                }
                if (sizeof($addRow) > 0) {
                    foreach ($addRow as $gateKey => $mpFields) {

                        if (isset($mpFields['hideRow']) && $mpFields['hideRow'] == true) {

                        }
                        else {
                            $addFildRowFields[$gateKey] = $mpFields['label'];
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    //                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID)->result();
                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID);

                    $notApprove = array();
                    foreach ($dTmp as $spec) {
                        if ((isset($spec->valid_qty_no_approve)) && ($spec->valid_qty_no_approve > 0)) {
                            $notApprove[$spec->produk_id] = $spec->valid_qty_no_approve;
                        }
                    }
                    $mainNotApprove = array();
                    $itemsNotApprove = array();
                    if (sizeof($notApprove) > 0) {
                        $itemsNotApprove = $items_reg;
                        foreach ($notApprove as $pid => $pqty_not_approve) {
                            $itemsNotApprove[$pid]['qty'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['jml'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['subtotal'] = $pqty_not_approve * $itemsNotApprove[$pid]['harga'];
                            if (sizeof($detailCore) > 0) {
                                foreach ($detailCore as $key_c => $val_c) {
                                    $itemsNotApprove[$pid]['sub_' . $key_c] = $pqty_not_approve * $itemsNotApprove[$pid][$key_c];
                                    if (!isset($mainNotApprove[$key_c])) {
                                        $mainNotApprove[$key_c] = 0;
                                    }
                                    $mainNotApprove[$key_c] += ($pqty_not_approve * $itemsNotApprove[$pid][$key_c]);
                                }
                            }
                            if (!isset($mainNotApprove['harga'])) {
                                $mainNotApprove['harga'] = 0;
                            }
                            $mainNotApprove['harga'] += ($pqty_not_approve * $itemsNotApprove[$pid]['harga']);
                        }
                    }
                }
            }

            //tambahan jika ada result item
            // arrPrint($main_reg);
            if (sizeof($rsltItems_reg) > 0) {
                //region reference detil
                $m->setFilters(array());
                $tmpRegRef = $m->lookupDataRegistriesByMasterID($main_reg['referenceID']);
                foreach ($tmpRegRef as $refRow) {
                    foreach ($regfields as $params) {
                        if (isset($refRow->$params)) {
                            $varR = $params . "_ref";
                            $$varR = unserialize(base64_decode($refRow->$params));
                        }
                    }
                }

                $tmpRowRslts_ref = array();
                $totalNilaiRef = 0;
                if (sizeof($rsltItems_ref) > 0) {
                    foreach ($rsltItems_ref as $rowRefID => $rowref_data) {
                        // $hpp = number_format($rowref_data['hpp'],0);
                        $hpp = $rowref_data['hpp'];
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] = 0;

                        }
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] = 0;
                        }

                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] += $rowref_data['qty'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] += $rowref_data['sub_hpp'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['harga'] = $rowref_data['hpp'];
                        $totalNilaiRef += $rowref_data['sub_hpp'];
                    }
                    $rowDetilRslt_label[$main_reg['pihakExternID']] = array(
                        "harga" => "harga",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                    );
                }
                //endregion

                //region current detil
                $tmpRowRslts = array();
                $tmpRowRsltsnilai = 0;
                foreach ($rsltItems_reg as $rowFifo => $fifoData) {
                    // $hpp_0 = number_format($fifoData['hpp'],0);
                    $hpp_0 = $fifoData['hpp'];
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] = 0;
                    }
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] = 0;
                    }
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] += $fifoData['qty'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] += $fifoData['sub_hpp'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['harga'] = $fifoData['hpp'];
                    $tmpRowRsltsnilai += $fifoData['sub_hpp'];
                }
                $tmpRowRslts_label = array(
                    "nama" => "produk",
                    "qty" => "qty",
                    $main_reg['pihakExternID'] => $main_reg['pihakExternName'],
                    $main_reg['jenisTr'] => $main_reg['jenisTrName'],
                );
                $rowDetilRslt_label[$main_reg['jenisTr']] = array(
                    "harga" => "harga",
                    "qty" => "qty",
                    "subtotal" => "subtotal",
                );

                //endregion
            }
        }

        $transaksiJenisTopLabel = isset($main_reg['jenisTrName']) ? $main_reg['jenisTrName'] : "";
        //-------------------------------------
        if (($configNotes != NULL) && ($globalVars['trash_4'] == 1)) {
            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {
                    $configNotes = str_replace("{" . $key . "}", $val, $configNotes);
                }
            }
            $notes_final = $configNotes;
        }
        else {
            $notes_final = NULL;
        }


        //region packinglist/serial
        $shoppingCartSubDetailFields = isset($this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3] : array();
        $shopingCartPairFieldResume = isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume'] : array();
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            cekHere(":: $jenisMasterID ::");
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$jenisMasterID'");
            $tr->addFilter("jenis='5822pkd'");
            $tr->addFilter("trash_4='0'");
            $tmp0 = $tr->lookupAll()->result();
            $ids_transaksi = array();
            foreach ($tmp0 as $tmp0_x) {
                $ids_transaksi[] = $tmp0_x->id;
            }

            if (count($ids_transaksi) > 0) {

                cekHitam($this->db->last_query());
                //            matiHere(__LINE__);
                //            $trid_pkd = $tmp0[0]->id;
                $tr->setFilters(array());
                //            $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
                //            $tr->addFilter("transaksi_id='$trid_pkd'");
                $tr->addFilter("transaksi_id in (" . implode(',', $ids_transaksi) . ")");
                $tmpReg = $tr->lookupDataRegistries()->result();
                //                arrPrint($pairRegistry);
                //                cekBiru($this->db->last_query());
                $dataRegistry = array();
                if (sizeof($tmpReg) > 0) {
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $eParams) {

                            $dataRegistry[] = blobDecode($eParams->items);
                            foreach ($pairRegistry as $param) {
                                $dataRegistry[$param][] = blobDecode($eParams->$param);
                                //                            switch ($param){
                                //                                case "main":
                                //                                    $main[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items":
                                //                                    $items[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items3_sum":
                                //                                    $items3_sum[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                            }
                                //                            unset($param->transaksi_id);
                                //                            if ($eParams->$param == null) {
                                //                                $eParams->$param = blobEncode(array());
                                //                            }
                                ////                            $registryData[]
                                //                            $$param = blobDecode($eParams->$param);
                            }
                        }
                    }
                }

                $arrSubDetailDataKolom = array();
                foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                    $gerbang = $kolomSpec["gate"];
                    $source = $kolomSpec["source"];
                    $source_new = $dataRegistry[$source];
                    $arrHasil = array();
                    foreach ($source_new as $ix => $sxdata) {

                        foreach ($sxdata as $sdata) {
                            //                        arrPrint($sdata);
                            //                        matiHEre(__LINE__);
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


                    }
                    $arrSubDetailDataKolom[$kolom] = $arrHasil;
                    //                arrPrint($arrSubDetailDataKolom);
                }
                $fullfillment = array();
                if (count($shopingCartPairFieldResume) > 0) {
                    if (isset($dataRegistry["items"])) {
                        foreach ($dataRegistry["items"] as $ixx => $data) {
                            foreach ($data as $pid => $data_0) {
                                foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                    if (!isset($shipment[$pid][$field_shipment])) {
                                        $shipment[$pid][$field_shipment] = 0;
                                    }
                                    $shipment[$pid][$field_shipment] += $data_0[$field_shipment];
                                }
                            }

                        }
                    }
                }

                if (count($fullfil_id) > 0) {
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id='$fullfil_id'");
                    $tmpRegFulfil = $tr->lookupDataRegistries()->result();
                    cekMerah($this->db->last_query());
                    $itemsFulfilmentTmp = blobDecode($tmpRegFulfil[0]->items);
                    if (count($shopingCartPairFieldResume) > 0) {
                        //                    if(isset($dataRegistry["items"])){
                        foreach ($itemsFulfilmentTmp as $pid => $data_0) {
                            //                            foreach ($data as $pid =>$data_0){
                            foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                if (!isset($shipment[$pid][$field_shipment])) {
                                    $fullfillment[$pid][$field_shipment] = 0;
                                }
                                $fullfillment[$pid][$field_shipment] += $data_0[$field_shipment];
                            }
                            //                            }

                        }
                        //                    }
                    }
                }
            }


        }
        else {
            //            cekMerah("::: KOSONG :::");
        }

        //-------------------------------------
        $imagesReference = getTransaksiImageReference($jenisMasterID)[$jenisMasterID];

        $data = array(
            //            "mode" => $this->uri->segment(3),
            "mode" => count($imagesReference) > 0 ? "viewResumeImages" : $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
            "items" => $lItems,
            "receiptLink" => $receiptLink,
            "signStr" => $signStr,
            "cabangData" => $arrCabang,

            "urutCounter" => $urutCounters,
            "underMaintenance" => underConstruction(),

            // ===== ======
            "detail_title" => "Pengajuan $transaksiJenisTopLabel",

            "itemLabels" => isset($itemLabelsTop) ? $itemLabelsTop : array(),
            "item2Labels" => isset($item2LabelsTop) ? $item2LabelsTop : array(),
            "item3Labels" => isset($item3LabelsTop) ? $item3LabelsTop : array(),

            "detail_main" => isset($main_reg) ? $main_reg : array(),
            "detail_items" => isset($items_reg) ? $items_reg : array(),
            "detil_item_rslt" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            // "detil_item_rslt_ref" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            "detil_item_rslt_label" => isset($tmpRowRslts_label) ? $tmpRowRslts_label : array(),
            "detil_item_rslt_label2" => isset($rowDetilRslt_label) ? $rowDetilRslt_label : array(),
            "detail2_items" => isset($items2_sum_reg) ? $items2_sum_reg : array(),
            "detail3_items" => isset($items3_sum_reg) ? $items3_sum_reg : array(),

            "detail_sumRows" => $sumRows,
            "detail2_sumRows" => isset($sumRows2) ? $sumRows2 : array(),
            "detail3_sumRows" => isset($sumRows3) ? $sumRows3 : array(),
            "noteEnabled" => $noteEnabled,
            "addRows" => $addFildRowFields,
            //--------------------
            "mainNotApprove" => isset($mainNotApprove) ? $mainNotApprove : array(),
            "itemsNotApprove" => isset($itemsNotApprove) ? $itemsNotApprove : array(),
            "detail_not_approve_title" => "Pengajuan $transaksiJenisTopLabel (tidak diapprove)",
            //--------------------
            "notes_final" => $notes_final,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "referenceImages" => $imagesReference,
            "elementStr" => isset($elementStr) ? $elementStr : "",
            "deliveryDetail" => isset($deliveryDetail) ? $deliveryDetail : array(),
            "shipment" => $shipment,
            "fullfillment" => $fullfillment,
            "customHeaderResume" => isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil'] : array(),
        );
        $this->load->view("transaksi", $data);
    }

    public function viewResume_03agus2026()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1[0]) > 0) {
            foreach ($tmp1[0] as $tmpKey => $tmpVal) {
                $rkey_field = $tmpKey;
                if ($rkey_field == "nomer") {
                    $rkey_field = "nomer_nolink";
                }
                //                $globalDatas[$rkey] = formatField_he_format($rkey_field, $rval, "", "");
                $globalVars[$tmpKey] = formatField_he_format($rkey_field, $tmpVal, "", "");
            }
        }
        $jenisMasterID = $tmp1[0]->id_master;
        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = ($tmp1[0]->step_number < 1) ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items", "main_elements", "transaksi_id");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;
        $configConnectEdit = isset($configUiJenis["connectToEdit"]) ? $configUiJenis["connectToEdit"] : NULL;
        $fullfil_id = 0;
        $jenisTr_110r = array("110r_1");
        $jenisTr_spd = "5822spd";
        $jenisTr_spd_link = array("5822spd_4");

        // membaca status edit di TOPID
        $tr = new MdlTransaksi();
        $tr->addFilter("id='$transaksiTopID'");
        $tmpTop = $tr->lookupAll()->result();
        $arrTopEdit = array();
        $epEdit = array();
        if (sizeof($tmpTop) > 0) {
            if ((isset($tmpTop[0]->status_edit)) && ($tmpTop[0]->status_edit == 1)) {
                $arrTopEdit[$transaksiTopID] = array(
                    "status_edit" => $tmpTop[0]->status_edit,
                    "edit_dtime" => $tmpTop[0]->edit_dtime,
                    "edit_id" => $tmpTop[0]->edit_id,
                    "edit_name" => $tmpTop[0]->edit_name,
                );
                $epEdit[$tmpTop[0]->id] = $tmpTop[0]->id->master;
            }
            $fullfil_id = is_null($tmpTop[0]->fullfillment_id) ? 0 : $tmpTop[0]->fullfillment_id;
        }

        //region swap from registry
        $pairAddRegistry = array("main_elements");
        foreach ($pairAddRegistry as $regkey) {
            $pairRegistry[] = $regkey;
        }
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();
        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {

                        if ($eParams->$param == null) {
                            $eParams->$param = blobEncode(array());
                        }
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion

        $deliveryDetail = blobDecode($main_elements["deliveryDetails"]["contents"]);

        //region signatures
        //ambil data fullfill dulu bro biar bisa digabung sign
        if ($fullfil_id > 0) {
            $trf = new MdlTransaksi();
            $trf->setFilters(array());
            $trf->addFilter("id='$fullfil_id'");
            $tmp_master_fulfill = $trf->lookupMainTransaksi()->result();
            $masterID_fullfil = $tmp_master_fulfill[0]->id_master;
        }
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if ($fullfil_id > 0) {
                $trs->addFilter("transaksi_id in ($masterID,$masterID_fullfil)");
            }
            else {
                $trs->addFilter("transaksi_id='$masterID'");
            }
        }
        else {
            $trs->addFilter("transaksi_id='$masterID'");
        }


        //        $tmpSign = $trs->lookupSignatureResumeByMasterID()->result();//belum tak matiin
        $tmpSign = $trs->lookupSignaturesByMasterID($masterID)->result();
        //                 cekLime($this->db->last_query());
        //         arrPrint($tmpSign);
        $arrCounters = blobDecode($tmp1[0]->counters);
        $urutCounters = NULL;
        if (sizeof($arrCounters) > 0) {
            $key2 = $tmp1[0]->jenis . "|" . $tmp1[0]->cabang_id;
            $urutCounters = digit_5($arrCounters["stepCode|placeID"][$key2]);
        }
        if ($urutCounters != NULL) {
            $urutCounters = "-$urutCounters";
        }

        if (isset($this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames'])) {
            $resumeFields = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['selectFields'];
            $resumeTitle = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['title'];
            $resumeName = isset($tmp1[0]->$resumeFields) ? $tmp1[0]->$resumeFields : "";
        }
        else {
            $resumeTitle = "";
            $resumeName = "";
        }

        $arrSignName = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $ii => $row) {
                $signValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "label" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    //                    "title" => $row->step_name,
                    //                    "label" => $row->step_name,
                    "dtime" => $row->dtime,

                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,

                    "oleh" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                    "prev_id" => isset($row->prev_id) ? $row->prev_id : 0,
                    "link_id" => isset($row->transaksi_id) ? $row->transaksi_id : 0,
                );
                $arrSignName[$ii] = array(
                    "nama" => $row->oleh_nama,
                    "dtime" => $row->dtime,
                );
            }
        }


        $signStr = "";
        //endregion

        //-------------counter global------------
        $counterGlobalOverride = null;
        if (!empty($this->configUiJenis['counter_global'])) {
            $key = $this->configUiJenis['counter_global'];
            $counterGlobalOverride = isset($tmp1[0]->$key) ? $tmp1[0]->$key : null;
        }

        //entry points
        $trep = new MdlTransaksi();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
//        showLast_query("biru");
//        cekBiru(count($tmpEP));
        $epValues = array();
        $rowStepNumber = array();
        $epDetail = array();
        $terimaAr = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
//                cekOrange($row->id." || ".$row->nomer);
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobalOld = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterGlobal = ($counterGlobalOverride !== null) ? $counterGlobalOverride : $counterGlobalOld;
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $id_master = $row->id_master;
                if (empty($row->deskripsi)) {
                    if (!isset($rowStepNumber["referenceID__" . $row->step_number])) {
                        $rowStepNumber["referenceID__" . $row->step_number] = array(
                            "id" => $row->id,
                            "link_id" => $row->link_id,
                            "step_num" => $row->step_number,

                        );
                    }
                    $kkey = 'sign_' . $row->id . '_' . $row->step_number;
                    $epValues[$kkey] = array(
                        "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => $row->jenis_label,
                        "dtime" => $row->dtime,
                        "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->oleh_nama,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
                        "global_counter" => $cGlobals,
                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
//                        "date_faktur" => isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL,
//                        "nomer_faktur" => isset($row->efaktur) ? $row->efaktur : NULL,
//                        "gunggungan_mode" => isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0,
//
                        "kkey" => $kkey,
                    );
                    if ($row->efaktur != 0) {
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["date_faktur"] = isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["nomer_faktur"] = isset($row->efaktur) ? $row->efaktur : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["gunggungan_mode"] = isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0;
                    }
                }
                else {
                    $tmpReg = $tr->lookupDataRegistriesByMasterID($row->link_id)->result();
                    $main = blobDecode($tmpReg[0]->main);
                    if ($jenis == "5822spd") {
                        $main["referenceID__4"] = $main["referenceID"];
                    }
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        foreach ($main as $m_key => $mval) {
                            if (isset($rowStepNumber[$m_key])) {
                                cekMerah($m_key . "::" . $mval . " ::*" . $rowStepNumber[$m_key]["id"]);
//                                switch ($jenis) {
//                                    case "9112":
//                                    case "9111":
//                                        break;
//                                    default:
//                                        break;
//                                }
//
                                $epValues['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = $row->deskripsi;
                                $epDetail['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                );
                            }
                            else {
                                //                            cekBiru("lolosss");
                            }
                        }
                    }
                    else {
                        switch ($main["jenisTr"]) {
                            case "9912":
                            case "9911":
//                                cekUngu($main["referenceStepNumber"]);
                                $selectedTrid = $main["transaksi"] + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["referenceStepNumber"]]["status"] = array(
                                    "oleh_nama" => $main["olehName"],
                                    "dtime" => $main["dtime"],
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                $kkey = 'sign_' . $row->id . '_' . $row->step_number . '_r';
                                $epValues[$kkey] = array(
                                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                                    "cabang_nama" => $row->cabang_nama,
                                    "label" => $row->jenis_label,
                                    "dtime" => $main["dtime"],
                                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                                    "nomer_orig" => $row->nomer,
                                    "oleh" => $main["olehName"],
                                    "caption_department" => "text",
                                    "deskripsi" => $row->deskripsi,
                                    "link_id" => $row->link_id,
                                    "global_counter" => $cGlobals,
                                    "master_id_global_counter" => $cMasterIDGlobals,
                                    "jenis" => $jenis,
                                    "kkey" => $kkey,
                                );
                                break;
                            default:
                                $selectedTrid = isset($main["transaksi"]) ? $main["transaksi"] : $main["currentID"];
                                $selectedTrid = $selectedTrid + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["stepNumber"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                $kkey = 'sign_' . $row->id . '_' . $row->step_number . '_r';
                                $epValues[$kkey] = array(
                                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                                    "cabang_nama" => $row->cabang_nama,
                                    "label" => $row->jenis_label,
                                    "dtime" => $row->dtime,
                                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                                    "nomer_orig" => $row->nomer,
                                    "oleh" => $row->oleh_nama,
                                    "caption_department" => "text",
                                    "deskripsi" => $row->deskripsi,
                                    "link_id" => $row->link_id,
                                    "global_counter" => $cGlobals,
                                    "master_id_global_counter" => $cMasterIDGlobals,
                                    "jenis" => $jenis,
                                    "kkey" => $kkey,
                                );
                                break;
                        }
                    }
                }
                if (in_array($row->jenis, $jenisTr_spd_link)) {
                    $terimaAr[$row->link_id] = $row->link_id;
                }
                if (in_array($row->jenis, $jenisTr_110r)) {
                    $inputFaktur[$row->link_id] = $row->link_id;
                }
            }
        }

        //------LINK KE AR
        if (sizeof($terimaAr) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis='749'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $terimaAr) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
            showLast_query("pink");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $kkey = 'sign_' . $row->transaksi_id . '_' . $row->step_number;
                $epValues[$kkey] = array(
                    "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                    "kkey" => $kkey,
                );
            }
        }
        //------
        if (sizeof($inputFaktur) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis_master='110'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $inputFaktur) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
//            showLast_query("kuning");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $kkey = 'sign_' . $row->transaksi_id . '_' . $row->step_number;
                $epValues[$kkey] = array(
                    "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                    "date_faktur" => $row->efaktur_dtime,
                    "nomer_faktur" => $row->efaktur,
                    "gunggungan_mode" => $row->gunggungan_mode,
                    "kkey" => $kkey,
                );
                if ($row->trash_4 == 1) {
                    $kkey_old = 'sign_' . $row->transaksi_id . '_' . $row->step_number;
                    $kkey = 'sign_' . $row->transaksi_id . '_' . $row->step_number . '_r';
                    if (!array_key_exists($kkey_old, $epValues)) {
                    }
                    $epValues[$kkey] = array(
                        "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => "Pembatalan/Reject " . $row->jenis_label,
//                        "dtime" => $row->dtime,
                        "dtime" => $row->cancel_dtime,
                        "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                        "nomer_reject" => formatField_he_format("nomer_nolink", isset($row->cancel_transaksi_nomer) ? $row->cancel_transaksi_nomer : NULL),
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->cancel_name,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
//                        "global_counter" => $cGlobals,
//                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
                        "date_faktur" => $row->efaktur_dtime,
                        "nomer_faktur" => $row->efaktur,
                        "gunggungan_mode" => $row->gunggungan_mode,
                        "kkey" => $kkey,
                    );
                }
            }
        }

//arrPrintCyan($epValues);

        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;
        $steps = $configUiJenis;
        foreach ($steps as $step => $keis) {
            $autoNextStep = isset($keis['autoNextStep']) ? $keis['autoNextStep'] : 0;
        }
//arrPrintCyan($signArray);
        $pakai_ini_urut_keyid = 0;
        if ($pakai_ini_urut_keyid == 1) {
            $signArrayKeys = array_keys($signArray);
            foreach ($signArrayKeys as $keyy) {
                $keyy_ex = explode("_", $keyy);
                $signArrayKeysTrid[$keyy_ex[1]] = $keyy;
            }
        }
        else {
            foreach ($signArray as $xx) {
                $newKey = $xx["dtime"] . "_" . $xx["kkey"];
//                $signArrayKeysTrid[$xx["dtime"]] = $xx;
                $signArrayKeysTrid[$newKey] = $xx;
            }
        }

        ksort($signArrayKeysTrid);
//        arrPrintCyan($signArray);
        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel table-responsive'>";
            $signStr .= "<h4 class='text-blue'><span class='fa fa-sign-in'></span> entry points </h4>";

            $signStr .= "<h5 class='text-blue'><span class='fa fa-tasks'></span> $resumeTitle  $resumeName</h5>";
            $signStr .= "<table class='table table-condensed table-striped table-hover-color-red dataTable'>";
            $signStr .= "<tr bgcolor='#f0f0f0'>";
            $signStr .= "<td class='text-muted'>date</td>";
            $signStr .= "<td class='text-muted'>title</td>";
            $signStr .= "<td class='text-muted'>branch</td>";
            $signStr .= "<td class='text-muted'>transaction number</td>";
            $signStr .= "<td class='text-muted'>person</td>";
            $signStr .= "<td class='text-muted'>status</td>";
            $signStr .= "<td class='text-muted'>tool</td>";
            $nom = 0;
            foreach ($signArrayKeysTrid as $k_ii => $v_ii) {
                $nom++;
                if ($pakai_ini_urut_keyid == 1) {
                    $k = $v_ii;
                    $sSpec = $signArray[$v_ii];
                    $jenis = $sSpec['jenis'];
                    $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                    $nomerOrig = $sSpec['nomer_orig'];
                    $nomerOrigEx = explode("_", $nomerOrig);
                    $nomerOrigNota = $nomerOrigEx[0];
                    $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                    $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                    $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;
                    $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                    $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";
                    $edit_description = "";
                    if (isset($arrTopEdit[$sSpec['link_id']])) {
                        $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                        $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                        $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                    }
                    $rejection_label = "";
                    $reject_description = "";
                    if (isset($epDetail[$k])) {
                        $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                        $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                        $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                        $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                    }
                    //-----
                    $date_faktur = "";
                    $nomer_faktur = "";
                    $gunggungan_mode = 0;
                    $gunggungan_mode_bgcolor = "";
                    if (isset($sSpec["date_faktur"])) {
                        $date_faktur = $sSpec["date_faktur"];
                    }
                    if (isset($sSpec["nomer_faktur"])) {
                        $nomer_faktur = $sSpec["nomer_faktur"];
                    }
                    if (isset($sSpec["gunggungan_mode"])) {
                        $gunggungan_mode = $sSpec["gunggungan_mode"];
                        $gunggungan_mode_bgcolor = "yellow";
                    }
                }
                else {
                    $k = $v_ii;
                    $sSpec = $v_ii;
                    $jenis = $sSpec['jenis'];
                    $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                    $nomerOrig = $sSpec['nomer_orig'];
                    $nomerOrigEx = explode("_", $nomerOrig);
                    $nomerOrigNota = $nomerOrigEx[0];
                    $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                    $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                    $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;
                    $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                    $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";
                    $edit_description = "";
                    if (isset($arrTopEdit[$sSpec['link_id']])) {
                        $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                        $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                        $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                    }
                    $rejection_label = "";
                    $reject_description = "";
                    if (isset($epDetail[$k])) {
                        $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                        $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                        $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                        $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                    }
                    //-----
                    $nomer_reject = "";
                    $date_faktur = "";
                    $nomer_faktur = "";
                    $gunggungan_mode = 0;
                    $gunggungan_mode_bgcolor = "";
                    if (isset($sSpec["nomer_reject"])) {
                        $nomer_reject = $sSpec["nomer_reject"];
                    }
                    if (isset($sSpec["date_faktur"])) {
                        $date_faktur = $sSpec["date_faktur"];
                    }
                    if (isset($sSpec["nomer_faktur"])) {
                        $nomer_faktur = $sSpec["nomer_faktur"];
                    }
                    if (isset($sSpec["gunggungan_mode"])) {
                        $gunggungan_mode = $sSpec["gunggungan_mode"];
                        $gunggungan_mode_bgcolor = "yellow";
                    }
                }


                $signStr .= "<tr style='background-color:$gunggungan_mode_bgcolor;'>";
                $signStr .= "<td>" . formatField_he_format("date_time", $sSpec['dtime']);
                $signStr .= "</td>";
                $signStr .= "<td>" . ($sSpec['label']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                $signStr .= "<td>" . (formatField_he_format("nomer_nolink", $nomerViewFinal)) . $signDeskripsi . "";
                if ($edit_description != "") {
                    $signStr .= "<br>$edit_description";
                }
                if ($date_faktur != "") {
                    $signStr .= "<br>tanggal faktur: $date_faktur";
                }
                if ($nomer_faktur != "") {
                    $signStr .= "<br>Nomer faktur: $nomer_faktur";
                }
                if ($nomer_reject != "") {
                    $signStr .= "<br>Nomer Pembatalan/Reject: $nomer_reject";
                }

                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $oleh_nama . "";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $status . "<br>" . $reject_description . "";
                $signStr .= "</td>";

                $signStr .= "<td title='" . $sSpec['label'] . "'>";
//                if($nom == 1){
//                    $signStr .= "-";
//                }
//                else{
//                }
                $signStr .= formatField_he_format("print_label", $nomerOrigNota, $this->jenisTr, MODUL_PATH);

                $signStr .= "</td>";

                $signStr .= "</tr>";
            }
            $signStr .= "</table class='table table-condensed'>";

            $signStr .= "</div class='panel'>";
        }


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $allowedView = $this->config->item('userGroup_jurnal') != null ? $this->config->item('userGroup_jurnal') : array();
        $rekeningAlias = $this->config->item('accountAlias') != null ? $this->config->item('accountAlias') : array();

        $lItems = array();
        $headers = array();
        $arrCabang = array();
        if (array_intersect($allowedView, $mems)) {
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Mdls/MdlCabang");

            $cb = New MdlCabang();
            $tmpCb = $cb->lookupAll()->result();
            $arrCabang = array();
            if (sizeof($tmpCb) > 0) {
                foreach ($tmpCb as $spec) {
                    $arrCabang[$spec->id] = $spec->nama;
                }
            }

            $j = new ComJurnal();
            $j->addFilter("transaksi_no='$no'");
            $j->addFilter("status='1'");
            $j->addFilter("trash='0'");
            if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

            }
            else {
                // dimatikan dulu untuk checking jurnal lengkapnya...........
                //                $j->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            //            $j->setSortBy(array("kolom" => "urut", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            if (sizeof($tmp) > 0) {
                /* ---------------------------------------
                 * array link untuk mengarahkan ke halaman mutasi yg dikendaki
                 * -----------------------------------*/

                //region Description
                $mutasiLinks = array();
                foreach ($accountChildsLinks as $rekNama => $rekLink) {
                    $mutasiLinks[$rekNama] = $rekLink . "/" . $main['pihakID'] . "?o=" . $main['cabangID'] . "&trID=$transaksiID";
                }
                //endregion

                foreach ($tmp as $row) {
                    $d_start = backCustomDate("30", $row->dtime);
                    $d_stop = $row->dtime;
                    if ($row->j_jenis == "items") {
                        $blob_ext = blobEncode($row->rekening);
                        //                        Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/1076?o=-1&ext2_id=&main_ext2_id=
                        $defLink = "Ledger/viewMoveDetails/RekeningPembantuProduk/" . $row->rekening_main . "/" . $row->extern_id . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop&blob_ext=$blob_ext";
                    }
                    else {
                        $defLink = "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                        //                    $defLink = "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                    }

                    //                    $rekening_alias = isset($rekeningAlias[$row->rekening]) ? $rekeningAlias[$row->rekening] : $row->rekening;
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        //$strLink = key_exists($row->rekening, $mutasiLinks) ? $mutasiLinks[$row->rekening] : $defLink;
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }

                ksort($lItems);
            }


        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $mobMode = $isMob == true ? "1" : "0";

        $printSettings = isset($this->configLayout[$jenisMasterTrans]['allowPrint']['1']) ? $this->configLayout[$jenisMasterTrans]['allowPrint']['1'] : array();
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Transaksi/viewReceipt/";

        $valasType = (isset($main['currencyDetails__label'])) ? "&type=" . blobEncode($main['currencyDetails__label']) : "";
        $valasFKali = "&f=" . blobEncode(1);

        if ($step_number == 1000) {
            $receiptLink = "";
        }
        else {
            if (sizeof($tmpSign) > 0) {
                if (isset($printSettings['size'])) {
                    switch ($printSettings['size']) {
                        case "normal":
                            $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=1$valasType$valasFKali');";
                            break;
                        case "small":
                            $receiptLink = "top.popSmall('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=2');";
                            break;
                        default:
                            //                                cekkuning("size: UNKNOWN");
                            break;
                    }
                }
                else {
                    $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=3$valasType$valasFKali');";
                }
            }
            else {
                $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?st=4');";
            }
        }


        // ============== TAMBAHAN ============== ============== ==============
        $itemLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields'][1] : array();
        $itemLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1] : array();
        $itemLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1] : array();
        $itemNumLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1] : array();
        $itemNumLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1] : array();
        $itemNumLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1] : array();
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1];
        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;


        $arrCons = array();
        $headerScheme = array();

        if ($showScheme) {

            $awal_pinjaman = isset($main['awal_pinjaman']) ? $main['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($main['jatuh_tempo']) ? $main['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($main['harga']) ? $main['harga'] : 0;
            $rate_bunga = isset($main['persen_bunga']) ? $main['persen_bunga'] : 0;

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

        $itemLabelsTop = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $item2LabelsTop = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $item3LabelsTop = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemLabelBlacklist = array(
            "stok_center",
            "stok",
        );

        foreach ($itemLabelsTop as $key => $val) {
            if (in_array($key, $itemLabelBlacklist)) {
                unset($itemLabelsTop[$key]);
            }
        }
        if (isset($this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount']) && $this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount'][1] == true) {
            unset($itemLabelsTop['subtotal']);
            unset($item2LabelsTop['subtotal']);
            unset($item3LabelsTop['subtotal']);
        }
        $blacklistView = $this->config->item('userGroup_blacklist') != null ? $this->config->item('userGroup_blacklist') : array();
        $addFildRowFields = array();


        $allowedViewTop = false;
        foreach ($mems as $memsVal) {
            if (!in_array($memsVal, $blacklistView)) {
                $allowedViewTop = true;
            }
        }

        if ($allowedViewTop == true) {

            //untuk melihat data order/spo nya
            $m = new MdlTransaksi();
            $m->setFilters(array());
            $tmpReg = $m->lookupDataRegistriesByMasterID($transaksiTopID)->result();
            $regfields = $m->getFields()["dataRegistry"];
            $addRow = isset($this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes']) ? $this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes'] : array();
            $itemNotApprove = isset($this->configUi[$jenisMasterTrans]['closedRequest'][1]) ? $this->configUi[$jenisMasterTrans]['closedRequest'][1] : true;
            $detailCore = isset($this->configCore[$jenisMasterTrans]['valueGates']['detail']) ? $this->configCore[$jenisMasterTrans]['valueGates']['detail'] : array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regfields as $params) {
                        // cekHitam($params);
                        if (isset($row->$params) && ($params != "transaksi_id")) {

                            $var = $params . "_reg";
                            $$var = unserialize(base64_decode($row->$params));
                        }

                    }
                    // $$var = unserialize(base64_decode($row->values));
                }
                if (sizeof($addRow) > 0) {
                    foreach ($addRow as $gateKey => $mpFields) {

                        if (isset($mpFields['hideRow']) && $mpFields['hideRow'] == true) {

                        }
                        else {
                            $addFildRowFields[$gateKey] = $mpFields['label'];
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    //                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID)->result();
                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID);

                    $notApprove = array();
                    foreach ($dTmp as $spec) {
                        if ((isset($spec->valid_qty_no_approve)) && ($spec->valid_qty_no_approve > 0)) {
                            $notApprove[$spec->produk_id] = $spec->valid_qty_no_approve;
                        }
                    }
                    $mainNotApprove = array();
                    $itemsNotApprove = array();
                    if (sizeof($notApprove) > 0) {
                        $itemsNotApprove = $items_reg;
                        foreach ($notApprove as $pid => $pqty_not_approve) {
                            $itemsNotApprove[$pid]['qty'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['jml'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['subtotal'] = $pqty_not_approve * $itemsNotApprove[$pid]['harga'];
                            if (sizeof($detailCore) > 0) {
                                foreach ($detailCore as $key_c => $val_c) {
                                    $itemsNotApprove[$pid]['sub_' . $key_c] = $pqty_not_approve * $itemsNotApprove[$pid][$key_c];
                                    if (!isset($mainNotApprove[$key_c])) {
                                        $mainNotApprove[$key_c] = 0;
                                    }
                                    $mainNotApprove[$key_c] += ($pqty_not_approve * $itemsNotApprove[$pid][$key_c]);
                                }
                            }
                            if (!isset($mainNotApprove['harga'])) {
                                $mainNotApprove['harga'] = 0;
                            }
                            $mainNotApprove['harga'] += ($pqty_not_approve * $itemsNotApprove[$pid]['harga']);
                        }
                    }
                }
            }

            //tambahan jika ada result item
            // arrPrint($main_reg);
            if (sizeof($rsltItems_reg) > 0) {
                //region reference detil
                $m->setFilters(array());
                $tmpRegRef = $m->lookupDataRegistriesByMasterID($main_reg['referenceID']);
                foreach ($tmpRegRef as $refRow) {
                    foreach ($regfields as $params) {
                        if (isset($refRow->$params)) {
                            $varR = $params . "_ref";
                            $$varR = unserialize(base64_decode($refRow->$params));
                        }
                    }
                }

                $tmpRowRslts_ref = array();
                $totalNilaiRef = 0;
                if (sizeof($rsltItems_ref) > 0) {
                    foreach ($rsltItems_ref as $rowRefID => $rowref_data) {
                        // $hpp = number_format($rowref_data['hpp'],0);
                        $hpp = $rowref_data['hpp'];
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] = 0;

                        }
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] = 0;
                        }

                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] += $rowref_data['qty'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] += $rowref_data['sub_hpp'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['harga'] = $rowref_data['hpp'];
                        $totalNilaiRef += $rowref_data['sub_hpp'];
                    }
                    $rowDetilRslt_label[$main_reg['pihakExternID']] = array(
                        "harga" => "harga",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                    );
                }
                //endregion

                //region current detil
                $tmpRowRslts = array();
                $tmpRowRsltsnilai = 0;
                foreach ($rsltItems_reg as $rowFifo => $fifoData) {
                    // $hpp_0 = number_format($fifoData['hpp'],0);
                    $hpp_0 = $fifoData['hpp'];
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] = 0;
                    }
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] = 0;
                    }
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] += $fifoData['qty'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] += $fifoData['sub_hpp'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['harga'] = $fifoData['hpp'];
                    $tmpRowRsltsnilai += $fifoData['sub_hpp'];
                }
                $tmpRowRslts_label = array(
                    "nama" => "produk",
                    "qty" => "qty",
                    $main_reg['pihakExternID'] => $main_reg['pihakExternName'],
                    $main_reg['jenisTr'] => $main_reg['jenisTrName'],
                );
                $rowDetilRslt_label[$main_reg['jenisTr']] = array(
                    "harga" => "harga",
                    "qty" => "qty",
                    "subtotal" => "subtotal",
                );

                //endregion
            }
        }

        $transaksiJenisTopLabel = isset($main_reg['jenisTrName']) ? $main_reg['jenisTrName'] : "";
        //-------------------------------------
        if (($configNotes != NULL) && ($globalVars['trash_4'] == 1)) {
            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {
                    $configNotes = str_replace("{" . $key . "}", $val, $configNotes);
                }
            }
            $notes_final = $configNotes;
        }
        else {
            $notes_final = NULL;
        }


        //region packinglist/serial
        $shoppingCartSubDetailFields = isset($this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3] : array();
        $shopingCartPairFieldResume = isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume'] : array();
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            cekHere(":: $jenisMasterID ::");
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$jenisMasterID'");
            $tr->addFilter("jenis='5822pkd'");
            $tr->addFilter("trash_4='0'");
            $tmp0 = $tr->lookupAll()->result();
            $ids_transaksi = array();
            foreach ($tmp0 as $tmp0_x) {
                $ids_transaksi[] = $tmp0_x->id;
            }

            if (count($ids_transaksi) > 0) {

                cekHitam($this->db->last_query());
                //            matiHere(__LINE__);
                //            $trid_pkd = $tmp0[0]->id;
                $tr->setFilters(array());
                //            $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
                //            $tr->addFilter("transaksi_id='$trid_pkd'");
                $tr->addFilter("transaksi_id in (" . implode(',', $ids_transaksi) . ")");
                $tmpReg = $tr->lookupDataRegistries()->result();
                //                arrPrint($pairRegistry);
                //                cekBiru($this->db->last_query());
                $dataRegistry = array();
                if (sizeof($tmpReg) > 0) {
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $eParams) {

                            $dataRegistry[] = blobDecode($eParams->items);
                            foreach ($pairRegistry as $param) {
                                $dataRegistry[$param][] = blobDecode($eParams->$param);
                                //                            switch ($param){
                                //                                case "main":
                                //                                    $main[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items":
                                //                                    $items[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items3_sum":
                                //                                    $items3_sum[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                            }
                                //                            unset($param->transaksi_id);
                                //                            if ($eParams->$param == null) {
                                //                                $eParams->$param = blobEncode(array());
                                //                            }
                                ////                            $registryData[]
                                //                            $$param = blobDecode($eParams->$param);
                            }
                        }
                    }
                }

                $arrSubDetailDataKolom = array();
                foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                    $gerbang = $kolomSpec["gate"];
                    $source = $kolomSpec["source"];
                    $source_new = $dataRegistry[$source];
                    $arrHasil = array();
                    foreach ($source_new as $ix => $sxdata) {

                        foreach ($sxdata as $sdata) {
                            //                        arrPrint($sdata);
                            //                        matiHEre(__LINE__);
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


                    }
                    $arrSubDetailDataKolom[$kolom] = $arrHasil;
                    //                arrPrint($arrSubDetailDataKolom);
                }
                $fullfillment = array();
                if (count($shopingCartPairFieldResume) > 0) {
                    if (isset($dataRegistry["items"])) {
                        foreach ($dataRegistry["items"] as $ixx => $data) {
                            foreach ($data as $pid => $data_0) {
                                foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                    if (!isset($shipment[$pid][$field_shipment])) {
                                        $shipment[$pid][$field_shipment] = 0;
                                    }
                                    $shipment[$pid][$field_shipment] += $data_0[$field_shipment];
                                }
                            }

                        }
                    }
                }

                if (count($fullfil_id) > 0) {
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id='$fullfil_id'");
                    $tmpRegFulfil = $tr->lookupDataRegistries()->result();
                    cekMerah($this->db->last_query());
                    $itemsFulfilmentTmp = blobDecode($tmpRegFulfil[0]->items);
                    if (count($shopingCartPairFieldResume) > 0) {
                        //                    if(isset($dataRegistry["items"])){
                        foreach ($itemsFulfilmentTmp as $pid => $data_0) {
                            //                            foreach ($data as $pid =>$data_0){
                            foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                if (!isset($shipment[$pid][$field_shipment])) {
                                    $fullfillment[$pid][$field_shipment] = 0;
                                }
                                $fullfillment[$pid][$field_shipment] += $data_0[$field_shipment];
                            }
                            //                            }

                        }
                        //                    }
                    }
                }
            }


        }
        else {
            //            cekMerah("::: KOSONG :::");
        }

        //-------------------------------------
        $imagesReference = getTransaksiImageReference($jenisMasterID)[$jenisMasterID];

        $data = array(
            //            "mode" => $this->uri->segment(3),
            "mode" => count($imagesReference) > 0 ? "viewResumeImages" : $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
            "items" => $lItems,
            "receiptLink" => $receiptLink,
            "signStr" => $signStr,
            "cabangData" => $arrCabang,

            "urutCounter" => $urutCounters,
            "underMaintenance" => underConstruction(),

            // ===== ======
            "detail_title" => "Pengajuan $transaksiJenisTopLabel",

            "itemLabels" => isset($itemLabelsTop) ? $itemLabelsTop : array(),
            "item2Labels" => isset($item2LabelsTop) ? $item2LabelsTop : array(),
            "item3Labels" => isset($item3LabelsTop) ? $item3LabelsTop : array(),

            "detail_main" => isset($main_reg) ? $main_reg : array(),
            "detail_items" => isset($items_reg) ? $items_reg : array(),
            "detil_item_rslt" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            // "detil_item_rslt_ref" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            "detil_item_rslt_label" => isset($tmpRowRslts_label) ? $tmpRowRslts_label : array(),
            "detil_item_rslt_label2" => isset($rowDetilRslt_label) ? $rowDetilRslt_label : array(),
            "detail2_items" => isset($items2_sum_reg) ? $items2_sum_reg : array(),
            "detail3_items" => isset($items3_sum_reg) ? $items3_sum_reg : array(),

            "detail_sumRows" => $sumRows,
            "detail2_sumRows" => isset($sumRows2) ? $sumRows2 : array(),
            "detail3_sumRows" => isset($sumRows3) ? $sumRows3 : array(),
            "noteEnabled" => $noteEnabled,
            "addRows" => $addFildRowFields,
            //--------------------
            "mainNotApprove" => isset($mainNotApprove) ? $mainNotApprove : array(),
            "itemsNotApprove" => isset($itemsNotApprove) ? $itemsNotApprove : array(),
            "detail_not_approve_title" => "Pengajuan $transaksiJenisTopLabel (tidak diapprove)",
            //--------------------
            "notes_final" => $notes_final,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "referenceImages" => $imagesReference,
            "elementStr" => isset($elementStr) ? $elementStr : "",
            "deliveryDetail" => isset($deliveryDetail) ? $deliveryDetail : array(),
            "shipment" => $shipment,
            "fullfillment" => $fullfillment,
            "customHeaderResume" => isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil'] : array(),
        );
        $this->load->view("transaksi", $data);
    }

    public function viewResume()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1[0]) > 0) {
            foreach ($tmp1[0] as $tmpKey => $tmpVal) {
                $rkey_field = $tmpKey;
                if ($rkey_field == "nomer") {
                    $rkey_field = "nomer_nolink";
                }
                //                $globalDatas[$rkey] = formatField_he_format($rkey_field, $rval, "", "");
                $globalVars[$tmpKey] = formatField_he_format($rkey_field, $tmpVal, "", "");
            }
        }
        $jenisMasterID = $tmp1[0]->id_master;
        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = ($tmp1[0]->step_number < 1) ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items", "main_elements", "transaksi_id");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;
        $configConnectEdit = isset($configUiJenis["connectToEdit"]) ? $configUiJenis["connectToEdit"] : NULL;
        $fullfil_id = 0;
        $jenisTr_110r = array("110r_1");
        $jenisTr_spd = "5822spd";
        $jenisTr_spd_link = array("5822spd_4");

        // membaca status edit di TOPID
        $tr = new MdlTransaksi();
        $tr->addFilter("id='$transaksiTopID'");
        $tmpTop = $tr->lookupAll()->result();
//        showLast_query("hitam");
        $arrTopEdit = array();
        $epEdit = array();
        if (sizeof($tmpTop) > 0) {
            if ((isset($tmpTop[0]->status_edit)) && ($tmpTop[0]->status_edit == 1)) {
                $arrTopEdit[$transaksiTopID] = array(
                    "status_edit" => $tmpTop[0]->status_edit,
                    "edit_dtime" => $tmpTop[0]->edit_dtime,
                    "edit_id" => $tmpTop[0]->edit_id,
                    "edit_name" => $tmpTop[0]->edit_name,
                );
                $epEdit[$tmpTop[0]->id] = $tmpTop[0]->id->master;
            }
            $fullfil_id = is_null($tmpTop[0]->fullfillment_id) ? 0 : $tmpTop[0]->fullfillment_id;
        }

        //region swap from registry
        $pairAddRegistry = array("main_elements");
        foreach ($pairAddRegistry as $regkey) {
            $pairRegistry[] = $regkey;
        }
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();
        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {

                        if ($eParams->$param == null) {
                            $eParams->$param = blobEncode(array());
                        }
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion

        $deliveryDetail = blobDecode($main_elements["deliveryDetails"]["contents"]);

        //region signatures
        //ambil data fullfill dulu bro biar bisa digabung sign
        if ($fullfil_id > 0) {
            $trf = new MdlTransaksi();
            $trf->setFilters(array());
            $trf->addFilter("id='$fullfil_id'");
            $tmp_master_fulfill = $trf->lookupMainTransaksi()->result();
            $masterID_fullfil = $tmp_master_fulfill[0]->id_master;
        }
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if ($fullfil_id > 0) {
                $trs->addFilter("transaksi_id in ($masterID,$masterID_fullfil)");
            }
            else {
                $trs->addFilter("transaksi_id='$masterID'");
            }
        }
        else {
            $trs->addFilter("transaksi_id='$masterID'");
        }


        //        $tmpSign = $trs->lookupSignatureResumeByMasterID()->result();//belum tak matiin
        $tmpSign = $trs->lookupSignaturesByMasterID($masterID)->result();
        //                 cekLime($this->db->last_query());
        //         arrPrint($tmpSign);
        $arrCounters = blobDecode($tmp1[0]->counters);
        $urutCounters = NULL;
        if (sizeof($arrCounters) > 0) {
            $key2 = $tmp1[0]->jenis . "|" . $tmp1[0]->cabang_id;
            $urutCounters = digit_5($arrCounters["stepCode|placeID"][$key2]);
        }
        if ($urutCounters != NULL) {
            $urutCounters = "-$urutCounters";
        }

        if (isset($this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames'])) {
            $resumeFields = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['selectFields'];
            $resumeTitle = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['title'];
            $resumeName = isset($tmp1[0]->$resumeFields) ? $tmp1[0]->$resumeFields : "";
        }
        else {
            $resumeTitle = "";
            $resumeName = "";
        }

        $arrSignName = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $ii => $row) {
                $signValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "label" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    //                    "title" => $row->step_name,
                    //                    "label" => $row->step_name,
                    "dtime" => $row->dtime,

                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,

                    "oleh" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                    "prev_id" => isset($row->prev_id) ? $row->prev_id : 0,
                    "link_id" => isset($row->transaksi_id) ? $row->transaksi_id : 0,
                );
                $arrSignName[$ii] = array(
                    "nama" => $row->oleh_nama,
                    "dtime" => $row->dtime,
                );
            }
        }


        $signStr = "";
        //endregion

        //-------------counter global------------
        $counterGlobalOverride = null;
        if (!empty($this->configUiJenis['counter_global'])) {
            $key = $this->configUiJenis['counter_global'];
            $counterGlobalOverride = isset($tmp1[0]->$key) ? $tmp1[0]->$key : null;
        }

        //entry points
        $trep = new MdlTransaksi();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
//        showLast_query("biru");
//        cekBiru(count($tmpEP));
        $epValues = array();
        $rowStepNumber = array();
        $epDetail = array();
        $terimaAr = array();
        $trOrigIDs = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
                $trOrigIDs[] = $row->link_id;
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobalOld = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterGlobal = ($counterGlobalOverride !== null) ? $counterGlobalOverride : $counterGlobalOld;
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $id_master = $row->id_master;
                if (empty($row->deskripsi)) {
                    if (!isset($rowStepNumber["referenceID__" . $row->step_number])) {
                        $rowStepNumber["referenceID__" . $row->step_number] = array(
                            "id" => $row->id,
                            "link_id" => $row->link_id,
                            "step_num" => $row->step_number,

                        );
                    }
                    $kkey = 'sign_' . $row->id . '_' . $row->step_number;
                    $epValues[$kkey] = array(
                        "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => $row->jenis_label,
                        "dtime" => $row->dtime,
                        "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->oleh_nama,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
                        "global_counter" => $cGlobals,
                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
//                        "date_faktur" => isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL,
//                        "nomer_faktur" => isset($row->efaktur) ? $row->efaktur : NULL,
//                        "gunggungan_mode" => isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0,
//
                        "kkey" => $kkey,
                    );
                    if ($row->efaktur != 0) {
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["date_faktur"] = isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["nomer_faktur"] = isset($row->efaktur) ? $row->efaktur : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["gunggungan_mode"] = isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0;
                    }
                }
                else {
                    $tmpReg = $tr->lookupDataRegistriesByMasterID($row->link_id)->result();
                    $main = blobDecode($tmpReg[0]->main);
                    if ($jenis == "5822spd") {
                        $main["referenceID__4"] = $main["referenceID"];
                    }
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        foreach ($main as $m_key => $mval) {
                            if (isset($rowStepNumber[$m_key])) {
                                cekMerah($m_key . "::" . $mval . " ::*" . $rowStepNumber[$m_key]["id"]);
//                                switch ($jenis) {
//                                    case "9112":
//                                    case "9111":
//                                        break;
//                                    default:
//                                        break;
//                                }
//
                                $epValues['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = $row->deskripsi;
                                $epDetail['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                );
                            }
                            else {
                                //                            cekBiru("lolosss");
                            }
                        }
                    }
                    else {
                        switch ($main["jenisTr"]) {
                            case "9912":
                            case "9911":
//                                cekUngu($main["referenceStepNumber"]);
                                $selectedTrid = $main["transaksi"] + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["referenceStepNumber"]]["status"] = array(
                                    "oleh_nama" => $main["olehName"],
                                    "dtime" => $main["dtime"],
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                $kkey = 'sign_' . $row->id . '_' . $row->step_number . '_r';
                                $epValues[$kkey] = array(
                                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                                    "cabang_nama" => $row->cabang_nama,
                                    "label" => $row->jenis_label,
                                    "dtime" => $main["dtime"],
                                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                                    "nomer_orig" => $row->nomer,
                                    "oleh" => $main["olehName"],
                                    "caption_department" => "text",
                                    "deskripsi" => $row->deskripsi,
                                    "link_id" => $row->link_id,
                                    "global_counter" => $cGlobals,
                                    "master_id_global_counter" => $cMasterIDGlobals,
                                    "jenis" => $jenis,
                                    "kkey" => $kkey,
                                );
                                break;
                            default:
                                $selectedTrid = isset($main["transaksi"]) ? $main["transaksi"] : $main["currentID"];
                                $selectedTrid = $selectedTrid + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["stepNumber"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                $kkey = 'sign_' . $row->id . '_' . $row->step_number . '_r';
                                $epValues[$kkey] = array(
                                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                                    "cabang_nama" => $row->cabang_nama,
                                    "label" => $row->jenis_label,
                                    "dtime" => $row->dtime,
                                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                                    "nomer_orig" => $row->nomer,
                                    "oleh" => $row->oleh_nama,
                                    "caption_department" => "text",
                                    "deskripsi" => $row->deskripsi,
                                    "link_id" => $row->link_id,
                                    "global_counter" => $cGlobals,
                                    "master_id_global_counter" => $cMasterIDGlobals,
                                    "jenis" => $jenis,
                                    "kkey" => $kkey,
                                );
                                break;
                        }
                    }
                }
                if (in_array($row->jenis, $jenisTr_spd_link)) {
                    $terimaAr[$row->link_id] = $row->link_id;
                }
                if (in_array($row->jenis, $jenisTr_110r)) {
                    $inputFaktur[$row->link_id] = $row->link_id;
                }
            }
        }

        //------LINK KE AR
        if (sizeof($terimaAr) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis='749'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $terimaAr) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
            showLast_query("pink");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $kkey = 'sign_' . $row->transaksi_id . '_' . $row->step_number;
                $epValues[$kkey] = array(
                    "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                    "kkey" => $kkey,
                );
            }
        }
        //------
        if (sizeof($inputFaktur) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis_master='110'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $inputFaktur) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
//            showLast_query("kuning");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $kkey = 'sign_' . $row->transaksi_id . '_' . $row->step_number;
                $epValues[$kkey] = array(
                    "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                    "date_faktur" => $row->efaktur_dtime,
                    "nomer_faktur" => $row->efaktur,
                    "gunggungan_mode" => $row->gunggungan_mode,
                    "kkey" => $kkey,
                );
                if ($row->trash_4 == 1) {
                    $kkey_old = 'sign_' . $row->transaksi_id . '_' . $row->step_number;
                    $kkey = 'sign_' . $row->transaksi_id . '_' . $row->step_number . '_r';
                    if (!array_key_exists($kkey_old, $epValues)) {
                    }
                    $epValues[$kkey] = array(
                        "title" => isset(loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . loadConfigModulJenis_he_misc($row->jenis_master, "coTransaksiUi")['steps'][$row->step_number]['label'] : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => "Pembatalan/Reject " . $row->jenis_label,
//                        "dtime" => $row->dtime,
                        "dtime" => $row->cancel_dtime,
                        "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                        "nomer_reject" => formatField_he_format("nomer_nolink", isset($row->cancel_transaksi_nomer) ? $row->cancel_transaksi_nomer : NULL),
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->cancel_name,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
//                        "global_counter" => $cGlobals,
//                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
                        "date_faktur" => $row->efaktur_dtime,
                        "nomer_faktur" => $row->efaktur,
                        "gunggungan_mode" => $row->gunggungan_mode,
                        "kkey" => $kkey,
                    );
                }
            }
        }

        //------LINK KE PENERIMAAN PENJUALAN TUNAI (KAS/FINANCE) VIA TRANSAKSI_DATA - UNIFIED AUDIT TRAIL (ISO 9001:2015 & ISO 27001)
        /* Role: Software Engineer Agent & ISO Software Auditor */
        $searchIDs = $trOrigIDs;
//        $searchIDs = array();
//        if ($transaksiID > 0) { $searchIDs[] = $transaksiID; }
//        if ($masterID > 0) { $searchIDs[] = $masterID; }
//        if ($transaksiTopID > 0) { $searchIDs[] = $transaksiTopID; }

        $nomerTopCheck = "";
        if (isset($tmp1[0]->nomer_top) && strlen(trim($tmp1[0]->nomer_top)) > 0) {
            $nomerTopCheck = trim($tmp1[0]->nomer_top);
        }
        elseif (isset($tmp1[0]->nomer) && strlen(trim($tmp1[0]->nomer)) > 0) {
            $nomerTopCheck = trim($tmp1[0]->nomer);
        }
        else {
            $nomerTopCheck = trim($no);
        }

        $exNo = explode(".", $nomerTopCheck);
        $nomerCore = "";
        if (count($exNo) >= 4) {
            $nomerCore = $exNo[1] . "." . $exNo[2] . "." . $exNo[3];
        }
        elseif (count($exNo) >= 3) {
            $nomerCore = $exNo[1] . "." . $exNo[2];
        }
        elseif (count($exNo) >= 2) {
            $nomerCore = $exNo[1];
        }

        $this->db->select("t.id, t.nomer, t.dtime, t.oleh_nama, t.cabang_nama, t.jenis, t.jenis_label, t.counters, t.deskripsi, t.jenis_master");
        $this->db->from("transaksi t");
        $this->db->join("transaksi_data td", "td.transaksi_id = t.id", "left");
        $this->db->group_start();
        if (count($searchIDs) > 0) {
            $this->db->where_in("td.produk_id", $searchIDs);
            $this->db->or_where_in("td.transaksi_id", $searchIDs);
            $this->db->or_where_in("t.id_top", $searchIDs);
            $this->db->or_where_in("t.id_master", $searchIDs);
        }
        if (strlen($nomerTopCheck) > 0) {
            $this->db->or_where("t.nomer_top", $nomerTopCheck);
            $this->db->or_where("t.nomer", $nomerTopCheck);
        }
        if (strlen($nomerCore) > 0) {
            $this->db->or_like("t.nomers_prev", $nomerCore);
            $this->db->or_like("t.nomer_top", $nomerCore);
            $this->db->or_like("t.nomer", $nomerCore);
        }
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("t.jenis", "4464");
        $this->db->or_like("t.jenis", "4464_");
        $this->db->or_where("t.jenis_master", "4464");
        $this->db->group_end();
        $this->db->where("t.trash", "0");
        $this->db->where("t.link_id", "0");
        $this->db->group_by("t.id");

        $queryPayment = $this->db->get();
        showLast_query("biru");
        if ($queryPayment && $queryPayment->num_rows() > 0) {
            foreach ($queryPayment->result() as $pRow) {
                $kkeyPay = 'sign_pay_' . $pRow->id;
                $counterDecode = blobDecode($pRow->counters);
                $cGlobals = "";
                if (is_array($counterDecode) && isset($counterDecode['stepCode|placeID'])) {
                    $firstCounter = reset($counterDecode['stepCode|placeID']);
                    $cGlobals = digit_5($firstCounter);
                }
                $epValues[$kkeyPay] = array(
                    "title" => "Penerimaan Penjualan Tunai",
                    "cabang_nama" => $pRow->cabang_nama,
                    "label" => "Penerimaan Penjualan Tunai",
                    "dtime" => $pRow->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $pRow->nomer),
                    "nomer_orig" => $pRow->nomer,
                    "oleh" => $pRow->oleh_nama,
                    "caption_department" => "Finance / Kasir",
                    "deskripsi" => $pRow->deskripsi,
                    "link_id" => $pRow->id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cGlobals,
                    "jenis" => "4464",
                    "kkey" => $kkeyPay,
                );
            }
        }

//arrPrintCyan($epValues);

        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;
        $steps = $configUiJenis;
        foreach ($steps as $step => $keis) {
            $autoNextStep = isset($keis['autoNextStep']) ? $keis['autoNextStep'] : 0;
        }
//arrPrintCyan($signArray);
        $pakai_ini_urut_keyid = 0;
        if ($pakai_ini_urut_keyid == 1) {
            $signArrayKeys = array_keys($signArray);
            foreach ($signArrayKeys as $keyy) {
                $keyy_ex = explode("_", $keyy);
                $signArrayKeysTrid[$keyy_ex[1]] = $keyy;
            }
        }
        else {
            foreach ($signArray as $xx) {
                $newKey = $xx["dtime"] . "_" . $xx["kkey"];
//                $signArrayKeysTrid[$xx["dtime"]] = $xx;
                $signArrayKeysTrid[$newKey] = $xx;
            }
        }

        ksort($signArrayKeysTrid);
//        arrPrintCyan($signArray);
        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel table-responsive'>";
            $signStr .= "<h4 class='text-blue'><span class='fa fa-sign-in'></span> entry points </h4>";

            $signStr .= "<h5 class='text-blue'><span class='fa fa-tasks'></span> $resumeTitle  $resumeName</h5>";
            $signStr .= "<table class='table table-condensed table-striped table-hover-color-red dataTable'>";
            $signStr .= "<tr bgcolor='#f0f0f0'>";
            $signStr .= "<td class='text-muted'>date</td>";
            $signStr .= "<td class='text-muted'>title</td>";
            $signStr .= "<td class='text-muted'>branch</td>";
            $signStr .= "<td class='text-muted'>transaction number</td>";
            $signStr .= "<td class='text-muted'>person</td>";
            $signStr .= "<td class='text-muted'>status</td>";
            $signStr .= "<td class='text-muted'>tool</td>";
            $nom = 0;
            foreach ($signArrayKeysTrid as $k_ii => $v_ii) {
                $nom++;
                if ($pakai_ini_urut_keyid == 1) {
                    $k = $v_ii;
                    $sSpec = $signArray[$v_ii];
                    $jenis = $sSpec['jenis'];
                    $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                    $nomerOrig = $sSpec['nomer_orig'];
                    $nomerOrigEx = explode("_", $nomerOrig);
                    $nomerOrigNota = $nomerOrigEx[0];
                    $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                    $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                    $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;
                    $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                    $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";
                    $edit_description = "";
                    if (isset($arrTopEdit[$sSpec['link_id']])) {
                        $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                        $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                        $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                    }
                    $rejection_label = "";
                    $reject_description = "";
                    if (isset($epDetail[$k])) {
                        $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                        $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                        $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                        $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                    }
                    //-----
                    $date_faktur = "";
                    $nomer_faktur = "";
                    $gunggungan_mode = 0;
                    $gunggungan_mode_bgcolor = "";
                    if (isset($sSpec["date_faktur"])) {
                        $date_faktur = $sSpec["date_faktur"];
                    }
                    if (isset($sSpec["nomer_faktur"])) {
                        $nomer_faktur = $sSpec["nomer_faktur"];
                    }
                    if (isset($sSpec["gunggungan_mode"])) {
                        $gunggungan_mode = $sSpec["gunggungan_mode"];
                        $gunggungan_mode_bgcolor = "yellow";
                    }
                }
                else {
                    $k = $v_ii;
                    $sSpec = $v_ii;
                    $jenis = $sSpec['jenis'];
                    $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                    $nomerOrig = $sSpec['nomer_orig'];
                    $nomerOrigEx = explode("_", $nomerOrig);
                    $nomerOrigNota = $nomerOrigEx[0];
                    $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                    $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                    $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;
                    $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                    $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";
                    $edit_description = "";
                    if (isset($arrTopEdit[$sSpec['link_id']])) {
                        $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                        $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                        $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                    }
                    $rejection_label = "";
                    $reject_description = "";
                    if (isset($epDetail[$k])) {
                        $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                        $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                        $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                        $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                    }
                    //-----
                    $nomer_reject = "";
                    $date_faktur = "";
                    $nomer_faktur = "";
                    $gunggungan_mode = 0;
                    $gunggungan_mode_bgcolor = "";
                    if (isset($sSpec["nomer_reject"])) {
                        $nomer_reject = $sSpec["nomer_reject"];
                    }
                    if (isset($sSpec["date_faktur"])) {
                        $date_faktur = $sSpec["date_faktur"];
                    }
                    if (isset($sSpec["nomer_faktur"])) {
                        $nomer_faktur = $sSpec["nomer_faktur"];
                    }
                    if (isset($sSpec["gunggungan_mode"])) {
                        $gunggungan_mode = $sSpec["gunggungan_mode"];
                        $gunggungan_mode_bgcolor = "yellow";
                    }
                }


                $signStr .= "<tr style='background-color:$gunggungan_mode_bgcolor;'>";
                $signStr .= "<td>" . formatField_he_format("date_time", $sSpec['dtime']);
                $signStr .= "</td>";
                $signStr .= "<td>" . ($sSpec['label']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                $signStr .= "<td>" . (formatField_he_format("nomer_nolink", $nomerViewFinal)) . $signDeskripsi . "";
                if ($edit_description != "") {
                    $signStr .= "<br>$edit_description";
                }
                if ($date_faktur != "") {
                    $signStr .= "<br>tanggal faktur: $date_faktur";
                }
                if ($nomer_faktur != "") {
                    $signStr .= "<br>Nomer faktur: $nomer_faktur";
                }
                if ($nomer_reject != "") {
                    $signStr .= "<br>Nomer Pembatalan/Reject: $nomer_reject";
                }

                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $oleh_nama . "";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $status . "<br>" . $reject_description . "";
                $signStr .= "</td>";

                $signStr .= "<td title='" . $sSpec['label'] . "'>";
//                if($nom == 1){
//                    $signStr .= "-";
//                }
//                else{
//                }
                $signStr .= formatField_he_format("print_label", $nomerOrigNota, $this->jenisTr, MODUL_PATH);

                $signStr .= "</td>";

                $signStr .= "</tr>";
            }
            $signStr .= "</table class='table table-condensed'>";

            $signStr .= "</div class='panel'>";
        }


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $allowedView = $this->config->item('userGroup_jurnal') != null ? $this->config->item('userGroup_jurnal') : array();
        $rekeningAlias = $this->config->item('accountAlias') != null ? $this->config->item('accountAlias') : array();

        $lItems = array();
        $headers = array();
        $arrCabang = array();
        if (array_intersect($allowedView, $mems)) {
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Mdls/MdlCabang");

            $cb = New MdlCabang();
            $tmpCb = $cb->lookupAll()->result();
            $arrCabang = array();
            if (sizeof($tmpCb) > 0) {
                foreach ($tmpCb as $spec) {
                    $arrCabang[$spec->id] = $spec->nama;
                }
            }

            $j = new ComJurnal();
            $j->addFilter("transaksi_no='$no'");
            $j->addFilter("status='1'");
            $j->addFilter("trash='0'");
            if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

            }
            else {
                // dimatikan dulu untuk checking jurnal lengkapnya...........
                //                $j->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            //            $j->setSortBy(array("kolom" => "urut", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            if (sizeof($tmp) > 0) {
                /* ---------------------------------------
                 * array link untuk mengarahkan ke halaman mutasi yg dikendaki
                 * -----------------------------------*/

                //region Description
                $mutasiLinks = array();
                foreach ($accountChildsLinks as $rekNama => $rekLink) {
                    $mutasiLinks[$rekNama] = $rekLink . "/" . $main['pihakID'] . "?o=" . $main['cabangID'] . "&trID=$transaksiID";
                }
                //endregion

                foreach ($tmp as $row) {
                    $d_start = backCustomDate("30", $row->dtime);
                    $d_stop = $row->dtime;
                    if ($row->j_jenis == "items") {
                        $blob_ext = blobEncode($row->rekening);
                        //                        Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/1076?o=-1&ext2_id=&main_ext2_id=
                        $defLink = "Ledger/viewMoveDetails/RekeningPembantuProduk/" . $row->rekening_main . "/" . $row->extern_id . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop&blob_ext=$blob_ext";
                    }
                    else {
                        $defLink = "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                        //                    $defLink = "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                    }

                    //                    $rekening_alias = isset($rekeningAlias[$row->rekening]) ? $rekeningAlias[$row->rekening] : $row->rekening;
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        //$strLink = key_exists($row->rekening, $mutasiLinks) ? $mutasiLinks[$row->rekening] : $defLink;
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }

                ksort($lItems);
            }


        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $mobMode = $isMob == true ? "1" : "0";

        $printSettings = isset($this->configLayout[$jenisMasterTrans]['allowPrint']['1']) ? $this->configLayout[$jenisMasterTrans]['allowPrint']['1'] : array();
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Transaksi/viewReceipt/";

        $valasType = (isset($main['currencyDetails__label'])) ? "&type=" . blobEncode($main['currencyDetails__label']) : "";
        $valasFKali = "&f=" . blobEncode(1);

        if ($step_number == 1000) {
            $receiptLink = "";
        }
        else {
            if (sizeof($tmpSign) > 0) {
                if (isset($printSettings['size'])) {
                    switch ($printSettings['size']) {
                        case "normal":
                            $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=1$valasType$valasFKali');";
                            break;
                        case "small":
                            $receiptLink = "top.popSmall('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=2');";
                            break;
                        default:
                            //                                cekkuning("size: UNKNOWN");
                            break;
                    }
                }
                else {
                    $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=3$valasType$valasFKali');";
                }
            }
            else {
                $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?st=4');";
            }
        }


        // ============== TAMBAHAN ============== ============== ==============
        $itemLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields'][1] : array();
        $itemLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1] : array();
        $itemLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1] : array();
        $itemNumLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1] : array();
        $itemNumLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1] : array();
        $itemNumLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1] : array();
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1];
        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;


        $arrCons = array();
        $headerScheme = array();

        if ($showScheme) {

            $awal_pinjaman = isset($main['awal_pinjaman']) ? $main['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($main['jatuh_tempo']) ? $main['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($main['harga']) ? $main['harga'] : 0;
            $rate_bunga = isset($main['persen_bunga']) ? $main['persen_bunga'] : 0;

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

        $itemLabelsTop = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $item2LabelsTop = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $item3LabelsTop = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemLabelBlacklist = array(
            "stok_center",
            "stok",
        );

        foreach ($itemLabelsTop as $key => $val) {
            if (in_array($key, $itemLabelBlacklist)) {
                unset($itemLabelsTop[$key]);
            }
        }
        if (isset($this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount']) && $this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount'][1] == true) {
            unset($itemLabelsTop['subtotal']);
            unset($item2LabelsTop['subtotal']);
            unset($item3LabelsTop['subtotal']);
        }
        $blacklistView = $this->config->item('userGroup_blacklist') != null ? $this->config->item('userGroup_blacklist') : array();
        $addFildRowFields = array();


        $allowedViewTop = false;
        foreach ($mems as $memsVal) {
            if (!in_array($memsVal, $blacklistView)) {
                $allowedViewTop = true;
            }
        }

        if ($allowedViewTop == true) {

            //untuk melihat data order/spo nya
            $m = new MdlTransaksi();
            $m->setFilters(array());
            $tmpReg = $m->lookupDataRegistriesByMasterID($transaksiTopID)->result();
            $regfields = $m->getFields()["dataRegistry"];
            $addRow = isset($this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes']) ? $this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes'] : array();
            $itemNotApprove = isset($this->configUi[$jenisMasterTrans]['closedRequest'][1]) ? $this->configUi[$jenisMasterTrans]['closedRequest'][1] : true;
            $detailCore = isset($this->configCore[$jenisMasterTrans]['valueGates']['detail']) ? $this->configCore[$jenisMasterTrans]['valueGates']['detail'] : array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regfields as $params) {
                        // cekHitam($params);
                        if (isset($row->$params) && ($params != "transaksi_id")) {

                            $var = $params . "_reg";
                            $$var = unserialize(base64_decode($row->$params));
                        }

                    }
                    // $$var = unserialize(base64_decode($row->values));
                }
                if (sizeof($addRow) > 0) {
                    foreach ($addRow as $gateKey => $mpFields) {

                        if (isset($mpFields['hideRow']) && $mpFields['hideRow'] == true) {

                        }
                        else {
                            $addFildRowFields[$gateKey] = $mpFields['label'];
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    //                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID)->result();
                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID);

                    $notApprove = array();
                    foreach ($dTmp as $spec) {
                        if ((isset($spec->valid_qty_no_approve)) && ($spec->valid_qty_no_approve > 0)) {
                            $notApprove[$spec->produk_id] = $spec->valid_qty_no_approve;
                        }
                    }
                    $mainNotApprove = array();
                    $itemsNotApprove = array();
                    if (sizeof($notApprove) > 0) {
                        $itemsNotApprove = $items_reg;
                        foreach ($notApprove as $pid => $pqty_not_approve) {
                            $itemsNotApprove[$pid]['qty'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['jml'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['subtotal'] = $pqty_not_approve * $itemsNotApprove[$pid]['harga'];
                            if (sizeof($detailCore) > 0) {
                                foreach ($detailCore as $key_c => $val_c) {
                                    $itemsNotApprove[$pid]['sub_' . $key_c] = $pqty_not_approve * $itemsNotApprove[$pid][$key_c];
                                    if (!isset($mainNotApprove[$key_c])) {
                                        $mainNotApprove[$key_c] = 0;
                                    }
                                    $mainNotApprove[$key_c] += ($pqty_not_approve * $itemsNotApprove[$pid][$key_c]);
                                }
                            }
                            if (!isset($mainNotApprove['harga'])) {
                                $mainNotApprove['harga'] = 0;
                            }
                            $mainNotApprove['harga'] += ($pqty_not_approve * $itemsNotApprove[$pid]['harga']);
                        }
                    }
                }
            }

            //tambahan jika ada result item
            // arrPrint($main_reg);
            if (sizeof($rsltItems_reg) > 0) {
                //region reference detil
                $m->setFilters(array());
                $tmpRegRef = $m->lookupDataRegistriesByMasterID($main_reg['referenceID']);
                foreach ($tmpRegRef as $refRow) {
                    foreach ($regfields as $params) {
                        if (isset($refRow->$params)) {
                            $varR = $params . "_ref";
                            $$varR = unserialize(base64_decode($refRow->$params));
                        }
                    }
                }

                $tmpRowRslts_ref = array();
                $totalNilaiRef = 0;
                if (sizeof($rsltItems_ref) > 0) {
                    foreach ($rsltItems_ref as $rowRefID => $rowref_data) {
                        // $hpp = number_format($rowref_data['hpp'],0);
                        $hpp = $rowref_data['hpp'];
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] = 0;

                        }
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] = 0;
                        }

                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] += $rowref_data['qty'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] += $rowref_data['sub_hpp'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['harga'] = $rowref_data['hpp'];
                        $totalNilaiRef += $rowref_data['sub_hpp'];
                    }
                    $rowDetilRslt_label[$main_reg['pihakExternID']] = array(
                        "harga" => "harga",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                    );
                }
                //endregion

                //region current detil
                $tmpRowRslts = array();
                $tmpRowRsltsnilai = 0;
                foreach ($rsltItems_reg as $rowFifo => $fifoData) {
                    // $hpp_0 = number_format($fifoData['hpp'],0);
                    $hpp_0 = $fifoData['hpp'];
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] = 0;
                    }
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] = 0;
                    }
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] += $fifoData['qty'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] += $fifoData['sub_hpp'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['harga'] = $fifoData['hpp'];
                    $tmpRowRsltsnilai += $fifoData['sub_hpp'];
                }
                $tmpRowRslts_label = array(
                    "nama" => "produk",
                    "qty" => "qty",
                    $main_reg['pihakExternID'] => $main_reg['pihakExternName'],
                    $main_reg['jenisTr'] => $main_reg['jenisTrName'],
                );
                $rowDetilRslt_label[$main_reg['jenisTr']] = array(
                    "harga" => "harga",
                    "qty" => "qty",
                    "subtotal" => "subtotal",
                );

                //endregion
            }
        }

        $transaksiJenisTopLabel = isset($main_reg['jenisTrName']) ? $main_reg['jenisTrName'] : "";
        //-------------------------------------
        if (($configNotes != NULL) && ($globalVars['trash_4'] == 1)) {
            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {
                    $configNotes = str_replace("{" . $key . "}", $val, $configNotes);
                }
            }
            $notes_final = $configNotes;
        }
        else {
            $notes_final = NULL;
        }


        //region packinglist/serial
        $shoppingCartSubDetailFields = isset($this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3] : array();
        $shopingCartPairFieldResume = isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume'] : array();
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            cekHere(":: $jenisMasterID ::");
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$jenisMasterID'");
            $tr->addFilter("jenis='5822pkd'");
            $tr->addFilter("trash_4='0'");
            $tmp0 = $tr->lookupAll()->result();
            $ids_transaksi = array();
            foreach ($tmp0 as $tmp0_x) {
                $ids_transaksi[] = $tmp0_x->id;
            }

            if (count($ids_transaksi) > 0) {

                cekHitam($this->db->last_query());
                //            matiHere(__LINE__);
                //            $trid_pkd = $tmp0[0]->id;
                $tr->setFilters(array());
                //            $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
                //            $tr->addFilter("transaksi_id='$trid_pkd'");
                $tr->addFilter("transaksi_id in (" . implode(',', $ids_transaksi) . ")");
                $tmpReg = $tr->lookupDataRegistries()->result();
                //                arrPrint($pairRegistry);
                //                cekBiru($this->db->last_query());
                $dataRegistry = array();
                if (sizeof($tmpReg) > 0) {
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $eParams) {

                            $dataRegistry[] = blobDecode($eParams->items);
                            foreach ($pairRegistry as $param) {
                                $dataRegistry[$param][] = blobDecode($eParams->$param);
                                //                            switch ($param){
                                //                                case "main":
                                //                                    $main[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items":
                                //                                    $items[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items3_sum":
                                //                                    $items3_sum[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                            }
                                //                            unset($param->transaksi_id);
                                //                            if ($eParams->$param == null) {
                                //                                $eParams->$param = blobEncode(array());
                                //                            }
                                ////                            $registryData[]
                                //                            $$param = blobDecode($eParams->$param);
                            }
                        }
                    }
                }

                $arrSubDetailDataKolom = array();
                foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                    $gerbang = $kolomSpec["gate"];
                    $source = $kolomSpec["source"];
                    $source_new = $dataRegistry[$source];
                    $arrHasil = array();
                    foreach ($source_new as $ix => $sxdata) {

                        foreach ($sxdata as $sdata) {
                            //                        arrPrint($sdata);
                            //                        matiHEre(__LINE__);
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


                    }
                    $arrSubDetailDataKolom[$kolom] = $arrHasil;
                    //                arrPrint($arrSubDetailDataKolom);
                }
                $fullfillment = array();
                if (count($shopingCartPairFieldResume) > 0) {
                    if (isset($dataRegistry["items"])) {
                        foreach ($dataRegistry["items"] as $ixx => $data) {
                            foreach ($data as $pid => $data_0) {
                                foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                    if (!isset($shipment[$pid][$field_shipment])) {
                                        $shipment[$pid][$field_shipment] = 0;
                                    }
                                    $shipment[$pid][$field_shipment] += $data_0[$field_shipment];
                                }
                            }

                        }
                    }
                }

                if (count($fullfil_id) > 0) {
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id='$fullfil_id'");
                    $tmpRegFulfil = $tr->lookupDataRegistries()->result();
                    cekMerah($this->db->last_query());
                    $itemsFulfilmentTmp = blobDecode($tmpRegFulfil[0]->items);
                    if (count($shopingCartPairFieldResume) > 0) {
                        //                    if(isset($dataRegistry["items"])){
                        foreach ($itemsFulfilmentTmp as $pid => $data_0) {
                            //                            foreach ($data as $pid =>$data_0){
                            foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                if (!isset($shipment[$pid][$field_shipment])) {
                                    $fullfillment[$pid][$field_shipment] = 0;
                                }
                                $fullfillment[$pid][$field_shipment] += $data_0[$field_shipment];
                            }
                            //                            }

                        }
                        //                    }
                    }
                }
            }


        }
        else {
            //            cekMerah("::: KOSONG :::");
        }

        //-------------------------------------
        $imagesReference = getTransaksiImageReference($jenisMasterID)[$jenisMasterID];

        $data = array(
            //            "mode" => $this->uri->segment(3),
            "mode" => count($imagesReference) > 0 ? "viewResumeImages" : $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
            "items" => $lItems,
            "receiptLink" => $receiptLink,
            "signStr" => $signStr,
            "cabangData" => $arrCabang,

            "urutCounter" => $urutCounters,
            "underMaintenance" => underConstruction(),

            // ===== ======
            "detail_title" => "Pengajuan $transaksiJenisTopLabel",

            "itemLabels" => isset($itemLabelsTop) ? $itemLabelsTop : array(),
            "item2Labels" => isset($item2LabelsTop) ? $item2LabelsTop : array(),
            "item3Labels" => isset($item3LabelsTop) ? $item3LabelsTop : array(),

            "detail_main" => isset($main_reg) ? $main_reg : array(),
            "detail_items" => isset($items_reg) ? $items_reg : array(),
            "detil_item_rslt" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            // "detil_item_rslt_ref" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            "detil_item_rslt_label" => isset($tmpRowRslts_label) ? $tmpRowRslts_label : array(),
            "detil_item_rslt_label2" => isset($rowDetilRslt_label) ? $rowDetilRslt_label : array(),
            "detail2_items" => isset($items2_sum_reg) ? $items2_sum_reg : array(),
            "detail3_items" => isset($items3_sum_reg) ? $items3_sum_reg : array(),

            "detail_sumRows" => $sumRows,
            "detail2_sumRows" => isset($sumRows2) ? $sumRows2 : array(),
            "detail3_sumRows" => isset($sumRows3) ? $sumRows3 : array(),
            "noteEnabled" => $noteEnabled,
            "addRows" => $addFildRowFields,
            //--------------------
            "mainNotApprove" => isset($mainNotApprove) ? $mainNotApprove : array(),
            "itemsNotApprove" => isset($itemsNotApprove) ? $itemsNotApprove : array(),
            "detail_not_approve_title" => "Pengajuan $transaksiJenisTopLabel (tidak diapprove)",
            //--------------------
            "notes_final" => $notes_final,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "referenceImages" => $imagesReference,
            "elementStr" => isset($elementStr) ? $elementStr : "",
            "deliveryDetail" => isset($deliveryDetail) ? $deliveryDetail : array(),
            "shipment" => $shipment,
            "fullfillment" => $fullfillment,
            "customHeaderResume" => isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil'] : array(),
        );
        $this->load->view("transaksi", $data);
    }

    public function viewResumeNEW()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1[0]) > 0) {
            foreach ($tmp1[0] as $tmpKey => $tmpVal) {
                $rkey_field = $tmpKey;
                if ($rkey_field == "nomer") {
                    $rkey_field = "nomer_nolink";
                }
                //                $globalDatas[$rkey] = formatField_he_format($rkey_field, $rval, "", "");
                $globalVars[$tmpKey] = formatField_he_format($rkey_field, $tmpVal, "", "");
            }
        }
        $jenisMasterID = $tmp1[0]->id_master;
        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = $tmp1[0]->step_number < 1 ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items", "main_elements", "transaksi_id");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;
        $configConnectEdit = isset($configUiJenis["connectToEdit"]) ? $configUiJenis["connectToEdit"] : NULL;
        $fullfil_id = 0;
        $jenisTr_110r = array("110r_1");
        $jenisTr_spd = "5822spd";
        $jenisTr_spd_link = array("5822spd_4");

        // membaca status edit di TOPID
        $tr = new MdlTransaksi();
        $tr->addFilter("id='$transaksiTopID'");
        $tmpTop = $tr->lookupAll()->result();
        $arrTopEdit = array();
        $epEdit = array();
        if (sizeof($tmpTop) > 0) {
            if ((isset($tmpTop[0]->status_edit)) && ($tmpTop[0]->status_edit == 1)) {
                $arrTopEdit[$transaksiTopID] = array(
                    "status_edit" => $tmpTop[0]->status_edit,
                    "edit_dtime" => $tmpTop[0]->edit_dtime,
                    "edit_id" => $tmpTop[0]->edit_id,
                    "edit_name" => $tmpTop[0]->edit_name,
                );
                $epEdit[$tmpTop[0]->id] = $tmpTop[0]->id->master;
            }
            $fullfil_id = is_null($tmpTop[0]->fullfillment_id) ? 0 : $tmpTop[0]->fullfillment_id;
        }

        //region swap from registry
        $pairAddRegistry = array("main_elements");
        foreach ($pairAddRegistry as $regkey) {
            $pairRegistry[] = $regkey;
        }
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();
        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {

                        if ($eParams->$param == null) {
                            $eParams->$param = blobEncode(array());
                        }
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion

        $deliveryDetail = blobDecode($main_elements["deliveryDetails"]["contents"]);

        //region signatures
        //ambil data fullfill dulu bro biar bisa digabung sign
        if ($fullfil_id > 0) {
            $trf = new MdlTransaksi();
            $trf->setFilters(array());
            $trf->addFilter("id='$fullfil_id'");
            $tmp_master_fulfill = $trf->lookupMainTransaksi()->result();
            $masterID_fullfil = $tmp_master_fulfill[0]->id_master;
        }
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $masterID = isset($tmp1[0]->id_master) ? $tmp1[0]->id_master : 0;
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if ($fullfil_id > 0) {
                $trs->addFilter("transaksi_id in ($masterID,$masterID_fullfil)");
            }
            else {
                $trs->addFilter("transaksi_id='$masterID'");
            }
        }
        else {
            $trs->addFilter("transaksi_id='$masterID'");
        }


        //        $tmpSign = $trs->lookupSignatureResumeByMasterID()->result();//belum tak matiin
        $tmpSign = $trs->lookupSignaturesByMasterID($masterID)->result();
        //                 cekLime($this->db->last_query());
        //         arrPrint($tmpSign);
        $arrCounters = blobDecode($tmp1[0]->counters);
        $urutCounters = NULL;
        if (sizeof($arrCounters) > 0) {
            $key2 = $tmp1[0]->jenis . "|" . $tmp1[0]->cabang_id;
            $urutCounters = digit_5($arrCounters["stepCode|placeID"][$key2]);
        }
        if ($urutCounters != NULL) {
            $urutCounters = "-$urutCounters";
        }

        if (isset($this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames'])) {
            $resumeFields = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['selectFields'];
            $resumeTitle = $this->configUi[$tmp1[0]->jenis_master]['resumeFieldNames']['title'];
            $resumeName = isset($tmp1[0]->$resumeFields) ? $tmp1[0]->$resumeFields : "";
        }
        else {
            $resumeTitle = "";
            $resumeName = "";
        }

        $arrSignName = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $ii => $row) {
                $signValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "label" => isset($this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$tmp1[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    //                    "title" => $row->step_name,
                    //                    "label" => $row->step_name,
                    "dtime" => $row->dtime,

                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,

                    "oleh" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                    "prev_id" => isset($row->prev_id) ? $row->prev_id : 0,
                    "link_id" => isset($row->transaksi_id) ? $row->transaksi_id : 0,
                );
                $arrSignName[$ii] = array(
                    "nama" => $row->oleh_nama,
                    "dtime" => $row->dtime,
                );
            }
        }


        $signStr = "";
        //endregion

        //-------------counter global------------
        $counterGlobalOverride = null;
        if (!empty($this->configUiJenis['counter_global'])) {
            $key = $this->configUiJenis['counter_global'];
            $counterGlobalOverride = isset($tmp1[0]->$key) ? $tmp1[0]->$key : null;
        }

        //entry points
        $trep = new MdlTransaksi();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
        showLast_query("biru");
        cekBiru(count($tmpEP));
        $epValues = array();
        $rowStepNumber = array();
        $epDetail = array();
        $terimaAr = array();
        $registryCacheByLinkId = array();
        $registryIds = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $tmpEpRow) {
                if (!empty($tmpEpRow->deskripsi) && !empty($tmpEpRow->link_id)) {
                    $registryIds[$tmpEpRow->link_id] = $tmpEpRow->link_id;
                }
            }
        }
        if (sizeof($registryIds) > 0) {
            $trRegistry = new MdlTransaksi();
            $trRegistry->setJointSelectFields("transaksi_id,main");
            $tmpRegistryQuery = $trRegistry->lookupBaseDataRegistries(array_values($registryIds));
            if ($tmpRegistryQuery !== false) {
                $tmpRegistry = $tmpRegistryQuery->result();
                if (sizeof($tmpRegistry) > 0) {
                    foreach ($tmpRegistry as $tmpRegistryRow) {
                        $registryCacheByLinkId[$tmpRegistryRow->transaksi_id] = $tmpRegistryRow;
                    }
                }
            }
        }
        $entryPointCounterDecodeTime = 0;
        $entryPointEmptyRowBuildTime = 0;
        $entryPointRegistryLookupTime = 0;
        $entryPointRegistryDecodeTime = 0;
        $entryPointRejectedRowBuildTime = 0;
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
//                cekOrange($row->id." || ".$row->nomer);
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $tEntryPoint = microtime(true);
                $counterDecode = blobDecode($row->counters);
                $entryPointCounterDecodeTime += microtime(true) - $tEntryPoint;
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobalOld = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterGlobal = ($counterGlobalOverride !== null) ? $counterGlobalOverride : $counterGlobalOld;
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $id_master = $row->id_master;
                $stepLabelKey = $row->jenis_master . "|" . $row->step_number;
                if (!isset($stepLabelCache[$stepLabelKey])) {
                    $stepLabelCache[$stepLabelKey] = isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "";
                }
                $stepLabel = $stepLabelCache[$stepLabelKey];
                if (empty($row->deskripsi)) {
                    $tEntryPoint = microtime(true);
                    if (!isset($rowStepNumber["referenceID__" . $row->step_number])) {
                        $rowStepNumber["referenceID__" . $row->step_number] = array(
                            "id" => $row->id,
                            "link_id" => $row->link_id,
                            "step_num" => $row->step_number,

                        );
                    }
                    $epValues['sign_' . $row->id . '_' . $row->step_number] = array(
                        "title" => $stepLabel,
                        "cabang_nama" => $row->cabang_nama,
                        "label" => $row->jenis_label,
                        "dtime" => $row->dtime,
                        "nomer" => $row->nomer,
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->oleh_nama,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
                        "global_counter" => $cGlobals,
                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
//                        "date_faktur" => isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL,
//                        "nomer_faktur" => isset($row->efaktur) ? $row->efaktur : NULL,
//                        "gunggungan_mode" => isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0,
//
                    );
                    if ($row->efaktur != 0) {
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["date_faktur"] = isset($row->efaktur_dtime) ? $row->efaktur_dtime : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["nomer_faktur"] = isset($row->efaktur) ? $row->efaktur : NULL;
                        $epValues['sign_' . $row->id . '_' . $row->step_number]["gunggungan_mode"] = isset($row->gunggungan_mode) ? $row->gunggungan_mode : 0;
                    }
                    $entryPointEmptyRowBuildTime += microtime(true) - $tEntryPoint;
                }
                else {
                    $tEntryPoint = microtime(true);
                    if (!isset($registryCacheByLinkId[$row->link_id])) {
                        $tRegistryLookup = microtime(true);
                        $tmpReg = $tr->lookupDataRegistriesByMasterID($row->link_id)->result();
                        $entryPointRegistryLookupTime += microtime(true) - $tRegistryLookup;
                        if (sizeof($tmpReg) > 0) {
                            $registryCacheByLinkId[$row->link_id] = $tmpReg[0];
                        }
                    }
                    $tmpReg = isset($registryCacheByLinkId[$row->link_id]) ? array($registryCacheByLinkId[$row->link_id]) : array();
                    if (sizeof($tmpReg) == 0) {
                        $entryPointRejectedRowBuildTime += microtime(true) - $tEntryPoint;
                        continue;
                    }
                    $tRegistryDecode = microtime(true);
                    $main = blobDecode($tmpReg[0]->main);
                    $entryPointRegistryDecodeTime += microtime(true) - $tRegistryDecode;
                    if ($jenis == "5822spd") {
                        $main["referenceID__4"] = $main["referenceID"];
                    }
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        foreach ($main as $m_key => $mval) {
                            if (isset($rowStepNumber[$m_key])) {
                                cekMerah($m_key . "::" . $mval . " ::*" . $rowStepNumber[$m_key]["id"]);
//                                switch ($jenis) {
//                                    case "9112":
//                                    case "9111":
//                                        break;
//                                    default:
//                                        break;
//                                }
//
                                $epValues['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = $row->deskripsi;
                                $epDetail['sign_' . $rowStepNumber[$m_key]["id"] . '_' . $rowStepNumber[$m_key]["step_num"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                );
                            }
                            else {
                                //                            cekBiru("lolosss");
                            }
                        }
                    }
                    else {
                        switch ($main["jenisTr"]) {
                            case "9912":
                            case "9911":
//                                cekUngu($main["referenceStepNumber"]);
                                $selectedTrid = $main["transaksi"] + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["referenceStepNumber"]]["status"] = array(
                                    "oleh_nama" => $main["olehName"],
                                    "dtime" => $main["dtime"],
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                $epValues['sign_' . $row->id . '_' . $row->step_number . '_r'] = array(
                                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? "Pembatalan/Reject " . $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                                    "cabang_nama" => $row->cabang_nama,
                                    "label" => $row->jenis_label,
                                    "dtime" => $main["dtime"],
                                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                                    "nomer_orig" => $row->nomer,
                                    "oleh" => $main["olehName"],
                                    "caption_department" => "text",
                                    "deskripsi" => $row->deskripsi,
                                    "link_id" => $row->link_id,
                                    "global_counter" => $cGlobals,
                                    "master_id_global_counter" => $cMasterIDGlobals,
                                    "jenis" => $jenis,
                                );
                                break;
                            default:
                                $selectedTrid = isset($main["transaksi"]) ? $main["transaksi"] : $main["currentID"];
                                $selectedTrid = $selectedTrid + 1;
                                $epDetail['sign_' . $selectedTrid . '_' . $main["stepNumber"]]["status"] = array(
                                    "oleh_nama" => $row->oleh_nama,
                                    "dtime" => $row->dtime,
                                    "nomer" => $row->nomer2,
                                    "label" => $row->jenis_label,
                                );
                                $epValues['sign_' . $row->id . '_' . $row->step_number . '_r'] = array(
                                    "title" => $stepLabel !== "" ? "Pembatalan/Reject " . $stepLabel : "",
                                    "cabang_nama" => $row->cabang_nama,
                                    "label" => $row->jenis_label,
                                    "dtime" => $row->dtime,
                                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                                    "nomer_orig" => $row->nomer,
                                    "oleh" => $row->oleh_nama,
                                    "caption_department" => "text",
                                    "deskripsi" => $row->deskripsi,
                                    "link_id" => $row->link_id,
                                    "global_counter" => $cGlobals,
                                    "master_id_global_counter" => $cMasterIDGlobals,
                                    "jenis" => $jenis,
                                );
                                break;
                        }
                    }
                    $entryPointRejectedRowBuildTime += microtime(true) - $tEntryPoint;
                }
                if (isset($jenisTrSpdLinkLookup[$row->jenis])) {
                    $terimaAr[$row->link_id] = $row->link_id;
                }
                if (isset($jenisTr110rLookup[$row->jenis])) {
                    $inputFaktur[$row->link_id] = $row->link_id;
                }
            }
        }
        $configUiJenisCache = array();
        $getConfigUiJenisCached = function ($jenisMaster) use (&$configUiJenisCache) {
            if (!isset($configUiJenisCache[$jenisMaster])) {
                $configUiJenisCache[$jenisMaster] = loadConfigModulJenis_he_misc($jenisMaster, "coTransaksiUi");
            }
            return $configUiJenisCache[$jenisMaster];
        };
        $stepLabelCache = array();
        $jenisTrSpdLinkLookup = array();
        foreach ($jenisTr_spd_link as $jenisTrSpdLink) {
            $jenisTrSpdLinkLookup[$jenisTrSpdLink] = true;
        }
        $jenisTr110rLookup = array();
        foreach ($jenisTr_110r as $jenisTr110r) {
            $jenisTr110rLookup[$jenisTr110r] = true;
        }

        //------LINK KE AR
        if (sizeof($terimaAr) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis='749'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $terimaAr) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
            showLast_query("pink");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $configUiRow = $getConfigUiJenisCached($row->jenis_master);
                $stepLabel = isset($configUiRow['steps'][$row->step_number]['label']) ? $configUiRow['steps'][$row->step_number]['label'] : "";
                $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
                    "title" => $stepLabel,
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                );
            }
        }
        //------
        if (sizeof($inputFaktur) > 0) {
            $trep = new MdlTransaksi();
            $trep->addFilter("transaksi.jenis_master='110'");
            $trep->addFilter("transaksi_data.produk_id in ('" . implode("','", $inputFaktur) . "')");
            $trepTmp = $trep->lookupJoined_OLD()->result();
//            showLast_query("kuning");
            foreach ($trepTmp as $row) {
                $jenis = $row->jenis;
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterjenismasterID = $jenis . "|" . $row->id_master;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $counterMasterIDGlobal = isset($counterDecode['stepCode|masterID'][$counterjenismasterID]) ? $counterDecode['stepCode|masterID'][$counterjenismasterID] : 0;
                $cGlobals = digit_5($counterGlobal);
                $cMasterIDGlobals = $counterMasterIDGlobal > 0 ? digit_5($counterMasterIDGlobal) : 0;
                $configUiRow = $getConfigUiJenisCached($row->jenis_master);
                $stepLabel = isset($configUiRow['steps'][$row->step_number]['label']) ? $configUiRow['steps'][$row->step_number]['label'] : "";
                $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
                    "title" => $stepLabel,
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    "dtime" => $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    "oleh" => $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                    "master_id_global_counter" => $cMasterIDGlobals,
                    "jenis" => $jenis,
                    "date_faktur" => $row->efaktur_dtime,
                    "nomer_faktur" => $row->efaktur,
                    "gunggungan_mode" => $row->gunggungan_mode,
                );
                if ($row->trash_4 == 1) {
//                    arrPrintHitam($row);
                    $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number . '_r'] = array(
                        "title" => $stepLabel !== "" ? "Pembatalan/Reject " . $stepLabel : "",
                        "cabang_nama" => $row->cabang_nama,
                        "label" => "Pembatalan/Reject " . $row->jenis_label,
//                        "dtime" => $row->dtime,
                        "dtime" => $row->cancel_dtime,
                        "nomer" => $row->nomer,
                        "nomer_reject" => isset($row->cancel_transaksi_nomer) ? $row->cancel_transaksi_nomer : NULL,
                        "nomer_orig" => $row->nomer,
                        "oleh" => $row->cancel_name,
                        "caption_department" => "text",
                        "deskripsi" => $row->deskripsi,
                        "link_id" => $row->link_id,
//                        "global_counter" => $cGlobals,
//                        "master_id_global_counter" => $cMasterIDGlobals,
                        "jenis" => $jenis,
                        "date_faktur" => $row->efaktur_dtime,
                        "nomer_faktur" => $row->efaktur,
                        "gunggungan_mode" => $row->gunggungan_mode,
                    );
                }
            }
        }

//arrPrintCyan($epValues);

        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;
        $signPrintLabel = "<span class='fa fa-print' style=\"font-size:20px\"></span>";
        $signDateFormat = "d M Y H:i";
        $steps = $configUiJenis;
        foreach ($steps as $step => $keis) {
            $autoNextStep = isset($keis['autoNextStep']) ? $keis['autoNextStep'] : 0;
        }
//arrPrintCyan($signArray);
        $pakai_ini_urut_keyid = 0;
        if ($pakai_ini_urut_keyid == 1) {
            $signArrayKeys = array_keys($signArray);
            foreach ($signArrayKeys as $keyy) {
                $keyy_ex = explode("_", $keyy);
                $signArrayKeysTrid[$keyy_ex[1]] = $keyy;
            }
        }
        else {
            foreach ($signArray as $xx) {
                $signArrayKeysTrid[$xx["dtime"]] = $xx;
            }
        }

        ksort($signArrayKeysTrid);
        arrPrintPink($signArrayKeysTrid);
//        arrPrintCyan($signArray);
//        arrPrintHitam($epDetail);
//        mati_disini(__LINE__);
        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel table-responsive'>";
            $signStr .= "<h4 class='text-blue'><span class='fa fa-sign-in'></span> entry points </h4>";

            $signStr .= "<h5 class='text-blue'><span class='fa fa-tasks'></span> $resumeTitle  $resumeName</h5>";
            $signStr .= "<table class='table table-condensed table-striped table-hover-color-red dataTable'>";
            $signStr .= "<tr bgcolor='#f0f0f0'>";
            $signStr .= "<td class='text-muted'>date</td>";
            $signStr .= "<td class='text-muted'>title</td>";
            $signStr .= "<td class='text-muted'>branch</td>";
            $signStr .= "<td class='text-muted'>transaction number</td>";
            $signStr .= "<td class='text-muted'>person</td>";
            $signStr .= "<td class='text-muted'>status</td>";
            $signStr .= "<td class='text-muted'>tool</td>";
            foreach ($signArrayKeysTrid as $k_ii => $v_ii) {
                if ($pakai_ini_urut_keyid == 1) {
                    $k = $v_ii;
                    $sSpec = $signArray[$v_ii];
                    $jenis = $sSpec['jenis'];
                    $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                    $nomerOrig = $sSpec['nomer_orig'];
                    $nomerOrigEx = explode("_", $nomerOrig);
                    $nomerOrigNota = $nomerOrigEx[0];
                    $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                    $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                    $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;
                    $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                    $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";
                    $edit_description = "";
                    if (isset($arrTopEdit[$sSpec['link_id']])) {
                        $edit_dtime = date($signDateFormat, strtotime($arrTopEdit[$sSpec['link_id']]['edit_dtime']));
                        $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                        $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                    }
                    $rejection_label = "";
                    $reject_description = "";
                    if (isset($epDetail[$k])) {
                        $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                        $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                        $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                        $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                    }
                    //-----
                    $date_faktur = "";
                    $nomer_faktur = "";
                    $gunggungan_mode = 0;
                    $gunggungan_mode_bgcolor = "";
                    if (isset($sSpec["date_faktur"])) {
                        $date_faktur = $sSpec["date_faktur"];
                    }
                    if (isset($sSpec["nomer_faktur"])) {
                        $nomer_faktur = $sSpec["nomer_faktur"];
                    }
                    if (isset($sSpec["gunggungan_mode"])) {
                        $gunggungan_mode = $sSpec["gunggungan_mode"];
                        $gunggungan_mode_bgcolor = "yellow";
                    }
                }
                else {
                    $k = $v_ii;
                    $sSpec = $v_ii;
                    $jenis = $sSpec['jenis'];
                    $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                    $nomerOrig = $sSpec['nomer_orig'];
                    $nomerOrigEx = explode("_", $nomerOrig);
                    $nomerOrigNota = $nomerOrigEx[0];
                    $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];
                    $nomerViewEdit = $nomerOrigNota . "-" . $sSpec['master_id_global_counter'];
                    $nomerViewFinal = $jenis == "582spoe" ? $nomerViewEdit : $nomerView;
                    $oleh_nama = isset($arrSignName[$k]) ? $arrSignName[$k] : $sSpec['oleh'];
                    $status = isset($sSpec['status']) ? " (" . $sSpec['status'] . ")" : "";
                    $edit_description = "";
                    if (isset($arrTopEdit[$sSpec['link_id']])) {
                        $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                        $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                        $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                    }
                    $rejection_label = "";
                    $reject_description = "";
                    if (isset($epDetail[$k])) {
                        $reject_dtime = formatField_he_format("date_time", $epDetail[$k]["status"]['dtime']);
                        $reject_nama = $epDetail[$k]["status"]['oleh_nama'];
                        $reject_description .= $epDetail[$k]["status"]['label'] . "<br>";
                        $reject_description .= "<span style='color:red;'>by $reject_nama, $reject_dtime</span>";
                    }
                    //-----
                    $nomer_reject = "";
                    $date_faktur = "";
                    $nomer_faktur = "";
                    $gunggungan_mode = 0;
                    $gunggungan_mode_bgcolor = "";
                    if (isset($sSpec["nomer_reject"])) {
                        $nomer_reject = $sSpec["nomer_reject"];
                    }
                    if (isset($sSpec["date_faktur"])) {
                        $date_faktur = $sSpec["date_faktur"];
                    }
                    if (isset($sSpec["nomer_faktur"])) {
                        $nomer_faktur = $sSpec["nomer_faktur"];
                    }
                    if (isset($sSpec["gunggungan_mode"])) {
                        $gunggungan_mode = $sSpec["gunggungan_mode"];
                        $gunggungan_mode_bgcolor = "yellow";
                    }
                }


                $signStr .= "<tr style='background-color:$gunggungan_mode_bgcolor;'>";
                $signStr .= "<td>" . date($signDateFormat, strtotime($sSpec['dtime']));
                $signStr .= "</td>";
                $signStr .= "<td>" . ($sSpec['label']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                $signStr .= "<td>" . $nomerViewFinal . $signDeskripsi . "";
                if ($edit_description != "") {
                    $signStr .= "<br>$edit_description";
                }
                if ($date_faktur != "") {
                    $signStr .= "<br>tanggal faktur: $date_faktur";
                }
                if ($nomer_faktur != "") {
                    $signStr .= "<br>Nomer faktur: $nomer_faktur";
                }
                if ($nomer_reject != "") {
                    $signStr .= "<br>Nomer Pembatalan/Reject: $nomer_reject";
                }

                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $oleh_nama . "";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= "<span class='fa fa-user'></span> " . $status . "<br>" . $reject_description . "";
                $signStr .= "</td>";

                $signStr .= "<td title='" . $sSpec['label'] . "'>";
                $signStr .= $signPrintLabel;
                $signStr .= "</td>";

                $signStr .= "</tr>";
            }
            $signStr .= "</table class='table table-condensed'>";

            $signStr .= "</div class='panel'>";
        }


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $allowedView = $this->config->item('userGroup_jurnal') != null ? $this->config->item('userGroup_jurnal') : array();
        $rekeningAlias = $this->config->item('accountAlias') != null ? $this->config->item('accountAlias') : array();

        $lItems = array();
        $headers = array();
        $arrCabang = array();
        if (array_intersect($allowedView, $mems)) {
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Mdls/MdlCabang");

            $cb = New MdlCabang();
            $tmpCb = $cb->lookupAll()->result();
            $arrCabang = array();
            if (sizeof($tmpCb) > 0) {
                foreach ($tmpCb as $spec) {
                    $arrCabang[$spec->id] = $spec->nama;
                }
            }

            $j = new ComJurnal();
            $j->addFilter("transaksi_no='$no'");
            $j->addFilter("status='1'");
            $j->addFilter("trash='0'");
            if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

            }
            else {
                // dimatikan dulu untuk checking jurnal lengkapnya...........
                //                $j->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            //            $j->setSortBy(array("kolom" => "urut", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            if (sizeof($tmp) > 0) {
                /* ---------------------------------------
                 * array link untuk mengarahkan ke halaman mutasi yg dikendaki
                 * -----------------------------------*/

                //region Description
                $mutasiLinks = array();
                foreach ($accountChildsLinks as $rekNama => $rekLink) {
                    $mutasiLinks[$rekNama] = $rekLink . "/" . $main['pihakID'] . "?o=" . $main['cabangID'] . "&trID=$transaksiID";
                }
                //endregion

                foreach ($tmp as $row) {
                    $d_start = backCustomDate("30", $row->dtime);
                    $d_stop = $row->dtime;
                    if ($row->j_jenis == "items") {
                        $blob_ext = blobEncode($row->rekening);
                        //                        Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/1076?o=-1&ext2_id=&main_ext2_id=
                        $defLink = "Ledger/viewMoveDetails/RekeningPembantuProduk/" . $row->rekening_main . "/" . $row->extern_id . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop&blob_ext=$blob_ext";
                    }
                    else {
                        $defLink = "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                        //                    $defLink = "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&trID=$transaksiID&date1=$d_start&date2=$d_stop";
                    }

                    //                    $rekening_alias = isset($rekeningAlias[$row->rekening]) ? $rekeningAlias[$row->rekening] : $row->rekening;
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        //$strLink = key_exists($row->rekening, $mutasiLinks) ? $mutasiLinks[$row->rekening] : $defLink;
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }
                //                arrPrint($lItems);
                ksort($lItems);
            }


        }

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $mobMode = $isMob == true ? "1" : "0";

        $printSettings = isset($this->configLayout[$jenisMasterTrans]['allowPrint']['1']) ? $this->configLayout[$jenisMasterTrans]['allowPrint']['1'] : array();
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Transaksi/viewReceipt/";

        $valasType = (isset($main['currencyDetails__label'])) ? "&type=" . blobEncode($main['currencyDetails__label']) : "";
        $valasFKali = "&f=" . blobEncode(1);

        if (sizeof($tmpSign) > 0) {
            if (isset($printSettings['size'])) {
                switch ($printSettings['size']) {
                    case "normal":
                        $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=1$valasType$valasFKali');";
                        break;
                    case "small":
                        $receiptLink = "top.popSmall('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=2');";
                        break;
                    default:
                        //                                cekkuning("size: UNKNOWN");
                        break;
                }
            }
            else {
                $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?mobMode=$mobMode&st=3$valasType$valasFKali');";
            }
        }
        else {
            $receiptLink = "top.popBig('" . MODUL_PATH . $printLocation . $this->jenisTr . "/" . "$no?st=4');";
        }


        // ============== TAMBAHAN ============== ============== ==============
        $itemLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields'][1] : array();
        $itemLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields2'][1] : array();
        $itemLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartFields3'][1] : array();
        $itemNumLabels = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields'][1] : array();
        $itemNumLabels2 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields2'][1] : array();
        $itemNumLabels3 = isset($this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartNumFields3'][1] : array();
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1];
        $noteEnabled = isset($this->configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $showScheme = isset($this->configUi[$this->jenisTr]['shoppingCartShowScheme']) ? $this->configUi[$this->jenisTr]['shoppingCartShowScheme'] : false;


        $arrCons = array();
        $headerScheme = array();

        if ($showScheme) {

            $awal_pinjaman = isset($main['awal_pinjaman']) ? $main['awal_pinjaman'] : date('Y-m-d');
            $jatuh_tempo = isset($main['jatuh_tempo']) ? $main['jatuh_tempo'] : date('Y-m-d');
            $nilai_pinjaman = isset($main['harga']) ? $main['harga'] : 0;
            $rate_bunga = isset($main['persen_bunga']) ? $main['persen_bunga'] : 0;

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

        $itemLabelsTop = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $item2LabelsTop = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $item3LabelsTop = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemLabelBlacklist = array(
            "stok_center",
            "stok",
        );

        foreach ($itemLabelsTop as $key => $val) {
            if (in_array($key, $itemLabelBlacklist)) {
                unset($itemLabelsTop[$key]);
            }
        }
        if (isset($this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount']) && $this->configUi[$jenisMasterTrans]['shoppingCartHideSubamount'][1] == true) {
            unset($itemLabelsTop['subtotal']);
            unset($item2LabelsTop['subtotal']);
            unset($item3LabelsTop['subtotal']);
        }
        $blacklistView = $this->config->item('userGroup_blacklist') != null ? $this->config->item('userGroup_blacklist') : array();
        $addFildRowFields = array();


        $allowedViewTop = false;
        foreach ($mems as $memsVal) {
            if (!in_array($memsVal, $blacklistView)) {
                $allowedViewTop = true;
            }
        }

        if ($allowedViewTop == true) {

            //untuk melihat data order/spo nya
            $m = new MdlTransaksi();
            $m->setFilters(array());
            $tmpReg = $m->lookupDataRegistriesByMasterID($transaksiTopID)->result();
            $regfields = $m->getFields()["dataRegistry"];
            $addRow = isset($this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes']) ? $this->configUi[$jenisMasterTrans]['additionalRows']['dummyElement']['yes'] : array();
            $itemNotApprove = isset($this->configUi[$jenisMasterTrans]['closedRequest'][1]) ? $this->configUi[$jenisMasterTrans]['closedRequest'][1] : true;
            $detailCore = isset($this->configCore[$jenisMasterTrans]['valueGates']['detail']) ? $this->configCore[$jenisMasterTrans]['valueGates']['detail'] : array();

            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($regfields as $params) {
                        // cekHitam($params);
                        if (isset($row->$params) && ($params != "transaksi_id")) {

                            $var = $params . "_reg";
                            $$var = unserialize(base64_decode($row->$params));
                        }

                    }
                    // $$var = unserialize(base64_decode($row->values));
                }
                if (sizeof($addRow) > 0) {
                    foreach ($addRow as $gateKey => $mpFields) {

                        if (isset($mpFields['hideRow']) && $mpFields['hideRow'] == true) {

                        }
                        else {
                            $addFildRowFields[$gateKey] = $mpFields['label'];
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    //                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID)->result();
                    $dTmp = $m->lookupDetailTransaksi($transaksiTopID);

                    $notApprove = array();
                    foreach ($dTmp as $spec) {
                        if ((isset($spec->valid_qty_no_approve)) && ($spec->valid_qty_no_approve > 0)) {
                            $notApprove[$spec->produk_id] = $spec->valid_qty_no_approve;
                        }
                    }
                    $mainNotApprove = array();
                    $itemsNotApprove = array();
                    if (sizeof($notApprove) > 0) {
                        $itemsNotApprove = $items_reg;
                        foreach ($notApprove as $pid => $pqty_not_approve) {
                            $itemsNotApprove[$pid]['qty'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['jml'] = $pqty_not_approve;
                            $itemsNotApprove[$pid]['subtotal'] = $pqty_not_approve * $itemsNotApprove[$pid]['harga'];
                            if (sizeof($detailCore) > 0) {
                                foreach ($detailCore as $key_c => $val_c) {
                                    $itemsNotApprove[$pid]['sub_' . $key_c] = $pqty_not_approve * $itemsNotApprove[$pid][$key_c];
                                    if (!isset($mainNotApprove[$key_c])) {
                                        $mainNotApprove[$key_c] = 0;
                                    }
                                    $mainNotApprove[$key_c] += ($pqty_not_approve * $itemsNotApprove[$pid][$key_c]);
                                }
                            }
                            if (!isset($mainNotApprove['harga'])) {
                                $mainNotApprove['harga'] = 0;
                            }
                            $mainNotApprove['harga'] += ($pqty_not_approve * $itemsNotApprove[$pid]['harga']);
                        }
                    }
                }
            }

            //tambahan jika ada result item
            // arrPrint($main_reg);
            if (sizeof($rsltItems_reg) > 0) {
                //region reference detil
                $m->setFilters(array());
                $tmpRegRef = $m->lookupDataRegistriesByMasterID($main_reg['referenceID']);
                foreach ($tmpRegRef as $refRow) {
                    foreach ($regfields as $params) {
                        if (isset($refRow->$params)) {
                            $varR = $params . "_ref";
                            $$varR = unserialize(base64_decode($refRow->$params));
                        }
                    }
                }

                $tmpRowRslts_ref = array();
                $totalNilaiRef = 0;
                if (sizeof($rsltItems_ref) > 0) {
                    foreach ($rsltItems_ref as $rowRefID => $rowref_data) {
                        // $hpp = number_format($rowref_data['hpp'],0);
                        $hpp = $rowref_data['hpp'];
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] = 0;

                        }
                        if (!isset($tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'])) {
                            $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] = 0;
                        }

                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['qty'] += $rowref_data['qty'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['subtotal'] += $rowref_data['sub_hpp'];
                        $tmpRowRslts_ref[$rowref_data['id']][$main_reg['pihakExternID']][$hpp]['harga'] = $rowref_data['hpp'];
                        $totalNilaiRef += $rowref_data['sub_hpp'];
                    }
                    $rowDetilRslt_label[$main_reg['pihakExternID']] = array(
                        "harga" => "harga",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                    );
                }
                //endregion

                //region current detil
                $tmpRowRslts = array();
                $tmpRowRsltsnilai = 0;
                foreach ($rsltItems_reg as $rowFifo => $fifoData) {
                    // $hpp_0 = number_format($fifoData['hpp'],0);
                    $hpp_0 = $fifoData['hpp'];
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] = 0;
                    }
                    if (!isset($tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'])) {
                        $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] = 0;
                    }
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['qty'] += $fifoData['qty'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['subtotal'] += $fifoData['sub_hpp'];
                    $tmpRowRslts_ref[$fifoData['id']][$main_reg['jenisTr']][$hpp_0]['harga'] = $fifoData['hpp'];
                    $tmpRowRsltsnilai += $fifoData['sub_hpp'];
                }
                $tmpRowRslts_label = array(
                    "nama" => "produk",
                    "qty" => "qty",
                    $main_reg['pihakExternID'] => $main_reg['pihakExternName'],
                    $main_reg['jenisTr'] => $main_reg['jenisTrName'],
                );
                $rowDetilRslt_label[$main_reg['jenisTr']] = array(
                    "harga" => "harga",
                    "qty" => "qty",
                    "subtotal" => "subtotal",
                );

                //endregion
            }
        }

        $transaksiJenisTopLabel = isset($main_reg['jenisTrName']) ? $main_reg['jenisTrName'] : "";
        //-------------------------------------
        if (($configNotes != NULL) && ($globalVars['trash_4'] == 1)) {
            foreach ($globalVars as $key => $val) {
                if (!is_array($val)) {
                    $configNotes = str_replace("{" . $key . "}", $val, $configNotes);
                }
            }
            $notes_final = $configNotes;
        }
        else {
            $notes_final = NULL;
        }


        //region packinglist/serial
        $shoppingCartSubDetailFields = isset($this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSubDetailFields'][3] : array();
        $shopingCartPairFieldResume = isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResume'] : array();
        if (sizeof($shoppingCartSubDetailFields) > 0) {
            //            cekHere(":: $jenisMasterID ::");
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$jenisMasterID'");
            $tr->addFilter("jenis='5822pkd'");
            $tr->addFilter("trash_4='0'");
            $tmp0 = $tr->lookupAll()->result();
            $ids_transaksi = array();
            foreach ($tmp0 as $tmp0_x) {
                $ids_transaksi[] = $tmp0_x->id;
            }

            if (count($ids_transaksi) > 0) {

                cekHitam($this->db->last_query());
                //            matiHere(__LINE__);
                //            $trid_pkd = $tmp0[0]->id;
                $tr->setFilters(array());
                //            $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
                //            $tr->addFilter("transaksi_id='$trid_pkd'");
                $tr->addFilter("transaksi_id in (" . implode(',', $ids_transaksi) . ")");
                $tmpReg = $tr->lookupDataRegistries()->result();
                //                arrPrint($pairRegistry);
                //                cekBiru($this->db->last_query());
                $dataRegistry = array();
                if (sizeof($tmpReg) > 0) {
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $eParams) {

                            $dataRegistry[] = blobDecode($eParams->items);
                            foreach ($pairRegistry as $param) {
                                $dataRegistry[$param][] = blobDecode($eParams->$param);
                                //                            switch ($param){
                                //                                case "main":
                                //                                    $main[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items":
                                //                                    $items[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                                case "items3_sum":
                                //                                    $items3_sum[]=blobDecode($eParams->$param);
                                //                                    break;
                                //                            }
                                //                            unset($param->transaksi_id);
                                //                            if ($eParams->$param == null) {
                                //                                $eParams->$param = blobEncode(array());
                                //                            }
                                ////                            $registryData[]
                                //                            $$param = blobDecode($eParams->$param);
                            }
                        }
                    }
                }

                $arrSubDetailDataKolom = array();
                foreach ($shoppingCartSubDetailFields as $kolom => $kolomSpec) {
                    $gerbang = $kolomSpec["gate"];
                    $source = $kolomSpec["source"];
                    $source_new = $dataRegistry[$source];
                    $arrHasil = array();
                    foreach ($source_new as $ix => $sxdata) {

                        foreach ($sxdata as $sdata) {
                            //                        arrPrint($sdata);
                            //                        matiHEre(__LINE__);
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


                    }
                    $arrSubDetailDataKolom[$kolom] = $arrHasil;
                    //                arrPrint($arrSubDetailDataKolom);
                }
                $fullfillment = array();
                if (count($shopingCartPairFieldResume) > 0) {
                    if (isset($dataRegistry["items"])) {
                        foreach ($dataRegistry["items"] as $ixx => $data) {
                            foreach ($data as $pid => $data_0) {
                                foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                    if (!isset($shipment[$pid][$field_shipment])) {
                                        $shipment[$pid][$field_shipment] = 0;
                                    }
                                    $shipment[$pid][$field_shipment] += $data_0[$field_shipment];
                                }
                            }

                        }
                    }
                }

                if (count($fullfil_id) > 0) {
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id='$fullfil_id'");
                    $tmpRegFulfil = $tr->lookupDataRegistries()->result();
                    cekMerah($this->db->last_query());
                    $itemsFulfilmentTmp = blobDecode($tmpRegFulfil[0]->items);
                    if (count($shopingCartPairFieldResume) > 0) {
                        //                    if(isset($dataRegistry["items"])){
                        foreach ($itemsFulfilmentTmp as $pid => $data_0) {
                            //                            foreach ($data as $pid =>$data_0){
                            foreach ($shopingCartPairFieldResume as $field_shipment => $alias) {
                                if (!isset($shipment[$pid][$field_shipment])) {
                                    $fullfillment[$pid][$field_shipment] = 0;
                                }
                                $fullfillment[$pid][$field_shipment] += $data_0[$field_shipment];
                            }
                            //                            }

                        }
                        //                    }
                    }
                }
            }


        }
        else {
            //            cekMerah("::: KOSONG :::");
        }

        //-------------------------------------
        $imagesReference = getTransaksiImageReference($jenisMasterID)[$jenisMasterID];

        $data = array(
            //            "mode" => $this->uri->segment(3),
            "mode" => count($imagesReference) > 0 ? "viewResumeImages" : $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
            "items" => $lItems,
            "receiptLink" => $receiptLink,
            "signStr" => $signStr,
            "cabangData" => $arrCabang,

            "urutCounter" => $urutCounters,
            "underMaintenance" => underConstruction(),

            // ===== ======
            "detail_title" => "Pengajuan $transaksiJenisTopLabel",

            "itemLabels" => isset($itemLabelsTop) ? $itemLabelsTop : array(),
            "item2Labels" => isset($item2LabelsTop) ? $item2LabelsTop : array(),
            "item3Labels" => isset($item3LabelsTop) ? $item3LabelsTop : array(),

            "detail_main" => isset($main_reg) ? $main_reg : array(),
            "detail_items" => isset($items_reg) ? $items_reg : array(),
            "detil_item_rslt" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            // "detil_item_rslt_ref" => isset($tmpRowRslts_ref) ? $tmpRowRslts_ref : array(),
            "detil_item_rslt_label" => isset($tmpRowRslts_label) ? $tmpRowRslts_label : array(),
            "detil_item_rslt_label2" => isset($rowDetilRslt_label) ? $rowDetilRslt_label : array(),
            "detail2_items" => isset($items2_sum_reg) ? $items2_sum_reg : array(),
            "detail3_items" => isset($items3_sum_reg) ? $items3_sum_reg : array(),

            "detail_sumRows" => $sumRows,
            "detail2_sumRows" => isset($sumRows2) ? $sumRows2 : array(),
            "detail3_sumRows" => isset($sumRows3) ? $sumRows3 : array(),
            "noteEnabled" => $noteEnabled,
            "addRows" => $addFildRowFields,
            //--------------------
            "mainNotApprove" => isset($mainNotApprove) ? $mainNotApprove : array(),
            "itemsNotApprove" => isset($itemsNotApprove) ? $itemsNotApprove : array(),
            "detail_not_approve_title" => "Pengajuan $transaksiJenisTopLabel (tidak diapprove)",
            //--------------------
            "notes_final" => $notes_final,
            "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
            "referenceImages" => $imagesReference,
            "elementStr" => isset($elementStr) ? $elementStr : "",
            "deliveryDetail" => isset($deliveryDetail) ? $deliveryDetail : array(),
            "shipment" => $shipment,
            "fullfillment" => $fullfillment,
            "customHeaderResume" => isset($this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil']) ? $this->configUi[$jenisMasterTrans]['shopingCartPairFieldResumeDetil'] : array(),
        );
        $this->load->view("transaksi", $data);
    }

    public function viewMyActs()
    {

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }

        $uID = isset($_GET['u']) && $_GET['u'] > 0 ? $_GET['u'] : $this->session->login['id'];
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $limit = 18;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        //        $historyFields = $this->configUi[$jenisTr]['shortHistoryFields'];
        $historyFields = isset($this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields']) ? $this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields'] : array(
            "produk_nama" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "@price",

            "nomer_top+nomer" => "receipt number",
            "oleh_nama+dtime" => "person",
        );
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        //        arrprint($historyFields);
        //
        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    //                    $stepLabels[$stepNumber] = $stepSpec['stateLabel'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $stepSpec['target'];
                }

            }


            //            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
            $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : $this->configUi[$jenisTr]['steps'][1]['target'];
        }
        //endregion

        //
        //region lookup histories


        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $identifiers = $tr->fetchIdentifiers();
        $recaps = array();
        $names = array();

        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("transaksi.oleh_id='" . $uID . "'");//dimatiin buat test

        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion

        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }

        //        $tr->addFilter("next_substep_code=''");


        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            // "viewDetails" => MODUL_PATH . get_class($this) . "/viewDetails",
            "viewDetails" => MODUL_PATH . "/views/viewDetails",
        );


        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion


        $arrayHistory = array();
        $arrayHistory_ids = array();

        //region ngumpulin ID transaksi
        $trIDs = array();
        $tr0 = $tr;
        //        $tmpHist0 = $tr0->lookupHistories_joined_all($jmlData, $limit, $page)->result();
        $tmpHist0 = $tr0->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpHist0);
        // matiHEre(__LINE__);
        if (sizeof($tmpHist0) > 0) {
            foreach ($tmpHist0 as $row) {
                if (!in_array($row->id, $trIDs)) {
                    $trIDs[] = $row->id;
                }
            }
        }
        //endregion


        //pairingan dari elements
        //region pairing dari elements
        if (isset($this->config->item("heTransaksi_elementPairs")[$this->jenisTr])) {
            // matiHEre(__LINE__);
            $pairConfig = $this->config->item("heTransaksi_elementPairs")[$this->jenisTr];

        }
        if (sizeof($trIDs) > 0) {
            // matiHEre(__LINE__);
            $trReg = new MdlTransaksi();
            $trReg->setFilters(array());
            // $trReg->addFilter("param='main_elements'");
            $trReg->setJointSelectFields("main_elements,transaksi_id");//string
            $trReg->addFilter("transaksi_id in (" . implode(",", $trIDs) . ")");
            $tmpReg = $trReg->lookupDataRegistries()->result();
            // cekkuning($this->db->last_query());
            // arrPrint($tmpReg);
            // matiHere();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    // arrPrint($row);
                    //     matiHEre();
                    $els = blobDecode($row->main_elements);

                    $trID = $row->transaksi_id;
                    if (!isset($identPairs[$trID])) {
                        $identPairs[$trID] = array();
                        $namePairs[$trID] = array();
                        $fieldPairs[$trID] = array();
                    }
                    if (sizeof($els) > 0) {
                        foreach ($els as $elName => $elSpec) {
                            if (isset($pairConfig[$elName])) {

                                //                            arrprint($elSpec);
                                $elPairs = hePairFromElement($this->jenisTr, $elName, $elSpec);
                                //                            arrprint($elPairs['content']);
                                $identifiers = $identifiers + $elPairs['identifiers'];
                                $identPairs[$trID] = $identPairs[$trID] + $elPairs['identifiers'];
                                $names[$elName . "_id"][$elPairs['content'][$elName . "_id"]] = $elName . "_" . $elPairs['content'][$elName . "_nama"];
                                //                                $namePairs[$trID][$elName."_id"][$elPairs['content'][$elName."_id"]]=$elPairs['content'][$elName."_nama"];
                                $namePairs[$trID][$elName . "_id"]["id"] = $elPairs['content'][$elName . "_id"];
                                $namePairs[$trID][$elName . "_id"]["name"] = $elPairs['content'][$elName . "_nama"];
                                if (!isset($fieldPairs[$trID][$elName . "_id"])) {
                                    $fieldPairs[$trID][$elName . "_id"] = "";
                                    $fieldPairs[$trID][$elName . "_nama"] = "";
                                }
                                $fieldPairs[$trID][$elName . "_id"] = $elPairs['content'][$elName . "_id"];
                                $fieldPairs[$trID][$elName . "_nama"] = $elPairs['content'][$elName . "_nama"];
                                //                                cekhijau("pairResult");
                                //                                arrprint($elPairs);
                            }
                        }
                    }
                }
            }
        }
        //endregion
        // matiHEre("hoop".__LINE__);
        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion
        $tmpHist = $tr->lookupHistories_joined_all()->result();
        //        cekbiru($this->db->last_query());

        //        cekMerah($this->db->last_query());


        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                $trID = $row->transaksi_id;
                $tmp = array();
                $tmp1 = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        $colValue = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "";
                    }

                    //                    $tmp[$fName] = formatField_he_format($fName, $row->$fName);
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
                    //                    $arrayHistory[$row->transaksi_id][$fName] = $colValue;
                }
                if (isset($fieldPairs[$trID]) && sizeof($fieldPairs[$trID]) > 0) {
                    foreach ($fieldPairs[$trID] as $k => $v) {
                        $tmp[$k] = $v;
                    }
                }
                $arrayHistory[] = $tmp;
                $arrayHistory_ids[] = $tmp1;

                $jenis = $row->jenis;
                foreach ($identifiers as $iID => $iName) {
                    //                    cekbiru("checking $iID");
                    if ((isset($row->$iID) > 0 && strlen($row->$iID) > 0 && isset($row->$iName) > 0 && strlen($row->$iName) > 0) || (isset($namePairs[$trID][$iID]["id"]) && strlen($namePairs[$trID][$iID]["id"]) > 0)) {
                        //                            cekkuning("$iID/$iName");

                        //                        cekmerah("$iID being processed");
                        if (!isset($names[$iID])) {
                            $names[$iID] = array();
                        }
                        //                        $srcName = "";
                        if (isset($identPairs[$trID]) && array_key_exists($iID, $identPairs[$trID])) {
                            $srcID = $namePairs[$trID][$iID]["id"];
                            $srcName = $namePairs[$trID][$iID]["name"];
                            //                            cekbiru("$trID $srcID reading from element value: $srcID/$srcName");

                        }
                        else {
                            $srcID = $row->$iID;
                            $srcName = $row->$iName;
                            //                            cekbiru("$trID $srcID reading from table row: $srcID/$srcName");
                        }
                        //                        cekkuning("$iID/$iName/$srcID");
                        $names[$iID][$srcID] = $srcName;
                        if (!isset($recaps[$jenis][$iID][$srcID])) {
                            $recaps[$jenis][$iID][$srcID] = array(
                                "qty" => 0,
                                "value" => 0,
                            );
                        }

                        $recaps[$jenis][$iID][$srcID]['qty'] += $row->produk_ord_jml;
                        $recaps[$jenis][$iID][$srcID]['value'] += ($row->produk_ord_jml * $row->produk_ord_hrg);
                    }
                    else {
                        //                        cekmerah("$iID NOT being processed");
                    }
                }

            }
        }
        //endregion

        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //
        //region prepare params for viewer
        $subTitle = lgTranslateTime($date1) . " to " . lgTranslateTime($date2);
        if ($date1 == $date2) {
            $subTitle = lgTranslateTime($date1);
        }

        $availFilters = array();
        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                if (isset($this->config->item("heTransaksi_identifierGroups")[$gID]) && sizeof($this->config->item("heTransaksi_identifierGroups")[$gID]) > 0) {
                    foreach ($this->config->item("heTransaksi_identifierGroups")[$gID] as $fID => $fLabel) {
                        if (!array_key_exists($fID, $availFilters)) {
                            $availFilters[$fID] = $fLabel;
                        }
                    }

                }
            }
        }

        //        arrprint($availFilters);die();


        //arrprint($names);die();

        //arrprint($arrayHistory);
        $thisTr = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : $this->jenisTr;
        $thisTrName = isset($row->jenis_label) ? $row->jenis_label : $this->configUi[$jenisTr]["label"];
        $data = array(
            "mode" => $this->uri->segment(3),
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            //            "trName"               => $this->configUi[$jenisTr]["label"],
            "trName" => $thisTrName,
            "errMsg" => $this->session->errMsg,
            "title" => (isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName) . " (" . $this->session->login['nama'] . ")",
            "subTitle" => $subTitle,
            //            "pageCount"            => $numPages,
            //            "page"                 => $page,
            //            "pages"                => $pages,
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . $this->uri->segment(2) . "/viewIncomplete/" . $this->uri->segment(3) . "/" . $this->jenisTr,
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr,
            //
            //            "availFilters"=>isset($this->config->item("heTransaksi_report")[$jenisTr]["availFilters"])?$this->config->item("heTransaksi_report")[$jenisTr]["availFilters"]:array(),"availFilters"=>isset($this->config->item("heTransaksi_report")[$jenisTr]["availFilters"])?$this->config->item("heTransaksi_report")[$jenisTr]["availFilters"]:array(),
            "availFilters" => isset($availFilters) ? $availFilters : array("oleh_id" => "person"),

            "names" => $names,
            "recaps" => isset($recaps[$thisTr]) ? $recaps[$thisTr] : array(),

        );
        //endregion


        $this->load->view("history", $data);
    }

    public function viewOutstanding()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
            // redirect(base_url() . "Login");
        }

        $limit = 1000;
        //        $limit = 18;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(6);
        $cCode = "_TR_" . $this->jenisTr;
        $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : "transaksi";
        $selecetedCode = isset($this->configUi[$jenisTr]['steps']['2']['target']) ? $this->configUi[$jenisTr]['steps']['2']['target'] : "";
        $historyFields = isset($this->configLayout[$jenisTr]['fixedFieldHold']) ? $this->configLayout[$jenisTr]['fixedFieldHold'] : array();
        $detailsFields = isset($this->configLayout[$jenisTr]['fixedFieldHold'][$currentState]['loop']) ? $this->configLayout[$jenisTr]['fixedFieldHold'][$currentState]['loop'] : array();
        $arrayValid = isset($this->configLayout[$jenisTr]['fixedFieldHold'][$currentState]['array_flip']) ? $this->configLayout[$jenisTr]['fixedFieldHold'][$currentState]['array_flip'] : array();
        $lockerStock = isset($this->configLayout[$jenisTr]['lockerStock']) ? $this->configLayout[$jenisTr]['lockerStock'] : "MdlLockerStock";

        $menuLabel = isset($this->configUi[$jenisTr]['label']) ? $this->configUi[$jenisTr]['label'] : "";

        if (isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = null;
            unset($_SESSION[$cCode]);
        }


        //            $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
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

        //       arrPrint($arrayFlip);
        $mb = New MobileDetect();
        $this->load->model("Mdls/$lockerStock");
        $st = new $lockerStock();
        //        $st->addFilter("jenis='produk'");
        //        $st->addFilter("jenis_locker='stock'");
        $st->addFilter("state='active'");

        $tmpStock = $st->lookupAll()->result();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];

        $marking_style = array();
        if (isset($_GET['trID']) && ($_GET['trID'] > 0)) {
            $marking_style[$_GET['trID']] = "background-color:yellow;font-size:20px;";
        }


        //        arrPrint($tmpStock);

        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        //region prepare ERP
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        if (sizeof($historyFields) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($historyFields);

            foreach ($historyFields as $stepNumber => $stepSpec) {
                $subCodes[$stepNumber] = $stepNumber;
                $stepCodes[] = $stepNumber;
                $stepLabels[$stepNumber] = $stepSpec['label'];
                $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $stepNumber . "/" . "?date1=$date1&date2=$date2";
            }
        }
        //endregion


        //region prepare data outstanding
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr1 = new MdlTransaksi();

        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis='$selecetedCode'");
        $tr->addFilter("transaksi.cabang_id='$cabangID'");

        //        $tr->addFilter("id_master='14005'");
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";

        //region date filter
        //         $this->db->where("fulldate>='" . $date1 . "'");
        //         $this->db->where("fulldate<='" . $date2 . "'");
        //endregion


        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
        }
        $jmlData = $tr->lookupDataCount();
        //        $limit=10;
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
        }

        // $tr->addFilter("sub_step_number>0");
        // $tr->addFilter("valid_qty>0");
        $tr->addFilterJoin("sub_step_number>0");
        $tr->addFilterJoin("valid_qty>0");

        $tmpTr = $tr->lookupJoined();
        //        endregion

        $extractedItems = array();//==untuk urusan update transaksi referer
        $validItems = array();
        $validItemSends = array();
        $mainData = array();
        $itemsVal = array();
        $itemData = array();
        $mainDataOutstandingItems = array();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );

        $stocks = array();
        foreach ($tmpStock as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }

        $summary = array();
        $arrayAction = array();
        $arrayItemsMasterToID = array();
        $arrayItemsReg = array();
        $arrayItemsRegPair = array();
        $arrayItemsOutstandingPair = array();
        if (sizeof($tmpTr) > 0) {
            //            arrPrintWebs($tmpTr);
            //            cekHere(sizeof($tmpTr));
            //            arrprint($historyFields[$currentState]);
            if (isset($historyFields[$currentState]['items'])) {
                $listTrID = array();
                foreach ($tmpTr as $row) {
                    $listTrID[] = $row->id;
                    $ind_reg = $row->indexing_registry != NULL ? blobDecode($row->indexing_registry) : array();
                    $arrayItemsReg[$row->id_master] = isset($ind_reg['items']) ? $ind_reg['items'] : 0;
                    $arrayItemsMasterToID[$row->id_master] = $row->transaksi_id;
                }
                //                arrPrint($arrayItemsReg);
                // membaca registry items
                $tr->setFilters(array());
                // $tr->addFilter("id in ('" . implode("','", $arrayItemsReg) . "')");
                $tr->setJointSelectFields("items,transaksi_id");
                $tr->addFilter("transaksi_id in ('" . implode("','", $listTrID) . "')");
                $tmpReg = $tr->lookupDataRegistries()->result();
                // cekLime($this->db->last_query());
                // matiHEre();
                foreach ($tmpReg as $rSpec) {
                    $arrayItemsRegPair[$rSpec->transaksi_id] = blobDecode($rSpec->items);
                }
                foreach ($arrayItemsRegPair as $trID => $tmp) {
                    foreach ($tmp as $pID => $etmp) {
                        if (isset($historyFields[$currentState]['items']['outstanding_items'])) {
                            foreach ($historyFields[$currentState]['items']['outstanding_items'] as $val) {
                                $arrayItemsOutstandingPair[$trID][$pID] = $etmp[$val];
                            }
                        }
                    }
                }
            }
            //            arrPrint($arrayItemsMasterToID);
            //            arrPrint($arrayItemsOutstandingPair);
            foreach ($tmpTr as $row) {
                if (!isset($validItems[$row->produk_id])) {
                    $validItems[$row->id_master][$row->produk_id] = 0;
                }
                if (!isset($validItemSends[$row->produk_id])) {
                    $validItemSends[$row->id_master][$row->produk_id] = 0;
                }

                $validItems[$row->id_master][$row->produk_id] += $row->valid_qty;
                $validItemSends[$row->id_master][$row->produk_id] += $row->produk_ord_jml - $row->valid_qty;

                if (!isset($extractedItems[$row->produk_id])) {
                    $extractedItems[$row->id_master][$row->produk_id] = array();
                }

                $no = 0;
                $srcKey = isset($historyFields[$currentState]['srcKey']) ? $historyFields[$currentState]['srcKey'] : array();

                if (isset($historyFields[$currentState]['fields'])) {
                    foreach ($historyFields[$currentState]['fields'] as $srcKey_0 => $srcAlias) {
                        $no++;
                        $val_main = isset($row->$srcKey_0) ? makeValue($srcKey_0, json_decode(json_encode($row), true), json_decode(json_encode($row), true)) : "";
                        //                        $mainData[$row->$srcKey][$srcKey_0] = formatField_he_format($srcKey_0, $val_main);

                        if (is_array($srcAlias)) {
                            $hisStep = $srcAlias['step'];
                            $hisKey = $srcAlias['key'];
                            //                            arrPrintWebs($row->ids_his);
                            if (isset($row->ids_his)) {
                                if ($hisKey == "nomer") {
                                    $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                    if ($returnVal == "") {
                                        $mainData[$row->$srcKey][$srcKey_0] = "-";
                                    }
                                    else {
                                        $mainData[$row->$srcKey][$srcKey_0] = $returnVal;
                                    }
                                }
                                else {
                                    $ids_his_decode = blobDecode($row->ids_his);
                                    if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                        $mainData[$row->$srcKey][$srcKey_0] = $ids_his_decode[$hisStep][$hisKey];
                                    }
                                    else {
                                        $mainData[$row->$srcKey][$srcKey_0] = "-";
                                    }
                                }
                            }
                            else {
                                $mainData[$row->$srcKey][$srcKey_0] = "-";
                            }
                        }
                        else {
                            //                            $mainData[$row->$srcKey][$srcKey_0] = formatField_he_format($srcKey_0, $val_main);
                            $mainData[$row->$srcKey][$srcKey_0] = $val_main;
                        }
                    }

                    // mereplace manual aoutstanding items...
                    $mainDataOutstandingItems[$row->$srcKey][] = array(
                        "valid_qty" => $row->valid_qty,
                        "label" => $row->produk_label,
                        "kode" => $row->produk_kode,
                        "produk_id" => $row->produk_id,
                    );

                }

                $arrAsem[$row->produk_id][] = $row->produk_kode;

                if (sizeof($detailsFields) > 0) {
                    foreach ($detailsFields as $fieldKey => $key) {
                        $val = makeValue($key, json_decode(json_encode($row), true), json_decode(json_encode($row), true));
                        $itemData[$row->$srcKey][$fieldKey][] = $val;
                        //                        $itemData[$row->$srcKey][$fieldKey][] = formatField_he_format($fieldKey, $val);

                        //                        foreach ($tmpStock as $temps) {
                        //                            if($row->produk_id==$temps->produk_id && $temps->cabang_id==$cabangID){
                        //                                $stocks[$cabangID][$row->produk_id] = $temps->jumlah;
                        //                            }
                        //                        }
                        //                        $itemData[$row->$srcKey]['stok'][] = $row->produk_id;

                        $itemData[$row->$srcKey]['stok'][] = isset($stocks[$cabangID][$row->produk_id]['jumlah']) ? $stocks[$cabangID][$row->produk_id]['jumlah'] : 0;
                    }
                }

            }
            //                        arrPrintWebs($arrayItemsOutstandingPair);
            // ======================================================================
            // memairingkan qty outstanding dengan masterID masing-masing.
            if (sizeof($mainData) > 0) {
                if (sizeof($mainDataOutstandingItems) > 0) {
                    foreach ($mainDataOutstandingItems as $masterID => $spec) {
                        if (array_key_exists($masterID, $mainData)) {

                            $hasil = "";
                            $sub_tmpOutstandingPair = 0;
                            foreach ($spec as $dSpec) {
                                $addSpec = "";
                                if (isset($arrayItemsMasterToID[$masterID])) {
                                    $pairTrID = $arrayItemsMasterToID[$masterID];
                                    $tmpOutstandingPair = isset($arrayItemsOutstandingPair[$pairTrID][$dSpec['produk_id']]) ? $arrayItemsOutstandingPair[$pairTrID][$dSpec['produk_id']] : "0";
                                    $sub_tmpOutstandingPair += ($tmpOutstandingPair * $dSpec['valid_qty']);

                                    $price = number_format($tmpOutstandingPair);
                                    $sum_price = number_format($tmpOutstandingPair * $dSpec['valid_qty']);
                                    /*
                                     * harga tidak ditampilin, permintaan dari san supaya hemat tempat saat di print, 16/12/2022
                                     */
                                    // $addSpec = "<span style='width:200px;' class='pull-right text-right'>@ $price  |  $sum_price</span>";
                                }

                                if ($hasil == "") {
                                    $hasil = $dSpec['valid_qty'] . "x " . $dSpec['label'] . " " . $dSpec['kode'] . $addSpec;
                                }
                                else {
                                    $hasil .= "<br>" . $dSpec['valid_qty'] . "x " . $dSpec['label'] . " " . $dSpec['kode'] . $addSpec;
                                }
                            }
                            //                            cekHere($hasil);
                            $mainData[$masterID]['outstanding_items'] = $hasil;
                            //                            $mainData[$masterID]['sub_outstanding_items'] = formatField_he_format("debet", $sub_tmpOutstandingPair);
                            $mainData[$masterID]['sub_outstanding_items'] = $sub_tmpOutstandingPair;
                            if (!isset($summary['sub_outstanding_items'])) {
                                $summary['sub_outstanding_items'] = 0;
                            }
                            $summary['sub_outstanding_items'] += $sub_tmpOutstandingPair;
                        }
                    }
                }
            }
        }
        else {
            cekmerah("TIDAK ada yang mau diekstrak");
        }
        //endregion
        //arrPrintPink($mainData);

        //region header label
        $headerFieldLabel = array();
        if (isset($historyFields[$currentState]['fields'])) {
            foreach ($historyFields[$currentState]['fields'] as $srcKey => $alias) {
                if (is_array($alias)) {
                    $headerFieldLabel[$srcKey] = isset($alias['label']) ? $alias['label'] : "-";
                }
                else {
                    $headerFieldLabel[$srcKey] = $alias;
                }
            }
        }
        //arrPrintPink($mainData);
        //        cekKuning(":: $currentState ::");
        //arrPrintWebs($headerFieldLabel);
        //endregion
        $addLink = null;
        $data = array(
            "mode" => $this->uri->segment(3),
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            //            "subTitle" => "<b> Out Standing $menuLabel <r>( periode " . (isset($date1) && isset($date2) ? date("d-M-Y", strtotime($date1)) . " s/d " . date("d-M-Y", strtotime($date2)) : "HINGGA HARI INI") . " )</r></b> ",
            "subTitle" => "<b> Outstanding $menuLabel",
            "arrayHistoryLabels" => $headerFieldLabel,
            "arrayHistory" => $mainData,
            "arrayAction" => $arrayAction,
            "arrayHistorySumField" => array(),
            "detailsFields" => $itemData,
            "arrayHistoryId" => array(),
            "action" => array(),
            "steps" => $historyFields,
            "arrayValid" => $arrayValid,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . $this->uri->segment(2) . $this->uri->segment(3) . "/" . $this->jenisTr . "/viewIncomplete/",
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $this->uri->segment(5),
            "marking_style" => $marking_style,
            "summary" => $summary,
        );

        $this->load->view("history", $data);
    }

    public function viewStatus()
    {
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $limit = 20;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $historyFields = isset($this->configUi[$jenisTr]['shortStatusFields']) ? $this->configUi[$jenisTr]['shortStatusFields'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $pairStepCode = isset($this->configUi[$jenisTr]['steps']) ? $this->configUi[$jenisTr]['steps'] : array();
        $addMainStep = isset($this->configUi[$jenisTr]['addMainStep']) ? $this->configUi[$jenisTr]['addMainStep'] : array();
        $regulerRoutesConfig = $this->config->item("heTransaksi_regulerRoutes") != NULL ? $this->config->item("heTransaksi_regulerRoutes") : array();
        $sourceInternalConnectConfig = $this->config->item("heTransaksi_source_internal_connect") != NULL ? $this->config->item("heTransaksi_source_internal_connect") : array();
        $sourceCenterConnectConfig = $this->config->item("heTransaksi_center_connect") != NULL ? $this->config->item("heTransaksi_center_connect") : array();


        $jenisTrConnect = heGetOriginConnectTCode($jenisTr);
        $jenisTrOriginConnect = heGetOriginTCode($jenisTr);
        $arrJenisTrMain = array($jenisTr);
        // ini mencari target koneknya
        if ($jenisTrConnect != NULL) {
            $arrJenisTrMain[] = $jenisTrConnect;
        }
        // ini mencari sumber koneknya
        if ($jenisTrOriginConnect != NULL) {
            if (!in_array($jenisTrOriginConnect, $sourceInternalConnectConfig)) {
                $arrJenisTrMain[] = $jenisTrOriginConnect;
            }
        }


        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['shortStatusFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }


        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $tmpCode = array();
        if (sizeof($pairStepCode) > 0) {
            foreach ($pairStepCode as $t => $tSpec) {
                $tmpCode[$t] = $tSpec['target'];
            }
        }
        if (sizeof($addMainStep) > 0) {
            foreach ($addMainStep as $t => $tSpec) {
                $tmpCode[$t] = $tSpec['target'];
            }
        }

        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal_activityMain");


        //region lookup histories
        $tr = new MdlTransaksi();
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";


        // kolom yang diambil dari tabel transaksi...
        $arrKolomTr = array(
            "id",
            "dtime",
            "nomer_top",
            "nomer",
            "jenis",
            "jenis_master",
            "jenis_top",
            "jenis_label",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "cabang_id",
            "cabang_nama",
            "cabang2_id",
            "cabang2_nama",
        );
        $arrJurnalStep = array();
        if (sizeof($regulerRoutesConfig) > 0) {
            foreach ($regulerRoutesConfig as $jenisMaster => $jSpec) {
                foreach ($jSpec as $step => $sSpec) {
                    $arrJurnalStep[$jenisMaster][$step] = $sSpec["debet"];
                }
            }
        }


        $cj = New ComJurnal_activityMain();
        $cj->setFilters(array());
        if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {
        }
        else {
            $cabang_id = $this->session->login['cabang_id'];
            $cabang_pusat = CB_ID_PUSAT;
            //            cekPink("origin konek $jenisTrOriginConnect");
            //            arrPrint($sourceCenterConnectConfig);
            if (in_array($jenisTrOriginConnect, $sourceCenterConnectConfig)) {
                //                cekPink("transaksi yang ada koneksi dengan PUSAT");
                $where = "((cabang_id='$cabang_id' AND cabang2_id='$cabang_pusat') OR (cabang_id='$cabang_pusat' AND cabang2_id='$cabang_id'))";
            }
            elseif (in_array($jenisTr, $sourceCenterConnectConfig)) {
                //                cekPink("transaksi yang ada koneksi dengan PUSAT");
                $where = "((cabang_id='$cabang_id' AND cabang2_id='$cabang_pusat') OR (cabang_id='$cabang_pusat' AND cabang2_id='$cabang_id'))";
            }
            else {
                //                cekPink("transaksi yang TIDAK ada koneksi dengan PUSAT");
                $where = "((cabang_id='$cabang_id' AND cabang2_id='$cabang_id') OR (cabang_id='$cabang_id' AND cabang2_id='$cabang_id'))";
            }

            $this->db->where($where);
        }

        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

            $subTitle_date = "";
            $subSubTitle_date = "";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }

        //        arrprint($arrJenisTrMain);
        $cj->addFilter("jenis_master in ('" . implode("','", $arrJenisTrMain) . "')");
        $tmp = $cj->lookupAll()->result();
        //        showLast_query("biru");
        //        cekHitam(sizeof($tmp));

        $tmpHistory = array();
        $tmpHistory_0 = array();
        $tmpHistory_waiting = array();
        $tmpHistory_activity = array();
        $tmpHistory_activityData = array();
        $trIDs = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {


                $tmpHistory_0[$tmpSpec->master_id][] = $tmpSpec;
                $tmpHistory_activity[$tmpSpec->master_id][] = $tmpSpec->activity;
                $tmpHistory_activityData[$tmpSpec->master_id][$tmpSpec->activity] = array(
                    "activity" => $tmpSpec->activity,
                    "master_id" => $tmpSpec->master_id,
                    "transaksi_no" => isset($tmpSpec->transaksi_no) ? $tmpSpec->transaksi_no : "",
                    "dtime" => $tmpSpec->dtime,
                    "fulldate" => $tmpSpec->fulldate,
                    "oleh_nama" => isset($tmpSpec->oleh_nama) ? $tmpSpec->oleh_nama : "",
                );

                $trIDs[$tmpSpec->master_id] = $tmpSpec->master_id;
                if ($tmpSpec->kredit > 0) {
                    if ($tmpSpec->activity != "request") {

                        $tmpHistory_waiting[$tmpSpec->master_id][] = $tmpSpec->activity;
                    }
                }
            }


            // pair dengan transaksi
            $tr = New MdlTransaksi();
            $tr->addFilter("id in ('" . implode("','", $trIDs) . "')");
            $tmpTr = $tr->lookupAll()->result();
            //            showLast_query("kuning");
            foreach ($tmpTr as $trSpec) {
                foreach ($arrKolomTr as $key) {
                    $subTr[$key] = isset($trSpec->$key) ? $trSpec->$key : "";
                }
                $tmpHistory[$trSpec->id] = (object)$subTr;
            }

            // pair dengan registry main
            $treg = New MdlTransaksi();
            $treg->setFilters(array());
            $treg->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
            // $treg->addFilter("param='main'");
            $treg->setJointSelectFields("main,transaksi_id");
            $tmpReg = $treg->lookupDataRegistries()->result();
            $pairRegistriesResult = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    $pairRegistriesResult[$regRow->transaksi_id] = blobDecode($regRow->main);
                }
            }
        }


        $tmpHist = $tmpHistory;
        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_topID = array();
        $arrayHistoryCanceled = array();
        $arrayHistoryDone = array();
        $arrayHistoryStatus = array();
        if (sizeof($tmpHist) > 0) {
            krsort($tmpHist);
            foreach ($tmpHist as $row) {
                if (isset($pairRegistriesResult[$row->id])) {
                    foreach ($pairRegistriesResult[$row->id] as $k => $v) {
                        if (!isset($row->$k)) {
                            $row->$k = $v;
                        }
                    }
                }

                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (strpos($fName, '+') !== false) {
                        //==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        $colValue = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "";
                    }
                    $tmp[$fName] = $colValue;
                    //                    $tmp1["id"] = $row->id;
                }

                $disabled = "";
                $arrActivity = array();
                $arrWaiting = array();

                if (isset($tmpHistory_activity[$row->id])) {
                    $arrActivity = $tmpHistory_activity[$row->id];
                }
                if (isset($tmpHistory_waiting[$row->id])) {
                    $arrWaiting = $tmpHistory_waiting[$row->id];
                }


                $status_next = "";
                if (isset($arrJurnalStep[$row->jenis_master])) {
                    foreach ($arrJurnalStep[$row->jenis_master] as $number => $value) {
                        $value_add = "";
                        $onclick = "";

                        if (in_array($value, $arrActivity)) {
                            if (in_array($value, $arrWaiting)) {
                                $disabled = "";
                                $class = "btn-warning";
                            }
                            else {

                                $dtime = $tmpHistory_activityData[$row->id][$value]['dtime'];
                                $oleh_nama = $tmpHistory_activityData[$row->id][$value]['oleh_nama'];
                                $transaksi_no = $tmpHistory_activityData[$row->id][$value]['transaksi_no'];
                                $transaksi_no_f = formatNota("nomer", $transaksi_no);

                                $disabled = "";
                                $class = "btn-success";
                                $value_add = " (by $oleh_nama, $transaksi_no_f, $dtime)";
                                $onclick = "onclick=\"showModal('" . base_url() . "Transaksi/viewResume/$transaksi_no','view resume for $transaksi_no_f')\"";
                            }
                        }
                        else {
                            $disabled = "";
                            $class = "btn-default";
                        }

                        $value_add = "";
                        if ($status_next == "") {
                            $status_next = "<input type='button' class='btn btn-sm $class' $disabled value='$value $value_add'
                                    $onclick
                                    >";
                            $status_next .= "<br>";
                        }
                        else {
                            $status_next = $status_next;
                            $status_next .= "<input type='button' class='btn btn-sm $class' $disabled value='$value $value_add'
                                    $onclick
                                    >";
                            $status_next .= "<br>";
                        }
                    }
                }
                $tmp['status_next'] = $status_next;


                $arrayHistory[$row->id] = $tmp;
                $arrayHistoryStatus[$row->id] = str_replace("<br>", "&nbsp;", $status_next);


            }

        }
        //endregion


        //region prepare params for viewer
        $trName = isset($this->configUi[$jenisTr]["label"]) ? $this->configUi[$jenisTr]["label"] : "";
        $data = array(
            "mode" => $this->uri->segment(3),
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "transaction status ",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistoryId" => $arrayHistory_ids,
            "arrayHistoryCanceled" => $arrayHistoryCanceled,
            "action" => isset($action) && sizeof($action) > 0 ? $action : array(),
            "steps" => isset($steps) && sizeof($steps) > 0 ? $steps : array(),
            "stepLabels" => isset($stepLabels) && sizeof($stepLabels) > 0 ? $stepLabels : array(),
            "stepLinks" => isset($stepLinks) && sizeof($stepLinks) > 0 ? $stepLinks : array(),
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . $this->uri->segment(2) . "/viewIncomplete/" . $this->uri->segment(3) . "/" . $this->jenisTr,
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => isset($addLink) ? $addLink : "",
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            "arrayHistoryStatus" => $arrayHistoryStatus,
            "customStatusLayout" => true,
            //            "customStatusLayout" => false,
        );
        //endregion


        $this->load->view("history", $data);
    }

    public function index()
    {
        $starttime = microtime(true);
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }

        $sesionReplacer = replaceSession();
        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $paymentConfig = isset($this->configUi[$jenisTr]['paymentConfig']) ? $this->configUi[$jenisTr]['paymentConfig'] : false;
        $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array();
        $kepoinFields = isset($this->configUi[$jenisTr]['shortKepoinFields']) ? $this->configUi[$jenisTr]['shortKepoinFields'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $shoppingCartPerTransaksi = isset($this->configUi[$jenisTr]['shoppingCartPerTransaksi']) ? $this->configUi[$jenisTr]['shoppingCartPerTransaksi'] : 0;
        $shoppingCartPerTransaksiBtn = isset($this->configUi[$jenisTr]['shoppingCartPerTransaksiBtn']) ? $this->configUi[$jenisTr]['shoppingCartPerTransaksiBtn'] : 0;
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $steps = $this->configUi[$jenisTr]['steps'];
        //region link to add new transaction

        //tambahin hak akses custom

        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            $isDisableMakeTrans = isset($this->configUi[$this->jenisTr]['isDisableMakeTrans']) ? $this->configUi[$this->jenisTr]['isDisableMakeTrans'] : false;
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
                //                redirect($targetUrl);
            }

            if ($isDisableMakeTrans) {
                $addLink = null;
                //                $_SESSION['errMsg'] = "test error dari 1982";
                //                arrPrint( $this->session->errMsg );
            }
            else {
                $addLink = array(
                    "link" => $targetUrl,
                    "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
                );
            }

        }
        else {
            $addLink = null;
        }
        //endregion

        //------------------------------
        $arrWarningPlaceTrans = cekOpnameAktif($jenisTr, $this->configUi, my_cabang_id());
        //------------------------------

        $mb = New MobileDetect();
        // $isMob = $mb->isMobile();
        $isMob = false;
        $getStep = isset($_GET['step']) ? $_GET['step'] : ($isMob == true ? 2 : 1);

        $forceMobile = isset($_SESSION['login']['forceMobile']) ? $_SESSION['login']['forceMobile'] : 0;

        // cekHere("$isMob || $forceMobile");

        $baseUrl = base_url();
        // if ($forceMobile == 0) {
        $link_undoneList = MODUL_PATH . "Transaksi/viewUndoneItemsIndex/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";
        // }
        // else {
        //     $link_undoneList = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";
        // }
        $link_historyList = MODUL_PATH . "History/showData/$jenisTr/?limit=10";

        $scriptBottom = "";
        $scriptBottom .= "<script>
             var viewundoneList = function () {
                         top.$('#undoneList').html(\"<div class='row' style='margin-top: 14px; padding-bottom: 14px;'> <span class='col-lg-12 text-center'><img width='5%' src='$baseUrl/assets/images/loading_16_p.gif'></span> </div>\");
                     setTimeout(function () {
                                      if (top.$('#undoneList').find('img').length > 0) {
                                          top.$('#undoneList').load('$link_undoneList', function(){viewundoneList})
                                          }
                                      },1000);
                     }\n
                     viewundoneList()
\n\n
                    var viewhistoryList = function () {
                        top.$('#historyList').html(\"<div class='row' style='margin-top: 14px; padding-bottom: 14px;'> <span class='col-lg-12 text-center'><img width='5%' src='$baseUrl/assets/images/loading_16_p.gif'></span> </div>\");
                        setTimeout(function () {
                              if (top.$('#historyList').find('img').length > 0) {
                                  top.$('#historyList').load('$link_historyList', function(){viewhistoryList})
                         }
                        },1000);
                     }\n
                     viewhistoryList()\n
                     
         </script>
        ";

        $data = array(
            "mode" => "index",
            // "isMobile"                       => $isMob,
            "errMsg" => $this->session->errMsg,
            "template" => $this->configUi[$jenisTr]["template"],
            "title" => $this->configUi[$jenisTr]["label"],
            "subTitle" => $this->configUi[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            'addLink' => $addLink,

            "scriptBotto" => isset($scriptBottom) ? $scriptBottom : "",
            "steps" => $steps,
            "allSteps" => $this->allSteps,
            "arrWarningPlaceTrans" => isset($arrWarningPlaceTrans) ? $arrWarningPlaceTrans : array(),

        );
        $this->load->view("transaksi", $data);
    }

    public function selectPaymentExternSrc()
    {
        $targetJenis = $this->uri->segment(3);

        heInitCart($targetJenis);


        //==dapatkan srcJenis
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
        $readerDueDate = isset($this->configUi[$targetJenis]['dueDateReader']) ? $this->configUi[$targetJenis]['dueDateReader'] : false;

        if (sizeof($paymentSources) > 0) {
            foreach ($paymentSources as $src => $mainSpecs) {
                //                cekHere("$src");;
                if (sizeof($mainSpecs) > 0) {

                    foreach ($mainSpecs as $step => $sSpecTemp) {
                        //                        cekHere("$step");
                        //                        arrprint($sSpecTemp);
                        $sCtr = 0;
                        foreach ($sSpecTemp as $sSpec) {

                            //                            arrPrint($sSpec);
                            //                            cekHitam($src);
                            if (isset($sSpec['jenisTarget']) && $sSpec['jenisTarget'] == $targetJenis) {
                                $srcJenis = $sSpec['jenisSrc'];
                                $rawSrcJenis = $src;
                                $srcIndex = $sCtr;
                                $srcStep = $step;
                                //                            cekhijau($srcJenis . " memenuhi syarat");
                            }
                            else {
                                $srcJenis = isset($sSpec['jenisSrc']) ? $sSpec['jenisSrc'] : "";
                                $rawSrcJenis = "";
                                $step = "";
                                $srcIndex = "";
                                $srcStep = "";
                                //                            cekmerah($srcJenis . " TIDAK memenuhi syarat");
                            }
                        }
                        $sCtr++;

                    }
                }
            }
        }

        //        arrPrint($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]);
        //cekHitam("$rawSrcJenis |$srcStep| $srcIndex");
        //        die();
        $defItemLabels = array(
            "extern_nama" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] : "",
            //                "nomer"=>"receipt number",
            //                "fulldate"=>"date",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "tagihan_valas" => "due amount",
            "terbayar_valas" => "paid",
            "diskon_valas" => "discount",
            "sisa_valas" => "due remain",
        );
        $itemLabels = (isset($this->configUi[$targetJenis]["shoppingCartReferenceExternFields"])) ? $this->configUi[$targetJenis]["shoppingCartReferenceExternFields"] : $defItemLabels;
        $itemLabels["due_date"] = "due date";
        $itemLabels["aging"] = "aging(days)";
        $itemLabels["over_due"] = "over due";

        //		die();
        //==dapatkan daftar kolom dari srcJenis
        //        $historyFields = $this->configUi[$srcJenis]['shortHistoryFields'];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("sisa>0");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        //        cekHere($this->db->last_query());
        $tmpSrcDue = array();
        $dueEmployee = array();
        if ($readerDueDate) {
            $tr->setFilters(array());
            $tr->addFilter("status='1'");
            $tmpSrcDue = $tr->lookupAllDueDate()->result();
            $tempDataDues = array();
            $validDate = array();
            foreach ($tmpSrcDue as $tmpSrcDue_tmp) {
                $tempDataDues[$tmpSrcDue_tmp->customers_id][] = array(
                    "due_date" => $tmpSrcDue_tmp->due_date,
                    "aging_dtime" => $tmpSrcDue_tmp->dtime,
                );

            }
            //            arrPrint($tempDataDues);
            $dtime_now = strtotime(date("Y-m-d"));
            foreach ($tempDataDues as $cus_id => $tempDataDues_0) {
                $dueVal = array();
                $dtimeVal = array();
                foreach ($tempDataDues_0 as $dtime_val) {

                    $keyIndex = strtotime($dtime_val['due_date']);
                    $dueVal[] = $keyIndex;
                    $dtimeVal[$keyIndex] = array(
                        "due_date" => $dtime_val['due_date'],
                        "aging" => $dtime_val['aging_dtime'],
                    );

                }
                asort($dueVal);
                $key_index = $dueVal['0'];
                $date_due = $dtimeVal[$key_index]['due_date'];
                $aging = $dtimeVal[$key_index]['aging'];

                //cekHere("$date_due || $aging");
                if ($dtime_now > $key_index) {
                    $dueEmployee[$cus_id] = array(
                        "due_date" => formatField_he_format("dtime", $date_due),
                        "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                        "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                    );
                }
            }


        }
        //matiHere();
        // cekHere($targetJenis);
        //        arrPrint($dueEmployee);
        $items = array();
        $externs = array();
        $tagihans = array();
        $terbayars = array();
        $sisas = array();
        $diskons = array();
        $tagihans_valas = array();
        $terbayars_valas = array();
        $sisas_valas = array();
        $diskons_valas = array();
        $srcLabel = "";
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {
                $tmp = array();
                //                foreach($historyFields as $fName=>$label){
                //                    $tmp[$fName]=$row->$fName;
                //                }
                $classMarking = "";
                if (isset($dueEmployee[$row->extern_id])) {
                    $classMarking = "bg-danger";
                    //                    cekHere($row->extern_nama);
                }

                if (!in_array($row->extern_id, $externs)) {
                    if (!isset($tagihans[$row->extern_id])) {
                        $tagihans[$row->extern_id] = 0;
                        $terbayars[$row->extern_id] = 0;
                        $diskons[$row->extern_id] = 0;
                        $sisas[$row->extern_id] = 0;
                        $tagihans_valas[$row->extern_id] = 0;
                        $terbayars_valas[$row->extern_id] = 0;
                        $sisas_valas[$row->extern_id] = 0;
                        $diskons_valas[$row->extern_id] = 0;

                    }

                    $tmp = (array)$row;
                    $tmp["link"] = base_url() . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
                    $tmp["due_date"] = isset($dueEmployee[$row->extern_id]['due_date']) ? formatField_he_format("dtime", $dueEmployee[$row->extern_id]['due_date']) : "-";
                    $tmp["aging"] = isset($dueEmployee[$row->extern_id]['aging']) ? $dueEmployee[$row->extern_id]['aging'] : "-";
                    $tmp["over_due"] = isset($dueEmployee[$row->extern_id]['over_due']) ? $dueEmployee[$row->extern_id]['over_due'] : "-";
                    $tmp["class_marking"] = $classMarking;
                    //                    $tmp["class_marking"] = "bg-danger";
                    //                    $tmp["tagihan"] = $tagihans[$row->extern_id];
                    $items[$row->extern_id] = $tmp;
                    $externs[] = $row->extern_id;
                    $externName = $row->extern_nama;
                }
                $tagihans[$row->extern_id] += isset($row->tagihan) ? $row->tagihan : 0;
                $terbayars[$row->extern_id] += isset($row->terbayar) ? $row->terbayar : 0;
                $sisas[$row->extern_id] += isset($row->sisa) ? $row->sisa : 0;
                $diskons[$row->extern_id] += isset($row->diskon) ? $row->diskon : 0;
                $tagihans_valas[$row->extern_id] += isset($row->tagihan_valas) ? $row->tagihan_valas : 0;
                $terbayars_valas[$row->extern_id] += isset($row->terbayar_valas) ? $row->terbayar_valas : 0;
                $sisas_valas[$row->extern_id] += isset($row->sisa_valas) ? $row->sisa_valas : 0;
                $diskons_valas[$row->extern_id] += isset($row->diskon_valas) ? $row->diskon_valas : 0;
                $srcLabel = $row->label;
            }
            //            arrPrint($items);
            foreach ($items as $externID => $iSpec) {
                $items[$externID]['tagihan'] = $tagihans[$externID];
                $items[$externID]['terbayar'] = $terbayars[$externID];
                $items[$externID]['diskon'] = $diskons[$externID];
                $items[$externID]['sisa'] = $sisas[$externID];
                $items[$externID]['tagihan_valas'] = $tagihans_valas[$externID];
                $items[$externID]['terbayar_valas'] = $terbayars_valas[$externID];
                $items[$externID]['diskon_valas'] = $diskons_valas[$externID];
                $items[$externID]['sisa_valas'] = $sisas_valas[$externID];
            }
        }

        //        cekHitam("$srcStep||$srcIndex");
        //		cekkuning("rawSrcJenis: ".$rawSrcJenis);
        //        arrPrint($this->uri->segment_array());
        $data = array(
            "mode" => $this->uri->segment(2),
            //            "template" => $this->configUi[$jenisTr]["template"],
            "title" => $this->configUi[$targetJenis]["label"],
            "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
            "items" => $items,
            "srcLabel" => $srcLabel,
            "itemLabels" => $itemLabels,
            "jenisTr" => $this->jenisTr,
            //            "dueDate"    =>$dueEmployee,
        );
        //        arrprint($data);

        if (!isset($_GET['json'])) {
            $this->load->view("transaksi", $data);
        }
        else {
            //            echo json_encode($data);

            arrPrint($data);
        }


        //        die("selecting payment src...");
    }

    public function selectPaymentSrc()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $externID = $this->uri->segment(5);
        $selectedTrID = $this->uri->segment(6);

        $this->load->model("MdlTransaksi");

        // arrPrintPink($this->uri->segment_array());
        $scriptBottom = "";
        $scriptTrigger = "";
        // if(isset($selectedTrID)){
        $scriptTrigger .= "$('.chRadio').trigger('click')";
        // }

        $jenisTr = $targetJenis = $this->jenisTr;
        $cCode = $this->jenisTr;

        // cekHere("cCode:: $cCode " . __METHOD__ . " line " . __LINE__);
        heInitCart($targetJenis);
        $this->load->helper("he_versi_history_old");

        $detailResetList = array(
            "items",
            //            "out_detail",
            //            "out_detail2",
            "tableIn_detail",
            "tableIn_detail2",
            "tableIn_detail_values",
            "tableIn_detail2_sum",
            "tableIn_detail_values2_sum",
        );
        foreach ($detailResetList as $sSName) {
            $_SESSION[$cCode][$sSName] = null;
            unset($_SESSION[$cCode][$sSName]);
        }


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


        //==dapatkan srcJenis
        $stepCode = $this->configUi[$targetJenis]['steps'][1]['target'];
        $steps = $this->configUi[$targetJenis]['steps'];
        $tagihanSrc = isset($this->configUi[$targetJenis]['tagihanSrc']) ? $this->configUi[$targetJenis]['tagihanSrc'] : "sisa";
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
        $returnRoutes = null != ($this->config->item("transaksi_returnRoutes")) ? $this->config->item("transaksi_returnRoutes") : array();
        $readerDueDate = isset($this->configUi[$targetJenis]['dueDateReader']) ? $this->configUi[$targetJenis]['dueDateReader'] : false;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        $prePrint = isset($steps[1]['prePrint']) ? $steps[1]['prePrint'] : "";

        $ppnDisabledConfig = isset($this->configUi[$targetJenis]['ppnDisabled']) ? $this->configUi[$targetJenis]['ppnDisabled'] : array();

        $jenisSrc = null;

        //		arrprint($paymentSources);die();
        if (sizeof($paymentSources) > 0) {
            foreach ($paymentSources as $src => $sSpec) {
                $payConfigs = $paymentSources[$src];
                if (sizeof($payConfigs) > 0) {
                    //                    $sCtr = 0;
                    foreach ($payConfigs as $paymentSrcConfigTmp) {
                        $sCtr = 0;
                        foreach ($paymentSrcConfigTmp as $paymentSrcConfig) {
                            if (isset($paymentSrcConfig['jenisTarget']) && $paymentSrcConfig['jenisTarget'] == $targetJenis) {
                                $srcJenis = $paymentSrcConfig['jenisSrc'];
                                $rawSrcJenis = $src;
                                $srcIndex = $sCtr;
                                $jenisSrc = $srcJenis;
                            }
                            $sCtr++;
                        }

                    }
                }
            }
        }

        $references = array();
        if ($jenisSrc != null) {
            if (isset($returnRoutes[$jenisSrc])) {
                //                $retCode = $returnRoutes[$jenisSrc];
                //
                //
                //                $trr = new MdlTransaksi();
                //                $trr->setFilters(array());
                //                $trr->addFilter("param='main'");
                //                $trr->addFilter("jenis='$retCode'");
                //                $tmpR = $trr->lookupRegistries_joined()->result();
                //                //cekHere($this->db->last_query());
                //
                //                if (sizeof($tmpR) > 0) {
                //                    foreach ($tmpR as $row) {
                //                        $main = blobDecode($row->values);
                //                        //                        arrprint($main);
                //                        $references[$main['referenceID']] = array(
                //                            "id" => $row->transaksi_id,
                //                            "nomer" => $row->nomer,
                //                            "refID" => $main['referenceID'],
                //                            "refJenis" => $main['referenceJenis'],
                //                            "refNum" => $main['referenceNomer'],
                //                            "harga" => isset($main['harga']) ? $main['harga'] : 0,
                //                            "nett" => isset($main['nett']) ? $main['nett'] : 0,
                //
                //
                //                        );
                //                    }
                //                }
            }
        }

        //        arrprint($references);
        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();


        //==dapatkan daftar kolom dari srcJenis
        //        $historyFields = $this->configUi[$srcJenis]['shortHistoryFields'];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("sisa>0");

        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($targetJenis)->result();
        //        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        // cekKuning(__LINE__. " | " .$this->db->last_query());
        //        arrPrint($tmpSrc);
        if ($readerDueDate) {
            $tr->setFilters(array());
            $tmpSrcDue = $tr->lookupAllDueDate()->result();
            //        arrPrint($tmpSrcDue);
            if (sizeof($tmpSrcDue) > 0) {
                foreach ($tmpSrcDue as $tmpSrccDue_0) {
                    $mainDueDate[$tmpSrccDue_0->transaksi_id] = $tmpSrccDue_0->due_date;
                }
            }
        }

        $externName = "";
        $items = array();
        $tempDueDate = array();
        $ppnDisabled = array();
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {
                $tmp = array();
                //                foreach($historyFields as $fName=>$label){
                //                    $tmp[$fName]=$row->$fName;
                //                }
                $jenis = $row->jenis;
                $transaksi_id = $row->id;
                if ($readerDueDate) {
                    $tempDueDate = blobDecode($row->ids_prev);
                    $dueTime = array();
                    if (sizeof($tempDueDate) > 0) {
                        $time_due = array();
                        foreach ($tempDueDate as $k => $spdID) {
                            $time_due[] = strtotime($mainDueDate[$spdID]);
                            $dueTime[strtotime($mainDueDate[$spdID])] = $mainDueDate[$spdID];
                        }
                        asort($time_due);
                        $key_due = $time_due[0];
                    }
                    if ($key_due > 0) {
                        $date_now = strtotime(date("Y-m-d"));
                        $dateValid = strtotime($dueTime[$key_due]);
                        //                        cekHijau("$dateValid >$date_now");
                        if ($dateValid < $date_now) {
                            //                            cekHere("$dateValid >$date_now");
                            $tmp['class_bg'] = "bg-danger";
                        }
                        else {
                            $tmp['class_bg'] = "";
                        }
                    }
                    else {
                        $tmp['class_bg'] = "";
                    }

                    $tmp['due_date'] = isset($dueTime[$key_due]) ? formatField_he_format("dtime", $dueTime[$key_due]) : "-";
                    $tmp['aging'] = isset($dueTime[$key_due]) && umurDay($dueTime[$key_due]) > 0 ? umurDay($dueTime[$key_due]) : "0";
                }
                else {
                    $tmp['class_bg'] = "";
                }


                $tmp['refID'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refID'] : "";
                $tmp['refNum'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refNum'] : "";
                $tmp['refValue'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['nett'] : "";

                if (strlen($prePrint) > 10) {
                    $linkPrint = base_url() . "$prePrint/$jenis/$transaksi_id";
                    $tmp['prePrint'] = "<button type='button' class='btn btn-link' onclick=\"window.open('$linkPrint')\"><i class='fa fa-print'></i></button>";
                }

                if (isset($ppnDisabledConfig['enabled']) && $ppnDisabledConfig['enabled'] == true) {
                    //                    if (($row->ppn_sisa > 0) && ($row->ppn_status == 1)) {
                    if (($row->ppn_status == 1)) {
                        $ppnDisabled[] = $row->transaksi_id;
                        $tmp['notes'] = isset($ppnDisabledConfig['notes']) ? "<span style='color:red;font-size:10px;'>" . $ppnDisabledConfig['notes'] . "</span>" : "-";
                    }
                }

                $items[] = (array)$row + $tmp;
                $externName = $row->extern_nama;
            }
        }
        //arrPrint($items);
        $cCode = "_TR_" . $targetJenis;
        //arrPrint($ppnDisabled);
        $defaultItemLabels = array(
            "extern_label2" => "jenis",
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        );

        $jQueryCustom = isset($this->configUi[$this->jenisTr]['jQueryCustom']) ? $this->configUi[$this->jenisTr]['jQueryCustom'] : "";

        if (sizeof($jQueryCustom) > 0) {
            $scriptBottom .= $jQueryCustom;
        }


        $defSisa = isset($_SESSION[$cCode]['main']['sisa']) ? $_SESSION[$cCode]['main']['sisa'] : 0;
        $data = array(
            "mode" => $this->uri->segment(3),
            "title" => $this->configUi[$targetJenis]["label"],
            "subTitle" => $externName,
            "jenisTr" => $targetJenis,
            "items" => $items,
            "prePrint" => $prePrint,
            "itemLabels" => isset($this->configUi[$targetJenis]["shoppingCartReferenceFields"]) ? $this->configUi[$targetJenis]["shoppingCartReferenceFields"] : $defaultItemLabels,
            "selectProcessor" => $this->configUi[$targetJenis]["selectorProcessor"],
            "paymentSubtitle" => "details for " . $this->configUi[$this->jenisTr]['steps'][1]['label'],
            "btnLabel" => "continue " . $this->configUi[$this->jenisTr]['steps'][1]['label'],
            "ses_outMaster" => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
            "ses_items" => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
            // -----------
            "actionTarget" => "top.$('#result').load('" . MODUL_PATH . "Transaksi/validate/" . $targetJenis . "?rawPrev=$rawPrevURL');",
            "columnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/nilai_entry",
            "bankColumnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/paymentMethod_cash",
            // -------------------
            "selectedBankID" => isset($_SESSION[$cCode]['main']['cash_account']) ? $_SESSION[$cCode]['main']['cash_account'] : 0,
            "dueDateReader" => $readerDueDate,
            "tagihanSrc" => $tagihanSrc,
            "scriptBottom" => $scriptBottom,
            "tagihanValue" => isset($_SESSION[$cCode]['main'][$tagihanSrc]) ? $_SESSION[$cCode]['main'][$tagihanSrc] : $defSisa,
            "isPaymentRadioSelect" => isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false,
            "ppnDisabled" => $ppnDisabled,
            "scriptTrigger" => $scriptTrigger,
        );

        if (!isset($_GET['json'])) {
            $this->load->view("transaksi", $data);
        }
        else {
            return json_encode($data);
        }

        //        die("selecting payment src...");
    }

    public function viewIncomplete()
    {


        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;

        $historyFields = $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        //
        //region lookup on-going transactions
        $currentState = 99;
        $progressFields = $historyFields;
        $progressFields['items'] = "detail items";
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array(
            "99" => "all",
            //            "0" => "canceled",
        );
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        $stepLinks = array(
            "99" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/99?date1=$date1&date2=$date2",
            //            "0"  => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/0?date1=$date1&date2=$date2",
            "0" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/0",
        );


        $arrayOnprogress = array();
        $arrayOnprogressbyState = array();


        if (sizeof($steps) > 1) {
            $stepCodes = array();
            //arrPrint($this->accessList);
            $jmlStep = count($steps);
            $indsteps = "(";
            $arrFilters = array();
            if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList[$jenisTr]) > 0) {
                foreach ($this->accessList[$jenisTr] as $stepNumber => $stepSpec) {
                    if ($stepNumber <= $jmlStep) {
                        foreach ($stepSpec as $targetCode => $filters) {
                            $indsteps .= "'$targetCode',";
                            $stepCodes[] = $targetCode;
                            if ($filters['allowFollowUp'] == "true") {
                                $arrFilters["allowFollowUp"][] = $targetCode;
                            }

                        }
                    }
                }
            }

            $indsteps = rtrim($indsteps, ",");
            $indsteps .= ")";
            $stepLabels ["0"] = "canceled";

            if ($steps[1]['userGroup'] == $this->session->login['jenis']) {
                $stepLabels[-1] = "<span class='glyphicon glyphicon-plus'></span>";
                // $stepLinks[-1] = MODUL_PATH . $this->uri->segment(2) . "/createForm/" . $this->uri->segment(3) . "?date1=$date1&date2=$date2";
                $stepLinks[-1] = MODUL_PATH . "Create/index/" . $this->jenisTr . "?date1=$date1&date2=$date2";
            }


            //            $currentState = $this->uri->segment(4) > 0 ? $this->uri->segment(4) : 0;
            if (strlen($this->uri->segment(5)) > 0) {
                $currentState = $this->uri->segment(5);
            }


            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
            //            $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
            //            $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
            //            $tr->addFilter("jenis='" . $steps[1]['target'] . "'");
            $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
            $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");

            //region date filter
            //            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            //            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
            if (isset($_GET['date1'])) {
                $this->db->where("fulldate>='" . $_GET['date1'] . "'");
                $this->db->where("fulldate<='" . $_GET['date2'] . "'");
            }
            if (isset($_GET['search'])) {
                $tr->setKeyWord($searchStr);
            }

            //endregion

            //cekBiru($currentState);
            $tr->addFilter("next_substep_code<>''");
            if ($currentState < 99) {
                $tr->addFilter("sub_step_number='$currentState'");
                $tr->addFilter("valid_qty>0");
            }
            else {
                $tr->addFilter("sub_step_number>0");
                $tr->addFilter("valid_qty>0");
            }
            if (sizeof($arrFilters) > 0) {
                //                $tr->addFilter("next_step_code in $indsteps");
            }
            else {
                //                $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
            }

            //            $tmpHist = $tr->lookupUndoneEntries_joined($this->session->login['cabang_id'], $this->session->login['gudang_id'])->result();

            $tmpHist = $tr->lookupUndoneEntries_joined(replaceSession())->result();
            //            cekHere($this->db->last_query());
            //            arrPrintWebs($tmpHist);

            $selectedTopID = isset($_GET['topID']) ? $_GET['topID'] : 0;


            $rowCtr = 0;
            if (sizeof($tmpHist) > 0) {
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrProduk = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->transaksi_id;
                    $arrTransTopID[] = $row->id_top;
                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $hisSpec) {
                            $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                "step" => $hisSpec['step'],
                                "trID" => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }

                    $arrProduk[$row->transaksi_id] = array(
                        "id" => $row->produk_id,
                        "nama" => $row->produk_nama,
                        "label" => $row->produk_label,
                        "kode" => $row->produk_kode,
                        "valid_qty" => $row->valid_qty,
                        "master_id" => $row->id_master,
                    );
                }
                $tmpReg_result = array();
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());
                // $trReg->addFilter("param='main'");


                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $trReg->setJointSelectFields("main,transaksi_id");
                // $tmpReg = $trReg->lookupRegistries()->result();
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        $tmpReg_result[$regRow->transaksi_id]["main"] = blobDecode($regRow->main);
                        // arrPrint($regRow);
                        // $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
                    }
                }

                // matiHEre();
                foreach ($tmpHist as $row) {
                    if (sizeof($pairRegistries) > 0) {
                        if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                            foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                        }
                    }

                    $rowCtr++;
                    $tmp = array();
                    foreach ($historyFields as $fName => $fLabel) {

                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);

                        if ($fName == "no") {
                            $tmp[$fName] = formatField_he_format($fName, $rowCtr);
                        }
                    }
                    // menambah manual detail/items barang
                    if (isset($arrProduk[$row->transaksi_id])) {
                        $tmp['items'] = $arrProduk[$row->transaksi_id]["valid_qty"] . "x ";
                        $tmp['items'] .= $arrProduk[$row->transaksi_id]["label"] . " ";
                        $tmp['items'] .= $arrProduk[$row->transaksi_id]["kode"];

                        $linkOutstanding = MODUL_PATH . "Transaksi/viewOutstanding/$jenisTr/transaksi/$jenisTr?trID=" . $arrProduk[$row->transaksi_id]["master_id"];
                        $tmp['items'] .= "<br><br> <a class='btn btn-primary btn-block' title='lihat selengkapnya' target='_blank'
                            href='$linkOutstanding'>
                            <span class=''>click to view complete...</span></a>";
                    }
                    else {
                        $tmp['items'] = "-";
                    }


                    if ($row->sub_step_number > 0) {
                        $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                        $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $jenisTr);
                    }
                    else {
                        $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                    }

                    $tmp['action'] = "";
                    $nextStepNum = ($row->next_substep_num);
                    $currentStepNum = ($row->sub_step_number);
                    $nextStepCode = $row->next_step_code;
                    $allowJoin = isset($this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                    $allowFollowup = false;
                    if ($row->sub_step_number > 0) {

                        if (placeCanFollowupTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $currentStepNum)) {
                            //                        if (in_array($this->configUi[$jenisTr]['steps'][$currentStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];

                        }
                    }

                    if (isset($this->configUi[$jenisTr]['steps'][$nextStepNum])) {
                        //                        cekHitam("masooox $nextStepNum ".$row->sub_step_number);
                        if (isset($this->accessList[$jenisTr])) {
                            if (isset($this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                                $allowFollowup = $this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                                $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                            }


                        }
                        else {
                            if (placeCanFollowupTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $nextStepNum)) {
                                //                        if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                                $allowFollowup = true;
                                $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];

                            }
                        }

                    }

                    if ($allowFollowup) {
                        $stepLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['label'];

                        $followupLink = "top.$('#result').load('" . base_url() . "Transaksi/followupPrePreview/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                        $tmp['action'] = "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                    }


                    if ($currentState > 0) {
                        if (isset($tmp['jenis_label'])) {
                            $clickEvent = "";
                            if ($row->id_top == $selectedTopID) {
                                $checked = "checked";
                            }
                            else {
                                $clickEvent = "location.href='" . base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?topID=" . $row->id_top . "';";
                                $checked = "";
                            }

                            $tmp['jenis_label'] = "<label><input type='checkbox' name='oID[]' $checked onclick=\"$clickEvent\" value='" . $row->transaksi_id . "'>" . $tmp['jenis_label'] . "</label>";
                            //                                echo "topID:".$row->id_top."<br>";
                        }
                    }

                    $arrayOnprogress[] = $tmp;
                    $arrayOnprogressbyState[$row->sub_step_number][] = $tmp;


                }
            }
        }
        else {
            $arrayOnprogress = array();
        }

        //arrPrint($arrayOnprogress);

        //endregion

        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion
        //arrPrint($addLink);
        //
        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(3),
            "isMobile" => $isMob,
            "errMsg" => $this->session->errMsg,
            "template" => $this->configUi[$jenisTr]["template"],
            "title" => "incomplete " . $this->configUi[$jenisTr]["label"],
            //            "subTitle"          => $this->configUi[$jenisTr]["label"] . " with status '" . $stepLabels[$currentState] . "'",
            "subTitle" => $stepLabels[$currentState] . "'",
            "jenisTr" => $jenisTr,
            "jenisTransaksi" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            //            "selectorCaller" => base_url() . "_selectorItem/" . $this->configUi[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["selectorModel"],
            "selectorCaller" => MODUL_PATH . $this->configUi[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["selectorModel"],
            "selectorLabel" => $this->configUi[$jenisTr]["selectorLabel"],
            //            "pihakCaller" => base_url() . "_selectorPihak/" . $this->configUi[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModel"],
            "pihakCaller" => MODUL_PATH . $this->configUi[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModel"],
            "pihakCallerDelete" => MODUL_PATH . "_processPihak/remove/$jenisTr",
            // "pihakLabel"=>$this->configUi[$jenisTr]["pihakLabel"],
            "pihakLabel" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : $this->configUi[$jenisTr]["pihakLabel"],

            "arrayProgressLabels" => $progressFields,
            "arrayOnProgress" => $arrayOnprogress,
            "arrayOnProgressByState" => $arrayOnprogressbyState,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "currentState" => $currentState,
            "alternateLink" => MODUL_PATH . "/History" . "/viewHistory/" . $this->jenisTr,
            "alternateLinkCaption" => $this->configUi[$jenisTr]["label"] . " histories " . "<span class='glyphicon glyphicon-arrow-right'></span>",
            //==untuk keperluan followup many-to-one
            "allowJoin" => isset($allowJoin) ? $allowJoin : false,
            "actionLabel" => isset($actionLabel) ? $actionLabel : "",
            "_nextStepNum" => isset($nextStepNum) ? $nextStepNum : "",
            "_currentStepNum" => $currentState,
            "followupBase" => MODUL_PATH . "FollowUp/followupPrePreview/",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
        );
        //        $data= array();
        //endregion


        $this->load->view("history", $data);
    }

    public function viewIncompleteStepAntarCabang()
    {

        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
        $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $stepHistoryFields = isset($this->configUi[$jenisTr]['shortStepHistoryFields']) ? $this->configUi[$jenisTr]['shortStepHistoryFields'] : array();
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();


        $tmpMasterIDExt = array();
        $arrJenisStepRslt = array();
        $arrayOnProgressView = array();
        if (sizeof($stepHistoryFields) > 0) {
            if (strlen($connectTo) > 0) {
                $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
                $transaksiConfig = isset($this->configUi[$jenisTr]['steps']) ? $this->configUi[$jenisTr]['steps'] : array();
                $transaksiConnectConfig = isset($this->configUi[$connectTo]['steps']) ? $this->configUi[$connectTo]['steps'] : array();
                $transaksiStepConfig = isset($this->configUi[$jenisTr]['steps'][1]) ? $this->configUi[$jenisTr]['steps'][1] : array();
                $jenisTrTop = isset($transaksiStepConfig['target']) ? $transaksiStepConfig['target'] : "";


                $transaksiExtendConnect = isset($this->configUi[$jenisTr]['extConnectTo']) ? $this->configUi[$jenisTr]['extConnectTo'] : array();
                $transaksiExtendConnectMain = isset($this->configUi[$jenisTr]['extConnectToMain']) ? $this->configUi[$jenisTr]['extConnectToMain'] : array();
                $transaksiExtendConnectPair = isset($this->configUi[$jenisTr]['extConnectToPair']) ? $this->configUi[$jenisTr]['extConnectToPair'] : array();


                if (sizeof($transaksiExtendConnect) > 0) {
                    foreach ($transaksiExtendConnect as $key_jenis) {
                        $arrJenisStep = array(
                            "next_step_num" => 1,
                            "next_step_code" => $key_jenis,
                        );

                        $arrJenisStepRslt[$key_jenis] = callNextPICDetail($arrJenisStep);

                    }
                }


                $tr2 = new MdlTransaksi();
                $tr2->setFilters(array());

                if (isset($connectTo)) {
                    $tr2->addFilter("jenis_master='$connectTo'");
                    $tr2->addFilter("link_id='0'");
                    $tr2->addFilter("next_step_code>'0'");
                    $tr2->addFilter("sub_step_number>'0'");
                    $tr2->addFilter("gudang_id<>'0'");
                    if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {
                    }
                    else {
                        $tr2->addFilter("transaksi.cabang2_id='" . $this->session->login['cabang_id'] . "'");
                    }
                    $tmpSrc2 = $tr2->lookupJoined_OLD()->result();
                    //                    cekHere($this->db->last_query() . " --- " . count($tmpSrc2));

                    $tmp2 = array();
                    $tmpMasterIDResult = array();
                    $tmpMasterID = array();
                    $tmpResult = array();
                    $arrNextAction = array();
                    if (sizeof($tmpSrc2) > 0) {

                        foreach ($tmpSrc2 as $src2sSpec) {
                            $tmpMasterID[$src2sSpec->id_master] = $src2sSpec->id_master;
                            $arrNextAction[$src2sSpec->transaksi_id] = array(
                                "next_step_num" => $src2sSpec->next_substep_num,
                                "next_step_code" => $src2sSpec->next_substep_code,
                            );

                            $arrTransID[] = $src2sSpec->transaksi_id;
                        }
                        //arrPrint($tmpMasterID);
                        //arrPrint($transaksiExtendConnectMain);
                        //arrPrint($transaksiExtendConnect);

                        //--------------------------------
                        if (sizeof($transaksiExtendConnect) > 0) {
                            $tr12 = new MdlTransaksi();
                            $tr12->setFilters(array());
                            $tr12->addFilter("link_id=0");
                            $this->db->where_in("jenis", $transaksiExtendConnectMain);
                            $tr12->addFilter("id_master in ('" . implode("','", $tmpMasterID) . "')");
                            $tr12->addFilter("valid_qty>'0'");
                            //                            $trTmpExt = $tr12->lookupAll()->result();
                            $trTmpExt = $tr12->lookupJoined_OLD()->result();
                            //                            showLast_query("biru");
                            //                            cekHere(sizeof($trTmpExt));
                            //                            arrPrintWebs($trTmpExt);
                            if (sizeof($trTmpExt) > 0) {
                                foreach ($trTmpExt as $trTmpExtSpec) {
                                    //arrprint($trTmpExtSpec);
                                    $pairCode = $transaksiExtendConnectPair[$trTmpExtSpec->jenis];
                                    //                                    cekhere($pairCode);
                                    $tmpMasterIDExt[$trTmpExtSpec->id_master][$trTmpExtSpec->jenis] = $arrJenisStepRslt[$pairCode];
                                }
                            }
                        }
                        //                        arrPrint($tmpMasterIDExt);
                        //--------------------------------


                        $tr3 = new MdlTransaksi();
                        $tr3->setFilters(array());
                        $tr3->addFilter("link_id='0'");
                        $tr3->addFilter("id_master in ('" . implode("','", $tmpMasterID) . "')");
                        $tmpSrc3 = $tr3->lookupAll()->result();
                        //                    cekHijau($this->db->last_query() . " --- " . count($tmpSrc2));

                        //region membaca pairingan registries MAIN
                        $tmpReg_result = array();
                        if (sizeof($pairRegistries) > 0) {
                            foreach ($pairRegistries as $param) {

                                $trReg = new MdlTransaksi();
                                $trReg->setFilters(array());
                                $trReg->addFilter("param='$param'");
                                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                                $tmpReg = $trReg->lookupRegistries()->result();
                                if (sizeof($tmpReg) > 0) {
                                    foreach ($tmpReg as $regRow) {
                                        if ($regRow->values == null) {
                                            $regRow->values = blobEncode(array());
                                        }
                                        $param = $regRow->param;
                                        $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
                                    }
                                }
                            }
                        }
                        //endregion

                        foreach ($tmpSrc3 as $tmpSrc3Spec) {
                            $tmpMasterIDResult[$tmpSrc3Spec->id_master][$tmpSrc3Spec->jenis] = $tmpSrc3Spec->nomer;
                        }

                        foreach ($tmpSrc2 as $src2s) {
                            if (sizeof($tmpMasterIDResult[$src2s->id_master]) > 0) {
                                foreach ($tmpMasterIDResult[$src2s->id_master] as $key => $val) {

                                    $src2s->$key = $val;
                                }
                            }
                            $tmpResult[$src2s->id_master] = $src2s;
                        }

                        $arrNextPIC = callNextPIC($arrNextAction);


                        foreach ($tmpResult as $tmpSpec) {
                            //                            arrPrint($tmpSpec);
                            $extHistoryFields2 = isset($arrExtHistoryFields2[$tmpSpec->step_number]) ? $arrExtHistoryFields2[$tmpSpec->step_number] : array();

                            if (sizeof($pairRegistries) > 0) {
                                if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$tmpSpec->transaksi_id]))) {
                                    foreach ($tmpReg_result[$tmpSpec->transaksi_id] as $param => $eReg) {
                                        if ($param == "main") {
                                            foreach ($eReg as $k => $v) {
                                                if (!isset($tmpSpec->$k)) {
                                                    $tmpSpec->$k = $v;
                                                }
                                            }
                                        }
                                        elseif ($param == "items") {
                                            if (sizeof($extHistoryFields2) > 0) {
                                                foreach ($extHistoryFields2 as $k1 => $v1) {
                                                    if (is_array($v1)) {
                                                        $kolom = $v1['kolom'];
                                                        $format = $v1['format'];
                                                        //                                                cekHitam(":: $kolom :: $format ::");
                                                        if (!isset($tmpSpec->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $tmpSpec->$k1 = $tmpDetail;
                                                        }
                                                    }
                                                    else {
                                                        if (!isset($tmpSpec->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $tmpSpec->$k1 = $tmpDetail;
                                                        }
                                                    }

                                                }
                                            }
                                        }
                                    }
                                }
                            }


                            foreach ($stepHistoryFields as $fName => $fLabel) {

                                $keyFormat = array_key_exists($fName, getTransaksiJenis()) ? "nomer" : $fName;
                                $tmp2Datas[$fName] = isset($tmpSpec->$fName) ? formatField_he_format($keyFormat, $tmpSpec->$fName) : "-";
                            }

                            if ($tmpSpec->sub_step_number > 0) {
                                $tmp2Datas['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$tmpSpec->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$tmpSpec->sub_step_number]['stateLabel'] . "</span>";
                                $tmp2Datas['state'] .= "<br>" . createStateSign($tmpSpec->sub_step_number, $tmpSpec->step_avail, $connectTo);
                            }
                            else {
                                $tmp2Datas['state'] = "<span style='color:#777777'>canceled</span>";
                            }


                            $tmp2Datas['next_pic'] = "-";
                            if (sizeof($arrNextPIC) > 0) {
                                if (isset($arrNextPIC[$tmpSpec->next_substep_code][$tmpSpec->next_substep_num])) {
                                    $next_pic = "";
                                    $nob = 1;
                                    foreach ($arrNextPIC[$tmpSpec->next_substep_code][$tmpSpec->next_substep_num] as $spec) {
                                        if ($tmpSpec->cabang_id == $spec['cabang_id']) {

                                            if ($next_pic == "") {
                                                $next_pic = "$nob. " . $spec['nama'];
                                            }
                                            else {
                                                $nob++;
                                                $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'];
                                            }

                                        }
                                    }
                                    $tmp2Datas['next_pic'] = $next_pic;

                                }
                            }
                            //arrPrint($tmpMasterIDExt[$tmpSpec->id_master]);
                            //---------------------------------------------
                            //                            if(isset($tmpMasterIDExt[$tmpSpec->id_master])){
                            //                            if(isset($tmpMasterIDExt[$tmpSpec->id_master])){
                            if (sizeof($transaksiExtendConnectPair) > 0) {
                                foreach ($transaksiExtendConnectPair as $kk => $vv) {
                                    $next_picx = "";
                                    $nobx = 1;
                                    if (isset($tmpMasterIDExt[$tmpSpec->id_master][$kk])) {
                                        foreach ($tmpMasterIDExt[$tmpSpec->id_master][$kk] as $spec) {
                                            if ($next_picx == "") {
                                                $next_picx = "$nobx. " . $spec['nama'];
                                            }
                                            else {
                                                $nobx++;
                                                $next_picx .= "<br>" . "$nobx. " . $spec['nama'];
                                            }
                                        }
                                    }
                                    else {
                                        $next_picx = "done";
                                    }

                                    $tmp2Datas[$kk] = $next_picx;
                                }
                            }
                            //                            }
                            //---------------------------------------------


                            $tmp2[] = $tmp2Datas;

                            //                            break;
                        }
                    }
                }


                $arrayOnProgressView = $tmp2; // $tmp2
            }
        }

        return $arrayOnProgressView;
    }

    public function viewResumeDetails()
    {

        $selectedID = $this->uri->segment(5);

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_data.trash='0'");
        $joinedTrans = $tr->lookupJoinedByID($selectedID)->result()[0];

        $jenisTr = $joinedTrans->jenis_master;
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $receiptElemets = isset($this->configUi[$jenisTr]["receiptElements"]) ? $this->configUi[$jenisTr]["receiptElements"] : array();
        $receiptDetailCompactListlabel = isset($this->configLayout[$jenisTr]["reviewDetailCompactListsLabel"]) ? $this->configLayout[$jenisTr]["reviewDetailCompactListsLabel"] : array();
        $receiptDetailCompactListlabel2 = isset($this->configLayout[$jenisTr]["reviewDetailCompactListsLabel2"]) ? $this->configLayout[$jenisTr]["reviewDetailCompactListsLabel2"] : array();
        $receiptCompactDetailsSum = isset($this->configLayout[$jenisTr]["reviewCompactListDetailSum"]) ? $this->configLayout[$jenisTr]["reviewCompactListDetailSum"] : array();
        $receiptCompactDetailsSum2 = isset($this->configLayout[$jenisTr]["reviewCompactListDetailSum2"]) ? $this->configLayout[$jenisTr]["reviewCompactListDetailSum2"] : array();
        $receiptMainLabel = isset($this->configLayout[$jenisTr]["reviewMainCompactListsLabel"]) ? $this->configLayout[$jenisTr]["reviewMainCompactListsLabel"] : array();
        $reviewCompactListSum = isset($this->configLayout[$jenisTr]["reviewCompactListSum"]) ? $this->configLayout[$jenisTr]["reviewCompactListSum"] : array();
        $reviewAddRows = isset($this->configLayout[$jenisTr]["reviewAddRows"]) ? $this->configLayout[$jenisTr]["reviewAddRows"] : array();
        $reviewSign = isset($this->configLayout[$jenisTr]["reviewSign"][$joinedTrans->step_number]) ? $this->configLayout[$jenisTr]["reviewSign"][$joinedTrans->step_number] : array();

        $d_start = backCustomDate(30, $joinedTrans->fulldate);
        $d_stop = $joinedTrans->fulldate;

        $tr->setFilters(array());
        $tr->addFilter("transaksi_id='$selectedID'");
        $tmpReg = $tr->lookupDataRegistries()->result();

        $mainElements = array();

        $mainData = array();
        $receiptDetailFields = array();
        $receiptSumFields = array();
        $masterItems = array();
        $masterItems2_sum = array();
        foreach ($tmpReg as $regData) {
            foreach ($regData as $key_reg => $val_reg) {
                if ($val_reg == null) {
                    $val_reg = blobEncode(array());
                }
                switch ($key_reg) {
                    case "main":
                        $mainData = blobDecode($val_reg);
                        break;
                    case "items":
                        $masterItems_0 = blobDecode($val_reg);
                        foreach ($masterItems_0 as $pId => $masterItem) {
                            if (isset($masterItem['nett1']) && ($masterItem['nett1'] > 0)) {
                                $ppn_key = $masterItem['ppn'] / $masterItem['nett1'];
                            }
                            else {
                                $ppn_key = 0;
                            }
                            $addItem['harganppn'] = $masterItem['harga'] + ($masterItem['harga'] * $ppn_key);
                            $masterItems[$pId] = $masterItem + $addItem;
                        }
                        break;
                    case "items2_sum":
                        $masterItems2_sum = blobDecode($val_reg);
                        //                    foreach ($masterItems_0 as $pId => $masterItem) {
                        //                        $ppn_key = $masterItem['ppn'] / $masterItem['nett1'];
                        //                        $addItem['harganppn'] = $masterItem['harga'] + ($masterItem['harga'] * $ppn_key);
                        //                        $masterItems[$pId] = $masterItem + $addItem;
                        //                    }
                        break;
                    case "main_elements"://
                        $mainElements = blobDecode($val_reg);
                        break;
                    case "receiptDetailFields"://
                        $receiptDetailFields = blobDecode($val_reg);
                        break;
                    case "receiptSumFields"://
                        $receiptSumFields = blobDecode($val_reg);
                        break;
                }
            }

        }
        switch ($joinedTrans->produk_jenis) {
            case "produk":
                $link = base_url() . "Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan produk/";
                break;
            case "supplies":
                $link = base_url() . "Ledger/viewMoveDetails/RekeningPembantuSupplies/persediaan supplies/";
                break;
            default:
                $link = NULL;
                break;
        }


        $sumQty = array();
        $arrItemsLink = array();
        if (sizeof($masterItems) > 0) {
            $qty_sum = 0;
            foreach ($masterItems as $data) {
                $qty_sum += $data["qty"];

                if ($link != NULL) {
                    $arrItemsLink[$data['id']] = $link . $data['id'] . "?o=" . $data['cabangID'] . "&trID=" . $joinedTrans->transaksi_id . "&date1=$d_start&date2=$d_stop";
                }
            }
            $sumQty["qty"] = $qty_sum;
        }
        $mainData = $mainData + $sumQty + (array)$joinedTrans;

        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($joinedTrans->id_master)->result();

        $signHeader = array();
        $signValues['sign_' . 0] = array(
            "label" => "saleman",
            "contents" => isset($joinedTrans->salesman_nama) ? $joinedTrans->salesman_nama : "",
            "caption_department" => "",
        );
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->configUi[$joinedTrans->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->configUi[$joinedTrans->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }
        //endregion


        $data = array(
            "mode" => $this->uri->segment(3),
            "mainFields" => $mainData,
            "items" => $masterItems,
            "items2_sum" => $masterItems2_sum,
            "itemsLabel" => $receiptDetailCompactListlabel,
            "itemsLabel2" => $receiptDetailCompactListlabel2,
            "mainSumDetailsFieldsLabel" => $receiptCompactDetailsSum,
            "mainSumDetailsFieldsLabel2" => $receiptCompactDetailsSum2,
            "mainSumFieldsLabel" => $reviewCompactListSum,
            "mainFieldsLabel" => $receiptMainLabel,
            "mainElements" => $mainElements,
            "receiptSumFields" => $receiptSumFields,
            "reviewAddRows" => $reviewAddRows,
            "sign" => $signValues,
            "reviewSign" => $reviewSign,

            "itemsLink" => $arrItemsLink,
            "itemsKolomLink" => array("nama", "produk_kode"),
            "underMaintenance" => underConstruction(),
        );

        $this->load->view("transaksi", $data);

    }

    public function recordImage()
    {
        $this->db->trans_start();
        $files = isset($_FILES['file']) ? $_FILES['file'] : array();
        $trid = $imaster_id = $this->uri->segment(4);
        $this->load->model("Mdls/MdlTransaksiImageReference");
        $t = new MdlTransaksiImageReference();
        $imageRecorder = MODUL_PATH . get_class() . "/recordImage";

        $iID = $_GET['iID'];

        $linkDelete = "$imageRecorder/$imaster_id?iID=$iID&rel=delete";

        if (isset($_GET["rel"]) && $_GET["rel"] == "delete") {
            $update = array(
                "status" => "0",
                "trash" => "1",
            );
            $t->setFilters(array());
            $t->updateData(array("id_master" => $trid), $update) or matiHere("gagal menghapus relasi foto");
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo "
                    <script>
                        top.swal('wew error cok');
                    </script>
                    ";
            }
            else {
                $this->db->trans_complete();

                $tmp .= "<span id='overlay_c' class='overlay_$iID'><i class='fa fa-refresh fa-spin'></i></span>";
                $tmp .= "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$imaster_id?iID=$iID&valValue=$file_e' target='result'>";
                $tmp .= "<input type='file' class='btn btn-warning btn-block btn-xs' id='file-upload_$iID' style='border: none;padding:5px;' name='file' class='file' onchange='top.$(`.overlay_$iID`).show();document.getElementById(`myForm_$iID`).submit()'>";
                $tmp .= "</form>";

                echo "
                    <script>
                        top.$('._div_$iID').html(\"$tmp\");
                    </script>
                    ";
            }
        }
        else {
            if ($files['error'] == 0) {
                $cUrl_result = upload_image($files);
                $fullurl_f = $cUrl_result->full_url;
                $fullurl = str_replace('prog_', '', $fullurl_f);
                $data = array(
                    "nama" => $_FILES["file"]["name"],
                    "id_master" => $trid,
                    "cdn_link" => $fullurl,
                    "oleh_id" => $this->session->login["id"],
                    "oleh_nama" => $this->session->login["nama"],
                    "status" => "1",
                );
                $t->addData($data) or matiHEre("gagal upload data");
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo "
                    <script>
                        top.swal('wew error cok');
                    </script>
                    ";
                }
                else {
                    $this->db->trans_complete();
                    $file_e = $fullurl;

                    $label_image = strlen($_FILES["file"]["name"]) > 10 ? substr($_FILES["file"]["name"], 0, 10) . "..." : $_FILES["file"]["name"];
                    $label_ori = $_FILES["file"]["name"];

                    $tmp .= "<span id='overlay_c' class='overlay_$iID'><i class='fa fa-refresh fa-spin'></i></span>";
                    $tmp .= "<label title='$label_ori'><i class='fa fa-image'></i> $label_image</label>";
                    $tmp .= "<button type='button' class='btn btn-xs btn-danger' title='hapus foto' onclick='top.$(`.overlay_$iID`).show();document.getElementById(`result`).src=`$linkDelete`'>hapus</button>";

                    echo "
                    <script>
                        top.$('._div_$iID').html(\"$tmp\");
                    </script>
                    ";

                }
            }
        }
    }

    public function viewUndoneItemsIndex2()
    {
        session_write_close();
        $sisa_pembayaran = 100;
        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $starttime = microtime(true);
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $scriptBottom = "";
        $sesionReplacer = replaceSession();
        //        $jenisTr = $this->uri->segment(3);
        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $paymentConfig = isset($this->configUi[$jenisTr]['paymentConfig']) ? $this->configUi[$jenisTr]['paymentConfig'] : false;
        $myPaymentConfig = isset($this->configUi[$jenisTr]['myPaymentConfig']) ? $this->configUi[$jenisTr]['myPaymentConfig'] : false;
        $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array();
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $stepHistoryFields = isset($this->configUi[$jenisTr]['shortStepHistoryFields']) ? $this->configUi[$jenisTr]['shortStepHistoryFields'] : array();
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();
        $kreditLimitValidate = isset($this->configUi[$jenisTr]["kreditLimitValidate"]) ? $this->configUi[$jenisTr]["kreditLimitValidate"] : array();
        $allowPrintQr = isset($this->configUi[$jenisTr]["steps"][$jenisTrsub]["allowPrintQr"]) ? $this->configUi[$jenisTr]["steps"][$jenisTrsub]["allowPrintQr"] : 0;
        $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ? $this->configUi[$jenisTr]["shortItemsFields"] : array();
        /* ----------------------------------------------------------------------
         * deteksi mobile auto atau hanya orang tertentu,
         * diatur di heWeb mobile
         * ----------------------------------------------------------------------*/
        $isMob = isMobile_he_misc();
        // --------------------------------------------------------------
        $this->load->model("Coms/ComTransaksiKreditLimit");
        $kl = New ComTransaksiKreditLimit();
        $arrKreditLimitDataKredit = $kl->fetchBalancesKredit();
        //        arrPrintWebs($arrKreditLimitDataKredit);
        // --------------------------------------------------------------
        if (ipadd() == "202.65.117.72") {
            $historyFields = isset($this->configUi[$jenisTr]['shortHistoryFieldsCek']) ? $this->configUi[$jenisTr]['shortHistoryFieldsCek'] : $historyFields;
        }

        if ($isMob) {
            // $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }
        $prePreFields = $historyFields;
        $prePreFields['state'] = "status";
        $prePreFields['action'] = "action";
        $swapJenisTr = isset($this->configUi[$jenisTr]['requestCode']['masterCode']) ? $this->configUi[$jenisTr]['requestCode']['masterCode'] : array();
        $arrayOnprePre = array();
        $arrayOnprePreGroup = array();
        if (sizeof($swapJenisTr) > 0) {
            $steps = $this->configUi[$swapJenisTr]['steps'];
            $arrFilters = array();
            if (sizeof($steps) > 1) {
                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $stepCodes = array();
                $jmlStep = count($steps);
                if (isset($this->accessList[$swapJenisTr]) && sizeof($this->accessList) > 0) {
                    $indsteps = "(";
                    foreach ($this->accessList[$swapJenisTr] as $stepNumber => $stepSpec) {
                        if ($stepNumber <= $jmlStep) {
                            foreach ($stepSpec as $targetCode => $filters) {
                                $indsteps .= "'$targetCode',";
                                $stepCodes[] = $targetCode;
                                if ($filters['allowFollowUp'] == "true") {
                                    $arrFilters["allowFollowUp"][] = $targetCode;
                                }
                            }
                        }
                    }
                    $indsteps = rtrim($indsteps, ",");
                    $indsteps .= ")";
                    if (sizeof($arrFilters) > 0) {
                        $tr->addFilter("next_step_code in $indsteps");
                    }
                    else {
                        $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                    }
                }
                else {

                }
                $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
                $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
                $tr->addFilter("next_substep_code<>''");
                $tr->addFilter("sub_step_number>0");
                $tr->addFilter("valid_qty>0");
                $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
                if (sizeof($tmpHist) > 0) {
                    $arrTransID = array();
                    $arrTransTopID = array();
                    $arrIdsHist = array();
                    $arrTransHist = array();
                    foreach ($tmpHist as $row) {
                        $arrTransID[] = $row->id;
                        $arrTransTopID[] = $row->id_top;

                        if ($row->ids_his != "") {
                            $hist = blobDecode($row->ids_his);
                            foreach ($hist as $hisSpec) {
                                $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                    "step" => $hisSpec['step'],
                                    "trID" => $hisSpec['trID'],
                                    "nomer" => $hisSpec['nomer'],
                                );
                                $arrTransHist[] = $hisSpec['trID'];
                            }
                        }
                    }
                    $tmpReg_result = array();
                    $trReg = new MdlTransaksi();
                    $trReg->setFilters(array());
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();

                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            if ($regRow->values == null) {
                                $regRow->values = blobEncode(array());
                            }
                            $param = $regRow->param;
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
                        }
                    }

                    foreach ($tmpHist as $row) {
                        if (sizeof($pairRegistries) > 0) {
                            //                            $trReg = new MdlTransaksi();
                            //                            $trReg->setFilters(array());
                            //                            $trReg->addFilter("param in ('" . implode("','", $pairRegistries) . "')");
                            //                            $trReg->addFilter("transaksi_id='" . $row->transaksi_id . "'");
                            //                            $tmpReg = $trReg->lookupRegistries()->result();
                            //                            if (sizeof($tmpReg) > 0) {
                            //                                foreach ($tmpReg as $regRow) {
                            //                                    $param = $regRow->param;
                            //                                    $$param = blobDecode($regRow->values);
                            //                                }
                            //                                foreach ($pairRegistries as $eReg) {
                            //                                    foreach ($$eReg as $k => $v) {
                            //                                        if (!isset($row->$k)) {
                            //                                            $row->$k = $v;
                            //                                        }
                            //                                    }
                            //                                }
                            //                            }
                            if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                                foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                                    foreach ($eReg as $k => $v) {
                                        if (!isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                }
                            }
                        }

                        $tmp = array();
                        foreach ($historyFields as $fName => $fLabel) {
                            $tmp[$fName] = isset($row->$fName) ? formatField($fName, $row->$fName) : formatField($fName, 0);
                        }

                        //                        cekHijau('$swapJenisTr: ' . $swapJenisTr);

                        $showPoStatus = isset($this->configUi[$swapJenisTr]['showPoStatus']) ? $this->configUi[$swapJenisTr]['showPoStatus'] : array();
                        $cekStateLocation = ($this->session->login['cabang_id'] > 0) ? "cabang" : "pusat";

                        if ($row->sub_step_number > 0) {

                            $tmp['state'] = "<div class='panel panel-warning' style='padding: 3px;margin-bottom: 5px;' ><span style='color:" . $this->configUi[$swapJenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$swapJenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                            $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $swapJenisTr) . "</div>";

                            // cek PO
                            $arrTransactionSource = array();
                            $id_master = $row->id_master;
                            $id_top1 = $row->id_top;
                            $this->load->model("MdlTransaksi");
                            $l = new MdlTransaksi();
                            $l->setFilters(array());
                            $l->addFilterJoin("transaksi_data.valid_qty>0");
                            $l->addFilter("id_master='" . $id_master . "'");
                            $l->addFilter("jenis_master='" . $swapJenisTr . "'");
                            $l->addFilter("link_id=0");
                            $tmpTS = $l->lookupJoined();

                            $arrsub_step_number = array();
                            $arrstep_avail = array();
                            $arrext_blob = array();
                            $arrjenis_master = array();
                            $arrtransaksi_no = array();
                            $arrketerangan = array();

                            if (sizeof($tmpTS) > 0) {
                                foreach ($tmpTS as $kk => $arVL) {
                                    $id = $arVL->id;
                                    $id_master = $arVL->id_master;
                                    $id_top2 = $arVL->id_top;
                                    $produk_id = $arVL->produk_id;
                                    $arrTransactionSource[$id_top1] = $produk_id;
                                    $arrsub_step_number[$id_top1] = $arVL->sub_step_number;
                                    $arrstep_avail[$id_top1] = $arVL->step_avail;
                                    $arrext_blob[$id_top1][$kk] = isset($arVL->ext_blob) ? ($arVL->ext_blob != "" ? blobDecode($arVL->ext_blob) : "") : "";
                                }

                                if (sizeof($arrext_blob[$id_top1]) > 0) {
                                    foreach ($arrext_blob[$id_top1] as $ky => $aVal) {
                                        if ($aVal != "") {
                                            foreach ($arrext_blob[$id_top1][$ky]['static'] as $numb => $numData) {
                                                $arrtransaksi_no[$id_top1][$numb] = formatField_he_format("nomer", $numb);
                                                $arrjenis_master[$id_top1][$numb] = $numData['jenis'];
                                                $arrketerangan[$id_top1][$numb] = $numData['keterangan'];
                                            }
                                        }
                                    }
                                }
                            }

                            if ($arrext_blob[$id_top1][$ky] != "") {
                                $tmp['state'] .= "<div class='panel panel-danger bg-green' style='padding: 3px;margin-bottom: 5px;'>";
                                if ($cekStateLocation == "cabang") {
                                    $tmp['state'] .= "<div><b>diproses oleh PUSAT</b></div>";
                                }
                                if (sizeof($arrtransaksi_no[$id_top1]) > 0) {
                                    foreach ($arrtransaksi_no[$id_top1] as $numb_) {
                                        $tmp['state'] .= "<div><span class='fa fa-check-circle text-warning'></span> " . $numb_ . "</div>";
                                    }
                                }
                                $tmp['state'] .= "</div>";
                            }

                        }
                        else {
                            $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                        }

                        $tmp['action'] = "";
                        $nextStepNum = ($row->next_substep_num);
                        $currentStepNum = ($row->sub_step_number);
                        $currentStepCode = ($row->jenis);
                        $nextStepCode = ($row->next_step_code);

                        if ($row->sub_step_number > 0) {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                        }

                        if (isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum])) {
                            if (isset($this->accessList[$jenisTr])) {
                                if (isset($this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                                    $allowFollowup = $this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                                    $actionLabel = $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                            }
                            else {
                                if (in_array($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                                    $allowFollowup = true;
                                    $actionLabel = $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                                else {
                                    $allowFollowup = true;
                                    $actionLabel = "review " . $this->configUi[$swapJenisTr]['steps'][$currentStepNum]['label'];
                                }
                            }
                        }

                        if ($allowFollowup) {
                            $allowJoin = isset($this->configUi[$swapJenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$swapJenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                            $stepLabel = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['label'] : "";
                            $isCancelPacking = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                            $allowCancel = isset($this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$swapJenisTr]['steps'][$nextStepNum]['allowCancel'] : false;

                            $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                            $followupLink = "top.$('#result').load('" . base_url() . "Transaksi/$targetFollowupLink/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                            $tmp['action'] = "<div class='input-group'>";
                            $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                            if ($allowJoin) {
                                $tmp['action'] .= "<span class='input-group-addon'>";
                                $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                                $tmp['action'] .= "</span class='input-group-addon'>";
                            }
                            $tmp['action'] .= "</div class='input-group'>";

                            if ($allowCancel) {
                                $tmp['action'] .= "<div class='input-group'>";
                                $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                                if ($allowJoin) {
                                    $tmp['action'] .= "<span class='input-group-addon'>";
                                    $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                                    $tmp['action'] .= "</span class='input-group-addon'>";
                                }
                                $tmp['action'] .= "</div class='input-group'>";
                            }
                        }
                        else {

                        }
                        $arrayOnprePre[] = $tmp;
                        $arrayOnprePreGroup[$currentStepNum][] = $tmp;
                    }
                }
            }
            else {
                $arrayOnprePre = array();
            }
        }
        else {
            $arrayOnprePre = array();
        }
        //endregion prePre


        //region lookup on-going transactions
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->configUi[$jenisTr]['steps'];

        $arrFilters = array();
        $this->load->model("Mdls/MdlTransaksiImageReference");
        $this->load->model("MdlTransaksi");
        $ti = new MdlTransaksiImageReference();
        $tr = new MdlTransaksi();
        $stepCodes = array();
        $jmlStep = count($steps);

        if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList) > 0) {
            $indsteps = "(";
            foreach ($this->accessList[$jenisTr] as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    foreach ($stepSpec as $targetCode => $filters) {

                        $stepCodes[] = $targetCode;
                        if ($filters['allowCreate'] == "true") {
                            //berarti step 1 boleh edit
                            $next = $stepNumber + 1;
                            $target2 = $steps[$next]["target"];
                            $indsteps .= "'$target2',";
                            $arrFilters["allowFollowUp"][] = $target2;
                        }
                        if ($filters['allowFollowUp'] == "true") {
                            $indsteps .= "'$targetCode',";
                            $arrFilters["allowFollowUp"][] = $targetCode;
                        }

                    }
                    //                    foreach ($stepSpec as $targetCode => $filters) {
                    //                        $indsteps .= "'$targetCode',";
                    //                        $stepCodes[] = $targetCode;
                    //                        if ($filters['allowFollowUp'] == "true") {
                    //                            $arrFilters["allowFollowUp"][] = $targetCode;
                    //                        }
                    //
                    //                    }
                }
            }
            $indsteps = rtrim($indsteps, ",");
            $indsteps .= ")";
            if (sizeof($arrFilters) > 0) {
                $tr->addFilter("next_step_code in $indsteps");
            }
            else {
                $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                $tr->addFilter("next_step_code!=''");
            }
        }
        else {

        }

        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        if ($this->session->login['employee_type'] == "employee_freelance") {
            $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
        }
        if ($this->session->login['employee_type'] == "employee_kirim") {
            $tr->addFilter("kirim_metode_id='1'");
            //            $sesionReplacer = array();
        }

        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        //arrPrint($sesionReplacer);
        //showLast_query("biru");
        $arrayOnprogress = array();
        $arrayOnprogressGroup = array();
        $arrayOnprogressPartialMark = array();
        $arrayOnprogressGroupPartialMark = array();

        $extData = "";
        $extact = "";
        $selectProcessor = "";
        if (sizeof($tmpHist) > 0) {
            $arrTransID = array();
            $arrTransTopID = array();
            $arrIdsHist = array();
            $arrTransHist = array();
            $arrNextAction = array();
            $arrID_master = array();
            foreach ($tmpHist as $row) {
                $arrTransID[] = $row->transaksi_id;
                $arrID_master[$row->id_master] = $row->id_master;
                $arrTransTopID[] = $row->id_top;
                $arrNextAction[$row->transaksi_id] = array(
                    "next_step_num" => $row->next_substep_num,
                    "next_step_code" => $row->next_substep_code,
                );
                if ($row->ids_his != "") {
                    $hist = blobDecode($row->ids_his);
                    foreach ($hist as $hisSpec) {
                        $arrIdsHist[$row->id][$hisSpec['step']] = array(
                            "step" => $hisSpec['step'],
                            "trID" => $hisSpec['trID'],
                            "nomer" => $hisSpec['nomer'],
                        );
                        $arrTransHist[] = $hisSpec['trID'];
                    }
                }
                //                    arrPrint($row);
            }

            // region locker transaksi
            $this->load->model("Mdls/MdlLockerTransaksi");
            $lt = New MdlLockerTransaksi();
            $lt->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $lt->addFilter("state='hold'");
            $lt->addFilter("jumlah='1'");
            $ltTmp = $lt->lookupAll()->result();
            $transaksiHold = array();
            if (sizeof($ltTmp) > 0) {
                foreach ($ltTmp as $ltSpec) {
                    $hold_oleh_id = $ltSpec->oleh_id;
                    if ($hold_oleh_id == my_id()) {

                    }
                    else {
                        $transaksiHold[$ltSpec->transaksi_id] = $ltSpec->transaksi_id;
                    }
                }
            }
            // endregion locker transaksi


            // region membaca payment source label uang muka, untuk kas yang harus diterima dulu.
            $psrc = New MdlTransaksi();
            $psrc->setFilters(array());
            $psrc->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $psrc->addFilter("sisa>$sisa_pembayaran");
            $psrcTmp = $psrc->lookUpAllPaymentSrc()->result();
            $psrcData = array();
            if (sizeof($psrcTmp) > 0) {
                foreach ($psrcTmp as $psrcSpec) {
                    $psrcData[$psrcSpec->transaksi_id] = array(
                        "extern_id" => $psrcSpec->extern_id,
                        "extern_nama" => $psrcSpec->extern_nama,
                        "nomer" => $psrcSpec->nomer,
                        "terbayar" => $psrcSpec->terbayar,
                        "sisa" => $psrcSpec->sisa,
                        "label" => "* Transaksi belum dibayar/belum lunas.",
                    );
                }
            }
            // endregion membaca payment source label uang muka, untuk kas yang harus diterima dulu.


            $tmpReg_result = array();
            if (sizeof($pairRegistries) > 0) {
                $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());
                //            $trReg->addFilter("param='main'");
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        //                    arrPrintWebs($regRow);
                        foreach ($regRow as $key_reg => $val_reg) {
                            if ($val_reg == null) {
                                $val_reg = blobEncode(array());
                            }
                            if ($key_reg != "transaksi_id") {
                                $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                            }
                        }

                    }
                }
            }

            $relImages = array();
            if (count($arrID_master) > 0) {

                $ti->addFilter("id_master in ('" . implode("','", $arrID_master) . "')");
                $tmpImages = $ti->lookUpAll()->result();
                if (count($tmpImages) > 0) {
                    foreach ($tmpImages as $tmpImages_0) {
                        $relImages[$tmpImages_0->id_master] = array(
                            "nama" => $tmpImages_0->nama,
                            "link_img" => $tmpImages_0->cdn_link,
                            "dtime" => $tmpImages_0->dtime,
                        );
                    }
                }
                //                arrPrint($tmpImages);
                //                matiHEre();
            }


            $arrNextPIC = callNextPIC($arrNextAction);

            if (sizeof($arrIdsHist) > 0) {
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                $tmpTransHist = $tr->lookupAll()->result();


                if (sizeof($tmpTransHist) > 0) {
                    foreach ($tmpTransHist as $histSpec) {
                        $tmpTransHist_result[$histSpec->id] = array(
                            "oleh_id" => $histSpec->oleh_id,
                            "oleh_nama" => $histSpec->oleh_nama,
                        );
                    }
                }

                foreach ($arrIdsHist as $trID => $histSpec) {
                    foreach ($histSpec as $step => $detailSpec) {
                        if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                            $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                        }
                        $arrTransMainHist[$trID][$step] = $detailSpec;
                    }
                }
            }


            //region tambahan untuk view rincian dikolom
            $detailShow = array();
            if (count($arrItemShow) > 0) {
                $detailShow = $tr->lookupDetailTransaksi($arrTransID);
            }


            //endregion
            $numb = 0;
            foreach ($tmpHist as $row) {
                //arrPrint($row);
                $extHistoryFields2 = isset($arrExtHistoryFields2[$row->step_number]) ? $arrExtHistoryFields2[$row->step_number] : array();

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                        foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                            switch ($param) {
                                case "main":
                                    foreach ($eReg as $k => $v) {
                                        if (($k != null) && !isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                    break;
                                case "items":
                                    if (sizeof($extHistoryFields2) > 0) {
                                        foreach ($extHistoryFields2 as $k1 => $v1) {
                                            if (is_array($v1)) {
                                                $kolom = $v1['kolom'];
                                                $format = $v1['format'];
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                            else {
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
                //                arrPrintPink($row);


                $numb++;

                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (isset($row->$fName)) {
                        if (is_numeric($row->$fName)) {
                            if (!isset($sumFooter[$fName])) {
                                $sumFooter[$fName] = 0;
                            }
                            $sumFooter[$fName] += $row->$fName;
                        }
                    }

                    if (is_array($fLabel)) {
                        $hisStep = isset($fLabel['step']) ? $fLabel['step'] : 0;
                        $hisKey = isset($fLabel['key']) ? $fLabel['key'] : 0;

                        if (isset($row->ids_his)) {
                            if ($hisKey == "nomer") {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $ids_his_decode = blobDecode($row->ids_his);
                                if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                    $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                        }
                        else {
                            $tmp[$fName] = "-";
                        }
                    }
                    else {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                    }

                    if ($fName == "no") {
                        $tmp[$fName] = formatField_he_format($fName, $numb);
                    }

                }

                if (sizeof($row->cancel_packing_source_id) > 0) {
                    arrPrint($row->cancel_packing_source_id);
                    $trx = new MdlTransaksi();
                    $trx->addFilter("id='" . $row->cancel_packing_source_id . "'");
                    $tmpTrx = $trx->lookupAll()->result();

                    //                        $tmp['nomer_top'] = $tmpTrx[0]->nomer;
                    $tmp['nomer_top'] = formatField_he_format("nomer_top", $tmpTrx[0]->nomer);
                    //                        arrPrint($tmpTrx[0]->nomer);
                }


                if ($row->sub_step_number > 0) {
                    $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                    $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $jenisTr);
                }
                else {
                    $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                }

                //region show items
                //                arrPrint($arrItemShow);
                //                arrPrint($tmpReg_result);
                //                $tblItems = "";
                $tblItems = "";
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if (sizeof($arrItemShow) > 0) {
                        $tblItems .= "<table class='table table-bordered'>";
                        $tblItems .= "<tr>";
                        $tblItems .= "<th>No</th>";
                        foreach ($arrItemShow as $k => $iLabel) {
                            if (is_array($iLabel)) {
                                $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                            }
                            else {
                                $tblItems .= "<th >$iLabel</th>";
                            }
                        }
                        $tblItems .= "</tr>";


                        if (isset($detailShow[$row->transaksi_id])) {
                            $numb = 0;
                            foreach ($detailShow[$row->transaksi_id] as $row_1) {
                                $numb++;
                                $tblItems .= "<tr>";
                                $tblItems .= "<td>$numb</td>";
                                foreach ($arrItemShow as $k => $iLabel) {
                                    $class = "";
                                    if (is_numeric($row->$k)) {
                                        $class = "text-center";
                                    }
                                    $tblItems .= "<td>" . $row_1->$k . "</td>";
                                }
                                $tblItems .= "</tr>";
                            }

                        }


                        $tblItems .= "</table >";

                    }
                }
                else {
                    $detail = viewDetailTransaksi($tmpReg_result[$row->transaksi_id], $arrItemShow, $row->jenis_master);
                    $tblItems = $detail;
                }

                $tmp['item_fields'] = $tblItems;
                //endregion


                $tmp['next_pic'] = "-";
                if (sizeof($arrNextPIC) > 0) {
                    if (isset($arrNextPIC[$row->next_substep_code][$row->next_substep_num])) {
                        $next_pic = "";
                        $nob = 1;
                        foreach ($arrNextPIC[$row->next_substep_code][$row->next_substep_num] as $spec) {
                            //                            arrPrint($spec);
                            //                            cekHere($row->cabang_id . " :: " . $spec['cabang_id'] . " :: " . $spec['nama']);
                            $cabangNamaPIC = "<span class='meta'>(" . $spec['cabang_nama'] . ")</span>";
                            if ($row->cabang_id == $spec['cabang_id']) {
                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                            }
                            if (($spec['cabang_id'] == CB_ID_PUSAT)) {
                                //                            if ((my_cabang_id() == CB_ID_PUSAT) && ($spec['cabang_id'] == CB_ID_PUSAT)) {
                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'] . " $cabangNamaPIC";
                                }
                            }

                        }
                        $tmp['next_pic'] = $next_pic;
                        //                            cekHitam($next_pic);
                    }
                }


                $tmp['action'] = "";
                $nextStepNum = ($row->next_substep_num);
                $currentStepNum = ($row->sub_step_number);
                $currentStepCode = ($row->jenis);
                $nextStepCode = ($row->next_step_code);


                if ($row->sub_step_number > 0) {
                    $allowFollowup = true;
                    $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                }

                if (isset($this->configUi[$jenisTr]['steps'][$nextStepNum])) {
                    if (isset($this->accessList[$jenisTr])) {
                        if (isset($this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                            $allowFollowup = $this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }


                    }
                    else {
                        if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                        }
                        else {
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                        }
                    }
                }
                $req_cancel_qty = $row->req_cancel_qty != '' ? $row->req_cancel_qty : 0;
                $valid_qty = $row->valid_qty != '' ? $row->valid_qty : 0;


                if ($allowFollowup) {
                    $allowJoin = isset($this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                    $stepLabel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['label'] : "";
                    $isCancelPacking = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                    $allowCancel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel'] : false;
                    $allowScaner = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowScaner']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowScaner'] : false;
                    $allowNextStepOtorisasi = isset($this->configUi[$jenisTr]['allowNextStepOtorisasi'][$nextStepNum]) ? $this->configUi[$jenisTr]['allowNextStepOtorisasi'][$nextStepNum] : array();

                    $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
                    $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                    if ($isMob) {
                        $btn_block = "";
                    }
                    else {
                        $btn_block = "btn-block";
                    }
                    $class_button = "btn-primary";
                    $numberValidate = $row->step_number + 1;
                    $disabled_button_kreditlimit = "";
                    $disabled_button = "";
                    $disabled_kirim = "";
                    $disabled_hold = "";
                    $add_note = "";
                    $strPengirim = "";
                    $disabled_button_reject = "";
                    if (isset($psrcData[$row->transaksi_id])) {
                        $disabled_button = "disabled";
                        //                        $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                        //                        $class_button = "btn-default";
                        $class_button = "btn-primary";
                        $actionLabel = "menunggu pelunasan";


                        // belum dibayar
                        if ($psrcData[$row->transaksi_id]["sisa"] > $sisa_pembayaran) {
                            $disabled_button = "disabled";
                            $add_note .= "<br><span class='meta'>" . $psrcData[$row->transaksi_id]["label"] . "</span>";
                            $class_button = "btn-primary";
                            $actionLabel = "menunggu pelunasan";
                        }
                        if ($psrcData[$row->transaksi_id]["sisa"] <= $sisa_pembayaran) {
                            $disabled_button = "";
                            $disabled_button_reject = "disabled";
                            $add_note .= "<br><button disabled class='btn btn-success btn-block btn-sm fa fa-money' style='margin-top:5px;'> LUNAS</button>";
                            $class_button = "btn-primary";
                            $actionLabel = "siap dikirim dari cabang";
                        }

                    }
                    if (isset($kreditLimitValidate[$numberValidate])) {
                        if (isset($arrKreditLimitDataKredit[$row->customers_id]["kredit"]) && ($arrKreditLimitDataKredit[$row->customers_id]["kredit"] > 0)) {
                            $disabled_button_kreditlimit = "disabled";
                            $add_note .= "<br><span class='meta'>" . $kreditLimitValidate[$numberValidate]["label"] . "</span>";
                            //                            $class_button = "btn-default";
                            $class_button = "btn-primary";
                        }
                    }
                    if ($row->kirim_metode_id == 1) {
                        if (($row->pengirim_id == 0) || ($row->pengirim_id == null)) {
                            $followupPengirimLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/doFollowupPengirim/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "?pengirim=pengirim');";
                            $disabled_kirim = ($row->step_number >= 3) ? "disabled" : "";
                            $add_note .= "<br><span class='meta'>Pengirim belum terdaftar pada transaksi ini.</span>";
                            //                            $strPengirim = "<div class='input-group'>";
                            //                            $strPengirim .= "<button class='btn $btn_block btn-warning pull-right' title='turn this entry into $stepLabel'
                            //                                href='javascript:void(0)'
                            //                                onClick =\"top.open_holdon();$followupPengirimLink\">Ambil untuk dikirim</button>";
                            //                            $strPengirim .= "</div class='input-group'>";
                        }
                    }

                    if ($this->session->login["employee_type"] == "employee_kirim") {
                        $tmp['action'] = "";
                    }
                    else {
                        //-------------------------
                        if ($row->cabang_id > 0) {// hanya bila transaksi cabang
                            if (($row->gudang_status_jenis != NULL) && ($row->gudang_status_jenis == "pusat")) {
                                $allowScaner = false;
                                if (isset($allowNextStepOtorisasi[$row->gudang_status_jenis])) {
                                    $disabled_button = $allowNextStepOtorisasi[$row->gudang_status_jenis];
                                    $add_note .= "<br><span class='meta'>* " . $allowNextStepOtorisasi["label"] . "</span>";
                                    $actionLabel = "* siap dikirim dari dc/pusat";
                                    if (isset($psrcData[$row->transaksi_id])) {
                                        $actionLabel = "menunggu pelunasan";
                                    }
                                }
                            }
                        }
                        //-------------------------
                        if (isset($transaksiHold[$row->transaksi_id])) {
                            $disabled_hold = "disabled";
                            $add_note .= "<br><span class='meta'>* Barang siap dikirim.<br>Edit/Reject SO tidak bisa digunakan.</span>";
                        }
                        //-------------------------
                        $tmp['action'] = "<div class='input-group'>";
                        if ($allowScaner == false || $isMob == 1) {
                            //                            $disabled_button
                            //                                $disabled_button_kreditlimit
                            //                                $disabled_kirim
                            //                            cekHere("[$disabled_button] [$disabled_button_kreditlimit] [$disabled_kirim]");

                            if ($row->transaksi_id == 6399) {
                                cekHere("[$disabled_hold]");;
                            }
                            $tmp['action'] .= "<button class='btn $btn_block $class_button' title='turn this entry into $stepLabel' 
                                $disabled_hold
                                href='javascript:void(0)' 
                                onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</button>";
                        }
                        else {
                            $actionLabel = "QR untuk Handphone";
                            $targetFollowupLink = "followupDariHp";
                            $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                            $fpLink = MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number;
                            $followupLink = "BootstrapDialog.show(
                                   {
                                        title:'" . $actionLabel . "',
                                        message: $('<div></div>').load('" . $fpLink . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                    );";
                            $tmp['action'] .= "<button class='btn btn-primary btn-block' $disabled_button title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</button>";
                        }
                        $tmp['action'] .= isset($strPengirim) ? $strPengirim : "";
                        $tmp['action'] .= "</div class='input-group'>";
                        $tmp['action'] .= $add_note;

                        if ($req_cancel_qty > 0 && $valid_qty == 0) {
                            $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
                            $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>menuggu approve cancel</button>";
                            //                            $tmp['action'] .= "<button type='button' class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>x</button>";
                            $tmp['action'] .= "</div>";
                        }
                    }

                }
                else {

                }

                //EDIT------------------------------
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    $arrStepAllow = array(1, 2);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        //                        if ($row->seller_id == my_id()) {
                        ////                        cekHijau("bisa edit... denagn auto reject...");
                        //                            $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        //                            $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        //                            $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        //                            $evMaster = evaluateMain_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        ////                        cekHitam("evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                        //                            if ($evPre == null && $evPost == null && $evCom == null && $evMaster == null) {
                        //                                $transaksiID_edit = $row->transaksi_id;
                        //                                $transaksiCustomerNama_f = "<strong>" . $row->customers_nama . "</strong>";
                        //                                $transaksiNomer = $row->nomer;
                        //                                $transaksiNomer_f = formatField_he_format("nomer_nolink", $row->nomer);
                        ////                                $link_edit = MODUL_PATH . "FollowUp/doPreEdit/" . $this->jenisTr . "/$transaksiID_edit/$currentStepNum?mode=autoPrePreview";
                        //                                $link_edit = MODUL_PATH . "FollowUp/doPreEdit/" . $this->jenisTr . "/$transaksiID_edit/$currentStepNum";
                        //                                $actionLabel_edit = "Edit";
                        //                                $tmp['action'] .= "<a class='btn btn-warning btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)'
                        //                                style='margin-top:5px;'
                        //                                onclick=\"confirm_alert_result('Peringatan...', 'Anda akan merubah/edit transaksi an. $transaksiCustomerNama_f ?', '$link_edit');\">" . $actionLabel_edit . "</a>";
                        //                            }
                        //                        }
                        //                        else{
                        if ($allowFollowup) {
                            $transaksiID_reject = $row->transaksi_id;
                            $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            $evMaster = evaluateMain_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                            //                        cekHitam("evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                            if ($evPre == null && $evPost == null && $evCom == null && $evMaster == null) {
                                $transaksiID_edit = $row->transaksi_id;
                                $transaksiCustomerNama_f = "<strong>" . $row->customers_nama . "</strong>";
                                $transaksiNomer = $row->nomer;
                                $transaksiNomer_f = formatField_he_format("nomer_nolink", $row->nomer);
                                //                                $link_edit = MODUL_PATH . "FollowUp/doPreEdit/" . $this->jenisTr . "/$transaksiID_edit/$currentStepNum?mode=autoPrePreview";
                                $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=edit";
                                $actionLabel_edit = "Edit";
                                $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='background-color:#000000;color:#ffffff;'
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
                            }
                        }

                    }
                    //                    }
                }
                //------------------------------
                //REJECT------------------------------
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    $arrStepAllow = array(1, 2, 3);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        //                        if ($row->seller_id == my_id()) {
                        //                        cekHijau("bisa reject...");
                        $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
                        //                        cekHitam($row->transaksi_id . " || evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                        //                        cekHere($row->transaksi_id);
                        if ($row->transaksi_id == "16632") {
                            cekHitam($row->transaksi_id . " || evPreProcc: $evPre, evPostProcc: $evPost, evCom: $evCom");
                        }
                        if ($evPre == null && $evPost == null && $evCom == null) {
                            $transaksiID_reject = $row->transaksi_id;
                            $masterRevertStep = ($currentStepNum - 1);
                            $childRevertStep = -($currentStepNum);

                            if ($this->reject == true) {

                                //                                $link_reject = MODUL_PATH . "FollowUp/doRevert/" . $this->jenisTr . "/$transaksiID_reject/$masterRevertStep/$childRevertStep/$currentStepNum?mode=autoPrePreview";
                                $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=reject";
                                $actionLabel_edit = "Reject 1 step";
                                $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject'\">" . $actionLabel_edit . " </button>";

                            }

                            if ($this->reject_all == true) {

                                //                                $link_reject_all = MODUL_PATH . "FollowUp/doRevertAll/" . $this->jenisTr . "/$transaksiID_reject/$currentStepNum?mode=autoPrePreview";
                                $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr . "/$transaksiID_reject/$nextStepNum/" . $row->sub_step_number . "?getmode=rejectall";
                                $actionLabel_edit = "Reject all step";
                                $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                style='background-color:#000000;color:#ffffff;'
                                $disabled_hold $disabled_button_reject
                                onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";

                            }
                        }
                        //                        }
                    }
                }
                //------------------------------
                //UPLOAD IMAGES
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    //shippingMethod
                    //                    arrPrint($tmp);
                    $arrStepAllow = array(1, 2);
                    if (in_array($row->sub_step_number, $arrStepAllow)) {
                        if ($row->shippingMethod == 3) {
                            //ini installation
                            $file_e = "";
                            $imaster_id = $row->id_master;
                            $imageRecorder = MODUL_PATH . get_class() . "/recordImage";
                            $iID = $numb;
                            if (isset($relImages[$row->id_master])) {
                                //                                $label_image = preg_replace('/\d{10}/', '...', $relImages[$row->id_master]["nama"]);
                                $label_image = strlen($relImages[$row->id_master]["nama"]) > 10 ? substr($relImages[$row->id_master]["nama"], 0, 10) . "..." : $relImages[$row->id_master]["nama"];
                                $label_ori = $relImages[$row->id_master]["nama"];
                                $linkDelete = "$imageRecorder/$imaster_id?iID=$iID&rel=delete";

                                $tmp['action'] .= "<div style='margin-top: 5px;position:relative;min-height: 30px;padding: 5px;' class='_div_$iID'>";
                                $tmp['action'] .= "<span id='overlay_c' class='overlay_$iID'><i class='fa fa-refresh fa-spin'></i></span>";
                                $tmp['action'] .= "<label title='$label_ori'><i class='fa fa-image'></i> $label_image</label>";
                                $tmp['action'] .= "<button type='button' class='btn btn-xs btn-danger' title='hapus foto' onclick =\"top.$('.overlay_$iID').show();document.getElementById('result').src='$linkDelete'\"> hapus</button>";
                                $tmp['action'] .= "</div>";
                            }
                            else {
                                $file = isset($row->images) ? $row->images : "";
                                $file_e = urlencode($file);

                                $tmp['action'] .= "<div style='margin-top: 5px;position:relative;min-height: 30px;padding: 5px;' class='_div_$iID'>";
                                $tmp['action'] .= "<span id='overlay_c' class='overlay_$iID'><i class='fa fa-refresh fa-spin'></i></span>";
                                $tmp['action'] .= "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$imaster_id?iID=$iID&valValue=$file_e' target='result'>";
                                $tmp['action'] .= "<input type='file' class='btn btn-warning btn-block btn-xs' id='file-upload_$iID' style='border: none;padding:5px;' name='file' class='file' onchange=\"top.$('.overlay_$iID').show();document.getElementById('myForm_$iID').submit();\">";
                                $tmp['action'] .= "<div>Resolusi 1600 x 1204</div>";
                                $tmp['action'] .= "</form>";
                                $tmp['action'] .= "</div>";
                            }
                        }
                        else {

                        }
                    }
                }

                $tmpMark = array();
                $tmp['keterangan'] = "-";
                if ($row->partial == 1) {
                    $tmpMark['style'] = "background-color:yellow;";
                    $tmp['keterangan'] = "<span style='color:red;'>transaksi diproses sebagian</span>";
                }


                $arrayOnprogress[] = $tmp;
                $arrayOnprogressGroup[$currentStepNum][] = $tmp;

                $arrayOnprogressPartialMark[] = $tmpMark;
                $arrayOnprogressGroupPartialMark[$currentStepNum][] = $tmpMark;

            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            $isDisableMakeTrans = isset($this->configUi[$this->jenisTr]['isDisableMakeTrans']) ? $this->configUi[$this->jenisTr]['isDisableMakeTrans'] : false;
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            if ($isDisableMakeTrans) {
                $addLink = null;
            }
            else {
                $addLink = array(
                    "link" => $targetUrl,
                    "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
                );
            }

        }
        else {
            $addLink = null;
        }
        //endregion

        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $historyFieldsDt = isset($this->configUi[$jenisTr]['historyFields'][$jenisTrsub]) ? $this->configUi[$jenisTr]['historyFields'][$jenisTrsub] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $availDbTable = $tr->getAvailTable($historyFieldsDt);

        //-----------------------------------------------------
        $arrJenisTrCek = array("587", "687", "1587", "1687");
        if (in_array($jenisTr, $arrJenisTrCek)) {
            $transaksiName = isset($this->configUi[$jenisTr]['label']) ? $this->configUi[$jenisTr]['label'] : NULL;
            $subplace = isset($this->configUi[$jenisTr]['steps'][1]['subplace']) ? $this->configUi[$jenisTr]['steps'][1]['subplace'] : NULL;
            if ($subplace != NULL) {
                if (($subplace == "warehouse") && ($this->session->login['gudang_id'] < 0)) {
                    //                    cekPink("SESUAI");
                    $msg = NULL;
                }
                elseif (($subplace == "warehouse_ng") && ($this->session->login['gudang_id'] > 0)) {
                    //                    cekPink("SESUAI NOT GOOD");
                    $msg = NULL;
                }
                else {
                    $msg = "Anda tidak memiliki kewenangan untuk membuat request $transaksiName";
                    $addLink = array();
                }
            }
        }

        //-----------------------------------------------------
        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        //        cekMerah("Grabing data " . $val . " Seconds");

        $link_undoneList_kurir = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";

        $data = array(
            "mode" => isset($this->configUi[$jenisTr]["mode"]) ? $this->configUi[$jenisTr]["mode"] : $this->uri->segment(3),
            "isMobile" => $isMob,
            "errMsg" => $this->session->errMsg,
            "template" => $this->configUi[$jenisTr]["template"],
            "title" => $this->configUi[$jenisTr]["label"],
            "subTitle" => $this->configUi[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            'addLink' => $addLink,
            "allSteps" => $this->allSteps,
            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> recent " . $this->configUi[$jenisTr]["label"] . " histories",
            "arrayHistoryLabels" => array("dtime" => "time") + $historyFields,
            "arrayHistoryLabelsDt" => $historyFieldsDt,
            "availDbTable" => $availDbTable,
            "arrayHistory" => isset($arrayHistory) ? $arrayHistory : array(),
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> TRANSAKSI YANG PERLU ACTION ",
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgressToPay" => $paymentConfig,
            "itemLabels" => isset($itemLabels) ? $itemLabels : "",
            "srcLabel" => isset($srcLabel) ? $srcLabel : "",
            //            "arrayOnProgress" => array_merge($arrayOnprogress, $arrayOnprePre, $arrayOnpreDistribution),
            "steps" => $isMob == true ? array(2 => $steps[2]) : $steps,
            "arrayOnProgress" => (isset($arrayOnprogress) && sizeof($arrayOnprogress) > 0) ? $arrayOnprogress : array(),
            "arrayOnprogressGroup" => (isset($arrayOnprogressGroup) && (sizeof($arrayOnprogressGroup) > 0)) ? $arrayOnprogressGroup : array(),

            "arrayOnprePre" => $arrayOnprePre,
            "arrayOnprePreGroup" => $arrayOnprePreGroup,

            "arrayOnpreDistribution" => isset($arrayOnpreDistribution) ? $arrayOnpreDistribution : array(),
            "arrayOnpreDistributionGroup" => isset($arrayOnpreDistributionGroup) ? $arrayOnpreDistributionGroup : array(),

            "entities" => isset($entities) ? $entities : array(),
            "recapTitle" => "<span class='fa fa-newspaper-o'></span> today " . $this->configUi[$jenisTr]["label"] . " reports",
            "arrayRecapLabels" => isset($recapLabels) ? $recapLabels : array(),
            "arrayRecap" => isset($arrayRecap) ? $arrayRecap : array(),

            "onprogressViewTitle" => "<span class='fa fa-eye'></span> show incomplete step " . $this->configUi[$jenisTr]["label"],
            "onprogressViewSubTitle" => "<span class='text-black'>(daftar transaksi yang masih stanby di cabang tujuan)</span>",
            "arrayOnProgressView" => isset($arrayOnProgressView) ? $arrayOnProgressView : array(),
            "stepHistoryFields" => isset($stepHistoryFields) ? $stepHistoryFields : array(),

            "selectProcessor" => $selectProcessor,
            "sumFooter" => (isset($sumFooterResult) && sizeof($sumFooterResult) > 0) ? $sumFooter : array(),
            "scriptBottom" => $scriptBottom,
            "btnLabel" => $extData,
            "actionTarget" => $extact,
            "sumFooter" => isset($sumFooter) ? $sumFooter : "",

            "arrayOnprogressPartialMark" => (isset($arrayOnprogressPartialMark) && sizeof($arrayOnprogressPartialMark) > 0) ? $arrayOnprogressPartialMark : array(),
            "arrayOnprogressGroupPartialMark" => (isset($arrayOnprogressGroupPartialMark) && sizeof($arrayOnprogressGroupPartialMark) > 0) ? $arrayOnprogressGroupPartialMark : array(),

            "defaultItemTrgEditable" => isset($defaultItemTrgEditable) ? $defaultItemTrgEditable : array(),
            "editItemTrg" => MODUL_PATH . "_followupLiveEdit/editEfaktur/" . $this->jenisTr . "/",


            "link_scan_mobile" => ($this->session->login["employee_type"] == "employee_kirim") ? MODUL_PATH . "FollowUp/followupScanMobile/$jenisTr" : NULL,
            "link_undoneList_kurir" => $link_undoneList_kurir,
        );
        $this->load->view("transaksi", $data);
    }

    public function doReleaserTrans()
    {
        $jenisTr = url_segment(4);
        $trid = $_GET['trid'];
        if ($trid > 0) {
            $result = releaserLockerTrans($trid);
            if ($result == true) {
                echo "<script>
                    top.$('#btn_approve_$trid').removeAttr('disabled');
                </script>";
            }
            $arrAlert = array(
                "type" => "success",
                "title" => "BERHASIL",
                "html" => "Transaksi berhasil dibuka dan bisa diapprove.",
                "timer" => "1500",
                "showConfirmButton" => false,
                "allowOutsideClick" => false,
            );
            echo swalAlert($arrAlert);

        }

    }
}
