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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        $tmpB = $b->lookupByID($id)->result();
//showLast_query("biru");

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
//arrPrintWebs($row);
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

//                    arrPrint($fieldSrcs);
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

                    $_SESSION[$cCode]['main']['projectID'] = $id;
                    $_SESSION[$cCode]['main']['projectName'] = $row->nama;

                    $_SESSION[$cCode]['main']['pihakProjekID'] = $id;
                    $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                    $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->nama) ? $row->nama : "";
                    $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                    $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                    $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                    //-GUDANG PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                    //-------

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

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");

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
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);

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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();


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
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }


        if (isset($_SESSION[$cCode]['items2_sum'][$id])) {
            $_SESSION[$cCode]['items2_sum'][$id] = null;
            unset($_SESSION[$cCode]['items2_sum'][$id]);
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);

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

    // select waork order saat realisasi
    public function selectProject_OLD()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;


        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");


        if (isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }


        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $workOrderValidate = isset($this->configUi[$this->jenisTr]['workOrderValidate']) ? $this->configUi[$this->jenisTr]['workOrderValidate'] : array();


        $tmpB = $b->lookupByID($id)->result();
//        $b->addFilter("fase_id='$id'");
//        $tmpB = $b->lookupAll()->result();
        showLast_query("biru");
        arrPrintPink($tmpB);
//        arrPrintHijau($workOrderValidate);


//        mati_disini(__LINE__ . " ::: " . count($tmpB));
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $workorder_id = $row->fase_id;
                $produk_id = $row->produk_id;
                $produk_nama = $row->produk_nama;
                $id = $row->produk_dasar_id;
                $nama = $row->produk_dasar_nama;
                $harga = $row->harga;
                $nilai = $row->nilai;
                $jenis = $row->jenis;
                $qty_kredit = $row->qty_kredit;
                $qty_saldo = $row->qty_saldo;
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";

                $tmpJml_wo = $row->jml;
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

                //-------------------------
                if ((sizeof($workOrderValidate) > 0)) {
                    if (isset($workOrderValidate["enabled"]) && ($workOrderValidate["enabled"] == true)) {
                        $validateRule = $workOrderValidate["validateRule"];
                        foreach ($validateRule as $validateRuleSpec) {
                            $fsFilter = $validateRuleSpec["filter"];
                            $fsModel = $validateRuleSpec["model"];
                            $fsWarningLabel = $validateRuleSpec["warningLabel"];
                            $this->load->model("Mdls/$fsModel");
                            $ffss = New $fsModel();
                            makeFilter($fsFilter, (array)$row, $ffss);
                            $ffssTmp = $ffss->lookupAll()->result();
                            showLast_query("kuning");
                            cekHere(count($ffssTmp));
                            if (count($ffssTmp) == 0) {
                                mati_disini($fsWarningLabel);
                            }
                        }
                    }
                }

                //-------------------------
//mati_disini(__LINE__);

                $ppj = New MdlProdukProject();
                $ppj->addFilter("id='$produk_id'");
                $ppjTmp = $ppj->lookupAll()->result();
                $customer_id = $ppjTmp[0]->customer_id;
                $customer_nama = $ppjTmp[0]->customer_nama;
                $keterangan = $ppjTmp[0]->keterangan;
                $spek = $ppjTmp[0]->spek;
                $start_dtime = $ppjTmp[0]->start_dtime;
                $end_dtime = $ppjTmp[0]->end_dtime;


                if ($jenis == "produk") {

                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                        $tmp = array(
                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                            "id" => $id,
                            "nama" => $nama,
                            "name" => $nama,
                            "jml_wo" => $tmpJml_wo,
                            "jml" => $tmpJml,
                            "qty" => $tmpJml,
                            "harga" => $harga,
                            "nilai_untung" => 0,
                            "nilai_rugi" => 0,
                            "nilai_final_rugilaba" => 0,
                            "subtotal" => $tmpJml * $harga,
                            "workorderID" => $workorder_id,// work order id
                            "workorderName" => "",
                            "produkID" => $produk_id,// project id
                            "produkName" => $produk_nama,
                            "qty_kredit" => $qty_kredit,
                            "qty_saldo" => $qty_saldo,
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


                        foreach ($fieldSrcs as $key => $src) {
                            $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                        }
                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id] = $tmp;

                        //-------------------------
                        $_SESSION[$cCode]['main']['projectID'] = isset($row->produk_id) ? $row->produk_id : "";
                        $_SESSION[$cCode]['main']['projectName'] = isset($row->produk_nama) ? $row->produk_nama : "";

                        $_SESSION[$cCode]['main']['pihakProjekID'] = isset($row->produk_id) ? $row->produk_id : "";
                        $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                        $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->produk_nama) ? $row->produk_nama : "";
                        $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                        $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                        $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                        //-GUDANG PER PROJECT------
                        $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_id"];
                        $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_nama"];
                        $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_nama"];
                        //-------
                        $_SESSION[$cCode]['main']['pihakProjekCustomerID'] = $customer_id;
                        $_SESSION[$cCode]['main']['pihakProjekCustomerNama'] = $customer_nama;
                        $_SESSION[$cCode]['main']['pihakProjekWorkorderID'] = $workorder_id;
                        $_SESSION[$cCode]['main']['pihakProjekWorkorderNama'] = isset($workorder_nama) ? $workorder_nama : "";
                        $_SESSION[$cCode]['main']['pihakProjekStartDtime'] = $start_dtime;
                        $_SESSION[$cCode]['main']['pihakProjekEndDtime'] = $end_dtime;
                        //-------
                    }
                    else {

                        // region validate qty yang diambil, manual ya prends....
                        if (isset($_GET["newQty"]) && $_GET["newQty"] > 0) {
                            $msg = "Jumlah $produk_nama yang diambil melebihi sisa alokasi work order.";
                            mati_disini($msg);
                        }
                        // endregion validate qty yang diambil, manual ya prends....


                        if (sizeof($itemNumLabels) > 0) {

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
//            mati_disini(__LINE__);

//            if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
//                $nilai_untung = 0;
//                $nilai_rugi = 0;
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                    $nilai_untung += $iSpec['nilai_untung'];
//                    $nilai_rugi += $iSpec['nilai_rugi'];
//                }
//
//                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
//                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
//            }

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }

        }
        else {

            $msg = "Daftar bahan baku yang diperlukan belum diatur. Segera hubungi admin.";
            mati_disini($msg);

        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    //----------------------
    public function selectProject()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;


        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $selectorSubSrcModel = isset($this->configUi[$this->jenisTr]['selectorSubSrcModel']) ? $this->configUi[$this->jenisTr]['selectorSubSrcModel'] : null;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");


        if (isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
            $_SESSION[$cCode]['items2_sum'] = array();
        }


        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $workOrderValidate = isset($this->configUi[$this->jenisTr]['workOrderValidate']) ? $this->configUi[$this->jenisTr]['workOrderValidate'] : array();
        $pairedTasklist = isset($this->configUi[$this->jenisTr]['pairTasklist']) ? $this->configUi[$this->jenisTr]['pairTasklist'] : false;

        $tmpB = $b->lookupByID($id)->result();
        cekBiru($this->db->last_query());
        cekHitam($pairedTasklist);

//        $b->addFilter("fase_id='$id'");
//        $tmpB = $b->lookupAll()->result();
//        showLast_query("biru");
//        arrPrintPink($tmpB);
//        arrPrintHijau($workOrderValidate);


//        mati_disini(__LINE__ . " ::: " . count($tmpB));
        if (sizeof($tmpB) > 0) {
            if ($pairedTasklist) {
                $this->load->model('Mdls/MdlTasklistProject');
                $tl = new MdlTasklistProject();
                $tl->addFilter("fase_id='$id'");
                $prevTasklist = $tl->lookUpAll()->result();
                $curentTask = array();
                foreach ($prevTasklist as $prevTasklist_0) {
                    $curentTask = array("progress_id" => $prevTasklist_0->progress_id, "progress_nama" => $prevTasklist_0->progress_nama);
                }
//                arrPrint($curentTask);
//                matiHere(__LINE__);
            }
            foreach ($tmpB as $row) {
//                arrPrint($row);
                //-------------------------
                if ((sizeof($workOrderValidate) > 0)) {
                    if (isset($workOrderValidate["enabled"]) && ($workOrderValidate["enabled"] == true)) {
                        $validateRule = $workOrderValidate["validateRule"];
                        foreach ($validateRule as $validateRuleSpec) {
                            $fsFilter = $validateRuleSpec["filter"];
                            $fsModel = $validateRuleSpec["model"];
                            $fsWarningLabel = $validateRuleSpec["warningLabel"];
                            $this->load->model("Mdls/$fsModel");
                            $ffss = New $fsModel();
                            makeFilter($fsFilter, (array)$row, $ffss);
                            $ffssTmp = $ffss->lookupAll()->result();
//                            showLast_query("kuning");
//                            cekHere(count($ffssTmp));
                            if (count($ffssTmp) == 0) {
                                $msg = "Project " . $row->produk_nama . ", ";
                                mati_disini($msg . $fsWarningLabel);
                            }
                        }
                    }
                }
                $workOrderID = isset($row->id) && strlen($row->id) > 0 ? $row->id : 0;
                $workOrderNama = isset($row->nama) && strlen($row->nama) > 0 ? $row->nama : "";
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;

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
                        "subtotal" => 0,
                    );

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
                    if (count($curentTask) > 0) {
                        foreach ($curentTask as $rr => $vv) {
                            $tmp[$rr] = $vv;
                        }
                    }
//                    arrPrint($tmp);
//                    matiHere();
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                    //DETAIL WORK ORDER/KOMPOSISI-----------------------
                    if ($selectorSubSrcModel != null) {
                        $this->load->model("Mdls/$selectorSubSrcModel");
                        $bb = New $selectorSubSrcModel();
                        $bb->addFilter("sub_fase_id='$id'");
                        if (sizeof($selectorSubFilters) > 0) {
                            makeFilter($selectorSubFilters, $_SESSION[$cCode]["main"], $bb);
                        }
                        $tmpBB = $bb->lookupAll()->result();
                        showLast_query("biru");
//                        matiHEre();

                        if (sizeof($tmpBB) > 0) {
                            foreach ($tmpBB as $rowB) {
                                $workorder_id = $rowB->fase_id;
                                $workorder_nama = isset($rowB->fase_nama) ? $rowB->fase_nama : "";
                                $produk_id = $rowB->produk_id;
                                $produk_nama = $rowB->produk_nama;
                                $id = $rowB->produk_dasar_id;
                                $nama = $rowB->produk_dasar_nama;
                                $harga = $rowB->harga;
                                $nilai = $rowB->nilai;
                                $jenis = $rowB->jenis;
                                $qty_debet = $rowB->qty_debet;
                                $qty_kredit = $rowB->qty_kredit;
                                $qty_saldo = $rowB->qty_saldo;
                                $satuan = isset($rowB->satuan) && strlen($rowB->satuan) > 0 ? $rowB->satuan : "n/a";

                                $tmpJml_wo = $rowB->jml;
                                $tmpJml = 1;

                                if ($jenis == "produk") {
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items2_sum'])) {
                                        $tmp = array(
                                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                            "id" => $id,
                                            "nama" => $nama,
                                            "name" => $nama,
                                            "jml_wo" => $tmpJml_wo,
                                            "jml" => $tmpJml,
                                            "qty" => $tmpJml,
                                            "harga" => $harga,
                                            "nilai_untung" => 0,
                                            "nilai_rugi" => 0,
                                            "nilai_final_rugilaba" => 0,
                                            "subtotal" => $tmpJml * $harga,
                                            "workorderID" => $workorder_id,// work order id
                                            "workorderName" => "",
                                            "produkID" => $produk_id,// project id
                                            "produkName" => $produk_nama,
                                            "qty_debet" => $qty_debet,// jumlah sesuai work order
                                            "qty_kredit" => $qty_kredit,// jumlah sudah diambil
                                            "qty_saldo" => $qty_saldo,// sisa yang belum diambil
                                        );
                                        foreach ($fieldSrcs as $key => $src) {
                                            $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                                        }
                                        if ($subAmountConfig != null) {
                                            $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                                        }
                                        else {
                                            $tmp['subtotal'] = 0;
                                        }
                                        $_SESSION[$cCode]['items2_sum'][$id] = $tmp;

                                        $pakai_ini = 1;
                                        if($pakai_ini == 1){
                                            $arrDataTambahan = array(
                                                "outdoor" => array(
                                                    "outdoor_id" => "outdoor_nama",
                                                ),
                                                "indoor" => array(
                                                    "indoor_id_1" => "indoor_nama_1",
                                                    "indoor_id_2" => "indoor_nama_2",
                                                    "indoor_id_3" => "indoor_nama_3",
                                                    "indoor_id_4" => "indoor_nama_4",
                                                ),
                                                "heater" => array(
                                                    "heater_id" => "heater_nama",
                                                ),
                                            );
                                            $tmp = $_SESSION[$cCode]['items2_sum'][$id];
                                            //----------------------------------------
                                            // memasukkan kolom sku ke items2_sum
                                            $arrCat = array();
                                            $arrCode = array();
                                            foreach ($arrDataTambahan as $cat => $catSpec) {
                                                foreach ($catSpec as $dkey => $dval) {
                                                    if (isset($rows->$dval) && ($rows->$dval != NULL)) {
//                                                        $_SESSION[$cCode]['items2'][$produk_id][$rows->$dval] = array();
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
                                            $keterangan = "";
                                            $static_keterangan = "";
                                            if (sizeof($arrCat) > 0) {
                                                foreach ($arrCat as $kcat => $vcat) {
                                                    $new_vcat = $vcat * $_SESSION[$cCode]['items2_sum'][$id]["jml"];
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
                                                    $new_vcat = $vcat * $_SESSION[$cCode]['items2_sum'][$id]["jml"];
                                                    $tmp[$kcat] = $new_vcat;
                                                }
                                            }
                                            $tmp['keterangan'] = $keterangan;
                                            $tmp['static_keterangan'] = $static_keterangan;
                                            //----------------------------------------
                                            $_SESSION[$cCode]['items2_sum'][$id] = $tmp;
                                        }


                                    }
//                                    else {
//
//                                        // region validate qty yang diambil, manual ya prends....
//                                        if (isset($_GET["newQty"]) && $_GET["newQty"] > 0) {
//                                            $msg = "Jumlah $produk_nama yang diambil melebihi sisa alokasi work order.";
//                                            mati_disini($msg);
//                                        }
//                                        // endregion validate qty yang diambil, manual ya prends....
//
//
//                                        if (sizeof($itemNumLabels) > 0) {
//
//                                            foreach ($itemNumLabels as $key => $label) {
//                                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
//                                                    $newValue = $_GET[$key];
//                                                    $tmp[$key] = $newValue;
//                                                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
//                                                    echo "replacing value for $key with " . $newValue . "<br>";
//                                                }
//                                            }
//
//                                            if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
//                                                if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
//                                                    $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
//                                                }
//                                                if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
//                                                    $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
//                                                }
//                                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
//                                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);
//
//                                            }
//
//                                            if ($subAmountConfig != null) {
//                                                $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
//                                            }
//                                            else {
//                                                $tmp['subtotal'] = 0;
//                                            }
//                                            $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
//                                        }
//
//
//                                    }
                                }


                            }
                        }
                    }
                    else {
                        cekMerah("tidak ada sub_src_model");
                    }
//                    mati_disini(__LINE__);


                    //region MAIN
                    if ($row->produk_id > 0) {
                        $ppj = New MdlProdukProject();
                        $ppj->addFilter("id='$produk_id'");
                        $ppjTmp = $ppj->lookupAll()->result();
                        $customer_id = $ppjTmp[0]->customer_id;
                        $customer_nama = $ppjTmp[0]->customer_nama;
                        $keterangan = $ppjTmp[0]->keterangan;
                        $spek = $ppjTmp[0]->spek;
                        $start_dtime = $ppjTmp[0]->start_dtime;
                        $end_dtime = $ppjTmp[0]->end_dtime;
                    }


                    $_SESSION[$cCode]['main']['projectID'] = $row->produk_id;
                    $_SESSION[$cCode]['main']['projectName'] = $row->produk_nama;

                    $_SESSION[$cCode]['main']['pihakProjekID'] = $row->produk_id;
                    $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                    $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->produk_nama) ? $row->produk_nama : "";
                    $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                    $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                    $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                    $_SESSION[$cCode]['main']['pihakProjekCustomerID'] = $customer_id;
                    $_SESSION[$cCode]['main']['pihakProjekCustomerNama'] = $customer_nama;
                    $_SESSION[$cCode]['main']['pihakProjekKeterangan'] = $keterangan;
                    $_SESSION[$cCode]['main']['pihakProjekSpek'] = $spek;
                    $_SESSION[$cCode]['main']['pihakProjekStartDate'] = $start_dtime;
                    $_SESSION[$cCode]['main']['pihakProjekEndDate'] = $end_dtime;
                    //-GUDANG PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($row->produk_id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($row->produk_id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($row->produk_id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                    //-GUDANG WORKORDER PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderID'] = $workorder_id;
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderNama'] = $workorder_nama;
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderGudangID'] = getDefaultWarehouseProjectWorkorder($row->produk_id, $workorder_id, $workorder_nama)["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderGudangName'] = getDefaultWarehouseProjectWorkorder($row->produk_id, $workorder_id, $workorder_nama)["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderGudangNama'] = getDefaultWarehouseProjectWorkorder($row->produk_id, $workorder_id, $workorder_nama)["gudang_nama"];
                    //-GUDANG WORKORDER PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderSubID'] = $workOrderID;
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderSubNama'] = $workOrderNama;
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangID'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama)["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangName'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama)["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangNama'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama)["gudang_nama"];

                    //-------
                    //endregion

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


                $pakai_ini = 0;
                if ($pakai_ini == 1) {

                    $workorder_id = $row->fase_id;
                    $produk_id = $row->produk_id;
                    $produk_nama = $row->produk_nama;
                    $id = $row->produk_dasar_id;
                    $nama = $row->produk_dasar_nama;
                    $harga = $row->harga;
                    $nilai = $row->nilai;
                    $jenis = $row->jenis;
                    $qty_kredit = $row->qty_kredit;
                    $qty_saldo = $row->qty_saldo;
                    $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";

                    $tmpJml_wo = $row->jml;
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

                    //-------------------------
                    if ((sizeof($workOrderValidate) > 0)) {
                        if (isset($workOrderValidate["enabled"]) && ($workOrderValidate["enabled"] == true)) {
                            $validateRule = $workOrderValidate["validateRule"];
                            foreach ($validateRule as $validateRuleSpec) {
                                $fsFilter = $validateRuleSpec["filter"];
                                $fsModel = $validateRuleSpec["model"];
                                $fsWarningLabel = $validateRuleSpec["warningLabel"];
                                $this->load->model("Mdls/$fsModel");
                                $ffss = New $fsModel();
                                makeFilter($fsFilter, (array)$row, $ffss);
                                $ffssTmp = $ffss->lookupAll()->result();
                                showLast_query("kuning");
                                cekHere(count($ffssTmp));
                                if (count($ffssTmp) == 0) {
                                    mati_disini($fsWarningLabel);
                                }
                            }
                        }
                    }

                    //-------------------------
//mati_disini(__LINE__);

                    $ppj = New MdlProdukProject();
                    $ppj->addFilter("id='$produk_id'");
                    $ppjTmp = $ppj->lookupAll()->result();
                    $customer_id = $ppjTmp[0]->customer_id;
                    $customer_nama = $ppjTmp[0]->customer_nama;
                    $keterangan = $ppjTmp[0]->keterangan;
                    $spek = $ppjTmp[0]->spek;
                    $start_dtime = $ppjTmp[0]->start_dtime;
                    $end_dtime = $ppjTmp[0]->end_dtime;


                    if ($jenis == "produk") {

                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "nama" => $nama,
                                "name" => $nama,
                                "jml_wo" => $tmpJml_wo,
                                "jml" => $tmpJml,
                                "qty" => $tmpJml,
                                "harga" => $harga,
                                "nilai_untung" => 0,
                                "nilai_rugi" => 0,
                                "nilai_final_rugilaba" => 0,
                                "subtotal" => $tmpJml * $harga,
                                "workorderID" => $workorder_id,// work order id
                                "workorderName" => "",
                                "produkID" => $produk_id,// project id
                                "produkName" => $produk_nama,
                                "qty_kredit" => $qty_kredit,
                                "qty_saldo" => $qty_saldo,
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


                            foreach ($fieldSrcs as $key => $src) {
                                $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                            }
                            if ($subAmountConfig != null) {
                                $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                            }
                            else {
                                $tmp['subtotal'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id] = $tmp;

                            //-------------------------
                            $_SESSION[$cCode]['main']['projectID'] = isset($row->produk_id) ? $row->produk_id : "";
                            $_SESSION[$cCode]['main']['projectName'] = isset($row->produk_nama) ? $row->produk_nama : "";

                            $_SESSION[$cCode]['main']['pihakProjekID'] = isset($row->produk_id) ? $row->produk_id : "";
                            $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                            $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->produk_nama) ? $row->produk_nama : "";
                            $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                            $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                            $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                            //-GUDANG PER PROJECT------
                            $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_id"];
                            $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_nama"];
                            $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_nama"];
                            //-------
                            $_SESSION[$cCode]['main']['pihakProjekCustomerID'] = $customer_id;
                            $_SESSION[$cCode]['main']['pihakProjekCustomerNama'] = $customer_nama;
                            $_SESSION[$cCode]['main']['pihakProjekWorkorderID'] = $workorder_id;
                            $_SESSION[$cCode]['main']['pihakProjekWorkorderNama'] = isset($workorder_nama) ? $workorder_nama : "";
                            $_SESSION[$cCode]['main']['pihakProjekStartDtime'] = $start_dtime;
                            $_SESSION[$cCode]['main']['pihakProjekEndDtime'] = $end_dtime;
                            //-------
                        }
                        else {

                            // region validate qty yang diambil, manual ya prends....
                            if (isset($_GET["newQty"]) && $_GET["newQty"] > 0) {
                                $msg = "Jumlah $produk_nama yang diambil melebihi sisa alokasi work order.";
                                mati_disini($msg);
                            }
                            // endregion validate qty yang diambil, manual ya prends....


                            if (sizeof($itemNumLabels) > 0) {

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
            }
//            mati_disini(__LINE__);

//            if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
//                $nilai_untung = 0;
//                $nilai_rugi = 0;
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                    $nilai_untung += $iSpec['nilai_untung'];
//                    $nilai_rugi += $iSpec['nilai_rugi'];
//                }
//
//                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
//                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
//            }

//            if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                $_SESSION[$cCode]['main']['harga'] = 0;
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                }
//            }

        }
        else {

            $msg = "Daftar bahan baku yang diperlukan belum diatur. Segera hubungi admin.";
            mati_disini($msg);

        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }
    //----------------------
    public function selectProject__()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;


        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $selectorSubSrcModel = isset($this->configUi[$this->jenisTr]['selectorSubSrcModel']) ? $this->configUi[$this->jenisTr]['selectorSubSrcModel'] : null;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");


        if (isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
            $_SESSION[$cCode]['items2_sum'] = array();
        }


        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $workOrderValidate = isset($this->configUi[$this->jenisTr]['workOrderValidate']) ? $this->configUi[$this->jenisTr]['workOrderValidate'] : array();
        $selectorSubFilters = isset($this->configUi[$this->jenisTr]['selectorSubFilters']) ? $this->configUi[$this->jenisTr]['selectorSubFilters'] : array();


        $tmpB = $b->lookupByID($id)->result();
//        $b->addFilter("fase_id='$id'");
//        $tmpB = $b->lookupAll()->result();
//        showLast_query("biru");
//        arrPrintPink($tmpB);
//        arrPrintHijau($workOrderValidate);


//        mati_disini(__LINE__ . " ::: " . count($tmpB));
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
//                arrPrint($row);
                //-------------------------
                if ((sizeof($workOrderValidate) > 0)) {
                    if (isset($workOrderValidate["enabled"]) && ($workOrderValidate["enabled"] == true)) {
                        $validateRule = $workOrderValidate["validateRule"];
                        foreach ($validateRule as $validateRuleSpec) {
                            $fsFilter = $validateRuleSpec["filter"];
                            $fsModel = $validateRuleSpec["model"];
                            $fsWarningLabel = $validateRuleSpec["warningLabel"];
                            $this->load->model("Mdls/$fsModel");
                            $ffss = New $fsModel();
                            makeFilter($fsFilter, (array)$row, $ffss);
                            $ffssTmp = $ffss->lookupAll()->result();
//                            showLast_query("kuning");
//                            cekHere(count($ffssTmp));
                            if (count($ffssTmp) == 0) {
                                $msg = "Project " . $row->produk_nama . ", ";
                                mati_disini($msg . $fsWarningLabel);
                            }
                        }
                    }
                }
                //-------------------------

                $workOrderID = isset($row->id) && strlen($row->id) > 0 ? $row->id : 0;
                $workOrderNama = isset($row->nama) && strlen($row->nama) > 0 ? $row->nama : "";
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {

//                    $tmp = array(
//                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
//                        "id" => $id,
//                        "jml" => $tmpJml,
//                        "harga" => 0,
//                        "nilai_untung" => 0,
//                        "nilai_rugi" => 0,
//                        "nilai_final_rugilaba" => 0,
//                        "subtotal" => 0,
//                    );
//
//                    foreach ($fieldSrcs as $key => $src) {
////                        cekHere($row->$src . " " . $src);
//                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
//                    }
//                    if ($subAmountConfig != null) {
//                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
//                    } else {
//                        $tmp['subtotal'] = 0;
//                    }
//                    $_SESSION[$cCode]['items'][$id] = $tmp;

                    //DETAIL WORK ORDER/KOMPOSISI-----------------------
                    if ($selectorSubSrcModel != null) {
                        $this->load->model("Mdls/$selectorSubSrcModel");
                        $bb = New $selectorSubSrcModel();
                        $bb->addFilter("sub_fase_id='$id'");
                        if (sizeof($selectorSubFilters) > 0) {
                            makeFilter($selectorSubFilters, $_SESSION[$cCode]["main"], $bb);
                        }
                        $tmpBB = $bb->lookupAll()->result();
//                        showLast_query("biru");
//                        arrPrint($tmpBB);
//                        mati_disini(__LINE__);
                        if (sizeof($tmpBB) > 0) {
                            foreach ($tmpBB as $rowB) {
                                $workorder_id = $rowB->fase_id;
                                $workorder_nama = isset($rowB->fase_nama) ? $rowB->fase_nama : "";
                                $produk_id = $rowB->produk_id;
                                $produk_nama = $rowB->produk_nama;
                                $id = $rowB->produk_dasar_id;
                                $nama = $rowB->produk_dasar_nama;
                                $harga = $rowB->harga;
                                $nilai = $rowB->nilai;
                                $jenis = $rowB->jenis;
                                $qty_debet = $rowB->qty_debet;
                                $qty_kredit = $rowB->qty_kredit;
                                $qty_saldo = $rowB->qty_saldo;
                                $satuan = isset($rowB->satuan) && strlen($rowB->satuan) > 0 ? $rowB->satuan : "n/a";

                                $tmpJml_wo = $rowB->jml;
                                $tmpJml = 0;

                                if ($jenis == "produk") {
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                        $tmp = array(
                                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                            "id" => $id,
                                            "nama" => $nama,
                                            "name" => $nama,
                                            "jml_wo" => $tmpJml_wo,
                                            "jml" => $tmpJml,
                                            "qty" => $tmpJml,
                                            "harga" => $harga,
                                            "nilai_untung" => 0,
                                            "nilai_rugi" => 0,
                                            "nilai_final_rugilaba" => 0,
                                            "subtotal" => $tmpJml * $harga,
                                            "workorderID" => $workorder_id,// work order id
                                            "workorderName" => "",
                                            "produkID" => $produk_id,// project id
                                            "produkName" => $produk_nama,
                                            "qty_debet" => $qty_debet,// jumlah sesuai work order
                                            "qty_kredit" => $qty_kredit,// jumlah sudah diambil
                                            "qty_saldo" => $qty_saldo,// sisa yang belum diambil
                                        );

                                        foreach ($fieldSrcs as $key => $src) {
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
//                                    else {
//
//                                        // region validate qty yang diambil, manual ya prends....
//                                        if (isset($_GET["newQty"]) && $_GET["newQty"] > 0) {
//                                            $msg = "Jumlah $produk_nama yang diambil melebihi sisa alokasi work order.";
//                                            mati_disini($msg);
//                                        }
//                                        // endregion validate qty yang diambil, manual ya prends....
//
//
//                                        if (sizeof($itemNumLabels) > 0) {
//
//                                            foreach ($itemNumLabels as $key => $label) {
//                                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
//                                                    $newValue = $_GET[$key];
//                                                    $tmp[$key] = $newValue;
//                                                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
//                                                    echo "replacing value for $key with " . $newValue . "<br>";
//                                                }
//                                            }
//
//                                            if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
//                                                if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
//                                                    $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
//                                                }
//                                                if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
//                                                    $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
//                                                }
//                                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
//                                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);
//
//                                            }
//
//                                            if ($subAmountConfig != null) {
//                                                $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
//                                            }
//                                            else {
//                                                $tmp['subtotal'] = 0;
//                                            }
//                                            $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
//                                        }
//
//
//                                    }
                                }


                            }
                        }
                    }
                    else {
                        cekMerah("tidak ada sub_src_model");
                    }
//                    arrPrintPink($_SESSION[$cCode]['items']);
//                    mati_disini(__LINE__);


                    //region MAIN
                    if ($row->produk_id > 0) {
                        $ppj = New MdlProdukProject();
                        $ppj->addFilter("id='$produk_id'");
                        $ppjTmp = $ppj->lookupAll()->result();
                        $customer_id = $ppjTmp[0]->customer_id;
                        $customer_nama = $ppjTmp[0]->customer_nama;
                        $keterangan = $ppjTmp[0]->keterangan;
                        $spek = $ppjTmp[0]->spek;
                        $start_dtime = $ppjTmp[0]->start_dtime;
                        $end_dtime = $ppjTmp[0]->end_dtime;
                    }


                    $_SESSION[$cCode]['main']['projectID'] = $row->produk_id;
                    $_SESSION[$cCode]['main']['projectName'] = $row->produk_nama;

                    $_SESSION[$cCode]['main']['pihakProjekID'] = $row->produk_id;
                    $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                    $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->produk_nama) ? $row->produk_nama : "";
                    $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                    $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                    $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                    $_SESSION[$cCode]['main']['pihakProjekCustomerID'] = $customer_id;
                    $_SESSION[$cCode]['main']['pihakProjekCustomerNama'] = $customer_nama;
                    $_SESSION[$cCode]['main']['pihakProjekKeterangan'] = $keterangan;
                    $_SESSION[$cCode]['main']['pihakProjekSpek'] = $spek;
                    $_SESSION[$cCode]['main']['pihakProjekStartDate'] = $start_dtime;
                    $_SESSION[$cCode]['main']['pihakProjekEndDate'] = $end_dtime;
                    //-GUDANG SITE PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($row->produk_id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($row->produk_id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($row->produk_id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                    //-GUDANG WORKORDER PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderID'] = $workorder_id;
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderNama'] = $workorder_nama;
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderGudangID'] = getDefaultWarehouseProjectWorkorder($row->produk_id, $workorder_id, $workorder_nama)["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderGudangName'] = getDefaultWarehouseProjectWorkorder($row->produk_id, $workorder_id, $workorder_nama)["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderGudangNama'] = getDefaultWarehouseProjectWorkorder($row->produk_id, $workorder_id, $workorder_nama)["gudang_nama"];
                    //-GUDANG WORKORDER PER PROJECT------
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderSubID'] = $workOrderID;
                    $_SESSION[$cCode]['main']['pihakProjekWorkOrderSubNama'] = $workOrderNama;
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangID'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama)["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangName'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama)["gudang_nama"];
                    $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangNama'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama)["gudang_nama"];

                    //-------
                    //endregion

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


                $pakai_ini = 0;
                if ($pakai_ini == 1) {

                    $workorder_id = $row->fase_id;
                    $produk_id = $row->produk_id;
                    $produk_nama = $row->produk_nama;
                    $id = $row->produk_dasar_id;
                    $nama = $row->produk_dasar_nama;
                    $harga = $row->harga;
                    $nilai = $row->nilai;
                    $jenis = $row->jenis;
                    $qty_kredit = $row->qty_kredit;
                    $qty_saldo = $row->qty_saldo;
                    $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";

                    $tmpJml_wo = $row->jml;
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

                    //-------------------------
                    if ((sizeof($workOrderValidate) > 0)) {
                        if (isset($workOrderValidate["enabled"]) && ($workOrderValidate["enabled"] == true)) {
                            $validateRule = $workOrderValidate["validateRule"];
                            foreach ($validateRule as $validateRuleSpec) {
                                $fsFilter = $validateRuleSpec["filter"];
                                $fsModel = $validateRuleSpec["model"];
                                $fsWarningLabel = $validateRuleSpec["warningLabel"];
                                $this->load->model("Mdls/$fsModel");
                                $ffss = New $fsModel();
                                makeFilter($fsFilter, (array)$row, $ffss);
                                $ffssTmp = $ffss->lookupAll()->result();
                                showLast_query("kuning");
                                cekHere(count($ffssTmp));
                                if (count($ffssTmp) == 0) {
                                    mati_disini($fsWarningLabel);
                                }
                            }
                        }
                    }

                    //-------------------------
//mati_disini(__LINE__);

                    $ppj = New MdlProdukProject();
                    $ppj->addFilter("id='$produk_id'");
                    $ppjTmp = $ppj->lookupAll()->result();
                    $customer_id = $ppjTmp[0]->customer_id;
                    $customer_nama = $ppjTmp[0]->customer_nama;
                    $keterangan = $ppjTmp[0]->keterangan;
                    $spek = $ppjTmp[0]->spek;
                    $start_dtime = $ppjTmp[0]->start_dtime;
                    $end_dtime = $ppjTmp[0]->end_dtime;


                    if ($jenis == "produk") {

                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "nama" => $nama,
                                "name" => $nama,
                                "jml_wo" => $tmpJml_wo,
                                "jml" => $tmpJml,
                                "qty" => $tmpJml,
                                "harga" => $harga,
                                "nilai_untung" => 0,
                                "nilai_rugi" => 0,
                                "nilai_final_rugilaba" => 0,
                                "subtotal" => $tmpJml * $harga,
                                "workorderID" => $workorder_id,// work order id
                                "workorderName" => "",
                                "produkID" => $produk_id,// project id
                                "produkName" => $produk_nama,
                                "qty_kredit" => $qty_kredit,
                                "qty_saldo" => $qty_saldo,
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


                            foreach ($fieldSrcs as $key => $src) {
                                $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                            }
                            if ($subAmountConfig != null) {
                                $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                            }
                            else {
                                $tmp['subtotal'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id] = $tmp;

                            //-------------------------
                            $_SESSION[$cCode]['main']['projectID'] = isset($row->produk_id) ? $row->produk_id : "";
                            $_SESSION[$cCode]['main']['projectName'] = isset($row->produk_nama) ? $row->produk_nama : "";

                            $_SESSION[$cCode]['main']['pihakProjekID'] = isset($row->produk_id) ? $row->produk_id : "";
                            $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                            $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->produk_nama) ? $row->produk_nama : "";
                            $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                            $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                            $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                            //-GUDANG PER PROJECT------
                            $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_id"];
                            $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_nama"];
                            $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($row->produk_id, $row->produk_nama)["gudang_nama"];
                            //-------
                            $_SESSION[$cCode]['main']['pihakProjekCustomerID'] = $customer_id;
                            $_SESSION[$cCode]['main']['pihakProjekCustomerNama'] = $customer_nama;
                            $_SESSION[$cCode]['main']['pihakProjekWorkorderID'] = $workorder_id;
                            $_SESSION[$cCode]['main']['pihakProjekWorkorderNama'] = isset($workorder_nama) ? $workorder_nama : "";
                            $_SESSION[$cCode]['main']['pihakProjekStartDtime'] = $start_dtime;
                            $_SESSION[$cCode]['main']['pihakProjekEndDtime'] = $end_dtime;
                            //-------
                        }
                        else {

                            // region validate qty yang diambil, manual ya prends....
                            if (isset($_GET["newQty"]) && $_GET["newQty"] > 0) {
                                $msg = "Jumlah $produk_nama yang diambil melebihi sisa alokasi work order.";
                                mati_disini($msg);
                            }
                            // endregion validate qty yang diambil, manual ya prends....


                            if (sizeof($itemNumLabels) > 0) {

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
            }
//            mati_disini(__LINE__);

        }
        else {

            $msg = "Daftar bahan baku yang diperlukan belum diatur. Segera hubungi admin.";
            mati_disini($msg);

        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }


    public function selectDefine()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : $this->session->login["ppnFactor"];


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $selectorProcessor = isset($this->configUi[$this->jenisTr]['selectorProcessor']) ? $this->configUi[$this->jenisTr]['selectorProcessor'] : NULL;

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
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

//                    arrPrint($fieldSrcs);
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

                        $_SESSION[$cCode]['main']['projectID'] = $id;
                        $_SESSION[$cCode]['main']['projectName'] = $row->nama;

                        $_SESSION[$cCode]['main']['pihakProjekID'] = $id;
                        $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
                        $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->nama) ? $row->nama : "";
                        $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
                        $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
                        $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
                        //-GUDANG PER PROJECT------
                        $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_id"];
                        $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                        $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
                        //-------

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
        }
        else {
//            arrPrintHijau($_GET);
            if (sizeof($itemNumLabels) > 0) {
                foreach ($itemNumLabels as $key => $label) {
                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                        $newValue = $_GET[$key];
                        $tmp[$key] = $newValue;
                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;
//                        echo "replacing value for $key with " . $newValue . "<br>";
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
//arrPrintHijau($_GET);
            foreach ($_GET as $keyy => $vall) {
                if (!array_key_exists($keyy, $itemNumLabels)) {
                    $_SESSION[$cCode]['items'][$id][$keyy] = $vall;
//                    cekHijau("[$keyy] = $vall");
                    $_SESSION[$cCode]['main'][$keyy] = $vall;
                }
            }

        }


//        arrPrintHijau($_SESSION[$cCode]['items']);
//        mati_disini(__LINE__);


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
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

        if(isset($_GET["r"])){
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    }

    public function selectUangMuka()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $selectorProcessor = isset($this->configUi[$this->jenisTr]['selectorProcessor']) ? $this->configUi[$this->jenisTr]['selectorProcessor'] : NULL;

        $tmpB = $b->lookupByID($id)->result();
//showLast_query("biru");

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
//arrPrintWebs($row);
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
//                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "handler" => $this->uri->segment(1) . "/" . $selectorProcessor,
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

//                    arrPrint($fieldSrcs);
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

//                    $_SESSION[$cCode]['main']['projectID'] = $id;
//                    $_SESSION[$cCode]['main']['projectName'] = $row->nama;
//
//                    $_SESSION[$cCode]['main']['pihakProjekID'] = $id;
//                    $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($row->id_master) ? $row->id_master : "";
//                    $_SESSION[$cCode]['main']['pihakProjekName'] = isset($row->nama) ? $row->nama : "";
//                    $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($row->value_src) ? $row->value_src : "";
//                    $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($row->revertStep) ? $row->revertStep : false;
//                    $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($row->detailGate) ? $row->detailGate : "items";
//                    //-GUDANG PER PROJECT------
//                    $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_id"];
//                    $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
//                    $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
//                    //-------

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

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor($ppnFactor);
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
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

}