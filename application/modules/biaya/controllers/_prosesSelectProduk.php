<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class _prosesSelectProduk extends Modul_Controller
{
// START OF COMPLETE REPEATED LOGIC
    public function select()
    {
        $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;
        $product_id = isset($_GET['val']) ? $_GET['val'] : 0;

        if ($product_id) {
            $target_item = "items10_sum";
            $itemCloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();
            $this->load->model("Mdls/MdlProduk");
            $mp = new MdlProduk();
            $mpTmp = $mp->lookupByID($product_id)->result();
            if (sizeof($mpTmp) > 0) {
                $shoppingCartCashbackAddProduct = isset($this->configUi[$this->jenisTr]["shoppingCartCashbackAddProduct"][1]) ? $this->configUi[$this->jenisTr]["shoppingCartCashbackAddProduct"][1] : array();
                $arrDataTambahan = isset($shoppingCartCashbackAddProduct['produkUnitPart']) ? $shoppingCartCashbackAddProduct['produkUnitPart'] : array();
                $pFields = isset($shoppingCartCashbackAddProduct["selectorParamFields"]) ? $shoppingCartCashbackAddProduct["selectorParamFields"] : array(
                    "id" => "id",
                    "nama" => "nama",
                    "kode" => "kode",
                );
                $produk_jenis = $mpTmp[0]->kategori_nama;
                $item_session = array();
                foreach ($pFields as $sessionKey => $dbField) {
                    $item_session[$sessionKey] = isset($mpTmp[0]->$dbField) ? $mpTmp[0]->$dbField : "";
                }
                $rows = $mpTmp[0];
                $item_session['qty'] = 1;
                $jml_serial = $rows->jml_serial;
                $item_session['jml_serial'] = $jml_serial;
                $item_session['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                if ($jml_serial * 1 == 1) {
                    $d_kode = $rows->kode;
                    $_SESSION[$cCode]['items7'][$produk_id][$d_kode] = array();
                }
                $arrCat = array();
                $arrCode = array();
                if ($produk_jenis == "unit") {
                    foreach ($arrDataTambahan as $cat => $catSpec) {
                        foreach ($catSpec as $dkey => $dval) {
                            if (isset($rows->$dval) && ($mpTmp[0]->$dval != NULL)) {
                                $_SESSION[$cCode]['items7'][$product_id][$mpTmp[0]->$dval] = array();
                                //--------------
                                if (!isset($arrCat[$cat])) {
                                    $arrCat[$cat] = 0;
                                }
                                $arrCat[$cat] += 1;
                                //--------------
                                if (!isset($arrCode[$rows->$dval])) {
                                    $arrCode[$mpTmp[0]->$dval] = 0;
                                }
                                $arrCode[$mpTmp[0]->$dval] += 1;
                                //--------------
                            }
                        }
                    }
                }
                else {
                    $jml_serial = $mpTmp[0]->jml_serial;
                    $item_session['jml_serial'] = $jml_serial;
                    $item_session['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                    if ($jml_serial * 1 == 1) {
                        $d_kode = $mpTmp[0]->kode;
                        $_SESSION[$cCode]['items7'][$product_id][$d_kode] = array();
                        $arrCode[$d_kode] = 1;
                    }
                }

                $keterangan = "";
                $static_keterangan = "";
                if (sizeof($arrCat) > 0) {
                    foreach ($arrCat as $kcat => $vcat) {
//                        $new_vcat = $vcat * $_SESSION[$cCode]['items7'][$product_id]["jml"];
                        $new_vcat = $vcat * $item_session['qty'];
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
                        $item_session[$new_keyy] = $vcat;
                    }
                }
                if (sizeof($arrCode) > 0) {
                    foreach ($arrCode as $kcat => $vcat) {
//                        $new_vcat = $vcat * $_SESSION[$cCode]['items7'][$product_id]["jml"];
                        $new_vcat = $vcat * $item_session['qty'];
                        $item_session[$kcat] = $new_vcat;
                        $item_session["jml_" . $kcat] = $vcat;
                    }
                }
                $item_session['keterangan'] = $keterangan;
                $item_session['static_keterangan'] = $static_keterangan;
//                $item_session['place2ID'] = $static_keterangan;
//                $item_session['place2Name'] = $static_keterangan;
//                $item_session['gudang2ID'] = $static_keterangan;
//                $item_session['gudang2Name'] = $static_keterangan;

                if (!isset($_SESSION[$cCode]["items10_sum"])) {
                    $_SESSION[$cCode]["items10_sum"] = array();
                }
                if (isset($_SESSION[$cCode]["items10_sum"][$product_id])) {
                    $_SESSION[$cCode]["items10_sum"][$product_id]['qty'] += 1;
                }
                else {
                    $_SESSION[$cCode]["items10_sum"][$product_id] = $item_session;
                }

                /**
                 * build sementara karena supaya tidak menggangu helper value builder items
                 */
                if (isset($_SESSION[$cCode][$target_item][$product_id])) {
                    foreach ($itemCloners as $src) {
                        if (isset($_SESSION[$cCode]["main"][$src])) {
                            $_SESSION[$cCode][$target_item][$product_id][$src] = $_SESSION[$cCode]["main"][$src];
                        }

                    }
                }

            }
        }

        // Reset the selector main key value in session to make sure it clears
        $shoppingCartCashbackAddProduct = isset($this->configUi[$this->jenisTr]["shoppingCartCashbackAddProduct"][1]) ? $this->configUi[$this->jenisTr]["shoppingCartCashbackAddProduct"][1] : array();
        if (isset($shoppingCartCashbackAddProduct["key"])) {
            $session_key = $shoppingCartCashbackAddProduct["key"];
            $_SESSION[$cCode]["main"][$session_key] = null;
            unset($_SESSION[$cCode]["main"][$session_key]);
        }

        // Rebuild values
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();
        $vg->setPpnFactor($ppnFactor);
        $id = 0;
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
        echo "</script>";
    }

// END OF COMPLETE REPEATED LOGIC

    public function updateQty()
    {
        $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;
        $product_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $qty = isset($_GET['qty']) ? $_GET['qty'] : 1;

        $shoppingCartCashbackAddProduct = isset($this->configUi[$this->jenisTr]["shoppingCartCashbackAddProduct"][1]) ? $this->configUi[$this->jenisTr]["shoppingCartCashbackAddProduct"][1] : array();
        $arrDataTambahan = isset($shoppingCartCashbackAddProduct['produkUnitPart']) ? $shoppingCartCashbackAddProduct['produkUnitPart'] : array();


        if ($product_id && isset($_SESSION[$cCode]["items10_sum"][$product_id])) {
            $_SESSION[$cCode]["items10_sum"][$product_id]['qty'] = max(1, intval($qty));

            $this->load->model("Mdls/MdlProduk");
            $mp = new MdlProduk();
            $mpTmp = $mp->lookupByID($product_id)->result();
            $rows = $mpTmp[0];
            $produk_jenis = $rows->kategori_nama;
            $jml_serial = $rows->jml_serial;
            $arrCat = array();
            $arrCode = array();
            if ($produk_jenis == "unit") {
                foreach ($arrDataTambahan as $cat => $catSpec) {
                    foreach ($catSpec as $dkey => $dval) {
                        if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                            $_SESSION[$cCode]['items7'][$product_id][$rows->$dval] = array();
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
                $_SESSION[$cCode]["items10_sum"][$product_id]['jml_serial'] = $jml_serial;
                $_SESSION[$cCode]["items10_sum"][$product_id]['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                if ($jml_serial * 1 == 1) {
                    $d_kode = $rows->kode;
                    $_SESSION[$cCode]['items7'][$product_id][$d_kode] = array();
                    $arrCode[$d_kode] = 1;
                }
            }

            $keterangan = "";
            $static_keterangan = "";
            if (sizeof($arrCat) > 0) {
                foreach ($arrCat as $kcat => $vcat) {
//                        $new_vcat = $vcat * $_SESSION[$cCode]['items7'][$product_id]["jml"];
                    $new_vcat = $vcat * $qty;
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
                    $_SESSION[$cCode]["items10_sum"][$product_id][$new_keyy] = $vcat;
                }
            }
            if (sizeof($arrCode) > 0) {
                foreach ($arrCode as $kcat => $vcat) {
//                        $new_vcat = $vcat * $_SESSION[$cCode]['items7'][$product_id]["jml"];
                    $new_vcat = $vcat * $qty;
                    $_SESSION[$cCode]["items10_sum"][$product_id][$kcat] = $new_vcat;
                    $_SESSION[$cCode]["items10_sum"][$product_id]["jml_" . $kcat] = $vcat;
                }
            }
            $_SESSION[$cCode]["items10_sum"][$product_id]['keterangan'] = $keterangan;
            $_SESSION[$cCode]["items10_sum"][$product_id]['static_keterangan'] = $static_keterangan;


        }

        // Rebuild values
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();
        $vg->setPpnFactor($ppnFactor);
        $id = 0;
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
        echo "</script>";
    }

// START OF COMPLETE REPEATED LOGIC
    public function remove()
    {
        $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;
        $product_id = isset($_GET['id']) ? $_GET['id'] : 0;

        if ($product_id && isset($_SESSION[$cCode]["items10_sum"][$product_id])) {
            unset($_SESSION[$cCode]["items7"][$product_id]);
            unset($_SESSION[$cCode]["items10_sum"][$product_id]);

            if (isset($_SESSION[$cCode]['items9_sum']) && is_array($_SESSION[$cCode]['items9_sum'])) {
                $temp = array();
                foreach ($_SESSION[$cCode]['items9_sum'] as $item) {
                    if (isset($item['id']) && $item['id'] != $product_id) {
                        $temp[] = $item;
                    }
                }
                $_SESSION[$cCode]['items9_sum'] = $temp;
            }
        }

        // Rebuild values
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();
        $vg->setPpnFactor($ppnFactor);
        $id = 0;
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
        echo "</script>";
    }
// END OF COMPLETE REPEATED LOGIC

// START OF COMPLETE REPEATED LOGIC
// START OF COMPLETE REPEATED LOGIC
// START OF COMPLETE REPEATED LOGIC
    public function recordSerial()
    {
        $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;
        $product_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $index = isset($_GET['index']) ? intval($_GET['index']) : -1;
        $serial = isset($_GET['serial']) ? trim($_GET['serial']) : "";
        $act = isset($_GET['act']) ? $_GET['act'] : "";
        $current_cabang_id = my_cabang_id();

        if ($product_id && isset($_SESSION[$cCode]["items10_sum"][$product_id])) {
            if (!isset($_SESSION[$cCode]['items9_sum'])) {
                $_SESSION[$cCode]['items9_sum'] = array();
            }

            $pSpec = $_SESSION[$cCode]["items10_sum"][$product_id];
            $produk_kode = isset($pSpec['produk_kode']) ? $pSpec['produk_kode'] : "";

            // Find SKU key in items7
            $sku = "";
            if (isset($_SESSION[$cCode]['items7'][$product_id]) && is_array($_SESSION[$cCode]['items7'][$product_id])) {
                $keys = array_keys($_SESSION[$cCode]['items7'][$product_id]);
                if (sizeof($keys) > 0) {
                    $sku = $keys[0];
                }
            }
            if (strlen($sku) == 0 && strlen($produk_kode) > 0) {
                $sku = $produk_kode;
                $_SESSION[$cCode]['items7'][$product_id][$sku] = array();
            }

            if ($act == 'delete' && $index >= 0) {
                // 1. Remove from items9_sum
                $product_indices = array();
                foreach ($_SESSION[$cCode]['items9_sum'] as $k => $item) {
                    $match = false;
                    if (isset($item['id']) && $item['id'] == $product_id) {
                        $match = true;
                    }
                    elseif (isset($item['produk_kode']) && strlen($produk_kode) > 0 && $item['produk_kode'] == $produk_kode) {
                        $match = true;
                    }
                    if ($match) {
                        $product_indices[] = $k;
                    }
                }
                if (isset($product_indices[$index])) {
                    $target_key = $product_indices[$index];
                    unset($_SESSION[$cCode]['items9_sum'][$target_key]);
                    $_SESSION[$cCode]['items9_sum'] = array_values($_SESSION[$cCode]['items9_sum']);
                }

                // 2. Remove from items7
                if (strlen($sku) > 0 && isset($_SESSION[$cCode]['items7'][$product_id][$sku][$index])) {
                    unset($_SESSION[$cCode]['items7'][$product_id][$sku][$index]);
                    $_SESSION[$cCode]['items7'][$product_id][$sku] = array_values($_SESSION[$cCode]['items7'][$product_id][$sku]);
                }
            }
            else {
                if (strlen($serial) > 0) {
                    // Validasi khusus untuk Cashback Penjualan (6677)
                    if ($this->jenisTr == "6677") {
                        if (!$this->_validateSerial($product_id, $serial, $cCode)) {
                            return;
                        }
                    }

                    $product_indices = array();
                    foreach ($_SESSION[$cCode]['items9_sum'] as $k => $item) {
                        $match = false;
                        if (isset($item['id']) && $item['id'] == $product_id) {
                            $match = true;
                        }
                        elseif (isset($item['produk_kode']) && strlen($produk_kode) > 0 && $item['produk_kode'] == $produk_kode) {
                            $match = true;
                        }
                        if ($match) {
                            $product_indices[] = $k;
                        }
                    }
                    $qty = isset($pSpec['qty']) ? intval($pSpec['qty']) : 1;
                    $jml_serial = isset($pSpec['jml_serial']) ? intval($pSpec['jml_serial']) : 1;
                    $max_allowed = $qty * $jml_serial;

                    if (sizeof($product_indices) < $max_allowed) {
                        // 1. Add to items9_sum
                        $clone = $pSpec;
                        $clone['id'] = $product_id;
                        $clone['qty'] = 1;
                        $clone['serial'] = $serial;
                        $_SESSION[$cCode]['items9_sum'][] = $clone;

                        // 2. Add to items7
                        if (strlen($sku) > 0) {
                            if (!isset($_SESSION[$cCode]['items7'][$product_id][$sku]) || !is_array($_SESSION[$cCode]['items7'][$product_id][$sku])) {
                                $_SESSION[$cCode]['items7'][$product_id][$sku] = array();
                            }
                            $_SESSION[$cCode]['items7'][$product_id][$sku][] = $serial;
                        }
                    }
                }
            }
        }

        // Rebuild values
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();
        $vg->setPpnFactor($ppnFactor);
        $id = 0;
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
        echo "</script>";
    }

    private function _validateSerial($product_id, $serial, $cCode)
    {
        // 0. Ambil spesifikasi produk untuk memvalidasi jml_serial, outdoor_sku, dll
        $this->load->model("Mdls/MdlProduk");
        $mp = new MdlProduk();
        $mpTmp = $mp->lookupByID($product_id)->result();
        if (sizeof($mpTmp) == 0) {
            echo "<script>";
            echo "top.swal('Produk Tidak Valid', 'Produk tidak ditemukan dalam database.', 'error');";
            echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
            echo "</script>";
            return false;
        }

        $product_spec = $mpTmp[0];
        $jml_serial = isset($product_spec->jml_serial) ? intval($product_spec->jml_serial) : 0;

        // A. Cek duplikasi di keranjang belanja saat ini
        if (isset($_SESSION[$cCode]['items9_sum']) && is_array($_SESSION[$cCode]['items9_sum'])) {
            foreach ($_SESSION[$cCode]['items9_sum'] as $item) {
                if (isset($item['serial']) && $item['serial'] == $serial) {
                    echo "<script>";
                    echo "top.swal('Duplikasi Serial', 'Nomor serial \"' + " . json_encode($serial) . " + '\" sudah terdaftar dalam keranjang belanja saat ini.', 'warning');";
                    echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
                    echo "</script>";
                    return false;
                }
            }
        }

        // B. Ambil nomor serial sistem (produk_serial_number_2) dari tabel produk_per_serialnumber
        $sys_serial = $serial;
        $sql_sn = "SELECT produk_serial_number_2 FROM produk_per_serialnumber WHERE produk_id = ? AND (produk_serial_number = ? OR produk_serial_number_2 = ?) LIMIT 1";
        $query_sn = $this->db->query($sql_sn, array($product_id, $serial, $serial));
        if ($query_sn->num_rows() > 0) {
            $row_sn = $query_sn->row();
            $sys_serial = $row_sn->produk_serial_number_2;
        }

        // C. Validasi pembagian serial "in" (indoor) vs "ot" (outdoor) jika jml_serial > 1 (AC)
        if ($jml_serial > 1) {
            $suffix = "";
            if (strpos($sys_serial, ':') !== false) {
                $parts = explode(':', $sys_serial);
                $suffix = strtolower(trim(end($parts)));
            }

            if ($suffix != 'ot' && $suffix != 'in') {
                echo "<script>";
                echo "top.swal('Format Serial Salah', 'Produk ini memerlukan serial bertipe Indoor (in) atau Outdoor (ot).', 'error');";
                echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
                echo "</script>";
                return false;
            }

            // Hitung kuantiti dan hitung serial terdaftar di keranjang untuk produk ini
            $pSpec = isset($_SESSION[$cCode]["items10_sum"][$product_id]) ? $_SESSION[$cCode]["items10_sum"][$product_id] : array();
            $qty = isset($pSpec['qty']) ? intval($pSpec['qty']) : 1;

            $existing_ot = 0;
            $existing_in = 0;
            if (isset($_SESSION[$cCode]['items9_sum']) && is_array($_SESSION[$cCode]['items9_sum'])) {
                foreach ($_SESSION[$cCode]['items9_sum'] as $item) {
                    if (isset($item['id']) && $item['id'] == $product_id) {
                        $chk_serial = isset($item['serial']) ? $item['serial'] : "";
                        $chk_sys = $chk_serial;
                        $sql_chk = "SELECT produk_serial_number_2 FROM produk_per_serialnumber WHERE produk_id = ? AND (produk_serial_number = ? OR produk_serial_number_2 = ?) LIMIT 1";
                        $query_chk = $this->db->query($sql_chk, array($product_id, $chk_serial, $chk_serial));
                        if ($query_chk->num_rows() > 0) {
                            $chk_sys = $query_chk->row()->produk_serial_number_2;
                        }
                        if (strpos($chk_sys, ':') !== false) {
                            $chk_parts = explode(':', $chk_sys);
                            $chk_suffix = strtolower(trim(end($chk_parts)));
                            if ($chk_suffix == 'ot') {
                                $existing_ot++;
                            }
                            elseif ($chk_suffix == 'in') {
                                $existing_in++;
                            }
                        }
                    }
                }
            }

            if ($suffix == 'ot') {
                $max_ot = $qty * 1;
                if ($existing_ot >= $max_ot) {
                    echo "<script>";
                    echo "top.swal('Kuota Terpenuhi', 'Nomor serial Outdoor (ot) untuk produk ini sudah memenuhi kuota maksimal (' + " . intval($max_ot) . " + ').', 'warning');";
                    echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
                    echo "</script>";
                    return false;
                }
            }
            elseif ($suffix == 'in') {
                $max_in = ($jml_serial == 3) ? ($qty * 2) : ($qty * 1);
                if ($existing_in >= $max_in) {
                    echo "<script>";
                    echo "top.swal('Kuota Terpenuhi', 'Nomor serial Indoor (in) untuk produk ini sudah memenuhi kuota maksimal (' + " . intval($max_in) . " + ').', 'warning');";
                    echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
                    echo "</script>";
                    return false;
                }
            }
        }

        // D. Langkah 1: Cek apakah serial pernah dikeluarkan oleh sistem secara global
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $ps_global = new ComRekeningPembantuProdukPerSerial();
        $ps_global->addFilter("produk_id='" . $product_id . "'");
        $ps_global->addFilter("extern_nama='" . $sys_serial . "'");
        $res_global = $ps_global->fetchBalances("1010030030");

        $is_global_issued = false;
        if (sizeof($res_global) > 0) {
            foreach ($res_global as $bal) {
                if (isset($bal->qty_debet) && $bal->qty_debet > 0) {
                    $is_global_issued = true;
                    break;
                }
            }
        }

        if (!$is_global_issued) {
            echo "<script>";
            echo "top.swal('Serial Tidak Valid', 'Nomor serial \"' + " . json_encode($serial) . " + '\" tidak valid atau belum pernah dikeluarkan oleh sistem.', 'error');";
            echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
            echo "</script>";
            return false;
        }

        // E. Langkah 2: Cek apakah serial dikeluarkan dari cabang scanning aktif saat ini
        $current_cabang_id = my_cabang_id();
        $current_cabang_nama = my_cabang_nama();

        $ps_branch = new ComRekeningPembantuProdukPerSerial();
        $ps_branch->addFilter("produk_id='" . $product_id . "'");
        $ps_branch->addFilter("extern_nama='" . $sys_serial . "'");
        $ps_branch->addFilter("cabang_id='" . $current_cabang_id . "'");
        $res_branch = $ps_branch->fetchBalances("1010030030");

        $is_branch_issued = false;
        if (sizeof($res_branch) > 0) {
            foreach ($res_branch as $bal) {
                if (isset($bal->qty_debet) && $bal->qty_debet > 0) {
                    $is_branch_issued = true;
                    break;
                }
            }
        }

        if (!$is_branch_issued) {
            echo "<script>";
            echo "top.swal('Cabang Tidak Sesuai', 'Nomor serial \"' + " . json_encode($serial) . " + '\" tidak dikeluarkan dari cabang ini (Cabang Anda: ' + " . json_encode($current_cabang_nama) . " + ').', 'error');";
            echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/viewCart/" . $this->jenisTr . "');";
            echo "</script>";
            return false;
        }

        return true;
    }
// END OF COMPLETE REPEATED LOGIC




    public function selectBarang()
    {
        // arrPrint($_GET);

        //untuk obat sementara discount minus
        if ($_GET["disc_percent"] < 0 || $_GET["disc_percent"] > 100) {
            $minus = $_GET["disc_percent"] < 0 ? "persentase diskon salah, silahkan menggunakan nilai positif" : "pemberian diskon salah, diskon maksimal 100%";
            matiHEre($minus);
        }
        if ($_GET["disc"] < 0) {
            matiHEre("pemberian diskon salah, silahkan menggunakan nilai positif");
        }

        $this->load->helper("he_angka_helper");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $produk_id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();

        $tmpB = $b->lookupByID($id)->result();

        // -----------------------------------------
        $tableIn_master = $_SESSION[$cCode]['tableIn_master'];
        $gudang_status_id = $tableIn_master['gudang_status_id'];

        $this->load->model("Mdls/MdlLockerStockBooking");
        $lsb = new MdlLockerStockBooking();
        $lsb_datas = $lsb->getStokBooking();

        $ppnFactorInclude = $_SESSION[$cCode]['main']['ppnFactorInclude'];


        $this->load->library("Diskon");
        $ld = new Diskon();
        $ld->setTokoId(my_toko_id());
        $pro_jml = 0;
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
                $produk_label_2 = "<span class='text-red'>Produk $produk_kode $produk_nama</span>";

                $valValidate_items = array();

                if (sizeof($validateMeasurement) > 0) {
                    $iValidate = 0;
                    foreach ($validateMeasurement as $keyVal => $validateKol) {
                        $valValidate = $row->$keyVal;
                        if ($valValidate == 0) {
                            $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                            $alerts = array(
                                "type" => "warning",
                                "title" => strtoupper("Data ukuran produk $produk_label_2 belum lengkap "),
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

                /** ----------------------------------------------------------------
                 * inisisasi cCode Items harga price
                 * ----------------------------------------------------------------*/
                if (!isset($_SESSION[$cCode]['items']) || !array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                        "discount_persen" => isset($arrDiscount[$id]['persen']) ? $arrDiscount[$id]['persen'] : 0,
                        "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                        "harga_jasa" => 0,
                    );

                    if (sizeof($priceMainConfig) > 0) {
                        if (isset($priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']])) {
                            $priceConfig = $priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']];
                            cekUngu("masuk disini...");
                        }
                    }

                    //                    cekBiru(__LINE__ . " sebelum price");
                    if (sizeof($priceConfig) > 0) {
                        //                        cekHijau("mmasuk price @" . __LINE__);
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                            $h->addFilter("produk_id='$id'");
                            //                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("cabang_id=" . CB_ID_PUSAT);
                        }
                        else {
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            //                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("cabang_id=" . CB_ID_PUSAT);
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
                        showLast_query("kuning");
// matiHere(__LINE__);
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    //                                    cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        //                                        cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        //                                        cekBiru("sini " . __LINE__);
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
                                    }

                                }

                            }
                            //                            arrPrintKuning($rawPrices);
                            $prices = normalizePrices("produk", $rawPrices);
                            //                            arrPrint($prices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga_reguler'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                if ($tmp['harga'] == 0) {
                                    $msg = "Harga jual Dealer atau End User $produk_label_2 belum disetting. Silahkan segera hubungi admin untuk setting harga jual.";
                                    mati_disini($msg);
                                }
                                // arrPrintKuning($rawPrices);
                                // arrPrintPink($tmp);
                                // matiDisini(__LINE__);
                            }
                            else {
                                $msg = "Harga jual Dealer atau End User $produk_label_2 belum disetting. Silahkan segera hubungi admin untuk setting harga jual.";
                                mati_disini($msg);
                            }
                        }
                        else {
                            $msg = "Harga jual Dealer atau End User $produk_label_2 belum disetting. Silahkan segera hubungi admin untuk setting harga jual.";
                            mati_disini($msg);
                        }
                    }
                    // arrPrintHijau($tmpH);
                    // arrPrintPink($tmp);
                    //                     matiHere(__LINE__);
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
                            //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                //                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                $tmp[$key] = $newValue;

                            }
                        }
                    }


                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    // arrPrintPink($tmp);
                    // matiHere(__LINE__);
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
                    cekBiru("ada id $id  ada cCode items $cCode");

                    /** ---------------------------------------------
                     * penambahan pilihan harga manual
                     * konsumen dengan level distributor tidak bisa merubah/memilih harga
                     * ---------------------------------------------*/
                    $harga_pilihan = isset($_GET['harga']) ? $_GET['harga'] : false;
                    if ($harga_pilihan != false) {
                        if (isset($_SESSION[$cCode]['items'][$id]['jual'])) {
                            // cekOrange("harga diganti");
                            $_SESSION[$cCode]['items'][$id]['jual'] = $harga_pilihan;
                        }

                        if (isset($_SESSION[$cCode]['items'][$id]) && isset($_GET['rowid'])) {
                            if ($_SESSION[$cCode]['items'][$id]['id'] == $id) {
                                $_SESSION[$cCode]['items'][$id]['row_harga_id'] = $_GET['rowid'];
                            }
                        }

                        if (isset($_GET['rowid'])) {

                            $_SESSION[$cCode]['harga_dipilih'][$id]['rowid'] = $_GET['rowid'];
                            $_SESSION[$cCode]['harga_dipilih'][$id]['harga'] = $harga_pilihan;
                        }
                    }
                    //---end pilihan harga manual----------------------------------------------------

                    // cekBiru("harga_pilihan: $harga_pilihan");
                    // cekBiru("after price  $id");
                    // arrPrint($_SESSION[$cCode]['items']);
                    // arrPrint($_SESSION[$cCode]['harga_dipilih']);
                    // arrPrintBlue($_SESSION[$cCode]['items'], __LINE__);

                    if (isset($_GET['newQty'])) {
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
                        // matiHEre();
                    }

                    // arrPrint($itemNumLabels);
                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                if ($key == "disc") {
                                    // matiHEre();
                                    $newValue = pembulatanDiskon($_GET[$key]);
                                }
                                else {
                                    $newValue = $_GET[$key];
                                }

                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

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

                /** ----------------------------------------------------------
                 * diskon-diskonan-grosir
                 * ----------------------------------------------------------*/
                $tmp = $_SESSION[$cCode]['items'][$id];
                $sesmain = $_SESSION[$cCode]["main"];
                $pihak_kategori = $sesmain['kategoriNama'];
                $pro_diskon = $rows->diskon_persen;
                $pro_premi = $rows->premi_jual;
                // cekHitam($pro_premi . " uhui");
                $pro_harga = $tmp['jual'] + (($pro_premi / 100) * $tmp['jual']);
                $pro_harga_reseller = $tmp['jual_reseller'] + (($pro_premi / 100) * $tmp['jual_reseller']);
                $pro_harga_online = $tmp['jual_online'] + (($pro_premi / 100) * $tmp['jual_online']);
                // cekBiru("pro_harga_reseller: $pro_harga_reseller");
                // cekBiru("masuk ke diskon2an:: $pro_premi || $pro_harga ori:".$tmp['jual']." @" . __LINE__);
                // cekKuning("$pihak_kategori");
                cekKuning("$pro_harga");
                cekKuning("$pihak_kategori");
                $pro_jml = $tmp['jml'];

                $calc_hasils = $ld->selectorDiskon($id, $pro_harga, $pro_jml, $rows);

                $calc_hasil = $calc_hasils["simple"];
                /** -------------------------------------------------
                 * untuk membatasi pihak kategori tertentu tentang pilihan harga
                 * -------------------------------------------------*/
                // if ($pihak_kategori == "distributor") {
                //     $calc_hasils = $ld->selectorDiskon($id, $pro_harga_reseller, $pro_jml, $rows, $sesmain);
                //     arrPrintHijau($calc_hasils);
                //     $calc_hasil = $calc_hasils["grosir"];
                // }
                //
                // if ($pihak_kategori == "online") {
                //     $calc_hasils = $ld->selectorDiskon($id, $pro_harga_online, $pro_jml, $rows, $sesmain);
                //     arrPrintHijau($calc_hasils);
                //     $calc_hasil = $calc_hasils["simple"];
                // }

                // else {
                //     $calc_hasils = $ld->selectorDiskon($id, $pro_harga, $pro_jml, $rows);
                //     arrPrintHijau($calc_hasils);
                //     $calc_hasil = $calc_hasils["simple"];
                // }

                //                arrPrintKuning($calc_hasil);
                //                 cekHijau($gudang_status_id);
                $stok_booking = isset($lsb_datas[$id][$gudang_status_id]) ? $lsb_datas[$id][$gudang_status_id]['sum_valid_qty'] : "0";
                // arrPrintPink($stok_booking);
                // matiHere(__LINE__);
                $tmp['stok_booking'] = $stok_booking;
                // $tmp['stok_booking_center'] = 99;
                $tmp['discPersen'] = $calc_hasil['persen'];
                $tmp['lastNett'] = $calc_hasil['harga_af'];
                // $tmp['harga'] = $pihak_kategori == "reguler" ? $pro_harga : $calc_hasil['harga_af'];
                /** ---------------------------------------------------------------------------
                 * kategori ada 3: reguler distributor online (tidak bisa memilih harga)
                 * yg mendapat diskon berjenang hanya distributor
                 * jika ada premi semua diskon off
                 * ---------------------------------*/
                $yg_dipakai = 2;
                if ($yg_dipakai == 1) {
                    if ($pro_premi > 0) {
                        $harga_yg_dipakai = $pro_harga;
                    }
                    elseif ($pro_premi == 0) {
                        $harga_yg_dipakai = $calc_hasil['harga_af'];
                    }
                    else {
                        if ($pihak_kategori == "distributor") {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                        }
                        else {
                            $harga_yg_dipakai = $pro_harga;
                        }
                    }
                }
                elseif ($yg_dipakai == 2) {
                    if ($pihak_kategori == "distributor") {
                        if ($pro_premi > 0) {
                            $harga_yg_dipakai = $pro_harga;
                            $jual_dipakai = $pro_harga;
                        }
                        else {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                            $jual_dipakai = $pro_harga_reseller;

                            // cekAlert(__LINE__);
                        }
                    }
                    else {
                        if ($pro_premi > 0) {
                            $harga_yg_dipakai = $pro_harga;
                            $jual_dipakai = $pro_harga;
                        }
                        else {
                            // cekAlert(__LINE__);
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                            $jual_dipakai = $pro_harga;
                        }
                    }
                }
// cekAlert($harga_yg_dipakai);

                $tmp['jual_dipakai'] = $jual_dipakai;
                $tmp['harga'] = $harga_yg_dipakai;
                // ------------------------------------------------------end--------------------
                $tmp['harga_jual'] = $calc_hasil['harga_be'] * $tmp['satuan_factor_qty'];
                $tmp['harga_disc'] = ($calc_hasil['harga_af'] * $tmp['satuan_factor_qty']) * $tmp['qty_unit'];

                $tmp['discNilai'] = $calc_hasil['nilai'] * $tmp['satuan_factor_qty'];
                $tmp['id'] = $id;

                $tmp['subtotal'] = $calc_hasil['harga_af'] * $pro_jml;
                // -------------------------------------------------------------------
                // $produk_jenis["jml"] = $pro_jml;
                // memasukkan kolom sku ke items2
                // handle serial 1 dan scan mode
                $jml_serial = $rows->jml_serial;
                $tmp['jml_serial'] = $jml_serial;
                $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                if ($jml_serial * 1 == 1) {
                    $d_kode = $rows->kode;
                    $_SESSION[$cCode]['items2'][$produk_id][$d_kode] = array();
                }
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
                    $_SESSION[$cCode]['items2'][$produk_id][$rows->kode] = array();
                    $arrCat["barcode"] = 1;
                    $arrCode[$rows->kode] = 1;
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
                                    "harga" => $tmpKomposit_0->harga / $ppnFactorInclude,
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
                                    "harga" => $tmpKomposit_0->harga / $ppnFactorInclude,
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

            /* -----------------------------------------------------------
            * diskon unit/non unit
            * -----------------------------------------------------------*/
            unset($_SESSION[$cCode]['items_kategori']);
            foreach ($_SESSION[$cCode]['items'] as $item) {
                $kategori_produk = $item["kategori_nama"];
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
            }
            foreach ($_SESSION[$cCode]['items'] as $item) {
                $pro_jml = $item['jml'];
                $produk_jenis = str_replace(" ", "_", $item['kategori_nama']);

                if (!isset($_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'])) {
                    $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] = 0;
                }
                $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] += $pro_jml;
                $kategori_produk = $item["kategori_nama"];
                if (!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])) {
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;
            }
            // arrPrint($_SESSION[$cCode]["main"]);
            // ---------------------------------------------------------

            $potongan_nilai = $ld->selectorDiskonKategori($_SESSION[$cCode]);

//            arrPrintPink($potongan_nilai);
            // arrPrintKuning($produk_jenis);
//            cekHijau("membuat session main");
            if (ipadd() == "202.65.117.72") {
                //                mati_disini(__LINE__);
            }
            $rows2 = array();
            $pro_premi2 = 0;
            foreach ($potongan_nilai as $dcu_kategori => $dcu) {
                $nilai_dcu = $dcu['nilai'];
                $_SESSION[$cCode]["main"]["diskon_kategori_$dcu_kategori"] = $nilai_dcu;
                $_SESSION[$cCode]["main"]["jml_kategori_$dcu_kategori"] = $dcu['jml'];

                // if ($nilai_dcu > 0) {
                $sesmain2 = $_SESSION[$cCode]["main"];
                $pihak_kategori2 = $sesmain2["kategoriNama"];
                cekHere("update harga yg dipakai");
                foreach ($_SESSION[$cCode]['items'] as $pro_id => $item_speks) {
                    /* --------------------------------------------------
                     * pilih yg sebagai dasar mau harga list atau harga distributor/reseller
                     * ---------------------------------------------------*/
                    $pro_harga2 = $item_speks['jual'] + (($pro_premi2 / 100) * $item_speks['jual']);
                    $pro_harga_reseller2 = $item_speks['jual_reseller'] + (($pro_premi2 / 100) * $item_speks['jual_reseller']);
                    // ------------------------------------------------------------------------
                    $pro_jml2 = $item_speks['jml'];

                    if ($nilai_dcu > 0) {
                        if (!isset($item_speks['jual_reseller'])) {
                            $pro_harga_dipakai = $pro_harga2;
                        }
                        else {
                            $pro_harga_dipakai = $pro_harga_reseller2;
                        }
                        $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga_dipakai, $pro_jml2, $rows2, $sesmain2);
                        //                        $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga_reseller2, $pro_jml2, $rows2, $sesmain2);
                        $calc_hasil = $calc_hasils["grosir"];
                    }
                    else {
                        //     // if ($pihak_kategori2 == "distributor") {
                        cekOrange("harusnya tidak diskon " . __LINE__);
                        // $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga2, $pro_jml2, $rows2, $sesmain2);
                        // $calc_hasil = $calc_hasils["grosir"];
                        $calc_hasil = array(
                            "type" => "diskon",
                            "persen" => "0",
                            "nilai" => "0",
                            "harga_be" => $pro_harga2,
                            "harga_af" => $pro_harga2,
                        );
//                        arrPrintHijau($calc_hasils);
                    }

//                    cekHitam("$pro_id ---------");
//                    arrPrintWebs($calc_hasils);

                    $tmp2['discPersen'] = $calc_hasil['persen'];
                    $tmp2['lastNett'] = $calc_hasil['harga_af'];
                    $tmp2['jual_dipakai'] = $pro_harga_reseller2;
                    $tmp2['harga'] = $calc_hasil['harga_af'];

                    arrPrintKuning($tmp2);

                    /* ----------------------------------------------------------------------
                     * ngupdate session items pada key2 tertentu saja spt yg didefine diatasnya
                     * ----------------------------------------------------------------------*/
                    foreach ($tmp2 as $sesKey => $newSesValue) {
                        $_SESSION[$cCode]['items'][$pro_id][$sesKey] = $newSesValue;
                    }
                }

                // }
            }
            // --------------------------en kategori diskon----------------------

            /* -----------------------------------------------------------------
             * ngupdate harga yg dipakai per item
             * -----------------------------------------------------------------*/
            // if(isset($potongan_nilai) && (count($potongan_nilai) > 0) && ($potongan_nilai['nilai'] > 0)){
            //     cekHere("update harga yg dipakai");
            //     foreach ($_SESSION[$cCode]['items'] as $item){
            //
            //     }
            // }

            //region hadiah
            $hadiahTmp = $ld->callHadiahPenjualanProduk($_SESSION[$cCode]['items']);
            if(count($hadiahTmp)>0){
                //unset semua hadiah dan buil ulang
                //rebuild items untuk menmbahkan hadiah untuk tampil di rombongan shopingcart
                /**
                 * inject ke items
                 * unset dulu items
                 */
//                matiHere(my_ppn_factor());
                foreach ($hadiahTmp["summary"] as $rel_id =>$hadiahData){
                    unset($_SESSION[$cCode]["items"][$rel_id]);

                    //ambil ulang data produk hadiah
                    $tmpB = $b->lookupByID($rel_id)->result();
//                    arrPrint($hadiahData);
//                    arrPrint($fieldSrcs);
//                    matiHEre(__LINE__);
                    if(count($tmpB)>0){

                        foreach($tmpB as $d_h =>$dataProdukHadiah){
//                            arrPrint($dataProdukHadiah);
//                            matiHEre();
                            $tmpHadiah = array(
                                "handler" => $this->modul . "/" . $this->uri->segment(2),
                                "id" => $hadiahData["produk_rel_id"],
                                "jml" => $hadiahData["jml"],
                                "qty" => $hadiahData["jml"],
                                "harga" => $hadiahData["produk_rel_harga"],
                                "subtotal" => $hadiahData["jml"]*$hadiahData["produk_rel_harga"],
                                "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                                "disc_percent" => 100,
                                "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                                "harga_jasa" => 0,
                                "disc"=>$hadiahData["produk_rel_harga"],
                                "_diskon_ppn"=>$hadiahData["produk_rel_harga"]*(1+(my_ppn_factor()/100)),
                            );
                            foreach ($fieldSrcs as $key => $src) {
                                if (is_array($src) && sizeof($src) > 0) {
                                    foreach ($src as $srcSpec) {
                                        if (isset($tmpHadiah[$srcSpec]) || isset($dataProdukHadiah->$srcSpec)) {
                                            cekBiru("ambil gerbang key -> $srcSpec");
                                            $tmpHadiah[$key] = makeValue($srcSpec, $tmpHadiah, $tmpHadiah, isset($dataProdukHadiah->$srcSpec) ? $dataProdukHadiah->$srcSpec : 0);
                                        }
                                    }
                                }
                                else {
                                    $tmpHadiah[$key] = makeValue($src, $tmpHadiah, $tmpHadiah, isset($rows->$src) ? $dataProdukHadiah->$src : 0);
                                    //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                                }
                            }
//                            arrPrint($tmpHadiah);
//                            matiHere();
                            $tmpHadiah["allowEdit"]=false;
                            $tmpHadiah["type_produk"]="hadiah";

                            if (sizeof($itemNumLabels) > 0) {

                                foreach ($itemNumLabels as $key => $label) {
                                    if (isset($tmpHadiah[$key]) && $tmpHadiah[$key] > 0) {
                                        $newValue = $tmpHadiah[$key];
                                        $tmpHadiah[$key] = $newValue;
                                        //                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                        $tmpHadiah[$key] = $newValue;

                                    }
                                }
                            }

                            if ($subAmountConfig != null) {
                                $tmpHadiah['subtotal'] = makeValue($subAmountConfig, $tmpHadiah, $tmpHadiah, 0);
                            }
                            else {
                                $tmpHadiah['subtotal'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$rel_id] = $tmpHadiah;
//arrPrint( $_SESSION[$cCode]['items'][$rel_id]);
//                            matiHere($hadiahData["produk_rel_id"]);

                        }
                    }


                }
//                matiHEre(__LINE__);
//                $_SESSION[$cCode]["items5_sum"]=$hadiahTmp;
            }
            //endregion


        }
        else {
            cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
            die();
        }

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
        $vg->setppnFactor($ppnFactor);


        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        if (isset($_GET['minValue']) || isset($_GET['rowid'])) {
            echo "<script>";
            echo "top.console.log('minValue isset shoppingcart direload');";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
        else {
            echo "<script>";
            echo "top.calcShoppingCartPettycash();";
            echo "</script>";
        }


    }


}
// END OF COMPLETE REPEATED LOGIC
// END OF COMPLETE REPEATED LOGIC
