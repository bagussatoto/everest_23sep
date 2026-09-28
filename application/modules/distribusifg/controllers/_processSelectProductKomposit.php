<?php

require_once "Modul_Controller.php";

class _processSelectProductKomposit extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function select__OLD()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();

        $tmpB = $b->lookupByID($id)->result();


        $arrComponents = array();
        $arrComponentsProduk = array();
        $arrCostComponentsValidate = array();
        if (sizeof($componentAssConfig) > 0) {
            $this->load->model("Mdls/" . $componentAssConfig['model']);
            $pk = New $componentAssConfig['model']();
//            $pk->setFilters(array());
            $pk->setSortBy(array(
                "kolom" => "produk_dasar_id",
                "mode" => "ASC",
            ));
            $tmpPK = $pk->lookupByPID($id)->result();
//mati_disini($this->db->last_query());

            $c1 = 0;
            $c2 = 0;
            if (sizeof($tmpPK) > 0) {
                foreach ($tmpPK as $e => $eSpec) {
                    if ($eSpec->jenis == "produk") {
                        $c1++;
                        $arrComponents[$id][$eSpec->jenis][$c1] = array(
                            "handler" => "",
                            "id" => $eSpec->produk_dasar_id,
                            "nama" => $eSpec->produk_dasar_nama,
                            "jml" => $eSpec->jml,
                            "satuan" => $eSpec->satuan_nama,
                            "nilai" => $eSpec->nilai,
                            "sub_nilai" => $eSpec->jml * $eSpec->nilai,
                        );

                        $arrComponentsProduk[$id][$eSpec->jenis][$c1] = $eSpec;
                    }
                }
            }
            else {
                $arrComponents[$id] = array();
            }
        }
// matiHEre("hoop");
        if (sizeof($arrComponents[$id]) == 0) {
            die(lgShowAlert("belum ada data komposisi produk, harap di setUp terlebih dahulu via login holding."));
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

        // menyimpan komposisi mentah ke session
        $_SESSION[$cCode]['items_komposisi'][$id] = $arrComponentsProduk[$id];
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        // $configUiJenis = $this->configUi[$this->jenisTr];
        // $configCoreJenis = $this->configCore[$this->jenisTr];
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
//                arrPrint($row);
                $rows = $row;
                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $nama = isset($row->nama) > 0 ? $row->nama : "n/a";
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
                    $_SESSION[$cCode]['items'][$id] = $tmp;
                    //endregion


                    $tmpRslt = $arrComponents[$id];


                    $_SESSION[$cCode]['items2'][$id] = $tmpRslt;
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


                    if (isset($_SESSION[$cCode]['items2'][$id]) && sizeof($_SESSION[$cCode]['items2'][$id]) > 0) {
                        foreach ($_SESSION[$cCode]['items2'][$id]['produk'] as $e => $eSpec) {
                            $_SESSION[$cCode]['items2'][$id]['produk'][$e]['jml'] = isset($arrComponents[$id]['produk'][$e]['jml']) ? ($arrComponents[$id]['produk'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                        }
                        foreach ($_SESSION[$cCode]['items2'][$id]['biaya'] as $e => $eSpec) {
                            $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['jml'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['sub_nilai'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['nilai'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                        }
                    }
                }
            }

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
//                $_SESSION[$cCode]['out_master']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                    $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }
            if (sizeof($_SESSION[$cCode]['items2']) > 0) {
                cekBiru("bulding summary item_result...");
                $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
                $_SESSION[$cCode]['items3_sum'] = array();// biaya-nya...
                foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                    foreach ($pSpec as $jenis => $jSpec) {
                        foreach ($jSpec as $eSpec) {
                            if ($jenis == "produk") {
                                if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();
                                }
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                            }

                        }
                    }
                }
            }
            if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                    $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
                }
            }


        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }


//mati_disini(":: SAMPAI BAWAH ::");
//         echo "<script>";
//         echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
//         echo "</script>";
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
            "stepNumber" => $stepNum,
            "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        // mati_disini(__FILE__ . "<hr> @" . __LINE__);
        // echo "<script>";
        // echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?".$f_selector."selID=$id&modul=".$this->modul."';";
        // echo "</script>";

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
        $_SESSION[$cCode]['items3_sum'] = array();
        $_SESSION[$cCode]['tableIn_detail2_sum'] = array();
        $_SESSION[$cCode]['tableIn_detail_values2_sum'] = array();
        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $jenis => $jSpec) {
                    foreach ($jSpec as $eSpec) {
                        if ($jenis == "produk") {
                            if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                            }
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                        }
                        if ($jenis == "biaya") {
                            if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] = 0;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] = 0;
                            }
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                        }
                    }
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

        if (isset($_SESSION[$cCode]['items_komposisi'][$id])) {
            $_SESSION[$cCode]['items_komposisi'][$id] = NULL;
            unset($_SESSION[$cCode]['items_komposisi'][$id]);
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


    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $produk_id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();
        //-----------------------------------------------
        if (!isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = array();
        }


        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . __CLASS__);
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . " " . __METHOD__);
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
        $shopingCartReferenceValidator = isset($this->configUi[$this->jenisTr]['shopingCartReferenceValidator']) ? $this->configUi[$this->jenisTr]['shopingCartReferenceValidator'] : array();
        $shopingCartStockValidate = isset($this->configUi[$this->jenisTr]['shopingCartStockValidate'][1]) ? $this->configUi[$this->jenisTr]['shopingCartStockValidate'][1] : false;


        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("lime");
        // matiHere(__LINE__ . " " .__METHOD__);

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $item_jenis = $row->jenis;//item,komposit/paket,rakitan
                $produk_jenis_id = $rows->kategori_id;
                $produk_jenis = $rows->kategori_nama;
                $produk_nama = $rows->nama;
                $produk_kode = $rows->kode;
                $produk_kode = htmlspecialchars($produk_kode);
                $produk_nama = htmlspecialchars($produk_nama);
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
                    // matiHere(__LINE__. "<hr>");
                    //                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                    if (sizeof($tmpC) > 0) {
                        // arrPrint($tmpC);
                        // arrPrint($row);
                        $kode = $row->kode;
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
                                // echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                // echo "</script>";
                                $msg = "Insufficient stock of:<br><red class='text-red'>$kode $nama</red><hr>$jml_now $satuan stock available";
                                $alerts = array(
                                    "type" => "warning",
                                    "title" => strtoupper($kode),
                                    "html" => $msg,
                                );
                                echo swalAlert($alerts);
                                die($msg);

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

                if (sizeof($connectedDiscountConfig) > 0) {
                    if ($connectedDiscountConfig['enabled'] == 1) {
                        $mdlNameRelation = $connectedDiscountConfig['mdlNameRelation'];
                        $mdlNameSource = $connectedDiscountConfig['mdlNameSource'];

                        $this->load->model("Mdls/" . $mdlNameRelation);
                        $dr = new $mdlNameRelation();
                        $dr->addFilter("produk_id='$id'");
                        $dr->addFilter("status='1'");
                        $tmpDr = $dr->lookupAll($id)->result();
                        //                        cekMerah($this->db->last_query());
                        //                        arrPrint($tmpDr);
                        $produkQty = isset($_GET['jml']) ? $_GET['jml'] : $tmpJml;
                        foreach ($tmpDr as $drSpec) {
                            $this->load->model("Mdls/" . $mdlNameSource);
                            $sr = new $mdlNameSource();
                            $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                            $sr->addFilter("status='1'");
                            $tmpSr = $sr->lookupAll($id)->result();
                            showLast_query("merah");
                            //                            arrPrint($tmpSr);
                            foreach ($tmpSr as $srSpec) {
                                arrPrint($srSpec);
                                if ($produkQty > $srSpec->max_qty) {
                                    $discountPersen = $srSpec->discount_persen;
                                    $discountQty = $srSpec->discount_qty;
                                }
                                elseif (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                    $discountPersen = $srSpec->discount_persen;
                                    $discountQty = $srSpec->discount_qty;
                                }
                                else {
                                    $discountPersen = 0;
                                    $discountQty = 0;
                                }
                                $arrDiscount[$id] = array(
                                    "persen" => $discountPersen,
                                    "qty" => $discountQty,
                                );
                                cekMerah("pID: $id ::: persen: $discountPersen ::: qty: $discountQty");
                            }
                        }
                    }
                }

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                /* ----------------------------------------------------------------
                 * inisisasi cCode Items
                 * ----------------------------------------------------------------*/
                if (!isset($_SESSION[$cCode]['items']) || !array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "jml_serial" => $rows->jml_serial,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                        "discount_persen" => isset($arrDiscount[$id]['persen']) ? $arrDiscount[$id]['persen'] : 0,
                        "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                    );

                    if (sizeof($priceMainConfig) > 0) {
                        if (isset($priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']])) {
                            $priceConfig = $priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']];
                            cekUngu("masuk disini...");
                        }
                    }

                    cekBiru(__LINE__);
                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                            $h->addFilter("produk_id='$id'");
                            // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            // $h->addFilter("toko_id=" . my_toko_id());
                        }
                        else {
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            // $h->addFilter("toko_id=" . my_toko_id());
                        }

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
                        // showLast_query("kuning");

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    //                                    cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        cekBiru("sini " . __LINE__);
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
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
                    //------------------------------------------------------
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
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
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

                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
//                    cekBiru("ada id $id tapi tidak ada cCode items");
                    if (isset($_GET['newQty'])) {
                        if ((sizeof($shopingCartReferenceValidator) > 0)) {
                            if ((isset($_SESSION[$cCode]["main"]["requestReferenceID"])) && ($_SESSION[$cCode]["main"]["requestReferenceID"] > 0)) {
                                if (isset($shopingCartReferenceValidator["enabled"]) && ($shopingCartReferenceValidator["enabled"] == true)) {
                                    if ($shopingCartReferenceValidator["allowIncrement"] == false) {
                                        $max_jml_order = $_SESSION[$cCode]['items'][$id]['max_jml_order'];
                                        if ($_GET['newQty'] > $max_jml_order) {
                                            $msg = "jumlah barang yang akan dikirim ke konsumen melebihi jumlah order yaitu $max_jml_order. ";
                                            $msg .= "anda tidak bisa menambah jumlah barang yang akan dikirim.";
                                            mati_disini($msg);
                                        }
                                    }
                                }
                            }
                        }
                        if ($shopingCartStockValidate == true) {
                            $stok_rekening = $_SESSION[$cCode]['items'][$id]["current_stok"];
                            $stok_intransit = $_SESSION[$cCode]['items'][$id]["intransit_stok"];
                            $stok_akhir = $stok_rekening - $stok_intransit - $_GET['newQty'];
                            if ($stok_akhir < 0) {
                                $msg = "Stok $produk_nama kurang, jumlah yang diinput melebihi stok yang tersedia. Silahkan dikoreksi. code: " . __LINE__;
                                mati_disini($msg);
                            }
                        }

                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + (isset($_SESSION[$cCode]['items'][$id]['ppn']) ? $_SESSION[$cCode]['items'][$id]['ppn'] : 0)));
                    }
                    if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {
                        foreach ($arrDiscount[$id] as $dKey => $dVal) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['discount_' . $dKey])) {
                                $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = $dVal;
                        }
                    }


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

                $tmp = $_SESSION[$cCode]['items'][$id];

                // memasukkan kolom sku ke items2
                // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");
                $arrCat = array();
                $arrCode = array();
                if ($produk_jenis == "unit") {
                    foreach ($arrDataTambahan as $cat => $catSpec) {
                        foreach ($catSpec as $dkey => $dval) {
                            if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                $_SESSION[$cCode]['items2'][$produk_id][$rows->$dval] = array();
                                //--------------
                                if (!isset($arrCat[$cat])) {
                                    $arrCat[$cat] = 0;
                                }
                                $arrCat[$cat] += 1;
                                //--------------
                                if (!isset($arrCode[$rows->$dval])) {
                                    $arrCode[$rows->$dval] = 0;
                                }
                                $arrCode[$rows->$dval] += 1;
                                //--------------
                            }
                        }
                    }
                }
                else {
                    $jml_serial = $rows->jml_serial;
                    $tmp['jml_serial'] = $jml_serial;
                    $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                    if ($jml_serial * 1 == 1) {
                        $d_kode = $rows->kode;
                        $_SESSION[$cCode]['items2'][$produk_id][$d_kode] = array();
                        $arrCode[$d_kode] = 1;
                    }
                }

                $keterangan = "";
                $static_keterangan = "";
                if (sizeof($arrCat) > 0) {
                    foreach ($arrCat as $kcat => $vcat) {
                        $new_vcat = $vcat * $_SESSION[$cCode]['items'][$id]["jml"];
                        if ($keterangan == "") {
                            $keterangan = " $new_vcat $kcat";
                        }
                        else {
                            $keterangan .= "<br> $new_vcat $kcat";
                        }
                        if ($static_keterangan == "") {
                            $static_keterangan = " $vcat $kcat";
                        }
                        else {
                            $static_keterangan .= "<br> $vcat $kcat";
                        }
                        $new_keyy = "qty_" . $kcat;
                        $tmp[$new_keyy] = $vcat;
                    }
                }
                if (sizeof($arrCode) > 0) {
                    foreach ($arrCode as $kcat => $vcat) {
                        $new_vcat = $vcat * $_SESSION[$cCode]['items'][$id]["jml"];
                        $tmp[$kcat] = $new_vcat;
                        $tmp["jml_" . $kcat] = $vcat;
                    }
                }
                $tmp['keterangan'] = $keterangan;
                $tmp['static_keterangan'] = $static_keterangan;
                //----------------------------------------
                $_SESSION[$cCode]['items'][$produk_id] = $tmp;

                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    if ($item_jenis == "item_komposit") {
                        $_SESSION[$cCode]['items6'][$produk_id] = array();
                        $_SESSION[$cCode]['items7'][$produk_id] = array();
                        $this->load->model("Mdls/MdlProduk2");
                        $this->load->model("Mdls/MdlProdukKompositKomposisi");
                        $pp = new MdlProduk2();
                        $kk = new MdlProdukKompositKomposisi();
                        $kk->addFilter("produk_id=$id'");
                        $tmpKomposit = $kk->lookUpAll()->result();

                        if (count($tmpKomposit) > 0) {
                            $qty_faktor = isset($_GET['newQty']) ? $_GET['newQty'] : 1;
                            $idProduk_komposit = array();
                            $priceKomposit = array();
                            $items8 = array();
                            foreach ($tmpKomposit as $tmpKomposit_0) {
                                $idProduk_komposit[] = $tmpKomposit_0->produk_dasar_id;
                                $priceKomposit[$tmpKomposit_0->produk_dasar_id] = array(
                                    "harga" => $tmpKomposit_0->harga / 1.11,
                                    "jml" => $tmpKomposit_0->jml * $qty_faktor,
                                );
                                $items8[$tmpKomposit_0->produk_dasar_id] = array(
                                    "id" => $tmpKomposit_0->id,
                                    "produk_id" => $tmpKomposit_0->produk_id,
                                    "produk_nama" => $tmpKomposit_0->produk_nama,
                                    "produk_dasar_id" => $tmpKomposit_0->produk_dasar_id,
                                    "produk_dasar_nama" => $tmpKomposit_0->produk_dasar_nama,
                                    "jml" => $tmpKomposit_0->jml,
                                    "qty" => $tmpKomposit_0->jml,
                                    "harga" => $tmpKomposit_0->harga / 1.11,
                                    "harga_nppn" => $tmpKomposit_0->harga,
                                );
                                $_SESSION[$cCode]["items_komposisi"][$tmpKomposit_0->produk_id] = $items8;
                            }
                            $pp->addFilter("id in ('" . implode("','", $idProduk_komposit) . "')");
                            $tmpDataProdukKomposisi = $pp->lookUpAll()->result();
                            foreach ($tmpDataProdukKomposisi as $tmpProdukKomposisiPaket) {
                                $produk_jenis_paket = $tmpProdukKomposisiPaket->kategori_nama;
                                $tmpPaket = array(
                                    "id" => $tmpProdukKomposisiPaket->id,
                                    "jml" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"],
                                    "qty" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"],
                                    "harga" => $priceKomposit[$tmpProdukKomposisiPaket->id]["harga"],
                                    "subtotal" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"] * $priceKomposit[$tmpProdukKomposisiPaket->id]["harga"],
                                    "satuan" => strlen($tmpProdukKomposisiPaket->satuan) > 0 ? $tmpProdukKomposisiPaket->satuan : "n/a",
                                    "harga_jasa" => 0,
                                );
                                foreach ($fieldSrcs as $key => $src) {
                                    if (is_array($src) && sizeof($src) > 0) {
                                        foreach ($src as $srcSpec) {
                                            if (isset($tmpPaket[$srcSpec]) || isset($tmpProdukKomposisiPaket->$srcSpec)) {
                                                cekBiru("ambil gerbang key -> $srcSpec");
                                                $tmpPaket[$key] = makeValue($srcSpec, $tmpPaket, $tmpPaket, isset($tmpProdukKomposisiPaket->$srcSpec) ? $tmpProdukKomposisiPaket->$srcSpec : 0);
                                            }
                                        }
                                    }
                                    else {
                                        $tmpPaket[$key] = makeValue($src, $tmpPaket, $tmpPaket, isset($tmpProdukKomposisiPaket->$src) ? $tmpProdukKomposisiPaket->$src : 0);
                                        //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                                    }

                                }
                                $jml_serial_paket = $tmpProdukKomposisiPaket->jml_serial;
                                $tmpPaket['jml_serial'] = $jml_serial_paket;
                                $tmpPaket['scan_mode'] = $jml_serial_paket > 0 ? "serial" : "simple";
                                if ($jml_serial_paket * 1 == 1) {
                                    $d_kode = $tmpProdukKomposisiPaket->kode;
                                    $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$d_kode] = array();
                                }
                                // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");

                                $arrCat = array();
                                $arrCode = array();
                                if ($produk_jenis_paket == "unit") {
                                    foreach ($arrDataTambahan as $cat => $catSpec) {
                                        foreach ($catSpec as $dkey => $dval) {
                                            if (isset($tmpProdukKomposisiPaket->$dval) && ($tmpProdukKomposisiPaket->$dval != NULL)) {
                                                $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$tmpProdukKomposisiPaket->$dval] = array();
                                                //--------------
                                                if (!isset($arrCat[$cat])) {
                                                    $arrCat[$cat] = 0;
                                                }
                                                $arrCat[$cat] += 1;
                                                //--------------
                                                if (!isset($arrCode[$tmpProdukKomposisiPaket->$dval])) {
                                                    $arrCode[$tmpProdukKomposisiPaket->$dval] = 0;
                                                }
                                                $arrCode[$tmpProdukKomposisiPaket->$dval] += 1;
                                                //--------------
                                            }
                                        }
                                    }
                                }
                                else {
                                    $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$tmpProdukKomposisiPaket->kode] = array();
                                    $arrCat["barcode"] = 1;
                                    $arrCode[$tmpProdukKomposisiPaket->kode] = 1;
                                }
                                $keterangan = "";
                                $static_keterangan = "";
                                if (sizeof($arrCat) > 0) {
                                    foreach ($arrCat as $kcat => $vcat) {
                                        $new_vcat = $vcat * $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"];
                                        if ($keterangan == "") {
                                            $keterangan = " $new_vcat $kcat";
                                        }
                                        else {
                                            $keterangan .= "<br> $new_vcat $kcat";
                                        }
                                        if ($static_keterangan == "") {
                                            $static_keterangan = " $vcat $kcat";
                                        }
                                        else {
                                            $static_keterangan .= "<br> $vcat $kcat";
                                        }
                                        $new_keyy = "qty_" . $kcat;
                                        $tmpPaket[$new_keyy] = $vcat;
                                    }
                                }
                                if (sizeof($arrCode) > 0) {
                                    foreach ($arrCode as $kcat => $vcat) {
                                        $new_vcat = $vcat * $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"];
                                        $tmpPaket[$kcat] = $new_vcat;
                                    }
                                }
                                $tmpPaket['keterangan'] = $keterangan;
                                $tmpPaket['static_keterangan'] = $static_keterangan;
                                $tmpPaket['produk_paket_id'] = $produk_id;
                                $tmpPaket['produk_paket_nama'] = $row->nama;
                                $_SESSION[$cCode]["items6"][$produk_id][$tmpProdukKomposisiPaket->id] = $tmpPaket;

                            }
                        }
                        else {
                            matiHere("produk paket belum memiliki komposisi !. Silahkan perbaiki data dari menu data produk penjualan paket");
                        }

                        //                    arrprint($tmpKomposit);
                    }
                }

            }
        }
        else {
            cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
            die();
        }

        // arrPrint($_SESSION[$cCode]['items']);
        // mati_disini();
        $f_selector = "";
        if (isset($_GET['selector'])) {
            $f_selector = "selector&";
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

        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);
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
//        }

    }

}