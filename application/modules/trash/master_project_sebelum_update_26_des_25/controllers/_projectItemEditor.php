<?php

require_once "Modul_Controller.php";

class _projectItemEditor extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addItem__()
    {
        $prodID = $_GET['sID'];
        $id = $_GET['bID'];
        $selectedPlace = ($_SESSION['login']['cabang_id']);
        $this->load->model("Mdls/" . "MdlProdukMergerRakitan");
        $this->load->model("Mdls/" . "MdlHargaProduk");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlFifoAverage");

        $o = new MdlProdukMergerRakitan();
        $pp = new MdlHargaProduk();
        $ff = new MdlFifoProdukJadi();
        $fa = new MdlFifoAverage();

        $tmp = $o->lookupByID($id)->result();

//        $pp->addFilter("cabang_id='$selectedPlace'");
//        $pp->addFilter("jenis_value in ('jual', 'hpp')");
//        $tmpPriceBahan = $pp->lookupAll()->result();
        $fa->addFilter("cabang_id='$selectedPlace'");
        $fa->addFilter("jenis='produk'");
        $tmpPriceBahan = $fa->lookupAll()->result();
        $tmpPrice = array();
        if (sizeof($tmpPriceBahan) > 0) {
            foreach ($tmpPriceBahan as $priceData) {
//                $tmpPrice[$priceData->produk_id] = $priceData->nilai;
//                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
                $tmpPrice[$priceData->produk_id]["hpp"] = $priceData->hpp;
            }
        }
        //------------------------------------------------------
        $ff->addFilter("cabang_id='$selectedPlace'");
        $ffTmp = $ff->lookupAll()->result();
        $ffHpp = array();
        if (sizeof($ffTmp) > 0) {
            foreach ($ffTmp as $ffSpec) {
                $ffHpp[$ffSpec->produk_id] = $ffSpec->hpp;
            }
        }
        //------------------------------------------------------

        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            $_SESSION['PROPKGED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }

        if (!array_key_exists($id, $_SESSION['PROPKGED'][$prodID]['component'])) {

            $hpp = isset($ffHpp[$id]) ? $ffHpp[$id] : isset($tmpPrice[$id]['hpp']) ? $tmpPrice[$id]['hpp'] : 0;
            $_SESSION['PROPKGED'][$prodID]['component'][$id] = array(
                "name" => $tmp[0]->nama,
                "satuan" => $tmp[0]->satuan,
                "kode" => $tmp[0]->kode,
                "price" => $tmpPrice[$id]['jual'],
                "hpp" => $hpp,
                "jml" => 1,
                "subtotal" => 1 * $tmpPrice[$id]['jual'],
                "subhpp" => 1 * $hpp,

            );
//            cekHitam("masuk sini");
        }
        else {
            if (isset($_GET['jml'])) {
//                $hrg = isset($tmpPrice[$id]) ? $tmpPrice[$id] : 0;
//                cekKuning($tmpPrice[$id]);
                $_SESSION['PROPKGED'][$prodID]['component'][$id]['jml'] = $_GET['jml'];
                $_SESSION['PROPKGED'][$prodID]['component'][$id]['subtotal'] = $_GET['jml'] * $tmpPrice[$id]['jual'];
                $_SESSION['PROPKGED'][$prodID]['component'][$id]['subhpp'] = $_GET['jml'] * $tmpPrice[$id]['hpp'];
            }
        }
//arrPrint($_SESSION['PROPKGED'][$prodID]['component']);
//        matiHEre("debuging");
        $backLink = isset($_SESSION['PROPKGED'][$prodID]['backLink']) ? $_SESSION['PROPKGED'][$prodID]['backLink'] : "#";
//        matiHere($backLink);
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                        title:'Modify Product ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: " . 'top.$' . "('<div></div>').load('" . $backLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "<script>$actionTarget</script>";
        if (isset($_GET['jml'])) {

            echo "<script>
                localStorage.btnKalkulasi = 99999999999999;
            </script>";

        }
        else {
            echo "<script>
                if($('#result2').length>0){
                    document.getElementById('result2').contentWindow.location.reload();
                    top.close_holdon()
                }
                else{
                    //location.reload()
                }
            </script>";
        }


    }

    public function addItem__OLD()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $discRp = isset($_GET['disc']) ? $_GET['disc'] : 0;
        $discPercent = isset($_GET['disc_percent']) ? $_GET['disc_percent'] : 0;
        $harga = isset($_GET['harga']) ? $_GET['harga'] : 0;
//        arrPrint($_GET);
        $cCode = $this->cCode;
        // $_SESSION[$cCode]["items2_sum"]= array();//untuk reset dulu selamacoding

        $selectorSrcModel = isset($this->configUi[$this->jenisTr]['selectorModel2']) ? $this->configUi[$this->jenisTr]['selectorModel2'] : matiHEre(" undefine model!");

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();
        // matiHEre($id);

        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][1] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck2']) ? $this->configUi[$this->jenisTr]['lockerCheck2'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue2'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue2'][1] : null;
        $fixedTableIn_subValues = config_item('transaksi_fixedTableIn_subValues') != null ? config_item('transaksi_fixedTableIn_subValues') : array();
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
                            if (!array_key_exists($id, $_SESSION[$cCode]["items2_sum"])) {
                                $jml_sudah_diambil = 0;
                                $jml_diperlukan = 1;
                                $jml_nambah = 1;
                            }
                            else {
                                if (isset($_GET['newQty'])) {
                                    $jml_sudah_diambil = $_SESSION[$cCode]["items2_sum"][$id]['jml'];
                                    $jml_diperlukan = $_GET['newQty'];
                                    $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                }
                                else {
                                    $jml_sudah_diambil = $_SESSION[$cCode]["items2_sum"][$id]['jml'];
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

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc2']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc2'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items2_sum'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        // "valid_qty" => $tmpJml,
                        "harga" => 0,
                        "nilai_untung" => 0,
                        "nilai_rugi" => 0,
                        "nilai_final_rugilaba" => 0,
                        //                        "txt_rugilaba" => "kerugian",
                        "subtotal" => 0,
                    );
                    if (sizeof($priceConfig2) > 0) {
                        $mdlName = $priceConfig2['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("jenis='produk'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig2['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig2['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            // arrPrint($rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }

                                $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
//                                 arrPrint($tmp);
//                                 matiHEre();
                            }
                        }

                    }
                    //                    arrPrint($fieldSrcs);
                    foreach ($fieldSrcs as $key => $src) {
                        //                        cekHere($row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]["items2_sum"][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    $_SESSION[$cCode]['items2_sum'][$id] = $tmp;

                }
                else {

                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums... *** ");
                        echo("LINE " . __LINE__);

                        $keyChange = "";

                        if (!isset($_SESSION[$cCode]['items2_sum'][$id]['disc'])) {
                            $_SESSION[$cCode]['items2_sum'][$id]['disc'] = 0;
                        }

                        if (!isset($_SESSION[$cCode]['items2_sum'][$id]['disc_percent'])) {
                            $_SESSION[$cCode]['items2_sum'][$id]['disc_percent'] = 0;
                        }

                        if (trim($_SESSION[$cCode]['items2_sum'][$id]['disc']) !== trim($discRp)) {
                            $keyChange = 'disc';
                        }
                        elseif (trim($_SESSION[$cCode]['items2_sum'][$id]['disc_percent']) !== ($discPercent)) {
                            $keyChange = 'disc_percent';
                        }
                        elseif (trim($_SESSION[$cCode]['items2_sum'][$id]['harga']) !== ($harga)) {
                            $keyChange = 'harga';
                        }
                        else {
                            $keyChange = '';
                        }

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items2_sum'][$id][$key] = $newValue;
                                cekKuning("$key --- $newValue");
                            }
                        }

                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]["items2_sum"][$id], $_SESSION[$cCode]["items2_sum"][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }

                        $_SESSION[$cCode]['items2_sum'][$id]['subtotal'] = $tmp['subtotal'];
//                        mati_disini(":: $keyChange :: " . $_SESSION[$cCode]['items2_sum'][$id]['harga']);
                    }

                    if (isset($_GET['newQty'])) {
                        //builder manual reciprocating diskonRp dan diskon persen
//                        mati_disini(":: $keyChange :: " . $_SESSION[$cCode]['items2_sum'][$id]['harga']);
                        $preDiskonRp = 0;
                        $subValue = $_SESSION[$cCode]['items2_sum'][$id]['jml'] * $_SESSION[$cCode]['items2_sum'][$id]['harga'];
                        switch ($keyChange) {
                            case "disc":
                                //diskon rp dirubah
                                if ($discRp * 1 > 0) {
                                    if ($discRp <= $subValue) {
                                        $preDiskonRp = $discRp;
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc_percent'] = (($discRp / $subValue) * 100);
                                    }
                                    else {
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc'] = 0;
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas harga jual total");
                                    }
                                }
                                else {
                                    $preDiskonRp = 0;
                                    $_SESSION[$cCode]['items2_sum'][$id]['disc_percent'] = 0;
                                    $_SESSION[$cCode]['items2_sum'][$id]['disc'] = 0;
                                }
                                break;
                            case "disc_percent":
                                //diskon persen dirubah
                                if ($discPercent * 1 > 0) {
                                    if ($discPercent * 1 > 100) {
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc'] = 0;
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas 100%");
                                    }
                                    else {
                                        $preDiskonRp = (($discPercent / 100) * $subValue);
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc'] = $preDiskonRp;
                                    }
                                }
                                break;
                            case "harga":
                                $_SESSION[$cCode]['items2_sum'][$id]['harga'] = $harga;
                                break;
                            default:
                                if ($discPercent * 1 > 0) {
                                    if ($discPercent * 1 > 100) {
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc'] = 0;
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas 100%");
                                    }
                                    else {
                                        $preDiskonRp = (($discPercent / 100) * $subValue);
                                        $_SESSION[$cCode]['items2_sum'][$id]['disc'] = $preDiskonRp;
                                    }
                                }
                                break;
                        }
                        $_SESSION[$cCode]['items2_sum'][$id]['jml'] = $jml;
                        $_SESSION[$cCode]['items2_sum'][$id]['subtotal'] = ($_SESSION[$cCode]['items2_sum'][$id]['jml'] * $_SESSION[$cCode]['items2_sum'][$id]['harga']) - $preDiskonRp;
                    }
                    else {

                        $_SESSION[$cCode]['items2_sum'][$id]['jml'] = $jml;
                        $_SESSION[$cCode]['items2_sum'][$id]['subtotal'] = ($_SESSION[$cCode]['items2_sum'][$id]['jml'] * ($_SESSION[$cCode]['items2_sum'][$id]['harga']));
                    }
                }
            }

            if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($_SESSION[$cCode]['items2_sum'] as $ids => $tempSes) {
                        foreach ($fixedTableIn_subValues as $key => $srcKey) {
                            $_SESSION[$cCode]['items2_sum'][$ids][$key] = $tempSes[$srcKey];
                        }
                    }
                }
            }
            if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                $_SESSION[$cCode]['main']['harga_items2'] = 0;
                foreach ($_SESSION[$cCode]['items2_sum'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga_items2'] += ($iSpec['jml'] * $iSpec['harga']);
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
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


//        arrPrintHijau($_SESSION[$cCode]['items2_sum']);
//        mati_disini(__LINE__);


        echo "<script>\n";
        echo "  if(top.document.getElementById('shopping_cart')){\n";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  \n}";
        echo "\n</script>";

    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = $this->cCode;
        if (array_key_exists($id, $_SESSION[$cCode]['items2_sum'])) {
            $_SESSION[$cCode]['items2_sum'][$id] = null;
            unset($_SESSION[$cCode]['items2_sum'][$id]);
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
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
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

    public function search()
    {
        // $prodID = $_GET['sID'];
        $key = isset($_GET['key']) ? $_GET['key'] : "";
        $selectorProcessor = $this->configUi[$this->jenisTr]["selectorProcessorItems2"];
        // arrPrintWebs($this->configUi[$this->jenisTr]);
        $this->load->model("Mdls/" . "MdlProdukMergerRakitan");
        // cekHere($selectorProcessor);
// matiHEre($this->jenisTr);

        $lsPrdSes = array();
        if (isset($_SESSION[$this->cCode]["items2_sum"]) && sizeof($_SESSION[$this->cCode]["items2_sum"]) > 0) {
            foreach ($_SESSION[$this->cCode]["items2_sum"] as $dataPrdSes) {

                $lsPrdSes[$dataPrdSes['id']] = $dataPrdSes;
            }
        }

        $o = new MdlProdukMergerRakitan();
        if (strlen($_GET['key']) < 3) {
            $this->db->limit(10);
        }
        $tmp = $o->lookupByKeyword($key)->result();
        // cekBiru($this->db->last_query());

        if (sizeof($tmp) > 0) {
            echo "<ul class='list-group'>";
            foreach ($tmp as $row) {
                $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                echo "<li class='list-group-item'>";
                echo "<a href=\"javascript:void(0)\" onclick =\"top.$('#result').load('" . MODUL_PATH . $selectorProcessor . "/" . $this->jenisTr . "?id=" . $row->id . "');\">";
                echo $row->nama;
                echo "</a> $check";
                echo "</li class='list-group-item'>";
            }
            echo "</ul class='list-group'>";
        }


        echo "<script>localStorage.lastSearch='$key'</script>";
    }

    public function viewCart()
    {
        $prodID = $this->uri->segment(3);
        if (isset($_SESSION['PROPKGED'][$prodID]['entries']) && sizeof($_SESSION['PROPKGED'][$prodID]['entries']) > 0) {
            echo("<ul class='list-group'>");
            $cnt = 0;
            $totalBiaya = 0;
            foreach ($_SESSION['PROPKGED'][$prodID]['entries'] as $id => $row) {
                $cnt++;
                echo("<li class='list-group-item'>");
                echo("<div class='row'>");
                echo("<div class='col-sm-1'>");
                echo("<a class='text-center' href=\"javascript:void(0)\" onclick=\"top.$('#result').load('" . base_url() . get_class($this) . "/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>");
                echo("</div class='col-sm-1'>");

                echo("<div class='col-sm-5'>");
                echo("<a href='" . base_url() . "BahanEditor/index/1/id/" . $id . "'>");
                echo($row['name']);
                echo("</a>");
                echo("</div class='col-sm-8'>");
                echo("<div class='col-sm-3'>");
                echo("<div class='input-group'>");
                echo("<input type='hidden' name='counter[]' value='$cnt'>");
                echo("<input type='hidden' name='id[]' value='$id'>");
                echo("<input type='hidden' name='hpp[]' value='" . $row['hpp'] . "' id='hpp_$id'>");
                echo("<input type='text' class='form-control text-right' name='jml[]' value='" . $row['jml'] . "' onkeyup =\"document.getElementById('subtotal_$id').innerHTML=(this.value*document.getElementById('hpp_$id').value);\">");
                echo("<span class='input-group-addon' style='background:#f0f0f0;'>" . $row['satuan'] . "</span>");
                echo("</div class='input-group'>");
                echo("</div class='col-sm-3'>");

                echo("<div class='col-sm-3'>");
                echo("<div class='form-control text-right' style='background:#ffddaa;' id='subtotal_$id'>" . ($row['jml'] * $row['hpp']) . "</div>");
                echo("</div class='col-sm-3'>");

                echo("</div class='row'>");
                echo("</li class='list-group-item'>");
                $totalBiaya += lgBulatkan($row['hpp'] * $row['jml']);
            }
            echo("<li class='list-group-item' style='background:#e5e5e5;'>");
            echo("<div class='row'>");
            echo("<div class='col-sm-8'>total biaya bahan");
            echo("</div class='col-sm-8'>");

            echo("<div class='col-sm-4'>");
            echo("<input type='text' class='form-control text-right' style='color:#dd3300;' value='RP. $totalBiaya' readonly>");

            echo("</div class='col-sm-4'>");
            echo("</li class='list-group-item'>");
            echo("</ul class='list-group'>");
        }
    }

    //----------------------------
    public function addItem()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $parentID = 0;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $discRp = isset($_GET['disc']) ? $_GET['disc'] : 0;
        $discPercent = isset($_GET['disc_percent']) ? $_GET['disc_percent'] : 0;
        $harga = isset($_GET['harga']) ? $_GET['harga'] : 0;
        $sub_target = isset($_GET["sub_target"]) ? $_GET["sub_target"] : matiHere("pair target tidak dikenal!");
//        arrPrint($_GET);
//        matiHere(__LINE__);
        $cCode = $this->cCode;
        // $_SESSION[$cCode]["items2_sum"]= array();//untuk reset dulu selamacoding


        switch ($_GET["sub_target"]) {
            case "produk":
                $selectorSrcModel = isset($this->configUi[$this->jenisTr]['selectorModel2']) ? $this->configUi[$this->jenisTr]['selectorModel2'] : matiHEre(" undefine model!");
                break;
            case "biaya":
                $selectorSrcModel = $this->configUi[$this->jenisTr]['shoppingCartPairedItem2']['mdlName'];
                break;
            default:
                matiHEre("gagal mengenali data model");
                break;
        }
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();
//        cekLime($selectorSrcModel);
        // matiHEre($id);
        switch ($sub_target) {
            case "produk":
                $target_session = "items2_sum";
                $targetitems2 = "items2";
                $target_komposisi = "items_komposisi";
                break;
            case "biaya":
                $target_session = "items3_sum";
                $targetitems2 = "items2";
                $target_komposisi = "items_komposisi";
                break;
            default:
                matiHEre("gagal mengenali target sesi");
                break;
        }

        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields2'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields2'][1] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck2']) ? $this->configUi[$this->jenisTr]['lockerCheck2'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue2'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue2'][1] : null;
        $fixedTableIn_subValues = config_item('transaksi_fixedTableIn_subValues') != null ? config_item('transaksi_fixedTableIn_subValues') : array();

        // karena sudah punya gerbang items2_sum, jadi langsung merubah key yang dimaksud saja dari url
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $tmpB = $b->lookupByID($id)->result();
            cekMErah($this->db->last_query());
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
//                            arrPrint($tmpC);
                            foreach ($tmpC as $row) {
                                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                $nama = $row->nama;

                                $jml_now = $row->jumlah;
                                if (!array_key_exists($id, $_SESSION[$cCode]["items2_sum"])) {
                                    $jml_sudah_diambil = 0;
                                    $jml_diperlukan = 1;
                                    $jml_nambah = 1;
                                }
                                else {
                                    if (isset($_GET['newQty'])) {
                                        $jml_sudah_diambil = $_SESSION[$cCode]["items2_sum"][$id]['jml'];
                                        $jml_diperlukan = $_GET['newQty'];
                                        $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                    }
                                    else {
                                        $jml_sudah_diambil = $_SESSION[$cCode]["items2_sum"][$id]['jml'];
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

                    $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc2']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc2'] : array("nama" => "nama");
                    if (!array_key_exists($id, $_SESSION[$cCode][$target_session])) {
                        $tmp = array(
                            "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                            "id" => $id,
                            "jml" => $tmpJml,
                            // "valid_qty" => $tmpJml,
                            "harga" => 0,
                            "nilai_untung" => 0,
                            "nilai_rugi" => 0,
                            "nilai_final_rugilaba" => 0,
                            //                        "txt_rugilaba" => "kerugian",
                            "subtotal" => 0,
                        );
                        if (sizeof($priceConfig2) > 0) {
                            $mdlName = $priceConfig2['model'];
                            $this->load->model("Mdls/" . $mdlName);
                            $h = new $mdlName();
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("jenis='produk'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig2['label']) . "')");
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $tmpH = $h->lookupAll($id)->result();

                            if (sizeof($tmpH) > 0) {
                                $rawPrices = array();
                                foreach ($tmpH as $hSpec) {
                                    foreach ($priceConfig2['key_label'] as $key => $val) {
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
                                    }

                                }
                                $prices = normalizePrices("produk", $rawPrices);
                                // arrPrint($rawPrices);
                                if (sizeof($prices) > 0) {
                                    foreach ($prices as $k => $v) {
                                        $tmp[$k] = $v;
                                    }

                                    $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
//                                 arrPrint($tmp);
//                                 matiHEre();
                                }
                            }

                        }
                        //                    arrPrint($fieldSrcs);
                        foreach ($fieldSrcs as $key => $src) {
                            //                        cekHere($row->$src . " " . $src);
                            $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                        }
                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]["items2_sum"][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode][$target_session][$id] = $tmp;

                    }
                    else {

                        if (sizeof($itemNumLabels) > 0) {
                            echo("iterating subNums... *** ");
                            echo("LINE " . __LINE__);

                            $keyChange = "";

                            if (!isset($_SESSION[$cCode][$target_session][$id]['disc'])) {
                                $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                            }

                            if (!isset($_SESSION[$cCode][$target_session][$id]['disc_percent'])) {
                                $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
                            }

                            if (trim($_SESSION[$cCode][$target_session][$id]['disc']) !== trim($discRp)) {
                                $keyChange = 'disc';
                            }
                            elseif (trim($_SESSION[$cCode][$target_session][$id]['disc_percent']) * 1 !== ($discPercent) * 1) {
                                $keyChange = 'disc_percent SES: ' . $_SESSION[$cCode][$target_session][$id]['disc_percent'] . " || now: $discPercent";
                            }
                            elseif (trim($_SESSION[$cCode][$target_session][$id]['harga']) * 1 !== ($harga) * 1) {
                                $keyChange = 'harga SES: ' . $_SESSION[$cCode][$target_session][$id]['harga'] . " || now: $harga";
                            }
                            else {
                                $keyChange = '';
                            }

                            // region cek harga jual project vs anggaran
                            switch ($keyChange) {
                                default:
                                case "harga":
                                    $hrg_beli = isset($_SESSION[$cCode][$target_session][$id]['anggaran']) ? $_SESSION[$cCode][$target_session][$id]['anggaran'] : $_SESSION[$cCode][$target_session][$id]['hpp_supplier'] * 1;
//                                    $hrg_jual = $_SESSION[$cCode][$target_session][$id]['harga'] * 1;
                                    $hrg_jual = $harga * 1;
                                    $nilai_confirm = $_SESSION[$cCode][$target_session][$id]['nilai_confirm'] * 1;
//                                    matiHere(__LINE__ . "<br> hrg_beli: $hrg_beli || hrg_jual: $hrg_jual || nilai_confirm: $nilai_confirm" );
                                    if(isset($_GET['confirm'])){
                                        $_SESSION[$cCode][$target_session][$id]['harga'] = $harga;
                                        $hrg_jual = $harga * 1;
                                    }
                                    if (($hrg_jual < $hrg_beli) && (!isset($_GET['confirm']))) {
                                        if (($hrg_jual * 1) != ($nilai_confirm * 1)) {
                                            $_SESSION[$cCode][$target_session][$id]['nilai_confirm'] = $hrg_jual;
                                            $url_current = getCurrentUrl();
                                            echo "<script>";
                                            echo "
                                            top.swal({
                                              title: 'NILAI PROJECT < NILAI ANGGARAN',
                                              html: 'Nilai Project <r>(" . number_format($hrg_jual) . ")</r> lebih kecil dari Anggaran project <r>(" . number_format($hrg_beli) . ")</r>. <br>Jika dilanjutkan, Anda mengalami kerugian pada produk ini..<br><br>Apakah Anda yakin ingin melanjutkan?',
                                              type: 'warning',
                                              showConfirmButton: true,
                                              showCancelButton: true
                                            }).then((confirm) => {
                                                  if (confirm) {
                                                        top.swal('Anda memilih untuk melanjutkan Nilai Project lebih kecil dari Anggaran Project');
                                                        top.swal.enableLoading();
                                                        top.$('#result').load('$url_current&confirm', function(){
                                                            setTimeout(function(){
                                                                top.swal.close()
                                                            }, 1000);
                                                        });
                                                  }
                                            });
                                            ";
                                            echo "</script>";
                                            die();
                                        }
                                    }
                                    break;
                            }
                            // endregion cek harga jual project vs anggaran

                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode][$target_session][$id][$key] = $newValue;
                                    cekKuning("$key --- $newValue");
                                }
                            }

                            if ($subAmountConfig != null) {
                                $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]["items2_sum"][$id], $_SESSION[$cCode]["items2_sum"][$id], 0);
                            }
                            else {
                                $tmp['subtotal'] = 0;
                            }

                            $_SESSION[$cCode][$target_session][$id]['subtotal'] = $tmp['subtotal'];

                        }

                        if (isset($_GET['newQty'])) {
                            //builder manual reciprocating diskonRp dan diskon persen
//                        mati_disini(":: $keyChange :: " . $_SESSION[$cCode][$target_session][$id]['harga']);
                            $preDiskonRp = 0;
                            $subValue = $_SESSION[$cCode][$target_session][$id]['jml'] * $_SESSION[$cCode][$target_session][$id]['harga'];
                            switch ($keyChange) {
                                case "disc":
                                    //diskon rp dirubah
                                    if ($discRp * 1 > 0) {
                                        if ($discRp <= $subValue) {
                                            $preDiskonRp = $discRp;
                                            $_SESSION[$cCode][$target_session][$id]['disc_percent'] = (($discRp / $subValue) * 100);
                                        }
                                        else {
                                            $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                            $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas harga jual total");
                                        }
                                    }
                                    else {
                                        $preDiskonRp = 0;
                                        $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
                                        $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                    }
                                    break;
                                case "disc_percent":
                                    //diskon persen dirubah
                                    if ($discPercent * 1 > 0) {
                                        if ($discPercent * 1 > 100) {
                                            $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                            $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas 100%");
                                        }
                                        else {
                                            $preDiskonRp = (($discPercent / 100) * $subValue);
                                            $_SESSION[$cCode][$target_session][$id]['disc'] = $preDiskonRp;
                                        }
                                    }
                                    break;
                                case "harga":
                                    $_SESSION[$cCode][$target_session][$id]['harga'] = $harga;
                                    break;
                                default:
                                    if ($discPercent * 1 > 0) {
                                        if ($discPercent * 1 > 100) {
                                            $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                            $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas 100%");
                                        }
                                        else {
                                            $preDiskonRp = (($discPercent / 100) * $subValue);
                                            $_SESSION[$cCode][$target_session][$id]['disc'] = $preDiskonRp;
                                        }
                                    }
                                    break;
                            }
                            $_SESSION[$cCode][$target_session][$id]['jml'] = $jml;
                            $_SESSION[$cCode][$target_session][$id]['subtotal'] = ($_SESSION[$cCode][$target_session][$id]['jml'] * $_SESSION[$cCode][$target_session][$id]['harga']) - $preDiskonRp;
                            $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['jml'] = $jml;
                            $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['harga'] = $harga;
                            $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['subtotal'] = $jml * $harga;

                            $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['jml'] = $jml;
                            $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['harga'] = $harga;
                            $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['subtotal'] = $jml * $harga;

                        }
                        else {
                            $_SESSION[$cCode][$target_session][$id]['jml'] = $jml;
                            $_SESSION[$cCode][$target_session][$id]['subtotal'] = ($_SESSION[$cCode][$target_session][$id]['jml'] * ($_SESSION[$cCode][$target_session][$id]['harga']));

                            $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['jml'] = $jml;
                            $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['harga'] = $harga;
                            $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['subtotal'] = $jml * $harga;

                            $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['jml'] = $jml;
                            $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['harga'] = $harga;
                            $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['subtotal'] = $jml * $harga;
                        }
                    }
                }

                if (sizeof($_SESSION[$cCode][$target_session]) > 0) {
                    if (sizeof($fixedTableIn_subValues) > 0) {
                        foreach ($_SESSION[$cCode][$target_session] as $ids => $tempSes) {
                            foreach ($fixedTableIn_subValues as $key => $srcKey) {
                                $_SESSION[$cCode][$target_session][$ids][$key] = $tempSes[$srcKey];
                            }
                        }
                    }
                }
//                if (sizeof($_SESSION[$cCode][$target_session]) > 0) {
//                    $_SESSION[$cCode]['main']['harga_items2'] = 0;
//                    $_SESSION[$cCode]['main']['ppn_items2'] = 0;
//                    foreach ($_SESSION[$cCode][$target_session] as $id => $iSpec) {
//                        $_SESSION[$cCode]['main']['harga_items2'] += ($iSpec['jml'] * $iSpec['harga']);
//                        $_SESSION[$cCode]['main']['ppn_items2'] += ($iSpec['jml'] * $iSpec['harga'])*(11/100);
//                    }
//                    //matiHere($_SESSION[$cCode]['main']['ppn_out_bulat']);
//                }
            }
            else {
                cekMerah("tidak ada itemnya!");
                die();
            }
        }
        else {

            $sisa_alokasi = $_SESSION[$cCode][$target_session][$id]["qty_saldo"];
            $produk_nama = $_SESSION[$cCode][$target_session][$id]["nama"];
            if ($jml > $sisa_alokasi) {
                $msg = "Maksimal pengambilan $produk_nama adalah $sisa_alokasi, anda memasukkan $jml, melebihi jumlah yang bisa diambil. Silahkan dikoreksi.";
                mati_disini($msg);
            }

            if (isset($_GET['newQty'])) {
                //builder manual reciprocating diskonRp dan diskon persen
//                        mati_disini(":: $keyChange :: " . $_SESSION[$cCode][$target_session][$id]['harga']);
                $preDiskonRp = 0;
                $subValue = $_SESSION[$cCode][$target_session][$id]['jml'] * $_SESSION[$cCode][$target_session][$id]['harga'];
                switch ($keyChange) {
                    case "disc":
                        //diskon rp dirubah
                        if ($discRp * 1 > 0) {
                            if ($discRp <= $subValue) {
                                $preDiskonRp = $discRp;
                                $_SESSION[$cCode][$target_session][$id]['disc_percent'] = (($discRp / $subValue) * 100);
                            }
                            else {
                                $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas harga jual total");
                            }
                        }
                        else {
                            $preDiskonRp = 0;
                            $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
                            $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                        }
                        break;
                    case "disc_percent":
                        //diskon persen dirubah
                        if ($discPercent * 1 > 0) {
                            if ($discPercent * 1 > 100) {
                                $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas 100%");
                            }
                            else {
                                $preDiskonRp = (($discPercent / 100) * $subValue);
                                $_SESSION[$cCode][$target_session][$id]['disc'] = $preDiskonRp;
                            }
                        }
                        break;
                    case "harga":
                        $_SESSION[$cCode][$target_session][$id]['harga'] = $harga;
                        break;
                    default:
                        if ($discPercent * 1 > 0) {
                            if ($discPercent * 1 > 100) {
                                $_SESSION[$cCode][$target_session][$id]['disc'] = 0;
                                $_SESSION[$cCode][$target_session][$id]['disc_percent'] = 0;
//                                        matiHere("moso diskon diatas 100%");
                            }
                            else {
                                $preDiskonRp = (($discPercent / 100) * $subValue);
                                $_SESSION[$cCode][$target_session][$id]['disc'] = $preDiskonRp;
                            }
                        }
                        break;
                }
                $_SESSION[$cCode][$target_session][$id]['jml'] = $jml;
                $_SESSION[$cCode][$target_session][$id]['qty'] = $jml;
                $_SESSION[$cCode][$target_session][$id]['subtotal'] = ($_SESSION[$cCode][$target_session][$id]['jml'] * $_SESSION[$cCode][$target_session][$id]['harga']) - $preDiskonRp;
                $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['jml'] = $jml;
                $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['harga'] = $harga;
                $_SESSION[$cCode][$targetitems2][$parentID][$sub_target][$id]['subtotal'] = $jml * $harga;

                $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['jml'] = $jml;
                $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['harga'] = $harga;
                $_SESSION[$cCode][$target_komposisi][$parentID][$sub_target][$id]['subtotal'] = $jml * $harga;
            }
            else {

                $_SESSION[$cCode][$target_session][$id]['jml'] = $jml;
                $_SESSION[$cCode][$target_session][$id]['qty'] = $jml;
                $_SESSION[$cCode][$target_session][$id]['subtotal'] = ($_SESSION[$cCode][$target_session][$id]['jml'] * ($_SESSION[$cCode][$target_session][$id]['harga']));
            }
            if (sizeof($_SESSION[$cCode][$target_session]) > 0) {
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($_SESSION[$cCode][$target_session] as $ids => $tempSes) {
                        foreach ($fixedTableIn_subValues as $key => $srcKey) {
                            $_SESSION[$cCode][$target_session][$ids][$key] = $tempSes[$srcKey];
                        }
                    }
                }
            }
            if (sizeof($_SESSION[$cCode][$target_session]) > 0) {
                $_SESSION[$cCode]['main']['harga_items2'] = 0;
                foreach ($_SESSION[$cCode][$target_session] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga_items2'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }
        }
//arrPrint($_SESSION[$cCode][$targetitems2][$parentID]);
        $harga_total = 0;
        $harga_anggran_biaya = 0;
        $harga_anggran_material = 0;
        foreach ($_SESSION[$cCode][$targetitems2][$parentID] as $jnProduk => $datas) {
            foreach ($datas as $ids_data => $temp_sess) {
                $harga_total += $temp_sess["jml"] * $temp_sess["harga"];
                $harga_anggran_material += $jnProduk == "produk" ? $temp_sess["jml"] * $temp_sess["hpp_supplier"] : 0;
                $harga_anggran_biaya += $jnProduk == "biaya" ? $temp_sess["jml"] * $temp_sess["anggaran"] : 0;
            }
//            if(!isset())
//            arrprint($datas);
        }

//        matiHere($harga_total);
        $_SESSION[$cCode]['main']['harga_items2'] = $harga_total;
        $_SESSION[$cCode]['main']['ppn_items2'] = $harga_total * (11 / 100);
        $_SESSION[$cCode]['main']['harga_anggran_material'] = $harga_anggran_material;
        $_SESSION[$cCode]['main']['harga_anggaran_biaya'] = $harga_anggran_biaya;
        $_SESSION[$cCode]['main']['total_anggaran'] = $harga_anggran_biaya + $harga_anggran_material;


//        matiHere(__LINE__);

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
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        if (isset($_GET["r"])) {
            echo "<script>\n";
            echo "  if(top.document.getElementById('shopping_cart')){\n";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  \n}";
            echo "\n</script>";
        }
        else {
            echo "\n<script>\n";
            echo "var idsRow=$id;top.$(\"tr#tr_\"+idsRow+\" > td > input\").css({'background': '','color': '343434'});";
            echo "\n</script>";
        }

    }
}