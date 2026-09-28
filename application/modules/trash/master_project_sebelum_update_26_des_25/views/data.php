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
            $(document).ready( function(){
                var arrayOnprePre;
                var arrayOnProgress;
                var arrayHistory;
                var arrayRecap;

                arrayRecap = $('#arrayRecap').DataTable({
                    pageLength: -1,
                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
                    scrollY: 'calc(100%-400)',
                    scrollX: true,
                    scrollCollapse: false,
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

            });
        </script>";

        }


        $scriptBottom .= isset($scriptBotto) ? $scriptBotto : "";


        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(
            array(
                "error_msg" => $error,
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "alt_display" => isset($altDisplay) ? $altDisplay : "",
                "prop_display" => isset($propDisplay) ? $propDisplay : "",

                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),

                "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",

                "onprogress_title" => isset($onprogressTitle) ? $onprogressTitle : "",
                "onprogress_content" => isset($strOnprog) ? $strOnprog : "",
                "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => isset($strOnprogView) ? $strOnprogView : "",
                "onprop_display_view" => isset($onpropDisplayView) ? $onpropDisplayView : "",

                "onprop_payment_view" => isset($arrayOnProgressPaymentView) ? $arrayOnProgressPaymentView : "none",

                "add_link" => isset($addLinkStr) ? $addLinkStr : "",
                "history_title" => isset($historyTitle) ? $historyTitle : "",
                "history_content" => isset($strHist) ? $strHist : "",
                "history_footer" => isset($strHistFooter) ? $strHistFooter : "",
                "recap_title" => isset($recapTitle) ? $recapTitle : "",
                "recap_content" => isset($strRecap) ? $strRecap : "",
                "recap_footer" => isset($strRecapFooter) ? $strRecapFooter : "",
                "profile_name" => isset($this->session->login['nama']) ? $this->session->login['nama'] : "",
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
                "index_active" => "class='active'",
                "time_line" => $time_line,
                "keterangan_notif" => isset($keterangan_notif) ? $keterangan_notif : "",

            )
        );

        $p->render();

        break;
    case "master_project":

        $p = New Layout("Master Project", "Project Manager", MODUL_TEMPLATE_PATH . "template/data_index.html");
        $targetResult = isset($result) ? "&result=$result" : "&result=result";

        if (isset($errMsg) && strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        $strMain = "";
        //region BOM
        if (sizeof($produk_fase) > 0) {
            $bomTitle = "<div>";
            $bomTitle .= "<h3 id='untuk_reload_iframe'><u>Rencana Kerja <b>( $produkNama )</b></u></h3>";
            $bomTitle .= "</div>";
        }
        else {
            $bomTitle = "<div>";
            $bomTitle .= "<h2 class='text-red text-bold'><u class='blink'>Silahkan Buat Rencana Kerja </b></u></h2>";
            $bomTitle .= "</div>";
        }

        //region master timwork
        $timWork = "";
        $timWork .= "<div class='container_timWork'>";
        $timWork .= "<div class='box-header'>";
        $timWork .= "<h3 id=''><b><u>Anggota</u></b></h3>";
        $timWork .= "</div>";
        $timWork .= "<div class='box-body no-padding'>";
        $timWork .= "<form class='form' name='timwork' id='timwork' method='post' target='result' action='$addFaseProdukKomposisiTimLink?mode=timwork'>";
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

        $i = 0;
        if (count($masterTimwork) > 0) {
            foreach ($masterTimwork as $tID => $masterTimwork) {
                $labelName = $masterTimwork["employee_nama"];
                $modalLink = $previewLink . "MdlTimWorkProject/";
                $timWork .= "<tr>";
                $i++;
                $timWork .= "<td>$i</td>";
                foreach ($masterTimworkLabel as $hField => $hLabel) {
                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                    $val = isset($masterTimwork[$transformKey]) ? $masterTimwork[$transformKey] : "";
                    $timWork .= "<td>" . formatField($hField, $val) . "</td>";
                }
                //tambah logic crud
                $btn = "<div>";
                if (isset($allowedAccess["employee_id"]["update"]) && $allowedAccess["employee_id"]["update"] == true) {
                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlTimWorkProject/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                }
                if (isset($allowedAccess["employee_id"]["delete"]) && $allowedAccess["employee_id"]["delete"] == true) {
                    $btn .= "<button cx_tmpnamamaterial='$labelName' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='TimWorkProject' type='button' title='clik untuk menghapus realasi teamwork' class='btn btn-xs btn-flat btn-danger delTimWork'><i class='fa fa-fw fa-trash'></i></button>";
                }
                $btn .= "</div>";
                $timWork .= "<td class='text-center'>$btn</td>";
                $timWork .= "</tr>";
            }
        }

        //untuk tambah komponen
        //akses list untuk nambaha data
        $timWork .= "<tr>";
        $timWork .= "<td></td>";
        if (isset($allowedAccess["employee_id"]["create"]) && $allowedAccess["employee_id"]["create"] == true) {
            foreach ($masterTimworkLabel as $hField => $hLabel) {
                if (isset($produk_fase_komposisiEditable[$hField])) {
                    if (isset($relEmployee[$hField])) {
                        $strTim = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+''); \">";
                        $strTim .= "<option value='0'>==PILIH==</option>";
                        $ic = 0;
                        foreach ($relEmployee[$hField] as $datas) {
                            $ic++;
                            $selected = isset($newData["timwork"][$produkID][$hField]) && $newData["timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                            $strTim .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                        }
                        $strTim .= "</select>";
                    }
                    else {
                        $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                        $strTim = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+'');\">";
                    }
                }
                else {
                    $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                    $strTim = formatField($hField, $value);
                }
                $timWork .= "<td>";
                $timWork .= $strTim;
                $timWork .= "</td>";
            }
            //logic button sini allowed add
            $btnAddTimwork = "<button onclick=\"document.getElementById('timwork').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
            $timWork .= "<td class='text-center'>$btnAddTimwork</td>";
            $timWork .= "</tr>";
        }

        $timWork .= "</tbody>";
        $timWork .= "</table>";
        $timWork .= "</form>";
        $timWork .= "</div>";
        $timWork .= "</div>";
        $timWork .= "<div id='input_temp_0'></div>";

        $timWork .= "<script>
            function reload_timework(){
                $('.container_timWork').load('$modulClassLink/showTeamWork/$produkID');
            }
            function init_delTimWork(){
                $('.delTimWork').off();
                $('.delTimWork').on('click', function(){
                    var cx_tmpnamamaterial = $(this).attr('cx_tmpnamamaterial');
                    var cx_deletelink = $(this).attr('cx_deletelink');
                    var cx_tid = $(this).attr('cx_tid');
                    var cx_mdl = $(this).attr('cx_mdl');
                    swal({
                        title: 'Apakah kamu yakin untuk menghapus?',
                        html: \"<span class='text-bold text-red text-uppercase'>\"+cx_tmpnamamaterial+\"</span> akan dihapus, data yg telah di hapus tidak bisa dikembalikan.\",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'setuju & lanjutkan!'
                    })
                    .then(function(){
                        swal('proses menghapus<br>mohon tunggu sebentar');
                        swal.enableLoading();
                        $.ajax({
                            url: cx_deletelink + '' + cx_mdl + '/' + cx_tid,
                            success: function(res){
                                arrDatas = JSON.parse(res)
                                if(arrDatas.status){
                                    setTimeout(function(){
                                        swal('SUKSES', 'data berhasil dihapus', 'success');
                                        top.reload_timework();\n
                                        top.reloadSelectpicker();\n
                                    },1000)
                                }
                            }
                        });
                    })
                    .catch(function(reason){
                        //alert(\"The alert was dismissed by the user: \"+reason);
                    });
                })
            }
            init_delTimWork();
        </script>";

        //endregion

        //region masterproject
        $project = "";
        $lock = 0;
        $no_kontrak = 0;
        $nilai_proyek = "";
        $batas_waktu = "";
        $transaksi_no = "";
        $project_start = 0;

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

//            arrPrint($masterProjectField);

            $project .= "<tbody>";
            foreach ($masterProjectField as $key => $label) {

                $text_color = $key == "harga" ? "text-orange text-bold" : "";

                $project .= "<tr>";
                $project .= "<td key='$key' class='$text_color'>$label</td>";
                $project .= "<td class='text-left $text_color'>" . formatField($key, $masterProject[$key]) . "</td>";
                $project .= "</tr>";

                switch ($key) {
                    case "nomor_kontrak":
                        $no_kontrak = isset($masterProject[$key]) && $masterProject[$key] != '' ? $masterProject[$key] : "";
                        break;
                    case "harga":
                        $nilai_proyek = $masterProject[$key];
                        break;
                    case "end_dtime":
                        $batas_waktu = $masterProject[$key];
                        break;
                    case "lock":
                        $lock = $masterProject[$key];
                        break;
                    case "transaksi_no":
                        $transaksi_no = $masterProject[$key];
                        break;
                    case "project_start":
                        $project_start = $masterProject[$key];
                        break;
                }
            }
            $project .= "</tbody>";
            $project .= "</table>";
            $project .= "</div>";
        }
        //endregion

        //region komposisi produk fase

        $produkKomposisiFase = "<div class='box box-success'>";
        $produkKomposisiFase .= "<div class='box-header'>";
        $produkKomposisiFase .= "<h3 id=''>
        <b><u>SETTING RAB</u></b>
        <div><sup><small><r><i>adalah total bahan baku dan biaya dari total keseluruhan projek</i></r></small></sip></div>
        </h3>";
        $produkKomposisiFase .= "</div>";

        $produkKomposisiFase .= "<div id='containerPreLoadProdukFase' class='box-body no-padding'>";

//        //JIKA ADA UPDATE COPAS DARI SINI SAJA ( CONTROLLER->MasterData->showKomposisiProdukFase() )
//        if (sizeof($produk_fases) > 0) {
//
//            $produkKomposisiFase .= "<div class='nav-tabs-custom'>";
//            $produkKomposisiFase .= "<div class='tab-content no-padding'>";
//            $produkKomposisiFase .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";
//
//            $faseNoA = 0;
//            foreach ($produk_fase as $fase_urut => $faseData) {
//                if(isset($produk_komposisi_fase[$fase_urut])){
//
//                }
//                else{
//                    $faseNoA++;
//                }
//            }
//            $faseNo = 0;
//            $faseNoErr = 0;
//            foreach ($produk_fase as $fase_urut => $faseData) {
//                if (isset($produk_komposisi_fase[$fase_urut])) {
//                    $actLink = $faseNo == 0 && $faseNoA == 0 ? "active" : "";
//                    $produkKomposisiFase .= "
//                    <li class='nav-item $actLink'>
//                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 12px;' class=''> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
//                    </li>";
//                    $faseNo++;
//                }
//                else {
//                    $actLink = $faseNoErr == 0 ? "active" : "";
//                    $produkKomposisiFase .= "
//                    <li class='nav-item $actLink'>
//                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 14px;' class='text-red'><i class='fa fa-warning blink text-yellow'></i> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
//                    </li>";
//                    $faseNoErr++;
//                }
//            }
//
//            $produkKomposisiFase .= "</ul>";
//            $faseNoB = 0;
//            foreach ($produk_fase as $fase_urut => $faseData) {
//                if (isset($produk_komposisi_fase[$fase_urut])) {
//                    $actLink = $faseNoB == 0 && $faseNoA == 0 ? "active in" : "";
//                    $produkKomposisiFase .= "<div class='uu lv1 tab-pane fade $actLink' id='tab-fase_$fase_urut'>";
//                    $produkKomposisiFase .= "
//                    <div class='box box-solid'>
//                        <div class='box-header with-border'>
//                            <h3 class='box-title'><i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;".($faseData['nama'])."&nbsp;&nbsp;&nbsp;<small><i class='fa fa-clock-o text-muted'></i>&nbsp;".date("Y-m-d H:i")."</small></h3>
//                        </div>
//                        <div class='box-body'>";
//
//                    $total_anggaran_unit = 0;
//                    $jual_per_unit = 0;
//
//                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
//                        //MdlProjectKomposisiWorkorder
//                        switch ($hFieldKey) {
//                            case "produk":
//                                $idForm = "bahan_baku" . "$fase_urut";
//                                $produkKomposisiFase .= "<div class='$idForm'>";
//                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut'>";
//                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                $produkKomposisiFase .= "<thead>";
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td>No</td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    $produkKomposisiFase .= "<td>$hLabel</td>";
//                                }
//                                if($lock==0){
//                                    $produkKomposisiFase .= "<td>action</td>";
//                                }
//                                $produkKomposisiFase .= "</tr>";
//                                $produkKomposisiFase .= "</thead>";
//                                $produkKomposisiFase .= "<tbody>";
//                                $produkKomposisiFase .= "<tr>";
//                                $i = 0;
//                                $arrSelected = array();
//                                if (isset($produk_komposisi_fase[$fase_urut]["produk"]) && sizeof($produk_komposisi_fase[$fase_urut]["produk"])) {
//                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $DataRelsupplies) {
//                                        $tID=$DataRelsupplies["id"];
//                                        $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
//                                        $produkKomposisiFase .= "<tr>";
//                                        $i++;
//                                        $produkKomposisiFase .= "<td>$i</td>";
//                                        $tmpNamaMaterial = '';
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
//
////                                            cekMerah($hField);
////                                            cekMerah($transformKey);
////                                            cekMerah($val);
////                                            cekMerah("===============");
//
//                                            $origVal=$val;
//                                            $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;
//
//                                            $produkKomposisiFase .= "<td hField='$hField' title='$origVal'>" . formatField($hField, $val) . "</td>";
//                                            $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val : "";
//                                            $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
//                                        }
//                                        $btn = "<div>";
//                                        if(isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["produk_dasar_id"]["update"] == true) {
//                                            $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
//                                        }
//                                        if(isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
//                                            $btn .= "<button cx_jenis='produk' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorder' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                        }
//                                        $btn .="</div>";
//                                        if($lock==0){
//                                            $produkKomposisiFase .= "<td >$btn</td>";
//                                        }
//                                        $produkKomposisiFase .= "</tr>";
//                                    }
//                                }
//
//                                if($lock==0){
//                                    //untuk tambah komponen
//                                    $produkKomposisiFase .= "</tr>";
//                                    $produkKomposisiFase .= "<tr>";
//                                    $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                    foreach ($hLabelData as $hField => $hLabel) {
//                                        if (isset($produk_fase_komposisiEditable[$hField])) {
//                                            if (isset($relSupplies[$hField])) {
//                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                                $strItem .= "<option value='0'>==PILIH==</option>";
//                                                $queryParams = "";
//                                                foreach ($relSupplies[$hField] as $datas) {
//                                                    $selected = isset($newData["komposisi_fase"][$fase_urut][$produkID][$hField]) && $newData["komposisi_fase"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
//                                                    $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                    $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                                }
//                                                $strItem .= "</select>";
//                                            }
//                                            else {
//                                                $value = isset($newData["komposisi_fase"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase"][$fase_urut][$produkID][$hField] : "";
//                                                $strItem = "<input id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                            }
//                                        }
//                                        else {
//                                            $value = isset($newData["komposisi_fase"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase"][$fase_urut][$produkID][$hField] : "";
//                                            $defValue = is_numeric($value) ? number_format($value) : $value;
//                                            $strItem = "<span id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                        }
//                                        $produkKomposisiFase .= "<td>";
//                                        $produkKomposisiFase .= $strItem;
//                                        $produkKomposisiFase .= "</td>";
//                                    }
//
//
//                                    $btnRemoveFasekomposisi = "<button id='komposisi_fase_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                    $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                    $produkKomposisiFase .= "</tr>";
//                                }
//
//
//                                $produkKomposisiFase .= "</tbody>";
//                                $produkKomposisiFase .= "</table>";
//                                $produkKomposisiFase .= "</form>";
//                                $produkKomposisiFase .= "</div>";
//                                break;
//                            case "biaya":
//                                $idForm = "biaya" . "$fase_urut";
//                                $produkKomposisiFase .= "<div class='$idForm'>";
//                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut'>";
//                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                $produkKomposisiFase .= "<thead>";
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td>No</td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    $produkKomposisiFase .= "<td>$hLabel</td>";
//                                }
//
//                                if($lock==0){
//                                    $produkKomposisiFase .= "<td>Action</td>";
//                                }
//
//                                $produkKomposisiFase .= "</tr>";
//
//                                $produkKomposisiFase .= "</thead>";
//                                $produkKomposisiFase .= "<tbody>";
//                                //bagaian data relasi komposisi
//                                // arrprint($relBiaya);
//                                $i = 0;
//                                $arrSelected = array();
//                                if (isset($produk_komposisi_fase[$fase_urut]["biaya"]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya"]) > 0) {
//                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $DataRelsuppliesBiaya) {
//                                        $tID=$DataRelsuppliesBiaya["id"];
//                                        $arrSelected[] = $DataRelsuppliesBiaya["produk_dasar_id"];
//                                        $produkKomposisiFase .= "<tr>";
//                                        $i++;
//                                        $tmpNamaMaterial = '';
//                                        $produkKomposisiFase .= "<td>$i</td>";
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
//                                            $produkKomposisiFase .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
//                                            $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val : "";
//                                            $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
//                                        }
//
//                                        $btn = "<div>";
//                                        if(isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
//                                            $btn .= "<button cx_jenis='biaya' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorder' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                        }
//                                        $btn .="</div>";
//
//                                        if($lock==0){
//                                            $produkKomposisiFase .= "<td >$btn</td>";
//                                        }
//
//                                        $produkKomposisiFase .= "</tr>";
//                                    }
//                                }
//
//                                if($lock==0){
//                                    //untuk tambah komponen
//                                    $produkKomposisiFase .= "<tr>";
//                                    $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                    foreach ($hLabelData as $hField => $hLabel) {
//                                        if (isset($produk_fase_komposisiEditable[$hField])) {
//                                            if (isset($relBiaya[$hField])) {
//                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                                $strItem .= "<option value='0'>==PILIH==</option>";
//                                                foreach ($relBiaya[$hField] as $datas) {
//                                                    $selected = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
////                                                    $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                    $disable = "";
//                                                    $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                                }
//                                                $strItem .= "</select>";
//                                            }
//                                            else {
//                                                $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
//                                                if(is_numeric($value)){
//                                                    $strItem = "<input id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                                }
//                                                else{
//                                                    $strItem = "<input id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); });\">";
//                                                }
//                                            }
//                                        }
//                                        else {
//                                            $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
//                                            $defValue = is_numeric($value) ? number_format($value) : $value;
//                                            $strItem = "<span id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
////                                        $strItem = "<input id='inhide_komposisi_fase_biaya_$fase_urut$hField' class='hidden' value='$defValue'>";
//                                        }
//                                        $produkKomposisiFase .= "<td>";
//                                        $produkKomposisiFase .= $strItem;
//                                        $produkKomposisiFase .= "</td>";
//                                    }
//                                    $btnRemoveFasekomposisi = "<button id='komposisi_fase_biaya_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi biaya' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                    $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                    $produkKomposisiFase .= "</tr>";
//                                }
//
//                                $produkKomposisiFase .= "</tbody>";
//                                $produkKomposisiFase .= "</table>";
//                                $produkKomposisiFase .= "</form>";
//                                $produkKomposisiFase .= "</div>";
//                                break;
//                            case "timwork":
//                                $idForm = "timwork" . "$fase_urut";
//                                $produkKomposisiFase .= "<div class='$idForm'>";
//                                $produkKomposisiFase .= "<div class='panel'>";
//                                $produkKomposisiFase .= "<div class='panel-header'><h4>Tim Kerja</h4></div>";
//                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiTimLink?mode=komposisi_fase_timwork&fase_id=$fase_urut$targetResult'>";
//                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                $produkKomposisiFase .= "<thead>";
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td>No</td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    $produkKomposisiFase .= "<td>$hLabel</td>";
//                                }
//                                $produkKomposisiFase .= "<td>Action</td>";
//                                $produkKomposisiFase .= "</tr>";
//
//                                $produkKomposisiFase .= "</thead>";
//                                $produkKomposisiFase .= "<tbody>";
//                                //bagaian data relasi komposisi
//                                // arrprint($relBiaya);
//                                $i = 0;
//                                if (isset($produk_komposisi_fase[$fase_urut]["timwork"]) && sizeof($produk_komposisi_fase[$fase_urut]["timwork"]) > 0) {
//                                    foreach ($produk_komposisi_fase[$fase_urut]["timwork"] as $DataRelsuppliesBiaya) {
//                                        //                                        arrPrint($DataRelsuppliesBiaya);
//                                        $produkKomposisiFase .= "<tr>";
//                                        $i++;
//                                        $produkKomposisiFase .= "<td>$i</td>";
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
//                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
//                                        }
//                                        $produkKomposisiFase .= "</tr>";
//                                    }
//                                }
//
//                                //untuk tambah komponen
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td></td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    if (isset($produk_fase_komposisiEditable[$hField])) {
//                                        if (isset($relEmployee[$hField])) {
//                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
//                                            $strItem .= "<option value='0'>==PILIH==</option>";
//                                            foreach ($relEmployee[$hField] as $datas) {
//                                                $selected = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) && $newData["komposisi_fase_timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
//                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                            }
//                                            $strItem .= "</select>";
//
//                                        }
//                                        else {
//                                            $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
//                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
//                                        }
//                                    }
//                                    else {
//                                        $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
//                                        $strItem = formatField($hField, $value);
//                                        // $strItem ="";
//                                    }
//                                    $produkKomposisiFase .= "<td>";
//                                    $produkKomposisiFase .= $strItem;
//                                    $produkKomposisiFase .= "</td>";
//                                }
//                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i>  tambah</button>";
//                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                $produkKomposisiFase .= "</tr>";
//
//
//                                $produkKomposisiFase .= "</tbody>";
//                                $produkKomposisiFase .= "</table>";
//                                $produkKomposisiFase .= "</form>";
//                                $produkKomposisiFase .= "</div>";//clas panel
//                                $produkKomposisiFase .= "</div>";
//                                break;
//                            case "target":
//                                // $idForm = "target" . $fase_urut;
//                                // $produkKomposisiFase .= "<div class=''>";
//                                // $produkKomposisiFase .= "<table class='table table-bordered'>";
//                                // $produkKomposisiFase .= "<thead>";
//                                // $produkKomposisiFase .= "<tr>";
//                                // foreach ($hLabelData as $hField => $hLabel) {
//                                //     $produkKomposisiFase .= "<td>$hLabel</td>";
//                                // }
//                                //
//                                // $produkKomposisiFase .= "</tr>";
//                                // $produkKomposisiFase .= "</thead>";
//                                // $produkKomposisiFase .= "<tbody>";
//                                //
//                                // $produkKomposisiFase .= "<tr>";
//                                // foreach ($hLabelData as $hField => $hLabel) {
//                                //     if (isset($produk_fase_komposisiEditable[$hField])) {
//                                //         if (isset($relTarget[$hField])) {
//                                //             $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$addFaseHasilProduksi" . "/$produkID?mode=komposisi_target&key=$hField&&fase_id=$fase_urut&value='+encodeURI(this.value)+'$targetResult'); \">";
//                                //             $strItem .= "<option value='0'> ---silahkan pilih--</option>";
//                                //             foreach ($relTarget[$hField] as $datas) {
//                                //                 $selected = isset($currentTargetWip[$produkID][$fase_urut][$hField]) && $currentTargetWip[$produkID][$fase_urut][$hField] == $datas['id'] ? "selected" : "";
//                                //                 // $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."||". $currentTargetWip[$produkID][$fase_urut][$hField]."</option>";
//                                //                 $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                //             }
//                                //             $strItem .= "</select>";
//                                //         }
//                                //
//                                //     }
//                                //
//                                //     $produkKomposisiFase .= "<td>";
//                                //     $produkKomposisiFase .= $strItem;
//                                //     $produkKomposisiFase .= "</td>";
//                                // }
//                                // $produkKomposisiFase .= "</tr>";
//                                // $produkKomposisiFase .= "</tbody>";
//                                // $produkKomposisiFase .= "</table>";
//                                // $produkKomposisiFase .= "</div>";
//                                break;
//                            case "jual":
////                                arrPrint($produk_komposisi_fase);
//
//                                if (isset($produk_komposisi_fase[$fase_urut]["jual"]) && sizeof($produk_komposisi_fase[$fase_urut]["jual"])) {
//                                    foreach ($produk_komposisi_fase[$fase_urut]["jual"] as $DataRelsupplies) {
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
//                                            $jual_per_unit += $hField=="subtotal" ? $val*1 : 0;
//                                        }
//                                    }
//                                }
//
//                                break;
//                            case "room":
//                                $produkKomposisiRoom = "";
//                                $idForm = "rooms" . "$fase_urut";
//                                $produkKomposisiRoom .= "<div class='$idForm'>";
//                                $produkKomposisiRoom .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase_room&fase_id=$fase_urut'>";
//                                $produkKomposisiRoom .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                $produkKomposisiRoom .= "<thead>";
//                                $produkKomposisiRoom .= "<tr>";
//                                $produkKomposisiRoom .= "<td>No</td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    $produkKomposisiRoom .= "<td>$hLabel</td>";
//                                }
//
//                                if($lock==0){
//                                    $produkKomposisiRoom .= "<td>action</td>";
//                                }
//
//                                $produkKomposisiRoom .= "</tr>";
//                                $produkKomposisiRoom .= "</thead>";
//                                $produkKomposisiRoom .= "<tbody>";
//                                $produkKomposisiRoom .= "<tr>";
//                                $i = 0;
//                                $arrSelectedRooms = array();
//                                if (isset($produk_komposisi_fase[$fase_urut]["room"]) && sizeof($produk_komposisi_fase[$fase_urut]["room"])) {
//                                    foreach ($produk_komposisi_fase[$fase_urut]["room"] as $DataRelsupplies) {
//                                        $tID=$DataRelsupplies["id"];
//                                        $arrSelectedRooms[] = $DataRelsupplies["produk_dasar_id"];
//                                        $produkKomposisiRoom .= "<tr>";
//                                        $i++;
//                                        $produkKomposisiRoom .= "<td>$i</td>";
//                                        $tmpNamaMaterial = '';
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
//                                            $produkKomposisiRoom .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
//                                            $tmpNamaMaterial .= $hField == "room_nama" ? $val : "";
//                                        }
//                                        $btn = "<div>";
//                                        if(isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["produk_dasar_id"]["update"] == true) {
//                                            $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorderRoom/$tID','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
//                                        }
//                                        if(isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
//                                            $btn .= "<button cx_jenis='room' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorderRoom' type='button' title='click untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                        }
//                                        $btn .="</div>";
//
//                                        if($lock==0){
//                                            $produkKomposisiRoom .= "<td>$btn</td>";
//                                        }
//
//                                        $produkKomposisiRoom .= "</tr>";
//                                    }
//                                }
//                                $produkKomposisiRoom .= "</tr>";
//
//
//                                if($lock==0){
//                                    //untuk tambah komponen
//                                    $produkKomposisiRoom .= "<tr>";
//                                    $produkKomposisiRoom .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                    foreach ($hLabelData as $hField => $hLabel) {
//                                        if (isset($produk_fase_komposisiEditable[$hField])) {
//                                            $value = isset($newData["komposisi_fase_room"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_room"][$fase_urut][$produkID][$hField] : "";
//                                            $strItem = "<input id='komposisi_fase_room_$fase_urut$hField' class='form-control' type='text' value='$value' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_room&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)) });\">";
//                                        }
//                                        else {
//                                            $value = isset($newData["komposisi_fase_room"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_room"][$fase_urut][$produkID][$hField] : "";
//                                            $defValue = is_numeric($value) ? number_format($value) : $value;
//                                            $strItem = "<span id='komposisi_fase_room_$fase_urut$hField' class='form-control'>$defValue</span>";
//                                        }
//                                        $produkKomposisiRoom .= "<td>";
//                                        $produkKomposisiRoom .= $strItem;
//                                        $produkKomposisiRoom .= "</td>";
//                                    }
//
//                                    $btnRemoveFasekomposisiRoom = "<button id='komposisi_fase_room_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                    $produkKomposisiRoom .= "<td class='text-center'>$btnRemoveFasekomposisiRoom</td>";
//                                    $produkKomposisiRoom .= "</tr>";
//                                }
//
//
//                                $produkKomposisiRoom .= "</tbody>";
//                                $produkKomposisiRoom .= "</table>";
//                                $produkKomposisiRoom .= "</form>";
//                                $produkKomposisiRoom .= "</div>";
//
//                                $produkKomposisiRoom .= "<div style='margin-top: 40px;' class='box box-success'>";
//                                $produkKomposisiRoom .= "<div class='box-header text-bold fa-2x text-blue'>
//                                DETAIL MATERIAL PER RUANGAN
//                                <div class='meta'></div>
//                                </div>";
//                                $produkKomposisiRoom .= "<div class='box-body no-padding'>";
//
//                                $produkKomposisiRoom .= "<div class='nav-tabs-custom'>";
//                                $produkKomposisiRoom .= "<div class='tab-content no-padding'>";
//
//                                if (isset($produk_komposisi_fase[$fase_urut]["room"]) && sizeof($produk_komposisi_fase[$fase_urut]["room"])) {
//                                    $produkKomposisiRoom .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";
//                                    $numTab=0;
//                                    foreach($produk_komposisi_fase[$fase_urut]["room"] as $DataRelsupplies){
//                                        $numTab++;
//                                        $val = $DataRelsupplies['room_nama'];
//                                        $selID = $DataRelsupplies['room_id'];
//                                        $selActive = $numTab== 1 ? "active" : "";
//                                        $produkKomposisiRoom .= "<li class='nav-item $selActive'>";
//                                        $produkKomposisiRoom .= "<a class='nav-link' id='cc-tab-room_$selID' data-toggle='pill' href='#tab-room_$selID' role='tab' aria-controls='cc-tab-room_$selID' aria-selected='false'>";
//                                        $produkKomposisiRoom .= "<span style='font-size: 12px;' class='text-bold text-uppercase'> <b>$val</b></span>";
//                                        $produkKomposisiRoom .= "</a>";
//                                        $produkKomposisiRoom .= "</li>";
//                                    }
//                                    $produkKomposisiRoom .= "</ul>";
//
//                                    $numTabCont=0;
//                                    foreach ($produk_komposisi_fase[$fase_urut]["room"] as $DataRelsupplies) {
//                                        $numTabCont++;
//                                        $selID = $DataRelsupplies['room_id'];
//                                        $selNama = $DataRelsupplies['room_nama'];
//                                        $selActive = $numTabCont== 1 ? "active in" : "";
//                                        $produkKomposisiRoom .= "<div class='tab-pane fade $selActive' id='tab-room_$selID'>";
//                                        foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
//                                            switch ($hFieldKey) {
//                                                case "produk_room":
//                                                    $produkKomposisiRoom .= "<div class='text-uppercase text-bold'>------------------------------- BAHAN BAKU $selNama -------------------------------</div>";
//                                                    $idForm = "rooms" . "$fase_urut$selID";
//                                                    $produkKomposisiRoom .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=produk_komposisi_fase_room&fase_id=$fase_urut&selID=$selID'>";
//                                                    $produkKomposisiRoom .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                                    $produkKomposisiRoom .= "<thead>";
//                                                    $produkKomposisiRoom .= "<tr>";
//                                                    $produkKomposisiRoom .= "<td>No</td>";
//
//                                                    foreach ($hLabelData as $hField => $hLabel) {
//                                                        $produkKomposisiRoom .= "<td>$hLabel</td>";
//                                                    }
//
//                                                    if($lock==0){
//                                                        $produkKomposisiRoom .= "<td>action</td>";
//                                                    }
//
//                                                    $produkKomposisiRoom .= "</tr>";
//                                                    $produkKomposisiRoom .= "</thead>";
//                                                    $produkKomposisiRoom .= "<tbody>";
//                                                    $produkKomposisiRoom .= "<tr>";
//                                                    $i = 0;
//
//                                                    $arrSelected = array();
//                                                    if (isset($produk_komposisi_fase[$fase_urut]["produk_room"][$selID]) && sizeof($produk_komposisi_fase[$fase_urut]["produk_room"][$selID])) {
//                                                        foreach ($produk_komposisi_fase[$fase_urut]["produk_room"][$selID] as $DataRelsupplies) {
//                                                            $tID=$DataRelsupplies["id"];
//                                                            $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
//                                                            $produkKomposisiRoom .= "<tr>";
//                                                            $i++;
//                                                            $produkKomposisiRoom .= "<td>$i</td>";
//                                                            $tmpNamaMaterial = '';
//                                                            foreach ($hLabelData as $hField => $hLabel) {
//                                                                $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                                                $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
//                                                                $produkKomposisiRoom .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
//                                                                $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
//                                                            }
//
//                                                            $btn = "";
//                                                            if(isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
//                                                                $btn .= "<div>";
//                                                                $btn .= "<button cx_jenis='room' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorderRoomProduk' type='button' title='click untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                                                $btn .="</div>";
//                                                            }
//
//                                                            if($lock==0){
//                                                                $produkKomposisiRoom .= "<td>$btn</td>";
//                                                            }
//
//                                                            $produkKomposisiRoom .= "</tr>";
//                                                        }
//                                                    }
//                                                    $produkKomposisiRoom .= "</tr>";
//
//                                                    if($lock==0){
//                                                        //untuk tambah komponen
//                                                        $produkKomposisiRoom .= "<tr>";
//                                                        $produkKomposisiRoom .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                                        foreach ($hLabelData as $hField => $hLabel) {
//                                                            if (isset($produk_fase_komposisiRoomEditable[$hField])) {
//                                                                if (isset($relSupplies[$hField])) {
//                                                                    $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room&key=$hField&value='+encodeURI(this.value)+'&selID=$selID', function(res){ eval(atob(res)) }); \">";
//                                                                    $strItem .= "<option value='0'>==PILIH==</option>";
//                                                                    $queryParams = "";
//                                                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $datas) {
//                                                                        $selected = isset($newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField]) && $newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
//                                                                        $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                                        $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                                        $strItem .= "<option $iconCheck $selected $disable value='" . $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] . "</option>";
//                                                                    }
//                                                                    $strItem .= "</select>";
//                                                                }
//                                                                else {
//                                                                    $value = isset($newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField] : "";
//                                                                    $strItem = "<input size='4' disabled id='produk_komposisi_fase_room_$fase_urut$selID$hField' class='form-control form-control-sm text-red text-bolds text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room&key=$hField&value='+encodeURI(removeCommas(this.value))+'&selID=$selID', function(res){ eval(atob(res)) });\">";
//                                                                }
//                                                            }
//                                                            else {
//                                                                $value = isset($newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField] : "";
//                                                                $defValue = is_numeric($value) ? number_format($value) : $value;
//                                                                $strItem = "<span id='produk_komposisi_fase_room_$fase_urut$selID$hField' class='form-control form-control-sm no-border text-red text-bolds text-right'>$defValue</span>";
//                                                            }
//                                                            $produkKomposisiRoom .= "<td>";
//                                                            $produkKomposisiRoom .= $strItem;
//                                                            $produkKomposisiRoom .= "</td>";
//                                                        }
//
//
//                                                        $btnRemoveFasekomposisi = "<button id='produk_komposisi_fase_room_$fase_urut$selID$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                                        $produkKomposisiRoom .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                                        $produkKomposisiRoom .= "</tr>";
//                                                    }
//
//                                                    $produkKomposisiRoom .= "</tbody>";
//                                                    $produkKomposisiRoom .= "</table>";
//                                                    $produkKomposisiRoom .= "</form>";
//
//                                                    break;
//                                                case "biaya_room":
//
//                                                    $produkKomposisiRoom .= "<div class='text-uppercase text-bold'>------------------------------- BIAYA/JASA $selNama -------------------------------</div>";
//
//                                                    $idForm = "biayarooms" . "$fase_urut$selID";
//                                                    $produkKomposisiRoom .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=produk_komposisi_fase_room_biaya&fase_id=$fase_urut&selID=$selID'>";
//                                                    $produkKomposisiRoom .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                                    $produkKomposisiRoom .= "<thead>";
//                                                    $produkKomposisiRoom .= "<tr>";
//                                                    $produkKomposisiRoom .= "<td>No</td>";
//                                                    foreach ($hLabelData as $hField => $hLabel) {
//                                                        $produkKomposisiRoom .= "<td>$hLabel</td>";
//                                                    }
//
//                                                    if($lock==0){
//                                                        $produkKomposisiRoom .= "<td>action</td>";
//                                                    }
//
//                                                    $produkKomposisiRoom .= "</tr>";
//                                                    $produkKomposisiRoom .= "</thead>";
//                                                    $produkKomposisiRoom .= "<tbody>";
//                                                    $produkKomposisiRoom .= "<tr>";
//                                                    $i = 0;
//
//                                                    $arrSelected = array();
//                                                    if (isset($produk_komposisi_fase[$fase_urut]["biaya_room"][$selID]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya_room"][$selID])) {
//                                                        foreach ($produk_komposisi_fase[$fase_urut]["biaya_room"][$selID] as $DataRelsupplies) {
//                                                            $tID=$DataRelsupplies["id"];
//                                                            $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
//                                                            $produkKomposisiRoom .= "<tr>";
//                                                            $i++;
//                                                            $produkKomposisiRoom .= "<td>$i</td>";
//                                                            $tmpNamaMaterial = '';
//                                                            foreach ($hLabelData as $hField => $hLabel) {
//                                                                $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                                                $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
//                                                                $produkKomposisiRoom .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
//                                                                $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
//                                                            }
//                                                            $btn = "<div>";
//                                                            if(isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
//                                                                $btn .= "<button cx_jenis='room' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorderRoomProdukBiaya' type='button' title='click untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                                            }
//                                                            $btn .="</div>";
//
//                                                            if($lock==0){
//                                                                $produkKomposisiRoom .= "<td>$btn</td>";
//                                                            }
//
//                                                            $produkKomposisiRoom .= "</tr>";
//                                                        }
//                                                    }
//                                                    $produkKomposisiRoom .= "</tr>";
//
//                                                    if($lock==0){
//                                                        arrPrint($produk_komposisi_fase[$fase_urut]["biaya"]);
//                                                        //untuk tambah komponen
//                                                        $produkKomposisiRoom .= "<tr>";
//                                                        $produkKomposisiRoom .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                                        foreach ($hLabelData as $hField => $hLabel) {
//                                                            if (isset($produk_fase_komposisiRoomEditable[$hField])) {
//                                                                if (isset($relSupplies[$hField])) {
////                                                                    $hField = $hField=="produk_dasar_id" ? "id" : $hField;
//                                                                    $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room_biaya&key=$hField&value='+encodeURI(this.value)+'&selID=$selID', function(res){ eval(atob(res)) }); \">";
//                                                                    $strItem .= "<option value='0'>==PILIH==</option>";
//                                                                    $queryParams = "";
//                                                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $datas) {
//                                                                        $selected = isset($newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField]) && $newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
//                                                                        $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                                        $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                                        $ketRoom = $datas['keterangan'] != '' ? "(" . $datas['keterangan'] . ")" : "";
//                                                                        $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] ."-". $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] ." $ketRoom</option>";
//                                                                    }
//                                                                    $strItem .= "</select>";
//                                                                }
//                                                                else {
//                                                                    $value = isset($newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField] : "";
//                                                                    $strItem = "<input size='4' disabled id='produk_komposisi_fase_room_biaya_$fase_urut$selID$hField' class='form-control form-control-sm text-red text-bolds text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room_biaya&key=$hField&value='+encodeURI(removeCommas(this.value))+'&selID=$selID', function(res){ eval(atob(res)) });\">";
//                                                                }
//                                                            }
//                                                            else {
//                                                                $value = isset($newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField] : "";
//                                                                $defValue = is_numeric($value) ? number_format($value) : $value;
//                                                                $strItem = "<span id='produk_komposisi_fase_room_biaya_$fase_urut$selID$hField' class='form-control form-control-sm no-border text-red text-bolds text-right'>$defValue</span>";
//                                                            }
//                                                            $produkKomposisiRoom .= "<td>";
//                                                            $produkKomposisiRoom .= $strItem;
//                                                            $produkKomposisiRoom .= "</td>";
//                                                        }
//
//                                                        $btnRemoveFasekomposisi = "<button id='produk_komposisi_fase_room_biaya_$fase_urut$selID$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                                        $produkKomposisiRoom .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                                        $produkKomposisiRoom .= "</tr>";
//                                                    }
//
//
//                                                    $produkKomposisiRoom .= "</tbody>";
//                                                    $produkKomposisiRoom .= "</table>";
//                                                    $produkKomposisiRoom .= "</form>";
//
//                                                    break;
//                                            }
//                                        }
//
//                                        $produkKomposisiRoom .= "<div class='text-uppercase text-bold'>------------------------------- $selNama -------------------------------</div>";
//
//                                        $produkKomposisiRoom .= "</div>";
//                                    }
//                                }
//                                $produkKomposisiRoom .= "</div>";
//                                $produkKomposisiRoom .= "</div>";
//
//                                $produkKomposisiRoom .= "</div>";
//                                $produkKomposisiRoom .= "</div>";
//
//                                $room_data = $faseData['nama'];
//
//                                $collapsed_box = count($arrSelectedRooms)>0 ? "" : "collapsed-box";
//                                $collapsed_checked = count($arrSelectedRooms)>0 ? "checked" : "";
//
//                                $disableCheckSparatedRoom = $lock==0 ? "" : "disabled";
//
//                                $produkKomposisiFase .= "
//                                    <div class='box box-warning box-solid $collapsed_box'>
//                                        <div class='box-header with-border'>
//                                            <h3 class='box-title text-bold text-black'>
//                                                SEPARATED ROOM &nbsp;
//                                                <input $disableCheckSparatedRoom $collapsed_checked pids='$fase_urut' id='spr_$fase_urut' type='checkbox' class='minimal spr_check'>
//                                                <div class='small text-bold text-white'>Silahkan di centang jika anda memerlukan pendistribusian bahan baku yang lebih rinci di setiap ruangan</div>
//                                            </h3>
//                                            <button id='btnColls_$fase_urut' type='button' class='btn btn-box-tool hidden' data-widget='collapse'><i class='fa fa-minus'></i></button>
//                                        </div>
//                                        <div class='box-body'>
//                                            <div class='text-bold fa-2x'>$room_data</div>
//                                            <div class='text-bolds text-red'><i>Tambahkan nama ruangan untuk mendistribusikan bahan baku lebih rinci di setiap ruangan</i></div>
//                                            $produkKomposisiRoom
//                                        </div>
//                                    </div>
//                                ";
//
//                                break;
//                        }
//                    }
//
//                    $produkKomposisiFase .= "<div style='padding: 5px;' class='bg-info'>";
//                    $produkKomposisiFase .= "<div style='margin-top: 15px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-bold'>Total BOM @Unit :</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format($total_anggaran_unit)."</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
//                    $produkKomposisiFase .= "</div>";
//
//                    $defValue = $jual_per_unit*1>0 && $jual_per_unit*1 > $total_anggaran_unit ? $jual_per_unit*1 : $total_anggaran_unit;
//
//                    $produkKomposisiFase .= "<div style='margin-top: 5px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-bold'>Jual Project @Unit (Rp): </span></div>";
//                    $disableInputSetJual = $lock==0 ? "" : "disabled";
//
//                    $produkKomposisiFase .= "<div class='col-md-3 no-padding text-right' id=''>
//                    <input $disableInputSetJual id='hbom_$produkID$fase_urut' class='hidden' value='$total_anggaran_unit'>
//                    <input $disableInputSetJual id='saved_jual_$produkID$fase_urut' class='hidden' value='$jual_per_unit'>
//                    <input $disableInputSetJual pidfase='$produkID$fase_urut' id='hjp_$produkID$fase_urut' class='form-control form-control-sm text-bold text-right int_jual_project' style='font-size: 16px;padding-right: 14px !important;' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(removeCommas(this.value))\" value='".number_format($defValue)." '>
//                    </div>";
//
//                    $btn_danger  = "hidden";
//                    $btn_success = "hidden";
//                    $btn_save    = "hidden";
//
//                    if($jual_per_unit*1>0 && $jual_per_unit*1 > $total_anggaran_unit){
//                        $btn_danger = "hidden";
//                        $btn_success = "";
//                        $btn_save = "hidden";
//                    }
//
//                    if($jual_per_unit*1==0 && $jual_per_unit*1 < $total_anggaran_unit){
//                        $btn_danger = "hidden";
//                        $btn_success = "hidden";
//                        $btn_save = "";
//                    }
//
//                    if($jual_per_unit*1>0 && $jual_per_unit*1 < $total_anggaran_unit){
//                        $btn_danger = "";
//                        $btn_success = "hidden";
//                        $btn_save = "hidden";
//                    }
//
//                    if($jual_per_unit*1>0 && $jual_per_unit*1 == $total_anggaran_unit){
//                        $btn_danger = "hidden";
//                        $btn_success = "";
//                        $btn_save = "hidden";
//                    }
//
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id='md1_btnSave_$produkID$fase_urut'>
//                        <span class='btn btn-sm btn-danger $btn_danger' disabled><i class='fa fa-times'></i></span>
//                        <span class='btn btn-sm btn-success $btn_success' disabled><i class='fa fa-check-circle'></i></span>
//                        <span class='btn btn-sm btn-warning save_jual_project $btn_save' cx_url='$saveJualProject?mode=save_jual_project&fase_id=$fase_urut' produk_id='$produkID' fase_id='$fase_urut'><i class='fa fa-save'></i></span>
//                    </div>";
//
//                    $produkKomposisiFase .= "</div>";
//
//                    $diskon_value = 0;
//
////                    $produkKomposisiFase .= "<div style='margin-top: 5px;' class='row'>";
////                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-bold'>Diskon (Rp): </span></div>";
////                    $produkKomposisiFase .= "<div class='col-md-3 no-padding text-right' id=''>
////                        <input $disableInputSetJual produk_id='$produkID' fase_id='$fase_urut' pidfase='$produkID$fase_urut' id='diskon_$produkID$fase_urut' cx_url='$saveJualProject?mode=save_diskon&fase_id=$fase_urut' class='form-control form-control-sm text-bold text-right int_diskon' style='font-size: 16px;padding-right: 14px !important;' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(removeCommas(this.value))\" value='".number_format($diskon_value)."'>
////                    </div>";
//
////                    $produkKomposisiFase .= "</div>";
//
////                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
////                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''>&nbsp;</div>";
////                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'>______________________</div>";
////                    $produkKomposisiFase .= "<div class='col-md-1 no-padding text-left' id=''><span style='font-size: 12px;' class='text-bold'><i class='fa fa-plus'></i></span></div>";
////                    $produkKomposisiFase .= "</div>";
//
////                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
////                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>SUBTOTAL:</span></div>";
////                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format($defValue-$diskon_value)."</span></div>";
////                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
////                    $produkKomposisiFase .= "</div>";
//
//                    //==========================================================
//
//                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>PPN(".$this->session->login['ppnFactor']."%):</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format((($defValue-$diskon_value)*$this->session->login['ppnFactor'])/100)."</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
//                    $produkKomposisiFase .= "</div>";
//
//                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''>&nbsp;</div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'>______________________</div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 no-padding text-left' id=''><span style='font-size: 12px;' class='text-bold'><i class='fa fa-plus'></i></span></div>";
//                    $produkKomposisiFase .= "</div>";
//
//                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>GRAND TOTAL: </span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format($defValue-$diskon_value+((($defValue-$diskon_value)*$this->session->login['ppnFactor'])/100))."</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
//                    $produkKomposisiFase .= "</div>";
//
//                    //==========================================================
//
//                    $produkKomposisiFase .= "<div style='margin-top: 25px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>Total R/L @Unit :</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format($jual_per_unit-$total_anggaran_unit)."</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
//                    $produkKomposisiFase .= "</div>";
//
//                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>Total R/L :</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format(($jual_per_unit-$total_anggaran_unit)*$faseData['qty'])."</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
//                    $produkKomposisiFase .= "</div>";
//
//                    $produkKomposisiFase .= "</div>";
//
//                    //BOM / KOMPOSISI PAKET
//                    $produkKomposisiFase .= "<div style='margin-top: 30px;margin-bottom: 6px;' class='fa-2x text-muted'> <i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;PAKET INSTALASI&nbsp;&nbsp;&nbsp;
//                                                <div class='box-tools pull-right'>
//                                                    <button type='button' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> TAMBAH PAKET</button>
//                                                </div>
//                                             </div>";
//                    $produkKomposisiFase .= "<div class='box-group bom-container' id='accordion'>";
//
//                    if (sizeof($produk_paket) > 0) {
//                        foreach ($produk_paket as $pp_key => $faseData) {
//
//                            $produkKomposisiFase .= "<div class='box box-solid box-info'>";
//                            $produkKomposisiFase .= "<div class='box-header with-border'>";
//                            $produkKomposisiFase .= "<h3 class='box-title'><i class='fa fa-tags'></i>&nbsp;&nbsp;".($faseData->nama)."&nbsp;&nbsp;&nbsp;</h3>";
//                            $produkKomposisiFase .= "<div class='box-tools'>";
//
//                            if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
//                                $produkKomposisiFase .= "<button cx_jenis='produk' cx_tmpnamamaterial='".($faseData->nama)."' cx_deletelink='$deleteLink' cx_tid='$pp_key' cx_mdl='ProjectProdukPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger btn-box-tool delWorkOrder'><i class='fa fa-trash'></i> Hapus Paket</button>";
//                                $produkKomposisiFase .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//                            }
//
//                            $produkKomposisiFase .= "<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
//                            $produkKomposisiFase .= "</div>";
//                            $produkKomposisiFase .= "</div>";
//
//                            $produkKomposisiFase .= "<div class='box-body'>";
//
//                            $total_anggaran_unit = 0;
//                            $jual_per_unit = 0;
//
//                            foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
//                                switch ($hFieldKey) {
//                                    case "produk":
//                                        $idForm = "komposisi_bom" . "$pp_key";
//                                        $produkKomposisiFase .= "<div class='$idForm'>";
//                                        $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiBomLink?mode=komposisi_bom&paket_id=$pp_key&fase_id=$fase_urut'>";
//                                        $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                        $produkKomposisiFase .= "<thead>";
//                                        $produkKomposisiFase .= "<tr>";
//                                        $produkKomposisiFase .= "<td>No</td>";
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $produkKomposisiFase .= "<td>$hLabel</td>";
//                                        }
//                                        $produkKomposisiFase .= "<td>action</td>";
//                                        $produkKomposisiFase .= "</tr>";
//                                        $produkKomposisiFase .= "</thead>";
//                                        $produkKomposisiFase .= "<tbody>";
//                                        $produkKomposisiFase .= "<tr>";
//                                        $i = 0;
//                                        $arrSelected = array();
//                                        if (isset($produk_komposisi_bom[$pp_key]["produk"]) && sizeof($produk_komposisi_bom[$pp_key]["produk"])) {
//                                            foreach ($produk_komposisi_bom[$pp_key]["produk"] as $DataRelsupplies) {
//                                                $tID=$DataRelsupplies["id"];
//                                                $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
//                                                $produkKomposisiFase .= "<tr>";
//                                                $i++;
//                                                $produkKomposisiFase .= "<td>$i</td>";
//                                                $tmpNamaMaterial = '';
//                                                foreach ($hLabelData as $hField => $hLabel) {
//                                                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                                    $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
//
//                                                    $origVal=$val;
//                                                    $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;
//
//                                                    $produkKomposisiFase .= "<td hField='$hField' title='$origVal'>" . formatField($hField, $val) . "</td>";
//                                                    $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val : "";
//                                                    $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
//                                                }
//                                                $btn = "<div>";
////                                                if(isset($allowedAccess["produk_paket"]["update"]) && $allowedAccess["produk_paket"]["update"] == true) {
////                                                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
////                                                }
//                                                if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
//                                                    $btn .= "<button cx_jenis='produk' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProdukKomposisiPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                                }
//                                                $btn .="</div>";
//
//                                                $produkKomposisiFase .= "<td >$btn</td>";
//
//                                                $produkKomposisiFase .= "</tr>";
//                                            }
//                                        }
//
//                                        //untuk tambah komponen
//                                        $produkKomposisiFase .= "</tr>";
//                                        $produkKomposisiFase .= "<tr>";
//                                        $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
////                                        cekMerah("=============================");
////                                        arrPrintWebs($hLabelData);
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            if ($hField=="produk_dasar_id") {
//                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom&paket_id=$pp_key&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                                $strItem .= "<option value='0'>==PILIH==</option>";
//                                                $queryParams = "";
//                                                foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $datas) {
//                                                    $selected = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) && $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
//                                                    $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                    $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] . "</option>";
//                                                }
//                                                $strItem .= "</select>";
//                                            }
//                                            else if ($hField=="jml") {
//                                                $value = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] : "";
//                                                $strItem = "<input id='komposisi_bom_$fase_urut$pp_key$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom&paket_id=$pp_key&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                            }
//                                            else {
//                                                $value = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] : "";
//                                                $defValue = is_numeric($value) ? number_format($value) : $value;
//                                                $strItem = "<span id='komposisi_bom_$fase_urut$pp_key$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                            }
//                                            $produkKomposisiFase .= "<td>";
//                                            $produkKomposisiFase .= $strItem;
//                                            $produkKomposisiFase .= "</td>";
//                                        }
//
//                                        $btnRemoveFasekomposisi = "<button id='komposisi_bom_$fase_urut$pp_key$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                        $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                        $produkKomposisiFase .= "</tr>";
//
//                                        $produkKomposisiFase .= "</tbody>";
//                                        $produkKomposisiFase .= "</table>";
//                                        $produkKomposisiFase .= "</form>";
//                                        $produkKomposisiFase .= "</div>";
//                                        break;
//                                    case "biaya":
//                                        $idForm = "biaya" . "$pp_key";
//                                        $produkKomposisiFase .= "<div class='$idForm'>";
//                                        $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiBomBiayaLink?mode=komposisi_bom_biaya&paket_id=$pp_key&fase_id=$fase_urut'>";
//                                        $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                        $produkKomposisiFase .= "<thead>";
//                                        $produkKomposisiFase .= "<tr>";
//                                        $produkKomposisiFase .= "<td>No</td>";
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            $produkKomposisiFase .= "<td>$hLabel</td>";
//                                        }
//
//                                        $produkKomposisiFase .= "<td>Action</td>";
//                                        $produkKomposisiFase .= "</tr>";
//
//                                        $produkKomposisiFase .= "</thead>";
//                                        $produkKomposisiFase .= "<tbody>";
//                                        //bagaian data relasi komposisi
//                                        $i = 0;
//                                        $arrSelected = array();
//                                        if (isset($produk_komposisi_bom[$pp_key]["biaya"]) && sizeof($produk_komposisi_bom[$pp_key]["biaya"]) > 0) {
//                                            foreach ($produk_komposisi_bom[$pp_key]["biaya"] as $DataRelsuppliesBiaya) {
//                                                $tID=$DataRelsuppliesBiaya["id"];
//                                                $arrSelected[] = $DataRelsuppliesBiaya["produk_dasar_id"];
//                                                $produkKomposisiFase .= "<tr>";
//                                                $i++;
//                                                $tmpNamaMaterial = '';
//                                                $produkKomposisiFase .= "<td>$i</td>";
//                                                foreach ($hLabelData as $hField => $hLabel) {
//                                                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
//                                                    $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
//
//                                                    $origVal=$val;
//                                                    $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;
//
//                                                    $produkKomposisiFase .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
//                                                    $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val : "";
//                                                    $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
//                                                }
//
//                                                $btn = "<div>";
//                                                if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
//                                                    $btn .= "<button cx_jenis='biaya' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProdukKomposisiPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
//                                                }
//                                                $btn .="</div>";
//
//                                                $produkKomposisiFase .= "<td >$btn</td>";
//
//                                                $produkKomposisiFase .= "</tr>";
//                                            }
//                                        }
//
//                                        //untuk tambah komponen
//                                        $produkKomposisiFase .= "<tr>";
//                                        $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                        foreach ($hLabelData as $hField => $hLabel) {
//                                            if ($hField=="produk_dasar_id") {
//                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom_biaya&paket_id=$pp_key&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                                $strItem .= "<option value='0'>==PILIH==</option>";
//                                                foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $datas) {
//                                                    $selected = isset($newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField]) && $newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField] == $datas['id'] ? "selected" : "";
//                                                    $disable = "";
//                                                    $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['produk_dasar_nama'] . "</option>";
//                                                }
//                                                $strItem .= "</select>";
//                                            }
//                                            else if($hField=="jml"){
//                                                $value = isset($newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField] : "";
//                                                $strItem = "<input id='komposisi_bom_biaya_$fase_urut$pp_key$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom_biaya&paket_id=$pp_key&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                            }
//                                            else {
//                                                $value = isset($newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField] : "";
//                                                $defValue = is_numeric($value) ? number_format($value) : $value;
//                                                $strItem = "<span id='komposisi_bom_biaya_$fase_urut$pp_key$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                            }
//
//                                            $produkKomposisiFase .= "<td>";
//                                            $produkKomposisiFase .= $strItem;
//                                            $produkKomposisiFase .= "</td>";
//                                        }
//                                        $btnRemoveFasekomposisi = "<button id='komposisi_bom_biaya_$fase_urut$pp_key$produkID' idform='$idForm' disabled type='button' title='simpan komposisi biaya' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                        $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                        $produkKomposisiFase .= "</tr>";
//
//                                        $produkKomposisiFase .= "</tbody>";
//                                        $produkKomposisiFase .= "</table>";
//                                        $produkKomposisiFase .= "</form>";
//                                        $produkKomposisiFase .= "</div>";
//                                        break;
//                                }
//                            }
//
//                            $produkKomposisiFase .= "</div>";
//                            $produkKomposisiFase .= "</div>";
//                            $produkKomposisiFase .= "</div>";
//
//
//                        }
//                    }
//                    else{
//                        $produkKomposisiFase .= "<div class='box box-danger box-solid box-header text-center text-bold'> SILAHKAN BUAT BOM DULU </div>";
//
//                        $produkKomposisiFase .= "
//                            <script>
//                                setTimeout(function(){
//                                    top.$('#produk_fase_qty').val(1).trigger('blur');
//                                    top.$('#produk_fase_nama').val('".$masterProject['nama']."').trigger('blur');
//                                    top.$('#produk_fase_lokasi').val('-').trigger('blur');
//                                    setTimeout(function(){
//                                        $('#addProdukFase').click();
//                                    },500);
//                                },1000);
//                            </script>";
//                    }
//
//                    $produkKomposisiFase .= "</div>";
//                    $produkKomposisiFase .= "</div>";
////                    $produkKomposisiFase .= "</div>";
////                    $produkKomposisiFase .= "</div>";
//                    //SAMPE SINI SAJA REPLACE BOM
//
//                }
//                else {
//                    $produkKomposisiFase .= "<div class='bg-ble lv12 tab-pane fade active in' id='tab-fase_$fase_urut'>";
//                    $produkKomposisiFase .= "<div class='blink text-bold text-danger'><h4 class=''>Material " . ($faseData['nama']) . " belum diseting, silahkan klik tombol tambah</h3></div>";
//                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
//                        switch ($hFieldKey) {
//                            case "produk":
//                                $idForm = "bahan_baku" . "$fase_urut";
//                                $produkKomposisiFase .= "<div class='$idForm'>";
//                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut'>";
//                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                $produkKomposisiFase .= "<thead>";
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td>No</td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    $produkKomposisiFase .= "<td>$hLabel</td>";
//                                }
//                                $produkKomposisiFase .= "<td>action</td>";
//                                $produkKomposisiFase .= "</tr>";
//                                $produkKomposisiFase .= "</thead>";
//                                $produkKomposisiFase .= "<tbody>";
//                                $produkKomposisiFase .= "<tr>";
//
//                                $arrSelected = array();
//
//                                $i = 0;
//                                //untuk tambah komponen
//                                $produkKomposisiFase .= "</tr>";
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    if (isset($produk_fase_komposisiEditable[$hField])) {
//                                        if (isset($relSupplies[$hField])) {
//                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                            $strItem .= "<option value='0'>==PILIH==</option>";
//                                            $queryParams = "";
//                                            foreach ($relSupplies[$hField] as $datas) {
//                                                $selected = isset($newData["komposisi_bom"][$fase_urut][$produkID][$hField]) && $newData["komposisi_bom"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
//                                                $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                            }
//                                            $strItem .= "</select>";
//                                        }
//                                        else {
//                                            $value = isset($newData["komposisi_bom"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$produkID][$hField] : "";
//                                            $strItem = "<input id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                        }
//                                    }
//                                    else {
//                                        $value = isset($newData["komposisi_bom"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$produkID][$hField] : "";
//                                        $defValue = is_numeric($value) ? number_format($value) : $value;
//                                        $strItem = "<span id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                    }
//                                    $produkKomposisiFase .= "<td>";
//                                    $produkKomposisiFase .= $strItem;
//                                    $produkKomposisiFase .= "</td>";
//                                }
//
//
//                                $btnRemoveFasekomposisi = "<button id='komposisi_fase_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                $produkKomposisiFase .= "</tr>";
//
//                                $produkKomposisiFase .= "</tbody>";
//                                $produkKomposisiFase .= "</table>";
//                                $produkKomposisiFase .= "</form>";
//                                $produkKomposisiFase .= "</div>";
//                                break;
//                            case "biaya":
//                                $idForm = "biaya" . "$fase_urut";
//                                $produkKomposisiFase .= "<div class='$idForm'>";
//                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut'>";
//                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
//                                $produkKomposisiFase .= "<thead>";
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td>No</td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    $produkKomposisiFase .= "<td>$hLabel</td>";
//                                }
//                                $produkKomposisiFase .= "<td>Action</td>";
//                                $produkKomposisiFase .= "</tr>";
//
//                                $produkKomposisiFase .= "</thead>";
//                                $produkKomposisiFase .= "<tbody>";
//                                //bagaian data relasi komposisi
//                                // arrprint($relBiaya);
//                                $i = 0;
//                                //bagian add baru
//                                //untuk tambah komponen
//                                $produkKomposisiFase .= "<tr>";
//                                $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                foreach ($hLabelData as $hField => $hLabel) {
//                                    if (isset($produk_fase_komposisiEditable[$hField])) {
//                                        if (isset($relBiaya[$hField])) {
//                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                            $strItem .= "<option value='0'>==PILIH==</option>";
//                                            foreach ($relBiaya[$hField] as $datas) {
//                                                $selected = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
//                                                $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
//                                                $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                            }
//                                            $strItem .= "</select>";
//                                        }
//                                        else {
//                                            $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
//                                            $strItem = "<input id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                        }
//                                    }
//                                    else {
//                                        $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
//                                        $defValue = is_numeric($value) ? number_format($value) : $value;
//                                        $strItem = "<span id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                    }
//                                    $produkKomposisiFase .= "<td>";
//                                    $produkKomposisiFase .= $strItem;
//                                    $produkKomposisiFase .= "</td>";
//                                }
//                                $btnRemoveFasekomposisi = "<button id='komposisi_fase_biaya_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi biaya' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
//                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                $produkKomposisiFase .= "</tr>";
//
//
//                                $produkKomposisiFase .= "</tbody>";
//                                $produkKomposisiFase .= "</table>";
//                                $produkKomposisiFase .= "</form>";
//                                $produkKomposisiFase .= "</div>";
//                                break;
//                        }
//                    }
//                    $produkKomposisiFase .= "</div>";
//                }
//            }
//
//            $produkKomposisiFase .= "</div>"; //box-body
//            $produkKomposisiFase .= "</div>";
//
//        }
//        else{
////            $produkKomposisiFase .= "<div class='box box-danger box-solid box-header text-center text-bold'> SILAHKAN BUAT RENCANA KERJA DULU </div>";
////
////            $produkKomposisiFase .= "
////            <script>
////                setTimeout(function(){
////                    top.$('#produk_fase_qty').val(1).trigger('blur');
////                    top.$('#produk_fase_nama').val('".$masterProject['nama']."').trigger('blur');
////                    top.$('#produk_fase_lokasi').val('-').trigger('blur');
////                    setTimeout(function(){
////                        $('#addProdukFase').click();
////                    },500);
////                },1000);
////            </script>";
//        }
//
//
//        $produkKomposisiFase .= "
//        <script>
//
//            $('.title_xedit').editable({
//                url: '".base_url()."master_project/MasterData/exeEditable'
//            });
//
//            $('.xedit').on('click', function(e){
//                var pid = $(this).attr('data-produk_id');
//                var nama = $(this).attr('data-sub_nama');
//                var furut = $(this).attr('data-fase_urut');
//                var sfurut = $(this).attr('data-sub_fase_urut');
//                var href = $(this).attr('data-href');
//                e.stopPropagation();
//                e.preventDefault();
//                console.log('pid: '+pid+'| nama: '+nama+'| furut: '+furut+'| sfurut: '+sfurut+'| href: '+href);
//                $('.title_xedit[href=\''+href+'\']').editable('toggle')
//                console.log( $('.title_xedit[href=\''+href+'\']') );
//            })
//
//            $('.spr_check').on('ifChanged', function(event){
//                var pids = $(this).attr('pids');
//                if( $(this).is(':checked') ){
//                    $('#btnColls_'+pids).trigger('click');
//                }
//                else{
//                    event
//                    swal('mematikan mode separated room', 'dengan mematikan mode separated room, data yang pernah tersimpan akan dihapus, apakah anda yakin dengan hal ini??', 'question')
//                    .then( (res)=>{
//                        if(res){
//                            $('#btnColls_'+pids).trigger('click');
//                        }
//                    })
//                    .then((aaa)=>{
//                        console.log(aaa);
//                    })
//                }
//            });
//
//            $('input[type=\"checkbox\"].minimal, input[type=\"radio\"].minimal').iCheck({
//              checkboxClass: 'icheckbox_minimal-blue',
//              radioClass: 'iradio_minimal-blue'
//            });
//
//            function init_int_jual_project(){
//                $('.int_jual_project').off();
//                $('.int_jual_project').on('keyup', function(){
//                    var pidfase     = $(this).attr('pidfase');
//                    var cur         = removeCommas( $(this).val() );
//                    var ori         = $('#hbom_'+pidfase).val();
//                    var saved       = $('#saved_jual_'+pidfase).val();
//                    var btn_danger  = $('#md1_btnSave_'+pidfase+' > span.btn-danger');
//                    var btn_success = $('#md1_btnSave_'+pidfase+' > span.btn-success');
//                    var btn_save    = $('#md1_btnSave_'+pidfase+' > span.btn-warning');
//
//                    if( cur*1 > ori*1 && saved!=cur ){
////                        console.log('oke');
//                        $(btn_danger).addClass('hidden');
//                        $(btn_success).addClass('hidden');
//                        $(btn_save).removeClass('hidden');
//                        $(this).addClass('text-green');
//                        $(this).removeClass('text-red');
//                        $(this).removeClass('text-orange');
//                    }
//                    else if( cur*1 < ori*1 ){
////                        console.error('not good');
//                        $(btn_danger).removeClass('hidden');
//                        $(btn_success).addClass('hidden');
//                        $(btn_save).addClass('hidden');
//                        $(this).addClass('text-red');
//                        $(this).removeClass('text-orange');
//                        $(this).removeClass('text-green');
//                    }
//                    else if( cur*1 == saved*1 ){
////                        console.log('no change');
//                        $(btn_danger).addClass('hidden');
//                        $(btn_success).removeClass('hidden');
//                        $(btn_save).addClass('hidden');
//                        $(this).removeClass('text-green');
//                        $(this).removeClass('text-red');
//                        $(this).removeClass('text-orange');
//                    }
//                    else if( cur*1 < saved*1 && cur*1 == ori*1){
////                        console.log('price change down');
//                        $(btn_danger).addClass('hidden');
//                        $(btn_success).addClass('hidden');
//                        $(btn_save).removeClass('hidden');
//
//                        $(this).removeClass('text-green');
//                        $(this).removeClass('text-red');
//                        $(this).addClass('text-orange');
//                    }
//                    else{
////                        console.log('kondisi embuh');
////                        console.log('kondisi embuh');
//                        $(btn_danger).addClass('hidden');
//                        $(btn_success).addClass('hidden');
//                        $(btn_save).addClass('hidden');
//                        $(btn_save).trigger('click');
//                    }
//
//                });
//
//                $('.int_jual_project').trigger('keyup');
//            }
//
//            function init_saveHrgProject(){
//                $('.save_jual_project').on('click', function(){
//                    var cx_url      = $(this).attr('cx_url');
//                    var produk_id   = $(this).attr('produk_id');
//                    var fase_id     = $(this).attr('fase_id');
//                    var hjp         = $('#hjp_'+produk_id+''+fase_id).val();
//                    var hbom         = $('#hbom_'+produk_id+''+fase_id).val();
//                    (async () => {
//                      const rawResponse = await fetch(cx_url, {
//                        method: 'POST',
//                        body: JSON.stringify({jual_project: removeCommas(hjp),hbom: removeCommas(hbom)})
//                      });
//                      const content = await rawResponse.json();
//
//                      if(content.status){
//                            eval(content.js)
//                      }
//
//                    })();
//                });
//            }
//
//            function init_saveDiscProject(){
//                $('.int_diskon').on('keyup', delay_v2(function(){
//                    var cx_url      = $(this).attr('cx_url');
//                    var produk_id   = $(this).attr('produk_id');
//                    var fase_id     = $(this).attr('fase_id');
//                    var diskon         = $('#diskon_'+produk_id+''+fase_id).val();
//                    (async () => {
//                      const rawResponse = await fetch(cx_url, {
//                        method: 'POST',
//                        body: JSON.stringify({diskon_project: removeCommas(diskon)})
//                      });
//                      const content = await rawResponse.json();
//                      if(content.status){
//                            eval(content.js)
//                      }
//                    })();
//                },2500));
//            }
//
//            function preLoadFase(){
//                $('.container_produk_fase').load('$modulClassLink/showProdukFase/$produkID', function(){
//                    //top.window.location.reload();
//                });
//            }
//
//            function preLoadProdukFase(){
//                $('#containerPreLoadProdukFase').load('$modulClassLink/showKomposisiProdukFase/$produkID');
//            }
//
//            function reloadSelectpicker(){
//                $('.selectpicker').selectpicker('refresh');
//            }
//
//            function reloadTombolSimpan(){
//                var formJml = document.querySelectorAll('[id*=\"jml\"]');
//                jQuery.each(formJml, function(a,b){
//                    var jml_value = $(b);
//                    if( $(jml_value).val() * 1 > 0 ){
//                        $(jml_value).trigger('blur');
//                    }
//                });
//            }
//
//            function initDeleteFunc(){
//                $('.delWorkOrder').off();
//                $('.delWorkOrder').on('click', function(){
//                    var cx_jenis = $(this).attr('cx_jenis');
//                    var cx_tmpnamamaterial = $(this).attr('cx_tmpnamamaterial');
//                    var cx_deletelink = $(this).attr('cx_deletelink');
//                    var cx_tid = $(this).attr('cx_tid');
//                    var cx_mdl = $(this).attr('cx_mdl');
//                    swal({
//                        title: 'Apakah kamu yakin untuk menghapus?',
//                        html: \"<span class='text-bold text-red text-uppercase'>\"+cx_tmpnamamaterial+\"</span> akan dihapus, data yg telah di hapus tidak bisa dikembalikan.\",
//                        type: 'question',
//                        showCancelButton: true,
//                        confirmButtonColor: '#3085d6',
//                        cancelButtonColor: '#d33',
//                        confirmButtonText: 'setuju & lanjutkan!'
//                    })
//                    .then(function(){
//                        swal('proses menghapus<br>mohon tunggu sebentar');
//                        swal.enableLoading();
//                        $.ajax({
//                            url: cx_deletelink + '' + cx_mdl + '/' + cx_tid,
//                            success: function(res){
//                                arrDatas = JSON.parse(res)
//                                if(arrDatas.status){
//                                    setTimeout(function(){
//                                        swal('SUKSES', 'data berhasil dihapus', 'success');
//                                        preLoadFase();
//                                    },1000)
//                                }
//                            }
//                        });
//                    })
//                    .catch(function(reason){
//                        //alert(\"The alert was dismissed by the user: \"+reason);
//                    });
//                })
//            }
//
//            function init_addProdukFase(){
//                top.$('#addProdukFase').off();
//                top.$('#addProdukFase').on('click', function(){
//                    var id = $(this).attr('id');
//                    var ev_aa = $(this).attr('cx_onclick');
//                    var arr_input = $('.'+id);
//                    var error = '';
//                    jQuery.each(arr_input, function(a, b){
//                        var keys = $(b).attr('key');
//                        var val = $(b).val();
//                        if( keys=='qty' && val=='' ){
//                            error += '<b><r> Jumlah </r></b> wajib di isi<br>'
//                        }
//                        if( keys=='nama' && val=='' ){
//                            error += '<b><r> Nama Rencana Kerja </r></b> wajib di isi'
//                        }
//                    });
//                    if( error=='' ){
//                        //bisa lanjut
//                        eval(ev_aa);
//                    }
//                    else{
//                        swal('isian kurang', error, 'error');
//                    }
//                });
//            }
//
//            init_addProdukFase();
//            initDeleteFunc();
//            reloadTombolSimpan();
//            init_saveHrgProject();
//            init_saveDiscProject();
//            init_int_jual_project();
//        </script>
//
//        ";
//
//        //JIKA ADA UPDATE COPAS DARI SINI SAJA

        $produkKomposisiFase .= "</div>";
        $produkKomposisiFase .= "</div>";

        $produkKomposisiFase .= "<div class='hidden' id='input_temp'></div>";

        //endregion

        //region rencana proses produksi (nama_produk)
        $produkFase = "";
        $produkFase .= "<div class='container_produk_fase'>";
        $produkFase .= "<form class='form' name='produk_fase' id='produk_fase' method='post' target='result' action='$addFaseProdukLink?mode=produk_fase'>";
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
        if (isset($produk_fase) && count($produk_fase)) {
            $i = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                $i++;
                $produkFase .= "<tr>";
                $produkFase .= "<td>$i</td>";
                $tmpNamaMaterial = "";
                foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                    $newKey = isset($relSuppliesHeader[$produkfaseKey]) ? $relSuppliesHeader[$produkfaseKey] : $produkfaseKey;
                    if (isset($faseData[$newKey])) {
                        if (is_numeric($faseData[$newKey])) {
                            $fieldValue = formatField($newKey, $faseData[$newKey]);
                        }
                        else {
                            $fieldValue = $faseData[$newKey];
                        }
                    }
                    else {
                        $fieldValue = "";
                    }
                    $produkFase .= "<td>" . $fieldValue . "</td>";
                    $tmpNamaMaterial .= $newKey == "nama" ? $fieldValue : "";
                }
                //region button remove
                // $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $btn = "<div>";
                if (isset($allowedAccess["fase_id"]["update"]) && $allowedAccess["fase_id"]["update"] == true) {
                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectWorkOrder/$fase_urut/$hField','edit $labelName')\"><i class='fa fa-fw fa-edit'></i></button>";
                }
                if (isset($allowedAccess["fase_id"]["delete"]) && $allowedAccess["fase_id"]["delete"] == true) {
                    $btn .= "<button disabled cx_jenis='fase_id' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$fase_urut' cx_mdl='ProjectWorkOrder' type='button' title='click untuk menghapus relasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                }

                $btn .= "</div>";
                $produkFase .= "<td class='text-center'>$btn</td>";
                //endregion
                $produkFase .= "</tr>";
            }
            //tambahan tr untuk add data baru
        }

        if (!count($produk_fase) && $lock == 0) {
            //untuk penambahan
            $produkFase .= "<tr>";
            $produkFase .= "<td></td>";
            foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                if (isset($produk_fase_komposisiEditable[$produkfaseKey])) {
                    if (isset($relWorkOrderEmployee[$produkfaseKey])) {
                        $strTim_wo = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp_0').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value)+'$targetResult'); \">";
                        $strTim_wo .= "<option value='0'>==PILIH==</option>";
                        $ic = 0;
                        foreach ($relWorkOrderEmployee[$produkfaseKey] as $datas) {
                            $ic++;
                            $selected = isset($newData["produk_fase"][$produkID][$produkfaseKey]) && $newData["produk_fase"][$produkID][$produkfaseKey] == $datas['employee_id'] ? "selected" : "";
                            $strTim_wo .= "<option $selected value='" . $datas['employee_id'] . "'>" . $datas['employee_nama'] . "</option>";
                        }
                        $strTim_wo .= "</select>";
                    }
                    else {
                        $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
                        $readOnly = "";
                        $strTim_wo = "<input $readOnly class='form-control form-control-sm' type='text' value='$preval' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\">";
                    }
                }
                else {
                    $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
                    $readOnly = "";
                    $strTim_wo = "<input key='$produkfaseKey' $readOnly id='produk_fase_$produkfaseKey' class='form-control form-control-sm addProdukFase' type='text' value='$preval' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\">";
                }
                $produkFase .= "<td>$strTim_wo</td>";

            }
            $produkFase .= "<td> <span id='addProdukFase' class='btn btn-sm btn-success' disabled cx_onclicks=\"document.getElementById('produk_fase').submit();\"><i class='fa fa-plus'></i> tambah</span> </td>";
            $produkFase .= "</tr>";
        }

        $produkFase .= "</tbody>";
        $produkFase .= "</table>";
        $produkFase .= "</form>";
        $produkFase .= "</div>";
        //endregion rencana proses produksi (nama_produk)

        //region summary project
        // cekHitam(count($sumaryProject));
        $rincianProject = "";
        $total_anggaran = 0;
        if (count($sumaryProject) > 0) {

            $rincianProject .= "<div class='box-header'></div>";

            $rincianProject .= "<div class='panel panel-body no-padding table-responsive'>
                                    <h4>Ringkasan Anggaran</h4>
                                    <h5 class='text-bold text-muted'><i><b><r>($produkNama)</r></b></i></h5>";

            $rincianProject .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            $rincianProject .= "<thead>";
            $rincianProject .= "<tr>";
            $rincianProject .= "<th>No</th>";
            $rincianProject .= "<th>Rencana Kerja</th>";
            $rincianProject .= "<th>Qty</th>";
            $rincianProject .= "<th>@BOM</th>";
            $rincianProject .= "<th>T.BOM</th>";
            $rincianProject .= "<th>@jual</th>";
            $rincianProject .= "<th>T.JUAL</th>";
            $rincianProject .= "<th>T.R/L</th>";
            $rincianProject .= "</tr>";
            $rincianProject .= "</thead>";
            $rincianProject .= "<tbody>";

            $no = 0;
            $subtotal = 0;
            $subtotalTotal = 0;
            $subtotalTotalJual = 0;
            foreach ($produk_fase as $k => $sumarryProdukFase_0) {
                $no++;
                $anggaran_total = 0;

                foreach ($produk_komposisi_fase_sub[$k] as $jenis_ => $arrAgr0) {
                    $nilai_anggaran = 0;
                    foreach ($arrAgr0 as $jenis => $arrAgr) {
                        switch ($jenis) {
                            case "produk":
                                foreach ($arrAgr as $ks => $prdAgr) {
                                    $nilai_anggaran += $prdAgr['subtotal'];
                                }
                                break;
                            case "biaya":
                                foreach ($arrAgr as $ks => $prdAgr) {
                                    $nilai_anggaran += $prdAgr['subtotal'];
                                }
                                break;
                        }
                    }
                    $anggaran_total += $nilai_anggaran;
                }

                $subtotal = $anggaran_total;
                $subtotalRata = $anggaran_total / $sumarryProdukFase_0['qty'];
                $subtotalJual = $produk_komposisi_fase[$k]['jual'][0]['harga'];
//                $subtotalJual = $produk_komposisi_fase[$k]['jual'][0]['hpp'];
                $totalJual = $produk_komposisi_fase[$k]['jual'][0]['harga'] * $sumarryProdukFase_0['qty'];
//                $totalJual = $produk_komposisi_fase[$k]['jual'][0]['hpp']*$sumarryProdukFase_0['qty'];

//                echo json_encode($produk_komposisi_fase[$k]['jual'][0]['harga']) . "<br><br>";

                $rincianProject .= "<tr onclick=\"showHideRow($k);\" ".__LINE__.">";
                $rincianProject .= "<td class='text-center text-bold'>$no.</td>";
                $limitNama = strlen($sumarryProdukFase_0['nama']) > 25 ? substr($sumarryProdukFase_0['nama'], 0, 22) . "..." : $sumarryProdukFase_0['nama'];
                $rincianProject .= "<td class='text-left text-link text-bold' title='klik " . $sumarryProdukFase_0['nama'] . " untuk melihat detail'>" . $limitNama . "</td>";
                $rincianProject .= "<td class='text-left text-link text-bold'>" . $sumarryProdukFase_0['qty'] . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $subtotalRata) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $subtotal) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $subtotalJual) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $totalJual) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $totalJual - $subtotal) . "</td>";
                $rincianProject .= "</tr>";

                $rincianProject .= "<tr id='hr_$k' class='hidden'>";
                $rincianProject .= "<td colspan='5' class='bg-olive text-black'>";
                $rincianProject .= "<div id='wrapper_$k' class='open' style='height:0px;overflow: hidden;transition: height 200ms;'>";
                $rincianProject .= "<div id='height_$k'>";

                $fase_urut = $k;
                foreach ($produk_komposisi_fase_sub[$k] as $nSubFase => $subFaseData) {
                    $sub_nama = isset($sub_fase_nama[$k][$nSubFase]) ? $sub_fase_nama[$k][$nSubFase] : "####";
                    $rincianProject .= "<div class='box'>";
                    $rincianProject .= "<div class='box-header text-bold text-uppercase text-primary'>$sub_nama</div>";
                    $rincianProject .= "<div class='box-body no-padding'>";
                    $faseSubTotal = 0;
                    foreach ($produk_komposisi_fase_header_mini as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display striped table-bordered'>";
                                $rincianProject .= "<thead>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<th>No</th>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $rincianProject .= "<th>$hLabel</th>";
                                }
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</thead>";
                                $rincianProject .= "<tbody>";
                                $i = 0;
                                $presub = 0;
                                if (isset($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["produk"]) && sizeof($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["produk"])) {
                                    foreach ($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["produk"] as $aa => $DataRelsupplies) {
                                        $tID = $DataRelsupplies["id"];
                                        $presub += $DataRelsupplies["jml"] * $DataRelsupplies["harga"];
                                        $rincianProject .= "<tr>";
                                        $i++;
                                        $rincianProject .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $rincianProject .= "<td>" . formatField($hField, $val) . "</td>";

                                        }
                                        $rincianProject .= "</tr>";
                                    }
                                }
                                $rincianProject .= "</tbody>";
                                $rincianProject .= "<tfoot class='text-bold'>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<td colspan='" . count($hLabelData) . "' class='text-right'>Total</td>";
                                $rincianProject .= "<td>" . formatField("subtotal", $presub) . "</td>";
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</tfoot>";
                                $rincianProject .= "</table>";

                                $faseSubTotal += $presub;
                                break;
                            case "biaya":
                                $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display table-bordered'>";
                                $rincianProject .= "<thead>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<th>No</th>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $rincianProject .= "<th>$hLabel</th>";
                                }
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</thead>";
                                $rincianProject .= "<tbody>";
                                //bagaian data relasi komposisi
                                $i = 0;
                                $presub = 0;
                                if (isset($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["biaya"]) && sizeof($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["biaya"]) > 0) {
                                    foreach ($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["biaya"] as $DataRelsuppliesBiaya) {
                                        $presub += $DataRelsuppliesBiaya["jml"] * $DataRelsuppliesBiaya["harga"];
                                        $rincianProject .= "<tr>";
                                        $i++;
                                        $rincianProject .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $rincianProject .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $rincianProject .= "</tr>";
                                    }
                                }
                                $rincianProject .= "</tbody>";
                                $rincianProject .= "<tfoot class='text-bold'>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<td colspan='" . count($hLabelData) . "' class='text-right'>Total</td>";
                                $rincianProject .= "<td>" . formatField("subtotal", $presub) . "</td>";
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</tfoot>";
                                $rincianProject .= "</table>";

                                $faseSubTotal += $presub;
                                break;
                            case "jual":
                                if (isset($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["jual"]) && sizeof($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["jual"])) {
                                    foreach ($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["jual"] as $aa => $DataRelsupplies) {
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";

                                        }
                                    }
                                }
                                break;
                        }
                    }
                    $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display table-bordered'>";
                    $rincianProject .= "<thead class='text-bold'>";
                    $rincianProject .= "<tr>";
                    $rincianProject .= "<th colspan='' class='text-right fa-2x'>Total</th>";
                    $rincianProject .= "<th class='fa-2x'>" . formatField("subtotal", $faseSubTotal) . "</th>";
                    $rincianProject .= "</tr>";
                    $rincianProject .= "</thead>";
                    $rincianProject .= "</table>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }

                $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display table-bordered'>";
                $rincianProject .= "<thead class='text-bold'>";
                $rincianProject .= "<tr>";
                $rincianProject .= "<th colspan='' class='text-right fa-2x'>Total</th>";
                $rincianProject .= "<th class='fa-2x'>" . formatField("subtotal", $subtotal) . "</th>";
                $rincianProject .= "</tr>";
                $rincianProject .= "</thead>";
                $rincianProject .= "</table>";

                $rincianProject .= "</div>";
                $rincianProject .= "</div>";

                $rincianProject .= "</td>";
                $rincianProject .= "</tr>";

                $subtotalTotal += $subtotal;
                $subtotalTotalJual += $totalJual;
            }

            $rincianProject .= "</tbody>";
            $rincianProject .= "<tfoot>";

            $rincianProject .= "<tr>";
            $rincianProject .= "<td colspan='3' class='text-center'>Total Anggaran/BOM</td>";
            $rincianProject .= "<td colspan='2'>" . formatField("subtotal", $subtotalTotal) . "</td>";
            $rincianProject .= "</tr>";

            $rincianProject .= "<tr>";
            $rincianProject .= "<td colspan='3' class='text-center'>Total Jual Project</td>";
            $rincianProject .= "<td colspan='2'>" . formatField("subtotal", $subtotalTotalJual) . "</td>";
            $rincianProject .= "</tr>";

            $bg_color = $subtotalTotalJual - $subtotalTotal < 0 ? "bg-danger" : "bg-info";
            $kerugin = $subtotalTotalJual - $subtotalTotal < 0 ? " <br><small><r>rugi " . number_format($subtotalTotal - $subtotalTotalJual) . "</r></small>" : "";

            $rincianProject .= "<tr class='$bg_color text-bold fa-2x'>";
            $rincianProject .= "<td colspan='3' class='text-center'>Total R/L $kerugin</td>";
            $rincianProject .= "<td colspan='2'>" . formatField("subtotal", $subtotalTotalJual - $subtotalTotal) . "</td>";
            $rincianProject .= "</tr>";
            $rincianProject .= "</tfoot>";

            $rincianProject .= "</table>";
            $rincianProject .= "</div>";

            $rincianProject .= "<div>";

//            $rincianProject .= "<div style='font-size:16px;font-family: unset;'>
//                <span class='text-left text-bold'>Total Anggaran</span>
//                <span class='pull-right text-bold text-primary'>".number_format($subtotalTotal)."</span>
//            </div>";

            $rincianProject .= "</div>";

            $id = $this->uri->segment(4);

            //TOMBOL SAVE PROJECT
            $linkSaveBom = "" . MODUL_PATH . "MasterData/saveBom/" . $id . "/" . $subtotalTotalJual;
            $editBomLink = "" . MODUL_PATH . "MasterData/editBom/" . $id;

            if ($lock) {
                if ($project_start) {
                    $rincianProject .= "<div class='alert alert-default'>";
                    $rincianProject .= "<div class='btn-group btn-sm'>";
                    $rincianProject .= "<button type='button' class='btn btn-block btn-flat btn-default'>EDIT MATERIAL BOM</button>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>EDIT MATERIAL TIDAK DAPAT DILAKUKAN LAGI KARENA PROJECT TELAH RUNNING</span>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }
                else {
                    $rincianProject .= "<div class='alert alert-success'>";
                    $rincianProject .= "<div class='btn-group btn-sm'>";
                    $rincianProject .= "<button type='button' onclick=\"if(confirm('Edit Material BOM dapat membatalkan Quotation sebelumnya..!! Yakin melanjutkan ini..??')){window.location.href='$editBomLink'}\" class='btn btn-block btn-flat btn-default'>EDIT MATERIAL BOM</button>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>Edit Material BOM dapat membatalkan <r>Quotation</r> sebelumnya..!!</span>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>Sehingga Quotation baru yg di update, harus diapprove kembali.</span>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }
            }
            else {
                if ($lock && $transaksi_no != "" && $no_kontrak != 0) {
                    $rincianProject .= "lock: $lock || transaksi_no: $transaksi_no || no_kontrak: $no_kontrak";
                    //jika sudah lock dan sudah di approve serta sudah ada no kontraknya
                }
                else {
                    $rincianProject .= "<div class='alert alert-success'>";
                    $rincianProject .= "<div class='btn-group btn-sm'>";
                    $rincianProject .= "<button type='button' onclick=\"if(confirm('Setelah Material BOM disimpan, maka BOM tidak dapat ditambah atau dikurangi lagi, namun tombol Edit bisa membantu Anda untuk kembali pada Mode Editor BOM. Yakin melanjutkan ini..??')){window.location.href='$linkSaveBom'}\" class='btn btn-block btn-flat btn-warning'>SIMPAN BOM PROJECT & LANJUTKAN (data)</button>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>Setelah Material BOM disimpan, maka BOM tidak dapat ditambah atau dikurangi lagi, namun tombol Edit bisa membantu Anda untuk kembali pada Mode Editor BOM.</span>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }
            }

        }

//        $rincianProject = "lock: $lock || transaksi_no: $transaksi_no || no_kontrak: $no_kontrak";

        $rincianProject .= "
            <script>
                function showHideRow(ids){
                    if( $('#hr_'+ids) && $('#hr_'+ids).hasClass('hidden') ){
                        $('#hr_'+ids).removeClass('hidden');
                        $('#wrapper_'+ids).height( $('#height_'+ids).outerHeight(true) + 10 );
                    }
                    else{
                        $('#wrapper_'+ids).height(0);
                        setTimeout( function(){
                            $('#hr_'+ids).addClass('hidden');
                        }, 450)
                    }
                }
                function reloadSummaryProject(){
                    $('#projectSummary').load('$modulClassLink/showSummaryProject/$produkID');
                }
            </script>
            ";


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

        $scriptBottom = "<script>$('#btnReload').on('click', function(){ })</script>";
        $scriptBottom .= "<script>$('#untuk_reload_iframe').off(); $('#untuk_reload_iframe').on('click', function(){ var iframe = top.document.getElementById('$result'); iframe.src=iframe.src; console.log(iframe.src); })</script>";

        $scriptBottom .= "
        <script>
            $('.nav-item').on('click', function(){
                var tabThis = $('a', $(this)).attr('id');
                localStorage.setItem('position_tab', tabThis);
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
            }, 500)

            function preLoadProdukFase(){
                var winSearch = top.window.location.search;

                //swal(winSearch);
                $('#containerPreLoadProdukFase').load('$modulClassLink/showKomposisiProdukFase/$produkID'+winSearch);
            }

            top.preLoadProdukFase();
        </script>";

        /* -----------------------------
         * matiin project
         * -----------------------------*/
//        $img = img_maintenace();
//        $txt = dtimeNow('d F Y H:i');
//        $linkHome = base_url();
//        $txt .= " <br>Kembali ke <a href=\'$linkHome\'>HOME</a>";
//
//        $scriptBottom .= "<script>
//            swal({
//                // title:'ssss',
//                html:'$txt',
//                imageUrl: '$img',
//                showConfirmButton: false,
//                allowOutsideClick: false,
//            });
//        </script>";

        $timWorkUndone = "<div style='margin-top: 22px;' class='box-header bg-orange box-solid text-center text-bold'><i class='fa fa-warning text-white blink'></i> SETELAH QUOTATION/BOM DI APPROVE, ANDA BISA MEMBUAT TIM DISINI. <i class='fa fa-warning text-white blink'></i></div>";
        $timWorkUndone .= "<div style='margin-top: 5px;' class='box-header bg-info box-solid text-center text-bold'><i><u><r> SILAHKAN MENGHUBUNGI ATASAN ANDA, AGAR MEMPERCEPAT PROSES PENGECEKAN DAN APPROVAL </r></u></i><br><div><a href='" . base_url() . "master_project/Transaksi/index/588'>ATAU KLIK DISINI</a></div></div>";
//        $timWorkUndone .= "<div>lock: $lock || no_kontrak: $no_kontrak || transaksi_no: $transaksi_no</div>";

        $WorkOrderUnSave = "<div style='margin-top: 22px;' class='box-header bg-orange box-solid text-center text-bold'><i class='fa fa-warning text-white blink'></i> JANGAN LUPA UNTUK MENYIMPAN BOM JIKA TELAH SELESAI MENAMBAHKAN KOMPONEN-KOMPONEN. <i class='fa fa-warning text-white blink'></i></div>";

        $timWork_f = "";

//        echo "lock: $lock || no_kontrak: $no_kontrak";
//        echo "$lock*1==1 => " . ($lock*1==1) . "<br>";
//        echo "$no_kontrak!='' => " . ($no_kontrak!='') . "<br>";
//        echo "$no_kontrak!=0 => " . ($no_kontrak!='0') . "<br>";

        if ($lock * 1 == 1) {
            $pakai_teamwork = 0;
            if ($pakai_teamwork) {
                $timWork_f = $timWork;
            }
            else {
                $timWork_f = "<div class='well well-success text-center text-bold text-red'>ANDA BISA MENAMBAH PENUGASAN MELALUI TAB TASKLIST/WO</div>";
            }
        }
        else {
            if ($lock * 1 == 1) {
                $timWork_f = $timWorkUndone;
            }
            else {
                $timWork_f = $WorkOrderUnSave;
            }
        }

        $p->addTags(array(
            "timWork" => $timWork_f,
//            "timWork"            => "-- tambahkan team work --",
            "projectData" => $project,
//            "projectData" => "",
            "projectDataDetails" => $rincianProject,
            "content" => $strMain,
            "scriptBottom" => $scriptBottom,
            "display_iframe" => "none",//biar gak panjang
            "error_msg" => $error,
            "menu_left" => callMenuLeft(),
            "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
        ));
        $p->render();
        break;
    case "taskList":
//        trt
        if ($load == "switch") {
            //akan load template dengan format element saja
            $p = New Layout("View Project", "View Your Project", MODUL_TEMPLATE_PATH . "template/tasklist.html");
        }
        else {

            if($produkID=="qc"){
                //akan load template dengan format element saja
                $p = New Layout("View Project", "View Your Project", MODUL_TEMPLATE_PATH . "template/tasklist_qc.html");
            }
            else{
                //akan load template dengan format html lengkap
                $p = New Layout("Manage Project", "Manage Your Project", MODUL_TEMPLATE_PATH . "template/tasklist_base.html");
            }

        }

        $targetResult = isset($result) ? "&result=$result" : "&result=result";

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
            $formWork_tim .= "<select multiple id='employee_id' data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=employee_id&value='+encodeURI( $(this).selectpicker('val') )+'$targetResult');\">";
            $formWork_tim .= "<option value='0'>-- silahkan pilih --</option>";
            foreach ($timWork as $timWork_0) {
                $pid = $timWork_0->produk_id;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] == $timWork_0->employee_id) ? "selected" : "";
                $formWork_tim .= "<option $selected value='" . $timWork_0->employee_id . "'>" . $timWork_0->employee_nama . "</option>";
            }
            $formWork_tim .= "</select>";
        }
        else {
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
            $formWork_tim .= "<select id='employee_id' data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\">";
            $formWork_tim .= "<option value='0'>-- silahkan pilih --</option>";
            $formWork_tim .= "</select>";

//            $formWork_tim .= "<span class='btn btn-xs btn-flat' onclick=\"$addNewCustomer\"> ADD EMPLOYEE </span>";
        }

        if (count($workOrder) > 0) {

            $kol = "fase_id";
            $formWork_order = "";
            $formWork_order .= "<select id='$kol' data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\">";
            $formWork_order .= "<option value='0'>-- silahkan pilih --</option>";
            foreach ($workOrder as $workOrder_0) {
                $pid = $workOrder_0->produk_id;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] == $workOrder_0->id) ? "selected" : "";
                $formWork_order .= "<option $selected value='" . $workOrder_0->id . "'>" . $workOrder_0->nama . " </option>";
            }
            $formWork_order .= "</select>";

            $showNilai = $_SESSION['MasterData']['show_nilai'] * 1;

            $formWork_order .= "<script>
                $('#$kol').off();
                $('#$kol').on('change', function(){
                    var values = $(this).val();
                    $.ajax({
                        url: '$selector$produkID?key=fase_id&value='+encodeURI(values)+'$targetResult',
                        success: function(r_fase_id){
                            var hsl = JSON.parse(r_fase_id);
                            var opt = \"<label class='box-title'>Pilih Pekerjaan: &nbsp;</label>\"
                                    + \"<select id='sub_fase' data-style='btn btn-sm btn-success' class='' data-live-search='true'>\"
                                    + \"<option value='0'>-- Pilih Pekerjaan --</option>\"

                            jQuery.each(hsl.$kol, function(a,b){

                                console.log(b);

                                var sel = hsl.session[$produkID].sub_fase_id*1  == b.id*1 ? 'selected' : ''
                                var disabeled = b.daftar_tugas*1 > 0 ? 'disabled' : ''
                                var icons__t = b.daftar_tugas*1 > 0 ? 'fa fa-check-circle' : ''
                                opt += \"<option data-icon='\"+icons__t+\"' \"+disabeled+\" \"+sel+\" value='\"+b.id+\"'>\"+b.nama+\"</option>\"
                            })

                            opt += \"</select>\"

                            $('#sub_fase_selector').html(opt);
                            $('#sub_fase_selector').removeClass('hidden');
                            top.$('select#sub_fase').selectpicker('refresh');
                            initSubFase();
                            top.$('select#sub_fase').trigger('change');
                        }
                    })
                });

                $('#$kol').trigger('change');

                function initSubFase(){
//                    $('#sub_fase').off();
                    $('#sub_fase').on('change', function(){
                        var values = $(this).val();
                        $.ajax({
                            url: '$selector$produkID?key=sub_fase_id&value='+encodeURI(values)+'$targetResult',
                            success: function(sub_fase_id){
                                var hsl = JSON.parse(sub_fase_id);
                                var room = hsl.room;
                                var komposisi = hsl.komposisi_room;

                                console.error('room.length'  );
                                console.log( room.length );

                                var updated = Object.values(hsl.sub_fase_id.reduce((obj, item) => {
                                    var key = item.jenis + '_' + item.produk_dasar_id;
                                    if (!obj[key]) {
                                        obj[key] = Object.assign(item)
                                        obj[key].harga = obj[key].harga*1
                                        obj[key].jml = obj[key].jml*1
                                        obj[key].saldo = obj[key].saldo*1
                                    }
                                    else {
                                        obj[key].jml += item.jml*1
                                        obj[key].saldo += item.saldo*1
                                    }
                                    return obj
                                }, {}));

                                var showNilai = $showNilai*1;
                                var table = ''
                                    table += \"<div class='row'>\"

                                    if(room.length>0){
                                        table += \"<div class='container-fluids'>\"

                                        table += \"<div class='box box-warning box-solid'>\"
                                        table += \"<div class='box-header fa-2x text-bold text-blue'>SEPARATED ROOM</div>\"
                                        table += \"<div class='box-body'>\"

                                        table += \"<div class='nav-tabs-custom'>\"
                                        table += \"<div class='tab-content no-padding'>\"
                                        table += \"<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>\"

                                        jQuery.each(room, function(a, b){
                                            var defActive = a==0 ? 'active' : ''
                                            var num = a+1;
                                            table += \"<li class='nav-item \"+defActive+\"'>\"
                                            table += \"<a class='nav-link' id='cc-tab-room_\"+b.room_id+\"' data-toggle='pill' href='#tsk-tab-room_\"+b.room_id+\"' role='tab' aria-controls='cc-tab-room_\"+b.room_id+\"' aria-selected='false'><span style='font-size: 12px;' class='text-bold text-uppercase'> <b>\"+num+\". \"+b.room_nama+\"</b></span></a>\"
                                            table += \"</li>\"

                                        });

                                        table += \"</ul>\"

                                            jQuery.each(room, function(a, b){
                                                var list_bahan_biaya = Object.values(komposisi[b.room_id].reduce((obj, item) => {
                                                    var key = item.jenis + '_' + item.produk_dasar_id;
                                                    if (!obj[key]) {
                                                        obj[key] = Object.assign(item)
                                                        obj[key].harga = obj[key].harga*1
                                                        obj[key].jml = obj[key].jml*1
                                                        obj[key].saldo = obj[key].saldo*1
                                                    }
                                                    else {
                                                        obj[key].jml += item.jml*1
                                                        obj[key].saldo += item.saldo*1
                                                    }
                                                    return obj
                                                }, {}));

                                                var defActive = a==0 ? 'active in' : ''

                                                table += \"<div class='tab-pane fade \"+defActive+\"' id='tsk-tab-room_\"+b.room_id+\"'>\"
                                                table += \"<div class='table-responsive'>\"
                                                table += \"<table id='table_container_material_\"+b.room_id+\"' class='table dataTable compact display table-bordered table-condensedx'>\"
                                                table += \"<thead>\"
                                                table += \"<tr>\"
                                                table += \"<th>No</th>\"
                                                table += \"<th>Bahan baku</th>\"
                                                table += \"<th>UoM</th>\"

                                                if(showNilai){
//                                                    table += \"<th class='text-center'>H.HPP<br>(satuan)</th>\"
//                                                    table += \"<th class='text-center'>H.PPV<br>(satuan)</th>\"
                                                    table += \"<th class='text-center'>H.BELI<br>(satuan)</th>\"
                                                    table += \"<th class='text-center'>H.JUAL<br>(satuan)</th>\"
                                                }

                                                table += \"<th class='text-center'>Jml</th>\"

                                                if(showNilai){
//                                                    table += \"<th class='text-center'>Total<br>HPP</th>\"
//                                                    table += \"<th class='text-center'>Total<br>PPV</th>\"
                                                    table += \"<th class='text-center'>Total<br>H.BELI</th>\"
                                                    table += \"<th class='text-center'>Total<br>H.JUAL</th>\"
                                                    table += \"<th class='text-center'>R/L</th>\"
                                                }

                                                table += \"</tr>\"
                                                table += \"</thead>\"
                                                table += \"<tbody>\"

                                                var no          = 0;
                                                var total_saldo = 0;
                                                var total_qty   = 0;

                                                var totHpp  = 0;
                                                var totPpv  = 0;
                                                var totJual = 0;
                                                var totAng  = 0;

                                                var sumHpp  = 0;
                                                var sumPpv  = 0;
                                                var sumJual = 0;
                                                var sumAng  = 0;

                                                jQuery.each(list_bahan_biaya, function(a, b){
                                                    no++;
                                                    produk_dasar_nama = b.produk_dasar_nama;
                                                    satuan = b.satuan;
                                                    harga  = b.harga*1>0 ? b.harga*1 : 0;
                                                    nilai  = b.nilai*1>0 ? b.nilai*1 : 0;
                                                    jml    = b.jml;
                                                    saldo  = b.saldo;

                                                    hpp  = b.hrg_hpp*1> 0 ? b.hrg_hpp*1 : 0;
                                                    ppv  = b.hrg_ppv*1> 0 ? b.hrg_ppv*1 : 0;
                                                    jual = b.hrg_jual*1> 0 ? b.hrg_jual*1 : 0;

                                                    table += \"<tr>\"
                                                    table += \"<td>\"+no+\"</td>\"
                                                    table += \"<td>\"+produk_dasar_nama+\"</td>\"
                                                    table += \"<td>\"+satuan+\"</td>\"

                                                    if(showNilai){
//                                                        table += \"<td class='bg-limex text-right'>\"+addCommas(hpp)+\"</td>\"
//                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv)+\"</td>\"
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual)+\"</td>\"
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga)+\"</td>\"
                                                    }

                                                    table += \"<td class='text-bold text-center'>\"+addCommas(jml)+\"</td>\"

                                                    if(showNilai){
//                                                        table += \"<td class='bg-greenx text-right'>\"+addCommas(hpp*jml)+\"</td>\"
//                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv*jml)+\"</td>\"
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual*jml)+\"</td>\"
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga*jml)+\"</td>\"
                                                        table += \"<td class='text-right'>\"+addCommas( (harga*jml)-(jual*jml))+\"</td>\"
                                                    }

                                                    table += \"</tr>\"

                                                    total_saldo += saldo*1;
                                                    total_qty   += jml*1;

                                                    totHpp  += hpp;
                                                    totPpv  += ppv;
                                                    totJual += jual;
                                                    totAng  += harga;

                                                    sumHpp  += hpp*jml;
                                                    sumPpv  += ppv*jml;
                                                    sumJual += jual*jml;
                                                    sumAng  += harga*jml;
                                                })

                                                table += \"</tbody>\"

                                                table += \"<tfoot class='bg-gray'>\"
                                                table += \"<tr>\"
                                                table += \"<th>-</th>\"
                                                table += \"<th>-</th>\"
                                                table += \"<th>-</th>\"

                                                if(showNilai){
//                                                    table += \"<th class='text-right'>\"+addCommas(totHpp)+\"</th>\"
//                                                    table += \"<th class='text-right'>\"+addCommas(totPpv)+\"</th>\"
                                                    table += \"<th class='text-right'>\"+addCommas(totJual)+\"</th>\"
                                                    table += \"<th class='text-right'>\"+addCommas(totAng)+\"</th>\"
                                                }

                                                    table += \"<th class='text-center'>\"+addCommas(total_qty)+\"</th>\"

                                                if(showNilai){
//                                                    table += \"<th class='text-right'>\"+addCommas(sumHpp)+\"</th>\"
//                                                    table += \"<th class='text-right'>\"+addCommas(sumPpv)+\"</th>\"
                                                    table += \"<th class='text-right'>\"+addCommas(sumJual)+\"</th>\"
                                                    table += \"<th class='text-right'>\"+addCommas(sumAng)+\"</th>\"
                                                    table += \"<th class='text-right'>\"+addCommas((sumAng)-(sumJual))+\"</th>\"
                                                }

                                                table += \"</tr>\"
                                                table += \"</tfoot>\"

                                                table += \"</table>\"
                                                table += \"</div>\"
                                                table += \"</div>\"

                                        })

                                        table += \"</div>\"
                                        table += \"</div>\"

                                        table += \"</div>\"
                                        table += \"</div>\"


                                        table += \"</div>\"
                                    }

                                    table += \"<div class='container-fluid'>\"
                                    table += \"<div class='table-responsive'>\"

                                table += \"<table id='viewData no_room table_container_material' class='table dataTable compact display table-bordered table-condensedx'>\"
                                table += \"<thead>\"
                                table += \"<tr>\"
                                table += \"<th>No</th>\"
                                table += \"<th>Bahan baku</th>\"
                                table += \"<th>UoM</th>\"

                                if(showNilai){
//                                    table += \"<th class='text-center'>H.HPP<br>(satuan)</th>\"
//                                    table += \"<th class='text-center'>H.PPV<br>(satuan)</th>\"
                                    table += \"<th class='text-center'>H.BELI<br>(satuan)</th>\"
                                    table += \"<th class='text-center'>H.JUAL<br>(satuan)</th>\"
                                }

                                table += \"<th class='text-center'>Jml</th>\"

                                if(showNilai){
//                                    table += \"<th class='text-center'>Total<br>HPP</th>\"
//                                    table += \"<th class='text-center'>Total<br>PPV</th>\"
                                    table += \"<th class='text-center'>Total<br>H.BELI</th>\"
                                    table += \"<th class='text-center'>Total<br>H.JUAL</th>\"
                                    table += \"<th class='text-center'>R/L</th>\"
                                }

                                table += \"</tr>\"
                                table += \"</thead>\"
                                table += \"<tbody>\"

                                var no=0;
                                var total_saldo = 0;
                                var total_qty = 0;

                                var totHpp = 0;
                                var totPpv = 0;
                                var totJual = 0;
                                var totAng = 0;

                                var sumHpp = 0;
                                var sumPpv = 0;
                                var sumJual = 0;
                                var sumAng = 0;

                                jQuery.each(updated, function(a, b){
                                    no++;
                                    produk_dasar_nama = b.produk_dasar_nama;
                                    satuan = b.satuan;
                                    harga  = b.harga*1>0 ? b.harga*1 : 0;
                                    nilai  = b.nilai*1>0 ? b.nilai*1 : 0;
                                    jml    = b.jml;
                                    saldo  = b.saldo;

                                    hpp  = b.hrg_hpp*1> 0 ? b.hrg_hpp*1 : 0;
                                    ppv  = b.hrg_ppv*1> 0 ? b.hrg_ppv*1 : 0;
                                    jual = b.hrg_jual*1> 0 ? b.hrg_jual*1 : 0;

                                    table += \"<tr>\"
                                    table += \"<td>\"+no+\"</td>\"
                                    table += \"<td>\"+produk_dasar_nama+\"</td>\"
                                    table += \"<td>\"+satuan+\"</td>\"

                                    if(showNilai){
//                                        table += \"<td class='bg-limex text-right'>\"+addCommas(hpp)+\"</td>\"
//                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga)+\"</td>\"
                                    }

                                    table += \"<td class='text-bold text-center'>\"+addCommas(jml)+\"</td>\"

                                    if(showNilai){
//                                        table += \"<td class='bg-greenx text-right'>\"+addCommas(hpp*jml)+\"</td>\"
//                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv*jml)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual*jml)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga*jml)+\"</td>\"
                                        table += \"<td class='text-right'>\"+addCommas( (harga*jml)-(jual*jml))+\"</td>\"
                                    }

                                    table += \"</tr>\"

                                    total_saldo += saldo*1;
                                    total_qty   += jml*1;

                                    totHpp  += hpp;
                                    totPpv  += ppv;
                                    totJual += jual;
                                    totAng  += harga;

                                    sumHpp  += hpp*jml;
                                    sumPpv  += ppv*jml;
                                    sumJual += jual*jml;
                                    sumAng  += harga*jml;
                                })

                                table += \"</tbody>\"

                                table += \"<tfoot class='bg-gray'>\"
                                table += \"<tr>\"
                                table += \"<th>-</th>\"
                                table += \"<th>-</th>\"
                                table += \"<th>-</th>\"

                                if(showNilai){
//                                    table += \"<th class='text-right'>\"+addCommas(totHpp)+\"</th>\"
//                                    table += \"<th class='text-right'>\"+addCommas(totPpv)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(totJual)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(totAng)+\"</th>\"
                                }

                                    table += \"<th class='text-center'>\"+addCommas(total_qty)+\"</th>\"

                                if(showNilai){
//                                    table += \"<th class='text-right'>\"+addCommas(sumHpp)+\"</th>\"
//                                    table += \"<th class='text-right'>\"+addCommas(sumPpv)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(sumJual)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(sumAng)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas((sumAng)-(sumJual))+\"</th>\"
                                }

                                table += \"</tr>\"
                                table += \"</tfoot>\"

                                table += \"</table>\"
                                table += \"</div>\"
                                table += \"</div>\"
                                table += \"</div>\"

                                $('#container_material').html(table);

                                var table_material = $('#table_container_material').DataTable({
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            text: 'Show Hide Column',
                                            extend: 'colvis',
                                            postfixButtons: ['colvisRestore']
                                        }
                                    ],
                                    ordering: false,
                                    searching: false,
                                    info: false,
                                    paging: false,
                                    stateSave: true
                                });

                                table_material.on('buttons-action', function ( e, buttonApi, dataTable, node, config ) {
                                    //console.log( 'Button '+buttonApi.text()+' was activated' );
                                    table_material.draw()
                                });

                                if( $('#table_container_material thead tr th') ){
                                    var header_text = $('#table_container_material thead tr th');
                                    var custom_btn = ''
                                    jQuery.each(header_text, function(a, b){
                                        if(a>0){
                                            if(custom_btn == ''){
                                                custom_btn += \"<a style='cursor: pointer;' class='toggle-vis text-uppercase' data-column='\"+a+\"'>\"+$(b).text()+\"</a>\"
                                            }
                                            else{
                                                custom_btn += \"- <a style='cursor: pointer;' class='toggle-vis text-uppercase' data-column='\"+a+\"'>\"+$(b).text()+\"</a>\"
                                            }
                                        }
                                    });
                                    $('.list_showhidecolumn').html(custom_btn);
                                }

                                if( $('.list_showhidecolumn') ){
                                    document.querySelectorAll('a.toggle-vis').forEach((el) => {
                                        el.addEventListener('click', function (e) {
                                            e.preventDefault();
                                            let columnIdx = e.target.getAttribute('data-column');
                                            let column = table_material.column(columnIdx);
                                            // Toggle the visibility
                                            column.visible(!column.visible());
                                            if( table_material.draw() ){
                                                //console.log('redraw table_material');
                                            }
                                        });
                                    });
                                }
                            }
                        })
                    });
//                    $('#sub_fase').trigger('change');
                }

            </script>";

        }

        //region option material
        $optionmaterial = "<select id='option_material' data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" >";
        $optionmaterial .= "<option value=''>-- silahkan pilih --</option>";
        $optionmaterial .= "<option value='1'>Material</option>";
        $optionmaterial .= "<option value='2'>Non material</option>";
        $optionmaterial .= "</select >";

        $optionmaterial .= "<script>
                    $('#option_material').on('change', function(){
                        var values = $(this).val();
                        switch(values){
                            case '1':
                                console.log('masuk case ' + values);
                                $.ajax({
                                    url: '$selector$produkID?key=material&value='+encodeURI(values),
                                    success: function(r_fase_id){
                                        var hsl = JSON.parse(r_fase_id);
                                        var showNilai = $showNilai*1;
                                        var table = ''
                                            table += \"<table class='table dataTable compact display table-bordered table-condensedx'>\"
                                            table += \"<thead>\"
                                            table += \"<tr>\"
                                            table += \"<th>No</th>\"
                                            table += \"<th>Bahan baku</th>\"
                                            table += \"<th>Satuan</th>\"

                                            if(showNilai){
                                                table += \"<th>Harga Beli(satuan)</th>\"
                                                table += \"<th>Harga Jual(satuan)</th>\"
                                            }

                                            table += \"<th>Jml</th>\"

                                            if(showNilai){
                                                table += \"<th>Subtotal</th>\"
                                            }

                                            table += \"</tr>\"
                                            table += \"</thead>\"
                                            table += \"<tbody>\"

                                            var no=0;
                                            var total_saldo = 0
                                            var total_qty = 0
                                            jQuery.each(hsl, function(a, b){
                                                no++;
                                                produk_dasar_nama = b.produk_dasar_nama
                                                satuan = b.satuan
                                                harga = b.harga
                                                nilai = b.nilai
                                                jml = b.jml
                                                saldo = b.saldo

                                                table += \"<tr>\"
                                                table += \"<td>\"+no+\"</td>\"
                                                table += \"<td>\"+produk_dasar_nama+\"</td>\"
                                                table += \"<td>\"+satuan+\"</td>\"

                                                if(showNilai){
                                                    table += \"<td class='text-right'>\"+addCommas(harga)+\"</td>\"
                                                    table += \"<td class='text-right'>\"+addCommas(nilai)+\"</td>\"
                                                }

                                                table += \"<td class='text-right'>\"+addCommas(jml)+\"</td>\"

                                                if(showNilai){
                                                    table += \"<td class='text-right'>\"+addCommas(saldo)+\"</td>\"
                                                }

                                                table += \"</tr>\"

                                                total_saldo += saldo*1
                                                total_qty += jml*1
                                            })

                                            table += \"</tbody>\"

                                            table += \"<tfoot>\"
                                            table += \"<tr>\"
                                            table += \"<th>-</th>\"
                                            table += \"<th>-</th>\"
                                            table += \"<th>-</th>\"

                                            if(showNilai){
                                                table += \"<th>-</th>\"
                                                table += \"<th>-</th>\"
                                            }

                                            table += \"<th class='text-right'>\"+addCommas(total_qty)+\"</th>\"

                                            if(showNilai){
                                                table += \"<th class='text-right'>\"+addCommas(total_saldo)+\"</th>\"
                                            }

                                            table += \"</tr>\"
                                            table += \"</tfoot>\"

                                            table += \"</table>\"

                                        $('#container_material').html(table)
                                    }
                                })
                            break;
                            default:
                                console.log('masuk case KOSONG');
                                $('#container_material').html('')
                            break;
                        }
                    });
                </script>";

        //endregion

        //region option progress tasklist
        $progresTask = "";
        if (count($progresTaklist) > 0) {
            $kol = "progress_id";
            $progresTask .= "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=progress_id&value='+encodeURI(this.value)+'$targetResult');\">";
            $progresTask .= "<option value='0'>-- silahkan pilih --</option>";
            foreach ($progresTaklist as $progresTaklist_0) {
                $pid = $produkID;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] = $progresTaklist_0->id) ? "selected" : "";
                $progresTask .= "<option $selected value='" . $progresTaklist_0->id . "'>" . $progresTaklist_0->nama . " </option>";
            }
            $progresTask .= "</select>";
        }
        //endregion

//    arrPrint($masterProject);
//    arrPrintWebs($taskistProject);

        $project = "";
        if (count($masterProject) > 0) {
            $project .= "<table line='" . __LINE__ . "' class='table dataTable compact display table-bordered table-condensed'>";
            foreach ($masterProjectField as $key => $label) {
                $mpVal = $masterProject->$key;
                $project .= "<tr>";
                $project .= "<td width='1%' class='text-bold'>$label</td>";
                $project .= "<td class='text-right' data-key='$key' data-value='$mpVal'>" . formatField($key, $masterProject->$key) . "</td>";
                $project .= "</tr>";
            }
            $project .= "</table>";
        }

        //==================== REGION viewPenggunaanMaterial =======================
        //==========================================================================
        //==========================================================================

        $viewPenggunaanMaterial = "";

        if (count($penggunaan_material) > 0) {

            $viewPenggunaanMaterial .= "<style>
</style>";

            $viewPenggunaanMaterial .= "
                <div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;'>
                  <h5 style='margin:0;'>INFORMASI PENGGUNAAN MATERIAL & SUPPLIES</h5>
                  <div>
                    <label style='margin-right:5px;'>Filter Jenis:</label>
                    <select id='filterJenis' class='form-control input-sm' style='width:150px; display:inline-block;'>
                      <option value=''>Semua Jenis</option>
                      <option value='Biaya'>Biaya</option>
                      <option value='Produk'>Produk</option>
                    </select>
                  </div>
                </div>";


            $viewPenggunaanMaterial .= "<table line='" . __LINE__ . "' id='tblPenggunaanMaterial' class='table dataTable compact display table-bordered table-condensed'>";
            $viewPenggunaanMaterial .= "
                            <thead>
                                <tr class='text-center'>
                                    <th width='1%'>#</th>
                                    <th width='1%'>Nama Material / Biaya</th>
                                    <th width='1%'>Jenis</th>
                                    <th width='1%' class='text-right'>Komposisi<br><small>(Qty)</small></th>
                                    <th width='1%' class='text-right'>Digunakan<br><small>(Qty)</small></th>
                                    <th width='1%' class='text-right'>Retur<br><small>(Qty)</small></th>
                                    <th width='1%' class='text-right'>Sisa<br><small>(Qty)</small></th>
                                    <th width='1%' class='text-right'>%</th>
                                </tr>
                            </thead>
                            <tbody>
                        ";

            $total_komposisi = 0;
            $total_digunakan = 0;
            $total_sisa = 0;
            $no = 1;

            foreach ($penggunaan_material as $row) {
                $persen = 0;
                if ($row->total_komposisi > 0) {
                    $persen = round(( ($row->total_digunakan+$row->total_return) / $row->total_komposisi) * 100, 2);
                }

                // Warna progress bar
                if ($persen >= 90) $color = 'success';
                elseif ($persen >= 50) $color = 'warning';
                else $color = 'danger';

                $viewPenggunaanMaterial .= "<tr>";
                $viewPenggunaanMaterial .= "<td class='text-center'>{$no}</td>";
                $viewPenggunaanMaterial .= "<td>{$row->produk_dasar_nama}</td>";
                $viewPenggunaanMaterial .= "<td class='text-center'>" . ucfirst($row->jenis) . "</td>";
                $viewPenggunaanMaterial .= "<td class='text-right'>" . number_format($row->total_komposisi, 0, ',', '.') . "</td>";
                $viewPenggunaanMaterial .= "<td class='text-right'>" . number_format($row->total_digunakan, 0, ',', '.') . "</td>";
                $viewPenggunaanMaterial .= "<td class='text-right'>" . number_format($row->total_return, 0, ',', '.') . "</td>";
                $viewPenggunaanMaterial .= "<td class='text-right'>" . number_format($row->sisa, 0, ',', '.') . "</td>";

                // Kolom persentase dengan bar
// warna progress bar sesuai nilai persen
                if ($persen >= 98) {
                    $barClass = 'progress-bar-success';  // hijau
                    $label = 'Selesai';
                }
                elseif ($persen >= 50) {
                    $barClass = 'progress-bar-warning';  // kuning
                    $label = 'Progres';
                }
                else {
                    $barClass = 'progress-bar-danger';   // merah
                    $label = 'Awal';
                }

// progress bar
                $viewPenggunaanMaterial .= "
                    <td class='text-right'>
                        <div class='progress active' style=''>
                            <div class='progress-bar {$barClass} progress-bar-striped'
                                role='progressbar'
                                aria-valuenow='{$persen}'
                                aria-valuemin='0'
                                aria-valuemax='100'
                                style='width: {$persen}%'>
                                <span class='text-bold text-auto' style='font-size:10px;'>{$persen}% {$label}</span>
                            </div>
                        </div>
                    </td>";


                $viewPenggunaanMaterial .= "</tr>";

                $total_komposisi += $row->total_komposisi;
                $total_digunakan += $row->total_digunakan;
                $total_return += $row->total_return;
                $total_sisa += $row->sisa;
                $total_persen += $persen;
                $no++;
            }

            // Total keseluruhan
            $viewPenggunaanMaterial .= "
        </tbody>
        <tfoot>
            <tr class='text-right text-bold'>
                <td colspan='3' class='text-center text-bold'>TOTAL</td>
                <td>" . number_format($total_komposisi, 0, ',', '.') . "</td>
                <td>" . number_format($total_digunakan, 0, ',', '.') . "</td>
                <td>" . number_format($total_return, 0, ',', '.') . "</td>
                <td>" . number_format($total_sisa, 0, ',', '.') . "</td>
                <td>" . number_format($total_persen/count($penggunaan_material), 2, ',', '.') . "</td>
            </tr>
        </tfoot>
    ";

            $viewPenggunaanMaterial .= "</table>";

            // Inject JS DataTables
            $viewPenggunaanMaterial .= "
    <script>
    $(document).ready(function() {
        var table = $('#tblPenggunaanMaterial').DataTable({
              paging: false,
              searching: true,
              info: false,
              ordering: false,
              scrollX: true,
              dom: 't', // tampilkan hanya tabel tanpa filter bawaan
              className: 'compact'
            });

        $('#filterJenis').on('change', function() {
            table.column(2).search(this.value).draw();
        });
    });
    </script>";

        }
        else {
            $viewPenggunaanMaterial .= "<p class='text-center text-muted'><em>Tidak ada data penggunaan material.</em></p>";
        }

        //==================== END REGION viewPenggunaanMaterial =======================
        //==============================================================================
        //==============================================================================


        //region active tasklist
        $taskListData = "";



        if (count($taskistProject) > 0) {

            if($produkID=="qc"){
                $taskListData .= "
                    <div class=\"box-header\"><h3><i class=\"fa fa-table\"></i> Tasklist Menunggu Proses QC <div class=\"btn btn-sm btn-info pull-right\" onclick=\"qcProject();\"><i class=\"fa fa-refresh\"></i> REFRESH</div></h3></div>
                ";
                $order     = "order: [2, 'asc']";
                $searching = "searching: true";
                $paging    = "paging: true";
                $info      = "info: true";

                $custButton = "
                    dom: 'Bfrtip',
                    buttons: [
                      {
                        extend: 'excelHtml5',
                        text: '<i class=\"fa fa-file-excel-o\"></i> Export Excel',
                        className: 'btn btn-success btn-sm'
                      },
                      {
                        text: '<i class=\"fa fa-filter\"></i> Tampilkan Yang Telat',
                        className: 'btn btn-warning btn-sm',
                        action: function () {
                          table.column(2).search('Terlambat').draw();
                        }
                      },
                      {
                        text: '<i class=\"fa fa-undo\"></i> Reset Filter',
                        className: 'btn btn-default btn-sm',
                        action: function () {
                          table.search('').columns().search('').draw();
                        }
                      }
                    ],
                ";

                $columnDefs = "
                    columnDefs: [{
                        targets: 2,
                        render: function ( data, type, row, meta ) {
                            var end = new Date(data).getTime();
                            var start = new Date().getTime();
                            console.log(end-start);
                            return data + '<br><r>'+top.moment(data, 'YYYYMMDD').fromNow()  + '</r>'
                        }
                    },
                    {
                        targets: 7, //kolom paket
                        render: function ( data, type, row, meta ) {
                            return \"<i class='fa fa-dropbox text-navi'></i> <span class='text-bold text-uppercase text-navi'>\"+ data +\"</span>\"
                        }
                    },
                    {
                        targets: 8, //kolom pelaksana di tunjuk
                        render: function ( data, type, row, meta ) {
                            return \"<i class='fa fa-user-circle text-blue'></i> <span class='text-bold text-uppercase text-blue'>\"+ data +\"</span>\"
                        }
                    },
                    {
                        targets: 10, //kolom status
                        render: function ( data, type, row, meta ) {
                            switch(data){
                                case '1':
                                    return \"<div class='btn btn-xs btn-warning btn-flatx' disabled title='menunggu di proses oleh pelaksana'><i class='fa fa-hourglass'></i> menunggu di proses</div>\"
                                break;
                                case '2':
                                    return \"<div class='btn btn-xs btn-primary btn-flatx' disabled title='sedang di proses oleh pelaksana'><i class='fa fa-gear fa-spin'></i> di proses</div>\"
                                break;
                                case '3':
                                    return \"<div class='btn btn-xs btn-success btn-flatx' disabled title='pelaksanaan telah selesai'><i class='fa fa-check-circle'></i> selesai</div>\"
                                break;
                                default:
                                    return \"none\";
                                break;
                            }
                        }
                    },
                    {
                        targets: 11, //kolom progress
                        render: function ( data, type, row, meta ) {
                            var progg = data*1 > 0 ? data : 0
                            var progg_stat = 'active'
                            text='';
                            if(progg<10){
                                bar_class = 'danger';
                                text_color = 'black';
                                progg_wid = progg*1;
                            }
                            else if(progg<50){
                                bar_class = 'warning';
                                text_color = 'red';
                                progg_wid = progg*1;
                            }
                            else if(progg<80){
                                bar_class = 'primary';
                                text_color = 'auto';
                                progg_wid = progg*1;
                            }
                            else if(progg==100){
                                bar_class = 'success';
                                text_color = 'auto';
                                text = 'Selesai';
                                progg_wid = progg*1;
                            }
                            else if(progg==0){
                                bar_class = 'danger';
                                text_color = 'white';
                                progg_wid = 25;
                                progg_stat = ''
                            }
                            else{
                                bar_class = '';
                                text_color = '';
                                progg_wid = progg*1;
                            }

                            btn  = \"\"
                            btn += \"<div class='progress \"+progg_stat+\"'>\"
                            btn += \"<div class='progress-bar progress-bar-\"+bar_class+\" progress-bar-striped' role='progressbar' aria-valuenow='\"+progg*1+\"' aria-valuemin='0' aria-valuemax='100' style='width: \"+progg_wid+\"%'>\"
                            btn += \"<span class='text-bold text-\"+text_color+\"'>\"+progg*1+\"% \"+text+\"</span>\"

                            btn += \"</div>\"
                            btn += \"</div>\"
                            
                            if(progg>99 && row[10]==2){
                                btn += \"<div class='text-red text-bold text-center text-uppercase'> menunggu pengecekan<br>(QC) </div>\"
                            }

                            return btn;
                        }
                    },
                    {
                        targets: 12, //kolom Tindakan / tombol action
                        render: function ( data, type, row, meta ) {
                           var btn = \"<div class='btn-group-vertical'>\"
                           jQuery.each(data, function(a, b){
                                var typBtn = b.typBtn;
                                var scriptID = b.scriptID;
                                var opt = b.opt;
                                var onclick = b.onclick;
                                var bclass = b.bclass;
                                var btnDisabled = b.btnDisabled;
                                var dataicon = b.dataicon;
                                var txt = b.txt;
                                var progress_id = b.progress_id;
                                var onclick_broke = b.onclick_broke;
                                var label = b.label!='' ? b.label : a;
                                if(typBtn=='n'){
                                    btn += \"<div data-id='\"+a+\"-\"+scriptID+\"' \"+opt+\" \"+onclick+\" class='btn btn-xs btn-flatx \"+bclass+\" text-left' \"+btnDisabled+\"><i class='\"+dataicon+\"'></i>&nbsp;&nbsp;\"+label+\" \"+txt+\"</div>\";
                                }
                                else{
                                    btn += \"<div data-id='\"+a+\"-\"+scriptID+\"' \"+opt+\" \"+onclick+\" class='btn btn-xs btn-flatx \"+bclass+\" text-left' \"+btnDisabled+\"><i class='\"+dataicon+\"'></i>&nbsp;&nbsp;\"+label+\" \"+txt+\"</div>\";
                                }
                           })
                           btn += \"</div>\"
                           return btn;
                        }
                    }]
                ";

            }
            else{

                $taskListData .= "
                    <div class='box-header table-responsive'>
                            <div class='col-xs-12 col-md-4 col-lg-4 no-padding'>
                        <h3>INFORMASI DETAIL PROJECT</h3>
                        $project
                    </div>
                    <div style='padding-left:50px !important;' class='col-xs-12 col-md-8 col-lg-8 no-padding'>
                        $viewPenggunaanMaterial
                    </div>
                    </div>
                ";

                $searching = "searching: false";
                $paging    = "paging: false";
                $info      = "info: false";
                $order     = "order: [8, 'desc']";
                $custButton = "";
                $columnDefs = "
                    columnDefs: [{
                        targets: 2,
                        render: function ( data, type, row, meta ) {
                            var end = new Date(data).getTime();
                            var start = new Date().getTime();
                            console.log(end-start);
                            return data + '<br><r>'+top.moment(data, 'YYYYMMDD').fromNow()  + '</r>'
                        }
                    },
                    {
                        targets: 5, //kolom paket
                        render: function ( data, type, row, meta ) {
                            return \"<i class='fa fa-dropbox text-navi'></i> <span class='text-bold text-uppercase text-navi'>\"+ data +\"</span>\"
                        }
                    },
                    {
                        targets: 6, //kolom pelaksana di tunjuk
                        render: function ( data, type, row, meta ) {
                            return \"<i class='fa fa-user-circle text-blue'></i> <span class='text-bold text-uppercase text-blue'>\"+ data +\"</span>\"
                        }
                    },
                    {
                        targets: 8, //kolom status
                        render: function ( data, type, row, meta ) {
                            switch(data){
                                case '1':
                                    return \"<div class='btn btn-xs btn-warning btn-flatx' disabled title='menunggu di proses oleh pelaksana'><i class='fa fa-hourglass'></i> menunggu di proses</div>\"
                                break;
                                case '2':
                                    return \"<div class='btn btn-xs btn-primary btn-flatx' disabled title='sedang di proses oleh pelaksana'><i class='fa fa-gear fa-spin'></i> di proses</div>\"
                                break;
                                case '3':
                                    return \"<div class='btn btn-xs btn-success btn-flatx' disabled title='pelaksanaan telah selesai'><i class='fa fa-check-circle'></i> selesai</div>\"
                                break;
                                default:
                                    return \"none\";
                                break;
                            }
                        }
                    },
                    {
                        targets: 9, //kolom progress
                        render: function ( data, type, row, meta ) {
                            var progg = data*1 > 0 ? data : 0
                            var progg_stat = 'active'
                            text='';
                            if(progg<10){
                                bar_class = 'danger';
                                text_color = 'black';
                                progg_wid = progg*1;
                            }
                            else if(progg<50){
                                bar_class = 'warning';
                                text_color = 'red';
                                progg_wid = progg*1;
                            }
                            else if(progg<80){
                                bar_class = 'primary';
                                text_color = 'auto';
                                progg_wid = progg*1;
                            }
                            else if(progg==100){
                                bar_class = 'success';
                                text_color = 'auto';
                                text = 'Selesai';
                                progg_wid = progg*1;
                            }
                            else if(progg==0){
                                bar_class = 'danger';
                                text_color = 'white';
                                progg_wid = 25;
                                progg_stat = ''
                            }
                            else{
                                bar_class = '';
                                text_color = '';
                                progg_wid = progg*1;
                            }

                            btn  = \"\"
                            btn += \"<div class='progress \"+progg_stat+\"'>\"
                            btn += \"<div class='progress-bar progress-bar-\"+bar_class+\" progress-bar-striped' role='progressbar' aria-valuenow='\"+progg*1+\"' aria-valuemin='0' aria-valuemax='100' style='width: \"+progg_wid+\"%'>\"
                            btn += \"<span class='text-bold text-\"+text_color+\"'>\"+progg*1+\"% \"+text+\"</span>\"
                            btn += \"</div>\"
                            btn += \"</div>\"

                            if(progg==100 && row[7]==2){
                                btn += \"<div class='text-red text-bold text-center text-uppercase'> menunggu pengecekan<br>(QC) </div>\"
                            }

                            return btn;
                        }
                    },
                    {
                        targets: 10, //kolom Tindakan / tombol action
                        render: function ( data, type, row, meta ) {
                           var btn = \"<div class='btn-group-vertical'>\"
                           jQuery.each(data, function(a, b){
                                var typBtn = b.typBtn;
                                var scriptID = b.scriptID;
                                var opt = b.opt;
                                var onclick = b.onclick;
                                var bclass = b.bclass;
                                var btnDisabled = b.btnDisabled;
                                var dataicon = b.dataicon;
                                var txt = b.txt;
                                var progress_id = b.progress_id;
                                var onclick_broke = b.onclick_broke;
                                var label = b.label!='' ? b.label : a;
                                if(typBtn=='n'){
                                    btn += \"<div data-id='\"+a+\"-\"+scriptID+\"' \"+opt+\" \"+onclick+\" class='btn btn-xs btn-flatx \"+bclass+\" text-left' \"+btnDisabled+\"><i class='\"+dataicon+\"'></i>&nbsp;&nbsp;\"+label+\" \"+txt+\"</div>\";
                                }
                                else{
                                    btn += \"<div data-id='\"+a+\"-\"+scriptID+\"' \"+opt+\" \"+onclick+\" class='btn btn-xs btn-flatx \"+bclass+\" text-left' \"+btnDisabled+\"><i class='\"+dataicon+\"'></i>&nbsp;&nbsp;\"+label+\" \"+txt+\"</div>\";
                                }
                           })
                           btn += \"</div>\"
                           return btn;
                        }
                    }]
                ";
            }

            $taskListData .= "<div class='box-body no-paddingx'>";
            $taskListData .= "<div class='table-responsive'>";
            $taskListData .= "<table id='daftar_tugas_' class='table dataTable compact display table-bordered table-condensed'>";
            $taskListData .= "<thead>";
            $taskListData .= "<tr>";
            $taskListData .= "<th>No</th>";
            foreach ($tasklistProjectField as $kry => $label) {
                $taskListData .= "<th>$label</th>";
            }
            $taskListData .= "</tr>";
            $taskListData .= "</thead>";
            $taskListData .= "<tbody></tbody>";

            $taskListData .= "</table>";
            $taskListData .= "</div>";
            $taskListData .= "</div>";

            $taskListData .= "<script>
                                $('#nomer_check').on('keyup', function(e){
                                    if(e.keyCode==13){
                                        checkActivatePreSpk()
                                    }
                                })
                                function checkActivatePreSpk(){
                                    var nomer_check = $('#nomer_check').val();
                                    //$('#nomer_check').val('');
                                    if(nomer_check!=''){
                                        nomer_check_f = btoa(nomer_check)
                                        $.ajax({
                                            url: '" . base_url() . "master_project/MasterData/pre_activate_task/$produkID/'+nomer_check_f,
                                            success: function(a){
                                                var arrData = JSON.parse(a)
                                                if(arrData.status){
                                                    if(arrData.data[0].progress_id==2){
                                                        //jika data sudah memiliki pelaksana
                                                        var html = ''
                                                        var pelaksana     = arrData.data[0].employee_nama
                                                        var spk           = arrData.data[0].no_spk
                                                        var pre_spk       = arrData.data[0].no_pre_spk
                                                        var no_kontrak    = arrData.data[0].no_kontrak
                                                        var nama_task     = arrData.data[0].nama
                                                        var notes         = arrData.data[0].nilai
                                                        var nilai_task    = arrData.data[0].nilai_sub_fase
                                                        var nilai_kontrak = arrData.data[0].nilai_kontrak
                                                        var date_process  = arrData.data[0].date_process
                                                        var komposisi = arrData.data[0].komposisi
                                                        if(komposisi){
                                                            jQuery.each(komposisi, function(a, b){
                                                                console.log('komposisi')
                                                                console.log(a)
                                                                console.log(b)
                                                            })
                                                        }
                                                        BootstrapDialog.show({
                                                            title:'ACTIVATION '+nama_task,
                                                            message: $('<div></div>').html(\"<div class='box box-header box-danger box-solid text-center'> TASK INI SUDAH DI PROSES OLEH <span class='text-uppercase text-bold text-red'>\"+pelaksana+\"</span> pada \"+date_process+\" dengan NO SPK <b>\"+spk+\"</b></div>\"),
                                                            draggable:true,
                                                            closable:true,
                                                            size:top.BootstrapDialog.SIZE_WIDE,
                                                            onhide: function(){
                                                                top.$('#nomer_check').focus();
                                                                console.error('hide');
                                                            },

                                                            buttons: [{
                                                                label: 'Aktivasi & Lanjutkan',
                                                                cssClass: 'btn-default',
                                                                title: 'Activasi Pre SPK',
                                                                icon: 'fa fa-send',
                                                                action: function(dialog) {
                                                                    swal('On Progress...', 'Tidak bisa dilanjut, karena Task ini sudah ada yang mengerjakan..', 'warning')
                                                                }
                                                            }, {
                                                                label: 'Batal',
                                                                cssClass: 'btn-danger',
                                                                action: function(dialog) {
                                                                    dialog.close();
                                                                }
                                                            }]
                                                        });
                                                    }
                                                    else if(arrData.data[0].progress_id==1){
                                                        //jika data belum ada pelaksana dan bisa di lanjutkan
                                                        var html = ''
                                                        var pre_spk       = arrData.data[0].no_pre_spk
                                                        var no_kontrak    = arrData.data[0].no_kontrak
                                                        var nama_task     = arrData.data[0].nama
                                                        var notes         = arrData.data[0].nilai
                                                        var nilai_task    = arrData.data[0].nilai_sub_fase
                                                        var nilai_kontrak = arrData.data[0].nilai_kontrak
                                                        var date_process  = arrData.data[0].date_process
                                                        var komposisi     = arrData.data[0].komposisi
                                                        var this_id       = arrData.data[0].id
                                                        var produk_id     = arrData.data[0].produk_id
                                                        var fase_id       = arrData.data[0].fase_id

                                                        html += \"<div style='font-size: 18px;' class='box box-header box-danger box-solid'>\"
                                                        html += \"<div class='col-md-12 no-padding'>\"

                                                        html += \"<div class='col-md-2 no-padding'>\"
                                                        html += \"<span class='text-bold'>Nama</span>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='col-md-1'>\"
                                                        html += \"<span>: </span>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='col-md-9 no-padding'>\"
                                                        html += \"<span>\"+nama_task+\"</span>\"
                                                        html += \"</div>\"

                                                        html += \"<div class='col-md-2 no-padding'>\"
                                                        html += \"<span class='text-bold'>No Pre SPK</span>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='col-md-1'>\"
                                                        html += \"<span>: </span>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='col-md-9 no-padding'>\"
                                                        html += \"<span>\"+pre_spk+\"</span>\"
                                                        html += \"</div>\"

                                                        html += \"<div class='col-md-2 no-padding'>\"
                                                        html += \"<span class='text-bold'>No Kontrak</span>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='col-md-1'>\"
                                                        html += \"<span>: </span>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='col-md-9 no-padding'>\"
                                                        html += \"<span>\"+no_kontrak+\"</span>\"
                                                        html += \"</div>\"

                                                        html += \"</div>\"
                                                        html += \"</div>\"

                                                        if( typeof komposisi['room'] != 'undefined' ){
                                                            //separated room
                                                            html += \"<div class='box'>\"
                                                            html += \"<div class='box box-solid box-success'>\"
                                                            html += \"<div class='box-header text-bold text-uppercase'> \"+nama_task+\" </div>\"

                                                            jQuery.each(komposisi['room'], function(a, b){
                                                                var main_rid = b.room_id
                                                                html += \"<div style='margin-bottom: 25px;' class='container-fluid'>\"
                                                                html += \"<div class=''>\"
                                                                html += \"<table class='table dataTable table-bordered compact'>\"
                                                                html += \"<caption class='text-bold text-uppercase fa-2x'>\"+b.room_nama+\"</caption>\"
                                                                html += \"<thead>\"
                                                                html += \"<tr>\"
                                                                html += \"<th>No</th>\"
                                                                html += \"<th>Material</th>\"
                                                                html += \"<th>Jml</th>\"
                                                                html += \"<th>Satuan</th>\"
                                                                html += \"</tr>\"
                                                                html += \"</thead>\"
                                                                html += \"<tbody>\"
                                                                num_room=0;
                                                                jQuery.each(komposisi['produk_room'], function(a, b){
                                                                    if(main_rid==b.room_id){
                                                                        num_room++;
                                                                        html += \"<tr>\"
                                                                        html += \"<td>\"+num_room+\"</td>\"
                                                                        html += \"<td>\"+b.produk_dasar_nama+\"</td>\"
                                                                        html += \"<td>\"+b.jml+\"</td>\"
                                                                        html += \"<td>\"+b.satuan+\"</td>\"
                                                                        html += \"</tr>\"
                                                                    }
                                                                })
                                                                html += \"</tbody>\"
                                                                html += \"</table>\"
                                                                html += \"</div>\"
                                                                html += \"</div>\"
                                                            })
                                                            html += '</div>' //success
                                                            html += '</div>' //box
                                                        }
                                                        else{

                                                            //tanpa separated room
                                                            html += \"<div class='box'>\"
                                                            html += \"<div class='box box-solid box-success'>\"
                                                            html += \"<div class='box-header text-bold text-uppercase'> \"+nama_task+\" </div>\"

                                                            html += \"<div class='box-body'>\"
                                                            html += \"<table class='table dataTable table-bordered compact'>\"
                                                            html += \"<thead>\"
                                                            html += \"<tr>\"
                                                            html += \"<th>No</th>\"
                                                            html += \"<th>Material</th>\"
                                                            html += \"<th>Jml</th>\"
                                                            html += \"<th>Satuan</th>\"
                                                            html += \"</tr>\"
                                                            html += \"</thead>\"
                                                            html += \"<tbody>\"

                                                            num_room=0;
                                                            jQuery.each(komposisi['produk'], function(a, b){
                                                                num_room++;
                                                                html += \"<tr>\"
                                                                html += \"<td>\"+num_room+\"</td>\"
                                                                html += \"<td>\"+b.produk_dasar_nama+\"</td>\"
                                                                html += \"<td>\"+b.jml+\"</td>\"
                                                                html += \"<td>\"+b.satuan+\"</td>\"
                                                                html += \"</tr>\"
                                                            })

                                                            html += \"</tbody>\"
                                                            html += \"</table>\"

                                                            html += '</div>'
                                                            html += '</div>'
                                                            html += '</div>'

                                                        }

                                                        html += \"<SECTION>\"
                                                        html += \"  <DIV id='dropzone'>\"
                                                        html += \"    <FORM class='dropzone needsclick' id='demo-upload' action='/upload'>\"
                                                        html += \"      <DIV class='dz-message needsclick'>\"
                                                        html += \"        Seret photo ke dalam kotak ini atau klik upload.<BR>\"
                                                        html += \"        <SPAN class='note needsclick'>(untuk melanjutkan activasi Pre SPK,\"
                                                        html += \"        upload PHOTO lembar Pre SPK yang telah Anda Tanda Tangan.)</SPAN>\"
                                                        html += \"      </DIV>\"
                                                        html += \"    </FORM>\"
                                                        html += \"  </DIV>\"
                                                        html += \"</SECTION>\"

                                                        html += \"<DIV id='preview-template' style='display: none;'>\"
                                                        html += \"<DIV class='dz-preview dz-file-preview'>\"
                                                        html += \"<DIV class='dz-image'><IMG data-dz-thumbnail=''></DIV>\"
                                                        html += \"<DIV class='dz-details'>\"
                                                        html += \"<DIV class='dz-size'><SPAN data-dz-size=''></SPAN></DIV>\"
                                                        html += \"<DIV class='dz-filename'><SPAN data-dz-name=''></SPAN></DIV></DIV>\"
                                                        html += \"<DIV class='dz-progress'><SPAN class='dz-upload'\"
                                                        html += \"data-dz-uploadprogress=''></SPAN></DIV>\"
                                                        html += \"<DIV class='dz-error-message'><SPAN data-dz-errormessage=''></SPAN></DIV>\"
                                                        html += \"<div class='dz-success-mark'>\"
                                                        html += \"  <svg width='54px' height='54px' viewBox='0 0 54 54' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:sketch='http://www.bohemiancoding.com/sketch/ns'>\"
                                                        html += \"    <title>Check</title>\"
                                                        html += \"    <desc>Created with Sketch.</desc>\"
                                                        html += \"    <defs></defs>\"
                                                        html += \"    <g id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd' sketch:type='MSPage'>\"
                                                        html += \"        <path d='M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z' id='Oval-2' stroke-opacity='0.198794158' stroke='#747474' fill-opacity='0.816519475' fill='#FFFFFF' sketch:type='MSShapeGroup'></path>\"
                                                        html += \"    </g>\"
                                                        html += \"  </svg>\"
                                                        html += \"</div>\"
                                                        html += \"<div class='dz-error-mark'>\"
                                                        html += \"  <svg width='54px' height='54px' viewBox='0 0 54 54' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:sketch='http://www.bohemiancoding.com/sketch/ns'>\"
                                                        html += \"      <title>error</title>\"
                                                        html += \"      <desc>Created with Sketch.</desc>\"
                                                        html += \"      <defs></defs>\"
                                                        html += \"      <g id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd' sketch:type='MSPage'>\"
                                                        html += \"          <g id='Check-+-Oval-2' sketch:type='MSLayerGroup' stroke='#747474' stroke-opacity='0.198794158' fill='#FFFFFF' fill-opacity='0.816519475'>\"
                                                        html += \"              <path d='M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z' id='Oval-2' sketch:type='MSShapeGroup'></path>\"
                                                        html += \"          </g>\"
                                                        html += \"      </g>\"
                                                        html += \"  </svg>\"
                                                        html += \"</div>\"

                                                        var url_validasi_img = ''

                                                        BootstrapDialog.show({
                                                            title:'ACTIVATION '+nama_task,
                                                            message: $('<div></div>').html(html),
                                                            draggable:true,
                                                            closable:true,
                                                            size:top.BootstrapDialog.SIZE_WIDE,
                                                            buttons: [{
                                                                label: 'Aktivasi & Lanjutkan',
                                                                cssClass: 'btn-success',
                                                                title: 'Activasi Pre SPK',
                                                                icon: 'fa fa-send',
                                                                data: {
                                                                    js: 'btn-confirm',
                                                                    'user-id': '3'
                                                                },
                                                                action: function(dialog) {
                                                                    swal({
                                                                        title: nama_task,
                                                                        html: 'klik ok untuk melanjutkan...!!<br><br><img width=\'125\' src=\''+localStorage.url_validasi_img+'\'>',
                                                                    })
                                                                    .then( (res)=>{

                                                                        if(localStorage.url_validasi_img!=''){
                                                                            $.ajax({
                                                                                url: '" . base_url() . "master_project/MasterData/exe_activate_task',
                                                                                method: 'post',
                                                                                data: {id:this_id, produk_id:produk_id, pre_spk:pre_spk, fase_id:fase_id, pre_spk_validasi_img:localStorage.url_validasi_img},
                                                                                success: function(aaa){
                                                                                    var arrData = JSON.parse(aaa);
                                                                                    if(arrData.status){
                                                                                        swal('sukses', 'Pre SPK berhasil di aktivasi, anda bisa melakukan progress pada table dibawah.')
                                                                                        swal.enableLoading();
                                                                                        setTimeout(function(){
                                                                                            top.BootstrapDialog.closeAll();
                                                                                            swal.close();
                                                                                            setTimeout(function(){
                                                                                                //top.window.location.reload();
                                                                                            }, 200)
                                                                                        }, 500)
                                                                                    }
                                                                                    else{
                                                                                        swal('DEBUG:sukses', 'Pre SPK berhasil di aktivasi, anda bisa melakukan progress pada table dibawah.')
                                                                                        swal.enableLoading();
                                                                                        setTimeout(function(){
                                                                                            top.BootstrapDialog.closeAll();
                                                                                            swal.close();
                                                                                            setTimeout(function(){
                                                                                                //top.window.location.reload();
                                                                                            }, 200)
                                                                                        }, 500)
                                                                                    }
                                                                                }
                                                                            })
                                                                        }
                                                                        else{
                                                                            swal('link upload image rusak, silahkan hapus dan reupload photo');
                                                                        }


                                                                    })
                                                                }
                                                            }, {
                                                                label: 'Batal',
                                                                cssClass: 'btn-danger',
                                                                action: function(dialog) {
                                                                    dialog.close();
                                                                }
                                                            }],
                                                            onshown: function(){
                                                                top.$(\"button[data-js='btn-confirm']\").prop('disabled', true);

//                                                                console.log('this_id: ' + this_id);
//                                                                console.log('produk_id: ' + produk_id);

                                                                var dropzone = new Dropzone('#demo-upload', {
                                                                    acceptedFiles: '.jpg,.png,.jpeg',
                                                                    addRemoveLinks: true,
                                                                    maxFilesize: 10,
                                                                    maxFile: 1,
                                                                    parallelUploads: 2,
                                                                    thumbnailHeight: 120,
                                                                    thumbnailWidth: 120,
                                                                    filesizeBase: 1000,
                                                                    dictFileTooBig: 'File terlalu besar ({{filesize}}MiB). Max ukuran: {{maxFilesize}}MiB.',
                                                                    forceFallback: false, //untuk switch ke metode upload JADUL
                                                                    dictDefaultMessage: \"Klik atau Drag Drop File di sini untuk Upload\",
                                                                    dictRemoveFile: 'hapus',
                                                                    dictRemoveFileConfirmation: 'Anda yakin menghapus photo ini..??',
                                                                    dictInvalidFileType: \"Gagal Upload, Pastikan file berjenis gambar.\",
                                                                    url: '$linkUploadPhoto'+this_id+'/'+produk_id,
                                                                    thumbnail: function(file, dataUrl) {
                                                                        if (file.previewElement) {
                                                                          file.previewElement.classList.remove('dz-file-preview');
                                                                          var images = file.previewElement.querySelectorAll('[data-dz-thumbnail]');
                                                                          for (var i = 0; i < images.length; i++) {
                                                                            var thumbnailElement = images[i];
                                                                            thumbnailElement.alt = file.name;
                                                                            thumbnailElement.src = dataUrl;
                                                                          }
                                                                          setTimeout(function() { file.previewElement.classList.add('dz-image-preview'); }, 1);
                                                                        }
                                                                    },
                                                                    params(files, xhr, chunk) {
                                                                        $('.def-images').addClass('hidden');
                                                                        var uuid = files[0]['upload']['uuid'];
                                                                        var tmpImg = {}
//                                                                        console.log('uuid: '+ uuid);
                                                                        xhr.onreadystatechange = function() {
                                                                            if (xhr.readyState == XMLHttpRequest.DONE) {
                                                                                var callBack = JSON.parse(xhr.responseText)
                                                                                var id = callBack.id
                                                                                var produk_id = callBack.produk_id
                                                                                var cdn_url = callBack.uploaded.full_url

                                                                                tmpImg[uuid] = {
                                                                                    uuid,id,produk_id, cdn_url
                                                                                }

                                                                                localStorage.url_validasi_img = cdn_url

                                                                                top.$(\"button[data-js='btn-confirm']\").prop('disabled', false);

//                                                                                console.log('cdn_url: ' + cdn_url);
//                                                                                console.log('callBack: ');
//                                                                                console.log(callBack);
//                                                                                console.log('tmpImg: ');
//                                                                                console.log(tmpImg);

                                                                                //imgBankCtr(tmpImg,'','add')
                                                                                //imgCheckBtn(id,produk_id,'');
                                                                            }
                                                                        }
                                                                    },
                                                                    removedfile(file) {
                                                                        if (file.previewElement != null && file.previewElement.parentNode != null) {
                                                                            file.previewElement.parentNode.removeChild(file.previewElement);
                                                                        }
                                                                        var removeUuid = file.upload.uuid;

                                                                        localStorage.url_validasi_img = ''
                                                                        top.$(\"button[data-js='btn-confirm']\").prop('disabled', true);

                                                                        // imgBankCtr(removeUuid,'','del')
                                                                        // imgCheckBtn(this_id,produk_id,'');
                                                                    },
                                                                    error(file, message) {
                                                                        if (file.previewElement) {
                                                                              file.previewElement.classList.add('dz-error');
                                                                              if (typeof message !== 'string' && message.error) {
                                                                                    message = message.error;
                                                                              }
                                                                              for (let node of file.previewElement.querySelectorAll(
                                                                                    '[data-dz-errormessage]'
                                                                              )) {
                                                                                    node.textContent = message;
                                                                              }
                                                                        }
                                                                    },
                                                                });
                                                            },
                                                        });
                                                    }
                                                    else{

//                                                        var html = ''
//                                                        var pre_spk = arrData.data[0].no_pre_spk
//                                                        var no_kontrak = arrData.data[0].no_kontrak
//                                                        var nama_task = arrData.data[0].nama
//                                                        var notes = arrData.data[0].nilai
//                                                        var nilai_task = arrData.data[0].nilai_sub_fase
//                                                        var nilai_kontrak = arrData.data[0].nilai_kontrak
//                                                        var date_process = arrData.data[0].date_process
//                                                        var komposisi = arrData.data[0].komposisi
//                                                        if(komposisi){
//                                                            jQuery.each(komposisi, function(a, b){
//                                                                console.log('komposisi')
//                                                                console.log(b)
//                                                            })
//                                                        }
//                                                        BootstrapDialog.show({
//                                                            title:'ACTIVATION '+nama_task,
//                                                            message: $('<div></div>').html(\"<div class='box box-header box-danger box-solid text-center'> TASK INI SUDAH DI PROSES OLEH <span class='text-uppercase text-bold text-red'>\"+pelaksana+\"</span> pada \"+date_process+\" dengan NO SPK <b>\"+spk+\"</b></div>\"),
//                                                            draggable:true,
//                                                            closable:true,
//                                                            size:top.BootstrapDialog.SIZE_WIDE,
//                                                        });

                                                        console.log('TRAX MODE INI TIDAK ADA DALAM SKENARIO');
                                                        swal('TRAX MODE INI TIDAK ADA DALAM SKENARIO')
                                                    }
                                                }
                                                else{
                                                    swal('NOMER PRE SPK<br><b>' +nomer_check+ '</b> <br><r>TIDAK DI TEMUKAN...</r>');
                                                }
                                            }
                                        })
                                    }
                                    else{
                                        swal('harap mengisi nomor Pre SPK kemudian klik SEND.');
                                    }
                                }
                                top.moment.locale('id');
                            </script>
                            <script>
                                var dft_tugas = top.$('#daftar_tugas_').DataTable({
                                    serverSide: true,
                                    ajax: '" . base_url() . "master_project/MasterData/daftar_tugas/tasklist/$produkID',
                                    autoWidth: false,
                                    processing: true,
                                    $searching,
                                    $paging,
                                    $info,
                                    $order,
                                    $columnDefs,
                                    initComplete: function(a, b){
//                                        var pendingTask = b.pendingtask.length;
//                                        $('#pendingtask').html(pendingTask);
                                    }
                                });
                             </script>";

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

        if (count($workOrder) > 0) {
            $workOrder = "";
            $workOrder .= "<div class='blink text-bold text-info box-header'><h3>$workOrderTitle#</h3></div>";
            $workOrder .= "<div class='box-body no-paddingx table-responsivex'>";
            $workOrder .= "<form id='tasklist_form' name='tasklist_form' target='result' action='$addtaskLink?$targetResult'>";

            $workOrder .= "<div class=''>";
            $workOrder .= "<label class='box-title'>Pilih Project: &nbsp;</label>";
            $workOrder .= $formWork_order;
            $workOrder .= "</div>";

            $workOrder .= "<div id='sub_fase_selector' style='margin-top: 6px;' class='hidden'>";
            $workOrder .= "</div>";

            $workOrder .= "<div style='margin-top: 6px;' class='row'>";
            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<label class=''>Tugas: &nbsp;</label>";

            $default_nama = isset($sessionData[$produkID]["nama"]) ? $sessionData[$produkID]["nama"] : "";

            $workOrder .= "<input id='in_tugas' type='text' class='form-control form-control-sm' value ='" . $default_nama . "'onmouseoutx=\"$('#input_temp').load('$selector" . "$produkID?key=nama&value='+encodeURI(this.value)+'$targetResult');\">";
            $workOrder .= "</div>";
            $workOrder .= "<div class='col-md-6' class='' stylex='right: 10px;'>";
            $workOrder .= "<label class=''>Keterangan: &nbsp;</label>";

            $defaultValue = isset($sessionData[$produkID]["nilai"]) ? $sessionData[$produkID]["nilai"] : "";

            $workOrder .= "<textarea id='in_keterangan' rows=\"5\" cols=\"33\" type='textarea' class='form-control' onmouseoutx=\"$('#input_temp').load('$selector" . "$produkID?key=nilai&value='+encodeURI(this.value)+'$targetResult');\">$defaultValue</textarea>";
            $workOrder .= "</div>";
            $workOrder .= "</div>";

            $workOrder .= "<div style='margin-top: 6px;' class=''>";
            $workOrder .= "<label>Pelaksana: &nbsp;</label>";
            $workOrder .= $formWork_tim;
            $workOrder .= "</div>";

            $workOrder .= "<div style='margin-top: 6px;' class='row'>";
            $workOrder .= "<div class='col-md-6'>";
            $workOrder .= "<div class='form-group'>";
            $workOrder .= "<label>Mulai: &nbsp;</label>";
            $workOrder .= "<div class='input-group date'>";
            $workOrder .= "<div class='input-group-addon'>";
            $workOrder .= "<i class='fa fa-calendar'></i>";
            $workOrder .= "</div>";

            $defaultStartDate = isset($sessionData[$produkID]["dtime_start"]) ? date("Y-m-d", strtotime($sessionData[$produkID]["dtime_start"])) : "";

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

            $defaultEndDate = isset($sessionData[$produkID]["dtime_end"]) ? date("Y-m-d", strtotime($sessionData[$produkID]["dtime_end"])) : "";

            $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_end' value='$defaultEndDate' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_end&value='+encodeURI(this.value)+'$targetResult');\"> ";
            $workOrder .= "</div>";
            $workOrder .= "</div>";
            $workOrder .= "</div>";

            $workOrder .= "<div id='container_material' class='col-md-12'>";
            $workOrder .= "&nbsp;";
            $workOrder .= "</div>";

            $workOrder .= "<div style='margin-top: 35px;' class='col-md-12'>";
            $workOrder .= "<button id='simpanTugas' type='button' class='btn btn-block btn-success btn-sm' cx_onclick=\"document.getElementById('tasklist_form').submit();\">Simpan</button>";
            $workOrder .= "</div>";

            $workOrder .= "</div>";

            $workOrder .= "</form>";
            $workOrder .= "</div>";

            $workOrder .= "<div id='input_temp'></div>";

            $workOrder .= "
            <script>\n

                function init_inTugas(){
                    $('#in_tugas').on('keyup', delay_v2(function(){
                        $('#input_temp').load('$selector" . "$produkID?key=nama&value='+encodeURI(this.value)+'');
                    }, 1200))
                }
                function init_inKeterangan(){
                    $('#in_keterangan').on('keyup', delay_v2(function(){
                        $('#input_temp').load('$selector" . "$produkID?key=nilai&value='+encodeURI(this.value)+'');
                    }, 1200))
                }

                init_inTugas();
                init_inKeterangan();
                function init_simpanTugas(){
                    top.$('#simpanTugas').off();
                    top.$('#simpanTugas').on('click', function(){
                        var id = $(this).attr('id');
                        var ev_aa = $(this).attr('cx_onclick');
                        var arr_input = $('.'+id);
                        var error = '';

                        var fase_id         = $('#fase_id option:selected').val();
                        var sub_fase        = $('#sub_fase option:selected').val();
                        var employee_id     = $('#employee_id option:selected').val();
                        var in_tugas        = $('#in_tugas').val();
                        var in_keterangan   = $('#in_keterangan').val();
                        var date_end        = $('#date_end').val();
                        var date_start      = $('#date_start').val();

                        if(fase_id*1 <= 0){
                            //console.error('waduh fase_id*1 > 0 yaitu: ' + fase_id);
                            error += '<div> <b>Perintah Kerja</b> Wajib di isi... </div>'
                        }

                        if(sub_fase*1 <= 0){
                            //console.error('waduh sub_fase*1 > 0 yaitu: ' + sub_fase);
                            error += '<div> <b>Sub Pekerjaan</b> Wajib di isi... </div>'
                        }

                        if(employee_id*1 <= 0){
                            //console.error('waduh employee_id*1 > 0 yaitu: ' + employee_id);
                            error += '<div> <b>Pelaksana</b> Wajib di pilih... </div>'
                        }

                        if(in_tugas==''){
                            //console.error('waduh in_tugas kosong');
                            error += '<div> <b>Tugas</b> Wajib di isi... </div>'
                        }

                        if(in_keterangan==''){
                            //console.error('waduh in_keterangan kosong');
                            error += '<div> <b>Keterangan</b> Wajib di isi... </div>'
                        }

                        if(date_end==''){
                            //console.error('waduh date_end kosong');
                            error += '<div> <b>Waktu Selesai</b> Wajib di isi... </div>'
                        }

                        if(date_start==''){
                            //console.error('waduh date_start kosong');
                            error += '<div> <b>Waktu Mulai</b> Wajib di isi... </div>'
                        }

                        if( error=='' ){
                            //bisa lanjut
                            eval(ev_aa);
                        }
                        else{
                            swal('isian kurang', error, 'error');
                        }
                    });
                }

                init_simpanTugas();

            </script>

            ";

        }

        $projectDataDetails = "
            <div class='text-bold text-primary fa-2x text-center'>
                <u><i>TASK LIST TIMELINE</i></u> &nbsp;&nbsp;
                <span id='btnReloadTimeLine' class='btn btn-sm btn-warning'>
                    Reload &nbsp;&nbsp; <i class='fa fa-refresh'></i>
                </span>
            </div>

            <div style='overflow: hidden;'>
                <table id='tableTimeLine' style='overflow: hidden;' class='table dataTable compact table-bordered table-condensed'>
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>Ket</th>
                            <th>Pekerjaan</th>
                            <th>Person</th>
                            <th>-</th>
                            <!-- <th>Employee</th>
                            <th>Status</th>
                            <th>Status</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <script>\n

                var timeline = $('#tableTimeLine').DataTable({
                    ajax: '$timelinetaskLink',
                    paging: false,
                    info: false,
                    processing: true,
                    searching: false,
                    ordering: false,
                    scrollCollapse: false,
                    scrollY: '200px',
                    scroller: {
                        serverWait: 500
                    },
                    columnDefs:[
                        {
                            targets: 0,
                            class: 'text-left',
                            width: '1%',
                            render: function(data, type, row, meta){
                                var input = ''
                                input += \"<span class='btn btn-xs'>\"+moment(data).format('YYYY-MM-DD HH:mm')+\"</span>\"
                                input += '<br><small><i><r>'+top.moment(data, 'YYYYMMDD HHmmss').fromNow()  + '</r></i></small>'
                                return input;
                            }
                        },
                        {
                            targets: 1,
                            class: 'text-left',
                            width: '1%',
                            render: function(data, type, row, meta){
                                var input = ''
                                if(data=='QC PROSES'){
                                    input += \"<span class='btn btn-xs bg-violet'>\"+data+\"</span>\"
                                }
                                else if(data=='create'){
                                    input += \"<span class='btn btn-xs bg-lime'>\"+data+\"</span>\"
                                }
                                else if(data=='update'){
                                    input += \"<span class='btn btn-xs bg-yellow'>\"+data+\"</span>\"
                                }
                                else if(data=='subtasklist'){
                                    input += \"<span class='btn btn-xs bg-blue'>\"+data+\"</span>\"
                                }
                                else if(data=='trashed_tmb'){
                                    input += \"<span class='btn btn-xs bg-red'>\"+data+\"</span>\"
                                }
                                else if(data=='trashed'){
                                    input += \"<span class='btn btn-xs bg-red'>\"+data+\"</span>\"
                                }
                                else{
                                    input += \"<span class='btn btn-xs btn-default'>\"+data+\"</span>\"
                                }
                                return input;
                            }
                        },
                        {
                            targets: 2,
                            class: 'text-left',
                            width: '1%'
                        },
                        {
                            targets: 3,
                            class: 'text-left',
                            width: '1%'
                        },
                        {
                            targets: 4,
                            class: 'text-left',
                            render: function(data, type, row, meta){
                                var input = ''
                                if(row[1]=='QC PROSES'){
                                    input += \"Melakukan QC pada Pekerjaan, \"
                                    input += \"<span class=''> dengan No SPK: <br><b>\"+row[5]+\"</b></span>\"
                                }
                                else if(row[1]=='create'){
                                    input += \"Membuat SPK untuk \"
                                    input += \"<span class='text-red text-bold text-uppercase'>\"+data+\"</span>\"
                                    input += \"<span class=''> Dengan No SPK: <br><b>\"+row[5]+\"</b></span>\"
                                }
                                else if(row[1]=='update'){
                                    input += \"Menerima & Memproses SPK, \"
                                    input += \"<span class=''> No SPK: <br><b>\"+row[5]+\"</b></span>\"
                                }
                                else if(row[1]=='subtasklist'){
                                    input += \"Menyelesaikan Tasklist/WO, \"
                                    input += \"<span class=''> dengan Nomer TASK: <br><b>\"+row[6]+\"</b></span>\"
                                }
                                else if(row[1]=='trashed_tmb'){
                                    input += \"Menghapus Tasklist/WO Tambahan, \"
                                    input += \"<span class=''> dengan Nomer TASK: <br><b>\"+row[5]+\"</b></span>\"
                                }
                                else if(row[1]=='trashed'){
                                    input += \"Menghapus Tasklist/WO, \"
                                    input += \"<span class=''> dengan Nomer TASK: <br><b>\"+row[5]+\"</b></span>\"
                                }
                                else{
                                    input += \"<span class='btn btn-xs btn-default'>\"+data+\"</span>\"
                                }
                                return input;
                            }
                        },
                    ],
                    initComplete: function(){
                        //console.log('last update table: ' + new Date());
                    },
                    rowCallback: function( row, data, displayNum, displayIndex, dataIndex ){
                        //console.log(row)
                    }
                });\n

                $('#btnReloadTimeLine').on('click', function(){
                    timeline.ajax.reload();
                });\n

            </script>
        ";

        // $img = img_maintenace();
        // $txt = dtimeNow('d F Y H:i');
        // $linkHome = base_url();
        // $txt .= " <br>Kembali ke <a href=\'$linkHome\'>HOME</a>";
        //
        // $projectDataDetails .= "<script>
        //     swal({
        //         // title:'ssss',
        //         html:'$txt',
        //         imageUrl: '$img',
        //         showConfirmButton: false,
        //         allowOutsideClick: false,
        //     });
        // </script>";

        // $img = img_maintenace();
        // $txt = dtimeNow('d F Y H:i');
        // $linkHome = base_url();
        // $txt .= " <br>Kembali ke <a href=\'$linkHome\'>HOME</a>";
        //
        // $projectDataDetails .= "<script>
        //     swal({
        //         // title:'ssss',
        //         html:'$txt',
        //         imageUrl: '$img',
        //         showConfirmButton: false,
        //         allowOutsideClick: false,
        //     });
        // </script>";

//    $scriptBottom .= "\n<script> top.$(\".selectpicker\").selectpicker('refresh');top.init_tab(); </script>";
//        $scriptBottom .= "\n" . $scriptBottomCtr;
//        arrPrintWebs($sessionData);
//        arrPrintWebs($cCode);
        $taskListNew = "";
//        $taskListNew = "
//            <div class='col-md-7'>
//                <div class='box box-success'>$workOrder</div>
//            </div>
//        ";
        $linkTambahTugas = "
                    BootstrapDialog.show({
                        title:'BUAT PENUGASAN BARU',
                        message: $('<div></div>').load('" . base_url() . "master_project/MasterData/penugasan/$produkID?debuger=0'),
                        draggable:true,
                        closable:true,
                        size:top.BootstrapDialog.SIZE_WIDE,
                    });
                ";

        $belumStartProject = "<div style='margin-top: 22px;' class='box-header bg-orange box-solid text-center text-bold fa-2x'><i class='fa fa-warning text-white blink'></i> SETELAH QUOTATION/BOM DI APPROVE, ANDA BISA MENAMBAHKAN TUGAS / SPK DISINI. <i class='fa fa-warning text-white blink'></i></div>";
        $belumStartProject .= "<div style='margin-top: 5px;' class='box-header bg-info box-solid text-center text-bold'><i><u><r> SILAHKAN MENGHUBUNGI ATASAN ANDA, AGAR MEMPERCEPAT PROSES PENGECEKAN DAN APPROVAL </r></u></i><br><div><a href='" . base_url() . "master_project/Transaksi/index/588'>ATAU KLIK DISINI</a></div></div>";

        $btnTambahTugas = "";


        $btnTambahTugas .= "<span class='pull-right'><span onclick=\"$linkTambahTugas\" class='btn btn-info btn-md btn_tambah_tugas'>TAMBAH TUGAS</span></span>";

        $p->addTags(array(
            "taskListNew" => $taskListNew,
            "btnTambahTugas" => $btnTambahTugas,
//            "taskListNew"        => $taskListNew,
//            "workOrder"          => $workOrder,
//            "workOrder"          => "workOrder",
            "tasklist" => $taskListData, //daftar tugas <== dimatikan dulu belum di perlukan
//            "tasklist"           => "", //daftar tugas
//            "tasklist"           => "", //daftar tugas
            "timWork" => "",
            "projectData" => "", //$project
            "projectDataDetails" => $projectDataDetails,
//            "projectDataDetails" => "",
//            "content"            => $strMain,
            "content" => "",
            //"workOrder"          => $workOrder,
            "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
            "scriptBottomCtr" => $scriptBottomCtr,
            "display_iframe" => "none",//biar gak panjang
            "error_msg" => isset($error) ? $error : "",
            "menu_left" => callMenuLeft(),
            "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "ci_session" => $ci_session,
            "class_projectSudahStart" => $produkID=="qc" ? "" : ($projectStart ? "" : "hidden"),
            "class_projectBelumStart" => $projectStart ? "hidden" : "",
            "projectBelumStart" => "$belumStartProject",
//            "allowCreateTugas" => "$allowCreateTugas",
        ));

        $p->render();
        break;
    case "barcodeView":
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/modalBarcode.html");
        $p->addTags(array(
            "content" => $content,
            "jsBottom" => $jsBottom,
        ));

        $p->render();
        break;
}