<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _selectorPihakMainRules extends CI_Controller
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
//        cekHitam();
        $jenisTr = $this->uri->segment(4);

        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);
        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $pihakFilters = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakFilters'] : array();
        $pihakAddStaticEntry = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pihakAddStaticEntry'] : array();
        $key = isset($_GET['search']) ? $_GET['search'] : "";

        $arrCekKolom = array(
            "nomer" => "pihakMainNota",
            "name" => "pihakNameMainRules",
            "nama" => "pihakNameMainRules",
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCek) {
            if (isset($this->config->item("heTransaksi_ui")[$jenisTr][$valueCek]) && $this->config->item("heTransaksi_ui")[$jenisTr][$valueCek] == true) {
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

        $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakMainProcessorRules'] . "/" . "$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]['pihakModelMainRules'];
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