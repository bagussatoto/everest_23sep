<?php
//cekHere($mode);
switch ($mode) {

    case "viewBalanceSheet":

        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");


        $no = 0;


        if (sizeof($items) > 0) {
            $str = "<div class='table-responsive viewBalanceSheet'>";
            $str .= "<table id='viewBalanceSheet' class='table display'>";
            $str .= "<thead>";
            $str .= "<tr bgcolor='#e5e5e5' class='text-center text-uppercase'>";
            $str .= "<td>";
            $str .= "no";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";
            $str .= "</thead>";
            $str .= "<tbody>";

            foreach ($items as $key => $iSpec) {

                $no++;
                $str .= "<tr class='text-capitalize'>";

                $str .= "<td align='right'>";
                $str .= "$no";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";

//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);

                    $str .= "</td>";
                }

                $str .= "</tr>";

            }

            $str .= "<tr bgcolor='#e5e5e5' class='text-uppercase'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }

            $str .= "</tr>";
            $str .= "</tbody>";

            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>
                    $(document).ready( function(){

                        var table = $('#viewBalanceSheet').DataTable({
                                        dom: 'lBfrtip',
                                        fixedHeader: true,
                                        lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                        pageLength: -1,
                                        ordering: false,
                                        lengthChange: false,
                                        info: false,
                                        paging: false,
                                        buttons: [],
                                });


                                $('.table-responsive.viewBalanceSheet').floatingScroll();
                                $('.table-responsive.viewBalanceSheet').scroll(
                                    delay_v2(function () {
                                        $('#viewBalanceSheet').DataTable().fixedHeader.adjust();
                                    }, 200)
                                );
                            });

                    </script>";

            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "'");
                $kdit = number_format($totals['kredit'], "10", ".", "'");
                $crossBalance = $totals['debet'] - $totals['kredit'];
                $crossBalance = number_format($crossBalance, "10", ".", "'");

                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);

        $p->render();
        break;

    case "viewBalanceSheetBulanan":

        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");

        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select month </span>";
        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";


        $no = 0;
        if (sizeof($items) > 0) {
            $str .= "<table class='table table-condensed table-bordered table-hover no-margin'>";
            $str .= "<tr bgcolor='#e5e5e5' class='text-center text-uppercase'>";
            $str .= "<td>";
            $str .= "no";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";

            foreach ($items as $key => $iSpec) {

                $no++;
                $str .= "<tr class='text-capitalize'>";

                $str .= "<td align='right'>";
                $str .= "$no";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";

//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);

                    $str .= "</td>";
                }

                $str .= "</tr>";

            }

            $str .= "<tr bgcolor='#e5e5e5' class='text-uppercase'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }

            $str .= "</tr>";
            $str .= "</table>";

            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "'");
                $kdit = number_format($totals['kredit'], "10", ".", "'");
                $crossBalance = $totals['debet'] - $totals['kredit'];
                $crossBalance = number_format($crossBalance, "10", ".", "'");

//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
//        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;

    case "viewBalanceSheetMonthly":

        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");

        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on'>Pilih Periode </span>";
        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m", strtotime('-1 month')) . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";


        $no = 0;
        if (sizeof($items) > 0) {
            $str .= "<table class='table table-condensed table-bordered table-hover no-margin'>";
            $str .= "<tr bgcolor='#e5e5e5' class='text-center text-uppercase'>";
            $str .= "<td>";
            $str .= "no";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";

            foreach ($items as $key => $iSpec) {

                $no++;
                $str .= "<tr class='text-capitalize'>";

                $str .= "<td align='right'>";
                $str .= "$no";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";

//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);

                    $str .= "</td>";
                }

                $str .= "</tr>";

            }

            $str .= "<tr bgcolor='#e5e5e5' class='text-uppercase'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }

            $str .= "</tr>";
            $str .= "</table>";

            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "'");
                $kdit = number_format($totals['kredit'], "10", ".", "'");
                $crossBalance = $totals['debet'] - $totals['kredit'];
                $crossBalance = number_format($crossBalance, "10", ".", "'");

//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
//        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;
    case "viewBalanceSheetMonthToDate":

        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");

        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select month </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";


        $no = 0;
        if (sizeof($items) > 0) {
            $str .= "<table class='table table-condensed table-bordered table-hover no-margin'>";
            $str .= "<tr bgcolor='#e5e5e5' class='text-center text-uppercase'>";
            $str .= "<td>";
            $str .= "no";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";

            foreach ($items as $key => $iSpec) {

                $no++;
                $str .= "<tr class='text-capitalize'>";

                $str .= "<td align='right'>";
                $str .= "$no";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";

//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);

                    $str .= "</td>";
                }

                $str .= "</tr>";

            }

            $str .= "<tr bgcolor='#e5e5e5' class='text-uppercase'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }

            $str .= "</tr>";
            $str .= "</table>";

            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "'");
                $kdit = number_format($totals['kredit'], "10", ".", "'");
                $crossBalance = $totals['debet'] - $totals['kredit'];
                $crossBalance = number_format($crossBalance, "10", ".", "'");

//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
//        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;

    case "viewBalanceSheetYearly":

        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");

        $str = "";
        $str .= "
            <style>
                .ui-datepicker-calendar {
                   display: none;
                }
                .ui-datepicker-month {
                   display: none;
                }
                .ui-datepicker-next,.ui-datepicker-prev {
                  display:none;
                }
            </style>";

        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on'> pilih periode </span>";
        $str .= "<input readonly type='year' id='yearly' value='$defaultDate' min='$oldDate' max='" . date("Y", strtotime('-1 year')) . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        $str .= "
        <script>
            $(function() {
                $('#yearly').datepicker({
                    changeYear: true,
                    showButtonPanel: true,
                    yearRange: '2019:" . date("Y", strtotime('-1 year')) . "',
                    dateFormat: 'yy',
//                    minDate: '$oldDate',
//                    maxDate: 'D',
                    onClose: function(dateText, inst) { 
                        var year = $(\"#ui-datepicker-div .ui-datepicker-year :selected\").val();
                        $(this).datepicker('setDate', new Date(year, 1));
                        if(this.value!='' && $defaultDate!=this.value){
                            location.href='$thisPage?date='+this.value
                        }
                        
                    }
                });
                $(\".date-picker-year\").focus(function () {
                    $(\".ui-datepicker-month\").hide();
                });
            });
        </script>";


        $no = 0;
        if (sizeof($items) > 0) {
            $str .= "<table class='table table-condensed table-bordered table-hover no-margin'>";
            $str .= "<tr bgcolor='#e5e5e5' class='text-center text-uppercase'>";
            $str .= "<td>";
            $str .= "no";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";

            foreach ($items as $key => $iSpec) {

                $no++;
                $str .= "<tr class='text-capitalize'>";

                $str .= "<td align='right'>";
                $str .= "$no";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";

//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);

                    $str .= "</td>";
                }

                $str .= "</tr>";

            }

            $str .= "<tr bgcolor='#e5e5e5' class='text-uppercase'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }

            $str .= "</tr>";
            $str .= "</table>";

            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "'");
                $kdit = number_format($totals['kredit'], "10", ".", "'");
                $crossBalance = $totals['debet'] - $totals['kredit'];
                $crossBalance = number_format($crossBalance, "10", ".", "'");

//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
//        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;
    case "viewBalanceSheetYearToDate":

        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");

        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select month </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";


        $no = 0;
        if (sizeof($items) > 0) {
            $str .= "<table class='table table-condensed table-bordered table-hover no-margin'>";
            $str .= "<tr bgcolor='#e5e5e5' class='text-center text-uppercase'>";
            $str .= "<td>";
            $str .= "no";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";

            foreach ($items as $key => $iSpec) {

                $no++;
                $str .= "<tr class='text-capitalize'>";

                $str .= "<td align='right'>";
                $str .= "$no";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";

//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);

                    $str .= "</td>";
                }

                $str .= "</tr>";

            }

            $str .= "<tr bgcolor='#e5e5e5' class='text-uppercase'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";

            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }

            $str .= "</tr>";
            $str .= "</table>";

            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "'");
                $kdit = number_format($totals['kredit'], "10", ".", "'");
                $crossBalance = $totals['debet'] - $totals['kredit'];
                $crossBalance = number_format($crossBalance, "10", ".", "'");

//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
//        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;

    case "viewBalanceSheetTmp":
//        arrPrint($items);
        $contens = "";
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
//        $p = New Layout("$title", "$subTitle", "application/template/default.html");

//        $template = array(
//            'table_open'        => '<table id="table" class="table table-bordered tabled-condensed" style=\'table-layout: fixed; width: 100%; \'>',
//            'thead_open'        => '<thead class="bg-info text-uppercase" style="text-align: center;">',
//            'thead_close'       => '</thead>',
//            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
//            'footer_row_start'  => '<tr>',
//            'footer_row_end'    => '</tr>',
//            'footer_cell_start' => '<th>',
//            'footer_cell_end'   => '</th>',
//            'tfoot_close'       => '</tfoot>',
//            'table_close'       => '</table>',
//        );
//        $this->table->set_template($template);
//
//        $header = array('data' => "No", 'class' => 'text-center');
//        $header_f = array();
//        $header_f[] = $header;


        $no = 0;


        if (sizeof($items) > 0) {
            $str = "<table class='table table-condensed table-bordered'>";
            $str .= "<tr bgcolor='#e5e5e5'>";
            $str .= "<td align='right'>";
            $str .= "no.";
            $str .= "</td>";
            foreach ($array_header as $key => $label) {
                $str .= "<td>";
                $str .= $label;
                $str .= "</td>";
            }
            $str .= "</tr>";
            foreach ($items as $key => $iSpec) {

                $no++;

                $str .= "<tr>";

                $str .= "<td align='right'>";
                $str .= "$no.";
                $str .= "</td>";

                $linkMutasi = $inspectTarget_mutasi . "Rekening/" . $iSpec['rekening'];

                foreach ($array_header as $key => $label) {
                    $rekName = $iSpec['rekening'];
                    $linkDet = isset($accountChilds["$rekName"]) ? $inspectTarget_rincian . $accountChilds["$rekName"] . "/" . str_replace(" ", "%20", $rekName) : null;
                    $strLinkDet = isset($accountChilds[$iSpec['rekening']]) ? $linkDet : "";
                    $strLinkMutasi = "&nbsp;<a href='$linkMutasi'><span class='text-muted fa fa-clock-o'></span></a>";
                    $str .= "<td>";
//                    if($linkDet!=null){
//                        $str .= "<a href=$strLinkDet>".$iSpec[$key]."</a>";
//                    }else{
//                        $str .= $iSpec[$key];
//                    }
//
//                    $str.= "<span class='pull-right'>$strLinkMutasi</span>";

                    $str .= formatField($key, $iSpec[$key]);
//                    $str .= $iSpec[$key];
                    $str .= "</td>";
                }

                $str .= "</tr>";

            }
            $str .= "<tr bgcolor='#e5e5e5'>";
            $str .= "<td>";
            $str .= "";
            $str .= "</td>";
            foreach ($array_header as $key => $label) {
                $str .= "<td style='font-size:1.5em'>";

                $str .= isset($totals[$key]) ? formatField($key, $totals[$key]) : "";
//                $str .= isset($totals[$key]) ? $totals[$key] : "0";
                $str .= "</td>";
            }
            $str .= "</tr>";
            $str .= "</table>";
            if (show_debuger() == 1) {

                $dbet = number_format($totals['debet'], "10", ".", "");
                $kdit = number_format($totals['kredit'], "10", ".", "");
                $crossBalance = $dbet - $kdit;
                $crossBalance = number_format($crossBalance, "10", ".", "");

//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                $str .= "<div class='text-red tex-miring'>" . $crossBalance . " = " . $dbet . " - " . $kdit . "</div>";
            }

        }
        else {
            $str = 'tidak ada data';
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
        //  endregion menu left

        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
//        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;

    case "viewNeraca":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        // $linkExcel = base_url(). "ExcelWriter/neraca";
        $excel_data = "date=$defaultDate";
        $excel_name = "neraca $defaultDate";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        if (isset($dateSelector) && ($dateSelector == true)) {

            $str .= "<span class='input-group-add-on' >select month </span>";
            $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        }
        if (isset($linkExcel)) {

            $str .= "&nbsp; <button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
            $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
        }
        if (isset($buttonMode) && ($buttonMode['enabled'] == true)) {
            $btn_label = $buttonMode['label'];
            $btn_link = $buttonMode['link'];
            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"location.href='$btn_link?$grMenu" . "n=1'\" title='download data'><i class='fa fa-bookmark-o'></i> $btn_label</button>";
        }

        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";
        if (sizeof($categories) > 0) {

            foreach ($headers as $key => $label) {
                $totals[$key] = 0;
            }
            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            $str .= "<thead>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($headers as $key => $label) {
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<th>$label</th>";
                }
                else {
                    $isExist_href = "<th>" . $label;
                    $isExist_href .= "</th>";
                }

                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</thead>";

            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($headers as $key => $label) {
                    $subTotals[$key] = 0;
                }
                if (isset($rekeningsName[$catName]) && sizeof($rekeningsName[$catName]) > 0) {
                    foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                        if (($rekenings[$catName][$rekName]['debet'] > 0) && ($rekenings[$catName][$rekName]['kredit'] > 0)) {
                            $val_detail = $rekenings[$catName][$rekName]['debet'] - $rekenings[$catName][$rekName]['kredit'];
                            if ($val_detail > 0) {
                                $rekenings[$catName][$rekName]['debet'] = $val_detail;
                                $rekenings[$catName][$rekName]['kredit'] = 0;
                            }
                            else {
                                $rekenings[$catName][$rekName]['debet'] = 0;
                                $rekenings[$catName][$rekName]['kredit'] = $val_detail * -1;
                            }
                        }
                        $rek = $rekName;
                        $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                        $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                        $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                        $str .= "<tr>";
                        $str .= "<td column='consulente:'>$catName</td>";
                        $str .= "<td column='$catName:'><span data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rekNameAlias</span></td>";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                            }
                            else {
                                if (isset($values[$key])) {
                                    if (is_numeric($values[$key])) {
                                        if ($values[$key] >= 0) {
                                            $rVal = number_format($values[$key] * 1, "0", ".", ",");
                                            $style = "style='text-align:right;'";
                                        }
                                        else {
                                            $rVal = "<r>(" . number_format($values[$key] * -1, "0", ".", ",") . ")</r>";
                                            $style = "style='text-align:right;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $values[$key]);
                                        $style = "";
                                    }
                                }
                                else {
                                    $rVal = "";
                                    $style = "";
                                }
                                $isExist_href = "<td $style>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $str .= "$isExist_href";

                            if (array_key_exists($key, $totals)) {
                                $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
                                $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;

                            }
                        }
                        $str .= "</tr>";

                    }

//                    $str .= "<tr style='background-color:#f0f0f0;'>";
//                    $str .= "<td></td>";
//                    $str .= "<td></td>";
//                    foreach ($headers as $key => $label) {
//                        $coLStr = isset($subTotals[$key]) ? $subTotals[$key] : "";
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            if (is_numeric($coLStr)) {
//                                if ($coLStr >= 0) {
//                                    $rVal = number_format($coLStr*1, "0", ".", ",");
//                                    $style = "style='font-size:1.3em;text-align:right;'";
//                                }
//                                else {
//                                    $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
//                                    $style = "style='font-size:1.3em;text-align:right;'";
//                                }
//                            }
//                            else {
//                                $rVal = formatField($key, $coLStr);
//                                $style = "style='font-size:1.3em;text-align:right;'";
//                            }
//                            $isExist_href = "<td $style>" . $rVal;
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
//                    }
//                    $str .= "</tr>";
//
//                    $str .= "<tr>";
//
//                    foreach ($headers as $key => $label) {
//                        $str .= "<td style='font-size:1em;'></td>";
//                        }
//
//                    $str .= "</tr>";
                }
            }
            $str .= "</tbody>";

            $str .= "<tfoot>";
            $str .= "<tr bgcolor='#e5e5e5'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($headers as $key => $label) {
                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<th style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</th>";
                }
                else {
                    $isExist_href = "<th style='font-size:1.3em;'>" . formatField($key, $coLStr);
                    $isExist_href .= "</th>";
                }
                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</tfoot>";

            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip',
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            if (show_debuger() == 1) {
                $dbet = $totals['debet'];
                $kdit = $totals['kredit'];
                $crossBalance = $dbet - $kdit;

                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
            }
        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewNeracaTahunan":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select year ";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= $pilih_tahun;
        $str .= "</span>";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
//                "rek_id" => 0,
                "debet" => 0,
                "kredit" => 0
            );
            $str .= "<table class='table table-condensed table-bordered'>";
            foreach ($categories as $catName) {
                $subTotals = array(
//                    "rek_id" => 0,
                    "debet" => 0,
                    "kredit" => 0
                );


                $str .= "<tr bgcolor='#f0f0f0'>";
                $headers['rekening'] = $catName;//==override header label paling kiri
                foreach ($headers as $key => $label) {
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";

                    }
                    else {

                        $isExist_href = "<td>" . $label;
                        $isExist_href .= "</td>";

                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";

                if (isset($rekenings[$catName]) && sizeof($rekenings[$catName]) > 0) {
                    foreach ($rekenings[$catName] as $rek => $values) {
                        $str .= "<tr>";
                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                                $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";

                            }
                            else {
                                if (is_numeric($values[$key])) {
                                    if ($values[$key] >= 0) {
//                                        cekHere("ada normal");
                                        $rVal = formatField($key, $values[$key]);
                                        $style = "style='text-align:right;'";
                                    }
                                    else {
//                                        $rVal = formatField($key, $values[$key]*-1);
//                                        cekHere("ada minusnya...");
                                        $rVal = "(" . number_format($values[$key] * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;'";
                                    }
                                }
                                else {
//                                    cekHere("ada");
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "";
                                }
//                                $isExist_href = "<td>" . formatField($key, $values[$key]);
                                $isExist_href = "<td $style>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $str .= "$isExist_href";
//                            if (is_numeric($values[$key])) {
                            if (array_key_exists($key, $totals)) {
                                $totals[$key] += $values[$key];
                                $subTotals[$key] += $values[$key];

                            }
                        }
                        $str .= "</tr>";

                    }

                    $str .= "<tr>";
                    foreach ($headers as $key => $label) {
                        $coLStr = isset($subTotals[$key]) ? $subTotals[$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                        }
                        else {

                            $isExist_href = "<td style='font-size:1.3em;'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";

                        }


                        $str .= "$isExist_href";
                    }
                    $str .= "</tr>";

                }
            }

            $str .= "<tr bgcolor='#e5e5e5'>";
            foreach ($headers as $key => $label) {
                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                }
                else {

                    $isExist_href = "<td style='font-size:1.3em;'>" . formatField($key, $coLStr);
                    $isExist_href .= "</td>";

                }
//                $str.="<td style='font-size:1.5em;'>".formatField($key,$coLStr);
//                $str.="</td>";
                $str .= "$isExist_href";
            }
            $str .= "</tr>";

            $str .= "</table class='table table-condensed'>";
            if (show_debuger() == 1) {
                // arrPrint($totals);
                $dbet = $totals['debet'];
                $kdit = $totals['kredit'];
                $crossBalance = $dbet - $kdit;

                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
            }
        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewNeracaRealtime":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select month </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
//                "rek_id" => 0,
                "debet" => 0,
                "kredit" => 0
            );
            $str .= "<table class='table table-condensed table-bordered'>";
            foreach ($categories as $catName) {
                $subTotals = array(
//                    "rek_id" => 0,
                    "debet" => 0,
                    "kredit" => 0
                );


                $str .= "<tr bgcolor='#f0f0f0'>";
                $headers['rekening'] = $catName;//==override header label paling kiri
                foreach ($headers as $key => $label) {
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";

                    }
                    else {

                        $isExist_href = "<td>" . $label;
                        $isExist_href .= "</td>";

                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";

                if (isset($rekenings[$catName]) && sizeof($rekenings[$catName]) > 0) {
                    foreach ($rekenings[$catName] as $rek => $values) {
                        $str .= "<tr>";
                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                                $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";

                            }
                            else {
                                if (is_numeric($values[$key])) {
                                    if ($values[$key] >= 0) {
                                        $rVal = formatField($key, $values[$key]);
                                        $style = "style='text-align:right;'";
                                    }
                                    else {
//                                        $rVal = formatField($key, $values[$key]*-1);
                                        $rVal = "(" . number_format($values[$key] * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "";
                                }
//                                $isExist_href = "<td>" . formatField($key, $values[$key]);
                                $isExist_href = "<td $style>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $str .= "$isExist_href";
//                            if (is_numeric($values[$key])) {
                            if (array_key_exists($key, $totals)) {
                                $totals[$key] += $values[$key];
                                $subTotals[$key] += $values[$key];
                            }
                        }
                        $str .= "</tr>";

                    }

                    $str .= "<tr>";
                    foreach ($headers as $key => $label) {
                        $coLStr = isset($subTotals[$key]) ? $subTotals[$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                        }
                        else {

                            $isExist_href = "<td style='font-size:1.3em;'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";

                        }


                        $str .= "$isExist_href";
                    }
                    $str .= "</tr>";

                }
            }

            $str .= "<tr bgcolor='#e5e5e5'>";
            foreach ($headers as $key => $label) {
                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                }
                else {

                    $isExist_href = "<td style='font-size:1.3em;'>" . formatField($key, $coLStr);
                    $isExist_href .= "</td>";

                }
//                $str.="<td style='font-size:1.5em;'>".formatField($key,$coLStr);
//                $str.="</td>";
                $str .= "$isExist_href";
            }
            $str .= "</tr>";

            $str .= "</table class='table table-condensed'>";
            if (show_debuger() == 1) {
                // arrPrint($totals);
                $dbet = $totals['debet'];
                $kdit = $totals['kredit'];
                $crossBalance = $dbet - $kdit;

                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
            }
        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewRugiLaba":
//cekHere($defaultDate);
//cekHere($oldDate);
        $defaultDate = "2019-09";
        $oldDate = "2019-09";

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select month </span>";
        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m-d") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );
            $str .= "<table class='table table-condensed table-bordered'>";
            foreach ($categories as $catName) {
                $subTotals = array(
                    "debet" => 0,
                    "kredit" => 0
                );


                $str .= "<tr bgcolor='#f0f0f0'>";
                $headers['rekening'] = $catName;//==override header label paling kiri
                foreach ($headers as $key => $label) {
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";

                    }
                    else {

                        $isExist_href = "<td>" . $label;
                        $isExist_href .= "</td>";

                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";

                if (isset($rekenings[$catName]) && sizeof($rekenings[$catName]) > 0) {
                    foreach ($rekenings[$catName] as $rek => $values) {
                        $str .= "<tr>";
                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                                $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";

                            }
                            else {

                                $isExist_href = "<td>" . formatField($key, $values[$key]);
                                $isExist_href .= "</td>";

                            }
                            $str .= "$isExist_href";
                            if (is_numeric($values[$key])) {
                                $totals[$key] += $values[$key];
                                $subTotals[$key] += $values[$key];
                            }
                        }
                        $str .= "</tr>";

                    }

                    $str .= "<tr>";
                    foreach ($headers as $key => $label) {
                        $coLStr = isset($subTotals[$key]) ? $subTotals[$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                        }
                        else {

                            $isExist_href = "<td style='font-size:1.3em;'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";

                        }
//                        $str.="<td style='font-size:1.3em;'>".formatField($key,$coLStr);
//                        $str.="</td>";
                        $str .= "$isExist_href";
                    }
                    $str .= "</tr>";

                }
            }

            $str .= "<tr bgcolor='#e5e5e5'>";
            foreach ($headers as $key => $label) {
                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                }
                else {

                    $isExist_href = "<td style='font-size:1.3em;'>" . formatField($key, $coLStr);
                    $isExist_href .= "</td>";

                }
//                $str.="<td style='font-size:1.5em;'>".formatField($key,$coLStr);
//                $str.="</td>";
                $str .= "$isExist_href";
            }
            $str .= "</tr>";

            $str .= "</table class='table table-condensed'>";
        }
        else {
            $str .= "profit and lost is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewRugiLaba2":
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


//arrPrintWebs($rekenings);
        if (sizeof($categories) > 0) {
//            $totals = array(
//                "values_before_koreksi" => 0,
//                "values_koreksi" => 0,
//                "values" => 0,
//            );
            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
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
            foreach ($categories as $catName) {
                //                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
//                $subTotals = array(
//                    "values_before_koreksi" => 0,
//                    "values_koreksi" => 0,
//                    "values" => 0,
//                );

                $catBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $catNumb++;
                foreach ($headers as $key => $label) {
                    $subTotals[$key] = 0;
                }


//                $str .= "<tr bgcolor='#f0f0f0'>";
//                $str .= "<td>&nbsp;</td>";
//                foreach ($headers as $key => $label) {
//                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//
//                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";
//
//                    }
//                    else {
//                        if (!is_numeric($label)) {
//                            $hLabel = $label;
//                        }
//                        else {
//                            $hLabel = "";
//                        }
//                        $isExist_href = "<td>" . $hLabel;
//                        $isExist_href .= "</td>";
//
//                    }
//
//                    $str .= "$isExist_href";
//                }
//                $str .= "</tr>";
                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rek = $rekName;
                    $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $firstRow = $numRest == 0 ? "first_row" : "";

                    $str .= "<tr class='$firstRow'>";
                    $str .= "<td column='consulente:'>$catBottom</td>";
                    $str .= "<td column='$catBottom:'>$rekNameAlias_f</td>";

                    foreach ($headers as $key => $label) {

                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                        }
                        else {
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
                                }
                                else {
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "";
                                }
                            }
                            else {
                                $rVal = "";
                                $style = "";
                            }
                            $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                            $isExist_href = "<td $style>$rVal_f";
                            $isExist_href .= "</td>";

                        }


                        $str .= "$isExist_href";

                        if (array_key_exists($key, $totals)) {
                            $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
                            $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;
                        }
//                        if(isset($values[$key]) && is_numeric($values[$key])){
//                            if(!isset($totals[$key])){
//                                $totals[$key] = 0;
//                            }
//                            if(!isset($subTotals[$key])){
//                                $subTotals[$key] = 0;
//                            }
//                            $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
//                            $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;
//                        }
                    }
                    $str .= "</tr>";

                    $numRest++;
                }

                if ($catNumb == $last) {
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: #f0f0f0;'>";
                    $tfoot .= "<td></td>";
                    $tfoot .= "<td style='font-size:1.3em;'>$catBottom</td>";
                    foreach ($headers as $key => $label) {
                        if (isset($subTotals[$key])) {
                            $coLStr = $subTotals[$key];
                        }
                        else {
                            $coLStr = "";
                        }
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                        }
                        else {
                            if (is_numeric($coLStr)) {
                                if ($coLStr >= 0) {
                                    $rVal = number_format($coLStr * 1, "0", ".", ",");
                                    $style = "style='text-align:right;font-size:1.3em;'";
                                }
                                else {
                                    $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                    $style = "style='text-align:right;color:red;font-size:1.3em;'";
                                }
                            }
                            else {
                                $rVal = formatField($key, $coLStr);
                                $style = "style='font-size:1.3em;'";
                            }
                            $isExist_href = "<td $style>$rVal ";
                            $isExist_href .= "</td>";

                        }

                        $tfoot .= "$isExist_href";
                    }
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                }


            }
            $str .= "</tbody>";
            $str .= $tfoot;


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);
                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.first_row').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                                    delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                                    }, 200)
                                );
                    </script>";
        }
        else {
            $str .= "profit and lost is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewRugiLabaTahunan":
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

            $str .= "<span class='input-group-add-on' >select year </span>";
            // $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
            $str .= "<select class='form-control' onchange=\"location . href = '$thisPage?gr=$gr&date=' + this . value;\">";
            $str .= "<option value=''>--pilih tahun--</option>";
            // cekBiru($tahunDipilih);
            for ($i = 2015; $i < dtimeNow('Y'); $i++) {
                $selected_date = $i == $tahunDipilih ? "selected" : "";
                $str .= "<option $selected_date>$i</option>";
            }

            $str .= "</select>";
        }

        if (isset($linkExcel)) {

            // $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
            // $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
        }

        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";


        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );
            $str .= "<table class='table datatables table-condensed table-bordered'>";
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td>&nbsp;</td>";
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
            }
            $str .= "</tr>";

            $str .= "</thead>";

            $str .= "<tbody>";
            foreach ($categories as $catName) {
                //                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $subTotals = array(
                    //                    "debet" => 0,
                    //                    "kredit" => 0,
                    "values" => 0,
                );


                //                $str .= "<tr bgcolor='#f0f0f0'>";
                //                $str .= "<td>&nbsp;</td>";
                //                foreach ($headers as $key => $label) {
                //                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                //
                //                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";
                //
                //                    }
                //                    else {
                //                        if (!is_numeric($label)) {
                //                            $hLabel = $label;
                //                        }
                //                        else {
                //                            $hLabel = "";
                //                        }
                //                        $isExist_href = "<td>" . $hLabel;
                //                        $isExist_href .= "</td>";
                //
                //                    }
                //
                //                    $str .= "$isExist_href";
                //                }
                //                $str .= "</tr>";

                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rek = $rekName;
                    $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td>$rekNameAlias_f</td>";
                    foreach ($headers as $key => $label) {

                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                        }
                        else {
                            if (is_numeric($values[$key])) {
                                if ($values[$key] >= 0) {
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "style='text-align:right;'";
                                }
                                else {
                                    $rVal = "(" . number_format($values[$key] * -1, "0", ".", ",") . ")";
                                    $style = "style='text-align:right;color:red;'";
                                }
                            }
                            else {
                                $rVal = formatField($key, $values[$key]);
                                $style = "";
                            }
                            $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                            $isExist_href = "<td $style>$rVal_f";
                            $isExist_href .= "</td>";

                        }


                        $str .= "$isExist_href";

                        if (array_key_exists($key, $totals)) {
                            $totals[$key] += $values[$key];
                            $subTotals[$key] += $values[$key];
                        }
                    }
                    $str .= "</tr>";

                }

                $str .= "<tr style='background-color: #f0f0f0;'>";
                $catBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                $str .= "<td style='font-size:1.3em;'>$catBottom</td>";
                foreach ($headers as $key => $label) {
                    if (isset($subTotals[$key])) {
                        $coLStr = $subTotals[$key];
                    }
                    else {
                        $coLStr = "";
                    }
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {
                        if (is_numeric($coLStr)) {
                            if ($coLStr >= 0) {
                                $rVal = formatField($key, $coLStr);
                                $style = "style='text-align:right;font-size:1.3em;'";
                            }
                            else {
                                //                                    $rVal = formatField($key, $coLStr*-1);
                                $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                $style = "style='text-align:right;color:red;font-size:1.3em;'";
                            }
                        }
                        else {
                            $rVal = formatField($key, $coLStr);
                            $style = "style='font-size:1.3em;'";
                        }
                        $isExist_href = "<td $style>$rVal";
                        $isExist_href .= "</td>";

                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";

                //                }
            }
            $str .= "</tbody>";


            $str .= "</table class='table table-condensed'>";

            $str .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            stateSave: false,
                            paging: false,
                            ordering: false,
                            info: false,
                            searching: false,
//                            buttons: [
//                                        { extend: 'print', footer: true },
// 
//                                    ],
                                      buttons: [
                                          //'excel', 
                                          'print', 
                                          'pdf'
                                          
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

                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
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


                                //new $.fn.dataTable.FixedHeader( table );
                                $('.table-responsive').floatingScroll();
                            });


                    </script>";
        }
        else {
            $str .= "profit and lost is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewPLRealtime":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select month </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
//                "debet" => 0,
//                "kredit" => 0,
                "values" => 0,
            );
            $str .= "<table class='table table-condensed table-bordered'>";
            foreach ($categories as $catName) {
//                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $subTotals = array(
//                    "debet" => 0,
//                    "kredit" => 0,
                    "values" => 0,
                );


                $str .= "<tr bgcolor='#f0f0f0'>";
                $str .= "<td>&nbsp;</td>";
//                $headers['rekening'] = $catName;//==override header label paling kiri
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
                }
                $str .= "</tr>";

//                if (isset($rekenings[$catName]) && sizeof($rekenings[$catName]) > 0) {
//                    krsort($rekenings[$catName]);
//                    foreach ($rekenings[$catName] as $rek => $values) {
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rek = $rekName;
                    $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;

                    $str .= "<tr>";
                    $str .= "<td>$rekNameAlias</td>";
                    foreach ($headers as $key => $label) {

                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                        }
                        else {
                            if (is_numeric($values[$key])) {
                                if ($values[$key] >= 0) {
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "style='text-align:right;'";
                                }
                                else {
                                    $rVal = "(" . number_format($values[$key] * -1, "0", ".", ",") . ")";
                                    $style = "style='text-align:right;color:red;'";
                                }
                            }
                            else {
                                $rVal = formatField($key, $values[$key]);
                                $style = "";
                            }
                            $isExist_href = "<td $style>$rVal";
                            $isExist_href .= "</td>";

                        }
                        $str .= "$isExist_href";

                        if (array_key_exists($key, $totals)) {
                            $totals[$key] += $values[$key];
                            $subTotals[$key] += $values[$key];
                        }
                    }
                    $str .= "</tr>";

                }

                $str .= "<tr>";
                $catBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                $str .= "<td style='font-size:1.3em;'>$catBottom</td>";
                foreach ($headers as $key => $label) {
                    if (isset($subTotals[$key])) {
                        $coLStr = $subTotals[$key];
                    }
                    else {
                        $coLStr = "";
                    }
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {
                        if (is_numeric($coLStr)) {
                            if ($coLStr >= 0) {
                                $rVal = formatField($key, $coLStr);
                                $style = "style='text-align:right;font-size:1.3em;'";
                            }
                            else {
//                                    $rVal = formatField($key, $coLStr*-1);
                                $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                $style = "style='text-align:right;color:red;font-size:1.3em;'";
                            }
                        }
                        else {
                            $rVal = formatField($key, $coLStr);
                            $style = "style='font-size:1.3em;'";
                        }
                        $isExist_href = "<td $style>$rVal";
                        $isExist_href .= "</td>";

                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";

//                }
            }

//            $str .= "<tr bgcolor='#e5e5e5'>";
//            foreach ($headers as $key => $label) {
//                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
//                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//
//                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";
//
//                }
//                else {
//                    if (is_numeric($coLStr)) {
//                        if($coLStr >= 0){
//                            $rVal = formatField($key, $coLStr);
//                            $style = "style='text-align:right;font-size:1.3em;'";
//                        }
//                        else{
//                            $rVal = formatField($key, $coLStr*-1);
//                            $style = "style='text-align:right;color:red;font-size:1.3em;'";
//                        }
//                    }
//                    else{
//                        $rVal = formatField($key, $coLStr);
//                        $style = "style='font-size:1.3em;'";
//                    }
//
//                    $isExist_href = "<td $style>$rVal";
//                    $isExist_href .= "</td>";
//
//                }
//
//                $str .= "$isExist_href";
//            }
//            $str .= "</tr>";

            $str .= "</table class='table table-condensed'>";
        }
        else {
            $str .= "profit and lost is not yet available.<br>start making any transaction so this page has a content";
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

    case "viewNeraca_consolidated":

        $pakai_ini = $pakai_konsolidasi;
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select date </span>";
        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );

            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $str .= "<th></th>";
                }
            }
            foreach ($headers as $key => $label) {
                $str .= "<th></th>";
            }
            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
//                    $headerBgColor = "background-color:#DFDFAC;";
//                    $isiBgColor = "background-color:#FFFFCC;";
//                    $subBgColor = "background-color:#FFFFCC;";
//                    $totalBgColor = "background-color:#FFFFCC;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
//                    $headerBgColor = "background-color:#DFB0BE;";
//                    $isiBgColor = "background-color:#FFD0DE;";
//                    $subBgColor = "background-color:#FFD0DE;";
//                    $totalBgColor = "background-color:#FFD0DE;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
                $str .= "$cabName";
                $str .= "</th>";
            }
            if ($pakai_ini == 1) {
                $str .= "<th colspan='3' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                $str .= "Konsolidasi";
                $str .= "</th>";
            }
            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $str .= "<th>$label</th>";
                }
            }
            foreach ($headers as $key => $label) {
                $str .= "<th>$label</th>";
            }
            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";
                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";

                        foreach ($headers as $key => $label) {
                            $val = 0;
                            if (isset($rekenings[$cabID][$catName][$rekName])) {

                                if (($rekenings[$cabID][$catName][$rekName]['debet'] > 0) && ($rekenings[$cabID][$catName][$rekName]['kredit'] > 0)) {

                                    $val_detail = $rekenings[$cabID][$catName][$rekName]['debet'] - $rekenings[$cabID][$catName][$rekName]['kredit'];
                                    if ($val_detail > 0) {
                                        $rekenings[$cabID][$catName][$rekName]['debet'] = $val_detail;
                                        $rekenings[$cabID][$catName][$rekName]['kredit'] = 0;
                                    }
                                    else {
                                        $rekenings[$cabID][$catName][$rekName]['debet'] = 0;
                                        $rekenings[$cabID][$catName][$rekName]['kredit'] = $val_detail * -1;
                                    }
                                }

                                $value = $rekenings[$cabID][$catName][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                        $align = "text-align:right;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }
                                $str .= "<td style='$bgColor$align' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rVal</td>";
                            }
                            else {
                                $rVal = "";
                                $str .= "<td style='$bgColor'>$rVal</td>";
                            }
                            if (is_numeric($val)) {
                                if ($key != "link") {
                                    if (!isset($subTotalsCatRight[$catName][$key])) {
                                        $subTotalsCatRight[$catName][$key] = 0;
                                    }

                                    if (!isset($subTotals[$cabID][$catName])) {
                                        $subTotals[$cabID][$catName] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }
                                    if (!isset($totals[$cabID])) {
                                        $totals[$cabID] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }
                                    if (!isset($subTotalsRight[$rekName])) {
                                        $subTotalsRight[$rekName] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }

                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion

                    if ($pakai_ini == 1) {

                        // ini milik holding...
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                            if ($key != "link") {
                                if (!isset($subTotalsCatRight2[$catName])) {
                                    $subTotalsCatRight2[$catName] = array(
                                        "debet" => 0,
                                        "kredit" => 0,
                                    );
                                }
                                if (isset($subTotalsRight[$rekName][$key])) {

                                    if ($subTotalsRight[$rekName]['debet'] && $subTotalsRight[$rekName]['kredit']) {
                                        $val_comp = $subTotalsRight[$rekName]['debet'] - $subTotalsRight[$rekName]['kredit'];
                                        if ($val_comp > 0) {
                                            $subTotalsRight[$rekName]['debet'] = $val_comp;
                                            $subTotalsRight[$rekName]['kredit'] = 0;
                                        }
                                        else {
                                            $subTotalsRight[$rekName]['debet'] = 0;
                                            $subTotalsRight[$rekName]['kredit'] = $val_comp * -1;
                                        }
                                    }
                                    if (in_array($rekName, $accountConsolidation)) {
                                        $value = $subTotalsRight[$rekName][$key];
                                        $val = 0;
                                        $color = "color:#A9A9A9;";
                                        $miring = "font-style:italic;";
                                    }
                                    else {
                                        $value = $subTotalsRight[$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        $color = "";
                                        $miring = "";
                                    }

                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                        $subTotalsCatRight2[$catName][$key] += $val;

                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $str .= "<td style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'></td>";
                                }
                            }
                            else {
                                $str .= "<td style='$bgColor'></td>";
                            }
                        }
                    }
                    $str .= "<td><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    $str .= "</tr>";
                }
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                    if ($key != "link") {
                        $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";

                        if (!isset($totalsBottomRight[$key])) {
                            $totalsBottomRight[$key] = 0;
                        }
                        $totalsBottomRight[$key] += $coLStr;
                    }
                }
            }
            $str .= "</tbody>";
            //endregion


            //  region total bawah
            $str .= "<tfoot>";
            $str .= "<tr style='background-color:#f0f0f0;'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";

                    $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'></td>";
                }
            }

            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</tfoot>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip',
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "viewDivNeraca_consolidated":
//        arrPrint($headers);
//        arrPrint($cabang);
        $arrHDColumnStyle = array(
            "header" => "background-color:#ACACAC;",
            "isi" => "background-color:#CCCCCC;",
            "subTotal" => "background-color:#CCCCCC;",
            "total" => "background-color:#CCCCCC;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select date </span>";
        $str .= "<input type='date' value='$defaultDate' min='$oldDate' max='" . date("Y-m-d") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );

            $str .= "<table class='table table-condensed table-bordered'>";


            //region header tabel
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td class='text-center' rrowspan='2'>--</td>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    $headerBgColor = "background-color:#DFDFAC;";
                    $isiBgColor = "background-color:#FFFFCC;";
                    $subBgColor = "background-color:#FFFFCC;";
                    $totalBgColor = "background-color:#FFFFCC;";
                }
                else {
                    $headerBgColor = "background-color:#DFB0BE;";
                    $isiBgColor = "background-color:#FFD0DE;";
                    $subBgColor = "background-color:#FFD0DE;";
                    $totalBgColor = "background-color:#FFD0DE;";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

                $str .= "<td colspan='3' class='text-center' style='$headerBgColor'>";
                $str .= "$cabName";
                $str .= "</td>";
            }
            $str .= "<td colspan='3' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
            $str .= "Total";
            $str .= "</td>";
            $str .= "</tr>";
            //endregion


            //region isi tabel
            foreach ($categories as $catName) {

                //region header category
                $str .= "<tr bgcolor='#f0f0f0'>";
                $str .= "<td>$catName</td>";
                foreach ($cabang as $cabID => $cabName) {
                    $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                    foreach ($headers as $key => $label) {
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }
                }
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                    }
                    else {
                        $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                    }
                    $str .= "$isExist_href";
                }
                $str .= "</tr>";
                //endregion

                if (isset($rekeningsName[$catName]) && sizeof($rekeningsName[$catName]) > 0) {

                    foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                        $str .= "<tr>";
                        $str .= "<td>$rekName</td>";


                        //region isi category
                        foreach ($cabang as $cabID => $cabName) {

                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";

                            if (!isset($subTotals[$cabID][$catName])) {

                                $subTotals[$cabID][$catName] = array(
                                    "debet" => 0,
                                    "kredit" => 0,
                                );
                            }
                            if (!isset($totals[$cabID])) {

                                $totals[$cabID] = array(
                                    "debet" => 0,
                                    "kredit" => 0,
                                );
                            }
                            if (!isset($subTotalsRight[$rekName])) {
                                $subTotalsRight[$rekName] = array(
                                    "debet" => 0,
                                    "kredit" => 0,
                                );
                            }


                            foreach ($headers as $key => $label) {
                                if (!isset($subTotalsCatRight[$catName][$key])) {
                                    $subTotalsCatRight[$catName][$key] = 0;
                                }
                                if (!isset($totalsBottomRight[$key])) {
                                    $totalsBottomRight[$key] = 0;
                                }


                                $val = 0;
                                if (isset($rekenings[$cabID][$catName][$rekName])) {

                                    $value = $rekenings[$cabID][$catName][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $str .= "<td style='$bgColor'>" . formatField($key, $val) . "</td>";
//                                foreach($rekenings[$cabID][$catName][$rekName] as $value){
//                                    $val = (isset($value[$key]) && strlen($value[$key]) > 0) ? $value[$key] : "0";
//                                    $str .= "<td>" . formatField($key, $val) . "</td>";
//                                }

                                }
                                else {
                                    $str .= "<td style='$bgColor'>&nbsp;</td>";
                                }

                                if (is_numeric($val)) {
                                    if ($key != "link") {
                                        $totals[$cabID][$key] += $val;
                                        $subTotals[$cabID][$catName][$key] += $val;
                                        $subTotalsRight[$rekName][$key] += $val;
                                        $subTotalsCatRight[$catName][$key] += $val;
                                        $totalsBottomRight[$key] += $val;
                                    }
                                }
                            }
                        }
                        //endregion

                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                            if ($key != "link") {
                                if (isset($subTotalsRight[$rekName][$key])) {
                                    $value = $subTotalsRight[$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $str .= "<td style='$bgColor'>" . formatField($key, $val) . "</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'>&nbsp;</td>";
                                }
                            }
                            else {
                                $str .= "<td style='$bgColor'>&nbsp;</td>";
                            }
                        }
                        $str .= "</tr>";
                    }
                }


                //region subTotal bawah category
                $str .= "<tr>";
                $str .= "<td>&nbsp;</td>";
                foreach ($cabang as $cabID => $cabName) {
                    $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";

                    foreach ($headers as $key => $label) {
                        $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        else {
                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";

                        }
                        $str .= "$isExist_href";
                    }
                }
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                    if ($key != "link") {

                        $coLStr = isset($subTotalsCatRight[$catName][$key]) ? $subTotalsCatRight[$catName][$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        else {
                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";

                        }
                        $str .= "$isExist_href";
                    }
                    else {
                        $str .= "<td style='$bgColor'>&nbsp;</td>";
                    }
                }
                $str .= "</tr>";
                //endregion

            }
            //endregion


            //  region total bawah
            $str .= "<tr>";
            $str .= "<td>&nbsp;</td>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";

                    $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'>&nbsp;</td>";
                }
            }
            $str .= "</tr>";
            //  endregion total bawah


//            arrPrint($totalsBottomRight);


            $str .= "</table class='table table-condensed'>";
        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "btn_back" => callBackNav(),
                "content" => $str,
                "profile_name" => $this->session->login['nama'],
            )
        );
        $p->render();


        break;

    case "viewPL_consolidated":

        //        $arrHDColumnStyle = array(
        //            "header" => "background-color:#ACACAC;",
        //            "isi" => "background-color:#CCCCCC;",
        //            "subTotal" => "background-color:#CCCCCC;",
        //            "total" => "background-color:#CCCCCC;",
        //        );
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
//print_r($headers);
        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select date </span>";
        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
                $str .= "$cabName";
                $str .= "</th>";
//                $str .= "<th></th>";

            }
            $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
            $str .= "Konsolidasi";
            $str .= "</th>";
            $str .= "<th class='text-center'>-</th>";
            $str .= "<th class='text-center'>-</th>";
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion

            //region isi tabel
            $str .= "<tbody>";
            $tfoot = "";
            $catCount = count($categories);
            $cat = 1;
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;
                    $firstRow = $numRest == 0 ? "first_row" : "";
                    $str .= "<tr class='$firstRow'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                        if (!isset($subTotals[$cabID][$catName])) {
                            $subTotals[$cabID][$catName] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        if (!isset($totals[$cabID])) {
                            $totals[$cabID] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        if (!isset($subTotalsRight[$rekName])) {
                            $subTotalsRight[$rekName] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        foreach ($headers as $key => $label) {
                            if (!isset($subTotalsCatRight[$catName][$key])) {
                                $subTotalsCatRight[$catName][$key] = 0;
                            }
                            if (!isset($totalsBottomRight[$key])) {
                                $totalsBottomRight[$key] = 0;
                            }

                            $val = 0;
                            if (isset($rekenings[$catName][$cabID][$rekName])) {

                                $value = $rekenings[$catName][$cabID][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
//                                        $rVal = formatField($key, $value);
                                        $rVal = number_format($value * 1, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }

                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $rVal = "";
                                $str .= "<td style='$bgColor'>$rVal</td>";
                            }


                            if (is_numeric($val)) {
                                if ($key != "link" && $key != "link_detail") {
//                                if ($key != "link") {
                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $totalsBottomRight[$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                        if ($key != "link") {
                            if (!isset($subTotalsCatRight2[$catName])) {
                                $subTotalsCatRight2[$catName] = array(
                                    //                                    "debet" => 0,
                                    //                                    "kredit" => 0,
                                    "values" => 0,
                                );
                            }
                            if (isset($subTotalsRight[$rekName][$key])) {

                                $value = $subTotalsRight[$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";

                                if (is_numeric($value)) {
                                    if ($value > 0) {
//                                        $rVal = formatField($key, $value);
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }

                                    $subTotalsCatRight2[$catName][$key] += $val;
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }


                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;

                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $str .= "<td style='$bgColor'></td>";
                                $str .= "<td style='$bgColor'></td>";
                            }
                        }
                    }
                    $str .= "</tr>";
                    $numRest++;
                }

                if ($cat == $catCount) {
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: lightblue;'>";
                    $tfoot .= "<td>-</td>";
                    $tfoot .= "<td style='text-align:left;font-size:1.3em;'>$catSubBottom</td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";

                        foreach ($headers as $key => $label) {
//                        cekHere("$key => $label");
                            $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                if ($key != "link") {
                                    $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        //                                    $rVal = formatField($key, $coLStr);
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<td $style>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                        if ($key != "link") {
                            $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<td $style'>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
                    $tfoot .= "<th></th>";
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                }

                $cat++;
//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
////                        $str .= "<td></td>";
//                    }
////                    $str .= "<td></td>";
////                    $str .= "<td>&nbsp;</td>";
//                    $str .= "</tr>";
//                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;
            //endregion


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "viewPLYearToDate_consolidated":

        //        $arrHDColumnStyle = array(
        //            "header" => "background-color:#ACACAC;",
        //            "isi" => "background-color:#CCCCCC;",
        //            "subTotal" => "background-color:#CCCCCC;",
        //            "total" => "background-color:#CCCCCC;",
        //        );
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        //        $str .= "<div class='panel-body alert alert-info-dot'>";
        //        $str .= "<div class='input-group'>";
        //        $str .= "<span class='input-group-add-on' >select date </span>";
        //        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        //        $str .= "</div class='input-group'>";
        //        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td class='text-center'></td>";
            $str .= "<td class='text-center'></td>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    //                    $headerBgColor = "background-color:#DFDFAC;";
                    //                    $isiBgColor = "background-color:#FFFFCC;";
                    //                    $subBgColor = "background-color:#FFFFCC;";
                    //                    $totalBgColor = "background-color:#FFFFCC;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
                    //                    $headerBgColor = "background-color:#DFB0BE;";
                    //                    $isiBgColor = "background-color:#FFD0DE;";
                    //                    $subBgColor = "background-color:#FFD0DE;";
                    //                    $totalBgColor = "background-color:#FFD0DE;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

                $str .= "<td ccolspan='2' class='text-center' style='$headerBgColor'>";
                $str .= "$cabName";
                $str .= "</td>";
                $str .= "<td></td>";
            }
            $str .= "<td ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
            $str .= "Konsolidasi";
            $str .= "</td>";
//            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $catNumb++;

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";

                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $firstRow = $numRest == 0 ? "first_row" : "";

                    $str .= "<tr class='$firstRow'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";


                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                        if (!isset($subTotals[$cabID][$catName])) {
                            $subTotals[$cabID][$catName] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        if (!isset($totals[$cabID])) {
                            $totals[$cabID] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        if (!isset($subTotalsRight[$rekName])) {
                            $subTotalsRight[$rekName] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        foreach ($headers as $key => $label) {
                            if (!isset($subTotalsCatRight[$catName][$key])) {
                                $subTotalsCatRight[$catName][$key] = 0;
                            }
                            if (!isset($totalsBottomRight[$key])) {
                                $totalsBottomRight[$key] = 0;
                            }

                            $val = 0;
                            if (isset($rekenings[$catName][$cabID][$rekName])) {

                                $value = $rekenings[$catName][$cabID][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value * 1, "0", ".", ",");
                                        $align = "text-align:right;color:black;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "0";
                                        $align = "text-align:right;color:black;";
                                    }
                                    if (isset($rekenings[$catName][$cabID][$rekName]['link_values'])) {
                                        $link_values = $rekenings[$catName][$cabID][$rekName]['link_values'];
//                                        $rVal = "<a href='$link_values' ttarget='_blank' style='$align'>$rVal</a>";
                                        $rVal = "$rVal";
                                    }
                                    else {

                                        $rVal = "$rVal";
                                    }
                                    //------------

                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }

                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $rVal = "";
                                $str .= "<td style='$bgColor'>";
                                $str .= "$rVal";
                                $str .= "</td>";

                            }


                            if (is_numeric($val)) {
                                if ($key != "link") {
                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $totalsBottomRight[$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                        if ($key != "link") {
                            if (!isset($subTotalsCatRight2[$catName])) {
                                $subTotalsCatRight2[$catName] = array(
                                    //                                    "debet" => 0,
                                    //                                    "kredit" => 0,
                                    "values" => 0,
                                );
                            }
                            if (isset($subTotalsRight[$rekName][$key])) {

                                $value = $subTotalsRight[$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";

                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value * 1, "0", ".", ",");
                                        //                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }

                                    $subTotalsCatRight2[$catName][$key] += $val;
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }

                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "" : $rVal;

                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $str .= "<td style='$bgColor'></td>";
                            }
                        }
//                        else {
//                            $str .= "<td style='$bgColor'></td>";
//                        }
//
                    }
                    $str .= "</tr>";

                    $numRest++;
                }

                if ($catNumb == $last) {
                    //region subTotal bawah category
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: #f0f0f0;'>";
                    $tfoot .= "<th></th>";
                    $tfoot .= "<th style='text-align:left;font-size:1.3em;'>$catSubBottom</th>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";

                        foreach ($headers as $key => $label) {
                            $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                if ($key != "link") {
                                    $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<th $style>$rVal";
                                $isExist_href .= "</th>";
                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                        if ($key != "link") {
                            $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<th $style'>$rVal";
                                $isExist_href .= "</th>";

                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                    //endregion
                }

//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
//                        $str .= "<td></td>";
//                    }
//                    $str .= "<td></td>";
//                    $str .= "</tr>";
//                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;

            //endregion


            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($title) . "-$mode_report');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);
                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.first_row').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                                    delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                                    }, 200)
                                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "viewPLYearToDate_consolidatedKomparasi":

        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        if (isset($rangeDate) && ($rangeDate == true)) {
//            if($this->session->login['debuger'] == 1){

            //region range tanggal
            $str .= "<div class='row'>";
            $str .= "<div class='col-md-12'>";

            $str .= "<div class='panel panel-default'>";
            $str .= "<div class='panel-body no-padding'>";
            $str .= "<div class='table-responsive'>";
            $str .= "<table class='table table-condensed no-padding no-border no-margin'
                                       style='border:0px solid black;'>";
            $str .= "<tr>";
            $str .= "<td valign='middle' class='text-right'>";
            $str .= "<span class='glyphicon glyphicon-calendar'></span> start date";
            $str .= "</td>";
            $str .= "<td>";
            $str .= "<input id='date1' class='form-control' type='date' value='$date1'
                                                   min='$minDate' max='$maxDate'>";
            $str .= "</td>";


            $str .= "<td valign='middle' class='text-right'>";
            $str .= "<span class='glyphicon glyphicon-calendar'></span> to date";
            $str .= "</td>";
            $str .= "<td>";
            $str .= "<input id='date2' class='form-control' type='date' value='$date2'
                                                    min='$minDate' max='$maxDate'>";
            $str .= "</td>";

            $str .= "<td align='left' valign='middle'>";
            $str .= "<a class='btn btn-primary bbtn-block' href='javascript:void(0)'
                      onclick=\"location.href='" . $thisPage . "&date1='+document.getElementById('date1').value+'&date2='+document.getElementById('date2').value+'';\"
                                             >";
            $str .= "<span class='fa fa-arrow-right'></span>";
            $str .= "</a>";

            $str .= "</td>";
            $str .= "</tr>";


            $str .= "</table>";
            $str .= "</div>";
            $str .= "</div>";
            $str .= "</div>";

            $str .= "</div>";
            $str .= "</div>";
            //endregion
//            }


        }

        if (sizeof($categories) > 0) {
            foreach ($headerTahun as $thn => $thn_label) {
                $totals[$thn]['values'] = 0;
            }

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td class='text-center' rowspan='2'></td>";
            $str .= "<td class='text-center' rowspan='2'></td>";

            //region top header
            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    //                    $headerBgColor = "background-color:#DFDFAC;";
                    //                    $isiBgColor = "background-color:#FFFFCC;";
                    //                    $subBgColor = "background-color:#FFFFCC;";
                    //                    $totalBgColor = "background-color:#FFFFCC;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
                    //                    $headerBgColor = "background-color:#DFB0BE;";
                    //                    $isiBgColor = "background-color:#FFD0DE;";
                    //                    $subBgColor = "background-color:#FFD0DE;";
                    //                    $totalBgColor = "background-color:#FFD0DE;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

                $str .= "<td colspan='2' class='text-center' style='$headerBgColor'>";
                $str .= "$cabName";
                $str .= "</td>";
//                $str .= "<td></td>";
            }
            $str .= "<td colspan='2' rrowspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
            $str .= "Konsolidasi";
            $str .= "</td>";
            $str .= "</tr>";
            //endregion

            // region subheader
            $str .= "<tr bgcolor='#f0f0f0'>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headerTahun as $thn => $thn_label) {
                    $str .= "<td ccolspan='2' class='text-center' style='$headerBgColor'>";
                    $str .= "$thn";
                    $str .= "</td>";
                }
            }
            foreach ($headerTahun as $thn => $thn_label) {
                $str .= "<td ccolspan='2' class='text-center' style='$headerBgColor'>";
                $str .= "$thn";
                $str .= "</td>";
            }
            $str .= "</tr>";
            // endregion
            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $catNumb++;

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";

                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $firstRow = $numRest == 0 ? "first_row" : "";

                    $str .= "<tr class='$firstRow'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";


                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        foreach ($headerTahun as $thn => $thn_label) {

                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                            if (!isset($subTotals[$thn][$cabID][$catName])) {
                                $subTotals[$thn][$cabID][$catName] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($totals[$thn][$cabID])) {
                                $totals[$thn][$cabID] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($subTotalsRight[$thn][$rekName])) {
                                $subTotalsRight[$thn][$rekName] = array(
                                    "values" => 0,
                                );
                            }
                            foreach ($headers as $key => $label) {
                                if (!isset($subTotalsCatRight[$thn][$catName][$key])) {
                                    $subTotalsCatRight[$thn][$catName][$key] = 0;
                                }
                                if (!isset($totalsBottomRight[$thn][$key])) {
                                    $totalsBottomRight[$thn][$key] = 0;
                                }

                                $val = 0;
                                if (isset($rekenings[$thn][$catName][$cabID][$rekName])) {

                                    $value = $rekenings[$thn][$catName][$cabID][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;color:black;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "0";
                                            $align = "text-align:right;color:black;";
                                        }
                                        if (isset($rekenings[$catName][$cabID][$rekName]['link_values'])) {
                                            $link_values = $rekenings[$catName][$cabID][$rekName]['link_values'];
//                                        $rVal = "<a href='$link_values' ttarget='_blank' style='$align'>$rVal</a>";
                                            $rVal = "$rVal";
                                        }
                                        else {

                                            $rVal = "$rVal";
                                        }
                                        //------------

                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }

                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $rVal = "";
                                    $str .= "<td style='$bgColor'>";
                                    $str .= "$rVal";
                                    $str .= "</td>";

                                }


                                if (is_numeric($val)) {
                                    if ($key != "link") {
                                        $totals[$thn][$cabID][$key] += $val;
                                        $subTotals[$thn][$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$thn][$catName][$key] += $val;
                                        $totalsBottomRight[$thn][$key] += $val;
                                        $subTotalsRight[$thn][$rekName][$key] += $val;
                                        //--------------
                                        if ($catNumb != $last) {
                                            if (!isset($totalBawahNetto[$thn][$cabID][$key])) {
                                                $totalBawahNetto[$thn][$cabID][$key] = 0;
                                            }
                                            $totalBawahNetto[$thn][$cabID][$key] += $val;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headerTahun as $thn => $thn_label) {

                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                            if ($key != "link") {
                                if (!isset($subTotalsCatRight2[$thn][$catName])) {
                                    $subTotalsCatRight2[$thn][$catName] = array(
                                        "values" => 0,
                                    );
                                }
                                if (isset($subTotalsRight[$thn][$rekName][$key])) {

                                    $value = $subTotalsRight[$thn][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";

                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }

                                        $subTotalsCatRight2[$thn][$catName][$key] += $val;
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }

                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "" : $rVal;

                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'></td>";
                                }
                            }

                        }
                    }
                    $str .= "</tr>";

                    $numRest++;
                }

                if ($catNumb == $last) {
                    //region subTotal bawah category
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: #f0f0f0;'>";
                    $tfoot .= "<th></th>";
                    $tfoot .= "<th style='text-align:left;font-size:1.3em;'>$catSubBottom</th>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                        foreach ($headerTahun as $thn => $thn_label) {

                            foreach ($headers as $key => $label) {
                                $coLStr = isset($subTotals[$thn][$cabID][$catName][$key]) ? $subTotals[$thn][$cabID][$catName][$key] : "";
                                //----------
                                $valBawahNetto = isset($totalBawahNetto[$thn][$cabID][$key]) ? $totalBawahNetto[$thn][$cabID][$key] : "";
                                //----------
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    if ($key != "link") {
                                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    }
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                        //----------
                                        if (floor($coLStr) != floor($valBawahNetto)) {
//                                    if(($coLStr) != ($valBawahNetto)){
                                            $msg = ("ada selisih laba netto... $coLStr != $valBawahNetto :: $cabName");
                                            $tutupLaporan = true;
                                            if (!isset($tutupLaporanCabang[$thn][$cabID])) {
                                                $tutupLaporanCabang[$thn][$cabID] = $msg;
                                            }
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    //----------
//                                $valBawahNetto_f = "<br>".number_format($valBawahNetto, "0", ".", ",");
                                    //----------
                                    $isExist_href = "<th $style>$rVal";
                                    $isExist_href .= "</th>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                    foreach ($headerTahun as $thn => $thn_label) {

                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                            if ($key != "link") {
                                $coLStr = isset($subTotalsCatRight2[$thn][$catName][$key]) ? $subTotalsCatRight2[$thn][$catName][$key] : "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    $isExist_href = "<th $style'>$rVal";
                                    $isExist_href .= "</th>";

                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                    //endregion
                }
//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
//                        $str .= "<td></td>";
//                    }
//                    $str .= "<td></td>";
//                    $str .= "</tr>";
//                }
            }
            $str .= "</tbody>";
            $str .= $tfoot;
//arrPrint($subTotals);
//arrPrintPink($totalBawahNetto);

            //endregion


            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($title) . "-$mode_report');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);
                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.first_row').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                                    delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                                    }, 200)
                                );
                    </script>";

//            cekHere($tutupLaporan);
            if (isset($tutupLaporan) && ($tutupLaporan == true)) {
//                arrPrintPink($tutupLaporanCabang);
//                $str = underMaintenance();
            }


        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "viewNeracaYearToDate_consolidated":

        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );


            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $str .= "<th></th>";
                }
            }
            foreach ($headers as $key => $label) {
                $str .= "<th></th>";
            }
            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

//                $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
                $str .= "<th colspan='3' class='text-center text-uppercase'>";
                $str .= "$cabName";
                $str .= "</th>";
//                $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
            }


//            $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
            $str .= "<th colspan='3' class='text-center text-uppercase'>";
            $str .= "Konsolidasi";
            $str .= "</th>";
//            $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";

            $str .= "<th class='text-center text-uppercase' rrowspan='2'></th>";

//            for($r=0;$r<=($j*2)+1;$r++){
//                $str .= "<th colspanx='1' class='hiddenxx'>&nbsp;</th>";
//            }

            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $str .= "<th>$label</th>";
                }
            }
            foreach ($headers as $key => $label) {
                $str .= "<th>$label</th>";
            }
            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "</thead>";
            $str .= "<tbody>";
//            $str .= "</tbody>";
//            $str .= "</table>";
            //endregion

            //region isi tabel
//            $str .= "<tbody>";
//            $i = 0;
//            $len = count($categories);
            foreach ($categories as $catName) {

//                if ($i == 0) {
//                    // first
//                } else if ($i == $len - 1) {
//                    // last
//                }

//                $str .= "<div class='table-responsive tbl_$catName'>";
//                $str .= "<table id='tbl_$catName' class='table display compact table-bordered'>";
//                $str .= "<thead>";
                //region header category
//                $str .= "<tr style='background-color: lightblue;' class='text-center text-bold text-uppercase'>";
//                $str .= "<td column='consulente:'>$catName</td>";
//                $str .= "<td column='$catName:'>$catName</td>";
//                foreach ($cabang as $cabID => $cabName) {
//                    $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";
//                    foreach ($headers as $key => $label) {
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor min-width: 125px;max-width: 125px;'>$label</td>";
//                        }
//                        else {
//                            if($label!=""){
//                                $isExist_href = "<td column='$label:' style='$bgColor min-width: 125px;max-width: 125px;'>" . $label . "</td>";
//                            }
//                            else{
//                                $isExist_href = "<td column='-:' style='$bgColor min-width: 23px;max-width: 23px;'>" . $label . "</td>";
//                            }
//
//                        }
//                        $str .= "$isExist_href";
//                    }
//                }
//                foreach ($headers as $key => $label) {
//                    $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
//                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                        $isExist_href = $key == "link" ? "" : "<td style='$bgColor min-width: 150px;max-width: 150px;'>$label</td>";
//                    }
//                    else {
//                        $isExist_href = "<td style='$bgColor min-width: 150px;max-width: 150px;'>" . $label . "</td>";
//                    }
//                    $str .= "$isExist_href";
//                }
//                $str .= "<td style='min-width: 150px;max-width: 150px;'>$catName</td>";
//                $str .= "</tr>";
                //endregion
//                $str .= "</thead>";
//                $str .= "<tbody>";
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                    $rekAlias12Tmp = explode(' ', $rekNameAlias);
                    $rekAlias12 = count($rekAlias12Tmp) > 1 ? $rekAlias12Tmp[0] . " " . $rekAlias12Tmp[1] : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
//                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rekNameAlias</span></td>";
                    $str .= "<td column='$catName:'>$rekNameAlias</td>";
                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                        foreach ($headers as $key => $label) {
                            $val = 0;
                            if (isset($rekenings[$cabID][$catName][$rekName])) {

                                if (($rekenings[$cabID][$catName][$rekName]['debet'] > 0) && ($rekenings[$cabID][$catName][$rekName]['kredit'] > 0)) {

                                    $val_detail = $rekenings[$cabID][$catName][$rekName]['debet'] - $rekenings[$cabID][$catName][$rekName]['kredit'];
                                    if ($val_detail > 0) {
                                        $rekenings[$cabID][$catName][$rekName]['debet'] = $val_detail;
                                        $rekenings[$cabID][$catName][$rekName]['kredit'] = 0;
                                    }
                                    else {
                                        $rekenings[$cabID][$catName][$rekName]['debet'] = 0;
                                        $rekenings[$cabID][$catName][$rekName]['kredit'] = $val_detail * -1;
                                    }
                                }

                                $value = $rekenings[$cabID][$catName][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
//                                        $rVal = formatField($key, $value);
                                        $rVal = number_format($value * 1, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                        $align = "text-align:right;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $value);
//                                    $rVal = number_format($value*1, "0", ".", ",");
                                    $align = "text-align:right;";
                                }
                                if ($label != '') {
                                    $str .= "<td column='$label:' style='$bgColor$align' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td column='-:' style='$bgColor$align' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rVal</td>";
                                }
                            }
                            else {
                                $rVal = "";
                                if ($label != '') {
                                    $str .= "<td column='$label:' style='$bgColor'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td column='-:' style='$bgColor'>$rVal</td>";
                                }

                            }
                            if (is_numeric($val)) {
                                if ($key != "link") {
                                    if (!isset($subTotalsCatRight[$catName][$key])) {
                                        $subTotalsCatRight[$catName][$key] = 0;
                                    }

                                    if (!isset($subTotals[$cabID][$catName])) {
                                        $subTotals[$cabID][$catName] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }
                                    if (!isset($totals[$cabID])) {
                                        $totals[$cabID] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }
                                    if (!isset($subTotalsRight[$rekName])) {
                                        $subTotalsRight[$rekName] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }

                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                        if ($key != "link") {
                            if (!isset($subTotalsCatRight2[$catName])) {
                                $subTotalsCatRight2[$catName] = array(
                                    "debet" => 0,
                                    "kredit" => 0,
                                );
                            }
                            if (isset($subTotalsRight[$rekName][$key])) {

                                if ($subTotalsRight[$rekName]['debet'] && $subTotalsRight[$rekName]['kredit']) {
                                    $val_comp = $subTotalsRight[$rekName]['debet'] - $subTotalsRight[$rekName]['kredit'];
                                    if ($val_comp > 0) {
                                        $subTotalsRight[$rekName]['debet'] = $val_comp;
                                        $subTotalsRight[$rekName]['kredit'] = 0;
                                    }
                                    else {
                                        $subTotalsRight[$rekName]['debet'] = 0;
                                        $subTotalsRight[$rekName]['kredit'] = $val_comp * -1;
                                    }
                                }
                                if (in_array($rekName, $accountConsolidation)) {
                                    $value = $subTotalsRight[$rekName][$key];
                                    $val = 0;
                                    $color = "color:#A9A9A9;";
                                    $miring = "font-style:italic;";
                                }
                                else {
                                    $value = $subTotalsRight[$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $color = "";
                                    $miring = "";
                                }
                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                        $align = "text-align:right;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                    $subTotalsCatRight2[$catName][$key] += $val;

                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }
                                if ($label != '') {
                                    $str .= "<td column='$label:' style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td column='-:' style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                            }
                            else {
                                if ($label != '') {
                                    $str .= "<td column='$label:' style='$bgColor'></td>";
                                }
                                else {
                                    $str .= "<td column='-:' style='$bgColor'></td>";
                                }
                            }
                        }
                        else {
                            if ($label != '') {
                                $str .= "<td column='$label:' style='$bgColor'></td>";
                            }
                            else {
                                $str .= "<td column='-:' style='$bgColor'></td>";
                            }
                        }
                    }
                    $str .= "<td>$rekNameAlias</td>";
//                    $str .= "<td>--</td>";
                    $str .= "</tr>";
                }

                //region subTotal bawah category
//                $str .= "<tr style='background-color: #ffcece;' class='text-right text-bold text-uppercase'>";
//                $str .= "<td column='consulente:'>scl_$catName</td>";
//                $str .= "<td column='$catName'>$catName</td>";
//                foreach ($cabang as $cabID => $cabName) {
//                    $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
//                    foreach ($headers as $key => $label) {
//                        $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            if (is_numeric($coLStr)) {
//                                if ($coLStr > 0) {
//                                    $scatVal = number_format($coLStr, "0", ".", ",");
//                                    $align = "text-align:right;";
//                                }
//                                elseif ($coLStr < 0) {
//                                    $scatVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
//                                    $align = "text-align:right;";
//                                }
//                                else {
//                                    $scatVal = "";
//                                    $align = "text-align:right;";
//                                }
//                            }
//                            else {
//                                $scatVal = formatField($key, $coLStr);
//                            }
//                            $isExist_href = "<td style='font-size:1.3em;$bgColor$align'>";
//                            $isExist_href .= $scatVal;
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
//                    }
//                }
                foreach ($headers as $key => $label) {
//                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                    if ($key != "link") {
                        $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";

                        if (!isset($totalsBottomRight[$key])) {
                            $totalsBottomRight[$key] = 0;
                        }
                        $totalsBottomRight[$key] += $coLStr;
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
                    }
                    else {
//                        $str .= "<td style='$bgColor'></td>";
                    }
                }
//                $str .= "<td>&nbsp;</td>";
////                $str .= "<td>&nbsp;</td>";
//                $str .= "</tr>";
                //endregion

//                $str .= "</tbody>";
//                $str .= "</table class='table table-condensed'>";
//                $str .= "</div>";

//                $str .= "<script>\n
//                            $('#tbl_$catName').DataTable({
//                                dom: 'lBfrtip',
//                                fixedHeader: true,
//                                lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
//                                pageLength: -1,
//                                stateSave: false,
//                                paging: false,
//                                ordering: false,
//                                info: false,
//                                searching: false,
//                                buttons: [],
//                                autoWidth: false
//                            });
//                \n</script>";

//                $i++;
            }
            $str .= "</tbody>";
            //endregion


            //  region total bawah
//            $str .= "<table id='tbl_foot' class='table display compact table-bordered'>";
            $str .= "<tfoot>";
            $str .= "<tr style='background-color: #f0f0f0;' class='text-right text-bold'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";

                    $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {
                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'></td>";
                }
            }
            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</tfoot>";

//            $str .= "<tbody>";
//            $str .= "</tbody>";
//            $str .= "</table>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>
//                    $(document).ready( function(){
//                        var table = $('table#tbl_heads').DataTable({
//                            dom: 'lBfrtip',
//                            fixedHeader: true,
//                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
//                            pageLength: -1,
//                            stateSave: false,
//                            paging: false,
//                            ordering: false,
//                            info: false,
//                            searching: false,
//                            buttons: [ 'excel', 'print', 'pdf' ],
////                            footerCallback: function ( row, data, start, end, display ) {
////                                        var api = this.api(), data;
////                                        // Remove the formatting to get integer data for summation
////                                        var intVal = function ( i ) {
////                                            return typeof i === 'string' ?
////                                                i.replace(/[$,]/g, '')*1 :
////                                                typeof i === 'number' ?
////                                                    i : 0;
////                                        };
////
////                                        var arrayFooter = $('tfoot>tr>th');
////                                        var dpageTotal = [];
////                                        jQuery.each(arrayFooter, function(i,d){
////                                            var id_n_index = parseFloat(i);
////                                            dpageTotal[id_n_index] = 0;
////                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
////                                                var pos = obj.indexOf('<');
////                                                if(pos!==-1){
////                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
////                                                }
////                                                else{
////                                                }
////                                            });
////                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
////                                                $( api.column(id_n_index).footer() ).html(
////                                                    \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
////                                                );
////                                            }
////                                        });
////                                    }
//                                        });
//
//                                $('.table-responsive.tbl_head').floatingScroll();
////                                $('.table-responsive.tbl_head').scroll(
////                                    delay_v2(function () {
////                                        $('table#tbl_head').DataTable().fixedHeader.adjust();
////                                        console.log('adjust header');
////                                    }, 200)
////                                );
//                                        });

var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
    columnDefs: [
        { visible: false, targets: 0 }
    ],
    stateSave: false,
    autoWidth: false,
    bLengthChange : false, //hidden dropdown banyak data ditampilkan
    ordering: false, //disable order
    fixedHeader: true,
    searching: false, //hidden search form
    info: false, //hidden informasi bawah
    paging: false, //hidden tombol paging
    stateDuration: 60*60*24*365,
    displayLength: -1, //tampilkan semua data
    dom: 'lfTrtip',
    drawCallback: function ( settings ) {
    
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        var colonne = api.row(0).data().length;
        var totale = new Array();
            totale['Totale']= new Array();
        var groupid = -1;
        var subtotale = new Array();
        var arrCountGroup = new Array();
        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
            if(arrCountGroup[group]==undefined){
                arrCountGroup[group] = 0
            }
            arrCountGroup[group] += 1
        });
        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                if ( last !== group ) {
                    groupid++;
                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                    );
                    last = group;
                }
                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                $.each(val,function(index2,val2){
                    if (typeof subtotale[groupid] =='undefined'){
                        subtotale[groupid] = new Array();
                    }
                    if (typeof subtotale[groupid][index2] =='undefined'){
                        subtotale[groupid][index2] = 0;
                    }
                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }

                    valore = Number(val2.replace('€','').replace(',',''));

                    if(isNaN(valore)){
                        var testValorVal2 = accounting.unformat(val2);
                        valore = testValorVal2
                    }

                    subtotale[groupid][index2] += valore;
                    totale['Totale'][index2] += valore;
                });
            }
        });
        $('tbody').find('.group').each(function (i,v) {
            var rowCount = $(this).nextUntil('.group').length;
            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
            var subtd = '';
            for (var a=2;a<colonne;a++){
                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                    if(subtotale[i][a]*1<0){
                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                    }
                    else{
                        if(subtotale[i][a]==0){
                            subtd += \"<td></td>\";
                        }
                        else{
                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                        }
                    }
                }
                else{
                    subtd += '<td></td>';
                }
            }
            $(this).append(subtd);
        });
    }
});

// Collapse / Expand Click Groups
$('.grid tbody').on( 'click', 'tr.group', function () {
    var rowsCollapse = $(this).nextUntil('.group');
    $(rowsCollapse).toggleClass('hidden');
    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
});

$(window).resize(function() {
    oTable.draw(false)
                                });

                                $('.table-responsive.tbl_head').floatingScroll();
                                $('.table-responsive.tbl_head').scroll(
                                    delay_v2(function () {
      $('.grid').DataTable().fixedHeader.adjust();
                                    }, 200)
                                );

                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "viewRlBulanan":

        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");

        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";


        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select date </span>";
        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();

            foreach ($headers as $label) {
                $str .= "<th class='text-center' style='font-size:15px;'>$label</th>";
            }

            $str .= "</tr>";
            $str .= "</thead>";
            //endregion

            //region isi tabel
            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                $values_bulan_cat = 0;
                $values_bulan_range_cat = 0;
                $values_bulan_ytd_cat = 0;
                $catNumb++;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    $values_bulan = isset($rugilabaBulanIni[$rekName]['saldo']) ? $rugilabaBulanIni[$rekName]['saldo'] : 0;
                    $values_bulan_range = isset($rugilabaBulanRange[$rekName]['saldo']) ? $rugilabaBulanRange[$rekName]['saldo'] : 0;
                    $values_bulan_ytd = isset($rugilabaBulanYtd[$rekName]['saldo']) ? $rugilabaBulanYtd[$rekName]['saldo'] : 0;

                    if ($rekName == "rugilaba") {
                        $values_bulan = ($values_bulan * -1);
                        $values_bulan_range = ($values_bulan_range * -1);
                        $values_bulan_ytd = ($values_bulan_ytd * -1);
                    }
                    $values_bulan_cat += $values_bulan;
                    $values_bulan_range_cat += $values_bulan_range;
                    $values_bulan_ytd_cat += $values_bulan_ytd;

                    if ($values_bulan >= 0) {
//                        $values_bulan_f = formatField("debet", $values_bulan);
                        $values_bulan_f = number_format($values_bulan * 1, "0", ".", ",");
                        $styleBulan = "color:#000000;text-align:right;";
                    }
                    else {
                        $values_bulan_f = "(" . number_format($values_bulan * -1, "0", ".", ",") . ")";
                        $styleBulan = "color:red;text-align:right;";
                    }

                    if ($values_bulan_range >= 0) {
//                        $values_bulan_range_f = formatField("debet", $values_bulan_range);
                        $values_bulan_range_f = number_format($values_bulan_range * 1, "0", ".", ",");
                        $styleRange = "color:#000000;text-align:right;";
                    }
                    else {
                        $values_bulan_range_f = "(" . number_format($values_bulan_range * -1, "0", ".", ",") . ")";
                        $styleRange = "color:red;text-align:right;";
                    }

                    if ($values_bulan_ytd >= 0) {
//                        $values_bulan_ytd_f = formatField("debet", $values_bulan_ytd);
                        $values_bulan_ytd_f = number_format($values_bulan_ytd * 1, "0", ".", ",");
                        $styleYtd = "color:#000000;text-align:right;";
                    }
                    else {
                        $values_bulan_ytd_f = "(" . number_format($values_bulan_ytd * -1, "0", ".", ",") . ")";
                        $styleYtd = "color:red;text-align:right;";
                    }

                    if ($rekName == "rugilaba") {
                        $values_bulan_f = "";
                        $values_bulan_range_f = "";
                        $values_bulan_ytd_f = "";
                    }
                    $str .= "<td style='$styleBulan'>" . $values_bulan_f . "</td>";
                    $str .= "<td style='$styleRange'>" . $values_bulan_range_f . "</td>";
                    $str .= "<td style='$styleYtd'>" . $values_bulan_ytd_f . "</td>";

                    $str .= "</tr>";


                }

                if ($catNumb == $last) {
                    //region subTotal bawah category
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color:#f0f0f0;'>";
                    $tfoot .= "<th>-</th>";
                    $tfoot .= "<th style='text-align:left;font-size:1.3em;'>$catSubBottom</th>";

                    if ($values_bulan_cat >= 0) {
                        $values_bulan_cat_f = formatField("debet", $values_bulan_cat);
                        $styleBulan = "color:#000000;";
                    }
                    else {
                        $values_bulan_cat_f = "(" . number_format($values_bulan_cat * -1, "0", ".", ",") . ")";
                        $styleBulan = "color:red;text-align:right;";
                    }

                    if ($values_bulan_range_cat >= 0) {
                        $values_bulan_range_cat_f = formatField("debet", $values_bulan_range_cat);
                        $styleRange = "color:#000000;";
                    }
                    else {
                        $values_bulan_range_cat_f = "(" . number_format($values_bulan_range_cat * -1, "0", ".", ",") . ")";
                        $styleRange = "color:red;text-align:right;";
                    }

                    if ($values_bulan_ytd_cat >= 0) {
                        $values_bulan_ytd_cat_f = formatField("debet", $values_bulan_ytd_cat);
                        $styleYtd = "color:#000000;";
                    }
                    else {
                        $values_bulan_ytd_cat_f = "(" . number_format($values_bulan_ytd_cat * -1, "0", ".", ",") . ")";
                        $styleYtd = "color:red;text-align:right;";
                    }

                    $tfoot .= "<th style='$styleBulan font-size:1.3em;'>" . $values_bulan_cat_f . "</th>";
                    $tfoot .= "<th style='$styleRange font-size:1.3em;'>" . $values_bulan_range_cat_f . "</th>";
                    $tfoot .= "<th style='$styleYtd font-size:1.3em;'>" . $values_bulan_ytd_cat_f . "</th>";
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                    //endregion
                }
            }


            $str .= "</tbody>";

            $str .= $tfoot;
            //endregion

            $str .= "</table>";
            $str .= "</div>";


            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Export to Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','$cabang_nama-$title-$defaultDate');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "viewRlTahunan":

        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        $str .= "<span class='input-group-add-on' >select year </span>";
        // $str .= "<input type='year' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        $str .= "<select class='form-control' onchange=\"location . href = '$thisPage?gr=$gr&date=' + this . value;\">";
        $str .= "<option value=''>--pilih tahun--</option>";
        // cekBiru($tahunDipilih);
        for ($i = 2015; $i < dtimeNow('Y'); $i++) {
            $selected_date = $i == $tahunDipilih ? "selected" : "";
            $str .= "<option $selected_date>$i</option>";
        }

        $str .= "</select>";
        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();

            foreach ($headers as $label) {
                $str .= "<th class='text-center' style='font-size:15px;'>$label</th>";
            }
            // $str .= "</td>";
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion

            //region isi tabel
            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                $values_bulan_cat = 0;
                $values_bulan_range_cat = 0;
                $values_bulan_ytd_cat = 0;
                $catNumb++;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    $values_bulan = isset($rugilabaBulanIni[$rekName]['saldo']) ? $rugilabaBulanIni[$rekName]['saldo'] : 0;
                    $values_bulan_range = isset($rugilabaBulanRange[$rekName]['saldo']) ? $rugilabaBulanRange[$rekName]['saldo'] : 0;
                    $values_bulan_ytd = isset($rugilabaBulanYtd[$rekName]['saldo']) ? $rugilabaBulanYtd[$rekName]['saldo'] : 0;

                    if ($rekName == "rugilaba") {
                        $values_bulan = ($values_bulan * -1);
                        $values_bulan_range = ($values_bulan_range * -1);
                        $values_bulan_ytd = ($values_bulan_ytd * -1);
                    }
                    $values_bulan_cat += $values_bulan;
                    $values_bulan_range_cat += $values_bulan_range;
                    $values_bulan_ytd_cat += $values_bulan_ytd;

                    if ($values_bulan >= 0) {
//                        $values_bulan_f = formatField("debet", $values_bulan);
                        $values_bulan_f = number_format($values_bulan * 1, "0", ".", ",");
                        $styleBulan = "color:#000000;text-align:right;";
                    }
                    else {
                        $values_bulan_f = "(" . number_format($values_bulan * -1, "0", ".", ",") . ")";
                        $styleBulan = "color:red;text-align:right;";
                    }

                    if ($values_bulan_range >= 0) {
//                        $values_bulan_range_f = formatField("debet", $values_bulan_range);
                        $values_bulan_range_f = number_format($values_bulan_range * 1, "0", ".", ",");
                        $styleRange = "color:#000000;text-align:right;";
                    }
                    else {
                        $values_bulan_range_f = "(" . number_format($values_bulan_range * -1, "0", ".", ",") . ")";
                        $styleRange = "color:red;text-align:right;";
                    }

                    if ($values_bulan_ytd >= 0) {
//                        $values_bulan_ytd_f = formatField("debet", $values_bulan_ytd);
                        $values_bulan_ytd_f = number_format($values_bulan_ytd * 1, "0", ".", ",");
                        $styleYtd = "color:#000000;text-align:right;";
                    }
                    else {
                        $values_bulan_ytd_f = "(" . number_format($values_bulan_ytd * -1, "0", ".", ",") . ")";
                        $styleYtd = "color:red;text-align:right;";
                    }

                    if ($rekName == "rugilaba") {
                        $values_bulan_f = "";
                        $values_bulan_range_f = "";
                        $values_bulan_ytd_f = "";
                    }
                    $str .= "<td style='$styleBulan'>" . $values_bulan_f . "</td>";
                    // $str .= "<td style='$styleRange;'>" . $values_bulan_range_f . "</td>";
                    $str .= "<td style='$styleYtd'>" . $values_bulan_ytd_f . "</td>";

                    $str .= "</tr>";


                }

                if ($catNumb == $last) {
                    //region subTotal bawah category
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color:#f0f0f0;'>";
                    $tfoot .= "<th>-</th>";
                    $tfoot .= "<th style='text-align:left;font-size:1.3em;'>$catSubBottom</th>";

                    if ($values_bulan_cat >= 0) {
                        $values_bulan_cat_f = formatField("debet", $values_bulan_cat);
                        $styleBulan = "color:#000000;";
                    }
                    else {
                        $values_bulan_cat_f = "(" . number_format($values_bulan_cat * -1, "0", ".", ",") . ")";
                        $styleBulan = "color:red;text-align:right;";
                    }

                    if ($values_bulan_range_cat >= 0) {
                        $values_bulan_range_cat_f = formatField("debet", $values_bulan_range_cat);
                        $styleRange = "color:#000000;";
                    }
                    else {
                        $values_bulan_range_cat_f = "(" . number_format($values_bulan_range_cat * -1, "0", ".", ",") . ")";
                        $styleRange = "color:red;text-align:right;";
                    }

                    if ($values_bulan_ytd_cat >= 0) {
                        $values_bulan_ytd_cat_f = formatField("debet", $values_bulan_ytd_cat);
                        $styleYtd = "color:#000000;";
                    }
                    else {
                        $values_bulan_ytd_cat_f = "(" . number_format($values_bulan_ytd_cat * -1, "0", ".", ",") . ")";
                        $styleYtd = "color:red;text-align:right;";
                    }

                    $tfoot .= "<th style='$styleBulan font-size:1.3em;'>" . $values_bulan_cat_f . "</th>";
                    // $str .= "<td style='$styleRange;font-size:1.3em;'>" . $values_bulan_range_cat_f . "</td>";
                    $tfoot .= "<th style='$styleYtd font-size:1.3em;'>" . $values_bulan_ytd_cat_f . "</th>";
                    $tfoot .= "</tr>";

                    if ($catCtr < (count($categories) - 2)) {
                        $tfoot .= "<tr>";
                        $tfoot .= "<th style='font-size:1.3em;'>&nbsp;</th>";
                        $tfoot .= "<th style='font-size:1.3em;'>&nbsp;</th>";
                        // $str .= "<td style='font-size:1.3em;'>&nbsp;</td>";
                        $tfoot .= "<th style='font-size:1.3em;'>&nbsp;</th>";
                        $tfoot .= "</tr>";
                    }
                    $tfoot .= "</tfoot>";
                    //endregion

                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;
            //endregion

            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Export to Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','$cabang_nama-$title-$tahunDipilih');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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
    case "viewNeracaKoreksi":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        // $linkExcel = base_url(). "ExcelWriter/neraca";

        $excel_data = "date=$defaultDate";
        $excel_name = "neraca $defaultDate";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";

        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";

        if (isset($dateSelector) && ($dateSelector == true)) {
            $str .= "<span class='input-group-add-on' >select month </span>";
            $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        }

        if (isset($linkExcel)) {
            $str .= "&nbsp; <button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
            $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
        }

        if (isset($buttonMode) && ($buttonMode['enabled'] == true)) {
            $btn_label = $buttonMode['label'];
            $btn_link = $buttonMode['link'];
            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"location.href='$btn_link?$grMenu" . "n=1'\" title='download data'><i class='fa fa-bookmark-o'></i> $btn_label</button>";
        }

        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";

        if (count($categories) > 0) {
            $nullChecker = 0;
            foreach ($categories as $catName) {
                if (isset($rekeningsName[$catName]) && sizeof($rekeningsName[$catName]) > 0) {
                    foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                        $nullChecker++;
                    }
                }
            }

            if ($nullChecker) {

                foreach ($headers as $key => $label) {
                    $totals[$key] = 0;
                }

                $str .= "<div class='table-responsive tbl_head'>";
                $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

                $str .= "<thead>";

                //dummy header jangan di hapus untuk keperluan javascript
                $str .= "<tr bgcolor='#e5e5e5' class='text-center'>";
                $str .= "<th></th>";
                $str .= "<th></th>";
                foreach ($headers as $key => $label) {
                    $str .= "<th></th>";
                }
                $str .= "</tr>";


                //header utama
                $str .= "<tr bgcolor='#e5e5e5' class='text-center'>";
                $str .= "<th></th>";
                $str .= "<th></th>";

                foreach ($mainHeaders as $key => $label) {
                    if ($key != "link") {
                        $colspan = $key == "koreksi" ? $totalKoreksi * 2 : 2;
                        $isExist_href = "<th class='text-center text-uppercase' colspan='$colspan'>";
                        $isExist_href .= "$label";
                        $isExist_href .= "</th>";
                    }
                    else {
                        $isExist_href = "<th>";
                        $isExist_href .= "$label";
                        $isExist_href .= "</th>";
                    }

                    $str .= "$isExist_href";
                }

                $str .= "</tr>";

                //sub header
                $str .= "<tr bgcolor='#e5e5e5' class='text-center'>";
                $str .= "<th></th>";
                $str .= "<th></th>";

                foreach ($headers as $key => $label) {
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<th class='text-center text-uppercase'>$label</th>";
                    }
                    else {
                        $isExist_href = "<th class='text-center text-uppercase'>" . $label;
                        $isExist_href .= "</th>";
                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";
                $str .= "</thead>";

                $str .= "<tbody>";

                $trChecker = 0;
                foreach ($categories as $catName) {
                    foreach ($headers as $key => $label) {
                        $subTotals[$key] = 0;
                    }

                    if (isset($rekeningsName[$catName]) && sizeof($rekeningsName[$catName]) > 0) {
                        foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                            if (($rekenings[$catName][$rekName]['debet'] > 0) && ($rekenings[$catName][$rekName]['kredit'] > 0)) {
                                $val_detail = $rekenings[$catName][$rekName]['debet'] - $rekenings[$catName][$rekName]['kredit'];
                                if ($val_detail > 0) {
                                    $rekenings[$catName][$rekName]['debet'] = $val_detail;
                                    $rekenings[$catName][$rekName]['kredit'] = 0;
                                }
                                else {
                                    $rekenings[$catName][$rekName]['debet'] = 0;
                                    $rekenings[$catName][$rekName]['kredit'] = $val_detail * -1;
                                }
                            }

                            $rek = $rekName;
                            $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                            $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                            $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                            $str .= "<tr>";
                            $str .= "<td column='consulente:'>$catName</td>"; //jangan di hapus, keperluan javascript
                            $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                            foreach ($headers as $key => $label) {
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                                }
                                else {
                                    if (isset($values[$key])) {
                                        if (is_numeric($values[$key])) {
                                            if ($values[$key] >= 0) {
                                                $rVal = number_format($values[$key] * 1, "0", ".", ",");
                                                $style = "style='text-align:right;'";
                                            }
                                            else {
                                                $rVal = "<r>(" . number_format($values[$key] * -1, "0", ".", ",") . ")</r>";
                                                $style = "style='text-align:right;'";
                                            }
                                        }
                                        else {
                                            $rVal = formatField($key, $values[$key]);
                                            $style = "";
                                        }
                                    }
                                    else {
                                        $rVal = "";
                                        $style = "";
                                    }
                                    $isExist_href = "<td $style>$rVal";
                                    $isExist_href .= "</td>";

                                }
                                $str .= "$isExist_href";

                                if (array_key_exists($key, $totals)) {
                                    $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
                                    $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;
                                }
                            }
                            $str .= "</tr>";
                            $trChecker++;
                        }
                    }

                }
                $str .= "</tbody>";

                $str .= "<tfoot>";
                $str .= "<tr bgcolor='#e5e5e5'>";
                $str .= "<th></th>";
                $str .= "<th></th>";
                foreach ($headers as $key => $label) {
                    $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<th style='font-size:1.3em;' class='text-right'>" . formatField($key, $coLStr) . "</th>";
                    }
                    else {
                        $isExist_href = "<th style='font-size:1.3em;' class='text-right'>" . formatField($key, $coLStr);
                        $isExist_href .= "</th>";
                    }
                    $str .= "$isExist_href";
                }
                $str .= "</tr>";
                $str .= "</tfoot>";

                $str .= "</table>";
                $str .= "</div>";

                $str .= "<script>

                    var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                        columnDefs: [
                            { visible: false, targets: 0 }
                        ],
                        stateSave: false,
                        autoWidth: false,
                        bLengthChange : false, //hidden dropdown banyak data ditampilkan
                        ordering: false, //disable order
                            fixedHeader: true,
                        searching: false, //hidden search form
                        info: false, //hidden informasi bawah
                        paging: false, //hidden tombol paging
                        stateDuration: 60*60*24*365,
                        displayLength: -1, //tampilkan semua data
                        dom: 'lfTrtip',
                        drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                            var api = this.api();
                            var rows = api.rows( {page:'current'} ).nodes();
                            var last=null;
                            var colonne = api.row(0).data().length;
                            var totale = new Array();
                                totale['Totale']= new Array();
                            var groupid = -1;
                            var subtotale = new Array();
                            var arrCountGroup = new Array();
                            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                                if(arrCountGroup[group]==undefined){
                                    arrCountGroup[group] = 0
                                }
                                arrCountGroup[group] += 1
                            });
                            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                                if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                    if ( last !== group ) {
                                        groupid++;
                                        $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                            \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                        );
                                        last = group;
                                    }
                                    val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                    $.each(val,function(index2,val2){
                                        if (typeof subtotale[groupid] =='undefined'){
                                            subtotale[groupid] = new Array();
                                        }
                                        if (typeof subtotale[groupid][index2] =='undefined'){
                                            subtotale[groupid][index2] = 0;
                                        }
                                        if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                        valore = Number(val2.replace('€','').replace(',',''));
                                        if(isNaN(valore)){
                                            var testValorVal2 = accounting.unformat(val2);
                                            valore = testValorVal2
                                        }
                                        subtotale[groupid][index2] += removeDecimal(valore);
                                        totale['Totale'][index2] += removeDecimal(valore);

                                    });
                                }
                            });
                            $('tbody').find('.group').each(function (i,v) {
                                var rowCount = $(this).nextUntil('.group').length;
                               $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                                var subtd = '';
                                for (var a=2;a<colonne;a++){
                                    if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                        if(subtotale[i][a]*1<0){
                                            subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                            if(subtotale[i][a]==0){
                                                subtd += \"<td></td>\";
                                                }
                                            else{
                                                subtd += \"<td align='right'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                        }
                                    }
                                    else{
                                        subtd += '<td></td>';
                                    }
                                }
                                $(this).append(subtd);
                                        });
                                    }
                                });
                    // Collapse / Expand Click Groups
                    $('.grid tbody').on( 'click', 'tr.group', function () {
                        var rowsCollapse = $(this).nextUntil('.group');
                        $(rowsCollapse).toggleClass('hidden');
                        $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                        $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                    });
                    $(window).resize(function() {
                        oTable.draw(false)
                            });

                    $('.table-responsive.tbl_head').floatingScroll();
                    $('.table-responsive.tbl_head').scroll(
                      delay_v2(function () {
                          $('.grid').DataTable().fixedHeader.adjust();
                      }, 200)
                    );
                    </script>";
                if (show_debuger() == 1) {
                    $dbet = $totals['debet'];
                    $kdit = $totals['kredit'];
                    $crossBalance = $dbet - $kdit;

                    $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
                }
            }
            else {
                $str .= "neraca is not yet available.<br>start making any transaction so this page has a content<br>or you can change month/year parameter above";
            }

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content<br>or you can change month/year parameter above";
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
            $str .= "<td>-</td>";
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
                        $target = base_url() . "Neraca/detailCashflow/$rekID?date=$defaultDate";

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
//                $str .= "<tr>";
//                $str .= "<td style='background-color:red;'></td>";
//                $str .= "<td style='background-color:red;'>$summaryTotal != $selisihKas [$selisih]</td>";
//                $str .= "</tr>";
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
            $str .= $underMaintenance;
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
                "underMaintenance" => $underMaintenance,
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

    //UTAMA----------------------------
    case "lapKeuanganKonsolidasian":

//        $pakai_ini = $pakai_konsolidasi;
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
//        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        switch ($periode) {
            case "bulanan":
                $str .= "<div class='panel-body alert alert-info-dot'>";
                $str .= "<div class='input-group'>";
                $str .= "<span class='input-group-add-on' >select date </span>";
                $str .= "<input type='month' value='$defaultDate' min='$oldDate' 
            max='" . date("Y-m") . "' 
            onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
                $str .= "</div class='input-group'>";
                $str .= "</div class='panel-body'>";
                break;
            case "tahunan":
                $str .= "<div class='panel-body alert alert-info-dot'>";
                $str .= "<div class='input-group'>";
                $str .= "<span class='input-group-add-on' >select year </span>";
                $str .= $p->selectTahun($defaultDate, "date");
                $str .= "</div class='input-group'>";
                $str .= "</div class='panel-body'>";
                break;
            case "ytd":
                break;
        }




        if (isset($neraca_adj)) {
            $str .= "";
            $str .= "<div id='neraca_adj' class='col-md-12'></div>";
            $str .= "<script>$('#neraca_adj').load('$neraca_adj');</script>";
        }
        if (isset($rugilaba_adj)) {
            $str .= "";
            $str .= "<div id='rugilaba_adj' class='col-md-12'></div>";
            $str .= "<script>$('#rugilaba_adj').load('$rugilaba_adj');</script>";
        }
        if (isset($neraca)) {
            $str .= "";
            $str .= "<div id='neraca' class='col-md-12'></div>";
            $str .= "<script>$('#neraca').load('$neraca');</script>";
        }
        if (isset($rugilaba)) {
            $str .= "";
            $str .= "<div id='rugilaba' class='col-md-12'></div>";
            $str .= "<script>$('#rugilaba').load('$rugilaba');</script>";
        }
        if (isset($cashflow)) {
            $str .= "";
            $str .= "<div id='cashflow' class='col-md-12'></div>";
            $str .= "<script>$('#cashflow').load('$cashflow');</script>";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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
    case "lapKeuangan":

//        $pakai_ini = $pakai_konsolidasi;
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
//        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        switch ($periode) {
            case "bulanan":
                $str .= "<div class='panel-body alert alert-info-dot'>";
                $str .= "<div class='input-group'>";
                $str .= "<span class='input-group-add-on' >select date </span>";
                $str .= "<input type='month' value='$defaultDate' min='$oldDate' 
            max='" . date("Y-m") . "' 
            onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";

                $str .= "</div class='input-group'>";
                $str .= "</div class='panel-body'>";
                break;
            case "tahunan":
                $str .= "<div class='panel-body alert alert-info-dot'>";
                $str .= "<div class='input-group'>";
                $str .= "<span class='input-group-add-on' >select year </span>";
                $str .= $p->selectTahun($defaultDate, "date");

                $str .= "</div class='input-group'>";
                $str .= "</div class='panel-body'>";
                break;
        }


        if (isset($neraca)) {
            $str .= "";
            $str .= "<div id='neraca' class='col-md-12'></div>";
            $str .= "<script>$('#neraca').load('$neraca');</script>";
        }
        if (isset($rugilaba)) {
            $str .= "";
            $str .= "<div id='rugilaba' class='col-md-12'></div>";
            $str .= "<script>$('#rugilaba').load('$rugilaba');</script>";
        }
        if (isset($cashflow)) {
            $str .= "";
            $str .= "<div id='cashflow' class='col-md-12'></div>";
            $str .= "<script>$('#cashflow').load('$cashflow');</script>";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    //per-cabang----------------------------
    case "keuangan_neraca":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/default.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        $str .= "<h4>$subTitle</h4>";
        if (sizeof($categories) > 0) {

            foreach ($headers as $key => $label) {
                $totals[$key] = 0;
            }
            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid nowrap compact'>";

            $str .= "<thead>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($headers as $key => $label) {
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<th>$label</th>";
                }
                else {
                    $isExist_href = "<th>" . $label;
                    $isExist_href .= "</th>";
                }

                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</thead>";

            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($headers as $key => $label) {
                    $subTotals[$key] = 0;
                }
                if (isset($rekeningsName[$catName]) && sizeof($rekeningsName[$catName]) > 0) {
                    foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                        foreach ($arrTahun as $thn_header) {

                            if (isset($rekenings[$catName][$rekName]['debet_' . $thn_header]) && ($rekenings[$catName][$rekName]['debet_' . $thn_header] > 0)
                                && isset($rekenings[$catName][$rekName]['kredit_' . $thn_header]) && ($rekenings[$catName][$rekName]['kredit_' . $thn_header] > 0)) {
                                $val_detail = $rekenings[$catName][$rekName]['debet_' . $thn_header] - $rekenings[$catName][$rekName]['kredit_' . $thn_header];
                                if ($val_detail > 0) {
                                    $rekenings[$catName][$rekName]['debet_' . $thn_header] = $val_detail;
                                    $rekenings[$catName][$rekName]['kredit_' . $thn_header] = 0;
                                }
                                else {
                                    $rekenings[$catName][$rekName]['debet_' . $thn_header] = 0;
                                    $rekenings[$catName][$rekName]['kredit_' . $thn_header] = $val_detail * -1;
                                }
                            }
                        }
                        $rek = $rekName;
                        $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                        $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                        $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                        $str .= "<tr>";
                        $str .= "<td column='consulente:'>$catName</td>";
                        $str .= "<td column='$catName:'><span data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rekNameAlias</span></td>";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                            }
                            else {
                                if (isset($values[$key])) {
                                    if (is_numeric($values[$key])) {
                                        if ($values[$key] >= 0) {
                                            $rVal = number_format($values[$key] * 1, "0", ".", ",");
                                            $style = "style='text-align:right;'";
                                        }
                                        else {
                                            $rVal = "<r>(" . number_format($values[$key] * -1, "0", ".", ",") . ")</r>";
                                            $style = "style='text-align:right;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $values[$key]);
                                        $style = "";
                                    }
                                }
                                else {
                                    $rVal = "";
                                    $style = "";
                                }
                                $isExist_href = "<td $style>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $str .= "$isExist_href";

                            if (array_key_exists($key, $totals)) {
                                $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
                                $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;

                            }
                        }
                        $str .= "</tr>";

                    }

//                    $str .= "<tr style='background-color:#f0f0f0;'>";
//                    $str .= "<td></td>";
//                    $str .= "<td></td>";
//                    foreach ($headers as $key => $label) {
//                        $coLStr = isset($subTotals[$key]) ? $subTotals[$key] : "";
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            if (is_numeric($coLStr)) {
//                                if ($coLStr >= 0) {
//                                    $rVal = number_format($coLStr*1, "0", ".", ",");
//                                    $style = "style='font-size:1.3em;text-align:right;'";
//                                }
//                                else {
//                                    $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
//                                    $style = "style='font-size:1.3em;text-align:right;'";
//                                }
//                            }
//                            else {
//                                $rVal = formatField($key, $coLStr);
//                                $style = "style='font-size:1.3em;text-align:right;'";
//                            }
//                            $isExist_href = "<td $style>" . $rVal;
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
//                    }
//                    $str .= "</tr>";
//
//                    $str .= "<tr>";
//
//                    foreach ($headers as $key => $label) {
//                        $str .= "<td style='font-size:1em;'></td>";
//                        }
//
//                    $str .= "</tr>";
                }
            }
            $str .= "</tbody>";

            $str .= "<tfoot>";
            $str .= "<tr bgcolor='#e5e5e5'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($headers as $key => $label) {
                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<th style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</th>";
                }
                else {
                    $isExist_href = "<th style='font-size:1.3em;'>" . formatField($key, $coLStr);
                    $isExist_href .= "</th>";
                }
                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</tfoot>";

            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip',
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            if (show_debuger() == 1) {
//                $dbet = $totals['debet'];
//                $kdit = $totals['kredit'];
//                $crossBalance = $dbet - $kdit;
//
//                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
            }
        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;

        break;
    case "keuangan_neraca_ytd":

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        // $linkExcel = base_url(). "ExcelWriter/neraca";
        $excel_data = "date=$defaultDate";
        $excel_name = "neraca $defaultDate";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
        $str .= "<div class='panel-body alert alert-info-dot'>";
        $str .= "<div class='input-group'>";
        if (isset($dateSelector) && ($dateSelector == true)) {

            $str .= "<span class='input-group-add-on' >select month </span>";
            $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        }
        if (isset($linkExcel)) {

            $str .= "&nbsp; <button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
            $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
        }
        if (isset($buttonMode) && ($buttonMode['enabled'] == true)) {
            $btn_label = $buttonMode['label'];
            $btn_link = $buttonMode['link'];
            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"location.href='$btn_link?$grMenu" . "n=1'\" title='download data'><i class='fa fa-bookmark-o'></i> $btn_label</button>";
        }

        $str .= "</div class='input-group'>";
        $str .= "</div class='panel-body'>";
        if (sizeof($categories) > 0) {

            foreach ($headers as $key => $label) {
                $totals[$key] = 0;
            }
            $str .= "<h4>$subTitle</h4>";
            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid neraca nowrap compact'>";

            $str .= "<thead>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($headers as $key => $label) {
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<th>$label</th>";
                }
                else {
                    $isExist_href = "<th>" . $label;
                    $isExist_href .= "</th>";
                }

                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</thead>";

            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($headers as $key => $label) {
                    $subTotals[$key] = 0;
                }
                if (isset($rekeningsName[$catName]) && sizeof($rekeningsName[$catName]) > 0) {
                    foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                        if (($rekenings[$catName][$rekName]['debet'] > 0) && ($rekenings[$catName][$rekName]['kredit'] > 0)) {
                            $val_detail = $rekenings[$catName][$rekName]['debet'] - $rekenings[$catName][$rekName]['kredit'];
                            if ($val_detail > 0) {
                                $rekenings[$catName][$rekName]['debet'] = $val_detail;
                                $rekenings[$catName][$rekName]['kredit'] = 0;
                            }
                            else {
                                $rekenings[$catName][$rekName]['debet'] = 0;
                                $rekenings[$catName][$rekName]['kredit'] = $val_detail * -1;
                            }
                        }
                        $rek = $rekName;
                        $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                        $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                        $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                        $str .= "<tr>";
                        $str .= "<td column='consulente:'>$catName</td>";
                        $str .= "<td column='$catName:'><span data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rekNameAlias</span></td>";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                            }
                            else {
                                if (isset($values[$key])) {
                                    if (is_numeric($values[$key])) {
                                        if ($values[$key] >= 0) {
                                            $rVal = number_format($values[$key] * 1, "0", ".", ",");
                                            $style = "style='text-align:right;'";
                                        }
                                        else {
                                            $rVal = "<r>(" . number_format($values[$key] * -1, "0", ".", ",") . ")</r>";
                                            $style = "style='text-align:right;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $values[$key]);
                                        $style = "";
                                    }
                                }
                                else {
                                    $rVal = "";
                                    $style = "";
                                }
                                $isExist_href = "<td $style>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $str .= "$isExist_href";

                            if (array_key_exists($key, $totals)) {
                                $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
                                $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;

                            }
                        }
                        $str .= "</tr>";

                    }

//                    $str .= "<tr style='background-color:#f0f0f0;'>";
//                    $str .= "<td></td>";
//                    $str .= "<td></td>";
//                    foreach ($headers as $key => $label) {
//                        $coLStr = isset($subTotals[$key]) ? $subTotals[$key] : "";
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            if (is_numeric($coLStr)) {
//                                if ($coLStr >= 0) {
//                                    $rVal = number_format($coLStr*1, "0", ".", ",");
//                                    $style = "style='font-size:1.3em;text-align:right;'";
//                                }
//                                else {
//                                    $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
//                                    $style = "style='font-size:1.3em;text-align:right;'";
//                                }
//                            }
//                            else {
//                                $rVal = formatField($key, $coLStr);
//                                $style = "style='font-size:1.3em;text-align:right;'";
//                            }
//                            $isExist_href = "<td $style>" . $rVal;
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
//                    }
//                    $str .= "</tr>";
//
//                    $str .= "<tr>";
//
//                    foreach ($headers as $key => $label) {
//                        $str .= "<td style='font-size:1em;'></td>";
//                        }
//
//                    $str .= "</tr>";
                }
            }
            $str .= "</tbody>";

            $str .= "<tfoot>";
            $str .= "<tr bgcolor='#e5e5e5'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            foreach ($headers as $key => $label) {
                $coLStr = isset($totals[$key]) ? $totals[$key] : "";
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<th style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</th>";
                }
                else {
                    $isExist_href = "<th style='font-size:1.3em;'>" . formatField($key, $coLStr);
                    $isExist_href .= "</th>";
                }
                $str .= "$isExist_href";
            }
            $str .= "</tr>";
            $str .= "</tfoot>";

            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.neraca').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip',
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('.neraca tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.neraca tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            if (show_debuger() == 1) {
                $dbet = $totals['debet'];
                $kdit = $totals['kredit'];
                $crossBalance = $dbet - $kdit;

                $str .= "<div class='text-red tex-miring'>" . formatField('number', $crossBalance) . " = " . formatField('number', $dbet) . " - " . formatField('number', $kdit) . "</div>";
            }
        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;

        break;

    case "keuangan_rugilaba":
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

//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        if (isset($dateSelector) && ($dateSelector == true)) {
//
//            $str .= "<span class='input-group-add-on' >select year </span>";
//            // $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//            $str .= "<select class='form-control' onchange=\"location . href = '$thisPage?gr=$gr&date=' + this . value;\">";
//            $str .= "<option value=''>--pilih tahun--</option>";
//            // cekBiru($tahunDipilih);
//            for ($i = 2015; $i < dtimeNow('Y'); $i++) {
//                $selected_date = $i == $tahunDipilih ? "selected" : "";
//                $str .= "<option $selected_date>$i</option>";
//            }
//
//            $str .= "</select>";
//        }
//
//        if (isset($linkExcel)) {
//
//            // $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
//            // $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
//        }
//
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        $str .= "<h4>$subTitle</h4>";
        if (sizeof($categories) > 0) {
            foreach ($headersTahun as $header_thn) {
                $totals[$header_thn] = 0;
            }
            $str .= "<table class='table datatables table-condensed table-bordered'>";
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td>&nbsp;</td>";
            foreach ($headers as $key => $label) {
//                cekHere("$key => $label");
                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                    $isExist_href = $key == "link" ? "" : "<td>$label</td>";
                }
                else {
//                    if (!is_numeric($label)) {
                    $hLabel = $label;
//                    }
//                    else {
//                        $hLabel = "";
//                    }
                    $isExist_href = "<td>" . $hLabel;
                    $isExist_href .= "</td>";
                }
                $str .= "$isExist_href";
            }
            $str .= "</tr>";

            $str .= "</thead>";
//arrPrintPink($rekenings);
            $str .= "<tbody>";
            foreach ($categories as $catName) {
                //                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                foreach ($headersTahun as $header_thn) {
                    $subTotals[$header_thn] = 0;
                }


                //                $str .= "<tr bgcolor='#f0f0f0'>";
                //                $str .= "<td>&nbsp;</td>";
                //                foreach ($headers as $key => $label) {
                //                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                //
                //                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";
                //
                //                    }
                //                    else {
                //                        if (!is_numeric($label)) {
                //                            $hLabel = $label;
                //                        }
                //                        else {
                //                            $hLabel = "";
                //                        }
                //                        $isExist_href = "<td>" . $hLabel;
                //                        $isExist_href .= "</td>";
                //
                //                    }
                //
                //                    $str .= "$isExist_href";
                //                }
                //                $str .= "</tr>";

                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rek = $rekName;
//                    $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td>$rekNameAlias_f</td>";
                    foreach ($headers as $key => $label) {
                        $values = isset($rekenings[$key][$catName][$rekName]) ? $rekenings[$key][$catName][$rekName] : 0;
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                        }
                        else {
                            if (is_numeric($values[$key])) {
                                if ($values[$key] >= 0) {
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "style='text-align:right;'";
                                }
                                else {
                                    $rVal = "(" . number_format($values[$key] * -1, "0", ".", ",") . ")";
                                    $style = "style='text-align:right;color:red;'";
                                }
                            }
                            else {
                                $rVal = formatField($key, $values[$key]);
                                $style = "";
                            }
                            $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                            $isExist_href = "<td $style>$rVal_f";
                            $isExist_href .= "</td>";

                        }
                        $str .= "$isExist_href";

                        if (array_key_exists($key, $totals)) {
                            $totals[$key] += $values[$key];
                            $subTotals[$key] += $values[$key];
                        }
                    }
                    $str .= "</tr>";

                }

                $str .= "<tr style='background-color: #f0f0f0;'>";
                $catBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                $str .= "<td style='font-size:1.3em;'>$catBottom</td>";
                foreach ($headers as $key => $label) {
                    if (isset($subTotals[$key])) {
                        $coLStr = $subTotals[$key];
                    }
                    else {
                        $coLStr = "";
                    }
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";
                    }
                    else {
                        if (is_numeric($coLStr)) {
                            if ($coLStr >= 0) {
                                $rVal = formatField($key, $coLStr);
                                $style = "style='text-align:right;font-size:1.3em;'";
                            }
                            else {
                                //                                    $rVal = formatField($key, $coLStr*-1);
                                $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                $style = "style='text-align:right;color:red;font-size:1.3em;'";
                            }
                        }
                        else {
                            $rVal = formatField($key, $coLStr);
                            $style = "style='font-size:1.3em;'";
                        }
                        $isExist_href = "<td $style>$rVal";
                        $isExist_href .= "</td>";

                    }

                    $str .= "$isExist_href";
                }
                $str .= "</tr>";

                //                }
            }
            $str .= "</tbody>";


            $str .= "</table class='table table-condensed'>";

            $str .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            stateSave: false,
                            paging: false,
                            ordering: false,
                            info: false,
                            searching: false,
//                            buttons: [
//                                        { extend: 'print', footer: true },
// 
//                                    ],
                                      buttons: [
                                          //'excel', 
                                          'print', 
                                          'pdf'
                                          
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

                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
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


                                //new $.fn.dataTable.FixedHeader( table );
                                $('.table-responsive').floatingScroll();
                            });


                    </script>";
        }
        else {
            $str .= "profit and lost is not yet available.<br>start making any transaction so this page has a content";
        }
        echo $str;
        break;
    case "keuangan_rugilaba_ytd":
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


//arrPrintWebs($rekenings);
        $str .= "<h4>$subTitle</h4>";
        if (sizeof($categories) > 0) {
//            $totals = array(
//                "values_before_koreksi" => 0,
//                "values_koreksi" => 0,
//                "values" => 0,
//            );
            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid rugilaba nowrap compact'>";

            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
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
            foreach ($categories as $catName) {
                //                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
//                $subTotals = array(
//                    "values_before_koreksi" => 0,
//                    "values_koreksi" => 0,
//                    "values" => 0,
//                );

                $catBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $catNumb++;
                foreach ($headers as $key => $label) {
                    $subTotals[$key] = 0;
                }


//                $str .= "<tr bgcolor='#f0f0f0'>";
//                $str .= "<td>&nbsp;</td>";
//                foreach ($headers as $key => $label) {
//                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//
//                        $isExist_href = $key == "link" ? "" : "<td>$label</td>";
//
//                    }
//                    else {
//                        if (!is_numeric($label)) {
//                            $hLabel = $label;
//                        }
//                        else {
//                            $hLabel = "";
//                        }
//                        $isExist_href = "<td>" . $hLabel;
//                        $isExist_href .= "</td>";
//
//                    }
//
//                    $str .= "$isExist_href";
//                }
//                $str .= "</tr>";
                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rek = $rekName;
                    $values = isset($rekenings[$catName][$rekName]) ? $rekenings[$catName][$rekName] : 0;
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $firstRow = $numRest == 0 ? "first_row" : "";

                    $str .= "<tr class='$firstRow'>";
                    $str .= "<td column='consulente:'>$catBottom</td>";
                    $str .= "<td column='$catBottom:'>$rekNameAlias_f</td>";

                    foreach ($headers as $key => $label) {

                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td>" . formatField($key, $values[$key]) . "</td>";
                        }
                        else {
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
                                }
                                else {
                                    $rVal = formatField($key, $values[$key]);
                                    $style = "";
                                }
                            }
                            else {
                                $rVal = "";
                                $style = "";
                            }
                            $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                            $isExist_href = "<td $style>$rVal_f";
                            $isExist_href .= "</td>";

                        }


                        $str .= "$isExist_href";

                        if (array_key_exists($key, $totals)) {
                            $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
                            $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;
                        }
//                        if(isset($values[$key]) && is_numeric($values[$key])){
//                            if(!isset($totals[$key])){
//                                $totals[$key] = 0;
//                            }
//                            if(!isset($subTotals[$key])){
//                                $subTotals[$key] = 0;
//                            }
//                            $totals[$key] += isset($values[$key]) ? $values[$key] : 0;
//                            $subTotals[$key] += isset($values[$key]) ? $values[$key] : 0;
//                        }
                    }
                    $str .= "</tr>";

                    $numRest++;
                }

                if ($catNumb == $last) {
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: #f0f0f0;'>";
                    $tfoot .= "<td></td>";
                    $tfoot .= "<td style='font-size:1.3em;'>$catBottom</td>";
                    foreach ($headers as $key => $label) {
                        if (isset($subTotals[$key])) {
                            $coLStr = $subTotals[$key];
                        }
                        else {
                            $coLStr = "";
                        }
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;'>" . formatField($key, $coLStr) . "</td>";

                        }
                        else {
                            if (is_numeric($coLStr)) {
                                if ($coLStr >= 0) {
                                    $rVal = number_format($coLStr * 1, "0", ".", ",");
                                    $style = "style='text-align:right;font-size:1.3em;'";
                                }
                                else {
                                    $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                    $style = "style='text-align:right;color:red;font-size:1.3em;'";
                                }
                            }
                            else {
                                $rVal = formatField($key, $coLStr);
                                $style = "style='font-size:1.3em;'";
                            }
                            $isExist_href = "<td $style>$rVal ";
                            $isExist_href .= "</td>";

                        }

                        $tfoot .= "$isExist_href";
                    }
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                }


            }
            $str .= "</tbody>";
            $str .= $tfoot;


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.rugilaba').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('.rugilaba tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.rugilaba tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
        }
        else {
            $str .= "profit and lost is not yet available.<br>start making any transaction so this page has a content";
        }
        echo $str;
        break;

    //konsolidasian----------------------------
    case "keuangan_neraca_monthly_konsolidasi":
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);
        $pakai_ini = 1;
        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select date </span>";
////        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage" . "date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );

            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid neraca nowrap compact'>";

            //region header tabel
            $str .= "<thead>";

//            $str .= "<tr bgcolor='#f0f0f0'>";
//            $str .= "<th></th>";
//            $str .= "<th></th>";
//            foreach ($cabang as $cabID => $cabName) {
//                foreach ($headers as $key => $label) {
//                    $str .= "<th></th>";
//                }
//            }
//            foreach ($headers as $key => $label) {
//                $str .= "<th></th>";
//            }
//            $str .= "<th></th>";
//            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
//                    $headerBgColor = "background-color:#DFDFAC;";
//                    $isiBgColor = "background-color:#FFFFCC;";
//                    $subBgColor = "background-color:#FFFFCC;";
//                    $totalBgColor = "background-color:#FFFFCC;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
//                    $headerBgColor = "background-color:#DFB0BE;";
//                    $isiBgColor = "background-color:#FFD0DE;";
//                    $subBgColor = "background-color:#FFD0DE;";
//                    $totalBgColor = "background-color:#FFD0DE;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

//                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
//                $str .= "$cabName";
//                $str .= "</th>";
            }
            if ($pakai_ini == 1) {
                $str .= "<th colspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                $str .= "Konsolidasi";
                $str .= "</th>";
            }
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
//            foreach ($cabang as $cabID => $cabName) {
//                foreach ($headers as $key => $label) {
//                    $str .= "<th>$label</th>";
//                }
//            }
            foreach ($headers as $key => $label) {
                $str .= "<th>$label</th>";
            }
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";

                        foreach ($headers as $key => $label) {
                            $val = 0;
                            if (isset($rekenings[$cabID][$catName][$rekName])) {

                                if (($rekenings[$cabID][$catName][$rekName]['debet'] > 0) && ($rekenings[$cabID][$catName][$rekName]['kredit'] > 0)) {

                                    $val_detail = $rekenings[$cabID][$catName][$rekName]['debet'] - $rekenings[$cabID][$catName][$rekName]['kredit'];
                                    if ($val_detail > 0) {
                                        $rekenings[$cabID][$catName][$rekName]['debet'] = $val_detail;
                                        $rekenings[$cabID][$catName][$rekName]['kredit'] = 0;
                                    }
                                    else {
                                        $rekenings[$cabID][$catName][$rekName]['debet'] = 0;
                                        $rekenings[$cabID][$catName][$rekName]['kredit'] = $val_detail * -1;
                                    }
                                }

                                $value = $rekenings[$cabID][$catName][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                        $align = "text-align:right;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }
//                                $str .= "<td style='$bgColor$align' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rVal</td>";
                            }
                            else {
                                $rVal = "";
//                                $str .= "<td style='$bgColor'>$rVal</td>";
                            }
                            if (is_numeric($val)) {
                                if ($key != "link") {
                                    if (!isset($subTotalsCatRight[$catName][$key])) {
                                        $subTotalsCatRight[$catName][$key] = 0;
                                    }

                                    if (!isset($subTotals[$cabID][$catName])) {
                                        $subTotals[$cabID][$catName] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }
                                    if (!isset($totals[$cabID])) {
                                        $totals[$cabID] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }
                                    if (!isset($subTotalsRight[$rekName])) {
                                        $subTotalsRight[$rekName] = array(
                                            "debet" => 0,
                                            "kredit" => 0,
                                        );
                                    }

                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion

                    if ($pakai_ini == 1) {

                        // ini milik holding...
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                            if ($key != "link") {
                                if (!isset($subTotalsCatRight2[$catName])) {
                                    $subTotalsCatRight2[$catName] = array(
                                        "debet" => 0,
                                        "kredit" => 0,
                                    );
                                }
                                if (isset($subTotalsRight[$rekName][$key])) {

                                    if ($subTotalsRight[$rekName]['debet'] && $subTotalsRight[$rekName]['kredit']) {
                                        $val_comp = $subTotalsRight[$rekName]['debet'] - $subTotalsRight[$rekName]['kredit'];
                                        if ($val_comp > 0) {
                                            $subTotalsRight[$rekName]['debet'] = $val_comp;
                                            $subTotalsRight[$rekName]['kredit'] = 0;
                                        }
                                        else {
                                            $subTotalsRight[$rekName]['debet'] = 0;
                                            $subTotalsRight[$rekName]['kredit'] = $val_comp * -1;
                                        }
                                    }
                                    if (in_array($rekName, $accountConsolidation)) {
                                        $value = $subTotalsRight[$rekName][$key];
                                        $val = 0;
                                        $color = "color:#A9A9A9;";
                                        $miring = "font-style:italic;";
                                    }
                                    else {
                                        $value = $subTotalsRight[$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        $color = "";
                                        $miring = "";
                                    }

                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                        $subTotalsCatRight2[$catName][$key] += $val;

                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $str .= "<td style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'></td>";
                                }
                            }
                            else {
                                $str .= "<td style='$bgColor'></td>";
                            }
                        }
                    }
//                    $str .= "<td><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    $str .= "</tr>";
                }
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                    if ($key != "link") {
                        $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";

                        if (!isset($totalsBottomRight[$key])) {
                            $totalsBottomRight[$key] = 0;
                        }
                        $totalsBottomRight[$key] += $coLStr;
                    }
                }
            }
            $str .= "</tbody>";
            //endregion


            //  region total bawah
            $str .= "<tfoot>";
            $str .= "<tr style='background-color:#f0f0f0;'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $isExist_href = "";
                    $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";

                    $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

//                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

//                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
//                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                    }
                    else {
                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";
                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'></td>";
                }
            }

//            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</tfoot>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.neraca').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip',
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('.neraca tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.neraca tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;
        break;
    case "keuangan_rugilaba_monthly_konsolidasi":
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

//        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
//        $p = New Layout("$title", "$subTitle", "$templateSelected");
//        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select date </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid rugilaba nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

//                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
//                $str .= "$cabName";
//                $str .= "</th>";
//
//                $str .= "<th></th>";

            }
            $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
            $str .= "Konsolidasi";
            $str .= "</th>";
//            $str .= "<th class='text-center'>-</th>";
//            $str .= "<th class='text-center'>-</th>";
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion

            //region isi tabel
            $str .= "<tbody>";
            $tfoot = "";
            $catCount = count($categories);
            $cat = 1;
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;
                    $firstRow = $numRest == 0 ? "first_row" : "";
                    $str .= "<tr class='$firstRow' line='" . __LINE__ . "'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                        if (!isset($subTotals[$cabID][$catName])) {
                            $subTotals[$cabID][$catName] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        if (!isset($totals[$cabID])) {
                            $totals[$cabID] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        if (!isset($subTotalsRight[$rekName])) {
                            $subTotalsRight[$rekName] = array(
                                //                                "debet" => 0,
                                //                                "kredit" => 0,
                                "values" => 0,
                            );
                        }
                        foreach ($headers as $key => $label) {
                            if (!isset($subTotalsCatRight[$catName][$key])) {
                                $subTotalsCatRight[$catName][$key] = 0;
                            }
                            if (!isset($totalsBottomRight[$key])) {
                                $totalsBottomRight[$key] = 0;
                            }

                            $val = 0;
                            if (isset($rekenings[$catName][$cabID][$rekName])) {

                                $value = $rekenings[$catName][$cabID][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
//                                        $rVal = formatField($key, $value);
                                        $rVal = number_format($value * 1, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }

                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
//                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $rVal = "";
//                                $str .= "<td style='$bgColor'>$rVal</td>";
                            }


                            if (is_numeric($val)) {
                                if ($key != "link" && $key != "link_detail") {
//                                if ($key != "link") {
                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $totalsBottomRight[$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion

//arrPrintHijau($headers);
                    // ini milik holding...
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                        if ($key != "link") {
                            if (!isset($subTotalsCatRight2[$catName])) {
                                $subTotalsCatRight2[$catName] = array(
                                    "values" => 0,
                                );
                            }
                            if (isset($subTotalsRight[$rekName][$key])) {
                                $value = $subTotalsRight[$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
//                                        $rVal = formatField($key, $value);
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }

                                    $subTotalsCatRight2[$catName][$key] += $val;
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }
                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $str .= "<td style='$bgColor'></td>";
                                $str .= "<td style='$bgColor'></td>";
                            }
                        }
                    }
                    $str .= "</tr>";
                    $numRest++;
                }

                if ($cat == $catCount) {
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: lightblue;'>";
                    $tfoot .= "<td>-</td>";
                    $tfoot .= "<td style='text-align:left;font-size:1.3em;'>$catSubBottom</td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                        foreach ($headers as $key => $label) {
                            $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
                            $isExist_href = "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                if ($key != "link") {
//                                    $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        //                                    $rVal = formatField($key, $coLStr);
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
//                                $isExist_href = "<td $style>$rVal";
//                                $isExist_href .= "</td>";
                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                        if ($key != "link") {
                            $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<td $style'>$rVal";
                                $isExist_href .= "</td>";

                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
//                    $tfoot .= "<th>-</th>";
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                }

                $cat++;
//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
////                        $str .= "<td></td>";
//                    }
////                    $str .= "<td></td>";
////                    $str .= "<td>&nbsp;</td>";
//                    $str .= "</tr>";
//                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;
            //endregion


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $str .= "<script>

                var oTable = $('.grid.rugilaba').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('.rugilaba tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.rugilaba tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            }

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;
        break;
    case "keuangan_cashflow_monthly_konsolidasi":

//        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
//        $p = New Layout("$title", "$subTitle", "$templateSelected");

//        $strDates = blobEncode($defaultDate);
//        $strRekeningAliases = blobEncode($rekeningsNameAlias);
//        $strRekenings = blobEncode($rekeningsName);
//        $strNilais = blobEncode($rekenings);
//        $excel_data = "rekening=$strRekenings&alias=$strRekeningAliases&nilai=$strNilais&date=$strDates";
//        $excel_name = "rugi laba $defaultDate";
        $str = "";

//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";

//        if (isset($dateSelector) && ($dateSelector == true)) {
//            $str .= "<span class='input-group-add-on' >select month </span>";
//            $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//        }
//
//        if (isset($linkExcel)) {
//            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
//            $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
//        }
//
//        if (isset($buttonMode) && ($buttonMode['enabled'] == true)) {
//            $btn_label = $buttonMode['label'];
//            $btn_link = $buttonMode['link'];
//            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"location.href='$btn_link'\" title='download data'><i class='fa fa-bookmark-o'></i> $btn_label</button>";
//        }

//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";


        if (sizeof($categories) > 0) {
            $idTbl = "cashflow";
            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='$idTbl' class='table table-sm table-hover table-striped table-bordered grid cashflow nowrap compact'>";
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td>-</td>";
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
                        $target = base_url() . "Neraca/detailCashflow/$rekID?date=$defaultDate";

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
//                $str .= "<tr>";
//                $str .= "<td></td>";
//                $str .= "<td style='background-color:red;'>$summaryTotal != $selisihKas [$selisih]</td>";
//                $str .= "</tr>";
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
            $str .= $underMaintenance;
            $str .= "-----";
        }

        echo $str;
        break;

    case "keuangan_neraca_konsolidasi_OLD":
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);
        $pakai_ini = 1;
        $str = "";
//        cekHere(":: TEST ::");
        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );

            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid neraca nowrap compact'>";

            //region header tabel
            $str .= "<thead>";

//            $str .= "<tr bgcolor='#f0f0f0'>";
//            $str .= "<th></th>";
//            $str .= "<th></th>";
//            foreach ($cabang as $cabID => $cabName) {
//                foreach ($headers as $key => $label) {
//                    $str .= "<th></th>";
//                }
//            }
//            foreach ($headers as $key => $label) {
//                $str .= "<th></th>";
//            }
//            $str .= "<th></th>";
//            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
//                    $headerBgColor = "background-color:#DFDFAC;";
//                    $isiBgColor = "background-color:#FFFFCC;";
//                    $subBgColor = "background-color:#FFFFCC;";
//                    $totalBgColor = "background-color:#FFFFCC;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
//                    $headerBgColor = "background-color:#DFB0BE;";
//                    $isiBgColor = "background-color:#FFD0DE;";
//                    $subBgColor = "background-color:#FFD0DE;";
//                    $totalBgColor = "background-color:#FFD0DE;";
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

//                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
//                $str .= "$cabName";
//                $str .= "</th>";
            }
            if ($pakai_ini == 1) {
                $colspan = sizeof($headers);
                $str .= "<th colspan='$colspan' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                $str .= "Konsolidasi";
                $str .= "</th>";
            }
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
//            foreach ($cabang as $cabID => $cabName) {
//                foreach ($headers as $key => $label) {
//                    $str .= "<th>$label</th>";
//                }
//            }
            foreach ($headers as $key => $label) {
                $str .= "<th>$label</th>";
            }
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";

                        foreach ($headers as $key => $label) {
                            $val = 0;
                            if (isset($rekenings[$cabID][$catName][$rekName])) {
//cekHitam(":: $rekName ::");
//arrPrintHijau($rekenings[$cabID][$catName][$rekName]);
                                foreach ($arrTahun as $thn_header) {

                                    if (isset($rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header]) && ($rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] > 0)
                                        && isset($rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header]) && ($rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header] > 0)) {
                                        $val_detail = $rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] - $rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header];
                                        if ($val_detail > 0) {
                                            $rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] = $val_detail;
                                            $rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header] = 0;
                                        }
                                        else {
                                            $rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] = 0;
                                            $rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header] = $val_detail * -1;
                                        }
                                    }
                                }

                                $value = isset($rekenings[$cabID][$catName][$rekName][$key]) ? $rekenings[$cabID][$catName][$rekName][$key] : 0;
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                $rVal = "";
                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                        $align = "text-align:right;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }
//                                $str .= "<td style='$bgColor$align' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rVal</td>";
                            }
                            else {
                                $rVal = "";
//                                $str .= "<td style='$bgColor'>$rVal</td>";
                            }
                            if (is_numeric($val)) {
                                if ($key != "link") {
                                    if (!isset($subTotalsCatRight[$catName][$key])) {
                                        $subTotalsCatRight[$catName][$key] = 0;
                                    }
//                                    foreach($arrTahun as $thn_header){
//                                        if (!isset($subTotals[$cabID][$catName])) {
//                                            $subTotals[$cabID][$catName] = array(
//                                                "debet_".$thn_header => 0,
//                                                "kredit_".$thn_header => 0,
//                                            );
//                                        }
//                                        if (!isset($totals[$cabID])) {
//                                            $totals[$cabID] = array(
//                                                "debet_".$thn_header => 0,
//                                                "kredit_".$thn_header => 0,
//                                            );
//                                        }
//                                        if (!isset($subTotalsRight[$rekName])) {
//                                            $subTotalsRight[$rekName] = array(
//                                                "debet_".$thn_header => 0,
//                                                "kredit_".$thn_header => 0,
//                                            );
//                                        }
//                                    }
                                    if (!isset($totals[$cabID][$key])) {
                                        $totals[$cabID][$key] = 0;
                                    }
                                    if (!isset($subTotals[$cabID][$catName][$key])) {
                                        $subTotals[$cabID][$catName][$key] = 0;
                                    }
                                    if (!isset($subTotalsCatRight[$catName][$key])) {
                                        $subTotalsCatRight[$catName][$key] = 0;
                                    }
                                    if (!isset($subTotalsRight[$rekName][$key])) {
                                        $subTotalsRight[$rekName][$key] = 0;
                                    }
                                    $totals[$cabID][$key] += $val;
                                    $subTotals[$cabID][$catName][$key] += $val;
                                    $subTotalsCatRight[$catName][$key] += $val;
                                    $subTotalsRight[$rekName][$key] += $val;
                                }
                            }
                        }
                    }
                    //endregion

                    if ($pakai_ini == 1) {

                        // ini milik holding...
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                            if ($key != "link") {
//                                if (!isset($subTotalsCatRight2[$catName])) {
//                                    foreach($arrTahun as $thn_header){
//
//                                        $subTotalsCatRight2[$catName] = array(
//                                            "debet_".$thn_header => 0,
//                                            "kredit_".$thn_header => 0,
//                                        );
//                                    }
//                                }
                                if (!isset($subTotalsCatRight2[$catName][$key])) {
                                    $subTotalsCatRight2[$catName][$key] = 0;
                                }
                                if (isset($subTotalsRight[$rekName][$key])) {
                                    foreach ($arrTahun as $thn_header) {

                                        if ($subTotalsRight[$rekName]['debet_' . $thn_header] && $subTotalsRight[$rekName]['kredit_' . $thn_header]) {
                                            $val_comp = $subTotalsRight[$rekName]['debet_' . $thn_header] - $subTotalsRight[$rekName]['kredit_' . $thn_header];
                                            if ($val_comp > 0) {
                                                $subTotalsRight[$rekName]['debet_' . $thn_header] = $val_comp;
                                                $subTotalsRight[$rekName]['kredit_' . $thn_header] = 0;
                                            }
                                            else {
                                                $subTotalsRight[$rekName]['debet_' . $thn_header] = 0;
                                                $subTotalsRight[$rekName]['kredit_' . $thn_header] = $val_comp * -1;
                                            }
                                        }
                                    }
                                    if (in_array($rekName, $accountConsolidation)) {
                                        $value = $subTotalsRight[$rekName][$key];
                                        $val = 0;
                                        $color = "color:#A9A9A9;";
                                        $miring = "font-style:italic;";
                                    }
                                    else {
                                        $value = $subTotalsRight[$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        $color = "";
                                        $miring = "";
                                    }

                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                        $subTotalsCatRight2[$catName][$key] += $val;

                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $str .= "<td style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'>-</td>";
                                }
                            }
                            else {
                                $str .= "<td style='$bgColor'>--</td>";
                            }
                        }
                    }
//                    $str .= "<td><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    $str .= "</tr>";
                }
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                    if ($key != "link") {
                        $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";

                        if (!isset($totalsBottomRight[$key])) {
                            $totalsBottomRight[$key] = 0;
                        }
                        $totalsBottomRight[$key] += $coLStr;
                    }
                }
            }
            $str .= "</tbody>";
            //endregion


            //  region total bawah
            $str .= "<tfoot>";
            $str .= "<tr style='background-color:#f0f0f0;'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            foreach ($cabang as $cabID => $cabName) {
                foreach ($headers as $key => $label) {
                    $isExist_href = "";
                    $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";

                    $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

//                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                    }
                    else {

//                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
//                        $isExist_href .= "</td>";

                    }
                    $str .= "$isExist_href";
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                    }
                    else {
                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";
                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'></td>";
                }
            }

//            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</tfoot>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.neraca').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfTrtip',
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('.neraca tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.neraca tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;
        break;
    case "keuangan_neraca_konsolidasi":
        // cekLime("uhuii");
        // arrPrint($subHeadersKanan);
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);
        $pakai_ini = 1;
        $str = "";
//        cekHere(":: TEST ::");
        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );

            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid neraca nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";

            //region header tabel
            $str .= "<thead>";

//            $str .= "<tr bgcolor='#f0f0f0'>";
//            $str .= "<th></th>";
//            $str .= "<th></th>";
//            foreach ($cabang as $cabID => $cabName) {
//                foreach ($headers as $key => $label) {
//                    $str .= "<th></th>";
//                }
//            }
//            foreach ($headers as $key => $label) {
//                $str .= "<th></th>";
//            }
//            $str .= "<th></th>";
//            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";

            $j = 0;
            $arrColumnStyle = array();
            if (isset($cabang) && sizeof($cabang) > 0) {
                $colspan = sizeof($headers);
                foreach ($cabang as $cabID => $cabName) {
                    $j++;
                    if ($j % 2 == 0) {
//                    $headerBgColor = "background-color:#DFDFAC;";
//                    $isiBgColor = "background-color:#FFFFCC;";
//                    $subBgColor = "background-color:#FFFFCC;";
//                    $totalBgColor = "background-color:#FFFFCC;";
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    else {
//                    $headerBgColor = "background-color:#DFB0BE;";
//                    $isiBgColor = "background-color:#FFD0DE;";
//                    $subBgColor = "background-color:#FFD0DE;";
//                    $totalBgColor = "background-color:#FFD0DE;";
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    $arrColumnStyle[$cabID] = array(
                        "header" => $headerBgColor,
                        "isi" => $isiBgColor,
                        "subTotal" => $subBgColor,
                        "total" => $totalBgColor,
                    );

                    $str .= "<th colspan='$colspan' class='text-center' style='$headerBgColor'>";
                    $str .= "$cabName";
                    $str .= "</th>";
                }
            }
            if ($pakai_ini == 1) {
                $colspan = sizeof($headers);
                $str .= "<th colspan='$colspan' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                $str .= "Konsolidasi";
                $str .= "</th>";
            }
            if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                $colspan = sizeof($headers);
                foreach ($headersKanan as $headers_kanan) {
                    $str .= "<th colspan='$colspan' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                    $str .= "$headers_kanan";
                    $str .= "</th>";
                }
            }
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            if (isset($cabang) && sizeof($cabang) > 0) {
                foreach ($cabang as $cabID => $cabName) {
                    foreach ($headers as $key => $label) {
                        $str .= "<th>$label</th>";
                    }
                }
            }
            foreach ($headers as $key => $label) {
                $str .= "<th>$label</th>";
            }
            if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                foreach ($headersKanan as $headers_kanan) {
                    foreach ($subHeadersKanan as $key => $label) {
                        $str .= "<th>$label</th>";
                    }
                }
            }
            // nama-nama rekening sebelah kanan
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            foreach ($categories as $catName) {
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    //region isi category
                    if (isset($cabang) && sizeof($cabang) > 0) {

                        foreach ($cabang as $cabID => $cabName) {
                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";

                            foreach ($headers as $key => $label) {
                                $val = 0;
                                if (isset($rekenings[$cabID][$catName][$rekName])) {

                                    foreach ($arrTahun as $thn_header) {

                                        if (isset($rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header]) && ($rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] > 0)
                                            && isset($rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header]) && ($rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header] > 0)) {
                                            $val_detail = $rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] - $rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header];
                                            if ($val_detail > 0) {
                                                $rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] = $val_detail;
                                                $rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header] = 0;
                                            }
                                            else {
                                                $rekenings[$cabID][$catName][$rekName]['debet_' . $thn_header] = 0;
                                                $rekenings[$cabID][$catName][$rekName]['kredit_' . $thn_header] = $val_detail * -1;
                                            }
                                        }
                                    }

                                    $value = isset($rekenings[$cabID][$catName][$rekName][$key]) ? $rekenings[$cabID][$catName][$rekName][$key] : 0;
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $str .= "<td style='$bgColor$align' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rVal</td>";
                                }
                                else {
                                    $rVal = "";
                                    $str .= "<td style='$bgColor'>$rVal</td>";
                                }
                                if (is_numeric($val)) {
                                    if ($key != "link") {
                                        if (!isset($subTotalsCatRight[$catName][$key])) {
                                            $subTotalsCatRight[$catName][$key] = 0;
                                        }
                                        //                                    foreach($arrTahun as $thn_header){
                                        //                                        if (!isset($subTotals[$cabID][$catName])) {
                                        //                                            $subTotals[$cabID][$catName] = array(
                                        //                                                "debet_".$thn_header => 0,
                                        //                                                "kredit_".$thn_header => 0,
                                        //                                            );
                                        //                                        }
                                        //                                        if (!isset($totals[$cabID])) {
                                        //                                            $totals[$cabID] = array(
                                        //                                                "debet_".$thn_header => 0,
                                        //                                                "kredit_".$thn_header => 0,
                                        //                                            );
                                        //                                        }
                                        //                                        if (!isset($subTotalsRight[$rekName])) {
                                        //                                            $subTotalsRight[$rekName] = array(
                                        //                                                "debet_".$thn_header => 0,
                                        //                                                "kredit_".$thn_header => 0,
                                        //                                            );
                                        //                                        }
                                        //                                    }
                                        if (!isset($totals[$cabID][$key])) {
                                            $totals[$cabID][$key] = 0;
                                        }
                                        if (!isset($subTotals[$cabID][$catName][$key])) {
                                            $subTotals[$cabID][$catName][$key] = 0;
                                        }
                                        if (!isset($subTotalsCatRight[$catName][$key])) {
                                            $subTotalsCatRight[$catName][$key] = 0;
                                        }
                                        if (!isset($subTotalsRight[$rekName][$key])) {
                                            $subTotalsRight[$rekName][$key] = 0;
                                        }
                                        $totals[$cabID][$key] += $val;
                                        $subTotals[$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$catName][$key] += $val;
                                        $subTotalsRight[$rekName][$key] += $val;
                                    }
                                }
                            }
                        }
                    }

                    //endregion

                    // ini milik holding...
                    if ($pakai_ini == 1) {
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                            if ($key != "link") {
                                if (!isset($subTotalsCatRight2[$catName][$key])) {
                                    $subTotalsCatRight2[$catName][$key] = 0;
                                }
                                if (isset($rekeningsKonsolidasiNilai[$catName][$rekName][$key])) {
                                    //$subTotalsRight
//                                if (isset($subTotalsRight[$rekName][$key])) {//$subTotalsRight
//                                    foreach ($arrTahun as $thn_header) {
//                                        if ($subTotalsRight[$rekName]['debet_' . $thn_header] && $subTotalsRight[$rekName]['kredit_' . $thn_header]) {
//                                            $val_comp = $subTotalsRight[$rekName]['debet_' . $thn_header] - $subTotalsRight[$rekName]['kredit_' . $thn_header];
//                                            if ($val_comp > 0) {
//                                                $subTotalsRight[$rekName]['debet_' . $thn_header] = $val_comp;
//                                                $subTotalsRight[$rekName]['kredit_' . $thn_header] = 0;
//                                            }
//                                            else {
//                                                $subTotalsRight[$rekName]['debet_' . $thn_header] = 0;
//                                                $subTotalsRight[$rekName]['kredit_' . $thn_header] = $val_comp * -1;
//                                            }
//                                        }
//                                    }
//                                    if (in_array($rekName, $accountConsolidation)) {
//                                        $value = $subTotalsRight[$rekName][$key];
//                                        $val = 0;
//                                        $color = "color:#A9A9A9;";
//                                        $miring = "font-style:italic;";
//                                    }
//                                    else {
//                                        $value = $subTotalsRight[$rekName][$key];
//                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
//                                        $color = "";
//                                        $miring = "";
//                                    }
                                    foreach ($arrTahun as $thn_header) {
                                        if ($rekeningsKonsolidasiNilai[$catName][$rekName]['debet_' . $thn_header] && $rekeningsKonsolidasiNilai[$catName][$rekName]['kredit_' . $thn_header]) {
                                            $val_comp = $rekeningsKonsolidasiNilai[$catName][$rekName]['debet_' . $thn_header] - $rekeningsKonsolidasiNilai[$catName][$rekName]['kredit_' . $thn_header];
                                            if ($val_comp > 0) {
                                                $rekeningsKonsolidasiNilai[$catName][$rekName]['debet_' . $thn_header] = $val_comp;
                                                $rekeningsKonsolidasiNilai[$catName][$rekName]['kredit_' . $thn_header] = 0;
                                            }
                                            else {
                                                $rekeningsKonsolidasiNilai[$catName][$rekName]['debet_' . $thn_header] = 0;
                                                $rekeningsKonsolidasiNilai[$catName][$rekName]['kredit_' . $thn_header] = $val_comp * -1;
                                            }
                                        }
                                    }
                                    if (in_array($rekName, $accountConsolidation)) {
                                        $value = $rekeningsKonsolidasiNilai[$catName][$rekName][$key];
                                        $val = 0;
                                        $color = "color:#A9A9A9;";
                                        $miring = "font-style:italic;";
                                    }
                                    else {
                                        $value = $rekeningsKonsolidasiNilai[$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        $color = "";
                                        $miring = "";
                                    }

                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                        $subTotalsCatRight2[$catName][$key] += $val;
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $str .= "<td style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'>-</td>";
                                }
                            }
                            else {
                                $str .= "<td style='$bgColor'>--</td>";
                            }
                        }
                    }
                    if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                        foreach ($subHeadersKanan as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                            if ($key != "link") {
                                if (!isset($subTotalsCatKanan[$catName][$key])) {
                                    $subTotalsCatKanan[$catName][$key] = 0;
                                }
                                if (isset($rekeningsKonsolidasiKanan[$catName][$rekName][$key])) {//$subTotalsRight
//                                    foreach ($arrTahun as $thn_header) {
                                    if ($rekeningsKonsolidasiKanan[$catName][$rekName]['debet'] && $rekeningsKonsolidasiKanan[$catName][$rekName]['kredit']) {
                                        $val_comp = $rekeningsKonsolidasiKanan[$catName][$rekName]['debet'] - $rekeningsKonsolidasiKanan[$catName][$rekName]['kredit'];
                                        if ($val_comp > 0) {
                                            $rekeningsKonsolidasiKanan[$catName][$rekName]['debet'] = $val_comp;
                                            $rekeningsKonsolidasiKanan[$catName][$rekName]['kredit'] = 0;
                                        }
                                        else {
                                            $rekeningsKonsolidasiKanan[$catName][$rekName]['debet'] = 0;
                                            $rekeningsKonsolidasiKanan[$catName][$rekName]['kredit'] = $val_comp * -1;
                                        }
                                    }
//                                    }
                                    if (in_array($rekName, $accountConsolidation)) {
                                        $value = $rekeningsKonsolidasiKanan[$catName][$rekName][$key];
                                        $val = 0;
                                        $color = "color:#A9A9A9;";
                                        $miring = "font-style:italic;";
                                    }
                                    else {
                                        $value = $rekeningsKonsolidasiKanan[$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        $color = "";
                                        $miring = "";
                                    }

                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                        $subTotalsCatKanan[$catName][$key] += $val;
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $str .= "<td style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'>-</td>";
                                }
                            }
                            else {
                                $str .= "<td style='$bgColor'>--</td>";
                            }
                        }
                    }
                    // nama-naam rekening sebelah kanan
//                    $str .= "<td><span ttitle='$rekKeterangan' data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"$rekKeterangan\">$rekNameAlias</span></td>";

                    $str .= "</tr>";
                }
                foreach ($headers as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                    if ($key != "link") {
                        $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";

                        if (!isset($totalsBottomRight[$key])) {
                            $totalsBottomRight[$key] = 0;
                        }
                        $totalsBottomRight[$key] += $coLStr;
                    }
                }
                if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                    foreach ($subHeadersKanan as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                        if ($key != "link") {
                            $coLStr = isset($subTotalsCatKanan[$catName][$key]) ? $subTotalsCatKanan[$catName][$key] : "";

                            if (!isset($totalsBottomKanan[$key])) {
                                $totalsBottomKanan[$key] = 0;
                            }
                            $totalsBottomKanan[$key] += $coLStr;
                        }
                    }
                }
            }
            $str .= "</tbody>";
            //endregion

// arrPrint($totalsBottomRight);
            //  region total bawah
            $str .= "<tfoot>";
            $str .= "<tr style='background-color:#f0f0f0;'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            if (isset($cabang) && sizeof($cabang) > 0) {
                foreach ($cabang as $cabID => $cabName) {
                    foreach ($headers as $key => $label) {
                        $isExist_href = "";
                        $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";

                        $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";

                        }
                        else {

                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";

                        }
                        $str .= "$isExist_href";
                    }
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";
                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if (is_numeric($coLStr)) {
                        $coLStr = number_format($coLStr, 0, ".", ",");
                    }
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                    }
                    else {
                        $isExist_href = "<td style='font-size:1.3em;$bgColor' class='text-right'>" . formatField($key, $coLStr);
                        $isExist_href .= "</td>";
                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'></td>";
                }
            }
            if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                foreach ($subHeadersKanan as $key => $label) {
                    $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";
                    if ($key != "link") {
                        $coLStr = isset($totalsBottomKanan[$key]) ? $totalsBottomKanan[$key] : "";
//                        if(is_numeric($coLStr)){
//                            $coLStr = number_format($coLStr,0,".",",");
//                        }
//
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        else {
                            $isExist_href = "<td style='font-size:1.3em;$bgColor' class='text-right'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";
                        }
                        $str .= "$isExist_href";
                    }
                    else {
                        $str .= "<td style='$bgColor'></td>";
                    }
                }
            }

//            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</tfoot>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.neraca').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('tbl_head','','" . strtoupper($subTitle) . "');
                            }
                        },
                                {
                                    text: 'Print',
                                    action: function (e, dt, node, config) {
                                        tableToPrint('tbl_head');
                                    }
                                }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('.neraca tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.neraca tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;
        break;
    case "keuangan_neraca_konsolidasi_ytd":

        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );

            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid neraca nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";

            //region header tabel
            $str .= "<thead>";

//            $str .= "<tr bgcolor='#f0f0f0'>";
//            $str .= "<th></th>";
//            $str .= "<th></th>";
//            foreach ($cabang as $cabID => $cabName) {
//                foreach ($headers as $key => $label) {
//                    $str .= "<th></th>";
//                }
//            }
//            foreach ($headers as $key => $label) {
//                $str .= "<th></th>";
//            }
//            $str .= "<th></th>";
//            $str .= "</tr>";
//
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";

            $j = 0;
            $arrColumnStyle = array();
            if (isset($cabang) && sizeof($cabang) > 0) {
                foreach ($cabang as $cabID => $cabName) {
                    $j++;
                    if ($j % 2 == 0) {
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    else {
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    $arrColumnStyle[$cabID] = array(
                        "header" => $headerBgColor,
                        "isi" => $isiBgColor,
                        "subTotal" => $subBgColor,
                        "total" => $totalBgColor,
                    );

//                $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
                    $str .= "<th colspan='2' class='text-center text-uppercase'>";
                    $str .= "$cabName";
                    $str .= "</th>";
//                $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
                }
            }

//            $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
            $str .= "<th colspan='2' class='text-center text-uppercase'>";
            $str .= "YTD";
            $str .= "</th>";
//            $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";

//            $str .= "<th class='text-center text-uppercase' rrowspan='2'></th>";

//            for($r=0;$r<=($j*2)+1;$r++){
//                $str .= "<th colspanx='1' class='hiddenxx'>&nbsp;</th>";
//            }

            $str .= "</tr>";

            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th></th>";
            $str .= "<th></th>";
            if (isset($cabang) && sizeof($cabang) > 0) {

                foreach ($cabang as $cabID => $cabName) {
                    foreach ($headers as $key => $label) {
                        $str .= "<th>$label</th>";
                    }
                }
            }
            foreach ($headers as $key => $label) {
                $str .= "<th>$label</th>";
            }
//            $str .= "<th></th>";
            $str .= "</tr>";

            $str .= "</thead>";
            $str .= "<tbody>";
//            $str .= "</tbody>";
//            $str .= "</table>";
            //endregion

            //region isi tabel
//            $str .= "<tbody>";
//            $i = 0;
//            $len = count($categories);
            foreach ($categories as $catName) {

//                if ($i == 0) {
//                    // first
//                } else if ($i == $len - 1) {
//                    // last
//                }

//                $str .= "<div class='table-responsive tbl_$catName'>";
//                $str .= "<table id='tbl_$catName' class='table display compact table-bordered'>";
//                $str .= "<thead>";
                //region header category
//                $str .= "<tr style='background-color: lightblue;' class='text-center text-bold text-uppercase'>";
//                $str .= "<td column='consulente:'>$catName</td>";
//                $str .= "<td column='$catName:'>$catName</td>";
//                foreach ($cabang as $cabID => $cabName) {
//                    $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";
//                    foreach ($headers as $key => $label) {
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor min-width: 125px;max-width: 125px;'>$label</td>";
//                        }
//                        else {
//                            if($label!=""){
//                                $isExist_href = "<td column='$label:' style='$bgColor min-width: 125px;max-width: 125px;'>" . $label . "</td>";
//                            }
//                            else{
//                                $isExist_href = "<td column='-:' style='$bgColor min-width: 23px;max-width: 23px;'>" . $label . "</td>";
//                            }
//
//                        }
//                        $str .= "$isExist_href";
//                    }
//                }
//                foreach ($headers as $key => $label) {
//                    $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
//                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                        $isExist_href = $key == "link" ? "" : "<td style='$bgColor min-width: 150px;max-width: 150px;'>$label</td>";
//                    }
//                    else {
//                        $isExist_href = "<td style='$bgColor min-width: 150px;max-width: 150px;'>" . $label . "</td>";
//                    }
//                    $str .= "$isExist_href";
//                }
//                $str .= "<td style='min-width: 150px;max-width: 150px;'>$catName</td>";
//                $str .= "</tr>";
                //endregion
//                $str .= "</thead>";
//                $str .= "<tbody>";
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";

                    $rekening_key = strtolower($rekID);
                    if(array_key_exists($rekening_key, $arrRekeningDetils)){
                        $link_detil = base_url() . $arrRekeningDetils[$rekening_key]["link"] . $rekening_key;
                        $detil_title = isset($arrRekeningDetils[$rekening_key]["title"]) ? base_url() . $arrRekeningDetils[$rekening_key]["title"] . $rekening_key : "detil $rekening_name";
                        $linkH .= "<span class='pull-right'><a href='" . base_url() . "$link_detil" . $row['rekening'] . "'  data-toggle='tooltip' title='$detil_title' target='_blank'><span class='fa fa-sticky-note-o'></span></a> &nbsp;</span>";

                        $rekNameAlias_l = "<a href='$link_detil' title='detil $rekNameAlias' target='_blank'>$rekNameAlias</a>";
                    }
                    else{
                        $rekNameAlias_l = $rekNameAlias;
                    }

                    $rekAlias12Tmp = explode(' ', $rekNameAlias);
                    $rekAlias12 = count($rekAlias12Tmp) > 1 ? $rekAlias12Tmp[0] . " " . $rekAlias12Tmp[1] : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
//                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rekNameAlias</span></td>";
                    $str .= "<td column='$catName:' title='$rekID'>$rekNameAlias_l </td>";

                    //region isi category
                    if (isset($cabang) && sizeof($cabang) > 0) {
                        foreach ($cabang as $cabID => $cabName) {
                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                            foreach ($headers as $key => $label) {
                                $val = 0;
                                if (isset($rekenings[$cabID][$catName][$rekName])) {
                                    if (($rekenings[$cabID][$catName][$rekName]['debet'] > 0) && ($rekenings[$cabID][$catName][$rekName]['kredit'] > 0)) {

                                        $val_detail = $rekenings[$cabID][$catName][$rekName]['debet'] - $rekenings[$cabID][$catName][$rekName]['kredit'];
                                        if ($val_detail > 0) {
                                            $rekenings[$cabID][$catName][$rekName]['debet'] = $val_detail;
                                            $rekenings[$cabID][$catName][$rekName]['kredit'] = 0;
                                        }
                                        else {
                                            $rekenings[$cabID][$catName][$rekName]['debet'] = 0;
                                            $rekenings[$cabID][$catName][$rekName]['kredit'] = $val_detail * -1;
                                        }
                                    }
                                    $value = $rekenings[$cabID][$catName][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
//                                        $rVal = formatField($key, $value);
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                            $align = "text-align:right;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
//                                    $rVal = number_format($value*1, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    if ($label != '') {
                                        $str .= "<td column='$label:' style='$bgColor$align' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rVal</td>";
                                    }
                                    else {
                                        $str .= "<td column='-:' style='$bgColor$align' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rVal</td>";
                                    }
                                }
                                else {
                                    $rVal = "";
                                    if ($label != '') {
                                        $str .= "<td column='$label:' style='$bgColor'>$rVal</td>";
                                    }
                                    else {
                                        $str .= "<td column='-:' style='$bgColor'>$rVal</td>";
                                    }

                                }
                                if (is_numeric($val)) {
                                    if ($key != "link") {
                                        if (!isset($subTotalsCatRight[$catName][$key])) {
                                            $subTotalsCatRight[$catName][$key] = 0;
                                        }

                                        if (!isset($subTotals[$cabID][$catName])) {
                                            $subTotals[$cabID][$catName] = array(
                                                "debet" => 0,
                                                "kredit" => 0,
                                            );
                                        }
                                        if (!isset($totals[$cabID])) {
                                            $totals[$cabID] = array(
                                                "debet" => 0,
                                                "kredit" => 0,
                                            );
                                        }
                                        if (!isset($subTotalsRight[$rekName])) {
                                            $subTotalsRight[$rekName] = array(
                                                "debet" => 0,
                                                "kredit" => 0,
                                            );
                                        }

                                        $totals[$cabID][$key] += $val;
                                        $subTotals[$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$catName][$key] += $val;
                                        $subTotalsRight[$rekName][$key] += $val;
                                    }
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                        if ($key != "link") {
                            if (!isset($subTotalsCatRight2[$catName])) {
                                $subTotalsCatRight2[$catName] = array(
                                    "debet" => 0,
                                    "kredit" => 0,
                                );
                            }
//                            if (isset($subTotalsRight[$rekName][$key])) {
//
//                                if ($subTotalsRight[$rekName]['debet'] && $subTotalsRight[$rekName]['kredit']) {
//                                    $val_comp = $subTotalsRight[$rekName]['debet'] - $subTotalsRight[$rekName]['kredit'];
//                                    if ($val_comp > 0) {
//                                        $subTotalsRight[$rekName]['debet'] = $val_comp;
//                                        $subTotalsRight[$rekName]['kredit'] = 0;
//                                    }
//                                    else {
//                                        $subTotalsRight[$rekName]['debet'] = 0;
//                                        $subTotalsRight[$rekName]['kredit'] = $val_comp * -1;
//                                    }
//                                }
//                                if (in_array($rekName, $accountConsolidation)) {
//                                    $value = $subTotalsRight[$rekName][$key];
//                                    $val = 0;
//                                    $color = "color:#A9A9A9;";
//                                    $miring = "font-style:italic;";
//                                }
//                                else {
//                                    $value = $subTotalsRight[$rekName][$key];
//                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
//                                    $color = "";
//                                    $miring = "";
//                                }
//                                if (is_numeric($value)) {
//                                    if ($value > 0) {
//                                        $rVal = number_format($value, "0", ".", ",");
//                                        $align = "text-align:right;";
//                                    }
//                                    elseif ($value < 0) {
//                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
//                                        $align = "text-align:right;";
//                                    }
//                                    else {
//                                        $rVal = "";
//                                        $align = "text-align:right;";
//                                    }
//                                    $subTotalsCatRight2[$catName][$key] += $val;
//                                }
//                                else {
//                                    $rVal = formatField($key, $value);
//                                    $align = "text-align:right;";
//                                }
//                                if ($label != '') {
//                                    $str .= "<td column='$label:' style='$bgColor$align$color$miring'>$rVal</td>";
//                                }
//                                else {
//                                    $str .= "<td column='-:' style='$bgColor$align$color$miring'>$rVal</td>";
//                                }
//                            }
//                            else {
//                                if ($label != '') {
//                                    $str .= "<td column='$label:' style='$bgColor'></td>";
//                                }
//                                else {
//                                    $str .= "<td column='-:' style='$bgColor'></td>";
//                                }
//                            }

                            if (isset($rekeningsKonsolidasiNilai[$catName][$rekName][$key])) {

                                if ($rekeningsKonsolidasiNilai[$catName][$rekName]['debet'] && $rekeningsKonsolidasiNilai[$catName][$rekName]['kredit']) {
                                    $val_comp = $subTotalsRight[$rekName]['debet'] - $subTotalsRight[$rekName]['kredit'];
                                    if ($val_comp > 0) {
                                        $subTotalsRight[$rekName]['debet'] = $val_comp;
                                        $subTotalsRight[$rekName]['kredit'] = 0;
                                    }
                                    else {
                                        $subTotalsRight[$rekName]['debet'] = 0;
                                        $subTotalsRight[$rekName]['kredit'] = $val_comp * -1;
                                    }
                                }
                                if (in_array($rekName, $accountConsolidation)) {
                                    $value = $rekeningsKonsolidasiNilai[$catName][$rekName][$key];
                                    $val = 0;
                                    $color = "color:#A9A9A9;";
                                    $miring = "font-style:italic;";
                                }
                                else {
                                    $value = $rekeningsKonsolidasiNilai[$catName][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $color = "";
                                    $miring = "";
                                }
                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                        $align = "text-align:right;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }
                                    $subTotalsCatRight2[$catName][$key] += $val;
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }
                                if ($label != '') {
                                    $str .= "<td column='$label:' style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                                else {
                                    $str .= "<td column='-:' style='$bgColor$align$color$miring'>$rVal</td>";
                                }
                            }
                            else {
                                if ($label != '') {
                                    $str .= "<td column='$label:' style='$bgColor'></td>";
                                }
                                else {
                                    $str .= "<td column='-:' style='$bgColor'></td>";
                                }
                            }
                        }
                        else {
                            if ($label != '') {
                                $str .= "<td column='$label:' style='$bgColor'></td>";
                            }
                            else {
                                $str .= "<td column='-:' style='$bgColor'></td>";
                            }
                        }
                    }
//                    $str .= "<td>$rekNameAlias</td>";
//                    $str .= "<td>--</td>";
                    $str .= "</tr>";
                }

                //region subTotal bawah category

//                $str .= "<tr style='background-color: #ffcece;' class='text-right text-bold text-uppercase'>";
//                $str .= "<td column='consulente:'>scl_$catName</td>";
//                $str .= "<td column='$catName'>$catName</td>";
//                foreach ($cabang as $cabID => $cabName) {
//                    $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
//                    foreach ($headers as $key => $label) {
//                        $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            if (is_numeric($coLStr)) {
//                                if ($coLStr > 0) {
//                                    $scatVal = number_format($coLStr, "0", ".", ",");
//                                    $align = "text-align:right;";
//                                }
//                                elseif ($coLStr < 0) {
//                                    $scatVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
//                                    $align = "text-align:right;";
//                                }
//                                else {
//                                    $scatVal = "";
//                                    $align = "text-align:right;";
//                                }
//                            }
//                            else {
//                                $scatVal = formatField($key, $coLStr);
//                            }
//                            $isExist_href = "<td style='font-size:1.3em;$bgColor$align'>";
//                            $isExist_href .= $scatVal;
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
//                    }
//                }

                foreach ($headers as $key => $label) {
//                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                    if ($key != "link") {
                        $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";

                        if (!isset($totalsBottomRight[$key])) {
                            $totalsBottomRight[$key] = 0;
                        }
                        $totalsBottomRight[$key] += $coLStr;
//                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
//                        }
//                        else {
//                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
//                            $isExist_href .= "</td>";
//                        }
//                        $str .= "$isExist_href";
                    }
                    else {
//                        $str .= "<td style='$bgColor'></td>";
                    }
                }
//                $str .= "<td>&nbsp;</td>";
////                $str .= "<td>&nbsp;</td>";
//                $str .= "</tr>";
                //endregion

//                $str .= "</tbody>";
//                $str .= "</table class='table table-condensed'>";
//                $str .= "</div>";

//                $str .= "<script>\n
//                            $('#tbl_$catName').DataTable({
//                                dom: 'lBfrtip',
//                                fixedHeader: true,
//                                lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
//                                pageLength: -1,
//                                stateSave: false,
//                                paging: false,
//                                ordering: false,
//                                info: false,
//                                searching: false,
//                                buttons: [],
//                                autoWidth: false
//                            });
//                \n</script>";

//                $i++;
            }
            $str .= "</tbody>";
            //endregion


            //  region total bawah
//            $str .= "<table id='tbl_foot' class='table display compact table-bordered'>";
            $str .= "<tfoot>";
            $str .= "<tr style='background-color: #f0f0f0;' class='text-right text-bold'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            if (isset($cabang) && sizeof($cabang) > 0) {

                foreach ($cabang as $cabID => $cabName) {
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";
                        $isExist_href = "";
                        $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        else {
                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";
                        }
                        $str .= "$isExist_href";
                    }
                }
            }
            foreach ($headers as $key => $label) {
                $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                if ($key != "link") {

                    $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                    }
                    else {
                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                    }
                    $str .= "$isExist_href";
                }
                else {
                    $str .= "<td style='$bgColor'></td>";
                }
            }
//            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</tfoot>";

//            $str .= "<tbody>";
//            $str .= "</tbody>";
//            $str .= "</table>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.neraca').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('tbl_head','','" . strtoupper($subTitle) . "');
                            }
                        },
                                {
                                    text: 'Print',
                                    action: function (e, dt, node, config) {
                                        tableToPrint('tbl_head');
                                    }
                                }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('.neraca tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.neraca tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }
        echo $str;
        break;

    case "keuangan_rugilaba_konsolidasi_OLD":
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

//        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
//        $p = New Layout("$title", "$subTitle", "$templateSelected");
//        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select date </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid rugilaba nowrap compact'>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();
            foreach ($cabang as $cabID => $cabName) {
                $j++;
                if ($j % 2 == 0) {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                else {
                    $headerBgColor = "";
                    $isiBgColor = "";
                    $subBgColor = "";
                    $totalBgColor = "";
                }
                $arrColumnStyle[$cabID] = array(
                    "header" => $headerBgColor,
                    "isi" => $isiBgColor,
                    "subTotal" => $subBgColor,
                    "total" => $totalBgColor,
                );

//                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
//                $str .= "$cabName";
//                $str .= "</th>";
//
//                $str .= "<th></th>";

            }
            foreach ($headersTahun as $thn_header) {
                $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                $str .= "$thn_header";
                $str .= "</th>";
            }
//            $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
//            $str .= "Konsolidasi";
//            $str .= "</th>";
//            $str .= "<th class='text-center'>-</th>";
//            $str .= "<th class='text-center'>-</th>";
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion

            //region isi tabel
            $str .= "<tbody>";
            $tfoot = "";
            $catCount = count($categories);
            $cat = 1;
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;
                    $firstRow = $numRest == 0 ? "first_row" : "";
                    $str .= "<tr class='$firstRow' line='" . __LINE__ . "'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    //region isi category
                    foreach ($cabang as $cabID => $cabName) {
                        foreach ($headersTahun as $thn_header) {
                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                            if (!isset($subTotals[$thn_header][$cabID][$catName])) {
                                $subTotals[$thn_header][$cabID][$catName] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($totals[$thn_header][$cabID])) {
                                $totals[$thn_header][$cabID] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($subTotalsRight[$thn_header][$rekName])) {
                                $subTotalsRight[$thn_header][$rekName] = array(
                                    "values" => 0,
                                );
                            }

                            foreach ($headers as $key => $label) {
                                if (!isset($subTotalsCatRight[$thn_header][$catName][$key])) {
                                    $subTotalsCatRight[$thn_header][$catName][$key] = 0;
                                }
                                if (!isset($totalsBottomRight[$thn_header][$key])) {
                                    $totalsBottomRight[$thn_header][$key] = 0;
                                }

                                $val = 0;
                                if (isset($rekenings[$thn_header][$catName][$cabID][$rekName])) {
                                    $value = $rekenings[$thn_header][$catName][$cabID][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
//                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $rVal = "";
//                                $str .= "<td style='$bgColor'>$rVal</td>";
                                }

                                if (is_numeric($val)) {
                                    if ($key != "link" && $key != "link_detail") {

                                        $totals[$thn_header][$cabID][$key] += $val;
                                        $subTotals[$thn_header][$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$thn_header][$catName][$key] += $val;
                                        $totalsBottomRight[$thn_header][$key] += $val;
                                        $subTotalsRight[$thn_header][$rekName][$key] += $val;
                                    }
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headersTahun as $thn_header) {
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                            if ($key != "link") {
                                if (!isset($subTotalsCatRight2[$thn_header][$catName])) {
                                    $subTotalsCatRight2[$thn_header][$catName] = array(
                                        "values" => 0,
                                    );
                                }
                                if (isset($subTotalsRight[$thn_header][$rekName][$key])) {
                                    $value = $subTotalsRight[$thn_header][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                        $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $str .= "<td style='$bgColor'></td>";
                                    $str .= "<td style='$bgColor'></td>";
                                }
                            }
                        }
                    }
                    $str .= "</tr>";
                    $numRest++;
                }

                if ($cat == $catCount) {
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: lightblue;'>";
                    $tfoot .= "<td>-</td>";
                    $tfoot .= "<td style='text-align:left;font-size:1.3em;'>$catSubBottom</td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                        foreach ($headersTahun as $thn_header) {
                            foreach ($headers as $key => $label) {
                                $coLStr = isset($subTotals[$thn_header][$cabID][$catName][$key]) ? $subTotals[$thn_header][$cabID][$catName][$key] : "";
                                $isExist_href = "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    if ($key != "link") {
//                                    $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    }
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
//                                $isExist_href = "<td $style>$rVal";
//                                $isExist_href .= "</td>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                    foreach ($headersTahun as $thn_header) {
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                            if ($key != "link") {
                                $coLStr = isset($subTotalsCatRight2[$thn_header][$catName][$key]) ? $subTotalsCatRight2[$thn_header][$catName][$key] : "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    $isExist_href = "<td $style'>$rVal";
                                    $isExist_href .= "</td>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
//                    $tfoot .= "<th>-</th>";
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                }

                $cat++;
//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
////                        $str .= "<td></td>";
//                    }
////                    $str .= "<td></td>";
////                    $str .= "<td>&nbsp;</td>";
//                    $str .= "</tr>";
//                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;
            //endregion


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $str .= "<script>

                var oTable = $('.grid.rugilaba').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('.rugilaba tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.rugilaba tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            }

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;
        break;
    case "keuangan_rugilaba_konsolidasi":
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

//        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
//        $p = New Layout("$title", "$subTitle", "$templateSelected");
//        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";
//        $str .= "<span class='input-group-add-on' >select date </span>";
//        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";

        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
//            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid rugilaba nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();
            if (isset($cabang) && (sizeof($cabang) > 0)) {
                foreach ($cabang as $cabID => $cabName) {
                    $j++;
                    if ($j % 2 == 0) {
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    else {
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    $arrColumnStyle[$cabID] = array(
                        "header" => $headerBgColor,
                        "isi" => $isiBgColor,
                        "subTotal" => $subBgColor,
                        "total" => $totalBgColor,
                    );

//                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
//                $str .= "$cabName";
//                $str .= "</th>";
//
//                $str .= "<th></th>";

                }
            }
            if (isset($headersTahun) && sizeof($headersTahun) > 0) {

                foreach ($headersTahun as $thn_header) {
                    $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                    $str .= "$thn_header";
                    $str .= "</th>";
                }
            }
//            $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
//            $str .= "Konsolidasi";
//            $str .= "</th>";
//            $str .= "<th class='text-center'>-</th>";
//            $str .= "<th class='text-center'>-</th>";
            if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                foreach ($headersKanan as $header_kanan) {
                    $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                    $str .= "$header_kanan";
                    $str .= "</th>";
                }
            }
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion

            //region isi tabel
            $str .= "<tbody>";
            $tfoot = "";
            $catCount = count($categories);
            $cat = 1;
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;
                    $firstRow = $numRest == 0 ? "first_row" : "";
                    $str .= "<tr class='$firstRow' line='" . __LINE__ . "'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    //region isi category

                    foreach ($cabang as $cabID => $cabName) {
                        foreach ($headersTahun as $thn_header => $xx) {
                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                            if (!isset($subTotals[$thn_header][$cabID][$catName])) {
                                $subTotals[$thn_header][$cabID][$catName] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($totals[$thn_header][$cabID])) {
                                $totals[$thn_header][$cabID] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($subTotalsRight[$thn_header][$rekName])) {
                                $subTotalsRight[$thn_header][$rekName] = array(
                                    "values" => 0,
                                );
                            }

                            foreach ($headers as $key => $label) {
                                if (!isset($subTotalsCatRight[$thn_header][$catName][$key])) {
                                    $subTotalsCatRight[$thn_header][$catName][$key] = 0;
                                }
                                if (!isset($totalsBottomRight[$thn_header][$key])) {
                                    $totalsBottomRight[$thn_header][$key] = 0;
                                }

                                $val = 0;
                                if (isset($rekenings[$thn_header][$catName][$cabID][$rekName])) {
                                    $value = $rekenings[$thn_header][$catName][$cabID][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
//                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $rVal = "";
//                                $str .= "<td style='$bgColor'>$rVal</td>";
                                }

                                if (is_numeric($val)) {
                                    if ($key != "link" && $key != "link_detail") {

                                        $totals[$thn_header][$cabID][$key] += $val;
                                        $subTotals[$thn_header][$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$thn_header][$catName][$key] += $val;
                                        $totalsBottomRight[$thn_header][$key] += $val;
                                        $subTotalsRight[$thn_header][$rekName][$key] += $val;
                                    }
                                }
                            }
                        }
                    }

                    //endregion


                    // ini milik holding...
                    if (isset($headersTahun) && sizeof($headersTahun) > 0) {
                        foreach ($headersTahun as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                                if ($key != "link") {
                                    if (!isset($subTotalsCatRight2[$thn_header][$catName])) {
                                        $subTotalsCatRight2[$thn_header][$catName] = array(
                                            "values" => 0,
                                        );
                                    }
//                                if (isset($subTotalsRight[$thn_header][$rekName][$key])) {
//                                    $value = $subTotalsRight[$thn_header][$rekName][$key];
//                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
//                                    if (is_numeric($value)) {
//                                        if ($value > 0) {
//                                            $rVal = number_format($value, "0", ".", ",");
//                                            $align = "text-align:right;";
//                                        }
//                                        elseif ($value < 0) {
//                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
//                                            $align = "text-align:right;color:red;";
//                                        }
//                                        else {
//                                            $rVal = "";
//                                            $align = "text-align:right;";
//                                        }
//                                        $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
//                                    }
//                                    else {
//                                        $rVal = formatField($key, $value);
//                                        $align = "text-align:right;";
//                                    }
//                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
//                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
//                                }
//                                else {
//                                    $str .= "<td style='$bgColor'></td>";
//                                    $str .= "<td style='$bgColor'></td>";
//                                }

                                    if (isset($rekeningsKonsolidasiNilai[$thn_header][$catName][$rekName][$key])) {
                                        $value = $rekeningsKonsolidasiNilai[$thn_header][$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        if (is_numeric($value)) {
                                            if ($value > 0) {
                                                $rVal = number_format($value, "0", ".", ",");
                                                $align = "text-align:right;";
                                            }
                                            elseif ($value < 0) {
                                                $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                                $align = "text-align:right;color:red;";
                                            }
                                            else {
                                                $rVal = "";
                                                $align = "text-align:right;";
                                            }
                                            $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                        }
                                        else {
                                            $rVal = formatField($key, $value);
                                            $align = "text-align:right;";
                                        }
                                        $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                        $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    }
                                    else {
                                        $str .= "<td style='$bgColor'></td>";
//                                    $str .= "<td style='$bgColor'></td>";
                                    }
                                }
                            }
                        }
                    }
                    if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                        foreach ($headersKanan as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                                if ($key != "link") {
                                    if (!isset($subTotalsCatKanan[$thn_header][$catName])) {
                                        $subTotalsCatKanan[$thn_header][$catName] = array(
                                            "values" => 0,
                                        );
                                    }
//                                if (isset($subTotalsRight[$thn_header][$rekName][$key])) {
//                                    $value = $subTotalsRight[$thn_header][$rekName][$key];
//                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
//                                    if (is_numeric($value)) {
//                                        if ($value > 0) {
//                                            $rVal = number_format($value, "0", ".", ",");
//                                            $align = "text-align:right;";
//                                        }
//                                        elseif ($value < 0) {
//                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
//                                            $align = "text-align:right;color:red;";
//                                        }
//                                        else {
//                                            $rVal = "";
//                                            $align = "text-align:right;";
//                                        }
//                                        $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
//                                    }
//                                    else {
//                                        $rVal = formatField($key, $value);
//                                        $align = "text-align:right;";
//                                    }
//                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
//                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
//                                }
//                                else {
//                                    $str .= "<td style='$bgColor'></td>";
//                                    $str .= "<td style='$bgColor'></td>";
//                                }

                                    if (isset($rekeningsKonsolidasiKanan[$thn_header][$catName][$rekName][$key])) {
                                        $value = $rekeningsKonsolidasiKanan[$thn_header][$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        if (is_numeric($value)) {
                                            if ($value > 0) {
                                                $rVal = number_format($value, "0", ".", ",");
                                                $align = "text-align:right;";
                                            }
                                            elseif ($value < 0) {
                                                $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                                $align = "text-align:right;color:red;";
                                            }
                                            else {
                                                $rVal = "";
                                                $align = "text-align:right;";
                                            }
                                            $subTotalsCatKanan[$thn_header][$catName][$key] += $val;
                                        }
                                        else {
                                            $rVal = formatField($key, $value);
                                            $align = "text-align:right;";
                                        }
                                        $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                        $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    }
                                    else {
                                        $str .= "<td style='$bgColor'></td>";
//                                    $str .= "<td style='$bgColor'></td>";
                                    }
                                }
                            }
                        }
                    }
                    $str .= "</tr>";
                    $numRest++;
                }

                if ($cat == $catCount) {
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: lightblue;'>";
                    $tfoot .= "<td>-</td>";
                    $tfoot .= "<td style='text-align:left;font-size:1.3em;'>$catSubBottom</td>";
                    if (isset($cabang) && (sizeof($cabang) > 0)) {
                        foreach ($cabang as $cabID => $cabName) {
                            $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                            foreach ($headersTahun as $thn_header => $xx) {
                                foreach ($headers as $key => $label) {
                                    $coLStr = isset($subTotals[$thn_header][$cabID][$catName][$key]) ? $subTotals[$thn_header][$cabID][$catName][$key] : "";
                                    $isExist_href = "";
                                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
//                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                        if ($key != "link") {
//                                    $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                        }
                                    }
                                    else {
                                        if (is_numeric($coLStr)) {
                                            if ($coLStr >= 0) {
                                                $rVal = number_format($coLStr * 1, "0", ".", ",");
                                                $style = "style='text-align:right;font-size:1.3em;'";
                                            }
                                            else {
                                                $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                                $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                            }
                                        }
                                        else {
                                            $rVal = formatField($key, $coLStr);
                                            $style = "";
                                        }
//                                $isExist_href = "<td $style>$rVal";
//                                $isExist_href .= "</td>";
                                    }
                                    $tfoot .= "$isExist_href";
                                }
                            }
                        }
                    }
                    if (isset($headersTahun) && sizeof($headersTahun) > 0) {

                        foreach ($headersTahun as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                                if ($key != "link") {
                                    $coLStr = isset($subTotalsCatRight2[$thn_header][$catName][$key]) ? $subTotalsCatRight2[$thn_header][$catName][$key] : "";
                                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    }
                                    else {
                                        if (is_numeric($coLStr)) {
                                            if ($coLStr >= 0) {
                                                $rVal = number_format($coLStr * 1, "0", ".", ",");
                                                $style = "style='text-align:right;font-size:1.3em;'";
                                            }
                                            else {
                                                $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                                $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                            }
                                        }
                                        else {
                                            $rVal = formatField($key, $coLStr);
                                            $style = "";
                                        }
                                        $isExist_href = "<td $style'>$rVal";
                                        $isExist_href .= "</td>";
                                    }
                                    $tfoot .= "$isExist_href";
                                }
                            }
                        }
                    }
                    if (isset($headersKanan) && sizeof($headersKanan) > 0) {

                        foreach ($headersKanan as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                                if ($key != "link") {
                                    $coLStr = isset($subTotalsCatKanan[$thn_header][$catName][$key]) ? $subTotalsCatKanan[$thn_header][$catName][$key] : "";
                                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                        $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    }
                                    else {
                                        if (is_numeric($coLStr)) {
                                            if ($coLStr >= 0) {
                                                $rVal = number_format($coLStr * 1, "0", ".", ",");
                                                $style = "style='text-align:right;font-size:1.3em;'";
                                            }
                                            else {
                                                $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                                $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                            }
                                        }
                                        else {
                                            $rVal = formatField($key, $coLStr);
                                            $style = "";
                                        }
                                        $isExist_href = "<td $style'>$rVal";
                                        $isExist_href .= "</td>";
                                    }
                                    $tfoot .= "$isExist_href";
                                }
                            }
                        }
                    }
//                    $tfoot .= "<th>-</th>";
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                }

                $cat++;
//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
////                        $str .= "<td></td>";
//                    }
////                    $str .= "<td></td>";
////                    $str .= "<td>&nbsp;</td>";
//                    $str .= "</tr>";
//                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;
            //endregion


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $str .= "<script>

                var oTable = $('.grid.rugilaba').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        },
                                {
                                    text: 'Print',
                                    action: function (e, dt, node, config) {
                                        tableToPrint('rugilaba');
                                    }
                                }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('.rugilaba tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.rugilaba tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            }

        }
        else {
            $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
        }

        echo $str;
        break;
    case "keuangan_rugilaba_konsolidasi_ytd":

        //        $arrHDColumnStyle = array(
        //            "header" => "background-color:#ACACAC;",
        //            "isi" => "background-color:#CCCCCC;",
        //            "subTotal" => "background-color:#CCCCCC;",
        //            "total" => "background-color:#CCCCCC;",
        //        );
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";
        //        $str .= "<div class='panel-body alert alert-info-dot'>";
        //        $str .= "<div class='input-group'>";
        //        $str .= "<span class='input-group-add-on' >select date </span>";
        //        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
        //        $str .= "</div class='input-group'>";
        //        $str .= "</div class='panel-body'>";
//        $str .= "<h4>$subTitle</h4>";
        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba'>";
            $str .= "<table id='rugilaba' class='table table-sm table-hover table-striped table-bordered grid rugilaba nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td class='text-center'></td>";
            $str .= "<td class='text-center'></td>";


            $j = 0;
            $arrColumnStyle = array();
            if (isset($cabang) && sizeof($cabang) > 0) {
                foreach ($cabang as $cabID => $cabName) {
                    $j++;
                    if ($j % 2 == 0) {
                        //                    $headerBgColor = "background-color:#DFDFAC;";
                        //                    $isiBgColor = "background-color:#FFFFCC;";
                        //                    $subBgColor = "background-color:#FFFFCC;";
                        //                    $totalBgColor = "background-color:#FFFFCC;";
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    else {
                        //                    $headerBgColor = "background-color:#DFB0BE;";
                        //                    $isiBgColor = "background-color:#FFD0DE;";
                        //                    $subBgColor = "background-color:#FFD0DE;";
                        //                    $totalBgColor = "background-color:#FFD0DE;";
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    $arrColumnStyle[$cabID] = array(
                        "header" => $headerBgColor,
                        "isi" => $isiBgColor,
                        "subTotal" => $subBgColor,
                        "total" => $totalBgColor,
                    );

                    $str .= "<td ccolspan='2' class='text-center' style='$headerBgColor'>";
                    $str .= "$cabName";
                    $str .= "</td>";
//                $str .= "<td></td>";// link
                }
            }
            $str .= "<td ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
            $str .= "YTD";
            $str .= "</td>";
//            $str .= "<td></td>";
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion


            //region isi tabel
            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";
                $catNumb++;

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";

                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;

                    $firstRow = $numRest == 0 ? "first_row" : "";

                    $str .= "<tr class='$firstRow'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";


                    //region isi category
                    if (isset($cabang) && sizeof($cabang) > 0) {
                        foreach ($cabang as $cabID => $cabName) {
                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                            if (!isset($subTotals[$cabID][$catName])) {
                                $subTotals[$cabID][$catName] = array(
                                    //                                "debet" => 0,
                                    //                                "kredit" => 0,
                                    "values" => 0,
                                );
                            }
                            if (!isset($totals[$cabID])) {
                                $totals[$cabID] = array(
                                    //                                "debet" => 0,
                                    //                                "kredit" => 0,
                                    "values" => 0,
                                );
                            }
                            if (!isset($subTotalsRight[$rekName])) {
                                $subTotalsRight[$rekName] = array(
                                    //                                "debet" => 0,
                                    //                                "kredit" => 0,
                                    "values" => 0,
                                );
                            }
                            foreach ($headers as $key => $label) {
                                if (!isset($subTotalsCatRight[$catName][$key])) {
                                    $subTotalsCatRight[$catName][$key] = 0;
                                }
                                if (!isset($totalsBottomRight[$key])) {
                                    $totalsBottomRight[$key] = 0;
                                }

                                $val = 0;
                                if (isset($rekenings[$catName][$cabID][$rekName])) {

                                    $value = $rekenings[$catName][$cabID][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;color:black;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "0";
                                            $align = "text-align:right;color:black;";
                                        }
                                        if (isset($rekenings[$catName][$cabID][$rekName]['link_values'])) {
                                            $link_values = $rekenings[$catName][$cabID][$rekName]['link_values'];
//                                        $rVal = "<a href='$link_values' ttarget='_blank' style='$align'>$rVal</a>";
                                            $rVal = "$rVal";
                                        }
                                        else {

                                            $rVal = "$rVal";
                                        }
                                        //------------

                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }

                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $rVal = "";
                                    $str .= "<td style='$bgColor'>";
                                    $str .= "$rVal";
                                    $str .= "</td>";

                                }


                                if (is_numeric($val)) {
                                    if ($key != "link") {
                                        $totals[$cabID][$key] += $val;
                                        $subTotals[$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$catName][$key] += $val;
                                        $totalsBottomRight[$key] += $val;
                                        $subTotalsRight[$rekName][$key] += $val;
                                    }
                                }
                            }
                        }
                    }
                    //endregion


                    // ini milik holding...
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";

                        if ($key != "link") {
                            if (!isset($subTotalsCatRight2[$catName])) {
                                $subTotalsCatRight2[$catName] = array(
                                    //                                    "debet" => 0,
                                    //                                    "kredit" => 0,
                                    "values" => 0,
                                );
                            }

//                            if (isset($subTotalsRight[$rekName][$key])) {
//
//                                $value = $subTotalsRight[$rekName][$key];
//                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";
//
//                                if (is_numeric($value)) {
//                                    if ($value > 0) {
//                                        $rVal = number_format($value * 1, "0", ".", ",");
//                                        //                                        $rVal = number_format($value, "0", ".", ",");
//                                        $align = "text-align:right;";
//                                    }
//                                    elseif ($value < 0) {
//                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
//                                        $align = "text-align:right;color:red;";
//                                    }
//                                    else {
//                                        $rVal = "";
//                                        $align = "text-align:right;";
//                                    }
//
//                                    $subTotalsCatRight2[$catName][$key] += $val;
//                                }
//                                else {
//                                    $rVal = formatField($key, $value);
//                                    $align = "text-align:right;";
//                                }
//
//                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "" : $rVal;
//
//                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
//                            }
//                            else {
//                                $str .= "<td style='$bgColor'></td>";
//                            }

                            if (isset($rekeningsKonsolidasiNilai[$catName][$rekName][$key])) {
                                $value = $rekeningsKonsolidasiNilai[$catName][$rekName][$key];
                                $val = (isset($value) && strlen($value) > 0) ? $value : "0";

                                if (is_numeric($value)) {
                                    if ($value > 0) {
                                        $rVal = number_format($value * 1, "0", ".", ",");
                                        //                                        $rVal = number_format($value, "0", ".", ",");
                                        $align = "text-align:right;";
                                    }
                                    elseif ($value < 0) {
                                        $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                        $align = "text-align:right;color:red;";
                                    }
                                    else {
                                        $rVal = "";
                                        $align = "text-align:right;";
                                    }

                                    $subTotalsCatRight2[$catName][$key] += $val;
                                }
                                else {
                                    $rVal = formatField($key, $value);
                                    $align = "text-align:right;";
                                }

                                $rVal_f = in_array($rekName, $rekeningBlacklist) ? "" : $rVal;
                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                            }
                            else {
                                $str .= "<td style='$bgColor'></td>";
                            }

                        }
//                        else {
//                            $str .= "<td style='$bgColor'></td>";
//                        }
//
                    }
                    $str .= "</tr>";

                    $numRest++;
                }

                if ($catNumb == $last) {
                    //region subTotal bawah category
                    $tfoot .= "<tfoot>";
                    $tfoot .= "<tr style='background-color: #f0f0f0;'>";
                    $tfoot .= "<th></th>";
                    $tfoot .= "<th style='text-align:left;font-size:1.3em;'>$catSubBottom</th>";
                    if (isset($cabang) && sizeof($cabang) > 0) {
                        foreach ($cabang as $cabID => $cabName) {
                            $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                            $isExist_href = "";
                            foreach ($headers as $key => $label) {
                                $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    if ($key != "link") {
                                        $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    }
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    $isExist_href = "<th $style>$rVal";
                                    $isExist_href .= "</th>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";

                        if ($key != "link") {
                            $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<th $style'>$rVal";
                                $isExist_href .= "</th>";

                            }
                            $tfoot .= "$isExist_href";
                        }
                    }
                    $tfoot .= "</tr>";
                    $tfoot .= "</tfoot>";
                    //endregion
                }

//                if ($catCtr < (count($categories) - 2)) {
//                    $str .= "<tr>";
//                    $str .= "<td style='text-align:left;'></td>";
//                    foreach ($cabang as $cabID => $cabName) {
//                        $str .= "<td></td>";
//                        $str .= "<td></td>";
//                    }
//                    $str .= "<td></td>";
//                    $str .= "</tr>";
//                }

            }
            $str .= "</tbody>";
            $str .= $tfoot;

            //endregion


            $str .= "</table>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.rugilaba').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba','','" . strtoupper($subTitle) . "');
                            }
                        },
                                {
                                    text: 'Print',
                                    action: function (e, dt, node, config) {
                                        tableToPrint('rugilaba');
                                    }
                                }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('.rugilaba tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.rugilaba tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba').floatingScroll();
                $('.table-responsive.rugilaba').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            $str .= "p/l report is not yet available.<br>start making any transaction so this page has a content";
        }
        echo $str;


        break;

    case "keuangan_cashflow_konsolidasi":

//        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
//        $p = New Layout("$title", "$subTitle", "$templateSelected");

//        $strDates = blobEncode($defaultDate);
//        $strRekeningAliases = blobEncode($rekeningsNameAlias);
//        $strRekenings = blobEncode($rekeningsName);
//        $strNilais = blobEncode($rekenings);
//        $excel_data = "rekening=$strRekenings&alias=$strRekeningAliases&nilai=$strNilais&date=$strDates";
//        $excel_name = "rugi laba $defaultDate";
        $str = "";

//        $str .= "<div class='panel-body alert alert-info-dot'>";
//        $str .= "<div class='input-group'>";

//        if (isset($dateSelector) && ($dateSelector == true)) {
//            $str .= "<span class='input-group-add-on' >select month </span>";
//            $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?date='+this.value;\">";
//        }
//
//        if (isset($linkExcel)) {
//            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"download_excel();\" title='download data'><i class='fa fa-download'></i> EXCEL</button>";
//            $str .= downloadXlsx($linkExcel, $excel_data, $excel_name);
//        }
//
//        if (isset($buttonMode) && ($buttonMode['enabled'] == true)) {
//            $btn_label = $buttonMode['label'];
//            $btn_link = $buttonMode['link'];
//            $str .= "&nbsp;<button type='button' classs='btn btn-sm btn-success pull-right' onclick=\"location.href='$btn_link'\" title='download data'><i class='fa fa-bookmark-o'></i> $btn_label</button>";
//        }

//        $str .= "</div class='input-group'>";
//        $str .= "</div class='panel-body'>";


        if (sizeof($categories) > 0) {
            $idTbl = "cashflow";
            $str .= "<div class='table-responsive rugilaba'>";
//            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='$idTbl' class='table table-sm table-hover table-striped table-bordered grid cashflow nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<td>-</td>";
//            $str .= "<td></td>";
            foreach ($headersTahun as $key => $label) {

                $isExist_href = "<td>" . $label;
                $isExist_href .= "</td>";


                $str .= "$isExist_href";
                $totals[$key] = 0;
            }
            $str .= "</tr>";

            $str .= "</thead>";

            $str .= "<tbody>";
            $last = count($categories);
            $catNumb = 0;
            $tfoot = "";
            $summaryTotal = array();
            foreach ($categories as $catId => $catName) {
                // group top
                $catName_f = strtoupper($catName);
                $str .= "<tr>";
                $str .= "<td style='font-weight: bold;'>$catName_f</td>";
                foreach ($headersTahun as $thn_header) {
                    $topHeaderValues = isset($topHeaderIsi[$thn_header][$catId]) ? formatField("debet", $topHeaderIsi[$thn_header][$catId]) : "";
                    $str .= "<td>$topHeaderValues</td>";
                }
                $str .= "</tr>";

                $summary = array();
                // isi masing2 group top
                if (isset($rekenings[$catId])) {
                    foreach ($rekenings[$catId] as $rekID => $rekName) {
                        $rekName_f = ucwords($rekName);
                        $title2 = "mm_" . $rekID;
                        $target = base_url() . "laporankeuangan/Keuangan/detailCashflow/$rekID?date=$defaultDate&date1=$date_start&date2=$date_stop&prevdate1=$prev_date_start&prevdate2=$prev_date_stop";

                        $title3 = "nn_" . $rekID;

                        $str .= "<tr>";
                        $str .= "<td title2=$target title3=$title3 class='dt-nama-$idTbl'>";
                        $str .= "<a href='JavaScript:void(0);' onclick=\"\">";
                        $str .= "$rekName_f";
                        $str .= "</a>";
                        $str .= "</td>";

                        foreach ($headersTahun as $thn_header) {
                            $values = isset($dataRekening[$thn_header][$rekID]) ? $dataRekening[$thn_header][$rekID] : 0;
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
                                        if (!isset($summary[$thn_header])) {
                                            $summary[$thn_header] = 0;
                                        }
                                        if (!isset($summaryTotal[$thn_header])) {
                                            $summaryTotal[$thn_header] = 0;
                                        }
                                        $summary[$thn_header] += $values[$key];
                                        $summaryTotal[$thn_header] += $values[$key];
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

                                $date_start = $dateDetailCashflow[$thn_header][0];
                                $date_stop = $dateDetailCashflow[$thn_header][1];
                                $target_detail = base_url() . "laporankeuangan/Keuangan/detailCashflow/$rekID?date=$thn_header&date1=$date_start&date2=$date_stop";
                                $isExist_href = "<td $style title2=$target_detail title3=$title3 class='dt-nama-$idTbl'>$rVal";
                                $isExist_href .= "</td>";
                                $str .= "$isExist_href";
                            }
                        }

                        $str .= "</tr>";
                        //---------------------------
                    }
                }
                // summary bawah topHeader
                if (isset($topHeaderSummary[$catId])) {
                    $str .= "<tr>";
                    $str .= "<td style='font-weight: bold;'>" . $topHeaderSummary[$catId] . "</td>";
                    foreach ($headersTahun as $thn_header) {
                        if ($summary[$thn_header] < 0) {
                            $style = "style='text-align:right;color:red;'";
                            $rVal = "(" . number_format($summary[$thn_header] * -1, "0", ".", ",") . ")";
                        }
                        else {
                            $style = "";
                            $rVal = formatField("debet", $summary[$thn_header]);
                        }

                        $str .= "<td $style>" . $rVal . "</td>";
                    }
                    $str .= "</tr>";
                }

            }

            //---------------------
//            if (round($summaryTotal, 2) != round($selisihKas, 2)) {
//                $selisih = $summaryTotal - $selisihKas;
//                $str .= "<tr>";
//                $str .= "<td></td>";
//                $str .= "<td style='background-color:red;'>$summaryTotal != $selisihKas [$selisih]</td>";
//                $str .= "</tr>";
//            }
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
            $str .= $underMaintenance;
            $str .= "-----";
        }

        echo $str;
        break;

    case "viewNeracaAdj":
        // cekBiru("hitam");
        // arrPrint($rekenings);
        // arrPrint($rekeningsName);
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        // arrPrint($rekeningsNameAlias);
        // arrPrint($rekeningsName);

        if ((sizeof($categories) > 0) && (sizeof($rekenings) > 0)) {
            $totals = array(
                "debet" => 0,
                "kredit" => 0
            );



            $str .= "<div class='table-responsive tbl_head'>";
            $str .= "<table id='tbl_head' class='table table-sm table-hover table-striped table-bordered grid neraca_adj nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";

            //region header tabel
            $str .= "<thead>";

            //            $str .= "<tr bgcolor='#f0f0f0'>";
            //            $str .= "<th></th>";
            //            $str .= "<th></th>";
            //            foreach ($cabang as $cabID => $cabName) {
            //                foreach ($headers as $key => $label) {
            //                    $str .= "<th></th>";
            //                }
            //            }
            //            foreach ($headers as $key => $label) {
            //                $str .= "<th></th>";
            //            }
            //            $str .= "<th></th>";
            //            $str .= "</tr>";
            //
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th rowspan=''></th>";
            $str .= "<th rowspan=''></th>";

            $j = 0;
            $arrColumnStyle = array();
            // if (isset($cabang) && sizeof($cabang) > 0) {
            //     foreach ($cabang as $cabID => $cabName) {
            //         $j++;
            //         if ($j % 2 == 0) {
            //             $headerBgColor = "";
            //             $isiBgColor = "";
            //             $subBgColor = "";
            //             $totalBgColor = "";
            //         }
            //         else {
            //             $headerBgColor = "";
            //             $isiBgColor = "";
            //             $subBgColor = "";
            //             $totalBgColor = "";
            //         }
            //         $arrColumnStyle[$cabID] = array(
            //             "header" => $headerBgColor,
            //             "isi" => $isiBgColor,
            //             "subTotal" => $subBgColor,
            //             "total" => $totalBgColor,
            //         );
            //
            //         //                $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
            //         $str .= "<th colspan='2' class='text-center text-uppercase'>";
            //         $str .= "$cabName";
            //         $str .= "</th>";
            //         //                $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
            //     }
            // }

            //            $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";
            // arrPrint($masterHeader);
            // matiHEre();
            if(sizeof($masterHeader)>0){
                foreach($masterHeader as $mKey =>$dataHeaderLabel){
                    $label = $dataHeaderLabel["label"];
                    $str .= "<th colspan='2' class='text-center text-uppercase'>";
                    $str .= "$label  ";
                    $str .= "</th>";
                }
            }

            // $str .= "<th colspan='2' class='text-center text-uppercase'>";
            // $str .= "YTD";
            // $str .= "</th>";
            //            $str .= "<th colspanx='1' class='hiddenx'>&nbsp;</th>";

            //            $str .= "<th class='text-center text-uppercase' rrowspan='2'></th>";

            //            for($r=0;$r<=($j*2)+1;$r++){
            //                $str .= "<th colspanx='1' class='hiddenxx'>&nbsp;</th>";
            //            }

            $str .= "</tr>";

            $str .="<tr bgcolor='#f0f0f0'> ";
            if(sizeof($masterHeader)>0){
                $str .= "<th></th>";
                $str .= "<th></th>";
                foreach($masterHeader as $mKey =>$dataHeaderLabel){
                    $label = $dataHeaderLabel["label"];
                    foreach($dataHeaderLabel["rekening"] as $label){
                        $str .= "<th colspan='' class='text-center text-uppercase'>";
                        $str .= "$label";
                        $str .= "</th>";
                    }

                }
            }


            $str .= "</thead>";
            //endregion
            //region isi tabel
            $str .= "<tbody>";
           
            foreach ($categories as $catName) {

                //                if ($i == 0) {
                //                    // first
                //                } else if ($i == $len - 1) {
                //                    // last
                //                }

                //                $str .= "<div class='table-responsive tbl_$catName'>";
                //                $str .= "<table id='tbl_$catName' class='table display compact table-bordered'>";
                //                $str .= "<thead>";
                //region header category
                //                $str .= "<tr style='background-color: lightblue;' class='text-center text-bold text-uppercase'>";
                //                $str .= "<td column='consulente:'>$catName</td>";
                //                $str .= "<td column='$catName:'>$catName</td>";
                //                foreach ($cabang as $cabID => $cabName) {
                //                    $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";
                //                    foreach ($headers as $key => $label) {
                //                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                //                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor min-width: 125px;max-width: 125px;'>$label</td>";
                //                        }
                //                        else {
                //                            if($label!=""){
                //                                $isExist_href = "<td column='$label:' style='$bgColor min-width: 125px;max-width: 125px;'>" . $label . "</td>";
                //                            }
                //                            else{
                //                                $isExist_href = "<td column='-:' style='$bgColor min-width: 23px;max-width: 23px;'>" . $label . "</td>";
                //                            }
                //
                //                        }
                //                        $str .= "$isExist_href";
                //                    }
                //                }
                //                foreach ($headers as $key => $label) {
                //                    $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                //                    if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                //                        $isExist_href = $key == "link" ? "" : "<td style='$bgColor min-width: 150px;max-width: 150px;'>$label</td>";
                //                    }
                //                    else {
                //                        $isExist_href = "<td style='$bgColor min-width: 150px;max-width: 150px;'>" . $label . "</td>";
                //                    }
                //                    $str .= "$isExist_href";
                //                }
                //                $str .= "<td style='min-width: 150px;max-width: 150px;'>$catName</td>";
                //                $str .= "</tr>";
                //endregion
                //                $str .= "</thead>";
                //                $str .= "<tbody>";
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {
                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekKeterangan = isset($rekeningKeterangan[$rekName]) ? $rekeningKeterangan[$rekName] : "";
// matiHEre($rekNameAlias);
                    $rekAlias12Tmp = explode(' ', $rekNameAlias);
                    $rekAlias12 = count($rekAlias12Tmp) > 1 ? $rekAlias12Tmp[0] . " " . $rekAlias12Tmp[1] : $rekNameAlias;

                    $str .= "<tr>";
                    $str .= "<td column='consulente:'>$catName</td>";
                    //                    $str .= "<td column='$catName:'><span ttitle='$rekKeterangan' data-toggle='tooltip' data-placement='right' data-original-title='$rekKeterangan'>$rekNameAlias</span></td>";
                    $str .= "<td column='$catName:'>$rekNameAlias </td>";

                    //region isi rekening
                    foreach($masterHeader as $mKEY =>$nKeData){
                        $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                        foreach($nKeData["rekening"] as $rekKEY){
                            if(isset($dataRekenening[$mKEY][$catName][$rekID][$rekKEY])){
                                $value = $dataRekenening[$mKEY][$catName][$rekID][$rekKEY];
                            }else{
                                $value = 0;
                            }

                            if (is_numeric($value)) {
                                if ($value > 0) {
                                    $rVal = number_format($value, "0", ".", ",");
                                    $align = "text-align:right;";
                                }
                                elseif ($value < 0) {
                                    $rVal = "<r>(" . number_format($value * -1, "0", ".", ",") . ")</r>";
                                    $align = "text-align:right;";
                                }
                                else {
                                    $rVal = "";
                                    $align = "text-align:right;";
                                }
                                $subTotalsCatRight2[$mKEY][$rekKEY] += $value;
                            }
                            else {
                                $rVal = formatField($rekKEY, $value);
                                $align = "text-align:right;";
                            }
                            // $str .="<td class='text'>".formatField_he_format("harga",$val)."</td>";
                            $str .= "<td style='$bgColor$align$color$miring'>$rVal</td>";
                        }

                    }
                    
                    //emdregion


                    $str .= "</tr>";
                }

                //region subTotal bawah category

                //                $str .= "<tr style='background-color: #ffcece;' class='text-right text-bold text-uppercase'>";
                //                $str .= "<td column='consulente:'>scl_$catName</td>";
                //                $str .= "<td column='$catName'>$catName</td>";
                //                foreach ($cabang as $cabID => $cabName) {
                //                    $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                //                    foreach ($headers as $key => $label) {
                //                        $coLStr = isset($subTotals[$cabID][$catName][$key]) ? $subTotals[$cabID][$catName][$key] : "";
                //                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                //                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                //                        }
                //                        else {
                //                            if (is_numeric($coLStr)) {
                //                                if ($coLStr > 0) {
                //                                    $scatVal = number_format($coLStr, "0", ".", ",");
                //                                    $align = "text-align:right;";
                //                                }
                //                                elseif ($coLStr < 0) {
                //                                    $scatVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                //                                    $align = "text-align:right;";
                //                                }
                //                                else {
                //                                    $scatVal = "";
                //                                    $align = "text-align:right;";
                //                                }
                //                            }
                //                            else {
                //                                $scatVal = formatField($key, $coLStr);
                //                            }
                //                            $isExist_href = "<td style='font-size:1.3em;$bgColor$align'>";
                //                            $isExist_href .= $scatVal;
                //                            $isExist_href .= "</td>";
                //                        }
                //                        $str .= "$isExist_href";
                //                    }
                //                }

                // arrPrint($subTotalsCatRight2);
                // arrPrint($masterHeader);
                // foreach ($masterHeader as $key => $labelData) {
                //
                //     // foreach($labelData["rekening"] as $key){
                //     //     if ($key != "link") {
                //     //         $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";
                //     //
                //     //         if (!isset($totalsBottomRight[$key])) {
                //     //             $totalsBottomRight[$key] = 0;
                //     //         }
                //     //         $totalsBottomRight[$key] += $coLStr;
                //     //     }
                //     //     else {
                //     //         //                        $str .= "<td style='$bgColor'></td>";
                //     //     }
                //     // }
                //     // //                    $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                //     // if ($key != "link") {
                //     //     $coLStr = isset($subTotalsCatRight2[$catName][$key]) ? $subTotalsCatRight2[$catName][$key] : "";
                //     //
                //     //     if (!isset($totalsBottomRight[$key])) {
                //     //         $totalsBottomRight[$key] = 0;
                //     //     }
                //     //     $totalsBottomRight[$key] += $coLStr;
                //     //     //                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                //     //     //                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                //     //     //                        }
                //     //     //                        else {
                //     //     //                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                //     //     //                            $isExist_href .= "</td>";
                //     //     //                        }
                //     //     //                        $str .= "$isExist_href";
                //     // }
                //     // else {
                //     //     //                        $str .= "<td style='$bgColor'></td>";
                //     // }
                // }
                //                $str .= "<td>&nbsp;</td>";
                ////                $str .= "<td>&nbsp;</td>";
                //                $str .= "</tr>";
                //endregion

                //                $str .= "</tbody>";
                //                $str .= "</table class='table table-condensed'>";
                //                $str .= "</div>";

                //                $str .= "<script>\n
                //                            $('#tbl_$catName').DataTable({
                //                                dom: 'lBfrtip',
                //                                fixedHeader: true,
                //                                lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                //                                pageLength: -1,
                //                                stateSave: false,
                //                                paging: false,
                //                                ordering: false,
                //                                info: false,
                //                                searching: false,
                //                                buttons: [],
                //                                autoWidth: false
                //                            });
                //                \n</script>";

                //                $i++;
            }
            $str .= "</tbody>";
            //endregion

            // arrprint($totalsBottomRight);

            //  region total bawah

            $str .= "<tfoot>";
            $str .= "<tr style='background-color: #f0f0f0;' class='text-right text-bold'>";
            $str .= "<td></td>";
            $str .= "<td></td>";
            // $str .= "<td></td>";
            // $str .= "<td></td>";
            // $str .= "<td></td>";
            // arrPrint($subTotalsCatRight2);
            if (isset($cabang) && sizeof($cabang) > 0) {
            
                foreach ($cabang as $cabID => $cabName) {
                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrColumnStyle[$cabID]['total']) ? $arrColumnStyle[$cabID]['total'] : "";
                        $isExist_href = "";
                        $coLStr = isset($totals[$cabID][$key]) ? $totals[$cabID][$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        else {
                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr);
                            $isExist_href .= "</td>";
                        }
                        $str .= "$isExist_href";
                    }
                }
            }
            foreach($masterHeader as $mKey =>$mData){
                foreach($mData["rekening"] as $key){
                    $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";

                    if ($key != "link") {

                        $coLStr = isset($subTotalsCatRight2[$mKey][$key]) ? $subTotalsCatRight2[$mKey][$key] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        else {
                            $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                        }
                        $str .= "$isExist_href";
                    }
                    else {
                        $str .= "<td style='$bgColor'></td>";
                    }
                }
            }
            // foreach ($headers as $key => $label) {
            //     $bgColor = isset($arrHDColumnStyle['total']) ? $arrHDColumnStyle['total'] : "";
            //
            //     if ($key != "link") {
            //
            //         $coLStr = isset($totalsBottomRight[$key]) ? $totalsBottomRight[$key] : "";
            //         if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
            //             $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
            //         }
            //         else {
            //             $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
            //         }
            //         $str .= "$isExist_href";
            //     }
            //     else {
            //         $str .= "<td style='$bgColor'></td>";
            //     }
            // }
            
            $str .= "</tr>";
            $str .= "</tfoot>";
            //  endregion total bawah

            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $str .= "<script>

                var oTable = $('.grid.neraca_adj').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('tbl_head','','" . strtoupper($subTitle) . "');
                            }
                        },
                                {
                                    text: 'Print',
                                    action: function (e, dt, node, config) {
                                        tableToPrint('tbl_head');
                                    }
                                }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += valore;
                                    totale['Totale'][index2] += valore;
                                });
                            }
                        });
                        $('.neraca_adj tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right'>\"+addCommas(subtotale[i][a])+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.neraca_adj tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).nextUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.tbl_head').floatingScroll();
                $('.table-responsive.tbl_head').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";

        }
        else {
            // $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
            $str .= "";
        }
        echo $str;
        break;
    case "lapKeuanganAdj":
// cekHitam(__LINE__." pakai view ini");
        //        $pakai_ini = $pakai_konsolidasi;
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );
        //        $mainColspan = ((sizeof($headers) * (sizeof($cabang) + 1)) + 2);

        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $p = New Layout("$title", "$subTitle", "$templateSelected");
        $str = "";

        switch ($periode) {
            case "bulanan":
                $str .= "<div class='panel-body alert alert-info-dot'>";
                $str .= "<div class='input-group'>";
                $str .= "<span class='input-group-add-on' >select date </span>";
                $str .= "<input type='month' value='$defaultDate' min='$oldDate' 
            max='" . date("Y-m") . "' 
            onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";

                $str .= "</div class='input-group'>";
                $str .= "</div class='panel-body'>";
                break;
            case "tahunan":
                $str .= "<div class='panel-body alert alert-info-dot'>";
                $str .= "<div class='input-group'>";
                $str .= "<span class='input-group-add-on' >select year </span>";
                $str .= $p->selectTahun($defaultDate, "date");

                $str .= "</div class='input-group'>";
                $str .= "</div class='panel-body'>";
                break;
        }


        if (isset($neraca)) {
            // matiHEre(__LINE__);
            $str .= "";
            $str .= "<div id='neraca' class='coll-md-12'></div>";
            // $str .= "<div id='neraca2' >ini itu</div>";
            $str .= "<script>$('#neraca').load('$neraca');</script>";
        }
        if (isset($rugilaba)) {
            $str .= "";
            $str .= "<div id='rugilaba' class='col-md-12'></div>";
            $str .= "<script>$('#rugilaba').load('$rugilaba');</script>";
        }
        if (isset($cashflow)) {
            $str .= "";
            $str .= "<div id='cashflow' class='col-md-12'></div>";
            $str .= "<script>$('#cashflow').load('$cashflow');</script>";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
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

    case "keuangan_rugilaba_konsolidasi_adj":
        $arrHDColumnStyle = array(
            "header" => "background-color:none;",
            "isi" => "background-color:none;",
            "subTotal" => "background-color:none;",
            "total" => "background-color:none;",
        );

        //        $templateSelected = isset($_GET["mode"]) && $_GET["mode"] == "print" ? "application/template/pagesPrintnable.html" : "application/template/finance.html";
        //        $p = New Layout("$title", "$subTitle", "$templateSelected");
        //        $grMenu = isset($_GET['gr']) && $_GET['gr'] != '' ? "gr=" . $_GET['gr'] . "&" : "";
        $str = "";
        //        $str .= "<div class='panel-body alert alert-info-dot'>";
        //        $str .= "<div class='input-group'>";
        //        $str .= "<span class='input-group-add-on' >select date </span>";
        //        $str .= "<input type='month' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage?$grMenu" . "date='+this.value;\">";
        //        $str .= "</div class='input-group'>";
        //        $str .= "</div class='panel-body'>";


        if (sizeof($categories) > 0) {
            $totals = array(
                //                "debet" => 0,
                //                "kredit" => 0,
                "values" => 0,
            );

            $str .= "<div class='table-responsive rugilaba_adj'>";
            //            $str .= "<h4>$subTitle</h4>";
            $str .= "<table id='rugilaba_adj' class='table table-sm table-hover table-striped table-bordered grid rugilaba_adj nowrap compact'>";
            $str .= "<caption><h4 class='text-uppercase'>$subTitle</h4></caption>";

            //region header tabel
            $str .= "<thead>";
            $str .= "<tr bgcolor='#f0f0f0'>";
            $str .= "<th class='text-center'></th>";
            $str .= "<th class='text-center'></th>";

            $j = 0;
            $arrColumnStyle = array();
            if (isset($cabang) && (sizeof($cabang) > 0)) {
                foreach ($cabang as $cabID => $cabName) {
                    $j++;
                    if ($j % 2 == 0) {
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    else {
                        $headerBgColor = "";
                        $isiBgColor = "";
                        $subBgColor = "";
                        $totalBgColor = "";
                    }
                    $arrColumnStyle[$cabID] = array(
                        "header" => $headerBgColor,
                        "isi" => $isiBgColor,
                        "subTotal" => $subBgColor,
                        "total" => $totalBgColor,
                    );

                    //                $str .= "<th colspan='3' class='text-center' style='$headerBgColor'>";
                    //                $str .= "$cabName";
                    //                $str .= "</th>";
                    //
                    //                $str .= "<th></th>";

                }
            }
            if (isset($headersTahun) && sizeof($headersTahun) > 0) {

                foreach ($headersTahun as $thn_header) {
                    $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                    $str .= "$thn_header";
                    $str .= "</th>";
                }
            }

            if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                foreach ($headersKanan as $header_kanan) {
                    $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                    $str .= "$header_kanan";
                    $str .= "</th>";
                }
            }

            if(isset($headersMaster) & sizeof($headersMaster)>0){
                foreach($headersMaster as $header_master){
                    $str .= "<th ccolspan='2' class='text-center' style='" . $arrHDColumnStyle['header'] . "'>";
                    $str .= $header_master["label"];
                    $str .= "</th>";
                }
            }
            $str .= "</tr>";
            $str .= "</thead>";
            //endregion
            //region isi tabel
            $str .= "<tbody>";
            $tfoot = "";
            $catCount = count($categories);
            $cat = 1;
            foreach ($categories as $catCtr => $catName) {
                $catSubBottom = (isset($categoryRLBottom) && isset($categoryRLBottom[$catName])) ? $categoryRLBottom[$catName] : "";

                //region header category
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $str .= "<tr bgcolor='#f0f0f0'>";
                    $str .= "<td></td>";
                    $str .= "<td></td>";
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['header']) ? $arrColumnStyle[$cabID]['header'] : "";

                        foreach ($headers as $key => $label) {
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                            }
                            else {
                                $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                            }
                            $str .= "$isExist_href";
                        }
                    }

                    foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['header']) ? $arrHDColumnStyle['header'] : "";
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                            $isExist_href = $key == "link" ? "" : "<td style='$bgColor'>$label</td>";
                        }
                        else {
                            $isExist_href = "<td style='$bgColor'>" . $label . "</td>";
                        }
                        $str .= "$isExist_href";
                    }

                    $str .= "</tr>";
                }
                //endregion

                $numRest = 0;
                foreach ($rekeningsName[$catName] as $rekID => $rekName) {

                    $rekNameAlias = isset($rekeningsNameAlias[$rekName]) ? $rekeningsNameAlias[$rekName] : $rekName;
                    $rekNameAlias_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rekNameAlias;
                    $firstRow = $numRest == 0 ? "first_row" : "";
                    $str .= "<tr class='$firstRow' line='" . __LINE__ . "'>";
                    $str .= "<td column='consulente:'>$catSubBottom</td>";
                    $str .= "<td column='$catSubBottom:'>$rekNameAlias_f</td>";

                    //region isi category

                    foreach ($cabang as $cabID => $cabName) {
                        foreach ($headersTahun as $thn_header => $xx) {
                            $bgColor = isset($arrColumnStyle[$cabID]['isi']) ? $arrColumnStyle[$cabID]['isi'] : "";
                            if (!isset($subTotals[$thn_header][$cabID][$catName])) {
                                $subTotals[$thn_header][$cabID][$catName] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($totals[$thn_header][$cabID])) {
                                $totals[$thn_header][$cabID] = array(
                                    "values" => 0,
                                );
                            }
                            if (!isset($subTotalsRight[$thn_header][$rekName])) {
                                $subTotalsRight[$thn_header][$rekName] = array(
                                    "values" => 0,
                                );
                            }

                            foreach ($headers as $key => $label) {
                                if (!isset($subTotalsCatRight[$thn_header][$catName][$key])) {
                                    $subTotalsCatRight[$thn_header][$catName][$key] = 0;
                                }
                                if (!isset($totalsBottomRight[$thn_header][$key])) {
                                    $totalsBottomRight[$thn_header][$key] = 0;
                                }

                                $val = 0;
                                if (isset($rekenings[$thn_header][$catName][$cabID][$rekName])) {
                                    $value = $rekenings[$thn_header][$catName][$cabID][$rekName][$key];
                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    $rVal = "";
                                    if (is_numeric($value)) {
                                        if ($value > 0) {
                                            $rVal = number_format($value * 1, "0", ".", ",");
                                            $align = "text-align:right;";
                                        }
                                        elseif ($value < 0) {
                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                            $align = "text-align:right;color:red;";
                                        }
                                        else {
                                            $rVal = "";
                                            $align = "text-align:right;";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $value);
                                        $align = "text-align:right;";
                                    }
                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    //                                $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                }
                                else {
                                    $rVal = "";
                                    //                                $str .= "<td style='$bgColor'>$rVal</td>";
                                }

                                if (is_numeric($val)) {
                                    if ($key != "link" && $key != "link_detail") {

                                        $totals[$thn_header][$cabID][$key] += $val;
                                        $subTotals[$thn_header][$cabID][$catName][$key] += $val;
                                        $subTotalsCatRight[$thn_header][$catName][$key] += $val;
                                        $totalsBottomRight[$thn_header][$key] += $val;
                                        $subTotalsRight[$thn_header][$rekName][$key] += $val;
                                    }
                                }
                            }
                        }
                    }

                    //endregion


                    // ini milik holding...
                    if (isset($headersTahun) && sizeof($headersTahun) > 0) {
                        foreach ($headersTahun as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                                if ($key != "link") {
                                    if (!isset($subTotalsCatRight2[$thn_header][$catName])) {
                                        $subTotalsCatRight2[$thn_header][$catName] = array(
                                            "values" => 0,
                                        );
                                    }
                                    //                                if (isset($subTotalsRight[$thn_header][$rekName][$key])) {
                                    //                                    $value = $subTotalsRight[$thn_header][$rekName][$key];
                                    //                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    //                                    if (is_numeric($value)) {
                                    //                                        if ($value > 0) {
                                    //                                            $rVal = number_format($value, "0", ".", ",");
                                    //                                            $align = "text-align:right;";
                                    //                                        }
                                    //                                        elseif ($value < 0) {
                                    //                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                    //                                            $align = "text-align:right;color:red;";
                                    //                                        }
                                    //                                        else {
                                    //                                            $rVal = "";
                                    //                                            $align = "text-align:right;";
                                    //                                        }
                                    //                                        $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                    //                                    }
                                    //                                    else {
                                    //                                        $rVal = formatField($key, $value);
                                    //                                        $align = "text-align:right;";
                                    //                                    }
                                    //                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    //                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    //                                }
                                    //                                else {
                                    //                                    $str .= "<td style='$bgColor'></td>";
                                    //                                    $str .= "<td style='$bgColor'></td>";
                                    //                                }

                                    if (isset($rekeningsKonsolidasiNilai[$thn_header][$catName][$rekName][$key])) {
                                        $value = $rekeningsKonsolidasiNilai[$thn_header][$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        if (is_numeric($value)) {
                                            if ($value > 0) {
                                                $rVal = number_format($value, "0", ".", ",");
                                                $align = "text-align:right;";
                                            }
                                            elseif ($value < 0) {
                                                $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                                $align = "text-align:right;color:red;";
                                            }
                                            else {
                                                $rVal = "";
                                                $align = "text-align:right;";
                                            }
                                            $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                        }
                                        else {
                                            $rVal = formatField($key, $value);
                                            $align = "text-align:right;";
                                        }
                                        $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                        $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    }
                                    else {
                                        $str .= "<td style='$bgColor'></td>";
                                        //                                    $str .= "<td style='$bgColor'></td>";
                                    }
                                }
                            }
                        }
                    }
                    if (isset($headersKanan) && sizeof($headersKanan) > 0) {
                        foreach ($headersKanan as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                                if ($key != "link") {
                                    if (!isset($subTotalsCatKanan[$thn_header][$catName])) {
                                        $subTotalsCatKanan[$thn_header][$catName] = array(
                                            "values" => 0,
                                        );
                                    }
                                    //                                if (isset($subTotalsRight[$thn_header][$rekName][$key])) {
                                    //                                    $value = $subTotalsRight[$thn_header][$rekName][$key];
                                    //                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    //                                    if (is_numeric($value)) {
                                    //                                        if ($value > 0) {
                                    //                                            $rVal = number_format($value, "0", ".", ",");
                                    //                                            $align = "text-align:right;";
                                    //                                        }
                                    //                                        elseif ($value < 0) {
                                    //                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                    //                                            $align = "text-align:right;color:red;";
                                    //                                        }
                                    //                                        else {
                                    //                                            $rVal = "";
                                    //                                            $align = "text-align:right;";
                                    //                                        }
                                    //                                        $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                    //                                    }
                                    //                                    else {
                                    //                                        $rVal = formatField($key, $value);
                                    //                                        $align = "text-align:right;";
                                    //                                    }
                                    //                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    //                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    //                                }
                                    //                                else {
                                    //                                    $str .= "<td style='$bgColor'></td>";
                                    //                                    $str .= "<td style='$bgColor'></td>";
                                    //                                }

                                    if (isset($rekeningsKonsolidasiKanan[$thn_header][$catName][$rekName][$key])) {
                                        $value = $rekeningsKonsolidasiKanan[$thn_header][$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        if (is_numeric($value)) {
                                            if ($value > 0) {
                                                $rVal = number_format($value, "0", ".", ",");
                                                $align = "text-align:right;";
                                            }
                                            elseif ($value < 0) {
                                                $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                                $align = "text-align:right;color:red;";
                                            }
                                            else {
                                                $rVal = "";
                                                $align = "text-align:right;";
                                            }
                                            $subTotalsCatKanan[$thn_header][$catName][$key] += $val;
                                        }
                                        else {
                                            $rVal = formatField($key, $value);
                                            $align = "text-align:right;";
                                        }
                                        $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                        $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    }
                                    else {
                                        $str .= "<td style='$bgColor'></td>";
                                        //                                    $str .= "<td style='$bgColor'></td>";
                                    }
                                }
                            }
                        }
                    }

                    if (isset($headersMaster) && sizeof($headersMaster) > 0) {
                        foreach ($headersMaster as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $bgColor = isset($arrHDColumnStyle['isi']) ? $arrHDColumnStyle['isi'] : "";
                                if ($key != "link") {
                                    if (!isset($subTotalsCatKanan[$thn_header][$key])) {
                                        $subTotalsCatKanan[$thn_header][$key] = 0;
                                    }
                                    if (!isset($subTotalsCatRight2[$thn_header][$catName])) {
                                        $subTotalsCatRight2[$thn_header][$catName] = array(
                                            "values" => 0,
                                        );
                                    }
                                    //                                if (isset($subTotalsRight[$thn_header][$rekName][$key])) {
                                    //                                    $value = $subTotalsRight[$thn_header][$rekName][$key];
                                    //                                    $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                    //                                    if (is_numeric($value)) {
                                    //                                        if ($value > 0) {
                                    //                                            $rVal = number_format($value, "0", ".", ",");
                                    //                                            $align = "text-align:right;";
                                    //                                        }
                                    //                                        elseif ($value < 0) {
                                    //                                            $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                    //                                            $align = "text-align:right;color:red;";
                                    //                                        }
                                    //                                        else {
                                    //                                            $rVal = "";
                                    //                                            $align = "text-align:right;";
                                    //                                        }
                                    //                                        $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                    //                                    }
                                    //                                    else {
                                    //                                        $rVal = formatField($key, $value);
                                    //                                        $align = "text-align:right;";
                                    //                                    }
                                    //                                    $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                    //                                    $str .= "<td style='$bgColor$align'>$rVal_f</td>";
                                    //                                }
                                    //                                else {
                                    //                                    $str .= "<td style='$bgColor'></td>";
                                    //                                    $str .= "<td style='$bgColor'></td>";
                                    //                                }

                                    if (isset($rekeningsKonsolidasiNilai[$thn_header][$catName][$rekName][$key])) {
                                        $value = $rekeningsKonsolidasiNilai[$thn_header][$catName][$rekName][$key];
                                        $val = (isset($value) && strlen($value) > 0) ? $value : "0";
                                        if (is_numeric($value)) {
                                            if ($value > 0) {
                                                $rVal = number_format($value, "0", ".", ",");
                                                $align = "text-align:right;";
                                            }
                                            elseif ($value < 0) {
                                                $rVal = "(" . number_format($value * -1, "0", ".", ",") . ")";
                                                $align = "text-align:right;color:red;";
                                            }
                                            else {
                                                $rVal = "";
                                                $align = "text-align:right;";
                                            }

                                            $subTotalsCatRight2[$thn_header][$catName][$key] += $val;
                                            $subTotalsCatKanan[$thn_header][$key] += $val;
                                        }
                                        else {
                                            $rVal = formatField($key, $value);
                                            $align = "text-align:right;";
                                        }
                                        $rVal_f = in_array($rekName, $rekeningBlacklist) ? "&nbsp;" : $rVal;
                                        $str .= "<td style='$bgColor$align'>$rVal_f </td>";
                                    }
                                    else {
                                        $str .= "<td style='$bgColor'></td>";
                                        //                                    $str .= "<td style='$bgColor'></td>";
                                    }
                                }
                            }
                        }
                    }
                    $str .= "</tr>";
                    $numRest++;
                }

//                 matiHEre();


                $cat++;
                //                if ($catCtr < (count($categories) - 2)) {
                //                    $str .= "<tr>";
                //                    $str .= "<td style='text-align:left;'></td>";
                //                    foreach ($cabang as $cabID => $cabName) {
                //                        $str .= "<td></td>";
                ////                        $str .= "<td></td>";
                //                    }
                ////                    $str .= "<td></td>";
                ////                    $str .= "<td>&nbsp;</td>";
                //                    $str .= "</tr>";
                //                }

            }
            // arrPrint($subTotalsCatKanan);
            if (sizeof($subTotalsCatKanan)>0) {
                $tfoot .= "<tfoot>";
                $tfoot .= "<tr style='background-color: lightblue;'>";
                $tfoot .= "<td>-</td>";
                $tfoot .= "<td style='text-align:left;font-size:1.3em;'>$catSubBottom*</td>";
                if (isset($cabang) && (sizeof($cabang) > 0)) {
                    foreach ($cabang as $cabID => $cabName) {
                        $bgColor = isset($arrColumnStyle[$cabID]['subTotal']) ? $arrColumnStyle[$cabID]['subTotal'] : "";
                        foreach ($headersTahun as $thn_header => $xx) {
                            foreach ($headers as $key => $label) {
                                $coLStr = isset($subTotals[$thn_header][$cabID][$catName][$key]) ? $subTotals[$thn_header][$cabID][$catName][$key] : "";
                                $isExist_href = "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    //                            $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    if ($key != "link") {
                                        //                                    $isExist_href = "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                    }
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    //                                $isExist_href = "<td $style>$rVal";
                                    //                                $isExist_href .= "</td>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                }
                if (isset($headersTahun) && sizeof($headersTahun) > 0) {

                    foreach ($headersTahun as $thn_header => $xx) {
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                            if ($key != "link") {
                                $coLStr = isset($subTotalsCatRight2[$thn_header][$catName][$key]) ? $subTotalsCatRight2[$thn_header][$catName][$key] : "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    $isExist_href = "<td $style'>$rVal";
                                    $isExist_href .= "</td>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                }
                if (isset($headersKanan) && sizeof($headersKanan) > 0) {

                    foreach ($headersKanan as $thn_header => $xx) {
                        foreach ($headers as $key => $label) {
                            $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                            if ($key != "link") {
                                $coLStr = isset($subTotalsCatKanan[$thn_header][$catName][$key]) ? $subTotalsCatKanan[$thn_header][$catName][$key] : "";
                                if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                    $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                                }
                                else {
                                    if (is_numeric($coLStr)) {
                                        if ($coLStr >= 0) {
                                            $rVal = number_format($coLStr * 1, "0", ".", ",");
                                            $style = "style='text-align:right;font-size:1.3em;'";
                                        }
                                        else {
                                            $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                            $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                        }
                                    }
                                    else {
                                        $rVal = formatField($key, $coLStr);
                                        $style = "";
                                    }
                                    $isExist_href = "<td $style'>$rVal";
                                    $isExist_href .= "</td>";
                                }
                                $tfoot .= "$isExist_href";
                            }
                        }
                    }
                }
                if (isset($headersMaster) && sizeof($headersMaster) > 0) {
                    foreach ($headersMaster as $thn_header => $xx) {
                        foreach ($headers as $key => $label) {
                        $bgColor = isset($arrHDColumnStyle['subTotal']) ? $arrHDColumnStyle['subTotal'] : "";
                        if ($thn_header != "link") {
                            $coLStr = isset($subTotalsCatKanan[$thn_header][$key]) ? $subTotalsCatKanan[$thn_header][$key] : "";
                            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                                $isExist_href = $key == "link" ? "" : "<td style='font-size:1.3em;$bgColor'>" . formatField($key, $coLStr) . "</td>";
                            }
                            else {
                                if (is_numeric($coLStr)) {
                                    if ($coLStr >= 0) {
                                        $rVal = number_format($coLStr * 1, "0", ".", ",");
                                        $style = "style='text-align:right;font-size:1.3em;'";
                                    }
                                    else {
                                        $rVal = "(" . number_format($coLStr * -1, "0", ".", ",") . ")";
                                        $style = "style='text-align:right;font-size:1.3em;color:red;'";
                                    }
                                }
                                else {
                                    $rVal = formatField($key, $coLStr);
                                    $style = "";
                                }
                                $isExist_href = "<td $style'>$rVal";
                                $isExist_href .= "</td>";
                            }
                            $tfoot .= "$isExist_href";
                        }
                        }
                    }
                }
                //                    $tfoot .= "<th>-</th>";
                // $tfoot .= "<td>-</td>";
                // $tfoot .= "<td>-</td>";
                // $tfoot .= "<td>-</td>";

                $tfoot .= "</tr>";
                $tfoot .= "</tfoot>";
            }
            $str .= "</tbody>";
            $str .= $tfoot;
            //endregion


            $str .= "</table class='table table-condensed'>";
            $str .= "</div>";

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $str .= "<script>

                var oTable = $('.grid.rugilaba_adj').not('.initialized').addClass('initialized').show().DataTable({
                    columnDefs: [
                        { visible: false, targets: 0 }
                    ],
                    stateSave: false,
                    autoWidth: false,
                    bLengthChange : false, //hidden dropdown banyak data ditampilkan
                    ordering: false, //disable order
                            fixedHeader: true,
                    searching: false, //hidden search form
                    info: false, //hidden informasi bawah
                    paging: false, //hidden tombol paging
                    stateDuration: 60*60*24*365,
                    displayLength: -1, //tampilkan semua data
                    dom: 'lfBTrtip',
                    buttons: [
                        {
                            text: 'Download Excel',
                            action: function (e, dt, node, config) {
                                tableToExcel('rugilaba_adj','','" . strtoupper($subTitle) . "');
                            }
                        },
                                {
                                    text: 'Print',
                                    action: function (e, dt, node, config) {
                                        tableToPrint('rugilaba_adj');
                                    }
                                }
                    ],
                    drawCallback: function ( settings ) {
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
                        var colonne = api.row(0).data().length;
                        var totale = new Array();
                            totale['Totale']= new Array();
                        var groupid = -1;
                        var subtotale = new Array();
                        var arrCountGroup = new Array();
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(arrCountGroup[group]==undefined){
                                arrCountGroup[group] = 0
                            }
                            arrCountGroup[group] += 1
                        });
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if(group!='' && arrCountGroup[group] && arrCountGroup[group]*1>1){
                                if ( last !== group ) {
                                    groupid++;
                                    $(rows).eq( i + (arrCountGroup[group]-1) ).after(
                                        \"<tr class=group><td style='font-weight: 700;font-size: 1.2em;'>\"+group+'</td></tr>'
                                    );
                                    last = group;
                                }
                                val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                                $.each(val,function(index2,val2){
                                    if (typeof subtotale[groupid] =='undefined'){
                                        subtotale[groupid] = new Array();
                                    }
                                    if (typeof subtotale[groupid][index2] =='undefined'){
                                        subtotale[groupid][index2] = 0;
                                    }
                                    if (typeof totale['Totale'][index2] =='undefined'){ totale['Totale'][index2] = 0; }
                                    valore = Number(val2.replace('€','').replace(',',''));
                                    if(isNaN(valore)){
                                        var testValorVal2 = accounting.unformat(val2);
                                        valore = testValorVal2
                                    }
                                    subtotale[groupid][index2] += removeDecimal(valore);
                                    totale['Totale'][index2] += removeDecimal(valore);

                                });
                            }
                        });
                        $('.rugilaba_adj tbody').find('.group').each(function (i,v) {
                            var rowCount = $(this).nextUntil('.group').length;
                            $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'html': '' })));
                            var subtd = '';
                            for (var a=2;a<colonne;a++){
                                if(subtotale[i][a]*1==0 || subtotale[i][a]*1>0 || subtotale[i][a]*1<0){
                                    if(subtotale[i][a]*1<0){
                                        subtd += \"<td align='right' style='color: red;font-weight: 700;font-size: 1.2em;'>(\"+(addCommas(removeDecimal(subtotale[i][a])*-1))+')</td>';
                                                }
                                                else{
                                        if(subtotale[i][a]==0){
                                            subtd += \"<td></td>\";
                                                }
                                        else{
                                            subtd += \"<td align='right' style='font-weight: 700;font-size: 1.2em;'>\"+addCommas(removeDecimal(subtotale[i][a]))+'</td>';
                                        }
                                    }
                                }
                                else{
                                    subtd += '<td></td>';
                                }
                            }
                            $(this).append(subtd);
                                        });
                                    }
                                });
                // Collapse / Expand Click Groups
                $('.grid.rugilaba_adj tbody').on( 'click', 'tr.group', function () {
                    var rowsCollapse = $(this).prevUntil('.group');
                    $(rowsCollapse).toggleClass('hidden');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('fa-minus-circle fa-plus-circle');
                    $('.btnplusminus', $(this).find('td:first')[0] ).toggleClass('text-green');
                });
                $(window).resize(function() {
                    oTable.draw(false)
                            });

                $('.table-responsive.rugilaba_adj').floatingScroll();
                $('.table-responsive.rugilaba_adj').scroll(
                  delay_v2(function () {
                      $('.grid').DataTable().fixedHeader.adjust();
                  }, 200)
                );
                    </script>";
            }

        }
        else {
            // $str .= "neraca is not yet available.<br>start making any transaction so this page has a content";
            $str .= "";
        }

        echo $str;
        break;
}