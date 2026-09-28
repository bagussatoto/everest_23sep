<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _selectorPihak extends CI_Controller
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
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
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
        //----------------------------------


        $this->db->limit(20);

        $tmpO = $o->lookupByKeyword($key)->result();
//        cekHitam($this->db->last_query());
//        arrPrint($tmpO);

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakProcessor'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModel'];
        $pihakView = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakView']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakView'] : "";
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
        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);
        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters2']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters2'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
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

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakProcessor2'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModel2'];
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

        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;

//        $mdlName = $this->uri->segment(5);
        if (isset($_SESSION[$cCode]['main']['pihak2Com'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }

        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters3']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters3'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
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

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakProcessor3'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModel3'];
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

        $cCode = "_TR_" . $jenisTr;
//arrPrint($this->uri->segment_array());
        $mdlName = $this->uri->segment(5);
//        if (isset($_SESSION[$cCode]['main']['pihakExternID'])) {
//            $mdlName = $_SESSION[$cCode]['main']['pihakExternID'];
//        }
//        else {
//            $mdlName = $this->uri->segment(5);
//        }

        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakExternFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakExternFilters'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakExternName",
            "nama" => "pihakExternName",
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

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakExternProcessor'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModelExtern'];
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

    //------------------------------
    public function selectPihak3UM()
    {

        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;

        $mdlName = $this->uri->segment(5);
//        if (isset($_SESSION[$cCode]['main']['pihak3Com'])) {
//            $mdlName = $_SESSION[$cCode]['main']['pihak3Mdl'];
//        }
//        else {
//            $mdlName = $this->uri->segment(5);
//        }

        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters3']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters3'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakName",
            "nama" => "pihakName",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->config->item("heTransaksi_ui")[$jenisTr][$valueCek]) && $this->config->item("heTransaksi_ui")[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }

        $items = array();
//mati_disini($mdlName);
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

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakProcessor3'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModel3'];
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
}