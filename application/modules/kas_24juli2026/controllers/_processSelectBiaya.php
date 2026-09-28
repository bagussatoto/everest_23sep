<?php

require_once "Modul_Controller.php";

class _processSelectBiaya extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;;

    }

    /**
     * Reset state relasi saat sumber item diganti agar tidak membawa data PO sebelumnya.
     */
    private function resetRelasiSwitchState($cCode, $relasiTitipanPo = array())
    {
        if (isset($_SESSION[$cCode]['items'])) {
            unset($_SESSION[$cCode]['items']);
        }

        $targetGate = isset($relasiTitipanPo['targetGate']) ? $relasiTitipanPo['targetGate'] : "items6_sum";
        if (isset($_SESSION[$cCode][$targetGate])) {
            unset($_SESSION[$cCode][$targetGate]);
        }

        if (isset($_SESSION[$cCode]['main_elements'])) {
            foreach (array("actionType", "option_nota", "referensi_so") as $elName) {
                if (isset($_SESSION[$cCode]['main_elements'][$elName])) {
                    unset($_SESSION[$cCode]['main_elements'][$elName]);
                }
            }
        }

        if (isset($_SESSION[$cCode]['main']) && is_array($_SESSION[$cCode]['main'])) {
            $resetPrefix = array(
                "actionType",
                "option_nota",
                "referensi_so",
                "targetSo",
                "source_reference",
                "source_extern2",
            );
            foreach (array_keys($_SESSION[$cCode]['main']) as $mainKey) {
                foreach ($resetPrefix as $prefix) {
                    if (strpos($mainKey, $prefix) === 0) {
                        unset($_SESSION[$cCode]['main'][$mainKey]);
                        break;
                    }
                }
            }
        }
    }

    public function select()
    {
        $this->load->helper("he_angka");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $selectorRegistries = isset($this->configUi[$this->jenisTr]['selectorRegistries']) ? $this->configUi[$this->jenisTr]['selectorRegistries'] : array();
        $singleSelectorAllow = isset($this->configUi[$this->jenisTr]['singleSelectorAllow']) ? $this->configUi[$this->jenisTr]['singleSelectorAllow'] : false;

        if($singleSelectorAllow == true){
            if(isset($_SESSION[$cCode]['items'][$id])){
                unset($_SESSION[$cCode]['items']);
            }
        }

        switch ($this->jenisTr) {
            case "4643":
                if (isset($_SESSION[$cCode]["main"]["referensi_so"]) && ($_SESSION[$cCode]["main"]["referensi_so"] > 0)) {
                    $referensi_so_id = $_SESSION[$cCode]["main"]["referensi_so"];
                    $this->load->model("ReComs/ReComTitipanUangMukaSupplier");
                    $rcm = New ReComTitipanUangMukaSupplier();
                    $rcm->pair($referensi_so_id, $_GET["harga"]);
                    $rcm->exec();
                }
                break;
        }

        cekHere("model: $selectorSrcModel");
        $mdlBefore = $selectorSrcModel;
        if(isset($_SESSION[$cCode]['main']['selectorSrcModelDinamis'])){
            $selectorSrcModel = $_SESSION[$cCode]['main']['selectorSrcModelDinamis'];
        }

        if($mdlBefore != $selectorSrcModel){
            cekHere("model switch dari $mdlBefore ke : $selectorSrcModel");
        }

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $shoppingCartEditableValidator = isset($this->configUi[$this->jenisTr]['shoppingCartEditableValidator'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableValidator'][1] : array();
        $shoppingCartEditableValidatorLabel = isset($this->configUi[$this->jenisTr]['shoppingCartEditableValidatorLabel'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableValidatorLabel'][1] : array();
        $shoppingCartEditableProsentaseValidator = isset($this->configUi[$this->jenisTr]['shoppingCartEditableProsentaseValidator'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableProsentaseValidator'][1] : array();
        $shoppingCartEditableProsentaseValidatorLabel = isset($this->configUi[$this->jenisTr]['shoppingCartEditableProsentaseValidatorLabel'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableProsentaseValidatorLabel'][1] : array();

        $tmpB = $b->lookupByID($id)->result();
        showLast_query("biru");

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $coa_code = isset($row->coa_code) && strlen($row->coa_code) > 0 ? $row->coa_code : "";
                $is_reference = isset($row->is_reference) ? $row->is_reference : 0;
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
//                    cekHere($this->db->last_query());

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
                        matiAlert("tidak ditemukan item " . $row->nama . " di locker stock.");
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
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            $new_key = $key . "_" . $coa_code;
                            $tmp[$new_key] = $newValue;
                        }
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

                    $pakai_ini = 1;
                    if ($pakai_ini == 1) {
                        if (sizeof($shoppingCartEditableValidator) > 0) {
                            foreach ($shoppingCartEditableValidator as $entry => $source) {
                                $entry_nilai = isset($_GET[$entry]) ? $_GET[$entry] : 0;
                                if (array_key_exists($coa_code, $source)) {
                                    $item_key = $source[$coa_code];
                                    $source_nilai = isset($_SESSION[$cCode]['items'][$id][$item_key]) ? $_SESSION[$cCode]['items'][$id][$item_key] : 0;
                                    $label = isset($shoppingCartEditableValidatorLabel[$coa_code]) ? $shoppingCartEditableValidatorLabel[$coa_code] : "";
                                    if ($entry_nilai > $source_nilai) {
                                        $selisih = reformatExponent($entry_nilai - $source_nilai);
                                        if ($selisih > 5) {
                                            $msg = "$label <br><span class='meta'>code: " . __LINE__ . "</span>";
                                            matiAlert($msg);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $pakai_ini = 1;
                    if ($pakai_ini == 1) {
                        if (sizeof($shoppingCartEditableProsentaseValidator) > 0) {

                            $source = $shoppingCartEditableProsentaseValidator["source"];
                            $target = $shoppingCartEditableProsentaseValidator["target"];
                            $keycek = $shoppingCartEditableProsentaseValidator["key_cek"];
                            $prosentase = $shoppingCartEditableProsentaseValidator["prosentase"];
                            $source_nilai = isset($_GET[$source]) ? $_GET[$source] : 0;
                            $target_nilai = isset($_SESSION[$cCode]["main"][$target]) ? $_SESSION[$cCode]["main"][$target] : 0;
                            $target_nilai_min = ((100 - $prosentase) / 100) * $target_nilai;
                            $target_nilai_max = (100 / (100 - $prosentase)) * $target_nilai;
                            $target_nilai_min_f = number_format($target_nilai_min);
                            $target_nilai_max_f = number_format($target_nilai_max);
                            if ($source_nilai > $target_nilai_max) {
                                $msg = "Isikan jumlah kas/uang yang diterima dengan benar (sesuai jumlah pajak yang dibayar), minimal $target_nilai_min_f, maksimal $target_nilai_max_f. code: " . __LINE__;
                                mati_disini($msg);
                            }
                            elseif ($source_nilai < $target_nilai_min) {
                                $msg = "Isikan jumlah kas/uang yang diterima dengan benar (sesuai jumlah pajak yang dibayar), minimal $target_nilai_min_f, maksimal $target_nilai_max_f. code: " . __LINE__;
                                mati_disini($msg);
                            }
//                        mati_disini("[$target_nilai_min] [$target_nilai_max] [$source_nilai] [$source]");
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                                $new_key = $key . "_" . $coa_code;
                                $_SESSION[$cCode]['items'][$id][$new_key] = $newValue;
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

                        //manual protect jika harga > source_main_harga
                        if(isset($_SESSION[$cCode]['items'][$id]['source_main_harga']) && $_SESSION[$cCode]['items'][$id]['harga'] > $_SESSION[$cCode]['items'][$id]['source_main_harga']){
                            $_SESSION[$cCode]['items'][$id]['harga'] = $_SESSION[$cCode]['items'][$id]['source_main_harga'];
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
                //------
                if ($is_reference == 1) {
                    $_SESSION[$cCode]["main"]["wajib_reference"] = $is_reference;
                    $_SESSION[$cCode]["main"]["wajib_reference_id"] = $row->id;
                    $_SESSION[$cCode]["main"]["wajib_reference_label"] = $row->nama;
                    $_SESSION[$cCode]["main"]["reference_gate"] = 2;//lihat ditabel dta_reference_gate
                }
                else{
                    $_SESSION[$cCode]["main"]["reference_gate"] = 1;//lihat ditabel dta_reference_gate
                }
                //------

                if (sizeof($selectorRegistries) > 0) {
                    $this->load->model("MdlTransaksi");
                    $trreg = New MdlTransaksi();
                    $trreg->setFilters(array());
                    $trreg->setJointSelectFields("transaksi_id, main");
                    $trreg->addFilter("transaksi_id='$id'");
                    $trregTmp = $trreg->lookupDataRegistries()->result();
                    if (sizeof($trregTmp) > 0) {
                        $main = blobDecode($trregTmp[0]->main);
                        if (sizeof($main) > 0) {
                            foreach ($selectorRegistries as $keyval => $vals) {
                                $_SESSION[$cCode]['items'][$id][$keyval] = isset($main[$vals]) ? $main[$vals] : 0;
                            }
                        }
                    }
                }

//                if($coa_code == "1010050010"){
//                    $_SESSION[$cCode]['main']['defaultOptionReference'] = "2";// tembak dulu...
//                }
//
            }

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }

                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
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
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        //------

        //------

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
        $arrTrID = isset($_GET['trs']) ? unserialize(base64_decode($_GET['trs'])) : array();
        $arrMain = isset($_GET['main']) ? unserialize(base64_decode($_GET['main'])) : array();

        $cCode = $this->cCode;;

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
                // cekHere($this->db->last_query());
                // arrPrint($tmpB);

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
                                        // cekHere($this->db->last_query());
                                    }
                                    //  endregion locker hold


                                    $this->db->trans_complete() or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;

                                }
                            }
                            else {
                                matiAlert("tidak ditemukan item " . $row->nama . " di locker stock.");
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
                                            cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            cekKuning("$val2 direplace dengan NOL");
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

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = $this->cCode;;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();

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
            $coa_code = $_SESSION[$cCode]['items'][$id]["coa_code"];
            $_SESSION[$cCode]['items'][$id][$new_key] = $newValue;
            if (sizeof($itemNumLabels) > 0) {
                foreach ($itemNumLabels as $key => $label) {
                    $new_key = $key . "_" . $coa_code;
                    $_SESSION[$cCode]['main'][$new_key] = null;
                    unset($_SESSION[$cCode]['main'][$new_key]);
                }
            }

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

    public function updateValues()
    {
        echo "---------------------------your input params needed------------------------------";
        arrprint($_POST);
        $cCode = $this->cCode;;
        $rawParam = $_POST['param'];
        arrPrint($rawParam);
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }

    public function blockedSelect()
    {

        //untuk buka modalnya
//        arrPrint($this->uri->segment_array());
//        cekHitam($this->modul);

        $id = isset($_GET['id']) ? $_GET['id'] : "";

        $cCode = $this->cCode;
        $shopingCartFaktur = isset($this->configUi[$this->jenisTr]['addMainSource'][1]) ? $this->configUi[$this->jenisTr]['addMainSource'][1] : array();
        $selectorProcessorTarget = isset($this->configUi[$this->jenisTr]['selectorProcessorTarget']) ? $this->configUi[$this->jenisTr]['selectorProcessorTarget'] : array();

        arrPrint($shopingCartFaktur);
        //region efaktur
        $dataFaktur = array();
        if (count($shopingCartFaktur) > 0) {
//            arrPrint($shopingCartFaktur);
//            cekMerah(__LINE__);
//            if ($_SESSION[$cCode]["main"]["dpp_final"] == 0) {
//                cekHitam("masuk sini");
////                $_SESSION[$cCode]["main"]["skip_faktur"]="true";
//            }
//            else {
            foreach ($shopingCartFaktur["fields"] as $key_faktur => $faktur_labels) {
                $dataFaktur[$key_faktur] = isset($_SESSION[$cCode]["main"][$key_faktur]) ? $_SESSION[$cCode]["main"][$key_faktur] : "";
            }
            $validator_js = "var error=0,resError=[];\n";
            $faktur = "";
            if (count($shopingCartFaktur) > 0) {
//                if (isset($showFormulirFaktur) && ($showFormulirFaktur == true)) {
                $shopingCartFakturItems = array();
                $faktur .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
                $faktur .= "<table class='table '>";
                $faktur .= "<tr class='bg-primary'>";
                foreach ($shopingCartFaktur["fields"] as $ff => $ff_abels) {

                    $man = isset($shopingCartFaktur["editableFieldsMandatori"][$ff]) ? " <span class='text-bold text-red'>(* wajib isi)</span>" : "";
                    $faktur .= "<th>$ff_abels$man</th>";
                }
                $faktur .= "</tr>";
                $faktur .= "<tr>";
                $linkFaktur = isset($shopingCartFaktur["editProcess"]) ? MODUL_PATH . $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr : "";

                $whenInAction = "";

                foreach ($shopingCartFaktur["fields"] as $fff => $f_labels) {
//                cekMerah($fff);
                    $labels = "";
//                    if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                    if (isset($shopingCartFaktur["editableFields2"][$fff])) {
                        $inputType = $shopingCartFaktur["editableFields2"][$fff];
                        $defValues = isset($shopingCartFakturItems[$fff]) ? $shopingCartFakturItems[$fff] : "";
                        if ($shopingCartFaktur["editableFields2"][$fff] == "checkbox") {
                            $classinputType = "";
                            $labels = "tic disini jika faktur belum tersedia";
                            $vals = "checked";
                            $checked = isset($shopingCartFakturItems[$fff]) && $shopingCartFakturItems[$fff] == "true" ? $vals : "";
                        }
                        else {
                            $classinputType = "form-control ";
                            $labels = "";
                            $vals = "value";
                            $checked = "";
                        }
//                            $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name)\">";
                        $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked >";
                    }
                    else {
                        $value = isset($shopingCartFakturItems[$fff]) ? formatField($fff, $shopingCartFakturItems[$fff]) : 0;
                    }

                    $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'>$labels </span></td>";

                    if (isset($shopingCartFaktur["editableFieldsMandatori"][$fff])) {
                        $reason = $shopingCartFaktur["editableFieldsMandatori"][$fff];
                        $validator_js .= "
                            var $fff = top.$('#$fff').val();
                            if( top.$('#$fff').val() == '' ){
                                error++;
                                resError.push('$reason');
                            }\n
                        ";
                    }
                    else {
                        $validator_js .= "
                            var $fff = top.$('#$fff').val();\n
                        ";
                    }

                    if ($whenInAction == "") {
                        $whenInAction .= "top.$.when( top.$.ajax('" . MODUL_PATH . $selectorProcessorTarget . "/" . $this->jenisTr . "?selector&id=" . $id . "&minValue=0') )\n";
                        $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . $selectorProcessorTarget . "/" . $this->jenisTr . "?id=" . $id . "&newQty=0&harga='+$fff) )\n";
                        $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                        $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . "_processPihak/selectTaxes/" . $this->jenisTr . "?val=uang_muka_produk&p=uang_muka_produk') )\n";
                        $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . "_processPihak/selectTaxes/" . $this->jenisTr . "?val=include_ppn&p=include_ppn') )\n";
                    }
                    else {
                        if ($fff == "dateFaktur") {
                            $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr . "?nilai='+$fff+'&nama=dateFaktur') )\n";
                        }
                        else {
                            if ($fff == "eFaktur") {
                                $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . $shopingCartFaktur["editProcess"] . "/" . $this->jenisTr . "?nilai='+$fff+'&nama=eFaktur') )\n";
                            }
                            else {
                                $whenInAction .= ".then( top.$.ajax('" . MODUL_PATH . "_shoppingCart/recordAddRow/" . $this->jenisTr . "/$fff/?val='+$fff) )\n";
                            }
                        }
                    }
                }

                $whenInAction .= ".then( dialog.close() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                $whenInAction .= ".then( top.swal.close() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";
                $whenInAction .= ".then( top.$('.glyphicon.glyphicon-refresh').parent().click() )\n";

                $faktur .= "</tr>";
                $faktur .= "</table>";
                $faktur .= "</div>";
                $faktur .= "";
                $faktur .= "<div id='panel_error' class='panel panel-danger hidden'>";
                $faktur .= "<div class='panel-heading fa-2x'><i class='fa fa-warning text-yellow blink'></i> PERHATIAN</div>";
                $faktur .= "<div class='panel-body bg-red'>";
                $faktur .= "</div>";
                $faktur .= " </div>";
            }
        }

//        cekHere($faktur);
//        $faktur="999";


        //endregion
        $addClick = "
                    top.BootstrapDialog.show({
                        title:'New xxxx',
                        message: \"$faktur\",
                        draggable:true,
                        closable:true,
                        type:top.BootstrapDialog.TYPE_SUCCESS,
                        size: top.BootstrapDialog.SIZE_WIDE,
                        onOpen: function(){

                        },
                        buttons: [{
                            label: 'BATAL',
                            cssClass: 'btn-danger pull-left',
                            action: function(dialog) {
                                console.log(dialog)
                                dialog.close();
                            }
                        }, {
                            label: 'SIMPAN & LANJUT',
                            cssClass: 'btn-success',
                            action: function(dialog) {
                                top.$('#panel_error').addClass('hidden');
                                top.swal('prosess.. mohon tunggu validasi data.');
                                top.swal.enableLoading();

                                $validator_js

                                if(!error){
                                    console.log('TIDAK ADA ERROR');
                                    $whenInAction

                                }
                                else{
                                    var errText = '<ol>'
                                    top.jQuery.each(resError, function(aa, res){
                                        console.error('aa: '+ aa + '  ||  res: ' + res);
                                        errText += \"<li class='text-white text-bold font-size-1-5'>\" + res + '</li>';
                                    });
                                    errText += '</ol>'
                                    top.$('.panel-body').html(errText);

                                    setTimeout(function(){
                                        top.$('#panel_error').removeClass('hidden');
                                        top.swal.close();
                                    }, 1000)

                                }

                            }
                        }],
                    });
                                    ";
        echo "<script>" . $addClick . "</script>";
//        matiHere(__LINE__);
    }

    public function selectReplace()
    {
        $this->load->helper("he_angka");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

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
        $shoppingCartEditableValidator = isset($this->configUi[$this->jenisTr]['shoppingCartEditableValidator'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableValidator'][1] : array();
        $shoppingCartEditableValidatorLabel = isset($this->configUi[$this->jenisTr]['shoppingCartEditableValidatorLabel'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableValidatorLabel'][1] : array();
        $relasiTitipanPo = isset($this->configUi[$this->jenisTr]['relasiTitipanPo']) ? $this->configUi[$this->jenisTr]['relasiTitipanPo'] : array();

        $tmpB = $b->lookupByID($id)->result();
        showLast_query("biru");
        // reset state relasi/elemen supaya tidak terbawa dari pemilihan sebelumnya
        $this->resetRelasiSwitchState($cCode, $relasiTitipanPo);

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $coa_code = isset($row->coa_code) && strlen($row->coa_code) > 0 ? $row->coa_code : "";
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
//                    cekHere($this->db->last_query());

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
                        matiAlert("tidak ditemukan item " . $row->nama . " di locker stock.");
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
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            $new_key = $key . "_" . $coa_code;
                            $tmp[$new_key] = $newValue;
                        }
                    }

                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                    $_SESSION[$cCode]['main']["source_extern2_id"] = $row->extern2_id;// id po nya
                    $_SESSION[$cCode]['main']["source_extern2_nama"] = $row->extern2_nama;// nomer po nya

//                    $_SESSION[$cCode]['main']["source_reference_id"] = $row->extern2_id;// id transaksi titipan nya
//                    $_SESSION[$cCode]['main']["source_reference_nama"] = $row->extern2_nama;// nomer transaksi titipan nya

                }
                else {
//                    if (isset($_GET['newQty'])) {
//                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
//                    }
//                    else {
//                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
//                    }
                    $pakai_ini = 1;
                    if ($pakai_ini == 1) {
                        if (sizeof($shoppingCartEditableValidator) > 0) {
                            foreach ($shoppingCartEditableValidator as $entry => $source) {
                                $entry_nilai = isset($_GET[$entry]) ? $_GET[$entry] : 0;
                                if (array_key_exists($coa_code, $source)) {
                                    $item_key = $source[$coa_code];
                                    $source_nilai = isset($_SESSION[$cCode]['items'][$id][$item_key]) ? $_SESSION[$cCode]['items'][$id][$item_key] : 0;
                                    $label = isset($shoppingCartEditableValidatorLabel[$coa_code]) ? $shoppingCartEditableValidatorLabel[$coa_code] : "";
                                    if ($entry_nilai > $source_nilai) {
                                        $selisih = reformatExponent($entry_nilai - $source_nilai);
                                        if ($selisih > 5) {
                                            $msg = "$label <br><span class='meta'>code: " . __LINE__ . "</span>";
                                            matiAlert($msg);
                                        }

                                    }

                                }
                            }
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                                $new_key = $key . "_" . $coa_code;
                                $_SESSION[$cCode]['items'][$id][$new_key] = $newValue;
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

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }

                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
            }

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }

            if (sizeof($relasiTitipanPo) > 0) {
                if (isset($relasiTitipanPo["enabled"]) && ($relasiTitipanPo["enabled"] == true)) {
                    $rekening = $relasiTitipanPo["rekening"];
                    $jenis_reference = $relasiTitipanPo["jenis_reference"];
                    $kolom = $relasiTitipanPo["kolom"];
                    $targetGate = $relasiTitipanPo["targetGate"];
                    $comName = $relasiTitipanPo["comName"];
                    $this->load->helper("he_mass_table");
                    $this->load->model("Coms/$comName");
                    $cm = New $comName();
                    $cm->addFilter("debet>0");
                    $cm->addFilter("jenis='$jenis_reference'");
                    $cm->addFilter("extern2_id=" . $_SESSION[$cCode]['main']["source_extern2_id"]);
                    $cmTmp = $cm->fetchMovesByTransIDs($rekening, array());// tembak dulu....
                    if (sizeof($cmTmp) > 0) {
                        foreach ($cmTmp as $ii => $iiSpec) {
                            foreach ($kolom as $kolom_data) {
                                $_SESSION[$cCode][$targetGate][$ii][$kolom_data] = isset($iiSpec->$kolom_data) ? $iiSpec->$kolom_data : "";
                                $_SESSION[$cCode][$targetGate][$ii]["source_reference_id"] = $iiSpec->transaksi_id;// id transaksi titipan nya
                                $_SESSION[$cCode][$targetGate][$ii]["source_reference_nama"] = $iiSpec->transaksi_no;// nomer transaksi titipan nya
                            }
                        }
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

    public function selectReplaceUm()
    {
        $this->load->helper("he_angka");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $selectorSrcModel = $this->uri->segment(5);

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
//        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $shoppingCartEditableValidator = isset($this->configUi[$this->jenisTr]['shoppingCartEditableValidator'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableValidator'][1] : array();
        $shoppingCartEditableValidatorLabel = isset($this->configUi[$this->jenisTr]['shoppingCartEditableValidatorLabel'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableValidatorLabel'][1] : array();
        $relasiTitipanPo = isset($this->configUi[$this->jenisTr]['relasiTitipanPo']) ? $this->configUi[$this->jenisTr]['relasiTitipanPo'] : array();

        $tmpB = $b->lookupByID($id)->result();
        showLast_query("biru");
        // reset state relasi/elemen supaya tidak terbawa dari pemilihan sebelumnya
        $this->resetRelasiSwitchState($cCode, $relasiTitipanPo);

        $d_label = "";
        $d_jenisTr = "";

        //auto select element
        switch($selectorSrcModel){
            case "MdlCustomerUangMuka":
                $elName = "sourceUmJenis";
                $mdlName = "MdlStaticPreNotaJenis";
                $key = "is_titipan";
                $_SESSION[$cCode]['main']['sourceUmJenis'] = $key;
                $_SESSION[$cCode]['main']['selectorSrcModelDinamis'] = $selectorSrcModel;
                $d_label = "uang muka konsumen";
                $d_jenisTr = "04467";
                heFetchElement_modul($this->jenisTr, $elName, $mdlName, $key, $this->configUiJenis);
                break;
            case "MdlPaymentSource":
                $elName = "sourceUmJenis";
                $mdlName = "MdlStaticPreNotaJenis";
                $is_ppn = 0;
                if(!empty($tmpB)){
                    foreach($tmpB as $row){
                        $is_ppn += $row->ppn;
                    }
                }

                if($is_ppn*1 > 0){
                    $key = "is_uangmuka_reguler_ppn";
                }
                else{
                    $key = "is_uangmuka_reguler";
                }

                $_SESSION[$cCode]['main']['sourceUmJenis'] = $key;
                $_SESSION[$cCode]['main']['selectorSrcModelDinamis'] = $selectorSrcModel;
                $d_label = "uang muka konsumen";
                $d_jenisTr = "04467";
                heFetchElement_modul($this->jenisTr, $elName, $mdlName, $key, $this->configUiJenis);
                break;
            case "MdlPaymentSourceProject":
                $elName = "sourceUmJenis";
                $mdlName = "MdlStaticPreNotaJenis";
                $key = "is_uangmuka_project";
                $_SESSION[$cCode]['main']['sourceUmJenis'] = $key;
                $_SESSION[$cCode]['main']['selectorSrcModelDinamis'] = $selectorSrcModel;
                $d_label = "uang muka konsumen";
                $d_jenisTr = "04467";
                heFetchElement_modul($this->jenisTr, $elName, $mdlName, $key, $this->configUiJenis);
                break;
        }

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $coa_code = isset($row->coa_code) && strlen($row->coa_code) > 0 ? $row->coa_code : "";
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
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
                            }
                            //  endregion locker hold
                            $this->db->trans_complete() or die("Gagal bro");
                            $tmpJml = $jml_diperlukan;
                        }
                    }
                    else {
                        matiAlert("tidak ditemukan item " . $row->nama . " di locker stock.");
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
                        "subtotal" => 0,
                        "ppn" => $row->ppn,
                    );
                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();
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
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            $new_key = $key . "_" . $coa_code;
                            $tmp[$new_key] = $newValue;
                        }
                    }
                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    $tmp["source_main_harga"] = $tmp['harga']; //nyimpan hrg utama, tidak terpengaruh saat di ganti2
                    $tmp["tabel_id"] = $tmp['id'];
                    $tmp["d_jenisTr"] = $d_jenisTr;
                    $tmp["d_label"] = $d_label;
                    $tmp["d_transaksi_id"] = $row->transaksi_id;

                    $_SESSION[$cCode]['items'][$id] = $tmp;
                    $_SESSION[$cCode]['main']["source_extern2_id"] = $tmp['extern2_id'];// id po nya
                    $_SESSION[$cCode]['main']["source_extern2_nama"] = $tmp['extern2_nama'];// nomer po nya
                    $_SESSION[$cCode]['main']["source_main_harga"] = $tmp['harga'];
                }
                else {
                    $pakai_ini = 1;
                    if ($pakai_ini == 1) {
                        if (sizeof($shoppingCartEditableValidator) > 0) {
                            foreach ($shoppingCartEditableValidator as $entry => $source) {
                                $entry_nilai = isset($_GET[$entry]) ? $_GET[$entry] : 0;
                                if (array_key_exists($coa_code, $source)) {
                                    $item_key = $source[$coa_code];
                                    $source_nilai = isset($_SESSION[$cCode]['items'][$id][$item_key]) ? $_SESSION[$cCode]['items'][$id][$item_key] : 0;
                                    $label = isset($shoppingCartEditableValidatorLabel[$coa_code]) ? $shoppingCartEditableValidatorLabel[$coa_code] : "";
                                    if ($entry_nilai > $source_nilai) {
                                        $selisih = reformatExponent($entry_nilai - $source_nilai);
                                        if ($selisih > 5) {
                                            $msg = "$label <br><span class='meta'>code: " . __LINE__ . "</span>";
                                            matiAlert($msg);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                $new_key = $key . "_" . $coa_code;
                                $_SESSION[$cCode]['items'][$id][$new_key] = $newValue;
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
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
                $nilai_untung = 0;
                $nilai_rugi = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $nilai_untung += $iSpec['nilai_untung'];
                    $nilai_rugi += $iSpec['nilai_rugi'];
                }
                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
            }
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }
            if (sizeof($relasiTitipanPo) > 0) {
                if (isset($relasiTitipanPo["enabled"]) && ($relasiTitipanPo["enabled"] == true)) {
                    $rekening = $relasiTitipanPo["rekening"];
                    $jenis_reference = $relasiTitipanPo["jenis_reference"];
                    $kolom = $relasiTitipanPo["kolom"];
                    $targetGate = $relasiTitipanPo["targetGate"];
                    $comName = $relasiTitipanPo["comName"];
                    $this->load->helper("he_mass_table");
                    $this->load->model("Coms/$comName");
                    $cm = New $comName();
                    $cm->addFilter("debet>0");
                    $cm->addFilter("jenis='$jenis_reference'");
                    $cm->addFilter("extern2_id=" . $_SESSION[$cCode]['main']["source_extern2_id"]);
                    $cmTmp = $cm->fetchMovesByTransIDs($rekening, array());// tembak dulu....
                    if (sizeof($cmTmp) > 0) {
                        foreach ($cmTmp as $ii => $iiSpec) {
                            foreach ($kolom as $kolom_data) {
                                $_SESSION[$cCode][$targetGate][$ii][$kolom_data] = isset($iiSpec->$kolom_data) ? $iiSpec->$kolom_data : "";
                                $_SESSION[$cCode][$targetGate][$ii]["source_reference_id"] = $iiSpec->transaksi_id;// id transaksi titipan nya
                                $_SESSION[$cCode][$targetGate][$ii]["source_reference_nama"] = $iiSpec->transaksi_no;// nomer transaksi titipan nya
                            }
                        }
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
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }
}

