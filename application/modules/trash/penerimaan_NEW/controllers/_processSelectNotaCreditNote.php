<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";
class _processSelectNotaCreditNote extends Modul_Controller
{
//    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
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

    }

    public function select()
    {
//        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;



        $row = $_GET;

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;


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

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();
        $tmpB = $b->lookupByID($id)->result();
//	    arrPrint($tmpB);
//        mati_disini();

        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
            cekMerah("akan memasukkan ITEMS");
            $tmp = array(
                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                "id" => $id,
                "refID" => $id,
                "jml" => 1,
                "harga" => 0,
                "satuan" => "-",
                "subtotal" => 0,
                "jenis" => $tmpB['0']->jenis,
            );

            foreach ($fieldSrcs as $key => $src) {
//                $tmpEx = $cal->multiExplode($src);
//                arrPrint($tmpEx);
//                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
//                    cekBiru("$key perhitungan");
//                    $newSrc = $src;
//                    foreach ($tmpEx as $key2 => $val2) {
//                        echo "$key2 - $val2 <br>";
//                        if (!is_numeric($val2)) {
//                            if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
//                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                            } else {
//                                $newSrc = str_replace($val2, 0, $newSrc);
//                            }
//                        }
//
//                    }
//                    cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
//                    $tmp[$key] = $cal->calculate($newSrc);
//                } else {
//                    cekBiru("$key BUKAN perhitungan");
//                    $tmp[$key] = $row[$src];
//                }
                $tmp[$key] = makeValue($src, $tmp, $tmp, $tmpB[0]->$src);
            }
            //===perhitungan subtotal
            $this->load->library("FieldCalculator");
            $cal = new FieldCalculator();


            if ($subAmountConfig != null) {
//                $tmpEx = $cal->multiExplode($subAmountConfig);
//                if (sizeof($tmpEx) > 1) {
//                    $newSrc = $subAmountConfig;
//                    foreach ($tmpEx as $key2 => $val2) {
//                        if (isset($tmp[$val2])) {
//                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                            cekKuning("$val2 direplace dengan " . $tmp[$val2]);
//                        } else {
//                            $newSrc = str_replace($val2, "0", $newSrc);
//                            cekKuning("$val2 direplace dengan NOL");
//                        }
//
//                    }
//                    $subtotal = $cal->calculate($newSrc);
//                    cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");
//
//                } else {
//                    $subtotal = isset($tmp[$subAmountConfig]) ? $tmp[$subAmountConfig] : 0;
//                    cekHijau("subtotal dari perhitungan yang gak ada");
//                }
                $tmp['subtotal'] = makeValue($src, $tmp, $tmp, $tmpB[0]->$src);
            }
            else {
                $tmp['subtotal'] = 0;
            }


        }
        else {
            cekMerah("TIDAK akan memasukkan ITEMS");
        }

        $_SESSION[$cCode]['items'][$id] = $tmp;


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