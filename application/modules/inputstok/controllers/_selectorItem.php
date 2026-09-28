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
        $enter = isset($_GET['enter']) ? $_GET['enter'] : 0;

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
        /* ----------------------------------------------------------------------------------------------------------
         * bila ada main session yg ilang saat clear shoping cart ditambah di inisiasi _shopingCart/reset
         * ----------------------------------------------------------------------------------------------------------*/
        if (strlen($key) < 3) {
            $this->db->limit(20); //dimatikan karena tidak bisa select all produk
        }
//         $o->addFilter("toko_id='$tkID'");
        $tmpO = $o->lookupByKeyword($key)->result();
//        showLast_query("biru");


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
            $jmlProduk = sizeof($tmpO);
            foreach ($tmpO as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $pID = isset($row->produk_id) ? $row->produk_id : $row->id;
//                $pID = isset($row->$selectorParamFields['id']) ? $row->$selectorParamFields['id'] : $row->id;

                if ($jmlProduk == 1 && $enter == 1) {
                    // akan langsung diload ke shoping cart tanpa harus klik (searching menggunakan barcode reader)
                    $link_to_prosess_selector = $processor . "?id=$pID";
                    echo "<script>
                            top.$('#overlay').show();
                            $('#result').load('$link_to_prosess_selector');
                        </script>";
                }

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