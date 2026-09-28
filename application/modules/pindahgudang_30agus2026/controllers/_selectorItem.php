<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
require_once "Modul_Controller.php";

class _selectorItem extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        // arrPrint($this->uri->segment_array());
    }

    public function selectItem()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;

        $cekES = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : "";
        $cID = isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : $this->session->login['cabang_id'];
        $gID = isset($_SESSION[$cCode]['main']['gudangID']) ? $_SESSION[$cCode]['main']['gudangID'] : $this->session->login['gudang_id'];
        $tkID = isset($_SESSION[$cCode]['main']['tokoID']) ? $_SESSION[$cCode]['main']['tokoID'] : (isset($this->session->login['toko_id']) ? $this->session->login['toko_id'] : "");

        $mdlName = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->uri->segment(5);

        $fields = $this->configUi[$jenisTr]['selectorFields'];

        $modelFilter = isset($this->configUi[$jenisTr]['selectorFilters']) ? $this->configUi[$jenisTr]['selectorFilters'] : array();
        $modelFilterCustom = isset($this->configUi[$jenisTr]['selectorMainFilters']) ? $this->configUi[$jenisTr]['selectorMainFilters'] : array();
        $modelSrcFilter = isset($this->configUi[$jenisTr]['selectorSrcFilters']) ? $this->configUi[$jenisTr]['selectorSrcFilters'] : array();

        $selectorFields = isset($this->configUi[$jenisTr]['selectorViewedFields']) ? $this->configUi[$jenisTr]['selectorViewedFields'] : array();
        $selectorNaming = isset($this->configUi[$jenisTr]['selectorViewedNames']) ? $this->configUi[$jenisTr]['selectorViewedNames'] : array();
        $selectorParamFields = isset($this->configUi[$jenisTr]['selectorParamFields']) ? $this->configUi[$jenisTr]['selectorParamFields'] : array();
        $selectorSrcParamFields = isset($this->configUi[$jenisTr]['selectorSrcParamFields']) ? $this->configUi[$jenisTr]['selectorSrcParamFields'] : array();
        $selectorMainFields = isset($this->configUi[$jenisTr]['selectorMainViewedFields']) ? $this->configUi[$jenisTr]['selectorMainViewedFields'] : array();
        $selectorMainParamFields = isset($this->configUi[$jenisTr]['selectorMainParamFields']) ? $this->configUi[$jenisTr]['selectorMainParamFields'] : array();
        $selectorModel = isset($this->configUi[$jenisTr]['selectorModel']) ? $this->configUi[$jenisTr]['selectorModel'] : "MdlProduk";
        $selectorSrcModel = isset($this->configUi[$jenisTr]['selectorSrcModel']) ? $this->configUi[$jenisTr]['selectorSrcModel'] : "MdlProduk";
        $selectorView = isset($this->configUi[$jenisTr]['selectorView']) ? $this->configUi[$jenisTr]['selectorView'] : "_selector";
        // cekMerah($selectorView);
        $selectorDefaultMinValue = isset($this->configUi[$jenisTr]['selectorDefaultMinValue']) ? $this->configUi[$jenisTr]['selectorDefaultMinValue'] : "0";
        $key = isset($_GET['search']) ? $_GET['search'] : "";
        $preLocker = isset($this->configUi[$jenisTr]['validLocker']) ? $this->configUi[$jenisTr]['validLocker'] : false;
        $selectorOrderBy = isset($this->configUi[$jenisTr]['selectorOrderBy']) ? $this->configUi[$jenisTr]['selectorOrderBy'] : NULL;
        $items = array();

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            //            cekkuning("TIDAK mengandung kurawal");
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            //            cekkuning("TIDAK mengandung kurawal");
        }


        if ($preLocker) {
            $mdlPreLocker = $this->configUi[$jenisTr]["lockerCheck"]["mdlName"];
            $this->load->model("Mdls/" . $mdlPreLocker);
            $pl = new $mdlPreLocker();
        }
        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        // cekHere($mdlName);

        //pairing produk
        $this->load->model("Mdls/" . $selectorSrcModel);

        $b = new $selectorSrcModel();


        $arrFilterCustom = array();
        $filterCustom = false;
        if (sizeof($modelFilterCustom) > 0) {
            if (isset($modelFilterCustom[$_SESSION[$cCode]['main']['pihakMainName']])) {
                $arrFilterCustom = $modelFilterCustom[$_SESSION[$cCode]['main']['pihakMainName']];
                $filterCustom = true;
            }
            else {
                $filterCustom = false;
            }
        }
        else {
            $filterCustom = false;
        }

        if ($filterCustom == true) {
            if (sizeof($arrFilterCustom) > 0) {
                makeFilter($arrFilterCustom, $_SESSION[$cCode]['main'], $o);
            }
            $selectorFields = $selectorMainFields[$_SESSION[$cCode]['main']['pihakMainName']];
            $selectorParamFields = $selectorMainParamFields[$_SESSION[$cCode]['main']['pihakMainName']];

            $selectorProcessor = $this->configUi[$jenisTr]['selectorMainProcessor'][$_SESSION[$cCode]['main']['pihakMainName']];
            $processor = $this->modulPath . $selectorProcessor . "/$jenisTr";
        }
        else {
            if (sizeof($modelFilter) > 0) {
                foreach ($modelFilter as $f) {
                    $f_ex = explode("=", $f);
                    if (!isset($f_ex[1])) {
                        // cekHitam();
                        $f_ey = explode(">", $f_ex[0]);
                        if (substr($f_ey[1], 0, 1) == ".") {
                            $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                        }
                        else {
                            if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                                $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                            }
                            else {
                                $o->addFilter($f_ey[0] . ">0");
                            }
                        }
                    }
                    else {
                        if (substr($f_ex[1], 0, 1) == ".") {
                            $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                        }
                        else {
                            if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                                $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                            }
                            else {
                                $o->addFilter($f_ex[0] . "=''");
                            }

                        }
                    }

                }
            }
            $processor = $this->modulPath . $this->configUi[$jenisTr]['selectorProcessor'] . "/$jenisTr";

        }
        // cekHijau($processor);
        $isPinjamTr = (isset($this->configUi[$jenisTr]['pairMakers'][1]['stokPinjam']) || $jenisTr == "2587");
        $arrPinjamByProd = array();
        $arrPinjamProdIDs = array();
        $arrLockerStock = array();
        $arrPinjamReady = array();
        $arrPinjamZero = array();

        if ($isPinjamTr) {
            $this->load->model("Coms/ComRekeningPembantuPinjamBarang");
            $csPinjam = new ComRekeningPembantuPinjamBarang();
            $csPinjam->setFilters(array());
            $csPinjam->addFilter("cabang_id=" . $cID);
            $csPinjam->addFilter("gudang_id=" . $gID);
            $csPinjam->addFilter("periode='forever'");
            $tmpPinjamAll = $csPinjam->lookupAll()->result();
            if (sizeof($tmpPinjamAll) > 0) {
                foreach ($tmpPinjamAll as $pjRow) {
                    $qKredit = isset($pjRow->qty_kredit) ? (float)$pjRow->qty_kredit : 0;
                    if ($qKredit > 0) {
                        $arrPinjamByProd[$pjRow->extern_id] = $qKredit;
                        $arrPinjamProdIDs[] = $pjRow->extern_id;
                    }
                }
            }

            // Ambil semua stok fisik aktif di gudang ini dari stock_locker
            $this->load->model("Mdls/MdlLockerStock");
            $mdlLs = new MdlLockerStock();
            $mdlLs->setFilters(array());
            $mdlLs->addFilter("cabang_id=" . $cID);
            $mdlLs->addFilter("gudang_id=" . $gID);
            $mdlLs->addFilter("state='active'");
            $mdlLs->addFilter("jenis_locker='stock'");
            $tmpLsAll = $mdlLs->lookupAll()->result();
            if (sizeof($tmpLsAll) > 0) {
                foreach ($tmpLsAll as $lsRow) {
                    $arrLockerStock[$lsRow->produk_id] = (float)$lsRow->jumlah;
                }
            }

            // Bangun daftar seluruh barang pinjaman (termasuk yang stok fisik di gudang = 0)
            if (sizeof($arrPinjamProdIDs) > 0) {
                $this->load->model("Mdls/MdlProduk");
                $pMdl = new MdlProduk();
                $pMdl->setFilters(array());
                $pMdl->addFilter("id in ('" . implode("','", $arrPinjamProdIDs) . "')");
                if (strlen($key) > 0) {
                    $pMdl->createSmartSearch($key, array("nama", "barcode", "kode"));
                }
                $borrowedProducts = $pMdl->lookupAll()->result();
                if (sizeof($borrowedProducts) > 0) {
                    foreach ($borrowedProducts as $bP) {
                        $pID = $bP->id;
                        $satuan = isset($bP->satuan) && strlen($bP->satuan) > 0 ? $bP->satuan : "n/a";
                        $qtyPinjamItem = isset($arrPinjamByProd[$pID]) ? (float)$arrPinjamByProd[$pID] : 0;
                        $stokFisik = isset($arrLockerStock[$pID]) ? (float)$arrLockerStock[$pID] : 0;

                        $tmpB = array();
                        $tmpB['id'] = $pID;
                        $tmpB['nama'] = $bP->nama;
                        $tmpB['satuan'] = $satuan;
                        $tmpB['target'] = $processor;
                        $tmpB['minValue'] = $selectorDefaultMinValue;
                        $tmpB['qty_pinjam'] = $qtyPinjamItem;
                        $tmpB['stok_fisik'] = $stokFisik;
                        $tmpB['no_pinjam'] = 0;

                        if ($stokFisik >= $qtyPinjamItem) {
                            $dotIcon = "<i class='fa fa-circle text-success' style='margin-left:6px;font-size:0.9em;' title='Stok Fisik Cukup (" . number_format($stokFisik) . " $satuan)'></i>";
                        }
                        elseif ($stokFisik > 0) {
                            $dotIcon = "<i class='fa fa-circle text-warning' style='margin-left:6px;font-size:0.9em;' title='Stok Fisik Kurang (" . number_format($stokFisik) . " $satuan)'></i>";
                        }
                        else {
                            $dotIcon = "<i class='fa fa-circle text-danger' style='margin-left:6px;font-size:0.9em;' title='Stok Fisik Kosong (0 $satuan)'></i>";
                        }

                        $lblKode = (isset($bP->kode) && strlen($bP->kode) > 0 && $bP->kode != $bP->nama) ? "<span style='font-size:0.85em;margin:0px 3px;color:#0056cd;'>[" . htmlspecialchars($bP->kode) . "]</span>" : "";
                        $pinjamBadge = "<span class='label label-primary' style='font-size:0.8em;margin-left:4px;'>Pinjam: " . number_format($qtyPinjamItem) . " $satuan</span>";
                        $stokBadge = "<span class='text-muted' style='font-size:0.8em;margin-left:4px;'>(Stok: " . number_format($stokFisik) . ")</span>";

                        $tmpB['label'] = $lblKode . " " . $pinjamBadge . " " . $stokBadge . " " . $dotIcon;

                        $socketURL[$tmpB['id']] = isset($this->configUi[$jenisTr]['selectorSocket']) ? base_url() . $this->configUi[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
                        $socketParams[$tmpB['id']] = isset($socketConfig['inParams']) ? $socketConfig['inParams'] : array();

                        $arrPinjamReady[] = $tmpB;
                    }
                }
            }
        }

        /* ----------------------------------------------------------------------------------------------------------
         * bila ada main session yg ilang saat clear shoping cart ditambah di inisiasi _shopingCart/reset
         * ----------------------------------------------------------------------------------------------------------*/
        if(strlen($key)<3){
            $this->db->limit(20); //dimatikan karena tidak bisa select all produk
        }

        $tmpO = $o->lookupByKeyword($key)->result();
        showLast_query("biru");


        if (sizeof($tmpO) > 0) {

            $socketConfig = "";
            if (isset($this->configUi[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->configUi[$jenisTr]['selectorSocket'];
            }

            $socketParams = array();
            $socketURL = array();

            if (sizeof($modelSrcFilter) > 0) {
                makeFilter($modelSrcFilter, $_SESSION['login'], $b);
            }


            $prodIds = array();
            if ($selectorModel == 'MdlProduk') {
                foreach ($tmpO as $prodItems) {
                    $prodIds[] = $prodItems->id;
                }
//                $this->db->where_in("produk_id", $prodIds);
            }

            /* -------------------------------------------------
             * aslinya ada dalam foreach dibawah enih, namun performenya akan buruk dikarekan selec yg dalam perulangan
             * ----------------------------------------------*/
            // cekMerah($selectorModel);
            switch ($selectorModel) {
                case "MdlProduk":
                case "MdlProduk2":
                case "MdlNotaItem":
                    // $this->db->limit(20);
                    // $this->db->order_by("id","desc");
                    if (sizeof($modelFilter) > 0) {
                        foreach ($modelFilter as $f) {
                            $f_ex = explode("=", $f);
                            if (!isset($f_ex[1])) {
                                $f_ey = explode(">", $f_ex[0]);
                                if (substr($f_ey[1], 0, 1) == ".") {
                                    // $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                    $b->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                }
                                else {
                                    if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                                        // $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                                        $b->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                                    }
                                    else {
                                        // $o->addFilter($f_ey[0] . ">0");
                                        $b->addFilter($f_ey[0] . ">0");
                                    }
                                }
                            }
                            else {
                                if (substr($f_ex[1], 0, 1) == ".") {
                                    $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                                }
                                else {
                                    if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                                        // $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                                        $b->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                                    }
                                    else {
                                        // $o->addFilter($f_ex[0] . "=''");
                                        $b->addFilter($f_ex[0] . "=''");
                                    }

                                }
                            }
                        }
                    }
                    break;
                // case "MdlProduk":
                //     $b->setFilters(array());
                //     $this->db->where_in("produk_id", $prodIds);
                //     break;
            }

            $dataSrc = $b->lookupAll()->result();
            // showLast_query("lime");
            // arrPrint($dataSrc);
            //            arrPrint($dataSrc);
            //             if(ipadd() == '202.65.117.72'){
            //
            //                 mati_disini(ipadd());
            //             }

            $tmpP = array();
            foreach ($dataSrc as $srcItems) {
                $main_key = isset($selectorSrcParamFields['id']) ? $selectorSrcParamFields['id'] : "id";
                // cekBiru($main_key);
                $tmpP[$srcItems->$main_key] = $srcItems;
            }
            //--

            $colors = array(
                "#000000",
                "#0056cd",
                "#ff7700",
                "#009900",
                "#9999cc",
            );

            foreach ($tmpO as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $pID = isset($row->produk_id) ? $row->produk_id : $row->id;
//                $pID = isset($row->$selectorParamFields['id']) ? $row->$selectorParamFields['id'] : $row->id;

                if ($isPinjamTr && in_array($pID, $arrPinjamProdIDs)) {
                    // Item pinjaman sudah diproses di atas
                    continue;
                }

                $defaultValue = isset($tmpP[$pID]->moq) ? $tmpP[$pID]->moq : $selectorDefaultMinValue;
                foreach ($selectorParamFields as $key => $src) {
                    $tmp[$key] = isset($row->$src) && $row->$src != "" ? $row->$src : "$key - null ";
                }

                $tmp['minValue'] = $defaultValue;
                // cekBiru($selectorModel);
                // ------------------------------------------------------------
                // if ($selectorModel == 'MdlProduk') {
                switch ($selectorModel) {
                    case "MdlProduk":
                    case "MdlProduk2":
                        /* ------------------------------
                         * pembeda warna dan link
                         * ---------------------------------*/
                        // cekBiru($pID);
                        if (isset($tmpP[$pID]->jumlah) && $tmpP[$pID]->jumlah > 0) {
                            $tmp['target'] = $processor;
                            $tmp['bg'] = "text-red";
                        }
                        else {
                            $tmp['target'] = $processor;
                            // $tmp['bg'] = "bg-grey-1 text-grey-1";
                            $tmp['bg'] = "";
                        }
                        break;
                    default:
                        $tmp['target'] = $processor;
                        break;
                }

                // }
                // else {
                //
                // }
                // ----------------------------------------------------------------------------
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                if ($isPinjamTr) {
                    $stokFisik = isset($row->jumlah) ? (float)$row->jumlah : 0;
                    $tmp['qty_pinjam'] = 0;
                    $tmp['stok_fisik'] = $stokFisik;
                    $tmp['no_pinjam'] = 1;
                    $tmp['bg'] = "text-muted";

                    $lblKode = (isset($row->kode) && strlen($row->kode) > 0 && $row->kode != $row->nama) ? "<span style='font-size:0.85em;margin:0px 3px;color:#777;'>[" . htmlspecialchars($row->kode) . "]</span>" : "";
                    $nonPinjamBadge = "<span class='label label-default text-muted' style='font-size:0.75em;margin-left:4px;'>Non-Pinjaman</span>";
                    $stokBadge = "<span class='text-muted' style='font-size:0.8em;margin-left:4px;'>(Stok: " . number_format($stokFisik) . " $satuan)</span>";
                    $dotIcon = "<i class='fa fa-circle-o text-muted' style='margin-left:6px;font-size:0.85em;' title='Bukan Barang Pinjaman'></i>";

                    $tmp['label'] = $lblKode . " " . $nonPinjamBadge . " " . $stokBadge . " " . $dotIcon;
                    $arrPinjamZero[] = $tmp;
                }
                else {
                    if (sizeof($selectorFields) > 0) {
                        $nCtr = 0;
                        foreach ($selectorFields as $f) {
                            // cekPink($f);
                            $nCtr++;
                            $align = $nCtr == 1 ? "text-left" : "text-right";
                            $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                            $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";
                            if ($f != 'kode' && is_numeric($row->$f)) {
                                $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                            }
                            else {
                                $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                                $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                            }
                        }
                    }
                }


                $addParams = array(
                    "cCode" => $cCode,
                );
                $socketURL[$tmp['id']] = isset($this->configUi[$jenisTr]['selectorSocket']) ? base_url() . $this->configUi[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
                $socketParams[$tmp['id']] = isset($socketConfig['inParams']) ? $socketConfig['inParams'] : array();

                if (isset($socketParams[$tmp['id']]) && sizeof($socketParams[$tmp['id']]) > 0) {
                    foreach ($socketParams[$tmp['id']] as $key => $src) {
                        $socketURL[$tmp['id']] .= "&$key={" . $src . "}";
                    }
                    if (sizeof($addParams) > 0) {
                        foreach ($addParams as $key => $src) {
                            $socketURL[$tmp['id']] .= "&$key=$src";
                        }
                    }
                }

                if ($preLocker) {
                    $stokLocker = $pl->cekLoker($cID, $pID, "active", "", "", $gID);
                    $valLocker = isset($stokLocker['jumlah']) ? $stokLocker['jumlah'] : 0;
                    //
                    // $tmp['stok'] = $valLocker;
                    if ($valLocker > 0) {
                        $items[] = $tmp;
                    }
                }
                else {
                    // cekHijau(__LINE__);
                    $items[] = $tmp;

                    if ($selectorModel == 'MdlProduk') {

                        if ($tmp['jumlah'] == 0) {
                            $arrKosong[] = $tmp;
                        }
                        else {
                            $arrReady[] = $tmp;
                        }

                        // cekHijau($arrReady);
                        // cekBiru($arrKosong);
                        /* ---------------------------------------------------------
                         * data ini yg ditampilkan pada selektor
                         * -------------------------------------------------------*/
                        $items = array_merge(sizeof($arrReady) > 0 ? $arrReady : array(), sizeof($arrKosong) > 0 ? $arrKosong : array());
                    }
                }
            }

            if ($isPinjamTr) {
                $items = array_merge(sizeof($arrPinjamReady) > 0 ? $arrPinjamReady : array(), sizeof($arrPinjamZero) > 0 ? $arrPinjamZero : array());
            }
        }
        elseif ($isPinjamTr && sizeof($arrPinjamReady) > 0) {
            $items = $arrPinjamReady;
        }
        else {
            //            cekhitam("tidak ada data");
        }

        // cekKuning($items);
        // cekKuning($selectorView);
        $data = array(
            "mode" => "view",
            "selectorNaming" => $selectorNaming,
            "cCode" => "$cCode",
            "items" => $items,
            "socketParams" => isset($socketParams) ? $socketParams : array(),
            "socketURL" => isset($socketURL) ? $socketURL : array(),
        );

        $this->load->view("$selectorView", $data);

    }


    public function selectItem2()
    {

        $jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);


        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $modelFilter = isset($this->configUi[$jenisTr]['selectorFilters']) ? $this->configUi[$jenisTr]['selectorFilters'] : array();
        $selectorFields = isset($this->configUi[$jenisTr]['selectorViewedFields']) ? $this->configUi[$jenisTr]['selectorViewedFields'] : array();
        $selectorParamFields = isset($this->configUi[$jenisTr]['selectorParamFields']) ? $this->configUi[$jenisTr]['selectorParamFields'] : array();

        $selectorModel = isset($this->configUi[$jenisTr]['selectorModel2']) ? $this->configUi[$jenisTr]['selectorModel2'] : "MdlProduk";
        $selectorSrcModel = isset($this->configUi[$jenisTr]['selectorSrcModel2']) ? $this->configUi[$jenisTr]['selectorSrcModel2'] : "MdlProduk";

        $key = isset($_GET['search']) ? $_GET['search'] : "";
        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();

        //pairing produk
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        if (sizeof($modelFilter) > 0) {
            foreach ($modelFilter as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {

                            $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                        }
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                            $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                        }

                    }
                }
            }
        }

        //        $o->createSmartSearch($key,$o->getListedFieldsSelectItem());
        $tmpO = $o->lookupByKeyword($key)->result();
        //        cekmerah($this->db->last_query());

        if (sizeof($tmpO) > 0) {
            $processor = base_url() . $this->configUi[$jenisTr]['selectorProcessor2'] . "/$jenisTr";

            if (isset($this->configUi[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->configUi[$jenisTr]['selectorSocket'];
            }
            $socketParams = array();
            $socketURL = array();

            //            arrprint($tmpO);

            foreach ($tmpO as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $pID = isset($row->produk_id) ? $row->produk_id : $row->id;

                $b->addFilter("id=" . $pID);
                $tmpP = $b->lookupAll($pID)->result();
                //                cekkuning($this->db->last_query());
                $defaultValue = isset($tmpP[0]->moq) ? $tmpP[0]->moq : 0;
                foreach ($selectorParamFields as $key => $src) {
                    $tmp[$key] = $row->$src;
                }
                $tmp['minValue'] = $defaultValue;
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                if (sizeof($selectorFields) > 0) {
                    foreach ($selectorFields as $f) {
                        if (is_numeric($row->$f)) {
                            $tmp['label'] .= "" . number_format($row->$f) . " | ";
                        }
                        else {
                            $tmp['label'] .= "" . $row->$f . " | ";
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
                    //                    $tmp['label'] = "<div class='no-padding'>". $tmp['label'] . "</div> " . $row->jumlah ;
                    //                    $tmp['label'] = "<div style='font-size:0.8em' class='no-padding'>" . ($tmp['label']) . "</div> ";
                    $tmp['label'] = "<div style='font-size:0.8em' class='no-padding'>" . ($tmp['label']) . "</div> ";

                }


                $addParams = array(
                    "cCode" => $cCode,
                );
                $socketURL[$tmp['id']] = isset($this->configUi[$jenisTr]['selectorSocket']) ? base_url() . $this->configUi[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
                $socketParams[$tmp['id']] = isset($socketConfig['inParams']) ? $socketConfig['inParams'] : array();
                if (isset($socketParams[$tmp['id']]) && sizeof($socketParams[$tmp['id']]) > 0) {
                    foreach ($socketParams[$tmp['id']] as $key => $src) {
                        $socketURL[$tmp['id']] .= "&$key={" . $src . "}";
                    }
                    if (sizeof($addParams) > 0) {
                        foreach ($addParams as $key => $src) {
                            $socketURL[$tmp['id']] .= "&$key=$src";
                        }
                    }
                }


                $items[] = $tmp;
            }
        }


        $data = array(
            "mode" => "view",
            "cCode" => "$cCode",
            //            "arrayFields"=>$selectorFields,
            "items" => $items,
            "socketParams" => isset($socketParams) ? $socketParams : array(),
            "socketURL" => isset($socketURL) ? $socketURL : "",
        );


        //        arrprint($data);die();

        $this->load->view("_selector", $data);

    }

}