<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "viewReceipt_OLD":
        //        arrPrint($items);
        if (isset($mainElements)) {
            //            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            if (isset($elementUsedFieldsConfigs) && sizeof($elementUsedFieldsConfigs) > 0) {
                                                if (isset($elementUsedFieldsConfigs[$e]) && $elementUsedFieldsConfigs[$e] != "") {
                                                    $colLabel = $elementUsedFieldsConfigs[$e];
                                                }
                                                else {
                                                    $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                                }
                                            }
                                            else {
                                                $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            }
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }
        $item_src = "";
        if (isset($itemSrc) && sizeof($itemSrc) > 0) {
            //            arrPrint($itemSrc);

            $item_src .= "<div class='table-responsive' style='border:0px solid red;'>";
            $item_src .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            $item_src .= "<tr bgcolor='#f5f5f5'>";
            $item_src .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";

            foreach ($itemSrcLabel as $ky => $srcLabel) {
                $item_src .= "<th class='text-muted' style='font-weight:bold;'>";
                $item_src .= $srcLabel . "";
                $item_src .= "</th>";
            }
            $item_src .= "</tr>";
            $mno = 0;
            foreach ($itemSrc as $itemSrc0) {
                $mno++;
                $item_src .= "<tr line=" . __LINE__ . ">";
                $item_src .= "<td align='right'>";
                $item_src .= $mno;
                $item_src .= "</td>";
                foreach ($itemSrcLabel as $ky => $srclabel) {
                    $val = isset($itemSrc0[$ky]) ? $itemSrc0[$ky] : "";
                    $item_src .= "<td>";
                    $item_src .= formatField($ky, $val);
                    $item_src .= "</td>";
                }
                $item_src .= "</tr>";

            }


            $item_src .= "</table>";
            $item_src .= "</div>";
        }
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    // arrPrint($iSpec);
                    //                     arrPrint($itemLabels);

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();

                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));

                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
                    //                    arrPrint($items[$id]);
                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";

                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";

                            cekMerah($iVal);

                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);

                            cekHijau($string);

                            $string = str_replace("&lt;br&gt;", "<br>", $string);


                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";

                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

                //                 arrPrint($mainValues);
                //                 arrPrint($sumRows);
                //                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
                    //                                       arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {

                            if (sizeof($mainValues[$key]) > 0) {
                                //                                cekHere("$key " . $mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif ($mainValues[$key] < 0) {
                                //                                cekHitam($mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                arrPrint($sumRows2);
                if (isset($sumRows2) && sizeof($sumRows2) > 0) {
                    foreach ($sumRows2 as $key2 => $label2) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key2])) {

                            if (sizeof($mainValues[$key2]) > 0) {
                                //                                cekHere("$key " . $mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key2, $zeroAllowed))) {
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif ($main[$key2] < 0) {
                                //                                cekHitam($mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr line=" . __LINE__ . ">";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr line=" . __LINE__ . ">";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";
            }


            $contentStr6 = "";
            if (isset($dpValueDetilsINV) && sizeof($dpValueDetilsINV) > 0) {
                $contentStr6 .= "<div class='panel-body'>";
                $contentStr6 .= "<table class='table table-responsive'>";
                foreach ($dpFieldNameINV as $dp_fields => $dpFields_alias) {
                    $contentStr6 .= "<tr line=" . __LINE__ . ">";
                    $contentStr6 .= "<td>$dpFields_alias</td>";
                    $contentStr6 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetilsINV[$dp_fields]) . "</td>";
                    $contentStr6 .= "</tr>";
                }
                $contentStr6 .= "</table>";
                $contentStr6 .= "</div>";

                $elementLabels["content_6_display"] = "block";
            }
            else {
                $elementLabels["content_6_display"] = "none";
            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $contenStr5 = "";
            if (isset($mainData2) && sizeof($mainData2) > 0) {

                //                $contenStr5 .= "<div class='panel-body'>";
                $contenStr5 .= "<table class='table table-bordered'>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-centter'>No</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    $contenStr5 .= "<td class='text-centter'>$add_fields</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-center'>1</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    //                    cekHitam($fieldsKey);
                    $contenStr5 .= "<td>" . formatField($fieldsKey, $mainData2[$fieldsKey]) . "</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                if (strlen($inWord2) > 5) {
                    $contenStr5 .= "<tr line=" . __LINE__ . ">";
                    $contenStr5 .= "<td style='vertical-align: bottom;' colspan='" . sizeof($mainData2Fields) . "' rowspan='' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord2</span></td>";
                    $contenStr5 .= "</tr>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "</table>";
                //                $contenStr5 .= "</div>";

            }
            $elementLabels["content_src"] = $item_src;
            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
            $elementLabels["content_5"] = $contenStr5;
            $elementLabels["content_6"] = $contentStr6;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }
        // cekBiru($template);
        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        //arrPrintWebs($arrTags);

        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceipt":

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr>";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
//                                    $elementStr .= $colLabel!="" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>$val</td>" : "<td colspan='3'>$val</td>";
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }
//                                        cekHere($eKey);

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr>";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }

//arrPrint($items);
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    // arrPrint($iSpec);
                    // arrPrint($itemLabels);

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));
                    $contentStr .= "<tr>";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";

                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";

                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";

                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {
                            $contentStr .= "<tr>";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";
                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";
                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);
                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

//                    arrPrint($iSpec);
                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

                // arrPrint($mainValues);
                // arrPrint($sumRows);
//                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr>";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
//                    arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

//                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
//                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {
//                            cekHere("$key " . $mainValues[$key]);
                            if ($mainValues[$key] > 0) {
                                $contentStr .= "<tr>";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr>";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif ($mainValues[$key] < 0) {
//                                cekHitam($mainValues[$key]);
                                $contentStr .= "<tr>";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                        }
//                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr>";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr>";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr>";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr>";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr>";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr>";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
//                    $contentStr3 .="<div class='col-md-1 text-right'>$dpFields_alias</div>";
//                    $contentStr3 .="<div class='col-md-2 font-size-1-2'>".formatField($dp_fields,$dpValueDetils[$dp_fields])."</div>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";

            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }
//arrPrint($mainData2Fields);
            $contenStr5 = "";
            if (isset($mainData2) && sizeof($mainData2) > 0) {

//                $contenStr5 .= "<div class='panel-body'>";
                $contenStr5 .= "<table class='table table-bordered'>";
                $contenStr5 .= "<tr>";
                $contenStr5 .= "<td class='text-centter'>No</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    $contenStr5 .= "<td class='text-centter'>$add_fields</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr>";
                $contenStr5 .= "<td class='text-center'>1</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
//                    cekHitam($fieldsKey);
                    $contenStr5 .= "<td>" . formatField($fieldsKey, $mainData2[$fieldsKey]) . "</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr>";
                if (strlen($inWord2) > 5) {
                    $contenStr5 .= "<tr>";
                    $contenStr5 .= "<td style='vertical-align: bottom;' colspan='" . sizeof($mainData2Fields) . "' rowspan='' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord2</span></td>";
                    $contenStr5 .= "</tr>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "</table>";
//                $contenStr5 .= "</div>";

            }

//            arrPrintKuning($mainValues);
//            arrPrintKuning($receiptEfakturFields);
            $contentStr7 = "";
            if (isset($receiptEfakturFields) && sizeof($receiptEfakturFields) > 0) {
                $no = 0;
                $contentStr7 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr7 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr7 .= "<tr bgcolor='#f5f5f5'>";
                foreach ($receiptEfakturFields as $key => $label) {
                    $contentStr7 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr7 .= $label;
                    $contentStr7 .= "</th>";
                }
                $contentStr7 .= "</tr>";
                $contentStr7 .= "<tr line=" . __LINE__ . ">";
                foreach ($receiptEfakturFields as $key => $label) {
//                    cekHere("$key");
                    $val = isset($mainValues[$key]) ? $mainValues[$key] : "";
                    $contentStr7 .= "<td>";
                    $contentStr7 .= formatField_he_format($key, $val);
                    $contentStr7 .= "</td>";
                }
                $contentStr7 .= "</tr>";
                $contentStr7 .= "</table>";
                $contentStr7 .= "</div>";
            }
//            cekHere($contentStr7);


            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
            $elementLabels["content_5"] = $contenStr5;
            $elementLabels["content_6"] = $contentStr6;
            $elementLabels["content_7"] = $contentStr7;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }


        $p->addTags($arrTags);
        $p->render();

        break;
    case "viewReceiptReg":
        //        arrPrint($items);
        if (isset($mainElements)) {
//                        arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            if (isset($elementUsedFieldsConfigs) && sizeof($elementUsedFieldsConfigs) > 0) {
                                                if (isset($elementUsedFieldsConfigs[$e]) && $elementUsedFieldsConfigs[$e] != "") {
                                                    $colLabel = $elementUsedFieldsConfigs[$e];
                                                }
                                                else {
                                                    $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                                }
                                            }
                                            else {
                                                $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            }
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }

        $item_src = "";
        if (sizeof($itemSrc) > 0) {
            //            arrPrint($itemSrc);

            $item_src .= "<div class='table-responsive' style='border:0px solid red;'>";
            $item_src .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            $item_src .= "<tr bgcolor='#f5f5f5'>";
            $item_src .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";

            foreach ($itemSrcLabel as $ky => $srcLabel) {
                $item_src .= "<th class='text-muted' style='font-weight:bold;'>";
                $item_src .= $srcLabel . "";
                $item_src .= "</th>";
            }
            $item_src .= "</tr>";
            $mno = 0;
            foreach ($itemSrc as $itemSrc0) {
                $mno++;
                $item_src .= "<tr line=" . __LINE__ . ">";
                $item_src .= "<td align='right'>";
                $item_src .= $mno;
                $item_src .= "</td>";
                foreach ($itemSrcLabel as $ky => $srclabel) {
                    $val = isset($itemSrc0[$ky]) ? $itemSrc0[$ky] : "";
                    $item_src .= "<td>";
                    $item_src .= formatField($ky, $val);
                    $item_src .= "</td>";
                }
                $item_src .= "</tr>";

            }


            $item_src .= "</table>";
            $item_src .= "</div>";
        }


        //region view nota yang dipilih
        $viewMaster_item = "";
        if (isset($master_item_label) && (sizeof($master_item_label) > 0) && (sizeof($masterItems) > 0)) {
            $viewMaster_item .= "<div style='margin-top: 0px;margin-bottom: 10; margin-bottom: 20px;'>";
            $viewMaster_item .= "<table class='table table-condensed no-padding table-bordered no-margin'>";
            $viewMaster_item .= "<tr class='bg-grey-2 text-uppercase'>";
            $viewMaster_item .= "<th style='width:1%;' class='text-muted text-center'>";
            $viewMaster_item .= "NO";
            $viewMaster_item .= "</th>";

            foreach ($master_item_label as $mKey => $mLabel) {

                $viewMaster_item .= "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                if (is_array($mLabel)) {
                    $viewMaster_item .= $mLabel["label"];
                }
                else {
                    $viewMaster_item .= $mLabel;
                }
                $viewMaster_item .= "</th>";

            }
            $viewMaster_item .= "</tr>";
            $mxi = 0;
            $viewMaster_item .= "<tr>";
            $viewMaster_item .= "<td style='vertical-align:middle; width:1%' class='text-center'>";
            $viewMaster_item .= "1";
            $viewMaster_item .= "</td>";
            foreach ($masterItems as $km => $val_km) {
                $viewMaster_item .= "<td style='vertical-align:middle; width:1%' class='text-center'>";
                $viewMaster_item .= formatField_he_format($km, $val_km);
                $viewMaster_item .= "</td>";

            }
            $viewMaster_item .= "</tr>";

            $viewMaster_item .= "</table>";
            $viewMaster_item .= "</div>";
            $viewMaster_item .= "<br>";
        }
        //endregion


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            $contentStr .= $viewMaster_item;
            if (isset($items) && sizeof($items) > 0 && sizeof($items5) == 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    // arrPrint($iSpec);
                    //                     arrPrint($itemLabels);

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();

                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));

                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
                    //                    arrPrint($items[$id]);
                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";

                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";

                            cekMerah($iVal);

                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);

                            cekHijau($string);

                            $string = str_replace("&lt;br&gt;", "<br>", $string);


                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";

                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

                //                 arrPrint($mainValues);
                //                 arrPrint($sumRows);
                //                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
                    //                                       arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {

                            if (sizeof($mainValues[$key]) > 0) {
                                //                                cekHere("$key " . $mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif ($mainValues[$key] < 0) {
                                //                                cekHitam($mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }

            // Pastikan variabel opsi_cetak tertangkap (default 1)
            $opsi_cetak = isset($opsi_cetak) ? $opsi_cetak : 4;
            $master_jenis_produk = $metaProduk;
            // =========================================================
            // SIMULASI MASTER DATA PRODUK (Nanti timpa dari Controller)
            // Format: ID_PRODUK_DASAR => 'unit' / 'non unit'
            // =========================================================
            $master_jenis_produk = isset($master_jenis_produk) ? $master_jenis_produk : array();

            //details
            if (isset($items5) && sizeof($items5) > 0) {
                $contentStr .=  "<tr class='bg-info items5'>";
                $contentStr .=  "<td colspan='$jmlKolomHeader'>";
                $contentStr .=  "<div class='panel no-margin'>"; // anakan table
                $contentStr .=  "<table id='items5' class='table dataTable compact table-condensed no-padding table-bordered no-margin table-hover'>";
//                $contentStr .=  "<caption class='text-bold text-red'></caption>";

                // =========================================================
                // PRA-KALKULASI GLOBAL
                // =========================================================
                $rekap_barang = array();
                $grand_total_all = 0;

                // Variabel Khusus Opsi 4
                $global_jasa = 0;
                $global_material = 0;
                $global_unit = 0;
                $merk_unit = array();

                foreach ($items5 as $iSpec) {
                    $grand_total_all += isset($iSpec['harga']) ? (float)$iSpec['harga'] : 0;

                    if ($opsi_cetak == 3 || $opsi_cetak == 4) {
                        // 1. Kumpulkan dari Biaya (JASA)
                        if (isset($iSpec['bahan_baku']['biaya'])) {
                            foreach ($iSpec['bahan_baku']['biaya'] as $prdData) {
                                $nama = strtoupper($prdData['produk_dasar_nama']);
                                $qty = (float)$prdData['jml'];
                                $saldo = (float)$prdData['saldo'];
                                $satuan = isset($prdData['satuan']) && !empty($prdData['satuan']) ? $prdData['satuan'] : 'Unit';

                                if ($opsi_cetak == 3) {
                                    // Kategori 3 untuk Jasa
                                    if(!isset($rekap_barang[$nama])) {
                                        $rekap_barang[$nama] = array('qty' => 0, 'satuan' => $satuan, 'kategori' => 3);
                                    }
                                    $rekap_barang[$nama]['qty'] += $qty;
                                }
                                if ($opsi_cetak == 4) {
                                    $global_jasa += $saldo;
                                }
                            }
                        }

                        // 2. Kumpulkan dari Produk (MATERIAL & UNIT)
                        if (isset($iSpec['bahan_baku']['produk'])) {
                            foreach ($iSpec['bahan_baku']['produk'] as $prdData) {
//                                arrPrint($prdData);
                                $id_dasar = $prdData['produk_dasar_id'];
                                $kodeProduk = isset($master_jenis_produk[$id_dasar]['kode']) && $master_jenis_produk[$id_dasar]['kode'] !== "" ? "<r>" . $master_jenis_produk[$id_dasar]['kode'] . "</r>" : '--';
                                $nama = strtoupper($prdData['produk_dasar_nama']);
                                $qty = (float)$prdData['jml'];
                                $saldo = (float)$prdData['saldo'];
                                $satuan = isset($master_jenis_produk[$id_dasar]['satuan']) && !empty($master_jenis_produk[$id_dasar]['satuan']) ? strtolower($master_jenis_produk[$id_dasar]['satuan']) : 'Unit';

                                // Cek tipe produk ke Master Data
                                $jenis = isset($master_jenis_produk[$id_dasar]['kategori']) ? $master_jenis_produk[$id_dasar]['kategori'] : 'non unit';

                                if ($opsi_cetak == 3) {
                                    // Kategori 1 untuk Unit, 2 untuk Non-Unit
                                    $kategori_num = ($jenis == 'unit') ? 1 : 2;

                                    if(!isset($rekap_barang[$nama])) {
                                        $rekap_barang[$nama] = array('qty' => 0, 'satuan' => $satuan, 'kategori' => $kategori_num, 'kode' => $kodeProduk);
                                    }
                                    $rekap_barang[$nama]['qty'] += $qty;
                                }
                                if ($opsi_cetak == 4) {
                                    if ($jenis == 'unit') {
                                        $global_unit += $saldo;
                                        // Ambil kata pertama untuk merk
                                        $exp = explode(" ", $nama);
                                        $merk = isset($exp[0]) ? $exp[0] : '';
                                        if (!in_array($merk, $merk_unit) && $merk != '') {
                                            $merk_unit[] = $merk;
                                        }
                                    } else {
                                        $global_material += $saldo;
                                    }
                                }
                            }
                        }
                    }
                }

                // =========================================================
                // HEADER TABEL
                // =========================================================
                $contentStr .=  "<thead>";
                $contentStr .=  "<tr>";

                if ($opsi_cetak == 3) {
                    // HEADER OPSI 3: No | JUMLAH | SATUAN | BARANG
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-bold' width='5%'>No</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-bold' width='15%'>JUMLAH</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-bold' width='15%'>SATUAN</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-bold' width='65%'>BARANG</td>";
                }
                elseif ($opsi_cetak == 4) {
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center' width='5%'>No</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='10%'>JUMLAH</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='50%'>BARANG</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='5%'>HARGA SATUAN</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='30%'>JUMLAH</td>";
                }
                elseif ($opsi_cetak == 1) {
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center' width='5%'>No</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize'>Pekerjaan/WO</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='25%'>jumlah</td>";
                }
                elseif ($opsi_cetak == 2) {
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center' width='5%'>No</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='30%'>Pekerjaan/WO</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize'>Barang</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='20%'>jumlah</td>";
                }
                else {
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center' width='5%'>No</td>";
                    if (is_array($itemLabels5)) {
                        foreach ($itemLabels5 as $key => $label) {
                            $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize'>" . $label . "</td>";
                        }
                    }
                }
                $contentStr .=  "</tr>";
                $contentStr .=  "</thead>";

                // =========================================================
                // BODY TABEL
                // =========================================================
                $no = 0;
                $contentStr .=  "<tbody>";

                if ($opsi_cetak == 3) {
                    // URUTKAN ARRAY BERDASARKAN KATEGORI (1: Unit, 2: Non-Unit, 3: Jasa)
                    uasort($rekap_barang, function($a, $b) {
                        if ($a['kategori'] == $b['kategori']) {
                            return 0;
                        }
                        return ($a['kategori'] < $b['kategori']) ? -1 : 1;
                    });

                    $no_rekap = 1;
                    foreach ($rekap_barang as $nama_barang => $data_rek) {
                        $contentStr .= "<tr>";
                        $contentStr .= "<td class='text-center'>{$no_rekap}.</td>";
                        $contentStr .= "<td class='text-center'><b>{$data_rek['qty']}</b></td>";
                        $contentStr .= "<td class='text-center text-capitalize'>{$data_rek['satuan']}</td>";
                        $contentStr .= "<td>{$nama_barang} {$data_rek['kode']}</td>";
                        $contentStr .= "</tr>";
                        $no_rekap++;
                    }
                } elseif ($opsi_cetak == 4) {
                    // BODY OPSI 4
                    $no_rekap = 1;

                    if ($global_jasa > 0) {
                        $contentStr .= "<tr>";
                        $contentStr .= "<td class='text-center'>{$no_rekap}.</td>";
                        $contentStr .= "<td class='text-center text-bold'>1 LOT</td>";
                        $contentStr .= "<td class='text-bold'>JASA PEMASANGAN AC</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_jasa) . "</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_jasa) . "</td>";
                        $contentStr .= "</tr>";
                        $no_rekap++;
                    }
                    if ($global_material > 0) {
                        $contentStr .= "<tr>";
                        $contentStr .= "<td class='text-center'>{$no_rekap}.</td>";
                        $contentStr .= "<td class='text-center text-bold'>1 LOT</td>";
                        $contentStr .= "<td class='text-bold'>MATERIAL dan PERLENGKAPAN</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_material) . "</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_material) . "</td>";
                        $contentStr .= "</tr>";
                        $no_rekap++;
                    }
                    if ($global_unit > 0) {
                        $str_merk = implode(", ", $merk_unit);
                        $title_unit = "UNIT AC" . ($str_merk != '' ? " {$str_merk}" : "");

                        $contentStr .= "<tr>";
                        $contentStr .= "<td class='text-center'>{$no_rekap}.</td>";
                        $contentStr .= "<td class='text-center text-bold'>1 LOT</td>";
                        $contentStr .= "<td class='text-bold'>{$title_unit}</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_unit) . "</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_unit) . "</td>";
                        $contentStr .= "</tr>";
                        $no_rekap++;
                    }

                } else {
                    // OPSI 1 & 2
                    foreach ($items5 as $iSpec) {
                        $no++;
                        $bgColor = "transparent";
                        if (isset($_SESSION['errLines']) && in_array($iSpec['id'], $_SESSION["errLines"])) {
                            $bgColor = "#ffff77";
                        }

                        $contentStr .=  "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";

                        $nama_project = isset($iSpec['produk_nama']) ? $iSpec['produk_nama'] : '';
                        $total_baris = isset($iSpec['harga']) ? (float)$iSpec['harga'] : 0;

                        if ($opsi_cetak == 1) {
                            $contentStr .=  "<td class='text-center valign-m'>$no.</td>";
                            $contentStr .=  "<td class='text-left valign-m text-bold'>{$nama_project}</td>";
                            $contentStr .=  "<td class='text-right valign-m text-bold'>" . number_format($total_baris) . "</td>";

                        } elseif ($opsi_cetak == 2) {
                            $contentStr .=  "<td class='text-center valign-m'>$no.</td>";
                            $contentStr .=  "<td class='text-left valign-m text-bold'>{$nama_project}</td>";

                            $contentStr .= "<td><table width='100%' class='table-condensed no-margin'>";
                            if (isset($iSpec['bahan_baku']['produk'])) {
                                foreach ($iSpec['bahan_baku']['produk'] as $prdData) {
                                    $contentStr .= "<tr class='text-meta text-bold'><td style='border:none; padding:2px;'>- {$prdData['produk_dasar_nama']}</td><td class='text-right' style='border:none; padding:2px;' width='15%'>{$prdData['jml']} x</td></tr>";
                                }
                            }
                            if (isset($iSpec['bahan_baku']['biaya'])) {
                                foreach ($iSpec['bahan_baku']['biaya'] as $prdData) {
                                    $contentStr .= "<tr class='text-meta text-bold'><td style='border:none; padding:2px;'>- {$prdData['produk_dasar_nama']}</td><td class='text-right' style='border:none; padding:2px;' width='15%'>{$prdData['jml']} x</td></tr>";
                                }
                            }
                            $contentStr .= "</table></td>";
                            $contentStr .=  "<td class='text-right valign-m text-bold text-purple'>" . number_format($total_baris) . "</td>";

                        } else {
                            // Fallback
                        }

                        $contentStr .=  "</tr>";
                    }
                }
                $contentStr .=  "</tbody>";

                // =========================================================
                // FOOTER (SUBTOTAL, PPN, GRAND TOTAL)
                // =========================================================
                // Kalkulasi PPN dan Total Akhir
                $ppn_11 = $grand_total_all * 0.11;
                $grand_total_akhir = $grand_total_all + $ppn_11;

                // Tentukan jumlah colspan agar dinamis menyesuaikan opsi cetak
                $colspan_footer = 2; // Default
                if ($opsi_cetak == 1) {
                    $colspan_footer = 2;
                } elseif ($opsi_cetak == 3) {
                    $colspan_footer = 3;
                } elseif ($opsi_cetak == 2) {
                    $colspan_footer = 3;
                } elseif ($opsi_cetak == 4) {
                    $colspan_footer = 4;
                } else {
                    $colspan_footer = is_array($itemLabels5) ? count($itemLabels5) : 3;
                }

                $contentStr .=  "<tfoot>";


                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        if (isset($mainValues[$key])) {
                            if ($mainValues[$key] > 0) {
                                $contentStr .= "<tr>";
                                $contentStr .= "<td colspan='$colspan_footer' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr>";
                                $contentStr .= "<td colspan='$colspan_footer' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif ($mainValues[$key] < 0) {
                                $contentStr .= "<tr>";
                                $contentStr .= "<td colspan='$colspan_footer' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                        }
                    }
                }


                // Baris Subtotal
//                $contentStr .=  "<tr class='bg-grey-2'>";
//                $contentStr .=  "<td colspan='$colspan_footer' class='text-right text-bold'>SUBTOTAL</td>";
//                $contentStr .=  "<td class='text-right valign-m text-bold'>".number_format($grand_total_all)."</td>";
//                $contentStr .=  "</tr>";
//
//                // Baris PPN 11%
//                $contentStr .=  "<tr class='bg-grey-2'>";
//                $contentStr .=  "<td colspan='$colspan_footer' class='text-right text-bold'>PPN (11%)</td>";
//                $contentStr .=  "<td class='text-right valign-m text-bold'>".number_format($ppn_11)."</td>";
//                $contentStr .=  "</tr>";
//
//                // Baris Grand Total Keseluruhan
//                $contentStr .=  "<tr class='bg-grey-2'>";
//                $contentStr .=  "<td colspan='$colspan_footer' class='text-right text-bold'>GRAND TOTAL</td>";
//                $contentStr .=  "<td class='text-right valign-m text-bold text-red'>".number_format($grand_total_akhir)."</td>";
//                $contentStr .=  "</tr>";

                $pembilang = isset($inWord) ? $inWord : (isset($inWord2) ? $inWord2 : "");

                // Baris TERBILANG / PEMBILANG
                if (isset($pembilang) && $pembilang != '') {
                    $total_seluruh_kolom = $colspan_footer + 1; // Menggabungkan dari ujung kiri ke kanan
                    $contentStr .=  "<tr class='bg-grey-1'>";
                    $contentStr .=  "<td colspan='$total_seluruh_kolom' class='text-left' style='padding: 10px 15px; border-top: 2px solid #ddd;'>";
                    $contentStr .=  "<span class='text-muted'>Terbilang:</span><br/>";
                    $contentStr .=  "<span style='font-style: italic; font-weight: bold; font-size: 1.1em; text-transform: capitalize;'># {$pembilang} #</span>";
                    $contentStr .=  "</td>";
                    $contentStr .=  "</tr>";
                }

                $contentStr .=  "</tfoot>";

                $contentStr .=  "</table>";
                $contentStr .=  "</div>";
                $contentStr .=  "</td>";
                $contentStr .=  "</tr>";
            }
            //details

            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                arrPrint($sumRows2);
                if (isset($sumRows2) && sizeof($sumRows2) > 0) {
                    foreach ($sumRows2 as $key2 => $label2) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key2])) {

                            if (sizeof($mainValues[$key2]) > 0) {
                                //                                cekHere("$key " . $mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key2, $zeroAllowed))) {
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif ($main[$key2] < 0) {
                                //                                cekHitam($mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr line=" . __LINE__ . ">";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr line=" . __LINE__ . ">";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";
            }


            $contentStr6 = "";
            if (isset($dpValueDetilsINV) && sizeof($dpValueDetilsINV) > 0) {
                $contentStr6 .= "<div class='panel-body'>";
                $contentStr6 .= "<table class='table table-responsive'>";
                foreach ($dpFieldNameINV as $dp_fields => $dpFields_alias) {
                    $contentStr6 .= "<tr line=" . __LINE__ . ">";
                    $contentStr6 .= "<td>$dpFields_alias</td>";
                    $contentStr6 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetilsINV[$dp_fields]) . "</td>";
                    $contentStr6 .= "</tr>";
                }
                $contentStr6 .= "</table>";
                $contentStr6 .= "</div>";

                $elementLabels["content_6_display"] = "block";
            }
            else {
                $elementLabels["content_6_display"] = "none";
            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $contenStr5 = "";
            if (isset($mainData2) && sizeof($mainData2) > 0) {

                //                $contenStr5 .= "<div class='panel-body'>";
                $contenStr5 .= "<table class='table table-bordered'>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-centter'>No</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    $contenStr5 .= "<td class='text-centter'>$add_fields</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-center'>1</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    //                    cekHitam($fieldsKey);
                    $contenStr5 .= "<td>" . formatField($fieldsKey, $mainData2[$fieldsKey]) . "</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                if (strlen($inWord2) > 5) {
                    $contenStr5 .= "<tr line=" . __LINE__ . ">";
                    $contenStr5 .= "<td style='vertical-align: bottom;' colspan='" . sizeof($mainData2Fields) . "' rowspan='' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord2</span></td>";
                    $contenStr5 .= "</tr>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "</table>";
                //                $contenStr5 .= "</div>";

            }

//            arrPrintKuning($mainValues);
//            arrPrintKuning($receiptEfakturFields);
            $contentStr7 = "";
            if (isset($receiptEfakturFields) && sizeof($receiptEfakturFields) > 0) {
                $no = 0;
                $contentStr7 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr7 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr7 .= "<tr bgcolor='#f5f5f5'>";
                foreach ($receiptEfakturFields as $key => $label) {
                    $contentStr7 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr7 .= $label;
                    $contentStr7 .= "</th>";
                }
                $contentStr7 .= "</tr>";
                $contentStr7 .= "<tr line=" . __LINE__ . ">";
                foreach ($receiptEfakturFields as $key => $label) {
//                    cekHere("$key");
                    $val = isset($mainValues[$key]) ? $mainValues[$key] : "";
                    $contentStr7 .= "<td>";
                    $contentStr7 .= formatField_he_format($key, $val);
                    $contentStr7 .= "</td>";
                }
                $contentStr7 .= "</tr>";
                $contentStr7 .= "</table>";
                $contentStr7 .= "</div>";
            }
//cekHere($contentStr7);


            $elementLabels["content_src"] = $item_src;
            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
            $elementLabels["content_5"] = $contenStr5;
            $elementLabels["content_6"] = $contentStr6;
            $elementLabels["content_7"] = $contentStr7;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        // arrPrintWebs($arrTags);

        $p->addTags($arrTags);
        $p->render();

        break;

    case "barcodeTransaksi":
        $mytitle = "Indeks Data";
        $hasil = "";

        //$myId = my_id();
        //$myCabangId = my_cabang_id();

        $p = New Layout("$mytitle", "sub judul", "application/template/blankPrint.html");


        if (sizeof($datas) > 0) {
            foreach ($datas as $data) {
                $barcode = $data['barcode'];
                $barcode_f = $data['barcode_f'];
                $qty = $data['qty'];
                $kode = $data['kode'];
                $nama = $data['nama'];


                for ($i = 1; $i <= $qty; $i++) {

                    $hasil .= "<div class='col-md-2'>";
                    $hasil .= "<div class='text-center' style='width: 300px; height: 120px;'>";
                    $hasil .= "$kode $nama";
                    $hasil .= "$barcode_f";
                    $hasil .= "</div>";
                    $hasil .= "</div>";
                }
            }
        }


        $p->addTags(
            array(
                // "menu_left"=>callMenuLeft(),
                // "btn_back"=>callBackNav(),
                "title" => "$title",
                "content" => $hasil,
                "stop_time" => "",
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->render();
        break;

    case "settlement":
        $p = New Layout("$title", "subJdl", "application/template/blankPrint.html");

        $strData = "";
        if (sizeof($cprofiles) > 0) {
            $cpAlias = $cprofiles->alias;
            $cpPropinsi = $cprofiles->propinsi;
            $cpAlamat = $cprofiles->alamat;

            $strData .= "<div class='overflow-h'>";
            $strData .= "<div class='col-xs-5'><h1 class='no-padding no-margin'>$cpAlias</h1></div>";
            $strData .= "<div class='col-md-7 pull-right text-right' style='vert-align: bottom;margin-top: 18px;'>$cpAlamat $cpPropinsi</div>";
            $strData .= "</div>";
        }
        // foreach ($data_transaksi_id as $trJenis => $transaksiIds) {

        $trJenis_fields = isset($jenisParams[$trJenis]["settlementMainFields"]) ? $jenisParams[$trJenis]["settlementMainFields"] : array();
        $trJenis_items = isset($jenisParams[$trJenis]["settlementItemFields"]) ? $jenisParams[$trJenis]["settlementItemFields"] : array();

        /* --------------------------------------------------
         * header
         * --------------------------------------------------*/
        $tbHead = "";
        $sumBawah = array();
        if (sizeof($trJenis_fields) > 0) {
            $tbHead .= "<tr class='text-uppercase'>";
            $tbHead .= "<th class='text-center bg-primary'>no</th>";
            foreach ($trJenis_fields as $trField => $trFieldAttr) {
                $hLabel = $trFieldAttr['label'];
                $tbHead .= "<th class='text-center bg-primary'>$hLabel</thclas>";

                $sumBawah[$trJenis] = isset($trFieldAttr['format']) ? 1 : 0;
            }
            if (sizeof($trJenis_items) > 0) {
                // foreach ($trJenis_items as $trField => $trFieldAttr) {
                //     $hLabel = $trFieldAttr['label'];
                $tbHead .= "<th class='text-center bg-primary'>rincian</th>";
                // }
            }
            $tbHead .= "</tr>";
        }
        // cekPink($sumBawah);
        /* --------------------------------------------------
         * body
         * --------------------------------------------------*/
        $tbBodi = "";
        $no = 0;
        $sumFields = array();
        foreach ($transaksiIds as $transaksiId) {
            $no++;
            $mainSpeks = $mainRegistries[$transaksiId];
            $trItems = $itemRegistries[$transaksiId];
            $tbBodi .= "<tr>";
            if (sizeof($trJenis_fields) > 0) {
                $tbBodi .= "<td class='text-right' title='$transaksiId'>$no</td>";
            }
            /* ------------------------------------------------
             * main
             * ------------------------------------------------*/
            foreach ($trJenis_fields as $trField => $trFieldAttr) {
                $mainValue = isset($mainSpeks[$trField]) ? $mainSpeks[$trField] : null;
                $mainValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField, $mainValue) : $mainValue;
                $attr = isset($trFieldAttr['attr']) ? $trFieldAttr['attr'] : "";
                $tbBodi .= "<td $attr>$mainValue_f</td>";

                if (isset($trFieldAttr['sumFields'])) {
                    if (!isset($sumFields[$trField])) {
                        $sumFields[$trField] = 0;
                    }
                    $sumFields[$trField] += $mainValue;
                }
                // sumFields
            }

            /* ------------------------------------------------
             * items
             * ------------------------------------------------*/
            $tbBodi .= "<td>";
            if (sizeof($trJenis_fields) > 0) {
                $tbBodi .= "<table class='table table-condensed table-striped no-margin'>";
                /* ------------------------------------------------
                 * items-body
                 * ------------------------------------------------*/
                foreach ($trItems as $iKey => $trItem) {
                    if (sizeof($trJenis_items) > 0) {
                        $tbBodi .= "<tr>";
                        foreach ($trJenis_items as $trField => $trFieldAttr) {
                            $itemValue = isset($trItem[$trField]) ? $trItem[$trField] : 0;
                            $attr = isset($trFieldAttr['attr']) ? $trFieldAttr['attr'] : "";
                            // cekBiru($trFieldAttr['format'] . " $trField");
                            $itemValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField, $itemValue) : $itemValue;
                            // $itemValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField,$fValue) : $fValue;

                            $tbBodi .= "<td $attr>$itemValue_f</td>";
                        }
                        $tbBodi .= "</tr>";
                    }
                }
                $tbBodi .= "</table>";
            }
            else {
                $tbBodi .= sizeof($trItems) . " <i> config ui $trJenis belum terdefinisi</i>";
            }
            $tbBodi .= "</td>";

            $tbBodi .= "</tr>";
        }
        // arrPrint($sumFields);
        /* ------------------------------------------------
         * items-footer
         * ------------------------------------------------*/
        $tbFooter = "";
        if (isset($sumBawah[$trJenis]) && ($sumBawah[$trJenis] > 0)) {
            $tbFooter .= "<tr class='bg-primary'>";
            $tbFooter .= "<th>-</th>";
            foreach ($trJenis_fields as $trField => $trFieldAttr) {
                $fValue = isset($sumFields[$trField]) ? $sumFields[$trField] : "";
                $fValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField, $fValue) : $fValue;
                $fAttr = isset($trFieldAttr['attr']) ? $trFieldAttr['attr'] : "";

                $tbFooter .= "<th $fAttr>$fValue_f</th>";
            }
            $tbFooter .= "<th>-</th>";
            $tbFooter .= "</tr>";
        }

        $tbInduk = "";
        $tbInduk .= "<div>PIC : <span class='text-uppercase text-bold'>" . $tertanda['pic']['nama'] . "</span></div>";
        $tbInduk .= "<div>Tanggal : <span class='text-uppercase text-bold'>" . $tertanda['pic']['tanggal'] . "</span></div>";

        $tbInduk .= "<table class='table table-condensed table-striped table-bordered'>";
        $tbInduk .= $tbHead;
        $tbInduk .= $tbBodi;
        $tbInduk .= $tbFooter;
        $tbInduk .= "</table>";

        $btn_box = "";
        // $link_cetak = base_url()."Printing/settlement/$mainId/$trJenis";
        // $btn_box = "<button type='button' class='btn btn-sm pull-right' data-widget='collapse' data-toggle='tooltip' title='' style='margin-right: 5px;' data-original-title='Collapse'><i class='fa fa-minus'></i></button>";
        // $btn_box .= "<button type='button' class='btn btn-sm pull-right' data-toggle='tooltip' title='cetak' style='margin-right: 5px;' data-original-title='cetak' onclick=\"window.open('$link_cetak','status=1,width=600');\"><i class='fa fa-print'></i></button>";
        $trJenis_label = isset($jenisParams[$trJenis]["label"]) ? "settlement " . $jenisParams[$trJenis]["label"] : $trJenis;
        $trJenis_debug = show_debuger() == 1 ? " :: $trJenis" : "";
        $p->setLayoutBoxHeadingProperty("title='$trJenis'");
        $p->setLayoutBoxHeadingCss("text-uppercase");
        $p->setLayoutBoxHeading($trJenis_label . $trJenis_debug, $btn_box);
        $p->setLayoutBoxCss("box-info");
        $p->setLayoutBoxBody(true);
        $p->setLayoutBoxBodyCss("no-paddingg");
        $strData .= $p->layout_box($tbInduk);
        // }

        if (sizeof($tertanda) > 0) {
            foreach ($tertanda as $key => $itemSpeks) {
                $label = isset($itemSpeks['label']) ? $itemSpeks['label'] : $key;
                $nama = isset($itemSpeks['nama']) ? $itemSpeks['nama'] : "-";
                $tanggal = isset($itemSpeks['tanggal']) ? $itemSpeks['tanggal'] : "&nbsp;";
                $strData .= "<div class='col-md-3 col-xs-6 text-center text-capitalize'>";
                $strData .= "$label";
                $strData .= "<div style='height: 50px;'></div>";
                $strData .= "$nama";
                $strData .= "<div>$tanggal</div>";
                $strData .= "</div>";
            }
        }

        $p->addTags(
            array(
                // "menu_left"=>callMenuLeft(),
                // "btn_back"=>callBackNav(),
                // "title" => "$title",
                "content" => $strData,
                "stop_time" => "",
                "profile_name" => $this->session->login['nama'],
                "printing" => "window.print();",
            )
        );

        $p->render();
        break;

    case "viewReceipt_mod":
        // arrPrint($companyProfile);
        // cekHere($jenisTr);
        /* -----------------------------------------------------
         * headwer company profile
         * -----------------------------------------------------*/
        $cpStr = "";
        if ($jenisTr == "580_mod") {
            $cpStr .= "<div>";
            // $cpStr .= "<div><h3 class='no-margin text-uppercase'>" . $companyProfile->alias . "</h3></div>";
            $cpStr .= "<div style='margin-top: 0px;'>Alamat :<br>" . $companyProfile->alamat . "</div>";
            $cpStr .= "<div>" . $companyProfile->kecamatan . " " . $companyProfile->propinsi . " " . $companyProfile->kodepos . "</div>";
            $cpStr .= "<div>" . "Telephone : <b>" . $companyProfile->tlp . "</b></div>";
            // $cpStr .= "<div>" . "NPWP : <b>" . $companyProfile->npwp . "</b></div>";
            $cpStr .= "</div>";
        }
        else {

// arrPrintPink($companyProfile);
            $cpStr .= "<div id='isi_cprofile' class='dborder-cek' style='font-size: 1em;'>";
            $cpStr .= "<div><span class='no-margin text-uppercase text-bold' style='text-decoration: underline;font-size: 1.1em;'>" . $companyProfile->companyProfile_alias . "</span></div>";
            $cpStr .= "<div style=';' class='text-uppercase'>air conditioner  - spare part</div>";
            $cpStr .= "<div style=''>" . $companyProfile->companyProfile_alamat . "</div>";
            $cpStr .= "<div>" . $companyProfile->companyProfile_kecamatan . " " . $companyProfile->companyProfile_kabupaten . " " . $companyProfile->companyProfile_propinsi . " " . $companyProfile->companyProfile_kodepos . "</div>";
            $cpStr .= "<div>" . "Telepon : <b>" . $companyProfile->companyProfile_tlp . "</b></div>";
            // $cpStr .= "<div>" . "NPWP : <b>" . $companyProfile->npwp . "</b></div>";
            $cpStr .= "</div>";
        }

        // arrPrintHijau($itemLabels);
        // arrPrintHijau($items);
        // arrPrintHijau($main);
        // arrPrintKuning($fixedElements['fixedElements']);
        // arrPrintKuning($mainElements);
        // arrPrintPink($elementFixedConfig);
        // arrPrintPink($confLayout['fixedElements']);
        // arrPrintPink($fixedElements);
        $notes_isi = $fixedElements['noteDetails']['contents'];
        $notes_label = $fixedElements['noteDetails']['label'];

        /* ----------------------------------------------------------
         * detil-detilnya element
         * ---------------------------------------------------------*/
        $dataFixElemens = $fixedElements['fixedElements']['contents'];
        $fixedElements = "<div class='panel-heading text-left'>" . $fixedElements['fixedElements']['label'] . "</div>";

        //         arrPrintKuning($dataFixElemens);
        // arrPrint($elementFixedConfig);
        $fixedElements .= "<div class='panel-body'>";
        $fixedElements .= "<table class='table table-condensed'>";
        foreach ($elementFixedConfig as $kitem => $litem) {
            $vitem = $dataFixElemens[$litem];

            $fixedElements .= "<tr>";
            $fixedElements .= "<td class='tbl-label'>$litem</td>";
            $fixedElements .= "<td>:</td>";
            $fixedElements .= "<td class='tbl-nilai'>$vitem</td>";
            $fixedElements .= "</tr>";
        }
        $fixedElements .= "</table>";
        $fixedElements .= "</div>";

        // $nomer = $dataFixElemens[$elementFixedConfig['nomer_top']];
        // arrPrintKuning($mainValues);
        $dataFixElemens = $mainValues;
        $nomer = $dataFixElemens['nomer'];
        // $tgl_trans = isset($dataFixElemens[$elementFixedConfig['dtime']]) ? $dataFixElemens[$elementFixedConfig['dtime']] : $dataFixElemens['Date'];
        $tgl_trans = isset($dataFixElemens['fulldate']) ? formatTanggal($dataFixElemens['fulldate'], 'd F Y') : $dataFixElemens['Date'];
        $tanggal_transaksi = "<div class='bborder-cek '>";
        $tanggal_transaksi .= "<div class='text-uppercase' style='float: left;text-align: left; overflow: hidden;'>";
        $tanggal_transaksi .= "<h4 class='no-margin text-renggang-5'>$title</h4>";
        $tanggal_transaksi .= "<p class='no-margin'>$nomer</p>";
        $tanggal_transaksi .= "</div>";
        $tanggal_transaksi .= "<div>";
        $tanggal_transaksi .= "Tanggal: $tgl_trans";
        $tanggal_transaksi .= "</div>";
        $tanggal_transaksi .= "</div>";
        //        arrPrint($dataFixElemens);

        /* --------------------------------------------------
         * data konsumen
         * -------------------------------------------------------*/
        $customerDetails = "";
        $customerDetails .= "<div class='panel-heading text-left'>" . $elementConfigs['customerDetails']['label'] . "</div>";
// arrPrintKuning($mainElements);
        $customerDetails .= "<div class='panel-body'>";
        $customerDetails .= "<table class='table table-condensed'>";
        // $vitem_0 = blobDecode($mainElements['customerDetails']['contents']);
        $vitem_0 = ($mainElements['vendorDetails']['contents']);
        // arrPrint($vitem_0);

        arrPrint($mainValues);

        // arrPrint($elementConfigs['customerDetails']['usedFields']);
//        $nama = isset($vitem_0['nama']) ? $vitem_0['nama'] : $mainValues['customerName'];
        $nama = isset($vitem_0['nama']) ? $vitem_0['nama'] : $mainValues['customerDetails__nama'];
        $alamat_1 = $vitem_0['alamat_1'];
        $kelurahan = $vitem_0['kelurahan'];
        $kecamatan = $vitem_0['kecamatan'];
        $kabupaten = $vitem_0['kabupaten'];
        $propinsi = $vitem_0['propinsi'];
        // $tlp_1 = $vitem_0['tlp_1'];
        $tlp_1 = isset($vitem_0['tlp_1']) ? $vitem_0['tlp_1'] : $mainValues['customerDetails__tlp_1'];

        $alamat_konsumen = (strlen($alamat_1) > 3 ? $alamat_1 : '');
        $alamat_konsumen .= (strlen($kelurahan) > 2 ? " $kelurahan," : '');
        $alamat_konsumen .= (strlen($propinsi) > 2 ? " $propinsi," : '');

        $pihakProfile = "<div class='text-left'>";
        $pihakProfile .= "Kepada Yth.<br>";
        $pihakProfile .= "<div class='text-uppercase text-bold'>$nama</div>";
        $pihakProfile .= "<div class='text-capitalize'>$alamat_konsumen</div>";
        $pihakProfile .= "<div class=''>Tlp: $tlp_1 </div>";
        $pihakProfile .= "</div>";

        if(isset($mainValues['referensi_so_project'])){
            $projectDetails = $mainValues['referensi_so_project__nama'];
            $pihakProfile .= "<div style='margin-top: 10px;' class='text-right'>";
            $pihakProfile .= "<div class='text-uppercase text-bold'>PROJECT : $projectDetails</div>";
            $pihakProfile .= "</div>";
        }

        $showElementConfigs = $elementConfigs;
        if (isset($layoutElementConfigs) && count($layoutElementConfigs) > 0) {
            $showElementConfigs = $layoutElementConfigs;
        }
        foreach ($showElementConfigs['customerDetails']['usedFields'] as $kitem => $litem) {
            // arrPrintHijau($vitem_0);
            $vitem = $vitem_0[$kitem];

            $customerDetails .= "<tr>";
            $customerDetails .= "<td class='tbl-label'>$litem</td>";
            $customerDetails .= "<td>:</td>";
            $customerDetails .= "<td class='tbl-nilai' title='$litem'>$vitem</td>";
            $customerDetails .= "</tr>";
        }
        $customerDetails .= "</table>";
        $customerDetails .= "</div>";

//        arrPrintWebs($customerDetails);

        /* --------------------------------------------------
         * data deliveri
         * -------------------------------------------------------*/
        $deliveryDetails = "";
        $deliveryDetails .= "<div class='panel-heading text-left'>" . $elementConfigs['deliveryDetails']['label'] . "</div>";

        $deliveryDetails .= "<div class='panel-body'>";
        $deliveryDetails .= "<table class='table table-condensed'>";
        $vitem_0 = blobDecode($mainElements['deliveryDetails']['contents']);

        $showElementConfigs = $elementConfigs;
        if (isset($layoutElementConfigs) && count($layoutElementConfigs) > 0) {
            $showElementConfigs = $layoutElementConfigs;
        }
        foreach ($showElementConfigs['deliveryDetails']['usedFields'] as $kitem => $litem) {
            // arrPrintHijau($vitem_0);
            $vitem = $vitem_0[$kitem];

            $deliveryDetails .= "<tr>";
            $deliveryDetails .= "<td class='tbl-label'>$litem</td>";
            $deliveryDetails .= "<td>:</td>";
            $deliveryDetails .= "<td class='tbl-nilai' title='$litem'>$vitem</td>";
            $deliveryDetails .= "</tr>";
        }
        $deliveryDetails .= "</table>";
        $deliveryDetails .= "</div>";

        // arrPrintHijau($fixedElements);
        /* --------------------------------------------------
         * data note catatan
         * -------------------------------------------------------*/
        $noteDetails = "";
        if (isset($main->description)) {

            $noteDetails .= "<div class='ppanel-heading text-left'>" . $notes_label . "</div>";
            // $noteDetails .= "<div class='panel-heading text-left'>"."notes"."</div>";

            $noteDetails .= "<div class='ppanel-body'>";
            $noteDetails .= "<table class='table table-condensed'>";
            // arrPrintHijau($main);
            // $vitem_0 = blobDecode($mainElements['deliveryDetails']['contents']);
            // $notes_filter = (array_filter($notes_isi));
            // foreach ($notes_filter as $kitem => $litem) {
            //     // arrPrintHijau($vitem_0);
            //     $vitem = $vitem_0[$kitem];
            //     cekHijau("$vitem $kitem");
            //     $litem_f = strlen($litem) < 12 ? str_replace("<br>", "",$litem) : $litem;
            //     $noteDetails .= "<tr>";
            //     // $noteDetails .= "<td class='tbl-label'>$litem</td>";
            //     // $noteDetails .= "<td>:</td>";
            //     $noteDetails .= "<td class='tbl-nilai' title='$kitem'>$litem_f</td>";
            //     $noteDetails .= "</tr>";
            // }

            $notes_isi = $main->description;
            $noteDetails .= "<tr>";
            $noteDetails .= "<td class='tbl-nilai' title='$kitem'>$notes_isi</td>";
            $noteDetails .= "</tr>";
            $noteDetails .= "</table>";
            $noteDetails .= "</div>";
        }

        if( sizeof($items5) == 0 ){
        /* --------------------------------------------------
         * isi invoice
         * -------------------------------------------------------*/
        $tblShow = "";
        $tblShow .= "<tr class='bg-success text-uppercase'>";
        $tblShow .= "<th>no</th>";
        foreach ($itemLabels as $itemLabel) {

            // arrPrint($itemLabel);
            if (is_array($itemLabel)) {
                $attr = isset($itemLabel['attr']) ? $itemLabel['attr'] : "";
                $tblShow .= "<th $attr>" . $itemLabel["label"] . "</th>";
            }
            else {
                $tblShow .= "<th>$itemLabel</th>";
            }
        }
        $tblShow .= "</tr>";
        //arrPrint($arrSubDetailDataKolom);
        // arrPrint($itemLabels);
        /* -------------------------
         * bodies table
         * -----------------------*/
        // arrPrintHijau($items);
        $no = 0;
        foreach ($items as $pids => $item) {
            $no++;
            //            $id = $item["id"];

            $id = $pids;
            //            arrPrintMerah($item);
            $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
            $items[$id] = array_merge(array_filter($items[$id]), array_filter($arrItemsRegistries[$id]));
            //            arrPrint($arrItemsRegistries[$id]);
            //            $item = $items[$id];
            //            arrPrintKuning($item);
            //            arrPrintKuning($itemLabels);
            $tblShow .= "<tr>";
            $tblShow .= "<td>$no</td>";
            foreach ($itemLabels as $itemkey => $itemLabel) {
                //                arrPrint($itemLabel);

                $attr = isset($itemLabel['attr']) ? $itemLabel['attr'] : "";
                $nilai = isset($item[$itemkey]) ? $item[$itemkey] : "";
                //cekMerah($nilai . " | " . $itemkey);
                $add_data_val = "";
                if (is_array($itemLabel)) {
                    $add_data_val = isset($item[$itemLabel["addKey"]]) ? "<br>" . $item[$itemLabel["addKey"]] : "";
                    $cssClass = isset($itemLabel['label']) ? $itemLabel['label'] : "";
                }
                else {
                    $cssClass = str_replace(" ", "_", $itemLabel);
                }
                //                cekBiru($nilai . " | " . $itemkey . " | " . $cssClass);
                //                $nilai_f = formatField_he_format($cssClass, $nilai);
                $nilai_f = formatField_he_format($itemkey, $nilai);
                //                $nilai_f = formatField_he_format("harga", $nilai);
                $tblShow .= "<td title='$itemkey' $attr class='$cssClass'>$nilai_f $add_data_val</td>";
            }
            $tblShow .= "</tr>";
            //-----------------------
            if (isset($arrSubDetailDataKolom["nama"][$id])) {
                $tblShow .= "<tr line=" . __LINE__ . ">";
                $tblShow .= "<td>&nbsp;</td>";
                $tblShow .= "<td colspan='" . sizeof($itemLabels) . "' style='font-size:12px;'>";
                foreach ($arrSubDetailDataKolom["nama"][$id] as $sku => $sku_data) {
                    $tblShow .= "$sku : " . formatField_he_format("serial", $sku_data) . "<br>";
                }
                $tblShow .= "</td>";
                $tblShow .= "</tr>";
            }
            //-----------------------
            if (isset($noteEnabled) && ($noteEnabled == true)) {
                if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {
                    $tblShow .= "<tr line=" . __LINE__ . ">";
                    $tblShow .= "<td>&nbsp;</td>";
                    $tblShow .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";
                    $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";
                    $string = str_replace("\n", "<br>", $iVal);
                    $string = str_replace("\r", "<br>", $string);
                    $string = str_replace("&lt;br&gt;", "<br>", $string);
                    $tblShow .= $string;
                    $tblShow .= "</td>";
                    $tblShow .= "</tr>";
                }
            }
        }

        /* -------------------------
         * jumlah bawah
         * -----------------------*/
        $colspan = count($itemLabels);
        // cekHere("$colspan");
        // arrPrintPink($mainValues);
        // arrPrintHijau($receiptSumFieldsLayout);
        $simpel_sum = array(
            "nett1",
            // "ppn_out_bulat",
            "nett1_bulat",
            "nilai_pembulatan",
        );
        // arrPrintHijau($confLayout);
        // arrPrintHijau($signature);
        // arrPrintPink($signValues);
        // arrPrintPink($receiptSumFieldsLayout);

        /* -----------------------------------
         * point dibuang dari sumfield
         * ---------------------------------------*/
        unset($receiptSumFieldsLayout['point_transaksi']);
        unset($receiptSumFieldsLayout['point_saldo_akhir']);
        unset($receiptSumFieldsLayout['ongkir_ui']);
        // unset($receiptSumFieldsLayout['nett1']);
        if (isset($mainValues["diskon_kategori_unit"]) && $mainValues["diskon_kategori_unit"] == 0) {
            unset($receiptSumFieldsLayout['lastNett']);
            unset($receiptSumFieldsLayout['diskon_kategori_unit']);
        }
        if (isset($mainValues["total_diskon"]) && $mainValues["total_diskon"] == 0) {

            unset($receiptSumFieldsLayout['total_diskon']);
        }
        // $receiptSumFieldsLayout['lastNett'] = "total";
        // $receiptSumFieldsLayout['diskon_kategori_unit'] = "Diskon";
        // arrPrintHijau($receiptSumFieldsLayout);
        // arrPrintHijau($mainValues);
        if (!isset($mainValue['diskon_kategori_unit'])) {
            unset($receiptSumFieldsLayout['diskon_kategori_unit']);
            unset($receiptSumFieldsLayout['lastNett']);
        }

        foreach ($receiptSumFieldsLayout as $kitem => $litem) {

            $cssClass = str_replace(" ", "_", strtolower($litem));
            $cl_lysimpel = in_array($kitem, $simpel_sum) ? "lysimpel" : "";

            $tblShow .= "<tr class='table-botom-borderless $cl_lysimpel' title='$kitem'>";

            $nilai = $mainValues[$kitem] > 0 ? $mainValues[$kitem]:0;
            $nilai_f = number_format($nilai);
            //
            $tblShow .= "<td colspan='$colspan' align='right' class='bottom-borderless $cssClass'>$litem</td>";
            $tblShow .= "<td class='nilai_nota $cssClass' title='$kitem'>$nilai_f</td>";
            //     }
            $tblShow .= "</tr>";
        }


        $itemStr = "";
        $itemStr .= "<style type='text/css'>
            .dpp_ppn, .ppn_realisasi, .dpp, .jumlah_total, .harga_satuan, .sub_nett1{
                text-align: right;
            }
            .total_amount, .jumlah, .total{
                font-weight: bold;                
                border-top: unset !important;
                height: unset !important;
            }
            .total_bruto{
                font-weight: bold;
                
            }
            .table {
                margin-bottom: 0;
            }
            .table>tbody>tr>td, .table>tbody>tr>th{
                padding: 0 5px;
            }
            body{
                font-family: Arial;
                // font-size: 1em !important;
                font-size: 14px !important;
            }
</style>";
        $itemStr .= "<div class='panel-contentt'>";
        $itemStr .= "<table class='table table-bordered'>";
        $itemStr .= $tblShow;
        $itemStr .= "</table>";
        $itemStr .= "</div>";
        }


        // =========================================================================================
        // JIKA ITEMS5 ADA ISINYA -> GUNAKAN LOGIKA RASIO & METAPRODUK
        // =========================================================================================
        if (isset($items5) && sizeof($items5) > 0) {

            $opsi_cetak = isset($opsi_cetak) ? $opsi_cetak : 4;
            $master_jenis_produk = isset($metaProduk) ? $metaProduk : array();

            // Variabel Finansial dari Controller
            $tagihan_um_f = isset($tagihan_um) ? (float)$tagihan_um : 0;
            $project__harga_f = isset($project__harga) ? (float)$project__harga : 0;
            $pph23_f = isset($pph23) ? (float)$pph23 : 0;
            $kas_nilai_f = isset($kas_nilai) ? (float)$kas_nilai : 0;

            // --- HITUNG RASIO TAGIHAN ---
            // Rasio = Tagihan Saat Ini / (Nilai Kontrak 100% + PPN 11%)
            $grand_total_100 = $project__harga_f + ($project__harga_f * 0.11);
            $rasio = ($grand_total_100 > 0) ? ($tagihan_um_f / $grand_total_100) : 0;
            $persen_um = round($rasio * 100);
            $lbl_um = " (" . $persen_um . "%)";

            // Wrapper disamakan dengan layout lama
            $contentStr =  "<div class='panel panel-default no-padding $class_blur'>";
            $contentStr .= "<div class='panel-contentt $class_blur'>";
            $contentStr .= "<table id='items5' class='table table-bordered'>";

            // --- PRA-KALKULASI DATA ---
            $rekap_barang = array();
            $grand_total_all_um = 0;
            $global_jasa = 0;
            $global_material = 0;
            $global_unit = 0;
            $merk_unit = array();

            foreach ($items5 as $iSpec) {
                // Ambil harga dasar dari level project
                $harga_proproporsional = (isset($iSpec['harga']) ? (float)$iSpec['harga'] : 0) * $rasio;
                $grand_total_all_um += $harga_proproporsional;

                if ($opsi_cetak == 3 || $opsi_cetak == 4) {
                    // 1. Biaya (JASA / BIAYA)
                    if (isset($iSpec['bahan_baku']['biaya'])) {
                        foreach ($iSpec['bahan_baku']['biaya'] as $prdData) {
                            $nama = strtoupper($prdData['produk_dasar_nama']);
                            $qty_rasio = (float)$prdData['jml'] * $rasio;
                            $saldo_rasio = (float)$prdData['saldo'] * $rasio;
                            $satuan = isset($prdData['satuan']) && !empty($prdData['satuan']) ? $prdData['satuan'] : 'Unit';

                            if ($opsi_cetak == 3) {
                                if(!isset($rekap_barang[$nama])) {
                                    $rekap_barang[$nama] = array('qty' => 0, 'satuan' => $satuan, 'kategori' => 3, 'kode' => '--');
                                }
                                $rekap_barang[$nama]['qty'] += $qty_rasio;
                            }
                            if ($opsi_cetak == 4) { $global_jasa += $saldo_rasio; }
                        }
                    }

                    // 2. Produk (MATERIAL & UNIT)
                    if (isset($iSpec['bahan_baku']['produk'])) {
                        foreach ($iSpec['bahan_baku']['produk'] as $prdData) {
                            $id_dasar = $prdData['produk_dasar_id'];
                            $kodeProduk = isset($master_jenis_produk[$id_dasar]['kode']) && $master_jenis_produk[$id_dasar]['kode'] !== "" ? "<r>" . $master_jenis_produk[$id_dasar]['kode'] . "</r>" : '--';
                            $nama = strtoupper($prdData['produk_dasar_nama']);
                            $qty_rasio = (float)$prdData['jml'] * $rasio;
                            $saldo_rasio = (float)$prdData['saldo'] * $rasio;
                            $satuan = isset($master_jenis_produk[$id_dasar]['satuan']) && !empty($master_jenis_produk[$id_dasar]['satuan']) ? strtolower($master_jenis_produk[$id_dasar]['satuan']) : 'Unit';

                            // Kategori via MetaProduk
                            $jenis = isset($master_jenis_produk[$id_dasar]['kategori']) ? strtolower($master_jenis_produk[$id_dasar]['kategori']) : 'non unit';

                            if ($opsi_cetak == 3) {
                                $kategori_num = ($jenis == 'unit') ? 1 : 2;
                                if(!isset($rekap_barang[$nama])) {
                                    $rekap_barang[$nama] = array('qty' => 0, 'satuan' => $satuan, 'kategori' => $kategori_num, 'kode' => $kodeProduk);
                                }
                                $rekap_barang[$nama]['qty'] += $qty_rasio;
                            }
                            if ($opsi_cetak == 4) {
                                if ($jenis == 'unit') {
                                    $global_unit += $saldo_rasio;
                                    $exp = explode(" ", $nama);
                                    $merk = isset($exp[0]) ? $exp[0] : '';
                                    if (!in_array($merk, $merk_unit) && $merk != '') { $merk_unit[] = $merk; }
                                } else {
                                    $global_material += $saldo_rasio;
                                }
                            }
                        }
                    }
                }
            }

            // --- HEADER ---
            $contentStr .= "<thead><tr class='bg-success text-uppercase'>";
            if ($opsi_cetak == 3) {
                $contentStr .= "<th class='text-center' width='5%'>No</th><th class='text-center' width='15%'>JUMLAH$lbl_um</th><th class='text-center' width='15%'>SATUAN</th><th class='text-center' width='65%'>BARANG</th>";
            }
            elseif ($opsi_cetak == 4) {
                $contentStr .= "
                    <th class='text-center' width='5%'>No</th>
                    <th class='text-center' width='10%'>JUMLAH</th>
                    <th class='text-center' width='45%'>BARANG</th>
                    <th class='text-center' width='20%'>HARGA SATUAN</th>
                    <th class='text-center' width='15%'>JUMLAH</th>
                ";
            }
            elseif ($opsi_cetak == 1) {
                $contentStr .= "<th class='text-center' width='5%'>No</th><th class='text-center'>Pekerjaan/WO</th><th class='text-center' width='25%'>Jumlah$lbl_um</th>";
            }
            elseif ($opsi_cetak == 2) {
                $contentStr .= "<th class='text-center' width='5%'>No</th><th class='text-center' width='30%'>Pekerjaan/WO</th><th class='text-center'>Barang</th><th class='text-center' width='20%'>Jumlah$lbl_um</th>";
            }
            $contentStr .= "</tr></thead>";

            // --- BODY ---
            $contentStr .= "<tbody>";
            if ($opsi_cetak == 3) {
                uasort($rekap_barang, function($a, $b) {
                    if ($a['kategori'] == $b['kategori']) { return 0; }
                    return ($a['kategori'] < $b['kategori']) ? -1 : 1;
                });
                $no_rekap = 1;
                foreach ($rekap_barang as $nama_barang => $data_rek) {
                    $contentStr .= "<tr><td class='text-center'>{$no_rekap}.</td><td class='text-center'><b>" . round($data_rek['qty'], 2) . "</b></td><td class='text-center text-capitalize'>{$data_rek['satuan']}</td><td>{$nama_barang} {$data_rek['kode']}</td></tr>";
                    $no_rekap++;
                }
            } elseif ($opsi_cetak == 4) {
                $no_rekap = 1;
                if ($global_jasa > 0) {
                    $contentStr .= "
                        <tr>
                            <td class='text-center'>{$no_rekap}.</td>
                            <td class='text-center text-bold'>1 LOT</td>
                            <td class='text-bold'>{$persen_um}% JASA PEMASANGAN AC</td>
                            <td class='text-right'>" . number_format($global_jasa) . "</td>
                            <td class='text-right text-bold'>" . number_format($global_jasa) . "</td>
                        </tr>
                    ";
                    $no_rekap++;
                }
                if ($global_material > 0) {
                    $contentStr .= "
                    <tr>
                        <td class='text-center'>{$no_rekap}.</td>
                        <td class='text-center text-bold'>1 LOT</td>
                        <td class='text-bold'>{$persen_um}% MATERIAL dan PERLENGKAPAN</td>
                        <td class='text-right'>" . number_format($global_material) . "</td>
                        <td class='text-right text-bold'>" . number_format($global_material) . "</td>
                    </tr>";
                    $no_rekap++;
                }
                if ($global_unit > 0) {
                    $str_merk = implode(", ", $merk_unit);
                    $title_unit = "{$persen_um}% UNIT AC" . ($str_merk != '' ? " {$str_merk}" : "");
                    $contentStr .= "
                    <tr>
                        <td class='text-center'>{$no_rekap}.</td>
                        <td class='text-center text-bold'>1 LOT</td>
                        <td class='text-bold'>{$title_unit}</td>
                        <td class='text-right'>" . number_format($global_unit) . "</td>
                        <td class='text-right text-bold'>" . number_format($global_unit) . "</td>
                    </tr>";
                    $no_rekap++;
                }
            } else {
                $no = 0;
                foreach ($items5 as $iSpec) {
                    $no++;
                    $total_baris_um = (isset($iSpec['harga']) ? (float)$iSpec['harga'] : 0) * $rasio;
                    $contentStr .= "<tr><td class='text-center'>$no.</td><td class='text-left text-bold'>{$iSpec['nama']}</td>";
                    if ($opsi_cetak == 2) {
                        $contentStr .= "<td><table width='100%' class='table-condensed no-margin'>";
                        if (isset($iSpec['bahan_baku']['produk'])) {
                            foreach ($iSpec['bahan_baku']['produk'] as $prdData) {
                                $qty_v = round($prdData['jml'] * $rasio, 2);
                                $contentStr .= "
                                <tr class='text-meta text-bold'>
                                    <td style='border:none; padding:2px;'>- {$prdData['produk_dasar_nama']}</td>
                                    <td class='text-right' style='border:none; padding:2px;' width='15%'>{$qty_v} x</td>
                                </tr>";
                            }
                        }
                        if (isset($iSpec['bahan_baku']['biaya'])) {
                            foreach ($iSpec['bahan_baku']['biaya'] as $prdData) {
                                $qty_v = round($prdData['jml'] * $rasio, 2);
                                $contentStr .= "
                                <tr class='text-meta text-bold'>
                                    <td style='border:none; padding:2px;'>- {$prdData['produk_dasar_nama']}</td>
                                    <td class='text-right' style='border:none; padding:2px;' width='15%'>{$qty_v} x</td>
                                </tr>";
                            }
                        }
                        $contentStr .= "</table></td>";
                    }
                    $contentStr .= "<td class='text-right text-bold'>" . number_format($total_baris_um) . "</td></tr>";
                }
            }
            $contentStr .= "</tbody>";

            // --- FOOTER ---
            $contentStr .= "<tfoot>";
            $colspan_footer = ($opsi_cetak == 1) ? 2 : (($opsi_cetak == 4) ? 4 : 3);

//            $contentStr .=  "<tr class='bg-grey-2'><td colspan='$colspan_footer' class='text-right'>TOTAL NILAI PEKERJAAN BERDASARKAN PROGRES</td><td class='text-right'>".number_format($grand_total_all_um)."</td></tr>";

            if(isset($receiptSumFieldsLayout) && is_array($receiptSumFieldsLayout)){
                foreach ($receiptSumFieldsLayout as $kitem => $litem) {
                    $nilai = isset($mainValues[$kitem]) ? $mainValues[$kitem] : 0;
                    $contentStr .= "
                    <tr>
                        <td colspan='$colspan_footer' align='right'>$litem</td>
                        <td class='text-right'>" . number_format(floor($nilai)) . "</td>
                    </tr>";
                }
            }

            if ($opsi_cetak == 4) {
//                if ($pph23_f > 0) { $contentStr .= "<tr><td colspan='$colspan_footer' class='text-right text-red'>Potongan PPH 23 (2%)</td><td class='text-right text-red'>(".number_format($pph23_f).")</td></tr>"; }
//                if ($kas_nilai_f > 0) { $contentStr .= "<tr class='bg-grey-2'><td colspan='$colspan_footer' class='text-right'><b>Nilai Bersih (Transfer)</b></td><td class='text-right'><b>".number_format($kas_nilai_f)."</b></td></tr>"; }
            }

            $contentStr .= "</tfoot></table></div></div>";
            $itemStr .= $contentStr;
        }

        // =========================================================================================
        // STYLING CSS GLOBAL (HANYA DITAMBAHKAN SEKALI DI PALING BAWAH)
        // =========================================================================================
        $itemStr .= "<style type='text/css'>
                        .dpp, .jumlah_total, .harga_satuan, .sub_nett1{
                            text-align: right;
                        }
                        .jumlah, .total{
                            font-weight: bold;                
                            border-top: unset !important;
                            height: unset !important;
                        }
                        .total_bruto{
                            font-weight: bold;
                        }
                        .table {
                            margin-bottom: 0;
                        }
                        .table>tbody>tr>td, .table>tfoot>tr>td, .table>tbody>tr>th, .table>thead>tr>th{
                            padding: 5px;
                            vertical-align: middle;
                        }
                        body{
                            font-family: Arial, sans-serif;
                            font-size: 14px !important;
                        }
                    </style>";


        $contenFaktu = "";
        if (isset($receiptEfakturFields) && sizeof($receiptEfakturFields) > 0) {
            $no = 0;
            $contenFaktu .= "<div class='panel panel-default'>";
            $contenFaktu .= "<div class='table-responsive' style='border:0px solid red;'>";
            $contenFaktu .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            $contenFaktu .= "<tr bgcolor='#f5f5f5'>";
            foreach ($receiptEfakturFields as $key => $label) {
                $contenFaktu .= "<th class='text-muted' style='font-weight:bold;'>";
                $contenFaktu .= $label;
                $contenFaktu .= "</th>";
            }
            $contenFaktu .= "</tr>";
            $contenFaktu .= "<tr line=" . __LINE__ . ">";
            foreach ($receiptEfakturFields as $key => $label) {
//                    cekHere("$key");
                $val = isset($mainValues[$key]) ? $mainValues[$key] : "";
                $contenFaktu .= "<td class='$key'>";
                $contenFaktu .= formatField_he_format($key, $val);
                $contenFaktu .= "</td>";
            }
            $contenFaktu .= "</tr>";
            $contenFaktu .= "</table>";
            $contenFaktu .= "</div>";
            $contenFaktu .= "</div>";
        }
//cekHere($contentStr7);
        // arrPrint($terbilangParams);
        // arrPrintPink($layoutElementConfigs);
        /* ---------------------------------------------------
         * inword
         * ---------------------------------------------------*/
        $terbilangParams = isset($confLayout['terbilangSumFields']) ? $confLayout['terbilangSumFields'] : "";
        // arrPrintPink($terbilangParams);
        if ($terbilangParams != "") {
            foreach ($terbilangParams as $terkey => $terbilangParam) {
                $grand_pembulatan_dropshiper = $mainValues[$terkey];
                //                cekHitam(__LINE__);
                $inwords = inWordInd($grand_pembulatan_dropshiper);
            }
            // $inwordStr = "<div class='panel-body'>Terbilang : ---<b class='font-size-1'>$inwords</b>---</div>";
            $inwordStr = "<div class=''>---<b class='font-size-1'>$inwords</b>---</div>";
        }
        else {
            $inwordStr = "";
        }

        // arrPrintHijau($mainValues);
        /* ---------------------------------------------
         * sign
         * ---------------------------------------------*/
        $olehName = $mainValues['olehName'];
        $description = $mainValues['description'];
        // arrPrintKuning($signValues);
        $signStr = array();
        if (sizeof($signValues) > 0) {
            foreach ($signValues as $key => $eSpec) {
                $signVar = "";
                $slabel = $eSpec['label'];
                if ($key == "sign_1") {
                    $scontents = strlen($eSpec['contents']) > 2 ? $eSpec['contents'] : $olehName;
                }
                else {

                    $scontents = $eSpec['contents'];
                }

                $signVar .= "<div style='floatr: left;' title='$key' class='$key text-uppercase'>";
                $signVar .= "<div >$slabel</div>";
                $signVar .= "<div style='min-height: 55px;'>&nbsp;</div>";
                $signVar .= "<div class='text-capitalize'>($scontents)</div>";
//                $signVar .= "<div class='text-capitalize'>(_______________)</div>";
                $signVar .= "</div>";

                $signStr[$key] = $signVar;
            }
            //            $pemilikSign = "";
            //            $pemilikSign .= "<div style='floatr: left;' title='$key' class='$key text-uppercase'>";
            //            $pemilikSign .= "<div>Pemilik</div>";
            //            $pemilikSign .= "<div style='min-height: 55px;'>&nbsp;</div>";
            //            // $pemilikSign .= "<div class='text-capitalize'>($scontents)</div>";
            //            $pemilikSign .= "<div class='text-capitalize'>(_______________)</div>";
            //            $pemilikSign .= "</div>";
            //
            //            $signStr['pemilikSign'] = $pemilikSign;

//            $penerimaSign = "";
//            $penerimaSign .= "<div style='floatr: left;' title='$key' class='$key text-uppercase'>";
//            $penerimaSign .= "<div>Penerima</div>";
//            $penerimaSign .= "<div style='min-height: 55px;'>&nbsp;</div>";
//            // $pemilikSign .= "<div class='text-capitalize'>($scontents)</div>";
//            $penerimaSign .= "<div class='text-capitalize'>(_______________)</div>";
//            $penerimaSign .= "</div>";
//
//            $signStr['penerimaSign'] = $penerimaSign;
//
//            $penerimaSign = "";
//            $penerimaSign .= "<div style='floatr: left;' title='$key' class='$key text-uppercase'>";
//            $penerimaSign .= "<div>Hormat kami</div>";
//            $penerimaSign .= "<div style='min-height: 55px;'>&nbsp;</div>";
//            // $pemilikSign .= "<div class='text-capitalize'>($scontents)</div>";
//            $penerimaSign .= "<div class='text-capitalize'>(_______________)</div>";
//            $penerimaSign .= "</div>";
//
//            $signStr['sign_2'] = $penerimaSign;


        }
//                 arrPrintPink($signStr);
        //--------------------------------------------------------------end-----------
        $statik_note_datas = array(
            "penagihan hanya dengan faktur asli",
            "pembayaran dengan rekening/giro dianggap lunas jika telah diuangkan",
            "barang yang telah dibeli tidak dapat ditukar / dikembalikan",
        );

        $statik_note = "";
        $statik_note .= "<div class='alert alert-danger' style='min-height: 95px;'>";
        $statik_note .= "<ul class='todo-list no-padding'>";
//         foreach ($statik_note_datas as $statik_note_data) {
//             $statik_note .= "<li class='text-capitalize'><div class='overflow-h'><span style='float: left;margin-right: 2px;' class='glyphicon glyphicon-chevron-right
//
// '></span> <div style='float: left; width: 91%;' class=''>$statik_note_data</div></div></li>";
//         }
        $statik_note .= "<li class='text-capitalize'>Notes:</li>";
        $statik_note .= "<li class='text-capitalize'>$description</li>";
        $statik_note .= "</ul>";
        $statik_note .= "</div>";
        // $statik_note = "";

        $footer = "";
        $footer .= "<div class='col-md-12 text-uppercase' id='rekening_baknk'>";
        $footer .= "transfer via <b>BCA 8830713132</b> an. PT. Everest Electronic<br>";
        $footer .= "transfer via <b>MANDIRI 1640009779887</b> an. PT. Everest Electronic";
        $footer .= "</div>";

        /* ---------------------------------------------------
         * point transaksi
         * ---------------------------------------------------*/
        $point_transaksi = $mainValues['point_transaksi'];
        $point_akhir = $mainValues['point_saldo_akhir'];
        // arrPrintHijau($mainValues);
        $point_transaksi_f = number_format($point_transaksi);
        $point_akhir_f = number_format($point_akhir);
        $lain_lain = "";
        $lain_lain .= "<div class='col-md-12' style='border-top: darkgrey 1px dashed;margin-top: 10px;font-style: italic;'>";
        $lain_lain .= "Poin saat ini: $point_transaksi_f Total: $point_akhir_f";
        $lain_lain .= "</div>";
        // $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template, $showCabangInvoice);
        $template = "template/582spo_mod.html";
        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template);

        // if (sizeof($elementLabels) > 0) {
        //     foreach ($elementLabels as $tKey => $tValue) {
        //         $arrTags[$tKey] = $tValue;
        //     }
        //     $arrTags["time_cetak"] = "print @" . dtimeNow('d M Y H:i');
        // }

        //         arrPrintWebs($arrTags);
        // cekHijau("$template");

        /* ----------------------------------------------------------------
         *
         * ----------------------------------------------------------------*/
        $cp_editor = MODUL_PATH . "Printing/editorPrint";
        $option = "";
        $option .= "<div class='hidden-on-print text-uppercase' style='background-color: black;padding-left: 20px;'>";
        // $option .= "<label class='text-white'><input type='radio' name='layout' id='lylengkap' checked> lengkap</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' name='layout' id='lysimpel' checked> lengkap/simple layout</label> ";
        $option .= "<label class='text-white'><input type='checkbox' id='myCheckbox' checked> logo </label> ";
        $option .= "<label class='text-white'><input type='checkbox' id='myCheckAddr' checked> alamat perusahaan</label> ";
        // $option .= " <label class='text-white'><input type='checkbox' id='checkboxrekening' checked> rekening bank</label> <a class='text-warning' href='$cp_editor' data-toggle='modal' data-target='#myModal'><i class='glyphicon glyphicon-pencil'></i></a>";
        $option .= " <button type='button' id='print' onclick=\"window.print();\" title='print/cetak'> <i class='glyphicon glyphicon-print'></i></button>";


        // =========================================================================
        // TAMBAHAN: DROPDOWN PILIHAN OPSI CETAK TABEL
        // =========================================================================
        $option .= " <span style='display:inline-block; background-color:#000; padding:0 10px; border-radius:4px; margin-left:5px; margin-right:5px;'>";
        $option .= " <label class='text-white' style='margin-right:5px; margin-bottom:0;'>MODE CETAK:</label>";
        // JS onchange di bawah ini akan menambahkan/mengubah parameter ?opsi_cetak=X di URL lalu me-reload halaman tanpa merusak ID invoice
//        $option .= " <select id='pilih_opsi_cetak' style='color:black; padding:2px; border-radius:3px; outline:none;' onchange=\"let url = new URL(window.location.href); url.searchParams.set('opsi_cetak', this.value); window.location.href = url.href; console.log('url.href: ' + url.href);\">";

        $option .= " <select id='pilih_opsi_cetak' style='color:black; padding:2px; border-radius:3px; outline:none;' onchange=\"
                        var currentUrl = window.location.href;
                        var newUrl;
                        var val = this.value;
                        if (currentUrl.indexOf('opsi_cetak=') > -1) {
                            // Jika parameter sudah ada, ganti nilainya
                            newUrl = currentUrl.replace(/opsi_cetak=[^&]+/, 'opsi_cetak=' + val);
                        } else {
                            // Jika belum ada, tambahkan parameter (cek apakah sudah ada '?' atau belum)
                            var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
                            newUrl = currentUrl + separator + 'opsi_cetak=' + val;
                        }
                        window.location.href = newUrl;
                    \">";

        $option .= " <option value='1' ".($opsi_cetak == 1 ? "selected" : "").">Opsi 1 (Project & Total)</option>";
        $option .= " <option value='2' ".($opsi_cetak == 2 ? "selected" : "").">Opsi 2 (Rincian Barang)</option>";
        $option .= " <option value='3' ".($opsi_cetak == 3 ? "selected" : "").">Opsi 3 (Rekap QTY & Satuan)</option>";
        $option .= " <option value='4' ".($opsi_cetak == 4 ? "selected" : "").">Opsi 4 (Grouping 1 LOT)</option>";
        $option .= " </select>";
        $option .= " </span>";
        // =========================================================================
        //
        //

        $option .= "</div>";
        $option .= "<script>
            
            function sendSimpel() {
                var checkbox = document.getElementById('lysimpel');
                var lgValue = checkbox.checked ? 1 : 0;
    
                console.log('lgValue', lgValue)
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
                if(lgValue == 1){                
                    $('.lysimpel').removeClass('hidden');
                    $('td.bottom-borderless').attr('colspan', 5);
                }
                else {                
                    $('.lysimpel').addClass('hidden');
                    $('td.bottom-borderless').attr('colspan', 5);
                }
            }
            // var ceksimpel = $('#lysimpel');
            var ceksimpel = document.getElementById('lysimpel');
            ceksimpel.addEventListener('change',sendSimpel);
            // var ceklengkap = document.getElementById('lylengkap');
            // ceklengkap.addEventListener('change',sendLengkap);

            // Fungsi untuk mengirimkan nilai lg berdasarkan status checkbox
            function sendLGValue() {
                var checkbox = document.getElementById('myCheckbox');
                var lgValue = checkbox.checked ? 1 : 0;
    
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
                if(lgValue == 1){                
                    $('#logo').removeClass('hidden');
                    // $('#cprofile').removeClass('hidden');
                    // $('#isi_cprofile').removeClass('hidden');
                }
                else {                
                    $('#logo').addClass('hidden');
                    // $('#cprofile').addClass('hidden');
                    // $('#isi_cprofile').addClass('hidden');
                }
            }
    
            // Tambahkan event listener untuk checkbox
            var checkbox = document.getElementById('myCheckbox');
            checkbox.addEventListener('change', sendLGValue);
            
            function sendAddrValue() {
                var checkbox = document.getElementById('myCheckAddr');
                var lgValue = checkbox.checked ? 1 : 0;
    
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
                if(lgValue == 1){                
                    $('#logo').removeClass('hidden');
                    $('#cprofile').removeClass('hidden');
                    $('#myCheckbox').attr('value', 'true');
                }
                else {                
                    $('#logo').addClass('hidden');
                    $('#cprofile').addClass('hidden');
                    // $('#isi_cprofile').addClass('hidden');
                }
            }
    
            // Tambahkan event listener untuk checkbox
            var checkbox = document.getElementById('myCheckAddr');
            checkbox.addEventListener('change', sendAddrValue);
            
            function sendRKValue() {
                var checkboxrk = document.getElementById('checkboxrekening');
                var lgValue = checkboxrk.checked ? 1 : 0;
            
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
                if(lgValue == 1){                
                    $('#rekeningbank').removeClass('hidden');
                }
                else {                
                    $('#rekeningbank').addClass('hidden');
                }
            }
            var checkboxrekening = document.getElementById('checkboxrekening');
            checkboxrekening.addEventListener('change', sendRKValue);
            
        </script>";
//        arrPrintPink($signStr);
//        arrPrintPink($signStr);
//        cekHere("$pihakProfile");
        $arrTags["cb_nama"] = isset($cb_nama) ? $cb_nama : "";
        $arrTagsPrint = array(
            "tanggal_transaksi" => $tanggal_transaksi,
            // "lg_hidden"    => $lg_hidden,
            "option" => $option,
            "companyProfile" => $cpStr,
            "pihakProfile" => $pihakProfile,
            "fixedElements" => $fixedElements,
            "customerDetails" => $customerDetails,
            "deliveryDetails" => $deliveryDetails,
            "noteDetails" => $noteDetails,
            "content" => $itemStr,
            "content_3" => $inwordStr,
            "statik_note" => $statik_note,
            "qrCodeNota" => isset($qrCodeNota) ? $qrCodeNota : "",
            "time_cetak" => "print @" . dtimeNow('d M Y H:i'),
            // "footer" => $footer,
            "footer" => "",
            "lain_lain" => "",
            "salesman" => "",
            "fakturElement" => $contenFaktu,
//            "customerSignitures" => "",
//            "sign_1" => "",
        );
        $arrTagsPrint = $arrTagsPrint + $signStr;
//        arrPrintPink($arrTagsPrint);
//        $p->addTags(
//            array(
//                "tanggal_transaksi" => $tanggal_transaksi,
//                // "lg_hidden"    => $lg_hidden,
//                "option" => $option,
//                "companyProfile" => $cpStr,
//                "pihakProfile" => $pihakProfile,
//                "fixedElements" => $fixedElements,
//                "customerDetails" => $customerDetails,
//                "deliveryDetails" => $deliveryDetails,
//                "noteDetails" => $noteDetails,
//                "content" => $itemStr,
//                "content_3" => $inwordStr,
//                "statik_note" => $statik_note,
//                "qrCodeNota" => isset($qrCodeNota) ? $qrCodeNota : "",
//                "time_cetak" => "print @" . dtimeNow('d M Y H:i'),
//                "footer" => $footer,
//                "lain_lain" => "",
//                "salesman" => "",
////                "customerSignitures"            => "",
////                "sign_1"            => "",
//            ) + $signStr
//        );
        $p->addTags($arrTagsPrint);
        $p->render();

        break;
}

