<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
class _processSelectNotaItemAssembling extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $this->load->model("MdlTransaksi");

        $cal = new FieldCalculator();
        $trs = new Mdltransaksi();


        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = "_TR_" . $this->jenisTr;
        if (isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = null;
            unset($_SESSION[$cCode]['items']);
        }
        if (isset($_SESSION[$cCode]['items2_sum'])) {
            $_SESSION[$cCode]['items2_sum'] = null;
            unset($_SESSION[$cCode]['items2_sum']);
        }


        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $referenceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceFields'] : null;


        //  membaca isi nota
        $tmpB = $b->lookupByID($id)->result();
//        cekMerah($this->db->last_query());

        //  membaca detail nilai, transaksi_data_values
        $tmpDetailValues = $trs->lookupDetailValuesByTransID($id)->result();


        //  membaca registry (776r)
        $tmpRegistry = $trs->lookupRegistriesByMasterID($id)->result();

        $items = array();
        $items2 = array();
        $items2_sum = array();
        if (sizeof($tmpRegistry) > 0) {
            foreach ($tmpRegistry as $row) {
                $tmpParams = $row->param;
                $$tmpParams = unserialize(base64_decode($row->values));
            }
        }
//arrPrint($items2);
//        arrPrint($tableIn_detail_values2_sum);


        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['seluruhnya'] = true;
            $_SESSION[$cCode]['main']['referenceID'] = $id;
            foreach ($tmpB as $row) {
                $produk_jenis = $row->produk_jenis;
                $id = $row->produk_id;
                $tmpJml = $row->produk_ord_jml;
                $tmpJmlReturn = $row->produk_ord_jml_return;
                $tmpJml_avail = $tmpJml - $tmpJmlReturn;

                $tmpDisabled = "0";
                if ($tmpJml_avail <= 0) {
                    $tmpJml = 0;
                    $tmpDisabled = "1";
                }
                else {
                    $tmpJml = $tmpJml_avail;
                    $tmpDisabled = "0";
                }

                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if ($produk_jenis == "produk") {
                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                        $tmp = array(
                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                            "id" => $id,
                            "jml" => $tmpJml,
                            "harga" => 0,
                            "subtotal" => 0,
                            "disabled" => $tmpDisabled,
                            "nama" => $row->produk_nama,
                            "name" => $row->produk_nama,
                        );

                        //region injector ke items, detail isi nota
                        if (sizeof($tableIn_detail_values) > 0) {
                            foreach ($tableIn_detail_values as $pID => $rowD) {
                                foreach ($rowD as $key => $value) {
                                    if ($id == $pID) {
                                        $tmp[$key] = $value;
                                    }
                                }
                            }
                        }
                        //endregion

                        foreach ($fieldSrcs as $key => $src) {
//                            $tmpEx = $cal->multiExplode($src);
//                            if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
//                                cekBiru("$key perhitungan");
//                                $newSrc = $src;
//                                foreach ($tmpEx as $key2 => $val2) {
//                                    echo "$key2 - $val2 <br>";
//                                    if (!is_numeric($val2)) {
//                                        if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
//                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                        }
//                                        else {
//                                            $newSrc = str_replace($val2, 0, $newSrc);
//                                        }
//                                    }
////                                else {
////                                    if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
////                                        $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
////                                    } else {
////                                        if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
////                                            $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
////                                        } else {
////                                            $newSrc = str_replace($val2, 0, $newSrc);
////                                        }
////                                    }
////                                }
//                                }
//                                cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
//                                $tmp[$key] = $cal->calculate($newSrc);
//                            }
//                            else {
//                                cekBiru("$key BUKAN perhitungan");
//                                $tmp[$key] = isset($row->$src) ? $row->$src : "";
//                            }
                            $tmp[$key] = makeValue($src, $tmp, $tmp, 0);
                        }

                        //===perhitungan subtotal
//                        $cal = new FieldCalculator();
                        if ($subAmountConfig != null) {
//                            $tmpEx = $cal->multiExplode($subAmountConfig);
//                            if (sizeof($tmpEx) > 1) {
//                                $newSrc = $subAmountConfig;
//                                foreach ($tmpEx as $key2 => $val2) {
//                                    if (isset($tmp[$val2])) {
//                                        $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                        cekKuning("$val2 direplace dengan " . $tmp[$val2]);
//                                    }
//                                    else {
//                                        $newSrc = str_replace($val2, "0", $newSrc);
//                                        cekKuning("$val2 direplace dengan NOL");
//                                    }
//
//                                }
//                                $subtotal = $cal->calculate($newSrc);
//                                cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");
//
//                            }
//                            else {
//                                $subtotal = 0;
//                                cekHijau("subtotal dari perhitungan yang gak ada");
//                            }
                            $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                        }
                        else {
                            $subtotal = 0;
                        }
                        $tmp["subtotal"] = $subtotal;
                        $_SESSION[$cCode]['items'][$id] = $tmp;
                    }
                    else {
//                    if (isset($_GET['newQty'])) {
//                        if($_GET['newQty'] > $_SESSION[$cCode]['items'][$id]['jml']){
//                            $msg = "GAGAL BROOO...";
//                            cekHere("$msg");
//                            die(lgShowAlert($msg));
//                        }
//                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                    }
//                      else {
//                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                    }
                        if (sizeof($itemNumLabels) > 0) {
                            echo("iterating subNums..");
                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                    echo "replacing value for $key with " . $newValue . "<br>";
                                }

                            }
                            foreach ($itemNumLabels as $key => $label) {
                                $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                            }
                            $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
                            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                        }
                    }
                    if (sizeof($referenceConfig) > 0) {
                        foreach ($referenceConfig as $key => $label) {
                            $_SESSION[$cCode]['main'][$key] = $row->$label;
                        }
                    }
                    $_SESSION[$cCode]['items2'][$id] = $items2[$id];
                }
                else {
                    if (!array_key_exists($id, $_SESSION[$cCode]['items2_sum'])) {
                        $tmp = array(
                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                            "id" => $id,
                            "jml" => $tmpJml,
                            "harga" => 0,
                            "subtotal" => 0,
                            "disabled" => $tmpDisabled,
                            "nama" => $row->produk_nama,
                            "name" => $row->produk_nama,
                        );

                        //region injector ke items, detail isi nota
                        if (sizeof($tableIn_detail_values2_sum) > 0) {
                            foreach ($tableIn_detail_values2_sum as $pID => $rowD) {
                                foreach ($rowD as $key => $value) {
                                    if ($id == $pID) {
                                        $tmp[$key] = $value;
                                    }
                                }
                            }
                        }
                        //endregion

                        foreach ($fieldSrcs as $key => $src) {
//                            $tmpEx = $cal->multiExplode($src);
//                            if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
//                                cekBiru("$key perhitungan");
//                                $newSrc = $src;
//                                foreach ($tmpEx as $key2 => $val2) {
//                                    echo "$key2 - $val2 <br>";
//                                    if (!is_numeric($val2)) {
//                                        if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
//                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                        }
//                                        else {
//                                            $newSrc = str_replace($val2, 0, $newSrc);
//                                        }
//                                    }
////                                else {
////                                    if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
////                                        $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
////                                    } else {
////                                        if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
////                                            $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
////                                        } else {
////                                            $newSrc = str_replace($val2, 0, $newSrc);
////                                        }
////                                    }
////                                }
//                                }
//                                cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
//                                $tmp[$key] = $cal->calculate($newSrc);
//                            }
//                            else {
//                                cekBiru("$key BUKAN perhitungan");
//                                $tmp[$key] = isset($row->$src) ? $row->$src : "";
//                            }
                            $tmp[$key] = makeValue($src, $tmp, $tmp, 0);
                        }
                        //===perhitungan subtotal
//                        $cal = new FieldCalculator();
                        if ($subAmountConfig != null) {
//                            $tmpEx = $cal->multiExplode($subAmountConfig);
//                            if (sizeof($tmpEx) > 1) {
//                                $newSrc = $subAmountConfig;
//                                foreach ($tmpEx as $key2 => $val2) {
//                                    if (isset($tmp[$val2])) {
//                                        $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                        cekKuning("$val2 direplace dengan " . $tmp[$val2]);
//                                    }
//                                    else {
//                                        $newSrc = str_replace($val2, "0", $newSrc);
//                                        cekKuning("$val2 direplace dengan NOL");
//                                    }
//
//                                }
//                                $subtotal = $cal->calculate($newSrc);
//                                cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");
//
//                            }
//                            else {
//                                $subtotal = 0;
//                                cekHijau("subtotal dari perhitungan yang gak ada");
//                            }
                            $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                        }
                        else {
                            $subtotal = 0;
                        }
                        $tmp["subtotal"] = $subtotal;
                        $_SESSION[$cCode]['items2_sum'][$id] = $tmp;
                    }
                    else {
                        if (sizeof($itemNumLabels) > 0) {
                            echo("iterating subNums..");
                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode]['items2_sum'][$id][$key] = $newValue;
                                    echo "replacing value for $key with " . $newValue . "<br>";
                                }

                            }

                            foreach ($itemNumLabels as $key => $label) {
                                $_SESSION[$cCode]['items2_sum'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items2_sum'][$id][$key] * $_SESSION[$cCode]['items2_sum'][$id]["jml"]);
                            }
                            $_SESSION[$cCode]['items2_sum'][$id]['sub_nett'] = ($_SESSION[$cCode]['items2_sum'][$id]['nett'] * $_SESSION[$cCode]['items2_sum'][$id]['jml']);

                            $_SESSION[$cCode]['items2_sum'][$id]['subtotal'] = ($_SESSION[$cCode]['items2_sum'][$id]['jml'] * $_SESSION[$cCode]['items2_sum'][$id]['harga']);
                        }
                    }
                    if (sizeof($referenceConfig) > 0) {
                        foreach ($referenceConfig as $key => $label) {
                            $_SESSION[$cCode]['main'][$key] = $row->$label;
                        }
                    }
                }
            }
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }
        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }


        mati_disini(get_class($this));

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $referenceJenisTr = $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceJenisTr'];
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $externalConfig = isset($this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues']) ? $this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues'] : array();

        $_SESSION[$cCode]['main']['seluruhnya'] = false;

        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
            cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['items'][$id])) {
                cekBiru("ada barang, cek lokernya");
                $this->db->trans_start();

                $mdlName = $lockerConfig['mdlName'];
                $this->load->model("Mdls/" . $mdlName);

                $c = new $mdlName();
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id']);
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

        if (isset($_SESSION[$cCode]['items'][$id])) {
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
        }
        if (isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = null;
            unset($_SESSION[$cCode]['items2'][$id]);
        }

        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }

        if (isset($_SESSION[$cCode]['items'])) {
            $arrBahanJml = 0;
            foreach ($_SESSION[$cCode]['items'] as $pID => $pSpec) {
                foreach ($_SESSION[$cCode]['items2'][$pID] as $bSpec) {
                    $arrBahanJml[$bSpec['id']] += $bSpec['jml'];
                }
            }
            if (sizeof($arrBahanJml) > 0) {
                foreach ($arrBahanJml as $bID => $bQty) {
                    $_SESSION[$cCode]['items2_sum'][$bID]['jml'] = $bQty;
//                    $_SESSION[$cCode]['out_detail2_sum'][$bID]['jml'] = $bQty;
                }
            }
        }

        if (sizeof($externalConfig) > 0) {
            foreach ($externalConfig as $keyName => $arrVal) {

                if (isset($arrVal['mdlName']) && strlen($arrVal['mdlName']) > 0) {
                    $key = $keyName . "_src";
                    if (isset($_SESSION[$cCode]['main_add_fields'][$key])) {
                        $_SESSION[$cCode]['main_add_fields'][$key] = null;
                        unset($_SESSION[$cCode]['main_add_fields'][$key]);
                    }
                }

                if (isset($arrVal['taxFactor'])) {
                    $key = $keyName . "_tax";
                    if (isset($_SESSION[$cCode]['main'][$key])) {
                        $_SESSION[$cCode]['main'][$key] = null;
                        unset($_SESSION[$cCode]['main'][$key]);
                    }
                    if (isset($_SESSION[$cCode]['main'][$key])) {
                        $_SESSION[$cCode]['main'][$key] = null;
                        unset($_SESSION[$cCode]['main'][$key]);
                    }
                    if (isset($_SESSION[$cCode]['main_add_values'][$key])) {
                        $_SESSION[$cCode]['main_add_values'][$key] = null;
                        unset($_SESSION[$cCode]['main_add_values'][$key]);
                    }
                }

                $key = $keyName;
                if (isset($_SESSION[$cCode]['main'][$key])) {
                    $_SESSION[$cCode]['main'][$key] = null;
                    unset($_SESSION[$cCode]['main'][$key]);
                }
                if (isset($_SESSION[$cCode]['main'][$key])) {
                    $_SESSION[$cCode]['main'][$key] = null;
                    unset($_SESSION[$cCode]['main'][$key]);
                }
                if (isset($_SESSION[$cCode]['main_add_values'][$key])) {
                    $_SESSION[$cCode]['main_add_values'][$key] = null;
                    unset($_SESSION[$cCode]['main_add_values'][$key]);
                }


            }
        }


        if (sizeof($_SESSION[$cCode]['items']) == 0) {
            $detailResetList = array(
                "items",
                "items2",
                "items2_sum",
                "tableIn_detail",
                "tableIn_detail2",
                "tableIn_detail2_sum",
                "tableIn_detail_values",
                "tableIn_detail_values2",
                "tableIn_detail_values2_sum",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode][$sSName]);
            }
        }


//        mati_disini(__FUNCTION__);
        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

    public function edit()
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
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id']);
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


        if (isset($_SESSION[$cCode]['items'][$id])) {
            if (isset($_GET['newQty'])) {
                if ($_GET['newQty'] > $_SESSION[$cCode]['items'][$id]['jml']) {
                    $msg = "Jumlah de-assembling produk " . html_escape($_SESSION[$cCode]['items'][$id]['nama']) . " melebihi ketentuan.";
                    die(lgShowAlert($msg));
                }
                $_SESSION[$cCode]['main']['seluruhnya'] = false;

                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['hpp']);

            }

            if (sizeof($_SESSION[$cCode]['items2']) > 0) {
                $arrNewJml = array();
                foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                    foreach ($pSpec as $eSpec) {
                        if (!isset($arrNewJml[$eSpec['id']])) {
                            $arrNewJml[$eSpec['id']] = 0;
                        }
                        $arrNewJml[$eSpec['id']] += ($eSpec['jml'] * $_SESSION[$cCode]['items'][$pID]['jml']);
                    }
                }

                if (sizeof($arrNewJml) > 0) {
                    foreach ($arrNewJml as $bID => $bQty) {
                        if (!isset($_SESSION[$cCode]['items2_sum'][$bID]['jml'])) {
                            $_SESSION[$cCode]['items2_sum'][$bID]['jml'] = 0;
                        }
                        $_SESSION[$cCode]['items2_sum'][$bID]['jml'] = $bQty;
                    }
                }
            }
            else {
                if (isset($_SESSION[$cCode]['items2_sum'])) {
                    $_SESSION[$cCode]['items2_sum'] = null;
                    unset($_SESSION[$cCode]['items2_sum']);
                }

                if (isset($_SESSION[$cCode]['tableIn_detail2_sum'])) {
                    $_SESSION[$cCode]['tableIn_detail2_sum'] = null;
                    unset($_SESSION[$cCode]['tableIn_detail2_sum']);
                }

                if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum'])) {
                    $_SESSION[$cCode]['tableIn_detail_values2_sum'] = null;
                    unset($_SESSION[$cCode]['tableIn_detail_values2_sum']);
                }
            }
        }

//        mati_disini("TEST DE-ASSEMBLING...");

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
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