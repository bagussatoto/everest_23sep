<?php


if (isset($isSettlement) && ($isSettlement == true)) {

    $strByTrans = "";
    $strByMenu = "";
    $strByPym = "";
    $strByBahan = "";

    if (isset($headerByTransaksi) && sizeof($headerByTransaksi) > 0) {
        if (isset($settlementByTransaksi) && sizeof($settlementByTransaksi) > 0) {
            $itemTrans = (isset($settlementByTransaksiProduk) && sizeof($settlementByTransaksiProduk) > 0) ? $settlementByTransaksiProduk : array();

//            $strByTrans .= "<h3>$labelByTransaksi</h3>";

            $total = 0;
            foreach ($settlementByTransaksi as $trNomor => $spec) {
                $total += $spec['validGrandTotal'];

                $strByTrans .= "<div class='table-responsive no-padding no-border'>";

                if (isset($headerMainByTransaksi) && sizeof($headerMainByTransaksi) > 0) {
                    foreach ($headerMainByTransaksi as $key => $val) {
                        $mhVal = isset($spec[$key]) ? $spec[$key] : "";
                        if ($val == NULL) {
                            $strByTrans .= formatField($key, $mhVal);
                        }
                        else {
                            $strByTrans .= "$val : " . formatField($key, $mhVal);
                        }
                        $strByTrans .= "<br>";
                    }
                }

                //region header per-transaksi
                $strByTrans .= "<table class='table table-condensed no-padding table-bordered no-margin'>";
                $strByTrans .= "<tr class='bg-grey-2 text-uppercase'>";
                $strByTrans .= "<th style='width:0.5%;' class='text-center'>";
                $strByTrans .= "No.";
                $strByTrans .= "</th>";
                foreach ($headerByTransaksi as $key => $val) {
                    $strByTrans .= "<th class='text-muted text-center'>";
                    $strByTrans .= $val;
                    $strByTrans .= "</th>";
                }
                $strByTrans .= "</tr>";

                if (isset($itemTrans[$trNomor]) && sizeof($itemTrans[$trNomor]) > 0) {
                    $no_itr = 0;
                    foreach ($itemTrans[$trNomor] as $iSpec) {
                        $no_itr++;

                        $strByTrans .= "<tr>";
                        $strByTrans .= "<td style='text-align: right;'>$no_itr</td>";

                        foreach ($headerByTransaksi as $key => $val) {
                            $colVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                            $strByTrans .= "<td>$colVal</td>";
                        }
                        $strByTrans .= "</tr>";
                    }
                }

                if (isset($summaryByTransaksi) && sizeof($summaryByTransaksi) > 0) {
                    foreach ($summaryByTransaksi as $key => $val) {
                        $colspan = sizeof($headerByTransaksi);
                        $sumVal = isset($spec[$key]) ? $spec[$key] : "0";

                        $strByTrans .= "<tr>";
                        $strByTrans .= "<td style='text-align: right;' colspan='$colspan'>$val</td>";
                        $strByTrans .= "<td>" . formatField($key, $sumVal) . "</td>";
                        $strByTrans .= "</tr>";
                    }
                }

                $strByTrans .= "</table>";
                //endregion


                $strByTrans .= "</div>";
                $strByTrans .= "<br>";

            }

            $strByTrans .= "<div style='color:#000000;font-size:18px;' class='alert alert-warning'>";
            $strByTrans .= "<div><span sstyle='color:#000000;font-size:18px;'>TOTAL BILL: </span><span class='pull-right'>" . formatField("debet", sizeof($settlementByTransaksi)) . "</span></div>";
            $strByTrans .= "<div><span sstyle='color:#000000;font-size:18px;'>TOTAL BY NOTA/BILL: </span><span class='pull-right'>" . formatField("debet", $total) . "</span></div>";
            $strByTrans .= "</div>";
            $strByTrans .= "<br><br>";

        }
    }

    if (isset($settlementByMenu) && sizeof($settlementByMenu) > 0) {
        if (isset($headerByMenu) && sizeof($headerByMenu) > 0) {
            $strByMenu .= "<div class='table-responsive no-padding no-border'>";
            $strByMenu .= "<h3>$labelByMenu</h3>";
            $strByMenu .= "<table class='table table-condensed no-padding table-bordered no-margin'>";
            $strByMenu .= "<tr class='bg-grey-2 text-uppercase'>";
            $strByMenu .= "<th style='width:0.5%;' class='text-center'>";
            $strByMenu .= "No.";
            $strByMenu .= "</th>";
            foreach ($headerByMenu as $key => $val) {
                $strByMenu .= "<th class='text-muted text-center'>";
                $strByMenu .= $val;
                $strByMenu .= "</th>";
            }
        }
        $strByMenu .= "<tr class='bg-grey-2 text-uppercase'>";

        $no_menu = 0;
        $total_menu = array();
        foreach ($settlementByMenu as $mSpec) {
            $no_menu++;
            $strByMenu .= "<tr>";
            $strByMenu .= "<td style='text-align: right;'>$no_menu</td>";

            foreach ($headerByMenu as $key => $val) {

                $colVal = isset($mSpec[$key]) ? formatField($key, $mSpec[$key]) : "";
                $strByMenu .= "<td>$colVal</td>";

                if (isset($mSpec[$key]) && is_numeric($mSpec[$key])) {
                    if (!isset($total_menu[$key])) {
                        $total_menu[$key] = 0;
                    }
                    $total_menu[$key] += $mSpec[$key];
                }
            }
            $strByMenu .= "</tr>";
        }
        $strByMenu .= "<tr>";
        $strByMenu .= "<td style='text-align: right;' colspan='2'>TOTAL</td>";
        foreach ($headerByMenu as $key => $val) {
            if (isset($total_menu[$key])) {
                $colVal = formatField($key, $total_menu[$key]);
                $strByMenu .= "<td>$colVal</td>";
            }
        }
        $strByMenu .= "</tr>";
        $strByMenu .= "</table class='table table-condensed no-padding table-bordered no-margin'>";
        $strByMenu .= "</div class='table-responsive no-padding no-border'>";
        $strByMenu .= "<br>";
    }

    if (isset($settlementByPayment) && sizeof($settlementByPayment) > 0) {
        if (isset($headerByPayment) && sizeof($headerByPayment) > 0) {
            $strByPym .= "<div class='table-responsive no-padding no-border'>";
            $strByPym .= "<h3>$labelByPayment</h3>";
            $strByPym .= "<table class='table table-condensed no-padding table-bordered no-margin'>";
            $strByPym .= "<tr class='bg-grey-2 text-uppercase'>";
            $strByPym .= "<th style='width:0.5%;' class='text-center'>";
            $strByPym .= "No.";
            $strByPym .= "</th>";
            foreach ($headerByPayment as $key => $val) {
                $strByPym .= "<th class='text-muted text-center'>";
                $strByPym .= $val;
                $strByPym .= "</th>";
            }
        }
        $strByPym .= "<tr class='bg-grey-2 text-uppercase'>";

        $no_menu = 0;
        $total_menu = array();
        foreach ($settlementByPayment as $mSpec) {
            $no_menu++;
            $strByPym .= "<tr>";
            $strByPym .= "<td style='text-align: right;'>$no_menu</td>";

            foreach ($headerByPayment as $key => $val) {

                $colVal = isset($mSpec[$key]) ? formatField($key, $mSpec[$key]) : "";
                $strByPym .= "<td>$colVal</td>";

                if (isset($mSpec[$key]) && is_numeric($mSpec[$key])) {
                    if (!isset($total_menu[$key])) {
                        $total_menu[$key] = 0;
                    }
                    $total_menu[$key] += $mSpec[$key];
                }
            }
            $strByPym .= "</tr>";
        }
        $strByPym .= "<tr>";
        $strByPym .= "<td style='text-align: right;' colspan='2'>TOTAL</td>";
        foreach ($headerByPayment as $key => $val) {
            if (isset($total_menu[$key])) {
                $colVal = formatField($key, $total_menu[$key]);
                $strByPym .= "<td>$colVal</td>";
            }
        }
        $strByPym .= "</tr>";
        $strByPym .= "</table class='table table-condensed no-padding table-bordered no-margin'>";
        $strByPym .= "</div class='table-responsive no-padding no-border'>";
        $strByPym .= "<br>";
    }

    if (isset($settlementByBahan) && sizeof($settlementByBahan) > 0) {
        if (isset($headerByBahan) && sizeof($headerByBahan) > 0) {
            $strByBahan .= "<div class='table-responsive no-padding no-border'>";
            $strByBahan .= "<h3>$labelByBahan</h3>";
            $strByBahan .= "<table class='table table-condensed no-padding table-bordered no-margin'>";
            $strByBahan .= "<tr class='bg-grey-2 text-uppercase'>";
            $strByBahan .= "<th style='width:0.5%;' class='text-center'>";
            $strByBahan .= "No.";
            $strByBahan .= "</th>";
            foreach ($headerByBahan as $key => $val) {
                $strByBahan .= "<th class='text-muted text-center'>";
                $strByBahan .= $val;
                $strByBahan .= "</th>";
            }
        }
        $strByBahan .= "<tr class='bg-grey-2 text-uppercase'>";

        $no_menu = 0;
        $total_menu = array();
        foreach ($settlementByBahan as $mSpec) {
            $no_menu++;
            $strByBahan .= "<tr>";
            $strByBahan .= "<td style='text-align: right;'>$no_menu</td>";

            foreach ($headerByBahan as $key => $val) {

                $colVal = isset($mSpec[$key]) ? formatField($key, $mSpec[$key]) : "";
                $strByBahan .= "<td>$colVal</td>";

                if (isset($mSpec[$key]) && is_numeric($mSpec[$key])) {
                    if (!isset($total_menu[$key])) {
                        $total_menu[$key] = 0;
                    }
                    $total_menu[$key] += $mSpec[$key];
                }
            }
            $strByBahan .= "</tr>";
        }
        $strByBahan .= "<tr>";
        $strByBahan .= "<td style='text-align: right;' colspan='3'>TOTAL</td>";
        foreach ($headerByBahan as $key => $val) {
            if (isset($total_menu[$key])) {
                $colVal = formatField($key, $total_menu[$key]);
                $strByBahan .= "<td>$colVal</td>";
            }
        }
        $strByBahan .= "</tr>";
        $strByBahan .= "</table class='table table-condensed no-padding table-bordered no-margin'>";
        $strByBahan .= "</div class='table-responsive no-padding no-border'>";
    }

    echo $strByTrans;
    echo $strByMenu;
    echo $strByPym;
    echo $strByBahan;

}
else {
// arrPrintPink($items);
    if (isset($items)) {

        if (isset($fixedNoteTop)) {
            echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
            echo "<span>$fixedNoteTop</span>";
            echo "</div>";
        }

        $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

        if (sizeof($items) > 0) {
            $jmlKolomHeader = sizeof($itemLabels) + 2;
            // cekHijau($jmlKolomHeader);
            echo "<div class='table-responsive no-padding no-border'>";
            echo "<table class='table table-condensed no-padding table-bordered no-margin'>";

            //region clear shoping cart
            if (!$avoidRemove) {
                echo "<tr class='bg-grey-2'>";
                // echo "<td>&nbsp;</td>";
                echo "<td colspan='" . (sizeof($itemLabels) + 2) . "'>";

                echo "<span class='pull-left'>";
                echo "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
                echo "</span>";

                echo "</td>";
                echo "</tr>";
            }
            //endregion

            /*===============header shoping cart======================*/
            if (isset($itemLabels)) {
                if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                    echo "<tr class='bg-grey-2 text-uppercase'>";
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "NO";
                    echo "</th>";
                    foreach ($itemLabels as $key => $label) {
                        echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                        echo $label;
                        echo "</th>";
                    }

                    if (!$avoidRemove) {
                        // echo "<th style='width:1%;' class='text-muted text-center'>";
                        // echo "x";
                        // echo "</th>";
                    }
                    echo "</tr>";
                }
            }
            //        arrprint($keyUpEvents);
            //        arrprint($selectedPrices);


            /*===============body shoping cart=======================================*/
            $no = 0;
            foreach ($items as $iSpec) {

                if ($showItems) {

                    $iID = $iSpec['id'];
                    $rowRemove = "<a href='javascript:void(0);' class='text-red pull-right' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';setTimeout( function(){ $('#shopping_cart').trigger('change') }, 1500);\"><i class='fa fa-times'></i></a>";
                    $no++;
                    $bgColor = "transparent";
                    if (isset($_SESSION['errLines'])) {
                        if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                            $bgColor = "#ffff77";
                        }
                    }

                    echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                    echo "<td style='vertical-align:middle; width:1%' class='text-center'>";
                    echo $no;
                    echo $rowRemove;
                    echo "</td>";
                    $colCtr = 0;
                    $queryParams = "";
                    $colID = array();
                    $listMode = array();
                    $readOnly = array();
                    $qtyParam = "";
                    if (isset($itemLabels['jml'])) {
                        //                $queryParams .= "&$key='+document.getElementById('" . $colID[$key] . "').value+'";
                        $qtyParam = "+document.getElementById('jml_$no').value";
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
                            $queryParams .= "&$key='+document.getElementById('" . $colID[$key] . "').value+'";
                        }
                    }

                    $tabIndex = 1000;
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
                                //cekHitam($key);
                                echo "<input type='number' tabindex=".($tabIndex+$noID)." min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . $fieldVal . "' onclick='this.select()' " . $keyUpStr[$key] . " ";

                                $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                                $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" $keyupData";
                                $pemicuGerbangAsli .= "onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                                $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                                $pemicuGerbang .= "onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
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
                                    echo "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';\"";
                                }
                                else {
                                    echo "onkeyup=\"delay_v2( function(){ $('#shopping_cart').trigger('change') }, 400, this );\"";
                                }
                                echo ">";

                                $tabIndex += $tabIndex;

                                break;
                            case "text":
                                if (is_numeric($fieldVal)) {
                                    //                            echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . formatField($key, $fieldVal) . "</span>";
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . ($fieldVal) . "</span>";
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

                    //region remover per row
                    if (!$avoidRemove) {
                        // echo "<td width='1%'>";
                        // echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                        // echo "</td>";
                    }
                    //endregion

                    echo "</tr>";

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

                                        echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";

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
                        echo "<td>&nbsp;</td>";
                        $c_itemLabels = sizeof($itemLabels);
                        $c_pairedItemField = sizeof($pairedItemField);
                        $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
                        echo "<td colspan='" . $c_colspan . "'>";
                        //==pairedItems, if any
                        if (isset($selItems) && sizeof($selItems) > 0) {
                            echo "<select
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
                                    echo "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
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
                                        echo "<input type='number' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    >";
                                        break;
                                    case "text":
                                        if (is_numeric($fieldVal)) {
                                            echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . number_format($fieldVal) . "</span>";
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
                echo "<tr class='bg-info'>";
                echo "<td colspan='$jmlKolomHeader'>";

                // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
                echo "<div class='panel no-margin'>"; // anakan table
                echo "<table class='table table-condensed table-striped no-padding no-border'>";

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
                    echo "</tr>";
                    //endregion
                }

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
                    foreach ($itemLabels2 as $key => $label) {
                        //                if(in_array($key,$editableFields)){
                        $colID = $key . "_" . $no;
                        $queryParams .= "&$key='+document.getElementById('$colID').value+'";
                        //                }
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
                        $tabIndexNum = $colCtr . $no;

                        if (is_numeric($iSpec[$key])) {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo formatField($key, $iSpec[$key]);
                            // echo $iSpec[$key];
                        }
                        else {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo $iSpec[$key];
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                //endregion

                echo "</table>";
                echo "</div>"; // anakan table

                // arrPrint($kurangStoks);

                echo "</td>";
                echo "</tr>";
            }
            //endregion

            //region items3
            if (isset($items3) && sizeof($items3) > 0) {
                echo "<tr class='bg-info'>";
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
                    echo "</tr>";
                    //endregion
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
                    echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                    echo "<td width='5%'>";
                    echo $no;
                    echo ".</td>";
                    $colCtr = 0;
                    $queryParams = "";
                    foreach ($itemLabels3 as $key => $label) {
                        //                if(in_array($key,$editableFields)){
                        $colID = $key . "_" . $no;
                        $queryParams .= "&$key='+document.getElementById('$colID').value+'";
                        //                }
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
                        $tabIndexNum = $colCtr . $no;

                        if (is_numeric($iSpec[$key])) {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo $iSpec[$key];
                        }
                        else {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo $iSpec[$key];
                        }
                        echo "</td>";
                    }
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
                        echo "<input type='text' id='$key' class='form-control text-right' readonly value='" . ($val) . "' >";
                        echo "</td>";
                        echo "</tr>";
                    }

                }
            }
            if (isset($sumRows2) && sizeof($sumRows2) > 0) {
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
                    $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "";
                    echo "<tr class='$rowHide'>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td colspan='2' class='text-right'>";
                    echo $addRows[$k];
                    echo "</td>";
                    echo "</tr>";
                }
            }

            //region clear shoping cart
            if (!$avoidRemove) {
                echo "<tr class='bg-grey-2'>";
                // echo "<td>&nbsp;</td>";
                echo "<td colspan='" . (sizeof($itemLabels) + 2) . "'>";

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
            // $volume_gross = "";
            // $berat_gross = "";
            // if (isset($detilSizeBar)) {
            //     if (sizeof($detilSizeBar) > 0) {
            //
            //         $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
            //         $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
            //
            //         $volume = isset($detilSizeBar['volume']) ? $detilSizeBar['volume'] : 0;
            //         $berat = isset($detilSizeBar['berat']) ? $detilSizeBar['berat'] : 0;
            //
            //
            //         echo "<div class='row bg-danger' style='background: #ffdecf;padding: 7px;'>";
            //         echo "<div class='col-md-3 col-lg-3'>
            //                 <div class='input-group'>
            //                 <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
            //                 <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume' disabled=''>
            //                 </div>
            //              </div>";
            //         echo "<div class='col-md-3 col-lg-3'>
            //                 <div class='input-group'>
            //                 <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
            //                 <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat' disabled=''>
            //                 </div>
            //              </div>";
            //         echo "<div class='col-md-3 col-lg-3'>
            //                 <div class='input-group'>
            //                 <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
            //                 <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
            //                 </div>
            //              </div>";
            //         echo "<div class='col-md-3 col-lg-3'>
            //                 <div class='input-group'>
            //                 <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
            //                 <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
            //                 </div>
            //              </div>";
            //         echo "</div>";
            //     }
            // }


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


            // items element
            if (sizeof($itemElements) > 0) {
                echo "<div class='panel-body table-responsive'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12'>";
                echo "<h4 class='text-blue text-left'>Please fill in details below</h4>";
                echo "</div class='col-md-12'>";
                echo "</div class='row'>";
                echo "<div class='col-lg-12 no-padding text-center' style='text-align:center;'>";
                $elCtr = 0;
                foreach ($itemElements as $eName => $pSpec) {

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
                        if (isset($itemElementConfigs[0]['autoSelect']) && $itemElementConfigs[0]['autoSelect']) {
                        }
                        else {
                            $helpName = $pSpec['helpName'];
                            echo "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $itemElementResetTarget . "$eName/$helpName';\"><span class='fa fa-eraser'></span></a>";
                        }
                        echo "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";
                        echo "</div class='box-title'>";

                        if (isset($itemElementConfigs[0]['warningLabel']) && $itemElementConfigs[0]['warningLabel']) {
                            echo "<div class='col-md-12'>" . $itemElementConfigs[0]['warningLabel'] . "</div>";
                        }

                        echo "<div class=''>&nbsp;</div>";
                        $strSpec = $pSpec['string'];
                        /*-----------------------------
                         * penampil element kirimna dari controler
                         * -----------------------*/
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

}
