<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
class _processSelectProductStock extends CI_Controller
{
    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlHargaProduk");
    }

    public function select()
    {

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : $_GET['minValue'];

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $cCode = "_TR_" . $this->jenisTr;
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();

        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $lockerWarnOnly = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckWarnOnly']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckWarnOnly'] : false;

        $connectedDiscountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount'] : array();


        $jml_nambah = $jml;
        //pairing produk
        $b = new MdlProduk();
        $b->addFilter("id=" . $id);
        $tmpB = $b->lookupAll($id)->result();


        $c = new MdlLockerStock();
        $c->addFilter("jenis='produk'");
        $c->addFilter("produk_id='$id'");
        $c->addFilter("state='active'");
        $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
        $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
        $c->addFilter("oleh_id='0'");
        $c->addFilter("transaksi_id='0'");
        $tmpC = $c->lookupAll($id)->result();

//        cekBiru($this->db->last_query());
        $produk_id = $tmpC[0]->produk_id;


        if (sizeof($tmpC) > 0) {
            $currentActiveLocker = array(
                "id" => $tmpC[0]->id,
                "jml" => $tmpC[0]->jumlah,
                "nama" => $tmpC[0]->nama,
                "satuan" => $tmpC[0]->satuan,
            );
        }
        else {
            $currentActiveLocker = array(
                "id" => 0,
                "jml" => 0,
                "nama" => "noname",
                "satuan" => "-",
            );
        }


        $c = new MdlLockerStock();
        $c->addFilter("jenis='produk'");
        $c->addFilter("produk_id='$id'");
        $c->addFilter("state='hold'");
        $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
        $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
        $c->addFilter("oleh_id=" . $this->session->login['id']);
        $c->addFilter("transaksi_id='0'");
        $tmpC = $c->lookupAll($id)->result();

        if (sizeof($tmpC) > 0) {
            $currentHoldLocker = array(
                "id" => $tmpC[0]->id,
                "jml" => $tmpC[0]->jumlah,
                "nama" => $tmpC[0]->nama,
                "satuan" => $tmpC[0]->satuan,
            );
        }
        else {
            $currentHoldLocker = array(
                "id" => 0,
                "jml" => 0,
                "nama" => "noname",
                "satuan" => "-",
            );
        }


        $persediaan = (sizeof($tmpC) > 0 && $tmpC[0]->jumlah > 0) ? $tmpC[0]->jumlah : "0";

        //==jumlahnya berupa entri baru atau edit?

        //==sudah ada di shopcart atau belum
        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {//belum ada
            $mode = "new";
            $jml_sudah_diambil = 0;
            if (isset($_GET['newQty'])) {
                $jml_diperlukan = $_GET['newQty'];
            }
            else {
                if (isset($_GET['jml'])) {
                    $jml_diperlukan = $_GET['jml'];
                }
                else {
                    $jml_diperlukan = $jml;
                }
            }
            $jmlBaru = $jml_diperlukan;
        }
        else {//sudah ada
            if (isset($_GET['newQty'])) {
                if ($_GET['newQty'] < 1) {
                    echo(lgShowAlert("qty must be more than 0!"));
                    echo "
                                <script>                                
                                top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?ohyes=ohno','shopping_cart');reLoad();
                                </script>
                            ";
                    die();
                }
                $mode = "edit";
                $jml_sudah_diambil = $_SESSION[$cCode]['items'][$produk_id]['jml'];
                $jml_diperlukan = $_GET['newQty'];
                $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;

                if ($jml_nambah > $currentActiveLocker['jml']) {
                    echo(lgShowAlert("stok $id tidak cukup. (perlu $jml_diperlukan, dengan tambahan $jml_nambah, stok saat ini" . $currentActiveLocker['jml'] . ")"));
                    echo "
                                <script>                                
                                top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?ohyes=ohno','shopping_cart');reLoad();
                                </script>
                            ";

                    die();
                }
                $jmlBaru = $_GET['newQty'];

            }
            else {
                if (isset($_GET['jml'])) {
                    $mode = "add";
                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$produk_id]['jml'];
                    $jml_diperlukan = $jml_sudah_diambil + $jml;
                    $jml_nambah = $_GET['jml'];
                    $jmlBaru = $jml_diperlukan;
                }
                else {
                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$produk_id]['jml'];
                    $jml_diperlukan = 0;
                    $jml_nambah = 0;
                    $jmlBaru = $jml_sudah_diambil;
                }
            }
        }


        //===update lokernya
        if ($jml_nambah != 0) {
            if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {

                if ($jml_diperlukan > ($currentActiveLocker['jml'] + $currentHoldLocker['jml'])) {
                    echo(lgShowAlert("stock amount doesnt match. needed: $jml_diperlukan. avail: " . ($currentActiveLocker['jml'] + $currentHoldLocker['jml'])));
                    echo "
                                <script>                                
                                top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?ohyes=ohno','shopping_cart');reLoad();
                                </script>
                            ";
                    die();
                }

                $this->db->trans_start();
                //  region update locker active
                $where = array(
                    "id" => $currentActiveLocker['id'],
                );
                $data_active = array(
                    "jumlah" => $currentActiveLocker['jml'] - $jml_nambah,
                    "state" => "active",
                );
                $c = new MdlLockerStock();
                $c->updateData($where, $data_active);
                cekHere($this->db->last_query());
                //  endregion update locker active


                //  region locker hold
                $c = new MdlLockerStock();
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $produk_id, "hold", $this->session->login['id'], "", $this->session->login['gudang_id']);
                if (sizeof($array_hold_sebelumnya) > 0) {
                    $where = array(
                        "id" => $array_hold_sebelumnya['id'],
                    );
                    $data_hold = array(
                        "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                    );
                    $c = new MdlLockerStock();
                    $c->updateData($where, $data_hold);
                    cekBiru($this->db->last_query());
                }
                else {
                    $data_hold = array(
                        "jenis" => "produk",
                        "cabang_id" => $this->session->login['cabang_id'],
                        "produk_id" => $id,
                        "nama" => $currentActiveLocker['nama'],
                        "satuan" => $currentActiveLocker['satuan'],
                        "state" => "hold",
                        "jumlah" => $jml_nambah,
                        "oleh_id" => $this->session->login['id'],
                        "oleh_nama" => $this->session->login['nama'],
                        "gudang_id" => $this->session->login['gudang_id'],
                    );
//                    arrPrint($data_hold);

                    $c = new MdlLockerStock();
                    $c->addData($data_hold);
                    cekHere($this->db->last_query());
                }
                //  endregion locker hold
                $this->db->trans_complete();// or die("Gagal bro");
            }
            else {
                if ($lockerWarnOnly == true) {
                    if ($jml_diperlukan > ($currentActiveLocker['jml'] + $currentHoldLocker['jml'])) {
                        echo(lgShowAlert("stock amount doesnt match to make on order. needed: $jml_diperlukan. avail: " . ($currentActiveLocker['jml'] + $currentHoldLocker['jml'])));
                        echo "
                                <script>                                
                                top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?ohyes=ohno','shopping_cart');reLoad();
                                </script>
                            ";
                        die();
                    }
                }
            }
        }


        $tmp = array(
            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
            "id" => $produk_id,
            "nama" => $currentActiveLocker['nama'],
            "satuan" => $currentActiveLocker['satuan'],
            "harga" => 0,
            "subtotal" => 0,
            "jml" => $jmlBaru,
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


        $lastNett = $tmp[$priceConfig['mainSrc']];
        $lastDisc = isset($tmp['disc']) ? $tmp['disc'] : 0;
        $akumDisc = isset($tmp['disc']) ? $tmp['disc'] : 0;
        $lastNett -= $akumDisc;
        $tmp['lastNett'] = $lastNett;
        $tmp['akumDisc'] = $akumDisc;
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
                $produkQty = $jmlBaru;
                if (sizeof($tmpDr) > 0) {
                    $arrDiscount = array();
                    foreach ($tmpDr as $drSpec) {
                        $this->load->model("Mdls/" . $mdlNameSource);
                        $sr = new $mdlNameSource();
                        $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                        $sr->addFilter("status='1'");
                        $tmpSr = $sr->lookupAll($id)->result();
//                        cekBiru(__LINE__ ." - ". $this->db->last_query());


                        foreach ($tmpSr as $srSpec) {
                            if (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                $discountPersen = $srSpec->discount_persen;
//                                $discountQty = $srSpec->discount_qty;
                            }
                            else {
                                $discountPersen = 0;
//                                $discountQty = 0;
                            }

//                            $arrDiscount[$id] = array(
//                                "persen" => $discountPersen,
////                                "qty" => $discountQty,
//                            );
                            $arrDiscount[$id][$srSpec->var_name] = array(
                                "persen" => $discountPersen,
                                //                                "qty" => $discountQty,
                            );

                            cekMerah("pID: $id ::: disc_persen: $discountPersen ::: disc_qty: 0 ::: prod_qty: $produkQty");

                        }
                    }


                    if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {
//                        foreach ($arrDiscount[$id] as $dKey => $dVal) {
//                            if (!isset($tmp['__disc_' . $dKey])) {
//                                $tmp['__disc_' . $dKey] = 0;
//                            }
//                            $tmp['__disc_' . $dKey] = $dVal;
//                        }
//                        arrPrint($arrDiscount[$id]);
//                        $lastNett = $tmp['jual_nppn'];
//                        $lastDisc = 0;
//                        $akumDisc = 0;
                        foreach ($arrDiscount[$id] as $code => $dSpec) {

                            foreach ($dSpec as $dKey => $dVal) {
                                if (!isset($tmp['__disc_' . $code . '_' . $dKey])) {
                                    $tmp['__disc_' . $code . '_' . $dKey] = 0;
                                }
                                $tmp['__disc_' . $code . '_' . $dKey] = $dVal;

//                                cekHitam(":: diskon code $code :: $dKey => $dVal ::");


                                $lastDisc = ($dVal / 100) * $lastNett;
                                $akumDisc += $lastDisc;
                                $lastNett -= $lastDisc;

                                $tmp['__disc_' . $code . '_value'] = $lastDisc;

                                cekLime("eksekusi code: $code, lastDisc: $lastDisc, lastNett: $lastNett, akumDisc: $akumDisc");
                            }

                        }
                        cekOrange("lastNett: $lastNett, akumDisc: $akumDisc");

                        $tmp['lastNett'] = $lastNett;
                        $tmp['akumDisc'] = $akumDisc;
                    }
//                    else {
//                        $tmp['lastNett'] = 0;
//                        $tmp['akumDisc'] = 0;
//                    }
                }
//                else {
//                    cekHitam(":: TIDAK ada diskon");
//                    $tmp['lastNett'] = 0;
//                    $tmp['akumDisc'] = 0;
//                }
            }
        }

//        cekHijau("akumulasi diskon: $akumDisc");
//mati_disini();


        //===baru update shopcartnya
        foreach ($fieldSrcs as $key => $src) {
            cekUngu(":: $key => $src ::");
            $tmp[$key] = makeValue($src, $_SESSION[$cCode]['items'][$id], $tmp, $tmpB[0]->$src);
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
//            $tmp['subtotal']=makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $tmp, 0);
            $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
        }
        else {
            $tmp['subtotal'] = 0;
        }


//        arrprint($tmp);die();

        $_SESSION[$cCode]['items'][$produk_id] = $tmp;


//        arrPrint($_SESSION[$cCode]['items']);
//        mati_disini();

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


        $cCode = "_TR_" . $this->jenisTr;

        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }

        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $lockerWarnOnly = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckWarnOnly']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckWarnOnly'] : false;
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['connectedDiscount'] : array();


        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                            cekMerah("masuk locker config");

                            $this->db->trans_start();


//                            die();
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
//                                        $jml_diperlukan = 1;
                                        $jml_diperlukan = $arrItems[$id];
//                                        $jml_nambah = 1;
                                        $jml_nambah = $arrItems[$id];
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


                                    $this->db->trans_complete();// or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;


                                }
                            }
                            else {
                                mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                            }

                        }
                        else {
//                            cekmerah("tidak pakai loker");
                        }

                        if ($lockerWarnOnly == true) {
                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
                            cekHere($this->db->last_query());
                            if (sizeof($tmpC) < 1) {
                                if ($jml_nambah > $jml_now) {
                                    echo "<script>top.alert('$nama tidak memiliki stok. \\nsilahkan hubungi bagian terkait untuk pengadaan stok')";
                                    echo "</script>";
                                    die();
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
                                "satuan" => strlen($row->satuan) > 0 ? $row->satuan : "n/a",
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


                            $lastNett = $tmp[$priceConfig['mainSrc']];
//                            $lastNett = $priceConfig['mainSrc'];
                            $lastDisc = isset($tmp['disc']) ? $tmp['disc'] : 0;
                            $akumDisc = isset($tmp['disc']) ? $tmp['disc'] : 0;
                            $lastNett -= $akumDisc;
                            $tmp['lastNett'] = $lastNett;
                            $tmp['akumDisc'] = $akumDisc;


                            if (sizeof($connectedDiscountConfig) > 0) {
                                if ($connectedDiscountConfig['enabled'] == 1) {
                                    $mdlNameRelation = $connectedDiscountConfig['mdlNameRelation'];
                                    $mdlNameSource = $connectedDiscountConfig['mdlNameSource'];

                                    $this->load->model("Mdls/" . $mdlNameRelation);
                                    $dr = new $mdlNameRelation();
                                    $dr->addFilter("produk_id='$id'");
                                    $dr->addFilter("status='1'");
                                    $tmpDr = $dr->lookupAll($id)->result();


                                    $produkQty = $tmpJml;
//                                    $tmp['lastNett'] = $tmp['jual_nppn'];
//                                    $tmp['akumDisc'] = 0;
                                    if (sizeof($tmpDr) > 0) {
                                        $arrDiscount = array();
                                        foreach ($tmpDr as $drSpec) {
                                            $this->load->model("Mdls/" . $mdlNameSource);
                                            $sr = new $mdlNameSource();
                                            $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                                            $sr->addFilter("status='1'");
                                            $tmpSr = $sr->lookupAll($id)->result();


                                            foreach ($tmpSr as $srSpec) {
                                                if (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                                    $discountPersen = $srSpec->discount_persen;
//                                $discountQty = $srSpec->discount_qty;
                                                }
                                                else {
                                                    $discountPersen = 0;
//                                $discountQty = 0;
                                                }
                                                $arrDiscount[$id][$srSpec->var_name] = array(
                                                    "persen" => $discountPersen,
                                                    //                                "qty" => $discountQty,
                                                );

                                                cekMerah("pID: $id ::: disc_persen: $discountPersen ::: disc_qty: 0 ::: prod_qty: $produkQty");

                                            }
                                        }


                                        if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {

//                                            $lastNett = $tmp['jual_nppn'];
//                                            $lastDisc = isset($tmp['disc']) ? $tmp['disc'] : 0;
//                                            $akumDisc = isset($tmp['disc']) ? $tmp['disc'] : 0;
//                                            $lastNett -= $akumDisc;
//                                            $tmp['lastNett'] = $lastNett;
//                                            $tmp['akumDisc'] = $akumDisc;
                                            foreach ($arrDiscount[$id] as $code => $dSpec) {

                                                foreach ($dSpec as $dKey => $dVal) {
                                                    if (!isset($tmp['__disc_' . $code . '_' . $dKey])) {
                                                        $tmp['__disc_' . $code . '_' . $dKey] = 0;
                                                    }
                                                    $tmp['__disc_' . $code . '_' . $dKey] = $dVal;


                                                    $lastDisc = ($dVal / 100) * $lastNett;
                                                    $akumDisc += $lastDisc;
                                                    $lastNett -= $lastDisc;

                                                    $tmp['__disc_' . $code . '_value'] = $lastDisc;

                                                    cekLime("eksekusi code: $code, lastDisc: $lastDisc, lastNett: $lastNett, akumDisc: $akumDisc");
                                                }

                                            }
                                            cekOrange("lastNett: $lastNett, akumDisc: $akumDisc");

                                            $tmp['lastNett'] = $lastNett;
                                            $tmp['akumDisc'] = $akumDisc;
                                        }
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
                                    $tmp[$key] = isset($row->$src) ? $row->$src : 0;
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
                        }
                        else {
                            if (isset($_GET['newQty'])) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                            }
                            else {
                                $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
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


                                    $produkQty = isset($_SESSION[$cCode]['items'][$id]['jml']) ? $_SESSION[$cCode]['items'][$id]['jml'] : 0;
                                    $_SESSION[$cCode]['items'][$id]['lastNett'] = $_SESSION[$cCode]['items'][$id][$priceConfig['mainSrc']];
                                    $_SESSION[$cCode]['items'][$id]['akumDisc'] = 0;
                                    if (sizeof($tmpDr) > 0) {
                                        $arrDiscount = array();
                                        foreach ($tmpDr as $drSpec) {
                                            $this->load->model("Mdls/" . $mdlNameSource);
                                            $sr = new $mdlNameSource();
                                            $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                                            $sr->addFilter("status='1'");
                                            $tmpSr = $sr->lookupAll($id)->result();


                                            foreach ($tmpSr as $srSpec) {
                                                if (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                                    $discountPersen = $srSpec->discount_persen;
//                                $discountQty = $srSpec->discount_qty;
                                                }
                                                else {
                                                    $discountPersen = 0;
//                                $discountQty = 0;
                                                }

                                                $arrDiscount[$id][$srSpec->var_name] = array(
                                                    "persen" => $discountPersen,
                                                    //                                "qty" => $discountQty,
                                                );

                                                cekMerah("pID: $id ::: disc_persen: $discountPersen ::: disc_qty: 0 ::: prod_qty: $produkQty");

                                            }
                                        }


                                        if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {

                                            $lastNett = $_SESSION[$cCode]['items'][$id][$priceConfig['mainSrc']];
                                            $lastDisc = 0;
                                            $akumDisc = 0;
                                            foreach ($arrDiscount[$id] as $code => $dSpec) {

                                                foreach ($dSpec as $dKey => $dVal) {
                                                    if (!isset($tmp['__disc_' . $code . '_' . $dKey])) {
                                                        $_SESSION[$cCode]['items'][$id]['__disc_' . $code . '_' . $dKey] = 0;
                                                    }
                                                    $_SESSION[$cCode]['items'][$id]['__disc_' . $code . '_' . $dKey] = $dVal;


                                                    $lastDisc = ($dVal / 100) * $lastNett;
                                                    $akumDisc += $lastDisc;
                                                    $lastNett -= $lastDisc;

                                                    $_SESSION[$cCode]['items'][$id]['__disc_' . $code . '_value'] = $lastDisc;

                                                    cekLime("eksekusi code: $code, lastDisc: $lastDisc, lastNett: $lastNett, akumDisc: $akumDisc");
                                                }

                                            }
                                            cekOrange("lastNett: $lastNett, akumDisc: $akumDisc");

                                            $_SESSION[$cCode]['items'][$id]['lastNett'] = $lastNett;
                                            $_SESSION[$cCode]['items'][$id]['akumDisc'] = $akumDisc;
                                        }
                                    }
                                }
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
                                $_SESSION[$cCode]['items'][$id]["subtotal"] = $subtotal;
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


//        arrPrint($_SESSION[$cCode]['main']);
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
        if (isset($_SESSION[$cCode]['items'][$id])) {


            $this->db->trans_start();

            $c = new MdlLockerStock();
            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
            $where = array(
                "id" => $array_hold_sebelumnya['id'],
            );
            $data_hold = array(
                "jumlah" => 0,
            );

            $c->updateData($where, $data_hold);
            cekBiru($this->db->last_query());
//die();

            $c = new MdlLockerStock();
            $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id']);
            cekHijau($this->db->last_query());


            $where = array(
                "id" => $array_active_sebelumnya['id'],
            );
            $data_active = array(
                "jumlah" => $array_active_sebelumnya['jumlah'] + $array_hold_sebelumnya['jumlah'],
            );
            $c->updateData($where, $data_active);


            $this->db->trans_complete();// or die("Gagal bro");


            $detailResetList = array(
                "items",
                "tableIn_detail",
                "tableIn_detail2",
                "tableIn_detail2_sum",
                "tableIn_detail_values",
                "tableIn_detail_values2_sum",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName][$id] = null;
                unset($_SESSION[$cCode][$sSName][$id]);
            }

        }
        if (sizeof($_SESSION[$cCode]['items']) < 1) {
//            matiHere();
//            $_SESSION[$cCode] = null;
//            unset($_SESSION[$cCode]);
        }

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
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
}