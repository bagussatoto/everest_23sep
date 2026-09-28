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

        $id = $produk_id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        //-----------------------------------------------
        if (!isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = array();
        }
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
            "part" => array(
                "produk_part_id_1" => "produk_part_nama_1",
                "produk_part_id_2" => "produk_part_nama_2",
                "produk_part_id_3" => "produk_part_nama_3",
            ),
        );
        //-----------------------------------------------
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();
        $shoppingCartCheckerItems = isset($this->configUi[$this->jenisTr]['shoppingCartCheckerItems']) ? $this->configUi[$this->jenisTr]['shoppingCartCheckerItems'] : false;

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


        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("lime");
        // matiHere(__LINE__ . " " .__METHOD__);

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $produk_jenis = $rows->kategori_nama;
                $produk_nama = $rows->nama;
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

                        }
                    }
                    else {
                        $pre__cabang_id = $this->session->login['cabang_id'];
                        $pre__gudang_id = $this->session->login['gudang_id'];

                        $c->addFilter("state='active'");
                        $c->addFilter("cabang_id=" . $pre__cabang_id);
                        $c->addFilter("gudang_id=" . $pre__gudang_id);

                    }
                    $tmpC = $c->lookupAll($id)->result();

                    if (sizeof($tmpC) > 0) {

                        $kode = $row->kode;
                        foreach ($tmpC as $row) {
                            $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                            $nama = $row->nama;
                            $nama = htmlspecialchars($nama);

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
                                $msg = "Stok tidak cukup <br><red class='text-red'>$kode $nama</red><hr>, stok tersedia saat ini $jml_now $satuan. Silahkan dikoreksi.";
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
                                    "gudang_id" => $pre__gudang_id,
                                    "cabang_id" => $pre__cabang_id,
                                );
                                $c->addData($data_hold);
//                                cekHere($this->db->last_query());
                            }
                            //  endregion locker hold

                            $this->db->trans_complete() or die("Gagal bro");

                            $tmpJml = $jml_diperlukan;

                        }
                    }
                    else {
                        $nama = htmlspecialchars($row->nama);
                        mati_disini("tidak ditemukan item " . $nama . " di locker stock.");
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
                        $produkQty = isset($_GET['jml']) ? $_GET['jml'] : $tmpJml;
                        foreach ($tmpDr as $drSpec) {
                            $this->load->model("Mdls/" . $mdlNameSource);
                            $sr = new $mdlNameSource();
                            $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                            $sr->addFilter("status='1'");
                            $tmpSr = $sr->lookupAll($id)->result();
                            showLast_query("merah");
                            foreach ($tmpSr as $srSpec) {
//                                arrPrint($srSpec);
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

                //----------------------------------------
                if($shoppingCartCheckerItems == true){
                    checkerProdukData($row, $arrDataTambahan);
                }
                //----------------------------------------


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

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
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

                    // arrPrint($itemNumLabels);
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

                /* ----------------------------------------------------------
                 * diskon-diskonan-grosir
                 * ----------------------------------------------------------*/
                //----------------------------------------

                $tmp = $_SESSION[$cCode]['items'][$id];
                // memasukkan kolom sku ke items2
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
                    $_SESSION[$cCode]['items2'][$produk_id][$rows->kode] = array();
                    $arrCat["kode"] = 1;
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
            "stepNumber" => $stepNum,
            "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
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
                $produk_id = $id;
                $tmpB = $b->lookupByID($id)->result();
                cekHere($this->db->last_query());
                arrPrint($tmpB);

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $rows = $row;
                        $produk_jenis = $rows->kategori_nama;
                        $produk_nama = $rows->nama;
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $produk_nama = htmlspecialchars($produk_nama);
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
                                    $nama = htmlspecialchars($nama);

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
                                $nama = htmlspecialchars($row->nama);
                                mati_disini("tidak ditemukan item " . $nama . " di locker stock.");
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
                                $h->addFilter("toko_id=" . $toko_id);
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
                            //----------------------------------------
                            $_SESSION[$cCode]['items'][$produk_id] = $tmp;
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
                        $_SESSION[$cCode]['out_master']['harga'] = 0;

                        /*
                         * akumulasi item ke main
                         * */
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                            $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
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
            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }
        if (isset($_GET['singleRefID']) && strlen($_GET['singleRefID']) > 0) {
            $_SESSION[$cCode]['main']['singleReference'] = $_GET['singleRefID'];
            $_SESSION[$cCode]['out_master']['singleReference'] = $_GET['singleRefID'];
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
            cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['items'][$id])) {
                cekBiru("ada barang, cek lokernya");
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
                cekBiru("TIDAK ada barang, ga jadi cek loker");
            }
        }
        else {
            cekBiru("TIDAK melibatkan session @" . __CLASS__);
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

        $f_selector = "";
        if (isset($_GET['selector'])) {
            $f_selector = "selector&";
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        if (isset($_GET['mb'])) {
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
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
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

                    cekMerah("masuk locker config $mdlName");

                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
                    //                    $c->addFilter("id='$id'");//==id locker
                    $c->addFilter("state='active'");
                    // $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    // $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                    $c->addFilter("toko_id=" . my_toko_id());


                    $tmpC = $c->lookupAll($id)->result();
                    cekHere($this->db->last_query());

                    //                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                    if (sizeof($tmpC) > 0) {
                        // arrPrint($tmpC);
                        // arrPrint($row);
                        $kode = $row->kode;
                        foreach ($tmpC as $row) {
                            $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                            $nama = $row->nama;
                            $nama = htmlspecialchars($nama);

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
                        $nama = htmlspecialchars($row->nama);
                        mati_disini("tidak ditemukan item " . $nama . " di locker stock.");
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

                        cekKuning("masukkk pak eko");
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

                                    cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        cekBiru("sini");
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
            "stepNumber" => 1,
            "stepCode" => $configUiJenis['steps'][1]['target'],
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


    public function selectProduksiProses()
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

        $arrGate = array(
//            "main",
            "items",
//            "tableIn_master",
            "tableIn_detail",
        );
        foreach ($arrGate as $gate) {

            $_SESSION[$cCode][$gate] = array();
        }

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
//arrPrint($arrItems);
//mati_disini(__LINE__);
        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {
                if ($jmlParam > 0) {

                    $tmpB = $b->lookupByID($id)->result();
//                cekHere($this->db->last_query());
//                arrPrint($tmpB);

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
                                $c->addFilter("cabang_id=" . my_cabang_id());
                                $c->addFilter("gudang_id=" . my_gudang_id());
                                $tmpC = $c->lookupAll($id)->result();
                                cekHere($this->db->last_query());


                                if (sizeof($tmpC) > 0) {
//                                arrPrint($tmpC);
                                    foreach ($tmpC as $row) {
                                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                        $nama = $row->nama;
                                        $nama = htmlspecialchars($nama);

                                        $jml_now = $row->jumlah;
                                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                            $jml_sudah_diambil = 0;
//                                        $jml_diperlukan = 1;
//                                        $jml_nambah = 1;
                                            $jml_diperlukan = $tmpJml;
                                            $jml_nambah = $tmpJml;
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
                                    $nama = htmlspecialchars($row->nama);
                                    mati_disini("tidak ditemukan item " . $nama . " di locker stock.");
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
                                    $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
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


                                if (sizeof($arrMain) > 0) {
                                    foreach ($arrMain as $key => $val) {
                                        $tmp[$key] = $val;
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
                            $_SESSION[$cCode]['out_master']['harga'] = 0;

                            /*
                             * akumulasi item ke main
                             * */
                            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                                $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                            }
                        }

                    }
                    else {
//                    cekMerah("tidak ada itemnya!");
//                    die();
                    }

                }

            }
        }

        if (sizeof($arrTrID) > 0) {
            $_SESSION[$cCode]['main']['references'] = $arrTrID;
//            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }
        if (isset($_GET['singleRefID']) && strlen($_GET['singleRefID']) > 0) {
            $_SESSION[$cCode]['main']['singleReference'] = $_GET['singleRefID'];
            $_SESSION[$cCode]['out_master']['singleReference'] = $_GET['singleRefID'];
        }


        if (sizeof($arrMain) > 0) {
            foreach ($arrMain as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }
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
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        $linkBack = MODUL_PATH . "Create/index/" . $this->jenisTr;
        echo "<script>";
        echo "window.location.href='$linkBack'";
//        echo "  if(top.document.getElementById('shopping_cart')){";
//        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
//        echo "  }";
//
        echo "</script>";


    }


    public function multiSelectBi()
    {

//         mati_disini();
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $items = isset($_GET['items']) ? $_GET['items'] : "";
//        $getSupplierID = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null;

        $arrItems = isset($_GET['items']) ? unserialize(base64_decode($items)) : (isset($_POST['items']) ? json_decode(base64_decode($_POST['items'])) : array());
        $getSupplierID = $supplier = (isset($_POST['supplier']) ? ($_POST['supplier']) : $_GET['supplier']);

        $cCode = $this->cCode;
        $jenisTr = $this->jenisTr;

        // resetor session items
        if (isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = NULL;
            unset($_SESSION[$cCode]['items']);
            $_SESSION[$cCode]['items'] = array();
        }


        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again*");
        $ppnCekDefaultValue = isset($this->configUi[$this->jenisTr]['ppnCekDefaultValue']) ? $this->configUi[$this->jenisTr]['ppnCekDefaultValue'] : array();
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();

        $id = "";
        if (sizeof($arrItems) > 0) {

            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
//                showLast_query("biru");
                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $rows = $row;
                        $produk_jenis = $rows->kategori_nama;
                        $produk_nama = $rows->nama;
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $produk_nama = htmlspecialchars($produk_nama);
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
                            if (sizeof($tmpC) > 0) {
                                foreach ($tmpC as $row) {
                                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                    $nama = $row->nama;
                                    $nama = htmlspecialchars($nama);

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
                                    //                                    cekHere($this->db->last_query());
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
                                        //                                        cekHere($this->db->last_query());
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
                                $nama = htmlspecialchars($row->nama);
                                mati_disini("tidak ditemukan item " . $nama . " di locker stock.");
                            }

                        }

                        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, (isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array()))) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "subtotal" => 0,
                                "harga_old" => 0,
                                "ppn" => isset($_SESSION[$cCode]['main']['ppnPersenCheck']) ? $_SESSION[$cCode]['main']['ppnPersenCheck'] : 10,
                                "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                            );

                            if (sizeof($priceConfig) > 0) {
                                $mdlName = $priceConfig['model'];
                                $this->load->model("Mdls/" . $mdlName);
                                $h = new $mdlName();

                                if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                                    $h->addFilter("produk_id='$id'");
                                    $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                                }
                                else {
                                    $h->addFilter("produk_id='$id'");
                                    $h->addFilter("status='1'");
                                    $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                    $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
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

                                if (sizeof($tmpH) > 0) {
                                    $rawPrices = array();
                                    foreach ($tmpH as $hSpec) {
                                        foreach ($priceConfig['key_label'] as $key => $val) {
                                            if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter']) {
                                                $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                            }
                                            else {
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
                                        $tmp['harga_old'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    }
                                }

                            }
                            foreach ($fieldSrcs as $key => $src) {

                                $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                            }
                            if (sizeof($itemNumLabels) > 0) {

                                foreach ($itemNumLabels as $key => $label) {

                                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                        $newValue = $_GET[$key];
                                        $tmp[$key] = $newValue;

                                        $tmp[$key] = $newValue;

                                    }
                                    else {
                                        $nilai = isset($tmp[$key]) ? $tmp[$key] : 0;

                                    }
                                }
                            }

                            if ($subAmountConfig != null) {

                                $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                            }
                            else {

                                $tmp["subtotal"] = 0;
                            }

                            $_SESSION[$cCode]['items'][$id] = $tmp;


                            //----------------------------------------
                            $tmp = $_SESSION[$cCode]['items'][$id];
                            if (!isset($_SESSION[$cCode]['items2'][$id])) {
                                $_SESSION[$cCode]['items2'][$id] = array();
                            }
//                            $arrDataTambahan = array(
//                                "outdoor" => array(
//                                    "outdoor_id" => "outdoor_nama",
//                                ),
//                                "indoor" => array(
//                                    "indoor_id_1" => "indoor_nama_1",
//                                    "indoor_id_2" => "indoor_nama_2",
//                                    "indoor_id_3" => "indoor_nama_3",
//                                    "indoor_id_4" => "indoor_nama_4",
//                                ),
//                                "heater" => array(
//                                    "heater_id" => "heater_nama",
//                                ),
//                            );

                            // memasukkan kolom sku ke items2
                            $arrCat = array();
                            $arrCode = array();
                            if ($produk_jenis == "unit") {
                                foreach ($arrDataTambahan as $cat => $catSpec) {
                                    foreach ($catSpec as $dkey => $dval) {
                                        if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                            $_SESSION[$cCode]['items2'][$id][$rows->$dval] = array();
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

        $result = array();
        if (count($ppnCekDefaultValue) > 0) {
            if ($ppnCekDefaultValue["enabled"] == true) {
                $ppnPersenCheck = isset($_SESSION[$cCode]["main"]["ppnPersenCheck"]) ? $_SESSION[$cCode]["main"]["ppnPersenCheck"] : $ppnCekDefaultValue["ppnPersenCheck"];
                $keyDetail = $ppnCekDefaultValue["keyDetail"];
                $selsMainTarget = $keyDetail['srcMain'];
                $selsItemsTarget = $keyDetail['srcItem'];
                $mainOverwrite = $keyDetail['overWriteMain'];


                if (isset($_POST['items'])) {

                    $result["status_a"] = 1;
                    $result["url_a"] = MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn=$ppnPersenCheck&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite";
                    $result["frame_a"] = "result";
                }
                else {
                    echo "<script>";
                    echo "top.$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn=$ppnPersenCheck&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite" . "');";
                    echo "</script>\n";

                }
            }
        }

        if ($getSupplierID > 0) {
            if (isset($_POST['items'])) {

                $result["status_b"] = 1;
                $result["url_b"] = MODUL_PATH . "_processPihak/select/$jenisTr/MdlSupplier?id=$getSupplierID";
                $result["frame_b"] = "result";
            }
            else {
                echo "<script>";
                echo "  top.$('#result').load('" . MODUL_PATH . "_processPihak/select/$jenisTr/MdlSupplier?id=$getSupplierID" . "');";
                echo "</script>";

            }
        }
        else {
            $arrReset = array(
                "pihakID",
                "pihakName",
                "pihakName2",
                "supplierID",
                "supplierName",
                "vendorDetails",
                "vendorDetails__label",
                "vendorDetails__nama",
                "vendorDetails__alamat_1",
                "vendorDetails__tlp_1",
                "vendorDetails__tlp_2",
                "vendorDetails__contact_person",
            );
            foreach ($arrReset as $kk) {
                $_SESSION[$cCode]["main"][$kk] = null;
            }
            unset($_SESSION[$cCode]["main_elements"]["vendorDetails"]);
            unset($_SESSION[$cCode]["main_elements"]["deliveryDetails"]);
        }


//         matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/

        //MODE POST items INI DIPAKAI UNTUK HANDLE API (ajax handler)
        //KLO MAU PAKE YG BIASA GUNAKAN MODE GET items

        if (isset($_POST['items'])) {
//            $result = array(
//                "status" => 1,
//                "url" => base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id",
//            );
            $result["status_c"] = 1;
            $result["url_c"] = base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id";
            $result["frame_c"] = "shopping_cart";
            echo json_encode($result);
        }
        else {
            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "</script>";
        }
    }

}