<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/14/2019
 * Time: 5:16 PM
 */

switch ($mode) {
    case "viewYearly":
        //        cekBiru();
        // region navigasi
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $selectedField = $navGate['nav2'];
        $date1 = isset($navGate['date1']) ? "&date1=" . $navGate['date1'] : "&date1=" . date("Y-m-d");
        $date2 = isset($navGate['date2']) ? "&date2=" . $navGate['date2'] : "&date2=" . date("Y-m-d");
        $navKey = isset($navGate['nav2']) ? "&nav2=" . $navGate['nav2'] : "&nav2=cabang";
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $navNtm = "<div class='col-md-12 text-left'>";
        $navNtm .= "<ul class='pagination'>";
        foreach ($navBtn as $modess => $navTmp) {
            $modess = $modess == 'salesman' ? 'seller' : $modess;
            $link = $navTmp['action'] . "?$navKey$date1$date2";
            $navTitle = $navTmp['label'];
            $cClass = $modess == $method ? 'bg-red text-bold text-white' : 'bg-light-blue text-gray';
            $navNtm .= "<li><a href='$link' class='btn btn-sm $cClass' data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>  $navTitle</a></li>";
        }
        $navNtm .= "</ul>";
        $navNtm .= "</div>";
        //endregion

        //region navigation 2

        $navBtn = "<h3>Pilih Periode</h3>";
        $navBtn .= "<select id='periode' onchange=\"period_url()\" class='form-control'>";
        foreach ($periode as $key => $pLabel) {
            $selected = $key == $selectedField ? "selected" : "";
            //arrPrint($pLabel);
            //            $navLink = $thisPage . "/$method?nav2=$key$date1$date2";
            $navLink = base_url() . $this->uri->segment(1) . "/" . $pLabel['method'] . "/" . $this->uri->segment(3) . "?nav2=$key$date1$date2";
            //            $navLink = "$thisPage/".$pLabel['method']."/".$this->uri->segment(3)."?nav2=$key$date1$date2";
            //            $navLink = "https://google.com";
            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">" . $pLabel['label'] . "</option>";
            //            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">".$navLink."</option>";
        }
        $navBtn .= "</select>";
        $navBtn .= "
        <script>
            var period_url = function(){
                var url = $('option:selected', $('#periode')).attr('url');
                window.location.href=url
            }
        </script>
        ";
        //endregion

        $daterange = "<table class='table table-condensed no-padding no-border'>
                        <tr>
                            <td>
                                <span class='glyphicon glyphicon-calendar'></span>
                                <label for='date1'>start date</label>
                            </td>
                            <td>
                                <input id='date1' class='form-control' data-date='' data-date-format='DD/MM/YYYY' type='date' value='" . $navGate['date1'] . "' min='{date_min}' max='{date_max}'>
                            </td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-default btn-block' href='javascript:void(0)' onclick='location.href='$thisPage/$method';'>
                                    <span class='glyphicon glyphicon-remove'></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class='glyphicon glyphicon-calendar'></span>
                                <label for='date2'>to date</label>
                            </td>
                            <td>
                                <input id='date2' class='form-control' data-date='' data-date-format='DD/MM/YYYY' type='date' value='" . $navGate['date2'] . "' min='{date_min}' max='{date_max}'>
                            </td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-primary btn-block' href='javascript:void(0)' onclick=\"location.href='$thisPage/$method?date1='+document.getElementById('date1').value+'&date2='+document.getElementById('date2').value+'&nav2='+$('#periode').val();\">
                                    <span class='fa fa-arrow-right'></span>
                                </a>
                            </td>
                        </tr>
                      </table>";

        $cotentList = "";

        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (isset($itemsMain['1'])) {
                $headerCount = isset($headerFields[1]['headerField']) ? sizeof($headerFields[1]['headerField']) : 0;

                $title = $itemsMain['1']['title'];
                $sbTitle = $itemsMain['1']['subtitle'];
                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain'>";
                }


                //                arrPrint($itemsMain[1]['mainData']);
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain'>";
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th rowspan='3' class='text-center'>No</th>";
                foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                $colspan2 = sizeof($itemsPeriode) * 2;
                $cotentList .= "<th colspan='$colspan2' class='text-center'>Penjualan(netto)</th>";
                $cotentList .= "</tr>";
                $cotentList .= "<tr>";
                foreach ($headerFields['1']['header2'] as $kol => $alias) {
                    $cotentList .= "<th colspan='2' class='text-center'>$alias </th>";
                }
                $cotentList .= "</tr>";
                if (sizeof($itemsPeriode) > 0) {
                    $colspan2 = sizeof($itemsPeriode);
                    $cotentList .= "<tr>";
                    foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                        foreach ($itemsPeriode as $periode) {
                            $cotentList .= "<th class='text-center'>$periode</th>";
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                $rr = 0;
                $cotentListFoot = "";
                if (sizeof($itemsMain[1]['mainData']) > 0) {
                    $itemValues = $itemsMain[1]['mainValues'];
                    foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                        $i = 0;
                        if (isset($itemsMain[1]['mainData'][$kol])) {
                            foreach ($itemsMain[1]['mainData'][$kol] as $kID => $kData) {
                                $i++;
                                $cotentList .= "<tr>";
                                $cotentList .= "<td>$i</td>";
                                foreach ($kData as $kk_key => $labelName) {
                                    $cotentList .= "<td>$labelName</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        $val = isset($itemValues[$periode][$kID][$kol]) ? $itemValues[$periode][$kID][$kol] : 0;
                                        $cotentList .= "<td class='text-right'>" . number_format($val) . "</td>";
                                    }
                                }
                                $cotentList .= "</tr>";
                            }
                            if (isset($itemsMain[1]['sumFooter'])) {
                                $colspanf = sizeof($headerFields['1']['headerField']) + 1;
                                $footerValue = $itemsMain[1]['sumFooter'];
                                $cotentListFoot = "<tr>";
                                $d = 1;
                                for ($d; $d <= $colspanf; $d++) {
                                    $cotentListFoot .= "<td colspan=''>-</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        $val = isset($footerValue[$kol][$periode]) ? number_format($footerValue[$kol][$periode]) : "-";
                                        $cotentListFoot .= "<td class='text-right text-bold'>$val</td>";
                                    }
                                }
                            }
                            $cotentList .= "</tr>";
                        }
                    }
                }

                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div class='panel-body'> <!-- panel-body -->";
                $cotentList .= "</div class='panel'> <!-- panel -->";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('itemsMain3', 'name', '$sbTitle.xls')
                                    }
                                }
                            ],
                            fnPreDrawCallback:function(){
                                $('#itemsMain_progress').show();
                            },
                            fnInitComplete:function(){
                                $('#itemsMain').show();
                                $('#itemsMain_progress').hide();
                            },
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                jQuery.each(data[0], function(i,bs){
                                    total = api
                                        .column( i )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));

                                        }, 0 );
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );

                                        if(parseFloat(pageTotal)>0 && i>0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        }else{

                                        }
                                })
                            }
                        });
                        $(\".table-responsive.itemsMain\").scroll(function () {
                            setTimeout(function () {
                                $('#itemsMain').DataTable().fixedHeader.adjust();
                            }, 400);
                        });
                    </script>";
                }
            }
            //endregion
            //region conten chil1
            $cotentListFoot = "";
            if (isset($itemsMain[2]) && sizeof($itemsMain['2']['mainData']) > 0) {
                $header2Merger = $itemsMain['2']['mainIndex2'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['2']['title'];
                $colspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";
                $rowspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3> $title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain2', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain2'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain2'>";
                }
                $cotentList .= "<table csp='$colspan' rsp='$rowspan' class='table table-bordered compact nowarp order-column' id='itemsMain2'>";

                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentListFoot = "<tr>";
                $cotentList .= "<th class='' rowspan='$rowspan'>No.</th>";
                $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                foreach ($headerFields['2']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='$rowspan' class='text-center'>$alias</th>"; //header kiri
                    $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                }
                if (sizeof($itemsMain['2']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['2']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>"; //header lv 1
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['2']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['2']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan3'>$ixLabel</th>";
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields[2]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>";
                                    $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                $cotentListFoot .= "</tr>";
                $cotentList .= "</thead>";
                //endregion

                //region data
                $cotentList .= "<tbody>";
                $i = 0;
                foreach ($itemsMain['2']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['2']['mainValues'][$pID];
                    $cotentList .= "<tr>";

                    $cotentList .= "<td>$i</td>";
                    foreach ($headerFields['2']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan='' class='text-right'>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</tbody>";
                //endregion data

                //subtotal bawah
                if (sizeof($itemsMain['2']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['2']['sumFooter']);
                    $sumValues = $itemsMain['2']['sumFooter'];
                    $fColspan = sizeof($headerFields['2']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    //                    $cotentListFoot .= "<td colspan=''>-</td>";
                    //                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($sumValues[$Cid][$kk][$periode]) ? $sumValues[$Cid][$kk][$periode] : 0;
                                $cotentListFoot .= "<td rowspan='' colspan='' class='text-right'>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }
                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10 || sizeof($itemsMain['2']['mainData']) > 100) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain2').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('itemsMain2', 'id', '$sbTitle.xls')
                                    }
                                }
                            ],
                            fnPreDrawCallback:function(){
                                $('#itemsMain2_progress').show();
                            },
                            fnInitComplete:function(){
                                $('#itemsMain2').show();
                                $('#itemsMain2_progress').hide();
                            },
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                jQuery.each(data[0], function(i,bs){
                                    total = api
                                        .column( i )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );

                                        if(parseFloat(pageTotal)>0 && i>0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        }else{

                                        }
                                })
                            }
                        });
                        $(\".table-responsive.itemsMain2\").scroll(function () {
                            setTimeout(function () {
                                $('#itemsMain2').DataTable().fixedHeader.adjust();
                            }, 400);
                        });
                    </script>";
                }
            }
            //endregion
            //region conten chil2
            if (isset($itemsMain[3]) && sizeof($itemsMain[3]) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $header2Merger = $itemsMain['3']['mainIndex2'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['3']['title'];
                $colspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                $rowspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain3', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain3'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain3'>";
                }
                $cotentList .= "<table class='table table-bordered tables-striped compact nowarp tables-hover order-column' name='itemsMain3' id='itemsMain3'>";
                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th colspan='5'>$title</th>";
                $cotentList .= "</tr>";
                $cotentList .= "<tr>";
                $cotentListFoot = "<tr>";
                $cotentList .= "<td class='' rowspan='3'>No.</td>";
                $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                foreach ($headerFields['3']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='3' class='text-center'>$alias</td>";
                    $cotentListFoot .= "<td class='text-center text-bold'>-</td>";
                }
                if (sizeof($itemsMain['3']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['3']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<td rowspan='' colspan='$colspan2' class='text-center'>$alias</td>";
                    }
                }
                $cotentList .= "</tr>";

                if (sizeof($headerFields['3']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['3']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<td colspan='$colspan3'>$ixLabel</td>";
                        }
                    }
                    $cotentList .= "</tr>";

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields[3]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<td class='text-center'>$periode</td>";
                                    $cotentListFoot .= "<td class='text-text text-bold'>-</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                $cotentListFoot .= "</tr>";
                $cotentList .= "</thead>";
                //endregion

                //region data
                $cotentList .= "<tbody>";
                $i = 0;
                foreach ($itemsMain['3']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['3']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";
                    foreach ($headerFields['3']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td class='text-right' rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</tbody>";
                //endregion

                //subtotal bawah
                if (sizeof($itemsMain['3']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['3']['sumFooter']);
                    $sumValues = $itemsMain['3']['sumFooter'];
                    $fColspan = sizeof($headerFields['3']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    //                    $cotentListFoot .= "<td colspan=''>-</td>";
                    //                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($sumValues[$Cid][$kk][$periode]) ? $sumValues[$Cid][$kk][$periode] : 0;
                                $cotentListFoot .= "<td class='text-right' rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }
                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                //                $cotentList .= "<div id='itemsMain3_progress' class='text-center'><img src='".base_url()."public/images/sys/loader-2.gif'></div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain3').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('itemsMain3', 'id', '$sbTitle.xls')
                                    }
                                }
                            ],
                            fnPreDrawCallback:function(){
                                $('#itemsMain3_progress').show();
                                $('#itemsMain3').hide();
                            },
                            fnInitComplete:function(){
                                setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                                setTimeout( function(){ $('#itemsMain3').show() },600);
                            },
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                jQuery.each(data[0], function(i,bs){
                                    total = api
                                        .column( i )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );

                                        if(parseFloat(pageTotal)>0 && i>0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).removeClass('text-center');
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).removeClass('text-center');
                                            $( api.column( i ).footer() ).addClass('text-right');
                                        }else{

                                        }
                                })
                                setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                                setTimeout( function(){ $('#itemsMain3').show() },600);

                            }
                        });
                        $(\".table-responsive.itemsMain3\").scroll(function () {
                            setTimeout(function () {
                                $('#itemsMain3').DataTable().fixedHeader.adjust();
                                setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                                setTimeout( function(){ $('#itemsMain3').show() },600);

                            }, 400);
                        });
                    </script>";
                }

            }
            //endregion
            //region conten chil3

            //                cekMerah('$headerFields[4]  ' . sizeof($headerFields['4']));

            if (isset($itemsMain['4']) && sizeof($itemsMain['4']) > 0) {

                $header2Merger = $itemsMain['4']['mainIndex2'] + array("subtotal" => "konsolidasi");

                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['4']['title'];
                $colspan = isset($headerFields['4']['header2']) ? sizeof($headerFields['4']['header2']) + 1 : "";
                $rowspan = isset($headerFields['4']['header2']) ? sizeof($headerFields['4']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div id='panel_itemsMain4' class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain4', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain4'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain4'>";
                }

                $cotentList .= "<table class='table table-bordered tables-striped compact nowarp tables-hover order-column' id='itemsMain4'>";

                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th colspan='5'>$title</th>";
                $cotentList .= "</tr>";
                $cotentList .= "<tr>";
                $cotentListFoot = "<tr>";
                $cotentList .= "<th class='' rowspan='3'>No.</th>";
                $cotentListFoot .= "<td rowspan='' colspan='' class='text-center'>-</td>";
                foreach ($headerFields['4']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>"; //header kiri lv1
                    $cotentListFoot .= "<td rowspan='' colspan='' class='text-center'>-</td>";
                }
                if (sizeof($itemsMain['4']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['4']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='2' class='text-center'>$alias</th>"; //header kanan lv 1
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['4']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan4 = sizeof($itemsPeriode);
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['4']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan4' class='text-center'>$ixLabel</th>"; //kanan header lv 2
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields['4']['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>"; //kanan header lv3
                                    $cotentListFoot .= "<td class='text-center'>-</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                $cotentListFoot .= "</tr>";
                $cotentList .= "</thead>";
                //endregion header
                //arrPrintWebs($header2Merger);
                //arrPrintWebs($headerFields[4]['header2']);
                $cotentList .= "<tbody>";
                //region data
                $i = 0;
                foreach ($itemsMain['4']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['4']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";
                    foreach ($headerFields['4']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['4']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td class='text-right' rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    if (isset($itemsMain[4]['sumFooter'])) {
                        $colspanf = sizeof($headerFields['4']['headerField']) + 1;
                        $footerValue = $itemsMain[4]['sumFooter'];
                        //                        arrPrint($footerValue);
                        $cotentListFoot = "<tr>";
                        $cotentListFoot .= "<td colspan=''>-</td>";
                        foreach ($headerFields['4']['headerField'] as $kol => $aliasX) {
                            $cotentListFoot .= "<td colspan=''>-</td>";
                        }
                        foreach ($header2Merger as $Cid => $alias) {
                            foreach ($headerFields[4]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $val = isset($footerValue[$Cid][$kol][$periode]) ? $footerValue[$Cid][$kol][$periode] : "0";
                                    $cotentListFoot .= "<td class='text-right text-bold'>" . number_format($val) . "</td>";
                                }
                            }
                        }
                        $cotentListFoot .= "</tr>";
                    }
                    $cotentList .= "</tr>";
                }
                //                arrPrint($headerFields[4]['header2']);
                //endregion
                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                //                $cotentList .= "<div id='itemsMain4_progress' class='text-center'><img src='".base_url()."public/images/sys/loader-2.gif'></div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";

                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                    $('#itemsMain4').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain4', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain4_progress').show();
                            $('#itemsMain4').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain4_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain4').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );

                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){ $('#itemsMain4_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain4').show() },600);

                        }
                    });
                    $(\".table-responsive.itemsMain4\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain4').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain4_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain4').show() },600);

                        }, 400);
                    });
                </script>";
                }
            }
            //endregion
        }
        $p->addTags(array(
            // "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left"        => callMenuLeft(),
            // "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $cotentList,
            "profile_name"     => $this->session->login['nama'],
            // "add_link" => $addLinkStr,
            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn"          => $navNtm,
            "navigasi"         => $navBtn,
            "url"              => $thisPage . "/$method",
            "date1"            => $navGate['date1'],
            "date2"            => $navGate['date2'],
            "daterange"        => $daterange,
        ));
        $p->render();
        break;
    case "viewYear":
        // arrPrint($detilLink);
        // region navigasi
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $nav2 = isset($navGate['nav2']) ? $navGate['nav2'] : "";
        $date1 = isset($navGate['date1']) ? "&date1=" . $navGate['date1'] : "&date1=" . date("Y") - 1;
        //        $date2 = isset($navGate['date2']) ? "&date2=" . $navGate['date2'] : date("Y-m-d");
        $navKey = isset($navGate['nav2']) ? "&nav2=" . $navGate['nav2'] : "&nav2=cabang";

        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $navNtm = "<div class='col-md-12 text-left'>";
        $navNtm .= "<ul class='pagination'>";
        foreach ($navBtn as $modess => $navTmp) {
            $modess = $modess == 'salesman' ? 'seller' : $modess;
            $link = $navTmp['action'] . "?$navKey$date1";
            $navTitle = $navTmp['label'];
            $cClass = $modess == $method ? 'bg-red text-bold text-white' : 'bg-light-blue text-gray';
            $navNtm .= "<li><a href='$link' class='btn btn-sm $cClass' data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>  $navTitle</a></li>";
        }
        $navNtm .= "</ul>";
        $navNtm .= "</div>";
        //endregion

        //region navigation 2
        $navBtn = "<h3>Pilih Periode</h3>";
        $navBtn .= "<select id='periode' onchange=\"period_url()\" class='form-control'>";
        foreach ($periode as $key => $pLabel) {
            $selected = $key == $nav2 ? "selected" : "";
            $navLink = base_url() . $this->uri->segment(1) . "/" . $pLabel['method'] . "/" . $this->uri->segment(3) . "?nav2=$key$date1";
            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">" . $pLabel['label'] . "</option>";
        }
        $navBtn .= "</select>";
        $navBtn .= "
        <script>
            var period_url = function(){
                var url = $('option:selected', $('#periode')).attr('url');
                window.location.href=url
            }
        </script>
        ";
        //endregion
        $thisYear = date('Y');
        $pastYear = $thisYear - 10;
        $rangeYears = range($pastYear, $thisYear - 1, 1);

        $daterange = "<h3>Pilih Tahun</h3>";
        $daterange .= "<select id='tahun' onchange=\"tahun_url()\" class='form-control'>";
        foreach ($rangeYears as $key => $tahun) {
            $selected = $tahun == $navGate['date1'] ? "selected" : "";
            $navLink = base_url() . $this->uri->segment(1) . "/viewYear/" . $this->uri->segment(3) . "?CHOS=1&nav2=$nav2&date1=$tahun";
            $daterange .= "<option value ='$tahun' $selected url=\"$navLink\">$tahun </option>";
        }
        $daterange .= "</select>";
        $daterange .= "<script>
            var tahun_url = function(){
                var url = $('option:selected', $('#tahun')).attr('url');
                window.location.href=url
            }
        </script>";

        $cotentList = "";

        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (isset($itemsMain['1'])) {
                $title = $itemsMain['1']['title'];
                $sbTitle = $itemsMain['1']['subtitle'];
                $headerCount = isset($headerFields[1]['headerField']) ? sizeof($headerFields[1]['headerField']) : 0;
                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' name='itemsMain' id='itemsMain'>";
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th rowspan='3' class='text-center'>No</th>";
                foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                $colspan2 = sizeof($itemsPeriode) * 2;
                $cotentList .= "<th colspan='$colspan2' class='text-center'>Penjualan(netto)</th>";
                //                $cotentList .= "<td colspan='$colspan2' class='text-center'>konsolidasi</td>";
                //arrPrint($itemsPeriode);
                //arrPrint($itemValues);
                $cotentList .= "</tr>";
                $cotentList .= "<tr>";
                foreach ($headerFields['1']['header2'] as $kol => $alias) {
                    $cotentList .= "<th colspan='2' class='text-center'>$alias </th>";
                }
                $cotentList .= "</tr>";
                if (sizeof($itemsPeriode) > 0) {
                    $colspan2 = sizeof($itemsPeriode);
                    $cotentList .= "<tr>";
                    foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                        foreach ($itemsPeriode as $periode) {
                            $cotentList .= "<th class='text-center'>$periode</th>";
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                //                arrPrint($itemsMain[1]['sumFooter']);
                $rr = 0;
                $cotentListFoot = "";
                if (sizeof($itemsMain[1]['mainData']) > 0) {
                    $itemValues = $itemsMain[1]['mainValues'];
                    //                    arrPrint($itemValues);
                    foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                        $i = 0;
                        if (isset($itemsMain[1]['mainData'][$kol])) {
                            foreach ($itemsMain[1]['mainData'][$kol] as $kID => $kData) {
                                $i++;
                                $cotentList .= "<tr>";
                                $cotentList .= "<td>$i</td>";
                                foreach ($kData as $kk_key => $labelName) {
                                    $cotentList .= "<td>$labelName</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        $val = isset($itemValues[$periode][$kID][$kol]) ? $itemValues[$periode][$kID][$kol] : 0;
                                        $link = $val > 0 ? $detilLink['main'] . "/" . $this->uri->segment(3) . "/?th=$periode&cb=$kID" : "#";
                                        $cotentList .= "<td class='text-right'><a href='$link'>" . number_format($val) . "</a></td>";

                                    }
                                }
                                $cotentList .= "</tr>";
                            }
                            if (isset($itemsMain[1]['sumFooter'])) {
                                //                                arrPrint($itemsMain[1]['sumfield']);
                                //                                arrPrint(sizeof($headerFields['1']['headerField']));
                                $colspanf = sizeof($headerFields['1']['headerField']) + 1;
                                $footerValue = $itemsMain[1]['sumFooter'];
                                $cotentListFoot = "<tr>";
                                //                                foreach ($itemsMain[1]['sumfield']['footer'] as $kol => $valAlias) {
                                //                                $cotentListFoot .= "<td colspan=''>Total</td>";

                                $d = 1;
                                for ($d; $d <= $colspanf; $d++) {
                                    $cotentListFoot .= "<td colspan=''>-</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        //                                        $val = isset($footerValue[$kol][$periode]) ? $footerValue[$kol][$periode] : "-";
                                        $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                                    }
                                }
                            }
                            $cotentList .= "</tr>";
                            //                            }
                        }
                    }
                }


                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('itemsMain', 'id', '$sbTitle.xls')
                                    }
                                }
                            ],
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                jQuery.each(data[0], function(i,bs){
                                    total = api
                                        .column( i )
                                        .data()
                                        .reduce( function (a, b) {
                                            b = $(b).html() ? b : '<a>'+b+'</a>';
                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));

                                        }, 0 );
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            b = $(b).html() ? b : '<a>'+b+'</a>';
                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
                                        }, 0 );

                                        if(parseFloat(pageTotal)>0 && i>0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        }else{

                                        }
                                })
                            }
                        });
                        $('.table-responsive.itemsMain').floatingScroll();
                        $('.table-responsive.itemsMain').scroll(function () {
                            setTimeout(function () {
                                $('#itemsMain').DataTable().fixedHeader.adjust();
                            }, 400);
                        });
                    </script>";
                }
            }
            //endregion
            //region conten chil1
            if (isset($itemsMain[2]) && sizeof($itemsMain['2']['mainData']) > 0) {
                $header2Merger = $itemsMain['2']['mainIndex2'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;
                $title = $itemsMain['2']['title'];
                $colspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";
                $rowspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3> $title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain2', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain2'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain2'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' name='itemsMain2' id='itemsMain2'>";
                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='$rowspan'>No.</th>";
                foreach ($headerFields['2']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='$rowspan' class='text-center'>$alias</th>";
                }
                if (sizeof($itemsMain['2']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['2']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";

                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['2']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['2']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan3'>$ixLabel </th>";
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields[2]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                $cotentList .= "</thead>";
                //endregion

                //region data
                //                arrPrint($itemsMain['2']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['sumFooter']);
                $cotentList .= "<tbody>";
                $i = 0;
                foreach ($itemsMain['2']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['2']['mainValues'][$pID];
                    //arrPrint($dataValues);
                    $cotentList .= "<tr>";
                    $cotentList .= "<td class='text-center'>$i</td>";

                    foreach ($headerFields['2']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td class='text-left'>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan=''>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td class='text-right' rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";

                }
                $cotentList .= "</tbody>";


                //subtotal bawah
                if (sizeof($itemsMain['2']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['2']['sumFooter']);
                    $sumValues = $itemsMain['2']['sumFooter'];
                    $fColspan = sizeof($headerFields['2']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    //                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }


                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain2').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('itemsMain2', 'id', '$sbTitle.xls')
                                    }
                                }
                            ],
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };
                                jQuery.each(data[0], function(i,bs){
                                    total = api
                                        .column( i )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );
                                        if(parseFloat(pageTotal)>0 && i>0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        }
                                        else{

                                        }
                                })
                            }
                        });
                        $('.table-responsive.itemsMain2').floatingScroll();
                        $('.table-responsive.itemsMain2').scroll(function () {
                            setTimeout(function () {
                                $('#itemsMain2').DataTable().fixedHeader.adjust();
                            }, 400);
                        });
                    </script>";
                }

            }
            //endregion
            //region conten chil2
            if (isset($itemsMain[3]) && sizeof($itemsMain[3]) > 0) {
                $header2Merger = $itemsMain['3']['mainIndex2'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['3']['title'];
                $colspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                $rowspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                //cekHitam(sizeof($headerFields['3']['header2']));
                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain3', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain3'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain3'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' name='itemsMain3' id='itemsMain3'>";
                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='3'>No.</th>";
                foreach ($headerFields['3']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                if (sizeof($itemsMain['3']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['3']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";
                    }
                }
                $cotentList .= "</tr>";
                $cotentListFoot = "<td colspan=''>-</td>";
                $d3 = 0;
                for ($d3; $d3 <= $colspan; $d3++) {
                    $cotentListFoot .= "<td colspan=''>-</td>";
                }
                if (sizeof($headerFields['3']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['3']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan3'>$ixLabel</th>";
                            $cotentListFoot .= "<td colspan=''>-</td>";
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields[3]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                //endregion
                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                //region data
                //                arrPrint($itemsMain['2']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['3']['mainValues']);
                //                $itemsMain['3']['mainData']
                $i = 0;
                foreach ($itemsMain['3']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['3']['mainValues'][$pID];

                    $cotentList .= "<tr>";
                    $cotentList .= "<td class='text-center'>$i</td>";

                    foreach ($headerFields['3']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td class='text-left'>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan=''>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td class='text-right' rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }


                    $cotentList .= "</tr>";
                }

                $cotentList .= "</tbody>";

                //subtotal bawah
                if (sizeof($itemsMain['3']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['3']['sumFooter']);
                    $sumValues = $itemsMain['3']['sumFooter'];
                    $fColspan = sizeof($headerFields['3']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    //                    $cotentListFoot .= "<td colspan=''>-</td>";
                    //                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($sumValues[$Cid][$kk][$periode]) ? $sumValues[$Cid][$kk][$periode] : 0;
                                $cotentListFoot .= "<td class='text-right' rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }

                //endregion
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain3').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('itemsMain3', 'id', '$sbTitle.xls')
                                    }
                                }
                            ],
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };
                                jQuery.each(data[0], function(i,bs){
                                    total = api
                                        .column( i )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );

                                        if(parseFloat(pageTotal)>0 && i>0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        }else{

                                        }
                                })
                            }
                        });
                        $('.table-responsive.itemsMain3').floatingScroll();
                        $('.table-responsive.itemsMain3').scroll(function () {
                            setTimeout(function () {
                                $('#itemsMain3').DataTable().fixedHeader.adjust();
                            }, 400);
                        });

                    </script>";
                }
            }
            //endregion
            //region conten chil3
            if (isset($itemsMain[4]) && sizeof($itemsMain[4]) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $header2Merger = $itemsMain['4']['mainIndex2'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['4']['title'];
                $colspan = isset($headerFields['4']['header2']) ? sizeof($headerFields['4']['header2']) + 1 : "";
                $rowspan = isset($headerFields['4']['header2']) ? sizeof($headerFields['4']['header2']) + 1 : "";
                //cekHitam(sizeof($headerFields['4']['header2']));
                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h4>$title</h4></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain4', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain4'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain4'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' name='itemsMain4' id='itemsMain4'>";
                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='3'>No.</th>";
                $cotentListFoot = "<td class='text-right' colspan=''>-</td>";
                foreach ($headerFields['4']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                    $cotentListFoot .= "<td class='text-right' colspan=''>-</td>";
                }
                if (sizeof($itemsMain['4']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['4']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";
                        $cotentListFoot .= "<td class='text-right' colspan=''>-</td>";
                    }
                }
                $cotentList .= "</tr>";


                if (sizeof($headerFields['4']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan4 = sizeof($itemsPeriode);
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['4']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan4'>$ixLabel</th>";
                            $cotentListFoot .= "<td class='text-right'>-</td>";
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields[4]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                //endregion
                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                //region data
                $i = 0;
                foreach ($itemsMain['4']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['4']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td class='text-center'>$i</td>";
                    foreach ($headerFields['4']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td class='text-left'>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['4']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan=''>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td class='text-right'>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</tbody>";
                //endregion
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";

                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                    $('#itemsMain4').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain4', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                    }else{

                                    }
                            })
                        }
                    });
                    $('.table-responsive.itemsMain4').floatingScroll();
                    $('.table-responsive.itemsMain4').scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain4').DataTable().fixedHeader.adjust();
                        }, 400);
                    });
                </script>";
                }

            }
            //endregion
        }
        $p->addTags(array(
            // "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left"        => callMenuLeft(),
            // "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $cotentList,
            "profile_name"     => $this->session->login['nama'],
            // "add_link" => $addLinkStr,
            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn"          => $navNtm,
            "navigasi"         => $navBtn,
            "url"              => $thisPage . "/$method",
            "date1"            => $navGate['date1'],
            //            "date2" => $navGate['date2'],
            "daterange"        => $daterange,
        ));
        $p->render();

        break;
    case "viewMonthly":
        // region navigasi
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $selectedField = $navGate['nav2'];
        $date1 = isset($navGate['date1']) ? "&date1=" . $navGate['date1'] : "&date1=" . date("Y-m-d");
        $date2 = isset($navGate['date2']) ? "&date2=" . $navGate['date2'] : "&date2=" . date("Y-m-d");
        $navKey = isset($navGate['nav2']) ? "&nav2=" . $navGate['nav2'] : "&nav2=cabang";

        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $navNtm = "<div class='col-md-12 text-left'>";
        $navNtm .= "<ul class='pagination'>";
        foreach ($navBtn as $modess => $navTmp) {
            $modess = $modess == 'salesman' ? 'seller' : $modess;
            $link = $navTmp['action'] . "?$navKey$date1$date2";
            $navTitle = $navTmp['label'];
            $cClass = $modess == $method ? 'bg-red text-bold text-white' : 'bg-light-blue text-gray';
            $navNtm .= "<li><a href='$link' class='btn btn-sm $cClass' data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>  $navTitle</a></li>";
        }
        $navNtm .= "</ul>";
        $navNtm .= "</div>";
        //endregion

        //region navigation 2

        $navBtn = "<h3>Pilih Periode</h3>";
        $navBtn .= "<select id='periode' onchange=\"period_url()\" class='form-control'>";
        foreach ($periode as $key => $pLabel) {
            $selected = $key == $selectedField ? "selected" : "";
            //arrPrint($pLabel);
            //            $navLink = $thisPage . "/$method?nav2=$key$date1$date2";
            $navLink = base_url() . $this->uri->segment(1) . "/" . $pLabel['method'] . "/" . $this->uri->segment(3) . "?nav2=$key$date1$date2";
            //            $navLink = "$thisPage/".$pLabel['method']."/".$this->uri->segment(3)."?nav2=$key$date1$date2";
            //            $navLink = "https://google.com";
            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">" . $pLabel['label'] . "</option>";
            //            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">".$navLink."</option>";
        }
        $navBtn .= "</select>";
        $navBtn .= "
        <script>
            var period_url = function(){
                var url = $('option:selected', $('#periode')).attr('url');
                window.location.href=url
            }
        </script>
        ";
        //endregion

        $daterange = "<table class='table table-condensed no-padding no-border'>
                        <tr>
                            <td>
                                <span class='glyphicon glyphicon-calendar'></span>
                                <label for='date1'>Pilih Bulan</label>
                            </td>
                            <td>
                                <input id='date1' class='form-control' data-date='' data-date-format='DD/MM/YYYY' type='month' value='" . $navGate['date1'] . "' min='{date_min}' max='{date_max}'>
                            </td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-default btn-block' href='javascript:void(0)' onclick=\"location.href='$thisPage/$method';\">
                                    <span class='glyphicon glyphicon-remove'></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-primary btn-block' href='javascript:void(0)' onclick=\"location.href='$thisPage/$method?date1='+document.getElementById('date1').value+'&nav2='+$('#periode').val();\">
                                    <span class='fa fa-arrow-right'></span>
                                </a>
                            </td>
                        </tr>
                      </table>";

        $cotentList = "";
        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (isset($itemsMain['1'])) {
                $title = $itemsMain['1']['title'];
                $sbTitle = $itemsMain['1']['subtitle'];
                $headerCount = isset($headerFields[1]['headerField']) ? sizeof($headerFields[1]['headerField']) : 0;

                //            $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain'>";
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th rowspan='3' class='text-center'>No</th>";
                foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                $colspan2 = (sizeof($itemsPeriode) * 2) + 2;
                $cotentList .= "<th colspan='$colspan2' class='text-center'>Penjualan(netto)</th>";
                $cotentList .= "</tr>";

                if (sizeof($itemsPeriode) > 0) {
                    $colspan2 = sizeof($itemsPeriode);
                    $colspan3 = sizeof($headerFields['1']['header2']);
                    $cotentList .= "<tr>";
                    foreach ($itemsPeriode as $periode) {
                        $cotentList .= "<th class='text-center' colspan='$colspan3'>$periode</th>";
                    }
                    $cotentList .= "<th colspan='2' class='text-center' rowspan='1'>konsolidasi</th>";
                    $cotentList .= "</tr>";
                    $cotentList .= "<tr>";
                    foreach ($itemsPeriode as $periode) {
                        foreach ($headerFields['1']['header2'] as $kol => $alias) {
                            $cotentList .= "<th style='min-width:60px;' class='text-capitalize text-center'>$alias</th>";
                        }
                    }
                    foreach ($headerFields['1']['header2'] as $kol => $alias) {
                        $cotentList .= "<th style='min-width:60px;' class='text-capitalize text-center'>$alias</th>";
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                //                arrPrint($itemsMain[1]['mainValues']);
                $rr = 0;
                $cotentListFoot = "";
                if (sizeof($itemsMain[1]['mainData']) > 0) {
                    $itemValues = $itemsMain[1]['mainValues'];
                    //                    arrPrint($headerFields[1]['header2']);
                    foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                        $i = 0;
                        if (isset($itemsMain[1]['mainData'][$kol])) {
                            foreach ($itemsMain[1]['mainData'][$kol] as $kID => $kData) {
                                $i++;
                                $cotentList .= "<tr>";
                                $cotentList .= "<td>$i</td>";
                                foreach ($kData as $kk_key => $labelName) {
                                    $cotentList .= "<td>$labelName</td>";
                                }

                                foreach ($itemsPeriode as $periode => $periodeLabel) {
                                    foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                        $val = isset($itemValues[$kID][$periode][$kol]) ? $itemValues[$kID][$periode][$kol] : 0;
                                        $cotentList .= "<td class='text-right'>" . number_format($val) . "</td>";
                                    }
                                }
                                //region subtotal kaanan
                                $itemSubValues = $itemsMain[1]['mainSumValues'];
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    $sub_val = isset($itemSubValues[$kID]['subtotal'][$kol]) ? $itemSubValues[$kID]['subtotal'][$kol] : 0;
                                    $cotentList .= "<td class='text-right text-bold'>" . number_format($sub_val) . "</td>";
                                }
                                //endregion
                                $cotentList .= "</tr>";
                            }
                            if (isset($itemsMain[1]['sumFooter'])) {
                                //                                arrPrint($itemsMain[1]['sumfield']);
                                //                                arrPrint(sizeof($headerFields['1']['headerField']));
                                $colspanf = sizeof($headerFields['1']['headerField']) + 1;
                                $footerValue = $itemsMain[1]['sumFooter'];
                                $cotentListFoot = "<tr>";
                                //                                foreach ($itemsMain[1]['sumfield']['footer'] as $kol => $valAlias) {
                                $cotentListFoot .= "<td colspan=''>-</td>";

                                $d = 0;
                                for ($d; $d <= $colspanf; $d++) {
                                    $cotentListFoot .= "<td colspan=''>-</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        //                                        $val = isset($footerValue[$kol][$periode]) ? $footerValue[$kol][$periode] : "-";
                                        $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                                    }
                                }
                                $cotentList .= "</tr>";
                            }

                            //                            }
                        }
                    }
                }


                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //            $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                    $('#itemsMain').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain_progress').show();
                            $('#itemsMain').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );

                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){
                                $('#itemsMain_progress').hide()
                            },500);
                            setTimeout( function(){
                                $('#itemsMain').show();
                                $('.table-responsive.itemsMain').floatingScroll();
                                $('#itemsMain').DataTable().fixedHeader.adjust();
                            },600);

                        }
                    });
                    $('.table-responsive.itemsMain').floatingScroll();
                    $('#itemsMain').DataTable().fixedHeader.adjust();
                    $(\".table-responsive.itemsMain\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain').show() },600);
                        }, 400);
                    });
                </script>";
                }

            }
            //endregion

            //region conten chil1
            if (isset($itemsMain[2]) && sizeof($itemsMain['2']['mainData']) > 0) {
                $header2Merger = $itemsMain['2']['paramPeriode'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['2']['title'];
                $colspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";
                $rowspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3> $title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain2', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain2'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain2'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain2'>";
                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='$rowspan'>No.</th>";
                foreach ($headerFields['2']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='$rowspan' class='text-center'>$alias</th>";
                }
                if (sizeof($itemsMain['2']['mainIndex2'])) {
                    $colspan2 = sizeof($headerFields['2']['header2']) * sizeof($itemsMain['2']['mainIndex2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";

                    }
                    //                    $cotentList .= "<td rowspan='' colspan='$colspan2' class='text-center'>Total</td>";
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['2']['header2']) > 0) {
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsMain['2']['mainIndex2']);
                    foreach ($header2Merger as $periode) {
                        foreach ($headerFields['2']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th class='text-bold text-center' colspan='$colspan3'>$ixLabel </th>";
                        }
                    }

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($headerFields['2']['header2']);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['2']['paramPeriode'] as $kol => $valAlias) {
                            foreach ($headerFields['2']['header2'] as $alias) {
                                foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                    $cotentList .= "<th class='text-bold text-center' colspan=''>$cidLabel</th>";
                                }
                            }


                        }
                        //subtotal
                        foreach ($headerFields['2']['header2'] as $alias) {
                            foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                $cotentList .= "<th class='text-bold text-center' colspan=''>$cidLabel</th>";
                            }
                        }


                        $cotentList .= "</tr>";
                    }


                }
                $cotentList .= "</thead>";
                //endregion
                $cotentList .= "<tbody>";
                //region data
                $i = 0;
                foreach ($itemsMain['2']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['2']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";

                    foreach ($headerFields['2']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $periodID => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                $val = isset($dataValues[$periodID][$kk][$cID]) ? $dataValues[$periodID][$kk][$cID] : 0;
                                $cotentList .= "<td rowspan='' colspan='' class='text-right'>" . number_format($val) . "</td>";
                                //                                $cotentList .="<td>$pID $periodID $kk $cID</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";

                }
                $cotentList .= "</tbody>";

                //subtotal bawah
                if (sizeof($itemsMain['2']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['2']['sumFooter']);
                    $sumValues = $itemsMain['2']['sumFooter'];
                    $fColspan = sizeof($headerFields['2']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    //                    $cotentListFoot .= "<td colspan=''>-</td>";
                    //                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                $val = isset($sumValues[$Cid][$kk][$cID]) ? number_format($sumValues[$Cid][$kk][$cID]) : "-";
                                $cotentListFoot .= "<td rowspan='' colspan=''>$val</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }
                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                //                $cotentList .= "<div id='itemsMain2_progress' class='text-center'><img src='".base_url()."public/images/sys/loader-2.gif'></div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                    $('#itemsMain2').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain2', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain2_progress').show();
                            $('#itemsMain2').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain2_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain2').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){ $('#itemsMain2_progress').hide() },500);
                            setTimeout( function(){
                                $('#itemsMain2').show();
                                $('.table-responsive.itemsMain2').floatingScroll();
                                $('#itemsMain2').DataTable().fixedHeader.adjust();
                            },600);

                        }
                    });
                    $('.table-responsive.itemsMain2').floatingScroll();
                    $(\".table-responsive.itemsMain2\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain2').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain2_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain2').show() },600);
                        }, 100);
                    });
                </script>";
                }

            }
            //endregion

            //            //region conten chil2
            if (isset($itemsMain[3]) && sizeof($itemsMain[3]) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $headerCount = isset($headerFields[3]['headerField']) ? sizeof($headerFields[3]['headerField']) : 0;

                $title = $itemsMain['3']['title'];
                $colspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                $rowspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain3', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain3'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain3'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain3'>";
                //region header

                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='3'>No.</th>";
                foreach ($headerFields['3']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                if (sizeof($itemsMain['3']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode);
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['3']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {
                        foreach ($headerFields['3']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan3'>$ixLabel</th>";
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {
                            foreach ($headerFields[3]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                $cotentList .= "</thead>";
                //endregion

                //region data
                //                arrPrint($itemsMain['3']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['mainValues']);
                $cotentList .= "<tbody>";
                $i = 0;
                foreach ($itemsMain['3']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['3']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";
                    foreach ($headerFields['3']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</tbody>";
                //endregion
                //                //subtotal bawah
                if (sizeof($itemsMain['3']['sumFooter']) > 0) {
                    $sumValues = $itemsMain['3']['sumFooter'];
                    $fColspan = sizeof($headerFields['3']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($sumValues[$Cid][$kk][$periode]) ? $sumValues[$Cid][$kk][$periode] : 0;
                                $cotentListFoot .= "<td rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }
                //                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= $cotentListFoot;
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";

                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain3').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain3', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain3_progress').show();
                            $('#itemsMain3').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain3').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                            setTimeout( function(){
                                $('#itemsMain3').show();
                                $('.table-responsive.itemsMain3').floatingScroll();
                                $('#itemsMain3').DataTable().fixedHeader.adjust();
                            },600);
                        }
                    });
                    $('.table-responsive.itemsMain3').floatingScroll();
                    $(\".table-responsive.itemsMain3\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain3').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain3').show() },600);
                        }, 100);
                    });
                    </script>";
                }
            }
            //            //endregion conten chil2
        }
        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left"        => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $cotentList,
            "profile_name"     => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn"          => $navNtm,
            "navigasi"         => $navBtn,
            "url"              => $thisPage . "/$method",
            "date1"            => $navGate['date1'],
            "date2"            => $navGate['date2'],
            "daterange"        => $daterange,
        ));
        $p->render();

        break;
    case "viewDetailItem":
        // region navigasi
        $content = "";
        //        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongoDetail.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        //        $selectedField = $navGate['nav2'];
        //        $date1 = isset($navGate['date1']) ? "&date1=" . $navGate['date1'] : date("Y-m-d");
        //        $date2 = isset($navGate['date2']) ? "&date2=" . $navGate['date2'] : date("Y-m-d");
        //        $navKey = isset($navGate['nav2']) ? "&nav2=" . $navGate['nav2'] : "&nav2=cabang";
        //
        //
        //        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        //        $navNtm = "<div class='col-md-12 text-left'>";
        //        $navNtm .= "<ul class='pagination'>";
        //        foreach ($navBtn as $mode => $navTmp) {
        //            $link = $navTmp['action'] . "?$navKey$date1$date2";
        //            $navTitle = $navTmp['label'];
        ////            $navNtm .= "<span class='col-md-3' style='padding: 2px'><a href=\"$link\" class='btn btn-danger'>" . $navTmp['label'] . "</a></span>";
        //            $navNtm .= "<li><a href='$link' class=\"text-white bg-light-blue\" data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>$navTitle</a></li>";
        //        }
        //
        //        $navNtm .= "</ul>";
        //        $navNtm .= "</div>";
        //endregion

        //region navigation 2

        //        $navBtn = "<h3>Pilih Periode</h3>";
        //        $navBtn .= "<select id='periode' onchange=\"period_url()\" class='form-control'>";
        //        foreach ($periode as $key => $pLabel) {
        //            $selected = $key == $selectedField ? "selected" : "";
        ////arrPrint($pLabel);
        ////            $navLink = $thisPage . "/$method?nav2=$key$date1$date2";
        //            $navLink = base_url() . $this->uri->segment(1) . "/" . $pLabel['method'] . "/" . $this->uri->segment(3) . "?nav2=$key$date1$date2";
        ////            $navLink = "$thisPage/".$pLabel['method']."/".$this->uri->segment(3)."?nav2=$key$date1$date2";
        ////            $navLink = "https://google.com";
        //            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">" . $pLabel['label'] . "</option>";
        ////            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">".$navLink."</option>";
        //        }
        //        $navBtn .= "</select>";
        //        $navBtn .= "
        //        <script>
        //            var period_url = function(){
        //                var url = $('option:selected', $('#periode')).attr('url');
        //                window.location.href=url
        //            }
        //        </script>
        //        ";
        //endregion

        $daterange = "<table class='table table-condensed no-padding no-border'>
                        <tr>
                            <td>
                                <span class='glyphicon glyphicon-calendar'></span>
                                <label for='date1'>start date</label>
                            </td>
                            <td>
                                <input id='date1' class='form-control' data-date='' data-date-format='DD/MM/YYYY' type='date' value='" . $navGate['date1'] . "' min='{date_min}' max='{date_max}'>
                            </td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-default btn-block' href='javascript:void(0)' onclick='location.href='$thisPage/$method';'>
                                    <span class='glyphicon glyphicon-remove'></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class='glyphicon glyphicon-calendar'></span>
                                <label for='date2'>to date</label>
                            </td>
                            <td>
                                <input id='date2' class='form-control' data-date='' data-date-format='DD/MM/YYYY' type='date' value='" . $navGate['date2'] . "' min='{date_min}' max='{date_max}'>
                            </td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-primary btn-block' href='javascript:void(0)' onclick='location.href='$thisPage/$method?date1='+document.getElementById('date1').value+'&date2='+document.getElementById('date2').value+'&nav2='+$('#periode').val();'>
                                    <span class='fa fa-arrow-right'></span>
                                </a>
                            </td>
                        </tr>
                      </table>";
        $cotentList = "";

        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (sizeof($itemsMain) > 0) {
                //                $title = $title
                //                $sbTitle = $itemsMain['1']['subtitle'];
                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text-bold'>$title</div>";
                $cotentList .= "<div><span>$subTitle</span></div>";
                $cotentList .= "<div class='panel-body table-responsive'>";

                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain'>";
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th rowspan='3' class='text-center'>No</th>";
                foreach ($headerFields as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }

                $cotentList .= "</tr>";

                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                //                arrPrint($itemsMain);
                $rr = 0;
                $cotentListFoot = "";
                $cotentListFoot = "";
                if (sizeof($itemsMain) > 0) {
                    //                    $itemValues = $itemsMain;
                    //                    arrPrint($itemValues);
                    $i = 0;
                    foreach ($itemsPeriode as $kol => $alias) {
                        $i++;
                        $cotentList .= "<tr>";
                        $cotentList .= "<td>$i</td>";
                        $cotentList .= "<td>$alias</td>";
                        foreach ($indexHeader as $k => $alias) {
                            $val = isset($itemsMain[$kol][$k]) ? number_format($itemsMain[$kol][$k]) : 0;
                            $cotentList .= "<td class='text-right'>$val</td>";

                        }
                    }
                    $cotentList .= "</tr>";
                    //$cotentListFoot
                    $ii = sizeof($headerFields) + 1;
                    $j = 1;
                    for ($j; $j <= $ii; $j++) {
                        $cotentListFoot = "<td>-</td>";
                    }
                    //                    cekHitam($ii);
                }


                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";

                $cotentList .= "\n<script>
                    $('#itemsMain').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain_progress').show();
                            $('#itemsMain').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );

                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){
                                $('#itemsMain_progress').hide()
                            },500);
                            setTimeout( function(){
                                $('#itemsMain').show();
                                $('.table-responsive.itemsMain').floatingScroll();
                                $('#itemsMain').DataTable().fixedHeader.adjust();
                            },600);

                        }
                    });
                    $('.table-responsive.itemsMain').floatingScroll();
                    $('#itemsMain').DataTable().fixedHeader.adjust();
                    $(\".table-responsive.itemsMain\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain').show() },600);
                        }, 400);
                    });
                </script>";

            }
            //endregion


        }
        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left"        => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $cotentList,
            "profile_name"     => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
            //            "nav_btn" => $navNtm,
            //            "navigasi" => $navBtn,
            "url"              => $thisPage . "/$method",
            //            "date1" => $navGate['date1'],
            //            "date2" => $navGate['date2'],
            "daterange"        => $daterange,
        ));
        $p->render();
        break;
    case "viewReport":
        $sbTitle = $subTitle;
        // region navigasi
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $selectedField = $navGate['nav2'];
        $date1 = isset($navGate['date1']) ? "&date1=" . $navGate['date1'] : "&date1=" . date("Y-m-d");
        $date2 = isset($navGate['date2']) ? "&date2=" . $navGate['date2'] : "&date2=" . date("Y-m-d");
        $navKey = isset($navGate['nav2']) ? "&nav2=" . $navGate['nav2'] : "&nav2=cabang";

        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $navNtm = "<div class='col-md-12 text-left'>";
        $navNtm .= "<ul class='pagination'>";
        foreach ($navBtn as $modess => $navTmp) {
            $modess = $modess == 'salesman' ? 'seller' : $modess;
            $link = $navTmp['action'] . "?$navKey$date1$date2";
            $navTitle = $navTmp['label'];
            $cClass = $modess == $method ? 'bg-red text-bold text-white' : 'bg-light-blue text-gray';
            $navNtm .= "<li><a href='$link' class='btn btn-sm $cClass' data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>  $navTitle</a></li>";
        }
        $navNtm .= "</ul>";
        $navNtm .= "</div>";
        //endregion
        // $navNtm = "";

        //region navigation 2

        $navBtn = "<h3>Pilih Periode</h3>";
        $navBtn .= "<select id='periode' onchange=\"period_url()\" class='form-control'>";
        foreach ($periode as $key => $pLabel) {
            $selected = $key == $selectedField ? "selected" : "";
            //arrPrint($pLabel);
            //            $navLink = $thisPage . "/$method?nav2=$key$date1$date2";
            $navLink = base_url() . $this->uri->segment(1) . "/" . $pLabel['method'] . "/" . $this->uri->segment(3) . "?nav2=$key$date1$date2";
            //            $navLink = "$thisPage/".$pLabel['method']."/".$this->uri->segment(3)."?nav2=$key$date1$date2";
            //            $navLink = "https://google.com";
            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">" . $pLabel['label'] . "</option>";
            //            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">".$navLink."</option>";
        }
        $navBtn .= "</select>";
        $navBtn .= "
        <script>
            var period_url = function(){
                var url = $('option:selected', $('#periode')).attr('url');
                window.location.href=url
            }
        </script>
        ";
        //endregion
        $navNtm = "";

        $daterange = "<table class='table table-condensed no-padding no-border'>
                        <tr>
                            <td>
                                <span class='glyphicon glyphicon-calendar'></span>
                                <label for='date1'>Pilih Bulan</label>
                            </td>
                            <td>
                                <input id='date1' class='form-control' data-date='' data-date-format='DD/MM/YYYY' type='month' value='" . $navGate['date1'] . "' min='{date_min}' max='{date_max}'>
                            </td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-default btn-block' href='javascript:void(0)' onclick=\"location.href='$thisPage/$method';\">
                                    <span class='glyphicon glyphicon-remove'></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td align='left' valign='middle'>
                                <a class='btn btn-primary btn-block' href='javascript:void(0)' onclick=\"location.href='$thisPage/$method?date1='+document.getElementById('date1').value+'&nav2='+$('#periode').val();\">
                                    <span class='fa fa-arrow-right'></span>
                                </a>
                            </td>
                        </tr>
                      </table>";
        // cekPink($itemsMain['1']);
        $cotentList = "";
        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            // sizeof($itemsMain[1]['mainData'])
            if (isset($itemsMain['1']['mainData']) && (sizeof($itemsMain['1']['mainData']) > 10)) {
            // if (isset($itemsMain['1'])) {
                $title = $itemsMain['1']['title'];
                $sbTitle = $itemsMain['1']['subtitle'];
                $headerCount = isset($headerFields[1]['headerField']) ? sizeof($headerFields[1]['headerField']) : 0;

                //            $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title**</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain'>";

                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th rowspan='3' class='text-center'>No</th>";
                foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                $colspan2 = (sizeof($itemsPeriode) * 2) + 2;
                $cotentList .= "<th colspan='$colspan2' class='text-center'>Penjualan(netto)</th>";
                $cotentList .= "</tr>";

                if (sizeof($itemsPeriode) > 0) {
                    $colspan2 = sizeof($itemsPeriode);
                    $colspan3 = sizeof($headerFields['1']['header2']);
                    $cotentList .= "<tr>";
                    foreach ($itemsPeriode as $periode) {
                        $cotentList .= "<th class='text-center' colspan='$colspan3'>$periode</th>";
                    }
                    $cotentList .= "<th colspan='2' class='text-center' rowspan='1'>konsolidasi</th>";
                    $cotentList .= "</tr>";
                    $cotentList .= "<tr>";
                    foreach ($itemsPeriode as $periode) {
                        foreach ($headerFields['1']['header2'] as $kol => $alias) {
                            $cotentList .= "<th style='min-width:60px;' class='text-capitalize text-center'>$alias</th>";
                        }
                    }
                    foreach ($headerFields['1']['header2'] as $kol => $alias) {
                        $cotentList .= "<th style='min-width:60px;' class='text-capitalize text-center'>$alias</th>";
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</thead>";

                $cotentList .= "<tbody>";
                //                arrPrint($itemsMain[1]['mainValues']);
                $rr = 0;
                $cotentListFoot = "";
                if (sizeof($itemsMain[1]['mainData']) > 0) {
                    $itemValues = $itemsMain[1]['mainValues'];
                    //                    arrPrint($headerFields[1]['header2']);
                    foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                        $i = 0;
                        if (isset($itemsMain[1]['mainData'][$kol])) {
                            foreach ($itemsMain[1]['mainData'][$kol] as $kID => $kData) {
                                $i++;
                                $cotentList .= "<tr>";
                                $cotentList .= "<td>$i</td>";
                                foreach ($kData as $kk_key => $labelName) {
                                    $cotentList .= "<td>$labelName</td>";
                                }

                                foreach ($itemsPeriode as $periode => $periodeLabel) {
                                    foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                        $val = isset($itemValues[$kID][$periode][$kol]) ? $itemValues[$kID][$periode][$kol] : 0;
                                        $cotentList .= "<td class='text-right'>" . number_format($val) . "</td>";
                                    }
                                }
                                //region subtotal kaanan
                                $itemSubValues = $itemsMain[1]['mainSumValues'];
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    $sub_val = isset($itemSubValues[$kID]['subtotal'][$kol]) ? $itemSubValues[$kID]['subtotal'][$kol] : 0;
                                    $cotentList .= "<td class='text-right text-bold'>" . number_format($sub_val) . "</td>";
                                }
                                //endregion
                                $cotentList .= "</tr>";
                            }
                            if (isset($itemsMain[1]['sumFooter'])) {
                                //                                arrPrint($itemsMain[1]['sumfield']);
                                //                                arrPrint(sizeof($headerFields['1']['headerField']));
                                $colspanf = sizeof($headerFields['1']['headerField']) + 1;
                                $footerValue = $itemsMain[1]['sumFooter'];
                                $cotentListFoot = "<tr>";
                                //                                foreach ($itemsMain[1]['sumfield']['footer'] as $kol => $valAlias) {
                                $cotentListFoot .= "<td colspan=''>-</td>";

                                $d = 0;
                                for ($d; $d <= $colspanf; $d++) {
                                    $cotentListFoot .= "<td colspan=''>-</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        //                                        $val = isset($footerValue[$kol][$periode]) ? $footerValue[$kol][$periode] : "-";
                                        $cotentListFoot .= "<td class='text-right text-bold'>-</td>";
                                    }
                                }
                                $cotentList .= "</tr>";
                            }

                            //                            }
                        }
                    }
                }


                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //            $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                    $('#itemsMain').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain_progress').show();
                            $('#itemsMain').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );

                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){
                                $('#itemsMain_progress').hide()
                            },500);
                            setTimeout( function(){
                                $('#itemsMain').show();
                                $('.table-responsive.itemsMain').floatingScroll();
                                $('#itemsMain').DataTable().fixedHeader.adjust();
                            },600);

                        }
                    });
                    $('.table-responsive.itemsMain').floatingScroll();
                    $('#itemsMain').DataTable().fixedHeader.adjust();
                    $(\".table-responsive.itemsMain\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain').show() },600);
                        }, 400);
                    });
                </script>";
                }

            }
            //endregion

            //region conten chil1
            if (isset($itemsMain[2]) && sizeof($itemsMain['2']['mainData']) > 0) {
                $header2Merger = $itemsMain['2']['paramPeriode'] + array("subtotal" => "konsolidasi");
                $headerCount = isset($header2Merger) ? sizeof($header2Merger) : 0;

                $title = $itemsMain['2']['title'];
                $colspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";
                $rowspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3> $title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";
                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain2', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || sizeof($itemsMain[1]['mainData']['subject_id']) > 100) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain2'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain2'>";
                }
                // $cotentList .= "<div class='table-responsive'>";
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain2'>";
                //region header
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='$rowspan'>No.</th>";
                foreach ($headerFields['2']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='$rowspan' class='text-center'>$alias</th>";
                }
                if (sizeof($itemsMain['2']['mainIndex2'])) {
                    $colspan2 = sizeof($headerFields['2']['header2']) * sizeof($itemsMain['2']['mainIndex2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";

                    }
                    //                    $cotentList .= "<td rowspan='' colspan='$colspan2' class='text-center'>Total</td>";
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['2']['header2']) > 0) {
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsMain['2']['mainIndex2']);
                    foreach ($header2Merger as $periode) {
                        foreach ($headerFields['2']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th class='text-bold text-center' colspan='$colspan3'>$ixLabel </th>";
                        }
                    }

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($headerFields['2']['header2']);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['2']['paramPeriode'] as $kol => $valAlias) {
                            foreach ($headerFields['2']['header2'] as $alias) {
                                foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                    $cotentList .= "<th class='text-bold text-center' colspan=''>$cidLabel</th>";
                                }
                            }


                        }
                        //subtotal
                        foreach ($headerFields['2']['header2'] as $alias) {
                            foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                $cotentList .= "<th class='text-bold text-center' colspan=''>$cidLabel</th>";
                            }
                        }


                        $cotentList .= "</tr>";
                    }


                }
                $cotentList .= "</thead>";
                //endregion
                $cotentList .= "<tbody>";
                //region data
                $i = 0;
                foreach ($itemsMain['2']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['2']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";

                    foreach ($headerFields['2']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $periodID => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                $val = isset($dataValues[$periodID][$kk][$cID]) ? $dataValues[$periodID][$kk][$cID] : 0;
                                $cotentList .= "<td rowspan='' colspan='' class='text-right'>" . number_format($val) . "</td>";
                                //                                $cotentList .="<td>$pID $periodID $kk $cID</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";

                }
                $cotentList .= "</tbody>";

                //subtotal bawah
                if (sizeof($itemsMain['2']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['2']['sumFooter']);
                    $sumValues = $itemsMain['2']['sumFooter'];
                    $fColspan = sizeof($headerFields['2']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    //                    $cotentListFoot .= "<td colspan=''>-</td>";
                    //                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsMain['2']['mainIndex2'] as $cID => $cidLabel) {
                                $val = isset($sumValues[$Cid][$kk][$cID]) ? number_format($sumValues[$Cid][$kk][$cID]) : "-";
                                $cotentListFoot .= "<td rowspan='' colspan=''>$val</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }
                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                // $cotentList .= "</div>";

                //                $cotentList .= "<div id='itemsMain2_progress' class='text-center'><img src='".base_url()."public/images/sys/loader-2.gif'></div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";
                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                    $('#itemsMain2').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain2', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain2_progress').show();
                            $('#itemsMain2').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain2_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain2').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){ $('#itemsMain2_progress').hide() },500);
                            setTimeout( function(){
                                $('#itemsMain2').show();
                                $('.table-responsive.itemsMain2').floatingScroll();
                                $('#itemsMain2').DataTable().fixedHeader.adjust();
                            },600);

                        }
                    });
                    $('.table-responsive.itemsMain2').floatingScroll();
                    $(\".table-responsive.itemsMain2\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain2').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain2_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain2').show() },600);
                        }, 100);
                    });
                </script>";
                }

            }
            //endregion
// cekBiru($itemsMain);
           //region conten chil2
            if (isset($itemsMain[3]) && sizeof($itemsMain[3]) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $headerCount = isset($headerFields[3]['headerField']) ? sizeof($headerFields[3]['headerField']) : 0;

                $title = $itemsMain['3']['title'];
                $colspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                $rowspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";

                //                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";
                $cotentList .= "<div class='container-fluid'>";
                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div><span>$sbTitle</span></div>";

                if ($headerCount > 10 || (isset($itemsMain[1]['mainData']['subject_id']) && sizeof($itemsMain[1]['mainData']['subject_id']) > 100)) {
                    $cotentList .= "<div class='btn btn-sm btn-primary' onclick=\"tableToExcel('itemsMain3', 'id', '$sbTitle.xls')\"><i class='fa fa-download'></i> Download Excel</div>";
                    $cotentList .= "<div class='meta'>table terlalu besar, silahkan export ke excel untuk memudahkan pengecekan.</div>";
                }

                $cotentList .= "</div>";

                if ($headerCount > 10 || (isset($itemsMain[1]['mainData']['subject_id']) && sizeof($itemsMain[1]['mainData']['subject_id']) > 100)) {
                    $cotentList .= "<div style='max-height: 300px;' class='panel-body table-responsive itemsMain3'>";
                }
                else {
                    $cotentList .= "<div class='panel-body table-responsive itemsMain3'>";
                }
                $cotentList .= "<table class='table table-bordered compact nowarp order-column' id='itemsMain3'>";
                //region header

                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th class='' rowspan='3'>No.</th>";
                foreach ($headerFields['3']['headerField'] as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text-center'>$alias</th>";
                }
                if (sizeof($itemsMain['3']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode);
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        $cotentList .= "<th rowspan='' colspan='$colspan2' class='text-center'>$alias</th>";
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['3']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {
                        foreach ($headerFields['3']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<th colspan='$colspan3'>$ixLabel</th>";
                        }
                    }
                    $cotentList .= "</tr>";
                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {
                            foreach ($headerFields[3]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<th class='text-center'>$periode</th>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                $cotentList .= "</thead>";
                //endregion

                //region data
                //                arrPrint($itemsMain['3']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['mainValues']);
                $cotentList .= "<tbody>";
                $i = 0;
                foreach ($itemsMain['3']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['3']['mainValues'][$pID];
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";
                    foreach ($headerFields['3']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $cotentList .= "</tbody>";
                //endregion
                //                //subtotal bawah
                if (sizeof($itemsMain['3']['sumFooter']) > 0) {
                    $sumValues = $itemsMain['3']['sumFooter'];
                    $fColspan = sizeof($headerFields['3']['headerField']) + 1;
                    $cotentListFoot = "<tr>";
                    $d2 = 1;
                    for ($d2; $d2 <= $fColspan; $d2++) {
                        $cotentListFoot .= "<td colspan=''>-</td>";
                    }
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                $val = isset($sumValues[$Cid][$kk][$periode]) ? $sumValues[$Cid][$kk][$periode] : 0;
                                $cotentListFoot .= "<td rowspan='' colspan=''>" . number_format($val) . "</td>";
                            }
                        }
                    }
                    $cotentListFoot .= "</tr>";
                }
                //                //endregion

                $cotentList .= "<tfoot>";
                $cotentList .= $cotentListFoot;
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                //                $cotentList .= "</div>";

                if ($headerCount > 10) {

                }
                else {
                    $cotentList .= "\n<script>
                        $('#itemsMain3').DataTable({
                        dom: 'lBfrtip',
                        fixedHeader: true,
                        iDisplayLength: -1,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        buttons: [
                            {
                                text: 'Export to Excel',
                                action: function ( e, dt, node, config ) {
                                    tableToExcel('itemsMain3', 'id', '$sbTitle.xls')
                                }
                            }
                        ],
                        fnPreDrawCallback:function(){
                            $('#itemsMain3_progress').show();
                            $('#itemsMain3').hide();
                        },
                        fnInitComplete:function(){
                            setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain3').show() },600);
                        },
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                        $( api.column( i ).footer() ).addClass('text-bold');
                                    }
                                    else if(parseFloat(pageTotal)==0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).removeClass('text-center');
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }else{

                                    }
                            })
                            setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                            setTimeout( function(){
                                $('#itemsMain3').show();
                                $('.table-responsive.itemsMain3').floatingScroll();
                                $('#itemsMain3').DataTable().fixedHeader.adjust();
                            },600);
                        }
                    });
                    $('.table-responsive.itemsMain3').floatingScroll();
                    $(\".table-responsive.itemsMain3\").scroll(function () {
                        setTimeout(function () {
                            $('#itemsMain3').DataTable().fixedHeader.adjust();
                            setTimeout( function(){ $('#itemsMain3_progress').hide() },500);
                            setTimeout( function(){ $('#itemsMain3').show() },600);
                        }, 100);
                    });
                    </script>";
                }
            }
           //endregion conten chil2
        }
        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left"        => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $cotentList,
            "profile_name"     => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn"          => $navNtm,
            // "navigasi"         => $navBtn,
            "navigasi"         => "",
            "url"              => $thisPage . "/$method",
            "date1"            => $navGate['date1'],
            "date2"            => $navGate['date2'],
            // "daterange"        => $daterange,
            "daterange"        => "",
        ));
        $p->render();

        break;
}