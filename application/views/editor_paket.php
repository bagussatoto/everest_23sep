<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 05/12/18
 * Time: 21:38
 */
//cekHere($mode);
switch ($mode) {
    default:
        break;
    case "edit":
        $strDock = isset($_GET['dock']) ? "&dock=$_GET[dock]" : "";
        $p = New Layout("", "", "application/template/komposisi_paket.html");
        $p->addTags(array(
            "composition" => $content,
            "btn_attr"    => $btnAttr,
            "btn_lagi"    => $btnLagi,
            "prodID"      => isset($_GET['sID']) ? $_GET['sID'] . $strDock : "0",
        ));
        $p->render();
        break;

    case "edit_paket":
        // arrPrint($anu);
        $strDock = isset($_GET['dock']) ? "&dock=$_GET[dock]" : "";

        $p = New Layout("", "", "application/template/paket_komposisi.html");
        //        $p = New Layout("", "", "application/template/transaksi.html");
        $p->addTags(array(
            "composition" => $content,
            "btn_attr"    => $btnAttr,
            "prodID"      => isset($_GET['sID']) ? $_GET['sID'] . $strDock : "0",
        ));

        $p->render();
        break;

    case "addMany":
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $p = New Layout("$title", "$subTitle", "application/template/massEditor.html");
        $p->addTags(array(
            "menu_left"        => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),

            "data_active_title"   => "You can fill in one or more rows to $title",
            "data_active_content" => $content,

            "profile_name" => $this->session->login['nama'],
            //                "link_str" => $linkStr,
            "error_msg"    => $error,
            //                "search_str" => $searchStr,
            "this_page"    => $thisPage,
            "form_target"  => $formTarget,
            "search_str"   => isset($_GET['k']) ? $_GET['k'] : "",
        ));
        //endregion

        $p->render();
        break;

    case "edit_komposit":
        // arrPrint($anu);
        $strDock = isset($_GET['dock']) ? "&dock=$_GET[dock]" : "";

        $p = New Layout("", "", "application/template/komposit_komposisi.html");
        //        $p = New Layout("", "", "application/template/transaksi.html");
        $p->addTags(array(
            "composition" => $content,
            "btn_attr"    => $btnAttr,
            "prodID"      => isset($_GET['sID']) ? $_GET['sID'] . $strDock : "0",
        ));

        $p->render();
        break;

    case "edit_satuan":
        $p = New Layout("", "", "application/template/satuan.html");
        // arrPrint($viewFields);
        // cekHEre();
        $tokoID = my_toko_id();
        $contens = "";
        //        $contens .="<div class='row'>";


        $contens .= "<table class='table table-condensed table-bordered table-hover dataTable compact'>";
        $contens .= "<caption> --------------------------- </caption>";

        $contens .= "<thead>";
        $contens .= "<tr>";
        $contens .= "<th>No</th>";
        foreach ($viewFields as $kol => $label) {
            $contens .= "<th>$label</th>";
        }
        $contens .= "<th>action</th>";
        $contens .= "</tr>";
        $contens .= "</thead>";

        $contens .= "<tbody>";
        if (sizeof($prevData) > 0) {
            $rr = 0;
            // arrPrint($prevData);
            foreach ($prevData as $data) {
                $rr++;
                $curentID = $data->id;
                $contens .= "<tr>";
                $contens .= "<td>$rr</td>";
                foreach ($viewFields as $kol => $label) {
                    // arrPrint($data);
                    if (isset($relData[$kol]["data"])) {
                        if (is_array($relData[$kol]["data"])) {
                            $colField = $relData[$kol]["keyField"];
                            $FieldValues = "";
                            $FieldValues .= "<select name='" . $colField . "_" . $rr . "' class='' onchange=\"document.getElementById('result').src='" . $editTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=$colField&value='+encodeURI(this.value);\">";
                            // $FieldValues .="<select name='tt'>";
                            foreach ($relData[$kol]["data"] as $ix => $ixData) {
                                // arrPrint($ixData);
                                foreach ($relData[$kol]["input"] as $key => $value) {
                                    // cekBiru($key." ".$value);
                                    $values = $ixData[$key];
                                    $selectedFiled = $ixData[$key] == $data->$key ? "selected" : "";
                                    $FieldValues .= "<option value='$values' $selectedFiled >" . $ixData[$value] . "</option>";
                                }
                            }
                            $FieldValues .= "</select>";
                        }
                    }
                    else {
                        $colField = $kol;
                        $FieldValues = "<input name='' class='' value='" . $data->$kol . "' onchange=\"document.getElementById('result').src='" . $editTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=$colField&value='+encodeURI(this.value);\">";
                    }
                    $contens .= "<td>$FieldValues</td>";

                }
                //region button remove

                $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $contens .= "<td >$btn</td>";
                //endregion
                $contens .= "</tr>";
            }
        }

        $contens .= "<form target='result' method='post' action='" . $formTarget . " '>";
        $contens .= "<tr>";
        $contens .= "<td>-------</td>";

        $ctrl = 0;

        foreach ($viewFields as $kol => $llabel) {
            // cekPink($kol);
            $ctrl++;
            $contens .= "<td>";

            if (isset($relData[$kol])) {
                if (isset($relData[$kol]) && is_array($relData[$kol]["data"])) {
                    $prevRelData = $relData[$kol]["data"];
                    // arrPrint($prevRelData);
                    $colFiel = $relData[$kol]["keyField"];

                    $list = "";
                    $list .= "<select name='$colFiel'>";
                    $list .= "<option name='$colFiel' selected disabled style=\"display:none\">-silahkan pilih-</option>";
                    foreach ($relData[$kol]["data"] as $ii => $data) {
                        // arrprint($data);
                        foreach ($relData[$kol]["input"] as $key => $value) {
                            // cekBiru($key);
                            $values = $data[$key];
                            $selected = $relData[$kol]["attr"];
                            $list .= "<option value='$values' $selected >" . $data[$value] . "</option>";
                        }
                    }
                    $list .= "</select>";

                    $contens .= $list;
                }

            }
            else {
                // cekHitam("33");
                $contens .= "<input type='text'  name='$kol'>";
                $contens .= "<input type='hidden'  name='produk_id' value='$selectedID'>";
                $contens .= "<input type='hidden'  name='toko_id' value='$tokoID'>";
            }

            $contens .= "</td>";

        }
        //region btn submit
        $contens .= "<td>";
        $contens .= "<button type='submit' class='btn btn-xs btn-success'>simpan";
        $contens .= "</td>";
        $contens .= "</tr>";
        $contens .= "</tbody>";

        $contens .= "</form>";
        $contens .= "</table>";

        //        $contens .="</div>";
        $scriptBottom = "<script >top.console.log('cek iframe'); $('#btnReload').on('click', function(){ }); console.log( this.window ); </script>";
        // echo $contens;


        // matiHEre(__LINE__);
        $p->addTags(array(
            "content"      => $contens,
            "scriptBottom" => $scriptBottom,
            // "btn_attr" => $btnAttr,
            // "prodID" => isset($_GET['sID']) ? $_GET['sID'].$strDock : "0",
        ));
        //
        $p->render();
        break;

    case "edit_komposisi":
        // arrPrint($currentTargetWip);
        $p = New Layout("", "", "application/template/satuan.html");
        $targetResult = isset($result) ? "&result=$result" : "&result=result";

        $strMain = "";
        //region BOM
    // arrprintWebs($relTarget);
        if (sizeof($produk_fase) > 0) {
            $bomTitle = "<div>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h3 id='untuk_reload_iframe'><u>Rencana Proses Produksi <b>( $produkNama )</b></u></h3>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }
        else {
            $bomTitle = "<div class='blink'>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h2 class='text-red text-bold'><u>Silahkan buat rencana proses produksi <b>( $produkNama )</b></u></h2>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }


        //region rencana proses produksi (nama_produk)
        $produkFase = "";
        $produkFase .= "<div class='overflow-h'>";
        $produkFase .= "<form class='form' name='produk_fase' id='produk_fase' method='post' target='result' action='$addFaseProdukLink?mode=produk_fase$targetResult'>";
        $produkFase .= "<table class='table table-bordered table-hover'>";
        $produkFase .= "<thead>";
        $produkFase .= "<tr>";
        $produkFase .= "<th>No</th>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $produkFase .= "<th>$produkfase_alias</th>";
        }
        $produkFase .= "<th>action</th>";
        $produkFase .= "</tr>";
        $produkFase .= "</thead>";
        $produkFase .= "<tbody>";
        if (isset($produk_fase) && sizeof($produk_fase)) {
            $i = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                $i++;
                $produkFase .= "<tr>";
                $produkFase .= "<td>$i</td>";
                foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                    if (isset($faseData[$produkfaseKey])) {
                        $fieldValue = $faseData[$produkfaseKey];
                    }
                    else {

                    }
                    $produkFase .= "<td>" . $faseData[$produkfaseKey] . "</td>";
                }
                //region button remove
                // $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></button>";
                // $produkFase .= "<td>$btn</td>";
                $produkFase .= "<td></td>";
                //endregion
                $produkFase .= "</tr>";
            }
            //tambahan tr untuk add data baru
        }
        $produkFase .= "<tr>";
        $produkFase .= "<td></td>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey]: "";
            $readOnly = "";
            // if($produkfaseKey=="urut"){
            //     $preval = $i+1;
            //     $readOnly = "readonly";
            // }
            $produkFase .= "<td><input $readOnly class='form-control' type='text' value='$preval' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\"></td>";
        }
        $produkFase .= "<td> <span class='btn btn-xs btn-success' onclick=\"document.getElementById('produk_fase').submit();\">tambah</span> </td>";
        $produkFase .= "</tr>";
        $produkFase .= "</tbody>";
        $produkFase .= "</table>";
        $produkFase .= "</form>";
        $produkFase .= "</div>";
        //endregion rencana proses produksi (nama_produk)


        //region komposisi produk fase
        if (sizeof($produk_fase) > 0) {
            $produkKomposisiFase = "<div class='border-ck'>";

            $produkKomposisiFase .= "<div style='margin-bottom: 10px;'>";
            $produkKomposisiFase .= "<h3 id=''><b><u>SETTING BOM SETIAP FASE</u></b></h3>";
            $produkKomposisiFase .= "</div>";

            $produkKomposisiFase .= "<div class='nav-tabs-custom'>";
            $produkKomposisiFase .= "<div class='tab-content no-padding'>";

            $produkKomposisiFase .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";

            $faseNoA=0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if(isset($produk_komposisi_fase[$fase_urut])){
                }
                else{
                    $faseNoA++;
                }
            }
            $faseNo=0;
            $faseNoErr=0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if(isset($produk_komposisi_fase[$fase_urut])){
                    $actLink = $faseNo==0 && $faseNoA == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 14px;' class=''>Fase ($fase_urut) <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNo++;
                }
                else{
                    $actLink = $faseNoErr==0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 16px;' class='text-red'><i class='fa fa-warning blink text-yellow'></i>Fase ($fase_urut) <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNoErr++;
                }
            }
            $produkKomposisiFase .= "</ul>";
            $faseNoB=0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNoB==0 && $faseNoA== 0 ? "active in" : "";
                    $produkKomposisiFase .= "<div class='uu lv1 tab-pane fade $actLink' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class=''>  <h4 class=''><i class='fa fa-hand-o-right'></i> &nbsp; &nbsp; Fase( $fase_urut )" . ($faseData['nama']) . "&nbsp;&nbsp;&nbsp;<small><i class='fa fa-clock-o text-muted'></i>&nbsp;" . date("Y-m-d H:i") . "</small></h4></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku"."$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $produkKomposisiFase .= "<tr>";
                                $i = 0;
                                if(isset($produk_komposisi_fase[$fase_urut]["produk"]) && sizeof($produk_komposisi_fase[$fase_urut]["produk"])){
                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $DataRelsupplies) {
                                        // arrPrint($DataRelsupplies);
                                        $supRelID = $DataRelsupplies["id"];
                                        $masterID = $DataRelsupplies["produk_id"];
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $btnRemoveFasekomposisi = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"$('#result').load('$removeLink" . "?mode=komposisiProduk&id=$supRelID&faseID=$fase_urut&masterID=$masterID&value='+encodeURI(this.value)+'$targetResult');\"><span class='glyphicon glyphicon-trash'></span></button>";
                                        $produkKomposisiFase .= "<td >$btnRemoveFasekomposisi</td>";
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- </option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] ==$datas['id'] ? "selected":"";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField]:"";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField]:"";
                                        $strItem =formatField($hField,$value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";


                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .="</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya"."$fase_urut";
                                $produkKomposisiFase .= "<div class='border-cek'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                            // arrprint($relBiaya);
                                $i = 0;
                                if(isset($produk_komposisi_fase[$fase_urut]["biaya"]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya"] )> 0){
                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $DataRelsuppliesBiaya) {
                                        // arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $btnRemoveFasekomposisi = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"$('#result').load('$removeLink" . "?mode=komposisiProduk&id=$supRelID&faseID=$fase_urut&masterID=$masterID&value='+encodeURI(this.value)+'$targetResult');\"><span class='glyphicon glyphicon-trash'></span></button>";
                                        $produkKomposisiFase .= "<td >$btnRemoveFasekomposisi</td>";
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem= "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ---silahkan pilih--</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] ==$datas['id'] ? "selected":"";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField]:"";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField]:"";
                                        $strItem =formatField($hField,$value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi biaya' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .="</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                $idForm ="target".$fase_urut;
                                $produkKomposisiFase .= "<div class=''>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }

                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";

                                $produkKomposisiFase .= "<tr>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relTarget[$hField])) {
                                            $strItem= "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$addFaseHasilProduksi"."/$produkID?mode=komposisi_target&key=$hField&&fase_id=$fase_urut&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ---silahkan pilih--</option>";
                                            foreach ($relTarget[$hField] as $datas) {
                                                $selected = isset($currentTargetWip[$produkID][$fase_urut][$hField]) && $currentTargetWip[$produkID][$fase_urut][$hField] == $datas['id'] ? "selected":"";
                                                // $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."||". $currentTargetWip[$produkID][$fase_urut][$hField]."</option>";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."</option>";
                                            }
                                            $strItem .= "</select>";
                                        }

                                    }

                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                    $faseNoB++;
                }
                else {
                    $produkKomposisiFase .= "<div class='bg-ble lv12 tab-pane fade active in' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class='blink text-bold text-danger'><h4 class=''>Fase( $fase_urut )" . ($faseData['nama']) . " belum diseting, silahkan klik tombol tambah</h3></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku".$fase_urut;
                                $produkKomposisiFase .= "<div class='border-cek'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                 $i = 0;
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                // arrprint($relSupplies);
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- </option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                // arrPrint($datas);
                                                // $queryParams .= "?&$hField='+removeCommas(document.getElementById('this').value)+'";
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] ==$datas['id'] ? "selected":"";
                                                $strItem .= "<option $selected value='" .$datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField]:"";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField]:"";
                                        $strItem =formatField($hField,$value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $produkKomposisiFase .= "<div class='border-cek'>";
                                $produkKomposisiFase .= "<form class='form' id='biaya' method='post' name='bahan_baku' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                $i = 0;
                                //bagian add baru
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- *</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] ==$datas['id'] ? "selected":"";

                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField]:"";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector"."$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $strItem = "";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button type='button' title='simpan komposisi baru' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                $produkKomposisiFase .= "<div class=''>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                // $produkKomposisiFase .="<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $i = 0;
                                // $produkKomposisiFase .="<td></td>";
                                $produkKomposisiFase .= "<tr>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= "<input>";
                                    $produkKomposisiFase .= "<input type='hidden' name='fase'>";
                                    $produkKomposisiFase .= "</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                }
            }
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
        }
        else {

        }
        $produkKomposisiFase .= "<div id='input_temp'></div>";

        //endregion
        $strMain = $bomTitle;
        $strMain .= $produkFase;
        $strMain .= $produkKomposisiFase;
        $scriptBottom = "<script >top.console.log('cek iframe'); $('#btnReload').on('click', function(){ }); console.log( this.window ); </script>";
        $scriptBottom .= "<script>$('#untuk_reload_iframe').off(); $('#untuk_reload_iframe').on('click', function(){ var iframe = top.document.getElementById('$result');iframe.src=iframe.src; })</script>";
        $scriptBottom .= "<script>

                $('.nav-item').on('click', function(){
                    var tabThis = $('a', $(this)).attr('id');
                    localStorage.setItem('position_tab', tabThis);
                    console.log('tabThis: ' + tabThis);
                })

                var pos = localStorage.getItem('position_tab');

                if( $('#'+pos) ){
                    $('#'+pos).click()
                }

                //region menyimpan dan restore posisi scroll (by chepy)
                var scroll_$result = localStorage.getItem('scroll_$result');
                top.$('#$result').contents().scrollTop(scroll_$result);
                top.$(top.$('#$result').contents()).on('scroll', function(){
                    localStorage.setItem('scroll_$result', $(this).scrollTop());
                });
                //endregion menyimpan dan restore posisi scroll (by chepy)

                setTimeout( function(){
                    var thisFrameHeight = top.$('#$result').contents().height();
                    top.$('#$result').height(thisFrameHeight);
                    console.log('thisFrameHeight $result: ' + thisFrameHeight);
                }, 500)

        </script>";
        $p->addTags(array(
            "content"        => $strMain,
            "scriptBottom"   => $scriptBottom,
            "display_iframe" => "none",//biar gak panjang
            // "btn_attr" => $btnAttr,
            // "prodID" => isset($_GET['sID']) ? $_GET['sID'].$strDock : "0",
        ));
        //
        $p->render();
        break;

    case "edit_teamwork":
        arrPrint($produk_komposisi);
        $p = New Layout("", "", "application/template/satuan.html");
        $targetResult = isset($result) ? "&result=$result" : "&result=result";

        $strMain = "";
        //region BOM
        // arrprintWebs($relTarget);
        if (count($produk_komposisi) > 0) {
            $bomTitle = "<div>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h3 id='untuk_reload_iframe'><u> Anggaran projek <b>( $produkNama )</b></u></h3>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }
        else {
            $bomTitle = "<div class='blink'>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h2 class='text-red text-bold'><u>**Silahkan buat rencana anggaran dan tim kerja projek<b>( $produkNama )</b></u></h2>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }


        //region rencana proses produksi (nama_produk)
        $produkFase = "";
        $produkFase .= "<div class='overflow-h'>";
        $produkFase .= "<form class='form' name='produk_fase' id='produk_fase' method='post' target='result' action='$addFaseProdukLink?mode=produk_fase$targetResult'>";
        $produkFase .= "<table class='table table-bordered table-hover'>";
        $produkFase .= "<thead>";
        $produkFase .= "<tr>";
        $produkFase .= "<th>No</th>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $produkFase .= "<th>$produkfase_alias</th>";
        }
        $produkFase .= "<th>action</th>";
        $produkFase .= "</tr>";
        $produkFase .= "</thead>";
        $produkFase .= "<tbody>";
        if (isset($produk_fase) && sizeof($produk_fase)) {
            $i = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                $i++;
                $produkFase .= "<tr>";
                $produkFase .= "<td>$i</td>";
                foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                    if (isset($faseData[$produkfaseKey])) {
                        $fieldValue = $faseData[$produkfaseKey];
                    }
                    else {

                    }
                    $produkFase .= "<td>" . $faseData[$produkfaseKey] . "</td>";
                }
                //region button remove
                // $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></button>";
                $produkFase .= "<td>$btn</td>";
                //endregion
                $produkFase .= "</tr>";
            }
            //tambahan tr untuk add data baru
        }
        $produkFase .= "<tr>";
        $produkFase .= "<td></td>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
            // $readOnly = "";
            // if ($produkfaseKey == "urut") {
            //     $preval = $i + 1;
            //     $readOnly = "readonly";
            // }
            $readOnly="";
            $produkFase .= "<td><input $readOnly class='form-control' type='text' value='$preval' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\"></td>";
        }
        $produkFase .= "<td> <span class='btn btn-xs btn-success' onclick=\"document.getElementById('produk_fase').submit();\">tambah</span> </td>";
        $produkFase .= "</tr>";
        $produkFase .= "</tbody>";
        $produkFase .= "</table>";
        $produkFase .= "</form>";
        $produkFase .= "</div>";
        //endregion rencana proses produksi (nama_produk)


        //region komposisi produk fase
        if (sizeof($produk_fase) > 0) {
            $produkKomposisiFase = "<div class='border-ck'>";

            $produkKomposisiFase .= "<div style='margin-bottom: 10px;'>";
            $produkKomposisiFase .= "<h3 id=''><b><u>SETTING BOM SETIAP FASE</u></b></h3>";
            $produkKomposisiFase .= "</div>";

            $produkKomposisiFase .= "<div class='nav-tabs-custom'>";
            $produkKomposisiFase .= "<div class='tab-content no-padding'>";

            $produkKomposisiFase .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";

            $faseNoA = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                }
                else {
                    $faseNoA++;
                }
            }
            $faseNo = 0;
            $faseNoErr = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNo == 0 && $faseNoA == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 14px;' class=''>Fase ($fase_urut) <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNo++;
                }
                else {
                    $actLink = $faseNoErr == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 16px;' class='text-red'><i class='fa fa-warning blink text-yellow'></i>Fase ($fase_urut) <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNoErr++;
                }
            }
            $produkKomposisiFase .= "</ul>";
            $faseNoB = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNoB == 0 && $faseNoA == 0 ? "active in" : "";
                    $produkKomposisiFase .= "<div class='uu lv1 tab-pane fade $actLink' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class=''>  <h4 class=''><i class='fa fa-hand-o-right'></i> &nbsp; &nbsp; Fase( $fase_urut )" . ($faseData['nama']) . "&nbsp;&nbsp;&nbsp;<small><i class='fa fa-clock-o text-muted'></i>&nbsp;" . date("Y-m-d H:i") . "</small></h4></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $produkKomposisiFase .= "<tr>";
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["produk"]) && sizeof($produk_komposisi_fase[$fase_urut]["produk"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $DataRelsupplies) {
                                        // arrPrint($DataRelsupplies);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $btnRemoveFasekomposisi = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></button>";
                                        $produkKomposisiFase .= "<td >$btnRemoveFasekomposisi</td>";
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- </option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";


                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["biaya"]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $DataRelsuppliesBiaya) {
                                        //                                        arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ---silahkan pilih--</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi biaya' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                $idForm = "target" . $fase_urut;
                                $produkKomposisiFase .= "<div class=''>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }

                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";

                                $produkKomposisiFase .= "<tr>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relTarget[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$addFaseHasilProduksi" . "/$produkID?mode=komposisi_target&key=$hField&&fase_id=$fase_urut&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ---silahkan pilih--</option>";
                                            foreach ($relTarget[$hField] as $datas) {
                                                $selected = isset($currentTargetWip[$produkID][$fase_urut][$hField]) && $currentTargetWip[$produkID][$fase_urut][$hField] == $datas['id'] ? "selected" : "";
                                                // $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."||". $currentTargetWip[$produkID][$fase_urut][$hField]."</option>";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }

                                    }

                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                    $faseNoB++;
                }
                else {
                    $produkKomposisiFase .= "<div class='bg-ble lv12 tab-pane fade active in' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class='blink text-bold text-danger'><h4 class=''>Fase( $fase_urut )" . ($faseData['nama']) . " belum diseting, silahkan klik tombol tambah</h3></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . $fase_urut;
                                $produkKomposisiFase .= "<div class='border-cek'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $i = 0;
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                // arrprint($relSupplies);
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- </option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                // arrPrint($datas);
                                                // $queryParams .= "?&$hField='+removeCommas(document.getElementById('this').value)+'";
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $produkKomposisiFase .= "<div class='border-cek'>";
                                $produkKomposisiFase .= "<form class='form' id='biaya' method='post' name='bahan_baku' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                $i = 0;
                                //bagian add baru
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- *</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] == $datas['id'] ? "selected" : "";

                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $strItem = "";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button type='button' title='simpan komposisi baru' class='btn btn-success'> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                $produkKomposisiFase .= "<div class=''>";
                                $produkKomposisiFase .= "<table class='table table-bordered'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                // $produkKomposisiFase .="<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $i = 0;
                                // $produkKomposisiFase .="<td></td>";
                                $produkKomposisiFase .= "<tr>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= "<input>";
                                    $produkKomposisiFase .= "<input type='hidden' name='fase'>";
                                    $produkKomposisiFase .= "</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                }
            }
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
        }
        else {

        }
        $produkKomposisiFase .= "<div id='input_temp'></div>";

        //endregion
        $strMain = $bomTitle;
        $strMain .= $produkFase;
        $strMain .= $produkKomposisiFase;
        $scriptBottom = "<script >top.console.log('cek iframe'); $('#btnReload').on('click', function(){ }); console.log( this.window ); </script>";
        $scriptBottom .= "<script>$('#untuk_reload_iframe').off(); $('#untuk_reload_iframe').on('click', function(){ var iframe = top.document.getElementById('$result');iframe.src=iframe.src; })</script>";
        $scriptBottom .= "<script>

                $('.nav-item').on('click', function(){
                    var tabThis = $('a', $(this)).attr('id');
                    localStorage.setItem('position_tab', tabThis);
                    console.log('tabThis: ' + tabThis);
                })

                var pos = localStorage.getItem('position_tab');

                if( $('#'+pos) ){
                    $('#'+pos).click()
                }

                //region menyimpan dan restore posisi scroll (by chepy)
                var scroll_$result = localStorage.getItem('scroll_$result');
                top.$('#$result').contents().scrollTop(scroll_$result);
                top.$(top.$('#$result').contents()).on('scroll', function(){
                    localStorage.setItem('scroll_$result', $(this).scrollTop());
                });
                //endregion menyimpan dan restore posisi scroll (by chepy)

                setTimeout( function(){
                    var thisFrameHeight = top.$('#$result').contents().height();
                    top.$('#$result').height(thisFrameHeight);
                    console.log('thisFrameHeight $result: ' + thisFrameHeight);
                }, 500)

        </script>";
        $p->addTags(array(
            "content" => $strMain,
            "scriptBottom" => $scriptBottom,
            "display_iframe" => "none",//biar gak panjang
            // "btn_attr" => $btnAttr,
            // "prodID" => isset($_GET['sID']) ? $_GET['sID'].$strDock : "0",
        ));
        //
        $p->render();
        break;

}