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
        $strContent .= "
        
        <style>
        
        .is-grey {
          background-color: #e0e0e0 !important;
          color: #555 !important;
        }
        
        .is-grey .progress-bar {
          background-color: #aaa !important;
        }
        
        .is-grey div,
        .is-grey span {
          color: #555 !important;
        }

        </style>
        
        ";
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

                        $isGrey = $jenisTr!="9833" && $iSpec['qty_distribute_persentase']*1 >= 100 ? "is-grey" : "";
                        $isGreyTitle = $jenisTr!="9833" && $iSpec['qty_distribute_persentase']*1 >= 100 ? "SPK ini (".$iSpec['no_spk'].")sudah komplit distribusinya..." : "";

                        $strContent .= "<li title='$isGreyTitle' class='list-group-item $isGrey'>";
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
                            if($jenisTr!="9833" && $iSpec['qty_distribute_persentase']*1 >= 100){
                                $actionTarget = "top.BootstrapDialog.show({
                                                title: 'OPSSSSSSSSSS',
                                                message: 'masuk sini udh 100 =>  %',
                                                draggable: false,                                        
                                                type: top.BootstrapDialog.TYPE_DEFAULT,
                                                size: top.BootstrapDialog.SIZE_SMALL,
                                                closable: true,
                                            });";
                            }
                            else{
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

                        }
                        else {

                            if($jenisTr!="9833" && $iSpec['qty_distribute_persentase']*1 >= 100){

                            }
                            else{
                                $mb_js = "";
                                if (isset($_GET['mb'])) {
                                    $mb_js = "setTimeout( function(){ top.load_shoppingcart() },500)";
                                }
                                switch ($cCode) {
                                    default:
                                        if (isset($iSpec['target']) && ($iSpec['target'] != NULL)) {
                                            $strContent .= "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?spk=".$iSpec['no_spk']."&selector&id=" . $iSpec['id'] . "$defVal';$mb_js\">";
                                        }
                                        break;
                                }
                            }
                        }

                        if (isset($_SESSION[$cCode]['items'][$iSpec['id']])) {
                            $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'> <i class='glyphicon glyphicon-check text-success text-bold'></i> </span>";
                        }
                        else {
                            $strContent .= "<span pid='" . $iSpec['id'] . "' id='itmCheck_" . $iSpec['id'] . "' class='itemCheck pull-right'></span>";
                        }

                        $strContent .= isset($iSpec['nama']) ? "<div style='font-weight: 700;font-size: 16px;'>" . $iSpec['nama'] . "</div>" : "";
                        $strContent .= isset($iSpec['label']) ? "<div>" . $iSpec['label'] . "</div>" : "";

                        $cssWidth = $iSpec['qty_distribute_persentase']*1 > 100 ? 100 : ($iSpec['qty_distribute_persentase']*1>0 ? $iSpec['qty_distribute_persentase']*1 : 0);

                        $strContent .= "
                            <div cCode='".$cCode."|" .$jenisTr."' style='margin-bottom: 0px;' class='progress active bg-red'>
                                <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='".$iSpec['qty_distribute_persentase']."' aria-valuemin='0' aria-valuemax='100' style='width: $cssWidth%'>
                                     <i class='fa fa-truck'></i> &nbsp; <span style='font-size: 14px;' class='text-bold'>".number_format($iSpec['qty_distribute_persentase'], 0)."</span> <i class='fa fa-percent'></i>
                                </div>
                            </div>
                        ";

                        if (isset($iSpec['target'])) {
                            if($jenisTr!="9833" && $iSpec['qty_distribute_persentase']*1 >= 100){

                            }
                            else{
                                $strContent .= "</a>";
                            }
                        }
                        else {
                            if (isset($iSpec['notes'])) {
                                $strContent .= "&nbsp;&nbsp;&nbsp;";
                                $strContent .= "<span class='meta'>";
                                $strContent .= $iSpec['notes'];
                                $strContent .= "</span>";
                            }
                        }
                        $strContent .= "</li>";
                    }
                }
                $strContent .= "<li class='list-group-item text-center text-muted' style='background:#e5e5c5;'><small>... type more keywords<br>for more specific results ...</small>";
                $strContent .= "</li>";
                $strContent .= "</ul class='list-group'>";
            }
            else {
                $strContent .= "<div class='alert bg-gray text-center text-red text-bold'>";
                $strContent .= "- tidak ada items yang cocok -<br>REASON: SPK kemungkinan tidak ada produk unitnya...??<br>SPK sudah selesai dikerjakan (QC)...??";
                $strContent .= "</div>";
            }
        }
        else {
            $strContent .= "<div class='alert bg-gray text-center text-red text-bold'>";
            $strContent .= "- tidak ada items yang cocok -<br>REASON: SPK kemungkinan tidak ada produk unitnya...??<br>SPK sudah selesai dikerjakan (QC)...??";
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