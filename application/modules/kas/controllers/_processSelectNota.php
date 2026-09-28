<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";

class _processSelectNota extends Modul_Controller
{
    // private $jenisTr;
    private $validateJenisTr = array();

    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION[$this->cCode]['main']['refIDs'])) {
            $_SESSION[$this->cCode]['main']['refIDs'] = array();
        }
        if (!isset($_SESSION[$this->cCode]['main']['refs'])) {
            $_SESSION[$this->cCode]['main']['refs'] = "";
        }
        if (!isset($_SESSION[$this->cCode]['main']['refs_intext'])) {
            $_SESSION[$this->cCode]['main']['refs_intext'] = "";
        }

        $this->validateJenisTr = array(
            "771", "1771", //"475", "476", "477",
        );
    }

    public function select_OLD()
    {
        $cCode = $this->cCode;
        $id = $_GET['transaksi_id'];

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";
        $exchangeValidate = isset($this->configUi[$this->jenisTr]['exchangeValidate']) ? $this->configUi[$this->jenisTr]['exchangeValidate'] : array();


        if (in_array($this->jenisTr, $this->validateJenisTr)) {
            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
            }
        }


        if ($isRadioSelect) {
            $detailResetList = array(
                "items",
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

        );

        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        foreach ($_GET as $get_key => $get_val) {
            $new_get_key = str_replace("amp;", "", $get_key);
            $row[$new_get_key] = $get_val;
        }

        if (sizeof($exchangeValidate) > 0) {
            if (isset($exchangeValidate['enabled']) && ($exchangeValidate['enabled'] == true)) {
                $valasID = $row['valas_id'];
                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $itemSpec) {
                        if ($valasID != $itemSpec['valas_id']) {
                            $label_err = $exchangeValidate['label'];
                            mati_disini($label_err);
                        }
                    }
                }
            }
        }


        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['transaksi_id'];
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();

        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
            $tmp = array(
                "handler" => $this->uri->segment(1),
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
            );
            foreach ($fieldSrcs as $key => $src) {
                $tmp[$key] = makeValue($src, $row, $row, 0);
            }

            if ($subAmountConfig != null) {
                $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                $tmp["subtotal"] = $subtotal;
            }
            else {
                $tmp["subtotal"] = 0;
            }
            if (sizeof($componentAssConfig) > 0) {
                $this->load->model($componentAssConfig["model"]);
                $tr = new $componentAssConfig["model"]();
                $tmpReg = $tr->lookupRegistriesByMasterID($id)->result();
                $itemsRegistries = array();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $row) {
                        switch ($row->param) {
                            case "items"://
                                $itemsRegistries = unserialize(base64_decode($row->values));
                                break;
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


        }
        else {
            cekMerah("TIDAK akan memasukkan ITEMS");
        }

        $_SESSION[$cCode]['main']['refIDs'][$id] = $id;

        switch ($_GET['state']) {
            case "true":
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $_SESSION[$cCode]['items'][$id] = $tmp;
                }
                if (sizeof($componentAssConfig) > 0) {
                    if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                        $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                    }
                }
                break;
            case "false":
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

                break;
        }

        if ((isset($_SESSION[$cCode]['items2'])) && (sizeof($_SESSION[$cCode]['items2']) > 0)) {
            cekBiru("bulding summary item_result...");
            $_SESSION[$cCode]['items2_sum'] = array();
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $eSpec) {
                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = 0;
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();


                    }

                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] += $eSpec['harga'];
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

        if ((isset($_SESSION[$cCode]['items2_sum'])) && (sizeof($_SESSION[$cCode]['items2_sum']) > 0)) {
            foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
            }
        }


        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
        $_SESSION[$cCode]['main']['cashMethode'] = "none";


        // region menuju ValueGate untuk rebuild gerbang nilai
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        // arrPrint($this->configUiJenis);
        // $configUiJenis = $this->configUi[$this->jenisTr];
        // $configCoreJenis = $this->configCore[$this->jenisTr];
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        // endregion menuju ValueGate untuk rebuild gerbang nilai

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();


        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
            cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['items'][$id])) {
                cekBiru("ada barang, cek lokernya");
                $this->db->trans_start();

                $mdlName = $lockerConfig['mdlName'];
                $this->load->model("Mdls/" . $mdlName);

                $c = new $mdlName();
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id']);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active");
                $where = array(
                    "id" => $array_active_sebelumnya['id'],
                );
                $data_active = array(
                    "jumlah" => $array_active_sebelumnya['jumlah'] + $array_hold_sebelumnya['jumlah'],
                );
                $c->updateData($where, $data_active);


                $this->db->trans_complete() or die("Gagal bro");
            }
            else {
                cekBiru("TIDAK ada barang, ga jadi cek loker");
            }
        }
        else {
            cekBiru("TIDAK melibatkan session");
        }

//        die();
        if (isset($_SESSION[$cCode]['items'][$id])) {
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
        }
//        if (sizeof($_SESSION[$cCode]['items']) < 1) {
//            $_SESSION[$cCode] = null;
//            unset($_SESSION[$cCode]);
//        }

        if (isset($_SESSION[$cCode]['main']['refIDs'][$id])) {
            $_SESSION[$cCode]['main']['refIDs'][$id] = null;
            unset($_SESSION[$cCode]['main']['refIDs'][$id]);
        }


        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);


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
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function updateValues()
    {
        $cCode = "_TR_" . $this->jenisTr;
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }

    //------------------------------------------------------
    public function selectId()
    {

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal");
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal");
        }


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();

        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceMainConfig = isset($this->configUi[$this->jenisTr]['selectedMainPrice']) ? $this->configUi[$this->jenisTr]['selectedMainPrice'] : array();

        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();


        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $valValidate_items = array();
                if (sizeof($validateMeasurement) > 0) {
                    $iValidate = 0;
                    foreach ($validateMeasurement as $keyVal => $validateKol) {
                        $valValidate = $row->$keyVal;
                        if ($valValidate == 0) {
                            $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                            $alerts = array(
                                "type" => "warning",
                                "title" => strtoupper("Data ukuran produk belum lengkap "),
                                "html" => $msg,
                            );
                            echo swalAlert($alerts);
                            die($msg);
                        }
                    }

                }


                if (sizeof($valValidate_items) > 0) {
//                    arrPrint($valValidate_items);
                    $msg = "Data pendukung produk belum lengkap<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr>$jml_now $satuan stock available";
                    $alerts = array(
                        "type" => "warning",
                        "title" => strtoupper($kode),
                        "html" => $msg,
                    );
                    echo swalAlert($alerts);
                    die($msg);
                }
                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",

                    );


                    foreach ($fieldSrcs as $key => $src) {
                        if (is_array($src) && sizeof($src) > 0) {
                            foreach ($src as $srcSpec) {
                                if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                    cekBiru("ambil gerbang key -> $srcSpec");
                                    $tmp[$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : 0);
                                }
                            }
                        }
                        else {
                            $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
//                            cekHere("hasilnya $key -> " . $tmp[$key]);
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }
                        }
                    }


                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }

                        }


                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
                    }
                }
            }
        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }


        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

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
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }


    public function select()
    {
        $cCode = $this->cCode;
        $trID = $transaksi_id_selected = $id = $_GET['transaksi_id'];

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";
        $exchangeValidate = isset($this->configUi[$this->jenisTr]['exchangeValidate']) ? $this->configUi[$this->jenisTr]['exchangeValidate'] : array();
        $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();


        if (in_array($this->jenisTr, $this->validateJenisTr)) {
            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
            }
        }
        //---------------------------------------
        if ((sizeof($selectorNotaLocker) > 0)) {
            if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $lt = New MdlLockerTransaksi();
                $lt->addFilter("transaksi_id='$trID'");
                $lt->addFilter("state='hold'");
                $lt->addFilter("jumlah='1'");
                $ltTmp = $lt->lookupAll()->result();
                // showLast_query("pink");
                // cekHere($trID);
                // arrPrintHijau($ltTmp);
                $allowedActionByHold = NULL;
                $isAllowedByLiveLock = false;
                if (sizeof($ltTmp) > 0) {
                    foreach ($ltTmp as $ltSpec) {
                        if ($ltSpec->oleh_id == my_id()) {
                            $isAllowedByLiveLock = true;
                            continue;
                        }

                        $this->load->model("Mdls/MdlEmployee");
                        $empObj = new MdlEmployee();
                        $empObj->setFilters(array());
                        $empQuery = $empObj->lookupByCondition(array("id" => $ltSpec->oleh_id))->result();
                        if (sizeof($empQuery) > 0) {
                            $holderName = $ltSpec->oleh_nama;
                            if (!empty($empQuery[0]->nama)) {
                                $holderName = $empQuery[0]->nama;
                            }
                            if (isset($empQuery[0]->status_login) && $empQuery[0]->status_login == 1 && !empty($empQuery[0]->phpsessid) && !empty($empQuery[0]->last_dtime_active)) {
                                $inactiveSeconds = timeDiff($empQuery[0]->last_dtime_active, dtimeNow());
                                $inactiveMinutes = floor($inactiveSeconds / 60);
                                if ($inactiveMinutes < 5) {
                                    $allowedActionByHold = $holderName;
                                    break;
                                }
                            }
                        }
                    }
                }
                if ($allowedActionByHold != NULL) {
                    $msg = "Nota yang anda pilih sedang ditindaklanjuti oleh $allowedActionByHold. Silahkan pilih nota lainnya. code: " . __LINE__;
                    mati_disini($msg);
                }
            }
        }
        //---------------------------------------


        if ($isRadioSelect) {
            $detailResetList = array(
                "items",
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
            "extern_jenis" => isset($_GET['extern_jenis']) && sizeof($_GET['extern_jenis']) > 0 ? $_GET['extern_jenis'] : "",
            "cash_account_pym" => isset($_GET['cash_account']) && sizeof($_GET['cash_account']) > 0 ? $_GET['cash_account'] : "",

        );

        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        foreach ($_GET as $get_key => $get_val) {
            $new_get_key = str_replace("amp;", "", $get_key);
            $row[$new_get_key] = $get_val;
        }

        if (sizeof($exchangeValidate) > 0) {
            if (isset($exchangeValidate['enabled']) && ($exchangeValidate['enabled'] == true)) {
                $valasID = $row['valas_id'];
                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $itemSpec) {
                        if ($valasID != $itemSpec['valas_id']) {
                            $label_err = $exchangeValidate['label'];
                            mati_disini($label_err);
                        }
                    }
                }
            }
        }


        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['transaksi_id'];
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $shoppingCartLoadReference = isset($this->configUi[$this->jenisTr]['shoppingCartLoadReference']) ? $this->configUi[$this->jenisTr]['shoppingCartLoadReference'] : false;
        $shoppingCartDetail = isset($this->configUi[$this->jenisTr]['shoppingCartDetail']) ? $this->configUi[$this->jenisTr]['shoppingCartDetail'] : array();

        if ($shoppingCartLoadReference == true) {
            $this->load->model("MdlTransaksi");
            $tr = New MdlTransaksi();
            $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    foreach ($regRow as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $$key_reg = blobDecode($val_reg);
                        }
                    }
                }

                if (isset($main)) {
                    foreach ($main as $key => $val) {
                        if (!array_key_exists($key, $_SESSION[$cCode]['main'])) {
                            $_SESSION[$cCode]['main'][$key] = $val;
                        }
                    }
                }
                if (isset($items)) {
                    $_SESSION[$cCode]['items'] = $items;
                }


            }


            if (!array_key_exists($id, $_SESSION[$cCode]['items4_sum'])) {
                $tmp = array(
                    "handler" => $this->uri->segment(1),
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
                    "extern_id" => isset($_GET['extern_id']) ? $_GET['extern_id'] : 0,
                    "extern_nama" => isset($_GET['extern_nama']) ? $_GET['extern_nama'] : 0,
                    "tagihan" => isset($_GET['tagihan']) ? $_GET['tagihan'] : 0,
                    "sisa" => isset($_GET['sisa']) ? $_GET['sisa'] : 0,
                    "nama" => isset($_GET['nomer']) ? $_GET['nomer'] : 0,
                    "transaksi_id" => isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 0,
                );
                foreach ($fieldSrcs as $key => $src) {
                    $tmp[$key] = makeValue($src, $row, $row, 0);
                }

                if ($subAmountConfig != null) {
                    $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                    $tmp["subtotal"] = $subtotal;
                }
                else {
                    $tmp["subtotal"] = 0;
                }
                $_SESSION[$cCode]["items4_sum"][$id] = $tmp;

            }

        }
        else {

            if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                $tmp = array(
                    "handler" => $this->uri->segment(1),
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
                );
                foreach ($fieldSrcs as $key => $src) {
                    $tmp[$key] = makeValue($src, $row, $row, 0);
                }

                if ($subAmountConfig != null) {
                    $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                    $tmp["subtotal"] = $subtotal;
                }
                else {
                    $tmp["subtotal"] = 0;
                }
                if (sizeof($componentAssConfig) > 0) {
                    $this->load->model($componentAssConfig["model"]);
                    $tr = new $componentAssConfig["model"]();
                    $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
                    $itemsRegistries = array();
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $row) {
                            switch ($row->param) {
                                case "items"://
                                    $itemsRegistries = unserialize(base64_decode($row->values));
                                    break;
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


            }
            else {
                cekMerah("TIDAK akan memasukkan ITEMS");
            }

            $_SESSION[$cCode]['main']['refIDs'][$id] = $id;

            switch ($_GET['state']) {
                case "true":

                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                        $_SESSION[$cCode]['items'][$id] = $tmp;
                    }
                    if (sizeof($componentAssConfig) > 0) {
                        if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                            $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                        }
                    }

                    if (sizeof($shoppingCartDetail) > 0) {
                        if (isset($shoppingCartDetail["targetGate"]) && ($shoppingCartDetail["targetGate"] != NULL)) {
                            $tagetGate = $shoppingCartDetail["targetGate"];
                            $this->load->model("MdlTransaksi");
                            $tr = New MdlTransaksi();
                            $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
                            if (sizeof($tmpReg) > 0) {
                                foreach ($tmpReg as $regRow) {
                                    foreach ($regRow as $key_reg => $val_reg) {
                                        if ($key_reg != "transaksi_id") {
                                            $$key_reg = blobDecode($val_reg);
                                        }
                                    }
                                }

                                if (isset($main)) {
                                    $_SESSION[$cCode][$tagetGate][$id]['main'] = $main;

                                }
                                if (isset($items)) {
                                    $_SESSION[$cCode][$tagetGate][$id]['items'] = $items;
                                }


                            }
                        }
                    }


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
                                    $this->load->model("Mdls/MdlEmployee");
                                    $empObj = new MdlEmployee();
                                    $byUpdateHold = array();
                                    $totalUpdateHold = 0;
                                    $insertHold = true;
                                    foreach ($ltTmp as $ltSpec) {
                                        if (($ltSpec->state == "hold") && ($ltSpec->jumlah == "1")) {
                                            if ($ltSpec->oleh_id != my_id()) {
                                                $empObj->setFilters(array());
                                                $empQuery = $empObj->lookupByCondition(array("id" => $ltSpec->oleh_id))->result();
                                                $isActiveOwner = false;
                                                if (sizeof($empQuery) > 0) {
                                                    if (isset($empQuery[0]->status_login) && $empQuery[0]->status_login == 1 && !empty($empQuery[0]->phpsessid) && !empty($empQuery[0]->last_dtime_active)) {
                                                        $inactiveSeconds = timeDiff($empQuery[0]->last_dtime_active, dtimeNow());
                                                        $inactiveMinutes = floor($inactiveSeconds / 60);
                                                        if ($inactiveMinutes < 5) {
                                                            $isActiveOwner = true;
                                                        }
                                                    }
                                                }
                                                if ($isActiveOwner == false) {
                                                    $lt->setFilters(array());
                                                    $lt->updateData(
                                                        array(
                                                            "id" => $ltSpec->id,
                                                        ),
                                                        array(
                                                            "jumlah" => "0",
                                                        )
                                                    );
                                                    continue;
                                                }
                                            }
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


                    if (sizeof($shoppingCartDetail) > 0) {
                        if (isset($shoppingCartDetail["targetGate"]) && ($shoppingCartDetail["targetGate"] != NULL)) {
                            $tagetGate = $shoppingCartDetail["targetGate"];
                            if (isset($_SESSION[$cCode][$tagetGate][$id])) {
                                $_SESSION[$cCode][$tagetGate][$id] = null;
                                unset($_SESSION[$cCode][$tagetGate][$id]);
                            }
                        }
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

            if ((isset($_SESSION[$cCode]['items2'])) && (sizeof($_SESSION[$cCode]['items2']) > 0)) {
                cekBiru("bulding summary item_result...");
                $_SESSION[$cCode]['items2_sum'] = array();
                foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                    foreach ($pSpec as $eSpec) {
                        if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = 0;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();


                        }

                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] += $eSpec['harga'];
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

            if ((isset($_SESSION[$cCode]['items2_sum'])) && (sizeof($_SESSION[$cCode]['items2_sum']) > 0)) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                    $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
                }
            }

            $_SESSION[$cCode]["items4_sum"] = $_SESSION[$cCode]['items'];

            $default_nilai_entry = 0;
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $iiSpec) {
                    $default_nilai_entry += $iiSpec["sisa"];
                }
            }
        }


        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
        $_SESSION[$cCode]['main']['cashMethode'] = "none";
//        $_SESSION[$cCode]['main']['nilai_entry'] = round($_SESSION[$cCode]['main']['nilai_cash']);
        $_SESSION[$cCode]['main']['nilai_entry'] = round($default_nilai_entry);
        $_SESSION[$cCode]['main']['place2ID'] = "-1";
        $_SESSION[$cCode]['main']['place2Name'] = "Pusat";
        //reference untuk trigger pembeda antara penjualan produk reguler vs jasa untuk memunculkan kolom pph23
        $_SESSION[$cCode]["main"]['reference_jenis'] = $row["jenis_master"];


        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        // region menuju ValueGate untuk rebuild gerbang nilai
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        // endregion menuju ValueGate untuk rebuild gerbang nilai


        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }

    public function selectTerimaUang()
    {
        $cCode = $this->cCode;
        $trID = $transaksi_id_selected = $id = $_GET['transaksi_id'];
        $modePym = $_GET['md'];

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";
        $exchangeValidate = isset($this->configUi[$this->jenisTr]['exchangeValidate']) ? $this->configUi[$this->jenisTr]['exchangeValidate'] : array();
        $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();
        $itemsBiayaCashback = isset($this->configUi[$this->jenisTr]['itemsBiayaCashback']) ? $this->configUi[$this->jenisTr]['itemsBiayaCashback'] : array();


        if (in_array($this->jenisTr, $this->validateJenisTr)) {
            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
            }
        }
        //---------------------------------------
        if ((sizeof($selectorNotaLocker) > 0)) {
            if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $lt = New MdlLockerTransaksi();
                $lt->addFilter("transaksi_id='$trID'");
                $lt->addFilter("state='hold'");
                $lt->addFilter("jumlah='1'");
                $ltTmp = $lt->lookupAll()->result();
                // showLast_query("pink");
                // cekHere($trID);
                // arrPrintHijau($ltTmp);
                $allowedActionByHold = NULL;
                $isAllowedByLiveLock = false;
                if (sizeof($ltTmp) > 0) {
                    foreach ($ltTmp as $ltSpec) {
                        if ($ltSpec->oleh_id == my_id()) {
                            $isAllowedByLiveLock = true;
                            continue;
                        }

                        $this->load->model("Mdls/MdlEmployee");
                        $empObj = new MdlEmployee();
                        $empObj->setFilters(array());
                        $empQuery = $empObj->lookupByCondition(array("id" => $ltSpec->oleh_id))->result();
                        if (sizeof($empQuery) > 0) {
                            $holderName = $ltSpec->oleh_nama;
                            if (!empty($empQuery[0]->nama)) {
                                $holderName = $empQuery[0]->nama;
                            }
                            if (isset($empQuery[0]->status_login) && $empQuery[0]->status_login == 1 && !empty($empQuery[0]->phpsessid) && !empty($empQuery[0]->last_dtime_active)) {
                                $inactiveSeconds = timeDiff($empQuery[0]->last_dtime_active, dtimeNow());
                                $inactiveMinutes = floor($inactiveSeconds / 60);
                                if ($inactiveMinutes < 5) {
                                    $allowedActionByHold = $holderName;
                                    break;
                                }
                            }
                        }
                    }
                }
                if ($allowedActionByHold != NULL) {
                    $msg = "Nota yang anda pilih sedang ditindaklanjuti oleh $allowedActionByHold. Silahkan pilih nota lainnya. code: " . __LINE__;
                    mati_disini($msg);
                }
            }
        }
        //---------------------------------------


        if ($isRadioSelect) {
            $detailResetList = array(
                "items",
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
            "extern_jenis" => isset($_GET['extern_jenis']) && sizeof($_GET['extern_jenis']) > 0 ? $_GET['extern_jenis'] : "",
            "cash_account_pym" => isset($_GET['cash_account']) && sizeof($_GET['cash_account']) > 0 ? $_GET['cash_account'] : "",

        );

        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        foreach ($_GET as $get_key => $get_val) {
            $new_get_key = str_replace("amp;", "", $get_key);
            $row[$new_get_key] = $get_val;
        }

        if (sizeof($exchangeValidate) > 0) {
            if (isset($exchangeValidate['enabled']) && ($exchangeValidate['enabled'] == true)) {
                $valasID = $row['valas_id'];
                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $itemSpec) {
                        if ($valasID != $itemSpec['valas_id']) {
                            $label_err = $exchangeValidate['label'];
                            mati_disini($label_err);
                        }
                    }
                }
            }
        }


        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['transaksi_id'];
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $fieldSrcsPym = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrcPym']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrcPym'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $shoppingCartLoadReference = isset($this->configUi[$this->jenisTr]['shoppingCartLoadReference']) ? $this->configUi[$this->jenisTr]['shoppingCartLoadReference'] : false;
        $shoppingCartDetail = isset($this->configUi[$this->jenisTr]['shoppingCartDetail']) ? $this->configUi[$this->jenisTr]['shoppingCartDetail'] : array();

        if ($shoppingCartLoadReference == true) {
            $this->load->model("MdlTransaksi");
            $tr = New MdlTransaksi();
            $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    foreach ($regRow as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $$key_reg = blobDecode($val_reg);
                        }
                    }
                }

                if (isset($main)) {
                    foreach ($main as $key => $val) {
                        if (!array_key_exists($key, $_SESSION[$cCode]['main'])) {
                            $_SESSION[$cCode]['main'][$key] = $val;
                        }
                    }
                }
                if (isset($items)) {
                    $_SESSION[$cCode]['items'] = $items;
                }


            }


            if (!array_key_exists($id, $_SESSION[$cCode]['items4_sum'])) {
                $tmp = array(
                    "handler" => $this->uri->segment(1),
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
                    "extern_id" => isset($_GET['extern_id']) ? $_GET['extern_id'] : 0,
                    "extern_nama" => isset($_GET['extern_nama']) ? $_GET['extern_nama'] : 0,
                    "tagihan" => isset($_GET['tagihan']) ? $_GET['tagihan'] : 0,
                    "sisa" => isset($_GET['sisa']) ? $_GET['sisa'] : 0,
                    "nama" => isset($_GET['nomer']) ? $_GET['nomer'] : 0,
                    "transaksi_id" => isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 0,
                );
                foreach ($fieldSrcs as $key => $src) {
                    $tmp[$key] = makeValue($src, $row, $row, 0);
                }

                if ($subAmountConfig != null) {
                    $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                    $tmp["subtotal"] = $subtotal;
                }
                else {
                    $tmp["subtotal"] = 0;
                }
                $_SESSION[$cCode]["items4_sum"][$id] = $tmp;

            }

        }
        else {

            if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                $tmp = array(
                    "handler" => $this->uri->segment(1),
                    "id" => $id,
                    "refID" => $id,
                    "jml" => 1,
                    "harga" => isset($_GET['sisa']) ? $_GET['sisa'] : 0,
                    "piutang_pph" => isset($_GET['sisa']) ? $_GET['sisa'] : 0,
                    "satuan" => "-",
                    "subtotal" => 0,
                    "jenis_source" => isset($_GET['target_jenis']) ? $_GET['target_jenis'] : 0,
                    "valas_id" => isset($_GET['valas_id']) ? $_GET['valas_id'] : 0,
                    "valas_nama" => isset($_GET['valas_nama']) ? $_GET['valas_nama'] : 0,
                    "valas_nilai" => isset($_GET['valas_nilai']) ? $_GET['valas_nilai'] : 0,
                    //----
                    "pihakMainID" => isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 0,
                    "pihakMainName" => isset($_GET['nomer']) ? $_GET['nomer'] : 0,
                    "biayaDetails" => isset($_GET['biaya_rekening_id']) ? $_GET['biaya_rekening_id'] : 0,
                    "biayaDetails__label" => isset($_GET['biaya_rekening_id_label']) ? $_GET['biaya_rekening_id_label'] : 0,
                    "biayaDetails__nama" => isset($_GET['biaya_rekening_id_label']) ? $_GET['biaya_rekening_id_label'] : 0,
                    "reference_dtime" => isset($_GET['reference_dtime']) ? $_GET['reference_dtime'] : 0,
                    "pihakMainName" => isset($_GET['pihakMainName']) ? $_GET['pihakMainName'] : 0,
                    "reference_oleh_nama" => isset($_GET['reference_oleh_nama']) ? $_GET['reference_oleh_nama'] : 0,

                    "extern_nilai2" => isset($_GET['extern_nilai2']) ? $_GET['extern_nilai2'] : 0,
                    "extern_nilai5" => isset($_GET['extern_nilai5']) ? $_GET['extern_nilai5'] : 0,
                    //----
                );

                if(isset($fieldSrcs) && (sizeof($fieldSrcs)>0)){

                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $row, $row, 0);
                    }
                }
                if(isset($fieldSrcsPym) && (sizeof($fieldSrcsPym)>0)){

                    foreach ($fieldSrcsPym as $key => $src) {
                        if(!isset($tmp[$key])){
                            $tmp[$key] = makeValue($src, $row, $row, 0);
                        }
                    }
                }

                $key_add = $itemsBiayaCashback[$_GET['biaya_rekening_id']];
                $tmp[$key_add] = isset($_GET['sisa']) ? $_GET['sisa'] : 0;

                if ($subAmountConfig != null) {
                    $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                    $tmp["subtotal"] = $subtotal;
                }
                else {
                    $tmp["subtotal"] = 0;
                }
                if (sizeof($componentAssConfig) > 0) {
                    $this->load->model($componentAssConfig["model"]);
                    $tr = new $componentAssConfig["model"]();
                    $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
                    $itemsRegistries = array();
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $row) {
                            switch ($row->param) {
                                case "items"://
                                    $itemsRegistries = unserialize(base64_decode($row->values));
                                    break;
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


            }
            else {
                cekMerah("TIDAK akan memasukkan ITEMS");
            }

            $_SESSION[$cCode]['main']['refIDs'][$id] = $id;

            switch ($_GET['state']) {
                case "true":

                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                        $_SESSION[$cCode]['items'][$id] = $tmp;
                    }
                    if (sizeof($componentAssConfig) > 0) {
                        if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                            $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                        }
                    }

                    if (sizeof($shoppingCartDetail) > 0) {
                        if (isset($shoppingCartDetail["targetGate"]) && ($shoppingCartDetail["targetGate"] != NULL)) {
                            $tagetGate = $shoppingCartDetail["targetGate"];
                            $this->load->model("MdlTransaksi");
                            $tr = New MdlTransaksi();
                            $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
                            if (sizeof($tmpReg) > 0) {
                                foreach ($tmpReg as $regRow) {
                                    foreach ($regRow as $key_reg => $val_reg) {
                                        if ($key_reg != "transaksi_id") {
                                            $$key_reg = blobDecode($val_reg);
                                        }
                                    }
                                }

                                if (isset($main)) {
                                    $_SESSION[$cCode][$tagetGate][$id]['main'] = $main;

                                }
                                if (isset($items)) {
                                    $_SESSION[$cCode][$tagetGate][$id]['items'] = $items;
                                }


                            }
                        }
                    }


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
                                    $this->load->model("Mdls/MdlEmployee");
                                    $empObj = new MdlEmployee();
                                    $byUpdateHold = array();
                                    $totalUpdateHold = 0;
                                    $insertHold = true;
                                    foreach ($ltTmp as $ltSpec) {
                                        if (($ltSpec->state == "hold") && ($ltSpec->jumlah == "1")) {
                                            if ($ltSpec->oleh_id != my_id()) {
                                                $empObj->setFilters(array());
                                                $empQuery = $empObj->lookupByCondition(array("id" => $ltSpec->oleh_id))->result();
                                                $isActiveOwner = false;
                                                if (sizeof($empQuery) > 0) {
                                                    if (isset($empQuery[0]->status_login) && $empQuery[0]->status_login == 1 && !empty($empQuery[0]->phpsessid) && !empty($empQuery[0]->last_dtime_active)) {
                                                        $inactiveSeconds = timeDiff($empQuery[0]->last_dtime_active, dtimeNow());
                                                        $inactiveMinutes = floor($inactiveSeconds / 60);
                                                        if ($inactiveMinutes < 5) {
                                                            $isActiveOwner = true;
                                                        }
                                                    }
                                                }
                                                if ($isActiveOwner == false) {
                                                    $lt->setFilters(array());
                                                    $lt->updateData(
                                                        array(
                                                            "id" => $ltSpec->id,
                                                        ),
                                                        array(
                                                            "jumlah" => "0",
                                                        )
                                                    );
                                                    continue;
                                                }
                                            }
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


                    if (sizeof($shoppingCartDetail) > 0) {
                        if (isset($shoppingCartDetail["targetGate"]) && ($shoppingCartDetail["targetGate"] != NULL)) {
                            $tagetGate = $shoppingCartDetail["targetGate"];
                            if (isset($_SESSION[$cCode][$tagetGate][$id])) {
                                $_SESSION[$cCode][$tagetGate][$id] = null;
                                unset($_SESSION[$cCode][$tagetGate][$id]);
                            }
                        }
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

            if ((isset($_SESSION[$cCode]['items2'])) && (sizeof($_SESSION[$cCode]['items2']) > 0)) {
                cekBiru("bulding summary item_result...");
                $_SESSION[$cCode]['items2_sum'] = array();
                foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                    foreach ($pSpec as $eSpec) {
                        if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = 0;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();


                        }

                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] += $eSpec['harga'];
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

            if ((isset($_SESSION[$cCode]['items2_sum'])) && (sizeof($_SESSION[$cCode]['items2_sum']) > 0)) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                    $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
                }
            }

            $_SESSION[$cCode]["items4_sum"] = $_SESSION[$cCode]['items'];

            $default_nilai_entry = 0;
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $iiSpec) {
                    $default_nilai_entry += $iiSpec["sisa"];
                }
            }
        }

        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
        $_SESSION[$cCode]['main']['cashMethode'] = "none";
//        $_SESSION[$cCode]['main']['nilai_entry'] = round($_SESSION[$cCode]['main']['nilai_cash']);
        $_SESSION[$cCode]['main']['nilai_entry'] = round($default_nilai_entry);
        $_SESSION[$cCode]['main']['place2ID'] = "-1";
        $_SESSION[$cCode]['main']['place2Name'] = "Pusat";
        //reference untuk trigger pembeda antara penjualan produk reguler vs jasa untuk memunculkan kolom pph23
        $_SESSION[$cCode]["main"]['reference_jenis'] = $row["jenis_master"];

        $_SESSION[$cCode]["main"]["pihakMainID"] = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 0;
        $_SESSION[$cCode]["main"]["pihakMainName"] = isset($_GET['nomer']) ? $_GET['nomer'] : 0;
        $_SESSION[$cCode]["main"]["biayaDetails"] = isset($_GET['biaya_rekening_id']) ? $_GET['biaya_rekening_id'] : 0;
        $_SESSION[$cCode]["main"]["biayaDetails__label"] = isset($_GET['biaya_rekening_id_label']) ? $_GET['biaya_rekening_id_label'] : 0;
        $_SESSION[$cCode]["main"]["biayaDetails__nama"] = isset($_GET['biaya_rekening_id_label']) ? $_GET['biaya_rekening_id_label'] : 0;
        $_SESSION[$cCode]["main"]["harga_original"] = isset($_GET['extern_nilai2']) ? $_GET['extern_nilai2'] : 0;
        $_SESSION[$cCode]["main"]["nilai_pph_original"] = isset($_GET['sisa']) ? $_GET['sisa'] : 0;
        $_SESSION[$cCode]["main"]["nilai_kas_cn"] = isset($_GET['extern_nilai5']) ? $_GET['extern_nilai5'] : 0;
        $_SESSION[$cCode]["main"]["modePymSrc"] = isset($_GET['md']) ? $_GET['md'] : "";
        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        // region menuju ValueGate untuk rebuild gerbang nilai
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        // endregion menuju ValueGate untuk rebuild gerbang nilai


        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }


}
