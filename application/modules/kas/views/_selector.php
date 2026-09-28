<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 17/09/2018
 * Time: 19.35
 */

switch ($mode) {
    case "view":

        $strContent = "";
        if (isset($items) && sizeof($items) > 0) {
            if (is_array($items) && (sizeof($items) > 0)) {
                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                if ($cCode == "_TR_461") {
                    $strContent .= "<li class='list-group-item text-center text-muted' style='background: lightgray;' >
                                    <div class='text-right'><div onclick='selectorCheckAll()' class='btn btn-xs btn-warning'>pilih semua</div></div>
                                </li>";
                }
                foreach ($items as $iSpec) {
                    if (strlen($iSpec['nama']) > 0) {
                        if (isset($iSpec['minValue'])) {
                            $defaultValue = $iSpec['minValue'];
                            $defVal = "&minValue=$defaultValue";
                        }
                        else {
                            $defVal = "";
                        }
                        $strContent .= "<li class='list-group-item'>";
                        if ($socketURL[$iSpec['id']] != "") {
                            if (sizeof($socketParams[$iSpec['id']]) > 0) {
                                foreach ($socketParams[$iSpec['id']] as $key => $src) {
                                    if (isset($iSpec[$src])) {
                                        $iVal = addslashes($iSpec[$src]);
                                    }
                                    else {
                                        $iVal = 0;
                                    }
                                    $socketURL[$iSpec['id']] = str_replace("{" . $src . "}", $iVal, $socketURL[$iSpec['id']]);
                                }
                            }
                        }
                        if (isset($socketURL[$iSpec['id']]) && strlen($socketURL[$iSpec['id']]) > 3) {
                            $actionTarget = "top.BootstrapDialog.show({
                                                title: '" . $iSpec['nama'] . "',
                                                message: " . '$' . "('<div></div>').load('" . $socketURL[$iSpec['id']] . "&url=" . blobEncode("" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal") . "'),
                                                draggable: false,                                        
                                                type: top.BootstrapDialog.TYPE_DEFAULT,
                                                size: top.BootstrapDialog.SIZE_SMALL,
                                                closable: true,
                                            });";
                            $strContent .= "<a href='javascript:void(0);' onclick=\"$actionTarget\">";
                        }
                        else {

                            $mb_js = "";
                            if (isset($_GET['mb'])) {
                                $mb_js = "setTimeout( function(){ top.load_shoppingcart() },500)";
                            }

                            switch ($cCode) {
                                case "_TR_3683":
                                    if (strlen($defVal) > 3) {
                                        $strContent .= "<a href='javascript:void(0);'
                                        onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal'\">";
                                    }
                                    else {
                                        $strContent .= "<a href='javascript:void(0);' 
                                                        onclick=\" BootstrapDialog.show({
                                                            title: 'Attention !',
                                                            message: 'Out of <b class=\'font-size-1-5 text-red\'>" . $iSpec['produk_kode'] . " " . $iSpec['nama'] . "</b> stock '
                                                        });
                                                    \">";
                                    }
                                    break;
                                case "_TR_461":
                                    $strContent .= "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?selector&id=" . $iSpec['id'] . "$defVal';$mb_js\">";
                                    break;
                                default:
                                    if (isset($iSpec['target']) && ($iSpec['target'] != NULL)) {
                                        $strContent .= "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?selector&id=" . $iSpec['id'] . "$defVal';$mb_js\">";
                                    }
                                    else {
                                        $strContent .= "<a href='javascript:void(0);' style='color:#717271;'>";
                                    }
                                    break;
                            }
                        }

                        $strContent .= isset($iSpec['nama']) ? $iSpec['nama'] : "";
                        $strContent .= "<span>" . $iSpec['label'] . "</span>";

                        if (isset($_SESSION[$cCode]['items'][$iSpec['id']])) {
                            $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'> <i class='fa fa-check-square text-success text-bold'></i> </span>";
                        }
                        else {
                            $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'></span>";
                        }

                        $strContent .= "</a>";

                        if (isset($iSpec['link_mutasi'])) {
                            $strContent .= $iSpec['link_mutasi'];
                        }
                        $strContent .= "</li>";
                    }
                }
                $strContent .= "<li class='list-group-item text-center text-muted' style='background:#e5e5c5;'><small>... type more keywords<br>for more specific results ...</small>";
                $strContent .= "</li>";
                $strContent .= "</ul class='list-group'>";
            }
            else {
                $strContent .= "<div class='form-control text-center'>";
                $strContent .= "- no matched item -";
                $strContent .= "</div>";
            }
        }
        else {
            $strContent .= "<div class='form-control text-center'>";
            $strContent .= "- no matched item -";
            $strContent .= "</div>";
        }

        if ($cCode == "_TR_461") {
            $strContent .= "
            <script>
                var arrItemCheck = $('.itemCheck')
                var arrChecked = {};
                if(countObj(arrItemCheck)>0){
                    jQuery.each(arrItemCheck, function(i, b){
                        var pid = $(b).attr('pid');
                        if(arrChecked[pid]==undefined){
                            arrChecked[pid] = {}
                        }
                        arrChecked[pid] = 0
                    })
                    localStorage.sesCartBahan = JSON.stringify(arrChecked)
                }
                function selectorCheckAll(){
                    var sesCartBahan = localStorage.sesCartBahan != undefined ? JSON.parse(localStorage.sesCartBahan) : 0
                    var baseUrl = $('#baseURL').html()
                    if(sesCartBahan!=0&&countObj(sesCartBahan)>0){
                        $.ajax({
                            type: 'POST',
                            url: baseUrl + 'Selectors/_processSelectSupplies/gerbangBi/461?items=',
                            data: {items: JSON.stringify(sesCartBahan)},
                            success: function(data) {
                                $.ajax({
                                    type: 'GET',
                                    async: false,
                                    url: baseUrl + 'ValueGate/buildValues/461',
                                    success: function(data) {
                                        if($('#shopping_cart').length>0){
                                            $('#shopping_cart').load(baseUrl + '_shoppingCart/viewCart/461')
                                            $('input[name=text]').trigger('click')
                                        }
                                        else{
                                            top.load_shoppingcart();
                                            $('input[name=text]').trigger('click')
                                        }
                                        
                                    }
                                })
                            }
                        })
                    }
                    else{
                        swal('silahkan pilih bahan yang akan diinput stok / dibeli')
                    }
                }
            </script>
            ";
        }


        echo $strContent;
        break;
    case "viewUm":
        $strContent = "";
        if (isset($items) && is_array($items) && sizeof($items) > 0) {
            // --- group by tab ---
            $groups   = array();
            $tabOrder = array();
            foreach ($items as $it) {
                $tab = (isset($it['tab']) && strlen($it['tab'])) ? $it['tab'] : 'lainnya';
                if (!isset($groups[$tab])) {
                    $groups[$tab] = array();
                    $tabOrder[]   = $tab; // simpan urutan kemunculan
                }
                $groups[$tab][] = $it;
            }

            // --- NAV TABS ---
            $strContent .= "<div style='padding: 6px;' class='text-center bg-warning text-green text-bold'><i>PILIH SUMBER YANG AKAN DIPINDAH</i></div>";

            $strContent .= "<ul class='nav nav-tabs' role='tablist'>";
            $isFirst = true;
            foreach ($tabOrder as $tabName) {
                $safeId  = preg_replace('/[^a-z0-9_]+/i', '-', $tabName);
                $cnt     = count($groups[$tabName]);
                $active  = $isFirst ? " class='active'" : "";
                $title   = strtoupper($tabName);
                $strContent .= "<li$active><a href='#tab-{$safeId}' role='tab' data-toggle='tab'>{$title} <span class='badge'>{$cnt}</span></a></li>";
                $isFirst = false;
            }
            $strContent .= "</ul>";

            // --- TAB PANES ---
            $strContent .= "<div class='tab-content' style='margin-top:8px;'>";
            $isFirstPane = true;
            foreach ($tabOrder as $tabName) {
                $safeId = preg_replace('/[^a-z0-9_]+/i', '-', $tabName);
                $activePane = $isFirstPane ? " active in" : "";
                $strContent .= "<div class='tab-pane fade{$activePane}' id='tab-{$safeId}'>";

                // isi tiap tab: list-group item seperti logic lama
                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                foreach ($groups[$tabName] as $iSpec) {
                    if (!isset($iSpec['nama']) || strlen($iSpec['nama']) == 0) {
                        continue;
                    }

                    // minValue → querystring
                    if (isset($iSpec['minValue'])) {
                        $defaultValue = $iSpec['minValue'];
                        $defVal = "&minValue=$defaultValue";
                    } else {
                        $defVal = "";
                    }

                    $strContent .= "<li class='list-group-item'>";

                    // Socket URL param substitution
                    if (isset($iSpec['id']) && isset($socketURL[$iSpec['id']]) && $socketURL[$iSpec['id']] != "") {
                        if (isset($socketParams[$iSpec['id']]) && is_array($socketParams[$iSpec['id']]) && sizeof($socketParams[$iSpec['id']]) > 0) {
                            foreach ($socketParams[$iSpec['id']] as $key => $src) {
                                $iVal = isset($iSpec[$src]) ? addslashes($iSpec[$src]) : 0;
                                $socketURL[$iSpec['id']] = str_replace("{".$src."}", $iVal, $socketURL[$iSpec['id']]);
                            }
                        }
                    }

                    // Link / action
                    if (isset($iSpec['id']) && isset($socketURL[$iSpec['id']]) && strlen($socketURL[$iSpec['id']]) > 3) {
                        // via BootstrapDialog + socket
                        $targetUrl = isset($iSpec['target']) ? $iSpec['target'] : '';
                        $actionTarget = "top.BootstrapDialog.show({
                                            title: '" . addslashes($iSpec['nama']) . "',
                                            message: " . '$' . "('<div></div>').load('" . $socketURL[$iSpec['id']] . "&url=" . blobEncode($targetUrl . "?id=" . (isset($iSpec['id']) ? $iSpec['id'] : 0) . $defVal) . "'),
                                            draggable: false,
                                            type: top.BootstrapDialog.TYPE_DEFAULT,
                                            size: top.BootstrapDialog.SIZE_SMALL,
                                            closable: true
                                        });";
                        $strContent .= "<a href='javascript:void(0);' onclick=\"$actionTarget\">";
                    }
                    else {
                        // fallback langsung iframe result
                        $mb_js = "";
                        if (isset($_GET['mb'])) {
                            $mb_js = "setTimeout(function(){ top.load_shoppingcart() },500)";
                        }
                        switch ($cCode) {
                            case "_TR_3683":
                                if (strlen($defVal) > 3 && isset($iSpec['target'], $iSpec['id'])) {
                                    $strContent .= "<a href='javascript:void(0);'
                                    onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal'\">";
                                }
                                else {
                                    $prodKode = isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : '';
                                    $nm = isset($iSpec['nama']) ? $iSpec['nama'] : '';
                                    $strContent .= "<a href='javascript:void(0);'
                                    onclick=\" BootstrapDialog.show({ title: 'Attention !', message: 'Out of <b class=\\'font-size-1-5 text-red\\'>" . addslashes($prodKode.' '.$nm) . "</b> stock '}); \">";
                                }
                                break;
                            case "_TR_461":
                                if (isset($iSpec['target'], $iSpec['id'])) {
                                    $strContent .= "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?selector&id=" . $iSpec['id'] . "$defVal';$mb_js\">";
                                }
                                else {
                                    $strContent .= "<a href='javascript:void(0);' style='color:#717271;'>";
                                }
                                break;
                            default:
                                if (isset($iSpec['target']) && $iSpec['target'] != NULL && isset($iSpec['id'])) {
                                    $strContent .= "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?selector&id=" . $iSpec['id'] . "$defVal';$mb_js\">";
                                }
                                else {
                                    $strContent .= "<a href='javascript:void(0);' style='color:#717271;'>";
                                }
                                break;
                        }
                    }
                    // teks item
                    $strContent .= isset($iSpec['nama']) ? $iSpec['nama'] : "";
                    $strContent .= "<span>" . (isset($iSpec['label']) ? $iSpec['label'] : "") . "</span>";
                    // tampilkan tabLabel kecil (opsional)
                    if (isset($iSpec['tabLabel']) && strlen($iSpec['tabLabel']) > 0) {
                        $strContent .= "<br><small class='text-muted'>" . $iSpec['tabLabel'] . "</small>";
                    }
                    // checklist kanan
                    if (isset($iSpec['id']) && isset($_SESSION[$cCode]['items'][$iSpec['id']])) {
                        $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'><i class='fa fa-check-square text-success text-bold'></i></span>";
                    }
                    elseif (isset($iSpec['id'])) {
                        $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'></span>";
                    }
                    $strContent .= "</a>";
                    // link mutasi bila ada
                    if (isset($iSpec['link_mutasi'])) {
                        $strContent .= $iSpec['link_mutasi'];
                    }
                    $strContent .= "</li>";
                }
                // footer tips per tab
                $strContent .= "<li class='list-group-item text-center text-muted' style='background:#e5e5c5;'><small>... type more keywords<br>for more specific results ...</small></li>";
                $strContent .= "</ul>"; // end UL items
                $strContent .= "</div>"; // end tab-pane
                $isFirstPane = false;
            }
            $strContent .= "</div>"; // end tab-content
        }
        else {
//            $strContent .= "<div class='form-control text-center'>- no matched item -</div>";
            $strContent .= "<div class='text-center bg-warning text-info text-bold'><i>TIDAK ADA SUMBER YANG BISA DIPILIH</i></div>";
        }

        echo $strContent;
        break;


    default:
        die('mode tidak ditentukan');
        break;
}

