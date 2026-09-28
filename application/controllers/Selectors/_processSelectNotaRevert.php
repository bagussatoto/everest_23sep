<?php

class _processSelectNotaRevert extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
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
        );
        foreach ($this->whitelist as $list) {
            $this->whitelistMain[$list] = isset($_SESSION[$cCode]['main'][$list]) ? $_SESSION[$cCode]['main'][$list] : "";
        }
        $this->jenisTrException = array("9911", "9912");
    }

    public function select_backup()
    {

        $this->load->library("FieldCalculator");
        $this->load->model("MdlTransaksi");

        $cal = new FieldCalculator();
        $trs = new MdlTransaksi();

        $transID_ref = $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        cekHitam(":: $transID_ref ::");
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

        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $referenceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceFields'] : null;
//        $externalConfig = isset($this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues']) ? $this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues'] : array();
        $itemsInjectorConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['itemsInjector']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['itemsInjector'] : array();
        $fifoValidate = $this->config->item('heTransaksi_pembatalanFifoValidate') != NULL ? $this->config->item('heTransaksi_pembatalanFifoValidate') : array();


        //  membaca isi nota
        $tmpB = $b->lookupByID($id)->result(); // ini membaca isi dari transaksi data
        showLast_query("hitam");


        //  membaca registry shipment (582spd)
        $tmpRegistry = $trs->lookupRegistriesByMasterID($id)->result();


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
        if (sizeof($tmpRegistry) > 0) {
            foreach ($tmpRegistry as $row) {
                switch ($row->param) {
                    case "main":
                        $masterMainFields = unserialize(base64_decode($row->values));
                        break;
                    case "master_add_fields":
                        $masterAddFields = unserialize(base64_decode($row->values));
                        break;
                    case "master_add_values":
                        $masterAddValues = unserialize(base64_decode($row->values));
                        break;
                    case "tableIn_detail_values":
                        $tmpDetailValues = unserialize(base64_decode($row->values));
                        break;
                    case "tableIn_master_values":
                        $tmpMasterInValues = unserialize(base64_decode($row->values));
                        break;
                    case "items":
                        $itemsFields = unserialize(base64_decode($row->values));
                        break;
                    case "items2":
                        $items2Fields = unserialize(base64_decode($row->values));
                        break;
                    case "items2_sum":
                        $items2_sum = unserialize(base64_decode($row->values));
                        break;
                    case "rsltItems":
                        $rsltItems = unserialize(base64_decode($row->values));
                        break;
                    case "rsltItems2":
                        $rsltItems2 = unserialize(base64_decode($row->values));
                        break;

                    case "preProcessor":
                        $preProcessor = isset($row->values) ? unserialize(base64_decode($row->values)) : array();
                        break;
                    case "postProcessor":
                        $postProcessor = isset($row->values) ? unserialize(base64_decode($row->values)) : array();
                        break;
                    case "jurnal_index":
                        $component = isset($row->values) ? unserialize(base64_decode($row->values)) : array();
                        break;
                }
            }
        }

        if (sizeof($tmpB) > 0) {
            //--------------------------------------------
            $jenisMasterRef = $tmpB[0]->jenis_master;
            if (isset($fifoValidate[$jenisMasterRef])) {
                $mdlLoc = $fifoValidate[$jenisMasterRef]['mdlNameLoc'];
                $mdlName = $fifoValidate[$jenisMasterRef]['mdlName'];
                $mdlMethod = $fifoValidate[$jenisMasterRef]['method'];

                $this->load->model("$mdlLoc/$mdlName");
                $mdd = New $mdlName();
                $resultTmp = array();
                if (method_exists($mdd, $mdlMethod)) {
                    $resultTmp = $mdd->$mdlMethod($transID_ref);
                }
                foreach ($itemsFields as $iSpec) {
                    $i_qty = $iSpec['qty'];
                    $i_nama = htmlspecialchars($iSpec['name']);
                    $f_qty = isset($resultTmp[$iSpec['id']]) ? $resultTmp[$iSpec['id']] : 0;
                    if ($f_qty != $i_qty) {
                        $msg = "Jumlah stok $i_nama tidak cukup. Pembatalan transaksi tidak bisa dilanjutkan. Silahkan menggunakan fasilitas return transaksi.";
                        die(lgShowAlertBiru($msg));
                    }
                }

            }
//            arrPrintWebs($resultTmp);
//            mati_disini("$jenisMasterRef :: $transID_ref");
            //--------------------------------------------

            // ================== tambahan membaca activity
            $jenis_master = $tmpB[0]->jenis_master;
//            cekHitam("jenis master: $jenis_master");
            $pembatalanValidateConfig = isset($this->config->item('heTransaksi_pembatalanValidate')[$jenis_master]) ? $this->config->item('heTransaksi_pembatalanValidate')[$jenis_master] : array();
            if (sizeof($pembatalanValidateConfig) > 0) {
                foreach ($pembatalanValidateConfig as $pembatalanSpec) {
                    $mdlNameValidate = $pembatalanSpec['mdlName'];
                    $mdlFilterValidate = isset($pembatalanSpec['mdlFilter']) ? $pembatalanSpec['mdlFilter'] : array();

                    $this->load->model("Mdls/$mdlNameValidate");
                    $mdl_v = New $mdlNameValidate();
                    $mdl_v->setFilters(array());
                    if (sizeof($mdlFilterValidate) > 0) {
                        $rslt = makeFilter($mdlFilterValidate, (array)$tmpB[0], $mdl_v);
                    }
                    $validateTmp = $mdl_v->lookupAll()->result();

                    if (sizeof($validateTmp) > 0) {
                        $msg = $pembatalanSpec['label'];
//                        cekHitam($msg);
                        die(lgShowAlert($msg));
                    }
                    if (isset($pembatalanSpec['detailCekQty']) && ($pembatalanSpec['detailCekQty'] == true)) {
                        $trs->setFilters(array());
                        $dTr = $trs->lookupDetailTransaksi($id)->result();
                        $totalOrdJml = 0;
                        $totalValidQty = 0;
                        foreach ($dTr as $dTrSpec) {
                            $totalOrdJml += $dTrSpec->produk_ord_jml;
                            $totalValidQty += $dTrSpec->valid_qty;
                        }
                        if ($totalOrdJml != $totalValidQty) {
                            mati_disini($pembatalanSpec['label']);
                        }
                    }
                }

            }
            // ================== tambahan membaca activity
            $_SESSION[$cCode]['main']['seluruhnya'] = true;
            $_SESSION[$cCode]['main']['referenceID'] = $id;
            $_SESSION[$cCode]['main']['referenceNomer'] = $masterMainFields['nomer'];
            $_SESSION[$cCode]['main']['referenceNomer_top'] = $tmpB[0]->nomer_top;
            $_SESSION[$cCode]['main']['jenisTr_reference'] = $tmpB[0]->jenis;

            $_SESSION[$cCode]['main']['referenceStepNumber'] = $tmpB[0]->step_number;
            $_SESSION[$cCode]['main']['referenceID_top'] = $tmpB[0]->id_top;


            foreach ($tmpB as $row) {

                $id = $row->produk_id;
                $name = $row->produk_nama;
                $tmpJml = $row->produk_ord_jml;
                $tmpJmlReturn = $row->produk_ord_jml_return;
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
//                        cekMerah("masuk locker config");

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
                            mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                        }

                    }

                    $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
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
                            $tmp[$key2] = makeValue($src2, $tmp, $tmp, $row->$src2);
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
//                        cekmerah("sudah ada di items, mau update subtotal");
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
                            $_SESSION[$cCode]['main'][$key] = $row->$label;
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
                    $_SESSION[$cCode]['main'][$key] = $value;

                }
            }
            if (sizeof($masterMainFields) > 0) {
                foreach ($masterMainFields as $key => $value) {
                    if (!isset($_SESSION[$cCode]['main'][$key])) {
                        $_SESSION[$cCode]['main'][$key] = $value;
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
            if (sizeof($rsltItems) > 0) {
                foreach ($rsltItems as $key => $value) {
                    $_SESSION[$cCode]['rsltItems'][$key] = $value;
                }
            }
            if (sizeof($rsltItems2) > 0) {
                foreach ($rsltItems2 as $key => $value) {
                    $_SESSION[$cCode]['rsltItems2'][$key] = $value;
                }
            }
        }
        else {
//            cekMerah("tidak ada itemnya!");
            die();
        }

        cekPink("masterMainFields");
        arrPrint($tmpRegistry);

        //region fetch jurnalconfig

        $revertedJurnal = fetchRevertJurnal($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $component);
        $revertPostProc = fetchRevertPostProc($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor);
        $revertPaymentSrc = fetchRevertPaymentSrc($masterMainFields["jenis"], $masterMainFields["step_number"], $masterMainFields["transaksi_id"]);
        $swapcomFifo = fetchSwapComFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"]);
        $swapPreFifo = fetchSwapPreFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"]);
        $swapPreFifoMain = fetchSwapPreFifoMain($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['master']);


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


        if (sizeof($swapcomFifo) > 0) {

            $jenisTr_reference = $_SESSION[$cCode]['main']['jenisTr_reference'];
            $jenisException = $this->config->item('heTransaksi_revertJenisException') != null ? $this->config->item('heTransaksi_revertJenisException') : array();
            if (!in_array($jenisTr_reference, $jenisException)) {

                if (isset($swapcomFifo['detail']) && sizeof($swapcomFifo['detail']) > 0) {
                    foreach ($revertedJurnal as $main => $mainVal) {
                        $loop = array();
                        foreach ($mainVal as $key => $ValDetails) {
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Jurnal") {
                                if (isset($ValDetails["loop"]) && !array_key_exists("hutang lain ppv", $ValDetails["loop"])) {

                                    $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "selisih";//"(hpp+ppn)-nett"
                                }
                            }
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
                                if (isset($ValDetails["loop"]) && !array_key_exists("hutang lain ppv", $ValDetails["loop"])) {

                                    $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "selisih";//"(hpp+ppn)-nett"
                                }
                            }
                        }
                    }
                }
            }

//arrPrintWebs($revertedJurnal);
//            arrPrintWebs($swapcomFifo);
            foreach ($swapcomFifo as $gate => $spec) {
                foreach ($spec as $ii => $subSpec) {
                    $preProcc[$gate][$ii] = $subSpec;
                }
            }
        }

        if (sizeof($swapPreFifo) > 0) {

            foreach ($swapPreFifo as $gate => $swapPreFifoSpec) {

                foreach ($swapPreFifoSpec as $spec) {
                    $revertedJurnal[$gate][] = $spec;
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

//        mati_disini("MAINTENANCE");
        if (sizeof($revertedJurnal) > 0) {
            if (isset($revertedJurnal['master'])) {
                krsort($revertedJurnal['master']);
            }
            if (isset($revertedJurnal['detail'])) {
                krsort($revertedJurnal['detail']);
            }
        }

//        arrPrintWebs($revertPostProc);
//        mati_disini();
//        cekPink2("preprocc");
//        arrPrintWebs($preProcc);
//        cekPink("postprocc");
//        arrPrintWebs($swapPreFifoMain);
//        cekPink2("cetak reverted");
//        arrPrint($preProcc);
//        cekPink2("cetak reverted post procc");
//        arrPrintWebs($revertPostProc);
//        mati_disini();

        $_SESSION[$cCode]["revert"]["jurnal"] = $revertedJurnal;
        $_SESSION[$cCode]["revert"]["postProc"] = $revertPostProc;
        $_SESSION[$cCode]["revert"]["connectedPaymentsource"] = $revertPaymentSrc;
//        $_SESSION[$cCode]["revert"]["preProc"] = $swapcomFifo;
        $_SESSION[$cCode]["revert"]["preProc"] = $preProcc;


        //endregion


        if (isset($this->whitelistMain) && sizeof($this->whitelistMain) > 0) {
            foreach ($this->whitelistMain as $key => $val) {
                if (isset($_SESSION[$cCode]['main'][$val])) {
                    $_SESSION[$cCode]['main']['nilai_cancel'] = $_SESSION[$cCode]['main'][$val];
                }
            }
        }

        $nextProp = array();
        if (isset($_SESSION[$cCode]['main']['pihakExternRevertStep']) && ($_SESSION[$cCode]['main']['pihakExternRevertStep'] == true)) {
            $pihakExternMasterID = isset($_SESSION[$cCode]['main']['pihakExternMasterID']) ? $_SESSION[$cCode]['main']['pihakExternMasterID'] : 0;
            $stepsConfig = isset($this->config->item('heTransaksi_ui')[$pihakExternMasterID]['steps']) ? $this->config->item('heTransaksi_ui')[$pihakExternMasterID]['steps'] : array();
            $maxStep = sizeof($stepsConfig);
            if ($maxStep > 0) {
                $stepNum = $_SESSION[$cCode]['main']['referenceStepNumber'] - 1;
                $nextStepNum = $_SESSION[$cCode]['main']['referenceStepNumber'];
                $nextProp = array(
                    "step_num" => $stepNum,
                    "num" => $nextStepNum,
                    "code" => $this->config->item("heTransaksi_ui")[$pihakExternMasterID]['steps'][$nextStepNum]['target'],
                    "label" => $this->config->item("heTransaksi_ui")[$pihakExternMasterID]['steps'][$nextStepNum]['label'],
                    "groupID" => $this->config->item("heTransaksi_ui")[$pihakExternMasterID]['steps'][$nextStepNum]['userGroup'],

                    "trID" => isset($_SESSION[$cCode]['main']['referenceID_top']) ? $_SESSION[$cCode]['main']['referenceID_top'] : 0,

                    "detailGate" => isset($_SESSION[$cCode]['main']['pihakExternDetailGate']) ? $_SESSION[$cCode]['main']['pihakExternDetailGate'] : "",
                );

            }
        }
        $_SESSION[$cCode]['main']['referenceNextProp'] = $nextProp;


        //----------------------------------
        cekHitam("cetak POST-PROCC");
//        arrPrintPink($revertPostProc);

        cekHitam("cetak PRE-PROCC");
//        arrPrintPink($preProcc);

        //----------------------------------
        cekHitam("cetak REVERT JURNAL // COMPONENT");
        arrPrintWebs($revertedJurnal);
//        mati_disini("====== ====== ======");

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $referenceJenisTr = $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceJenisTr'];
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $externalConfig = isset($this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues']) ? $this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues'] : array();


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


//        mati_disini(__FUNCTION__);
        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart')";
        echo "</script>";
    }

    public function edit()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $referenceJenisTr = $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceJenisTr'];
        $externalConfig = isset($this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues']) ? $this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

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

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

    public function cancel()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $referenceJenisTr = $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceJenisTr'];
        $externalConfig = isset($this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues']) ? $this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

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

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }

    public function updateValues()
    {
        $cCode = "_TR_" . $this->jenisTr;
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }

    //----------------------------
    public function select()
    {

        $this->load->library("FieldCalculator");
        $this->load->model("MdlTransaksi");

        $cal = new FieldCalculator();
        $trs = new MdlTransaksi();

        $transID_ref = $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
//        cekHitam(":: $transID_ref ::");
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

        $selectorModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $referenceConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['referenceFields'] : null;
//        $externalConfig = isset($this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues']) ? $this->config->item('heTransaksi_core')[$referenceJenisTr]['externalValues'] : array();
        $itemsInjectorConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['itemsInjector']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['itemsInjector'] : array();
        $fifoValidate = $this->config->item('heTransaksi_pembatalanFifoValidate') != NULL ? $this->config->item('heTransaksi_pembatalanFifoValidate') : array();


        //  membaca isi nota
        $tmpB = $b->lookupByID($id)->result(); // ini membaca isi dari transaksi data
//        showLast_query("hitam");

// matiHEre();
        //  membaca registry shipment (582spd)
        $tmpRegistry = $trs->lookupRegistriesByMasterID($id)->result();


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
        if (sizeof($tmpRegistry) > 0) {
            foreach ($tmpRegistry as $row) {
                switch ($row->param) {
                    case "main":
                        $masterMainFields = unserialize(base64_decode($row->values));
                        break;
                    case "master_add_fields":
                        $masterAddFields = unserialize(base64_decode($row->values));
                        break;
                    case "master_add_values":
                        $masterAddValues = unserialize(base64_decode($row->values));
                        break;
                    case "tableIn_detail_values":
                        $tmpDetailValues = unserialize(base64_decode($row->values));
                        break;
                    case "tableIn_master_values":
                        $tmpMasterInValues = unserialize(base64_decode($row->values));
                        break;
                    case "items":
                        $itemsFields = unserialize(base64_decode($row->values));
                        break;
                    case "items2":
                        $items2Fields = unserialize(base64_decode($row->values));
                        break;
                    case "items2_sum":
                        $items2_sum = unserialize(base64_decode($row->values));
                        break;
                    case "rsltItems":
                        $rsltItems = unserialize(base64_decode($row->values));
                        break;
                    case "rsltItems2":
                        $rsltItems2 = unserialize(base64_decode($row->values));
                        break;

                    case "preProcessor":
                        $preProcessor = isset($row->values) ? unserialize(base64_decode($row->values)) : array();
                        break;
                    case "postProcessor":
                        $postProcessor = isset($row->values) ? unserialize(base64_decode($row->values)) : array();
                        break;
                    case "jurnal_index":
                        $component = isset($row->values) ? unserialize(base64_decode($row->values)) : array();
                        break;
                }
            }
        }

//arrPrintWebs($tmpMasterInValues);
//mati_disini();
//        arrPrintWebs($tmpB);
        if (sizeof($tmpB) > 0) {
            //--------------------------------------------
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
//            arrPrintWebs($resultTmp);
//            mati_disini("$jenisMasterRef :: $transID_ref");
            //--------------------------------------------

            // ================== tambahan membaca activity
            $jenis_master = $tmpB[0]->jenis_master;
            $jenisTr_reference = $tmpB[0]->jenis;
            $pembatalanValidateConfig = isset($this->config->item('heTransaksi_pembatalanValidate')[$jenis_master]) ? $this->config->item('heTransaksi_pembatalanValidate')[$jenis_master] : array();
            if (sizeof($pembatalanValidateConfig) > 0) {
                foreach ($pembatalanValidateConfig as $pembatalanSpec) {
                    $mdlNameValidate = $pembatalanSpec['mdlName'];
                    $mdlFilterValidate = isset($pembatalanSpec['mdlFilter']) ? $pembatalanSpec['mdlFilter'] : array();

                    $this->load->model("Mdls/$mdlNameValidate");
                    $mdl_v = New $mdlNameValidate();
                    $mdl_v->setFilters(array());
                    if (sizeof($mdlFilterValidate) > 0) {
                        $rslt = makeFilter($mdlFilterValidate, (array)$tmpB[0], $mdl_v);
                    }
                    $validateTmp = $mdl_v->lookupAll()->result();

                    if (sizeof($validateTmp) > 0) {
                        $msg = $pembatalanSpec['label'];
//                        cekHitam($msg);
                        die(lgShowAlert($msg));
                    }
                    if (isset($pembatalanSpec['detailCekQty']) && ($pembatalanSpec['detailCekQty'] == true)) {
                        $trs->setFilters(array());
                        $dTr = $trs->lookupDetailTransaksi($id)->result();
                        $totalOrdJml = 0;
                        $totalValidQty = 0;
                        foreach ($dTr as $dTrSpec) {
                            $totalOrdJml += $dTrSpec->produk_ord_jml;
                            $totalValidQty += $dTrSpec->valid_qty;
                        }
                        if ($totalOrdJml != $totalValidQty) {
                            mati_disini($pembatalanSpec['label']);
                        }
                    }
                }

            }
            // ================== tambahan membaca activity
            $_SESSION[$cCode]['main']['seluruhnya'] = true;
            $_SESSION[$cCode]['main']['referenceID'] = $id;
            $_SESSION[$cCode]['main']['referenceNomer'] = $masterMainFields['nomer'];
            $_SESSION[$cCode]['main']['referenceNomer_top'] = $tmpB[0]->nomer_top;
            $_SESSION[$cCode]['main']['jenisTr_reference'] = $tmpB[0]->jenis;

            $_SESSION[$cCode]['main']['referenceStepNumber'] = $tmpB[0]->step_number;
            $_SESSION[$cCode]['main']['referenceID_top'] = $tmpB[0]->id_top;


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

                    $fieldSrcs = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
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
//                        cekmerah("sudah ada di items, mau update subtotal");
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
                    $_SESSION[$cCode]['main'][$key] = $value;

                }
            }
            if (sizeof($masterMainFields) > 0) {
                foreach ($masterMainFields as $key => $value) {
                    if (!isset($_SESSION[$cCode]['main'][$key])) {
                        $_SESSION[$cCode]['main'][$key] = $value;
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
            //---- registry resultItems dan resultItems2 dialihkan ke resultItems_revert dan resultItems2_revert
            if (sizeof($rsltItems) > 0) {
                if (isset($_SESSION[$cCode]['rsltItems_revert'])) {
                    $_SESSION[$cCode]['rsltItems_revert'] = NULL;
                    unset($_SESSION[$cCode]['rsltItems_revert']);
                }
                foreach ($rsltItems as $key => $value) {
//                    $_SESSION[$cCode]['rsltItems'][$key] = $value;

                    if (!isset($_SESSION[$cCode]['rsltItems_revert'][$value['id']])) {
                        $_SESSION[$cCode]['rsltItems_revert'][$value['id']] = $value;
                        $_SESSION[$cCode]['rsltItems_revert'][$value['id']]['jml'] = 0;
                        $_SESSION[$cCode]['rsltItems_revert'][$value['id']]['qty'] = 0;
                    }
                    $_SESSION[$cCode]['rsltItems_revert'][$value['id']]['jml'] += $value['jml'];
                    $_SESSION[$cCode]['rsltItems_revert'][$value['id']]['qty'] += $value['qty'];
                }
            }
            if (sizeof($rsltItems2) > 0) {
                if (isset($_SESSION[$cCode]['rsltItems2_revert'])) {
                    $_SESSION[$cCode]['rsltItems2_revert'] = NULL;
                    unset($_SESSION[$cCode]['rsltItems2_revert']);
                }
                foreach ($rsltItems2 as $key => $value) {
//                    $_SESSION[$cCode]['rsltItems2'][$key] = $value;

                    if (!isset($_SESSION[$cCode]['rsltItems2_revert'][$value['id']])) {
                        $_SESSION[$cCode]['rsltItems2_revert'][$value['id']] = $value;
                        $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['jml'] = 0;
                        $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['qty'] = 0;
                }
                    $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['jml'] += $value['jml'];
                    $_SESSION[$cCode]['rsltItems2_revert'][$value['id']]['qty'] += $value['qty'];
            }
            }
        }
        else {
//            cekMerah("tidak ada itemnya!");
            die();
        }


//        cekPink("masterMainFields");
//        arrPrint($masterMainFields);

        //region fetch jurnalconfig

        $revertedJurnal = fetchRevertJurnal($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $component);
        $revertPostProc = fetchRevertPostProc($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor);
        $revertPaymentSrc = fetchRevertPaymentSrc($masterMainFields["jenis"], $masterMainFields["step_number"], $masterMainFields["transaksi_id"]);
        $swapcomFifo = fetchSwapComFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor['detail']); // ini untuk dimasukkan ke preprocc
        $swapPreFifo = fetchSwapPreFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['detail']); // ini untuk masuk ke postprocc/component fifo
        $swapPreFifoMain = fetchSwapPreFifoMain($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['master']);

//cekHijau($postProcessor);
//cekHitam(":: :: ::");
//arrPrintPink($swapcomFifo);
//arrPrintPink($revertedJurnal);
//arrPrintPink($revertPostProc['detail']);
//mati_disini("=== === ===");

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


        cekPink2($jenisTr_reference);
        $jenisTr_reference = in_array($jenisTr_reference, $this->jenisTrException) ? $jenisTr_reference : $_SESSION[$cCode]['main']['jenisTr_reference'];
        if (sizeof($swapcomFifo) > 0) {

            $jenisException = $this->config->item('heTransaksi_revertJenisException') != null ? $this->config->item('heTransaksi_revertJenisException') : array();
//            cekPink2(":: $jenisTr_reference ::");

            if (!in_array($jenisTr_reference, $jenisException)) {

                if (isset($swapcomFifo['detail']) && sizeof($swapcomFifo['detail']) > 0) {
                    foreach ($revertedJurnal as $main => $mainVal) {
                        $loop = array();
                        foreach ($mainVal as $key => $ValDetails) {
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Jurnal") {
                                if (isset($ValDetails["loop"]) && !array_key_exists("hutang lain ppv", $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'])) {
                                        $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo']);
                                    }
                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
                                if (isset($ValDetails["loop"]) && !array_key_exists("hutang lain ppv", $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'])) {
                                        $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo']);
                                    }
                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
//                                            arrPrintPink($ValDetails);
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "selisih";//"(hpp+ppn)-nett"
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
                    $preProcc[$gate][$ii] = $subSpec;
                }
            }
        }

        if (sizeof($swapPreFifo) > 0) {

            foreach ($swapPreFifo as $gate => $swapPreFifoSpec) {

                foreach ($swapPreFifoSpec as $spec) {
//                    $revertedJurnal[$gate][] = $spec;
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
                                if (isset($ValDetails["loop"]) && !array_key_exists("hutang lain ppv", $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'])) {
                                        $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo']);
        }

                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }

                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
                                if (isset($ValDetails["loop"]) && !array_key_exists("hutang lain ppv", $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'])) {
                                        $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo']);
                                    }

                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
//                                            arrPrintPink($ValDetails);
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['selisih persediaan karena fifo'] = "selisih";//"(hpp+ppn)-nett"
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

//krsort($revertPostProc['detail']);
//cekKuning($revertPostProc['detail']);

//        cekHitam("---PRE-PROCC----------------------------");
//        cekUngu($preProcc);
//        cekPink("postprocc");
//        arrPrintWebs($swapPreFifoMain);
//        cekPink2("cetak reverted");
//        arrPrint($preProcc);
//        cekPink2("cetak reverted post procc");
//        cekHitam("---POST-PROCC----------------------------");
//        cekKuning($revertPostProc['detail']);
//        cekHere("---REKENING-PROCC----------------------------");
//        cekHere($revertedJurnal);
//        mati_disini();

        $_SESSION[$cCode]["revert"]["jurnal"] = $revertedJurnal;
        $_SESSION[$cCode]["revert"]["postProc"] = $revertPostProc;
        $_SESSION[$cCode]["revert"]["connectedPaymentsource"] = $revertPaymentSrc;
//        $_SESSION[$cCode]["revert"]["preProc"] = $swapcomFifo;
        $_SESSION[$cCode]["revert"]["preProc"] = $preProcc;


        //endregion


        if (in_array($tmpB[0]->jenis, $this->jenisTrException)) {
//            $_SESSION[$cCode]['main']['nilai_cancel'] = ""; // nilai_cancel tidak diganti
            cekPink2("disini");
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
            $stepsConfig = isset($this->config->item('heTransaksi_ui')[$pihakExternMasterID]['steps']) ? $this->config->item('heTransaksi_ui')[$pihakExternMasterID]['steps'] : array();
            $maxStep = sizeof($stepsConfig);
            if ($maxStep > 0) {
                $stepNum = $_SESSION[$cCode]['main']['referenceStepNumber'] - 1;
                $nextStepNum = $_SESSION[$cCode]['main']['referenceStepNumber'];
                $nextProp = array(
                    "step_num" => $stepNum,
                    "num" => $nextStepNum,
                    "code" => $this->config->item("heTransaksi_ui")[$pihakExternMasterID]['steps'][$nextStepNum]['target'],
                    "label" => $this->config->item("heTransaksi_ui")[$pihakExternMasterID]['steps'][$nextStepNum]['label'],
                    "groupID" => $this->config->item("heTransaksi_ui")[$pihakExternMasterID]['steps'][$nextStepNum]['userGroup'],

                    "trID" => isset($_SESSION[$cCode]['main']['referenceID_top']) ? $_SESSION[$cCode]['main']['referenceID_top'] : 0,

                    "detailGate" => isset($_SESSION[$cCode]['main']['pihakExternDetailGate']) ? $_SESSION[$cCode]['main']['pihakExternDetailGate'] : "",
                );

            }
        }
        $_SESSION[$cCode]['main']['referenceNextProp'] = $nextProp;


        //----------------------------------
        cekHitam("cetak POST-PROCC");
        arrPrintPink($revertPostProc);

//        cekHitam("cetak PRE-PROCC");
//        arrPrintPink($preProcc);

        //----------------------------------
//        cekHitam("cetak REVERT JURNAL // COMPONENT");
//        arrPrintWebs($revertedJurnal);


//        mati_disini("====== ====== ======");

        echo "<script>";
        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        echo "</script>";
    }
}