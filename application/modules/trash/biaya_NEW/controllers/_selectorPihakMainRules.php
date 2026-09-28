<?php

require_once "Modul_Controller.php";
class _selectorPihakMainRules extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;

    }

    public function selectPihak()
    {
//        print_r($_GET);
//        cekHitam();
        $jenisTr = $this->jenisTr;

        $cCode = $this->cCode;
        $mdlName = $this->uri->segment(5);
        $fields = $this->configUi[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->configUi[$jenisTr]['pihakFilters']) ? $this->configUi[$jenisTr]['pihakFilters'] : array();
        $pihakAddStaticEntry = isset($this->configUi[$jenisTr]['pihakAddStaticEntry']) ? $this->configUi[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakNameMainRules",
            "nama" => "pihakNameMainRules",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->configUi[$jenisTr][$valueCek]) && $this->configUi[$jenisTr][$valueCek] == true) {
                $selectColumn = $keyCek;
                break;
            }
        }
//        cekHere($mdlName);

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

        $processor = MODUL_PATH . $this->configUi[$jenisTr]['pihakMainProcessorRules'] . "/" . "$jenisTr/" . $this->configUi[$jenisTr]['pihakModelMainRules'];
        if (sizeof($tmpO) > 0) {
            foreach ($tmpO as $row) {

//                $tmpName=isset($row->nama)?$row->nama:"";
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";

                if (isset($row->name)) {
                    $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : $row->name;
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