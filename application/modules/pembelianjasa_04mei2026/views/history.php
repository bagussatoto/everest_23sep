<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */
$segment_uri_array = $this->uri->segment_array();
$segmenUri = implode("/", $segment_uri_array);
$segmenUriEnc = implode("_", $segment_uri_array);

$getSearch = isset($_GET['search']) ? $_GET['search'] : "";

$btnNavigasi = "";
switch ($mode) {
    case "viewStatus":
    case "viewHistory":
    case "viewHistoryOld":
//    arrPrint($segment_uri_array);
        //region btnNavigasi
        $uri_1 = $segment_uri_array['1'];
        $uri_2 = $segment_uri_array['2'];
        $uri_3 = $segment_uri_array['3'];
        $uri_4 = $segment_uri_array['4'];
        $date1 = isset($_GET['date1']) ? "?date1=" . $_GET['date1'] : "";
        $date2 = isset($_GET['date2']) ? "&date2=" . $_GET['date2'] : "";
        $metodes = array(
            "viewHistory" => "Data Baru",
            "viewHistoryOld" => "Data Lama",
        );
//        $navigasies[base_url() . $uri_1 . "/$uri_2" . "/viewHistory/" . $uri_3] = "Baru";
//        $navigasies[base_url() . $uri_1 . "/$uri_2" . "/viewHistoryOld/" . $uri_3] = "Lama";
        $navigasies[base_url() . $uri_1 . "/$uri_2/" . $uri_3] = "Baru";
        $navigasies[base_url() . $uri_1 . "/$uri_2/" . $uri_3] = "Lama";

        $btnNavigasi = "";
        // foreach($navigasies as $navLink => $navLabel){
        foreach ($metodes as $navMetode => $navLabel) {
//            $navLink = base_url() . $uri_1 . "/$navMetode/" . $uri_3 . $date1 . $date2;
            $navLink = base_url() . $uri_1 . "/$uri_2/$navMetode/$uri_4/" . $date1 . $date2;
            $btnDisabled = $navMetode == $uri_2 ? "disabled" : "";
            $btnNavigasi .= "<button type='button' class='btn btn-info' $btnDisabled onclick=\"location.href='$navLink';\"><i class='fa fa-history'>&nbsp; $navLabel</i></button> ";
        }
        if (isset($_GET['date1'])) {
            $date1_f = formatField("fulldate", $_GET['date1']);
            $date2_f = formatField("fulldate", $_GET['date2']);
            $dateRange = $date1_f . " - " . $date2_f;
        }
        else {
            $dateRange = "";
        }
        // $subTitle = "<h4 class='no-padding no-margin text-capitalise'>".$subTitle . " $dateRange </h4>";
        $subTitle = $subTitle . " $dateRange";
        // $filters['date1'] = "2019-10-9";
        //endregion
        break;
}
//region seraching entry

$date1 = isset($date1) ? $date1 : "";
$date2 = isset($date2) ? $date2 : "";
$acturi = base_url() . $segmenUri . $date1 . $date2;

$searching = "<form method='get' action='$acturi' class='margin-top-10'>";
$searching .= "<div class='input-group input-group-sm'>";
$searching .= "<input type='text' name='search' value='$getSearch' onclick=\"this.select();\" class='form-control' placeholder='Search by customer or PIC name'>";

if (strlen($date1) > 0) {
    $searching .= "<input type='text' name='date1' value='" . $_GET['date1'] . "' onclick=\"this.select();\" class='form-control hidden' placeholder=''>";
}
if (strlen($date2) > 0) {
    $searching .= "<input type='text' name='date2' value='" . $_GET['date2'] . "' onclick=\"this.select();\" class='form-control hidden' placeholder=''>";
}
// $searching .= "<input type='hidden' name=''>";
$searching .= "<span class='input-group-btn'>
                          <button type='submit' class='btn btn-info'><i class='fa fa-search'></i></button>
                        </span>";
$searching .= "</div>";
$searching .= "</form>";
//endregion
$btnNavigasi .= $searching;

switch ($mode) {
    case "viewHistory":
        $contens = "";
        $custom_button = "";

        if (isset($customButton)) {
            if (sizeof($customButton) > 0) {
                foreach ($customButton as $ky => $preg) {
                    foreach ($preg as $k => $mbuh) {
                        $$k = $preg[$k];
                    }
                    $custom_button .= "{text:'$label',action:function(e,dt,node,config){window.open('" . base_url() . $target . $customButtonTarget . "','download');}},";
                    // $custom_button .= "{text:'$label',action:function(e,dt,node,config){window.open('".base_url().$target.$uri_4."?date1=$date1&date2=$date2','download');}},";
                }
            }
        }

        $cf_button = $this->config->item('button');
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;
        $arrayHistoryCanceleds = isset($arrayHistoryCanceled) ? $arrayHistoryCanceled : array();

        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");

            foreach ($stepLabels as $step => $label) {
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }


        $content .= ("<div class=\"box-body\">");
        if (sizeof($arrayHistory) > 0) {
//            $content .= ("<form name='fSelect'>");
            $content .= "<div class='table-responsive table_history_$segmenUriEnc'>";
            $content .= "<table id='table_history_$segmenUriEnc' class='table display no-padding'>";

            //region thead
            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#f0f0f0'>");

            foreach ($arrayHistoryLabels as $key => $label) {
                // arrPrint($label);
                $content .= ("<th class='text-uppercase text-muted text-center'>");
                if (is_array($label)) {
                    $content .= ($label['label']);
                }
                else {
                    $content .= ($label);
                }
                $content .= ("</th>");
            }


            $content .= ("</tr>");
            $content .= ("</thead>");

            //endregion head

            //region tbody
            $content .= ("<tbody>");
            $rowCtr = 0;
            foreach ($arrayHistory as $ii => $row) {

                $rslt_style = "";
                $cancel = false;
                if (isset($history_canceled[$ii]) && (sizeof($history_canceled[$ii]) > 0)) {
                    foreach ($history_canceled[$ii] as $hist_cancel) {
                        if ($rslt_style == "") {
                            $rslt_style = $hist_cancel;
                        }
                        else {
                            $rslt_style = $rslt_style . $hist_cancel;
                        }
                    }
                    $cancel = true;
                }
                if (isset($history_keterangan[$ii]) && (sizeof($history_keterangan[$ii]) > 0)) {
                    if ($cancel == false) {

                        foreach ($history_keterangan[$ii] as $hist_keterangan) {

                            if ($rslt_style == "") {
                                $rslt_style = $hist_keterangan;
                            }
                            else {
                                $rslt_style = $rslt_style . $hist_keterangan;
                            }
                        }
                    }
                }

                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr' style='$rslt_style'>");
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");
            }
            $content .= ("</tbody>");
            //endregion tbody

            //region tfoot
            $content .= ("<tfoot>");
            $content .= "<tr class='tfooter bg-grey-1'>";
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th>");
                if (isset($arrayHistorySumField) && isset($arrayHistorySumField[$key])) {
                    $content .= formatField($key, $arrayHistorySumField[$key]);
                }
                else {
                    $content .= "--";
                }
                $content .= ("</th>");
            }
            $content .= "</tr>";
            $content .= ("</tfoot>");
            //endregion tfoot
            $content .= "</table class='table table-bordered'>";
            $content .= "</div class='table-responsive'>";
//            $content .= ("</form>");

        }
        else {
            $content .= "no history found.<br>";
            $content .= "to go back to index, you can click BACK button<br>";
        }
        $content .= "</div>";

        $content .= "<script>
                    $(document).ready( function(){
                        var table = $('#table_history_$segmenUriEnc').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            stateSave: true,
                            processing: true,
                            searchDelay: 1500,
                            search: {
                                smart: false
                            },

                            buttons: [
                                        { extend: 'print', footer: true },
                                        {
                                            text: 'Download Excel',
                                            action: function (e, dt, node, config) {
                                                fnExcelReport('table_history_$segmenUriEnc');
                                            }
                                        },
                                        $custom_button
                                    ],



                            footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                                        var arrayFooter = $('#table_history_$segmenUriEnc>tfoot>tr>th');
                                        var dpageTotal = [];
                                        jQuery.each(arrayFooter, function(i,d){

                                            var id_n_index = parseFloat(i);
                                            dpageTotal[id_n_index] = 0;

                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                var pos = obj.indexOf('<');
                                                var hr = obj.indexOf('<hr>');
                                                if(pos!==-1&&hr==-1&&id_n_index>0){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] >= 0 ){
                                            $( api.column(id_n_index).footer() ).html(
                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                            );
                                        }
                                        });
                                    }
                                });


                                //new $.fn.dataTable.FixedHeader( table );
                                $('.table-responsive.table_history_$segmenUriEnc').floatingScroll();
                                $('.table-responsive.table_history_$segmenUriEnc').scroll(function() {
                                    setTimeout(function () {
                                        $('#table_history_$segmenUriEnc').DataTable().fixedHeader.adjust();
                                    }, 100);
                            });
                            });
                    </script>";


        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "/template/history.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
// cekLime($thisPage);
        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => isset($filters['dates']['start']) ? $filters['dates']['start'] : "",
            "date_max" => isset($filters['dates']['end']) ? $filters['dates']['end'] : "",
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "getSearch" => strlen($getSearch) > 0 ? "&search=" . $getSearch : "",
        ));

        $p->render();
        break;
    case "showData":

        $contens = "";
        $custom_button = "";
        if (isset($customButton)) {
            if (sizeof($customButton) > 0) {
                foreach ($customButton as $ky => $preg) {
                    foreach ($preg as $k => $mbuh) {
                        $$k = $preg[$k];
                    }
                    $custom_button .= "{text:'$label',action:function(e,dt,node,config){window.open('" . base_url() . $target . $customButtonTarget . "','download');}},";
                }
            }
        }
        $cf_button = $this->config->item('button');
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;
        $arrayHistoryCanceleds = isset($arrayHistoryCanceled) ? $arrayHistoryCanceled : array();

        $link_history = MODUL_PATH . "History/viewHistory/$jenisTr";
        $content = "";
        $content .= "<div class='box box-solid box-success no-margin'>";

        $content .= "<div class='box-header text-uppercase'>";
        $content .= "<h4 class='box-title'>Riwayat transaksi</h4>";
        $content .= "<div class='box-tools pull-right'>";
        $content .= "<button type='button' class='btn btn-box-tool' title='melihat seluruh history' onclick=\"location.href='$link_history'\"><i class='fa fa-list'></i></button>";
        $content .= "</div>";
        $content .= "</div>";

        $content .= "</div>";
        /* --------------------------------------
         * step TAB
         * --------------------------------------*/
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= "<ul class='nav nav-tabs'>";
            foreach ($stepLabels as $step => $label) {
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";

                $linkStep = $stepLinks[$step];
                $content .= "<li class='nav-item'>";

                $varUrl = $steps[$step]['target'] == $currentState ? "javascript:void(0);" : "if($('#historyList>div.box-body')){ $('#historyList>div.box-body').append(`<div id='overlay'><div id='text'>Loading Content...</div></div>`); document.getElementById('overlay').style.display = 'block'; $('#historyList').load('$linkStep'); }else{ console.log('tidak ada container undoneList'); }";

                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"$varUrl\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= "</li>";
            }
            $content .= "</ul class='pager'>";
        }

        /* --------------------------------------
         * body isi
         * --------------------------------------*/
        $content .= "<div class='box-body'>";
        if (sizeof($arrayHistory) > 0) {
//            $content .= "<form name='fSelect'>";
            $content .= "<div class='table-responsive data_history_$jenisTr'>";
            $content .= "<table class='table display no-padding' id='data_history_$jenisTr'>";
            //region thead
            $content .= "<thead>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= "<th class='text-uppercase text-muted text-center'>";
                if (is_array($label)) {
                    $content .= $label['label'];
                }
                else {
                    $content .= $label;
                }
                $content .= "</th>";
            }
            $content .= "</tr>";
            $content .= "</thead>";
            //endregion head

            //region tbody
            $content .= ("<tbody>");
            $rowCtr = 0;
            foreach ($arrayHistory as $ii => $row) {

                $rslt_style = "";
                if (isset($history_canceled[$ii]) && (sizeof($history_canceled[$ii]) > 0)) {
                    //                    arrPrintWebs($history_canceled[$ii]);
                    foreach ($history_canceled[$ii] as $hist_cancel) {
                        if ($rslt_style == "") {
                            $rslt_style = $hist_cancel;
                        }
                        else {
                            $rslt_style = $rslt_style . $hist_cancel;
                        }
                    }
                }
                //cekHere($rslt_style);
                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr' style='$rslt_style'>");
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");
            }
            $content .= ("</tbody>");
            //endregion tbody

            //region tfoot
            $content .= "<tfoot>";
            $content .= "<tr class='tfooter bg-grey-1'>";
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= "<th>";
                if (isset($arrayHistorySumField) && isset($arrayHistorySumField[$key])) {
                    $content .= formatField($key, $arrayHistorySumField[$key]);
                }
                else {
                    $content .= "--";
                }
                $content .= "</th>";
            }
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion tfoot
            $content .= "</table class='table table-bordered'>";
            $content .= "</div class='table-responsive'>";
        }
        else {
            $content .= "no history found.<br>";
            $content .= "to go back to index, you can click BACK button<br>";
        }
        $content .= "</div>";

        $content .= "<script>
                    $(document).ready( function(){
                            var table = $('#data_history_$jenisTr').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                                processing: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },
                                          $custom_button
                                    ],
                            footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                                    var arrayFooter = $('#data_history_$jenisTr>tfoot>tr>th');
                                        var dpageTotal = [];
                                        jQuery.each(arrayFooter, function(i,d){

                                            var id_n_index = parseFloat(i);
                                            dpageTotal[id_n_index] = 0;

                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                var pos = obj.indexOf('<');
                                            var hr = obj.indexOf('<hr>');
                                            if(pos!==-1&&hr==-1&&id_n_index>0){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{

                                                }

                                            });

                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                            $( api.column(id_n_index).footer() ).html(
                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                            );
                                        }


                                        });
                                    }
                                });

                            });
                        $('.table-responsive.data_history_$jenisTr').floatingScroll();
                        $('.table-responsive.data_history_$jenisTr').scroll(function() {
                            setTimeout(function () {
                                $('#data_history_$jenisTr').DataTable().fixedHeader.adjust();
                            }, 400);
                        });
                    </script>";

        echo $content;

        break;
    case "viewStatusGlobal":
    case "viewStatus":
        $contens = "";
        $custom_button = "";
        $cf_button = $this->config->item('button');
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;
        $arrayHistoryCanceleds = isset($arrayHistoryCanceled) ? $arrayHistoryCanceled : array();


        if (isset($customStatusLayout) && ($customStatusLayout == true)) {
            $rowspan = 2;
            unset($arrayHistoryLabels['status_next']);
            $colspan = sizeof($arrayHistoryLabels);
        }
        else {
            $rowspan = 1;
            $colspan = 1;
        }


        //        $stepLabels = array();
        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            //            if(sizeof($stepLabels) > 3){
            //                $content .= ("<span class='btn btn-default'>Silahkan pilih jenis transaksi: </span>");
            //                $content .= ("<select class='btn btn-default'>");
            //                $content .= ("<option> -- pilih jenis transaksi -- </option>");
            //                foreach ($stepLabels as $step => $label) {
            //                    cekHere($stepLinks[$step]);
            //                    $content .= ("<option >$label</option>");
            //                }
            //                $content .= ("</select>");
            //            }
            //            else{
            $content .= ("<ul class='nav nav-tabs'>");
            foreach ($stepLabels as $step => $label) {

                $color = $step == $currentState ? "#454549" : "#999999";
                $borderColor = $step == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $step == $currentState ? "#ffffff" : "#f0f0f0";

                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($step == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
            //            }
        }


        $content .= ("<div class=\"box-body\">");

        if (sizeof($arrayHistory) > 0) {
            $content .= ("<form name='fSelect'>");
            $content .= ("<div class='table-responsive'>");
            $content .= "<table class='table hover datatables table-condensed table-bordered no-padding'>";
            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#f0f0f0'>");
            $content .= ("<th class='text-muted'>");
            $content .= "no.";
            $content .= ("</th>");
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th class='text-muted'>");
                $content .= ($label);
                $content .= ("</th>");
            }
            $content .= ("</tr>");
            $content .= ("</thead>");

            $content .= ("<tbody>");
            $rowCtr = 0;
            foreach ($arrayHistory as $trID => $row) {
                $canceled = "";
                if (in_array($trID, $arrayHistoryCanceleds)) {
                    $canceled = "background-color:#E6E6FA;";
                }

                $rowCtr++;
                $bgcolor = $rowCtr % 2 == 0 ? "background-color:#FFFFFF;" : "background-color:#FFE4E1;";
                $content .= ("<tr id='tr$rowCtr' style='$canceled $bgcolor'>");

                $content .= ("<td rowspan='$rowspan'>");
                $content .= ($rowCtr);
                $content .= ("</td>");
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td data-order='" . preg_replace('/[^A-Za-z0-9\-]/', '', $row[$key]) . "'>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");

                if (isset($customStatusLayout) && ($customStatusLayout == true)) {
                    $bgcolor = $rowCtr % 2 == 0 ? "background-color:#FFFFFF;" : "background-color:#FFE4E1;";
                    $status = isset($arrayHistoryStatus[$trID]) ? $arrayHistoryStatus[$trID] : "";
                    $content .= ("<tr style='$bgcolor'>");
                    $content .= ("<td colspan='$colspan'> $status </td>");
                    $content .= ("<tr>");
                }
            }
            $content .= ("</tbody>");

            $content .= ("</table class='table table-bordered'>");
            $content .= ("</div class='table-responsive'>");
            $content .= ("</form>");

        }
        else {
            $content .= ("no history found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
        }

        $content .= ("</div>");


        $content .= "\n<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },
                                          $custom_button
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
//                                            console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );


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


        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }


        switch ($mode) {
            case "viewStatusGlobal":
                $template = "history_status_global";
                break;
            case "viewStatus":
            case "viewStatusNew":
                $template = "history_status";
                break;
            //            default:
            //                cekMerah("no template");
            //                break;
        }
        cekMerah($template);
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "/template/$template.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => isset($filters['dates']['start']) ? $filters['dates']['start'] : "",
            "date_max" => isset($filters['dates']['end']) ? $filters['dates']['end'] : "",
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;

    case "viewStatusOLD":
        $contens = "";
        $custom_button = "";
        $cf_button = $this->config->item('button');
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;
        $arrayHistoryCanceleds = isset($arrayHistoryCanceled) ? $arrayHistoryCanceled : array();

        $stepLabels = array();

        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");

            foreach ($stepLabels as $step => $label) {
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }


        $content .= ("<div class=\"box-body\">");
        if (sizeof($arrayHistory) > 0) {
            $content .= ("<form name='fSelect'>");
            $content .= ("<div class='table-responsive'>");
            $content .= "<table class='table hover datatables table-condensed table-bordered no-padding'>";
            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#f0f0f0'>");
            //            $content .= ("<th class='text-muted'>");
            //            $content .= "no.";
            //            $content .= ("</th>");
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th class='text-muted'>");
                $content .= ($label);
                $content .= ("</th>");
            }
            $content .= ("</tr>");
            $content .= ("</thead>");

            $content .= ("<tbody>");
            $rowCtr = 0;
            foreach ($arrayHistory as $trID => $row) {
                $canceled = "";
                if (in_array($trID, $arrayHistoryCanceleds)) {
                    $canceled = "background-color:#E6E6FA;";
                }

                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr' style='$canceled'>");

                //                $content .= ("<td>");
                //                $content .= ($rowCtr);
                //                $content .= ("</td>");
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td data-order='" . preg_replace('/[^A-Za-z0-9\-]/', '', $row[$key]) . "'>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");

            }
            $content .= ("</tbody>");

            $content .= ("</table class='table table-bordered'>");
            $content .= ("</div class='table-responsive'>");
            $content .= ("</form>");

        }
        else {
            $content .= ("no history found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
        }
        $content .= ("</div>");


        $content .= "\n<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },
                                          $custom_button
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
//                                            console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );


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


        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", "application/template/history.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => $filters['dates']['start'],
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;

    case "viewHistoryOld":
        $contens = "";
        $custom_button = "";
        if (isset($customButton)) {
            if (sizeof($customButton) > 0) {
                // arrPrint($customButton);
                foreach ($customButton as $ky => $preg) {
                    foreach ($preg as $k => $mbuh) {
                        $$k = $preg[$k];
                    }
                    $custom_button .= "{text:'$label',action:function(e,dt,node,config){window.open('" . base_url() . $target . $customButtonTarget . "','download');}},";
                    // $custom_button .= "{text:'$label',action:function(e,dt,node,config){window.open('".base_url().$target.$uri_4."?date1=$date1&date2=$date2','download');}},";
                }
            }
        }
        $cf_button = $this->config->item('button');
        $segment_uri_array = $this->uri->segment_array();
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;

        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");

            foreach ($stepLabels as $step => $label) {
                //                $btnClass = $steps[$step]['target'] == $currentState ? "btn-success" : "btn-default";
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }


        $content .= ("<div class=\"box-body\">");
        if (sizeof($arrayHistory) > 0) {
            $content .= ("<form name='fSelect'>");
            $content .= ("<div class='table-responsive'>");
            $content .= "<table class='table table-condensed table-bordered no-padding table-hover datatables'>";

            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#f0f0f0'>");
            $content .= "<th class='text-muted text-center'>No</th>";
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th class='text-muted text-center'>");
                if (is_array($label)) {
                    $content .= ($label['label']);
                }
                else {
                    $content .= ($label);
                }
                $content .= ("</th>");
            }
            $content .= ("</tr>");
            $content .= ("</thead>");


            $rowCtr = 0;
            foreach ($arrayHistory as $row) {
                $rowCtr++;

                $content .= ("<tr id='tr$rowCtr'>");
                $content .= ("<td class='text-right'>");
                $content .= $rowCtr;
                $content .= ("</td>");
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");


            }


            $content .= ("</table class='table table-bordered'>");
            $content .= ("</div class='table-responsive'>");
            $content .= ("</form>");

        }
        else {
            $content .= ("no history found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
        }
        $content .= ("</div>");

        $content .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },
                                          $custom_button
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

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", "application/template/history.html");
        $p->addTags(array(
            "jenisTr" => $jenisTr,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => $filters['dates']['start'],
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;
    case "viewMyActs":
        //$myId = my_id();
        //$myCabangId = my_cabang_id();
        $contens = "";
        //        $contens .= "<script type=\"text/javascript\">
        //        var table;
        //                        $(document).ready(function() {
        //                        table = $('#table').DataTable({
        ////                        'processing': true,
        ////                        'serverSide': true,
        //                        'pageLength': 50,
        //                   });
        //
        //                        });
        //                        </script>";
        $cf_button = $this->config->item('button');
        $segment_uri_array = $this->uri->segment_array();
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;


        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");

            foreach ($stepLabels as $step => $label) {
                //                $btnClass = $steps[$step]['target'] == $currentState ? "btn-success" : "btn-default";
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }


        $content .= ("<div class=\"box-body\">");
        if (sizeof($arrayHistory) > 0) {
            $content .= ("<form name='fSelect'>");
            $content .= ("<div class='table-responsive'>");
            $content .= "<h3 class='text-muted'>$trName details</h3>";
            $content .= "<table class='table table-condensed table-bordered no-padding'>";
            $content .= ("<tr bgcolor='#f0f0f0'>");
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th class='text-muted'>");
                $content .= ($label);
                $content .= ("</th>");
            }
            $content .= ("</tr>");


            $rowCtr = 0;
            foreach ($arrayHistory as $row) {
                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr'>");
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");

            }


            $content .= ("</table class='table table-bordered'>");
            $content .= ("</div class='table-responsive'>");
            $content .= ("</form>");

        }
        else {
            $content .= ("you did not make any <strong>$trName</strong> activity at selected date(s).<br>");
            $content .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");
        }
        $content .= ("</div>");

        //        if (sizeof($availFilters) > 0 && sizeof($arrayHistory) > 0) {
        //            $content .= ("<div class=\"box-body\">");
        //            $content .= "<h3 class='text-muted'>resume</h3>";
        //            $content .= ("<div class=\"row\">");
        //
        //            foreach ($availFilters as $fName => $fLabel) {
        //                if (isset($names[$fName]) && sizeof($names[$fName]) > 0) {
        //                    $content .= ("<div class=\"col-md-4\">");
        //                    $content .= "<h4>$fLabel</h4>";
        //
        //
        //                    $content .= "<table class='table table-condensed table-bordered no-padding'>";
        //                    $content .= "<tr bgcolor='#f0f0f0'>";
        //
        //                    $content .= "<td>$fLabel</td>";
        //                    $content .= "<td class='text-right'>(unit)</td>";
        //                    $content .= "<td class='text-right'>IDR</td>";
        //
        //                    $content .= "</tr>";
        //                    foreach ($names[$fName] as $oID => $oName) {
        //
        //                        $content .= "<tr>";
        //
        //                        $content .= "<td>$oName</td>";
        //                        $content .= "<td class='text-right'>" . number_format($recaps[$fName][$oID]['qty']) . "</td>";
        //                        $content .= "<td class='text-right'>" . number_format($recaps[$fName][$oID]['value']) . "</td>";
        //
        //                        $content .= "</tr>";
        //                    }
        //                    $content .= "</table>";
        //                    $content .= ("</div class=\"col-md-4\">");
        //                }
        //            }
        //            $content .= ("</div class=\"row\">");
        //            $content .= ("</div class=\"box-body\">");
        //        }

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "/template/history.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => isset($filters['dates']['start']) ? $filters['dates']['start'] : "",
            "date_max" => isset($filters['dates']['end']) ? $filters['dates']['end'] : "",
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;

    case "viewIncomplete":


        //arrPrint($arrayProgressLabels);
        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");
            foreach ($stepLabels as $step => $label) {
                //                $btnClass = $step == $currentState ? "btn-success" : "btn-default";
                //                $color = $step == $currentState ? "#454549" : "#999999";
                //                $borderColor = $step == $currentState ? "#cccccc" : "#ffffff";
                //                $bgColor = $step == $currentState ? "#ffffff" : "#f0f0f0";
                //cekHitam(":: $step == $checkState ::");
                //                $color = ((string)$step == (string)$checkState) ? "#454549" : "#999999";
                //                $borderColor = ((string)$step == (string)$checkState) ? "#cccccc" : "#ffffff";
                //                $bgColor = ((string)$step == (string)$checkState) ? "#ffffff" : "#f0f0f0";
                $color = "";
                $borderColor = "";
                $bgColor = "";

                $content .= ("<li class='nav-item'>");
                $content .= "<a class='btn ' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' 
                    onclick =\"location.href='" . $stepLinks[$step] . "';\">";
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }


        $content .= ("<div class=\"box-body\">");
        if (sizeof($arrayOnProgress) > 0) {
            $content .= ("<form name='fSelect'>");
            $content .= ("<div class='table-responsive'>");
            $content .= "<table class='table table-condensed table-bordered no-padding'>";
            $content .= ("<tr bgcolor='#f0f0f0'>");
            foreach ($arrayProgressLabels as $key => $label) {
                $content .= ("<th class='text-muted'>");
                if (is_array($label)) {
                    $str = isset($label['label']) ? $label['label'] : "-";
                }
                else {
                    $str = $label;
                }
                $content .= ($str);
                $content .= ("</th>");
            }
            $content .= ("</tr>");


            $rowCtr = 0;
            foreach ($arrayOnProgress as $row) {
                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr'>");
                foreach ($arrayProgressLabels as $key => $label) {
                    $content .= ("<td>");
                    $content .= ($row[$key]);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");

            }
            if ($allowJoin) {
                //                echo "allow join";
                $content .= ("<tr id='tr$rowCtr'>");

                $content .= ("<td colspan='" . sizeof($arrayProgressLabels) . "'>");
                $content .= ("<a class='btn btn-warning btn-block' href='#' onclick=\"

var items = document.forms['fSelect'].elements['oID[]'];
console.log('items-length='+items.length);

var strIDs='';
for (var i=0; i<=items.length; i++) {
    console.log('iterasi checkbox ke '+i);
    if(items[i].checked){
        console.log(items[i].value);
        strIDs+=items[i].value+'-';
    }
}
console.log('strIDs='+strIDs);
if(strIDs.length>0){top.document.getElementById('result').src='$followupBase'+strIDs+'/$_nextStepNum/$_currentStepNum';}else{alert('please select at least one entry')}


\">$actionLabel from selected items</a>");
                $content .= ("</td>");
                $content .= ("</tr>");
            }

            $content .= ("</table class='table table-bordered'>");
            $content .= ("</div class='table-responsive'>");
            $content .= ("</form>");

        }
        else {
            $content .= ("no item to followup.<br>");
            $content .= ("you can try to select another status tab<br>");
        }
        $content .= ("</div>");

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }


        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/history.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => isset($filters['dates']['start']) ? $filters['dates']['start'] : "",
            "date_max" => isset($filters['dates']['end']) ? $filters['dates']['end'] : "",
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;

    case "viewMySettlement":


        $cf_button = $this->config->item('button');
        $segment_uri_array = $this->uri->segment_array();
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;

        $content = "";
        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");

            foreach ($stepLabels as $step => $label) {
                //                $btnClass = $steps[$step]['target'] == $currentState ? "btn-success" : "btn-default";
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }


        //        arrprint($names);die();

        //        arrprint($recaps);die();


        $content .= ("<div class='panel panel-default'>");
        $content .= ("<div class='panel-body'>");
        $content .= ("<div class='row'>");
        $content .= ("<div class='col-md-12'>");
        $content .= ("<h3 class='text-blue'>activities</h3>");
        if (sizeof($trItems) > 0) {

            $content .= ("<table class='table table-bordered table-condensed' width='100%'>");

            $content .= ("<tr bgcolor='#f0f0f0'>");
            $content .= ("<td align='right' class='text-muted'>no.");
            $content .= ("</td>");
            foreach ($trHeaders as $key => $label) {
                $content .= ("<td class='text-muted'>$label");
                $content .= ("</td>");
            }
            $content .= ("</tr>");

            $no = 0;
            $totals = array();
            foreach ($trItems as $iSpec) {
                $no++;
                $content .= ("<tr>");
                $content .= ("<td align='right'>$no.");
                $content .= ("</td>");
                foreach ($trHeaders as $key => $label) {

                    $content .= ("<td>");
                    $content .= formatField($key, $iSpec[$key]);
                    $content .= ("</td>");
                    if (is_numeric($iSpec[$key])) {
                        if (!isset($totals[$key])) {
                            $totals[$key] = 0;
                        }
                        $totals[$key] += $iSpec[$key];
                    }
                }
                $content .= ("</tr>");
            }

            $content .= ("<tr bgcolor='#f5f5f5'>");
            $content .= ("<td align='right'>");
            $content .= ("</td>");
            foreach ($trHeaders as $key => $label) {
                $content .= ("<td>");
                if (isset($totals[$key])) {
                    $content .= formatField($key, $totals[$key]);
                }
                $content .= ("</td>");
            }
            $content .= ("</tr>");

            $content .= ("</table class='table table-condensed'>");
        }
        else {
            $content .= ("<div class='text-muted'>");
            $content .= ("- no transactional activities you have been made within selected date(s) -");
            $content .= ("</div class='text-muted'>");
        }
        $content .= ("</div class='col-md-8'>");
        $content .= ("</div class='row'>");
        $content .= ("</div class='panel panel-default'>");
        $content .= ("</div class='panel-body'>");


        $content .= ("<div class='panel panel-default'>");
        $content .= ("<div class='panel-body'>");
        $content .= "<h3 class='text-blue'>asset movements</h3>";
        $aCtr = 0;
        if (sizeof($names) > 0) {
            $content .= ("<div class=\"row\">");
            foreach ($names as $nameID => $valSpec) {
                $totals[$nameID] = array(
                    "qty_in" => 0,
                    "qty_out" => 0,
                    "value_in" => 0,
                    "value_out" => 0,
                );
                if (isset($names[$nameID]) && sizeof($names[$nameID]) > 0) {
                    $aCtr++;
                    $content .= ("<div class='col-md-4'>");
                    $content .= "<h4 class='text-muted'>$nameID</h4>";

                    $content .= "<div class='table-responsive'>";
                    $content .= "<table class='table table-bordered table-condensed'>";
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    $content .= "<td class='text-muted' align='right'>no.</td>";
                    $content .= "<td class='text-muted'>item names</td>";
                    $content .= "<td class='text-muted'>IN qty</td>";
                    $content .= "<td class='text-muted'>OUT qty</td>";
                    $content .= "<td class='text-muted'>IN IDR</td>";
                    $content .= "<td class='text-muted'>OUT IDR</td>";
                    $content .= "</tr>";
                    $iCtr = 0;
                    foreach ($names[$nameID] as $iID => $iName) {
                        $iCtr++;
                        $qty_in = isset($recaps[$nameID][$iID]['qty_in']) ? $recaps[$nameID][$iID]['qty_in'] : 0;
                        $qty_out = isset($recaps[$nameID][$iID]['qty_out']) ? $recaps[$nameID][$iID]['qty_out'] : 0;
                        $val_in = isset($recaps[$nameID][$iID]['value_in']) ? number_format($recaps[$nameID][$iID]['value_in']) : 0;
                        $val_out = isset($recaps[$nameID][$iID]['value_out']) ? number_format($recaps[$nameID][$iID]['value_out']) : 0;
                        $content .= "<tr>";
                        $content .= "<td align='right'>$iCtr.</td>";
                        $content .= "<td>$iName</td>";
                        $content .= "<td align='right'>$qty_in</td>";
                        $content .= "<td align='right'>$qty_out</td>";
                        $content .= "<td align='right'>$val_in</td>";
                        $content .= "<td align='right'>$val_out</td>";
                        $content .= "</tr>";

                        $totals[$nameID]['qty_in'] += $qty_in;
                        $totals[$nameID]['qty_out'] += $qty_out;
                        $totals[$nameID]['value_in'] += $recaps[$nameID][$iID]['value_in'];
                        $totals[$nameID]['value_out'] += $recaps[$nameID][$iID]['value_out'];

                    }


                    $qty_in = $totals[$nameID]['qty_in'];
                    $qty_out = $totals[$nameID]['qty_out'];
                    $val_in = number_format($totals[$nameID]['value_in']);
                    $val_out = number_format($totals[$nameID]['value_out']);
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    $content .= "<td align='right'>&nbsp;</td>";
                    $content .= "<td>total</td>";
                    $content .= "<td align='right'>$qty_in</td>";
                    $content .= "<td align='right'>$qty_out</td>";
                    $content .= "<td align='right'>$val_in</td>";
                    $content .= "<td align='right'>$val_out</td>";
                    $content .= "</tr>";

                    $content .= "</table class='table table-bordered table-condensed'>";
                    $content .= "</div class='table-responsive'>";

                    $content .= ("</div class=col-md-3>");

                }
                else {
                    $content .= ("<div class=\"box-body\">");
                    $content .= ("<div class='text-muted'>");
                    $content .= ("you haven't made any activities causing asset movements within selected date(s).<br>");
                    $content .= ("try to select other date(s) or you can click BACK button to go to previous page<br>");
                    $content .= ("</div class='text-muted'>");
                    $content .= ("</div>");
                }

            }
            $content .= ("</div class=\"row\">");


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("<div class='text-muted'>");
            $content .= ("you did not make any <strong>$trName</strong> activity at selected date(s).<br>");
            $content .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");
            $content .= ("</div class='text-muted'>");
            $content .= ("</div>");
        }

        $content .= ("</div class='panel-body'>");
        $content .= ("</div class='panel panel-default'>");


        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "/template/history.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "navigasi" => $btnNavigasi,
            "subJenisTr" => $subJenisTr,
            "trName" => $trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => $filters['dates']['start'],
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;
    case "viewOutstanding":

        $contens = "";
        $cf_button = $this->config->item('button');
        $segment_total = $this->uri->total_segments();
        $tab_segment = $this->uri->segment(5);

        $segment_page = $segment_total;
        $content = "";

        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");
            foreach ($stepLabels as $step => $label) {
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }

        $content .= "<div class='box-body'>";
        if (sizeof($arrayHistory) > 0) {
            $content .= "<form name='fSelect'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table id='barangam' class='table hover datatables table-condensed table-bordered no-padding'>";

            //region thead
            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#f0f0f0'>");

            $content .= "<th width='1%' class='text-uppercase text-muted text-center'>";
            $content .= "No";
            $content .= "</th>";

            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th class='text-uppercase text-muted text-center'>");
                $content .= ($label);
                $content .= ("</th>");
            }

            $content .= ("</tr>");
            $content .= ("</thead>");
            //endregion head

            //region tbody
            $content .= ("<tbody>");
            $rowCtr = 0;

            $produk_kode = array();
            $produk_ord_jml = array();
            $ord_sent_qty = array();
            $ord_valid_qty = array();
            $action_button = array();
            $kumpul_array = array();

            //arrPrintWebs($marking_style);
            // arrPrintWebs($arrayHistory);
            foreach ($arrayHistory as $parentKey => $row) {
                $mark = "";
                if (isset($marking_style) && (isset($marking_style[$parentKey]))) {
                    $mark = $marking_style[$parentKey];
                }
                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr' style='$mark'>");
                $content .= "<td class='text-center text-bold' style='vertical-align: top;'>$rowCtr</td>";
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td style='vertical-align: top;'>");
                    if (isset($detailsFields[$parentKey][$key]) && sizeof($detailsFields[$parentKey][$key]) > 0) {
                        if (is_array($detailsFields[$parentKey][$key])) {
                            if (sizeof($detailsFields[$parentKey][$key]) > 1) {
                                $reValue = "";
                                $sumValue = 0;
                                $arr = array();
                                if ($tab_segment == 'customer') {
                                    if ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'nomer_top') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_ord_jml') {
                                        $produk_ord_jml = array();
                                        $produk_ord_jml_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkCode[$parentKey]['produk_kode'])) {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $produk_ord_jml[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkCode[$parentKey]['produk_kode'])) {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($produk_ord_jml[$parentKey][$key][$code]) > 1) {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                                else {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_sent_qty') {
                                        $ord_sent_qty = array();
                                        $ord_sent_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkCode[$parentKey]['produk_kode'])) {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $ord_sent_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkCode[$parentKey]['produk_kode'])) {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($ord_sent_qty[$parentKey][$key][$code]) > 1) {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                                else {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        $valid_qty = array();
                                        $valid_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkCode[$parentKey]['produk_kode'])) {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $valid_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkCode[$parentKey]['produk_kode'])) {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($valid_qty[$parentKey][$key][$code]) > 1) {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                                else {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $reValue .= "<div>$value</div>";
                                        }
                                    }
                                }
                                elseif ($tab_segment == 'supplier') {
                                    if ($key == 'produk_id') {
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'nomer_top') {
                                        $nomerTop = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $nomerTop[$parentKey][$key][] = $value;
                                        }
                                        if (isset($nomerTop[$parentKey][$key])) {
                                            foreach (array_flip($nomerTop[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_ord_jml') {
                                        $produk_ord_jml = array();
                                        $produk_ord_jml_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkId[$parentKey]['produk_id'])) {
                                                foreach ($produkId[$parentKey]['produk_id'] as $keys => $code) {
                                                    $produk_ord_jml[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                            else {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $produk_ord_jml[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkId[$parentKey]['produk_id'])) {
                                            foreach (array_flip($produkId[$parentKey]['produk_id']) as $code => $vval) {
                                                if (sizeof($produk_ord_jml[$parentKey][$key][$code]) > 1) {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                                else {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($produk_ord_jml[$parentKey][$key][$code]) > 1) {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                                else {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_nama') {
                                        $produkNama = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkNama[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkNama[$parentKey][$key])) {
                                            foreach (array_flip($produkNama[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_sent_qty') {
                                        $ord_sent_qty = array();
                                        $ord_sent_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkId[$parentKey]['produk_id'])) {
                                                foreach ($produkId[$parentKey]['produk_id'] as $keys => $code) {
                                                    $ord_sent_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                            else {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $ord_sent_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkId[$parentKey]['produk_id'])) {
                                            foreach (array_flip($produkId[$parentKey]['produk_id']) as $code => $vval) {
                                                if (sizeof($ord_sent_qty[$parentKey][$key][$code]) > 1) {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                                else {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($ord_sent_qty[$parentKey][$key][$code]) > 1) {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                                else {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        $valid_qty = array();
                                        $valid_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkId[$parentKey]['produk_id'])) {
                                                foreach ($produkId[$parentKey]['produk_id'] as $keys => $code) {
                                                    $valid_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                            else {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $valid_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkId[$parentKey]['produk_id'])) {
                                            foreach (array_flip($produkId[$parentKey]['produk_id']) as $code => $vval) {
                                                if (sizeof($valid_qty[$parentKey][$key][$code]) > 1) {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                                else {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($valid_qty[$parentKey][$key][$code]) > 1) {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                                else {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $reValue .= "<div>$value</div>";
                                        }
                                    }
                                }
                                elseif ($key == 'produk') {
                                    if ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_id') {
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'stok') {
                                        $produkCode = array();
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        elseif (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                }
                                else {
                                    if ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_id') {
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'stok') {
                                        $produkCode = array();
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        elseif (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                }
                                $content .= strpos($reValue, "div") > 0 ? $reValue : "<div>$reValue</div>";
                            }
                            else {
                                foreach ($detailsFields[$parentKey][$key] as $value) {
                                    $content .= "<div>$value</div>";
                                }
                            }
                        }
                        else {
                            $contens .= $detailsFields[$parentKey][$key];
                        }
                    }
                    else {
                        $content .= isset($row[$key]) ? ($row[$key]) : "";
                    }
                    $content .= ("</td>");
                }
                $content .= ("</tr>");
            }
            $content .= ("</tbody>");
            //endregion tbody

            //region tfoot
            $content .= ("<tfoot>");
            $content .= "<tr class='tfoot bg-grey-1'>";
            $content .= ("<th>");
            $content .= "--";
            $content .= ("</th>");
            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th>");
                if (isset($summary[$key])) {
                    $content .= formatField("debet", $summary[$key]);
                }
                else {
                    $content .= "--";
                }
                $content .= ("</th>");
            }
            $content .= "</tr>";
            $content .= ("</tfoot>");
            //endregion tfoot
            $content .= "</table class='table table-bordered'>";
            $content .= "</div class='table-responsive'>";
            $content .= "</form>";

        }
        else {
            $content .= "no outstanding found.<br>";
            $content .= "to go back to index, you can click BACK button<br>";
        }
        $content .= "</div>";
        $content .= "<script>

                    function printDiv(divName){
                        var printContents = $('table', $('#DataTables_Table_0_wrapper') ).html();
                        var originalContents = document.body.innerHTML;
                        document.body.innerHTML = '<table class=table>' + printContents + '</table>';
                        window.print();
                        top.location.reload();
                    }

                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                       'copy', 
                                       'csv', 
                                       'excel', 
                                       'pdf', 
                                       {
                                            extend: 'print',
                                            text: 'Print current page',
                                            exportOptions: {
                                                stripHtml: false
                                            }
                                        }
                                   ],

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
//                                                if( $(obj).length > 1){
//                                                    jQuery.each( $(obj), function(iii, obj2){
//                                                        dpageTotal[id_n_index] += intVal( $(obj2).html() );
//                                                    });
//                                                }
//                                                else{
//                                                    var pos = obj.indexOf('<');
//                                                    var hr = obj.indexOf('<hr>');
//                                                    if(pos!==-1&&hr==-1&&id_n_index>0){
//                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
//                                                    }
//                                                    else{
//                                                    
//                                                    }
//                                                }
var pos = obj.indexOf('<');
                                                var hr = obj.indexOf('<hr>');
                                                var br = obj.indexOf('<br>');
                                                if(pos*1 > -1){ console.log('nilai pos: '+pos)}
                                                if(pos==0&&hr==-1&&br==-1&&id_n_index>0){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-left text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                );
                                            }
                                        });
                                    }
                                });
                            });

                            $('.table-responsive').floatingScroll();
                            $('.table-responsive').scroll(function () {
                                setTimeout(function () {
                                    $('table.datatables').DataTable().fixedHeader.adjust();
                                }, 400);
                            });
                            
                    </script>";


        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/outstanding.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "navigasi" => $btnNavigasi,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => isset($filters['dates']['start']) ? $filters['dates']['start'] : "",
            "date_max" => isset($filters['dates']['end']) ? $filters['dates']['end'] : "",
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;
    case "salesKonsolidate":

        $contens = "";
        $cf_button = $this->config->item('button');
        $segment_total = $this->uri->total_segments();
        $tab_segment = $this->uri->segment(4);

        $segment_page = $segment_total;
        $content = "";

        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $content .= ("<ul class='nav nav-tabs'>");
            foreach ($stepLabels as $step => $label) {
                $color = $steps[$step]['target'] == $currentState ? "#454549" : "#999999";
                $borderColor = $steps[$step]['target'] == $currentState ? "#cccccc" : "#ffffff";
                $bgColor = $steps[$step]['target'] == $currentState ? "#ffffff" : "#f0f0f0";
                $content .= ("<li class='nav-item'>");
                $content .= "<a class='nav-link btn' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;' onclick =\"location.href='" . $stepLinks[$step] . "?date1=" . $filters['date1'] . "&date2=" . $filters['date2'] . "';\">";
                if ($steps[$step]['target'] == $currentState) {
                    $content .= "<span class='fa fa-adjust'></span> ";
                }
                $content .= "$label</a>";
                $content .= ("</li>");
            }
            $content .= ("</ul class='pager'>");
        }

        $content .= ("<div class=\"box-body\">");
        if (sizeof($arrayHistory) > 0) {
            $content .= ("<form name='fSelect'>");
            $content .= ("<div class='table-responsive'>");
            $content .= "<table class='table hover datatables table-condensed table-bordered no-padding' id='tabel_out'>";

            //region thead
            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#f0f0f0'>");

            $content .= "<th width='1%' class='text-uppercase text-muted text-center'>";
            $content .= "No";
            $content .= "</th>";

            foreach ($arrayHistoryLabels as $key => $label) {
                $content .= ("<th class='text-uppercase text-muted text-center'>");
                $content .= ($label);
                $content .= ("</th>");
            }

            $content .= ("</tr>");
            $content .= ("</thead>");
            //endregion head

            //region tbody
            $content .= ("<tbody>");
            $rowCtr = 0;

            $produk_kode = array();
            $produk_ord_jml = array();
            $ord_sent_qty = array();
            $ord_valid_qty = array();
            $action_button = array();
            $kumpul_array = array();

            //arrPrintWebs($marking_style);
            // arrPrintWebs($arrayHistory);
            // arrPrintWebs($detailsFields);
            foreach ($arrayHistory as $parentKey => $row) {
                $mark = "";
                if (isset($marking_style) && (isset($marking_style[$parentKey]))) {
                    $mark = $marking_style[$parentKey];
                }
                $rowCtr++;
                $content .= ("<tr id='tr$rowCtr' style='$mark'>");
                $content .= "<td class='text-center text-bold'>$rowCtr</td>";
                foreach ($arrayHistoryLabels as $key => $label) {
                    $content .= ("<td>");
                    if (isset($detailsFields[$parentKey][$key]) && sizeof($detailsFields[$parentKey][$key]) > 0) {
                        if (is_array($detailsFields[$parentKey][$key])) {
                            if (sizeof($detailsFields[$parentKey][$key]) > 1) {
                                $reValue = "";
                                $sumValue = 0;
                                $arr = array();
                                if ($tab_segment == 'customer') {
                                    if ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'nomer_top') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_ord_jml') {
                                        $produk_ord_jml = array();
                                        $produk_ord_jml_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkCode[$parentKey]['produk_kode'])) {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $produk_ord_jml[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkCode[$parentKey]['produk_kode'])) {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($produk_ord_jml[$parentKey][$key][$code]) > 1) {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                                else {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_sent_qty') {
                                        $ord_sent_qty = array();
                                        $ord_sent_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkCode[$parentKey]['produk_kode'])) {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $ord_sent_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkCode[$parentKey]['produk_kode'])) {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($ord_sent_qty[$parentKey][$key][$code]) > 1) {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                                else {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        $valid_qty = array();
                                        $valid_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkCode[$parentKey]['produk_kode'])) {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $valid_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkCode[$parentKey]['produk_kode'])) {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($valid_qty[$parentKey][$key][$code]) > 1) {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                                else {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $reValue .= "<div>$value</div>";
                                        }
                                    }
                                }
                                elseif ($tab_segment == 'supplier') {
                                    if ($key == 'produk_id') {
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'nomer_top') {
                                        $nomerTop = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $nomerTop[$parentKey][$key][] = $value;
                                        }
                                        if (isset($nomerTop[$parentKey][$key])) {
                                            foreach (array_flip($nomerTop[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_ord_jml') {
                                        $produk_ord_jml = array();
                                        $produk_ord_jml_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkId[$parentKey]['produk_id'])) {
                                                foreach ($produkId[$parentKey]['produk_id'] as $keys => $code) {
                                                    $produk_ord_jml[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                            else {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $produk_ord_jml[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkId[$parentKey]['produk_id'])) {
                                            foreach (array_flip($produkId[$parentKey]['produk_id']) as $code => $vval) {
                                                if (sizeof($produk_ord_jml[$parentKey][$key][$code]) > 1) {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                                else {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($produk_ord_jml[$parentKey][$key][$code]) > 1) {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                                else {
                                                    $produk_ord_jml_total = 0;
                                                    foreach ($produk_ord_jml[$parentKey][$key][$code] as $vval2) {
                                                        $produk_ord_jml_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$produk_ord_jml_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_nama') {
                                        $produkNama = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkNama[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkNama[$parentKey][$key])) {
                                            foreach (array_flip($produkNama[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_sent_qty') {
                                        $ord_sent_qty = array();
                                        $ord_sent_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkId[$parentKey]['produk_id'])) {
                                                foreach ($produkId[$parentKey]['produk_id'] as $keys => $code) {
                                                    $ord_sent_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                            else {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $ord_sent_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkId[$parentKey]['produk_id'])) {
                                            foreach (array_flip($produkId[$parentKey]['produk_id']) as $code => $vval) {
                                                if (sizeof($ord_sent_qty[$parentKey][$key][$code]) > 1) {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                                else {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($ord_sent_qty[$parentKey][$key][$code]) > 1) {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                                else {
                                                    $ord_sent_qty_total = 0;
                                                    foreach ($ord_sent_qty[$parentKey][$key][$code] as $vval2) {
                                                        $ord_sent_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$ord_sent_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        $valid_qty = array();
                                        $valid_qty_tmp = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (isset($produkId[$parentKey]['produk_id'])) {
                                                foreach ($produkId[$parentKey]['produk_id'] as $keys => $code) {
                                                    $valid_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                            else {
                                                foreach ($produkCode[$parentKey]['produk_kode'] as $keys => $code) {
                                                    $valid_qty[$parentKey][$key][$code][$keys] = $detailsFields[$parentKey][$key][$keys];
                                                }
                                            }
                                        }
                                        if (isset($produkId[$parentKey]['produk_id'])) {
                                            foreach (array_flip($produkId[$parentKey]['produk_id']) as $code => $vval) {
                                                if (sizeof($valid_qty[$parentKey][$key][$code]) > 1) {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                                else {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey]['produk_kode']) as $code => $vval) {
                                                if (sizeof($valid_qty[$parentKey][$key][$code]) > 1) {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                                else {
                                                    $valid_qty_total = 0;
                                                    foreach ($valid_qty[$parentKey][$key][$code] as $vval2) {
                                                        $valid_qty_total += $vval2;
                                                    }
                                                    $reValue .= "<div>$valid_qty_total</div>";
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $reValue .= "<div>$value</div>";
                                        }
                                    }
                                }
                                elseif ($key == 'produk') {
                                    if ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_id') {
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'stok') {
                                        $produkCode = array();
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        elseif (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                }
                                else {
                                    if ($key == 'produk_kode') {
                                        $produkCode = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'produk_id') {
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'stok') {
                                        $produkCode = array();
                                        $produkId = array();
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            $produkCode[$parentKey][$key][] = $value;
                                            $produkId[$parentKey][$key][] = $value;
                                        }
                                        if (isset($produkCode[$parentKey][$key])) {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        elseif (isset($produkId[$parentKey][$key])) {
                                            foreach (array_flip($produkId[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                        else {
                                            foreach (array_flip($produkCode[$parentKey][$key]) as $code => $vval) {
                                                $reValue .= "<div>$code</div>";
                                            }
                                        }
                                    }
                                    elseif ($key == 'ord_valid_qty') {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($detailsFields[$parentKey][$key] as $value) {
                                            if (is_numeric($value)) {
                                                $reValue += $value;
                                            }
                                            else {
                                                $reValue .= "<div>$value</div>";
                                            }
                                        }
                                    }
                                }
                                $content .= strpos($reValue, "div") > 0 ? $reValue : "<div>$reValue</div>";
                            }
                            else {
                                foreach ($detailsFields[$parentKey][$key] as $value) {
                                    $content .= "<div>$value</div>";
                                }
                            }
                        }
                        else {
                            $contens .= $detailsFields[$parentKey][$key];
                        }
                    }
                    else {
                        $content .= isset($row[$key]) ? ($row[$key]) : "";
                    }
                    $content .= ("</td>");

                    if (is_numeric($row[$key])) {

                        if (!isset($sumValues[$key])) {
                            $sumValues[$key] = 0;
                        }
                        $sumValues[$key] += $row[$key];
                    }
                    else {
                        $sumValues[$key] = "*";
                    }
                }
                $content .= ("</tr>");
            }
            $content .= ("</tbody>");
            //endregion tbody
            // arrPrint($sumValues);
            //region tfoot
            $content .= ("<tfoot>");
            $content .= "<tr class='tfoot bg-grey-1'>";
            $content .= ("<th>");
            $content .= "--";
            $content .= ("</th>");
            foreach ($arrayHistoryLabels as $key => $label) {
                $sumNilai = is_numeric($summary[$key]) ? formatField('debet', $summary[$key]) : "-";
                $content .= ("<th>");
                $content .= $sumNilai;
                $content .= ("</th>");
            }
            $content .= "</tr>";
            $content .= ("</tfoot>");
            //endregion tfoot
            $content .= ("</table class='table table-bordered'>");
            $content .= ("</div class='table-responsive'>");
            $content .= ("</form>");

        }
        else {
            $content .= ("no outstanding found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
        }
        $content .= ("</div>");
        $content .= "<script>

                    function printDiv(divName){
                        var printContents = $('table', $('#DataTables_Table_0_wrapper') ).html();
                        var originalContents = document.body.innerHTML;
                        document.body.innerHTML = '<table class=table>' + printContents + '</table>';
                        window.print();
                        top.location.reload();
                    }

                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        {
                                            text: '<i class=\"fa fa-print\"></i> cetak',
                                            action: function ( e, dt, node, config ) {
                                                printDiv();
                                            }
                                        },
                                        // {
                                        //     text: 'Download Excel',
                                        //     action: function (e, dt, node, config) {
                                        //         fnExcelReport();
                                        //     }
                                        // },
                                    ],
                           buttons: [
                                       'copy', 'csv', 'excel', 'pdf', 'print',
                                   ],
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


                                                if( $(obj).length > 1){
                                                    jQuery.each( $(obj), function(iii, obj2){
                                                        dpageTotal[id_n_index] += intVal( $(obj2).html() );
                                                    });
                                                }
                                                else{
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }

//                                                console.log( $(obj).length );
//                                                console.log(obj);
//                                                console.log($(obj).html());
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-left text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
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


        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle", "application/template/outstandingConsolidate.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            //            "navigasi" => $btnNavigasi,
            "navigasi" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            //            "add_link" => $addLinkStr,
            "add_link" => "",
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => $filters['dates']['start'],
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));

        $p->render();
        break;
}