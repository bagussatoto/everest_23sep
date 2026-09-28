<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 05/12/18
 * Time: 21:38
 */
//cekHere($mode);
switch ($mode) {
    default:
        mati_disini("mode belum dibuat [$mode] @" . __LINE__ . __FILE__);
        break;
    case "viewRekening":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

        //        foreach ($navigasi as $keyNav => $valNav) {
        //            $labelNav = $navigasiAttr[$keyNav]["label"];
        //            $minimal = $navigasiAttr[$keyNav]["minimal"];
        //
        //            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                                    <label>$labelNav: </label>
        //                                    <input name='$keyNav' id='$keyNav' class='form-control' type='number' value='$valNav' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
        //                                </div>";
        //        }
        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

        // $content_nav .= form_button("cek", "show graph", "class='btn btn-info' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";
        //endregion

        /* ==============================
         * URUSAN CHART
         * ----------------------*/

        $kolomX = array(
            "nilai_af" => "netto",
        );
        $yKoloms = array(
            "nilai_af" => "total penjualan",
        );

        $varDatas = array();
        $varBiaya = array();
        $qtDatas = array();
        $qtBiaya = array();
        $varYlabels = array();
        $varYkeys = array();

        //        arrPrint($content);

        //        matiHere();
        //        if (sizeof($penjualanBulanan) > 0) {
        //            /* =================================
        //             * penjualan bulanan
        //             * -------------------*/
        //            $hChart=array();
        //            foreach ($penjualanBulanan as $th => $datas_1) {
        //                foreach ($datas_1 as $bl => $datas_2) {
        //
        //                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
        //
        //                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
        //                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
        //
        //                    $hChart['netto'][] = ($totals-$hpp-$biaya);
        //                    $specs['netto'] = ($totals-$hpp-$biaya);
        //
        //                    $hChart['bruto'][] = ($totals-$hpp);
        //                    $specs['bruto'] = ($totals-$hpp);
        //
        //                    $hChart['hpp'][]=$hpp;
        //                    $hChart['biaya'][] = $biaya;
        //
        //                    $specs['hpp'] = $hpp;
        //                    $specs['biaya'] = $biaya;
        //
        ////                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
        ////                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
        //                    $hChart['nilai_af'][] = round($datas_2['nilai_af']);
        //                    $specs['nilai_af'] = round($datas_2['nilai_af']);
        //
        //                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
        //                    $thBl = "$th " . $namaBl;
        //
        //                    $hChart['bulan'][] = $thBl;
        //                    $specs['bulan'] = $thBl;
        //
        //                    $varYkeys = array();
        //                    $varYlabels = array();
        //
        //                    //bar
        //                    $varYkeys[] = 'netto';
        //                    $varYlabels[] = 'laba bersih';
        //                    $varYkeys[] = 'bruto';
        //                    $varYlabels[] = 'laba kotor';
        //
        //                    //line
        //                    $varYkeys[] = 'nilai_af';
        //                    $varYlabels[] = 'total penjualan';
        ////                    $varYkeys[] = 'fire';
        ////                    $varYlabels[] = 'fire';
        ////                    $varYkeys[] = 'nonFire';
        ////                    $varYlabels[] = 'nonFire';
        //
        //                    $varYkeys[] = 'biaya';
        //                    $varYlabels[] = 'biaya';
        //                    $varYkeys[] = 'hpp';
        //                    $varYlabels[] = 'hpp';
        //
        //                    $varAll['min'][] = min($specs);
        //                    $varAll['max'][] = max($specs);
        //
        //                    $varDatas[] = $specs;
        //                }
        //
        ////                foreach ($datas_1 as $bl => $datas_2) {
        ////
        ////                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
        ////
        ////                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
        ////                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
        ////
        //////                    $specs['totalBiaya'] = $biaya;
        //////                    $specs['totalHpp'] = $hpp;
        ////
        ////                    $specs['netto'] = ($totals-$hpp-$biaya);
        ////                    $specs['bruto'] = ($totals-$hpp);
        ////
        ////                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
        ////                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
        ////                    $specs['nilai_af'] = round($datas_2['nilai_af']);
        ////
        ////                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
        ////                    $thBl = "$th " . $namaBl;
        ////
        ////                    $specs['bulan'] = $thBl;
        ////
        ////                    $varYkeys = array();
        ////                    $varYlabels = array();
        ////
        ////
        ////                    $varYkeys[] = 'fire';
        ////                    $varYkeys[] = 'nonFire';
        ////                    $varYkeys[] = 'nilai_af';
        ////                    $varYkeys[] = 'bruto';
        ////                    $varYkeys[] = 'netto';
        ////
        //////                    $varYkeys[] = 'totalBiaya';
        //////                    $varYkeys[] = 'totalHpp';
        ////
        ////                    $varYlabels[] = 'fire';
        ////                    $varYlabels[] = 'nonFire';
        ////                    $varYlabels[] = 'total penjualan';
        ////                    $varYlabels[] = 'laba kotor';
        ////                    $varYlabels[] = 'laba bersih';
        //////                    $varYlabels[] = 'total BIAYA';
        //////                    $varYlabels[] = 'total HPP';
        ////
        //////                    foreach ($yKoloms as $yKolom => $yLabel) {
        //////                        $varYlabels[] = $yLabel;
        //////                        $varYkeys[] = $yKolom;
        //////                        $specs[$yKolom] = round($datas_2[$yKolom]);
        //////                    }
        ////
        ////                    $varAll['min'][] = min($specs);
        ////                    $varAll['max'][] = max($specs);
        ////
        ////                    $varDatas[] = $specs;
        ////                }
        //
        //            }
        //
        //
        //        arrPrint($content);
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                //                    cekHijau($xLabel);
                //                    cekHijau($xData);
                if ("label" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                }
                else {
                    $$xLabel = "[" . implode(",", $xData) . "]";
                }
            }
        }


        //        arrPrint($bruto);
        //            $ymin = min($varAll['min']);
        //            $ymin = round($ymin-(($ymin*10)/100));
        //            $ymax = max($varAll['max']);
        //            $ymax = round($ymax-(($ymax*10)/100));
        //
        //            // arrPrint($penjualanQuarter);
        ////             arrPrint( $ymax );
        ////             arrPrint( $ymin );
        //            // arrPrint($penjualanQuarter);
        //
        //            /* ----------------------
        //             * penjual rata2 per triwulan
        //             * --------------------------------------------------dimatikan dulu bosss
        //             * ------------------------*/
        //            $qtDatas = array();
        //            // foreach ($penjualanQuarter as $th => $datas_1a) {
        //            //
        //            //     $urut = 0;
        //            //     foreach ($datas_1a as $qt => $datas_3a) {
        //            //         $bl = $dataQuarter[$th][$qt]['bl'];
        //            //
        //            //         $qtSpecs['quarter'] = (string)"#$qt/$th";
        //            //         $urut++;
        //            //         $qtSpecs['qt'] = (string)$datas_3a;
        //            //         $qtDatas[] = $qtSpecs;
        //            //     }
        //            // }
        //        }
        //
        ////        sort($varYkeys);
        //        $jsonQtDatas = json_encode($qtDatas);
        //        $jsonDatas = json_encode($varDatas);
        //        $jsonYkeys = json_encode($varYkeys);
        //        $jsonYlabel = json_encode($varYlabels);

        // arrPrint($namaBulan);
        // arrPrint($jsonYkeys);
        // arrPrint($jsonDatas);
        // arrPrint($varDatas);

        //         arrPrint($varYkeys);
        //         arrPrint($varBiaya);
        // arrPrint($varYlabels);
        // arrPrint($qtDatas);
        // arrPrint($jsonQtDatas);
        // matiHere();

        // region penjuala vs pembelian
        $varDatas_2 = array();
        if (sizeof($pembelianBulanan) > 0) {
            /* =================================
             * penjualan bulanan
             * -------------------*/
            // arrPrintWebs($pembelianBulanan);
            // arrPrintWebs($varDatas);
            // $varDatas = array();
            $varYkeys = array();
            $varYlabels = array();
            $indek = -1;
            $baseData = "penjualan";
            if ($baseData == "pembelian") {
                // -----------BASE DATA PEMBELIAN--------------
                foreach ($pembelianBulanan as $th => $datas_1) {
                    foreach ($datas_1 as $bl => $datas_2) {
                        $indek++;
                        // cekHijau("$datas_2");
                        $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                        $thBl = "$th " . $namaBl;
                        // $thBl = $namaBl;
                        // cekHere($thBl);
                        $specs_2['bulan'] = $thBl;
                        $specs_2['pembelian'] = $datas_2;
                        $specs_2['penjualan'] = $varDatas[$indek]["nilai_af"];

                        // arrPrintWebs($specs_2);
                        $varDatas_2[] = $specs_2;
                    }
                }
            }
            else {
                // -----------BASE DATA PENJUALAN--------------
                if (is_array($penjualanBulanan) && (sizeof($penjualanBulanan) > 0)) {
                    foreach ($penjualanBulanan as $th => $datas_1) {
                        foreach ($datas_1 as $bl => $datas_2) {
                            $indek++;
                            // arrPrint($datas_2);
                            $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                            $thBl = "$th " . $namaBl;
                            // $thBl = $namaBl;
                            // cekHere($thBl);
                            $specs_2['bulan'] = $thBl;
                            $specs_2['penjualan'] = $datas_2['nilai_af'];
                            $specs_2['pembelian'] = isset($pembelianBulanan[$th][$bl]) ? $pembelianBulanan[$th][$bl] : 0;

                            // arrPrintWebs($specs_2);
                            $varDatas_2[] = $specs_2;
                        }
                    }
                }

            }

            $varYkeys_2[] = 'pembelian';
            $varYkeys_2[] = 'penjualan';
            $varYlabels_2[] = 'pembelian';
            $varYlabels_2[] = 'penjualan';
            // arrPrint($varDatas_2);
        }

        $jsonDatas_2 = json_encode($varDatas_2);
        $jsonYkeys_2 = json_encode($varYkeys_2);
        $jsonYlabel_2 = json_encode($varYlabels_2);
        // endregion penjuala vs pembelian


        $strData = "";
        // $strData .= "xxx";
        // $strData .= "<div class='nav-tabs-custom'>";
        // $strData .= "<div class='col-md-12'>";

        //        $strData .= "<div class='box box-solid box-success'>";
        //        $strData .= "<div class='box-header with-border'><h3>Monthly Sales Morris</h3></div>";
        //        $strData .= "<div class='box-body'>";
        //        $strData .= "<div class='dchart' id='bar-chart' style='pposition: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        //        $strData .= "</div>";
        //        $strData .= "<div id='legend' class='bars-legend'></div>";
        //        $strData .= "</div>"; // body
        //        $strData .= "</div>"; // box end

        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_1'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        // $strData .= "</div>"; // box end 12

        //        arrPrint($varDatas);

        $strData .= "<script>

        var chart = Highcharts.chart('container_1', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    toggle: {
                        align: 'left',
                        y: 30,
                        x: 20,
                        height: 14,
                        theme: {
                            'stroke-width': 1,
                            stroke: 'silver',
                            r: 0
                        },
                        text: 'Chart Height',
                        menuItems: [{
                            text: '400px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 400)
                            }
                        }, {
                            text: '800px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 800)
                            }
                        }, {
                            text: '1200px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1200)
                            }
                        }, {
                            text: '1600px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1600)
                            }
                        }]
                    }
                }
            },
          chart: {
            type: \"xy\",
            height: 400,
          },
          title: {
            text: 'GRAPH REPORTS',
            align: 'left'
          },
          subtitle: {
            text: \"<div class='text-bold'>laba bersih = penjualan - hpp - biaya</div><br><div class='text-bold'>laba kotor = penjualan - hpp</div><br><div class='text-bold'> </div>\",
            align: 'center'
          },
          xAxis: [{
            categories: $label,
            crosshair: true
          }],
          yAxis: [
            { // Primary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            opposite: true
          },
          { // Secondary yAxis
//            type: 'linear',
//            max: ymax,
//            min: ymin,
            tickInterval: 10000000,
            gridLineWidth: 1,
            plotLines: [{
                color: '#C0C0C0',
                width: 3,
                value: 0
            }],
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            },
            labels: {
              format: '{text}',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            }
          }],
          tooltip: {
            shared: true
          },
          plotOptions: {
                series: {
                    label: {
                        enabled: false,
                    }
                }
           },
          legend: {
            layout: 'vertical',
            align: 'right',
            x: 10,
            verticalAlign: 'top',
            y: 85,
            floating: true,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },
          series: [
            {
            name: 'Laba Kotor',
            type: 'column',
            color: 'red',
            yAxis: 1,
            stacking: 'normal',
            data: $bruto,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Laba Bersih',
            type: 'column',
            yAxis: 1,
            color: 'green',
            stacking: 'normal',
            data: $netto,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'penjualan',
            type: 'spline',
            color: 'black',
            yAxis: 1,
            data: $penjualan,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'HPP',
            type: 'spline',
            color: 'blue',
            yAxis: 1,
            data: $hpp,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Biaya (tanpa HPP)',
            type: 'spline',
            color: 'orange',
            yAxis: 1,
            data: $biaya,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Total Biaya',
            type: 'spline',
            color: 'pink',
            yAxis: 1,
            data: $biayahpp,
            tooltip: {
              valueSuffix: ' '
            }
          }
          ],
          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  floating: false,
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  x: 0,
                  y: 0
                }
              }
            }]
          }
        });

//function getDataChart() {
//  setTimeout(function() {
//    fetch('https://demo.mayagrahakencana.com/san_saham_up/eusvc/Graph/askPenjualanBulanan').then(function(response) {
//      return response.json()
//    }).then(function(data) {
//
////var label = this.xAxis.categories,
////    bruto = this.series[0],
////    netto = this.series[1];
//
//    console.log(data.label);
//
//    //label.addPoint([x, y], false, true);
//
//        chart.options.xAxis.categories = data.label.split(\",\");
//        chart.options.series[0].data = data.bruto.split(\",\");
//        chart.options.series[1].data = data.netto.split(\",\");
//    })
//  }, 1000)
//}

$('.highcharts-credits').remove();

        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = '';
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----


        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content"     => $strData,
                "add_link"    => "",
                "stop_time"   => "",
            ));

            $p->render();
        }
        else {
            echo $strData;
        }

        break;

    case "viewGraphSales":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

        //        foreach ($navigasi as $keyNav => $valNav) {
        //            $labelNav = $navigasiAttr[$keyNav]["label"];
        //            $minimal = $navigasiAttr[$keyNav]["minimal"];
        //
        //            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                                    <label>$labelNav: </label>
        //                                    <input name='$keyNav' id='$keyNav' class='form-control' type='number' value='$valNav' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
        //                                </div>";
        //        }
        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

        // $content_nav .= form_button("cek", "show graph", "class='btn btn-info' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";
        //endregion

        /* ==============================
         * URUSAN CHART
         * ----------------------*/

        $kolomX = array(
            "nilai_af" => "netto",
        );
        $yKoloms = array(
            "nilai_af" => "total penjualan",
        );

        $varDatas = array();
        $varBiaya = array();
        $qtDatas = array();
        $qtBiaya = array();
        $varYlabels = array();
        $varYkeys = array();

        //        arrPrint($content);

        //        matiHere();
        //        if (sizeof($penjualanBulanan) > 0) {
        //            /* =================================
        //             * penjualan bulanan
        //             * -------------------*/
        //            $hChart=array();
        //            foreach ($penjualanBulanan as $th => $datas_1) {
        //                foreach ($datas_1 as $bl => $datas_2) {
        //
        //                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
        //
        //                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
        //                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
        //
        //                    $hChart['netto'][] = ($totals-$hpp-$biaya);
        //                    $specs['netto'] = ($totals-$hpp-$biaya);
        //
        //                    $hChart['bruto'][] = ($totals-$hpp);
        //                    $specs['bruto'] = ($totals-$hpp);
        //
        //                    $hChart['hpp'][]=$hpp;
        //                    $hChart['biaya'][] = $biaya;
        //
        //                    $specs['hpp'] = $hpp;
        //                    $specs['biaya'] = $biaya;
        //
        ////                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
        ////                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
        //                    $hChart['nilai_af'][] = round($datas_2['nilai_af']);
        //                    $specs['nilai_af'] = round($datas_2['nilai_af']);
        //
        //                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
        //                    $thBl = "$th " . $namaBl;
        //
        //                    $hChart['bulan'][] = $thBl;
        //                    $specs['bulan'] = $thBl;
        //
        //                    $varYkeys = array();
        //                    $varYlabels = array();
        //
        //                    //bar
        //                    $varYkeys[] = 'netto';
        //                    $varYlabels[] = 'laba bersih';
        //                    $varYkeys[] = 'bruto';
        //                    $varYlabels[] = 'laba kotor';
        //
        //                    //line
        //                    $varYkeys[] = 'nilai_af';
        //                    $varYlabels[] = 'total penjualan';
        ////                    $varYkeys[] = 'fire';
        ////                    $varYlabels[] = 'fire';
        ////                    $varYkeys[] = 'nonFire';
        ////                    $varYlabels[] = 'nonFire';
        //
        //                    $varYkeys[] = 'biaya';
        //                    $varYlabels[] = 'biaya';
        //                    $varYkeys[] = 'hpp';
        //                    $varYlabels[] = 'hpp';
        //
        //                    $varAll['min'][] = min($specs);
        //                    $varAll['max'][] = max($specs);
        //
        //                    $varDatas[] = $specs;
        //                }
        //
        ////                foreach ($datas_1 as $bl => $datas_2) {
        ////
        ////                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
        ////
        ////                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
        ////                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
        ////
        //////                    $specs['totalBiaya'] = $biaya;
        //////                    $specs['totalHpp'] = $hpp;
        ////
        ////                    $specs['netto'] = ($totals-$hpp-$biaya);
        ////                    $specs['bruto'] = ($totals-$hpp);
        ////
        ////                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
        ////                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
        ////                    $specs['nilai_af'] = round($datas_2['nilai_af']);
        ////
        ////                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
        ////                    $thBl = "$th " . $namaBl;
        ////
        ////                    $specs['bulan'] = $thBl;
        ////
        ////                    $varYkeys = array();
        ////                    $varYlabels = array();
        ////
        ////
        ////                    $varYkeys[] = 'fire';
        ////                    $varYkeys[] = 'nonFire';
        ////                    $varYkeys[] = 'nilai_af';
        ////                    $varYkeys[] = 'bruto';
        ////                    $varYkeys[] = 'netto';
        ////
        //////                    $varYkeys[] = 'totalBiaya';
        //////                    $varYkeys[] = 'totalHpp';
        ////
        ////                    $varYlabels[] = 'fire';
        ////                    $varYlabels[] = 'nonFire';
        ////                    $varYlabels[] = 'total penjualan';
        ////                    $varYlabels[] = 'laba kotor';
        ////                    $varYlabels[] = 'laba bersih';
        //////                    $varYlabels[] = 'total BIAYA';
        //////                    $varYlabels[] = 'total HPP';
        ////
        //////                    foreach ($yKoloms as $yKolom => $yLabel) {
        //////                        $varYlabels[] = $yLabel;
        //////                        $varYkeys[] = $yKolom;
        //////                        $specs[$yKolom] = round($datas_2[$yKolom]);
        //////                    }
        ////
        ////                    $varAll['min'][] = min($specs);
        ////                    $varAll['max'][] = max($specs);
        ////
        ////                    $varDatas[] = $specs;
        ////                }
        //
        //            }
        //
        //
        //        arrPrint($content);
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                // cekHijau($xLabel);
                // cekHijau($xData);
                if ("tahun" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                }
                else {
                    $$xLabel = "[" . implode(",", $xData) . "]";
                }
            }
        }
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($tahun);

        // arrPrintHijau(json_decode($target_tahun_now)[0]);
        $penjualan_tahun_lalu = json_decode($penjualan_past)[0];
        $target_tahun_ini = json_decode($target_tahun_now)[0];
        $selisih_target = $target_tahun_ini - $penjualan_tahun_lalu;
        $persen_selisih = ($selisih_target / $penjualan_tahun_lalu) * 100;
        // cekHere("$selisih_target == $target_tahun_ini - $penjualan_tahun_lalu; $persen_selisih");

        $strData = "";
        // $strData .= "xxx";
        // $strData .= "<div class='nav-tabs-custom'>";
        // $strData .= "<div class='col-md-12'>";

        //        $strData .= "<div class='box box-solid box-success'>";
        //        $strData .= "<div class='box-header with-border'><h3>Monthly Sales Morris</h3></div>";
        //        $strData .= "<div class='box-body'>";
        //        $strData .= "<div class='dchart' id='bar-chart' style='pposition: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        //        $strData .= "</div>";
        //        $strData .= "<div id='legend' class='bars-legend'></div>";
        //        $strData .= "</div>"; // body
        //        $strData .= "</div>"; // box end
        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container'></div>";
        $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label 
<input type='button' id='target_save' value='0' disabled style='display: none;'></span></p>";
        $strData .= "<div class='range-slider hidden' id='target_slider'>
          <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>
        var pmtRange = document.getElementById('investment'),chart;
        function setData(chart) {
            var pmt = +pmtRange.value,
                target_persen = $target_persen,
                ranges = [];

            ranges[0] = pmt;
            
            chart.series[3].setData(ranges);
        }
        
        var chart = Highcharts.chart('container', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    // toggle: {
                    //     align: 'left',
                    //     y: 30,
                    //     x: 20,
                    //     height: 14,
                    //     theme: {
                    //         'stroke-width': 1,
                    //         stroke: 'silver',
                    //         r: 0
                    //     },
                    //     text: 'Chart Height',
                    //     menuItems: [{
                    //         text: '400px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 400)
                    //         }
                    //     }, {
                    //         text: '800px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 800)
                    //         }
                    //     }, {
                    //         text: '1200px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1200)
                    //         }
                    //     }, {
                    //         text: '1600px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1600)
                    //         }
                    //     }]
                    // }
                }
            },
          // chart: {
          //   type: \"xy\",
          //   height: 300,
          // },
          chart: {
                type: 'column'
            },
          title: {
            text: 'PENJUALAN',
            align: 'left'
          },
          subtitle: {
             text: \"<div class='text-bold' id='open_editor_target' title='klik untuk merubah'>Target Pertumbuhan <link>$target_persen%</link> dari $penjualan_past_label</div><br><div class='text-bold'>&nbsp;</div><br><div class='text-bold'> </div>\",
            align: 'left'
          },
          xAxis: [{
            categories: ['PENJUALAN DALAM MILYAR'],
            // crosshair: true
          }],
          yAxis: [
            { // Primary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[1]
              }
            },
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            // opposite: true
          },
          { // Secondary yAxis
//            type: 'linear',
//            max: ymax,
//            min: ymin,
            tickInterval: 5,
            gridLineWidth: 1,
            plotLines: [{
                // color: '#C0C0C0',
                width: 3,
                value: 0
            }],
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            },
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            }
          }],
          tooltip: {
            shared: true
          },
          plotOptions: {
                series: {
                    label: {
                        enabled: false,
                    }
                }
           },
          legend: {
            layout: 'vertical',
            align: 'right',
            x: 10,
            verticalAlign: 'top',
            y: 85,
            floating: true,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },                    
          series: [
              {
                name: $penjualan_past_label,
                // type: 'column',
                color: 'red',
                // yAxis: 1,
                // stacking: 'normal',
                data: $penjualan_past,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                    // inside: true,
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M',
                }
              },
              {
                name: $penjualan_now_label,
                // type: 'column',
                color: 'blue',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}<br>$persen_now% vs $penjualan_past_label<br>$date_now',
                     // rotation: -90,
                    // inside: true
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M'
                }
              },
              {
                name: $target_penjualan_now_label,
                // type: 'column',
                color: '#ccc',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $target_penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                     // rotation: -90,
                    // inside: true
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M'
                }
              },
              {
                name: $target_tahun_now_label,
                color: '#333',
                data: $target_tahun_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}<br>$target_persen%',
                }],
               tooltip: {
                    pointFormat:'<span style=\"color:{point . color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
     
          ],
          
          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  floating: false,
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  x: 0,
                  y: 0
                }
              }
            }]
          }
        });

        pmtRange.addEventListener('change', setData.bind(null, chart));

$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----


        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content"     => $strData,
                "add_link"    => "",
                "stop_time"   => "",
            ));

            $p->render();
        }
        else {
            echo $strData;
        }

        break;
    case "viewComparePerSales":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

        //        foreach ($navigasi as $keyNav => $valNav) {
        //            $labelNav = $navigasiAttr[$keyNav]["label"];
        //            $minimal = $navigasiAttr[$keyNav]["minimal"];
        //
        //            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                                    <label>$labelNav: </label>
        //                                    <input name='$keyNav' id='$keyNav' class='form-control' type='number' value='$valNav' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
        //                                </div>";
        //        }
        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

        // $content_nav .= form_button("cek", "show graph", "class='btn btn-info' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";
        //endregion

        /* ==============================
         * URUSAN CHART
         * ----------------------*/

        $kolomX = array(
            "nilai_af" => "netto",
        );
        $yKoloms = array(
            "nilai_af" => "total penjualan",
        );

        $varDatas = array();
        $varBiaya = array();
        $qtDatas = array();
        $qtBiaya = array();
        $varYlabels = array();
        $varYkeys = array();


        //        arrPrint($content);
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                // cekHijau($xLabel);
                // cekHijau($xData);
                if ("tahun" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                }
                else {
                    // $$xLabel = "[" . implode(",", $xData) . "]";
                    $$xLabel = json_encode($xData);
                    // $$xLabel = str_replace("\"","'", str_replace("}","]",str_replace("{","[",json_encode($xData))));
                }
            }
        }
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($tahun);

        // arrPrintHijau(json_decode($target_tahun_now)[0]);
        // $penjualan_tahun_lalu = json_decode($penjualan_past)[0];
        // $target_tahun_ini = json_decode($target_tahun_now)[0];
        // $selisih_target = $target_tahun_ini - $penjualan_tahun_lalu;
        // $persen_selisih = ($selisih_target / $penjualan_tahun_lalu) * 100;
        // cekHere("$selisih_target == $target_tahun_ini - $penjualan_tahun_lalu; $persen_selisih");

        $strData = "";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container'></div>";
        $strData .= "<p class='highcharts-description'></p>";

        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);


        // arrPrintPink($penjualan_now);
        // arrPrintPink(json_decode($penjualan_now));
        $date_now = dtimeNow('d M');
        $strData .= "<script>
           
        var chart = Highcharts.chart('container', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: false
                    },
                    toggle: {
                        align: 'left',
                        y: 30,
                        x: 20,
                        height: 14,
                        theme: {
                            'stroke-width': 1,
                            stroke: 'silver',
                            r: 0
                        },
                        text: 'Chart Height',
                        menuItems: [{
                            text: '400px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 400)
                            }
                        }, {
                            text: '800px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 800)
                            }
                        }, {
                            text: '1200px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1200)
                            }
                        }, {
                            text: '1600px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1600)
                            }
                        }]
                    }
                }
            },
          chart: {
            type: \"xy\",
            height: 700,
                        
            events: {
              load: function() {
                var yData = this.series[1].yData;
                const sum = yData.reduce((a, b) => a + b);
            
                this.yAxis[0].addPlotLine({
                  value: sum / yData.length,
                  // value: 600000000,
                  color: 'red',
                  dashStyle: 'longdash',
                  label: {
                    text: 'rata-rata penjualan',
                     style: {
                                        
                    	color: 'red',
                    	
                    }
                  }
                });
              }
            }
          },
          // chart: {
          //       type: 'column'
          //   },
          title: {
            text: 'PENJUALAN',
            align: 'left'
          },
          subtitle: {
             text: \"<div class='text-bold' id='open_editor_target' title='klik untuk merubah'>Target Pertumbuhan <link>$target_persen%</link> dari $penjualan_past_label</div><br><div class='text-bold'>&nbsp;</div><br><div class='text-bold'> </div>\",
            align: 'left'
          },
          xAxis: {
                type: 'category',
                labels: {
                        rotation: -45,
                        style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
        }
          },
          yAxis: [
            { // Primary yAxis
                labels: {
                  format: '{text}',
                  style: {
                    color: Highcharts.getOptions().colors[1]
                  }
                },
                title: {
                  text: '',
                  style: {
                    color: Highcharts.getOptions().colors[0]
                  }
                },
                // opposite: true
            },
            { // Secondary yAxis
    //            type: 'linear',
    //            max: ymax,
    //            min: ymin,
                tickInterval: 1000,
                gridLineWidth: 1,
                plotLines: [{
                    // color: '#C0C0C0',
                    width: 3,
                    value: 100000,
                    color: 'green',
                    dashStyle: 'shortdash',

                }],
                title: {
                  text: '',
                  style: {
                    color: Highcharts.getOptions().colors[0]
                  }
                },
                labels: {
                  format: '{value}',
                  style: {
                    color: Highcharts.getOptions().colors[0]
                  }
                }
            },
            // plotLines: [{
            //     value: minRate,
            //     color: 'green',
            //     dashStyle: 'shortdash',
            //     width: 2,
            //     label: {
            //         text: 'Last quarter minimum'
            //     }
            // }, {
            //     value: maxRate,
            //     color: 'red',
            //     dashStyle: 'shortdash',
            //     width: 2,
            //     label: {
            //         text: 'Last quarter maximum'
            //     }
            // }]
          ],
          tooltip: {
            shared: true
          },
          plotOptions: {
                series: {
                    label: {
                        enabled: false,
                    }
                }
           },
          legend: {
            layout: 'vertical',
            align: 'right',
            x: 10,
            verticalAlign: 'top',
            y: 85,
            floating: true,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },                    
          series: [
              {
                name: $penjualan_past_label,
                type: 'column',
                color: 'red',
                // yAxis: 1,
                // stacking: 'normal',
                data: $penjualan_past,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
              {
                name: $penjualan_now_label,
                type: 'column',
                color: 'blue',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                     // rotation: -90,
                    // inside: true
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M'
                }
              },
               {
                name: $target_penjualan_now_label,
                type: 'column',
                color: '#ccc',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $target_penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                     // rotation: -90,
                    // inside: true
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
              {
                name: $target_tahun_now_label,
                type: 'column',
                color: '#333',
                data: $target_tahun_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}<br>$target_persen%',
                }],
               tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
          ],
          
          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  floating: false,
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  x: 0,
                  y: 0
                }
              }
            }]
          }
        });



$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----


        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content"     => $strData,
                "add_link"    => "",
                "stop_time"   => "",
            ));

            $p->render();
        }
        else {
            echo $strData;
        }

        break;
}