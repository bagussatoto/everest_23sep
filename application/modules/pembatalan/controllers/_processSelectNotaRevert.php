<?php
require_once "Modul_Controller.php";

class _processSelectNotaRevert extends Modul_Controller
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
        $this->whitelist = array(
            "pihakExternID",
            "pihakExternMasterID",
            "pihakExternName",
            "pihakExternValueSrc",
            "pihakExternRevertStep",
            "pihakExternDetailGate",
            "pihakExternRevertRequest",
        );
        $whitelistMain = array();
        foreach ($this->whitelist as $list) {
            $whitelistMain[$list] = isset($_SESSION[$cCode]['main'][$list]) ? $_SESSION[$cCode]['main'][$list] : "";
        }
        $this->whitelistMain = $whitelistMain;
        $this->jenisTrException = array("9911", "9912");
    }

// START OF COMPLETE REPEATED LOGIC
    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
// END OF COMPLETE REPEATED LOGIC
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


//        if (isset($_SESSION[$cCode]['items'][$id])) {
//            $_SESSION[$cCode]['items'][$id] = null;
//            unset($_SESSION[$cCode]['items'][$id]);
//        }
//        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
//            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
//            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
//        }

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
//            cekkuning("resetting $sSName");
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

// START OF COMPLETE REPEATED LOGIC
    public function edit()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
// END OF COMPLETE REPEATED LOGIC
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

    public function cancel()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
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

    //----------------------------
    public function select()
    {
        $starttime = microtime(true);

        $this->load->library("FieldCalculator");
        $this->load->model("MdlTransaksi");
        $arrRekening_coa = rekening_coa_he_accounting();
        $arrPenggantiRekening = array(
            "biaya lain lain" => "beban lain lain",
        );
        $arrDropRekening = array(
            "hutang install",
            "ongkos install",
        );
        $arrAdjustmentRekening = array(
            "srcAccount",
            "targetAccount",
            "target3Account",
            "target4Account",
        );

        $cal = new FieldCalculator();
        $trs = new MdlTransaksi();

        $transID_ref = $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $cCode = "_TR_" . $this->jenisTr;

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            $detailResetList = array(
                "main",
                "items",
                "items2",
                "items2_sum",
                "items3",
                "items3_sum",
                "tableIn_detail",
                "tableIn_detail2",
                "tableIn_detail_values",
                "tableIn_detail2_sum",
                "tableIn_detail_values2_sum",
                "tableIn_detail_rsltItems",
                "tableIn_detail_values_rsltItems",
                "tableIn_detail_rsltItems2",
                "tableIn_detail_values_rsltItems2",
                "rsltItems",
                "rsltItems2",
                "items_komposisi",

                "items4",
                "items4_sum",
                "items9_sum",
                "items10_sum",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode]["$sSName"]);
            }
            if (isset($this->whitelistMain) && sizeof($this->whitelistMain) > 0) {
                foreach ($this->whitelistMain as $key => $val) {
                    if (!isset($_SESSION[$cCode]['main'][$key])) {
                        $_SESSION[$cCode]['main'][$key] = $val;
                    }
                }
            }
        }

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $referenceConfig = isset($this->configUi[$this->jenisTr]['referenceFields']) ? $this->configUi[$this->jenisTr]['referenceFields'] : null;
        $itemsInjectorConfig = isset($this->configUi[$this->jenisTr]['itemsInjector']) ? $this->configUi[$this->jenisTr]['itemsInjector'] : array();
        $fifoValidate = $this->config->item('heTransaksi_pembatalanFifoValidate') != NULL ? $this->config->item('heTransaksi_pembatalanFifoValidate') : array();
        $pembatalanChecker = $this->config->item('heTransaksi_pembatalanChecker') != NULL ? $this->config->item('heTransaksi_pembatalanChecker') : array();


        //  membaca isi nota
        $tmpB = $b->lookupByID($id)->result(); // ini membaca isi dari transaksi data
//        showLast_query("hitam");


        //  membaca registry shipment (582spd)
        $tmpRegistry = $trs->lookupDataRegistriesByMasterID($id)->result();
//        showLast_query("biru");


        $masterAddFields = array();
        $itemsFields = array();
        $items2Fields = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $masterAddValues = array();
        $tmpMasterInValues = array();
        $tmpDetailValues = array();
        $postProcessor = array();
        $preProcessor = array();
        $revert = array();
        $component = array();
        $main_elements = array();
        if (sizeof($tmpRegistry) > 0) {
            foreach ($tmpRegistry as $row) {
                foreach ($row as $key_reg => $val_reg) {
                    switch ($key_reg) {
                        case "main":
                            $masterMainFields = unserialize(base64_decode($val_reg));
                            break;
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
                        case "items2":
                            $items2Fields = unserialize(base64_decode($val_reg));
                            break;
                        case "items2_sum":
                            $items2_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items3":
                            $items3 = unserialize(base64_decode($val_reg));
                            break;
                        case "items3_sum":
                            $items3_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items4":
                            $items4 = unserialize(base64_decode($val_reg));
                            break;
                        case "items4_sum":
                            $items4_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items5_sum":
                            $items5_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items6":
                            $items6 = unserialize(base64_decode($val_reg));
                            break;
                        case "items6_sum":
                            $items6_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items7":
                            $items7 = unserialize(base64_decode($val_reg));
                            break;
                        case "items7_sum":
                            $items7_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items8_sum":
                            $items8_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items9_sum":
                            $items9_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items10_sum":
                            $items10_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "rsltItems":
                            $rsltItems = unserialize(base64_decode($val_reg));
                            break;
                        case "rsltItems2":
                            $rsltItems2 = unserialize(base64_decode($val_reg));
                            break;

                        case "preProcessor":
                            $preProcessor = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                        case "postProcessor":
                            $postProcessor = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                        case "jurnal_index":
                            $component = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                        case "main_elements":
                            $main_elements = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                    }
                }
            }
        }


        if (sizeof($tmpB) > 0) {

            $jenisMasterRef = $tmpB[0]->jenis_master;
            if (isset($fifoValidate[$jenisMasterRef])) {
                $mdlLoc = $fifoValidate[$jenisMasterRef]['mdlNameLoc'];
                $mdlName = $fifoValidate[$jenisMasterRef]['mdlName'];
                $mdlMethod = $fifoValidate[$jenisMasterRef]['method'];
                $label = isset($fifoValidate[$jenisMasterRef]['label']) ? $fifoValidate[$jenisMasterRef]['label'] : "";

                $this->load->model("$mdlLoc/$mdlName");
                $mdd = New $mdlName();
                $resultTmp = array();
                if (method_exists($mdd, $mdlMethod)) {
                    $resultTmp = $mdd->$mdlMethod($transID_ref);
                    showLast_query("orange");
                }
                else {
                    cekHitam("TIDAK ADA method");
                }
                foreach ($itemsFields as $iSpec) {
                    $i_qty = $iSpec['qty'];
                    $i_nama = htmlspecialchars($iSpec['name']);
                    $f_qty = isset($resultTmp[$iSpec['id']]) ? $resultTmp[$iSpec['id']] : 0;
                    if ($f_qty != $i_qty) {
                        $msg = "Jumlah stok $i_nama tidak cukup/sudah digunakan. Pembatalan transaksi tidak bisa dilanjutkan. $label";
                        die(lgShowAlertBiru($msg));
                    }
                }
            }

            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;
            cekungu("sampai membaca validasi fifo: $val");
            //--------------------------------------------

            // ================== tambahan membaca activity
            $tr_no_dibatalkan = $tmpB[0]->nomer;
            $tr_jenis_dibatalkan = $tmpB[0]->jenis;
            $tr_cabang_dibatalkan = $tmpB[0]->cabang_id;
            $referenceNumberSO = $masterMainFields["referenceNumberSO"];
            $referenceNumberPrepack = $masterMainFields["referenceNumber__3"];
            $tr_id_dibatalkan = $tmpB[0]->transaksi_id;
            $tr_id_master_dibatalkan = $tmpB[0]->id_master;
            $status_grn = $tmpB[0]->status_grn;
            $jenis_master = $tmpB[0]->jenis_master;
            $jenisTr_reference = $tmpB[0]->jenis;
            $tr_dtime_dibatalkan = $tmpB[0]->dtime;
            $tr_fulldate_dibatalkan = $tmpB[0]->fulldate;
            $tr_idshis = blobDecode($tmpB[0]->ids_his);
            $tr_pembayaran = $tmpB[0]->pembayaran;
            $arrIdsHis = array();
            if (sizeof($tr_idshis) > 0) {
                foreach ($tr_idshis as $step_his => $data_his) {
                    $arrIdsHis['referenceID_dibatalkan__' . $step_his] = $data_his["trID"];
                    $arrIdsHis['referenceNumber_dibatalkan__' . $step_his] = $data_his["nomer"];
                    $arrIdsHis['referenceNomer_dibatalkan__' . $step_his] = $data_his["nomer"];
                    $arrIdsHis['referenceDtime_dibatalkan__' . $step_his] = $data_his["dtime"];
                    $arrIdsHis['referenceFulldate_dibatalkan__' . $step_his] = $data_his["fulldate"];
                }
            }

            $tmpB[0]->referensi_so__id = isset($masterMainFields["referensi_so__id"]) ? $masterMainFields["referensi_so__id"] : 0;

            $pembatalanValidateConfig = isset($this->config->item('heTransaksi_pembatalanValidate')[$jenis_master]) ? $this->config->item('heTransaksi_pembatalanValidate')[$jenis_master] : array();
            if (sizeof($pembatalanValidateConfig) > 0) {
                foreach ($pembatalanValidateConfig as $pembatalanSpec) {
                    $mdlNameValidate = $pembatalanSpec['mdlName'];
                    $mdlFilterValidate = isset($pembatalanSpec['mdlFilter']) ? $pembatalanSpec['mdlFilter'] : array();

                    $this->load->model("Mdls/$mdlNameValidate");
                    $mdl_v = New $mdlNameValidate();
                    $mdl_v->setFilters(array());
                    if (sizeof($mdlFilterValidate) > 0) {
                        arrPrintCyan($mdlFilterValidate);
                        $rslt = makeFilter($mdlFilterValidate, (array)$tmpB[0], $mdl_v);
                    }
                    $validateTmp = $mdl_v->lookupAll()->result();
//                    showLast_query("pink");
//                    arrPrint($validateTmp);
//                    cekPink(count($validateTmp));
                    if (sizeof($validateTmp) > 0) {

                        if ($mdlNameValidate == "MdlPaymentSource") {
                            $this->load->model("Mdls/MdlRevertJurnalCabang");
                            $this->load->model("Mdls/MdlRevertJurnal");
                            $pp = new MdlRevertJurnal();
                            $pc = new MdlRevertJurnalCabang();
                            $dataTmpAliasPusat = $pp->lookUpAll()->result();
                            $dataTmpAliascabang = $pc->lookUpAll()->result();
                            $aliasData = array();
                            foreach ($dataTmpAliasPusat as $dataTmpAliasPusat_0) {
                                $aliasData[$dataTmpAliasPusat_0->id] = $dataTmpAliasPusat_0->nama;
                            }
                            foreach ($dataTmpAliascabang as $dataTmpAliascabang_0) {
                                $aliasData[$dataTmpAliascabang_0->id] = $dataTmpAliascabang_0->nama;
                            }

                            switch ($tr_jenis_dibatalkan) {
                                case "677":
                                case "675":
                                case "2677":
                                case "2675":
                                    $filter_produk_jenis = "expense";
                                    break;
                                default:
                                    $filter_produk_jenis = "invoice";
                                    break;
                            }

                            $produk_id = $validateTmp[0]->transaksi_id;
                            $trs->setFilters(array());
                            $trs->addFilter("transaksi_data.produk_id='$produk_id'");
//                            $trs->addFilter("transaksi_data.produk_jenis='expense'");
                            $trs->addFilter("transaksi_data.produk_jenis='$filter_produk_jenis'");
                            $trs->addFilter("transaksi.link_id='0'");
                            $tempPayment = $trs->lookupJoined_OLD()->result();
                            showLast_query("biru");
                            cekMerah(count($tempPayment));
                            arrPrintCyan($tempPayment);
                            if (sizeof($tempPayment) > 1) {
                                $arrPayment_no = array();
                                $arrPayment_alias = array();
                                foreach ($tempPayment as $ix => $tempSpec) {
                                    $arrPayment_no[$ix] = $tempSpec->nomer;
                                    $arrPayment_alias[$tempSpec->jenis] = $aliasData[$tempSpec->jenis];
                                }
                                $payment_no = implode(", ", $arrPayment_no);
                                $aliasDataTransaksi = implode(", ", $arrPayment_alias);
                            }
                            elseif (sizeof($tempPayment == 1)) {
                                $payment_no = $tempPayment[0]->nomer;
                                $payment_jenis = $tempPayment[0]->jenis;
                                $aliasDataTransaksi = $aliasData[$payment_jenis];
                            }
                            else {
                                $payment_no = "";
                                $payment_jenis = "";
                                $aliasDataTransaksi = "";
                            }
                            $cabang_id = $tempPayment[0]->cabang_id;
                            $title_cabang = $cabang_id < 0 ? "di DC/Pusat" : "di cabang";
                            $tr_cabang_dibatalkan2 = $cabang_id < 0 ? "di DC/Pusat" : "di cabang";
                            cekMerah($aliasDataTransaksi);
                            cekOrange($payment_no);
                            $payment_no_f = formatField_he_format("nomer_nolink", $payment_no);
                            $tr_no_dibatalkan_f = formatField_he_format("nomer_nolink", $tr_no_dibatalkan);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= $pembatalanSpec['label'] . "<br>";
                            $msg2 .= "<div class='text-bold text-red'>Nomer  " . $aliasDataTransaksi . " ($payment_no_f) </div>";
                            $msg2 .= "<div class='text-bold text-red'>Berhubung akan direject ($tr_no_dibatalkan_f) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
                            $msg2 .= "<ol>";
                            $msg2 .= "<li>Batalkan " . $aliasData[$payment_jenis] . " Nomer <strong>$payment_no_f</strong><br> " . $title_cabang . " dari menu pembatalan transaksi.</li>";
                            $msg2 .= "<li>Batalkan " . $aliasData[$tr_jenis_dibatalkan] . " Nomer <strong>$tr_no_dibatalkan_f</strong>" . $tr_cabang_dibatalkan2 . " dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
                            $msg2 .= "</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
//                            arrprint($tempPayment);
                            //join transaksi yang link_id=0
//                            matiHere();

                        }
                        else {

                            $msg = $pembatalanSpec['label'];
//                        cekHitam($msg);
                            die(lgShowAlertMerah($msg));
                            mati_disini(($msg));
                        }

                    }
                    if (isset($pembatalanSpec['detailCekQty']) && ($pembatalanSpec['detailCekQty'] == true)) {
                        $trs->setFilters(array());
//                        $dTr = $trs->lookupDetailTransaksi($id)->result();
                        $dTr = $trs->lookupDetailTransaksi($id);
//                        showLast_query("biru");
//                        arrPrintWebs($dTr);
                        $totalOrdJml = 0;
                        $totalValidQty = 0;
                        foreach ($dTr[$id] as $dTrSpec) {
                            $totalOrdJml += $dTrSpec->produk_ord_jml;
                            $totalValidQty += $dTrSpec->valid_qty;
                        }
                        if ($totalOrdJml != $totalValidQty) {
                            die(lgShowAlertMerah($pembatalanSpec['label']));
                            mati_disini($pembatalanSpec['label']);
                        }
                    }
                }

            }

            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;
            cekungu("sampai membaca pembatalan validate: $val [jenisTr_reference: $jenisTr_reference]");
            // ================== tambahan membaca activity
//            arrPrintCyan($pembatalanChecker[$jenisTr_reference]);
            if (sizeof($pembatalanChecker[$jenisTr_reference]) > 0) {
                foreach ($pembatalanChecker[$jenisTr_reference] as $mode_cek => $cekSpec) {
                    switch ($mode_cek) {
                        case "serial":
                            $mdlNameLoc = $cekSpec["mdlNameLoc"];
                            $mdlName = $cekSpec["mdlName"];
                            $mdlFilter = $cekSpec["mdlFilter"];
                            if (isset($cekSpec["pairedModel"])) {
                                $pairedMdlNameLoc = $cekSpec["pairedModel"]["mdlNameLoc"];
                                $pairedMdlName = $cekSpec["pairedModel"]["mdlName"];
                                $pairedMdlFilter = $cekSpec["pairedModel"]["mdlFilter"];
                                $pairedMdlFilterIn = $cekSpec["pairedModel"]["mdlFilterIn"];
                                $mdlFilterInSrc = $cekSpec["pairedModel"]["mdlFilterInSrc"];
                            }
                            $mdlFilterInSrcData = array();
                            $mdlFilterInTargetData = array();
                            $mdlFilterInSrcDataProduk = array();
                            $mdlFilterInTargetDataProduk = array();
                            $arrResultDiffProduk = array();
                            $arrResultAllProduk = array();
                            $mdlResultAllProduk = array();

                            $this->load->model("$mdlNameLoc/$mdlName");
                            $md = New $mdlName();
                            if (sizeof($mdlFilter) > 0) {
                                makeFilter($mdlFilter, $masterMainFields, $md);
                                arrPrint($mdlFilter);
                                arrPrint($masterMainFields);
                                $mdTmp = $md->lookupAll()->result();
                                showLast_query("hitam");
                                if (sizeof($mdTmp) > 0) {
                                    foreach ($mdTmp as $mdSpec) {
                                        $mdlFilterInSrcData[] = $mdSpec->$mdlFilterInSrc;
                                        $mdlFilterInSrcDataProduk[$mdSpec->produk_id][$mdSpec->produk_sku_part_nama][] = $mdSpec->$mdlFilterInSrc;
                                        $mdlResultAllProduk[$mdSpec->produk_id][$mdSpec->produk_sku_part_nama][] = $mdSpec;
                                    }
                                }

                                if (isset($cekSpec["pairedModel"])) {
                                    $this->load->model("$pairedMdlNameLoc/$pairedMdlName");
                                    $mdp = New $pairedMdlName();
                                    if (sizeof($pairedMdlFilter) > 0) {
                                        if (sizeof($mdlFilterInSrcData) > 0) {
                                            $mdp->addFilter("$pairedMdlFilterIn in ('" . implode("','", $mdlFilterInSrcData) . "')");
                                        }
                                        makeFilter($pairedMdlFilter, $masterMainFields, $mdp);
                                        $mdpTmp = $mdp->lookupAll()->result();
//                                        showLast_query("biru");
//                                    cekHere(count($mdpTmp));
                                        if (sizeof($mdpTmp) > 0) {
                                            foreach ($mdpTmp as $mdpSpec) {
//                                                arrPrint($mdpSpec);
                                                $mdlFilterInTargetData[] = $mdpSpec->$pairedMdlFilterIn;
                                                $mdlFilterInTargetDataProduk[$mdpSpec->produk_id][$mdpSpec->extern2_nama][] = $mdpSpec->$pairedMdlFilterIn;
                                            }
                                        }
                                    }
                                }
                            }

                            $arrResultDiff = array_diff($mdlFilterInSrcData, $mdlFilterInTargetData);
                            foreach ($mdlFilterInSrcDataProduk as $pid => $pSpec) {
                                foreach ($pSpec as $psku => $skuSpec) {
                                    $avaliSerial = isset($mdlFilterInTargetDataProduk[$pid][$psku]) ? $mdlFilterInTargetDataProduk[$pid][$psku] : array();
                                    $avaliSerialDiff = array_diff($skuSpec, $avaliSerial);
                                    if (sizeof($avaliSerialDiff) > 0) {
                                        $arrResultDiffProduk[$pid][$psku] = $avaliSerialDiff;
                                    }
                                    $arrResultAllProduk[$pid][$psku] = $skuSpec;
                                }
                            }
                            break;
                        case "packinglist":
//                            cekHitam("[$jenisTr_reference] [$transID_ref]");
                            $arrRefMasterIDs = array();
//                            $arrCekTrIDs = array_keys($itemsFields);
                            $arrCekTrIDs = array();
                            if (isset($masterMainFields["referensi_order"]) && ($masterMainFields["referensi_order"] > 0)) {
                                $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                            }
                            else {
                                foreach ($itemsFields as $sspec) {
                                    if (isset($sspec["refID"]) && ($sspec["refID"] > 0)) {
                                        $arrCekTrIDs[] = $sspec["refID"];
                                    }
                                    else {
                                        $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                                    }
                                }
                            }
                            $this->load->model("MdlTransaksi");
                            $trc = New MdlTransaksi();
                            $trc->addFilter("id in ('" . implode("','", $arrCekTrIDs) . "')");
                            $trcTmp = $trc->lookupAll()->result();
                            showLast_query("biru");
                            if (sizeof($trcTmp) > 0) {
                                foreach ($trcTmp as $specc) {
                                    $arrRefMasterIDs[] = $specc->id_master;
                                }

                                $trc = New MdlTransaksi();
                                $trc->addFilter("id_master in ('" . implode("','", $arrRefMasterIDs) . "')");
                                $trc->addFilter("trash_4='0'");
//                                $trc->addFilter("jenis='5822spd'");
                                $this->db->where_in('jenis', array("5822spd", "5823spd"));
                                $trcTmp = $trc->lookupAll()->result();
                                showLast_query("biru");
//                                arrPrint($arrRefMasterIDs);
//                                matiHere(__LINE__."::");
                                if (in_array("91399", $arrRefMasterIDs)) {
                                    //untuk lolosin transaki lama (mei 2024) yang kena sabotase
                                    if ($tr_id_dibatalkan == "91441") {
                                        $trcTmp = array();
                                    }

                                }

                                if (sizeof($trcTmp) > 0) {
                                    $arrdatas = array();
                                    foreach ($trcTmp as $speccc) {
                                        $arrdatas[] = $speccc->nomer;
                                        $customer_nama = $speccc->customers_nama;
                                    }
                                    $nomerss = implode(",", $arrdatas);
//                                    $msg = "Pembatalan tidak bisa dilanjutkan karena Sales Order sudah dikirim ke konsumen $customer_nama dengan nomer $nomerss. Silahkan diperiksa kembali. code: " . __LINE__;
//                                    mati_disini($msg);
                                    $sw_title = "Transaksi Tidak dapat dibatalkan";
                                    $msg2 = "<div class='text-left'>";
                                    $msg2 .= "Pembatalan tidak bisa dilanjutkan karena Sales Order sudah dikirim ke konsumen $customer_nama, nomer kirim: $nomerss <br><br>";
                                    $msg2 .= "<div class='text-bold text-red'>sehingga transaksi  ($tr_no_dibatalkan) tidak dapat dibatalkan</div>";

                                    //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                                    //endregion untuk dinamis pake tempalte ini setiap row

                                    $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                                    matiWhiteboard($sw_title, $msg2);
                                }
                            }

                            break;
                        case "salesorder":
                            $arrRefMasterIDs = array();
//                            $arrCekTrIDs = array_keys($itemsFields);
                            $arrCekTrIDs = array();
                            if (isset($masterMainFields["referensi_order"]) && ($masterMainFields["referensi_order"] > 0)) {
                                $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                            }
                            else {
                                foreach ($itemsFields as $sspec) {
                                    if (isset($sspec["refID"]) && ($sspec["refID"] > 0)) {
                                        $arrCekTrIDs[] = $sspec["refID"];
                                    }
                                    else {
                                        $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                                    }
                                }
                            }
                            $this->load->model("MdlTransaksi");
                            $trc = New MdlTransaksi();
                            $trc->addFilter("id in ('" . implode("','", $arrCekTrIDs) . "')");
                            $trc->addFilter("trash_4='1'");
                            $trcTmp = $trc->lookupAll()->result();
                            if (sizeof($trcTmp) > 0) {
                                $arrdataslunas = array();
                                foreach ($trcTmp as $speccc) {
//                                    arrPrint($speccc);
                                    $arrdataslunas[$speccc->id] = $speccc->nomer;
                                    $customer_nama = $speccc->customers_nama;
                                    $cancel_nama = $speccc->cancel_name;
                                    $trid = $speccc->id;
                                    //------
                                    $jenis = "4464";// penerimaan penjualan tunai
                                    $trcc = New MdlTransaksi();
                                    $trcc->setFilters(array());
                                    $trcc->addFilter("transaksi_id='$trid'");
//                                    $trcc->addFilter("target_jenis='4464'");
                                    $trccTmp = $trcc->lookupPaymentSrcByJenis($jenis)->result();
                                    showLast_query("biru");
                                    cekHere(count($trccTmp));
                                    $total_terbayar_lunas = 0;
                                    if (sizeof($trccTmp) > 0) {
                                        foreach ($trccTmp as $trccSpec) {
//                                            arrPrintWebs($trccSpec);
                                            $total_terbayar_lunas += $trccSpec->terbayar;// jumlah yang sudah dibayar, masing-masing nota
                                        }
                                    }
                                }
//                                $nomerss = implode(",", $arrdataslunas);
//                                $msg = "Pembatalan tidak bisa dilanjutkan karena Sales Order nomer $nomerss konsumen $customer_nama sudah di REJECT/CANCEL oleh $cancel_nama. Silahkan diperiksa kembali. code: " . __LINE__;
//                                mati_disini($msg);

                            }
                            break;
                        case "hadiah":
                            $this->load->model("MdlTransaksi");
                            $trc = New MdlTransaksi();
                            $trc->addFilter("id_master=" . $masterMainFields["transaksiReference"]);
                            $trc->addFilter("jenis='66771'");
                            $trc->addFilter("trash_4='0'");
                            $trcTmp = $trc->lookupAll()->result();
                            showLast_query("biru");
                            if(sizeof($trcTmp)>0){
                                $customer_nama = $trcTmp[0]->customers_nama;
                                $nomerss = $trcTmp[0]->nomer;
                                $sw_title = "Transaksi Tidak dapat dibatalkan";
                                $msg2 = "<div class='text-left'>";
                                $msg2 .= "Pembatalan tidak bisa dilanjutkan karena Barang Hadiah sudah diserahkan ke penerima $customer_nama, nomer kirim: $nomerss <br><br>";
                                $msg2 .= "<div class='text-bold text-red'>sehingga transaksi  ($tr_no_dibatalkan) tidak dapat dibatalkan</div>";
                                //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                                //endregion untuk dinamis pake tempalte ini setiap row
                                $msg2 .= "</div>";
                                cekHere($msg2);
                                matiWhiteboard($sw_title, $msg2);
                            }

                            break;
                    }
                }
            }

//            mati_disini(__LINE__);

            switch ($jenis_master) {
                case "7499":
                case "5822":
                case "5823":
                case "5822spd":
                case "5823spd":
                    $jenis_master_return = array(
                        array("next_step_code" => "9822r"),
                        array("next_step_code" => "9822g"),
                        array("next_step_code" => "9822"),
                    );
                    // region cek input faktur PPN KELUARAN
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("reference_id='$transID_ref'");
                    $t->addFilter("link_id='0'");
                    $t->addFilter("trash_4='0'");
                    $t->addFilter("jenis in ('110e', '110')");
//                $t->addFilter("next_substep_code<>''");
//                $t->addFilter("sub_step_number>0");
                    $t->addFilter("transaksi_data.valid_qty>'0'");
//                    $tTmp = $t->lookUpAll()->result();
                    $tTmp = $t->lookupJoined_OLD()->result();
                    showLast_query("kuning");
                    cekKuning(count($tTmp));
                    if (sizeof($tTmp) > 0) {
                        $next_step = $tTmp[0]->next_step_num;
//                        if ($next_step > 2) {
                        $pic_nama_implode = callNextPICName($jenis_master_return);
//                            arrPrintCyan($arrNextPIC);
//                            $pic_nama = array();
//                            if(sizeof($arrNextPIC)>0){
//                                foreach ($arrNextPIC as $nSpec){
//                                    foreach ($nSpec as $nnSpec){
//                                        foreach($nnSpec as $nnnSpec){
//                                            $pic_nama[$nnnSpec["nama"]] = $nnnSpec["nama"];
//                                        }
//                                    }
//                                }
//                            }
//                            $pic_nama_implode = (sizeof($pic_nama)>0) ? "PIC retur penjualan: " . implode(", ", $pic_nama) : "";
                        $nomer_alias = $tTmp[0]->nomer2;
//                            $msg = "Transaksi Penjualan Nomer <strong>$referenceNumberSO</strong>  sudah dikirim ke konsumen, nomer kirim: 5822spd.1.2601.2 dan sudah dibuat faktur ppn keluaran dengan otorisasi . ";
//                            $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong> di DC/Pusat) <br>";
//                            die(lgShowAlertMerah($msg));
//
//                            mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        if ($jenis_master != "7499") {
                            $msg2 .= "Transaksi Penjualan Nomer <strong>$referenceNumberSO</strong> sudah dikirim ke konsumen, nomer kirim: $tr_no_dibatalkan dan sudah dibuat faktur ppn keluaran dengan otorisasi <strong>$nomer_alias</strong> <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>jika faktur sudah dientry, maka hanya dapat melakukan retur penjualan (lihat tutorial return penjualan)</div>";
                            $msg2 .= "<br><strong>PIC retur penjualan: $pic_nama_implode</strong>";
                        }
                        else {
                            $tr_no_dibatalkan_f = formatField_he_format("nomer_nolink", $tr_no_dibatalkan);
                            $nomer_alias_f = formatField_he_format("nomer_nolink", $nomer_alias);
                            $jenis_master_return = array(
                                array("next_step_code" => "9911"),
                            );
                            $pic_nama_implode = callNextPICName($jenis_master_return);
                            $msg2 .= "Transaksi Penerbitan Termin Nomer <strong>$referenceNumberSO</strong>, sudah dibuat faktur ppn keluaran dengan otorisasi <strong>$nomer_alias</strong> <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>Jika perlu pembatalan ($tr_no_dibatalkan_f) maka yang harus dilakukan:</div>";
                            $msg2 .= "<ol>";
                            $msg2 .= "<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias_f</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
                            $msg2 .= "<br><strong>PIC pembatalan di DC/Pusat: $pic_nama_implode</strong>";
                            $msg2 .= "</ol>";
                        }

                        cekMerah($msg2);

                        //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .= "<ol>";
//                            $msg2 .= "<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .= "<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .= "<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
//                        }
                    }
                    // endregion cek input faktur PPN KELUARAN

                    // region cek detail return penjualan items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
//                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
                            $nomer_so_dibatalkan = $masterMainFields["referenceNumberSO"];

//                            $msg = "Penjualan ini $tr_no_dibatalkan telah diretur, sehingga transaksi apapun atas SO Nomer $nomer_so_dibatalkan tidak bisa dilakukan.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Transaksi Penjualan Nomer <strong>$referenceNumberSO</strong> sudah dikirim ke konsumen, nomer kirim: $tr_no_dibatalkan dan sudah dilakukan return  <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>sehingga transaksi  ($tr_no_dibatalkan) tidak dapat dibatalkan</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            matiHere(__LINE__);
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }
                    // endregion cek detail return penjualan items

                    // region cek 4464, jika SO tunai
                    if ($tr_pembayaran == "cash") {
                        $trr = New MdlTransaksi();
                        $trr->addFilter("jenis='4464'");
                        $trr->addFilter("trash_4='0'");
                        $trr->addFilterJoin("produk_id=" . $arrIdsHis["referenceID_dibatalkan__2"]);
                        $trrTmp = $trr->lookupJoined();
//                        showLast_query("biru");
//                        arrPrintWebs($trrTmp);
                        $arrReferencePenjualanTunai = array();
                        if (sizeof($trrTmp) > 0) {
                            $arrReferencePenjualanTunai["referenceID_penjualan_tunai"] = $trrTmp[0]->transaksi_id;
                            $arrReferencePenjualanTunai["referenceNomer_penjualan_tunai"] = $trrTmp[0]->nomer;
                            $arrReferencePenjualanTunai["referenceNumber_penjualan_tunai"] = $trrTmp[0]->nomer;
                        }
                    }
                    // endregion cek 4464, jika SO tunai


                    break;
                case "466":
                    $lockerAvail = array();
                    $arrPIDs_items = array_keys($itemsFields);
                    $this->load->model("Mdls/MdlLockerStock");
                    $ls = New MdlLockerStock();
                    $ls->addFilter("jenis='produk'");
                    $ls->addFilter("state='active'");
                    $ls->addFilter("cabang_id=" . $masterMainFields["placeID"]);
                    $ls->addFilter("gudang_id=" . $masterMainFields["gudangID"]);
                    $ls->addFilter("produk_id in ('" . implode("','", $arrPIDs_items) . "')");
                    $lsTmp = $ls->lookupAll()->result();
                    showLast_query("biru");
                    if (sizeof($lsTmp) > 0) {
                        foreach ($lsTmp as $lsSpec) {
                            $lockerAvail[$lsSpec->produk_id] = array(
                                "last_stock_avail" => $lsSpec->jumlah,
                            );
                        }
                        foreach ($lockerAvail as $ls_id => $ls_spec) {
                            if (isset($itemsFields[$ls_id])) {
                                foreach ($ls_spec as $ls_key => $ls_val) {
                                    $itemsFields[$ls_id][$ls_key] = $ls_val;
                                }
                            }
                        }
                    }
                    $_SESSION[$cCode]['main']['showLastStock'] = true;

                    // region cek detail return pembelian items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
//                            $msg = "Transaksi gagal dibatalkan karena sudah dilakukan return pembelian fg reguler.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Karena sudah dilakukan return pembelian fg reguler. <br><br>";
//                            $msg2 .= "<div class='text-bold text-red'>berhubung akan direject ($tr_no_dibatalkan) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);

                        }
                    }
                    // endregion cek detail return pembelian items
                    break;
                case "1466":
                    $lockerAvail = array();
                    $arrPIDs_items = array_keys($itemsFields);
                    $this->load->model("Mdls/MdlLockerStock");
                    $ls = New MdlLockerStock();
                    $ls->addFilter("jenis='produk'");
                    $ls->addFilter("state='active'");
                    $ls->addFilter("cabang_id=" . $masterMainFields["placeID"]);
                    $ls->addFilter("gudang_id=" . $masterMainFields["gudangProjectID"]);
                    $ls->addFilter("produk_id in ('" . implode("','", $arrPIDs_items) . "')");
                    $lsTmp = $ls->lookupAll()->result();
                    showLast_query("biru");
                    if (sizeof($lsTmp) > 0) {
                        foreach ($lsTmp as $lsSpec) {
                            $lockerAvail[$lsSpec->produk_id] = array(
                                "last_stock_avail" => $lsSpec->jumlah,
                            );
                        }
                        foreach ($lockerAvail as $ls_id => $ls_spec) {
                            if (isset($itemsFields[$ls_id])) {
                                foreach ($ls_spec as $ls_key => $ls_val) {
                                    $itemsFields[$ls_id][$ls_key] = $ls_val;
                                }
                            }
                        }
                    }
                    $_SESSION[$cCode]['main']['showLastStock'] = true;

                    // region cek detail return pembelian items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
//                            $msg = "Transaksi gagal dibatalkan karena sudah dilakukan return pembelian fg project.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Karena sudah dilakukan return pembelian fg project. <br><br>";
//                            $msg2 .= "<div class='text-bold text-red'>berhubung akan direject ($tr_no_dibatalkan) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }
                    // endregion cek detail return pembelian items
                    break;
                case "461":
                    // region cek detail return pembelian items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
//                            $msg = "Transaksi gagal dibatalkan karena sudah dilakukan return pembelian supplies.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Karena sudah dilakukan return pembelian pembelian supplies. <br><br>";
//                            $msg2 .= "<div class='text-bold text-red'>berhubung akan direject ($tr_no_dibatalkan) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }
                    // endregion cek detail return pembelian items
                    break;
                case "3463":
                    // deteksi tgl SRN Service project, bila sebelum 30 nov 2024 maka ppn dan hutang dagang dikurangi sebesar ppn.
                    // 30 nov 2024 dan selanjutnya maka apa adanya.
//                    arrPrintCyan($masterMainFields);
                    if ($tr_fulldate_dibatalkan < "2024-11-30") {
                        $masterMainFields["nilai_tambah_piutang_pembelian"] = $masterMainFields["harga_disc"];
                        $masterMainFields["nilai_tambah_ppn_in"] = 0;

                        $tmpMasterInValues["nilai_tambah_piutang_pembelian"] = $tmpMasterInValues["harga_disc"];
                        $tmpMasterInValues["nilai_tambah_ppn_in"] = 0;
                    }
                    break;
                case "489":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
//                        $msg = "Transaksi Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
//                        mati_disini($msg);

                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Transaksi Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput.  <br><br>";
                        $msg2 .= "Jika akan tetap melakukan pembatalan, maka lakukan langkah berikut:";
                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "487":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
                        $msg = "Transaksi Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Jika akan tetap melakukan pembatalan, maka lakukan langkah berikut:";
                        $msg .= "Jika akan tetap dibatalkan, maka dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
                        mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Karena Nomer Faktur PPN Masukan sudah diinput <br><br>";
                        $msg2 .= "Jika akan tetap melakukan pembatalan, maka lakukan langkah berikut:";
//                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";

                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "462":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
//                        $msg = "Transaksi Pembayaran Hutang Biaya Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
//                        mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Karena Nomer Faktur PPN Masukan sudah diinput <br><br>";
                        $msg2 .= "Jika akan tetap melakukan pembatalan, maka lakukan langkah berikut:";
//                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";

                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "483":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
//                        $msg = "Transaksi Pembayaran Hutang Biaya Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
//                        mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Karena Nomer Faktur PPN Masukan sudah diinput <br><br>";
                        $msg2 .= "Jika akan tetap melakukan pembatalan, maka lakukan langkah berikut:";
//                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> <br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Biaya Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";

                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "4464":

                    break;
            }

//            mati_disini(__LINE__);

            // region START OF COMPLETE REPEATED LOGIC: Pengecekan Locker Transaksi & Race Condition
            $this->db->trans_start();

            // Menggunakan Query Binding sesuai aturan keamanan proyek
            $lockSql = "SELECT id, oleh_id, oleh_nama, last_access 
                    FROM stock_locker_transaksi 
                    WHERE transaksi_id = ? 
                      AND state = 'hold' 
                      AND jumlah = '1' 
                    FOR UPDATE";
            $lockQuery = $this->db->query($lockSql, array($id));
            $activeLocks = $lockQuery->result();

            if (sizeof($activeLocks) > 0) {
                $lock = $activeLocks[0];
                if ($lock->oleh_id != $this->session->login['id']) {
                    $this->db->trans_rollback(); // Batalkan/lepas lock database agar antrean jalan

                    // Deteksi jika petugas pertama sudah idle > 5 menit (300 detik)
                    $isIdle = false;
                    $timeDiff = time() - strtotime($lock->dtime);
                    $idleMsg = "";
                    if ($timeDiff >= 300) {
                        $isIdle = true;
                        $idleMsg = "<br><br>Rekan Anda tampaknya sudah tidak aktif selama " . floor($timeDiff / 60) . " menit. Anda dapat mengambil alih dokumen ini.";
                    }
                    $idleMsg = "";

                    $msg = "Nota ini sedang diproses oleh <b>" . htmlspecialchars($lock->oleh_nama) . "</b>.<br>" .
                        "Mohon tunggu beberapa saat atau hubungi rekan Anda untuk bergantian memproses nota ini agar data tidak tumpang tindih." . $idleMsg;

                    $alerts = array(
                        "type" => "warning",
                        "title" => "Dokumen Sedang Digunakan",
                        "html" => $msg,
                    );

                    echo swalAlert($alerts);
                    die($msg);
                }
            }
            else {

                // Tulis lock hold baru untuk user aktif
                $this->load->model("Mdls/MdlLockerTransaksi");
                $lt = new MdlLockerTransaksi();
                $newHold = array(
                    "state" => "hold",
                    "produk_id" => "$id",
                    "transaksi_id" => "$id",
                    "cabang_id" => $this->session->login['cabang_id'],
                    "oleh_id" => $this->session->login['id'],
                    "oleh_nama" => $this->session->login['nama'],
                    "jenis" => "transaksi",
                    "jenis_locker" => "transaksi",
                    "jumlah" => "1",
                    "gudang_id" => "0",
                    "last_access" => dtimeNow(),
                );
                $lt->addData($newHold);
            }


            $this->db->trans_complete();
            // endregion END OF COMPLETE REPEATED LOGIC


            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;
            cekungu("sampai pembatalan checker: $val");

            if (sizeof($arrIdsHis) > 0) {
                foreach ($arrIdsHis as $key_his => $val_his) {
                    $_SESSION[$cCode]['main'][$key_his] = $val_his;
                }
            }
//            arrPrintCyan($arrReferencePenjualanTunai);
            if (sizeof($arrReferencePenjualanTunai) > 0) {
                foreach ($arrReferencePenjualanTunai as $key_his => $val_his) {
                    $_SESSION[$cCode]['main'][$key_his] = $val_his;
                }
            }

            $_SESSION[$cCode]['main']['seluruhnya'] = true;
            $_SESSION[$cCode]['main']['referenceID'] = $id;
            $_SESSION[$cCode]['main']['referenceNomer'] = $masterMainFields['nomer'];
//            $_SESSION[$cCode]['main']['referenceID'] = $jenis_master =="466" ? $masterMainFields["referenceID"]:$id;
//            $_SESSION[$cCode]['main']['referenceNomer'] = $jenis_master =="466" ? $masterMainFields["referenceNumber"]:$masterMainFields['nomer'];
            $_SESSION[$cCode]['main']['referenceNomer_top'] = $tmpB[0]->nomer_top;
            $_SESSION[$cCode]['main']['jenisTr_reference'] = $tmpB[0]->jenis;
            $_SESSION[$cCode]['main']['referenceID_master'] = $tr_id_master_dibatalkan;

            $_SESSION[$cCode]['main']['referenceStepNumber'] = $tmpB[0]->step_number;
            $_SESSION[$cCode]['main']['referenceID_top'] = $tmpB[0]->id_top;
            $_SESSION[$cCode]['main']['reference_dtime'] = $tmpB[0]->dtime;
            $_SESSION[$cCode]['main']['reference_fulldate'] = $tmpB[0]->fulldate;
            $_SESSION[$cCode]['main']['reference_pembayaran'] = $tr_pembayaran;


            if (isset($arrResultDiff) && (sizeof($arrResultDiff) > 0)) {
                $_SESSION[$cCode]['extracted_serial_diff']['all_serial'] = $mdlFilterInSrcData;
                $_SESSION[$cCode]['extracted_serial_diff']['avail_serial'] = $mdlFilterInTargetData;
                $_SESSION[$cCode]['extracted_serial_diff']['diff_serial'] = $arrResultDiff;
                $_SESSION[$cCode]['extracted_serial_diff']['diff_serial_produk'] = $arrResultDiffProduk;

                $_SESSION[$cCode]['extracted_serial_diff']['all_serial_produk'] = $arrResultAllProduk;
            }
            else {
                $_SESSION[$cCode]['extracted_serial_diff']['all_serial'] = $mdlFilterInSrcData;
                $_SESSION[$cCode]['extracted_serial_diff']['avail_serial'] = $mdlFilterInTargetData;
                $_SESSION[$cCode]['extracted_serial_diff']['diff_serial'] = $arrResultDiff;
                $_SESSION[$cCode]['extracted_serial_diff']['diff_serial_produk'] = $arrResultDiffProduk;

                $_SESSION[$cCode]['extracted_serial_diff']['all_serial_produk'] = $arrResultAllProduk;
            }

            foreach ($itemsFields as $row) {
//                $id = $row->produk_id;
//                $name = $row->produk_nama;
//                $tmpJml = $row->produk_ord_jml;
//                $tmpJmlReturn = $row->produk_ord_jml_return;
                $rows = (object)$row;
                $id = $rows->id;
                $name = $rows->nama;
                $tmpJml = $rows->qty;
                $tmpJmlReturn = isset($rows->produk_ord_jml_return) ? $rows->produk_ord_jml_return : 0;

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
                            mati_disini("tidak ditemukan item " . $rows->nama . " di locker stock.");
                        }

                    }

                    $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                    if (!isset($_SESSION[$cCode]['items']) || (!array_key_exists($id, $_SESSION[$cCode]['items']))) {
//                        cekmerah("belum ada di items, mau menambahkan");
                        $tmp = array(
                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
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
//                            cekMerah($this->db->last_query());
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
//                            arrPrint($arrDiff);
//                            matiHere();
                            if (array_key_exists("sisa", $arrDiff)) {

                            }
                            else {
                                foreach ($fieldSrcs as $keySrc => $srcFields) {
                                    $arrDiff[$keySrc] = $arrDiff["harga"];
                                }
//                                $arrDiff["sisa"] = $arrDiff["harga"];
//                                $arrDiff["tagihan"] = $arrDiff["harga"];
                            }
                            foreach ($arrDiff as $key => $val) {
//                                cekKuning($key . " diisi dengan " . $val);
                                $tmp[$key] = $val;


                            }

                        }
//                        matiHere();
                        //endregion

                        if (sizeof($itemsInjectorConfig) && $itemsInjectorConfig['enabled'] == true) {
                            foreach ($itemsInjectorConfig['kolom'] as $target => $source) {
                                $tmp[$target] = isset($itemsFields[$id][$source]) ? $itemsFields[$id][$source] : "";
                            }
                        }


                        foreach ($fieldSrcs as $key2 => $src2) {
//                            $rows_val = isset($rows->$src2) ? $rows->$src2 : "";
                            $tmp[$key2] = makeValue($src2, $tmp, $tmp, $rows->$src2);
//                            cekmerah("$key2 diisi dengan " . $tmp[$key2]);
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
                            $_SESSION[$cCode]['main'][$key] = $rows->$label;
                        }
                    }
                }
            }

            if (sizeof($itemsFields) > 0) {
                foreach ($itemsFields as $key => $val) {
                    $setVal = $_SESSION[$cCode]["items"][$key];
                    foreach ($val as $keys => $val0) {
                        if (!isset($setVal[$keys])) {
                            $_SESSION[$cCode]["items"][$key][$keys] = $val0;
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
                    if (!isset($_SESSION[$cCode]['main'][$key]) || ($_SESSION[$cCode]['main'][$key] == NULL)) {
                        $_SESSION[$cCode]['main'][$key] = $value;
                    }
                }
            }
            if (sizeof($masterMainFields) > 0) {
                foreach ($masterMainFields as $key => $value) {
                    if (!isset($_SESSION[$cCode]['main'][$key])) {
                        switch ($tmpB[0]->jenis) {
                            case "999":
                                if (in_array($key, $arrAdjustmentRekening)) {
                                    cekUngu("::: $key => $value :::");
                                    if (is_numeric($value)) {
                                        $_SESSION[$cCode]['main'][$key] = $value;
                                    }
                                    else {
                                        $new_value = isset($arrRekening_coa[$value]) ? $arrRekening_coa[$value] : NULL;
                                        $_SESSION[$cCode]['main'][$key] = $new_value;
                                    }
                                }
                                else {
                                    $_SESSION[$cCode]['main'][$key] = $value;
                                }
                                break;
                            default:
                                $_SESSION[$cCode]['main'][$key] = $value;
                                // ada gerbang baru kas netto di penerimaan penjualan tunai. dipakai saat auto setor kas ke pusat.
                                // auto setor tidak masuk ke registry jurnal, jadi pakai config aktual.
                                // bila tidak ada kas_netto maka diberi nilai_entry (untuk transaksi lama).
                                if (!isset($_SESSION[$cCode]['main']['kas_netto'])) {
                                    $_SESSION[$cCode]['main']['kas_netto'] = $_SESSION[$cCode]['main']['nilai_entry'];
                                }
                                break;
                        }
//                        $_SESSION[$cCode]['main'][$key] = $value;
                    }

                }
            }


            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);

                }
            }
            if (sizeof($items2Fields) > 0) {
                foreach ($items2Fields as $key => $value) {
                    $_SESSION[$cCode]['items2'][$key] = $value;
                }
            }
            if (sizeof($items2_sum) > 0) {
                foreach ($items2_sum as $key => $value) {
                    $_SESSION[$cCode]['items2_sum'][$key] = $value;
                }
            }
            if (sizeof($items3) > 0) {
                foreach ($items3 as $key => $value) {
                    $_SESSION[$cCode]['items3'][$key] = $value;
                }
            }
            if (sizeof($items3_sum) > 0) {
                foreach ($items3_sum as $key => $value) {
                    $_SESSION[$cCode]['items3_sum'][$key] = $value;
                }
            }
            if (sizeof($items4) > 0) {
                foreach ($items4 as $key => $value) {
                    $_SESSION[$cCode]['items4'][$key] = $value;
                }
            }
            if (sizeof($items4_sum) > 0) {
                foreach ($items4_sum as $key => $value) {
                    $_SESSION[$cCode]['items4_sum'][$key] = $value;
                }
            }
            if (sizeof($items5) > 0) {
                foreach ($items5 as $key => $value) {
                    $_SESSION[$cCode]['items5'][$key] = $value;
                }
            }
            if (sizeof($items5_sum) > 0) {
                foreach ($items5_sum as $key => $value) {
                    $_SESSION[$cCode]['items5_sum'][$key] = $value;
                }
            }
            if (sizeof($items6) > 0) {
                foreach ($items6 as $key => $value) {
                    $_SESSION[$cCode]['items6'][$key] = $value;
                }
            }
            if (sizeof($items6_sum) > 0) {
                foreach ($items6_sum as $key => $value) {
                    $_SESSION[$cCode]['items6_sum'][$key] = $value;
                }
            }
            if (sizeof($items7) > 0) {
                foreach ($items7 as $key => $value) {
                    $_SESSION[$cCode]['items7'][$key] = $value;
                }
            }
            if (sizeof($items7_sum) > 0) {
                foreach ($items7_sum as $key => $value) {
                    $_SESSION[$cCode]['items7_sum'][$key] = $value;
                }
            }
            if (sizeof($items8) > 0) {
                foreach ($items8 as $key => $value) {
                    $_SESSION[$cCode]['items8'][$key] = $value;
                }
            }
            if (sizeof($items8_sum) > 0) {
                foreach ($items8_sum as $key => $value) {
                    $_SESSION[$cCode]['items8_sum'][$key] = $value;
                }
            }
            if (sizeof($items9_sum) > 0) {
                foreach ($items9_sum as $key => $value) {
                    $_SESSION[$cCode]['items9_sum'][$key] = $value;
                }
            }
            if (sizeof($items10_sum) > 0) {
                foreach ($items10_sum as $key => $value) {
                    $_SESSION[$cCode]['items10_sum'][$key] = $value;
                }
            }
            //---- registry resultItems dan resultItems2 dialihkan ke resultItems_revert dan resultItems2_revert
            if (sizeof($rsltItems) > 0) {
                if (isset($_SESSION[$cCode]['rsltItems_revert'])) {
                    $_SESSION[$cCode]['rsltItems_revert'] = NULL;
                    unset($_SESSION[$cCode]['rsltItems_revert']);
                }
                foreach ($rsltItems as $key => $value) {
                    if (!isset($_SESSION[$cCode]['rsltItems_revert'][$key])) {
                        $_SESSION[$cCode]['rsltItems_revert'][$key] = $value;
                    }

                    //if (!isset($_SESSION[$cCode]['rsltItems_revert'][$value['id']])) {
                    //    $_SESSION[$cCode]['rsltItems_revert'][$value['id']] = $value;
                    //    $_SESSION[$cCode]['rsltItems_revert'][$value['id']]['jml'] = 0;
                    //    $_SESSION[$cCode]['rsltItems_revert'][$value['id']]['qty'] = 0;
                    //}
                    //$_SESSION[$cCode]['rsltItems_revert'][$value['id']]['jml'] += $value['jml'];
                    //$_SESSION[$cCode]['rsltItems_revert'][$value['id']]['qty'] += $value['qty'];
                }
            }
            else {
                $_SESSION[$cCode]['rsltItems_revert'] = array();
            }
            if (sizeof($rsltItems2) > 0) {
                if (isset($_SESSION[$cCode]['rsltItems2_revert'])) {
                    $_SESSION[$cCode]['rsltItems2_revert'] = NULL;
                    unset($_SESSION[$cCode]['rsltItems2_revert']);
                }
                foreach ($rsltItems2 as $key => $value) {
                    $_SESSION[$cCode]['rsltItems2_revert'][$key] = $value;

                    //if (!isset($_SESSION[$cCode]['rsltItems2_revert'][$value['id']])) {
                    //  $_SESSION[$cCode]['rsltItems2_revert'][$value['id']] = $value;
//                        $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['jml'] = 0;
//                        $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['qty'] = 0;
//                    }
                    //                  $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['jml'] += $value['jml'];
//                    $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['qty'] += $value['qty'];
                }
            }
            else {
                $_SESSION[$cCode]['rsltItems2_revert'] = array();
            }
            if (sizeof($main_elements) > 0) {
                foreach ($main_elements as $key => $value) {
                    $_SESSION[$cCode]['main_elements'][$key] = $value;
                }
            }

            //-------------
            $componentAuto = loadConfigModulJenis_he_misc($jenisMasterRef, "coTransaksiCore")["componentsAuto"][$jenisMasterRef];
            $postProcessorAuto = loadConfigModulJenis_he_misc($jenisMasterRef, "coTransaksiCore")["postProcessorAuto"][$jenisMasterRef];
            $preProcessorAuto = loadConfigModulJenis_he_misc($jenisMasterRef, "coTransaksiCore")["preProcessorAuto"][$jenisMasterRef];
            //-------------


            $koreksi_serial = isset($this->configUi[$this->jenisTr]['koreksi_serial'][$jenisTr_reference]) ? $this->configUi[$this->jenisTr]['koreksi_serial'][$jenisTr_reference] : array();
            if (sizeof($koreksi_serial) > 0) {
//                if (isset($_SESSION[$cCode]["items7_sum"]) && (sizeof($_SESSION[$cCode]["items7_sum"]) > 0)) {
//                    // disini adalah GRN baru, generate serial saat PRE-GRN dan rekening serial saat GRN
//                }
//                else {
//                }
                if (isset($koreksi_serial["enabled"]) && ($koreksi_serial["enabled"] == true)) {
                    $list_ip_addr = $koreksi_serial["ipaddr"];
//                    if (in_array(ipadd(), $list_ip_addr)) {
                    if ($this->session->login['ghost'] == 1) {
                        // rebuild items2
                        $_SESSION[$cCode]["items2"] = array();
                        $_SESSION[$cCode]["items3_sum"] = array();
                        foreach ($mdlResultAllProduk as $pid => $pSpec) {
                            foreach ($pSpec as $sku => $skuSpec) {
                                foreach ($skuSpec as $sSpec) {
                                    $detail2 = array(
                                        "serial" => $sSpec->produk_serial_number_2,
                                        "sku" => $sSpec->produk_sku_part_nama,
                                        "sku_serial" => $sSpec->produk_sku_serial,
                                        "qty" => 1,
                                        "produk_sku_part_id" => $sSpec->produk_sku_part_id,
                                        "produk_sku_part_nama" => $sSpec->produk_sku_part_nama,
                                    );
                                    $_SESSION[$cCode]["items2"][$pid][$sku][$sSpec->produk_serial_number_2] = $detail2;

                                    $detail3_sum = array(
                                        "id" => $sSpec->produk_id,
                                        "nama" => $sSpec->produk_nama,
                                        "name" => $sSpec->produk_nama,
                                        "kategori_id" => $_SESSION[$cCode]["items"][$pid]["kategori_id"],
                                        "kategori_nama" => $_SESSION[$cCode]["items"][$pid]["kategori_nama"],
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => $_SESSION[$cCode]["items"][$pid]["barcode"],
                                        "kode" => $_SESSION[$cCode]["items"][$pid]["kode"],
                                        "produk_kode" => $_SESSION[$cCode]["items"][$pid]["produk_kode"],
                                        "no_part" => $_SESSION[$cCode]["items"][$pid]["no_part"],
                                        "label" => $_SESSION[$cCode]["items"][$pid]["label"],
                                        "serial_number" => $sSpec->produk_serial_number_2,
                                        "produk_serial" => $sSpec->produk_serial_number_2,
                                        "produk_sku" => $sSpec->produk_sku_part_nama,
                                        "produk_sku_serial" => $sSpec->produk_sku_serial,
                                        "produk_sku_part_id" => $sSpec->produk_sku_part_id,
                                        "produk_sku_part_nama" => $sSpec->produk_sku_part_nama,
                                        "produk_sku_part_serial" => "",
                                    );
                                    $_SESSION[$cCode]["items3_sum"][] = $detail3_sum;
                                }
                            }
                        }

                    }
                }
            }

            if (isset($_SESSION[$cCode]['main']['pihakExternRevertRequest']) && ($_SESSION[$cCode]['main']['pihakExternRevertRequest'] == true)) {
                $postProcessorRequestRevert = isset($this->configCore[$this->jenisTr]['postProcessorRequestRevert'][$jenisTr_reference]) ? $this->configCore[$this->jenisTr]['postProcessorRequestRevert'][$jenisTr_reference] : array();
            }

        }
        else {
//            cekMerah("tidak ada itemnya!");
            mati_disini("Transaksi yang akan dibatalkan tidak tersedia/tidak aktif/sudah dibatalkan.");
        }


        //region fetch jurnalconfig
        $postProccExternID = isset($_SESSION[$cCode]['main']['pihakExternID']) ? $_SESSION[$cCode]['main']['pihakExternID'] : "";
        $revertedJurnal = fetchRevertJurnal($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $component, $_SESSION[$cCode]['main']['jenisTr_reference']);
        $revertPostProc = fetchRevertPostProc($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor, $postProccExternID, $_SESSION[$cCode]['main']['jenisTr_reference']);
        $revertPaymentSrc = fetchRevertPaymentSrc($masterMainFields["jenis"], $masterMainFields["step_number"], $masterMainFields["transaksi_id"]);
        $revertPaymentSrcUangMuka = fetchRevertPaymentSrcUangMuka($masterMainFields["jenis"], $masterMainFields["step_number"], $masterMainFields["transaksi_id"]);
        $swapcomFifo = fetchSwapComFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor['detail'], $_SESSION[$cCode]['main']['jenisTr_reference']); // ini untuk dimasukkan ke preprocc
        $swapPreFifo = fetchSwapPreFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['detail'], $_SESSION[$cCode]['main']['jenisTr_reference']); // ini untuk masuk ke postprocc/component fifo
        $swapPreSubFifo = fetchSwapPreSubFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['sub_detail'], $_SESSION[$cCode]['main']['jenisTr_reference']); // ini untuk masuk ke postprocc/component fifo
        $swapPreFifoMain = fetchSwapPreFifoMain($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['master']);
        //--------------
        $swapPreProc = fetchSwapPreProc($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['detail'], $_SESSION[$cCode]['main']['jenisTr_reference']);
        //--------------
        $revertedJurnalAuto = fetchRevertJurnalAuto($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $componentAuto, $_SESSION[$cCode]['main']['jenisTr_reference']);
        $revertPostProcAuto = fetchRevertPostProcAuto($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessorAuto, $postProccExternID);


        switch ($jenis_master) {
            case "967":
                $preProcc = array(
//                    "master" => array(
//                        // rekening koran
//                        array(
//                            "comName" => "RekeningKoranPembatalan",
//                            "loop" => array(),
//                            "static" => array(
//                                "cabang_id" => "placeID",
//                                "state" => ".active",
//                                "extern_id" => "cash_account",
//                                "extern_nama" => "cash_account__label",
//                                "nilai" => "nilai_entry",
//                                "method" => "cashMethode", // cash method yang dipilih
//                                "jenis" => ".hutang bank",
//
//                                "jenisTr" => "jenisTr",
//                            ),
//                            "resultParams" => array(
//                                "main" => array(
//                                    "nilai_cash" => "nilai_cash",
//                                    "nilai_koran" => "nilai_koran",
//                                    "nilai_cash_full" => "nilai_cash_full",
//                                    "nilai_koran_full" => "nilai_koran_full",
//                                ),
//                            ),
//                            "srcGateName" => "main",
//                            "srcRawGateName" => "main",
//                        ),
//                    ),
                );
                break;
            default:
                $preProcc = array(
                    "master" => array(
                        // rekening koran
                        array(
                            "comName" => "RekeningKoranPembatalan",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "state" => ".active",
                                "extern_id" => "cash_account",
                                "extern_nama" => "cash_account__label",
                                "nilai" => "nilai_entry",
                                "method" => "cashMethode", // cash method yang dipilih
                                "jenis" => ".hutang bank",

                                "jenisTr" => "jenisTr",
                            ),
                            "resultParams" => array(
                                "main" => array(
                                    "nilai_cash" => "nilai_cash",
                                    "nilai_koran" => "nilai_koran",
                                    "nilai_cash_full" => "nilai_cash_full",
                                    "nilai_koran_full" => "nilai_koran_full",
                                ),
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                    ),
                );
                break;
        }

        $arrRekPPV = array(
            "alfabet" => "hutang lain ppv",
//            "coa" => "2010090010",//hutang lain ppv
            "coa" => "7010150",
        );
        $tgl_coa = "2022-11-14";
        if ($_SESSION[$cCode]['main']['reference_fulldate'] > $tgl_coa) {
            $rekening_hutang_ppv = $arrRekPPV["coa"];
        }
        else {
            $rekening_hutang_ppv = $arrRekPPV["alfabet"];
        }

        $rekExeption2 = "2010090010";

        $jenisTr_reference = in_array($jenisTr_reference, $this->jenisTrException) ? $jenisTr_reference : $_SESSION[$cCode]['main']['jenisTr_reference'];


        if (sizeof($swapcomFifo) > 0) {

            $jenisException = $this->config->item('heTransaksi_revertJenisException') != null ? $this->config->item('heTransaksi_revertJenisException') : array();
//            cekPink2(":: $jenisTr_reference ::");

            if (!in_array($jenisTr_reference, $jenisException)) {
//                arrPrint($revertedJurnal);
//matiHEre(__LINE__);
                if (isset($swapcomFifo['detail']) && sizeof($swapcomFifo['detail']) > 0) {
                    foreach ($revertedJurnal as $main => $mainVal) {
                        $loop = array();
                        foreach ($mainVal as $key => $ValDetails) {
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Jurnal") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }
                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;

                                        case "4466":
                                        case "3344":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }
                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
//                                            arrPrintPink($ValDetails);
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        case "3344":
                                        case "4466":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($swapcomFifo as $gate => $spec) {
                foreach ($spec as $ii => $subSpec) {
//                    arrPrintWebs($subSpec);
//                    matiHere(__LINE__);
                    if (isset($subSpec["comName"]) && $subSpec["comName"] == "FifoAverage") {
                        foreach ($subSpec["resultParams"] as $gateIx => $subSpec_0) {
                            if (!isset($subSpec["resultParams"][$gateIx]["harga"])) {
                                /*
                                 * ini masih ditembak untuk replace harga dari nota yang gerbang jurnalnya harga namun saat dibatalin sumber harga dari hpp
                                 */
                                $subSpec["resultParams"][$gateIx]["harga"] = "hpp";
                            }
                        }
                    }
                    $preProcc[$gate][$ii] = $subSpec;
                }
            }
        }


        if (sizeof($swapPreFifo) > 0) {
            foreach ($swapPreFifo as $gate => $swapPreFifoSpec) {
                foreach ($swapPreFifoSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }
        if (sizeof($swapPreSubFifo) > 0) {
            foreach ($swapPreSubFifo as $gate => $swapPreSubFifoSpec) {
                foreach ($swapPreSubFifoSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }
        //-------------------------------
        if (sizeof($swapPreProc) > 0) {
            foreach ($swapPreProc as $gate => $swapPreProcSpec) {
                foreach ($swapPreProcSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }
        //-------------------------------
        if (sizeof($swapPreFifoMain) > 0) {
            foreach ($swapPreFifoMain as $gate => $swapPreFifoMainSpec) {
                foreach ($swapPreFifoMainSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }

        $preProcNameReplacer = array(
            "FifoProdukJadi" => "FifoProdukJadi_reverse",
        );
        switch ($jenis_master) {
            case "460":
//                $replacerMaster = array(
//                    "persediaan produk riil" => "hpp_riil",
//                    "persediaan produk" => "hpp_nppv",
//                    "hutang lain ppv" => "ppv_riil",
//                );
//                $replacerDetail = array(
//
//                );
//                if (sizeof($revertedJurnal['master']) > 0) {
////                    arrPrintWebs($revertedJurnal['master']);
//                    foreach ($revertedJurnal['master'] as $spec){
//                        if(($spec['comName']=="Jurnal") || ($spec['comName']=="Rekening")){
//                            foreach ($spec['loop'] as $keys => $vals){
//
//                            }
//                        }
//                    }
//                }

                if (isset($preProcc['detail'])) {
                    foreach ($preProcc['detail'] as $ii => $spec) {
                        if (array_key_exists($spec['comName'], $preProcNameReplacer)) {
                            $spec['comName'] = $preProcNameReplacer[$spec['comName']];
                            $spec['static']['transaksi_id_ref'] = ".$transID_ref";
                            $preProcc['detail'][$ii] = $spec;
                        }
                    }
                }
//                arrPrintWebs($preProcc);


                break;
            default:
                break;
        }

        if (sizeof($revertedJurnal) > 0) {
            if (isset($revertedJurnal['master'])) {
                krsort($revertedJurnal['master']);
            }
            if (isset($revertedJurnal['detail'])) {
                krsort($revertedJurnal['detail']);
            }
            //------------------------
            if (isset($revertedJurnal['preProcc']) && (sizeof($revertedJurnal['preProcc']) > 0)) {
                $revPreProcc = $revertedJurnal['preProcc'];
                $revertedJurnal['preProcc'] = NULL;
                unset($revertedJurnal['preProcc']);

                foreach ($revPreProcc as $gate => $gateSpec) {
                    foreach ($gateSpec as $subSpec) {
                        $preProcc[$gate][] = $subSpec;
                    }
                }

                if (isset($revPreProcc['detail']) && (sizeof($revPreProcc['detail']) > 0)) {
                    foreach ($revertedJurnal as $main => $mainVal) {
                        $loop = array();
                        foreach ($mainVal as $key => $ValDetails) {

                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Jurnal") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }

                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        case "3344":
                                        case "4466":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }

                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }

                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
//                                            arrPrintPink($ValDetails);
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        case "3344":
                                        case "4466":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        if (in_array($jenisTr_reference, $this->jenisTrException)) {
            if (sizeof($revertPostProc) > 0) {
                if (isset($revertPostProc['master'])) {
                    krsort($revertPostProc['master']);
                }
                if (isset($revertPostProc['detail'])) {
                    krsort($revertPostProc['detail']);
                }
            }
        }

        if (sizeof($postProcessorRequestRevert) > 0) {
            foreach ($postProcessorRequestRevert as $mdt => $mdtSpec) {
                foreach ($mdtSpec as $iimdt => $iimdtSpec) {
                    $revertPostProc[$mdt][] = $iimdtSpec;
                }
            }
        }


//arrPrint($revertedJurnal['detail']);

        //------------------------
        $revertedJurnal_awal = array();
        $componentsAwalRevert = isset($this->configCore[$this->jenisTr]['componentsAwalGantiRekening'][$jenisTr_reference]) ? $this->configCore[$this->jenisTr]['componentsAwalGantiRekening'][$jenisTr_reference] : array();
        if (sizeof($componentsAwalRevert) > 0) {
            $keyUangMuka = $componentsAwalRevert["key"];
            $tipeUangMuka = makeValue($componentsAwalRevert["tipeuangmuka"], $_SESSION[$cCode]["main"], $_SESSION[$cCode]["main"], 0);
            $batasTanggal = $componentsAwalRevert["batastanggal"];
            cekKuning("[$tipeUangMuka] [$keyUangMuka] [$batasTanggal] [$tr_fulldate_dibatalkan]");
            if (($tipeUangMuka == 1)
                && ($tr_fulldate_dibatalkan < $batasTanggal)) {
                $core = $componentsAwalRevert["core"];
                if (sizeof($core) > 0) {
                    foreach ($core as $masterGate => $mSpec) {
                        foreach ($mSpec as $ctr => $mmSpec) {
                            $revertedJurnal_awal[$masterGate][] = $mmSpec;
                        }
                    }
                }
            }
        }
        else {
            cekMerah("TIDAK ADA COMPONENT AWAL $jenisTr_reference...");
        }
        //------------------------
//arrPrintHitam($revertedJurnal_awal);
//mati_disini(__LINE__);
        $revertedJurnal_new = array();
        foreach ($revertedJurnal as $masterGate => $mSpec) {
            foreach ($mSpec as $ctr => $mmSpec) {
                switch ($masterGate) {
                    case "detail":
                    case "sub_detail":
                        $rek_replacer = array(
                            "8020" => "RekeningPembantuProdukRiil",// 8020 maka comName direplace RekeningPembantuProdukRiil
                        );
                        if (isset($mmSpec['loop'])) {
                            foreach ($mmSpec['loop'] as $rek_loop => $gate_loop) {
                                if (array_key_exists($rek_loop, $rek_replacer)) {
                                    $mmSpec["comName"] = $rek_replacer[$rek_loop];
                                }
                            }
                        }
                        break;
                }
                if (isset($mmSpec['loop'])) {
                    $old_loop = $mmSpec['loop'];
                    unset($mmSpec['loop']);

                    $new_loop = array();
                    foreach ($old_loop as $key_loop => $val_loop) {
                        // mendeteksi rekening yang sudah ganti label, kalau ada ikut label baru
                        if (isset($arrPenggantiRekening[$key_loop])) {
                            $key_loop = $arrPenggantiRekening[$key_loop];
                        }
                        // mendeteksi rekening masih dipakai atau tidak...
                        if (!in_array($key_loop, $arrDropRekening)) {
                            // mendeteksi rekening coa atau bukan, kalau bukan ganti ke coa
                            if (!is_numeric($key_loop)) {
//                                cekMerah("key rek loop: $key_loop, val rek loop: $val_loop");
                                if (substr($key_loop, 0, 1) == "{") {
//                                    $key_loop = trim($key_loop, "{");
//                                    $key_loop = trim($key_loop, "}");
//                                    $key_loop = str_replace($key_loop, $_SESSION[$cCode]['main'][$key_loop], $key_loop);
//
                                    $new_key_loop = $key_loop;
                                }
                                else {

                                    $new_key_loop = isset($arrRekening_coa[$key_loop]) ? $arrRekening_coa[$key_loop] : NULL;
                                    if ($new_key_loop == NULL) {

                                        if ($key_loop == "exec_locker") {
                                            $new_key_loop = "exec_locker";
                                        }
                                        elseif ($key_loop == "exec") {
                                            $new_key_loop = "exec";
                                        }
                                        else {
                                            $msg = "Cart of Account belum lengkap, silahkan dikoordinasikan dengan web development. Rekening $key_loop :: " . $mmSpec["comName"];
                                            mati_disini($msg);
                                            die(lgShowAlert($msg));
                                        }

                                    }
                                }
                                $new_loop[$new_key_loop] = $val_loop;
                            }
                            else {
                                $new_loop[$key_loop] = $val_loop;
                            }
                        }
                    }

                    if (sizeof($new_loop) > 0) {
                        $revertedJurnal_new[$masterGate][$ctr] = $mmSpec;
                        $revertedJurnal_new[$masterGate][$ctr]['loop'] = $new_loop;
//                        $revertedJurnal_new[$masterGate][] = $mmSpec;
//                        $revertedJurnal_new[$masterGate][]['loop'] = $new_loop;
                    }
                }
                else {
                    $revertedJurnal_new[$masterGate][$ctr] = $mmSpec;
//                    $revertedJurnal_new[$masterGate][] = $mmSpec;
                }
            }

        }

        if (isset($revertPostProc["detail"]) && (sizeof($revertPostProc["detail"]) > 0)) {
            krsort($revertPostProc["detail"]);
            arrPrintHitam($revertPostProc["detail"]);
            $revertPostProcDetailSort = $revertPostProc["detail"];
            $revertPostProc["detail"] = $revertPostProcDetailSort;
        }


        $_SESSION[$cCode]["revert"]["jurnal"] = $revertedJurnal_new;
//        $_SESSION[$cCode]["revert"]["jurnal"] = $revertedJurnal;
        $_SESSION[$cCode]["revert"]["postProc"] = $revertPostProc;
        $_SESSION[$cCode]["revert"]["connectedPaymentsource"] = $revertPaymentSrc;
        $_SESSION[$cCode]["revert"]["connectedPaymentsourceUangMuka"] = $revertPaymentSrcUangMuka;
//        $_SESSION[$cCode]["revert"]["preProc"] = $swapcomFifo;
        $_SESSION[$cCode]["revert"]["preProc"] = $preProcc;


        if (sizeof($revertedJurnalAuto) > 0) {
            if (isset($revertedJurnalAuto['master'])) {
                krsort($revertedJurnalAuto['master']);
            }
            if (isset($revertedJurnalAuto['detail'])) {
                krsort($revertedJurnalAuto['detail']);
            }
            $_SESSION[$cCode]["revertAuto"]["jurnalAuto"] = $revertedJurnalAuto;
        }
        if (sizeof($revertPostProcAuto) > 0) {
            if (isset($revertPostProcAuto['master'])) {
                krsort($revertPostProcAuto['master']);
            }
            if (isset($revertPostProcAuto['detail'])) {
                krsort($revertPostProcAuto['detail']);
            }
            $_SESSION[$cCode]["revertAuto"]["postProcAuto"] = $revertPostProcAuto;
        }
        if (sizeof($preProcessorAuto) > 0) {
//            if (isset($revertPostProcAuto['master'])) {
//                krsort($revertPostProcAuto['master']);
//            }
//            if (isset($revertPostProcAuto['detail'])) {
//                krsort($revertPostProcAuto['detail']);
//            }
            $_SESSION[$cCode]["revertAuto"]["preProcAuto"] = $preProcessorAuto;
        }

        if (sizeof($revertedJurnal_awal) > 0) {
            $_SESSION[$cCode]["revertAwal"]["jurnalAwal"] = $revertedJurnal_awal;
        }

        //endregion


        if (in_array($tmpB[0]->jenis, $this->jenisTrException)) {
//            $_SESSION[$cCode]['main']['nilai_cancel'] = ""; // nilai_cancel tidak diganti
//            cekPink2("disini");
        }
        else {
            if (isset($this->whitelistMain) && sizeof($this->whitelistMain) > 0) {
                foreach ($this->whitelistMain as $key => $val) {
                    if (isset($_SESSION[$cCode]['main'][$val])) {
                        $_SESSION[$cCode]['main']['nilai_cancel'] = $_SESSION[$cCode]['main'][$val];
                    }
                }
            }
        }

        $nextProp = array();
        if (isset($_SESSION[$cCode]['main']['pihakExternRevertStep']) && ($_SESSION[$cCode]['main']['pihakExternRevertStep'] == true)) {
            $pihakExternMasterID = isset($_SESSION[$cCode]['main']['pihakExternMasterID']) ? $_SESSION[$cCode]['main']['pihakExternMasterID'] : 0;
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($pihakExternMasterID, "coTransaksiUi");
//            arrPrintHijau($configUiMasterModulJenis);
            $stepsConfig = isset($configUiMasterModulJenis['steps']) ? $configUiMasterModulJenis['steps'] : array();
            $maxStep = sizeof($stepsConfig);
//            cekHere("::: [$pihakExternMasterID] $maxStep :::");
            if ($maxStep > 0) {
                $stepNum = $_SESSION[$cCode]['main']['referenceStepNumber'] - 1;
                $nextStepNum = $_SESSION[$cCode]['main']['referenceStepNumber'];
                $idHist = blobDecode($tmpB[0]->ids_his);
                $trID_hist = $idHist[$stepNum]["trID"];
//                arrPrintKuning($idHist);
                switch ($jenis_master) {
                    case "111":
                        $t = new MdlTransaksi();
                        $t->setfilters(array());
                        $t->addFilter("id_master='$tr_id_master_dibatalkan'");
                        $t->addFilter("jenis='111r'");
                        $tTmp = $t->lookUpAll()->result();
                        $trID_hist = $tTmp[0]->id;
                        break;
                }
                $nextProp = array(
//                    "step_num" => $stepNum,
//                    "num" => $nextStepNum,
//                    "code" => $this->configUi[$pihakExternMasterID]['steps'][$nextStepNum]['target'],
//                    "label" => $this->configUi[$pihakExternMasterID]['steps'][$nextStepNum]['label'],
//                    "groupID" => $this->configUi[$pihakExternMasterID]['steps'][$nextStepNum]['userGroup'],
//                    "trID" => isset($_SESSION[$cCode]['main']['referenceID_top']) ? $_SESSION[$cCode]['main']['referenceID_top'] : 0,
//                    "detailGate" => isset($_SESSION[$cCode]['main']['pihakExternDetailGate']) ? $_SESSION[$cCode]['main']['pihakExternDetailGate'] : "",

                    "step_num" => $stepNum,
                    "num" => $nextStepNum,
                    "code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    "label" => $configUiMasterModulJenis['steps'][$nextStepNum]['label'],
                    "groupID" => $configUiMasterModulJenis['steps'][$nextStepNum]['userGroup'],
//                    "trID" => isset($_SESSION[$cCode]['main']['referenceID_top']) ? $_SESSION[$cCode]['main']['referenceID_top'] : 0,
                    "trID" => $trID_hist,
                    "detailGate" => isset($_SESSION[$cCode]['main']['pihakExternDetailGate']) ? $_SESSION[$cCode]['main']['pihakExternDetailGate'] : "",
                );

            }
        }

        $_SESSION[$cCode]['main']['referenceNextProp'] = $nextProp;


        //----------------------------------
        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        cekungu("sampai revert step 1 langkah: $val");

        if (isset($arrdataslunas) && (sizeof($arrdataslunas) > 0)) {
            //replace gerbang nilai....
//            nilai_bayar_nocn_nolebih_bayar ----- dikurangi terbayar ()
//            deposit_konsumen ----- ditambah terbayar ()
            cekHijau("terbayar total lunas: $total_terbayar_lunas");

            $deposit_konsumen = isset($_SESSION[$cCode]["main"]["deposit_konsumen"]) ? $_SESSION[$cCode]["main"]["deposit_konsumen"] : 0;
            $nilai_bayar_nocn_nolebih_bayar = isset($_SESSION[$cCode]["main"]["nilai_bayar_nocn_nolebih_bayar"]) ? $_SESSION[$cCode]["main"]["nilai_bayar_nocn_nolebih_bayar"] : 0;
            $deposit_konsumen_new = $deposit_konsumen + $total_terbayar_lunas;
            $nilai_bayar_nocn_nolebih_bayar_new = $nilai_bayar_nocn_nolebih_bayar - $total_terbayar_lunas;
            cekKuning("deposit_konsumen_new: $deposit_konsumen_new");
            cekKuning("nilai_bayar_nocn_nolebih_bayar_new: $nilai_bayar_nocn_nolebih_bayar_new");
            $_SESSION[$cCode]["main"]["deposit_konsumen"] = $deposit_konsumen_new;
            $_SESSION[$cCode]["main"]["nilai_bayar_nocn_nolebih_bayar"] = $nilai_bayar_nocn_nolebih_bayar_new;
        }

        $_SESSION[$cCode]["main"]["label_notif"] = NULL;
        switch ($jenis_master) {
            case "466":
                if ($status_grn == 1) {
                    $this->load->model("Mdls/MdlLockerStockDiskonVendor");
                    $diskonIDs = array(1, 2, 3, 4, 5, 8);
                    $mm = New MdlLockerStockDiskonVendor();
                    $mm->addFilter("transaksi_id='$transID_ref'");
                    $mm->addFilter("extern_id in ('" . implode("','", $diskonIDs) . "')");
                    $mmTmp = $mm->lookupAll()->result();
                    showLast_query("biru");
                    $_SESSION[$cCode]["items4_sum"] = array();
                    if (sizeof($mmTmp) > 0) {
                        foreach ($mmTmp as $mmSpec) {
                            $pid = $mmSpec->extern2_id;
                            $pnama = $mmSpec->extern2_nama;
                            $jml = $mmSpec->jumlah;
                            $diskon_id = $mmSpec->extern_id;
                            $diskon_nama = $mmSpec->extern_nama;
                            $diskon_nilai_unit = $mmSpec->nilai_unit;
                            $diskon_nilai = $mmSpec->nilai_unit;
                            $diskon_nilai_diklaim = $mmSpec->nilai_diklaim;
                            $diskon_nilai_total = $diskon_nilai + $diskon_nilai_diklaim;
                            $data4_sum = array(
                                "id" => $pid,
                                "nama" => $pnama,
                                "name" => $pnama,
                                "jml" => $jml,
                                "qty" => $jml,
                                "diskon_id" => $diskon_id,
                                "diskon_nama" => $diskon_nama,
                                "diskon_name" => $diskon_nama,
                                "diskon_persen" => 0,
                                "diskon_nilai" => $diskon_nilai_total,
                                "diskon_nilai_total" => $diskon_nilai_total,
                                "laba_lain_lain" => $diskon_nilai_total,
                            );
                            $_SESSION[$cCode]["items4_sum"][] = $data4_sum;
                        }
                    }
                }
                break;
            case "588":
            case "588st":
//                if(!isset($_SESSION[$cCode]['main']['harga_non_ppn'])){
//                    $harga_non_ppn = $_SESSION[$cCode]['main']['nett1'];
//                    $ppn = (11/100) * $harga_non_ppn;
//                    $harga_nppn = $harga_non_ppn + $ppn;
//                    $_SESSION[$cCode]['main']['harga_non_ppn'] = $harga_non_ppn;
//                }
//
                break;
            case "1771":
            case "771":
            case "111":
            case "498":
            case "487":
            case "462":
            case "1462":
            case "483":
                $addgate = array(
                    "referenceID" => "referenceID",
                    "referenceNomer" => "referenceNomer",
                    "rejection" => ".1",
                );
                foreach ($_SESSION[$cCode]["revert"]["postProc"]["detail"] as $xx => $xxSpec) {
                    foreach ($addgate as $aa => $bb) {
                        $xxSpec["static"][$aa] = $bb;
                    }
                    $_SESSION[$cCode]["revert"]["postProc"]["detail"][$xx] = $xxSpec;
                }
                break;
            case "16678":
                // mengambalikan locker transaksi komisi (invoice yang dipilih) dari 1 menjadi 0
                // supaya bisa dipilih lagi/request ulang.
                $addPostProccDetail = array(
                    array(
                        "comName" => "LockerProject",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".-1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "rejection" => ".1",
                            "reference_id" => "masterID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                );

                foreach ($addPostProccDetail as $xx => $addSpec) {
                    $_SESSION[$cCode]["revert"]["postProc"]["detail"][] = $addSpec;
                }
                break;
            case "16677":
                // mengambalikan locker transaksi komisi (invoice yang dipilih) dari 1 menjadi 0
                // supaya bisa dipilih lagi/request ulang.
                $addPostProccDetail = array(
                    array(
                        "comName" => "LockerTransaksi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".-1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "rejection" => ".1",
                            "reference_id" => "masterID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                );

                foreach ($addPostProccDetail as $xx => $addSpec) {
                    $_SESSION[$cCode]["revert"]["postProc"]["detail"][] = $addSpec;
                }

                break;

            case "19467":
                if (isset($_SESSION[$cCode]["main"]["referensi_so"]) && ($_SESSION[$cCode]["main"]["referensi_so"] > 0)) {
                    $jurnal_tambahan = false;
                    $trs = new MdlTransaksi();
                    $trsTmp = $trs->lookupDetailTransaksiNoJenis($_SESSION[$cCode]["main"]["referensi_so"])->result();
                    if (sizeof($trsTmp) > 0) {
                        foreach ($trsTmp as $trsSpec) {
                            $tr = new MdlTransaksi();
                            $tr->addFilter("id=" . $trsSpec->produk_id);
                            $trTmp = $tr->lookupMainTransaksi()->result();
                            if ($trTmp[0]->trash_4 == 1) {
                                $jurnal_tambahan = true;
                                break;
                            }
                        }
                    }
                    cekKuning("[jurnal_tambahan: $jurnal_tambahan]");
                    if ($jurnal_tambahan == true) {
                        $componentsRevertLabel = isset($this->configUi[$this->jenisTr]['componentsGantiRekening'][$jenisTr_reference]) ? $this->configUi[$this->jenisTr]['componentsGantiRekening'][$jenisTr_reference] : array();
                        $label_notif = str_replace("{customerName}", $_SESSION[$cCode]["main"]["customerName"], $componentsRevertLabel["label"]);
                        $_SESSION[$cCode]["main"]["label_notif"] = $label_notif;
                        //region component tambahan
                        $componentsRevert = isset($this->configCore[$this->jenisTr]['componentsGantiRekening'][$jenisTr_reference]) ? $this->configCore[$this->jenisTr]['componentsGantiRekening'][$jenisTr_reference] : array();
                        if (sizeof($componentsRevert) > 0) {
                            $key_cek_nilai = $componentsRevert["key"];
                            if (isset($_SESSION[$cCode]["main"][$key_cek_nilai]) && ($_SESSION[$cCode]["main"][$key_cek_nilai] > 0)) {
                                $coreMaster = $componentsRevert["core"]["master"];
                                $coreDetail = $componentsRevert["core"]["detail"];
                                if (sizeof($coreMaster) > 0) {
                                    foreach ($coreMaster as $mSpec) {
                                        $_SESSION[$cCode]["revert"]["jurnal"]["master"][] = $mSpec;
                                    }
                                }
                                if (sizeof($coreDetail) > 0) {
                                    foreach ($coreDetail as $dSpec) {
                                        $_SESSION[$cCode]["revert"]["jurnal"]["detail"][] = $dSpec;
                                    }
                                }
                            }
                        }
                        //endregion

                        //region postprocc tambahan
                        $postProccRevert = isset($this->configCore[$this->jenisTr]['postProcessorGantiRekening'][$jenisTr_reference]) ? $this->configCore[$this->jenisTr]['postProcessorGantiRekening'][$jenisTr_reference] : array();
                        if (sizeof($postProccRevert) > 0) {
                            $key_cek_nilai = $postProccRevert["key"];
                            if (isset($_SESSION[$cCode]["main"][$key_cek_nilai]) && ($_SESSION[$cCode]["main"][$key_cek_nilai] > 0)) {
                                $postMaster = $postProccRevert["core"]["master"];
                                $postDetail = $postProccRevert["core"]["detail"];
                                if (sizeof($postMaster) > 0) {
                                    foreach ($postMaster as $mSpec) {
                                        $_SESSION[$cCode]["revert"]["postProc"]["master"][] = $mSpec;
                                    }
                                }
                                if (sizeof($postDetail) > 0) {
                                    foreach ($postDetail as $dSpec) {
                                        $_SESSION[$cCode]["revert"]["postProc"]["detail"][] = $dSpec;
                                    }
                                }
                            }
                        }
                        //endregion
                    }

                }

                break;

            default:
                cekHitam("<h4>DEFAULT BOSS...</h4>");
                break;
        }


        //kloning ep_point transaksi yang dibatalkan----------------------------------
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $trs->addFilter("link_id='$tr_id_dibatalkan'");
        $trsPoint = $trs->lookupEntryPoints($tr_id_master_dibatalkan)->result();

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        cekungu("sampai ep point transaksi yang dibatalkan: $val");

        if (sizeof($trsPoint) > 0) {
            $refEpPoint = (array)$trsPoint[0];
            $nomer_old = $refEpPoint["nomer"];
            $nomer_old_ex = explode("_", $nomer_old);
            $nomer_new_ep = $nomer_old_ex[0] . "_" . $nomer_old_ex[1] . "_" . date("YmdHis");
            $jenis_old = $refEpPoint["jenis"];
            $jenis_old_ex = explode("_", $jenis_old);
            $jenis_new_ep = $jenis_old_ex[0];
            unset($refEpPoint["id"]);
            $refEpPoint["nomer"] = $nomer_new_ep;
            $refEpPoint["jenis"] = $jenis_new_ep;
            $refEpPoint["jenis_label"] = "Pembatalan " . $refEpPoint["jenis_label"];

            $_SESSION[$cCode]['tableIn_master_eppoint'] = $refEpPoint;
        }
        //----------------------------------

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        cekungu("sampai ep point transaksi yang dibatalkan: $val");

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


        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        cekungu("sampai build value akhir: $val");
        arrPrint($_SESSION[$cCode]["main"]);


        echo "<script>";
        echo "  localStorage.setItem('transID_ref', $transID_ref);";
        echo "  console.log('" . get_full_url() . "');";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }


}



