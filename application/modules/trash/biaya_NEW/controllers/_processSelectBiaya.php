<?php

require_once "Modul_Controller.php";

class _processSelectBiaya extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;;

    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {

                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
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

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {

                    $exception_id = $row->allowed_ext;//untuk menjaga supaya biaya bank tidak dicampur dengan biaya lain kakrena berelasi denga rekening bank
                    if (count($_SESSION[$cCode]['items']) > 0 && $exception_id == 1) {
                        $msg = $row->nama . "  tidak dapat di gabung dengan biaya lain karen melibatkan rekening bank ";
                        die(lgShowAlert($msg));
                    }
                    else {
                        if ($_SESSION[$cCode]["main"]["allowed_ext"] == 1) {
                            $msg = $row->nama . "  tidak dapat di gabung dengan biaya bank karen melibatkan rekening bank, silahkan bersihkan shopingcart terlebih dahulu dan pilih ulang " . $row->nama;
                            die(lgShowAlert($msg));
                        }

                    }
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "nilai_untung" => 0,
                        "nilai_rugi" => 0,
                        "nilai_final_rugilaba" => 0,
//                        "txt_rugilaba" => "kerugian",
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
//                        $h->addFilter("jenis_value in (" . implode(",", $priceConfig['label']) . ")");
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
                                $tmp['harga_perolehan'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }
                    if (sizeof($priceConfig2) > 0) {
                        $mdlName = $priceConfig2['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("state='active'");
                        $h->addFilter("jenis in ('" . implode("','", $priceConfig2['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();

//                        cekHere("masuk sini bro #2, LINE => " . __LINE__);
//                        cekMerah($this->db->last_query());

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig2['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga_sisa_tmp'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga_sisa'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                            }
                        }

                    }

//                    arrPrint($fieldSrcs);
                    foreach ($fieldSrcs as $key => $src) {
//                        cekHere($row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }

//matiHere();
                    //===perhitungan subtotal
//                    $this->load->library("FieldCalculator");
//                    $cal = new FieldCalculator();


//                    if ($subAmountConfig != null) {
//                        $tmpEx = $cal->multiExplode($subAmountConfig);
//                        if (sizeof($tmpEx) > 1) {
//                            $newSrc = $subAmountConfig;
//                            foreach ($tmpEx as $key2 => $val2) {
//                                if (isset($tmp[$val2])) {
//                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                    cekKuning("$val2 direplace dengan " . $tmp[$val2]);
//                                } else {
//                                    $newSrc = str_replace($val2, "0", $newSrc);
//                                    cekKuning("$val2 direplace dengan NOL");
//                                }
//
//                            }
//                            $subtotal = $cal->calculate($newSrc);
//                            cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");
//
//                        } else {
//                            $subtotal = 0;
//                            cekHijau("subtotal dari perhitungan yang gak ada");
//                        }
//                    } else {
//                        $subtotal = 0;
//                        cekHijau("subtotal NOL");
//                    }
                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums... *** ");
                        echo("LINE " . __LINE__);
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }
                        }

                        if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
                            }
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
                            $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);

                        }

//                        foreach ($itemNumLabels as $key => $label) {
//                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
//                        }
//                        $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));

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

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }

                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
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
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
//        matiHEre(__LINE__ . " || HAHAHAHA");

        switch($this->jenisTr){
            case "1677":
                if ($_SESSION[$cCode]["main"]["allowed_ext"] == 1) {

                    echo "<script>";
                    echo "  window.onload = function() {";
                    echo "  if(top.document.getElementById('data_cabang_pos')){";
                    echo "  top.document.getElementById(\"data_cabang_pos\").classList.replace(\"hidden\", \"block\");";
                    echo "  }";
                    echo "  if(top.document.getElementById('master_pilihan_main')){";
                    echo "  top.document.getElementById(\"master_pilihan_main\").classList.replace(\"hidden\", \"block\");";
                    echo "  }";
                    echo "  if(top.document.getElementById('pilihan_main')){";
                    echo "  top.document.getElementById(\"pilihan_main\").classList.replace(\"hidden\", \"block\");";
                    echo "  }";
                    echo "  };";
                    echo "</script>";
                }
                break;
        }


        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function multiSelect()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $items = $_GET['items'];

        $arrItems = isset($_GET['items']) ? unserialize(base64_decode($items)) : array();
        $arrTrID = isset($_GET['trs']) ? unserialize(base64_decode($_GET['trs'])) : array();
        $arrMain = isset($_GET['main']) ? unserialize(base64_decode($_GET['main'])) : array();

        $cCode = $this->cCode;;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
                // cekHere($this->db->last_query());
                // arrPrint($tmpB);

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
                            // cekHere($this->db->last_query());


                            if (sizeof($tmpC) > 0) {
                                // arrPrint($tmpC);
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
                                    // cekHere($this->db->last_query());
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
                                        // cekHere($this->db->last_query());
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

                        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
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
                                // arrPrint($tmpEx);
                                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                                    // cekBiru("$key perhitungan");
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

                                    }
                                    // cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
                                    $tmp[$key] = $cal->calculate($newSrc);
                                }
                                else {
                                    // cekBiru("$key BUKAN perhitungan");
                                    $tmp[$key] = $row->$src;
                                }


                            }

                            //===perhitungan subtotal
                            $cal = new FieldCalculator();


                            if (sizeof($arrMain) > 0) {
                                foreach ($arrMain as $key => $val) {
                                    $_SESSION[$cCode][$key] = $val;
                                }
                            }

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
                                    // cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                                }
                                else {
                                    $subtotal = 0;
                                    // cekHijau("subtotal dari perhitungan yang gak ada");
                                }
                            }
                            else {
                                $subtotal = 0;
                                // cekHijau("subtotal NOL");
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
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
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
        }
        if (isset($_GET['singleRefID']) && strlen($_GET['singleRefID']) > 0) {
            $_SESSION[$cCode]['main']['singleReference'] = $_GET['singleRefID'];
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
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
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

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = $this->cCode;;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();


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

//        die();
        if (isset($_SESSION[$cCode]['items'][$id])) {
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
            $_SESSION[$cCode]['out_detail'][$id] = null;
            unset($_SESSION[$cCode]['out_detail'][$id]);
            $_SESSION[$cCode]['out_detail2'][$id] = null;
            unset($_SESSION[$cCode]['out_detail2'][$id]);
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }
//        if (sizeof($_SESSION[$cCode]['items']) < 1) {
//            $_SESSION[$cCode] = null;
//            unset($_SESSION[$cCode]);
//        }

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
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
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
        echo "---------------------------your input params needed------------------------------";
        arrprint($_POST);
        $cCode = $this->cCode;;
        $rawParam = $_POST['param'];
        arrPrint($rawParam);
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }


    public function selectInv()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $tmpB = $b->lookupByID($id)->result();


        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
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

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                $shoppingCartFieldSrcAdditional = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional'] : array("nama" => "nama");
                $shoppingCartFieldSrcAdditional2 = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional2']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional2'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "nilai_untung" => 0,
                        "nilai_rugi" => 0,
                        "nilai_final_rugilaba" => 0,
//                        "txt_rugilaba" => "kerugian",
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
//                        $h->addFilter("jenis_value in (" . implode(",", $priceConfig['label']) . ")");
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
                                $tmp['harga_perolehan'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }
                    if (sizeof($priceConfig2) > 0) {
                        $mdlName = $priceConfig2['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("state='active'");
                        $h->addFilter("jenis in ('" . implode("','", $priceConfig2['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();

//                        cekHere("masuk sini bro #2, LINE => " . __LINE__);
//                        cekMerah($this->db->last_query());

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig2['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga_sisa_tmp'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga_sisa'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                            }
                        }

                    }

                    foreach ($fieldSrcs as $key => $src) {
//                        cekHere($row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }

                    //--------
                    $this->load->model("MdlTransaksi");
                    $trr = New MdlTransaksi();
                    $trr->setJointSelectFields("main, items");
                    $trr->setFilters(array());
                    $trr->addFilter("transaksi_id='$id'");
                    $trrReg = $trr->lookupDataRegistries()->result();
                    showLast_query("biru");
                    $mainreg = blobDecode($trrReg[0]->main);
                    $itemreg = blobDecode($trrReg[0]->items);
                    //--------

//                    $referenceNumber_current_explode = substr($main["referenceNumber_current"], 0, 4);
                    $referenceNumber_current_explode = explode(".", $mainreg["referenceNumber_current"]);
//                    arrPrint($referenceNumber_current_explode);
//                    arrPrint($shoppingCartFieldSrcAdditional);
//                    arrPrint($shoppingCartFieldSrcAdditional2);
//
//                    mati_disini(__LINE__);
                    switch ($referenceNumber_current_explode[0]) {
                        case "5822spd":
                        case "5823spd":
                            foreach ($shoppingCartFieldSrcAdditional2 as $rkey => $rval) {
//                                $tmp[$rkey] = isset($mainreg[$rval]) ? $mainreg[$rval] : "";
                                $tmp[$rkey] = makeValue($rval, $mainreg, $mainreg, 0);
                            }
                            break;
                        case "4464":
//                            if($fulldate < "2024-03-01"){
                            if (isset($mainreg["new_net3"]) && ($mainreg["new_net3"] > 0)) {
                                cekMerah("ATAS MASUK DISINI...(ada new_net3, inv lawas)");
                                $shoppingCartFieldSrcAdditional = array(
                                    "inv_new_net3" => "new_net3",
                                    "inv_grand_ppn" => "grand_ppn",
                                    "inv_new_net1" => "new_net1",
                                    "inv_dpp_pengganti" => "dpp_pengganti",
                                );
                                foreach ($shoppingCartFieldSrcAdditional as $rkey => $rval) {
                                    $tmp[$rkey] = isset($mainreg[$rval]) ? $mainreg[$rval] : "";
                                }
                            }
                            else {
                                cekMerah("BAWAH MASUK DISINI...");
                                foreach ($shoppingCartFieldSrcAdditional as $rkey => $rval) {
                                    foreach ($itemreg as $itemSpec) {
                                        if (!isset($tmp[$rkey])) {
                                            $tmp[$rkey] = 0;
                                        }
                                        $tmp[$rkey] += makeValue($rval, $itemSpec, $itemSpec, 0);
                                    }
                                }
                            }
                            break;
                        case "7499":
                            foreach ($shoppingCartFieldSrcAdditional2 as $rkey => $rval) {
//                                $tmp[$rkey] = isset($mainreg[$rval]) ? $mainreg[$rval] : "";
                                $tmp[$rkey] = makeValue($rval, $mainreg, $mainreg, 0);
                            }
                            break;
                    }

//                    foreach ($shoppingCartFieldSrcAdditional as $rkey => $rval) {
//                        $tmp[$rkey] = isset($mainreg[$rval]) ? $mainreg[$rval] : "";
//                        foreach ($itemreg as $itemSpec) {
//                            if (!isset($tmp[$rkey])) {
//                                $tmp[$rkey] = 0;
//                            }
//                            $tmp[$rkey] += makeValue($rval, $itemSpec, $itemSpec, 0);
//                        }
//                    }
                    //--------
                    //arrPrintCyan($tmp);
                    //mati_disini(__LINE__);
                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {

                    if (sizeof($itemNumLabels) > 0) {

                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                    echo "replacing value for $key with " . $newValue . "<br>";
                                }
                            }
                        }
                        else {
                            if (isset($_GET["harga"]) && ($_GET["harga"] > 0)) {
                                $tarif_pph23 = $_SESSION[$cCode]['items'][$id]["pph23Methode__tarif"];
                                $nilai_pph23 = ($tarif_pph23 / 100) * $_GET["harga"];
                                $_SESSION[$cCode]['items'][$id]["nilai_pph23"] = $nilai_pph23;
                                $_SESSION[$cCode]['items'][$id]["harga"] = $_GET["harga"];
                                $_SESSION[$cCode]['items'][$id]["nilai_kas_cn"] = $_GET["harga"] - $nilai_pph23;

                            }
                            elseif (isset($_GET["nilai_kas_cn"]) && ($_GET["nilai_kas_cn"] > 0)) {
                                $tarif_pph23 = $_SESSION[$cCode]['items'][$id]["pph23Methode__tarif"];
                                $biaya_cashback = (100 / (100 - $tarif_pph23)) * $_GET["nilai_kas_cn"];
                                $nilai_pph23 = ($tarif_pph23 / 100) * $biaya_cashback;
                                $_SESSION[$cCode]['items'][$id]["nilai_pph23"] = $nilai_pph23;
                                $_SESSION[$cCode]['items'][$id]["harga"] = $biaya_cashback;
                                $_SESSION[$cCode]['items'][$id]["nilai_kas_cn"] = $_GET["nilai_kas_cn"];
                            }
                        }


                        if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
                            }
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
                            $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);

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

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }

                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
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
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
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

    public function selectProject()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $tmpB = $b->lookupByID($id)->result();


        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
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

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                $shoppingCartFieldSrcAdditional = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional'] : array("nama" => "nama");
                $shoppingCartFieldSrcAdditional2 = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional2']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrcAdditional2'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "nilai_untung" => 0,
                        "nilai_rugi" => 0,
                        "nilai_final_rugilaba" => 0,
//                        "txt_rugilaba" => "kerugian",
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
//                        $h->addFilter("jenis_value in (" . implode(",", $priceConfig['label']) . ")");
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
                                $tmp['harga_perolehan'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }
                    if (sizeof($priceConfig2) > 0) {
                        $mdlName = $priceConfig2['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("state='active'");
                        $h->addFilter("jenis in ('" . implode("','", $priceConfig2['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();

//                        cekHere("masuk sini bro #2, LINE => " . __LINE__);
//                        cekMerah($this->db->last_query());

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig2['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga_sisa_tmp'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga_sisa'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                            }
                        }

                    }

                    foreach ($fieldSrcs as $key => $src) {
                        cekHere($key . "->" . $row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    arrPrint($tmp);

                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
//                    matiHere(__LINE__);
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {

                    if (sizeof($itemNumLabels) > 0) {

                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                    echo "replacing value for $key with " . $newValue . "<br>";
                                }
                            }
                        }
                        else {
                            if (isset($_GET["harga"]) && ($_GET["harga"] > 0)) {
                                $tarif_pph23 = $_SESSION[$cCode]['items'][$id]["pph23Methode__tarif"];
                                $nilai_pph23 = ($tarif_pph23 / 100) * $_GET["harga"];
                                $_SESSION[$cCode]['items'][$id]["nilai_pph23"] = $nilai_pph23;
                                $_SESSION[$cCode]['items'][$id]["harga"] = $_GET["harga"];
                                $_SESSION[$cCode]['items'][$id]["nilai_kas_cn"] = $_GET["harga"] - $nilai_pph23;

                            }
                            elseif (isset($_GET["nilai_kas_cn"]) && ($_GET["nilai_kas_cn"] > 0)) {
                                $tarif_pph23 = $_SESSION[$cCode]['items'][$id]["pph23Methode__tarif"];
                                $biaya_cashback = (100 / (100 - $tarif_pph23)) * $_GET["nilai_kas_cn"];
                                $nilai_pph23 = ($tarif_pph23 / 100) * $biaya_cashback;
                                $_SESSION[$cCode]['items'][$id]["nilai_pph23"] = $nilai_pph23;
                                $_SESSION[$cCode]['items'][$id]["harga"] = $biaya_cashback;
                                $_SESSION[$cCode]['items'][$id]["nilai_kas_cn"] = $_GET["nilai_kas_cn"];
                            }
                        }


                        if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
                            }
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
                            $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);

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

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }

                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
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
//matiHere(__LINE__);
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
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
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

    public function selectLogamMulia()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
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

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "nilai_untung" => 0,
                        "nilai_rugi" => 0,
                        "nilai_final_rugilaba" => 0,
//                        "txt_rugilaba" => "kerugian",
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
//                        $h->addFilter("jenis_value in (" . implode(",", $priceConfig['label']) . ")");
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
                                $tmp['harga_perolehan'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }
                    if (sizeof($priceConfig2) > 0) {
                        $mdlName = $priceConfig2['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("state='active'");
                        $h->addFilter("jenis in ('" . implode("','", $priceConfig2['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();

//                        cekHere("masuk sini bro #2, LINE => " . __LINE__);
//                        cekMerah($this->db->last_query());

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig2['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga_sisa_tmp'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga_sisa'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                            }
                        }

                    }

                    foreach ($fieldSrcs as $key => $src) {
//                        cekHere($row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }

                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
                    if (isset($_GET['newQty'])) {
                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['qty'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                    }
                    if (sizeof($itemNumLabels) > 0) {
//                        echo("iterating subNums... *** ");
//                        echo("LINE " . __LINE__);
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                            }
                        }

                        if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
                            }
                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
                            $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);

                        }
//                        foreach ($itemNumLabels as $key => $label) {
//                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
//                        }
//                        $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
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

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }

                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
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
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
//        matiHEre(__LINE__ . " || HAHAHAHA");

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function blockedSelect()
    {

        //untuk buka modalnya
//        arrPrint($this->uri->segment_array());
//        cekHitam($this->modul);
        $arrList = array(
            "nomer" => " invoice",
        );

        //skema onclick

        $id = isset($_GET['id']) ? $_GET['id'] : "";
        if (count($_SESSION[$this->cCode]['items']) > 0) {
            if (!in_array($id, $_SESSION[$this->cCode]['items'])) {
                $msg = "  tidak dapat di gabung dengan biaya lain karen melibatkan rekening bank ";
                die(lgShowAlert($msg));
            }
        }

//arrprint($id);
        $cCode = $this->cCode;
        $shopingCartFaktur = isset($this->configUi[$this->jenisTr]['addMainSource'][1]) ? $this->configUi[$this->jenisTr]['addMainSource'][1] : array();
        $selectorProcessorTarget = isset($this->configUi[$this->jenisTr]['selectorProcessor']) ? $this->configUi[$this->jenisTr]['selectorProcessor'] : array();
        $prosessor = MODUL_PATH . "_selectorItem/selectPenerimaan/" . $this->jenisTr . "?id=$id";
        //region efaktur
        $validator_js = "var error=0,resError=[];\n";
        $faktur = "";
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (count($shopingCartFaktur) > 0) {
//                if (isset($showFormulirFaktur) && ($showFormulirFaktur == true)) {
                $shopingCartFakturItems = array();
                $faktur .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
                $faktur .= "<table class='table '>";
                $faktur .= "<tr class='bg-primary'>";
//                foreach ($shopingCartFaktur["fields"] as $ff => $ff_abels) {
//                    $man = isset($shopingCartFaktur["editableFieldsMandatori"][$ff]) ? " <span class='text-bold text-red'>(* wajib isi)</span>" : "";
                $faktur .= "<th>Invoice Penerimaan</th>";
//                }
                $faktur .= "</tr>";
                $faktur .= "<tr>";
                $linkFaktur = isset($shopingCartFaktur["editProcess"]) ? MODUL_PATH . $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr : "";

                $whenInAction = "";

                foreach ($shopingCartFaktur["fields"] as $fff => $f_labels) {
//                cekMerah($fff);
                    $labels = "";
//                    if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                    if (isset($shopingCartFaktur["editableFields2"][$fff])) {
                        $inputType = $shopingCartFaktur["editableFields2"][$fff];
                        $defValues = isset($shopingCartFakturItems[$fff]) ? $shopingCartFakturItems[$fff] : "";
                        if ($shopingCartFaktur["editableFields2"][$fff] == "checkbox") {
                            $classinputType = "";
                            $labels = "tic disini jika faktur belum tersedia";
                            $vals = "checked";
                            $checked = isset($shopingCartFakturItems[$fff]) && $shopingCartFakturItems[$fff] == "true" ? $vals : "";
                        }
                        else {
                            $classinputType = "form-control ";
                            $labels = "";
                            $vals = "value";
                            $checked = "";
                        }
//                            $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name)\">";
                        $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked >";
                    }
                    else {
                        $value = isset($shopingCartFakturItems[$fff]) ? formatField($fff, $shopingCartFakturItems[$fff]) : 0;
                    }

                    $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'>$labels </span></td>";

                    if (isset($shopingCartFaktur["editableFieldsMandatori"][$fff])) {
                        $reason = $shopingCartFaktur["editableFieldsMandatori"][$fff];
                        $validator_js .= "
                            var $fff = top.$('#$fff').val();
                            if( top.$('#$fff').val() == '' ){
                                error++;
                                resError.push('$reason');
                            }\n
                        ";
                    }
                    else {
                        $validator_js .= "
                            var $fff = top.$('#$fff').val();\n
                        ";
                    }

                    if ($whenInAction == "") {
                        $whenInAction .= "top.$.when( top.$.ajax('" . MODUL_PATH . $selectorProcessorTarget . "/" . $this->jenisTr . "?selector&id=" . $id . "&minValue=0') )\n";
                        $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . $selectorProcessorTarget . "/" . $this->jenisTr . "?id=" . $id . "&newQty=0&harga='+$fff) )\n";
                        $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                        $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . "_processPihak/selectTaxes/" . $this->jenisTr . "?val=uang_muka_produk&p=uang_muka_produk') )\n";
                        $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . "_processPihak/selectTaxes/" . $this->jenisTr . "?val=include_ppn&p=include_ppn') )\n";
                    }
                    else {
                        if ($fff == "dateFaktur") {
                            $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr . "?nilai='+$fff+'&nama=dateFaktur') )\n";
                        }
                        else {
                            if ($fff == "eFaktur") {
                                $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr . "?nilai='+$fff+'&nama=eFaktur') )\n";
                            }
                            else {
                                $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . "_shoppingCart/recordAddRow/" . $this->jenisTr . "/$fff/?val='+$fff) )\n";
                            }
                        }
                    }
                }

                $whenInAction .= ".then( dialog.close() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                $whenInAction .= ".then( top.swal.close() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";

                $faktur .= "</tr>";
                $faktur .= "</table>";
                $faktur .= "</div>";
                $faktur .= "";
                $faktur .= "<div id='panel_error' class='panel panel-danger hidden'>";
                $faktur .= "<div class='panel-heading fa-2x'><i class='fa fa-warning text-yellow blink'></i> PERHATIAN</div>";
                $faktur .= "<div class='panel-body bg-red'>";
                $faktur .= "</div>";
                $faktur .= " </div>";
//            }
            }
        }


        $faktur = "<input id='inv_p' class='form-control select2' placeholder='pilih invoice'  >";//ajax
        $faktur .= "<div id='sementara' cclass='container-fluid'></div>";

        //endregion

        $addClick = "


                    top.BootstrapDialog.show({
                        title:'Pilih Invoice Penerimaan',
                        message: \"$faktur\",
                        draggable:true,
                        closable:true,
                        type:top.BootstrapDialog.TYPE_SUCCESS,
                        size: top.BootstrapDialog.SIZE_WIDE,
                        onshown: function(){
        console.log(top.$('#sementara'));
        top.$('#inv_p').on('focus',function(){
        top.$('#sementara').load('$prosessor')
        
        })
top.$('#inv_p').on('keyup', top.delay_v2( function(cari){ 
    top.console.log('sekali keyup perdetik') 
    top.console.log(top.$(cari)) 
    top.console.log(cari.currentTarget.value) 
    top.$('#sementara').load('$prosessor&search='+cari.currentTarget.value)
}, 1000))

                        },
                        buttons: [{
                            label: 'BATAL',
                            cssClass: 'btn-danger pull-left',
                            action: function(dialog) {
                                console.log(dialog)
                                dialog.close();
                            }
                        }, {
                            label: 'SIMPAN & LANJUT',
                            cssClass: 'btn-success',
                            action: function(dialog) {
                                top.$('#panel_error').addClass('hidden');
                                top.swal('prosess.. mohon tunggu validasi data.');
                                top.swal.enableLoading();

                                $validator_js

                                if(!error){
                                    console.log('TIDAK ADA ERROR');
                                    $whenInAction

                                }
                                else{
                                    var errText = '<ol>'
                                    top.jQuery.each(resError, function(aa, res){
                                        console.error('aa: '+ aa + '  ||  res: ' + res);
                                        errText += \"<li class='text-white text-bold font-size-1-5'>\" + res + '</li>';
                                    });
                                    errText += '</ol>'
                                    top.$('.panel-body').html(errText);

                                    setTimeout(function(){
                                        top.$('#panel_error').removeClass('hidden');
                                        top.swal.close();
                                    }, 1000)

                                }

                            }
                        }],
                    });
                                    ";
        echo "<script>" . $addClick . "</script>";
//        matiHere(__LINE__);
    }

    public function selectPenerimaan()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $idBiaya = isset($_GET['bid']) ? $_GET['bid'] : "";
        $this->load->model("Mdls/MdlTransaksi2");
        $l = new MdlTransaksi2();
        $l->setFilters(array());
        $l->addFilter("transaksi_id=$id");
        $result = $l->lookupDataRegistries()->result();
//        arrPrint($result);
        $element = blobDecode($result[0]->main);
        //masih dimanual
        $arrSelect = array(
            "cabangID" => "cabang2_id",
            "cabangName" => "cabang2_nama",
            "cash_account_id" => "cash_account_ref",
            "cash_account_nama" => "cash_account_nama_ref",
            "transaksi_id" => "transaksi_id_ref",
        );
//        $cCode = "_TR_" . $this->jenisTr;
//        matiHEre($this->cCode."::".__LINE__);
        foreach ($arrSelect as $src => $target) {
            $_SESSION[$this->cCode]["main"][$target] = $element[$src];
        }


        $exec = MODUL_PATH . "_processSelectBiaya/select/" . $this->jenisTr . "?selector&id=" . $idBiaya . "&minValue=0";
        echo "<script>
top.$('#result').load('$exec');
top.BootstrapDialog.closeAll();
</script>";
    }
}

