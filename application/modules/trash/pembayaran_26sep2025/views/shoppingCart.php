<?php

if (isset($items)) {

    //arrprint($main);
    $elementTimeStart = microtime(true);

    echo "<style>
            .slide-container {
                padding: 15px 0;
            }
            .slide-bar {
                  height: 8px;
                  background: #e0e0e0;
                  border-radius: 4px;
                  margin-top: 15px;
                  position: relative;
            }
            .slide-handle {
                  width: 25px;
                  height: 25px;
                  background: #4CAF50;
                  border-radius: 50%;
                  position: absolute;
                  top: -8px;
                  left: 0;
                  cursor: grab;
            }
        </style>";

    $elementBawah = 0;
    $contentElement = "";
    /* ============== ================ */
    if (!$elementBawah) {
        if (sizeof($elements) > 0) {
            $contentElement .= "<div class='panel-body table-responsive'>";
            $contentElement .= "<div class='row'>";
            $contentElement .= "<div class='col-md-12'>";
            $contentElement .= "<h4 class='text-blue text-left'>Please fill in details below</h4>";
            $contentElement .= "</div>";
            $contentElement .= "</div>";

            // region ORIGINAL
//            $contentElement .= "<div class='col-lg-12 no-padding text-center' style='text-align:center;'>";
//            $elCtr = 0;
//            foreach ($elements as $eName => $pSpec) {
//                $elCtr++;
//                if (isset($pSpec['type']) && ($pSpec['type'] == "hidden")) {
//                    // type hidden tidak perlu tampil di ui //
//                }
//                else {
//                    if ($elCtr % 2 == 0) {
//                    }
//                    else {
////                        $contentElement .=  "<div class='col-lg-12 no-padding'>";
//                        $contentElement .=  "<div class='row row-eq-height'>";
//                    }
//                    $contentElement .=  "<div class='col-md-6 col-lg-6' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";
//                    $contentElement .=  "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";
//                    $contentElement .=  $pSpec['label'] . " ";
//                    if (isset($elementConfigs[$eName]['autoSelect']) && $elementConfigs[$eName]['autoSelect']) {
//
//                    }
//                    else {
//                        $contentElement .=  "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
//                    }
//                    if (isset($elementConfigMutasi[$eName])) {
//                        $modalDialog = modalDialogBtn('&nbsp;', $elementConfigMutasi[$eName], $auto_close = 0, 'saldo');
//                        $contentElement .=  "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"$modalDialog\" ttarget='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
//                    }
//                    $contentElement .=  "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";
//                    $contentElement .=  "</div class='box-title'>";
//                    if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
//                        $contentElement .=  "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
//                    }
//                    $contentElement .=  "<div class=''>&nbsp;</div>";
//                    $contentElement .=  $pSpec['string'];
//                    $contentElement .=  "</div>";
//                    if ($elCtr % 2 == 0) {
////                        $contentElement .=  "</div>";
//                        $contentElement .=  "</div>";
//                    }
//                }
//            }
//            $contentElement .=  "</div>";
            //endregion ORIGINAL

            $contentElement .= "<div class='col-lg-12 no-padding text-center' style='text-align:center;'>";
            $elCtr = 0;
            foreach ($elements as $eName => $pSpec) {
                if (isset($pSpec['type']) && ($pSpec['type'] == "hidden")) {
                    continue;
                }

                $elCtr++;
                // Start row for every odd element (1st, 3rd, 5th, etc.)
                if ($elCtr % 2 == 1) {
                    $contentElement .= "<div class='row row-eq-height'>";
                }

                $contentElement .= "<div class='col-md-6 col-lg-6' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";
                $contentElement .= "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";
                $contentElement .= $pSpec['label'] . " ";

                // Show erase icon if not auto-select
                if (!isset($elementConfigs[$eName]['autoSelect']) || !$elementConfigs[$eName]['autoSelect']) {
                    $contentElement .= "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
                }

                // Mutation icon
                if (isset($elementConfigMutasi[$eName])) {
                    $modalDialog = modalDialogBtn('&nbsp;', $elementConfigMutasi[$eName], $auto_close = 0, 'saldo');
                    $contentElement .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"$modalDialog\" ttarget='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                }

                $contentElement .= "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";
                $contentElement .= "</div>"; // Close elTitle

                // Warning label
                if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
                    $contentElement .= "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
                }

                $contentElement .= "<div class=''>&nbsp;</div>";
                $contentElement .= $pSpec['string'];
                $contentElement .= "</div>"; // Close col-md-6

                // Close row after every even element (2nd, 4th, 6th, etc.)
                if ($elCtr % 2 == 0) {
                    $contentElement .= "</div>"; // Close row
                }
            }

// Close last row if total elements is odd
            if ($elCtr % 2 == 1) {
                $contentElement .= "</div>"; // Close row
            }

            $contentElement .= "</div>"; // Close col-lg-12

//            if (isset($showScheme) && sizeof($showScheme) > 0) {
//
//                $contentElement .=  "<div class='clearfix'><hr></div>";
//                $contentElement .=  "<div class='col-md-12 no-padding'>";
//                $contentElement .=  "<div class='text-center text-danger text-bold'>-- SKEMA PINJAMAN ANDA --</div>";
//                $contentElement .=  "<div class='text-center text-danger text-bold meta'>generator skema hanya berlaku untuk single kreditur</div>";
//                $contentElement .=  "<div class='text-center text-danger text-bold'> ========================================== </div>";
//
//                //header skema
//                $contentElement .=  "<div class='col-md-12 no-padding'>";
//
//                $contentElement .=  "<span class='col-md-2 text-left text-bold no-padding'>Nama Pemegang Saham </span>
//                <span class='text-left col-md-9 no-padding text-capitalize'>: " . $headerScheme['nama'] . "</span>";
//
//                //                $headerScheme = array(
//                //                    "nama" => "$nmPemengangSaham",
//                //                    "jml_pinjaman" => "$nilai_pinjaman",
//                //                    "bunga_tahunan" => "$rate_bunga",
//                //                    "awal_meminjam" => "$awal_pinjaman",
//                //                    "pelunasan_pinjaman" => "$jatuh_tempo",
//                //                    "lama_pinjaman" => "$total_hari hari ($total_bulan bln)",
//                //                );
//
//                $contentElement .=  "<span class='col-md-2 text-left text-bold no-padding'>Jumlah Pinjaman </span>      <span class='text-left col-md-9 no-padding'>: " . number_format($headerScheme['jml_pinjaman']) . "</span>";
//                $contentElement .=  "<span class='col-md-2 text-left text-bold no-padding'>Bunga Tahunan </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['bunga_tahunan'] . "%</span>";
//                $contentElement .=  "<span class='col-md-2 text-left text-bold no-padding'>Awal Meminjam </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['awal_meminjam'] . "</span>";
//                $contentElement .=  "<span class='col-md-2 text-left text-bold no-padding'>Pelunasan Pinjaman </span>   <span class='text-left col-md-9 no-padding'>: " . $headerScheme['pelunasan_pinjaman'] . "</span>";
//                $contentElement .=  "<span class='col-md-2 text-left text-bold no-padding'>Lama Pinjaman </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['lama_pinjaman'] . "</span>";
//
//                $contentElement .=  "</div>";
//                $contentElement .=  "<div class='clearfix'>&nbsp;</div>";
//                $contentElement .=  "<div><table id='main_table' class='table datatable table-bordered table-hover table-striped'><thead>";
//                $contentElement .=  "<tr>  <th width='1%'>No</th>
//                            <th>Periode</th>
//                            <th>jml hari / periode</th>
//                            <th>Pokok Pinjaman</th>
//                            <th>Rate Bunga</th>
//                            <th>Nilai Bunga</th>
//                            <th>PPh23</th>
//                            <th>bunga setelah dipotong PPh</th>
//                      </tr>";
//
//                $contentElement .=  "</thead><tbody>";
//
//                $total_bunga = 0;
//                $total_pph23 = 0;
//                $total_bunga_pph23 = 0;
//                $total_hari = 0;
//                $no = 1;
//
//                foreach ($showScheme as $thnbln => $pinjaman) {
//
//                    $setBackground = isset($pinjaman['silangan']) ? $pinjaman['silangan'] : "merah";
//                    $bgColor = " ";
//
//                    switch ($setBackground) {
//                        default:
//                        case "merah":
//                            $bgColor = "bg-white";
//                            break;
//                        case "hijau":
//                            $bgColor = "bg-success";
//                            break;
//                        case "berjalan":
//                            $bgColor = "bg-warning";
//                            break;
//                    }
//
//                    $contentElement .=  "  <tr>
//                                <td class='$bgColor'>$no</td>
//                                <td class='$bgColor'>" . date('F Y', strtotime($pinjaman['thnbln'] . '-01')) . "</td>
//                                <td class='$bgColor'>" . $pinjaman['jml_hari_dbln'] . "</td>
//                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pinjaman'], 0) . "</td>
//                                <td class='$bgColor'>" . $pinjaman['rate_bunga'] . "%</td>
//                                <td class='$bgColor'>" . number_format($pinjaman['nilai_bunga'], 0) . "</td>
//                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pph23'], 0) . "</td>
//                                <td class='$bgColor'>" . number_format($pinjaman['nett_bunga'], 0) . "</td>
//                            </tr>";
//
//                    $no++;
//
//                    $total_bunga += $pinjaman['nilai_bunga'] * 1;
//                    $total_pph23 += $pinjaman['nilai_pph23'] * 1;
//                    $total_bunga_pph23 += $pinjaman['nett_bunga'] * 1;
//                    $total_hari += $pinjaman['jml_hari_dbln'] * 1;
//                }
//
//                $contentElement .=  "<tfoot>
//                        <tr>
//                            <td>-</td>
//                            <td>-</td>
//                            <td>" . $total_hari . "</td>
//                            <td>-</td>
//                            <td>-</td>
//                            <td>" . number_format($total_bunga, 0) . "</td>
//                            <td>" . number_format($total_pph23, 0) . "</td>
//                            <td>" . number_format($total_bunga_pph23, 0) . "</td>
//                        </tr>
//                    </tfoot>";
//
//                $contentElement .=  "</tbody>
//                        </table>
//                        </div>";
//                $contentElement .=  "<div class='clearfix'>&nbsp;</div>";
//                $contentElement .=  "<div class='text-left'>Keterangan:</div>";
//                $contentElement .=  "<div class='text-left'> - periode dengan background hijau akan otomatis dibuatkan <span class='text-capitalize text-bold'>request loan interest</span> sesaat setelah request pinjaman diapprove </div>";
//                $contentElement .=  "</div>";
//                $contentElement .=  "<br>";
//            }

        }
    }

    // ============= FIXED NOTE ===============
    $fixedNoteBawah = 0;
    $contenFixedNote = "";
    if (isset($fixedNote)) {
        $contenFixedNote .= "<div class='alert alert-danger' style='margin-top: 10px;font-size: 15px;'>";
        $contenFixedNote .= "<span>$fixedNote</span>";
        if (isset($fixedNoteLink)) {
            foreach ($fixedNoteLink as $fixedNoteLink_spec) {
                $link = isset($fixedNoteLink_spec['link']) ? $fixedNoteLink_spec['link'] : NULL;
                $labels = isset($fixedNoteLink_spec['label']) ? $fixedNoteLink_spec['label'] : NULL;
                $contenFixedNote .= "<span><br>- $labels</span>";
                if ($link != NULL) {
                    $contenFixedNote .= "atau <a href=\"$link\" target='_parent'>klik disini</a>.";
                }
            }
        }
        $contenFixedNote .= "</div>";
    }

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

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
        echo "</div>";

        /*=============== BADGE PPN / NON PPN =================*/
        /*
         * untuk penmpil produk di a/P payment
         */

        //arrprintWebs($main_data);
        //-------------------------------------
        if (sizeof($arrRefData) > 0) {
            $listRef = "";
            foreach ($arrRefData as $tr_ref_po => $tr_ref_po_data) {
                $main_data = $tr_ref_po_data["main"];
                //                arrprint($main_data);
                $items_data = $tr_ref_po_data["items"];
                $nomer_ref_po = $main_data["nomer"];
                $nomer_ref_po_f = formatField_he_format("nomer_nolink", $nomer_ref_po) . "-" . $main_data["global_number_reference"];

                $listRef .= "<div class='panel panel-default'>";
                $listRef .= "<div class='panel-body' style='padding: 5px;'>";
                $listRef .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>Nomer PO: $nomer_ref_po_f</div>";
                $listRef .= "<table class='table table-bordered table-condensed no-margin' id='rincian_penerimaan_produk_00'>";

                $listRef .= "<thead>";
                $listRef .= "<tr class='bg-primary'>";
                $listRef .= "<th>No</th>";
                foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                $listRef .= "<th>Subtotal</th>";
                $listRef .= "</tr>";
                $listRef .= "</thead>";

                $dt = 0;
                foreach ($items_data as $pids => $dataPID) {
                    $dt++;
                    $listRef .= "<tr>";
                    $listRef .= "<td>$dt</td>";
                    foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    $listRef .= "<td  class='text-bold'>" . formatField("subtotal", $dataPID["subtotal"]) . "</td>";
                    $listRef .= "</tr>";
                }

                $colspan = count($receiptDetailFieldsReference) + count($receipCartNumFieldsReference) + 1;
                $listRef .= "<tfoot cclass='bg-gray'>";
                foreach ($receiptSumFieldsReference as $pkey => $p_label) {
                    $listRef .= "<tr>";
                    $listRef .= "<td colspan='$colspan' style='font-size: 12px;padding-right: 20px;' class='text-bold text-meta text-right'>$p_label</td>";
                    $listRef .= "<td colspan='' style='font-size: 12px;padding-right: 10px;' class='text-bold text-meta text-right'>" . formatField($pkey, $main_data[$pkey]) . "</td>";
                    $listRef .= "</tr>";
                }
                $listRef .= "</tfoot>";

                $listRef .= "</table class='table table-bordered no-margin' id='rincian_penerimaan_produk_00'>";
                $listRef .= "</div class='panel-body' style='padding: 5px;'>";
                $listRef .= "</div class='panel panel-default'>";
                $listRef .= "<br>";
            }
            echo $listRef;
        }
        //-------------------------------------
        if (sizeof($itemsBelumGrn) > 0) {
            $listRef = "";
            foreach ($mainBelumGrn as $trid => $mainBelumGrnSpec) {
                $nomer_ref_po = $mainBelumGrnSpec["transaksi_no"];
                $nomer_ref_po_f = formatField_he_format("nomer_nolink", $nomer_ref_po) . "-" . $arrRefData[$trid]["main"]["global_number_reference"];
                $bgcolor = $mainBelumGrnSpec["background_color"];

                $listRef .= "<div class='panel panel-default'>";
                $listRef .= "<div class='panel-body' style='padding: 5px;'>";
                $listRef .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>Belum GRN Nomer PO: $nomer_ref_po_f</div>";
                $listRef .= "<table class='table table-bordered table-condensed no-margin' id='rincian_penerimaan_produk_01'>";

                $listRef .= "<thead>";
                $listRef .= "<tr class='bg-primary'>";
                $listRef .= "<th>No</th>";
                foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                $listRef .= "<th>Subtotal</th>";
                $listRef .= "</tr>";
                $listRef .= "</thead>";

                $dt = 0;
                foreach ($itemsBelumGrn[$trid] as $pids => $dataPID) {
                    $dt++;
                    $listRef .= "<tr style='background-color:$bgcolor;'>";
                    $listRef .= "<td>$dt</td>";
                    foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td  class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    $listRef .= "<td class='text-bold'>" . formatField("subtotal", $dataPID["subtotal"]) . "</td>";
                    $listRef .= "</tr>";
                }

                $colspan = count($receiptDetailFieldsReference) + count($receipCartNumFieldsReference) + 1;
                $listRef .= "<tfoot cclass='bg-gray'>";
                foreach ($receiptSumFieldsReference as $pkey => $p_label) {
                    $listRef .= "<tr style='background-color:$bgcolor;'>";
                    $listRef .= "<td colspan='$colspan' style='font-size: 12px;padding-right: 20px;' class='text-bold text-meta text-right'>$p_label</td>";
                    $listRef .= "<td colspan='' style='font-size: 12px;padding-right: 10px;' class='text-bold text-meta text-right'>" . formatField($pkey, $mainBelumGrnSpec[$pkey]) . "</td>";
                    $listRef .= "</tr>";
                }
                $listRef .= "</tfoot>";

                $listRef .= "</table class='table table-bordered no-margin' id='rincian_penerimaan_produk_01'>";
                $listRef .= "</div class='panel-body' style='padding: 5px;'>";
                $listRef .= "</div class='panel panel-default'>";
                $listRef .= "<br>";
            }
            echo $listRef;
        }
        //-------------------------------------

        $listProduk = "";
        if (count($items4) > 0) {
            $colspan = count($items4Label);
            $input_diskon_global = "";
            if ($transaksi_jenis == "489") {
                $input_diskon_global .= "<div class='input-group pull-right' style='width: 20VW;padding: 6px;'>";
                $input_diskon_global .= "<span class='input-group-addon text-bold text-red'>ADDITIONAL DISKON</span>";
                $input_diskon_global .= "<span class='input-group-addon'><input placeholder='masukan nilai' style='min-width: 8vw;' type='text' id='global_disc' class='form-control text-right text-bold'></span>";
                $input_diskon_global .= "<span class='input-group-addon'><span id='diskon_terapkan' class='btn btn-md btn-warning btn-flat pull-right'>TERAPKAN</span></span>";
                //            $input_diskon_global .= "<span class='input-group-addon'><input type='checkbox' id='global_disc_tic' onclick=\"add_global_disc('global_disc');\" class='pull-right'></span>";
                $input_diskon_global .= "</div>";
            }
            $listProduk = "<div class='panel panel-default'>";
            $listProduk = "";
            $listProduk .= $optionPpn;
            $listProduk .= "<div class='panel panel-default'>";
            $listProduk .= "<div class='panel-body' style='padding: 5px;'>";
            $listProduk .= "<div id='overlay'><div id='text'>Loading Content...</div></div>";
            $listProduk .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>$shopingCartPairProdukGateLabel $input_diskon_global</div>";
            $listProduk .= "<table class='table table-bordered no-margin' id='rincian_penerimaan_produk'>";
            $listProduk .= "<thead>";
            $listProduk .= "<tr class='bg-primary'>";
            $listProduk .= "<th>No</th>";
            foreach ($items4Label as $pKey => $pid_label) {
                $listProduk .= "<th>$pid_label</th>";
            }
            $listProduk .= "</tr>";
            $listProduk .= "</thead>";

            $dt = 0;
            $subtotal = 0;
            $ppn = 0;
            foreach ($items4 as $pids => $dataPID) {
                $dt++;
                $listProduk .= "<tr>";
                $listProduk .= "<td>$dt</td>";
                foreach ($items4Label as $pkey => $p_label) {
                    if ($pkey == "subtotal") {
                        $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                        $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                        $disabled = $checked == "checked" ? "" : "disabled";
                        $backgroundColor = $checked == "checked" ? "bg-olive" : "bg-red";
                        $listProduk .= "<td pkey='$pkey' class='text-auto text-bold text-right no-padding'>";
                        $listProduk .= "<div class='funkyradio-success'>";
                        $listProduk .= "
                                    <div class='input-group border-cekx'>
                                        <input it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi no-border form-control $backgroundColor text-right no-padding'>
                                        <span class='input-group-addon'>
                                            <input name='arrItems4' it4pid='$pids' it4key='$pkey' id='det_$pids$pkey' class='checkDetails' $checked onclick=\" \" type='checkbox'>
                                        </span>
                                    </div>
                              ";
                        $listProduk .= "</div>";
                        $listProduk .= "</td>";
                    }
                    else {
                        if ($pkey == "add_disc_persen") {
                            $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                            $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                            $disabled = $checked == "checked" ? "" : "disabled";
                            $backgroundColor = $checked == "checked" ? "" : "bg-red";
                            $listProduk .= "<td pkey='$pkey' class='text-auto text-bold text-right no-padding'>";
                            $listProduk .= "<div class='funkyradio-success'>";
                            $listProduk .= "
                                    <div class='input-groupx border-cekx'>
                                        <input disabled it4_subtotal='" . $dataPID['subtotal'] . "' placeholder='$pkey' it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi_$pkey no-border form-control $backgroundColor text-right no-padding'>
                                    </div>
                              ";
                            $listProduk .= "</div>";
                            $listProduk .= "</td>";
                        }
                        else {
                            if ($pkey == "add_disc_rupiah") {
                                $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                                $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                                $disabled = $checked == "checked" ? "disabled" : "disabled";
                                $backgroundColor = $checked == "checked" ? "bg-grey" : "bg-red";
                                $listProduk .= "<td pkey='$pkey' class='text-auto text-bold text-right no-padding'>";
                                $listProduk .= "<div class='funkyradio-success'>";
                                $listProduk .= "
                                    <div class='input-groupx border-cekx'>
                                        <input it4_subtotal='" . $dataPID['subtotal'] . "' placeholder='$pkey' it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi_$pkey no-border bg-olive form-control $backgroundColor text-right no-padding'>
                                    </div>
                              ";
                                $listProduk .= "</div>";
                                $listProduk .= "</td>";
                            }
                            else {
                                $listProduk .= "<td pkey='$pkey' class='text-bold'>" . formatField($pkey, $dataPID[$pkey]) . "</td>";
                            }
                        }
                    }
                }
                $subtotal += $dataPID["subtotal"];
                $ppn += $dataPID["ppn"];
            }
            $total = $ppn + $subtotal;
            $listProduk .= "</tr>";
            $listProduk .= "<tfoot class='bg-gray'>";
            $listProduk .= "<tr>";
            $listProduk .= "<th colspan='' style='font-size: 16px;padding-right: 40px;' class='text-bold text-meta text-right'>-</th>";
            foreach ($items4Label as $pkey => $p_label) {
                $listProduk .= "<th colspan='' style='font-size: 16px;padding-right: 40px;' class='text-bold text-meta text-right'></th>";
            }
            $listProduk .= "</tr>";
            $listProduk .= "</tfoot>";
            $listProduk .= "</table>";
            $listProduk .= "<div id='wr_koreksi'></div>";
            $listProduk .= "</div>";
            $listProduk .= "</div>";
            $listProduk .= "<script>
                if( localStorage.last_global_diskon != 'undefined'){
                    $('#global_disc').val(localStorage.last_global_diskon)
                }
                $('.in_koreksi_add_disc_rupiah').off();
                $('.in_koreksi_add_disc_rupiah').on('keyup', delay_v2(function(event){
                    var formID = $(this).attr('id');
                    var pkey = $(this).attr('it4key');
                    $(this).attr('nilaiKeyup', removeCommas($(this).val()));
                    var nilaiDiskonGlobal = removeCommas($('#global_disc').val())*1
                    var tablesProduk = $('#rincian_penerimaan_produk tbody tr');
                    jQuery.each(tablesProduk, function(a, b){
                        var sourcepKey = $('td[pkey='+pkey+'] input', $(this)).attr('id');
                    });
                }, 250));

                $('#global_disc').on('keyup', delay_v2(function(a){
                    //detect enter
                    if( a.keyCode == 13){
                        $('#diskon_terapkan').click()
                    }
                }, 1000));

                var ses_nilaiTagihanNonPPN = 0;
                var ses_nilaiDiskonNonPPN = 0;
                $('#diskon_terapkan').on('click', delay_v2(function(){
                    var thisssss = $('#global_disc')
                    var additional_diskon = $(thisssss).val();
                    var isValid = 0;
                    var url = '$additionalDiskonRecorderTarget/diskon_global?debuger=0&val='+removeCommas(additional_diskon)
                    $.ajax(url).always(function(data) {
                        jsonData = JSON.parse(data);
                        if(jsonData.status == 0){
                            swal('PERHATIAN',jsonData.msg,'error');
                            localStorage.last_global_diskon = 0;
                            return;
                        }

                        localStorage.last_global_diskon = $(thisssss).val();
                        var tablesProduk = $('#rincian_penerimaan_produk tbody tr');
                        var totalOri = 0;
                        var totalAfterDiskon = 0;
                        var nilaiTagihan = 0;
                        var nilaiTagihanNonPPN = 0;

                        jQuery.each(tablesProduk, function(a, b){
                            var _hrgNow = removeCommas($('td[pkey=harga] span', $(b)).html());
                            var _qtyNow = removeCommas($('td[pkey=jml] span', $(b)).html());
                            var _totalNonPPn = (_hrgNow*1) * (_qtyNow*1);
                            nilaiTagihan += _totalNonPPn*1.11;
                            nilaiTagihanNonPPN += _totalNonPPn;
                        });

                        ses_nilaiTagihanNonPPN = nilaiTagihanNonPPN;

                        var total_harga_produk_nppn = nilaiTagihan;
                        var nilaiDiskonGlobal = removeCommas($(thisssss).val());
                        var tmp_total_diskon = (nilaiDiskonGlobal*1) / (total_harga_produk_nppn*1);
                        var total_diskon = tmp_total_diskon;

                        var whenMethod = '';
                        var totalProporsi = 0;
                        var totalDiskonItems = 0;

                        jQuery.each(tablesProduk, function(a, b){
                            var hrgNow = removeCommas($('td[pkey=harga] span', $(b)).html());
                            var qtyNow = removeCommas($('td[pkey=jml] span', $(b)).html());
                            var totalNonPPn = (hrgNow*1) * (qtyNow*1);
                            var afterDiskon = totalNonPPn - (totalNonPPn*total_diskon);
                            var nominDiskon = totalNonPPn-afterDiskon;
                            var proporsiItems = (totalNonPPn/nilaiTagihanNonPPN)*100;
                            var nilaiGlobalDiskon = removeCommas($(thisssss).val())*1;

                            totalProporsi += proporsiItems*1;
                            totalDiskonItems += nominDiskon*1;
                            ses_nilaiDiskonNonPPN += nominDiskon.toFixed(0)*1;

                            $('td[pkey=add_disc_persen] input', $(b)).val(proporsiItems.toFixed(2))
                            $('td[pkey=add_disc_persen] input', $(b)).attr('nilaiAuto', proporsiItems.toFixed(2))
                            $('td[pkey=add_disc_rupiah] input', $(b)).val( addCommas(nominDiskon.toFixed(0)) )
                            $('td[pkey=add_disc_rupiah] input', $(b)).attr('nilaiAuto', addCommas(nominDiskon.toFixed(0)) )

                            totalOri += totalNonPPn;
                            totalAfterDiskon += afterDiskon;
                            var rowPosisi = tablesProduk.length;
                            if(a == rowPosisi - 1){
                                localStorage.shoppingcartNoReload = 0
                            }
                            $('td[pkey=subtotal] span input', $(b)).prop('checked', true)
                            $('td[pkey=subtotal] input', $(b)).val(addCommas( afterDiskon.toFixed(0) )).trigger('kirim_via_jne');
                        });
                        var footer = $('#rincian_penerimaan_produk tfoot tr th');
                        jQuery.each(footer, function(a, b){
                            if(a==6){
                                $(b).html(addCommas(totalProporsi.toFixed(0))).css('padding-right', 0)
                            }
                            if(a==7){
                                var b7 = ''
                                b7 += '<div>'+addCommas(totalDiskonItems.toFixed(0))+'</div>'
                                b7 += '<div><span class=pull-left>+PPN 11%</span><span class=pull-right>'+addCommas( (totalDiskonItems*1.11).toFixed(0))+'</span></div>'
                                $(b).html(b7).css('padding-right', 0)
                            }
                            if(a==9){
                                var b9 = ''
                                b9 += '<div>'+addCommas(totalAfterDiskon.toFixed(0))+'</div>'
                                b9 += '<div><span class=pull-left>+PPN 11%</span><span class=pull-right>'+addCommas( (totalAfterDiskon*1.11).toFixed(0))+'</span></div>'
                                $(b).html(b9)
                            }
                        })
                    });
                    if(!isValid){
                        console.log('tidak valid isValid')
                        return;
                    }
                }, 200))

                $('.in_koreksi').off();
                $('.in_koreksi').keyup(function(){
                    this.value = addCommas(this.value)
                });

                $('.in_koreksi').keyup(delay_v2(function(){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    var check = 0
                    if( $('#det_'+pid+''+key).prop('checked') ){
                        check = 1
                    }
                    var url = '$koreksiRecorderTarget/'+key+'/'+pid+'?val='+removeCommas(this.value)+'&check='+check
                    $.ajax(url)
                    .always(function(data) {
                        var shoppingcartNoReload = localStorage.shoppingcartNoReload;
                        if( shoppingcartNoReload!= 'undefined' && shoppingcartNoReload == 1 ){
                            console.log('tidak di reload ')
                        }
                        else{
                            top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){
                            });
                        }
                    });
                }, 1200));

                $('.in_koreksi_add_disc_rupiah').keyup(delay_v2(function(){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    var check = 1
                    var url = '$koreksiRecorderTarget/'+key+'/'+pid+'?val='+removeCommas(this.value)+'&check='+check
                    $.ajax(url)
                        .always(function(data) {
                            top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){
                        });
                    });
                }, 1200));

                $('.in_koreksi').on('kirim_via_jne', function(){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');

                    var check = 0
                    if( $('#det_'+pid+''+key).prop('checked') ){
                        check = 1
                    }

                    var totalNonPPn = removeCommas(this.value)*1;
                    ses_nilaiTagihanNonPPN = ses_nilaiTagihanNonPPN-totalNonPPn;

                    var url = '$koreksiRecorderTarget/'+key+'/'+pid+'?val='+removeCommas(this.value)+'&check='+check

                    if( ((ses_nilaiTagihanNonPPN*1) - (ses_nilaiDiskonNonPPN*1)).toFixed(0) == 0){
                        $.ajax(url)
                        .done(function(data) {
                             top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){

                             });
                        });
                    }
                    else{
                        $.ajax(url)
                        .done(function(data) {

                        });
                    }
                });

                var checkBox = $('.checkDetails');
                // $('.checkDetails').trigger('change');
                // console.log('checkBox:', checkBox);
                // console.log('-------------------------------------');
                jQuery.each(checkBox, function(a,b){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    var formValue = removeCommas($('#in_'+pid+''+key).val())*1;
                    var orig = removeCommas($('#in_'+pid+''+key).attr('origvalue'))*1;
                    var sesval = removeCommas($('#in_'+pid+''+key).attr('sesVal'))*1;
                    
                    // console.log('formValue: ' + formValue);
                    // console.log('orig: ' + orig.toFixed(0));
                    // console.log('sesval: ' + sesval);
                });
                $('.checkDetails').off();
                $('.checkDetails').on('click', function() {
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    document.getElementById('overlay').style.display = 'block';
                    if( $(this).prop('checked') ){
                        $('#in_'+pid+''+key).prop('disabled', false)
                        .removeClass('bg-red')
                        .select()
                        .trigger('keyup');
                    }
                    else{
                        var origvalue = $('#in_'+pid+''+key).attr('origvalue')*1;
                        $('#in_'+pid+''+key).val( origvalue.toFixed(0) );
                        $('#in_'+pid+''+key).prop('disabled', true)
                        .addClass('bg-red')
                        .blur()
                        .trigger('keyup');
                    }
                });
            </script>";

            // arrPrintHijau($main);
            $uang_muka_dipakai_ppn = isset($main['uang_muka_dipakai_ppn']) ? $main['uang_muka_dipakai_ppn'] : "";
            $cash_account = isset($main['cash_account']) ? $main['cash_account'] : 0;
            $is_cash_account = isset($main['cash_account']) ? 1 : 0;
            $is_skip_faktur = isset($main['skip_faktur']) ? 1 : 0;

            $listProduk .= "<script>
                    var konfirmasi_cek = false;
                    var uang_muka_dipakai_ppn = removeCommas($uang_muka_dipakai_ppn);
                    var isSkipFaktur = '$is_skip_faktur';
                    var isCashAccount = '$is_cash_account';
                    function semuaCheckboxDicentang() {
                        var semuaDicentang = true;
                        // Menggunakan loop untuk memeriksa setiap checkbox dalam kolom
                        var tt = 0;
                        $('.checkDetails').each(function() {
                            tt++;
                            // var checkbox = $(this).find('td:eq(7) input[type=\'checkbox\']');
                            var checkbox = $(this);                        
                            // console.log('tt::', tt);
                            // console.log('checkbox::', checkbox.prop('checked'));
                            // console.log(checkbox);
                            // console.log('checkbox::', $(checkbox).is(\":checked\"));
                            // Periksa status cek checkbox
                            if (!checkbox.prop('checked')) {
                                semuaDicentang = false;
                                return false; // Menghentikan loop jika ada checkbox yang tidak dicentang
                            }
                        });
                        // console.log('semuaDicentang', semuaDicentang);
                        return semuaDicentang;
                    }
                    var skip_faktur = $('#skip_faktur').prop('checked');
                    var dateFaktur = $('#dateFaktur').val();
                    var eFaktur = $('#eFaktur').val();
                    // console.log('semuaDicentang:', semuaCheckboxDicentang);
                    if (semuaCheckboxDicentang()) {
                        console.log('Semua checkbox dalam kolom telah dicentang.');
                        // if(konfirmasi_cek == false){                        
                        //     $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                        // }
                        konfirmasi_cek = true;
                      // if(isUm === 1 && uang_muka_dipakai_ppn == ''){                          
                      //       // $('#uang_muka_dipakai_ppn').css('background-color', '#95fd75').focus();
                      //       $('#uang_muka_dipakai_ppn').css('background-color', 'pink').focus();
                      // }
                      // else {
                      //     console.log('hahaha');
                      //     console.log('isCashAccount', isCashAccount);
                      //     console.log('isSkipFaktur', isSkipFaktur);
                      //    
                      //     if(isSkipFaktur == 0){                              
                      //       $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                      //       $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
                      //       // $('#dateFaktur').css('border-color', 'red');
                      //       $('#eFaktur').css('border-color', 'red').focus();
                      //       // $('#eFaktur').css('border-color', 'red');
                      //     }
                      //     else if(isCashAccount == 0 && isSkipFaktur == 1){                         
                      //      
                      //       $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
                      //     }
                      // }
                    } else {
                        console.log('belum dicentang semua');
                        $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                        $('#rincian_penerimaan_produk').focus();
//                        swal({type: 'warning',title: 'Upss..',html: 'Berilah tic pada setiap nilai, sebagai konfirmasi nilainya sudah benar'});
//                         swal({type: 'warning',title: 'Upss..',html: 'Cek pada nilai barang, bila sudah sesuai silahkan cukup berikan tic, bila belum sesuai silahkan dikoreksi.'});
                    }
                    // console.log('konfirmasi_cek 297:', konfirmasi_cek);
                </script>";

            $listProduk .= "<script>
                $('#global_disc').keyup(function(){
                    this.value = addCommas(this.value)
                });
                $('#global_disc_tic').change(function() {
                    if ($(this).is(':checked')) {
                        // Checkbox is checked
                        var value = removeCommas($('#global_disc').val());
                        console.log('Checkbox checked. Value:', value);
                        // Add your code here to handle the checked state
                        // add_global_disc(value);
                    }
                    else {
                        // Checkbox is unchecked
                        console.log('Checkbox unchecked.');
                        // Add your code here to handle the unchecked state
                        // remove_global_disc();
                    }
                });
            </script>";
        }
        else {
            $listProduk .= "<script>
                    var konfirmasi_cek = false;
                     </script>";
        }
        echo $listProduk;

        //-------------------------------------------------------------------------------
        if (sizeof($shopingCartPaymentItemsColor) > 0) {
            $legend = "";
            foreach ($shopingCartPaymentItemsColor['colorCode'] as $ix => $ixSpec) {
                $bgcolor = $ixSpec["color"];
                $legend .= "<span ids='legend_' class='btn btn-sm' style='background-color:$bgcolor;'> </span> " . $ixSpec['label'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            echo $legend;
        }
        //-------------------------------------------------------------------------------
        //arrprint($shopingCartEmbededTaxItem);
        /**
         * untuk handling view pajak karena tidak bsia dimasukan ke items
         */
        //        arrprint($shopingCartTaxtarget);
        $embededTaxItems = "";
        $embededTaxItemsTarget = "";
        if (count($shopingCartTaxtarget) > 0) {
            $embededTaxItems .= "<div class='panel panel-default' style='border:1px solid pink;'>";
            $embededTaxItems .= "<div class='panel-header text-bold font-size-1-5 text-uppercase border-ceek overflow-h'>" . $shopingCartEmbededTaxItem["fieldEmbeded"]["itemSrc"]["title"] . "</div>";
            $embededTaxItems .= "<table line='" . __LINE__ . "'  ids='' class='table table-condensed no-padding table-bordered no-margin'>";
            if (isset($shopingCartEmbededTaxItem["fieldEmbeded"]["itemSrc"])) {
                $embededTaxItems .= "<tr>";
                $embededTaxItems .= "<th line='" . __LINE__ . "' >No</th>";
                foreach ($shopingCartEmbededTaxItem["fieldEmbeded"]["itemSrc"]["fields"] as $k_tax => $k_label) {
                    $embededTaxItems .= "<th line='" . __LINE__ . "' >$k_label</th>";
                }
                $embededTaxItems .= "</tr>";
                $i = 0;
                $subtotal = 0;
                foreach ($shopingCartTaxSrc as $shopingCartTaxSrc_0) {
                    foreach ($shopingCartTaxSrc_0 as $shopingCartTaxSrc_0_1) {
                        $i++;
                        $embededTaxItems .= "<tr>";
                        $embededTaxItems .= "<td line='" . __LINE__ . "' >$i</td>";
                        foreach ($shopingCartEmbededTaxItem["fieldEmbeded"]["itemSrc"]["fields"] as $k_tax => $k_label) {
                            $values = $shopingCartTaxSrc_0_1[$k_tax];
                            if (is_numeric($values)) {
                                $values = number_format($values, 0);
                                $class = "text-right";
                            }
                            else {
                                $class = "";
                            }
                            $embededTaxItems .= "<td line='" . __LINE__ . "' class='$class'>$values</td>";
                        }
                        $embededTaxItems .= "</tr>";
                        $subtotal += $shopingCartTaxSrc_0_1["sisa"];
                    }
                }
                $embededTaxItems .= "<tr>";
                $embededTaxItems .= "<td line='" . __LINE__ . "' colspan='6' class='text-right text bold'>PPN MASUKAN</td>";
                $embededTaxItems .= "<td line='" . __LINE__ . "' class='text-right text-bold'>" . number_format($subtotal, 0) . "</td>";
                $embededTaxItems .= "</tr>";
            }
            $embededTaxItems .= "</table>";
            $embededTaxItems .= "</div>";
            $embededTaxItemsTarget .= "<div class='panel panel-default' style='border:1px solid blue;'>";
            $embededTaxItemsTarget .= "<div class='panel-header text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>" . $shopingCartEmbededTaxItem["fieldEmbeded"]["itemtarget"]["title"] . "</div>";
            $embededTaxItemsTarget .= "<table ids='' class='table table-condensed no-padding table-bordered no-margin'>";
            if (isset($shopingCartEmbededTaxItem["fieldEmbeded"]["itemtarget"])) {
                $embededTaxItemsTarget .= "<tr>";
                $embededTaxItemsTarget .= "<th line='" . __LINE__ . "' >No</th>";
                foreach ($shopingCartEmbededTaxItem["fieldEmbeded"]["itemtarget"]["fields"] as $k_tax => $k_label_i) {
                    $embededTaxItemsTarget .= "<th line='" . __LINE__ . "' >$k_label_i</th>";
                }
                $embededTaxItemsTarget .= "</tr>";
                $i = 0;
                $subtotal = 0;
                foreach ($shopingCartTaxtarget as $shopingCartTaxtarget_0) {
                    foreach ($shopingCartTaxtarget_0 as $shopingCartTaxtarget_0_1) {
                        $i++;
                        $embededTaxItemsTarget .= "<tr>";
                        $embededTaxItemsTarget .= "<td line='" . __LINE__ . "' >$i</td>";
                        foreach ($shopingCartEmbededTaxItem["fieldEmbeded"]["itemtarget"]["fields"] as $k_tax_i => $k_label_i) {
                            $values = $shopingCartTaxtarget_0_1[$k_tax_i];
                            if (is_numeric($values)) {
                                $values = number_format($values, 0);
                                $class = "text-right";
                            }
                            else {
                                $class = "";
                            }
                            $embededTaxItemsTarget .= "<td class='$class'>$values</td>";
                        }
                        $embededTaxItemsTarget .= "</tr>";
                        $subtotal += $shopingCartTaxtarget_0_1["sisa"];
                    }
                }
                $embededTaxItemsTarget .= "<tr>";
                $embededTaxItemsTarget .= "<td line='" . __LINE__ . "' colspan='7' class='text-right text bold'>PPN KELUARAN</td>";
                $embededTaxItemsTarget .= "<td line='" . __LINE__ . "' class='text-right text-bold'>" . number_format($subtotal, 0) . "</td>";
                $embededTaxItemsTarget .= "</tr>";
            }
            $embededTaxItemsTarget .= "</table>";
            $embededTaxItemsTarget .= "</div>";
        }
        //-------------------------------end handling pajak------------------------------------

        $tableBawah = "";

        /*=============== items ==================*/
        $viewItems = "";
        $viewItems .= "<table ids='table_apakah' class='table table-condensed no-padding table-bordered no-margin'>";
        /*===============header shoping cart======================*/
        if (isset($itemLabels)) {
            if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                $viewItems .= "<tr class='bg-grey-2 text-uppercase'>";
                $viewItems .= "<th line='" . __LINE__ . "' style='width:1%;' class='text-muted text-center'>";
                $viewItems .= "NO";
                $viewItems .= "</th>";
                foreach ($itemLabels as $key => $label) {
                    $viewItems .= "<th line='" . __LINE__ . "' style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                    $viewItems .= $label;
                    $viewItems .= "</th>";
                }
                //----------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    $viewItems .= "<th line='" . __LINE__ . "' style='width:1%;' class='text-muted text-center'>";
                    $viewItems .= "V";
                    $viewItems .= "</th>";
                }
                //----------
                if (!$avoidRemove) {
                    $viewItems .= "<th line='" . __LINE__ . "' style='width:1%;' class='text-muted text-center'>";
                    $viewItems .= "x";
                    $viewItems .= "</th>";
                }
                $viewItems .= "</tr>";
            }
        }
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
                //------
                if (isset($iSpec['background_pembayaran'])) {
                    $bgColor = $iSpec['background_pembayaran'];
                }
                //------
                $viewItems .= "<tr line='" . __LINE__ . "' id='tr_" . $iSpec['id'] . "' bgcolor='$bgColor'>";
                $viewItems .= "<td line='" . __LINE__ . "' style='vertical-align:middle; width:1%' class='text-center'>";
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
                        if (in_array($key, $inputOptions)) {
                            cekHijau("ada dalam input option $key");
                        }
                    }
                    else {
                        $readOnly[$key] = "readonly_xxz";
                        $listMode[$key] = "text";
                    }
                    $colID[$key] = $key . "_" . $no;
                    if ($listMode[$key] == "input") {
                        if (isset($shoppingCartEditableFieldsType[$key])) {
                            $queryParams .= "&$key='+(document.getElementById('" . $colID[$key] . "').value)+'";
                        }
                        else {
                            $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                        }
                    }
                    if (array_key_exists($key, $inputOptions)) {
                        //syarat
                        $validToShow = 0;
                        if (isset($inputOptions[$key]['validate']) && count($inputOptions[$key]['validate']) > 0) {
                            $vKey = $inputOptions[$key]['validate']['key'];
                            $vVal = $inputOptions[$key]['validate']['value'];
                            $vGate = $inputOptions[$key]['validate']['valuegate'];
                            $valuegate = $$vGate;
                            if (isset($valuegate[$vKey]) && $valuegate[$vKey] == $vVal) {
                                $validToShow = 1;
                            }
                        }
                        if ($validToShow) {
                            $listMode[$key] = $inputOptions[$key]['inputType'];
                            $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                        }
                        else {
                            //dipaksa text
                            $listMode[$key] = "text";
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
                    $viewItems .= "<td line='" . __LINE__ . "' align='left'>";
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
                                $fieldVal = isset($iSpec[$key]) ? $iSpec[$key] + 0 : "";
                            }
                            else {
                                $fieldVal = isset($iSpec[$key]) ? $iSpec[$key] : "";
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
                            if (isset($shoppingCartEditableFieldsType[$key])) {
                                $tipe_input = $shoppingCartEditableFieldsType[$key];
                                $max = "";
                                if ($tipe_input == "date") {
                                    $max = "max='" . dtimeNow("Y-m-d") . "'";
                                }
                                $niceValue = $fieldVal;
                                $viewItems .= "<input type='$tipe_input' $max min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID class='form-control text-right' style='color:$color;' value='" . $niceValue . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                                $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                                $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";
                                $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\"  ";
                                $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";
                                if (isset($unionSelectors['base'])) {
                                    if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                        $viewItems .= str_replace("this", $baseInputName, $pemicuGerbang);
                                    }
                                    else {
                                        if (in_array($key, $unionSelectors['members'])) {
                                            //==jadi member union, tidak memicu perubahan gerbang
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
                                    $viewItems .= "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';\"";
                                }
                                else {
                                    $viewItems .= "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 200, this );\"";
                                }
                                $viewItems .= ">";
                            }
                            else {
                                $tipe_input = "text";
                                $niceValue = niceDecimal($fieldVal);
                                $viewItems .= "<input type='$tipe_input'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . $niceValue . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
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
                                        if (in_array($key, $unionSelectors['members'])) {
                                            //==jadi member union, tidak memicu perubahan gerbang
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
                                    $viewItems .= "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 200, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                                }
                                $viewItems .= ">";
                            }
                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                $viewItems .= "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    $viewItems .= "<span sini#1 keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                                else {
                                    $viewItems .= "<span sini#3 keyid=$keyID noid=$noID id=$colID disabled class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                            }
                            break;
                        case "combo":
                            $extern2_nama = formatField($key, $fieldVal);
                            $arrExtn2 = array(
                                1 => "dipotong",
                                2 => "tidak dipotong",
                            );
                            //BELUM ADA EFEK APA2 TEMBAK DULU
                            $shows = "<select data-id=$iID data-style='btn btn-md btn-warning' data-placeholder='pilih' data-live-search='false' data-headers='--silahkan pilih--' data-size='15' data-container='.modal' class='selectpicker combo_select form-controlx btn-block select2 show-tick'>";
                            foreach ($arrExtn2 as $kk => $pval) {
                                $selected = strtoupper($fieldVal) == strtoupper($pval) ? "selected" : "";
                                $shows .= "<option $selected value='$kk'>" . strtoupper($pval) . "</option>";
                            }
                            $shows .= "</select>";
                            $viewItems .= "<span sini#1 keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . $shows . "</span>";
                            break;
                    }
                    $viewItems .= "</td>";
                }

                //-----------------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    if (isset($iSpec['ceklist_opname']) && ($iSpec['ceklist_opname'] == 1)) {
                        $ceklist_checked = "checked";
                    }
                    else {
                        $ceklist_checked = "";
                    }
                    $viewItems .= "<td line='" . __LINE__ . "' width='1%'>";
                    $viewItems .= "<input type='checkbox' $ceklist_checked
                        onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
                    $viewItems .= "</td>";
                }
                //-----------------
                //region remover per row
                if (!$avoidRemove) {
                    $viewItems .= "<td line='" . __LINE__ . "' width='1%'>";
                    $viewItems .= "<a class='text-black btn btn-warning btn-sm' title='remove this item' data-toggle='tooltip' data-placement='left'
                    onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\">
                    <span class='glyphicon glyphicon-remove'></span>
                    </a>";
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
                $colTambahan01 = 0;
                if ($noteEnabled == true) {
                    $colspan2 = $imageEnable == true ? 1 : -1;
                    $colspan = sizeof($itemLabels) - $colspan2;
                    $viewItems .= "<tr>";
                    $viewItems .= "<td line='" . __LINE__ . "' >&nbsp;</td>";
                    $viewItems .= "<td line='" . __LINE__ . "' colspan='" . $colspan . "'>";
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
                        $viewItems .= "<td line='" . __LINE__ . "' colspan='2'>";
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
                    $viewItems .= "<td line='" . __LINE__ . "' >&nbsp;</td>";
                    $c_itemLabels = sizeof($itemLabels);
                    $c_pairedItemField = sizeof($pairedItemField);
                    $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
                    $viewItems .= "<td line='" . __LINE__ . "' colspan='" . $c_colspan . "'>";
                    //==pairedItems, if any
                    if (isset($selItems) && sizeof($selItems) > 0) {
                        $viewItems .= "<select title='Choose one of the following...' data-header='Ketik Nama/Kode/Folder/Barcode' data-size='10' data-container='body'
                                class='picker_$iID selectpicker form-control select2 show-tick' data-style='btn-primary' data-live-search='true' classs='form-control'
                                onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?val='+(this.value)+'&iid=$iID'\">";
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
                            $viewItems .= "<td line='" . __LINE__ . "' >";
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
                if (isset($shopingCartPairSubItemSrc) && (sizeof($shopingCartPairSubItemSrc) > 0)) {
                    $c_colspan = count($itemLabels) + 1;
                    $colTambahan01 = 1;
                    $viewItems .= "<tr>";
                    $viewItems .= "<td line='" . __LINE__ . "' >&nbsp;</td>";
                    $viewItems .= "<td line='" . __LINE__ . "' c_colspan='$c_colspan' colspan='" . $c_colspan . "'>";
                    $viewItems .= "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                    $viewItems .= "<table class='table table-condensed table-striped no-padding no-border'>";
                    $viewItems .= "<tr>";
                    $viewItems .= "<td line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center'>";
                    $viewItems .= "No";
                    $viewItems .= "</td>";
                    foreach ($shopingCartPairSubItemSrc as $key => $label) {
                        $viewItems .= "<td line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center text-capitalize'>";
                        $viewItems .= $label;
                        $viewItems .= "</td>";
                    }
                    $viewItems .= "</tr>";
                    $totalBawah = array();
                    $no = 0;
                    foreach ($shopingCartPairSubItemSrcData[$iID] as $iSubSpec) {
                        $no++;
                        $viewItems .= "<td line='" . __LINE__ . "' >$no</td>";
                        foreach ($shopingCartPairSubItemSrc as $key => $label) {
                            $viewItems .= "<td>";
                            $viewItems .= formatField_he_format($key, $iSubSpec[$key]);
                            $viewItems .= "</td>";
                            if (is_numeric($iSubSpec[$key])) {
                                if (!isset($totalBawah[$key])) {
                                    $totalBawah[$key] = 0;
                                }
                                $totalBawah[$key] += $iSubSpec[$key];
                            }
                        }
                    }
                    $viewItems .= "<tr>";
                    $viewItems .= "<td line='" . __LINE__ . "' ></td>";
                    foreach ($shopingCartPairSubItemSrc as $key => $label) {
                        $viewItems .= "<td line='" . __LINE__ . "' >";
                        $viewItems .= formatField_he_format($key, $totalBawah[$key]);
                        $viewItems .= "</td>";
                    }
                    $viewItems .= "</tr>";
                    $viewItems .= "</table class='table table-condensed table-striped no-padding no-border'>";
                    $viewItems .= "</div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                    $viewItems .= "</td>";
                    $viewItems .= "</tr>";
                }
            }
        }
        echo $viewItems;
        /*=============== items ==================*/

        /*=============== items2 ==================*/
        //region items2, kalau salah satunya untuk produksi dan konversi
        $viewItems2 = "";
        if (isset($items2) && sizeof($items2) > 0) {
            if ($shoppingCartAdvanceItems == true) {
                $viewItems2 .= "<tr items2 class='bg-info'>";
                $viewItems2 .= "<td items2 line='" . __LINE__ . "' colspan='$jmlKolomHeader'>";
                $viewItems2 .= "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                $viewItems2 .= "<table items2 class='table table-condensed table-striped no-padding no-border'>";
                $no = 0;
                //region body table anakan
                $kurangStoks = array();
                foreach ($items2 as $iSpec) {
                    $trid = $main['refID'];
                    $iID = $iSpec['id'];
                    $sub_key = $iSpec['pph'];
                    $itemLabels2 = isset($shoppingCartAdvanceItemsLabel[$iID]) ? $shoppingCartAdvanceItemsLabel[$iID] : array();
                    $subItemLabels = isset($shoppingCartAdvanceSubItemsLabel[$iID]) ? $shoppingCartAdvanceSubItemsLabel[$iID] : array();
                    $editableField = isset($shoppingCartAdvanceEditableField[$iID]) ? $shoppingCartAdvanceEditableField[$iID] : array();
                    $advanceNumType = isset($shoppingCartAdvanceNumType[$iID]) ? $shoppingCartAdvanceNumType[$iID] : array();
                    $subAdvanceItems2 = isset($subAdvanceItems[$iID]) ? $subAdvanceItems[$iID] : array();// ini items2
                    //arrPrintWebs($subAdvanceItems2);
                    if (sizeof($itemLabels2) && (is_array($itemLabels2))) {
                        //region header table anakan
                        $viewItems2 .= "<tr>";
                        $viewItems2 .= "<td line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center'>";
                        $viewItems2 .= "No";
                        $viewItems2 .= "</td>";
                        foreach ($itemLabels2 as $key => $label) {
                            $viewItems2 .= "<td line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center text-capitalize'>";
                            $viewItems2 .= $label;
                            $viewItems2 .= "</td>";
                        }
                        $viewItems2 .= "</tr>";
                        //endregion
                    }

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
                    $viewItems2 .= "<td line='" . __LINE__ . "' width='5%'>";
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
                        $viewItems2 .= "<td line='" . __LINE__ . "' class='$cAlign'>";
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

                    //region --SUB_ITEMS-----------
                    if (sizeof($subItemLabels) > 0) {
                        if (isset($subItemLabels)) {
                            if (sizeof($subItemLabels) && (is_array($subItemLabels))) {
                                $colspan2 = ((isset($imageEnable)) && ($imageEnable == true)) ? 1 : 0;
                                $colspan = sizeof($itemLabels2);
                                $viewItems2 .= "<tr subItemLabels line='" . __LINE__ . "' >";
                                $viewItems2 .= "<td subItemLabels line='" . __LINE__ . "' >&nbsp;</td>";
                                $viewItems2 .= "<td subItemLabels line='" . __LINE__ . "' colspan='" . $colspan . "'>";
                                //region tabel SUB_ITEMS
                                $viewItems2 .= "<table line='" . __LINE__ . "' subItemLabels class='table table-condensed no-padding no-border no-margin'>";
                                //--header sub items
                                $viewItems2 .= "<tr line='" . __LINE__ . "' subItemLabels class='bg-grey-1 text-uppercase'>";
                                foreach ($subItemLabels as $key => $label) {
                                    $viewItems2 .= "<th line='" . __LINE__ . "' style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                                    if (is_array($label)) {
                                        $viewItems2 .= $label["label"];
                                    }
                                    else {
                                        $viewItems2 .= $label;
                                    }
                                    $viewItems2 .= "</th>";
                                }

                                $viewItems2 .= "</tr>";
                                //--body sub items
                                foreach ($subAdvanceItems2 as $subNo => $iiSpec) {
                                    $viewItems2 .= "<tr class='bbg-grey-2 text-uppercase'>";
                                    foreach ($subItemLabels as $key => $label) {
                                        $subValue = isset($iiSpec[$key]) ? $iiSpec[$key] : "";
                                        if (in_array($key, $editableField)) {
                                            $disabled = "";
                                        }
                                        else {
                                            $disabled = "disabled";
                                        }
                                        if (array_key_exists($key, $advanceNumType)) {
                                            $input_type = "number";
                                            $text_align = "text-right";
                                            $thisValue = "?trid=$trid&val='+removeCommas(this.value)";
                                            $thisChecked = "?trid=$trid&state='+this.checked";
                                            if ($subValue == NULL) {
                                                $subValue = 0;
                                            }
                                        }
                                        else {
                                            $input_type = "text";
                                            $text_align = "text-left";
                                            $thisValue = "?trid=$trid&val='+encodeURIComponent(this.value)";
                                            $thisChecked = "?trid=$trid&state='+this.checked";
                                        }
                                        $subcolID = $key . "_" . $iID . "_" . $subNo;
                                        $linkSubEditable = $shoppingCartAdvanceItemsSelector . "/$iID/$key/$subNo";
                                        $viewItems2 .= "<td line='" . __LINE__ . "' $key style='width:1%;white-space: nowrap;' class='text-muted'>";
                                        if (in_array($key, $editableField)) {
                                            $viewItems2 .= "<div class=\"funkyradio-success\">";
                                            $viewItems2 .= "<div class=\"input-group border-cekx\">";
                                            $viewItems2 .= "<input type='text' id='$subcolID' harga='" . $iiSpec['harga'] . "' class='$key form-control bg-olive $text_align' $disabled value='" . number_format($subValue) . "'";
                                            $viewItems2 .= " onclick='this.select()' ";
                                            $viewItems2 .= " onblur=\"document.getElementById('result').src='" . $linkSubEditable . "$thisValue+'&keystate=$key'\"";
                                            $viewItems2 .= " >";
                                            if (isset($shoppingCartAdvanceSubEditableAdditional[$key])) {
                                                $form_tipe = $shoppingCartAdvanceSubEditableAdditional[$key]["tipe"];
                                                $form_link = $shoppingCartAdvanceSubEditableAdditionalSelector . "/$iID/$key/$subNo";
                                                $checked = (isset($iiSpec[$key . '_checklist']) && ($iiSpec[$key . '_checklist'] == 1)) ? "checked" : "";
                                                $viewItems2 .= "<span class=\"input-group-addon\">";
                                                $viewItems2 .= "<input name='$key' $checked id='$key' class='bg-olive' onclick=\"document.getElementById('result').src='" . $form_link . "$thisChecked\" type='$form_tipe'>";
                                                $viewItems2 .= "</span class=\"input-group-addon\">";
                                            }
                                            $viewItems2 .= "</div class=\"input-group border-cekx\">";
                                            $viewItems2 .= "</div class=\"funkyradio-success\">";
                                        }
                                        else {
                                            $viewItems2 .= formatField_he_format($key, $subValue, "", "");
                                        }
                                        $viewItems2 .= "</td>";
                                    }
                                    $viewItems2 .= "</tr>";
                                }

                                $viewItems2 .= "</table class='table table-condensed no-padding table-bordered no-margin'>";

                                $viewItems2 .= "</td>";
                                if (isset($imageEnable) && ($imageEnable == true)) {
                                    $viewItems2 .= "<td line='" . __LINE__ . "' colspan='2'>";
                                    $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    if (isset($imageType)) {
                                        switch ($imageType) {
                                            case "images":
                                                $file_e = "";
                                                $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                                $file_e = urlencode($file);
                                                $viewItems2 .= "<div class='input-groups'>";
                                                if (strlen($imageVal) > 0) {
                                                    $modals = array(
                                                        "title" => "Attachment " . $iSpec['nama'],
                                                        "body" => array($file),
                                                    );
                                                    $modal_e = urlencode(blobEncode($modals));
                                                    $modal_l = base_url() . "Katalog/modal/$modal_e";
                                                    $viewItems2 .= "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                                    $viewItems2 .= "<input type='hidden' name='img_$iID' value='$file'>";
                                                }
                                                $viewItems2 .= "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";
                                                $viewItems2 .= "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";
                                                $viewItems2 .= "</form>";
                                                $viewItems2 .= "</div>";
                                                break;
                                            case "text":
                                            default:
                                                $viewItems2 .= "<input type=text class='form-control' value='$noteVal'
                                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                                >";
                                                break;
                                        }
                                    }
                                    $viewItems2 .= "</td>";
                                }
                                $viewItems2 .= "</tr>";
                            }
                        }
                    }
                    //endregion --SUB_ITEMS-----------
                }
                //endregion
                $viewItems2 .= "</table>";
                $viewItems2 .= "</div>"; // anakan table
                $viewItems2 .= "</td>";
                $viewItems2 .= "</tr>";
            }
            else {

                if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                    $viewItems2 .= "<tr class='bg-info'>";
                    $viewItems2 .= "<td colspan='" . (($jmlKolomHeader * 1) - 1) . "'>";
                    $viewItems2 .= "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                    $viewItems2 .= "<table line='" . __LINE__ . "' mode_bukan_shoppingCartAdvanceItems class='table table-condensed table-striped no-padding no-border'>";
                    $viewItems2 .= "<tr>";
                    $viewItems2 .= "<td line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center'>";
                    $viewItems2 .= "No";
                    $viewItems2 .= "</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $viewItems2 .= "<td line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center text-capitalize'>";
                        $viewItems2 .= $label;
                        $viewItems2 .= "</td>";
                    }
                    $viewItems2 .= "</tr>";

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
                        $viewItems2 .= "<td line='" . __LINE__ . "' width='5%'>";
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
                            $viewItems2 .= "<td line='" . __LINE__ . "' class='$cAlign'>";
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
                    //endregion
                    $viewItems2 .= "</table>";
                    $viewItems2 .= "</div>"; // anakan table
                    $viewItems2 .= "</td>";
                    $viewItems2 .= "</tr>";
                }
            }
        }
        echo $viewItems2;
        /*=============== items2 ==================*/

        $colDiv = 0;
        foreach ($addRowLabels as $k => $label) {
            if (isset($receiptAddDpp[$k]) && count($receiptAddDpp[$k]) > 0) {
                $colDiv++;
            }
        }

        /*=============================sumrows============================*/
        $viewSumRow = "";
        $viewSumRow .= "";
        if (isset($sumRows) && sizeof($sumRows) > 0) {
            $nr = 0;
            $ttAddRow = 0;
            foreach ($addRowLabels as $k => $label) {
                if (isset($addRowHiddens[$k]) && !$addRowHiddens[$k]) {
                    $ttAddRow++;
                }
            }
            foreach ($sumRows as $key => $label) {
                $ttAddRow++;
            }
            foreach ($sumRows as $key => $label) {
                $val = 0;
                $colspan_row = sizeof($itemLabels);
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
                $customeElement = "";
                $customeFixedNote = "";
                if ($showItems) {
                    $viewSumRow .= "<div sumRows class='row' style='padding: 6px;'>";

                    $viewSumRow .= "<div sumRows class='col-md-7 col-lg-7'>";
                    $viewSumRow .= "<div sumRows line='" . __LINE__ . "'  colspan='" . $colspanDiv . "' class='text-right $bottom_borderless valign-t text-uppercase'>$label</div>";
                    $viewSumRow .= "</div>";

                    $viewSumRow .= "<div sumRows class='col-md-5 col-lg-5 no-padding'>";
                    $viewSumRow .= "<div sumRows line='" . __LINE__ . "' colspan_shopingCartPairSubItemSrc_num='$shopingCartPairSubItemSrc_num' colspan='$shopingCartPairSubItemSrc_num' class='right-borderlesss'>";
                    $viewSumRow .= "<input type='text' id='$key' class='form-control text-right' readonly value='" . niceDecimal($val) . "' >";
                    $viewSumRow .= "</div>";

                    $viewSumRow .= "</div>";
                    $viewSumRow .= "</div>";
                }
            }
        }
//        echo $viewSumRow;
        /*=============================sumrows============================*/

        /*=============================sumRows2============================*/
        $viewSumRow2 = "";
        $viewSumRow2 .= "";
        if (isset($sumRows2) && sizeof($sumRows2) > 0) {
            $viewSumRow2 .= "<div bgcolor='#e0e0e0'>";
            $viewSumRow2 .= "<div sumRows2 line='" . __LINE__ . "' colspan='" . (sizeof($itemLabels2) + 1) . "' class='text-left text-muted'><span class='fa fa-cog'></span> additional fees</div>";
            $viewSumRow2 .= "</div>";
            $viewSumRow2 .= "</div>";
            $nr = 0;
            foreach ($sumRows2 as $key => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows2) ? "bottom-borderless" : "";
                $viewSumRow2 .= "<div bgcolor='#f0f0f5'>";

                $viewSumRow2 .= "<div line='" . __LINE__ . "' colspan='" . sizeof($itemLabels) . "' class='text-right bottom-borderless valign-t text-uppercase col-md-7 col-lg-7'>$label</div>";

                $viewSumRow2 .= "<div class='col-md-5 col-lg-5 no-padding'>";
                $viewSumRow2 .= $sumSpec2[$key];
                $viewSumRow2 .= "</div>";

                $viewSumRow2 .= "</div>";
            }
        }
//        echo $viewSumRow2;
        /*=============================sumRows2============================*/

        /*=============================addRows============================*/
        $viewAddRows = "";
        $viewAddRows .= "";
        if (sizeof($addRows) > 0) {
            $nr = 0;

//            arrPrint($addRowLabels);
            foreach ($addRowLabels as $k => $label) {
                $colspan_row = sizeof($itemLabels);
                /* --------------------------------------------------------------
                 * replacerLabel
                 * --------------------------------------------------------------*/
                $label_f = str_replace("{ppnFactor}", $ppnFactor, $label);
                // --------------------------------------------------------------
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                $addRowBackground = isset($addBackground[$k]) ? $addBackground[$k] : "";

                $viewAddRows .= "<div addRows line='" . __LINE__ . "' class='row table-bordered $rowHide $addRowBackground' style='padding: 6px'>";
//                $viewAddRows .= "<div class='container-fluid'>";

                $viewAddRows .= "<div class='col-md-7 col-lg-7'>";

                if (isset($receiptAddDpp[$k]) && sizeof($receiptAddDpp[$k]) > 0) {
                    $colspan_row_0 = sizeof($itemLabels);
                    $colspan_row = 1;
                    $colspan_row_lbl = $colspan_row_0 - 4;
                    foreach ($receiptAddDpp[$k] as $key_dpp => $alias_dpp_key) {
                        $value_dpp = number_format($main[$key_dpp]);
                        $numkonLabel = $colDiv * 1 > 0 ? 1 : $colspan_row_lbl;

                        $viewAddRows .= "<div class='col-md-8 col-lg-8 no-padding'>";
                        $viewAddRows .= "<div line='" . __LINE__ . "' class=''>$alias_dpp_key</div>";
                        $viewAddRows .= "<div line='" . __LINE__ . "' class=''>$value_dpp</div>";
                        $viewAddRows .= "</div>";

                        $viewAddRows .= "<div class='col-md-4 col-lg-4 no-padding'>";
                        $viewAddRows .= "<div line='" . __LINE__ . "' id='label_$k' class='text-right'>$label_f</div>";
                        $viewAddRows .= "</div>";
                    }
                }
                else {
                    $viewAddRows .= "<div line='" . __LINE__ . "' id='label_$k' class='text-right'>$label_f</div>";

                }


                $viewAddRows .= "</div>";

                $viewAddRows .= "<div line='" . __LINE__ . "' colspan='' class='text-right col-md-5 col-lg-5 no-padding'>";
                $viewAddRows .= $addRows[$k];
                $viewAddRows .= "</div>";

//                $viewAddRows .= "</div>";
                $viewAddRows .= "</div>";
            }

            $harga_x = isset($main['harga_x']) ? $main['harga_x'] : 0;
            $is_cash_account = isset($main['cash_account']) ? 1 : 0;
            $cash_account = isset($main['cash_account']) ? $main['cash_account'] : 0;
            $is_uang_muka = isset($main['uangMukaPpn']) ? 1 : 0;
            $uang_muka_tersedia = isset($main['uangMukaPpn__debet']) ? $main['uangMukaPpn__debet'] : 0;

            echo "<script>
                        if( $('.dpp_ppn_rupiah').length ){
                            $('.dpp_ppn_rupiah').on('keyup', delay_v2( function(){
                                var curVal = removeCommas($(this).val())*1;
                                var harga = $(this).attr('harga')*1;
                                var persen = (curVal/harga*100).toFixed(2);
                                if(curVal>harga){
                                    $('.dpp_ppn_persen').val(0);
                                    $(this).val(0);
                                    return;
                                }
                                $('.dpp_ppn_persen').val(addCommas(persen));
                                $(this).val(addCommas(curVal));
                            }, 500));
                        }

                        if( $('.dpp_ppn_persen').length ){
                            $('.dpp_ppn_persen').on('keyup', delay_v2( function(){
                                var curVal = removeCommas($(this).val())*1;
                                var harga = $(this).attr('harga')*1;
                                var rupiah = (curVal*harga/100).toFixed(0);
                                if(curVal>100){
                                    $('.dpp_ppn_rupiah').val(addCommas(harga));
                                    $(this).val(100);
                                    return;
                                }
                                $('.dpp_ppn_rupiah').val(addCommas(rupiah));
                                $(this).val(addCommas(curVal));
                            }, 500));
                        }

                        if( $('.dpp_pph_rupiah').length ){
                            $('.dpp_pph_rupiah').on('keyup', delay_v2( function(){
                                var curVal = removeCommas($(this).val())*1;
                                var harga = $(this).attr('harga')*1;
                                var persen = (curVal/harga*100).toFixed(2);
                                if(curVal>harga){
                                    $('.dpp_pph_persen').val(100);
                                    $(this).val(addCommas(harga));
                                    return;
                                }
                                $('.dpp_pph_persen').val(addCommas(persen));
                                $(this).val(addCommas(curVal));
                            }, 500));
                        }

                        if( $('.dpp_pph_persen').length ){
                            $('.dpp_pph_persen').on('keyup', delay_v2( function(){
                                var curVal = removeCommas($(this).val())*1;
                                var harga = $(this).attr('harga')*1;
                                var rupiah = (curVal*harga/100).toFixed(0);
                                if(curVal>100 || curVal < 10){
                                    $('.dpp_pph_rupiah').val(addCommas(harga));
                                    $(this).val(100);
                                    return;
                                }
                                $('.dpp_pph_rupiah').val(addCommas(rupiah));
                                $(this).val(addCommas(curVal));
                            }, 500));
                        }

                        var harga_x = $harga_x;
                        var isUm = $is_uang_muka;
                        var isCa = $is_cash_account;
                        var cash_account = $cash_account;
                        var nilai_round = $('#nilai_round').val();
                        var tagihan_bayar = $('#tagihan_bayar').val();
                        var nilai_entry = removeCommas($('#nilai_entry').val());
                        var uang_muka = removeCommas($('#uang_muka_dipakai_ppn').val());
                        var uang_muka_tersedia = $uang_muka_tersedia;

                        if(harga_x == 0){
                            var nilai_max = removeCommas(nilai_round);
                        }
                        else {
                            var nilai_max = removeCommas(harga_x);
                        }

            </script>";

            echo "<script>
                    function labelMencolok(key) {
                        var saldotext = $('#saldo_' + key).text();
                        var num_saldotext = Number(saldotext.replace(/\./g, ''));
                        if(num_saldotext > 0){
                            $('#label_' + key).addClass('text-red text-bold');
                        }
                    }
                    var labelKeis = ['credit_note_diskon', 'credit_note_dipakai', 'uang_muka_nonrelasi_dipakai','uang_muka_dipakai', 'uang_muka_dipakai_ppn'];
                    labelKeis.forEach(function(item) {
                        labelMencolok(item);
                    });
                    labelKeis.forEach(function(item) {
                        $('#' + item).on('blur', function() {
                            let ketikan = $('#'+ item).val();
                            let saldotext = $('#saldo_' + item).text();
                            let num_saldotext = Number(saldotext.replace(/\./g, ''));
                            if(ketikan > num_saldotext){
                                swal({
                                    title: 'peringatan.. !!',
                                    html: 'maximal diskon ' + addCommas(max_diskon_nilai) + ', sekarang ' + addCommas(diskon_nilai) + ' dari ' + addCommas(dpp)
                                });
                            }
                        });
                    });
                </script>";
        }
//        echo $viewAddRows;
        /*=============================addRows============================*/

        //REGION BAWAH ITEMS HARUSNYA ADA 1 TR UNTUK WADAH
        $colSpanWadah = count($itemLabels) + 1 + $colTambahan01; //full sepanjang kolom yang di miliki itemsLabel 1 + 1 karena nomer manual tidak mengikuti jml label
        $tableBawah .= "<tr line='" . __LINE__ . "'>";
        $tableBawah .= "<td line='" . __LINE__ . "' colspan='$colSpanWadah'>";

        $tableBawah .= "<div line='" . __LINE__ . "' class='container-fluid'>";
        $tableBawah .= "<div line='" . __LINE__ . "' class='row'>";

        //WADAH ELEMENT KIRI
        $tableBawah .= "<div class='col-md-8 col-lg-8 no-padding'>
                            <div class='container-fluid'>
                                $customeFixedNote
                                $contentElement
                            </div>
                        </div>
                    </div>
        ";

        //WADAH FORM INPUT KANAN
        $tableBawah .= "
        <div class='col-md-4 col-lg-4 no-padding'>
            <div class='container-fluid'>
                $viewSumRow
                $viewSumRow2
                $viewAddRows
            </div>
        </div>
        ";

        $tableBawah .= "</div>"; //row
        $tableBawah .= "</div>"; //container-fluid
        $tableBawah .= "</td>";
        $tableBawah .= "</tr>";
        //ENDREGION BAWAH ITEMS HARUSNYA ADA 1 TR UNTUK WADAH

        $tableBawah .= "<script>
            top.$('.combo_select').on('change', function(e){
                var values = $(this).val();
                var labels = $(this).find('option:selected').text();
                var iid = $(this).attr('data-id');
                top.$('#tray')
                .load('" . base_url() . "pembayaran/_shoppingCart/recordItemColumn/" . $jenisTr . "/extern2_id?iid='+iid+'&val='+values+'&nr')
                .load('" . base_url() . "pembayaran/_shoppingCart/recordItemColumn/" . $jenisTr . "/extern2_nama?iid='+iid+'&val='+encodeURI(labels)+'&nr')
                .load('" . base_url() . "pembayaran/_shoppingCart/recordColumn/" . $jenisTr . "/pphGateId?iid='+iid+'&val='+values+'&nr')
                .load('" . base_url() . "pembayaran/_shoppingCart/recordColumn/" . $jenisTr . "/pphGateLabel?iid='+iid+'&val='+encodeURI(labels))
            });
        </script>";

        //region items3
        $viewItems3 = "";
        $viewItems3 .= "";
        if (isset($items3) && sizeof($items3) > 0) {
            $viewItems3 .= "<tr items3 class='bg-info'>";
            $viewItems3 .= "<td items3 line='" . __LINE__ . "' colspan='$jmlKolomHeader'>";
            $viewItems3 .= "<div class='panel no-margin'>"; // anakan table
            $viewItems3 .= "<table id='items3_' class='table table-condensed table-striped no-padding no-border'>";

            if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
                //region header table anakan
                $viewItems3 .= "<tr>";
                $viewItems3 .= "<td items3 line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center'>";
                $viewItems3 .= "No";
                $viewItems3 .= "</td>";
                foreach ($itemLabels3 as $key => $label) {
                    $viewItems3 .= "<td items3 line='" . __LINE__ . "' class='text-muted bg-grey-1 text-center text-capitalize'>";
                    $viewItems3 .= $label;
                    $viewItems3 .= "</td>";
                }
                $viewItems3 .= "</tr>";
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
                $viewItems3 .= "<tr items3 line='" . __LINE__ . "' id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                $viewItems3 .= "<td items3 line='" . __LINE__ . "' width='5%'>";
                $viewItems3 .= $no;
                $viewItems3 .= ".</td>";
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
                    $viewItems3 .= "<td items3 line='" . __LINE__ . "' class='$cAlign'>";
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

                    $viewItems3 .= "<tr sumRows3 class='bg-grey-01 3'>";
                    $viewItems3 .= "<td sumRows3 line='" . __LINE__ . "' colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-t text-uppercase'>$label</td>";
                    $viewItems3 .= "<td sumRows3 line='" . __LINE__ . "' class='right-borderlesss'>";
                    $viewItems3 .= formatField($key, $val);
                    $viewItems3 .= "</td>";
                    $viewItems3 .= "</tr>";
                }
            }

            $viewItems3 .= "</table>";
            $viewItems3 .= "</div>"; // anakan table

            $viewItems3 .= "</td>";
            $viewItems3 .= "</tr>";
        }
        echo $viewItems3;
        //endregion

        $avoidRemoveAll_items = isset($avoidRemoveAll_items) ? $avoidRemoveAll_items : array();

        //region clear shoping cart
        if ((!$avoidRemove) || (!$avoidRemoveAll_items)) {
            $addColspan = (isset($checkOpname) && ($checkOpname == true)) ? 3 : 2;
            $tableBawah .= "<tr class='bg-grey-2'>";
            $tableBawah .= "<td colspan='" . (sizeof($itemLabels) + $addColspan) . "'>";

            $tableBawah .= "<span class='pull-left'>";
            $tableBawah .= "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
            $tableBawah .= "</span>";

            $tableBawah .= "</td>";
            $tableBawah .= "</tr>";
        }
        //endregion

        $tableBawah .= "</table>";
        $tableBawah .= "</div>";


        //table tutup bawah
        echo $tableBawah;


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
                            unset($shopingCartFakturParam["editableFields"]["dpp_final"]);
                        }
                        foreach ($formulirFaktur as $ctt => $fSpec) {
                            $faktur .= "<tr>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                                $labels = "";
                                $btn_formulir = "";
                                $btn_formulir_delete = "";
                                if (isset($shopingCartFakturParam["editableFields"][$fff])) {
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
                $faktur .= "<script>
                                var skip_faktur = $('#skip_faktur').prop('checked');
                                var dateFaktur = $('#dateFaktur').val();
                                var eFaktur = $('#eFaktur').val();
                                if(skip_faktur == false && dateFaktur == '' && eFaktur == '' && konfirmasi_cek == true){
                                    $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                                    $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
                                    $('#dateFaktur').css('border-color', 'red');
                                    $('#eFaktur').css('border-color', 'red');
                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                                    // swal({type: 'warning',title: 'Upss..',html: 'Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia'});
                                    konfirmasi_cek = false;
                                    $('#wr_skip_efakture').html('<r>Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia</r>');
                                }
                                if(nilai_entry > 0 && isCa == 0 && konfirmasi_cek == true){
                                    $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                                    konfirmasi_cek = false;
                                }
                                else if(nilai_entry == 0 && isCa == 0) {
                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                                }
                                $('input[name=\"cash_account\"]').change(function(){
                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                                });
                            </script>";

            }

        }
        echo $faktur;

        //rebate
        $rebate = "";
        if (count($showReabate) > 0) {
            foreach ($showReabate as $kolom => $value) {
                $rebate .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
                $rebate .= "<table class='table'>";
                $rebate .= "<tr class='bg-primary'>";
                $rebate .= "<td class='bg-primary'>$kolom</td>";
                $rebate .= "<td class='bg-primary pull-right'>$value</td>";
                $rebate .= "</tr class='bg-primary'>";
                $rebate .= "</table class='bg-primary'>";
                $rebate .= "</div>";
            }
            //            cekHitam(__LINE__);
        }
        echo $rebate;

        /* ======= FIXEDNOTE ======== */
        if ($fixedNoteBawah) {
            echo $contenFixedNote;
        }

        if (isset($shoppingCartNoFakturLabelInfo) && ($shoppingCartNoFakturLabelInfo != NULL)) {
            echo "<div class='alert alert-info' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$shoppingCartNoFakturLabelInfo</span>";
            //            if (isset($fixedNoteLink)) {
            //                foreach ($fixedNoteLink as $fixedNoteLink_spec) {
            //                    $link = isset($fixedNoteLink_spec['link']) ? $fixedNoteLink_spec['link'] : NULL;
            //                    $labels = isset($fixedNoteLink_spec['label']) ? $fixedNoteLink_spec['label'] : NULL;
            //                    echo "<span><br>- $labels</span>";
            //                    if ($link != NULL) {
            //                        echo "atau <a href=\"$link\"
            //                                target='_parent'>klik disini</a>.";
            //                    }
            //                }
            //            }
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

        /*------------element------------*/
        if ($elementBawah) {
            echo $contentElement;
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
        //--------
        if (isset($showNotes) && ($showNotes == true)) {
            echo "<br>";
            echo "<div class='box-footer bg-gray' style='margin-top:10px;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<textarea class='form-control' placeholder='description note'
                      style='font-style:italic;font-family:Monaco, Menlo, Consolas, monospace;'
                      onblur=\"document.getElementById('result').src='$column_recorder/description?val='+encodeURIComponent(this.value);\"
                      onmouseout=\"document.getElementById('result').src='$column_recorder/description?val='+encodeURIComponent(this.value);\"
                          >$default_description</textarea>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
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
        //--------

        $supplierID = $main['supplierID'];
        $currentTr = $this->jenisTr;
        //        cekHere("tipe_transaksi_sumber = $tipe_transaksi_sumber");

        echo "<script>

                function callViewLepasRelasi(){
                    showModal('" . base_url() . "pembayaran/_shoppingCart/viewLepasRelasi/489?debuger=1','LEPAS RELASI UANG MUKA DARI PO LAIN');
                }

                function getCurrentDateTime() {
                  const now = new Date();
                  const year = now.getFullYear();
                  const month = String(now.getMonth() + 1).padStart(2, '0');
                  const day = String(now.getDate()).padStart(2, '0');
                  const hours = String(now.getHours()).padStart(2, '0');
                  const minutes = String(now.getMinutes()).padStart(2, '0');
                  return `\${year}-\${month}-\${day} \${hours}:\${minutes}`;
                }

                function showSuccessAlert() {
                    const customerId = $('#customerSelect').val();
                    const itemId = $('#itemSelect').val();
                    const itemNilai = $('#formItemNilai').val();
                    const selectedNomer = $('#selectedNomer').text();
                    const selectedNilai = $('#selectedNilai').text();
                    const formItemNotes = $('#formItemNotes').val();
                    const saldo_uang_muka_nonrelasi_dipakai = $('#saldo_uang_muka_nonrelasi_dipakai').text();
                    const totalSaldoNonRelasi = (removeCommas(saldo_uang_muka_nonrelasi_dipakai)*1) + (removeCommas(selectedNilai)*1)

                    swal({
                        title: \"Transaksi Berhasil\",
                        html: \"<div>\"
                        + \"Proses pelepasan relasi titipan PO <r><b>(\"+selectedNomer+\")</b></r> telah berhasil dilakukan. (\"+getCurrentDateTime()+\")<br><br>\"
                        + \"Saldo senilai <r><b>\"+selectedNilai+\"</b></r> telah masuk ketitipan non-relasi.<br>\"
                        + \"Total saldo titipan non-relasi telah bertambah menjadi <span class='text-green'><b>\"+addCommas(totalSaldoNonRelasi)+\"</b></span><br>\"
                        + \"Sebelumnya <b><r>(\"+saldo_uang_muka_nonrelasi_dipakai+\")</r></b><br><br>\"
                        + \"Anda sekarang dapat menggunakan saldo titipan non-relasi untuk menyelesaikan pembayaran.<br><br><br>\"
                        + \"</div>\"
                        + \"<div class='slide-container'>\"
                        + \"<p>Geser slide untuk close<br> → → → → </p>\"
                        + \"<div class='slide-bar'>\"
                        + \"<div class='slide-handle'></div>\"
                        + \"</div>\"
                        + \"</div>\",
                        type: \"success\",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        width: '600px',
                    });

                  const handle = document.querySelector('.slide-handle');
                  const bar = document.querySelector('.slide-bar');
                  let isDragging = false;

                  handle.addEventListener('mousedown', () => isDragging = true);

                  document.addEventListener('mousemove', (e) => {
                    if (!isDragging) return;
                    const barRect = bar.getBoundingClientRect();
                    const maxX = barRect.width - handle.offsetWidth;
                    let x = e.clientX - barRect.left;

                    x = Math.max(0, Math.min(x, maxX));
                    handle.style.transform = `translateX(\${x}px)`;

                    if (x >= maxX - 5) {
                      isDragging = false;
                      $(\".slide-container > p\").html(\"Oke.. Tunggu Sebentar....<br><i class='fa fa-refresh fa-spin'></i>\");
                      top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/$currentTr', function(){ setTimeout( function(){ swal.close(); closeTopBootstrapModal(); }, 2000)} );
                    }
                  });

                  document.addEventListener('mouseup', () => isDragging = false);
                }

                function closeTopBootstrapModal() {
                  const close = document.querySelectorAll('.close');
                  if (close.length > 0) {
                    const topClose = close[close.length - 1];
                        $(topClose).click();
                  }
                }

                function initSaldoUM(){
                    var url = '" . base_url() . $this->modul . "/Transaksi/saldoUmTotal/$currentTr/$supplierID'
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

                top.$('.select2').selectpicker({ dropdownParent: '.modal' });

                if( $('span[keyid=qty_debet]').length > 0 ){
                    top.shoppingCardValidator()
                    //top.console.log('perlu validator shoppingcart');
                }
                else{
                    //top.console.error('tidak perlu validator shoppingcart');
                }
                var tipe_transaksi_sumber=$tipe_transaksi_sumber;
                var jenis_transaksi_sumber=$jenis_transaksi_sumber+'_';
//                var jenis_transaksi_sumber='jas';
                
                if(tipe_transaksi_sumber==1){
                    
                    $('#tagihan_bayar').prop('disabled',false)
//                    $('#nilai_entry').prop('disabled',false)
                    var tmpharus_bayar=$('#nilai_entry').val()
                    var harus_bayar=removeCommas(tmpharus_bayar)*1
                    if($transaksi_injected_entry==1){
                        $('#nilai_entry').val(harus_bayar).trigger('onblur')    
                    }
                    
                    var paymentSrc=$('.paymentSrc')
                    console.log(paymentSrc)
jQuery.each(paymentSrc,function (a,b) {
    var checked=$(b).prop('checked')
    console.log(checked)
    if(!checked){
        $(b).prop('disabled',true)
    }

})
                }
                else{
//                    console.log(jenis_transaksi_sumber)
                    switch(jenis_transaksi_sumber){
                        case '489_':
//                            console.log('underscore ',jenis_transaksi_sumber)
                                $('#nilai_entry').prop('disabled',true)
                                var paymentSrc=$('.paymentSrc')
                                jQuery.each(paymentSrc,function (a,b) {
                                $(b).prop('disabled',false)                    
//                           console.log(jenis_transaksi_sumber)
            
            // console.log($(b).parent().parent())
                                })
                                jQuery.each(paymentSrc,function (a,b) {
                                var checked=$(b).parent().parent().parent()
                                var cci=$(checked).children()[1]
                                var cdi=$('span',$(cci)).text()
                                if(cdi=='pemindahbukuan'){
                                    $(b).prop('disabled',true)
                                }
                                
                                // console.log($(b).parent().parent())
                                })
                            break;
                            default:
//                                console.log('default....')
                                    $('#nilai_entry').prop('disabled',false)
                                    var tmpharus_bayar=$('#nilai_entry').val()
                                    var harus_bayar=removeCommas(tmpharus_bayar)*1
                                    if($transaksi_injected_entry==1){
                                        $('#nilai_entry').val(harus_bayar).trigger('onblur')    
                                    }
                                break;
                       
                    }
//                    
//                    else{
//                        
//                    }
                }
                close_holdon();
                </script>";


        // tembak jenis dulu 483, maka mereplace formulir kewajiban bayar dengan sisa yang belum dibayar...
        if ($jenisTr == "483") {
            $nilai_entry = $main["tagihan_bayar_after_uang_muka_norelasi"];
            echo "<script>
                    var nilai_entri = $nilai_entry;
                    console.log('nilai_entri: ' + nilai_entri);
                    console.log('nilai_entri: ' + addCommas(Math.floor(nilai_entri)));
                $('#payment_out').val(addCommas(Math.floor(nilai_entri)));
            </script>";
        }

        //--------
        if (count($arrItemTidakDibayar) > 0) {
            echo "<script>
                if(top.document.getElementById('checkbox_payment')){
                    const checkbox = top.document.getElementById('checkbox_payment');
                    if (checkbox) {
                        checkbox.disabled = true;
                    } else {
                        console.error('Elemen dengan ID \"checkbox_payment\" tidak ditemukan.');
                    }
                }
                top.document.getElementById('btnSave').disabled=true;
            </script>";
        }
        else {
            echo "<script>
                if(top.document.getElementById('checkbox_payment')){
                    const checkbox = top.document.getElementById('checkbox_payment');
                    if (checkbox) {
                        checkbox.disabled = false;
                    } else {
                        console.error('Elemen dengan ID \"checkbox_payment false\" tidak ditemukan.');
                    }
                }
            </script>";
        }
        //--------


    }
    else {

        echo "<script>

var paymentSrc=$('.paymentSrc')
                    jQuery.each(paymentSrc,function (a,b) {
    $(b).prop('disabled',false)                    
    
    // console.log($(b).parent().parent())
                    })
close_holdon()
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
                if(top.document.getElementById('checkbox_payment')){
                    
                     const checkbox = top.document.getElementById('checkbox_payment');
                    if (checkbox) {
                        checkbox.disabled = true;
                    } else {
                        console.error('Elemen dengan ID \"checkbox_payment\" tidak ditemukan.');
                    }
                
                }
            </script>";

    }

    //    heGetTimedQuery($elementTimeStart, __LINE__);

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
