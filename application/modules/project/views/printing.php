<?php

switch($mode){
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
        // arrPrint($items);
        // arrPrint($mainValues);
        // arrPrint($items2);
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
                    $item_src .= formatField_he_format($ky, $val);
                    $item_src .= "</td>";
                }
                $item_src .= "</tr>";

            }


            $item_src .= "</table>";
            $item_src .= "</div>";
        }
        // arrPrint($itemClosingFg);
//         arrPrint($items);
        // arrPrint($items3);

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";

                if (sizeof($itemClosingFg)) {
                    $repacerLabel = array(
                        "produk_nama" => "nama",
                    );
                    foreach ($items as $iid => $tmpData) {
                        foreach ($repacerLabel as $oldKey => $newKey) {
                            if (isset($tmpData[$oldKey])) {
                                $items[$iid][$newKey] = $tmpData[$oldKey];
                            }
                        }
                    }
                    // arrPrint($receiptMultitemSubField_detils);
                    $contentStr .= "<tr bgcolor='#f5f5f5'>";
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                    foreach ($receiptMultiItemSubFields as $key => $keyLabel) {
                        $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                        $contentStr .= $keyLabel;
                        $contentStr .= "</th>";
                    }
                    $contentStr .= "</tr>";
                    $no = 0;
                    $totalPl = 0;
                    foreach ($items as $id => $iSpec) {
                        // arrPrint($iSpec);
                        $pid = $iSpec["produk_id"];
                        $no++;
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td align='right'>";
                        $contentStr .= $no;
                        $contentStr .= ".</td>";
                        foreach ($receiptMultiItemSubFields as $key => $label) {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                            $keyFormat = $key == "nama" ? "nomer" : $key;
                            if ($key == "details") {
                                $contentStr .= "<td>";

                                $contentStr .= "<table  class='table table-bordered table-condensed' style='width: 100%;'>";

                                if (isset($itemClosingFg[$pid]) && sizeof($itemClosingFg[$pid]) > 0) {
                                    // $contentStr .="<div>PRODUK</div>";
                                    $contentStr .= "<tr bgcolor='#f5f5f5'>";
                                    foreach ($receiptMultitemSubField_detils as $keyL => $keyAlias) {
                                        $contentStr .= "<th  class='text-muted' style='font-weight:bold;'>";
                                        $contentStr .= $keyAlias;
                                        $contentStr .= "</th>";
                                    }
                                    $contentStr .= "</tr>";
                                    foreach ($itemClosingFg[$pid] as $pr_subID => $tmpSubItems) {
                                        $totalPl += $tmpSubItems["sub_harga"];
                                        $contentStr .= "<tr>";
                                        foreach ($receiptMultitemSubField_detils as $keyL => $keyAlias) {
                                            $contentStr .= "<td>";
                                            $contentStr .= formatField_he_format($keyL, $tmpSubItems[$keyL]);
                                            $contentStr .= "</td>";
                                        }
                                        $contentStr .= "</tr>";
                                    }
                                }
                                if (isset($items3[$pid]) && sizeof($items3[$pid]) > 0) {
                                    $contentStr .= "<tr bgcolor='#f5f5f5'>";
                                    foreach ($receiptMultitemSubField_detil_biaya as $keyB => $keyAlias_b) {
                                        $contentStr .= "<th  class='text-muted' style='font-weight:bold;'>";
                                        $contentStr .= $keyAlias_b;
                                        $contentStr .= "</th>";
                                    }
                                    $contentStr .= "</tr>";
                                    foreach ($items3[$pid] as $tmpBiaya) {
                                        $totalPl += $tmpBiaya["sub_harga"];
                                        $contentStr .= "<tr>";
                                        foreach ($receiptMultitemSubField_detil_biaya as $keyB => $keyAlias_b) {
                                            $contentStr .= "<td>";
                                            $contentStr .= formatField_he_format($keyB, $tmpBiaya[$keyB]);
                                            $contentStr .= "</td>";
                                        }
                                        $contentStr .= "</tr>";
                                        // arrPrint($tmpBiaya);
                                    }

                                }


                                $contentStr .= "</table>";
                                $contentStr .= "</td>";
                            }
                            else {
                                $contentStr .= "<td>";
                                $contentStr .= formatField_he_format($keyFormat, $val);
                                $contentStr .= "</td>";
                            }

                        }

                        $contentStr .= "</tr>";
                    }
                    cekHitam("ada items2 " . $totalPl);
                    $contentStr .= "<tr>";
                    $contentStr .= "<td colspan='3' class='text-right'>TOTAL PACKING LIST</td>";
                    $contentStr .= "<td>" . formatField_he_format("subtotal", $totalPl) . "</td>";
                    $contentStr .= "</tr>";

                }
                else {
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
                }


                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                // cekMErah("hitam");
                $no = 0;
                $contentStr2 .= "<div class='table-responsive'>";
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
                    arrPrint($iSpec);
                    cekMErah("masuk");
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
                        cekMErah("masuk " . $key);
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
            // arrPrintWebs($rsltItems3Labels);
            $contentStr9 = "";
            if (isset($rsltItems3_sub) && sizeof($rsltItems3_sub) > 0) {
                $no = 0;
                $contentStr9 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr9 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr9 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr9 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($rsltItems3Labels as $key => $label) {
                    $contentStr9 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr9 .= $label;
                    $contentStr9 .= "</th>";
                }
                $contentStr9 .= "</tr>";
                foreach ($rsltItems3_sub as $id => $iSpec) {
                    //                    cekHere($id);
                    $no++;
                    //                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr9 .= "<tr line=" . __LINE__ . ">";
                    $contentStr9 .= "<td align='right'>";
                    $contentStr9 .= $no;
                    $contentStr9 .= ".</td>";
                    foreach ($rsltItems3Labels as $key => $label) {
                        //                        $replacers = array(
                        //                            "produk_nama" => "nama",
                        //                            "produk_ord_jml" => "jml",
                        //                        );
                        //                        foreach ($replacers as $orig => $new) {
                        //                            if ($key == $orig) {
                        //                                $key = $new;
                        //                            }
                        //                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr9 .= "<td>";
                        $contentStr9 .= formatField($key, $val);
                        $contentStr9 .= "</td>";
                    }
                    $contentStr9 .= "</tr>";

                    //-----ISI PO PROJEK
                    $iID = $iSpec['produk_id'];
                    $subAdvanceItems2 = isset($subAdvanceItems[$iID]) ? $subAdvanceItems[$iID] : array();// ini items2

                    if (sizeof($subAdvanceItems2) > 0) {
                        foreach ($subAdvanceItems2 as $ipoID => $ipoSpec) {

                            $sub_key = isset($ipoSpec['pph']) ? $ipoSpec['pph'] : 0;
                            $itemLabels = isset($receiptAdvanceItemsLabel[$iID][$ipoID]) ? $receiptAdvanceItemsLabel[$iID][$ipoID] : array();
                            $subItemLabels = isset($receiptAdvanceSubItemsLabel[$iID][$ipoID]) ? $receiptAdvanceSubItemsLabel[$iID][$ipoID] : array();
                            $editableField = isset($receiptAdvanceEditableField[$iID][$ipoID]) ? $receiptAdvanceEditableField[$iID][$ipoID] : array();
                            $advanceNumType = isset($receiptAdvanceNumType[$iID][$ipoID]) ? $receiptAdvanceNumType[$iID][$ipoID] : array();
                            //                            cekHere($sub_key);
                            //                            arrPrintPink($ipoSpec);
                            if (sizeof($itemLabels) > 0) {

                                $colspan = sizeof($rsltItems3Labels);
                                $contentStr9 .= "<tr line=" . __LINE__ . ">";
                                $contentStr9 .= "<td align='left'></td>";
                                $contentStr9 .= "<td align='left' colspan='$colspan'>";
                                //                            $contentStr9 .= "(po projek disini)";

                                $contentStr9 .= "<div class='table-responsive' style='border:0px solid red;'>";
                                $contentStr9 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";

                                $contentStr9 .= "<tr bgcolor='#f5f5f5'>";
                                //                            $contentStr9 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                                foreach ($itemLabels as $key => $label) {
                                    $contentStr9 .= "<th class='text-muted' style='font-weight:bold;'>";
                                    $contentStr9 .= $label . " <span class='meta'>$key</span>";
                                    $contentStr9 .= "</th>";
                                }
                                $contentStr9 .= "</tr>";

                                $contentStr9 .= "<tr line=" . __LINE__ . ">";
                                //                            $contentStr9 .= "<td align='right'>";
                                //                            $contentStr9 .= $no;
                                //                            $contentStr9 .= ".</td>";

                                foreach ($itemLabels as $key => $label) {
                                    $val = isset($ipoSpec[$key]) ? $ipoSpec[$key] : "";
                                    $contentStr9 .= "<td>";
                                    $contentStr9 .= formatField($key, $val);
                                    $contentStr9 .= "</td>";
                                }
                                $contentStr9 .= "</tr>";

                                $contentStr9 .= "</table class='table table-bordered table-condensed' style='width: 100%;'>";
                                $contentStr9 .= "</div class='table-responsive' style='border:0px solid red;'>";

                                $contentStr9 .= "</td>";
                                $contentStr9 .= "</tr>";
                            }

                        }
                    }
                    //-----ISI PO PROJEK

                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($rsltItems3_sub[$id]['note']) && strlen($rsltItems3_sub[$id]['note']) > 1) {
                            $contentStr9 .= "<tr line=" . __LINE__ . ">";
                            $contentStr9 .= "<td>&nbsp;</td>";
                            $contentStr9 .= "<td colspan='" . sizeof($rsltItems3Labels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($rsltItems3_sub[$id]['note']) ? $rsltItems3_sub[$id]['note'] : "";
                            $contentStr9 .= $iVal;
                            $contentStr9 .= "</td>";
                            $contentStr9 .= "</tr>";
                        }
                    }
                }
                $contentStr9 .= "</table>";
                $contentStr9 .= "</div>";
            }
            else {
                if (isset($items3) && sizeof($items3) > 0) {

                }
            }

//            $contentStr3 = "";
            $contentStr3 = "<div class='panel-header'><h4>SPESIFIKASI</h4></div>";
            $contentStr3 .= "<div class='panel-body no-padding'>";
            $contentStr3 .= $komposisi_project;
            $contentStr3 .= "</div>";
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

            //            cekHere($rsltItems3Labels);
            //            cekHere($contentStr9);

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
            $elementLabels["content_9"] = $contentStr9;
            $elementLabels["content_10"] = $contentStr9;//untuk nota closing karena multi packing list
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
    case "viewRencanaKerja":

//        cekHitam("TEST...");
        $pakai_ini = 0;
        if ($pakai_ini == 1) {

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
        }
        else {
//            arrPrintKuning($arrProjectKomposisiWorkOrder);

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


            $p = New Layout("$title", "", MODUL_TEMPLATE_PATH . "template/rencana.html");

            $str = "";
            $str2 = "";
            $nom = 0;
            $total = 0;
            if(isset($arrProjectWorkOrder)){
                $str .= "<div>";
                $str .= "<h3><span class='text-uppercase'>RINCIAN ANGGARAN</span></h3>";
                $str .= "</div>";

                foreach ($arrProjectWorkOrder as $key => $labels){
                    $nom++;
                    $str .= "<div>";
                    $str .= "<h4><span class='text-uppercase'>$nom. $labels</span></h4>";
                    $str .= "</div>";

                    if(isset($arrProjectKomposisiWorkOrder[$key])){
//                        arrPrint($arrProjectKomposisiWorkOrder[$key]);

                        $total_kategori = 0;
                        foreach ($arrProjectKomposisiWorkOrder[$key] as $kategori => $sspec){
                            $kategori = ($kategori == "produk") ? "material":"non material";
                            $str .= "<div sstyle='border:1px solid blue;'>";
                            $str .= "<h5><span class='text-uppercase' style='padding-left: 20px;'>$kategori</span></h5>";
                            $str .= "</div>";

                            $str .= "<table class='table table-bordered table-condensed' style='width: 100%;bborder:1px solid black;'>";
                            $str .= "<tr>";
                            $str .= "<th>No.</th>";
                            foreach ($itemLabels as $label){
                                $str .= "<th>$label</th>";
                            }
                            $str .= "</tr>";

                            $no = 0;
                            $subtotal = 0;
                            foreach ($sspec as $spec){
                                $no++;
                                $str .= "<tr>";
                                $str .= "<td>$no</td>";
                                foreach ($itemLabels as $keyy => $vall){
                                    $new_val = formatField_he_format($keyy, $spec[$keyy]);
                                    $str .= "<td>$new_val</td>";
                                    if($keyy == "subtotal"){
                                        $subtotal += $spec[$keyy];
                                    }
                                }
                                $str .= "</tr>";
                            }
                            $colspan = sizeof($itemLabels);
                            $str .= "<tr>";
                            $str .= "<td colspan='$colspan'>-</td>";
                            $str .= "<td>".formatField_he_format("debet", $subtotal)."</td>";
                            $str .= "</tr>";
                            $str .= "</table>";

                            $total_kategori += $subtotal;

                        }
                        $str .= "<div class='alert alert-warning' style='font-size: 15px;'>ANGGARAN $labels : <span class='pull-right'>".number_format($total_kategori, "0", ".", ",")."</span></div>";

                        $total += $total_kategori;
                    }

                }
                $str .= "<div class='alert alert-warning' style='font-size: 15px;'>TOTAL ANGGARAN : <span class='pull-right'>".number_format($total, "0", ".", ",")."</span></div>";
            }

            if(isset($arrProjectKomposisi)){

                $str2 .= "<div>";
                $str2 .= "<h3><span class='text-uppercase'>RINGKASAN ANGGARAN</span></h3>";
                $str2 .= "</div>";

                $total_sum = 0;
                foreach ($arrProjectKomposisi as $kategori => $sspec){
                    $kategori = ($kategori == "produk") ? "material":"non material";
                    $str2 .= "<div sstyle='border:1px solid blue;'>";
                    $str2 .= "<h5><span class='text-uppercase' style='padding-left: 20px;'>$kategori</span></h5>";
                    $str2 .= "</div>";

                    $str2 .= "<table class='table table-bordered table-condensed' style='width: 100%;bborder:1px solid black;'>";
                    $str2 .= "<tr>";
                    $str2 .= "<th>No.</th>";
                    foreach ($itemLabels as $label){
                        $str2 .= "<th>$label</th>";
                    }
                    $str2 .= "</tr>";

                    $no = 0;
                    $subtotal = 0;
                    foreach ($sspec as $spec){
                        $no++;
                        $str2 .= "<tr>";
                        $str2 .= "<td>$no</td>";
                        foreach ($itemLabels as $keyy => $vall){
                            $new_val = formatField_he_format($keyy, $spec[$keyy]);
                            $str2 .= "<td>$new_val</td>";
                            if($keyy == "subtotal"){
                                $subtotal += $spec[$keyy];
                            }
                        }
                        $str2 .= "</tr>";
                    }
                    $colspan = sizeof($itemLabels);
                    $str2 .= "<tr>";
                    $str2 .= "<td colspan='$colspan'>-</td>";
                    $str2 .= "<td>".formatField_he_format("debet", $subtotal)."</td>";
                    $str2 .= "</tr>";

                    $str2 .= "</table>";

                    $total_sum += $subtotal;
                }
                $str2 .= "<div class='alert alert-warning' style='font-size: 15px;'>TOTAL ANGGARAN : <span class='pull-right'>".number_format($total_sum, "0", ".", ",")."</span></div>";

            }


//            echo $str;
            if (sizeof($elementLabels) > 0) {
                foreach ($elementLabels as $tKey => $tValue) {
                    $arrTags[$tKey] = $tValue;
                }
            }
            $arrTags["content"] = $str;
            $arrTags["content_2"] = $str2;
            $arrTags["noteDetails"] = "";
            $arrTags["customerSignitures"] = "";
            $arrTags["sign_1"] = "";
            $arrTags["sign_2"] = "";
            $arrTags["companyProfile"] = "";
//            $arrTags["customerDetails"] = "";
//            arrPrintHijau($arrTags);
            $p->addTags($arrTags);
            $p->render();
        }


        break;
    case "print_tasklist":
        $p = New Layout("$title", "----------", MODUL_TEMPLATE_PATH . "template/print_tasklist.html");

        $nomer_order = $produkProject[0]->quot_nomer;
        $nomer_po = $produkProject[0]->spek;


        $strData = "";
        $tableHeader = "";

        //$masa_pelaksanaan_detail_pekerjaan
        $tableHeader .= "<table style='margin-top: 0px;' class='table dataTablex compact table-borderedx table-condensed no-footer'>";
        $tableHeader .= "<thead>";
        $tableHeader .= "<tr>";
        $tableHeader .= "<td style='border-right: 0px!important;'>No. Order</td><td style='border-left: 0px!important;'>: ".formatField_he_format('nomer', $nomer_order)."</td>";
        $tableHeader .= "</tr>";
        $tableHeader .= "<tr>";
        $tableHeader .= "<td style='border-right: 0px!important;'>No. PO</td><td style='border-left: 0px!important;'>: $nomer_po</td>";
        $tableHeader .= "</tr>";
        $tableHeader .= "<tr>";

        // $tableHeader .= "<td style='border-right: 0px!important;'>Masa Berlaku</td><td style='border-left: 0px!important;'>: $alamat_detail_pekerjaan</td>";

        $exp_tgls = explode('s/d', $masa_pelaksanaan_detail_pekerjaan);
        if (count($exp_tgls) > 1) {
            $masa_pelaksanaan_detail_pekerjaan_f = formatTanggal($exp_tgls[0], 'd-F-Y') . " s/d " . formatTanggal($exp_tgls[1], 'd-F-Y');
        }
        else {
            $masa_pelaksanaan_detail_pekerjaan_f = formatTanggal($exp_tgls[0], 'd-F-Y');
        }
        $tanggal_jatuh_tempo_f = formatTanggal($tanggal_jatuh_tempo, 'd-F-Y');

        $tableHeader .= "<td style='border-right: 0px!important;'>Masa Berlaku</td><td style='border-left: 0px!important;'>: $tanggal_jatuh_tempo_f</td>";
        $tableHeader .= "</tr>";
        $tableHeader .= "<tr>";
        $tableHeader .= "<td style='border-right: 0px!important;'>Halaman</td><td style='border-left: 0px!important;'>: 1 halaman</td>"; //masih manual
        $tableHeader .= "</tr>";
        $tableHeader .= "</thead>";
        $tableHeader .= "</table>";


        //DETAILS PEKERJAAN
        $strData .= "<div class='col-print-12'>";
        $strData .= "<div class='container-fluidx'>";
        $strData .= "<div class='col-print-6'>";
        $strData .= "<table style='margin-top: 15px;' class='table dataTable compact table-bordered table-condensed no-footer'>";
        $strData .= "<thead>";
        $strData .= "<tr>";
        $strData .= "<td style='border-right: 0px!important;'>Kepada</td><td style='border-left: 0px!important;border-right: 0px!important;'>: $owner_nama</td>";
        $strData .= "</tr>";
        $strData .= "<tr>";
        $strData .= "<td style='border-right: 0px!important;'>Pekerjaan</td><td style='border-left: 0px!important;border-right: 0px!important;'>: $label_pekerjaan</td>";
        $strData .= "</tr>";
        $strData .= "<tr>";
        $strData .= "<td style='border-right: 0px!important;'>Tujuan</td><td style='border-left: 0px!important;border-right: 0px!important;'>: $nama</td>";
        $strData .= "</tr>";
        $strData .= "</thead>";
        $strData .= "</table>";
        $strData .= "</div>";

        $strData .= "<div class='col-print-6'>";
        $strData .= "<table style='margin-top: 15px;' class='table dataTable compact table-bordered table-condensed no-footer'>";
        $strData .= "<thead>";
        $strData .= "<tr>";
        $strData .= "<td style='border-left: 0px!important;border-right: 0px!important;'>No SPK</td><td style='border-left: 0px!important;'>: $nomer_int_spk</td>";
        $strData .= "</tr>";
        $strData .= "<tr>";
        $tanggal_pelaksana_f = formatTanggal($tanggal_pelaksana, 'd-F-Y');
        // $strData .= "<td style='border-left: 0px!important;border-right: 0px!important;'>Tanggal Pelaksana</td><td style='border-left: 0px!important;'>: $masa_pelaksanaan_detail_pekerjaan_f</td>";
        $strData .= "<td style='border-left: 0px!important;border-right: 0px!important;'>Tanggal Pelaksana</td><td style='border-left: 0px!important;'>: $tanggal_pelaksana_f</td>";
        $strData .= "</tr>";
        $strData .= "<tr>";
        $strData .= "<td style='border-left: 0px!important;border-right: 0px!important;'>Attachment</td><td style='border-left: 0px!important;'>: <span id='terlampir' class=''>Terlampir</span></td>";
        $strData .= "</tr>";
        $strData .= "</thead>";
        $strData .= "</table>";
        $strData .= "</div>";

        $strData .= "</div>";
        $strData .= "</div>";

        //LIST PRODUK/MATERIAL

        $list_produk = "<div style='' class='col-print-12'>";
        $list_produk .= "<div class='container-fluidx'>";
        $list_produk .= "<table class='table dataTable compact table-bordered table-condensed no-footer'>";
        $list_produk .= "<caption class='text-left'>Dengan Hormat,<br>Bersama ini kami terbitkan Surat Perintah Kerja (SPK) dengan rincian sebagai berikut.</caption>";
        $list_produk .= "<thead>";
        $list_produk .= "<tr>";
        $list_produk .= "<th class='text-center'>No</th>";
        $list_produk .= "<th class='text-center'>Produk</th>";
        $list_produk .= "<th class='text-center'>QR/SKU</th>";
        $list_produk .= "<th class='text-center'>Jumlah</th>";
        $list_produk .= "<th class='text-center'>Satuan</th>";
//        $list_produk .= "<th class='text-center'>Keterangan</th>";
        $list_produk .= "<th class='text-center'>Checklist</th>";
        $list_produk .= "</tr>";
        $list_produk .= "</thead>";

        $list_produk .= "<tbody>";

        $num=0;
        foreach($produk as $k => $dProduk){
            $num++;
            $list_produk .= "<tr>";
            $list_produk .= "<td class='text-center'>$num</td>";
            $list_produk .= "<td class='text-capitalize' style='padding-left: 6px !important;'>".$dProduk->produk_dasar_nama."    </td>";
            $list_produk .= "<td class='text-left'>      ".$dProduk->barcode."              </td>";
            $list_produk .= "<td class='text-center'>    ".$dProduk->jml."                  </td>";
            $list_produk .= "<td class='text-center'>    unit               </td>";
            $list_produk .= "<td class='text-center'>    <input class='no-margin' type='checkbox' disabled></td>";
            $list_produk .= "</tr>";
        }

        if(!empty($supplies)){
            $numS=$num;
            foreach($supplies as $k => $dProduk){
                $numS++;
                $list_produk .= "<tr>";
                $list_produk .= "<td class='text-center'>$numS</td>";
                $list_produk .= "<td class='text-capitalize' style='padding-left: 6px !important;'>(supplies) &nbsp; ".$dProduk['biaya_dasar_nama']."</td>";
                $list_produk .= "<td class='text-left'>      ".$dProduk['barcode']."              </td>";
                $list_produk .= "<td class='text-center'>    ".$dProduk['total']."                  </td>";
                $list_produk .= "<td class='text-center'>    ".$dProduk['satuan']."               </td>";
                $list_produk .= "<td class='text-center'>    <input class='no-margin' type='checkbox' disabled></td>";
                $list_produk .= "</tr>";
            }
        }


        $list_produk .= "</tbody>";

        $list_produk .= "</table>";
        $list_produk .= "</div>";
        $list_produk .= "</div>";

        $strData .= $list_produk;

        //PERNYATAAN SYARAT KETENTUAN PENGERJAAN
//        $strData .= "<div style='margin-top: 15px;' class='row'>
//            <div class='col-print-12'>Syarat, ketentuan dan keterangan pelaksanaan pekerjaan :</div>
//            <ol>
//                <li>Item pekerjaan, bobot prosentase pekerjaan, volume pekerjaan, gambar kerja dan Rencana Kerja dan Syarat – Syarat (RKS) merupakan bagian yang tidak terpisahkan dari Surat Perintah Kerja (SPK) ini.</li>
//                <li>Keterlambatan terhadap penyelesaian pekerjaan (100%), sesuai dengan Surat Perjanjian Kerjasama pasal 4 butir 2, akan dikenakan denda 0,2% dari Nilai Pekerjaan untuk setiap minggunya.</li>
//                <li>Apabila dalam masa pelaksanaan pekerjaan ada perubahan – perubahan secara teknis, maka akan diatur dan dituangkan dalam bentuk SPK Addendum, yang akan diberitahukan oleh Pihak I.</li>
//            </ol>
//        </div>";

        $strData .= "<div style='margin-top: 15px;' class='col-print-12'>
                        <div class='container-fluid'>
                            <div class='col-print-12'>Instruksi pelaksanaan pekerjaan: </div>
                            <ol>
                                <li>Material sudah disediakan oleh SPV, jika belum, silahkan datang langsung ke gudang untuk mengambil material dengan membawa SPK ini.</li>
                                <li>Setelah melaksanakan pekerjaan, photo bukti kerja dan posting pada App Project PT. Everest Elektronic.</li>
                                <li>Pekerjaan yang telah diposting akan di setujui jika memiliki detail pengerjaan yg jelas, termasuk photo dan pengerjaan tepat waktu.</li>
                            </ol>
                        </div>
                    </div>";

        //PENUTUP
        $strData .= "<div style='margin-top: 15px;' class='col-print-12'>
                        <div class='container-fluid'>
                            Demikian Surat Perintah Kerja ini disusun untuk dipergunakan sebagaimana mestinya.
                        </div>
                    </div>";

        //SIGN-SIGN
        $sign_pihak1 = "
        <div style='margin-top: 50px;' class='col-print-12'>
            <div class='text-center'>&nbsp;</div>
        <!-- <div class='text-center'>PIHAK II</div> -->
            <div class='text-center'>PELAKSANA PROJECT</div>
            <div style='height: 75px;'>&nbsp;</div>
            <div nama_pihak1='$nama_pihak2' class='text-center text-bold text-uppercase'>(______________________)</div>
        </div>";

        $sign_pihak2 = "
        <div style='margin-top: 50px;' class='col-print-12'>
            <div class='text-center'>&nbsp;</div>
        <!-- <div class='text-center'>PIHAK I</div> -->
            <div class='text-center'>ADMIN PROJECT</div>
            <div style='height: 75px;'>&nbsp;</div>
            <div class='text-center text-bold text-uppercase'>(___<u>$nama_pihak1</u>___)</div>
        </div>";


        $sign_pihak4 = "
        <div style='margin-top: 50px;' class='col-print-12'>
            <div class='text-center'>&nbsp;</div>
        <!-- <div class='text-center'>PIHAK II</div> -->
            <div class='text-center' id='sign_4'>TEKNISI</div>
            <div class='text-center hidden' id='sign_4_alt'>DRIVER</div>
            <div style='height: 75px;'>&nbsp;</div>
            <div class='text-center text-bold text-uppercase'>(______________________)</div>
        </div>";

        $sign_pihak3 = "
        <div style='margin-top: 40px;' class='col-print-12'>
        <!-- <div class='text-center'>PIHAK III</div> -->
            <div class='text-center text-bold'><sub>Tangerang , - - - - - - - - - - - - - - - - - - - - - - </sub> " . date("Y") . "</div>
            <div class='text-center'>MENGETAHUI</div>
            <div style='height: 75px;'>&nbsp;</div>
            <div class='text-center text-bold text-uppercase'>(<u>______________________</u>)</div>
        </div>";

        /* ----------------------------------------------------------------
         *
         * ----------------------------------------------------------------*/
        $cp_editor = MODUL_PATH . "Printing/editorPrint";
        $option = "";
        $option .= "<div class='hidden-on-print text-uppercase' style='background-color: black;padding-left: 20px;'>";
        // $option .= "<label class='text-white'><input type='radio' name='layout' id='lylengkap' checked> lengkap</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' name='layout' id='sku'> SKU</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' name='layout' id='lysimpel' checked> lengkap/simple layout</label> ";
        // $option .= "<label class='text-white'><input type='checkbox' id='myCheckbox' checked> logo </label> ";
        // $option .= "<label class='text-white'><input type='checkbox' id='myCheckAddr' checked> alamat perusahaan</label> ";
        // $option .= " <label class='text-white'><input type='checkbox' id='checkboxrekening' checked> rekening bank</label> <a class='text-warning' href='$cp_editor' data-toggle='modal' data-target='#myModal'><i class='glyphicon glyphicon-pencil'></i></a>";
        $option .= " <label class='text-white'><input type='checkbox' id='checkboxTerlampir' checked> terlampir</label>";
        $option .= " <label class='text-white'><input type='checkbox' id='checkboxTitle' checkedd> Surat Jalan Pengiriman</label>";
        $option .= " <button type='button' id='print' onclick=\"window.print();\" title='print/cetak'> <i class='glyphicon glyphicon-print'></i></button>";
        $option .= "</div>";
        $option .= "<script>
            
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
            // var ceklengkap = document.getElementById('lylengkap');
            // ceklengkap.addEventListener('change',sendLengkap);

            // Fungsi untuk mengirimkan nilai lg berdasarkan status checkbox
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
            
            function sendTitle() {
                var checkboxtl = document.getElementById('checkboxTitle');
                var lgValue = checkboxtl.checked ? 1 : 0;
            
                 console.log('lgValue', lgValue)
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
                if(lgValue == 1){                
                    $('#title').addClass('hidden');
                    $('#title_alt').removeClass('hidden');                    
                    $('#sign_4_alt').removeClass('hidden');                    
                    $('#sign_4').addClass('hidden');
                }
                else {                
                    $('#title').removeClass('hidden');
                    $('#title_alt').addClass('hidden');
                    $('#sign_4_alt').addClass('hidden');
                    $('#sign_4').removeClass('hidden');
                }
            }
            var checkboxtitle = document.getElementById('checkboxTitle');
            checkboxtitle.addEventListener('change', sendTitle);
            
             function sendTerlampir() {
                var checkboxtl = document.getElementById('checkboxTerlampir');
                var lgValue = checkboxtl.checked ? 1 : 0;
            
                 console.log('lgValue', lgValue)
                // Kirim nilai lg ke server atau lakukan tindakan yang sesuai           
                if(lgValue == 1){                
                    $('#terlampir').removeClass('hidden');
                }
                else {                
                    $('#terlampir').addClass('hidden');
                }
            }
            var checkboxterlampir = document.getElementById('checkboxTerlampir');
            checkboxterlampir.addEventListener('change', sendTerlampir);
            
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

        $companyProfile_alias_e= explode(" ", $companyProfile_alias);
        // arrPrint($companyProfile_alias_e);
        $str_target = $companyProfile_alias_e[2];
        $companyProfile_alias_c = str_replace($str_target, "<r>$str_target</span>", $companyProfile_alias);
        $p->addTags(
            array(
                "content" => $strData,
                "stop_time" => "",
                "profile_name" => $this->session->login['nama'],
                "printing" => "window.print();",
                "noteDetails" => "kosong",
                "fixedElements" => "kosong",
                "sign_1" => $sign_pihak1,
                "sign_2" => $sign_pihak2,
                "sign_3"         => $sign_pihak3,
                "sign_4"         => $sign_pihak4,
                "nomer" => $pre ? $tempTaskList[0]->no_pre_spk : $tempTaskList[0]->no_spk,
                "companyProfile" => $companyProfile,
                "companyProfile_alias" => $companyProfile_alias_c,
                "no_kontrak" => $tempTaskList[0]->no_kontrak,
                "tableHeader"    => $tableHeader,
                "titleHeader"    => $titleHeader,
                "barcodeSPK"    => $pre ? $tempTaskList[0]->no_pre_spk : $tempTaskList[0]->no_spk,
                "option" => $option,
//                "list_produk" => $list_produk,
//                "no_kontrak" => $list_produk,
//                "tgl_kontrak" => $list_produk,
//                "detail_kontrak" => $list_produk,
//                "tgl_spk" => $list_produk,
//                "nama_pihak1" => $list_produk,
//                "jabatan_pihak1" => $list_produk,
//                "alamat_pihak1" => $list_produk,
//                "nama_pihak2" => $list_produk,
//                "alamat_pihak2" => $list_produk,
//                "label_pekerjaan" => $list_produk,
//                "alamat_detail_pekerjaan" => $list_produk,
//                "masa_pelaksanaan_detail_pekerjaan" => $list_produk,
//                "nilai_pekerjaan" => $list_produk,
//                "nilai_pekerjaan" => $list_produk,
            )
        );

        $p->render();
        break;
}
