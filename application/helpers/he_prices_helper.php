<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 4/11/2019
 * Time: 8:14 PM
 */

function normalizePrices($priceType, $nilais)
{

    $ci =& get_instance();
    $ci->load->config("hePrices");
    $ci->load->helper("he_angka");
    $priceConfig = isset($ci->config->item("hePrices")[$priceType]) ? $ci->config->item("hePrices")[$priceType] : array();


    if (sizeof($priceConfig)) {
        foreach ($priceConfig as $lblID => $spec) {
            switch ($spec['srcType']) {
                case "percentage":
                    $srcKey = isset($spec['srcSrc']) ? $spec['srcSrc'] : "";
                    $nilais[$lblID] = isset($nilais[$srcKey]) ? ($nilais[$lblID] * $nilais[$srcKey] / 100) : 0;
                    break;
                case "normal":
                    if(isset($nilais[$lblID])){
                        // $nilais[$lblID] = pembulatanDiskon($nilais[$lblID]);//untuk buang koma sehingga perhitungan bulat lihat di he_angka_helper
                        // arrPrintHijau($nilais[$lblID]);
                        // matiHere(__LINE__);
                        $nilais[$lblID] = $nilais[$lblID];
                    }
                    break;
            }
        }

    }
    return $nilais;

}