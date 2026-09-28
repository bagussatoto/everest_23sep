<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
class _processPihakMainRules extends CI_Controller
{

    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function select()
    {

        $pihakMainValueSrc = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainValueSrcRules']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainValueSrcRules'] : array();
        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["pihakMainNota"]) && $this->config->item("heTransaksi_ui")[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        if (sizeof($tmpB) > 0) {
            if ($id == "pm") {
                $ppnFactor = 10;
            }
            else {
                $ppnFactor = 0;
            }

            unset($_SESSION[$cCode]['items']);

            $_SESSION[$cCode]['main']['pihakMainRulesID'] = $id;
            $_SESSION[$cCode]['main']['ppn_persen_dipakai'] = $ppnFactor;
            $_SESSION[$cCode]['main']['pihakMainRulesName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";

//            $_SESSION[$cCode]['main']['pihakName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn,$tmpB[0]->$selectColumn) : "";
//            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
//            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

//            matiHere($selectColumn);
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }

//            $_SESSION[$cCode]['main']['pihakName'] = $tmpPihakName;


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }
            arrPrint($_SESSION[$cCode]);
            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            echo "top.document.getElementById('pihakRules').value='" . $tmpPihakName . "';";
//            echo "top.document.getElementById('pihakRules').value='asik';";
            echo "top.document.getElementById('pilihan_rules').innerHTML='';";
//            echo "top.document.getElementById('pilihan_main').style.display='none';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakMainRulesID'] = $id;
            $_SESSION[$cCode]['main']['pihakRulesID'] = "";
//            $_SESSION[$cCode]['main']['pihakName2'] = "";
//            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
//            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            echo "top.document.getElementById('pihakRulesID').value='" . $_SESSION[$cCode]['main']['pihakMainRulesName'] . "';";
            echo "top.document.getElementById('pilihan_rules').innerHTML='';";
            echo "</script>";
        }
    }

    public function remove()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $_SESSION[$cCode]['main']['pihakMainRulesID'] = null;
        $_SESSION[$cCode]['main']['pihakMainRulesName'] = null;
        $_SESSION[$cCode]['main']['pihakMainNameRules'] = null;

//        $_SESSION[$cCode]['main']['pihakMdlName'] = null;
        unset($_SESSION[$cCode]['main']['pihakMainRulesID']);
        unset($_SESSION[$cCode]['main']['pihakMainRulesName']);
        unset($_SESSION[$cCode]['main']['pihakMainNameRules']);
        unset($_SESSION[$cCode]['items']);

//        $_SESSION[$cCode]['out_master']['pihakID'] = null;
//        $_SESSION[$cCode]['out_master']['pihakName'] = null;
//        unset($_SESSION[$cCode]['out_master']['pihakID']);
//        unset($_SESSION[$cCode]['out_master']['pihakName']);
    }
}