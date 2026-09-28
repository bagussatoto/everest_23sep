<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
class _processPihakMain extends CI_Controller
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

        $shoppingCartSessionDeleter = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSessionDeleter']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartSessionDeleter'] : array();
        $pihakMainValueSrc = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainValueSrc2']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainValueSrc2'] : array();
        $staticAccountComRekening = isset($this->config->item("heTransaksi_core")[$this->jenisTr]['staticAccountComRekening']) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['staticAccountComRekening'] : array();
        $recomsValidate = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainRecoms']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['pihakMainRecoms'] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);


        // region pembersih session items...
        if (sizeof($shoppingCartSessionDeleter) > 0) {
            foreach ($shoppingCartSessionDeleter as $gateName => $gSpec) {
                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                    foreach ($gSpec as $uSpec) {
                        $_SESSION[$cCode][$gateName][$uSpec] = NULL;
                        unset($_SESSION[$cCode][$gateName][$uSpec]);
                    }
                }
                else {
                    if (isset($_SESSION[$cCode][$gateName])) {
                        $_SESSION[$cCode][$gateName] = NULL;
                        unset($_SESSION[$cCode][$gateName]);
                    }
                }
            }
        }
//        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
//            $_SESSION[$cCode]['items'] = NULL;
//            unset($_SESSION[$cCode]['items']);
//
//            $_SESSION[$cCode]['tableIn_detail_values'] = NULL;
//            unset($_SESSION[$cCode]['tableIn_detail_values']);
//
//            $_SESSION[$cCode]['tableIn_detail'] = NULL;
//            unset($_SESSION[$cCode]['tableIn_detail']);
//
//            $arrUnsetMain = array(
//                "seluruhnya",
//                "referenceID",
//                "referenceJenis",
//                "referenceNomer",
//            );
//            foreach ($arrUnsetMain as $uSpec) {
//                $_SESSION[$cCode]['main'][$uSpec] = NULL;
//                unset($_SESSION[$cCode]['main'][$uSpec]);
//            }
//        }

        // endregion pembersih session items...

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
//        arrPrint($tmpB);


            $selectColumn = "nama";
        $arrCekKolomPihak = array(
            "pihakMainNota" => "nomer",
            "pihakMainNotaReference" => "nomer",
        );
        foreach ($arrCekKolomPihak as $keyCek => $valueCek) {
            if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr][$keyCek]) && $this->config->item("heTransaksi_ui")[$this->jenisTr][$keyCek] == true) {
                $selectColumn = $valueCek;
                break;
        }
        }
//        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["pihakMainNota"]) && $this->config->item("heTransaksi_ui")[$this->jenisTr]["pihakMainNota"] == true) {
//            $selectColumn = "nomer";
//        }
//        else {
//            $selectColumn = "nama";
//        }


        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakMainID'] = $id;
            $_SESSION[$cCode]['main']['pihakMainName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";

            $stat = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            if (sizeof($staticAccountComRekening) > 0) {
                $_SESSION[$cCode]['main']['pihakMainAkum'] = $staticAccountComRekening[$stat];
                $_SESSION[$cCode]['main']['pihakMainAkumDetails'] = "akum penyu " . $stat;
            }


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            if (sizeof($recomsValidate) > 0) {
                arrPrint($recomsValidate);
                cekLIme($id);
                $mdlName = $recomsValidate['mdlName'];
                $pihakFilters = $recomsValidate['filters'];
                $selectMethod = isset($recomsValidate['selectMethod'][$id]) ? $recomsValidate['selectMethod'][$id] : false;
//                matiHEre($selectMethod." ".$id);
                $validateField = isset($recomsValidate['usedFields']) ? $recomsValidate['usedFields'] : "";
                $replaceTarget = $recomsValidate['targetField'];
                $this->load->model("Mdls/" . $mdlName);
                $m = new $mdlName();
                if (sizeof($pihakFilters) > 0) {
                    foreach ($pihakFilters as $f) {
                        $f_ex = explode("=", $f);
                        if (!isset($f_ex[1])) {
                            $f_ey = explode(">", $f_ex[0]);
                            if (substr($f_ey[1], 0, 1) == ".") {
                                $m->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                            }
                            else {
                                $m->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                            }
                        }
                        else {
                            if (substr($f_ex[1], 0, 1) == ".") {
                                $m->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                            }
                            else {
//                                        matiHEre("ini".$f_ex[1]);
                                $m->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                            }
                        }
                    }
                }
                $temp2 = $m->lookUpAll()->result();
                if (sizeof($temp2) > 0) {
                    unset($_SESSION[$cCode]['main'][$replaceTarget]);
                    unset($_SESSION[$cCode]['main']["pphGate"]);
                    if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                        foreach ($_SESSION[$cCode]['items'] as $keyID => $items) {
                            unset($_SESSION[$cCode]['items'][$keyID][$replaceTarget]);
                        }
                    }

                    if (strlen($temp2[0]->$validateField) == 0) {
                        if ($selectMethod) {
                            $_SESSION[$cCode]['main'][$replaceTarget] = 4;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph23";
                        }
                        else {
                            $_SESSION[$cCode]['main'][$replaceTarget] = 10;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph4 ayat 2";
                        }
                    }
                    else {
                        if ($selectMethod) {
//                            matiHere($selectMethod);
                            $_SESSION[$cCode]['main'][$replaceTarget] = 2;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph23";
                        }
                        else {
                            $_SESSION[$cCode]['main'][$replaceTarget] = 10;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph4 ayat 2";
                        }
                    }

                }
//                arrPrint($temp2);
//                cekLime($this->db->last_query());

//                $m->addFilter()
//                arrPrint( $_SESSION[$cCode]['main'][$replaceTarget]);
//                matiHere("masuk recoms");
            }
//            mati_disini();


            echo "<script>";
            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            echo "top.document.getElementById('pihakMainName').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_main').innerHTML='';";
            echo "top.document.getElementById('pilihan_item').style.display='none';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakMainID'] = $id;
            $_SESSION[$cCode]['main']['pihakMainName'] = "";
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
            echo "top.document.getElementById('pihakMain').value='" . $_SESSION[$cCode]['main']['pihakMainName'] . "';";
            echo "top.document.getElementById('pilihan_main').innerHTML='';";
            echo "</script>";
        }
    }

    public function remove()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $_SESSION[$cCode]['main']['pihakMainID'] = null;
        $_SESSION[$cCode]['main']['pihakMainName'] = null;
        $_SESSION[$cCode]['main']['pihakMdlName'] = null;
        unset($_SESSION[$cCode]['main']['pihakMainID']);
        unset($_SESSION[$cCode]['main']['pihakMainName']);
        unset($_SESSION[$cCode]['main']['pihakMdlName']);
        unset($_SESSION[$cCode]['items']);

//        $_SESSION[$cCode]['out_master']['pihakID'] = null;
//        $_SESSION[$cCode]['out_master']['pihakName'] = null;
//        unset($_SESSION[$cCode]['out_master']['pihakID']);
//        unset($_SESSION[$cCode]['out_master']['pihakName']);
    }
}