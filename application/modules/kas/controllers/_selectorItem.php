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

        $selectorDefaultMinValue = isset($this->configUi[$jenisTr]['selectorDefaultMinValue']) ? $this->configUi[$jenisTr]['selectorDefaultMinValue'] : "0";
        $key = isset($_GET['search']) ? $_GET['search'] : "";
        $preLocker = isset($this->configUi[$jenisTr]['validLocker']) ? $this->configUi[$jenisTr]['validLocker'] : false;
        $selectorOrderBy = isset($this->configUi[$jenisTr]['selectorOrderBy']) ? $this->configUi[$jenisTr]['selectorOrderBy'] : NULL;
        $pairMakers = isset($this->configUi[$jenisTr]['pairMakers'][1]) ? $this->configUi[$jenisTr]['pairMakers'][1] : NULL;
        $pairSaldo = isset($this->configUi[$jenisTr]['pairSaldo']) ? $this->configUi[$jenisTr]['pairSaldo'] : NULL;
        $pairSaldoMutasi = isset($this->configUi[$jenisTr]['pairSaldoMutasi']) ? $this->configUi[$jenisTr]['pairSaldoMutasi'] : NULL;
        $viewSelectorCustomAllow = isset($this->configUi[$jenisTr]['viewSelectorCustomAllow']) ? $this->configUi[$jenisTr]['viewSelectorCustomAllow'] : false;


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
//                                cekHitam("HAHAHA " . $f_ey[0]);
                                $f_ey = explode(" in ", $f_ey[0]);
                                if (sizeof($f_ey) > 0) {
                                    $o->addFilter($f_ey[0] . " in " . $f_ey[1]);
                                }
                                else {
                                    $o->addFilter($f_ey[0] . ">0");
                                }
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
        /* ----------------------------------------------------------------------------------------------------------
         * bila ada main session yg ilang saat clear shoping cart ditambah di inisiasi _shopingCart/reset
         * ----------------------------------------------------------------------------------------------------------*/
        if (strlen($key) < 3) {
            $this->db->limit(20); //dimatikan karena tidak bisa select all produk
        }
        $tmpO = $o->lookupByKeyword($key)->result();
//        cekMerah($this->db->last_query());
//        matiHEre();

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
//            cekMerah($selectorModel);
            switch ($selectorModel) {
                case "MdlProduk":
                case "MdlProduk2":
                case "MdlNotaItem":
//                    arrprint($modelFilter);
//                    matiHere();
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
                                    $b->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
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
                case "MdlTransaksi2":
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
                                        $b->addFilter($f_ey[0] . ">0");
                                    }
                                }
                            }
                            else {
                                if (substr($f_ex[1], 0, 1) == ".") {
                                    $b->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
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
            if (strlen($key) < 3) {
                $this->db->limit(20); //dimatikan karena tidak bisa select all produk
            }
            $dataSrc = $b->lookupAll()->result();

            $tmpP = array();
            foreach ($dataSrc as $srcItems) {
                $main_key = isset($selectorSrcParamFields['id']) ? $selectorSrcParamFields['id'] : "id";
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
                $defaultValue = isset($tmpP[$pID]->moq) ? $tmpP[$pID]->moq : $selectorDefaultMinValue;
                foreach ($selectorParamFields as $key => $src) {
                    $tmp[$key] = isset($row->$src) && $row->$src != "" ? $row->$src : "$key - null ";
                }
                //------
                if (sizeof($pairMakers) > 0) {
                    foreach ($pairMakers as $iix => $iixSpec) {
                        $this->load->helper("Pairs/" . $iixSpec["helperName"]);
//                        cekHitam($iixSpec["helperName"]);
                        $hasil = $iixSpec["functionName"]($this->jenisTr, 1, $iixSpec["params"], 0);
                        if (sizeof($hasil) > 0) {
                            foreach ($hasil as $h_key => $h_val) {
                                $tmp[$h_key] = $h_val;
                            }
                        }
                    }
                }
                //------

                $tmp['minValue'] = $defaultValue;
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
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                $tmp['link_mutasi'] = "";
                if (sizeof($selectorFields) > 0) {
                    $nCtr = 0;
                    if($viewSelectorCustomAllow == true){
                        foreach ($selectorFields as $f) {
                            $nCtr++;
                            $align = $nCtr == 1 ? "text-left" : "text-right";
                            $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:1em";
                            $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";
                            if ($f != 'kode' && is_numeric($row->$f)) {
                                $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='nno-padding no-border btn btn-default btn-sm $align'>" . number_format($row->$f) . "</span>";
                            }
                            else {
                                $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                                $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='nno-padding no-border btn btn-default btn-sm $align'>" . $newFields . "</span>";
                            }
                        }
                    }
                    else{
                        foreach ($selectorFields as $f) {
                            $nCtr++;
                            $align = $nCtr == 1 ? "text-left" : "text-right";
                            $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:1em";
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

                    $tmp['label'] = rtrim($tmp['label'], "| ");
                    $tmp['label'] = ($tmp['label']);
                }
                //------
                if (sizeof($pairSaldo) > 0) {
                    foreach ($pairSaldo as $s_key => $s_val) {
                        if ($s_key == $row->coa_code) {
                            $align = "text-left";
                            $fSize = "font-size:1em";
                            $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";
                            if ($s_val != 'kode' && is_numeric($tmp[$s_val])) {
                                $saldo = $tmp[$s_val];
                                if ($saldo > 0) {
                                    $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'> @ " . number_format($saldo) . "</span>";
                                }
                                else {
                                    $tmp['label'] .= "<br><span style='$fSize ;margin:0px 2px 0px 2px;color:#717271;' cclass='no-padding no-border $align'>";
                                    $tmp['label'] .= "<span class='fa fa-exclamation-triangle' style='color:red;font-style:italic;'> saldo kosong/habis, tidak ada yang perlu dikembalikan.</span>";
                                    $tmp['label'] .= "</span>";
                                    $tmp['target'] = "";
                                }
                            }
                            if (isset($pairSaldoMutasi[$row->coa_code])) {
                                $pihakID = $_SESSION[$cCode]["main"]["pihakID"];
                                $pihakName = $_SESSION[$cCode]["main"]["pihakName"];
                                $ket_label = $row->nama;
                                $rek_nama = fetchAccountStructureAlias()[$row->coa_code];
                                $text = $rek_nama . " " . $pihakName;
                                $blob_ext = blobEncode($text);
                                $link_mutasi = base_url() . $pairSaldoMutasi[$row->coa_code] . "/$pihakID/" . $row->coa_code . "?o=" . my_cabang_id() . "&main_ext2_id=$pihakID&blob_ext=$blob_ext";
                                $tmp['link_mutasi'] = "<a href='$link_mutasi' class='btn btn-sm' data-toggle='tooltip' title='Mutasi $ket_label $pihakName' target='_blank'><span class='text-muted fa fa-clock-o'></span></a>";
                            }


                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
                    $tmp['label'] = ($tmp['label']);
                }
                //------

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
        }
        else {
            //            cekhitam("tidak ada data");
        }
//        arrPrintCyan($items);
//        arrPrintCyan($socketURL);
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

    public function selectItemUm()
    {
        $jenisTr = $this->jenisTr;
        $cCode   = $this->cCode;

        $cekES = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : "";
        $cID   = isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : $this->session->login['cabang_id'];
        $gID   = isset($_SESSION[$cCode]['main']['gudangID']) ? $_SESSION[$cCode]['main']['gudangID'] : $this->session->login['gudang_id'];
        $tkID  = isset($_SESSION[$cCode]['main']['tokoID']) ? $_SESSION[$cCode]['main']['tokoID'] : (isset($this->session->login['toko_id']) ? $this->session->login['toko_id'] : "");

        $mdlName = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->uri->segment(5);

        $fields = $this->configUi[$jenisTr]['selectorFields'];

        $modelFilter        = isset($this->configUi[$jenisTr]['selectorFilters']) ? $this->configUi[$jenisTr]['selectorFilters'] : array();
        $modelFilterCustom  = isset($this->configUi[$jenisTr]['selectorMainFilters']) ? $this->configUi[$jenisTr]['selectorMainFilters'] : array();
        $modelSrcFilter     = isset($this->configUi[$jenisTr]['selectorSrcFilters']) ? $this->configUi[$jenisTr]['selectorSrcFilters'] : array();

        $selectorFields         = isset($this->configUi[$jenisTr]['selectorViewedFields']) ? $this->configUi[$jenisTr]['selectorViewedFields'] : array();
        $selectorNaming         = isset($this->configUi[$jenisTr]['selectorViewedNames']) ? $this->configUi[$jenisTr]['selectorViewedNames'] : array();
        $selectorParamFields    = isset($this->configUi[$jenisTr]['selectorParamFields']) ? $this->configUi[$jenisTr]['selectorParamFields'] : array();
        $selectorSrcParamFields = isset($this->configUi[$jenisTr]['selectorSrcParamFields']) ? $this->configUi[$jenisTr]['selectorSrcParamFields'] : array();
        $selectorMainFields     = isset($this->configUi[$jenisTr]['selectorMainViewedFields']) ? $this->configUi[$jenisTr]['selectorMainViewedFields'] : array();
        $selectorMainParamFields= isset($this->configUi[$jenisTr]['selectorMainParamFields']) ? $this->configUi[$jenisTr]['selectorMainParamFields'] : array();
        $selectorModel          = isset($this->configUi[$jenisTr]['selectorModel']) ? $this->configUi[$jenisTr]['selectorModel'] : "MdlProduk";
        $selectorSrcModel       = isset($this->configUi[$jenisTr]['selectorSrcModel']) ? $this->configUi[$jenisTr]['selectorSrcModel'] : "MdlProduk";
        $selectorView           = isset($this->configUi[$jenisTr]['selectorView']) ? $this->configUi[$jenisTr]['selectorView'] : "_selector";

        $selectorDefaultMinValue= isset($this->configUi[$jenisTr]['selectorDefaultMinValue']) ? $this->configUi[$jenisTr]['selectorDefaultMinValue'] : "0";
        $key                    = isset($_GET['search']) ? $_GET['search'] : "";
        $preLocker              = isset($this->configUi[$jenisTr]['validLocker']) ? $this->configUi[$jenisTr]['validLocker'] : false;
        $selectorOrderBy        = isset($this->configUi[$jenisTr]['selectorOrderBy']) ? $this->configUi[$jenisTr]['selectorOrderBy'] : NULL;
        $pairMakers             = isset($this->configUi[$jenisTr]['pairMakers'][1]) ? $this->configUi[$jenisTr]['pairMakers'][1] : NULL;
        $pairSaldo              = isset($this->configUi[$jenisTr]['pairSaldo']) ? $this->configUi[$jenisTr]['pairSaldo'] : NULL;
        $pairSaldoMutasi        = isset($this->configUi[$jenisTr]['pairSaldoMutasi']) ? $this->configUi[$jenisTr]['pairSaldoMutasi'] : NULL;


        $_SESSION[$cCode]['main']['selectedType_konsumen'] = "include_ppn";

        // === helper: EXACT legacy filter dibungkus agar reusable (PHP 5.6 OK)
        $__apply_legacy_filter = function($targetModel, $filters, $sess) {
            if (!is_array($filters) || sizeof($filters) == 0) return;
            foreach ($filters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (isset($f_ey[1]) && substr($f_ey[1], 0, 1) == ".") {
                        $targetModel->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    } else {
                        if (isset($f_ey[1]) && isset($sess[$f_ey[1]])) {
                            $targetModel->addFilter($f_ey[0] . ">'" . $sess[$f_ey[1]] . "'");
                        } else {
                            $f_ey = explode(" in ", $f_ey[0]);
                            if (sizeof($f_ey) > 0) {
                                $targetModel->addFilter($f_ey[0] . " in " . $f_ey[1]);
                            } else {
                                $targetModel->addFilter($f_ey[0] . ">0");
                            }
                        }
                    }
                } else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $targetModel->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    } else {
                        if (isset($sess[$f_ex[1]])) {
                            $targetModel->addFilter($f_ex[0] . "='" . $sess[$f_ex[1]] . "'");
                        } else {
                            $targetModel->addFilter($f_ex[0] . "=''");
                        }
                    }
                }
            }
        };

        // === helper: safe convert CI result -> array
        $__result_to_array = function($q) {
            $data = array();
            if (is_object($q) && method_exists($q, 'result_array')) {
                $data = $q->result_array();
            }
            elseif (is_object($q) && method_exists($q, 'result')) {
                $tmp = $q->result();
                foreach ($tmp as $oRow) { $data[] = (array)$oRow; }
            }
            return $data;
        };

        $items = array();

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }

        if ($preLocker) {
            $mdlPreLocker = $this->configUi[$jenisTr]["lockerCheck"]["mdlName"];
            $this->load->model("Mdls/" . $mdlPreLocker);
            $pl = new $mdlPreLocker();
        }

        // load single (tetap, meski nanti mungkin tidak dipakai saat multi-model)
        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();

        // pairing produk
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        // custom filter untuk MAIN (seperti existing)
        $arrFilterCustom = array();
        $filterCustom    = false;
        if (sizeof($modelFilterCustom) > 0) {
            if (isset($modelFilterCustom[$_SESSION[$cCode]['main']['pihakMainName']])) {
                $arrFilterCustom = $modelFilterCustom[$_SESSION[$cCode]['main']['pihakMainName']];
                $filterCustom    = true;
            } else {
                $filterCustom    = false;
            }
        } else {
            $filterCustom = false;
        }

        if ($filterCustom == true) {
            if (sizeof($arrFilterCustom) > 0) {
                makeFilter($arrFilterCustom, $_SESSION[$cCode]['main'], $o); // akan diabaikan jika multi-model (o tidak dipakai)
            }
            $selectorFields      = $selectorMainFields[$_SESSION[$cCode]['main']['pihakMainName']];
            $selectorParamFields = $selectorMainParamFields[$_SESSION[$cCode]['main']['pihakMainName']];

            $selectorProcessor = $this->configUi[$jenisTr]['selectorMainProcessor'][$_SESSION[$cCode]['main']['pihakMainName']];
            $processor = $this->modulPath . $selectorProcessor . "/$jenisTr";
        }
        else {
            // set default processor seperti existing
            $processor = $this->modulPath . $this->configUi[$jenisTr]['selectorProcessor'] . "/$jenisTr";
            // filter untuk $o (single model) akan diaplikasikan di bawah jika jalur single digunakan
        }

        // ==========================================================
        // MULTI-MODEL PATH via selectorTab (gabung jadi satu $tmpO)
        // ==========================================================
        $selectorTabs = isset($this->configUi[$jenisTr]['selectorTab']) ? $this->configUi[$jenisTr]['selectorTab'] : array();
        if (!empty($selectorTabs)) {
            $sessionMain = isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array();
            $rowsMerged  = array();
            $seenKeys    = array(); // dedup per-tab
            $limitShort  = (strlen($key) < 3) ? 20 : 0;

            foreach ($selectorTabs as $tabCode => $tabCfg) {
                $mdlFromCfg  = isset($tabCfg['mdlName']) ? $tabCfg['mdlName'] : '';
                if ($mdlFromCfg === '') continue;

                $tabFilters  = isset($tabCfg['mdlFilter']) ? (array)$tabCfg['mdlFilter'] : array();
                $tabKeyField = isset($tabCfg['key']) ? $tabCfg['key'] : 'id';
                $tabLabelSrc = isset($tabCfg['labelSrc']) ? $tabCfg['labelSrc'] : '';

                // load & instance tiap model
                $this->load->model("Mdls/" . $mdlFromCfg);
                $m = new $mdlFromCfg();

                // pakai filter lama (1:1)
                $__apply_legacy_filter($m, $tabFilters, $sessionMain);

                // limit jika keyword pendek
                if ($limitShort > 0) {
                    $this->db->limit($limitShort);
                }

//                $this->db->where("sisa>10");

                // ambil data: prefer lookupByKeyword, fallback lookupAll
                if (method_exists($m, 'lookupByKeyword')) {
                    $q = $m->lookupByKeyword($key);
                }
                elseif (method_exists($m, 'lookupAll')) {
                    $q = $m->lookupAll();
                }
                else {
                    $q = NULL;
                }

//                showLast_query("hitam");

                $data = $q ? $__result_to_array($q) : array();

                if(empty($data)){
//                    echo "==== empty ====";
                }
                else{
//                    cekHere( json_encode($data) );
                }

                foreach ($data as $row) {
                    $rowKey = isset($row[$tabKeyField]) ? $row[$tabKeyField] : null;
                    $dedupKey = $tabCode . '::' . (string)$rowKey;
                    if ($rowKey !== null && isset($seenKeys[$dedupKey])) continue;
                    $seenKeys[$dedupKey] = 1;

                    // tambahkan meta (aman diabaikan downstream)
                    $row['_selector_tab']   = $tabCode;
                    $row['_selector_model'] = $mdlFromCfg;
                    if (is_string($tabLabelSrc) && $tabLabelSrc !== '') {
                        $parts = explode('/', $tabLabelSrc);
                        $vals  = array();
                        foreach ($parts as $p) {
                            $p = trim($p);
                            $vals[] = isset($row[$p]) ? formatNota($p, $row[$p]) : '';
                        }
                        $row['_selector_label'] = trim(implode(' / ', $vals), " /");
                    }
                    // cast array -> object
                    $rowsMerged[] = json_decode(json_encode($row));
                }
            }
            $tmpO = $rowsMerged;
        }
        else {
            // ==========================================================
            // SINGLE-MODEL PATH (perilaku lama)
            // ==========================================================
            if ($filterCustom != true) {
                // apply filter lama ke $o persis existing
                $__apply_legacy_filter($o, $modelFilter, isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array());
            }
            if (strlen($key) < 3) {
                $this->db->limit(20); //dimatikan karena tidak bisa select all produk
            }
            $tmpO = $o->lookupByKeyword($key)->result();
        }

        if (sizeof($tmpO) > 0) {
            $socketConfig = "";
            if (isset($this->configUi[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->configUi[$jenisTr]['selectorSocket'];
            }
            $socketParams = array();
            $socketURL    = array();

            if (sizeof($modelSrcFilter) > 0) {
                makeFilter($modelSrcFilter, $_SESSION['login'], $b);
            }

            $prodIds = array();
            if ($selectorModel == 'MdlProduk') {
                foreach ($tmpO as $prodItems) {
                    $prodIds[] = isset($prodItems->id) ? $prodItems->id : (isset($prodItems->produk_id) ? $prodItems->produk_id : null);
                }
            }

//            cekMerah($selectorModel);
            switch ($selectorModel) {
                case "MdlProduk":
                case "MdlProduk2":
                case "MdlNotaItem":
                    // pakai filter lama yang sama untuk $b
                    $__apply_legacy_filter($b, $modelFilter, isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array());
                    break;
                case "MdlTransaksi2":
                    $__apply_legacy_filter($b, $modelFilter, isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array());
                    break;
            }

            if (strlen($key) < 3) {
                $this->db->limit(20); //dimatikan karena tidak bisa select all produk
            }
            $dataSrc = $b->lookupAll()->result();

            $tmpP = array();
            foreach ($dataSrc as $srcItems) {
                $main_key = isset($selectorSrcParamFields['id']) ? $selectorSrcParamFields['id'] : "id";
                $tmpP[$srcItems->$main_key] = $srcItems;
            }

            $colors = array("#000000","#0056cd","#ff7700","#009900","#9999cc");
//arrPrint($tmpO);
            foreach ($tmpO as $row) {
                $tmp = array();

                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                $pID    = isset($row->produk_id) ? $row->produk_id : (isset($row->id) ? $row->id : null);
                $defaultValue = (isset($pID) && isset($tmpP[$pID]->moq)) ? $tmpP[$pID]->moq : $selectorDefaultMinValue;

                foreach ($selectorParamFields as $keyField => $srcField) {
                    $tmp[$keyField] = (isset($row->$srcField) && $row->$srcField != "") ? $row->$srcField : "$keyField - null ";
                }

                if (sizeof($pairMakers) > 0) {
                    foreach ($pairMakers as $iix => $iixSpec) {
                        $this->load->helper("Pairs/" . $iixSpec["helperName"]);
                        $hasil = $iixSpec["functionName"]($this->jenisTr, 1, $iixSpec["params"], 0);
                        if (sizeof($hasil) > 0) {
                            foreach ($hasil as $h_key => $h_val) {
                                $tmp[$h_key] = $h_val;
                            }
                        }
                    }
                }

                $tmp['minValue'] = $defaultValue;

                switch ($selectorModel) {
                    case "MdlProduk":
                    case "MdlProduk2":
                        if (isset($tmpP[$pID]->jumlah) && $tmpP[$pID]->jumlah > 0) {
                            $tmp['target'] = $processor;
                            $tmp['bg']     = "text-red";
                        } else {
                            $tmp['target'] = $processor;
                            $tmp['bg']     = "";
                        }
                        break;
                    default:
                        $tmp['target'] = $processor;
                        break;
                }

                $tmp['target']      = $processor . "/" . $row->_selector_model;
                $tmp['tab']         = "" . $row->_selector_tab;
                $tmp['tabLabel']    = "" . $row->_selector_label;
                $tmp['label']       = "";
                $tmp['link_mutasi'] = "";

                if (sizeof($selectorFields) > 0) {
                    $nCtr = 0;
                    foreach ($selectorFields as $f) {
                        $nCtr++;
                        $align = $nCtr == 1 ? "text-left" : "text-right";
                        $fSize = "font-size:1em";
                        $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                        if ($f != 'kode' && isset($row->$f) && is_numeric($row->$f)) {
                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                        }
                        else {
                            $val = isset($row->$f) ? $row->$f : '';
                            $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $val) : $val;
                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
                }

                if (sizeof($pairSaldo) > 0) {
                    foreach ($pairSaldo as $s_key => $s_val) {
                        if ($s_key == (isset($row->coa_code) ? $row->coa_code : null)) {
                            $align = "text-left";
                            $fSize = "font-size:1em";
                            $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                            if ($s_val != 'kode' && isset($tmp[$s_val]) && is_numeric($tmp[$s_val])) {
                                $saldo = $tmp[$s_val];
                                if ($saldo > 0) {
                                    $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'> @ " . number_format($saldo) . "</span>";
                                }
                                else {
                                    $tmp['label'] .= "<br><span style='$fSize ;margin:0px 2px 0px 2px;color:#717271;' cclass='no-padding no-border $align'>";
                                    $tmp['label'] .= "<span class='fa fa-exclamation-triangle' style='color:red;font-style:italic;'> saldo kosong/habis, tidak ada yang perlu dikembalikan.</span>";
                                    $tmp['label'] .= "</span>";
                                    $tmp['target'] = "";
                                }
                            }

                            if (isset($pairSaldoMutasi[$row->coa_code])) {
                                $pihakID   = $_SESSION[$cCode]["main"]["pihakID"];
                                $pihakName = $_SESSION[$cCode]["main"]["pihakName"];
                                $ket_label = isset($row->nama) ? $row->nama : '';
                                $rek_nama  = fetchAccountStructureAlias()[$row->coa_code];
                                $text      = $rek_nama . " " . $pihakName;
                                $blob_ext  = blobEncode($text);
                                $link_mutasi = base_url() . $pairSaldoMutasi[$row->coa_code] . "/$pihakID/" . $row->coa_code . "?o=" . my_cabang_id() . "&main_ext2_id=$pihakID&blob_ext=$blob_ext";
                                $tmp['link_mutasi'] = "<a href='$link_mutasi' class='btn btn-sm' data-toggle='tooltip' title='Mutasi $ket_label $pihakName' target='_blank'><span class='text-muted fa fa-clock-o'></span></a>";
                            }
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
                }

                $addParams = array("cCode" => $cCode);
                $tmpIdForSocket = isset($tmp['id']) ? $tmp['id'] : (isset($tmp['transaksi_id']) ? $tmp['transaksi_id'] : (isset($tmp['project_id']) ? $tmp['project_id'] : 'id'));

                $socketURL[$tmpIdForSocket]   = isset($this->configUi[$jenisTr]['selectorSocket']) ? base_url() . $this->configUi[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
                $socketParams[$tmpIdForSocket] = isset($socketConfig['inParams']) ? $socketConfig['inParams'] : array();

                if (isset($socketParams[$tmpIdForSocket]) && sizeof($socketParams[$tmpIdForSocket]) > 0) {
                    foreach ($socketParams[$tmpIdForSocket] as $kk => $src) {
                        $socketURL[$tmpIdForSocket] .= "&$kk={" . $src . "}";
                    }
                    if (sizeof($addParams) > 0) {
                        foreach ($addParams as $kk => $vv) {
                            $socketURL[$tmpIdForSocket] .= "&$kk=$vv";
                        }
                    }
                }

                if ($preLocker) {
                    $stokLocker = $pl->cekLoker($cID, $pID, "active", "", "", $gID);
                    $valLocker  = isset($stokLocker['jumlah']) ? $stokLocker['jumlah'] : 0;
                    if ($valLocker > 0) {
                        $items[] = $tmp;
                    }
                }
                else {
                    $items[] = $tmp;

                    if ($selectorModel == 'MdlProduk') {
                        if (!isset($arrKosong)) $arrKosong = array();
                        if (!isset($arrReady))  $arrReady  = array();
                        if (isset($tmp['jumlah']) && $tmp['jumlah'] == 0) {
                            $arrKosong[] = $tmp;
                        }
                        else {
                            $arrReady[]  = $tmp;
                        }
                        $items = array_merge(sizeof($arrReady) > 0 ? $arrReady : array(), sizeof($arrKosong) > 0 ? $arrKosong : array());
                    }
                }
            }
        }
        else {
            // tidak ada data
        }

        $data = array(
            "mode"              => "viewUm",
            "selectorNaming"    => $selectorNaming,
            "cCode"             => "$cCode",
            "items"             => $items,
            "socketParams"      => isset($socketParams) ? $socketParams : array(),
            "socketURL"         => isset($socketURL) ? $socketURL : array(),
        );

        $this->load->view($selectorView, $data);
    }

}