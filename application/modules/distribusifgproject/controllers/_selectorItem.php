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
        $pihakProjekID = isset($_SESSION[$cCode]['main']['pihakProjekID']) ? $_SESSION[$cCode]['main']['pihakProjekID'] : 0;

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
        $selectorDefaultMinValue = isset($this->configUi[$jenisTr]['selectorDefaultMinValue']) ? $this->configUi[$jenisTr]['selectorDefaultMinValue'] : "1";
        $key = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : "";
        $preLocker = isset($this->configUi[$jenisTr]['validLocker']) ? $this->configUi[$jenisTr]['validLocker'] : false;
        $selectorOrderBy = isset($this->configUi[$jenisTr]['selectorOrderBy']) ? $this->configUi[$jenisTr]['selectorOrderBy'] : NULL;
        $selectorLinkMutasi = isset($this->configUi[$jenisTr]['selectorLinkMutasi']) ? $this->configUi[$jenisTr]['selectorLinkMutasi'] : array();
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

        //pairing produk
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        // pairing used produk from workorder sub
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $pksw = new MdlProjectKomposisiWorkorderSub();
        $pksw->addFilter("jenis='produk'");
        $pksw->addFilter("jenis_transaksi='sub_wo'");
        $pksw->addFilter("produk_id='$pihakProjekID'");
//        $pksw->addFilter("qty_saldo>0");

        $prdSubWO = $pksw->lookupall()->result();
        // cekMerah(__LINE__);
        // showLast_query("biru");

        $stokSubWO = array();
        if (!empty($prdSubWO)) {
            foreach ($prdSubWO as $ky => $swRow) {
                $stokSubWO[$swRow->no_spk][$swRow->produk_dasar_id] = $swRow;
            }
        }

//        arrPrintWebs($stokSubWO);

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

//        cekHere("filterCustom: $filterCustom");
//        arrPrint($modelFilter);

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

//         cekHijau($processor);

        /* ----------------------------------------------------------------------------------------------------------
         * bila ada main session yg ilang saat clear shoping cart ditambah di inisiasi _shopingCart/reset
         * ----------------------------------------------------------------------------------------------------------*/
        if (strlen($key) < 3) {
//            $this->db->limit(100); //dimatikan karena tidak bisa select all produk
        }

        $o->addFilter("produk_id='$pihakProjekID'");
        $o->addFilter("progress_id=2");
        $tmpO = $o->lookupByKeyword($key)->result();
//        showLast_query("biru");
//        cekUngu("tmpO count : " . count($tmpO));
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
//            showLast_query("biru");

            $tmpP = array();
            foreach ($dataSrc as $srcItems) {
                $main_key = isset($selectorSrcParamFields['id']) ? $selectorSrcParamFields['id'] : "id";
                // cekBiru($main_key);
                $tmpP[$srcItems->$main_key] = $srcItems;
            }

            $colors = array(
                "#000000",
                "#0056cd",
                "#ff7700",
                "#009900",
                "#9999cc",
            );

            $this->load->model("Mdls/MdlLockerStock");

            foreach ($tmpO as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $pID = isset($row->produk_id) ? $row->produk_id : $row->id;
                $fase_id = isset($row->fase_id) ? $row->fase_id : 0;
                $sub_fase_id = isset($row->sub_fase_id) ? $row->sub_fase_id : 0;
                $no_spk = isset($row->no_spk) ? $row->no_spk : 0;
                $ambil_angka_depan_spk = explode("/", $no_spk)[0];
                $vGdID = "$pID" . "$fase_id" . "$sub_fase_id" . "$ambil_angka_depan_spk";

                $prLock = new MdlLockerStock();
                $prLock->addFilter("gudang_id='$vGdID'");
//                $prLock->addFilter("state='active'");
                $prLock->addFilter("jumlah>0");
                $tmprLock = $prLock->lookupAll()->result();

                $fromLockerData = array();
                if(!empty($tmprLock)){
                    foreach($tmprLock as $row_){
                        $fromLockerData[$row_->gudang_id][$row_->produk_id][] = $row_;
                    }
                }

//                arrPrint($fromLockerData[$vGdID]);
//                showLast_query("biru");
//                cekUngu("tmprLock count : " . count($tmprLock));
                $rekap = [];
                $jml_asli = $stokSubWO[$no_spk][$produk]->jml;
//                cekMerah($swRow->no_spk . " | produk: $produk || " . $jml_asli . " | ");
//                arrPrint( json_encode($stokSubWO[$no_spk]));

                $fromWoData = $stokSubWO[$no_spk];

                foreach ($fromWoData as $item){
                    $produk = $item->produk_dasar_id;
                    $jml_asli = $item->jml;

                    if (!isset($rekap[$no_spk][$produk])) {
                        $rekap[$no_spk][$produk] = [
                            'req'       => 0,
                            'active'    => 0,
                            'distribute'=> 0,
                            'returned'  => 0,
                            'kelebihan' => 0,
                            'hold'      => 0,
                            'intransit' => 0,
                            'bobot'     => 0,
                        ];
                    }

                    $rekap[$no_spk][$produk]["req"] = $jml_asli;

                    if(isset($fromLockerData[$vGdID][$produk])){
                        $arrDataFromLocker = $fromLockerData[$vGdID][$produk];
//                        arrPrint($arrDataFromLocker);
                        foreach($arrDataFromLocker as $item){

                            $state  = $item->state;
                            if ($state == 'hold') {
                                $stateKey = 'hold';
                            }
                            elseif ($state == 'distribute') {
                                $stateKey = 'distribute';
                            }
                            elseif ($state == 'intransit') {
                                $stateKey = 'intransit';
                            }
                            elseif ($state == 'return') {
                                $stateKey = 'returned';
                            }
                            else {
                                $stateKey = 'active';
                            }

                            $rekap[$no_spk][$produk][$stateKey] += $item->jumlah;

                            if ($rekap[$no_spk][$produk][$stateKey] > $jml_asli) {
                                $rekap[$no_spk][$produk]['kelebihan'] = $rekap[$no_spk][$produk][$stateKey] - $jml_asli;
                            }
                            $totalDistribusi[$no_spk] = $rekap[$no_spk][$produk]['active'] + $rekap[$no_spk][$produk]['distribute'] + $rekap[$no_spk][$produk]['hold'] - $rekap[$no_spk][$produk]['returned'] + $rekap[$no_spk][$produk]['intransit'];
                            $rekap[$no_spk][$produk]['bobot'] = $rekap[$no_spk][$produk]['req'] > 0
                                ? round(($totalDistribusi[$no_spk] / $rekap[$no_spk][$produk]['req']) * 100, 2)
                                : 0;
                        }
                    }
                }

// ==== GRAND TOTAL ====
                $grandReq = 0;
                $grandActive = 0;
                $grandDistribute = 0;
                $grandHold = 0;
                $grandIntransit = 0;
                $grandReturned = 0;
                $grandKelebihan = 0;

                foreach ($rekap as $_spk => $_spkData) {
                    foreach ($_spkData as $produk => $states) {
                        $grandReq       += $states['req'];
                        $grandActive    += $states['active'];
                        $grandDistribute+= $states['distribute'];
                        $grandHold      += $states['hold'];
                        $grandIntransit += $states['intransit'];
                        $grandReturned  += $states['returned'];
                        $grandKelebihan += $states['kelebihan'];
                    }
                }

                $grandDistribusi = $grandActive + $grandDistribute + $grandReturned + $grandHold + $grandIntransit;
                $grandBobot = $grandReq > 0 && $grandDistribusi > 0 ? round(($grandDistribusi / $grandReq) * 100, 2) : 0;

                // cekHijau("grandActive: $grandActive | grandDistribute: $grandDistribute | grandReturned: $grandReturned | grandHold: $grandHold | grandIntransit: $grandIntransit");

//                cekHijau("grandDistribusi");
//                cekHijau($grandDistribusi);

            // OUTPUT TABLE
//                echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
//                echo "<tr style='background:#eee;'>
//                        <th>Produk ID</th>
//                        <th>Req</th>
//                        <th>Active</th>
//                        <th>Intransit</th>
//                        <th>Distribute</th>
//                        <th>Returned</th>
//                        <th>Lebih</th>
//                        <th>Bobot (%)</th>
//                      </tr>";

                foreach ($rekap as $_spk => $_dataSpk) {
                    foreach ($_dataSpk as $produk => $states) {
//                        echo "<tr>
//                                <td>{$produk}</td>
//                                <td align='center'>{$states['req']}</td>
//                                <td align='center'>{$states['active']}</td>
//                                <td align='center'>{$states['intransit']}</td>
//                                <td align='center'>{$states['distribute']}</td>
//                                <td align='center'>{$states['returned']}</td>
//                                <td align='center'>{$states['kelebihan']}</td>
//                                <td align='center'>{$states['bobot']}%</td>
//                              </tr>";
                    }
                }

//                echo "<tr style='background:#f5f5f5; font-weight:bold;'>
//                        <td>TOTAL</td>
//                        <td align='center'>{$grandReq}</td>
//                        <td align='center'>{$grandActive}</td>
//                        <td align='center'>{$grandIntransit}</td>
//                        <td align='center'>{$grandDistribute}</td>
//                        <td align='center'>{$grandReturned}</td>
//                        <td align='center'>{$grandKelebihan}</td>
//                        <td align='center'>{$grandBobot}%</td>
//                      </tr>";
//                echo "</table>";


//                $stockFromLocker=0;
//                $arrStockFromLocker=array();
//                if(!empty($tmprLock)){
//                    foreach($tmprLock as $ky => $sfl){
//                        $stockFromLocker += $sfl->jumlah;
//                        $arrStockFromLocker[$sfl->produk_id] = $sfl->jumlah;
//                    }
//                }
//                arrPrint($tmprLock);
//                matiHere();
//                cekMerah("vGdID: $vGdID | pID: $pID | fid:$fase_id | sfid: $sub_fase_id");
//                arrPrintWebs($stokSubWO[$sub_fase_id]);
                /* ------------------------------------------------------
                 * ngelokup dalam foreacht harap dipertimbangkan ni loadtimenya
                 * ------------------------------------------------------*/
                // $b->addFilter($b->getTableName() . ".id=" . $pID);
                // $tmpP = $b->lookupAll($pID)->result();
                // $defaultValue = isset($tmpP[0]->moq) ? $tmpP[0]->moq : 0;

                $defaultValue = isset($tmpP[$pID]->moq) ? $tmpP[$pID]->moq : $selectorDefaultMinValue;
                foreach ($selectorParamFields as $key => $src) {
                    $tmp[$key] = isset($row->$src) && $row->$src != "" ? $row->$src : "$key - null ";
                }

                $tmp['minValue'] = $defaultValue;
                $tmp['no_spk'] = $no_spk;

                $produk_sub_wo_debet = 0;
                $produk_sub_wo_kredit = 0;
                $produk_sub_wo_saldo = 0;
                $produk_sub_wo_debet_rp = 0;
                $produk_sub_wo_kredit_rp = 0;
                $produk_sub_wo_saldo_rp = 0;

                $kelebihan = array();
                $tmp['qty_distribute_persentase'] = 0;
                if(isset($stokSubWO[$no_spk])){
//                    foreach($stokSubWO[$no_spk] as $sfpid => $sfRow){
//                        $produk_sub_wo_debet     += $sfRow->qty_debet;
//                        $produk_sub_wo_kredit    += $sfRow->qty_kredit;
//                        $produk_sub_wo_saldo     += $sfRow->qty_saldo;
//                        $produk_sub_wo_debet_rp  += $sfRow->debet;
//                        $produk_sub_wo_kredit_rp += $sfRow->kredit;
//                        $produk_sub_wo_saldo_rp  += $sfRow->saldo;
//
////                        if(isset($arrStockFromLocker[$sfpid]) && $arrStockFromLocker[$sfpid] > $sfRow->qty_saldo){
////                            $kelebihan += $arrStockFromLocker[$sfpid] - $sfRow->qty_saldo;
////                        }
//
//                        $kelebihan[$sfpid] = $arrStockFromLocker[$sfpid];
//                    }
                    $tmp['qty_distribute_persentase'] = $grandBobot;
                    $tmp['rp_distribute_persentase']  = 0;
                }

//                arrPrintWebs($tmp);
//                arrPrintWebs($kelebihan);
//                arrPrintWebs("GUDANG: $vGdID | LB:  | WO: $produk_sub_wo_debet | SL: $stockFromLocker");
//                arrPrintWebs("GUDANG: $vGdID | WO: $produk_sub_wo_kredit | SL: $stockFromLocker");
                // cekBiru($selectorModel);

                // ------------------------------------------------------------

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
                    case "MdlLockerStock":
                        if (isset($row->jumlah) && $row->jumlah > 0) {
                            $tmp['target'] = $processor;
                            $tmp['bg'] = "text-red";
                        }
                        else {
                            $tmp['target'] = null;
                            $tmp['bg'] = "bg-grey-1 text-grey-1";
                            $tmp['notes'] = "stok kosong";
                        }
                        break;
                    default:
                        $tmp['target'] = $processor;
                        break;
                }

                //arrPrint($tmp);
                //$tmp['target'] = $processor;

                $tmp['label'] = "";
                if (sizeof($selectorFields) > 0) {
                    $nCtr = 0;
                    foreach ($selectorFields as $f) {
                        // cekPink($f);
                        $nCtr++;
                        $align = $nCtr == 1 ? "text-left" : "text-right";
                        $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                        $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                        if ($f == 'id' && is_numeric($row->$f)) {
                            $tmp['label'] .= "<div style='$fSize ;margin:0px 2px 0px 2px;color:$color;font-weight:bold;' class='no-padding no-border'> PID: " . number_format($row->$f) . " </div>";
                        }
                        elseif ($f != 'kode' && is_numeric($row->$f)) {
                            $tmp['label'] .= "<div style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border'>" . number_format($row->$f) . "</div>";
                        }
                        else {
                            $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                            $tmp['label'] .= "<div style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border'>" . $newFields . "</div>";
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
                    $tmp['label'] = ($tmp['label']);

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
                // $stokLocker = $pl->cekLoker($cID, $pID, "active", "", "", $gID);
                // $valLocker = isset($stokLocker['jumlah']) ? $stokLocker['jumlah'] : 0;
                // $tmp['stok'] = 0;
                if ($preLocker) {
                    $stokLocker = $pl->cekLoker($cID, $pID, "active", "", "", $gID);
                    $valLocker = isset($stokLocker['jumlah']) ? $stokLocker['jumlah'] : 0;
                    // $tmp['stok'] = $valLocker;
                    if ($valLocker > 0) {
                        $items[] = $tmp;
                    }
                }
                else {
//                    cekHijau(__LINE__);
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
        }
        else {
            //            cekhitam("tidak ada data");
        }

//        cekKuning($items);
//        cekKuning($selectorView);
//        matiHere(__LINE__);
        $data = array(
            "mode" => "view",
            "selectorNaming" => $selectorNaming,
            "cCode" => "$cCode",
            "jenisTr" => $jenisTr,
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