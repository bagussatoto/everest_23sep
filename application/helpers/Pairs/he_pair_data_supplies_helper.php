<?php

function cekPairDataSupplies($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlProduk2");
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $kolom = isset($configUi['pairMakers'][$stepNumber]['dataProduk']['kolom']) ? $configUi['pairMakers'][$stepNumber]['dataProduk']['kolom'] : array();

    $items = isset($_SESSION[$cCode][$gate]) ? $_SESSION[$cCode][$gate] : array();
    $pIDs = array();
    if (sizeof($items) > 0) {
        foreach ($items as $ii => $dspec) {
            foreach($dspec as $spec){
                $pIDs[] = isset($spec['biaya_dasar_id']) ? $spec['biaya_dasar_id'] : $spec['id'];
            }
        }
    }

    if (sizeof($pIDs) > 0) {

        $c = New MdlProduk2();
        $c->addFilter("id in ('" . implode("','", $pIDs) . "')");
        $tmp = $c->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            $result = array();
            foreach ($tmp as $tmpSpec){
                if(sizeof($kolom)>0){
                    foreach ($kolom as $val){
                        $result[$tmpSpec->id][$val] = isset($tmpSpec->$val) ? $tmpSpec->$val : "";
                    }
                }
            }
            foreach ($items as $ii => $spec) {
                if(isset($result[$ii])){
                    foreach ($result[$ii] as $key => $val){
                        if(!isset($spec[$key])){
                            $spec[$key] = $val;
                        }
                    }
                }
                $_SESSION[$cCode][$gate][$ii] = $spec;
            }
        }
    }
//arrPrint($_SESSION[$cCode][$gate]);
//matiHere("cekPairDataSupplies");
    return true;
}