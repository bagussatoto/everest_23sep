<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
class _processSelectNota extends CI_Controller
{
    private $jenisTr;
    private $validateJenisTr = array();

    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        if (!isset($_SESSION[$cCode]['main']['refIDs'])) {
            $_SESSION[$cCode]['main']['refIDs'] = array();
        }
        if (!isset($_SESSION[$cCode]['main']['refs'])) {
            $_SESSION[$cCode]['main']['refs'] = "";
        }
        if (!isset($_SESSION[$cCode]['main']['refs_intext'])) {
            $_SESSION[$cCode]['main']['refs_intext'] = "";
        }

        $this->validateJenisTr = array(
            "771", "1771", //"475", "476", "477",
        );
    }

    public function select()
    {
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        $id = $_GET['transaksi_id'];

        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeComNameDetails']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['isPaymentRadioSelect']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['label']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['label'] : "";
        $exchangeValidate = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['exchangeValidate']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['exchangeValidate'] : array();


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
                $mainValueInjector = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['mainValueInjectors']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['mainValueInjectors'] : array();
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
        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss'] : array();

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

                    $mainValueInjector = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['mainValueInjectors']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['mainValueInjectors'] : array();
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

        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
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

        if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
            foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
            }
        }


        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
        $_SESSION[$cCode]['main']['cashMethode'] = "none";


        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo';";
        echo "</script>";

    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();


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

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart')";
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

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

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
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();

        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $priceMainConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedMainPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedMainPrice'] : array();

        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['validateMeasurement'][1]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['validateMeasurement'][1] : array();


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


                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

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


        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }
}