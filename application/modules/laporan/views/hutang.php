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
                $nilai_f = isset($attrs['format']) ? ($nilai >= 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

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
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "add_td"           => "",
                "content"          => $content,
                // navigasi
                "url"              => $url,
                "date1"            => $date1,
                "date2"            => $date2,
                "date_min"         => $date_min,
                "date_max"         => $date_max,

            )
        );
        $p->render();
        break;

    case "produk_harian":
        $tgl_max = formatTanggal($date2, 'd');
        $bln_max = formatTanggal($date2, 'm');
        $thn_max = formatTanggal($date2, 'Y');
        // cekKuning("$date2 | $tgl_max");
        $colspan_jml = $tgl_max;
        $header_str = $bln_max == dtimeNow('m') ? "MTD" : "Bulanan";
        $margin_avg_f = number_format($margin_avg, "2") . "%";

        // $total_bawah_penjualan = $total_bawah['penjualan'];
        // $total_bawah_penjualan_f = number_format($total_bawah_penjualan);
        // $total_bawah_penjualan_str = "<div class='text-color-blue'>$total_bawah_penjualan_f</div><div id='nilai_halaman'></div>";


        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($masterHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;

            $strHead .= "<th rowspan='2'>$hLabel</th>";
        }
        $strHead .= "<td colspan='$colspan_jml'><b class='text-renggang-10'>Nilai Penjualan Harian</b></td>";
        $strHead .= "<td colspan='2'><b class=''>Penjualan Bulan</b></td>";
        // $strHead .= "<td colspan='2'><b class=''>Penjualan Bulan (Penuh Hari)</b></td>";
        // $strHead .= "<td rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>total unit</b><span id='pg_unit'></span></td>";
        $strHead .= "<td colspan='3'><b>Nilai Rata-rata Harian</b> <r class='font-size-1-2 pull-right'>$margin_avg_f</r></td>";


        $strHead .= "</tr>";

        $strHead .= "<tr class=''>";
        for ($i = 1; $i <= $tgl_max; $i++) {

            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $dtLabel = "$thn_max-$bln_max-$tgl_key";

            $strHead .= "<th id='tgl_$tgl_key' class='text-center'>$tgl_key<span id='pg_$i'></span></th>";
        }
        $strHead .= "<th class='text-center' style='vertical-align: bottom !important;' id='unit'><b>unit</b><span id='pg_unit'></span></th>";
        $strHead .= "<th id='nilai'>$header_str<span id='pg_nilai'></span></th>";
        // ----------------------------
        $strHead .= "<th id='unit_avg'>Unit<span id='pg_unit_avg'></span></th>";
        $strHead .= "<th id='nilai_avg'>Nilai<span id='pg_nilai_avg'></span></th>";
        $strHead .= "<th id='margin_avg'>Margin<span id='pg_margin_avg'></span></thid>";
        $strHead .= "</tr>";


        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";

        // arrPrintWebs($master_data);
        // arrPrintWebs(array_slice($master_data,0,2));
        // arrPrintWebs($child_data);
        // matiHere(__LINE__);
        $sum_bawah_total = 0;
        foreach ($master_data as $produk_id => $master_datum) {
            $produk_data_jualans = isset($child_data[$produk_id]) ? $child_data[$produk_id] : array();
            $nama = $master_datum["nama"];
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($masterHeaders as $kolom => $attrs) {
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

            $sum_kanan = 0;
            for ($i = 1; $i <= $tgl_max; $i++) {

                $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
                $dtLabel = "$thn_max-$bln_max-$tgl_key";
                // $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;
                // $chNilai = isset($produk_data_jualans[$dtLabel]['sum_kredit']) ? ($produk_data_jualans[$dtLabel]['sum_kredit'] * 1) : 0;
                $chNilai = isset($produk_data_jualans[$dtLabel]['sum_kredit']) ? ($produk_data_jualans[$dtLabel]['sum_kredit'] * 1) : 0;

                // $chkredit = isset($produk_data_jualans[$dtLabel]['sum_kredit']) ? ($produk_data_jualans[$dtLabel]['sum_kredit'] * 1) : 0;
                // $chDebet = isset($produk_data_jualans[$dtLabel]['sum_debet']) ? ($produk_data_jualans[$dtLabel]['sum_debet'] * 1) : 0;
                // $chNilai = ($chkredit + $chDebet);
                $chNilai_f = number_format($chNilai, 0);
                // cekHere("$dtLabel $chNilai");
                $strBody .= "<td class='text-right' data-order='$chNilai'>$chNilai_f</td>";

                $sum_kanan += $chNilai;

                if (!isset($sum_bawah_harian[$tgl_key])) {
                    $sum_bawah_harian[$tgl_key] = 0;
                }
                $sum_bawah_harian[$tgl_key] += $chNilai;
                // $sum_bawah_key[$dtLabel][$tgl_key][] = $chNilai;
                $sum_bawah_total += $chNilai;
            }

            $sum_kanan_f = number_format($sum_kanan, 0);
            // $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            $avg_margin = isset($margin_data[$produk_id]) ? $margin_data[$produk_id] : 0;

            $harian_qty = isset($qty_data[$produk_id]) ? $qty_data[$produk_id] : 0;
            $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / $tgl_max) : 0;
            $avg_harian = $sum_kanan / $tgl_max;
            $avg_harian_f = number_format($avg_harian, 2);
            $avg_margin_f = number_format($avg_margin, 2);
            $avg_harian_qty_f = number_format($avg_harian_qty, 2);
            $harian_qty_f = number_format($harian_qty, 0);

            $avg_margin_l = $avg_margin_f;
            if ($avg_margin <= 1) {
                $link_detil = base_url() . "laporan/Penjualan/viewprodukdetile?id=$produk_id";
                $linkModal = modalDialogBtn("$nama", $link_detil);
                $avg_margin_l = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$nama'>$avg_margin_f</a>";
            }

            // $avg_harian_qty = 0;
            $strBody .= "<td class='text-right bg-warning' data-order='$harian_qty'>$harian_qty_f</td>";
            $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            // -----------------------------------------------
            $strBody .= "<td class='text-right bg-success' data-order='$avg_harian_qty'>$avg_harian_qty_f</td>";
            $strBody .= "<td class='text-right bg-success' data-order='$avg_harian'>$avg_harian_f</td>";
            $strBody .= "<td class='text-right bg-info' data-order='$avg_margin'>$avg_margin_l</td>";

            $strBody .= "</tr>";

            $sum_bawah_nilai += $sum_kanan;
            $sum_bawah_qty += $harian_qty;
            $sum_bawah_qty_avg += $avg_harian_qty;
            $sum_bawah_avg += $avg_harian;
            $sum_bawah_margin_avg += $avg_margin;
        }
        $sum_bawah_qty_f = number_format($sum_bawah_qty);
        $sum_bawah_qty_avg_f = number_format($sum_bawah_qty_avg);
        $sum_bawah_nilai_f = number_format($sum_bawah_nilai);
        $sum_bawah_nilai_avg_f = number_format($sum_bawah_avg);
        $sum_bawah_margin_avg_f = number_format($sum_bawah_margin_avg);
        // cekBiru("$sum_bawah_nilai || $sum_bawah_total");
        // cekBiru("qty bawah: $sum_bawah_qty");
        cekBiru($sum_bawah_harian);
        // cekBiru($sum_bawah_key);
        // cekBiru(array_sum($sum_bawah_key['2023-06-01']['01']));
        cekOrange(array_sum($sum_bawah_harian));
        // cekBiru($sum_bawah_harian);
        // arrPrintHijau($total_bawah);
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
        foreach ($masterHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        for ($i = 1; $i <= $tgl_max; $i++) {

            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $dtLabel = "$thn_max-$bln_max-$tgl_key";
            // $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;

            $strFoot .= "<th class='text-right'>0</th>";
        }
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";

        $strFoot .= "</tr>";
        // matiHere();
        //         cekHere(__LINE__);
        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
            .dataTables_info {
            margin-top: 0;
            padding-top: 4px !important;
                margin-right: 10px;
                text-transform: uppercase;
                color: blue !important;
                font-size: 14px;
            }
        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensed table-bordered table-striped table-hover-color-red' id='$tbl_id'>";
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
        $jml_informasi = count($margin_data);

        $persen_10 = 10;
        $jml_10 = round($jml_informasi * ($persen_10 / 100));
        $persen_10_f = $persen_10 . "%";

        $persen_pareto = 20;
        $jml_pareto = round($jml_informasi * ($persen_pareto / 100));
        $persen_pareto_f = $persen_pareto . "%";

        $num_kolom = $tgl_max + count($masterHeaders) + 1;
        // cekHere("jml informasi data:: $jml_informasi --- $jml_pareto || $num_kolom");
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);

        $js_str = "";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $nilai_bawah = $sum_bawah_harian[$tgl_key];
            $nilai_bawah_f = number_format($nilai_bawah);

            $js_str .= "$('<br><b class=\"text-orange\">$nilai_bawah_f</b>').appendTo('#tgl_$tgl_key');\n";
        }
        $strTbl .= "<script>        
                $modalSize
                $js_str
                $('<div><b><r>$sum_bawah_qty_f</r></b></div>').appendTo('#unit');
                $('<br><b><r>$sum_bawah_nilai_f</r></b>').appendTo('#nilai');
                // $('<div class=\"text-green\"><b>$sum_bawah_qty_avg_f</b></div>').appendTo('#unit_avg');
                // $('<div class=\"text-green\"><b>$sum_bawah_nilai_avg_f</b></div>').appendTo('#nilai_avg');
                // //$('<div class=\"text-green\"><b>$sum_bawah_margin_avg_f</b></div>').appendTo('#margin_avg');
                
               
                // $('<div class=\"loading_2\">Loading</div>').appendTo('.box-body');

                            
                    
                    </script>";

        $strTbl .= "<script>
                $('.box-body').addClass('loading_2');
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        // $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    $.fn.dataTable.ext.search.push(
                                function( settings, searchData, index, rowData, counter ) {
                                    var col = parseFloat( removeCommas(searchData[$num_kolom]) ) || 0; // using the data from the 4th column
                                    if(localStorage.showzero*1){
                                        return true;
                                    }
                                    else{
                                        if ( col*1 > 0 ){
                                            return true;
                                        }
                                        return false;
                                    }
                                }
                            );
                    var text_button = 'Tampilkan/sembunyikan Penjualan nol';
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
                                        
                                        // $('div.loading_2').remove();
                                        $(\".box-body\").removeClass(\"loading_2\");
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                     dom: '<\'top\'ilBfprt<\'clear\'>>rt<\'bottom\'ip<\'clear\'>>',
                                    // dom: 'lBfrtip',
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
                                    fixedHeader: true,
                                    stateSave: true,

                                    order: [[$num_kolom, 'desc']],
                                    lengthMenu: [ [10, 15, 20, 50, $jml_10, $jml_pareto, -1], [10, 15, 20, 50, '$persen_10_f','$persen_pareto_f', 'All'] ],
                                    pageLength: $jml_pareto,
                                    buttons: [
                                            'copy',
                                            {extend:'csv', footer: true},
                                            'excel',
                                            'pdf',
                                            'print',
                                            { extend: 'excelHtml5', footer: true },
                                            {

                                                action: function ( e, dt, node, config ) {
                                                    console.log('my button click');
        
                                                    //$('tr.hidden').removeClass('hidden');
                                                    if(localStorage.showzero*1){
                                                        localStorage.showzero = 0
                                                        datareview$tbl_id.draw();
                                                        // var text_button = 'tampilkan data';
                                                    }
                                                    else{
                                                        localStorage.showzero = 1
                                                        datareview$tbl_id.draw();
                                                        // var text_button = 'sembunyikan data';
                                                    }
                                                    console.log('localStorage.showzero: ' , localStorage.showzero);
                                                },
                                                
                                                text: text_button,
                                            }
                                    ],

                                    infoCallback: function( settings, start, end, max, total, pre ) {
                                        var api = this.api();
                                        var pageInfo = api.page.info();
                                     
                                        return 'menampilkan halaman <b>'+ (pageInfo.page+1) +'</b> dari <b>'+ pageInfo.pages + '</b>';
                                    },
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
                                        var jml_kolom = (arrayFooter.length) - 1;
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
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);

                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                                
                                                if((id_n_index > 4) && (id_n_index < jml_kolom)){                                                
                                                    if ($('#ph_'+id_n_index).length === 0) {
                                                    
                                                        $( api.column(id_n_index).header() ).append(
                                                            \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                                        );
                                                    }                                                                                                                                          
                                                    
                                                    $(\"#ph_\"+id_n_index).html(
                                                        \"<b>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                                    );
                                                }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
 
                     });
                     
                    top.$('#hide').click(function() {
                         $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                             console.log(datareview$tbl_id.row(dataIndex));
                                return $( datareview$tbl_id.row(dataIndex).node(30) ) == 0;
                            });
                        datareview$tbl_id.draw();
                    });    
                    top.$('#reset').click(function() {
                        $.fn.dataTable.ext.search.pop();
                        datareview$tbl_id.draw();
                    });
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                     // $('#$tbl_id tbody').on('click', \"td.dt-nama-$pengenal_kolom$tbl_id\", function () {
                    //  ----------------------------------------------------------
                }, 500));
                                        
                    </script>";
        $strTbl .= "<script>
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
    case "produk_bulanan":
        $bulan_namas = namaBulan();
        // $tgl_max = formatTanggal($date2, 'd');
        $thn_max = formatTanggal($date2, 'Y');
        $tgl_max = $bln_max = formatTanggal($date2, 'm') - 1;

        if ($thn_max == "2023") {
            $tgl_max = $bln_max = formatTanggal($date2, 'm') - 1;
            // $tgl_max = $bln_max = formatTanggal($date2, 'm');
        }
        // cekKuning("$date2 | $tgl_max");
        $colspan_jml = $tgl_max;
        $header_str = $bln_max == dtimeNow('m') ? "YTD" : "Tahunan";
        $margin_avg_f = number_format($margin_avg, "2") . "%";

        // $total_bawah_penjualan = $total_bawah['penjualan'];
        // $total_bawah_penjualan_f = number_format($total_bawah_penjualan);
        // $total_bawah_penjualan_str = "<div class='text-color-blue'>$total_bawah_penjualan_f</div><div id='nilai_halaman'></div>";


        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($masterHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;

            $strHead .= "<th rowspan='2'>$hLabel</th>";
        }
        $strHead .= "<td colspan='$colspan_jml'><b class='text-renggang-10'>Nilai Penjualan Bulanan</b></td>";
        $strHead .= "<td colspan='2'><b class=''>Penjualan Tahun (Penuh Bulan)</b></td>";
        // $strHead .= "<td rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>total unit</b><span id='pg_unit'></span></td>";
        $strHead .= "<td colspan='3'><b>Nilai Rata-rata Bulanan</b> <r class='font-size-1-2 pull-right'>$margin_avg_f</r></td>";

        // cekHere($tgl_max);
        $strHead .= "</tr>";

        $strHead .= "<tr>";
        for ($i = 1; $i <= $tgl_max; $i++) {

            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $bln_nama = $bulan_namas[$tgl_key];
            $dtLabel = "$thn_max-$bln_max-$tgl_key";

            $str_kanan = $i == 1 ? "<div style='float: left;text-align: left;'><br><div class='text-orange'>Total Produk</div><div class='text-primary'>Total 1 page</div></div>" : "";

            $strHead .= "<th id='tgl_$tgl_key' class='text-center'>$bln_nama $str_kanan<span id='pg_$i' title='total halaman ini'></span></th>";
        }
        $strHead .= "<th class='text-center' style='vertical-align: bottom !important;' id='unit'><b>Unit</b><span id='pg_unit'></span></th>";
        $strHead .= "<th id='nilai'>Nilai<span id='pg_nilai'></span></th>";

        $strHead .= "<th id='unit_avg'>Unit<span id='pg_unit_avg'></span></th>";
        $strHead .= "<th id='nilai_avg'>Nilai<span id='pg_nilai_avg'></span></th>";
        $strHead .= "<th id='margin_avg'>Margin<span id='pg_margin_avg'></span></thid>";
        $strHead .= "</tr>";


        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";

        // arrPrintWebs($master_data);
        // arrPrintWebs(array_slice($master_data,0,2));
        // arrPrintKuning($child_data);
        // matiHere(__LINE__);

        foreach ($master_data as $produk_id => $master_datum) {
            $nama = $master_datum["nama"];
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($masterHeaders as $kolom => $attrs) {
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

            $sum_kanan = 0;
            for ($i = 1; $i <= $tgl_max; $i++) {

                $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
                $dtLabel = "$thn_max-$bln_key";
                // cekHere("$dtLabel");
                $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;
                $chNilai_f = number_format($chNilai, 0);


                $strBody .= "<td class='text-right' data-order='$chNilai'>$chNilai_f</td>";

                $sum_kanan += $chNilai;

                if (!isset($sum_bawah_harian[$bln_key])) {
                    $sum_bawah_harian[$bln_key] = 0;
                }
                $sum_bawah_harian[$bln_key] += $chNilai;
            }
            /*----summary kanan----------*/
            $sum_kanan_f = number_format($sum_kanan, 0);
            // $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            $avg_margin = isset($margin_data[$produk_id]) ? $margin_data[$produk_id] : 0;

            $harian_qty = isset($qty_data[$produk_id]) ? $qty_data[$produk_id] : 0;
            /* --------------------------------------------------------------------------------
             * exeption tahun awal mengunkan aplikasi
             * --------------------------------------------------------------------------------*/
            if ($thn_max == "2023") {
                $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / ($tgl_max - 3)) : 0;
                $avg_harian = $sum_kanan / ($tgl_max - 3);
            }
            else {
                $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / $tgl_max) : 0;
                $avg_harian = $sum_kanan / $tgl_max;
            }

            $avg_harian_f = number_format($avg_harian, 2);
            $avg_margin_f = number_format($avg_margin, 2);
            $avg_harian_qty_f = number_format($avg_harian_qty, 2);
            $harian_qty_f = number_format($harian_qty, 0);

            $avg_margin_l = $avg_margin_f;
            if ($avg_margin <= 1) {
                $link_detil = base_url() . "laporan/Penjualan/viewprodukdetile?id=$produk_id";
                $linkModal = modalDialogBtn("$nama", $link_detil);
                $avg_margin_l = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$nama'>$avg_margin_f</a>";
            }

            // $avg_harian_qty = 0;
            $strBody .= "<td class='text-right bg-warning' data-order='$harian_qty'>$harian_qty_f</td>";
            $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            // -----------------------------------------------------------
            $strBody .= "<td class='text-right bg-success' data-order='$avg_harian_qty'>$avg_harian_qty_f</td>";
            $strBody .= "<td class='text-right bg-success' data-order='$avg_harian'>$avg_harian_f</td>";
            $strBody .= "<td class='text-right bg-info' data-order='$avg_margin'>$avg_margin_l</td>";

            $strBody .= "</tr>";

            $sum_bawah_nilai += $sum_kanan;
            $sum_bawah_qty += $harian_qty;

            $sum_bawah_qty_avg += $avg_harian_qty;
            $sum_bawah_avg += $avg_harian;
            $sum_bawah_margin_avg += $avg_margin;
        }
        $sum_bawah_qty_f = number_format($sum_bawah_qty);
        $sum_bawah_qty_avg_f = number_format($sum_bawah_qty_avg);
        $sum_bawah_nilai_f = number_format($sum_bawah_nilai);
        $sum_bawah_nilai_avg_f = number_format($sum_bawah_avg);
        $sum_bawah_margin_avg_f = number_format($sum_bawah_margin_avg);
        // cekBiru($total_bawah);
        // cekBiru("qty bawah: $sum_bawah_qty");
        // cekBiru("$jml_autoTr");
        // cekBiru($sum_bawah_harian);
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
        foreach ($masterHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        for ($i = 1; $i <= $tgl_max; $i++) {

            $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $dtLabel = "$thn_max-$bln_key";
            // $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;

            $strFoot .= "<th class='text-right'>0</th>";
        }
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";

        $strFoot .= "</tr>";
        // matiHere();
        //         cekHere(__LINE__);
        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
            .dataTables_info {
            margin-top: 0;
            padding-top: 4px !important;
                margin-right: 10px;
                text-transform: uppercase;
                color: blue !important;
                font-size: 14px;
            }
        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red table-bordered' id='$tbl_id'>";
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
        $jml_informasi = count($margin_data);

        $persen_10 = 10;
        $jml_10 = round($jml_informasi * ($persen_10 / 100));
        $persen_10_f = $persen_10 . "%";

        $persen_pareto = 20;
        $jml_pareto = round($jml_informasi * ($persen_pareto / 100));
        $persen_pareto_f = $persen_pareto . "%";

        $num_kolom = $tgl_max + count($masterHeaders) + 1;
        // cekHere("jml informasi data:: $jml_informasi --- $jml_pareto || $num_kolom");
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);

        $js_str = "";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $nilai_bawah = $sum_bawah_harian[$bln_key];
            $nilai_bawah_f = number_format($nilai_bawah);

            $js_str .= "$('<br><b class=\"text-orange\" title=\"total data\">$nilai_bawah_f</b>').appendTo('#tgl_$bln_key');\n";
        }
        $strTbl .= "<script>        
                $modalSize
                $js_str
                $('<div><b><r>$sum_bawah_qty_f</r></b></div>').appendTo('#unit');
                $('<div class=\"text-center\"><b><r>$sum_bawah_nilai_f</r></b></div>').appendTo('#nilai');
                $('<div class=\"text-green text-center\"><b>$sum_bawah_qty_avg_f</b></div>').appendTo('#unit_avg');
                $('<div class=\"text-green text-center\"><b>$sum_bawah_nilai_avg_f</b></div>').appendTo('#nilai_avg');
                //$('<div class=\"text-green text-center\"><b>$sum_bawah_margin_avg_f</b></div>').appendTo('#margin_avg');
                
                $('.box-body').addClass('loading_2');
                // $('<div class=\"loading_2\">Loading</div>').appendTo('.box-body');
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        // $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    $.fn.dataTable.ext.search.push(
                                function( settings, searchData, index, rowData, counter ) {
                                    var col = parseFloat( removeCommas(searchData[$num_kolom]) ) || 0; // using the data from the 4th column
                                    if(localStorage.showzero*1){
                                        return true;
                                    }
                                    else{
                                        if ( col*1 > 0 ){
                                            return true;
                                        }
                                        return false;
                                    }
                                }
                            );
                            
                    var text_button = 'Tampilkan/sembunyikan Penjualan nol';
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
                                        
                                        // $('div.loading_2').remove();
                                        $(\".box-body\").removeClass(\"loading_2\");
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                     dom: '<\'top\'ilBfprt<\'clear\'>>rt<\'bottom\'ip<\'clear\'>>',
                                    // dom: 'lBfrtip',
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
                                    fixedHeader: true,
                                    stateSave: true,

                                     order: [[$num_kolom, 'desc']],
                                    lengthMenu: [ [10, 15, 20, 50, $jml_10, $jml_pareto, -1], [10, 15, 20, 50, '$persen_10_f','$persen_pareto_f', 'All'] ],
                                    pageLength: $jml_pareto,
                                    buttons: [
                                            'copy',
                                            {extend:'csv', footer: true},
                                            'excel',
                                            'pdf',
                                            'print',
                                            { extend: 'excelHtml5', footer: true },
                                            {

                                                action: function ( e, dt, node, config ) {
                                                    console.log('my button click');
        
                                                    //$('tr.hidden').removeClass('hidden');
                                                    if(localStorage.showzero*1){
                                                        localStorage.showzero = 0
                                                        datareview$tbl_id.draw();
                                                        var text_button = 'tampilkan data';
                                                    }
                                                    else{
                                                        localStorage.showzero = 1
                                                        datareview$tbl_id.draw();
                                                        var text_button = 'sembunyikan data';
                                                    }
                                                    console.log('localStorage.showzero: ' , localStorage.showzero);
                                                },
                                                
                                                text: text_button,
                                            }
                                    ],

                                    infoCallback: function( settings, start, end, max, total, pre ) {
                                        var api = this.api();
                                        var pageInfo = api.page.info();
                                     
                                        return 'menampilkan halaman <b>'+ (pageInfo.page+1) +'</b> dari <b>'+ pageInfo.pages + '</b>';
                                    },
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
                                        var jml_kolom = (arrayFooter.length) - 1;
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
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);

                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                                
                                                if((id_n_index > 4) && (id_n_index < jml_kolom)){                                                
                                                    if ($('#ph_'+id_n_index).length === 0) {
                                                    
                                                        $( api.column(id_n_index).header() ).append(
                                                            \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                                        );
                                                    }                                                                                                                                          
                                                    
                                                    $(\"#ph_\"+id_n_index).html(
                                                        \"<b title='total halaman'>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                                    );
                                                }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
 
                     });
                    
                    top.$('#hide').click(function() {
                         $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                             console.log(datareview$tbl_id.row(dataIndex));
                                return $( datareview$tbl_id.row(dataIndex).node(30) ) == 0;
                            });
                        datareview$tbl_id.draw();
                    });    
                    top.$('#reset').click(function() {
                        $.fn.dataTable.ext.search.pop();
                        datareview$tbl_id.draw();
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
    case "produk_bulanan_per":
        $bulan_namas = namaBulan();
        // $tgl_max = formatTanggal($date2, 'd');
        $thn_max = formatTanggal($date2, 'Y');
        $tgl_max = $bln_max = formatTanggal($date2, 'm') - 0;

        if ($thn_max == "2023") {
            $tgl_max = $bln_max = formatTanggal($date2, 'm') - 0;
            // $tgl_max = $bln_max = formatTanggal($date2, 'm');
        }
        // cekKuning("$date2 | $tgl_max");
        $colspan_jml = $tgl_max * 2;
        $header_str = $bln_max == dtimeNow('m') ? "YTD" : "Tahunan";
        $margin_avg_f = number_format($margin_avg, "2") . "%";

        // $total_bawah_penjualan = $total_bawah['penjualan'];
        // $total_bawah_penjualan_f = number_format($total_bawah_penjualan);
        // $total_bawah_penjualan_str = "<div class='text-color-blue'>$total_bawah_penjualan_f</div><div id='nilai_halaman'></div>";


        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<th rowspan='3'>no</th>";
        foreach ($masterHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;

            $strHead .= "<th rowspan='3'>$hLabel</th>";
        }
        $strHead .= "<td colspan='$colspan_jml'><b class='text-renggang-10'>Nilai $title_now Bulanan</b></td>";
        $strHead .= "<td colspan='2'><b class=''>$title_now $judul_lap</b></td>";
        // $strHead .= "<td rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>total unit</b><span id='pg_unit'></span></td>";
        // $strHead .= "<td colspan='3'><b>Nilai Rata-rata Bulanan</b> <r class='font-size-1-2 pull-right'>$margin_avg_f</r></td>";
        // $strHead .= "<td colspan='3'><b>Nilai Rata-rata Bulanan</b></td>";

        // cekHere($tgl_max);
        $strHead .= "</tr>";

        $strHead .= "<tr>";
        for ($i = 1; $i <= $tgl_max; $i++) {

            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $label_now = $tgl_max == $i ? "(MTD)" : "";
            $bln_nama = $bulan_namas[$tgl_key] . " $label_now";
            $dtLabel = "$thn_max-$bln_max-$tgl_key";

            $str_kanan = $i == 1 ? "<div style='float: left;text-align: left;'><br><div class='text-orange'>Total Produk</div><div class='text-primary'>Total 1 page</div></div>" : "";

            // $strHead .= "<th id='tgl_$tgl_key' class='text-center'>$bln_nama $str_kanan<span id='pg_$i' title='total halaman ini'></span></th>";
            // $strHead .= "<th id='tgl_$tgl_key' colspan='2' class='text-center'>$bln_nama <span id='pg_$i' title='total halaman ini'></span></th>";
            $strHead .= "<th colspan='2' class='text-center' title='$tgl_key'>$bln_nama</th>";
        }
        // --------------------
        $strHead .= "<th rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>Unit</b><span id='pg_unit'></span></th>";
        $strHead .= "<th rowspan='2' id='nilai'>Nilai<span id='pg_nilai'></span></th>";

        // $strHead .= "<th rowspan='2' id='unit_avg'>Unit<span id='pg_unit_avg'></span></th>";
        // $strHead .= "<th rowspan='2' id='nilai_avg'>Nilai<span id='pg_nilai_avg'></span></th>";
        // $strHead .= "<th rowspan='2' id='margin_avg'>Margin<span id='pg_margin_avg'></span></th>";
        $strHead .= "</tr>";

        /*sub header*/
        $strHead .= "<tr>";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $strHead .= "<th>unit</th>";
            $strHead .= "<th id='tgl_$tgl_key'>nilai</th>";
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

        // arrPrintWebs($master_data);
        // arrPrintWebs(array_slice($master_data,0,2));
        // arrPrintKuning($child_data);
        // matiHere(__LINE__);
        $sum_bawah_nilai = 0;
        $sum_bawah_qty = 0;
        $sum_bawah_qty_avg = 0;
        $sum_bawah_avg = 0;
        $sum_bawah_margin_avg = 0;
        foreach ($master_data as $produk_id => $master_datum) {
            $nama = $master_datum["nama"];
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($masterHeaders as $kolom => $attrs) {
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

            $pembagi = 1000000;
            $sum_kanan = 0;
            for ($i = 1; $i <= $tgl_max; $i++) {

                $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
                $dtLabel = "$thn_max-$bln_key";
                // cekHere("$dtLabel");
                $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['netto'] * 1) / $pembagi : 0) : 0;
                $chNilai_f = number_format($chNilai, 0);

                $chQty = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['netto_qty'] * 1) : 0) : 0;
                $chQty_f = number_format($chQty, 0);


                $strBody .= "<td class='text-right' data-order='$chQty'>$chQty_f</td>";
                $strBody .= "<td class='text-right' data-order='$chNilai'>$chNilai_f</td>";

                $sum_kanan += $chNilai;

                if (!isset($sum_bawah_harian[$bln_key])) {
                    $sum_bawah_harian[$bln_key] = 0;
                }
                $sum_bawah_harian[$bln_key] += $chNilai;
            }
            /*----summary kanan----------*/
            $sum_kanan_f = number_format($sum_kanan, 0);
            // $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            $avg_margin = isset($margin_data[$produk_id]) ? $margin_data[$produk_id] : 0;

            $harian_qty = isset($qty_data[$produk_id]) ? $qty_data[$produk_id] : 0;
            /* --------------------------------------------------------------------------------
             * exeption tahun awal mengunkan aplikasi
             * --------------------------------------------------------------------------------*/
            if ($thn_max == "2023") {
                $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / ($tgl_max - 3)) : 0;
                $avg_harian = $sum_kanan / ($tgl_max - 3);
            }
            else {
                $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / $tgl_max) : 0;
                $avg_harian = $sum_kanan / $tgl_max;
            }

            $avg_harian_f = number_format($avg_harian, 2);
            $avg_margin_f = number_format($avg_margin, 2);
            $avg_harian_qty_f = number_format($avg_harian_qty, 2);
            $harian_qty_f = number_format($harian_qty, 0);

            $avg_margin_l = $avg_margin_f;
            if ($avg_margin <= 1) {
                $link_detil = base_url() . "laporan/Penjualan/viewprodukdetile?id=$produk_id";
                $linkModal = modalDialogBtn("$nama", $link_detil);
                $avg_margin_l = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$nama'>$avg_margin_f</a>";
            }

            // $avg_harian_qty = 0;
            $strBody .= "<td class='text-right bg-warning' data-order='$harian_qty'>$harian_qty_f</td>";
            $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            // -----------------------------------------------------------
            // $strBody .= "<td class='text-right bg-success' data-order='$avg_harian_qty'>$avg_harian_qty_f</td>";
            // $strBody .= "<td class='text-right bg-success' data-order='$avg_harian'>$avg_harian_f</td>";
            // $strBody .= "<td class='text-right bg-info' data-order='$avg_margin'>$avg_margin_l</td>";

            $strBody .= "</tr>";

            $sum_bawah_nilai += $sum_kanan;
            $sum_bawah_qty += $harian_qty;

            $sum_bawah_qty_avg += $avg_harian_qty;
            $sum_bawah_avg += $avg_harian;
            $sum_bawah_margin_avg += $avg_margin;
        }
        $sum_bawah_qty_f = number_format($sum_bawah_qty);
        $sum_bawah_qty_avg_f = number_format($sum_bawah_qty_avg);
        $sum_bawah_nilai_f = number_format($sum_bawah_nilai);
        $sum_bawah_nilai_avg_f = number_format($sum_bawah_avg);
        $sum_bawah_margin_avg_f = number_format($sum_bawah_margin_avg);
        // cekBiru($total_bawah);
        // cekBiru("qty bawah: $sum_bawah_qty");
        // cekBiru("$jml_autoTr");
        // cekBiru($sum_bawah_harian);
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
        foreach ($masterHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        for ($i = 1; $i <= $tgl_max; $i++) {

            $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $dtLabel = "$thn_max-$bln_key";
            // $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;

            $strFoot .= "<th class='text-right'>0</th>";
            $strFoot .= "<th class='text-right'>0</th>";
        }
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";

        $strFoot .= "</tr>";
        // matiHere();
        //         cekHere(__LINE__);
        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
            .dataTables_info {
            margin-top: 0;
            padding-top: 4px !important;
                margin-right: 10px;
                text-transform: uppercase;
                color: blue !important;
                font-size: 14px;
            }
            .bukan-btn{
                text-transform: uppercase !important;
                font-weight: bold !important;
                border: none !important;
                padding-right: 0px !important;
                background-image: -webkit-linear-gradient(top, white 0%, #fff 100%) !important;
            }
             .pilih-btn {
                color: crimson !important;
                border: crimson 1px solid !important;
                background-image: linear-gradient(to bottom, white 0%, #dd4b3966 100%) !important;
            }
            .subj-btn {
                color: mediumseagreen !important;
                border: mediumseagreen 1px solid !important;
                background-image: linear-gradient(to bottom, white 0%, #63cd1366 100%) !important;            
            }
        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red table-bordered' id='$tbl_id'>";
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
        $jml_informasi = count($margin_data);

        $persen_10 = 10;
        $jml_10 = round($jml_informasi * ($persen_10 / 100));
        $persen_10_f = $persen_10 . "%";

        $persen_pareto = 20;
        if ($jml_informasi > 20) {

            $jml_pareto = round($jml_informasi * ($persen_pareto / 100));
        }
        else {
            $jml_pareto = 20;
        }
        $persen_pareto_f = $persen_pareto . "%";

        $num_master = 2;
        $num_kolom = ($tgl_max * 2) + count($masterHeaders) + 2;
        // cekHere("jml informasi data:: $jml_informasi --- $jml_pareto || $num_kolom");
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);

        $js_str = "";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $nilai_bawah = $sum_bawah_harian[$bln_key];
            $nilai_bawah_f = number_format($nilai_bawah);

            $js_str .= "$('<br><b class=\"text-orange\" title=\"total data\">$nilai_bawah_f</b>').appendTo('#tgl_$bln_key');\n";
        }
        // cekHere($subjek);
        $link_perper = base_url() . "laporan/Penjualan/cekpenjualanbulanan";
        $btn_cabang = isset($subjek) && $subjek == "cabang" ? "pilih-btn" : "subj-btn";
        $btn_seller = isset($subjek) && $subjek == "salesman" ? "pilih-btn" : "subj-btn";
        $btn_customer = isset($subjek) && $subjek == "customer" ? "pilih-btn" : "subj-btn";
        $btn_produk = isset($subjek) && $subjek == "produk" ? "pilih-btn" : "subj-btn";
        $link_perperkat = base_url() . "laporan/Penjualan/cekpenjualanbulankategori";
        $btn_kategori = isset($subjek) && $subjek == "kategori" ? "pilih-btn" : "subj-btn";
        $btn_tipe = isset($subjek) && $subjek == "tipe" ? "pilih-btn" : "subj-btn";
        // var col = parseFloat( removeCommas(searchData[$num_kolom]) ) || 0; // using the data from the 4th column

        $strTbl .= "<script>        
                $modalSize
                $js_str
                $('<div><b><r>$sum_bawah_qty_f</r></b></div>').appendTo('#unit');
                $('<div class=\"text-center\"><b><r>$sum_bawah_nilai_f</r></b></div>').appendTo('#nilai');
                // $('<div class=\"text-green text-center\"><b>$sum_bawah_qty_avg_f</b></div>').appendTo('#unit_avg');
                // $('<div class=\"text-green text-center\"><b>$sum_bawah_nilai_avg_f</b></div>').appendTo('#nilai_avg');
                //$('<div class=\"text-green text-center\"><b>$sum_bawah_margin_avg_f</b></div>').appendTo('#margin_avg');
                
                $('.box-body').addClass('loading_2');
                // $('<div class=\"loading_2\">Loading</div>').appendTo('.box-body');
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        // $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    // $.fn.dataTable.ext.search.push(
                    //             function( settings, searchData, index, rowData, counter ) {
                    //                 var col = parseFloat( removeCommas(searchData[$num_kolom]) ) || 0; // using the data from the 4th column
                    //                 // console.log(col);
                    //                 if(localStorage.showzero*1){
                    //                     return true;
                    //                 }
                    //                 else{
                    //                     if ( col*1 > 0 ){
                    //                         return true;
                    //                     }
                    //                     return false;
                    //                 }
                    //             }
                    //         );
                            
                    var text_button = 'Tampilkan/sembunyikan Penjualan nol';
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
                                        
                                        // $('div.loading_2').remove();
                                        $(\".box-body\").removeClass(\"loading_2\");
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                     dom: '<\'top\'ilBfprt<\'clear\'>>rt<\'bottom\'ip<\'clear\'>>',
                                    // dom: 'lBfrtip',
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
                                    fixedHeader: true,
                                    stateSave: true,

                                     order: [[$num_kolom, 'desc']],
                                    lengthMenu: [ [10, 15, 20, 50, $jml_10, $jml_pareto, -1], [10, 15, 20, 50, '$persen_10_f','$persen_pareto_f', 'All'] ],
                                    pageLength: $jml_pareto,
                                    buttons: [
                                            'copy',
                                            {extend:'csv', footer: true},
                                            'excel',
                                            'pdf',
                                            'print',
                                            { extend: 'excelHtml5', footer: true },
                                            // {
                                            //
                                            //     action: function ( e, dt, node, config ) {
                                            //         // console.log('my button click');
                                            //
                                            //         //$('tr.hidden').removeClass('hidden');
                                            //         if(localStorage.showzero*1){
                                            //             localStorage.showzero = 0
                                            //             datareview$tbl_id.draw();
                                            //             // var text_button = 'tampilkan data';
                                            //         }
                                            //         else{
                                            //             localStorage.showzero = 1
                                            //             datareview$tbl_id.draw();
                                            //             // var text_button = 'sembunyikan data';
                                            //         }
                                            //         // console.log('localStorage.showzero: ' , localStorage.showzero);
                                            //     },
                                            //    
                                            //     text: text_button,
                                            // },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                              
                                                },
                                                 className: 'bukan-btn',
                                                text: 'Pilih subjek >',
                                            },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                                    $('#sum_satu').load('$link_perper/cabang/$aktivitas');
                                                },
                                                className: '$btn_cabang',
                                                text: 'Cabang',
                                            },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                                    $('#sum_satu').load('$link_perper/seller/$aktivitas');
                                                },
                                                className: '$btn_seller',
                                                text: 'Salesman',
                                            },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                                    $('#sum_satu').load('$link_perper/customer/$aktivitas');
                                                },
                                                className: '$btn_customer',
                                                text: 'Customer',
                                            },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                                    $('#sum_satu').load('$link_perper/produk/$aktivitas');
                                                },
                                                className: '$btn_produk',
                                                text: 'Produk',
                                            },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                                    $('#sum_satu').load('$link_perper/kategori/$aktivitas');
                                                },
                                                className: '$btn_kategori',
                                                text: 'Kategori Produk',
                                            },
                                            {
                                                action: function ( e, dt, node, config ) {                                             
                                                    $('#sum_satu').load('$link_perper/tipe/$aktivitas');
                                                },
                                                className: '$btn_tipe',
                                                text: 'Tipe Penjualan',
                                            },
                                    ],

                                    infoCallback: function( settings, start, end, max, total, pre ) {
                                        var api = this.api();
                                        var pageInfo = api.page.info();
                                     
                                        return 'halaman <b>'+ (pageInfo.page+1) +'</b> dari <b>'+ pageInfo.pages + '</b>';
                                    },
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
                                        var jml_kolom = (arrayFooter.length) - 1;
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
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);

                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                                
                                                if((id_n_index > $num_master) && (id_n_index <= jml_kolom)){                                                
                                                    if ($('#ph_'+id_n_index).length === 0) {
                                                    
                                                        $( api.column(id_n_index).header() ).append(
                                                            \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                                        );
                                                    }                                                                                                                                          
                                                    
                                                    $(\"#ph_\"+id_n_index).html(
                                                        \"<b title='total halaman'>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                                    );
                                                }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
 
                     });
                    
                    top.$('#hide').click(function() {
                         $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                             // console.log(datareview$tbl_id.row(dataIndex));
                                return $( datareview$tbl_id.row(dataIndex).node(30) ) == 0;
                            });
                        datareview$tbl_id.draw();
                    });    
                    top.$('#reset').click(function() {
                        $.fn.dataTable.ext.search.pop();
                        datareview$tbl_id.draw();
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
            $btn_colaps .= "<button class='btn btn-sm btn-box-tool text-red' title='menampilkan detile laporan summary' data-togle='tooltip' onclick=\"window.open('$link_detile');\"><i class='fa fa-fighter-jet'></i> ke detil laporan</button>";
        }
        $btn_colaps .= "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";

        $p->setLayoutBoxHeadingCss("text-capitalize");
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
    case "produk_bulanan_perkategori":
        $bulan_namas = namaBulan();
        // $tgl_max = formatTanggal($date2, 'd');
        $thn_max = formatTanggal($date2, 'Y');
        $tgl_max = $bln_max = formatTanggal($date2, 'm') - 0;

        if ($thn_max == "2023") {
            $tgl_max = $bln_max = formatTanggal($date2, 'm') - 0;
            // $tgl_max = $bln_max = formatTanggal($date2, 'm');
        }
        // cekKuning("$date2 | $tgl_max");
        $colspan_jml = $tgl_max * 2;
        $header_str = $bln_max == dtimeNow('m') ? "YTD" : "Tahunan";
        $margin_avg_f = number_format($margin_avg, "2") . "%";

        // $total_bawah_penjualan = $total_bawah['penjualan'];
        // $total_bawah_penjualan_f = number_format($total_bawah_penjualan);
        // $total_bawah_penjualan_str = "<div class='text-color-blue'>$total_bawah_penjualan_f</div><div id='nilai_halaman'></div>";


        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<th rowspan='3'>no</th>";
        foreach ($masterHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;

            $strHead .= "<th rowspan='3'>$hLabel</th>";
        }
        $strHead .= "<td colspan='$colspan_jml'><b class='text-renggang-10'>Nilai $title_now Bulanan</b></td>";
        $strHead .= "<td colspan='2'><b class=''>$title_now $judul_lap</b></td>";
        // $strHead .= "<td rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>total unit</b><span id='pg_unit'></span></td>";
        // $strHead .= "<td colspan='3'><b>Nilai Rata-rata Bulanan</b> <r class='font-size-1-2 pull-right'>$margin_avg_f</r></td>";
        // $strHead .= "<td colspan='3'><b>Nilai Rata-rata Bulanan</b></td>";

        // cekHere($tgl_max);
        $strHead .= "</tr>";

        $strHead .= "<tr>";
        for ($i = 1; $i <= $tgl_max; $i++) {

            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $label_now = $tgl_max == $i ? "(MTD)" : "";
            $bln_nama = $bulan_namas[$tgl_key] . " $label_now";
            $dtLabel = "$thn_max-$bln_max-$tgl_key";

            $str_kanan = $i == 1 ? "<div style='float: left;text-align: left;'><br><div class='text-orange'>Total Produk</div><div class='text-primary'>Total 1 page</div></div>" : "";

            // $strHead .= "<th id='tgl_$tgl_key' class='text-center'>$bln_nama $str_kanan<span id='pg_$i' title='total halaman ini'></span></th>";
            // $strHead .= "<th id='tgl_$tgl_key' colspan='2' class='text-center'>$bln_nama <span id='pg_$i' title='total halaman ini'></span></th>";
            $strHead .= "<th colspan='2' class='text-center' title='$tgl_key'>$bln_nama</th>";
        }
        // --------------------
        $strHead .= "<th rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>Unit</b><span id='pg_unit'></span></th>";
        $strHead .= "<th rowspan='2' id='nilai'>Nilai<span id='pg_nilai'></span></th>";

        // $strHead .= "<th rowspan='2' id='unit_avg'>Unit<span id='pg_unit_avg'></span></th>";
        // $strHead .= "<th rowspan='2' id='nilai_avg'>Nilai<span id='pg_nilai_avg'></span></th>";
        // $strHead .= "<th rowspan='2' id='margin_avg'>Margin<span id='pg_margin_avg'></span></th>";
        $strHead .= "</tr>";

        /*sub header*/
        $strHead .= "<tr>";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $strHead .= "<th>unit</th>";
            $strHead .= "<th id='tgl_$tgl_key'>nilai</th>";
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

        // arrPrintWebs($master_data);
        // arrPrintWebs(array_slice($master_data,0,2));
        // arrPrintKuning($child_data);
        // matiHere(__LINE__);
        $sum_bawah_nilai = 0;
        $sum_bawah_qty = 0;
        $sum_bawah_qty_avg = 0;
        $sum_bawah_avg = 0;
        $sum_bawah_margin_avg = 0;
        foreach ($master_data as $produk_id => $master_datum) {
            $nama = $master_datum["nama"];
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($masterHeaders as $kolom => $attrs) {
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

            $pembagi = 1000000;
            $sum_kanan = 0;
            for ($i = 1; $i <= $tgl_max; $i++) {

                $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
                $dtLabel = "$thn_max-$bln_key";
                // cekHere("$dtLabel");
                $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['netto'] * 1) / $pembagi : 0) : 0;
                $chNilai_f = number_format($chNilai, 0);

                $chQty = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['netto_qty'] * 1) : 0) : 0;
                $chQty_f = number_format($chQty, 0);


                $strBody .= "<td class='text-right' data-order='$chQty'>$chQty_f</td>";
                $strBody .= "<td class='text-right' data-order='$chNilai'>$chNilai_f</td>";

                $sum_kanan += $chNilai;

                if (!isset($sum_bawah_harian[$bln_key])) {
                    $sum_bawah_harian[$bln_key] = 0;
                }
                $sum_bawah_harian[$bln_key] += $chNilai;
            }
            /*----summary kanan----------*/
            $sum_kanan_f = number_format($sum_kanan, 0);
            // $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            $avg_margin = isset($margin_data[$produk_id]) ? $margin_data[$produk_id] : 0;

            $harian_qty = isset($qty_data[$produk_id]) ? $qty_data[$produk_id] : 0;
            /* --------------------------------------------------------------------------------
             * exeption tahun awal mengunkan aplikasi
             * --------------------------------------------------------------------------------*/
            if ($thn_max == "2023") {
                $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / ($tgl_max - 3)) : 0;
                $avg_harian = $sum_kanan / ($tgl_max - 3);
            }
            else {
                $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / $tgl_max) : 0;
                $avg_harian = $sum_kanan / $tgl_max;
            }

            $avg_harian_f = number_format($avg_harian, 2);
            $avg_margin_f = number_format($avg_margin, 2);
            $avg_harian_qty_f = number_format($avg_harian_qty, 2);
            $harian_qty_f = number_format($harian_qty, 0);

            $avg_margin_l = $avg_margin_f;
            if ($avg_margin <= 1) {
                $link_detil = base_url() . "laporan/Penjualan/viewprodukdetile?id=$produk_id";
                $linkModal = modalDialogBtn("$nama", $link_detil);
                $avg_margin_l = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$nama'>$avg_margin_f</a>";
            }

            // $avg_harian_qty = 0;
            $strBody .= "<td class='text-right bg-warning' data-order='$harian_qty'>$harian_qty_f</td>";
            $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            // -----------------------------------------------------------
            // $strBody .= "<td class='text-right bg-success' data-order='$avg_harian_qty'>$avg_harian_qty_f</td>";
            // $strBody .= "<td class='text-right bg-success' data-order='$avg_harian'>$avg_harian_f</td>";
            // $strBody .= "<td class='text-right bg-info' data-order='$avg_margin'>$avg_margin_l</td>";

            $strBody .= "</tr>";

            $sum_bawah_nilai += $sum_kanan;
            $sum_bawah_qty += $harian_qty;

            $sum_bawah_qty_avg += $avg_harian_qty;
            $sum_bawah_avg += $avg_harian;
            $sum_bawah_margin_avg += $avg_margin;
        }
        $sum_bawah_qty_f = number_format($sum_bawah_qty);
        $sum_bawah_qty_avg_f = number_format($sum_bawah_qty_avg);
        $sum_bawah_nilai_f = number_format($sum_bawah_nilai);
        $sum_bawah_nilai_avg_f = number_format($sum_bawah_avg);
        $sum_bawah_margin_avg_f = number_format($sum_bawah_margin_avg);
        // cekBiru($total_bawah);
        // cekBiru("qty bawah: $sum_bawah_qty");
        // cekBiru("$jml_autoTr");
        // cekBiru($sum_bawah_harian);
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
        foreach ($masterHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        for ($i = 1; $i <= $tgl_max; $i++) {

            $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $dtLabel = "$thn_max-$bln_key";
            // $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;

            $strFoot .= "<th class='text-right'>0</th>";
            $strFoot .= "<th class='text-right'>0</th>";
        }
        $strFoot .= "<th class='text-right'>0</th>";
        $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";

        $strFoot .= "</tr>";
        // matiHere();
        //         cekHere(__LINE__);
        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
            .dataTables_info {
            margin-top: 0;
            padding-top: 4px !important;
                margin-right: 10px;
                text-transform: uppercase;
                color: blue !important;
                font-size: 14px;
            }
            .bukan-btn{
                text-transform: uppercase !important;
                font-weight: bold !important;
                border: none !important;
                padding-right: 0px !important;
                background-image: -webkit-linear-gradient(top, white 0%, #fff 100%) !important;
            }
             .pilih-btn {
                color: crimson !important;
                border: crimson 1px solid !important;
                background-image: linear-gradient(to bottom, white 0%, #dd4b3966 100%) !important;
            }
        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red table-bordered' id='$tbl_id'>";
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
        $jml_informasi = count($margin_data);

        $persen_10 = 10;
        $jml_10 = round($jml_informasi * ($persen_10 / 100));
        $persen_10_f = $persen_10 . "%";

        $persen_pareto = 20;
        if ($jml_informasi > 20) {

            $jml_pareto = round($jml_informasi * ($persen_pareto / 100));
        }
        else {
            $jml_pareto = 20;
        }
        $persen_pareto_f = $persen_pareto . "%";

        $num_master = 2;
        $num_kolom = ($tgl_max * 2) + count($masterHeaders) + 2;
        // cekHere("jml informasi data:: $jml_informasi --- $jml_pareto || $num_kolom");
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);

        $js_str = "";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $bln_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            $nilai_bawah = $sum_bawah_harian[$bln_key];
            $nilai_bawah_f = number_format($nilai_bawah);

            $js_str .= "$('<br><b class=\"text-orange\" title=\"total data\">$nilai_bawah_f</b>').appendTo('#tgl_$bln_key');\n";
        }
        // cekHere($subjek);
        $link_perper = base_url() . "laporan/Penjualan/cekpenjualanbulanan";
        $btn_cabang = isset($subjek) && $subjek == "cabang" ? "pilih-btn" : "";
        $btn_seller = isset($subjek) && $subjek == "salesman" ? "pilih-btn" : "";
        $btn_customer = isset($subjek) && $subjek == "customer" ? "pilih-btn" : "";
        $btn_produk = isset($subjek) && $subjek == "produk" ? "pilih-btn" : "";
        $strTbl .= "<script>        
                $modalSize
                $js_str
                $('<div><b><r>$sum_bawah_qty_f</r></b></div>').appendTo('#unit');
                $('<div class=\"text-center\"><b><r>$sum_bawah_nilai_f</r></b></div>').appendTo('#nilai');
                // $('<div class=\"text-green text-center\"><b>$sum_bawah_qty_avg_f</b></div>').appendTo('#unit_avg');
                // $('<div class=\"text-green text-center\"><b>$sum_bawah_nilai_avg_f</b></div>').appendTo('#nilai_avg');
                //$('<div class=\"text-green text-center\"><b>$sum_bawah_margin_avg_f</b></div>').appendTo('#margin_avg');
                
                $('.box-body').addClass('loading_2');
                // $('<div class=\"loading_2\">Loading</div>').appendTo('.box-body');
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        // $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    $.fn.dataTable.ext.search.push(
                                function( settings, searchData, index, rowData, counter ) {
                                    var col = parseFloat( removeCommas(searchData[$num_kolom]) ) || 0; // using the data from the 4th column
                                    if(localStorage.showzero*1){
                                        return true;
                                    }
                                    else{
                                        if ( col*1 > 0 ){
                                            return true;
                                        }
                                        return false;
                                    }
                                }
                            );
                            
                    var text_button = 'Tampilkan/sembunyikan Penjualan nol';
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
                                        
                                        // $('div.loading_2').remove();
                                        $(\".box-body\").removeClass(\"loading_2\");
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                     dom: '<\'top\'ilBfprt<\'clear\'>>rt<\'bottom\'ip<\'clear\'>>',
                                    // dom: 'lBfrtip',
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
                                    fixedHeader: true,
                                    stateSave: true,

                                     order: [[$num_kolom, 'desc']],
                                    lengthMenu: [ [10, 15, 20, 50, $jml_10, $jml_pareto, -1], [10, 15, 20, 50, '$persen_10_f','$persen_pareto_f', 'All'] ],
                                    pageLength: $jml_pareto,
                                    buttons: [
                                            'copy',
                                            {extend:'csv', footer: true},
                                            'excel',
                                            'pdf',
                                            'print',
                                            { extend: 'excelHtml5', footer: true },
                                            {

                                                action: function ( e, dt, node, config ) {
                                                    console.log('my button click');
        
                                                    //$('tr.hidden').removeClass('hidden');
                                                    if(localStorage.showzero*1){
                                                        localStorage.showzero = 0
                                                        datareview$tbl_id.draw();
                                                        var text_button = 'tampilkan data';
                                                    }
                                                    else{
                                                        localStorage.showzero = 1
                                                        datareview$tbl_id.draw();
                                                        var text_button = 'sembunyikan data';
                                                    }
                                                    console.log('localStorage.showzero: ' , localStorage.showzero);
                                                },
                                                
                                                text: text_button,
                                            },
                                            // {
                                            //     action: function ( e, dt, node, config ) {                                             
                                            //  
                                            //     },
                                            //      className: 'bukan-btn',
                                            //     text: 'Pilih subjek >',
                                            // },
                                            // {
                                            //     action: function ( e, dt, node, config ) {                                             
                                            //         $('#sum_satu').load('$link_perper/cabang/$aktivitas');
                                            //     },
                                            //     className: '$btn_cabang',
                                            //     text: 'Cabang',
                                            // },
                                            // {
                                            //     action: function ( e, dt, node, config ) {                                             
                                            //         $('#sum_satu').load('$link_perper/seller/$aktivitas');
                                            //     },
                                            //     className: '$btn_seller',
                                            //     text: 'Salesman',
                                            // },
                                            // {
                                            //     action: function ( e, dt, node, config ) {                                             
                                            //         $('#sum_satu').load('$link_perper/customer/$aktivitas');
                                            //     },
                                            //     className: '$btn_customer',
                                            //     text: 'Customer',
                                            // },
                                            // {
                                            //     action: function ( e, dt, node, config ) {                                             
                                            //         $('#sum_satu').load('$link_perper/produk/$aktivitas');
                                            //     },
                                            //     className: '$btn_produk',
                                            //     text: 'Produk',
                                            // },
                                    ],

                                    infoCallback: function( settings, start, end, max, total, pre ) {
                                        var api = this.api();
                                        var pageInfo = api.page.info();
                                     
                                        return 'halaman <b>'+ (pageInfo.page+1) +'</b> dari <b>'+ pageInfo.pages + '</b>';
                                    },
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
                                        var jml_kolom = (arrayFooter.length) - 1;
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
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);

                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                                
                                                if((id_n_index > $num_master) && (id_n_index <= jml_kolom)){                                                
                                                    if ($('#ph_'+id_n_index).length === 0) {
                                                    
                                                        $( api.column(id_n_index).header() ).append(
                                                            \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                                        );
                                                    }                                                                                                                                          
                                                    
                                                    $(\"#ph_\"+id_n_index).html(
                                                        \"<b title='total halaman'>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                                    );
                                                }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
 
                     });
                    
                    top.$('#hide').click(function() {
                         $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                             console.log(datareview$tbl_id.row(dataIndex));
                                return $( datareview$tbl_id.row(dataIndex).node(30) ) == 0;
                            });
                        datareview$tbl_id.draw();
                    });    
                    top.$('#reset').click(function() {
                        $.fn.dataTable.ext.search.pop();
                        datareview$tbl_id.draw();
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
            $btn_colaps .= "<button class='btn btn-sm btn-box-tool text-red' title='menampilkan detile laporan summary' data-togle='tooltip' onclick=\"window.open('$link_detile');\"><i class='fa fa-fighter-jet'></i> ke detil laporan</button>";
        }
        $btn_colaps .= "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";

        $p->setLayoutBoxHeadingCss("text-capitalize");
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

    case "nota_harian":
        $tgl_max = formatTanggal($date2, 'd');
        $bln_max = formatTanggal($date2, 'm');
        $thn_max = formatTanggal($date2, 'Y');
        // cekKuning("$date2 | $tgl_max");
        $colspan_jml = $tgl_max;
        $header_str = $bln_max == dtimeNow('m') ? "MTD" : "Bulanan";
        // $margin_avg_f = number_format($margin_avg, "2") . "%";

        // $total_bawah_penjualan = $total_bawah['penjualan'];
        // $total_bawah_penjualan_f = number_format($total_bawah_penjualan);
        // $total_bawah_penjualan_str = "<div class='text-color-blue'>$total_bawah_penjualan_f</div><div id='nilai_halaman'></div>";


        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($masterHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;

            $strHead .= "<th rowspan='2'>$hLabel</th>";
        }
        // -----------------------------------
        // $strHead .= "<td colspan='$colspan_jml'><b class='text-renggang-10'>Nilai Penjualan Harian</b></td>";
        // $strHead .= "<td colspan='2'><b class=''>Penjualan Bulan</b></td>";
        // $strHead .= "<td colspan='2'><b class=''>Penjualan Bulan (Penuh Hari)</b></td>";
        // $strHead .= "<td rowspan='2' class='text-center' style='vertical-align: bottom !important;' id='unit'><b>total unit</b><span id='pg_unit'></span></td>";
        // $strHead .= "<td colspan='3'><b>Nilai Rata-rata Harian</b> <r class='font-size-1-2 pull-right'>$margin_avg_f</r></td>";


        $strHead .= "</tr>";

        // $strHead .= "<tr>";
        // for ($i = 1; $i <= $tgl_max; $i++) {
        //
        //     $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
        //     $dtLabel = "$thn_max-$bln_max-$tgl_key";
        //
        //     $strHead .= "<th id='tgl_$tgl_key' class='text-center'>$tgl_key<span id='pg_$i'></span></th>";
        // }
        // $strHead .= "<th class='text-center' style='vertical-align: bottom !important;' id='unit'><b>unit</b><span id='pg_unit'></span></th>";
        // $strHead .= "<th id='nilai'>$header_str<span id='pg_nilai'></span></th>";
        // // ----------------------------
        // $strHead .= "<th id='unit_avg'>Unit<span id='pg_unit_avg'></span></th>";
        // $strHead .= "<th id='nilai_avg'>Nilai<span id='pg_nilai_avg'></span></th>";
        // $strHead .= "<th id='margin_avg'>Margin<span id='pg_margin_avg'></span></thid>";
        // $strHead .= "</tr>";


        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";

        // arrPrintWebs($master_data);
        // arrPrintWebs(array_slice($master_data,0,2));
        // arrPrintWebs($child_data);
        // arrPrintWebs($strGet);
        // matiHere(__LINE__);

        foreach ($master_data as $produk_id => $master_datum) {
            $nama = isset($master_datum["nama"]) ? $master_datum["nama"] : "noname";
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($masterHeaders as $kolom => $attrs) {
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
                    $key_get_tambahan = "";
                    if ($reqKey != false) {
                        $key_get_tambahan = "&$reqKey=$reqValue&ky=$reqKey";
                    }
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . $key_get_tambahan : "";
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

            // $sum_kanan = 0;
            // for ($i = 1; $i <= $tgl_max; $i++) {
            //
            //     $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            //     $dtLabel = "$thn_max-$bln_max-$tgl_key";
            //     $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;
            //     $chNilai_f = number_format($chNilai, 0);
            //
            //     $strBody .= "<td class='text-right' data-order='$chNilai'>$chNilai_f</td>";
            //
            //     $sum_kanan += $chNilai;
            //
            //     if(!isset($sum_bawah_harian[$tgl_key])){
            //         $sum_bawah_harian[$tgl_key] = 0;
            //     }
            //     $sum_bawah_harian[$tgl_key] += $chNilai;
            // }
            //
            // $sum_kanan_f = number_format($sum_kanan, 0);
            // // $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            // $avg_margin = isset($margin_data[$produk_id]) ? $margin_data[$produk_id] : 0;
            //
            // $harian_qty = isset($qty_data[$produk_id]) ? $qty_data[$produk_id] : 0;
            // $avg_harian_qty = isset($qty_data[$produk_id]) ? ($harian_qty / $tgl_max) : 0;
            // $avg_harian = $sum_kanan / $tgl_max;
            // $avg_harian_f = number_format($avg_harian, 2);
            // $avg_margin_f = number_format($avg_margin, 2);
            // $avg_harian_qty_f = number_format($avg_harian_qty, 2);
            // $harian_qty_f = number_format($harian_qty, 2);
            //
            // $avg_margin_l = $avg_margin_f;
            // if($avg_margin <= 1){
            //     $link_detil = base_url() . "laporan/Penjualan/viewprodukdetile?id=$produk_id";
            //     $linkModal = modalDialogBtn("$nama", $link_detil);
            //     $avg_margin_l = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$nama'>$avg_margin_f</a>";
            // }
            //
            // // $avg_harian_qty = 0;
            // $strBody .= "<td class='text-right bg-warning' data-order='$harian_qty'>$harian_qty_f</td>";
            // $strBody .= "<td class='text-right bg-warning' data-order='$sum_kanan'>$sum_kanan_f</td>";
            // // -----------------------------------------------
            // $strBody .= "<td class='text-right bg-success' data-order='$avg_harian_qty'>$avg_harian_qty_f</td>";
            // $strBody .= "<td class='text-right bg-success' data-order='$avg_harian'>$avg_harian_f</td>";
            // $strBody .= "<td class='text-right bg-info' data-order='$avg_margin'>$avg_margin_l</td>";

            $strBody .= "</tr>";

            // $sum_bawah_nilai += $sum_kanan;
            // $sum_bawah_qty += $harian_qty;
            // $sum_bawah_qty_avg += $avg_harian_qty;
            // $sum_bawah_avg += $avg_harian;
            // $sum_bawah_margin_avg += $avg_margin;
        }
        // $sum_bawah_qty_f = number_format($sum_bawah_qty);
        // $sum_bawah_qty_avg_f = number_format($sum_bawah_qty_avg);
        // $sum_bawah_nilai_f = number_format($sum_bawah_nilai);
        // $sum_bawah_nilai_avg_f = number_format($sum_bawah_avg);
        // $sum_bawah_margin_avg_f = number_format($sum_bawah_margin_avg);
        // cekBiru($total_bawah);
        // cekBiru("qty bawah: $sum_bawah_qty");
        // cekBiru("$jml_autoTr");
        // cekBiru($sum_bawah_harian);
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
        foreach ($masterHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        // for ($i = 1; $i <= $tgl_max; $i++) {
        //
        //     $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
        //     $dtLabel = "$thn_max-$bln_max-$tgl_key";
        //     // $chNilai = isset($child_data[$produk_id]) ? (isset($child_data[$produk_id][$dtLabel]) ? ($child_data[$produk_id][$dtLabel]['sum_kredit'] * 1) : 0) : 0;
        //
        //     $strFoot .= "<th class='text-right'>0</th>";
        // }
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";
        // $strFoot .= "<th class='text-right'>0</th>";

        $strFoot .= "</tr>";
        // matiHere();
        //         cekHere(__LINE__);
        $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
            .dataTables_info {
            margin-top: 0;
            padding-top: 4px !important;
                margin-right: 10px;
                text-transform: uppercase;
                color: blue !important;
                font-size: 14px;
            }
        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-bordered table-striped table-hover-color-red' border='1' id='$tbl_id'>";
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
        $jml_informasi = count($master_data);

        $persen_10 = 10;
        $jml_10 = round($jml_informasi * ($persen_10 / 100));
        $persen_10_f = $persen_10 . "%";

        $persen_pareto = 20;
        $jml_pareto = round($jml_informasi * ($persen_pareto / 100));
        $persen_pareto_f = $persen_pareto . "%";

        $num_kolom = count($masterHeaders) + 1;
        // cekHere("jml informasi data:: $jml_informasi --- $jml_pareto || $num_kolom");
        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);

        $js_str = "";
        for ($i = 1; $i <= $tgl_max; $i++) {
            $tgl_key = str_pad($i, 2, 0, STR_PAD_LEFT);
            // $nilai_bawah = $sum_bawah_harian[$tgl_key];
            // $nilai_bawah_f = number_format($nilai_bawah);

            // $js_str .= "$('<br><b class=\"text-orange\">$nilai_bawah_f</b>').appendTo('#tgl_$tgl_key');\n";
        }
        // $js_str
        // $('<div><b><r>$sum_bawah_qty_f</r></b></div>').appendTo('#unit');
        // $('<div class=\"text-green\"><b>$sum_bawah_qty_avg_f</b></div>').appendTo('#unit_avg');
        // $('<br><b><r>$sum_bawah_nilai_f</r></b>').appendTo('#nilai');
        // $('<div class=\"text-green\"><b>$sum_bawah_nilai_avg_f</b></div>').appendTo('#nilai_avg');
        // //$('<div class=\"text-green\"><b>$sum_bawah_margin_avg_f</b></div>').appendTo('#margin_avg');
        $strTbl .= "<script>        
                $modalSize

                
                // $('.box-body').addClass('loading_2');
                // $('<div class=\"loading_2\">Loading</div>').appendTo('.box-body');
        </script>";

        $strTbl .= "<script>
                $(document).ready( delay_v2( function(){
                   
                    $.fn.dataTable.ext.search.push(
                                function( settings, searchData, index, rowData, counter ) {
                                    var col = parseFloat( removeCommas(searchData[$num_kolom]) ) || 0; // using the data from the 4th column
                                    if(localStorage.showzero*1){
                                        return true;
                                    }
                                    else{
                                        if ( col*1 > 0 ){
                                            return true;
                                        }
                                        return false;
                                    }
                                }
                            );
                            
                    var text_button = 'Tampilkan/sembunyikan Penjualan nol';
        
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
                                        
                                        // $('div.loading_2').remove();
                                        $(\".box-body\").removeClass(\"loading_2\");
                                                                                                                                       
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                     dom: '<\'top\'ilBfprt<\'clear\'>>rt<\'bottom\'ip<\'clear\'>>',
                                    // dom: 'lBfrtip',
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
                                    fixedHeader: true,
                                    stateSave: true,

                                     order: [[$num_kolom, 'desc']],
                                    lengthMenu: [ [10, 15, 20, 50, $jml_10, $jml_pareto, -1], [10, 15, 20, 50, '$persen_10_f','$persen_pareto_f', 'All'] ],
                                    pageLength: $jml_pareto,
                                    buttons: [
                                            'copy',
                                            {extend:'csv', footer: true},
                                            'excel',
                                            'pdf',
                                            'print',
                                            { extend: 'excelHtml5', footer: true },                                            
                                    ],

                                    infoCallback: function( settings, start, end, max, total, pre ) {
                                        var api = this.api();
                                        var pageInfo = api.page.info();
                                     
                                        return 'menampilkan halaman <b>'+ (pageInfo.page+1) +'</b> dari <b>'+ pageInfo.pages + '</b>';
                                    },
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
                                        var jml_kolom = (arrayFooter.length) - 1;
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
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);

                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                                
                                                if((id_n_index > 4) && (id_n_index < jml_kolom)){                                                
                                                    if ($('#ph_'+id_n_index).length === 0) {
                                                    
                                                        $( api.column(id_n_index).header() ).append(
                                                            \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                                        );
                                                    }                                                                                                                                          
                                                    
                                                    $(\"#ph_\"+id_n_index).html(
                                                        \"<b>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                                    );
                                                }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
 
                     });

                    top.$('#hide').click(function() {
                         $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                             console.log(datareview$tbl_id.row(dataIndex));
                                return $( datareview$tbl_id.row(dataIndex).node(30) ) == 0;
                            });
                        datareview$tbl_id.draw();
                    });    
                    top.$('#reset').click(function() {
                        $.fn.dataTable.ext.search.pop();
                        datareview$tbl_id.draw();
                    });
        
                     // -----------------------------------------------------                     
                     $auto_tr                              
                    //  ----------------------------------------------------------
                                    }, 500));

                    </script>";
        $strTbl .= "<script>
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

        if (isset($summary_on_top)) {
            $penjualan_total = isset($totals["transaksi_nilai"]) ? $totals["transaksi_nilai"] : 0;
            $penjualan_total = number_format($penjualan_total);

            $sum_bawah_data = $summary_on_top;
            $sum_atas = "";
            $strTbl .= "<style>
                .info-box-content {
                    margin-left: 60px;
                }
                .info-box {
                    min-height: 60px;
                    background-color: #00ef4c12;
                }
                .info-box-icon {
                    height: 60px;
                    width: 60px;
                    line-height: 60px;
                }
            </style>";
            $sum_atas .= "<div class=\'row\'>";

            foreach ($sum_bawah_data as $kei => $sum_bawah_datum) {

                $label = isset($sum_bawah_datum['label']) ? $sum_bawah_datum['label'] : $kei;
                $icon_fa = isset($sum_bawah_datum['icon_fa']) ? "fa fa-" . $sum_bawah_datum['icon_fa'] : "";
                $nilai_0 = isset($sum_bawah_datum['nilai']) ? str_replace(".", "", $sum_bawah_datum['nilai']) : 0;
                $nilai = $$nilai_0;
                $nilai_f = isset($sum_bawah_datum['nilai_f']) ? str_replace("{nilai}", $nilai, $sum_bawah_datum['nilai_f']) : "";


                $sum_atas .= "<div class=\'col-md-4\'>";
                $sum_atas .= "<div class=\'info-box\'>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-gear-outline \'></i></span>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-list-outline \'></i></span>";
                $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'$icon_fa \'></i></span>";

                $sum_atas .= "<div class=\'info-box-content\'>";
                $sum_atas .= "<span class=\'info-box-text\'>$label</span>";
                $sum_atas .= "<span class=\'info-box-number\'>$nilai_f</span>";
                $sum_atas .= "</div>";

                $sum_atas .= "</div>";

                $sum_atas .= "</div>";
            }
            // $sum_atas .= $p->layout_box_info($sum_bawah_data);
            $sum_atas .= "</div>";

            $strTbl .= "<script>
                $('#summary_atas').append('$sum_atas');
            </script>";
        }
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
    case "langsung_simple":
        // cekHere(__LINE__);
        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<td>no</td>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th title='$kolom'>$hLabel</th>";
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
        // cekHijau(count($master_data));
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

                if (isset($attrs['data_order'])) {
                    $nilai_order = isset($master_datum[$attrs['data_order']]) ? $master_datum[$attrs['data_order']] : "";
                    $data_order = "daya-order='$nilai_order'";
                }
                else {

                    $data_order = "";
                }


                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != null ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $str_logic = "";
                if (isset($attrs['logics'])) {
                    $ltexs = isset($attrs['logics']['text']) ? $attrs['logics']['text'] : "";
                    $lnilai = $attrs['logics']['nilai']; // tidak berguna ni, coba cari cari yg lain

                    $str_logic = $nilai < 0 ? "<br><div class='meta'>$ltexs</div>" : '';
                }

                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $lintarget = isset($attrs['links']['target']) ? $attrs['links']['target'] : "";
                    $linking = isset($attrs['links']['target']) ? $lintarget . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                    $link_type = isset($attrs['links']['link_type']) ? $attrs['links']['link_type'] : "modal";

                    switch ($link_type) {
                        case 'newwindow_custom':
                            $nama_e =blobEncode($master_datum['extern_nama']);
                            $date1 = "2025-01-01";
                            $date2 = dtimeNow('Y-m-d');
                            $custom = base_url() . $lintarget . "/$reqValue?o=".CB_ID_PUSAT. "&date1=$date1&date2=$date2&main_ext2_id=$reqValue&blob_ext=$nama_e";
                            $link_action = "window.open('$custom', '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');";
                            break;
                        case 'newwindow':
                            $link_action = "window.open('$linkDetile', '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');";
                            break;
                        case 'newtab':
                            $link_action = "window.open('$linkDetile', '_blank');";
                            break;
                        case 'parent':
                            $link_action = "window.location.href = '$linkDetile';";
                            break;
                        case 'modal':
                        default:
                            $link_action = $linkModal; // as is
                            break;
                    }


                    $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$link_action\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                elseif (isset($attrs['collapsible'])) {
                    $layout_in = isset($attrs['collapsible']['layout']) ? $attrs['collapsible']['layout'] : "";
                    $layout_str = isset($attrs['collapsible']['layout']) ? "&layout=$layout_in" : "";
                    $reqKey = isset($attrs['collapsible']['key']) ? $attrs['collapsible']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "";
                    $key_get_tambahan = "";
                    if ($reqKey != false) {
                        $key_get_tambahan = "&$reqKey=$reqValue&ky=$reqKey";
                    }
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . $key_get_tambahan . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkDetile = "https://san.mayagrahakencana.com/" . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
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

                $strBody .= "<td $data_order title2='$pengenal_link' title3='$pengenal_masterid' $attr>$nilai_link $str_logic</td>";

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
            "fulldate"
        );
        $strFoot = "";
        $strFoot .= "<tr class='bg-danger font-size-1-2'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) && (!array($format_key, $kolom_tanpa_format)) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        // arrPrintHijau($summariLabels);
        // arrPrintKuning($summariNilais);
        // arrPrint($summariSubjectLabels);
        $sum_btn = "";
        $sum_atas_btn = "";
        $sum_atas = "";
        if (isset($summariLabels)) {
            $sum_atas_btn .= "Berdasarkan: ";
            $sum_atas .= "<div class=\'row\'>";
            foreach ($summariNilais as $kei_1 => $sumValues) {
                $kei_1_label = $summariSubjectLabels[$kei_1];

                $sum_btn .= "<button type='button' class='btn btn-info text-uppercase' title='$kei_1' onclick=\"$('#$kei_1').fadeToggle();\">$kei_1_label</button>";

                $sum_atas .= "<div id='$kei_1' class='panel panel-default' style='display: none; float: left;margin-right: 5px;margin-top: 3px;'>";
                $sum_atas .= "<table class='table table-borderer table-striped table-hover-color-red'>";
                // $sum_atas .= "<tr class='text-uppercase bg-grey-2'><th>$kei_1_label</th><th>akumulasi transaksi</th>  </tr>";
                // cekKuning("$kei_1");
                //  arrPrintPink($sumValues);
                /* -------------------
                 * header
                 * -------------------*/
                $sum_atas .= "<tr class='text-uppercase bg-grey-2'>";
                if (isset($summariLabels[$kei_1]['header'])) {
                    foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {
                        $header_label = isset($headerParams['label']) ? $headerParams['label'] : $headerKey;
                        $sum_atas .= "<th>$header_label</th>";
                    }
                }
                else {
                    $sum_atas .= "<th colspan='2'>$kei_1_label</th>";
                }
                $sum_atas .= "</tr>";

                /* -------------------
                 * body
                 * -------------------*/
                $sumValueBawah = 0;
                foreach ($sumValues as $kei_2 => $sumValue) {
                    $sum_atas .= "<tr class='text-uppercase'>";
                    if (is_array($sumValue)) {
                        // arrPrint($sumValue);
                        foreach ($sumValue as $itemkey => $itemSumm) {
                            // cekKuning("$itemkey");
                            // arrPrint($itemSumm);
                            // $sumValue_f = is_numeric($itemSumm) ? number_format($itemSumm) : $itemSumm;
                            // $sum_atas .= "<td>$sumValue_f</td>";


                        }

                        //     ------------------------------

                        foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {

                            // $header_label = isset($headerParams['label']) ? $headerParams['label'] : $headerKey;

                            $itemSumm = $sumValue[$headerKey];
                            $sumValue_f = is_numeric($itemSumm) ? number_format($itemSumm) : $itemSumm;
                            $sumAttr = isset($headerParams['attr']) ? $headerParams['attr'] : "";

                            $sum_atas .= "<td $sumAttr>$sumValue_f</td>";

                            /* ------ -------------
                                ngesumm unutk footer
                            -----------------------*/
                            if (isset($headerParams['summary']) && ($headerParams['summary'] == true)) {
                                if (!isset($sumValueBawahs[$kei_1][$headerKey])) {
                                    $sumValueBawahs[$kei_1][$headerKey] = 0;
                                }
                                $sumValueBawahs[$kei_1][$headerKey] += $sumValue[$headerKey];
                            }

                        }
                    }
                    else {

                        $kei_2_label = $summariLabels[$kei_1][$kei_2];
                        $sumValue_f = number_format($sumValue);
                        // $sum_atas .= "<tr class='text-uppercase'>";
                        $sum_atas .= "<td>$kei_2_label</td>";
                        $sum_atas .= "<td class='text-right'>$sumValue_f</td>";
                        // $sum_atas .= "</tr>";

                        $sumValueBawah += $sumValue;
                    }
                    $sum_atas .= "</tr>";
                }

                /* ----------------------------
                 * footer
                 * ------------------------------*/
                $sum_atas .= "<tr class='text-uppercase bg-grey-2'>";
                if (isset($summariLabels[$kei_1]['header'])) {
                    $num = 0;
                    foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {
                        $num++;

                        $nilai_bawah = isset($sumValueBawahs[$kei_1][$headerKey]) ? $sumValueBawahs[$kei_1][$headerKey] : '-';
                        $nilai_bawah_f = $num == 1 ? "Total" : number_format($nilai_bawah);
                        $sum_atas .= "<th title='$headerKey'>$nilai_bawah_f</th>";
                    }
                }
                else {
                    $sumValueBawah_f = number_format($sumValueBawah);
                    $sum_atas .= "<th>total</th><th class='text-right'>$sumValueBawah_f</th>";
                }
                $sum_atas .= "</tr>";

                // $sumValueBawah_f = number_format($sumValueBawah);
                // $sum_atas .= "<tr class='text-uppercase bg-grey-2'><th>total</th><th class='text-right'>$sumValueBawah_f</th></tr>";


                $sum_atas .= "</table>";
                $sum_atas .= "</div>";
            }
            $sum_atas .= "</div>";

            $sum_atas_btn .= "<div class='btn-group'>";
            $sum_atas_btn .= $sum_btn;
            $sum_atas_btn .= "</div>";
        }

        if(isset($wadah_atas)){

            $sum_atas .= "<div class=\'row\' id='wadah_atas'>$wadah_atas</div>";
        }

        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
            .bg-grey-2 {
                background-color: #EFEBEF !important;
            }
        </style>";
        $strTbl .= "<div style='margin-bottom: 10px;overflow: hidden;'>";
        $strTbl .= "<div id='summary_btn_$data_id'>$sum_atas_btn</div>";
        $strTbl .= "<div id='summary_datas_$data_id'>$sum_atas</div>";
        $strTbl .= "<div id='summary_atas_$data_id'></div>";
        $strTbl .= "</div>";
        // -------------------------------------------------------------------------------------------------
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red' id='$tbl_id'>";
        // $strTbl .= "<caption class='hidden'>testing</caption>";
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
                                    pageLength: 50,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
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
                                        var jml_kolom = (arrayFooter.length) - 1;
                                        jQuery.each(arrayFooter, function(i,d){
                                            var id_n_index = parseFloat(i);
                                            // console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii = '', obj = ''){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);
                                        //
                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                        //        
                                        //         if((id_n_index > 4) && (id_n_index < jml_kolom)){                                                
                                        //             if ($('#ph_'+id_n_index).length === 0) {
                                        //            
                                        //                 $( api.column(id_n_index).header() ).append(
                                        //                     \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                        //                 );
                                        //             }                                                                                                                                          
                                        //            
                                        //             $(\"#ph_\"+id_n_index).html(
                                        //                 \"<b>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                        //             );
                                        //         }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
                    });
                    
                    // datareview$tbl_id.on('order.dt search.dt', function () {
                    //     let i = 1;
                    //     datareview$tbl_id.cells(null, 0, {
                    //         search: 'applied', order: 'applied'
                    //         }).every(function (cell) {
                    //             this.data(i++);
                    //         });
                    // }).draw();
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                    //  ----------------------------------------------------------
                                    }, 500));

                   </script>";
        $strTbl .= "<script>
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


        if (isset($summary_on_top)) {
            // $penjualan_total = isset($totals["transaksi_nilai"]) ? $totals["transaksi_nilai"] : 0;
            // $penjualan_total = number_format($penjualan_total);

            $sum_bawah_data = $summariNilais;
            $jml_baris_data = count($master_data);
            $sum_bawah_data = $summary_on_top;
            $sum_atas = "";
            $strTbl .= "<style>
                .info-box-content {
                    margin-left: 60px;
                }
                .info-box {
                    min-height: 60px;
                    background-color: #00ef4c12;
                }
                .info-box-icon {
                    height: 60px;
                    width: 60px;
                    line-height: 60px;
                }
            </style>";
            $sum_atas .= "<div class=\'row\'>***";

            foreach ($sum_bawah_data as $kei => $sum_bawah_datum) {

                $label = isset($sum_bawah_datum['label']) ? $sum_bawah_datum['label'] : $kei;
                $icon_fa = isset($sum_bawah_datum['icon_fa']) ? "fa fa-" . $sum_bawah_datum['icon_fa'] : "";
                $icon_bg = isset($sum_bawah_datum['icon_bg']) ? $sum_bawah_datum['icon_bg'] : "bg-aqua";
                $col_lebar = isset($sum_bawah_datum['col_lebar']) ? $sum_bawah_datum['col_lebar'] : "col-md-4 col-xl-3 col-xxl-2";

                $nilai_key = isset($sum_bawah_datum['nilai']) ? $sum_bawah_datum['nilai'] : 0;
                if (strstr($nilai_key, ".")) {
                    $nilai_0 = isset($sum_bawah_datum['nilai']) ? str_replace(".", "", $sum_bawah_datum['nilai']) : 0;
                    $nilai = $$nilai_0;
                }
                else {
                    // arrPrintKuning($totals);
                    // cekHere("$nilai_key");
                    $nilai_0 = isset($totals[$nilai_key]) ? $totals[$nilai_key] : 0;
                    $nilai = number_format($nilai_0);
                }
                $nilai_tpl = isset($sum_bawah_datum['nilai_tpl']) ? str_replace("{nilai}", $nilai, $sum_bawah_datum['nilai_tpl']) : "";


                $sum_atas .= "<div class=\'$col_lebar \'>";
                $sum_atas .= "<div class=\'info-box\'>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-gear-outline \'></i></span>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-list-outline \'></i></span>";
                $sum_atas .= "<span class=\'info-box-icon $icon_bg \'><i class=\'$icon_fa \'></i></span>";

                $sum_atas .= "<div class=\'info-box-content\'>";
                $sum_atas .= "<span class=\'info-box-text\'>$label</span>";
                $sum_atas .= "<span class=\'info-box-number\'>$nilai_tpl</span>";
                $sum_atas .= "</div>";

                $sum_atas .= "</div>";

                $sum_atas .= "</div>";
            }
            // $sum_atas .= $p->layout_box_info($sum_bawah_data);
            $sum_atas .= "</div>";

            $strTbl .= "<script>
                $('#summary_atas_$data_id').append('$sum_atas');
            </script>";
        }
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
            echo "<div style='padding: 10px;'>$strSummary</div>";
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

        /* ---------------------------------------------------------------------------
         * body
         * ---------------------------------------------------------------------------*/
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
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "";
                    $key_get_tambahan = "";
                    if ($reqKey != false) {
                        $key_get_tambahan = "&$reqKey=$reqValue&ky=$reqKey";
                    }
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . $key_get_tambahan . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkDetile = "https://san.mayagrahakencana.com/" . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
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
            "fulldate",
            "jam",
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

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";

        $strTbl .= "<div id='summary_atas'></div>";
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
                     $auto_tr            
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
        $p = New Layout();

        if (isset($summary_on_top)) {
            $penjualan_total = isset($totals["transaksi_nilai"]) ? $totals["transaksi_nilai"] : 0;
            $penjualan_total = number_format($penjualan_total);

            $sum_bawah_data = $summary_on_top;
            $sum_atas = "";
            $strTbl .= "<style>
                .info-box-content {
                    margin-left: 60px;
                }
                .info-box {
                    min-height: 60px;
                    background-color: #00ef4c12;
                }
                .info-box-icon {
                    height: 60px;
                    width: 60px;
                    line-height: 60px;
                }
            </style>";
            $sum_atas .= "<div class=\'row\'>";

            foreach ($sum_bawah_data as $kei => $sum_bawah_datum) {

                $label = isset($sum_bawah_datum['label']) ? $sum_bawah_datum['label'] : $kei;
                $icon_fa = isset($sum_bawah_datum['icon_fa']) ? "fa fa-" . $sum_bawah_datum['icon_fa'] : "";
                $icon_bg = isset($sum_bawah_datum['icon_bg']) ? $sum_bawah_datum['icon_bg'] : "bg-aqua";
                $icon_link = isset($sum_bawah_datum['icon_link']) ? $sum_bawah_datum['icon_link'] : "";
                $link_rinci = "location.href=\"https://detik.com\""; // format penulisannya quot
                // $link_rinci = modalDialogBtn("anu",$link_rinci); // format penulisannya quot
                $icon_fl = isset($sum_bawah_datum['icon_link']) ? "<a href=\'JavaScript:void(0);\' onclick=\'$link_rinci\'><i class=\'$icon_fa \'></i></a>" : "<i class=\'$icon_fa \'></i>";
                $nilai_key = isset($sum_bawah_datum['nilai']) ? $sum_bawah_datum['nilai'] : 0;
                if (strstr($nilai_key, ".")) {
                    $nilai_0 = isset($sum_bawah_datum['nilai']) ? str_replace(".", "", $sum_bawah_datum['nilai']) : 0;
                    $nilai = $$nilai_0;
                }
                else {
                    $nilai_key = isset($sum_bawah_datum['nilai']) ? $sum_bawah_datum['nilai'] : 0;
                    $nilai_0 = $totals[$nilai_key];
                    $nilai = number_format($nilai_0);
                }

                $nilai_tpl = isset($sum_bawah_datum['nilai_tpl']) ? str_replace("{nilai}", $nilai, $sum_bawah_datum['nilai_tpl']) : "";


                $sum_atas .= "<div class=\'col-md-4\'>";
                $sum_atas .= "<div class=\'info-box\'>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-gear-outline \'></i></span>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-list-outline \'></i></span>";
                $sum_atas .= "<span class=\'info-box-icon $icon_bg \'>$icon_fl</span>";

                $sum_atas .= "<div class=\'info-box-content\'>";
                $sum_atas .= "<span class=\'info-box-text\'>$label</span>";
                $sum_atas .= "<span class=\'info-box-number\'>$nilai_tpl</span>";
                $sum_atas .= "</div>";

                $sum_atas .= "</div>";

                $sum_atas .= "</div>";
            }
            // $sum_atas .= $p->layout_box_info($sum_bawah_data);
            $sum_atas .= "</div>";

            $strTbl .= "<script>
                $('#summary_atas').append('$sum_atas');
            </script>";
        }

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
        // $p = New Layout();
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
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/laporan_indek.html");

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
        $jenistr = isset($jenisTr) ? $jenisTr : "580";
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
        $loader_indikator = "<div class='loader-5 center'><span></span></div>";
        $content = "";
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu'>Mempersiapkan Data...  <div style='margin-left:50%;'>$loader_indikator</div></div>";
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
        // $loaders = "";
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
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "add_td"           => isset($add_td) ? $add_td : "",
                "content"          => $content,
                // navigasi
                "url"              => $url,
                "date1"            => $date1,
                "date2"            => $date2,
                "date_min"         => $date_min,
                "date_max"         => $date_max,

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

    case "viewSummary":

        $var = "";
        foreach ($summary as $key_1 => $item) {

            $var .= "<div>";
            $var .= "$key_1";
            $var .= "</div>";
        }

        echo $var;
        break;

    case "pivot":
        // cekHere(__LINE__);
        // arrPrintPink($pivotDatas);
        $master_data = $pivotDatas;
        // $strHead = "";
        // $strHead .= "<tr class='text-uppercase'>";
        // $strHead .= "<td>no</td>";
        // foreach ($arrHeaders as $kolom => $arrHeader) {
        //     $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
        //     $strHead .= "<th title='$kolom'>$hLabel</th>";
        // }
        // $strHead .= "<th title='$kolom'>ok</th>";
        // $strHead .= "</tr>";

        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";
        // cekHijau(count($master_data));
        $strBodySatu = "";
        foreach ($master_data as $master_datum) {
            $no++;
            // $strBody .= "<tr >";
            // $strBodySatu .= "<td rowspan='$jmlRincianBaris'>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != null ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $str_logic = "";
                if (isset($attrs['logics'])) {
                    $ltexs = isset($attrs['logics']['text']) ? $attrs['logics']['text'] : "";
                    $lnilai = $attrs['logics']['nilai']; // tidak berguna ni, coba cari cari yg lain

                    $str_logic = $nilai < 0 ? "<br><div class='meta'>$ltexs</div>" : '';
                }


                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
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
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "";
                    $key_get_tambahan = "";
                    if ($reqKey != false) {
                        $key_get_tambahan = "&$reqKey=$reqValue&ky=$reqKey";
                    }
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . $key_get_tambahan . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkDetile = "https://everest.mayagrahakencana.com/" . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
                }
                if (isset($attrs['sub'])) {
                    $subHeader = $attrs['sub'];
                    $jmlRincianBaris = count($nilai) + 2;
                    // cekHere($jmlRincianBaris);
                    // arrPrintKuning($nilai);
                    $strBodySub = "";
                    $subTotal = array();
                    foreach ($nilai as $item) {
                        $itemLabel = $item['label'];
                        $itemTotal = $item['total'];


                        $strBodySub .= "<tr>";
                        foreach ($subHeader as $subKey => $subParams) {
                            $subNilai = $item[$subKey];

                            $attr = isset($subParams['attr']) ? $subParams['attr'] : "";
                            $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                            $format_key = isset($subParams['format_key']) ? $subParams['format_key'] : $subKey;
                            $subNilai_f = isset($subParams['format']) ? ($subNilai >= 0 ? $subParams['format']($format_key, $subNilai, $jenistr, $modul_path) : $subNilai) : $subNilai;

                            $strBodySub .= "<td>$subNilai_f</td>";

                            if ($subParams['label']) {
                                $subLabel = isset($subParams['label']) ? $subParams['label'] : "";
                                $arrHeaders2[$subKey] = array(
                                    "label" => $subLabel,
                                );
                            }

                            if (isset($subParams['summary']) && $subParams['summary'] == true) {

                                if (!isset($subTotal[$subKey])) {
                                    $subTotal[$subKey] = 0;
                                }
                                $subTotal[$subKey] += $subNilai;
                            }
                        }
                        $strBodySub .= "</tr>";
                    }
// arrPrintKuning($subHeader);
                    $strBodySub .= "<tr>";
                    foreach ($subHeader as $subKey => $subParams) {
                        $nilaiSubBawah = isset($subTotal[$subKey]) ? $subTotal[$subKey] : "-";
                        // $nilaiSubBawah_f = number_format($nilaiSubBawah);
                        $format_key = isset($subParams['format_key']) ? $subParams['format_key'] : $subKey;
                        $nilaiSubBawah_f = isset($subParams['format']) ? ($nilaiSubBawah >= 0 ? $subParams['format']($format_key, $nilaiSubBawah, $jenistr, $modul_path) : $subNilai) : $nilaiSubBawah;

                        $strBodySub .= "<th class='bg-grey-2'>$nilaiSubBawah_f</th>";
                    }
                    $strBodySub .= "</tr>";
                    // arrPrintHijau($subTotal);
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

                // $strBodyDuas[] = "<td rowspan='$jmlRincianBaris'  title2='$pengenal_link' title3='$pengenal_masterid' $attr>$nilai_link $str_logic</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }

            }
            // arrPrintHijau($arrHeaders2);

            $strBody .= "<tr>";
            $strBody .= $strBodySatu = "<td rowspan='$jmlRincianBaris'>$no</td>";
            // $strBody .= $strBodySatu = "<td data-datatable-multi-row-rowspan='$jmlRincianBaris'>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";
                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != null ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (!is_array($nilai)) {
                    $strBody .= "<td rowspan='$jmlRincianBaris' $attr>$nilai_f</td>";
                }
                else {
                    // $strBody .=  "<td></td>";
                }
            }
            $strBody .= "</tr>";

            $strBody .= $strBodySub;

        }

        // ----------------------------------------------------------------------head-----------
        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<th>no</th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th title='$kolom'>$hLabel</th>";
        }
        foreach ($arrHeaders2 as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th title='$kolom'>$hLabel</th>";
        }
        // $strHead .= "<th title='$kolom'>ok</th>";
        $strHead .= "</tr>";
        // ----------------------------------------------------------------------head-----------

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
            "fulldate"
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

            $strFoot .= "<th $attr title='$kolom'>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }
        foreach ($arrHeaders2 as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strFoot .= "<th title='$kolom'>-</th>";
        }

        // arrPrintHijau($summariLabels);
        // arrPrintKuning($summariNilais);
        // arrPrint($summariSubjectLabels);
        $sum_btn = "";
        $sum_atas_btn = "";
        $sum_atas = "";
        if (isset($summariLabels)) {
            $sum_atas_btn .= "Berdasarkan: ";
            $sum_atas .= "<div class=\'row\'>";
            foreach ($summariNilais as $kei_1 => $sumValues) {
                $kei_1_label = $summariSubjectLabels[$kei_1];

                $sum_btn .= "<button type='button' class='btn btn-info text-uppercase' title='$kei_1' onclick=\"$('#$kei_1').fadeToggle();\">$kei_1_label</button>";

                $sum_atas .= "<div id='$kei_1' class='panel panel-default' style='display: none; float: left;margin-right: 5px;margin-top: 3px;'>";
                $sum_atas .= "<table class='table table-borderer table-striped table-hover-color-red'>";
                // $sum_atas .= "<tr class='text-uppercase bg-grey-2'><th>$kei_1_label</th><th>akumulasi transaksi</th>  </tr>";
                // cekKuning("$kei_1");
                //  arrPrintPink($sumValues);
                /* -------------------
                 * header
                 * -------------------*/
                $sum_atas .= "<tr class='text-uppercase bg-grey-2'>";
                if (isset($summariLabels[$kei_1]['header'])) {
                    foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {
                        $header_label = isset($headerParams['label']) ? $headerParams['label'] : $headerKey;
                        $sum_atas .= "<th>$header_label</th>";
                    }
                }
                else {
                    $sum_atas .= "<th colspan='2'>$kei_1_label</th>";
                }
                $sum_atas .= "</tr>";

                /* -------------------
                 * body
                 * -------------------*/
                $sumValueBawah = 0;
                foreach ($sumValues as $kei_2 => $sumValue) {
                    $sum_atas .= "<tr class='text-uppercase'>";
                    if (is_array($sumValue)) {
                        // arrPrint($sumValue);
                        foreach ($sumValue as $itemkey => $itemSumm) {
                            // cekKuning("$itemkey");
                            // arrPrint($itemSumm);
                            // $sumValue_f = is_numeric($itemSumm) ? number_format($itemSumm) : $itemSumm;
                            // $sum_atas .= "<td>$sumValue_f</td>";


                        }

                        //     ------------------------------

                        foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {

                            // $header_label = isset($headerParams['label']) ? $headerParams['label'] : $headerKey;

                            $itemSumm = $sumValue[$headerKey];
                            $sumValue_f = is_numeric($itemSumm) ? number_format($itemSumm) : $itemSumm;
                            $sumAttr = isset($headerParams['attr']) ? $headerParams['attr'] : "";

                            $sum_atas .= "<td $sumAttr>$sumValue_f</td>";

                            /* ------ -------------
                                ngesumm unutk footer
                            -----------------------*/
                            if (isset($headerParams['summary']) && ($headerParams['summary'] == true)) {
                                if (!isset($sumValueBawahs[$kei_1][$headerKey])) {
                                    $sumValueBawahs[$kei_1][$headerKey] = 0;
                                }
                                $sumValueBawahs[$kei_1][$headerKey] += $sumValue[$headerKey];
                            }

                        }
                    }
                    else {

                        $kei_2_label = $summariLabels[$kei_1][$kei_2];
                        $sumValue_f = number_format($sumValue);
                        // $sum_atas .= "<tr class='text-uppercase'>";
                        $sum_atas .= "<td>$kei_2_label</td>";
                        $sum_atas .= "<td class='text-right'>$sumValue_f</td>";
                        // $sum_atas .= "</tr>";

                        $sumValueBawah += $sumValue;
                    }
                    $sum_atas .= "</tr>";
                }

                /* ----------------------------
                 * footer
                 * ------------------------------*/
                $sum_atas .= "<tr class='text-uppercase bg-grey-2'>";
                if (isset($summariLabels[$kei_1]['header'])) {
                    $num = 0;
                    foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {
                        $num++;

                        $nilai_bawah = isset($sumValueBawahs[$kei_1][$headerKey]) ? $sumValueBawahs[$kei_1][$headerKey] : '-';
                        $nilai_bawah_f = $num == 1 ? "Total" : number_format($nilai_bawah);
                        $sum_atas .= "<th title='$headerKey'>$nilai_bawah_f</th>";
                    }
                }
                else {
                    $sumValueBawah_f = number_format($sumValueBawah);
                    $sum_atas .= "<th>total</th><th class='text-right'>$sumValueBawah_f</th>";
                }
                $sum_atas .= "</tr>";

                // $sumValueBawah_f = number_format($sumValueBawah);
                // $sum_atas .= "<tr class='text-uppercase bg-grey-2'><th>total</th><th class='text-right'>$sumValueBawah_f</th></tr>";


                $sum_atas .= "</table>";
                $sum_atas .= "</div>";
            }
            $sum_atas .= "</div>";

            $sum_atas_btn .= "<div class='btn-group'>";
            $sum_atas_btn .= $sum_btn;
            $sum_atas_btn .= "</div>";
        }

        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
            .bg-grey-2 {
                background-color: #EFEBEF !important;
            }
            .table.dataTable tbody th, table.dataTable tbody td{
                white-space: unset;
            }
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th {
                padding: 3px 8px;
            }
        </style>";
        $strTbl .= "<div style='margin-bottom: 10px;overflow: hidden;'>";
        $strTbl .= "<div id='summary_btn_$data_id'>$sum_atas_btn</div>";
        $strTbl .= "<div id='summary_datas_$data_id'>$sum_atas</div>";
        $strTbl .= "<div id='summary_atas_$data_id'></div>";
        $strTbl .= "</div>";
        // -------------------------------------------------------------------------------------------------
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red table-bordered datatable datatable-multi-row' id='$tbl_id'>";
        // $strTbl .= "<caption class='hidden'>testing</caption>";
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
                        
                        // $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
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
                                    pageLength: 20,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
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
                                        var jml_kolom = (arrayFooter.length) - 1;
                                        jQuery.each(arrayFooter, function(i,d){
                                            var id_n_index = parseFloat(i);
                                            // console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii = '', obj = ''){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);
                                        //
                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                        //        
                                        //         if((id_n_index > 4) && (id_n_index < jml_kolom)){                                                
                                        //             if ($('#ph_'+id_n_index).length === 0) {
                                        //            
                                        //                 $( api.column(id_n_index).header() ).append(
                                        //                     \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                        //                 );
                                        //             }                                                                                                                                          
                                        //            
                                        //             $(\"#ph_\"+id_n_index).html(
                                        //                 \"<b>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                        //             );
                                        //         }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    },
                                    fnDrawCallback: function() {
                                    
                                        $table = $(this);
                                                                        
                                        if ($table.closest('.datatable-multi-row').length) {
                                                                        
                                          $table.find('tbody>tr').each(function() {
                                            var $tr = $(this);
                                    
                                            var extra_row = $tr.find('.extra-row-content').html();
                                    
                                            if (!$tr.next().hasClass('dt-added')) {
                                              $tr.after(extra_row);
                                              $tr.find('td').each(function() {
                                    
                                                var $td = $(this);
                                                var rowspan = parseInt($td.data('datatable-multi-row-rowspan'), 10);
                                                if (rowspan) {
                                                  $td.attr('rowspan', rowspan);
                                                }
                                              });
                                            }
                                    
                                          });
                                    
                                        } // end if the table has the proper class
                                      } // end fnDrawCallback()
             
                    });
                    
                    // datareview$tbl_id.on('order.dt search.dt', function () {
                    //     let i = 1;
                    //     datareview$tbl_id.cells(null, 0, {
                    //         search: 'applied', order: 'applied'
                    //         }).every(function (cell) {
                    //             this.data(i++);
                    //         });
                    // }).draw();
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                    //  ----------------------------------------------------------
                                    }, 500));

                   </script>";
        $strTbl .= "<script>
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


        if (isset($summary_on_top)) {
            // $penjualan_total = isset($totals["transaksi_nilai"]) ? $totals["transaksi_nilai"] : 0;
            // $penjualan_total = number_format($penjualan_total);

            $sum_bawah_data = $summariNilais;
            $jml_baris_data = count($master_data);
            $sum_bawah_data = $summary_on_top;
            $sum_atas = "";
            $strTbl .= "<style>
                .info-box-content {
                    margin-left: 60px;
                }
                .info-box {
                    min-height: 60px;
                    background-color: #00ef4c12;
                }
                .info-box-icon {
                    height: 60px;
                    width: 60px;
                    line-height: 60px;
                }
            </style>";
            $sum_atas .= "<div class=\'row\'>***";

            foreach ($sum_bawah_data as $kei => $sum_bawah_datum) {

                $label = isset($sum_bawah_datum['label']) ? $sum_bawah_datum['label'] : $kei;
                $icon_fa = isset($sum_bawah_datum['icon_fa']) ? "fa fa-" . $sum_bawah_datum['icon_fa'] : "";
                $icon_bg = isset($sum_bawah_datum['icon_bg']) ? $sum_bawah_datum['icon_bg'] : "bg-aqua";
                $col_lebar = isset($sum_bawah_datum['col_lebar']) ? $sum_bawah_datum['col_lebar'] : "col-md-4 col-xl-3 col-xxl-2";

                $nilai_key = isset($sum_bawah_datum['nilai']) ? $sum_bawah_datum['nilai'] : 0;
                if (strstr($nilai_key, ".")) {
                    $nilai_0 = isset($sum_bawah_datum['nilai']) ? str_replace(".", "", $sum_bawah_datum['nilai']) : 0;
                    $nilai = $$nilai_0;
                }
                else {
                    // arrPrintKuning($totals);
                    // cekHere("$nilai_key");
                    $nilai_0 = isset($totals[$nilai_key]) ? $totals[$nilai_key] : 0;
                    $nilai = number_format($nilai_0);
                }
                $nilai_tpl = isset($sum_bawah_datum['nilai_tpl']) ? str_replace("{nilai}", $nilai, $sum_bawah_datum['nilai_tpl']) : "";


                $sum_atas .= "<div class=\'$col_lebar \'>";
                $sum_atas .= "<div class=\'info-box\'>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-gear-outline \'></i></span>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-list-outline \'></i></span>";
                $sum_atas .= "<span class=\'info-box-icon $icon_bg \'><i class=\'$icon_fa \'></i></span>";

                $sum_atas .= "<div class=\'info-box-content\'>";
                $sum_atas .= "<span class=\'info-box-text\'>$label</span>";
                $sum_atas .= "<span class=\'info-box-number\'>$nilai_tpl</span>";
                $sum_atas .= "</div>";

                $sum_atas .= "</div>";

                $sum_atas .= "</div>";
            }
            // $sum_atas .= $p->layout_box_info($sum_bawah_data);
            $sum_atas .= "</div>";

            $strTbl .= "<script>
                $('#summary_atas_$data_id').append('$sum_atas');
            </script>";
        }
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
            echo "<div style='padding: 10px;'>$strSummary</div>";
            echo "<div style='padding: 10px;'>$strTbl</div>";
        }
        else {
            echo $p->layout_box($strTbl);
        }

        break;
}

?>