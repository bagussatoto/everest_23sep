<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/4/2018
 * Time: 11:30 AM
 */
class ValueGate extends CI_Controller
{
    private $jenisTr;
    private $jenisTrName;
    private $valueGateConfig;
    private $tableInConfig;
    private $tableInConfig_static;
    private $valueBuilderConfig;

    public function __construct()
    {
        parent::__construct();


        $this->jenisTr = $this->uri->segment(3);
        $this->jenisTrName = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'];
        $this->valueGateConfig = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['valueGates']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['valueGates'] : array();
        $this->tableInConfig = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn_static'] : array();
        $this->valueBuilderConfig = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['valueBuilders']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['valueBuilders'] : array();
        $cCode = "_TR_" . $this->jenisTr;
        if (!isset($_SESSION[$cCode])) {

        }
    }

    public function buildValues()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['selID']) ? $_GET['selID'] : 0;

        $stepNum = $this->uri->segment(4) > 0 ? $this->uri->segment(4) : 1;

        //<editor-fold desc="initial values">
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
        $initDetail = array(
            "olehID" => $this->session->login['id'],
            "olehName" => $this->session->login['nama'],
            "placeID" => $this->session->login['cabang_id'],
            "placeName" => $this->session->login['cabang_nama'],
            "cabangName" => $this->session->login['cabang_nama'],
            "gudangID" => $this->session->login['gudang_id'],
            "gudangName" => $this->session->login['gudang_nama'],
            "cabangID" => $this->session->login['cabang_id'],
            "jenisTr" => $this->jenisTr,
            "stepNumber" => $stepNum,
            "stepCode" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
            "dtime" => date("Y-m-d H:i:s"),
        );

        foreach ($initMaster as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
        }
//        if (isset($_SESSION[$cCode]['items'])) {
//
//            if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                    foreach ($initDetail as $key => $val) {
//                        $_SESSION[$cCode]['items'][$id][$key] = $val;
//                    }
//                }
//            }
//        }

        //</editor-fold>


        if (!isset($_SESSION[$cCode]['tableIn_master'])) {
            $_SESSION[$cCode]['tableIn_master'] = array();
        }
        $_SESSION[$cCode]['tableIn_detail'] = array();
        $_SESSION[$cCode]['tableIn_detail2'] = array();


        $this->load->helper("he_value_builder");
        cekBiru(":: sebelum fillValue");
        fillValues($this->jenisTr, 1, 1);

//        cekLime(":: setelah fillValue");
//        arrPrintWebs($_SESSION[$cCode]['main']);
//mati_disini(__FUNCTION__);

        $this->populateValuesToItems();
//        arrPrint($_SESSION[$cCode]['items']);

        if (!isset($_GET['stopHere'])) {
//            cekHitam("masuk populateValues");
            $no_viewCart = isset($_GET['noview']) ? "noview" : "";
            $this->populateValues($no_viewCart);
        }
        else {
//            cekPink("lewat saja, tidak masuk populateValues");
        }

//        arrPrint($_SESSION[$cCode]['items']);
//        mati_disini("end of buildValue");

        if(!isset($_GET['noview'])){
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
//        echo "  top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            echo "  top.$('#shopping_cart').load('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";

            echo "</script>";
        }




    }

    public function evalFees()
    {
//        die("eval...");
        $cCode = "_TR_" . $this->jenisTr;

        $extConfig = array();
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'])) {
            if (sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
                $extConfig = $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'];
            }
        }

//        print_r($_GET);
        if (!isset($_SESSION[$cCode]['main_add_values'])) {
            $_SESSION[$cCode]['main_add_values'] = array();
        }
        $_SESSION[$cCode]['main_add_values'][$_GET['key']] = $_GET['value'];
        if (isset($extConfig[$_GET['key']]['taxFactor']) && $extConfig[$_GET['key']]['taxFactor'] > 0) {
            echo $_GET['key'] . " mengandung TAX " . $extConfig[$_GET['key']]['taxFactor'] . "<br>";
            $_SESSION[$cCode]['main_add_values'][$_GET['key'] . "_tax"] = ($_GET['value'] * $extConfig[$_GET['key']]['taxFactor'] / 100);
        }
        else {
            echo $_GET['key'] . " TIDAK mengandung TAX <br>";
        }


        if (sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
            foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }
        }

//        die();

//        arrPrint($_SESSION[$cCode]['main_add_values']);
//        die();


        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0');";
        echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=10000','shopping_cart');";
        echo "</script>";

    }

    public function evalVals()
    {
//        die("eval...");
        $cCode = "_TR_" . $this->jenisTr;

        $extConfig = array();
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'])) {
            if (sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
                $extConfig = $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'];
            }
        }

//        print_r($_GET);
        if (!isset($_SESSION[$cCode]['main_add_fields'])) {
            $_SESSION[$cCode]['main_add_fields'] = array();
        }
        $_SESSION[$cCode]['main_add_fields'][$_GET['key']] = $_GET['value'];


        if (sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
            foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }
        }

//        die();

//        arrPrint($_SESSION[$cCode]['main_add_fields']);
//        die();


        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0');";
        echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=10000','shopping_cart');";
        echo "</script>";

    }

    public function fillByPaymentMethod()
    {
//        arrprint($_GET);die();
        $cCode = "_TR_" . $this->jenisTr;
        $availPayments = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['availPayments']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['availPayments'] : array();
        if (array_key_exists($_GET['val'], $availPayments)) {
            foreach ($availPayments as $p => $pSpec) {
                $valueGate = $pSpec['valueGate'];
                $_SESSION[$cCode]['main'][$valueGate] = 0;
            }
            $valueGate = $availPayments[$_GET['val']]['valueGate'];
            $valueSrc = $availPayments[$_GET['val']]['valueSrc'];
            $_SESSION[$cCode]['main'][$valueGate] = isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0;
            $_SESSION[$cCode]['main']['paymentMethod'] = $_GET['val'];
            $_SESSION[$cCode]['tableIn_master']['pembayaran'] = $_GET['val'];

        }
// else {
//            die("unknown payment method!");
//        }


        $addQS = "";
        if (isset($_GET['populate']) && $_GET['populate'] == 1) {
            $addQS .= "&populate=1&popValue=" . $_GET['popValue'] . "&popAcuanSrc=" . $_GET['popAcuanSrc'] . "&popAcuanTarget=" . $_GET['popAcuanTarget'];
        }
//        die("checking pop");
        if (strlen($addQS) > 3) {
            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/populateValues/" . $this->jenisTr . "?$addQS');";
            echo "</script>";
        }
//        die("evaluating oayment method ".$_GET['val']);
    }

    public function recordColumn()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $colName = $this->uri->segment(4);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;
//arrPrint($_GET);


        $_SESSION[$cCode]['main'][$colName] = $val;
        $_SESSION[$cCode]['main'][$colName] = $val;
        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
        }

        $addQS = "";
        if (isset($_GET['populate']) && $_GET['populate'] == 1) {
//            $addQS .= "&populate=1&popValue=" . $_GET['popValue'] . "&popAcuanSrc=" . $_GET['popAcuanSrc'] . "&popAcuanTarget=" . $_GET['popAcuanTarget'];

            $this->populateValues();

            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0$addQS');";
            echo "</script>";
        }
        else {
            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1');";
            echo "</script>";
        }

    }

    public function populateValues($no_viewCart='')
    {
        $cCode = "_TR_" . $this->jenisTr;

        $populatorConfig = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['valuePopulator']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['valuePopulator'] : array();
        if (sizeof($populatorConfig) > 0) {
            foreach ($populatorConfig as $key => $val) {
                $newVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                $$key = $newVal;
            }
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $nilaiAsal = $valueSrc;
                echo "nilai sekarang: $nilaiAsal<br>";
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    if ($nilaiAsal >= $iSpec[$acuanSrc]) {
                        $diambil = $iSpec[$acuanSrc];
                        echo "nilai diambil: $diambil ::: lebih besar samadengan yang diminta<br>";
                    }
                    else {
                        $diambil = $nilaiAsal;
                        echo "nilai diambil: $diambil ::: lebih kecil dari yang diminta<br>";
                    }
                    $_SESSION[$cCode]['items'][$id]['nilai_bayar'] = $diambil;
                    $_SESSION[$cCode]['items'][$id]['new_sisa'] = ($iSpec['sisa'] - $diambil);
                    $nilaiAsal -= $diambil;
                }
            }
        }

        $no_viewCart = $no_viewCart!='' ? "noview=1&" : "";

        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?".$no_viewCart."selID=0&stopHere=1');";
        echo "</script>";
    }

    public function populateValuesToItems()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $populatorToItemsConfig = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['valuePopulatorToItems']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['valuePopulatorToItems'] : array();
        if ((isset($populatorToItemsConfig['source'])) && (sizeof($populatorToItemsConfig['source']) > 0)) {
            foreach ($populatorToItemsConfig['source'] as $key => $val) {
                $newVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                $$key = $newVal;
            }
        }

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            if ((isset($populatorToItemsConfig['target'])) && (sizeof($populatorToItemsConfig['target']) > 0)) {
                foreach ($populatorToItemsConfig['target'] as $key => $val) {
                    foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                        foreach ($iSpec as $iKey => $iVal) {
                            $_SESSION[$cCode]['items'][$id][$iKey] = $iVal;
                            if (isset($$val) && ($$val > 0)) {
                                $_SESSION[$cCode]['items'][$id][$key] = $$val;
                            }
                        }
                    }
                }
            }
        }
    }

    public function recordItemColumn()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $iID = $_GET['iid'];
        $colName = $this->uri->segment(4);
        $val = rawurldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? rawurldecode($_GET['valValue']) : null;
//        print_r($_GET);

        $_SESSION[$cCode]['items'][$iID][$colName] = $val;
        $_SESSION[$cCode]['out_detail'][$iID][$colName] = $val;

        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['items'][$iID][$valCol] = $valValue;
            $_SESSION[$cCode]['out_detail'][$iID][$valCol] = $valValue;
        }
        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1');";
        echo "</script>";

    }

    public function recordPairedItem()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];
        $clonerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['cloner']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['cloner'] : array();
        $mainClonerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['mainCloner']['rsltItems']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['mainCloner']['rsltItems'] : array();


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        if (!isset($_SESSION[$cCode]["items2_sum"])) {
            $_SESSION[$cCode]["items2_sum"] = array();
        }


        $iID = $_GET['iid'];
        $colName = $this->uri->segment(4);
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

                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

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


//        mati_disini();
        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]["items2_sum"][$iID][$valCol] = $valValue;
        }
        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1');";
        echo "</script>";

    }

    public function recordPairedItemSatuan()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];
        $clonerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['cloner']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['cloner'] : array();
        $mainClonerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['mainCloner']['items2']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['mainCloner']['items2'] : array();


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        $iID = $_GET['iid'];
        $colName = $this->uri->segment(4);
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


                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");


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
        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1');";
        echo "</script>";

    }

    public function recordImage()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $colName = $this->uri->segment(4);
        $iID = $this->uri->segment(5);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;

        $files = $_FILES['file'];

        if ($files['error'] == 0) {

            $cUrl_result = upload_image($files);

            $_SESSION[$cCode]['items'][$iID][$colName] = $cUrl_result->full_url;
        }
        $addQS = "";
        if (isset($_GET['populate']) && $_GET['populate'] == 1) {
//            $addQS .= "&populate=1&popValue=" . $_GET['popValue'] . "&popAcuanSrc=" . $_GET['popAcuanSrc'] . "&popAcuanTarget=" . $_GET['popAcuanTarget'];

            $this->populateValues();

            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0$addQS');";
            echo "</script>";
        }
        else {
            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1');";
            echo "</script>";
        }
//        arrPrint($cUrl_result->full_url);
    }

    public function recordPairedItemOther()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModelTarget'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModelTarget'];

        $clonerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['cloner']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['cloner'] : array();
        $mainClonerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['mainCloner']['rsltItems']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['mainCloner']['rsltItems'] : array();


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();
//cekHere($selectorSrcModel);

        if (!isset($_SESSION[$cCode]["items2_sum"])) {
            $_SESSION[$cCode]["items2_sum"] = array();
        }


        $iID = $_GET['iid'];
        $colName = $this->uri->segment(4);
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

                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

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
        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1');";
        echo "</script>";

    }

    //----------------
    public function checklistOpname()
    {

        $pID = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;

        if (isset($_SESSION[$cCode]['items'][$pID])) {
            //---- masuk gerbang ceklist, di items
            $_SESSION[$cCode]['items'][$pID]['ceklist_opname'] = 1;
        }

//        mati_disini("cek pID: $pID");
    }

    public function checklistOpnameNote()
    {
//        arrPrint($_GET);
        $cCode = "_TR_" . $this->jenisTr;
        $note1 = blobDecode($_GET['note1']);
        $note2 = blobDecode($_GET['note2']);

        if (isset($_SESSION[$cCode]['main'])) {
            if(isset($_GET['note1'])){

                $_SESSION[$cCode]['main']['opnameNote_1'] = $note1;
                $_SESSION[$cCode]['main']['opnameNoteCeklist_1'] = 1;
            }
            if(isset($_GET['note2'])){

                $_SESSION[$cCode]['main']['opnameNote_2'] = $note2;
                $_SESSION[$cCode]['main']['opnameNoteCeklist_2'] = 1;
            }
        }
    }
}