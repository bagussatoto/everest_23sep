<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
class _processSelectNota2 extends CI_Controller
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
//unset($_SESSION[$cCode]['itemSrc_sum']);
        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeComNameDetails']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['isPaymentRadioSelect']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['label']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['label'] : "";

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

        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        $row = $_GET;

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['transaksi_id'];
        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss'] : array();

//        arrPrint($row);
//        arrPrintWebs($fieldSrcs);
//        matiHEre();
        if (!array_key_exists($id, $_SESSION[$cCode]['itemSrc'])) {
//            cekMerah("akan memasukkan ITEMS");
            $tmp = array(
                "handler" => $this->uri->segment(1),
                "id" => $id,
                "refID" => $id,
                "placeID" =>$_SESSION[$cCode]['main']['placeID'],
                "olehID" =>$_SESSION[$cCode]['main']['olehID'],
                "olehName" =>$_SESSION[$cCode]['main']['olehName'],
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
                    if (!isset($_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']])) {
                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']] = $eSpec;
                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['jml'] = 0;
//                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['harga'] = 0;
                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['src_harga'] = 0;
//                        $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['produk_ids'] = array();


                    }

                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['jml'] += $pSpec['jml'];
                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['src_harga'] += $pSpec['tagihan'];
                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['src_subtotal'] += $pSpec['subtotal'];
//                    $_SESSION[$cCode]['itemSrc_sum'][$pSpec['id']]['produk_ids'][$pID] = $pID;

//                }
            }
        }
        else{
            //reset gerbang nilai di main
            $_SESSION[$cCode]['main']['src_harga'] = 0;
            $_SESSION[$cCode]['main']['src_subtotal'] = 0;

        }




//              arrPrint($_SESSION[$cCode]['main']);
//              mati_disini("under debuging");

        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);


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
}