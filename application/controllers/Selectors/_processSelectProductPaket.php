<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
class _processSelectProductPaket extends CI_Controller
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
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $priceConfigItems = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPriceItems']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPriceItems'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['componentsAss'] : array();

        $tmpB = $b->lookupByID($id)->result();

        //-----------------------------------------
        if (isset($_SESSION[$cCode]['items'][$id])) {
//            unset($_SESSION[$cCode]['items']);
        }
        else{
            unset($_SESSION[$cCode]['items']);
        }
        //-----------------------------------------
//        arrPrintWebs($componentAssConfig);
        $arrComponents = array();
        if (sizeof($componentAssConfig) > 0) {
//            cekBiru();
            $this->load->model("Mdls/" . $componentAssConfig['model']);
            $pk = New $componentAssConfig['model']();
            $tmpPK = $pk->lookupByPID($id)->result();
            cekHitam($this->db->last_query());

            if (sizeof($tmpPK) > 0) {
                $listedItems = array();
                foreach ($tmpPK as $e => $eSpec) {
//                    cekHitam($e);
//                    arrPrint($eSpec);
                    $listedItems[$e] = $eSpec->produk_dasar_id;
                    //pair harga produk
                    $tmpItems = array(
                        "handler" => "",
                        "harga" => 0,
                        "jml" => 1,

                    );
                    if (sizeof($priceConfigItems) > 0) {

                        $mdlNameItems = $priceConfigItems['model'];
                        $this->load->model("Mdls/" . $mdlNameItems);
                        cekHitam($mdlNameItems);
                        $hItems = new $mdlNameItems();
                        $hItems->addFilter("produk_id='" . $eSpec->produk_dasar_id . "'");
                        $hItems->addFilter("status='1'");
//                        $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                        $hItems->addFilter("jenis_value in ('" . implode("','", $priceConfigItems['label']) . "')");
                        $hItems->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpHitems = $hItems->lookupAll($id)->result();
//                        cekMerah($this->db->last_query());

                        if (sizeof($tmpHitems) > 0) {
                            $rawPrices = array();
                            foreach ($tmpHitems as $hSpec) {
                                foreach ($priceConfigItems['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }
                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmpD[$k] = $v;
                                }
//                                arrPrint($tmpD);
                                $tmpItems = array(
                                    "harga" => isset($tmpD[$priceConfigItems['mainSrc']]) ? $tmpD[$priceConfigItems['mainSrc']] : 0,
                                    "harga_ori" => isset($tmpD[$priceConfigItems['mainSrc']]) ? $tmpD[$priceConfigItems['mainSrc']] : 0,
                                    "jml" => $eSpec->jml,
                                    "qty" => $eSpec->jml,
                                    "hpp" => isset($eSpec->hpp) ? $eSpec->hpp : 0,
//                                    "nett1" => isset($tmpD[$priceConfigItems['mainSrc']]) ? $tmpD[$priceConfigItems['mainSrc']] : 0,
                                    "nett1_sum" => isset($tmpD[$priceConfigItems['mainSrc']]) ? $tmpD[$priceConfigItems['mainSrc']] * $eSpec->jml : 0,


//                                    "jual" =>$tmpD["jual"],
                                );
//                                matiHEre($tmpD[$priceConfigItems['mainSrc']]);
                            }
                        }

                    }

                    //                    if (isset($eSpec->produk_dasar_id)) {
                    $fieldSrcs2 = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc2']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc2'] : array("nama" => "nama");
                    $this->load->model("Mdls/MdlProduk");
                    $pr = new MdlProduk();
                    arrPrint($listedItems);
                    foreach ($listedItems as $x => $tmpProdList) {
//                        arrPrint($tmpProdList);
//                        matiHEre();
//                        $pr->addFilter(" id in (" . implode(",", $tmpProdList) . ")");
                        $pr->addFilter("id='" . $eSpec->produk_dasar_id . "'");
                        $prodData = $pr->lookupAll()->result();
//                        cekBiru($this->db->last_query());
                        if (sizeof($prodData) > 0) {

                            foreach ($prodData as $tempData) {
//                                arrPrint($tempData);
//                                $tmpData = array();
                                foreach ($tempData as $r => $s) {
                                    $tmpItems[$r] = $s;
                                }
                                foreach ($fieldSrcs2 as $key => $src) {
                                    $tmpItems[$key] = makeValue($src, $tmpItems, $tmpItems, $tempData->$key);
                                }
                            }
                        }
                        $arrComponents[$id][$e] = $tmpItems;
//                    arrPrint($listedItems);
//                    matiHere();

                    }

//                        cekHijau($this->db->last_query());
//                    arrPrint($prodData);
                }

            }
            else {
                $arrComponents[$id] = array();
            }
        }
        if (sizeof($arrComponents[$id]) == 0) {
            die(lgShowAlert("komposisi paket belum  didefine."));
        }
//arrPrint($arrComponents);
//        matiHere("hoopp");

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
//                arrPrint($row);
                $rows = $row;
                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $nama = isset($row->nama) > 0 ? $row->nama : "n/a";
                $tmpJml = 1;

//                arrPrint($_SESSION[$cCode]['pair']);
//                matiHere();

                if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                    cekMerah("masuk locker config");

                    $mdlName = $lockerConfig['mdlName'];
                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
//                    $c->addFilter("id='$id'");//==id locker
                    $c->addFilter("state='active'");
                    $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                    $tmpC = $c->lookupAll($id)->result();
                    cekHere($this->db->last_query());

//                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                    if (sizeof($tmpC) > 0) {
                        arrPrint($tmpC);
                        foreach ($tmpC as $row) {
                            $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                            $nama = $row->nama;

                            $jml_now = $row->jumlah;
                            if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                $jml_sudah_diambil = 0;
                                $jml_diperlukan = 1;
                                $jml_nambah = 1;
                            }
                            else {
                                if (isset($_GET['newQty'])) {
                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                    $jml_diperlukan = $_GET['newQty'];
                                    $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                }
                                else {
                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                    $jml_diperlukan = $jml_sudah_diambil + $jml;
                                    $jml_nambah = $jml;
                                }
                            }
                            //  region validasi stok
                            if ($jml_nambah > $jml_now) {
                                echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                echo "</script>";
                                die();
                            }
                            //  endregion validasi stok


                            $this->db->trans_start();

                            //  region update locker active
                            $where = array(
                                "id" => $row->id,
                            );
                            $data_active = array(
                                "jumlah" => $jml_now - $jml_nambah,
                                "state" => "active",
                            );
                            $c->updateData($where, $data_active);
                            cekHere($this->db->last_query());
                            //  endregion update locker active


                            //  region locker hold
                            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
//                            arrPrint($array_hold_sebelumnya);
//                            mati_disini();
                            if (sizeof($array_hold_sebelumnya) > 0) {
                                $where = array(
                                    "id" => $array_hold_sebelumnya['id'],
                                );
                                $data_hold = array(
                                    "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                );
                                $c->updateData($where, $data_hold);
                                cekHere($this->db->last_query());
                            }
                            else {
                                $data_hold = array(
                                    "jenis" => "produk",
                                    "cabang_id" => $this->session->login['cabang_id'],
                                    "produk_id" => $id,
                                    "nama" => $nama,
                                    "satuan" => $row->satuan,
                                    "state" => "hold",
                                    "jumlah" => $jml_nambah,
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "gudang_id" => $this->session->login['gudang_id'],
                                );
                                $c->addData($data_hold);
                                cekHere($this->db->last_query());
                            }
                            //  endregion locker hold

                            $this->db->trans_complete() or die("Gagal bro");

                            $tmpJml = $jml_diperlukan;

                        }
                    }
                    else {
                        mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                    }

                }

                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
//                        $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();
                        cekMerah($this->db->last_query());
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }
                            }
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
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $rows->$src);
                    }

                    //region perhitungan subtotal
                    if ($subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $subtotal = 0;
                    }
                    $tmp["subtotal"] = $subtotal;
                    //endregion

                    $tmp["harga_disc"] = $tmp["harga"];
                    $_SESSION[$cCode]['items'][$id] = $tmp;//mati dulu


                    //region items2
                    $tmpRslt = $arrComponents[$id];
                    $fieldSrcs2 = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc2']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc2'] : array("nama" => "nama");

                    if (sizeof($arrComponents) > 0) {
                        $subtotal_nett1 = 0;
                        unset($_SESSION[$cCode]['items2']);
                        unset($_SESSION[$cCode]['items2_sum']);
                        foreach ($arrComponents[$id] as $iter => $iterArray) {
                            $subtotal_nett1 += $iterArray["nett1_sum"];
                            arrPrint($iterArray);

//                            $percent_price = $iterArray["nett1"]/$subtotal_nett1;
                        }
//                        cekHitam($subtotal_nett1);
//                        matiHEre();
                        foreach ($arrComponents[$id] as $iter => $iterArray) {

                            $percent_price = $iterArray["nett1_sum"] / $subtotal_nett1;
//                            $subtotal =  $_SESSION[$cCode]['items'][$id]["harga"]*$percent_price;
                            $subtotal_price_items2 = $tmp["harga"] * $percent_price;

                            $jual = $subtotal_price_items2 / $iterArray['jml'];
                            $dic_value = $iterArray['harga'] - $jual;
                            $harganet = $iterArray['harga'] - $dic_value;
                            $ppn = $jual * 10 / 100;
                            $jml = $iterArray['jml'];
                            $subppn = $ppn * $jml;
                            $subnet1 = $harganet * $jml;
                            $subnet2 = ($harganet + $ppn) * $jml;
                            $subharga = $jml * $iterArray['harga_ori'];
//                            matiHere();
//                            $tmpRslt[$iter]["harga"] =$iterArray['harga'];
                            $tmpRslt[$iter]["percent_price"] = $percent_price;
//                            $tmpRslt[$iter]["subtotal"] = $subtotal_price_items2;
                            $tmpRslt[$iter]["subtotal"] = $iterArray["nett1_sum"];
//                            $tmpRslt[$iter]["harga"] = $jual;
                            $tmpRslt[$iter]["harga"] = $iterArray["harga"];
                            $tmpRslt[$iter]["jual"] = $jual;
                            $tmpRslt[$iter]["nett1"] = $iterArray['harga'] - $dic_value;
                            $tmpRslt[$iter]["nett2"] = $harganet + $ppn;
//                            $tmpRslt[$iter]["nett1"] = $iterArray['harga'];
//                            $tmpRslt[$iter]["disc_value"] = $tmp["harga"] -($subtotal_price_items2/$iterArray['jml']);
                            $tmpRslt[$iter]["disc"] = $dic_value;
                            $tmpRslt[$iter]["disc_percent"] = $dic_value / $iterArray['harga'] * 100;
                            $tmpRslt[$iter]["jual_nppn"] = $jual + $ppn;
                            $tmpRslt[$iter]["ppn"] = $ppn;
                            $tmpRslt[$iter]["produk_id"] = $iterArray["id"];
                            $tmpRslt[$iter]["qty"] = $iterArray['jml'];
                            $tmpRslt[$iter]["produk_nama"] = $iterArray["nama"];
                            $tmpRslt[$iter]["produk_ord_jml"] = $iterArray['jml'];
                            $tmpRslt[$iter]["harga_ori"] = $iterArray['harga'];
                            $tmpRslt[$iter]["produk_ord_hrg"] = $iterArray["harga"];
                            $tmpRslt[$iter]["produk_kode"] = isset($iterArray['kode']) ? $iterArray['kode'] : "";
                            $tmpRslt[$iter]["produk_label"] = isset($iterArray['label']) ? $iterArray['label'] : "";
                            $tmpRslt[$iter]["valid_qty"] = $iterArray['jml'];
                            $tmpRslt[$iter]["produk_jenis"] = "produk";
                            $tmpRslt[$iter]["sub_ppn"] = $subppn;
                            $tmpRslt[$iter]["sub_harga"] = $subharga;
                            $tmpRslt[$iter]["sub_nett1"] = $subnet1;
                            $tmpRslt[$iter]["sub_nett2"] = $subnet2;
//                            $tmpRslt[$iter]["pihakName"] = "produk";
                            cekHere("harga" . $iterArray['harga'] . "percenprice " . $percent_price . " jual " . $jual . " disc value " . $dic_value . "disc%" . $dic_value / $iterArray['harga'] * 100);
//arrPrint($tmpRslt);
//matiHEre();
                        }

                    }
//                    arrPrint($tmpRslt);
//matiHEre();
                    $_SESSION[$cCode]['items2'][$id] = $tmpRslt;
                    //endregion
                }
                else {
                    if (isset($_GET['newQty'])) {
                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);

                        if (sizeof($arrComponents) > 0) {
                            $subtotal_nett1 = 0;
                            foreach ($arrComponents[$id] as $iter => $iterArray0) {
                                $subtotal_nett1 += $iterArray0["nett1"];
                            }


                        }
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                    }
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

                        $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['harga'] * $_SESSION[$cCode]['items'][$id]['jml']);
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                    }

                    //----------------------------------------------------
//                    if (isset($_GET['disc_percent']) && ($_GET['disc_percent'] >= 0)) {
                    if (isset($_GET['disc_percent'])) {
                        $_SESSION[$cCode]['items'][$id]['harga_orig'] = $_SESSION[$cCode]['items'][$id]['harga'];
                        $itemDisc = ($_GET['disc_percent'] / 100) * $_SESSION[$cCode]['items'][$id]['harga_orig'];
                        $itemHargaDisc = $_SESSION[$cCode]['items'][$id]['harga_orig'] - $itemDisc;

                        //---------------------------------------------
                        foreach ($fieldSrcs as $key => $src) {
                            $_SESSION[$cCode]['items'][$id][$key] = makeValue($src, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        $_SESSION[$cCode]['items'][$id]['harga_disc'] = $itemHargaDisc;
                        $_SESSION[$cCode]['items'][$id]['disc'] = $itemDisc;

                        foreach ($itemNumLabels as $key => $label) {
                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                        }

                        $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['harga'] * $_SESSION[$cCode]['items'][$id]['jml']);
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga_disc']);
                        //---------------------------------------------

                        $tmpRslt = $arrComponents[$id];
                        $fieldSrcs2 = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc2']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc2'] : array("nama" => "nama");
                        if (sizeof($arrComponents) > 0) {
                            $subtotal_nett1 = 0;
//                            unset($_SESSION[$cCode]['items2']);
//                            unset($_SESSION[$cCode]['items2_sum']);
                            foreach ($arrComponents[$id] as $iter => $iterArray) {
                                $subtotal_nett1 += $iterArray["nett1_sum"];
//                                arrPrint($iterArray);

//                            $percent_price = $iterArray["nett1"]/$subtotal_nett1;
                            }
                            cekHitam($subtotal_nett1);

                            foreach ($arrComponents[$id] as $iter => $iterArray) {

                                $percent_price = $iterArray["nett1_sum"] / $subtotal_nett1;
                                $subtotal_price_items2 = $itemHargaDisc * $percent_price;
                                cekHere(":: $itemHargaDisc :: $subtotal_price_items2 :: $percent_price :: $subtotal_nett1 :: " . $iterArray["nett1_sum"]);
                                $jual = $subtotal_price_items2 / $iterArray['jml'];
                                $dic_value = $iterArray['harga'] - $jual;
                                $harganet = $iterArray['harga'] - $dic_value;
                                $ppn = $jual * 10 / 100;
                                $jml = $iterArray['jml'];
                                $subppn = $ppn * $jml;
                                $subnet1 = $harganet * $jml;
                                $subnet2 = ($harganet + $ppn) * $jml;
                                $subharga = $jml * $iterArray['harga_ori'];

                                $tmpRslt[$iter]["percent_price"] = $percent_price;
                                $tmpRslt[$iter]["subtotal"] = $iterArray["nett1_sum"];
                                $tmpRslt[$iter]["harga"] = $iterArray["harga"];
                                $tmpRslt[$iter]["jual"] = $jual;
                                $tmpRslt[$iter]["nett1"] = $iterArray['harga'] - $dic_value;
                                $tmpRslt[$iter]["nett2"] = $harganet + $ppn;
                                $tmpRslt[$iter]["disc"] = $dic_value;
                                $tmpRslt[$iter]["disc_percent"] = $dic_value / $iterArray['harga'] * 100;
                                $tmpRslt[$iter]["jual_nppn"] = $jual + $ppn;
                                $tmpRslt[$iter]["ppn"] = $ppn;
                                $tmpRslt[$iter]["produk_id"] = $iterArray["id"];
                                $tmpRslt[$iter]["qty"] = $iterArray['jml'];
                                $tmpRslt[$iter]["produk_nama"] = $iterArray["nama"];
                                $tmpRslt[$iter]["produk_ord_jml"] = $iterArray['jml'];
                                $tmpRslt[$iter]["harga_ori"] = $iterArray['harga'];
                                $tmpRslt[$iter]["produk_ord_hrg"] = $iterArray["harga"];
                                $tmpRslt[$iter]["produk_kode"] = isset($iterArray['kode']) ? $iterArray['kode'] : "";
                                $tmpRslt[$iter]["produk_label"] = isset($iterArray['label']) ? $iterArray['label'] : "";
                                $tmpRslt[$iter]["valid_qty"] = $iterArray['jml'];
                                $tmpRslt[$iter]["produk_jenis"] = "produk";
                                $tmpRslt[$iter]["sub_ppn"] = $subppn;
                                $tmpRslt[$iter]["sub_harga"] = $subharga;
                                $tmpRslt[$iter]["sub_nett1"] = $subnet1;
                                $tmpRslt[$iter]["sub_nett2"] = $subnet2;

                                cekHere("harga" . $iterArray['harga'] . "percenprice " . $percent_price . " jual " . $jual . " disc value " . $dic_value . "disc%" . $dic_value / $iterArray['harga'] * 100);
                            }

                        }
                        $_SESSION[$cCode]['items2'][$id] = $tmpRslt;

                    }

                    //----------------------------------------------------

                    if (isset($_SESSION[$cCode]['items2'][$id]) && sizeof($_SESSION[$cCode]['items2'][$id]) > 0) {
                        foreach ($_SESSION[$cCode]['items2'][$id] as $e => $eSpec) {
                            $_SESSION[$cCode]['items2'][$id][$e]['jml'] = isset($arrComponents[$id][$e]['jml']) ? ($arrComponents[$id][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['qty'] = isset($arrComponents[$id][$e]['jml']) ? ($arrComponents[$id][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['valid_qty'] = isset($arrComponents[$id][$e]['jml']) ? ($arrComponents[$id][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['produk_ord_jml'] = isset($arrComponents[$id][$e]['jml']) ? ($arrComponents[$id][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['sub_ppn'] = isset($eSpec['sub_ppn']) ? ($eSpec['ppn'] * ($_SESSION[$cCode]['items'][$id]['jml'] * $arrComponents[$id][$e]['jml'])) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['sub_harga'] = isset($eSpec['sub_harga']) ? ($eSpec['harga'] * ($_SESSION[$cCode]['items'][$id]['jml'] * $arrComponents[$id][$e]['jml'])) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['sub_nett1'] = isset($eSpec['sub_nett1']) ? ($eSpec['nett1'] * ($_SESSION[$cCode]['items'][$id]['jml'] * $arrComponents[$id][$e]['jml'])) : 0;
                            $_SESSION[$cCode]['items2'][$id][$e]['sub_nett2'] = isset($eSpec['sub_nett2']) ? ($eSpec['nett2'] * ($_SESSION[$cCode]['items'][$id]['jml'] * $arrComponents[$id][$e]['jml'])) : 0;

                        }
                    }
                }
            }
//            arrPrint($_SESSION[$cCode]['items2']);

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;

                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);

                }
            }

            if (sizeof($_SESSION[$cCode]['items2']) > 0) {
                cekBiru("bulding summary item_result...");
                $_SESSION[$cCode]['items2_sum'] = array();
                foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                    foreach ($pSpec as $eSpec) {
                        if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['qty'] = 0;
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();

                        }

                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['qty'] += $eSpec['jml'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;

                    }
                }
            }

            if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                    $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = isset($pSpec['produk_ids']) ? blobEncode($pSpec['produk_ids']) : "";
                }
            }
        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }


//mati_disini(":: SAMPAI BAWAH ::");
        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

    public function multiSelect()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $items = $_GET['items'];

        $arrItems = isset($_GET['items']) ? unserialize(base64_decode($items)) : array();
        $arrTrID = isset($_GET['trs']) ? unserialize(base64_decode($_GET['trs'])) : array();


        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
                cekHere($this->db->last_query());
                arrPrint($tmpB);

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                            cekMerah("masuk locker config");

                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
                            cekHere($this->db->last_query());


                            if (sizeof($tmpC) > 0) {
                                arrPrint($tmpC);
                                foreach ($tmpC as $row) {
                                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                    $nama = $row->nama;

                                    $jml_now = $row->jumlah;
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                        $jml_sudah_diambil = 0;
                                        $jml_diperlukan = 1;
                                        $jml_nambah = 1;
                                    }
                                    else {
                                        if (isset($_GET['newQty'])) {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $_GET['newQty'];
                                            $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                        }
                                        else {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $jml_sudah_diambil + $jml;
                                            $jml_nambah = $jml;
                                        }
                                    }
                                    //  region validasi stok
                                    if ($jml_nambah > $jml_now) {
                                        echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                        echo "</script>";
                                        die();
                                    }
                                    //  endregion validasi stok


                                    $this->db->trans_start();

                                    //  region update locker active
                                    $where = array(
                                        "id" => $row->id,
                                    );
                                    $data_active = array(
                                        "jumlah" => $jml_now - $jml_nambah,
                                        "state" => "active",
                                    );
                                    $c->updateData($where, $data_active);
                                    cekHere($this->db->last_query());
                                    //  endregion update locker active


                                    //  region locker hold
                                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                    if (sizeof($array_hold_sebelumnya) > 0) {
                                        $where = array(
                                            "id" => $array_hold_sebelumnya['id'],
                                        );
                                        $data_hold = array(
                                            "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                        );
                                        $c->updateData($where, $data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    else {
                                        $data_hold = array(
                                            "jenis" => "produk",
                                            "cabang_id" => $this->session->login['cabang_id'],
                                            "produk_id" => $id,
                                            "nama" => $nama,
                                            "satuan" => $row->satuan,
                                            "state" => "hold",
                                            "jumlah" => $jml_nambah,
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "gudang_id" => $this->session->login['gudang_id'],
                                        );
                                        $c->addData($data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    //  endregion locker hold


                                    $this->db->trans_complete() or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;

                                }
                            }
                            else {
                                mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                            }

                        }

                        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "subtotal" => 0,
                            );

                            if (sizeof($priceConfig) > 0) {
                                $mdlName = $priceConfig['model'];
                                $this->load->model("Mdls/" . $mdlName);
                                $h = new $mdlName();
                                $h->addFilter("produk_id='$id'");
                                $h->addFilter("status='1'");
//                                $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                                $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                                $tmpH = $h->lookupAll($id)->result();
                                cekMerah($this->db->last_query());
                                if (sizeof($tmpH) > 0) {
                                    $rawPrices = array();
                                    foreach ($tmpH as $hSpec) {
                                        foreach ($priceConfig['key_label'] as $key => $val) {
                                            if ($key == $hSpec->jenis_value) {
                                                $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                            }
                                        }
                                    }
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
                                $tmpEx = $cal->multiExplode($src);
                                arrPrint($tmpEx);
                                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                                    cekBiru("$key perhitungan");
                                    $newSrc = $src;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        echo "$key2 - $val2 <br>";
                                        if (!is_numeric($val2)) {
                                            if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            }
                                            else {
                                                $newSrc = str_replace($val2, 0, $newSrc);
                                            }
                                        }
//                                else {
//                                    if (isset($_SESSION[$cCode]['out_master'][$val2]) && $_SESSION[$cCode]['out_master'][$val2] > 0) {
//                                        $newSrc = str_replace($val2, $_SESSION[$cCode]['out_master'][$val2], $newSrc);
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

                            //===perhitungan subtotal
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
                            $_SESSION[$cCode]['items'][$id] = $tmp;
//                    die();
                        }
                        else {
                            if (isset($_GET['newQty'])) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                            else {
                                $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }

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
                    }

                    if (sizeof($_SESSION[$cCode]['items']) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;
//                        $_SESSION[$cCode]['out_master']['harga'] = 0;
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                            $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        }
                    }

                }
                else {
                    cekMerah("tidak ada itemnya!");
                    die();
                }

            }
        }

        if (sizeof($arrTrID) > 0) {
            $_SESSION[$cCode]['main']['references'] = $arrTrID;
//            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }

//        mati_disini();
//        die();
        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart');";
        // echo "top.document.getElementById('tr_".$id."').style.background='#ffff00';";
        echo "</script>";
    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();


        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {

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

        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }


        if (isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = null;
            unset($_SESSION[$cCode]['items2'][$id]);
        }

        $_SESSION[$cCode]['items2_sum'] = array();
        $_SESSION[$cCode]['tableIn_detail2_sum'] = array();
        $_SESSION[$cCode]['tableIn_detail_values2_sum'] = array();
        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $eSpec) {

                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                    }
                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];

                }
            }
        }
        else {
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


        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart')";
        echo "</script>";
    }

    public function updateValues()
    {
        echo "---------------------------your input params needed------------------------------";
        arrprint($_POST);
        $cCode = "_TR_" . $this->jenisTr;
        $rawParam = $_POST['param'];
        arrPrint($rawParam);
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }
}