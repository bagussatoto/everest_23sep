<?php

require_once "Modul_Controller.php";

class _processRecordDtp extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        /**
         * handling untuk split gerbang item7 sum keprluan insentif pajak
         */

        $ppn_dtp = isset($_GET['ppn_dtp']) ? $_GET['ppn_dtp'] : 0;
        $rate = isset($_GET['rate']) ? $_GET['rate'] : ($ppn_dtp == 1 ? 100 : 0);
        
        $factor = $rate / 100;
        $cCode = $this->cCode;
        
        if (isset($_SESSION[$cCode]['main'])) {
            // Store the factor (e.g. 0.5 for 50%) which is used by coTransaksiCore multiplication
            $_SESSION[$cCode]['main']['ppn_dpt'] = $rate;
            $_SESSION[$cCode]['main']['pajak_ditanggung_pemerintah'] = $factor;
            $_SESSION[$cCode]['main']['dtp_active'] = ($ppn_dtp == 1) ? 1 : 0;
        }

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
            "stepNumber" => isset($_SESSION[$cCode]['main']['stepNumber']) ? $_SESSION[$cCode]['main']['stepNumber'] : 1,
            "stepCode" => isset($this->configUiJenis['steps'][$_SESSION[$cCode]['main']['stepNumber']]['target']) ? $this->configUiJenis['steps'][$_SESSION[$cCode]['main']['stepNumber']]['target'] : $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $vg->buildValue($this->jenisTr, 0, $initMasterValues, $this->modul);
        
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0&reloadDtp=1');";
        echo "  }";
        echo "</script>";
    }
}
