<?php

require_once "Modul_Controller.php";

class ActivityReport extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $tmpJenis = $this->uri->segment(4);
        // if (strlen($tmpJenis) > 0) {
        //     $this->jenisTr = $tmpJenis;
        // }

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);//
        // arrPrint($this->session->login);

        $this->load->library("MobileDetect");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlEmployeeCabang");
        $this->load->model("Mdls/MdlReport");

        $trd = new MdlTransaksi();
        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        $this->placeId = $this->session->login['cabang_id'];

        $this->sID_alias = array(
            "oleh_id" => "olehID",
            "customers_id" => "pihakID",
            "cabang_id" => "cabangID",
            "produk_id" => "id",
            "suppliers_id" => "pihakID",
            "seller_id" => "sellerID",
        );
    }

    /*
     * settlemet pos
     */
    public function viewMySettlement()
    {
        $arrAllowTrx = array("4464", "4467", "749");
        // arrPrint($this->session->login);
        // $autoconnectJenis = "759";
        $starttime = microtime(true);
        $jenisTr = $this->jenisTr;


        //region date filter
        //endregion

        $this->jenisTrName = isset($this->configUi[$this->jenisTr]['steps'][1]['label']) ? $this->configUi[$this->jenisTr]['steps'][1]['label'] : "unnamed";
        $connectedSettlement = isset($this->configUi[$this->jenisTr]["connectSettlement"]) ? $this->configUi[$this->jenisTr]["connectSettlement"] : array();
        $connectedSettlementFields = isset($this->configUi[$this->jenisTr]["settlementFields"]) ? $this->configUi[$this->jenisTr]["settlementFields"] : array();

//        arrPrintWebs($jenisTr);
//        arrPrintWebs($connectedSettlementFields);

        $settlConfig = $this->config->item("heTransaksi_settlementGroups");
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("oleh_id='" . $this->session->login['id'] . "'");
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("settlement_id='1'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in (" . implode(",", $arrAllowTrx) . ")");

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        if (count($mems) == 1 && in_array("o_seller_spv", $mems) || count($mems) == 1 && in_array("o_holding", $mems)) {

        }
        else {
//            $this->db->where("fulldate>='" . $date1 . "'");
//            $this->db->where("fulldate<='" . $date2 . "'");
        }

//        $date1 = "2022-05-29";
//        $date2 = "2022-05-29";

        $tempTrans = $tr->lookUpAll()->result();

        $ids = array();
        $idsCancel = array();
        $idsNett = array();
        $oleh_id_check = array();
        $oleh_nama_check = array();
        $extern = array();

        if (sizeof($tempTrans) > 0) {

            foreach ($tempTrans as $temp) {
                $oleh_id_check[$temp->oleh_id] = $temp->id;
                $oleh_nama_check[$temp->oleh_id] = $temp->oleh_nama;
                $extern = array(
                    "id" => $temp->oleh_id,
                    "nama" => $temp->oleh_nama,
                );
                if ($temp->trash_4 == 1) {
                    $idsCancel[$temp->id] = $temp->id;
                }
                else {
                    $idsNett[$temp->id] = $temp->id;
                }
            }

            $tr->setFilters(array());
//            $tr->addFilter("jenis='" . $jenisTr . "'");
            $tr->addFilter("transaksi_id in (" . implode(",", $idsNett) . ")");
            $tr->addFilter("label='hutang setoran'");
            $lookUpAllPaymentSrc = $tr->lookUpAllPaymentSrc()->result();

            $paymentSourceData = array();
            if (!empty($lookUpAllPaymentSrc)) {
                foreach ($lookUpAllPaymentSrc as $k => $pymRow) {
                    $paymentSourceData[$pymRow->transaksi_id] = $pymRow;
                }
            }
//arrPrintKuning($paymentSourceData);
            $titip_setoran = 0;
            if (!isset($oleh_id_check[$this->session->login['id']])) {
                $titip_setoran = 1;
                foreach ($oleh_id_check as $olhID) {
                    $titip_setoran_nama = $oleh_nama_check[$olhID];
                }
                $nama_penyetor = $this->session->login['nama'];
            }
            foreach ($tempTrans as $tempData) {
                $ids[$tempData->id] = $tempData->id;

                $tempData->transaksi_nilai = $paymentSourceData[$tempData->id]->terbayar;

                $tNomer_top = $tempData->nomer_top;
                $cabang_id = $tempData->cabang_id;
                $oleh_id = $tempData->oleh_id;
                $tNomer = $tempData->nomer;
                $jenisTrtop = explode("-", $tNomer_top)[0];
                $jenisTrsub = explode("-", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId . "|" . $oleh_id;
                $arrIdHist = blobDecode($tempData->ids_his);
                $arrCounters = isset($arrIdHist[1]['counters']) ? blobDecode($arrIdHist[1]['counters']) : "";
                $counterIds_his = is_array($arrCounters) ? blobDecode(blobDecode($tempData->ids_his)[1]['counters']) : "";
                $counters = blobDecode($tempData->counters);
                $counterGlobal = $counters['stepCode|placeID|olehID'][$counterjenis];
                $counterIds_his_global = isset($counterIds_his['stepCode|placeID|olehID']["$jenisTrtop|$cabang_id|$oleh_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);
                $settleTmpData = array();

                if (count($mems) == 1 && in_array("o_seller_spv", $mems) || count($mems) == 1 && in_array("o_holding", $mems)) {

                }
                else {
                    unset($connectedSettlementFields['oleh_nama']);
//                    unset($connectedSettlementFields['transaksi_nilai']);
                }

                $connectedSettlementFields['oleh_id'] = "id seller";
                $connectedSettlementFields['show'] = "tampilkan";
                $tempData->show = $tempData->oleh_id == $this->session->login['id'] ? 1 : 0;
//arrPrintKuning($tempData);
                foreach ($connectedSettlementFields as $key => $label) {
                    if ($key == "nomer") {
                        $kolomValue_0s = formatField_he_format($key, $tempData->$key);
                        $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                    }
                    else {
                        $kolomValues = formatField_he_format($key, $tempData->$key);
                    }
                    $settleTmpData[$key] = $kolomValues;
                }

                $settleData[] = $settleTmpData;

            }
        }

        //region history settle
        $relJenis = $connectedSettlement['target'];
        $configUiJenis = loadConfigModulJenis_he_misc("$relJenis", "coTransaksiUi");

        $tr->setFilters(array());
        if (count($mems) == 1 && in_array("o_seller_spv", $mems) || count($mems) == 1 && in_array("o_holding", $mems)) {

        }
        else {
            $tr->addFilter("extern_id='" . $this->session->login['id'] . "'");
        }

        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("jenis='" . $relJenis . "'");
        $tr->addFilter("target_jenis='" . $connectedSettlement["targetPayment"] . "'");

        $this->db->order_by("id", "desc");
        $this->db->limit(14);
        $tempPayment = $tr->lookUpPayment()->result();

        $settlementHistoryFields = $configUiJenis["shortHistoryFields"];
        $settlementHistoryFieldsPending = $configUiJenis["shortHistoryFieldsPending"];

        $arrayPending = array();
        $arrayHistory = array();
        if (sizeof($tempPayment) > 0) {
            foreach ($tempPayment as $tempHist) {
                if ($tempHist->tagihan == $tempHist->sisa) {
                    //jadi outstanding
                    $iindexKey = "pending";
                }
                else {
                    //jadi history
                    $iindexKey = "history";
                }
                $tempPending = array();
                foreach ($settlementHistoryFields as $key => $keyLabel) {
                    $tempPending[$key] = $tempHist->$key;
                }
                if ($iindexKey == "pending") {
                    $tempPending["keterangan"] = "menunggu approval";
                }
                else {
                    $tempPending["keterangan"] = "diterima";
                }
                $arrayHistory[$iindexKey][] = $tempPending;
            }
        }
        //endregion

        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $subTitle = lgTranslateTime($date1) . " to " . lgTranslateTime($date2);

        if ($date1 == $date2) {
            $subTitle = lgTranslateTime($date1);
        }

        $thisTr = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;

        $thisTrName = isset($row->jenis_label) ? $row->jenis_label : $this->configUi[$jenisTr]["label"];

//        arrPrint($settleData);
//        arrPrint($settlementHistoryFields);

        $data = array(
            "mode" => "viewSettlement",
            "jenisTr" => $jenisTr,
            "subJenisTr" => strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $jenisTr,
            "trName" => $thisTrName,
            "errMsg" => $this->session->errMsg,
            "title" => "Settlement (" . $this->session->login['nama'] . ")",
            "subTitle" => $subTitle,
            "itemsLabel" => $connectedSettlementFields,
            "items" => $settleData,
            "settlementLink" => MODUL_PATH . "doSettlementPos/$jenisTr",
            "arrayHistoryLabels" => $settlementHistoryFields,
            "arrayHistoryLabelPending" => $settlementHistoryFieldsPending,
            "arrayHistory" => $arrayHistory,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(4),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4),
            "accountAlias" => isset($accountsAlias) ? $accountsAlias : array(),
            "preview_target" => MODUL_PATH . get_class($this) . "/previewSettlement/" . $jenisTr . "/" . $connectedSettlement["modul"] . "/" . $connectedSettlement["target"] . "?date1=$date1&date2=$date2",
            "action_target" => MODUL_PATH . get_class($this) . "/doSettlementPos/" . $jenisTr . "/" . $connectedSettlement["modul"] . "/" . $connectedSettlement["target"] . "?date1=$date1&date2=$date2",
            "action_label" => $connectedSettlement["btnLabel"],
            "extern" => $extern,
            "this_login" => $this->session->login,
            "mems" => $mems,
            "isAdmin" => count($mems) == 1 && in_array("o_seller_spv", $mems) || count($mems) == 1 && in_array("o_holding", $mems) ? 1 : 0,
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        $this->load->view("history", $data);

    }

    public function previewSettlement()
    {

        $arrAllowTrx = array("4464", "4467", "749");
        $jenisTr = $this->jenisTr;
        $timeStart = date('Y-m-d H:i:s');
        $heTransaksiUi = $this->configUi;
        $selectedRegistries = array("main", "items");
        $currentTr = $this->uri->segment(4);
        $targetModul = $this->uri->segment(5);
        $targetJenis = $this->uri->segment(6);
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $action_target = $_POST['action_target'];

        $this->load->model("Mdls/MdlEmployeeCabang");
        $emp = new MdlEmployeeCabang();

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();

        $this->load->model("Mdls/MdlHargaProduk");
        $prc = new MdlHargaProduk();

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("oleh_id='" . $this->session->login['id'] . "'");
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("settlement_id='1'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('4464','4467','749')");

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : mati_disini("Settlement gagal disimpan. Sesi anda habis, silahkan login ulang.");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : mati_disini("Settlement gagal disimpan. Sesi anda habis, silahkan login ulang.");

        if (count($mems) == 1 && in_array("o_seller_spv", $mems) || count($mems) == 1 && in_array("o_holding", $mems)) {

        }
        else {
//            $this->db->where("fulldate>='" . $date1 . "'");
//            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $tempTrans = $tr->lookUpAll()->result();

        // arrPrint($tempTrans);
        // cekmerah($this->db->last_query());
        // matiHerE();

        $emp->addFilter("oto_settlement=1");
        $emp->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $arrEmployee = $emp->lookUpAll()->result();

        $ids = array();
        $idsCancel = array();
        $idsNett = array();
        $oleh_id_check = array();
        $oleh_nama_check = array();
        $extern = array();
        $arrCustomer = array();

        if (sizeof($tempTrans) > 0) {

            foreach ($tempTrans as $temp) {
                $ids[$temp->id] = $temp->id;
                $oleh_id_check[$temp->oleh_id] = $temp->id;
                $oleh_nama_check[$temp->oleh_id] = $temp->oleh_nama;
                $extern = array(
                    "id" => $temp->oleh_id,
                    "nama" => $temp->oleh_nama,
                );
                $arrCustomer[$temp->id] = $temp->customers_nama;
                if ($temp->trash_4 == 1) {
                    $idsCancel[$temp->id] = $temp->id;
                }
                else {
                    $idsNett[$temp->id] = $temp->id;
                }
            }

            $tr->setFilters(array());
//            $tr->addFilter("jenis='" . $jenisTr . "'");
            $tr->addFilter("transaksi_id in (" . implode(",", $idsNett) . ")");
            $lookUpAllPaymentSrc = $tr->lookUpAllPaymentSrc()->result();

            $paymentSourceData = array();
            if (!empty($lookUpAllPaymentSrc)) {
                foreach ($lookUpAllPaymentSrc as $k => $pymRow) {
                    $paymentSourceData[$pymRow->transaksi_id] = $pymRow;
                }
            }

            $titip_setoran = 0;
            if (!isset($oleh_id_check[$this->session->login['id']])) {
                $titip_setoran = 1;
                foreach ($oleh_id_check as $olhID) {
                    $titip_setoran_nama = $oleh_nama_check[$olhID];
                }
                $nama_penyetor = $this->session->login['nama'];
            }

            //ambil register
            $tr->setFilters(array());
            $tempReg = $tr->lookupBaseDataRegistries($ids)->result();

            $newItems = array();
            $newItemsCanceled = array();
            $tempItems = array();
            $sumProduk = array();
            $sumProdukCanceled = array();
            $itemsTrans = array();
            $itemsTransCanceled = array();

            $totalCash = 0;
            $totalAddDiskon = 0;
            $totalDiskonProduk = 0;
            $totalDiskonCust = 0;
            $totalHargaJual = 0;
            $totalDppPPn = 0;
            $totalPPn = 0;
            $totalNonDpp = 0;
            $totalCukai = 0;
            $totalCancel = 0;
            $totalCashCanceled = 0;
            $totalAddDiskonCanceled = 0;
            $totalDiskonProdukCanceled = 0;
            $totalDiskonCustCanceled = 0;
            $totalHargaJualCanceled = 0;
            $totalDppPPnCanceled = 0;
            $totalPPnCanceled = 0;
            $totalNonDppCanceled = 0;
            $totalCukaiCanceled = 0;

            $freeProduk = array();
            $freeProdukCanceled = array();
            $preInjectTrData = array();
            $preInjectTrDataCanceled = array();

            if (sizeof($tempReg) > 0) {
                foreach ($tempReg as $regData) {
                    $trID = $regData->transaksi_id;
                    if (!isset($idsCancel[$trID])) {
                        $main = blobdecode($regData->main);
                        $items = blobdecode($regData->items);
                        $preInjectTrData[$trID] = $main["nomer"];
                        $preItems = array();
                        $preItemsCanceled = array();

                        $diskonProduk = 0;
                        $hargaJual = 0;
                        $dppPPn = 0;
                        $pPn = 0;
                        $nonDpp = 0;
                        $cukai = 0;
                        $diskonProdukCanceled = 0;
                        $hargaJualCanceled = 0;
                        $dppPPnCanceled = 0;
                        $pPnCanceled = 0;
                        $nonDppCanceled = 0;
                        $cukaiCanceled = 0;

                        foreach ($items as $itemDataTmp) {

//                            arrPrintWebs($itemDataTmp);

                            if (in_array($itemDataTmp['jenisTr'], $arrAllowTrx)) {
                                //bagian jual
                                if (isset($itemDataTmp['free_items'])) {
                                    $freeProduk[] = $itemDataTmp['free_items'];
                                }

                                $itemDataTmp['cukai_produk'] = 0;
                                $tmpSumItems = array(
                                    "id" => $itemDataTmp['id'],
                                    "produk_id" => $itemDataTmp['id'],
                                    "nama" => $itemDataTmp['nama'],
                                    "produk_nama" => $itemDataTmp['nama'],
//                                    "barcode" => $itemDataTmp['barcode'],
//                                    "kode" => $itemDataTmp['barcode'],
//                                    "produk_kode" => $itemDataTmp['barcode'],
                                    "jml" => $itemDataTmp['jml'],
                                    "produk_qty" => $itemDataTmp['jml'],
//                                    "qty_unit" => $itemDataTmp['qty_unit'],
//                                    "harga_list" => $itemDataTmp['harga_list'],
//                                    "harga_jual" => $itemDataTmp['harga_jual'],
//                                    "discNilai" => $itemDataTmp['discNilai'],
//                                    "discPersen" => $itemDataTmp['discPersen'],
                                    "harga" => $itemDataTmp['harga'],
                                    "subtotal" => $itemDataTmp['subtotal'],
                                    "ppn_produk" => isset($itemDataTmp['tipe_pajak']) ? $itemDataTmp['tipe_pajak'] : 1,
                                    "ppnFactor" => isset($itemDataTmp['ppnFactor']) ? $itemDataTmp['ppnFactor'] : my_ppn_factor(),
                                    "ppn" => $itemDataTmp['ppn'],
                                    "sub_ppn" => $itemDataTmp['sub_ppn'],
                                    "satuan" => $itemDataTmp['satuan'],
//                                    "satuan_id" => $itemDataTmp['satuan_id'],
//                                    "satuan_factor_qty" => $itemDataTmp['satuan_factor_qty'],
//                                    "satuan_factor_id" => $itemDataTmp['satuan_factor_id'],
//                                    "satuan_factor_nama" => $itemDataTmp['satuan_factor_nama'],
                                );

//                        $sumProduk[$itemDataTmp["id"]][] = $itemDataTmp; //aslinya

                                $sumProduk[$itemDataTmp["id"]][] = $tmpSumItems;
                                $preItems[$main["nomer"]][$itemDataTmp["id"]] = $tmpSumItems;

                                $diskonProduk += isset($itemDataTmp['discNilai']) && $itemDataTmp['discNilai'] * 1 > 0 ? $itemDataTmp['discNilai'] * $itemDataTmp['jml'] : 0;
                                $hargaJual += isset($itemDataTmp['harga_jual']) && $itemDataTmp['harga_jual'] * 1 > 0 ? $itemDataTmp['harga_jual'] * $itemDataTmp['jml'] : 0;
                                $dppPPn += isset($itemDataTmp['ppn_produk']) && $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;
                                $pPn += isset($itemDataTmp['ppn_produk']) && $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['sub_ppn'] : 0;
                                $nonDpp += isset($itemDataTmp['ppn_produk']) && $itemDataTmp['ppn_produk'] * 1 == 0 ? $itemDataTmp['subtotal'] : 0;
                                $cukai += isset($itemDataTmp['cukai_produk']) && $itemDataTmp['cukai_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;

                            }
                            elseif ($itemDataTmp['jenisTr'] == "982") {

                                //bagian cancel
                                if (isset($itemDataTmp['free_items'])) {
//                                    arrPrintPink('free_items (982)');
//                                    arrPrintPink($itemDataTmp['free_items']);
                                    $freeProdukCanceled[] = $itemDataTmp['free_items'];
                                }

                                $itemDataTmp['cukai_produk'] = 0;
                                $tmpSumItems = array(
                                    "id" => $itemDataTmp['id'],
                                    "produk_id" => $itemDataTmp['id'],
                                    "nama" => $itemDataTmp['nama'],
                                    "produk_nama" => $itemDataTmp['nama'],
                                    "barcode" => isset($itemDataTmp['barcode']) ? $itemDataTmp['barcode'] : "",
                                    "kode" => isset($itemDataTmp['barcode']) ? $itemDataTmp['barcode'] : "",
                                    "produk_kode" => isset($itemDataTmp['barcode']) ? $itemDataTmp['barcode'] : "",
                                    "jml" => $itemDataTmp['jml'],
                                    "produk_qty" => $itemDataTmp['jml'],
                                    "harga_list" => $itemDataTmp['harga_list'],
                                    "harga_jual" => $itemDataTmp['harga_jual'],
                                    "discNilai" => $itemDataTmp['discNilai'],
                                    "discPersen" => $itemDataTmp['discPersen'],
                                    "harga" => $itemDataTmp['harga'],
                                    "subtotal" => $itemDataTmp['subtotal'],
                                    "ppn_produk" => isset($itemDataTmp['tipe_pajak']) ? $itemDataTmp['tipe_pajak'] : 1,
                                    "ppnFactor" => $itemDataTmp['ppnFactor'],
                                    "ppn" => $itemDataTmp['ppn'],
                                    "sub_ppn" => $itemDataTmp['sub_ppn'],
                                    "satuan" => $itemDataTmp['satuan'],
                                    "satuan_id" => $itemDataTmp['satuan_id'],

//                                    "qty_unit" => $itemDataTmp['qty_unit'],
//                                    "satuan_factor_qty" => $itemDataTmp['satuan_factor_qty'],
//                                    "satuan_factor_id" => $itemDataTmp['satuan_factor_id'],
//                                    "satuan_factor_nama" => $itemDataTmp['satuan_factor_nama'],
                                );

//                        $sumProduk[$itemDataTmp["id"]][] = $itemDataTmp; //aslinya

                                $sumProdukCanceled[$itemDataTmp["id"]][] = $tmpSumItems;
                                $preItemsCanceled[$main["nomer"]][$itemDataTmp["id"]] = $tmpSumItems;

                                $diskonProdukCanceled += $itemDataTmp['discNilai'] * 1 > 0 ? $itemDataTmp['discNilai'] * $itemDataTmp['jml'] : 0;
                                $hargaJualCanceled += $itemDataTmp['harga_jual'] * 1 > 0 ? $itemDataTmp['harga_jual'] * $itemDataTmp['jml'] : 0;
                                $dppPPnCanceled += $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;
                                $pPnCanceled += $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['sub_ppn'] : 0;
                                $nonDppCanceled += $itemDataTmp['ppn_produk'] * 1 == 0 ? $itemDataTmp['subtotal'] : 0;
                                $cukaiCanceled += $itemDataTmp['cukai_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;

                            }

                        }
                        if (in_array($main["jenisTr"], $arrAllowTrx)) {

                            $totalCash += isset($main["nilai_entry"]) ? $main["nilai_entry"] : 0;
                            $totalDiskonCust += isset($main["disc"]) ? $main["disc"] : 0;
                            $totalAddDiskon += isset($main["add_disc"]) ? $main["add_disc"] * 1 : 0;

                            $totalDiskonProduk += $diskonProduk;
                            $totalHargaJual += $hargaJual;
                            $totalDppPPn += $dppPPn;
                            $totalPPn += $pPn;
                            $totalNonDpp += $nonDpp;
                            $totalCukai += $cukai;

                            $buildMainTmp = array(
                                "olehID" => $main['olehID'],
                                "sellerID" => $main['olehID'],
                                "olehName" => $main['olehName'],
                                "sellerName" => $main['olehName'],
                                "placeID" => $main['placeID'],
                                "placeName" => $main['placeName'],
                                "cabangID" => $main['cabangID'],
                                "cabangName" => $main['cabangName'],
                                "gudangID" => $main['gudangID'],
                                "gudangName" => $main['gudangName'],
                                "divID" => $main['divID'],
                                "tokoID" => $main['tokoID'],
                                "tokoNama" => $main['tokoNama'],
                                "stepCode" => $main['stepCode'],
                                "dtime" => $main['dtime'],
                                "fulldate" => $main['fulldate'],
                                "harga" => $main['harga'],
                                "grand_total" => isset($main['nilai_entry']) ? $main['nilai_entry'] : 0,
                                "bayar" => isset($main['bayar']) ? $main['bayar'] : 0,
                                "oto_diskon" => isset($main['oto_diskon']) ? $main['oto_diskon'] : "",

                                "appr_code" => isset($main['appr_code']) ? $main['appr_code'] : "",

                                "cash_account" => $main['cash_account'],
                                "cash_account__label" => $main['cash_account__label'],

                                "debit_account" => isset($main['debit_account']) ? $main['debit_account'] : "",
                                "debit_account__label" => isset($main['debit_account__label']) ? $main['debit_account__label'] : "",

                                "credit_account" => isset($main['credit_account']) ? $main['credit_account'] : "",
                                "credit_account__label" => isset($main['credit_account__label']) ? $main['credit_account__label'] : "",

                                "diskon_produk" => $diskonProduk, //diskon dari potongan produk
                                "add_disc" => $totalAddDiskon, //diskon tambahan (manual input diskon)
                                "diskon_tambahan_nilai" => isset($main['diskon_tambahan_nilai']) ? $main['diskon_tambahan_nilai'] : 0, //diskon customer

                                "harga_jual" => $hargaJual,
                                "dpp_ppn" => $dppPPn,
                                "ppn" => $pPn,
                                "non_dpp" => $nonDpp,
                                "cukai" => $cukai,

                                "transaksi_id" => $main['transaksi_id'],
//                                "paymentMethod_cash" => $main['paymentMethod_cash'],
//                            "paymentMethod_cash" => $main['paymentMethod_cash'],

                                "harga_nett2" => $main['harga'],
                                "tagihan" => isset($main['nilai_entry']) ? $main['nilai_entry'] : 0,
                                "customers_nama" => $main['customers_nama'],
//                                "kembali" => $main['kembali'],
                            );
//                        }
                            $itemsbase64 = base64_encode(json_encode($preItems[$main["nomer"]]));
                            $tempItems = $buildMainTmp + array("produk" => $itemsbase64, "nomor" => $main["nomer"]);
                            $newItems["items"][] = (object)$tempItems;
                            $itemsTrans[$trID] = $tempItems;
//                        cekUngu("transaksi_nilai: ". $main["grand_total"]);
                        }
                        else {

                            $totalCashCanceled += $main["grand_total"];
                            $totalDiskonCustCanceled += isset($main["disc"]) ? $main["disc"] : 0;
                            $totalAddDiskonCanceled += isset($main["add_disc"]) ? $main["add_disc"] * 1 : 0;

                            $totalDiskonProdukCanceled += $diskonProdukCanceled;
                            $totalHargaJualCanceled += $hargaJualCanceled;
                            $totalDppPPnCanceled += $dppPPnCanceled;
                            $totalPPnCanceled += $pPnCanceled;
                            $totalNonDppCanceled += $nonDppCanceled;
                            $totalCukaiCanceled += $cukaiCanceled;

                            $buildMainTmpCanceled = array(
                                "olehID" => $main['olehID'],
                                "sellerID" => $main['olehID'],
                                "olehName" => $main['olehName'],
                                "sellerName" => $main['olehName'],
                                "placeID" => $main['placeID'],
                                "placeName" => $main['placeName'],
                                "cabangID" => $main['cabangID'],
                                "cabangName" => $main['cabangName'],
                                "gudangID" => $main['gudangID'],
                                "gudangName" => $main['gudangName'],
                                "divID" => $main['divID'],
                                "tokoID" => $main['tokoID'],
                                "tokoNama" => $main['tokoNama'],
                                "stepCode" => $main['stepCode'],
                                "dtime" => $main['dtime'],
                                "fulldate" => $main['fulldate'],
                                "harga" => $main['harga'],
                                "grand_total" => $main['grand_total'],
                                "bayar" => isset($main['bayar']) ? $main['bayar'] : 0,
                                "oto_diskon" => isset($main['oto_diskon']) ? $main['oto_diskon'] : "",
                                "cash_account" => $main['cash_account'],
                                "cash_account__label" => $main['cash_account__label'],

                                "diskon_produk" => $diskonProduk, //diskon dari potongan produk
                                "add_disc" => $totalAddDiskon, //diskon tambahan (manual input diskon)
                                "diskon_tambahan_nilai" => $main['diskon_tambahan_nilai'], //diskon customer

                                "harga_jual" => $hargaJual,
                                "dpp_ppn" => $dppPPn,
                                "ppn" => $pPn,
                                "non_dpp" => $nonDpp,
                                "cukai" => $cukai,

                                "transaksi_id" => $main['transaksi_id'],
                                "paymentMethod_cash" => $main['paymentMethod_cash'],

                                "harga_nett2" => $main['harga'],
                                "tagihan" => $main['grand_total'],
                                "kembali" => $main['kembali'],
                            );

                            $itemsbase64Canceled = base64_encode(json_encode($preItemsCanceled[$main["nomer"]]));
                            $tempItemsCanceled = $buildMainTmpCanceled + array("produk" => $itemsbase64Canceled, "nomor" => $main["nomer"]);
                            $newItemsCanceled["items"][] = (object)$tempItemsCanceled;
                            $itemsTransCanceled[$trID] = $tempItemsCanceled;

                        }
                    }
                    else {
                        $main = blobdecode($regData->main);
                        $totalCancel += isset($idsCancel[$trID]) ? $main["grand_total"] : 0;
                    }
                }
            }
            else {
                matiHEre("no data to execute. silahkan relogin untuk membersihkan sesi");
            }

            $freeProdukSum = array();
            if (!empty($freeProduk)) {
                foreach ($freeProduk as $ky => $arrDistProduk) {
                    foreach ($arrDistProduk as $idDisc => $distProduk) {
                        if (!isset($freeProdukSum[$distProduk['free_produk_id']])) {
                            $freeProdukSum[$distProduk['free_produk_id']] = array();
                        }
                        if (!isset($freeProdukSum[$distProduk['free_produk_id']]['qty'])) {
                            $freeProdukSum[$distProduk['free_produk_id']]['qty'] = 0;
                        }
                        $freeProdukSum[$distProduk['free_produk_id']]['nama_produk'] = $distProduk['free_produk_nama'];
                        $freeProdukSum[$distProduk['free_produk_id']]['qty'] += $distProduk['free_qty'];
                    }
                }
            }

            $freeProdukCanceledSum = array();
            if (!empty($freeProdukCanceled)) {
                foreach ($freeProdukCanceled as $ky => $arrDistProduk) {
                    foreach ($arrDistProduk as $idDisc => $distProduk) {
                        if (!isset($freeProdukCanceledSum[$distProduk['free_produk_id']])) {
                            $freeProdukCanceledSum[$distProduk['free_produk_id']] = array();
                        }
                        if (!isset($freeProdukCanceledSum[$distProduk['free_produk_id']]['qty'])) {
                            $freeProdukCanceledSum[$distProduk['free_produk_id']]['qty'] = 0;
                        }
                        $freeProdukCanceledSum[$distProduk['free_produk_id']]['nama_produk'] = $distProduk['free_produk_nama'];
                        $freeProdukCanceledSum[$distProduk['free_produk_id']]['qty'] += $distProduk['free_qty'];
                    }
                }
            }

            $tr->setFilters(array());
            $tr->addFilter("transaksi_id in (" . implode(",", $idsNett) . ")");
            $trData = $tr->lookupTransaksiData()->result();

            $newTrData = array();
            $newTrDataCanceled = array();
            $idsNettPenjualan = array();
            $idsNettCanceled = array();
            if (sizeof($trData) > 0) {
                $newNr = array();
                $newNrCanceled = array();
                foreach ($trData as $iix => $trDataTmp) {
                    if (in_array($trDataTmp->sub_tail_code, $arrAllowTrx)) {
                        $buildDataTr = array(
                            "id" => $trDataTmp->id,
                            "transaksi_id" => $trDataTmp->transaksi_id,
                            "produk_jenis" => $trDataTmp->produk_jenis,
                            "produk_id" => $trDataTmp->produk_id,
                            "produk_nama" => $trDataTmp->produk_nama,
                            "valid_qty" => $trDataTmp->valid_qty,
                            "produk_ord_jml" => $trDataTmp->produk_ord_jml,
                            "produk_ord_hrg" => $trDataTmp->produk_ord_hrg,
                            "detail_tipe" => $trDataTmp->detail_tipe,
                            "sub_tail_code" => $trDataTmp->sub_tail_code,
                            "sub_tail_number" => $trDataTmp->sub_tail_number,
                            "next_substep_num" => $trDataTmp->next_substep_num,
                            "sub_step_avail" => $trDataTmp->sub_step_avail,
                            "sub_step_current" => $trDataTmp->sub_step_current,
                            "sub_step_number" => $trDataTmp->sub_step_number,
                        );
                        $trNr = (array)$buildDataTr + array("nomor" => $preInjectTrData[$trDataTmp->transaksi_id]);
                        $newNr[$iix] = (object)$trNr;
                        $idsNettPenjualan[$trDataTmp->transaksi_id] = $trDataTmp->transaksi_id;
                    }
                    else {
                        $buildDataTrCanceled = array(
                            "id" => $trDataTmp->id,
                            "transaksi_id" => $trDataTmp->transaksi_id,
                            "produk_jenis" => $trDataTmp->produk_jenis,
                            "produk_id" => $trDataTmp->produk_id,
                            "produk_nama" => $trDataTmp->produk_nama,
                            "valid_qty" => $trDataTmp->valid_qty,
                            "produk_ord_jml" => $trDataTmp->produk_ord_jml,
                            "produk_ord_hrg" => $trDataTmp->produk_ord_hrg,
                            "detail_tipe" => $trDataTmp->detail_tipe,
                            "sub_tail_code" => $trDataTmp->sub_tail_code,
                            "sub_tail_number" => $trDataTmp->sub_tail_number,
                            "next_substep_num" => $trDataTmp->next_substep_num,
                            "sub_step_avail" => $trDataTmp->sub_step_avail,
                            "sub_step_current" => $trDataTmp->sub_step_current,
                            "sub_step_number" => $trDataTmp->sub_step_number,
                        );
                        $trNrCanceled = (array)$buildDataTrCanceled + array("nomor" => $preInjectTrData[$trDataTmp->transaksi_id]);
                        $newNrCanceled[$iix] = (object)$trNrCanceled;
                        $idsNettCanceled[$trDataTmp->transaksi_id] = $trDataTmp->transaksi_id;
                    }
                }
                $newTrData["trData"] = $newNr;
                $newTrDataCanceled["trData"] = $newNrCanceled;
            }

            $doSendApi = array(
                "row" => count($tempTrans),
                "currentTr" => $currentTr,
                "targetJenis" => $targetJenis,
                "login" => $this->session->login,

                "newItems" => $newItems,
                "sumProduk" => $sumProduk,
                "itemsTrans" => $itemsTrans,

                "itemsTransCanceled" => $itemsTransCanceled,
                "sumProdukCanceled" => $sumProdukCanceled,
                "newItemsCanceled" => $newItemsCanceled,

                "freeProduk" => $freeProduk,
                "freeProdukSum" => $freeProdukSum,
                "freeProdukCanceledSum" => $freeProdukCanceledSum,

                "totalCash" => $totalCash,
                "totalDiskonProduk" => $totalDiskonProduk,
                "totalAddDiskon" => $totalAddDiskon,
                "totalDiskonCust" => $totalDiskonCust,

                "totalCashCanceled" => $totalCashCanceled,
                "totalDiskonProdukCanceled" => $totalDiskonProdukCanceled,
                "totalAddDiskonCanceled" => $totalAddDiskonCanceled,
                "totalDiskonCustCanceled" => $totalDiskonCustCanceled,

                "newTrData" => $newTrData,
                "newTrDataCanceled" => $newTrDataCanceled,

                "ids" => $ids,
                "idsCancel" => $idsCancel,
                "idsNett" => $idsNett,
                "idsNettPenjualan" => $idsNettPenjualan,
                "idsNettCanceled" => $idsNettCanceled,

                "debug" => 0, // <<==== MENGATUR DEBUG DI SERVER API TUJUAN
            );
            $dataEncoded = array(
                "data" => blobEncode($doSendApi)
            );

//            arrPrintWebs($itemsTrans);
//            arrPrintWebs($paymentSourceData);
//            arrPrint($doSendApi);
//            matiHere(__LINE__);
            $nomer_main = $this->session->login['id'] . "" . strtotime(date('Y-m-d'));

            $payment = array();
            $paymentNilai = array();
            $arrPaymentNilai = array();
            foreach ($itemsTrans as $ky => $row) {
                $row['customers_nama'] = $arrCustomer[$row['transaksi_id']];
                $payment[$row['cash_account']] = $row['cash_account__label'];
                $arrPaymentNilai[$row['cash_account']][] = $row;
                if (!isset($paymentNilai[$row['cash_account']])) {
                    $paymentNilai[$row['cash_account']] = 0;
                }
                $paymentNilai[$row['cash_account']] += $row['tagihan'];
            }

//            arrPrint($arrPaymentNilai);
            $paymentNilaiJson = json_encode($paymentNilai);

            echo "<style>
                    .tooltip-inner {
                        max-width: 100% !important;
                        color: #000 !important;
                    }
                </style>";

            echo "<div style='margin-bottom: 25px;padding: 6px;' class='panel panel-primary'>";
            echo "<div style='' class='row'>";
            echo "<div style='' class='container-fluid'>";
            echo "<div class='col-lg-3 no-padding text-left'>
            <div style='font-size: 13px;' class='text-bold text-red'>Outlet</div>
            <div style='font-size: 18px;' class='text-bold text-muted text-capitalize'>" . $this->session->login['cabang_nama'] . "</div>
                <input value='" . $this->session->login['cabang_id'] . "' id='cabang_id' class='hidden'>
            </div>";
            echo "<div class='col-lg-3 no-padding text-center'>
            <div style='font-size: 13px;' class='text-bold text-red'>Tgl Settlement</div>
            <div style='font-size: 18px;' class='text-bold text-muted text-capitalize'>" . date("Y-m-d H:i") . "</div>
            </div>";
            echo "<div class='col-lg-3 no-padding text-center'>
            <div style='font-size: 13px;' class='text-bold text-red'>Total Struk</div>
            <div style='font-size: 18px;' class='text-bold text-muted text-capitalize'>" . count($itemsTrans) . " Nota</div>
            </div>";

            if ($titip_setoran) {
                echo "<div class='col-lg-3 no-padding text-center'>
                <div style='font-size: 13px;' class='text-bold text-red'>Kasir Nama</div>
                <div style='font-size: 18px;' class='text-bold text-muted text-capitalize'>" . $extern["nama"] . "<br>(by: " . $this->session->login['nama'] . ")</div>
                    <input value='" . $extern['id'] . "' id='oleh_id' class='hidden'>
                    <input value='" . $extern["nama"] . "' id='oleh_nama' class='hidden'>
                </div>";
            }
            else {
                echo "<div class='col-lg-3 no-padding text-center'>
                <div style='font-size: 13px;' class='text-bold text-red'>Kasir Nama</div>
                <div style='font-size: 18px;' class='text-bold text-muted text-capitalize'>" . $this->session->login['nama'] . "</div>
                    <input value='" . $this->session->login['id'] . "' id='oleh_id' class='hidden'>
                    <input value='" . $this->session->login['nama'] . "' id='oleh_nama' class='hidden'>
                </div>";
            }

            echo "</div>";
            echo "</div>";
            echo "</div>";

            $arrNonTunai = array();
            if (!empty($payment)) {
                foreach ($payment as $payID => $payNama) {
                    echo "<div style='margin-bottom: 3px;' class='row'>";
                    echo "<div class='col-lg-6'>";

                    $toolTips = "";
                    if (isset($arrPaymentNilai[$payID])) {
                        $arrListTrxAcc = $arrPaymentNilai[$payID];

                        $toolTips .= "<div class='row'>";
                        $toolTips .= "<div class='container-fluid'>";
                        $toolTips .= "<div class='table-responsive'>";
                        $toolTips .= "<table class='table dataTable compact display table-bordered'>";
                        $toolTips .= "<thead class='bg-gray'>
                                        <tr>
                                            <th>No</th>
                                            <th>INV</th>
                                            <th>CUSTOMER</th>
                                            <th>PYM</th>
                                            <th>NILAI</th>
                                        </tr>
                                      </thead>";
                        $toolTips .= "<tbody>";


                        $num = 0;
                        $total = 0;
                        foreach ($arrListTrxAcc as $ky => $rowsData) {
                            $num++;
                            $toolTips .= "<tr>";
                            $toolTips .= "<td>" . $num . "</td>";
                            $toolTips .= "<td>" . $rowsData['nomor'] . "</td>";
                            $toolTips .= "<td>" . $rowsData['customers_nama'] . "</td>";
                            $toolTips .= "<td>" . $rowsData['cash_account__label'] . "</td>";
                            $toolTips .= "<td class='text-right text-bold'>" . number_format($rowsData['tagihan']) . "</td>";
                            $toolTips .= "</tr>";
                            $total += $rowsData['tagihan'] * 1;
                        }

                        $toolTips .= "</tbody>";
                        $toolTips .= "<tfoot class='bg-gray'>";
                        $toolTips .= "<tr>";
                        $toolTips .= "<td></td>";
                        $toolTips .= "<td></td>";
                        $toolTips .= "<td></td>";
                        $toolTips .= "<td></td>";
                        $toolTips .= "<td class='text-right text-bold'>" . number_format($total) . "</td>";
                        $toolTips .= "</tr>";
                        $toolTips .= "</tfoot>";
                        $toolTips .= "</table>";
                        $toolTips .= "</div>";
                        $toolTips .= "</div>";
                        $toolTips .= "</div>";
                    }

                    $non_tunai = strtoupper($payNama) != "TUNAI" ? "<sup data-toggle='tooltip' id='pay_id_$payID' data-html='true' data-placement='bottom' titles=\"$toolTips\" data-original-title=\"$toolTips\" class='text-red text-sm text-bold'>( " . number_format($paymentNilai[$payID]) . " ) </sup> <sup><i class='fa fa-eye text-orange' onclick=\"$('sup[data-toggle=tooltip]#pay_id_$payID').tooltip('show');\"></i></sup>" : "";
                    $defValue = strtoupper($payNama) != "TUNAI" ? $paymentNilai[$payID] : 0;

                    if (strtoupper($payNama) != "TUNAI") {
                        $arrNonTunai[] += 1;
                    }

                    echo "<span style='font-size: 18px;' class='text-bold text-primary'>$payNama &nbsp;$non_tunai</span>";
                    echo "</div>";
                    echo "<div class='col-lg-6'>";
                    echo "<span><input style='font-size: 23px;' value='$defValue' payid='$payID' paynama='$payNama' class='form-control text-muted text-right text-bold form-payment text-capitalize' placeholder='nilai $payNama'></span>";
                    echo "</div>";
                    echo "</div>";
                }

                if (count($arrNonTunai) > 0) {
                    echo "<div class='box box-danger box-solid box-header text-center text-bold'>
                                <div class='text-red'>~ NILAI YANG TERTERA PADA LAYAR ADALAH NILAI BERDASARKAN SYSTEM ~</div>
                                <div class='text-red'>
                                    <i class='fa fa-warning text-orange'></i>
                                    &nbsp;&nbsp;NILAI YANG WAJIB DIMASUKAN ADALAH NILAI YANG TERTERA PADA SETTLEMENT MESIN EDC.&nbsp;&nbsp;
                                    <i class='fa fa-warning text-orange'></i>
                                </div>
                          </div>";
                }


                if ($totalCashCanceled * 1 > 0) {
                    //total canceled
                    echo "<div style='margin-top: 25px;' class='row'>";

                    echo "<div class='col-lg-6'>";
                    echo "<span class='text-bold text-red fa-2x pull-rightx'>TOTAL PEMBATALAN</span>";
                    echo "</div>";

                    echo "<div class='col-lg-6'>";
                    echo "<span><input style='font-size: 23px;' value='" . number_format($totalCashCanceled) . "' class='form-control text-red bg-gray text-right text-bold form-total-canceled' disabled></span>";
                    echo "</div>";

                    echo "</div>";
                    //total canceled

                    //total setoran
                    echo "<div style='margin-top: 3px;' class='row'>";
                }
                else {

                    //total setoran
                    echo "<div style='margin-top: 25px;' class='row'>";
                }

                echo "<div class='col-lg-6'>";
                echo "<span class='text-bold text-success fa-2x pull-rightx'>TOTAL SETTLEMENT</span>";
                echo "</div>";

                echo "<div class='col-lg-6'>";
                echo "<span><input style='font-size: 23px;' class='form-control text-green bg-gray text-right text-bold form-total-payment' disabled></span>";
                echo "<input value='$totalCash' class='hidden form-total-cash'>";
                echo "</div>";

                echo "</div>";
                //total setoran


                //selisih setoran
                echo "<div style='margin-bottom: 10px;margin-top: 3px;' class='row hidden'>";

                echo "<div class='col-lg-6'>";
                echo "<span class='text-bold text-purple fa-2x pull-rightx'>SELISIH SETTLEMENT</span>";
                echo "</div>";

                echo "<div class='col-lg-6'>";
                echo "<span><input style='font-size: 23px;' class='form-control text-purple bg-gray text-right text-bold form-total-selisih' disabled></span>";
                echo "</div>";

                echo "</div>";
                //selisih setoran
            }

            echo "<div style='margin-top: 25px;padding: 6px;display: none;' class='panel panel-success panel-terbilang'>";
            echo "<div style='' class='row'>";
            echo "<div style='' class='container-fluid'>";

            echo "<div class='col-lg-12 no-padding text-left'>
                    <div style='font-size: 14px;' class='text-bold text-info text-uppercase'>
                        <i class='fa fa-money'></i> TERBILANG <i class='fa fa-money'></i>
                    </div>
                    <div style='font-size: 18px;' class='text-bold text-green text-capitalize form-terbilang'></div>
                </div>";

            echo "</div>";
            echo "</div>";
            echo "</div>";


//            if($titip_setoran && count($mems)==1 && in_array("o_seller_spv", $mems) || count($mems)==1 && in_array("o_holding", $mems) ){
//
//                echo "
//                            <button type='button' disabled class='btn btn-info btn-flat btn-kirim'><i class='fa fa-send'></i> KIRIM SETTLEMENT</button>
//                    ";
//
//            }
//            else{

            echo "<div style='margin-top: 25px;padding: 6px;' class='panel panel-danger'>";
            echo "<div style='' class='row'>";
            echo "<div style='' class='container-fluid'>";

            echo "<div class='col-lg-12 no-padding text-center'>
                    <div style='font-size: 14px;' class='text-bold text-red text-uppercase blink'>
                        <i class='fa fa-warning'></i> NILAI SETORAN TIDAK DAPAT DIREVISI <i class='fa fa-warning'></i>
                    </div>
                    <div style='font-size: 12px;' class='text-bold text-muted text-capitalize'>Pastikan Nilai Setoran Anda sudah benar, karena data settlement tidak dapat di revisi setelah settlement berhasil.</div>
                </div>";

            echo "</div>";
            echo "</div>";
            echo "</div>";

            $withOtorisator = 0;

            if ($withOtorisator) {

                echo "<div style='margin-top: 25px;padding: 6px;' class='panel panel-info'>";
                echo "<div style='' class='row'>";
                echo "<div style='' class='container-fluid'>";

                echo "<div class='col-lg-5 no-padding text-right'>
                    <div style='font-size: 14px;' class='text-bold text-muted text-capitalize'>&nbsp;</div>
                    <div class='input-group input-group-md pull-right'>
                  <select class='form-control select-otorisator text-bold text-uppercase'>";
                echo "<option value='0'>PILIH OTORISATOR</option>";

                if (!empty($arrEmployee)) {
                    foreach ($arrEmployee as $i => $eRow) {
                        echo "<option value='" . $eRow->id . "' class='text-bold text-uppercase'>" . $eRow->nama . "</option>";
                    }
                }

                echo "</select>
                    </div>
                </div>";


                echo "<div class='col-lg-7 no-padding text-left pull-right'>
                    <div style='font-size: 14px;' class='text-bold text-muted text-capitalize'>Masukan PIN/OTP PIC SETORAN</div>
                    <div class='input-group input-group-md'>
                        <form autocomplete='off'>
                            <input type='text' autocomplete='false' class='pin-otp-admin-nama' style='display: none;'>
                            <input type='password' autocomplete='off' class='form-control pin-otp-admin' placeholder='MASUKAN PIN / OTP'>
                        </form>
                        <span class='input-group-btn'>
                            <button type='button' disabled class='btn btn-info btn-flat btn-kirim'><i class='fa fa-send'></i> KIRIM SETTLEMENT</button>
                            <button type='button' data-dismiss='modal' class='btn btn-danger btn-flat btn-close-kirim'><i class='fa fa-times'></i></button>
                        </span>
                    </div>
                </div>";

                echo "</div>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style='margin-top:-20px;font-size: 14px;' class='text-bold'><r>*</r> Otorisator adalah person yang ditunjuk perusahaan untuk menerima settlement.</div>";
                echo "</div>";

                echo "<script>

                    var reqOtpSettlement = function(){
                        top.$.ajax({
                            url: '" . base_url() . $this->modul . "/_shoppingCart/reqSettlementOTP/" . $this->jenisTr . "',
                            method: 'post',
                            data: {
                                'shoppingcart_order_id': '$nomer_main',
                                'grand_total': '$totalCash',
                                'main': '" . blobEncode($doSendApi) . "',
                            },
                            success: function(a){

                            }
                        })
                    }

                    var activeBtnNext = function(){

                        top.$('.btn-kirim').attr('disabled', false);
                        top.$('.btn-kirim').off();
                        top.$('.btn-kirim').on('click', function(){
                            var pin_otp = $('.pin-otp-admin').val();
                            var total_setor = removeCommas( $('.form-total-payment').val());
                            var oto_settlement_id = $('select.select-otorisator option:checked').val();
                            var oto_settlement_nama = $('select.select-otorisator option:checked').text();

                            if( pin_otp != '' && total_setor*1>0 && oto_settlement_id*1>0 ){

                                var paymentTmp = $('.form-payment');
                                var arrPayment = []
                                jQuery.each(paymentTmp, function(i,p){
                                    var payID = $(p).attr('payid')
                                    var payNama = $(p).attr('paynama')
                                    var nilai = removeCommas($(p).val())
                                    arrPayment.push({payID:payID,payNama:payNama,nilai:nilai});
                                })

                                top.swal('Memproses Transaksi Settlement')
                                top.swal.showLoading();

                                top.$.ajax({
                                    url: '" . base_url() . $this->modul . "/_shoppingCart/settlementOtorisator',
                                    method: 'post',
                                    data: {
                                        pin_diskon: pin_otp,
                                        nomer_main: $nomer_main,
                                        payment: arrPayment,
                                        terbilang: $('.form-terbilang').text(),
                                        payment_nilai: $paymentNilaiJson,
                                        action_target: '$action_target',
                                        oto_settlement_id: oto_settlement_id,
                                        oto_settlement_nama: oto_settlement_nama,
                                    },
                                    success: function(a){
                                        jsonData = JSON.parse(a);
                                        if(jsonData.status==1){
                                            top.swal('Proses Mengirim Settlement...<br>Mohon jangan menutup Browser Anda.')
                                            top.swal.showLoading();
                                            top.$('#result').load('$action_target', {payment:{
                                                payment: jsonData.dbData.payment,
                                                payment_nilai: jsonData.dbData.payment_nilai,
                                                payment_terbilang: jsonData.dbData.payment_terbilang,
                                                payment_nilai_seharusnya: jsonData.dbData.payment_nilai_seharusnya,
                                                payment_nilai_selisih: jsonData.dbData.payment_nilai_selisih,
                                                selisih_payment: jsonData.dbData.selisih_payment,
                                                payment_oto_id: jsonData.dbData.payment_oto_id,
                                                payment_oto_nama: jsonData.dbData.payment_oto_nama,
                                            }});
                                        }
                                        else{
                                            swal('Error', jsonData.reason, 'warning');
                                            reqOtpSettlement()
                                        }
                                    }
                                })
                            }
                            else{
                                if(pin_otp=='' && total_setor*1>0 && oto_settlement_id*1>0 ){
                                    swal('BELUM LENGKAP', 'PIN/OTP Harus Diisi Sebagai Kontrol Otorisasi.', 'warning');
                                }
                                else if(pin_otp!='' && total_setor*1==0 && oto_settlement_id*1>0 ){
                                    swal('BELUM LENGKAP', 'Nilai Settlement Harus Diisi.', 'warning');
                                }
                                else if(pin_otp!='' && total_setor*1>0 && oto_settlement_id*1==0 ){
                                    swal('BELUM LENGKAP', 'Otorisator Harus Dipilih.', 'warning');
                                }
                                else{
                                    swal('BELUM LENGKAP', 'Nilai Settlement, Otorisator dan PIN/OTP Harus Diisi.', 'warning');
                                }
                            }
                        })
                    }

//                    console.log('" . base_url() . $this->modul . "/_shoppingCart/reqSettlementOTP/" . $this->jenisTr . "');
//                    console.log('nomer_main: $nomer_main');
//                    console.log('grand_total: $totalCash');
//                    console.log('this->jenisTr: " . $this->jenisTr . "');

                    reqOtpSettlement();
            ";
                echo "

                console.log('action_target: $action_target');

                $('.form-payment').off();
                $('.form-payment').on('keyup', function(){
                    $(this).val(addCommas(removeCommas($(this).val())))
                });\n";

                echo "$('.form-payment').on('click', function(){
                    $(this).select()
                });\n";

                echo "$('.form-payment').on('keyup', delay_v2(function(){
                    //hitung all form-payment
                    var formpayment = $('.form-payment')
                    var formtotalpayment = $('.form-total-payment')
                    var formtotalcanceled = $('.form-total-canceled')
                    var formtotalcash = $('.form-total-cash')
                    var formtotalselisih = $('.form-total-selisih')
                    var total = 0
                    var selisih = 0

                    jQuery.each(formpayment, function(a,b){
                        total += removeCommas( $(b).val() )*1 > 0 ? removeCommas( $(b).val() ) : 0
                    })

                    $(formtotalpayment).val( (total*1>0?addCommas(total):total*-1)  )

                    selisih = total - removeCommas($(formtotalcash).val());

                    if(selisih*1>0){
                        $(formtotalselisih).val('+ ' + addCommas(selisih)  ).removeClass('text-red').addClass('text-purple')
                    }
                    else{
                        $(formtotalselisih).val( addCommas(selisih)  ).removeClass('text-purple').addClass('text-red')
                    }

                    if(total*1>0){
                        $('.form-terbilang').html( pembilang(removeCommas(total),1) )
                        $('.panel-terbilang').fadeIn();
                    }
                    else{
                        $('.form-terbilang').html( '' )
                        $('.panel-terbilang').fadeOut();
                    }
                },100));\n";

                echo "
                $('.btn-kirim').off();
                $('.btn-kirim').on('click', function(){
                    var pinotp   = $('.pin-otp-admin').val()
                    var terbilang   = $('.form-terbilang').text()
                    var total_setor = removeCommas( $('.form-total-payment').val() )
                    var cabang_id   = $('#cabang_id').val()
                    var toko_id     = $('#toko_id').val()
                    var oleh_id     = $('#oleh_id').val()
                    var oleh_nama   = $('#oleh_nama').val()

//                    console.log('terbilang', terbilang);
//                    console.log('total_setor', total_setor);
//                    console.log('cabang_id', cabang_id);
//                    console.log('toko_id', toko_id);
//                    console.log('oleh_id', oleh_id);
//                    console.log('oleh_nama', oleh_nama);
//                    console.error('pinotp', pinotp);
                });\n";

                echo "
                        $('.pin-otp-admin').off();
                        $('.pin-otp-admin').on('click', function(){
                        $(this).select();
                    });\n";

                echo "$('.pin-otp-admin').on('keyup', delay_v2( function(){
                    var pinotp   = $(this).val()
                    if( pinotp.length >= 6 ){
//                        console.log( $(this).val() );
                        activeBtnNext()
                    }
                    else{
                        top.$('.btn-kirim').attr('disabled', true);
                        top.$('.btn-kirim').off('click');
                    }
                }, 500));\n";

                echo "$('.form-payment').trigger('keyup')\n";

                echo "</script>";
            }
            else {

                //=======================================================
                //=================== TANPA OTORISASI ===================
                //=======================================================

//                $payment = $_POST['payment'];
//                $terbilang = $_POST['terbilang'];
//                $arrPayment = array();
//                if(!empty($payment)){
//                    foreach($payment as $k => $rowp){
//                        $arrPayment[$rowp['payID']] = $rowp;
//                    }
//                }
//                $pin = $_POST['pin_diskon'];
//                $selisihPayment = array();
//                $totalSetor = 0;
//                $totalSetorSeharusnya = 0;
//                if(!empty($payment_nilai)){
//                    foreach($payment_nilai as $payid => $paynilai){
//                        $nilai_setor = $arrPayment[$payid]['nilai']*1;
//                        $pay_nama = $arrPayment[$payid]['payNama'];
//                        $nilai_seharusnya = $paynilai*1;
//                        $totalSetor += $nilai_setor;
//                        $totalSetorSeharusnya += $nilai_seharusnya;

//                        if($nilai_seharusnya-$nilai_setor <= 0){
//                            //selisih plus
//                            $status_selisih = ($nilai_seharusnya-$nilai_setor) == 0 ? "passed" : "plus";
//                            $selisihPayment[$payid] = array(
//                                "nama" => $pay_nama,
//                                "nilai_setor" => $nilai_setor,
//                                "nilai_seharusnya" => $nilai_seharusnya,
//                                "selisih" => ($nilai_setor-$nilai_seharusnya),
//                                "status_selisih" => $status_selisih,
//                            );
//                        }
//                        else{
//                            //selisih minus
//                            $selisihPayment[$payid] = array(
//                                "nama" => $pay_nama,
//                                "nilai_setor" => $nilai_setor,
//                                "nilai_seharusnya" => $nilai_seharusnya,
//                                "selisih" => ($nilai_setor-$nilai_seharusnya),
//                                "status_selisih" => "minus",
//                            );
//                        }
//                    }
//                }
//
//                $dbData['payment'] =  $arrPayment;
//                $dbData['payment_nilai'] =  $totalSetor;
//                $dbData['payment_terbilang'] =  $terbilang;
//                $dbData['payment_nilai_seharusnya'] =  $totalSetorSeharusnya;
//                $dbData['payment_nilai_selisih'] =  $totalSetor-$totalSetorSeharusnya;
//                $dbData['selisih_payment'] =  $selisihPayment;
//                $dbData['payment_oto_id'] =  $oto_settlement_id;
//                $dbData['payment_oto_nama'] =  $oto_settlement_nama;


                echo "<div style='margin-top: 25px;padding: 6px;' class='panel panel-info'>";
                echo "<div style='' class='row'>";
                echo "<div style='' class='container-fluid'>";
                echo "<div id='btn-pembayaran-danger' class='btn btn-md btn-danger pull-right'> BATAL </div>";
                echo "<div id='btn-pembayaran-success' class='btn btn-md btn-success pull-right'> LANJUTKAN SETTLEMENT </div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                echo "<script>

                    var activeBtnNext = function(){

                        top.$('#btn-pembayaran-success').attr('disabled', false);
                        top.$('#btn-pembayaran-success').off();
                        top.$('#btn-pembayaran-success').on('click', function(){

                            var total_setor = removeCommas( $('.form-total-payment').val());
                            var paymentNilaiJson = $paymentNilaiJson;
                            var action_target = '$action_target';

                            if( total_setor*1>0 ){

                                var paymentTmp = $('.form-payment');
                                var arrPayment = []
                                jQuery.each(paymentTmp, function(i,p){
                                    var payID = $(p).attr('payid')
                                    var payNama = $(p).attr('paynama')
                                    var nilai = removeCommas($(p).val())
                                    arrPayment.push({payID:payID,payNama:payNama,nilai:nilai});
                                })

                                var total_dibayar = 0;
                                var arrPayment_f = {};
                                jQuery.each(arrPayment, function(a, b){
                                    total_dibayar += b.nilai;
                                    arrPayment_f[b.payID] = b
                                })

                                var totalSetor = 0;
                                var payment_nilai_seharusnya = 0;
                                var totalSetorSeharusnya = 0;
                                var arr_selisih_payment = {};

                                jQuery.each(paymentNilaiJson, function(a, b){
                                    payment_nilai_seharusnya += b;

                                    nilai_setor = arrPayment_f[a]['nilai']*1;
                                    pay_nama = arrPayment_f[a]['payNama'];
                                    nilai_seharusnya = b*1;
                                    totalSetor += nilai_setor;
                                    totalSetorSeharusnya += nilai_seharusnya;

                                    if(nilai_seharusnya-nilai_setor <= 0){
                                        //selisih plus
                                        status_selisih = (nilai_seharusnya-nilai_setor) == 0 ? 'passed' : 'plus';
                                        arr_selisih_payment[a] = {
                                            'nama' : pay_nama,
                                            'nilai_setor' : nilai_setor,
                                            'nilai_seharusnya' : nilai_seharusnya,
                                            'selisih' : (nilai_setor-nilai_seharusnya),
                                            'status_selisih' : status_selisih,
                                        }
                                    }
                                    else{
                                        //selisih minus
                                        arr_selisih_payment[a] = {
                                            'nama' : pay_nama,
                                            'nilai_setor' : nilai_setor,
                                            'nilai_seharusnya' : nilai_seharusnya,
                                            'selisih' : (nilai_setor-nilai_seharusnya),
                                            'status_selisih' : 'minus',
                                        }
                                    }
                                })

                                var payment_nilai_selisih = total_dibayar-payment_nilai_seharusnya;

                                if(payment_nilai_selisih*1<0){

                                    swal({
                                      title: 'nilai settlement kurang dari nilai seharusnya, tetap lanjutkan settlement..??',
                                      type: 'warning',
                                      showCancelButton: true,
                                      confirmButtonText: 'Lanjut',
                                    }).then((result) => {
                                      if(result) {
                                        top.swal('Proses Mengirim Settlement...<br>Mohon jangan menutup Browser Anda.')
                                        top.swal.showLoading();
                                        top.$('#result').load(action_target, {
                                            payment:{
                                                payment: arrPayment,
                                                payment_nilai: totalSetor,
                                                payment_terbilang: $('.form-terbilang').text(),
                                                payment_nilai_seharusnya: payment_nilai_seharusnya,
                                                payment_nilai_selisih: payment_nilai_selisih,
                                                selisih_payment: arr_selisih_payment,
                                            }
                                        });
                                      }
                                    });

                                }
                                else{
                                    top.swal('Proses Mengirim Settlement...<br>Mohon jangan menutup Browser Anda.')
                                    top.swal.showLoading();
                                    top.$('#result').load(action_target, {
                                        payment:{
                                            payment: arrPayment,
                                            payment_nilai: totalSetor,
                                            payment_terbilang: $('.form-terbilang').text(),
                                            payment_nilai_seharusnya: payment_nilai_seharusnya,
                                            payment_nilai_selisih: payment_nilai_selisih,
                                            selisih_payment: arr_selisih_payment,
                                        }
                                    });
                                }
                            }
                            else{
                                swal('BELUM LENGKAP', 'Nilai Settlement Harus Diisi.', 'warning');
                            }
                        })
                    }

                $('.form-payment').off();
                $('.form-payment').on('keyup', function(){
                    $(this).val(addCommas(removeCommas($(this).val())))
                });\n";

                echo "$('.form-payment').on('click', function(){
                    $(this).select()
                });\n";

                echo "
                    $('#btn-pembayaran-danger').on('click', function(){
                        top.BootstrapDialog.closeAll();
                    });
                \n";

                echo "$('.form-payment').on('keyup', delay_v2(function(){

                            //hitung all form-payment
                            var formpayment = $('.form-payment');
                            var formtotalpayment = $('.form-total-payment');
                            var formtotalcanceled = $('.form-total-canceled');
                            var formtotalcash = $('.form-total-cash');
                            var formtotalselisih = $('.form-total-selisih');
                            var total = 0;
                            var selisih = 0;

                            jQuery.each(formpayment, function(a,b){
                                total += removeCommas( $(b).val() )*1 > 0 ? removeCommas( $(b).val() ) : 0
                            });

                            $(formtotalpayment).val( (total*1>0?addCommas(total):total*-1) );

                            selisih = total - removeCommas($(formtotalcash).val());

                            if(selisih*1>0){
                                $(formtotalselisih).val('+ ' + addCommas(selisih) ).removeClass('text-red').addClass('text-purple')
                            }
                            else{
                                $(formtotalselisih).val( addCommas(selisih) ).removeClass('text-purple').addClass('text-red')
                            }

                            if(total*1>0){
                                $('.form-terbilang').html( pembilang(removeCommas(total),1) )
                                $('.panel-terbilang').fadeIn();
                                activeBtnNext()
                            }
                            else{
                                $('.form-terbilang').html('')
                                $('.panel-terbilang').fadeOut();

                                top.$('#btn-pembayaran-success').attr('disabled', true);
                                top.$('#btn-pembayaran-success').off('click');
                            }

                      }, 100));\n";

                echo "$('.form-payment').trigger('keyup')\n";


                echo "</script>";
            }

            if ($titip_setoran && count($mems) == 1 && in_array("o_seller_spv", $mems) || count($mems) == 1 && in_array("o_holding", $mems)) {


            }


        }
    }

    public function doSettlementPos()
    {
        $timeStart = date('Y-m-d H:i:s');
        $selectedRegistries = array("main", "items");
        $currentTr = $this->uri->segment(4);
        $targetModul = $this->uri->segment(5);
        $targetJenis = $this->uri->segment(6);
        $payment_info = $_POST['payment'];
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $this->load->model("Mdls/MdlHargaProduk");
        $prc = new MdlHargaProduk();
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("oleh_id='" . $this->session->login['id'] . "'");
        $tr->addFilter("settlement_id='1'");
        $tr->addFilter("link_id='0'");

        $this->db->where("jenis IN('4464','749')");

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : mati_disini("Settlement gagal disimpan. Sesi anda habis, silahkan login ulang.");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : mati_disini("Settlement gagal disimpan. Sesi anda habis, silahkan login ulang.");

//        if( count($mems)==1 && in_array("o_seller_spv", $mems) || count($mems)==1 && in_array("o_holding", $mems) ){
//
//        }
//        else{
////            $this->db->where("fulldate>='" . $date1 . "'");
////            $this->db->where("fulldate<='" . $date2 . "'");
//        }

        $tempTrans = $tr->lookUpAll()->result();
        showLast_query("merah");
        // arrPrintHijau($tempTrans);
        // $origJenis = $tempTrans[0]->jenis;
        $origJenis = 7761;
        $stepNum = 1;
        $tCode = $this->configUi[$origJenis]['steps'][$stepNum]['target'];
        // arrPrintHijau($this->configUi);

        $ids = array();
        $idsCancel = array();
        $idsNett = array();
        $dataEncoded = array();
        $oleh_id_check = array();
        $oleh_nama_check = array();
        $extern = array();
        if (sizeof($tempTrans) > 0) {
            foreach ($tempTrans as $temp) {
                $ids[$temp->id] = $temp->id;
                $oleh_id_check[$temp->oleh_id] = $temp->id;
                $oleh_nama_check[$temp->oleh_id] = $temp->oleh_nama;
                $extern = array(
                    "id" => $temp->oleh_id,
                    "nama" => $temp->oleh_nama,
                );
                if ($temp->trash_4 == 1) {
                    $idsCancel[$temp->id] = $temp->id;
                }
                else {
                    $idsNett[$temp->id] = $temp->id;
                }
            }

            $titip_setoran = 0;
            if (!isset($oleh_id_check[$this->session->login['id']])) {
                $titip_setoran = 1;
                foreach ($oleh_id_check as $olhID) {
                    $titip_setoran_nama = $oleh_nama_check[$olhID];
                }
                $nama_penyetor = $this->session->login['nama'];
            }

            //panggil library curl untuk koneksi ke API server UTAMA
            $this->load->library("Curl");
            $curl = New Curl();
            //ambil register
            $tr->setFilters(array());
            $tempReg = $tr->lookupBaseDataRegistries($ids)->result();
            $newItems = array();
            $tempItems = array();
            $sumProduk = array();
            $sumProdukCanceled = array();
            $itemsTrans = array();
            $itemsTransCanceled = array();
            $totalCash = 0;
            $totalDiskonProduk = 0;
            $totalDiskonCust = 0;
            $totalHargaJual = 0;
            $totalDppPPn = 0;
            $totalPPn = 0;
            $totalNonDpp = 0;
            $totalCukai = 0;
            $totalCancel = 0;
            $totalCashCanceled = 0;
            $totalAddDiskonCanceled = 0;
            $totalDiskonProdukCanceled = 0;
            $totalDiskonCustCanceled = 0;
            $totalHargaJualCanceled = 0;
            $totalDppPPnCanceled = 0;
            $totalPPnCanceled = 0;
            $totalNonDppCanceled = 0;
            $totalCukaiCanceled = 0;
            $totalAddDiskon = 0;
            $freeProduk = array();
            $freeProdukCanceled = array();
            $preInjectTrData = array();
            $preInjectTrDataCanceled = array();
            $keyToPoint = array(
                "customerID",
                "customerName",
                "tokoID",
                "tokoNama",
                "cabangID",
                "cabangName",
                "jenisTr",
                "dtime",
                "paymentMethod",
            );
            $diskonProdukCanceled = 0;
            if (sizeof($tempReg) > 0) {
                foreach ($tempReg as $regData) {
                    $trID = $regData->transaksi_id;
                    if (!isset($idsCancel[$trID])) {
                        $main = blobdecode($regData->main);
                        $items = blobdecode($regData->items);
                        $preInjectTrData[$trID] = $main["nomer"];
                        $preItems = array();

                        $hargaJual = 0;
                        $dppPPn = 0;
                        $pPn = 0;
                        $nonDpp = 0;
                        $cukai = 0;

                        $preItemsCanceled = array();

                        $hargaJualCanceled = 0;
                        $dppPPnCanceled = 0;
                        $pPnCanceled = 0;
                        $nonDppCanceled = 0;
                        $cukaiCanceled = 0;

                        foreach ($items as $itemDataTmp) {
                            $diskonProduk = 0;
                            if ($itemDataTmp['jenisTr'] == $currentTr) {
                                //bagian jual
                                if (isset($itemDataTmp['free_items'])) {
                                    $freeProduk[] = $itemDataTmp['free_items'];
                                }
                                $itemDataTmp['cukai_produk'] = 0;
                                $tmpSumItems = array(
                                    "id" => $itemDataTmp['id'],
                                    "produk_id" => $itemDataTmp['id'],
                                    "nama" => $itemDataTmp['nama'],
                                    "produk_nama" => $itemDataTmp['nama'],
                                    "barcode" => $itemDataTmp['barcode'],
                                    "kode" => $itemDataTmp['barcode'],
                                    "produk_kode" => $itemDataTmp['barcode'],
                                    "jml" => $itemDataTmp['jml'],
                                    "produk_qty" => $itemDataTmp['jml'],
                                    "qty_unit" => $itemDataTmp['qty_unit'],
                                    "harga_list" => $itemDataTmp['harga_list'],
                                    "harga_jual" => $itemDataTmp['harga_jual'],
                                    "discNilai" => $itemDataTmp['discNilai'],
                                    "discPersen" => $itemDataTmp['discPersen'],
                                    "harga" => $itemDataTmp['harga'],
                                    "subtotal" => $itemDataTmp['subtotal'],
                                    "ppn_produk" => isset($itemDataTmp['tipe_pajak']) ? $itemDataTmp['tipe_pajak'] : 1,
                                    "ppnFactor" => $itemDataTmp['ppnFactor'],
                                    "ppn" => $itemDataTmp['ppn'],
                                    "sub_ppn" => $itemDataTmp['sub_ppn'],
                                    "satuan" => $itemDataTmp['satuan'],
                                    "satuan_id" => $itemDataTmp['satuan_id'],
                                    "satuan_factor_qty" => $itemDataTmp['satuan_factor_qty'],
                                    "satuan_factor_id" => $itemDataTmp['satuan_factor_id'],
                                    "satuan_factor_nama" => $itemDataTmp['satuan_factor_nama'],
                                );

                                $sumProduk[$itemDataTmp["id"]][] = $tmpSumItems;
                                $preItems[$main["nomer"]][$itemDataTmp["id"]] = $tmpSumItems;

                                $diskonProduk += $itemDataTmp['discNilai'] * 1 > 0 ? $itemDataTmp['discNilai'] * $itemDataTmp['jml'] : 0;
                                $hargaJual += $itemDataTmp['harga_jual'] * 1 > 0 ? $itemDataTmp['harga_jual'] * $itemDataTmp['jml'] : 0;
                                $dppPPn += $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;
                                $pPn += $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['sub_ppn'] : 0;
                                $nonDpp += $itemDataTmp['ppn_produk'] * 1 == 0 ? $itemDataTmp['subtotal'] : 0;
                                $cukai += $itemDataTmp['cukai_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;

                            }
                            elseif ($itemDataTmp['jenisTr'] == "982") {

                                //bagian cancel
                                if (isset($itemDataTmp['free_items'])) {
                                    $freeProdukCanceled[] = $itemDataTmp['free_items'];
                                }

                                $itemDataTmp['cukai_produk'] = 0;
                                $tmpSumItems = array(
                                    "id" => $itemDataTmp['id'],
                                    "produk_id" => $itemDataTmp['id'],
                                    "nama" => $itemDataTmp['nama'],
                                    "produk_nama" => $itemDataTmp['nama'],
                                    "barcode" => isset($itemDataTmp['barcode']) ? $itemDataTmp['barcode'] : "",
                                    "kode" => isset($itemDataTmp['barcode']) ? $itemDataTmp['barcode'] : "",
                                    "produk_kode" => isset($itemDataTmp['barcode']) ? $itemDataTmp['barcode'] : "",
                                    "jml" => $itemDataTmp['jml'],
                                    "produk_qty" => $itemDataTmp['jml'],
                                    "harga_list" => $itemDataTmp['harga_list'],
                                    "harga_jual" => $itemDataTmp['harga_jual'],
                                    "discNilai" => $itemDataTmp['discNilai'],
                                    "discPersen" => $itemDataTmp['discPersen'],
                                    "harga" => $itemDataTmp['harga'],
                                    "subtotal" => $itemDataTmp['subtotal'],
                                    "ppn_produk" => isset($itemDataTmp['tipe_pajak']) ? $itemDataTmp['tipe_pajak'] : "",
                                    "ppnFactor" => $itemDataTmp['ppnFactor'],
                                    "ppn" => $itemDataTmp['ppn'],
                                    "sub_ppn" => $itemDataTmp['sub_ppn'],
                                    "satuan" => $itemDataTmp['satuan'],
                                    "satuan_id" => $itemDataTmp['satuan_id'],
                                );

                                $sumProdukCanceled[$itemDataTmp["id"]][] = $tmpSumItems;
                                $preItemsCanceled[$main["nomer"]][$itemDataTmp["id"]] = $tmpSumItems;

                                $diskonProdukCanceled += $itemDataTmp['discNilai'] * 1 > 0 ? $itemDataTmp['discNilai'] * $itemDataTmp['jml'] : 0;
                                $hargaJualCanceled += $itemDataTmp['harga_jual'] * 1 > 0 ? $itemDataTmp['harga_jual'] * $itemDataTmp['jml'] : 0;
                                $dppPPnCanceled += $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;
                                $pPnCanceled += $itemDataTmp['ppn_produk'] * 1 > 0 ? $itemDataTmp['sub_ppn'] : 0;
                                $nonDppCanceled += $itemDataTmp['ppn_produk'] * 1 == 0 ? $itemDataTmp['subtotal'] : 0;
                                $cukaiCanceled += $itemDataTmp['cukai_produk'] * 1 > 0 ? $itemDataTmp['subtotal'] : 0;

                            }

                        }

                        if ($main["jenisTr"] == $currentTr) {
                            /* -----------------------------------------------------------
                             * pengakimulasi point per customer dalam satu setlement
                             * -----------------------------------------------------------*/
                            if (!isset($newPoints[$main['customerID']]['qty_point'])) {
                                $newPoints[$main['customerID']]['qty_point'] = 0;
                            }
                            $newPoints[$main['customerID']]['qty_point'] += $main['point_transaksi'];
                            $point_hpp = isset($main['point_hpp']) ? $main['point_hpp'] : 0;
                            if (!isset($newPoints[$main['customerID']]['hpp_point'])) {
                                $newPoints[$main['customerID']]['hpp_point'] = 0;
                            }
                            $newPoints[$main['customerID']]['hpp_point'] += $point_hpp;
                            $newPoints[$main['customerID']]['hpp_point_satuan'] = $point_hpp;

                            $newPoints[$main['customerID']]['id'] = $main['customerID'];
                            $newPoints[$main['customerID']]['nama'] = $main['customerName'];
                            $newPoints[$main['customerID']]['transaksi_nilai'] = $main['grand_total'];
                            $newPoints[$main['customerID']]['dtime'] = $main['dtime'];
                            $newPoints[$main['customerID']]['nomer'] = isset($main['nomer']) ? $main['nomer'] : (isset($main['nomor']) ? $main['nomor'] : 0);

                            foreach ($keyToPoint as $point_key) {
                                $newPoints[$main['customerID']][$point_key] = $main[$point_key];
                            }
                            // -----------------------------------------------------------
                            $totalCash += $main["grand_total"];
                            $totalDiskonCust += isset($main["disc"]) ? $main["disc"] : 0;
                            $totalAddDiskon += isset($main["add_disc"]) ? $main["add_disc"] * 1 : 0;

                            $totalDiskonProduk += $diskonProduk;
                            $totalHargaJual += $hargaJual;
                            $totalDppPPn += $dppPPn;
                            $totalPPn += $pPn;
                            $totalNonDpp += $nonDpp;
                            $totalCukai += $cukai;

                            $buildMainTmp = array(
                                "olehID" => $main['olehID'],
                                "sellerID" => $main['olehID'],
                                "customerID" => $main['customerID'],
                                "customerName" => $main['customerName'],
                                "olehName" => $main['olehName'],
                                "sellerName" => $main['olehName'],
                                "placeID" => $main['placeID'],
                                "placeName" => $main['placeName'],
                                "cabangID" => $main['cabangID'],
                                "cabangName" => $main['cabangName'],
                                "gudangID" => $main['gudangID'],
                                "gudangName" => $main['gudangName'],
                                "divID" => $main['divID'],
                                "tokoID" => $main['tokoID'],
                                "tokoNama" => $main['tokoNama'],
                                "stepCode" => $main['stepCode'],
                                "dtime" => $main['dtime'],
                                "fulldate" => $main['fulldate'],
                                "harga" => $main['harga'],
                                "grand_total" => $main['grand_total'],
                                "bayar" => $main['bayar'],
                                "oto_diskon" => isset($main['oto_diskon']) ? $main['oto_diskon'] : "",
                                "cash_account" => $main['cash_account'],
                                "cash_account__label" => $main['cash_account__label'],

                                "diskon_produk" => $diskonProduk, //diskon dari potongan produk
                                "add_disc" => isset($main["add_disc"]) ? $main["add_disc"] * 1 : 0,
                                "diskon_tambahan_nilai" => isset($main["diskon_tambahan_nilai"]) ? $main["diskon_tambahan_nilai"] * 1 : 0, //diskon customer

                                "harga_jual" => $hargaJual,
                                "dpp_ppn" => $dppPPn,
                                "ppn" => $pPn,
                                "non_dpp" => $nonDpp,
                                "cukai" => $cukai,

                                "transaksi_id" => $main['transaksi_id'],
                                "paymentMethod_cash" => $main['paymentMethod_cash'],

                                "harga_nett2" => $main['harga'],
                                "tagihan" => $main['grand_total'],
                                "kembali" => $main['kembali'],
                                "machineID" => $main['machineID'],
                                "cpu_info" => $main['CPU_INFO'],
                                "com_info" => $main['COM_INFO'],
                            );

                            $itemsbase64 = base64_encode(json_encode($preItems[$main["nomer"]]));
                            $tempItems = $buildMainTmp + array("produk" => $itemsbase64, "nomor" => $main["nomer"]);
                            $newItems["items"][] = (object)$tempItems;
                            $itemsTrans[$trID] = $tempItems;

                            $newPoints_2["customerPoint"] = $newPoints;
                        }
                        else {

                            $totalCashCanceled += $main["grand_total"];
                            $totalDiskonCustCanceled += isset($main["disc"]) ? $main["disc"] : 0;
                            $totalAddDiskonCanceled += isset($main["add_disc"]) ? $main["add_disc"] * 1 : 0;

                            $totalDiskonProdukCanceled += $diskonProdukCanceled;
                            $totalHargaJualCanceled += $hargaJualCanceled;
                            $totalDppPPnCanceled += $dppPPnCanceled;
                            $totalPPnCanceled += $pPnCanceled;
                            $totalNonDppCanceled += $nonDppCanceled;
                            $totalCukaiCanceled += $cukaiCanceled;

                            $buildMainTmpCanceled = array(
                                "olehID" => $main['olehID'],
                                "sellerID" => $main['olehID'],
                                "olehName" => $main['olehName'],
                                "sellerName" => $main['olehName'],
                                "customerID" => $main['customerID'],
                                "customerName" => $main['customerName'],
                                "placeID" => $main['placeID'],
                                "placeName" => $main['placeName'],
                                "cabangID" => $main['cabangID'],
                                "cabangName" => $main['cabangName'],
                                "gudangID" => $main['gudangID'],
                                "gudangName" => $main['gudangName'],
                                "divID" => $main['divID'],
                                "tokoID" => $main['tokoID'],
                                "tokoNama" => $main['tokoNama'],
                                "stepCode" => $main['stepCode'],
                                "dtime" => $main['dtime'],
                                "fulldate" => $main['fulldate'],
                                "harga" => $main['harga'],
                                "grand_total" => $main['grand_total'],
                                "bayar" => $main['bayar'],
                                "oto_diskon" => isset($main['oto_diskon']) ? $main['oto_diskon'] : "",
                                "cash_account" => $main['cash_account'],
                                "cash_account__label" => $main['cash_account__label'],

                                "diskon_produk" => $diskonProduk, //diskon dari potongan produk
                                "add_disc" => isset($main["add_disc"]) ? $main["add_disc"] * 1 : 0,
                                "diskon_tambahan_nilai" => isset($main["diskon_tambahan_nilai"]) ? $main["diskon_tambahan_nilai"] * 1 : 0, //diskon customer

                                "harga_jual" => $hargaJual,
                                "dpp_ppn" => $dppPPn,
                                "ppn" => $pPn,
                                "non_dpp" => $nonDpp,
                                "cukai" => $cukai,

                                "transaksi_id" => $main['transaksi_id'],
                                "paymentMethod_cash" => $main['paymentMethod_cash'],

                                "harga_nett2" => $main['harga'],
                                "tagihan" => $main['grand_total'],
                                "kembali" => $main['kembali'],

                                "machineID" => $main['machineID'],
                                "cpu_info" => $main['CPU_INFO'],
                                "com_info" => $main['COM_INFO'],
                            );

                            $itemsbase64Canceled = base64_encode(json_encode($preItemsCanceled[$main["nomer"]]));
                            $tempItemsCanceled = $buildMainTmpCanceled + array("produk" => $itemsbase64Canceled, "nomor" => $main["nomer"]);
                            $newItemsCanceled["items"][] = (object)$tempItemsCanceled;
                            $itemsTransCanceled[$trID] = $tempItemsCanceled;

                        }
                    }
                    else {
                        $main = blobdecode($regData->main);
                        $totalCancel += isset($idsCancel[$trID]) ? $main["grand_total"] : 0;
                    }
                }
            }
            else {
                matiHEre("no data to execute. silahkan relogin untuk membersihkan sesi");
            }
            $freeProdukSum = array();
            if (!empty($freeProduk)) {
                foreach ($freeProduk as $ky => $arrDistProduk) {
                    foreach ($arrDistProduk as $idDisc => $distProduk) {
                        if (!isset($freeProdukSum[$distProduk['free_produk_id']])) {
                            $freeProdukSum[$distProduk['free_produk_id']] = array();
                        }
                        if (!isset($freeProdukSum[$distProduk['free_produk_id']]['qty'])) {
                            $freeProdukSum[$distProduk['free_produk_id']]['qty'] = 0;
                        }
                        $freeProdukSum[$distProduk['free_produk_id']]['nama_produk'] = $distProduk['free_produk_nama'];
                        $freeProdukSum[$distProduk['free_produk_id']]['qty'] += $distProduk['free_qty'];
                    }
                }
            }
            $freeProdukCanceledSum = array();
            if (!empty($freeProdukCanceled)) {
                foreach ($freeProdukCanceled as $ky => $arrDistProduk) {
                    foreach ($arrDistProduk as $idDisc => $distProduk) {
                        if (!isset($freeProdukCanceledSum[$distProduk['free_produk_id']])) {
                            $freeProdukCanceledSum[$distProduk['free_produk_id']] = array();
                        }
                        if (!isset($freeProdukCanceledSum[$distProduk['free_produk_id']]['qty'])) {
                            $freeProdukCanceledSum[$distProduk['free_produk_id']]['qty'] = 0;
                        }
                        $freeProdukCanceledSum[$distProduk['free_produk_id']]['nama_produk'] = $distProduk['free_produk_nama'];
                        $freeProdukCanceledSum[$distProduk['free_produk_id']]['qty'] += $distProduk['free_qty'];
                    }
                }
            }
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id in (" . implode(",", $idsNett) . ")");
            $trData = $tr->lookupTransaksiData()->result();
            $newTrData = array();
            $newTrDataCanceled = array();
            $idsNettPenjualan = array();
            $idsNettCanceled = array();
            if (sizeof($trData) > 0) {
                $newNr = array();
                $newNrCanceled = array();
                foreach ($trData as $iix => $trDataTmp) {
                    if ($trDataTmp->sub_tail_code == $currentTr) {
                        $buildDataTr = array(
                            "id" => $trDataTmp->id,
                            "transaksi_id" => $trDataTmp->transaksi_id,
                            "produk_jenis" => $trDataTmp->produk_jenis,
                            "produk_id" => $trDataTmp->produk_id,
                            "produk_nama" => $trDataTmp->produk_nama,
                            "valid_qty" => $trDataTmp->valid_qty,
                            "produk_ord_jml" => $trDataTmp->produk_ord_jml,
                            "produk_ord_hrg" => $trDataTmp->produk_ord_hrg,
                            "detail_tipe" => $trDataTmp->detail_tipe,
                            "sub_tail_code" => $trDataTmp->sub_tail_code,
                            "sub_tail_number" => $trDataTmp->sub_tail_number,
                            "next_substep_num" => $trDataTmp->next_substep_num,
                            "sub_step_avail" => $trDataTmp->sub_step_avail,
                            "sub_step_current" => $trDataTmp->sub_step_current,
                            "sub_step_number" => $trDataTmp->sub_step_number,
                        );
                        $trNr = (array)$buildDataTr + array("nomor" => $preInjectTrData[$trDataTmp->transaksi_id]);
                        $newNr[$iix] = (object)$trNr;
                        $idsNettPenjualan[$trDataTmp->transaksi_id] = $trDataTmp->transaksi_id;
                    }
                    else {
                        $buildDataTrCanceled = array(
                            "id" => $trDataTmp->id,
                            "transaksi_id" => $trDataTmp->transaksi_id,
                            "produk_jenis" => $trDataTmp->produk_jenis,
                            "produk_id" => $trDataTmp->produk_id,
                            "produk_nama" => $trDataTmp->produk_nama,
                            "valid_qty" => $trDataTmp->valid_qty,
                            "produk_ord_jml" => $trDataTmp->produk_ord_jml,
                            "produk_ord_hrg" => $trDataTmp->produk_ord_hrg,
                            "detail_tipe" => $trDataTmp->detail_tipe,
                            "sub_tail_code" => $trDataTmp->sub_tail_code,
                            "sub_tail_number" => $trDataTmp->sub_tail_number,
                            "next_substep_num" => $trDataTmp->next_substep_num,
                            "sub_step_avail" => $trDataTmp->sub_step_avail,
                            "sub_step_current" => $trDataTmp->sub_step_current,
                            "sub_step_number" => $trDataTmp->sub_step_number,
                        );
                        $trNrCanceled = (array)$buildDataTrCanceled + array("nomor" => $preInjectTrData[$trDataTmp->transaksi_id]);
                        $newNrCanceled[$iix] = (object)$trNrCanceled;
                        $idsNettCanceled[$trDataTmp->transaksi_id] = $trDataTmp->transaksi_id;
                    }
                }
                $newTrData["trData"] = $newNr;
                $newTrDataCanceled["trData"] = $newNrCanceled;
            }
            $totalPoint = 0;
            if (!empty($newPoints_2)) {
                foreach ($newPoints_2 as $custP => $datas) {
                    foreach ($datas as $idp => $dat) {
                        $totalPoint += $dat['qty_point'];
                    }
                }
            }
            $doSendApi = array(
                "row" => count($tempTrans),
                "currentTr" => $currentTr,
                "targetJenis" => $targetJenis,
                "login" => $this->session->login,
                "titip_setoran" => $titip_setoran,
                "nama_penyetor" => $nama_penyetor,
                "extern" => $extern,
                "newItems" => $newItems,
                "sumProduk" => $sumProduk,
                "itemsTrans" => $itemsTrans,
                "itemsTransCanceled" => $itemsTransCanceled,
                "sumProdukCanceled" => $sumProdukCanceled,
                "newItemsCanceled" => $newItemsCanceled,
                "freeProduk" => $freeProduk,
                "freeProdukSum" => $freeProdukSum,
                "freeProdukCanceledSum" => $freeProdukCanceledSum,
                "totalCash" => $totalCash,
                "totalDiskonProduk" => $totalDiskonProduk,
                "totalAddDiskon" => $totalAddDiskon,
                "totalDiskonCust" => $totalDiskonCust,
                "totalCashCanceled" => $totalCashCanceled,
                "totalDiskonProdukCanceled" => $totalDiskonProdukCanceled,
                "totalAddDiskonCanceled" => $totalAddDiskonCanceled,
                "totalDiskonCustCanceled" => $totalDiskonCustCanceled,
                "newTrData" => $newTrData,
                "newTrDataCanceled" => $newTrDataCanceled,
                "ids" => $ids,
                "idsCancel" => $idsCancel,
                "idsNett" => $idsNett,
                "idsNettPenjualan" => $idsNettPenjualan,
                "idsNettCanceled" => $idsNettCanceled,
                "payment" => $payment_info,
                "newPointCustomer" => $newPoints_2,
                "totalPoint" => $totalPoint,
                "debug" => 0, // <<==== MENGATUR DEBUG DI SERVER API TUJUAN
            );
            $dataEncoded = array(
                "data" => blobEncode($doSendApi)
            );
        }

        $this->db->trans_begin();
        $ids = array();
        $idsReturn = array();

        if (sizeof($tempTrans) > 0) {
            foreach ($tempTrans as $temp) {
                $ids[$temp->id] = $temp->id;
            }

            //region build items dari main ke gerbang items
            $tr->setFilters(array());
            $tempReg = $tr->lookupBaseDataRegistries($ids)->result();

            $newItems = array();
            $tempItems = array();
            $sumProduk = array();
            $itemsTrans = array();
            $preInjectTrData = array();
            if (sizeof($tempReg) > 0) {
                $newPoints = array();
                foreach ($tempReg as $regData) {
                    $trID = $regData->transaksi_id;
                    $main = blobdecode($regData->main);
                    $items = blobdecode($regData->items);
                    $preInjectTrData[$trID] = $main["nomer"];
                    $itemsbase64 = base64_encode(json_encode($items));
                    $tempItems = $main + array("produk" => $itemsbase64, "nomor" => $main["nomer"]);
                    $newItems["items"][] = (object)$tempItems;
                    /* -----------------------------------------------------------
                     * pengakimulasi point per customer dalam satu setlement
                     * -----------------------------------------------------------*/
                    if (!isset($newPoints[$tempItems['customerID']]['qty_point'])) {
                        $newPoints[$tempItems['customerID']]['qty_point'] = 0;
                    }
                    $newPoints[$tempItems['customerID']]['qty_point'] += $tempItems['point_transaksi'];
                    $point_hpp = isset($main['point_hpp']) ? $main['point_hpp'] : 0;
                    if (!isset($newPoints[$tempItems['customerID']]['hpp_point'])) {
                        $newPoints[$tempItems['customerID']]['hpp_point'] = 0;
                    }
                    $newPoints[$tempItems['customerID']]['hpp_point'] += $point_hpp;
                    $newPoints[$tempItems['customerID']]['hpp_point_satuan'] = $point_hpp;
                    foreach ($keyToPoint as $point_key) {
                        $newPoints[$main['customerID']][$point_key] = $main[$point_key];
                    }
                    // -----------------------------------------------------------
                    $itemsTrans[$trID] = $tempItems;
                    foreach ($items as $itemDataTmp) {
                        $sumProduk[$itemDataTmp["id"]][] = $itemDataTmp;
                    }
                }
            }
            else {
                matiHEre("no data to execute. silahkan relogin untuk membersihkan sesi");
            }
            //endregion

            $pointCustomer['point_customer'] = $newPoints;

            //region build trData
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id in (" . implode(",", $ids) . ")");
            $trData = $tr->lookupTransaksiData()->result();
            $newTrData = array();
            if (sizeof($trData) > 0) {
                $newNr = array();
                foreach ($trData as $iix => $trDataTmp) {
                    $trNr = (array)$trDataTmp + array("nomor" => $preInjectTrData[$trDataTmp->transaksi_id]);
                    $newNr[$iix] = (object)$trNr;
                }
                $newTrData["trData"] = $newNr;
            }
            //endregion

            $fildsSumItems = array(
                "id" => "id",
                "nama" => "nama",
                "no_part" => "no_part",
                "satuan" => "satuan",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "jml" => "jml",
                "qty" => "qty",
                "produk_qty" => "produk_qty",
                "placeID" => "placeID",
                "cabangID" => "cabangID",
            );
            $sumProdukNew = array();
            if (sizeof($sumProduk) > 0) {
                foreach ($sumProduk as $ii => $data) {
                    $sumSubtotal = 0;
                    $sumJml = 0;
                    foreach ($data as $itemsdetail) {
                        $sumProdukNew["sumProduk"][$ii] = $itemsdetail;
                        $sumSubtotal += $itemsdetail["subtotal"];
                        $sumJml += $itemsdetail["jml"];
                    }
                    $sumProdukNew["sumProduk"][$ii]["subtotal"] = $sumSubtotal;
                    $sumProdukNew["sumProduk"][$ii]["jml"] = $sumJml;
                    $sumProdukNew["sumProduk"][$ii]["qty"] = $sumJml;
                }
            }
            $data = $newItems + $newTrData + $sumProdukNew + $pointCustomer;
            $toInsertBlob = blobEncode($data);

            //region array builder transaction
            $itemsTmp = array(
                "handler" => "Selectors/_processSelectBiaya",
                "id" => "transaksi_id",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "nama" => "nama",
                "label" => "",
                "reference" => "",
                "qty" => "1",
                "name" => "name",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_total",
                "olehID" => "olehID",
                "olehName" => "olehName",
                "placeID" => "placeID",
                "placeName" => "cabang_nama",
                "cabangID" => "cabangID",
                "cabangName" => "cabangName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
                "gudang2ID" => "-1",
                "gudang2Name" => "default center warehouse",
                "jenisTr" => $targetJenis,
                "next_substep_code" => $targetJenis,
                "next_subgroup_code" => "o_finance",
                "sub_step_number" => "1",
                "sub_step_current" => "1",
                "nilai_bayar" => "",
                "new_sisa" => "0",
                "sub_new_sisa" => "0",
                "note" => "",
                "pihakID" => "-1",
                "pihakName" => "PUSAT",
            );
            $items2 = array();
            $items2_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();
            $tableIn_masterTmp = array(
                "trash" => "0",
                "jenis_master" => $targetJenis,
                "jenis_top" => $targetJenis,
                "jenis" => $targetJenis,
                "jenis_label" => "request depresiasi",
                "div_id" => "2",
                "div_nama" => "default",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",
                "cabang_id" => "cabangID",
                "cabang_nama" => "cabangName",
                "transaksi_nilai" => "sub_total",
                "transaksi_jenis" => $targetJenis,
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "-1",
                "gudang2_nama" => "default center warehouse",
                "keterangan" => "",
                "cabang2_id" => "-1",
                "cabang2_nama" => "PUSAT",
                "pihakMainName" => "",
                "pihakMainID" => "",
            );
            $tableIn_detailTmp = array(
                "produk_id" => "transaksi_id",
                "produk_kode" => "",
                "produk_label" => "",
                "produk_nama" => "nomer",
                "produk_ord_jml" => "1",
                "produk_ord_hrg" => "nilai_cash",
                "hpp" => "nilai_cash",
                "satuan" => "",
                "note" => "",
                "reference" => "",
                "trash" => "0",
                "produk_jenis" => "invoice",
                "valid_qty" => "1",
            );
            $tableIn_detail2_sum = array();
            $tableIn_detail_rsltItems = array();
            $tableIn_detail_rsltItems2 = array();
            $tableIn_master_valuesTmp = array(
                "gudang2" => "-1",
                "harga" => "harga",
                "divID" => "2",
                "subtotal" => "subtotal",
                "reference" => "0",
                "nilai_bayar" => "0",
                "note" => "0",
            );
            $tableIn_detail_valuesTmp = array(
                "jml" => "1",
                "harga" => "nilai_cash",
                "subtotal" => "nilai_cash",
                "qty" => "1",
                "sub_harga" => "nilai_cash",
                "sub_subtotal" => "nilai_cash",
                "sub_new_sisa" => "0",
            );
            $tableIn_detail_values_rsltItemsTmp = array();
            $tableIn_detail_values_rsltItems2Tmp = array();
            $tableIn_detail_values2_sumTmp = array();
            $tableIn_detail2 = array();
            $main_add_values = array();
            $main_add_fields = array();
            $main_elements = array(
                "gudang2" => Array
                (
                    "elementType" => "dataModel",
                    "name" => "gudang2",
                    "key" => "-1",
                    "labelSrc" => "name",
                    "label" => "gudang dc",
                    "labelValue" => "default center warehouse",
                    "mdl_name" => "MdlGudangDefault_center",
                    "contents" => "YToxOntzOjQ6Im5hbWEiO3M6MDoiIjt9",
                    "contents_intext" => print_r(array("nama" => ""), true),
                ),
            );
            $main_inputs = array();
            $main_inputs_orig = array();
            $receiptDetailFieldsTmp = array(
                "produk_nama" => "nomer",
            );
            $receiptSumFieldsTmp = array(
                "harga" => "total amount",
            );
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $tableIn_detail_values2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $tableIn_detail_values_rsltItems = array();
            $tableIn_detail_values_rsltItems2 = array();
            //endregion

            //region builder mian
            $main = array(
                "olehID" => $this->session->login["id"],
                "olehName" => $this->session->login["nama"],
                "placeID" => $this->session->login["cabang_id"],
                "placeName" => $this->session->login["cabang_nama"],
                "cabangID" => $this->session->login["cabang_id"],
                "cabangName" => $this->session->login["cabang_nama"],
                "gudangID" => $this->session->login["gudang_id"],
                "gudangName" => $this->session->login["gudang_nama"],
                "tokoID" => $this->session->login["toko_id"],
                "jenisTr" => $targetJenis,
                "jenisTrMaster" => $targetJenis,
                "jenisTrTop" => $targetJenis,
                "jenisTrName" => "settlement",
                "stepNumber" => "1",
                "stepCode" => $targetJenis,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow(),
                "harga" => $totalCash,
                "divID" => "2",
                "divName" => $this->session->login["div_nama"],
                "subtotal" => $totalCash,
                // "harga" => $totalCash,
                "nilai_cash" => $totalCash,
                "reference" => "0",
                "jenis" => $targetJenis,
                "transaksi_jenis" => $targetJenis,
                "next_step_code" => $targetJenis,
                "next_group_code" => "c_finance",
                "step_number" => "1",
                "step_current" => "1",
                "longitude" => "",
                "lattitude" => "",
                "accuracy" => "",
                "nilai_bayar" => "0",
                "new_sisa" => "0",
                "note" => "0",
                "description" => "",
                "pihakDisc" => "",
                "pihakName" => $this->session->login["nama"],
                "pihakID" => $this->session->login["id"],
                "machineID" => $this->session->login["machineID"],
            );
            //endregion
            //region builder items
            $items = array();
            foreach ($itemsTrans as $itsID => $itsData) {
                foreach ($itemsTmp as $col => $selectedRow) {
                    $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //endregion builder items
            //region builder tabel in master
            $tableIn_master = array(
                "trash" => "0",
                "jenis_master" => $targetJenis,
                "jenis_top" => $targetJenis,
                "jenis" => $targetJenis,
                "jenis_label" => "settlement",
                "div_id" => "2",
                "div_nama" => "default",
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow(),
                "oleh_id" => $this->session->login["id"],
                "oleh_nama" => $this->session->login["nama"],
                "cabang_id" => $this->session->login["cabang_id"],
                "cabang_nama" => $this->session->login["cabang_nama"],
                "transaksi_nilai" => $totalCash,
                "transaksi_jenis" => $targetJenis,
                "gudang_id" => $this->session->login["gudang_id"],
                "gudang_nama" => $this->session->login['gudang_nama'],
                "pihakMainName" => $this->session->login["id"],
                "pihakMainID" => $this->session->login["nama"],
            );
            //endregion
            //region builder table in detil
            $tableIn_detail = array();
            foreach ($itemsTrans as $itsID => $itsData) {
                foreach ($tableIn_detailTmp as $col => $selectedRow) {
                    $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            // arrPrintPink($tableIn_detail);
            //endregion builder table in detil
            //region table in master values
            $tableIn_master_values = array(
                "gudang" => $this->session->login["gudang_id"],
                "harga" => $totalCash,
                "divID" => "2",
                "subtotal" => $totalCash,
                "reference" => "0",
                "nilai_bayar" => "0",
                "note" => "0",
            );
            //endregion table in master values
            //region build table in detil values
            $tableIn_detail_values = array();
            foreach ($itemsTrans as $itsID => $itsData) {
                foreach ($tableIn_detail_valuesTmp as $col => $selectedRow) {
                    $tableIn_detail_values[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //endregion build table in detil values
            //region build table receipDetailFields
            $receiptDetailFields = array();
            foreach ($itemsTrans as $itsID => $itsData) {
                foreach ($receiptDetailFieldsTmp as $col => $selectedRow) {
                    $receiptDetailFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //endregion
            //region receiptSumFields
            $receiptSumFields = array();
            foreach ($itemsTrans as $itsID => $itsData) {
                foreach ($receiptSumFieldsTmp as $col => $selectedRow) {
                    $receiptSumFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //endregion

            $configUi = loadConfigModulJenis_he_misc($targetJenis, 'coTransaksiUi');
            $configLayout = loadConfigModulJenis_he_misc($targetJenis, 'coTransaksiLayout');
            $configCore = $this->load->config->item("heTransaksi_core");
            $configValues = loadConfigModulJenis_he_misc($targetJenis, 'coTransaksiValues');
            $modul = $this->load->config->item("heTransaksi_ui")[$targetJenis]["modul"];
            $transaksiMode = $configUi["transaksiMode"];
            $core = isset($configCore[$modul][$transaksiMode]) ? $configCore[$modul][$transaksiMode] : array();
            $runCoreAkunting = isset($configUi["steps"]["1"]["runCoreAccounting"]) ? $configUi["steps"]["1"]["runCoreAccounting"] : false;

            if (sizeof($itemsTrans) > 0) {

                $gate['items'] = isset($arrItems) ? $arrItems : array();
                //region transaksional
                $buildTablesMaster = isset($configValues['components'][1]['master']) ? $configValues['components'][1]['master'] : array();
                $buildTablesDetail = isset($configValues['components'][1]['detail']) ? $configValues['components'][1]['detail'] : array();
                $addMasterTables = array(
                    "rugilaba",
                    "laba ditahan",
                    "rugilaba lain lain",
                );
                foreach ($addMasterTables as $trek) {
                    $buildTablesMaster[] = array(
                        "comName" => "RugiLaba",
                        "loop" => array(
                            "$trek" => .0,
                        ),
                    );
                }
                if (sizeof($buildTablesMaster) > 0) {
                    $bCtr = 0;
                    foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                        $bCtr++;
                        $mdlName = $buildTablesMaster_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                        }
                        else {
                            //                        cekkuning("TIDAK mengandung kurawal");
                        }

                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                            foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesMaster_specs['loop'][$key];
                                    unset($buildTablesMaster_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $main[$key], $key);
                                    $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesMaster_specs);
                            }
                        }
                    }
                }
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            cekbiru("model: $mdlName");
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                }
                            }
                        }
                    }
                }
                //===finalisasi sebelum masuk tabel beneran

                //region penomoran receipt NEW
                //region penomoran receipt
                $this->load->model("CustomCounter");
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");
                $cn->setModul($this->modul);
                $cn->setStepCode($tCode);
                $configCustomParams = $configValues['counters'];

                if (sizeof($configCustomParams) > 0) {
                    $cContent = array();
                    foreach ($configCustomParams as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $main[$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i], my_toko_id());
                        $cContent[$cRawParams][$cRawValues] = $paramSpec["value"];
                        switch ($paramSpec["id"]) {
                            case 0: //===counter type is new
                                $addData = array(
                                    "toko_id" => my_toko_id(),
                                    "toko_nama" => my_toko_nama(),
                                );
                                $paramKeyRaw = print_r($cParams, true);
                                $paramValuesRaw = print_r($cValues[$i], true);
                                $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw, $addData);
                                break;
                            default: //===counter to be updated
                                $cn->updateCount($paramSpec["id"], $paramSpec["value"]);
                                break;
                        }
                    }
                }

                $appliedCounters = base64_encode(serialize($cContent));
                $appliedCounters_inText = print_r($cContent, true);

                //region NUMBERING

                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");

                $counterForNumber = array($configValues['formatNota']);
                foreach ($counterForNumber as $i => $c0RawParams) {
                    $c0Params = explode(",", $c0RawParams);
                    foreach ($c0Params as $k => $cRawParams) {
                        $dParams = explode("|", $cRawParams);
                        if (count($dParams) > 1) {
                            if (!in_array($cRawParams, $configValues['counters'])) {
                                die(__LINE__ . "( $cRawParams ) Used number should be registered in counters config as well");
                            }
                        }
                    }
                }

                $tmpNomorNota = "";
                $arrNomorNota = array();
                foreach ($counterForNumber as $i => $c0RawParams) {
                    $c0Params = explode(",", $c0RawParams);
                    $c0Values = array();
                    foreach ($c0Params as $k => $cRawParams) {
                        $arrRawParams = explode("|", $cRawParams);
                        if (sizeof($arrRawParams) > 1) {
                            $cRawParamsValues = array();
                            foreach ($arrRawParams as $key) {
                                $cRawParamsValues[$key] = $main[$key];
                            }
                            $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                            $cRawParamsValuesV = implode("|", $cRawParamsValues);
                            $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                        }
                        else {
                            $cRawParamsValuesK = $arrRawParams[0];
                            $cRawParamsValuesV = $main[$arrRawParams[0]];
                            if ($arrRawParams[0] == "fulldate") {
                                $arrNomorNota[] = date("dmY", strtotime($cRawParamsValuesV));
                            }
                            elseif ($arrRawParams[0] == "stepCode") {
                                $arrNomorNota[] = $cRawParamsValuesV;
                            }
                            elseif ($arrRawParams[0] == "placeID") {
                                $arrNomorNota[] = $cRawParamsValuesV;
                            }
                            elseif ($arrRawParams[0] == "customerID") {
                                $arrNomorNota[] = digit_4($cRawParamsValuesV);
                            }
                            elseif ($arrRawParams[0] == "machineID") {
                                if ($cRawParamsValuesV != "") {
                                    $arrNomorNota[] = substr($cRawParamsValuesV, -4);
                                }
                                else {
                                    $arrNomorNota[] = "NMID";
                                }
                            }
                            elseif ($arrRawParams[0] == "olehID") {
                                $arrNomorNota[] = $cRawParamsValuesV;
                            }
                            else {
                                $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                            }
                        }
                    }
                }

                $stepNumber = 1;
                $tmpNomorNota = implode(".", $arrNomorNota);

//                arrPrint($counterForNumber);
//                arrPrint($arrNomorNota);
//                cekBiru($tmpNomorNota);

                if (isset($configUi['steps'][2])) {
                    $nextProp = array(
                        "num" => 2,
                        "code" => $configUi['steps'][2]['target'],
                        "label" => $configUi['steps'][2]['label'],
                        "groupID" => $configUi['steps'][2]['userGroup'],
                    );
                }
                else {
                    $nextProp = array(
                        "num" => 0,
                        "code" => "",
                        "label" => "",
                        "groupID" => "",
                    );
                }
                //endregion penomoran receipt NEW

                //region addition on master
                $addValues = array(
                    'counters' => $appliedCounters,
                    'counters_intext' => $appliedCounters_inText,
                    'nomer' => $tmpNomorNota,
                    'dtime' => date("Y-m-d H:i:s"),
                    'fulldate' => date("Y-m-d"),
                    "step_avail" => sizeof($configUi['steps']),
                    "step_number" => 1,
                    "step_current" => 1,
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],
                    "tail_number" => 1,
                    "tail_code" => $configUi['steps'][1]['target'],
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }
                //endregion

                //region addition on detail
                $addSubValues = array(
                    "sub_step_number" => 1,
                    "sub_step_current" => 1,
                    "sub_step_avail" => sizeof($configUi['steps']),
                    "next_substep_num" => $nextProp['num'],
                    "next_substep_code" => $nextProp['code'],
                    "next_substep_label" => $nextProp['label'],
                    "next_subgroup_code" => $nextProp['groupID'],
                    "sub_tail_number" => 1,
                    "sub_tail_code" => $configUi['steps'][1]['target'],
                );
                foreach ($tableIn_detail as $id => $dSpec) {
                    foreach ($addSubValues as $key => $val) {
                        $tableIn_detail[$id][$key] = $val;
                    }
                }
                //endregion

                //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                if (sizeof($tableIn_master) > 0) {
                    $tableIn_master['status_4'] = 11;
                    $tableIn_master['trash_4'] = 0;

                    $tr = new MdlTransaksi();
                    $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                    $insertID = $tr->writeMainEntries($tableIn_master);

                    cekHijau("tableIn_master");
                    showLast_query("hijau");

                    $mongoList['main'][] = $insertID;
                    $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);

                    $mongoList['main'][] = $epID;

                    $insertNum = $tableIn_master['nomer'];
                    $main['nomer'] = $insertNum;
                    if ($insertID < 1) {
                        die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                    }

                    //==transaksi_id dan nomor nota diinject kan ke gate utama
                    $injectors = array(
                        "transaksi_id" => $insertID,
                        "nomer" => $tmpNomorNota,
                    );
                    $arrInjectorsTarget = array(
                        "items",
                    );
                    foreach ($injectors as $key => $val) {
                        $main[$key] = $val;
                        foreach ($arrInjectorsTarget as $target) {
                            foreach ($items as $xis => $iSpec) {
                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                                if (isset($items[$id])) {
                                    $items[$id][$key] = $val;
                                }
                            }
                            foreach ($gate[$target] as $xis => $iSpec) {
                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                                $gate[$target][$id][$key] = $val;
                            }
                        }
                    }

                    //===signature
                    $dwsign = $tr->writeSignature($insertID, array(
                        "nomer" => $main['nomer'],
                        "step_number" => 1,
                        "step_code" => $currentTr,
                        "step_name" => $configUi['steps'][1]['label'],
                        "group_code" => $configUi['steps'][1]['userGroup'],
                        "oleh_id" => "-100",
                        "oleh_nama" => "sys",
                        "keterangan" => $configUi['steps'][1]['label'] . " oleh sys",
                        "transaksi_id" => $insertID,
                    )) or die("Failed to write signature");
                    $mongoList['sign'][] = $dwsign;
                    $idHis = array(
                        $stepNumber => array(
                            "step" => $stepNumber,
                            "trID" => $insertID,
                            "nomer" => $tmpNomorNota,
                            "counters" => $appliedCounters,
                            "counters_intext" => $appliedCounters_inText,
                        ),
                    );
                    $idHis_blob = blobEncode($idHis);
                    $idHis_intext = print_r($idHis, true);
                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                        "next_step_num" => $nextProp['num'],
                        "next_step_code" => $nextProp['code'],
                        "next_step_label" => $nextProp['label'],
                        "next_group_code" => $nextProp['groupID'],

                        //===references
                        "id_master" => $insertID,
                        "id_top" => $insertID,
                        "ids_prev" => "",
                        "ids_prev_intext" => "",
                        "nomer_top" => $main['nomer'],
                        "nomers_prev" => "",
                        "nomers_prev_intext" => "",
                        "jenises_prev" => "",
                        "jenises_prev_intext" => "",
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,
                    )) or die("Failed to update tr next-state!");

                    $addValues = array(
                        //===references
                        "id_master" => $insertID,
                        "id_top" => $insertID,
                        "ids_prev" => "",
                        "ids_prev_intext" => "",
                        "nomer_top" => $main['nomer'],
                        "nomers_prev" => "",
                        "nomers_prev_intext" => "",
                        "jenises_prev" => "",
                        "jenises_prev_intext" => "",
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,
                    );
                    foreach ($addValues as $key => $val) {
                        $tableIn_master[$key] = $val;
                    }

                }
                if (sizeof($tableIn_master_values) > 0) {
                    if (isset($configValues['tableIn']['mainValues'])) {
                        $inserMainValues = array();
                        foreach ($configValues['tableIn']['mainValues'] as $key => $src) {
                            if (isset($tableIn_master_values[$key])) {
                                $dd = $tr->writeMainValues($insertID, array(
                                    "key" => $key,
                                    "value" => $tableIn_master_values[$key],
                                ));
                                $inserMainValues[] = $dd;
                                $mongoList['mainValues'][] = $dd;
                            }

                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                }
                if (sizeof($main_add_values) > 0) {
                    $inserMainValues = array();
                    foreach ($main_add_values as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }

                    //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
                if (sizeof($main_inputs) > 0) {
                    foreach ($main_inputs as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                    //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
                if (sizeof($main_add_fields) > 0) {
                    foreach ($main_add_fields as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                    //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
                if (sizeof($main_elements) > 0) {
                    foreach ($main_elements as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
                        ));

                        //==nebeng bikin inputLabels
                        $currentValue = "";
                        switch ($aSpec['elementType']) {
                            case "dataModel":
                                $currentValue = $aSpec['key'];
                                break;
                            case "dataField":
                                $currentValue = $aSpec['value'];
                                break;
                        }
//                        if (array_key_exists($elName, $relOptionConfigs)) {
//                            if (isset($relOptionConfigs[$elName][$currentValue])) {
//                                if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
//                                    foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
//                                        $inputLabels[$oValueName] = $oValSpec['label'];
//                                        if (isset($oValSpec['auth'])) {
//                                            if (isset($oValSpec['auth']['groupID'])) {
//                                                $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                            else {
//                                //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
//                            }
//                        }
                        //                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                    }
                }
                if (sizeof($tableIn_detail) > 0) {
                    $insertIDs = array();
                    $insertDeIDs = array();
                    foreach ($tableIn_detail as $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                        if ($epID != 999) {
                            $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                            $mongoList['detail'][] = $insertEpID;
                        }
                        //                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
                    }
                    if (sizeof($insertIDs) == 0) {
                        die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                    }
                    else {
                        $indexing_details = array();
                        foreach ($insertDeIDs as $key => $numb) {
                            $indexing_details[$key] = $numb;
                        }
                        foreach ($indexing_details as $k => $arrID) {
                            $arrBlob = blobEncode($arrID);
                            $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                            cekOrange($this->db->last_query());
                        }
                    }
                }
                if (sizeof($tableIn_detail2) > 0) {
                    $insertIDs = array();
                    foreach ($tableIn_detail2 as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                        if ($epID != 999) {
                            $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                            $mongoList['detail'] = $insertIDs;
                        }
                        //                                cekUngu($this->db->last_query());
                    }
                }
                if (sizeof($tableIn_detail2_sum) > 0) {
                    $insertIDs = array();
                    foreach ($tableIn_detail2_sum as $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;

                        if ($epID != 999) {
                            $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
                        }
                    }
                    //                            cekOrange($this->db->last_query());
                }
                if (sizeof($tableIn_detail_rsltItems) > 0) {
                    $insertIDs = array();
                    foreach ($tableIn_detail_rsltItems as $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                        if ($epID != 999) {
                            $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
                        }
                        //                                cekUngu($this->db->last_query());
                    }
                }
                if (sizeof($tableIn_detail_values) > 0) {
                    foreach ($tableIn_detail_values as $pID => $dSpec) {
                        if (isset($configValues['tableIn']['detailValues'])) {
                            $insertIDs = array();
                            foreach ($configValues['tableIn']['detailValues'] as $key => $src) {
                                if (isset($tableIn_detail[$pID])) {
                                    $dd = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                    ));
//                                    arrPrintWebs(array(
//                                        "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
//                                        "produk_id" => $pID,
//                                        "key" => $key,
//                                        "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
//                                    ));
                                    $insertIDs[$pID][] = $dd;
                                    $mongoList['detailValues'][] = $dd;
                                }
                                //                                        cekLime($this->db->last_query());
                            }
                            if (sizeof($insertIDs) > 0) {
                                $arrBlob = blobEncode($insertIDs);
                                $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                            }
                        }
                    }
                }
                if (sizeof($tableIn_detail_values2_sum) > 0) {
                    foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
                        if (isset($configValues['tableIn']['detailValues2_sum'])) {
                            foreach ($configValues['tableIn']['detailValues2_sum'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                ));
                                $insertIDs[] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }
                    }
                }
                //endregion

                //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
                $steps = $configUi['steps'];

                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;

                //region COMPONENT-----
                if ($runCoreAkunting == true) {
                    //region processing main components, if in single step
                    $componentGate['master'] = array();
                    $componentConfig['master'] = array();
                    //==filter nilai, jika NOL tidak dikirim, sesuai config==
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    if (isset($configValues[$targetJenis]['relativeComponets']) && $configValues[$this->jenisTr]['relativeComponets'] == true) {
                        $iterator = isset($main['revert']['jurnal']['master']) ? $main['revert']['jurnal']['master'] : array();
                    }
                    else {
                        if (isset($main['componentsBuilder'][1]['master'])) {
                            $iterator = $main['componentsBuilder'][1]['master'];
                        }
                        else {
                            // matiHEre($runCoreAkunting);
                            $iterator = isset($core['components']['master']) ? $core['components']['master'] : array();
                        }
                    }
                    // arrprintWebs($main);
                    // matiHEre(__LINE__);
                    if (sizeof($iterator) > 0) {
                        $componentConfig['master'] = $iterator;
                        $cCtr = 0;
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $cCtr++;
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $main[$comName], $comName);
                            }
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            cekHere("component # $cCtr: $comName<br>");

                            // $dSpec = $this->cCodeData[$cCode][$srcGateName];
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $main[$key], $key);
                                    }
                                    $realValue = makeValue($value, $main, $main, 0);
                                    $tmpOutParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $main, $main, 0);
                                    $tmpOutParams['static'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                }
                                $tmpOutParams['static']["urut"] = $cCtr;
                                $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static']["keterangan"] = $configUi["steps"][1]["label"] . " nomor " . $tmpNomorNota . " oleh " . $tableIn_master['oleh_nama'];
                            }

                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            //===filter value nol, jika harus difilter
                            $tobeExecuted = true;
                            if (in_array($mdlName, $compValidators)) {
                                $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                                if (sizeof($loopParams) > 0) {
                                    foreach ($loopParams as $key => $val) {
                                        cekmerah("$comName : $key = $val ");
                                        if ($val == 0) {
                                            unset($tmpOutParams['loop'][$key]);
                                        }
                                    }
                                }
                                if (sizeof($tmpOutParams['loop']) < 1) {
                                    $tobeExecuted = false;
                                }
                            }
                            if ($tobeExecuted) {
                                //----- kiriman gerbang untuk counter mutasi rekening
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($tableIn_master);
                                }
                                if (method_exists($m, "setMain")) {
                                    $m->setMain($main);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($this->jenisTr);
                                }
                                //----- kiriman gerbang untuk counter mutasi rekening
                                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                cekHitam($this->db->last_query());
                            }
                            $componentGate['master'][$cCtr] = $tmpOutParams;
                        }
                    }
                    else {
                        cekKuning("components is not set");
                    }
                    //endregion
                }
                else {
                    cekHitam("TIDAK ADA COMPONENT");
                }
                //endregion

                //====registri value-gate
                $baseRegistries = array(
                    'main' => sizeof($main) > 0 ? $main : array(),
                    'items' => sizeof($items) > 0 ? $items : array(),
                    'items2' => sizeof($items2) > 0 ? $items2 : array(),
                    'items2_sum' => sizeof($items2_sum) > 0 ? $items2_sum : array(),
                    'items3' => sizeof($items3) > 0 ? $items3 : array(),
                    'items3_sum' => sizeof($items3_sum) > 0 ? $items3_sum : array(),
                    'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                    'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                    'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                    'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                    'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                    'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                    'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                    'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                    'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                    'tableIn_detail_values_rsltItems' => sizeof($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                    'tableIn_detail_values_rsltItems2' => sizeof($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
                    'tableIn_detail_values2_sum' => sizeof($tableIn_detail_values2_sum) > 0 ? $tableIn_detail_values2_sum : array(),
                    'main_add_values' => sizeof($main_add_values) > 0 ? $main_add_values : array(),
                    'main_add_fields' => sizeof($main_add_fields) > 0 ? $main_add_fields : array(),
                    'main_elements' => sizeof($main_elements) > 0 ? $main_elements : array(),
                    'main_inputs' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                    'main_inputs_orig' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                    "receiptDetailFields" => isset($configLayout['receiptDetailFields'][1]) ? $configLayout['receiptDetailFields'][1] : array(),
                    "receiptSumFields" => isset($configLayout['receiptSumFields'][1]) ? $configLayout['receiptSumFields'][1] : array(),
                    "receiptDetailFields2" => isset($configLayout['receiptDetailFields2'][1]) ? $configLayout['receiptDetailFields2'][1] : array(),
                    "receiptSumFields2" => isset($configLayout['receiptSumFields2'][1]) ? $configLayout['receiptSumFields2'][1] : array(),
                );

                //===
                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                $mongRegID[] = $doWriteReg;

                //region nulis paymentSource
                $stepCode = $configUi['steps'][1]['target'];
                $paymentSources = $this->config->item("payment_source");

                if (array_key_exists($stepCode, $paymentSources)) {
                    $payConfigs = $paymentSources[$stepCode];
                    if (sizeof($payConfigs) > 0) {
                        foreach ($payConfigs[1] as $paymentSrcConfig) {
                            $valueSrc = $paymentSrcConfig['valueSrc'];
                            $externSrc = $paymentSrcConfig['externSrc'];
                            $arrDataPym = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => $titip_setoran ? $extern['id'] : $this->session->login['id'],
                                "extern_nama" => $titip_setoran ? $extern['nama'] : $this->session->login['nama'],
                                "nomer" => $main['nomer'],
                                "externValueBlob" => blobEncode(array()),
//                                "externValueBlob" => $toInsertBlob, //blob untuk local POS di matikan, cukup ngirim ke web admin saja
                                "label" => $paymentSrcConfig['label'],
                                "tagihan" => isset($main["nilai_cash"]) ? $main["nilai_cash"] : 0,
                                "terbayar" => $payment_info['payment_nilai'],
                                "sisa" => isset($main["nilai_cash"]) ? $main["nilai_cash"] - $payment_info['payment_nilai'] : 0,
                                "cabang_id" => $this->session->login["cabang_id"],
                                "cabang_nama" => $this->session->login["cabang_nama"],
                                "oleh_id" => $titip_setoran ? $extern['id'] : $this->session->login['id'],
                                "oleh_nama" => $titip_setoran ? $extern['nama'] : $this->session->login['nama'],
                                "machineID" => isset($this->session->login['machineID']) ? $this->session->login['machineID'] : "none",
                                "cpu_info" => isset($this->session->login['CPU_INFO']) ? $this->session->login['CPU_INFO'] : "none",
                                "com_name" => isset($this->session->login['COM_INFO']) ? $this->session->login['COM_INFO'] : "none",
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
//                                "extern2_id" => $payment_info['payment_oto_id'],
//                                "extern2_nama" => $payment_info['payment_oto_nama'],
                            );

//                            arrPrint($arrDataPym);
                            $pymSrcID = $tr->writePaymentSrc($insertID, $arrDataPym);
//                            cekMerah($this->db->last_query());

                            $kolomValidasi = isset($paymentSrcConfig['kolom']) ? $paymentSrcConfig['kolom'] : array();
                            if (sizeof($kolomValidasi) > 0) {
                                $trr = New MdlTransaksi();
                                $trr->setFilters(array());
                                $pym = $trr->lookupPaymentSrcByID($pymSrcID)->result();
//                                cekKuning($this->db->last_query());
                                foreach ($kolomValidasi as $kolom_validasi) {
                                    if (isset($pym[0]->$kolom_validasi)) {
                                        if ($pym[0]->$kolom_validasi == NULL) {
                                            $msg = "Settlement gagal disimpan. Sesi anda habis, silahkan login ulang.";
                                            mati_disini($msg);
                                        }
                                        else {
                                            cekUngu("kolom ada isinya");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else {
                    cekMerah("TIDAK nulis paymentSrc");
                }
                //endregion

                //region update transaksi yang sudah disettle
                if ($insertID) {
                    $tr->setFilters(array());
                    foreach ($ids as $u => $idToupdate) {
                        $tr->setTableName("transaksi");
                        $tr->setFilters(array());
                        $wheres = array(
                            "id" => $idToupdate,
                        );
                        $dataUpd = array(
                            "settlement_id" => $insertID,
                        );
                        $tr->updateData($wheres, $dataUpd);
                        cekHitam($this->db->last_query());
                    }
                }
                //endregion
            }
        }
        else {
            matiHEre("semua transaksi penjualan sudah settle");
        }

        // $result = json_decode($sendToMainServer, true);
        // echo json_encode($result);
        // echo "<script>top.HoldOn.close();</script>";
        // matiHere("STOP DULU TRANSAKSI POS BELUM COMMIT");
        // matiHere();
        // $this->db->trans_commit();

        /*
         * API ke domian master di geser setelah commit karena  melibatakan jurnal di POS, kas pada penjualan hanya master
         */
        // region API Settle pusat
        // $timeStart = strtotime(date("Y-m-d H:i:s"));
        // $timeEnd = strtotime("+5 minutes", strtotime(date("Y-m-d H:i:s")));
        // $url = ADM_DOMAIN."/penjualan/ActivityReportApi/doSettlementPosAPI";
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataEncoded) );
        // curl_setopt($ch, CURLOPT_FAILONERROR, true);
        // $sendToMainServer = curl_exec($ch);
        // curl_close($ch);
//        $file = $this->fileSettlement($dataEncoded);
//        $machineID = MACHINE_ID;
//        $save_settlement = array(
//            "file_name"     => $file,
//            "machine_id"    => $machineID,
//            "oleh_id"       => $this->session->login['id'],
//            "oleh_nama"     => $this->session->login['nama'],
//            "settlement_id" => $insertID,
//            "kasir_id"      => $this->session->login['id'],
//            "kasir_nama"    => $this->session->login['nama'],
//            "nomer"         => $tmpNomorNota,
//        );
//        $ins_settlement = $this->db->insert("transaksi_settlement", $save_settlement);
//        echo json_encode($save_settlement);

        $arrAlert = array(
            "type" => "success",
            "title" => "Settlement Saved on '$file'",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        echo swalAlert($arrAlert);

//        matiHere("=== TRANSAKSI SETTLEMENT BERHASIL, sedang maintenance... Tunggu beberapa saat lagi... ===");

        $this->db->trans_commit();

        unset($this->session->errMsg);

        echo "<script> setTimeout(function(){ top.window.location.reload() }, 2500);</script>";

    }
}
