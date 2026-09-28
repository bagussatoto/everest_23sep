<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _selectorItemPendapatan extends CI_Controller
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
        $mdlName = $this->uri->segment(5);


        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $modelFilter = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters'] : array();
        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields'] : array();
        $selectorParamFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields'] : array();

        $selectorModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel'] : "MdlProduk";
        $selectorSrcModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel'] : "MdlProduk";
        $selectorView = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorView']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorView'] : "_selector";
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

//        $o->createSmartSearch($key,$o->getListedFieldsSelectItem());
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();

//        cekbiru($this->db->last_query());

        if (sizeof($tmpO) > 0) {
            $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorProcessor'] . "/$jenisTr";

            if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'];
            }
            $socketParams = array();
            $socketURL = array();

//            arrprint($tmpO);

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
//                cekkuning($this->db->last_query());
                $defaultValue = isset($tmpP[0]->moq) ? $tmpP[0]->moq : 0;
                foreach ($selectorParamFields as $key => $src) {
//                    cekHitam($key);
                    $tmp[$key] = $row->$src;
                }
                $tmp['minValue'] = $defaultValue;
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                if (sizeof($selectorFields) > 0) {
                    $nCtr = 0;
                    foreach ($selectorFields as $f) {
                        $nCtr++;
                        $align = $nCtr == 1 ? "text-left" : "text-right";
                        $fSize = $nCtr == 1 ? "font-size:0.9em" : "font-size:0.7em";
                        $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";
                        if (is_numeric($row->$f)) {
//                            $tmp['label'] .= "" . number_format($row->$f) . " | ";
                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                        }
                        else {
//                            $tmp['label'] .= "" . $row->$f . " | ";
                            $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
//                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $row->$f . "</span>";
                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
//                    $tmp['label'] = "<div class='no-padding'>". $tmp['label'] . "</div> " . $row->jumlah ;
//                    $tmp['label'] = "<div style='font-size:0.8em' class='no-padding'>" . ($tmp['label']) . "</div> ";
//                    $tmp['label'] = "<div styles='font-size:0.8em' class='no-padding'>" . ($tmp['label']) . "</div> ";
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

//
//        arrprint($items);
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