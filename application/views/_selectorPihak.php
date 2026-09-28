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
//                    $strContent .= "<div style='margin:1px;' class='panel no-padding text-bold text-left'>";


//                    die($socketURL);

                    $strContent .= " <a href='javascript:void(0);' style='font-size:0.9em;'
                                        onclick=\"document.getElementById('result').src='$iSpec[target]?id=$iSpec[id]$defVal'\">";


                    $strContent .= $iSpec['label'];
                    if (isset($iSpec['label_view'])) {
                        if (strlen($iSpec['label_view']) > 0) {

                            $strContent .= "<span class='text-red' > <small>(" . $iSpec['label_view'] . ")</small></span>";
                        }
                        elseif (strlen($iSpec['label_view_alt']) > 1){
                            $strContent .= "<span class='text-red' > <small>(" . $iSpec['label_view_alt'] . ")</small></span>";
                        }

                    }

//                    $strContent .= json_encode($iSpec);
                    $strContent .= "</a>";
//                    $strContent .= "</div>";
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