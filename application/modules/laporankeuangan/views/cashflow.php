<?php
//cekHere($mode);
switch ($mode) {

    case "cashflow":
        //cekHere($defaultDate);
        //cekHere($oldDate);
        //        $defaultDate = "2019-09";
        //        $oldDate = "2019-09";

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        // $linkExcel = base_url(). "ExcelWriter/rugiLaba";
        $strDates = blobEncode($defaultDate);
        $strRekeningAliases = blobEncode($rekeningsNameAlias);
        $strRekenings = blobEncode($rekeningsName);
        $strNilais = blobEncode($rekenings);
        $excel_data = "rekening=$strRekenings&alias=$strRekeningAliases&nilai=$strNilais&date=$strDates";
        $excel_name = "rugi laba $defaultDate";
        $str = "";

        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";

        if (isset($dateSelector) && ($dateSelector == true)) {
            $str .= "<span class='input-group-add-on' >select month </span>";
            $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        }

        if (isset($linkExcel)) {
            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
            $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
        }

        if (isset($buttonMode) && ($buttonMode['enabled'] == true)) {
            $btn_label = $buttonMode['label'];
            $btn_link = $buttonMode['link'];
            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"location.href='$btn_link'\" title='download data'><i class='fa fa-bookmark-o'></i> $btn_label</button>";
        }

        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";


        if (sizeof($categories) > 0) {
            $idTbl = "cashflow";
            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='$idTbl' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td></td>";
//            $str .= "<td></td>";
            foreach ($headers as $key => $label) {
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                    $isExist_href = $key == "link" ? "" : "<td>$label</td>";

                }
                else {
                    if (!is_numeric($label)) {
                        $hLabel = $label;
                    }
                    else {
                        $hLabel = "";
                    }
                    $isExist_href = "<td>" . $hLabel;
                    $isExist_href .= "</td>";

                }

                $str .= "$isExist_href";
                $totals[$key] = 0;
            }
            $str .= "</tr>";

            $str .= "</thead>";

            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            $summaryTotal = 0;
            foreach ($categories as $catId => $catName) {
                // group top
                $catName_f = strtoupper($catName);
                $str .= "<tr>";
                $str .= "<td style='font-weight: bold;'>$catName_f</td>";
                $topHeaderValues = isset($topHeaderIsi[$catId]) ? formatField("debet", $topHeaderIsi[$catId]) : "";
                $str .= "<td>$topHeaderValues</td>";
                $str .= "</tr>";

                $summary = 0;
                // isi masing2 group top
                if (isset($rekenings[$catId])) {
                    foreach ($rekenings[$catId] as $rekID => $rekName) {
                        $rekName_f = ucwords($rekName);
                        $title2 = "mm_" . $rekID;
                        $target = base_url() . "laporancashflow/Neraca/detailCashflow/$rekID?date=2022-05";

                        $title3 = "nn_" . $rekID;

                        $str .= "<tr>";
                        $str .= "<td title2=$target title3=$title3 class='dt-nama-$idTbl'>";
                        $str .= "<a href='JavaScript:void(0);' onclick=\"\">";
                        $str .= "$rekName_f";
                        $str .= "</a>";
                        $str .= "</td>";

                        $values = isset($dataRekening[$rekID]) ? $dataRekening[$rekID] : 0;
                        foreach ($headers as $key => $label) {
                            if (isset($values[$key])) {
                                if (is_numeric($values[$key])) {
                                    if ($values[$key] >= 0) {
                                        $rVal = formatField($key, $values[$key]);
                                        $style = "style='text-align:right;'";

                                    }
                                    else {
                                        $rVal = "(" . number_format($values[$key] * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;color:red;'";
                                    }

                                    $summary += $values[$key];
                                    $summaryTotal += $values[$key];

                                }
                                else {
                                    $rVal = formatField_he_format($key, $values[$key]);
                                    $style = "";
                                }
                            }
                            else {
                                $rVal = "";
                                $style = "";
                            }
                            $isExist_href = "<td $style>$rVal";
                            $isExist_href .= "</td>";
                            $str .= "$isExist_href";
                        }
                        $str .= "</tr>";
                        //---------------------------
                    }
                }
                // summary bawah topHeader
                if (isset($topHeaderSummary[$catId])) {
                    if ($summary < 0) {
                        $style = "style='text-align:right;color:red;'";
                        $rVal = "(" . number_format($summary * -1, "0", ".", ",") . ")";
                    }
                    else {
                        $style = "";
                        $rVal = formatField("debet", $summary);
                    }
                    $str .= "<tr>";
                    $str .= "<td style='font-weight: bold;'>" . $topHeaderSummary[$catId] . "</td>";
                    $str .= "<td $style>" . $rVal . "</td>";
                    $str .= "</tr>";
                }

            }

            //---------------------
            if (round($summaryTotal, 2) != round($selisihKas, 2)) {
                $selisih = $summaryTotal - $selisihKas;
                $str .= "<tr>";
                $str .= "<td colspan='2' style='background-color:red;'>$summaryTotal != $selisihKas [$selisih]</td>";
                $str .= "</tr>";
            }
            //---------------------

            $str .= "</tbody>";
            $str .= $tfoot;


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $tbl_id = $idTbl;
                $str .= "<script>
               
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
                                    searching: false,
                                    order: [],
                                    paging: false,
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
        
                     // -----------------------------------------------------
                     // $('#$tbl_id tbody').on('click', \"td.dt-nama-$pengenal_kolom$tbl_id\", function () {
                     $('#$tbl_id tbody').on('click', \"td.dt-nama-$tbl_id\", function () {
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
                     });
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
            }
        }
        else {
            $str .= "-----";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                //                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $str,
                "profile_name" => $this->session->login['nama'],
            )
        );
        $p->render();
        break;

    case "detailCashflow":
        $idTbl = "detail";
        $str = "";
        if (isset($items) && (sizeof($items) > 0)) {
            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='$idTbl' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";
            // header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td>No.</td>";
            foreach ($headers as $key => $label) {
                if (!is_numeric($label)) {
                    $hLabel = $label;
                }
                else {
                    $hLabel = "";
                }
                $isExist_href = "<td>" . $hLabel;
                $isExist_href .= "</td>";
                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</thead>";
//arrprintHijau($items);
            // isi tabel
            $str .= "<tbody>";
            $no = 0;
            $totalBottom = array();
            foreach ($items as $itemsSpec) {
                $no++;
                $str .= "<tr bbgcolor='#f0f0f0'>";
                $str .= "<td>$no</td>";
                foreach ($headers as $key => $label) {
                    $val = isset($itemsSpec[$key]) ? $itemsSpec[$key] : "";
                    if (is_numeric($val)) {
                        $new_val = formatField_he_format("debet", $val, $itemsSpec['jenis_master'], $itemsSpec['modul_path']);
                        if (!in_array($label, $detailHeaderBlacklist)) {

                            if (!isset($totalBottom[$key])) {
                                $totalBottom[$key] = 0;
                            }
                            $totalBottom[$key] += $val;
                        }

                    }
                    else {
                        $new_val = formatField_he_format($key, $val, $itemsSpec['jenis_master'], $itemsSpec['modul_path']);
                    }
                    $str .= "<td>$new_val</td>";
                }
                $str .= "</tr>";
            }
            $colspan = sizeof($headers);
            $str .= "<tr>";
            $str .= "<td>-</td>";
            foreach ($headers as $key => $label) {
                $val = isset($totalBottom[$key]) ? formatField_he_format("debet", $totalBottom[$key]) : "";
                $str .= "<td>$val</td>";
            }
            $str .= "</tr>";
            $str .= "</tbody>";


            $str .= "</table class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";
            $str .= "</div class='table-responsive rugilaba'>";
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $tbl_id = $idTbl;
                $str .= "<script>
               
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
                                    searching: true,
                                    order: [],
                                    paging: false,
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
        
                     // -----------------------------------------------------
                     // $('#$tbl_id tbody').on('click', \"td.dt-nama-$pengenal_kolom$tbl_id\", function () {
                     $('#$tbl_id tbody').on('click', \"td.dt-nama-$tbl_id\", function () {
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
                     });
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
            }
        }
        else {

        }
        echo $str;
        break;

}