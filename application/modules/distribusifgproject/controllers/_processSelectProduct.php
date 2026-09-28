<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */

require_once "Modul_Controller.php";

class _processSelectProduct extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $produk_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        if (isset($this->configUi[$this->jenisTr]['selectorModelEdit'])) {
            $selectorModel = $this->configUi[$this->jenisTr]['selectorModelEdit'];
        }
        if (isset($this->configUi[$this->jenisTr]['selectorSrcModelEdit'])) {
            $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModelEdit'];
        }

        //-----------------------------------------------
        if (!isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = array();
        }
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();
        // $arrDataTambahan = array(
        //     "outdoor" => array(
        //         "outdoor_id" => "outdoor_sku",
        //     ),
        //     "indoor" => array(
        //         "indoor_id_1" => "indoor_sku_1",
        //         "indoor_id_2" => "indoor_sku_2",
        //         "indoor_id_3" => "indoor_sku_3",
        //         "indoor_id_4" => "indoor_sku_4",
        //     ),
        //     "heater" => array(
        //         "heater_id" => "heater_nama",
        //     ),
        //     "part" => array(
        //         "produk_part_id_1" => "produk_part_nama_1",
        //         "produk_part_id_2" => "produk_part_nama_2",
        //         "produk_part_id_3" => "produk_part_nama_3",
        //     ),
        // );
        //-----------------------------------------------

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
        $selectorLockerProcessFilters = isset($this->configUi[$this->jenisTr]['selectorLockerProcessFilters']) ? $this->configUi[$this->jenisTr]['selectorLockerProcessFilters'] : array();
        $shopingCartReferenceValidator = isset($this->configUi[$this->jenisTr]['shopingCartReferenceValidator']) ? $this->configUi[$this->jenisTr]['shopingCartReferenceValidator'] : array();
        $shopingCartStockValidate = isset($this->configUi[$this->jenisTr]['shopingCartStockValidate'][1]) ? $this->configUi[$this->jenisTr]['shopingCartStockValidate'][1] : false;

        //ambil penggunaan dari sub_tasklist_komposisi
        $no_spk_ses = isset($_SESSION[$cCode]['main']["pihakProjekNoSpk"]) ? $_SESSION[$cCode]['main']["pihakProjekNoSpk"] : "";
        $this->load->model("Mdls/MdlSubProgresTasklistKomposisi");
        $sptk = new MdlSubProgresTasklistKomposisi();
        $sptk->addFilter("no_spk='$no_spk_ses'");
        $sptk->addFilter("progress_id='2'");
        $tmpSptk = $sptk->lookupAll()->result();

        $arrUsedProjectSpk = array();
        if (!empty($tmpSptk)) {
            foreach ($tmpSptk as $k => $used_data) {
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["id"] = $used_data->produk_dasar_id;
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["nama"] = $used_data->produk_dasar_nama;
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["satuan"] = $used_data->satuan;
                if (!isset($arrUsedProjectSpk[$used_data->produk_dasar_id]["data"])) {
                    $arrUsedProjectSpk[$used_data->produk_dasar_id]["data"] = 0;
                }
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["data"]++;
                if (!isset($arrUsedProjectSpk[$used_data->produk_dasar_id]["jml_used"])) {
                    $arrUsedProjectSpk[$used_data->produk_dasar_id]["jml_used"] = 0;
                }
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["jml_used"] += $used_data->jml * 1;
            }
        }

// arrPrint($arrUsedProjectSpk);
// matiHere();
// ambil penggunaan dari sub_tasklist_komposisi

        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $produk_jenis = $rows->kategori_nama;
                $produk_nama = $rows->nama;
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
//                    cekMerah("masuk locker config");
//arrPrintKuning($selectorLockerProcessFilters);
                    $mdlName = $lockerConfig['mdlName'];
                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
                    if (sizeof($selectorLockerProcessFilters) > 0) {
                        makeFilter($selectorLockerProcessFilters, $_SESSION[$cCode]["main"], $c);

                        foreach ($selectorLockerProcessFilters as $fval) {
                            $fval_ex = explode("=", $fval);
                            $fval_ex_0 = $fval_ex[0];
                            $fval_ex_1 = $fval_ex[1];
                            $new_key = "pre__" . $fval_ex_0;
                            $$new_key = $_SESSION[$cCode]["main"][$fval_ex_1];
//                            cekHijau($pre__cabang_id);
//                            cekHijau($pre__gudang_id);
                        }
                    }
                    else {
//                        $pre__cabang_id = $this->session->login['cabang_id'];
                        $pre__cabang_id = isset($_SESSION[$cCode]['main']["projectCabangID"]) ? $_SESSION[$cCode]['main']["projectCabangID"] : $this->session->login['cabang_id'];
//                        $pre__gudang_id = $this->session->login['gudang_id'];
                        $pre__gudang_id = isset($_SESSION[$cCode]['main']["pihakProjekWorkorderSubGudangID"]) ? $_SESSION[$cCode]['main']["pihakProjekWorkorderSubGudangID"] : $this->session->login['gudang_id'];

                        $c->addFilter("state='active'");
                        $c->addFilter("cabang_id=" . $pre__cabang_id);
                        $c->addFilter("gudang_id=" . $pre__gudang_id);

                    }
                    $tmpC = $c->lookupAll($id)->result();
                    // cekHere($this->db->last_query());

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
                                $jml_diperlukan = 0;
                                $jml_nambah = 0;
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
                                $msg = "Stok tidak cukup <br><red class='text-red'>$kode $nama</red><hr>stok tersedia saat ini $jml_now. <br> input Anda (" . $jml_nambah . ") Silahkan dikoreksi.";
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
                            // cekHere($this->db->last_query());
                            //  endregion update locker active


                            //  region locker hold
//                            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                            $array_hold_sebelumnya = $c->cekLoker($pre__cabang_id, $id, "hold", $this->session->login['id'], "0", $pre__gudang_id);
                            if (sizeof($array_hold_sebelumnya) > 0) {
                                $where = array(
                                    "id" => $array_hold_sebelumnya['id'],
                                );
                                $data_hold = array(
                                    "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                );
                                $c->updateData($where, $data_hold);
//                                cekHere($this->db->last_query());
                            }
                            else {
                                $data_hold = array(
                                    "jenis" => "produk",
                                    "produk_id" => $id,
                                    "nama" => $nama,
                                    "satuan" => $row->satuan,
                                    "state" => "hold",
                                    "jumlah" => $jml_nambah,
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
//                                    "gudang_id" => $this->session->login['gudang_id'],
//                                    "cabang_id" => $this->session->login['cabang_id'],
                                    "gudang_id" => $pre__gudang_id,
                                    "cabang_id" => $pre__cabang_id,
                                );
                                $c->addData($data_hold);
//                                cekHere($this->db->last_query());
                            }
                            //  endregion locker hold
//                            mati_disini(__LINE__);
                            $this->db->trans_complete() or die("Gagal bro");

                            $tmpJml = $jml_diperlukan;

                        }
                    }
                    else {
                        mati_disini("stok " . $row->nama . " tidak cukup/tidak tersedia.");
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
                            // showLast_query("merah");
                            //                            arrPrint($tmpSr);
                            foreach ($tmpSr as $srSpec) {
                                // arrPrint($srSpec);
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
                                // cekMerah("pID: $id ::: persen: $discountPersen ::: qty: $discountQty");
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
                        "jml_used" => 0,
                    );

                    if (sizeof($priceMainConfig) > 0) {
                        if (isset($priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']])) {
                            $priceConfig = $priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']];
                            cekUngu("masuk disini...");
                        }
                    }

                    // cekBiru(__LINE__);
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
                                        // cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        // cekBiru("sini " . __LINE__);
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
                                    // cekBiru("ambil gerbang key -> $srcSpec");
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

                    $tmp['jml_used'] = isset($arrUsedProjectSpk[$id]['jml_used']) ? $arrUsedProjectSpk[$id]['jml_used'] : 0;
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
//                    cekBiru("ada id $id tapi tidak ada cCode items");
//                    matiHere("MASUK SINI KAN");
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

                        $spkNo = $_SESSION[$cCode]['main']['pihakProjekNoSpk'];
                        $sub_fase_id = $_SESSION[$cCode]['items'][$id]['pihakProjekWorkOrderSubID'];
                        $gudang_project = $_SESSION[$cCode]['items'][$id]['pihakProjekWorkorderSubGudangID'];
                        $ppid = $id;
                        $user_input_jml = trim($_GET['newQty']);
//                        cekMerah("spkNo: $spkNo");
//                        cekMerah("sub_fase_id: $sub_fase_id");
//                        cekMerah("gudang_project: $gudang_project");
//                        cekMerah("ppid: $ppid");
                        $sisa_kekurangan = $this->getDistributionSummary($spkNo, $sub_fase_id, $ppid, $gudang_project);

                        // cekMerah("sisa_kekurangan");
                        // arrPrint($sisa_kekurangan);

                        $sisa_ = $sisa_kekurangan[$ppid]["sisa"];
                        $terkirim_ = $sisa_kekurangan[$ppid]["terkirim"];
                        $permintaan_ = $sisa_kekurangan[$ppid]["permintaan"];
                        $intransit_ = $sisa_kekurangan[$ppid]["intransit"];

                        // cekMerah("permintaan_: $permintaan_");
                        // cekMerah("terkirim_: $terkirim_");
                        // cekMerah("sisa_: $sisa_");
                        // cekMerah("Input_Now: $user_input_jml");
//die(__LINE__);
                        if ($user_input_jml > $sisa_) {
                            matiHere("Anda hanya boleh input ($sisa_). Anda saat ini input (" . $user_input_jml . ").<br>Kebutuhan (" . $permintaan_ . ") || sudah distribusi: (" . $terkirim_ . ") <br> Jadi Anda hanya boleh input ($sisa_) sisanya<br>LINE: " . __LINE__);
                        }

                        $_SESSION[$cCode]['items'][$id]['jml'] = $user_input_jml;
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
                    }
                }
                $tmp['keterangan'] = $keterangan;
                $tmp['static_keterangan'] = $static_keterangan;
                $tmp['jml_used'] = isset($arrUsedProjectSpk[$id]['jml_used']) ? $arrUsedProjectSpk[$id]['jml_used'] : 0;

                //----------------------------------------
                $_SESSION[$cCode]['items'][$produk_id] = $tmp;
            }
        }
        else {
            // cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
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

    public function multiSelect()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $items = $_GET['items'];

        $arrItems = isset($_GET['items']) ? unserialize(base64_decode($items)) : array();
        // id_produk => qty

        $arrTrID = isset($_GET['trs']) ? unserialize(base64_decode($_GET['trs'])) : array();

        $arrMain = isset($_GET['main']) ? unserialize(base64_decode($_GET['main'])) : array();

        $cCode = "_TR_" . $this->jenisTr;
        $toko_id = my_toko_id();

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();

        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {
                $tmpB = $b->lookupByID($id)->result();
//                cekHere($this->db->last_query());
//                arrPrint($tmpB);

                //-----------------------------------------------
                if (!isset($_SESSION[$cCode]['items2'][$id])) {
                    $_SESSION[$cCode]['items2'][$id] = array();
                }
//                $arrDataTambahan = array(
//                    "outdoor" => array(
//                        "outdoor_id" => "outdoor_nama",
//                    ),
//                    "indoor" => array(
//                        "indoor_id_1" => "indoor_nama_1",
//                        "indoor_id_2" => "indoor_nama_2",
//                        "indoor_id_3" => "indoor_nama_3",
//                        "indoor_id_4" => "indoor_nama_4",
//                    ),
//                    "heater" => array(
//                        "heater_id" => "heater_nama",
//                    ),
//                    "part" => array(
//                        "produk_part_id_1" => "produk_part_nama_1",
//                        "produk_part_id_2" => "produk_part_nama_2",
//                        "produk_part_id_3" => "produk_part_nama_3",
//                    ),
//                );
                //-----------------------------------------------


                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $rows = $row;
                        $produk_jenis = $rows->kategori_nama;
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                            // cekMerah("masuk locker config");

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
                                        // cekHere($this->db->last_query());
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
                                mati_disini("stok " . $row->nama . " tidak cukup/tidak tersedia.");
                            }

                        }

                        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "max_jml_order" => $tmpJml,
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
                                $h->addFilter("toko_id=" . $toko_id);
                                $tmpH = $h->lookupAll($id)->result();
                                // cekMerah($this->db->last_query());
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
                                            // cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            // cekKuning("$val2 direplace dengan NOL");
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

                        $tmp = $_SESSION[$cCode]['items'][$id];
                        // memasukkan kolom sku ke items2
                        // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");
                        $arrCat = array();
                        $arrCode = array();
                        if ($produk_jenis == "unit") {
                            foreach ($arrDataTambahan as $cat => $catSpec) {
                                foreach ($catSpec as $dkey => $dval) {
                                    if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                        $_SESSION[$cCode]['items2'][$id][$rows->$dval] = array();
                                        if (!isset($arrCat[$cat])) {
                                            $arrCat[$cat] = 0;
                                        }
                                        $arrCat[$cat] += 1;
                                        if (!isset($arrCode[$rows->$dval])) {
                                            $arrCode[$rows->$dval] = 0;
                                        }
                                        $arrCode[$rows->$dval] += 1;
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
                                $_SESSION[$cCode]['items2'][$id][$d_kode] = array();
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
                            }
                        }
                        $tmp['keterangan'] = $keterangan;
                        $tmp['static_keterangan'] = $static_keterangan;
                        //----------------------------------------
                        $_SESSION[$cCode]['items'][$id] = $tmp;


                    }

                    if (sizeof($_SESSION[$cCode]['items']) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;

                        /*
                         * akumulasi item ke main
                         * */
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        }
                    }

                }
                else {
                    // cekMerah("tidak ada itemnya!");
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

    public function test()
    {
        matiHere(__FILE__);
    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $selectorLockerProcessFilters = isset($this->configUi[$this->jenisTr]['selectorLockerProcessFilters']) ? $this->configUi[$this->jenisTr]['selectorLockerProcessFilters'] : array();

        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
            // cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['items'][$id])) {
                // cekBiru("ada barang, cek lokernya");
                $this->db->trans_start();

                $mdlName = $lockerConfig['mdlName'];
                $this->load->model("Mdls/" . $mdlName);

                $c = new $mdlName();

                if (sizeof($selectorLockerProcessFilters) > 0) {
                    foreach ($selectorLockerProcessFilters as $fval) {
                        $fval_ex = explode("=", $fval);
                        $fval_ex_0 = $fval_ex[0];
                        $fval_ex_1 = $fval_ex[1];
                        $new_key = "pre__" . $fval_ex_0;
                        $$new_key = $_SESSION[$cCode]["main"][$fval_ex_1];
//                            cekHijau($pre__cabang_id);
//                            cekHijau($pre__gudang_id);
                    }
                }
                else {
                    $pre__cabang_id = $this->session->login['cabang_id'];
                    $pre__gudang_id = $this->session->login['gudang_id'];

                }

                $array_hold_sebelumnya = $c->cekLoker($pre__cabang_id, $id, "hold", $this->session->login['id'], "0", $pre__gudang_id);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($pre__cabang_id, $id, "active", "0", "0", $pre__gudang_id);
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
                // cekBiru("TIDAK ada barang, ga jadi cek loker");
            }
        }
        else {
            // cekBiru("TIDAK melibatkan session @" . __CLASS__);
        }

        //        die();
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
        //        if (sizeof($_SESSION[$cCode]['items']) < 1) {
        //            $_SESSION[$cCode] = null;
        //            unset($_SESSION[$cCode]);
        //        }

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
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        if (isset($_GET['mb'])) {
            echo "<script>";
            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?" . $f_selector . "selID=$id';";
            echo "top.load_shoppingcart();";
            echo "</script>";
        }
        else {
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
                "jenisTrTop" => $configUiJenis['steps'][1]['target'],
                "jenisTrName" => $configUiJenis['steps'][1]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";

            // echo "<script>";
            // echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?".$f_selector."selID=$id';";
            // echo "</script>";
        }

        //        echo "<script>";
        //        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        //        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart')";
        //        echo "</script>";
    }

    public function updateValues()
    {
        echo "---------------------------your input params needed------------------------------";
        arrprint($_POST);
        $cCode = "_TR_" . $this->jenisTr;
        //        $rawParam = $_POST['param'];
        //        arrPrint($rawParam);
        //        arrPrint($cCode);
        die("updating.............................. (will be available sooner or later)");
        //        $rawParam = $_GET['param'];
        //        $param = unserialize(base64_decode($rawParam));
        //        if (is_array($param) && sizeof($param) > 0) {
        //
        //        }
    }

    public function selectNoQty()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();


        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
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
                    $mdlName = $lockerConfig['mdlName'];

                    // cekMerah("masuk locker config $mdlName");

                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
                    //                    $c->addFilter("id='$id'");//==id locker
                    $c->addFilter("state='active'");
                    // $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    // $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                    $c->addFilter("toko_id=" . my_toko_id());


                    $tmpC = $c->lookupAll($id)->result();
                    // cekHere($this->db->last_query());

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
                            // cekHere($this->db->last_query());
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
                                // cekHere($this->db->last_query());
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
                        mati_disini("stok " . $row->nama . " tidak cukup/tidak tersedia.");
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
                        $dr->addFilter("toko_id=" . my_toko_id());
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
                            //                            cekBiru($this->db->last_query());
                            //                            arrPrint($tmpSr);
                            foreach ($tmpSr as $srSpec) {
                                // arrPrint($srSpec);
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
                                // cekMerah("pID: $id ::: persen: $discountPersen ::: qty: $discountQty");
                            }
                        }
                    }
                }


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                        "discount_persen" => isset($arrDiscount[$id]['persen']) ? $arrDiscount[$id]['persen'] : 0,
                        "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                    );


                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();

                        if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                            $h->addFilter("produk_id='$id'");
                            // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("toko_id=" . my_toko_id());
                        }
                        else {
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("toko_id=" . my_toko_id());
                        }

                        // cekKuning("masukkk pak eko");
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
                        //                        cekmerah($this->db->last_query());
                        //                        matiHere();
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {

                                    // cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        // cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        // cekBiru("sini");
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
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                //                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                $tmp[$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }
                        }
                    }

                    //===perhitungan subtotal
                    //                    $this->load->library("FieldCalculator");
                    //                    $cal = new FieldCalculator();


                    if ($subAmountConfig != null) {
                        //                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    //                    arrprint($tmp);die();
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {

                    if (isset($_GET['newQty'])) {
                        //                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['jml'] = $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id]['jml'] = $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }


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
            }

        }
        else {
            // cekMerah("tidak ada itemnya!");
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
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
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
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
        // echo "<script>";
        // echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "</script>";
    }

    public function selectProject()
    {
//        matiHere(__LINE__);
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $source_spk = isset($_GET['spk']) ? $_GET['spk'] : 0;
        $sub_spk = isset($_GET['sub_spk']) ? $_GET['sub_spk'] : 0;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $selectorSubSrcModel = isset($this->configUi[$this->jenisTr]['selectorSubSrcModel']) ? $this->configUi[$this->jenisTr]['selectorSubSrcModel'] : null;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");

        if (isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
            $_SESSION[$cCode]['items2_sum'] = array();
        }
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();
//        $arrDataTambahan = array(
//            "outdoor" => array(
//                "outdoor_id" => "outdoor_sku",
//            ),
//            "indoor" => array(
//                "indoor_id_1" => "indoor_sku_1",
//                "indoor_id_2" => "indoor_sku_2",
//                "indoor_id_3" => "indoor_sku_3",
//                "indoor_id_4" => "indoor_sku_4",
//            ),
//            "heater" => array(
//                "heater_id" => "heater_nama",
//            ),
//        );

        //ambil penggunaan dari sub_tasklist_komposisi
        $this->load->model("Mdls/MdlSubProgresTasklistKomposisi");
        $sptk = new MdlSubProgresTasklistKomposisi();
        $sptk->addFilter("no_spk='$source_spk'");
        $sptk->addFilter("progress_id='2'");
        $tmpSptk = $sptk->lookupAll()->result();

        // showLast_query("hitam");
        // arrPrint($tmpSptk);

        $arrUsedProjectSpk = array();
        if (!empty($tmpSptk)) {
            foreach ($tmpSptk as $k => $used_data) {
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["id"] = $used_data->produk_dasar_id;
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["nama"] = $used_data->produk_dasar_nama;
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["satuan"] = $used_data->satuan;
                if (!isset($arrUsedProjectSpk[$used_data->produk_dasar_id]["data"])) {
                    $arrUsedProjectSpk[$used_data->produk_dasar_id]["data"] = 0;
                }
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["data"]++;
                if (!isset($arrUsedProjectSpk[$used_data->produk_dasar_id]["jml_used"])) {
                    $arrUsedProjectSpk[$used_data->produk_dasar_id]["jml_used"] = 0;
                }
                $arrUsedProjectSpk[$used_data->produk_dasar_id]["jml_used"] += $used_data->jml * 1;
            }
        }

//        arrPrint($arrUsedProjectSpk);
//        matiHere();
        //ambil penggunaan dari sub_tasklist_komposisi

        $this->load->model("Mdls/MdlProduk2");
        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        cekUngu($selectorSrcModel);

        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $workOrderValidate = isset($this->configUi[$this->jenisTr]['workOrderValidate']) ? $this->configUi[$this->jenisTr]['workOrderValidate'] : array();
        $selectorSubFilters = isset($this->configUi[$this->jenisTr]['selectorSubFilters']) ? $this->configUi[$this->jenisTr]['selectorSubFilters'] : array();

        $tmpB = $b->lookupByID($id)->result();
        // cekHere(__LINE__);
//        cekHere($selectorModel);
//        cekHere($source_spk);
//        cekHere(__LINE__);
//        cekHere($selectorSubSrcModel);
        // showLast_query("biru");

//        arrPrint($selectorSrcModel);
//        matiHere($this->db->last_query());
//        $b->addFilter("fase_id='$id'");
//        $tmpB = $b->lookupAll()->result();
//        showLast_query("biru");
//        arrPrintPink($tmpB);
//        arrPrintHijau($workOrderValidate);
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
//                arrPrint($row);

//                //-------------------------
//                if (sizeof($workOrderValidate)>0){
//                    if (isset($workOrderValidate["enabled"]) && ($workOrderValidate["enabled"] == true)) {
//                        $validateRule = $workOrderValidate["validateRule"];
//                        foreach ($validateRule as $validateRuleSpec) {
//                            $fsFilter = $validateRuleSpec["filter"];
//                            $fsModel = $validateRuleSpec["model"];
//                            $fsWarningLabel = $validateRuleSpec["warningLabel"];
//                            $this->load->model("Mdls/$fsModel");
//                            $ffss = New $fsModel();
//                            makeFilter($fsFilter, (array)$row, $ffss);
//                            $ffssTmp = $ffss->lookupAll()->result();
////                            showLast_query("kuning");
////                            cekHere(count($ffssTmp));
//                            if (count($ffssTmp) == 0) {
//                                $msg = "Project " . $row->produk_nama . ", ";
//                                mati_disini($msg . $fsWarningLabel);
//                            }
//                        }
//                    }
//                }
//                else{
//                    cekMerah("tidak ada work ordernyaaa");
//                    matiHere("tidak ada work ordernyaaa <br>" . __LINE__);
//                }
//                //-------------------------

                $workOrderID = isset($row->id) && strlen($row->id) > 0 ? $row->id : 0;
                $workOrderNama = isset($row->nama) && strlen($row->nama) > 0 ? $row->nama : "";
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                //DETAIL WORK ORDER/KOMPOSISI-----------------------
                if ($selectorSubSrcModel != null) {
                    if ($row->produk_id > 0) {
                        $this->load->model("Mdls/$selectorModel");
                        $tp = New $selectorModel();
                        $tp->addFilter("produk_id='" . $row->produk_id . "'");
                        $tp->addFilter("sub_fase_id='$id'");
                        $tp->addFilter("no_spk='$source_spk'");
                        $tpTmp = $tp->lookupAll()->result();
                        // cekMerah(__LINE__);
                        // showLast_query("merah");
                        // cekHere(count($tpTmp));
                        $employee_id = $tpTmp[0]->employee_id;
                    }

                    $this->load->model("Mdls/$selectorSubSrcModel");
                    $bb = New $selectorSubSrcModel();
                    $bb->addFilter("sub_fase_id='$id'");
                    $bb->addFilter("employee_id='$employee_id'");
                    $bb->addFilter("no_spk='$source_spk'");
                    if (sizeof($selectorSubFilters) > 0) {
                        makeFilter($selectorSubFilters, $_SESSION[$cCode]["main"], $bb);
                    }
                    $tmpBB = $bb->lookupAll()->result();
                    cekUngu("LINE: " . __LINE__);
                    // showLast_query("merah");
                    // cekHere(count($tmpBB));
//                    arrPrint($tmpBB);
//                    mati_disini(__LINE__ . " ---- " . count($tmpBB));
                    if (sizeof($tmpBB) > 0) {
                        foreach ($tmpBB as $rowB) {
                            // cekMerah("sini boy");
                            // arrPrint($rowB);
                            $workorder_id = $rowB->fase_id;
                            $workorder_nama = isset($rowB->fase_nama) ? $rowB->fase_nama : "";
                            $produk_id = $rowB->produk_id;
                            $produk_nama = $rowB->produk_nama;
                            $idp = $rowB->produk_dasar_id;
                            $nama = $rowB->produk_dasar_nama;
                            $harga = $rowB->harga;
                            $nilai = $rowB->nilai;
                            $jenis = $rowB->jenis;
                            $qty_debet = $rowB->qty_debet;
                            $qty_kredit = $rowB->qty_kredit;
                            $qty_saldo = $rowB->qty_saldo;
                            $no_spk = $rowB->no_spk;
                            $satuan = isset($rowB->satuan) && strlen($rowB->satuan) > 0 ? $rowB->satuan : "n/a";

                            $ambil_angka_depan_spk = explode("/", $source_spk)[0];
                            $gudang_project = "$produk_id" . "$workorder_id" . "$id" . "$ambil_angka_depan_spk";

                            $tmpJml_wo = $rowB->jml;
                            $tmpJml = 0;

                            // cekMerah("source_spk: $source_spk");
                            // cekMerah("id: $id");
                            // cekMerah("idp: $idp");
                            // cekMerah("gudang_project: $gudang_project");

                            $sisa_kekurangan = $this->getDistributionSummary($source_spk, $id, $idp, $gudang_project);

                            $sisa_ = $sisa_kekurangan[$idp]["sisa"];
                            $terkirim_ = $sisa_kekurangan[$idp]["terkirim"];
                            $permintaan_ = $sisa_kekurangan[$idp]["permintaan"];

                            // cekMerah("sini boy $id");
                            // arrPrint($sisa_kekurangan);

//                            $tmpJml = $sisa_;

                            if ($jenis == "produk" || $jenis == "item_komposit") {
                                if (!array_key_exists($idp, $_SESSION[$cCode]['items'])) {
                                    $tmp = array(
                                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                        "id" => $idp,// id bahan baku (produk)
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
                                        "jml_used" => isset($arrUsedProjectSpk[$idp]['jml_used']) ? $arrUsedProjectSpk[$idp]['jml_used'] : 0,// sisa yang belum diambil
                                        "satuan" => isset($arrUsedProjectSpk[$idp]['satuan']) ? $arrUsedProjectSpk[$idp]['satuan'] : "-",// sisa yang belum diambil
                                    );

                                    foreach ($fieldSrcs as $key => $src) {
                                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                                    }
                                    if ($subAmountConfig != null) {
                                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$idp], 0);
                                    }
                                    else {
                                        $tmp['subtotal'] = 0;
                                    }

                                    $tmp['jml_used'] = isset($arrUsedProjectSpk[$idp]['jml_used']) ? $arrUsedProjectSpk[$idp]['jml_used'] : 0;

                                    $_SESSION[$cCode]['items'][$idp] = $tmp;

                                    $pakai_ini = 1;
                                    if ($pakai_ini == 1) {
                                        if (!isset($_SESSION[$cCode]['items2'][$idp])) {
                                            $_SESSION[$cCode]['items2'][$idp] = array();
                                        }

                                        $tmp = $_SESSION[$cCode]['items'][$idp];
                                        //----------------------------------------
                                        // memasukkan kolom sku ke items2
                                        $arrCat = array();
                                        $arrCode = array();
                                        $bbp = new MdlProduk2();
                                        $tmpBbp = $bbp->lookupByID($idp)->result();
//                                        arrPrint($tmpBbp);

                                        foreach ($tmpBbp as $roww) {
                                            $rows = $roww;
                                            $produk_jenis = $rows->kategori_nama;
                                            $produk_nama = $rows->nama;
                                            if ($produk_jenis == "unit") {
                                                foreach ($arrDataTambahan as $cat => $catSpec) {
                                                    foreach ($catSpec as $dkey => $dval) {
                                                        if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                                            $_SESSION[$cCode]['items2'][$idp][$rows->$dval] = array();
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

//                                                arrPrintWebs($produk_jenis . " | " . $idp);
                                                $jml_serial = $rows->jml_serial;
                                                $tmp['jml_serial'] = $jml_serial;
                                                $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                                                if ($jml_serial * 1 == 1) {
                                                    $d_kode = $rows->kode;
                                                    $_SESSION[$cCode]['items2'][$idp][$d_kode] = array();
                                                    $arrCode[$d_kode] = 1;
                                                }
                                            }
                                            else {
                                                $jml_serial = $rows->jml_serial;
                                                $tmp['jml_serial'] = $jml_serial;
                                                $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                                                if ($jml_serial * 1 == 1) {
                                                    $d_kode = $rows->kode;
                                                    $_SESSION[$cCode]['items2'][$idp][$d_kode] = array();
                                                    $arrCode[$d_kode] = 1;
                                                }
                                            }
                                        }

                                        $keterangan = "";
                                        $static_keterangan = "";
                                        if (sizeof($arrCat) > 0) {
                                            foreach ($arrCat as $kcat => $vcat) {
                                                $new_vcat = $vcat * $_SESSION[$cCode]['items'][$idp]["jml"];
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
                                                $new_vcat = $vcat * $_SESSION[$cCode]['items'][$idp]["jml"];
                                                $tmp[$kcat] = $new_vcat;
                                            }
                                        }
                                        $tmp['keterangan'] = $keterangan;
                                        $tmp['static_keterangan'] = $static_keterangan;
                                        $tmp['jml_used'] = isset($arrUsedProjectSpk[$idp]['jml_used']) ? $arrUsedProjectSpk[$idp]['jml_used'] : 0;

                                        //----------------------------------------
                                        if ($sisa_ > 0) {
                                            $_SESSION[$cCode]['items'][$idp] = $tmp;
                                        }
                                        else {
                                            if ($cCode == "_TR_9833") {
                                                $_SESSION[$cCode]['items'][$idp] = $tmp;
                                            }
                                            else {
                                                unset($_SESSION[$cCode]['items'][$idp]);
                                                unset($_SESSION[$cCode]['items2'][$idp]);
                                                unset($_SESSION[$cCode]['tableIn_detail_values'][$idp]);
                                                unset($_SESSION[$cCode]['tableIn_detail_values2'][$idp]);
                                            }
                                        }
                                    }
                                }
                                else {
                                    if (sizeof($itemNumLabels) > 0) {
                                        echo("iterating subNums... *** ");
                                        echo("LINE " . __LINE__);
                                        foreach ($itemNumLabels as $key => $label) {
                                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                                $newValue = $_GET[$key];
                                                $tmp[$key] = $newValue;
                                                $_SESSION[$cCode]['items'][$idp][$key] = $newValue;
                                                echo "replacing value for $key with " . $newValue . "<br>";
                                            }
                                        }
                                        if (sizeof($_SESSION[$cCode]['items'][$idp][$key]) > 0) {
                                            if (!isset($_SESSION[$cCode]['items'][$idp]['nilai_untung'])) {
                                                $_SESSION[$cCode]['items'][$idp]['nilai_untung'] = 0;
                                            }
                                            if (!isset($_SESSION[$cCode]['items'][$idp]['nilai_rugi'])) {
                                                $_SESSION[$cCode]['items'][$idp]['nilai_rugi'] = 0;
                                            }
                                            $_SESSION[$cCode]['items'][$idp]['nilai_untung'] = ($_SESSION[$cCode]['items'][$idp]['harga'] - $_SESSION[$cCode]['items'][$idp]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$idp]['harga'] - $_SESSION[$cCode]['items'][$idp]['harga_sisa']) : 0;
                                            $_SESSION[$cCode]['items'][$idp]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$idp]['harga'] - $_SESSION[$cCode]['items'][$idp]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$idp]['harga_sisa'] - $_SESSION[$cCode]['items'][$idp]['harga']);
                                        }
                                        if ($subAmountConfig != null) {
                                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$idp], $_SESSION[$cCode]['items'][$id], 0);
                                        }
                                        else {
                                            $tmp['subtotal'] = 0;
                                        }
                                        $_SESSION[$cCode]['items'][$idp]['subtotal'] = $tmp['subtotal'];
                                    }
                                }
                            }
                        }
                    }
                }
                else {
                    // cekMerah("tidak ada sub_src_model");
                }

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
//                arrPrint($row);
//                mati_disini(__LINE__);
                $_SESSION[$cCode]['main']['projectID'] = $row->produk_id;
                $_SESSION[$cCode]['main']['projectName'] = $row->produk_nama;
                $_SESSION[$cCode]['main']['projectCabangID'] = $tpTmp[0]->cabang_id;
                $_SESSION[$cCode]['main']['pihakProjekID'] = $row->produk_id;
                $_SESSION[$cCode]['main']['pihakProjekNoSpk'] = $source_spk;
                $_SESSION[$cCode]['main']['pihakProjekNoSubSpk'] = $sub_spk;
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
                $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangID'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama, $source_spk)["gudang_id"];
                $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangName'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama, $source_spk)["gudang_nama"];
                $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangNama'] = getDefaultWarehouseProjectSubWorkorder($row->produk_id, $workorder_id, $workOrderID, $workOrderNama, $source_spk)["gudang_nama"];
                //-------
                //endregion

                $pakai_ini = 0;
                if ($pakai_ini) {
                    $workorder_id = $row->fase_id;
                    $produk_id = $row->produk_id;
                    $produk_nama = $row->produk_nama;
                    $idp = $row->produk_dasar_id;
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
                        // cekMerah("masuk locker config");
                        $mdlName = $lockerConfig['mdlName'];
                        $this->load->model("Mdls/" . $mdlName);
                        $c = new $mdlName();
                        $c->addFilter("produk_id='$idp'");
                        $c->addFilter("state='active'");
                        $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                        $tmpC = $c->lookupAll($idp)->result();

                        if (sizeof($tmpC) > 0) {
                            foreach ($tmpC as $row) {
                                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                $nama = $row->nama;
                                $jml_now = $row->jumlah;
                                if (!array_key_exists($idp, $_SESSION[$cCode]['items'])) {
                                    $jml_sudah_diambil = 0;
                                    $jml_diperlukan = 1;
                                    $jml_nambah = 1;
                                }
                                else {
                                    if (isset($_GET['newQty'])) {
                                        $jml_sudah_diambil = $_SESSION[$cCode]['items'][$idp]['jml'];
                                        $jml_diperlukan = $_GET['newQty'];
                                        $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                    }
                                    else {
                                        $jml_sudah_diambil = $_SESSION[$cCode]['items'][$idp]['jml'];
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
                                $c = new $mdlName();
                                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $idp, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
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
                                    // cekHere($this->db->last_query());
                                }
                                else {
                                    $data_hold = array(
                                        "jenis" => "produk",
                                        "cabang_id" => $this->session->login['cabang_id'],
                                        "produk_id" => $idp,
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
                            mati_disini("stok " . $row->nama . " tidak cukup/tidak tersedia.");
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
                                // showLast_query("kuning");
                                // cekHere(count($ffssTmp));
                                if (count($ffssTmp) == 0) {
                                    mati_disini($fsWarningLabel);
                                }
                            }
                        }
                    }
                    //-------------------------

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
                                // cekMerah($this->db->last_query());
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
        }
        else {

            $msg = "Daftar bahan baku yang diperlukan belum diatur. Segera hubungi admin.";
            mati_disini($msg);

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
        $vg->buildValue($this->jenisTr, 0, $initMasterValues, $this->modul);

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    private function getDistributionSummary($spkNo, $sub_fase_id, $ppid, $gudang_project)
    {
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $tracker = new MdlProjectKomposisiWorkorderSub();

        $filters = ["no_spk='$spkNo'", "jenis='produk'", "produk_dasar_id='$ppid'"];

        $tracker->setFilters($filters);
        $initialRequests = $tracker->lookupAll()->result_array();
        // showLast_query("hitam");
//        matiHere(__LINE__);
        $this->load->model("Mdls/MdlLockerStock");
        $c = new MdlLockerStock();
        $c->addFilter("produk_id='$ppid'");
        $c->addFilter("gudang_id=" . $gudang_project);
        $distributed = $c->lookupAll()->result_array();

        $summary = [];
        foreach ($initialRequests as $req) {
            $key = $req['produk_dasar_id'];
            $summary[$key] = [
                'nama' => $req['produk_dasar_nama'],
                'permintaan' => (float)$req['jml'],
                'terkirim' => 0,
                'satuan' => $req['satuan']
            ];
        }
        foreach ($distributed as $dist) {
            $key = $dist['produk_id'];
            if (isset($summary[$key])) {
                $state = $dist["state"];
                if (!isset($summary[$key][$state])) {
                    $summary[$key][$state] = 0;
                }
                if (!isset($summary[$key]["terkirim"])) {
                    $summary[$key]["terkirim"] = 0;
                }
                switch ($state) {
                    case "intransit":
                        $summary[$key][$state] += (float)$dist['jumlah'];
                        break;
                    case "active":
                        $summary[$key]['terkirim'] += (float)$dist['jumlah'];
                        break;
                    case "sold":
                        $summary[$key][$state] += (float)$dist['jumlah'];
                        break;

                }

            }
        }
        foreach ($summary as &$item) {
            $item['sisa'] = $item['permintaan'] - $item['terkirim'] - $item['intransit'];
        }
        return $summary;
    }

}