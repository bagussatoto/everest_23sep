<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "index":
        $scriptBottom = "";
        $arrBlacklist = array(
            "no",
        );
        $stepper = isset($_GET['step']) ? $_GET['step'] : 1;
        if (isset($_GET['step'])) {
            //            arrPrint($steps[$_GET['step']]);
        }

        $time_line = "cek";
        if (isset($allSteps)) {
            $time_line = createStateHorizontal('-1', sizeof($allSteps), $jenisTr);
        }
        //-----------------------------------
        $keterangan_notif = notifTransaksi();
        //-----------------------------------
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

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/transaksi_index.html");

        $strOnprePre = "";
        $strOnprog = "";


        if (isset($arrayOnProgressToPay) && $arrayOnProgressToPay == true) {
            //            cekHere($arrayOnProgressToPay);
        }
        else {
            //region onprogress

            //            $strOnprog = "";
            //            $switchToHistory = count($steps) == $stepper ? "History" : "";
            //
            //            if (sizeof($steps) > 1) {
            //                $strOnprog .= "<ul class='nav nav-tabs'>";
            //                foreach ($steps as $tStep => $stepData) {
            //                    $isiBadge = isset($arrayOnprogressGroup[$tStep]) ? "<span class='badge bg-red'>" . sizeof($arrayOnprogressGroup[$tStep]) . "</span>" : "";
            //                    $actives = $tStep == $stepper ? "active" : "";
            //                    $trSelesai = count($steps) == $tStep ? "SELESAI<br>" : "";
            //
            //
            //                    $cssSelesai = count($steps) == $tStep ? "style='padding-top: 0;padding-bottom: 0;'" : "";
            //                    $strOnprog .= "<li class='$actives'>";
            //                    $strOnprog .= "<a $cssSelesai class='nav-link btn' onclick=\"location.href='" . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?step=$tStep';\">";
            //                    $strOnprog .= "<span class='text-uppercase text-bold'>$trSelesai" . $stepData['label'] . "</span>  $isiBadge </a>";
            //                    $strOnprog .= "</li>";
            //                }
            //                $strOnprog .= "</ul>";
            //
            //                $strOnprog .= "<div class='clearfix'>&nbsp;</div>";
            //            }
            //
            //
            //            if (isset($arrayOnprogressGroup[$stepper]) && sizeof($arrayOnprogressGroup[$stepper]) > 0) {
            //                $arrayOnProgress = $arrayOnprogressGroup[$stepper];
            //                $arrayOnprogressMarking = (isset($arrayOnprogressGroupPartialMark[$stepper]) && (sizeof($arrayOnprogressGroupPartialMark[$stepper]) > 0)) ? $arrayOnprogressGroupPartialMark[$stepper] : "";
            //            }
            //            else {
            //                $arrayOnProgress = array();
            //                $arrayOnprogressMarking = array();
            //            }
            //            if (sizeof($arrayOnProgress) > 0) {
            ////                $strOnprog .= "<div class='table-responsive'>";
            //                $strOnprog .= "<table id='arrayOnProgress$switchToHistory' class='table stripe compact nowarp order-column table-condensed table-bordered no-padding' style='border:solid red 0px;'>";
            //                $strOnprog .= "<thead>";
            //
            //                if (count($steps) == $stepper) {
            //
            //                }
            //                else {
            //                    $strOnprog .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
            //                    if (sizeof($arrayProgressLabels) > 0) {
            //                        $strOnprog .= "<th class=''>No.</th>";
            //                        foreach ($arrayProgressLabels as $key => $label) {
            //                            $strOnprog .= "<th class=''>";
            //                            if (is_array($label)) {
            //                                $strOnprog .= isset($label['label']) ? $label['label'] : "-";
            //                            }
            //                            else {
            //                                $strOnprog .= $label;
            //                            }
            //                            $strOnprog .= "</th>";
            //                        }
            //                    }
            //                    $strOnprog .= "</tr>";
            //                }
            //
            //                $strOnprog .= "</thead>";
            //                $strOnprog .= "<tbody>";
            //
            //                if (count($steps) == $stepper) {
            //
            //                }
            //                else {
            //                    $no = 0;
            //                    foreach ($arrayOnProgress as $key => $val) {
            //                        //----------------------
            //                        $background_color = isset($arrayOnprogressMarking[$key]['style']) ? $arrayOnprogressMarking[$key]['style'] : "";
            //
            //
            //                        $no++;
            //                        $strOnprog .= "<tr line=" . __LINE__ . " style='$background_color'>";
            //                        $strOnprog .= "<td>$no</td>";
            //                        if (sizeof($arrayProgressLabels) > 0) {
            //                            foreach ($arrayProgressLabels as $key => $label) {
            //                                $strOnprog .= "<td>";
            //                                $strOnprog .= $val[$key];
            //                                $strOnprog .= "</td>";
            //                            }
            //                        }
            //                        $strOnprog .= "</tr>";
            //                    }
            //                }
            //
            //
            //                $strOnprog .= "</tbody>";
            //
            //
            //                if (isset($sumFooter) && sizeof($sumFooter) > 0) {
            //                    $strOnprog .= "<tfoot>";
            //                    $strOnprog .= "<tr line=" . __LINE__ . ">";
            //
            //                    if (count($steps) == $stepper) {
            //
            //                    }
            //                    else {
            //                        if (sizeof($arrayProgressLabels) > 0) {
            //                            $strOnprog .= "<td>-</td>";
            //                            foreach ($arrayProgressLabels as $key => $label) {
            //                                $strOnprog .= "<td>";
            //                                if (isset($sumFooter) && isset($sumFooter[$key])) {
            //                                    $strOnprog .= $sumFooter[$key];
            //                                }
            //                                else {
            //                                    $strOnprog .= "-";
            //                                }
            //                                $strOnprog .= "</td>";
            //                            }
            //                        }
            //                    }
            //
            //                    $strOnprog .= "</tr>";
            //                    $strOnprog .= "</tfoot>";
            //                }
            //
            //                $strOnprog .= "</table>";
            //                $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
            //            }
            //            else {
            //                $stepName = isset($steps[$stepper]['label']) ? $steps[$stepper]['label'] : "";
            //                $strOnprog .= "- $stepName item you specified has no entry -";
            //                $strOnprogFooter = "";
            //            }
            //
            //endregion
        }


        //region onprogressView Doank
        $strOnprogView = "";
        //        if (is_array($arrayOnProgressView) && sizeof($arrayOnProgressView) > 0) {
        //            $strOnprogView .= "<table class='table table-condensed table-bordered no-padding'>";
        //            $strOnprogView .= "<tr bgcolor='#f0f0f0'>";
        //            $strOnprogView .= "<td class='text-muted'>No.</td>";
        //            if (sizeof($stepHistoryFields) > 0) {
        //                foreach ($stepHistoryFields as $key => $label) {
        //                    $strOnprogView .= "<td class='text-muted'>";
        //                    if (is_array($label)) {
        //                        $strOnprogView .= isset($label['label']) ? $label['label'] : "-";
        //                    }
        //                    else {
        //                        $strOnprogView .= $label;
        //                    }
        //                    $strOnprogView .= "</td>";
        //                }
        //            }
        //            $strOnprogView .= "</tr>";
        //            $noOPV = 0;
        //            foreach ($arrayOnProgressView as $key => $val) {
        //                $strOnprogView .= "<tr line=" . __LINE__ . ">";
        //                if (sizeof($stepHistoryFields) > 0) {
        //                    $noOPV++;
        //                    $strOnprogView .= "<td class='text-right'>$noOPV</td>";
        //                    foreach ($stepHistoryFields as $key => $label) {
        //                        $strOnprogView .= "<td>";
        //                        $strOnprogView .= isset($val[$key]) ? $val[$key] : "";
        //                        $strOnprogView .= "</td>";
        //                    }
        //                }
        //                $strOnprogView .= "</tr>";
        //            }
        //            $strOnprogView .= "</table>";
        ////            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        //
        //            $onpropDisplayView = "block";
        //        }
        //        else {
        //            $strOnprogView .= "-the item you specified has no entry-";
        //            $strOnprogFooter = "";
        //            $onpropDisplayView = "none";
        //        }

        //endregion


        $strHist = "";
        //        if ((isset($arrayHistoryLabelsDt)) && (sizeof($arrayHistoryLabelsDt) > 0)) {
        //            $labelColumnDt = "\n";
        //            $sortColumnDef = "";
        //            foreach ($arrayHistoryLabelsDt as $key => $label) {
        //                $sortable = in_array($key, $availDbTable) ? "true" : "false";
        //                $sortColumnDef .= $key == reset($availDbTable) ? "order: [[ " . key($availDbTable) . ", 'asc' ]]," : "";
        //                if (is_array($label)) {
        //                    $labelx = isset($label['label']) ? strtoupper($label['label']) : "-";
        //                    $labelColumnDt .= "{ field: '$key', data: '$key', title: '$labelx', sortable: $sortable },\n";
        //                }
        //                else {
        //                    $labelColumnDt .= "{ field: '$key', data: '$key', title: '" . strtoupper($label) . "', sortable: $sortable },\n";
        //                }
        //            }
        //        }

        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table id='arrayHistory' class='table dataTable compact display table-bordered table-condensed no-padding'>";

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

        //        if (sizeof($arrayRecap) > 0) {
        //            $strRecap .= "<div class='table-responsive'>";
        //            $strRecap .= "<table id='arrayRecap' class='table table-condensed table-bordered no-padding'>";
        //
        //            $strRecap .= "<thead>";
        //            $strRecap .= "<tr line=" . __LINE__ . ">";
        //            if (sizeof($arrayRecapLabels) > 0) {
        //                foreach ($arrayRecapLabels as $key => $label) {
        //                    $strRecap .= "<td class='text-muted'>";
        //                    $strRecap .= $label;
        //                    $strRecap .= "</td>";
        //                }
        //            }
        //            $strRecap .= "</tr>";
        //            $strRecap .= "</thead>";
        //            $strRecap .= "<tbody>";
        //            foreach ($arrayRecap as $key => $val) {
        //                $strRecap .= "<tr line=" . __LINE__ . ">";
        //                if (sizeof($arrayRecapLabels) > 0) {
        //                    foreach ($arrayRecapLabels as $key => $label) {
        //                        $strRecap .= "<td>";
        //                        $strRecap .= $val[$key];
        //                        $strRecap .= "</td>";
        //                    }
        //                }
        //                $strRecap .= "</tr>";
        //            }
        //            $strRecap .= "</tbody>";
        //
        //            $strRecap .= "</table>";
        //            $strRecap .= "</div>";
        //
        //            $strRecapFooter = "<a class='btn btn-default' href='" . base_url() . "ActivityReport/viewMonthly/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        //        }
        //        else {
        //            $strRecap = "-the item you specified has no entry-";
        //            $strRecapFooter = "";
        //        }

        //endregion

        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            $scriptBottom .= "

        <script>
    $(document).ready( function () {
        if( $('table#arrayOnProgressHistory') ){

            var startDate = localStorage.getItem('startDate');
            var endDate = localStorage.getItem('endDate');

            var getStartDate = function (){
                return localStorage.getItem('startDate')
            }
            var getEndDate = function (){
                return localStorage.getItem('endDate');
            }
            $.fn.dataTableExt.afnFiltering.push(
                function (oSettings, aData, iDataIndex) {
                if (typeof startdate === 'undefined' || typeof enddate === 'undefined') {
                    return true;
                }
                var coldate = moment(aData[3], 'DD-MM-YYYY');
                console.log('coldate', coldate)
                var valid = true;
                if (coldate.isValid()) {
                    if (enddate.isBefore(coldate)) {
                        console.log('enddate before coldate', enddate)
                        valid = false;
                    }
                    if (coldate.isBefore(startdate)) {
                        console.log('coldate before startdate', startdate)
                        valid = false;
                    }
                }
                else {
                    valid = false;
                }
                return valid;
            });
            arrayOnProgressHistory = $('table#arrayOnProgressHistory').DataTable( {
                $sortColumnDef
                dom: ' l <\"dataTables_filter_custom\"> f r t i p ',
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'GET',
                    url: '" . base_url() . $this->uri->segment(1) . "/viewHistoryApi/$jenisTr/" . $steps[$stepper]['target'] . "?xxx=1&debuger=1',
                    datatype: 'json',
                    data: function (data) {
                        if(undefined!=$('.custom_daterange').val() && ''!=$('.custom_daterange').val()){
                            console.log('range date ada isinya');
                            console.log( $('.custom_daterange').val() );
                            var startDate = $('.custom_daterange').val().split(' to ')[0];
                            var endDate = $('.custom_daterange').val().split(' to ')[1];
                            data.startDate = startDate;
                            data.endDate = endDate;
                            $('#close_btn').removeClass('hidden');
                        }else{
                            console.log('date range kosong, data diabaikan');
                            $('#close_btn').addClass('hidden');
                        }
                    }
                },
                columns: [$labelColumnDt],
                buttons: [
                    {
                        text: 'Reload',
                        action: function ( e, dt, node, config ) {
                            dt.ajax.reload();
                        }
                    }
                ]
            } );

            $('.dataTables_filter_custom').html(\"<div class='input-group'><input type='text' placeholder='Filter Range Tanggal' class='form-control custom_daterange' style='margin-left: 25px;'><span id='close_btn' class='input-group-btn hidden'><button class='btn btn-danger' type='button' style='margin-left: 30px;'><i class='glyphicon glyphicon-remove'></i></button></span></div>\").css('margin-left','25');

            if( $('#arrayOnProgressHistory').length ) { new $.fn.dataTable.FixedHeader( arrayOnProgressHistory ); }

            top.$('.dataTables_scrollBody').floatingScroll();

            $( \".dataTables_scrollBody\" ).scroll(function() {
                setTimeout( function(){
                    $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
                }, 400);
            });

            $('.custom_daterange').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 last days': [moment().subtract(6, 'days'), moment()],
                    '30 last days': [moment().subtract(29, 'days'), moment()],
                    'This month': [moment().startOf('month'), moment().endOf('month')],
                    'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                autoUpdateInput: false,
                opens:'left',
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD-MMM-YYYY'
                }
            });

           $('.custom_daterange', this).on('apply.daterangepicker', function (ev, picker) {
               ev.preventDefault();
               $(this).val(picker.startDate.format('DD-MMM-YYYY') + ' to ' + picker.endDate.format('DD-MMM-YYYY'));
               arrayOnProgressHistory.draw();
           });

           $('.custom_daterange', this).on('cancel.daterangepicker', function (ev, picker) {
               ev.preventDefault();
               $(this).val('');
               arrayOnProgressHistory.draw();
           });

            $('#close_btn').on('click', function () {
                $('#close_btn').addClass('hidden');
                $('.custom_daterange').val('');
                arrayOnProgressHistory.draw();
            });

        }
    });
</script>\n

<style>
    .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 30%;
        left: 50%;
        width: 30%;
        height: 60px;
        margin-left: -20%;
        margin-top: -50px;
        padding-top: 10px;
        text-align: center;
        font-size: 2em;
        background: lightgreen;
    }
</style>
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

        }


        $scriptBottom .= isset($scriptBotto) ? $scriptBotto : "";


        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(
            array(
                "error_msg"    => $error,
                "jenisTr"      => $jenisTr . $str_group,
                "trName"       => $trName,
                "alt_display"  => isset($altDisplay) ? $altDisplay : "",
                "prop_display" => isset($propDisplay) ? $propDisplay : "",

                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),

                "prePre_title"   => isset($prePreTitle) ? $prePreTitle : "",
                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                "prePre_footer"  => isset($strOnprePreFooter) ? $strOnprePreFooter : "",

                "onprogress_title"   => isset($onprogressTitle) ? $onprogressTitle : "",
                "onprogress_content" => isset($strOnprog) ? $strOnprog : "",
                "onprogress_footer"  => isset($strOnprogFooter) ? $strOnprogFooter : "",

                "onprogressView_title"    => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content"  => isset($strOnprogView) ? $strOnprogView : "",
                "onprop_display_view"     => isset($onpropDisplayView) ? $onpropDisplayView : "",

                "onprop_payment_view" => isset($arrayOnProgressPaymentView) ? $arrayOnProgressPaymentView : "none",

                "add_link"         => isset($addLinkStr) ? $addLinkStr : "",
                "history_title"    => isset($historyTitle) ? $historyTitle : "",
                "history_content"  => isset($strHist) ? $strHist : "",
                "history_footer"   => isset($strHistFooter) ? $strHistFooter : "",
                "recap_title"      => isset($recapTitle) ? $recapTitle : "",
                "recap_content"    => isset($strRecap) ? $strRecap : "",
                "recap_footer"     => isset($strRecapFooter) ? $strRecapFooter : "",
                "profile_name"     => isset($this->session->login['nama']) ? $this->session->login['nama'] : "",
                "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom"     => isset($scriptBottom) ? $scriptBottom : "",
                "index_active"     => "class='active'",
                "time_line"        => $time_line,
                "keterangan_notif" => isset($keterangan_notif) ? $keterangan_notif : "",

            )
        );

        $p->render();

        break;

    case "master_project_":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/data_index.html");
        $scriptBottom = "";
        $arrBlacklist = array(
            "no",
        );
        $stepper = isset($_GET['step']) ? $_GET['step'] : 1;
        if (isset($_GET['step'])) {
            //            arrPrint($steps[$_GET['step']]);
        }

        $time_line = "cek";
        if (isset($allSteps)) {
            $time_line = createStateHorizontal('-1', sizeof($allSteps), $jenisTr);
        }
        //-----------------------------------
        $keterangan_notif = notifTransaksi();
        //-----------------------------------
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

        //mulai tampilkan data
        $data = "";
        if (count($komposisi) > 0) {
            $data .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            $data .= "<tr>";
            $data .= "<th>No</th>";
            foreach ($komposisiLabel as $key => $label) {
                $data .= "<th>$label</th>";
            }
            $data .= "</tr>";
            $i = 0;
            foreach ($komposisi as $komposisi_0) {
                $i++;
                $data .= "<tr>";
                $data .= "<td>$i</td>";
                foreach ($komposisiLabel as $key => $label) {
                    $data .= "<td>" . formatField("$key", $komposisi_0[$key]) . "</td>";
                }
                $data .= "</tr>";
            }
            $data .= "</table>";
        }
        //tambah ui untuk add new komposisi

        if (count)

            echo $data;
        // matiHere();
        $p->addTags(
            array(
                "error_msg" => $error,
                // "jenisTr" => $jenisTr . $str_group,
                // "trName" => $trName,
                // "alt_display" => isset($altDisplay) ? $altDisplay : "",
                // "prop_display" => isset($propDisplay) ? $propDisplay : "",

                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                //
                // "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
                // "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                // "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",
                //
                // "onprogress_title" => isset($onprogressTitle) ? $onprogressTitle : "",
                // "onprogress_content" => isset($strOnprog) ? $strOnprog : "",
                // "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",
                //
                // "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                // "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                // "onprogressView_content" => isset($strOnprogView) ? $strOnprogView : "",
                // "onprop_display_view" => isset($onpropDisplayView) ? $onpropDisplayView : "",
                //
                // "onprop_payment_view" => isset($arrayOnProgressPaymentView) ? $arrayOnProgressPaymentView : "none",
                //
                // "add_link" => isset($addLinkStr) ? $addLinkStr : "",
                // "history_title" => isset($historyTitle) ? $historyTitle : "",
                // "history_content" => isset($strHist) ? $strHist : "",
                // "history_footer" => isset($strHistFooter) ? $strHistFooter : "",
                // "recap_title" => isset($recapTitle) ? $recapTitle : "",
                // "recap_content" => isset($strRecap) ? $strRecap : "",
                // "recap_footer" => isset($strRecapFooter) ? $strRecapFooter : "",
                // "profile_name" => isset($this->session->login['nama']) ? $this->session->login['nama'] : "",
                // "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                // "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                // "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
                // "index_active" => "class='active'",
                // "time_line" => $time_line,
                // "keterangan_notif" => isset($keterangan_notif) ? $keterangan_notif : "",

            )
        );

        $p->render();
        break;
    case "master_project":
        //untuk default edit mode
        // arrPrint($currentTargetWip);
        // $p = New Layout("", "", MODUL_TEMPLATE_PATH."template/satuan.html");

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/data_index.html");
        $targetResult = isset($result) ? "&result=$result" : "&result=result";

        $strMain = "";
        //region BOM
        if (sizeof($produk_fase) > 0) {
            $bomTitle = "<div>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h3 id='untuk_reload_iframe'><u>Volume Kerja <b>( $produkNama )</b></u></h3>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }
        else {
            $bomTitle = "<div class='blink'>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h2 class='text-red text-bold'><u>Silahkan Buat Volume Kerja <b>( $produkNama )</b></u></h2>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }

        //region master timwork
        $timWork = "";
        $timWork .= "<div class='box-header'>";
        $timWork .= "<h3 id=''><b><u>Anggota</u></b></h3>";
        $timWork .= "</div>";
        $timWork .= "<div class='box-body no-padding'>";
        $timWork .= "<form class='form' name='timwork' id='timwork' method='post' target='result' action='$addFaseProdukKomposisiTimLink?mode=timwork$targetResult'>";
        $timWork .= "<table class='table dataTable compact display table-bordered table-condensed'>";
        $timWork .= "<thead>";
        $timWork .= "<tr>";
        $timWork .= "<td>No</td>";
        foreach ($masterTimworkLabel as $hField => $hLabel) {
            $timWork .= "<td>$hLabel</td>";
        }
        $timWork .= "<td>Action</td>";
        $timWork .= "</tr>";

        $timWork .= "</thead>";
        $timWork .= "<tbody>";
        //bagaian data relasi komposisi

        // arrprint($masterTimwork);
        $i = 0;
        if (count($masterTimwork) > 0) {
            foreach ($masterTimwork as $tID =>$masterTimwork) {
                //                                        arrPrint($DataRelsuppliesBiaya);
                $labelName = $masterTimwork["employee_nama"];
                $modalLink = $previewLink."MdlTimWorkProject/";
                $timWork .= "<tr>";
                $i++;
                $timWork .= "<td>$i</td>";
                foreach ($masterTimworkLabel as $hField => $hLabel) {
                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                    $val = isset($masterTimwork[$transformKey]) ? $masterTimwork[$transformKey] : "";
                    $timWork .= "<td>" . formatField($hField, $val) ."</td>";
                }
                //tambah logic crud
                $btn = "<div>";
                if(isset($allowedAccess["employee_id"]["update"]) && $allowedAccess["employee_id"]["update"] ==true){
                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlTimWorkProject/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                }
                if(isset($allowedAccess["employee_id"]["delete"]) && $allowedAccess["employee_id"]["delete"] ==true){
                    $btn .= "<button type='button' title='hapus' class='btn btn-xs btn-flat btn-danger'><span class='fa fa-fw fa-trash'></span></button>";
                }
                $btn .= "</div>";
                $timWork .= "<td class='text-center'>$btn</td>";
                $timWork .= "</tr>";
            }
        }

        //untuk tambah komponen
        //akses list untuk nambaha data
        // $allowedAdd = false;

        $timWork .= "<tr>";
        $timWork .= "<td></td>";
        // $allowedAccess["employee_id"]["create"]=false;
        if(isset($allowedAccess["employee_id"]["create"]) && $allowedAccess["employee_id"]["create"] ==true){
            foreach ($masterTimworkLabel as $hField => $hLabel) {

                if (isset($produk_fase_komposisiEditable[$hField])) {
                    if (isset($relEmployee[$hField])) {
                        // cekMerah(__LINE__);
                        $strTim = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                        // $strTim .="<select>";
                        $strTim .= "<option>==PILIH==</option>";
                        $ic = 0;
                        foreach ($relEmployee[$hField] as $datas) {
                            $ic++;
                            // arrPrint($datas);
                            $selected = isset($newData["timwork"][$produkID][$hField]) && $newData["timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                            $strTim .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                            // $strTim .= "<option >$ic</option>";
                        }
                        $strTim .= "</select>";

                    }
                    else {
                        $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                        $strTim = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                    }
                }
                else {
                    $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                    $strTim = formatField($hField, $value);
                    // $strItem ="";
                }
                $timWork .= "<td>";
                $timWork .= $strTim;
                $timWork .= "</td>";
            }
            //logic button sini allowed add
            $btnRemoveTimwork = "<button onclick=\"document.getElementById('timwork').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
            $timWork .= "<td class='text-center'>$btnRemoveTimwork**</td>";
            $timWork .= "</tr>";
        }

        $timWork .= "</tbody>";
        $timWork .= "</table>";
        $timWork .= "</form>";
//        $timWork .= "</div>";
        $timWork .= "</div>";

        $timWork .= "<div id='input_temp_0'></div>";
        //endregion

        //region rencana proses produksi (nama_produk)
        $produkFase = "";
        $produkFase .= "<div class='overflow-h'>";
        $produkFase .= "<form class='form' name='produk_fase' id='produk_fase' method='post' target='result' action='$addFaseProdukLink?mode=produk_fase$targetResult'>";
        $produkFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
        $produkFase .= "<thead>";
        $produkFase .= "<tr>";
        $produkFase .= "<th>No**</th>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $produkFase .= "<th>$produkfase_alias</th>";
        }
        $produkFase .= "<th>action</th>";
        $produkFase .= "</tr>";
        $produkFase .= "</thead>";
        $produkFase .= "<tbody>";
        // arrPrintWebs($produk_fase);
        if (isset($produk_fase) && sizeof($produk_fase)) {
            $i = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                $i++;
                $produkFase .= "<tr>";
                $produkFase .= "<td>$i</td>";
                foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                    $newKey = isset($relSuppliesHeader[$produkfaseKey]) ? $relSuppliesHeader[$produkfaseKey] : $produkfaseKey;
                    if (isset($faseData[$newKey])) {
                        $fieldValue = $faseData[$newKey];
                    }
                    else {
                        $fieldValue = "";
                    }
                    $produkFase .= "<td>" . $fieldValue . "</td>";
                }
                //region button remove
                // $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $btn ="<div>";
                if(isset($allowedAccess["fase_id"]["update"]) && $allowedAccess["fase_id"]["update"] == true){
                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectWorkOrder/$fase_urut/$hField','edit $labelName')\"><i class='fa fa-fw fa-edit'></i></button>";
                }
                if(isset($allowedAccess["fase_id"]["delete"]) && $allowedAccess["fase_id"]["delete"] == true){
                    $btn .= "<button type='button' title='hapus' class='btn btn-xs btn-flat btn-danger'><i class='fa fa-fw fa-trash'></i></button>";
                }
                $btn .="</div>";
                $produkFase .= "<td class='text-center'>$btn</td>";
                //endregion
                $produkFase .= "</tr>";
            }
            //tambahan tr untuk add data baru
        }
        $produkFase .= "<tr>";
        $produkFase .= "<td></td>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            if(isset())
            $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
            $readOnly = "";
            $produkFase .= "<td><input $readOnly class='form-control form-control-sm' type='text' value='$preval' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\">**</td>";
        }
        $produkFase .= "<td> <span class='btn btn-sm btn-success' onclick=\"document.getElementById('produk_fase').submit();\"><i class='fa fa-plus'></i> tambah***</span> </td>";
        $produkFase .= "</tr>";
        $produkFase .= "</tbody>";
        $produkFase .= "</table>";
        $produkFase .= "</form>";
        $produkFase .= "</div>";
        //endregion rencana proses produksi (nama_produk)


        //region komposisi produk fase
        if (sizeof($produk_fase) > 0) {
            $produkKomposisiFase = "<div class='box box-success'>";
            $produkKomposisiFase .= "<div class='box-header'>";
            $produkKomposisiFase .= "<h3 id=''><b><u>SETTING MATERIAL</u></b></h3>";
            $produkKomposisiFase .= "</div>";

            $produkKomposisiFase .= "<div class='nav-tabs-custom'>";
            $produkKomposisiFase .= "<div class='tab-content no-padding'>";

            $produkKomposisiFase .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";

            //arrPrintHijau($produk_fase);
            //arrPrintWebs($produk_komposisi_fase);

            $faseNoA = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                }
                else {
                    $faseNoA++;
                }
            }
            $faseNo = 0;
            $faseNoErr = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNo == 0 && $faseNoA == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 12px;' class=''> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNo++;
                }
                else {
                    $actLink = $faseNoErr == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 14px;' class='text-red'><i class='fa fa-warning blink text-yellow'></i> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNoErr++;
                }
            }
            // arrPrint($produk_komposisi_fase);
            $produkKomposisiFase .= "</ul>";
            $faseNoB = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNoB == 0 && $faseNoA == 0 ? "active in" : "";
                    $produkKomposisiFase .= "<div class='uu lv1 tab-pane fade $actLink' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class=''>  <h4 class=''><i class='fa fa-hand-o-right'></i> &nbsp; &nbsp;" . ($faseData['nama']) . "&nbsp;&nbsp;&nbsp;<small><i class='fa fa-clock-o text-muted'></i>&nbsp;" . date("Y-m-d H:i") . "</small></h4></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        //MdlProjectKomposisiWorkorder
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $produkKomposisiFase .= "<tr>";
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["produk"]) && sizeof($produk_komposisi_fase[$fase_urut]["produk"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $DataRelsupplies) {
                                        // arrPrint($DataRelsupplies);
                                        $tID=$DataRelsupplies["id"];
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $btn = "<div>";
                                       if(isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["fase_id"]["update"] == true) {
                                           $btn .= "<button type='button' title='hapus' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                                       }
                                        if(isset($allowedAccess["fase_id"]["delete"]) && $allowedAccess["fase_id"]["delete"] == true) {
                                            $btn .= "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger'><i class='fa fa-fw fa-trash'></i></button>";
                                        }
                                        $btn .="</div>";

                                        $produkKomposisiFase .= "<td >$btn</td>";
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";


                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["biaya"]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $DataRelsuppliesBiaya) {
                                        //                                        arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi biaya' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "timwork":
                                $idForm = "timwork" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<div class='panel'>";
                                $produkKomposisiFase .= "<div class='panel-header'><h4>Tim Kerja</h4></div>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiTimLink?mode=komposisi_fase_timwork&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["timwork"]) && sizeof($produk_komposisi_fase[$fase_urut]["timwork"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["timwork"] as $DataRelsuppliesBiaya) {
                                        //                                        arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relEmployee[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            foreach ($relEmployee[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) && $newData["komposisi_fase_timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i>  tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";//clas panel
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                // $idForm = "target" . $fase_urut;
                                // $produkKomposisiFase .= "<div class=''>";
                                // $produkKomposisiFase .= "<table class='table table-bordered'>";
                                // $produkKomposisiFase .= "<thead>";
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>$hLabel</td>";
                                // }
                                //
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</thead>";
                                // $produkKomposisiFase .= "<tbody>";
                                //
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     if (isset($produk_fase_komposisiEditable[$hField])) {
                                //         if (isset($relTarget[$hField])) {
                                //             $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$addFaseHasilProduksi" . "/$produkID?mode=komposisi_target&key=$hField&&fase_id=$fase_urut&value='+encodeURI(this.value)+'$targetResult'); \">";
                                //             $strItem .= "<option> ---silahkan pilih--</option>";
                                //             foreach ($relTarget[$hField] as $datas) {
                                //                 $selected = isset($currentTargetWip[$produkID][$fase_urut][$hField]) && $currentTargetWip[$produkID][$fase_urut][$hField] == $datas['id'] ? "selected" : "";
                                //                 // $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."||". $currentTargetWip[$produkID][$fase_urut][$hField]."</option>";
                                //                 $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                //             }
                                //             $strItem .= "</select>";
                                //         }
                                //
                                //     }
                                //
                                //     $produkKomposisiFase .= "<td>";
                                //     $produkKomposisiFase .= $strItem;
                                //     $produkKomposisiFase .= "</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</tbody>";
                                // $produkKomposisiFase .= "</table>";
                                // $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                    $faseNoB++;
                }
                else {
                    $produkKomposisiFase .= "<div class='bg-ble lv12 tab-pane fade active in' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class='blink text-bold text-danger'><h4 class=''>Material " . ($faseData['nama']) . " belum diseting, silahkan klik tombol tambah</h3></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . $fase_urut;
                                $produkKomposisiFase .= "<div class='border-cehk'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $i = 0;
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                // arrprint($relSupplies);
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                // arrPrint($datas);
                                                // $queryParams .= "?&$hField='+removeCommas(document.getElementById('this').value)+'";
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-sm btn-success'><i class='fa fa-plus'></i>  tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . $fase_urut;
                                $produkKomposisiFase .= "<div class='border-chek'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                $i = 0;
                                //bagian add baru
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='".$datas['id']."'>".$datas['nama']."</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $strItem = "";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm'). submit();\" type='button' title='simpan komposisi baru' class='btn btn-sm btn-success'><i class='fa fa-plus'></i>  tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                // $produkKomposisiFase .= "<div class=''>";
                                // $produkKomposisiFase .= "<table class='table table-bordered'>";
                                // $produkKomposisiFase .= "<thead>";
                                // $produkKomposisiFase .= "<tr>";
                                // // $produkKomposisiFase .="<td>No</td>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>$hLabel</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</thead>";
                                // $produkKomposisiFase .= "<tbody>";
                                // $i = 0;
                                // // $produkKomposisiFase .="<td></td>";
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>";
                                //     $produkKomposisiFase .= "<input>";
                                //     $produkKomposisiFase .= "<input type='hidden' name='fase'>";
                                //     $produkKomposisiFase .= "</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</tbody>";
                                // $produkKomposisiFase .= "</table>";
                                // $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                }
            }
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
        }
        else {

        }
        $produkKomposisiFase .= "<div id='input_temp'></div>";

        //endregion


        //region masterproject
        // arrPrintWebs($masterProject);
        $project = "";
        if (count($masterProject) > 0) {
            $project .= "<div class='box-header'><h3>Project</h3></div>";
            $project .= "<div class='box-body no-padding'>";
            $project .= "<table class='table dataTable compact display table-bordered table-condensedx'>";

            $project .= "<thead>";
            $project .= "<tr>";
            $project .= "<th>&nbsp;</th>";
            $project .= "<th>&nbsp;</th>";
            $project .= "</tr>";
            $project .= "</thead>";

            $project .= "<tbody>";
            foreach ($masterProjectField as $key => $label) {
                $project .= "<tr>";
                $project .= "<td>$label</td>";
                $project .= "<td class='text-left'>" . formatField($key, $masterProject[$key]) . "</td>";
                $project .= "<tr>";
            }
            $project .= "</tbody>";

            $project .= "</table>";
            $project .= "</div>";
        }

        //endregion

        //region summary project
        // cekHitam(count($sumaryProject));
        $rincianProject = "";
        if (count($sumaryProject) > 0) {
            // arrprint($sumaryProject);
            // matiHere();
//            $rincianProject .= "<div class='panel'>";
            $rincianProject .= "<div class='box-header'><h3>Ringkasan anggaran</h3></div>";
            foreach ($sumaryProject as $jenisBiaya => $sumarryProject_0) {
                $submasterLabel = isset($masterLabel[$jenisBiaya]) ? $masterLabel[$jenisBiaya] : "";
                $rincianProject .= "<div class='panel panel-body no-padding table-responsive'>
                                    <h5 class='text-bold text-muted'>$submasterLabel</h5>";
                $rincianProject .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                $rincianProject .= "<thead>";
                $rincianProject .= "<tr>";
                $rincianProject .= "<th>No</th>";
                $subtotal = 0;
                foreach ($sumaryProjectLabel as $key => $label) {
                    $rincianProject .= "<th>$label</th>";
                }
                $rincianProject .= "</tr>";
                $rincianProject .= "</thead>";
                $rincianProject .= "<tbody>";
                $i = 0;
                foreach ($sumarryProject_0 as $sumarryProject__1) {
                    $i++;
                    $subtotal += $sumarryProject__1["subtotal"];
                    $rincianProject .= "<tr>";
                    $rincianProject .= "<td>$i</td>";
                    foreach ($sumaryProjectLabel as $key => $label) {
                        $rincianProject .= "<td>" . formatField($key, $sumarryProject__1[$key]) . "</td>";
                    }
                    $rincianProject .= "</tr>";
                }
                $rincianProject .= "<tr>";
                $rincianProject .= "<td colspan='" . count($sumaryProjectLabel) . "' class='text-center'>subtotal</td>";
                $rincianProject .= "<td>" . formatField("grandtotal", $subtotal) . "</td>";
                $rincianProject .= "</tr>";
                $rincianProject .= "</tbody>";
                $rincianProject .= "</table>";
                $rincianProject .= "</div>";

            }
//            $rincianProject .= "</div>";

        }

        // echo $rincianProject;
        //endregion
        // matiHere();
        $strMain = "";

        $strMain .= "<div class='box-header'>";
        $strMain .= $bomTitle;
        $strMain .= "</div>";
        $strMain .= "<div class='box-body no-padding table-responsive'>";
        $strMain .= $produkFase;
        $strMain .= $produkKomposisiFase;
        $strMain .= "</div>";

        $scriptBottom = "<script >top.console.log('cek iframe'); $('#btnReload').on('click', function(){ }); console.log( this.window ); </script>";
        $scriptBottom .= "<script>$('#untuk_reload_iframe').off(); $('#untuk_reload_iframe').on('click', function(){ var iframe = top.document.getElementById('$result');iframe.src=iframe.src; })</script>";
        $scriptBottom .= "<script>

                $('.nav-item').on('click', function(){
                    var tabThis = $('a', $(this)).attr('id');
                    localStorage.setItem('position_tab', tabThis);
                    console.log('tabThis: ' + tabThis);
                })

                var pos = localStorage.getItem('position_tab');

                if( $('#'+pos) ){
                    $('#'+pos).click()
                }

                //region menyimpan dan restore posisi scroll (by chepy)
                var scroll_$result = localStorage.getItem('scroll_$result');
                top.$('#$result').contents().scrollTop(scroll_$result);
                top.$(top.$('#$result').contents()).on('scroll', function(){
                    localStorage.setItem('scroll_$result', $(this).scrollTop());
                });
                //endregion menyimpan dan restore posisi scroll (by chepy)

                setTimeout( function(){
                    var thisFrameHeight = top.$('#$result').contents().height();
                    top.$('#$result').height(thisFrameHeight);
                    console.log('thisFrameHeight $result: ' + thisFrameHeight);
                }, 500)

        </script>";
        $p->addTags(array(
            "timWork"            => $timWork,
            "projectData"        => $project,
            "projectDataDetails" => $rincianProject,
            "content"            => $strMain,
            "scriptBottom"       => $scriptBottom,
            "display_iframe"     => "none",//biar gak panjang
            // "btn_attr" => $btnAttr,
            // "prodID" => isset($_GET['sID']) ? $_GET['sID'].$strDock : "0",
            "error_msg"          => $error,
            // "jenisTr" => $jenisTr . $str_group,
            // "trName" => $trName,
            // "alt_display" => isset($altDisplay) ? $altDisplay : "",
            // "prop_display" => isset($propDisplay) ? $propDisplay : "",

            "menu_left"        => callMenuLeft(),
            "trans_menu"       => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
        ));
        //
        $p->render();
        break;

    case "master_project_buat_tembakan":
        //untuk default edit mode
        // arrPrint($currentTargetWip);
        // $p = New Layout("", "", MODUL_TEMPLATE_PATH."template/satuan.html");

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/data_index.html");
        $targetResult = isset($result) ? "&result=$result" : "&result=result";

        $strMain = "";
        //region BOM
        if (sizeof($produk_fase) > 0) {
            $bomTitle = "<div>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h3 id='untuk_reload_iframe'><u>Rencana Kerja <b>( $produkNama )</b></u></h3>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }
        else {
            $bomTitle = "<div class='blink'>";
            // $bom .= "<div id='bom_material'>";
            $bomTitle .= "<h2 class='text-red text-bold'><u>Silahkan Buat Rencana Kerja <b>( $produkNama )</b></u></h2>";
            // $bom .= "</div>";
            $bomTitle .= "</div>";
        }

        //region master timwork
        $timWork = "";
        $timWork .= "<div class=''>";
        $timWork .= "<div style='margin-bottom: 10px;'>";
        $timWork .= "<h3 id=''><b><u>Anggota</u></b></h3>";
        $timWork .= "</div>";
        $timWork .= "<form class='form' name='timwork' id='timwork' method='post' target='result' action='$addFaseProdukKomposisiTimLink?mode=timwork$targetResult'>";
        $timWork .= "<table class='table dataTable compact display table-bordered table-condensed'>";
        $timWork .= "<thead>";
        $timWork .= "<tr>";
        $timWork .= "<td>No</td>";
        foreach ($masterTimworkLabel as $hField => $hLabel) {
            $timWork .= "<td>$hLabel</td>";
        }
        $timWork .= "<td>Action</td>";
        $timWork .= "</tr>";

        $timWork .= "</thead>";
        $timWork .= "<tbody>";
        // $timWork = "<div></div>";
        //bagaian data relasi komposisi

        // arrprint($masterTimwork);
        $i = 0;
        if (count($masterTimwork) > 0) {
            foreach ($masterTimwork as $tID =>$masterTimwork) {
                //                                        arrPrint($DataRelsuppliesBiaya);
                $labelName = $masterTimwork["employee_nama"];
                $modalLink = $previewLink."MdlTimWorkProject/";
                $timWork .= "<tr>";
                $i++;
                $timWork .= "<td>$i</td>";
                foreach ($masterTimworkLabel as $hField => $hLabel) {
                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                    $val = isset($masterTimwork[$transformKey]) ? $masterTimwork[$transformKey] : "";
                    $timWork .= "<td>" . formatField($hField, $val) . " $hLabel</td>";
                }
                //tambah logic crud
                $btn = "<div class='form-group'>";
                if(isset($allowedAccess["employee_id"]["update"]) && $allowedAccess["employee_id"]["update"] ==true){
                    $btn .= "<button type='button' title='edit' class='btn-warning' onclick=\"showModal('" . $previewLink . "MdlTimWorkProject/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                }
                if(isset($allowedAccess["employee_id"]["delete"]) && $allowedAccess["employee_id"]["delete"] ==true){
                    $btn .= "<button type='button' title='hapus' class='btn-danger'><span class='fa fa-fw fa-trash'></span></button>";
                }
                $timWork .= "<td class='text-center'>$btn</td>";
                $timWork .= "</tr>";
            }
        }

        //untuk tambah komponen
        //akses list untuk nambaha data
        // $allowedAdd = false;

        $timWork .= "<tr>";
        $timWork .= "<td></td>";
        // $allowedAccess["employee_id"]["create"]=false;
        if(isset($allowedAccess["employee_id"]["create"]) && $allowedAccess["employee_id"]["create"] ==true){
            foreach ($masterTimworkLabel as $hField => $hLabel) {

                if (isset($produk_fase_komposisiEditable[$hField])) {
                    if (isset($relEmployee[$hField])) {
                        // cekMerah(__LINE__);
                        $strTim = "<select data-style=\"btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                        // $strTim .="<select>";
                        $strTim .= "<option>==PILIH==</option>";
                        $ic = 0;
                        foreach ($relEmployee[$hField] as $datas) {
                            $ic++;
                            // arrPrint($datas);
                            $selected = isset($newData["timwork"][$produkID][$hField]) && $newData["timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                            $strTim .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                            // $strTim .= "<option >$ic</option>";
                        }
                        $strTim .= "</select>";

                    }
                    else {
                        $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                        $strTim = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                    }
                }
                else {
                    $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                    $strTim = formatField($hField, $value);
                    // $strItem ="";
                }
                $timWork .= "<td>";
                $timWork .= $strTim;
                $timWork .= "</td>";
            }
            //logic button sini allowed add
            $btnRemoveTimwork = "<button onclick=\"document.getElementById('timwork').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
            $timWork .= "<td class='text-center'>$btnRemoveTimwork</td>";
            $timWork .= "</tr>";
        }



        $timWork .= "</tbody>";
        $timWork .= "</table>";
        $timWork .= "</form>";
        $timWork .= "</div>";
        $timWork .= "<div id='input_temp_0'></div>";
        //endregion

        //region rencana proses produksi (nama_produk)
        $produkFase = "";
        $produkFase .= "<div class='overflow-h'>";
        $produkFase .= "<form class='form' name='produk_fase' id='produk_fase' method='post' target='result' action='$addFaseProdukLink?mode=produk_fase$targetResult'>";
        $produkFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
        $produkFase .= "<thead>";
        $produkFase .= "<tr>";
        $produkFase .= "<th>No</th>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $produkFase .= "<th>$produkfase_alias</th>";
        }
        $produkFase .= "<th>action</th>";
        $produkFase .= "</tr>";
        $produkFase .= "</thead>";
        $produkFase .= "<tbody>";
        // arrPrintWebs($produk_fase);
        if (isset($produk_fase) && sizeof($produk_fase)) {
            $i = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                $i++;
                $produkFase .= "<tr>";
                $produkFase .= "<td>$i</td>";
                foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                    $newKey = isset($relSuppliesHeader[$produkfaseKey]) ? $relSuppliesHeader[$produkfaseKey] : $produkfaseKey;
                    if (isset($faseData[$newKey])) {
                        $fieldValue = $faseData[$newKey];
                    }
                    else {
                        $fieldValue = "";
                    }
                    $produkFase .= "<td>" . $fieldValue . "</td>";
                }
                //region button remove
                // $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $btn ="<div>";
                if(isset($allowedAccess["fase_id"]["update"]) && $allowedAccess["fase_id"]["update"] == true){
                    $btn .= "<button type='button' title='hapus' class='btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectWorkOrder/$fase_urut/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                }
                if(isset($allowedAccess["fase_id"]["delete"]) && $allowedAccess["fase_id"]["delete"] == true){
                    $btn .= "<button type='button' title='hapus' class='btn-danger'><span class='fa fa-fw fa-trash'></span></button>";
                }
                $btn .="</div>";
                $produkFase .= "<td>$btn</td>";
                //endregion
                $produkFase .= "</tr>";
            }
            //tambahan tr untuk add data baru
        }
        $produkFase .= "<tr>";
        $produkFase .= "<td></td>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
            $readOnly = "";
            $produkFase .= "<td><input $readOnly class='form-control form-control-sm' type='text' value='$preval' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\"></td>";
        }
        $produkFase .= "<td> <span class='btn btn-sm btn-success' onclick=\"document.getElementById('produk_fase').submit();\"><i class='fa fa-plus'></i> tambah</span> </td>";
        $produkFase .= "</tr>";
        $produkFase .= "</tbody>";
        $produkFase .= "</table>";
        $produkFase .= "</form>";
        $produkFase .= "</div>";
        //endregion rencana proses produksi (nama_produk)


        //region komposisi produk fase
        if (sizeof($produk_fase) > 0) {
            $produkKomposisiFase = "<div class='border-ck'>";

            $produkKomposisiFase .= "<div style='margin-bottom: 10px;'>";
            $produkKomposisiFase .= "<h3 id=''><b><u>Rencana MATERIAL</u></b></h3>";
            $produkKomposisiFase .= "</div>";

            $produkKomposisiFase .= "<div class='nav-tabs-custom'>";
            $produkKomposisiFase .= "<div class='tab-content no-padding'>";

            $produkKomposisiFase .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";

            //arrPrintHijau($produk_fase);
            //arrPrintWebs($produk_komposisi_fase);

            $faseNoA = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                }
                else {
                    $faseNoA++;
                }
            }
            $faseNo = 0;
            $faseNoErr = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNo == 0 && $faseNoA == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 12px;' class=''> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNo++;
                }
                else {
                    $actLink = $faseNoErr == 0 ? "active" : "";
                    $produkKomposisiFase .= "<li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 14px;' class='text-red'><i class='fa fa-warning blink text-yellow'></i> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNoErr++;
                }
            }
            // arrPrint($produk_komposisi_fase);
            $produkKomposisiFase .= "</ul>";
            $faseNoB = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNoB == 0 && $faseNoA == 0 ? "active in" : "";
                    $produkKomposisiFase .= "<div class='uu lv1 tab-pane fade $actLink' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class=''>  <h4 class=''><i class='fa fa-hand-o-right'></i> &nbsp; &nbsp;" . ($faseData['nama']) . "&nbsp;&nbsp;&nbsp;<small><i class='fa fa-clock-o text-muted'></i>&nbsp;" . date("Y-m-d H:i") . "</small></h4></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        //MdlProjectKomposisiWorkorder
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $produkKomposisiFase .= "<tr>";
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["produk"]) && sizeof($produk_komposisi_fase[$fase_urut]["produk"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $DataRelsupplies) {
                                        // arrPrint($DataRelsupplies);
                                        $tID=$DataRelsupplies["id"];
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $btn = "<div>";
                                        if(isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["fase_id"]["update"] == true) {
                                            $btn .= "<button type='button' title='hapus' class='btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                                        }
                                        if(isset($allowedAccess["fase_id"]["delete"]) && $allowedAccess["fase_id"]["delete"] == true) {
                                            $btn .= "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></button>";
                                        }
                                        $btn .="</div>";

                                        $produkKomposisiFase .= "<td >$btn</td>";
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option> ----- </option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";


                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-sm btn-success'><i class='fa fa-plus'></i>  tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["biaya"]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $DataRelsuppliesBiaya) {
                                        //                                        arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi biaya' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "timwork":
                                $idForm = "timwork" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<div class='panel'>";
                                $produkKomposisiFase .= "<div class='panel-header'><h4>Tim Kerja</h4></div>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiTimLink?mode=komposisi_fase_timwork&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["timwork"]) && sizeof($produk_komposisi_fase[$fase_urut]["timwork"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["timwork"] as $DataRelsuppliesBiaya) {
                                        //                                        arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relEmployee[$hField])) {
                                            $strItem = "<select data-style=\"btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            foreach ($relEmployee[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) && $newData["komposisi_fase_timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";//clas panel
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                // $idForm = "target" . $fase_urut;
                                // $produkKomposisiFase .= "<div class=''>";
                                // $produkKomposisiFase .= "<table class='table table-bordered'>";
                                // $produkKomposisiFase .= "<thead>";
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>$hLabel</td>";
                                // }
                                //
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</thead>";
                                // $produkKomposisiFase .= "<tbody>";
                                //
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     if (isset($produk_fase_komposisiEditable[$hField])) {
                                //         if (isset($relTarget[$hField])) {
                                //             $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$addFaseHasilProduksi" . "/$produkID?mode=komposisi_target&key=$hField&&fase_id=$fase_urut&value='+encodeURI(this.value)+'$targetResult'); \">";
                                //             $strItem .= "<option> ---silahkan pilih--</option>";
                                //             foreach ($relTarget[$hField] as $datas) {
                                //                 $selected = isset($currentTargetWip[$produkID][$fase_urut][$hField]) && $currentTargetWip[$produkID][$fase_urut][$hField] == $datas['id'] ? "selected" : "";
                                //                 // $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."||". $currentTargetWip[$produkID][$fase_urut][$hField]."</option>";
                                //                 $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                //             }
                                //             $strItem .= "</select>";
                                //         }
                                //
                                //     }
                                //
                                //     $produkKomposisiFase .= "<td>";
                                //     $produkKomposisiFase .= $strItem;
                                //     $produkKomposisiFase .= "</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</tbody>";
                                // $produkKomposisiFase .= "</table>";
                                // $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                    $faseNoB++;
                }
                else {
                    $produkKomposisiFase .= "<div class='bg-ble lv12 tab-pane fade active in' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class='blink text-bold text-danger'><h4 class=''>Material " . ($faseData['nama']) . " belum diseting, silahkan klik tombol tambah</h3></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . $fase_urut;
                                $produkKomposisiFase .= "<div class='border-cehk'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $i = 0;
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                // arrprint($relSupplies);
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                // arrPrint($datas);
                                                // $queryParams .= "?&$hField='+removeCommas(document.getElementById('this').value)+'";
                                                $selected = isset($newData["komposisi_fase"][$produkID][$hField]) && $newData["komposisi_fase"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase"][$produkID][$hField]) ? $newData["komposisi_fase"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan komposisi baru' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . $fase_urut;
                                $produkKomposisiFase .= "<div class='border-chek'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                $i = 0;
                                //bagian add baru
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option>==PILIH==</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$produkID][$hField] == $datas['id'] ? "selected" : "";

                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $strItem = "";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm'). submit();\" type='button' title='simpan komposisi baru' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                // $produkKomposisiFase .= "<div class=''>";
                                // $produkKomposisiFase .= "<table class='table table-bordered'>";
                                // $produkKomposisiFase .= "<thead>";
                                // $produkKomposisiFase .= "<tr>";
                                // // $produkKomposisiFase .="<td>No</td>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>$hLabel</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</thead>";
                                // $produkKomposisiFase .= "<tbody>";
                                // $i = 0;
                                // // $produkKomposisiFase .="<td></td>";
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>";
                                //     $produkKomposisiFase .= "<input>";
                                //     $produkKomposisiFase .= "<input type='hidden' name='fase'>";
                                //     $produkKomposisiFase .= "</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</tbody>";
                                // $produkKomposisiFase .= "</table>";
                                // $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                }
            }
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
            $produkKomposisiFase .= "</div>";
        }
        else {

        }
        $produkKomposisiFase .= "<div id='input_temp'></div>";

        //endregion


        //region masterproject
        // arrPrintWebs($masterProject);
        $project = "";
        if (count($masterProject) > 0) {
            $project .= "<div class='panel'>";
            $project .= "<div class='panel-header'><h3>Project</h3></div>";
            $project .= "<div class='panel-body no-padding table-responsive'>";
            $project .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            foreach ($masterProjectField as $key => $label) {
                $project .= "<tr>";
                $project .= "<td>$label</td>";
                $project .= "<td class='text-right'>" . formatField($key, $masterProject[$key]) . "</td>";
                $project .= "<tr>";

            }
            $project .= "</div>";
            $project .= "</table>";
            $project .= "</div>";
        }
        //endregion

        //region summary project
        // cekHitam(count($sumaryProject));
        $rincianProject = "";
        if (count($sumaryProject) > 0) {
            // arrprint($sumaryProject);
            // matiHere();
            $rincianProject .= "<div class='panel'>";
            $rincianProject .= "<div class='panel panel-header'><h3>Ringkasan anggaran </h3></div>";
            foreach ($sumaryProject as $jenisBiaya => $sumarryProject_0) {
                $submasterLabel = isset($masterLabel[$jenisBiaya]) ? $masterLabel[$jenisBiaya] : "";
                $rincianProject .= "<div class='panel panel-body no-padding table-responsive'><h3>$submasterLabel</h3>";
                $rincianProject .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                $rincianProject .= "<tr>";
                $rincianProject .= "<th>No</th>";
                $subtotal = 0;
                foreach ($sumaryProjectLabel as $key => $label) {
                    $rincianProject .= "<th>$label</th>";
                }
                $rincianProject .= "</tr>";
                $i = 0;
                foreach ($sumarryProject_0 as $sumarryProject__1) {
                    $i++;
                    $subtotal += $sumarryProject__1["subtotal"];
                    $rincianProject .= "<tr>";
                    $rincianProject .= "<td>$i</td>";
                    foreach ($sumaryProjectLabel as $key => $label) {
                        $rincianProject .= "<td>" . formatField($key, $sumarryProject__1[$key]) . "</td>";
                    }
                    $rincianProject .= "</tr>";
                }
                $rincianProject .= "<tr>";
                $rincianProject .= "<td colspan='" . count($sumaryProjectLabel) . "' class='text-center'>subtotal</td>";
                $rincianProject .= "<td>" . formatField("grandtotal", $subtotal) . "</td>";
                $rincianProject .= "</tr>";
                $rincianProject .= "</table>";
                $rincianProject .= "</div>";

            }
            $rincianProject .= "</div>";

        }

        // echo $rincianProject;
        //endregion
        // matiHere();
        $strMain = $bomTitle;
        $strMain .= $produkFase;
        $strMain .= $produkKomposisiFase;
        $scriptBottom = "<script >top.console.log('cek iframe'); $('#btnReload').on('click', function(){ }); console.log( this.window ); </script>";
        $scriptBottom .= "<script>$('#untuk_reload_iframe').off(); $('#untuk_reload_iframe').on('click', function(){ var iframe = top.document.getElementById('$result');iframe.src=iframe.src; })</script>";
        $scriptBottom .= "<script>

                $('.nav-item').on('click', function(){
                    var tabThis = $('a', $(this)).attr('id');
                    localStorage.setItem('position_tab', tabThis);
                    console.log('tabThis: ' + tabThis);
                })

                var pos = localStorage.getItem('position_tab');

                if( $('#'+pos) ){
                    $('#'+pos).click()
                }

                //region menyimpan dan restore posisi scroll (by chepy)
                var scroll_$result = localStorage.getItem('scroll_$result');
                top.$('#$result').contents().scrollTop(scroll_$result);
                top.$(top.$('#$result').contents()).on('scroll', function(){
                    localStorage.setItem('scroll_$result', $(this).scrollTop());
                });
                //endregion menyimpan dan restore posisi scroll (by chepy)

                setTimeout( function(){
                    var thisFrameHeight = top.$('#$result').contents().height();
                    top.$('#$result').height(thisFrameHeight);
                    console.log('thisFrameHeight $result: ' + thisFrameHeight);
                }, 500)

        </script>";
        $p->addTags(array(
            "timWork"            => $timWork,
            "projectData"        => $project,
            "projectDataDetails" => $rincianProject,
            "content"            => $strMain,
            "scriptBottom"       => $scriptBottom,
            "display_iframe"     => "none",//biar gak panjang
            // "btn_attr" => $btnAttr,
            // "prodID" => isset($_GET['sID']) ? $_GET['sID'].$strDock : "0",
            "error_msg"          => $error,
            // "jenisTr" => $jenisTr . $str_group,
            // "trName" => $trName,
            // "alt_display" => isset($altDisplay) ? $altDisplay : "",
            // "prop_display" => isset($propDisplay) ? $propDisplay : "",

            "menu_left"        => callMenuLeft(),
            "trans_menu"       => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
        ));
        //
        $p->render();
        break;

    case "taskListLoad":
    case "taskList":

        if($mode=="taskListLoad"){
            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/tasklist.html");
        }
        else{
            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/tasklist_base.html");
        }


        $targetResult = isset($result) ? "&result=$result" : "&result=result";
        // $selector
        // $mytaskForm = createTaskListForm($masterProject,$timWork,$timWork,$addLink);

        // arrPrint($sessionData);
        // arrPrintWebs($timWork);
        // matiHere();
        if (count($timWork) > 0) {
            $addNewCustomer = "
                    BootstrapDialog.show({
                        title:'New Customer',
                        message: $('<div></div>').load('" . base_url() . "Data/add/Employee_all'),
                        draggable:true,
                        closable:true,
                        size:top.BootstrapDialog.SIZE_WIDE,
                    });
                ";

            $kol = "employee_id";
            $formWork_tim = "";
            $formWork_tim .= "<select data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=employee_id&value='+encodeURI(this.value)+'$targetResult');\">";
            $formWork_tim .= "<option>-- silahkan pilih --</option>";
            foreach ($timWork as $timWork_0) {
                $pid = $timWork_0->produk_id;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $timWork_0->employee_id) ? "selected" : "";
                $formWork_tim .= "<option $selected value='" . $timWork_0->employee_id . "'>" . $timWork_0->employee_nama . "</option>";
            }
            $formWork_tim .= "</select>";
        }
        if (count($workOrder) > 0) {
            $kol = "fase_id";
            $formWork_order = "";
            $formWork_order .= "<select  data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=fase_id&value='+encodeURI(this.value)+'$targetResult');\">";
            $formWork_order .= "<option>-- silahkan pilih --</option>";
            foreach ($workOrder as $workOrder_0) {
                $pid = $workOrder_0->produk_id;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $workOrder_0->id) ? "selected" : "";
                $formWork_order .= "<option $selected value='" . $workOrder_0->id . "'>" . $workOrder_0->nama . " </option>";
            }
            $formWork_order .= "</select>";
        }

        //region option material
        $optionmaterial = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" >";
        $optionmaterial .= "<option>-- silahkan pilih --</option>";
        $optionmaterial .= "<option>Material</option>";
        $optionmaterial .= "<option>Non material</option>";
        $optionmaterial .= "</select >";
        //endregion

        //region option progress tasklist
        $progresTask = "";
        if(count($progresTaklist)>0){
            $kol = "progress_id";
            $progresTask .= "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=progress_id&value='+encodeURI(this.value)+'$targetResult');\">";
            $progresTask .= "<option>-- silahkan pilih --</option>";
            foreach ($progresTaklist as $progresTaklist_0) {
                $pid =$produkID;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $progresTaklist_0->id) ? "selected" : "";
                $progresTask .= "<option $selected value='" . $progresTaklist_0->id . "'>" . $progresTaklist_0->nama . " </option>";
            }
            $progresTask .= "</select>";
        }
        //endregion

        //region active tasklist
        $taskListData = "";
        if (count($taskistProject) > 0) {
            // $taskListData .= "<div class='box'>";
            $taskListData .= "<div class='box-header'><h3>Daftar Tugas</h3></div>";
            $taskListData .= "<div class='box-body no-padding'>";
            $taskListData .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            $taskListData .= "<thead>";
            $taskListData .= "<tr>";
            $taskListData .= "<th>No</th>";
            foreach ($tasklistProjectField as $kry => $label) {
                $taskListData .= "<th>$label</th>";
            }
            $taskListData .= "</tr>";
            $taskListData .= "</thead>";
            $taskListData .= "<tbody>";
            $i = 0;
            foreach ($taskistProject as $taskistProject_0) {
                $i++;
                $taskListData .= "<tr>";
                $taskListData .= "<td>$i</td>";
                foreach ($tasklistProjectField as $key => $label) {
                    $taskListData .= "<td>" . $taskistProject_0[$key] . "</td>";
                }
                $taskListData .= "</tr>";
            }
            $taskListData .= "</tbody>";

            $taskListData .= "</table>";
            $taskListData .= "</div>";
        }
        else {
            $taskListData .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            $taskListData .= "<thead>";
            $taskListData .= "<tr>";
            $taskListData .= "<th>No</th>";
            foreach ($tasklistProjectField as $kry => $label) {
                $taskListData .= "<th>$label</th>";
            }
            $taskListData .= "</tr>";
            $taskListData .= "</thead>";
            $taskListData .= "<tbody>";
            $taskListData .= "<tr>";
            $taskListData .= "<td></td>";
            $taskListData .= "<td colspan='" . count($tasklistProjectField) . "' class='text-bold text-center'>Belum ada data penugasan</td>";
            $taskListData .= "</tr>";
            $taskListData .= "</tbody>";
            $taskListData .= "</table>";
        }
        //endregion
        // arrprint($masterProject);
        $project = "";
        if (count($masterProject) > 0) {
//            $project .= "<div class='box box-success'>";
            $project .= "<div class='box-header'><h3>Project</h3></div>";
            $project .= "<div class='box-body no-padding table-responsive'>";
            $project .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            foreach ($masterProjectField as $key => $label) {
                $project .= "<tr>";
                $project .= "<td>$label</td>";
                $project .= "<td class='text-right'>" . formatField($key, $masterProject->$key) . "</td>";
                $project .= "<tr>";

            }
            $project .= "</div>";
            $project .= "</table>";
            $project .= "</div>";
        }
        // echo $taskListData;

        $workOrder = "";

        if (count($workOrder) > 0) {

            // $workOrder .= "<div class='box'>";
            $workOrder .= "<div class='blink text-bold text-info box-header'><h3>$workOrderTitle #</h3></div>";
            $workOrder .= "<div class='box-body no-paddingx table-responsivex'>";
//            $workOrder .= "<div class='fa-2x'>SIAPIN FORM UNTUK PASANG TASKLIST</div>";
            $workOrder .= "<form class='forhm-control' id='tasklist_form' name='tasklist_form' target='result' action='$addtaskLink?$targetResult'>";

            $workOrder .= "<div class=''>";
            $workOrder .= "<label class='box-title'>Rencana kerja: &nbsp;</label>";
            $workOrder .= $formWork_order;
            $workOrder .= "</div>";

            $workOrder .= "<div class='row'>";
            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<label class=''>Tugas: &nbsp;</label>";
            $default_nama = isset($sessionData[$produkID]["nama"]) ? $sessionData[$produkID]["nama"] : "";
            $workOrder .= "<input type='text' class='form-control form-control-sm' value ='" . $default_nama . "'onmouseout =\"$('#input_temp').load('$selector" . "$produkID?key=nama&value='+encodeURI(this.value)+'$targetResult');\">";
            $workOrder .= "</div>";
            $workOrder .= "<div class='col-md-6' style='right: 10px;'>";
            $workOrder .= "<label class=''>Keterangan: &nbsp;</label>";
            $defaultValue = isset($sessionData[$produkID]["nilai"]) ? $sessionData[$produkID]["nilai"] : "";
            $workOrder .= "<textarea  rows=\"5\" cols=\"33\" type='textarea' class='form-control' onmouseout =\"$('#input_temp').load('$selector" . "$produkID?key=nilai&value='+encodeURI(this.value)+'$targetResult');\">$defaultValue</textarea>";
            $workOrder .= "</div>";
            $workOrder .= "</div>";

            $workOrder .= "<div class=''>";
            $workOrder .= "<label>Pelaksana: &nbsp;</label>";
            $workOrder .= $formWork_tim;
            $workOrder .= "</div>";


            $workOrder .= "<div class='row'>";
            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<div class='form-group'>";
            $workOrder .= "<label>Mulai: &nbsp;</label>";
            $workOrder .= "<div class='input-group date'>";
            $workOrder .= "<div class='input-group-addon'>";
            $workOrder .= "<i class='fa fa-calendar'></i>";
            $workOrder .= "</div>";
            $defaultStartDate = isset($sessionData[$produkID]["start_date"]) ? date("Y-m-d", $sessionData[$produkID]["start_date"]) : "";
            $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_start' value='$defaultStartDate' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_start&value='+encodeURI(this.value)+'$targetResult');\"> ";
            $workOrder .= "</div>";
            $workOrder .= "</div>";
            $workOrder .= "</div>";

            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<div class='form-group'>";
            $workOrder .= "<label class='box-title'>Tenggat: &nbsp;</label>";
            $workOrder .= "<div class='input-group date'>";
            $workOrder .= "<div class='input-group-addon'>";
            $workOrder .= "<i class='fa fa-calendar'></i>";
            $workOrder .= "</div>";
            $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_end' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_end&value='+encodeURI(this.value)+'$targetResult');\"> ";
            $workOrder .= "</div>";
            $workOrder .= "</div>";
            $workOrder .= "</div>";

            //menggunakan material
            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<label>Menggunakan material? &nbsp;</label>"; //jika checked buka form untuk input materal
            $workOrder .= $optionmaterial;
            $workOrder .= "</div>";

            //progres
            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<div class='form-group'>";
            $workOrder .= "<label>Status: &nbsp;</label>"; //jika checked buka form untuk input materal
            $workOrder .= "$progresTask";
            $workOrder .= "</div>";
            $workOrder .= "</div>";

            $workOrder .= "<div class='col-md-12'>";
            $workOrder .= "<button type='button' class='btn btn-block btn-success btn-sm' onclick=\"document.getElementById('tasklist_form').submit();\">Simpan</button>";
            $workOrder .= "</div>";

            $workOrder .= "</div>";

            $workOrder .= "</form>";
            $workOrder .= "</div>";
            // $workOrder .="</div>";
            $workOrder .= "<div id='input_temp'></div>";
        }

//        echo $workOrder;

        $p->addTags(array(
            "taskListNew"        => $workOrder,
            "tasklist"           => $taskListData,
            "timWork"            => "",
            "projectData"        => $project,
            "projectDataDetails" => "",
            "content"            => $strMain,
            "workOrder"          => $workOrder,
            "scriptBottom"       => $scriptBottom,
            "display_iframe"     => "none",//biar gak panjang
            "error_msg"          => $error,

            "menu_left"        => callMenuLeft(),
            "trans_menu"       => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),

        ));

        $p->render();
        break;

//    case "taskListLoad":
//
//        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/tasklist.html");
//        $targetResult = isset($result) ? "&result=$result" : "&result=result";
//        // $selector
//        // $mytaskForm = createTaskListForm($masterProject,$timWork,$timWork,$addLink);
//
//        // arrPrint($sessionData);
//        // arrPrintWebs($timWork);
//        // matiHere();
//        if (count($timWork) > 0) {
//            $addNewCustomer = "
//                    BootstrapDialog.show(
//                       {
//                            title:'New Customer',
//                            message: $('<div></div>').load('" . base_url() . "Data/add/Employee_all'),
//                            draggable:true,
//                            closable:true,
//                            size:top.BootstrapDialog.SIZE_WIDE,
//                        });
//        ";
//
//            $kol = "employee_id";
//            $formWork_tim = "";
//            $formWork_tim .= "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=employee_id&value='+encodeURI(this.value)+'$targetResult');\">";
//            $formWork_tim .= "<option>==PILIH==</option>";
//            foreach ($timWork as $timWork_0) {
//                $pid = $timWork_0->produk_id;
//                // cekMErah($sessionData[$pid][$kol]);
//                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $timWork_0->employee_id) ? "selected" : "";
//                $formWork_tim .= "<option $selected value='" . $timWork_0->employee_id . "'>" . $timWork_0->employee_nama . "</option>";
//            }
//            $formWork_tim .= "</select>";
//        }
//        if (count($workOrder) > 0) {
//            $kol = "fase_id";
//            $formWork_order = "";
//            $formWork_order .= "<select  data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=fase_id&value='+encodeURI(this.value)+'$targetResult');\">";
//            $formWork_order .= "<option>-- silahkan pilih --</option>";
//            foreach ($workOrder as $workOrder_0) {
//                $pid = $workOrder_0->produk_id;
//                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $workOrder_0->id) ? "selected" : "";
//                $formWork_order .= "<option $selected value='" . $workOrder_0->id . "'>" . $workOrder_0->nama . " </option>";
//            }
//            $formWork_order .= "</select>";
//        }
//
//        //region option material
//        $optionmaterial = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" >";
//        $optionmaterial .= "<option>==PILIH==</option>";
//        $optionmaterial .= "<option>Material</option>";
//        $optionmaterial .= "<option>Non material</option>";
//        $optionmaterial .= "</select >";
//        //endregion
//
//        //region option progress tasklist
//    $progresTask = "";
//         if(count($progresTaklist)>0){
//             $kol = "progress_id";
//             $progresTask .= "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=progress_id&value='+encodeURI(this.value)+'$targetResult');\">";
//             $progresTask .= "<option>==PILIH==</option>";
//             foreach ($progresTaklist as $progresTaklist_0) {
//                 $pid =$produkID;
//                 $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $progresTaklist_0->id) ? "selected" : "";
//                 $progresTask .= "<option $selected value='" . $progresTaklist_0->id . "'>" . $progresTaklist_0->nama . " </option>";
//             }
//             $progresTask .= "</select>";
//         }
//        //endregion

//        //region active tasklist
//        $taskListData = "";
//        if (count($taskistProject) > 0) {
//            // $taskListData .= "<div class='box'>";
//            $taskListData .= "<div class='box-header'><h3>Daftar Tugas</h3></div>";
//            $taskListData .= "<div class='box-body no-padding'>";
//            $taskListData .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//            $taskListData .= "<thead>";
//            $taskListData .= "<tr>";
//            $taskListData .= "<th>No</th>";
//            foreach ($tasklistProjectField as $kry => $label) {
//                $taskListData .= "<th>$label</th>";
//            }
//            $taskListData .= "</tr>";
//            $taskListData .= "</thead>";
//            $taskListData .= "<tbody>";
//            $i = 0;
//            foreach ($taskistProject as $taskistProject_0) {
//                $i++;
//                $taskListData .= "<tr>";
//                $taskListData .= "<td>$i</td>";
//                foreach ($tasklistProjectField as $key => $label) {
//                    $taskListData .= "<td>" . $taskistProject_0[$key] . "</td>";
//                }
//                $taskListData .= "</tr>";
//
//
//            }
//            $taskListData .= "</tbody>";
//
//            $taskListData .= "</table>";
//            $taskListData .= "</div>";
//        }
//        else {
//            $taskListData .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//            $taskListData .= "<thead>";
//            $taskListData .= "<tr>";
//            $taskListData .= "<th>No</th>";
//            foreach ($tasklistProjectField as $kry => $label) {
//                $taskListData .= "<th>$label</th>";
//            }
//            $taskListData .= "</tr>";
//            $taskListData .= "</thead>";
//            $taskListData .= "<tbody>";
//            $taskListData .= "<tr>";
//            $taskListData .= "<td></td>";
//            $taskListData .= "<td colspan='" . count($tasklistProjectField) . "' class='text-bold text-center'>Belum ada data penugasan</td>";
//            $taskListData .= "</tr>";
//            $taskListData .= "</tbody>";
//            $taskListData .= "</table>";
//        }
//        //endregion
//        // arrprint($masterProject);
//        $project = "";
//        if (count($masterProject) > 0) {
////            $project .= "<div class='box box-success'>";
//            $project .= "<div class='box-header'><h3>Project</h3></div>";
//            $project .= "<div class='box-body no-padding table-responsive'>";
//            $project .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//            foreach ($masterProjectField as $key => $label) {
//                $project .= "<tr>";
//                $project .= "<td>$label</td>";
//                $project .= "<td class='text-right'>" . formatField($key, $masterProject->$key) . "</td>";
//                $project .= "<tr>";
//
//            }
//            $project .= "</div>";
//            $project .= "</table>";
//            $project .= "</div>";
//        }
//        // echo $taskListData;
//
//
//        $workOrder = "";
//        if (count($workOrder) > 0) {
//            // $workOrder .= "<div class='box'>";
//            $workOrder .= "<div class='blink text-bold text-info box-header'><h3>$workOrderTitle **</h3></div>";
//            $workOrder .= "<div class='box-body no-paddingx table-responsives'>";
//            $workOrder .= "<div class='fa-2x'>SIAPIN FORM UNTUK PASANG TASKLIST</div>";
//            $workOrder .= "<form class='forhm-control' id='tasklist_form' name='tasklist_form' target='result' action='$addtaskLink?$targetResult'>";
//            $workOrder .= "<div class=''>";
//            $workOrder .= "<label class='box-title'>Rencana kerja</label>";
//            $workOrder .= $formWork_order;
//            $workOrder .= "</div>";
//            $workOrder .= "<div class='row'>";
//            $workOrder .= "<div class='col-md-6'>";
//            $workOrder .= "<label class=''>Tugas</label>";
//            $default_nama = isset($sessionData[$produkID]["nama"]) ? $sessionData[$produkID]["nama"] : "";
//            $workOrder .= "<input type='text' class='form-control form-control-sm' value ='" . $default_nama . "'onmouseout =\"$('#input_temp').load('$selector" . "$produkID?key=nama&value='+encodeURI(this.value)+'$targetResult');\">";
//            $workOrder .= "</div>";
//            $workOrder .= "<div class='col-md-6' style='right: 10px;'>";
//            $workOrder .= "<label class=''>Keterangan</label>";
//            $defaultValue = isset($sessionData[$produkID]["nilai"]) ? $sessionData[$produkID]["nilai"] : "";
//            $workOrder .= "<textarea  rows=\"5\" cols=\"33\" type='textarea' class='form-control' onmouseout =\"$('#input_temp').load('$selector" . "$produkID?key=nilai&value='+encodeURI(this.value)+'$targetResult');\">$defaultValue</textarea>";
//            $workOrder .= "</div>";
//            $workOrder .= "</div>";
//
//            $workOrder .= "<div class='form-group'>";
//            $workOrder .= "<label>Pelaksana</label>";
//            $workOrder .= $formWork_tim;
//            $workOrder .= "</div>";
//
//
//            $workOrder .= "<div class='row'>";
//            $workOrder .= "<div class='col-md-6'>";
//            $workOrder .= "<div class='form-group'>";
//            $workOrder .= "<label>Mulai</label>";
//            $workOrder .= "<div class='input-group date'>";
//            $workOrder .= "<div class='input-group-addon'>";
//            $workOrder .= "<i class='fa fa-calendar'></i>";
//            $workOrder .= "</div>";
//            $defaultStartDate = isset($sessionData[$produkID]["start_date"]) ? date("Y-m-d", $sessionData[$produkID]["start_date"]) : "";
//            $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_start' value='$defaultStartDate' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_start&value='+encodeURI(this.value)+'$targetResult');\"> ";
//            $workOrder .= "</div>";
//            $workOrder .= "</div>";
//            $workOrder .= "</div>";
//
//            $workOrder .= "<div class='col-md-6'>";
//            $workOrder .= "<div class='form-group'>";
//            $workOrder .= "<label class='box-title'>Tenggat</label>";
//            $workOrder .= "<div class='input-group date'>";
//            $workOrder .= "<div class='input-group-addon'>";
//            $workOrder .= "<i class='fa fa-calendar'></i>";
//            $workOrder .= "</div>";
//            $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_end' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_end&value='+encodeURI(this.value)+'$targetResult');\"> ";
//            $workOrder .= "</div>";
//            $workOrder .= "</div>";
//            $workOrder .= "</div>";
//
//            //menggunakan material
//            $workOrder .= "<div class='col-md-6'>";
//            $workOrder .= "<label>Menggunakan material ?</label>"; //jika checked buka form untuk input materal
//            $workOrder .= $optionmaterial;
//            $workOrder .= "</div>";
//
//            //progres
//            $workOrder .= "<div class='col-md-6'>";
//            $workOrder .= "<div class='form-group'>";
//            $workOrder .= "<label>Status</label>"; //jika checked buka form untuk input materal
//            $workOrder .= "$progresTask";
//            $workOrder .= "</div>";
//            $workOrder .= "</div>";
//
//
//            $workOrder .= "<div class='col-md-12'>";
//            $workOrder .= "<button type='button' class='btn btn-block btn-success btn-sm' onclick=\"document.getElementById('tasklist_form').submit();\">Simpan</button>";
//            $workOrder .= "</div>";
//
//            $workOrder .= "</div>";
//
//
//            $workOrder .= "</form>";
//            $workOrder .= "</div>";
//            // $workOrder .="</div>";
//            $workOrder .= "<div id='input_temp'></div>";
//        }
//
////        echo $workOrder;
//
//        $p->addTags(array(
//            "taskListNew"        => $workOrder,
//            "tasklist"           => $taskListData,
//            "timWork"            => "",
//            "projectData"        => $project,
//            "projectDataDetails" => "",
//            "content"            => $strMain,
//            "workOrder"          => $workOrder,
//            "scriptBottom"       => $scriptBottom,
//            "display_iframe"     => "none",//biar gak panjang
//            "error_msg"          => $error,
//
////            "menu_left"        => callMenuLeft(),
////            "trans_menu"       => callTransMenu(),
////            "float_menu_atas"  => callFloatMenu('atas'),
////            "float_menu_bawah" => callFloatMenu(),
////            "menu_taskbar"     => callMenuTaskbar(),
////            "btn_back"         => callBackNav(),
//
//        ));
//
//        $p->render();
//        break;

    case "barcodeView":
        $p = New Layout("", "", MODUL_TEMPLATE_PATH ."template/modalBarcode.html");
        $p->addTags(array(
            "content"  => $content,
            "jsBottom" => $jsBottom,
        ));

        $p->render();
        break;
}