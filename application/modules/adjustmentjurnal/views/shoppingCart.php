<?php

if (isset($itemsjurnal)) {

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";


    if (sizeof($itemsjurnal) > 0) {
        $preheader = array("2021"=>"GL 2021","adjustment"=>"Adjustment","rev"=>"GL revisi");
        /*===bagian logic tambahan taxes untuk payment src*/
        if(isset($shopingCartAddTax) && sizeof($shopingCartAddTax)>0){
            echo "<div class=''>";
            echo "<div class='text-center text-bold bg-red text-uppercase'> Tipe konsumen </div>";
            foreach ($shopingCartAddTax["fields"] as $sels => $label) {
                $checked = $checkTaxes == $sels?"checked":"";
                    echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_pajak' $checked value='$sels'  onclick=\"$('#result').load('" .$shopingCartAddTaxAction."/?val='+this.value+'&p=$sels');\">
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

                    $jenisTr = isset($arrHeaderElementJenis) ? $arrHeaderElementJenis :"";
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
        echo "<table id='tbl_head' class='table grid text-muted compact'>";
        /*===============header shoping cart======================*/

        echo "<thead>";
        // arrPrint($header2);
        if (isset($headerMaster)) {
            if (sizeof($headerMaster) && (is_array($headerMaster)) && $showItems) {
                echo "<tr class='bg-grey-2 text-uppercase'>";

                echo "<th style='width:1%;' class='text-muted text-center' rowspanx='2'>";
                echo "--";
                echo "</th>";

                echo "<th style='width:1%;' class='text-muted text-center' rowspanx='2'>";
                echo "NO";
                echo "</th>";
                // echo "<th style='width:1%;' class='text-muted text-center' rowspan='2'>";
                // echo "Kode Rekening";
                // echo "</th>";
                // echo "<th style='width:1%;' class='text-muted text-center' rowspan='2'>";
                // echo "Rekening";
                // echo "</th>";
                $colspan = sizeof($header2)+1;
                foreach($headerMaster as $kol_master =>$alias){
                    $rowspan = isset($header2[$kol_master]) ? 1:2;
                    $colspan = isset($header2[$kol_master]) ? 2:1;

//                    echo "<th colspan='$colspan' rowspan='$rowspan' class='text-center'>$alias</th>";
                    echo "<th colspan='$colspan' rowspans='$rowspan' class='text-center'>$alias</th>";
                }
                echo "</tr>";
                echo "<tr class='bg-grey-2 text-uppercase'>";

                echo "<th colspan='' class='text-center'>--</th>";
                echo "<th colspan='' class='text-center'>--</th>";
                echo "<th colspan='' class='text-center'>--</th>";
                echo "<th colspan='' class='text-center'>--</th>";
                foreach($headerMaster as $kol_master =>$alias_labelmaster){
                    // arrPrint($alias);
                    if(isset($header2[$kol_master])){
                        foreach($header2[$kol_master] as $kol =>$kol_alias){
                            echo "<th colspan='' class='text-center'>$kol_alias</th>";
                        }
                    }

                    // echo "<th colspan='' class='text-center'>KREDIT</th>";
                }
                echo "</tr class='bg-grey-2 text-uppercase'>";
                // foreach ($itemLabels as $key => $label) {
                //     echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                //     echo $label;
                //     echo "</th>";
                // }
                // if (!$avoidRemove) {
                //     echo "<th style='width:1%;' class='text-muted text-center'>";
                //     echo "x";
                //     echo "</th>";
                // }
                // echo "</tr>";
            }
        }

        echo "</thead>";
        //ini untuk data view pembantu

        echo "<caption>
            <div class='rows'>
                <div class='col-md-3 no-padding'>
                    <div class='input-group'>
                      <span class='input-group-btn input-group-justified'>
                            <a class='btn btn-info' href='javascript:void(0)' onclick='collapSemua()'> Collapse All <i class='fa fa-compress'></i></a>
                            <a class='btn btn-info' href='javascript:void(0)' onclick='expandSemua()'> Expand All <i class='fa fa-expand'></i></a>
                      </span>
                   </div>
                </div>
                <div class='col-md-3 no-padding'>&nbsp;
                </div>
                <div class='col-md-3 no-padding'>&nbsp;
                </div>
                <div class='col-md-3 no-padding'>
                    <form id='searchForm' action='javascript:searchNamaRekening();'>
                       <div class='input-group'>
                          <input type='text' id='searchItem' class='form-control' placeholder='Cari Nama/Nomor'>
                          <span class='input-group-btn'>
                                <button id='go' class='btn btn-info' type='button' onclick='document.getElementById(\"searchForm\").submit(); return false;'>Cari</button>
                          </span>
                       </div>
                    </form>
                </div>
            </div>
        </caption>";

        // arrprint($pembantuItemData);

        if(sizeof($itemsjurnal)>0){
//            arrPrint($rekeningBlacklist);

            echo "<tbody>";
            foreach ($headerMaster as $master_h =>$masterLabel){
                // if(isset())
            }
            $sumfields = array();
            $i = 0;
            foreach($itemsjurnal as $rekID =>$label){
                $i++;

                /*
                 * MAIN REKENING
                 */

                $position = detectRekDefaultPosition($rekID);

                echo "<tr rek_position='$position' trJenis='rekMain' trHook='".$i.'_'.$rekID."' id='rekID_$rekID' style='cursor: pointer;' class='group bg-gray text-uppercase text-left' titlexx='klick to collapsible'>";
                echo "<td column='consulente:'>$rekID</td>";
                echo "<td column='$rekID:'>$i</td>";
                echo "<td class='kode_rekening'><span>$rekID</span></td>";

                $notifyQuestion = isset($rekeningBlacklist[$rekID]) ? "<i title='rekening ini harus melalui transaksi' class='fa fa-question-circle'></i>" : "";
                $harusDisabled = isset($rekeningBlacklist[$rekID]) ? 1 : 0;
                $txtSingkatPosisiRek = $position=="kredit" ? "<b>(KR) </b>" : "<b>(DE) </b>";
                echo "<td class='nama_rekening'> $txtSingkatPosisiRek <span>$label $notifyQuestion</span></td>";
                foreach ($headerMaster as $master_h =>$masterLabel){
                    if(isset($header2[$master_h])){
                        foreach ($header2[$master_h] as $kolomSrc =>$kolAlias){
                            if(isset($itemsJurnal2[$rekID][$master_h][$kolomSrc])){
                                if(isset($jurnalEditableFields[$master_h])){
                                    foreach($jurnalEditableFields[$master_h] as $editable){
                                        if($kolomSrc == $editable){
                                            $key = $kolomSrc;
                                            $colID[$key] = $kolomSrc . "_" . $i;
                                            $val = $itemsJurnal2[$rekID][$master_h][$kolomSrc];
                                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $i . "')" : "this";
                                            $queryParams = "?key=$key&rid=$rekID&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)";
//                                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $itemsJurnal2[$rekID][$master_h]["editTarget"] . "/$queryParams;}\"  ";
                                            $pemicuGerbang = "onchange=\"hiliteDiv($baseInputName);document.getElementById('result').src='" . $itemsJurnal2[$rekID][$master_h]["editTarget"] . "/$queryParams;\"  ";

                                            //disini dipasang logic untuk triger gerbangnya
                                            $mainRekDisable = isset($pembantuItems[$rekID]) && sizeof($pembantuItems[$rekID])>0 ? "disabled" : (isset($rekeningBlacklist[$rekID])?"disabled":"");
                                            $mainRekCssBack = isset($pembantuItems[$rekID]) && sizeof($pembantuItems[$rekID])>0 ? "background-color: lightgray;" : (isset($rekeningBlacklist[$rekID])?"background-color: lightgray;":"");
                                            $mainRekOwnChild = isset($pembantuItems[$rekID]) && sizeof($pembantuItems[$rekID])>0 ? 1 : 0;

                                            echo "<td><input force_disable='$harusDisabled' ownchill='$mainRekOwnChild' style='$mainRekCssBack' $mainRekDisable mrid='$rekID' type='text' id='".$colID[$key]."' value='".niceDecimal($val)."'  class='text-right mrid' autocomplete='off' onclick='this.select()' $pemicuGerbang></td>";
                                        }
                                    }
                                    // matiHere(($jurnalEditableFields[$master_h][$kolomSrc]);
                                }
                                else{
                                    $valNonEditable = isset($itemsJurnal2[$rekID][$master_h][$kolomSrc.'_ori']) ? removeCommas(number_format($itemsJurnal2[$rekID][$master_h][$kolomSrc.'_ori'],0)) : removeCommas(number_format($itemsJurnal2[$rekID][$master_h][$kolomSrc],0));
                                    echo "<td class='revMrid' master_h='$master_h' kolomSrc='".$kolomSrc."_".$i."' class='text-bold' defvalue='".$valNonEditable."'>".formatField_he_format("harga",$itemsJurnal2[$rekID][$master_h][$kolomSrc])."</td>";
                                }
                                if(!isset($sumfields["sumFooter"][$master_h][$kolomSrc])){
                                    $sumfields["sumFooter"][$master_h][$kolomSrc] = 0;
                                }
                                $sumfields["sumFooter"][$master_h][$kolomSrc] +=$itemsJurnal2[$rekID][$master_h][$kolomSrc];

                            }
                        }
                    }
                }
                echo "</tr class='bg-grey-2 text-uppercase'>";

                /*
                 * REKENING PEMBANTU
                 */

                //region tambahan
                if(isset($pembantuItems[$rekID]) && sizeof($pembantuItems[$rekID])>0){
                    $pembantuNumb = 1;
                    $i_=0;
                    foreach($pembantuItems[$rekID] as $exterID =>$pembantuItems_0){
                        $i_++;
                        echo "<tr rek_position='$position' trJenis='rekPembantu' myrekmain='rekID_$rekID' trPemHook='".$i.'_'.$rekID.'_'.$exterID."' id='".$i_."_rekIDPem_$rekID' style='background-color:#f6c7ff' class='text-uppercase text-left'>";
                        echo "<td column='consulente:'>$rekID</td>";
                        echo "<td column='$rekID:' class='text-right'>$pembantuNumb</td>";
                        echo "<td class='text-right'>-</td>";
                        echo "<td class='nama_rekening'><span>".$pembantuItems_0["extern_nama"]."</span></td>";

                        foreach ($headerMaster as $master_h =>$masterLabel){
                            if(isset($header2[$master_h])){
                                foreach ($header2[$master_h] as $kolomSrc =>$kolAlias){
                                    // echo "<td>^^$kolomSrc</td>";
                                    if(isset($pembantuItemData[$rekID][$master_h][$exterID][$kolomSrc])){
                                        if(isset($jurnalEditableFields[$master_h])){
                                            foreach($jurnalEditableFields[$master_h] as $editable){
                                                if($kolomSrc == $editable){
                                                    $key = $kolomSrc;
                                                    // $colID[$key] = $kolomSrc . "_" . $i_;
                                                    $colID[$key] = $kolomSrc . "_" . $rekID."_".$exterID."_".$i_;
                                                    $val = $pembantuItemData[$rekID][$master_h][$exterID][$kolomSrc];
                                                    $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $i_ . "')" : "this";
                                                    $queryParams = "?key=$key&rid=$rekID&masterkey=$master_h&crid=$exterID&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)";

//                                                    $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $pembantuItemData[$rekID][$master_h][$exterID]["editTarget"] . "/$queryParams;}\"  ";
                                                    $pemicuGerbang = "onchange=\"hiliteDiv($baseInputName);document.getElementById('result').src='" . $pembantuItemData[$rekID][$master_h][$exterID]["editTarget"] . "/$queryParams;\"  ";
                                                    //disini dipasang logic untuk triger gerbangnya
                                                    // echo "<td ><input type='text' hook='".$kolomSrc . "_" .$i_.$exterID."' crid='$rekID' id='".$colID[$key]."' value='".niceDecimal($val)."'  class='text-right crid' autocomplete='off' onclick='this.select()' $pemicuGerbang></td>";
                                                    echo "<td ><input type='text' kolomSrc='".$colID[$key]."' crid='$rekID' id='".$colID[$key]."' value='".niceDecimal($val)."'  class='text-right crid' autocomplete='off' onclick='this.select()' $pemicuGerbang></td>";
                                                }
                                            }
                                            // matiHere(($jurnalEditableFields[$master_h][$kolomSrc]);
                                        }
                                        else{
                                            $valNonEditablePem = isset($pembantuItemData[$rekID][$master_h][$exterID][$kolomSrc.'_ori']) ? removeCommas(number_format($pembantuItemData[$rekID][$master_h][$exterID][$kolomSrc.'_ori'],0)) : removeCommas(number_format($pembantuItemData[$rekID][$master_h][$exterID][$kolomSrc],0));
                                            echo "<td class='revCrid' kolomSrc='".$kolomSrc . "_" . $rekID."_".$exterID."_".$i_."' exterID='$exterID' master_h='$master_h' crid='$rekID' id='' current defvalue='".$valNonEditablePem."' >".formatField_he_format("harga",$pembantuItemData[$rekID][$master_h][$exterID][$kolomSrc])."</td>";
                                        }
                                    }
                                    else{
                                        echo "<td class='revCrid' kolomSrc='".$kolomSrc . "_" . $rekID."_".$exterID."_".$i_."' exterID='$exterID' master_h='$master_h' crid='$rekID' id='' prev defvalue='0' >".formatField_he_format("harga",0)."</td>";
                                    }
                                }
                            }
                        }
                        echo "</tr>";
                        $pembantuNumb++;
                    }
                }
                //endregion
                $i_++;
            }


//            arrPrint($sumfields);

            echo "</tbody>";
            echo "<tfoot>";
            //region footer
            echo "<tr class='bg-grey-2 text-uppercase'>";
            echo "<th>-</th>";
            echo "<th>-</th>";
            echo "<th>-</th>";
            echo "<th>-</th>";
            foreach($headerMaster as $master_key =>$masterAlias){
                if(isset($header2[$master_key])){
                    foreach($header2[$master_key] as $k =>$y){
                        if(isset($sumfields["sumFooter"][$master_key][$k])){
                            echo "<th class='text-bold text-right sum_$k' mk='$master_key' kk='$k'>".number_format($sumfields["sumFooter"][$master_key][$k])."</th>";
                        }
                    }
                }
            }
            echo "</tr>";

            echo "<tr class='bg-grey-2 text-uppercase'>";
            echo "<th>-</th>";
            echo "<th>-</th>";
            echo "<th>-</th>";
            echo "<th>-</th>";
            echo "<th class='text-center selisih_prev' colspan='2'>-</th>";
            echo "<th class='text-center selisih_adj' colspan='2'>selisih</th>";
            echo "<th class='text-center selisih_curent' colspan='2'>selisih</th>";
            echo "</tr>";

            //endregion
            echo "</tfoot>";
        }



// arrPrint($sumfields);
        // if (isset($itemLabels)) {
        //     if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
        //         echo "<tr class='bg-grey-2 text-uppercase'>";
        //         echo "<th style='width:1%;' class='text-muted text-center'>";
        //         echo "NO";
        //         echo "</th>";
        //         foreach ($itemLabels as $key => $label) {
        //             echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
        //             echo $label;
        //             echo "</th>";
        //         }
        //
        //         //----------
        //         if (isset($checkOpname) && ($checkOpname == true)) {
        //             echo "<th style='width:1%;' class='text-muted text-center'>";
        //             echo "V";
        //             echo "</th>";
        //         }
        //         //----------
        //         if (!$avoidRemove) {
        //             echo "<th style='width:1%;' class='text-muted text-center'>";
        //             echo "x";
        //             echo "</th>";
        //         }
        //         echo "</tr>";
        //     }
        // }

        /*===============body shoping cart=======================================*/
        // arrPrint($selItems);
        $no = 0;
        // $showItems = false;
        
//         foreach ($items as $iSpec) {
//             if ($showItems) {
//
//                 $iID = $iSpec['id'];
//                 $no++;
//                 $bgColor = "transparent";
//                 if (isset($_SESSION['errLines'])) {
//                     if (in_array($iSpec['id'], $_SESSION["errLines"])) {
//                         $bgColor = "#ffff77";
//                     }
//                 }
//
//                 echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
//                 echo "<td style='vertical-align:middle; width:1%' class='text-center'>";
//                 echo $no;
//                 echo "</td>";
//                 $colCtr = 0;
//                 $queryParams = "";
//                 $colID = array();
//                 $listMode = array();
//                 $readOnly = array();
//                 $qtyParam = "";
//                 if (isset($itemLabels['jml'])) {
//                     $qtyParam = "+removeCommas(document.getElementById('jml_$no').value)";
//                 }
//                 foreach ($itemLabels as $key => $label) {
//                     $listMode[$key] = "input";
//                     $keyupEvent[$key] = "";
//                     $keyUpStr[$key] = "";
//                     if (array_key_exists($key, $keyUpEvents)) {
//                         //                    cekbiru("$key has events");
//                         if (sizeof($selectedPrices) > 0) {
//                             $keyupEvent[$key] = $keyUpEvents[$key];
//                             foreach ($selectedPrices as $k => $v) {
//                                 //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
//                                 $nameLabel = $k . "_" . $no;
//                                 $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
//                             }
//                             foreach ($itemLabels as $k => $v) {
//                                 $nameLabel = $k . "_" . $no;
//                                 $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
//                             }
//                         }
//                         if (isset($keyupAction) && $keyupAction == true) {
//                             $keyupEvent[$key] = $keyUpEvents[$key];
//                             foreach ($selectedPrices as $k => $v) {
//                                 //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
//                                 $nameLabel = $k . "_" . $no;
//                                 $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
//                             }
//                             foreach ($itemLabels as $k => $v) {
//                                 $nameLabel = $k . "_" . $no;
//                                 $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
//                             }
//                         }
//                     }
//                     else {
//                     }
//                     if (strlen($keyupEvent[$key]) > 2) {
//                         $keyUpStr[$key] = " onkeyup=\"" . $keyupEvent[$key] . "\" ";
//                     }
//                     if (in_array($key, $editableFields)) {
//                         $readOnly[$key] = "";
//                         if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
//                             $readOnly[$key] = "readonly_xz";
//                         }
//                         if (isset($paramsForceEditable[$key])) {
//                             if ($paramsForceEditable[$key] == true) {
//
//                             }
//                             else {
//                                 $readOnly[$key] = "readonly_xxz";
//                                 $listMode[$key] = "text";
//                             }
//                         }
//                     }
//                     else {
//                         $readOnly[$key] = "readonly_xxz";
//                         $listMode[$key] = "text";
//                     }
//                     $colID[$key] = $key . "_" . $no;
//                     if ($listMode[$key] == "input") {
//                         $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
//                     }
//                 }
//                 foreach ($itemLabels as $key => $label) {
//                     $colCtr++;
//                     $color = "343434";
//                     if (isset($_SESSION['errFields'][$iSpec['id']])) {
//                         if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
//                             $color = "#dd3300";
//                         }
//                     }
//                     echo "<td align='left'>";
//                     $colID = $key . "_" . $no;
//                     $keyID = $key;
//                     $noID = $no;
//                     $tabIndexNum = $colCtr . $no;
//                     $fieldVal = "";
//                     if (substr($key, 0, 1) == "*") {
//                         $key_p = str_replace("*", "", $key);
//                         $key_ex = explode("#", $key_p);
//                         $pair_name = $key_ex[0];
//                         $pair_key = $key_ex[1];
//                         $pair_key_val = $iSpec[$pair_key];
//                         if (sizeof($key_ex) > 1) {
//                             $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
//                         }
//                         else {
//                             $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
//                         }
//                     }
//                     else {
//                         if (isset($iSpec[$key])) {
//                             if (is_numeric($iSpec[$key])) {
//                                 $fieldVal = $iSpec[$key] + 0;
//                             }
//                             else {
//                                 $fieldVal = $iSpec[$key];
//                             }
//                         }
//                     }
//                     if (sizeof($minValues) > 0) {
//                         $moq = isset($minValues['moq'][$iID]) ? $minValues['moq'][$iID] : 0;
//                         $validateKey_up = true;
//                     }
//                     else {
//                         $moq = 0;
//                         $validateKey_up = false;
//                     }
//                     $keyupData = (($key == "qty" || $key == "jml") && $validateKey_up == true) ? "onkeydown=\"if(parseInt(this.value)<$moq){setTimeout(function(){ this.value='" . $iSpec[$key] . "'}, 1000);} \"" : "";
//
//                     switch ($listMode[$key]) {
//                         case "input":
//                             echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
//                             $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
//                             $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" $keyupData";
//                             $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
//                             $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
//                             $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
//                             $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";
//
//                             if (isset($unionSelectors['base'])) {
//                                 if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
//                                     echo str_replace("this", $baseInputName, $pemicuGerbang);
//                                 }
//                                 else {
//                                     if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
//                                         echo $pemicuGerbangUnion;
//                                     }
//                                     else {//==biasa aja, memicu perubahan gerbang
//                                         echo $pemicuGerbangAsli;
//                                     }
//                                 }
//                             }
//                             else {
//                                 echo $pemicuGerbangAsli;
//                             }
//
//                             if (isset($keyupAction) && $keyupAction == true) {
//                                 echo "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
//                             }
//                             else {
//                                 echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
//                             }
//                             echo ">";
//
//                             break;
//                         case "text":
//                             if (is_numeric($fieldVal)) {
//                                 echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
//                             }
//                             else {
//                                 if (strlen($fieldVal) > 10) {
//                                     echo "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
//                                 }
//                                 else {
//                                     echo "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
//                                 }
//                             }
//                             break;
//                     }
//                     echo "***</td>";
//                 }
//
//                 //-----------------
//                 if (isset($checkOpname) && ($checkOpname == true)) {
//                     if (isset($iSpec['ceklist_opname']) && ($iSpec['ceklist_opname'] == 1)) {
//                         $ceklist_checked = "checked";
//                     }
//                     else {
//                         $ceklist_checked = "";
//                     }
//                     echo "<td width='1%'>";
//                     echo "<input type='checkbox' $ceklist_checked 
//                         onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
//                     echo "</td>";
//                 }
//                 //-----------------
//                 //region remover per row
//                 if (!$avoidRemove) {
//                     echo "<td width='1%'>";
//                     echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
//                     echo "</td>";
//                 }
//                 //endregion
//
//                 echo "</tr>";
//
//                 echo "
//             <script>
//                 \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).html(\"<i class='fa fa-check'></i>\");
//                 \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).addClass(\"text-green text-bold pull-right\");
//             </script>
//             ";
//
//                 if ($noteEnabled == true) {
//                     $colspan2 = $imageEnable == true ? 1 : -1;
//                     $colspan = sizeof($itemLabels) - $colspan2;
//                     echo "<tr>";
//                     echo "<td>&nbsp;</td>";
//                     echo "<td colspan='" . $colspan . "'>";
//                     $noteVal = isset($iSpec['note']) ? $iSpec['note'] : "";
//                     if (isset($noteType)) {
//                         switch ($noteType) {
//                             case "textarea":
//                                 echo "<textarea class='form-control' placeholder='write notes here'
//                                 onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
//                                 onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
//                                 >$noteVal</textarea>";
//                                 break;
//                             case "text":
//                             default:
//                                 echo "<input type=text class='form-control' value='$noteVal' placeholder='write notes here'
//                                 onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
//                                 onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
//                                 >";
//                                 break;
//                         }
//                     }
//
//                     echo "</td>";
//                     if ($imageEnable == true) {
//                         echo "<td colspan='2'>";
//                         $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
//                         if (isset($imageType)) {
//                             switch ($imageType) {
//                                 case "images":
//
//                                     $file_e = "";
//                                     $file = isset($iSpec['images']) ? $iSpec['images'] : "";
//                                     $file_e = urlencode($file);
//                                     echo "<div class='input-groups'>";
//                                     if (strlen($imageVal) > 0) {
//                                         $modals = array(
//                                             "title" => "Attachment " . $iSpec['nama'],
//                                             "body" => array($file),
//                                         );
//                                         $modal_e = urlencode(blobEncode($modals));
//                                         $modal_l = base_url() . "Katalog/modal/$modal_e";
//
//                                         echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
//                                         echo "<input type='hidden' name='img_$iID' value='$file'>";
//                                     }
//
//                                     echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";
//
//                                     echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";
//
//                                     echo "</form>";
//                                     echo "</div>";
//
//                                     break;
//                                 case "text":
//                                 default:
//                                     echo "<input type=text class='form-control' value='$noteVal'
//                                 onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
//                                 onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
//                                 >";
//                                     break;
//                             }
//                         }
//                         echo "</td>";
//                     }
//                     echo "</tr>";
//                 }
//                
// //                 if ($pairedItemEnabled == true) {
// //                     if (sizeof($pairedItemField) > 0) {
// //                         $listModePairedItem = array();
// //                         $readOnlyPairedItem = array();
// //                         foreach ($pairedItemField as $key => $label) {
// //                             $listModePairedItem[$key] = "input";
// //                             if (in_array($key, $editableFields)) {
// //                                 $readOnlyPairedItem[$key] = "";
// //                                 if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
// //                                     $readOnlyPairedItem[$key] = "readonly_x";
// //                                 }
// //                             }
// //                             else {
// //                                 $readOnlyPairedItem[$key] = "readonly_xx";
// //                                 $listModePairedItem[$key] = "text";
// //                             }
// //                         }
// //                     }
// //                     echo "<tr>";
// //                     echo "<td>&nbsp;</td>";
// //                     $c_itemLabels = sizeof($itemLabels);
// //                     $c_pairedItemField = sizeof($pairedItemField);
// //                     $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
// //                     echo "<td colspan='" . $c_colspan . "'>";
// //                     //==pairedItems, if any
// //                     if (isset($selItems[$iID]) && sizeof($selItems[$iID]) > 0) {
// //                         echo "<select
// //                                 title='pilih rekening pembantu...'
// //                                 data-header='Ketik Nama yang akan disesuaikan'
// //                                 data-size='10'
// //                                 data-container='body'
// //                                 class='picker_$iID selectpicker form-control select2 show-tick'
// //                                 data-style='btn-primary'
// //                                 data-live-search='true'
// //                                 classs='form-control'
// //                                 onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?val='+(this.value)+'&iid=$iID'\"
// //                                 >";
// //
// //                         asort($selItems);
// //
// //                         foreach ($selItems[$iID] as $piID => $piName) {
// //                             if ($piID != $iSpec['id']) {
// //                                 $selectedState = (isset($pairedItems[$iID]) && ($piID == $pairedItems[$iID]['id'])) ? "selected" : "";
// //                                 $selItemsKodes = isset($selItemsKode[$piID]) ? $selItemsKode[$piID] : "-";
// //                                 $selItemsFolders = isset($selItemsFolder[$piID]) ? $selItemsFolder[$piID] : "-";
// //                                 $selItemsKeterangans = isset($selItemsKeterangan[$piID]) ? $selItemsKeterangan[$piID] : "-";
// //                                 $selItemsBarcodes = isset($selItemsBarcode[$piID]) ? $selItemsBarcode[$piID] : "-";
// //                                 echo "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
// //                             }
// //                         }
// //
// //                         echo "</select>";
// //
// //                     }
// //                     else{
// //                         cekLime($iID);
// //                     }
// //
// //                     echo "</td>";
// //
// // //                echo "<script>top.$('.select2').selectpicker();</script>";
// // //                echo "<script> setTimeout( function(){ top.$('.picker_$iID').selectpicker(); console.log('dari shopingcart picker_$iID') }, 100 ); </script>";
// //
// //                     echo "<script> $('.picker_$iID').selectpicker(); </script>";
// //
// // //                echo "<script> setTimeout( function(){ top.$('.select2').selectpicker(); console.log('dari shopingcart') }, 500 ); </script>";
// //
// //                     if (sizeof($pairedItemField) > 0) {
// //                         foreach ($pairedItemField as $key => $label) {
// //                             $pairedItems2ID = isset($pairedItems[$iID]['id']) ? $pairedItems[$iID]['id'] : 0;
// //                             $pairedItems2Qty = isset($pairedItems[$iID]['jml']) ? $pairedItems[$iID]['jml'] : 0;
// //                             $fieldVal = isset($pairedItems[$iID][$key]) ? $pairedItems[$iID][$key] : "";
// //                             echo "<td>";
// //                             switch ($listMode[$key]) {
// //                                 case "input":
// //                                     echo "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
// //                                     onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
// //                                     onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
// //                                     >";
// //                                     break;
// //                                 case "text":
// //                                     if (is_numeric($fieldVal)) {
// //                                         echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
// //                                     }
// //                                     else {
// //                                         echo "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
// //                                     }
// //                                     break;
// //                             }
// //                             echo "</td>";
// //                         }
// //                     }
// //                     echo "</tr>";
// //                 }
//             }
//
//
//         }

        //region items2, kalau salah satunya untuk produksi dan konversi
        
        // if (isset($items2) && sizeof($items2) > 0) {
        //     echo "<tr class='bg-info'>";
        //     echo "<td colspan='$jmlKolomHeader'>";
        //
        //     // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
        //     echo "<div class='panel no-margin'>"; // anakan table
        //     echo "<table class='table table-condensed table-striped no-padding no-border'>";
        //
        //     if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
        //         //region header table anakan
        //         echo "<tr>";
        //         echo "<td class='text-muted bg-grey-1 text-center'>";
        //         echo "No";
        //         echo "</td>";
        //         foreach ($itemLabels2 as $key => $label) {
        //             echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
        //             echo $label;
        //             echo "</td>";
        //         }
        //         echo "</tr>";

        //     }
        //endregion
        //     $no = 0;
            //region body table anakan
        //     $kurangStoks = array();
        //     foreach ($items2 as $iSpec) {
        //         $iID = $iSpec['id'];
        //         $no++;
        //         $bgColor = "transparent";
        //         if (isset($items2_sum_kurang) && is_array($items2_sum_kurang)) {
        //             if (isset($items2_sum_kurang[$iID])) {
        //                 $bgColor = "yellow";
        //             }
        //         }
        //         if (isset($_SESSION['errLines'])) {
        //             if (in_array($iSpec['id'], $_SESSION["errLines"])) {
        //                 $bgColor = "#ffff77";
        //             }
        //         }
        //         echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
        //         echo "<td width='5%'>*";
        //         echo $no;
        //         echo ".</td>";
        //         $colCtr = 0;
        //         $queryParams = "";
        //         foreach ($itemLabels2 as $key => $label) {
        //             //                if(in_array($key,$editableFields)){
        //             $colID = $key . "_" . $no;
        //             $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
        //             //                }
        //         }
        //
        //         foreach ($itemLabels2 as $key => $label) {
        //             $colCtr++;
        //             $color = "343434";
        //             if (isset($_SESSION['errFields'][$iSpec['id']])) {
        //                 if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
        //                     $color = "#dd3300";
        //                 }
        //             }
        //             $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
        //             //region membuat array stok yang kurang
        //             if ($key == "sisa") {
        //                 if ($iSpec[$key] < 0) {
        //                     $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
        //                     $cAlign .= " text-red text-bold";
        //                 }
        //                 else {
        //                     $cAlign .= "";
        //                 }
        //             }
        //             //endregion
        //             echo "<td class='$cAlign'>";
        //             $tabIndexNum = $colCtr . $no;
        //
        //             if (is_numeric($iSpec[$key])) {
        //                 // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
        //                 echo formatField($key, $iSpec[$key]);
        //                 // echo $iSpec[$key];
        //             }
        //             else {
        //                 // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
        //                 echo $iSpec[$key];
        //             }
        //             echo "</td>";
        //         }
        //         echo "</tr>";
        //     }
        //     //endregion
        //
        //     echo "</table>";
        //     echo "</div>"; // anakan table
        //
        //     // arrPrint($kurangStoks);
        //
        //     echo "</td>";
        //     echo "</tr>";
        // }
        
        //endregion

        //region items3

        // if (isset($items3) && sizeof($items3) > 0) {
        //     echo "<tr class='bg-info'>";
        //     echo "<td colspan='$jmlKolomHeader'>";
        //
        //     // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
        //     echo "<div class='panel no-margin'>"; // anakan table
        //     echo "<table class='table table-condensed table-striped no-padding no-border'>";
        //
        //     if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
        //         //region header table anakan
        //         echo "<tr>";
        //         echo "<td class='text-muted bg-grey-1 text-center'>";
        //         echo "No";
        //         echo "</td>";
        //         foreach ($itemLabels3 as $key => $label) {
        //             echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
        //             echo $label;
        //             echo "</td>";
        //         }
        //         echo "</tr>";
        //         //endregion
        //     }


        //     $no = 0;
        //     //region body table anakan
        //     $kurangStoks = array();
        //     foreach ($items3 as $iSpec) {
        //         $iID = $iSpec['id'];
        //         $no++;
        //         $bgColor = "transparent";
        //         if (isset($_SESSION['errLines'])) {
        //             if (in_array($iSpec['id'], $_SESSION["errLines"])) {
        //                 $bgColor = "#ffff77";
        //             }
        //         }
        //         echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
        //         echo "<td width='5%'>";
        //         echo $no;
        //         echo ".</td>";
        //         $colCtr = 0;
        //         $queryParams = "";
        //         foreach ($itemLabels3 as $key => $label) {
        //             //                if(in_array($key,$editableFields)){
        //             $colID = $key . "_" . $no;
        //             $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
        //             //                }
        //         }
        //
        //         foreach ($itemLabels3 as $key => $label) {
        //             $colCtr++;
        //             $color = "343434";
        //             if (isset($_SESSION['errFields'][$iSpec['id']])) {
        //                 if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
        //                     $color = "#dd3300";
        //                 }
        //             }
        //             $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
        //             //region membuat array stok yang kurang
        //             if ($key == "sisa") {
        //                 if ($iSpec[$key] < 0) {
        //                     $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
        //                     $cAlign .= " text-red text-bold";
        //                 }
        //                 else {
        //                     $cAlign .= "";
        //                 }
        //             }
        //             //endregion
        //             echo "<td class='$cAlign'>";
        //             $tabIndexNum = $colCtr . $no;
        //
        //             if (is_numeric($iSpec[$key])) {
        //                 // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
        //                 echo $iSpec[$key];
        //             }
        //             else {
        //                 // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
        //                 echo $iSpec[$key];
        //             }
        //             echo "</td>";
        //         }
        //         echo "</tr>";
        //     }
            //endregion
        //
        //
        //     if (isset($sumRows3) && sizeof($sumRows3) > 0) {
        //         $nr = 0;
        //         foreach ($sumRows3 as $key => $label) {
        //             $val = 0;
        //             $nr++;
        //             $bottom_borderless = $nr < sizeof($sumRows3) ? "bottom-borderless" : "";
        //
        //             if (isset($main[$key]) && $main[$key] > 0) {
        //                 $val = $main[$key];
        //             }
        //             else {
        //                 if (isset($addValues[$key]) && $addValues[$key] > 0) {
        //                     $val = $addValues[$key];
        //                 }
        //             }
        //
        //             echo "<tr class='bg-grey-01 3'>";
        //             echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
        //             echo "<td class='right-borderlesss'>";
        //             echo formatField($key, $val);
        //             echo "</td>";
        //             echo "</tr>";
        //         }
        //     }
        //
        //     echo "</table>";
        //     echo "</div>"; // anakan table
        //
        //     echo "</td>";
        //     echo "</tr>";
        // }
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
                echo $sumSpec2[$key];
                echo "</td>";
                echo "</tr>";
            }
        }

        if (sizeof($addRows) > 0) {
//arrPrint($addRowLabels);
            $nr = 0;
            foreach ($addRowLabels as $k => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
//                arrPrint($addRowHiddens[$k]);
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                echo "<tr class='$rowHide'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                echo "<td colspan='2' class='text-right'>";
                echo $addRows[$k];
                echo "</td>";
                echo "</tr>";
            }
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
        echo "</table class='table'>";
        echo "</div class='table-responsive'>";

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

        echo "<script>

                if($('span[keyid=qty_debet]').length>0){
                    top.shoppingCardValidator();
                }
                else{
                    //top.console.error('tidak perlu validator shoppingcart');
                }

                top.$('#overlay').css('display', 'block')

                function cekForm(){

                    var arrCurrentMain = $('.revMrid[master_h=curent][kolomsrc*=kredit]');
                    jQuery.each(arrCurrentMain, function(a, b){
                        var tmpID = $(b).attr('kolomsrc');
                        var have_child = $(b).parent().attr('have_child');
                        var force_disable = $(b).parent().attr('force_disable');
                        var debet_kredit = tmpID.split('_')[0];
                        var urut = tmpID.split('_')[2];
                        var value_kredit = 0;
                        var value_debet  = 0;

                        switch(debet_kredit){
                            case 'kredit':
                                    var value_debet  = removeCommas( accounting.unformat($('span', $('.revMrid[kolomsrc=debet_curent_'+urut+']')).html()) );
                                    var value_kredit = removeCommas( accounting.unformat($('span', $('.revMrid[kolomsrc=kredit_curent_'+urut+']')).html()) );

//       **                             if( (value_kredit*1)<=0 && (value_debet*1)<=0 ){
                                    if( (value_kredit*1)<0 && (value_debet*1)<0 ){
                                        $('input#debet_adj_'+urut).prop('disabled', true).css('background-color','lightgray')
                                        $('input#kredit_adj_'+urut).prop('disabled', true).css('background-color','lightgray')
                                        //console.error('value debet: '+ value_debet + ' || ' + 'value kredit: ' + value_kredit + ' || urut '+urut+' ==> ' + (value_debet*1<=0 && value_kredit*1<=0));
                                    }
                                    else{
                                        if(have_child==0){
                                            $('input#debet_adj_'+urut).prop('disabled', false).css('background-color','white')
                                            $('input#kredit_adj_'+urut).prop('disabled', false).css('background-color','white')
                                        }
                                        if(force_disable==1){
                                            $('input#debet_adj_'+urut).prop('disabled', true).css('background-color','lightgray')
                                            $('input#kredit_adj_'+urut).prop('disabled', true).css('background-color','lightgray')
                                        }
                                    }

                                    var debet_adj_form = removeCommas($('input#debet_adj_'+urut).val());
                                    var kredit_adj_form = removeCommas($('input#kredit_adj_'+urut).val());

                                    if((kredit_adj_form*1)>0 || (debet_adj_form*1)>0){
                                        $('input#debet_adj_'+urut).parent().parent().addClass('bg-teal').removeClass('bg-gray')
                                    }
                                break;
                        }

                    })

                    var arrCurrentPemb = $('.revCrid[master_h=curent][kolomsrc*=kredit]');
                    jQuery.each(arrCurrentPemb, function(a, b){
                        var tmpID = $(b).attr('kolomsrc');
                        var debet_kredit = tmpID.split('_')[0];
                        var urut = tmpID.split('_')[2] + '_' + tmpID.split('_')[3] + '_' + tmpID.split('_')[4];
//                        console.log(urut);
                        var value_kredit = 0;
                        var value_debet  = 0;

                        var value_prev_debet  = 0;
                        var value_prev_kredit  = 0;

                        switch(debet_kredit){
//                            case 'debet':
//                                    var value_kredit = removeCommas($('span', $('.revCrid[kolomsrc=kredit_curent_'+urut+']')).html());
//                                    var value_debet  = removeCommas($('span', $('.revCrid[kolomsrc=debet_curent_'+urut+']')).html());
//                                break;
                            case 'kredit':
                                    var value_debet  = removeCommas($('span', $('.revCrid[kolomsrc=debet_curent_'+urut+']')).html());
                                    var value_kredit = removeCommas($('span', $('.revCrid[kolomsrc=kredit_curent_'+urut+']')).html());
                                    var value_prev_debet = removeCommas($('span', $('.revCrid[kolomsrc=debet_prev_'+urut+']')).html());
                                    var value_prev_kredit = removeCommas($('span', $('.revCrid[kolomsrc=kredit_prev_'+urut+']')).html());

// **                                   if( (value_kredit*1)<=0 && (value_debet*1)<=0 && (value_prev_debet*1)<=0 && (value_prev_kredit*1)<=0 ){
                                    if( (value_kredit*1)<0 && (value_debet*1)<0 && (value_prev_debet*1)<0 && (value_prev_kredit*1)<0 ){
                                        $('input[kolomsrc=debet_adj_'+urut+']').prop('disabled', true).css('background-color','lightgray')
                                        $('input[kolomsrc=kredit_adj_'+urut+']').prop('disabled', true).css('background-color','lightgray')
                                        //console.error('value debet: '+ value_debet + ' || ' + 'value kredit: ' + value_kredit + ' || urut '+urut+' ==> ' + (value_debet*1<=0 && value_kredit*1<=0));
                                    }
                                    else{
                                        $('input[kolomsrc=debet_adj_'+urut+']').prop('disabled', false).css('background-color','white')
                                        $('input[kolomsrc=kredit_adj_'+urut+']').prop('disabled', false).css('background-color','white')
                                    }
                            break;
                        }
                    })
                }

                function chTemplate(glPrevDebet,glPrevKredit,adjustDebet,adjustKredit,currentDebet,currentKredit){
                    this.glPrevDebet   = glPrevDebet;
                    this.glPrevKredit  = glPrevKredit;
                    this.adjustDebet   = adjustDebet;
                    this.adjustKredit  = adjustKredit;
                    this.currentDebet  = currentDebet;
                    this.currentKredit = currentKredit;
                }

                function calc_semua(loop=0){

                    var arrayTrMain = $('tr[trJenis=rekMain]');
                    var arrayTrPemb = $('tr[trJenis=rekPembantu]');

                    var tableMain=[]
                    var total_glPrevDebet   = 0;
                    var total_glPrevKredit  = 0;
                    var total_adjustDebet   = 0;
                    var total_adjustKredit  = 0;
                    var total_currentDebet  = 0;
                    var total_currentKredit = 0;

                    jQuery.each(arrayTrMain, function(a,b){
                        var jenisRek = $(b).attr('rek_position');

                        var glPrevDebet         = $('span',$('td',$(b))[3]);
                        var glPrevDebet_val     = removeCommas($(glPrevDebet).html())*1;

                        var glPrevKredit        = $('span',$('td',$(b))[4]);
                        var glPrevKredit_val    = removeCommas($(glPrevKredit).html())*1;

                        var adjustDebet         = $('input',$('td',$(b))[5]);
                        var adjustDebet_val     = removeCommas($(adjustDebet).val())*1;

                        var adjustKredit        = $('input',$('td',$(b))[6]);
                        var adjustKredit_val    = removeCommas($(adjustKredit).val())*1;

                        var currentDebet        = $('span',$('td',$(b))[7]); // nilai yg terpengaruh
                        var currentDebet_defval = removeCommas($($('td',$(b))[7]).attr('defvalue'))*1;
                        var currentDebet_val    = removeCommas( accounting.unformat($(currentDebet).html()) )*1;

                        var currentKredit       = $('span',$('td',$(b))[8]); // nilai yg terpengaruh
                        var currentKredit_defval= removeCommas($($('td',$(b))[8]).attr('defvalue'))*1;
                        var currentKredit_val   = removeCommas( accounting.unformat($(currentKredit).html()) )*1;

                        if(jenisRek=='kredit'){
                            if(adjustDebet_val){ //jika form yang di isi DEBET
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval - adjustDebet_val + adjustKredit_val) )
                                }
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval + adjustDebet_val - adjustKredit_val) )
                                }
                            }
                            if(adjustKredit_val){ //jika form yang di isi KREDIT
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval - adjustKredit_val + adjustDebet_val  ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval + adjustKredit_val - adjustDebet_val ) )
                                }
                            }
                            //resetter dari default
                            if(adjustDebet_val*1==0 && adjustKredit_val*1==0){
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval ) )
                                }
                            }
                        }

                        if(jenisRek=='debet'){ //jenis rekening nya DEBET
                            if(adjustKredit_val){ //jika form yang di isi KREDIT
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval - adjustKredit_val + adjustDebet_val ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval + adjustKredit_val - adjustDebet_val ) )
                                }
                            }
                            if(adjustDebet_val){ //jika form yang di isi DEBET
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval - adjustDebet_val + adjustKredit_val) )
                                }
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval + adjustDebet_val - adjustKredit_val ) )
                                }
                            }

                            //resetter dari default
                            if(adjustDebet_val*1==0 && adjustKredit_val*1==0){
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval ) )
                                }
                            }
                        }

                        //tableMain.push(new chTemplate(glPrevDebet,glPrevKredit,adjustDebet,adjustKredit,currentDebet,currentKredit))

                        total_glPrevDebet   += glPrevDebet_val*1;
                        total_glPrevKredit  += glPrevKredit_val*1;
                        total_adjustDebet   += adjustDebet_val*1;
                        total_adjustKredit  += adjustKredit_val*1;
                        total_currentDebet  += currentDebet_val*1;
                        total_currentKredit += currentKredit_val*1;

                    })

//                    $('.sum_debet_prev').html( addCommas(total_glPrevDebet) );
//                    $('.sum_kredit_prev').html( addCommas(total_glPrevKredit) );
//                    if(total_glPrevDebet*1!=total_glPrevKredit*1){
//                        $('.sum_debet_prev').addClass('text-red');
//                        $('.sum_kredit_prev').addClass('text-red');
//                    }
//                    else{
//                        $('.sum_debet_prev').removeClass('text-red').addClass('text-green');
//                        $('.sum_kredit_prev').removeClass('text-red').addClass('text-green');
//                    }

                    $('.sum_debet_adj').html( addCommas(total_adjustDebet) );
                    $('.sum_kredit_adj').html( addCommas(total_adjustKredit) );
                    if(total_adjustDebet*1!=total_adjustKredit*1){
                        $('.sum_debet_adj').addClass('text-red').removeClass('text-green');
                        $('.sum_kredit_adj').addClass('text-red').removeClass('text-green');

                        if(total_adjustDebet*1>total_adjustKredit*1){
                            $('.selisih_adj').html( addCommas(total_adjustDebet*1-total_adjustKredit*1) ).addClass('text-red').removeClass('text-green');
                        }
                        else if(total_adjustKredit*1>total_adjustDebet*1){
                            $('.selisih_adj').html( addCommas(total_adjustKredit*1-total_adjustDebet*1) ).addClass('text-red').removeClass('text-green');
                        }
                        else{
                            $('.selisih_adj').html( 'undefined<br>'+'Kredit: '+total_adjustKredit*1+' | Debet: ' + total_adjustDebet*1 ).addClass('text-red').removeClass('text-green');
                        }
                    }
                    else{
                        $('.sum_debet_adj').removeClass('text-red').addClass('text-green');
                        $('.sum_kredit_adj').removeClass('text-red').addClass('text-green');
                        $('.selisih_adj').html( '<span class=\"fa fa-check-square\"></span> balance' ).removeClass('text-red').addClass('text-green');
                    }

                    $('.sum_debet_curent').html( addCommas(total_currentDebet) );
                    $('.sum_kredit_curent').html( addCommas(total_currentKredit) );
                    if(total_currentDebet*1!=total_currentKredit*1){
                        $('.sum_debet_curent').addClass('text-red').removeClass('text-green');
                        $('.sum_kredit_curent').addClass('text-red').removeClass('text-green');
                        if(total_currentDebet*1>total_currentKredit*1){
                            $('.selisih_curent').html( addCommas(total_currentDebet*1-total_currentKredit*1) ).addClass('text-red').removeClass('text-green');
                        }
                        else if(total_currentKredit*1>total_currentDebet*1){
                            $('.selisih_curent').html( addCommas(total_currentKredit*1-total_currentDebet*1) ).addClass('text-red').removeClass('text-green');
                        }
                        else{
                            $('.selisih_curent').html( 'undefined<br>'+'Debet: '+total_currentDebet*1+' | Kredit: ' + total_currentKredit*1 ).addClass('text-red').removeClass('text-green');
                        }
                    }
                    else{
                        $('.sum_debet_curent').removeClass('text-red').addClass('text-green');
                        $('.sum_kredit_curent').removeClass('text-red').addClass('text-green');
                        $('.selisih_curent').html( '<span class=\"fa fa-check-square\"></span> balance' ).removeClass('text-red').addClass('text-green');
                    }

                    //tableMain.push(new chTemplate(total_glPrevDebet,total_glPrevKredit,total_adjustDebet,total_adjustKredit,total_currentDebet,total_currentKredit))

                    var tablePembantu=[]
                    jQuery.each(arrayTrPemb, function(a,b){
                        var jenisRek = $(b).attr('rek_position');

                        var glPrevDebet         = $('span',$('td',$(b))[3]);
                        var glPrevDebet_val     = removeCommas($(glPrevDebet).html())*1;

                        var glPrevKredit        = $('span',$('td',$(b))[4]);
                        var glPrevKredit_val    = removeCommas($(glPrevKredit).html())*1;

                        var adjustDebet         = $('input',$('td',$(b))[5]);
                        var adjustDebet_val     = removeCommas($(adjustDebet).val())*1;

                        var adjustKredit        = $('input',$('td',$(b))[6]);
                        var adjustKredit_val    = removeCommas($(adjustKredit).val())*1;

                        var currentDebet        = $('span',$('td',$(b))[7]); // nilai yg terpengaruh
                        var currentDebet_defval = removeCommas($($('td',$(b))[7]).attr('defvalue'))*1;
                        var currentDebet_val    = removeCommas($(currentDebet).html())*1;

                        var currentKredit       = $('span',$('td',$(b))[8]); // nilai yg terpengaruh
                        var currentKredit_defval= removeCommas($($('td',$(b))[8]).attr('defvalue'))*1;
                        var currentKredit_val   = removeCommas($(currentKredit).html())*1;

                        //console.log('currentDebet_defval: ' + currentDebet_defval + ' || currentKredit_defval: ' + currentKredit_defval);

//                        tablePembantu.push([glPrevDebet,glPrevKredit,adjustDebet,adjustKredit,currentDebet,currentKredit])

                        if(jenisRek=='kredit'){
                            if(adjustDebet_val){ //jika form yang di isi DEBET
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval - adjustDebet_val + adjustKredit_val ) )
                                }
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval + adjustDebet_val - adjustKredit_val ) )
                                }
                            }
                            if(adjustKredit_val){ //jika form yang di isi KREDIT
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval - adjustKredit_val + adjustDebet_val ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval + adjustKredit_val - adjustDebet_val ) )
                                }
                            }
                            //resetter dari default
                            if(adjustDebet_val*1==0 && adjustKredit_val*1==0){
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval ) )
                                }
                            }
                        }

                        if(jenisRek=='debet'){ //jenis rekening nya DEBET
                            if(adjustKredit_val){ //jika form yang di isi KREDIT
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval - adjustKredit_val + adjustDebet_val ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval + adjustKredit_val - adjustDebet_val ) )
                                }
                            }
                            if(adjustDebet_val){ //jika form yang di isi DEBET
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval - adjustDebet_val + adjustKredit_val ) )
                                }
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval + adjustDebet_val - adjustKredit_val ) )
                                }
                            }

                            //resetter dari default
                            if(adjustDebet_val*1==0 && adjustKredit_val*1==0){
                                if(currentDebet_defval){ //jika current DEBET > 0
                                    $(currentDebet).html( addCommas( currentDebet_defval ) )
                                }
                                if(currentKredit_defval){ //jika current KREDIT > 0
                                    $(currentKredit).html( addCommas( currentKredit_defval ) )
                                }
                            }
                        }
                    })

                    //console.table(tableMain);
                    $('.fixedHeader-floating tfoot').parent().css('bottom', '64px')

                    if(loop){
                        setTimeout(function(){
                            calc_semua()
//                            console.log('call semua from loop');
                        },2500)
                    }
                    else{
                        HoldOn.close();
                        top.$('#overlay').css('display', 'none')
                    }
                }


                //KEYUP PADA PEMBANTU
                $('.crid').on('keyup', delay_v2( function(){

                    var tmpID = $(this).attr('id');
                    var debet_kredit = tmpID.split('_')[0]
                    var indukID = $(this).attr('crid');
                    var curValue = removeCommas($(this).val())

                    $(this).val( addCommas(curValue) )

                    switch(debet_kredit){
                        case 'debet':
                            var arrKreditCrid = $('.crid[crid='+indukID+'][id^=kredit]')
                            var arrDebetCrid = $('.crid[crid='+indukID+'][id^='+debet_kredit+']')

                            var totalKreditCrid = 0;
                            jQuery.each(arrKreditCrid, function(a, b){
                                totalKreditCrid += removeCommas( $(b).val() )
                            })
                            var totalDebetCrid = 0;
                            jQuery.each(arrDebetCrid, function(a, b){
                                totalDebetCrid += removeCommas( $(b).val() )
                            })

                            if(totalDebetCrid*1>0 && totalKreditCrid*1>0){
                                if( totalDebetCrid*1>totalKreditCrid*1 ){
                                    $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( addCommas( totalDebetCrid*1 - totalKreditCrid*1 ) )
                                    $('.mrid[mrid='+indukID+'][id^=kredit]').val( 0 )
                                }
                                else if( totalDebetCrid*1<totalKreditCrid*1 ){
                                    $('.mrid[mrid='+indukID+'][id^=kredit]').val( addCommas( totalKreditCrid*1 - totalDebetCrid*1 ) )
                                    $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( 0 )
                                }
                                else{
                                    console.log('masuk else belum di define LINE: ".__LINE__."');
                                    $('.mrid[mrid='+indukID+'][id^=kredit]').val( 0 )
                                    $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( 0 )
                                }
                            }
                            else{
                                $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( addCommas(totalDebetCrid) )

                                console.log('==============='+ debet_kredit +'===============')
                                console.log('totalDebetCrid: ' + totalDebetCrid);
                                console.log('totalKreditCrid: ' + totalKreditCrid);
                            }

                            break;
                        case 'kredit':
                            var arrDebetCrid = $('.crid[crid='+indukID+'][id^=debet]')
                            var arrKreditCrid = $('.crid[crid='+indukID+'][id^='+debet_kredit+']')

                            var totalDebetCrid = 0;
                            jQuery.each(arrDebetCrid, function(a, b){
                                totalDebetCrid += removeCommas( $(b).val() )
                            })

                            var totalKreditCrid = 0;
                            jQuery.each(arrKreditCrid, function(a, b){
                                totalKreditCrid += removeCommas( $(b).val() )
                            })

                            if(totalDebetCrid*1>0 && totalKreditCrid*1>0){
                                if( totalDebetCrid*1>totalKreditCrid*1 ){
                                    $('.mrid[mrid='+indukID+'][id^=debet]').val( addCommas( totalDebetCrid*1 - totalKreditCrid*1 ) )
                                    $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( 0 )
                                }
                                else if( totalDebetCrid*1<totalKreditCrid*1 ){
                                    $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( addCommas( totalKreditCrid*1 - totalDebetCrid*1 ) )
                                    $('.mrid[mrid='+indukID+'][id^=debet]').val( 0 )
                                }
                                else{
                                    console.log('masuk else belum di define LINE: ".__LINE__."');
                                    $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( 0 )
                                    $('.mrid[mrid='+indukID+'][id^=debet]').val( 0 )
                                }
                            }
                            else{
                                $('.mrid[mrid='+indukID+'][id^='+debet_kredit+']').val( addCommas(totalKreditCrid) )

                                console.log('==============='+ debet_kredit +'===============')
                                console.log('totalDebetCrid: ' + totalDebetCrid);
                                console.log('totalKreditCrid: ' + totalKreditCrid);
                            }

                            break;
                    }

                    setTimeout(function(){
                        calc_semua(1)
                    },200)

                }, 500))


                //KEYUP PADA MAIN REKENING
                $('.mrid').on('keyup', delay_v2( function(){
                    var curValue = removeCommas($(this).val())
                    $(this).val( addCommas(curValue) )
                    setTimeout(function(){
                        calc_semua(1)
                    },200)
                }, 500))

//                function calcFooter(){
//                    //adj_footer_calc
//                    var mainForm = $('.mrid');
//                    var adj_total_debet = 0;
//                    var adj_total_kredit = 0;
//                    if( mainForm.length*1 > 0 ){
//                        jQuery.each(mainForm, function(a,b){
//                            var tmpID = $(b).attr('id');
//                            var debet_kredit = tmpID.split('_')[0]
//                            var mrid = $(b).attr('mrid');
//                            switch(debet_kredit){
//                                case 'debet':
//                                    adj_total_debet += removeCommas($(b).val())*1
//                                    break;
//                                case 'kredit':
//                                    adj_total_kredit += removeCommas($(b).val())*1
//                                    break;
//                            }
//                        })
//
//                        $('th[kk=debet_adj]').html( addCommas(adj_total_debet) );
//                        console.log('adj_total_debet: ', adj_total_debet);
//
//                        $('th[kk=kredit_adj]').html( addCommas(adj_total_kredit) );
//                        console.log('adj_total_kredit: ', adj_total_kredit);
//
//                    }
//                }



                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                    //fixedHeader: true,
                    fixedHeader: {
                        header: true,
                        footer: false
                    },
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip', //tanpa button tambahan (Huruf B)
//                    dom: 'lfBTrtip', //dengan button tambahan (Huruf B)
                    drawCallback: function ( settings ) {
                        var intVal = function ( i ) {
                            return typeof i === 'string' ? i.replace(/[$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
//                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
//                                if ( last !== group ) {
//                                    groupid++;
//                                    last = group;
//                                }
//                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
//                                $.each(val,function(index2,val2){
//                                    if (typeof subtotale[groupid] =='undefined'){
//                                        subtotale[groupid] = new Array();
//                                    }
//                                    if (typeof subtotale[groupid][index2] =='undefined'){
//                                        subtotale[groupid][index2] = 0;
//                                    }
//                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
//                                    valore = Number(val2.replace('€','').replace(',',''));
//                                    if(isNaN(valore)){
//                                        var testValorVal2 = accounting.unformat(val2);
//                                        valore = testValorVal2
//                                    }
//                                    subtotale[groupid][index2] += valore;
//                                    totale['Totale'][index2] += valore;
//                                });
//                            }
                        });

                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            if(rowCount*1>0){
                                $(this).find('td:nth-child(3)').parent().attr('have_child', rowCount);
                                $(this).find('td:nth-child(3)').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '&nbsp;' + (rowCount*1>0 ? '<span title=\"click untuk expand\" class=\"text-red text-lg text-right\">('+rowCount+') REK</span>' : '') })));
                            }
                        });
                    }
                });

                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $(this).toggleClass('collapsed','expanded');
//                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
//                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });

                $(window).resize(function() {
                    oTable.draw(false)
                });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                    delay_v2(function () {
                    $('.grid').DataTable().fixedHeader.adjust();
                }, 200));

                function expandSemua(){
                    var targetExpand = $('.group');
                    jQuery.each(targetExpand, function(a, b){
                        if( $(b).hasClass('collapsed') ){
                            $(b).click()
                        }
                    })
                }

                function collapSemua(){
                    var targetExpand = $('.group');
                    jQuery.each(targetExpand, function(a, b){
                        if( !$(b).hasClass('collapsed') ){
                            $(b).click()
                        }
                    })
                }

                function deleteSearch(){
                    var arrKetemu = localStorage.searchKetemu!= undefined ? JSON.parse(localStorage.searchKetemu) : false
                    if(arrKetemu){
                        jQuery.each(arrKetemu, function(a, v){
                            jQuery.each(v, function(aa,bb){
                                document.getElementById(bb).style.removeProperty('background-color');
                            })
                        })
                        localStorage.searchKetemu = '{}'
                        document.getElementById('searchForm').elements['searchItem'].disabled = false;
                        $('#go').attr('onclick', 'document.getElementById(\"searchForm\").submit(); return false;').html('cari').toggleClass('btn-info btn-danger')
                        document.getElementById('searchForm').elements['searchItem'].value = ''
                        toastr.success('search berhasil dihapus')
                    }
                    else{
                        localStorage.searchKetemu = '{}'
                        $('#go').attr('onclick', 'document.getElementById(\"searchForm\").submit(); return false;').html('cari').toggleClass('btn-info btn-danger')
                        document.getElementById('searchForm').elements['searchItem'].value = ''
                        toastr.success('search berhasil dihapus')
                    }
                }

                function openPemRek(rekID){
                    var myrekmain = $('#'+rekID).attr('myrekmain');
                    if( $('#'+rekID).is(':hidden') ){
                        $('#'+myrekmain).click();
                        setTimeout(function(){
                            document.getElementById(rekID).scrollIntoView()
//                            document.getElementById(rekID).style.setProperty('font-weight', '900', 'important');
                            var positionSekarang = $(document).scrollTop()
                            //console.log('positionSekarang: ', positionSekarang);
                            setTimeout(function(){
                                var geserSitikJoss = (positionSekarang-100);
                                $(document).scrollTop(geserSitikJoss)
                                //console.log('geserSitikJoss: ', geserSitikJoss);

//                                setTimeout(function(){
//                                    document.getElementById(rekID).style.removeProperty('font-weight');
//                                },5000)

                            },200)
                        },200)
                    }
                    else{
                        setTimeout(function(){
                            document.getElementById(rekID).scrollIntoView()
//                            document.getElementById(rekID).style.setProperty('font-weight', '900', 'important');
                            var positionSekarang = $(document).scrollTop()
                            //console.log('positionSekarang: ', positionSekarang);
                            setTimeout(function(){
                                var geserSitikJoss = (positionSekarang-100);
                                $(document).scrollTop(geserSitikJoss)
                                //console.log('geserSitikJoss: ', geserSitikJoss);

//                                setTimeout(function(){
//                                    document.getElementById(rekID).style.removeProperty('font-weight');
//                                },5000)
                            },200)
                        },200)
                    }
                }

                function gotoMainRek(rekID){
                    setTimeout(function(){
                        document.getElementById(rekID).scrollIntoView()
//                        document.getElementById(rekID).style.setProperty('font-weight', '900', 'important');
                        var positionSekarang = $(document).scrollTop()
                        //console.log('positionSekarang: ', positionSekarang);
                        setTimeout(function(){
                            var geserSitikJoss = (positionSekarang-100);
                            $(document).scrollTop(geserSitikJoss)
                            //console.log('geserSitikJoss: ', geserSitikJoss);

//                            setTimeout(function(){
//                                document.getElementById(rekID).style.removeProperty('font-weight');
//                            },5000)
                        },200)
                    },200)
                }

                function searchNamaRekening() {
                   var name = document.getElementById('searchForm').elements['searchItem'].value;
                   var pattern = name.toLowerCase();
                   var targetId = '';
                   var divs = document.getElementsByClassName('nama_rekening');
                   var kode = document.getElementsByClassName('kode_rekening');
                   var arrMain=[]
                   var arrPemb=[]
                   for (var i = 0; i < divs.length; i++) {
                      var para = divs[i].getElementsByTagName('span');
                      var index = para[0].innerText.toLowerCase().indexOf(pattern);
                      if(index != -1){
                         targetId   = divs[i].parentNode.id;
                         trJenis    = $(divs[i]).parent().attr('trJenis');
                         switch(trJenis){
                            case 'rekPembantu':
                                arrPemb.push(targetId);
                            break;
                            case 'rekMain':
                                arrMain.push(targetId);
                            break;
                         }
                         document.getElementById(targetId).style.setProperty('background-color', 'yellow', 'important');
                      }
                   }

                   for (var i = 0; i < kode.length; i++) {
                      var para = kode[i].getElementsByTagName('span');
                      var index = para[0].innerText.toLowerCase().indexOf(pattern);
                      if(index != -1){
                         targetId   = kode[i].parentNode.id;
                         trJenis    = $(kode[i]).parent().attr('trJenis');
                         switch(trJenis){
                            case 'rekPembantu':
                                arrPemb.push(targetId);
                            break;
                            case 'rekMain':
                                arrMain.push(targetId);
                            break;
                         }
                         document.getElementById(targetId).style.setProperty('background-color', 'yellow', 'important');
                      }
                   }

                   ketemu = {arrMain: arrMain, arrPemb: arrPemb}

                   if( ketemu.arrMain.length>0 || ketemu.arrPemb.length>0 ){
                        var tmpArrKetemu={}
                        jQuery.each(ketemu, function(rekJenis, arrRek){
                            if(arrRek.length>0){
                                tmpArrKetemu[rekJenis] = arrRek
                            }
                        })

                        var txtKetemu=''
                        jQuery.each(tmpArrKetemu, function(rekJenis2, arrRek2){
//                            console.log('rekJenis2: ', rekJenis2);
                            if(arrRek2.length>0){
                                var titles = rekJenis2=='arrPemb'?'Rek Pembantu' : 'Rek Main'
                                var cssBg = rekJenis2=='arrPemb'?'bg-pink' : 'bg-yellow'
                                txtKetemu += '<div style=\"margin-top: 5px;border-radius: 5px;\" class=\"text-bold text-uppercase bg-pink text-center\"><p>'+titles+'</p></div>'
                                jQuery.each(arrRek2,function(i, rekID){
                                    var rekName = $('#'+rekID+' .nama_rekening span:first').text()
                                    if(rekJenis2=='arrPemb'){
                                        txtKetemu += '<div onclick=\"openPemRek(\''+rekID+'\')\" class=\"text-uppercase row_hasil_pencarian\" >'+rekName+'</div>'
                                    }
                                    else{
                                        var rekNo = $('#'+rekID+' .kode_rekening span:first').text()
                                        txtKetemu += '<div onclick=\"gotoMainRek(\''+rekID+'\')\" class=\"text-uppercase row_hasil_pencarian\" >'+rekNo+'-'+rekName+'</div>'
                                    }
                                })
                            }
                        })

                        localStorage.searchKetemu = JSON.stringify(ketemu)
                        document.getElementById('searchForm').elements['searchItem'].disabled = true;
                        $('#go').attr('onclick', 'deleteSearch()').html('<span class=\"fa fa-close\"></span>').toggleClass('btn-info btn-danger')

                        toastr.success(txtKetemu, 'Hasil Pencarian',{
                          'closeButton': true,
                          'debug': false,
                          'newestOnTop': false,
                          'progressBar': false,
                          'positionClass': 'toast-top-right',
                          'preventDuplicates': false,
                          'onclick': null,
                          'showDuration': '300',
                          'hideDuration': '1000',
                          'timeOut': 0,
                          'extendedTimeOut': 0,
                          'showEasing': 'swing',
                          'hideEasing': 'linear',
                          'showMethod': 'fadeIn',
                          'hideMethod': 'fadeOut',
                          'tapToDismiss': false
                        })

                   }
                   else{
                        swal('pencarian', 'Rekening (<b>'+name+'</b>) tidak ditemukan dimanapun..', 'warning')
                        toastr.error('pencarian gagal menemukan <b>'+name+'</b>')
                   }
                }

                //collaps semua secara default


                $(document).ready(function(){
                    setTimeout(function(){
                        $('.group').trigger('click', function(){ swal('click selesai') })
                        setTimeout(function(){
                            cekForm()
                            setTimeout(function(){
                                calc_semua(1);
                            },500)
                        },500)
                    },500)
                })

                </script>";


    }
    else {
        echo "<div class='panel-body'>";
        echo "<div class='text-danger'>";
        echo "- <strong>you have not chosen any item yet</strong> -<br>";
        echo "<small>you can do so by selecting items from available selectors</small><br>";
        echo "</div class='text-warning'>";
        echo "</div class='panel-body'>";
    }

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
