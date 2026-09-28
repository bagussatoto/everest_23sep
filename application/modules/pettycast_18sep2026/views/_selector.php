<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 17/09/2018
 * Time: 19.35
 */

switch ($mode) {
    case "view":
        // mati_disini();
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
                                    $strContent .= "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?selector&id=" . $iSpec['id'] . "$defVal';$mb_js\">";
                                    break;
                            }
                        }

//                        $strContent .= isset($iSpec['nama']) ? $iSpec['nama'] : "";
//                        $strContent .= "<span>" . $iSpec['label'] . "</span>";
                        if (isset($iSpec['nama']) && (strlen($iSpec['nama']) > 3)) {
                            $strContent .= isset($iSpec['nama']) ? $iSpec['nama'] : "";
                        }
                        else {
//                            $strContent .= isset($iSpec['nama']) ? $iSpec['nama'] : "";
                            $strContent .= "<span>" . $iSpec['label'] . "</span>";
                        }

                        if (isset($_SESSION[$cCode]['items'][$iSpec['id']])) {
                            $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'> <i class='fa fa-check-square text-success text-bold'></i> </span>";
                        }
                        else {
                            $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'></span>";
                        }
                        $strContent .= "</a>";
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
    default:
        die('mode tidak ditentukan');
        break;
}