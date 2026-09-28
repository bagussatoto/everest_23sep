<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "index":
        $arrBlacklist = array(
            "no",
        );
        $stepper = isset($_GET['step']) ? $_GET['step'] : 1;
        if (isset($_GET['step'])) {
//            arrPrint($steps[$_GET['step']]);
        }

//        arrPrintWebs($steps);

//        arrPrintWebs($arrayOnProgress);
//        arrPrintWebs($arrayOnprogressGroup);

//        arrPrint($arrayOnprePre);
//        arrPrint($arrayOnprePreGroup);

//        arrPrintWebs($arrayOnpreDistribution);
//        arrPrintWebs($arrayOnpreDistributionGroup);

//        arrPrintWebs($arrayOnprogressGroup[$stepper]);
//
//        matiHere();

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }


        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/transaksi_index.html");

        $strOnprePre = "";
        if (isset($arrayOnprePre)) {
            //region prepre
            $strOnprePre = "";
            if (sizeof($arrayOnprePre) > 0) {
                $strOnprePre .= "<table id='arrayOnprePre' class='01 table table-condensed table-bordered no-padding'>";

                $strOnprePre .= "<thead>";
                $strOnprePre .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayprePreLabels) > 0) {
                    foreach ($arrayprePreLabels as $key => $label) {
                        $strOnprePre .= "<td class='text-muted'>";
                        $strOnprePre .= $label;
                        $strOnprePre .= "</td>";
                    }
                }
                $strOnprePre .= "</tr>";
                $strOnprePre .= "</thead>";
                $strOnprePre .= "<tbody>";

                foreach ($arrayOnprePre as $key => $val) {
                    $strOnprePre .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayprePreLabels) > 0) {
                        foreach ($arrayprePreLabels as $key => $label) {
                            $strOnprePre .= "<td>";
                            $strOnprePre .= $val[$key];
                            $strOnprePre .= "</td>";
                        }
                    }
                    $strOnprePre .= "</tr>";
                }
                $strOnprePre .= "</tbody>";
                $strOnprePre .= "</table>";

                $strOnprePreFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
            }
            else {
                $strOnprePre = "-the item you specified has no entry-";
                $strOnprePreFooter = "";
            }
            //endregion
        }

        if (isset($arrayOnProgressToPay) && $arrayOnProgressToPay == true) {
//            cekHere($arrayOnProgressToPay);
        }
        else {
            //region onprogress
            $strOnprog = "";
            $switchToHistory = count($steps) == $stepper ? "History" : "";

            if (sizeof($steps) > 1) {
                $strOnprog .= "<ul class='nav nav-tabs'>";
                foreach ($steps as $tStep => $stepData) {
                    $isiBadge = isset($arrayOnprogressGroup[$tStep]) ? "<span class='badge bg-red'>" . sizeof($arrayOnprogressGroup[$tStep]) . "</span>" : "";
                    $actives = $tStep == $stepper ? "active" : "";
                    $trSelesai = count($steps) == $tStep ? "SELESAI<br>" : "";

                    $cssSelesai = count($steps) == $tStep ? "style='padding-top: 0;padding-bottom: 0;'" : "";
                    $strOnprog .= "<li class='$actives'>";
                    $strOnprog .= "<a $cssSelesai class='nav-link btn' onclick=\"location.href='" . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?step=$tStep';\">";
                    $strOnprog .= "<span class='text-uppercase text-bold'>$trSelesai" . $stepData['label'] . "</span>  $isiBadge </a>";
                    $strOnprog .= "</li>";
                }
                $strOnprog .= "</ul>";

                $strOnprog .= "<div class='clearfix'>&nbsp;</div>";
            }

            if (isset($arrayOnprogressGroup[$stepper]) && sizeof($arrayOnprogressGroup[$stepper]) > 0) {
                $arrayOnProgress = $arrayOnprogressGroup[$stepper];
            }

            if (sizeof($arrayOnProgress) > 0) {

//                $strOnprog .= "<div class='table-responsive'>";
                $strOnprog .= "<table id='arrayOnProgress$switchToHistory' class='table stripe compact nowarp order-column table-condensed table-bordered no-padding'>";
                $strOnprog .= "<thead>";
                $strOnprog .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
                if (sizeof($arrayProgressLabels) > 0) {
                    $strOnprog .= "<th class=''>No.</th>";
                    foreach ($arrayProgressLabels as $key => $label) {
                        $strOnprog .= "<th class=''>";
                        if (is_array($label)) {
                            $strOnprog .= isset($label['label']) ? $label['label'] : "-";
                        }
                        else {
                            $strOnprog .= $label;
                        }
                        $strOnprog .= "</th>";
                    }
                }
                $strOnprog .= "</tr>";
                $strOnprog .= "</thead>";
                $strOnprog .= "<tbody>";

                if (count($steps) == $stepper) {

                }
                else {
                    $no = 0;
                    foreach ($arrayOnProgress as $key => $val) {
                        $no++;
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        $strOnprog .= "<td>$no</td>";
                        if (sizeof($arrayProgressLabels) > 0) {
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                $strOnprog .= $val[$key];
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }
                }


                $strOnprog .= "</tbody>";


                if (isset($sumFooter) && sizeof($sumFooter) > 0) {
                    $strOnprog .= "<tfoot>";
                    $strOnprog .= "<tr line=" . __LINE__ . ">";

                    if (count($steps) == $stepper) {

                    }
                    else {
                        if (sizeof($arrayProgressLabels) > 0) {
                            $strOnprog .= "<td>-</td>";
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                if (isset($sumFooter) && isset($sumFooter[$key])) {
                                    $strOnprog .= $sumFooter[$key];
                                }
                                else {
                                    $strOnprog .= "-";
                                }
                                $strOnprog .= "</td>";
                            }
                        }
                    }

                    $strOnprog .= "</tr>";
                    $strOnprog .= "</tfoot>";
                }

                $strOnprog .= "</table>";
                $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
            }
            else {
                $strOnprog = "-the item you specified has no entry-";
                $strOnprogFooter = "";
            }
            //endregion
        }


        //region onprogressView Doank
        $strOnprogView = "";
        if (is_array($arrayOnProgressView) && sizeof($arrayOnProgressView) > 0) {
            $strOnprogView .= "<table class='table table-condensed table-bordered no-padding'>";
            $strOnprogView .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($stepHistoryFields) > 0) {
                foreach ($stepHistoryFields as $key => $label) {
                    $strOnprogView .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strOnprogView .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strOnprogView .= $label;
                    }
                    $strOnprogView .= "</td>";
                }
            }
            $strOnprogView .= "</tr>";
            foreach ($arrayOnProgressView as $key => $val) {
                $strOnprogView .= "<tr line=" . __LINE__ . ">";
                if (sizeof($stepHistoryFields) > 0) {
                    foreach ($stepHistoryFields as $key => $label) {
                        $strOnprogView .= "<td>";
                        $strOnprogView .= isset($val[$key]) ? $val[$key] : "";
                        $strOnprogView .= "</td>";
                    }
                }
                $strOnprogView .= "</tr>";
            }
            $strOnprogView .= "</table>";
//            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";

            $onpropDisplayView = "block";
        }
        else {
            $strOnprogView .= "-the item you specified has no entry-";
            $strOnprogFooter = "";
            $onpropDisplayView = "none";
        }
        //endregion


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table id='arrayHistory' class='03 table table-condensed table-bordered no-padding'>";

            $strHist .= "<thead>";
            $strHist .= "<tr line=" . __LINE__ . ">";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strHist .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strHist .= $label;
                    }
                    $strHist .= "</td>";
                }
            }
            $strHist .= "</tr>";
            $strHist .= "</thead>";
            $strHist .= "<tbody>";

            foreach ($arrayHistory as $key => $val) {
                // print_r($val);
                $strHist .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $strHist .= $val[$key];
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }
            $strHist .= "</tbody>";

            $strHist .= "</table>";

            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

        $strRecap = "";
        //region recap
        if (sizeof($arrayRecap) > 0) {
            $strRecap .= "<div class='table-responsive'>";
            $strRecap .= "<table id='arrayRecap' class='table table-condensed table-bordered no-padding'>";

            $strRecap .= "<thead>";
            $strRecap .= "<tr line=" . __LINE__ . ">";
            if (sizeof($arrayRecapLabels) > 0) {
                foreach ($arrayRecapLabels as $key => $label) {
                    $strRecap .= "<td class='text-muted'>";
                    $strRecap .= $label;
                    $strRecap .= "</td>";
                }
            }
            $strRecap .= "</tr>";
            $strRecap .= "</thead>";
            $strRecap .= "<tbody>";
            foreach ($arrayRecap as $key => $val) {
                $strRecap .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayRecapLabels) > 0) {
                    foreach ($arrayRecapLabels as $key => $label) {
                        $strRecap .= "<td>";
                        $strRecap .= $val[$key];
                        $strRecap .= "</td>";
                    }
                }
                $strRecap .= "</tr>";
            }
            $strRecap .= "</tbody>";

            $strRecap .= "</table>";
            $strRecap .= "</div>";

            $strRecapFooter = "<a class='btn btn-default' href='" . base_url() . "ActivityReport/viewMonthly/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {
            $strRecap = "-the item you specified has no entry-";
            $strRecapFooter = "";
        }
        //endregion

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        if (isset($arrayOnProgressToPay) && $arrayOnProgressToPay == true) {
            $propDisplay = "block";
            $altDisplay = "none";
            $addLinkStr = "";
            $scriptBottom = "";
            $items = sizeof($arrayOnProgress) > 0 ? $arrayOnProgress : array();
            $strOnprog = "";
            if (sizeof($items) > 0) {
                $strOnprog .= "<table id='arrayOnProgressToPay' class='table cTable table-condensed table-striped table-bordered'>";
                $strOnprog .= "<thead>";
                $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                if (sizeof($itemLabels) > 0) {
                    $strOnprog .= "<th class='text-muted text-right'>";
                    $strOnprog .= "No.";
                    $strOnprog .= "</th>";
                    foreach ($itemLabels as $key => $label) {
                        $strOnprog .= "<th class='text-capitalize text-muted'>";
                        $strOnprog .= $label;
                        $strOnprog .= "</th>";
                    }
                }
                $strOnprog .= "</tr>";
                $strOnprog .= "</thead>";
                $strOnprog .= "<tbody>";
                $no = 0;
                foreach ($items as $key => $val) {
                    $no++;
                    $strOnprog .= "<tr line=" . __LINE__ . ">";
                    $strOnprog .= "<td align='right' class='" . $val['class_marking'] . "'>$no</td>";
                    if (sizeof($itemLabels) > 0) {
                        foreach ($itemLabels as $key => $label) {
//                        cekHere($key);
                            $classMarking = "";
                            $strOnprog .= "<td data-order='" . $val[$key] . "' class='" . $val['class_marking'] . "'>";
                            $strOnprog .= "<a href='javascript:void(0)' title='make a $title with " . $val['extern_nama'] . "' data-toggle='tooltip' data-placement='right' onclick=\"top.BootstrapDialog.show(
                                   {
                                       title:'$title - " . $val['extern_nama'] . "',
                                       message: " . '$' . "('<div></div>').load('" . $val['link'] . "'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                        \" >";
                            $strOnprog .= formatField($key, $val[$key]);
                            $strOnprog .= "</a>";
                            $strOnprog .= "</td>";
                            if (is_numeric($val[$key])) {
                                if (!isset($total[$key])) {
                                    $total[$key] = 0;
                                }
                                $total[$key] += $val[$key];
                            }
                        }
                    }
                    $strOnprog .= "</tr>";
                }
                $strOnprog .= "</tbody>";
                $strOnprog .= "<tfoot>";
                $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                $strOnprog .= "<td></td>";
                $strOnprog .= "<td class='text-muted'>total amount of '$srcLabel'</td>";
                foreach ($itemLabels as $key => $label) {
                    if (isset($total[$key])) {
                        $strOnprog .= "<td class='text-muted'>";
                        $strOnprog .= formatField($key, $total[$key]);
                        $strOnprog .= "</td>";
                    }
                }
                $strOnprog .= "</tr>";
                $strOnprog .= "</tfoot>";
                $strOnprog .= "</table>";
                $scriptBottom .= "
            <script>
                $(document).ready( function(){

//                    console.log('mode datatable activated');

                    var table = $('.table.cTable').DataTable({
                                    stateSave: false,
                                    order: [[ 8, 'desc' ]],
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        // Total over all pages
                                        var total2=0;
                                        jQuery.each( $(api.column(2).data()), function(i, obj){
                                            total2 += intVal( $('span', obj).html() );
                                        });

                                        var total3=0;
                                        jQuery.each( $(api.column(3).data()), function(i, obj){
                                            total3 += intVal( $('span', obj).html() );
                                        });

                                        var total4=0;
                                        jQuery.each( $(api.column(4).data()), function(i, obj){
                                            total4 += intVal( $('span', obj).html() );
                                        });

                                        var total5=0;
                                        jQuery.each( $(api.column(5).data()), function(i, obj){
                                            total5 += intVal( $('span', obj).html() );
                                        });


                                        // Total over this page
                                        pageTotal2 = api
                                            .column( 2, { page: 'current'} )
                                            .data()
                                            .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                            }, 0 );


                                        var pageTotal2=0;
                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
                                            pageTotal2 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal3=0;
                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
                                            pageTotal3 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal4=0;
                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
                                            pageTotal4 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal5=0;
                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
                                            pageTotal5 += intVal( $('span', obj).html() );
                                        });


                                        // Update footer
                                        $( api.column( 2 ).footer() ).html(
                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
                                        );

                                        $( api.column( 3 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
                                        );

                                        $( api.column( 4 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
                                        );

                                        $( api.column( 5 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
                                        );

                                    }
                                });

                    table.on( 'draw', function () {
                        var body = $( table.table().body() );
                        body.unhighlight();
                        body.highlight( table.search() );
//                        console.log('highlight');
                    } );
            new $.fn.dataTable.FixedHeader( table );
                });
            </script>";

            }
            else {
                $strOnprog = "-the item you specified has no entry-";
            }
        }
        else {
            if (sizeof($arrayOnProgress) > 0) {
                $propDisplay = "block";
                $altDisplay = "none";
            }
            else {
                $propDisplay = "none";
                $altDisplay = "block";
            }
        }

        $scriptBottom .= "

        <script>

    $(document).ready( function () {
        var arrayOnprePre;
        var arrayOnProgress;
        var arrayHistory;
        var arrayRecap;
//        var arrayOnProgressToPay;

//         arrayOnProgressToPay = $('#arrayOnProgressToPay').DataTable({
//            pageLength: -1,
//            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
////            sorting: false,
////            searching: false,
////            pageResize: true,
//            scrollY: 'calc(100%-400px)',
//            scrollX: true,
//            scrollCollapse: false,
////            fixedColumns:   {
////                leftColumns: 3,
////                rightColumns: 1
////            },
//            buttons: [
//                {
//                    extend: 'print',
//                    footer: true
//                },
//                {
//                    extend: 'excel',
//                    text: 'Excel',
//                    exportOptions: {
//                        modifier: {
//                            page: 'current'
//                        }
//                    }
//                }
//            ],
//        });

        arrayRecap = $('#arrayRecap').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
            scrollY: 'calc(100%-400)',
            scrollX: true,
            scrollCollapse: false,
//            fixedColumns:   {
//                leftColumns: 3,
//                rightColumns: 1
//            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        arrayHistory = $('#arrayHistory').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
//            scrollY: '-webkit-calc(100%-400px)',
//            scrollX: true,
//            scrollCollapse: false,
//            fixedColumns:   {
//                leftColumns: 3,
//                rightColumns: 1
//            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        if( $('#arrayHistory').length ) { new $.fn.dataTable.FixedHeader( arrayHistory ); }

        arrayOnProgress = $('#arrayOnProgress').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
//            scrollY: '-webkit-calc(100%-400)',
//            scrollX: true,
//            scrollCollapse: false,
//            fixedColumns:   {
//                leftColumns: 4,
//                rightColumns: 1
//            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        if( $('#arrayOnProgress').length ) { new $.fn.dataTable.FixedHeader( arrayOnProgress ); }
        top.$('.dataTables_scrollBody').floatingScroll();

        $( \".dataTables_scrollBody\" ).scroll(function() {
            console.log('sekeroll');
            setTimeout( function(){
                $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
            }, 400);
        });

        arrayOnprePre = $('#arrayOnprePre').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
            scrollY: 'calc(100%-400px)',
            scrollX: true,
            scrollCollapse: false,
            fixedColumns:   {
                leftColumns: 3,
                rightColumns: 1
            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        $( \".box-body\" ).scroll(function() {
            console.log('sekeroll');
            setTimeout( function(){
                $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
            }, 400);
        });

        top.$('.box-body').floatingScroll();

        $(\".DTFC_RightBodyLiner\").css(\"overflow\", \"hidden\");
        $(\".DTFC_RightWrapper\").css(\"right\", \"0px\");
        $(\".DTFC_RightWrapper\").css(\"width\", \"fit-content\");
        $(\".DTFC_RightFootWrapper\").hide();

    } );
        </script>";
        // cekHere($_GET['gr']);
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(
            array(
                "error_msg" => $error,
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "alt_display" => $altDisplay,
                "prop_display" => $propDisplay,

                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),

                "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",

                "onprogress_title" => $onprogressTitle,
                "onprogress_content" => $strOnprog,
                "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => $strOnprogView,
                "onprop_display_view" => $onpropDisplayView,

                "add_link" => $addLinkStr,
                "history_title" => $historyTitle,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                "recap_title" => $recapTitle,
                "recap_content" => $strRecap,
                "recap_footer" => $strRecapFooter,
                "profile_name" => $this->session->login['nama'],
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
            )
        );

        $p->render();

        break;

    case "createForm":

        //        die($allowJoin);

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        //region baca atribut, keterangan dari config
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $jenisTr = isset($jenisTr) ? $jenisTr : "";
        $jenisTransaksi = isset($jenisTransaksi) ? $jenisTransaksi : "";
        $pihakCaller = isset($pihakCaller) ? $pihakCaller : "";
        $pihakCaller2 = isset($pihakCaller2) ? $pihakCaller2 : "";
        $selectorCaller = isset($selectorCaller) ? $selectorCaller : "";
        $selectorCaller2 = isset($selectorCaller2) ? $selectorCaller2 : "";
        $selectorCallerForm = ''; // link shopping_cart pilih multi item
        $pihakCallerDelete = isset($pihakCallerDelete) ? $pihakCallerDelete : "";
        $pihakLabel = isset($pihakLabel) ? $pihakLabel : 'pilih';
        $pihakLabel2 = isset($pihakLabel2) ? $pihakLabel2 : 'pilih';
        $selectorLabel = isset($selectorLabel) ? $selectorLabel : 'pilih';
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $template = isset($template) ? $template : "";
        $setSubmitButton = isset($setSubmitButton) ? $setSubmitButton : "LANJUTKAN";
        $submitLabel = "Continue " . $subTitle;
        //endregion baca atribut, keterangan dari config

        $p = New Layout("$title", "$subTitle", "$template");

        $strOnprog = "";
        $strOnprogFooter = "";

        //        arrprint($arrayProgressLabels);
        //        arrprint($arrayOnProgress2);

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($arrayOnProgress2) > 0) {
                //region onprogress2
                if (sizeof($arrayOnProgress2) > 0) {
                    $strOnprog .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    switch ($viewMode) {
                        case "list":
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                            $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                            if (sizeof($arrayProgress2Labels) > 0) {
                                foreach ($arrayProgress2Labels as $key => $label) {
                                    $strOnprog .= "<td class='text-muted'>";
                                    $strOnprog .= $label;
                                    $strOnprog .= "</td>";
                                }
                            }
                            $strOnprog .= "</tr>";
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<td>";
                                        $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                        $strOnprog .= "</td>";
                                    }
                                }
                                $strOnprog .= "</tr>";
                            }
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td class='alert alert-warning' colspan='" . sizeof($arrayProgress2Labels) . "' align='center'>to process <strong>by request</strong> entries, you need to clear the list above from selected items.</td>";
                                $strOnprog .= "</tr>";
                            }
                            else {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td colspan='" . sizeof($arrayProgress2Labels) . "' align='right'><button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\">followup as new $title</button></td>";
                                $strOnprog .= "</tr>";
                            }
                            $strOnprog .= "</table>";
                            break;
                        case "thumbnail":
                            $strOnprog .= "<div class='panel-body' style='background:#e5e5e0;border:2px #cccccc dashed;'>";
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered' cellspacing='4'>";
                            $strOnprog .= "<tr line=" . __LINE__ . ">";
                            $no = 0;
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $no++;
                                $strOnprog .= "<td bgcolor='#f0f0f0' align='center'>";
                                $strOnprog .= "<label for='select_" . $no . "'>";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<div class='text-center'>";
                                        $strVal = isset($val[$key]) ? ($val[$key]) : "";
                                        $strVal = is_numeric($strVal) ? number_format($strVal) : $strVal;
                                        $strOnprog .= $strVal;
                                        $strOnprog .= "</div>";
                                    }
                                }
                                $strOnprog .= "</label>";
                                $strOnprog .= "</td>";
                                if ($no % 5 == 0) {
                                    $strOnprog .= "</tr><tr line=" . __LINE__ . ">";
                                }
                            }
                            $strOnprog .= "</tr>";
                            $strOnprog .= "</table class='table table-condensed table-bordered no-padding'>";

                            $strOnprog .= "<div class='row'>";
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6'>";
                                $strOnprog .= "to process <strong>by request</strong> entries, you need to clear the list above from selected items.";
                                $strOnprog .= "</div>";
                            }
                            else {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6 text-right'>";
                                $strOnprog .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary btn-block' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup as new $title</button>";
                                $strOnprog .= "</div>";
                            }
                            $strOnprog .= "</div>";
                            $strOnprog .= "</div>";

                            break;
                    }

                    $strOnprog .= "</form>";
                }
                //endregion
            }

            if (sizeof($arrayOnProgress) > 0) {
                //region onprogress
                if (sizeof($arrayOnProgress) > 0) {
                    $strOnprog .= "<div class='panel-body'>";
                    $strOnprog .= "<h4>action needed #1</h4>";
                    $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    if (sizeof($arrayProgressLabels) > 0) {
                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<td class='text-muted'>";
                            $strOnprog .= $label;
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";

                    foreach ($arrayOnProgress as $key => $val) {
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        if (sizeof($arrayProgressLabels) > 0) {
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }

                    $strOnprog .= "</table>";
                    $strOnprog .= "<div class='text-right'>";
                    $strOnprog .= "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
                    $strOnprog .= "</div class='text-right'>";
                    $strOnprog .= "</div class='panel-body'>";


                }
                else {
                    if (isset($arrayOnProgress2) && sizeof($arrayOnProgress2) > 0) {
                        $strOnprog = "";
                        $strOnprogFooter = "";
                    }
                    else {
                        $strOnprog = "-the item you specified has no entry-";
                        $strOnprogFooter = "";
                    }

                }
                //endregion
            }

        }
        $uploadData = "";
        if (sizeof($uploadConfig) > 0) {
            $jenisTransaksi = $this->uri->segment(3);
            $labelUpload = $uploadConfig['label'];
            $uploadAction = base_url() . $uploadConfig['action'];
            $uploadData .= "<form id='uplodXls' method='post' enctype='multipart/form-data' action='$uploadAction' target='result'>";
            $uploadData .= "<input type='file' name='fileExcel' class='form-control'>";
            $uploadData .= "<input type='submit' value='upload' class='btn btn-primary'>";
            $uploadData .= "</form>";
            $uploadData .= "<script>
                    function insertItem(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        var totalProduk = parseFloat(Object.keys(dTemp).length);
                        top.$('#result').load('" . base_url() . "/Selectors/_processSelectProduct/selectNoQty/" . $jenisTransaksi . "?id='+data.id+'&minValue=0', null, function(){
//                            if(data.qty>1){
                                setTimeout( function(){ changeUnit(ls_urut, ls_concated) }, 1200);
//                            }
//                            else{
//                                setTimeout( function(){ rolling(ls_urut, ls_concated) }, 1200);
//                            }
                            
                            var ls_urut_tt = parseFloat(totalProduk) - parseFloat(ls_urut);
                            if(parseFloat(ls_urut_tt) != parseFloat(totalProduk)){
                                top.$('#totalProduk').html(parseFloat(totalProduk));
                                top.$('#progressProduk').html(parseFloat(ls_urut_tt));
                                                                console.log('totalProduk: ' + totalProduk);
//                                console.log('ls_urut: ' + ls_urut);
                                console.log('ls_urut_tt: ' + ls_urut_tt);
                            }
                            else if(parseFloat(ls_urut_tt) === parseFloat(totalProduk)){
                                top.$('#totalProduk').html(parseFloat(totalProduk));
                                top.$('#progressProduk').html(parseFloat(ls_urut_tt));
                                HoldOn.close();
                                swal('selesai upload '+parseFloat(totalProduk)+' PRODUK, silahkan diperiksa kembali sebelum disimpan')
                                console.log('selesai');
                                console.error('totalProduk: ' + totalProduk);
//                                console.log('ls_urut: ' + ls_urut);
                                console.error('ls_urut_tt: ' + ls_urut_tt);
                            }else{
                                console.log('selesai **');
                            }
                            
                        })
                    }
                    function changeUnit(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        top.$('#result').load('" . base_url() . "/Selectors/_processSelectProduct/selectNoQty/" . $jenisTransaksi . "?id='+data.id+'&newQty=&qty_opname='+data.qty, null, function(){
                            rolling(ls_urut, ls_concated);
                        })
                    }
                    
                    function rolling(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        var rl_ls_urut = (ls_urut-1);
                        

                        if(rl_ls_urut>=0){
                            setTimeout( function(){ insertItem(rl_ls_urut, ls_concated) }, 500);
                        }
                        else{
                            
                        }

                    }
                    
$('#uplodXls').on('submit',function() {
    localStorage.clear();
    
    var setInt= setInterval(function() {
      var arrProduk = JSON.parse(localStorage.getItem('items'));
            if(null!=arrProduk){
                
                var options = {
                    theme:\"custom\",
                    // If theme == \"custom\" , the content option will be available to customize the logo
                    content:'<img style=\"width:80px;\" src=\"https://www.google.de/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png\" class=\"center-block\">',
                    message:' <h4>SEDANG PROSES UPLOAD PRODUK<br>MOHON UNTUK TIDAK MEREFRESH BROWSER ANDA.</h4><br><br><h1>PROGRESS... <span class=\"text-bold text-red\" id=\"progressProduk\"></span> Produk, DARI TOTAL <span id=\"totalProduk\" class=\"text-bold text-orange\"></span> PRODUK </h1><br> <input type=\"button\" value=\"Close this Cover\" onclick=\"HoldOn.close();\">',
                    backgroundColor:\"#1847B1\",
                    textColor:\"white\"
                };
                
                top.HoldOn.open(options);
                
                clearInterval(setInt);
                var arrProduk = JSON.parse(localStorage.getItem('items'));
                var totalProduk = Object.keys(arrProduk).length;
                var urut = 1;
                var concated = [];
                var arrays = [];
                jQuery.each(arrProduk, function(id,data){
                    arrays = data;
                    arrays['id'] = id;
                    concated[urut] = arrays;
                    urut++;
                });
                localStorage.setItem('urut', '');
                localStorage.setItem('concat', '');
                localStorage.setItem('urut', (urut-2));
                localStorage.setItem('concat', JSON.stringify(concated).replace('null,', '') );
                var ls_urut = localStorage.getItem('urut');
                var ls_concated = localStorage.getItem('concat');
                insertItem(ls_urut, ls_concated);
            }
    },1000);})";


            $uploadData .= "</script>";

            arrPrint($uploadConfig);
        }

        //region onprogressView Doank
        $strOnprogView = "";
        if (is_array($arrayOnProgressView) && sizeof($arrayOnProgressView) > 0) {
            $strOnprogView .= "<table class='table table-condensed table-bordered no-padding'>";
            $strOnprogView .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($stepHistoryFields) > 0) {
                foreach ($stepHistoryFields as $key => $label) {
                    $strOnprogView .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strOnprogView .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strOnprogView .= $label;
                    }
                    $strOnprogView .= "</td>";
                }
            }
            $strOnprogView .= "</tr>";
            foreach ($arrayOnProgressView as $key => $val) {
                $strOnprogView .= "<tr line=" . __LINE__ . ">";
                if (sizeof($stepHistoryFields) > 0) {
                    foreach ($stepHistoryFields as $key => $label) {
                        $strOnprogView .= "<td>";
                        $strOnprogView .= isset($val[$key]) ? $val[$key] : "";
                        $strOnprogView .= "</td>";
                    }
                }
                $strOnprogView .= "</tr>";
            }
            $strOnprogView .= "</table>";
//            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";

            $onpropDisplayView = "block";
        }
        else {
            $strOnprogView .= "-the item you specified has no entry-";
            $strOnprogFooter = "";
            $onpropDisplayView = "none";
        }
        //endregion


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table class='table table-condensed table-bordered no-padding'>";

            $strHist .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strHist .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strHist .= $label;
                    }
                    $strHist .= "</td>";
                }
            }
            $strHist .= "</tr>";

            foreach ($arrayHistory as $key => $val) {
                // print_r($val);
                $strHist .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $strHist .= $val[$key];
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }


            $strHist .= "</table>";

            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

        //        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
        //
        //            $propDisplay = "block";
        //            $altDisplay = "none";
        //        } else {
        //
        //            $propDisplay = "none";
        //            $altDisplay = "block";
        //        }

        $propDisplay = "block";
        $altDisplay = "none";


        //        cekkuning($strOnprog);die();

        //        die("allowTmpSave:".$allowTmpSave);

        if (isset($barcodeSettings['srcModel'])) {
            $barcodeProcessor = "document.getElementById('result').src='" . base_url() . "Addons/BarcodeReader/readCode?jenisTr=$jenisTr&srcModel=" . $barcodeSettings['srcModel'] . "&srcColumn=" . $barcodeSettings['srcColumn'] . "&proc=" . blobEncode($selectorProcessor) . "&code='+this.value;";
        }
        else {
            $barcodeProcessor = "return false;";
        }
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(
            array(
                "error_msg" => $error,
                "alt_display" => $altDisplay,
                "modeedit" => $modeedit,
                "modeeditopt" => "$modeeditopt",
                "prop_display" => $propDisplay,
                "tmpsave_display" => $allowTmpSave == true ? "block" : "none",
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "pihak_caller" => $pihakCaller,
                "pihak_caller2" => $pihakCaller2,
                "pihak_caller_rules" => $pihakMainCallerRules,
                "pihak_caller3" => $pihakCaller3,
                "pihak_callerExtern" => $pihakExternCaller,
                "selector_caller" => $selectorCaller,
                "selector_callerExtern" => $pihakExternCaller,
                "selector_caller2" => isset($selectorCaller2) ? $selectorCaller2 : "",
                "selector_caller_rules" => isset($selectorCalleRules) ? $selectorCallerRules : "",
                "selector_caller3" => isset($selectorCaller3) ? $selectorCaller3 : "",
                "pihak_caller_delete" => $pihakCallerDelete,
                "pihak_main_caller_delete" => $pihakMainCallerDelete,
                "pihak_main_caller_rules_delete" => $pihakMainCallerRulesDelete,
                "selector_caller_form" => $selectorCallerForm,
                "pihak_label" => $pihakLabel,
                "pihak_label2" => isset($pihakLabel2) ? $pihakLabel2 : "",
                "pihak_label3" => isset($pihakLabel3) ? $pihakLabel3 : "",
                "selector_label" => $selectorLabel,
                "selector_label2" => isset($selectorLabel2) ? $selectorLabel2 : "",
                "selector_rules_label" => isset($selectorLabelRules) ? $selectorLabelRules : "",
                "selector_label3" => isset($selectorLabel3) ? $selectorLabel3 : "",
                "submit_button" => $submitLabel,
                "pihak_main_label" => $pihakMainLabel,
                "pihak_rules_label" => $pihakMainLabelRules,
                "pihak_main_caller" => $pihakMainCaller,
                "pihakExternLabel" => $pihakExternLabel,
                //                "clear_shopping_cart" => $setClearShoppingCart,
                //                "action_shopping_cart" => $setActionShoppingCart,
                "onprogress_content" => $strOnprog,
                "onprogress_footer" => $strOnprogFooter,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                //                "payment_str"          => $strPaymentMethod,
                "ext_tool" => $extTool,
                "column_recorder" => $columnRecorderTarget,
                "default_description" => $defaultDescription,
                "profile_name" => $this->session->login['nama'],
                "add_pihak" => $addPihakStr,
                "add_pihak_rules" => (isset($addPihakRulesStr) ? $addPihakRulesStr : ""),
                "add_item" => $addItemStr,
                "this_page" => $thisPage,
                "view_mode_switch" => $viewModeSwitch,
                "barcode_action" => $barcodeProcessor,
                "mobile_scan" => $isMobile ? $mobScanStr : "",
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => $strOnprogView,
                "onprop_display_view" => $onpropDisplayView,
                "globalTemplate" => $globalTemplate,
                "upload_item" => "$uploadData",

            )
        );

        $p->render();
        break;

    case "editForm":

//        die($allowJoin);

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        //region baca atribut, keterangan dari config
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $jenisTr = isset($jenisTr) ? $jenisTr : "";
        $jenisTransaksi = isset($jenisTransaksi) ? $jenisTransaksi : "";
        $pihakCaller = isset($pihakCaller) ? $pihakCaller : "";
        $selectorCaller = isset($selectorCaller) ? $selectorCaller : "";
        $selectorCaller2 = isset($selectorCaller2) ? $selectorCaller2 : "";
        $selectorCallerForm = ''; // link shopping_cart pilih multi item
        $pihakCallerDelete = isset($pihakCallerDelete) ? $pihakCallerDelete : "";
        $pihakLabel = isset($pihakLabel) ? $pihakLabel : 'pilih';
        $selectorLabel = isset($selectorLabel) ? $selectorLabel : 'pilih';
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $template = isset($template) ? $template : "";
        $setSubmitButton = isset($setSubmitButton) ? $setSubmitButton : "LANJUTKAN";
        $submitLabel = "Continue " . $subTitle;
        //endregion baca atribut, keterangan dari config

        $p = New Layout("$title", "$subTitle", "$template");

//cekHitam($template);
        $strOnprog = "";
        $strOnprogFooter = "";

//        arrprint($arrayProgressLabels);
//        arrprint($arrayOnProgress2);

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($arrayOnProgress2) > 0) {
                //region onprogress2
                if (sizeof($arrayOnProgress2) > 0) {
                    $strOnprog .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    switch ($viewMode) {
                        case "list":
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                            $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                            if (sizeof($arrayProgress2Labels) > 0) {
                                foreach ($arrayProgress2Labels as $key => $label) {
                                    $strOnprog .= "<td class='text-muted'>";
                                    $strOnprog .= $label;
                                    $strOnprog .= "</td>";
                                }
                            }
                            $strOnprog .= "</tr>";
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<td>";
                                        $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                        $strOnprog .= "</td>";
                                    }
                                }
                                $strOnprog .= "</tr>";
                            }
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td class='alert alert-warning' colspan='" . sizeof($arrayProgress2Labels) . "' align='center'>to process <strong>by request</strong> entries, you need to clear the list above from selected items.</td>";
                                $strOnprog .= "</tr>";
                            }
                            else {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td colspan='" . sizeof($arrayProgress2Labels) . "' align='right'><button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\">followup as new $title</button></td>";
                                $strOnprog .= "</tr>";
                            }
                            $strOnprog .= "</table>";
                            break;
                        case "thumbnail":
                            $strOnprog .= "<div class='panel-body' style='background:#e5e5e0;border:2px #cccccc dashed;'>";
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered' cellspacing='4'>";
                            $strOnprog .= "<tr line=" . __LINE__ . ">";
                            $no = 0;
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $no++;
                                $strOnprog .= "<td bgcolor='#f0f0f0' align='center'>";
                                $strOnprog .= "<label for='select_" . $no . "'>";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<div class='text-center'>";
                                        $strVal = isset($val[$key]) ? ($val[$key]) : "";
                                        $strVal = is_numeric($strVal) ? number_format($strVal) : $strVal;
                                        $strOnprog .= $strVal;
                                        $strOnprog .= "</div>";
                                    }
                                }
                                $strOnprog .= "</label>";
                                $strOnprog .= "</td>";
                                if ($no % 5 == 0) {
                                    $strOnprog .= "</tr><tr line=" . __LINE__ . ">";
                                }
                            }
                            $strOnprog .= "</tr>";
                            $strOnprog .= "</table class='table table-condensed table-bordered no-padding'>";

                            $strOnprog .= "<div class='row'>";
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6'>";
                                $strOnprog .= "to process <strong>by request</strong> entries, you need to clear the list above from selected items.";
                                $strOnprog .= "</div>";
                            }
                            else {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6 text-right'>";
                                $strOnprog .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary btn-block' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup as new $title</button>";
                                $strOnprog .= "</div>";
                            }
                            $strOnprog .= "</div>";
                            $strOnprog .= "</div>";

                            break;
                    }

                    $strOnprog .= "</form>";
                }
                //endregion
            }
            if (sizeof($arrayOnProgress) > 0) {
                //region onprogress
                if (sizeof($arrayOnProgress) > 0) {
                    $strOnprog .= "<div class='panel-body'>";
                    $strOnprog .= "<h4>action needed #2</h4>";
                    $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    if (sizeof($arrayProgressLabels) > 0) {
                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<td class='text-muted'>";
                            $strOnprog .= $label;
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";

                    foreach ($arrayOnProgress as $key => $val) {
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        if (sizeof($arrayProgressLabels) > 0) {
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }

                    $strOnprog .= "</table>";
                    $strOnprog .= "<div class='text-right'>";
                    $strOnprog .= "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
                    $strOnprog .= "</div class='text-right'>";
                    $strOnprog .= "</div class='panel-body'>";


                }
                else {
                    if (isset($arrayOnProgress2) && sizeof($arrayOnProgress2) > 0) {
                        $strOnprog = "";
                        $strOnprogFooter = "";
                    }
                    else {
                        $strOnprog = "-the item you specified has no entry-";
                        $strOnprogFooter = "";
                    }

                }
                //endregion
            }

        }


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table class='table table-condensed table-bordered no-padding'>";

            $strHist .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<td class='text-muted'>";
                    $strHist .= $label;
                    $strHist .= "</td>";
                }
            }
            $strHist .= "</tr>";

            foreach ($arrayHistory as $key => $val) {
                // print_r($val);
                $strHist .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $strHist .= $val[$key];
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }


            $strHist .= "</table>";

            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

//        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
//
//            $propDisplay = "block";
//            $altDisplay = "none";
//        } else {
//
//            $propDisplay = "none";
//            $altDisplay = "block";
//        }

        $propDisplay = "block";
        $altDisplay = "none";


//        cekkuning($strOnprog);die();

//        die("allowTmpSave:".$allowTmpSave);

        if (isset($barcodeSettings['srcModel'])) {
            $barcodeProcessor = "document.getElementById('result').src='" . base_url() . "Addons/BarcodeReader/readCode?jenisTr=$jenisTr&srcModel=" . $barcodeSettings['srcModel'] . "&srcColumn=" . $barcodeSettings['srcColumn'] . "&proc=" . blobEncode($selectorProcessor) . "&code='+this.value;";
        }
        else {
            $barcodeProcessor = "return false;";
        }
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(
            array(
                "error_msg" => $error,
                "alt_display" => $altDisplay,
                "prop_display" => $propDisplay,
                "tmpsave_display" => $allowTmpSave == true ? "block" : "none",
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "pihak_caller" => $pihakCaller,
                "selector_caller" => $selectorCaller,
                "selector_caller2" => $selectorCaller2,
                "pihak_caller_delete" => $pihakCallerDelete,
                "selector_caller_form" => $selectorCallerForm,
                "pihak_label" => $pihakLabel,
                "selector_label" => $selectorLabel,
                "selector_label2" => isset($selectorLabel2) ? $selectorLabel2 : "",
                "submit_button" => $submitLabel,
                //                "clear_shopping_cart" => $setClearShoppingCart,
                //                "action_shopping_cart" => $setActionShoppingCart,
                "onprogress_content" => $strOnprog,
                "onprogress_footer" => $strOnprogFooter,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                //                "payment_str"          => $strPaymentMethod,
                "selectedID" => $selectedID,
                "ext_tool" => $extTool,
                "column_recorder" => $columnRecorderTarget,
                "default_description" => $defaultDescription,
                "profile_name" => $this->session->login['nama'],
                "add_pihak" => $addPihakStr,
                "add_item" => $addItemStr,
                "this_page" => $thisPage,
                "view_mode_switch" => $viewModeSwitch,
                "barcode_action" => $barcodeProcessor,
                "mobile_scan" => $isMobile ? $mobScanStr : "",
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            )
        );

        $p->render();
        break;

    case "viewUndoneItems":


//        arrprint($arrayOnProgress);

        $str = "";
        if (sizeof($overDue) > 0) {
            $str .= "<div class='box box-danger'>";

            $str .= "<div class='box-header text-red blink'>";
            $str .= "<h4><span class=\"glyphicon glyphicon-calendar\"></span> over due transactions</h4>";
            $str .= "</div class='box box-header'>";


            $str .= "<div class='box-body'>";
            if (sizeof($overDue) > 0) {
                $str .= "<table class='table table-condensed'>";

                $str .= "<tr line=" . __LINE__ . ">";
                foreach ($arrayProgressLabels as $k => $label) {
                    $str .= "<td>$label</td>";
                }
                $str .= "</tr>";


                foreach ($overDue as $key => $pSpec) {
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td class='bg-danger'>";
//                    $str.=formatField($k,$pSpec[$k]);
                        $str .= $pSpec[$k];
                        $str .= "</td>";
                    }
                    $str .= "</tr>";
                }


                $str .= "</table class='table table-condensed'>";

            }

            //
            $str .= "</div class='box-body'>";
            $str .= "</div class='box box-danger'>";
        }

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            $str .= "<div class='box box-danger'>";

            $str .= "<div class='box-header text-green blink'>";
            $str .= "<h4><span class=\"glyphicon glyphicon-flash\"></span> on-going transactions</h4>";
            $str .= "</div class='box box-header'>";

            $str .= "<div class='box-body'>";

            if (sizeof($arrayOnProgress2) > 0) {
                $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                $str .= "<div class='panel panel-default'>";

//            $str .= "<div class='panel-body' style='background:#e5e5e5;border:2px #cccccc dashed;'>";
                $str .= "<div class='panel-body'>";
                $str .= "<h4 class='text-blue'>from requests</h4>";
                $str .= "<div class='row'>";

//            $str.="<ul class='pager'>";
                $rCtr = 0;
                foreach ($arrayOnProgress2 as $key => $pSpec) {
                    $rCtr++;

//                    $str .= "<div class='col-xs-2 panel'>";

                    if (isset($pSpec['select'])) {
                        $str .= "<label style='margin-bottom:-2px'>";
                    }
//                    $str .= "<div>{lihat produk}</div>";

                    $str .= "<div class='text-center alaIcon'>";
                    foreach ($arrayProgress2Labels as $k => $label) {
                        $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                        $str .= "<div>";
                        $str .= formatGlanceField($k, $iVal);
                        $str .= "</div>";
                    }

                    if (isset($pSpec['select'])) {
                        $str .= $pSpec['select'];
                    }

                    $str .= "</div class='col-md-3'>";
                    if (isset($pSpec['select'])) {
                        $str .= "</label>";
                    }

//                    $str .= "<div style='width:120px;' class='btn btn-xs btn-warning btn-block'><i class='fa fa-eye'></i> <b>".formatField('nomer',$pSpec['nomer'])."</b></div>";
//                    $str .= "</div>";

                }
//            $str.="</ul class='pager'>";
                $str .= "</div>";
                if ($allowMultiSelect == true) {
                    $str .= "<div class='row'>";
                    if (isset($needToClear) && $needToClear == true) {
                        $str .= "<div class='col-sm-12 text-center'>";
                        $str .= "<div class='text-warning'>";
                        $str .= "to process one of entries above, you need to clear selected items<br>";
                        $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                        $str .= "</div class='alert alert-warning'>";
                        $str .= "</div>";
                    }
                    else {
                        $str .= "<div class='col-sm-6'></div>";
                        $str .= "<div class='col-sm-6 text-right'>";
                        $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                }
                $str .= "</div>";
                $str .= "</div class='panel panel-default'>";
                $str .= "</form>";
            }

            if (sizeof($arrayOnProgress) > 0) {
                $str .= "<table class='table table-condensed'>";

                $str .= "<tr line=" . __LINE__ . ">";
                foreach ($arrayProgressLabels as $k => $label) {
                    $str .= "<td>";
                    if (is_array($label)) {
                        $str .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $str .= $label;
                    }
                    $str .= "</td>";
                }
                $str .= "</tr>";


                foreach ($arrayOnProgress as $key => $pSpec) {
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td>";
//                    $str.=formatField($k,$pSpec[$k]);
                        $str .= $pSpec[$k];
                        $str .= "</td>";
                    }
                    $str .= "</tr>";
                }


                if (isset($sumFooter) && sizeof($sumFooter) > 0) {
                    $str .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayProgressLabels) > 0) {
//                        $str .= "<td>-</td>";
                        foreach ($arrayProgressLabels as $key => $label) {
                            $str .= "<td>";
                            if (isset($sumFooter) && isset($sumFooter[$key])) {
                                $str .= $sumFooter[$key];
                            }
                            else {
                                $str .= "-";
                            }
                            $str .= "</td>";
                        }
                    }
                    $str .= "</tr>";
                }


                $str .= "</table class='table table-condensed'>";

            }

            $str .= "</div class='box-body'>";
            $str .= "</div class='box box-danger'>";

        }


        echo $str;
        break;

    case "viewRequestItems":

//        arrPrint($tabFieldsItems);

        $str = "";
        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($tabHistoryFields) > 0) {
//                cekKuning("::");
                $str .= "<div class=\"clearfix\">&nbsp;</div>";
                $str .= "<div class=\"nav-tabs-custom\">";
                $str .= "<ul class=\"nav nav-tabs\">";
                $str .= "<li class=\"pull-left header\"><i class=\"fa fa-th\"></i> LIST AUTO PRE-PO<br><span style='margin-top: -18px;' class='pull-right text-green blink'>----------></span></li>";
                $no1 = 1;
                foreach ($tabHistoryFields as $ky => $arrLab) {
                    $active = $no1 == 1 ? "active text-bold text-green" : "text-bold";
                    $str .= "<li class=\"$active\"><a href=\"#tab_$ky\" data-toggle=\"tab\" aria-expanded=\"false\">" . $arrLab['label'] . "</a></li>";
                    $no1++;
                }
                $str .= "</ul>";
                $str .= "<div class=\"tab-content\">";

                $no2 = 1;
                foreach ($tabHistoryFields as $ky => $arrLab) {

                    $active = $no2 == 1 ? " active" : "";
                    $str .= "<div class=\"tab-pane$active\" id=\"tab_$ky\">";

                    $str .= "<form method='post' id='$ky' name='$ky' target='result' action='$reqFormTarget'>";
                    $str .= "<table class='table $ky table-hover' >";
                    $str .= "<thead style='background: lightgrey;'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($tabFieldsItems[$ky] as $kyLabel => $label) {

                        if ($kyLabel == 'select') {
                            if (isset($allowMultiSelect) && $allowMultiSelect == true) {
                                $selectAll = "<input type='checkbox' id='selectAll_$ky'>";
                                $str .= "<th>$selectAll $label</th>";
                            }
                            else {
                                $str .= "<th>$label</th>";
                            }

                        }
                        else {
                            $str .= "<th>$label</th>";
                        }

                    }
                    $str .= "</tr>";
                    $str .= "</thead>";
                    $str .= "<tbody>";
//                    arrPrint($arrayOnProgress2);
                    foreach ($arrayOnProgress2[$ky] as $row) {
                        $str .= "<tr line=" . __LINE__ . ">";
                        foreach ($tabFieldsItems[$ky] as $kyLabel => $rows) {
                            if (isset($row[$kyLabel])) {
                                $str .= "<td>";
                                $str .= $row[$kyLabel];
                                $str .= "</td>";
                            }
                        }
                        $str .= "</tr>";
                    }
                    $str .= "</tbody>";
                    $str .= "<tfoot style='background: lightgrey;'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($tabFieldsItems[$ky] as $kyLabel => $rows) {
                        $angka = array();
                        foreach ($arrayOnProgress2[$ky] as $row) {
                            if (!isset($angka[$ky])) {
                                $angka[$ky] = 0;
                            }
                            $angka[$ky] += is_numeric($row[$kyLabel]) ? $row[$kyLabel] : "";
                        }
                        $str .= "<th>";
                        $str .= $angka[$ky] > 0 ? $angka[$ky] : "";
                        $str .= "</th>";
                    }

                    $str .= "</tr>";
                    $str .= "</tfoot>";
                    $str .= "</table>";


                    if ($allowMultiSelect == true) {
                        $str .= "<div class='row'>";
                        if (isset($needToClear) && $needToClear == true) {
                            $str .= "<div class='col-sm-12 text-center'>";
                            $str .= "<div class='text-warning'>";
                            $str .= "to process one of entries above, you need to clear selected items<br>";
                            $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items </a>";
                            $str .= "</div class='alert alert-warning'>";
                            $str .= "</div>";
                        }
                        else {
                            if (isset($arrLab['allowFollowup']) && $arrLab['allowFollowup'] == true) {
                                cekHijau($ky);
                                $str .= "<div class='col-sm-6 text-left'>";
                                $str .= "<button id='btnConnect$ky' name='btnConnect$ky' class='btn btn-primary' href=# onclick=\"this.disabledxx=true;this.innerHTML='clear the list to connect another one';document.getElementById('$ky').submit()\"><span class='fa fa-external-link'></span> Followup " . $arrLab['label'] . "</button>";
                                $str .= "</div>";
                                $str .= "<div class='col-sm-6'></div>";
                            }
                            else {
                                $str .= "<div class='clearfix'>&nbsp;</div>";
                                $str .= "<div class='col-sm-12 text-left'>";
                                $str .= "<div class='alert alert-danger' role='error'>";
                                $str .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                                $str .= "<span class='sr-only'>Error:</span>";
                                $str .= " NOTE:";
                                $str .= "<div> - Tidak Bisa Followup " . $arrLab['label'] . ", silahkan Followup melalui metode lainnya.</div>";
                                $str .= "<div> - Bagian ini hanya untuk kebutuhan Pengecekan.</div>";
                                $str .= "<div> - Letakan Cursor pada isi kolom produk, untuk melihat Rincian.</div>";
                                $str .= "</div>";
                                $str .= "</div>";
                            }

                        }
                        $str .= "</div class=row>";
                    }


                    $str .= "</div>";
                    $str .= "</form>";
                    $no2++;
                }
                $str .= "</div>";
                $str .= "</div>";

            }
            else {
                cekMerah("####");
                $str .= "<div class='box box-danger'>";
                $str .= "<div class='box-header text-green blink'>";
                $str .= "<h4><span class=\"glyphicon glyphicon-flash\"></span> on-going transactions</h4>";
                $str .= "</div class='box box-header'>";
                $str .= "<div class='box-body'>";
                if (sizeof($arrayOnProgress2) > 0) {
                    $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    $str .= "<div class='panel panel-default'>";
                    $str .= "<div class='panel-body'>";
                    $str .= "<h4 class='text-blue'>from requests</h4>";
                    $str .= "<div class='row'>";
                    $rCtr = 0;
                    foreach ($arrayOnProgress2 as $key => $pSpec) {
                        $rCtr++;
                        if (isset($pSpec['select'])) {
                            $str .= "<label>";
                        }
                        $str .= "<div class='text-center alaIcon'>";
                        foreach ($arrayProgress2Labels as $k => $label) {
                            $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                            $str .= "<div>";
                            $str .= formatGlanceField($k, $iVal);
                            $str .= "</div>";
                        }
                        if (isset($pSpec['select'])) {
                            $str .= $pSpec['select'];
                        }
                        $str .= "</div class='col-md-3'>";
                        if (isset($pSpec['select'])) {
                            $str .= "</label>";
                        }
                    }
                    $str .= "</div>";
                    if ($allowMultiSelect == true) {
                        $str .= "<div class='row'>";
                        if (isset($needToClear) && $needToClear == true) {
                            $str .= "<div class='col-sm-12 text-center'>";
                            $str .= "<div class='text-warning'>";
                            $str .= "to process one of entries above, you need to clear selected items<br>";
                            $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                            $str .= "</div class='alert alert-warning'>";
                            $str .= "</div>";
                        }
                        else {
                            $str .= "<div class='col-sm-6'></div>";
                            $str .= "<div class='col-sm-6 text-right'>";
                            $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                            $str .= "</div>";
                        }
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                    $str .= "</div class='panel panel-default'>";
                    $str .= "</form>";


                }
                if (sizeof($arrayOnProgress) > 0) {
                    $str .= "<table class='table table-condensed'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td>$label</td>";
                    }
                    $str .= "</tr>";
                    foreach ($arrayOnProgress as $key => $pSpec) {
                        $str .= "<tr line=" . __LINE__ . ">";
                        foreach ($arrayProgressLabels as $k => $label) {
                            $str .= "<td>";
                            $str .= $pSpec[$k];
                            $str .= "</td>";
                        }
                        $str .= "</tr>";
                    }
                    $str .= "</table class='table table-condensed'>";
                }
                $str .= "</div class='box-body'>";
                $str .= "</div class='box box-danger'>";
            }
        }

        echo $str;
        break;

    case "viewCompactUndoneItems":


//        arrprint($arrayOnProgress);

        $str = "";

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            $str .= "<div class='box box-danger'>";

            $str .= "<div class='box-header text-red blink'>";
            $str .= "<h4><span class=\"glyphicon glyphicon-flash\"></span> on-going transactions</h4>";
            $str .= "</div class='box box-header'>";


            $str .= "<div class='box-body'>";
            //


            if (sizeof($arrayOnProgress2) > 0) {
                $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                $str .= "<div class='panel panel-default'>";
//            $str .= "<div class='panel-body' style='background:#e5e5e5;border:2px #cccccc dashed;'>";
                $str .= "<div class='panel-body'>";
                $str .= "<h4 class='text-blue'>from requests</h4>";
                $str .= "<div class='row'>";
//            $str.="<ul class='pager'>";
                $rCtr = 0;
                foreach ($arrayOnProgress2 as $key => $pSpec) {
                    $rCtr++;
//                $str.="<li style='border:1px #777777 dotted;'>";
                    if (isset($pSpec['select'])) {
                        $str .= "<label>";

                    }

                    $str .= "<div class='text-center alaIcon'>";


                    foreach ($arrayProgress2Labels as $k => $label) {
//                    $iVal=is_numeric($pSpec[$k])?number_format($pSpec[$k]):$pSpec[$k];
//                    $str.=$iVal."<br>";
                        $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                        $str .= "<div>";
                        $str .= formatGlanceField($k, $iVal);
                        $str .= "</div>";

                    }

                    if (isset($pSpec['select'])) {
                        $str .= $pSpec['select'];

                    }
                    $str .= "</div class='col-md-3'>";
                    if (isset($pSpec['select'])) {

                        $str .= "</label>";
                    }
//                $str.="</li>";
                }
//            $str.="</ul class='pager'>";

                $str .= "</div>";
                if ($allowMultiSelect == true) {

                    $str .= "<div class='row'>";
                    if (isset($needToClear) && $needToClear == true) {

                        $str .= "<div class='col-sm-12 text-center'>";
                        $str .= "<div class='text-warning'>";
                        $str .= "to process one of entries above, you need to clear selected items<br>";
                        $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                        $str .= "</div class='alert alert-warning'>";
                        $str .= "</div>";
                    }
                    else {
                        $str .= "<div class='col-sm-6'></div>";
                        $str .= "<div class='col-sm-6 text-right'>";
                        $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                }
                $str .= "</div>";
                $str .= "</div class='panel panel-default'>";
                $str .= "</form>";


            }

            if (sizeof($arrayOnProgress) > 0) {
                $str .= "<table class='table table-condensed'>";

                $str .= "<tr line=" . __LINE__ . ">";
                foreach ($arrayProgressLabels as $k => $label) {
                    $str .= "<td>$label</td>";
                }
                $str .= "</tr>";


                foreach ($arrayOnProgress as $key => $pSpec) {
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td>";
//                    $str.=formatField($k,$pSpec[$k]);
                        $str .= $pSpec[$k];
                        $str .= "</td>";
                    }
                    $str .= "</tr>";
                }


                $str .= "</table class='table table-condensed'>";

            }

            //
            $str .= "</div class='box-body'>";
            $str .= "</div class='box box-danger'>";

        }


        echo $str;
        break;

    case "preview":
        cekHere(":: HAHAHA ::");
        arrPrint($itemsSrc);
        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            //region itemssrc
//            arrPrint($itemSrcLabels);
            $srcItems = "";
            if (sizeof($itemsSrc) > 0) {
                $srcItems .= "<div class='table-responsive'>";
                $srcItems .= "<table  class='table table-bordered table-condensed' style='background:#ffffff;'>";
                $srcItems .= "<tr bgcolor='#f5f5f5'>";
                $srcItems .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemSrcLabels as $cKol => $cAlias) {
                    $srcItems .= "<th class='text-muted' style='font-weight:bold;'>";
                    $srcItems .= $cAlias;
                    $srcItems .= "</th>";
                }
                $n = 0;
                foreach ($itemsSrc as $itemsSrc_0) {
                    $n++;
                    $srcItems .= "<tr line=" . __LINE__ . ">";
                    $srcItems .= "<td>$n</td>";
                    foreach ($itemSrcLabels as $cKol => $cAlias) {
                        $srcItems .= "<td>" . formatField($cKol, $itemsSrc_0[$cKol]);
                        $srcItems .= "</td>";
                    }
                    $srcItems .= "</tr>";
                }

                $srcItems .= "</tr>";
                $srcItems .= "</table>";
                $srcItems .= "</div>";
            }
            echo $srcItems;
            //endregion
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";


                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ((isset($imageEnabled)) && ($imageEnabled == true))) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                        // echo "</td>";


                    }
                    if (isset($imageEnabled) && ($imageEnabled == true)) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }
//arrPrint($mainAddValues);
            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . " bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
//                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }

            if (isset($items3) && sizeof($items3) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items3 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        echo "<td>";
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }
                if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                    foreach ($sumRows3 as $key => $label) {
                        $colspanX = sizeof($itemLabels3) > 1 ? sizeof($itemLabels3) : sizeof($itemLabels);
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($main[$key]) && $main[$key] > 0) {
                            $val = $main[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            }
            arrPrint($sumRows);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
//                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
//                arrPrint($mainAddValues);

            }
            arrPrintWebs($sumAddRows);
            if (isset($sumAddRows) && sizeof($sumAddRows) > 0) {
                $valAdd = 0;
                foreach ($sumAddRows as $keyAdd => $label) {
//                        cekLime($keyAdd);
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
                    $val = 0;
                    if (isset($main[$keyAdd]) && $main[$keyAdd] > 0) {
                        $valAdd = isset($main[$keyAdd]) ? $main[$keyAdd] : 0;
                    }
                    else {
                        if (isset($mainAddValues[$keyAdd]) && $mainAddValues[$keyAdd] > 0) {
                            $valAdd = isset($mainAddValues[$keyAdd]) ? $mainAddValues[$keyAdd] : 0;
                        }
                        else {
                            $valAdd = 0;
                        }
                    }

                    echo formatField($keyAdd, $valAdd);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
//                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
//                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
//                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

//                if (isset($grandTotal) && $grandTotal > 0) {
//                    echo "<tr bgcolor='#e5e5e5'>";
//                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
//                    echo "<td class='text-right'>";
//
//
//                    echo formatField("total", $grandTotal);
//                    echo "</td>";
//                    echo "</tr>";
//                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }


            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
//                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
//                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
//                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
//                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {

                                        if ($val != "") {
                                            echo "<tr line=" . __LINE__ . ">";
                                            $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                            if (strlen($strLabel) > 0) {
                                                echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            }
                                            echo "<td align='left'>$val</td>";
                                            echo "</tr>";
                                        }


                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
//                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "editPreview":
//        cekHere(":: HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";


                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ($imageEnabled == true)) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                        // echo "</td>";


                    }
                    if ($imageEnabled == true) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }


            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
//                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }


//            arrprint($main);
//            arrprint($mainAddValues);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
//                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
//                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
//                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
//                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

//                if (isset($grandTotal) && $grandTotal > 0) {
//                    echo "<tr bgcolor='#e5e5e5'>";
//                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
//                    echo "<td class='text-right'>";
//
//
//                    echo formatField("total", $grandTotal);
//                    echo "</td>";
//                    echo "</tr>";
//                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

//            if (isset($main['tagihan'])) {
//                echo "<tr line=".__LINE__.">";
//                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
//                echo "<td class='text-right'>";
//
//                echo formatField("tagihan", $main['tagihan']);
//                echo "</td>";
//                echo "</tr>";
//            }

            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
//                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
//                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
//                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
//                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {
                                        echo "<tr line=" . __LINE__ . ">";
                                        $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                        if (strlen($strLabel) > 0) {

                                            echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
//                                    echo "<td align='left'>$label</td>";
                                        }
                                        echo "<td align='left'>$val</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
//                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "cancelPackingPreview":
        cekHere(":: cancelPackingPreview HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";

                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ($imageEnabled == true)) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                    }
                    if ($imageEnabled == true) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }


            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
//                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }


//            arrprint($main);
//            arrprint($mainAddValues);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
//                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
//                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
//                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
//                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

//                if (isset($grandTotal) && $grandTotal > 0) {
//                    echo "<tr bgcolor='#e5e5e5'>";
//                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
//                    echo "<td class='text-right'>";
//
//
//                    echo formatField("total", $grandTotal);
//                    echo "</td>";
//                    echo "</tr>";
//                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

//            if (isset($main['tagihan'])) {
//                echo "<tr line=".__LINE__.">";
//                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
//                echo "<td class='text-right'>";
//
//                echo formatField("tagihan", $main['tagihan']);
//                echo "</td>";
//                echo "</tr>";
//            }

            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
//                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
//                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
//                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
//                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {
                                        echo "<tr line=" . __LINE__ . ">";
                                        $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                        if (strlen($strLabel) > 0) {

                                            echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
//                                    echo "<td align='left'>$label</td>";
                                        }
                                        echo "<td align='left'>$val</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
//                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "preCancelPackingPreview":
        cekHere(":: preCancelPackingPreview HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";

                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ($imageEnabled == true)) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                    }
                    if ($imageEnabled == true) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }


            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
//                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }


//            arrprint($main);
//            arrprint($mainAddValues);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
//                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
//                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
//                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
//                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
//                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

//                if (isset($grandTotal) && $grandTotal > 0) {
//                    echo "<tr bgcolor='#e5e5e5'>";
//                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
//                    echo "<td class='text-right'>";
//
//
//                    echo formatField("total", $grandTotal);
//                    echo "</td>";
//                    echo "</tr>";
//                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

//            if (isset($main['tagihan'])) {
//                echo "<tr line=".__LINE__.">";
//                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
//                echo "<td class='text-right'>";
//
//                echo formatField("tagihan", $main['tagihan']);
//                echo "</td>";
//                echo "</tr>";
//            }

            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
//                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
//                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
//                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
//                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {
                                        echo "<tr line=" . __LINE__ . ">";
                                        $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                        if (strlen($strLabel) > 0) {

                                            echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
//                                    echo "<td align='left'>$label</td>";
                                        }
                                        echo "<td align='left'>$val</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
//                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "followupPreview":

        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";


        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
                if (isset($mainValues[$key])) {
                    echo formatField($key, $mainValues[$key]);
                }
                else {
                    echo "";
                }

            }

            echo "</div class='col-md-6'>";
            echo "</div class='row'>";

            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
//        if (isset($items) && sizeof($items) > 0) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";


            $no = 0;
            if (isset($items) && sizeof($items) > 0) {
                echo "<thead>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";


                    foreach ($itemLabels as $key => $label) {

                        // cekHere($key . " " . $iSpec[$key]);
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );

                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }

                        switch ($detailSizeKey) {
                            default:
                            case "ckd":

                                foreach ($items as $pid => $item) {

                                    $replacers = array(
                                        "volume_new" => "volume_gross",
                                        "sub_volume_new" => "sub_volume_gross",
                                        "berat_new" => "berat_gross",
                                        "sub_berat_new" => "sub_berat_gross",
                                    );

                                    foreach ($replacers as $orig => $new) {
                                        if ($key == $orig) {
                                            $key = $new;
                                        }
                                    }
                                }

                                break;
                            case "cbu":
                                break;
                        }


                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        if ($key == "stok") {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                        elseif ($key == "stok_center") {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                        else {
                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;
                        }
//                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
//                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "text";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(removeCommas(this.value))<1 || parseInt(removeCommas(this.value))>$maxVal){this.value='" . number_format($maxVal) . "';}\" onkeyup=\"this.value=addCommas(this.value);\" onblur=\"top.$('#result').load('$updateItemFieldTarget?id=$id&key=$key&val='+removeCommas(this.value))\" ";
                                }
                                else {
                                    $addEvent = " onkeyup=\"this.value=addCommas(this.value);\"  onblur=\"top.$('#result').load('$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+removeCommas(this.value))\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='" . number_format($val) . "' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
//                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        if ($allowRemove == false) {

                        }
                        else {
                            echo "<td>";
                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+encodeURIComponent(this.value)\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

                if ((isset($itemLabels2)) && (sizeof($itemLabels2) > 1)) {

                    if (isset($items2) && sizeof($items2) > 0) {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr bgcolor='#f5f5f5'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($itemLabels2 as $key => $label) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $label;
                            echo "</th>";
                        }
                        echo "</tr>";

                        $no = 0;
                        foreach ($items2 as $iSpec2) {
                            $no++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($itemLabels2 as $key2 => $label2) {
                                $replacers = array(
                                    "produk_nama" => "nama",
                                    "produk_ord_jml" => "jml",
                                );
                                foreach ($replacers as $orig => $new) {
                                    if ($key2 == $orig) {
                                        $key2 = $new;
//                                    cekHere(":: $key2 :: $new ::");
                                    }
                                }

                                echo "<td>";
                                if (isset($iSpec2[$key2])) {
                                    echo formatField($key2, $iSpec2[$key2]);
                                }
                                else {
                                    echo "";
                                }
                                echo "</td>";
                            }
                            echo "</tr>";
//                    if ($noteEnabled == true) {
//                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
//                            echo "<tr line=".__LINE__.">";
//                            echo "<td>&nbsp;</td>";
//                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
//                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
//                            echo $iVal;
//                            echo "</td>";
//
//                            echo "</tr>";
//                        }
//
//                    }
                        }

                    }
                }

                if (isset($items3) && sizeof($items3) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels3 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items3 as $iSpec) {
                        $no++;

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels3 as $key => $label) {
                            echo "<td>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                        }
                        echo "</tr>";
                        if ($noteEnabled == true) {
                            if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td>&nbsp;</td>";
                                echo "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                                echo $iVal;
                                echo "</td>";

                                echo "</tr>";
                            }

                        }
                    }
                    if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                        foreach ($sumRows3 as $key => $label) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right'>$label</td>";
                            echo "<td class='text-right'>";
                            if (isset($mainValues[$key])) {
                                echo formatField($key, $mainValues[$key]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($sumRows) && sizeof($sumRows) > 0) {


                    foreach ($sumRows as $key => $label) {

                        if (isset($items2) && sizeof($items2) > 0) {
//                            cekKuning("ATAS");
                            $colspanSum = sizeof($itemLabels2) > 1 ? $itemLabels2 : $itemLabels;
                        }
                        else {
//                            cekBiru("BAWAH");
                            $colspanSum = $itemLabels;
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($colspanSum) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        if (isset($mainValues[$key])) {

                            echo formatField($key, $mainValues[$key]);
                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    if (isset($sumAddRows) && sizeof($sumAddRows) > 0) {
                        foreach ($sumAddRows as $key => $label) {
                            echo "<tr line='" . __LINE__ . " key: $key'>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                            echo "<td class='text-right'>";

                            if (isset($mainValues[$key])) {

                                echo formatField($key, $mainValues[$key]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }


                //region child data
                if (isset($items_child) && sizeof($items_child) > 0) {
                    echo "<div class='table-responsive'>";
//                    echo "<div class=''>Detail</div>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemsChildLabel as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    if ($itemsChildGate == "main") {
//                        arrPrint($main);
//                        foreach ($items as $id => $itemSpec) {
                        foreach ($items_child as $id => $itemSpec) {
                            $no++;
                            foreach ($itemSpec as $x => $iSpec) {
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td align='right'>";
                                echo $no;
                                echo ".</td>";
                                foreach ($itemsChildLabel as $key => $label) {
//                                cekHere()test
                                    if (isset($itemsChildLabelEditable[$key])) {
                                        $inputType = "text";
                                        $val = $iSpec[$key];
                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemChildTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&x=$x&val='+this.value\" ";
                                        $strVal = "<input type=$inputType name='$id" . "_" . "$x' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                                        $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                                    }
                                    else {
                                        $strVal = $iSpec[$key];
                                    }
                                    echo "<td $tdOpt>";
                                    echo $strVal;
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }
//                                arrPrintWebs($iSpec);


                        }
//                        }
                    }
                    else {
                        foreach ($items as $id => $itemSpec) {
                            foreach ($items_child[$id] as $x => $iSpec) {
                                $no++;
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td align='right'>";
                                echo $no;
                                echo ".</td>";
                                foreach ($itemsChildLabel as $key => $label) {
//                                cekHere()test
                                    if (isset($itemsChildLabelEditable[$key])) {
                                        $inputType = "text";
                                        $val = $iSpec[$key];
                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemChildTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&x=$x&val='+this.value\" ";
                                        $strVal = "<input type=$inputType name='$id" . "_" . "$x' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                                        $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                                    }
                                    else {
                                        $strVal = $iSpec[$key];
                                    }
                                    echo "<td $tdOpt>";
                                    echo $strVal;
                                    echo "</td>";
                                }
                                echo "</tr>";

                            }
                        }
                    }


                }

                //endregion


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";
//                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
//                        echo $mainValues[$key];

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";
//                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                //region extended add main source
                $no = 0;
                if (isset($addMainSourceField) && sizeof($addMainSourceField) > 0) {
                    echo "<div class='table-responsive'>";
//                    echo "<div class=''>Detail</div>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($addMainSourceField as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td $tdOpt>";
                    echo "1";
                    echo "</td>";
                    foreach ($addMainSourceField as $kol => $alias) {
                        if (isset($addMainSourceEdit[$kol])) {
                            $inputType = $addMainSourceEdit[$kol];
                            $val = isset($mainValues[$kol]) ? $mainValues[$kol] : "";
                            $addEvent = " onblur=\"document.getElementById('result').src='$updateMainSourceTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$kol&&val='+this.value\" ";
                            $strVal = "<input type=$inputType name='$kol' class='form-control text-left' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            $strVal = formatField($kol, $mainValues[$kol]);
                        }
                        echo "<td $tdOpt>";
                        echo $strVal;
                        echo "</td>";

                    }
                    echo "</tr>";


                }


                //endregion

//	            if(isset($main['tagihan'])){
//		            echo "<tr line=".__LINE__.">";
//		            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
//		            echo "<td class='text-right'>";
//
//		            echo formatField("tagihan", $main['tagihan']);
//		            echo "</td>";
//		            echo "</tr>";
//	            }
            }

            echo "</tbody>";
            echo "</table>";


            if (isset($items) && sizeof($items) > 0) {
                if (isset($dpData) && sizeof($dpData) > 0) {

                    echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                    foreach ($dpData['field'] as $dp_key => $dp_label) {
                        echo "<tr bbgcolor='#f0f0f0'>";
                        echo "<td align='left'>$dp_label</td>";
                        echo "<td align='right'> " . formatField($dp_key, $dpData['value'][$dp_key]) . " </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";
                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {

                    if (isset($mainElements['detilSize'])) {
                        if (in_array('detilSize', $editableElements)) {
                            $editLink = "BootstrapDialog.show(
                                       {
                                           title:'detilSize',
                                            message: $('<div></div>').load('" . $elementEditTarget . "detilSize" . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                            size:BootstrapDialog.SIZE_WIDE,
                                            draggable:false,
                                            closable:true,
                                            }
                                            );
                                           ";

                            echo "<div style='font-size: 14px;' class='text-center col-md-12'>";
                            echo "Anda Sedang Menggunakan Data Ukuran: <span class='text-uppercase text-bold'>$detailSizeKey</span> ";
                            echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                            echo "<span class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i> ganti</span>";
                            echo "</a>";
                            echo "</div>";
                        }
                    }

                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {
//                    arrPrint($mainElements);
                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    foreach ($mainElements as $elName => $aSpec) {
//                        cekBiru("$elName");
                        if (array_key_exists($elName, $elementConfig)) {
//                            cekKuning("$elName");
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " &nbsp;&nbsp;&nbsp;</span>";
                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";
                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";
                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));

                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            if ($val != "") {
                                                echo "<tr line=" . __LINE__ . ">";
                                                $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }
                                    else {
//                                        echo "<table class='tables table-condensed'>";
//                                        echo "<tr line=".__LINE__.">";
//                                        $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
//                                        echo "<td align='left' class='text-black'>$strLabel harus dipilih</td>";
//                                        echo "</tr>";
//                                        echo "</table>";

                                        $msg = "<span class='glyphicon glyphicon-arrow-left'></span> &nbsp;&nbsp;silahkan " . $aSpec['label'] . " dipilih ulang dengan klik icon pensil sebelah kiri.";
                                        echo "<table class='tables table-condensed'>";
                                        echo "<tr line=" . __LINE__ . ">";
                                        echo "<td align='left' class='text-red' style='font-size: 15px;'>$msg</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                    }
                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                    }
                    echo "</table>";
                    echo "</div class='panel-default'>";
                }

                // if (strlen($description) > 0) { // mendeteksi jumlah karakter catatan, kalau lebih dari 0 maka ditampilkan. berlaku semua transaksi.
                if (isset($description)) { // mendeteksi gerbang catatan (main), bila ada maka ditampilkan. berlaku semua transaksi.
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                    // bila bisa mengedit catatan dan mau edit maka editlah.
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    // bila tidak bisa mengedit catatan, maka lihat saja
                    else {
                        if (strlen($description) > 0) {

                            echo nl2br($description);
                        }
                        else {
                            echo "-";
                        }
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }

                if (isset($descriptionAdditionalRule) && ($descriptionAdditionalRule['enabled'] == true)) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note (from current step) </span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($descriptionAdditionalRule['editabled']) && ($descriptionAdditionalRule['editabled'] == true)) {
                        $key_note = "description_additional";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($descriptionAdditional);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($descriptionAdditional);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                else {
//                    arrPrint($descriptionAdditionalPreviews);
//                    cekHere(sizeof($descriptionAdditionalPreviews));
                    if (sizeof($descriptionAdditionalPreviews) > 0) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>description note (dari step sebelumnya) </span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                        $val_result = "";
                        foreach ($descriptionAdditionalPreviews as $ii => $iiVal) {
                            if ($val_result == "") {
                                $val_result = $iiVal;
                            }
                            else {
                                $val_result .= "<br>" . $iiVal;
                            }
                        }
                        echo nl2br($val_result);


                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }

                if (sizeof($descriptionMainFollowupRule) > 0) {

                    if (isset($descriptionMainFollowupRule['enabled']) && ($descriptionMainFollowupRule['enabled'] == true)) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>" . $descriptionMainFollowupRule['label'] . "</span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                        if (isset($descriptionMainFollowupRule['editabled']) && ($descriptionMainFollowupRule['editabled'] == true)) {
                            $key_note = "description_main_followup";
                            $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                            echo "<textarea class='form-control text-left' $addEvent_description>";
                            echo nl2br($descriptionMainFollowup);
                            echo "</textarea>";
                        }
                        else {
                            echo nl2br($descriptionMainFollowup);
                        }

                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }

//                else {
//                    if (sizeof($descriptionAdditionalPreviews) > 0) {
//                        echo "<table class='table table-bordered table-condensed'>";
//                        echo "<tr line=".__LINE__.">";
//                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
//                        echo "<span class='text-muted'>description note (dari step sebelumnya) </span><br>";
//                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
//
//                        $val_result = "";
//                        foreach ($descriptionAdditionalPreviews as $ii => $iiVal) {
//                            if ($val_result == "") {
//                                $val_result = $iiVal;
//                            }
//                            else {
//                                $val_result .= "<br>" . $iiVal;
//                            }
//                        }
//                        echo nl2br($val_result);
//
//
//                        echo "</span><br>";
//                        echo "</td>";
//                        echo "</tr>";
//                        echo "</table>";
//                    }
//                }


                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }

            echo "</div class='table-responsive'>";


            if (isset($items) && sizeof($items) > 0) {
                echo "<div>";

                // echo "<div class='col-md-2'>";
                echo "<button type='button' class='btn btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "</div class='col-md-2'>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-danger' style='border:1px #ff7700 solid;ccolor:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-danger' style='border:1px #ff7700 solid;ccolor:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                // echo "<div class='col-md-2'>&nbsp;";
                // echo "</div class='col-md-2'>";
                echo "<div class='bbtn-group pull-right'>";
                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    // echo "<div class='panel-body'>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger' style='border:1px #dd3300 solid;ccolor:#dd3300;' 
                        onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';
                        document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-danger' style='border:1px #dd3300 solid;color:#dcdcdc;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    if (isset($rejectionSpecAll['targetUrl']) != "" && $rejectionSpecAll['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger' style='border:1px #000000 solid;color:#ffffff;background-color:#000000;' 
                        onclick=\"if(confirm('" . $rejectionSpecAll['warning'] . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpecAll['targetUrl'] . "';
                        document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp;" . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-danger' style='border:1px #000000 solid;color:#dcdcdc;background-color:#000000;'><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp;" . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    echo "<button type='button' disabled class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                    // echo "</div>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger' style='border:1px #dd3300 solid;ccolor:#dd3300;' 
                        onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';this.disabled=true;
                        document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                        echo "&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button button type='button' disabled class='btn btn-danger' style='border:1px #dd3300 solid;color:#dcdcdc;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    if (isset($rejectionSpecAll['targetUrl']) != "" && $rejectionSpecAll['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger' style='border:1px #000000 solid;color:#ffffff;background-color:#000000;' 
                        onclick=\"if(confirm('" . $rejectionSpecAll['warning'] . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpecAll['targetUrl'] . "';this.disabled=true;
                        document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp; " . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button button type='button' disabled class='btn btn-danger' style='border:1px #000000 solid;color:#dcdcdc;background-color:#000000;'><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp; " . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<button button type='button' class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }


                if (isset($xShipmentBtn['targetUrl']) && $xShipmentBtn['targetUrl'] != "") {
                    echo "&nbsp;&nbsp;<button type='button' class='btn btn-danger' style='bborder:1px #fff solid;color:#ffffff;' 
                    onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';
                    document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</button>";
                }

                echo "</div>";

                echo "</div>"; // 2669

                if (isset($definitionButton) && sizeof($definitionButton) > 0) {

                    echo "<div class='row' style='margin-top: 20px;margin-bottom:-30px;font-size: larger;'>";
                    echo "<div class='panel-body'>";
                    echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
//                    echo "<small>";
                    foreach ($definitionButton as $lButton => $kButton) {
                        echo "<strong>$lButton</strong> : $kButton";
                        echo "<br>";
                    }
//                    echo "</small>";
                    echo "</div class='col-md-12 text-center'>";
                    echo "</div class='panel-body'>";
                    echo "</div class='row'>";
                }


                echo "<div class='row' style='margin-top: 20px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";
                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";
                echo "</div>";
                echo "</div class='row'>";
            }

            echo "</form>";
        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();
        }

        break;

    case "followupCancelPackingPrePreview":

        cekHere(":: followupCancelPackingPrePreview HAHAHA ::");

        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";
        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
                echo "";
            }
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $no = 0;

            //table produk
            if (isset($items) && sizeof($items) > 0) {
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
//                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "number";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                                }
                                else {
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
//                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        echo "<td>";
                        echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+this.value\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }


//                if (isset($items2) && sizeof($items2) > 0) {
//
//                    foreach ($items2 as $id => $iSpec) {
//                        if (array_key_exists($id, $msgWarnings)) {
//                            $addStyle = "background-color:yellow;color:#000000;";
//                        } else {
//                            $addStyle = "";
//                        }
//
//                        $no++;
//                        echo "<tr line=".__LINE__.">";
//                        echo "<td align='right' style='$addStyle'>";
//                        echo $no;
//                        echo ".</td>";
//                        foreach ($itemLabels2 as $key => $label) {
//
//                            $replacers = array(
//                                "produk_nama"    => "nama",
//                                "produk_ord_jml" => "jml",
//                            );
//
//                            foreach ($replacers as $orig => $new) {
//                                if ($key == $orig) {
//                                    $key = $new;
//                                }
//                            }
//
//
//                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $iSpec[$key];
//
//                            if ($allowEdit == true && in_array($key, $editableFields)) {
//                                if (is_numeric($val)) {
//                                    $val += 0;
//                                    $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
//                                    $inputType = "number";
//                                    $addEvent = "";
//                                    if (!$allowIncrement) {
//                                        $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
//                                    } else {
//                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
//                                    }
//
//                                } else {
//                                    $inputType = "text";
//                                    $addEvent = "";
//                                }
//                                $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
//                                $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
//                            } else {
//                                $strVal = formatField($key, $val);
//                                $tdOpt = "style='$addStyle'";
//                            }
//
//                            echo "<td $tdOpt >$strVal";
//                            echo "</td>";
//                        }
//                        if ($allowEdit == true) {//==delete item
//                            echo "<td>";
//                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
//                            echo "</td>";
//                        }
//                        echo "</tr>";
//                    }
//                }

//arrPrint($items2);
                if (isset($items2) && sizeof($items2) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items2 as $iSpec2) {
                        $no++;
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels2 as $key2 => $label2) {
                            $replacers = array(
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "jml",
                            );
                            foreach ($replacers as $orig => $new) {
                                if ($key2 == $orig) {
                                    $key2 = $new;
//                                    cekHere(":: $key2 :: $new ::");
                                }
                            }

                            echo "<td>";
                            if (isset($iSpec2[$key2])) {
                                echo formatField($key2, $iSpec2[$key2]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
//                    if ($noteEnabled == true) {
//                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
//                            echo "<tr line=".__LINE__.">";
//                            echo "<td>&nbsp;</td>";
//                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
//                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
//                            echo $iVal;
//                            echo "</td>";
//
//                            echo "</tr>";
//                        }
//
//                    }
                    }

                }


                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";
                        if (isset($mainValues[$key])) {
                            echo formatField($key, $mainValues[$key]);

                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }


                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

            }
            echo "</table>";

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";

//                arrPrint($detilSizeBar);

                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {
                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            //details
            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {

                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";

                    echo "<table class='table table-bordered table-condensed'>";

                    foreach ($mainElements as $elName => $aSpec) {

                        if (array_key_exists($elName, $elementConfig)) {

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " </span>";

                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";

                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";

                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (sizeof($strLabel) > 0 && $val != '') {
                                                echo "<tr line=" . __LINE__ . ">";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }

                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";

                    echo "</div class='panel-default'>";
                }
                if (strlen($description) > 0) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').
src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+this.value;\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($description);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }
            echo "</div class='table-responsive'>";

            //button action
            if (isset($items) && sizeof($items) > 0) {
                echo "<div class='row'>";

                echo "<div class='col-md-2'>";
                echo "<a class='btn btn-block btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </a>";
                echo "</div class='col-md-2'>";

                echo "<div class='col-md-2'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<a class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</a>";
                }
                else {
                    echo "<button disabled class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                echo "</div class='col-md-2'>";

                echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<a class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</a>";
                }
                else {
//                    echo "&nbsp;";
                    echo "<button disabled class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                echo "</div class='col-md-2'>";

                echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<a class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</a>";
                }
                else {
//                    echo "&nbsp;";
                    echo "<button disabled class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                echo "</div class='col-md-2'>";

//                echo "<div class='col-md-2'>&nbsp;";
//                echo "</div class='col-md-2'>";

                echo "<div class='col-md-4 text-right'>";

                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    echo "<div class='panel-body'>";
//                    echo "<span class='text-danger'>these values need to be verified first</span><br>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }

                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }


                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<a class='btn btn-block btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</a>";
                    }
                    else {
                        echo "<button disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    echo "<button disabled class='btn btn-success'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";

                    echo "</div>";
                }
                else {

                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }

                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<a class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</a>";
                    }
                    else {
                        echo "<button disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<a class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</a>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }

                if (isset($xShipmentBtn['targetUrl']) != "" && $xShipmentBtn['targetUrl'] != "") {
                    echo "<span class='btn btn-default ' style='border:1px #fff solid;color:#ff7700;' onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</span>";
                }

                echo "</div class='col-md-4'>";
                echo "</div class='row'>";

                echo "<div class='row'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";

                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";

                echo "</div>";
                echo "</div class='row'>";
            }


            echo "</form>";

        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();

        }

        break;

    case "followupCancelPackingPreview":

        cekHere(":: followupCancelPackingPreview HAHAHA ::");

        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";
        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
                echo "";
            }
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $no = 0;

            //table produk
            if (isset($items) && sizeof($items) > 0) {
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
//                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "number";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                                }
                                else {
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
//                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        echo "<td>";
                        echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+this.value\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }


//                if (isset($items2) && sizeof($items2) > 0) {
//
//                    foreach ($items2 as $id => $iSpec) {
//                        if (array_key_exists($id, $msgWarnings)) {
//                            $addStyle = "background-color:yellow;color:#000000;";
//                        } else {
//                            $addStyle = "";
//                        }
//
//                        $no++;
//                        echo "<tr line=".__LINE__.">";
//                        echo "<td align='right' style='$addStyle'>";
//                        echo $no;
//                        echo ".</td>";
//                        foreach ($itemLabels2 as $key => $label) {
//
//                            $replacers = array(
//                                "produk_nama"    => "nama",
//                                "produk_ord_jml" => "jml",
//                            );
//
//                            foreach ($replacers as $orig => $new) {
//                                if ($key == $orig) {
//                                    $key = $new;
//                                }
//                            }
//
//
//                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $iSpec[$key];
//
//                            if ($allowEdit == true && in_array($key, $editableFields)) {
//                                if (is_numeric($val)) {
//                                    $val += 0;
//                                    $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
//                                    $inputType = "number";
//                                    $addEvent = "";
//                                    if (!$allowIncrement) {
//                                        $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
//                                    } else {
//                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
//                                    }
//
//                                } else {
//                                    $inputType = "text";
//                                    $addEvent = "";
//                                }
//                                $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
//                                $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
//                            } else {
//                                $strVal = formatField($key, $val);
//                                $tdOpt = "style='$addStyle'";
//                            }
//
//                            echo "<td $tdOpt >$strVal";
//                            echo "</td>";
//                        }
//                        if ($allowEdit == true) {//==delete item
//                            echo "<td>";
//                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
//                            echo "</td>";
//                        }
//                        echo "</tr>";
//                    }
//                }

//arrPrint($items2);
                if (isset($items2) && sizeof($items2) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items2 as $iSpec2) {
                        $no++;
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels2 as $key2 => $label2) {
                            $replacers = array(
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "jml",
                            );
                            foreach ($replacers as $orig => $new) {
                                if ($key2 == $orig) {
                                    $key2 = $new;
//                                    cekHere(":: $key2 :: $new ::");
                                }
                            }

                            echo "<td>";
                            if (isset($iSpec2[$key2])) {
                                echo formatField($key2, $iSpec2[$key2]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
//                    if ($noteEnabled == true) {
//                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
//                            echo "<tr line=".__LINE__.">";
//                            echo "<td>&nbsp;</td>";
//                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
//                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
//                            echo $iVal;
//                            echo "</td>";
//
//                            echo "</tr>";
//                        }
//
//                    }
                    }

                }


                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";
                        if (isset($mainValues[$key])) {
                            echo formatField($key, $mainValues[$key]);

                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }


                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

            }
            echo "</table>";

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";

//                arrPrint($detilSizeBar);

                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {
                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            //details
            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {

                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";

                    echo "<table class='table table-bordered table-condensed'>";

                    foreach ($mainElements as $elName => $aSpec) {

                        if (array_key_exists($elName, $elementConfig)) {

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " </span>";

                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";

                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";

                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (sizeof($strLabel) > 0 && $val != '') {
                                                echo "<tr line=" . __LINE__ . ">";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }

                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";

                    echo "</div class='panel-default'>";
                }
                if (strlen($description) > 0) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').
src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+this.value;\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($description);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }
            echo "</div class='table-responsive'>";

            //button action
            if (isset($items) && sizeof($items) > 0) {
                echo "<div>";

                // echo "<div class='col-md-2'>";
                echo "<button type='button' class='btn btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "</div class='col-md-2'>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                // echo "<div class='col-md-2'>&nbsp;";
                // echo "</div class='col-md-2'>";
                echo "<div class='btn-group pull-right'>";
                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    // echo "<div class='panel-body'>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "&nbsp;<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;<button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    echo "&nbsp;<button type='button' disabled class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                    // echo "</div>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "<button button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "&nbsp;<button button type='button' class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }
                echo "</div>";

                if (isset($xShipmentBtn['targetUrl']) != "" && $xShipmentBtn['targetUrl'] != "") {
                    echo "<span class='btn btn-default ' style='border:1px #fff solid;color:#ff7700;' onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</span>";
                }

                echo "</div>"; // 2669

                echo "<div class='row' style='margin-top: 60px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";

                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";

                echo "</div>";
                echo "</div class='row'>";
            }

            echo "</form>";

        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();

        }

        break;

    case "showDetails":
        function bs_modal($label, $field)
        {
            $label_width = "col-sm-3";
            $forms_width = "col-sm-9";

            $var = "<div class='form-group overflow-h'>";
            $var .= "<label class='$label_width control-label'>$label</label>
                  <div class='$forms_width'>
                  <div class='input-group' style='width:100%;'>
                    $field
                    
                  </div>
                  </div>";
            $var .= "</div>";
            return $var;
        }

        $p = New Pages("$title", "sub judul", "application/template/pages.html");
        $arrAtribut = array(
            "target" => "result",
            "name" => "myForm",
            "id" => "myForm",
        );
        // $action_link = "";
//        $form = "";
//        arrPrint($arrayHeaderLabels);
//        arrPrint($arrayTablesHeader);
//        arrPrint($arrayNotaData);
//        arrPrint($dataProduk);

        //region header Nota
        $template = array(
            'table_open' => '<table border="2" cellpadding="1" cellspacing="1" class="table  tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
        );
        $this->table->set_template($template);
        $headerNota = "<table>";
        if (sizeof($arrayHeaderLabels) > 0) {
            $header_f = array();
            foreach ($arrayNotaData as $key => $dataHeaderNota) {

                $header_result_f = array();
                foreach ($arrayHeaderLabels as $kolom => $label) {
                    $value = $dataHeaderNota[$kolom];
                    $headerNota .= "<tr line=" . __LINE__ . ">";
                    $headerNota .= "<td>$label</td>";
                    $headerNota .= "<td>:</td>";
                    $headerNota .= "<td>$value</td>";
                    $headerNota .= "</tr>";
                }


            }

//            $this->table->add_row($header_f);

        }
        $headerNota .= "</table>";
        //endregion header nota

        //region data transaksi
        if (sizeof($dataProduk) > 0) {
            $header_f = array();
            foreach ($arrayTablesHeader as $kolom => $alias) {
                $header_result_f = array('data' => $alias, 'class' => 'text-center');
                $header_f[] = $header_result_f;
            }
            $this->table->set_heading($header_f);
            //region data transaksi
            foreach ($dataProduk as $dataTrasnsaksi) {
                $isi = array();
                foreach ($arrayTablesHeader as $kolom => $alias) {
                    $value = $dataTrasnsaksi[$alias];
                    $isi[] = array('data' => $value);
                }
                $this->table->add_row($isi);
            }
            //endregion
        }

        $contens = $headerNota;
        $contens .= $this->table->generate();
        //endregion

        //  region button modal-footer
        $pihak_data = "<div class='row'>";
        foreach ($arrayNotaPihak as $key => $arrPihak) {
            foreach ($arrPihak as $pihak => $by) {
                $pihak_data .= "<div class='col-md-6'>";
                $pihak_data .= "<div>$pihak</div>";
                $pihak_data .= "<div class='row'></div>";
                $pihak_data .= "<div>$by</div>";
                $pihak_data .= "</div>";
            }

        }
        $pihak_data .= "</div>";
        $contens .= "$pihak_data";

        $button = form_button("tes", "<i class='fa fa-close'> Close</i>", "class='btn btn-default pull-left' data-dismiss='modal'");
        //  endregion button form


        $p->setLayoutModalHeader($title, true);
        $p->setLayoutModalBody($contens);
        $p->setLayoutModalFooter($button);

//        $modal = form_open($action_link, $arrAtribut);
        $modal = $p->layout_modal();
//        $modal .= form_close();

        echo $modal;
        break;

    case "viewReceipt":
        arrPrint($items);
        if (isset($mainElements)) {
//            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            if (isset($elementUsedFieldsConfigs) && sizeof($elementUsedFieldsConfigs) > 0) {
                                                if (isset($elementUsedFieldsConfigs[$e]) && $elementUsedFieldsConfigs[$e] != "") {
                                                    $colLabel = $elementUsedFieldsConfigs[$e];
                                                }
                                                else {
                                                    $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                                }
                                            }
                                            else {
                                                $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            }
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }
        $item_src = "";
        if (sizeof($itemSrc) > 0) {
//            arrPrint($itemSrc);

            $item_src .= "<div class='table-responsive' style='border:0px solid red;'>";
            $item_src .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            $item_src .= "<tr bgcolor='#f5f5f5'>";
            $item_src .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";

            foreach ($itemSrcLabel as $ky => $srcLabel) {
                $item_src .= "<th class='text-muted' style='font-weight:bold;'>";
                $item_src .= $srcLabel . "";
                $item_src .= "</th>";
            }
            $item_src .= "</tr>";
            $mno = 0;
            foreach ($itemSrc as $itemSrc0) {
                $mno++;
                $item_src .= "<tr line=" . __LINE__ . ">";
                $item_src .= "<td align='right'>";
                $item_src .= $mno;
                $item_src .= "</td>";
                foreach ($itemSrcLabel as $ky => $srclabel) {
                    $val = isset($itemSrc0[$ky]) ? $itemSrc0[$ky] : "";
                    $item_src .= "<td>";
                    $item_src .= formatField($ky, $val);
                    $item_src .= "</td>";
                }
                $item_src .= "</tr>";

            }


            $item_src .= "</table>";
            $item_src .= "</div>";
        }
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    // arrPrint($iSpec);
//                     arrPrint($itemLabels);

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();

                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));

                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
//                    arrPrint($items[$id]);
                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";

                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";

                            cekMerah($iVal);

                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);

                            cekHijau($string);

                            $string = str_replace("&lt;br&gt;", "<br>", $string);


                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";

                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

//                 arrPrint($mainValues);
//                 arrPrint($sumRows);
//                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
//                    arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

//                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
//                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {

                            if (sizeof($mainValues[$key]) > 0) {
//                                cekHere("$key " . $mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif ($mainValues[$key] < 0) {
//                                cekHitam($mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                        }
//                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                arrPrint($sumRows2);
                if (isset($sumRows2) && sizeof($sumRows2) > 0) {
                    foreach ($sumRows2 as $key2 => $label2) {

//                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
//                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key2])) {

                            if (sizeof($mainValues[$key2]) > 0) {
//                                cekHere("$key " . $mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key2, $zeroAllowed))) {
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif ($main[$key2] < 0) {
//                                cekHitam($mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                        }
//                        cekHere($label." - ".$key." - ".$val);
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr line=" . __LINE__ . ">";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr line=" . __LINE__ . ">";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";
            }


            $contentStr6 = "";
            if (isset($dpValueDetilsINV) && sizeof($dpValueDetilsINV) > 0) {
                $contentStr6 .= "<div class='panel-body'>";
                $contentStr6 .= "<table class='table table-responsive'>";
                foreach ($dpFieldNameINV as $dp_fields => $dpFields_alias) {
                    $contentStr6 .= "<tr line=" . __LINE__ . ">";
                    $contentStr6 .= "<td>$dpFields_alias</td>";
                    $contentStr6 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetilsINV[$dp_fields]) . "</td>";
                    $contentStr6 .= "</tr>";
                }
                $contentStr6 .= "</table>";
                $contentStr6 .= "</div>";

                $elementLabels["content_6_display"] = "block";
            }
            else {
                $elementLabels["content_6_display"] = "none";
            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $contenStr5 = "";
            if (isset($mainData2) && sizeof($mainData2) > 0) {

//                $contenStr5 .= "<div class='panel-body'>";
                $contenStr5 .= "<table class='table table-bordered'>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-centter'>No</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    $contenStr5 .= "<td class='text-centter'>$add_fields</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-center'>1</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
//                    cekHitam($fieldsKey);
                    $contenStr5 .= "<td>" . formatField($fieldsKey, $mainData2[$fieldsKey]) . "</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                if (strlen($inWord2) > 5) {
                    $contenStr5 .= "<tr line=" . __LINE__ . ">";
                    $contenStr5 .= "<td style='vertical-align: bottom;' colspan='" . sizeof($mainData2Fields) . "' rowspan='' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord2</span></td>";
                    $contenStr5 .= "</tr>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "</table>";
//                $contenStr5 .= "</div>";

            }
            $elementLabels["content_src"] = $item_src;
            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
            $elementLabels["content_5"] = $contenStr5;
            $elementLabels["content_6"] = $contentStr6;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

//arrPrintWebs($arrTags);

        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceiptCashIn":

//cekHere("iki");
//        arrPrint($mainElements);
//        arrPrint($elementConfigs);
//"nomer"

//        arrPrint($mainElements);
        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
//                        $elementStr .= $eSpec['label']."**";//of dulu
                        if ($eSpec['label'] == "cash in") {
                            $elementStr .= "";
                        }
                        else {
                            if ($eSpec['label'] == "customer details") {
                                $elementStr .= "billing details";
                            }
                            else {
                                $elementStr .= $eSpec['label'];
                            }
                        }
                        $elementStr .= "";
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
//                                    $elementStr .= $colLabel!="" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>$val</td>" : "<td colspan='3'>$val</td>";
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }
//                                        cekHere($eKey);

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }


        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }

        }
//        arrPrint($elementLabels);
        // arrPrint($items);

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }

                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));
                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";

                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";

                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";

                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";
                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";
                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);
                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

//                    arrPrint($iSpec);
                }
//cekHere(sizeof($itemLabels));
                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                    $rowspan2 = sizeof($dpValueDetils) + 1;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                    $rowspan2 = "";
                }
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {

                        if (isset($mainValues2[$key])) {
                            if ($mainValues2[$key] > 0) {

                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$mainColspan' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues2[$key])) {
                                    $contentStr .= formatField($key, $mainValues2[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues2[$key])) {
                                    $contentStr .= formatField($key, $mainValues2[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";

                            }


                        }

                    }
                    if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "'>&nbsp</td>";
                        $contentStr .= "</tr>";
                        if (strlen($inWord) > 5) {
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td style='vertical-align: top;' colspan='$colspan' rowspan='$rowspan2' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                            $contentStr .= "</tr>";

                        }
                        foreach ($dpFieldName as $dp_fields => $dpFields_alias) {

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='$colspan2' class='text-right'>$dpFields_alias</td>";
                            $contentStr .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                    else {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='$mainColspan' class='text-right'>Vat 10%</td>";
                        $contentStr .= "<td class='text-right'>" . number_format(0 + $mainValues2['grand_ppn']) . "</td>";
                        $contentStr .= "</tr>";

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "'>&nbsp</td>";
                        $contentStr .= "</tr>";
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        if (strlen($inWord) > 5) {

                            $contentStr .= "<td style='vertical-align: top;' colspan='$colspan' rowspan='2' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
//                                    $contentStr .= "</tr>";
                        }
//                                $contentStr .= "<tr line=".__LINE__.">";
//                                arrPrint($mainValues);
                        $contentStr .= "<td style='vertical-falign: middle;' colspan='2' class='text-right text-bold'>Terbayar </td>";
                        $contentStr .= "<td style='vertical-falign: middle;' colspan='' class='text-right text-bold'>" . number_format(0 + $mainValues['nilai_bayar']) . "</td>";
                        $contentStr .= "</tr>";
//                                $contentStr .= "</tr>";
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
//                    $contentStr3 .="<div class='col-md-1 text-right'>$dpFields_alias</div>";
//                    $contentStr3 .="<div class='col-md-2 font-size-1-2'>".formatField($dp_fields,$dpValueDetils[$dp_fields])."</div>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";

            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", $template);
//arrPrint($elementLabels);
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }
//        arrPrint($mainElements);
//        arrPrint($arrTags);
        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceiptBT_":


//        arrPrint($base);

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (isset($elementConfigs[$eKey]['elementType'])) {
                                switch ($elementConfigs[$eKey]['elementType']) {
                                    case "dataModel":
                                        $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] . ":" : "";
                                        break;
                                    case "dataField":
                                        $colLabel = isset($elementConfigs[$eKey]['labelSrc']) ? $elementConfigs[$eKey]['labelSrc'] . ":" : "";
                                        break;
                                }
                            }
                            else {
                                $colLabel = $e . ":";
                            }


                            if (!is_numeric($e)) {
//                                if(!empty($val)){
                                $elementStr .= "<span class=''>$colLabel $val</span><br>";
//                                }
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                    }

                    $elementLabels[$eKey] = $elementStr;
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";

                foreach ($specHeader as $value) {
//                    $elementHdr .= "<div class='panel panel-heading text-center'>";
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
//                    $elementHdr .= "</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;

            }

        }


        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            $no = 0;
            $contentStr = "";
            $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label;
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {
                    $no++;
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
                    foreach ($itemLabels as $key => $label) {
//                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $iSpec[$key];
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);


                        $contentStr .= "</td>";
                    }
                    $contentStr .= "</tr>";
                }
//                arrprint($itemLabels);
//                var_dump($itemLabels);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        $contentStr .= "<td class='text-right'>";
                        if (isset($mainValues[$key])) {

                            $contentStr .= formatField($key, $mainValues[$key]);
                        }
                        else {
                            $contentStr .= "";
                        }
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                    }
                }

                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    $contentStr .= "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
//                            $contentStr.=$mainValues[$key . "_tax"];


                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }

                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

//                if (isset($grandTotal) && $grandTotal > 0) {
//                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
//                    $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total**</td>";
//                    $contentStr .= "<td class='text-right'>";
//
//                    $contentStr .= formatField("total", $grandTotal);
//                    $contentStr .= "</td>";
//                    $contentStr .= "</tr>";
//                }
            }
            $contentStr .= "</table>";


            //region signatures
//            $signatureStr = "<div class='table-responsive'>";
//            $signatureStr = "";
            if (sizeof($signature) > 0) {
//                $signatureStr .= "<table class='table table-bordered table-condensed'>";
//                $signatureStr .= "<tr line=".__LINE__.">";
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
//                    $signatureStr .= "<td class='text-center'>";
                    $signatureStr .= "<div class='panel panel-default  text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
//                    $signatureStr .= $iSpecs['caption_department'];
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
//                    $signatureStr .= "</td>";
                    $elementLabels[$iKey] = $signatureStr;
                }
//                $signatureStr .= "</tr>";
//                $signatureStr .= "</table>";
//                $signatureStr = "";
            }
//            $signatureStr .= "</div>";
            //endregion

            $elementLabels["content"] = $contentStr;
//            $elementLabels["signatures"] = $signatureStr;
        }
        //endregion

        $p = New Layout("$title", "", $template);

//        arrPrint($elementLabels);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
//                cekHitam($tValue);
                $arrTags[$tKey] = $tValue;
            }
        }

//arrPrint($arrTags);
        $p->addTags($arrTags);


        $p->render();

        break;

    case "viewReceiptBT":

//arrPrint($sumRows);
//arrPrint($mainElements);

        function FormatCreditCard($cc)
        {
            $cc = str_replace(array('-', ' '), '', $cc);
            $cc_length = strlen($cc);
            $newCreditCard = substr($cc, -4);
            for ($i = $cc_length - 5; $i >= 0; $i--) {
                if ((($i + 1) - $cc_length) % 4 == 0) {
                    $newCreditCard = '-' . $newCreditCard;
                }
                $newCreditCard = $cc[$i] . $newCreditCard;
            }
            return $newCreditCard;
        }

        $no = 0;
        $total_produk_ord_jml = 0;
        $total_produk_diskon = 0;
        $produk_diskon = 0;
        $grandTotal = 0;
        $kembali = 0;
        $tunai = 0;
        $transactionInfoNotaStr = "";
        $headerNotaStr = "";
        $footerNotaStr = "";
        $contentStr = "";


        $maxStringLength = 42;
        $cPrint = "<IMAGE380X120>" . base_url() . "/assets/images/kop_sbmLine2.png<br>";

        $paymentMethodKey = "";

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {

                $paymentMethodKey = isset($mainElements['paymentMethod']['key']) ? $mainElements['paymentMethod']['key'] : "";

                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    $elementSmallStr = "";
                    $transactionInfoStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                        $elementSmallStr .= "<SMALL><BOLD>#" . strtoupper($eSpec['label']) . "<BR>";
                        $transactionInfoStr .= "<div style='font-size: 10px;' class='text-left text-bold'>#" . strtoupper($eSpec['label']) . "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] : $e;
                            if (!is_numeric($e)) {
                                $elementStr .= "<span class=''>$colLabel : $val</span><br>";
                                $elementSmallStr .= "<SMALL>$colLabel : $val<br>";
                                $transactionInfoStr .= "<div style='font-size: 10px;' class='text-left'>$colLabel : $val</div>";
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                    $elementSmallStr .= "<SMALL>$val<br>";
                                    $transactionInfoStr .= "<div style='font-size: 10px;' class='text-left'>$val</div>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                        $transactionInfoStr .= "<BR>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    $elementSmalls[$eKey] = $elementSmallStr;
                    $transactionInfo[$eKey] = $transactionInfoStr;
                }

                if (sizeof($signature) > 0) {
                    $elementLabels['kasir'] = $cPrint_kasir = $signature['sign_1']['contents'];
                    $elementLabels['customers'] = $cPrint_customers = isset($mainElements['gudang2ID']['labelValue']) ? $mainElements['gudang2ID']['labelValue'] : "";
                    $elementLabels['customers'] = $cPrint_customers !== '' ? $cPrint_customers = $cPrint_customers : $cPrint_customers = isset($signature['sign_2']['contents']) ? $signature['sign_2']['contents'] : "";
                }

                $elementLabels['tanggal'] = $cPrint_tgl = date("Y-m-d", strtotime($main->dtime));
                $elementLabels['hours'] = $cPrint_jam = date("H:i", strtotime($main->dtime));
                $elementLabels['nota'] = $cPrint_nota = isset($main->nomer) ? $main->nomer : 0;
                $elementLabels['jenis_label'] = $cPrint_jenis_label = isset($main->jenis_label) ? $main->jenis_label : "#";

                $transactionInfoNotaStr .= "<div style='font-size: 16px;' class='text-center text-bold'>" . trim(strtoupper($cPrint_jenis_label)) . "</div>";
                $transactionInfoNotaStr .= "<dsh></dsh>";

                $cPrint .= "<CENTER><MEDIUM1><BOLD>" . trim(strtoupper($cPrint_jenis_label)) . "<br>";
                $cPrint .= "<DLINE><BR>";

                if (isset($mainElements['vendorDetails'])) {

//                    arrPrint($elementSmalls);
                    $cPrint .= isset($elementSmalls['deliveryDetails']) ? $elementSmalls['deliveryDetails'] : "";
                    $cPrint .= isset($elementSmalls['vendorDetails']) ? $elementSmalls['vendorDetails'] : "";
                    $cPrint .= isset($elementSmalls['fixedElements']) ? $elementSmalls['fixedElements'] : "";
                    $cPrint .= isset($elementSmalls['paymentMethod']) ? $elementSmalls['paymentMethod'] : "";

                    $cPrint .= "<BR>";
                    $cPrint .= "<DLINE>";
                    $cPrint .= "<BR><BR>";

                    $transactionInfoNotaStr .= isset($transactionInfo['deliveryDetails']) ? $transactionInfo['deliveryDetails'] : "";
                    $transactionInfoNotaStr .= isset($transactionInfo['vendorDetails']) ? $transactionInfo['vendorDetails'] : "";
                    $transactionInfoNotaStr .= isset($transactionInfo['fixedElements']) ? $transactionInfo['fixedElements'] : "";
                    $transactionInfoNotaStr .= isset($transactionInfo['paymentMethod']) ? $transactionInfo['paymentMethod'] : "";

                }
                else {

                    if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {
                        $elementLabels['customers'] = $cPrint_customers = isset($mainElements['customerDetails']['labelValue']) ? $mainElements['customerDetails']['labelValue'] : $signature['customerSignitures']['contents'];
                    }

                    $cPrint .= "<CENTER><SMALL>$cPrint_tgl|$cPrint_jam|" . trim(strtoupper($cPrint_nota)) . "|" . trim(strtoupper($cPrint_kasir)) . "<BR>";
                    $cPrint .= "<CENTER><SMALL>#" . strtoupper($cPrint_customers) . "<BR>";
                    $cPrint .= "<LINE>";

                    $transactionInfoNotaStr .= "<div style='font-size: 10px;' class='text-center'>$cPrint_tgl|$cPrint_jam|" . trim(strtoupper($cPrint_nota)) . "|" . trim(strtoupper($cPrint_kasir)) . "</div>";
                    $transactionInfoNotaStr .= "<div style='font-size: 10px;' class='text-center'>#" . strtoupper($cPrint_customers) . "</div>";
                }

                if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {
                    $cPrint .= "<SMALL>NAMA BARANG / KODE<br>";
                    $cPrint .= "<SMALL>         QTY         HRG         TOTAL  <BR>";

                    if ($mainValues['disc'] > 0) {
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-12 no-padding'>ITEM/KODE</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-center col-xs-3 no-padding'>QTY</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-3 no-padding'>H.SATUAN</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-3 no-padding'>DISC</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-3 no-padding'>TOTAL</div>";
                    }
                    else {
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-12 no-padding'>ITEM/KODE</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-4 no-padding'>QTY</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-4 no-padding'>H.SATUAN</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-4 no-padding'>TOTAL</div>";
                    }


                }
                else {

                    $cPrint .= "<SMALL>NAMA BARANG / KODE           QTY    SATUAN<br>";

                    $headerNotaStr .= "<div style='font-size: 10px;' class='col-xs-8 text-bold no-padding text-left'>ITEM/KODE</div>";
                    $headerNotaStr .= "<div style='font-size: 10px;' class='col-xs-2 text-bold no-padding text-right'>QTY</div>";
                    $headerNotaStr .= "<div style='font-size: 10px;' class='col-xs-2 text-bold no-padding text-right'>SATUAN</div>";
                }

                $cPrint .= "<DLINE>";
                $elementLabels['transaction_info'] = $transactionInfoNotaStr;

            }
        }


        $elementLabels['header_nota'] = $headerNotaStr;
        $contentStr .= "<dline></dline>";
        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            foreach ($items as $id => $iSpec) {

//                arrPrint($detailValues[$id][$valueKey]);

                $no++;
                $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                $contentStr .= "<div>";
                $arrKeysItems = array('');

                $produk_nama = isset($detailValues[$id]['produk_nama']) ? $detailValues[$id]['produk_nama'] : isset($iSpec['produk_nama']) ? $iSpec['produk_nama'] : "--";
                $produk_ord_jml = isset($detailValues[$id]['produk_ord_jml']) ? $detailValues[$id]['produk_ord_jml'] : isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : isset($sumRows) && isset($detailValues[$id]['jml']) ? $detailValues[$id]['jml'] : "";
                $produk_satuan = isset($detailValues[$id]['satuan']) ? $detailValues[$id]['satuan'] : isset($iSpec['satuan']) ? $iSpec['satuan'] : "--";

                $harga = isset($valueKey) && isset($detailValues[$id][$valueKey]) ? $detailValues[$id][$valueKey] : 0;

                $produk_diskon = isset($detailValues[$id]['disc']) ? ($detailValues[$id]['disc'] * $produk_ord_jml) : isset($iSpec['disc']) ? ($iSpec['disc'] * $produk_ord_jml) : 0;
                $add_diskon = isset($mainValues['add_disc']) ? $mainValues['add_disc'] : 0;

                $subtotal = isset($items[$id]['subtotal']) ? $items[$id]['subtotal'] : 0;

                $total_produk_ord_jml += $produk_ord_jml;
                $total_produk_diskon += $produk_diskon;
                $grandTotal += $subtotal;

                $contentStr .= "</div>";

                $item_nama = $produk_nama;
                $item_jml = isset($sumRows) ? isset($detailValues[$id]['jml']) ? number_format($detailValues[$id]['jml']) : number_format($produk_ord_jml) : number_format($produk_ord_jml);
                $item_hrg = number_format($harga);
                $item_subTotal = number_format($subtotal);
                $item_satuan = $produk_satuan;
                $item_disc = $produk_diskon;

                $strCountNama = strlen($item_nama);
                $strCountJml = strlen($item_jml);
                $strCountHrg = strlen($item_hrg);
                $strCountSub = strlen($item_subTotal);
                $strCountSat = strlen($item_satuan);
                $strCountDisc = strlen($item_disc);

                $item_nama_f = "";
                $item_hrg_f = "";
                $item_jml_f = "";
                $item_subTotal_f = "";
                $item_disc_f = number_format($item_disc);

                $maxStringColumn = 0;
                $maxStringColumn1 = 0;
                $maxStringColumn2 = 0;

                if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {

                    if ($strCountNama < $maxStringLength) {
                        $spaceRepeat = (int)$maxStringLength - (int)$strCountNama;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_nama_f = "$item_nama$addSpace";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";
                        }
                    }
                    elseif ($strCountNama == $maxStringLength) {
                        $item_nama_f = "$item_nama";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";
                        }
                    }
                    else {
                        $item_nama_f = "$item_nama";
                        $vowels = array("a", "e", "i", "o", "u");
                        $item_nama_f = str_replace($vowels, " ", ucwords($item_nama_f));
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";
                        }
                        elseif (strlen($item_nama_f) < $maxStringLength) {
                            $spaceRepeat = (int)$maxStringLength - (int)strlen($item_nama_f);
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $item_nama_f = "$item_nama_f$addSpace";
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";
                            }
                        }
                        else {
                            $item_nama_f = "$item_nama";
                            $charDot = 3;
                            $item_nama_f = substr($item_nama_f, 0, 39);
                            $item_nama_f = $item_nama_f . str_repeat(".", $charDot);
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";
                            }
                        }
                    }
                    $maxStringColumn = ($maxStringLength / 3);
                    //region jumlah
                    if ($strCountJml < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountJml;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_jml_f = "$addSpace$item_jml";
                    }
                    elseif ($strCountJml == $maxStringColumn) {
                        $item_jml_f = "$item_jml";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion jumlah
                    //region harga
                    if ($strCountHrg < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountHrg;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_hrg_f = "$addSpace$item_hrg";
                    }
                    elseif ($strCountHrg == $maxStringColumn) {
                        $item_hrg_f = "$item_hrg";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion harga
                    //region subTotal
                    if ($strCountSub < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountSub;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_subTotal_f = "$addSpace$item_subTotal";
                    }
                    elseif ($strCountSub == $maxStringColumn) {
                        $item_subTotal_f = "$item_subTotal";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion subTotal

                    $cPrint .= "<SMALL>$item_jml_f$item_hrg_f$item_subTotal_f<br>";

                    if ($mainValues['disc'] > 0) {
                        $contentStr .= "<div style='font-size: 10px;' class='text-left col-xs-12 no-padding'>$item_nama_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_jml_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_hrg_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_disc_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_subTotal_f</div>";
                    }
                    else {
                        $contentStr .= "<div style='font-size: 10px;' class='text-left col-xs-12 no-padding'>$item_nama_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-4 no-padding'>$item_jml_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-4 no-padding'>$item_hrg_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-4 no-padding'>$item_subTotal_f</div>";
                    }


                }
                else {

                    $maxStringColumnNama = 22;
                    $maxStringColumnQty = 10;
                    $maxStringColumnSatuan = 10;

                    $item_nama_f = "";
                    $item_jml_f = "";
                    $item_satuan_f = "";

                    if ($strCountNama < $maxStringColumnNama) {
                        $spaceRepeat = (int)$maxStringColumnNama - (int)$strCountNama;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_nama_f = "$item_nama$addSpace";
                        if (strlen($item_nama_f) == $maxStringColumnNama) {
                            $item_nama_f = "$item_nama_f";
                        }
                    }
                    elseif ($strCountNama == $maxStringColumnNama) {
                        $item_nama_f = "$item_nama";
                        if (strlen($item_nama_f) == $maxStringColumnNama) {
                            $item_nama_f = "$item_nama_f";
                        }
                    }
                    else {
                        $item_nama_f = "$item_nama";
                        $vowels = array("a", "e", "i", "o", "u");
                        $item_nama_f = str_replace($vowels, " ", ucwords($item_nama_f));
                        if (strlen($item_nama_f) == $maxStringColumnNama) {
                            $item_nama_f = "$item_nama_f";
                        }
                        elseif (strlen($item_nama_f) < $maxStringColumnNama) {
                            $spaceRepeat = (int)$maxStringColumnNama - (int)strlen($item_nama_f);
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $item_nama_f = "$item_nama_f$addSpace";
                            if (strlen($item_nama_f) == $maxStringColumnNama) {
                                $item_nama_f = "<SMALL>$item_nama_f";
                            }
                        }
                        else {
                            $item_nama_f = "$item_nama";
                            $charDot = 3;
                            $item_nama_f = substr($item_nama_f, 0, 29);
                            $item_nama_f = $item_nama_f . str_repeat(".", $charDot);
                            if (strlen($item_nama_f) == $maxStringColumnNama) {
                                $item_nama_f = "$item_nama_f";
                            }
                        }
                    }
                    if ($strCountJml < $maxStringColumnQty) {
                        $spaceRepeat = (int)$maxStringColumnQty - (int)$strCountJml;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_jml_f = "$addSpace$item_jml";
                    }
                    elseif ($strCountJml == $maxStringColumnQty) {
                        $item_jml_f = "$item_jml";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    if ($strCountSat < $maxStringColumnSatuan) {
                        $spaceRepeat = (int)$maxStringColumnSatuan - (int)$strCountSat;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_satuan_f = "$addSpace$item_satuan";
                    }
                    elseif ($strCountSat == $maxStringColumnSatuan) {
                        $item_satuan_f = "$item_satuan";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    $cPrint .= "<SMALL>$item_nama_f$item_jml_f$item_satuan_f<br>";

//                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-12 text-bold no-padding text-left'>$item_nama_f$item_jml_f$item_satuan_f</div>";
                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-8 no-padding text-left'>$item_nama</div>";
                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-2 no-padding text-right'>$item_jml</div>";
                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-2 no-padding text-right'>$item_satuan</div>";

                }
            }

        }

        $cPrint .= "<LINE>";

        $totalItems = count($items);
        $elementLabels["content"] = $contentStr;
        $elementLabels["totalItems"] = "ITEM(s)=" . $totalItems;
        $elementLabels["totalUnit"] = "UNIT(s)=" . $total_produk_ord_jml;
        $elementLabels["totalDiskon"] = $total_produk_diskon;

        if ($total_produk_diskon > 0) {
            $elementLabels["hemat"] = number_format($total_produk_diskon);
            $elementLabels["text_hemat"] = "LEBIH HEMAT  ------------> ";
        }
        else {
            $elementLabels["hemat"] = "";
            $elementLabels["text_hemat"] = "";
        }

        $elementLabels["grandTotal"] = number_format($grandTotal);
        $elementLabels["hargaJual"] = number_format($grandTotal);
        $elementLabels["harusDibayar"] = number_format($grandTotal - $total_produk_diskon - $add_diskon);
        $elementLabels["smallPrint"] = "$template";
        $elementLabels["add_disc"] = number_format($add_diskon);

        $elementLabels["paymentMethodText"] = "";
        $elementLabels["paymentMethodValue"] = "";


        if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {
            $grandTotalc = number_format($grandTotal);
            $strCountGrandTotal = strlen($grandTotalc);
            $grandTotal_f = "";
            if ($strCountGrandTotal < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountGrandTotal;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $grandTotal_f = "$addSpace$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            elseif ($strCountGrandTotal == $maxStringLength) {
                $grandTotal_f = "$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            else {
                // sepertinya belum mungkin grand total sampai melebihi 42 character
            }
            $cPrint .= "<SMALL><BOLD>                             =============";

            $footerNotaStr .= "<div>&nbsp;</div>";
            $footerNotaStr .= "<div style='font-size: 14px;' class='text-bold text-right col-xs-12 no-padding'><i>$grandTotalc</i></div>";
            $footerNotaStr .= "<div style='font-size: 14px;' class='text-bold text-right col-xs-12 no-padding'>=============</div>";

        }

        $footerNotaStr .= "<div>&nbsp;</div>";

        $items = "ITEM(s)=" . $totalItems;
        $strCountItems = strlen($items);
        $items_f = "";
        if ($strCountItems < $maxStringLength) {
            $spaceRepeat = (int)$maxStringLength - (int)$strCountItems;
            $addSpace = str_repeat(' ', $spaceRepeat);
            $items_f = "$addSpace$items";
            if (strlen($items_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$items_f<br>";
            }
        }
        elseif ($strCountItems == $maxStringLength) {
            $items_f = "$items";
            if (strlen($items_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$items_f<br>";
            }
        }
        else {
            // sepertinya belum mungkin items sampai melebihi 42 character
        }

        $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-12 no-padding'>$items</div>";

        $units = "UNIT(s)=" . $total_produk_ord_jml;
        $strCountUnits = strlen($units);
        $units_f = "";
        if ($strCountUnits < $maxStringLength) {
            $spaceRepeat = (int)$maxStringLength - (int)$strCountUnits;
            $addSpace = str_repeat(' ', $spaceRepeat);
            $units_f = "$addSpace$units";
            if (strlen($units_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$units_f<br>";
            }
        }
        elseif ($strCountUnits == $maxStringLength) {
            $units_f = "$units";
            if (strlen($units_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$units_f<br>";
            }
        }
        else {
            // sepertinya belum mungkin units sampai melebihi 42 character
        }
        $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-12 no-padding'>$units</div>";
        $footerNotaStr .= "<div>&nbsp;</div>";

        if (isset($mainElements['paymentMethod'])) {

            if ($mainElements['paymentMethod']['labelValue'] != 'credit') {
                $txtJual = "HARGA ......................:";
                $hrgJual = number_format($grandTotal);
                $strCountTxtJual = strlen($txtJual);
                $strCountJual = strlen($hrgJual);

                $hrgJual_f = "";
                if ($strCountJual < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgJual_f = "$txtJual$addSpace$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                    $hrgJual_f = "$txtJual$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtJual</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgJual</div>";

                $txtDisc = "DISKON TAMBAHAN ............:";
                $hrgDisc = number_format($add_diskon);
                $strCountTxtDisc = strlen($txtDisc);
                $strCountDisc = strlen($hrgDisc);

                $hrgDisc_f = "";
                if ($strCountDisc < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                    $hrgDisc_f = "$txtDisc$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtDisc</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgDisc</div>";

                $txtTotals = "TOTAL YANG HARUS DIBAYAR....:";
                $hrgTotals = number_format($grandTotal);
                $strCountTxtTotals = strlen($txtTotals);
                $strCountTotals = strlen($hrgTotals);

                $hrgTotals_f = "";
                if ($strCountTotals < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTotals_f";
                    }
                }
                elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                    $hrgTotals_f = "$txtTotals$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTotals_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTotals</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTotals</div>";

            }
            else {
                $txtJual = "HARGA ......................:";
                $hrgJual = number_format($grandTotal);
                $strCountTxtJual = strlen($txtJual);
                $strCountJual = strlen($hrgJual);

                $hrgJual_f = "";
                if ($strCountJual < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgJual_f = "$txtJual$addSpace$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                    $hrgJual_f = "$txtJual$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtJual</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgJual</div>";

                $txtDisc = "DISKON TAMBAHAN ............:";
                $hrgDisc = number_format($add_diskon);
                $strCountTxtDisc = strlen($txtDisc);
                $strCountDisc = strlen($hrgDisc);

                $hrgDisc_f = "";
                if ($strCountDisc < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                    $hrgDisc_f = "$txtDisc$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtDisc</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgDisc</div>";

                $txtTotals = "TOTAL YANG BELUM DIBAYAR....:";
                $hrgTotals = number_format($grandTotal);
                $strCountTxtTotals = strlen($txtTotals);
                $strCountTotals = strlen($hrgTotals);

                $hrgTotals_f = "";
                if ($strCountTotals < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                    $hrgTotals_f = "$txtTotals$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTotals</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTotals</div>";

                $footerNotaStr .= "<div>&nbsp;</div>";

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-12 text-left no-padding'>#" . strtoupper($mainElements['paymentMethod']['labelValue']) . "</div>";

                $cPrint .= "<SMALL><br>";
                $cPrint .= "<SMALL><BOLD><CENTER>#" . strtoupper($mainElements['paymentMethod']['labelValue']) . "";

            }


        }
        else {

            if (isset($mainElements['returnMethod'])) {

                $txtJual = "HARGA ......................:";
                $hrgJual = number_format($grandTotal);
                $strCountTxtJual = strlen($txtJual);
                $strCountJual = strlen($hrgJual);

                $hrgJual_f = "";
                if ($strCountJual < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgJual_f = "$txtJual$addSpace$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                    $hrgJual_f = "$txtJual$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtJual</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgJual</div>";

                $txtDisc = "DISKON TAMBAHAN ............:";
                $hrgDisc = number_format($add_diskon);
                $strCountTxtDisc = strlen($txtDisc);
                $strCountDisc = strlen($hrgDisc);

                $hrgDisc_f = "";
                if ($strCountDisc < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                    $hrgDisc_f = "$txtDisc$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtDisc</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgDisc</div>";

                $txtTotals = "TOTAL YANG AKAN DIRETURN....:";
                $hrgTotals = number_format($grandTotal);
                $strCountTxtTotals = strlen($txtTotals);
                $strCountTotals = strlen($hrgTotals);

                $hrgTotals_f = "";
                if ($strCountTotals < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                    $hrgTotals_f = "$txtTotals$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTotals</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTotals</div>";
                $footerNotaStr .= "<div>&nbsp;</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>#" . strtoupper($mainElements['returnMethod']['labelValue']) . "</div>";

//                if($mainElements['returnMethod']['labelValue']=='cash'){
//                    $cPrint    .= "<BR>";
//                    $cPrint    .= "<SMALL><BOLD><CENTER>#".strtoupper($mainElements['returnMethod']['labelValue'])."";
//                }
//                else{
//                    $cPrint    .= "<BR>";
//                    $cPrint    .= "<SMALL><BOLD><CENTER>#".strtoupper($mainElements['returnMethod']['labelValue'])."";
//                }

            }


            if (isset($sumRows) && sizeof($sumRows) > 0) {

                $maxStringLabel = 31;
                $maxStringValue = 11;

                foreach ($sumRows as $key => $label) {
                    $cPrint .= "<BR>";
                    $label = strtoupper($label);
                    $strCountLabel = strlen($label);
                    $label_f = "";

                    if ($strCountLabel < $maxStringLabel) {
                        $spaceRepeat = (int)$maxStringLabel - (int)$strCountLabel;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $label_f = "$addSpace$label";
                        if (strlen($label_f) == $strCountLabel) {
                            $label_f = "$label_f";
                        }
                    }
                    elseif ((int)$strCountLabel == $maxStringLabel) {
                        $label_f = "$label";
                        if (strlen($label_f) == $maxStringLabel) {
                            $label_f = "$label_f";
                        }
                    }
                    else {
                        // sepertinya belum mungkin units sampai melebihi 42 character
                    }

                    $values_f = "";

                    if (isset($mainValues[$key])) {

                        $values = number_format($mainValues[$key]);
                        $strCountValues = strlen($values);

                        if ($strCountValues < $maxStringValue) {
                            $spaceRepeat = (int)$maxStringValue - (int)$strCountValues;
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $values_f = "$addSpace$values";
                            if (strlen($values_f) == (int)$maxStringValue) {
                                $values_f = "$values_f";
                            }
                        }
                        elseif ((int)$strCountValues == (int)$maxStringValue) {
                            $values_f = "$values";
                            if (strlen($values_f) == $maxStringValue) {
                                $values_f = "$values_f";
                            }
                        }
                        else {
                            // sepertinya belum mungkin units sampai melebihi 42 character
                        }

                    }
                    else {
                        $values_f = "          0";
                    }

                    $cPrint .= "<SMALL><BOLD>$label_f$values_f";

                    $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$label</div>";
                    $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>" . $mainValues[$key] . "</div>";

                }
            }
        }


        if ($paymentMethodKey == 'cash') {

            $paymentMethodValue = isset($mainValues['bayar']) ? $mainValues['bayar'] : $grandTotal;
            $kembali = isset($mainValues['kembali']) ? $mainValues['kembali'] : ($paymentMethodValue - $grandTotal);
            $elementLabels["paymentMethodText"] = "TUNAI.......................:";
            $elementLabels["paymentMethodValue"] = number_format($paymentMethodValue);
            $elementLabels["kembaliText"] = "KEMBALI.....................:";
            $elementLabels["kembali"] = number_format($kembali);

            $txtTunai = "TUNAI.......................:";
            $hrgTunai = number_format($paymentMethodValue);
            $strCountTxtTunai = strlen($txtTunai);
            $strCountTunai = strlen($hrgTunai);

            $hrgTunai_f = "";
            if ($strCountTunai < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                $hrgTunai_f = "$txtTunai$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

            $txtKembali = "KEMBALI.....................:";
            $hrgKembali = number_format($kembali);
            $strCountTxtKembali = strlen($txtKembali);
            $strCountKembali = strlen($hrgKembali);

            $hrgKembali_f = "";
            if ($strCountKembali < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                $hrgKembali_f = "$txtKembali$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";

        }
        elseif ($paymentMethodKey == 'credit_card') {

//            $type = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account']) ? $masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account'] : $masterGates['credit_account'];
            $type = isset($masterGates['credit_account']) ? $masterGates['credit_account'] : "";

            $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
            $cardNumber = FormatCreditCard($cardNumber);
            $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

            $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";
//arrPrint($mainValues);
//            if($total_produk_diskon>0){
            $grandTotal = $grandTotal - $total_produk_diskon - $add_diskon;
//            }else{
//
//            }

            $paymentMethodText = "Kartu Kredit";
            $type = str_replace('_', ' ', $type);
            $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
            $elementLabels["paymentMethodText"] = "CC." . $cardNumber . " ........:";
            $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
            $elementLabels["kembaliText"] = "<span class='text-capitalize'>Amount: " . round($grandTotal) . "</span>";
            $elementLabels["kembali"] = " ";

            $txtTunai = "CC." . $cardNumber . " ........:";
            $hrgTunai = number_format($grandTotal);
            $strCountTxtTunai = strlen($txtTunai);
            $strCountTunai = strlen($hrgTunai);

            $hrgTunai_f = "";
            if ($strCountTunai < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                $hrgTunai_f = "$txtTunai$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

            $txtKembali = "Amount: $grandTotal";
            $hrgKembali = " ";
            $strCountTxtKembali = strlen($txtKembali);
            $strCountKembali = strlen($hrgKembali);

            $hrgKembali_f = "";
            if ($strCountKembali < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                $hrgKembali_f = "$txtKembali$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";


        }
        elseif ($paymentMethodKey == 'debit_card') {

            $type = isset($masterGates['debit_account']) ? $masterGates['debit_account'] : "";
            $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
            $cardNumber = FormatCreditCard($cardNumber);
            $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

            $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";
            $paymentMethodText = "Kartu Debit";
            $type = str_replace('_', ' ', $type);

            $grandTotal = $grandTotal - $total_produk_diskon - $add_diskon;

            $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;

            $elementLabels["paymentMethodText"] = "DC." . $cardNumber . " ........:";
            $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
            $elementLabels["kembaliText"] = "<span class='text-capitalize'>Amount: " . round($grandTotal) . "</span>";
            $elementLabels["kembali"] = " ";

            $txtTunai = "DC." . $cardNumber . " ........:";
            $hrgTunai = number_format($grandTotal);
            $strCountTxtTunai = strlen($txtTunai);
            $strCountTunai = strlen($hrgTunai);

            $hrgTunai_f = "";
            if ($strCountTunai < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                $hrgTunai_f = "$txtTunai$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

            $txtKembali = "Amount: $grandTotal";
            $hrgKembali = " ";
            $strCountTxtKembali = strlen($txtKembali);
            $strCountKembali = strlen($hrgKembali);

            $hrgKembali_f = "";
            if ($strCountKembali < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                $hrgKembali_f = "$txtKembali$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";

        }
        else {

            $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
            $elementLabels["paymentMethodText"] = "--";
            $elementLabels["paymentMethodValue"] = "--";
            $elementLabels["kembaliText"] = "";
            $elementLabels["kembali"] = "";

            if (isset($mainElements['paymentMethod'])) {
                $txtTunai = " ";
                $hrgTunai = " ";
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f<br>";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f<br>";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

                $txtKembali = " ";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f<br>";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f<br>";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";

            }

        }

        //endregion

        //custom elementLabels

        $elementLabels['footer_nota'] = $footerNotaStr;

        $p = New Layout("$title", "", "application/template/582sr.html");
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        $cPrint .= "<SMALL>                                          <br>";
        $cPrint .= "<CENTER><SMALL>** Terima Kasih **<br>";
        $cPrint .= "<SMALL>------------------------------------------<br>";


        $arrTags['cPrint'] = $cPrint;

        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceiptOpname":

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
//                                    $elementStr .= $colLabel!="" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>$val</td>" : "<td colspan='3'>$val</td>";
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }
//                                        cekHere($eKey);

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }


                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));
                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";

                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "0";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        if (is_numeric($val)) {

                            if (!isset($total_bawah[$key])) {
                                $total_bawah[$key] = 0;
                            }
                            $total_bawah[$key] += $val;
                        }
                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";
                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";
                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);
                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;


                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

                // arrPrint($mainValues);
                // arrPrint($sumRows);
//                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
//                    arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

//                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
//                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {
                            if ($mainValues[$key] > 0) {
//                                cekHere($mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }

                        }
//                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }


                $contentStr .= "<tr class='text-muted' style='font-weight:bold;'>";
                $contentStr .= "<td></td>";
                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<td>";
                    if (isset($total_bawah[$key])) {
                        if (is_numeric($total_bawah[$key])) {
                            $contentStr .= formatField($key, $total_bawah[$key]);
                        }
                        else {
                            $contentStr .= "";
                        }
                    }
                    $contentStr .= "</td>";
                }
                $contentStr .= "</tr>";

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr line=" . __LINE__ . ">";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr line=" . __LINE__ . ">";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }


            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
//                    $contentStr3 .="<div class='col-md-1 text-right'>$dpFields_alias</div>";
//                    $contentStr3 .="<div class='col-md-2 font-size-1-2'>".formatField($dp_fields,$dpValueDetils[$dp_fields])."</div>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";

            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }


        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewSmallReceipt":

        function FormatCreditCard($cc)
        {
            $cc = str_replace(array('-', ' '), '', $cc);
            $cc_length = strlen($cc);
            $newCreditCard = substr($cc, -4);
            for ($i = $cc_length - 5; $i >= 0; $i--) {
                if ((($i + 1) - $cc_length) % 4 == 0) {
                    $newCreditCard = '-' . $newCreditCard;
                }
                $newCreditCard = $cc[$i] . $newCreditCard;
            }
            return $newCreditCard;
        }

        $paymentMethodKey = "";

        if (isset($mainElements)) {

//            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {

                $paymentMethodKey = isset($mainElements['paymentMethod']['key']) ? $mainElements['paymentMethod']['key'] : "";

                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] : $e;
                            if (!is_numeric($e)) {
                                $elementStr .= "<span class=''>$colLabel : $val</span><br>";
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                }

                if (sizeof($signature) > 0) {
                    $elementLabels['kasir'] = $signature['sign_1']['contents'];
                    $elementLabels['customers'] = $signature['customerSignitures']['contents'];
                }

                $elementLabels['tanggal'] = date("Y-m-d", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['hours'] = date("H:i", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['nota'] = isset($mainElements['fixedElements']['contents']['No']) ? $mainElements['fixedElements']['contents']['No'] : "";

            }
        }

        if (isset($headerTablesSmall)) {

            if (sizeof($headerTablesSmall) > 0) {

            }

        }

        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            $no = 0;
            $total_produk_ord_jml = 0;
            $total_produk_diskon = 0;
            $produk_diskon = 0;
            $grandTotal = 0;
            $kembali = 0;
            $tunai = 0;
            $contentStr = "";

            foreach ($items as $id => $iSpec) {
//                arrPrint($iSpec);
                $no++;
                $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                $contentStr .= "<div>";
                $arrKeysItems = array('');

                $produk_ppn = isset($detailValues[$id]['ppn']) ? $detailValues[$id]['ppn'] : $iSpec['ppn'];
                $produk_nama = isset($detailValues[$id]['produk_nama']) ? $detailValues[$id]['produk_nama'] : $iSpec['produk_nama'];
                $produk_ord_jml = isset($detailValues[$id]['produk_ord_jml']) ? $detailValues[$id]['produk_ord_jml'] : $iSpec['produk_ord_jml'];
                $harga = isset($detailValues[$id]['harga_nett1']) ? ($detailValues[$id]['harga_nett1']) : ($iSpec['harga_nett1']);

                $produk_diskon = isset($detailValues[$id]['disc']) ? ($detailValues[$id]['disc'] * $produk_ord_jml) : ($iSpec['disc'] * $produk_ord_jml);
                $add_diskon = isset($mainValues['add_disc']) ? $mainValues['add_disc'] : 0;


//                $subtotal = isset($detailValues[$id]['subtotal']) ? $detailValues[$id]['subtotal'] : $iSpec['subtotal'];
                $subtotal = isset($items[$id]['subtotal']) ? $items[$id]['subtotal'] : 0;
                $total_produk_ord_jml += $produk_ord_jml;
                $total_produk_diskon += $produk_diskon;
                $grandTotal += $subtotal;

                $contentStr .= "<div style='font-size: .9em;' class='col-xs-12 text-bold no-padding text-left'>";
                $contentStr .= $produk_nama;
                $contentStr .= "</div>";

                if ($mainValues['disc'] > 0) {
                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_diskon);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }
                else {
                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }

                $contentStr .= "</div>";
            }
//arrPrint($main);

            $totalItems = count($items);
            $elementLabels["content"] = $contentStr;
            $elementLabels["totalItems"] = "ITEM(s)=" . $totalItems;
            $elementLabels["totalUnit"] = "UNIT(s)=" . $total_produk_ord_jml;
            $elementLabels["totalDiskon"] = $total_produk_diskon;

            if ($total_produk_diskon > 0) {
                $elementLabels["hemat"] = number_format($total_produk_diskon);
                $elementLabels["text_hemat"] = "LEBIH HEMAT  ------------> ";
            }
            else {
                $elementLabels["hemat"] = "";
                $elementLabels["text_hemat"] = "";
            }


            $elementLabels["grandTotal"] = number_format($grandTotal);
            $elementLabels["hargaJual"] = number_format($grandTotal);
            $elementLabels["harusDibayar"] = number_format($grandTotal - $total_produk_diskon - $add_diskon);
            $elementLabels["smallPrint"] = "$template";
            $elementLabels["add_disc"] = number_format($add_diskon);

            $elementLabels["paymentMethodText"] = "";
            $elementLabels["paymentMethodValue"] = "";

            if ($paymentMethodKey == 'cash') {

                $paymentMethodValue = isset($mainValues['bayar']) ? $mainValues['bayar'] : $grandTotal;
                $kembali = isset($mainValues['kembali']) ? $mainValues['kembali'] : ($paymentMethodValue - $grandTotal);
                $elementLabels["paymentMethodText"] = "TUNAI.......................:";
                $elementLabels["paymentMethodValue"] = number_format($paymentMethodValue);
                $elementLabels["kembaliText"] = "KEMBALI.....................:";
                $elementLabels["kembali"] = number_format($kembali);

            }
            elseif ($paymentMethodKey == 'credit_card') {
//                $type = isset($main['paymentMethod_credit_account']) ? $main['paymentMethod_credit_account'] : $main['credit_account'];
//                $type = isset($main['paymentMethod_' . $paymentMethodKey . '_credit_account']) ? $main['paymentMethod_' . $paymentMethodKey . '_credit_account'] : $main['credit_account'];
//                $cardNumber = isset($main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number'])?$main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number']:$main['card_number'];
//                $cardName = isset($main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name'])?$main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name']:$main['card_name'];

                $type = isset($main['credit_account']) ? $main['credit_account'] : "";

                $cardNumber = isset($main['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($main['card_name']) ? $main['card_name'] : "";

                $paymentMethodText = "Kartu Kredit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "CC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";
            }
            elseif ($paymentMethodKey == 'debit_card') {
//                $type = isset($main['paymentMethod_' . $paymentMethodKey . '_debit_account'])?$main['paymentMethod_' . $paymentMethodKey . '_debit_account']:$main['debit_account'];
                $type = isset($main['debit_account']) ? $main['debit_account'] : "";
//                $cardNumber = isset($main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number'])?$main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number']:$main['card_number'];
//                $cardName = isset($main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name'])?$main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name']:$main['card_name'];
                $cardNumber = isset($main['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($main['card_name']) ? $main['card_name'] : "";
                $paymentMethodText = "Kartu Debit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "DC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";
            }
            else {
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "--";
                $elementLabels["paymentMethodValue"] = "--";
                $elementLabels["kembaliText"] = "";
                $elementLabels["kembali"] = "";
            }
        }
        //endregion

        $p = New Layout("$title", "", "application/template/582sr.html");
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        $p->addTags($arrTags);
        $p->render();


        break;

    case "viewSmallReceiptBT":

        function FormatCreditCard($cc)
        {
            $cc = str_replace(array('-', ' '), '', $cc);
            $cc_length = strlen($cc);
            $newCreditCard = substr($cc, -4);
            for ($i = $cc_length - 5; $i >= 0; $i--) {
                if ((($i + 1) - $cc_length) % 4 == 0) {
                    $newCreditCard = '-' . $newCreditCard;
                }
                $newCreditCard = $cc[$i] . $newCreditCard;
            }
            return $newCreditCard;
        }

        //smallprint bluetooth
        $maxStringLength = 42;
        $cPrint = "<CENTER><BOLD>SUMBER BERKAT MAKMUR<br>";
        $cPrint .= "<CENTER><SMALL>Jln. Arah Tanjungmera<br>";
        $cPrint .= "<CENTER><SMALL>Manembo Nembo Matuari Bitung<br>";
        $cPrint .= "<CENTER><SMALL>Sulawesi Utara<br>";
        $cPrint .= "<CENTER><SMALL>Telp: (xxx) xxxxxxx<br>";
        $cPrint .= "<CENTER><SMALL>NPWP: 908093693823000<br>";
        $cPrint .= "<SMALL>------------------------------------------<br>";


        $paymentMethodKey = "";

        if (isset($mainElements)) {

//            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {

                $paymentMethodKey = isset($mainElements['paymentMethod']['key']) ? $mainElements['paymentMethod']['key'] : "";

                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] : $e;
                            if (!is_numeric($e)) {
                                $elementStr .= "<span class=''>$colLabel : $val</span><br>";
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                }

                if (sizeof($signature) > 0) {
                    $elementLabels['kasir'] = $cPrint_kasir = $signature['sign_1']['contents'];
                    $elementLabels['customers'] = $cPrint_customers = isset($signature['customerSignitures']['contents']) ? $signature['customerSignitures']['contents'] : "--";
                }

                $elementLabels['tanggal'] = $cPrint_tgl = date("Y-m-d", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['hours'] = $cPrint_jam = date("H:i", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['nota'] = $cPrint_nota = $mainElements['fixedElements']['contents']['No'];

                $cPrint .= "<CENTER><SMALL><BOLD>$cPrint_tgl $cPrint_jam/$cPrint_nota/$cPrint_kasir<br>";
                $cPrint .= "<CENTER><SMALL>#$cPrint_customers<br>";
                $cPrint .= "<SMALL>------------------------------------------";
                $cPrint .= "<BOLD><SMALL>NAMA BARANG / KODE<br>";
                $cPrint .= "<BOLD><SMALL>         QTY         HRG         TOTAL  <br>";
                $cPrint .= "<SMALL>==========================================";


            }
        }

        if (isset($headerTablesSmall)) {

            if (sizeof($headerTablesSmall) > 0) {

            }

        }

//arrprint($items);
        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            $no = 0;
            $total_produk_ord_jml = 0;
            $total_produk_diskon = 0;
            $produk_diskon = 0;
            $grandTotal = 0;
            $kembali = 0;
            $tunai = 0;
            $contentStr = "";

            foreach ($items as $id => $iSpec) {
//                arrPrint($iSpec);
                $no++;
                $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                $contentStr .= "<div>";
                $arrKeysItems = array('');

//                $produk_ppn = isset($detailValues[$id]['ppn']) ? $detailValues[$id]['ppn'] : $iSpec['ppn'];
                $produk_nama = isset($detailValues[$id]['produk_nama']) ? $detailValues[$id]['produk_nama'] : $iSpec['produk_nama'];
                $produk_ord_jml = isset($detailValues[$id]['produk_ord_jml']) ? $detailValues[$id]['produk_ord_jml'] : $iSpec['produk_ord_jml'];
                $harga = isset($detailValues[$id]['harga_nett1']) ? ($detailValues[$id]['harga_nett1']) : ($iSpec['harga_nett1']);

                $produk_diskon = isset($detailValues[$id]['disc']) ? ($detailValues[$id]['disc'] * $produk_ord_jml) : ($iSpec['disc'] * $produk_ord_jml);
                $add_diskon = isset($mainValues['add_disc']) ? $mainValues['add_disc'] : 0;


//                $subtotal = isset($detailValues[$id]['subtotal']) ? $detailValues[$id]['subtotal'] : $iSpec['subtotal'];
                $subtotal = isset($items[$id]['subtotal']) ? $items[$id]['subtotal'] : 0;
                $total_produk_ord_jml += $produk_ord_jml;
                $total_produk_diskon += $produk_diskon;
                $grandTotal += $subtotal;

                if ($mainValues['disc'] > 0) {
                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_diskon);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }
                else {
                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }

                $contentStr .= "</div>";

                $item_nama = $produk_nama;
                $item_jml = number_format($produk_ord_jml);
                $item_hrg = number_format($harga);
                $item_subTotal = number_format($subtotal);


                $strCountNama = strlen($item_nama);
                $strCountJml = strlen($item_jml);
                $strCountHrg = strlen($item_hrg);
                $strCountSub = strlen($item_subTotal);

                $item_nama_f = "";
                $item_hrg_f = "";
                $item_jml_f = "";
                $item_subTotal_f = "";

                if ($strCountNama > 0) {
                    if ($strCountNama < $maxStringLength) {
                        $spaceRepeat = (int)$maxStringLength - (int)$strCountNama;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_nama_f = "$item_nama$addSpace";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";

                        }
                    }
                    elseif ($strCountNama == $maxStringLength) {
                        $item_nama_f = "$item_nama";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";

                        }
                    }
                    else {
                        $item_nama_f = "$item_nama";
                        $vowels = array("a", "e", "i", "o", "u");
                        $item_nama_f = str_replace($vowels, " ", ucwords($item_nama_f));
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";

                        }
                        elseif (strlen($item_nama_f) < $maxStringLength) {
                            $spaceRepeat = (int)$maxStringLength - (int)strlen($item_nama_f);
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $item_nama_f = "$item_nama_f$addSpace";
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";

                            }
                        }
                        else {
                            $item_nama_f = "$item_nama";
                            $charDot = 3;
                            $item_nama_f = substr($item_nama_f, 0, 39);
                            $item_nama_f = $item_nama_f . str_repeat(".", $charDot);
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";

                            }
                        }
                    }
                }
                if ($strCountJml > 0 && $strCountHrg > 0 && $strCountSub > 0) {
                    $maxStringColumn = ($maxStringLength / 3);
                    //region jumlah
                    if ($strCountJml < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountJml;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_jml_f = "$addSpace$item_jml";
                    }
                    elseif ($strCountJml == $maxStringColumn) {
                        $item_jml_f = "$item_jml";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion jumlah
                    //region harga
                    if ($strCountHrg < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountHrg;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_hrg_f = "$addSpace$item_hrg";
                    }
                    elseif ($strCountHrg == $maxStringColumn) {
                        $item_hrg_f = "$item_hrg";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion harga
                    //region subTotal
                    if ($strCountSub < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountSub;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_subTotal_f = "$addSpace$item_subTotal";
                    }
                    elseif ($strCountSub == $maxStringColumn) {
                        $item_subTotal_f = "$item_subTotal";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion subTotal
                    $cPrint .= "<SMALL>$item_jml_f$item_hrg_f$item_subTotal_f";

                }

            }

            $cPrint .= "<SMALL>------------------------------------------<br>";


            $totalItems = count($items);
            $elementLabels["content"] = $contentStr;
            $elementLabels["totalItems"] = "ITEM(s)=" . $totalItems;
            $elementLabels["totalUnit"] = "UNIT(s)=" . $total_produk_ord_jml;
            $elementLabels["totalDiskon"] = $total_produk_diskon;

            if ($total_produk_diskon > 0) {
                $elementLabels["hemat"] = number_format($total_produk_diskon);
                $elementLabels["text_hemat"] = "LEBIH HEMAT  ------------> ";
            }
            else {
                $elementLabels["hemat"] = "";
                $elementLabels["text_hemat"] = "";
            }


            $elementLabels["grandTotal"] = number_format($grandTotal);
            $elementLabels["hargaJual"] = number_format($grandTotal);
            $elementLabels["harusDibayar"] = number_format($grandTotal - $total_produk_diskon - $add_diskon);
            $elementLabels["smallPrint"] = "$template";
            $elementLabels["add_disc"] = number_format($add_diskon);

            $elementLabels["paymentMethodText"] = "";
            $elementLabels["paymentMethodValue"] = "";


            $grandTotalc = number_format($grandTotal);
            $strCountGrandTotal = strlen($grandTotalc);
            $grandTotal_f = "";
            if ($strCountGrandTotal < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountGrandTotal;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $grandTotal_f = "$addSpace$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            elseif ($strCountGrandTotal == $maxStringLength) {
                $grandTotal_f = "$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            else {
                // sepertinya belum mungkin grand total sampai melebihi 42 character
            }
            $cPrint .= "<SMALL><BOLD>                             =============";


            $items = "ITEM(s)=" . $totalItems;
            $strCountItems = strlen($items);
            $items_f = "";
            if ($strCountItems < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountItems;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $items_f = "$addSpace$items";
                if (strlen($items_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$items_f";
                }
            }
            elseif ($strCountItems == $maxStringLength) {
                $items_f = "$items";
                if (strlen($items_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$items_f";
                }
            }
            else {
                // sepertinya belum mungkin items sampai melebihi 42 character
            }

            $units = "UNIT(s)=" . $total_produk_ord_jml;
            $strCountUnits = strlen($units);
            $units_f = "";
            if ($strCountUnits < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountUnits;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $units_f = "$addSpace$units";
                if (strlen($units_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$units_f";
                }
            }
            elseif ($strCountUnits == $maxStringLength) {
                $units_f = "$units";
                if (strlen($units_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$units_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }


            $txtJual = "HARGA ......................:";
            $hrgJual = number_format($grandTotal);
            $strCountTxtJual = strlen($txtJual);
            $strCountJual = strlen($hrgJual);

            $hrgJual_f = "";
            if ($strCountJual < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgJual_f = "$txtJual$addSpace$hrgJual";
                if (strlen($hrgJual_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgJual_f";
                }
            }
            elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                $hrgJual_f = "$txtJual$hrgJual";
                if (strlen($hrgJual_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgJual_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $txtDisc = "DISKON TAMBAHAN ............:";
            $hrgDisc = number_format($add_diskon);
            $strCountTxtDisc = strlen($txtDisc);
            $strCountDisc = strlen($hrgDisc);

            $hrgDisc_f = "";
            if ($strCountDisc < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                if (strlen($hrgDisc_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgDisc_f";
                }
            }
            elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                $hrgDisc_f = "$txtDisc$hrgDisc";
                if (strlen($hrgDisc_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgDisc_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $txtTotals = "TOTAL YANG HARUS DIBAYAR....:";
            $hrgTotals = number_format($grandTotal);
            $strCountTxtTotals = strlen($txtTotals);
            $strCountTotals = strlen($hrgTotals);

            $hrgTotals_f = "";
            if ($strCountTotals < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                if (strlen($hrgTotals_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTotals_f";
                }
            }
            elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                $hrgTotals_f = "$txtTotals$hrgTotals";
                if (strlen($hrgTotals_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTotals_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }


            if ($paymentMethodKey == 'cash') {

                $paymentMethodValue = isset($mainValues['bayar']) ? $mainValues['bayar'] : $grandTotal;
                $kembali = isset($mainValues['kembali']) ? $mainValues['kembali'] : ($paymentMethodValue - $grandTotal);
                $elementLabels["paymentMethodText"] = "TUNAI.......................:";
                $elementLabels["paymentMethodValue"] = number_format($paymentMethodValue);
                $elementLabels["kembaliText"] = "KEMBALI.....................:";
                $elementLabels["kembali"] = number_format($kembali);

                $txtTunai = "TUNAI.......................:";
                $hrgTunai = number_format($paymentMethodValue);
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = "KEMBALI.....................:";
                $hrgKembali = number_format($kembali);
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }


            }
            elseif ($paymentMethodKey == 'credit_card') {
                $type = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account']) ? $masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account'] : $masterGates['credit_account'];
//                $cardNumber = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number']:$masterGates['card_number'];
//                $cardName = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name']:$masterGates['card_name'];

                $type = isset($masterGates['credit_account']) ? $masterGates['credit_account'] : "";

                $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";

                $paymentMethodText = "Kartu Kredit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "CC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";

                $txtTunai = "CC." . $cardNumber . " .....:";
                $hrgTunai = number_format($grandTotal);
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = "Mr/Ms.$cardName-$grandTotal";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

            }
            elseif ($paymentMethodKey == 'debit_card') {
//                $type = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account']:$masterGates['debit_account'];
                $type = isset($masterGates['debit_account']) ? $masterGates['debit_account'] : "";
//                $cardNumber = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number']:$masterGates['card_number'];
//                $cardName = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name']:$masterGates['card_name'];
                $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";
                $paymentMethodText = "Kartu Debit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "DC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";

                $txtTunai = "DC." . $cardNumber . " .....:";
                $hrgTunai = number_format($grandTotal);
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = "Mr/Ms.$cardName-$grandTotal";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

            }
            else {
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "--";
                $elementLabels["paymentMethodValue"] = "--";
                $elementLabels["kembaliText"] = "";
                $elementLabels["kembali"] = "";

                $txtTunai = " ";
                $hrgTunai = " ";
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = " ";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

            }
        }
        //endregion

        $p = New Layout("$title", "", "application/template/582sr.html");
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        $cPrint .= "<SMALL>                                          ";
        $cPrint .= "<SMALL>                                          ";
        $cPrint .= "<SMALL>                                          <br>";

        $cPrint .= "<CENTER><SMALL>** Terima Kasih **<br>";
        $cPrint .= "<SMALL>------------------------------------------<br>";
//        $cPrint    .= "<SMALL>------------------------------------------";
        $cPrint .= "<QR>12345678<br>";

        $arrTags['cPrint'] = $cPrint;

        $p->addTags($arrTags);
        $p->render();

        break;

    case "selectPaymentSrc":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $strContent = "";
        $arrayPpnDisabled = (isset($ppnDisabled) && (sizeof($ppnDisabled) > 0)) ? $ppnDisabled : array();
        $kelebihanBayarMethod = (isset($kelebihanBayar)) ? $kelebihanBayar : false;

        $p = New Layout("$title", "$subTitle", "application/template/payment.html");
//        die($selectProcessor);
//    arrPrint($items);
        //region onprogress
        if (isset($dueDateReader) && $dueDateReader == "true") {
            $itemLabels["due_date"] = "due date";
            $itemLabels["aging"] = "aging (days)";
        }
        if ($prePrint == true) {
            // cekHere("$prePrint");
            $itemLabels["prePrint"] = "print";
        }


        $strPayment = "";
        $strElements = "";
        if (sizeof($items) > 0) {

            if (isset($isPaymentRadioSelect) && $isPaymentRadioSelect == true) {
                $strContent .= "<div class='card bg-danger text-center text-bold'> HANYA BISA PILIH SALAH SATU </div>";
            }

            $strContent .= "<table class='table cTable-modal table-condensed table-responsive no-padding table-bordered'>";

            $strContent .= "<thead>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($itemLabels) > 0) {
                $strContent .= "<th class='text-muted text-center'>";
                $strContent .= "select";
                $strContent .= "</th>";
                foreach ($itemLabels as $key => $label) {
                    $strContent .= "<th class='text-muted'>";
                    $strContent .= $label;
                    $strContent .= "</th>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</thead>";

// arrPrint($items);
            //region tblBody
            $strContent .= "<tbody>";
            foreach ($items as $key => $val) {
//arrPrint($val);
                $strContent .= "<tr line=" . __LINE__ . ">";
                if (sizeof($itemLabels) > 0) {

                    $qstrLabels = array(
                        "transaksi_id" => "trID",
                        "nomer" => "nomer",
                        "extern_id" => "xID",
                        "tagihan" => "tagihan",
                        "terbayar" => "terbayar",
                        "sisa" => "sisa",
                        "diskon" => "diskon",
                        "extern_nama" => "xID",
                        "tagihan_valas" => "tagihan_valas",
                        "terbayar_valas" => "terbayar_valas",
                        "sisa_valas" => "sisa_valas",
                        "diskon_valas" => "diskon_valas",
                        "valas_id" => "valas_id",
                        "valas_nama" => "valas_nama",
                        "valas_nilai" => "valas_nilai",
                        "id_master" => "id_master",
                        "extern_label2" => "pihakMainName",
                        "extern_nilai2" => "extern_nilai2",
                        "extern_nilai3" => "extern_nilai3",
                        "extern_nilai4" => "extern_nilai4",
                        "pph_23" => "pph_23",
                        "ppn_sisa" => "ppn_payment",
                        "ppn" => "ppn",
                        "extern2_id" => "extern2_id",
                        "extern2_nama" => "extern2_nama",
                        "extern_jenis" => "extern_jenis",
                        "jenis_master" => "jenis_master",
//                        "id_master" => "id_master",
                    );
                    $qstr = "";
                    foreach ($qstrLabels as $key => $label) {
                        $qstr .= "&$key=" . $val[$key];
                    }

                    $strContent .= "<td class='" . $val['class_bg'] . "text-muted text-center'>";

                    $checked = "";
                    if (isset($ses_items[$val['transaksi_id']])) {
                        $checked = "checked";
                    }

                    $disabled = "";
                    if (sizeof($arrayPpnDisabled) > 0) {
                        if (in_array($val['transaksi_id'], $arrayPpnDisabled)) {
                            $disabled = "disabled";
                        }
                    }

                    $disabledLockerTransaksi = "";
                    if (sizeof($lockerDisabled) > 0) {
                        if (in_array($val['transaksi_id'], $lockerDisabled)) {
                            $disabledLockerTransaksi = "disabled";
                        }
                    }

                    $strContent .= "<div class='funkyradio-success'>";

                    $strContent .= "<input class='chRadio' type=checkbox $checked $disabled $disabledLockerTransaksi value='" . $val['transaksi_id'] . "' id='opt" . $val['transaksi_id'] . "' onclick=\"document.getElementById('result').src='" . base_url() . "$selectProcessor/$jenisTr" . "?$qstr&state='+this.checked;\">";
                    $strContent .= "<label for='opt" . $val['transaksi_id'] . "' class='no-padding no-margin' title='select this entry'>";
                    $strContent .= "</label>";
                    $strContent .= "</div class='funkyradio-success'>";

                    $strContent .= "</td>";
                    foreach ($itemLabels as $key => $label) {
                        $strContent .= "<td  class='" . $val['class_bg'] . "'>";
                        if (isset($val[$key])) {
                            $strContent .= strlen($val[$key]) > 0 ? formatField($key, $val[$key]) : "-";
                            if (is_numeric($val[$key])) {
                                if (!isset($total[$key])) {
                                    $total[$key] = 0;
                                }
                                $total[$key] += $val[$key];
                            }
                        }
                        else {
                            $strContent .= "-";
                        }
                        $strContent .= "</td>";


                    }
                }
                $strContent .= "</tr>";
            }
            $strContent .= "</tbody>";
            //endregion

            //region footer summary bawah
            $strContent .= "<tfoot>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            $strContent .= "<td>&nbsp;</td>";
            foreach ($itemLabels as $key => $label) {
                if (isset($total[$key])) {
                    $strContent .= "<td class='$key'>";
                    $strContent .= formatField($key, $total[$key]);
                    $strContent .= "</td>";
                }
                else {
                    $strContent .= "<td>&nbsp;</td>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</tfoot>";
            //endregion
            $strContent .= "</table>";

            $strPayment .= "<table class='table table-condensed no-padding'>";
            $strBankAcc = "";
            $defValue = isset($ses_outMaster['sisa']) ? $ses_outMaster['sisa'] : 0;
            $defPaymentValue = isset($ses_outMaster['nilai_bayar']) ? $ses_outMaster['nilai_bayar'] : 0;
            $creditAmount = isset($ses_outMaster['creditAmount']) ? $ses_outMaster['creditAmount'] : 0;
            $defaultDisabled = $defPaymentValue > 0 ? "" : "disabled";
            if ($kelebihanBayarMethod == true) {

                $paymentRows = array(
                    " " => "<label>
                            <input type=checkbox 
                            onclick=\"
                            if(this.checked==true){
                            setTimeout(function(){
                            document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/$jenisTr';
                            document.getElementById('btnSave').disabled=false;
                            },1200);}
                            \"> i confirm that the numbers above are correct</label>",


                    "" => "<input type=button class='btn btn-success btn-block' id='btnSave' value='$btnLabel' disabled 
                        onclick=\"
                                if(parseInt(removeCommas(document.getElementById('nilai_entry').value))<0)
                                {alert('please fill in amount value');} else {$actionTarget}\">",
                );

            }
            else {

                $paymentRows = array(
                    " " => "<label>
                            <input type=checkbox 
                            onclick=\"
                            if(this.checked==true){
                            setTimeout(function(){
                            document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/$jenisTr';
                            document.getElementById('btnSave').disabled=false;
                            },1200);}
                            //else{
                            //document.getElementById('btnSave').disabled=true;this.checked=false;
                            //if(document.getElementById('nilai_entry')){
                            //hiliteDiv('nilai_entry');document.getElementById('nilai_entry').focus();document.getElementById('nilai_entry').select();
                            //}
                            //}
                            \"> i confirm that the numbers above are correct</label>",


                    "" => "<input type=button class='btn btn-success btn-block' id='btnSave' value='$btnLabel' disabled 
                        onclick=\"
                                if(parseInt(removeCommas(document.getElementById('nilai_entry').value))>parseInt(removeCommas(document.getElementById('$tagihanSrc').value)) || parseInt(removeCommas(document.getElementById('nilai_entry').value))<0)
                                {alert('please fill in amount value');}else {$actionTarget}\">",
                );
            }


            foreach ($paymentRows as $key => $val) {
                $strPayment .= "<tr line=" . __LINE__ . ">";
                $strPayment .= "<td>$key</td>";
                $strPayment .= "<td>$val</td>";
                $strPayment .= "</tr>";
            }
            $strPayment .= "</table>";

            if (isset($isPaymentRadioSelect) && $isPaymentRadioSelect == true) {
                $strContent .= "<script>
                                    $(\".chRadio\").change(function(){
                                        $(\".chRadio\").prop('checked',false);
                                        $(this).prop('checked',true);
                                        console.log(this.checked);
                                    });
                               </script>";
            }


            $strContent .= "<script>

//            $(document).ready( function(){
//$('.table.cTable-modal').DataTable();
                    console.log('mode datatable activated');

                    var table = $('.table.cTable-modal').DataTable({
                                    stateSave: false,
                                    order: [[ 10, 'desc' ]],
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };



//                                        // Total over all pages
//                                        var total6=0;
//                                        jQuery.each( $(api.column(6).data()), function(i, obj){
//                                            total6 += intVal( $(obj).html() );
//                                        });
//
//                                        var total7=0;
//                                        jQuery.each( $(api.column(7).data()), function(i, obj){
//                                            total7 += intVal( $(obj).html() );
//                                        });
//
//                                        var total8=0;
//                                        jQuery.each( $(api.column(8).data()), function(i, obj){
//                                            total8 += intVal( $(obj).html() );
//                                        });
//
//                                        var total9=0;
//                                        jQuery.each( $(api.column(9).data()), function(i, obj){
//                                            total9 += intVal( $(obj).html() );
//                                        });
//
//                                        var total10=0;
//                                        jQuery.each( $(api.column(10).data()), function(i, obj){
//                                            total10 += intVal( $(obj).html() );
//                                        });

                                        // Total over this page
                                        var pageTotal6=0;
                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
                                            pageTotal6 += intVal( $(obj).html() );
                                            console.log( $(obj).html() );
                                        });

                                        var pageTotal7=0;
                                        jQuery.each( $(api.column(6, { page: 'current'}).data()), function(i, obj){
                                            pageTotal7 += intVal( $(obj).html() );
                                        });

                                        var pageTotal8=0;
                                        jQuery.each( $(api.column(7, { page: 'current'}).data()), function(i, obj){
                                            pageTotal8 += intVal( $(obj).html() );
                                        });

                                        var pageTotal9=0;
                                        jQuery.each( $(api.column(8, { page: 'current'}).data()), function(i, obj){
                                            pageTotal9 += intVal( $(obj).html() );
                                        });

                                        var pageTotal10=0;
                                        jQuery.each( $(api.column(9, { page: 'current'}).data()), function(i, obj){
                                            pageTotal10 += intVal( $(obj).html() );
                                        });

                                        // Update footer
                                        $( api.column( 5 ).footer() ).html(
                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal6)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total6)+\"</div>\"
                                        );

                                        $( api.column( 6 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal7)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total7)+\"</div>\"
                                        );

                                        $( api.column( 7 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal8)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total8)+\"</div>\"
                                        );

                                        $( api.column( 8 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal9)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total9)+\"</div>\"
                                        );

                                        $( api.column( 9 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal10)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total10)+\"</div>\"
                                        );
                                    }
                                });

                    table.on( 'draw', function () {
                        var body = $( table.table().body() );
                        body.unhighlight();
                        body.highlight( table.search() );
                        console.log('highlight');
                    } );

//                });
                    $('#shopping_cart').on('change', function(){
                        console.log('shopping_cart changed');
                    });
                    $('#result').on('change', function(){
                        console.log('result changed');
                    });
             </script>";
        }
        else {
            $strContent = "-the item you specified has no entry-<br>";
            $strContent = "you may want to go back to previous page";
        }
        //endregion

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "jenisTr" => $jenisTr,
                "payment_subtitle" => $paymentSubtitle,
                "profile_name" => $this->session->login['nama'],
                "content" => $strContent,
                "elements" => $strElements,
                "payment_str" => $strPayment,
                "scriptBottom" => $scriptBottom,
                //                "title" => $title,
                //                "sub_title" => $subTitle
            )
        );

        $p->render();


        break;

    case "selectPaymentExternSrc":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/default.html");
//arrPrint($dueDate);
        $strContent = "";
        if (sizeof($items) > 0) {
//            $strContent .= "<div id=\"external_filter_container_wrapper\">
//                                <label>External filter for \"Numbers\" column :</label>
//                                <div id=\"external_filter_container\"></div>
//                            </div>";
            $strContent .= "<table class='table cTable table-condensed table-striped table-bordered'>";
            $strContent .= "<thead>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($itemLabels) > 0) {
                $strContent .= "<td class='text-muted text-right'>";
                $strContent .= "No.";
                $strContent .= "</td>";
                foreach ($itemLabels as $key => $label) {
                    $strContent .= "<td class='text-capitalize text-muted'>";
                    $strContent .= $label;
                    $strContent .= "</td>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</thead>";
            $no = 0;
            foreach ($items as $key => $val) {
                $no++;
                $strContent .= "<tr line=" . __LINE__ . ">";
                $strContent .= "<td align='right' class='" . $val['class_marking'] . "'>$no</td>";
                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $label) {
//                        cekHere($key);
                        $classMarking = "";
                        $strContent .= "<td data-order='" . $val[$key] . "' class='" . $val['class_marking'] . "'>";
                        $strContent .= "<a href='javascript:void(0)' title='make a $title with " . $val['extern_nama'] . "' data-toggle='tooltip' data-placement='right' onclick=\"top.BootstrapDialog.show(
                                   {
                                       title:'$title - " . $val['extern_nama'] . "',
                                       message: " . '$' . "('<div></div>').load('" . $val['link'] . "'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                        \" >";
                        $strContent .= formatField($key, $val[$key]);
                        $strContent .= "</a>";
                        $strContent .= "</td>";
                        if (is_numeric($val[$key])) {
                            if (!isset($total[$key])) {
                                $total[$key] = 0;
                            }
                            $total[$key] += $val[$key];
                        }
                    }
                }
                $strContent .= "</tr>";
            }
            $strContent .= "<tfoot>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            $strContent .= "<td></td>";
            $strContent .= "<td class='text-muted'>total amount of '$srcLabel'</td>";
            foreach ($itemLabels as $key => $label) {
                if (isset($total[$key])) {
                    $strContent .= "<td class='text-muted'>";
                    $strContent .= formatField($key, $total[$key]);
                    $strContent .= "</td>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</tfoot>";
            $strContent .= "</table>";
            $strContent .= "
            <script>
                $(document).ready( function(){

                    console.log('mode datatable activated');

                    var table = $('.table.cTable').DataTable({
                                    stateSave: false,
                                    order: [[ 8, 'desc' ]],
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        // Total over all pages
                                        var total2=0;
                                        jQuery.each( $(api.column(2).data()), function(i, obj){
                                            total2 += intVal( $('span', obj).html() );
                                        });

                                        var total3=0;
                                        jQuery.each( $(api.column(3).data()), function(i, obj){
                                            total3 += intVal( $('span', obj).html() );
                                        });

                                        var total4=0;
                                        jQuery.each( $(api.column(4).data()), function(i, obj){
                                            total4 += intVal( $('span', obj).html() );
                                        });

                                        var total5=0;
                                        jQuery.each( $(api.column(5).data()), function(i, obj){
                                            total5 += intVal( $('span', obj).html() );
                                        });


                                        // Total over this page
                                        pageTotal2 = api
                                            .column( 2, { page: 'current'} )
                                            .data()
                                            .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                            }, 0 );


                                        var pageTotal2=0;
                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
                                            pageTotal2 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal3=0;
                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
                                            pageTotal3 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal4=0;
                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
                                            pageTotal4 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal5=0;
                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
                                            pageTotal5 += intVal( $('span', obj).html() );
                                        });


                                        // Update footer
                                        $( api.column( 2 ).footer() ).html(
                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
                                        );

                                        $( api.column( 3 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
                                        );

                                        $( api.column( 4 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
                                        );

                                        $( api.column( 5 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
                                        );

                                    }
                                });

                    table.on( 'draw', function () {
                        var body = $( table.table().body() );
                        body.unhighlight();
                        body.highlight( table.search() );
                        console.log('highlight');
                    } );

                });
            </script>";

        }
        else {
            $strContent = "-the item you specified has no entry-";
        }
        //endregion


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "profile_name" => $this->session->login['nama'],
                "content" => $strContent,
                "self" => isset($thisPage) ? $thisPage : "",
                "trName" => isset($trName) ? $trName : "",
                "jenisTr" => $jenisTr,
            )
        );

        $p->render();


        break;

    case "viewJembreng":

        $contentStr = "";
        $contentStr .= "<div class='table-responsive'>";
        $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";

        $contentStr .= "<tr bgcolor='#f5f5f5'>";
        $contentStr .= "<td></td>";
        foreach ($main as $stepNum => $iSpec) {
            $contentStr .= "<td>";
            $contentStr .= "Step: $stepNum<br>";
            $contentStr .= "Nomer: " . $iSpec['nomer'] . "<br>";
            $contentStr .= "By: " . $iSpec['nama'] . "<br>";
            $contentStr .= "</td>";
        }
        $contentStr .= "</tr>";

        foreach ($items[1] as $iSpecDetail) {
            $contentStr .= "<tr bgcolor='#f5f5f5'>";
            $contentStr .= "<td>" . $iSpecDetail['nama'] . "</td>";

            foreach ($main as $stepNum => $iSpec) {
                $cont = "";
                foreach ($items[$stepNum] as $iSpecDetail) {
                    $cont = "<td>" . $iSpecDetail['jml'] . "</td>";
                }
                $contentStr .= $cont;
            }

            $contentStr .= "</tr>";

        }


        $contentStr .= "</table>";
        $contentStr .= "</div>";


        $p = New Layout("$title", "", $template);
        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "title" => $title,
                "content" => $contentStr,
                "signatures" => "",
                "header" => "",
            )
        );

        $p->render();

        break;

    case "viewResume":

        echo $signStr;

        // view jurnal, kalau wewenang allowed dan jurnalnya ada
        if (sizeof($items) > 0) {
            foreach ($items as $cabangID => $subItems) {

                if (sizeof($subItems) > 0) {
                    $cabangNama = isset($cabangData[$cabangID]) ? $cabangData[$cabangID] : "";

                    echo "<h4 class='text-blue'><span class='fa fa-book'></span> journal entries ($cabangNama) " . formatField("nomer", $title) . "$urutCounter</h4>";

                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-condensed'>";


                    foreach ($subItems as $urut => $mSpec) {

                        echo "<tr bgcolor='#f0f0f0'>";
                        foreach ($headers as $key => $label) {
                            echo "<td>";
                            echo "$label";
                            echo "</td>";
                        }
                        echo "</tr>";

                        foreach ($mSpec as $iSpec) {

                            echo "<tr line=" . __LINE__ . ">";
                            foreach ($headers as $key => $label) {
                                echo "<td>";
                                echo "<a href='" . $iSpec['link'] . "' target='_blank'>";
                                echo formatField($key, $iSpec[$key]);
                                echo "</a>";
                                echo "</td>";
                                if (is_numeric($iSpec[$key])) {
                                    if (!isset($total[$cabangID][$urut][$key])) {
                                        $total[$cabangID][$urut][$key] = 0;
                                    }
                                    $total[$cabangID][$urut][$key] += $iSpec[$key];
                                }
                            }
                            echo "</tr>";
                        }

                        echo "<tr style='font-size: 15px;font-weight: bold;'>";
                        foreach ($headers as $key => $label) {
                            echo "<td>";
                            if (isset($total[$cabangID][$urut][$key])) {
                                echo formatField($key, $total[$cabangID][$urut][$key]);
                            }
                            echo "</td>";
                        }
                        echo "</tr>";

                    }


                    echo "</table class='table table-condensed'>";
                    echo "</div class='table-responsive'>";
                }
                else {
                    echo "<div class='text-center text-warning'>";
                    echo "- no journal affected by this transaction -<br><br>";
                    echo "</div class='text-center text-warning'>";
                }
            }
        }
        else {
            echo "<div class='text-center text-warning'>";
            echo "- no journal affected by this transaction -<br><br>";
            echo "</div class='text-center text-warning'>";
        }

        // tambahan detail isi nota top
        if (isset($detail_items) && sizeof($detail_items) > 0) {
            if (isset($itemLabels) && (sizeof($itemLabels) > 1)) {

                echo "<h4 class='text-blue'><span class='fa fa-book'>$detail_title</span></h4>";

                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($detail_items as $iSpec) {
                    $no++;
                    $fieldVal = "";


                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        echo "<td>";
                        if (substr($key, 0, 1) == "*") {
                            $key_p = str_replace("*", "", $key);
                            $key_ex = explode("#", $key_p);
                            $pair_name = $key_ex[0];
                            $pair_key = $key_ex[1];
                            $pair_key_val = $iSpec[$pair_key];
                            if (sizeof($key_ex) > 1) {
                                $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                            }
                            else {
                                $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                            }
                        }
                        else {
                            $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                        }

                        echo $fieldVal;
                        echo "</td>";
                    }
                    echo "</tr>";

                    if (($noteEnabled == true) || ((isset($imageEnabled)) && ($imageEnabled == true))) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>&nbsp;</td>";
                        echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            // echo "</td>";


                        }
                        if (isset($imageEnabled) && ($imageEnabled == true)) {
                            $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                            echo $iVal;
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($detail_sumRows) && sizeof($detail_sumRows) > 0) {
                    foreach ($detail_sumRows as $key => $label) {
//                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                        $colspanX = sizeof($itemLabels);
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($detail_main[$key]) && $detail_main[$key] > 0) {
                            $val = $detail_main[$key];
                        }
                        else {
                            $val = 0;
//                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
//                            $val = $mainAddValues[$key];
//                        }
//
                        }

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
//                arrPrint($mainAddValues);

                }
                if (isset($addRows) && sizeof($addRows) > 0) {
                    $colspanRows = sizeof($itemLabels);
                    $listAddRows = "";
                    foreach ($addRows as $key => $label) {
                        $listAddRows .= "<tr>";
                        $valAddRows = isset($detail_main[$key]) ? $detail_main[$key] : "0";
                        $listAddRows .= "<td colspan='$colspanRows' align='right'>$label</td>";
                        $listAddRows .= "<td colspan='$colspanRows'>" . formatField($key, $valAddRows) . "</td>";
                        $listAddRows .= "</tr>";
                    }

                    echo $listAddRows;
//                    arrPrint($addRows);
                }

//            if (isset($sumAddRows) && sizeof($sumAddRows) > 0) {
//                $valAdd = 0;
//                foreach ($sumAddRows as $keyAdd => $label) {
////                        cekLime($keyAdd);
//                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
//                    echo "<tr line=".__LINE__.">";
//                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
//                    echo "<td class='text-right'>";
//                    $val = 0;
//                    if (isset($main[$keyAdd]) && $main[$keyAdd] > 0) {
//                        $valAdd = isset($main[$keyAdd]) ? $main[$keyAdd] : 0;
//                    }
//                    else {
//                        if (isset($mainAddValues[$keyAdd]) && $mainAddValues[$keyAdd] > 0) {
//                            $valAdd = isset($mainAddValues[$keyAdd]) ? $mainAddValues[$keyAdd] : 0;
////                            cekKuning("$keyAdd, $valAdd");
//                        }
//                        else {
//                            $valAdd = 0;
////                            cekPink("$keyAdd, $valAdd");
//                        }
//                    }
//
//                    echo formatField($keyAdd, $valAdd);
//                    echo "</td>";
//                    echo "</tr>";
//                }
//            }


                echo "</table>";
                echo "</div class='table-responsive'>";


                // tambahan detail2 isi nota top
                if (isset($detail2_items) && sizeof($detail2_items) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($item2Labels as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($detail2_items as $iSpec) {
                        $no++;
                        $fieldVal = "";


                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($item2Labels as $key => $label) {
                            echo "<td>";
                            if (substr($key, 0, 1) == "*") {
                                $key_p = str_replace("*", "", $key);
                                $key_ex = explode("#", $key_p);
                                $pair_name = $key_ex[0];
                                $pair_key = $key_ex[1];
                                $pair_key_val = $iSpec[$pair_key];
                                if (sizeof($key_ex) > 1) {
                                    $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                                }
                                else {
                                    $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                                }
                            }
                            else {
                                $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                            }

                            echo $fieldVal;
                            echo "</td>";
                        }
                        echo "</tr>";
                    }


                    echo "</table>";
                    echo "</div class='table-responsive'>";
                }

                // tambahan detail3 isi nota top
                if (isset($detail3_items) && sizeof($detail3_items) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($item3Labels as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($detail3_items as $iSpec) {
                        $no++;
                        $fieldVal = "";


                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($item3Labels as $key => $label) {
                            echo "<td>";
                            if (substr($key, 0, 1) == "*") {
                                $key_p = str_replace("*", "", $key);
                                $key_ex = explode("#", $key_p);
                                $pair_name = $key_ex[0];
                                $pair_key = $key_ex[1];
                                $pair_key_val = $iSpec[$pair_key];
                                if (sizeof($key_ex) > 1) {
                                    $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                                }
                                else {
                                    $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                                }
                            }
                            else {
                                $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                            }

                            echo $fieldVal;
                            echo "</td>";
                        }
                        echo "</tr>";
                    }


                    echo "</table>";
                    echo "</div class='table-responsive'>";
                }

            }
        }
        if (isset($showScheme) && sizeof($showScheme) > 0) {

            echo "<div class='panel panel-success'>";
            echo "<div class='text-center text-danger text-bold'>-- SKEMA PINJAMAN ANDA --</div>";
            echo "<div class='text-center text-danger text-bold meta'>generator skema hanya berlaku untuk single kreditur</div>";
            echo "<div class='text-center text-danger text-bold'> ========================================== </div>";

            //header skema
            echo "<div class='col-md-12'>";

            echo "<span class='col-md-2 text-left text-bold no-padding'>Nama Pemegang Saham </span>
                <span class='text-left col-md-9 no-padding text-capitalize'>: " . $headerScheme['nama'] . "</span>";

            echo "<span class='col-md-2 text-left text-bold no-padding'>Jumlah Pinjaman </span>      <span class='text-left col-md-9 no-padding'>: " . number_format($headerScheme['jml_pinjaman']) . "</span>  <span class='text-left col-md-1 no-padding'></span>";
            echo "<span class='col-md-2 text-left text-bold no-padding'>Bunga Tahunan </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['bunga_tahunan'] . "%</span>               <span class='text-left col-md-1 no-padding'></span>";
            echo "<span class='col-md-2 text-left text-bold no-padding'>Awal Meminjam </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['awal_meminjam'] . "</span>                <span class='text-left col-md-1 no-padding'></span>";
            echo "<span class='col-md-2 text-left text-bold no-padding'>Pelunasan Pinjaman </span>   <span class='text-left col-md-9 no-padding'>: " . $headerScheme['pelunasan_pinjaman'] . "</span>           <span class='text-left col-md-1 no-padding'></span>";
            echo "<span class='col-md-2 text-left text-bold no-padding'>Lama Pinjaman </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['lama_pinjaman'] . "</span>                <span class='text-left col-md-1 no-padding'></span>";

            echo "</div>";
            echo "<div class='clearfix'>&nbsp;</div>";
            echo "<div class='container-fluid'><table id='main_table' class='table datatable table-bordered table-hover table-striped'><thead>";
            echo "<tr line=" . __LINE__ . ">  <th width='1%'>No</th>
                            <th>Periode</th>
                            <th>jml hari / periode</th>
                            <th>Pokok Pinjaman</th>
                            <th>Rate Bunga</th>
                            <th>Nilai Bunga</th>
                            <th>PPh23</th>
                            <th>bunga setelah dipotong PPh</th>
                      </tr>";

            echo "</thead><tbody>";

            $total_bunga = 0;
            $total_pph23 = 0;
            $total_bunga_pph23 = 0;
            $total_hari = 0;
            $no = 1;

            foreach ($showScheme as $thnbln => $pinjaman) {

                $setBackground = isset($pinjaman['silangan']) ? $pinjaman['silangan'] : "merah";
                $bgColor = " ";

                switch ($setBackground) {
                    default:
                    case "merah":
                        $bgColor = "bg-white";
                        break;
                    case "hijau":
                        $bgColor = "bg-success";
                        break;
                    case "berjalan":
                        $bgColor = "bg-warning";
                        break;
                }

                echo "  <tr line=" . __LINE__ . ">
                            <td class='$bgColor'>$no</td>
                            <td class='$bgColor'>" . date('F Y', strtotime($pinjaman['thnbln'] . '-01')) . "</td>
                            <td class='$bgColor'>" . $pinjaman['jml_hari_dbln'] . "</td>
                            <td class='$bgColor'>" . number_format($pinjaman['nilai_pinjaman'], 0) . "</td>
                            <td class='$bgColor'>" . $pinjaman['rate_bunga'] . "%</td>
                            <td class='$bgColor'>" . number_format($pinjaman['nilai_bunga'], 0) . "</td>
                            <td class='$bgColor'>" . number_format($pinjaman['nilai_pph23'], 0) . "</td>
                            <td class='$bgColor'>" . number_format($pinjaman['nett_bunga'], 0) . "</td>
                        </tr>";

                $no++;

                $total_bunga += $pinjaman['nilai_bunga'] * 1;
                $total_pph23 += $pinjaman['nilai_pph23'] * 1;
                $total_bunga_pph23 += $pinjaman['nett_bunga'] * 1;
                $total_hari += $pinjaman['jml_hari_dbln'] * 1;
            }

            echo "<tfoot>
                        <tr line=" . __LINE__ . ">
                            <td>-</td>
                            <td>-</td>
                            <td>" . $total_hari . "</td>
                            <td>-</td>
                            <td>-</td>
                            <td>" . number_format($total_bunga, 0) . "</td>
                            <td>" . number_format($total_pph23, 0) . "</td>
                            <td>" . number_format($total_bunga_pph23, 0) . "</td>
                        </tr>
                    </tfoot>";

            echo "</tbody>
                        </table>
                        </div>";
            echo "<div class='clearfix'>&nbsp;</div>";

            echo "<div class='container-fluid'>";
            echo "<div class='text-left'>Keterangan:</div>";
            echo "<div class='text-left'> - periode dengan background hijau akan otomatis dibuatkan <span class='text-capitalize text-bold'>request loan interest</span> sesaat setelah request pinjaman diapprove </div>";
            echo "</div>";

            echo "<div class='clearfix'>&nbsp;</div>";
            echo "</div>";

//            echo "<div class='clearfix'><hr></div>";

            echo "</div>";

        }

        if (sizeof($main) > 0) {
            $accuracy = isset($main['accuracy']) ? $main['accuracy'] : "";
            $lattitude = isset($main['lattitude']) ? $main['lattitude'] : "";
            $longitude = isset($main['longitude']) ? $main['longitude'] : "";
            $olehName = isset($main['olehName']) ? strtoupper($main['olehName']) : "";
            if (isset($main['accuracy']) && isset($main['lattitude']) && isset($main['longitude'])) {

                echo "<style>#map-canvas{width: 100%;height: 40vh;}</style>";
                echo "<div class='panel'>";
                echo "<h4 class='text-red'>";
                echo "<span class='fa fa-map'></span>&nbsp;location info <a href=\"javascript:void(0)\" class='pull-right' onclick=\"showMaps()\">(view map)</a> </h4>";
                echo "<div class='hidden' id=\"map-canvas\"></div>";
                echo "</div>";
                echo "
                    <script>

                    function showMaps(){

                        $.getScript( 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDSzzQo2ZxKysHg5bn6YeKyukaP_AyvdUM', function( data, textStatus, jqxhr ) {
                          console.log( data );
                          console.log( textStatus );
                          console.log( jqxhr.status );
                          console.log( 'Load was performed.' );
                            var markers = [
                                ['<i class=\"fa fa-user\"></i> $olehName', '$lattitude', '$longitude']
                            ];
                            var mapCanvas = document.getElementById('map-canvas');
                            var mapOptions = {
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                zoom: 20
                            }
                            var map = new google.maps.Map(mapCanvas, mapOptions)
                            var infowindow = new google.maps.InfoWindow(), marker, i;
                            var bounds = new google.maps.LatLngBounds();
                            for (i = 0; i < markers.length; i++) {
                                pos = new google.maps.LatLng(markers[i][1], markers[i][2]);
                                bounds.extend(pos);
                                marker = new google.maps.Marker({
                                    position: pos,
                                    map: map
                                });
                                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                    return function() {
                                        infowindow.setContent(markers[i][0]);
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));
                                map.fitBounds(bounds);
                            }

                            $(mapCanvas).toggleClass('hidden')

                        });
                    }



                    </script>
                    ";

            }
        }

        echo "<div class='row bg-gray'>";
        echo "<div class='col-md-6'>";
        echo "</div class='col-md-6'>";

        echo "<div class='col-md-6 text-right'>";
        echo "<div class='panel-body'>";
        echo "<a class='btn btn-primary' href='javascript:void(0)' onclick=\"$receiptLink\">";
        echo "<span class='glyphicon glyphicon-print'></span> ";
        echo "print receipt number $title$urutCounter";
        echo "</a>";
        echo "</div class='panel'>";
        echo "</div class='col-md-6'>";

        echo "</div class='row'>";

        echo "<div class='row visible-xs visible-xm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl'>";
        echo "<div class='col-md-12'>
              <div class='text-center panel panel-warning'>
                  printing on mobile devices requires <strong>Quick Printer</strong>&trade; <a href='javascript:void(0);' onclick=\"window.open('https://cdn.mayagrahakencana.com/apk/PRTS.apk')\">download from here</a>
                  <br/>
                  <span class='text-red'>(ignore this warning if you have already  installed the printer driver)</span>
              </div>
              </div class='col-md-12'>";
        echo "</div class='row'>";

        break;

    case "viewResumeDetails":

        $contents = "<div class='table-responsive' style='padding-left: 5px;border:0px solid red;'>";

        if (isset($itemsLabel) && sizeof($itemsLabel) > 0) {

            //region header data customer
            $contents .= "<table width='100%'>";
            if (sizeof($mainFieldsLabel) > 0) {
                foreach ($mainFieldsLabel as $col => $alias) {
                    $contents .= "<tr line=" . __LINE__ . ">";
//                $contents .= "<td class='text-uppercase text-grey-3'>$alias</td>";
                    $contents .= "<td class='text-uppercase'>$alias</td>";
                    $val = isset($mainFields[$col]) ? $mainFields[$col] : "";
                    $contents .= "<td>:</td>";
                    $contents .= "<td style='padding-left:10px;'>" . formatField($col, $val) . "</td>";
                    $contents .= "</tr>";
                }
            }
            $contents .= "</table>";
            //endregion

            $contents .= "<div class='panel margin-top-10'>";
            $contents .= "<table class='table table-bordered no-margin table-condensed'>";
            //region header items
            $contents .= "<tr line=" . __LINE__ . ">";
            $contents .= "<th class='bg-info text-center'>" . ucwords('No') . "</th>";
            foreach ($itemsLabel as $colItems => $col_alias) {
                $contents .= "<th class='bg-info text-center'>" . ucwords($col_alias) . "</th>";
            }
            $contents .= "</tr>";
            //endregion

            //region detile items
            if (isset($items) && sizeof($items) > 0) {
                $no = 0;
                foreach ($items as $itemsData) {

                    $no++;
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td class='text-right'>" . formatField('number', $no) . "</td>";
                    foreach ($itemsLabel as $itemCol => $label) {
                        $value = isset($itemsData[$itemCol]) ? $itemsData[$itemCol] : 0;
                        $contents .= "<td>";
                        if (isset($itemsKolomLink) && in_array($itemCol, $itemsKolomLink)) {
                            $link = isset($itemsLink[$itemsData['id']]) ? $itemsLink[$itemsData['id']] : "";
                            $contents .= "<a href='$link' target='_blank'>";
                            $contents .= formatField($itemCol, $value);
                            $contents .= "</a>";
                        }
                        else {
                            $contents .= formatField($itemCol, $value);
                        }


                        $contents .= "</td>";
                    }
                    $contents .= "</tr>";
                }
            }
            else {
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='" . sizeof($itemsLabel) . "' class='text-center text-bold'> details not found!</td>";
                $contents .= "</tr>";
            }
            //endregion

            $jmlKolom = sizeof($itemsLabel);

            //region detile item sum
            $colspanTotal = $jmlKolom > 4 ? $jmlKolom - 3 : 3;
//            arrPrint($itemsLabel);
//            cekHere($colspanTotal);
            if (isset($mainSumDetailsFieldsLabel) && sizeof($mainSumDetailsFieldsLabel) > 0) {

                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='$colspanTotal' class='text-right table-borderless'>Total item</td>";
                foreach ($mainSumDetailsFieldsLabel as $sumDCol => $sum_Dalias) {

                    $valSum = isset($mainFields[$sumDCol]) ? $mainFields[$sumDCol] : 0;
                    $contents .= "<td>" . formatField($sumDCol, $valSum) . "</td>";
                }
                $contents .= "</tr>";
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='" . ($jmlKolom + 1) . "'></td>";
                $contents .= "</tr>";
            }
            //endregion

            //region rincian sumifelds
            $colspan2 = sizeof($itemsLabel) - 2;
            foreach ($mainSumFieldsLabel as $kolSum => $alias) {

                $val = isset($mainFields[$kolSum]) ? $mainFields[$kolSum] : 0;
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='$colspan2' class='text text-right bottom-borderless text-uppercase text-grey-3'>$alias</td>";
                $contents .= "<td colspan='3'> " . formatField($kolSum, $val) . "</td>";
                $contents .= "</tr>";

            }
            //endregion

            //region sumfields2
            if (sizeof($reviewAddRows) > 0) {
                foreach ($reviewAddRows as $aKol => $aAlias) {
                    $val_row = isset($mainFields[$aKol]) ? $mainFields[$aKol] : 0;
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='$colspan2' class='text text-right bottom-borderless text-uppercase text-grey-3'>$aAlias</td>";
                    $contents .= "<td colspan='3'> " . formatField($aKol, $val_row) . "</td>";
                    $contents .= "</tr>";
                }
            }
            //endregion
            $contents .= "</table>";
            $contents .= "</div>";


            if (isset($itemsLabel2) && sizeof($itemsLabel2) > 0) {
                $contents .= "<table class='table table-bordered no-margin table-condensed'>";
                //region header2 items
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<th class='bg-info text-center'>" . ucwords('No') . "</th>";
                foreach ($itemsLabel2 as $colItems => $col_alias) {
                    $contents .= "<th class='bg-info text-center'>" . ucwords($col_alias) . "</th>";
                }
                $contents .= "</tr>";
                //endregion

                //region detile2 items
                if (isset($items2_sum) && sizeof($items2_sum) > 0) {
                    $no = 0;
                    foreach ($items2_sum as $itemsData) {

                        $no++;
                        $contents .= "<tr line=" . __LINE__ . ">";
                        $contents .= "<td class='text-right'>" . formatField('number', $no) . "</td>";
                        foreach ($itemsLabel2 as $itemCol => $label) {
                            $value = isset($itemsData[$itemCol]) ? $itemsData[$itemCol] : 0;
                            $contents .= "<td>";
                            $contents .= formatField($itemCol, $value);
                            $contents .= "</td>";
                        }
                        $contents .= "</tr>";
                    }
                }
                else {
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='" . sizeof($itemsLabel2) . "' class='text-center text-bold'> details not found!</td>";
                    $contents .= "</tr>";
                }
                //endregion

                $jmlKolom = sizeof($itemsLabel2);

                //region detile item sum
                $colspanTotal = $jmlKolom - 3;
                if (isset($mainSumDetailsFieldsLabel2) && sizeof($mainSumDetailsFieldsLabel2) > 0) {
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='$colspanTotal' class='text-right table-borderless'>Total item</td>";
                    foreach ($mainSumDetailsFieldsLabel2 as $sumDCol => $sum_Dalias) {

                        $valSum = isset($mainFields[$sumDCol]) ? $mainFields[$sumDCol] : 0;
                        $contents .= "<td>" . formatField($sumDCol, $valSum) . "</td>";
                    }
                    $contents .= "</tr>";
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='" . ($jmlKolom + 1) . "'></td>";
                    $contents .= "</tr>";
                }
                //endregion

                $contents .= "</table>";
            }

            $contents .= "</div>";

            $contents .= "<div class='row margin-bottom-10 margin-top-10' style='border-top:0px solid #ddd;padding-top: 10px;'>";


            //region signature
            $siseMd = sizeof($reviewSign) > 0 ? (int)(12 / sizeof($reviewSign)) : 12;
            if (isset($sign) && sizeof($sign) > 0) {
                foreach ($reviewSign as $availStep) {
                    $contensSign = $sign[$availStep];
                    $contents .= "<div class='col-md-$siseMd'>";
                    $contents .= "<div class='text-center text-capitalize'>" . $contensSign['label'] . "</div><br><br>";
                    $contents .= "<div class='text-center text-uppercase'>(" . $contensSign['contents'] . ")</div>";
                    $contents .= "</div>";
                }
            }
            //endregion

        }
        else {
            $contents .= $underMaintenance;
        }


        $contents .= "</div>";

        echo $contents;
        break;

    case "editMainFaktur":
        //         cekHere("followupPreview :: HAHAHA ::");
        // cekHere("detailSizeKey :: $detailSizeKey ::");
        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";


        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
//                cekHere($key);
                if (isset($mainValues[$key])) {
//                    cekHere("iki");
                    echo formatField($key, $mainValues[$key]);
                }
                else {
                    echo "";
                }

            }

            echo "</div class='col-md-6'>";
            echo "</div class='row'>";

            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
//        if (isset($items) && sizeof($items) > 0) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";


            $no = 0;
            if (isset($items) && sizeof($items) > 0) {
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";


                    foreach ($itemLabels as $key => $label) {

                        // cekHere($key . " " . $iSpec[$key]);
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );

                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }

                        switch ($detailSizeKey) {
                            default:
                            case "ckd":

                                foreach ($items as $pid => $item) {

                                    $replacers = array(
                                        "volume_new" => "volume_gross",
                                        "sub_volume_new" => "sub_volume_gross",
                                        "berat_new" => "berat_gross",
                                        "sub_berat_new" => "sub_berat_gross",
                                    );

                                    foreach ($replacers as $orig => $new) {
                                        if ($key == $orig) {
                                            $key = $new;
                                        }
                                    }
                                }

                                break;
                            case "cbu":
                                break;
                        }


                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        if ($key == "stok") {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                        else {
                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;
                        }
//                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
//                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "number";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                                }
                                else {
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
//                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        if ($allowRemove == false) {

                        }
                        else {
                            echo "<td>";
                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+this.value\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

                if (isset($items2) && sizeof($items2) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items2 as $iSpec2) {
                        $no++;
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels2 as $key2 => $label2) {
                            $replacers = array(
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "jml",
                            );
                            foreach ($replacers as $orig => $new) {
                                if ($key2 == $orig) {
                                    $key2 = $new;
//                                    cekHere(":: $key2 :: $new ::");
                                }
                            }

                            echo "<td>";
                            if (isset($iSpec2[$key2])) {
                                echo formatField($key2, $iSpec2[$key2]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
//                    if ($noteEnabled == true) {
//                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
//                            echo "<tr line=".__LINE__.">";
//                            echo "<td>&nbsp;</td>";
//                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
//                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
//                            echo $iVal;
//                            echo "</td>";
//
//                            echo "</tr>";
//                        }
//
//                    }
                    }

                }

                if (isset($items3) && sizeof($items3) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels3 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items3 as $iSpec) {
                        $no++;

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels3 as $key => $label) {
                            echo "<td>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                        }
                        echo "</tr>";
                        if ($noteEnabled == true) {
                            if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td>&nbsp;</td>";
                                echo "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                                echo $iVal;
                                echo "</td>";

                                echo "</tr>";
                            }

                        }
                    }
                    if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                        foreach ($sumRows3 as $key => $label) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right'>$label</td>";
                            echo "<td class='text-right'>";
                            if (isset($mainValues[$key])) {
                                echo formatField($key, $mainValues[$key]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        if (isset($mainValues[$key])) {

                            echo formatField($key, $mainValues[$key]);
                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                //region child data
                if (isset($items_child) && sizeof($items_child) > 0) {
                    echo "<div class='table-responsive'>";
//                    echo "<div class=''>Detail</div>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemsChildLabel as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items as $id => $itemSpec) {
                        foreach ($items_child[$id] as $x => $iSpec) {
                            $no++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($itemsChildLabel as $key => $label) {
//                                cekHere()test
                                if (isset($itemsChildLabelEditable[$key])) {
                                    $inputType = "text";
                                    $val = $iSpec[$key];
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemChildTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&x=$x&val='+this.value\" ";
                                    $strVal = "<input type=$inputType name='$id" . "_" . "$x' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                                    $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                                }
                                else {
                                    $strVal = $iSpec[$key];
                                }
                                echo "<td $tdOpt>";
                                echo $strVal;
                                echo "</td>";
                            }
                            echo "</tr>";

                        }
                    }


                }

                //endregion


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";
//                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
//                        echo $mainValues[$key];

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";
//                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }
//arrPrint($addMainSourceField);
                //region extended add main source
                $no = 0;
                if (isset($addMainSourceField) && sizeof($addMainSourceField) > 0) {
                    echo "<div class='table-responsive'>";
//                    echo "<div class=''>Detail</div>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($addMainSourceField as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td $tdOpt>";
                    echo "1";
                    echo "</td>";
                    foreach ($addMainSourceField as $kol => $alias) {
                        if (isset($addMainSourceEdit[$kol])) {
                            $inputType = $addMainSourceEdit[$kol];
                            $val = isset($mainValues[$kol]) ? $mainValues[$kol] : "";
                            $addEvent = " onblur=\"document.getElementById('result').src='$updateMainSourceTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$kol&&val='+this.value\" ";
                            $strVal = "<input type=$inputType name='$kol' class='form-control text-left' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            $strVal = formatField($kol, $mainValues[$kol]);
                        }
                        echo "<td $tdOpt>";
                        echo $strVal;
                        echo "</td>";

                    }
                    echo "</tr>";


                }


                //endregion

//	            if(isset($main['tagihan'])){
//		            echo "<tr line=".__LINE__.">";
//		            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
//		            echo "<td class='text-right'>";
//
//		            echo formatField("tagihan", $main['tagihan']);
//		            echo "</td>";
//		            echo "</tr>";
//	            }
            }


            echo "</table>";

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";
                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {

                    if (isset($mainElements['detilSize'])) {
                        if (in_array('detilSize', $editableElements)) {
                            $editLink = "BootstrapDialog.show(
                                       {
                                           title:'detilSize',
                                            message: $('<div></div>').load('" . $elementEditTarget . "detilSize" . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                            size:BootstrapDialog.SIZE_WIDE,
                                            draggable:false,
                                            closable:true,
                                            }
                                            );
                                           ";

                            echo "<div style='font-size: 14px;' class='text-center col-md-12'>";
                            echo "Anda Sedang Menggunakan Data Ukuran: <span class='text-uppercase text-bold'>$detailSizeKey</span> ";
                            echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                            echo "<span class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i> ganti</span>";
                            echo "</a>";
                            echo "</div>";
                        }
                    }

                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {

                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    foreach ($mainElements as $elName => $aSpec) {
//                        cekHere($elName);
                        if (array_key_exists($elName, $elementConfig)) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " </span>";
                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";
                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";
                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            echo "<tr line=" . __LINE__ . ">";
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (strlen($strLabel) > 0) {
                                                echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            }
                                            echo "<td align='left' class='text-black'>$val</td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                    }
                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                    echo "</div class='panel-default'>";
                }

                if (strlen($description) > 0) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').
src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+this.value;\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($description);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }

                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }

            echo "</div class='table-responsive'>";


            if (isset($items) && sizeof($items) > 0) {
                echo "<div>";

                // echo "<div class='col-md-2'>";
                echo "<button type='button' class='btn btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "</div class='col-md-2'>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                // echo "<div class='col-md-2'>&nbsp;";
                // echo "</div class='col-md-2'>";
                echo "<div class='btn-group pull-right'>";
                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    // echo "<div class='panel-body'>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    echo "<button type='button' disabled class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                    // echo "</div>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "<button button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<button button type='button' class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }


                if (isset($xShipmentBtn['targetUrl']) != "" && $xShipmentBtn['targetUrl'] != "") {
                    echo "<span class='btn btn-default ' style='border:1px #fff solid;color:#ff7700;' onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</span>";
                }

                echo "</div>";

                echo "</div>"; // 2669

                echo "<div class='row' style='margin-top: 60px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";
                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";
                echo "</div>";
                echo "</div class='row'>";
            }
            echo "</form>";
        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();
        }

        break;

    case "index_multi":
        $arrBlacklist = array(
            "no",
        );

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/transaksi_index2.html");


//arrPrint($arrayProgressLabels);
//        matiHEre();
        $strHistFooter = "";
        $strRecapFooter = "";
        $strRecap = "";
        $strHist = "";
        $addLinkStr = "";
        $onpropDisplayView = "";
        $propDisplay = "";
        $str_group = "";
        $altDisplay = "";
        $disabledLockerTransaksi = "";
        $strOnprog = "";
        $strOnprog2 = "";
        $strOnprog3 = "";

        if (sizeof($arrayOnProgress) > 0) {
            foreach ($arrayOnProgress as $item => $itemsDetails) {
                $qstr = "";
                if ($item == "itemsSrc") {
                    $selectedProcessor = $selectProcessor['itemsSrc'];
                    $strOnprog .= "<table id='arrayOnProgress' class='02 table stripe table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<thead>";
                    $strOnprog .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayProgressLabels['itemsSrc']) > 0) {
                        $strOnprog .= "<th class=''>select</th>";
                        foreach ($arrayProgressLabels['itemsSrc'] as $key => $label) {
                            $strOnprog .= "<th class=''>";
                            if (is_array($label)) {
                                $strOnprog .= isset($label['label']) ? $label['label'] : "-";
                            }
                            else {
                                $strOnprog .= $label;
                            }
                            $strOnprog .= "</th>";
                        }
                    }
                    $strOnprog .= "</tr>";
                    $strOnprog .= "</thead>";
                    $strOnprog .= "<tbody>";
                    foreach ($itemsDetails as $key => $val) {

                        $qstrLabels = array(
                            "transaksi_id" => "trID",
                            "nomer" => "nomer",
                            "extern_id" => "xID",
                            "tagihan" => "tagihan",
                            "terbayar" => "terbayar",
                            "sisa" => "sisa",
                            "diskon" => "diskon",
                            "extern_nama" => "xID",
                            "tagihan_valas" => "tagihan_valas",
                            "terbayar_valas" => "terbayar_valas",
                            "sisa_valas" => "sisa_valas",
                            "diskon_valas" => "diskon_valas",
                            "valas_id" => "valas_id",
                            "valas_nama" => "valas_nama",
                            "valas_nilai" => "valas_nilai",
                            "id_master" => "id_master",
                            "extern_label2" => "pihakMainName",
                            "extern_nilai2" => "extern_nilai2",
                            "extern_nilai3" => "extern_nilai3",
                            "extern_nilai4" => "extern_nilai4",
                            "pph_23" => "pph_23",
                            "ppn_sisa" => "ppn_payment",
                            "ppn" => "ppn",
                            "extern2_id" => "extern2_id",
                            "extern2_nama" => "extern2_nama",
                            "extern_date2" => "extern_date2",
                            "extern_jenis" => "extern_jenis",
                            "jenis_master" => "jenis_master",
//                        "id_master" => "id_master",
                        );
                        $qstr = "";
                        foreach ($qstrLabels as $key => $label) {
                            $qstr .= "&$key=" . $val[$key];
                        }
                        $checked = "";
                        $disabled = "";
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        $strOnprog .= "<td class='text-center'><input class='chRadio' type=checkbox $checked $disabled $disabledLockerTransaksi value='" . $val['transaksi_id'] . "' id='opt" . $val['transaksi_id'] . "' onclick=\"document.getElementById('result').src='" . base_url() . "$selectedProcessor/$jenisTr" . "?$qstr&state='+this.checked;\"></td>";
                        if (sizeof($arrayProgressLabels['itemsSrc']) > 0) {
                            foreach ($arrayProgressLabels['itemsSrc'] as $key => $label) {

                                $strOnprog .= "<td>";
                                $strOnprog .= formatField($key, $val[$key]);
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }
                    $strOnprog .= "</tbody>";

                    //region footer summary bawah
                    $strOnprog .= "<tfoot>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    $strOnprog .= "<td>&nbsp;</td>";
                    foreach ($arrayProgressLabels['itemsSrc'] as $key => $label) {
                        if (isset($sumFooter['itemsSrc'][$key])) {
                            $strOnprog .= "<td class='$key'>";
                            $strOnprog .= formatField($key, $sumFooter['itemsSrc'][$key]);
//                            $strOnprog .=$sumFooter['itemsSrc'][$key];
                            $strOnprog .= "</td>";
                        }
                        else {
                            $strOnprog .= "<td>&nbsp;</td>";
                        }
                    }
                    $strOnprog .= "</tr>";
                    $strOnprog .= "</tfoot>";
                    //endregion
                    $strOnprog .= "</table>";
                }
                else {
                    $selectedProcessor = $selectProcessor['items'];
                    $strOnprog2 .= "<table id='arrayOnProgreshs' class='02 table stripe table-condensed table-bordered no-padding'>";
                    $strOnprog2 .= "<thead>";
                    $strOnprog2 .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayProgressLabels['items']) > 0) {
                        $strOnprog2 .= "<th class=''>select</th>";
                        foreach ($arrayProgressLabels['items'] as $key => $label) {
                            $strOnprog2 .= "<th class=''>";
                            if (is_array($label)) {
                                $strOnprog2 .= isset($label['label']) ? $label['label'] : "-";
                            }
                            else {
                                $strOnprog2 .= $label;
                            }
                            $strOnprog2 .= "</th>";
                        }
                    }
                    $strOnprog2 .= "</tr>";
                    $strOnprog2 .= "</thead>";
                    $strOnprog2 .= "<tbody>";
                    $no = 0;
                    foreach ($itemsDetails as $key => $val) {
                        $no++;
                        $qstrLabels = array(
                            "transaksi_id" => "trID",
                            "nomer" => "nomer",
                            "extern_id" => "xID",
                            "tagihan" => "tagihan",
                            "terbayar" => "terbayar",
                            "sisa" => "sisa",
                            "diskon" => "diskon",
                            "extern_nama" => "xID",
                            "tagihan_valas" => "tagihan_valas",
                            "terbayar_valas" => "terbayar_valas",
                            "sisa_valas" => "sisa_valas",
                            "diskon_valas" => "diskon_valas",
                            "valas_id" => "valas_id",
                            "valas_nama" => "valas_nama",
                            "valas_nilai" => "valas_nilai",
                            "id_master" => "id_master",
                            "extern_label2" => "pihakMainName",
                            "extern_nilai2" => "extern_nilai2",
                            "extern_nilai3" => "extern_nilai3",
                            "extern_nilai4" => "extern_nilai4",
                            "pph_23" => "pph_23",
                            "ppn_sisa" => "ppn_payment",
                            "ppn" => "ppn",
                            "extern2_id" => "extern2_id",
                            "extern2_nama" => "extern2_nama",
                            "extern_date2" => "extern_date2",
                            "extern_jenis" => "extern_jenis",
                            "jenis_master" => "jenis_master",
//                        "id_master" => "id_master",
                        );
                        $qstr = "";
                        foreach ($qstrLabels as $key => $label) {
                            $qstr .= "&$key=" . $val[$key];
                        }
                        $checked = "";
                        $disabled = "";
                        $strOnprog2 .= "<tr line=" . __LINE__ . ">";
                        $strOnprog2 .= "<td class='text-center'><input class='chRadio' type=checkbox $checked $disabled $disabledLockerTransaksi value='" . $val['transaksi_id'] . "' id='opt" . $val['transaksi_id'] . "' onclick=\"document.getElementById('result').src='" . base_url() . "$selectedProcessor/$jenisTr" . "?$qstr&state='+this.checked;\"></td>";
                        if (sizeof($arrayProgressLabels['items']) > 0) {
                            foreach ($arrayProgressLabels['items'] as $key => $label) {
                                $strOnprog2 .= "<td>";
                                $strOnprog2 .= formatField($key, $val[$key]);
                                $strOnprog2 .= "</td>";
                            }
                        }
                        $strOnprog2 .= "</tr>";
                    }
                    $strOnprog2 .= "</tbody>";

                    //region footer summary bawah
                    $strOnprog2 .= "<tfoot>";
                    $strOnprog2 .= "<tr bgcolor='#f0f0f0'>";
                    $strOnprog2 .= "<td>&nbsp;</td>";
                    foreach ($arrayProgressLabels['items'] as $key => $label) {
                        if (isset($sumFooter['itemsSrc'][$key])) {
                            $strOnprog2 .= "<td class='$key'>";
                            $strOnprog2 .= formatField($key, $sumFooter['items'][$key]);
//                            $strOnprog .=$sumFooter['itemsSrc'][$key];
                            $strOnprog2 .= "</td>";
                        }
                        else {
                            $strOnprog2 .= "<td>&nbsp;</td>";
                        }
                    }
                    $strOnprog2 .= "</tr>";
                    $strOnprog2 .= "</tfoot>";
                    //endregion

                    $strOnprog2 .= "</table>";
                }


            }

//            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $strOnprog = "-the item you specified has no entry-";
            $strOnprogFooter = "";
        }


        $strOnprog3 .= "<div class='col-lg-1 no-padding'></div>";
        $strOnprog3 .= "<div class='col-lg-10 no-padding'>
                            <div class='panel'>
                                <div class='panel-header'>
                                    <span class='pull-left'></span>
                                </div>
                                <div class='box-body no-padding' id='shopping_cart'>
                                    <div class='panel-body'>
                                        <div class='text-danger'>- <strong>you have not chosen any item yet</strong> -<br>
                                        <small>you can do so by selecting items from available selectors</small><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
        $strOnprog3 .= "<div class='col-lg-1 no-padding'></div>";


        //region tombol
        $strOnprog3 .= "<table class='table table-condensed no-padding'>";
        $strBankAcc = "";
        $defValue = isset($ses_outMaster['sisa']) ? $ses_outMaster['sisa'] : 0;
        $defPaymentValue = isset($ses_outMaster['nilai_bayar']) ? $ses_outMaster['nilai_bayar'] : 0;
        $creditAmount = isset($ses_outMaster['creditAmount']) ? $ses_outMaster['creditAmount'] : 0;
        $defaultDisabled = $defPaymentValue > 0 ? "" : "disabled";

        $paymentRows = array(
            " " => "<label>
                            <input type=checkbox 
                            onclick=\"
                            if(this.checked==true){
                            setTimeout(function(){
                            document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/$jenisTr';
                            document.getElementById('btnSave').disabled=false;
                            },1200);}
                            //else{
                            //document.getElementById('btnSave').disabled=true;this.checked=false;
                            //if(document.getElementById('nilai_entry')){
                            //hiliteDiv('nilai_entry');document.getElementById('nilai_entry').focus();document.getElementById('nilai_entry').select();
                            //}
                            //}
                            \"> i confirm that the numbers above are correct</label>",


            "" => "<input type=button class='btn btn-success btn-block' id='btnSave' value='$btnLabel' disabled 
                        onclick=\"
                                if(parseInt(removeCommas(document.getElementById('nilai_entry').value))>parseInt(removeCommas(document.getElementById('nilai_sisa').value)) || parseInt(removeCommas(document.getElementById('nilai_entry').value))<0)
                                {alert('please fill in amount value');}else {$actionTarget}\">",
        );


        foreach ($paymentRows as $key => $val) {
            $strOnprog3 .= "<tr line=" . __LINE__ . ">";
            $strOnprog3 .= "<td>$key</td>";
            $strOnprog3 .= "<td>$val</td>";
            $strOnprog3 .= "</tr>";
        }
        $strOnprog3 .= "</table>";

        if (isset($isPaymentRadioSelect) && $isPaymentRadioSelect == true) {
            $strOnprog .= "<script>
                                    $(\".chRadio\").change(function(){
                                        $(\".chRadio\").prop('checked',false);
                                        $(this).prop('checked',true);
                                        console.log(this.checked);
                                    });
                               </script>";
        }

        arrPrint($btnLabel);
        //endregion

        $p->addTags(
            array(
                "error_msg" => $error,
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "alt_display" => $altDisplay,
                "prop_display" => $propDisplay,

                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),

                "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",

                "onprogress_title" => "",
                "onprogress_content" => "",
                "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => "",
                "onprop_display_view" => $onpropDisplayView,
                "item_src" => $strOnprog,
                "items" => $strOnprog2,
                "items_btn" => $strOnprog3,
                "add_link" => $addLinkStr,
                "history_title" => $historyTitle,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                "recap_title" => $recapTitle,
                "recap_content" => $strRecap,
                "recap_footer" => $strRecapFooter,
                "profile_name" => $this->session->login['nama'],
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
            )
        );

        $p->render();

        break;
}