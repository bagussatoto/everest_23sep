<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */

switch ($mode) {
    case "View":

        $p = New Layout("$title", "$subTitle", "application/template/default.html");
        //cekHere(sizeof($detilFields));
        //arrPrint($detilFields);
        $strFields = "<table class='table table-bordered table-hover' id='myNewTable'>";
        if (sizeof($mainData) > 0) {

            //region header 2tingkat
            $strFields .= "<thead>";
            $strFields .= "<tr class='text-uppercase'>";
            $strFields .= "<th rowspan='2' class='text text-center text-bold bg-grey-2 valign-m'>No</th>";
            foreach ($mainLabels as $key => $mainAlias) {
                if (array_key_exists($key, $detilFields)) {
                    $rowSpan = "1";
                    $colspan = sizeof($detilFields[$key]);
                }
                else {
                    $rowSpan = "2";
                    $colspan = "1";
                }
                $strFields .= "<th rowspan='$rowSpan' colspan='$colspan' class='text text-center text-bold bg-grey-2 valign-m'>$mainAlias</th>";
            }
            $strFields .= "</tr>";

            $strFields .= "<tr class='text-uppercase'>";
            foreach ($mainLabels as $keys => $keysLabel) {
                if (isset($detilFields[$keys])) {
                    foreach ($detilFields[$keys] as $kol => $alias) {
                        //                       cekHere($alias);
                        $strFields .= "<th class='text text-center text-bold bg-grey-2 valign-m'>$alias</th>";
                    }
                }
            }
            $strFields .= "</tr>";
            $strFields .= "</thead>";
            //endregion
            // arrPrint($detilFields);

            // arrPrint($mainData);
            // arrPrint($detailsData);
            //regione body table
            $strFields .= "<tbody>";
            if (sizeof($mainData) > 0) {
                $i = 0;
                foreach ($mainData as $cusID => $nomerTop) {
                    $i++;
                    $rowspan2 = sizeof($detailsData[$cusID]) > 1 ? sizeof($detailsData[$cusID]) : "1";
                    $rowspan3 = sizeof($detailsData[$cusID]) > 1 ? sizeof($detailsData[$cusID]) + 1 : sizeof($detailsData[$cusID]);
                    foreach ($detailsData[$cusID] as $ix => $detailsDatum) {
                        if ($detailsDatum['status'] == "allowed") {

                        }
                    }

                    $strFields .= "<tr>";

//                    $strFields .= "<td rowspan='' class='text-right'>" . $i . "</td>";
//                    $strFields .= "<td rowspan='' >" . $pihakData[$cusID] . "</td>";
//                    for($s=1;$s<$rowspan2;$s++){
//                        $strFields .= "<td class=''></td>";
//                    }
                    //backup
                    $strFields .= "<td rowspan='$rowspan2' class='text-right'>" . $i . "</td>";
                    $strFields .= "<td rowspan='$rowspan2' >" . $pihakData[$cusID] . "</td>";

                    if (isset($detailsData[$cusID][0])) {
                        foreach ($detilFields['data_field'] as $col_sel => $colAlias) {
                            $strFields .= "<td rowspan='1'>" . formatField($col_sel, $detailsData[$cusID][0][$col_sel]) . "</td>";
                        }
                    }
                    $strFields .= "<td rowspan='$rowspan2' class='text-center'>" . formatField("btn_action", $cusID) . "</td>";//iki botton action
                    $strFields .= "</tr>";

                    if (isset($detailsData[$cusID]) && sizeof($detailsData[$cusID]) > 1) {
                        foreach ($detailsData[$cusID] as $plID => $tempDetails) {
                            if ($plID > 0) {
                                $strFields .= "<tr>";
                                foreach ($detilFields['data_field'] as $col_sel => $colAlias) {
                                    $strFields .= "<td rowspan='1' colspan='1'>" . formatField($col_sel, $tempDetails[$col_sel]) . "</td>";
                                }
                                $strFields .= "</tr>";
                            }
                        }
                    }
                }
            }
            $strFields .= "</tbody>";

            //endregion
            //            $strFields .= "<tfoot>";
            //            $strFields .= "<tr>";
            //            $strFields .= "</tr>";
            //            $strFields .= "</tfoot>";
        }
        $strFields .= "</table>";
        // region datatable
        $strFields .= "<script>
                            $(document).ready( function(){
        
                                var table = $('#datatables').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                  
                                            ],
        //                            buttons: [
        //                                        'copy', 'csv', 'excel', 'pdf', 'print'
        //                                    ],
        //                            buttons: [
        //                                        {
        //                                            extend: 'colvisGroup',
        //                                            text: 'Office info',
        //                                            show: [ 1, 2 ],
        //                                            hide: [ 3, 4, 5 ]
        //                                        },
        //                                        {
        //                                            extend: 'colvisGroup',
        //                                            text: 'HR info',
        //                                            show: [ 3, 4, 5 ],
        //                                            hide: [ 1, 2 ]
        //                                        },
        //                                        {
        //                                            extend: 'colvisGroup',
        //                                            text: 'Show all',
        //                                            show: ':hidden'
        //                                        }
        //                                    ]
                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;
        
                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };
        
                                                var arrayFooter = $('tfoot>tr>th');
                                                var dpageTotal = [];
                                                jQuery.each(arrayFooter, function(i,d){
                                                    var id_n_index = parseFloat(i);
                                                    dpageTotal[id_n_index] = 0;
                                                    jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
        //                                                console.log( $('span', obj).html() );
        //                                                console.log( obj );
        //                                                console.error( $(obj).html() );
                                                    });
                                                    console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );
        
        
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
        
        
                                                });
        
        // Total over all pages
        //                                        var total2=0;
        //                                        jQuery.each( $(api.column(2).data()), function(i, obj){
        //                                            total2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total3=0;
        //                                        jQuery.each( $(api.column(3).data()), function(i, obj){
        //                                            total3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total4=0;
        //                                        jQuery.each( $(api.column(4).data()), function(i, obj){
        //                                            total4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total5=0;
        //                                        jQuery.each( $(api.column(5).data()), function(i, obj){
        //                                            total5 += intVal( $('span', obj).html() );
        //                                        });
        
        
                                                // Total over this page
        //                                        pageTotal2 = api
        //                                            .column( 2, { page: 'current'} )
        //                                            .data()
        //                                            .reduce( function (a, b) {
        //                                                return intVal(a) + intVal(b);
        //                                            }, 0 );
        
        //                                        var pageTotal2=0;
        //                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal3=0;
        //                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal4=0;
        //                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal5=0;
        //                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal5 += intVal( $('span', obj).html() );
        //                                        });
        
                                                // Update footer
        //                                        $( api.column( 2 ).footer() ).html(
        //                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 3 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 4 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 5 ).footer() ).html(
        //                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
        //                                        );
        
                                            }
                                        });
        
                                    });
        
                                    $('.table-responsive').floatingScroll();
                            </script>";
        // endregion datatable

        // region last history
        $strHs = "";

        $strHs .= "<table class='table table-condensed table-hover'>";
        //region header
        $strHs .= "<thead>";
        $strHs .= "<tr class='text-uppercase'>";
        foreach ($hsHeaders as $hsHeader => $hAttr) {

            $strHs .= "<th $hAttr>$hsHeader</th>";
        }
        $strHs .= "</tr>";
        $strHs .= "</thead>";
        //endregion

        // region bodies
        $strHs .= "<tbody>";
        foreach ($hsBodies as $hsRow => $specs) {

            $strHs .= "<tr>";
            foreach ($specs as $field => $spec) {
                $attr = isset($spec['attr']) ? $spec['attr'] : "";
                $value = isset($spec['value']) ? $spec['value'] : "-";
                $value_f = isset($spec['format']) ? $spec['format']($field, $value) : $value;
                $value_l = isset($spec['link']) ? "<a href='" . $spec['link'] . "'>$value</a>" : $value_f;
                $strHs .= "<td $attr>$value_l</td>";
            }
            $strHs .= "</tr>";
        }
        $strHs .= "</tbody>";
        // endregion bodies

        $strHs .= "</table>";
        // endregion last history

        // region test
        $strTest = $test;
        // endregion test

        $p->setLayoutBoxCss("box-warning");
        $p->setLayoutBoxHeading(strtoupper($hsTitle));
        $strFields .= "<div class='margin-top-20'>";
        $strFields .= $p->layout_box($strHs);
        $strFields .= "</div>";
        // $strFields .= $strHs;

        // $strFields .= $strTest;

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "menu_sub" => callSubMEnu(),
            "content" => $strFields,
            "profile_name" => $this->session->login['nama'],
        ));

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->render();
        break;

    case "preview":
        //        $contents = "";
        //        arrPrint($items);
        $data_hdn = sizeof($items) > 0 ? blobEncode($items) : "";
        echo "<div class='row bg-gray'>";
        echo "<div class='col-md-6'>";
        echo "$title";
        echo "</div class='col-md-6'>";

        echo "<div class='col-md-6 text-right'>";
        echo "<div class='panel-body'>";
        //        echo "<a class='btn btn-primary' href='javascript:void(0)' onclick=\"$receiptLink\">";
        //        echo "<span class='glyphicon glyphicon-print'></span> ";

        //        echo "</a>";
        echo "</div class='panel'>";
        echo "</div class='col-md-6'>";

        echo "</div class='row'>";
        echo "<div>";
        $content = "<form name='form1' action='$target' target='result' method='post'>";
        $content .= "<table class='table table-active table-bordered'>";
        $content .= "<tr>";
        //arrPrint($itemLabels);
        foreach ($itemLabels['data_field'] as $col => $labels) {
            $content .= "<td>$labels</td>";
        }
        $content .= "</tr>";

        foreach ($items as $itemsData) {
            $i = 0;
            $content .= "<tr>";
            foreach ($itemLabels['data_field'] as $kol => $valAlias) {
                $i++;
                //                $content .="<td>$i</td>";
                $content .= "<td>" . formatField($kol, $itemsData[$kol]) . "</td>";
            }
            $content .= "</tr>";
        }
        $colspan = sizeof($itemLabels['data_field']) - 2;
        $content .= "<tr>";
        $content .= "<td colspan='$colspan' class='text-center'>Total</td>";
        $content .= "<td colspan=''>" . formatField("total", $sumValue) . "</td>";
        $content .= "</tr>";
        $content .= "</table>";
        $content .= "<input type='hidden' name='data' value='$data_hdn'>";
        $content .= "<input type='hidden' name='rawPrev' value='$rawPrev'>";

        $content .= "<div class='footer'>";
        $content .= "<div class='panel-body'>";
        foreach ($btnVal as $btnKey => $btnData) {
            $content .= "<div>";
            $content .= "<button type='" . $btnData['type'] . "' class='" . $btnData['class'] . "' style><span >" . $btnData['label'] . "</span><a/>";
            $content .= "</div>";
        }

        $content .= "</div>";
        $content .= "</div>";
        $content .= "</form>";

        echo $content;
        echo "</div>";

        //        echo $contents;
        break;
}