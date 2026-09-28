<?php
echo "<style>
    .bg-highlight {
        background-color: #fff3cd !important;
    }
    .is-invalid {
        background-color: #f8d7da !important;
        border-color: #f5c6cb !important;
    }
</style>";
if (isset($items)) {

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

    // cekHere("hui");

    if (sizeof($items) > 0) {

        /*===bagian logic tambahan taxes untuk payment src*/
        if (isset($shopingCartAddTax) && sizeof($shopingCartAddTax) > 0) {
            echo "<div class=''>";
            echo "<div class='text-center text-bold bg-red text-uppercase'> Tipe konsumen </div>";
            foreach ($shopingCartAddTax["fields"] as $sels => $label) {
                $checked = $checkTaxes == $sels ? "checked" : "";
                echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_pajak' $checked value='$sels'  onclick=\"$('#result').load('" . $shopingCartAddTaxAction . "/?val='+this.value+'&p=$sels');\">
                              <span>$label</span>
                          </label>";
            }
            echo "</div>";
        }

        /*============end tambahan*/
        $jmlKolomHeader = sizeof($itemLabels) + 2;
        // cekHijau($jmlKolomHeader);
        echo "<div class='table-responsive no-padding no-border'>";
        /*=============== BADGE PPN / NON PPN =================*/
        if (sizeof($arrHeaderElement) > 0) {
            foreach ($arrHeaderElement as $el => $eDetails) {
                $elLabel = $eDetails['label'];
                $elClass = $eDetails['class'];
                echo "<div class='$elClass'>";
                echo "<div class='text-center text-bold bg-yellow'> $elLabel </div>";
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
                    // cekhitam($checked."$currentPPN");
                    echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_ppn' value='$selsValue' $checked 
                              onclick=\"$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn='+this.value+'&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite');\">
                              <span>$selsLabel</span>
                          </label>";

                    //                     if ($autoTerapkan) {
                    //                         echo "
                    //                         <script>
                    // //                            setTimeout( function(){ $('input[name=switch_ppn]:checked').click() }, 500);
                    //                             $('#result').load('" . base_url() . "Selectors/_processSelectProductPpn/select/466?ppn=$currentPPN&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget')
                    //                         </script>";
                    //                     }
                }
                echo "</div>";
            }
        }
        /*=============== BADGE PPN / NON PPN =================*/
        echo "<table class='table table-condensed no-padding table-bordered no-margin'>";
        /*===============header shoping cart======================*/
        if (isset($itemLabels)) {
            if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                echo "<thead>";
                echo "<tr class='bg-grey-2 text-uppercase'>";
                echo "<th width='2px' style='' class='text-muted text-center'>";
                echo "NO";
                echo "</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                    echo $label;
                    echo "</th>";
                }

                //----------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "V";
                    echo "</th>";
                }
                //----------
                if (!$avoidRemove) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "x";
                    echo "</th>";
                }
                echo "</tr>";
                echo "</thead>";
            }
        }
        echo "<tbody>";
        /*===============body shoping cart=======================================*/
        $no = 0;
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

                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='1%' style='vertical-align:middle;' class='text-center'>";
                echo $no;
                echo "</td>";
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
                        if ($key != "nama") {
                            $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                        }
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
                    echo "<td align='left'>";
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
//                            cekHere($key);
                            switch ($key) {
                                case "nama":
//                                    cekHere($iSpec['editTarget']);
                                    echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-left' style='color:$color;' value='" . ($fieldVal) . "' 
                                        onclick='this.select()'";
//                                    $pemicuGerbangAsli = "onblur=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams'; \" $keyupData";
                                    $pemicuGerbangAsli = "onblur=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "&$key='+this.value;\" ";
                                    echo $pemicuGerbangAsli;
                                    break;
                                default:
                                    echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' 
                                        onclick='this.select()' " . $keyUpStr[$key] . " ";
                                    $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                                    $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" $keyupData";
                                    $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                                    $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                                    $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                                    $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";

                                    if (isset($unionSelectors['base'])) {
                                        if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                            echo str_replace("this", $baseInputName, $pemicuGerbang);
                                        }
                                        else {
                                            if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                                echo $pemicuGerbangUnion;
                                            }
                                            else {//==biasa aja, memicu perubahan gerbang
                                                echo $pemicuGerbangAsli;
                                            }
                                        }
                                    }
                                    else {
                                        echo $pemicuGerbangAsli;
                                    }

                                    if (isset($keyupAction) && $keyupAction == true) {
                                        echo "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                                    }
                                    else {
                                        echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                                    }

                                    break;
                            }


                            echo ">";

                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
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
                    echo "<td width='1%'>";
                    echo "<input type='checkbox' $ceklist_checked 
                        onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
                    echo "</td>";
                }
                //-----------------
                //region remover per row
                if (!$avoidRemove) {
                    echo "<td width='1%'>";
                    echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left' 
                    onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                    echo "</td>";
                }
                //endregion

                echo "</tr>";

                echo "
            <script>
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).html(\"<i class='fa fa-check'></i>\");
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).addClass(\"text-green text-bold pull-right\");
            </script>
            ";

                cekHere(":: $pairedItemEnabled ::");

                $arrItems2IDs = array();
                foreach($items2 as $k2 => $v2){
                    $arrItems2IDs[$v2['id']] = $v2['id'];
                }

                if ($noteEnabled == true) {
                    $colspan2 = $imageEnable == true ? 1 : -1;
                    $colspan = sizeof($itemLabels) - $colspan2;
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . $colspan . "'>";
                    $noteVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                    if (isset($noteType)) {
                        switch ($noteType) {
                            case "textarea":
                                echo "<textarea class='form-control' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >$noteVal</textarea>";
                                break;
                            case "text":
                            default:
                                echo "<input type=text class='form-control' value='$noteVal' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                break;
                        }
                    }

                    echo "</td>";
                    if ($imageEnable == true) {
                        echo "<td colspan='2'>";
                        $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                        if (isset($imageType)) {
                            switch ($imageType) {
                                case "images":

                                    $file_e = "";
                                    $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    $file_e = urlencode($file);
                                    echo "<div class='input-groups'>";
                                    if (strlen($imageVal) > 0) {
                                        $modals = array(
                                            "title" => "Attachment " . $iSpec['nama'],
                                            "body" => array($file),
                                        );
                                        $modal_e = urlencode(blobEncode($modals));
                                        $modal_l = base_url() . "Katalog/modal/$modal_e";

                                        echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                        echo "<input type='hidden' name='img_$iID' value='$file'>";
                                    }

                                    echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";
                                    echo "<div class='text-left text-bold text-red'> images yang support upload<br>(jpg, jpeg, png) </div>";
                                    echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";

                                    echo "</form>";
                                    echo "</div>";

                                    break;
                                case "files":

                                    $file_e = "";
                                    $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    $file_e = urlencode($file);
                                    echo "<div class='input-groups'>";

                                    if (strlen($imageVal) > 0) {
                                        $modals = array(
                                            "title" => "Attachment " . $iSpec['nama'],
                                            "body" => array($file),
                                        );
                                        $modal_e = urlencode(blobEncode($modals));
                                        $modal_l = base_url() . "Katalog/modal/$modal_e";

                                        echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><embed src='$file' class='img-rounder' height='100%' style='float: right;'></a>";
                                        echo "<input type='hidden' name='img_$iID' value='$file'>";
                                    }

                                    echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";
                                    echo "<div class='text-left text-bold text-red'> files yang support upload<br>(xls, xlsx, pdf, doc) </div>";
                                    echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading files ... ... ',showConfirmButton: false,timer:5000,});\">";

                                    echo "</form>";
                                    echo "</div>";

                                    break;

                                case "text":
                                default:
                                    echo "<input type=text class='form-control' value='$noteVal'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                    break;
                            }
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
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
                    echo "<tr>";
//                    echo "<td class='text-bold' style='padding: 14px;'>MATERIAL</td>";
                    $c_itemLabels = sizeof($itemLabels);
                    $c_pairedItemField = sizeof($pairedItemField);
                    $c_colspan = ($c_itemLabels - $c_pairedItemField + 1)+1;
                    echo "<td c_itemLabels=$c_itemLabels c_pairedItemField=$c_pairedItemField colspan='" . $c_colspan . "'>";
                    //==pairedItems, if any
                    if (isset($selItems) && sizeof($selItems) > 0) {
                        $pair_taget_sub = $pairedPairSub;
                        echo "<label style='font-size: 16px;text-decoration: underline;' class='col-md-2 text-left text-bold text-red' for='select_material'> Pilih Material: </label>";
                        $materialIsChecked = isset($main['non_material']) && $main['non_material'] == 1 ? "checked" : "";
                        echo "<label style='font-size: 14px;' class='col-md-4 text-left text-orange' for='prj_non_material'> centang disini, jika ingin project tanpa material <input $materialIsChecked type='checkbox' id='prj_non_material'></label>";
                        echo "<select
                                title='Pilih Material'
                                data-header='Ketik Nama Material'
                                id='select_material'
                                data-size='10'
                                data-container='body'
                                class='".$materialIsChecked."picker_$iID selectpicker form-control select2'
                                data-style='btn-primary'
                                data-live-search='true'
                                classs='form-control col-md-8'
                                onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?r=1&val='+(this.value)+'&iid=$iID&sub_target=$pair_taget_sub'\"
                                >";

                        asort($selItems);

                        foreach ($selItems as $piID => $piName) {
                            if (!in_array($piID,$arrItems2IDs) ) {
                                $selectedState = (isset($pairedItems[$iID]) && ($piID == $pairedItems[$iID]['id'])) ? "selected" : "";
                                $selItemsKodes = isset($selItemsKode[$piID]) ? $selItemsKode[$piID] : "-";
                                $selItemsFolders = isset($selItemsFolder[$piID]) ? $selItemsFolder[$piID] : "-";
                                $selItemsKeterangans = isset($selItemsKeterangan[$piID]) ? $selItemsKeterangan[$piID] : "-";
                                $selItemsBarcodes = isset($selItemsBarcode[$piID]) ? $selItemsBarcode[$piID] : "-";
                                echo "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
                            }
                            else{
                                echo "<option disabled class='text-red text-bold' data-subtext=' ✔' data-tokens='$piID ' value='$piID'>".$piName."</option>";
                            }
                        }
                        echo "</select>";
                    }
                    echo "</td>";
//                echo "<script>top.$('.select2').selectpicker();</script>";
//                echo "<script> setTimeout( function(){ top.$('.picker_$iID').selectpicker(); console.log('dari shopingcart picker_$iID') }, 100 ); </script>";
                    echo "<script> $('.picker_$iID').selectpicker(); </script>";
//                echo "<script> setTimeout( function(){ top.$('.select2').selectpicker(); console.log('dari shopingcart') }, 500 ); </script>";
                    if (sizeof($pairedItemField) > 0) {
                        foreach ($pairedItemField as $key => $label) {
                            $pairedItems2ID = isset($pairedItems[$iID]['id']) ? $pairedItems[$iID]['id'] : 0;
                            $pairedItems2Qty = isset($pairedItems[$iID]['jml']) ? $pairedItems[$iID]['jml'] : 0;
                            $fieldVal = isset($pairedItems[$iID][$key]) ? $pairedItems[$iID][$key] : "";
                            echo "<td>";
                            switch ($listMode[$key]) {
                                case "input":
                                    echo "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    >";
                                    break;
                                case "text":
                                    if (is_numeric($fieldVal)) {
                                        echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                                    }
                                    else {
                                        echo "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
                                    }
                                    break;
                            }
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                }

            }
        }

        //region items2, kalau salah satunya untuk produksi dan konversi
        if (isset($items2) && sizeof($items2) > 0) {
            echo "<tr items2 class='bg-info'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table class='table table-condensed table-striped no-padding no-border'>";
            echo "<thead>";

            if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }

                //remover per row items2
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "X";
                echo "</td>";
                echo "</tr>";
                //endregion
            }
            echo "</thead>";
            echo "<tbody>";
            $no = 0;
            //region body table anakan
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
                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                $colID = array();
                $listMode = array();
                $readOnly = array();
                $qtyParam = "";
                if (isset($itemLabels2['jml'])) {
                    $qtyParam = "+removeCommas(document.getElementById('jml_2_$no').value)";
                }

                foreach ($itemLabels2 as $key => $label) {
                    $listMode[$key] = "input";
                    $keyupEvent[$key] = "";
                    $keyUpStr[$key] = "";
                    if (array_key_exists($key, $keyUpEvents)) {
                        if (sizeof($selectedPrices) > 0) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
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
                    if (in_array($key, $editableFields2)) {
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
                    $colID[$key] = $key . "_2_" . $no;
                    if ($listMode[$key] == "input") {
                        $queryParams .= "&sub_target=produk&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                    }
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
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $colID = $key . "_2_" . $no;
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

                            $autoCalc = "top.calcShoppingCart(this);";


                            $reloader = "&r=1";
                            $reloader = ""; //tidak reload

                            echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                            $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams$reloader';} \" $keyupData";
                            $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                            $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";

                            if (isset($unionSelectors['base'])) {
                                if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                    echo str_replace("this", $baseInputName, $pemicuGerbang);
                                }
                                else {
                                    if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                        echo $pemicuGerbangUnion;
                                    }
                                    else {//==biasa aja, memicu perubahan gerbang
                                        echo $pemicuGerbangAsli;
                                    }
                                }
                            }
                            else {
                                echo $pemicuGerbangAsli;
                            }

                            if (isset($keyupAction) && $keyupAction == true) {
                                echo "onkeyup=\"$autoCalc document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            else {
                                echo "onkeyup=\"$autoCalc delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            echo ">";

                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField_he_format($key, $fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField_he_format($key, $fieldVal) . "</span>";
                                }
                            }
                            break;
                    }
                    echo "</td>";
                }
                echo "<td width='1%'>";
                if ($iSpec['removeTarget'] != NULL) {
                    echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left' 
                    onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                }
                else {
                    echo "";
                }

                echo "</td>";
                echo "</tr>";
            }
            //endregion

            echo "</tbody>";
            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";

            $arrItems3IDs = array();
            foreach($items3 as $k3 => $v3){
                $arrItems3IDs[$v3['id']] = $v3['id'];
            }
        }
        //endregion


            if(count($itemBiaya)>0){
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
                echo "<tr>";
//                    echo "<td class='text-bold' style='padding: 14px;'>MATERIAL</td>";
//                arrprint($itemBiaya);
                $c_itemLabels = sizeof($itemLabels);
                $c_pairedItemField = sizeof($pairedItemField);
                $c_colspan = ($c_itemLabels - $c_pairedItemField + 1)+1;
                echo "<td c_itemLabels=$c_itemLabels c_pairedItemField=$c_pairedItemField colspan='" . $c_colspan . "'>";
                //==pairedItems, if any
                if (isset($itemBiaya) && sizeof($itemBiaya) > 0) {

                    $addBiayaBtn = "<button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"
                            BootstrapDialog.show({
                                title:'Tambah Data Biaya Project Baru',
                                message: $('<div></div>').load('".base_url()."/statik/Data/add/DtaBiayaProduksiProject'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            });\"><i class='fa fa-plus'></i> buat biaya baru </button>
                    ";

                echo "<label style='font-size: 16px;text-decoration: underline;' class='col-md-2 text-left text-bold text-red text-underline' for='select_biaya'> Pilih Biaya: </label>";

                $biayaIsChecked = isset($main['non_biaya']) && $main['non_biaya'] == 1 ? "checked" : "";

                echo "<label style='font-size: 14px;' class='col-md-4 text-left text-orange' for='prj_non_biaya'> centang disini, jika ingin project tanpa biaya <input $biayaIsChecked type='checkbox' id='prj_non_biaya'></label>";

                    echo "<select
                                title='Pilih Biaya'
                                data-header='Ketik atau Biaya'
                                id='select_biaya'
                                data-size='10'
                                data-container='body'
                                class='".$biayaIsChecked."picker_$iID selectpicker form-control select2 show-tick'
                                data-style='btn-primary'
                                data-live-search='true'
                                classs='form-control col-md-8'
                                onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?r=1&val='+(this.value)+'&iid=0&sub_target=$itemBiaya_subgate'\"
                                >";

                    asort($itemBiaya);
                    foreach ($itemBiaya as $piID => $piNames) {
                            if ( !in_array($piID,$arrItems3IDs) ) {
                                $selectedState = (isset($itemBiaya[$iID]) && ($piID == $itemBiaya[$iID]['id'])) ? "selected" : "";
                                echo "<option data-subtext='' data-tokens='$piID ' value='$piID' $selectedState>".$piNames['nama']."</option>";
                            }
                            else{
                                echo "<option disabled class='text-red text-bold' data-subtext=' ✔' data-tokens='$piID ' value='$piID' $selectedState>".$piNames['nama']."</option>";
                            }
                    }
                    echo "</select>";
                }
                echo "</td>";
                echo "<script> $('.picker_$iID').selectpicker(); </script>";
                if (sizeof($itemBiaya) > 0) {
                    foreach ($pairedItemField as $key => $label) {
                        $pairedItems2ID = isset($itemBiaya[$iID]['id']) ? $itemBiaya[$iID]['id'] : 0;
                        $pairedItems2Qty = isset($itemBiaya[$iID]['jml']) ? $itemBiaya[$iID]['jml'] : 0;
                        $fieldVal = isset($itemBiaya[$iID][$key]) ? $itemBiaya[$iID][$key] : "";
                        echo "<td>";
                        switch ($listMode[$key]) {
                            case "input":
                                echo "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    >";
                                break;
                            case "text":
                                if (is_numeric($fieldVal)) {
                                    echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
                                }
                                break;
                        }
                        echo "</td>";
                    }
                }
                echo "</tr>";
            }

        //region items3
        if (isset($items3) && sizeof($items3) > 0) {



            echo "<tr items3 class='bg-info'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table class='table table-condensed table-striped no-padding no-border'>";

            if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
                //region header table anakan
                echo "<tr>";

                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";

                foreach ($itemLabels3 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }

                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "action";
                echo "</td>";

                echo "</tr>";
                //endregion
            }


            $no = 0;
            $colCtr=0;
            //region body table anakan
//            arrPrint($items3);
            $kurangStoks = array();
            foreach ($items3 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $colCtr++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr line='".__LINE__."' id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='1%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                $qtyParam ="";
                $listMode = array();
                $colID = array();
                foreach ($itemLabels3 as $key => $label) {
                    $listMode[$key] = "input";
                    $keyupEvent[$key] = "";
                    $keyUpStr[$key] = "";
                    if (array_key_exists($key, $keyUpEvents)) {
                        if (sizeof($selectedPrices) > 0) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels3 as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                        if (isset($keyupAction) && $keyupAction == true) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels3 as $k => $v) {
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
                    if (in_array($key, $editableFields3)) {
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
                    $colID[$key] = $key . "_biaya_" . $no;
                    if ($listMode[$key] == "input") {
                        $queryParams .= "&sub_target=biaya&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                    }
                }

//                foreach ($itemLabels3 as $key => $label) {
//
//                    //                if(in_array($key,$editableFields)){
//
//                    $colID = $key . "_biaya_" . $no;
//
//                    $queryParams .= $queryParams == "" ? "?1=1" : "";
//
//                    switch ($listMode[$key]) {
//                        case "input":
//                            $queryParams .= "&sub_target=biaya&$key='+removeCommas(document.getElementById('$colID').value)+'";
//                            break;
//                        case "text":
//                            $queryParams .= "&sub_target=biaya&$key='+removeCommas(document.getElementById('$colID').innerHTML)+'";
//                            break;
                    //                }
//
//
//                    if ($key == "jml") {
//                        $qtyParam .= "+removeCommas(document.getElementById('$colID').value)";
//                    }
//
//                    //                }
//                }
//                $colCtr=0;
                foreach ($itemLabels3 as $key => $label) {

                    $noID = $no;
                    $keyID=$key."_biaya";
                    $keyNoID=$key."_biaya_".$noID;

                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td line='".__LINE__."' key='$key' class='$cAlign'>";

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
//                            cekMerah($fieldVal);
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
//                            cekUngu($fieldVal);
                        }
                    }
                    else {
                        if (isset($iSpec[$key])) {
                            if (is_numeric($iSpec[$key])) {
                                $fieldVal = $iSpec[$key] + 0;
//                                cekHitam($fieldVal);
                            }
                            else {
                                $fieldVal = $iSpec[$key];
//                                cekBiru($fieldVal);
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
                            $autoCalc = "top.calcShoppingCart(this);";
                            $reloader = "&r=1";
                            $reloader = ""; //tidak reload
                            echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$keyNoID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                            $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams$reloader';} \" $keyupData";
                            $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                            $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";

                            if (isset($unionSelectors['base'])) {
                                if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                    echo str_replace("this", $baseInputName, $pemicuGerbang);
                                }
                                else {
                                    if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                        echo $pemicuGerbangUnion;
                                    }
                                    else {//==biasa aja, memicu perubahan gerbang
                                        echo $pemicuGerbangAsli;
                                    }
                                }
                            }
                            else {
                                echo $pemicuGerbangAsli;
                            }

                            if (isset($keyupAction) && $keyupAction == true) {
                                echo "onkeyup=\"$autoCalc document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            else {
                                echo "onkeyup=\"$autoCalc delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            echo ">";

                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                echo "<span line='".__LINE__."' keyid=$keyID noid=$noID id=$keyNoID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    echo "<span line='".__LINE__."' keyid=$keyID noid=$noID id=$keyNoID class='' style='color:$color;border:0px;'>" . formatField_he_format($key, $fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span line='".__LINE__."' key='$key' keyid=$keyID noid=$noID id=$keyNoID fieldVal='$fieldVal' class='form-control' style='color:$color;border:0px;'>" . formatField_he_format($key, $iSpec[$key]) . "</span>";
                                }
                            }
                            break;
                    }

//                    if (is_numeric($iSpec[$key])) {
//                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
//                        echo $iSpec[$key];
//                    }
//                    else {
//                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
//                        echo $iSpec[$key];
//                    }


                    echo "</td>";
                }

                echo "<td width='1%'>";
                if ($iSpec['removeTarget'] != NULL) {
                    echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left'
                    onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                }
                else {
                    echo "";
                }
                echo "</td>";

                echo "</tr>";
            }
            //endregion

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

                    echo "<tr class='bg-grey-01 3'>";
                    echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td class='right-borderlesss'>";
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion

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
                    echo "<tr class='bg-grey-01 0'>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td colspan='3' class='right-borderlesss'>";
                    echo "<input type='text' id='$key' class='form-control text-right' readonly value='" . niceDecimal($val) . "' >";
                    echo "</td>";
                    echo "</tr>";
                }

            }
        }
        if (isset($sumRows2) && sizeof($sumRows2) > 0) {

            echo "<!-- ===========sumRows2============= -->";
            echo "<tr bgcolor='#e0e0e0'>";
            echo "<td colspan='" . (sizeof($itemLabels2) + 1) . "' class='text-left text-muted'><span class='fa fa-cog'></span> additional fees</td>";
            echo "</td>";
            echo "</tr>";
            $nr = 0;
            foreach ($sumRows2 as $key => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows2) ? "bottom-borderless" : "";

                echo "<tr bgcolor='#f0f0f5'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right bottom-borderless valign-m text-uppercase'>$label</td>";
                echo "<td>";
                echo isset($sumSpec2[$key]) ? $sumSpec2[$key] : 0;
                echo "</td>";
                echo "</tr>";
            }
        }

        if (sizeof($addRows) > 0) {
            $nr = 0;
            foreach ($addRowLabels as $k => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                echo "<tr class='$rowHide'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                echo "<td colspan='2' class='text-right'>";
                echo $addRows[$k];
                echo "</td>";
                echo "</tr>";
            }
        }

        if($settingTermin==1){
            $total_project = $nilai_total_project*1;
            $total_project_last = $nilai_total_project_last;


            //IF total berubah dari setingan
            if($total_project*1 != $total_project_last*1){
                echo "<script>";
                echo "console.log('total_project: $total_project || total_project_last: $total_project_last');";
                echo "top.$('#shopping_cart').load('" . base_url() . $this->modul . "/" . "_shoppingCart/recalculation_termin/" . $this->jenisTr . "');";
                echo "console.log(' " . base_url() . $this->modul . "/" . "_shoppingCart/recalculation_termin/" . $this->jenisTr . " ');";
                echo "</script>";
                die();
            }


            $total_persen = 100;

            echo "<tr><td colspan='3'>&nbsp;</td></tr>";
            // echo "<tr><td colspan='3' class='text-center text-bold text-green'> ANDA TELAH MENGAKTIFKAN SETTINGAN TERMIN, <r>ISIKAN DATA BERURUTAN</r> ANDA AKAN MENDAPATKAN NOTIFIKASI KETIKA SUDAH WAKTUNYA MEMBUAT PENAGIHAN. <BR><r><i class='fa fa-warning blink'> </i> PERUBAHAN NILAI KONTAK AKAN MEMBUAT PENGATUAN YG TELAH DIBUAT KEMBALI KOSONG</r> </td></tr>";
            echo "<tr><td colspan='3' class='text-center text-bold text-green text-uppercase'> Anda telah mengaktifkan pengaturan termin. <r>Isi data secara berurut</r>, dan Anda akan mendapatkan notifikasi ketika sudah waktunya membuat penagihan.<BR><r><i class='fa fa-warning blink'> </i> Perubahan nilai kontrak akan mengosongkan kembali pengaturan yang telah dibuat.</r></td></tr>";

            echo "<tr>";

            echo "<td>";
            echo "<div url='$linkAddTerminSetting' id='setting_termin'>";
            echo "<div class='alert'><label>Pengaturan Termin <input id='checkSettingTermin' checked type='checkbox'></label></div>";
            echo "</div>";
            echo "</td>";

            echo "<td colspan='2'>";

            //=============================SETTING REF KONTRAK====================================
            echo "<form id='table_ref_form' method='post' enctype='multipart/form-data' name='table_ref_form' target='result' action='$linkAddTerminSetting/ref'>";
            echo "<table  id='table_ref' width='100%' class='table dataTable compact table-bordered table-hover-color-red table-striped'>";
            echo "<caption class='text-bold'>1# INFORMASI KONTRAK PROJECT PIHAK KE-3</caption>";
            echo "<thead>";
            echo "<tr class='bg-gray'>";
            echo "<th>UPLOAD REF. KONTRAK</th>";
            echo "<th>REF. KONTRAK</th>";
            echo "<th>Tgl Kontrak</th>";
            echo "<th>Tgl Mulai Project</th>";
            echo "<th>Tgl Akhir Project</th>";
            echo "<th>Notes</th>";
            echo "<th>&nbsp;</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            // arrPrintKuning($ref_kontrak);
            if( !empty($ref_kontrak) ){
                echo "<tr class='text-bold'>";
                foreach($ref_kontrak as $k => $val){
                    echo "<td>" . $val['upload_ref_kontrak'] . "</td>";
                    echo "<td>".$val['ref_kontrak']."</td>";
                    echo "<td>".$val['tgl_kontrak']."</td>";
                    echo "<td>".$val['tgl_mulai_project']."</td>";
                    echo "<td>".$val['tgl_akhir_project']."</td>";
                    echo "<td>".$val['notes_project']."</td>";
                    echo "<td><span id='ref_$k' jenis='ref' key='".$k."' class='btn btn-xs btn-danger btn_remove_ref' title='klik untuk hapus items'><i class='fa fa-trash'></i></span></td>";
                    $nilai_total_dp = $val['total']*1;
                    $nilai_persen_dp = $val['nilai']*1;
                }
                echo "</tr>";
                // echo "<script>
                //         toggleFormElements('table_dp_form', true);
                //         var enables_form = true;
                //      </script>";
            }
            else{
                echo "<tr>";

                echo "<td><input onclick=\"$(this).select();\" id='upload_ref_kontrak' name='upload_ref_kontrak' class='form-control' type='file'></td>";
                echo "<td><input onclick=\"$(this).select();\" id='input_ref_kontrak' name='ref_kontrak' class='form-control' type='text'></td>";
                echo "<td><input onclick=\"$(this).select();\" id='input_tgl_kontrak' name='tgl_kontrak' class='form-control' type='date'></td>";
                echo "<td><input onclick=\"$(this).select();\" id='input_tgl_mulai_project' name='tgl_mulai_project' class='form-control' type='date'></td>";
                echo "<td><input onclick=\"$(this).select();\" id='input_tgl_akhir_project' name='tgl_akhir_project' class='form-control' type='date'></td>";
                // echo "<td><input onclick=\"$(this).select();\" id='input_notes_project' name='notes_project' class='form-control' type='text'></td>";
                echo "<td><button onclick=\"\" id='input_notes_project' name='notes_project' class='btn btn-primary' type='button'>Buka note</button></td>";

                echo "<td><span id='btn_table_ref_form' onclick=\"$('#table_ref_form').submit();toggleFormElements('table_dp_form', true);\" class='btn btn-sm btn-success'><i class='fa fa-save'></i> SIMPAN</span></td>";

                echo "</tr>";

                echo "<script>
                        var startDate;
                        var endDate;
                        $('#input_tgl_kontrak').on('change', function(){
                            var startDate = this.value
                            $('#input_tgl_mulai_project').attr('min', startDate);
                        });
                        $('#input_tgl_mulai_project').on('change', function(){
                            var startDate = this.value
                            $('#input_tgl_akhir_project').attr('min', startDate);

                        });
                        $('#input_tgl_akhir_project').on('change', function(){
                            startDate = top.$('#input_tgl_mulai_project').val();
                            endDate = top.$('#input_tgl_akhir_project').val();
                        });
                        $('#table_ref_form').on('submit', delay_v2(function(){
                            top.$('input', top.$('#elTitle_tanggalStart').parent()).val(startDate).trigger('blur');
                            top.$('input', top.$('#elTitle_tenggatWaktu').parent()).val(endDate).trigger('blur');
                        }, 1000))


                         // toggleFormElements('table_dp_form', false);
                         // toggleFormElements('table_garansi_form', false);
                         // toggleFormElements('table_termin_form', false);
                         // var enables_form = false;
                     </script>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "<!-- Preview File -->
                <div id=\"filePreview\" class=\"mt-2\"></div>
                
                <!-- Modal Note -->
                <div class='modal fade' id='noteModal' tabindex='-1' role='dialog' aria-labelledby='noteModalLabel' aria-hidden='true'>
                  <div class='modal-dialog modal-lg' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header' style='padding: 5px;border-radius: 5px 5px 0 0;'>
                        <h4 class='modal-title' style='margin-left: 10px;
                    text-align: left;
                    color: #f9f9f9;
                    margin-top: 10px;'>Catatan Project</h4>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        <textarea id='noteText' name='notes_project' class='form-control' rows='6' placeholder='Tulis catatan proyek di sini...'></textarea>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Tutup</button>
                        <button type='button' class='btn btn-primary' id='saveNote'>Simpan Catatan</button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Hidden input for saving the note -->
                <input type=\"hidden\" name=\"notes_project_hidden\" id=\"notes_project_hidden\">
                ";
            echo "</form>";
            echo "<script>
                    $(document).ready(function () {
                    // Preview file
                    $('#upload_ref_kontrak').on('change', function () {
                        let file = this.files[0];
                        let previewContainer = $('#filePreview');
                        previewContainer.empty();
                
                        if (!file) return;
                
                        let fileType = file.type;
                
                        if (fileType.startsWith('image/')) {
                            let reader = new FileReader();
                            reader.onload = function (e) {
                                previewContainer.html('<img src=\"' + e.target.result + '\" class=\"img-thumbnail\" style=\"max-height: 200px;\">');
                            };
                            reader.readAsDataURL(file);
                        } else if (fileType === 'application/pdf') {
                            let url = URL.createObjectURL(file);
                            previewContainer.html('<embed src=\"' + url + '\" type=\"application/pdf\" width=\"100%\" height=\"300px\"/>');
                        } else {
                            previewContainer.html('<div class=\"text-muted\">File tidak bisa ditampilkan.</div>');
                        }
                    });
                
                    // Buka modal note
                    $('#input_notes_project').on('click', function () {
                        let currentNote = $('#notes_project_hidden').val();
                        $('#noteText').val(currentNote);
                        $('#noteModal').modal('show');
                    });
                
                    // Simpan catatan dari modal ke input hidden
                    $('#saveNote').on('click', function () {
                        let note = $('#noteText').val();
                        $('#notes_project_hidden').val(note);
                        $('#noteModal').modal('hide');
                    });
                });                                
                </script>";


            //=============================SETTING DP====================================
            echo "<form id='table_dp_form' name='table_dp_form' target='result' action='$linkAddTerminSetting/dp'>";
            echo "<table  id='table_dp' width='100%' class='table dataTable compact table-bordered table-hover-color-red table-striped'>";
            echo "<caption class='text-bold'>2# SETTING DP</caption>";
            echo "<thead>";
            echo "<tr class='bg-gray'>";
            echo "<th>NILAI DP (%) <br>dari TOTAL PROJECT</th>";
            echo "<th>Total (Rp) <r>Incl.PPN</r></th>";
            echo "<th>Keterangan DP</th>";
            echo "<th>&nbsp;</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            $nilai_total_dp = 0;
            $nilai_persen_dp = 0;
            if( !empty($dp) ){
                echo "<tr class='text-bold'>";
                foreach($dp as $k => $val){
                    echo "<td>".$val['persen']."%</td>";
                    echo "<td class='dp_total' orig='".$val['harga']."'>Rp ".number_format($val['harga'])."</td>";
                    echo "<td class='keterangan_dp'>".(isset($val['keterangan_dp']) ? $val['keterangan_dp'] : "-")."</td>";
                    echo "<td><span id='dp_$k' jenis='dp' key='".$k."' class='btn btn-xs btn-danger btn_remove_dp' title='klik untuk hapus items'><i class='fa fa-trash'></i></span></td>";
                    $nilai_total_dp += $val['harga']*1;
                    $nilai_persen_dp += $val['persen']*1;
                }
                echo "</tr>";
                echo "<script>
                        if(enables_form){                        
                            toggleFormElements('table_garansi_form', true);
                            var enables_form = true;
                        }
                    </script>";
            }
            else{
                echo "<tr>";
                echo "<td><input total_project='$total_project' onclick=\"$(this).select();\" id='input_dp' name='dp' class='form-control' value='0'></td>";
                echo "<td><input total_project='$total_project' onclick=\"$(this).select();\" id='input_total_dp' name='total_dp' class='form-control text-right' value='0'></td>";
                echo "<td><input id='input_keterangan_dp' name='keterangan_dp' class='form-control text-right' type='text'></td>";
                echo "<td><span onclick=\"$('#table_dp_form').submit()\" id='btn_table_dp_form' class='btn btn-sm btn-success'><i class='fa fa-save'></i> SIMPAN</span></td>";
                echo "</tr>";

                // echo "<script>
                //         $('#input_dp').on('keyup', function(){
                //             var total_project = $(this).attr('total_project');
                //             var current_value = $(this).val();
                //             var total_dp = total_project*(current_value/100);
                //             $('#input_total_dp').val(addCommas(total_dp.toFixed(0)));
                //         })
                //      </script>";
                echo "<script>
                        toggleFormElements('table_garansi_form', false);
                        toggleFormElements('table_termin_form', false);
                        var enables_form = false;
                     </script>";


            }

            echo "</tbody>";
            echo "</table>";
            echo "</form>";

//=============================SETTING GARANSI/RETENSI====================================
            echo "<form id='table_garansi_form' name='table_garansi_form' target='result' action='$linkAddTerminSetting/garansi'>";
            echo "<table id='table_retensi' width='100%' class='table dataTable compact table-bordered table-hover-color-red table-striped'>";
            echo "<caption class='text-bold'>3# SETTING GARANSI / RETENSI</caption>";

            echo "<thead>";
            echo "<tr class='bg-gray'>";
            echo "<th>NILAI GARANSI (%) <br>dari TOTAL PROJECT</th>";
            echo "<th>Total (Rp) <r>Incl.PPN</r></th>";
            echo "<th>Tgl Berakhirnya Garansi</th>";
            echo "<th>Keterangan Garansi</th>";
            echo "<th>&nbsp;</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            $nilai_total_garansi = 0;
            $nilai_persen_garansi = 0;
            if(!empty($garansi)){
                echo "<tr class='text-bold'>";
                foreach($garansi as $k => $val){
                    echo "<td>". $val['persen'] ."%</td>";
                    echo "<td class='garansi_total' orig='".$val['harga']."'>Rp ". number_format($val['harga']) ."</td>";
                    echo "<td class='tgl_akhir_garansi'>". date('Y-m-d', strtotime($val['tgl_akhir_garansi'])) ."</td>";
                    echo "<td class='keterangan_garansi'>". (isset($val['keterangan_garansi']) ? $val['keterangan_garansi'] : "-") ."</td>";
                    echo "<td><span id='garansi_$k' jenis='garansi' key='". $k ."' class='btn btn-xs btn-danger btn_remove_garansi' title='klik untuk hapus items'><i class='fa fa-trash'></i></span></td>";
                    $nilai_total_garansi += $val['harga']*1;
                    $nilai_persen_garansi += $val['persen']*1;
                }
                echo "</tr>";
                echo "<script>
                        if(enables_form){                             
                            toggleFormElements('table_termin_form', true);
                            var enables_form = true;
                        }
                     </script>";
            }
            else{
                echo "<tr>";
                echo "<td><input total_project='$total_project' onclick=\"$(this).select();\" id='input_garansi' name='garansi' class='form-control' value='0'></td>";
                echo "<td><input total_project='$total_project' onclick=\"$(this).select();\" id='input_total_garansi' name='total_garansi' class='form-control text-right' value='0'></td>";
                echo "<td><input required id='input_tgl_akhir_garansi' name='tgl_akhir_garansi' class='form-control text-center' type='date'></td>";
                echo "<td><input id='input_keterangan_garansi' name='keterangan_garansi' class='form-control text-center' type='text'></td>";
                echo "<td><span onclick=\"$('#table_garansi_form').submit()\" id='btn_table_garansi_form' class='btn btn-sm btn-success'><i class='fa fa-save'></i> SIMPAN</span></td>";
                echo "</tr>";

                echo "<script>
                        $('#input_garansi').on('keyup', function(){
                            var total_project = $(this).attr('total_project');
                            var current_value = $(this).val();
                            var total_garansi = total_project*(current_value/100);
                            $('#input_total_garansi').val(addCommas(total_garansi.toFixed(0)));
                        });
                        
                        toggleFormElements('table_termin_form', false);
                        var enables_form = false;
                     </script>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</form>";

            //=============================SETTING TERMIN====================================

            echo "<form id='table_termin_form' name='table_termin_form' target='result' action='$linkAddTerminSetting/termin'>";
            echo "<table id='table_termin' width='100%' class='table dataTable compact table-bordered table-hover-color-red table-striped'>";
            echo "<caption class='text-bold'>4# SETTING TERMIN</caption>";
            echo "<thead>";
            echo "<tr class='bg-gray'>";
            echo "<th>No</th>";
            echo "<th>LABEL<br>PERIHAL</th>";
            echo "<th class='text-center'>PROSENTASE PEKERJAAN<br>(%)</th>";
            echo "<th class='text-center'>PROSENTASE YG DIMINTAKAN<br>(%)</th>";
            echo "<th class='text-center'>TOTAL (Rp) <br><r>Incl.PPN</r></th>";
            echo "<th class='text-center'>&nbsp;</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            $limit = 100;
            $progress = 0;
            $total = 0;
            $no = 0;
            $total_pengerjaan=0;

            $last_termin = count($termin)>0 ? count($termin)+1 : 1;

            if(count($termin)*1>0){
                foreach($termin as $key => $row){
                    $progress += $row['persen']*1;
                    $total += $row['harga']*1;
                    $no++;
                    if(!empty($termin_label)){
                        echo "<tr class='text-bold'>";
                        echo "<td>$no</td>";
                        foreach($termin_label as $k => $tLabel){
                            $style = "";
                            $attr = "";
                            $class = "";
                            $label = "";
                            if(is_array($tLabel)){
                                $style = $tLabel['style'];
                                $attr = $tLabel['attr'];
                                $class = $tLabel['class'];
                                $label = $tLabel['label'];
                            }
                            else{
                                $label = $tLabel;
                            }
                            echo "<td class='$class $k'>".$row[$k]."</td>";
                        }
                        echo "<td class='text-right termin_subtotal' orig='".$row['harga']."'>Rp ".number_format($row['harga'])."</td>";
                        echo "<td><span id='termin_$key' jenis='termin' key='".$key."' class='btn btn-xs btn-danger btn_remove_termin' title='klik untuk hapus items'><i class='fa fa-trash'></i></span></td>";
                        echo "</tr>";
                        $total_pengerjaan += $row['harga']*1;
                    }
                }
            }

            $nilaiA = floor(($nilai_total_dp*1) + ($nilai_total_garansi*1) + ($total_pengerjaan*1));
            $nilaiB = floor($total_project*1);

            if( $nilaiA < $nilaiB ){

                if(!empty($termin_label)){
                    echo "<tr>";
                    echo "<td>".($no+1)."</td>";
                    foreach($termin_label as $k => $tLabel){
                        $style = "";
                        $attr = "";
                        $class = "";
                        $label = "";
                        if(is_array($tLabel)){
                            $style = $tLabel['style'];
                            $attr = $tLabel['attr'];
                            $class = $tLabel['class'];
                            $label = $tLabel['label'];
                        }
                        else{
                            $label = $tLabel;
                        }
                        echo "<td><input $attr style='$style' onclick='this.select()' total_project='$total_project' id='new_termin_$k' name='$k' placeholder='Termin ke-$last_termin' class='text-center form-control $class' value=''></td>";
                    }
                    echo "<td><input onclick='this.select()' total_project='$total_project' id='new_termin_total' name='harga' class='form-control text-right' value=''></td>";
                    echo "<td>
                            <button type=\"button\" id=\"btnGunakanSisa\" class=\"btn btn-sm btn-warning\" title=\"Gunakan Sisa\">Bayar semua</button>
                            <span onclick=\"$('#table_termin_form').submit()\" id='btn_table_termin_form' class='btn btn-sm btn-success'><i class='fa fa-save'></i></span>                            
                        </td>";
                    echo "</tr>";

                    echo "<script>
                        $('#new_termin_total').on('keyup', delay_v2(function(){
                            var curValue = removeCommas($(this).val());
                            var total_project = $(this).attr('total_project');
                            var persenNow = ((curValue/total_project)*100).toFixed(2);
                            setTimeout(function(){
                                $('#new_termin_penyelesaian').val( removeCommas( persenNow ) ).trigger('keyup')
                            }, 200)
                        },1000));

                        $('#new_termin_total').on('keyup', function(){
                            $(this).val( addCommas(removeCommas($(this).val())) );
                        });

                        $('#new_termin_persen').on('keyup', function(){
                            var total_project = $(this).attr('total_project');
                            var current_value = $(this).val();
                            var total_termin = total_project*(current_value/100);
                            $('#new_termin_total').val(addCommas(total_termin.toFixed(0)));
                            $('#new_termin_total').attr(total_termin.toFixed(0));

                            //cek total nya termasuk dp dan garansi
                            var value_dp = $('.dp_total').attr('orig')*1 != '' ? removeCommas($('.dp_total').attr('orig'))*1 : 0;
                            var value_garansi = $('.garansi_total').attr('orig') != '' ? removeCommas($('.garansi_total').attr('orig'))*1 : 0;
                            var tmp_value_termin = $('.termin_subtotal');
                            var value_termin_total = 0;
                            var total_total = 0;
                            jQuery.each(tmp_value_termin, function(a, b){
                                value_termin_total += $(b).attr('') != '' ? removeCommas($(b).attr('orig'))*1 : 0;
                            })

                            total_total = value_dp*1 + value_garansi*1 + value_termin_total*1;

                            //update total termin + new input
                            $('.live_termin').html(addCommas( (value_termin_total+total_termin).toFixed(0) ));
                            $('.live_persen_total_termin').html( (((value_termin_total+total_termin)/total_project)*100).toFixed(0) );

                            //update total seluruhan + new input
                            $('.live_total').html(addCommas( (total_total+total_termin).toFixed(0) ));
                            $('.live_persen_total').html( '(' + (((total_total+total_termin)/total_project)*100).toFixed(0) + '%)' );

                            if((total_total+total_termin)>total_project){
                                console.error('kebanyakan');
                                $('.live_total').parent().removeClass('text-olive').addClass('text-red');
                                $('.live_persen_total').addClass('text-red');
                            }
                            else{
                                if((total_total+total_termin) == total_project){
                                    $('.live_persen_total').removeClass('text-red').addClass('text-olive');
                                }
                                else{
                                    $('.live_persen_total').removeClass('text-olive').addClass('text-red');
                                }
                            }
                        })
                     </script>";
                }
            }

            echo "<tfoot>";

            $sisa_persen = $total_persen-($progress+$nilai_persen_garansi+$nilai_persen_dp);
            $sisa_rupiah = $total_project-($nilai_total_garansi+$nilai_total_dp+$total);

            if($sisa_persen*1 > 0){
                echo "<tr class='sisa_termin'>";
                echo "<th colspan='3' class='text-right'><span class='form-control no-border text-red text-bold'>SISA BELUM DI-TERMIN</span></th>";
                echo "<th class='sisa_persen text-center'><span class='form-control text-red text-bold'>".number_format($sisa_persen)."%</span></th>";
                echo "<th class='sisa_rupiah text-center'><span id='sisa_rupiah' data-sisa_rupiah='$sisa_rupiah' class='form-control text-red text-bold'>" . number_format($sisa_rupiah) . "</span></th>";
                echo "<th>&nbsp;</th>";
                echo "</tr>";
            }
            else{
                echo "<tr class='sisa_termin'>";
                echo "<th colspan='3' class='text-right'><span class='text-bold'>TOTAL TERMIN</span></th>";
                echo "<th class='sisa_persen text-center'><span class='text-bold'>".number_format($progress)."%</span></th>";
                echo "<th class='sisa_rupiah text-center'><span class='text-bold'>".number_format($total)."</span></th>";
                echo "<th>&nbsp;</th>";
                echo "</tr>";
            }

            if(!empty($termin)){
                echo "<tr>";
                echo "<th colspan='4' class='text-right'>NILAI TERMIN <r>(<span class='live_persen_total_termin'>$progress</span>%)</r></th>";
                echo "<th class='live_termin text-right'>".number_format($total_pengerjaan)."</th>";
                echo "<th>&nbsp;</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th colspan='4' class='text-right'>NILAI DP <r>($nilai_persen_dp%)</r></th>";
                echo "<th class='text-right'>".number_format($nilai_total_dp)."</th>";
                echo "<th>&nbsp;</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th colspan='4' class='text-right'>NILAI GARANSI <r>($nilai_persen_garansi%)</r></th>";
                echo "<th class='text-right'>".number_format($nilai_total_garansi)."</th>";
                echo "<th>&nbsp;</th>";
                echo "</tr>";

                $persen_color = ($progress+$nilai_persen_garansi+$nilai_persen_dp) == 100 ? " text-olive" : " text-red";

                echo "<tr class='text-olive'>";
                echo "<th colspan='4' class='text-right fa-2x'>TOTAL <span class='live_persen_total$persen_color'>(".($progress+$nilai_persen_garansi+$nilai_persen_dp)."%)</span></th>";
                echo "<th class='live_total text-right fa-2x'>".number_format($total_pengerjaan+$nilai_total_dp+$nilai_total_garansi)."</th>";
                echo "<th>&nbsp;</th>";
                echo "</tr>";

            }
            echo "</tfoot>";
            echo "</tbody>";
            echo "</table>";
            echo "</form>";

            echo "</td>";
            echo "</tr>";
        }
        else{

            echo "<tr><td colspan='3'>&nbsp;</td></tr>";
            echo "<tr><td colspan='3' class='text-center text-bold text-red'> AKTIFKAN PENGATURAN TERMIN, UNTUK MENGAKTIFKAN NOTIFIKASI PEMBUATAN TAGIHAN OTOMATIS </td></tr>";
            echo "<tr>
                    <td>
                        <div id='setting_termin'>
                            <div class='alert'><label class='fa-2x'>Centang Untuk Mengaktifkan Settingan Termin <input id='checkSettingTermin' type='checkbox'></label></div>
                        </div>
                    </td>
                  </tr>
            ";
        }

        //region clear shoping cart
        if ((!$avoidRemove) || (!$avoidRemoveAll_items)) {
            $addColspan = (isset($checkOpname) && ($checkOpname == true)) ? 3 : 2;
            echo "<tr class='bg-grey-2'>";
            echo "<td colspan='" . (sizeof($itemLabels) + $addColspan) . "'>";

            echo "<span class='pull-left'>";
            echo "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
            echo "</span>";

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        echo "</tbody>";
        echo "</table class='table'>";
        echo "</div class='table-responsive'>";

        // -------------------
        echo "<script>
            $(document).ready(function () {
                function clamp(val, min, max) {
                    return Math.max(min, Math.min(max, val));
                }
                
                function highlight(input) {
                    input.addClass('bg-highlight');
                    setTimeout(() => input.removeClass('bg-highlight'), 800);
                }
            
                function showError(input, message) {
                    clearError(input);
                    input.addClass('is-invalid');
                    input.after('<div class=\"input-error text-danger small mt-1\">' + message + '</div>');
                }
            
                function clearError(input) {
                    input.removeClass('is-invalid');
                    input.next('.input-error').remove();
                }                   
            
                function bindPair(inputPersenId, inputTotalId) {
                    let inputPersen = $('#' + inputPersenId);
                    let inputTotal = $('#' + inputTotalId);
                    let totalProject = parseFloat(inputPersen.attr('total_project'));
            
                    // console.log('total_total', total_total);
                    inputPersen.on('input', function () {
                        let persen = parseFloat($(this).val());
                        if (isNaN(persen)) persen = 0;
                        
                        if (persen > 100) {
                            showError(inputPersen, 'Melebihi batas maksimum 100%');
                            showError(inputTotal, '');
                        } else {
                            clearError(inputPersen);
                            clearError(inputTotal);
                        }
                        
                        persen = clamp(persen, 0, 100);
                        $(this).val(persen); // koreksi jika lebih
                        highlight(inputPersen);
            
                        let total = (persen / 100) * totalProject;
                        inputTotal.val(addCommas(Math.round(total)));
                        highlight(inputTotal);
                        
                        if (inputPersenId === 'input_garansi') checkGaransiTanggalRequired();
                    });
            
                    inputTotal.on('input', function () {
                        let raw = removeCommas($(this).val());
                        let total = parseFloat(raw);
                        if (isNaN(total)) total = 0;
            
                        if (total > totalProject) {
                            showError(inputTotal, 'Nilai melebihi total proyek');
                            showError(inputPersen, '');
                        } else {
                            clearError(inputTotal);
                            clearError(inputPersen);
                        }
            
                        $(this).val(addCommas(total));
                        highlight(inputTotal);
            
                        let persen = (total / totalProject) * 100;
                        inputPersen.val(persen.toFixed(2));
                        highlight(inputPersen);
                        
                        if (inputTotalId === 'input_total_garansi') checkGaransiTanggalRequired();
                    });
                }
                                            
                // Bind input pairs
                bindPair('input_dp', 'input_total_dp');
                bindPair('input_garansi', 'input_total_garansi');
                bindPair('new_termin_persen', 'new_termin_total');
                
                $('#input_tgl_akhir_garansi').on('input change', function () {
                    checkGaransiTanggalRequired();
                });

                $('#input_ref_kontrak, #input_tgl_kontrak, #input_tgl_mulai_project, #input_tgl_akhir_project').on('input change', function () {
                    checkKontrakTanggalRequired();
                });
                function checkGaransiTanggalRequired() {
                    let garansiPersen = parseFloat($('#input_garansi').val()) || 0;
                    let garansiTotal = parseFloat(removeCommas($('#input_total_garansi').val())) || 0;
                    let tglAkhir = $('#input_tgl_akhir_garansi').val();
                
                    if ((garansiPersen > 0 || garansiTotal > 0) && tglAkhir === '') {
                        disableSpanWithOnclick('btn_table_garansi_form');
                        $('#input_tgl_akhir_garansi').addClass('is-invalid');
                    } else {
                        enableSpanWithOnclick('btn_table_garansi_form');
                        $('#input_tgl_akhir_garansi').removeClass('is-invalid');
                    }
                }
                
                function checkKontrakTanggalRequired() {
                    let refKontrak = $('#input_ref_kontrak').val().trim();
                    let tglKontrak = $('#input_tgl_kontrak').val();
                    let tglMulai = $('#input_tgl_mulai_project').val();
                    let tglAkhir = $('#input_tgl_akhir_project').val();
                
                    let allValid = true;
                
                    if (refKontrak !== '') {
                        if (tglKontrak === '') {
                            $('#input_tgl_kontrak').addClass('is-invalid');
                            allValid = false;
                        } else {
                            $('#input_tgl_kontrak').removeClass('is-invalid');
                        }
                
                        if (tglMulai === '') {
                            $('#input_tgl_mulai_project').addClass('is-invalid');
                            allValid = false;
                        } else {
                            $('#input_tgl_mulai_project').removeClass('is-invalid');
                        }
                
                        if (tglAkhir === '') {
                            $('#input_tgl_akhir_project').addClass('is-invalid');
                            allValid = false;
                        } else {
                            $('#input_tgl_akhir_project').removeClass('is-invalid');
                        }
                
                        if (!allValid) {
                            disableSpanWithOnclick('btn_table_ref_form');
                        } else {
                            enableSpanWithOnclick('btn_table_ref_form');
                        }
                    } else {
                        // Kalau ref kosong, hapus error
                        $('#input_tgl_kontrak, #input_tgl_mulai_project, #input_tgl_akhir_project').removeClass('is-invalid');
                        enableSpanWithOnclick('btn_table_ref_form');
                        // disableSpanWithOnclick('btn_table_ref_form');
                    }
                }

                $('#btnGunakanSisa').on('click', function () {
                    // Ambil nilai dari data attribute
                    let sisaRupiah = $('#sisa_rupiah').data('sisa_rupiah');
                    if (!sisaRupiah || isNaN(sisaRupiah)) return;
                
                    // Format nilai dengan koma dan masukkan ke input
                    $('#new_termin_total').val(addCommas(sisaRupiah)).trigger('input');
                    // $('#new_termin_total').val(addCommas(Math.round(sisaRupiah))).trigger('input');
                    // $('#new_termin_total').val(addCommas(Math.floor(sisaRupiah))).trigger('input');
                
                    // Jika fungsi bindPair sudah digunakan, .trigger('input') akan otomatis memicu hitung persen
                    highlight($('#new_termin_total'));
                  });
            });
            
            
            </script>";
        /** ---------------------------------------------------------------------------------
         * fungsi disabe enabel form ada di template transaksi.html
         * ---------------------------------------------------------------------------------*/

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
        //--------

        if (sizeof($elements) > 0) {
            echo "<div class='panel-body table-responsive'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<h4 class='text-blue text-left'>Please fill in details below</h4>";
            echo "</div class='col-md-12'>";
            echo "</div class='row'>";
            echo "<div class='col-lg-12 no-padding text-center' style='text-align:center;'>";
            $elCtr = 0;
            foreach ($elements as $eName => $pSpec) {
                $elCtr++;
                if (isset($pSpec['type']) && ($pSpec['type'] == "hidden")) {
                    // type hidden tidak perlu tampil di ui //
                }
                else {
                    //region penampil untuk elemen pada shopingcart
                    if ($elCtr % 2 == 0) {
                    }
                    else {
                        echo "<div class='col-lg-12 no-padding'>";
                        echo "<div class='row row-eq-height'>";
                    }
                    echo "<div class='col-md-6 col-lg-6' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";

                    echo "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";

                    echo $pSpec['label'] . " ";
                    if (isset($elementConfigs[$eName]['autoSelect']) && $elementConfigs[$eName]['autoSelect']) {

                    }
                    else {
                        echo "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
                    }
                    //----------------------------------------
                    if (isset($elementConfigMutasi[$eName])) {
                        echo "&nbsp;&nbsp;&nbsp;<a href='" . $elementConfigMutasi[$eName] . "' target='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                    }
                    //----------------------------------------
                    echo "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";

                    echo "</div class='box-title'>";

                    if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
                        echo "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
                    }


                    echo "<div class=''>&nbsp;</div>";
                    echo $pSpec['string'];

                    echo "</div>";
                    if ($elCtr % 2 == 0) {
                        echo "</div>";
                        echo "</div>";
                    }
                    //endregion
                }
            }

            echo "</div class='row'>";

            if (isset($showScheme) && sizeof($showScheme) > 0) {

                echo "<div class='clearfix'><hr></div>";
                echo "<div class='col-md-12 no-padding'>";
                echo "<div class='text-center text-danger text-bold'>-- SKEMA PINJAMAN ANDA --</div>";
                echo "<div class='text-center text-danger text-bold meta'>generator skema hanya berlaku untuk single kreditur</div>";
                echo "<div class='text-center text-danger text-bold'> ========================================== </div>";

                //header skema
                echo "<div class='col-md-12 no-padding'>";

                echo "<span class='col-md-2 text-left text-bold no-padding'>Nama Pemegang Saham </span>
                <span class='text-left col-md-9 no-padding text-capitalize'>: " . $headerScheme['nama'] . "</span>";

//                $headerScheme = array(
//                    "nama" => "$nmPemengangSaham",
//                    "jml_pinjaman" => "$nilai_pinjaman",
//                    "bunga_tahunan" => "$rate_bunga",
//                    "awal_meminjam" => "$awal_pinjaman",
//                    "pelunasan_pinjaman" => "$jatuh_tempo",
//                    "lama_pinjaman" => "$total_hari hari ($total_bulan bln)",
//                );

                echo "<span class='col-md-2 text-left text-bold no-padding'>Jumlah Pinjaman </span>      <span class='text-left col-md-9 no-padding'>: " . number_format($headerScheme['jml_pinjaman']) . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Bunga Tahunan </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['bunga_tahunan'] . "%</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Awal Meminjam </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['awal_meminjam'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Pelunasan Pinjaman </span>   <span class='text-left col-md-9 no-padding'>: " . $headerScheme['pelunasan_pinjaman'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Lama Pinjaman </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['lama_pinjaman'] . "</span>";

                echo "</div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div><table id='main_table' class='table datatable table-bordered table-hover table-striped'><thead>";
                echo "<tr>  <th width='1%'>No</th>
                            <th>Periode</th>
                            <th>jml hari / periode</th>
                            <th>Pokok Pinjaman</th>
                            <th>Rate Bunga</th>
                            <th>Nilai Bunga</th>
                            <th>PPh23</th>
                            <th>bunga setelah dipotong PPh</th>
                      </tr>";

                echo "</thead><tbody>";

                $total_bunga = 0;
                $total_pph23 = 0;
                $total_bunga_pph23 = 0;
                $total_hari = 0;
                $no = 1;

                foreach ($showScheme as $thnbln => $pinjaman) {

                    $setBackground = isset($pinjaman['silangan']) ? $pinjaman['silangan'] : "merah";
                    $bgColor = " ";

                    switch ($setBackground) {
                        default:
                        case "merah":
                            $bgColor = "bg-white";
                            break;
                        case "hijau":
                            $bgColor = "bg-success";
                            break;
                        case "berjalan":
                            $bgColor = "bg-warning";
                            break;
                    }

                    echo "  <tr>
                                <td class='$bgColor'>$no</td>
                                <td class='$bgColor'>" . date('F Y', strtotime($pinjaman['thnbln'] . '-01')) . "</td>
                                <td class='$bgColor'>" . $pinjaman['jml_hari_dbln'] . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pinjaman'], 0) . "</td>
                                <td class='$bgColor'>" . $pinjaman['rate_bunga'] . "%</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_bunga'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pph23'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nett_bunga'], 0) . "</td>
                            </tr>";

                    $no++;

                    $total_bunga += $pinjaman['nilai_bunga'] * 1;
                    $total_pph23 += $pinjaman['nilai_pph23'] * 1;
                    $total_bunga_pph23 += $pinjaman['nett_bunga'] * 1;
                    $total_hari += $pinjaman['jml_hari_dbln'] * 1;
                }

                echo "<tfoot>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>" . $total_hari . "</td>
                            <td>-</td>
                            <td>-</td>
                            <td>" . number_format($total_bunga, 0) . "</td>
                            <td>" . number_format($total_pph23, 0) . "</td>
                            <td>" . number_format($total_bunga_pph23, 0) . "</td>
                        </tr>
                    </tfoot>";

                echo "</tbody>
                        </table>
                        </div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div class='text-left'>Keterangan:</div>";
                echo "<div class='text-left'> - periode dengan background hijau akan otomatis dibuatkan <span class='text-capitalize text-bold'>request loan interest</span> sesaat setelah request pinjaman diapprove </div>";
                echo "</div>";
            }

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

//            echo "<div class='panel panel-info col-md-12'>";

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
//            echo "</div>";
        }

        $finishing_dropdown = "";
        if(isset($r) && $r == 1){
            $finishing_dropdown .= "console.log('remove dropdown/reset');";
        }

        echo "<script>

                $finishing_dropdown\n

                if( $('span[keyid=qty_debet]').length > 0 ){
                    top.shoppingCardValidator()
                    //top.console.log('perlu validator shoppingcart');
                }
                else{
                    //top.console.error('tidak perlu validator shoppingcart');
                }

                $('.btn_remove_ref').click(function(){
                    var jenis = $(this).attr('jenis');
                    var key = $(this).attr('key');

                    swal({
                        title: 'KONFIRMASI',
                        html: 'Akan menghapus Info Kontrak, Lanjutkan?',
                        type: 'warning',
                        showCancelButton: true,
                    })
                    .then(function(a){
                        if(a){
                            $.ajax('".base_url()."/master_project/_shoppingCart/removeTermin/588/'+jenis+'/'+key)
                            .always(function(){
                                $('#shopping_cart').load('".base_url()."/master_project/_shoppingCart/viewCart/588')
                            });
                        }
                    });

                });

                $('.btn_remove_dp').click(function(){
                    var jenis = $(this).attr('jenis');
                    var key = $(this).attr('key');

                    swal({
                        title: 'KONFIRMASI',
                        html: 'Akan menghapus Items, Lanjutkan?',
                        type: 'warning',
                        showCancelButton: true,
                    })
                    .then(function(a){
                        if(a){
                            $.ajax('".base_url()."/master_project/_shoppingCart/removeTermin/588/'+jenis+'/'+key)
                            .always(function(){
                                $('#shopping_cart').load('".base_url()."/master_project/_shoppingCart/viewCart/588')
                            });
                        }
                    });

                });

                $('.btn_remove_garansi').click(function(){
                    var jenis = $(this).attr('jenis');
                    var key = $(this).attr('key');

                    swal({
                        title: 'KONFIRMASI',
                        html: 'Akan menghapus Items, Lanjutkan?',
                        type: 'warning',
                        showCancelButton: true,
                    })
                    .then(function(a){
                        if(a){
                            $.ajax('".base_url()."/master_project/_shoppingCart/removeTermin/588/'+jenis+'/'+key)
                            .always(function(){
                                $('#shopping_cart').load('".base_url()."/master_project/_shoppingCart/viewCart/588')
                            });
                        }
                    });
                });

                $('.btn_remove_termin').click(function(){
                    var jenis = $(this).attr('jenis');
                    var key = $(this).attr('key');

                    swal({
                        title: 'KONFIRMASI',
                        html: 'Akan menghapus Items, Lanjutkan?',
                        type: 'warning',
                        showCancelButton: true,
                    })
                    .then(function(a){
                        if(a){
                            $.ajax('".base_url()."/master_project/_shoppingCart/removeTermin/588/'+jenis+'/'+key)
                            .always(function(){
                                $('#shopping_cart').load('".base_url()."/master_project/_shoppingCart/viewCart/588')
                            });
                        }
                    });
                });

                $('#checkSettingTermin').click(function (e) {
                    const checkbox = $(this);
                    let check = checkbox.is(':checked') ? 1 : 0;
                
                    // Jika user mencoba uncheck
                    if (check === 0) {
                        e.preventDefault(); // cegah uncheck dulu

                        swal({
                            title: \"Yakin?\",
                            text: \"Membuang centang ini akan mereset semua pengaturan termin!\",
                            buttons: true,
                            type: 'warning',
                            showCancelButton: true,
                        }).then(function (willReset) {
                            if (willReset) {
                                checkbox.prop('checked', false); // lanjutkan uncheck
                                jalankanAjax(0); // panggil AJAX dengan val=0
                            } else {
                                checkbox.prop('checked', true); // kembalikan centang
                            }
                        });
                    } else {
                        // Jika centang (check == 1), langsung kirim AJAX
                        jalankanAjax(1);
                    }

                    // Jangan lanjutkan event default (prevent klik ganda)
                    e.preventDefault();
                });
                
                function jalankanAjax(check) {
                    $.ajax('$linkAddTerminSetting/pengaturan?val='+check)
                        .done(function (response) {
                            let resp;
                            try {
                                resp = JSON.parse(response);
                            } catch (e) {
                                console.warn('Bukan JSON, abaikan response:', response);
                                return;
                            }
                
                            if (check === 1 && resp.data?.nilai == 0) {
                                swal('RAB Belum di tentukan', resp.message, 'warning');
                            } else {
                                console.warn('untic');
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.error('Error:', textStatus, errorThrown);
                        })
                    .always(function(){
                            $('#shopping_cart').load('" . base_url() . "/master_project/_shoppingCart/viewCart/588');
                    });
                }

                </script>
                
                <script>
                // Fungsi untuk menangani perubahan status checkbox
                function handleCheckboxChange(e, checkbox, otherCheckboxId, typeName) {
                    e.preventDefault();
                    var isChecked = checkbox.is(':checked');
                    var otherCheckbox = top.$(otherCheckboxId);
                    
                    // Jika checkbox lain sudah dicentang, tampilkan peringatan
                    if (isChecked && otherCheckbox.is(':checked')) {
                        swal({
                            title: 'Tidak bisa memilih keduanya',
                            text: `Anda tidak bisa memilih `+ typeName +` ketika opsi ` + typeName === 'material' ? 'biaya' : 'material' + ` sudah aktif`,
                            type: 'error',
                            confirmButtonColor: '#3085d6',
                            buttonsStyling: true
                        });
                        checkbox.prop('checked', false);
                        return;
                    }
                
                    swal({
                        title: isChecked ? `Anda akan beralih ke project tanpa `+typeName+`?` 
                                         : `Anda akan beralih ke project menggunakan `+typeName+`?`,
                        text: isChecked ? `Anda yakin ingin mengaktifkan opsi ini?` 
                                        : `Anda yakin ingin menonaktifkan opsi ini?`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Tidak, batalkan',
                        buttonsStyling: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(function() {
                        checkbox.prop('checked', isChecked);
                        swal(
                            'Berhasil!',
                            isChecked ? ``+typeName+` telah dinonaktifkan.` 
                                      : ``+typeName+` telah diaktifkan.`,
                            'success'
                        );
                        var values = isChecked ? 1 : 0;
                        top.$('#result').load('$switchMaterialBiaya'+'/non_'+typeName+'?val='+values);
                    }, function(dismiss) {
                        if (dismiss === 'cancel') {
                            checkbox.prop('checked', !isChecked);
                            swal(
                                'Dibatalkan',
                                `Status `+typeName+` tidak berubah.`,
                                'error'
                            );
                            var values = isChecked ? 1 : 0;
                            //top.$('#result').load('$switchMaterialBiaya'+'/non_'+typeName+'?val='+values);
                        }
                    });
                }
                
                // Event handler untuk Material
                top.$('#prj_non_material').on('change', function(e) {
                    handleCheckboxChange(e, $(this), '#prj_non_biaya', 'material');
                });
                
                // Event handler untuk Biaya
                top.$('#prj_non_biaya').on('change', function(e) {
                    handleCheckboxChange(e, $(this), '#prj_non_material', 'biaya');
                });
                                        
                </script>
                
                
                ";
    }
    else {
        echo "<div class='panel-body'>";
        echo "<div class='text-danger'>";
        echo "- <strong>you have not chosen any item yet</strong> -<br>";
        echo "<small>you can do so by selecting items from available selectors</small><br>";
        echo "</div class='text-warning'>";
        echo "</div class='panel-body'>";
    }

    /* -------------------------------------
     * matiin project projek
     * -------------------------------------*/
//    $img = img_maintenace();
//    $txt = dtimeNow('d F Y H:i');
//    $linkHome = base_url();
//    $txt .= " <br>Kembali ke <a href=\'$linkHome\'>HOME</a>";
//
//    echo "<script>
//            swal({
//                // title:'ssss',
//                html:'$txt',
//                imageUrl: '$img',
//                showConfirmButton: false,
//                allowOutsideClick: false,
//            });
//    </script>";

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
