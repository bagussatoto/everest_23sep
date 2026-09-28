<?php

require_once "Modul_Controller.php";

class _processSelectNotaItem extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "items" => array(),
            );
        }
        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }
        $this->blackList = array(
            "jml", "qty",
        );
    }

    public function select()
    {

        $this->load->library("FieldCalculator");
        $this->load->model("MdlTransaksi");

        $cal = new FieldCalculator();
        $trs = new MdlTransaksi();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            $detailResetList = array(
                "items",
                "out_detail",
                "out_detail2",
                "tableIn_detail",
                "tableIn_detail2",
                "tableIn_detail_values",
                "tableIn_detail2_sum",
                "tableIn_detail_values2_sum",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode]["$sSName"]);
            }
        }

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal");
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal");
        }


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

//        $referenceJenisTr = $this->configUi[$this->jenisTr]['referenceJenisTr'];
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $referenceConfig = isset($this->configUi[$this->jenisTr]['referenceFields']) ? $this->configUi[$this->jenisTr]['referenceFields'] : null;
//        $externalConfig = isset($this->configCore[$referenceJenisTr]['externalValues']) ? $this->configCore[$referenceJenisTr]['externalValues'] : array();
        $itemsInjectorConfig = isset($this->configUi[$this->jenisTr]['itemsInjector']) ? $this->configUi[$this->jenisTr]['itemsInjector'] : array();

        $topReferenceTrans = isset($this->configUi[$this->jenisTr]['topReferenceTrans']) ? $this->configUi[$this->jenisTr]['topReferenceTrans'] : array();

        //  membaca isi nota
        $tmpB = $b->lookupByID($id)->result();
//        cekMerah($this->db->last_query() . " :: $selectorSrcModel");
//arrPrint($tmpB);

        //  membaca registry shipment (582spd)
        $tmpRegistry = $trs->lookupDataRegistriesByMasterID($id)->result();
//        cekMerah("[$id] " . $this->db->last_query());

        $masterAddFields = array();
        $itemsFields = array();
        $masterAddValues = array();
        $tmpMasterInValues = array();
        $tmpDetailValues = array();
        if (sizeof($tmpRegistry) > 0) {
            foreach ($tmpRegistry as $row) {

                foreach ($row as $key_reg => $val_reg) {

                    switch ($key_reg) {
                        case "master_add_fields":
                            $masterAddFields = unserialize(base64_decode($val_reg));
                            break;
                        case "master_add_values":
                            $masterAddValues = unserialize(base64_decode($val_reg));
                            break;
                        case "tableIn_detail_values":
                            $tmpDetailValues = unserialize(base64_decode($val_reg));
                            break;
                        case "tableIn_master_values":
                            $tmpMasterInValues = unserialize(base64_decode($val_reg));
                            break;
                        case "items":
                            $itemsFields = unserialize(base64_decode($val_reg));
                            break;
                    }
                }
            }
        }
//arrPrint($itemsFields);
//mati_disini();
//        arrprint($tmpDetailValues);

        if (sizeof($tmpB) > 0) {
//arrPrint($tmpB);
            if (sizeof($topReferenceTrans) > 0) {
                if (isset($topReferenceTrans['enabled']) && ($topReferenceTrans['enabled'] == true)) {
                    $trs->setFilters(array());
                    $trs->addFilter("id='" . $tmpB[0]->id_top . "'");
                    $refTmp = $trs->lookupAll()->result();
                    if (sizeof($refTmp) > 0) {
                        foreach ($topReferenceTrans['gate'] as $gate => $gateSpec) {
                            foreach ($gateSpec as $gateSrc => $gateTarget) {
                                $_SESSION[$cCode][$gate][$gateTarget] = isset($refTmp[0]->$gateSrc) ? $refTmp[0]->$gateSrc : "";
                            }
                        }
                    }
                }
            }

            $_SESSION[$cCode]['main']['seluruhnya'] = true;
            $_SESSION[$cCode]['main']['referenceID'] = $id;
            foreach ($tmpB as $row) {
                $id = $row->produk_id;
                $name = $row->produk_nama;
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

                if ($tmpJml > 0) {

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
                                        "satuan" => $satuan,
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
                    if (!isset($_SESSION[$cCode]['items']) || (!array_key_exists($id, $_SESSION[$cCode]['items']))) {
//                        cekmerah("belum ada di items, mau menambahkan");
                        $tmp = array(
                            "handler" => $this->modul . "/" . $this->uri->segment(2),
                            "id" => $id,
                            "name" => $name,
                            "nama" => $name,
                            "jml" => $tmpJml,
                            "harga" => 0,
                            "subtotal" => 0,
                            "disabled" => $tmpDisabled,
                        );

                        //region mengambil harga beli per-item
                        if (sizeof($priceConfig) > 0) {
                            $mdlName = $priceConfig['model'];
                            $this->load->model("Mdls/" . $mdlName);
                            $h = new $mdlName();
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
//                        $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $tmpH = $h->lookupAll($id)->result();
                            cekMerah($this->db->last_query());
                            if (sizeof($tmpH) > 0) {
                                foreach ($tmpH as $hSpec) {
                                    foreach ($priceConfig['key_label'] as $key => $val) {
                                        if ($key == $hSpec->jenis_value) {
                                            $tmp[$val] = isset($hSpec->nilai) ? ($hSpec->nilai + 0) : 0;
                                        }
                                    }
                                }
                            }
//                        $tmp['harga'] = isset($tmpH[0]->nilai) ? $tmpH[0]->nilai : 0;
                        }
                        //endregion

                        //region injector ke items, detail isi nota

                        if (isset($tmpDetailValues[$id]) && sizeof($tmpDetailValues[$id]) > 0) {
                            $arrDiff = array_diff_key($tmpDetailValues[$id], array_flip($this->blackList));
                            foreach ($arrDiff as $key => $val) {
                                cekKuning($key . " diisi dengan " . $val);
                                $tmp[$key] = $val;
                            }

                        }
                        //endregion

                        if (sizeof($itemsInjectorConfig) && $itemsInjectorConfig['enabled'] == true) {
                            foreach ($itemsInjectorConfig['kolom'] as $target => $source) {
                                $tmp[$target] = isset($itemsFields[$id][$source]) ? $itemsFields[$id][$source] : "";
                            }
                        }

                        foreach ($fieldSrcs as $key2 => $src2) {
                            if (is_array($src2) && sizeof($src2) > 0) {
                                foreach ($src2 as $srcSpec) {
                                    if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                        cekUngu("ambil gerbang key -> $srcSpec");
                                        $tmp[$key2] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : 0);
                                    }

                                }
                            }
                            else {
                                $tmp[$key2] = makeValue($src2, $tmp, $tmp, $row->$src2);
                            }

                        }

                        //===perhitungan subtotal
                        $cal = new FieldCalculator();


                        if ($subAmountConfig != null) {
//                            $subtotal = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                            $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                        }
                        else {
                            $subtotal = 0;
                            cekHijau("subtotal NOL");
                        }

                        $tmp["subtotal"] = $subtotal;
//arrPrintPink($tmp);
                        $_SESSION[$cCode]['items'][$id] = $tmp;

                    }
                    else {
                        cekmerah("sudah ada di items, mau update subtotal");
                        if ($subAmountConfig != null) {
                            $subtotal = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $subtotal = 0;
                            cekHijau("subtotal NOL");
                        }
                    }

                    if (sizeof($referenceConfig) > 0) {
                        foreach ($referenceConfig as $key => $label) {
                            $_SESSION[$cCode]['main'][$key] = $row->$label;
                        }
                    }
                }
            }


            if (sizeof($masterAddFields) > 0) {
                foreach ($masterAddFields as $key => $value) {
                    $_SESSION[$cCode]['main_add_fields'][$key] = $value;
                    $_SESSION[$cCode]['main'][$key] = $value;

                }
            }
            if (sizeof($masterAddValues) > 0) {
                foreach ($masterAddValues as $key => $value) {
                    $_SESSION[$cCode]['main_add_values'][$key] = $value;
                    $_SESSION[$cCode]['main'][$key] = $value;

                }
            }
            if (sizeof($tmpMasterInValues) > 0) {
                foreach ($tmpMasterInValues as $key => $value) {
                    $_SESSION[$cCode]['main'][$key] = $value;

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

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if(!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"]==null)){
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $stepNum = isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1;
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
        $cCode = $this->cCode;
        $referenceJenisTr = $this->configUi[$this->jenisTr]['referenceJenisTr'];
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $externalConfig = isset($this->configCore[$referenceJenisTr]['externalValues']) ? $this->configCore[$referenceJenisTr]['externalValues'] : array();


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


        $detailResetList = array(
            "items",
            "out_detail",
            "out_detail2",
            "tableIn_detail",
            "tableIn_detail2",
            "tableIn_detail_values",
            "tableIn_detail2_sum",
            "tableIn_detail_values2_sum",
        );
        foreach ($detailResetList as $sSName) {
            cekkuning("resetting $sSName");
            $_SESSION[$cCode]["$sSName"][$id] = null;
            unset($_SESSION[$cCode]["$sSName"][$id]);
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
//                    if (isset($_SESSION[$cCode]['out_master'][$key])) {
//                        $_SESSION[$cCode]['out_master'][$key] = null;
//                        unset($_SESSION[$cCode]['out_master'][$key]);
//                    }
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
//                if (isset($_SESSION[$cCode]['out_master'][$key])) {
//                    $_SESSION[$cCode]['out_master'][$key] = null;
//                    unset($_SESSION[$cCode]['out_master'][$key]);
//                }
                if (isset($_SESSION[$cCode]['main_add_values'][$key])) {
                    $_SESSION[$cCode]['main_add_values'][$key] = null;
                    unset($_SESSION[$cCode]['main_add_values'][$key]);
                }


            }
        }


        $stepNum = isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1;
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

    public function edit()
    {
        $id = $_GET['id'];
        $cCode = $this->cCode;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $referenceJenisTr = $this->configUi[$this->jenisTr]['referenceJenisTr'];
        $externalConfig = isset($this->configCore[$referenceJenisTr]['externalValues']) ? $this->configCore[$referenceJenisTr]['externalValues'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

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
            if (isset($_GET['newQty'])) {

                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];

                if ($subAmountConfig != null) {
//                            $subtotal = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    $subtotal = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                }
                else {
                    $subtotal = 0;
                    cekHijau("subtotal NOL");
                }

                $_SESSION[$cCode]['items'][$id]['subtotal'] = $subtotal;


                if (sizeof($externalConfig) > 0) {
                    foreach ($externalConfig as $keyName => $arrVal) {
                        if (isset($arrVal['mdlName']) && strlen($arrVal['mdlName']) > 0) {
                            $key = $keyName . "_src";
                            if (isset($_SESSION[$cCode]['main'][$key])) {
                                $_SESSION[$cCode]['main'][$key] = null;
                                unset($_SESSION[$cCode]['main'][$key]);
                            }
//                            if (isset($_SESSION[$cCode]['out_master'][$key])) {
//                                $_SESSION[$cCode]['out_master'][$key] = null;
//                                unset($_SESSION[$cCode]['out_master'][$key]);
//                            }
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
//                            if (isset($_SESSION[$cCode]['out_master'][$key])) {
//                                $_SESSION[$cCode]['out_master'][$key] = null;
//                                unset($_SESSION[$cCode]['out_master'][$key]);
//                            }
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
//                        if (isset($_SESSION[$cCode]['out_master'][$key])) {
//                            $_SESSION[$cCode]['out_master'][$key] = null;
//                            unset($_SESSION[$cCode]['out_master'][$key]);
//                        }
                        if (isset($_SESSION[$cCode]['main_add_values'][$key])) {
                            $_SESSION[$cCode]['main_add_values'][$key] = null;
                            unset($_SESSION[$cCode]['main_add_values'][$key]);
                        }
                    }
                }

            }
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if(!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"]==null)){
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $stepNum = isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1;
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

    public function cancel()
    {
        $id = $_GET['id'];
        $cCode = $this->cCode;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $referenceJenisTr = $this->configUi[$this->jenisTr]['referenceJenisTr'];
        $externalConfig = isset($this->configCore[$referenceJenisTr]['externalValues']) ? $this->configCore[$referenceJenisTr]['externalValues'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

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
            if (isset($_GET['newQty'])) {

                $max_jml = isset($_SESSION[$cCode]['items'][$id]['max_jml']) ? $_SESSION[$cCode]['items'][$id]['max_jml'] : 0;
                $packed_jml = isset($_SESSION[$cCode]['items'][$id]['packed_jml']) ? $_SESSION[$cCode]['items'][$id]['packed_jml'] : 0;
                $sent_jml = isset($_SESSION[$cCode]['items'][$id]['sent_jml']) ? $_SESSION[$cCode]['items'][$id]['sent_jml'] : 0;
                $cancel_jml = isset($_SESSION[$cCode]['items'][$id]['cancel_jml']) ? $_SESSION[$cCode]['items'][$id]['cancel_jml'] : 0;
                $req_cancel_jml = isset($_SESSION[$cCode]['items'][$id]['req_cancel_jml']) ? $_SESSION[$cCode]['items'][$id]['req_cancel_jml'] : 0;

                $max_qty = (int)$max_jml - ((int)$packed_jml + (int)$sent_jml + (int)$cancel_jml + (int)$req_cancel_jml);

                $jml_nambah = isset($_GET['newQty']) ? $_GET['newQty'] : 0;
                $nama = $_SESSION[$cCode]['items'][$id]['nama'];
                $kode = $_SESSION[$cCode]['items'][$id]['produk_kode'];
                $satuan = $_SESSION[$cCode]['items'][$id]['satuan'];

                if ($jml_nambah > $max_qty) {
                    $msg = "Insufficient m:$max_jml - s:$sent_jml - c:$cancel_jml - p:$packed_jml of:<br><red class='text-red'>$kode $nama</red><hr>$max_qty $satuan stock available";
                    $alerts = array(
                        "type" => "warning",
                        "title" => strtoupper($kode),
                        "html" => $msg,
                    );
                    echo swalAlert($alerts);
                    echo "<script>top.$('input[id_jml=$id]').val($max_qty);top.$('input[id_jml=$id]').trigger('blur');</script>";
                    die($msg);
                }

                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                $_SESSION[$cCode]['items'][$id]['outstanding'] = (int)$max_qty - (int)$_GET['newQty'];

                if ($subAmountConfig != null) {
                    $subtotal = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                }
                else {
                    $subtotal = 0;
                    cekHijau("subtotal NOL");
                }
                $_SESSION[$cCode]['items'][$id]['subtotal'] = $subtotal;
                if (sizeof($externalConfig) > 0) {
                    foreach ($externalConfig as $keyName => $arrVal) {
                        if (isset($arrVal['mdlName']) && strlen($arrVal['mdlName']) > 0) {
                            $key = $keyName . "_src";
                            if (isset($_SESSION[$cCode]['main'][$key])) {
                                $_SESSION[$cCode]['main'][$key] = null;
                                unset($_SESSION[$cCode]['main'][$key]);
                            }
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
                        if (isset($_SESSION[$cCode]['main_add_values'][$key])) {
                            $_SESSION[$cCode]['main_add_values'][$key] = null;
                            unset($_SESSION[$cCode]['main_add_values'][$key]);
                        }
                    }
                }
            }
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if(!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"]==null)){
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $stepNum = isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1;
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