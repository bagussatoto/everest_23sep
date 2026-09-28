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
            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
            $elementLabels["content_5"] = $contenStr5;
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
        // arrPrintWebs($items2);
        // matiHEre();
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                //arrPrintKuning($arrSubDetailDataKolom);
                foreach ($itemLabels as $key => $label) {
                    $width = "";
                    if ($key == "produk_nama") {
                        $width = "width='500px'";
                    }
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;' $width>";
                    if (is_array($label)) {
                        $contentStr .= $label["label"];
                    }
                    else {
                        $contentStr .= $label;
                    }
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();

                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));

                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $add_data_val = "";
                        if (is_array($label)) {
                            $add_data_val = isset($items[$id][$label["addKey"]]) ? "<br>" . $items[$id][$label["addKey"]] : "";
                        }
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "$add_data_val </td>";
                    }

                    $contentStr .= "</tr>";

                    if (isset($arrSubDetailDataKolom["nama"][$id])) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td>&nbsp;</td>";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style='font-size:12px;'>";
                        foreach ($arrSubDetailDataKolom["nama"][$id] as $sku => $sku_data) {
                            $contentStr .= "$sku : " . formatField_he_format("serial", $sku_data) . "<br>";
                        }
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                    }
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
                                if (count($sumDppTax) > 0) {
                                    // cekHijau($key);
                                    if (isset($receiptAddSDppTax[$key])) {
                                        $colspan_1 = $colspan2 - 2;
                                        foreach ($receiptAddSDppTax[$key] as $key_dpp => $alias_dpp_key) {
                                            $contentStr .= "<td colspan='$colspan_1' class='text-right'>$alias_dpp_key</td>";
                                            $contentStr .= "<td>" . formatField_he_format("subtotal", $sumDppTax[$key][$key_dpp]) . "</td>";
                                        }
                                        $colspan2_1 = "1";
                                    }
                                    else {
                                        $colspan2_1 = $colspan2;
                                    }
                                }
                                else {
                                    $colspan2_1 = $colspan2;
                                }
                                $contentStr .= "<td colspan='$colspan2_1' class='text-right'>$label</td>";
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
                if (isset($free_produk) && ($free_produk != NULL)) {
                    $str = "<div>";
                    $str .= "<span style='font-size:15px;font-weight:bold;'>Promo Supplier:</span>";
                    $str .= "<div>";
                    $str .= $free_produk;
                    $str .= "</div>";
                    $str .= "</div>";
                    $contentStr .= $str;
                }

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
                //                arrPrint($sumRows2);
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

        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

         cekHere($template);
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
        // arrPrintHijau($mainValues);
        // arrPrint($mainElements);
        // arrPrint($main);
        // cekHere($jenisTr);

        $strSupplierInv = isset($mainValues['description_main_followup']) ? $mainValues['description_main_followup'] : "";
        $strNotes = isset($mainValues['description']) ? $mainValues['description'] : "";
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
            $cpStr .= "<div>" . "NPWP : <b>" . $companyProfile->npwp . "</b></div>";
            $cpStr .= "</div>";
        }
        else {
            $cpStr .= "<div id='isi_cprofile' class='dborder-cek' style='font-size: 1em;'>";
            // $cpStr .= "<div><span class='no-margin text-uppercase text-bold' style='text-decoration: underline;font-size: 1.1em;'>" . $companyProfile->alias . "</span></div>";
            // $cpStr .= "<div style=';' class='text-uppercase'>air conditioner  - spare part</div>";
            $cpStr .= "<div style=''>" . $companyProfile->alamat . "</div>";
            $cpStr .= "<div>" . $companyProfile->kecamatan . " " . $companyProfile->kabupaten . " " . $companyProfile->propinsi . " " . $companyProfile->kodepos . "</div>";
            $cpStr .= "<div>" . "Telepon : <b>" . $companyProfile->tlp . "</b></div>";
            $cpStr .= "<div>" . "NPWP : <b>" . $companyProfile->npwp . "</b></div>";
            $cpStr .= "</div>";
        }
//            cekHere("[$jenisTr]");
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

        /* --------------------------------------------------
         * data pihak lain
         * -------------------------------------------------------*/
        $customerDetails = "";
        $customerDetails .= "<div class='panel-heading text-left'>" . $elementConfigs['customerDetails']['label'] . "</div>";

        $customerDetails .= "<div class='panel-body'>";
        $customerDetails .= "<table class='table table-condensed'>";
        $vitem_0 = blobDecode($mainElements['vendorDetails']['contents']);
        //baca jika ada elementvendorUp -> up to .jika tidak ada berarti transaksi lama
        if (isset($mainElements['vendorUp'])) {
            $contact_person = $mainElements['vendorUp']['value'];
        }
        else {
            $contact_person = $vitem_0['contact_person'];
        }

        $vitem_1 = blobDecode($mainElements['deliveryDetails']['contents']);
        $paymentMethods = $mainElements['paymentMethod'];
        $shippingDates = $mainElements['shippingDate'];
//        arrPrint($mainElements);
        // arrPrint($vitem_0);
        // arrPrint($vitem_1);
        // arrPrint($paymentMethod);
        // arrPrint($mainElements);

        // arrPrint($elementConfigs['customerDetails']['usedFields']);
        $nama = $vitem_0['nama'];
        $alamat_1 = $vitem_0['alamat_1'];
        $kelurahan = $vitem_0['kelurahan'];
        $kecamatan = $vitem_0['kecamatan'];
        $kabupaten = $vitem_0['kabupaten'];
        $propinsi = $vitem_0['propinsi'];
        $tlp_1 = $vitem_0['tlp_1'];


        $alamat_konsumen = (strlen($alamat_1) > 3 ? $alamat_1 : '');
        $alamat_konsumen .= (strlen($kelurahan) > 2 ? " $kelurahan," : '');
        $alamat_konsumen .= (strlen($propinsi) > 2 ? " $propinsi," : '');

        $pihakProfile = "<div class='box box-danger box-solid'>";
        $pihakProfile .= "<div class='box-header with-border'>";
        $pihakProfile .= "<h3 class='box-title text-uppercase'>supplier</h3>";
        $pihakProfile .= "</div>";
        $pihakProfile .= "<div class='box-body'>";

        $pihakProfile .= "<div class='text-uppercase text-bold'>$nama</div>";
        $pihakProfile .= "<div class='text-capitalize'>$alamat_konsumen</div>";
        if (strlen($contact_person) > 6) {
            // $pihakProfile .= "<div class=''>Up To: $contact_person </div>";
        }
        $pihakProfile .= "<hr class='no-margin no-padding'>Up To: $contact_person";
        if (strlen($tlp_1) > 6) {
            $pihakProfile .= "<div class=''>Phone: $tlp_1 </div>";
        }
        $pihakProfile .= "</div>";
        $pihakProfile .= "</div>";

        $nomer = $dataFixElemens[$elementFixedConfig['nomer']];

        // arrPrintPink($mainValues);
        $trIdKhusus = $mainValues['transaksi_id'];
        // cekHere($trIdKhusus);


        // $nomer = $dataFixElemens[$elementFixedConfig['nomer_top']];

        $tgl_trans = isset($dataFixElemens[$elementFixedConfig['dtime']]) ? $dataFixElemens[$elementFixedConfig['dtime']] : $dataFixElemens['Date'];
        $tanggal_transaksi = "<div class='bborder-cek '>";

        $tanggal_transaksi .= "<div id='title' class='text-uppercase' style='float: left;text-align: left; overflow: hidden;margin-top: -15px;'>";
        $tanggal_transaksi .= "<h4 class='no-margin text-renggang-55 text-bold'>$title</h4>";
        $tanggal_transaksi .= "<div style='border: 1px solid #000f79;padding: 3px 5px;margin-top: 5px;'>";
        $tanggal_transaksi .= "<p class='no-margin'>Date: $tgl_trans</p>";
        $tanggal_transaksi .= "<p class='no-margin'>No: $nomer <i class='hidden-on-print' style='color: #FFFFFF'>$trIdKhusus</i></p>";
        $tanggal_transaksi .= "</div>";
        $tanggal_transaksi .= "</div>";

        $title_alt = "<span class='text-renggang-5 text-bold'>Proforma Invoice</span> <small>tidak untuk penagihan</small>";
        $nomer_alt = str_replace('5822so', '5822spd', $nomer);
        // cekHere("$nomer");
        // cekHijau($nomer_alt);
        $tanggal_transaksi .= "<div id='title_alt' class='hidden text-uppercase' style='float: left;text-align: left; overflow: hidden;'>";
        $tanggal_transaksi .= "<h4 class='no-margin'>$title_alt</h4>";
        $tanggal_transaksi .= "<p class='no-margin'>$nomer_alt</p>";
        $tanggal_transaksi .= "</div>";

        // $tanggal_transaksi .= "<div>";
        // $tanggal_transaksi .= "</div>";
        $tanggal_transaksi .= "</div>";
        //        arrPrint($dataFixElemens);


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

        $billingDetails = "";
        /*tagihan billing*/


        /* --------------------------------------------------
         * data deliveri
         * -------------------------------------------------------*/
        $deliveryDetails = "";
        // cekHere($mainValues);
        // cekHere($shippingMethod);
        // arrPrint($mainElements);
        $cara_kirim = $mainValues['shippingMethod__label'];
        $shippingMethod = 1;
        if ($shippingMethod == 1) {

            $vitem_0 = blobDecode($mainElements['deliveryDetails']['contents']);

            $alamat = $vitem_0['alamat'];
            $alamat_kirim = $alamat;
            $alias = $showElementConfigs['deliveryDetails']['usedFields']['alias'];

            // $deliveryDetails .= "</div>";

            $deliveryDetails = "<div class='box box-danger box-solid'>";
            $deliveryDetails .= "<div class='box-header with-border'>";
            $deliveryDetails .= "<h3 class='box-title text-uppercase'>DELIVERY ADDRESS</h3>";
            $deliveryDetails .= "</div>";
            $deliveryDetails .= "<div class='box-body'>";
            $deliveryDetails .= "<div class='text-capitalize'>$alamat_kirim</div>";
            $deliveryDetails .= "<hr class='no-margin no-padding'>Up To: " . $vitem_0['alias'];
            $deliveryDetails .= "<hr class='no-margin no-padding'>Phone: " . $vitem_0['tlp'];
            $deliveryDetails .= "</div>";
            $deliveryDetails .= "</div>";
        }
        else {
            $deliveryDetails .= "<div class='panel-heading text-left text-capitalize'>Logistik: $cara_kirim</div>";
        }

        // arrPrintHijau($shippingDates);
        $tglKirim = $shippingDates["value"];
        $tglKirim_f = ($tglKirim != NULL) ? formatTanggal($tglKirim, 'd F Y') : "-";
        $shippingDate = "<div class='box box-danger box-solid'>";
        $shippingDate .= "<div class='box-header with-border'>";
        $shippingDate .= "<h3 class='box-title text-uppercase'>delivery date</h3>";
        $shippingDate .= "</div>";
        $shippingDate .= "<div class='box-body'>";
        $shippingDate .= "<div class='text-capitalize'>$tglKirim_f</div>";
        $shippingDate .= "</div>";
        $shippingDate .= "</div>";

        // arrPrintHijau($paymentMethods);
        $paymentMethonya = ($paymentMethods['labelValue'] != NULL) ? $paymentMethods['labelValue'] : "-";
        $paymentMethod = "<div class='box box-danger box-solid'>";
        $paymentMethod .= "<div class='box-header with-border'>";
        $paymentMethod .= "<h3 class='box-title text-uppercase'>payment method</h3>";
        $paymentMethod .= "</div>";
        $paymentMethod .= "<div class='box-body'>";
        $paymentMethod .= "<div class='text-capitalize'>$paymentMethonya</div>";
        $paymentMethod .= "</div>";
        $paymentMethod .= "</div>";

        if (strlen($strSupplierInv) > 0) {
            $paymentMethod = "<div style='border: 1px solid #000f79;padding: 10px 5px;margin-top: 5px;'>";
            $paymentMethod .= "<p class='no-margin'>Payment Method: <b class='text-uppercase'>$paymentMethonya</b></p>";
            $paymentMethod .= "<p class='no-margin'>No. Supplier Inv: <b>$strSupplierInv</b> </p>";
            $paymentMethod .= "</div>";
        }

        // arrPrintHijau($fixedElements);
        // arrPrintHijau($mainValues);
        /* --------------------------------------------------
         * data note catatan
         * -------------------------------------------------------*/
        $noteDetails = "";
        if (isset($mainValues['description']) && strlen($mainValues['description']) > 20) {
            // cekHere("didii");
            $noteDetails .= "<div class='alert alert-danger'>";
            $noteDetails .= "<div class='text-left text-renggang-5'>$notes_label</div>";
            // $noteDetails .= "<div class='panel-heading text-left'>"."notes"."</div>";

            $noteDetails .= "<div class=''>";
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

            $notes_isi = $mainValues['description'];
            $noteDetails .= "<tr>";
            $noteDetails .= "<td class='tbl-nilai' title='$kitem'>$notes_isi</td>";
            $noteDetails .= "</tr>";
            $noteDetails .= "</table>";
            $noteDetails .= "</div>";
            $noteDetails .= "</div>";
        }


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
                $tblShow .= "<th class='$itemLabel'>$itemLabel</th>";
            }
        }
        $tblShow .= "</tr>";
        // arrPrint($arrSubDetailDataKolom);
        arrPrint($itemLabels);
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
                // cekMerah($nilai . " | " . $itemkey);
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
                $tblShow .= "<tr line=" . __LINE__ . " class='serial_uye'>";
                $tblShow .= "<td>&nbsp;</td>";
                $tblShow .= "<td colspan='" . sizeof($itemLabels) . "' style='font-size:12px;'>";
                foreach ($arrSubDetailDataKolom["nama"][$id] as $sku => $sku_data) {
                    $tblShow .= "<b>$sku</b>: <br>" . formatField_he_format("serial", $sku_data) . "<br>";
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


            if (!isset($summary["nett1"])) {
                $summary["nett1"] = 0;
            }
            $summary["nett1"] += $item['nett1'] * $item['jml'];

            if (!isset($summary["ppn_out_bulat"])) {
                $summary["ppn_out_bulat"] = 0;
            }
            $summary["ppn_out_bulat"] += $item['xppn'] * $item['jml'];

            if (!isset($summary["grand_pembulatan"])) {
                $summary["grand_pembulatan"] = 0;
            }
            $summary["grand_pembulatan"] += $item['nett1nppn'] * $item['jml'];
        }

        /* -------------------------
         * jumlah bawah
         * -----------------------*/
        $colspan = count($itemLabels);
        // cekHere("$colspan");
        // arrPrintHijau($mainValues);
        // arrPrintHijau($receiptSumFieldsLayout);
        $simpel_sum = array(
            "nett1",
            "ppn_out_bulat",
            // "produk_kode",
        );
        // arrPrintHijau($confLayout);

        /* -----------------------------------
         * point dibuang dari sumfield
         * ---------------------------------------*/
        unset($receiptSumFieldsLayout['point_transaksi']);
        unset($receiptSumFieldsLayout['point_saldo_akhir']);
        if (isset($mainValues["diskon_kategori_unit"]) && $mainValues["diskon_kategori_unit"] == 0) {
            unset($receiptSumFieldsLayout['lastNett']);
            unset($receiptSumFieldsLayout['diskon_kategori_unit']);
        }

        // $receiptSumFieldsLayout['lastNett'] = "total";
        // $receiptSumFieldsLayout['diskon_kategori_unit'] = "Diskon";
        // arrPrintHijau($receiptSumFieldsLayout);
        // arrPrintHijau($mainValues);
        if (!isset($mainValue['diskon_kategori_unit'])) {
            unset($receiptSumFieldsLayout['diskon_kategori_unit']);
            unset($receiptSumFieldsLayout['lastNett']);
        }

        //        arrPrintHijau($mainValues);
        // arrPrintHijau($summary);
        $mainValues = array_merge($mainValues, $summary);

        //        arrPrintWebs($mainValues);
        foreach ($receiptSumFieldsLayout as $kitem => $litem) {

            $cssClass = str_replace(" ", "_", strtolower($litem));
            $cl_lysimpel = in_array($kitem, $simpel_sum) ? "lysimpel" : "";

            $tblShow .= "<tr class='table-botom-borderless $cl_lysimpel' title='$kitem'>";
            $nilai = $mainValues[$kitem];
            // $nilai = $summary[$kitem];
            $nilai_f = number_format($nilai);
            // cekHere("$kitem : $nilai");
            //
            $tblShow .= "<td colspan='$colspan' align='right' class='bottom-borderless $cssClass'>$litem</td>";
            $tblShow .= "<td class='nilai_nota $cssClass' title='$kitem'>$nilai_f</td>";
            //     }
            $tblShow .= "</tr>";
        }


        $itemStr = "";
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
            .table>tbody>tr>td, .table>tbody>tr>th{
                padding: 0 5px;
            }
            body{
                font-family: Arial;
                font-size: 12px !important;
            }
            .ttd{none}
            .bg-success{
               background-color: #0a53ff91 !important;               
            }
            .box-header{
                padding: 3px 10px !important;
                background-color: #0a53ff91 !important;
                background: #0a53ff91 !important;
                color: unset !important;
            }
            .box.box-solid.box-danger {
                border: 1px solid #c0c0c0 !important;
            }
            .alert{
                padding: 5px 10px !important;
            }
            .alert-danger{
                color: #000 !important;
                background-color: #ffffff !important;
                border-color: #c0c0c0 !important;
            }
</style>";
        // $itemStr .= "$billingDetails";
        $itemStr .= "<div class='panel-contentt'>";
        $itemStr .= "<table class='table table-bordered'>";
        $itemStr .= $tblShow;
        $itemStr .= "</table>";
        $itemStr .= "</div>";

        // arrPrintWebs($mainValues);
        $step_number = $mainValues["step_number"];
        /* -----------------------------------------------------------
         * diskon 1-2-3
         * -----------------------------------------------------------*/
        $arrDiskon = array(
            "diskon_1_nilai" => array(
                "label" => "diskon 1"
            ),
            "diskon_2_nilai" => array(
                "label" => "diskon 2"
            ),
            "diskon_3_nilai" => array(
                "label" => "diskon 3"
            ),
            "diskon_4_nilai" => array(
                "label" => "diskon 4"
            ),
            "diskon_5_nilai" => array(
                "label" => "diskon 5"
            ),
        );
        $content_2 = "";

// cekHere($step_number);
        if (isset($confLayout["receiptShowDiskon"])) {
            if (isset($confLayout["receiptShowDiskon"][$step_number]) && $confLayout["receiptShowDiskon"][$step_number] == true) {
                $content_2 .= "<div class='border-cekk overflow-h' style='margin-top: 10px;'>";
                foreach ($arrDiskon as $keyDiskon => $paramsDiskon) {
                    $label = isset($paramsDiskon["label"]) ? $paramsDiskon["label"] : $keyDiskon;
                    $nilai = $mainValues[$keyDiskon];
                    $nilai_f = formatField_he_format("harga", $nilai);
                    if ($nilai > 0) {
                        $content_2 .= "<div class='col-md-2 col-xs-2 alert alert-danger'>$label <b>$nilai_f</b></div>";
                    }
                }
                $content_2 .= "</div>";
            }
        }

        if (isset($free_produk) && ($free_produk != NULL)) {
            $str = "<div class='container-fluid' style='margin-bottom: 32px;'>";
            $str .= "<div class='row'>";
            $str .= "<div class='col-md-3 col-lg-3 col-sm-7 col-xs-7 no-padding'>";
            $str .= "<span class='fa fa-book text-bold' style='margin-bottom: 18px;font-size: 16px;'> Promo Free Produk</span>";
            $str .= "<div>";
            $str .= $free_produk;
            $str .= "</div>";
            $str .= "</div>";
            $str .= "</div>";
            $str .= "</div>";
            $content_2 .= $str;
        }

        // arrPrint($terbilangParams);
        // arrPrintPink($layoutElementConfigs);
        /* ---------------------------------------------------
         * inword
         * ---------------------------------------------------*/
        $terbilangParams = isset($confLayout['terbilangSumFields']) ? $confLayout['terbilangSumFields'] : "";
        // arrPrintPink($terbilangParams);
        if ($terbilangParams != "") {
            foreach ($terbilangParams as $terkey => $terbilangParam) {
                cekHere($terkey);
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

        /* ---------------------------------------------
         * sign
         * pelabelan diatus dari step coUI
         * ---------------------------------------------*/
        // arrPrintHijau($signValues_0);
        // arrPrintKuning($signValues);
        // arrPrintHijau($fixedSignConfig);
        $signStr = array();
        if (sizeof($signValues) > 0) {
            foreach ($signValues as $key => $eSpec) {
                $tampil_ky = $fixedSignConfig[$key];
                $tampil = $tampil_ky == false ? "hidden" : "";

                $ttd_class = $tampil == "hidden" ? "" : "ttd";
                $signVar = "";
                $slabel = isset($eSpec['label']) && strlen($eSpec['label']) > 2 ? $eSpec['label'] : $tampil_ky['label'];
                $scontents = ($eSpec['contents'] != NULL) ? $eSpec['contents'] : "_____________";

                $signVar .= "<div id='ttd_$key' style='floatr: left;' title='$key' class='$key $tampil $ttd_class text-uppercase'>";
                $signVar .= "<div >$slabel</div>";
                $signVar .= "<div style='min-height: 55px;'>&nbsp;</div>";
                $signVar .= "<div class='text-uppercase pic' style='text-decoration: underline;'>($scontents)</div>";
                // $signVar .= "<div class='text-capitalize'>(_______________)</div>";
                $signVar .= "</div>";


                $signStr[$key] = $signVar;
            }
        }
        if (count($signValues_0) > 0) {
//             foreach ($signValues_0 as $key => $eSpec) {
//                 $tampil_ky = $fixedSignConfig[$key];
//                 $tampil = $tampil_ky == false ? "hidden" : "";
//
//                 $ttd_class = $tampil == "hidden" ? "" : "ttd";
//                 $signVar = "";
//                 $slabel = $eSpec['label'];
//                 $scontents = ($eSpec['contents'] != NULL) ? $eSpec['contents'] : "_____________";
//
//                 $signVar .= "<div id='ttd_$key' style='floatr: left;' title='$key' class='$key $tampil $ttd_class text-uppercase'>";
//                 $signVar .= "<div >$slabel</div>";
//                 $signVar .= "<div style='min-height: 55px;'>&nbsp;</div>";
//                 $signVar .= "<div class='text-uppercase pic' style='text-decoration: underline;'>($scontents)</div>";
//                 // $signVar .= "<div class='text-capitalize'>(_______________)</div>";
//                 $signVar .= "</div>";
//
//
//                 $signStr[$key] = $signVar;
//             }
        }
        // arrPrintPink($signStr);
        // arrPrintPink($mainValues);
        //--------------------------------------------------------------end-----------
        $statik_note_datas = array(
            "penagihan hanya dengan faktur asli",
            "pembayaran dengan rekening/giro dianggap lunas jika telah diuangkan",
            "barang yang telah dibeli tidak dapat ditukar / dikembalikan",
        );

        $statik_note = "";
        $statik_note .= "<div class='alert alert-danger' style='min-height: 90px'>";
        // cekKuning(nl2br($strNotes));
        $statik_note .= "Notes:<br>";
        $statik_note .= nl2br($strNotes);
        $statik_note .= "</div>";

        $paymentMethod__label = $mainValues['paymentMethod__label'];
        $footer = "";
        $footer .= "<div class='col-md-8 col-xs-8 text-uppercase' id='rekeningbank'>";
        $footer .= "transfer via <b>BCA 8830713132</b> an. PT. Everest Electronic<br>";
        $footer .= "transfer via <b>MANDIRI 1640009779887</b> an. PT. Everest Electronic";
        $footer .= "</div>";
        $footer .= "<div style='float: right;margin-right: 5px;margin-top: 20px;' class='text-uppercase text-right col-md-3'>";
        $footer .= "metode pembayaran : $paymentMethod__label<br>";
        $footer .= "$pembayaran_status";
        $footer .= "$gudang_status";
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
        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . $template);

        //         arrPrintWebs($arrTags);
        //         cekHijau("$template");
        // cekHijau("$jenisTr");
        // cekHijau("$template");

        /* ----------------------------------------------------------------
         *
         * ----------------------------------------------------------------*/
        $cp_editor = MODUL_PATH . "Printing/editorPrint";
        $option = "";
        $option .= "<div class='hidden-on-print text-uppercase' style='background-color: black;padding-left: 20px;'>";
        // $option .= "<label class='text-white'><input type='radio' name='layout' id='lylengkap' checked> lengkap</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' name='layout' id='sku'> SKU</label> ";
        $option .= "<label class='text-white'><input type='checkbox' name='serial' id='lyserial' checked> tampilkan serial number</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' name='layout' id='lysimpel' checked> lengkap/simple layout</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' id='myCheckbox' checked> logo </label> ";
        // $option .= "<label class='text-white'><input type='checkbox' id='myCheckAddr' checked> alamat perusahaan</label> ";
        // $option .= " <label class='text-white'><input type='checkbox' id='checkboxrekening' checked> rekening bank</label> <a class='text-warning' href='$cp_editor' data-toggle='modal' data-target='#myModal'><i class='glyphicon glyphicon-pencil'></i></a>";
        $option .= " <button type='button' id='print' onclick=\"window.print();\" title='print/cetak'> <i class='glyphicon glyphicon-print'></i></button>";
        // $option .= " <label class='text-white'><input type='checkbox' id='checkboxTitle' checkedd> Proforma</label>";

        //region tombol kirim whatsapp sender
        if($whatsappSender['status_wa']==1){
            $option .= "&nbsp;&nbsp;&nbsp; <button type='button' id='wa_sender' style='background-color: #25D366;color:white;font-size: 12px;' class='text-bold' onclick=\"klikSendWa('".base64_encode(json_encode($whatsappSender))."')\" title='kirim wa invoice, perhatian: proforma invoice tidak bisa dikirim'> <i class='fa fa-whatsapp'></i> Kirim WA</button>";
        }
        else{
            $option .= "&nbsp;&nbsp;&nbsp; <button type='button' id='wa_sender' style='background-color: #ccc; color:#666; font-size: 12px; cursor: not-allowed;' class='text-bold' disabled title='WA belum aktif, atau lihat paket subscribe WA anda mungkin telah habis, dipengaturan Integrasi WA.'><i class='fa fa-whatsapp'></i> Kirim WA</button>";
        }

        $option .= "<script>
            var api_kirim_wa = 'https://cdn.mayagrahakencana.com/images/WASender/terima';
            var api_kirim_pdf = 'https://cdn.mayagrahakencana.com/images/FilesBridge/terimaPDF';
            async function klikSendWa(encodedOption) {
                const option = JSON.parse(atob(encodedOption));
                const pdfUrl = option.cdn_base_url;
                const renderUrl = option.url;
                const unik = option.unik_id_wa;
                const secret = option.secret_id_wa;
                const aplikasi = option.aplikasi;
                const log = option.log;
                try {
                    const headResponse = await fetch(pdfUrl, { method: 'HEAD' });
                    if (!headResponse.ok) {
                        throw new Error('PDF not found');
                    }
                    return showSwalForm(option.no_tujuan || '', pdfUrl, unik, secret, aplikasi, log);
                }
                catch (err) {
                    top.swal({
                        title: 'Menyiapkan File...',
                        html: 'generate PDF, mohon tunggu...<br><br><span style=\'font-size:14px;color:red;\'>setelah PDF selesai dibuat, <br>Anda bisa memasukan no WA tujuan dan text tambahan jika diperlukan.<br>customer yang telah menyimpan nomor hp, akan otomatis sebagai penerima.</span>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        onOpen: async () => {
                            top.swal.showLoading();
                            const swal = top.swal;
                            try {
                                await fetch(api_kirim_pdf, {
                                  method: 'POST',
                                  headers: {
                                     'Content-Type': 'application/json',
                                  },
                                  body: JSON.stringify({
                                     encodedOption: encodedOption,
                                  }),
                                })
                                .then((response) => {
                                    if (!response.ok) {
                                       throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                  })
                                  .then((data) => {
                                     console.log(data);
                                  })
                                  .catch((error) => {
                                     console.error('Error:', error);
                                  });

                                await new Promise(resolve => setTimeout(resolve, 10000));
                                const retry = await fetch(pdfUrl, { method: 'HEAD' });
                                if (!retry.ok) throw new Error('PDF masih belum tersedia setelah render.');
                                swal.close();
                                return showSwalForm(option.no_tujuan || '', pdfUrl, unik, secret, aplikasi, log);
                            }
                            catch (err) {
                                swal.close();
                                top.swal('Gagal', 'Gagal generate PDF: ' + err.message, 'error');
                            }
                        }
                    });
                }
            }

            function showSwalForm(no_tujuan, pdfUrl, unik_id, secret_id, aplikasi, log) {
                top.swal({
                    title: 'Kirim Pesan WA',
                    html: `
                        <input id=\"nomor_tujuan\" class=\"swal2-input\" placeholder=\"Nomor Tujuan 62/08 (pisahkan dengan koma)\" value=\"`+no_tujuan+`\">
                        <select id='nomor_pilihan' multiple='multiple' style='display:none;width: 100%;'></select>
                        <textarea id=\"message\" class=\"swal2-textarea\" placeholder=\"Pesan\">Berikut invoice Anda: </textarea>
                        <input type=\"hidden\" id=\"media_url\" value=\"`+pdfUrl+`\">
                        <div style=\"text-align:center;margin-top:10px;\">
                            <iframe src=\"`+pdfUrl+`\" width=\"100%\" height=\"145px\" style=\"border:1px solid #ccc;border-radius:6px;\"></iframe>
                        </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Kirim',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onOpenz: function(){

                        const options = [
                          { id: 'satu', text: 'Satu' },
                          { id: 'dua', text: 'Dua' },
                          { id: 'tiga', text: 'Tiga' },
                        ];

                        options.forEach(option => {
                          const newOption = new Option(option.text, option.id, false, false);
                          $('#pilihan').append(newOption);
                        });

                        top.$('#nomor_pilihan').select2({
                            tags: true,
                            placeholder: 'Pilih atau tambah baru...',
                            tokenSeparators: [',', ' ']
                        });
                    },
                    preConfirm: () => {
                        const nomor = document.getElementById('nomor_tujuan').value;
                        const pesan = document.getElementById('message').value;
                        const media = document.getElementById('media_url').value;
                        return send_test_advanced(nomor, pesan, media, unik_id, secret_id, aplikasi, log);
                    }
                });
            }

            function send_test_advanced(nomor_tujuan, message, media_url, unik_id, secret_id, aplikasi, log) {
                top.swal({
                    title: 'Sedang mengirim...',
                    text: 'Harap menunggu...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onOpen: () => {
                        top.swal.showLoading();
                    }
                });

                fetch(api_kirim_wa, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nomor_tujuan: nomor_tujuan,
                        message: message,
                        media_url: media_url,
                        unik_id: unik_id,
                        secret_id: secret_id,
                        log: log,
                        aplikasi: aplikasi,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP status bukan 200: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respons WA:', data);
                    top.setTimeout(() => {
                        if (data.status === 'ok') {
                            top.swal({
                                title: 'Sukses!',
                                text: data.msg || 'Pesan berhasil dikirim.',
                                icon: 'success'
                            });
                        } else {
                            top.swal({
                                title: 'Gagal!',
                                text: data.msg || 'Server mengembalikan status gagal.',
                                icon: 'error'
                            });
                        }
                    }, 2500);
                })
                .catch(error => {
                    console.error('Error kirim WA:', error);
                    top.setTimeout(() => {
                        top.swal({
                            title: 'Error!',
                            text: 'Gagal mengirim pesan: ' + error.message,
                            icon: 'error'
                        });
                    }, 2500);
                });
            }
        </script>";
        //endregion tombol kirim whatsapp sender


        $option .= "</div>";
        $option .= "<script>
            function sendSerial() {
                var checkbox = document.getElementById('lyserial');
                var lgValue = checkbox.checked ? 1 : 0;
                var colspan = $('td.bottom-borderless').attr('colspan');
                console.log('lgValue', lgValue)
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai
                // var new_colspan = colspan -1;           
                if(lgValue == 1){            
                    // var new_colspan = Number(colspan) + Number(2);
                    console.log('masuk');
                    $('.serial_uye').removeClass('hidden');
                    // $('td.bottom-borderless').attr('colspan', new_colspan);
                }
                else {                
                    // var new_colspan = colspan - 2;
                    $('.serial_uye').addClass('hidden');
                    // $('td.bottom-borderless').attr('colspan', new_colspan);
                }
            }
            // var ceksimpel = $('#lysimpel');
            var cekserial = document.getElementById('lyserial');
            cekserial.addEventListener('change',sendSerial);
            
            // function sendSimpel() {
            //     var checkbox = document.getElementById('lysimpel');
            //     var lgValue = checkbox.checked ? 1 : 0;
            //     var colspan = $('td.bottom-borderless').attr('colspan');
            //     console.log('lgValue', lgValue)
            //     // Kirim nilai lg ke server atau lakukan tindakan yang sesuai
            //     // var new_colspan = colspan -1;           
            //     if(lgValue == 1){            
            //         var new_colspan = Number(colspan) + Number(2);
            //         $('.lysimpel').removeClass('hidden');
            //         $('td.bottom-borderless').attr('colspan', new_colspan);
            //     }
            //     else {                
            //         var new_colspan = colspan - 2;
            //         $('.lysimpel').addClass('hidden');
            //         $('td.bottom-borderless').attr('colspan', new_colspan);
            //     }
            // }
            // // var ceksimpel = $('#lysimpel');
            // var ceksimpel = document.getElementById('lysimpel');
            // ceksimpel.addEventListener('change',sendSimpel);
            // // var ceklengkap = document.getElementById('lylengkap');
            // // ceklengkap.addEventListener('change',sendLengkap);
            //
            // // Fungsi untuk mengirimkan nilai lg berdasarkan status checkbox
            // function sendLGValue() {
            //     var checkbox = document.getElementById('myCheckbox');
            //     var lgValue = checkbox.checked ? 1 : 0;
            //
            //     // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
            //     if(lgValue == 1){                
            //         $('#logo').removeClass('hidden');
            //         // $('#cprofile').removeClass('hidden');
            //         // $('#isi_cprofile').removeClass('hidden');
            //     }
            //     else {                
            //         $('#logo').addClass('hidden');
            //         // $('#cprofile').addClass('hidden');
            //         // $('#isi_cprofile').addClass('hidden');
            //     }
            // }
            // // Tambahkan event listener untuk checkbox
            // var checkbox = document.getElementById('myCheckbox');
            // checkbox.addEventListener('change', sendLGValue);
            //
            // function sendAddrValue() {
            //     var checkbox = document.getElementById('myCheckAddr');
            //     var lgValue = checkbox.checked ? 1 : 0;
            //
            //     // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
            //     if(lgValue == 1){                
            //         $('#logo').removeClass('hidden');
            //         $('#cprofile').removeClass('hidden');
            //         $('#myCheckbox').attr('value', 'true');
            //          $('.temp').addClass('hidden');
            //         $('.ttd').removeClass('hidden');
            //         $('h4.text-renggang-5').removeClass('hidden');
            //         $('p.no-margin').removeClass('hidden');
            //     }
            //     else {                
            //         $('#logo').addClass('hidden');
            //         $('#cprofile').addClass('hidden');
            //         $('#ttd_salesman').addClass('hidden');
            //         $('#ttd_sign_1').addClass('hidden');
            //         $('#ttd_sign_2>.pic').html('(_____________)');
            //         // $('.ttd').addClass('hidden');
            //         // $('.col-xs-2').html('<div class=\'temp sign_1\' style=margin-top:55px;>( ________ )</div>');
            //         $('h4.text-renggang-5').addClass('hidden');
            //         $('p.no-margin').addClass('hidden');
            //         // $('#isi_cprofile').addClass('hidden');
            //     }
            // }   
            // // Tambahkan event listener untuk checkbox
            // var checkbox = document.getElementById('myCheckAddr');
            // checkbox.addEventListener('change', sendAddrValue);
            //
            // function sendRKValue() {
            //     var checkboxrk = document.getElementById('checkboxrekening');
            //     var lgValue = checkboxrk.checked ? 1 : 0;
            //
            //     // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
            //     if(lgValue == 1){                
            //         $('#rekeningbank').removeClass('hidden');
            //     }
            //     else {                
            //         $('#rekeningbank').addClass('hidden');
            //     }
            // }
            // var checkboxrekening = document.getElementById('checkboxrekening');
            // checkboxrekening.addEventListener('change', sendRKValue);
            //
            // function sendTitle() {
            //     var checkboxtl = document.getElementById('checkboxTitle');
            //     var lgValue = checkboxtl.checked ? 1 : 0;
            //
            //      console.log('lgValue', lgValue)
            //     // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
            //     if(lgValue == 1){                
            //         $('#title').addClass('hidden');
            //         $('#status_pembayaran').addClass('hidden');
            //         $('#title_alt').removeClass('hidden');                    
            //     }
            //     else {                
            //         $('#title').removeClass('hidden');
            //         $('#status_pembayaran').removeClass('hidden');
            //         $('#title_alt').addClass('hidden');
            //     }
            // }
            // var checkboxtitle = document.getElementById('checkboxTitle');
            // checkboxtitle.addEventListener('change', sendTitle);
            //
            // function sendSku() {
            //     var checkboxtl = document.getElementById('sku');
            //     var lgValue = checkboxtl.checked ? 1 : 0;
            // var colspan = $('td.bottom-borderless').attr('colspan');
            //
            //      // console.log('lgValue', lgValue)
            //     // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
            //     if(lgValue == 1){
            //         var new_colspan = Number(colspan) + Number(1);
            //         // $('#title').addClass('hidden');
            //         $('.sku').removeClass('hidden');
            //         $('td.bottom-borderless').attr('colspan', new_colspan);                                        
            //     }
            //     else {
            //         var new_colspan = Number(colspan) - Number(1);
            //         // $('#title').removeClass('hidden');
            //         $('.sku').addClass('hidden');
            //         $('td.bottom-borderless').attr('colspan', new_colspan);
            //     }
            // }
            // var checkboxtitle = document.getElementById('sku');
            // checkboxtitle.addEventListener('change', sendSku);
            // document.addEventListener('DOMContentLoaded', function() {  
            //         top.$('.sku').addClass('hidden');
            //         $('td.bottom-borderless').attr('colspan', 6);
            // }); 
            
        </script>";

        $arrTags["cb_nama"] = isset($cb_nama) ? $cb_nama : "";
        $p->addTags(
            array(
                "tanggal_transaksi" => $tanggal_transaksi,
                // "lg_hidden"    => $lg_hidden,
                // "title"            => "ahahah",
                "option" => $option,
                "companyProfile" => $cpStr,
                "pihakProfile" => $pihakProfile,
                "fixedElements" => $fixedElements,
                "shippingDate" => $shippingDate,
                "paymentMethod" => $paymentMethod,
                "customerDetails" => $customerDetails,
                "vendorDetails" => $pihakProfile,
                "deliveryDetails" => $deliveryDetails,
                "billingDetails" => $billingDetails,
                "noteDetails" => $noteDetails,
                "content" => $itemStr,
                "content_2" => $content_2,
                "content_3" => $inwordStr,
                "statik_note" => $statik_note,
                "qrCodeNota" => isset($qrCodeNota) ? $qrCodeNota : "",
                "time_cetak" => "print @" . dtimeNow('d M Y H:i'),
                "footer" => $footer,
                "lain_lain" => "",
                "arrSubDetailDataKolom" => isset($arrSubDetailDataKolom) ? $arrSubDetailDataKolom : "",
                "free_produk" => isset($free_produk) ? $free_produk : null,
            ) + $signStr
        );
        $p->render();

        break;

}