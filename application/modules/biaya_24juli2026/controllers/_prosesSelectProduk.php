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
            $target_item="items10_sum";
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
                $item_session['qty'] = 1;

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
                        $new_vcat = $vcat * $_SESSION[$cCode]['items7'][$product_id]["jml"];
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
                        $new_vcat = $vcat * $_SESSION[$cCode]['items7'][$product_id]["jml"];
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
                } else {
                    $_SESSION[$cCode]["items10_sum"][$product_id] = $item_session;
                }

                /**
                 * build sementara karena supaya tidak menggangu helper value builder items
                 */
                if(isset($_SESSION[$cCode][$target_item][$product_id])){
                    foreach($itemCloners as $src){
                        if(isset($_SESSION[$cCode]["main"][$src])){
                            $_SESSION[$cCode][$target_item][$product_id][$src]=$_SESSION[$cCode]["main"][$src];
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

        if ($product_id && isset($_SESSION[$cCode]["items10_sum"][$product_id])) {
            $_SESSION[$cCode]["items10_sum"][$product_id]['qty'] = max(1, intval($qty));
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
                    } elseif (isset($item['produk_kode']) && strlen($produk_kode) > 0 && $item['produk_kode'] == $produk_kode) {
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
            } else {
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
                        } elseif (isset($item['produk_kode']) && strlen($produk_kode) > 0 && $item['produk_kode'] == $produk_kode) {
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
                            } elseif ($chk_suffix == 'in') {
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
            } elseif ($suffix == 'in') {
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
}
// END OF COMPLETE REPEATED LOGIC
// END OF COMPLETE REPEATED LOGIC
