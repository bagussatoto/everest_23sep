<?php

function cekPairProduksiPreBiayaOpname($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $configCore = loadConfigModulJenis_he_misc($tr, "coTransaksiCore");
    $source = isset($configUi['pairMakers'][$stepNumber]['preBiaya']['source']) ? $configUi['pairMakers'][$stepNumber]['preBiaya']['source'] : null;
    $key = isset($configUi['pairMakers'][$stepNumber]['preBiaya']['sourceKey']) ? $configUi['pairMakers'][$stepNumber]['preBiaya']['sourceKey'] : array();
    $additionalItemCostBuilders = isset($configCore['additionalItemCostBuilders']) ? $configCore['additionalItemCostBuilders'] : array();
    $result = array();
    $exception = false;

    switch ($tr) {
        case "462":
            $cabangID = $_SESSION[$cCode]['main']['branchTarget'];
            $exception = true;
            break;
        default:
            $cabangID = $_SESSION[$cCode]['main']['pihakID'];
            break;
    }

    $ci =& get_instance();
//    $ci->load->model("Mdls/MdlBiayaProduksi_prebiaya");
//    $ci->load->model("Mdls/MdlProdukRakitanPreBiaya");
    $ci->load->model("Mdls/MdlCabang");

    $c = New MdlCabang();
    $c->addFilter("id='" . $cabangID . "'");
    $tmp = $c->lookupAll()->result();

    $production = false;
    if (sizeof($tmp) > 0) {
        $production = (isset($tmp[0]->tipe) && $tmp[0]->tipe == "produksi") ? true : false;
    }

    if ($production == true) {

        $preBiayaCek = array();
        if ($source != null) {
            if (isset($_SESSION[$cCode][$source]) && sizeof($_SESSION[$cCode][$source]) > 0) {
                foreach ($_SESSION[$cCode][$source] as $pID => $sSpec) {

                    if(sizeof($key) > 0){
                        foreach ($key as $val){
                            $_SESSION[$cCode][$source][$pID][$val."_rev"] = $sSpec[$val];
                        }
                    }

                }
            }


        }
    }
    else {
        $_SESSION[$cCode]['main']['pihakMainName_rev'] = $_SESSION[$cCode]['main']['pihakMainName'];
        $_SESSION[$cCode]['main']['nilai_bayar_rev'] = 0;
    }


    return $result;
}