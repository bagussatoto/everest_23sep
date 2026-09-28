<?php

if (isset($items)) {

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }
    if (isset($infoModeLabelPenerimaan)) {
        echo "<div class='alert alert-info text-bold' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$infoModeLabelPenerimaan</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

    if (sizeof($items) > 0) {
        $contentElement = "";
        if (!empty($elements)) {
            $contentElement .= "<div class='col-lg-12 no-paddingd text-center' style='text-align:center;'>";
            $contentElement .= "<h4 class='text-blue text-left'>Please fill in details below</h4>";
            $tempCols = [];
            foreach ($elements as $eName => $pSpec) {
                if (isset($pSpec['type']) && $pSpec['type'] === "hidden") {
                    continue;
                }
                $hiddenBox = (isset($pSpec['hiddenBox']) && $pSpec['hiddenBox'] === "hidden") ? "hidden" : "";
                $hiddenSelect = (isset($pSpec['hiddenSelect']) && $pSpec['hiddenSelect'] === "hidden") ? "hidden" : "";
                $colHTML = "<div class='col-md-6 col-lg-6 $hiddenBox' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";
                $colHTML .= "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";
                $colHTML .= $pSpec['label'] . " ";
                if (!(isset($elementConfigs[$eName]['autoSelect']) && $elementConfigs[$eName]['autoSelect'])) {
                    $colHTML .= "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
                }
                if (isset($elementConfigMutasi[$eName])) {
                    $modalDialog = modalDialogBtn('&nbsp;', $elementConfigMutasi[$eName], 0, 'saldo');
                    $colHTML .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"$modalDialog\" ttarget='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                }
                $colHTML .= "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";
                $colHTML .= "</div>";
                if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
                    $colHTML .= "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
                }
                $colHTML .= "<div class='line_" . __LINE__ . " $hiddenSelect'>&nbsp;</div>";
                $colHTML .= $pSpec['string'];
                $colHTML .= "</div>";
                $tempCols[] = $colHTML;
                if (count($tempCols) === 2) {
                    $contentElement .= "<div class='col-lg-12 no-paddingxz'><div class='row row-eq-height'>";
                    $contentElement .= implode("", $tempCols);
                    $contentElement .= "</div></div>";
                    $tempCols = [];
                }
            }
            if (!empty($tempCols)) {
                $contentElement .= "<div class='col-lg-12 no-paddingxz'><div class='row row-eq-height'>";
                $contentElement .= implode("", $tempCols);
                $contentElement .= "</div></div>";
            }
            $contentElement .= "</div>";
        }

        //-------------------------------------------------------------------------------
        if (sizeof($shopingCartPaymentItemsColor) > 0) {
            $legend = "";
            foreach ($shopingCartPaymentItemsColor['colorCode'] as $ix => $ixSpec) {
                $bgcolor = $ixSpec["color"];
                $legend .= "<span class='btn btn-sm' style='background-color:$bgcolor;'> </span> " . $ixSpec['label'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            echo $legend;
        }
        //-------------------------------------------------------------------------------

        /*===bagian logic tambahan taxes untuk payment src*/
        if (isset($shopingCartAddTax) && sizeof($shopingCartAddTax) > 0) {
//            arrPrint($shopingCartAddTax);
            echo "<div class=''>";
            echo "<div class='text-center text-bold bg-red text-uppercase'> Tipe konsumen </div>";
            foreach ($shopingCartAddTax["fields"] as $sels => $label) {
                $checked = ($checkTaxes == $sels) ? "checked" : "";
                echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' id='switch_pajak' name='switch_pajak' $checked value='$sels'  
                              onclick=\"$('#result').load('" . $shopingCartAddTaxAction . "/?val='+this.value+'&p=$sels');\">
                              <span>$label</span>
                          </label>";
            }
            echo "</div>";
        }

        /*============end tambahan*/
        $jmlKolomHeader = sizeof($itemLabels) + 2;
        $viewTableBawah = "";

        // cekHijau($jmlKolomHeader);
        $viewTableBawah .= "<div ids='container_utama' class='table-responsive no-padding no-border'>";
        /*=============== BADGE PPN / NON PPN =================*/
        if (sizeof($arrHeaderElement) > 0) {
            foreach ($arrHeaderElement as $el => $eDetails) {
                $elLabel = $eDetails['label'];
                $elClass = $eDetails['class'];
                $viewTableBawah .= "<div class='$elClass'>";
                $viewTableBawah .= "<div class='text-center text-bold bg-yellow'> $elLabel </div>";
                foreach ($eDetails['subElements'] as $sels => $seDetails) {
                    $selsLabel = $seDetails['label'];
                    $selsValue = $seDetails['value'];
                    $selsMainTarget = $seDetails['srcMain'];
                    $selsItemsTarget = $seDetails['srcItem'];
                    $mainOverwrite = $seDetails['overWriteMain'];
                    $currentPPN = isset($main[$selsMainTarget]) ? $main[$selsMainTarget] : 0;
                    $ppnPersenItems = isset($items[0]['ppnVendor']) ? $items[0]['ppnVendor'] : 0;
                    $autoTerapkan = ($ppnPersenItems != $currentPPN) && ($selsValue == $currentPPN) ? true : false;
                    $checked = $selsValue == $currentPPN ? "checked" : "";

                    $jenisTr = isset($arrHeaderElementJenis) ? $arrHeaderElementJenis : "";
                    $viewTableBawah .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_ppn' value='$selsValue' $checked 
                              onclick=\"$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn='+this.value+'&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite');\">
                              <span>$selsLabel</span>
                          </label>";
                }
                $viewTableBawah .= "</div>";
            }
        }
        /*=============== BADGE PPN / NON PPN =================*/
        $viewTableBawah .= "<table class='table table-condensed no-padding table-bordered no-margin'>";
        /*===============header shoping cart======================*/
        if (isset($itemLabels)) {
            if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                $viewTableBawah .= "<tr class='bg-grey-2 text-uppercase'>";
                $viewTableBawah .= "<th style='width:1%;' class='text-muted text-center'>";
                $viewTableBawah .= "NO";
                $viewTableBawah .= "</th>";
                foreach ($itemLabels as $key => $label) {
                    $viewTableBawah .= "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                    $viewTableBawah .= $label;
                    $viewTableBawah .= "</th>";
                }
                if (isset($checkOpname) && ($checkOpname == true)) {
                    $viewTableBawah .= "<th style='width:1%;' class='text-muted text-center'>";
                    $viewTableBawah .= "V";
                    $viewTableBawah .= "</th>";
                }
                if (!$avoidRemove) {
                    $viewTableBawah .= "<th style='width:1%;' class='text-muted text-center'>";
                    $viewTableBawah .= "x";
                    $viewTableBawah .= "</th>";
                }
                $viewTableBawah .= "</tr>";
            }
        }

        /*===============body shoping cart=======================================*/
        $no = 0;
        $viewItems = "";
        foreach ($items as $iSpec) {
            if ($showItems) {

                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }

                //------
                if (isset($iSpec['background_pembayaran'])) {
                    $bgColor = $iSpec['background_pembayaran'];
                }
                //------

                $viewItems .= "<tr items id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                $viewItems .= "<td style='vertical-align:middle; width:1%' class='text-center'>";
                $viewItems .= $no;
                $viewItems .= "</td>";

                $colCtr = 0;
                $queryParams = "";
                $colID = array();
                $listMode = array();
                $readOnly = array();
                $qtyParam = "";
                if (isset($itemLabels['jml'])) {
                    $qtyParam = "+removeCommas(document.getElementById('jml_$no').value)";
                }
                foreach ($itemLabels as $key => $label) {
                    $listMode[$key] = "input";
                    $keyupEvent[$key] = "";
                    $keyUpStr[$key] = "";
                    if (array_key_exists($key, $keyUpEvents)) {
                        //                    cekbiru("$key has events");
                        if (sizeof($selectedPrices) > 0) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                        if (isset($keyupAction) && $keyupAction == true) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                    }
                    else {
                    }
                    if (strlen($keyupEvent[$key]) > 2) {
                        $keyUpStr[$key] = " onkeyup=\"" . $keyupEvent[$key] . "\" ";
                    }
                    if (in_array($key, $editableFields)) {
                        $readOnly[$key] = "";
                        if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                            $readOnly[$key] = "readonly_xz";
                        }
                        if (isset($paramsForceEditable[$key])) {
                            if ($paramsForceEditable[$key] == true) {

                            }
                            else {
                                $readOnly[$key] = "readonly_xxz";
                                $listMode[$key] = "text";
                            }
                        }
                    }
                    else {
                        $readOnly[$key] = "readonly_xxz";
                        $listMode[$key] = "text";
                    }
                    $colID[$key] = $key . "_" . $no;
                    if ($listMode[$key] == "input") {
                        $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                    }
                }
                foreach ($itemLabels as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $viewItems .= "<td align='left'>";
                    $colID = $key . "_" . $no;
                    $keyID = $key;
                    $noID = $no;
                    $tabIndexNum = $colCtr . $no;
                    $fieldVal = "";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        if (isset($iSpec[$key])) {
                            if (is_numeric($iSpec[$key])) {
                                $fieldVal = $iSpec[$key] + 0;
                            }
                            else {
                                $fieldVal = $iSpec[$key];
                            }
                        }
                    }
                    if (sizeof($minValues) > 0) {
                        $moq = isset($minValues['moq'][$iID]) ? $minValues['moq'][$iID] : 0;
                        $validateKey_up = true;
                    }
                    else {
                        $moq = 0;
                        $validateKey_up = false;
                    }
                    $keyupData = (($key == "qty" || $key == "jml") && $validateKey_up == true) ? "onkeydown=\"if(parseInt(this.value)<$moq){setTimeout(function(){ this.value='" . $iSpec[$key] . "'}, 1000);} \"" : "";

                    switch ($listMode[$key]) {
                        case "input":
                            $viewItems .= "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                            $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" $keyupData";
                            $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                            $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";

                            if (isset($unionSelectors['base'])) {
                                if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                    $viewItems .= str_replace("this", $baseInputName, $pemicuGerbang);
                                }
                                else {
                                    if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                        $viewItems .= $pemicuGerbangUnion;
                                    }
                                    else {//==biasa aja, memicu perubahan gerbang
                                        $viewItems .= $pemicuGerbangAsli;
                                    }
                                }
                            }
                            else {
                                $viewItems .= $pemicuGerbangAsli;
                            }

                            if (isset($keyupAction) && $keyupAction == true) {
                                $viewItems .= "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            else {
                                $viewItems .= "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            $viewItems .= ">";

                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                $viewItems .= "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    $viewItems .= "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                                else {
                                    $viewItems .= "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                            }
                            break;
                    }
                    echo "</td>";
                }

                //-----------------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    if (isset($iSpec['ceklist_opname']) && ($iSpec['ceklist_opname'] == 1)) {
                        $ceklist_checked = "checked";
                    }
                    else {
                        $ceklist_checked = "";
                    }
                    $viewItems .= "<td width='1%'>";
                    $viewItems .= "<input type='checkbox' $ceklist_checked 
                        onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
                    $viewItems .= "</td>";
                }
                //-----------------
                //region remover per row
                if (!$avoidRemove) {
                    $viewItems .= "<td width='1%'>";
                    $viewItems .= "<a class='text-black btn btn-warning btn-sm' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                    $viewItems .= "</td>";
                }
                //endregion

                $viewItems .= "</tr>";

                $viewItems .= "
                    <script>
                        \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).html(\"<i class='fa fa-check'></i>\");
                        \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).addClass(\"text-green text-bold pull-right\");
                    </script>
                ";

                if ($noteEnabled == true) {
                    $colspan2 = $imageEnable == true ? 1 : -1;
                    $colspan = sizeof($itemLabels) - $colspan2;
                    $viewItems .= "<tr>";
                    $viewItems .= "<td>&nbsp;</td>";
                    $viewItems .= "<td colspan='" . $colspan . "'>";
                    $noteVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                    if (isset($noteType)) {
                        switch ($noteType) {
                            case "textarea":
                                $viewItems .= "<textarea class='form-control' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >$noteVal</textarea>";
                                break;
                            case "text":
                            default:
                                $viewItems .= "<input type=text class='form-control' value='$noteVal' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                break;
                        }
                    }

                    $viewItems .= "</td>";
                    if ($imageEnable == true) {
                        $viewItems .= "<td colspan='2'>";
                        $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                        if (isset($imageType)) {
                            switch ($imageType) {
                                case "images":

                                    $file_e = "";
                                    $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    $file_e = urlencode($file);
                                    $viewItems .= "<div class='input-groups'>";
                                    if (strlen($imageVal) > 0) {
                                        $modals = array(
                                            "title" => "Attachment " . $iSpec['nama'],
                                            "body" => array($file),
                                        );
                                        $modal_e = urlencode(blobEncode($modals));
                                        $modal_l = base_url() . "Katalog/modal/$modal_e";
                                        $viewItems .= "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                        $viewItems .= "<input type='hidden' name='img_$iID' value='$file'>";
                                    }
                                    $viewItems .= "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";
                                    $viewItems .= "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";
                                    $viewItems .= "</form>";
                                    $viewItems .= "</div>";
                                    break;
                                case "text":
                                default:
                                    $viewItems .= "<input type=text class='form-control' value='$noteVal'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                    break;
                            }
                        }
                        $viewItems .= "</td>";
                    }
                    $viewItems .= "</tr>";
                }
                if ($pairedItemEnabled == true) {
                    if (sizeof($pairedItemField) > 0) {
                        $listModePairedItem = array();
                        $readOnlyPairedItem = array();
                        foreach ($pairedItemField as $key => $label) {
                            $listModePairedItem[$key] = "input";
                            if (in_array($key, $editableFields)) {
                                $readOnlyPairedItem[$key] = "";
                                if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                                    $readOnlyPairedItem[$key] = "readonly_x";
                                }
                            }
                            else {
                                $readOnlyPairedItem[$key] = "readonly_xx";
                                $listModePairedItem[$key] = "text";
                            }
                        }
                    }
                    $viewItems .= "<tr>";
                    $viewItems .= "<td>&nbsp;</td>";
                    $c_itemLabels = sizeof($itemLabels);
                    $c_pairedItemField = sizeof($pairedItemField);
                    $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
                    $viewItems .= "<td colspan='" . $c_colspan . "'>";

                    if (isset($selItems) && sizeof($selItems) > 0) {
                        $viewItems .= "<select
                                title='Choose one of the following...'
                                data-header='Ketik Nama/Kode/Folder/Barcode'
                                data-size='10'
                                data-container='body'
                                class='picker_$iID selectpicker form-control select2 show-tick'
                                data-style='btn-primary'
                                data-live-search='true'
                                classs='form-control'
                                onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?val='+(this.value)+'&iid=$iID'\"
                                >";

                        asort($selItems);

                        foreach ($selItems as $piID => $piName) {
                            if ($piID != $iSpec['id']) {
                                $selectedState = (isset($pairedItems[$iID]) && ($piID == $pairedItems[$iID]['id'])) ? "selected" : "";
                                $selItemsKodes = isset($selItemsKode[$piID]) ? $selItemsKode[$piID] : "-";
                                $selItemsFolders = isset($selItemsFolder[$piID]) ? $selItemsFolder[$piID] : "-";
                                $selItemsKeterangans = isset($selItemsKeterangan[$piID]) ? $selItemsKeterangan[$piID] : "-";
                                $selItemsBarcodes = isset($selItemsBarcode[$piID]) ? $selItemsBarcode[$piID] : "-";
                                $viewItems .= "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
                            }
                        }
                        $viewItems .= "</select>";
                    }
                    $viewItems .= "</td>";
                    $viewItems .= "<script> $('.picker_$iID').selectpicker(); </script>";

                    if (sizeof($pairedItemField) > 0) {
                        foreach ($pairedItemField as $key => $label) {
                            $pairedItems2ID = isset($pairedItems[$iID]['id']) ? $pairedItems[$iID]['id'] : 0;
                            $pairedItems2Qty = isset($pairedItems[$iID]['jml']) ? $pairedItems[$iID]['jml'] : 0;
                            $fieldVal = isset($pairedItems[$iID][$key]) ? $pairedItems[$iID][$key] : "";
                            $viewItems .= "<td>";
                            switch ($listMode[$key]) {
                                case "input":
                                    $viewItems .= "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    >";
                                    break;
                                case "text":
                                    if (is_numeric($fieldVal)) {
                                        $viewItems .= "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                                    }
                                    else {
                                        $viewItems .= "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
                                    }
                                    break;
                            }
                            $viewItems .= "</td>";
                        }
                    }
                    $viewItems .= "</tr>";
                }
            }
        }
        $viewItems2 = "";

        //region items2, kalau salah satunya untuk produksi dan konversi
        if (isset($items2) && sizeof($items2) > 0) {
            $viewItems2 .= "<tr items2 class='bg-info'>";
            $viewItems2 .= "<td colspan='$jmlKolomHeader'>";
            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            $viewItems2 .= "<div class='panel no-margin'>"; // anakan table
            $viewItems2 .= "<table class='table table-condensed table-striped no-padding no-border'>";
            if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                //region header table anakan
                $viewItems2 .= "<tr>";
                $viewItems2 .= "<td class='text-muted bg-grey-1 text-center'>";
                $viewItems2 .= "No";
                $viewItems2 .= "</td>";
                foreach ($itemLabels2 as $key => $label) {
                    $viewItems2 .= "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    $viewItems2 .= $label;
                    $viewItems2 .= "</td>";
                }
                $viewItems2 .= "</tr>";
            }

            $no = 0;

            $kurangStoks = array();
            foreach ($items2 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($items2_sum_kurang) && is_array($items2_sum_kurang)) {
                    if (isset($items2_sum_kurang[$iID])) {
                        $bgColor = "yellow";
                    }
                }
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                $viewItems2 .= "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                $viewItems2 .= "<td width='5%'>";
                $viewItems2 .= $no;
                $viewItems2 .= ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels2 as $key => $label) {
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                }

                foreach ($itemLabels2 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    $viewItems2 .= "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;

                    if (is_numeric($iSpec[$key])) {
                        $viewItems2 .= formatField($key, $iSpec[$key]);
                    }
                    else {
                        $viewItems2 .= $iSpec[$key];
                    }
                    $viewItems2 .= "</td>";
                }
                $viewItems2 .= "</tr>";
            }
            $viewItems2 .= "</table>";
            $viewItems2 .= "</div>";
            $viewItems2 .= "</td>";
            $viewItems2 .= "</tr>";
        }

        $viewItems3 = "";
        if (isset($items3) && sizeof($items3) > 0) {
            $viewItems3 .= "<tr items3 class='bg-info'>";
            $viewItems3 .= "<td colspan='$jmlKolomHeader'>";
            $viewItems3 .= "<div class='panel no-margin'>"; // anakan table
            $viewItems3 .= "<table class='table table-condensed table-striped no-padding no-border'>";
            if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
                $viewItems3 .= "<tr>";
                $viewItems3 .= "<td class='text-muted bg-grey-1 text-center'>No</td>";
                foreach ($itemLabels3 as $key => $label) {
                    $viewItems3 .= "<td class='text-muted bg-grey-1 text-center text-capitalize'>$label</td>";
                }
                $viewItems3 .= "</tr>";
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items3 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                $viewItems3 .= "<tr id='tr_" . $iSpec['id'] . "' bgcolor='$bgColor'>";
                $viewItems3 .= "<td width='5%'>" . $no . ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels3 as $key => $label) {
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                }
                foreach ($itemLabels3 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                        $viewItems3 .= "<td class='$cAlign'>";
                        $tabIndexNum = $colCtr . $no;
                        if (is_numeric($iSpec[$key])) {
                            $viewItems3 .= $iSpec[$key];
                        }
                        else {
                            $viewItems3 .= $iSpec[$key];
                        }
                        $viewItems3 .= "</td>";
                    }
                    $viewItems3 .= "</tr>";
                }
                if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                    $nr = 0;
                    foreach ($sumRows3 as $key => $label) {
                        $val = 0;
                        $nr++;
                        $bottom_borderless = $nr < sizeof($sumRows3) ? "bottom-borderless" : "";
                        if (isset($main[$key]) && $main[$key] > 0) {
                            $val = $main[$key];
                        }
                        else {
                            if (isset($addValues[$key]) && $addValues[$key] > 0) {
                                $val = $addValues[$key];
                            }
                        }
                        $viewItems3 .= "<tr class='bg-grey-01 3'>";
                        $viewItems3 .= "<td colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                        $viewItems3 .= "<td class='right-borderlesss'>" . formatField($key, $val) . "</td>";
                        $viewItems3 .= "</tr>";
                    }
                }
                $viewItems3 .= "</table>";
                $viewItems3 .= "</div>"; // anakan table
                $viewItems3 .= "</td>";
                $viewItems3 .= "</tr>";
            }
            //endregion body table anakan
        }

        $viewSumRows = "";
        /*=============================sumrows============================*/
        if (isset($sumRows) && sizeof($sumRows) > 0) {
            $nr = 0;
            foreach ($sumRows as $key => $label) {
                $val = 0;
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows) ? "bottom-borderless" : "";
                if (isset($main[$key]) && $main[$key] > 0) {
                    $val = $main[$key];
                }
                else {
                    if (isset($addValues[$key]) && $addValues[$key] > 0) {
                        $val = $addValues[$key];
                    }
                }
                if ($showItems) {
                    $viewSumRows .= "<tr sumRows class='bg-grey-01 0'>";
                    $viewSumRows .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    $viewSumRows .= "<td colspan='3' class='right-borderlesss'>";
                    $viewSumRows .= "<input type='text' id='$key' class='form-control text-right' readonly value='" . niceDecimal($val) . "' >";
                    $viewSumRows .= "</td>";
                    $viewSumRows .= "</tr>";
                }
            }
        }

        $viewSumRows2 = "";
        if (isset($sumRows2) && sizeof($sumRows2) > 0) {
            $viewSumRows2 .= "<!-- ===========sumRows2============= -->";
            $viewSumRows2 .= "<tr sumRows2 bgcolor='#e0e0e0'>";
            $viewSumRows2 .= "<td colspan='" . (sizeof($itemLabels2) + 1) . "' class='text-left text-muted'><span class='fa fa-cog'></span> additional fees</td>";
            $viewSumRows2 .= "</td>";
            $viewSumRows2 .= "</tr>";
            $nr = 0;
            foreach ($sumRows2 as $key => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows2) ? "bottom-borderless" : "";
                $viewSumRows2 .= "<tr bgcolor='#f0f0f5'>";
                $viewSumRows2 .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right bottom-borderless valign-m text-uppercase'>$label</td>";
                $viewSumRows2 .= "<td>";
                $viewSumRows2 .= $sumSpec2[$key];
                $viewSumRows2 .= "</td>";
                $viewSumRows2 .= "</tr>";
            }
        }

        $viewAddRows = "";
        if (sizeof($addRows) > 0) {
            $nr = 0;
            $targetLink = "";
            foreach ($addRowLabels as $k => $label) {
                $tic_biaya = "";
                if (isset($shopingCartTicBox[$k])) {
                    $targetLink = MODUL_PATH . $shopingCartTicBox[$k]["methode"];
                    $target_name = $shopingCartTicBox[$k]["name"];
                    $biaya_checked = isset($main[$target_name]) && $main[$target_name] == "true" ? "checked" : "";
                    $tic_biaya = "<span></span><input name='$target_name' class='validate-biaya' data-id='$target_name' type='checkbox' class='fform-control' $biaya_checked onclick=\"$('#result').load('" . $targetLink . "/?state='+this.checked+'&key=$target_name');\"></span>";
                }
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                $addRowBackground = isset($addBackground[$k]) ? $addBackground[$k] : "";
                $viewAddRows .= "<div style='padding: 6px;' class='row addRows table-bordered $rowHide $addRowBackground'>";
                $viewAddRows .= "<div class='col-md-7 col-lg-7 text-right'>";
                $viewAddRows .= "<span line='" . __LINE__ . "' colspan='" . sizeof($itemLabels) . "' id='label_$k' class='text-right $bottom_borderless valign-m text-uppercase'> $label $tic_biaya </span>";
                $viewAddRows .= "</div>";
                $viewAddRows .= "<div class='col-md-5 col-lg-5'>";
                $viewAddRows .= $addRows[$k];
                $viewAddRows .= "</div>";
                $viewAddRows .= "</div>";
            }
        }

        $viewResetShoppingCart = "";
        if ((!$avoidRemove) || (!$avoidRemoveAll_items)) {
            $addColspan = (isset($checkOpname) && ($checkOpname == true)) ? 3 : 2;
            $viewResetShoppingCart .= "<tr class='bg-grey-2'>";
            $viewResetShoppingCart .= "<td colspan='" . (sizeof($itemLabels) + $addColspan) . "'>";
            $viewResetShoppingCart .= "<span class='pull-left'>";
            $viewResetShoppingCart .= "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
            $viewResetShoppingCart .= "</span>";
            $viewResetShoppingCart .= "</td>";
            $viewResetShoppingCart .= "</tr>";
        }

        $viewTableBawah .= "$viewItems";
        $viewTableBawah .= "$viewItems2";
        $viewTableBawah .= "$viewItems3";
        $viewTableBawah .= "$viewSumRows";
        $viewTableBawah .= "$viewSumRows2";

        $viewTableBawah .= "<tr line='" . __LINE__ . "'>";
        $viewTableBawah .= "<td colspan='" . (count($itemLabels) + 1) . "'>";
        $viewTableBawah .= "<div class='container-fluid no-padding'>";
        $viewTableBawah .= "<div class='row'>";
        $viewTableBawah .= "<div class='col-md-8 col-lg-8 no-padding'>";
//        $viewTableBawah .= "<div class='cointainer-fluid'>";
        $viewTableBawah .= "$contentElement";
//        $viewTableBawah .= "</div>";
        $viewTableBawah .= "</div>";

        $viewTableBawah .= "<div class='col-md-4 col-lg-4'>";
//        $viewTableBawah .= "<div class='cointainer-fluid'>";
        $viewTableBawah .= $viewAddRows;
//        $viewTableBawah .= "</div>";
        $viewTableBawah .= "</div>";

        $viewTableBawah .= "</div>";
        $viewTableBawah .= "</div>";
        $viewTableBawah .= "</td>";
        $viewTableBawah .= "</tr>";

        $viewTableBawah .= $viewResetShoppingCart;

        $viewTableBawah .= "</table>";
        $viewTableBawah .= "</div>";

        echo $viewTableBawah;

        $customerID = $main['customerID'];
        $currentTr = $this->jenisTr;

        echo "<script>
                const wlDisabled = new Set([
                  'uangMukaPpnTerbayar'
                ]);
                function labelMencolok(key) {
                    var saldotext = $('#saldo_' + key).text();
                    var num_saldotext = Number(saldotext.replace(/\./g, ''));
                    let nilai_round = Number($('#nilai_round').val());
                    let lebih_bayar = Number($('#lebih_bayar').val());
                    let ketikan = $('#'+ key).val();
                            
                    if(num_saldotext > 0){
                          $('#label_' + key).addClass('text-red text-bold');
                          if (wlDisabled.has(key)) {
                            //console.log(key +' termasuk disabled list');
                          } 
                          else {
                            $('#' + key).prop('disabled', false);
                          }
                    }
                    else if(num_saldotext == 0){
                         $('#' + key).prop('disabled', true);
                    }
                            
                    let new_sisa = Number($('#new_sisa').val());
                    if(new_sisa <= 0 && lebih_bayar == 0){
                        $('#nilai_entry').prop('disabled', true);
                    }
                    else {
                        $('#nilai_entry').prop('disabled', false).css('background-color','yellow');
                    }
                    if(ketikan > nilai_round){
                        swal({
                            title: 'peringatan.. !!',
                            html: 'maximal value yang bisa digunakan ' + addCommas(nilai_round) + ', sekarang ' + addCommas(ketikan)
                        });
                        $('#' + key).css('background-color','#fdb5b5');
                                
                        return false; 
                    }
                }
                        
                var labelKeis = ['credit_amount', 'point_konsumen_qtt','uang_muka_dipakai','uangMukaPpnTerbayar'];                        
                labelKeis.forEach(function(item) {                        
                    labelMencolok(item);   
                });
                        
                labelKeis.forEach(function(item) {
                    $('#' + item).on('blur', function() {
                        let ketikan = $('#'+ item).val();
                        let saldotext = $('#saldo_' + item).text();
                        let num_saldotext = Number(saldotext.replace(/\./g, ''));
                        let nilai_round = Number($('#nilai_round').val());
                    });                                            
                });

                function callViewLepasRelasi(){
                    showModal('" . base_url() . $this->modul . "/_shoppingCart/viewLepasRelasi/749?debuger=1','LEPAS RELASI UANG MUKA DARI SO LAIN');
                }
                
                function initSaldoUM(){
                    var url = '" . base_url() . $this->modul . "/Transaksi/saldoUmTotal/$currentTr/$customerID'
                    $.ajax(url)
                    .always(function(data) {
                        const json_data = JSON.parse(data);
                        jQuery.each(json_data, function(aa,bb){
                            console.log(bb.el_nama);
                            if(bb.el_nama=='titipanNonRelasi'){
                                console.log('bb.saldo: ', bb.saldo);
                                if( $('#saldoRelasiPO').length ){
                                    $('#saldoRelasiPO').html('<r>Total saldo dari PO Lain: <b><a>'+addCommas( (bb.saldo*1).toFixed(0) )+'</a></b></r>')
                                    .removeClass('hidden');
                                    $('#linkViewLepasRelasi').removeClass('hidden');
                                }
                            }
                            if(bb.el_nama=='uangMukaPpnTerbayar'){
                                console.log('bb.saldo: ', bb.saldo);
                                if( $('#saldoRelasiSO').length ){
                                    $('#saldoRelasiSO').html('<r>Total saldo dari SO Lain: <b><a>'+addCommas( (bb.saldo*1).toFixed(0) )+'</a></b></r>')
                                    .removeClass('hidden');
                                    $('#linkViewLepasRelasi').removeClass('hidden');
                                }
                            }
                            if(bb.el_nama=='titipanRelasi'){
                                console.log('bb.saldo: ', bb.saldo);
                                if($('#saldo_uang_muka_nonrelasi_dipakai').length){
                                    $('#saldo_uang_muka_nonrelasi_dipakai').html('<r>'+addCommas( (bb.saldo*1).toFixed(0) )+'</r>');
                                }
                            }
                            if(bb.el_nama=='creditAmount'){
                                console.log('bb.saldo: ', bb.saldo);
                                if($('#saldo_credit_note_diskon').length){
                                    $('#saldo_credit_note_diskon').html('<r>'+addCommas( (bb.saldo*1).toFixed(0) )+'</r>');
                                }
                            }
                        });
                    });
                }

                setTimeout(function(){
                    initSaldoUM();
                }, 200);
                        
            </script>";

        //--------
        $faktur = "";
        if (count($shopingCartFakturItems) > 0) {
            if (isset($showFormulirFaktur) && ($showFormulirFaktur == true)) {
                $faktur .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
                $faktur .= "<table class='table'>";
                $faktur .= "<tr class='bg-primary'>";
                foreach ($shopingCartFakturParam["fields"] as $ff => $ff_abels) {
                    $faktur .= "<th>$ff_abels</th>";
                }
                $faktur .= "</tr>";
//arrPrintCyan($shopingCartFakturParam["editableFields"]);
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    //-----------------------------
                    $faktur .= "<tr>";
                    $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                    foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                        if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                            $inputType = $shopingCartFakturParam["editableFields"][$fff];
                            $defValues = isset($shopingCartFakturItems[$fff]) ? $shopingCartFakturItems[$fff] : "";
                            if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                                $classinputType = "";
                                $labels = "tic disini jika faktur belum tersedia";
                                $vals = "checked";
                                $checked = isset($shopingCartFakturItems[$fff]) && $shopingCartFakturItems[$fff] == "true" ? $vals : "";
                            }
                            else {
                                $classinputType = "form-control ";
                                $labels = "";
                                $vals = "value";
                                $checked = "";
                            }
                            $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name)\">";
                        }
                        else {
                            $value = formatField($fff, $shopingCartFakturItems[$fff]);
                        }
                        $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'>$labels </span></td>";
                    }
                    $faktur .= "</tr>";
                    //-----------------------------
                }
                else {
                    if (sizeof($formulirFaktur) > 0) {
                        $countItems = sizeof($items);
                        if ($countItems == 1) {
//                            unset($shopingCartFakturParam["editableFields"]["dpp_final"]);
                        }
                        foreach ($formulirFaktur as $ctt => $fSpec) {
                            $faktur .= "<tr>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                                $labels = "";
                                $btn_formulir = "";
                                $btn_formulir_delete = "";
//                                cekHere("[$fff]");
                                if (isset($shopingCartFakturParam["editableFields"][$fff])) {
//                                    cekKuning("[$fff]");
                                    $inputType = $shopingCartFakturParam["editableFields"][$fff];
                                    $defValues = isset($fSpec[$fff]) ? $fSpec[$fff] : "";
                                    if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                                        $classinputType = "";
                                        $labels = ($ctt == 0) ? "tic disini jika faktur belum tersedia" : "";
                                        $vals = "checked";
                                        $checked = isset($fSpec[$fff]) && $fSpec[$fff] == "true" ? $vals : "";
                                        $value = ($ctt == 0) ? "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name,$ctt)\">" : "";
                                        $btn_formulir = (($ctt == 0) && ($countItems > 1)) ? "<button class='btn btn-warning' onclick=\"$('#result').load('$cloneFormulirFaktur');\"><span class='glyphicon glyphicon-plus'></span> Tambah Formulir Faktur</button>" : "";
                                        $btn_formulir_delete = ($ctt > 0) ? "<button class='btn btn-danger' onclick=\"$('#result').load('$cloneFormulirFakturDelete/$ctt');\"><span class='glyphicon glyphicon-trash'></span></button>" : "";
                                    }
                                    else {
                                        $classinputType = "form-control ";
                                        $labels = "";
                                        $vals = "value";
                                        $checked = "";
                                        $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name,$ctt)\">";
                                    }
                                }
                                else {
//                                    cekHitam("[$fff]");
                                    $value = formatField($fff, $fSpec[$fff]);
                                }

                                $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'> $labels </span>";
                                $faktur .= $btn_formulir;
                                $faktur .= $btn_formulir_delete;
                                $faktur .= "<script>
                                        function eksekutor(nilai,nama,ctt) {
                                            $('#result').load('$linkFaktur'+ctt+'?nilai='+nilai+'&nama='+nama)
                                        }
                                        </script>";
                                $faktur .= "</td>";

                                if (is_numeric($fSpec[$fff])) {
                                    if (!isset($sub_total_bawah[$fff])) {
                                        $sub_total_bawah[$fff] = 0;
                                    }
                                    $sub_total_bawah[$fff] += $fSpec[$fff];
                                }
                            }
                            $faktur .= "</tr>";
                        }
                        if (sizeof($formulirFaktur) > 1) {
                            $bgcolor = isset($formulirFakturStyle["bgcolor"]) ? $formulirFakturStyle["bgcolor"] : "";
                            $faktur .= "<tr style='background-color:$bgcolor;'>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $value = isset($sub_total_bawah[$fff]) ? formatField($fff, $sub_total_bawah[$fff]) : "";
                                $faktur .= "<td id='td_$fff' class='text-bold' style='font-size:15px;'>$value";
                                $faktur .= "</td>";
                            }
                            $faktur .= "</tr>";
                        }
                    }
                }

                $faktur .= "</table>";
                $faktur .= "<div id='wr_skip_efakture'></div>";
                $faktur .= "</div>";
//                $faktur .= "<script>
//                                var skip_faktur = $('#skip_faktur').prop('checked');
//                                var dateFaktur = $('#dateFaktur').val();
//                                var eFaktur = $('#eFaktur').val();
//                                if(skip_faktur == false && dateFaktur == '' && eFaktur == '' && konfirmasi_cek == true){
//                                    $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
//                                    $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
//                                    $('#dateFaktur').css('border-color', 'red');
//                                    $('#eFaktur').css('border-color', 'red');
//                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
//                                    // swal({type: 'warning',title: 'Upss..',html: 'Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia'});
//                                    konfirmasi_cek = false;
//                                    $('#wr_skip_efakture').html('<r>Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia</r>');
//                                }
//                                if(nilai_entry > 0 && isCa == 0 && konfirmasi_cek == true){
//                                    $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
//                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
//                                    konfirmasi_cek = false;
//                                }
//                                else if(nilai_entry == 0 && isCa == 0) {
//                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
//                                }
//                                $('input[name=\"cash_account\"]').change(function(){
//                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
//                                });
//                            </script>";

            }

        }
        echo $faktur;

        $uminv = "";
        if (count($data_inv) > 0) {
            $uminv .= "<div>$label_inv</div>";
            $uminv .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
            $uminv .= "<table class='table'>";
            $uminv .= "<tr class='bg-primary'>";
            $uminv .= "<th>No.</th>";
            foreach ($headers_inv as $ff => $ff_abels) {
                $uminv .= "<th>$ff_abels</th>";
            }
            $uminv .= "</tr>";
            $nov = 0;
            foreach ($data_inv as $dataSpec) {
                $nov++;
                //-----------------------------
                $uminv .= "<tr>";
                $uminv .= "<td id='td_$fff'>$nov</td>";
                foreach ($headers_inv as $fff => $f_labels) {
                    $value = formatField_he_format($fff, $dataSpec[$fff]);
                    $uminv .= "<td id='td_$fff'>$value</td>";
                    if (is_numeric($dataSpec[$fff])) {
                        if (!isset($dataSum_inv[$fff])) {
                            $dataSum_inv[$fff] = 0;
                        }
                        $dataSum_inv[$fff] += $dataSpec[$fff];
                    }
                }
                $uminv .= "</tr>";
                //-----------------------------
            }
            $uminv .= "<tr>";
            $uminv .= "<td id='td_$fff'>-</td>";
            foreach ($headers_inv as $fff => $f_labels) {
                $value = formatField_he_format($fff, $dataSum_inv[$fff]);
                $uminv .= "<td id='td_$fff'>$value</td>";
            }
            $uminv .= "</tr>";
            $uminv .= "</table>";
            $uminv .= "</div>";

        }
        echo $uminv;

        if (isset($fixedNote)) {
            echo "<div class='alert alert-danger' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$fixedNote</span>";
            echo "</div>";
        }

        /*---------------------sum CBM CKD------------------------------------*/
        $volume_gross = "";
        $berat_gross = "";
        if (isset($detilSizeBar)) {
            if (sizeof($detilSizeBar) > 0) {
                $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                $volume = isset($detilSizeBar['volume']) ? $detilSizeBar['volume'] : 0;
                $berat = isset($detilSizeBar['berat']) ? $detilSizeBar['berat'] : 0;

                echo "<div class='row bg-danger' style='background: #ffdecf;padding: 7px;'>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                        </div>
                     </div>";
                echo "</div>";
            }
        }

        //--------
        if (isset($checkOpnameEnabled) && ($checkOpnameEnabled == true)) {
            $noteEncode1 = blobEncode($checkOpnameNote1);
            $noteEncode2 = blobEncode($checkOpnameNote2);

            if (isset($checkOpnameCek1) && ($checkOpnameCek1 == 1)) {
                $ceklist_checked_1 = "checked";
            }
            else {
                $ceklist_checked_1 = "";
            }
            if (isset($checkOpnameCek2) && ($checkOpnameCek2 == 1)) {
                $ceklist_checked_2 = "checked";
            }
            else {
                $ceklist_checked_2 = "";
            }

            $strcekNote = "<br><div class='alert alert-danger' style='text-align: left;'>";

            $strcekNote .= "<input type='checkbox' value='' $ceklist_checked_1
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note1=$noteEncode1';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote1</span>";

            $strcekNote .= "<br><input type='checkbox' value='' $ceklist_checked_2
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note2=$noteEncode2';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote2</span>";

            $strcekNote .= "</div>";
            echo $strcekNote;
        }

        if (sizeof($inputs) > 0) {
            echo "<div class='col-lg-12 no-padding' style='margin-top:5px;'>";
            echo "<div class='alert alert-info-dot'>";
            echo "<h4 class='text-left'>additional values</h4>";
            echo "<table class='table table-condensed'>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td class='text-muted'>";
                echo $inputLabels[$eName];
                echo "</td>";
            }
            echo "</tr>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td>";
                echo $eStr;
                echo "</td>";
            }
            echo "</div>";
            echo "</div>";
            echo "</tr>";
            echo "</table class='table table-condensed'>";
            echo "</div class='panel-default'>";
            echo "</div class='panel'>";
        }

        if (isset($previewJurnal) && sizeof($previewJurnal) > 0) {
            $headersJurnal = $previewJurnal['header'];
            foreach ($previewJurnal['jurnal'] as $cabangID => $subItems) {
                if (sizeof($subItems) > 0) {
                    $cabangNama = isset($previewJurnal['cabang'][$cabangID]) ? $previewJurnal['cabang'][$cabangID] : "";
                    echo "<h4 class='text-blue' style='text-align: left;margin-top: 10px;'><span class='fa fa-book'></span> preview journal entries ($cabangNama)</h4>";
                    echo "<div class='tabel table-responsive'>";
                    echo "<table class='table table-condensed'>";
                    echo "<tr bgcolor='#f0f0f0'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        echo "$label";
                        echo "</td>";
                    }
                    echo "</tr>";
                    foreach ($subItems as $iSpec) {
                        echo "<tr>";
                        foreach ($headersJurnal as $key => $label) {
                            echo "<td style='text-align: left;'>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                            if (is_numeric($iSpec[$key])) {
                                if (!isset($total[$cabangID][$key])) {
                                    $total[$cabangID][$key] = 0;
                                }
                                $total[$cabangID][$key] += $iSpec[$key];
                            }
                        }
                        echo "</tr>";
                    }
                    echo "<tr style='font-size: 15px;font-weight: bold;'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        if (isset($total[$cabangID][$key])) {
                            echo formatField($key, $total[$cabangID][$key]);
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                    echo "</table>";
                    echo "</div>";
                }
                else {
                    echo "<div class='text-center text-warning'>";
                    echo "- no journal affected by this transaction -<br><br>";
                    echo "</div class='text-center text-warning'>";
                }
            }
        }

        if (isset($viewDescriptionNote) && ($viewDescriptionNote == true)) {
            echo "<span>Catatan:</span>";
            echo "<div class=\"box-footer bg-gray\">";
            echo "<div class=\"row\">";
            echo "<div class=\"col-md-12\">";
            echo "<textarea class=\"form-control\" placeholder=\"description note\"
                  style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\"
                  onblur=\"document.getElementById('result').src='$columnRecorderTarget/description?val='+encodeURIComponent(this.value);\"
                >$default_description</textarea>";
            echo "</div class=\"col-md-12\">";
            echo "</div class=\"row\">";
            echo "</div class=\"box-footer bg-gray\">";
        }

        echo "<script>
                    if( $('span[keyid=qty_debet]').length > 0 ){
                        top.shoppingCardValidator()
                        //top.console.log('perlu validator shoppingcart');
                    }
                    else{
                        //top.console.error('tidak perlu validator shoppingcart');
                    }
                </script>";

        if (($tipe_penjualan == 1) || $tipe_penjualan == 11) {// marketplace, || project
            echo "<script>
                $(\"input[name = 'switch_pajak']\").prop('disabled', true);            
                </script>";
        }
        if (($mode_penerimaan == "uangmuka")) {
            echo "<script>
                    $('#dp_nilai').attr('disabled', true);     
                    </script>";
        }

        //--------
        if (count($arrItemTidakDibayar) > 0) {
            echo "<script>
                    top.document.getElementById('checkbox_payment').disabled=true;
                    top.document.getElementById('btnSave').disabled=true;
                    console.log('HIHIHIHI');
                </script>";
        }
        else {
            echo "<script>
                    top.document.getElementById('checkbox_payment').disabled=false;
                    console.log('HOHOHOHOH');
                </script>";
        }
    }
    else {
        echo "<div class='panel-body'>";
        echo "<div class='text-danger'>";
        echo "- <strong>you have not chosen any item yet</strong> -<br>";
        echo "<small>you can do so by selecting items from available selectors</small><br>";
        echo "</div class='text-warning'>";
        echo "</div class='panel-body'>";

        echo "<script>
                var paymentSrc=$('.paymentSrc')
                    jQuery.each(paymentSrc,function (a,b) {
                        $(b).prop('disabled',false);   
                        // console.log($(b).parent().parent());
                    })
                close_holdon();
            </script>";

        /*
         * ini milik setor PPN Bulanan*/
//        echo "<div class='panel-body'>";
//        echo "<div class='text-danger text-center'>";
//        echo "- <strong>KAMU BELUM MEMILIH PPN KELUARAN/PPN MASUKAN</strong> -<br>";
//        echo "<small>KAMU SETIDAK NYA HARUS MEMILIH SATU (1) PPN KELUARAN dan SATU (1) PPN MASUKAN</small><br>";
//        echo "</div>";
//        echo "</div>";

        echo "<script>
                    top.document.getElementById('checkbox_payment').disabled=true;
                    console.log('HAHAHAHA');
                </script>";
    }

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }
}
?>