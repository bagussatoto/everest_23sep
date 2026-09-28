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
        if (isset($items)) {
            if (is_array($items) && (sizeof($items) > 0)) {
                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                foreach ($items as $iSpec) {
                    $strContent .= "<li class='list-group-item'>";
                    if (isset($iSpec['minValue'])) {
                        $defaultValue = $iSpec['minValue'];
                        $defVal = "&minValue=$defaultValue";
                    }
                    else {
                        $defVal = "";
                    }
                    $strContent .= " <a href='javascript:void(0);' style='font-size:0.9em;' onclick=\"top.HoldOn.open();top.document.getElementById('result').src='$iSpec[target]?id=$iSpec[id]$defVal'\">";

                    $strContent .= $iSpec['label'];
                    if (isset($iSpec['label_view'])) {
                        if (strlen($iSpec['label_view']) > 0) {
                            $strContent .= "<span class='text-red' ><small>(" . $iSpec['label_view'] . ")</small></span>";
                        }
                    }
                    $strContent .= "</a>";
                    $strContent .= "</li class='list-group-item'>";
                }
                $strContent .= "<li class='list-group-item text-center text-muted' style='background:#e5e5c5;'><small>... type more keywords<br>for more specific results ...</small>";
                $strContent .= "</li>";
                $strContent .= "</ul class='list-group'>";
                $strContent .= "</ul class='list-group text-left'>";
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
        echo $strContent;

        break;


}