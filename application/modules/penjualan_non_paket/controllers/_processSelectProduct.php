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
        // arrPrint($_GET);

        //untuk obat sementara discount minus
        if ($_GET["disc_percent"] < 0 || $_GET["disc_percent"] > 100) {
            $minus = $_GET["disc_percent"] < 0 ? "persentase diskon salah, silahkan menggunakan nilai positif" : "pemberian diskon salah, diskon maksimal 100%";
            matiHEre($minus);
        }
        if ($_GET["disc"] < 0) {
            matiHEre("pemberian diskon salah, silahkan menggunakan nilai positif");
        }
        // if($_GET["nett1"] < 0){
        //     matiHEre("pemberian diskon salah, diskon maksimal 100%");
        // }

        //

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
        //        $arrDataTambahan = array(
        //            "outdoor" => array(
        //                "outdoor_id" => "outdoor_nama",
        //            ),
        //            "indoor" => array(
        //                "indoor_id_1" => "indoor_nama_1",
        //                "indoor_id_2" => "indoor_nama_2",
        //                "indoor_id_3" => "indoor_nama_3",
        //                "indoor_id_4" => "indoor_nama_4",
        //            ),
        //            "heater" => array(
        //                "heater_id" => "heater_nama",
        //            ),
        //        );
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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");

        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("lime");
        // matiHere(__LINE__ . " " .__METHOD__);

        $this->load->library("Diskon");
        $ld = new Diskon();
        $ld->setTokoId(my_toko_id());
        // unset($_SESSION[$cCode]['items']);
        $pro_jml = 0;
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $produk_jenis_id = $rows->kategori_id;
                $produk_jenis = $rows->kategori_nama;
                $produk_nama = $rows->nama;
                $produk_kode = $rows->kode;
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

                /* ----------------------------------------------------------------
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
                            "harga_jasa"=>0,
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
                            arrPrintKuning($rawPrices);
                            $prices = normalizePrices("produk", $rawPrices);
                            arrPrint($prices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
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

                    /* ---------------------------------------------
                     * penambahan pilihan harga manual
                     * ---------------------------------------------*/
                    $harga_pilihan = isset($_GET['harga']) ? $_GET['harga'] : false;
                    if($harga_pilihan != false){
                        if(isset($_SESSION[$cCode]['items'][$id]['jual'])){
                            // cekOrange("harga diganti");
                            $_SESSION[$cCode]['items'][$id]['jual'] = $harga_pilihan;

                        }

                        if(isset($_SESSION[$cCode]['items'][$id]) && isset($_GET['rowid'])){
                            if($_SESSION[$cCode]['items'][$id]['id'] == $id){
                                $_SESSION[$cCode]['items'][$id]['row_harga_id'] = $_GET['rowid'];
                            }
                        }

                        if(isset($_GET['rowid'])){

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
                        echo("iterating subNums..");
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
                $tmp = $_SESSION[$cCode]['items'][$id];
                $sesmain = $_SESSION[$cCode]["main"];
                $pihak_kategori = $sesmain['kategoriNama'];
                $pro_diskon = $rows->diskon_persen;
                $pro_premi = $rows->premi_jual;
                // cekHitam($pro_premi . " uhui");
                $pro_harga = $tmp['jual'] + (($pro_premi / 100) * $tmp['jual']);
                $pro_harga_reseller = $tmp['jual_reseller'] + (($pro_premi / 100) * $tmp['jual_reseller']);
                // cekBiru("pro_harga_reseller: $pro_harga_reseller");
                // cekBiru("masuk ke diskon2an:: $pro_premi || $pro_harga ori:".$tmp['jual']." @" . __LINE__);
                // cekKuning("$pihak_kategori");
                cekKuning("$pro_harga");
                $pro_jml = $tmp['jml'];

                if ($pihak_kategori == "distributor") {
                    $calc_hasils = $ld->selectorDiskon($id, $pro_harga_reseller, $pro_jml, $rows, $sesmain);
                    arrPrintHijau($calc_hasils);
                    $calc_hasil = $calc_hasils["grosir"];
                }
                else {
                    $calc_hasils = $ld->selectorDiskon($id, $pro_harga, $pro_jml, $rows);
                    $calc_hasil = $calc_hasils["simple"];
                }

                arrPrintKuning($calc_hasil);
// matiHere(__LINE__);
                $tmp['discPersen'] = $calc_hasil['persen'];
                $tmp['lastNett'] = $calc_hasil['harga_af'];
                // $tmp['harga'] = $pihak_kategori == "reguler" ? $pro_harga : $calc_hasil['harga_af'];
                /* ---------------------------------------------------------------------------
                 * kategori ada 3: reguler distributor online
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
                        }
                    }
                    else {
                        if ($pro_premi > 0) {
                            $harga_yg_dipakai = $pro_harga;
                            $jual_dipakai = $pro_harga;
                        }
                        else {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                            $jual_dipakai = $pro_harga;
                        }
                    }
                }

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

//                arrPrintWebs($item);
                $kategori_produk = $item["kategori_nama"];
                if(!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])){
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;

            }
            // arrPrint($_SESSION[$cCode]["main"]);
            // ---------------------------------------------------------

            $potongan_nilai = $ld->selectorDiskonKategori($_SESSION[$cCode]);

            arrPrintPink($potongan_nilai);
            // arrPrintKuning($produk_jenis);
            cekHijau("membuat session main");
            if(ipadd() == "202.65.117.72"){
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
                        if(!isset($item_speks['jual_reseller'])){
                            $pro_harga_dipakai = $pro_harga2;
                        }
                        else{
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
                        arrPrintHijau($calc_hasils);
                    }

                    cekHitam("$pro_id ---------");
                    arrPrintWebs($calc_hasils);

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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");
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
            cekBiru("TIDAK melibatkan session @" . __CLASS__);
        }


        if (isset($_SESSION[$cCode]['items'][$id])) {
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
//            $_SESSION[$cCode]['out_detail'][$id] = null;
//            unset($_SESSION[$cCode]['out_detail'][$id]);
//            $_SESSION[$cCode]['out_detail2'][$id] = null;
//            unset($_SESSION[$cCode]['out_detail2'][$id]);
            /* -------------------------------------------------------
             * kategori item untuk diskon unit/non-unit
             * -------------------------------------------------------*/
            unset($_SESSION[$cCode]['items_kategori']);
            foreach ($_SESSION[$cCode]['items'] as $item) {
                $kategori_produk = $item["kategori_nama"];
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
            }
            foreach ($_SESSION[$cCode]['items'] as $item) {
                $pro_jml = $item['jml'];
                $produk_jenis = $item['kategori_nama'];

                if (!isset($_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'])) {
                    $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] = 0;
                }
                $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] += $pro_jml;

                $kategori_produk = $item["kategori_nama"];
                if(!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])){
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;
            }
            // ----------------------------------------end---------------
        }
        if (isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = null;
            unset($_SESSION[$cCode]['items2'][$id]);
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }


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
        $vg->setppnFactor($ppnFactor);
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
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");

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
        $vg->setppnFactor($ppnFactor);
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

    //tambahan selector satuan
    public function selectFactorSatuan()
    {
        $cCode = $this->cCode;
        arrPrint($_GET);
        // arrPrint($this->uri->segment_array());
        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $p = new MdlProdukSatuanRelasi();
        $id = isset($_GET['id']) ? $_GET['id'] : "0";
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $pID = $produk_id = $_GET['pid'];
        $kID = $_GET['key'];
        $kValue = $_GET['value'];
        $qparams = blobDecode($_GET["qparams"]);
        // arrPrint($qparams);
        // arrPrint($_GET);
        // matiHere();
        // arrprint($_SESSION[$cCode]["items3"]);

        $p->addFilter("toko_id='" . my_toko_id() . "'");
        $p->addFilter("produk_id='$pID'");
        $p->addFilter("$kID='$kValue'");
        $p->setTokoId(my_toko_id());
        $temp = $p->lookUpRelasiSatuan($pID);

        $toUpDate = array();
        foreach ($temp[$pID] as $temp_0) {
            if ($temp_0[$kID] == $kValue) {
                foreach ($qparams as $src => $srcTarget) {
                    $toUpDate[$srcTarget] = $temp_0[$src];
                }
            }
        }

        foreach ($toUpDate as $key => $values) {
            $_SESSION[$cCode]["items"][$pID][$key] = $values;
        }

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

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

        $tmpB = $b->lookupByID($pID)->result();
        showLast_query("lime");

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {

                /* ----------------------------------------------------------
                 * diskon-diskonan-grosir
                 * ----------------------------------------------------------*/
                $tmp = $_SESSION[$cCode]['items'][$pID];
                $pro_premi = $row->premi_jual;
                $pro_harga = $tmp['harga_list'] + (($pro_premi / 100) * $tmp['harga_list']);

                cekBiru("$pro_premi || $pro_harga");

                $pro_jml = $tmp['jml'];
                $calc_hasil = $this->selectorDiskon($pID, $pro_harga, $pro_jml);

                arrPrintKuning($calc_hasil);

                $tmp['discPersen'] = $calc_hasil['persen'];
                $tmp['harga_disc'] = $calc_hasil['harga_af'] * $tmp['satuan_factor_qty'];
                $tmp['lastNett'] = $calc_hasil['harga_af'];
                $tmp['harga'] = $calc_hasil['harga_af'];
                $tmp['harga_jual'] = $calc_hasil['harga_be'] * $tmp['satuan_factor_qty'];
                $tmp['discNilai'] = $calc_hasil['nilai'];
                $tmp['id'] = $pID;

                //                $tmp['bayar'] = 0; //reset bayar

                $tmp['subtotal'] = $calc_hasil['harga_af'] * $pro_jml;

                // arrPrintPink($_SESSION[$cCode]['items'], __LINE__ . " " . __METHOD__);

                //                cekMerah("hasil update + diskon sbb:");
                //                arrPrintWebs($tmp);


                $_SESSION[$cCode]['items'][$produk_id] = $tmp;
                // matiHere(__METHOD__ . __LINE__);

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
        // $configUiJenis = $this->configUi[$this->jenisTr];
        // $configCoreJenis = $this->configCore[$this->jenisTr];
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        if (isset($_GET['spc'])) {
            // matiHEre(__LINE__." file ".__FILE__);
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
                "jenisTrTop" => $this->configUiJenis['steps'][$stepNum]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][$stepNum]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


            // matiHere(__METHOD__ . __LINE__);
            /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
            echo "<script>";
            // echo "  if(top.document.getElementById('shopping_cart')){";
            // echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            // echo "  }";

            echo "if(top.document.getElementById('shopping_cart')){
                    localStorage.setItem('loadShoppingCart', 10);
                    top.$('#btn_kalkulasi').removeClass('hidden');
                  }";
            echo "</script>";

            // echo "<script>";
            // echo "top.document.getElementById('result').src='" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?" . $f_selector . "selID=$id';";
            // echo "top.load_shoppingcart();";
            // echo "</script>";
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
                "jenisTrTop" => $this->configUiJenis['steps'][$stepNum]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][$stepNum]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            // arrPrintKuning($_SESSION[$cCode]['items']);

            /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
    }

    private function selectorDiskon($produk_id, $produk_harga, $produk_jml, $produk_speks = array(), $ses_mains = array())
    {
        // arrPrint($produk_speks);
        // cekHitam("produk_id $produk_id || tokid " . my_toko_id());
        $this->load->library("Diskon");
        $dp = new Diskon();
        $dp->setTokoId(my_toko_id());
        $src_diskon = $dp->CallProdukDiskon($produk_id);
        // arrPrintHijau($src_diskon);
        // diskon
        $pro_harga = $produk_harga * 1;
        $pro_jml = $produk_jml;
        // cekPink("$pro_harga || $pro_jml");
        /*---menentukan diskon pokok dari produk atau grosir----*/
        $pihak_kategori = $ses_mains['kategoriNama'];

        $d_pokok = isset($src_diskon['produk']) ? $src_diskon['produk'] : 0;

        if ($pihak_kategori == "distributor") {
            $d_pokok = 0;
        }

        $jml_spek_grosir = sizeof($src_diskon['grosir']);
        if ($jml_spek_grosir > 0) {
            $gro_count = 0;
            foreach ($src_diskon['grosir'] as $item) {
                $gro_count++;
                $gro_minim = $item['minim'];
                $gro_maxim = $gro_count == $jml_spek_grosir ? INF : $item['maxim'];
                $gro_persen = $item['persen'];
                // cekPink2("$pro_jml >= $gro_minim) && ($pro_jml <= $gro_maxim)");

                if (($pro_jml >= $gro_minim) && ($pro_jml <= $gro_maxim)) {
                    $d_pokok = $gro_persen;
                    // cekBiru("---- $d_pokok");
                    break;
                }
            }
        }
        else {
            // $d_pokok = isset($src_diskon['produk']) ? $src_diskon['produk'] : 0;
        }

        $diskon_pokok["produk"] = $d_pokok;
        $diskon_event = array();

        $calc_hasil_grosir = $dp->calcDiskon($pro_harga, $diskon_pokok, $diskon_event, "diskon");

        if (count($produk_speks) > 0) {
            $pro_diskon = $produk_speks->diskon_persen;
            // $pro_premi = $produk_speks->premi_jual;
            $diskon_pokok["produk"] = $pro_diskon;

            $calc_hasil_simple = $dp->calcDiskon($pro_harga, $diskon_pokok, $diskon_event, "diskon");
        }

        $calc_hasil = array();
        $calc_hasil["grosir"] = $calc_hasil_grosir;
        $calc_hasil["simple"] = $calc_hasil_simple;

        return $calc_hasil;
    }

    public function selectReturn()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrint($_GET);
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $produk_id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $qty_unit = isset($_GET['qty_unit']) ? $_GET['qty_unit'] : 1;
        $cCode = $this->cCode;
        $pihakID = $_SESSION[$cCode]["main"]["pihakID"];
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

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
        $shopingCartItemOptionFields = isset($this->configUi[$this->jenisTr]['shopingCartItemOptionFields'][1]) ? $this->configUi[$this->jenisTr]['shopingCartItemOptionFields'][1] : array();
        $valueGateConfig = isset($this->configValues[$this->jenisTr]['detailInjectedValues']) ? $this->configValues[$this->jenisTr]['detailInjectedValues'] : array();
        $selectorProcessorParam = isset($this->configUi[$this->jenisTr]['selectorProcessorParam']) ? $this->configUi[$this->jenisTr]['selectorProcessorParam'] : array();
        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("lime");
        // matiHere(__LINE__ . " " .__METHOD__);

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


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                /* ----------------------------------------------------------------
                 * inisisasi cCode Items
                 * untuk harga ambil dari stock_locker_penjualan_cache degnan index custtomerid dan produk id
                 * ----------------------------------------------------------------*/
                if (!isset($_SESSION[$cCode]['items']) || !array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "qty_unit" => $qty_unit,
                        "harga" => 0,
                        "subtotal" => 0,
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
                    /*
                     * pengganti price config
                     */
                    $this->load->model("Mdls/MdlLockerStockPenjualanCache");
                    $pr = new MdlLockerStockPenjualanCache();
                    $pr->addFilter("extern_id='$id'");
                    $pr->addFilter("extern2_id='$pihakID'");
                    $tmpLocker = $pr->lookUpAll()->result();
                    if (sizeof($tmpLocker) > 0) {
                        foreach ($tmpLocker as $tmpLocker_0) {
                            foreach ($selectorProcessorParam as $key_tbl => $key_target) {
                                $tmp[$key_target] = $tmpLocker_0->$key_tbl;
                            }
                        }
                    }

                    //                    arrPrint($tmpLocker);
                    //                    arrPrint($selectorProcessorParam);


                    // if (sizeof($priceConfig) > 0) {
                    //     $mdlName = $priceConfig['model'];
                    //     $this->load->model("Mdls/" . $mdlName);
                    //     $h = new $mdlName();
                    //     if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                    //         $h->addFilter("produk_id='$id'");
                    //         // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    //         // $h->addFilter("toko_id=" . my_toko_id());
                    //     }
                    //     else {
                    //         $h->addFilter("produk_id='$id'");
                    //         $h->addFilter("status='1'");
                    //         $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                    //         // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    //         // $h->addFilter("toko_id=" . my_toko_id());
                    //     }
                    //
                    //     if (sizeof($priceFilter) > 0) {
                    //         foreach ($priceFilter as $f) {
                    //             $f_ex = explode("=", $f);
                    //             if (!isset($f_ex[1])) {
                    //                 $f_ey = explode(">", $f_ex[0]);
                    //                 if (substr($f_ey[1], 0, 1) == ".") {
                    //                     $h->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    //                 }
                    //                 else {
                    //                     if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                    //                         $h->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                    //                     }
                    //                     else {
                    //                         $h->addFilter($f_ey[0] . ">0");
                    //                     }
                    //                 }
                    //             }
                    //             else {
                    //                 if (substr($f_ex[1], 0, 1) == ".") {
                    //                     $h->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    //                 }
                    //                 else {
                    //                     if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                    //                         $h->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                    //                     }
                    //                     else {
                    //                         $h->addFilter($f_ex[0] . "=''");
                    //                     }
                    //
                    //                 }
                    //             }
                    //         }
                    //     }
                    //     $tmpH = $h->lookupAll($id)->result();
                    //     // showLast_query("kuning");
                    //
                    //     if (sizeof($tmpH) > 0) {
                    //         $rawPrices = array();
                    //         foreach ($tmpH as $hSpec) {
                    //             foreach ($priceConfig['key_label'] as $key => $val) {
                    //                 //                                    cekHitam($key);
                    //                 if (isset($resetFilter['resetFilter'])) {
                    //                     cekBiru("sino$key ||" . $hSpec->$key);
                    //                     //                                        if ($key == $hSpec->h) {
                    //                     //                                            cekLime($hSpec->$key);
                    //                     $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                    //                     //                                        }
                    //                 }
                    //                 else {
                    //                     cekBiru("sini " . __LINE__);
                    //                     if ($key == $hSpec->jenis_value) {
                    //                         $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                    //                     }
                    //                 }
                    //
                    //             }
                    //
                    //         }
                    //         $prices = normalizePrices("produk", $rawPrices);
                    //         if (sizeof($prices) > 0) {
                    //             foreach ($prices as $k => $v) {
                    //                 $tmp[$k] = $v;
                    //             }
                    //             // $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                    //         }
                    //     }
                    //
                    // }
                    //------------------------------------------------------
                    // arrPrint( $fieldSrcs);
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
                            //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                        }
                    }

                    // matiHEre(__LINE__);

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


                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    /*
                     * bagian option satuan
                     */
                    // arrPrint($shopingCartItemOptionFields);
                    //region sambungin ke relasi satuan
                    if (sizeof($shopingCartItemOptionFields) > 0) {
                        foreach ($shopingCartItemOptionFields as $keyIndex => $dataExtSatuan) {
                            $mdl = $dataExtSatuan["mdlName"];
                            // $field = $dataExtSatuan["selectField"];
                            $targetSes = $dataExtSatuan["targetSession"];
                            $methode = $dataExtSatuan["methode"];
                            $indexKey = $dataExtSatuan["keySrc"];
                            $this->load->model("Mdls/" . $mdl);
                            $mm = new $mdl();
                            $mm->setTokoId(my_toko_id());
                            $prevCon = $mm->$methode($id);
                            // arrprint($prevCon);
                            // matiHEre();
                            if (sizeof($prevCon) > 0) {
                                foreach ($prevCon[$id] as $prevCon_0) {
                                    foreach ($dataExtSatuan["usedFields"] as $src => $target) {
                                        if (!isset($_SESSION[$cCode][$targetSes][$id][$keyIndex][$prevCon_0[$indexKey]][$target])) {
                                            // cekMerah($prevCon_0[$indexKey]."  $target =>".$prevCon_0[$src]);
                                            $_SESSION[$cCode][$targetSes][$id][$keyIndex][$prevCon_0[$indexKey]][$target] = $prevCon_0[$src];
                                        }

                                    }
                                }
                            }
                        }
                    }
                    // matiHere();
                    $_SESSION[$cCode]['items'][$produk_id] = $tmp;
                    //endregion
                    // matiHEre(__LINE__);
                }
                else {
                    cekBiru("ada id $id tapi tidak ada cCode items @" . __LINE__);

                    //validasi stok
                    $stok_unit = $_SESSION[$cCode]['items'][$id]["stok"];
                    $newReq_qty = $_GET['newQty'] * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];

                    if ($newReq_qty <= $stok_unit) {
                        //skip boleh lanjut
                    }
                    else {
                        $errMsg = "Ditolak karena qty retur " . htmlspecialchars($_SESSION[$cCode]["items"][$id]["nama"]) . " melebihi total pembelian  dari konsumen (" . htmlspecialchars($_SESSION[$cCode]["items"][$id]["customerName"]) . ") Silahkan periksa kembali pemilihan satuan, maupun qty yang di minta";

                        $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                        $alerts = array(
                            "type" => "warning",
                            "title" => strtoupper("PERHATIAN"),
                            "html" => $errMsg,
                        );
                        echo swalAlert($alerts);
                        die($msg);
                    }

                    if (isset($_GET['newQty'])) {
                        // $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        // $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                        $_SESSION[$cCode]['items'][$id]['qty_unit'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'] * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];

                    }
                    else {
                        // $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                        // $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + (isset($_SESSION[$cCode]['items'][$id]['ppn']) ? $_SESSION[$cCode]['items'][$id]['ppn'] : 0)));
                        if (($_SESSION[$cCode]['items'][$id]['qty_unit'] + $qty_unit) > $stok_unit) {
                            $_SESSION[$cCode]['items'][$id]['qty_unit'] = $stok_unit;
                            $_SESSION[$cCode]['items'][$id]['jml'] = $stok_unit * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];
                        }
                        else {
                            $_SESSION[$cCode]['items'][$id]['qty_unit'] += $qty_unit;
                            $_SESSION[$cCode]['items'][$id]['jml'] += $qty_unit * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];
                        }


                    }


                    // if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {
                    //     foreach ($arrDiscount[$id] as $dKey => $dVal) {
                    //         if (!isset($_SESSION[$cCode]['items'][$id]['discount_' . $dKey])) {
                    //             $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = 0;
                    //         }
                    //         $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = $dVal;
                    //     }
                    // }

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

                // matiHEre(__LINE__);
                /* ----------------------------------------------------------
                 * diskon-diskonan-grosir
                 * ----------------------------------------------------------*/
                // $tmp = $_SESSION[$cCode]['items'][$id];
                // $pro_premi = $row->premi_jual;
                // $pro_harga = $tmp['harga_list'] + (($pro_premi / 100) * $tmp['harga_list']);
                // cekBiru("$pro_premi || $pro_harga");
                // $pro_jml = $tmp['jml'];
                // $calc_hasil = $this->selectorDiskon($id, $pro_harga, $pro_jml);
                // arrPrintKuning($calc_hasil);
                // $tmp['discPersen'] = $calc_hasil['persen'];
                // $tmp['harga_disc'] = $calc_hasil['harga_af'];
                // $tmp['lastNett'] = $calc_hasil['harga_af'];
                // $tmp['harga'] = $calc_hasil['harga_af'];
                // $tmp['harga_jual'] = $calc_hasil['harga_be'];
                // $tmp['discNilai'] = $calc_hasil['nilai'];
                // $tmp['id'] = $id;


                // $_SESSION[$cCode]['items'][$produk_id] = $tmp;// untuk return digeser ke builder session lihat !isset() <----

            }


        }
        else {
            cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
            die();
        }

        // arrPrint($_SESSION[$cCode]['items']);
        // mati_disini(__LINE__);
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

        if (isset($_GET['mb'])) {
            mati_disini(__FILE__ . "<hr> @" . __LINE__);
            $initMaster = array(
                "olehID" => $this->session->login['id'],
                "olehName" => $this->session->login['nama'],
                "placeID" => $this->session->login['cabang_id'],
                "placeName" => $this->session->login['cabang_nama'],
                "divID" => isset($this->session->login['div_id']) ? $this->session->login['div_id'] : 0,
                "divName" => isset($this->session->login['div_nama']) ? $this->session->login['div_nama'] : 0,
                "cabangID" => $this->session->login['cabang_id'],
                "cabangName" => $this->session->login['cabang_nama'],
                "gudangID" => $this->session->login['gudang_id'],
                "gudangName" => $this->session->login['gudang_nama'],
                "jenis_usaha" => isset($this->session->login['jenis_usaha']) ? $this->session->login['jenis_usaha'] : '-',
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
                "jenisTrName" => $this->jenisTrName,
                "stepNumber" => $stepNum,
                "stepCode" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['target'],
                "dtime" => date("Y-m-d H:i:s"),
                "fulldate" => date("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
                "tokoID" => $this->session->login['toko_id'],
                "tokoNama" => $this->session->login['toko_nama'],
            );
            // hevalueGateInisisai($initMaster);
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
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
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
    }

    private function selectorDiskonKategori($sessioncCode)
    {

        $this->load->model("Mdls/MdlDiskonCustomer");
        $dcu = new MdlDiskonCustomer();
        $dc_params = $dcu_srcs = $dcu->callDiskonAktive();
        // arrPrint($row);
        // arrPrint($dcu_srcs);
        // arrPrintPink($dc_params);
        /* ------------------------------------------
         * nyocokin dr item ada yg masuk setting diskon atau tidak
         * ------------------------------------------*/
        $sess_item_kategories = isset($sessioncCode['items_kategori']) ? $sessioncCode['items_kategori'] : array();
        $potongan_nilai = array();
        foreach ($dc_params as $dc_jenis_0 => $dc_param) {
            cekBiru("$dc_jenis_0:: " . $dc_jenis_0);
            /*-------kalau ada diskonnya dihitung----------*/
            if ((count($sess_item_kategories) > 0) && array_key_exists($dc_jenis_0, $sess_item_kategories)) {
                $jml_kategori = $sess_item_kategories[$dc_jenis_0]['jml'];

                $jml_dcu = count($dc_param);
                $dcu_count = 0;
                foreach ($dc_param as $x => $item) {
                    $dcu_count++;
                    // arrPrintKuning($item);
                    $minim = $item["minim"];
                    $maxim = $jml_dcu == $dcu_count ? INF : $item["maxim"];
                    $nilai = $item["nilai"];

                    cekOrange(" //// $minim <= $jml_kategori <= $maxim /////");
                    if ($jml_kategori >= $minim && $jml_kategori <= $maxim) {
                        cekHijau("nilai:: $nilai");
                        $potongan_nilai[$dc_jenis_0]['nilai'] = $nilai;
                        $potongan_nilai[$dc_jenis_0]['jml'] = $jml_kategori;

                        break;
                    }
                    else {
                        $potongan_nilai[$dc_jenis_0]['nilai'] = 0;
                        $potongan_nilai[$dc_jenis_0]['jml'] = $jml_kategori;
                    }
                }
            }
            else {
                cekKuning("tidak ada diskon " . __LINE__);
            }

        }

        return $potongan_nilai;
    }

    public function selectProdukService()
    {

        $this->load->helper("he_angka_helper");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
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
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");
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
                                //                                if (isset($_GET['newQty'])) {
                                //                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                //                                    $jml_diperlukan = $_GET['newQty'];
                                //                                    $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                //                                }
                                //                                else {
                                //                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                //                                    $jml_diperlukan = $jml_sudah_diambil + $jml;
                                //                                    $jml_nambah = $jml;
                                //                                }
                            }
                            //  region validasi stok
                            //                            if ($jml_nambah > $jml_now) {
                            //                                echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                            //                                echo "</script>";
                            //                                die();
                            //                            }
                            //  endregion validasi stok

                            //
                            //                            $this->db->trans_start();
                            //
                            //                            //  region update locker active
                            //                            $where = array(
                            //                                "id" => $row->id,
                            //                            );
                            //                            $data_active = array(
                            //                                "jumlah" => $jml_now - $jml_nambah,
                            //                                "state" => "active",
                            //                            );
                            //                            $c->updateData($where, $data_active);
                            //                            cekHere($this->db->last_query());
                            //                            //  endregion update locker active
                            //
                            //
                            //                            //  region locker hold
                            //                            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                            ////                            arrPrint($array_hold_sebelumnya);
                            ////                            mati_disini();
                            //                            if (sizeof($array_hold_sebelumnya) > 0) {
                            //                                $where = array(
                            //                                    "id" => $array_hold_sebelumnya['id'],
                            //                                );
                            //                                $data_hold = array(
                            //                                    "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                            //                                );
                            //                                $c->updateData($where, $data_hold);
                            //                                cekHere($this->db->last_query());
                            //                            }
                            //                            else {
                            //                                $data_hold = array(
                            //                                    "jenis" => "produk",
                            //                                    "cabang_id" => $this->session->login['cabang_id'],
                            //                                    "produk_id" => $id,
                            //                                    "nama" => $nama,
                            //                                    "satuan" => $row->satuan,
                            //                                    "state" => "hold",
                            //                                    "jumlah" => $jml_nambah,
                            //                                    "oleh_id" => $this->session->login['id'],
                            //                                    "oleh_nama" => $this->session->login['nama'],
                            //                                    "gudang_id" => $this->session->login['gudang_id'],
                            //                                );
                            //                                $c->addData($data_hold);
                            //                                cekHere($this->db->last_query());
                            //                            }
                            //                            //  endregion locker hold
                            //
                            //                            $this->db->trans_complete() or die("Gagal bro");
                            //
                            //                            $tmpJml = $jml_diperlukan;

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

                    //                    matiHere(__LINE__);
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
                        //                        if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
                        //                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
                        //                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
                        //                            }
                        //                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
                        //                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
                        //                            }
                        //                            $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
                        //                            $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);
                        //
                        //                        }
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
            //
            //            if (sizeof($_SESSION[$cCode]['items']) > 0) {
            //                $_SESSION[$cCode]['main']['harga'] = 0;
            //                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
            //                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            //                }
            //            }

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
        $vg->setppnFactor($ppnFactor);


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
    }

}