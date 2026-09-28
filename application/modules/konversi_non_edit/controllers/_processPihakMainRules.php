<?php

require_once "Modul_Controller.php";
class _processPihakMainRules extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;

    }

    public function select()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrcRules']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrcRules'] : array();
        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if(!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"]==null)){
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        if (sizeof($tmpB) > 0) {
            if ($id == "pm") {
                $ppnFactor = my_ppn_factor();
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
            // arrPrint($_SESSION[$cCode]);
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakRules').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_rules').innerHTML='';";
            echo "  }";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakMainRulesID'] = $id;
            $_SESSION[$cCode]['main']['pihakRulesID'] = "";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakRulesID').value='" . $_SESSION[$cCode]['main']['pihakMainRulesName'] . "';";
            echo "top.document.getElementById('pilihan_rules').innerHTML='';";
            echo "  }";

            // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";

            echo "</script>";
        }
    }

    public function remove()
    {
        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $_SESSION[$cCode]['main']['pihakMainRulesID'] = null;
        $_SESSION[$cCode]['main']['pihakMainRulesName'] = null;
        $_SESSION[$cCode]['main']['pihakMainRulesID_coa'] = null;
        $_SESSION[$cCode]['main']['pihakMainRulesName_coa'] = null;
        $_SESSION[$cCode]['main']['pihakMainNameRules'] = null;

//        $_SESSION[$cCode]['main']['pihakMdlName'] = null;
        unset($_SESSION[$cCode]['main']['pihakMainRulesID']);
        unset($_SESSION[$cCode]['main']['pihakMainRulesName']);
        unset($_SESSION[$cCode]['main']['pihakMainRulesID_coa']);
        unset($_SESSION[$cCode]['main']['pihakMainRulesName_coa']);
        unset($_SESSION[$cCode]['main']['pihakMainNameRules']);
        unset($_SESSION[$cCode]['items']);
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            "ppnFactor"=>my_ppn_factor(),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        // matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
//        $_SESSION[$cCode]['out_master']['pihakID'] = null;
//        $_SESSION[$cCode]['out_master']['pihakName'] = null;
//        unset($_SESSION[$cCode]['out_master']['pihakID']);
//        unset($_SESSION[$cCode]['out_master']['pihakName']);
    }
}