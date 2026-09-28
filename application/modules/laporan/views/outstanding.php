<?php

switch ($mode) {
    default:
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/laporan.html");

        /* --------------------------------------------------------------------
         * THEAD
         * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr>";
        $strHead .= "<td>no</td>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th>$hLabel</th>";
        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        // matiHere($jenistr);
        foreach ($master_data as $master_datum) {
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (isset($attrs['links'])) {
                    // matiHere();
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
                    // cekHere("$strTitle_head");
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                // $linkDetile = base_url() . $linking . "";
                // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;

                $strBody .= "<td $attr>$nilai_link</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }
            }
            $strBody .= "</tr>";

        }

        /* --------------------------------------------------------------------
         * TFOOD
         * --------------------------------------------------------------------*/
        $strFoot = "";
        $strFoot .= "<tr class='bg-danger'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            // $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        $tbl_id = "data_ok";
        $strTbl = "";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $strTbl .= "<script>
                
                $(document).ready( setTimeout( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                      
                    
                    var datareview = $('table#$tbl_id').DataTable({
                                     initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: false,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    processing: true,
                                    buttons: [
                                            'copy',
                                            {
                                                extend: 'csvHtml5',
                                                text: 'CSV',
                                                exportOptions: {
                                                    modifier: {
                                                        search: 'none'
                                                    },
                                                    format: {
                                                        body: function ( data, row, column, node ) {
                                                            if( /<\/?[a-z][\s\S]*>/i.test(data) ){
                                                                var indexnya = $(data).text().indexOf('>')
                                                                if(indexnya>0){
                                                                    var result = $(data).text().substring(indexnya + 1);
                                                                    return result;
                                                                    //console.error( result );
                                                                }

                                                                return $(data).text()
                                                            }
                                                            else{   
                                                                return data;
                                                                //console.log(data);
                                                            }
                                                        }
                                                    }
                                                },
                                            },
                                            'excel',
                                            'pdf',
                                            'print',
                                            ]
                                    
                                        });
                    
                    
                                    $('.table-responsive.tblid_$tbl_id').floatingScroll();
                                        $('.table-responsive.tblid_$tbl_id').scroll(
                                            delay_v2(function () {
                                            $('table#$tbl_id').DataTable().fixedHeader.adjust();
                                            }, 200)
                                        );
                    
                                    }, 500));

                
                </script>";
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCABANG
         * ---------------------------------------------------------------------------------------------*/
        $content = "";
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu'></div>";
            $str .= "<script>$('#sum_satu').load('$sum_satu');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERSALESMAN
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua'></div>";
            $str .= "<script>$('#sum_dua').load('$sum_dua');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCUSTOMER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_tiga)) {
            $str = "";
            $str .= "<div id='sum_tiga'></div>";
            $str .= "<script>$('#sum_tiga').load('$sum_tiga');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERSUPPLIER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_empat)) {
            $str = "";
            $str .= "<div id='sum_empat'></div>";
            $str .= "<script>$('#sum_empat').load('$sum_empat');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser LIMA
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_lima)) {
            $str = "";
            $str .= "<div id='sum_lima'></div>";
            $str .= "<script>$('#sum_lima').load('$sum_lima');</script>";
            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser ROW DATA perproduk transaksi
         * ---------------------------------------------------------------------------------------------*/
        $p->setLayoutBoxCss("box-info");
        $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $p->setLayoutBoxHeading("$subTitle", $btn_colaps);
        $p->setLayoutBoxBody(true);
        $content .= $p->layout_box($strTbl);
        //region loader popup syncro data
        $loaders = "";
        if (isset($loader)) {
            foreach ($loader as $div_id => $item_to_load) {
                $content .= "<div id='$div_id'></div>";

                // $loaders .= "\n $('#$div_id').load('$item_to_load')";
                $loaders .= "\n createPop('$item_to_load', '$div_id')";
            }

            $content .= "<script>$loaders</script>";
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
                "add_td" => "",
                "content" => $content,
                // navigasi
                "url" => $url,
                "date1" => $date1,
                "date2" => $date2,
                "date_min" => $date_min,
                "date_max" => $date_max,

            )
        );
        $p->render();
        break;

    case "langsung":

        // $arrTopHeaders = array(
        //     "penjualan"   => array(
        //         "label"   => "sales/packing list",
        //         "colspan" => "2",
        //     ),
        //     "outstanding" => array(
        //         "label"   => "outstanding",
        //         "colspan" => "2",
        //     ),
        // );
        foreach ($arrHeaders as $kolom_key => $arrHeader) {
            if (isset($arrHeader['rowspan'])) {
                $arrTopHeaders[$kolom_key] = $arrHeader;
            }
            else {
                $arrBottomHeaders[$kolom_key] = $arrHeader;
            }
        }
        $arrTopHeaders["previous"] = array(
            "label" => "previous",
            "attr_head" => "colspan='1'"
        );
        $arrTopHeaders["new"] = array(
            "label" => "new order",
            "attr_head" => "colspan='3'"
        );
        $arrTopHeaders["penjualan"] = array(
            "label" => "sales/packing list",
            "attr_head" => "colspan='2'"
        );
        $arrTopHeaders["outstanding"] = array(
            "label" => "outstanding",
            "attr_head" => "colspan='4'"
        );
        // $arrTopHeaders["outstanding_total"] = array(
        //     "label" => "total",
        //     "attr_head" => "colspan='1'"
        // );

        // arrPrint($arrTopHeaders);
        // arrPrintHijau($arrBottomHeaders);
        // $arrHeaders = array();
        // $arrHeaders = $arrBottomHeaders;
        // matiHere(__LINE__);

        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<td rowspan='2'>no</td>";
        foreach ($arrTopHeaders as $kolom => $arrHeader) {
            $topth = isset($arrHeader['topth']) ? "th" : "td";
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $rowspan = isset($arrHeader['rowspan']) ? "rowspan='" . $arrHeader['rowspan'] . "'" : "";
            $attr_head = isset($arrHeader['attr_head']) ? $arrHeader['attr_head'] : "";

            $strHead .= "<$topth $attr_head $rowspan>$hLabel</$topth>";
        }
        $strHead .= "</tr>";

        $strHead .= "<tr class='text-uppercase'>";
        // $strHead .= "<td>no</td>";
        // foreach ($arrHeaders as $kolom => $arrHeader) {
        foreach ($arrBottomHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $attr_head = isset($attrs['attr_head']) ? $attrs['attr_head'] : "";
            $strHead .= "<th $attr_head>$hLabel</th>";
        }
        $strHead .= "</tr>";

        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";

        foreach ($master_data as $master_datum) {
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    // $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                    $heading_mdl = isset($master_datum[$heading_key]) ? htmlspecialchars($master_datum[$heading_key]) : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                elseif (isset($attrs['collapsible'])) {
                    $layout_in = isset($attrs['collapsible']['layout']) ? $attrs['collapsible']['layout'] : "";
                    $layout_str = isset($attrs['collapsible']['layout']) ? "&layout=$layout_in" : "";
                    $reqKey = isset($attrs['collapsible']['key']) ? $attrs['collapsible']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
                }
                elseif (isset($attrs['popup'])) {
                    $reqKey = isset($attrs['popup']['key']) ? $attrs['popup']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['popup']['target']) ? $attrs['popup']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $nilai_link = "<a href='JavaScript:void(0);' title='popup' onclick=\"popBig('$linkDetile')\">$nilai_f</a>";
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                $strBody .= "<td $attr>$nilai_link</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }
            }
            $strBody .= "</tr>";

        }

        // cekBiru("$jml_autoTr");
        // arrPrintHijau($autoTr);
        $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        /* ---------------------------------------------------------------------------------------
         * generator scriptuntuk masuk ke js
         * ---------------------------------------------------------------------------------------*/
        $auto_tr = "";
        foreach ($autoTr as $item_kolom) {
            $auto_tr .= " $('#$tbl_id tbody').on('click', \"td.dt-nama-$item_kolom$tbl_id\", function () {
                         var tr = $(this).closest('tr');
                         var title2 = $(this).attr('title2');
                         var title3 = $(this).attr('title3');
                         var row = datareview$tbl_id.row(tr);
                         if(row.child.isShown()) {
                             row.child.hide();
                             tr.removeClass('shown');
                         }
                         else{
                             row.child(showChildProduk(title2,title3)).show();
                             loaderData(title3,title2);
                             tr.addClass('shown');
                         }
                     }); \n";
        }
        // ---------------------------------------------------------------------------------------

        $strFoot = "";
        $strFoot .= "<tr class='bg-danger'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }
        $strFoot .= "</tr>";


        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table border='1' class='table table-condensade table-striped table-hover-color-red' id='$tbl_id'>";
        $strTbl .= "<thead>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        // die($strTbl);
        // die();
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);
        $strTbl .= "<script>
                $modalSize
                
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview$tbl_id = $('table#$tbl_id').DataTable({
                                    initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                        
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
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
                                            // console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index])+\" </div> \"
                                                );
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>-</div>\"
                                                );
                                            }
                                        });
                                    }
 
                                        });
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                     // $('#$tbl_id tbody').on('click', \"td.dt-nama-$pengenal_kolom$tbl_id\", function () {
                     //     var tr = $(this).closest('tr');
                     //     var title2 = $(this).attr('title2');
                     //     var title3 = $(this).attr('title3');
                     //     var row = datareview$tbl_id.row(tr);
                     //     if(row.child.isShown()) {
                     //         row.child.hide();
                     //         tr.removeClass('shown');
                     //     }
                     //     else{
                     //         row.child(showChildProduk(title2,title3)).show();
                     //         loaderData(title3,title2);
                     //         tr.addClass('shown');
                     //     }
                     // });
                    //  ----------------------------------------------------------
                                    }, 500));

                    
                $('.table-responsive.tblid_$tbl_id').floatingScroll();
                $('.table-responsive.tblid_$tbl_id').scroll(
                    delay_v2(function () {
                        $('table#$tbl_id').DataTable().fixedHeader.adjust();
                    }, 200)
                );
                
               
                // ------------------------------------------------------
                function showChildProduk(d,m) {
                      // var rand = Math.floor(Math.random() * 10000);
                      // var rand = Math.floor(Date.now() / 1000);

                      var str_id = m;
                      var table = \"<div style='margin-left:25px;background-color: bisque;' id='\"+str_id+\"'>loading data ..... .....</div>\";

                      return table;                    
                }
                
                function loaderData(id,isi) {
                  // console.log(isi);
                  // console.log('uye');

                    $('#'+id).load(isi);
                }
                // ------------------------------------------------------
                
                </script>";

        // $content = "";
        // $content .= $strTbl;
        $url_now = current_url();
        $params = $_SERVER['QUERY_STRING'];
        $thislink = $url_now . "?" . $params;
        // cekKuning();
        // cekBiru($thislink);
        $reloader = "";
        if (isset($loader_div)) {
            $reloader = "<a href='JavaScript:;' onclick=\"$('#$loader_div').load('$thislink')\"><i class='fa fa-refresh'></i></a>";
        }
        $p = New Layout();
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu_'></div>";
            $str .= "<script>$('#sum_satu_').load('$sum_satu');</script>";

            echo $str;
        }
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua_'></div>";
            $str .= "<script>$('#sum_dua_').load('$sum_dua');</script>";
            echo $str;
        }
        $color_bar = isset($color_bar) ? $color_bar : "box-danger";
        $p->setLayoutBoxCss("$color_bar");
        $btn_colaps = "";
        if (isset($link_detile)) {
            $btn_colaps .= "<button type='button' title='minimize' class='btn btn-sm btn-box-tool text-red' title='menampilkan detile laporan summary' data-togle='tooltip' onclick=\"window.open('$link_detile');\"><i class='fa fa-fighter-jet'></i> ke detil laporan</button>";
        }
        $btn_colaps .= "<button type='button' title='minimize' class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";

        $p->setLayoutBoxHeading("$reloader $title", $btn_colaps);
        $p->setLayoutBoxBody(true);
        if (isset($layout) && $layout == false) {
            // echo __LINE__;
            echo "<div style='padding: 10px;'>$strTbl</div>";
        }
        else {
            echo $p->layout_box($strTbl);
        }

        break;

    case "langsung_2":

        // $arrTopHeaders = array(
        //     "penjualan"   => array(
        //         "label"   => "sales/packing list",
        //         "colspan" => "2",
        //     ),
        //     "outstanding" => array(
        //         "label"   => "outstanding",
        //         "colspan" => "2",
        //     ),
        // );
        $top_header = array();
        foreach ($arrHeaders as $kolom_key => $arrHeader) {
            if (isset($arrHeader['rowspan'])) {
                $arrTopHeaders[$kolom_key] = $arrHeader;
            }
            else {
                $arrBottomHeaders[$kolom_key] = $arrHeader;
            }

            if (isset($arrHeader['top_parent'])) {
                // cekHere($kolom_key . $arrHeader['top_parent']);
                $top_header[$arrHeader['top_parent']][] = $kolom_key;
            }
        }
        // arrPrintKuning($top_header);
        foreach ($top_header as $top_label => $itemKy) {
            $colspan = sizeof($itemKy);
            $arrTopHeaders[$top_label] = array(
                "label" => $top_label,
                "attr_head" => "colspan='$colspan'"
            );
        }
        // $arrTopHeaders["previous"] = array(
        //     "label" => "previous",
        //     "attr_head" => "colspan='3'"
        // );
        // $arrTopHeaders["new"] = array(
        //     "label" => "new order",
        //     "attr_head" => "colspan='5'"
        // );
        // $arrTopHeaders["penjualan"] = array(
        //     "label" => "sales/packing list",
        //     "attr_head" => "colspan='4'"
        // );
        // $arrTopHeaders["outstanding"] = array(
        //     "label" => "outstanding",
        //     "attr_head" => "colspan='7'"
        // );
        // $arrTopHeaders["outstanding_total"] = array(
        //     "label" => "total",
        //     "attr_head" => "colspan='1'"
        // );

        // arrPrint($arrTopHeaders);
        // arrPrintHijau($arrBottomHeaders);
        // $arrHeaders = array();
        // $arrHeaders = $arrBottomHeaders;
        // matiHere(__LINE__);

        $strHead = "";
        $strHead .= "<tr class='text-uppercase text-bold'>";
        $strHead .= "<td rowspan='2'>no</td>";
        foreach ($arrTopHeaders as $kolom => $arrHeader) {
            $topth = isset($arrHeader['topth']) ? "th" : "td";
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $rowspan = isset($arrHeader['rowspan']) ? "rowspan='" . $arrHeader['rowspan'] . "'" : "";
            $attr_head = isset($arrHeader['attr_head']) ? $arrHeader['attr_head'] : "";

            $strHead .= "<$topth $attr_head $rowspan>$hLabel</$topth>";
        }
        $strHead .= "</tr>";

        $strHead .= "<tr class='text-uppercase'>";
        // $strHead .= "<td>no</td>";
        // foreach ($arrHeaders as $kolom => $arrHeader) {
        foreach ($arrBottomHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $attr_head = isset($attrs['attr_head']) ? $attrs['attr_head'] : "";
            $strHead .= "<th $attr_head>$hLabel</th>";
        }
        $strHead .= "</tr>";

        $strBody = "";
        $no = 0;
        // $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";
        $modules = array(
            "588" => "penjualanproject",
            "7499" => "penjualanproject",
            "582" => "penjualan",
            // "282" => "penjualan",
            "382" => "penjualan",
        );

        foreach ($master_data as $master_datum) {

            $jenistr = $master_jenis = $master_datum['master_jenis'];
            $modulnya = isset($modules[$master_jenis]) ? $modules[$master_jenis] : "";
            // $modul_path = isset($modul_path) ? $modul_path : base_url() . "$modulnya/";
            $modul_path = base_url() . "$modulnya/";
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    // $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                    $heading_mdl = isset($master_datum[$heading_key]) ? htmlspecialchars($master_datum[$heading_key]) : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                elseif (isset($attrs['collapsible'])) {
                    $layout_in = isset($attrs['collapsible']['layout']) ? $attrs['collapsible']['layout'] : "";
                    $layout_str = isset($attrs['collapsible']['layout']) ? "&layout=$layout_in" : "";
                    $reqKey = isset($attrs['collapsible']['key']) ? $attrs['collapsible']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
                }
                elseif (isset($attrs['popup'])) {
                    $reqKey = isset($attrs['popup']['key']) ? $attrs['popup']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['popup']['target']) ? $attrs['popup']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $nilai_link = "<a href='JavaScript:void(0);' title='popup' onclick=\"popBig('$linkDetile')\">$nilai_f</a>";
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                $strBody .= "<td $attr>$nilai_link</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }
            }
            $strBody .= "</tr>";

        }

        // cekBiru("$jml_autoTr");
        // arrPrintHijau($autoTr);
        $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        /* ---------------------------------------------------------------------------------------
         * generator scriptuntuk masuk ke js
         * ---------------------------------------------------------------------------------------*/
        $auto_tr = "";
        foreach ($autoTr as $item_kolom) {
            $auto_tr .= " $('#$tbl_id tbody').on('click', \"td.dt-nama-$item_kolom$tbl_id\", function () {
                         var tr = $(this).closest('tr');
                         var title2 = $(this).attr('title2');
                         var title3 = $(this).attr('title3');
                         var row = datareview$tbl_id.row(tr);
                         if(row.child.isShown()) {
                             row.child.hide();
                             tr.removeClass('shown');
                         }
                         else{
                             row.child(showChildProduk(title2,title3)).show();
                             loaderData(title3,title2);
                             tr.addClass('shown');
                         }
                     }); \n";
        }
        // ---------------------------------------------------------------------------------------

        $strFoot = "";
        $strFoot .= "<tr class='bg-danger'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table border='1' class='table table-condensade table-striped table-hover-color-red' id='$tbl_id'>";
        $strTbl .= "<thead>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        // die($strTbl);
        // die();
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);
        $strTbl .= "<script>
                $modalSize
                
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview$tbl_id = $('table#$tbl_id').DataTable({
                                    initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                        
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
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
                                            // console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    // dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                    dpageTotal[id_n_index] += intVal( accounting.unformat($(obj).html()) );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] != 0 ){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index])+\" </div> \"
                                                );
                                            }
                                             else {
                                                 console.log(dpageTotal[id_n_index]);
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>-</div>\"
                                                );
                                            }
                                        });
                                    }
 
                                        });
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                     // $('#$tbl_id tbody').on('click', \"td.dt-nama-$pengenal_kolom$tbl_id\", function () {
                     //     var tr = $(this).closest('tr');
                     //     var title2 = $(this).attr('title2');
                     //     var title3 = $(this).attr('title3');
                     //     var row = datareview$tbl_id.row(tr);
                     //     if(row.child.isShown()) {
                     //         row.child.hide();
                     //         tr.removeClass('shown');
                     //     }
                     //     else{
                     //         row.child(showChildProduk(title2,title3)).show();
                     //         loaderData(title3,title2);
                     //         tr.addClass('shown');
                     //     }
                     // });
                    //  ----------------------------------------------------------
                                    }, 500));

                    
                $('.table-responsive.tblid_$tbl_id').floatingScroll();
                $('.table-responsive.tblid_$tbl_id').scroll(
                    delay_v2(function () {
                        $('table#$tbl_id').DataTable().fixedHeader.adjust();
                    }, 200)
                );
                
               
                // ------------------------------------------------------
                function showChildProduk(d,m) {
                      // var rand = Math.floor(Math.random() * 10000);
                      // var rand = Math.floor(Date.now() / 1000);

                      var str_id = m;
                      var table = \"<div style='margin-left:25px;background-color: bisque;' id='\"+str_id+\"'>loading data ..... .....</div>\";

                      return table;                    
                }
                
                function loaderData(id,isi) {
                  // console.log(isi);
                  // console.log('uye');

                    $('#'+id).load(isi);
                }
                // ------------------------------------------------------
                
                </script>";

        // $content = "";
        // $content .= $strTbl;
        $url_now = current_url();
        $params = $_SERVER['QUERY_STRING'];
        $thislink = $url_now . "?" . $params;
        // cekKuning();
        // cekBiru($thislink);
        $reloader = "";
        if (isset($loader_div)) {
            $reloader = "<a href='JavaScript:;' onclick=\"$('#$loader_div').load('$thislink')\"><i class='fa fa-refresh'></i></a>";
        }
        $p = New Layout();
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu_'></div>";
            $str .= "<script>$('#sum_satu_').load('$sum_satu');</script>";

            echo $str;
        }
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua_'></div>";
            $str .= "<script>$('#sum_dua_').load('$sum_dua');</script>";
            echo $str;
        }
        $color_bar = isset($color_bar) ? $color_bar : "box-danger";
        $p->setLayoutBoxCss("$color_bar");
        $btn_colaps = "";
        if (isset($link_detile)) {
            $btn_colaps .= "<button type='button' title='minimize' class='btn btn-sm btn-box-tool text-red' title='menampilkan detile laporan summary' data-togle='tooltip' onclick=\"window.open('$link_detile');\"><i class='fa fa-fighter-jet'></i> ke detil laporan</button>";
        }
        $btn_colaps .= "<button type='button' title='minimize' class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";

        $p->setLayoutBoxHeading("$reloader $title", $btn_colaps);
        $p->setLayoutBoxBody(true);
        if (isset($layout) && $layout == false) {
            // echo __LINE__;
            echo "<div style='padding: 10px;'>$strTbl</div>";
        }
        else {
            echo $p->layout_box($strTbl);
        }

        break;

    // row bisa collapsible
    case "langsung_indek":
        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<td>no</td>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th>$hLabel</th>";
        }
        $strHead .= "</tr>";

        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";

        foreach ($master_data as $master_datum) {
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $str_logic = "";
                if (isset($attrs['logics'])) {
                    $ltexs = isset($attrs['logics']['text']) ? $attrs['logics']['text'] : "";
                    $lnilai = $attrs['logics']['nilai']; // tidak berguna ni, coba cari cari yg lain

                    $str_logic = $nilai < 0 ? "<br><div class='meta'>$ltexs</div>" : '';
                }

                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    // $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                    $heading_mdl = isset($master_datum[$heading_key]) ? htmlspecialchars($master_datum[$heading_key]) : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                elseif (isset($attrs['collapsible'])) {
                    $layout_in = isset($attrs['collapsible']['layout']) ? $attrs['collapsible']['layout'] : "";
                    $layout_str = isset($attrs['collapsible']['layout']) ? "&layout=$layout_in" : "";
                    $reqKey = isset($attrs['collapsible']['key']) ? $attrs['collapsible']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
                }
                elseif (isset($attrs['popup'])) {
                    $reqKey = isset($attrs['popup']['key']) ? $attrs['popup']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['popup']['target']) ? $attrs['popup']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $nilai_link = "<a href='JavaScript:void(0);' title='popup' onclick=\"popBig('$linkDetile')\">$nilai_f</a>";
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                // if(isset($attrs['collapsible'])){
                //
                //     $nilai_link = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>";
                // }
                // else{
                //     $nilai_link = $nilai_f;
                // }

                $strBody .= "<td  title2='$pengenal_link' title3='$pengenal_masterid' $attr>$nilai_link $str_logic</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }


            }
            $strBody .= "</tr>";

        }

        // cekBiru("$jml_autoTr");
        // arrPrintHijau($autoTr);
        $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        /* ---------------------------------------------------------------------------------------
         * generator scriptuntuk masuk ke js
         * ---------------------------------------------------------------------------------------*/
        $auto_tr = "";
        foreach ($autoTr as $item_kolom) {
            $auto_tr .= " $('#$tbl_id tbody').on('click', \"td.dt-nama-$item_kolom$tbl_id\", function () {
                         var tr = $(this).closest('tr');
                         var title2 = $(this).attr('title2');
                         var title3 = $(this).attr('title3');
                         var row = datareview$tbl_id.row(tr);
                         if(row.child.isShown()) {
                             row.child.hide();
                             tr.removeClass('shown');
                         }
                         else{
                             row.child(showChildProduk(title2,title3)).show();
                             loaderData(title3,title2);
                             tr.addClass('shown');
                         }
                     }); \n";
        }
        // ---------------------------------------------------------------------------------------
        $kolom_tanpa_format = array(
            "fulldate", "dtime"
        );
        $strFoot = "";
        $strFoot .= "<tr class='bg-danger font-size-1-2'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) && (!in_array($format_key, $kolom_tanpa_format)) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr title='$format_key'>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red' id='$tbl_id'>";
        $strTbl .= "<caption class='hidden'>testing</caption>";
        $strTbl .= "<thead>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);
        $strTbl .= "<script>
                $modalSize
                               
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview$tbl_id = $('table#$tbl_id').DataTable({
                                    initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                        
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
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
                                            console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index])+\" </div> \"
                                                );
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>-</div>\"
                                                );
                                            }
                                        });
                                    }
 
                                        });
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                     // $('#$tbl_id tbody').on('click', \"td.dt-nama-$pengenal_kolom$tbl_id\", function () {
                     //     var tr = $(this).closest('tr');
                     //     var title2 = $(this).attr('title2');
                     //     var title3 = $(this).attr('title3');
                     //     var row = datareview$tbl_id.row(tr);
                     //     if(row.child.isShown()) {
                     //         row.child.hide();
                     //         tr.removeClass('shown');
                     //     }
                     //     else{
                     //         row.child(showChildProduk(title2,title3)).show();
                     //         loaderData(title3,title2);
                     //         tr.addClass('shown');
                     //     }
                     // });
                    //  ----------------------------------------------------------
                                    }, 500));

                    
                $('.table-responsive.tblid_$tbl_id').floatingScroll();
                $('.table-responsive.tblid_$tbl_id').scroll(
                    delay_v2(function () {
                        $('table#$tbl_id').DataTable().fixedHeader.adjust();
                    }, 200)
                );
                
               
                // ------------------------------------------------------
                function showChildProduk(d,m) {
                      // var rand = Math.floor(Math.random() * 10000);
                      // var rand = Math.floor(Date.now() / 1000);

                      var str_id = m;
                      var table = \"<div style='margin-left:25px;background-color: bisque;' id='\"+str_id+\"'>loading data ..... .....</div>\";

                      return table;                    
                }
                
                function loaderData(id,isi) {
                  // console.log(isi);
                  // console.log('uye');

                    $('#'+id).load(isi);
                }
                // ------------------------------------------------------
                
                </script>";

        // $content = "";
        // $content .= $strTbl;
        $url_now = current_url();
        $params = $_SERVER['QUERY_STRING'];
        $thislink = $url_now . "?" . $params;
        // cekKuning();
        // cekBiru($thislink);
        $reloader = "";
        if (isset($loader_div)) {
            $reloader = "<a href='JavaScript:;' onclick=\"$('#$loader_div').load('$thislink')\"><i class='fa fa-refresh'></i></a>";
        }
        $p = New Layout();
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu_'></div>";
            $str .= "<script>$('#sum_satu_').load('$sum_satu');</script>";

            echo $str;
        }
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua_'></div>";
            $str .= "<script>$('#sum_dua_').load('$sum_dua');</script>";
            echo $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCUSTOMER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_tiga)) {
            $str = "";
            $str .= "<div id='sum_tiga'></div>";
            $str .= "<script>$('#sum_tiga').load('$sum_tiga');</script>";

            echo $str;
        }
        $color_bar = isset($color_bar) ? $color_bar : "box-danger";
        $p->setLayoutBoxCss("$color_bar");
        $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $p->setLayoutBoxHeading("$reloader $title", $btn_colaps);
        $p->setLayoutBoxBody(true);

        if (isset($layout) && $layout == false) {
            // echo __LINE__;
            echo "<div style='padding: 10px;'>$strTbl</div>";
        }
        else {
            echo $p->layout_box($strTbl);
        }

        break;

    case "langsung_detail":
        foreach ($master_data as $master_datum) {


            $strHead = "";
            $strHead .= "<tr class='text-uppercase'>";
            $strHead .= "<td>no</td>";
            foreach ($arrHeaders as $kolom => $arrHeader) {
                $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
                $strHead .= "<th>$hLabel</th>";
            }
            $strHead .= "</tr>";

            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            $strGet = $_SERVER['QUERY_STRING'];

            foreach ($master_data as $master_datum) {
                $no++;
                $strBody .= "<tr >";
                $strBody .= "<td>$no</td>";
                foreach ($arrHeaders as $kolom => $attrs) {
                    // $nilai = $master_datum[$kolom];
                    // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                    $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                    $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                    if (isset($attrs['links'])) {
                        $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                        // $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                        $heading_mdl = isset($master_datum[$heading_key]) ? htmlspecialchars($master_datum[$heading_key]) : $nilai;
                        $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                        $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                        $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                        $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                        $linkDetile = base_url() . $linking . "";
                        $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                        $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                    }
                    else {
                        // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                        // $linkDetile = base_url() . $linking . "";
                        // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                        $nilai_link = $nilai_f;
                    }

                    $strBody .= "<td $attr>$nilai_link</td>";

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }
                $strBody .= "</tr>";

            }

            $strFoot = "";
            $strFoot .= "<tr class='bg-danger'>";
            $strFoot .= "<th></th>";
            foreach ($arrHeaders as $kolom => $attrs) {
                $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
                $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
                // $label = $attrs['label'];
                $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

                $strFoot .= "<th $attr>";
                $strFoot .= $fNilai_f;
                $strFoot .= "</th>";
            }

            $tbl_id = isset($data_id) ? $data_id : "data_ok1";
            $strTbl = "";

            $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
            $strTbl .= "<table class='table table-condensade table-striped' id='$tbl_id'>";
            $strTbl .= "<thead>";
            $strTbl .= $strHead;
            $strTbl .= "</thead>";
            $strTbl .= "<tbody>";
            $strTbl .= $strBody;
            $strTbl .= "</tbody>";
            $strTbl .= "<tfoot>";
            $strTbl .= $strFoot;
            $strTbl .= "</tfoot>";
            $strTbl .= "</table>";
            $strTbl .= "</div>";

            $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
            $strTbl .= "<script>
                $modalSize
                
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview = $('table#$tbl_id').DataTable({
                                    initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                        
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ]
 
                                        });
        
                                    }, 500));

                    
                $('.table-responsive.tblid_$tbl_id').floatingScroll();
                $('.table-responsive.tblid_$tbl_id').scroll(
                    delay_v2(function () {
                        $('table#$tbl_id').DataTable().fixedHeader.adjust();
                    }, 200)
                );
                
                </script>";

            // $content = "";
            // $content .= $strTbl;
            $url_now = current_url();
            $params = $_SERVER['QUERY_STRING'];
            $thislink = $url_now . "?" . $params;
            // cekKuning();
            // cekBiru($thislink);
            $reloader = "";
            if (isset($loader_div)) {
                $reloader = "<a href='JavaScript:;' onclick=\"$('#$loader_div').load('$thislink')\"><i class='fa fa-refresh'></i></a>";
            }
            $p = New Layout();
            if (isset($sum_satu)) {
                $str = "";
                $str .= "<div id='sum_satu_'></div>";
                $str .= "<script>$('#sum_satu_').load('$sum_satu');</script>";

                echo $str;
            }
            if (isset($sum_dua)) {
                $str = "";
                $str .= "<div id='sum_dua_'></div>";
                $str .= "<script>$('#sum_dua_').load('$sum_dua');</script>";
                echo $str;
            }
            $color_bar = isset($color_bar) ? $color_bar : "box-danger";
            $p->setLayoutBoxCss("$color_bar");
            $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
            $p->setLayoutBoxHeading("$reloader $title", $btn_colaps);
            $p->setLayoutBoxBody(true);
            echo $p->layout_box($strTbl);

        }

        break;

    case "indek":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/outstanding_indek.html");

        /* --------------------------------------------------------------------
         * THEAD
         * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr>";
        $strHead .= "<td>no</td>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th>$hLabel</th>";
        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        // matiHere($jenistr);
        foreach ($master_data as $master_datum) {
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (isset($attrs['links'])) {
                    // matiHere();
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
                    // cekHere("$strTitle_head");
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                // $linkDetile = base_url() . $linking . "";
                // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;

                $strBody .= "<td $attr>$nilai_link</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }
            }
            $strBody .= "</tr>";

        }

        /* --------------------------------------------------------------------
         * TFOOD
         * --------------------------------------------------------------------*/
        $kolom_tanpa_format = array(
            "fulldate"
        );
        $strFoot = "";
        $strFoot .= "<tr class='bg-danger'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            // $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) && (!array($format_key, $kolom_tanpa_format)) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            // $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        $tbl_id = "data_ok";
        $strTbl = "";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $strTbl .= "<script>
                
                $(document).ready( setTimeout( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                      
                    
                    var datareview = $('table#$tbl_id').DataTable({
                                     initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: false,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    processing: true,
                                    buttons: [
                                            'copy',
                                            {
                                                extend: 'csvHtml5',
                                                text: 'CSV',
                                                exportOptions: {
                                                    modifier: {
                                                        search: 'none'
                                                    },
                                                    format: {
                                                        body: function ( data, row, column, node ) {
                                                            if( /<\/?[a-z][\s\S]*>/i.test(data) ){
                                                                var indexnya = $(data).text().indexOf('>')
                                                                if(indexnya>0){
                                                                    var result = $(data).text().substring(indexnya + 1);
                                                                    return result;
                                                                    //console.error( result );
                                                                }

                                                                return $(data).text()
                                                            }
                                                            else{   
                                                                return data;
                                                                //console.log(data);
                                                            }
                                                        }
                                                    }
                                                },
                                            },
                                            'excel',
                                            'pdf',
                                            'print',
                                            ]
                                    
                                        });
                    
                    
                                    $('.table-responsive.tblid_$tbl_id').floatingScroll();
                                        $('.table-responsive.tblid_$tbl_id').scroll(
                                            delay_v2(function () {
                                            $('table#$tbl_id').DataTable().fixedHeader.adjust();
                                            }, 200)
                                        );
                    
                                    }, 500));

                
                </script>";
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCABANG
         * ---------------------------------------------------------------------------------------------*/
        $content = "";
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu'></div>";
            $str .= "<script>$('#sum_satu').load('$sum_satu');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERSALESMAN
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua'></div>";
            $str .= "<script>$('#sum_dua').load('$sum_dua');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCUSTOMER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_tiga)) {
            $str = "";
            $str .= "<div id='sum_tiga'></div>";
            $str .= "<script>$('#sum_tiga').load('$sum_tiga');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERSUPPLIER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_empat)) {
            $str = "";
            $str .= "<div id='sum_empat'></div>";
            $str .= "<script>$('#sum_empat').load('$sum_empat');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser LIMA
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_lima)) {
            $str = "";
            $str .= "<div id='sum_lima'></div>";
            $str .= "<script>$('#sum_lima').load('$sum_lima');</script>";
            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser ROW DATA perproduk transaksi
         * ---------------------------------------------------------------------------------------------*/
        // $p->setLayoutBoxCss("box-info");
        // $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        // $p->setLayoutBoxHeading("$subTitle", $btn_colaps);
        // $p->setLayoutBoxBody(true);
        // $content .= $p->layout_box($strTbl);
        if (isset($sum_null)) {
            // mati_disini();
            $str = "";
            $str .= "<div id='sum_null'></div>";
            $str .= "<script>$('#sum_null').load('$sum_null');</script>";

            $content .= $str;
        }

        //region loader popup syncro data
        $loaders = "";
        if (isset($loader)) {
            foreach ($loader as $div_id => $item_to_load) {
                $content .= "<div id='$div_id'></div>";

                // $loaders .= "\n $('#$div_id').load('$item_to_load')";
                $loaders .= "\n createPop('$item_to_load', '$div_id')";
            }

            $content .= "<script>$loaders</script>";
        }
        //endregion
        $scriptBottom = isset($scriptBottom) ? $scriptBottom : "";

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "add_td" => isset($add_td) ? $add_td : "",
                "content" => $content,
                // navigasi
                "url" => $url,
                "date1" => $date1,
                "date2" => $date2,
                "date_min" => $date_min,
                "date_max" => $date_max,
                "scriptBottom" => $scriptBottom,
            )
        );
        $p->render();
        break;

    case "langsung_2_header":
        $rowsapan = sizeof($arrHeader_add) > 0 ? "2" : "1";
        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<td rowspan='$rowsapan'>no</td>";
        foreach ($arrHeaders as $kolom => $arrHeader) {

            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th rowspan='$rowsapan'>$hLabel</th>";
        }

        if (isset($arrHeader_add) && sizeof($arrHeader_add) > 0) {
            foreach ($arrHeader_add as $kolom => $arrHeaderAdd) {
                $hLabel = isset($arrHeaderAdd['label']) ? $arrHeaderAdd['label'] : $kolom;
                $attr = $arrHeaderAdd["attr"];
                $strHead .= "<th $attr>$hLabel</th>";
            }

        }

        $strHead .= "</tr>";
        if (sizeof($header2)) {

            $strHead .= "<tr class='text-uppercase'>";
            foreach ($arrHeader_add as $headerAd_key => $headerAddData) {
                foreach ($header2 as $key_kolom => $keyDataField) {
                    $label2 = isset($keyDataField["label"]) ? $keyDataField["label"] : $key_kolom;
                    $strHead .= "<th >$label2</th>";
                }
            }

            $strHead .= "</tr class='text-uppercase'>";
        }

        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        // arrPrint($arrHeader_add);
        $totalFooter = array();
        foreach ($master_data as $master_datum) {
            // arrPrint($master_datum);
            $extern_id = $master_datum["extern_id"];
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                // cekMerah($attrs);
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    // $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                    $heading_mdl = isset($master_datum[$heading_key]) ? htmlspecialchars($master_datum[$heading_key]) : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                $strBody .= "<td $attr>$nilai_link</td>";

                // if (isset($attrs['summary'])) {
                //     if (!isset($totals[$kolom])) {
                //         $totals[$kolom] = 0;
                //     }
                //     $totals[$kolom] += $nilai;
                // }
            }
            // arrPrint($master_data_gudang);
            //region tambahan header2
            if (sizeof($master_data_gudang) > 0) {
                foreach ($arrHeader_add as $gLabel => $gData) {
                    $GID = $gData["label_id"];
                    foreach ($header2 as $hKey => $hDatas) {
                        $val = isset($master_data_gudang[$extern_id][$GID][$hKey]) ? $master_data_gudang[$extern_id][$GID][$hKey] : 0;

                        $strBody .= "<td >" . formatField_he_format("qty", $val) . "</td>";

                        if (!isset($totalFooter[$GID]["total_" . $hKey])) {
                            $totalFooter[$GID]["total_" . $hKey] = 0;
                        }
                        $totalFooter[$GID]["total_" . $hKey] += $val;
                    }

                }

                // if()
            }

            //endregion
            $strBody .= "</tr>";

        }
        // arrPrint($totalFooter);
        $strFoot = "";
        $strFoot .= "<tr class='bg-danger'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";

        }
        if (sizeof($totalFooter) > 0) {
            foreach ($arrHeader_add as $gLabel => $gData) {
                $gID = $gData["label_id"];
                foreach ($header2 as $hKey => $hDatas) {
                    $strFoot .= "<th $attr>";
                    $strFoot .= formatField_he_format("harga", $totalFooter[$gID]["total_" . $hKey]);
                    $strFoot .= "</th>";
                }

            }
        }
        $strFoot .= "</tr class='bg-danger'>";
        $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped' id='$tbl_id'>";
        $strTbl .= "<thead>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        // die($strTbl);
        // die();
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $strTbl .= "<script>
                $modalSize
                
                $(document).ready( delay_v2( function(){
                    var datareview = $('table#$tbl_id').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ]
 
                                        });
        
                                    }, 500));

                    
                $('.table-responsive.tblid_$tbl_id').floatingScroll();
                $('.table-responsive.tblid_$tbl_id').scroll(
                    delay_v2(function () {
                        $('table#$tbl_id').DataTable().fixedHeader.adjust();
                    }, 200)
                );
                
                </script>";

        // $content = "";
        // $content .= $strTbl;
        $url_now = current_url();
        $params = $_SERVER['QUERY_STRING'];
        $thislink = $url_now . "?" . $params;
        // cekKuning();
        // cekBiru($thislink);
        $reloader = "";
        if (isset($loader_div)) {
            $reloader = "<a href='JavaScript:;' onclick=\"$('#$loader_div').load('$thislink')\"><i class='fa fa-refresh'></i></a>";
        }
        $p = New Layout();
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu_'></div>";
            $str .= "<script>$('#sum_satu_').load('$sum_satu');</script>";

            echo $str;
        }
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua_'></div>";
            $str .= "<script>$('#sum_dua_').load('$sum_dua');</script>";
            echo $str;
        }
        $color_bar = isset($color_bar) ? $color_bar : "box-danger";
        $p->setLayoutBoxCss("$color_bar");
        $btn_colaps = "";
        if (isset($link_detile)) {
            $btn_colaps .= "<button class='btn btn-sm btn-box-tool text-red' title='menampilkan detile laporan summary' data-togle='tooltip' onclick=\"window.open('$link_detile');\"><i class='fa fa-fighter-jet'></i> ke detil laporan</button>";
        }
        $btn_colaps .= "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $p->setLayoutBoxHeading("$reloader $title", $btn_colaps);
        $p->setLayoutBoxBody(true);
        if (isset($layout) && $layout == false) {
            // echo __LINE__;
            echo "<div style='padding: 10px;'>$strTbl</div>";
        }
        else {
            echo $p->layout_box($strTbl);
        }

        break;

    case "test":
        $td = "";
        foreach ($result as $item) {
            // arrPrintHijau($item);
            $hd = "";
            $td .= "<tr>";
            foreach ($item as $kolom => $nilai) {
                $hd .= "<td>$kolom</td>";

                $td .= "<td>$nilai</td>";
            }
            $td .= "</tr>";

            $rekening = $item->rekening;

        }

        // echo "<button onClick=\"SelfCopy(this.id)\"  id=\"1\">1</button>";
        echo "<div id='2'>";
        echo "<table border='1' rules='all'>";
        echo "<tr>$hd</tr>";
        echo "$td";
        echo "</table>";
        echo "<div>";
        break;
}

?>