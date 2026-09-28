<?php

function cekPairProduksiPreBiayaDepresiasi($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $configCore = loadConfigModulJenis_he_misc($tr, "coTransaksiCore");
    $source = isset($configUi['pairMakers'][$stepNumber]['preBiaya']['source']) ? $configUi['pairMakers'][$stepNumber]['preBiaya']['source'] : null;
    $key = isset($configUi['pairMakers'][$stepNumber]['preBiaya']['key']) ? $configUi['pairMakers'][$stepNumber]['preBiaya']['key'] : "nama";
    $additionalItemCostBuilders = isset($configCore['additionalItemCostBuilders']) ? $configCore['additionalItemCostBuilders'] : array();
    $result = array();
    $exception = false;

    switch ($tr) {
        case "462":
            $cabangID = $_SESSION[$cCode]['main']['branchTarget'];
            $exception = true;
            break;
        default:
            $cabangID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : $_SESSION[$cCode]['main']['placeID'];
            break;
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlBiayaProduksi_prebiaya");
    $ci->load->model("Mdls/MdlProdukRakitanPreBiaya");
    $ci->load->model("Mdls/MdlCabang");

    $c = New MdlCabang();
    $c->addFilter("id='" . $cabangID . "'");
    $tmp = $c->lookupAll()->result();

    $production = false;
    if (sizeof($tmp) > 0) {
        $production = (isset($tmp[0]->tipe) && $tmp[0]->tipe == "produksi") ? true : false;
    }
    if ($production == true) {

        $n = New MdlProdukRakitanPreBiaya();
        $tmp = $n->lookupAll()->result();

        $preBiayaDef = array();
        $preBiayaDef_flip = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $nSpec) {
                $preBiayaDef[$nSpec->id] = $nSpec->nama;
            }
            $preBiayaDef_flip = array_flip($preBiayaDef);
        }


        if ($source != null) {
            if (isset($_SESSION[$cCode][$source]) && sizeof($_SESSION[$cCode][$source]) > 0) {
                foreach ($_SESSION[$cCode][$source] as $pID => $sSpec) {

                    // additional items cost builder
                    if (sizeof($additionalItemCostBuilders) > 0) {
                        foreach ($additionalItemCostBuilders as $kCost => $vCost) {
                            $rCost = makeValue($vCost, $sSpec, $sSpec, 0);
                            $_SESSION[$cCode][$source][$pID][$kCost] = $rCost;
                        }
                    }

                    foreach ($sSpec as $kSrc => $vSrc) {
                        if (is_numeric($vSrc)) {

                            $kSrc_new = $kSrc . "_rev";
                            $_SESSION[$cCode][$source][$pID][$kSrc_new] = $vSrc;

                            $_SESSION[$cCode][$source][$pID]['costID'] = $preBiayaDef_flip[$sSpec['rekName_2_child']];
                            $_SESSION[$cCode][$source][$pID]['costName'] = $_SESSION[$cCode][$source][$pID]['rekName_2_child'];
                        }
                    }
                }

            }

            // gerbang MAIN yang numeric juga di buat rev-nya
            $sourceMain = "main";
            if (isset($_SESSION[$cCode][$sourceMain])) {
                foreach ($_SESSION[$cCode][$sourceMain] as $kMain => $vMain) {
                    if (is_numeric($vMain)) {
                        $kMain_new = $kMain . "_rev";
                        $_SESSION[$cCode][$sourceMain][$kMain_new] = $vMain;

                        $_SESSION[$cCode][$sourceMain]['costID'] = $preBiayaDef_flip[$_SESSION[$cCode][$sourceMain]['rekName_2']];
                        $_SESSION[$cCode][$sourceMain]['costName'] = $_SESSION[$cCode][$sourceMain]['rekName_2'];
                    }
                }
            }

        }
    }
    else {

    }
//
//    arrPrint($preBiayaDef_flip);
//    arrPrint($_SESSION[$cCode][$source]);
//    mati_disini();


    return $result;
}