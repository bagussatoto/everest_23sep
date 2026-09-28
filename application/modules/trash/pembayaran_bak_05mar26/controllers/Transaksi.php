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
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        $this->configMasterUi = $this->config->item("heTransaksi_ui");

//        arrPrint($this->session->login);

    }

    public function index_OLD()
    {
        // arrPrint($_SESSION);
        $cCode = $this->cCode;
        // cekKuning($cCode);
        if (isset($_SESSION[$cCode])) {
            // cekKuning("shopping-cart (creator)");
            arrprint($_SESSION[$cCode]);
        }
        else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }

    public function validate()
    {

        $fieldOpnameEntryValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartFieldOpnameEntryValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartFieldOpnameEntryValidators"] : array();
        $fieldOpnameValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartFieldOpnameValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartFieldOpnameValidators"] : array();

        $fieldValidatorRules = isset($this->configUi[$this->jenisTr]["shoppingCartFieldValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartFieldValidators"] : array();
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
        $fieldValidatorsValue = isset($this->configUi[$this->jenisTr]['shopingCartValueValidator']) ? $this->configUi[$this->jenisTr]['shopingCartValueValidator'] : array();
        $fieldValidatorPairedItem = isset($this->configUi[$this->jenisTr]["shoppingCartValidatorsPairedItem"]) ? $this->configUi[$this->jenisTr]["shoppingCartValidatorsPairedItem"] : array();
        $unionValidateComparison = isset($this->configUi[$this->jenisTr]["shopingCartUnionComparison"]) ? $this->configUi[$this->jenisTr]["shopingCartUnionComparison"] : array();
        $unionValidate = isset($this->configUi[$this->jenisTr]["shoppingCartUnionValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartUnionValidators"] : array();
        $balanceValidate = isset($this->configUi[$this->jenisTr]["shoppingCartBalanceValidate"]) ? $this->configUi[$this->jenisTr]["shoppingCartBalanceValidate"] : array();
        $shopingCartPaymentItemsValidator = isset($this->configUi[$this->jenisTr]["shopingCartPaymentItemsValidator"]) ? $this->configUi[$this->jenisTr]["shopingCartPaymentItemsValidator"] : array();
        $previewCtr = isset($this->configUi[$this->jenisTr]["previewCtr"]) ? $this->configUi[$this->jenisTr]["previewCtr"] : matiHere('controller preview belum diset');
        $validatorSumberDana = isset($this->configUi[$this->jenisTr]["validatorSumberDana"]) ? $this->configUi[$this->jenisTr]["validatorSumberDana"] : array();
        $validatorKewajibanBayar = isset($this->configUi[$this->jenisTr]["validatorKewajibanBayar"]) ? $this->configUi[$this->jenisTr]["validatorKewajibanBayar"] : array();
        $efakturValidator = isset($this->configUi[$this->jenisTr]["efakturValidator"][1]) ? $this->configUi[$this->jenisTr]["efakturValidator"][1] : array();
        $shoppingCartAdvanceSubFieldsValidator = isset($this->configUi[$this->jenisTr]["shoppingCartAdvanceSubFieldsValidator"][1]) ? $this->configUi[$this->jenisTr]["shoppingCartAdvanceSubFieldsValidator"][1] : array();


        $cCode = $this->cCode;
        // matiHere($_SESSION[$cCode]["main"]["pphGateId"]);
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
                                $errMsgs[] = "$label harus lebih dari 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }

        }
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $aSpec) {
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    //--------------
                    if (isset($aSpec["noValidateReplacer"]["key"])) {
                        $key_validate = $aSpec["noValidateReplacer"]["key"];
                        if (isset($_SESSION[$cCode]["main"][$key_validate]) && ($_SESSION[$cCode]["main"][$key_validate] > 0)) {
                            $aSpec["noValidate"] = false;
                        }
                        else {
                            $aSpec["noValidate"] = true;
                        }
                    }
                    //--------------
                    if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                    }
                    else {
                        if (isset($aSpec['labelValidate'])) {
                            $elementMsg = $aSpec['labelValidate'];
                        }
                        else {
                            $elementMsg = ($aSpec['label'] . " wajib dipilih!");
                        }
                        $errMsgs[] = $elementMsg;
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
                                    $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!");
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
                            unset($_SESSION[$cCode]['add_rows']["nilai_entry"]);
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
                                    $err[$field] = $fieldLabel;

                                }
                            }
                            if (sizeof($err) == sizeof($fieldUnion)) {
                                $errMsgs[] = "cash account/ metode pembayaran tidak ada yang dipilih, silahkan periksa kembali. code: " . __LINE__;
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

            arrPrint($unionValidateComparison);
            arrPrint($unionValidate);
            if (array_key_exists("source", $val)) {
                $cekval = $val["target"];
                if (!isset($_SESSION[$cCode]['main'][$cekval])) {
                    foreach ($unionValidateComparison as $data) {
                        cekHere(":: sampai sini $cekval :: " . $data[$cekval]);
                        if (isset($data[$cekval])) {
                            $validateUnion = 0;
                            foreach ($unionValidate as $unionFields) {
                                foreach ($unionFields as $field => $fLabel) {
                                    if (isset($_SESSION[$cCode]['main'][$field]) && $_SESSION[$cCode]['main'][$field] > 0) {
                                    }
                                    else {
                                        $validateUnion++;
                                        $err[$field] = $fLabel;

                                    }
                                }
                            }
                            cekHere(sizeof($unionFields));
                            arrPrint($err);
                            if (sizeof($err) == sizeof($unionFields)) {
                                $errMsgs[] = "cash account /metode pembayaran tidak ada yang dipilih, silahkan periksa kembali. code: " . __LINE__;

                            }
                            // cekMErah();
                            // $errMsgs[] = $data[$cekval];
                            // $errErrorException[] = $data[$cekval];//dimatiin diganti dengan union validate 9 nov 2021
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
//                                    $errMsgs[] = "$label $name must be > 0";
                                    $errMsgs[] = "$label $name harus lebih dari 0";
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
        if (sizeof($fieldValidatorsValue) > 0) {
            // matiHere();
            foreach ($fieldValidatorsValue as $field => $label) {
                // matiHEre($field);
                if ($_SESSION[$cCode]['main'][$field] == 0) {
                    $errMsgs[] = "$label";
                    $errRows[] = $field;

                }
            }

        }
        // arrPrint($errRows);
        // matiHere(__LINE__." validator");
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

        if (sizeof($shopingCartPaymentItemsValidator) > 0) {
            foreach ($shopingCartPaymentItemsValidator as $key => $spec) {
                $target_key = $spec["target"];
                $label_cek = $spec["label"];
                $label2_cek = $spec["label2"];
                if (isset($_SESSION[$cCode]['main'][$target_key]) && ($_SESSION[$cCode]['main'][$target_key] < 1)) {
                    $errMsgs[] = $label2_cek;
                }

                if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    $no = 0;
                    foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                        $no++;
                        if ($iSpec[$target_key] <= 1) {
                            $nomer_nota = formatField_he_format("nomer_nolink", $iSpec["nama"]);
                            $label = str_replace("{nota}", $nomer_nota, $label_cek);
                            $errMsgs[] = $label . " <span class='meta'>(baris $no)</span>";
                        }
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

        if (sizeof($validatorSumberDana) > 0) {
            $total_item_sumber_dana_dipakai = array();
            $total_item_sumber_dana = sizeof($validatorSumberDana);
            foreach ($validatorSumberDana as $sumber_dana => $sumber_dana_label) {
                if (isset($_SESSION[$cCode]["main"][$sumber_dana]) && ($_SESSION[$cCode]["main"][$sumber_dana] > 0)) {
//                    cekHijau("$sumber_dana || " . $_SESSION[$cCode]["main"][$sumber_dana]);
                }
                else {
                    $total_item_sumber_dana_dipakai[$sumber_dana] = $sumber_dana_label;
                }
            }
//            arrPrint($total_item_sumber_dana_dipakai);
//            arrPrintCyan($total_item_sumber_dana);
            if (sizeof($total_item_sumber_dana_dipakai) == $total_item_sumber_dana) {
                $msg = "Silahkan pilih sumber dana yang digunakan untuk pembayaran ini. code: " . __LINE__;
                mati_disini($msg);
            }
        }
        if (sizeof($validatorKewajibanBayar) > 0) {
            $tipe_transaksi_sumber = isset($_SESSION[$cCode]['main']['tipe_transaksi_sumber']) ? $_SESSION[$cCode]['main']['tipe_transaksi_sumber'] : 0;
            if ($tipe_transaksi_sumber == 1) {

            }
            else {
                if (isset($validatorKewajibanBayar["enabled"]) && ($validatorKewajibanBayar["enabled"] == true)) {
                    $key_source = $validatorKewajibanBayar["key_source"];
                    $key_target = $validatorKewajibanBayar["key_target"];
                    if ($_SESSION[$cCode]["main"][$key_source] > 5) {
                        if ((!isset($_SESSION[$cCode]["main"][$key_target])) && ($_SESSION[$cCode]["main"][$key_target] == NULL)) {
                            $nilai = number_format($_SESSION[$cCode]["main"][$key_source], 0, ".", ",");
                            $sumber_dana = "";
                            if (sizeof($validatorSumberDana) > 0) {
                                $sumber_dana = implode(", ", $validatorSumberDana);
                            }
                            $msg = "Masih ada kewajiban bayar $nilai, silahkan pilih sumber dana yang masih tersedia ($sumber_dana). ";
                            mati_disini($msg);
                        }

                    }
                }
            }
        }
        $efakturValidators = isset($this->configUi[$this->jenisTr]["efakturValidator"]) ? $this->configUi[$this->jenisTr]["efakturValidator"] : array();
        if (sizeof($efakturValidators) > 0) {
            foreach ($efakturValidators as $idx => $efakturValidator) {
            if (isset($efakturValidator["enabled"]) && ($efakturValidator["enabled"] == true)) {

                    $gateSource = $efakturValidator["gateSource"];
                    $sources = $efakturValidator["source"];
                    $koloms = $efakturValidator["kolom"];

                    if ($gateSource == "items6_sum" && $_SESSION[$cCode]["main"]["skip_faktur"]) {
                        // Skip for regular if skip_faktur is checked
                    } else {
                    foreach ($sources as $vall) {
                            if (isset($_SESSION[$cCode]["main"][$vall]) && $_SESSION[$cCode]["main"][$vall] > 0) {
                            foreach ($koloms as $keyy => $labell) {
                                if (!isset($_SESSION[$cCode]["main"][$keyy]) || ($_SESSION[$cCode]["main"][$keyy] == NULL)) {
                                    $errMsgs[] = "$labell";
                                }
                            }
                        }
                    }

                    if (isset($_SESSION[$cCode][$gateSource]) && sizeof($_SESSION[$cCode][$gateSource]) > 0) {
                        $ppn_final_total = 0;
                        $nom = 0;
                        foreach ($_SESSION[$cCode][$gateSource] as $ii => $iiSpec) {
                            $nom++;
                            $noii = $ii + 1;
                            $ppn_final = isset($iiSpec["ppn_final"]) ? $iiSpec["ppn_final"] : 0;
                            $ppn_sudah_faktur = isset($iiSpec["ppn_sudah_faktur"]) ? $iiSpec["ppn_sudah_faktur"] : 0;
//                            if (($ppn_sudah_faktur > 0) && ($ppn_final == 0)) {
                            if (($ppn_sudah_faktur > 0)) {
                                if ($ppn_final == 0) {
                                    $msg = "Nilai PPN pada formulir $nom sebesar 0. Silahkan diisi dengan benar atau didelete bila tidak diperlukan. code: " . __LINE__;
                                    mati_disini($msg);
                                }
                                else {
                                        if (sizeof($koloms) > 0) {
                                            foreach ($koloms as $key => $label) {
                                            if (!isset($iiSpec[$key])) {
                                                $errMsgs[] = $label;
                                            }
                                            elseif (isset($iiSpec[$key]) && ($iiSpec[$key] == NULL)) {
                                                $errMsgs[] = $label;
                                            }
                                        }
                                    }
                                }
                            }
                            $ppn_final_total += $ppn_final;
                            //----

                        }

                            if ($gateSource == "items6_sum") {
                        $selisih = $ppn_final_total - $_SESSION[$cCode]["main"]["ppn_netto"];
                        $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
                        if ($selisih > 100) {
//                            $msg = "Selisih nilai PPN melebihi batasan. code: " . __LINE__;
                            $msg = "Terdapat selisih pada nilai PPN dari tagihan dengan input faktur. Silahkan dikoreksi lagi. code: " . __LINE__;
                            mati_disini($msg);
                        }
                    }
                        }

                }

            }
        }
        }

        if (sizeof($shoppingCartAdvanceSubFieldsValidator) > 0) {
            if (isset($_SESSION[$cCode]["items4"]) && sizeof($_SESSION[$cCode]["items4"]) > 0) {
                foreach ($_SESSION[$cCode]["items4"] as $trid => $spec) {// transaksi_id
                    foreach ($spec as $iid => $iiSpec) {// id biaya/jasa
                        $pph_cek = $_SESSION[$cCode]["items2"][$trid][$iid]["pph"];
                        foreach ($iiSpec as $cek => $iiiSpec) {
                            foreach ($shoppingCartAdvanceSubFieldsValidator as $b_cek => $specc) {
                                if ($pph_cek == $b_cek) {
                                    foreach ($specc as $key_cek => $val_cek) {
                                        if ($iiiSpec[$key_cek] == NULL) {
                                            $errMsgs[] = $val_cek;
//                                            mati_disini($val_cek);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

//mati_disini(__LINE__);


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
            // sementara ditanam langsung disini.
            case "462":
            case "1462":
                if (!isset($_SESSION[$cCode]['main']['pphGateLabel'])) {
                    $errMsgs[] = "METODE PPH23 dipotong atau tidak dipotong belum ditentukan. Silahkan hubungi Admin.";
                }
                elseif (isset($_SESSION[$cCode]['main']['pphGateLabel']) && ($_SESSION[$cCode]['main']['pphGateLabel'] == NULL)) {
                    $errMsgs[] = "METODE PPH23 dipotong atau tidak dipotong belum ditentukan. Silahkan hubungi Admin.";
                }
                break;
            case "114":

                $ppn_bendahara = isset($_SESSION[$cCode]['main']['ppn_bendahara_negara']) && $_SESSION[$cCode]['main']['ppn_bendahara_negara'] * 1 > 0 ? $_SESSION[$cCode]['main']['ppn_bendahara_negara'] * 1 : 0;
                $ppn_pib = isset($_SESSION[$cCode]['main']['ppn_pib']) && $_SESSION[$cCode]['main']['ppn_pib'] * 1 > 0 ? $_SESSION[$cCode]['main']['ppn_pib'] * 1 : 0;
                $ppn_masukan = isset($_SESSION[$cCode]['main']['ppn_masukan']) && $_SESSION[$cCode]['main']['ppn_masukan'] * 1 > 0 ? $_SESSION[$cCode]['main']['ppn_masukan'] * 1 : 0;

                //PPN KELUARAN
                $nilai_sisa = isset($_SESSION[$cCode]['main']['nilai_sisa']) && $_SESSION[$cCode]['main']['nilai_sisa'] * 1 > 0 ? $_SESSION[$cCode]['main']['nilai_sisa'] * 1 : 0;

                //NILAI DISETOR
                $harus_bayar = isset($_SESSION[$cCode]['main']['harus_bayar']) && $_SESSION[$cCode]['main']['harus_bayar'] * 1 > 0 ? $_SESSION[$cCode]['main']['harus_bayar'] * 1 : 0;

                //SALDO DEPOSIT
                $saldo_deposit = isset($_SESSION[$cCode]['main']['saldo_deposit']) && $_SESSION[$cCode]['main']['saldo_deposit'] * 1 > 0 ? $_SESSION[$cCode]['main']['saldo_deposit'] * 1 : 0;

                //NILAI CASH PEMBAYARAN
                $cash_account__saldo = isset($_SESSION[$cCode]['main']['cash_account__saldo']) && $_SESSION[$cCode]['main']['cash_account__saldo'] * 1 > 0 ? $_SESSION[$cCode]['main']['cash_account__saldo'] * 1 : 0;

                //deposit (jika ppn keluaran < dari ppn masukan)
                //dibolehkan lewat dengan persetujuan

//                if ($cash_account__saldo < $harus_bayar) {
//                    $errMsgs[] = "cash account tidak cukup<br>di butuhkan <b>" . number_format($harus_bayar) . "</b><br>yg tersedia <b><r>" . number_format($cash_account__saldo) . "</r></b><br>silahkan ganti akun pembayaran***";
//                }

                if ($saldo_deposit > 0 && $harus_bayar == 0) {
                    $errMsgs[] = "saldo deposit lebih besar dari nilai yang disetor, yakin untuk melanjutkan..???<br><small class=''>atau Anda bisa menambahkan beberapa komponen PPN Keluaran hingga nilai setoran lebih besar dari nilai saldo deposit.</small>";
                }

//                matiHere(__LINE__);

                break;
            case "115":
                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());
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
            switch ($this->jenisTr) {
                case "114":
                    $closable = "false";
                    $prevAction = $previewCtr . "/previewMulti";
                    break;
                default:
                    $closable = "true";
                    break;
            }
            $actionTarget = "top.BootstrapDialog.show({
                                title:'preview',
                                message: " . 'top.$' . "('<div></div>').load('" . base_url() . $this->modul . "/" . $prevAction . "/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
                                draggable:false,
                                size:top.BootstrapDialog.SIZE_WIDE,
                                type:top.BootstrapDialog.TYPE_DEFAULT,
                                closable:$closable,
                                }
                             );";

            echo "<script>top.close_holdon();$actionTarget</script>";

        }
    }

    public function viewUndoneItems()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;

        $historyFields = $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $dueDateAllow = isset($this->configUi[$jenisTr]['updateDueDate']) ? $this->configUi[$jenisTr]['updateDueDate'] : array();
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();
        $glanceHistoryFields = isset($this->configUi[$jenisTr]['glanceHistoryFields']) ? $this->configUi[$jenisTr]['glanceHistoryFields'] : array("nomer" => "receipt no");
        $glanceHistoryFields2 = array("nomer" => "receipt no"); //init;
        $selectorProcessor = base_url() . $this->configUi[$jenisTr]['selectorProcessor'] . "/$jenisTr";

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
        //cekLime($this->db->last_query());

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
            // cekHitam($selectKolom);
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
                            $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                            if ($returnVal == "") {
                                $tmp[$fName] = "-";
                            }
                            else {
                                $tmp[$fName] = $returnVal;
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

                    $tmp['action'] = "<div class='input-group'>";
                    $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' data-toggle='tooltip' href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\"><span class='glyphicon glyphicon-ok'></span> " . $actionLabel . "</a>";
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

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
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
                                                $arrtransaksi_no[$id_top1][$numb] = formatField("nomer", $numb);
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

        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();

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
                //arrPrint($row);
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
                //arrPrint($row);


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
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];

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


                $tmp['next_pic'] = "-";
                if (sizeof($arrNextPIC) > 0) {
                    if (isset($arrNextPIC[$row->next_substep_code][$row->next_substep_num])) {
                        $next_pic = "";
                        $nob = 1;
                        foreach ($arrNextPIC[$row->next_substep_code][$row->next_substep_num] as $spec) {
                            if ($row->cabang_id == $spec['cabang_id']) {

                                if ($next_pic == "") {
                                    $next_pic = "$nob. " . $spec['nama'];
                                }
                                else {
                                    $nob++;
                                    $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'];
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
                    //                        cekHere("koq");
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

                    $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";

                    $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                    $tmp['action'] = "<div class='input-group'>";
                    $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' 
                                onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                    //                        if ($allowJoin) {
                    //                            $tmp['action'] .= "<span class='input-group-addon'>";
                    //                            $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                    //                            $tmp['action'] .= "</span class='input-group-addon'>";
                    //                        }
                    $tmp['action'] .= "</div class='input-group'>";

                    if ($req_cancel_qty > 0 && $valid_qty == 0) {
                        $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
                        $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>menuggu approve cancel</button>";
                        //                            $tmp['action'] .= "<button type='button' class='btn btn-warning' title='sedang dalam process cancel packing' href='javascript:void(0)'>x</button>";
                        $tmp['action'] .= "</div>";
                    }

                }
                else {

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

                //                    break;
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

        //region prepare params to viewer
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
            "steps" => $steps,
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
        );
        //endregion
        $this->load->view("transaksi", $data);
    }

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
            cekHere("$masterRefCode :: $stateRefCode :: $stateRefNum");
            $progress2Fields = isset($this->configUi[$masterRefCode]['shortHistoryFields']) ? $this->configUi[$masterRefCode]['shortHistoryFields'] : "";
            $glanceHistoryFields2 = isset($this->configUi[$masterRefCode]['glanceHistoryFields']) ? $this->configUi[$masterRefCode]['glanceHistoryFields'] : array("nomer" => "receipt no");

            $reqFormTarget = MODUL_PATH . get_class($this) . "/swapFrom/" . $this->uri->segment(4);

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("transaksi.jenis_master='" . $masterRefCode . "'");
            $tr->addFilter("transaksi.jenis='" . $stateRefCode . "'");
            $tr->addFilter("transaksi.step_current='" . $stateRefNum . "'");
            if (isset($_GET['test']) && ($_GET['test'] == 1)) {
//                $tmpByReq = $tr->lookupRecentHistories()->result();

                $tr->addFilterJoin("transaksi_data.next_substep_code='" . $masterRefCode . "'");
                $tr->addFilterJoin("transaksi_data.produk_jenis='supplies'");
                $tr->addFilterJoin("transaksi_data.valid_qty>0");
                $tmpByReq = $tr->lookupJoined();
            }
            else {
                $tmpByReq = array();
            }
            cekHijau(__LINE__ . "<br>" . $this->db->last_query());
            matiHere(sizeof($tmpByReq));

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

                $tr->setFilters(array());
                $tr->addFilterJoin("transaksi_data.transaksi_id IN ('" . implode("','", $arrIds) . "')");
                $tr->addFilterJoin("transaksi_data.next_substep_code='" . $masterRefCode . "'");
                $tr->addFilter("transaksi.link_id=0");
                $tr->addFilterJoin("transaksi_data.produk_jenis='supplies'");
                $tr->addFilterJoin("transaksi_data.valid_qty>0");
                $trTr = $tr->lookupJoined();
                cekMerah(__LINE__ . " -/- " . $this->db->last_query());
                //arrPrint($trTr);
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
                                        "nomer" => formatField('nomer', $rows->nomer),
                                        "nomer_top" => formatField('nomer_top', $rows->nomer_top),
                                        "produk_id" => $rows->transaksi_id,
                                        "dtime" => formatField('dtime', $rows->dtime) . "<br><span class='meta'>" . timeSince(strtotime($rows->dtime)) . "</span>",
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
                                        $listingTrx[] = formatField('nomer_top', $nomer);
                                    }
                                    $clickEvent = "";
                                    if ($inputType == "radio" && !$needToClear) {
                                        $clickEvent = "onclick=\"document.getElementById('$key').submit()\"";
                                    }

                                    $produk_id = array(
                                        "select" => "<input type=$inputType name='prdID[]' value='" . $rows->produk_id . "' id='select_" . $key . "_" . $reqCtr . "' $clickEvent>",
                                        "produk_id" => $rows->produk_id,
                                        "dtime" => formatField('dtime', $rows->dtime) . "<br><span class='meta'>" . timeSince(strtotime($rows->dtime)) . "</span>",
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
    public function viewResume()
    {
        $globalVars = array();
        $no = $this->uri->segment(5);
        // $this->jenisTr = explode(".", $no)[0];
        $cCode = $this->cCode;
        $this->load->config("heAccounting");
        $accountChildsLinks = $this->config->item("accountChildsLinks");

        $this->load->model("MdlTransaksi");
        // $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp1 = $tr->lookupAll()->result();
//        showLast_query("biru");
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

        $jenisMasterTrans = $tmp1[0]->jenis_master;
        $transaksiID = $tmp1[0]->id;
        $transaksiTopID = $tmp1[0]->id_top;
        $step_number = $tmp1[0]->step_number < 1 ? 1 : $tmp1[0]->step_number;
        $configUiJenis = loadConfigModulJenis_he_misc($jenisMasterTrans, "coTransaksiUi");
        $globalVars['transaksi_nama'] = $configUiJenis['label'];
        $globalVars["transaksi_nama_" . $step_number] = $configUiJenis['steps'][$step_number]["label"];
        $globalVars["transaksi_actionLabel"] = $configUiJenis['steps'][$step_number]["actionLabel"];
        $pairRegistry = isset($configUiJenis["pairRegistries"]) ? $configUiJenis["pairRegistries"] : array("main", "items");
        $configNotes = isset($configUiJenis["canceledLabel"][$step_number]) ? $configUiJenis["canceledLabel"][$step_number] : NULL;

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


        //region swap from registry
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields($pairRegistry);//select fields jika ada, atau biarkana array untuk reset
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpReg = $tr->lookupDataRegistries()->result();

        if (sizeof($tmpReg) > 0) {
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $eParams) {
                    foreach ($pairRegistry as $param) {
                        $$param = blobDecode($eParams->$param);
                    }
                }
            }
        }
        //endregion

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
                $signValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
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
        //        $tmpEP = $trep->lookupEntryPoints_joined($masterID)->result();
        $tmpEP = $trep->lookupEntryPoints($masterID)->result();
        // showLast_query("biru");

        $epValues = array();
        if (sizeof($tmpEP) > 0) {
            foreach ($tmpEP as $ii => $row) {
                $jenisEx = explode("_", $row->jenis);
                $jenis = $jenisEx[0];
                $counterDecode = blobDecode($row->counters);
                $counterjenis = $jenis . "|" . $row->cabang_id;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $cGlobals = digit_5($counterGlobal);


                //                $epValues['sign_' . $row->transaksi_id . '_' . $row->step_number] = array(
                $epValues['sign_' . $row->id . '_' . $row->step_number] = array(
                    "title" => isset($this->configUi[$row->jenis_master]['steps'][$row->step_number]['label']) ? $this->configUi[$row->jenis_master]['steps'][$row->step_number]['label'] : "",
                    "cabang_nama" => $row->cabang_nama,
                    "label" => $row->jenis_label,
                    //                    "dtime" => $row->dtime,
                    "dtime" => isset($arrSignName[$ii]['dtime']) ? $arrSignName[$ii]['dtime'] : $row->dtime,
                    "nomer" => formatField_he_format("nomer_nolink", $row->nomer),
                    "nomer_orig" => $row->nomer,
                    // "oleh"               => $row->oleh_nama,
                    "oleh" => isset($arrSignName[$ii]['nama']) ? $arrSignName[$ii]['nama'] : $row->oleh_nama,
                    "caption_department" => "text",
                    "deskripsi" => $row->deskripsi,
                    "link_id" => $row->link_id,
                    "global_counter" => $cGlobals,
                );
            }
        }


        $signArray = sizeof($epValues) > 0 ? $epValues : $signValues;
        //arrPrint($signArray);

        if (sizeof($signArray) > 0) {
            $signStr .= "<div class='panel'>";
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

                $signDeskripsi = isset($sSpec['deskripsi']) ? " (" . $sSpec['deskripsi'] . ")" : "";
                $nomerOrig = $sSpec['nomer_orig'];
                $nomerOrigEx = explode("_", $nomerOrig);
                $nomerOrigNota = $nomerOrigEx[0];
                $nomerView = $nomerOrigNota . "-" . $sSpec['global_counter'];

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
                $signStr .= "<td>" . ($sSpec['title']);
                $signStr .= "</td>";
                $signStr .= "<td>" . (isset($sSpec['cabang_nama']) ? $sSpec['cabang_nama'] : "-");
                $signStr .= "</td>";
                //                $signStr .= "<td>" . ($sSpec['nomer']) . $signDeskripsi . "<br>$edit_description";
                $signStr .= "<td>" . (formatField_he_format("nomer_nolink", $nomerView)) . $signDeskripsi . "<br>$edit_description";
                $signStr .= "</td>";
                $signStr .= "<td>";
                $signStr .= ("<span class='fa fa-user'></span> " . $oleh_nama . "");
                $signStr .= "</td>";

                $signStr .= "<td>";
                // $signStr .= formatField_he_format("print_label", $nomerOrigNota);
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
            $j->setSortBy(array("kolom" => "id", "mode" => "ASC"));
            $tmp = $j->lookupAll()->result();
//            showLast_query("biru");
            $headers = array(
                "dtime" => "date",
                "rekening" => "account",
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $lItems = array();
            $lItemsNew = array();
            $lItemsDebet = array();
            $lItemsKredit = array();
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
                    $rekening_alias = isset(fetchAccountStructureAlias()[$row->rekening]) ? fetchAccountStructureAlias()[$row->rekening] : $row->rekening;
                    if ($row->debet > 0 || $row->kredit > 0) {
                        $strLink = $defLink;
                        $lItems[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                    if ($row->debet > 0) {
                        $strLink = $defLink;
                        $lItemsDebet[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                    if ($row->kredit > 0) {
                        $strLink = $defLink;
                        $lItemsKredit[$row->cabang_id][$row->urut][] = array(
                            "dtime" => $row->dtime,
                            "rekening" => $row->debet > 0 ? $rekening_alias : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rekening_alias,
                            "debet" => $row->debet > 0 ? $row->debet : "",
                            "kredit" => $row->kredit > 0 ? $row->kredit : "",
                            "link" => base_url() . $strLink,
                        );
                    }
                }
                ksort($lItems);

                foreach ($lItemsDebet as $cab_id => $dSpec) {
                    foreach ($dSpec as $urut => $uSpec) {
                        foreach ($uSpec as $spec) {
                            $lItemsNew[$cab_id][$urut][] = $spec;
                        }
                    }
                }
                foreach ($lItemsKredit as $cab_id => $kSpec) {
                    foreach ($kSpec as $urut => $uSpec) {
                        foreach ($uSpec as $spec) {
                            $lItemsNew[$cab_id][$urut][] = $spec;
                        }
                    }
                }
//                arrPrintHitam($lItemsNew);

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
                            $label_add = "";
                            if (isset($mpFields['label_add']) && (sizeof($mpFields['label_add']) > 0)) {
                                foreach ($mpFields['label_add'] as $l_add) {
                                    $label_add .= makeValue($l_add, $main_reg, $main_reg, "");
                                }
                            }

                            $addFildRowFields[$gateKey] = $mpFields['label'] . " $label_add";
                        }
                    }
                }

                //----------------------------------
                if ($itemNotApprove == true) {
                    $dTmp = $m->lookUpDetailTransaksi($transaksiTopID);

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
//        cekKuning("$notes_final");
        $data = array(
            "mode" => $this->uri->segment(3),
            "title" => $no,
            "showScheme" => $arrCons,
            "headerScheme" => $headerScheme,
            "headers" => $headers,
            "main" => isset($main) ? $main : array(),
//            "items" => $lItems,
            "items" => $lItemsNew,
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
                            $colValue .= isset($row->$key) ? formatField($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";
                    }

                    //                    $tmp[$fName] = formatField($fName, $row->$fName);
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
        arrPrint($this->uri->segment_array());

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
        // arrPrint($tmpTr);
        if (sizeof($tmpTr) > 0) {
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
                        //                        $mainData[$row->$srcKey][$srcKey_0] = formatField($srcKey_0, $val_main);

                        if (is_array($srcAlias)) {
                            $hisStep = $srcAlias['step'];
                            $hisKey = $srcAlias['key'];
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
                            $mainData[$row->$srcKey][$srcKey_0] = formatField_he_format($srcKey_0, $val_main);
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
                        $itemData[$row->$srcKey][$fieldKey][] = formatField_he_format($fieldKey, $val);
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
            //            arrPrintWebs($arrayItemsOutstandingPair);
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
                                    $addSpec = "<span style='width:200px;' class='pull-right text-right'>@ $price  |  $sum_price</span>";
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
                            $mainData[$masterID]['sub_outstanding_items'] = formatField("debet", $sub_tmpOutstandingPair);
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
            "subTitle" => "<b> Out Standing $menuLabel",
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
//        echo($_SERVER['REMOTE_ADDR']);
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

        $getStep = isset($_GET['step']) ? $_GET['step'] : 1;
        $baseUrl = base_url();
        $link_undoneList = MODUL_PATH . "Transaksi/selectPaymentExternSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";
        $link_historyList = MODUL_PATH . "History/showData/$jenisTr/?limit=10";
//
//        if ($this->jenisTr == "1483") {
//            $link_undoneList = MODUL_PATH . "Transaksi/selectPaymentSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";
//        }
        switch ($this->jenisTr) {
            case "115":
            case "1483":
                $link_undoneList = MODUL_PATH . "Transaksi/selectPaymentSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";
                break;
        }

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


        //region prepare params to viewer
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

            "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
            "steps" => $steps,
            "allSteps" => $this->allSteps,
        );
        //endregion

        $this->load->view("transaksi", $data);
    }

    public function selectPaymentExternSrc__()
    {
        $targetJenis = $this->uri->segment(4);
        heInitCart($targetJenis);

        cekBiru("biruuu");
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
        //
        //        $itemLabels["due_date"] = "due date";
        //        $itemLabels["aging"] = "aging(days)";
        //        $itemLabels["over_due"] = "over due";

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("sisa>0");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        //        cekHere($this->db->last_query());
        $tmpSrcDue = array();
        $dueEmployee = array();
        if ($readerDueDate) {

            $itemLabels["due_date"] = "due date";
            $itemLabels["aging"] = "aging(days)";
            $itemLabels["over_due"] = "over due";

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
                        "due_date" => formatField("dtime", $date_due),
                        "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                        "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                    );
                }
            }


        }


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
                    $tmp["link"] = MODUL_PATH . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
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

        $data = array(
            "mode" => $this->uri->segment(3),
            "title" => $this->configUi[$targetJenis]["label"],
            "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
            "items" => $items,
            "srcLabel" => $srcLabel,
            "itemLabels" => $itemLabels,
            "jenisTr" => $this->jenisTr,
        );

        if (!isset($_GET['json'])) {
            $this->load->view("transaksi", $data);
        }
        else {

            //            arrPrint($data);
        }

    }

    public function selectPaymentExternSrc_ori()
    {

        $targetJenis = $this->uri->segment(4);
        $master_target = $this->uri->segment(4);
        switch ($targetJenis) {
            case "111":
                $master_target = "489";
                break;
            default :
                $master_target = $this->uri->segment(4);
                break;
        }
        heInitCart($targetJenis);

        $refIDs = isset($_SESSION["_TR_" . $targetJenis]['main']['refIDs']) ? $_SESSION["_TR_" . $targetJenis]['main']['refIDs'] : array();
        //==dapatkan srcJenis
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
        $readerDueDate = isset($this->configUi[$targetJenis]['dueDateReader']) ? $this->configUi[$targetJenis]['dueDateReader'] : false;
        $showAll = isset($this->configUi[$targetJenis]['showAll']) ? $this->configUi[$targetJenis]['showAll'] : false;

        if (sizeof($paymentSources) > 0) {
            foreach ($paymentSources as $src => $mainSpecs) {
                if (sizeof($mainSpecs) > 0) {

                    foreach ($mainSpecs as $step => $sSpecTemp) {
                        $sCtr = 0;
                        foreach ($sSpecTemp as $sSpec) {
                            if (isset($sSpec['jenisTarget']) && $sSpec['jenisTarget'] == $targetJenis) {
                                $srcJenis = $sSpec['jenisSrc'];
                                $rawSrcJenis = $src;
                                $srcIndex = $sCtr;
                                $srcStep = $step;
                            }
                            else {
                                $srcJenis = isset($sSpec['jenisSrc']) ? $sSpec['jenisSrc'] : "";
                                $rawSrcJenis = "";
                                $step = "";
                                $srcIndex = "";
                                $srcStep = "";
                            }
                        }
                        $sCtr++;

                    }
                }
            }
        }


        $defItemLabels = array(
            "extern_nama" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] : "",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "tagihan_valas" => "due amount",
            "terbayar_valas" => "paid",
            "diskon_valas" => "discount",
            "sisa_valas" => "due remain***",
        );
        $itemLabels = (isset($this->configUi[$targetJenis]["shoppingCartReferenceExternFields"])) ? $this->configUi[$targetJenis]["shoppingCartReferenceExternFields"] : $defItemLabels;
        $this->load->model("MdlTransaksi");

        if (!isset($this->configUi[$targetJenis]['shopingcartSource'])) {

            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");

            switch ($this->uri->segment(4)) {
                case "111":
                    $tr->addFilter("ppn_sisa>1000");
                    break;
                default :
                    $tr->addFilter("sisa>1000");
                    break;
            }
            $tmpSrc = $tr->lookupPaymentSrcByJenis($master_target)->result();
//            cekMerah($this->db->last_query());
//            matiHere(__LINE__);
            $tmpSrcDue = array();
            $dueEmployee = array();
            if ($readerDueDate) {

                $itemLabels["due_date"] = "due date";
                $itemLabels["aging"] = "aging(days)";
                $itemLabels["over_due"] = "over due";

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
                    if ($dtime_now > $key_index) {
                        $dueEmployee[$cus_id] = array(
                            "due_date" => formatField("dtime", $date_due),
                            "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                            "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                        );
                    }
                }


            }
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
                    $classMarking = "";
                    if (isset($dueEmployee[$row->extern_id])) {
                        $classMarking = "bg-danger";
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
                            $dpp_ppn[$row->extern_id] = 0;
                            $ppn_sisa[$row->extern_id] = 0;
                            $ppn_approved[$row->extern_id] = 0;

                        }

                        $tmp = (array)$row;
                        $tmp["link"] = MODUL_PATH . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
                        $tmp["due_date"] = isset($dueEmployee[$row->extern_id]['due_date']) ? formatField_he_format("dtime", $dueEmployee[$row->extern_id]['due_date']) : "-";
                        $tmp["aging"] = isset($dueEmployee[$row->extern_id]['aging']) ? $dueEmployee[$row->extern_id]['aging'] : "-";
                        $tmp["over_due"] = isset($dueEmployee[$row->extern_id]['over_due']) ? $dueEmployee[$row->extern_id]['over_due'] : "-";
                        $tmp["class_marking"] = $classMarking;
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
                    $dpp_ppn[$row->extern_id] += isset($row->dpp_ppn) ? $row->dpp_ppn : 0;
                    $ppn_sisa[$row->extern_id] += isset($row->ppn_sisa) ? $row->ppn_sisa : 0;
                    $ppn_approved[$row->extern_id] += isset($row->ppn_approved) ? $row->ppn_approved : 0;
                    $srcLabel = $row->label;
                }
                foreach ($items as $externID => $iSpec) {
                    $items[$externID]['tagihan'] = $tagihans[$externID];
                    $items[$externID]['terbayar'] = $terbayars[$externID];
                    $items[$externID]['diskon'] = $diskons[$externID];
                    $items[$externID]['sisa'] = $sisas[$externID];
                    $items[$externID]['tagihan_valas'] = $tagihans_valas[$externID];
                    $items[$externID]['terbayar_valas'] = $terbayars_valas[$externID];
                    $items[$externID]['diskon_valas'] = $diskons_valas[$externID];
                    $items[$externID]['sisa_valas'] = $sisas_valas[$externID];
                    $items[$externID]['dpp_ppn'] = $sisas_valas[$externID];
                    $items[$externID]['ppn_approved'] = $sisas_valas[$externID];
                    $items[$externID]['dpp_ppn'] = $dpp_ppn[$externID];
                    $items[$externID]['ppn_approved'] = $ppn_approved[$externID];
                    $items[$externID]['ppn_sisa'] = $ppn_sisa[$externID];
                }
            }

            $link_top = NULL;
            if ($showAll == true) {
                $link_top = MODUL_PATH . get_class($this) . "/selectPaymentSrc/$targetJenis/";
            }

            $data = array(
                "mode" => $this->uri->segment(3),
                "title" => $this->configUi[$targetJenis]["label"],
                "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
                "items" => $items,
                "srcLabel" => $srcLabel,
                "itemLabels" => $itemLabels,
                "jenisTr" => $this->jenisTr,
                "link_top" => $link_top,
            );
//            cekMerah($this->uri->segment(3));

            if (!isset($_GET['json'])) {
                $this->load->view("transaksi", $data);
            }

        }
        else {

            //region yang terlibat ppn
            /*
             * 110 ppn keluran
             * 112,113 ppn masukan
             * 967 ppn return pembelian
             * 982 return penjualan
             * 981 pib
             * target jenis
             * 0000 ->tanda ppn masukan ataupun yang bersifat mengurangi ppn keluaran
             */
            $grupJenis = array(
                "112" => "itemsSrc",
                "113" => "itemsSrc",
                "981" => "itemsSrc1",
                "967" => "itemsSrc2",
                "114" => "items",
                "749" => "itemsTarget1",
                "982" => "itemsTarget1",
                // "itemsSrc"=>array("112","113"),
                // "itemsSrc1"=>array("967"),
                // "itemsSrc2"=>array("981"),
                // "items"=>array("114"),
                // "itemsTarget1"=>array("749"),
                // "itemsTarget2"=>array("982"),
            );
            // $arrayJenisSrc = array("110","111","112","113","967","749","681","982");
            // $targetjenis = array("0000","114");
            //endregion
            //ini dual shopincart contoh untuk setor ppn masukan kontra ppn keluaran
            // cekHitam("dual shopingcart");
            $defaultItemSrc = array(
                //                "extern_date2" => "date e-faktur",
                "dtime" => "tgl",
                "extern_date2" => "tgl<br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "extern_nama" => "vendor",
                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN NILAI",
            );
            $defaultItemTrg = array(
                "extern_date2" => "tgl <br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "customers_nama" => "Konsumen",
                "extern2_nama" => "Invoice",
                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN NILAI",
            );
            $defaultItemsTarget1 = array(
                "extern_date2" => "tgl <br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "extern_nama" => "Konsumen",
//                "label" => "Keterangan",
                "extern2_nama" => "Invoice",
//                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN NILAI",
            );
            $itemsSrc1Label = array(
                "extern_nama" => "No PO ",
                "suppliers_nama" => "vendor",
                "extern_date2" => "tgl pembayaran",
                "nomer" => "Nomor Pembayaran",
                "extern_label2" => "Nomor PIB",
                "sisa" => "PIB NILAI",
            );
            $defaultItemTrgEditable = array(
                "extern_label2",
            );

            $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
            $selectedTarget = $this->configUi[$targetJenis]['shopingcartSource']['target'];
            $returnRoutes = null != ($this->config->item("transaksi_returnRoutes")) ? $this->config->item("transaksi_returnRoutes") : array();
            $ppnDisabledConfig = isset($this->configUi[$targetJenis]['ppnDisabled']) ? $this->configUi[$targetJenis]['ppnDisabled'] : array();
            $jenisSrc = null;
            if (sizeof($paymentSources) > 0) {
                foreach ($paymentSources as $src => $sSpec) {
                    $payConfigs = $paymentSources[$src];
                    if (sizeof($payConfigs) > 0) {
                        foreach ($payConfigs as $paymentSrcConfigTmp) {
                            $sCtr = 0;
                            foreach ($paymentSrcConfigTmp as $paymentSrcConfig) {
                                if (isset($paymentSrcConfig['jenisTarget']) && $paymentSrcConfig['jenisTarget'] == $selectedTarget) {
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
                    $retCode = $returnRoutes[$jenisSrc];
                    $trr = new MdlTransaksi();
                    $trr->setFilters(array());
                    $trr->addFilter("param='main'");
                    $trr->addFilter("jenis='$retCode'");
                    $tmpR = $trr->lookupRegistries_joined()->result();
                    if (sizeof($tmpR) > 0) {
                        foreach ($tmpR as $row) {
                            $main = blobDecode($row->values);
                            $references[$main['referenceID']] = array(
                                "id" => $row->transaksi_id,
                                "nomer" => $row->nomer,
                                "refID" => $main['referenceID'],
                                "refJenis" => $main['referenceJenis'],
                                "refNum" => $main['referenceNomer'],
                                "harga" => isset($main['harga']) ? $main['harga'] : 0,
                                "nett" => isset($main['nett']) ? $main['nett'] : 0,
                            );
                        }
                    }
                }
            }

            // matiHEre($selectedTarget);
            //region items source
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("sisa>1000");
            $this->db->order_by("transaksi_payment_source.extern_date2", "asc");
            // $this->db->limit(200);
            $tmpSrc = $tr->lookupPaymentSrcByJenis($selectedTarget)->result();
            // $tmpSrc = $tr->lookUpAllPaymentSrc()->result();
            cekBiru($this->db->last_query());
//arrPrintPink($tmpSrc);

            $srcLabel = "";
            $sumFooter = array();
            $itemsSrc = array();
            $itemsSrc1 = array();
            $itemsSrc2 = array();
            $itemsTarget1 = array();
            $itemsTarget2 = array();
            // matiHEre();
            if (sizeof($tmpSrc) > 0) {
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                // arrPrint($fieldSrcs);
                foreach ($tmpSrc as $row) {
                    // arrPrint($row);
                    $tmp = array();
                    $jenis = $row->jenis;
                    $label = $row->label;
                    $transaksi_id = $row->id;
                    // foreach($fieldSrcs as $fname =>$flabel){
                    //     $tmp[$fname] = $row->$fname;
                    // }
                    $tmp['class_bg'] = "";
                    $tmp['refID'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refID'] : "";
                    $tmp['refNum'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refNum'] : "";
                    $tmp['refValue'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['nett'] : "";
//                    if (isset($ppnDisabledConfig['enabled']) && $ppnDisabledConfig['enabled'] == true) {
//                        if (($row->ppn_status == 1)) {
//                            $ppnDisabled[] = $row->transaksi_id;
//                            $tmp['notes'] = isset($ppnDisabledConfig['notes']) ? "<span style='color:red;font-size:10px;'>" . $ppnDisabledConfig['notes'] . "</span>" : "-";
//                        }
//                    }
                    switch ($jenis) {
                        case "111"://realisasi ppn masukan dari pembelian fg
                        case "112"://realisasi ppn masukan dari pembelian supplies
                        case "113"://realisasi ppn masukan dari pembelian jasa
                        case "489"://realisasi ppn masukan dari pembayaran
                        case "464a"://realisasi ppn masukan dari uang muka
                        case "3113"://realisasi ppn masukan dari pembelian jasa project
                            $itemsSrc[] = (array)$row + $tmp;
                            break;
                        case"967":
                            $itemsSrc2[] = (array)$row + $tmp;
                        case"682":
                            $itemsSrc1[] = (array)$row + $tmp;
                            // arrPrint($itemsSrc1);
                            // matiHEre();
                            break;
                        //------keatas base vendor
                        case "749":
                            if ($label == "ppn dibayar bendahara negara") {
                                $itemsTarget1[] = (array)$row + $tmp;
                            }
                            break;
                        case"982":
                            $itemsTarget2[] = (array)$row + $tmp;
                            break;
                    }
                    $trIDs[] = $row->transaksi_id;
                }

                // foreach ($defaultItemSrc as $fName => $fLabel) {
                //     foreach ($tmpSrc as $rowsTmp) {
                //         if (isset($rowsTmp->$fName) && is_numeric($rowsTmp->$fName)) {
                //             if (!isset($sumFooter['itemsSrc'][$fName])) {
                //                 $sumFooter['itemsSrc'][$fName] = 0;
                //             }
                //             $sumFooter['itemsSrc'][$fName] += $rowsTmp->$fName;
                //         }
                //     }
                //
                // }
            }
            if (sizeof($trIDs) > 0) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $lt = New MdlLockerTransaksi();
                $lt->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
                $lt->addFilter("state='active'");
                $lt->addFilter("jumlah='0'");
                $lt_tmp = $lt->lookupAll()->result();
                if (sizeof($lt_tmp) > 0) {
                    foreach ($lt_tmp as $lt_row) {
                        $lockerDisabled[] = $lt_row->transaksi_id;
                    }
                }
            }
            //endregion

            //region items
            $jenisSrc = null;
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
                    $retCode = $returnRoutes[$jenisSrc];

                    $trr = new MdlTransaksi();
                    $trr->setFilters(array());
                    $trr->addFilter("param='main'");
                    $trr->addFilter("jenis='$retCode'");
                    $tmpR = $trr->lookupRegistries_joined()->result();
                    //cekHere($this->db->last_query());

                    if (sizeof($tmpR) > 0) {
                        foreach ($tmpR as $row) {
                            $main = blobDecode($row->values);
                            $references[$main['referenceID']] = array(
                                "id" => $row->transaksi_id,
                                "nomer" => $row->nomer,
                                "refID" => $main['referenceID'],
                                "refJenis" => $main['referenceJenis'],
                                "refNum" => $main['referenceNomer'],
                                "harga" => isset($main['harga']) ? $main['harga'] : 0,
                                "nett" => isset($main['nett']) ? $main['nett'] : 0,
                            );
                        }
                    }
                }
            }

            $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();

            //==dapatkan daftar kolom dari srcJenis
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("sisa>1000");
//            $tr->addFilter("jenis=681");
            $this->db->order_by("transaksi_payment_source.extern_date2", "asc");
            // $this->db->limit(15);

            $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($targetJenis)->result();

            // cekHere($this->db->last_query());

            $externName = "";
            $items = array();
            $tempDueDate = array();
            $ppnDisabled = array();
            $lockerDisabled = array();
            $trIDs = array();
            if (sizeof($tmpSrc) > 0) {
                foreach ($tmpSrc as $rows) {
                    $tmp = array();
                    $jenis = $rows->jenis;
                    $transaksi_id = $rows->id;
                    $tmp['class_bg'] = "";
                    $tmp['refID'] = array_key_exists($rows->transaksi_id, $references) ? $references[$rows->transaksi_id]['refID'] : "";
                    $tmp['refNum'] = array_key_exists($rows->transaksi_id, $references) ? $references[$rows->transaksi_id]['refNum'] : "";
                    $tmp['refValue'] = array_key_exists($rows->transaksi_id, $references) ? $references[$rows->transaksi_id]['nett'] : "";
                    $items[] = (array)$rows + $tmp;
                    $externName = $rows->extern_nama;
                    $trIDs[] = $rows->transaksi_id;
                    foreach ($defaultItemTrg as $fName => $fLabel) {
                        if (isset($row->$fName) && is_numeric($rows->$fName)) {
                            if (!isset($sumFooter['items'][$fName])) {
                                $sumFooter['items'][$fName] = 0;
                            }
                            $sumFooter['items'][$fName] += $rows->$fName;
                        }
                    }
                }

                if (sizeof($trIDs) > 0) {
                    $this->load->model("Mdls/MdlLockerTransaksi");
                    $lt = New MdlLockerTransaksi();
                    $lt->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
                    $lt->addFilter("state='active'");
                    $lt->addFilter("jumlah='0'");
                    $lt_tmp = $lt->lookupAll()->result();
                    if (sizeof($lt_tmp) > 0) {
                        foreach ($lt_tmp as $lt_row) {
                            $lockerDisabled[] = $lt_row->transaksi_id;
                        }
                    }
                }

                foreach ($defaultItemTrg as $fName => $fLabel) {
                    foreach ($tmpSrc as $rowsTmp2) {
                        if (isset($rowsTmp2->$fName) && is_numeric($rowsTmp2->$fName)) {
                            if (!isset($sumFooter['items'][$fName])) {
                                $sumFooter['items'][$fName] = 0;
                            }
                            $sumFooter['items'][$fName] += $rowsTmp2->$fName;
                        }
                    }

                }
            }

            $arrayOnprogress = array(
                "itemsSrc" => $itemsSrc,
                "itemsSrc1" => $itemsSrc1,
                "itemsSrc2" => $itemsSrc2,
                "items" => $items,
                "itemsTarget1" => $itemsTarget1,
                "itemsTarget2" => $itemsTarget2,
            );

            unset($progressFields);

            $progressFields = array(
                "itemsSrc" => $defaultItemSrc,
                "items" => $defaultItemTrg,
                "itemsTarget1" => $defaultItemsTarget1,
                "itemsSrc1" => $itemsSrc1Label,
            );
            //endregion

            $extData = "continue " . $this->configUi[$this->jenisTr]['steps'][1]['label'];
            $extact = "top.$('#result').load('" . MODUL_PATH . "Transaksi/validate/" . $targetJenis . "?');";
            $selectProcessor = array(
                "items" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor"],
                "itemsSrc" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor2"],
                "itemsTarget1" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor3"],
                "itemsSrc1" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor4"],
            );

//arrPrint($srcLabel);
            $data = array(
                "mode" => isset($this->configUi[$this->jenisTr]["mode"]) ? $this->configUi[$this->jenisTr]["mode"] : $this->uri->segment(3), // index_multi
                "title" => $this->configUi[$targetJenis]["label"],
                "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
                "arrayOnProgress" => $arrayOnprogress,
                "srcLabel" => $srcLabel,
                "arrayProgressLabels" => $progressFields,
                "jenisTr" => $this->jenisTr,
                "selectProcessor" => $selectProcessor,
                "btnLabel" => $extData,
                "actionTarget" => $extact,
                "refIDs" => $refIDs,
                "defaultItemTrgEditable" => isset($defaultItemTrgEditable) ? $defaultItemTrgEditable : array(),
                "editItemTrg" => MODUL_PATH . "_followupLiveEdit/editEfaktur/" . $this->jenisTr . "/",
            );
            $this->load->view("transaksi", $data);
        }


    }

    public function selectPaymentExternSrc()
    {

        $targetJenis = $this->uri->segment(4);
        $master_target = $this->uri->segment(4);
        switch ($targetJenis) {
            case "111":
                $master_target = "489";
                break;
            default :
                $master_target = $this->uri->segment(4);
                break;
        }

        /**
         * pembersih session di cart
         * dimatikan supaya tidak hilang ketika direfresh
         * */
        // heInitCart($targetJenis);

        $refIDs = isset($_SESSION["_TR_" . $targetJenis]['main']['refIDs']) ? $_SESSION["_TR_" . $targetJenis]['main']['refIDs'] : array();
        $refItems = isset($_SESSION["_TR_" . $targetJenis]['items']) ? $_SESSION["_TR_" . $targetJenis]['items'] : array();
        $refItemSrc = isset($_SESSION["_TR_" . $targetJenis]['itemSrc']) ? $_SESSION["_TR_" . $targetJenis]['itemSrc'] : array();
        //==dapatkan srcJenis
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
        $readerDueDate = isset($this->configUi[$targetJenis]['dueDateReader']) ? $this->configUi[$targetJenis]['dueDateReader'] : false;
        $showAll = isset($this->configUi[$targetJenis]['showAll']) ? $this->configUi[$targetJenis]['showAll'] : false;

        if (sizeof($paymentSources) > 0) {
            foreach ($paymentSources as $src => $mainSpecs) {
                if (sizeof($mainSpecs) > 0) {

                    foreach ($mainSpecs as $step => $sSpecTemp) {
                        $sCtr = 0;
                        foreach ($sSpecTemp as $sSpec) {
                            if (isset($sSpec['jenisTarget']) && $sSpec['jenisTarget'] == $targetJenis) {
                                $srcJenis = $sSpec['jenisSrc'];
                                $rawSrcJenis = $src;
                                $srcIndex = $sCtr;
                                $srcStep = $step;
                            }
                            else {
                                $srcJenis = isset($sSpec['jenisSrc']) ? $sSpec['jenisSrc'] : "";
                                $rawSrcJenis = "";
                                $step = "";
                                $srcIndex = "";
                                $srcStep = "";
                            }
                        }
                        $sCtr++;

                    }
                }
            }
        }


        $defItemLabels = array(
            "extern_nama" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] : "",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "tagihan_valas" => "due amount",
            "terbayar_valas" => "paid",
            "diskon_valas" => "discount",
            "sisa_valas" => "due remain***",
        );
        $itemLabels = (isset($this->configUi[$targetJenis]["shoppingCartReferenceExternFields"])) ? $this->configUi[$targetJenis]["shoppingCartReferenceExternFields"] : $defItemLabels;
        $this->load->model("MdlTransaksi");

        if (!isset($this->configUi[$targetJenis]['shopingcartSource'])) {

            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");

            switch ($this->uri->segment(4)) {
                case "111":
                    $tr->addFilter("ppn_sisa>1000");
                    break;
                default :
//                    $tr->addFilter("sisa>1000");
                    $tr->addFilter("sisa>=5");//karena ada biaya 6 rupiah jadi filter lebih besar 100 dinon aktifkan
                    break;
            }
            $tmpSrc = $tr->lookupPaymentSrcByJenis($master_target)->result();
            cekMerah($this->db->last_query());
            //            matiHere(__LINE__);
            $tmpSrcDue = array();
            $dueEmployee = array();
            if ($readerDueDate) {

                $itemLabels["due_date"] = "due date";
                $itemLabels["aging"] = "aging(days)";
                $itemLabels["over_due"] = "over due";

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
                    if ($dtime_now > $key_index) {
                        $dueEmployee[$cus_id] = array(
                            "due_date" => formatField("dtime", $date_due),
                            "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                            "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                        );
                    }
                }


            }
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
                    $classMarking = "";
                    if (isset($dueEmployee[$row->extern_id])) {
                        $classMarking = "bg-danger";
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
                            $dpp_ppn[$row->extern_id] = 0;
                            $ppn_sisa[$row->extern_id] = 0;
                            $ppn_approved[$row->extern_id] = 0;

                        }

                        $tmp = (array)$row;
                        $tmp["link"] = MODUL_PATH . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
                        $tmp["due_date"] = isset($dueEmployee[$row->extern_id]['due_date']) ? formatField_he_format("dtime", $dueEmployee[$row->extern_id]['due_date']) : "-";
                        $tmp["aging"] = isset($dueEmployee[$row->extern_id]['aging']) ? $dueEmployee[$row->extern_id]['aging'] : "-";
                        $tmp["over_due"] = isset($dueEmployee[$row->extern_id]['over_due']) ? $dueEmployee[$row->extern_id]['over_due'] : "-";
                        $tmp["class_marking"] = $classMarking;
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
                    $dpp_ppn[$row->extern_id] += isset($row->dpp_ppn) ? $row->dpp_ppn : 0;
                    $ppn_sisa[$row->extern_id] += isset($row->ppn_sisa) ? $row->ppn_sisa : 0;
                    $ppn_approved[$row->extern_id] += isset($row->ppn_approved) ? $row->ppn_approved : 0;
                    $srcLabel = $row->label;
                }
                foreach ($items as $externID => $iSpec) {
                    $items[$externID]['tagihan'] = $tagihans[$externID];
                    $items[$externID]['terbayar'] = $terbayars[$externID];
                    $items[$externID]['diskon'] = $diskons[$externID];
                    $items[$externID]['sisa'] = $sisas[$externID];
                    $items[$externID]['tagihan_valas'] = $tagihans_valas[$externID];
                    $items[$externID]['terbayar_valas'] = $terbayars_valas[$externID];
                    $items[$externID]['diskon_valas'] = $diskons_valas[$externID];
                    $items[$externID]['sisa_valas'] = $sisas_valas[$externID];
                    $items[$externID]['dpp_ppn'] = $sisas_valas[$externID];
                    $items[$externID]['ppn_approved'] = $sisas_valas[$externID];
                    $items[$externID]['dpp_ppn'] = $dpp_ppn[$externID];
                    $items[$externID]['ppn_approved'] = $ppn_approved[$externID];
                    $items[$externID]['ppn_sisa'] = $ppn_sisa[$externID];
                }
            }

            $link_top = NULL;
            if ($showAll == true) {
                $link_top = MODUL_PATH . get_class($this) . "/selectPaymentSrc/$targetJenis/";
            }

            $data = array(
                "mode" => $this->uri->segment(3),
                "title" => $this->configUi[$targetJenis]["label"],
                "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
                "items" => $items,
                "srcLabel" => $srcLabel,
                "itemLabels" => $itemLabels,
                "jenisTr" => $this->jenisTr,
                "link_top" => $link_top,
            );
            //            cekMerah($this->uri->segment(3));

            if (!isset($_GET['json'])) {
                $this->load->view("transaksi", $data);
            }

        }
        else {
            $starttime = microtime(true);
            cekHitam("ini " . __LINE__);
            //region yang terlibat ppn
            /*
             * 110 ppn keluran
             * 112,113 ppn masukan
             * 967 ppn return pembelian
             * 982 return penjualan
             * 981 pib
             * target jenis
             * 0000 ->tanda ppn masukan ataupun yang bersifat mengurangi ppn keluaran
             */
            $grupJenis = array(
                "112" => "itemsSrc",
                "113" => "itemsSrc",
                "981" => "itemsSrc1",
                "967" => "itemsSrc2",
                "114" => "items",
                "749" => "itemsTarget1",
                "982" => "itemsTarget1",
                // "itemsSrc"=>array("112","113"),
                // "itemsSrc1"=>array("967"),
                // "itemsSrc2"=>array("981"),
                // "items"=>array("114"),
                // "itemsTarget1"=>array("749"),
                // "itemsTarget2"=>array("982"),
            );
            // $arrayJenisSrc = array("110","111","112","113","967","749","681","982");
            // $targetjenis = array("0000","114");
            //endregion
            //ini dual shopincart contoh untuk setor ppn masukan kontra ppn keluaran
            // cekHitam("dual shopingcart");
            $defaultItemSrc = array(
                //                "extern_date2" => "date e-faktur",
                "dtime" => "tgl terima",
                "extern_date2" => "tgl<br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "extern_nama" => "vendor",
                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN NILAI",
            );
            $defaultItemTrg = array(
                "fulldate" => "tgl entry",
                "extern_date2" => "tgl <br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "customers_nama" => "Konsumen",
                "extern2_nama" => "Invoice",
                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN NILAI",
                "returned" => "batal",
            );
            $defaultItemsTarget1 = array(
                "extern_date2" => "tgl <br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "extern_nama" => "Konsumen",
                //                "label" => "Keterangan",
                "extern2_nama" => "Invoice",
                //                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN NILAI",
            );
            $itemsSrc1Label = array(
                "extern_nama" => "No PO ",
                "suppliers_nama" => "vendor",
                "extern_date2" => "tgl pembayaran",
                "nomer" => "Nomor Pembayaran",
                "extern_label2" => "Nomor PIB",
                "sisa" => "PIB NILAI",
            );
            $itemsSrc4Label = array(
                "dtime" => "tgl terima",
                "extern_date2" => "tgl<br>e-faktur",
                "extern_label2" => "Nomor e-faktur",
                "extern_nama" => "vendor",
                "nomer" => "Realisasi",
                "extern_nilai2" => "DPP",
                "sisa" => "PPN DTP",
            );
            $defaultItemTrgEditable = array(
                "extern_label2",
            );

            //deteksi bulan dan tahun pajak untuk filter tgl efaktur-------
            $bulan_keluaran = isset($_GET["date_keluaran"]) ? $_GET["date_keluaran"] : date("Y-m");
            if (isset($_GET["date_keluaran"])) {
                $bulan_keluaran = $_GET["date_keluaran"];
            }
            elseif ($_SESSION["_TR_" . $targetJenis]['main']['date_keluaran']) {
                $bulan_keluaran = $_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'];
            }
            else {
                $bulan_keluaran = date("Y-m");
            }
            $bulan_keluaran_ex = explode("-", $bulan_keluaran);
            $bulan_keluaran_ex_1 = $bulan_keluaran_ex[0];// tahun
            $bulan_keluaran_ex_2 = $bulan_keluaran_ex[1];// bulan
//            $sesItemsDelete = isset($_GET["items_delete"]) ? $_GET["items_delete"] : 0;
//            if($sesItemsDelete == 1){
//                $_SESSION["_TR_" . $targetJenis]['items'] = array();
//            }
//            if(isset($_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'])){
//                if($_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'] != $bulan_keluaran){
//                    $_SESSION["_TR_" . $targetJenis]['items'] = array();
//
//                }
//            }
            //-------

            $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
            $selectedTarget = $this->configUi[$targetJenis]['shopingcartSource']['target'];
            $returnRoutes = null != ($this->config->item("transaksi_returnRoutes")) ? $this->config->item("transaksi_returnRoutes") : array();
            $ppnDisabledConfig = isset($this->configUi[$targetJenis]['ppnDisabled']) ? $this->configUi[$targetJenis]['ppnDisabled'] : array();
            $filterBulanConfig = isset($this->configUi[$this->jenisTr]["filterBulan"]) ? $this->configUi[$this->jenisTr]["filterBulan"] : array();

            $filter_bulan = false;
            if (sizeof($filterBulanConfig) > 0) {
                if (isset($filterBulanConfig["enabled"]) && ($filterBulanConfig["enabled"] == true)) {
                    $filter_bulan = $filterBulanConfig["enabled"];
                    $filter_bulan_tipe = $filterBulanConfig["tipe"];
                }
            }


            $jenisSrc = null;
            if (sizeof($paymentSources) > 0) {
                foreach ($paymentSources as $src => $sSpec) {
                    $payConfigs = $paymentSources[$src];
                    if (sizeof($payConfigs) > 0) {
                        foreach ($payConfigs as $paymentSrcConfigTmp) {
                            $sCtr = 0;
                            foreach ($paymentSrcConfigTmp as $paymentSrcConfig) {
                                if (isset($paymentSrcConfig['jenisTarget']) && $paymentSrcConfig['jenisTarget'] == $selectedTarget) {
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
//            if ($jenisSrc != null) {
//                if (isset($returnRoutes[$jenisSrc])) {
//                    $retCode = $returnRoutes[$jenisSrc];
//                    $trr = new MdlTransaksi();
//                    $trr->setFilters(array());
//                    $trr->addFilter("param='main'");
//                    $trr->addFilter("jenis='$retCode'");
//                    $tmpR = $trr->lookupRegistries_joined()->result();
//                    if (sizeof($tmpR) > 0) {
//                        foreach ($tmpR as $row) {
//                            $main = blobDecode($row->values);
//                            $references[$main['referenceID']] = array(
//                                "id" => $row->transaksi_id,
//                                "nomer" => $row->nomer,
//                                "refID" => $main['referenceID'],
//                                "refJenis" => $main['referenceJenis'],
//                                "refNum" => $main['referenceNomer'],
//                                "harga" => isset($main['harga']) ? $main['harga'] : 0,
//                                "nett" => isset($main['nett']) ? $main['nett'] : 0,
//                            );
//                        }
//                    }
//                }
//            }


            //region items source

            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("sisa>1000");
            $this->db->order_by("transaksi_payment_source.extern_date2", "asc");
//            $this->db->limit(100);
            $tmpSrc = $tr->lookupPaymentSrcByJenis($selectedTarget)->result();
            // $tmpSrc = $tr->lookUpAllPaymentSrc()->result();
            cekBiru($this->db->last_query());
            //arrPrintPink($tmpSrc);

            $srcLabel = "";
            $sumFooter = array();
            $itemsSrc = array();
            $itemsSrc1 = array();
            $itemsSrc2 = array();
            $itemsSrc4 = array();
            $itemsTarget1 = array();
            $itemsTarget2 = array();
            $itemsTarget2 = array();
            // matiHEre();
            if (sizeof($tmpSrc) > 0) {
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                // arrPrint($fieldSrcs);
                foreach ($tmpSrc as $row) {
                    // arrPrint($row);
                    $tmp = array();
                    $jenis = $row->jenis;
                    $label = $row->label;
                    $transaksi_id = $row->id;
                    // foreach($fieldSrcs as $fname =>$flabel){
                    //     $tmp[$fname] = $row->$fname;
                    // }
//
                    $tmp['class_bg'] = "";
                    $tmp['refID'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refID'] : "";
                    $tmp['refNum'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refNum'] : "";
                    $tmp['refValue'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['nett'] : "";
                    //                    if (isset($ppnDisabledConfig['enabled']) && $ppnDisabledConfig['enabled'] == true) {
                    //                        if (($row->ppn_status == 1)) {
                    //                            $ppnDisabled[] = $row->transaksi_id;
                    //                            $tmp['notes'] = isset($ppnDisabledConfig['notes']) ? "<span style='color:red;font-size:10px;'>" . $ppnDisabledConfig['notes'] . "</span>" : "-";
                    //                        }
                    //                    }
                    switch ($jenis) {
                        case "1111"://realisasi ppn masukan dari pembelian fg
                        case "111"://realisasi ppn masukan dari pembelian fg
                        case "112"://realisasi ppn masukan dari pembelian supplies
                        case "113"://realisasi ppn masukan dari pembelian jasa
                        case "489"://realisasi ppn masukan dari pembayaran
                        case "487"://realisasi ppn masukan dari pembayaran supplies
                        case "483"://realisasi ppn masukan dari pembayaran service project
                        case "462"://realisasi ppn masukan dari pembayaran service
                        case "1424"://realisasi ppn masukan dari ???
                        case "464a"://realisasi ppn masukan dari uang muka
                        case "3113"://realisasi ppn masukan dari pembelian jasa project
                        case "4821"://realisasippn masukan dari pembelian aset
                        case "771"://realisasi ppn masukan dari refill pettycash
                            // arrPrint($row);
                            $arrayRows = (array)$row;
                            $arrayRows["sisa"] = (int)$row->sisa;
                            $arrayRows["tagihan"] = (int)$row->tagihan;
                            $itemsSrc[] = $arrayRows + $tmp;
                            break;
                        case "967":
                            $itemsSrc2[] = (array)$row + $tmp;
                        case"682":
                            $itemsSrc1[] = (array)$row + $tmp;
                            // arrPrint($itemsSrc1);
                            // matiHEre();
                            break;
                        case "4821a":
//                            arrPrint($row);
//                            matiHere();
                            $itemsSrc4[] = (array)$row + $tmp;
                            break;
                        //------keatas base vendor
                        case "749":
                            if ($label == "ppn dibayar bendahara negara") {
                                $itemsTarget1[] = (array)$row + $tmp;
                            }
                            break;
                        case "982":
                            $itemsTarget2[] = (array)$row + $tmp;
                            break;
                    }
                    $trIDs[] = $row->transaksi_id;
                }

                // foreach ($defaultItemSrc as $fName => $fLabel) {
                //     foreach ($tmpSrc as $rowsTmp) {
                //         if (isset($rowsTmp->$fName) && is_numeric($rowsTmp->$fName)) {
                //             if (!isset($sumFooter['itemsSrc'][$fName])) {
                //                 $sumFooter['itemsSrc'][$fName] = 0;
                //             }
                //             $sumFooter['itemsSrc'][$fName] += $rowsTmp->$fName;
                //         }
                //     }
                //
                // }
            }
            //endregion

            //region items
            $jenisSrc = null;
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
                    $retCode = $returnRoutes[$jenisSrc];

                    $trr = new MdlTransaksi();
                    $trr->setFilters(array());
                    $trr->addFilter("param='main'");
                    $trr->addFilter("jenis='$retCode'");
                    $tmpR = $trr->lookupRegistries_joined()->result();
                    //cekHere($this->db->last_query());

                    if (sizeof($tmpR) > 0) {
                        foreach ($tmpR as $row) {
                            $main = blobDecode($row->values);
                            $references[$main['referenceID']] = array(
                                "id" => $row->transaksi_id,
                                "nomer" => $row->nomer,
                                "refID" => $main['referenceID'],
                                "refJenis" => $main['referenceJenis'],
                                "refNum" => $main['referenceNomer'],
                                "harga" => isset($main['harga']) ? $main['harga'] : 0,
                                "nett" => isset($main['nett']) ? $main['nett'] : 0,
                            );
                        }
                    }
                }
            }

            $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();

            //==dapatkan daftar kolom dari srcJenis
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
//            $tr->addFilter("sisa>1000");
            if ($filter_bulan == true) {
                $curent_date = date('Y-m-d');
                $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
//                $tr->addFilter("extern_date2>='$threeMonthsAgo'");
                $tr->addFilter("month(extern_date2)='$bulan_keluaran_ex_2'");
                $tr->addFilter("year(extern_date2)='$bulan_keluaran_ex_1'");

            }
            $tr->addFilter("setor_id='0'");
            $this->db->order_by("transaksi_payment_source.extern_date2", "asc");
//            $this->db->limit(20);
            $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($targetJenis)->result();
            cekHere($this->db->last_query());

            $externName = "";
            $items = array();
            $tempDueDate = array();
            $ppnDisabled = array();
            $lockerDisabled = array();
            $trIDs = array();
            if (sizeof($tmpSrc) > 0) {
                foreach ($tmpSrc as $rows) {
                    $arrRows = (array)$rows;
                    $arrRows["tagihan"] = (int)$rows->tagihan;
                    $arrRows["sisa"] = (int)$rows->sisa;

                    $tmp = array();
                    $jenis = $rows->jenis;
                    $transaksi_id = $rows->id;
                    $tmp['class_bg'] = "";
                    $tmp['refID'] = array_key_exists($rows->transaksi_id, $references) ? $references[$rows->transaksi_id]['refID'] : "";
                    $tmp['refNum'] = array_key_exists($rows->transaksi_id, $references) ? $references[$rows->transaksi_id]['refNum'] : "";
                    $tmp['refValue'] = array_key_exists($rows->transaksi_id, $references) ? $references[$rows->transaksi_id]['nett'] : "";
                    $items[] = $arrRows + $tmp;
                    $externName = $rows->extern_nama;
                    $trIDs[] = $rows->transaksi_id;
                    foreach ($defaultItemTrg as $fName => $fLabel) {
                        if (isset($row->$fName) && is_numeric($rows->$fName)) {
                            if (!isset($sumFooter['items'][$fName])) {
                                $sumFooter['items'][$fName] = 0;
                            }
                            $sumFooter['items'][$fName] += $rows->$fName;
                        }
                    }
                }

                foreach ($defaultItemTrg as $fName => $fLabel) {
                    foreach ($tmpSrc as $rowsTmp2) {
                        if (isset($rowsTmp2->$fName) && is_numeric($rowsTmp2->$fName)) {
                            if (!isset($sumFooter['items'][$fName])) {
                                $sumFooter['items'][$fName] = 0;
                            }
                            $sumFooter['items'][$fName] += $rowsTmp2->$fName;
                        }
                    }

                }
            }

            $arrayOnprogress = array(
                "itemsSrc" => $itemsSrc,
                "itemsSrc1" => $itemsSrc1,
                "itemsSrc2" => $itemsSrc2,
                "itemsSrc4" => $itemsSrc4,
                "items" => $items,
                "itemsTarget1" => $itemsTarget1,
                "itemsTarget2" => $itemsTarget2,
            );
            unset($progressFields);
            $progressFields = array(
                "itemsSrc" => $defaultItemSrc,
                "itemsSrc4" => $itemsSrc4Label,
                "items" => $defaultItemTrg,
                "itemsTarget1" => $defaultItemsTarget1,
                "itemsSrc1" => $itemsSrc1Label,
            );
            //endregion

            $extData = "continue " . $this->configUi[$this->jenisTr]['steps'][1]['label'];
            $extact = "top.$('#result').load('" . MODUL_PATH . "Transaksi/validate/" . $targetJenis . "?');";
            $selectProcessor = array(
                "items" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor"],
                "itemsSrc" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor2"],
                "itemsTarget1" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor3"],
                "itemsSrc1" => MODUL_PATH . $this->configUi[$this->jenisTr]["selectorProcessor4"],
            );
            $qstrLabelsKeluaran = isset($this->configUi[$this->jenisTr]["qstrLabelsKeluaran"]) ? $this->configUi[$this->jenisTr]["qstrLabelsKeluaran"] : array();
            if ($filter_bulan == true) {
                if (!isset($_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'])) {
                    $_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'] = 0;
                }
//                $_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'] = 0;
//                $_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'] = "";
                if (isset($_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'])) {
                    if ($_SESSION["_TR_" . $targetJenis]['main']['date_keluaran'] != $bulan_keluaran) {
                        $_SESSION["_TR_" . $targetJenis]['items'] = array();
                        // otomatis running ceklist semua
                        $iData = array();
                        foreach ($items as $ikey => $iSpec) {
                            foreach ($qstrLabelsKeluaran as $qkey => $qval) {
                                $iData[$ikey][$qkey] = isset($iSpec[$qkey]) ? $iSpec[$qkey] : "";
                                $iData[$ikey]["state"] = "true";
                            }
                        }
                        if (count($iData) > 0) {
                            $this->selectMulti($targetJenis, $bulan_keluaran, $iData);
                        }
                        $refItems = isset($_SESSION["_TR_" . $targetJenis]['items']) ? $_SESSION["_TR_" . $targetJenis]['items'] : array();

                    }
                }
            }
            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;
            cekHere("[start: $starttime] [end: $endtime] [selesai: $val]");
//            mati_disini(__LINE__);


            $data = array(
                "mode" => isset($this->configUi[$this->jenisTr]["mode"]) ? $this->configUi[$this->jenisTr]["mode"] : $this->uri->segment(3), // index_multi
                "title" => $this->configUi[$targetJenis]["label"],
                "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
                "arrayOnProgress" => $arrayOnprogress,
                "srcLabel" => $srcLabel,
                "arrayProgressLabels" => $progressFields,
                "jenisTr" => $this->jenisTr,
                "selectProcessor" => $selectProcessor,
                "btnLabel" => $extData,
                "actionTarget" => $extact,
                "refIDs" => $refIDs,
                "defaultItemTrgEditable" => isset($defaultItemTrgEditable) ? $defaultItemTrgEditable : array(),
                "editItemTrg" => MODUL_PATH . "_followupLiveEdit/editEfaktur/" . $this->jenisTr . "/",
                "refItems" => $refItems,
                "refItemSrc" => $refItemSrc,
                "viewCart" => base_url() . "pembayaran/_shoppingCart/viewCart/114?epreketek=yes",

                "bulan_keluaran" => $bulan_keluaran,
                "bulan_keluaran_max" => date("Y-m"),
                "link_kembali" => MODUL_PATH . "Transaksi/selectPaymentExternSrc/" . $this->jenisTr . "/?&md=transaksi",
                "qstrLabelsKeluaran" => $qstrLabelsKeluaran,
                "filterBulan" => $filter_bulan,
                "filterBulanTipe" => $filter_bulan_tipe,
//                "bulanMundur" => backCustomMonths(date("Y-m"), 3),
                "bulanMundur" => backCustomThisMonths(date("Y-m"), 2),
            );
            $this->load->view("transaksi", $data);
        }


    }

    public function selectPaymentSrc()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $elementTimeStart = microtime(true);

        $this->load->model("MdlTransaksi");
        $externID = ($this->uri->segment(5) != NULL) ? $this->uri->segment(5) : NULL;
        $selectedTrID = $this->uri->segment(6);

        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlLockerTransaksi");

        // arrPrintPink($this->uri->segment_array());
        $scriptBottom = "";

        $jenisTr = $targetJenis = $this->jenisTr;
        $cCode = $this->cCode;
        $master_target = $this->uri->segment(4);
        switch ($targetJenis) {
            case "111":
                $master_target = "489";
                break;
            default :
                $master_target = $this->uri->segment(4);
                break;
        }

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


        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        //-----------------------------------------------------


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
        $paymentSrcLocked = isset($this->configUi[$targetJenis]['paymentSrcLocked']) ? $this->configUi[$targetJenis]['paymentSrcLocked'] : array();
        $pembayaranBerurutan = isset($this->configUi[$targetJenis]['pembayaranBerurutan']) ? $this->configUi[$targetJenis]['pembayaranBerurutan'] : false;
        $shopingCartReferenceStepField = isset($this->configUi[$targetJenis]['shopingCartReferenceStepField']) ? $this->configUi[$targetJenis]['shopingCartReferenceStepField'] : array();
        $shopingCartReferenceUangMukaPpn = isset($this->configUi[$targetJenis]['shopingCartReferenceUangMukaPpn']) ? $this->configUi[$targetJenis]['shopingCartReferenceUangMukaPpn'] : array();
        $shopingCartReferenceTitipanPo = isset($this->configUi[$targetJenis]['shopingCartReferenceTitipanPo']) ? $this->configUi[$targetJenis]['shopingCartReferenceTitipanPo'] : array();
        $referencePOStatus = isset($this->configUi[$targetJenis]['referencePOStatus']['enabled']) ? $this->configUi[$targetJenis]['referencePOStatus']['enabled'] : false;
        $referencePOStatusData = isset($this->configUi[$targetJenis]['referencePOStatus']) ? $this->configUi[$targetJenis]['referencePOStatus'] : array();
        $labelKeterangan = isset($this->configUi[$targetJenis]['labelKeterangan']) ? $this->configUi[$targetJenis]['labelKeterangan'] : array();
//arrPrint($labelKeterangan);
        $jenisSrc = null;
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
                $retCode = $returnRoutes[$jenisSrc];

                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                //                $trr->addFilter("param='main'");
                //                $trr->addFilter("jenis='$retCode'");
                //                $tmpR = $trr->lookupRegistries_joined()->result();
                //
                $this->db->group_start();
                $this->db->where(array("suppliers_id" => $externID));
                $this->db->or_where(array("customers_id" => $externID));
                $this->db->group_end();

                $trr->addFilter("jenis='$retCode'");
                $tmpTr = $trr->lookupAll()->result();
                // arrPrint($tmpTr);
                // matiHere(__LINE__." ".__FILE__);
                $mainReg = array();
                $mainRegResult = array();
                if (sizeof($tmpTr) > 0) {
                    foreach ($tmpTr as $trSpec) {
                        // $reg_index = isset($trSpec->indexing_registry) ? blobDecode($trSpec->indexing_registry) : array();
                        // if (sizeof($reg_index) > 0) {
                        //     $mainReg[$trSpec->id] = isset($reg_index['main']) ? $reg_index['main'] : 0;
                        // }
                        $mainReg[$trSpec->id] = $trSpec->id;
                    }

                    if (sizeof($mainReg) > 0) {
                        $trr = new MdlTransaksi();
                        $trr->setFilters(array());
                        $trr->addFilter("transaksi_id in ('" . implode("','", $mainReg) . "')");
                        $trr->setJointSelectFields("transaksi_id,main");
                        $tmpReg = $trr->lookupDataRegistries()->result();

                        // arrPrint($tmpReg);
                        // matiHEre();
                        foreach ($tmpReg as $rSpec) {
                            $mainRegResult[$rSpec->transaksi_id] = blobDecode($rSpec->main);
                        }
                    }
                }


                //                $tmpR = array();
                if (sizeof($tmpTr) > 0) {
                    foreach ($tmpTr as $row) {
                        //                        $main = blobDecode($row->values);
                        $main = $mainRegResult[$row->id];
                        $references[$main['referenceID']] = array(
                            //                            "id" => $row->transaksi_id,
                            "id" => $row->id,
                            "nomer" => $row->nomer,
                            "refID" => $main['referenceID'],
                            "refJenis" => $main['referenceJenis'],
                            "refNum" => $main['referenceNomer'],
                            "harga" => isset($main['harga']) ? $main['harga'] : 0,
                            "nett" => isset($main['nett']) ? $main['nett'] : 0,
                        );
                    }
                }
            }
        }

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();

        //==dapatkan daftar kolom dari srcJenis
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        if ($externID != NULL) {
            $tr->addFilter("extern_id='$externID'");
        }
        if ($targetJenis == "111") {
            $tr->addFilter("ppn_sisa>1000");
        }
        else {
            $tr->addFilter("sisa>=5");//diturunkan karena ada biay 6 rupiah
        }

        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $this->db->order_by("transaksi_payment_source.dtime", "asc");
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($master_target)->result();
        cekHitam($this->db->last_query());
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
        $paymentSrcDisabled = array();
        $lockerDisabled = array();
        $transaksiIds_items = array();
        $transaksiIds_uangmukappn = array();
        $transaksiReferensiUangMukaPpn = array();
        $transaksiReferensiTitipanPo = array();
        $trIDs = array();
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {

                $id = $row->id;
                $transaksi_id = $row->transaksi_id;
                $transaksiIds_items[] = $transaksi_id;
                if (count($shopingCartReferenceStepField) > 0) {
                    $shopingCartReferenceStep = blobDecode($row->ids_his);
                    foreach ($shopingCartReferenceStepField as $stepref => $stepreference_field) {
                        if (isset($shopingCartReferenceStep[$stepref])) {
                            $transaksiIds_uangmukappn[] = $shopingCartReferenceStep[$stepref]["trID"];
                            if ($referencePOStatus == true) {
                                $transaksiIds_uangmukappn_data[$shopingCartReferenceStep[$stepref]["trID"]] = array(
                                    "dtime" => $shopingCartReferenceStep[$stepref]["dtime"],
                                    "fulldate" => $shopingCartReferenceStep[$stepref]["fulldate"],
                                    "olehID" => $shopingCartReferenceStep[$stepref]["olehID"],
                                    "olehName" => $shopingCartReferenceStep[$stepref]["olehName"],
                                    "transaksi_id" => $shopingCartReferenceStep[$stepref]["trID"],
                                    "nomer" => $shopingCartReferenceStep[$stepref]["nomer"],
                                    "transaksi_id_top" => $shopingCartReferenceStep[1]["trID"],
                                    "nomer_top" => $shopingCartReferenceStep[1]["nomer"],
                                );
                            }
                        }
                    }
                }
            }
            //--------------------------
//            arrPrintCyan($shopingCartReferenceUangMukaPpn);
            if (sizeof($shopingCartReferenceUangMukaPpn) > 0) {
                if (isset($shopingCartReferenceUangMukaPpn["enabled"]) && ($shopingCartReferenceUangMukaPpn["enabled"] == true)) {
                    if (sizeof($transaksiIds_uangmukappn) > 0) {
//                        $tr = new MdlTransaksi();
//                        $tr->setFilters(array());
//                        $tr->addFilter("extern2_id in ('" . implode("','", $transaksiIds_uangmukappn) . "')");
                        $this->load->model("Mdls/MdlSupplierUangMukaPpnRek");
                        $tr = new MdlSupplierUangMukaPpnRek();
                        $tr->setFilters(array());
                        $tr->addFilter("extern2_id in ('" . implode("','", $transaksiIds_uangmukappn) . "')");
                        foreach ($shopingCartReferenceUangMukaPpn["filter"] as $ff) {
                            $tr->addFilter($ff);
                        }
//                        $trTmpSrc = $tr->lookUpAllPaymentSrc()->result();
                        $trTmpSrc = $tr->lookupAll()->result();
                        showLast_query("kuning");
                        if (sizeof($trTmpSrc) > 0) {
                            foreach ($trTmpSrc as $trTmpSrcSpec) {
                                $dpp_pengganti = ($trTmpSrcSpec->debet * (11 / 12));
                                $ppn = ($trTmpSrcSpec->debet * ($this->session->login["ppnFactor"] / 100));
                                $uangmuka_nppn = $trTmpSrcSpec->debet + $ppn;
                                $transaksiReferensiUangMukaPpn[$trTmpSrcSpec->extern2_id] = array(
                                    "referensi_po_id" => $trTmpSrcSpec->extern2_id,
                                    "referensi_po_nomer" => $trTmpSrcSpec->extern2_nama,
//                                    "referensi_po_uangmuka" => $trTmpSrcSpec->sisa,
                                    "referensi_po_uangmuka" => $trTmpSrcSpec->debet,
                                    "referensi_po_dpp_pengganti" => $dpp_pengganti,
                                    "referensi_po_ppn" => $ppn,
                                    "referensi_po_uangmuka_nppn" => $uangmuka_nppn,
                                );
                            }
                        }
                    }
                }
//                showLast_query("biru");
//                heGetTimedQuery($elementTimeStart, __LINE__);
//                arrPrintHitam($transaksiReferensiUangMukaPpn);
            }
            //--------------------------
            if (sizeof($shopingCartReferenceTitipanPo) > 0) {
                if (isset($shopingCartReferenceTitipanPo["enabled"]) && ($shopingCartReferenceTitipanPo["enabled"] == true)) {
                    if (sizeof($transaksiIds_uangmukappn) > 0) {
//                        $tr = new MdlTransaksi();
                        $this->load->model("Mdls/MdlSupplierUangMukaRek");
                        $tr = new MdlSupplierUangMukaRek();
                        $tr->setFilters(array());
                        $tr->addFilter("extern2_id in ('" . implode("','", $transaksiIds_uangmukappn) . "')");
                        foreach ($shopingCartReferenceTitipanPo["filter"] as $ff) {
                            $tr->addFilter($ff);
                        }
//                        $trTmpSrc = $tr->lookupAllUangMukaSrc()->result();
                        $trTmpSrc = $tr->lookupAll()->result();
                        cekHere(count($trTmpSrc));
                        if (sizeof($trTmpSrc) > 0) {
                            foreach ($trTmpSrc as $trTmpSrcSpec) {
                                $transaksiReferensiTitipanPo[$trTmpSrcSpec->extern2_id] = array(
                                    "referensi_titipan_po_id" => $trTmpSrcSpec->extern2_id,
                                    "referensi_titipan_po_nomer" => $trTmpSrcSpec->extern2_nama,
                                    "referensi_titipan_po_uangmuka" => $trTmpSrcSpec->debet,
                                );
                            }
                        }
                    }

                }
//                showLast_query("biru");
//                heGetTimedQuery($elementTimeStart, __LINE__);
            }
            //--------------------------
            $lt = New MdlLockerTransaksi();
            $lt->addFilter("transaksi_id in ('" . implode("','", $transaksiIds_items) . "')");
            $lt->addFilter("state='hold'");
            $lt->addFilter("jumlah='1'");
            $ltTmp = $lt->lookupAll()->result();

            if ($referencePOStatus == true) {
                if (sizeof($transaksiIds_uangmukappn) > 0) {
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $trTmpDetail = $tr->lookupDetailTransaksi($transaksiIds_uangmukappn);
                    showLast_query("biru");
                    if (sizeof($trTmpDetail) > 0) {
                        foreach ($trTmpDetail as $tblid => $trTmpDetailSepc) {
                            $sub_total = 0;
                            foreach ($trTmpDetailSepc as $ii => $subSpec) {
                                $poid = $subSpec->transaksi_id;
                                $harga = $subSpec->produk_ord_hrg;
                                $qty = $subSpec->valid_qty;
                                $sub_harga = $harga * $qty;
                                $sub_total += $sub_harga;
                                $itemAdditional[$poid] = array(
                                    "nomer" => NULL,
                                    "nomer_top" => $transaksiIds_uangmukappn_data[$poid]["nomer_top"],
                                    "transaksi_id" => 0,
                                    "transaksi_id_po" => $poid,
                                    "transaksi_no" => $transaksiIds_uangmukappn_data[$poid]["nomer"],
                                    "refNum" => 0,
                                    "fulldate_po" => $transaksiIds_uangmukappn_data[$poid]["fulldate"],
                                    "tagihan" => "$sub_total",
                                    "refValue" => 0,
                                    "terbayar" => 0,
                                    "diskon" => 0,
                                    "sisa" => $sub_total,
                                    "notes" => "<span class=meta>PO belum GRN, silahkan hubungi pihak Pembelian.</span>",
//                                "notes" => "PO belum GRN, silahkan hubungi pihak Pembelian.",
                                    "referensi_po_uangmuka" => 0,
                                    "referensi_titipan_po_uangmuka" => 0,
                                    "background_color" => "yellow",
                                );
                            }
                        }
                    }
                }
            }

            $allowedActionByHold = array();
            if (sizeof($ltTmp) > 0) {
                foreach ($ltTmp as $ltSpec)
                    if ($ltSpec->oleh_id == my_id()) {
                        // cekUngu("transaksi HOLD by yang login, bisa dilanjutkan");
                    }
                    else {
                        $allowedActionByHold[$ltSpec->transaksi_id] = array(
                            "transaksi_id" => $ltSpec->transaksi_id,
                            "oleh_id" => $ltSpec->oleh_id,
                            "oleh_nama" => $ltSpec->oleh_nama,
                        );
                        // cekUngu("transaksi HOLD by orang lain, TIDAK BISA dilanjutkan");
                    }
            }
            foreach ($tmpSrc as $row) {

                if ($row->target_jenis == "1483") {
                    if ($row->realisasi_netto == 0) {
                        $row->realisasi_netto = $row->sisa;
                    }
                }
                $tmp = array();
                $jenisMaster = $row->jenis_master;
                $jenis = $row->jenis;
                $transaksi_id = $row->id;
                $counters = ($row->counters != NULL) ? blobDecode($row->counters) : array();
                $ids_his = ($row->ids_his != NULL) ? blobDecode($row->ids_his) : array();
                if (sizeof($counters) > 0) {
                    $globalCounter = $counters["stepCode"][$jenisMaster];
                    $tmp['globalCounter'] = digit_5($globalCounter);
                }
                if (count($shopingCartReferenceStepField) > 0) {
                    $shopingCartReferenceStep = blobDecode($row->ids_his);
                    foreach ($shopingCartReferenceStepField as $stepref => $stepreference_field) {
                        if (isset($shopingCartReferenceStep[$stepref])) {
                            $tmp['transaksi_no'] = $shopingCartReferenceStep[$stepref][$stepreference_field];
                            $tmp['transaksi_id_po'] = $shopingCartReferenceStep[$stepref]["trID"];
                            $tmp['transaksi_ref_po_nomer'] = $shopingCartReferenceStep[$stepref][$stepreference_field];
                            $tmp['transaksi_ref_id_po'] = $shopingCartReferenceStep[$stepref]["trID"];
                            if (isset($transaksiReferensiUangMukaPpn[$tmp['transaksi_id_po']])) {
                                foreach ($transaksiReferensiUangMukaPpn[$tmp['transaksi_id_po']] as $kk => $vv) {
                                    $tmp[$kk] = $vv;
                                }
                            }
                            if (isset($transaksiReferensiTitipanPo[$tmp['transaksi_id_po']])) {
                                foreach ($transaksiReferensiTitipanPo[$tmp['transaksi_id_po']] as $kk => $vv) {
                                    $tmp[$kk] = $vv;
                                }
                            }
                        }
                        else {
                            $tmp['transaksi_no'] = "";
                        }
                    }

                }
                if ($readerDueDate) {
                    $tempDueDate = blobDecode($row->ids_prev);
                    $dueTime = array();
                    if (sizeof($tempDueDate) > 0) {
                        $time_due = array();
                        foreach ($tempDueDate as $k => $spdID) {
                            if (isset($mainDueDate[$spdID])) {
                                $time_due[] = strtotime($mainDueDate[$spdID]);
                                $dueTime[strtotime($mainDueDate[$spdID])] = $mainDueDate[$spdID];
                            }
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
                //------
                if (sizeof($ids_his) > 0) {
//                    arrPrintHitam($ids_his);
                    foreach ($ids_his as $his_key => $his_val) {
                        $tmp["oleh_id_" . $his_key] = $his_val["olehID"];
                        $tmp["oleh_nama_" . $his_key] = $his_val["olehName"];
                        $tmp["nomer_" . $his_key] = $his_val["nomer"];
                        $tmp["nomer2_" . $his_key] = $his_val["nomer2"];
                        $tmp["nomer_alias_" . $his_key] = $his_val["nomer2"];
                        $tmp['referenceID__' . $step_his] = $data_his["trID"];
                        $tmp['referenceNumber__' . $step_his] = $data_his["nomer"];
                        $tmp['referenceNomer__' . $step_his] = $data_his["nomer"];
                        $tmp['referenceDtime__' . $step_his] = $data_his["dtime"];
                        $tmp['referenceFulldate__' . $step_his] = $data_his["fulldate"];
                    }
                }
                //------
                $tmp['refID'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refID'] : "";
                $tmp['refNum'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['refNum'] : "";
                $tmp['refValue'] = array_key_exists($row->transaksi_id, $references) ? $references[$row->transaksi_id]['nett'] : "";

                if (strlen($prePrint) > 10) {
                    $linkPrint = MODUL_PATH . "$prePrint/$jenis/$transaksi_id";
                    $tmp['prePrint'] = "<button type='button' class='btn btn-link' onclick=\"window.open('$linkPrint')\"><i class='fa fa-print'></i></button>";
                }
                if (isset($ppnDisabledConfig['enabled']) && $ppnDisabledConfig['enabled'] == true) {
                    if (($row->ppn_status == 1)) {
                        $ppnDisabled[] = $row->transaksi_id;
                        $tmp['notes'] = isset($ppnDisabledConfig['notes']) ? "<span style='color:red;font-size:10px;'>" . $ppnDisabledConfig['notes'] . "</span>" : "-";
                    }
                }
                if (isset($paymentSrcLocked['enabled']) && ($paymentSrcLocked['enabled'] == true)) {
                    if ($row->payment_locked == 1) {
                        $paymentSrcDisabled[] = $row->transaksi_id;
                        $tmp['notes'] = isset($paymentSrcLocked['notes']) ? "<span style='color:red;font-size:10px;'>" . $paymentSrcLocked['notes'] . "</span>" : "-";
                    }
                }

                $modul = isset($this->configMasterUi[$jenisMaster]['modul']) ? $this->configMasterUi[$jenisMaster]['modul'] : NULL;
                $jenisReferenceAlias = isset($this->configMasterUi[$jenisMaster]['label']) ? $this->configMasterUi[$jenisMaster]['label'] : "-";
                $tmp['jenis_master'] = $jenisMaster;
                $tmp['modul_path'] = base_url() . "$modul/";
                if ($row->ppn == 0) {
                    $ppn_transaksi = "0";
                }
                else {
                    $ppn_transaksi = "1";
                }
                $tmp['ppnTransaksi'] = $ppn_transaksi;
                $tmp['jenisReferenceAlias'] = $jenisReferenceAlias;
//cekMerah("[$jenisMaster]");
                if (isset($labelKeterangan[$jenisMaster])) {
                    $tmp["label_keterangan"] = $labelKeterangan[$jenisMaster];
                    if (($row->keterangan != NULL) && ($targetJenis == "1488")) {
                        $tmp["label_keterangan"] .= "&nbsp;" . $row->keterangan;
                    }
                }
                if (isset($tmp["label_keterangan"])) {
                    if (($row->payment_source_keterangan != NULL)) {
                        $tmp["label_keterangan"] .= "&nbsp;" . $row->payment_source_keterangan;
                    }
                }
                else {
                    if (($row->payment_source_keterangan != NULL)) {
                        $tmp["label_keterangan"] = "&nbsp;" . $row->payment_source_keterangan;
                    }
                }
//                cekHitam($tmp["keterangan"]);

                $items[] = (array)$row + $tmp;
                $externName = $row->extern_nama;

                $trIDs[] = $row->transaksi_id;
            }
        }
        //--------------------
        if ($referencePOStatus == true) {
            $tr = new MdlTransaksi();
            if ($externID > 0) {
                $tr->addFilter("suppliers_id='$externID'");
            }
            $tr->addFilter("jenis in ('" . implode("','", $referencePOStatusData["jenis"]) . "')");
            $tr->addFilter("next_substep_code<>''");
            $tr->addFilter("sub_step_number>0");
            $tr->addFilter("valid_qty>0");
            $tmpHist = $tr->lookupRecentUndoneEntriesNoGroup_joined(replaceSession())->result();
            showLast_query("hijau");
            cekHijau(count($tmpHist));
            if (sizeof($tmpHist) > 0) {
                $sub_total = array();
                foreach ($tmpHist as $row) {
                    $harga = $row->produk_ord_hrg;
                    $qty = $row->valid_qty;
                    $sub_harga = $harga * $qty;
                    if (!isset($sub_total[$row->transaksi_id])) {
                        $sub_total[$row->transaksi_id] = 0;
                    }
                    $sub_total[$row->transaksi_id] += $sub_harga;
                    $modul = isset($this->configMasterUi[$row->jenis_master]['modul']) ? $this->configMasterUi[$row->jenis_master]['modul'] : NULL;
                    $itemAdditional[$row->transaksi_id] = array(
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
                        "extern_id" => $row->suppliers_id,
                        "extern_nama" => $row->suppliers_nama,
                        "extern2_id" => $row->transaksi_id,
                        "extern2_nama" => $row->nomer,
                    );
                }
            }
//            arrPrint($itemAdditional);
            if (isset($itemAdditional) && (sizeof($itemAdditional) > 0)) {
                $arrKeysPo = array_keys($itemAdditional);
                if (sizeof($shopingCartReferenceUangMukaPpn) > 0) {
                    if (isset($shopingCartReferenceUangMukaPpn["enabled"]) && ($shopingCartReferenceUangMukaPpn["enabled"] == true)) {
                        if (sizeof($arrKeysPo) > 0) {
//                            $tr = new MdlTransaksi();
//                            $tr->setFilters(array());
//                            $tr->addFilter("extern2_id in ('" . implode("','", $arrKeysPo) . "')");
//                            foreach ($shopingCartReferenceUangMukaPpn["filter"] as $ff) {
//                                $tr->addFilter($ff);
//                            }
//                            $trTmpSrc = $tr->lookUpAllPaymentSrc()->result();
                            $this->load->model("Mdls/MdlSupplierUangMukaPpnRek");
                            $tr = new MdlSupplierUangMukaPpnRek();
                            $tr->setFilters(array());
                            $tr->addFilter("extern2_id in ('" . implode("','", $arrKeysPo) . "')");
                            foreach ($shopingCartReferenceUangMukaPpn["filter"] as $ff) {
                                $tr->addFilter($ff);
                            }
                            $trTmpSrc = $tr->lookupAll()->result();
                            if (sizeof($trTmpSrc) > 0) {
                                foreach ($trTmpSrc as $trTmpSrcSpec) {
                                    $dpp_pengganti = ($trTmpSrcSpec->debet * (11 / 12));
                                    $ppn = ($trTmpSrcSpec->debet * ($this->session->login["ppnFactor"] / 100));
                                    $uangmuka_nppn = $trTmpSrcSpec->debet + $ppn;
                                    $transaksiReferensiUangMukaPpn[$trTmpSrcSpec->extern2_id] = array(
                                        "referensi_po_id" => $trTmpSrcSpec->extern2_id,
                                        "referensi_po_nomer" => $trTmpSrcSpec->extern2_nama,
//                                        "referensi_po_uangmuka" => $trTmpSrcSpec->sisa,
                                        "referensi_po_uangmuka" => $trTmpSrcSpec->debet,
                                        "referensi_po_dpp_pengganti" => $dpp_pengganti,
                                        "referensi_po_ppn" => $ppn,
                                        "referensi_po_uangmuka_nppn" => $uangmuka_nppn,
                                    );
                                }
                            }
                        }
                    }
                }
                if (sizeof($shopingCartReferenceTitipanPo) > 0) {
                    if (isset($shopingCartReferenceTitipanPo["enabled"]) && ($shopingCartReferenceTitipanPo["enabled"] == true)) {
                        if (sizeof($arrKeysPo) > 0) {
                            $tr = new MdlTransaksi();
                            $this->load->model("Mdls/MdlSupplierUangMukaRek");
                            $tr = new MdlSupplierUangMukaRek();
//                            $tr->setFilters(array());
                            $tr->addFilter("extern2_id in ('" . implode("','", $arrKeysPo) . "')");
                            foreach ($shopingCartReferenceTitipanPo["filter"] as $ff) {
                                $tr->addFilter($ff);
                            }
//                            $trTmpSrc = $tr->lookupAllUangMukaSrc()->result();
                            $trTmpSrc = $tr->lookupAll()->result();
                            if (sizeof($trTmpSrc) > 0) {
                                foreach ($trTmpSrc as $trTmpSrcSpec) {
                                    $transaksiReferensiTitipanPo[$trTmpSrcSpec->extern2_id] = array(
                                        "referensi_titipan_po_id" => $trTmpSrcSpec->extern2_id,
                                        "referensi_titipan_po_nomer" => $trTmpSrcSpec->extern2_nama,
                                        "referensi_titipan_po_uangmuka" => $trTmpSrcSpec->debet,
                                    );
                                }
                            }
                        }
                    }
                }

                foreach ($itemAdditional as $ii => $iiSpec) {
                    if (isset($transaksiReferensiUangMukaPpn[$ii])) {
                        foreach ($transaksiReferensiUangMukaPpn[$ii] as $kk => $vv) {
                            $iiSpec[$kk] = $vv;
                        }
                    }
                    if (isset($transaksiReferensiTitipanPo[$ii])) {
                        foreach ($transaksiReferensiTitipanPo[$ii] as $kk => $vv) {
                            $iiSpec[$kk] = $vv;
                        }
                    }

                    $items[] = $iiSpec;
                }
            }
        }
        //--------------------


        $cCode = "_TR_" . $targetJenis;

        if ($targetJenis === "1148") {
            $defaultItemLabels = array(
                "fulldate" => "tgl entry",
                "extern_date2" => "tgl E-Biling",
                "extern_label2" => "E-Billing",
                "oleh_nama" => "pic",//default request dikeluarkan langsung tidak peduli ad vendor /tidak
                "nomer" => "nomer entry",
                "extern_nilai3" => "ppn Keluaran",
                "extern_nilai2" => "(ppn masukan)",
                "extern_nilai4" => "denda",
//                "refNum" => "return ref.",

                "tagihan" => "wajib setor",
//                "refValue" => "returned",
                "terbayar" => "terbayar",
//                "diskon" => "discount",
                "sisa" => "belum disetor",
            );
        }
        else {
            $defaultItemLabels = array(
                "oleh_nama" => "pic",//default request dikeluarkan langsung tidak peduli ad vendor /tidak
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
        }


        $jQueryCustom = isset($this->configUi[$this->jenisTr]['jQueryCustom']) ? $this->configUi[$this->jenisTr]['jQueryCustom'] : "";
        $checkerSaldoHutangUsaha = isset($this->configUi[$this->jenisTr]['checkerSaldoHutangUsaha']['enabled']) ? $this->configUi[$this->jenisTr]['checkerSaldoHutangUsaha']['enabled'] : false;

        if (sizeof($jQueryCustom) > 0) {
            $scriptBottom .= $jQueryCustom;
        }
        //----------------------------------
        $allowedSaldo = isset($this->configUi[$targetJenis]['allowedSaldo']) ? $this->configUi[$targetJenis]['allowedSaldo'] : array();
        $buttonMutasi = "";
        if (count($allowedSaldo) > 0) {
            if (isset($allowedSaldo["enabled"]) && ($allowedSaldo["enabled"] == true)) {
                $elementMutasiConfig = $this->config->item("accountElementMutasi") != NULL ? $this->config->item("accountElementMutasi") : array();
                if ($this->session->login['cabang_id'] < 0) {
                    $elementMutasiTmp = $elementMutasiConfig["center"];
//                    arrPrint($elementMutasiTmp);
                    $subjectID = $externID;
                    $subjectNama = $externName;
                    foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                        $allowed = isset($el_spec["allowed"]) ? $el_spec["allowed"] : array();
                        if (in_array($targetJenis, $allowed)) {
                            $elementTimeStart = microtime(true);
                            $blob_ext = $el_spec["label"] . " $subjectNama ";
                            $blob_ext = blobEncode($blob_ext);
                            $sub_rek = isset($el_spec["sub_rekening"]) ? $el_spec["sub_rekening"] : 0;
                            $rek = $el_spec["rekening"];
                            $comName = $el_spec["comName"];
                            $source = $el_spec["source"];
                            $label = $el_spec["label"];
                            $detail = isset($el_spec["detail"]) ? $el_spec["detail"] : array();
                            $this->load->model("Coms/$comName");
                            $cr = New $comName();

                            $this->db->select($source);
                            $cr->addFilter("rekening='$rek'");
                            if (isset($el_spec["sub_rekening"])) {
                                $cr->addFilter("extern2_id='$sub_rek'");
                            }

                            $cr->addFilter("extern_id='$subjectID'");
                            $cr->addFilter("periode='forever'");
                            $crTmp = $cr->lookupAll()->result();

                            $nilai_saldo = 0;
                            if (sizeof($crTmp) > 0) {
                                $nilai_saldo = $crTmp[0]->$source;
                            }
                            $link_mutasi = base_url() . $el_spec['link'] . "$subjectID/$sub_rek?o=" . $this->session->login['cabang_id'] . "&main_ext2_id=$subjectID&blob_ext=$blob_ext";
                            $nilai_saldo_f = number_format($nilai_saldo, "0", ".", ",");

                            $modalDialog = modalDialogBtn('&nbsp;', $link_mutasi, $auto_close = 0, 'saldo');

                            $buttonMutasi .= "<div class='btn-group btn-group-sm' role='group'>";
                            $buttonMutasi .= "<button href='javascript:void(0)' class='btn btn-info' 
                                onclick=\"$modalDialog\" >
                                <span class=''>$label: $nilai_saldo_f</span></button>";
                            //-----
                            if (sizeof($detail) > 0) {
//                                arrPrintHitam($detail);
                                $dcomName = $detail["comName"];
                                $dlink = $detail["link"];
                                $djudul = $el_spec["detail_judul"] . " ($subjectNama)";
                                $djudul_blob = blobEncode($djudul);
                                $dlink_mutasi = base_url() . $dlink . "$subjectID/$sub_rek?o=" . $this->session->login['cabang_id'] . "&ext2_id=$subjectID&main_ext2_id=$subjectID&judul=$djudul_blob&elnama=$el_nama";
                                $dModalDialog = modalDialogBtn('&nbsp;', $dlink_mutasi, $auto_close = 0, 'saldo');
                                $buttonMutasi .= "<button href='javascript:void(0)' class='btn btn-warning' 
                                onclick=\"$dModalDialog\" >
                                <span class='glyphicon glyphicon-th-list'></span></button>";
                            }
                            //-----
                            $buttonMutasi .= "</div>&nbsp;&nbsp;";
                        }
                    }
                }
            }
        }
        //----------------------------------

        $_SESSION[$cCode]['main']['pihakID'] = $externID;
        $defSisa = isset($_SESSION[$cCode]['main']['sisa']) ? $_SESSION[$cCode]['main']['sisa'] : 0;
        $itemTransaksiIDs = isset($_SESSION[$cCode]['items']) ? array_keys($_SESSION[$cCode]['items']) : array();
        $itemTransaksiIDs_blob = blobEncode($itemTransaksiIDs);
        if ($externID > 0) {
//            cekHere("ATAS");
            $itemLabels = isset($this->configUi[$targetJenis]["shoppingCartReferenceFields"]) ? $this->configUi[$targetJenis]["shoppingCartReferenceFields"] : $defaultItemLabels;
            $subtitle = $externName;
        }
        else {
//            cekHere("BAWAH");
            $itemLabels = isset($this->configUi[$targetJenis]["shoppingCartReferenceFieldsAll"]) ? $this->configUi[$targetJenis]["shoppingCartReferenceFieldsAll"] : $defaultItemLabels;
            $subtitle = "";
        }
//        arrPrint($itemLabels);
//        arrPrint($items);
//        cekHitam($this->uri->segment(3));
        $qstrLabels = array(
            "tabel_id" => "tabel_id",
            "transaksi_id" => "trID",
            "nomer" => "nomer",
            "extern_id" => "xID",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "diskon" => "diskon",
            "extern_nama" => "xID",
            "tagihan_valas" => "tagihan_valas",
            "terbayar_valas" => "terbayar_valas",
            "sisa_valas" => "sisa_valas",
            "diskon_valas" => "diskon_valas",
            "valas_id" => "valas_id",
            "valas_nama" => "valas_nama",
            "valas_nilai" => "valas_nilai",
            "id_master" => "id_master",
            "extern_label2" => "pihakMainName",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "extern_nilai4" => "extern_nilai4",
            "extern_nilai5" => "extern_nilai5",
            "pph_23" => "pph_23",
            "dpp_ppn" => "dpp_ppn",
            "ppn_sisa" => "ppn_payment",
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            "extern_jenis" => "extern_jenis",
            "jenis_master" => "jenis_master",
            //                        "id_master" => "id_master",
            "target_jenis" => "jenis_source",
            "ppnTransaksi" => "ppnTransaksi",
            //---------------------------------
            "referensi_po_id" => "referensi_po_id",
            "referensi_po_nomer" => "referensi_po_nomer",
            "extern3_id" => "extern3_id",
            "extern3_nama" => "extern3_nama",
            "extern4_id" => "extern4_id",
            "extern4_nama" => "extern4_nama",
            "transaksi_ref_id_po" => "transaksi_ref_id_po",
            "transaksi_ref_po_nomer" => "transaksi_ref_po_nomer",
            "jenis" => "jenis",
            "extern5_id" => "extern5_id",
            "extern5_nama" => "extern5_nama",
            /**
             * untuk handling jika perlu adjusment sumber payment source darri biaya apa
             * contoh hutang pph21, dari biaya komisi, tau dari biaya usaha yang akan diadjust otomatis
             */
            "realisasi_kurang" => "realisasi_kurang",
            "realisasi_netto" => "realisasi_netto",
            "realisasi_netto" => "realisasi_netto",
            "biaya_rekening" => "biaya_rekening",
            "biaya_rekening_label" => "biaya_rekening_label",
            "biaya_rekening_id" => "biaya_rekening_id",
            "biaya_rekening_id_label" => "biaya_rekening_id_label",
            "biaya_rekening2_id" => "biaya_rekening2_id",
            "biaya_rekening2_id_label" => "biaya_rekening2_id_label",
            "cabang2_id" => "cabang2_id",
            "cabang2_nama" => "cabang2_nama",
            //---------------------------------
            "referenceID__1" => "referenceID__1",
            "referenceNomer__1" => "referenceNomer__1",
            "referenceID__2" => "referenceID__2",
            "referenceNomer__2" => "referenceNomer__2",

        );

        $label_keterangan = isset($this->configUi[$targetJenis]['label_keterangan']) ? $this->configUi[$targetJenis]['label_keterangan'] : NULL;

        $data = array(
            "mode" => $this->uri->segment(3),
            "title" => $this->configUi[$targetJenis]["label"],
            "subTitle" => $subtitle,
            "jenisTr" => $targetJenis,
            "items" => $items,
            "prePrint" => $prePrint,
            "itemLabels" => $itemLabels,
            "selectProcessor" => $this->configUi[$targetJenis]["selectorProcessor"],
            "paymentSubtitle" => "details for " . $this->configUi[$this->jenisTr]['steps'][1]['label'],
            "btnLabel" => "continue " . $this->configUi[$this->jenisTr]['steps'][1]['label'],
            "ses_outMaster" => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
            "ses_items" => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),

            "actionTarget" => "top.document.getElementById('result').src=('" . MODUL_PATH . "Transaksi/validate/" . $targetJenis . "?rawPrev=$rawPrevURL');",
            "columnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/nilai_entry",
            "bankColumnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/paymentMethod_cash",

            "selectedBankID" => isset($_SESSION[$cCode]['main']['cash_account']) ? $_SESSION[$cCode]['main']['cash_account'] : 0,
            "dueDateReader" => $readerDueDate,
            "tagihanSrc" => $tagihanSrc,
            "scriptBottom" => $scriptBottom,
            "tagihanValue" => isset($_SESSION[$cCode]['main'][$tagihanSrc]) ? $_SESSION[$cCode]['main'][$tagihanSrc] : $defSisa,
            "isPaymentRadioSelect" => isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false,
            "ppnDisabled" => $ppnDisabled,
            "lockerDisabled" => isset($lockerDisabled) ? $lockerDisabled : array(),
            "paymentSrcDisabled" => isset($paymentSrcDisabled) ? $paymentSrcDisabled : array(),
            "clearContentTarget" => MODUL_PATH . get_class($this) . "/clearContent/" . $this->jenisTr,
//            "clearContentTarget" => MODUL_PATH . get_class($this) . "/clearContent/" . $this->jenisTr . "/?itemBlob=$itemTransaksiIDs_blob",
            "pembayaranBerurutan" => isset($pembayaranBerurutan) ? $pembayaranBerurutan : false,
            "buttonMutasi" => isset($buttonMutasi) ? $buttonMutasi : "",
            "checkerSaldoHutangUsaha" => isset($checkerSaldoHutangUsaha) ? $checkerSaldoHutangUsaha : false,
            "shopingCartReferenceProcessor" => isset($this->configUi[$this->jenisTr]["shopingCartReferenceProcessor"]) ? MODUL_PATH . get_class($this) . "/" . $this->configUi[$this->jenisTr]["shopingCartReferenceProcessor"] . "/" . $this->jenisTr : "#",
            "shopingCartReferenceEditable" => isset($this->configUi[$this->jenisTr]["shopingCartReferenceEditable"]) ? $this->configUi[$this->jenisTr]["shopingCartReferenceEditable"] : array(),

            "label_keterangan" => $label_keterangan,
            "qstrLabels" => isset($qstrLabels) ? $qstrLabels : array(),
        );

        $sizeInBytes = strlen(serialize($data));

//        echo "Ukuran array dalam byte: " . $sizeInBytes;
//        matiHere(__LINE__);

        if (!isset($_GET['json'])) {
            cekMerah(__LINE__);
            $this->load->view("transaksi", $data);
        }
        else {
            cekHitam(__LINE__);
            return json_encode($data);
        }


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
                                                                $valDetail = formatField($format, $eeReg[$kolom]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $tmpSpec->$k1 = $tmpDetail;
                                                        }
                                                    }
                                                    else {
                                                        if (!isset($tmpSpec->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField("nomer", $eeReg[$v1]);
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
                                $tmp2Datas[$fName] = isset($tmpSpec->$fName) ? formatField($keyFormat, $tmpSpec->$fName) : "-";
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

    public function clearContent()
    {

        $cCode = $this->cCode;
        $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();
        if ((sizeof($selectorNotaLocker) > 0)) {
            if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                if (isset($_SESSION[$cCode]["items"]) && (sizeof($_SESSION[$cCode]["items"]) > 0)) {
                    $trID = array_keys($_SESSION[$cCode]["items"]);
                }

                //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                $this->load->model("Mdls/MdlLockerTransaksi");
                if (is_array($trID) && (sizeof($trID) > 0)) {
                    foreach ($trID as $tridd) {
                        $lt = New MdlLockerTransaksi();
                        $lt->execLocker($_SESSION[$cCode]['main'], 0, $tridd, NULL);
                    }
                }
                else {
                    if ($trID > 0) {
                        $lt = New MdlLockerTransaksi();
                        $lt->execLocker($_SESSION[$cCode]['main'], 0, $trID, NULL);
                    }
                }
                //==================================================================================================
            }
        }

        $_SESSION[$cCode] = null;
        unset($_SESSION[$cCode]);

        switch ($this->jenisTr) {
            case "115":
            case "1483":
                heInitCart($this->jenisTr);
                $jenisTr = $this->jenisTr;
                $link_undoneList = MODUL_PATH . "Transaksi/selectPaymentSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=1";
                echo "<script>";
                echo "top.$('#undoneList').load('$link_undoneList');";
                echo "</script>";
                break;
        }

//        if ($this->jenisTr == "1483") {
//
//            heInitCart($this->jenisTr);
//            $jenisTr = $this->jenisTr;
//            $link_undoneList = MODUL_PATH . "Transaksi/selectPaymentSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=1";
//            echo "<script>";
//            echo "top.$('#undoneList').load('$link_undoneList');";
//            echo "</script>";
//        }

    }

    public function updatePaymentField()
    {

        $key = $_GET["key"];
        $val = $_GET["value"];
        $trid = isset($_GET["id"]) ? $_GET["id"] : $this->input->post('id');
        $sisa = isset($_GET["sisa"]) ? $_GET["sisa"] : $this->input->post('sisa');
        $linkCheckBox = isset($_GET["linkCheckBox"]) ? $_GET["linkCheckBox"] : $this->input->post('linkCheckBox');
        $this->load->model("Mdls/MdlPaymentSource");
        $p = new MdlPaymentSource();

        $realisasi_netto = $this->input->post('realisasi_netto');
        $realisasi_kurang = $this->input->post('realisasi_kurang');

        $update = array(
            "realisasi_kurang" => $this->input->post('realisasi_kurang'),
            "realisasi_netto" => $this->input->post('realisasi_netto'),
        );

        $realisasiCheck = 0;
        foreach ($update as $k => $value) {
            $realisasiCheck += $value * 1;
        }

        if (($realisasiCheck * 1) !== ($sisa * 1)) {
            matiHere(__LINE__);
        }

        $this->db->trans_start();
        $p->updateData(array("transaksi_id" => $trid), $update);
//cekHitam($this->db->last_query());
//matiHere(__LINE__);

        if ($this->db->trans_complete()) {
//            echo "<script>
//                    let url = new URL('$linkCheckBox');
//                    let params = new URLSearchParams(url.search);
//                    params.set('realisasi_kurang', '".blobEncode($this->input->post('realisasi_kurang'))."');
//                    params.set('realisasi_netto', '".blobEncode($this->input->post('realisasi_netto'))."');
//                    url.search = params.toString();
//                    let finalUrl = url.toString();
//
//                    top.console.error('finalUrl: ', finalUrl)
//                </script>";

            $result = array(
                "realisasi_kurang" => blobEncode($this->input->post('realisasi_kurang')),
                "realisasi_netto" => blobEncode($this->input->post('realisasi_netto'))
            );
            echo json_encode($result);
        }
        else {
            die("Gagal saat berusaha  commit transaction!");
        }
    }

    public function saldoUmTotal()
    {
        $jenisTr = $targetJenis = $this->jenisTr;
        $master_target = $this->uri->segment(4);
        $externID = ($this->uri->segment(5) != NULL) ? $this->uri->segment(5) : NULL;
        $selectedTrID = $this->uri->segment(6);

        //==dapatkan daftar kolom dari srcJenis
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        if ($externID != NULL) {
            $tr->addFilter("extern_id='$externID'");
        }
        if ($targetJenis == "111") {
            $tr->addFilter("ppn_sisa>1000");
        }
        else {
            $tr->addFilter("sisa>=100");
        }

        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $this->db->order_by("transaksi_payment_source.dtime", "asc");
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($master_target)->result();

        $externName = "";
        foreach ($tmpSrc as $row) {
            $externName = $row->extern_nama;
        }


//        arrPrint($tmpSrc);
//        matiHere(__LINE__);
        $allowedSaldo = isset($this->configUi[$targetJenis]['allowedSaldo']) ? $this->configUi[$targetJenis]['allowedSaldo'] : array();
        $arrMutasi = [];
        if (count($allowedSaldo) > 0) {
            if (isset($allowedSaldo["enabled"]) && ($allowedSaldo["enabled"] == true)) {
                $elementMutasiConfig = $this->config->item("accountElementMutasi") != NULL ? $this->config->item("accountElementMutasi") : array();
                if ($this->session->login['cabang_id'] < 0) {
                    $elementMutasiTmp = $elementMutasiConfig["center"];
//                    arrPrint($elementMutasiTmp);
                    $subjectID = $externID;
                    $subjectNama = $externName;
                    foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                        $allowed = isset($el_spec["allowed"]) ? $el_spec["allowed"] : array();
                        if (in_array($targetJenis, $allowed)) {
                            $elementTimeStart = microtime(true);
                            $blob_ext = $el_spec["label"] . " $subjectNama ";
//                            arrprint($blob_ext);
                            $blob_ext = blobEncode($blob_ext);
                            $sub_rek = isset($el_spec["sub_rekening"]) ? $el_spec["sub_rekening"] : 0;
                            $rek = $el_spec["rekening"];
                            $comName = $el_spec["comName"];
                            $source = $el_spec["source"];
                            $label = $el_spec["label"];
                            $this->load->model("Coms/$comName");
                            $cr = New $comName();
                            $this->db->select($source);
                            $cr->addFilter("rekening='$rek'");
                            if (isset($el_spec["sub_rekening"])) {
                                $cr->addFilter("extern2_id='$sub_rek'");
                            }
                            $cr->addFilter("extern_id='$subjectID'");
                            $cr->addFilter("periode='forever'");
                            $crTmp = $cr->lookupAll()->result();
                            $nilai_saldo = 0;
                            if (sizeof($crTmp) > 0) {
                                $nilai_saldo = $crTmp[0]->$source;
                            }
                            $link_mutasi = base_url() . $el_spec['link'] . "$subjectID/$sub_rek?o=" . $this->session->login['cabang_id'] . "&main_ext2_id=$subjectID&blob_ext=$blob_ext";
                            $nilai_saldo_f = number_format($nilai_saldo, "0", ".", ",");
                            $modalDialog = modalDialogBtn('&nbsp;', $link_mutasi, $auto_close = 0, 'saldo');

                            $arrMutasi[] = [
                                "nama" => $label,
                                "saldo" => $nilai_saldo,
                                "subjectID" => $subjectID,
                                "subjectNama" => $subjectNama,
                                "sub_rek" => $sub_rek,
                                "rek" => $rek,
                                "el_nama" => $el_nama,
                                "source" => $source,
                                "link" => $modalDialog,
                            ];

                            $buttonMutasi .= "<a href='javascript:void(0)' onclick=\"$modalDialog\" ttarget='_blank'><span class='btn btn-info'>$label: $nilai_saldo_f</span></a>";

                        }
                    }
                }
            }
        }

        echo json_encode($arrMutasi);
    }

    //---------
    public function getPPNKeluaran($targetJenis, $date_keluaran)
    {
        $this->load->model("MdlTransaksi");

        $bulan_keluaran = isset($date_keluaran) ? $date_keluaran : date("Y-m");
        $bulan_keluaran_ex = explode("-", $bulan_keluaran);
        $bulan_keluaran_ex_1 = $bulan_keluaran_ex[0];// tahun
        $bulan_keluaran_ex_2 = $bulan_keluaran_ex[1];// bulan

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . my_id() . "'");
        $tr->addFilter("sisa>1000");
        $tr->addFilter("month(extern_date2)='$bulan_keluaran_ex_2'");
        $tr->addFilter("year(extern_date2)='$bulan_keluaran_ex_1'");
        $this->db->order_by("transaksi_payment_source.extern_date2", "asc");
        $this->db->limit(20);
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($targetJenis)->result();
        cekHere($this->db->last_query());

        return $tmpSrc;

    }

    public function selectMulti($targetJenis, $bulan_keluaran, $iData)
    {


//        foreach ($_GET as $kg => $item) {
//            // get dari checklist, checkbox...
//            if (isset($_GET["state"])) {
//                if (blobDecode($item) == NULL) {
//                    $valss = NULL;//$item;
//                }
//                else {
//                    $valss = blobDecode($item);
//                }
//            }
//            else {
//                // BUKAN get dari checklist, checkbox...
//                $valss = $item;
//            }
////            $_GET[$kg] = ($kg == "state") ? $item : blobDecode($item);
//            $_GET[$kg] = ($kg == "state") ? $item : $valss;
//        }


//        arrPrint($_GET);
//        matiHEre(__LINE__ . "::: under maintenance::: " . $_GET["dateFaktur"] . " ==== " . $_GET["eFaktur"]);
//
//        $is_json = isset($_GET['is_json']) ? $_GET['is_json'] : "";

        $reload_shoppingcart = 1;
        $jenisTr = $this->jenisTr = $targetJenis;
        $cCode = $this->cCode;
        $cCode = "_TR_" . $targetJenis;
        $trID = $transaksi_id_selected = $id = $_GET['transaksi_id'];

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";
        $exchangeValidate = isset($this->configUi[$this->jenisTr]['exchangeValidate']) ? $this->configUi[$this->jenisTr]['exchangeValidate'] : array();
        $referenceGateFields = isset($this->configUi[$this->jenisTr]['referenceGateFields']) ? $this->configUi[$this->jenisTr]['referenceGateFields'] : array();
        $shopingCartPairProdukSrc = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukSrc']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukSrc'] : array();
        $shopingCartPairProdukSrcException = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukSrcException']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukSrcException'] : array();
        $shopingCartPairProdukSrc_gate = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGate']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGate'] : array();
        $shopingCartPairProdukGateDpp = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGateDpp']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGateDpp'] : false;
        $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();
        $shoppingCartReferenceToItem = isset($this->configUi[$this->jenisTr]['shoppingCartReferenceToItem']['enabled']) ? $this->configUi[$this->jenisTr]['shoppingCartReferenceToItem']['enabled'] : false;
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $ppnCekDefaultValue = isset($this->configUi[$this->jenisTr]['ppnCekDefaultValue']) ? $this->configUi[$this->jenisTr]['ppnCekDefaultValue'] : array();
        $shopingCartPairProdukGateCopy = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGateCopy']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGateCopy'] : false;
        $shopingCartForceItemSelected = isset($this->configUi[$this->jenisTr]['shopingCartForceItemSelected']) ? $this->configUi[$this->jenisTr]['shopingCartForceItemSelected'] : array();
        $shopingCartEmbededTaxItem = isset($this->configUi[$this->jenisTr]['shopingCartEmbededTaxItem']) ? $this->configUi[$this->jenisTr]['shopingCartEmbededTaxItem'] : array();
        $shopingCartEmbededGate = isset($shopingCartEmbededTaxItem['gateEmbeded']) ? $shopingCartEmbededTaxItem['gateEmbeded'] : array();
        $gateReplaceMain = isset($this->configUi[$this->jenisTr]['gateReplaceMain']) ? $this->configUi[$this->jenisTr]['gateReplaceMain'] : array();
        $comNameItem = isset($this->configUi[$this->jenisTr]['shopingCartInjectComItem']) ? $this->configUi[$this->jenisTr]['shopingCartInjectComItem'] : array();
        $shopingCartPairProdukGateException = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGateException']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGateException'] : array();

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $additionalDetailByCabang = isset($this->configUi[$this->jenisTr]["additionalDetailByCabang"]) ? $this->configUi[$this->jenisTr]["additionalDetailByCabang"] : array();
        $lembarFaktur = isset($this->configUi[$this->jenisTr]["lembarFaktur"]) ? $this->configUi[$this->jenisTr]["lembarFaktur"] : array();

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

//        if (in_array($this->jenisTr, $this->validateJenisTr)) {
//            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
//                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
//            }
//        }

        //---------------------------------------
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if ((sizeof($selectorNotaLocker) > 0)) {
                if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                    $this->load->model("Mdls/MdlLockerTransaksi");
                    $lt = New MdlLockerTransaksi();
                    $lt->addFilter("transaksi_id='$trID'");
                    $lt->addFilter("state='hold'");
                    $lt->addFilter("jumlah='1'");
                    $ltTmp = $lt->lookupAll()->result();
                    // showLast_query("pink");
                    $allowedActionByHold = NULL;
                    if (sizeof($ltTmp) > 0) {
                        if ($ltTmp[0]->oleh_id == my_id()) {
                            $allowedAction = true;
                            // cekUngu("transaksi HOLD by yang login, bisa dilanjutkan");
                        }
                        else {
                            $allowedAction = false;
                            $allowedActionByHold = $ltTmp[0]->oleh_nama;
                            // cekUngu("transaksi HOLD by orang lain, TIDAK BISA dilanjutkan");
                        }
                    }
                    if ($allowedActionByHold != NULL) {
                        cekMErah($trID);
                        $msg = "Nota yang anda pilih sedang ditindaklanjuti oleh $allowedActionByHold. Silahkan pilih nota lainnya. code: " . __LINE__;
                        mati_disini($msg);
                    }
                }
            }
        }
        //---------------------------------------

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if ($isRadioSelect == true) {
                $detailResetList = array(
                    "items",
                    "items2",
                    "tableIn_detail",
                    "tableIn_detail2",
                    "tableIn_detail_values",
                    "tableIn_detail2_sum",
                    "tableIn_detail_values2_sum",
                    "items2_sum",
                    "main",
                    "main_elements",

                );
                foreach ($detailResetList as $sSName) {
                    $_SESSION[$cCode][$sSName] = null;
                    unset($_SESSION[$cCode][$sSName]);
                }

                $_SESSION[$cCode]['main']['refIDs'] = null;
                unset($_SESSION[$cCode]['main']['refIDs']);

                if (sizeof($_SESSION[$cCode]['items']) > 0) {

                }
                else {
                    $mainValueInjector = isset($this->configUi[$this->jenisTr]['mainValueInjectors']) ? $this->configUi[$this->jenisTr]['mainValueInjectors'] : array();
                    if (sizeof($mainValueInjector) > 0) {
                        foreach ($mainValueInjector as $key => $val) {
                            $_SESSION[$cCode]['main'][$val] = null;
                            unset($_SESSION[$cCode]['main'][$val]);
                        }
                    }
                }

                if (isset($_SESSION[$cCode]['items2'])) {
                    $_SESSION[$cCode]['items2'] = null;
                    unset($_SESSION[$cCode]['items2']);
                }

            }
        }

        if (sizeof($iData) > 0) {
            foreach ($iData as $ikey => $iSpec) {
                $_GET = $iSpec;
                $trID = $transaksi_id_selected = $id = $_GET['transaksi_id'];

                $initMain = array(
                    "pihakID" => $_GET['extern_id'],
                    "pihakName" => $_GET['extern_nama'],
                    "valasDetails" => $_GET['valas_id'],
                    "pihakMainName" => isset($_GET['extern_label2']) && sizeof($_GET['extern_label2']) > 0 ? $_GET['extern_label2'] : "",
                    "other" => isset($_GET['extern_nilai4']) && sizeof($_GET['extern_nilai4']) > 0 ? $_GET['extern_nilai4'] : "",
                    "relativeComName" => sizeof($relativeComNameDetails) > 0 ? $relativeComNameDetails[$_GET['extern_label2']] : "",
                    "pph_23" => isset($_GET['pph_23']) ? $_GET['pph_23'] : 0,
                    "terbayar_pph23" => isset($_GET['terbayar_pph23']) ? $_GET['terbayar_pph23'] : 0,
                    "pphGate" => isset($_GET['extern_jenis']) && sizeof($_GET['extern_jenis']) > 0 ? $_GET['extern_jenis'] : "",
                    "pphGateLabel" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
                    "pairPihakName" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
                    "pairPihakID" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",
                    "pphGateId" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",
//            "ppnTransaksi" => "",
                    "pphGateLabel_3" => isset($_GET['extern3_nama']) && sizeof($_GET['extern3_nama']) > 0 ? $_GET['extern3_nama'] : "",
                    "pairPihakName_3" => isset($_GET['extern3_nama']) && sizeof($_GET['extern3_nama']) > 0 ? $_GET['extern3_nama'] : "",
                    "pairPihakID_3" => isset($_GET['extern3_id']) && sizeof($_GET['extern3_id']) > 0 ? $_GET['extern3_id'] : "",
                    "pphGateId_3" => isset($_GET['extern3_id']) && sizeof($_GET['extern3_id']) > 0 ? $_GET['extern3_id'] : "",
                    "pphGateLabel_4" => isset($_GET['extern4_nama']) && sizeof($_GET['extern4_nama']) > 0 ? $_GET['extern4_nama'] : "",
                    "pairPihakName_4" => isset($_GET['extern4_nama']) && sizeof($_GET['extern4_nama']) > 0 ? $_GET['extern4_nama'] : "",
                    "pairPihakID_4" => isset($_GET['extern4_id']) && sizeof($_GET['extern4_id']) > 0 ? $_GET['extern4_id'] : "",
                    "pphGateId_4" => isset($_GET['extern4_id']) && sizeof($_GET['extern4_id']) > 0 ? $_GET['extern4_id'] : "",
                );
                foreach ($initMain as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $src;
                }
                foreach ($_GET as $get_key => $get_val) {
                    $new_get_key = str_replace("amp;", "", $get_key);
                    $row[$new_get_key] = $get_val;
                    $row["id"] = $id;
                }
                if (isset($_GET["transaksi_ref_id_po"]) && !isset($_SESSION[$cCode]['main']["transaksi_ref_id_po"])) {
                    $_SESSION[$cCode]['main']["transaksi_ref_id_po"] = $_GET["transaksi_ref_id_po"];
                    $_SESSION[$cCode]['main']["transaksi_ref_po_nomer"] = $_GET["transaksi_ref_po_nomer"];
                }
                if (sizeof($exchangeValidate) > 0) {
                    if (isset($exchangeValidate['enabled']) && ($exchangeValidate['enabled'] == true)) {
                        $valasID = $row['valas_id'];
                        if (isset($_SESSION[$cCode]['items'])) {
                            foreach ($_SESSION[$cCode]['items'] as $itemSpec) {
                                if ($valasID != $itemSpec['valas_id']) {
                                    $label_err = $exchangeValidate['label'];
                                    mati_disini($label_err . " || LINE: " . __LINE__);
                                }
                            }
                        }
                    }
                }

                if (count($comNameItem) > 0) {
                    $comNameItemTmp = $this->config->item("accountSubChilds");
                }
                if ($_GET["nomer"] == "pemindahbukuan") {
                    $_SESSION[$cCode]["main"]["transaksi_injected_entry"] = "1";
                    $_SESSION[$cCode]["main"]["tipe_transaksi_sumber"] = "1";//untuk pembeda pindah buku dan reguler
                    $_SESSION[$cCode]["main"]["ppnTransaksi"] = $_GET['ppnTransaksi'];//untuk pembeda pindah buku dan reguler
                    unset($_SESSION[$cCode]['items']);
                    unset($_SESSION[$cCode]['items2']);
                    unset($_SESSION[$cCode]['items4_sum']);
                    $_SESSION[$cCode]["main"]["harga_x"] = 0;
                    $_SESSION[$cCode]["main"]["koreksi"] = 0;
                }
                else {
                    $_SESSION[$cCode]["main"]["transaksi_injected_entry"] = "0";
                    $_SESSION[$cCode]["main"]["ppnTransaksi"] = $_GET['ppnTransaksi'];//untuk pembeda pindah buku dan reguler
                    $_SESSION[$cCode]["main"]["tipe_transaksi_sumber"] = "0";//untuk pembeda pindah buku dan reguler

                    $_SESSION[$cCode]["main"]["harga_x"] = 0;
                    $_SESSION[$cCode]["main"]["koreksi"] = 0;
                }

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
//            cekMerah("TIDAK ADA DI SESI ITEMS");
//            cekBiru($id);
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . get_class($this),
                        "id" => $id,
                        "refID" => $id,
                        "jml" => 1,
                        "harga" => 0,
                        "satuan" => "-",
                        "subtotal" => 0,
                        "jenis_source" => isset($_GET['target_jenis']) ? $_GET['target_jenis'] : 0,
                        "valas_id" => isset($_GET['valas_id']) ? $_GET['valas_id'] : 0,
                        "valas_nama" => isset($_GET['valas_nama']) ? $_GET['valas_nama'] : 0,
                        "valas_nilai" => isset($_GET['valas_nilai']) ? $_GET['valas_nilai'] : 0,
                        "tabel_id" => isset($_GET['tabel_id']) ? $_GET['tabel_id'] : 0,
                        "ppnTransaksi" => isset($_GET['ppnTransaksi']) ? $_GET['ppnTransaksi'] : 0,
                        "referensi_po_id" => isset($_GET['referensi_po_id']) ? $_GET['referensi_po_id'] : 0,
                        "referensi_po_nomer" => isset($_GET['referensi_po_nomer']) ? $_GET['referensi_po_nomer'] : 0,
                        "transaksi_ref_id_po" => isset($_GET['transaksi_ref_id_po']) ? $_GET['transaksi_ref_id_po'] : 0,
                        "transaksi_ref_po_nomer" => isset($_GET['transaksi_ref_po_nomer']) ? $_GET['transaksi_ref_po_nomer'] : 0,
                        "jenis_ref_po" => isset($_GET['jenis']) ? $_GET['jenis'] : 0,
                    );
//            arrPrint($tmp);
                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $row, $row, 0);
                    }

                    foreach ($_GET as $aa => $bb) {
                        $tmp[$aa] = $bb;
                    }

                    if ($subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                        $tmp["subtotal"] = $subtotal;
                    }
                    else {
                        $tmp["subtotal"] = 0;
                    }
                    if (count($comNameItem) > 0) {
                        $tmp[$comNameItem["target_key"]] = $comNameItemTmp[$row[$comNameItem["src_key"]]];

                    }
//            arrprint($tmp);
//            matiHere();
                    if (sizeof($componentAssConfig) > 0) {
                        $this->load->model($componentAssConfig["model"]);
                        $tr = new $componentAssConfig["model"]();
                        $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
                        // cekHitam($this->db->last_query());
                        $mainRegistries = array();
                        $itemsRegistries = array();
                        $items2Registries = array();
                        if (sizeof($tmpReg) > 0) {
                            foreach ($tmpReg as $row) {
                                foreach ($row as $param => $values) {
                                    switch ($param) {
                                        case "main"://
                                            $mainRegistries = unserialize(base64_decode($values));
                                            if (sizeof($referenceGateFields) > 0) {
                                                $target = $referenceGateFields['target'];
                                                $fields = $referenceGateFields['fields'];
                                                foreach ($fields as $skey => $tkey) {
                                                    $_SESSION[$cCode][$target][$tkey] = isset($mainRegistries[$skey]) ? $mainRegistries[$skey] : "";
                                                }
                                            }
                                            break;
                                        case "items"://
                                            $itemsRegistries = unserialize(base64_decode($values));
                                            // arrPrint($itemsRegistries);
                                            // matiHEre();
                                            break;
                                        case "items2"://
                                            $items2Registries = unserialize(base64_decode($values));
                                            break;
                                    }
                                }

                            }

                        }
                        if (sizeof($relativeComNameDetails) > 0) {
                            $key = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
//                    cekHere($key);
//                    cekHere("$relativeComNameDetails[$key] " . $relativeComNameDetails[$key]);
                            $tmp['relativeCom'] = isset($relativeComNameDetails[$key]) ? $relativeComNameDetails[$key] : "";
                            $tmp['rekName'] = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
                        }


                    }
                    //-------
                    if ($shoppingCartReferenceToItem == true) {
                        foreach ($row as $get_key => $get_val) {
                            $tmp[$get_key] = $get_val;
                        }
                    }
                    //-------
                    if (($this->jenisTr == "1483") && ($row["extern2_id"] == 11)) {// freelancer
                        $tmp["extern_id_old"] = $tmp["extern_id"];
                        $tmp["extern_nama_old"] = $tmp["extern_nama"];
                        $tmp["extern_id"] = $tmp["extern5_id"];
                        $tmp["extern_nama"] = $tmp["extern5_nama"];
                    }
                    else {
                        $tmp["extern_id_old"] = $tmp["extern_id"];
                        $tmp["extern_nama_old"] = $tmp["extern_nama"];
                    }
                    //-------
                }
                else {

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                                $reload_shoppingcart = 0;
                            }
                        }
                    }
                }


                $_SESSION[$cCode]['main']['refIDs'][$id] = $id;
                if ($this->jenisTr == "3675") {
                    //untuk pembeda dari adjustment da dari qc
                    $_SESSION[$cCode]['main']['jenis_ref_po'] = $row["jenis_master"];
                }

                switch ($_GET['state']) {
                    case "true":
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $_SESSION[$cCode]['items'][$id] = $tmp;
                            if ($this->jenisTr == "483") {
                                $_SESSION[$cCode]['items'][$id]["extern_nilai2"] = $tmp["tagihan"];
                            }
                        }
                        if (sizeof($componentAssConfig) > 0) {
//                    matiHere(__LINE__);
                            if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                                $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                            }
                            if (!array_key_exists($id, $_SESSION[$cCode]['items4'])) {
//                        $_SESSION[$cCode]['items4'][$id] = $items2Registries;
                                if ($this->jenisTr == "483") {
                                    foreach ($items2Registries as $i => $items2Registries_0) {
//                                arrPrint($items2Registries_0);
                                        foreach ($items2Registries_0 as $ix => $items2Registries_01) {
                                            cekHijau("[MASUK DISINI, TEMBAK PPh]");
                                            if (isset($items2Registries_01["pph"]) && ($items2Registries_01["pph"] == 1)) {// kena pph
                                                $items2Registries_01["pph_persen"] = 0;
                                                $items2Registries_01["dpp_pph_persen"] = 100;
                                                $items2Registries_01["dpp_pph_rupiah"] = $items2Registries_01["harga"];
                                                $items2Registries_01["dppPPh"] = $items2Registries_01["harga"];
                                            }
                                            else {
                                                $items2Registries_01["pph_persen"] = 0;
                                                $items2Registries_01["dpp_pph_persen"] = 0;
                                                $items2Registries_01["dpp_pph_rupiah"] = 0;
                                                $items2Registries_01["dppPPh"] = 0;
                                            }

//                                    arrPrint($items2Registries_01);
//                                    $items2Registries_01
                                            $_SESSION[$cCode]['items4'][$id][$i][$ix] = $items2Registries_01;
                                        }


                                    }
                                }

                            }
                        }

//matiHere(__LINE__."::".$_GET['state']);
                        if ((sizeof($selectorNotaLocker) > 0)) {
                            if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {

                                $this->load->model("Mdls/MdlLockerTransaksi");
                                if ($this->session->login['ghost'] == 0) {
                                    $lt = New MdlLockerTransaksi();
                                    $lt->addFilter("transaksi_id='$transaksi_id_selected'");
                                    $ltTmp = $lt->lookupAll()->result();
                                    if (sizeof($ltTmp) == 0) {
                                        $ltHold = array(
                                            "state" => "hold",
                                            "produk_id" => $transaksi_id_selected,
                                            "transaksi_id" => $transaksi_id_selected,
                                            "cabang_id" => my_cabang_id(),
                                            "oleh_id" => my_id(),
                                            "oleh_nama" => my_name(),
                                            "jenis" => "transaksi",
                                            "jenis_locker" => "transaksi",
                                            "jumlah" => 1,
                                            "gudang_id" => "0",
                                        );
                                        // insert ke tabel locker transaksi
                                        $lt->addData($ltHold);
                                    }
                                    else {
                                        $byUpdateHold = array();
                                        $totalUpdateHold = 0;
                                        $insertHold = true;
                                        foreach ($ltTmp as $ltSpec) {
                                            if (($ltSpec->state == "hold") && ($ltSpec->jumlah == "1")) {
                                                $insertHold = false;
                                                break;
                                            }
                                            elseif (($ltSpec->state == "hold")) {
                                                $totalUpdateHold += isset($ltSpec->jumlah) ? $ltSpec->jumlah : 0;
                                                $byUpdateHold[] = $ltSpec->oleh_id;
                                            }
                                        }
                                        if ($insertHold == true) {
                                            if (($totalUpdateHold == 0) && (in_array($this->session->login['id'], $byUpdateHold))) {
                                                $ltHold = array(
                                                    "jumlah" => 1,
                                                );
                                                $ltWhere = array(
                                                    "state" => "hold",
                                                    "produk_id" => $transaksi_id_selected,
                                                    "transaksi_id" => $transaksi_id_selected,
                                                    "jenis" => "transaksi",
                                                    "jenis_locker" => "transaksi",
                                                    "oleh_id" => my_id(),
                                                    "oleh_nama" => my_name(),
                                                );
                                                $lt->updateData($ltWhere, $ltHold);
                                            }
                                            else {
                                                $ltHold = array(
                                                    "state" => "hold",
                                                    "produk_id" => $transaksi_id_selected,
                                                    "transaksi_id" => $transaksi_id_selected,
                                                    "cabang_id" => my_cabang_id(),
                                                    "oleh_id" => my_id(),
                                                    "oleh_nama" => my_name(),
                                                    "jenis" => "transaksi",
                                                    "jenis_locker" => "transaksi",
                                                    "jumlah" => 1,
                                                    "gudang_id" => "0",
                                                );
                                                $lt->addData($ltHold);
                                            }
                                        }
                                        else {
                                            //                cekPink("sudah ada yang HOLD");
                                        }
                                    }
                                }

                            }
                        }

                        break;
                    case "false":
                        $reload_shoppingcart = 1;
                        if (array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $detailResetList = array(
                                "items",
                                "tableIn_detail",
                                "tableIn_detail2",
                                "tableIn_detail_values",
                                "tableIn_detail2_sum",
                                "tableIn_detail_values2_sum",
                            );
                            foreach ($detailResetList as $sSName) {
                                $_SESSION[$cCode][$sSName][$id] = null;
                                unset($_SESSION[$cCode][$sSName][$id]);
                            }
                        }
                        if (isset($_SESSION[$cCode]['main']['refIDs'][$id])) {
                            $_SESSION[$cCode]['main']['refIDs'][$id] = null;
                            unset($_SESSION[$cCode]['main']['refIDs'][$id]);
                        }

                        if (sizeof($_SESSION[$cCode]['items']) > 0) {

                        }
                        else {

                            $mainValueInjector = isset($this->configUi[$this->jenisTr]['mainValueInjectors']) ? $this->configUi[$this->jenisTr]['mainValueInjectors'] : array();
                            if (sizeof($mainValueInjector) > 0) {
                                foreach ($mainValueInjector as $key => $val) {
                                    $_SESSION[$cCode]['main'][$val] = null;

                                    unset($_SESSION[$cCode]['main'][$val]);

                                }
                            }
                        }
                        if (isset($_SESSION[$cCode]['items2'][$id])) {
                            $_SESSION[$cCode]['items2'][$id] = null;
                            unset($_SESSION[$cCode]['items2'][$id]);
                        }

                        if (isset($_SESSION[$cCode]['items4'][$id])) {
                            $_SESSION[$cCode]['items4'][$id] = null;
                            unset($_SESSION[$cCode]['items4'][$id]);
                        }
                        if (isset($_SESSION[$cCode]['items3_sum'])) {
                            $_SESSION[$cCode]['items3_sum'] = null;
                            unset($_SESSION[$cCode]['items3_sum']);
                        }
                        if (isset($_SESSION[$cCode]['items4_sum'])) {
                            $_SESSION[$cCode]['items4_sum'] = null;
                            unset($_SESSION[$cCode]['items4_sum']);
                        }
                        if (isset($_SESSION[$cCode]['itemSrc'][$id])) {
                            $_SESSION[$cCode]['itemSrc'][$id] = null;
                            unset($_SESSION[$cCode]['itemSrc'][$id]);
                        }
                        if (isset($_SESSION[$cCode]['itemtarget'][$id])) {
                            $_SESSION[$cCode]['itemtarget'][$id] = null;
                            unset($_SESSION[$cCode]['itemtarget'][$id]);
                        }

                        if ((sizeof($selectorNotaLocker) > 0)) {
                            if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                                $this->load->model("Mdls/MdlLockerTransaksi");
                                if ($transaksi_id_selected > 0) {
                                    $lt = New MdlLockerTransaksi();
                                    $lt->execLocker($_SESSION[$cCode]['main'], 0, $transaksi_id_selected, NULL);
                                }
                            }
                        }

                        break;
                }

                if (sizeof($_SESSION[$cCode]['items2']) > 0) {
//            cekBiru("bulding summary item_result...");
                    $_SESSION[$cCode]['items2_sum'] = array();
                    foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                        foreach ($pSpec as $eSpec) {
                            if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = 0;
//
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] = 0;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();


                            }

                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];

                            switch ($this->jenisTr) {
                                case "462":
                                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'])) {
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] = 0;
                                    }
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] += $eSpec['ppn'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn'] = $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] / $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] += $eSpec['subtotal'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] / $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'];
                                    break;
                                case "483":
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = $eSpec['harga'];
//                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['dppPPh'] = $eSpec['harga'];
//                            $_SESSION[$cCode]['items'][$pID]['dppPPh'] += $eSpec['harga'];

                                    // ini exception, bila asal hutang jasa dari kode 6678/16678 maka dihapus items4_sum nya, supaya tidak dikoreksi harga belinya. 26 mei 2025
                                    if (in_array($eSpec["jenisTrMaster"], $shopingCartPairProdukGateException)) {

                                    }
                                    else {
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['dppPPh'] = $eSpec['harga'];
                                    }

                                    break;

                                default:
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] += $eSpec['harga'];
                                    break;
                            }
//                    if ($this->jenisTr == "462") {
//                        if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'])) {
//                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] = 0;
//                        }
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] += $eSpec['ppn'];
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn'] = $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] / $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'];
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] += $eSpec['subtotal'];
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] / $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'];
//                    }
//                    else {
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] += $eSpec['harga'];
//                    }

                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                            if (sizeof($relativeComNameDetails) > 0) {
                                $key = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
                                cekHere($key);
                                cekHere("$relativeComNameDetails[$key] " . $relativeComNameDetails[$key]);
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['relativeCom'] = isset($relativeComNameDetails[$key]) ? $relativeComNameDetails[$key] : "";
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['rekName'] = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
                            }


                        }
                    }
                }

                if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                        $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
                    }
                }
                $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
                $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
                $_SESSION[$cCode]['main']['cashMethode'] = "none";
                $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
                if (!isset($_SESSION[$cCode]["main"]["allow_multi"])) {
                    $_SESSION[$cCode]["main"]["allow_multi"] = 0;
                }

                if ($_GET["nomer"] == "pemindahbukuan") {

                }
                else {
                    if (count($shopingCartPairProdukSrc) > 0) {
                        $this->load->model("MdlTransaksi");
                        $tr = new MdlTransaksi();
                        $tr->setFilters(array());
                        $tr->addFilter("transaksi_id in ('" . implode("','", $_SESSION[$cCode]['main']['refIDs']) . "')");
                        $tmpReg = $tr->lookupDataRegistries()->result();

                        $itemRegProduk = array();
                        if (count($tmpReg) > 0) {
                            foreach ($tmpReg as $ii => $eeReg) {
//                        cekHitam("[$ii]");
//                    arrPrint(blobDecode($eeReg->main));
//                    matiHEre();
//                        arrPrint($eeReg);
//                        arrPrint($shopingCartPairProdukSrc_gate);
                                if (isset($eeReg->$shopingCartPairProdukSrc_gate)) {
                                    $itemRegRel = blobDecode($eeReg->$shopingCartPairProdukSrc_gate);

//                            if($shopingCartPairProdukSrc_gate=="items2"){
//                                foreach ($itemRegRel as $pid => $pidData) {
//
//                                    $itemRegProduk[$pid]["id"] = $pid;
//                                    foreach ($shopingCartPairProdukSrc as $src => $srcLabel) {
////                                    $itemRegProduk[$pid][$src] = isset($pidData[$src]) ? $pidData[$src] : "";
//                                        if (!array_key_exists($src, $shopingCartPairProdukSrcException)) {
//                                            $itemRegProduk[$pid][$src] = isset($pidData[$src]) ? $pidData[$src] : "";
//                                        }
//                                    }
//                                    if (!isset($itemRegProduk[$pid]["jml"])) {
//                                        $itemRegProduk[$pid]["jml"] = 0;
//                                    }
//                                    if (!isset($itemRegProduk[$pid]["ppn"])) {
//                                        $itemRegProduk[$pid]["ppn"] = 0;
//                                    }
//                                    if (!isset($itemRegProduk[$pid]["subtotal"])) {
//                                        $itemRegProduk[$pid]["subtotal"] = 0;
//                                    }
//
//                                    $itemRegProduk[$pid]["refID"] = $eeReg->transaksi_id;
//                                    $itemRegProduk[$pid]["jml"] += $pidData["jml"];
//                                    $itemRegProduk[$pid]["ppn"] += ($pidData["jml"] * $pidData["ppn"]);
//                                    $itemRegProduk[$pid]["subtotal"] += ($pidData["jml"] * $pidData["harga"]);
//                                    if ($this->jenisTr == "462") {
//                                        //untuk rata-rata harga dari multi PO jasa dibayar bebarengan
//                                        $itemRegProduk[$pid]["harga"] = $itemRegProduk[$pid]["subtotal"] / $itemRegProduk[$pid]["jml"];
//                                    }
//
//
//                                    if ($shopingCartPairProdukGateCopy == true) {
//                                        foreach ($pidData as $key => $val) {
//                                            if (!array_key_exists($key, $itemRegProduk[$pid])) {
//                                                $itemRegProduk[$pid][$key] = $val;
//                                            }
//                                        }
//                                    }
//
//                                    if ($shopingCartPairProdukGateDpp == true) {
////                                    $nilai_cek = $pidData["nett"] - $pidData["ppn"];
//                                        $dpp_persen = ($pidData["dppPPn"] / $pidData["harga"]) * 100;
//                                        $itemRegProduk[$pid]["dpp_persen"] = $dpp_persen;
////                                    mati_disini("[$dpp_persen] [$nilai_cek]");
//
////                                    $dpp_pph_persen = ($pidData["dppPPh"] / $pidData["harga"]) * 100;
////                                    $itemRegProduk[$pid]["dpp_pph_persen"] = $dpp_pph_persen;
//
//                                        $dpp_pph_persen = $pidData["harga"];
//                                        $itemRegProduk[$pid]["dpp_pph_persen"] = 100;
//                                    }
//
//                                }
//                            }
//                            else{
                                    foreach ($itemRegRel as $pid => $pidData) {
                                        $itemRegProduk[$pid]["id"] = $pid;
                                        foreach ($shopingCartPairProdukSrc as $src => $srcLabel) {
//                                    $itemRegProduk[$pid][$src] = isset($pidData[$src]) ? $pidData[$src] : "";
                                            if (!array_key_exists($src, $shopingCartPairProdukSrcException)) {
                                                $itemRegProduk[$pid][$src] = isset($pidData[$src]) ? $pidData[$src] : "";
                                            }
                                        }
                                        if (!isset($itemRegProduk[$pid]["jml"])) {
                                            $itemRegProduk[$pid]["jml"] = 0;
                                        }
                                        if (!isset($itemRegProduk[$pid]["ppn"])) {
                                            $itemRegProduk[$pid]["ppn"] = 0;
                                        }
                                        if (!isset($itemRegProduk[$pid]["subtotal"])) {
                                            $itemRegProduk[$pid]["subtotal"] = 0;
                                        }

                                        $itemRegProduk[$pid]["refID"] = $eeReg->transaksi_id;
                                        $itemRegProduk[$pid]["jml"] += $pidData["jml"];
                                        $itemRegProduk[$pid]["ppn"] += ($pidData["jml"] * $pidData["ppn"]);
                                        if ($jenisTr == "3675") {
//                                        if(!isset($itemRegProduk[$pid]["sub_harga"])){
//                                            $itemRegProduk[$pid]["sub_harga"]=0;
//                                        }
                                            $itemRegProduk[$pid]["sub_harga"] = $pidData["subtotal"];
                                            unset($itemRegProduk[$pid]["subtotal"]);
                                        }
                                        else {
//                                $itemRegProduk[$pid]["sub_total"] += ($pidData["jml"] * $pidData["harga"]);
                                            $itemRegProduk[$pid]["subtotal"] += ($pidData["jml"] * $pidData["harga"]);
                                        }

                                        if ($this->jenisTr == "462") {
                                            //untuk rata-rata harga dari multi PO jasa dibayar bebarengan
                                            $itemRegProduk[$pid]["harga"] = $itemRegProduk[$pid]["subtotal"] / $itemRegProduk[$pid]["jml"];
                                        }


                                        if ($shopingCartPairProdukGateCopy == true) {
                                            foreach ($pidData as $key => $val) {
                                                if (!array_key_exists($key, $itemRegProduk[$pid])) {
                                                    $itemRegProduk[$pid][$key] = $val;
                                                }
                                            }
                                        }

                                        if ($shopingCartPairProdukGateDpp == true) {
//                                    $nilai_cek = $pidData["nett"] - $pidData["ppn"];
                                            $dpp_persen = ($pidData["dppPPn"] / $pidData["harga"]) * 100;
                                            $itemRegProduk[$pid]["dpp_persen"] = $dpp_persen;
//                                    mati_disini("[$dpp_persen] [$nilai_cek]");

//                                    $dpp_pph_persen = ($pidData["dppPPh"] / $pidData["harga"]) * 100;
//                                    $itemRegProduk[$pid]["dpp_pph_persen"] = $dpp_pph_persen;

                                            $dpp_pph_persen = $pidData["harga"];
                                            if ($this->jenisTr == "483") {
                                                if (isset($items2Registries_01["pph"]) && ($items2Registries_01["pph"] == 1)) {// kena pph
                                                    $itemRegProduk[$pid]["dpp_pph_persen"] = 100;
                                                }
                                                else {
                                                    $itemRegProduk[$pid]["dpp_pph_persen"] = 0;
                                                }
                                            }
                                            else {
                                                $itemRegProduk[$pid]["dpp_pph_persen"] = 100;
                                            }
                                        }

                                    }
//                            }

                                }
                            }
                        }
                        /*
                             * ini untuk menampilkan produk di A/P untuk keperluan realisasi ppn
                             */
                        if (count($itemRegProduk) > 0) {
                            unset($_SESSION[$cCode]["items4_sum"]);
                            /**
                             * untuk mereset jika ada keperluan hanya menampilkan produk tertentu
                             * contoh pembayaran hutang bpsj(jenis tr 1487), hanya menmpilkan data bpjs saja, pphs1 dan gaji tidak perlu tampil
                             */
                            if (count($shopingCartForceItemSelected) > 0) {
                                $itemRegProduk__ = array();
                                if (isset($shopingCartForceItemSelected["id"])) {
                                    foreach ($shopingCartForceItemSelected["id"] as $_id) {
//                                            cekMerah(__LINE__."::".$_id);
                                        if (isset($itemRegProduk[$_id])) {
                                            $itemRegProduk__[$_id] = $itemRegProduk[$_id];
//                                                unset($itemRegProduk[$pid]);
                                        }
                                        else {
//                                                unset($itemRegProduk[$pid]);
//                                    cekBiru(__LINE__. "::".$pid);
                                        }
                                    }

                                }
                                else {
                                    $itemRegProduk__ = $itemRegProduk;
                                }
                            }
                            else {
                                $itemRegProduk__ = $itemRegProduk;
                            }
                            arrprint($itemRegProduk__);
//                    $_SESSION[$cCode]["items4_sum"] = $itemRegProduk;
                            $_SESSION[$cCode]["items4_sum"] = $itemRegProduk__;
                        }
                    }


                    /**
                     * untuk handling pajak
                     */
                    if (count($shopingCartEmbededTaxItem) > 0) {
                        $this->load->model("MdlTransaksi");
                        $tr = new MdlTransaksi();
                        $tr->setFilters(array());
                        $tr->addFilter("transaksi_id in ('" . implode("','", $_SESSION[$cCode]['main']['refIDs']) . "')");
                        $tmpReg = $tr->lookupDataRegistries()->result();
//                $itemRegProdukTax = array();
                        if (count($tmpReg) > 0) {
                            foreach ($tmpReg as $ii => $eeReg) {
                                $trid = $eeReg->transaksi_id;
                                foreach ($shopingCartEmbededGate as $ereg_key => $ereg_gateTarget) {
                                    if (isset($eeReg->$ereg_key)) {
                                        $itemRegRel = blobDecode($eeReg->$ereg_key);
                                        $_SESSION[$cCode][$ereg_gateTarget][$trid] = $itemRegRel;
                                    }

                                }

                            }
                        }
                    }

                }
//        arrPrint($_SESSION[$cCode]["items4"]);
//        matiHere();
            }
        }


        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            //region detector beda
            $arrJenisTrBeda = array("462", "1462", "487", "489");
//        if ($this->jenisTr == "462") {
            if (in_array($this->jenisTr, $arrJenisTrBeda)) {
                //cekrelasi um
                if (count($_SESSION[$cCode]['items']) == 0) {
                    $tempUm = $this->validateUangMuka($_GET["extern_id"], $_GET["transaksi_ref_id_po"]);

                    if (count($tempUm) > 0) {
                        if (isset($tempUm["um"])) {
//                        matiHEre($tempUm["um"]."::".__LINE__);
                            $_SESSION[$cCode]["main"]["validate_multi"] = "notallowed";
                        }
                        if (isset($tempUm["um_titipan"])) {
//                        matiHEre($tempUm["um_titipan"]."::".__LINE__);
                            $_SESSION[$cCode]["main"]["validate_multi"] = "notallowed";
                        }
                    }
                    else {
                        $_SESSION[$cCode]["main"]["validate_multi"] = "allowed";
                    }

                }

            }
            if (count($_SESSION[$cCode]['items']) > 0) {
                //tambah param skiper jika sduah di ok

//            if ($this->jenisTr == "462") {
                if (in_array($this->jenisTr, $arrJenisTrBeda)) {
                    $curentRelasiPo = $_SESSION[$cCode]['main']["transaksi_ref_id_po"];


                    $state = $_GET["state"];
                    $opt_id = "opt" . $_GET["transaksi_id"];
                    $link_allow_multi = MODUL_PATH . "" . get_class($this) . "/allowMultiNota/$jenisTr";

                    if ($_GET["transaksi_ref_id_po"] != $curentRelasiPo && $_SESSION[$cCode]["main"]["allow_multi"] == 0) {

                        $alert_label = isset($this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_po"]) ? $this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_po"] : "follow up 2/ lebih transaksi. Lanjutkan?";
                        $alert_label2 = isset($this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_um"]) ? $this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_um"] : "Tidak diijinkan";


                        //cek punya uang muka/relasi titipan po
                        $allowmulti_nota = false;
                        if (isset($_SESSION[$cCode]["main"]["validate_multi"]) && $_SESSION[$cCode]["main"]["validate_multi"] == "allowed") {
                            $tempValidateUm = $this->validateuangMuka($_GET["extern_id"], $_GET["transaksi_ref_id_po"]);
                            if (count($tempValidateUm) == 0) {
                                $allowmulti_nota = true;
                            }

                        }
                        else {
                            $linkOpenRealsi = base_url() . "" . "kas/Create/index/4644";
                            $linkOpenReset = MODUL_PATH . "" . "_shoppingCart/reset/462?noreload=1";
                        }

                        if ($allowmulti_nota) {
                            $alert = "<script>
        top.swal({
            title: '!! PERHATIAN !!*',
            html: '$alert_label',
            type: 'warning',
            showConfirmButton: false, // Menonaktifkan tombol confirm
            showCancelButton: false, // Menonaktifkan tombol cancel
            allowOutsideClick: false,
            onOpen: function() {
                // Membuat tombol manual setelah swal terbuka
                const modalContent = top.swal.getContent();
top.close_holdon();
                // Membuat div untuk tombol manual
                const customButtons = document.createElement('div');
                customButtons.classList.add('custom-buttons');
                customButtons.innerHTML = `
                    <div style='margin-top: 55px;'>
                        <button id='btn-yes' class='btn btn-lg btn-primary btn-yes'>Ya, Lanjutkan</button>
                        <button id='btn-no' class='btn btn-lg btn-danger btn-no'>Cancel</button>
                    </div>
                `;

                // Menambahkan tombol ke dalam modal
                modalContent.appendChild(customButtons);

                // Menambahkan event listener untuk tombol
                top.document.getElementById('btn-yes').addEventListener('click', function() {
$jenisTr
                    top.swal('menerapkan aturan');
                    top.swal.enableLoading();
                    
                    top.$.ajax({
                        url: '$link_allow_multi?state=1',
                        success: function(aaa){
                            console.log(aaa);
                            if(aaa){
                                top.$('#$opt_id')
                                .prop('checked', false)
                                .trigger('click');
                                top.swal.close();
                            }
                            else{
                                top.swal('terjadi galat');
                            }
                        }
                    });
                    

                });

                top.document.getElementById('btn-no').addEventListener('click', function() {
                    top.$('#$opt_id').prop('checked', false);
                    top.swal.close()
                });
            }
        });
    </script>";
                            echo $alert;
                            die();
                        }
                        else {
                            $alert = "<script>
        top.swal({
            title: '!! PERHATIAN !!',
            html: '$alert_label2',
            type: 'error',
            showConfirmButton: false, // Menonaktifkan tombol confirm
            showCancelButton: false, // Menonaktifkan tombol cancel
            
            allowOutsideClick: false,
            onOpen: function() {
                // Membuat tombol manual setelah swal terbuka
                const modalContent = top.swal.getContent();
                top.close_holdon();
                // Membuat div untuk tombol manual
                const customButtons = document.createElement('div');
                customButtons.classList.add('custom-buttons');
                customButtons.innerHTML = `
                <a id='' href='javascript:void(0)' onclick=\"top.$('#result').load('$linkOpenReset');top.window.location.href='$linkOpenRealsi'\"><h3 ><b>klik disini untuk lepas relasi titipan</b></h3></a>
                    <div style='margin-top: 55px;'>
                        <button id='btn-yes' class='btn btn-lg btn-primary btn-yes'>close</button>
                        
                    </div>
                `;

                // Menambahkan tombol ke dalam modal
                modalContent.appendChild(customButtons);

                // Menambahkan event listener untuk tombol
                top.document.getElementById('btn-yes').addEventListener('click', function() {
                    top.$('#$opt_id').prop('checked', false);
                    top.swal.close()
                });

                top.document.getElementById('btn-no').addEventListener('click', function() {
                    top.$('#$opt_id').prop('checked', false);
                    top.swal.close()
                });
            }
        });
    </script>";
                            echo $alert;
                            die();
                            matiHere();
                        }


                    }
                }

            }

            //endregion
        }


        //----------------------
        if (sizeof($_SESSION[$cCode]['items']) == 0) {
//            mati_disini("KOSONG...");
            // maka reset sesi saat ini...
//            echo "<script>";
//            echo "top.$('#result').load('" . MODUL_PATH . "/_shoppingCart/reset/" . $this->jenisTr . "');";
//            echo "</script>";
        }

        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        //-----------------------------------------------------
        $_SESSION[$cCode]["main"]["date_keluaran"] = $bulan_keluaran;
        //-----------------------------------------------------
        foreach ($lembarFaktur as $gate => $gerbang) {
            $_SESSION[$cCode]["main"][$gerbang] = isset($_SESSION[$cCode][$gate]) ? count($_SESSION[$cCode][$gate]) : 0;
        }

        if (sizeof($additionalDetailByCabang) > 0) {
            if (isset($additionalDetailByCabang["enabled"]) && ($additionalDetailByCabang["enabled"] == true)) {
                $_SESSION[$cCode] = pisahGerbangKoreksi($this->configUi[$this->jenisTr], $_SESSION[$cCode]);
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
        $vg->buildValue($this->jenisTr, 0, $initMasterValues, $this->modul);

        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            if (count($ppnCekDefaultValue) > 0) {
                if ($ppnCekDefaultValue["enabled"] == true) {
                    if (isset($ppnCekDefaultValue["required_value"])) {
                        $src_val_def = $ppnCekDefaultValue["required_value"];
                        if (isset($_GET[$src_val_def]) && $_GET[$src_val_def] > 0) {
                            $selectedDef = 1;
                        }
                        else {
                            $selectedDef = $ppnCekDefaultValue["ppnPersenCheck"];
                        }
                    }
                    else {
                        //defaultaja
                        $selectedDef = $ppnCekDefaultValue["ppnPersenCheck"];
                    }
                    $ppnPersenCheck = isset($_SESSION[$cCode]["main"]["ppnPersenCheck"]) ? $_SESSION[$cCode]["main"]["ppnPersenCheck"] : $selectedDef;
                    $keyDetail = $ppnCekDefaultValue["keyDetail"];
                    $selsMainTarget = $keyDetail['srcMain'];
                    $selsItemsTarget = $keyDetail['srcItem'];
                    $mainOverwrite = $keyDetail['overWriteMain'];

                    echo "<script>";
                    echo "  top.$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn=$ppnPersenCheck&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite" . "');";
                    echo "</script>";
                }
            }

            if (count($gateReplaceMain) > 0) {
                foreach ($gateReplaceMain as $target => $source) {
                    $_SESSION[$cCode]["main"][$target] = $_GET[$source];
                }
            }

            // ini exception, bila asal hutang jasa dari kode 6678/16678 maka dihapus items4_sum nya, supaya tidak dikoreksi harga belinya. 26 mei 2025
            if (in_array($_SESSION[$cCode]["main"]["jenis_ref_po"], $shopingCartPairProdukGateException)) {
                $_SESSION[$cCode]["items4_sum"] = NULL;
                unset($_SESSION[$cCode]["items4_sum"]);
                $_SESSION[$cCode]["main"]["dppPPh"] = 0;
            }

        }

    }
}
