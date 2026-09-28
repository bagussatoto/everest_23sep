<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _selectorItem extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function selectItem()
    {

        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $cekES = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : "";
        $cID = isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : $this->session->login['cabang_id'];
        $gID = isset($_SESSION[$cCode]['main']['gudangID']) ? $_SESSION[$cCode]['main']['gudangID'] : $this->session->login['gudang_id'];


        $mdlName = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->uri->segment(5);


        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $modelFilter = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters'] : array();
        $modelFilterCustom = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainFilters'] : array();

        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields'] : array();
        $selectorParamFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields'] : array();

        $selectorMainFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainViewedFields'] : array();
        $selectorMainParamFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainParamFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainParamFields'] : array();

        $selectorModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel'] : "MdlProduk";
        $selectorSrcModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel'] : "MdlProduk";

        $selectorView = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorView']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorView'] : "_selector";
        $key = isset($_GET['search']) ? $_GET['search'] : "";
        $preLocker = isset($this->config->item("heTransaksi_ui")[$jenisTr]['validLocker']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['validLocker'] : false;
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
            $mdlPreLocker = $this->config->item("heTransaksi_ui")[$jenisTr]["lockerCheck"]["mdlName"];
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

            $selectorProcessor = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainProcessor'][$_SESSION[$cCode]['main']['pihakMainName']];
            $processor = base_url() . $selectorProcessor . "/$jenisTr";
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
            $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorProcessor'] . "/$jenisTr";

        }
        $tmpO = $o->lookupByKeyword($key)->result();
//        showLast_query("biru");


        if (sizeof($tmpO) > 0) {
//            $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorProcessor'] . "/$jenisTr";
//            $mainProcessor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorMainProcessor'] . "/$jenisTr";

            $socketConfig = "";
            if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'];
            }

            $socketParams = array();
            $socketURL = array();

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

                $b->addFilter($b->getTableName() . ".id=" . $pID);
                $tmpP = $b->lookupAll($pID)->result();

                $defaultValue = isset($tmpP[0]->moq) ? $tmpP[0]->moq : 1;
                foreach ($selectorParamFields as $key => $src) {
                    $tmp[$key] = $row->$src != "" ? $row->$src : "$key - null ";
                }

                $tmp['minValue'] = $defaultValue;
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                if (sizeof($selectorFields) > 0) {
                    $nCtr = 0;
                    foreach ($selectorFields as $f) {
                        $nCtr++;
                        $align = $nCtr == 1 ? "text-left" : "text-right";
                        $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                        $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";
                        if (is_numeric($row->$f)) {
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
                $socketURL[$tmp['id']] = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']) ? base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
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

                    if ($valLocker > 0) {
                        $items[] = $tmp;
                    }
                }
                else {
                    $items[] = $tmp;
                }

            }
        }
        else {
//            cekhitam("tidak ada data");
        }


        $data = array(
            "mode" => "view",
            "cCode" => "$cCode",
            //            "arrayFields"=>$selectorFields,
            "items" => $items,
            "socketParams" => isset($socketParams) ? $socketParams : array(),
            "socketURL" => isset($socketURL) ? $socketURL : array(),
        );

//arrPrint($data);
//mati_disini();
        $this->load->view("$selectorView", $data);

    }


    public function selectItem2()
    {

        $jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);


        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $modelFilter = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters'] : array();
        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields'] : array();
        $selectorParamFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields'] : array();

        $selectorModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel2']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel2'] : "MdlProduk";
        $selectorSrcModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel2']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel2'] : "MdlProduk";

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
            $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorProcessor2'] . "/$jenisTr";

            if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'];
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
                $socketURL[$tmp['id']] = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']) ? base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
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