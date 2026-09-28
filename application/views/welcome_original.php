<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

switch ($mode) {


    case "welcome":


        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/home.html");

        $strOnprog = "";

        //region onprogress

        if (sizeof($dataProposals) > 0) {
            $strOnprog .= "<div class='table-responsive'>";
            $strOnprog .= "<table class='table table-condensed no-padding no-border'>";

            foreach ($dataProposals as $pSpec) {
                // print_r($val);

                $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                foreach ($pSpec as $key => $value) {
                    $strOnprog .= "<td class='text-muted'><small>";
                    $strOnprog .= $key;
                    $strOnprog .= "</small></td>";
                }
                $strOnprog .= "</tr>";

                $strOnprog .= "<tr>";
                foreach ($pSpec as $key => $value) {
                    $strOnprog .= "<td>";
                    $strOnprog .= formatField($key, $value);
                    $strOnprog .= "</td>";
                }
                $strOnprog .= "</tr>";

                //                if (sizeof($arrayProgressLabels[$trID]) > 0) {
                //                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                //                    foreach ($arrayProgressLabels[$trID] as $key => $label) {
                //                        $strOnprog .= "<td class='text-muted'><small>";
                //                        $strOnprog .= $label;
                //                        $strOnprog .= "</small></td>";
                //                    }
                //                    $strOnprog .= "</tr>";
                //                }
                //                $strOnprog .= "<tr>";
                //                if (sizeof($arrayProgressLabels[$trID]) > 0) {
                //                    foreach ($arrayProgressLabels[$trID] as $key => $label) {
                //                        $strOnprog .= "<td>";
                //                        $strOnprog .= $val[$key];
                //                        $strOnprog .= "</td>";
                //                    }
                //                }
                //                $strOnprog .= "</tr>";
            }

            $strOnprog .= "</table>";
            $strOnprog .= "</div class='table-responsive'>";
            //            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
            $strOnprogFooter = "";
        }
        //endregion

        //region onprogress
        if (sizeof($arrayOnProgress) > 0) {
            $strOnprog .= "<div class='table-responsive'>";
            $strOnprog .= "<table class='table table-condensed no-padding'>";


            foreach ($arrayOnProgress as $trID => $val) {
                //                 arrPrint($val);

                if (sizeof($arrayProgressLabels[$trID]) > 0) {
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    foreach ($arrayProgressLabels[$trID] as $key => $label) {
                        $strOnprog .= "<td class='text-muted'><small>";
                        $strOnprog .= $label;
                        $strOnprog .= "</small></td>";
                    }
                    $strOnprog .= "</tr>";
                }

                $strOnprog .= "<tr>";
                if (sizeof($arrayProgressLabels[$trID]) > 0) {
                    foreach ($arrayProgressLabels[$trID] as $key => $label) {
                        $strOnprog .= "<td>";
                        $strOnprog .= $val[$key];
                        $strOnprog .= "</td>";
                    }
                }
                $strOnprog .= "</tr>";
            }

            $strOnprog .= "</table>";
            $strOnprog .= "</div class='table-responsive'>";
            //            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
            $strOnprogFooter = "";
        }
        //endregion


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<div class='table-responsive tbl_welcome_history'>";
            $strHist .= "<table id='welcome_history' class='table table-condensed no-padding no-border'>";
            $strHist .= "<thead>";
            $strHist .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<th class='text-muted'>";
                    if (is_array($label)) {
                        $strHist .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strHist .= $label;
                    }
                    $strHist .= "</th>";
                }
            }
            $strHist .= "</tr>";
            $strHist .= "</thead>";
            $strHist .= "<tbody>";
            foreach ($arrayHistory as $key => $val) {
                $strHist .= "<tr>";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $tmp = isset($val[$key]) ? $val[$key] : "";
                        $strHist .= $tmp;
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }
            $strHist .= "</tbody>";
            $strHist .= "</table>";
            $strHist .= "</div class='table-responsive'>";

            $strHist .= "<script>
                    $(document).ready( function(){
                        var table = $('#welcome_history').DataTable({
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

                            buttons: [],

                            });


                        //new $.fn.dataTable.FixedHeader( table );
                        $('.table-responsive.tbl_welcome_history').floatingScroll();
                        $('.table-responsive.tbl_welcome_history').scroll(function() {
                            setTimeout(function () {
                                $('#welcome_history').DataTable().fixedHeader.adjust();
                            }, 100);
                        });
                    });
                    </script>";


            $strHistFooter = "";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }

        $strRecap = "";
        $recapTitle = "";
        if (sizeof($videos) > 0) {
            $recapTitle = "Tutorial Videos";
            $strRecap .= "<div class='rrow no-padding'>";
            $vCtr = 0;

            foreach ($videos as $kategori => $itemVideos) {
                $strRecap .= "<div class='no-padding col-md-4'>";
                $strRecap .= "<h4 class='no-padding no-margin text-uppercase text-success'>$kategori</h4>";
                $strRecap .= "<ul class='list-group'>";
                foreach ($itemVideos as $url => $label) {
                    $vCtr++;

                    $strRecap .= "<a class='list-group-item' style='border: none;;' href='javascript:void(0)' data-toggle='tooltip' data-placement='top' title='click to see video'
                                    onclick=\"BootstrapDialog.show({
                                            title:'$label',
                                            message: $('<div></div>').load('" . base_url() . "Embed/embed/?e=" . blobEncode($url) . "&l=" . blobEncode($label) . "'),
                                            size: BootstrapDialog.SIZE_WIDE,
                                            type: BootstrapDialog.TYPE_INFO,
                                            draggable:true,
                                            closable:true,
                                            buttons: [{
                                                       label: 'Close',
                                                        cssClass: 'btn-primary pull-left',
                                                        title: 'close',
                                                        action: function(dialogItself){
                                                            dialogItself.close();}
                                                        }],
                                        });\"
                                >";

                    $strRecap .= "<i class='fa fa-video-camera blink text-red'></i> ";
                    $strRecap .= "$label";
                    $strRecap .= "</a>";

                }
                $strRecap .= "</ul'>";
                $strRecap .= "</div>";
            }

            $strRecap .= "</div>";


        }
        //endregion

        if (sizeof($arrayOnProgress) > 0 || sizeof($dataProposals) > 0) {
            $propDisplay = "block";
            $altDisplay = "none";
        }
        else {
            $propDisplay = "none";
            $altDisplay = "block";
        }

        // arrPrint(my_memberships());
        $show_dashboard = false;
        $show_dashboard_produksi = false;
        // if (in_array("c_holding", my_memberships())) {
        if (in_array("c_owner", my_memberships())) {
            $show_dashboard = true;
            // $show_dashboard = false;
        }
        elseif (in_array("c_finance", my_memberships())) {
            // $show_dashboard = true;
            $show_dashboard = false;
        }
        elseif (in_array("o_finance", my_memberships())) {
            $show_dashboard = false;
        }
        if (in_array("p_produksi_spv", my_memberships())) {
            // cekHijau(__LINE__);
            $show_dashboard = false;
            $show_dashboard_produksi = true;
        }
        $script_bottom = "";
        $script_bottom = "<script>";
        /* ---------------------------------------------------
         * TO DO LIST by user_id
         * ---------------------------------------------------*/
        $allowed_id = array(
            "17",
            "316"
        );
        if (in_array(my_id(), $allowed_id)) {
            $link_todolist = base_url() . "dashboard/Todolist/viewTodolistTransaksi";
            $script_bottom .= "$(\"#todolist\").load(\"$link_todolist\");";
        }

        /*before opname*/
        // $script_bottom .= isset($notif_opname) ? $notif_opname : "";

        if ($show_dashboard == true) {

            $script_bottom .= "function loadDashboad(){ \n";
            /*before opname*/
            $script_bottom .= isset($notif_opname) ? $notif_opname : "";

            // $link_load = base_url() . "dashboard/Graph/viewSummary";
            $link_load = base_url() . "dashboard/Graph/viewSummary_2";
            $script_bottom .= "$(\"#summary_indeks\").load(\"$link_load\");";

            $link_graph = base_url() . "dashboard/Graph/viewGraphSales";
            $script_bottom .= "$(\"#graph\").load(\"$link_graph\");";

            $link_graph_penjualan = base_url() . "dashboard/Graph/viewCompareSales";
            $script_bottom .= "$(\"#graph_penjualan\").load(\"$link_graph_penjualan\");";

            $link_rasio = base_url() . "dashboard/Rasio/viewRekening";
            $script_bottom .= "$(\"#rasio_indeks\").load(\"$link_rasio\");";
            // $link_sales_pie = base_url() . "dashboard/Graph/viewSales";
            // $script_bottom .= "$(\"#sales_pie\").load(\"$link_sales_pie\");";

            /*berdasar data pada tahun yg dipilih*/
            $link_sales_donut = base_url() . "dashboard/Graph/viewSalesD";
            $script_bottom .= "$(\"#sales_donut\").load(\"$link_sales_donut\");";
            $link_sales_donut = base_url() . "dashboard/Graph/viewSalesDPast";
            $script_bottom .= "$(\"#sales_donut_past\").load(\"$link_sales_donut\");";
            $link_sales_donut = base_url() . "dashboard/Graph/viewSalesDttm";
            $script_bottom .= "$(\"#sales_donut_ttm\").load(\"$link_sales_donut\");";

            /*SCATTER -------------------------------------------------------------------------------------------*/
            $link_sebaran = base_url() . "dashboard/Graph/viewSebaran";
            $script_bottom .= "$(\"#margin\").load(\"$link_sebaran\");";

            $link_sebaran_pertumbuhan = base_url() . "dashboard/Graph/viewSebaranLajuPenjualan";
            $script_bottom .= "$(\"#pertumbuhan\").load(\"$link_sebaran_pertumbuhan\");";

            /* ------------------------------------------------------------------
             * PABILA OPNAME SUDAH MULAI TODOLIST AKAN DIREPLACE OLEH LINK INI
             * ------------------------------------------------------------------*/
            if ($view_opname != false) {
                $link_todolist = $view_opname;
                $script_bottom .= "$(\"#todolist\").load(\"$link_todolist\");";
            }

            if (ipadd() == "202.65.117.72") {

            } //--------------------ip

            // $link_kurs_bi = base_url() . "Kurs/index";
            $link_kurs_bi = base_url() . "Kurs/index_bouncing";
            $script_bottom .= "setTimeout( function() { $(\"#best_salesman\").load(\"$link_kurs_bi\") }, 4000);";

            $script_bottom .= "}\n";

            $script_bottom .= "
                document.addEventListener('DOMContentLoaded', function(event) {
                    loadDashboad();
                });
            ";

        }

        // cekLime(ipadd() . $show_dashboard_produksi);
        /*---PRODUKSI----*/
        // $show_dashboard_produksi = true;
        if ($show_dashboard_produksi == true) {
            $link_graph_penjualan = base_url() . "dashboard/Graph/viewEfisiensiBomThn";
            // $script_bottom .= "$(\"#graph_produksi\").load(\"$link_graph_penjualan\");";
            $script_bottom .= "$(\"#graph_produksi\").append($(\"<div/>\").load(\"$link_graph_penjualan\"));";

            $link_graph = base_url() . "dashboard/Graph/viewEfisiensiBomBlnan";
            // $script_bottom .= "$(\"#graph_pro\").load(\"$link_graph\");";
            $script_bottom .= "$(\"#graph_pro\").append($(\"<div/>\").load(\"$link_graph\"));";

            $link_graph_efisiensi_thn = base_url() . "dashboard/Graph/viewMultyEfisiensiBomThn?kb=2";
            $script_bottom .= "$(\"#graph_produksi\").append($(\"<div/>\").load(\"$link_graph_efisiensi_thn\"));";

            $link_graph_efisiensi = base_url() . "dashboard/Graph/viewMultyEfisiensiBomBlnan?kb=2";
            $script_bottom .= "$(\"#graph_pro\").append($(\"<div/>\").load(\"$link_graph_efisiensi\"));";
            // ---------------------
            $link_graph_efisiensi_thn = base_url() . "dashboard/Graph/viewMultyEfisiensiBomThn?kb=1";
            $script_bottom .= "$(\"#graph_produksi2\").append($(\"<div/>\").load(\"$link_graph_efisiensi_thn\"));";

            $link_graph_efisiensi = base_url() . "dashboard/Graph/viewMultyEfisiensiBomBlnan?kb=1";
            $script_bottom .= "$(\"#graph_pro2\").append($(\"<div/>\").load(\"$link_graph_efisiensi\"));";

            $link_graph_efisiensi_thn = base_url() . "dashboard/Graph/viewMultyEfisiensiBomThn?kb=4";
            $script_bottom .= "$(\"#graph_produksi2\").append($(\"<div/>\").load(\"$link_graph_efisiensi_thn\"));";

            $link_graph_efisiensi = base_url() . "dashboard/Graph/viewMultyEfisiensiBomBlnan?kb=4";
            $script_bottom .= "$(\"#graph_pro2\").append($(\"<div/>\").load(\"$link_graph_efisiensi\"));";

            $link_graph_efisiensi_thn = base_url() . "dashboard/Graph/viewMultyEfisiensiBomThn?kb=777";
            $script_bottom .= "$(\"#graph_produksi2\").append($(\"<div/>\").load(\"$link_graph_efisiensi_thn\"));";

            $link_graph_efisiensi = base_url() . "dashboard/Graph/viewMultyEfisiensiBomBlnan?kb=777";
            $script_bottom .= "$(\"#graph_pro2\").append($(\"<div/>\").load(\"$link_graph_efisiensi\"));";

            if (ipadd() == "202.65.117.72") {
                // $link_graph_penjualan = base_url() . "dashboard/Graph/viewEfisiensiBomBln";
                // $script_bottom .= "$(\"#graph_penjualan\").load(\"$link_graph_penjualan\");";
            }
            else {

            }
        }

        $script_bottom .= "</script>";

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu"         => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "alt_display" => $altDisplay,
            "prop_display" => $propDisplay,
            "onprogress_title" => $onprogressTitle,
            "onprogress_content" => $strOnprog,
            "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",
            "add_link" => "",
            "history_title" => $historyTitle,
            "history_content" => $strHist,
            "history_footer" => $strHistFooter,
            "profile_name" => $this->session->login['nama'],
            "recap_title" => $recapTitle,
            "recap_content" => $strRecap,
            "recap_footer" => "",
            "stop_time" => "",
            "script_bottom" => $script_bottom,
        ));

        $p->render();


        break;


}