<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
class _processSelectProductException extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }

    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

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


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();

        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $priceMainConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedMainPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedMainPrice'] : array();

        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['validateMeasurement'][1]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['validateMeasurement'][1] : array();
        $compareFieldsItems = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shopingCartCompareFields'][1]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shopingCartCompareFields'][1] : array();
        $shopingCartParamForceEditable = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shopingCartParamForceEditable'][1]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shopingCartParamForceEditable'][1] : array();


        $tmpB = $b->lookupByID($id)->result();
//        ceklime($this->db->last_query());
//        matiEHre();
//arrPrint($compareFieldsItems);
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


                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
//                matiHere($selectorModel);
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
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
//                                    cekHitam($key);
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

                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {

                    if (isset($_GET['newQty'])) {
                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
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

                if (sizeof($compareFieldsItems) > 0) {
                    $sourceValidateKey = $compareFieldsItems['main'];
                    $targetValidateKey = $compareFieldsItems['slave'];
                    $pph_on = isset($row->$sourceValidateKey) ? $row->$sourceValidateKey : 0;
                    $detect_ppn = isset($_SESSION[$cCode]['items'][$id][$targetValidateKey]) ? $_SESSION[$cCode]['items'][$id][$targetValidateKey] : 0;

                    //region detect npwp/ktp
//                    $pihak_id = !isset($_SESSION[$cCode]['main']['pihakID']) ? matiHere("Vendor belum dipilih"):$_SESSION[$cCode]['main']['pihakID'];
                    if (!isset($_SESSION[$cCode]['main']['pihakID'])) {
                        unset($_SESSION[$cCode]['items']);
                        matiHere("Vendor belum dipilih");
                    }
                    else {
                        $pihak_id = $_SESSION[$cCode]['main']['pihakID'];

                    }
                    $this->load->model("Mdls/MdlSupplier");
                    $s = new MdlSupplier();
                    $s->addFilter("id=$pihak_id");
                    $sData = $s->lookupAll()->result();
                    $npwp = $sData[0]->npwp;
                    $ktp = $sData[0]->no_ktp;
                    if (strlen($npwp) > 3) {
                        $ppn_allowed = true;
                    }
                    else {
                        $ppn_allowed = false;
                    }
                    if ($ppn_allowed) {
//                        $_SESSION[$cCode]['items'][$id]['allow_params_edit']=true;
                        foreach ($shopingCartParamForceEditable as $prams => $keyFields) {
//                            $_SESSION[$cCode]['items'][$id][$prams]=true;
                            $_SESSION[$cCode][$prams] = array($keyFields => true);
                        }
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id][$targetValidateKey] = 0;
                        if (sizeof($shopingCartParamForceEditable) > 0) {
                            foreach ($shopingCartParamForceEditable as $prams => $keyFields) {
//                               $_SESSION[$cCode]['items'][$id][$prams]=false;
                                $_SESSION[$cCode][$prams] = array($keyFields => false);
                            }

                        }
                    }
                    //endregion
//                    matiHEre("ppn::".$pph_on." vs pph :: ".$pph_on);
                    if (($pph_on > 0) && ($detect_ppn == 0)) {
                        if (!isset($_SESSION[$cCode]['main']["valid_pph_key"])) {
//                            $valValidPph = $pph_on * $detect_ppn;
                            $_SESSION[$cCode]['main']["valid_pph_key"] = $pph_on;
//                            matiHere($pph_on." <br> ".$detect_ppn);

                        }
                        else {
//                            matiHere($pph_on." <br> ".$detect_ppn);
                            $_SESSION[$cCode]['main']["valid_pph_key"] = $pph_on;
                        }

                    }
                    else {
                        if (!isset($_SESSION[$cCode]['main']["valid_pph_key"])) {
                            $_SESSION[$cCode]['main']["valid_pph_key"] = 0;
                        }
//                        matiHEre("else nya ini");
                    }
                }
                else {

                }
            }


        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }

//arrPrint($_SESSION[$cCode]['items']);
//mati_disini();
        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
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

//        mati_disini();
//        die();
        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart');";
        // echo "top.document.getElementById('tr_".$id."').style.background='#ffff00';";
        echo "</script>";
    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();


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

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart')";
        echo "</script>";
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

        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['validateMeasurement'][1]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['validateMeasurement'][1] : array();


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


                $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
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
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        }
                        else {
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
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


        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

}