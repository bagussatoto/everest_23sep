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

//                arrPrint($items);

                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                foreach ($items as $iSpec) {

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
                        $actionTarget = "top.BootstrapDialog.show(                                   {
                                       title:'" . $iSpec['nama'] . "',
                                        message: " . '$' . "('<div></div>').load('" . $socketURL[$iSpec['id']] . "&url=" . blobEncode("" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal") . "'),
                                        draggable:false,                                        
                                        type:top.BootstrapDialog.TYPE_DEFAULT,
                                        size:top.BootstrapDialog.SIZE_SMALL,
                                        closable:true,
                                        }
                                        );";

                        $strContent .= "<a class='text-bold text-capitalize' href='javascript:void(0);'
                                        onclick=\"$actionTarget\">";
                    }
                    else {
                        switch ($cCode) {
                            case "_TR_3683":
                                if ($defVal > 0) {

                                    $strContent .= "<a class='text-bold text-capitalize' href='javascript:void(0);'
                                        onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal'\">";
                                }
                                else {

                                    $strContent .= "<a class='text-bold text-capitalize' href='javascript:void(0);'
                                    onclick=\" BootstrapDialog.show({
                                                    title: 'Attention !',
                                                    message: 'Out of <b class=\'font-size-1-5 text-red\'>" . $iSpec['produk_kode'] . " " . $iSpec['nama'] . "</b> stock '
                                                });
                                            \">";
                                }
                                break;
                            default:
                                $strContent .= "<a class='text-bold text-capitalize' href='javascript:void(0);'
                                        onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal'\">";
                                break;
                        }
                    }
                    $strContent .= isset($iSpec['nama']) ? $iSpec['nama'] : "";
                    $strContent .= $iSpec['label'];
                    $strContent .= "</a>";

                    $strContent .= isset($iSpec['extra_button']) ? $iSpec['extra_button'] : "";

                    $strContent .= "</li>";
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
            if(count($itemsAll)>0){
                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                foreach ($itemsAll as $iSpec_0) {
                    $link = $iSpec_0["link"];
                    $strContent .= "<li class='list-group-item'>";
                    $strContent .= "<a class='text-bold text-capitalize' href=' $link'>";
                    $strContent .= isset($iSpec_0['nama']) ? "<h4 class='text-bold text-danger'>(".$iSpec_0['nama'].")</h4><h5 class='text-bold text-italic'>berada di ".$iSpec_0["lokasi"]."Silahkan lakukan distribusi supaya dapat digunakan di ".$iSpec_0["curent_lokasi"]. "(PID:".$iSpec_0['id'].")</h5>": "";
                    $strContent .= $iSpec_0['label'];
                    $strContent .="</a>";
                    $strContent .= "</li>";
                }
                $strContent .= "<li class='list-group-item text-center text-muted' style='background:#e5e5c5;'><small>... type more keywords<br>for more specific results ...</small>";
                $strContent .= "</li>";
                $strContent .= "</ul class='list-group'>";
            }
            else{
                $strContent .= "<div class='form-control text-center'>";
                $strContent .= "- no matched item -";
                $strContent .= "</div>";
            }

        }

        echo $strContent;

        break;


}