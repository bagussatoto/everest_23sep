<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
//
// echo FCPATH . "<br>";
// echo APPPATH . "<br>";
// $link = "../Modul_Controller";
// echo "<a href='$link'>test</a>";
// $file = APPPATH .'../ModulController.php';
//
// if ((!is_file($file)) or(!is_readable($file)))
//     die("<br>Cannot Include Language Config path:: $file");
// require_once($file);

// require_once (APPPATH ."application/modules/opname/Controllers/Modul_Controller.php");
require_once "Modul_Controller.php";


class _selectorPihak extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();
// cekBiru();

    }

    public function selectPihak()
    {
        //        print_r($_GET);
        // cekOrange(url_segment());
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $mdlName = $this->uri->segment(5);
        // matiHere();
        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakFilters']) ? $this->configUi[$jenisTr]['pihakFilters'] : array();
        $pihakResetor = isset($this->configUi[$jenisTr]['pihakResetor']) ? $this->configUi[$jenisTr]['pihakResetor'] : false;
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();

        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }

        $items = array();
// cekHitam($mdlName);
        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        if ($pihakResetor == true) {
            $o->setFilters(array());
        }
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("in", $f);
                //                arrPrintPink($f_ex);
                //                cekHitam(trim($f_ex[1]));
                if (isset($f_ex[1])) {
                    $o->addFilter($f_ex[0] . " in " . trim($f_ex[1], "'"));
                }
                else {
                    $f_ex = explode("=", $f);
                    if (!isset($f_ex[1])) {
                        $f_ey = explode(">", $f_ex[0]);
                        if (substr($f_ey[1], 0, 1) == ".") {
                            $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                        }
                        else {
                            $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                        }
                    }
                    else {
                        if (substr($f_ex[1], 0, 1) == ".") {
                            $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                        }
                        else {
                            $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                        }
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();

        $processor = base_url() . $this->modul . "/" . $this->configUi[$jenisTr]['pihakProcessor'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModel'];
        $pihakView = isset($this->configUi[$jenisTr]['pihakView']) ? $this->configUi[$jenisTr]['pihakView'] : "";
        $pihakViewAlt = isset($this->configUi[$jenisTr]['pihakViewAlt']) ? $this->configUi[$jenisTr]['pihakViewAlt'] : "";
        //        cekHitam("$pihakView");
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {

                //                $tmpName=isset($row->nama)?$row->nama:"";
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {

                    if (in_array($selectColumn, arrAvailFields())) {

                        $newTmpName = formatNota($selectColumn, $tmpName);
                    }
                    else {
                        $newTmpName = $tmpName;
                    }
                    $items[] = array(
                        "id" => $row->id,
                        //                        "label" => $tmpName,
                        "label" => $newTmpName,
                        "target" => $processor,
                        "label_view" => isset($row->$pihakView) ? $row->$pihakView : "",
                        "label_view_alt" => isset($row->$pihakViewAlt) ? $row->$pihakViewAlt : "",

                    );
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

    public function selectPihak2()
    {
        //        print_r($_GET);
        $jenisTr = $this->jenisTr;

        $cCode = $this->cCode;
        $mdlName = $this->uri->segment(5);
        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakFilters2']) ? $this->configUi[$jenisTr]['pihakFilters2'] : array();
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
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
                        $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();
        //        cekHitam($this->db->last_query());
        //        arrPrint($tmpO);

        $processor = MODUL_PATH . $this->configUi[$jenisTr]['pihakProcessor2'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModel2'];
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {

                //                $tmpName=isset($row->nama)?$row->nama:"";
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {

                    if (in_array($selectColumn, arrAvailFields())) {

                        $newTmpName = formatNota($selectColumn, $tmpName);
                    }
                    else {
                        $newTmpName = $tmpName;
                    }
                    $items[] = array(
                        "id" => $row->id,
                        //                        "label" => $tmpName,
                        "label" => $newTmpName,
                        "target" => $processor,
                    );
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

    public function selectPihak3()
    {

        $jenisTr = $this->jenisTr;

        $cCode = $this->cCode;

        //        $mdlName = $this->uri->segment(5);
        if (isset($_SESSION[$cCode]['main']['pihak2Com'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }

        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakFilters3']) ? $this->configUi[$jenisTr]['pihakFilters3'] : array();
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }

        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
//        $o->setFilters(array());
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();
//        showLast_query("biru");
        $processor = MODUL_PATH . $this->configUi[$jenisTr]['pihakProcessor3'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModel3'];
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {

                //                $tmpName=isset($row->nama)?$row->nama:"";
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {

                    if (in_array($selectColumn, arrAvailFields())) {

                        $newTmpName = formatNota($selectColumn, $tmpName);
                    }
                    else {
                        $newTmpName = $tmpName;
                    }
                    $items[] = array(
                        "id" => $row->id,
                        //                        "label" => $tmpName,
                        "label" => $newTmpName,
                        "target" => $processor,
                    );
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

    public function selectPihakExtern()
    {

        $jenisTr = $this->uri->segment(4);

        $cCode = $this->cCode;
        //arrPrint($this->uri->segment_array());
        $mdlName = $this->uri->segment(5);
        //        if (isset($_SESSION[$cCode]['main']['pihakExternID'])) {
        //            $mdlName = $_SESSION[$cCode]['main']['pihakExternID'];
        //        }
        //        else {
        //            $mdlName = $this->uri->segment(5);
        //        }

        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakExternFilters']) ? $this->configUi[$jenisTr]['pihakExternFilters'] : array();
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakExternName",
            "nama" => "pihakExternName",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }

        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        $o->setFilters(array());
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();
        //        cekHitam($mdlName . "<br>" . $this->db->last_query());
        //        arrPrint($tmpO);

        $processor = base_url() . $this->configUi[$jenisTr]['pihakExternProcessor'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModelExtern'];
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {

                //                $tmpName=isset($row->nama)?$row->nama:"";
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {

                    if (in_array($selectColumn, arrAvailFields())) {

                        $newTmpName = formatNota($selectColumn, $tmpName);
                    }
                    else {
                        $newTmpName = $tmpName;
                    }
                    $items[] = array(
                        "id" => $row->id,
                        //                        "label" => $tmpName,
                        "label" => $newTmpName,
                        "target" => $processor,
                    );
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

    public function selectPihakProjek()
    {

        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);

        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakFilters']) ? $this->configUi[$jenisTr]['pihakFilters'] : array();
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();
        $pihakProjekViewedFields = isset($this->configUi[$jenisTr]['pihakViewedFields']) ? $this->configUi[$jenisTr]['pihakViewedFields'] : array();
//arrPrintWebs($pihakProjekViewedFields);
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => array(
                "pihakMainNota",
                "pihakExternNota"
            ),
            "name" => array("pihakExternName"),
            "nama" => array("pihakExternName"),
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCeks) {
            foreach ($valueCeks as $valueCek) {

                if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                    $selectColumn = $keyCek;
                    break;
                }
            }
        }

        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        $o->setFilters(array());
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
                        $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
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

        $processor = base_url() . $this->modul . "/" . $this->configUi[$jenisTr]['pihakProcessor'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModel'];
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {
                    if (in_array($selectColumn, arrAvailFields())) {
                        $newTmpName = formatNota($selectColumn, $tmpName);
                    }
                    else {
                        $newTmpName = $tmpName;
                    }
                    if (isset($pihakExternViewedFields) && (sizeof($pihakExternViewedFields) > 0)) {
                        foreach ($pihakExternViewedFields as $field) {
                            $newTmpName .= " (" . $row->$field . ")";
                        }
                    }
                    if (isset($pihakProjekViewedFields) && (sizeof($pihakProjekViewedFields) > 0)) {
                        foreach ($pihakProjekViewedFields as $field) {
                            $newTmpName .= " " . $row->$field . " ";
                        }
                    }
                    $items[] = array(
                        "id" => $row->id,
                        "label" => $newTmpName,
                        "target" => $processor,
                    );
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
//arrPrintPink($items);
        $data = array(
            "mode" => "view",
            "items" => $items,
        );
        $this->load->view("_selectorPihak", $data);
    }

    public function selectPihakWoProjek()
    {

        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);

        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakWoProjekFilters']) ? $this->configUi[$jenisTr]['pihakWoProjekFilters'] : array();
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();
        $pihakWoProjekViewedFields = isset($this->configUi[$jenisTr]['pihakWoProjekViewedFields']) ? $this->configUi[$jenisTr]['pihakWoProjekViewedFields'] : array();
//arrPrintWebs($pihakProjekViewedFields);
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => array(
                "pihakMainNota",
                "pihakExternNota"
            ),
            "name" => array("pihakExternName"),
            "nama" => array("pihakExternName"),
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCeks) {
            foreach ($valueCeks as $valueCek) {
                if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                    $selectColumn = $keyCek;
                    break;
                }
            }
        }

        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        $o->setFilters(array());
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                    }
                }
            }
        }
        $this->db->limit(30);
        $tmpO = $o->lookupByKeyword($key)->result();

//        cekHere("key: $key");
//        showlast_Query("biru");

        $processor = base_url() . $this->modul . "/" . $this->configUi[$jenisTr]['pihakWoProjekProcessor'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModelWoProjek'];

        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {
                    if (in_array($selectColumn, arrAvailFields())) {
                        $newTmpName = "<span style='font-size: 14px;' class='text-bold text-uppercase'><i class='fa fa-building'></i> &nbsp; " . formatNota($selectColumn, $tmpName) . "</span>";
                    }
                    else {
                        $newTmpName = "<span style='font-size: 14px;' class='text-bold'>" . $tmpName . "</span>";
                    }
                    if (isset($pihakExternViewedFields) && (sizeof($pihakExternViewedFields) > 0)) {
                        foreach ($pihakExternViewedFields as $field) {
                            $newTmpName .= " (" . $row->$field . ")";
                        }
                    }
                    if (isset($pihakWoProjekViewedFields) && (sizeof($pihakWoProjekViewedFields) > 0)) {
                        foreach ($pihakWoProjekViewedFields as $field) {
                            $icon = "";
                            switch ($field) {
                                case "employee_nama":
                                    $icon = "<i class='fa fa-user'></i> &nbsp; ";
                                    break;
                                case "post_biaya_no":
                                    $icon = "<span class='text-bold text-red'>BIAYA SUDAH DIPOSTING, NOMER: </span>";
                                    break;
                            }
                            $newTmpName .= "<div field='$field' class='text-uppercase'>$icon" . $row->$field . "</div>";
                        }
                    }
                    $items[] = array(
                        "id" => $row->id,
                        "post_biaya_no" => $row->post_biaya_no,
                        "label" => $newTmpName,
                        "target" => $processor,
                    );
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


    public function selectPihakFreelancer()
    {
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $mdlName = $this->uri->segment(5);
        $mdlKey = $this->uri->segment(6);
        $idkolom = $_GET["id"];

        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakFilters']) ? $this->configUi[$jenisTr]['pihakFilters'] : array();
        $pihakResetor = isset($this->configUi[$jenisTr]['pihakResetor']) ? $this->configUi[$jenisTr]['pihakResetor'] : false;
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();

        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }

        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        if ($pihakResetor == true) {
            $o->setFilters(array());
        }
        if (sizeof($pihakFilters) > 0) {
            foreach ($pihakFilters as $f) {
                $f_ex = explode("in", $f);
                //                arrPrintPink($f_ex);
                //                cekHitam(trim($f_ex[1]));
                if (isset($f_ex[1])) {
                    $o->addFilter($f_ex[0] . " in " . trim($f_ex[1], "'"));
                }
                else {
                    $f_ex = explode("=", $f);
                    if (!isset($f_ex[1])) {
                        $f_ey = explode(">", $f_ex[0]);
                        if (substr($f_ey[1], 0, 1) == ".") {
                            $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                        }
                        else {
                            $o->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                        }
                    }
                    else {
                        if (substr($f_ex[1], 0, 1) == ".") {
                            $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                        }
                        else {
                            $o->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                        }
                    }
                }
            }
        }
        $this->db->limit(20);
        $tmpO = $o->lookupByKeyword($key)->result();

//        $processor = MODUL_PATH . $this->configUi[$jenisTr]['pihakProcessor'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModel'];
//        $pihakView = isset($this->configUi[$jenisTr]['pihakView']) ? $this->configUi[$jenisTr]['pihakView'] : "";
        $freelancerData = isset($this->configUi[$this->jenisTr]['freelancerData']) ? $this->configUi[$this->jenisTr]['freelancerData'] : array();
        if (sizeof($freelancerData) > 0) {
//            if ($freelancerData["optionFreelancerID"] == $_SESSION[$cCode]["main"]["optionFreelancerID"]) {
            $freelancerShow = true;
            $freelancerHeaders = $freelancerData["headers"];
            $freelancerLabel = $freelancerData["label"];
            $freelancerEditableFields = $freelancerData["editableFields"][$mdlKey]["process"];
//            }
        }
        $processor = MODUL_PATH . $freelancerEditableFields . "/$mdlKey/$idkolom";
        $pihakView = isset($this->configUi[$jenisTr]['pihakView']) ? $this->configUi[$jenisTr]['pihakView'] : "";
        //--- cek gerbang items4_sum freelancer yang sudah dipilih, supaya tidak muncul lagi
        $arrItems4_sum_ids = array();
        if(isset($_SESSION[$cCode]["items4_sum"])){
            foreach ($_SESSION[$cCode]["items4_sum"] as $iii => $iiiSpec){
                if($iiiSpec["id"] != NULL){
                    $arrItems4_sum_ids[$iiiSpec["id"]] = $iiiSpec["id"];
                }
            }
        }

        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {
                if(!in_array($row->id, $arrItems4_sum_ids)){

                    $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";
                    if (isset($row->name)) {
                        $tmpName = $row->$selectColumn;
                    }
                    if (strlen($tmpName) > 1) {
                        if (in_array($selectColumn, arrAvailFields())) {
                            $newTmpName = formatNota($selectColumn, $tmpName);
                        }
                        else {
                            $newTmpName = $tmpName;
                        }
                        unset($freelancerHeaders["id"]);
                        foreach ($freelancerHeaders as $aa => $bb){
                            $newTmpName .= "&nbsp;" . $row->$aa;
                        }
                        $items[] = array(
                            "id" => $row->id,
                            "label" => $newTmpName,
                            "target" => $processor,
                            "label_view" => isset($row->$pihakView) ? $row->$pihakView : "",

                        );
                    }

                }
            }
        }

//        if (sizeof($pihakAddStaticEntry) > 0) {
//            foreach ($pihakAddStaticEntry as $key => $val) {
//                $addStaticEntry[$key] = getDefaultWarehouseID($this->session->login['cabang_id'])[$val];
//                $addStaticEntry["target"] = $processor;
//
//            }
//
//            $items[] = $addStaticEntry;
//            foreach ($items as $iCtr => $iSpec) {
//                if ($this->session->login['gudang_id'] == $iSpec['id']) {
//                    unset($items[$iCtr]);
//                }
//            }
//        }
//        arrPrint($items);
        $data = array(
            "mode" => "view",
            "items" => $items,
        );


        $this->load->view("_selectorPihak", $data);
    }


}


