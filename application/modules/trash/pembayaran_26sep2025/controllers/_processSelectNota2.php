<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";

class _processSelectNota2 extends Modul_Controller
{
    // private $jenisTr;
    // private $validateJenisTr = array();

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
        $transaksi_id_selected = $id = $_GET['transaksi_id'];
        //unset($_SESSION[$cCode]['itemSrc_sum']);
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";

        if (in_array($this->jenisTr, $this->validateJenisTr)) {
            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
            }
        }


        if ($isRadioSelect) {
            $detailResetList = array(
                "itemSrc",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode][$sSName]);
            }

            $_SESSION[$cCode]['main']['refIDs'] = null;
            unset($_SESSION[$cCode]['main']['refIDs']);

            if (sizeof($_SESSION[$cCode]['itemSrc']) > 0) {

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
            "placeID" => isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : my_cabang_id(),
            "placeName" => isset($_SESSION[$cCode]['main']['placeName']) ? $_SESSION[$cCode]['main']['placeName'] : my_cabang_nama(),
            "olehID" => isset($_SESSION[$cCode]['main']['olehID']) ? $_SESSION[$cCode]['main']['olehID'] : my_id(),
            "olehName" => isset($_SESSION[$cCode]['main']['olehName']) ? $_SESSION[$cCode]['main']['olehName'] : my_name(),
            //            "valasDetails" => $_GET['valas_id'],
            //            "pihakMainName" => isset($_GET['extern_label2']) && sizeof($_GET['extern_label2']) > 0 ? $_GET['extern_label2'] : "",
            //            "other" => isset($_GET['extern_nilai4']) && sizeof($_GET['extern_nilai4']) > 0 ? $_GET['extern_nilai4'] : "",
            //            "relativeComName" => sizeof($relativeComNameDetails) > 0 ? $relativeComNameDetails[$_GET['extern_label2']] : "",
            //            "pph_23" => isset($_GET['pph_23']) ? $_GET['pph_23'] : 0,
            //            "terbayar_pph23" => isset($_GET['terbayar_pph23']) ? $_GET['terbayar_pph23'] : 0,
            //            "pphGate" => isset($_GET['extern_jenis']) && sizeof($_GET['extern_jenis']) > 0 ? $_GET['extern_jenis'] : "",
            //            "pphGateLabel" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
            //            "pairPihakName" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
            //            "pairPihakID" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",
            //            "pphGateId" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",

        );
        $transaksi_id_selected = $id = $_GET['transaksi_id'];

        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        $row = $_GET + array("id" => $id);

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();

        if (!array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
            //            cekMerah("akan memasukkan ITEMS");
            $tmp = array(
                "handler" => $this->uri->segment(1),
                "id" => $id,
                "refID" => $id,
                "placeID" => $_SESSION[$cCode]['main']['placeID'],
                "olehID" => $_SESSION[$cCode]['main']['olehID'],
                "olehName" => $_SESSION[$cCode]['main']['olehName'],
                "jml" => 1,
                "harga" => 0,
                "satuan" => "-",
                "subtotal" => 0,
                //                "nama" => 0,
                //                "nomer" => 0,
            );

            foreach ($fieldSrcs as $key => $src) {
                $tmp[$key] = makeValue($src, $row, $row, 0);
            }


            foreach ($_GET as $aa => $bb){
                $tmp[$aa] = $bb;
//                if(!array_key_exists($aa, $tmp)){
//                }
            }


            if ($subAmountConfig != null) {
                $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                $tmp["subtotal"] = $subtotal;
            }
            else {
                $tmp["subtotal"] = 0;
            }


        }
        else {
            cekMerah("TIDAK akan memasukkan ITEMS");
        }

        $_SESSION[$cCode]['main']['refIDs'][$id] = $id;

        switch ($_GET['state']) {
            case "true":
                if (!array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
                    $_SESSION[$cCode]['itemSrc'][$id] = $tmp;
                }
                if (sizeof($componentAssConfig) > 0) {
                    if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                        $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                    }
                }


                if (isset($_GET["src"]) && ($_GET["src"] == "preview")) {
//                    $_SESSION[$cCode]['items'][$id]["sisa"] = $_SESSION[$cCode]['items'][$id]["nilai_didelete"];
//                    $_SESSION[$cCode]['items'][$id]["nilai_didelete"] = 0;
                    $gerbang_asal = $_GET["asal"];
                    $gerbang_target = $_GET["target"];
                    if ($gerbang_target == "sisa") {
                        $_SESSION[$cCode]['items'][$id]["new_sisa"] = 0;
                    }
                    $_SESSION[$cCode]['items'][$id][$gerbang_target] = $_SESSION[$cCode]['items'][$id][$gerbang_asal];
                    $_SESSION[$cCode]['items'][$id][$gerbang_asal] = 0;
                }

                break;
            case "false":
                if (isset($_GET["src"]) && ($_GET["src"] == "preview")) {
//                    $_SESSION[$cCode]['items'][$id]["nilai_didelete"] = $_SESSION[$cCode]['items'][$id]["sisa"];
//                    $_SESSION[$cCode]['items'][$id]["sisa"] = 0;
                    $gerbang_asal = $_GET["asal"];
                    $gerbang_target = $_GET["target"];
                    if ($gerbang_asal == "sisa") {
                        $_SESSION[$cCode]['items'][$id]["new_sisa"] = $_SESSION[$cCode]['items'][$id][$gerbang_asal];
                    }
                    $_SESSION[$cCode]['items'][$id][$gerbang_target] = $_SESSION[$cCode]['items'][$id][$gerbang_asal];
                    $_SESSION[$cCode]['items'][$id][$gerbang_asal] = 0;
                }
                else{

                    if (array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
                        $detailResetList = array(
                            "itemSrc",
                            "itemSrc_sum",
                            //                        "tableIn_detail",
                            //                        "tableIn_detail2",
                            //                        "tableIn_detail_values",
                            //                        "tableIn_detail2_sum",
                            //                        "tableIn_detail_values2_sum",
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

                    if (sizeof($_SESSION[$cCode]['itemSrc']) > 0) {

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
                }

                break;
        }

        if (sizeof($_SESSION[$cCode]['itemSrc']) > 0) {
            cekBiru("bulding summary item_result...");
            $_SESSION[$cCode]['itemSrc_sum'] = array();
            //            $replaceItemSrc = array(
            ////                "harga" =>"src_harga",
            //                "tagihan" =>"src_harga",
            //                "jml" =>"jml",
            //                "subtotal" =>"src_subtotal",
            //            );
            foreach ($_SESSION[$cCode]['itemSrc'] as $pID => $pSpec) {
                //                arrPrint($pSpec);
                //                matiHEre();
                //                foreach ($pSpec as $eSpec) {
                //                    $eeSpec = $replaceItemSrc
                if (!isset($_SESSION[$cCode]['itemSrc_sum'][$pID])) {
//                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']] = $eSpec;
//                     $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']] = $pSpec;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID] = $pSpec;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID]['jml'] = 0;
                    //                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['harga'] = 0;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_harga'] = 0;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_subtotal'] = 0;
                    //                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['produk_ids'] = array();


                }

                $_SESSION[$cCode]['itemSrc_sum'][$pID]['jml'] = $pSpec['jml'];
                $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_harga'] += $pSpec['tagihan'];
                $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_subtotal'] += $pSpec['subtotal'];
                //                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['produk_ids'][$pID] = $pID;

                //                }
            }
        }
        else {
            //reset gerbang nilai di main
            $_SESSION[$cCode]['main']['src_harga'] = 0;
            $_SESSION[$cCode]['main']['src_subtotal'] = 0;

        }

        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        //-----------------------------------------------------

        switch($this->jenisTr){
            case "114":

                $lembarFaktur = isset($this->configUi[$this->jenisTr]["lembarFaktur"]) ? $this->configUi[$this->jenisTr]["lembarFaktur"] : array();
                foreach ($lembarFaktur as $gate => $gerbang){
                    $_SESSION[$cCode]["main"][$gerbang] = isset($_SESSION[$cCode][$gate]) ? count($_SESSION[$cCode][$gate]) : 0;
                }
                if(isset($_GET["src"]) && ($_GET["src"] == "preview")){
                    $this->load->helper("he_value_builder");
                    fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());
                    $load_link = MODUL_PATH . "Create/previewMulti/" . $this->jenisTr;
                    echo "<script>";
                    echo "  if(top.document.getElementById('items_preview_$transaksi_id_selected')){";
                    echo "  $('#items_preview_$transaksi_id_selected').css('visibility', 'collapse');";
                    echo "  }";
                    echo "  if(top.document.getElementById('previewMulti')){";
                    echo "  $('#previewMulti').load('$load_link');";
                    echo "  }";
                    echo "</script>";
                }
                else{
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
                        "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                        "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                        "stepNumber" => 1,
                        "stepCode" => $this->configUiJenis['steps'][1]['target'],
                        "dtime" => dtimeNow(),
                        "fulldate" => dtimeNow("Y-m-d"),
                        // "jenis_pajak"=>$this->session->login['jenis_usaha'],
                    );
                    $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

                    /* --------------------------------------------------
                     * ngereload shoping cart dlm modul
                     * --------------------------------------------------*/
                    echo "<script>";
                    echo "  if(top.document.getElementById('shopping_cart')){";
                    echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
                    echo "  }";
                    echo "</script>";

                }
                break;
            default:

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
                    "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                    "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                    "stepNumber" => 1,
                    "stepCode" => $this->configUiJenis['steps'][1]['target'],
                    "dtime" => dtimeNow(),
                    "fulldate" => dtimeNow("Y-m-d"),
                    // "jenis_pajak"=>$this->session->login['jenis_usaha'],
                );
                $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


                /* --------------------------------------------------
                 * ngereload shoping cart dlm modul
                 * --------------------------------------------------*/
                echo "<script>";
                echo "  if(top.document.getElementById('shopping_cart')){";
                echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
                echo "  }";
                echo "</script>";

                break;
        }

    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();


        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
            cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['itemSrc'][$id])) {
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
        if (isset($_SESSION[$cCode]['itemSrc'][$id])) {
            $_SESSION[$cCode]['itemSrc'][$id] = null;
            $_SESSION[$cCode]['itemSrc_sum'][$id] = null;
            unset($_SESSION[$cCode]['itemSrc'][$id]);
            unset($_SESSION[$cCode]['itemSrc_sum'][$id]);
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
        //        if (isset($_GET['mb'])) {
        //            $initMaster = array(
        //                "olehID" => $this->session->login['id'],
        //                "olehName" => $this->session->login['nama'],
        //                "sellerID" => $this->session->login['id'],
        //                "sellerName" => $this->session->login['nama'],
        //                "placeID" => $this->session->login['cabang_id'],
        //                "placeName" => $this->session->login['cabang_nama'],
        //                "divID" => isset($this->session->login['div_id']) ? $this->session->login['div_id'] : 0,
        //                "divName" => isset($this->session->login['div_nama']) ? $this->session->login['div_nama'] : 0,
        //                "cabangID" => $this->session->login['cabang_id'],
        //                "cabangName" => $this->session->login['cabang_nama'],
        //                "gudangID" => $this->session->login['gudang_id'],
        //                "gudangName" => $this->session->login['gudang_nama'],
        //                "jenis_usaha" => isset($this->session->login['jenis_usaha']) ? $this->session->login['jenis_usaha'] : '-',
        //                "jenisTr" => $this->jenisTr,
        //                "jenisTrMaster" => $this->jenisTr,
        //                "jenisTrTop" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
        //                "jenisTrName" => $this->jenisTrName,
        //                "stepNumber" => $stepNum,
        //                "stepCode" => $this->configUi[$this->jenisTr]['steps'][$stepNum]['target'],
        //                "dtime" => date("Y-m-d H:i:s"),
        //                "fulldate" => date("Y-m-d"),
        //                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        //                "tokoID" => $this->session->login['toko_id'],
        //                "tokoNama" => $this->session->login['toko_nama'],
        //            );
        //            echo "<script>";
        //            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?" . $f_selector . "selID=$id';";
        //            echo "top.load_shoppingcart();";
        //            echo "</script>";
        //        }
        //        else {
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
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        // matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
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
    //-----------------------
    public function selectMulti()
    {
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        $id = $_GET['transaksi_id'];
        $itemSrc = $_GET['itemSrc'];
        $arrItemSrc = explode(",", $itemSrc);

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();

        if (in_array($this->jenisTr, $this->validateJenisTr)) {
            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
            }
        }

//cekHere($itemSrc);
//arrPrint($arrItemSrc);



//mati_disini(__LINE__);

        if ($isRadioSelect) {
            $detailResetList = array(
                "itemSrc",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode][$sSName]);
            }

            $_SESSION[$cCode]['main']['refIDs'] = null;
            unset($_SESSION[$cCode]['main']['refIDs']);

            if (sizeof($_SESSION[$cCode]['itemSrc']) > 0) {

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
            "placeID" => isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : my_cabang_id(),
            "placeName" => isset($_SESSION[$cCode]['main']['placeName']) ? $_SESSION[$cCode]['main']['placeName'] : my_cabang_nama(),
            "olehID" => isset($_SESSION[$cCode]['main']['olehID']) ? $_SESSION[$cCode]['main']['olehID'] : my_id(),
            "olehName" => isset($_SESSION[$cCode]['main']['olehName']) ? $_SESSION[$cCode]['main']['olehName'] : my_name(),
            //            "valasDetails" => $_GET['valas_id'],
            //            "pihakMainName" => isset($_GET['extern_label2']) && sizeof($_GET['extern_label2']) > 0 ? $_GET['extern_label2'] : "",
            //            "other" => isset($_GET['extern_nilai4']) && sizeof($_GET['extern_nilai4']) > 0 ? $_GET['extern_nilai4'] : "",
            //            "relativeComName" => sizeof($relativeComNameDetails) > 0 ? $relativeComNameDetails[$_GET['extern_label2']] : "",
            //            "pph_23" => isset($_GET['pph_23']) ? $_GET['pph_23'] : 0,
            //            "terbayar_pph23" => isset($_GET['terbayar_pph23']) ? $_GET['terbayar_pph23'] : 0,
            //            "pphGate" => isset($_GET['extern_jenis']) && sizeof($_GET['extern_jenis']) > 0 ? $_GET['extern_jenis'] : "",
            //            "pphGateLabel" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
            //            "pairPihakName" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
            //            "pairPihakID" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",
            //            "pphGateId" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",

        );
        $id = $_GET['transaksi_id'];

        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        $row = $_GET + array("id" => $id);

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        if (!array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
            //            cekMerah("akan memasukkan ITEMS");
            $tmp = array(
                "handler" => $this->uri->segment(1),
                "id" => $id,
                "refID" => $id,
                "placeID" => $_SESSION[$cCode]['main']['placeID'],
                "olehID" => $_SESSION[$cCode]['main']['olehID'],
                "olehName" => $_SESSION[$cCode]['main']['olehName'],
                "jml" => 1,
                "harga" => 0,
                "satuan" => "-",
                "subtotal" => 0,
                //                "nama" => 0,
                //                "nomer" => 0,
            );

            foreach ($fieldSrcs as $key => $src) {
                $tmp[$key] = makeValue($src, $row, $row, 0);
            }
//arrPrint()
            if ($subAmountConfig != null) {
                $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                $tmp["subtotal"] = $subtotal;
            }
            else {
                $tmp["subtotal"] = 0;
            }


        }
        else {
            cekMerah("TIDAK akan memasukkan ITEMS");
        }

        $_SESSION[$cCode]['main']['refIDs'][$id] = $id;

        switch ($_GET['state']) {
            case "true":
                if (!array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
                    $_SESSION[$cCode]['itemSrc'][$id] = $tmp;
                }
                if (sizeof($componentAssConfig) > 0) {
                    if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                        $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                    }
                }
                break;
            case "false":
                if (array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
                    $detailResetList = array(
                        "itemSrc",
                        "itemSrc_sum",
                        //                        "tableIn_detail",
                        //                        "tableIn_detail2",
                        //                        "tableIn_detail_values",
                        //                        "tableIn_detail2_sum",
                        //                        "tableIn_detail_values2_sum",
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

                if (sizeof($_SESSION[$cCode]['itemSrc']) > 0) {

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

        if (sizeof($_SESSION[$cCode]['itemSrc']) > 0) {
            cekBiru("bulding summary item_result...");
            $_SESSION[$cCode]['itemSrc_sum'] = array();

            foreach ($_SESSION[$cCode]['itemSrc'] as $pID => $pSpec) {

                if (!isset($_SESSION[$cCode]['itemSrc_sum'][$pID])) {
//                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']] = $eSpec;
//                     $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']] = $pSpec;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID] = $pSpec;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID]['jml'] = 0;
                    //                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['harga'] = 0;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_harga'] = 0;
                    $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_subtotal'] = 0;
                    //                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['produk_ids'] = array();


                }

                $_SESSION[$cCode]['itemSrc_sum'][$pID]['jml'] = $pSpec['jml'];
                $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_harga'] += $pSpec['tagihan'];
                $_SESSION[$cCode]['itemSrc_sum'][$pID]['src_subtotal'] += $pSpec['subtotal'];

            }
        }
        else {
            //reset gerbang nilai di main
            $_SESSION[$cCode]['main']['src_harga'] = 0;
            $_SESSION[$cCode]['main']['src_subtotal'] = 0;

        }

        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
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
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }


}