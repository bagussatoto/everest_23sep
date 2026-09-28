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
                            "produk_nama"    => "nama",
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
                            "produk_nama"    => "nama",
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
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
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
            $master_jenis_produk = isset($metaProduk) ? $metaProduk : array();
            // =========================================================
            // SIMULASI MASTER DATA PRODUK (Nanti timpa dari Controller)
            // Format: ID_PRODUK_DASAR => 'unit' / 'non unit'
            // =========================================================
            $master_jenis_produk = isset($master_jenis_produk) ? $master_jenis_produk : array();

//            arrPrint($items5);
            //details
            if (isset($items5) && sizeof($items5) > 0) {
                $contentStr .=  "<tr class='bg-info items5'>";
                $contentStr .=  "<td colspan=''>";
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
                    if ($opsi_cetak == 3 || $opsi_cetak == 4 || $opsi_cetak == 6) {
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
                                if ($opsi_cetak == 4 || $opsi_cetak == 6) {
                                    $global_jasa += $saldo;
                                }
                            }
                        }

                        // 2. Kumpulkan dari Produk (MATERIAL & UNIT)
                        if (isset($iSpec['bahan_baku']['produk'])) {
                            foreach ($iSpec['bahan_baku']['produk'] as $prdData) {
                                $id_dasar = $prdData['produk_dasar_id'];
                                $kodeProduk = isset($master_jenis_produk[$id_dasar]['kode']) && $master_jenis_produk[$id_dasar]['kode'] !== "" ? "<r>" . $master_jenis_produk[$id_dasar]['kode'] . "</r>" : '--';
                                $nama = strtoupper($prdData['produk_dasar_nama']);
                                $qty = (float)$prdData['jml'];
                                $saldo = (float)$prdData['saldo'];
                                $satuan = isset($master_jenis_produk[$id_dasar]['satuan']) && !empty($master_jenis_produk[$id_dasar]['satuan']) ? strtolower($master_jenis_produk[$id_dasar]['satuan']) : 'Unit';
                                $jenis = isset($master_jenis_produk[$id_dasar]['kategori']) ? $master_jenis_produk[$id_dasar]['kategori'] : 'non unit';
                                if ($opsi_cetak == 3) {
                                    $kategori_num = ($jenis == 'unit') ? 1 : 2;
                                    if(!isset($rekap_barang[$nama])) {
                                        $rekap_barang[$nama] = array('qty' => 0, 'satuan' => $satuan, 'kategori' => $kategori_num, 'kode' => $kodeProduk);
                                    }
                                    $rekap_barang[$nama]['qty'] += $qty;
                                }
                                if ($opsi_cetak == 4 || $opsi_cetak == 6) {
                                    if ($jenis == 'unit') {
                                        $global_unit += $saldo;
                                        $exp = explode(" ", $nama);
                                        $merk = isset($exp[0]) ? $exp[0] : '';
                                        if (!in_array($merk, $merk_unit) && $merk != '') {
                                            $merk_unit[] = $merk;
                                        }
                                    }
                                    else {
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
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='15%'>JUMLAH</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='25%'>BARANG</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='25%'>HARGA SATUAN</td>";
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
                elseif ($opsi_cetak == 5) {
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center' width='5%'>No</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='45%'>BARANG / MATERIAL</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='10%'>QTY</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='10%'>SATUAN</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='15%'>HARGA</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='15%'>JUMLAH</td>";
                }
                elseif ($opsi_cetak == 6) {
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center' width='5%'>No</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='75%'>DESKRIPSI KATEGORI BIAYA</td>";
                    $contentStr .=  "<td class='text-muted bg-grey-1 text-center text-capitalize' width='20%'>JUMLAH</td>";
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

                } elseif ($opsi_cetak == 5) {
                    // BODY OPSI 5: SPK grouped
                    $no_rekap = 1;
                    foreach ($items5 as $iSpec) {
                        $nama_project = isset($iSpec['produk_nama']) ? strtoupper($iSpec['produk_nama']) : '';

                        // Sub-header SPK
                        $contentStr .= "<tr class='bg-grey-1'>";
                        $contentStr .= "<td class='text-center'><b>{$no_rekap}.</b></td>";
                        $contentStr .= "<td colspan='5' class='text-left text-bold text-blue'>Pekerjaan/WO: {$nama_project}</td>";
                        $contentStr .= "</tr>";

                        // Isi per produk
                        if (isset($iSpec['bahan_baku']['produk'])) {
                            foreach ($iSpec['bahan_baku']['produk'] as $prdData) {
                                $qty = (float)$prdData['jml'];
                                if ($qty > 0) {
                                    $id_dasar = isset($prdData['produk_dasar_id']) ? (int)$prdData['produk_dasar_id'] : 0;
                                    $harga = (float)$prdData['harga'];
                                    $subtotal = $qty * $harga;
                                    $sat = isset($prdData['satuan']) ? strtolower(trim($prdData['satuan'])) : '';
                                    if (empty($sat) || $sat === 'null' || $sat === '-') {
                                        $satuan = (isset($master_jenis_produk[$id_dasar]['satuan']) && !empty($master_jenis_produk[$id_dasar]['satuan']) && strtolower($master_jenis_produk[$id_dasar]['satuan']) !== 'null')
                                            ? $master_jenis_produk[$id_dasar]['satuan']
                                            : 'Unit';
                } else {
                                        $satuan = $prdData['satuan'];
                                    }

                                    $contentStr .= "<tr>";
                                    $contentStr .= "<td></td>";
                                    $contentStr .= "<td>- " . strtoupper($prdData['produk_dasar_nama']) . "</td>";
                                    $contentStr .= "<td class='text-center'><b>{$qty}</b></td>";
                                    $contentStr .= "<td class='text-center text-capitalize'>{$satuan}</td>";
                                    $contentStr .= "<td class='text-right'>" . number_format($harga) . "</td>";
                                    $contentStr .= "<td class='text-right text-bold'>" . number_format($subtotal) . "</td>";
                                    $contentStr .= "</tr>";
                                }
                            }
                        }
                        // Isi per biaya
                        if (isset($iSpec['bahan_baku']['biaya'])) {
                            foreach ($iSpec['bahan_baku']['biaya'] as $prdData) {
                                $qty = (float)$prdData['jml'];
                                if ($qty > 0) {
                                    $id_dasar = isset($prdData['produk_dasar_id']) ? (int)$prdData['produk_dasar_id'] : 0;
                                    $harga = (float)$prdData['harga'];
                                    $subtotal = $qty * $harga;
                                    $sat = isset($prdData['satuan']) ? strtolower(trim($prdData['satuan'])) : '';
                                    if (empty($sat) || $sat === 'null' || $sat === '-') {
                                        $satuan = (isset($master_jenis_produk[$id_dasar]['satuan']) && !empty($master_jenis_produk[$id_dasar]['satuan']) && strtolower($master_jenis_produk[$id_dasar]['satuan']) !== 'null')
                                            ? $master_jenis_produk[$id_dasar]['satuan']
                                            : 'Unit';
                                    } else {
                                        $satuan = $prdData['satuan'];
                                    }

                                    $contentStr .= "<tr>";
                                    $contentStr .= "<td></td>";
                                    $contentStr .= "<td>- " . strtoupper($prdData['produk_dasar_nama']) . "</td>";
                                    $contentStr .= "<td class='text-center'><b>{$qty}</b></td>";
                                    $contentStr .= "<td class='text-center text-capitalize'>{$satuan}</td>";
                                    $contentStr .= "<td class='text-right'>" . number_format($harga) . "</td>";
                                    $contentStr .= "<td class='text-right text-bold'>" . number_format($subtotal) . "</td>";
                                    $contentStr .= "</tr>";
                                }
                            }
                        }
                        $no_rekap++;
                    }
                } elseif ($opsi_cetak == 6) {
                    // BODY OPSI 6: Kategori Biaya
                    $no_rekap = 1;

                    if ($global_jasa > 0) {
                        $contentStr .= "<tr>";
                        $contentStr .= "<td class='text-center'>{$no_rekap}.</td>";
                        $contentStr .= "<td class='text-bold'>TOTAL BIAYA JASA dan INSTALASI</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($global_jasa) . "</td>";
                        $contentStr .= "</tr>";
                        $no_rekap++;
                    }
                    if ($global_material > 0 || $global_unit > 0) {
                        $tot_mat = $global_material + $global_unit;
                        $contentStr .= "<tr>";
                        $contentStr .= "<td class='text-center'>{$no_rekap}.</td>";
                        $contentStr .= "<td class='text-bold'>TOTAL BIAYA MATERIAL dan PRODUK</td>";
                        $contentStr .= "<td class='text-right text-bold'>" . number_format($tot_mat) . "</td>";
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
                        }
                        elseif ($opsi_cetak == 2) {
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
                        }
                        else {
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
                }
                elseif ($opsi_cetak == 3) {
                    $colspan_footer = 3;
                }
                elseif ($opsi_cetak == 2) {
                    $colspan_footer = 3;
                }
                elseif ($opsi_cetak == 4) {
                    $colspan_footer = 4;
                }
                elseif ($opsi_cetak == 5) {
                    $colspan_footer = 5;
                }
                elseif ($opsi_cetak == 6) {
                    $colspan_footer = 2;
                }
                else {
                    $colspan_footer = is_array($itemLabels5) ? count($itemLabels5) : 3;
                }

                $contentStr .=  "<tfoot>";

                // =========================================================
                // TAMBAHAN: INFO PROGRES FISIK (PROGRESSIVE BILLING)
                // =========================================================
                // Nilai total kontrak dari Controller (contoh: 100000000). Default 0 agar tidak error jika belum ada.
                $nilai_total_project = isset($nilai_total_project) && $nilai_total_project > 0 ? (float)$nilai_total_project : 0;

                // Hitung persen progres
                $persen_progres_saat_ini = ($nilai_total_project > 0) ? round(($grand_total_all / $nilai_total_project) * 100, 2) : 0;
                $teks_persen = ($persen_progres_saat_ini > 0) ? " ({$persen_progres_saat_ini}%)" : "";

                // 1. Baris Informasi Progres Saat Ini
                $contentStr .=  "<tr class='bg-grey-2'>";
                $contentStr .=  "<td colspan='$colspan_footer' class='text-right'>TOTAL PROGRES PEKERJAAN SAAT INI$teks_persen</td>";
                $contentStr .=  "<td class='text-right valign-m'>".number_format($grand_total_all)."</td>";
                $contentStr .=  "</tr>";

                // 2. Baris Informasi Nilai Kontrak Keseluruhan (Tampil jika ada $nilai_total_project)
                if ($nilai_total_project > 0) {
                    $contentStr .=  "<tr class='bg-grey-2'>";
                    $contentStr .=  "<td colspan='$colspan_footer' class='text-right'>NILAI KONTRAK KESELURUHAN (100%)</td>";
                    $contentStr .=  "<td class='text-right valign-m'>".number_format($nilai_total_project)."</td>";
                    $contentStr .=  "</tr>";

                    // Baris Kosong sebagai Pemisah antara Info Fisik dan Detail Tagihan (SumRows)
                    $contentStr .= "<tr><td colspan='".($colspan_footer + 1)."' style='border:none; height:10px;'></td></tr>";
                }
                // =========================================================

                // --- BAGIAN DETAIL TAGIHAN (SUM ROWS DARI CONTROLLER) ---
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

        $option = "";
        $option .= "<div class='hidden-on-print text-uppercase' style='background-color: black;padding-left: 20px;'>";
        $option .= " <button type='button' id='print' onclick=\"window.print();\" title='print/cetak'> <i class='glyphicon glyphicon-print'></i></button>";

        if (isset($items5) && is_array($items5) && sizeof($items5) > 0) {
            $option .= " <span style='display:inline-block; background-color:#000; padding:0 10px; border-radius:4px; margin-left:5px; margin-right:5px;'>";
            $option .= " <label class='text-white' style='margin-right:5px; margin-bottom:0;'>MODE CETAK:</label>";
            $option .= " <select id='pilih_opsi_cetak' style='color:black; padding:2px; border-radius:3px; outline:none;' onchange=\"
                            var currentUrl = window.location.href;
                            var newUrl;
                            var val = this.value;
                            if (currentUrl.indexOf('opsi_cetak=') > -1) {
                                newUrl = currentUrl.replace(/opsi_cetak=[^&]+/, 'opsi_cetak=' + val);
                            } else {
                                var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
                                newUrl = currentUrl + separator + 'opsi_cetak=' + val;
                            }
                            window.location.href = newUrl;
                        \">";

            $option .= " <option value='1' ".($opsi_cetak == 1 ? "selected" : "").">Opsi 1 (Project & Total)</option>";
            $option .= " <option value='2' ".($opsi_cetak == 2 ? "selected" : "").">Opsi 2 (Rincian Barang)</option>";
            $option .= " <option value='3' ".($opsi_cetak == 3 ? "selected" : "").">Opsi 3 (Rekap QTY & Satuan)</option>";
            $option .= " <option value='4' ".($opsi_cetak == 4 ? "selected" : "").">Opsi 4 (Grouping 1 LOT)</option>";
            $option .= " <option value='5' ".($opsi_cetak == 5 ? "selected" : "").">Opsi 5 (Full Detail SPK + Harga)</option>";
            $option .= " <option value='6' ".($opsi_cetak == 6 ? "selected" : "").">Opsi 6 (Rekap Material & Jasa)</option>";
            $option .= " </select>";
            $option .= " </span>";
        }
        $option .= "</div>";
        $elementLabels["options"] = $option;

        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH .$template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        //arrPrintWebs($arrTags);

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
                "title"        => "$title",
                "content"      => $hasil,
                "stop_time"    => "",
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
                "content"      => $strData,
                "stop_time"    => "",
                "profile_name" => $this->session->login['nama'],
                "printing"     => "window.print();",
            )
        );

        $p->render();
        break;
}

