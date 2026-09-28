<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _selectorPihakMain extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function selectPihak()
    {
//        print_r($_GET);
        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);
        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainFilters'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainViewedFields'] : array();
        $configPair = isset($this->config->item("heTransaksi_ui")[$jenisTr]["pihakMainPair"]) ? $this->config->item("heTransaksi_ui")[$jenisTr]["pihakMainPair"] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";
//arrPrint($configPair);
        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakNameMain",
            "nama" => "pihakNameMain",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->config->item("heTransaksi_ui")[$jenisTr][$valueCek]) && $this->config->item("heTransaksi_ui")[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }
//        cekHere($selectColumn);

        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
//                        $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                        $o->addFilter($f_ey[0] . ">'" . $_SESSION['cCode']['main'][$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
//                        $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                        $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();
        $pairedStock = array();
        if (sizeof($tmpO) > 0) {

            if (sizeof($configPair) > 0) {
                $tmpIds = "(";
                foreach ($tmpO as $temp2) {
                    $tmpIds .= "'$temp2->id',";
                }
                $tmpIds = rtrim($tmpIds, ",");
                $tmpIds .= ")";
                $mdlName = $configPair['MdlName'];
                $filters = $configPair['filter'];
                $this->load->model("Mdls/" . $mdlName);
                $p = new $mdlName();
                $p->addfilter("produk_id in $tmpIds");
//                cekHere($tmpIds);
                if (sizeof($filters) > 0) {
                    foreach ($filters as $s) {
                        $s_ex = explode("=", $s);
//                cekLime($f_ex);
                        if (!isset($s_ex[1])) {
                            $s_ey = explode(">", $s_ex[0]);
                            if (substr($s_ey[1], 0, 1) == ".") {
                                $p->addFilter($s_ey[0] . ">'" . ltrim($s_ey[1], ".") . "'");
                            }
                            else {
//                        $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                                $p->addFilter($s_ey[0] . ">'" . $_SESSION['cCode']['main'][$s_ey[1]] . "'");
                            }
                        }
                        else {
                            if (substr($s_ex[1], 0, 1) == ".") {
                                $p->addFilter($s_ex[0] . "='" . ltrim($s_ex[1], ".") . "'");
                            }
                            else {
//                        $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                                $p->addFilter($s_ex[0] . "='" . $_SESSION[$cCode]['main'][$s_ex[1]] . "'");
                            }
                        }
                    }
                }
                $tmpPairData = $p->lookupAll()->result();
                if (sizeof($tmpPairData) > 0) {
                    foreach ($tmpPairData as $tmpPairData0) {
                        $pairedStock[$tmpPairData0->produk_id] = $tmpPairData0->jumlah;
                    }
                }

            }

        }

//        cekHitam($this->db->last_query());
//        arrPrint($tmpIds);

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainProcessor'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModelMain'];
        if (sizeof($tmpO) > 0) {
            if (sizeof($configPair) > 0) {
                foreach ($tmpO as $row) {
                    if (isset($pairedStock[$row->id])) {
                        if (sizeof($selectorFields) > 0) {
//                    cekLime("masuk");
                            $nCtr = 0;
                            $tmpName = "";
                            if (isset($pairedStock[$row->id])) {

                            }
                            foreach ($selectorFields as $f) {
                                $nCtr++;
                                $align = $nCtr == 1 ? "text-left" : "text-right";
                                $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                                $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                                if (is_numeric($row->$f)) {
//                            $tmp['label'] .= "" . number_format($row->$f) . " | ";
                                    $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                                }
                                else {
//                            $tmp['label'] .= "" . $row->$f . " | ";
                                    $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
//                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $row->$f . "</span>";
                                    $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                                }
                            }
                            $tmpName = rtrim($tmpName, "| ");
//                    $tmpName['label'] = ($tmp['label']);

                        }
                        else {
                            $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                            if (isset($row->name)) {
                                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : $row->name;
                            }
                        }
                        if (strlen($tmpName) > 1) {
                            $items[] = array(
                                "id" => $row->id,
                                "label" => $tmpName,
//                        "label" => $newTmpName,
                                "target" => $processor,
                            );
                        }
                    }

                }
            }
            else {
                foreach ($tmpO as $row) {

//                $tmpName=isset($row->nama)?$row->nama:"";
                    if (sizeof($selectorFields) > 0) {
//                    cekLime("masuk");
                        $nCtr = 0;
                        $tmpName = "";
                        if (isset($pairedStock[$row->id])) {

                        }
                        foreach ($selectorFields as $f) {
                            $nCtr++;
                            $align = $nCtr == 1 ? "text-left" : "text-right";
                            $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                            $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                            if (is_numeric($row->$f)) {
                                $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                            }
                            else {
                                $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                                $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                            }
                        }
                        $tmpName = rtrim($tmpName, "| ");
                    }
                    else {
                        $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                        if (isset($row->name)) {
                            $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : $row->name;
                        }
                    }

                    if (strlen($tmpName) > 1) {
//                    if (in_array($selectColumn, arrAvailFields())) {
//
//                        $newTmpName = formatNota($selectColumn, $tmpName);
//                    }
//                    else {
//                        $newTmpName = $tmpName;
//                    }
                        $items[] = array(
                            "id" => $row->id,
                            "label" => $tmpName,
//                        "label" => $newTmpName,
                            "target" => $processor,
                        );
                    }
                }
//                cekHere("MASUK BAWAH");
            }

        }

        if (sizeof($pihakAddStaticEntry) > 0) {
            foreach ($pihakAddStaticEntry as $key => $val) {
                $addStaticEntry[$key] = getDefaultWarehouseID($this->session->login['cabang_id'])[$val];
                $addStaticEntry["target"] = $processor;

            }

            $items[] = $addStaticEntry;
            foreach ($items as $iCtr => $iSpec) {
                if ($this->session->login['gudang_id'] == $iSpec['id']) {
                    unset($items[$iCtr]);
                }
            }
        }

//arrPrint($selectorFields);
//arrPrint($items);
        $data = array(
            "mode" => "view",
            "items" => $items,
        );


        $this->load->view("_selectorPihak", $data);
    }


    public function selectPihakNota()
    {

        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);
        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainFilters'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainViewedFields'] : array();
        $configPair = isset($this->config->item("heTransaksi_ui")[$jenisTr]["pihakMainPair"]) ? $this->config->item("heTransaksi_ui")[$jenisTr]["pihakMainPair"] : array();
        $pairRegistries = isset($this->config->item("heTransaksi_ui")[$jenisTr]["pihakMainPairRegistries"]) ? $this->config->item("heTransaksi_ui")[$jenisTr]["pihakMainPairRegistries"] : array();


        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakNameMain",
            "nama" => "pihakNameMain",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->config->item("heTransaksi_ui")[$jenisTr][$valueCek]) && $this->config->item("heTransaksi_ui")[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }


        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
//                        $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                        $o->addFilter($f_ey[0] . ">'" . $_SESSION['cCode']['main'][$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
//                        $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                        $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();
//        arrPrint($tmpO);


        $pairedStock = array();
        if (sizeof($tmpO) > 0) {
            if (isset($pairRegistries['enabled']) && ($pairRegistries['enabled'] == true)) {
                $regIDs = array();
                foreach ($tmpO as $spec) {
                    $index_reg = blobDecode($spec->indexing_registry);
                    $regIDs[] = $index_reg[$pairRegistries['param']];
                }

                $this->load->model("MdlTransaksi");
                $rg = New MdlTransaksi();
                $rg->setFilters(array());
                $rg->addFilter("id in ('" . implode("','", $regIDs) . "')");
                $rgTmp = $rg->lookupRegistries()->result();

                $regDenied = array();
                foreach ($rgTmp as $rgSpec){
                    $regValue = blobDecode($rgSpec->values);

                    if(isset($pairRegistries['fieldsDenied'])){
                        foreach ($pairRegistries['fieldsDenied'] as $key => $spec){
                            if(array_key_exists($key, $regValue)){

                                if(in_array($regValue[$key], $spec)){
                                    // bila payment method adalah cash in advance maka masuk list untuk unset dari array...
                                    $regDenied[] = $rgSpec->transaksi_id;
                                }
                            }
                        }
                    }
                }
            }


            if (sizeof($configPair) > 0) {
                $tmpIds = "(";
                foreach ($tmpO as $temp2) {
                    $tmpIds .= "'$temp2->id',";
                }
                $tmpIds = rtrim($tmpIds, ",");
                $tmpIds .= ")";
                $mdlName = $configPair['MdlName'];
                $filters = $configPair['filter'];
                $this->load->model("Mdls/" . $mdlName);
                $p = new $mdlName();
                $p->addfilter("produk_id in $tmpIds");

                if (sizeof($filters) > 0) {
                    makeFilter($filters, $_SESSION[$cCode]['main'], $p);
                }
                $tmpPairData = $p->lookupAll()->result();

                if (sizeof($tmpPairData) > 0) {
                    foreach ($tmpPairData as $tmpPairData0) {
//                        $pairedStock[$tmpPairData0->produk_id] = $tmpPairData0->jumlah;
                        $pairedStock[$tmpPairData0->produk_id] = $tmpPairData0->nilai;
                    }
                }
            }
        }

        // unset list cash in advance milik $tmpO
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $ctr => $spec){
                if(in_array($spec->id, $regDenied)){
                    unset($tmpO[$ctr]);
                }
            }
        }

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainProcessor'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModelMain'];
        if (sizeof($tmpO) > 0) {

            if (sizeof($configPair) > 0) {
                foreach ($tmpO as $row) {

                    if (isset($pairedStock[$row->id]) && ($pairedStock[$row->id] > 0.1)) {
                        if (sizeof($selectorFields) > 0) {

                            $nCtr = 0;
                            $tmpName = "";
//                            if (isset($pairedStock[$row->id])) {
//
//                            }
//
                            foreach ($selectorFields as $f) {
                                $nCtr++;
                                $align = $nCtr == 1 ? "text-left" : "text-right";
                                $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                                $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                                if (is_numeric($row->$f)) {
                                $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                            }
                            else {
                                $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                                    $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                                }
                            }
                            $tmpName = rtrim($tmpName, "| ");


                        }
                        else {
                            $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                            if (isset($row->name)) {
                                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : $row->name;
                            }
                        }
                        if (strlen($tmpName) > 1) {
                            $items[] = array(
                                "id" => $row->id,
                                "label" => $tmpName,
//                        "label" => $newTmpName,
                                "target" => $processor,
                            );
                        }
                    }

                }
            }
            else {
                foreach ($tmpO as $row) {
                    if (sizeof($selectorFields) > 0) {
                        $nCtr = 0;
                        $tmpName = "";
                        if (isset($pairedStock[$row->id])) {

                        }
                        foreach ($selectorFields as $f) {
                            $nCtr++;
                            $align = $nCtr == 1 ? "text-left" : "text-right";
                            $fSize = $nCtr == 1 ? "font-size:1em" : "font-size:0.9em";
                            $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";

                            if (is_numeric($row->$f)) {
                                $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                            }
                            else {
                                $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                                $tmpName .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                            }
                        }
                        $tmpName = rtrim($tmpName, "| ");
                    }
                    else {
                        $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                        if (isset($row->name)) {
                            $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : $row->name;
                        }
                    }

                    if (strlen($tmpName) > 1) {
//                    if (in_array($selectColumn, arrAvailFields())) {
//
//                        $newTmpName = formatNota($selectColumn, $tmpName);
//                    }
//                    else {
//                        $newTmpName = $tmpName;
//                    }
                        $items[] = array(
                            "id" => $row->id,
                            "label" => $tmpName,
//                        "label" => $newTmpName,
                            "target" => $processor,
                        );
                    }
                }
            }

        }

        if (sizeof($pihakAddStaticEntry) > 0) {
            foreach ($pihakAddStaticEntry as $key => $val) {
                $addStaticEntry[$key] = getDefaultWarehouseID($this->session->login['cabang_id'])[$val];
                $addStaticEntry["target"] = $processor;

            }

            $items[] = $addStaticEntry;
            foreach ($items as $iCtr => $iSpec) {
                if ($this->session->login['gudang_id'] == $iSpec['id']) {
                    unset($items[$iCtr]);
                }
            }
        }

        $data = array(
            "mode" => "view",
            "items" => $items,
        );


        $this->load->view("_selectorPihak", $data);
    }
}