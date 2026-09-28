<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 17/09/2018
 * Time: 19.35
 */


switch ($mode) {

    case "view":
        //         arrprint($items);


        $strContent = "";
        if (isset($items)) {
            if (is_array($items) && (sizeof($items) > 0)) {
                $strContent .= "<ul class='list-group text-left' style='line-height:13px;'>";
                foreach ($items as $iSpec) {
                    $info_tambahan = "";
                    // arrPrint($iSpec);
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


                    if (strlen($iSpec['tlp_1']) > 1) {
                        $tlps = getFirstPhone($iSpec['tlp_1']);
                        // cekBiru($tlps);

                        $info_tambahan .= " <g> " . ucfirst($tlps) . "</g> ";
                    }
                    if (strlen($iSpec['kategori_nama']) > 1) {
                        $info_tambahan .= " <r>(" . ucfirst($iSpec['kategori_nama']) . ")</r>";
                    }
                    $strContent .= $iSpec['label'] . $info_tambahan;

                    if (isset($iSpec['label_view'])) {
                        if (strlen($iSpec['label_view']) > 0) {
                            $strContent .= "<span class='text-red' ><small>(" . $iSpec['label_view'] . ")</small></span>";
                        }

                    }

                    //                    $strContent .= json_encode($iSpec);
                    $strContent .= "</a>";
                    //                    $strContent .= "</div>";
                    $strContent .= "</li class='list-group-item'>";

                }

                /* -----------------------------------------------
                 * keyword data pencarian
                 * -----------------------------------------------*/
                $data_kunci = "";
                if (isset($kolomToSearch)) {
                    foreach ($kolomToSearch as $item) {
                        $var = $item;
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil, $var";
                        }
                    }

                    $data_kunci .= "<div class='border-cek text-lowercase text-left'>";
                    $data_kunci .= "<span style='background-color: coral;color: cornsilk;'>keyword yg bisa digunakan</span> ";
                    $data_kunci .= $hasil;
                    $data_kunci .= "</div>";
                }

                $warning = "<div class='text-red'>";
                $warning .= "<i class='fa fa-warning blink'></i> ";
                $warning .= " Menganti gudang akan mereset data produk yg telah dipilih ";
                $warning .= " <i class='fa fa-warning blink'></i> ";
                $warning .= "</div>";
                $strContent .= "<li class='list-group-item text-center text-muted' style='background:#e5e5c5;'><small>... type more keywords for more specific results ...</small> $data_kunci $warning";
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