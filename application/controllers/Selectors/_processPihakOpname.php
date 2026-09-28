<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
class _processPihakOpname extends CI_Controller
{

    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function select()
    {

        $pihakMainValueSrc = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainValueSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakModel = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['pihakModel']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['pihakModel'] : "";
        $selectorModel = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'] : "";
        $selectorSrcModel = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'] : "";
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $priceFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice'] : array();
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;


        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
//        $mdlName = $this->uri->segment(5);
        $mdlName = $pihakModel;

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["pihakMainNota"]) && $this->config->item("heTransaksi_ui")[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }


            $tmpPihakName_tmpB = $tmpPihakName;
//            echo "<script>";
//            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo';";
//            echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
//            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
//            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $tmpPihakName_tmpB = $_SESSION[$cCode]['main']['pihakName2'];
//            echo "<script>";
//            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo';";
//            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
//            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
//            echo "</script>";
        }

        // ======================================= ======================================= ======================================= //

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();
        $tmpB = $b->lookupAll()->result();
//        cekMerah($this->db->last_query());
//        mati_disini();
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $pid = $row->id;
                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;

                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $pid,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();

                        if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                            $h->addFilter("produk_id='$pid'");
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        }
                        else {
                            $h->addFilter("produk_id='$pid'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        }

                        cekKuning("masukkk pak eko");
                        if (sizeof($priceFilter) > 0) {
                            foreach ($priceFilter as $f) {
                                $f_ex = explode("=", $f);
                                if (!isset($f_ex[1])) {
                                    $f_ey = explode(">", $f_ex[0]);
                                    if (substr($f_ey[1], 0, 1) == ".") {
                                        $h->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                                            $h->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                                        }
                                        else {
                                            $h->addFilter($f_ey[0] . ">0");
                                        }
                                    }
                                }
                                else {
                                    if (substr($f_ex[1], 0, 1) == ".") {
                                        $h->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                                            $h->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                                        }
                                        else {
                                            $h->addFilter($f_ex[0] . "=''");
                                        }

                                    }
                                }
                            }
                        }


                        $tmpH = $h->lookupAll($id)->result();
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {

                                    cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        cekBiru("sino$key ||" . $hSpec->$key);
//                                        if ($key == $hSpec->h) {
//                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
//                                        }
                                    }
                                    else {
                                        cekBiru("sini");
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
                                    }

                                }

                            }
//                            arrPrint($rawPrices);
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }

                    foreach ($fieldSrcs as $key => $src) {
                        cekUngu(":: $key => $src ::");
//                        $tmp[$key] = makeValue($src, $_SESSION[$cCode]['items'][$id], $tmp, $tmpB[0]->$src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                    }

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;

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

                    $_SESSION[$cCode]['items'][$pid] = $tmp;

                }
                else {
//                    if (isset($_GET['newQty'])) {
//                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
//                    }
//                    else {
//                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
//                    }
                    if (isset($_GET['qty_opname'])) {
                        $_SESSION[$cCode]['items'][$id]['qty_opname'] = $_GET['qty_opname'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));

                        $selisih = $_GET['qty_opname'] - $_SESSION[$cCode]['items'][$id]['stok'];
                        if ($selisih > 0) {
                            $_SESSION[$cCode]['items'][$id]['qty_debet'] = $selisih;
                            $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
                            $_SESSION[$cCode]['items'][$id]['debet'] = $selisih * $_SESSION[$cCode]['items'][$id]['harga'];
                            $_SESSION[$cCode]['items'][$id]['kredit'] = 0;
                        }
                        elseif ($selisih < 0) {
                            $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['qty_kredit'] = ($selisih * -1);
                            $_SESSION[$cCode]['items'][$id]['debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['kredit'] = ($selisih * -1) * $_SESSION[$cCode]['items'][$id]['harga'];
                        }
                        else {
                            $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
                            $_SESSION[$cCode]['items'][$id]['debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['kredit'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['qty_selisih'] = $selisih;
                    }
//                    else {
//                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
//                    }


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

//            if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                $_SESSION[$cCode]['main']['harga'] = 0;
//                $_SESSION[$cCode]['out_master']['harga'] = 0;
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                    $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                }
//
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//
//                }
//
//            }
        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }


//        mati_disini("UNDER MAINTENANCE");
        echo "<script>";
        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
        echo "top.document.getElementById('pihakName').value='" . $tmpPihakName_tmpB . "';";
//        echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
        echo "</script>";
    }

    public function remove()
    {
        $cCode = "_TR_" . $this->jenisTr;

        $_SESSION[$cCode]['main']['pihakID'] = null;
        $_SESSION[$cCode]['main']['pihakName'] = null;
        $_SESSION[$cCode]['main']['pihakName2'] = null;
        unset($_SESSION[$cCode]['main']['pihakID']);
        unset($_SESSION[$cCode]['main']['pihakName']);
        unset($_SESSION[$cCode]['main']['pihakName2']);

        $_SESSION[$cCode]['main']['pihak2ID'] = null;
        $_SESSION[$cCode]['main']['pihak2Name'] = null;
        $_SESSION[$cCode]['main']['pihak2Name2'] = null;
        unset($_SESSION[$cCode]['main']['pihak2ID']);
        unset($_SESSION[$cCode]['main']['pihak2Name']);
        unset($_SESSION[$cCode]['main']['pihak2Name2']);


    }

}