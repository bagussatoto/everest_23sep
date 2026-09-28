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
        $this->load->helper("he_mass_table");
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
        $this->reject = true;
        $this->reject_all = true;
        $this->inActiveTime = 10;
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
        $fieldValidatorPairedItem = isset($this->configUi[$this->jenisTr]["shoppingCartValidatorsPairedItem"]) ? $this->configUi[$this->jenisTr]["shoppingCartValidatorsPairedItem"] : array();
        $unionValidateComparison = isset($this->configUi[$this->jenisTr]["shopingCartUnionComparison"]) ? $this->configUi[$this->jenisTr]["shopingCartUnionComparison"] : array();
        $unionValidate = isset($this->configUi[$this->jenisTr]["shoppingCartUnionValidators"]) ? $this->configUi[$this->jenisTr]["shoppingCartUnionValidators"] : array();
        $balanceValidate = isset($this->configUi[$this->jenisTr]["shoppingCartBalanceValidate"]) ? $this->configUi[$this->jenisTr]["shoppingCartBalanceValidate"] : array();
        $shopingCartPaymentItemsValidator = isset($this->configUi[$this->jenisTr]["shopingCartPaymentItemsValidator"]) ? $this->configUi[$this->jenisTr]["shopingCartPaymentItemsValidator"] : array();
        $previewCtr = isset($this->configUi[$this->jenisTr]["previewCtr"]) ? $this->configUi[$this->jenisTr]["previewCtr"] : matiHere('controller preview belum diset');
        $shopingCartAddTax = isset($this->configUi[$this->jenisTr]["shopingCartAddTax"]) ? $this->configUi[$this->jenisTr]["shopingCartAddTax"] : array();
        $kelebihanBayarValidator = isset($this->configUi[$this->jenisTr]["kelebihanBayarValidator"]["enabled"]) ? $this->configUi[$this->jenisTr]["kelebihanBayarValidator"]["enabled"] : false;
        $validatorKewajibanBayar = isset($this->configUi[$this->jenisTr]["validatorKewajibanBayar"]) ? $this->configUi[$this->jenisTr]["validatorKewajibanBayar"] : array();
        $shoppingCartElementValidator = isset($this->configUi[$this->jenisTr]["shoppingCartElementValidator"]) ? $this->configUi[$this->jenisTr]["shoppingCartElementValidator"] : array();

        $cCode = $this->cCode;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();
        if (sizeof($selectorNotaLocker) > 0 && isset($selectorNotaLocker['enabled']) && $selectorNotaLocker['enabled'] == true) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $this->load->model("Mdls/MdlEmployee");
                $lt = New MdlLockerTransaksi();
                $empObj = new MdlEmployee();
                $liveLockBlocked = array();
                foreach ($_SESSION[$cCode]['items'] as $tridd => $itemSpec) {
                    $lt->setFilters(array());
                    $lt->addFilter("transaksi_id='" . $tridd . "'");
                    $lt->addFilter("state='hold'");
                    $lt->addFilter("jumlah='1'");
                    $ltTmp = $lt->lookupAll()->result();
                    cekLime($this->db->last_query());
                    $allowedActionByHold = array();
                    $allowedActionByIdle = array();
                    if (sizeof($ltTmp) > 0) {
                        $this->load->model("Mdls/MdlEmployee");
                        $empObj = new MdlEmployee();
                        foreach ($ltTmp as $ltSpec) {
                            if ($ltSpec->oleh_id == my_id()) {
                                continue;
                            }
                            $isLiveLock = false;
                            $holderName = $ltSpec->oleh_nama;
                            $empObj->setFilters(array());
                            $empQuery = $empObj->lookupByCondition(array("id" => $ltSpec->oleh_id))->result();
                            if (sizeof($empQuery) > 0) {
                                if (!empty($empQuery[0]->nama)) {
                                    $holderName = $empQuery[0]->nama;
                                }
                                if (isset($empQuery[0]->status_login) && $empQuery[0]->status_login == 1 && !empty($empQuery[0]->phpsessid) && !empty($empQuery[0]->last_dtime_active)) {
                                    $inactiveSeconds = timeDiff($empQuery[0]->last_dtime_active, dtimeNow());
                                    $inactiveMinutes = floor($inactiveSeconds / 60);
                                    if ($inactiveMinutes < 5) {
                                        $isLiveLock = true;
                                    }
                                }
                            }
                            if ($isLiveLock == true) {
                                // Holder masih aktif — blokir nota, tidak bisa dipilih
                                $allowedActionByHold[$ltSpec->transaksi_id] = array(
                                    "transaksi_id" => $ltSpec->transaksi_id,
                                    "oleh_id" => $ltSpec->oleh_id,
                                    "oleh_nama" => $holderName,
                                );
                                $liveLockBlocked[$tridd] = $holderName;
                            }
                            else {
                                // Holder terdeteksi idle > 5 menit — nota bisa diambil alih
                                $allowedActionByIdle[$ltSpec->transaksi_id] = array(
                                    "transaksi_id" => $ltSpec->transaksi_id,
                                    "oleh_id" => $ltSpec->oleh_id,
                                    "oleh_nama" => $holderName,
                                );
                            }
                        }
                    }
                }
                if (sizeof($liveLockBlocked) > 0) {
                    $holderName = "";
                    foreach ($liveLockBlocked as $tridd => $tmpHolderName) {
                        $holderName = $tmpHolderName;
                        break;
                    }
                    $msg = "Nota yang Anda pilih sedang ditindaklanjuti oleh " . $holderName . ". Silahkan pilih nota lainnya.";
                    die(lgShowError("Transaksi sedang dikunci", $msg));
                }
            }
        }
        //==iterasi untuk memasukkan element relatif
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
                if (array_key_exists($eName, $relElementConfigs)) {
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
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                $elKey = $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
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
                elseif ($_SESSION[$cCode]['main'][$field] == NULL) {
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

        if ($kelebihanBayarValidator == true) {
            $lebih_bayar = isset($_SESSION[$cCode]['main']['lebih_bayar']) ? $_SESSION[$cCode]['main']['lebih_bayar'] : 0;
            $lebih_bayar_metode = isset($_SESSION[$cCode]['main']['kelebihanBayar']) ? $_SESSION[$cCode]['main']['kelebihanBayar'] : null;
            if ($lebih_bayar > 0) {
                if ($lebih_bayar_metode == null) {
                    $msg = $this->configUi[$this->jenisTr]["kelebihanBayarValidator"]["label"];
                    $errMsgs[] = $msg;
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

        //------
        if (sizeof($shopingCartAddTax) > 0) {
            if (isset($shopingCartAddTax["key"])) {
                if (!isset($_SESSION[$cCode]["main"][$shopingCartAddTax["key"]])) {
                    $msg = $shopingCartAddTax["label"];
                    $errMsgs[] = $msg;
                }
            }
        }
        //------
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
//                            $sumber_dana = "";
//                            if (sizeof($validatorSumberDana) > 0) {
//                                $sumber_dana = implode(", ", $validatorSumberDana);
//                            }
                            $msg = "Masih ada kewajiban bayar $nilai, silahkan pilih Payment Method dan akun yang tersedia. ";
                            mati_disini($msg);
                        }

                    }
                }
            }
        }

        if (sizeof($shoppingCartElementValidator) > 0) {
            if (isset($shoppingCartElementValidator["enabled"]) && ($shoppingCartElementValidator["enabled"] == true)) {
                foreach ($shoppingCartElementValidator["element"] as $element => $elSpec) {
                    foreach ($elSpec as $src => $targetSpec) {
                        $src_value = $_SESSION[$cCode]["main"][$src];
                        $target_value = $_SESSION[$cCode]["main"][$targetSpec["target"]];
                        if ($src_value > 0) {
                            if ($src_value != $target_value) {
                                mati_disini($targetSpec["label"]);
                            }
                        }
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
        // cekLime($this->db->last_query());

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
                            $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
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
        showLast_query("lime");
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
//                                        if (!isset($row->$k)) { // ini kalau ketemu $k == NULL akan macet.
                                        if (($k != NULL) && !isset($row->$k)) {// cek dulu $k != NULL, maka pairing/join dilakukan.
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
                                                if (($k1 != NULL) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                            else {
                                                if (($k1 != NULL) && !isset($row->$k1)) {
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

                //EDIT------------------------------
                $pakai_ini = 0;
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
// cekHitam(":: cetak onProgress2 ::");
// arrPrintWebs($arrayOnprogress2);


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
                $labels = isset($sSpec['label']) ? $sSpec['label'] : $sSpec['title'];

                $edit_description = "";
                if (isset($arrTopEdit[$sSpec['link_id']])) {
                    $edit_dtime = formatField_he_format("date_time", $arrTopEdit[$sSpec['link_id']]['edit_dtime']);
                    $edit_nama = $arrTopEdit[$sSpec['link_id']]['edit_name'];
                    $edit_description = "<span style='color:red;'>Edited by $edit_nama, $edit_dtime</span>";
                }


                $signStr .= "<tr>";
                $signStr .= "<td>" . formatField_he_format("date_time", $sSpec['dtime']);
                $signStr .= "</td>";
                $signStr .= "<td>" . ($labels);
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
        $printLocation = isset($this->configLayout[$jenisMasterTrans]['printLocation']) ? $this->configLayout[$jenisMasterTrans]['printLocation'] : "Printing/viewReceipt/";

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
        $sumRows = isset($this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1]) ? $this->configUi[$jenisMasterTrans]['shoppingCartSumFields'][1] : (isset($this->configLayout[$jenisMasterTrans]['receiptSumFields'][1]) ? $this->configLayout[$jenisMasterTrans]['receiptSumFields'][1] : '');
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
//        arrPrint($this->uri->segment_array());

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
                        //                        $mainData[$row->$srcKey][$srcKey_0] = formatField_he_format($srcKey_0, $val_main);

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
                            $mainData[$masterID]['sub_outstanding_items'] = formatField_he_format("debet", $sub_tmpOutstandingPair);
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
        if ($paymentConfig == true) {
            $link_undoneList = MODUL_PATH . "Transaksi/viewSelectPaymentExternSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno";
        }
        else {
            $link_undoneList = MODUL_PATH . "Transaksi/viewUndoneItemsIndex/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";
        }

        // dibawh ini undone list berisi data dari tabel transaksi_payment_source
        switch ($this->jenisTr){
            case "7468":
                $link_undoneList = MODUL_PATH . "Transaksi/viewSelectPaymentExternSrc/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno";
                break;
        }



        $link_historyList = MODUL_PATH . "History/showData/$jenisTr/?limit=10";
        $link_kepoinList = base_url() . $this->modul . "/Transaksi/viewKepoinItems/$jenisTr/?gr=cGVtYmVsaWFu";

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
                     \n
                    var viewkepoinList = function () {
                        top.$('#kepoinList').html(\"<div class='row' style='margin-top: 14px; padding-bottom: 14px;'> <span class='col-lg-12 text-center'><img width='5%' src='$baseUrl/assets/images/loading_16_p.gif'></span> </div>\");
                        setTimeout(function () {
                              if (top.$('#kepoinList').find('img').length > 0) {
                                  top.$('#kepoinList').load('$link_kepoinList', function(){viewkepoinList})
                         }
                        },1000);
                     }\n
                     viewkepoinList()\n
         </script>
        ";

        $linkMenu = isset($this->configUi[$jenisTr]['linkMenu']) ? $this->configUi[$jenisTr]['linkMenu'] : array();


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
            "linkMenu" => $linkMenu,
        );
        //endregion

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
//                arrPrintPink($data);

        if (!isset($_GET['json'])) {
            $this->load->view("transaksi", $data);
        }
        else {
            //            echo json_encode($data);

//            arrPrint($data);
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
        $this->load->model("Mdls/MdlLockerTransaksi");

        // START OF COMPLETE REPEATED LOGIC
        if (isset($_GET['forceRetakeLock']) && $_GET['forceRetakeLock'] == 1 && isset($_GET['retakeTrID']) && !empty($_GET['retakeTrID'])) {
            $retakeTrID = $_GET['retakeTrID'];
            $this->db->trans_start();
            $this->db->where(array(
                "transaksi_id" => $retakeTrID,
                "state" => "hold",
                "jenis" => "transaksi",
                "jenis_locker" => "transaksi"
            ))->update("stock_locker_transaksi", array("jumlah" => 0));
            $this->db->trans_complete();
        }
        // END OF COMPLETE REPEATED LOGIC

        $modePym = (isset($_GET["md"]) && ($_GET["md"] != NULL)) ? "&md=" . $_GET["md"] : "";


        $scriptBottom = "";
        $scriptTrigger = "";

        $scriptTrigger .= "$('.chRadio').trigger('click')";


        $jenisTr = $targetJenis = $this->jenisTr;
        $cCode = $this->cCode;


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
        $kelebihanBayarConfig = isset($this->configUi[$targetJenis]['kelebihanBayar']) && ($this->configUi[$targetJenis]['kelebihanBayar'] == true) ? true : false;
        $paymentSrcLocked = isset($this->configUi[$targetJenis]['paymentSrcLocked']) ? $this->configUi[$targetJenis]['paymentSrcLocked'] : array();
        $pembayaranBerurutan = isset($this->configUi[$targetJenis]['pembayaranBerurutan']) ? $this->configUi[$targetJenis]['pembayaranBerurutan'] : false;
        $pairDepositKonsumen = isset($this->configUi[$targetJenis]['pairDepositKonsumen']) ? $this->configUi[$targetJenis]['pairDepositKonsumen'] : array();
        $referenceAlias = isset($this->configUi[$targetJenis]['referenceAlias']) ? $this->configUi[$targetJenis]['referenceAlias'] : array();

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

        $elementConfigs = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("sisa>1");
        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($targetJenis)->result();

        //-------------------------------------------------
        $buttonMutasi = "";
        $pairDepositKonsumenDatas = array();
        if (sizeof($pairDepositKonsumen) > 0) {
            foreach ($pairDepositKonsumen as $kkey => $kSpec) {
                $comName = $kSpec["comName"];
                $rekening = $kSpec["rekening"];
                $sub_rekening = $kSpec["sub_rekening"];
                $posisi = $kSpec["saldo"];
                $label = $kSpec["label"];
                $cabang_id_el = isset($el_spec["cabang_id"]) ? $el_spec["cabang_id"] : my_cabang_id();
                $this->load->model("Coms/$comName");
                $mm = New $comName();
                $mm->addFilter("extern2_id='$sub_rekening'");
                if ($externID > 0) {
                    $mm->addFilter("extern_id='$externID'");
                }
                $mmTmp = $mm->fetchBalances($rekening);
                if (sizeof($mmTmp) > 0) {
                    foreach ($mmTmp as $mmSpec) {
                        $pairDepositKonsumenDatas[$kkey][$mmSpec->extern_id] = array(
                            "id" => $mmSpec->extern_id,
                            "nama" => $mmSpec->extern_nama,
                            "nilai" => $mmSpec->$posisi,
                        );
                        $blob_ext = $mmSpec->extern2_nama . "  " . $mmSpec->extern_nama;
                        $blob_ext = blobEncode($blob_ext);
                        $link_mutasi = base_url() . $kSpec['link'] . "$rekening/$externID/$sub_rekening?o=" . $cabang_id_el . "&main_ext2_id=$externID&blob_ext=$blob_ext";
                        $nilai_saldo_f = number_format($mmSpec->$posisi, "0", ".", ",");
                        $modalDialog = modalDialogBtn('&nbsp;', $link_mutasi, $auto_close = 0, 'saldo');
                        $buttonMutasi .= "<a href='javascript:void(0)' onclick=\"$modalDialog\" ttarget='_blank'>
                                <span class='btn btn-info'>$label: $nilai_saldo_f</span></a>";
                    }
                }
                else {
                    $pairDepositKonsumenDatas[$kkey] = array();
                }
            }
        }
        //-------------------------------------------------
        $allowedSaldo = isset($this->configUi[$targetJenis]['allowedSaldo']) ? $this->configUi[$targetJenis]['allowedSaldo'] : array();
        $buttonMutasi = "";
        if (sizeof($allowedSaldo) > 0) {
            if (isset($allowedSaldo["enabled"]) && ($allowedSaldo["enabled"] == true)) {
                $elementMutasiConfig = $this->config->item("accountElementMutasi") != NULL ? $this->config->item("accountElementMutasi") : array();
                if ($this->session->login['cabang_id'] > 0) {
                    $elementMutasiTmp = $elementMutasiConfig["branch"];
                    $subjectID = $externID;
                    $subjectNama = $externName;
                    foreach ($elementMutasiTmp as $el_nama => $el_spec) {
                        $allowed = isset($el_spec["allowed"]) ? $el_spec["allowed"] : array();
                        if (in_array($targetJenis, $allowed)) {

                            $blob_ext = $el_spec["label"] . " $subjectNama ";
                            $blob_ext = blobEncode($blob_ext);
                            $sub_rek = $el_spec["sub_rekening"];
                            $rek = $el_spec["rekening"];
                            $comName = $el_spec["comName"];
                            $source = $el_spec["source"];
                            $label = $el_spec["label"];
                            $cabang_id_el = isset($el_spec["cabang_id"]) ? $el_spec["cabang_id"] : $this->session->login['cabang_id'];
                            $this->load->model("Coms/$comName");
                            $cr = New $comName();
                            $cr->addFilter("rekening='$rek'");
                            $cr->addFilter("extern2_id='$sub_rek'");
                            $cr->addFilter("extern_id='$subjectID'");
                            $cr->addFilter("periode='forever'");
                            $cr->addFilter("cabang_id='$cabang_id_el'");
                            $crTmp = $cr->lookupAll()->result();
                            $nilai_saldo = 0;
                            if (sizeof($crTmp) > 0) {
                                $nilai_saldo = $crTmp[0]->$source;
                            }
                            $link_mutasi = base_url() . $el_spec['link'] . "$subjectID/$sub_rek?o=" . $cabang_id_el . "&main_ext2_id=$subjectID&blob_ext=$blob_ext";


                            $nilai_saldo_f = number_format($nilai_saldo, "0", ".", ",");
                            $modalDialog = modalDialogBtn('&nbsp;', $link_mutasi, $auto_close = 0, 'saldo');
                            $buttonMutasi .= "<a href='javascript:void(0)' onclick=\"$modalDialog\" ttarget='_blank'>
                                <span class='btn btn-info'>$label: $nilai_saldo_f</span></a>";

                        }
                    }
                }
            }
        }
        //-------------------------------------------------



        $externName = "";
        $items = array();
        $tempDueDate = array();
        $ppnDisabled = array();
        $paymentSrcDisabled = array();
        $lockerDisabled = array();
        $transaksiIds_items = array();
        $trIDs = array();
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {
                $id = $row->id;
                $transaksi_id = $row->transaksi_id;
                $transaksiIds_items[] = $transaksi_id;
            }
            $lt = New MdlLockerTransaksi();
            $lt->addFilter("transaksi_id in ('" . implode("','", $transaksiIds_items) . "')");
            $lt->addFilter("state='hold'");
            $lt->addFilter("jumlah='1'");
            $ltTmp = $lt->lookupAll()->result();
            cekLime($this->db->last_query());
            $allowedActionByHold = array();
            $allowedActionByIdle = array();
            if (sizeof($ltTmp) > 0) {
                $this->load->model("Mdls/MdlEmployee");
                $empObj = new MdlEmployee();
                foreach ($ltTmp as $ltSpec) {
                    if ($ltSpec->oleh_id == my_id()) {
                        continue;
                    }
                    $isLiveLock = false;
                    $holderName = $ltSpec->oleh_nama;
                    $empObj->setFilters(array());
                    $empQuery = $empObj->lookupByCondition(array("id" => $ltSpec->oleh_id))->result();
                    if (sizeof($empQuery) > 0) {
                        if (!empty($empQuery[0]->nama)) {
                            $holderName = $empQuery[0]->nama;
                        }
                        if (isset($empQuery[0]->status_login) && $empQuery[0]->status_login == 1 && !empty($empQuery[0]->phpsessid) && !empty($empQuery[0]->last_dtime_active)) {
                            $inactiveSeconds = timeDiff($empQuery[0]->last_dtime_active, dtimeNow());
                            $inactiveMinutes = floor($inactiveSeconds / 60);
                            if ($inactiveMinutes < $this->inActiveTime) {
                                $isLiveLock = true;
                            }
                        }
                    }
                    if ($isLiveLock == true) {
                        // Holder masih aktif — blokir nota, tidak bisa dipilih
                        $allowedActionByHold[$ltSpec->transaksi_id] = array(
                            "transaksi_id" => $ltSpec->transaksi_id,
                            "oleh_id" => $ltSpec->oleh_id,
                            "oleh_nama" => $holderName,
                        );
                    }
                    else {
                        // Holder terdeteksi idle > 5 menit — nota bisa diambil alih
                        $allowedActionByIdle[$ltSpec->transaksi_id] = array(
                            "transaksi_id" => $ltSpec->transaksi_id,
                            "oleh_id" => $ltSpec->oleh_id,
                            "oleh_nama" => $holderName,
                        );
                    }
                }
            }
            foreach ($tmpSrc as $row) {
                $tmp = array();
                $jenis = $row->jenis;
                $transaksi_id = $row->id;
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
                if (isset($paymentSrcLocked['enabled']) && ($paymentSrcLocked['enabled'] == true)) {
                    if ($row->payment_locked == 1) {
                        $paymentSrcDisabled[] = $row->transaksi_id;
                        $tmp['notes'] = isset($paymentSrcLocked['notes']) ? "<span style='color:red;font-size:12px;font-weight:bold;'>" . $paymentSrcLocked['notes'] . "</span>" : "-";
                    }
                }
                if (array_key_exists($row->transaksi_id, $allowedActionByHold)) {
                    $tmp['hold_disabled'] = true;
                    $tmp['notes'] = "<span style='color:red;font-size:12px;font-weight:bold;'>Nota ini sedang ditindaklanjuti<br>oleh " . $allowedActionByHold[$row->transaksi_id]["oleh_nama"] . "</span>";
                }
                elseif (array_key_exists($row->transaksi_id, $allowedActionByIdle)) {
                    $tmp['idle_lock'] = true;
                    $tmp['lock_holder'] = $allowedActionByIdle[$row->transaksi_id]["oleh_nama"];
                    $tmp['notes'] = "<span style='color:orange;font-size:12px;font-weight:bold;'>
Nota ini sedang ditindaklanjuti oleh " . $allowedActionByIdle[$row->transaksi_id]["oleh_nama"] . ",<br>
<span style='color:blue;ttext-decoration:underline;cursor:pointer;'>namun sudah tidak aktif lebih dari ".$this->inActiveTime." menit.<br>
Ceklist untuk mengambil alih.</span></span>";
                }

                $items[] = (array)$row + $tmp;
                $externName = $row->extern_nama;

                $trIDs[] = $row->transaksi_id;
            }
        }
        $cCode = "_TR_" . $targetJenis;
        $defaultItemLabels = array(
            "extern_label2" => "jenis",
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
//            "refNum" => "return ref.",
            "fulldate" => "date",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "due remain",
        );
        $jQueryCustom = isset($this->configUi[$this->jenisTr]['jQueryCustom']) ? $this->configUi[$this->jenisTr]['jQueryCustom'] : "";
        if (sizeof($jQueryCustom) > 0) {
            $scriptBottom .= $jQueryCustom;
        }

        $defSisa = isset($_SESSION[$cCode]['main']['sisa']) ? $_SESSION[$cCode]['main']['sisa'] : 0;

        //----
        if(isset($this->configUi[$targetJenis]["selectorProcessorPymSrc"]) && ($this->configUi[$targetJenis]["selectorProcessorPymSrc"] != NULL)){
            $selectProcessor = $this->configUi[$targetJenis]["selectorProcessorPymSrc"];
        }
        else{
            $selectProcessor = $this->configUi[$targetJenis]["selectorProcessor"];
        }
        //----

        $qstrLabels = array(
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
            "ppn_sisa" => "ppn_payment",
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            "extern_jenis" => "extern_jenis",
            "jenis_master" => "jenis_master",
//                        "id_master" => "id_master",
            "target_jenis" => "jenis_source",
            //-----
            "customers_id" => "customers_id",
            "customers_nama" => "customers_nama",
            "suppliers_id" => "suppliers_id",
            "suppliers_nama" => "suppliers_nama",
            "cash_account" => "cash_account",
            "biaya_rekening_id" => "biaya_rekening_id",
            "biaya_rekening_id_label" => "biaya_rekening_id_label",
            "oleh_nama" => "oleh_nama",
            "dtime" => "dtime",
//            "biaya_rekening_id" => "biaya_rekening_id",
//            "biaya_rekening_id" => "biaya_rekening_id",
        );

        $data = array(
            "mode" => 'selectPaymentSrc',
            "title" => $this->configUi[$targetJenis]["label"],
            "subTitle" => $externName,
            "jenisTr" => $targetJenis,
            "items" => $items,
            "prePrint" => $prePrint,
            "itemLabels" => isset($this->configUi[$targetJenis]["shoppingCartReferenceFields"]) ? $this->configUi[$targetJenis]["shoppingCartReferenceFields"] : $defaultItemLabels,
            "selectProcessor" => $selectProcessor,
            "paymentSubtitle" => "details for " . $this->configUi[$this->jenisTr]['steps'][1]['label'],
            "btnLabel" => "continue " . $this->configUi[$this->jenisTr]['steps'][1]['label'],
            "ses_outMaster" => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
            "ses_items" => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
            // -----------
            "actionTarget" => "top.$('#result').load('" . MODUL_PATH . "Transaksi/validate/" . $targetJenis . "?rawPrev=$rawPrevURL');",
            "columnRecorderTarget" => MODUL_PATH . "_shoppingCart/recordColumn/" . $this->jenisTr . "/nilai_entry",
            "bankColumnRecorderTarget" => MODUL_PATH . "_shoppingCart/recordColumn/" . $this->jenisTr . "/paymentMethod_cash",
            // -------------------
            "selectedBankID" => isset($_SESSION[$cCode]['main']['cash_account']) ? $_SESSION[$cCode]['main']['cash_account'] : 0,
            "dueDateReader" => $readerDueDate,
            "tagihanSrc" => $tagihanSrc,
            "scriptBottom" => $scriptBottom,
            "tagihanValue" => isset($_SESSION[$cCode]['main'][$tagihanSrc]) ? $_SESSION[$cCode]['main'][$tagihanSrc] : $defSisa,
            "isPaymentRadioSelect" => isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false,
            "kelebihanBayar" => $kelebihanBayarConfig,
            "ppnDisabled" => $ppnDisabled,
            "scriptTrigger" => $scriptTrigger,
            "lockerDisabled" => isset($lockerDisabled) ? $lockerDisabled : array(),
            "paymentSrcDisabled" => isset($paymentSrcDisabled) ? $paymentSrcDisabled : array(),
            "pembayaranBerurutan" => isset($pembayaranBerurutan) ? $pembayaranBerurutan : false,
            //-------------------
            "pairDepositKonsumenDatas" => $pairDepositKonsumenDatas,
            "pairDepositKonsumen" => $pairDepositKonsumen,
            "buttonMutasi" => isset($buttonMutasi) ? $buttonMutasi : "",
            "clearContentTarget" => MODUL_PATH . get_class($this) . "/clearContent/" . $this->jenisTr,
//            "inActiveTime" => $this->inActiveTime,
            "qstrLabels" => isset($qstrLabels) ? $qstrLabels : array(),
            "modePym" => $modePym,
        );

        if (!isset($_GET['json'])) {
            $this->load->view("transaksi", $data);
        }
        else {
            return json_encode($data);
        }


    }

    public function viewSelectPaymentExternSrc()
    {
//        cekBiru(url_segment());
        $jenisTr = $targetJenis = $this->jenisTr;
        $shortKepoinLabel = isset($this->configUi[$jenisTr]['shortKepoinLabel']) ? $this->configUi[$jenisTr]['shortKepoinLabel'] : "transaksi belum selesai";
        $kepoinFields = isset($this->configUi[$jenisTr]['shortKepoinFields']) ? $this->configUi[$jenisTr]['shortKepoinFields'] : array();
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $pairDepositKonsumen = isset($this->configUi[$jenisTr]['pairDepositKonsumen']) ? $this->configUi[$jenisTr]['pairDepositKonsumen'] : array();

        $modePym = "";
        switch ($targetJenis){
            case "7468":
                $modePym = "&md=pymSrc";
                break;
        }

        $this->load->model("MdlTransaksi");

        heInitCart($targetJenis);

        //==dapatkan srcJenis
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
        $readerDueDate = isset($this->configUi[$targetJenis]['dueDateReader']) ? $this->configUi[$targetJenis]['dueDateReader'] : false;
        $settleBtnShow = isset($this->configUi[$targetJenis]["settlementBtn"]) ? $this->configUi[$targetJenis]["settlementBtn"] : false;
        $shoppingCartPerTransaksi = isset($this->configUi[$targetJenis]['shoppingCartPerTransaksi']) ? $this->configUi[$targetJenis]['shoppingCartPerTransaksi'] : 0;
        $shoppingCartPerTransaksiBtn = isset($this->configUi[$targetJenis]['shoppingCartPerTransaksiBtn']) ? $this->configUi[$targetJenis]['shoppingCartPerTransaksiBtn'] : 0;
        $referenceAlias = isset($this->configUi[$targetJenis]['referenceAlias']) ? $this->configUi[$targetJenis]['referenceAlias'] : array();
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
        $itemLabels["button"] = "terima";

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("sisa>100");
        $tmpSrc = $tmpSrc_01 = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        showLast_query("biru");
        //-------------------------------------------------
        $pairDepositKonsumenDatas = array();
        $pairDepositKonsumenMutasi = array();
        if (sizeof($pairDepositKonsumen) > 0) {
            foreach ($pairDepositKonsumen as $kkey => $kSpec) {
                $comName = $kSpec["comName"];
                $rekening = $kSpec["rekening"];
                $sub_rekening = $kSpec["sub_rekening"];
                $posisi = $kSpec["saldo"];
                $this->load->model("Coms/$comName");
                //------
                $mm = New $comName();
                $mm->addFilter("extern2_id='$sub_rekening'");
                $mmTmp = $mm->fetchBalances($rekening);
                if (sizeof($mmTmp) > 0) {
                    foreach ($mmTmp as $mmSpec) {
                        $pairDepositKonsumenDatas[$kkey][$mmSpec->extern_id] = array(
                            "id" => $mmSpec->extern_id,
                            "nama" => $mmSpec->extern_nama,
                            "nilai" => $mmSpec->$posisi,
                        );
                    }
                }
                else {
                    $pairDepositKonsumenDatas[$kkey] = array();
                }
                //------
                $mt = New $comName();
                $mt->addFilter("cabang_id=" . my_cabang_id());
                $mtTmp = $mt->fetchMoves($rekening, NULL, $sub_rekening);
                showLast_query("biru");
                if (sizeof($mtTmp) > 0) {
                    foreach ($mtTmp as $mtSpec) {
                        $pairDepositKonsumenMutasi_0[$mtSpec->extern_id] = array(
                            "id" => $mtSpec->extern_id,
                            "nama" => $mtSpec->extern_nama,
                            "nilai" => $mtSpec->$posisi,
                            "transaksi_id" => $mtSpec->transaksi_id,
                        );
                    }
                    foreach ($pairDepositKonsumenMutasi_0 as $ext_id => $extSpec) {
                        $tr_id = $extSpec["transaksi_id"];
                        if ($tr_id > 0) {
                            $mtr = New Mdltransaksi();
                            $mtr->addFilter("id='$tr_id'");
                            $mtrTmp = $mtr->lookupAll()->result();
                            $pairDepositKonsumenMutasi[$kkey][$ext_id] = array(
                                "id" => $mtrTmp[0]->oleh_id,
                                "nama" => $mtrTmp[0]->oleh_nama,
                            );
                        }
                        else {
                            $pairDepositKonsumenMutasi[$kkey][$ext_id] = array(
                                "id" => 100,
                                "nama" => "SYSTEM",
                            );
                        }
                    }

                }
                //------
            }
        }
        //-------------------------------------------------
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
        $extern_nilai2 = array();
        $extern_nilai3 = array();
        $extern_nilai4 = array();
        $extern_nilai5 = array();
        $extern_nilai6 = array();
        $arrayOnprogressTransaksi = array();
        $arrayOnprogressTransaksiKoloms = array();
        $arrNotaINV_settlement = array();
        $srcLabel = "";
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {
                $tmp = array();

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
                    $tmp["link"] = MODUL_PATH . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id . "?$modePym";
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
                $srcLabel = $row->label;
            }
            $colspan_item = 1;
            foreach ($items as $externID => $iSpec) {
                $items[$externID]['tagihan'] = $tagihans[$externID];
                $items[$externID]['terbayar'] = $terbayars[$externID];
                $items[$externID]['diskon'] = $diskons[$externID];
                $items[$externID]['sisa'] = $sisas[$externID];
                $items[$externID]['tagihan_valas'] = $tagihans_valas[$externID];
                $items[$externID]['terbayar_valas'] = $terbayars_valas[$externID];
                $items[$externID]['diskon_valas'] = $diskons_valas[$externID];
                $items[$externID]['sisa_valas'] = $sisas_valas[$externID];
                $items[$externID]['qty_nota '] = "";
                if (count($referenceAlias) > 0) {
                    $colspan_item = 2;
                    foreach ($referenceAlias as $inewKey => $tmpAlias) {
                        $items[$externID][$inewKey] = $tmpAlias[$items[$externID][$inewKey]];
                    }
                }

            }
            if (sizeof($pairDepositKonsumenDatas) > 0) {
                foreach ($pairDepositKonsumenDatas as $kkey => $kkSpec) {
                    foreach ($kkSpec as $cid => $cSpec) {
                        if (array_key_exists($cid, $items)) {
                            if (!isset($items[$cid]["extern_id"])) {
                                $items[$cid]["extern_id"] = $cSpec["id"];
                            }
                            if (!isset($items[$cid]["extern_nama"])) {
                                $items[$cid]["extern_nama"] = $cSpec["nama"];
                            }
                            $items[$cid][$kkey] = $cSpec["nilai"];
                        }
                    }
                    if (isset($pairDepositKonsumenMutasi[$kkey])) {
                        foreach ($pairDepositKonsumenMutasi[$kkey] as $cid => $cSpec) {
                            if (array_key_exists($cid, $items)) {
                                if (!isset($items[$cid]["reference_oleh_id"])) {
                                    $items[$cid]["reference_oleh_id"] = $cSpec["id"];
                                }
                                if (!isset($items[$cid]["reference_oleh_nama"])) {
                                    $items[$cid]["reference_oleh_nama"] = $cSpec["nama"];
                                }
                            }
                        }
                    }
                }
            }
        }

        if (is_array($shoppingCartPerTransaksi)) {

            $arrayOnprogressTransaksi = $tmpSrc_01;
            $arrayOnprogressTransaksiKoloms = $shoppingCartPerTransaksi;
        }

        $data = array(
            "mode" => "viewSelectPaymentExternSrc",
            //            "template" => $this->configUi[$jenisTr]["template"],
            "title" => $this->configUi[$targetJenis]["label"],
            "subTitle" => isset($paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel']) ? "select " . $paymentSources[$rawSrcJenis][$srcStep][$srcIndex]['externSrc']['extLabel'] . "(s) listed below" : "",
            "items" => $items,
            "srcLabel" => $srcLabel,
            "itemLabels" => $itemLabels,
            "jenisTr" => $this->jenisTr,
            "referenceAlias" => $referenceAlias,//untuk ganti label 5822->reguler, 5823->pos
            "connectTo" => $connectTo,
            // "this_url" => $this->class,
            //            "dueDate"    =>$dueEmployee,
            // -------------------------
            "arrNotaINV_settlement" => $arrNotaINV_settlement,
            "arrayOnProgressTransaksi" => $arrayOnprogressTransaksi,
            "arrayOnProgressTransaksiKoloms" => $arrayOnprogressTransaksiKoloms,
            "shoppingCartPerTransaksiBtn" => $shoppingCartPerTransaksiBtn,
            "showBtnSettlement" => $settleBtnShow,
            "total_col_span" => $colspan_item,

        );

        $this->load->view("transaksi", $data);


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

    /* -----------------------------------------------
     * pengambil data, dieksekusi di methode viewKepoinItem
     * -----------------------------------------------*/
    public function viewIncompleteStepAntarCabang()
    {

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
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

                $this->load->model("MdlTransaksi");
                $tr2 = new MdlTransaksi();
                $tr2->setFilters(array());

                if (isset($connectTo)) {
                    $tr2->addFilter("jenis_master='$connectTo'");
                    $tr2->addFilter("link_id='0'");
                    $tr2->addFilter("next_step_code>'0'");
                    $tr2->addFilterJoin("sub_step_number>'0'");
                    $tr2->addFilter("gudang_id<>'0'");
                    if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {
                    }
                    else {
                        $tr2->addFilter("transaksi.cabang2_id='" . $this->session->login['cabang_id'] . "'");
                    }
                    // $tmpSrc2 = $tr2->lookupJoined_OLD()->result();
                    $tmpSrc2 = $tr2->lookupJoined();
//                    cekHere($this->db->last_query() . " --- " . count($tmpSrc2));
//                    arrPrint($tmpSrc2);
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
                            $tr12->addFilterJoin("valid_qty>'0'");
                            //                            $trTmpExt = $tr12->lookupAll()->result();
                            $trTmpExt = $tr12->lookupJoined();
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
                                // $trReg->addFilter("param='$param'");
                                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                                $tmpReg = $trReg->lookupDataRegistries()->result();
                                // cekBiru($tmpReg);
                                if (sizeof($tmpReg) > 0) {
                                    foreach ($tmpReg as $key => $regRow) {
                                        // arrPrint($regRow);
                                        foreach ($regRow as $param => $parvalue) {
                                            // cekMerah($param . $regRow->transaksi_id);
                                            // $param = $regRow->param;
                                            // cekBiru($param);
                                            if ($param != "transaksi_id") {
                                                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($parvalue);
                                            }
                                        }
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
//arrPrintPink($arrayOnProgressView);
        return $arrayOnProgressView;
    }

    public function viewKepoinItems()
    {
        $cabang_id = $this->cabangId;
        $jenisTr = $this->jenisTr;
        $shortKepoinLabel = isset($this->configUi[$jenisTr]['shortKepoinLabel']) ? $this->configUi[$jenisTr]['shortKepoinLabel'] : "transaksi belum selesai";
        $kepoinFields = isset($this->configUi[$jenisTr]['shortKepoinFields']) ? $this->configUi[$jenisTr]['shortKepoinFields'] : array();
        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $kepoinFields = $stepHistoryFields = isset($this->configUi[$jenisTr]['shortStepHistoryFields']) ? $this->configUi[$jenisTr]['shortStepHistoryFields'] : array();
        $arrExtHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"]) ? $this->configUi[$jenisTr]["extHistoryFields2"] : array();

        // $masterRefCode =
        $glanceHistoryFields2 = array("nomer" => "receipt no");
        if (isset($this->configUi[$jenisTr]['requestCode'])) {
            $masterRefCode = $this->configUi[$jenisTr]['requestCode']['masterCode'];
            $stateRefCode = $this->configUi[$jenisTr]['requestCode']['stateCode'];
            $stateRefNum = $this->configUi[$jenisTr]['requestCode']['stepNumber'];
            $glanceHistoryFields2 = isset($this->configUi[$masterRefCode]['glanceHistoryFields']) ? $this->configUi[$masterRefCode]['glanceHistoryFields'] : array("nomer" => "receipt no");
        }

        // $kepoinFields['action'] = "action";
        $arrayOnProgressView = array();
        if (strlen($connectTo) > 0) {
            $tmp2 = $this->viewIncompleteStepAntarCabang();
//            arrPrint($tmp2);
            // matiDisini(__LINE__);
            $arrayOnProgressView = $tmp2;
        }

        // arrPrintPink($arrayOnProgressView);
        // arrPrintPink($kepoinFields);
        $data = array(
            "mode" => "viewKepoinItems",
            // "isMobile"             => $isMob,
            // "arrayProgressLabels" => $_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'] == "list" ? $progressFields : $glanceHistoryFields,
            // "arrayProgress2Labels" => $_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'] == "list" ? $progress2Fields : $glanceHistoryFields2,
            // "arrayProgressLabels"  => $progressFields,
            "arrayProgress2Labels" => $glanceHistoryFields2,
            // "arrayOnProgress"      => $arrayOnprogress,
            // "arrayOnProgress2"     => $arrayOnprogress2,
            // "needToClear"          => $needToClear,
            // "reqFormTarget"        => $reqFormTarget,
            // "allowMultiSelect"     => $allowMultiSelect,
            // "overDue"              => $arrOver_due,
            // "clearCartTarget"      => base_url() . $this->modul . "/_shoppingCart/reset/" . $this->jenisTr,
            //
            // "sumFooter" => $sumFooterResult,
            "jenisTr" => $jenisTr,
            "shortKepoinLabel" => $shortKepoinLabel,
            "arrayOnProgressView" => $arrayOnProgressView,
            "connectTo" => $connectTo,
            "kepoinFields" => $kepoinFields,
        );

        $this->load->view("transaksi", $data);
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


    //---------------------------
    public function patchPenerimaanTunai()
    {
        $arrTransaksiIDs = array(
//            "13001",
//            "13003",
//            "13081",
            "13083",
        );


        $this->load->library("FieldCalculator");
        $this->load->model("MdlTransaksi");

        $cal = new FieldCalculator();
        $tr = new MdlTransaksi();
        $tr->addFilter("id in ('" . implode("','", $arrTransaksiIDs) . "')");
        $tmp1 = $tr->lookupAll()->result();
        if (sizeof($tmp1) > 0) {
            $this->jenisTr = $tmp1[0]->jenis_master;
            $insertID = $tmp1[0]->id;
            $insertNum = $tmp1[0]->nomer;
            $cCode = "_TR_" . $this->jenisTr;
            $_SESSION[$cCode] = null;

            $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;
            $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
            $inputLabels = array();
            $inputAuthConfigs = array();

            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in ('" . implode("','", $arrTransaksiIDs) . "')");
            $tmpReg = $trr->lookupDataRegistries()->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($row as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $_SESSION[$cCode][$key_reg] = blobDecode($val_reg);
                        }
                    }
                }
            }
        }
        $_SESSION[$cCode]["main"]["nilai_entry"] = $_SESSION[$cCode]["main"]["nilai_bayar_netto"];


        $this->load->helper("he_value_builder");
        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCore[$this->jenisTr], $this->configUi[$this->jenisTr], $this->configValues[$this->jenisTr]);


//        arrPrintWebs($_SESSION[$cCode]["main"]);
//        mati_disini(__LINE__);

        $this->db->trans_start();

        if (isset($_SESSION[$cCode])) {
            //region pre-processors (item)
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['preProc']['detail']) ? $_SESSION[$cCode]['revert']['preProc']['detail'] : array();
                cekMerah(":: iterator preprocc dari gerbang revert ::");
                arrPrintWebs($iterator);
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['detail'] : array();
            }

            if (sizeof($iterator) > 0) {


                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub-preproc: $comName, initializing values <br>";

                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();

                            //                            $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }


                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
                                            if (!isset($_SESSION[$cCode][$gateName])) {
                                                $_SESSION[$cCode][$gateName] = array();
                                                //                                    cekhijau("building the session: $gateName");
                                            }
                                            else {
                                                //                                    cekhijau("NOT building the session: $gateName");
                                            }

                                            foreach ($paramSpec as $id => $gSpec) {
                                                //										$id=$gSpec['id'];


                                                if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                    $_SESSION[$cCode][$gateName][$id] = array();
                                                }


                                                if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                            $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                        }

                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
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
                fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);


                //region injector gerbang value untuk pembatalan ppv dan selisih
                if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                    $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                    $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                    switch ($jenisTrReference) {
                        case "460":
                            $tempCalculate = array(
                                //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                            );
                            break;
                        default:
                            $tempCalculate = array(
                                "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                            );
                            break;
                    }
                    //                    $tempCalculate = array(
                    //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                    //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                    //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                    //                    );

                    //arrPrintWebs($tempCalculate);
                    foreach ($replace['recalculate'] as $iKey => $gate) {
                        $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                    }

                    cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                }

                //endregion


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            //region pre-processors (master)
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['preProc']['master']) ? $_SESSION[$cCode]['revert']['preProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {

                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();


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
                                $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                $subParams['static'][$key] = $realValue;

                                //                                cekPink2("$comName == $value || $realValue");
                                //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                            }

                            if (!isset($subParams['static']["transaksi_id"])) {
                                //									$subParams['static']["transaksi_id"] = $masterID;
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
// matiHere($tobeExecuted);
                        if ($tobeExecuted) {
                            arrPrint($tmpOutParams);
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            arrPrint($gotParams);
// cekHitam($this->db->last_query());
// matiHEre();
                            // cekbiru("gotparams dari pre-proc $comName");
                            // arrprint($gotParams);

                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    //										$id=$gSpec['id'];

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
                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                cekHere("$id === $key => $label");
                                                if (isset($_SESSION[$cCode]['main'][$key])) {
                                                    $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
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
                        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);


                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            //region processing sub-components, if in single step geser ke CLI

            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            //            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['jurnal']['detail']) ? $_SESSION[$cCode]['revert']['jurnal']['detail'] : array();
                $revertedTarget = $_SESSION[$cCode]['main']['pihakExternID'];
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
            }
            $componentConfig['detail'] = $iterator;
            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                //                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                                cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            echo "sub-component: $comName, initializing values <br>";
                            //                        cekHitam(__LINE__);
                            //                        $tmpOutParams[$cCtr] = array();

                            //                        cekhitam("$comName filterneeded: $filterNeeded");
                            //                        cekhitam("mau mengiterasi $srcGateName");
                            //                        cekhitam("telah mengiterasi $srcGateName");
                            //
                            $subParams = array();
                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                        $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            //arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    //cekHitam("cetak tmpOutParams");

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {

                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                                //                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                            }
                        }
                        echo "sub component: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        //                    arrPrint($tmpOutParams[$cCtr]);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            cekMerah("$comName dieksekusiii");
                            arrPrint($tmpOutParams[$cCtr]);
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    //cekKuning("subcomponents is not set");
                }
            }


            //endregion

            //region processing main components, if in single step

            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['jurnal']['master']) ? $_SESSION[$cCode]['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['master'] : array();
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
                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component # $cCtr: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                            }

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                            //                            cekKuning("LOOP $key diisi dengan $realValue");
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
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
                        $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


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
                }
            }
            else {
                cekKuning("components is not set, no iterator");
            }


            //endregion

            //region processing sub-post-processors, always
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['postProc']['detail']) ? $_SESSION[$cCode]['revert']['postProc']['detail'] : array();
                cekHitam("post procc pakai revert");
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
                cekHitam("post procc pakai config core");
            }
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();
                    if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                        arrPrint($_SESSION[$cCode][$srcGateName]);
                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            //                            $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    cekHitam("gate: $srcGateName, dengan key $id");
                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                }

                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
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
                    if (isset($_SESSION[$cCode][$srcGateName])) {
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

            //region processing main-post-processors, always
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['postProc']['detail']) ? $_SESSION[$cCode]['revert']['postProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>LINE: " . __LINE__;

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
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
                        $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


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


            cekHitam(":::: MULAI JURNAL SETOR ::::");
            cekHitam(":::: MULAI JURNAL SETOR ::::");
            //==========================================================================================================
            $this->load->model("Mdls/MdlCompany");
            $mc = New MdlCompany();
            $mcTmp = $mc->lookupAll()->result();
            $autoJurnalSetor = 0;
            if (sizeof($mcTmp) > 0) {
                $autoJurnalSetor = $mcTmp[0]->setor;
            }
            if ($autoJurnalSetor == 1) {

                //region pre-processors auto (item)
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['detail'] : array();
                if (sizeof($iterator) > 0) {


                    $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                    echo "ITEM NUM LABELS";

                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];

                            echo "sub-preproc: $comName, initializing values <br>";

                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $tmpOutParams[$cCtr] = array();

                                //                            $id = $dSpec['id'];
                                $id = $xid;
                                $subParams = array();

                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }

                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        //									$subParams['static']["transaksi_id"] = $masterID;
                                    }


                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
                                                if (!isset($_SESSION[$cCode][$gateName])) {
                                                    $_SESSION[$cCode][$gateName] = array();
                                                    //                                    cekhijau("building the session: $gateName");
                                                }
                                                else {
                                                    //                                    cekhijau("NOT building the session: $gateName");
                                                }

                                                foreach ($paramSpec as $id => $gSpec) {
                                                    //										$id=$gSpec['id'];


                                                    if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                        $_SESSION[$cCode][$gateName][$id] = array();
                                                    }


                                                    if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                            }

                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                    if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
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
                    if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                        $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                        $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                        switch ($jenisTrReference) {
                            case "460":
                                $tempCalculate = array(
                                    //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                    //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                    //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                    //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                                );
                                break;
                            default:
                                $tempCalculate = array(
                                    "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                    "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                    "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                );
                                break;
                        }
                        //                    $tempCalculate = array(
                        //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                        //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                        //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                        //                    );

                        //arrPrintWebs($tempCalculate);
                        foreach ($replace['recalculate'] as $iKey => $gate) {
                            $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                        }

                        cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                    }

                    //endregion


                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion

                //region pre-processors auto (master)
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {

                    $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();


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
                                    $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    $subParams['static'][$key] = $realValue;

                                    //                                cekPink2("$comName == $value || $realValue");
                                    //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                    //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    cekHere("$id === $key => $label");
                                                    if (isset($_SESSION[$cCode]['main'][$key])) {
                                                        $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
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
                            fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);


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

                //region processing sub-components auto
//            $componentGate['detail'] = array();
//            $componentConfig['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $iterator = isset($this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
//            $componentConfig['detail'] = $iterator;
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                //                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                                cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            echo "sub-component: $comName, initializing values <br>";
                            //                        cekHitam(__LINE__);
                            //                        $tmpOutParams[$cCtr] = array();

                            //                        cekhitam("$comName filterneeded: $filterNeeded");
                            //                        cekhitam("mau mengiterasi $srcGateName");
                            //                        cekhitam("telah mengiterasi $srcGateName");
                            //
                            $subParams = array();
                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                        $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            //arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    //cekHitam("cetak tmpOutParams");

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {

                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                                //                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                            }
                        }
                        echo "sub component: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        //                    arrPrint($tmpOutParams[$cCtr]);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            cekMerah("$comName dieksekusiii");
                            arrPrint($tmpOutParams[$cCtr]);
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    //cekKuning("subcomponents is not set");
                }
                //endregion

                //region processing main components auto
//            $componentGate['master'] = array();
//            $componentConfig['master'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $iterator = isset($this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                        }
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "component # $cCtr: $comName<br>";

                        $dSpec = $_SESSION[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                }

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                                //                            cekKuning("LOOP $key diisi dengan $realValue");
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                            $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                        }

                        if (isset($tComSpec['static2'])) {
                            //cekHere("DISINI OIII");
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
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
                            $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


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
                    }
                }
                else {
                    //cekKuning("components is not set");
                }
                //endregion

                //region processing sub-post-processors auto, always
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['detail'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        cekHitam("gate: $srcGateName, dengan key $id");
                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
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
                                    if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                        $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                    }

                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
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
                        if (isset($_SESSION[$cCode][$srcGateName])) {
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

                //region processing main-post-processors auto, always
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "post-processor: $comName<br>LINE: " . __LINE__;

                        $dSpec = $_SESSION[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($tmpOutParams['static'][$k])) {
                                        $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $_SESSION[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                            $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }
                        if (isset($tComSpec['static2'])) {
                            //cekHere("DISINI OIII");
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
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
                            $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                        }

                        //lgShowError("Ada kesalahan",);
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        // cekBiru("kiriman komponem $comName");
                        // arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekHitam($this->db->last_query());
                    }
                }
                else {

                }
                //endregion
            }
            //==========================================================================================================

            validateAllBalances("-1");
            validateAllBalances("1");

        }


        mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>.... DONE ....</h3>");
    }

    public function clearContent()
    {

        $cCode = $this->cCode;
        $trID = array();
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
        echo "<script>";
        echo "if(top.document.getElementById('undoneList')){";
        echo "$('#undoneList').load('" . MODUL_PATH . "Transaksi/selectPaymentExternSrc/" . $this->jenisTr . "?selID=0')";
        echo "}";
        echo "</script>";
    }
}
