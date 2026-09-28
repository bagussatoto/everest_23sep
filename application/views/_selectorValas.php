<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 17/09/2018
 * Time: 19.35
 */


switch ($mode) {

    case "view":
//        arrprint($items);
//arrprint($socketURL);

        $strContent = "";
        if (isset($items)) {
            if (is_array($items) && (sizeof($items) > 0)) {
                //$strContent .="<div>". json_encode($items) ."</div>";
                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                foreach ($items as $iSpec) {
                    if (isset($iSpec['minValue'])) {
                        $defaultValue = $iSpec['minValue'];
                        $defVal = "&minValue=$defaultValue";
                    }
                    else {
                        $defVal = "";
                    }
//                    $strContent .= "<div style='margin:1px;' class='panel no-padding text-bold text-left'>";

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


//                    die($socketURL);

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

                        $strContent .= " <a href='javascript:void(0);'
                                        onclick=\"$actionTarget\">";
                    }
                    else {
                        $strContent .= " <a href='javascript:void(0);'
                                        onclick=\"document.getElementById('result').src='" . $iSpec['target'] . "?id=" . $iSpec['id'] . "$defVal" . "&extern_id=" . $iSpec['bank_id'] . "'\">";
                    }


                    $strContent .= $iSpec['label'];
//                    $strContent .= json_encode($iSpec);
                    $strContent .= "</a>";
//                    $strContent .= "</div>";
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

        echo $strContent;

        break;


}