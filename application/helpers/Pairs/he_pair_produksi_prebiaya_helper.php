<?php

function cekPairProduksiPreBiaya($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $configCore = loadConfigModulJenis_he_misc($tr, "coTransaksiCore");
    // arrPrint($configUi);
    // matiHEre($cCode);
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
            $cabangID = $_SESSION[$cCode]['main']['pihakID'];
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

    if ($source != null) {
        if (isset($_SESSION[$cCode][$source]) && sizeof($_SESSION[$cCode][$source]) > 0) {
            foreach ($_SESSION[$cCode][$source] as $pID => $sSpec) {
                // inisiasi additional items cost builder
                if (sizeof($additionalItemCostBuilders) > 0) {
                    foreach ($additionalItemCostBuilders as $kCost => $vCost) {

                        $_SESSION[$cCode][$source][$pID][$kCost] = 0;
                        $_SESSION[$cCode][$source][$pID]['sub_' . $kCost] = 0;
                    }
                }
            }
        }
    }


    if ($production == true) {

        $m = New MdlBiayaProduksi_prebiaya();
        $tmp = $m->lookupAll()->result();
        $tmpResult = array();
        $tmpResultPreBiaya = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
                /*
                 * ber isi item biaya produksi berdasarkan id dta_biaya_produksi
                 * pembantu level 2
                 */
                $tmpResult[$tmpSpec->pre_biaya_nama][] = $tmpSpec->biayaproduksi_nama;//asli non coa
                /*
                 * biaya pembantu produksi aytau pun efisiensi berisi direct labor,quality, direct cost
                 * pembantu level 1
                 */
                $tmpResultPreBiaya[$tmpSpec->pre_biaya_id] = $tmpSpec->pre_biaya_nama;//asli non coa
                $tmpCoaRslt[$tmpSpec->coa_code][] = $tmpSpec->biayaproduksi_nama;
                $tmpCoaRsltPreBiaya[$tmpSpec->coa_code] = $tmpSpec->pre_biaya_nama;
                $tmpCoa2RsltPreBiaya[$tmpSpec->coa_code_2] = $tmpSpec->pre_biaya_nama;

            }
        }
        // arrPrint($tmpResult);
        // arrPrintWebs($tmpResultPreBiaya);
        // arrPrintWebs($tmpCoaRslt);
        // arrPrintWebs($tmpCoaRsltPreBiaya);
        // matiHere();
        if (sizeof($tmpResult) == 0) {
            die(lgShowAlert("Biaya produksi belum direlasikan dengan product standart cost. Segera hubungi admin.<br>dari menu <span style='color:blue;'>data/601 Product Cost Defines</span> 1"));
        }

        $n = New MdlProdukRakitanPreBiaya();
        $tmp = $n->lookupAll()->result();
        $no = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $nSpec) {
                $no++;
                $preBiayaDef[$no] = $nSpec->nama;
            }
        }


        $preBiayaCek = array();
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

                    foreach ($tmpResult as $preBiaya => $preBiayaSpec) {
                        if (in_array($sSpec[$key], $preBiayaSpec)) {
                            if (array_key_exists($preBiaya, array_flip($preBiayaDef))) {
                                $urut = array_flip($preBiayaDef)[$preBiaya];
                            }
                            else {
                                $urut = 0;
                            }
                            if (array_key_exists($preBiaya, array_flip($tmpResultPreBiaya))) {
                                $preBiayaID = array_flip($tmpResultPreBiaya)[$preBiaya];
                                $preCoaIDBiaya = array_flip($tmpCoaRsltPreBiaya)[$preBiaya];
                                $preCoaIDBiaya2 = array_flip($tmpCoa2RsltPreBiaya)[$preBiaya];
                            }
                            else {
                                $preBiayaID = 0;
                                $preCoaIDBiaya = 0;
                                $preCoaIDBiaya2 = 0;
                            }
                            $result[$pID] = array(
                                "preBiayaID" => $preBiayaID,
                                "preBiayaIDCoa" => $preBiayaID,
                                "urut" => $urut,
                                "value" => $preBiaya,
                            );
                            $_SESSION[$cCode]['main']['costID'] = $preBiayaID;
                            $_SESSION[$cCode]['main']['costName'] = $preBiaya;
                            $_SESSION[$cCode]['main']['costID_coa'] = $preCoaIDBiaya;
                            $_SESSION[$cCode]['main']['costNameCoa'] = $preBiaya;


                            // if($urut==3){
                            //     arrPrint($tmpResultPreBiaya);
                            //     arrPrint(array_flip($tmpResultPreBiaya));
                            //     matiHEre($preBiaya." || ".$preBiayaID);
                            // }
                            $_SESSION[$cCode]['main']['costID_' . $urut] = $preBiayaID;
                            $_SESSION[$cCode]['main']['costName_' . $urut] = $preBiaya;

                            $_SESSION[$cCode]['main']['costIdCoa_' . $urut] = $preCoaIDBiaya;//ini isi coa nya
                            $_SESSION[$cCode]['main']['costNameCoa_' . $urut] = $preBiaya;//ini isi coa label nya
                            $_SESSION[$cCode]['main']['pihakMainName_rev'] = $_SESSION[$cCode]['main']['pihakMainName'];
                            $_SESSION[$cCode]['main']['pihakMainNameCoa_rev'] = $_SESSION[$cCode]['main']['pihakMainNameCoa'];

                            $_SESSION[$cCode]['main']['nilai_bayar_rev'] = $_SESSION[$cCode]['main']['nilai_bayar'];

                            //tambahan gerbang untuk coa ke 2, milik quality/direct labor/delivery cost diluar efisiensi-----
                            $_SESSION[$cCode]['main']['cost2ID_coa'] = $preCoaIDBiaya2;
                            $_SESSION[$cCode]['main']['cost2NameCoa'] = $preBiaya;
                            $_SESSION[$cCode]['main']['cost2IdCoa_' . $urut] = $preCoaIDBiaya2;//ini isi coa nya, bukan pembantu efisiensi
                            $_SESSION[$cCode]['main']['cost2NameCoa_' . $urut] = $preBiaya;//ini isi coa label nya, bukan pembantu efisiensi
                            $_SESSION[$cCode]['main']['pihakMainName2_rev'] = $_SESSION[$cCode]['main']['pihakMainName'];
                            $_SESSION[$cCode]['main']['pihakMainName2Coa_rev'] = $_SESSION[$cCode]['main']['pihakMainNameCoa'];
                            //-----

                            $preBiayaCek[$sSpec[$key]] = $preBiaya;
                        }
//                        else{
//                            cekHere("$preBiaya == " . $sSpec[$key]);
//                            die(lgShowAlert($sSpec[$key] . " belum direlasikan dengan product standart cost. Segera hubungi admin."));
//                        }
                    }
                    if (sizeof($preBiayaCek) > 0) {
//                        cekMerah($sSpec[$key]);
//                        arrPrint($preBiayaCek);
                        if (!array_key_exists($sSpec[$key], $preBiayaCek)) {
                            die(lgShowAlert($sSpec[$key] . "  belum direlasikan dengan product standart cost. Segera hubungi admin.<br>dari menu <span style='color:blue;'>data/601 Product Cost Defines</span> 2"));
                        }
                    }
                }
                if (sizeof($preBiayaCek) > 0) {
                    foreach ($preBiayaCek as $pre_name => $val_name) {
                        if ($val_name == null) {
                            die(lgShowAlert("$pre_name  belum direlasikan dengan product standart cost. Segera hubungi admin.<br>dari menu <span style='color:blue;'>data/601 Product Cost Defines</span> 3"));
                        }
                    }
                }
                else {
                    if ($exception == false) {
                        die(lgShowAlert("[$key] Biaya produksi belum direlasikan dengan product standart cost. Segera hubungi admin.<br>dari menu <span style='color:blue;'>data/601 Product Cost Defines</span> 4"));
                    }
                }
            }


        }
    }
    else {
        // matiHEre("else");
        $_SESSION[$cCode]['main']['pihakMainName_rev'] = $_SESSION[$cCode]['main']['pihakMainName'];
        $_SESSION[$cCode]['main']['pihakMainNameCoa_rev'] = $_SESSION[$cCode]['main']['pihakMainNameCoa'];
        $_SESSION[$cCode]['main']['pihakMainName2_rev'] = $_SESSION[$cCode]['main']['pihakMainName'];
        $_SESSION[$cCode]['main']['pihakMainName2Coa_rev'] = $_SESSION[$cCode]['main']['pihakMainNameCoa'];
        $_SESSION[$cCode]['main']['nilai_bayar_rev'] = 0;
    }

// matiHEre(__LINE__);
    return $result;
}