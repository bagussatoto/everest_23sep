<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    case "indexOri":

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
            $p = New Layout("$title", "$subTitle", "application/template/blank.html");
            $attached = true;
        }
        else {
            $p = New Layout("$title", "$subTitle", "application/template/harga.html");
            $attached = false;
        }


        //    $content=$error;
        $content = "";

        $content .= $strViewHidden;


        if (sizeof($xLabels) > 0) {
            $header_prog = array();
            $isi_prog = array();
            //            $btn_save = "<input type='submit' class='btn btn-primary' value='$buttonLabel'>";
            //            $content .= "<form method='post' action='$formTarget' target='result'>";
            $content .= "<div class='box box-warning margin-top-10'>";
            //
            //            $content .= "<div class=''><span class='pull-right'>$btn_save</div>";
            if ($attached) {
                $content .= "<form method=\"post\" id=\"fPrice\" name=\"fPrice\" action=\"$formTarget\" target=\"result\">";
            }
            $content .= "<table  class='table table-bordered tabled-condensed no-margin table-responsive' style='table-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;;'>";
            //region header
            $content .= "<tr bgcolor='#e5e5e5'>";
            $content .= "<th rowspan='2' class='text-center' width='25px'>No</th>";
            $content .= "<th rowspan='2' width='85px' >$yHeader</th>";
            $i = 0;
            foreach ($xLabels as $xId => $xNames) {
                $i++;
                $bg_color = $i % 2 == 0 ? "#696969" : "#C0C0C0";
                $colspan = sizeof($zLabels);
                $content .= "<th colspan='$colspan' class='text-center' bgcolor='$bg_color'>$xNames</th>";
            }
            $content .= "</tr>";
            $content .= "<tr >";
            //            $content .= "<td colspan=''>**</td>";
            $no = 0;
            foreach ($xLabels as $xId => $xNames) {
                $no++;
                $bg_color = $no % 2 == 0 ? "#F5F5DC" : "#FFE4C4";
                foreach ($zLabels as $zId => $zNames) {
                    $content .= "<td class='text-center' bgcolor='$bg_color'>$zNames</td>";
                }
            }
            $content .= "</tr>";
            //endregion
            //region values
            //            arrPrint($history);
            //            arrPrint($yLabels);
            //            arrPrint($xLabels);
            //            arrPrint($history);
            //             $limit = $pageLimit;
            //             $pageOffside = $pageLimit * ($this->uri->segment(6) -1);
            $number = $pageOffside;
            foreach ($yLabels as $yId => $yNames) {
                $number++;
                $bg_color = $number % 2 == 0 ? "#F5F5DC" : "#F0F8FF";
                $content .= "<tr>";
                $content .= "<td bgcolor='f0f0f'><span class='btn-block text-right' >$number.</span></td>";
                $content .= "<td>$yNames</td>";
                //                $content .= "<td style='white-space: pre-wrap;word-break:break-word;'>$yNames</td>";
                //                $content .= "<td style='word-wrap: break-word; word-break: break-all; white-space: pre-wrap;'>$yNames</td>";
                foreach ($xLabels as $xId => $xNames) {
                    foreach ($zLabels as $zid => $zNames) {
                        $value = isset($values[$yId][$xId][$zid]) ? $values[$yId][$xId][$zid] : "0";
                        $history_link = isset($history[$yId][$xId][$zid]) ? $history[$yId][$xId][$zid] : "<a href='#' class='btn btn-warning' data-toggle='modal' data-target='#myModal'><i class='fa fa-clock-o'></i></a>";
                        //                        cekbiru($history_link);
                        $content .= "<td bgcolor='$bg_color' style='margin:0px;padding:0px;font-family:courier;'>" . "<div class='input-group'>" . "$value " . "<span class='input-group-btn'>" . "$history_link" . //                        "<a href='$history_link' class='btn btn-warning' data-toggle='modal' data-target='#myModal'><i class='fa fa-clock-o'></i></a>".
                            "</span>" . "</div>" . "</td>";
                    }
                }
                $content .= "</tr>";
            }

            //endregion

            $content .= "</table>";

            if ($attached) {
                $content .= "<div class='panel-body'>";
                $content .= "<span class='pull-right'>";
                $content .= "<a class='btn btn-info' onclick=\"document.getElementById('fPrice').submit();\">save prices</a>";
                $content .= "</span>";
                $content .= "</div>";
                $content .= "</form>";
            }

            $content .= "</div>";
            //            $content .= "</form>";
        }


        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => "callMenuTaskbar()",
            "btn_back" => callBackNav(),
            "start_page" => $startPage,
            "form_target" => $formTarget,
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "error_msg" => $error,
            "submit_btn_label" => $buttonLabel,
            "stop_time" => "",
            "page_str" => $pageStr,

            //                "add_link" => $btn_save,
        ));

        $p->render();
        break;
    case "index":

        // if (in_array("c_holding", $this->session->login['membership'])) {
        //     $allow = 1;
        // }
        // else {
        //     $allow = 0;
        // }

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
            $p = New Layout("$title", "$subTitle", "application/template/blank.html");
            $attached = true;
        }
        else {
            $p = New Layout("$title", "$subTitle", "application/template/harga.html");
            $attached = false;
        }

        $content = "";
        $content .= $strViewHidden;

        // cekHitam($syncAllowed);
//                arrPrint($yLabels);
//                arrPrint($yHeader);
        if (sizeof($yLabels) > 0) {

            if (sizeof($xLabels) > 0) {
                $header_prog = array();
                $isi_prog = array();


                //            $content .= "<div>";
                //            $content .= "<select>";
                //
                //            $content .= "<option value='0'>View All</option>";
                //            foreach($xLabels as $xId=>$xNames){
                //                $content .= "<option value='$xId'>$xNames</option>";
                //            }
                //
                //            $content .= "</select>";
                //            $content .= "</div>";

                //            $content .= "<script>
                //                    var options = {
                //                        theme:'custom',
                //                        content:'<span class=\"text-red text-bold text-center\">DON\'T REFRESH</span>',
                //                        message:'Loading Table Content... <br> PLEASE BE PATIENT...',
                //                        backgroundColor: \"#1847B1\",
                //                        textColor: \"white\"
                //                    };
                //                    top.HoldOn.open(options)
                //            </script>";

                $content .= "<div style='' class='col-lg-5 col-md-5 col-sm-5 no-padding'>";
                $content .= "<div class='box box-warning margin-top-10'>";
                $content .= "<table id='' class='table table-bordered table-hovered table-condensed no-margin text-capitalize' style=''>";
                //region header1
                $content .= "<thead>";
                $content .= "<tr bgcolor='#e5e5e5' style='white-space: normal; vertical-align: middle;'>";
                $content .= "<th rowspan='2' style='white-space: normal; vertical-align: middle;' class='text-center'>No</th>";

                if (isset($arryHeader)) {
                    foreach ($arryHeader as $y2Header) {
                        $content .= "<th class='yHeader' rowspan='3' style='white-space: nowrap; vertical-align: middle;'>$y2Header</th>";
                    }

                }
                else {
                    $content .= "<th class='yHeader' rowspan='2' style='white-space: normal; vertical-align: middle;'>$yHeader</th>";
                }


                $content .= "</tr>";
                $content .= "</thead>";
                //endregion header1

                $number = $pageOffside;
                foreach ($yLabels as $yId => $yNames) {
                    $number++;
                    $bg_color = $number % 2 == 0 ? "#F5F5DC" : "#F0F8FF";
                    $content .= "<tr style='clear: both;' class='produk_nama'>";
                    $content .= "<td bgcolor='f0f0f'><span class='btn-block text-right' >$number.</span></td>";
                    $content .= "<td style='white-space: nowrap; vertical-align: middle;'>$yNames</td>";

                    if (isset($y2Labels)) {
                        if (sizeof($y2Labels) > 0) {

                            $content .= "<td style='white-space: nowrap; vertical-align: middle;'>" . $y2Labels[$yId] . "</td>";

                        }
                    }

                    $content .= "</tr>";
                }

                $content .= "</table>";
                $content .= "</div>";
                $content .= "</div>";

                $content .= "<script>//top.Holdon.open()</script>";
                $content .= "<div style='' class='col-lg-7 col-md-7 col-sm-7 no-padding'>";
                $content .= "<div id='leftBoxTable' class='box box-warning margin-top-10 table-responsive data-fl-scrolls'>";
                if ($attached) {
                    $content .= "<form method=\"post\" id=\"fPrice\" name=\"fPrice\" action=\"$formTarget\" target=\"result\">";
                }
                $content .= "<table id='ddtattbl' class='table table-bordered table-hovered table-condensed no-margin text-capitalize'>";
                //region header
                $content .= "<thead>";
                $content .= "<tr class='th-header' bgcolor='#e5e5e5' style='clear: both;white-space: normal;'>";

                $i = 0;
                foreach ($xLabels as $xId => $xNames) {
                    $i++;
                    $bg_color = $i % 2 == 0 ? "#696969" : "#C0C0C0";
                    $colspan = sizeof($zLabels);
                    if ($xId > 0) {
                        $msg = "Harga cabang $xNames akan diperbaharui sesuai  harga pusat, Lanjutkan?";
                        $btnLableSync = "Perbaharui harga sesuai harga pusat";
                        $linkSyncBtn = $syncBtn . $xId . "?mode=single";
                        $titleSync = "Click untuk memperbaharui harga $xNames sesuai harga di Pusat";
                        // $btmSyncLabel = "<a href='$linkSyncBtn'><span class='btn btn-danger pull-right'>Sinkron harga</span></a>";
                    }
                    else {
                        $msg = "Semua harga di cabang akan diperbaharui sesuai harga pusat,  Lanjutkan?";
                        $btnLableSync = "Perbaharui harga semua cabang";
                        $linkSyncBtn = $syncBtn . "?mode=multi";
                        $titleSync = "Click untuk memperbaharui harga semua cabang sesuai harga di Pusat";
                    }
                    if ($syncAllowed == "disabled") {
                        $titleSync = "Tidak meiliki hak akses untuk melakukan pembaharuan harga";
                    }
                    if ($syncException > 0) {
                        $btmSyncLabel = "";
                    }
                    else {
                        $btmSyncLabel = "<button type='button' class='btn btn-danger pull-right' onclick=\"confirm_alert_result('Peringatan', '$msg', '$linkSyncBtn')\" title='$titleSync' $syncAllowed>$btnLableSync</button>";
                    }

//                $content .= "<th style='vertical-align: middle;' colspan='$colspan' class='text-center' bgcolor='$bg_color'>$i<br>$xNames</th>";
                    $content .= "<th style='vertical-align: middle;' colspan='$colspan' class='text-center' bgcolor='$bg_color'>$xNames $btmSyncLabel</th>";
                }
                $content .= "</tr>";
                $content .= "<tr style='clear: both;' class='td-header'>";

                $no = 0;
                foreach ($xLabels as $xId => $xNames) {
                    $no++;
                    $bg_color = $no % 2 == 0 ? "#F5F5DC" : "#FFE4C4";
                    foreach ($zLabels as $zId => $zNames) {
                        $content .= "<td style='min-width: 150px;max-width: 180px;vertical-align: middle;' class='text-center' bgcolor='$bg_color'>$zNames</td>";
                    }
                }
                $content .= "</tr>";
                $content .= "</thead>";

                foreach ($yLabels as $yId => $yNames) {

                    $content .= "<tr style='clear: both;' class='td-data'>";
                    foreach ($xLabels as $xId => $xNames) {

                        foreach ($zLabels as $zid => $zNames) {
                            //                        cekHere($zid);
                            $value = isset($values[$yId][$xId][$zid]) ? $values[$yId][$xId][$zid] : "0";
                            /* -----------------------------------------------------------------------------
                             * link history diatur dr controler __construct  $this->z['listHistory']
                             * -----------------------------------------------------------------------------*/
                            // $history_link = isset($history[$yId][$xId][$zid]) ? $history[$yId][$xId][$zid] : "<a href='#' class='btn btn-warning' data-toggle='modal' data-target='#myModal'><i class='fa fa-clock-o'></i></a>";
                            $history_link = isset($history[$yId][$xId][$zid]) ? $history[$yId][$xId][$zid] : "";
                            $content .= "<td bgcolor='$bg_color' style='margin:0px;padding:0px;font-family:courier;'>" . "<div class='input-group'>" . "$value " . "<span class='input-group-btn'>" . "$history_link" . "</span>" . "</div>" . "</td>";
                        }
                    }
                    $content .= "</tr>";
                }

                //region <tfoot>
                //            $content .= "<tfoot>";
                //            $content .= "<tr style='clear: both;' class='td-header'>";
                //            $no = 0;
                //            foreach ($xLabels as $xId => $xNames) {
                //                $no++;
                //                $bg_color = $no % 2 == 0 ? "#F5F5DC" : "#FFE4C4";
                //                foreach ($zLabels as $zId => $zNames) {
                //                    $content .= "<td style='min-width: 120px;max-width: 120px;vertical-align: middle;' class='text-center' bgcolor='$bg_color'>$zNames</td>";
                //                }
                //            }
                //            $content .= "</tr>";
                //            $content .= "<tr class='th-header' bgcolor='#e5e5e5' style='clear: both;white-space: normal;'>";
                //            $i = 0;
                //            foreach ($xLabels as $xId => $xNames) {
                //                $i++;
                //                $bg_color = $i % 2 == 0 ? "#696969" : "#C0C0C0";
                //                $colspan = sizeof($zLabels);
                //                $content .= "<th style='vertical-align: middle;' colspan='$colspan' class='text-center' bgcolor='$bg_color'>$xNames</th>";
                //            }
                //            $content .= "</tr>";
                //            $content .= "</tfoot>";
                //endregion <tfoot>

                $content .= "</table>";
                if ($attached) {
                    $content .= "<div class='panel-body'>";
                    $content .= "<span class='pull-right'>";
                    if($allow == true){

                        $content .= "<a id='btn_save' class='btn btn-info' onclick=\"validasiSubmitDuluBro()\">save prices</a>";
                    }
                    else{
                        $content .= "<span class='text-red blink'>Tidak memiliki hak akses untuk perubahan</span>";
                    }
                    $content .= "</span>";
                    $content .= "</div>";
                    $content .= "</form>";
                }
                $content .= "</div>";
                $content .= "</div>";
            }
        }
        else {
            $msg = "produk " . strtoupper($search_nama) . " tidak tersedia di daftar " . strtoupper($search_kategori) . ". Silahkan masukkan kata kunci lainnya.";
            $content .= "<div class='alert alert-warning' style='font-size: 15px;'>";
            $content .= $msg;
            $content .= "</div>";
        }


        $content .= "
                        <script>
                        
                        function valida(){
                            if($allow==0){
                                top.$(\"a[onclick = 'validasiSubmitDuluBro()']\").remove();
                            }
                        }

                        $(document).ready(function(){
                                valida();
                        });

                            function validasiSubmitDuluBro(){

                                var arrReadOnly = $('input[selectorize=iyes][readonly]');
                                var num=0;

                                jQuery.each(arrReadOnly, function(i,d){
                                    var currValue = $(arrReadOnly[i]).val() ? $(arrReadOnly[i]).val() : 0;
                                    var valueBefore = $(arrReadOnly[i]).attr('valuebefore') ? $(arrReadOnly[i]).attr('valuebefore') : 0;
                                    num++;
                                    if( valueBefore == currValue ){
                                    //tidak ada perubahan
                                        //console.log('%c' + num +  '. value_before: ' + $(arrReadOnly[i]).attr('valuebefore') + ' == value_current: ' + $(arrReadOnly[i]).val(), 'background: #222; color: #bada55' )
                                            $(arrReadOnly[i]).removeAttr('readonly').prop('disabled', true);
                                    }
                                    else{
                                    //ada perubahan dan akan disave
                                            $(arrReadOnly[i]).removeAttr('readonly').prop('disabled', false);
                                    }
                                });



                                if( num >= arrReadOnly.length ){
                                    document.getElementById('fPrice').submit();
                                }
                            }

                            function tableResize(){
                                var tdHeader = $('.td-header')[0].getBoundingClientRect().height;
                                var thHeader = $('.th-header')[0].getBoundingClientRect().height;
                                var tdData	 = $('.td-data')[0].getBoundingClientRect().height;
                                var totalHeight = tdHeader+thHeader;
                                var pTop	= $('.yHeader').css('padding-top');
                                    pTop	= pTop.replace('px', '');
                                var pBott	= $('.yHeader').css('padding-bottom');
                                    pBott	= pBott.replace('px', '');
                                var totalPadding = parseInt(pTop)+parseInt(pBott);
                                var pTopPn	= $('.produk_nama').css('padding-top');
                                    pTopPn	= pTopPn.replace('px', '');
                                var pBottPn	= $('.produk_nama').css('padding-bottom');
                                    pBottPn	= pBottPn.replace('px', '');
                                var totalPaddingPn = parseInt(pTopPn)+parseInt(pBottPn);
//console.log( 'tdHeader: ' + tdHeader );
//console.log( 'thHeader: ' + thHeader );
//console.log( 'totalHeight: ' + totalHeight );
//console.log( 'tdData: ' + tdData );
//console.log( 'totalPadding: ' + totalPadding );
//console.log( 'totalPaddingPn: ' + totalPaddingPn );
                                var tHtP = totalHeight-totalPadding;
                                var tDtP = tdData-totalPaddingPn;
                                //console.log( 'tHtP: ' + tHtP);
                                //console.log( 'tDtP: ' + tDtP);
                                $('.yHeader').height(totalHeight-totalPadding);
                                $('.produk_nama').height(tdData);

                            }

                            tableResize();
                            top.$('.table-responsive').floatingScroll();

                            $( window ).resize(function() {
                                tableResize();
                                console.log('resize left table');
                            });

                            $( 'input[selectorize=iyes]' ).dblclick(function() {
                                if($allow){
                                    $(this).attr('readonly', false)   
                                }
                            });

                            $( 'input[selectorize=iyes]' ).blur(function() {
                                var valuebefore  = $(this).attr('valuebefore') ? $(this).attr('valuebefore') : 0;
                                var valuecurrent = $(this).val() ? $(this).val() : 0;
                                    if( parseFloat(valuecurrent) != parseFloat(valuebefore) ){
                                    $(this).attr('readonly', false);
                                    var types = $(this).attr('zid') ? $(this).attr('zid') : 0;
                                    var cabang = $(this).attr('xid') ? $(this).attr('xid') : 0;
                                    var produk_id = $(this).attr('yid') ? $(this).attr('yid') : 0;
                                    if(cabang==-1){
                                        swal({
                                            title: 'Apakah ingin melanjutkan...???',
                                            text: 'Anda telah mengganti harga PUSAT, terapkan harga ini untuk semua cabang??',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'ya, terapkan disemua cabang',
                                            cancelButtonText: 'tidak, hanya PUSAT saja',
                                            onClose: () => {
                                                if( top.$('#btn_save2').length ){
                                                    top.$('#btn_save2').click();
                                                }
                                                else{
                                                    if( top.$('#btn_save').length ){
                                                        $('#btn_save').click();
                                                    }
                                                    else{
                                                        $('#btn_save').click();
                                                    }
                                                }
                                            }
                                        }).then( function () {
                                            var similiarData = $(\"input[zid][zid$='\"+types+\"']input[yid][yid$='\"+produk_id+\"']\");
                                            jQuery.each(similiarData, function(i,html){
                                                if( $(html).attr('xid') && $(html).attr('xid')!= -1 ){
                                                    $(html).val(valuecurrent);
                                                    $(html).removeAttr('readonly').prop('disabled', false);
                                                }
                                            });
                                            if( top.$('#btn_save2').length ){
                                                top.$('#btn_save2').click();
                                            }
                                            else{
                                                if( top.$('#btn_save').length ){
                                                    $('#btn_save').click();
                                                }
                                                else{
                                                    $('#btn_save').click();
                                                }
                                            }
                                        })
                                    }
                                    else{

                                    }
                                }
                                else{
                                    $(this).attr('readonly', true);
                                }
                            });

    $(document).ready( function(){

if( $('#ddtattbl').length > 0 ){
        var myNewTable = top.$('#ddtattbl').DataTable({
            sorting: false,
            pageLength: -1,
            searching: false,
        });

//        new top.$.fn.dataTable.FixedHeader( myNewTable );

        $('.dataTables_info').hide();
        $('.dataTables_paginate').hide();
        $('.dataTables_length').hide();

//        top.Holdon.close()

        $( '#leftBoxTable' ).scroll(function() {
            setTimeout( function(){
                top.$($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
            }, 1000);
        });
}



    });



                        </script>
                        ";

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "start_page" => $startPage,
            "form_target" => $formTarget,
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "error_msg" => $error,
            "submit_btn_label" => $buttonLabel,
            "stop_time" => "",
            "page_str" => $pageStr,
            "script_bottom" => ""
            //                "add_link" => $btn_save,
        ));

        $p->render();
        break;
    case "Images":
        $segments = $this->uri->segment_array();
        //        arrPrint($segments);
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $produk_jenis = $this->uri->segment(3);
        //cekHere($produk_jenis);
        //        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
        $p = New Layout("$title", "$subTitle", "application/template/blank.html");
        $attached = true;
        //        }
        //        else {
        //        $p = New Layout("$title", "$subTitle", "application/template/blank.html");
        //            $attached = false;
        //        }


        //    $content=$error;

        //        arrPrint($content);
        $contents = "<div class='bg-grey-1'>";
        $contents .= "<form id='imgform' action='$formTarget' method='post' enctype='multipart/form-data' target='result'>";

        $contents .= "<div class='panel panel-info' >";
        $contents .= "<div class='panel-body text-center' >";
        $contents .= "$content";
        $contents .= "<input type='hidden' name='parent_id' value='$parentId'>";
        $contents .= "<input type='hidden' name='status' value='1'>";
        $contents .= "<input type='hidden' name='jenis' value='$produk_jenis'>";
        $contents .= "</div>";

        $contents .= "<div class='panel-footer overflow-h'>";
        $contents .= "<button type='button' class='btn btn-info pull-right' onclick=\"document.getElementById('imgform').submit();\">upload images</button>";
        $contents .= "</div>";

        $contents .= "</div>";

        $contents .= "</form>";
        $contents .= "<div>";

        //        echo $contents;
        $p->addTags(array(
            //            "menu_left"        => callMenuLeft(),
            //            "trans_menu"       => callTransMenu(),
            //            "btn_back"         => callBackNav(),
            //            "start_page"       => $startPage,
            //            "form_target"      => $formTarget,
            "content" => $contents,
            ////            "profile_name"     => $this->session->login['nama'],
            ////            "self"             => $self,
            ////            "default_key"      => $defaultKey,
            ////            "error_msg"        => $error,
            //            "submit_btn_label" => $buttonLabel,
            ////            "stop_time" => "",
            //
            //            //                "add_link" => $btn_save,
        ));
        //
        $p->render();
        break;
    case "VendorOri":

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
            $p = New Layout("$title", "$subTitle", "application/template/blank.html");
            $attached = true;
        }
        else {
            $p = New Layout("$title", "$subTitle", "application/template/harga.html");
            $attached = false;
        }

        $content = "";

        $content .= $strViewHidden;

        if (sizeof($xLabels) > 0) {
            $header_prog = array();
            $isi_prog = array();
            $content .= "<div class='box box-warning margin-top-10 table-responsive'>";
            $content .= "test";
            if ($attached) {
                $content .= "<form method=\"post\" id=\"fPrice\" name=\"fPrice\" action=\"$formTarget\" target=\"result\">";
            }

            $content .= "<table class='table table-bordered table-hovered table-condensed no-margin' style=''>";
            $content .= "<thead>";

            //region header
            $content .= "<tr bgcolor='#e5e5e5' style='white-space: normal; vertical-align: middle;'>";
            $content .= "<th rowspan='2' style='white-space: normal; vertical-align: middle;' class='text-center'>No</th>";
            $content .= "<th rowspan='2' style='white-space: normal; vertical-align: middle;'>$yHeader</th>";
            $i = 0;
            foreach ($xLabels as $xId => $xNames) {
                $i++;
                $bg_color = $i % 2 == 0 ? "#696969" : "#C0C0C0";
                $colspan = sizeof($zLabels);
                $content .= "<th colspan='$colspan' style='min-width: 150px;max-width: 150px;vertical-align: middle;' class='text-center' tooltip='$xNames' bgcolor='$bg_color'>$xNames</th>";
            }
            $content .= "</tr>";

            $content .= "<tr >";
            $no = 0;
            foreach ($xLabels as $xId => $xNames) {
                $no++;
                $bg_color = $no % 2 == 0 ? "#F5F5DC" : "#FFE4C4";
                foreach ($zLabels as $zId => $zNames) {
                    $content .= "<td class='text-center' bgcolor='$bg_color'>$zNames</td>";
                }
            }
            $content .= "</tr>";
            $content .= "</thead>";

            $number = $pageOffside;
            foreach ($yLabels as $yId => $yNames) {
                $number++;
                $bg_color = $number % 2 == 0 ? "#F5F5DC" : "#F0F8FF";
                $content .= "<tr>";
                $content .= "<td bgcolor='f0f0f'><span class='btn-block text-right' >$number.</span></td>";
                $content .= "<td class='produk_nama'>$yNames</td>";

                foreach ($xLabels as $xId => $xNames) {
                    foreach ($zLabels as $zid => $zNames) {
                        $value = isset($values[$yId][$xId][$zid]) ? $values[$yId][$xId][$zid] : "0";
                        $history_link = isset($history[$yId][$xId][$zid]) ? $history[$yId][$xId][$zid] : "<a href='#' class='btn btn-warning' data-toggle='modal' data-target='#myModal'><i class='fa fa-clock-o'></i></a>";
                        $content .= "<td bgcolor='$bg_color' style='margin:0px;padding:0px;font-family:courier;'>" . "<div class='input-group'>" . "$value " . "<span class='input-group-btn'>" . "$history_link" . "</span>" . "</div>" . "</td>";
                    }
                }
                $content .= "</tr>";
            }

            $content .= "</table>";

            if ($attached) {
                $content .= "<div class='panel-body'>";
                $content .= "<span class='pull-right'>";
                $content .= "<a class='btn btn-info' onclick=\"document.getElementById('fPrice').submit();\">save prices*</a>";
                $content .= "</span>";
                $content .= "</div>";
                $content .= "</form>";
            }

            $content .= "</div>";
        }


        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "start_page" => $startPage,
            "form_target" => $formTarget,
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "error_msg" => $error,
            "submit_btn_label" => $buttonLabel,
            "stop_time" => "",
            "page_str" => $pageStr,

            //                "add_link" => $btn_save,
        ));

        $p->render();
        break;
    case "Vendor":

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
            $p = New Layout("$title", "$subTitle", "application/template/blank.html");
            $attached = true;
        }
        else {
            $p = New Layout("$title", "$subTitle", "application/template/harga.html");
            $attached = false;
        }

        $content = "";

        $content .= $strViewHidden;

        if (sizeof($xLabels) > 0) {
            $header_prog = array();
            $isi_prog = array();

            $content .= "<div style='' class='col-lg-3 no-padding'>";
            $content .= "<div class='box box-warning margin-top-10'>";
            $content .= "<table class='table table-bordered table-hovered table-condensed no-margin' style=''>";
            //region header1
            $content .= "<thead>";
            $content .= "<tr bgcolor='#e5e5e5' style='white-space: normal; vertical-align: middle;'>";
            $content .= "<th rowspan='2' style='white-space: normal; vertical-align: middle;' class='text-center'>No</th>";
            $content .= "<th class='yHeader' rowspan='2' style='white-space: normal; vertical-align: middle;'>$yHeader</th>";
            $content .= "</tr>";
            $content .= "</thead>";
            //endregion header1

            $number = $pageOffside;
            foreach ($yLabels as $yId => $yNames) {
                $number++;
                $bg_color = $number % 2 == 0 ? "#F5F5DC" : "#F0F8FF";
                $content .= "<tr style='clear: both;' class='produk_nama'>";
                $content .= "<td bgcolor='f0f0f'><span class='btn-block text-right' >$number.</span></td>";
                $content .= "<td>$yNames</td>";
                $content .= "</tr>";
            }

            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";

            $content .= "<div style='' class='col-lg-9 no-padding'>";
            $content .= "<div class='box box-warning margin-top-10 table-responsive'>";
            if ($attached) {
                $content .= "<form method=\"post\" id=\"fPrice\" name=\"fPrice\" action=\"$formTarget\" target=\"result\">";
            }
            $content .= "<table class='table table-bordered table-hovered table-condensed no-margin' style=''>";

            //region header2
            $content .= "<thead>";
            $content .= "<tr class='th-header' bgcolor='#e5e5e5' style='clear: both;white-space: normal; vertical-align: middle;'>";

            $i = 0;
            foreach ($xLabels as $xId => $xNames) {
                $i++;
                $bg_color = $i % 2 == 0 ? "#696969" : "#C0C0C0";
                $colspan = sizeof($zLabels);
                $content .= "<th id='$xId' colspan='$colspan' style='min-width: 150px;max-width: 150px;vertical-align: middle;' class='text-center' tooltip='$xNames' bgcolor='$bg_color'>$xNames</th>";
            }
            $content .= "</tr>";
            $content .= "<tr style='clear: both;' class='td-header'>";
            $no = 0;
            foreach ($xLabels as $xId => $xNames) {
                $no++;
                $bg_color = $no % 2 == 0 ? "#F5F5DC" : "#FFE4C4";
                foreach ($zLabels as $zId => $zNames) {
                    $content .= "<td class='text-center' bgcolor='$bg_color'>$zNames</td>";
                }
            }
            $content .= "</tr>";
            $content .= "</thead>";

            foreach ($yLabels as $yId => $yNames) {

                $content .= "<tr style='clear: both;' class='td-data'>";
                foreach ($xLabels as $xId => $xNames) {
                    foreach ($zLabels as $zid => $zNames) {

                        $value = isset($values[$yId][$xId][$zid]) ? $values[$yId][$xId][$zid] : "0";
                        $history_link = isset($history[$yId][$xId][$zid]) ? $history[$yId][$xId][$zid] : "<a href='#' class='btn btn-warning' data-toggle='modal' data-target='#myModal'><i class='fa fa-clock-o'></i></a>";
                        $content .= "<td bgcolor='$bg_color' style='margin:0px;padding:0px;font-family:courier;'>" . "<div class='input-group'>" . "$value " . "<span class='input-group-btn'>" . "$history_link" . "</span>" . "</div>" . "</td>";
                    }
                }
                $content .= "</tr>";

            }


            //endregion
            $content .= "</table>";
            if ($attached) {
                $content .= "<div class='panel-body'>";
                $content .= "<span class='pull-right'>";
                $content .= "<a class='btn btn-info' onclick=\"document.getElementById('fPrice').submit();\">save prices</a>";
                $content .= "</span>";
                $content .= "</div>";
                $content .= "</form>";
            }
            $content .= "</div>";
            $content .= "</div>";

        }

        $scriptBottom = "
                        <script>
                            var tdHeader = $('.td-header')[0].getBoundingClientRect().height;
                            var thHeader = $('.th-header')[0].getBoundingClientRect().height;
                            var tdData	 = $('.td-data')[0].getBoundingClientRect().height;
                            var totalHeight = tdHeader+thHeader;
                            var pTop	= $('.yHeader').css('padding-top');
                                pTop	= pTop.replace('px', '');
                            var pBott	= $('.yHeader').css('padding-bottom');
                                pBott	= pBott.replace('px', '');
                            var totalPadding = parseInt(pTop)+parseInt(pBott);
                            var pTopPn	= $('.produk_nama').css('padding-top');
                                pTopPn	= pTopPn.replace('px', '');
                            var pBottPn	= $('.produk_nama').css('padding-bottom');
                                pBottPn	= pBottPn.replace('px', '');
                            var totalPaddingPn = parseInt(pTopPn)+parseInt(pBottPn);
                            //console.log( 'tdHeader: ' + tdHeader);
                            //console.log( 'thHeader: ' + thHeader);
                            //console.log( 'totalHeight: ' + totalHeight);
                            //console.log( 'tdData: ' + tdData);
                            //console.log( 'totalPadding: ' + totalPadding);
                            //console.log( 'totalPaddingPn: ' + totalPaddingPn);
                            var tHtP = totalHeight-totalPadding;
                            var tDtP = tdData-totalPaddingPn;
                            //console.log( 'tHtP: ' + tHtP);
                            //console.log( 'tDtP: ' + tDtP);
                            $('.yHeader').height(totalHeight-totalPadding);
                            $('.produk_nama').height(tdData);
                        </script>
                        ";
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "start_page" => $startPage,
            "form_target" => $formTarget,
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "error_msg" => $error,
            "submit_btn_label" => $buttonLabel,
            "stop_time" => "",
            "page_str" => $pageStr,
            "script_bottom" => $scriptBottom,
        ));

        $p->render();
        break;
    case "suppliers":
        $segments = $this->uri->segment_array();
        //        arrPrint($segments);
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $produk_jenis = $this->uri->segment(3);
        //cekHere($produk_jenis);
        //        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
        $p = New Layout("$title", "$subTitle", "application/template/blank.html");
        $attached = true;
        //        }
        //        else {
        //        $p = New Layout("$title", "$subTitle", "application/template/blank.html");
        //            $attached = false;
        //        }


        //    $content=$error;

        //        arrPrint($content);
        $contents = "<div class='' >";
//        $contents .= "<form id='imgform' action='$formTarget' method='post' enctype='multipart/form-data' target='result'>";
        $contents .= "$content";
//        $contents .= "<input type='hidden' name='parent_id' value='$parentId'>";
//        $contents .= "<input type='hidden' name='status' value='1'>";
//        $contents .= "<input type='hidden' name='jenis' value='$produk_jenis'>";
//        $contents .= "<div class='panel-body'>";
//        $contents .= "<span class='pull-right'>";
//        $contents .= "<a class='btn btn-info' onclick=\"document.getElementById('imgform').submit();\">upload images</a>";
//        $contents .= "</span>";
//        $contents .= "</div>";
//        $contents .= "</form>";
        $contents .= "<div>";

        //        echo $contents;
        $p->addTags(array(
            //            "menu_left"        => callMenuLeft(),
            //            "trans_menu"       => callTransMenu(),
            //            "btn_back"         => callBackNav(),
            //            "start_page"       => $startPage,
            //            "form_target"      => $formTarget,
            "content" => $contents,
            ////            "profile_name"     => $this->session->login['nama'],
            ////            "self"             => $self,
            ////            "default_key"      => $defaultKey,
            ////            "error_msg"        => $error,
            //            "submit_btn_label" => $buttonLabel,
            ////            "stop_time" => "",
            //
            //            //                "add_link" => $btn_save,
        ));
        //
        $p->render();
        break;
}