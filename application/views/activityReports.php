<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/14/2019
 * Time: 5:16 PM
 */

switch ($mode) {
    case "recap":

        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        //region main conten
        $content = "";
        if (sizeof($stepNames) > 0) {
            $content .= "<ul class='nav nav-tabs'>";

            foreach ($stepNames as $stID => $stLabel) {


                $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
                $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
                $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";

                $content .= "<li class='nav-item'>";
                if (strcmp($stID, $selectedStep) == 0) {
                    //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
                    $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
                    //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
                    $content .= "<span class='fa fa-adjust'></span> ";
                    $content .= $stLabel;
                    $content .= "</a>";
                }
                else {
                    $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
                    $content .= $stLabel;
                    $content .= "</a>";


                }


                $content .= "</li>";

            }

            $content .= "</ul>";
        }

        if (sizeof($names) > 0) {
            $content .= "<table align='center' class='table table-condensed table-bordered'>";
            $content .= "<tr>";
            foreach ($names as $nID => $nSpec) {
                if (array_key_exists($nID, $availFilters)) {

                    $bgColor = $nID == $selectedFilter ? "#ccccdf" : "#e5e5e0";
                    $content .= "<td align='center' bgcolor='$bgColor'>";
                    $nameLabel = $identifierLabels[$nID] . createObjectSuffix($identifierLabels[$nID]);
                    if ($nID != $selectedFilter) {
                        $content .= "<a href='$thisPage?stID=$selectedStep&sID=$nID'>";
                        $content .= $nameLabel;
                        $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
                        $content .= "</a>";
                    }
                    else {
                        $content .= $nameLabel;
                        $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
                    }


                    $content .= "</td>";
                }
            }
            $content .= "</tr>";
            $content .= "</table>";


            if (isset($names[$selectedFilter]) && sizeof($names[$selectedFilter]) > 0) {

                $content .= "<div class='clearfix'>&nbsp;</div>";
                $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main')\"> Export/Download to Excel </span> </div>";
                $content .= "<div class='clearfix'>&nbsp;</div>";
                $content .= "<table id='main' align='center' class='table table-condensed table-bordered' style='table-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;;'>";
                $content .= "<tr>";
                $content .= "<td colspan='31' class='text-center'><h4>$title $subTitle by $identifierLabels[$selectedFilter]</h4>";
                $content .= "</td>";
                $content .= "</tr>";
                $content .= "<tr bgcolor='#f0f0f0'>";
                $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";

                $content .= "</td>";

                $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
                $content .= "<span class='pull-right'>$timeLabel <span class='fa fa-angle-double-right'></span></span> <br>";
                $content .= "<span class='fa fa-angle-double-down'></span> ";
                $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
                $content .= "</td>";
                foreach ($times as $pID => $pName) {
                    $content .= "<td align='center' colspan='2' class='text-muted'>";
                    if (isset($subPage)) {
                        $content .= "<a href='" . $subPage . "?time=$pID'>";
                        $content .= $pName;
                        $content .= "</a>";
                    }
                    else {
                        $content .= $pName;
                    }

                    $content .= "</td>";
                }

                $content .= "<td bgcolor='#009900' align='center' colspan='2' class='text-muted text-white'>";
                $content .= "TOTAL";
                $content .= "</td>";
                $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
                $content .= "AVG";
                $content .= "</td>";

                $content .= "</tr>";
                $content .= "<tr bgcolor='#e5e5e5'>";

                foreach ($times as $pID => $pName) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "qty";
                    $content .= "</td>";

                    $content .= "<td align='center' class='text-blue text-muted'>";
                    $content .= "IDR<br><small>(thou)</small>";
                    $content .= "</td>";
                }
                $content .= "<td bgcolor='#008800' align='center' class='text-muted text-white'>";
                $content .= "qty";
                $content .= "</td>";

                $content .= "<td align='center' class='text-blue text-muted'>";
                $content .= "IDR<br><small>(thou)</small>";
                $content .= "</td>";

                $content .= "<td bgcolor='#005588' align='center' class='text-muted text-white'>";
                $content .= "qty";
                $content .= "</td>";

                $content .= "<td align='center' class='text-blue text-muted'>";
                $content .= "IDR<br><small>(thou)</small>";
                $content .= "</td>";

                $content .= "</tr>";

                //                echo("ahghsga sas ga sas a");

                $no = 0;
                $sumQty = 0;
                $sumVal = 0;
                $totalV = array();
                $totalH = array();
                foreach ($names[$selectedFilter] as $oID => $oName) {
                    //                    cekBiru($oID);
                    $no++;
                    $content .= "<tr>";

                    $content .= "<td align='right' class='text-muted'>";
                    $content .= $no;
                    $content .= "</td>";


                    $content .= "<td>";
                    $content .= $oName;
                    $content .= "</td>";

                    $totalH[$oID] = array(
                        "qty" => 0,
                        "value" => 0,
                    );

                    foreach ($times as $pID => $pName) {
                        $qty = isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty']) ? number_format($recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty']) : "";
                        $val = isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['value']) ? number_format($recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'] / 1000) : "";
                        if (isset($historyPage)) {
                            $extPID = $pID . "-01";
                            $addLinkParam = array(
                                // "dtime like $pID%",
                                "dtime" => $extPID,
                                "$selectedFilter" => $oID,
                            );
                            $histLink['open'] = "<a href='$historyPage&addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }
                        $content .= "<td align='right' class='text-muted'>";
                        $content .= $histLink['open'] . $qty . $histLink['close'];
                        $content .= "</td>";
                        $content .= "<td align='right' class='text-muted text-blue'>";
                        $content .= $histLink['open'] . $val . $histLink['close'];
                        $content .= "</td>";
                        //
                        //                        cekkuning("hitung-menghitung");
                        if (!isset($totalV[$pID])) {
                            $totalV[$pID] = array(
                                "qty" => 0,
                                "value" => 0,
                            );
                            //                            cekkuning("setting totalV");
                        }
                        if (isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty']) && isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'])) {
                            //                            cekkuning("ada isi, mengakumulasi totalV");
                            $totalV[$pID]['qty'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty'];
                            $totalV[$pID]['value'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'];
                            $totalH[$oID]['qty'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty'];
                            $totalH[$oID]['value'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'];

                        }

                    }

                    $qty = isset($totalH[$oID]['qty']) ? number_format($totalH[$oID]['qty']) : "";
                    $val = isset($totalH[$oID]['value']) ? number_format($totalH[$oID]['value'] / 1000) : "";

                    $content .= "<td align='right' class='text-muted'>";
                    $content .= $histLink['open'] . $qty . $histLink['close'];
                    $content .= "</td>";
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= $histLink['open'] . $val . $histLink['close'];
                    $content .= "</td>";

                    $sumQty += $totalH[$oID]['qty'];
                    $sumVal += ($totalH[$oID]['value'] / 1000);

                    $content .= "</tr>";
                }


                $content .= "<tr bgcolor='#e5e5e5'>";

                $content .= "<td align='right' class='text-muted'>";
                $content .= "";
                $content .= "</td>";

                $content .= "<td>";
                $content .= "TOTAL";
                $content .= "</td>";

                foreach ($times as $pID => $pName) {
                    $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                    $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                    $content .= "<td align='right' class='text-muted'>";
                    $content .= $qty;
                    $content .= "</td>";
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= $val;
                    $content .= "</td>";

                }

                $content .= "<td>$sumQty</td>";
                $content .= "<td>" . formatField("value", $sumVal) . "</td>";
                for ($i = 1; $i <= 2; $i++) {
                    $content .= "<td class='text-center'>-</td>";
                }


                $content .= "</tr>";


                $content .= "</table>";


            }
            else {
                $content .= ("<div class=\"box-body\">");
                $content .= ("no report found.<br>");
                $content .= ("to go back to index, you can click BACK button<br>");
                $content .= ("</div class=\"box-body\">");
            }
        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }
        //endregion

        //region conten2
        $content2 = "";


        if (sizeof($recapName) > 0) {
            if (sizeof($recapName > 0)) {
                $content2 .= "<div class='clearfix'>&nbsp;</div>";
                $content2 .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main2')\"> Export/Download to Excel </span> </div>";
                $content2 .= "<div class='clearfix'>&nbsp;</div>";
                $content2 .= "<table id='main2' align='center' class='table table-condensed table-bordered' style='table-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;;'>";
                $content2 .= "<tr>";
                $content2 .= "<td colspan='29' class='text-center'><h4>$title $subTitle (by seller)</h4>";
                $content2 .= "</td>";
                $content2 .= "</tr>";
                $content2 .= "<tr bgcolor='#f0f0f0'>";
                $content2 .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";

                $content2 .= "</td>";
                $content2 .= "<td rowspan='2' valign='middle' align='center' class='text-muted'>Seller";

                $content2 .= "</td>";

                $content2 .= "<td rowspan='2' valign='bottom' class='text-muted'>";
                $content2 .= "<span class='pull-right'>$timeLabel <span class='fa fa-angle-double-right'></span></span> <br>";
                $content2 .= "<span class='fa fa-angle-double-down'></span> ";
                $content2 .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
                $content2 .= "</td>";
                foreach ($times as $pID => $pName) {
                    $content2 .= "<td align='center' colspan='2' class='text-muted'>";
                    if (isset($subPage)) {
                        $content2 .= "<a href='" . $subPage . "?time=$pID'>";
                        $content2 .= $pName;
                        $content2 .= "</a>";
                    }
                    else {
                        $content2 .= $pName;
                    }

                    $content2 .= "</td>";
                }

                $content2 .= "<td bgcolor='#009900' align='center' colspan='2' class='text-muted text-white'>";
                $content2 .= "TOTAL";
                $content2 .= "</td>";
                //                $content2 .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
                //                $content2 .= "AVG";
                //                $content2 .= "</td>";

                $content2 .= "</tr>";
                $content2 .= "<tr bgcolor='#e5e5e5'>";

                foreach ($times as $pID => $pName) {
                    $content2 .= "<td align='center' class='text-muted'>";
                    $content2 .= "qty";
                    $content2 .= "</td>";

                    $content2 .= "<td align='center' class='text-blue text-muted'>";
                    $content2 .= "IDR<br><small>(thou)</small>";
                    $content2 .= "</td>";
                }
                $content2 .= "<td bgcolor='#008800' align='center' class='text-muted text-white'>";
                $content2 .= "qty";
                $content2 .= "</td>";

                $content2 .= "<td align='center' class='text-blue text-muted'>";
                $content2 .= "IDR<br><small>(thou)</small>";
                $content2 .= "</td>";

                //                $content2 .= "<td bgcolor='#005588' align='center' class='text-muted text-white'>";
                //                $content2 .= "qty";
                //                $content2 .= "</td>";
                //
                //                $content2 .= "<td align='center' class='text-blue text-muted'>";
                //                $content2 .= "IDR<br><small>(thou)</small>";
                $content2 .= "</td>";

                $content2 .= "</tr>";
                //                arrPrint($recapChild);
                //                matiHEre();
                $sumQty = 0;
                $sumVal = 0;
                $totalV = array();
                foreach ($recapName as $kn => $tmpListedMaster) {
                    $n = 0;
                    if (sizeof($tmpListedMaster) > 0) {
                        foreach ($tmpListedMaster as $sID => $sIdData) {
                            $n++;
                            $rowspan = isset($recapChild[$selectedStep][$kn][$selectedFilter][$sID]) ? sizeof($recapChild[$selectedStep][$kn][$selectedFilter][$sID]) + 1 : "";
                            $rowspan2 = $rowspan > 2 ? "" : "2";
                            $content2 .= "<tr>";
                            $content2 .= "<td rowspan='$rowspan'>$n</td>";
                            $content2 .= "<td rowspan='$rowspan'>" . $recapNameLabel[$sID] . "</td>";
                            $content2 .= "</tr>";

                            if (isset($recapChild[$selectedStep][$kn][$selectedFilter][$sID]) && sizeof($recapChild[$selectedStep][$kn][$selectedFilter][$sID]) > 0) {
                                foreach ($recapChild[$selectedStep][$kn][$selectedFilter][$sID] as $ddID => $ddLabel) {
                                    $totalH[$ddID] = array(
                                        "qty" => 0,
                                        "value" => 0,
                                    );
                                    $rowspan3 = $rowspan2 == 2 ? "1" : $rowspan2;
                                    $content2 .= "<tr>";
                                    $content2 .= "<td rowspan='$rowspan3'>$ddLabel</td>";
                                    foreach ($times as $pID => $pName) {
                                        $qty = isset($recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['qty']) ? number_format($recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['qty']) : "";
                                        $val = isset($recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['value']) ? number_format($recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['value'] / 1000) : "";
                                        if (isset($historyPage)) {
                                            $extPID = $pID . "-01";
                                            $addLinkParam = array(
                                                // "dtime like $pID%",
                                                "dtime" => $extPID,
                                                "$selectedFilter" => $oID,
                                            );
                                            $histLink['open'] = "<a href='$historyPage&addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                                            $histLink['close'] = "</a>";
                                        }
                                        else {
                                            $histLink['open'] = "";
                                            $histLink['close'] = "";
                                        }
                                        $content2 .= "<td align='right' class='text-muted'>";
                                        $content2 .= $histLink['open'] . $qty . $histLink['close'];
                                        $content2 .= "</td>";
                                        $content2 .= "<td align='right' class='text-muted text-blue'>";
                                        $content2 .= $histLink['open'] . $val . $histLink['close'];
                                        $content2 .= "</td>";
                                        //
                                        //                        cekkuning("hitung-menghitung");
                                        if (!isset($totalV[$pID])) {
                                            $totalV[$pID] = array(
                                                "qty" => 0,
                                                "value" => 0,
                                            );
                                            //                            cekkuning("setting totalV");
                                        }
                                        if (isset($recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['qty']) && isset($recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['value'])) {
                                            //                                                                        cekkuning("ada isi, mengakumulasi totalV");
                                            $totalV[$pID]['qty'] += $recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['qty'];
                                            $totalV[$pID]['value'] += $recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['value'];
                                            $totalH[$ddID]['qty'] += $recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['qty'];
                                            $totalH[$ddID]['value'] += $recapList[$selectedStep][$kn][$selectedFilter][$pID][$sID][$ddID]['value'];

                                        }
                                        else {
                                            //                                            cekbiru("oraa");
                                        }


                                    }

                                    $qty = isset($totalH[$ddID]['qty']) ? number_format($totalH[$ddID]['qty']) : "";
                                    $val = isset($totalH[$ddID]['value']) ? number_format($totalH[$ddID]['value'] / 1000) : "";

                                    $content2 .= "<td align='right' class='text-muted'>";
                                    $content2 .= $histLink['open'] . $qty . $histLink['close'];
                                    $content2 .= "</td>";
                                    $content2 .= "<td align='right' class='text-muted text-blue'>";
                                    $content2 .= $histLink['open'] . $val . $histLink['close'];
                                    $content2 .= "</td>";

                                    $sumQty += $totalH[$ddID]['qty'];
                                    $sumVal += ($totalH[$ddID]['value'] / 1000);
                                    $content2 .= "</tr>";
                                }
                            }
                        }

                    }

                }
                $content2 .= "<tr bgcolor='#e5e5e5'>";

                $content2 .= "<td align='right' class='text-muted'>";
                $content2 .= "";
                $content2 .= "</td>";
                $content2 .= "<td align='right' class='text-muted'>";
                $content2 .= "";
                $content2 .= "</td>";

                $content2 .= "<td>";
                $content2 .= "TOTAL";
                $content2 .= "</td>";
                foreach ($times as $pID => $pName) {
                    $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                    $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                    $content2 .= "<td align='right' class='text-muted'>";
                    $content2 .= $qty;
                    $content2 .= "</td>";
                    $content2 .= "<td align='right' class='text-muted text-blue'>";
                    $content2 .= $val;
                    $content2 .= "</td>";

                }

                $content2 .= "<td>$sumQty</td>";
                $content2 .= "<td>" . formatField("value", $sumVal) . "</td>";
                for ($i = 1; $i <= 2; $i++) {
                    $content2 .= "<td class='text-center'>-</td>";
                }


                $content2 .= "</tr>";
                $content2 .= "</table>";
            }

        }
        else {
            $content2 .= ("<div class=\"box-body\">");
            $content2 .= ("no report found.<br>");
            $content2 .= ("to go back to index, you can click BACK button<br>");
            $content2 .= ("</div class=\"box-body\">");
        }
        //endregion

        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle (by " . $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]) . ")", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "content2" => $content2,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;
    case "recap_ext":

        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        //region main conten
        $content = "";
        // if (sizeof($stepNames) > 0) {
        //     $content .= "<ul class='nav nav-tabs'>";
        //     foreach ($stepNames as $stID => $stLabel) {
        //         $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
        //         $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
        //         $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";
        //
        //         $content .= "<li class='nav-item'>";
        //         if (strcmp($stID, $selectedStep) == 0) {
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
        //             $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
        //             $content .= "<span class='fa fa-adjust'></span> ";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //         }
        //         else {
        //             $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //
        //
        //         }
        //
        //
        //         $content .= "</li>";
        //
        //     }
        //
        //     $content .= "</ul>";
        // }

        // arrprint($recaps);
        // arrPrint($times);
        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<table id='main' align='center' class='table table-condensed table-bordered' style='table-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));
            $col_master = sizeof($times) * sizeof($headerList) + 7;
            $content .= "<tr>";
            $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            $content .= "</td>";
            $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {

                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }

            $content .= "<td bgcolor='#009900' align='center' colspan='$cols' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";
            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }
            foreach ($headerList as $jn => $alias) {
                $content .= "<td align='center' class='text-muted'>";
                $content .= "$alias";
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";
                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }

                        $addLinkParam['customers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage&addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";
                        $content .= $histLink['open'] . formatField("harga", $val) . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }

                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalH[$j]);
                    $content .= "</td>";
                }
                //
                // $qty = isset($totalH[$oID]['qty']) ? number_format($totalH[$oID]['qty']) : "";
                // $val = isset($totalH[$oID]['value']) ? number_format($totalH[$oID]['value'] / 1000) : "";
                //
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $histLink['open'] . $qty . $histLink['close'];
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $histLink['open'] . $val . $histLink['close'];
                // $content .= "</td>";
                //
                // $sumQty += $totalH[$oID]['qty'];
                // $sumVal += ($totalH[$oID]['value'] / 1000);

                $content .= "</tr>";
            }
            $content .= "<tr bgcolor='#e5e5e5'>";

            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";

            //region summary footer


            //endregion


            $content .= "</table>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle (by " . $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]) . ")", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "";
        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "content2" => $content2,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;
    case "recap__":

        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $content = "";
        //        arrprint($stepNames);


        if (sizeof($stepNames) > 0) {
            $content .= "<ul class='nav nav-tabs'>";

            foreach ($stepNames as $stID => $stLabel) {


                $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
                $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
                $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";

                $content .= "<li class='nav-item'>";
                if (strcmp($stID, $selectedStep) == 0) {
                    //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
                    $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
                    //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
                    $content .= "<span class='fa fa-adjust'></span> ";
                    $content .= $stLabel;
                    $content .= "</a>";
                }
                else {
                    $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
                    $content .= $stLabel;
                    $content .= "</a>";


                }


                $content .= "</li>";

            }

            $content .= "</ul>";
        }

        if (sizeof($names) > 0) {
            $content .= "<table align='center' class='table table-condensed table-bordered'>";
            $content .= "<tr>";
            foreach ($names as $nID => $nSpec) {
                if (array_key_exists($nID, $availFilters)) {

                    $bgColor = $nID == $selectedFilter ? "#ccccdf" : "#e5e5e0";
                    $content .= "<td align='center' bgcolor='$bgColor'>";
                    $nameLabel = $identifierLabels[$nID] . createObjectSuffix($identifierLabels[$nID]);
                    if ($nID != $selectedFilter) {
                        $content .= "<a href='$thisPage?stID=$selectedStep&sID=$nID'>";
                        $content .= $nameLabel;
                        $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
                        $content .= "</a>";
                    }
                    else {
                        $content .= $nameLabel;
                        $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
                    }


                    $content .= "</td>";
                }
            }
            $content .= "</tr>";
            $content .= "</table>";


            if (isset($names[$selectedFilter]) && sizeof($names[$selectedFilter]) > 0) {

                $content .= "<table align='center' class='table table-condensed table-bordered' style='table-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;;'>";
                $content .= "<tr bgcolor='#f0f0f0'>";
                $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";

                $content .= "</td>";

                $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
                $content .= "<span class='pull-right'>$timeLabel <span class='fa fa-angle-double-right'></span></span> <br>";
                $content .= "<span class='fa fa-angle-double-down'></span> ";
                $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
                $content .= "</td>";
                foreach ($times as $pID => $pName) {
                    $content .= "<td align='center' colspan='2' class='text-muted'>";
                    if (isset($subPage)) {
                        $content .= "<a href='" . $subPage . "?time=$pID'>";
                        $content .= $pName;
                        $content .= "</a>";
                    }
                    else {
                        $content .= $pName;
                    }

                    $content .= "</td>";
                }

                $content .= "<td bgcolor='#009900' align='center' colspan='2' class='text-muted text-white'>";
                $content .= "TOTAL";
                $content .= "</td>";
                $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
                $content .= "AVG";
                $content .= "</td>";

                $content .= "</tr>";
                $content .= "<tr bgcolor='#e5e5e5'>";

                foreach ($times as $pID => $pName) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "qty";
                    $content .= "</td>";

                    $content .= "<td align='center' class='text-blue text-muted'>";
                    $content .= "IDR<br><small>(thou)</small>";
                    $content .= "</td>";
                }
                $content .= "<td bgcolor='#008800' align='center' class='text-muted text-white'>";
                $content .= "qty";
                $content .= "</td>";

                $content .= "<td align='center' class='text-blue text-muted'>";
                $content .= "IDR<br><small>(thou)</small>";
                $content .= "</td>";

                $content .= "<td bgcolor='#005588' align='center' class='text-muted text-white'>";
                $content .= "qty";
                $content .= "</td>";

                $content .= "<td align='center' class='text-blue text-muted'>";
                $content .= "IDR<br><small>(thou)</small>";
                $content .= "</td>";

                $content .= "</tr>";

                //                echo("ahghsga sas ga sas a");

                $no = 0;
                $sumQty = 0;
                $sumVal = 0;
                $totalV = array();
                foreach ($names[$selectedFilter] as $oID => $oName) {
                    $no++;
                    $content .= "<tr>";

                    $content .= "<td align='right' class='text-muted'>";
                    $content .= $no;
                    $content .= "</td>";


                    $content .= "<td>";
                    $content .= $oName;
                    $content .= "</td>";

                    $totalH[$oID] = array(
                        "qty" => 0,
                        "value" => 0,
                    );

                    foreach ($times as $pID => $pName) {
                        $qty = isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty']) ? number_format($recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty']) : "";
                        $val = isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['value']) ? number_format($recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'] / 1000) : "";
                        if (isset($historyPage)) {
                            $addLinkParam = array(
                                "dtime like $pID%",
                                "$selectedFilter='$oID'",
                            );
                            $histLink['open'] = "<a href='$historyPage&addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }
                        $content .= "<td align='right' class='text-muted'>";
                        $content .= $histLink['open'] . $qty . $histLink['close'];
                        $content .= "</td>";
                        $content .= "<td align='right' class='text-muted text-blue'>";
                        $content .= $histLink['open'] . $val . $histLink['close'];
                        $content .= "</td>";
                        //
                        //                        cekkuning("hitung-menghitung");
                        if (!isset($totalV[$pID])) {
                            $totalV[$pID] = array(
                                "qty" => 0,
                                "value" => 0,
                            );
                            //                            cekkuning("setting totalV");
                        }
                        if (isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty']) && isset($recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'])) {
                            //                            cekkuning("ada isi, mengakumulasi totalV");
                            $totalV[$pID]['qty'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty'];
                            $totalV[$pID]['value'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'];
                            $totalH[$oID]['qty'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['qty'];
                            $totalH[$oID]['value'] += $recaps[$selectedStep][$selectedFilter][$pID][$oID]['value'];

                        }

                    }

                    $qty = isset($totalH[$oID]['qty']) ? number_format($totalH[$oID]['qty']) : "";
                    $val = isset($totalH[$oID]['value']) ? number_format($totalH[$oID]['value'] / 1000) : "";

                    $content .= "<td align='right' class='text-muted'>";
                    $content .= $histLink['open'] . $qty . $histLink['close'];
                    $content .= "</td>";
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= $histLink['open'] . $val . $histLink['close'];
                    $content .= "</td>";

                    $sumQty += $totalH[$oID]['qty'];
                    $sumVal += ($totalH[$oID]['value'] / 1000);

                    $content .= "</tr>";
                }


                $content .= "<tr bgcolor='#e5e5e5'>";

                $content .= "<td align='right' class='text-muted'>";
                $content .= "";
                $content .= "</td>";

                $content .= "<td>";
                $content .= "TOTAL";
                $content .= "</td>";

                foreach ($times as $pID => $pName) {
                    $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                    $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                    $content .= "<td align='right' class='text-muted'>";
                    $content .= $qty;
                    $content .= "</td>";
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= $val;
                    $content .= "</td>";

                }

                $content .= "<td>$sumQty</td>";
                $content .= "<td>" . formatField("value", $sumVal) . "</td>";
                for ($i = 1; $i <= 2; $i++) {
                    $content .= "<td class='text-center'>-</td>";
                }


                $content .= "</tr>";


                $content .= "</table>";


            }
            else {
                $content .= ("<div class=\"box-body\">");
                $content .= ("no report found.<br>");
                $content .= ("to go back to index, you can click BACK button<br>");
                $content .= ("</div class=\"box-body\">");
            }
        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("$title", "$subTitle (by " . $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]) . ")", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
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
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;

    case "historical":
        $content = "";
        // arrprint($stepNames);
        //        arrprint($tblBodies);

        $content .= "<div class='panel with-nav-tabs panel-default'>";
        /*============
         * TAB
         *===============*/
        if (sizeof($tblBodies) > 0) {
            $content .= "<div class='panel-heading'>";
            $content .= "<ul class='nav nav-tabs'>";
            // arrPrint($stepNames);
            $no = 0;
            foreach ($stepNames as $stLink => $stLabel) {
                $no++;
                $tActive = $no == 1 ? "active" : "";

                $content .= "<li class='nav-item text-uppercase $tActive'>";
                $content .= "<a class='nav-link' href='#tab$no' data-toggle='tab'>";
                $content .= $stLabel;
                $content .= "</a>";
                $content .= "</li>";

            }

            $content .= "</ul>";
            $content .= "</div>";

            // cekHijau();
        }


        if (sizeof($tblBodies) > 0) {
            /* -----------------------------
             * SUMMARY
             * -----------------------------*/
            // $content .= "<table align='center' class='table table-condensed table-bordered'>";
            // $content .= "<tr>";
            // foreach ($names as $nID => $nSpec) {
            //     if (array_key_exists($nID, $availFilters)) {
            //
            //         $bgColor = $nID == $selectedFilter ? "#ccccdf" : "#e5e5e0";
            //         $content .= "<td align='center' bgcolor='$bgColor'>";
            //         $nameLabel = $identifierLabels[$nID] . createObjectSuffix($identifierLabels[$nID]);
            //         if ($nID != $selectedFilter) {
            //             $content .= "<a href='$thisPage?stID=$selectedStep&sID=$nID'>";
            //             $content .= $nameLabel;
            //             $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
            //             $content .= "</a>";
            //         } else {
            //             $content .= $nameLabel;
            //             $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
            //         }
            //
            //
            //         $content .= "</td>";
            //     }
            // }
            // $content .= "</tr>";
            // $content .= "</table>";
            /*=======================================*/

            $content .= "<div class='panel-body'>";
            $content .= "<div class='tab-content'>";
            foreach ($tblBodies as $ix => $tblBody) {
                $datatables_r = "datatables$ix";
                $tblHeading = isset($tblHeadings[$ix]) ? $tblHeadings[$ix] : "";

                $content .= "<div class='tab-pane fade in active' id='tab$ix'>";
                $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
                if (isset($tblHeadings[$ix])) {
                    //region table heading
                    $content .= "<thead>";
                    $content .= "<tr>";
                    // foreach ($tblHeadings as $tblHeading) {
                    foreach ($tblHeading as $label => $attr) {
                        $content .= "<th align='center' $attr>";
                        $content .= $label;
                        $content .= "</th>";
                    }
                    // }
                    $content .= "</tr>";
                    $content .= "</thead>";
                    //endregion
                }


                //region table body
                $no = 0;
                $sumQty = 0;
                $sumVal = 0;
                $totalV = array();
                $content .= "<tbody>";
                foreach ($tblBody as $oID => $specs) {
                    $content .= "<tr>";
                    foreach ($specs as $spec) {

                        $content .= "<td " . $spec['attr'] . ">";
                        $content .= $spec['value'];
                        $content .= "</td>";
                    }
                    $content .= "</tr>";
                }
                $content .= "</tbody>";
                //endregion
                $content .= "</table>";
                $content .= "</div>";

                // $content .= "<div class='tab-pane fade in active' id='tab1'>";
                // $content .= "cek boss satu";
                // $content .= "</div>";

                // $content .= "<div class='tab-pane fade' id='tab2'>";
                // $content .= "cek boss";
                // $content .= "</div>";
                if (sizeof($tblHeading) > 0) {
                    $content .= "<script>
                            $(document).ready( function(){
                                var table = $('table.$datatables_r').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                  
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
                }

            } //  bodies


            $content .= "</div>"; // tab cntent
            $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }
        // $content .= "</div>";
        $content .= "</div>";

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();

        break;

    case "reporting":
        $ly = new Layout();
        $content = "";
        // arrprint($stepNames);
        //        arrprint($tblBodies);
        // $cUrl = current_url();
        $segments = $this->uri->segment_array();
        $strAction = $cUrl = base_url() . implode("/", $segments);
        // arrPrint($cUrl);
        $urlAdd = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $ly->setOnClickTarget("$strAction");
        $bulanNow = dtimeNow("Y-m");

        $bulanPilihan = isset($_GET['date']) ? $_GET['date'] : $bulanNow;
        $content .= "<div class='row margin-bottom-10'>";
        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectBulan($bulanPilihan);
        $content .= "</div>";
        $content .= "</div>";

        $content .= "<div class='panel with-nav-tabs panel-default'>";
        /*============
         * TAB
         *===============*/
        if (sizeof($tblBodies) > 0) {
            $content .= "<div class='panel-heading'>";
            $content .= "<ul class='nav nav-tabs'>";
            // arrPrint($stepNames);
            $no = 0;
            // foreach ($tblBodies as $ix => $tblBody) {
            //     $stLabel = $stepNames[$no];
            foreach ($stepNames as $stLink => $stLabel) {
                $expLink = explode("/", $stLink);

                $no = end($expLink);
                $tActive = $no == 4 ? "active" : "";

                $content .= "<li class='nav-item text-uppercase $tActive'>";
                $content .= "<a class='nav-link' href='#tab$no' data-toggle='tab'>";
                $content .= $stLabel;
                $content .= "</a>";
                $content .= "</li>";

            }

            $content .= "</ul>";
            $content .= "</div>";

            // cekHijau();
        }

        // arrPrint($tblBodies);
        cekMerah(sizeof($tblBodies));
        if (sizeof($tblBodies) > 0) {
            /* -----------------------------
             * SUMMARY
             * -----------------------------*/
            // $content .= "<table align='center' class='table table-condensed table-bordered'>";
            // $content .= "<tr>";
            // foreach ($names as $nID => $nSpec) {
            //     if (array_key_exists($nID, $availFilters)) {
            //
            //         $bgColor = $nID == $selectedFilter ? "#ccccdf" : "#e5e5e0";
            //         $content .= "<td align='center' bgcolor='$bgColor'>";
            //         $nameLabel = $identifierLabels[$nID] . createObjectSuffix($identifierLabels[$nID]);
            //         if ($nID != $selectedFilter) {
            //             $content .= "<a href='$thisPage?stID=$selectedStep&sID=$nID'>";
            //             $content .= $nameLabel;
            //             $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
            //             $content .= "</a>";
            //         } else {
            //             $content .= $nameLabel;
            //             $content .= " <span class='badge text-white bg-blue'>" . sizeof($nSpec) . "</span>";
            //         }
            //
            //
            //         $content .= "</td>";
            //     }
            // }
            // $content .= "</tr>";
            // $content .= "</table>";
            /*=======================================*/

            $content .= "<div class='panel-body'>";
            $content .= "<div class='tab-content'>";
            // $ix =0;
            // arrPrint($stepNames);
            // arrPrint($tblBodies);
            // foreach ($stepNames as $stLink => $stLabel) {
            //     $ix ++;
            //     $tblBody = $tblBodies[$ix];
            foreach ($tblBodies as $ix => $tblBody) {
                $datatables_r = "datatables$ix";
                $tblHeading = isset($tblHeadings[$ix]) ? $tblHeadings[$ix] : "";

                $content .= "<div class='tab-pane fade in active' id='tab$ix'>";
                $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
                if (isset($tblHeadings[$ix])) {
                    //region table heading
                    $content .= "<thead>";
                    $content .= "<tr>";
                    // foreach ($tblHeadings as $tblHeading) {
                    foreach ($tblHeading as $label => $attr) {
                        $content .= "<th align='center' $attr>";
                        $content .= $label;
                        $content .= "</th>";
                    }
                    // }
                    $content .= "</tr>";
                    $content .= "</thead>";
                    //endregion
                }

                //region table body
                $no = 0;
                $sumQty = 0;
                $sumVal = 0;
                $totalV = array();
                $content .= "<tbody>";
                foreach ($tblBody as $oID => $specs) {
                    $content .= "<tr>";
                    foreach ($specs as $spec) {
                        $content .= "<td " . $spec['attr'] . ">";
                        $content .= $spec['value'];
                        $content .= "</td>";
                    }
                    $content .= "</tr>";
                }
                $content .= "</tbody>";
                //endregion

                // region footer
                $content .= "<tfoot>";
                $content .= "<tr>";

                //                foreach ($tblFooters as $footer => $fAttr) {
                //                    $content .= "<th $fAttr>";
                //                    $content .= $footer;
                //                    $content .= "</th>";
                //                }

                foreach ($tblHeading as $label => $attr) {
                    $content .= "<th align='center' $attr>";
                    $content .= "--";
                    $content .= "</th>";
                }

                $content .= "</tr>";
                $content .= "</tfoot>";
                // endregion footer
                $content .= "</table>";
                $content .= "</div>";

                if (sizeof($tblHeading) > 0) {
                    $content .= "<script>
                            $(document).ready( function(){
                                var table = $('table.$datatables_r').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                                {
                                                    extend: 'print',
                                                    footer: true,
                                                    text: 'CETAK',
                                                },
                                                {
                                                    extend: 'copyHtml5',
                                                    footer: true,
                                                    text: 'COPY',
                                                },
                                                {
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    text: 'CSV',
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    text: 'EXCEL',
                                                },
                                                {
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    text: 'PDF',
                                                },
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

                                                var arrayFooterTmp = $('tfoot>tr>th');
                                                var dpageTotal = [];

                                                var arrBlackList = [5,6];
                                                var arrColSpan = [0,1,2,3,4];
                                                var arrSaldo = [7];

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrBlackList.includes(i)){
                                                        var id_n_index = parseFloat(i);
                                                        dpageTotal[id_n_index] = 0;
                                                        jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                             dpageTotal[id_n_index] += intVal( obj );
                                                        });
                                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrSaldo.includes(i)){
                                                        var saldo = 0;
                                                        var sales = 0;
                                                        var returns = 0;
                                                        sales = $( 'div', api.column(5).footer() ).html();
                                                            sales = sales.replace(/[$.]/g,'');
                                                        returns = $( 'div', api.column(6).footer() ).html()
                                                            returns = returns.replace(/[$.]/g,'');
                                                        saldo = intVal(sales) - intVal(returns)
                                                        if( !isNaN(saldo) && saldo > 0 ){
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });
//
                                                var keys = Object.keys(arrColSpan);
                                                var last = keys[keys.length-1];
                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrColSpan.includes(i)){
                                                        if(i==last){
                                                            $( api.column(i).footer()).attr('colspan', 5);
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-muted text-bold'>  S U M M A R Y  </div>\"
                                                            );
                                                        }
                                                        else{
                                                            $( api.column(i).footer() ).remove();
                                                        }
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
                }

            } //  bodies


            $content .= "</div>"; // tab cntent
            $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }
        // $content .= "</div>";
        $content .= "</div>";

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            // "nav_top" => "",
        ));
        $p->render();

        break;

    case "viewSales":
        $ly = new Layout();
        $reportingSumCabang = $confReportCabang;
        $reportingSumSubject = $confReportSubject;
        $reportingSumObject = $confReportObject;
        $sumSubjectTitle = $reportingSumSubject['title'];
        $sumCabangTitle = $reportingSumCabang['title'];
        $sumObjectTitle = $reportingSumObject['title'];

        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $cbFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumSubject['mdlFields'] as $field => $fChilds) {
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $sFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }

        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        $sHeader['no'] = "class='bg-success text-center'";
        foreach ($sFieldToshows as $kolom => $kolomAlias) {
            $sHeader[$kolomAlias] = "class='bg-success text-center'";

        }
        $pHeader['no'] = "class='bg-success text-center'";
        foreach ($pFieldToshows as $kolom => $kolomAlias) {
            $pHeader[$kolomAlias] = "class='bg-success text-center'";

        }


        $content = "";
        // arrprint($stepNames);
        // arrprint($tblBodies);
        // arrPrint($this->session->login);
        // [jenis] => spv_penj, admin
        // region navigasi
        $segments = $this->uri->segment_array();
        $strAction = $cUrl = base_url() . implode("/", $segments);
        // arrPrint($cUrl);
        $urlAdd = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $ly->setOnClickTarget("$strAction");
        $bulanNow = dtimeNow("Y-m");

        $bulanPilihan = isset($_GET['date']) ? $_GET['date'] : $bulanNow;
        $tahunPilihan = isset($_GET['year']) ? $_GET['year'] : "";
        $content .= "<div class='row margin-bottom-10'>";
        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectTahun($tahunPilihan);
        $content .= "</div>";

        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectBulan($bulanPilihan);
        $content .= "</div>";
        $content .= "</div>";
        // endregion navigasi

        if (sizeof($tblBodies) > 0) {
            /* -----------------------------
             * SUMMARY
             * -----------------------------*/
            //region summary cabang branch
            $content .= "<div class='box box-info'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumCabangTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin'>";
            //region header cabang
            foreach ($cbHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            foreach ($sumCabang as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($cbFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $cbFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($cbFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        $sumValue[$kolom] += isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($cbFieldFormat[$kolom])) {
                        $fValue = $cbFieldFormat[$kolom]($kolom, isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0);
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($cbFieldLink[$kolom])) {
                        $lValue = "<a href='" . $cbFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }

            //region footer cabang
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($cbFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-info'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-info'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            //endregion

            //region summary sales
            $content .= "<div class='box box-success'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumSubjectTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='sallesman'>";
            //region header cabang
            $content .= "<thead>";
            foreach ($sHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            $content .= "</thead>";
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            $content .= "<tbody>";
            foreach ($sumSubject as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($sFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $sFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($sFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        if (isset($nSpec[$kolom])) {

                            $sumValue[$kolom] += $nSpec[$kolom];
                        }
                        else {
                            $sumValue[$kolom] += 0;
                        }
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($sFieldFormat[$kolom])) {
                        if (isset($nSpec[$kolom])) {

                            $fValue = $sFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
                        }
                        else {
                            $fValue = $sFieldFormat[$kolom]($kolom, 0);;
                        }
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($sFieldLink[$kolom])) {
                        $lValue = "<a href='" . $sFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }
            $content .= "</tbody>";

            //region footer cabang
            $content .= "<tfoot>";
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($sFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-success'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "<script>
                            $(document).ready( function(){
                                $('#sallesman').DataTable({
                                dom: 'lBfrtip',
                                    paging: false,
                                    searching: false,
                                    order: [[2,'desc']],
                                     buttons: [
                                                {
                                                    extend: 'print',
                                                    footer: true,
                                                    text: 'CETAK',
                                                },
                                                {
                                                    extend: 'copyHtml5',
                                                    footer: true,
                                                    text: 'COPY',
                                                },
                                                {
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    text: 'CSV',
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    text: 'EXCEL',
                                                },
                                                {
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    text: 'PDF',
                                                },
                                            ],
                                });                                    
                            });
                         </script>";
            //endregion

            // //region summary produk
            // $content .= "<div class='box box-success'>";
            // $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumObjectTitle <small>$subTitle</small></h3></div>";
            // $content .= "<div class='box-body'>";
            // $content .= "<div class='table-responsive'>";
            // $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='produk'>";
            // //region header
            // $content .= "<thead>";
            // foreach ($pHeader as $kAlias => $hAtt) {
            //     $content .= "<th $hAtt>$kAlias</th>";
            // }
            // $content .= "</thead>";
            // //endregion
            // // arrPrint($cbFieldSumrows);
            // $cbNo = 0;
            // $sumValue = array();
            // $content .= "<tbody>";
            // foreach ($sumObject as $nID => $nSpec) {
            //     $cbNo++;
            //     $content .= "<tr>";
            //
            //     $content .= "<td class='text-right'>$cbNo</td>";
            //     foreach ($pFieldToshows as $kolom => $kolomAlias) {
            //         $cAttr = $pFieldAttr[$kolom];
            //
            //         //$cbFieldSumrows
            //         if (isset($pFieldSumrows[$kolom])) {
            //             if (!isset($sumValue[$kolom])) {
            //                 $sumValue[$kolom] = 0;
            //             }
            //             if (isset($nSpec[$kolom])) {
            //
            //                 $sumValue[$kolom] += $nSpec[$kolom];
            //             }
            //             else {
            //                 $sumValue[$kolom] += 0;
            //             }
            //         }
            //         else {
            //             $sumValue[$kolom] = "-";
            //             $sumValue['no'] = "-";
            //         }
            //
            //         //region formater value
            //         if (isset($pFieldFormat[$kolom])) {
            //             if (isset($nSpec[$kolom])) {
            //
            //                 $fValue = $pFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
            //             }
            //             else {
            //                 $fValue = $pFieldFormat[$kolom]($kolom, 0);;
            //             }
            //         }
            //         else {
            //             $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
            //         }
            //         //endregion
            //
            //         //region linker value
            //         if (isset($pFieldLink[$kolom])) {
            //             $lValue = "<a href='" . $pFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
            //         }
            //         else {
            //             $lValue = $fValue;
            //         }
            //         //endregion
            //
            //
            //         $content .= "<td $cAttr>";
            //         $content .= $lValue;
            //         $content .= "</td>";
            //     }
            //
            //     $content .= "</tr>";
            // }
            // $content .= "</tbody>";
            //
            // //region footer
            // $content .= "<tfoot>";
            // $content .= "<tr>";
            // foreach ($sumValue as $kolom => $kAlias) {
            //     $footerValue = $sumValue[$kolom];
            //     if (isset($pFieldSumrows[$kolom])) {
            //         $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
            //     }
            //     else {
            //         $content .= "<th class='text-right bg-success'>$footerValue</th>";
            //     }
            // }
            // $content .= "</tr>";
            // $content .= "</tfoot>";
            // //endregion
            // // arrPrint($sumValue);
            // $content .= "</table>";
            // $content .= "</div>";
            // $content .= "</div>";
            // $content .= "</div>";
            // $content .= "<script>
            //                 $(document).ready( function(){
            //                     $('#produk').DataTable({
            //                         paging: false,
            //                         searching: true,
            //                         order: [[8,'desc']]
            //                     });
            //                 });
            //              </script>";
            // //endregion

            /*=======================================*/

            //             $content .= "<div class='panel-body'>";
            //             $content .= "<div class='tab-content'>";
            //             // $ix =0;
            //             // arrPrint($stepNames);
            //             // arrPrint($tblBodies);
            //             // foreach ($stepNames as $stLink => $stLabel) {
            //             //     $ix ++;
            //             //     $tblBody = $tblBodies[$ix];
            //             // foreach ($tblBodies as $ix => $tblBody) {
            //             // arrPrint($tblBody);
            //             $datatables_r = "datatables";
            //             $tblHeading = isset($tblHeadings) ? $tblHeadings : "";
            //
            //             $content .= "<div class='tab-pane fade in active' id='tab'>";
            //             $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
            //             if (isset($tblHeadings)) {
            //                 //region table heading
            //                 $content .= "<thead>";
            //                 $content .= "<tr>";
            //                 // foreach ($tblHeadings as $tblHeading) {
            //                 foreach ($tblHeading as $label => $attr) {
            //                     $content .= "<th align='center' $attr>";
            //                     $content .= $label;
            //                     $content .= "</th>";
            //                 }
            //                 // }
            //                 $content .= "</tr>";
            //                 $content .= "</thead>";
            //                 //endregion
            //             }
            //             // $specs = $tblBodies;
            //             //region table body
            //             $no = 0;
            //             $sumQty = 0;
            //             $sumVal = 0;
            //             $totalV = array();
            //             $content .= "<tbody>";
            //             foreach ($tblBodies as $oID => $specs) {
            //                 $content .= "<tr>";
            //                 foreach ($specs as $spec) {
            //                     $content .= "<td " . $spec['attr'] . ">";
            //                     $content .= $spec['value'];
            //                     $content .= "</td>";
            //                 }
            //                 $content .= "</tr>";
            //             }
            //             $content .= "</tbody>";
            //             //endregion
            //
            //             // region footer
            //             $content .= "<tfoot>";
            //             $content .= "<tr>";
            //
            //             //                foreach ($tblFooters as $footer => $fAttr) {
            //             //                    $content .= "<th $fAttr>";
            //             //                    $content .= $footer;
            //             //                    $content .= "</th>";
            //             //                }
            //             // arrPrintWebs($tblHeading);
            //             foreach ($tblHeading as $label => $attr) {
            //                 $content .= "<th align='center' $attr>";
            //                 $content .= "--";
            //                 $content .= "</th>";
            //             }
            //
            //             $content .= "</tr>";
            //             $content .= "</tfoot>";
            //             // endregion footer
            //             $content .= "</table>";
            //             $content .= "</div>";
            //
            //             if (sizeof($tblHeading) > 0) {
            //                 $content .= "<script>
            //                             $(document).ready( function(){
            //                                 var table = $('table.$datatables_r').DataTable({
            //                                     dom: 'lBfrtip',
            //                                     fixedHeader: true,
            //                                     lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
            //                                     pageLength: -1,
            //                                     buttons: [
            //                                                 {
            //                                                     extend: 'print',
            //                                                     footer: true,
            //                                                     text: 'CETAK',
            //                                                 },
            //                                                 {
            //                                                     extend: 'copyHtml5',
            //                                                     footer: true,
            //                                                     text: 'COPY',
            //                                                 },
            //                                                 {
            //                                                     extend: 'csvHtml5',
            //                                                     footer: true,
            //                                                     text: 'CSV',
            //                                                 },
            //                                                 {
            //                                                     extend: 'excelHtml5',
            //                                                     footer: true,
            //                                                     text: 'EXCEL',
            //                                                 },
            //                                                 {
            //                                                     extend: 'pdfHtml5',
            //                                                     footer: true,
            //                                                     text: 'PDF',
            //                                                 },
            //                                             ],
            //
            //         //                            buttons: [
            //         //                                        'copy', 'csv', 'excel', 'pdf', 'print'
            //         //                                    ],
            //         //                            buttons: [
            //         //                                        {
            //         //                                            extend: 'colvisGroup',
            //         //                                            text: 'Office info',
            //         //                                            show: [ 1, 2 ],
            //         //                                            hide: [ 3, 4, 5 ]
            //         //                                        },
            //         //                                        {
            //         //                                            extend: 'colvisGroup',
            //         //                                            text: 'HR info',
            //         //                                            show: [ 3, 4, 5 ],
            //         //                                            hide: [ 1, 2 ]
            //         //                                        },
            //         //                                        {
            //         //                                            extend: 'colvisGroup',
            //         //                                            text: 'Show all',
            //         //                                            show: ':hidden'
            //         //                                        }
            //         //                                    ]
            //
            //                                     footerCallback: function ( row, data, start, end, display ) {
            //                                                 var api = this.api(), data;
            //
            //                                                 // Remove the formatting to get integer data for summation
            //                                                 var intVal = function ( i ) {
            //                                                     return typeof i === 'string' ?
            //                                                         i.replace(/[$,()]/g, '')*1 :
            //                                                         typeof i === 'number' ?
            //                                                             i : 0;
            //                                                 };
            //
            //                                                 var arrayFooterTmp = $('tfoot>tr>th');
            //                                                 var dpageTotal = [];
            //
            //                                                 var arrBlackList = [5,6,7,8,9,10];
            // //                                                var arrColSpan = [0,1,2,3,4];
            //                                                 var arrSaldo = [10];
            //                                                 var arrSaldoQty = [9];
            //
            //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            //                                                     if(arrBlackList.includes(i)){
            //                                                         var id_n_index = parseFloat(i);
            //                                                         dpageTotal[id_n_index] = 0;
            //                                                         jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
            //
            //                                                             obj = intVal(obj)>0 ? intVal(obj) : intVal($(obj).html())>0 ? intVal($(obj).html()) : 0;
            //
            //                                                               dpageTotal[id_n_index] += intVal( obj );
            //
            // //                                                             dpageTotal[id_n_index] += intVal($(obj).html())==0 ? intVal(obj) : intVal($(obj).html()) ;
            // //                                                             console.log( $(obj).html()!=='undefine' ? $(obj).html() : obj );
            // //                                                             console.log( typeof obj );
            // //                                                             console.log( (intVal(obj)==0 ? $(obj).html() : 0) );
            //                                                         });
            //                                                         if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
            //                                                             $( api.column(id_n_index).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
            //                                                             );
            //                                                         }else{
            //                                                             $( api.column(id_n_index).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>0</div>\"
            //                                                             );
            //                                                         }
            //                                                     }
            //                                                 });
            //
            //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            //                                                     if(arrSaldo.includes(i)){
            //                                                         var saldo = 0;
            //                                                         var sales = 0;
            //                                                         var returns = 0;
            //                                                         sales = $( 'div', api.column(6).footer() ).html();
            //                                                             sales = sales.replace(/[$.]/g,'');
            //                                                         returns = $( 'div', api.column(8).footer() ).html()
            //                                                             returns = returns.replace(/[$.]/g,'');
            //                                                         saldo = intVal(sales) - intVal(returns)
            //                                                         if( !isNaN(saldo) && saldo > 0 || saldo < 0){
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
            //                                                             );
            //                                                         }else{
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>0</div>\"
            //                                                             );
            //                                                         }
            //                                                     }
            //                                                 });
            //
            //
            //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            //                                                     if(arrSaldoQty.includes(i)){
            //                                                         var saldo = 0;
            //                                                         var sales = 0;
            //                                                         var returns = 0;
            //                                                         sales = $( 'div', api.column(5).footer() ).html();
            //                                                             sales = sales.replace(/[$.]/g,'');
            //                                                         returns = $( 'div', api.column(7).footer() ).html()
            //                                                             returns = returns.replace(/[$.]/g,'');
            //                                                         saldo = intVal(sales) - intVal(returns)
            //                                                         if( !isNaN(saldo) && saldo > 0 || saldo < 0){
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
            //                                                             );
            //                                                         }else{
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>0</div>\"
            //                                                             );
            //                                                         }
            //                                                     }
            //                                                 });
            //
            // //                                                 var keys = Object.keys(arrColSpan);
            // //                                                 var last = keys[keys.length-1];
            // //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            // //                                                     if(arrColSpan.includes(i)){
            // //                                                         if(i==last){
            // //                                                             $( api.column(i).footer()).attr('colspan', 5);
            // //                                                             $( api.column(i).footer() ).html(
            // //                                                                 \"<div class='text-right text-muted text-bold'>  S U M M A R Y  </div>\"
            // //                                                             );
            // //                                                         }
            // //                                                         else{
            // //                                                             $( api.column(i).footer() ).remove();
            // //                                                         }
            // //                                                     }
            // //                                                 });
            //
            //         // Total over all pages
            //         //                                        var total2=0;
            //         //                                        jQuery.each( $(api.column(2).data()), function(i, obj){
            //         //                                            total2 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var total3=0;
            //         //                                        jQuery.each( $(api.column(3).data()), function(i, obj){
            //         //                                            total3 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var total4=0;
            //         //                                        jQuery.each( $(api.column(4).data()), function(i, obj){
            //         //                                            total4 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var total5=0;
            //         //                                        jQuery.each( $(api.column(5).data()), function(i, obj){
            //         //                                            total5 += intVal( $('span', obj).html() );
            //         //                                        });
            //
            //         // Total over this page
            //         //                                        pageTotal2 = api
            //         //                                            .column( 2, { page: 'current'} )
            //         //                                            .data()
            //         //                                            .reduce( function (a, b) {
            //         //                                                return intVal(a) + intVal(b);
            //         //                                            }, 0 );
            //
            //         //                                        var pageTotal2=0;
            //         //                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal2 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var pageTotal3=0;
            //         //                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal3 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var pageTotal4=0;
            //         //                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal4 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var pageTotal5=0;
            //         //                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal5 += intVal( $('span', obj).html() );
            //         //                                        });
            //
            //                                                 // Update footer
            //         //                                        $( api.column( 2 ).footer() ).html(
            //         //                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
            //         //                                        );
            //
            //         //                                        $( api.column( 3 ).footer() ).html(
            //         //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
            //         //                                        );
            //
            //         //                                        $( api.column( 4 ).footer() ).html(
            //         //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
            //         //                                        );
            //
            //         //                                        $( api.column( 5 ).footer() ).html(
            //         //                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
            //         //                                        );
            //
            //                                             }
            //                                         });
            //
            //                                     });
            //
            //                                     $('.table-responsive').floatingScroll();
            //                             </script>";
            //             }
            //
            //             // } //  bodies
            //
            //
            //             $content .= "</div>"; // tab cntent
            //             $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;

    case "viewSalesProduk":
        $ly = new Layout();
        $reportingSumCabang = $confReportCabang;
        $reportingSumSubject = $confReportSubject;
        $reportingSumObject = $confReportObject;
        $sumSubjectTitle = $reportingSumSubject['title'];
        $sumCabangTitle = $reportingSumCabang['title'];
        $sumObjectTitle = $reportingSumObject['title'];

        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $cbFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumSubject['mdlFields'] as $field => $fChilds) {
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $sFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }

        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        $sHeader['no'] = "class='bg-success text-center'";
        foreach ($sFieldToshows as $kolom => $kolomAlias) {
            $sHeader[$kolomAlias] = "class='bg-success text-center'";

        }
        $pHeader['no'] = "class='bg-success text-center'";
        foreach ($pFieldToshows as $kolom => $kolomAlias) {
            $pHeader[$kolomAlias] = "class='bg-success text-center'";

        }


        $content = "";
        // arrprint($stepNames);
        // arrprint($tblBodies);
        // arrPrint($this->session->login);
        // [jenis] => spv_penj, admin
        // region navigasi
        $segments = $this->uri->segment_array();
        $strAction = $cUrl = base_url() . implode("/", $segments);
        // arrPrint($cUrl);
        $urlAdd = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $ly->setOnClickTarget("$strAction");
        $bulanNow = dtimeNow("Y-m");

        $bulanPilihan = isset($_GET['date']) ? $_GET['date'] : $bulanNow;
        $tahunPilihan = isset($_GET['year']) ? $_GET['year'] : "";
        $content .= "<div class='row margin-bottom-10'>";
        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectTahun($tahunPilihan);
        $content .= "</div>";

        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectBulan($bulanPilihan);
        $content .= "</div>";
        $content .= "</div>";
        // endregion navigasi

        if (sizeof($tblBodies) > 0) {
            /* -----------------------------
             * SUMMARY
             * -----------------------------*/
            //region summary cabang branch
            $content .= "<div class='box box-info'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumCabangTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin'>";
            //region header cabang
            foreach ($cbHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            foreach ($sumCabang as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($cbFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $cbFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($cbFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        $sumValue[$kolom] += isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($cbFieldFormat[$kolom])) {
                        $fValue = $cbFieldFormat[$kolom]($kolom, isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0);
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($cbFieldLink[$kolom])) {
                        $lValue = "<a href='" . $cbFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }

            //region footer cabang
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($cbFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-info'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-info'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            //endregion

            //region summary sales
            $content .= "<div class='box box-success'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumSubjectTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='sallesman'>";
            //region header cabang
            $content .= "<thead>";
            foreach ($sHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            $content .= "</thead>";
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            $content .= "<tbody>";
            foreach ($sumSubject as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($sFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $sFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($sFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        if (isset($nSpec[$kolom])) {

                            $sumValue[$kolom] += $nSpec[$kolom];
                        }
                        else {
                            $sumValue[$kolom] += 0;
                        }
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($sFieldFormat[$kolom])) {
                        if (isset($nSpec[$kolom])) {

                            $fValue = $sFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
                        }
                        else {
                            $fValue = $sFieldFormat[$kolom]($kolom, 0);;
                        }
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($sFieldLink[$kolom])) {
                        $lValue = "<a href='" . $sFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }
            $content .= "</tbody>";

            //region footer cabang
            $content .= "<tfoot>";
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($sFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-success'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "<script>
                            $(document).ready( function(){
                                $('#sallesman').DataTable({
                                    paging: false,
                                    searching: false,
                                    order: [[2,'desc']]
                                });                                    
                            });
                         </script>";
            //endregion

            //region summary produk
            $content .= "<div class='box box-success'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumObjectTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='produk'>";
            //region header
            $content .= "<thead>";
            foreach ($pHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            $content .= "</thead>";
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            $content .= "<tbody>";
            foreach ($sumObject as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($pFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $pFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($pFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        if (isset($nSpec[$kolom])) {

                            $sumValue[$kolom] += $nSpec[$kolom];
                        }
                        else {
                            $sumValue[$kolom] += 0;
                        }
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($pFieldFormat[$kolom])) {
                        if (isset($nSpec[$kolom])) {

                            $fValue = $pFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
                        }
                        else {
                            $fValue = $pFieldFormat[$kolom]($kolom, 0);;
                        }
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($pFieldLink[$kolom])) {
                        $lValue = "<a href='" . $pFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }
            $content .= "</tbody>";

            //region footer
            $content .= "<tfoot>";
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($pFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-success'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "<script>
                            $(document).ready( function(){
                                $('#produk').DataTable({
                                    paging: false,
                                    searching: true,
                                    order: [[8,'desc']]
                                });                                    
                            });
                         </script>";
            //endregion

            /*=======================================*/

            $content .= "<div class='panel-body'>";
            $content .= "<div class='tab-content'>";
            // $ix =0;
            // arrPrint($stepNames);
            // arrPrint($tblBodies);
            // foreach ($stepNames as $stLink => $stLabel) {
            //     $ix ++;
            //     $tblBody = $tblBodies[$ix];
            // foreach ($tblBodies as $ix => $tblBody) {
            // arrPrint($tblBody);
            $datatables_r = "datatables";
            $tblHeading = isset($tblHeadings) ? $tblHeadings : "";

            $content .= "<div class='tab-pane fade in active' id='tab'>";
            $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
            if (isset($tblHeadings)) {
                //region table heading
                $content .= "<thead>";
                $content .= "<tr>";
                // foreach ($tblHeadings as $tblHeading) {
                foreach ($tblHeading as $label => $attr) {
                    $content .= "<th align='center' $attr>";
                    $content .= $label;
                    $content .= "</th>";
                }
                // }
                $content .= "</tr>";
                $content .= "</thead>";
                //endregion
            }
            // $specs = $tblBodies;
            //region table body
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            $content .= "<tbody>";
            foreach ($tblBodies as $oID => $specs) {
                $content .= "<tr>";
                foreach ($specs as $spec) {
                    $content .= "<td " . $spec['attr'] . ">";
                    $content .= $spec['value'];
                    $content .= "</td>";
                }
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            //endregion

            // region footer
            $content .= "<tfoot>";
            $content .= "<tr>";

            //                foreach ($tblFooters as $footer => $fAttr) {
            //                    $content .= "<th $fAttr>";
            //                    $content .= $footer;
            //                    $content .= "</th>";
            //                }
            // arrPrintWebs($tblHeading);
            foreach ($tblHeading as $label => $attr) {
                $content .= "<th align='center' $attr>";
                $content .= "--";
                $content .= "</th>";
            }

            $content .= "</tr>";
            $content .= "</tfoot>";
            // endregion footer
            $content .= "</table>";
            $content .= "</div>";

            if (sizeof($tblHeading) > 0) {
                $content .= "<script>
                            $(document).ready( function(){
                                var table = $('table.$datatables_r').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                                {
                                                    extend: 'print',
                                                    footer: true,
                                                    text: 'CETAK',
                                                },
                                                {
                                                    extend: 'copyHtml5',
                                                    footer: true,
                                                    text: 'COPY',
                                                },
                                                {
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    text: 'CSV',
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    text: 'EXCEL',
                                                },
                                                {
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    text: 'PDF',
                                                },
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
                                                        i.replace(/[$,()]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };

                                                var arrayFooterTmp = $('tfoot>tr>th');
                                                var dpageTotal = [];

                                                var arrBlackList = [5,6,7,8,9,10];
//                                                var arrColSpan = [0,1,2,3,4];
                                                var arrSaldo = [10];
                                                var arrSaldoQty = [9];

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrBlackList.includes(i)){
                                                        var id_n_index = parseFloat(i);
                                                        dpageTotal[id_n_index] = 0;
                                                        jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                            obj = intVal(obj)>0 ? intVal(obj) : intVal($(obj).html())>0 ? intVal($(obj).html()) : 0;

                                                              dpageTotal[id_n_index] += intVal( obj );

//                                                             dpageTotal[id_n_index] += intVal($(obj).html())==0 ? intVal(obj) : intVal($(obj).html()) ;
//                                                             console.log( $(obj).html()!=='undefine' ? $(obj).html() : obj );
//                                                             console.log( typeof obj );
//                                                             console.log( (intVal(obj)==0 ? $(obj).html() : 0) );
                                                        });
                                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrSaldo.includes(i)){
                                                        var saldo = 0;
                                                        var sales = 0;
                                                        var returns = 0;
                                                        sales = $( 'div', api.column(6).footer() ).html();
                                                            sales = sales.replace(/[$.]/g,'');
                                                        returns = $( 'div', api.column(8).footer() ).html()
                                                            returns = returns.replace(/[$.]/g,'');
                                                        saldo = intVal(sales) - intVal(returns)
                                                        if( !isNaN(saldo) && saldo > 0 || saldo < 0){
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });


                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrSaldoQty.includes(i)){
                                                        var saldo = 0;
                                                        var sales = 0;
                                                        var returns = 0;
                                                        sales = $( 'div', api.column(5).footer() ).html();
                                                            sales = sales.replace(/[$.]/g,'');
                                                        returns = $( 'div', api.column(7).footer() ).html()
                                                            returns = returns.replace(/[$.]/g,'');
                                                        saldo = intVal(sales) - intVal(returns)
                                                        if( !isNaN(saldo) && saldo > 0 || saldo < 0){
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });

//                                                 var keys = Object.keys(arrColSpan);
//                                                 var last = keys[keys.length-1];
//                                                 jQuery.each(arrayFooterTmp, function(i,d){
//                                                     if(arrColSpan.includes(i)){
//                                                         if(i==last){
//                                                             $( api.column(i).footer()).attr('colspan', 5);
//                                                             $( api.column(i).footer() ).html(
//                                                                 \"<div class='text-right text-muted text-bold'>  S U M M A R Y  </div>\"
//                                                             );
//                                                         }
//                                                         else{
//                                                             $( api.column(i).footer() ).remove();
//                                                         }
//                                                     }
//                                                 });

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
            }

            // } //  bodies


            $content .= "</div>"; // tab cntent
            $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;

    case "viewInvoice":
        $ly = new Layout();
        $reportingSumCabang = $confReportCabang;
        $reportingSumSubject = $confReportSubject;
        $sumSubjectTitle = $reportingSumSubject['title'];
        $sumCabangTitle = $reportingSumCabang['title'];

        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $cbFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumSubject['mdlFields'] as $field => $fChilds) {
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $sFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        $sHeader['no'] = "class='bg-success text-center'";
        foreach ($sFieldToshows as $kolom => $kolomAlias) {
            $sHeader[$kolomAlias] = "class='bg-success text-center'";

        }


        $content = "";
        // arrprint($stepNames);
        // arrprint($tblBodies);
        // arrPrint($this->session->login);
        // [jenis] => spv_penj, admin
        // region navigasi
        $segments = $this->uri->segment_array();
        $strAction = $cUrl = base_url() . implode("/", $segments);
        // arrPrint($cUrl);
        $urlAdd = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $ly->setOnClickTarget("$strAction");
        $bulanNow = dtimeNow("Y-m");

        $bulanPilihan = isset($_GET['date']) ? $_GET['date'] : $bulanNow;
        $content .= "<div class='row margin-bottom-10'>";
        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectBulan($bulanPilihan);
        $content .= "</div>";
        $content .= "</div>";
        // endregion navigasi

        if (sizeof($tblBodies) > 0) {
            /* -----------------------------
             * SUMMARY
             * -----------------------------*/
            // //region summary cabang branch
            // $content .= "<div class='box box-info'>";
            // $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumCabangTitle</h3></div>";
            // $content .= "<div class='box-body'>";
            // $content .= "<div class='table-responsive'>";
            // $content .= "<table align='center' class='table table-condensed no-margin'>";
            // //region header cabang
            // foreach ($cbHeader as $kAlias => $hAtt) {
            //     $content .= "<th $hAtt>$kAlias</th>";
            // }
            // //endregion
            // // arrPrint($cbFieldSumrows);
            // $cbNo = 0;
            // $sumValue = array();
            // foreach ($sumCabang as $nID => $nSpec) {
            //     $cbNo++;
            //     $content .= "<tr>";
            //
            //     $content .= "<td class='text-right'>$cbNo</td>";
            //     foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            //         $cAttr = $cbFieldAttr[$kolom];
            //
            //         //$cbFieldSumrows
            //         if (isset($cbFieldSumrows[$kolom])) {
            //             if (!isset($sumValue[$kolom])) {
            //                 $sumValue[$kolom] = 0;
            //             }
            //             $sumValue[$kolom] += $nSpec[$kolom];
            //         }
            //         else {
            //             $sumValue[$kolom] = "-";
            //             $sumValue['no'] = "-";
            //         }
            //
            //         //region formater value
            //         if (isset($cbFieldFormat[$kolom])) {
            //             $fValue = $cbFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
            //         }
            //         else {
            //             $fValue = $nSpec[$kolom];
            //         }
            //         //endregion
            //
            //         //region linker value
            //         if (isset($cbFieldLink[$kolom])) {
            //             $lValue = "<a href='" . $cbFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
            //         }
            //         else {
            //             $lValue = $fValue;
            //         }
            //         //endregion
            //
            //
            //         $content .= "<td $cAttr>";
            //         $content .= $lValue;
            //         $content .= "</td>";
            //     }
            //
            //     $content .= "</tr>";
            // }
            //
            // //region footer cabang
            // $content .= "<tr>";
            // foreach ($sumValue as $kolom => $kAlias) {
            //     $footerValue = $sumValue[$kolom];
            //     if (isset($cbFieldSumrows[$kolom])) {
            //         $content .= "<th class='text-right bg-info'>" . formatField('nilai_af', $footerValue) . "</th>";
            //     }
            //     else {
            //         $content .= "<th class='text-right bg-info'>$footerValue</th>";
            //     }
            // }
            // $content .= "</tr>";
            // //endregion
            // // arrPrint($sumValue);
            // $content .= "</table>";
            // $content .= "</div>";
            // $content .= "</div>";
            // $content .= "</div>";
            // //endregion

            // //region summary sales
            // $content .= "<div class='box box-success'>";
            // $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumSubjectTitle</h3></div>";
            // $content .= "<div class='box-body'>";
            // $content .= "<div class='table-responsive'>";
            // $content .= "<table align='center' class='table table-condensed no-margin table-hover'>";
            // //region header cabang
            // foreach ($sHeader as $kAlias => $hAtt) {
            //     $content .= "<th $hAtt>$kAlias</th>";
            // }
            // //endregion
            // // arrPrint($cbFieldSumrows);
            // $cbNo = 0;
            // $sumValue = array();
            // foreach ($sumSubject as $nID => $nSpec) {
            //     $cbNo++;
            //     $content .= "<tr>";
            //
            //     $content .= "<td class='text-right'>$cbNo</td>";
            //     foreach ($sFieldToshows as $kolom => $kolomAlias) {
            //         $cAttr = $sFieldAttr[$kolom];
            //
            //         //$cbFieldSumrows
            //         if (isset($sFieldSumrows[$kolom])) {
            //             if (!isset($sumValue[$kolom])) {
            //                 $sumValue[$kolom] = 0;
            //             }
            //             $sumValue[$kolom] += $nSpec[$kolom];
            //         }
            //         else {
            //             $sumValue[$kolom] = "-";
            //             $sumValue['no'] = "-";
            //         }
            //
            //         //region formater value
            //         if (isset($sFieldFormat[$kolom])) {
            //             $fValue = $sFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
            //         }
            //         else {
            //             $fValue = $nSpec[$kolom];
            //         }
            //         //endregion
            //
            //         //region linker value
            //         if (isset($sFieldLink[$kolom])) {
            //             $lValue = "<a href='" . $sFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
            //         }
            //         else {
            //             $lValue = $fValue;
            //         }
            //         //endregion
            //
            //
            //         $content .= "<td $cAttr>";
            //         $content .= $lValue;
            //         $content .= "</td>";
            //     }
            //
            //     $content .= "</tr>";
            // }
            //
            // //region footer cabang
            // $content .= "<tr>";
            // foreach ($sumValue as $kolom => $kAlias) {
            //     $footerValue = $sumValue[$kolom];
            //     if (isset($cbFieldSumrows[$kolom])) {
            //         $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
            //     }
            //     else {
            //         $content .= "<th class='text-right bg-success'>$footerValue</th>";
            //     }
            // }
            // $content .= "</tr>";
            // //endregion
            // // arrPrint($sumValue);
            // $content .= "</table>";
            // $content .= "</div>";
            // $content .= "</div>";
            // $content .= "</div>";
            // //endregion
            /*=======================================*/

            $content .= "<div class='panel-body'>";
            $content .= "<div class='tab-content'>";
            // $ix =0;
            // arrPrint($stepNames);
            // arrPrint($tblBodies);
            // foreach ($stepNames as $stLink => $stLabel) {
            //     $ix ++;
            //     $tblBody = $tblBodies[$ix];
            // foreach ($tblBodies as $ix => $tblBody) {
            // arrPrint($tblBody);
            $datatables_r = "datatables";
            $tblHeading = isset($tblHeadings) ? $tblHeadings : "";

            $content .= "<div class='tab-pane fade in active' id='tab'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
            if (isset($tblHeadings)) {
                //region table heading
                $content .= "<thead>";
                $content .= "<tr class='text-uppercase'>";
                // foreach ($tblHeadings as $tblHeading) {

                foreach ($tblHeading as $label => $attr) {
                    $content .= "<th align='center' $attr>";
                    $content .= $label;
                    $content .= "</th>";
                }
                // }
                $content .= "</tr>";
                $content .= "</thead>";
                //endregion
            }

            //region table body
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            $content .= "<tbody>";
            foreach ($tblBodies as $oID => $specs) {

                //                cekHere(" di sini masalah nya ". sizeof($specs));

                if (sizeof($specs) > 0) {
                    $content .= "<tr>";
                    foreach ($specs as $spec) {
                        $content .= "<td " . $spec['attr'] . ">";
                        $content .= $spec['value'];
                        $content .= "</td>";
                    }
                    $content .= "</tr>";
                }


            }
            $content .= "</tbody>";
            //endregion

            // region footer
            $attr = "class='bg-info'";
            $content .= "<tfoot>";
            $content .= "<tr>";

            //                foreach ($tblFooters as $footer => $fAttr) {
            //                    $content .= "<th $fAttr>";
            //                    $content .= $footer;
            //                    $content .= "</th>";
            //                }
            // arrPrintWebs($tblHeading);
            // arrPrint($sumValue);
            // foreach ($tblHeading as $label => $attr) {
            // $content .= "<th align='center' $attr>";
            // $content .= "-";
            // $content .= "</th>";
            foreach ($historyFields as $label => $attrs) {
                $fvalue = isset($sumValue[$label]) ? $sumValue[$label] : "-";
                $fvalue = isset($sumValue[$label]) ? formatField($label, $sumValue[$label]) : "-";
                $content .= "<th align='center' $attr>";
                $content .= $fvalue;
                $content .= "</th>";
            }

            $content .= "</tr>";
            $content .= "</tfoot>";
            // endregion footer
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";

            if (sizeof($tblHeading) > 0) {
                $content .= "<script>
                            $(document).ready( function(){
                                var table = $('table.$datatables_r').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                                {
                                                    extend: 'print',
                                                    footer: true,
                                                    text: 'CETAK',
                                                },
                                                {
                                                    extend: 'copyHtml5',
                                                    footer: true,
                                                    text: 'COPY',
                                                },
                                                {
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    text: 'CSV',
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    text: 'EXCEL',
                                                },
                                                {
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    text: 'PDF',
                                                },
                                            ],

                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;

                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,()]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };

                                                var arrayFooterTmp = $('tfoot>tr>th');
                                                var dpageTotal = [];

                                                var arrBlackList = [5,6,7,8,9,10];
                                                var arrSaldo = [10];
                                                var arrSaldoQty = [9];

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrBlackList.includes(i)){
                                                        var id_n_index = parseFloat(i);
                                                        dpageTotal[id_n_index] = 0;
                                                        jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                            obj = intVal(obj)>0 ? intVal(obj) : intVal($(obj).html())>0 ? intVal($(obj).html()) : 0;

                                                              dpageTotal[id_n_index] += intVal( obj );
                                                        });
                                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });
                                            }
                                        });
        
                                    });
        
                                    $('.table-responsive').floatingScroll();
                                    $('.table-responsive').scroll(function () {
                                    setTimeout(function () {
                                        $('table.$datatables_r').DataTable().fixedHeader.adjust();
                                    }, 400);
                                });
                            </script>";
            }

            $content .= "</div>"; // tab cntent
            $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;

    case "viewMovement":
        $ly = new Layout();
        $reportingSumCabang = $confReportCabang;
        $reportingSumSubject = $confReportSubject;
        $reportingSumObject = $confReportObject;
        $sumSubjectTitle = isset($reportingSumSubject['title']) ? $reportingSumSubject['title'] : "";
        $sumCabangTitle = isset($reportingSumCabang['title']) ? $reportingSumCabang['title'] : "";
        $sumObjectTitle = isset($reportingSumObject['title']) ? $reportingSumObject['title'] : "";
        // arrPrint($reportingSumCabang);
        if (isset($reportingSumCabang['mdlFields'])) {
            // cekHere();
            foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
                $cbFields[] = $field;
                if (isset($fChilds['label'])) {
                    $cbFieldToshows[$field] = $fChilds['label'];
                }
                isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                if (isset($fChilds['attr'])) {
                    $cbFieldAttr[$field] = $fChilds['attr'];
                }
                if (isset($fChilds['link'])) {
                    $cbFieldLink[$field] = $fChilds['link'];
                }
                if (isset($fChilds['format'])) {
                    $cbFieldFormat[$field] = $fChilds['format'];
                }
                if (isset($fChilds['sum_rows'])) {
                    $cbFieldSumrows[$field] = $fChilds['sum_rows'];
                }
            }

            $cbHeader['no'] = "class='bg-info text-center'";
            foreach ($cbFieldToshows as $kolom => $kolomAlias) {
                $cbHeader[$kolomAlias] = "class='bg-info text-center'";

            }
        }
        if (isset($reportingSumSubject['mdlFields'])) {
            foreach ($reportingSumSubject['mdlFields'] as $field => $fChilds) {
                $sFields[] = $field;
                if (isset($fChilds['label'])) {
                    $sFieldToshows[$field] = $fChilds['label'];
                }
                isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                if (isset($fChilds['attr'])) {
                    $sFieldAttr[$field] = $fChilds['attr'];
                }
                if (isset($fChilds['link'])) {
                    $sFieldLink[$field] = $fChilds['link'];
                }
                if (isset($fChilds['format'])) {
                    $sFieldFormat[$field] = $fChilds['format'];
                }
                if (isset($fChilds['sum_rows'])) {
                    $sFieldSumrows[$field] = $fChilds['sum_rows'];
                }
            }

            $sHeader['no'] = "class='bg-success text-center'";
            foreach ($sFieldToshows as $kolom => $kolomAlias) {
                $sHeader[$kolomAlias] = "class='bg-success text-center'";

            }
        }
        if (isset($reportingSumObject['mdlFields'])) {
            foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
                $pFields[] = $field;
                if (isset($fChilds['label'])) {
                    $pFieldToshows[$field] = $fChilds['label'];
                }
                isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                if (isset($fChilds['attr'])) {
                    $pFieldAttr[$field] = $fChilds['attr'];
                }
                if (isset($fChilds['link'])) {
                    $pFieldLink[$field] = $fChilds['link'];
                }
                if (isset($fChilds['format'])) {
                    $pFieldFormat[$field] = $fChilds['format'];
                }
                if (isset($fChilds['sum_rows'])) {
                    $pFieldSumrows[$field] = $fChilds['sum_rows'];
                }
            }

            $pHeader['no'] = "class='bg-success text-center'";
            foreach ($pFieldToshows as $kolom => $kolomAlias) {
                $pHeader[$kolomAlias] = "class='bg-success text-center'";

            }
        }


        $content = "";
        // arrprint($stepNames);
        // arrprint($tblBodies);
        // arrPrint($this->session->login);
        // [jenis] => spv_penj, admin
        // region navigasi

        $btnOn = $this->uri->segment(3);
        $segments = $this->uri->segment_array();
        $strAction = $cUrl = base_url() . implode("/", $segments);
        // arrPrint($cUrl);
        if (isset($confAllReport['navigasi'])) {
            $tabs = "<div class='col-md-3 pull-left'>";
            $tabs .= "<div class='btn-group'>";
            foreach ($confAllReport['navigasi'] as $navKey => $navSpecs) {
                $navLabel = $navSpecs['label'];
                $navLink = $navSpecs['link'];
                $btnActive = $navKey == $btnOn ? "active" : "";
                $tabs .= "<button type='button' class='btn btn-info $btnActive' onclick=\"location.href='" . base_url() . "$navLink'\">$navLabel</button>";
            }

            $tabs .= "</div>";
            $tabs .= "</div>";
        }


        $urlAdd = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $ly->setOnClickTarget("$strAction");
        $bulanNow = dtimeNow("Y-m");

        $bulanPilihan = isset($_GET['date']) ? $_GET['date'] : $bulanNow;
        $content .= "<div class='row margin-bottom-10'>";
        $content .= $tabs;
        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectBulan($bulanPilihan);
        $content .= "</div>";
        $content .= "</div>";
        // endregion navigasi


        /* -----------------------------
         * SUMMARY
         * -----------------------------*/
        //region summary cabang branch
        // arrPrintLime($sumCabang);
        if (sizeof($sumCabang) > 0) {

            $content .= "<div class='box box-info'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumCabangTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin'>";
            //region header cabang
            // arrPrint($cbHeader);
            foreach ($cbHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            foreach ($sumCabang as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($cbFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $cbFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($cbFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        $sumValue[$kolom] += isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($cbFieldFormat[$kolom])) {
                        $fValue = $cbFieldFormat[$kolom]($kolom, isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0);
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($cbFieldLink[$kolom])) {
                        $lValue = "<a href='" . $cbFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }

            //region footer cabang
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($cbFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-info'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-info'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
        }
        //endregion

        //region summary sales
        if (sizeof($sumSubject) > 0) {

            $content .= "<div class='box box-success'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumSubjectTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='sallesman'>";
            //region header cabang
            $content .= "<thead>";
            foreach ($sHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            $content .= "</thead>";
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            $content .= "<tbody>";
            foreach ($sumSubject as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($sFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $sFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($sFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        if (isset($nSpec[$kolom])) {

                            $sumValue[$kolom] += $nSpec[$kolom];
                        }
                        else {
                            $sumValue[$kolom] += 0;
                        }
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($sFieldFormat[$kolom])) {
                        if (isset($nSpec[$kolom])) {

                            $fValue = $sFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
                        }
                        else {
                            $fValue = $sFieldFormat[$kolom]($kolom, 0);;
                        }
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($sFieldLink[$kolom])) {
                        $lValue = "<a href='" . $sFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }
            $content .= "</tbody>";

            //region footer cabang
            $content .= "<tfoot>";
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($sFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-success'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "<script>
                            $(document).ready( function(){
                                $('#sallesman').DataTable({
                                    paging: false,
                                    searching: false,
                                    order: [[2,'desc']]
                                });                                    
                            });
                         </script>";
        }
        //endregion

        //region summary produk
        if (sizeof($sumObject) > 0) {
            $content .= "<div class='box box-success'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumObjectTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='produk'>";
            //region header
            $content .= "<thead>";
            foreach ($pHeader as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            $content .= "</thead>";
            //endregion
            // arrPrint($cbFieldSumrows);
            $cbNo = 0;
            $sumValue = array();
            $content .= "<tbody>";
            foreach ($sumObject as $nID => $nSpec) {
                $cbNo++;
                $content .= "<tr>";

                $content .= "<td class='text-right'>$cbNo</td>";
                foreach ($pFieldToshows as $kolom => $kolomAlias) {
                    $cAttr = $pFieldAttr[$kolom];

                    //$cbFieldSumrows
                    if (isset($pFieldSumrows[$kolom])) {
                        if (!isset($sumValue[$kolom])) {
                            $sumValue[$kolom] = 0;
                        }
                        if (isset($nSpec[$kolom])) {

                            $sumValue[$kolom] += $nSpec[$kolom];
                        }
                        else {
                            $sumValue[$kolom] += 0;
                        }
                    }
                    else {
                        $sumValue[$kolom] = "-";
                        $sumValue['no'] = "-";
                    }

                    //region formater value
                    if (isset($pFieldFormat[$kolom])) {
                        if (isset($nSpec[$kolom])) {

                            $fValue = $pFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
                        }
                        else {
                            $fValue = $pFieldFormat[$kolom]($kolom, 0);;
                        }
                    }
                    else {
                        $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
                    }
                    //endregion

                    //region linker value
                    if (isset($pFieldLink[$kolom])) {
                        $lValue = "<a href='" . $pFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                    }
                    else {
                        $lValue = $fValue;
                    }
                    //endregion


                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }

                $content .= "</tr>";
            }
            $content .= "</tbody>";

            //region footer
            $content .= "<tfoot>";
            $content .= "<tr>";
            foreach ($sumValue as $kolom => $kAlias) {
                $footerValue = $sumValue[$kolom];
                if (isset($pFieldSumrows[$kolom])) {
                    $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
                else {
                    $content .= "<th class='text-right bg-success'>$footerValue</th>";
                }
            }
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion
            // arrPrint($sumValue);
            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "<script>
                            $(document).ready( function(){
                                $('#produk').DataTable({
                                    paging: false,
                                    searching: true,
                                    order: [[8,'desc']]
                                });                                    
                            });
                         </script>";
        }
        //endregion
        /*=======================================*/
        if (sizeof($tblBodies) > 0) {
            $content .= "<div class='panel-body'>";
            $content .= "<div class='tab-content'>";
            // $ix =0;
            // arrPrint($stepNames);
            // arrPrint($tblBodies);
            // foreach ($stepNames as $stLink => $stLabel) {
            //     $ix ++;
            //     $tblBody = $tblBodies[$ix];
            // foreach ($tblBodies as $ix => $tblBody) {
            // arrPrint($tblBody);
            $datatables_r = "datatables";
            $tblHeading = isset($tblHeadings) ? $tblHeadings : "";

            $content .= "<div class='tab-pane fade in active' id='tab'>";
            $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
            if (isset($tblHeadings)) {
                //region table heading
                $content .= "<thead>";
                $content .= "<tr>";
                // foreach ($tblHeadings as $tblHeading) {
                foreach ($tblHeading as $label => $attr) {
                    $content .= "<th align='center' $attr>";
                    $content .= $label;
                    $content .= "</th>";
                }
                // }
                $content .= "</tr>";
                $content .= "</thead>";
                //endregion
            }
            // $specs = $tblBodies;
            //region table body
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            $content .= "<tbody>";
            foreach ($tblBodies as $oID => $specs) {
                $content .= "<tr>";
                foreach ($specs as $spec) {
                    $content .= "<td " . $spec['attr'] . ">";
                    $content .= $spec['value'];
                    $content .= "</td>";
                }
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            //endregion

            // region footer
            $content .= "<tfoot>";
            $content .= "<tr>";

            //                foreach ($tblFooters as $footer => $fAttr) {
            //                    $content .= "<th $fAttr>";
            //                    $content .= $footer;
            //                    $content .= "</th>";
            //                }
            // arrPrintWebs($tblHeading);
            foreach ($tblHeading as $label => $attr) {
                $content .= "<th align='center' $attr>";
                $content .= "--";
                $content .= "</th>";
            }

            $content .= "</tr>";
            $content .= "</tfoot>";
            // endregion footer
            $content .= "</table>";
            $content .= "</div>";

            if (sizeof($tblHeading) > 0) {
                $content .= "<script>
                            $(document).ready( function(){
                                var table = $('table.$datatables_r').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                                {
                                                    extend: 'print',
                                                    footer: true,
                                                    text: 'CETAK',
                                                },
                                                {
                                                    extend: 'copyHtml5',
                                                    footer: true,
                                                    text: 'COPY',
                                                },
                                                {
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    text: 'CSV',
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    text: 'EXCEL',
                                                },
                                                {
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    text: 'PDF',
                                                },
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
                                                        i.replace(/[$,()]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };

                                                var arrayFooterTmp = $('tfoot>tr>th');
                                                var dpageTotal = [];

                                                var arrBlackList = [5,6,7,8,9,10];
//                                                var arrColSpan = [0,1,2,3,4];
                                                var arrSaldo = [10];
                                                var arrSaldoQty = [9];

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrBlackList.includes(i)){
                                                        var id_n_index = parseFloat(i);
                                                        dpageTotal[id_n_index] = 0;
                                                        jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                            obj = intVal(obj)>0 ? intVal(obj) : intVal($(obj).html())>0 ? intVal($(obj).html()) : 0;

                                                              dpageTotal[id_n_index] += intVal( obj );

//                                                             dpageTotal[id_n_index] += intVal($(obj).html())==0 ? intVal(obj) : intVal($(obj).html()) ;
//                                                             console.log( $(obj).html()!=='undefine' ? $(obj).html() : obj );
//                                                             console.log( typeof obj );
//                                                             console.log( (intVal(obj)==0 ? $(obj).html() : 0) );
                                                        });
                                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(id_n_index).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });

                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrSaldo.includes(i)){
                                                        var saldo = 0;
                                                        var sales = 0;
                                                        var returns = 0;
                                                        sales = $( 'div', api.column(6).footer() ).html();
                                                            sales = sales.replace(/[$.]/g,'');
                                                        returns = $( 'div', api.column(8).footer() ).html()
                                                            returns = returns.replace(/[$.]/g,'');
                                                        saldo = intVal(sales) - intVal(returns)
                                                        if( !isNaN(saldo) && saldo > 0 || saldo < 0){
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });


                                                jQuery.each(arrayFooterTmp, function(i,d){
                                                    if(arrSaldoQty.includes(i)){
                                                        var saldo = 0;
                                                        var sales = 0;
                                                        var returns = 0;
                                                        sales = $( 'div', api.column(5).footer() ).html();
                                                            sales = sales.replace(/[$.]/g,'');
                                                        returns = $( 'div', api.column(7).footer() ).html()
                                                            returns = returns.replace(/[$.]/g,'');
                                                        saldo = intVal(sales) - intVal(returns)
                                                        if( !isNaN(saldo) && saldo > 0 || saldo < 0){
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
                                                            );
                                                        }else{
                                                            $( api.column(i).footer() ).html(
                                                                \"<div class='text-right text-primary text-bold'>0</div>\"
                                                            );
                                                        }
                                                    }
                                                });

//                                                 var keys = Object.keys(arrColSpan);
//                                                 var last = keys[keys.length-1];
//                                                 jQuery.each(arrayFooterTmp, function(i,d){
//                                                     if(arrColSpan.includes(i)){
//                                                         if(i==last){
//                                                             $( api.column(i).footer()).attr('colspan', 5);
//                                                             $( api.column(i).footer() ).html(
//                                                                 \"<div class='text-right text-muted text-bold'>  S U M M A R Y  </div>\"
//                                                             );
//                                                         }
//                                                         else{
//                                                             $( api.column(i).footer() ).remove();
//                                                         }
//                                                     }
//                                                 });

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
            }

            // } //  bodies


            $content .= "</div>"; // tab cntent
            $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;

    case "viewRel":
        //        cekLime("sini");
        $segment_uri_array = $this->uri->segment_array();
        $segmenUri = implode("/", $segment_uri_array);
        //        arrPrint($main_values);
        //region view range tanggal
        $uri_1 = $segment_uri_array['1'];
        $uri_2 = $segment_uri_array['2'];
        //        $uri_3 = $segment_uri_array['3'];
        $date1 = isset($_GET['date1']) ? "?date1=" . $_GET['date1'] : "";
        $date2 = isset($_GET['date2']) ? "&date2=" . $_GET['date2'] : "";

        //        $navigasies[base_url() . $uri_1 . "/viewHistory/" . $uri_3] = "Baru";
        //        $navigasies[base_url() . $uri_1 . "/viewHistoryOld/" . $uri_3] = "Lama";

        $btnNavigasi = "";

        $navLink = base_url() . $uri_1 . "/$uri_2/" . $uri_2 . $date1 . $date2;
        //        $btnDisabled = $navMetode == $uri_2 ? "disabled" : "";
        $btnNavigasi .= "<button type='button' class='btn btn-info'  onclick=\"location.href='$navLink';\"><i class='fa fa-history'>&nbsp; </i></button> ";
        if (isset($_GET['date1'])) {
            $date1_f = formatField("fulldate", $_GET['date1']);
            $date2_f = formatField("fulldate", $_GET['date2']);
            $dateRange = $date1_f . "  <b>s/d</b>  " . $date2_f;
        }
        else {
            $dateRange = "";
        }

        //        arrPrint( sizeof($dateRange) );
        // $subTitle = "<h4 class='no-padding no-margin text-capitalise'>".$subTitle . " $dateRange </h4>";
        // $subTitle = "" == $dateRange ? $subTitle . " $dateRange" : "$dateRange";
        // cekKuning("$subTitle");
        // $filters['date1'] = "2019-10-9";
        //endregion
        //endregion
        //        arrPrint($mainLabel);
        $content = "<div class='panel '>";
        $content .= "<div class='table-responsive data_view_rel' >";
        $content .= "<table class='table hover datatables table-condensed table-bordered no-padding' id='data_view_rel'>";
        $content .= "<thead>";
        $content .= "<tr bgcolor='#f0f0f0'>";
        $content .= "<th>No</th>";
        foreach ($mainLabel as $field => $fieldLabel) {
            $content .= "<th>$fieldLabel</th>";
        }
        $content .= "</tr>";
        $content .= "</thead>";
        $i = 0;
        foreach ($main_values as $keyID => $tempValue) {
//            arrPrintPink($tempValue);
            $i++;
            $mainDetail = $main_detail[$keyID];
            $content .= "<tr>";
            $content .= "<td>$i.</td>";
            foreach ($mainLabel as $field => $fieldsLabel) {
                //                cekMerah($field);
                if (isset($tempValue[$field])) {
                    $detailFields = $tempValue[$field];
                }
                else {
                    $detailFields = isset($mainDetail[$field]) ? $mainDetail[$field] : array();
                }
                //arrPrint($detailFields);
                if (is_array($detailFields)) {

                    if (sizeof($detailFields) > 0) {
                        //                        arrPrint($detailFields);
                        $content .= "<td>";
                        //                        $content .="<div>";
                        $value_sum = 0;
                        foreach ($detailFields as $key => $keTmp) {
                            $iID = $keTmp['id'];
                            $nmr = $keTmp['nomer'];
                            $status = $keTmp['trash_4'] > 0 ? "text-red text-coret" : "fa fa-checked";
                            $date = $keTmp['dtime'];
                            $oleh_id = $keTmp['oleh_id'];
                            $olehName = isset($employeeData[$oleh_id]) ? $employeeData[$oleh_id] : "-";

                            //                           cekHitam($nmr);
                            $value = isset($main_registry[$iID]['grand_net']) ? $main_registry[$iID]['grand_net'] : 0;

                            $value_sum += $keTmp['trash_4'] > 0 ? 0 : $value;
                            //                           $content .="<div>";
                            //                           if($field == "582spo"){
                            //                               $content .="<span class='pull-left $status'>".formatField("nomer",$nmr)."</span>";
                            //                           }

                            //
                            $content .= "<span class='pull-left $status'>" . formatField_he_format("nomer", $nmr, $tempValue['jenis_master'], $tempValue['modul_path']) . "</span><br>";
                            //                           $content .="<span class='pull-right $status'>".formatField("harga",$value)."</span><br>";
                            //                           $content .="<span class='pull-left $status'>".formatField("tanggal",$date)."</span><br>";
                            //                           $content .="<span class='pull-left $status'>".formatField("nama",$olehName)."</span><br>";
                            //                           $content .="</div><br>";


                        }
                        //                        $content .="<div ><button type='button' class='text-center btn btn-block'>".formatField("total",$value_sum)."</button</div>";
                        //                        $content .="<div class='row'></div>";
                        //                        $content .="<div ><span  class='pull-right'>".formatField("total",$value_sum)."</span</div>";
                        //                        $content .="</div>";
                        $content .= "</td>";
                    }
                    else {
                        $content .= "<td>-</td>";
                    }
                }
                else {
                    $content .= "<td>" . formatField_he_format($field, $tempValue[$field], $tempValue['jenis_master'], $tempValue['modul_path']) . "</td>";
                }

                //                arrPrint($detailFields);
                //                $content .= isset($tempValue[$field]) ? "<td>".$tempValue[$field]."</td>":"";
            }
            $content .= "</tr>";
        }

        $content .= "</table>";
        $content .= "</div>";

        $content .= "</div>";
        $custom_button = isset($custom_button) ? $custom_button : "";
        /* ------
         * contoh pemakaian excel html5 ada di template/data.html
         * -----*/
        $content .= "<script>
                    $(document).ready( function(){

                        var table = $('#data_view_rel').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print',
                                        // { extend: 'print', footer: true },
                                        // { extend: 'excelHtml5', footer: true, buttonCommon: 'okok'},
                         
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
                                    }
                                });

                            });

                            $('.table-responsive.data_view_rel').floatingScroll();
                            $('.table-responsive.data_view_rel').scroll( delay_v2(function(){ $('table#dataTabel').DataTable().fixedHeader.adjust(); }, 200) );
                    </script>";
        // cekLime("$subTitle");
        $p = New Layout("$title", "$subTitle", "application/template/history_report.html");

        $p->addTags(array(
            "jenisTr" => "",
            // "trName"=>$trName,
            "navigasi" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => "$content",
            "profile_name" => $this->session->login['nama'],
            "url" => $thisPage,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => $filters['dates']['start'],
            "date_max" => $filters['dates']['end'],
            // "add_link"           => $addLinkStr,
            //            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] : "javascript:void(0)",
            //            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;

    case "viewRel2":
        cekLime("sini");
        $segment_uri_array = $this->uri->segment_array();
        $segmenUri = implode("/", $segment_uri_array);
        arrPrint($segment_uri_array);
        //region view range tanggal
        $uri_1 = $segment_uri_array['1'];
        $uri_2 = $segment_uri_array['2'];
        //        $uri_3 = $segment_uri_array['3'];
        $date1 = isset($_GET['date1']) ? "?date1=" . $_GET['date1'] : "";
        $date2 = isset($_GET['date2']) ? "&date2=" . $_GET['date2'] : "";

        //        $navigasies[base_url() . $uri_1 . "/viewHistory/" . $uri_3] = "Baru";
        //        $navigasies[base_url() . $uri_1 . "/viewHistoryOld/" . $uri_3] = "Lama";

        $btnNavigasi = "";

        $navLink = base_url() . $uri_1 . "/$uri_2/" . $uri_2 . $date1 . $date2;
        //        $btnDisabled = $navMetode == $uri_2 ? "disabled" : "";
        $btnNavigasi .= "<button type='button' class='btn btn-info'  onclick=\"location.href='$navLink';\"><i class='fa fa-history'>&nbsp; </i></button> ";
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
        //endregion
        //        arrPrint($mainLabel);
        $content = "<div class='panel '>";
        $content .= "<div class='panegl-body' >";
        $content .= "<table class='table hover datatables table-condensed table-bordered no-padding'>";
        $content .= ("<thead>");
        $content .= ("<tr bgcolor='#f0f0f0'>");
        $content .= "<th>No</th>";
        foreach ($mainLabel as $field => $fieldLabel) {
            $content .= "<th>$fieldLabel</th>";
        }
        $content .= "(</tr>)";
        $content .= ("</thead>");
        $i = 0;
        foreach ($main_values as $keyID => $tempValue) {
            $i++;
            $mainDetail = $main_detail[$keyID];
            $content .= "<tr>";
            $content .= "<td>$i.</td>";
            foreach ($mainLabel as $field => $fieldsLabel) {
                //                cekMerah($field);;
                if (isset($tempValue[$field])) {
                    $detailFields = $tempValue[$field];
                }
                else {
                    $detailFields = isset($mainDetail[$field]) ? $mainDetail[$field] : array();
                }

                if (is_array($detailFields)) {

                    if (sizeof($detailFields) > 0) {
                        //                        arrPrint($detailFields);
                        $content .= "<td>";
                        $content .= "<div>";
                        $value_sum = 0;
                        foreach ($detailFields as $key => $keTmp) {
                            $iID = $keTmp['id'];
                            $nmr = $keTmp['nomer'];
                            $status = $keTmp['trash_4'] > 0 ? "text-red text-coret" : "fa fa-checked";
                            $date = $main_registry[$iID]['dtime'];
                            $oleh_id = $main_registry[$iID]['olehID'];
                            $olehName = $employeeData[$oleh_id];

                            //                           cekHitam($nmr);
                            $value = isset($main_registry[$iID]['grand_net']) ? $main_registry[$iID]['grand_net'] : 0;

                            $value_sum += $keTmp['trash_4'] > 0 ? 0 : $value;
                            $content .= "<div>";
                            $content .= "<span class='pull-left $status'>" . formatField("nomer", $nmr) . "</span>";
                            $content .= "<span class='pull-right $status'>" . formatField("harga", $value) . "</span><br>";
                            $content .= "<span class='pull-left $status'>" . formatField("dtime", $date) . "</span><br>";
                            $content .= "<span class='pull-left $status'>" . formatField("nama", $olehName) . "</span>";
                            $content .= "</div>";


                        }
                        //                        $content .="<div ><button type='button' class='text-center btn btn-block'>".formatField("total",$value_sum)."</button</div>";
                        $content .= "<div class='row'></div>";
                        $content .= "<div ><span  class='pull-right'>" . formatField("total", $value_sum) . "</span</div>";
                        $content .= "</div>";
                        $content .= "</td>";
                    }
                    else {
                        $content .= "<td>-</td>";
                    }
                }
                else {
                    $content .= "<td>" . $tempValue[$field] . "</td>";
                }

                //                arrPrint($detailFields);
                //                $content .= isset($tempValue[$field]) ? "<td>".$tempValue[$field]."</td>":"";
            }
            $content .= "</tr>";
        }


        $content .= "</table>";
        $content .= "</div>";

        $content .= "</div>";
        $p = New Layout("$title", "$subTitle", "application/template/history_report.html");

        $p->addTags(array(
            "jenisTr" => "",
            // "trName"=>$trName,
            "navigasi" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => "$content",
            "profile_name" => $this->session->login['nama'],
            "url" => $thisPage,
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => $filters['dates']['start'],
            "date_max" => $filters['dates']['end'],
            // "add_link"           => $addLinkStr,
            //            "newTrTarget"      => isset($addLink['link']) ? $addLink['link'] : "javascript:void(0)",
            //            "newTrDisp"        => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;
        break;

    case "viewSalesCompared":
        $ly = new Layout();
        $reportingSumCabang = $confReportCabang;
        $reportingSumSubject = $confReportSubject;
        $reportingSumObject = $confReportObject;
        $sumSubjectTitle = $reportingSumSubject['title'];
        $sumCabangTitle = $reportingSumCabang['title'];
        $sumObjectTitle = $reportingSumObject['title'];

        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $cbFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumCabang['mdlFieldsSide'] as $fieldSide => $fChildsSide) {
            //            $cbFields[] = $field;
            if (isset($fChildsSide['label'])) {
                $cbFieldToshowsSide[$fieldSide] = $fChildsSide['label'];
            }
            //            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChildsSide['attr'])) {
                $cbFieldSideAttr[$fieldSide] = $fChildsSide['attr'];
            }
            //            if (isset($fChilds['link'])) {
            //                $cbFieldLink[$field] = $fChilds['link'];
            //            }
            //            if (isset($fChilds['format'])) {
            //                $cbFieldFormat[$field] = $fChilds['format'];
            //            }
            //            if (isset($fChilds['sum_rows'])) {
            //                $cbFieldSumrows[$field] = $fChilds['sum_rows'];
            //            }
        }

        foreach ($reportingSumSubject['mdlFields'] as $field => $fChilds) {
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $sFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        foreach ($reportingSumSubject['mdlFieldsSide'] as $fieldSide => $fChildsSide) {
            //            $cbFields[] = $field;
            if (isset($fChildsSide['label'])) {
                $sFieldToshowsSide[$fieldSide] = $fChildsSide['label'];
            }
            //            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChildsSide['attr'])) {
                $sFieldSideAttr[$fieldSide] = $fChildsSide['attr'];
            }
            //            if (isset($fChilds['link'])) {
            //                $cbFieldLink[$field] = $fChilds['link'];
            //            }
            //            if (isset($fChilds['format'])) {
            //                $cbFieldFormat[$field] = $fChilds['format'];
            //            }
            //            if (isset($fChilds['sum_rows'])) {
            //                $cbFieldSumrows[$field] = $fChilds['sum_rows'];
            //            }
        }


        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }


        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";
        }
        foreach ($sFieldToshows as $kolom => $kolomAlias) {
            if (!array_key_exists($kolom, $sFieldToshowsSide)) {
                $sHeader[$kolomAlias] = "class='bg-success text-center'";
            }
        }


        $cbHeaderSide['no'] = "class='bg-info text-center' rowspan='2'";
        foreach ($cbFieldToshowsSide as $kolom => $kolomAlias) {
            $cbHeaderSide[$kolomAlias] = "class='bg-info text-center' rowspan='2'";
        }
        if (isset($cbHeader) && (sizeof($cbHeader) > 0)) {
            $colspan = sizeof($tblTopHeadings) > 0 ? sizeof($tblTopHeadings) : 1;
            foreach ($cbHeader as $kolom => $kolomAlias) {
                $cbHeaderSide[$kolom] = "class='bg-info text-center' rowspan='1' colspan='$colspan'";
            }
        }


        $sHeaderSide['no'] = "class='bg-success text-center' rowspan='2'";
        foreach ($sFieldToshowsSide as $kolom => $kolomAlias) {
            $sHeaderSide[$kolomAlias] = "class='bg-success text-center' rowspan='2'";
        }
        if (isset($tblTopHeadings) && (sizeof($tblTopHeadings) > 0)) {
            $colspan = sizeof($tblTopHeadings) ? sizeof($tblTopHeadings) : 1;
            foreach ($cbHeader as $kolom => $kolomAlias) {
                $sHeaderSide[$kolom] = "class='bg-info text-center' rowspan='1' colspan='$colspan'";
            }
        }


        //        $pHeader['no'] = "class='bg-success text-center'";
        foreach ($pFieldToshows as $kolom => $kolomAlias) {
            $pHeader[$kolomAlias] = "class='bg-success text-center'";

        }


        $content = "";
        //         arrprint($cbHeader);
        //         arrprint($cbHeaderSide);
        // arrprint($stepNames);
        // arrprint($tblBodies);
        // arrPrint($this->session->login);
        // [jenis] => spv_penj, admin

        // region navigasi
        $segments = $this->uri->segment_array();
        $strAction = $cUrl = base_url() . implode("/", $segments);
        // arrPrint($cUrl);
        $urlAdd = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $ly->setOnClickTarget("$strAction");
        $bulanNow = dtimeNow("Y-m");

        $bulanPilihan = isset($_GET['date']) ? $_GET['date'] : $bulanNow;
        $tahunPilihan = isset($_GET['year']) ? $_GET['year'] : "";
        $content .= "<div class='row margin-bottom-10'>";
        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectTahun($tahunPilihan);
        $content .= "</div>";

        $content .= "<div class='col-md-3 pull-right'>";
        $content .= $ly->selectBulan($bulanPilihan);
        $content .= "</div>";
        $content .= "</div>";
        // endregion navigasi

        if (sizeof($tblBodies) > 0) {
            /* -----------------------------
             * SUMMARY
             * -----------------------------*/

            //region summary cabang branch
            $content .= "<div class='box box-info'>";
            $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumCabangTitle <small>$subTitle</small></h3></div>";
            $content .= "<div class='box-body'>";
            $content .= "<div class='table-responsive'>";
            $content .= "<table align='center' class='table table-condensed no-margin'>";

            //region header cabang
            $content .= "<tr>";
            foreach ($cbHeaderSide as $kAlias => $hAtt) {
                $content .= "<th $hAtt>$kAlias</th>";
            }
            $content .= "</tr>";

            if (isset($tblTopHeadings) && (sizeof($tblTopHeadings) > 0)) {
                $content .= "<tr>";
                foreach ($tblTopHeadings as $kolom => $kolomAlias) {
                    $content .= "<th rowspan='1' colspan='1' class='text-center bg bg-info'>$kolomAlias</th>";

                }
                $content .= "</tr>";
            }

            //endregion


            $cbNo = 0;
            $sumValue = array();
            foreach ($sumCabang as $nID => $nSpec) {
                arrPrintWebs($nSpec);
                $cbNo++;
                $content .= "<tr>";
                $content .= "<td class='text-right'>$cbNo</td>";

                foreach ($cbFieldToshowsSide as $kolom => $kolomAlias) {
                    $cAttr = $cbFieldSideAttr[$kolom];
                    $lValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : "-";

                    $content .= "<td $cAttr>";
                    $content .= $lValue;
                    $content .= "</td>";
                }


                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    foreach ($tblTopHeadings as $top_kolom => $xx) {
                        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
                            $cAttr = $cbFieldAttr[$kolom];

                            if (isset($cbFieldSumrows[$kolom])) {
                                if (!isset($sumValue[$top_kolom][$kolom])) {
                                    $sumValue[$top_kolom][$kolom] = 0;
                                }
                                $sumValue[$top_kolom][$kolom] += isset($sumCabangs[$nID][$top_kolom][$kolom]) ? $sumCabangs[$nID][$top_kolom][$kolom] : 0;
                            }
                            else {
                                $sumValue[$top_kolom][$kolom] = "-";
                                $sumValue[$top_kolom]['no'] = "-";
                            }

                            //region formater value
                            if (isset($cbFieldFormat[$kolom])) {
                                $fValue = $cbFieldFormat[$kolom]($kolom, isset($sumCabangs[$nID][$top_kolom][$kolom]) ? $sumCabangs[$nID][$top_kolom][$kolom] : 0);
                            }
                            else {
                                $fValue = isset($sumCabangs[$nID][$top_kolom][$kolom]) ? $sumCabangs[$nID][$top_kolom][$kolom] : 0;
                            }
                            //endregion

                            //region linker value
                            if (isset($cbFieldLink[$kolom])) {
                                $lValue = "<a href='" . $cbFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                            }
                            else {
                                $lValue = $fValue;
                            }
                            //endregion


                            $content .= "<td $cAttr>";
                            $content .= $lValue;
                            $content .= "</td>";
                        }
                    }
                }

                $content .= "</tr>";
            }


            //region footer cabang
            $content .= "<tr>";
            $content .= "<tr>";
            $content .= "<td class='text-right bg-info'>-</td>";
            foreach ($cbFieldToshowsSide as $kolom => $kolomAlias) {
                $content .= "<td class='text-right bg-info'>";
                $content .= "-";
                $content .= "</td>";
            }

            foreach ($sumValue as $sumTop => $sumTopSpec) {
                foreach ($sumTopSpec as $kolom => $footerValue) {
                    $content .= "<th class='text-right bg-info'>" . formatField('nilai_af', $footerValue) . "</th>";
                }
            }

            $content .= "</tr>";


            $content .= "</tr>";
            //endregion

            $content .= "</table>";
            $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
            //endregion


            $pakai_ini = 1;
            if ($pakai_ini == 1) {

                //region summary sales
                $content .= "<div class='box box-success'>";
                $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumSubjectTitle <small>$subTitle</small></h3></div>";
                $content .= "<div class='box-body'>";
                $content .= "<div class='table-responsive'>";
                $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='sallesman'>";

                //region header cabang
                $content .= "<tr>";
                foreach ($sHeaderSide as $kAlias => $hAtt) {
                    $content .= "<th $hAtt>$kAlias</th>";
                }
                $content .= "</tr>";

                if (isset($tblTopHeadings) && (sizeof($tblTopHeadings) > 0)) {
                    $content .= "<tr>";
                    foreach ($tblTopHeadings as $kolom => $kolomAlias) {
                        $content .= "<th rowspan='1' colspan='1' class='text-center bg bg-success'>$kolomAlias</th>";
                    }
                    $content .= "</tr>";
                }
                //endregion


                $cbNo = 0;
                $sumValue = array();
                $content .= "<tbody>";
                foreach ($sumSubject as $nID => $nSpec) {
                    $cbNo++;
                    $content .= "<tr>";
                    $content .= "<td class='text-right'>$cbNo</td>";

                    foreach ($sFieldToshowsSide as $kolom => $kolomAlias) {

                        $cAttr = $sFieldSideAttr[$kolom];
                        $lValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : "-";

                        $content .= "<td $cAttr>";
                        $content .= $lValue;
                        $content .= "</td>";
                    }


                    foreach ($tblTopHeadings as $top_kolom => $xx) {
                        foreach ($sFieldToshows as $kolom => $kolomAlias) {
                            if (!array_key_exists($kolom, $nSpec)) {

                                $cAttr = $sFieldAttr[$kolom];

                                if (isset($sFieldSumrows[$kolom])) {
                                    if (!isset($sumValue[$top_kolom][$kolom])) {
                                        $sumValue[$top_kolom][$kolom] = 0;
                                    }
                                    if (isset($sumSubjects[$nID][$top_kolom][$kolom])) {

                                        $sumValue[$top_kolom][$kolom] += $sumSubjects[$nID][$top_kolom][$kolom];
                                    }
                                    else {
                                        $sumValue[$top_kolom][$kolom] += 0;
                                    }
                                }
                                //                            else {
                                //                                $sumValue[$top_kolom][$kolom] = "-";
                                //                                $sumValue[$top_kolom]['no'] = "-";
                                //                            }

                                //region formater value
                                if (isset($sFieldFormat[$kolom])) {
                                    if (isset($sumSubjects[$nID][$top_kolom][$kolom])) {

                                        $fValue = $sFieldFormat[$kolom]($kolom, $sumSubjects[$nID][$top_kolom][$kolom]);
                                    }
                                    else {
                                        $fValue = $sFieldFormat[$kolom]($kolom, 0);;
                                    }
                                }
                                else {
                                    $fValue = isset($sumSubjects[$nID][$top_kolom][$kolom]) ? $sumSubjects[$nID][$top_kolom][$kolom] : 0;
                                }
                                //endregion

                                //region linker value
                                if (isset($sFieldLink[$kolom])) {
                                    $lValue = "<a href='" . $sFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
                                }
                                else {
                                    $lValue = $fValue;
                                }
                                //endregion


                                $content .= "<td $cAttr>";
                                $content .= $lValue;
                                $content .= "</td>";
                            }
                        }
                    }


                    $content .= "</tr>";
                }
                $content .= "</tbody>";

                //region footer cabang
                $content .= "<tfoot>";
                $content .= "<tr>";
                $content .= "<td class='text-right bg-success'>-</td>";
                foreach ($sFieldToshowsSide as $kolom => $kolomAlias) {
                    $content .= "<td class='text-right bg-success'>";
                    $content .= "-";
                    $content .= "</td>";
                }

                foreach ($sumValue as $sumTop => $sumTopSpec) {
                    foreach ($sumTopSpec as $kolom => $footerValue) {
                        $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
                    }
                }

                $content .= "</tr>";
                $content .= "</tfoot>";
                //endregion

                // arrPrint($sumValue);
                $content .= "</table>";
                $content .= "</div>";
                $content .= "</div>";
                $content .= "</div>";
                $content .= "<script>
                            $(document).ready( function(){
                                $('#sallesman').DataTable({
                                dom: 'lBfrtip',
                                    paging: false,
                                    searching: false,
                                    order: [[2,'desc']],
                                     buttons: [
                                                {
                                                    extend: 'print',
                                                    footer: true,
                                                    text: 'CETAK',
                                                },
                                                {
                                                    extend: 'copyHtml5',
                                                    footer: true,
                                                    text: 'COPY',
                                                },
                                                {
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    text: 'CSV',
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    text: 'EXCEL',
                                                },
                                                {
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    text: 'PDF',
                                                },
                                            ],
                                });                                    
                            });
                         </script>";
                //endregion

            }


            // //region summary produk
            // $content .= "<div class='box box-success'>";
            // $content .= "<div class='box-header with-border text-uppercase'><h3 class='box-title'>$sumObjectTitle <small>$subTitle</small></h3></div>";
            // $content .= "<div class='box-body'>";
            // $content .= "<div class='table-responsive'>";
            // $content .= "<table align='center' class='table table-condensed no-margin table-hover' id='produk'>";
            // //region header
            // $content .= "<thead>";
            // foreach ($pHeader as $kAlias => $hAtt) {
            //     $content .= "<th $hAtt>$kAlias</th>";
            // }
            // $content .= "</thead>";
            // //endregion
            // // arrPrint($cbFieldSumrows);
            // $cbNo = 0;
            // $sumValue = array();
            // $content .= "<tbody>";
            // foreach ($sumObject as $nID => $nSpec) {
            //     $cbNo++;
            //     $content .= "<tr>";
            //
            //     $content .= "<td class='text-right'>$cbNo</td>";
            //     foreach ($pFieldToshows as $kolom => $kolomAlias) {
            //         $cAttr = $pFieldAttr[$kolom];
            //
            //         //$cbFieldSumrows
            //         if (isset($pFieldSumrows[$kolom])) {
            //             if (!isset($sumValue[$kolom])) {
            //                 $sumValue[$kolom] = 0;
            //             }
            //             if (isset($nSpec[$kolom])) {
            //
            //                 $sumValue[$kolom] += $nSpec[$kolom];
            //             }
            //             else {
            //                 $sumValue[$kolom] += 0;
            //             }
            //         }
            //         else {
            //             $sumValue[$kolom] = "-";
            //             $sumValue['no'] = "-";
            //         }
            //
            //         //region formater value
            //         if (isset($pFieldFormat[$kolom])) {
            //             if (isset($nSpec[$kolom])) {
            //
            //                 $fValue = $pFieldFormat[$kolom]($kolom, $nSpec[$kolom]);
            //             }
            //             else {
            //                 $fValue = $pFieldFormat[$kolom]($kolom, 0);;
            //             }
            //         }
            //         else {
            //             $fValue = isset($nSpec[$kolom]) ? $nSpec[$kolom] : 0;
            //         }
            //         //endregion
            //
            //         //region linker value
            //         if (isset($pFieldLink[$kolom])) {
            //             $lValue = "<a href='" . $pFieldLink[$kolom] . "' target='_blank'>$fValue</a>";
            //         }
            //         else {
            //             $lValue = $fValue;
            //         }
            //         //endregion
            //
            //
            //         $content .= "<td $cAttr>";
            //         $content .= $lValue;
            //         $content .= "</td>";
            //     }
            //
            //     $content .= "</tr>";
            // }
            // $content .= "</tbody>";
            //
            // //region footer
            // $content .= "<tfoot>";
            // $content .= "<tr>";
            // foreach ($sumValue as $kolom => $kAlias) {
            //     $footerValue = $sumValue[$kolom];
            //     if (isset($pFieldSumrows[$kolom])) {
            //         $content .= "<th class='text-right bg-success'>" . formatField('nilai_af', $footerValue) . "</th>";
            //     }
            //     else {
            //         $content .= "<th class='text-right bg-success'>$footerValue</th>";
            //     }
            // }
            // $content .= "</tr>";
            // $content .= "</tfoot>";
            // //endregion
            // // arrPrint($sumValue);
            // $content .= "</table>";
            // $content .= "</div>";
            // $content .= "</div>";
            // $content .= "</div>";
            // $content .= "<script>
            //                 $(document).ready( function(){
            //                     $('#produk').DataTable({
            //                         paging: false,
            //                         searching: true,
            //                         order: [[8,'desc']]
            //                     });
            //                 });
            //              </script>";
            // //endregion

            /*=======================================*/

            //             $content .= "<div class='panel-body'>";
            //             $content .= "<div class='tab-content'>";
            //             // $ix =0;
            //             // arrPrint($stepNames);
            //             // arrPrint($tblBodies);
            //             // foreach ($stepNames as $stLink => $stLabel) {
            //             //     $ix ++;
            //             //     $tblBody = $tblBodies[$ix];
            //             // foreach ($tblBodies as $ix => $tblBody) {
            //             // arrPrint($tblBody);
            //             $datatables_r = "datatables";
            //             $tblHeading = isset($tblHeadings) ? $tblHeadings : "";
            //
            //             $content .= "<div class='tab-pane fade in active' id='tab'>";
            //             $content .= "<table class='table table-condensed table-bordered $datatables_r'>";
            //             if (isset($tblHeadings)) {
            //                 //region table heading
            //                 $content .= "<thead>";
            //                 $content .= "<tr>";
            //                 // foreach ($tblHeadings as $tblHeading) {
            //                 foreach ($tblHeading as $label => $attr) {
            //                     $content .= "<th align='center' $attr>";
            //                     $content .= $label;
            //                     $content .= "</th>";
            //                 }
            //                 // }
            //                 $content .= "</tr>";
            //                 $content .= "</thead>";
            //                 //endregion
            //             }
            //             // $specs = $tblBodies;
            //             //region table body
            //             $no = 0;
            //             $sumQty = 0;
            //             $sumVal = 0;
            //             $totalV = array();
            //             $content .= "<tbody>";
            //             foreach ($tblBodies as $oID => $specs) {
            //                 $content .= "<tr>";
            //                 foreach ($specs as $spec) {
            //                     $content .= "<td " . $spec['attr'] . ">";
            //                     $content .= $spec['value'];
            //                     $content .= "</td>";
            //                 }
            //                 $content .= "</tr>";
            //             }
            //             $content .= "</tbody>";
            //             //endregion
            //
            //             // region footer
            //             $content .= "<tfoot>";
            //             $content .= "<tr>";
            //
            //             //                foreach ($tblFooters as $footer => $fAttr) {
            //             //                    $content .= "<th $fAttr>";
            //             //                    $content .= $footer;
            //             //                    $content .= "</th>";
            //             //                }
            //             // arrPrintWebs($tblHeading);
            //             foreach ($tblHeading as $label => $attr) {
            //                 $content .= "<th align='center' $attr>";
            //                 $content .= "--";
            //                 $content .= "</th>";
            //             }
            //
            //             $content .= "</tr>";
            //             $content .= "</tfoot>";
            //             // endregion footer
            //             $content .= "</table>";
            //             $content .= "</div>";
            //
            //             if (sizeof($tblHeading) > 0) {
            //                 $content .= "<script>
            //                             $(document).ready( function(){
            //                                 var table = $('table.$datatables_r').DataTable({
            //                                     dom: 'lBfrtip',
            //                                     fixedHeader: true,
            //                                     lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
            //                                     pageLength: -1,
            //                                     buttons: [
            //                                                 {
            //                                                     extend: 'print',
            //                                                     footer: true,
            //                                                     text: 'CETAK',
            //                                                 },
            //                                                 {
            //                                                     extend: 'copyHtml5',
            //                                                     footer: true,
            //                                                     text: 'COPY',
            //                                                 },
            //                                                 {
            //                                                     extend: 'csvHtml5',
            //                                                     footer: true,
            //                                                     text: 'CSV',
            //                                                 },
            //                                                 {
            //                                                     extend: 'excelHtml5',
            //                                                     footer: true,
            //                                                     text: 'EXCEL',
            //                                                 },
            //                                                 {
            //                                                     extend: 'pdfHtml5',
            //                                                     footer: true,
            //                                                     text: 'PDF',
            //                                                 },
            //                                             ],
            //
            //         //                            buttons: [
            //         //                                        'copy', 'csv', 'excel', 'pdf', 'print'
            //         //                                    ],
            //         //                            buttons: [
            //         //                                        {
            //         //                                            extend: 'colvisGroup',
            //         //                                            text: 'Office info',
            //         //                                            show: [ 1, 2 ],
            //         //                                            hide: [ 3, 4, 5 ]
            //         //                                        },
            //         //                                        {
            //         //                                            extend: 'colvisGroup',
            //         //                                            text: 'HR info',
            //         //                                            show: [ 3, 4, 5 ],
            //         //                                            hide: [ 1, 2 ]
            //         //                                        },
            //         //                                        {
            //         //                                            extend: 'colvisGroup',
            //         //                                            text: 'Show all',
            //         //                                            show: ':hidden'
            //         //                                        }
            //         //                                    ]
            //
            //                                     footerCallback: function ( row, data, start, end, display ) {
            //                                                 var api = this.api(), data;
            //
            //                                                 // Remove the formatting to get integer data for summation
            //                                                 var intVal = function ( i ) {
            //                                                     return typeof i === 'string' ?
            //                                                         i.replace(/[$,()]/g, '')*1 :
            //                                                         typeof i === 'number' ?
            //                                                             i : 0;
            //                                                 };
            //
            //                                                 var arrayFooterTmp = $('tfoot>tr>th');
            //                                                 var dpageTotal = [];
            //
            //                                                 var arrBlackList = [5,6,7,8,9,10];
            // //                                                var arrColSpan = [0,1,2,3,4];
            //                                                 var arrSaldo = [10];
            //                                                 var arrSaldoQty = [9];
            //
            //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            //                                                     if(arrBlackList.includes(i)){
            //                                                         var id_n_index = parseFloat(i);
            //                                                         dpageTotal[id_n_index] = 0;
            //                                                         jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
            //
            //                                                             obj = intVal(obj)>0 ? intVal(obj) : intVal($(obj).html())>0 ? intVal($(obj).html()) : 0;
            //
            //                                                               dpageTotal[id_n_index] += intVal( obj );
            //
            // //                                                             dpageTotal[id_n_index] += intVal($(obj).html())==0 ? intVal(obj) : intVal($(obj).html()) ;
            // //                                                             console.log( $(obj).html()!=='undefine' ? $(obj).html() : obj );
            // //                                                             console.log( typeof obj );
            // //                                                             console.log( (intVal(obj)==0 ? $(obj).html() : 0) );
            //                                                         });
            //                                                         if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
            //                                                             $( api.column(id_n_index).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
            //                                                             );
            //                                                         }else{
            //                                                             $( api.column(id_n_index).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>0</div>\"
            //                                                             );
            //                                                         }
            //                                                     }
            //                                                 });
            //
            //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            //                                                     if(arrSaldo.includes(i)){
            //                                                         var saldo = 0;
            //                                                         var sales = 0;
            //                                                         var returns = 0;
            //                                                         sales = $( 'div', api.column(6).footer() ).html();
            //                                                             sales = sales.replace(/[$.]/g,'');
            //                                                         returns = $( 'div', api.column(8).footer() ).html()
            //                                                             returns = returns.replace(/[$.]/g,'');
            //                                                         saldo = intVal(sales) - intVal(returns)
            //                                                         if( !isNaN(saldo) && saldo > 0 || saldo < 0){
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
            //                                                             );
            //                                                         }else{
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>0</div>\"
            //                                                             );
            //                                                         }
            //                                                     }
            //                                                 });
            //
            //
            //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            //                                                     if(arrSaldoQty.includes(i)){
            //                                                         var saldo = 0;
            //                                                         var sales = 0;
            //                                                         var returns = 0;
            //                                                         sales = $( 'div', api.column(5).footer() ).html();
            //                                                             sales = sales.replace(/[$.]/g,'');
            //                                                         returns = $( 'div', api.column(7).footer() ).html()
            //                                                             returns = returns.replace(/[$.]/g,'');
            //                                                         saldo = intVal(sales) - intVal(returns)
            //                                                         if( !isNaN(saldo) && saldo > 0 || saldo < 0){
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>\"+addCommas(saldo)+\"</div>\"
            //                                                             );
            //                                                         }else{
            //                                                             $( api.column(i).footer() ).html(
            //                                                                 \"<div class='text-right text-primary text-bold'>0</div>\"
            //                                                             );
            //                                                         }
            //                                                     }
            //                                                 });
            //
            // //                                                 var keys = Object.keys(arrColSpan);
            // //                                                 var last = keys[keys.length-1];
            // //                                                 jQuery.each(arrayFooterTmp, function(i,d){
            // //                                                     if(arrColSpan.includes(i)){
            // //                                                         if(i==last){
            // //                                                             $( api.column(i).footer()).attr('colspan', 5);
            // //                                                             $( api.column(i).footer() ).html(
            // //                                                                 \"<div class='text-right text-muted text-bold'>  S U M M A R Y  </div>\"
            // //                                                             );
            // //                                                         }
            // //                                                         else{
            // //                                                             $( api.column(i).footer() ).remove();
            // //                                                         }
            // //                                                     }
            // //                                                 });
            //
            //         // Total over all pages
            //         //                                        var total2=0;
            //         //                                        jQuery.each( $(api.column(2).data()), function(i, obj){
            //         //                                            total2 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var total3=0;
            //         //                                        jQuery.each( $(api.column(3).data()), function(i, obj){
            //         //                                            total3 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var total4=0;
            //         //                                        jQuery.each( $(api.column(4).data()), function(i, obj){
            //         //                                            total4 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var total5=0;
            //         //                                        jQuery.each( $(api.column(5).data()), function(i, obj){
            //         //                                            total5 += intVal( $('span', obj).html() );
            //         //                                        });
            //
            //         // Total over this page
            //         //                                        pageTotal2 = api
            //         //                                            .column( 2, { page: 'current'} )
            //         //                                            .data()
            //         //                                            .reduce( function (a, b) {
            //         //                                                return intVal(a) + intVal(b);
            //         //                                            }, 0 );
            //
            //         //                                        var pageTotal2=0;
            //         //                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal2 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var pageTotal3=0;
            //         //                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal3 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var pageTotal4=0;
            //         //                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal4 += intVal( $('span', obj).html() );
            //         //                                        });
            //         //
            //         //                                        var pageTotal5=0;
            //         //                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
            //         //                                            pageTotal5 += intVal( $('span', obj).html() );
            //         //                                        });
            //
            //                                                 // Update footer
            //         //                                        $( api.column( 2 ).footer() ).html(
            //         //                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
            //         //                                        );
            //
            //         //                                        $( api.column( 3 ).footer() ).html(
            //         //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
            //         //                                        );
            //
            //         //                                        $( api.column( 4 ).footer() ).html(
            //         //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
            //         //                                        );
            //
            //         //                                        $( api.column( 5 ).footer() ).html(
            //         //                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
            //         //                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
            //         //                                        );
            //
            //                                             }
            //                                         });
            //
            //                                     });
            //
            //                                     $('.table-responsive').floatingScroll();
            //                             </script>";
            //             }
            //
            //             // } //  bodies
            //
            //
            //             $content .= "</div>"; // tab cntent
            //             $content .= "</div>"; // panel body


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
        ));
        $p->render();
        break;

    case "index_rev":
        // region navigasi

        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $navNtm = "<div class='col-md-6'>";
        //        arrPrint($navBtn);
        foreach ($navBtn as $mode => $navTmp) {

            $link = $navTmp['action'];
            $navNtm .= "<span class='col-md-3' style='padding: 2px'><a href=\"$link\" class='btn btn-danger'>" . $navTmp['label'] . "</a></span>";
        }
        $navNtm .= "</div>";

        $cotentList = "";
        //        arrPrint($itemsMain);
        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (isset($itemsMain['1'])) {
                $title = $itemsMain['1']['title'];
                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div class='panel-body'>";

                $cotentList .= "<table class='table table-bordered'>";
                $cotentList .= "<tr>";
                $cotentList .= "<td rowspan='3' class='text text-center'>No</td>";
                foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='3' class='text text-center'>$alias</td>";
                }
                $colspan2 = sizeof($itemsPeriode) * 2;
                $cotentList .= "<td colspan='$colspan2' class='text text-center'>Penjualan(netto)</td>";
                $cotentList .= "</tr>";
                $cotentList .= "<tr>";
                foreach ($headerFields['1']['header2'] as $kol => $alias) {
                    $cotentList .= "<td colspan='2' class='text text-center'>$alias </td>";
                }

                $cotentList .= "</tr>";
                if (sizeof($itemsPeriode) > 0) {
                    $colspan2 = sizeof($itemsPeriode);
                    $cotentList .= "<tr>";
                    foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                        foreach ($itemsPeriode as $periode) {
                            $cotentList .= "<td class='text text-center'>$periode</td>";
                        }
                    }
                    $cotentList .= "</tr>";
                }
                $rr = 0;
                if (sizeof($itemsMain[1]['mainData']) > 0) {
                    $itemValues = $itemsMain[1]['mainValues'];
                    //                    arrPrint($itemValues);
                    foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                        $i = 0;
                        foreach ($itemsMain[1]['mainData'][$kol] as $kID => $kData) {
                            $i++;
                            $cotentList .= "<tr>";
                            $cotentList .= "<td>$i</td>";
                            foreach ($kData as $kk_key => $labelName) {
                                $cotentList .= "<td>$labelName</td>";
                            }
                            foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $val = isset($itemValues[$periode][$kID][$kol]) ? $itemValues[$periode][$kID][$kol] : 0;
                                    $cotentList .= "<td class='text text-center'>" . formatField($kol, $val) . "</td>";
                                }
                            }

                            $cotentList .= "</tr>";
                        }
                    }
                }
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
            }
            //endregion
            //region conten chil1
            if (isset($itemsMain[2]) && sizeof($itemsMain['2']['mainData']) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $title = $itemsMain['2']['title'];
                $colspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";
                $rowspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";

                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text-bold'><h3> $title</h3></div>";
                $cotentList .= "<div class='panel-body'>";

                $cotentList .= "<table class='table table-bordered'>";
                //region header
                $cotentList .= "<tr>";
                $cotentList .= "<td class='' rowspan='$rowspan'>No.</td>";
                foreach ($headerFields['2']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='$rowspan' class='text text-center'>$alias</td>";
                }
                if (sizeof($itemsMain['2']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['2']['header2']);
                    foreach ($itemsMain['2']['mainIndex2'] as $Cid => $alias) {
                        $cotentList .= "<td rowspan='' colspan='$colspan2' class='text text-center'>$alias**</td>";
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['2']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    foreach ($itemsMain['2']['mainIndex2'] as $parentAlias) {

                        foreach ($headerFields['2']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<td colspan='$colspan3'>$ixLabel *</td>";
                        }
                    }
                    //                    }

                    $cotentList .= "</tr>";

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['2']['mainIndex2'] as $parentAlias) {
                            foreach ($headerFields[2]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<td class='text text-center'>$periode</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                //endregion

                //region data
                //                arrPrint($itemsMain['2']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['mainValues']);
                $i = 0;
                foreach ($itemsMain['2']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['2']['mainValues'][$pID];

                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";

                    foreach ($headerFields['2']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($itemsMain['2']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan='' '>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan='' '>" . formatField($kk, $val) . "</td>";
                            }
                        }
                    }


                    $cotentList .= "</tr>";
                }
                //                arrPrint($itemsPeriode);
                //                arrPrint($dataValues);

                //endregion
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
            }
            //endregion

            //region conten chil2
            //            cekhere("halo");
            //            arrPrint($headerFields['3']['header2']);
            if (isset($itemsMain[3]) && sizeof($itemsMain[3]) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $title = $itemsMain['3']['title'];
                $colspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                $rowspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";

                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div class='panel-body'>";

                $cotentList .= "<table class='table table-bordered'>";
                //region header
                $cotentList .= "<tr>";
                $cotentList .= "<td class='' rowspan='3'>No.</td>";
                foreach ($headerFields['3']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='3' class='text text-center'>$alias*</td>";
                }
                if (sizeof($itemsMain['3']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode);
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        $cotentList .= "<td rowspan='' colspan='$colspan2' class='text text-center'>$alias</td>";
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['3']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {

                        foreach ($headerFields['3']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<td colspan='$colspan3'>$ixLabel *</td>";
                        }
                    }
                    //                    }

                    $cotentList .= "</tr>";

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {
                            foreach ($headerFields[3]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<td class='text text-center'>$periode</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                //endregion

                //region data
                //                arrPrint($itemsMain['2']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['mainValues']);
                $i = 0;
                foreach ($itemsMain['3']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['3']['mainValues'][$pID];

                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";

                    foreach ($headerFields['3']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan='' '>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan='' '>" . formatField($kk, $val) . "</td>";
                            }
                        }
                    }


                    $cotentList .= "</tr>";
                }
                //                arrPrint($itemsPeriode);
                //                arrPrint($dataValues);

                //endregion
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
            }
            //endregion

        }
        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $cotentList,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn" => $navNtm,
        ));
        $p->render();

        break;

    case "index":
        // region navigasi
        //arrPrint($navBtn);
        arrPrint($indexKey);
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $navNtm = "<div class='col-md-6'>";
        //        arrPrint($navBtn);
        foreach ($navBtn as $mode => $navTmp) {

            $link = $navTmp['action'];
            $navNtm .= "<span class='col-md-3' style='padding: 2px'><a href=\"$link\" class='btn btn-danger'>" . $navTmp['label'] . "</a></span>";
        }
        $navNtm .= "</div>";
        //        arrPrint($items['2']);
        $cotentList = "";
        //        arrPrint($headerFields);
        //region conten index utama
        if (isset($items['1'])) {
            $cotentList .= "<div>";
            $cotentList .= "<div class='panel'>";

            $cotentList .= "<div class='panel-header'> reserv for title</div>";
            $cotentList .= "<div class='panel-body'>";

            $cotentList .= "<table class='table table-bordered'>";
            $cotentList .= "<tr>";
            $cotentList .= "<td>No</td>";
            foreach ($headerFields['1']['alias'] as $kol => $alias) {
                $cotentList .= "<td>$alias</td>";
            }
            $cotentList .= "</tr>";
            $rr = 0;
            foreach ($items['1'] as $contenIndex) {
                $rr++;
                $cotentList .= "<tr>";
                $cotentList .= "<td>$rr</td>";
                foreach ($headerFields['1']['alias'] as $kol => $alias) {
                    $cotentList .= "<td>" . formatField($kol, $contenIndex[$kol]) . "</td>";
                }
                $cotentList .= "</tr>";
            }
            $cotentList .= "</table>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
        }
        //endregion
        //region child1
        if (isset($items['2'])) {
            $cotentList .= "<div>";
            $cotentList .= "<div class='panel'>";

            $cotentList .= "<div class='panel-header'> reserv for title</div>";
            $cotentList .= "<div class='panel-body'>";

            $cotentList .= "<table class='table table-bordered'>";
            $cotentList .= "<tr>";
            $cotentList .= "<td>No</td>";
            $header = isset($headerFields['2']['masterHeader']) ? array_merge($headerFields['2']['masterHeader'], $headerFields['2']['alias']) : $headerFields['2']['alias'];
            //            if(isset())
            //            arrPrint($header);
            foreach ($header as $kol => $alias) {
                $cotentList .= "<td>$alias</td>";
            }
            $cotentList .= "</tr>";
            $rr = 0;
            foreach ($items['1'] as $ky => $contenIndex) {
                $rr++;
                $keySelect = $indexKey['1'];

                $rowSpan = sizeof($items['2'][$contenIndex[$keySelect]]) + 1;
                $cotentList .= "<tr>";
                $cotentList .= "<td rowspan='$rowSpan' colspan=''>$rr</td>";
                $cotentList .= "<td rowspan='$rowSpan'>" . $contenIndex[$keySelect] . "</td>";
                $cotentList .= "</tr>";
                foreach ($items['2'][$contenIndex[$keySelect]] as $tmp) {
                    $cotentList .= "<tr>";
                    foreach ($headerFields['2']['alias'] as $kol => $alias) {
                        $cotentList .= "<td colsfpan='2'>" . formatField($kol, $tmp[$kol]) . "</td>";
                    }
                    $cotentList .= "</tr>";
                }


            }
            $cotentList .= "</table>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
        }
        //endregion
        //region child 3
        if (isset($items['3'])) {
            $cotentList .= "<div>";
            $cotentList .= "<div class='panel'>";

            $cotentList .= "<div class='panel-header'> reserv for title</div>";
            $cotentList .= "<div class='panel-body'>";

            $cotentList .= "<table class='table table-bordered'>";
            $cotentList .= "<tr>";
            $cotentList .= "<td>No</td>";
            $header = isset($headerFields['3']['masterHeader']) ? array_merge($headerFields['3']['masterHeader'], $headerFields['3']['alias']) : $headerFields['3']['alias'];
            //            if(isset())
            //            arrPrint($header);
            foreach ($header as $kol => $alias) {
                $cotentList .= "<td>$alias</td>";
            }
            $cotentList .= "</tr>";
            $rr = 0;
            foreach ($items['1'] as $ky => $contenIndex) {
                $rr++;
                $keySelect = $indexKey['1'];

                $rowSpan = sizeof($items['3'][$contenIndex[$keySelect]]) + 1;
                $cotentList .= "<tr>";
                $cotentList .= "<td rowspan='$rowSpan' colspan=''>$rr</td>";
                $cotentList .= "<td rowspan='$rowSpan'>" . $contenIndex[$keySelect] . "</td>";
                $cotentList .= "</tr>";
                foreach ($items['3'][$contenIndex[$keySelect]] as $tmp) {
                    $cotentList .= "<tr>";
                    foreach ($headerFields['3']['alias'] as $kol => $alias) {
                        $cotentList .= "<td colsfpan='2'>" . formatField($kol, $tmp[$kol]) . "</td>";
                    }
                    $cotentList .= "</tr>";
                }


            }
            $cotentList .= "</table>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
        }

        //endregion

        //        foreach($headerFields as $i =>$ii){
        //            $cotentList.="<div>";
        //            $cotentList .="<table class='table table-bordered'>";
        //            $cotentList .="<tr>";
        //            $cotentList .="<td>No</td>";
        //            foreach($ii['alias'] as $key =>$label){
        //                $cotentList .="<td>$label</td>";
        //            }
        //            $cotentList .="</tr>";
        //
        //            foreach($items[$i] as$cID => $dataValue){
        //                $cotentList .="<tr>";
        //                foreach($ii['alias'] as $key =>$label){
        //                    $kk++;
        //                    $cotentList .="<td>$kk</td>";
        //                    $cotentList .="<td>".$dataValue[$key]."</td>";
        //                }
        //                $cotentList .="</tr>";
        //            }
        //
        //
        //            $cotentList.="</table>";
        //            $cotentList.="</div>";
        ////            arrPrint($ii);
        //        }foreach($headerFields as $i =>$ii){
        //            $cotentList.="<div>";
        //            $cotentList .="<table class='table table-bordered'>";
        //            $cotentList .="<tr>";
        //            $cotentList .="<td>No</td>";
        //            foreach($ii['alias'] as $key =>$label){
        //                $cotentList .="<td>$label</td>";
        //            }
        //            $cotentList .="</tr>";
        //
        //            foreach($items[$i] as$cID => $dataValue){
        //                $cotentList .="<tr>";
        //                foreach($ii['alias'] as $key =>$label){
        //                    $kk++;
        //                    $cotentList .="<td>$kk</td>";
        //                    $cotentList .="<td>".$dataValue[$key]."</td>";
        //                }
        //                $cotentList .="</tr>";
        //            }
        //
        //
        //            $cotentList.="</table>";
        //            $cotentList.="</div>";
        ////            arrPrint($ii);
        //        }


        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $cotentList,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn" => $navNtm,
        ));
        $p->render();
        break;

    case "viewYearly":
        // region navigasi

        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        //        $date1=isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";
        //        $date2=isset($_GET['date2']) ? "&date2=" . $_GET['date2'] : "th";
        //        $nav2 = isset($_GET['nav2']) ? "&nav2=" . $_GET['nav2'] : "th";
        $navNtm = "<div class='col-md-12 text-left'>";
        $navNtm .= "<ul class='pagination'>";
        foreach ($navBtn as $mode => $navTmp) {
            $link = $navTmp['action'];
            $navTitle = $navTmp['label'];
            //            $navNtm .= "<span class='col-md-3' style='padding: 2px'><a href=\"$link\" class='btn btn-danger'>" . $navTmp['label'] . "</a></span>";
            $navNtm .= "<li><a href='$link' class=\"text-white bg-light-blue\" data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>$navTitle</a></li>";
        }

        $navNtm .= "</ul>";
        $navNtm .= "</div>";


        //region navigation 2
        $selectedField = isset($_GET['nav2']) ? $_GET['nav2'] : "th";
        $navBtn = "<h2>Pilih Periode</h2>";
        $navBtn .= "<select id='periode' class='form-control'>";
        foreach ($periode as $key => $pLabel) {
            $selected = $key == $selectedField ? "selected" : "";
            $navLink = $thisPage . "/$method?nav2=$key$date1$date2";
            $navBtn .= "<option value ='$key' $selected onclick=\"location.href='$navLink'\">$pLabel</option>";
        }
        $navBtn .= "</select>";
        //endregion
        $cotentList = "";

        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (isset($itemsMain['1'])) {
                $title = $itemsMain['1']['title'];
                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div class='panel-body'>";

                $cotentList .= "<table class='table table-bordered'>";
                $cotentList .= "<tr>";
                $cotentList .= "<td rowspan='3' class='text text-center'>No</td>";
                foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='3' class='text text-center'>$alias</td>";
                }
                $colspan2 = sizeof($itemsPeriode) * 2;
                $cotentList .= "<td colspan='$colspan2' class='text text-center'>Penjualan(netto)</td>";
                //                $cotentList .= "<td colspan='$colspan2' class='text text-center'>konsolidasi</td>";

                $cotentList .= "</tr>";
                $cotentList .= "<tr>";
                foreach ($headerFields['1']['header2'] as $kol => $alias) {
                    $cotentList .= "<td colspan='2' class='text text-center'>$alias </td>";
                }
                $cotentList .= "</tr>";
                if (sizeof($itemsPeriode) > 0) {
                    $colspan2 = sizeof($itemsPeriode);
                    $cotentList .= "<tr>";
                    foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                        foreach ($itemsPeriode as $periode) {
                            $cotentList .= "<td class='text text-center'>$periode</td>";
                        }
                    }
                    $cotentList .= "</tr>";
                }
                //                arrPrint($itemsMain[1]['sumFooter']);
                $rr = 0;
                if (sizeof($itemsMain[1]['mainData']) > 0) {
                    $itemValues = $itemsMain[1]['mainValues'];
                    //                    arrPrint($itemValues);
                    foreach ($headerFields['1']['headerField'] as $kol => $alias) {
                        $i = 0;
                        if (isset($itemsMain[1]['mainData'][$kol])) {
                            foreach ($itemsMain[1]['mainData'][$kol] as $kID => $kData) {
                                $i++;
                                $cotentList .= "<tr>";
                                $cotentList .= "<td>$i</td>";
                                foreach ($kData as $kk_key => $labelName) {
                                    $cotentList .= "<td>$labelName</td>";
                                }
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        $val = isset($itemValues[$periode][$kID][$kol]) ? $itemValues[$periode][$kID][$kol] : 0;
                                        $cotentList .= "<td class='text-right'>" . formatField($kol, $val) . "</td>";
                                    }
                                }
                                $cotentList .= "</tr>";
                            }
                            if (isset($itemsMain[1]['sumFooter'])) {
                                //                                arrPrint($itemsMain[1]['sumfield']);
                                arrPrint(sizeof($headerFields['1']['headerField']));
                                $colspanf = sizeof($headerFields['1']['headerField']) + 1;
                                $footerValue = $itemsMain[1]['sumFooter'];
                                $cotentList .= "<tr>";
                                //                                foreach ($itemsMain[1]['sumfield']['footer'] as $kol => $valAlias) {
                                $cotentList .= "<td colspan='$colspanf'>Total</td>";
                                foreach ($headerFields[1]['header2'] as $kol => $valAlias) {
                                    foreach ($itemsPeriode as $periode) {
                                        $val = isset($footerValue[$kol][$periode]) ? $footerValue[$kol][$periode] : 0;
                                        $cotentList .= "<td class='text-right text-bold'>" . formatField($kol, $val) . "</td>";
                                    }
                                }
                                //                                }
                                $cotentList .= "</tr>";
                            }
                        }

                    }

                }
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
            }
            //endregion

            //region conten chil1
            if (isset($itemsMain[2]) && sizeof($itemsMain['2']['mainData']) > 0) {
                //                arrPrint($itemsMain['2']['mainIndex2']);
                $header2Merger = $itemsMain['2']['mainIndex2'] + array("subtotal" => "konsolidasi");
                //                arrPrint($header2Merger);
                //                $header = array_merge($headerFields['2']['headerField']);

                $title = $itemsMain['2']['title'];
                $colspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";
                $rowspan = isset($headerFields['2']['header2']) ? sizeof($headerFields['2']['header2']) + 1 : "";

                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text-bold'><h3> $title</h3></div>";
                $cotentList .= "<div class='panel-body'>";

                $cotentList .= "<table class='table table-bordered'>";
                //region header
                $cotentList .= "<tr>";
                $cotentList .= "<td class='' rowspan='$rowspan'>No.</td>";
                foreach ($headerFields['2']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='$rowspan' class='text text-center'>$alias</td>";
                }
                if (sizeof($itemsMain['2']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode) * sizeof($headerFields['2']['header2']);
                    foreach ($header2Merger as $Cid => $alias) {
                        $cotentList .= "<td rowspan='' colspan='$colspan2' class='text text-center'>$alias</td>";

                    }
                    //                    $cotentList .= "<td rowspan='' colspan='$colspan2' class='text text-center'>Total</td>";
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['2']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    foreach ($header2Merger as $parentAlias) {
                        foreach ($headerFields['2']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<td colspan='$colspan3'>$ixLabel </td>";
                        }
                    }

                    $cotentList .= "</tr>";

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($header2Merger as $parentAlias) {
                            foreach ($headerFields[2]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<td class='text text-center'>$periode</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                //endregion

                //region data
                //                arrPrint($itemsMain['2']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['sumFooter']);

                $i = 0;
                foreach ($itemsMain['2']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['2']['mainValues'][$pID];
                    //arrPrint($dataValues);
                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";

                    foreach ($headerFields['2']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan='' '>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan='' '>" . formatField($kk, $val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";

                }
                //subtotal bawah
                if (sizeof($itemsMain['2']['sumFooter']) > 0) {
                    //                    arrPrint($itemsMain['2']['sumFooter']);
                    $sumValues = $itemsMain['2']['sumFooter'];
                    $fColspan = sizeof($headerFields['2']['headerField']) + 1;
                    $cotentList .= "<tr>";
                    $cotentList .= "<td colspan='$fColspan' class='text-bold'>Total</td>";

                    foreach ($header2Merger as $Cid => $alias) {
                        foreach ($headerFields['2']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan='' '>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($sumValues[$Cid][$kk][$periode]) ? $sumValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td class='text-bold'>" . formatField($kk, $val) . "</td>";
                            }
                        }
                    }
                    $cotentList .= "</tr>";
                }


                //endregion

                //endregion
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
            }
            //endregion

            //region conten chil2
            if (isset($itemsMain[3]) && sizeof($itemsMain[3]) > 0) {
                //                arrPrint($headerFields['2']['header2']);
                $title = $itemsMain['3']['title'];
                $colspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";
                $rowspan = isset($headerFields['3']['header2']) ? sizeof($headerFields['3']['header2']) + 1 : "";

                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text-bold'><h3>$title</h3></div>";
                $cotentList .= "<div class='panel-body'>";

                $cotentList .= "<table class='table table-bordered'>";
                //region header
                $cotentList .= "<tr>";
                $cotentList .= "<td class='' rowspan='3'>No.</td>";
                foreach ($headerFields['3']['headerField'] as $kol => $alias) {
                    $cotentList .= "<td rowspan='3' class='text text-center'>$alias*</td>";
                }
                if (sizeof($itemsMain['3']['mainIndex2'])) {
                    $colspan2 = sizeof($itemsPeriode);
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        $cotentList .= "<td rowspan='' colspan='$colspan2' class='text text-center'>$alias</td>";
                    }
                }
                $cotentList .= "</tr>";
                if (sizeof($headerFields['3']['header2']) > 0) {
                    $cotentList .= "<tr>";
                    $colspan3 = sizeof($itemsPeriode);
                    //                    foreach($headerFields['2']['headerField'] as $kol => $alias){
                    foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {

                        foreach ($headerFields['3']['header2'] as $ix => $ixLabel) {
                            $cotentList .= "<td colspan='$colspan3'>$ixLabel *</td>";
                        }
                    }
                    //                    }

                    $cotentList .= "</tr>";

                    if (sizeof($itemsPeriode) > 0) {
                        $colspan2 = sizeof($itemsPeriode);
                        $cotentList .= "<tr>";
                        foreach ($itemsMain['3']['mainIndex2'] as $parentAlias) {
                            foreach ($headerFields[3]['header2'] as $kol => $valAlias) {
                                foreach ($itemsPeriode as $periode) {
                                    $cotentList .= "<td class='text text-center'>$periode</td>";
                                }
                            }
                        }
                        $cotentList .= "</tr>";
                    }
                }
                //endregion

                //region data
                //                arrPrint($itemsMain['2']['mainData']);
                //                arrPrint($itemsMain['2']['mainIndex2']);
                //                arrPrint($itemsMain['2']['mainValues']);
                $i = 0;
                foreach ($itemsMain['3']['mainData'] as $pID => $pidData) {
                    $i++;
                    $dataValues = $itemsMain['3']['mainValues'][$pID];

                    $cotentList .= "<tr>";
                    $cotentList .= "<td>$i</td>";

                    foreach ($headerFields['3']['headerField'] as $kol => $aliasX) {
                        $cotentList .= "<td>" . $pidData[$kol] . "</td>";
                    }
                    foreach ($itemsMain['3']['mainIndex2'] as $Cid => $alias) {
                        foreach ($headerFields['3']['header2'] as $kk => $kLabel) {
                            foreach ($itemsPeriode as $periode) {
                                //                                $cotentList .= "<td rowspan='' colspan='' '>$pID*1*$Cid * $kk*$periode</td>";
                                $val = isset($dataValues[$Cid][$kk][$periode]) ? $dataValues[$Cid][$kk][$periode] : 0;
                                $cotentList .= "<td rowspan='' colspan='' '>" . formatField($kk, $val) . "</td>";
                            }
                        }
                    }


                    $cotentList .= "</tr>";
                }
                //                arrPrint($itemsPeriode);
                //                arrPrint($dataValues);

                //endregion
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
            }
            //endregion

        }
        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $cotentList,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn" => $navNtm,
            "navigasi" => $navBtn,
            "url" => $thisPage . "/$method",
        ));
        $p->render();

        break;

    case "indexMontly":
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $navNtm = "<div class='col-md-6'>";
        //        arrPrint($navBtn);
        foreach ($navBtn as $mode => $navTmp) {

            $link = $navTmp['action'];
            $navNtm .= "<span class='col-md-3' style='padding: 2px'><a href=\"$link\" class='btn btn-danger'>" . $navTmp['label'] . "</a></span>";
        }
        $navNtm .= "</div>";
        $cotentList = "";
        if (sizeof($items) > 0) {
            arrPrint(headerFields2);
            $cotentList .= "<div>";
            $cotentList .= "<div class='panel'>";

            $cotentList .= "<div class='panel-header text text-bold'><h3>$title</h3></div>";
            $cotentList .= "<div class='panel-body'>";
            $cotentList .= "<table class='table table-bordered'>";
            $cotentList .= "<tr>";
            $cotentList .= "<td rowspan='2'>No.</td>";
            foreach ($headerFields as $k => $tpList) {
                $cotentList .= "<td rowspan='2' valign='bottom' class='text-muted'>";
                $cotentList .= "<span class='pull-right'>bulan</span><span class='fa fa-angle-double-right'></span>";
                $cotentList .= "<span class='fa fa-angle-double-down'>$tpList</span>";
                $cotentList .= "</td>";

            }
            $cotentList .= "</tr>";
            $cotentList .= "<tr>";
            foreach ($itemsPeriode as $mID => $midLabel) {
                $cotentList .= "<td rowspan=''>$mID</td>";
            }
            $cotentList .= "</tr>";


            $cotentList .= "</table>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";
            $cotentList .= "</div>";

        }
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $cotentList,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn" => $navNtm,
        ));
        $p->render();
        cekHitam("hitammm");
        break;

    case "viewDetail":

        break;

    case "viewDetailItem":
        // region navigasi
        $content = "";
        $p = New Layout("$title", "$subTitle", "application/template/reportsMongo.html");
        $method = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        //        $selectedField = $navGate['nav2'];
        //        $date1 = isset($navGate['date1']) ? "&date1=" . $navGate['date1'] : date("Y-m-d");
        //        $date2 = isset($navGate['date2']) ? "&date2=" . $navGate['date2'] : date("Y-m-d");
        //        $navKey = isset($navGate['nav2']) ? "&nav2=" . $navGate['nav2'] : "&nav2=cabang";
        //
        //
        //        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        //        $navNtm = "<div class='col-md-12 text-left'>";
        //        $navNtm .= "<ul class='pagination'>";
        //        foreach ($navBtn as $mode => $navTmp) {
        //            $link = $navTmp['action'] . "?$navKey$date1$date2";
        //            $navTitle = $navTmp['label'];
        ////            $navNtm .= "<span class='col-md-3' style='padding: 2px'><a href=\"$link\" class='btn btn-danger'>" . $navTmp['label'] . "</a></span>";
        //            $navNtm .= "<li><a href='$link' class=\"text-white bg-light-blue\" data-toogle='tooltip' data-placement='bottom' title='lap penjualan $navTitle'><span class='fa fa-align-justify'></span>$navTitle</a></li>";
        //        }
        //
        //        $navNtm .= "</ul>";
        //        $navNtm .= "</div>";
        //endregion

        //region navigation 2

        //        $navBtn = "<h3>Pilih Periode</h3>";
        //        $navBtn .= "<select id='periode' onchange=\"period_url()\" class='form-control'>";
        //        foreach ($periode as $key => $pLabel) {
        //            $selected = $key == $selectedField ? "selected" : "";
        ////arrPrint($pLabel);
        ////            $navLink = $thisPage . "/$method?nav2=$key$date1$date2";
        //            $navLink = base_url() . $this->uri->segment(1) . "/" . $pLabel['method'] . "/" . $this->uri->segment(3) . "?nav2=$key$date1$date2";
        ////            $navLink = "$thisPage/".$pLabel['method']."/".$this->uri->segment(3)."?nav2=$key$date1$date2";
        ////            $navLink = "https://google.com";
        //            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">" . $pLabel['label'] . "</option>";
        ////            $navBtn .= "<option value ='$key' $selected url=\"$navLink\">".$navLink."</option>";
        //        }
        //        $navBtn .= "</select>";
        //        $navBtn .= "
        //        <script>
        //            var period_url = function(){
        //                var url = $('option:selected', $('#periode')).attr('url');
        //                window.location.href=url
        //            }
        //        </script>
        //        ";
        //endregion
        $cotentList = "";

        if (sizeof($itemsMain) > 0) {
            //region conten index utama
            if (sizeof($itemsMain) > 0) {
                //                $title = $title
                //                $sbTitle = $itemsMain['1']['subtitle'];
                $cotentList .= "<div>";
                $cotentList .= "<div class='panel'>";

                $cotentList .= "<div class='panel-header text text-bold'>$title</div>";
                $cotentList .= "<div><span>$subTitle</span></div>";
                $cotentList .= "<div class='panel-body table-responsive'>";

                $cotentList .= "<table class='table table-bordered table-striped table-hover' id='itemsMain'>";
                $cotentList .= "<thead>";
                $cotentList .= "<tr>";
                $cotentList .= "<th rowspan='3' class='text text-center'>No</th>";
                foreach ($headerFields as $kol => $alias) {
                    $cotentList .= "<th rowspan='3' class='text text-center'>$alias</th>";
                }

                $cotentList .= "</tr>";

                $cotentList .= "</thead>";
                $cotentList .= "<tbody>";
                //                arrPrint($itemsMain);
                $rr = 0;
                $cotentListFoot = "";
                $cotentListFoot = "";
                if (sizeof($itemsMain) > 0) {
                    //                    $itemValues = $itemsMain;
                    //                    arrPrint($itemValues);
                    $i = 0;
                    foreach ($itemsPeriode as $kol => $alias) {
                        $i++;
                        $cotentList .= "<tr>";
                        $cotentList .= "<td>$i</td>";
                        $cotentList .= "<td>$alias</td>";
                        foreach ($indexHeader as $k => $alias) {
                            $val = isset($itemsMain[$kol][$k]) ? number_format($itemsMain[$kol][$k]) : 0;
                            $cotentList .= "<td class='text-right'>$val</td>";

                        }
                    }
                    $cotentList .= "</tr>";
                    //$cotentListFoot
                    $ii = sizeof($headerFields) + 1;
                    $j = 1;
                    for ($j; $j <= $ii; $j++) {
                        $cotentListFoot = "<td>-</td>";
                    }
                    //                    cekHitam($ii);
                }


                $cotentList .= "</tbody>";
                $cotentList .= "<tfoot>";
                $cotentList .= "$cotentListFoot";
                $cotentList .= "</tfoot>";
                $cotentList .= "</table>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";
                $cotentList .= "</div>";

                $cotentList .= "<script>
                    $('#itemsMain').DataTable({
                        iDisplayLength: 50,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        footerCallback: function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[$,x]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            jQuery.each(data[0], function(i,bs){
                                total = api
                                    .column( i )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );
                                pageTotal = api
                                    .column( i, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                    }, 0 );

                                    if(parseFloat(pageTotal)>0 && i>0){
                                        $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        $( api.column( i ).footer() ).addClass('text-right');
                                    }
                            })
                        }
                    });                    
                </script>";
            }
            //endregion


        }
        $p->addTags(array(
            //            "jenisTr" => $jenisTr . $str_group,
            // "trName"=>$trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $cotentList,
            "profile_name" => $this->session->login['nama'],
            // "add_link"           => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_btn" => $navNtm,
            "navigasi" => $navBtn,
            "url" => $thisPage . "/$method",
            "date1" => $navGate['date1'],
            "date2" => $navGate['date2'],

        ));
        $p->render();
        break;

    case "viewPembelian":
        // $thn = '2021';
        // $jml_bln = 4;

        // $getDate = "$thn-4";
        $regMonthYear = $getDate;

        /* ------------------------------
         * navigasi
         * ------------------------------*/
        $link_lap_vendor = base_url() . "ActivityReport/viewPembelian";
        $link_lap_produk = base_url() . "ActivityReport/viewPembelianProduk/$thn?date=$regMonthYear";
        $btn_data = "";
        $btn_data .= "<div class='col-md-4 pull-right'>";
        $btn_data .= "<div class='btn-group'>";
        // $btn_data .= "<button type='button' class='btn btn-danger text-uppercase' onclick=\"location.href='$link_lap_vendor'\">by vendor</button>";
        // $btn_data .= "<button type='button' class='btn btn-warning text-uppercase' onclick=\"location.href='$link_lap_produk'\">by produk</button>";
        $btn_data .= "<button type='button' class='btn btn-warning text-uppercase' onclick=\"document.getElementById('result').src = '$link_lap_produk'\"><i class='fa fa-download'></i> pembelian bulanan per produk $thn</button>";
        $btn_data .= "</div>";
        $btn_data .= "</div>";

        $datePicker = "<div class='row'>";
        $datePicker .= "<div class='col-md-2'>";
        //region date picker
        $datePicker .= "<form method='get'>";
        $datePicker .= "<div class='input-group date' id='datepicker'  data-date='$regMonthYear' data-date-format='yyyy'>";
        $datePicker .= "<div class='input-group-addon'>";
        $datePicker .= "<span class='add-on'><i class='fa fa-calendar' data-toggle='tooltip' data-placement='right' title='pilih bulan'></i></span>";
        $datePicker .= "</div>";
        $datePicker .= "<input type='text' autocomplete-off readonly class='form-control text-center' name='date' value='$regMonthYear'>";
        $datePicker .= "<span class='input-group-btn'>";
        $datePicker .= "<button type='submit' id='date_submit' style='display: none' class='btn btn-primary'><i class='fa fa-send-o'></i></button>";
        $datePicker .= "</span>";
        $datePicker .= "</div>";
        $datePicker .= "</form>";
        //endregion
        $datePicker .= "</div>";

        // if (ipadd() == "202.65.117.72") {
        $datePicker .= $btn_data;
        // }
        // else {
        //
        // }


        $datePicker .= "</div>";


        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $headers = "";
        //region header satu
        $headers .= "<tr>";
        $headers .= "<th class='bg-info v-al' rowspan='2'>no</th>";
        $headers .= "<th class='bg-info' rowspan='2'>nama</th>";
        // $headers .= "<th colspan='12'>2021</th>";
        for ($i = 1; $i <= $jml_bln; $i++) {
            $i_f = $i*1<10?"0$i":$i;
            $headers .= "<th class='bg-info' colspan='3'>$thn-$i_f</th>";

        }
        $headers .= "<th class='bg-info' colspan='3'>sub total</th>";
        $headers .= "</tr>";
        //endregion

        //region header dua
        $headers .= "<tr class='bg-info'>";
        for ($i = 1; $i <= $jml_bln; $i++) {

            $headers .= "<th>beli</th>";
            $headers .= "<th>return</th>";
            $headers .= "<th>netto</th>";
        }

        $headers .= "<th>beli</th>";
        $headers .= "<th>return</th>";
        $headers .= "<th>netto</th>";
        $headers .= "</tr>";
        //endregion

        $bodies = "";
        $no = 0;
        foreach ($src_vendor as $item) {
            $no++;
            $vend_id = $item->id;
            $vend_nama = $item->nama;
            $bodies .= "<tr>";
            $bodies .= "<td class='text-right'>$no</td>";
            $bodies .= "<td title='$vend_id'>$vend_nama</td>";

            /* -----------------------
             * data utama
             * ----------------------*/
            $kolom = "suppliers_id";
            $sub_nilai_p = 0;
            $sub_nilai_pr = 0;
            $sub_nilai_pn = 0;
            for ($i = 1; $i <= $jml_bln; $i++) {
                $nilai_p = isset($src_pembelians[$vend_id][$thn][$i]->nilai_af) ? $src_pembelians[$vend_id][$thn][$i]->nilai_af : 0;
                $nilai_pr = isset($src_pembelian_returns[$vend_id][$thn][$i]->nilai_af) ? $src_pembelian_returns[$vend_id][$thn][$i]->nilai_af : 0;
                $nilai_pn = $nilai_p - $nilai_pr;

                $nilai_p_f = formatField('debet', $nilai_p);
                $nilai_pr_f = formatField('debet', $nilai_pr);
                $nilai_pn_f = formatField('debet', $nilai_pn);

                $head_modal = strtoupper("$vend_nama ($thn/$i)");
                $link_tr = base_url() . "ActivityReport/viewDetile/$kolom/$vend_id/467/$thn/$i";
                $target = modalDialogBtn("$head_modal", $link_tr);
                $nilai_p_l = "<a href='javascript:void(0);' onclick=\"$target\">$nilai_p_f</a>";
                /* -----------------------
                 * pembelian
                 * ----------------------*/
                $bodies .= "<td data-order='$nilai_p'>$nilai_p_l</td>";

                $link_tr = base_url() . "ActivityReport/viewDetile/$kolom/$vend_id/967/$thn/$i";
                $target = modalDialogBtn("$head_modal", $link_tr);
                $nilai_pr_l = "<a href='javascript:void(0);' onclick=\"$target\">$nilai_pr_f</a>";
                /* -----------------------
                 * pembelian return
                 * ----------------------*/
                $bodies .= "<td data-order='$nilai_pr'>$nilai_pr_l</td>";

                /* -----------------------
                 * pembelian netto
                 * ----------------------*/
                $bodies .= "<td class='bg-success' data-order='$nilai_pn'>$nilai_pn_f</td>";

                $sub_nilai_p += $nilai_p;
                $sub_nilai_pr += $nilai_pr;
                $sub_nilai_pn += $nilai_pn;

                if (!isset($total[$thn][$i]['pembelian'])) {
                    $total[$thn][$i]['pembelian'] = 0;
                }
                $total[$thn][$i]['pembelian'] += $nilai_p;
                if (!isset($total[$thn][$i]['pembelian_return'])) {
                    $total[$thn][$i]['pembelian_return'] = 0;
                }
                $total[$thn][$i]['pembelian_return'] += $nilai_pr;
                if (!isset($total[$thn][$i]['pembelian_netto'])) {
                    $total[$thn][$i]['pembelian_netto'] = 0;
                }
                $total[$thn][$i]['pembelian_netto'] += $nilai_pn;
            }

            /* -----------------------
             * summary kanan
             * ----------------------*/
            $sub_nilai_p_f = formatField('debet', $sub_nilai_p);
            $sub_nilai_pr_f = formatField('debet', $sub_nilai_pr);
            $sub_nilai_pn_f = formatField('debet', $sub_nilai_pn);
            /* -----------------------
             * pembelian
             * ----------------------*/
            $bodies .= "<td data-order='$sub_nilai_p'>$sub_nilai_p_f</td>";
            /* -----------------------
             * pembelian return
             * ----------------------*/
            $bodies .= "<td data-order='$sub_nilai_pr'>$sub_nilai_pr_f</td>";
            /* -----------------------
             * pembelian netto
             * ----------------------*/
            $bodies .= "<td data-order='$sub_nilai_pn' class='bg-success'>$sub_nilai_pn_f</td>";

            if (!isset($total[$thn]['sub']['pembelian'])) {
                $total[$thn]['sub']['pembelian'] = 0;
            }
            $total[$thn]['sub']['pembelian'] += $sub_nilai_p;
            if (!isset($total[$thn]['sub']['pembelian_return'])) {
                $total[$thn]['sub']['pembelian_return'] = 0;
            }
            $total[$thn]['sub']['pembelian_return'] += $sub_nilai_pr;
            if (!isset($total[$thn]['sub']['pembelian_netto'])) {
                $total[$thn]['sub']['pembelian_netto'] = 0;
            }
            $total[$thn]['sub']['pembelian_netto'] += $sub_nilai_pn;

            $bodies .= "</tr>";
        }

        // arrPrint($total);
        $footies = "";
        //region footer
        $footies .= "<tr class='bg-info'>";
        $footies .= "<th colspan='2' class='text-uppercase text-right'>total</th>";

        foreach ($total as $thn => $bln_items) {
            foreach ($bln_items as $bln_item) {

                $tpembelian = $bln_item['pembelian'];
                $tpembelian_return = $bln_item['pembelian_return'];
                $tpembelian_netto = $bln_item['pembelian_netto'];

                $total_pembelian_sub_f = formatField('debet', $tpembelian);
                $total_pembelian_return_sub_f = formatField('debet', $tpembelian_return);
                $total_pembelian_netto_sub_f = formatField('debet', $tpembelian_netto);

                $footies .= "<th>$total_pembelian_sub_f</th>";
                $footies .= "<th>$total_pembelian_return_sub_f</th>";
                $footies .= "<th class='bg-success'>$total_pembelian_netto_sub_f</th>";
            }
        }

        $footies .= "</tr>";
        //endregion

        $str_tbl = "";
        // $str_tbl .= "<div class='border-cek'>$thn</div>";
        $str_tbl .= "<div class='table-responsive dataTabel'>";
        $str_tbl .= "<table id='dataTabel' class='table display no-padding'>";
        $str_tbl .= "<thead>";
        $str_tbl .= $headers;
        $str_tbl .= "</thead>";
        $str_tbl .= "<tbody>";
        $str_tbl .= $bodies;
        $str_tbl .= "</tbody>";
        $str_tbl .= "<tfoot>";
        $str_tbl .= $footies;
        $str_tbl .= "</tfoot>";
        $str_tbl .= "</table>";
        $str_tbl .= "<script>
                    $(document).ready( function(){
                        var table = $('table#dataTabel').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            paging: false,
                            processing: true,
                            buttons: [
                                        {
                                            extend: 'print',
                                            footer: true,
                                            text: 'CETAK',
                                        },
                                        {
                                            extend: 'copyHtml5',
                                            footer: true,
                                            text: 'COPY',
                                        },
                                        {
                                            extend: 'csvHtml5',
                                            footer: true,
                                            text: 'CSV',
                                        },
                                        {
                                            extend: 'excelHtml5',
                                            footer: true,
                                            text: 'EXCEL',
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            footer: true,
                                            text: 'PDF',
                                        },
                                    ],
                            });

                            $('.table-responsive.dataTabel').floatingScroll();
                                $('.table-responsive.dataTabel').scroll( delay_v2(function(){ $('table#dataTabel').DataTable().fixedHeader.adjust(); }, 200) );
                            });

                    </script>";

        $str_tbl .= "</div>";

        $content = "";
        $content .= $datePicker;
        $content .= "<hr style='margin: 5px;'>";
        $content .= $str_tbl;

        $script_bottom = "<script>
                                $(document).ready(function () {
                                    $('#datepicker').datepicker({
                                        format: \"yyyy-mm\",
                                        viewMode: \"years\",
                                        minViewMode: \"years\",
                                        maxMonth: new Date(2021, 1),
                                    })
                                        .change(dateChanged)
                                        .on('changeDate', dateChanged);
                            
                                function dateChanged(ev) {
                                    setTimeout(function () {
                                        $('#date_submit').click();
                                    }, 100);
                                }
                                });
                        </script>";

        $p->addTags(
            array(
                // "jenisTr" => $jenisTr,
                // "trName"=>$trName,
                "menu_left" => callMenuLeft(),
                // "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $content,
                "script_bottom" => $script_bottom,
                // "profile_name" => $this->session->login['nama'],
                // // "add_link"           => $addLinkStr,
                // "newTrTarget" => isset($addLink['link']) ? $addLink['link'] : "javascript:void(0)",
                // "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            )
        );

        $p->render();
        break;

    case "viewPembelianProduk":
        // $thn = '2021';
        // $jml_bln = 4;

        // $getDate = "$thn-4";
        $regMonthYear = $getDate;

        /* ------------------------------
         * navigasi
         * ------------------------------*/
        $link_lap_produk = base_url() . "ActivityReport/viewPembelian/produk";
        $btn_data = "";
        $btn_data .= "<div class='col-md-2 pull-right'>";
        $btn_data .= "<div class='btn-group'>";
        $btn_data .= "<button type='button' class='btn btn-danger text-uppercase'>by vrndor</button>";
        $btn_data .= "<button type='button' class='btn btn-warning text-uppercase' onclick=\"location.href='$link_lap_produk'\">by produk</button>";
        $btn_data .= "</div>";
        $btn_data .= "</div>";

        $datePicker = "<div class='row'>";
        $datePicker .= "<div class='col-md-2'>";
        //region date picker
        $datePicker .= "<form method='get'>";
        $datePicker .= "<div class='input-group date' id='datepicker'  data-date='$regMonthYear' data-date-format='yyyy'>";
        $datePicker .= "<div class='input-group-addon'>";
        $datePicker .= "<span class='add-on'><i class='fa fa-calendar' data-toggle='tooltip' data-placement='right' title='pilih bulan'></i></span>";
        $datePicker .= "</div>";
        $datePicker .= "<input type='text' autocomplete-off readonly class='form-control text-center' name='date' value='$regMonthYear'>";
        $datePicker .= "<span class='input-group-btn'>";
        $datePicker .= "<button type='submit' id='date_submit' style='display: none' class='btn btn-primary'><i class='fa fa-send-o'></i></button>";
        $datePicker .= "</span>";
        $datePicker .= "</div>";
        $datePicker .= "</form>";
        //endregion
        $datePicker .= "</div>";

        if (ipadd() == "202.65.117.72") {
            $datePicker .= $btn_data;
        }
        else {

        }


        $datePicker .= "</div>";


        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $headers = "";
        //region header satu
        $headers .= "<tr>";
        $headers .= "<th class='bg-info v-al' rowspan='2'>no</th>";
        $headers .= "<th class='bg-info' rowspan='2'>nama</th>";
        // $headers .= "<th colspan='12'>2021</th>";
        for ($i = 1; $i <= $jml_bln; $i++) {
            $headers .= "<th class='bg-info' colspan='6'>$thn-$i</th>";

        }
        $headers .= "<th class='bg-info' colspan='6'>sub total</th>";
        $headers .= "</tr>";
        //endregion

        //region header dua
        $headers .= "<tr class='bg-info'>";
        for ($i = 1; $i <= $jml_bln; $i++) {

            $headers .= "<th>qty beli</th>";
            $headers .= "<th>qty return</th>";
            $headers .= "<th>qty netto</th>";
            $headers .= "<th>beli</th>";
            $headers .= "<th>return</th>";
            $headers .= "<th>netto</th>";
        }

        $headers .= "<th>qty beli</th>";
        $headers .= "<th>qty return</th>";
        $headers .= "<th>qty netto</th>";
        $headers .= "<th>beli</th>";
        $headers .= "<th>return</th>";
        $headers .= "<th>netto</th>";
        $headers .= "</tr>";
        //endregion

        $bodies = "";
        $no = 0;
        foreach ($src_vendor as $item) {
            $no++;
            $vend_id = $item->id;
            $vend_nama = $item->nama;
            $bodies .= "<tr>";
            $bodies .= "<td class='text-right'>$no</td>";
            $bodies .= "<td title='$vend_id'>$vend_nama</td>";

            /* -----------------------
             * data utama
             * ----------------------*/
            $kolom = "suppliers_id";
            $sub_qty_p = 0;
            $sub_qty_pr = 0;
            $sub_qty_pn = 0;
            $sub_nilai_p = 0;
            $sub_nilai_pr = 0;
            $sub_nilai_pn = 0;
            for ($i = 1; $i <= $jml_bln; $i++) {
                $qty_p = isset($src_pembelians[$vend_id][$thn][$i]->unit_in) ? $src_pembelians[$vend_id][$thn][$i]->unit_in : 0;
                $qty_pr = isset($src_pembelians[$vend_id][$thn][$i]->unit_ot) ? $src_pembelians[$vend_id][$thn][$i]->unit_ot : 0;
                $qty_pn = $qty_p - $qty_pr;

                $nilai_p = isset($src_pembelians[$vend_id][$thn][$i]->nilai_in) ? $src_pembelians[$vend_id][$thn][$i]->nilai_in : 0;
                $nilai_pr = isset($src_pembelians[$vend_id][$thn][$i]->nilai_ot) ? $src_pembelians[$vend_id][$thn][$i]->nilai_ot : 0;
                $nilai_pn = $nilai_p - $nilai_pr;

                $qty_p_f = formatField('debet', $qty_p);
                $qty_pr_f = formatField('debet', $qty_pr);
                $qty_pn_f = formatField('debet', $qty_pn);

                $nilai_p_f = formatField('debet', $nilai_p);
                $nilai_pr_f = formatField('debet', $nilai_pr);
                $nilai_pn_f = formatField('debet', $nilai_pn);

                $bodies .= "<td>$qty_p_f</td>";
                $bodies .= "<td>$qty_pr_f</td>";
                $bodies .= "<td class='bg-warning'>$qty_pn_f</td>";

                $head_modal = strtoupper("$vend_nama ($thn/$i)");
                $link_tr = base_url() . "ActivityReport/viewDetile/$kolom/$vend_id/467/$thn/$i";
                $target = modalDialogBtn("$head_modal", $link_tr);
                $nilai_p_l = "<a href='javascript:void(0);' onclick=\"$target\">$nilai_p_f</a>";
                /* -----------------------
                 * pembelian
                 * ----------------------*/
                $bodies .= "<td>$nilai_p_l</td>";

                $link_tr = base_url() . "ActivityReport/viewDetile/$kolom/$vend_id/967/$thn/$i";
                $target = modalDialogBtn("$head_modal", $link_tr);
                $nilai_pr_l = "<a href='javascript:void(0);' onclick=\"$target\">$nilai_pr_f</a>";
                /* -----------------------
                 * pembelian return
                 * ----------------------*/
                $bodies .= "<td>$nilai_pr_l</td>";

                /* -----------------------
                 * pembelian netto
                 * ----------------------*/
                $bodies .= "<td class='bg-success'>$nilai_pn_f</td>";

                $sub_qty_p += $qty_p;
                $sub_qty_pr += $qty_pr;
                $sub_qty_pn += $qty_pn;

                $sub_nilai_p += $nilai_p;
                $sub_nilai_pr += $nilai_pr;
                $sub_nilai_pn += $nilai_pn;

                if (!isset($total[$thn][$i]['pembelian'])) {
                    $total[$thn][$i]['pembelian'] = 0;
                }
                $total[$thn][$i]['pembelian'] += $nilai_p;

                if (!isset($total[$thn][$i]['pembelian_return'])) {
                    $total[$thn][$i]['pembelian_return'] = 0;
                }
                $total[$thn][$i]['pembelian_return'] += $nilai_pr;

                if (!isset($total[$thn][$i]['pembelian_netto'])) {
                    $total[$thn][$i]['pembelian_netto'] = 0;
                }
                $total[$thn][$i]['pembelian_netto'] += $nilai_pn;
            }

            /* -----------------------
             * summary kanan
             * ----------------------*/
            $sub_qty_p_f = formatField('debet', $sub_qty_p);
            $sub_qty_pr_f = formatField('debet', $sub_qty_pr);
            $sub_qty_pn_f = formatField('debet', $sub_qty_pn);

            $sub_nilai_p_f = formatField('debet', $sub_nilai_p);
            $sub_nilai_pr_f = formatField('debet', $sub_nilai_pr);
            $sub_nilai_pn_f = formatField('debet', $sub_nilai_pn);

            $bodies .= "<td>$sub_qty_p_f</td>";
            $bodies .= "<td>$sub_qty_pr_f</td>";
            $bodies .= "<td>$sub_qty_pn_f</td>";

            /* -----------------------
             * pembelian
             * ----------------------*/
            $bodies .= "<td>$sub_nilai_p_f</td>";
            /* -----------------------
             * pembelian return
             * ----------------------*/
            $bodies .= "<td>$sub_nilai_pr_f</td>";
            /* -----------------------
             * pembelian netto
             * ----------------------*/
            $bodies .= "<td class='bg-success'>$sub_nilai_pn_f</td>";
            // -------------------------------------------------------------------------------------------------------

            if (!isset($total[$thn]['sub']['pembelian'])) {
                $total[$thn]['sub']['pembelian'] = 0;
            }
            $total[$thn]['sub']['pembelian'] += $sub_nilai_p;

            if (!isset($total[$thn]['sub']['pembelian_return'])) {
                $total[$thn]['sub']['pembelian_return'] = 0;
            }
            $total[$thn]['sub']['pembelian_return'] += $sub_nilai_pr;

            if (!isset($total[$thn]['sub']['pembelian_netto'])) {
                $total[$thn]['sub']['pembelian_netto'] = 0;
            }
            $total[$thn]['sub']['pembelian_netto'] += $sub_nilai_pn;

            $bodies .= "</tr>";
        }

        // arrPrint($total);
        $footies = "";
        //region footer
        $footies .= "<tr class='bg-info'>";
        $footies .= "<th colspan='2' class='text-uppercase text-right'>total</th>";

        foreach ($total as $thn => $bln_items) {
            foreach ($bln_items as $bln_item) {

                $tpembelian = $bln_item['pembelian'];
                $tpembelian_return = $bln_item['pembelian_return'];
                $tpembelian_netto = $bln_item['pembelian_netto'];

                $total_pembelian_sub_f = formatField('debet', $tpembelian);
                $total_pembelian_return_sub_f = formatField('debet', $tpembelian_return);
                $total_pembelian_netto_sub_f = formatField('debet', $tpembelian_netto);

                $footies .= "<th></th>";
                $footies .= "<th></th>";
                $footies .= "<th></th>";
                $footies .= "<th>$total_pembelian_sub_f</th>";
                $footies .= "<th>$total_pembelian_return_sub_f</th>";
                $footies .= "<th class='bg-success'>$total_pembelian_netto_sub_f</th>";
            }
        }

        $footies .= "</tr>";
        //endregion

        $str_tbl = "";
        // $str_tbl .= "<div class='border-cek'>$thn</div>";
        $str_tbl .= "<div class='table-responsive dataTabel'>";
        $str_tbl .= "<table id='dataTabel' class='table table-condensed table-bordered table-striped table-hover'>";
        $str_tbl .= "<thead>";
        $str_tbl .= $headers;
        $str_tbl .= "</thead>";
        $str_tbl .= "<tbody>";
        $str_tbl .= $bodies;
        $str_tbl .= "</tbody>";
        $str_tbl .= "<tfoot>";
        $str_tbl .= $footies;
        $str_tbl .= "</tfoot>";
        $str_tbl .= "</table>";
        $str_tbl .= "<script>
                    $(document).ready( function(){
                        var table = $('table#dataTabel').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            paging: false,
                            buttons: [
                                        {
                                            extend: 'print',
                                            footer: true,
                                            text: 'CETAK',
                                        },
                                        {
                                            extend: 'copyHtml5',
                                            footer: true,
                                            text: 'COPY',
                                        },
                                        {
                                            extend: 'csvHtml5',
                                            footer: true,
                                            text: 'CSV',
                                        },
                                        {
                                            extend: 'excelHtml5',
                                            footer: true,
                                            text: 'EXCEL',
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            footer: true,
                                            text: 'PDF',
                                        },
                                    ],
                                });
                                });

                                $('.table-responsive.dataTabel').floatingScroll();
                                $('.table-responsive.dataTabel').scroll( delay_v2(function(){ $('table#dataTabel').DataTable().fixedHeader.adjust(); }, 200) );
                            });
                    </script>";

        $str_tbl .= "</div>";

        $content = "";
        $content .= $datePicker;
        $content .= "<hr style='margin: 5px;'>";
        $content .= $str_tbl;

        $p->addTags(
            array(
                // "jenisTr" => $jenisTr,
                // "trName"=>$trName,
                "menu_left" => callMenuLeft(),
                // "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $content,
                // "profile_name" => $this->session->login['nama'],
                // // "add_link"           => $addLinkStr,
                // "newTrTarget" => isset($addLink['link']) ? $addLink['link'] : "javascript:void(0)",
                // "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            )
        );

        $p->render();
        break;

    case "recap_ext1":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $link_span = base_url() . "Addons/ViewDetails/item_report";
        //region main conten
        $content = "";
        // if (sizeof($stepNames) > 0) {
        //     $content .= "<ul class='nav nav-tabs'>";
        //     foreach ($stepNames as $stID => $stLabel) {
        //         $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
        //         $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
        //         $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";
        //
        //         $content .= "<li class='nav-item'>";
        //         if (strcmp($stID, $selectedStep) == 0) {
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
        //             $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
        //             $content .= "<span class='fa fa-adjust'></span> ";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //         }
        //         else {
        //             $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //
        //
        //         }
        //
        //
        //         $content .= "</li>";
        //
        //     }
        //
        //     $content .= "</ul>";
        // }


        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            $tahunPilihan = isset($_GET['year']) ? "&year=" . $_GET['year'] : "";
            //bagian customer
            $content .= "<div ><h3>Laporan Sales Order bulanan per customer $thPilihan</h3></div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            // $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReportt('main_1_$timeLabel')\"> Export/Download to Excel </span> </div>";
            // arrPrintPink($_SERVER['PATH_INFO']);
            $link_excel = base_url() . $_SERVER['PATH_INFO'] ."?ayo=1".$tahunPilihan;
            $content .= "<div><button type='button' class='btn btn-warning' onclick=\"btn_result('$link_excel');\">Export/Download to Excel</button></div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1_$timeLabel' align='center' rules='all' class='table table-condensed table-bordered table-hover' sjtyle='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<td align='center' rowspan='2' class='text-muted'>$pLabel</td>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    // $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    // $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<td bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";

            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted text-bold'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";

            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";

                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;
                    // cekLime($prevYear);
                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                        //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td align='' >$val</td>";
                    // "viewdetailPrev"
                }
                // $sumTotalPrev['prev']=$totalPrev;

                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }
                        $aa = "<span href='" . $link_span . "/$pID/$oID/$j?params=" . blobEncode($addLinkParam) . "' name='qtips' style=\"text-align:left; display:block;\" class=\"text-hover\">" . formatField("harga", $val) . "</span>";
                        $addLinkParam['customers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";

                        $content .= $histLink['open'] . $aa . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                // $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['982'] + $totalH['1982']);
                $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['1982']);
                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            // -------------------------
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";


            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            //             $content .= "\n<script>
            //
            // const isHTML = (str) => {
            //   const fragment = document.createRange().createContextualFragment(str);
            //
            //   // remove all non text nodes from fragment
            //   fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
            //
            //   // if there is textContent, then not a pure HTML
            //   return !(fragment.textContent || '').trim();
            // }
            //
            //                         $('#main').DataTable({
            //                             dom: 'lBfrtip',
            //                             fixedHeader: true,
            //                             iDisplayLength: -1,
            //                             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            //                             buttons: [
            //                                 {
            //                                     text: 'Export to Excel',
            //                                     action: function ( e, dt, node, config ) {
            //                                         tableToExcel('main', 'id', '$sbTitle.xls')
            //                                     }
            //                                 }
            //                             ],
            //                             footerCallback: function ( row, data, start, end, display ) {
            //                                 var api = this.api(), data;
            //                                 // Remove the formatting to get integer data for summation
            //                                 var intVal = function ( i ) {
            //                                     return typeof i === 'string' ?
            //                                         i.replace(/[$,x]/g, '')*1 :
            //                                         typeof i === 'number' ?
            //                                             i : 0;
            //                                 };
            //
            //                                     for(rw in data){
            //                                         jQuery.each(data[rw], function(i,bs){
            //                                             if(i*1>1){
            //                                             total = api
            //                                                 .column(i)
            //                                                 .data()
            //                                                 .reduce( function (a, b) {
            //                                                     if(isHTML(b)){
            //                                                         b = $(b).html()
            //                                                         if(isHTML(b)){
            //                                                             b = $(b).html()
            //                                                         }
            //                                                     }
            //                                                     return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                                 },0);
            // //                                                console.log('total', total);
            //
            //                                     pageTotal = api
            //                                         .column( i, { page: 'current'} )
            //                                         .data()
            //                                         .reduce( function (a, b) {
            //                                             if(isHTML(b)){
            //                                                 b = $(b).html()
            //                                                 if(isHTML(b)){
            //                                                     b = $(b).html()
            //                                                 }
            //                                             }
            //                                             return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                         }, 0 );
            //
            //                                         if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                             $( api.column( i ).footer() ).addClass('text-right');
            //                                             $( api.column( i ).footer() ).addClass('text-bold');
            //                                         }
            //                                         else if(parseFloat(pageTotal)==0){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                         }
            //                                         else{
            //                                             $( api.column(1).footer() ).html('T O T A L');
            //                                         }
            //
            //                                             }
            //                                         })
            //                                     }
            //
            // //                                jQuery.each(data[0], function(i,bs){
            // //                                    total = api
            // //                                        .column( i )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //
            // //                                        }, 0 );
            // //                                    pageTotal = api
            // //                                        .column( i, { page: 'current'} )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //                                        }, 0 );
            // //
            // //                                        if(parseFloat(pageTotal)>0 && i>0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                            $( api.column( i ).footer() ).addClass('text-right');
            // //                                            $( api.column( i ).footer() ).addClass('text-bold');
            // //                                        }
            // //                                        else if(parseFloat(pageTotal)==0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                        }else{
            // //
            // //                                        }
            // //                                })
            //                             }
            //                         });
            //                         $(\".table-responsive.main\").scroll(function () {
            //                             setTimeout(function () {
            //                                 $('#main').DataTable().fixedHeader.adjust();
            //                             }, 400);
            //                         });
            //                     </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }
        echo $content;
echo "<script>
function fnExcelReportt(id_table_tujuan)
{
        var tab_text=\"<table border='2px'><tr bgcolor='#87AFC6'>\";
        var textRange; var j=0;
        tab = document.getElementById(id_table_tujuan); // id of table

        for(j = 0 ; j < tab.rows.length ; j++)
        {
            tab_text=tab_text+tab.rows[j].innerHTML+\"</tr>\";
            //tab_text=tab_text+\"</tr>\";
        }

        tab_text=tab_text+\"</table>\";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, \"\");//remove if u want links in your table
        tab_text= tab_text.replace(/<a[^>]*>|<\/a>/g, \"\");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,\"\"); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, \"\"); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf(\"MSIE \");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open(\"txt/html\",\"replace\");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa=txtArea1.document.execCommand(\"SaveAs\",true,\"Say Thanks to Sumit.xls\");
        }
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        
        // console.log(encodeURIComponent(tab_text))

        return (sa);
    }
</script>";

        break;
    case "recap_ext1_new":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $link_span = base_url() . "Addons/ViewDetails/item_report";
        //region main conten
        $content = "";
        // if (sizeof($stepNames) > 0) {
        //     $content .= "<ul class='nav nav-tabs'>";
        //     foreach ($stepNames as $stID => $stLabel) {
        //         $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
        //         $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
        //         $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";
        //
        //         $content .= "<li class='nav-item'>";
        //         if (strcmp($stID, $selectedStep) == 0) {
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
        //             $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
        //             $content .= "<span class='fa fa-adjust'></span> ";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //         }
        //         else {
        //             $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //
        //
        //         }
        //
        //
        //         $content .= "</li>";
        //
        //     }
        //
        //     $content .= "</ul>";
        // }


        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div ><h3>Laporan Sales Order bulanan per salesman $thPilihan</h3></div>";

             $content .= "<div class='clearfix'>&nbsp;</div>";
             $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main_1')\"> Export/Download to Excel </span> </div>";
             $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1' align='center' class='table table-condensed table-bordered table-hover' sjtyle='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<th rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</th>";

            $content .= "<th rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</th>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<th align='center' rowspan='2' class='text-muted'>$pLabel</th>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<th align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    // $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    // $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</th>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<th bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</th>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";


            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted text-bold'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            // arrPrint($names);
            // matiHere();
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";
                // arrPrint($prevTimes);
                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;

                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td line='".__LINE__."' align='' >$val</td>";

                }
                // $sumTotalPrev['prev']=$totalPrev;
                $totalH = array();
                foreach ($times as $pID => $pName) {
//                    cekKuning(":: $pID :: $pName ::");
                    foreach ($headerList as $j => $al) {
//                        cekKuning("$j => $al");
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }
                        $aa = "<span href='" . $link_span . "/$pID/$oID/$j?params=" . blobEncode($addLinkParam) . "' name='qtips' style=\"text-align:left; display:block;\" class=\"text-hover\">" . formatField("harga", $val) . "</span>";

//                        $addLinkParam['customers_id'] = $oID;
                        $addLinkParam['jenis'] = $j;
                        $addLinkParam_00['seller_id'] = $oID;
                        $addLinkParam_00['seller_nama'] = $oName;
//                        $addLinkParam_00['trID_reference_master'] = "";

                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "&addParams_00=" .base64_encode(serialize($addLinkParam_00)). "'>";
                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td line='".__LINE__."' align='right' class='text-muted text-blue'>";
                        $content .= $histLink['open'] . $aa . $histLink['close'];
                        $content .= "</td>";

                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                // $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['982'] + $totalH['1982']);
                $totalH['outstanding'] = ($totalH['prev'] + $totalH['582so']) - ($totalH['582spd'] + $totalH['1982']);
                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";


            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            //             $content .= "\n<script>
            //
            // const isHTML = (str) => {
            //   const fragment = document.createRange().createContextualFragment(str);
            //
            //   // remove all non text nodes from fragment
            //   fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
            //
            //   // if there is textContent, then not a pure HTML
            //   return !(fragment.textContent || '').trim();
            // }
            //
            //                         $('#main').DataTable({
            //                             dom: 'lBfrtip',
            //                             fixedHeader: true,
            //                             iDisplayLength: -1,
            //                             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            //                             buttons: [
            //                                 {
            //                                     text: 'Export to Excel',
            //                                     action: function ( e, dt, node, config ) {
            //                                         tableToExcel('main', 'id', '$sbTitle.xls')
            //                                     }
            //                                 }
            //                             ],
            //                             footerCallback: function ( row, data, start, end, display ) {
            //                                 var api = this.api(), data;
            //                                 // Remove the formatting to get integer data for summation
            //                                 var intVal = function ( i ) {
            //                                     return typeof i === 'string' ?
            //                                         i.replace(/[$,x]/g, '')*1 :
            //                                         typeof i === 'number' ?
            //                                             i : 0;
            //                                 };
            //
            //                                     for(rw in data){
            //                                         jQuery.each(data[rw], function(i,bs){
            //                                             if(i*1>1){
            //                                             total = api
            //                                                 .column(i)
            //                                                 .data()
            //                                                 .reduce( function (a, b) {
            //                                                     if(isHTML(b)){
            //                                                         b = $(b).html()
            //                                                         if(isHTML(b)){
            //                                                             b = $(b).html()
            //                                                         }
            //                                                     }
            //                                                     return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                                 },0);
            // //                                                console.log('total', total);
            //
            //                                     pageTotal = api
            //                                         .column( i, { page: 'current'} )
            //                                         .data()
            //                                         .reduce( function (a, b) {
            //                                             if(isHTML(b)){
            //                                                 b = $(b).html()
            //                                                 if(isHTML(b)){
            //                                                     b = $(b).html()
            //                                                 }
            //                                             }
            //                                             return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                         }, 0 );
            //
            //                                         if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                             $( api.column( i ).footer() ).addClass('text-right');
            //                                             $( api.column( i ).footer() ).addClass('text-bold');
            //                                         }
            //                                         else if(parseFloat(pageTotal)==0){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                         }
            //                                         else{
            //                                             $( api.column(1).footer() ).html('T O T A L');
            //                                         }
            //
            //                                             }
            //                                         })
            //                                     }
            //
            // //                                jQuery.each(data[0], function(i,bs){
            // //                                    total = api
            // //                                        .column( i )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //
            // //                                        }, 0 );
            // //                                    pageTotal = api
            // //                                        .column( i, { page: 'current'} )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //                                        }, 0 );
            // //
            // //                                        if(parseFloat(pageTotal)>0 && i>0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                            $( api.column( i ).footer() ).addClass('text-right');
            // //                                            $( api.column( i ).footer() ).addClass('text-bold');
            // //                                        }
            // //                                        else if(parseFloat(pageTotal)==0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                        }else{
            // //
            // //                                        }
            // //                                })
            //                             }
            //                         });
            //                         $(\".table-responsive.main\").scroll(function () {
            //                             setTimeout(function () {
            //                                 $('#main').DataTable().fixedHeader.adjust();
            //                             }, 400);
            //                         });
            //                     </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion

        $tahunPilihan = isset($_GET['year']) ? "?year=" . $_GET['year'] : "";
        $link_content2 = base_url() . "ActivityReport/viewSalesOrderMonthly" . $tahunPilihan;
        $content2_injektor = "<script>
        $('#content2').load('$link_content2');
        </script>";


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        // echo $content;

        $addLink = "";
        $p = New Layout("$title", "$subTitle", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "<div id='content2'></div>";
        $content2 .= $content2_injektor;
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "content2"         => $content2,
            // "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;

    case "recap_extport":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $link_span = base_url() . "Addons/ViewDetails/item_report";
        //region main conten
        $content = "";
        // if (sizeof($stepNames) > 0) {
        //     $content .= "<ul class='nav nav-tabs'>";
        //     foreach ($stepNames as $stID => $stLabel) {
        //         $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
        //         $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
        //         $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";
        //
        //         $content .= "<li class='nav-item'>";
        //         if (strcmp($stID, $selectedStep) == 0) {
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
        //             $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
        //             $content .= "<span class='fa fa-adjust'></span> ";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //         }
        //         else {
        //             $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //
        //
        //         }
        //
        //
        //         $content .= "</li>";
        //
        //     }
        //
        //     $content .= "</ul>";
        // }

        // arrprint($recaps);
        // arrPrint($times);
        $content .= "<div ><h3>monthly sales order(export) report by customer $thPilihan</h3></div>";
        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main_1')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1' align='center' rules='all' class='table table-condensed table-bordered table-hover' sjtyle='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<td align='center' rowspan='2' class='text-muted'>$pLabel</td>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    // $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    // $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<td bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";


            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted text-bold'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";

                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;
                    // cekLime($prevYear);
                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                        //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td align='' >$val</td>";
                    // "viewdetailPrev"
                }
                // $sumTotalPrev['prev']=$totalPrev;
                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }
                        $aa = "<span href='" . $link_span . "/$pID/$oID/$j?params=" . blobEncode($addLinkParam) . "' name='qtips' style=\"text-align:left; display:block;\" class=\"text-hover\">" . formatField("harga", $val) . "</span>";
                        $addLinkParam['customers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";

                        $content .= $histLink['open'] . $aa . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                // $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['982'] + $totalH['1982']);
                $totalH['outstanding'] = $totalH['prev'] + $totalH['382so'] - ($totalH['382spd'] + $totalH['3981']);
                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";


            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            //             $content .= "\n<script>
            //
            // const isHTML = (str) => {
            //   const fragment = document.createRange().createContextualFragment(str);
            //
            //   // remove all non text nodes from fragment
            //   fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
            //
            //   // if there is textContent, then not a pure HTML
            //   return !(fragment.textContent || '').trim();
            // }
            //
            //                         $('#main').DataTable({
            //                             dom: 'lBfrtip',
            //                             fixedHeader: true,
            //                             iDisplayLength: -1,
            //                             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            //                             buttons: [
            //                                 {
            //                                     text: 'Export to Excel',
            //                                     action: function ( e, dt, node, config ) {
            //                                         tableToExcel('main', 'id', '$sbTitle.xls')
            //                                     }
            //                                 }
            //                             ],
            //                             footerCallback: function ( row, data, start, end, display ) {
            //                                 var api = this.api(), data;
            //                                 // Remove the formatting to get integer data for summation
            //                                 var intVal = function ( i ) {
            //                                     return typeof i === 'string' ?
            //                                         i.replace(/[$,x]/g, '')*1 :
            //                                         typeof i === 'number' ?
            //                                             i : 0;
            //                                 };
            //
            //                                     for(rw in data){
            //                                         jQuery.each(data[rw], function(i,bs){
            //                                             if(i*1>1){
            //                                             total = api
            //                                                 .column(i)
            //                                                 .data()
            //                                                 .reduce( function (a, b) {
            //                                                     if(isHTML(b)){
            //                                                         b = $(b).html()
            //                                                         if(isHTML(b)){
            //                                                             b = $(b).html()
            //                                                         }
            //                                                     }
            //                                                     return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                                 },0);
            // //                                                console.log('total', total);
            //
            //                                     pageTotal = api
            //                                         .column( i, { page: 'current'} )
            //                                         .data()
            //                                         .reduce( function (a, b) {
            //                                             if(isHTML(b)){
            //                                                 b = $(b).html()
            //                                                 if(isHTML(b)){
            //                                                     b = $(b).html()
            //                                                 }
            //                                             }
            //                                             return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                         }, 0 );
            //
            //                                         if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                             $( api.column( i ).footer() ).addClass('text-right');
            //                                             $( api.column( i ).footer() ).addClass('text-bold');
            //                                         }
            //                                         else if(parseFloat(pageTotal)==0){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                         }
            //                                         else{
            //                                             $( api.column(1).footer() ).html('T O T A L');
            //                                         }
            //
            //                                             }
            //                                         })
            //                                     }
            //
            // //                                jQuery.each(data[0], function(i,bs){
            // //                                    total = api
            // //                                        .column( i )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //
            // //                                        }, 0 );
            // //                                    pageTotal = api
            // //                                        .column( i, { page: 'current'} )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //                                        }, 0 );
            // //
            // //                                        if(parseFloat(pageTotal)>0 && i>0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                            $( api.column( i ).footer() ).addClass('text-right');
            // //                                            $( api.column( i ).footer() ).addClass('text-bold');
            // //                                        }
            // //                                        else if(parseFloat(pageTotal)==0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                        }else{
            // //
            // //                                        }
            // //                                })
            //                             }
            //                         });
            //                         $(\".table-responsive.main\").scroll(function () {
            //                             setTimeout(function () {
            //                                 $('#main').DataTable().fixedHeader.adjust();
            //                             }, 400);
            //                         });
            //                     </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }
        $addLink = "";
        $p = New Layout("", "", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "";
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            // "content2"         => $content2,
            "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;
    case "recapDetil":
        // arrPrint($items);

        $content = "";

        if (sizeof($items) > 0) {
//            $content .= "<div class='clearfix'>&nbsp;</div>";
//            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main')\"> Export/Download to Excel </span> </div>";
//            $content .= "<div class='clearfix'>&nbsp;</div>";
            $col_master = sizeof($headerFields) + 1;
            $content .= "<table id='main' align='center' class='table table-condensed table-bordered'>";
            $content .= "<tr>";
            $content .= "<td colspan='$col_master' class='text-center'><h4>$sub_title</h4>";
            $content .= "</td>";
            $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";
            foreach ($headerFields as $k => $label) {
                $content .= "<td  valign='middle' align='' class='text-muted'>" . $label . "</td>";
            }
            $content .= "</tr>";
            $i = 0;
            foreach ($items as $itemData) {
                $i++;
                $content .= "<tr>";
                $content .= "<td  valign='middle' align='left' class='text-muted'>" . $i . "</td>";
                foreach ($headerFields as $kk => $kf) {
                    $content .= "<td  valign='middle' align='' class='text-muted'>" . formatField($kk, $itemData[$kk]) . "</td>";
                }
                $content .= "</tr>";
            }

            if (sizeof($subtotal) > 0) {
                $content .= "<tr>";
                $content .= "<td  valign='middle' colspan='' align='left' class='text-muted'></td>";
                $content .= "<td  valign='middle' colspan='2' align='center' class='text-muted'>Total</td>";
                foreach ($headerFields as $kk => $kf) {
                    if (isset($subtotal[$kk])) {
                        $content .= "<td  valign='middle' align='' class='text-muted'>" . formatField("subtotal", $subtotal[$kk]) . "</td>";
                    }

                }
                $content .= "</tr>";
                // arrPrint($subtotal);
            }


            $content .= "</table>";

        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$sub_title", "$subTitle ", "application/template/abReport.html");
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => "",
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => "",

        ));
        $p->render();

        break;
    case "recapDetilPrev":
        // arrPrint($items);
        $content = "";

        if (sizeof($items) > 0) {
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $col_master = sizeof($headerFields) + 1;
            $content .= "<table id='main' align='center' class='table table-condensed table-bordered'>";
            $content .= "<tr>";
            $content .= "<td colspan='$col_master' class='text-center'><h4>monthly relative</h4>";
            $content .= "</td>";
            $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";
            foreach ($headerFields as $k => $label) {
                $content .= "<td  valign='middle' align='' class='text-muted'>" . $label . "</td>";
            }
            $content .= "</tr>";
            $i = 0;
            foreach ($items as $itemData) {
                $i++;
                $content .= "<tr>";
                $content .= "<td  valign='middle' align='left' class='text-muted'>" . $i . "</td>";
                foreach ($headerFields as $kk => $kf) {
                    $content .= "<td  valign='middle' align='' class='text-muted'>" . formatField($kk, $itemData[$kk]) . "</td>";
                }
                $content .= "</tr>";
            }

            if (sizeof($subtotal) > 0) {
                $content .= "<tr>";
                $content .= "<td  valign='middle' colspan='' align='left' class='text-muted'></td>";
                $content .= "<td  valign='middle' colspan='2' align='center' class='text-muted'>Total</td>";
                foreach ($headerFields as $kk => $kf) {
                    if (isset($subtotal[$kk])) {
                        $content .= "<td  valign='middle' align='' class='text-muted'>" . formatField("subtotal", $subtotal[$kk]) . "</td>";
                    }

                }
                $content .= "</tr>";
                // arrPrint($subtotal);
            }


            $content .= "</table>";

        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("$title", "$subTitle ", "application/template/abReport.html");
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => "",
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => "",

        ));
        $p->render();

        break;
    case "itemReport":
        $content = "";

        if (sizeof($items) > 0) {
            $content .= "<div class='clearfix'>&nbsp;</div>";
            // $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main')\"> Export/Download to Excel </span> </div>";
            // $content .= "<div class='clearfix'>&nbsp;</div>";
            $col_master = sizeof($headerFields) + 1;
            $content .= "<table align='center' class='table table-condensed table-bordered'>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>$sub_title</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";
            foreach ($headerFields as $k => $label) {
                $content .= "<td  valign='middle' align='' class='text-muted'>" . $label . "</td>";
            }
            $content .= "</tr>";
            $i = 0;
            foreach ($items as $itemData) {
                $i++;
                $content .= "<tr>";
                $content .= "<td  valign='middle' align='left' class='text-muted'>" . $i . "</td>";
                foreach ($headerFields as $kk => $kf) {
                    $content .= "<td  valign='middle' align='' class='text-muted'>" . formatField($kk, $itemData[$kk]) . "</td>";
                }
                $content .= "</tr>";
            }

            if (sizeof($subtotal) > 0) {
                $content .= "<tr>";
                $content .= "<td  valign='middle' colspan='' align='left' class='text-muted'></td>";
                $content .= "<td  valign='middle' colspan='2' align='center' class='text-muted'>Total</td>";
                foreach ($headerFields as $kk => $kf) {
                    if (isset($subtotal[$kk])) {
                        $content .= "<td  valign='middle' align='' class='text-muted'>" . formatField("subtotal", $subtotal[$kk]) . "</td>";
                    }

                }
                $content .= "</tr>";
                // arrPrint($subtotal);
            }


            $content .= "</table>";

        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        $p = New Layout("details", "{subTitle}", "application/template/viewdetails.html");
        $p->addTags(array('content' => $content));
        $p->render();
        break;
    case "recap_ext2_":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $link_span = base_url() . "Addons/ViewDetails/item_report";
        //region main conten
        $content = "";
        if (sizeof($masterRekeningData) > 0) {
            $content .= "<div ><h3>Rekening Penjualan bulanan $thPilihan</h3>*</div>";
            $content .= "<div>";
            $content .= "<table class='table table-bordered table-hover'>";
            $content .= "<tr>";
            $content .= "<td rowspan='2' bgcolor='#f0f0f0' align='center' class='text-muted '>No</td>";
            $content .= "<td rowspan='2' bgcolor='#f0f0f0' align='center' class='text-muted '>cabang</td>";
            foreach ($times as $kolM => $aliasM) {
                $content .= "<td colspan='3' bgcolor='#f0f0f0' align='center' class='text-muted '>$aliasM</td>";
            }
            $content .= "<td bgcolor='#009900' align='center' colspan='3' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            $arrHeaderMaster = array("penjualan" => "packinglist", "return penjualan" => "return", "netto" => "netto");
            $content .= "</tr>";
            $content .= "<tr>";
            foreach ($times as $kolM => $aliasM) {
                foreach ($arrHeaderMaster as $t => $tLabel) {
                    $content .= "<td bgcolor='#f0f0f0' align='center' class='text-muted '>$tLabel</td>";
                }
            }
            foreach ($arrHeaderMaster as $t => $tLabel) {
                $content .= "<td bgcolor='#f0f0f0' align='center' class='text-muted '>$tLabel</td>";
            }
            $content .= "</tr>";
            $ii = 0;
            foreach ($masterRekeningData as $cabang => $cabang_data) {
                $ii++;
                $content .= "<tr>";
                $content .= "<td>$ii</td>";
                $content .= "<td>" . $masterCabang[$cabang] . "</td>";
                foreach ($times as $kolM => $aliasM) {
                    foreach ($arrHeaderMaster as $km => $kmLabel) {
                        if (isset($cabang_data[$aliasM][$km])) {
                            $val = formatField("subtotal", $cabang_data[$aliasM][$km]);
                        }
                        else {
                            if ($km == "netto") {
                                if (isset($cabang_data[$aliasM]["penjualan"])) {
                                    $valT = $cabang_data[$aliasM]["penjualan"] - $cabang_data[$aliasM]["return penjualan"];
                                    $val = formatField("subtotal", $valT);
                                }
                                else {
                                    $val = formatField("subtotal", "0");
                                }

                            }
                            else {
                                $val = formatField("subtotal", "0");
                            }

                        }
                        $content .= "<td>$val</td>";
                    }

                }
                foreach ($arrHeaderMaster as $t => $tLabel) {
                    if (isset($sumValidatecabang[$cabang][$t])) {
                        $val = formatField("total", $sumValidatecabang[$cabang][$t]);
                    }
                    else {
                        if ($km == "netto") {
                            if (isset($sumValidatecabang[$cabang]["penjualan"])) {
                                $valT = $sumValidatecabang[$cabang]["penjualan"] - $sumValidatecabang[$cabang]["return penjualan"];
                                $val = formatField("total", $valT);
                            }
                            else {
                                $val = formatField("total", "0");
                            }

                        }
                        else {
                            $val = formatField("total", "0");
                        }

                    }
                    $content .= "<td class='text-bold'>$val</td>";
                }
                $content .= "</tr>";

            }

            if ($this->session->login['cabang_id'] < 0) {
                $content .= "<tr>";
                $content .= "<td></td>";
                $content .= "<td class='text-muted text-bold'>Total</td>";


                foreach ($times as $kolM => $aliasM) {
                    foreach ($arrHeaderMaster as $km => $kmLabel) {
                        if (isset($sumMasterRekeningData[$aliasM][$km])) {
                            $val = formatField("total", $sumMasterRekeningData[$aliasM][$km]);
                        }
                        else {
                            if ($km == "netto") {
                                if (isset($sumMasterRekeningData[$aliasM]["penjualan"])) {
                                    $valT = $sumMasterRekeningData[$aliasM]["penjualan"] - $sumMasterRekeningData[$aliasM]["return penjualan"];
                                    $val = formatField("total", $valT);
                                }
                                else {
                                    $val = formatField("total", "0");
                                }

                            }
                            else {
                                $val = formatField("total", "0");
                            }

                        }
                        $content .= "<td class='text-bold'>$val</td>";
                    }

                }
                foreach ($arrHeaderMaster as $t => $tLabel) {
                    if (isset($masterGrandTotal[$t])) {
                        $val = formatField("total", $masterGrandTotal[$t]);
                    }
                    else {
                        if ($km == "netto") {
                            if (isset($masterGrandTotal["penjualan"])) {
                                $valT = $masterGrandTotal["penjualan"] - $masterGrandTotal["return penjualan"];
                                $val = formatField("total", $valT);
                            }
                            else {
                                $val = formatField("total", "0");
                            }

                        }
                        else {
                            $val = formatField("total", "0");
                        }

                    }
                    $content .= "<td class='text-bold'>$val</td>";
                }
                $content .= "</tr>";
            }
            $content .= "</table>";
            $content .= "</div>";
        }

        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div ><h3>Laporan Penjualan bulanan per customer $thPilihan</h3></div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main_1')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1' align='center' rules='all' class='table table-condensed table-bordered table-hover' sjtyle='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<td align='center' rowspan='2' class='text-muted'>$pLabel**</td>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    // $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    // $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<td bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";

            // arrPrint($times);
            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted text-bold'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";

                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;
                    // cekLime($prevYear);
                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                        //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td align='' >$val</td>";
                    // "viewdetailPrev"
                }
                // $sumTotalPrev['prev']=$totalPrev;
                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }
                        $aa = "<span href='" . $link_span . "/$pID/$oID/$j?params=" . blobEncode($addLinkParam) . "' name='qtips' style=\"text-align:left; display:block;\" class=\"text-hover\">" . formatField("harga", $val) . "</span>";
                        $addLinkParam['customers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";

                        $content .= $histLink['open'] . $aa . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                // $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                // $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['982'] + $totalH['1982']);
                $totalH['netto'] = $totalH['582spd'] - $totalH['982'] - $totalH['9912'] - $totalH['999'];
                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";
            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            // $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            // $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            //             $content .= "\n<script>
            //
            // const isHTML = (str) => {
            //   const fragment = document.createRange().createContextualFragment(str);
            //
            //   // remove all non text nodes from fragment
            //   fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
            //
            //   // if there is textContent, then not a pure HTML
            //   return !(fragment.textContent || '').trim();
            // }
            //
            //                         $('#main').DataTable({
            //                             dom: 'lBfrtip',
            //                             fixedHeader: true,
            //                             iDisplayLength: -1,
            //                             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            //                             buttons: [
            //                                 {
            //                                     text: 'Export to Excel',
            //                                     action: function ( e, dt, node, config ) {
            //                                         tableToExcel('main', 'id', '$sbTitle.xls')
            //                                     }
            //                                 }
            //                             ],
            //                             footerCallback: function ( row, data, start, end, display ) {
            //                                 var api = this.api(), data;
            //                                 // Remove the formatting to get integer data for summation
            //                                 var intVal = function ( i ) {
            //                                     return typeof i === 'string' ?
            //                                         i.replace(/[$,x]/g, '')*1 :
            //                                         typeof i === 'number' ?
            //                                             i : 0;
            //                                 };
            //
            //                                     for(rw in data){
            //                                         jQuery.each(data[rw], function(i,bs){
            //                                             if(i*1>1){
            //                                             total = api
            //                                                 .column(i)
            //                                                 .data()
            //                                                 .reduce( function (a, b) {
            //                                                     if(isHTML(b)){
            //                                                         b = $(b).html()
            //                                                         if(isHTML(b)){
            //                                                             b = $(b).html()
            //                                                         }
            //                                                     }
            //                                                     return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                                 },0);
            // //                                                console.log('total', total);
            //
            //                                     pageTotal = api
            //                                         .column( i, { page: 'current'} )
            //                                         .data()
            //                                         .reduce( function (a, b) {
            //                                             if(isHTML(b)){
            //                                                 b = $(b).html()
            //                                                 if(isHTML(b)){
            //                                                     b = $(b).html()
            //                                                 }
            //                                             }
            //                                             return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                         }, 0 );
            //
            //                                         if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                             $( api.column( i ).footer() ).addClass('text-right');
            //                                             $( api.column( i ).footer() ).addClass('text-bold');
            //                                         }
            //                                         else if(parseFloat(pageTotal)==0){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                         }
            //                                         else{
            //                                             $( api.column(1).footer() ).html('T O T A L');
            //                                         }
            //
            //                                             }
            //                                         })
            //                                     }
            //
            // //                                jQuery.each(data[0], function(i,bs){
            // //                                    total = api
            // //                                        .column( i )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //
            // //                                        }, 0 );
            // //                                    pageTotal = api
            // //                                        .column( i, { page: 'current'} )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //                                        }, 0 );
            // //
            // //                                        if(parseFloat(pageTotal)>0 && i>0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                            $( api.column( i ).footer() ).addClass('text-right');
            // //                                            $( api.column( i ).footer() ).addClass('text-bold');
            // //                                        }
            // //                                        else if(parseFloat(pageTotal)==0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                        }else{
            // //
            // //                                        }
            // //                                })
            //                             }
            //                         });
            //                         $(\".table-responsive.main\").scroll(function () {
            //                             setTimeout(function () {
            //                                 $('#main').DataTable().fixedHeader.adjust();
            //                             }, 400);
            //                         });
            //                     </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }
        $addLink = "";
        $p = New Layout("", "", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "";
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            // "content2"         => $content2,
            "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;
    case "recap_ext2":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $link_span = base_url() . "Addons/ViewDetails/item_report";
        //region main conten
        $content = "";
        if (sizeof($masterRekeningData) > 0) {
            $content .= "<div ><h3>Rekening Penjualan bulanan $thPilihan</h3></div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('rekening')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div>";
            $content .= "<table id='rekening'class='table table-bordered table-hover'>";
            $content .= "<thead>";
            $content .= "<tr>";
            $content .= "<td rowspan='2' bgcolor='#f0f0f0' align='center' class='text-muted '>No</td>";
            $content .= "<td rowspan='2' bgcolor='#f0f0f0' align='center' class='text-muted '>cabang*</td>";
            foreach ($times as $kolM => $aliasM) {
                $content .= "<td colspan='3' bgcolor='#f0f0f0' align='center' class='text-muted '>$aliasM</td>";
            }
            $content .= "<td bgcolor='#009900' align='center' colspan='3' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            $arrHeaderMaster = array("penjualan" => "packinglist", "return penjualan" => "return", "netto" => "netto");
            $content .= "</tr>";
            $content .= "</thead>";
            $content .= "<tbody>";
            $content .= "<tr>";
            foreach ($times as $kolM => $aliasM) {
                foreach ($arrHeaderMaster as $t => $tLabel) {
                    $content .= "<td bgcolor='#f0f0f0' align='center' class='text-muted '>$tLabel</td>";
                }
            }
            foreach ($arrHeaderMaster as $t => $tLabel) {
                $content .= "<td bgcolor='#f0f0f0' align='center' class='text-muted '>$tLabel</td>";
            }
            $content .= "</tr>";
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $ii = 0;
            foreach ($masterRekeningData as $cabang => $cabang_data) {
                $ii++;
                $content .= "<tr>";
                $content .= "<td>$ii</td>";
                $content .= "<td>" . $masterCabang[$cabang] . "</td>";
                foreach ($times as $kolM => $aliasM) {
                    foreach ($arrHeaderMaster as $km => $kmLabel) {
                        if (isset($cabang_data[$aliasM][$km])) {
                            $val = formatField("subtotal", $cabang_data[$aliasM][$km]);
                        }
                        else {
                            if ($km == "netto") {
                                if (isset($cabang_data[$aliasM]["penjualan"])) {
                                    $valT = $cabang_data[$aliasM]["penjualan"] - $cabang_data[$aliasM]["return penjualan"];
                                    $val = formatField("subtotal", $valT);
                                }
                                else {
                                    $val = formatField("subtotal", "0");
                                }

                            }
                            else {
                                $val = formatField("subtotal", "0");
                            }

                        }
                        $content .= "<td>$val</td>";
                    }

                }
                foreach ($arrHeaderMaster as $t => $tLabel) {
                    if (isset($sumValidatecabang[$cabang][$t])) {
                        $val = formatField("total", $sumValidatecabang[$cabang][$t]);
                    }
                    else {
                        if ($km == "netto") {
                            if (isset($sumValidatecabang[$cabang]["penjualan"])) {
                                $valT = $sumValidatecabang[$cabang]["penjualan"] - $sumValidatecabang[$cabang]["return penjualan"];
                                $val = formatField("total", $valT);
                            }
                            else {
                                $val = formatField("total", "0");
                            }

                        }
                        else {
                            $val = formatField("total", "0");
                        }

                    }
                    $content .= "<td class='text-bold'>$val</td>";
                }
                $content .= "</tr>";

            }

            if ($this->session->login['cabang_id'] < 0) {
                $content .= "<tr>";
                $content .= "<td></td>";
                $content .= "<td class='text-muted text-bold'>Total</td>";


                foreach ($times as $kolM => $aliasM) {
                    foreach ($arrHeaderMaster as $km => $kmLabel) {
                        if (isset($sumMasterRekeningData[$aliasM][$km])) {
                            $val = formatField("total", $sumMasterRekeningData[$aliasM][$km]);
                        }
                        else {
                            if ($km == "netto") {
                                if (isset($sumMasterRekeningData[$aliasM]["penjualan"])) {
                                    $valT = $sumMasterRekeningData[$aliasM]["penjualan"] - $sumMasterRekeningData[$aliasM]["return penjualan"];
                                    $val = formatField("total", $valT);
                                }
                                else {
                                    $val = formatField("total", "0");
                                }

                            }
                            else {
                                $val = formatField("total", "0");
                            }

                        }
                        $content .= "<td class='text-bold'>$val</td>";
                    }

                }
                foreach ($arrHeaderMaster as $t => $tLabel) {
                    if (isset($masterGrandTotal[$t])) {
                        $val = formatField("total", $masterGrandTotal[$t]);
                    }
                    else {
                        if ($km == "netto") {
                            if (isset($masterGrandTotal["penjualan"])) {
                                $valT = $masterGrandTotal["penjualan"] - $masterGrandTotal["return penjualan"];
                                $val = formatField("total", $valT);
                            }
                            else {
                                $val = formatField("total", "0");
                            }

                        }
                        else {
                            $val = formatField("total", "0");
                        }

                    }
                    $content .= "<td class='text-bold'>$val</td>";
                }
                $content .= "</tr>";
            }
            $content .= "</tfoot>";
            $content .= "</table>";
            $content .= "</div>";
        }

        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div ><h3>Laporan Penjualan bulanan per customer $thPilihan</h3></div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main_1')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1' align='center' rules='all' class='table display no-padding' stylexx='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<td align='center' rowspan='2' class='text-muted'>$pLabel**</td>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    // $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    // $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<td bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";

            // arrPrint($times);
            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted text-bold'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";

                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;
                    // cekLime($prevYear);
                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                        //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td align='' >$val</td>";
                    // "viewdetailPrev"
                }
                // $sumTotalPrev['prev']=$totalPrev;
                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }
                        $aa = "<span href='" . $link_span . "/$pID/$oID/$j?params=" . blobEncode($addLinkParam) . "' name='qtips' style=\"text-align:left; display:block;\" class=\"text-hover\">" . formatField("harga", $val) . "</span>";
                        $addLinkParam['customers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";

                        $content .= $histLink['open'] . $aa . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                // $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                // $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['982'] + $totalH['1982']);
                $totalH['netto'] = $totalH['582spd'] + $totalH['382spd'] - $totalH['982'] - $totalH['9912'] - $totalH['999'];
                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";
            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            // $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            // $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            //             $content .= "\n<script>
            //
            // const isHTML = (str) => {
            //   const fragment = document.createRange().createContextualFragment(str);
            //
            //   // remove all non text nodes from fragment
            //   fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
            //
            //   // if there is textContent, then not a pure HTML
            //   return !(fragment.textContent || '').trim();
            // }
            //
            //                         $('#main').DataTable({
            //                             dom: 'lBfrtip',
            //                             fixedHeader: true,
            //                             iDisplayLength: -1,
            //                             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            //                             buttons: [
            //                                 {
            //                                     text: 'Export to Excel',
            //                                     action: function ( e, dt, node, config ) {
            //                                         tableToExcel('main', 'id', '$sbTitle.xls')
            //                                     }
            //                                 }
            //                             ],
            //                             footerCallback: function ( row, data, start, end, display ) {
            //                                 var api = this.api(), data;
            //                                 // Remove the formatting to get integer data for summation
            //                                 var intVal = function ( i ) {
            //                                     return typeof i === 'string' ?
            //                                         i.replace(/[$,x]/g, '')*1 :
            //                                         typeof i === 'number' ?
            //                                             i : 0;
            //                                 };
            //
            //                                     for(rw in data){
            //                                         jQuery.each(data[rw], function(i,bs){
            //                                             if(i*1>1){
            //                                             total = api
            //                                                 .column(i)
            //                                                 .data()
            //                                                 .reduce( function (a, b) {
            //                                                     if(isHTML(b)){
            //                                                         b = $(b).html()
            //                                                         if(isHTML(b)){
            //                                                             b = $(b).html()
            //                                                         }
            //                                                     }
            //                                                     return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                                 },0);
            // //                                                console.log('total', total);
            //
            //                                     pageTotal = api
            //                                         .column( i, { page: 'current'} )
            //                                         .data()
            //                                         .reduce( function (a, b) {
            //                                             if(isHTML(b)){
            //                                                 b = $(b).html()
            //                                                 if(isHTML(b)){
            //                                                     b = $(b).html()
            //                                                 }
            //                                             }
            //                                             return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                         }, 0 );
            //
            //                                         if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                             $( api.column( i ).footer() ).addClass('text-right');
            //                                             $( api.column( i ).footer() ).addClass('text-bold');
            //                                         }
            //                                         else if(parseFloat(pageTotal)==0){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                         }
            //                                         else{
            //                                             $( api.column(1).footer() ).html('T O T A L');
            //                                         }
            //
            //                                             }
            //                                         })
            //                                     }
            //
            // //                                jQuery.each(data[0], function(i,bs){
            // //                                    total = api
            // //                                        .column( i )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //
            // //                                        }, 0 );
            // //                                    pageTotal = api
            // //                                        .column( i, { page: 'current'} )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //                                        }, 0 );
            // //
            // //                                        if(parseFloat(pageTotal)>0 && i>0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                            $( api.column( i ).footer() ).addClass('text-right');
            // //                                            $( api.column( i ).footer() ).addClass('text-bold');
            // //                                        }
            // //                                        else if(parseFloat(pageTotal)==0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                        }else{
            // //
            // //                                        }
            // //                                })
            //                             }
            //                         });
            //                         $(\".table-responsive.main\").scroll(function () {
            //                             setTimeout(function () {
            //                                 $('#main').DataTable().fixedHeader.adjust();
            //                             }, 400);
            //                         });
            //                     </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }
        $addLink = "";
        $p = New Layout("", "", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "";
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            // "content2"         => $content2,
            "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;
    case "recap_ext1_vendor":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        //region main conten
        $content = "";
        // if (sizeof($stepNames) > 0) {
        //     $content .= "<ul class='nav nav-tabs'>";
        //     foreach ($stepNames as $stID => $stLabel) {
        //         $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
        //         $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
        //         $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";
        //
        //         $content .= "<li class='nav-item'>";
        //         if (strcmp($stID, $selectedStep) == 0) {
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
        //             $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
        //             $content .= "<span class='fa fa-adjust'></span> ";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //         }
        //         else {
        //             $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //
        //
        //         }
        //
        //
        //         $content .= "</li>";
        //
        //     }
        //
        //     $content .= "</ul>";
        // }

        // arrprint($recaps);
        // arrPrint($times);
        $content .= "<div ><h3>$titleReport</h3></div>";
        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main_1')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1' align='center' rules='all' class='table table-condensed table-bordered' sjtyle='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<td align='center' rowspan='2' class='text-muted'>$pLabel</td>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<td bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";


            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";

                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;
                    // cekLime($prevYear);
                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                        //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td align='' >$val</td>";
                    // "viewdetailPrev"
                }
                // $sumTotalPrev['prev']=$totalPrev;
                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }

                        $addLinkParam['suppliers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";
                        $content .= $histLink['open'] . formatField("harga", $val) . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                if (isset($modeItem) && ($modeItem == "produk")) {

                    $totalH['outstanding'] = $totalH['prev'] + $totalH['466'] - ($totalH['467'] + $totalH['1967']);
                }
                elseif (isset($modeItem) && ($modeItem == "produkImport")) {

                    $totalH['outstanding'] = $totalH['prev'] + $totalH['460a'] - ($totalH['460'] + $totalH['1960a']);
                }
                elseif (isset($modeItem) && ($modeItem == "supplies")) {

                    $totalH['outstanding'] = $totalH['prev'] + $totalH['461r'] - ($totalH['461'] + $totalH['1961']);
                }
                else {
                    $totalH['outstanding'] = 0;
                }


                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";


            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            $content .= "\n<script>

const isHTML = (str) => {
  const fragment = document.createRange().createContextualFragment(str);
  
  // remove all non text nodes from fragment
  fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
  
  // if there is textContent, then not a pure HTML
  return !(fragment.textContent || '').trim();
}

                        $('#main').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            iDisplayLength: -1,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                            buttons: [
                                {
                                    text: 'Export to Excel',
                                    action: function ( e, dt, node, config ) {
                                        tableToExcel('main', 'id', '$sbTitle.xls')
                                    }
                                }
                            ],
                            footerCallback: function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) { 
                                    return typeof i === 'string' ?
                                        i.replace(/[$,x]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                    for(rw in data){
                                        jQuery.each(data[rw], function(i,bs){
                                            if(i*1>1){
                                            total = api
                                                .column(i)
                                                .data()
                                                .reduce( function (a, b) {
                                                    if(isHTML(b)){
                                                        b = $(b).html()
                                                        if(isHTML(b)){
                                                            b = $(b).html()
                                                        }
                                                    }
                                                    return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                                },0);
//                                                console.log('total', total);
                                                
                                    pageTotal = api
                                        .column( i, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            if(isHTML(b)){
                                                b = $(b).html()
                                                if(isHTML(b)){
                                                    b = $(b).html()
                                                }
                                            }
                                            return intVal(removeCommas(a)) + intVal(removeCommas(b));
                                        }, 0 );

                                        if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                            $( api.column( i ).footer() ).addClass('text-right');
                                            $( api.column( i ).footer() ).addClass('text-bold');
                                        }
                                        else if(parseFloat(pageTotal)==0){
                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
                                        }
                                        else{
                                            $( api.column(1).footer() ).html('T O T A L');
                                        }

                                            }
                                        })
                                    }

//                                jQuery.each(data[0], function(i,bs){
//                                    total = api
//                                        .column( i )
//                                        .data()
//                                        .reduce( function (a, b) {
//                                            b = $(b).html() ? b : '<a>'+b+'</a>';
//                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
//
//                                        }, 0 );
//                                    pageTotal = api
//                                        .column( i, { page: 'current'} )
//                                        .data()
//                                        .reduce( function (a, b) {
//                                            b = $(b).html() ? b : '<a>'+b+'</a>';
//                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
//                                        }, 0 );
//
//                                        if(parseFloat(pageTotal)>0 && i>0){
//                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
//                                            $( api.column( i ).footer() ).addClass('text-right');
//                                            $( api.column( i ).footer() ).addClass('text-bold');
//                                        }
//                                        else if(parseFloat(pageTotal)==0){
//                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
//                                        }else{
//
//                                        }
//                                })
                            }
                        });
                        $(\".table-responsive.main\").scroll(function () {
                            setTimeout(function () {
                                $('#main').DataTable().fixedHeader.adjust();
                            }, 400);
                        });
                    </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }

        $p = New Layout("", "", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "";
        $p->addTags(array(
            "jenisTr" => $jenisTr . $str_group,
            "trName" => $trName,
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            "content2" => $content2,
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;
    case "recap_ext2_vendor":
        $stID = isset($_GET['stID']) ? "stID=" . $_GET['stID'] : "";
        $sID = isset($_GET['sID']) ? "sID=" . $_GET['sID'] : "";
        $qString = strlen($_SERVER['QUERY_STRING']) > 0 ? "?" . $_SERVER['QUERY_STRING'] : "";
        // cekKuning($_SERVER['QUERY_STRING']);
        $ly = new Layout();
        $strThNow = dtimeNow('Y');
        $this_uri = current_url() . $qString;
        // cekHere($this_uri. "   === $qString");

        $thPilihan = isset($_GET['year']) ? $_GET['year'] : $strThNow;
        $ly->setOnClickTarget($this_uri);
        $nav_top = $ly->selectTahun($thPilihan);

        $link_span = base_url() . "Addons/ViewDetails/item_report";
        //region main conten
        $content = "";
        // if (sizeof($stepNames) > 0) {
        //     $content .= "<ul class='nav nav-tabs'>";
        //     foreach ($stepNames as $stID => $stLabel) {
        //         $color = (strcmp($stID, $selectedStep) == 0) ? "#454549" : "#999999";
        //         $borderColor = (strcmp($stID, $selectedStep) == 0) ? "#cccccc" : "#ffffff";
        //         $bgColor = (strcmp($stID, $selectedStep) == 0) ? "#ffffff" : "#f0f0f0";
        //
        //         $content .= "<li class='nav-item'>";
        //         if (strcmp($stID, $selectedStep) == 0) {
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;border:1px $borderColor solid;'>";
        //             $content .= "<a class='nav-link-active' style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             //                    $content .= "<a class='nav-link-active' style='color:$color;'>";
        //             $content .= "<span class='fa fa-adjust'></span> ";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //         }
        //         else {
        //             $content .= "<a class='nav-link' href='$thisPage?stID=$stID&sID=$selectedFilter'  style='background:$bgColor;color:$color;border-width:1px 1px 0px 1px; border-color:$borderColor; border-style: solid;'>";
        //             $content .= $stLabel;
        //             $content .= "</a>";
        //
        //
        //         }
        //
        //
        //         $content .= "</li>";
        //
        //     }
        //
        //     $content .= "</ul>";
        // }

        // arrprint($recaps);
        // arrPrint($times);
        $content .= "<div ><h3>Laporan Penjualan bulanan per customer $thPilihan</h3></div>";
        if (sizeof($recaps) > 0) {
            // cekLime("ini");
            //bagian customer
            $content .= "<div class='clearfix'>&nbsp;</div>";
            $content .= "<div> <span class='btn btn-sm btn-warning btn-flat' onclick=\"fnExcelReport('main_1')\"> Export/Download to Excel </span> </div>";
            $content .= "<div class='clearfix'>&nbsp;</div>";

            $content .= "<table id='main_1' align='center' rules='all' class='table table-condensed table-bordered table-hover' sjtyle='tableu-layout: fixed; width: 100%; display: -webkit-flex; /* Safari */
  -webkit-flex-wrap: wrap; /* Safari 6.1+ */
  display: flex;   
  flex-wrap: wrap;'>";
            // cekLime(sizeof($times)." * ".sizeof($headerList));

            $col_master = sizeof($times) * sizeof($headerList) + 9;
            $content .= "<thead>";
            // $content .= "<tr>";
            // $content .= "<td colspan='$col_master' class='text-center'><h4>monthly sales report</h4>";
            // $content .= "</td>";
            // $content .= "</tr>";
            $content .= "<tr bgcolor='#f0f0f0'>";
            $content .= "<td rowspan='2' valign='middle' align='right' class='text-muted'>No.";
            $content .= "</td>";

            $content .= "<td rowspan='2' valign='bottom' class='text-muted'>";
            $content .= "<span class='pull-right'>$timeLabel<span class='fa fa-angle-double-right'></span></span> <br>";
            $content .= "<span class='fa fa-angle-double-down'></span> ";
            $content .= $identifierLabels[$selectedFilter] . createObjectSuffix($identifierLabels[$selectedFilter]);
            $content .= "</td>";
            //header previus year
            foreach ($prevTimes as $pk => $pLabel) {
                $content .= "<td align='center' rowspan='2' class='text-muted'>$pLabel**</td>";
            }
            $cols = sizeof($headerList);
            foreach ($times as $pID => $pName) {
                $content .= "<td align='center' colspan='$cols' class='text-muted'>";
                if (isset($subPage)) {
                    // $content .= "<a href='" . $subPage . "?time=$pID'>";
                    $content .= $pName;
                    // $content .= "</a>";
                }
                else {
                    $content .= $pName;
                }

                $content .= "</td>";
            }
            // arrPrint($headerListSum);
            $colsg = sizeof($headerListSum);
            $content .= "<td bgcolor='#009900' align='center' colspan='$colsg' class='text-muted text-white'>";
            $content .= "SUMMARY";
            $content .= "</td>";
            // $content .= "<td bgcolor='#005689' align='center' colspan='2' class='text-muted text-white'>";
            // $content .= "AVG";
            // $content .= "</td>";

            $content .= "</tr>";

            arrPrint($times);
            $content .= "<tr bgcolor='#e5e5e5'>";
            foreach ($times as $pID => $pName) {
                foreach ($headerList as $jn => $alias) {
                    $content .= "<td align='center' class='text-muted'>";
                    $content .= "$alias";
                    $content .= "</td>";
                }
            }

            // foreach ($prevTimes as $prev =>$prevLabel){
            //     $content .= "<td align='center' class='text-muted'>";
            //     $content .= "$prevLabel";
            //     $content .= "</td>";
            // }
            foreach ($headerListSum as $jn => $alias) {
                $content .= "<td align='center' class='text-muted text-bold'>";
                $content .= $alias;
                $content .= "</td>";
            }


            // $content .= "<td align='center' class='text-blue text-muted' colspan='2'>";
            // $content .= "</td>";
            $content .= "</tr>";
            $content .= "</thead>";
            $no = 0;
            $sumQty = 0;
            $sumVal = 0;
            $totalV = array();
            // arrPrint($times);
            // arrPrint($headerListSum);
            $totalPrev = 0;
            $content .= "<tbody>";
            foreach ($names as $oID => $oName) {
                $no++;
                $content .= "<tr>";
                $content .= "<td align='right' class='text-muted'>";
                $content .= $no;
                $content .= "</td>";
                $content .= "<td>";
                $content .= $oName;
                $content .= "</td>";

                foreach ($prevTimes as $pk => $pLabel) {
                    if (isset($prevRecaps[$oID][$pk])) {
                        $totalPrev += $prevRecaps[$oID][$pk];
                    }
                    $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                    $prevYear = $currYear - 1;
                    // cekLime($prevYear);
                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevRecaps[$oID][$pk]) ? formatField("subtotal", $prevRecaps[$oID][$pk]) : formatField("subtotal", 0);
                    if ($val > 0) {
                        $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                        //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                        $histLink['close'] = "</a>";
                    }
                    else {
                        $histLink['open'] = "";
                        $histLink['close'] = "";
                    }
                    $content .= "<td align='' >$val</td>";
                    // "viewdetailPrev"
                }
                // $sumTotalPrev['prev']=$totalPrev;
                $totalH = array();
                foreach ($times as $pID => $pName) {
                    foreach ($headerList as $j => $al) {
                        $val = isset($recaps[$oID][$pID][$j]) ? $recaps[$oID][$pID][$j] : 0;

                        if ($pID == "prev") {
                            $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
                            $prevYear = $currYear - 1;
                            // cekLime($prevYear);
                            $addLinkParam['dtime'] = $prevYear;
                        }
                        else {
                            $extPID = $pID . "-01";
                            $addLinkParam['dtime'] = $extPID;

                        }
                        $aa = "<span href='" . $link_span . "/$pID/$oID/$j?params=" . blobEncode($addLinkParam) . "' name='qtips' style=\"text-align:left; display:block;\" class=\"text-hover\">" . formatField("harga", $val) . "</span>";
                        $addLinkParam['customers_id'] = $oID;
                        if ($val > 0) {
                            $histLink['open'] = "<a href='$historyPage/$j?addParams=" . base64_encode(serialize($addLinkParam)) . "'>";
                            //                            $histLink['open'] = "<a href='$historyPage' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">";

                            $histLink['close'] = "</a>";
                        }
                        else {
                            $histLink['open'] = "";
                            $histLink['close'] = "";
                        }


                        $content .= "<td align='right' class='text-muted text-blue'>";

                        $content .= $histLink['open'] . $aa . $histLink['close'];
                        $content .= "</td>";
                        if (!isset($totalH[$j])) {
                            $totalH[$j] = 0;
                        }
                        if (!isset($totalV[$pID][$j])) {
                            $totalV[$pID][$j] = 0;
                        }
                        $totalH[$j] += $val;
                        $totalV[$pID][$j] += $val;
                    }
                }


                // foreach($prevTimes as $pKey =>$pLabel){
                // $prevVal = isset($prevRecaps[$oID][$pKey]) ? formatField("harga", $prevRecaps[$oID][$pKey]): formatField("harga", 0);
                // $totalH['prev'] = isset($prevRecaps[$oID]['prev']) ? $prevRecaps[$oID]['prev'] : 0;
                // $totalVe['prev'] +=isset($prevRecaps[$oID]['prev'])? $prevRecaps[$oID]['prev']:0;
                // $content .= "<td align='right' class='text-muted text-blue'>";
                //
                // $content .= $prevVal;
                // $content .= "***</td>";
                // }

                // $totalH['outstanding'] = $totalH['prev'] + $totalH['582so'] - ($totalH['582spd'] + $totalH['982'] + $totalH['1982']);
                $totalH['netto'] = $totalH['582spd'] - $totalH['982'] - $totalH['9912'];
                // $totalSumV = array();
                foreach ($headerListSum as $j => $jName) {
                    if (!isset($totalSumV[$j])) {
                        $totalSumV[$j] = 0;
                    }
                    $totalSumV[$j] += $totalH[$j];
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("subtotal", $totalH[$j]);
                    $content .= "</td>";
                }
                // arrPrint($totalH);
                $content .= "</tr>";
            }
            $content .= "</tbody>";
            $content .= "<tfoot>";
            $content .= "<tr bgcolor='#e5e5e5'>";
            $content .= "<td align='right' class='text-muted'>";
            $content .= "";
            $content .= "</td>";
            // $content .= "<td align='right' class='text-muted'>";
            // $content .= "";
            // $content .= "</td>";

            $content .= "<td>";
            $content .= "TOTAL";
            $content .= "</td>";


            // arrPrint($totalV);
            $valSumPrev = 0;
            // arrPrint($prevRecaps);
            // foreach ($sumTotalPrev as $jn => $alias) {
            // $valSumPrev +=
            // $content .= "<td align='center' class='text-muted'>" . formatField("subtotal", $totalPrev);
            // $content .= "</td>";

            // }
            foreach ($times as $pID => $pName) {
                $val = 0;
                foreach ($headerList as $j => $jName) {
                    $content .= "<td align='right' class='text-muted text-blue'>";
                    $content .= formatField("harga", $totalV[$pID][$j]);
                    $content .= "</td>";
                }
                // $qty = isset($totalV[$pID]['qty']) ? number_format($totalV[$pID]['qty']) : "";
                // $val = isset($totalV[$pID]['value']) ? number_format($totalV[$pID]['value'] / 1000) : "";
                // $content .= "<td align='right' class='text-muted'>";
                // $content .= $qty;
                // $content .= "</td>";
                // $content .= "<td align='right' class='text-muted text-blue'>";
                // $content .= $val;
                // $content .= "</td>";

            }
            // arrPrint($headerListSum);
            // arrPrint($totalSumV);
            foreach ($headerListSum as $jj => $jjLabel) {
                $content .= "<td align='right' class='text-muted text-blue'>";
                $content .= formatField("harga", $totalSumV[$jj]);
                $content .= "</td>";
            }


            $content .= "</tr>";

            // $content .= "<td>$sumQty</td>";
            // $content .= "<td>" . formatField("value", $sumVal) . "****</td>";
            // for ($i = 1; $i <= 2; $i++) {
            //     $content .= "<td class='text-center'>-</td>";
            // }
            // $content .= "</tr>";


            //region summary footer


            //endregion

            $content .= "</tfoot>";
            $content .= "</table>";
            //             $content .= "\n<script>
            //
            // const isHTML = (str) => {
            //   const fragment = document.createRange().createContextualFragment(str);
            //
            //   // remove all non text nodes from fragment
            //   fragment.querySelectorAll('*').forEach(el => el.parentNode.removeChild(el));
            //
            //   // if there is textContent, then not a pure HTML
            //   return !(fragment.textContent || '').trim();
            // }
            //
            //                         $('#main').DataTable({
            //                             dom: 'lBfrtip',
            //                             fixedHeader: true,
            //                             iDisplayLength: -1,
            //                             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            //                             buttons: [
            //                                 {
            //                                     text: 'Export to Excel',
            //                                     action: function ( e, dt, node, config ) {
            //                                         tableToExcel('main', 'id', '$sbTitle.xls')
            //                                     }
            //                                 }
            //                             ],
            //                             footerCallback: function ( row, data, start, end, display ) {
            //                                 var api = this.api(), data;
            //                                 // Remove the formatting to get integer data for summation
            //                                 var intVal = function ( i ) {
            //                                     return typeof i === 'string' ?
            //                                         i.replace(/[$,x]/g, '')*1 :
            //                                         typeof i === 'number' ?
            //                                             i : 0;
            //                                 };
            //
            //                                     for(rw in data){
            //                                         jQuery.each(data[rw], function(i,bs){
            //                                             if(i*1>1){
            //                                             total = api
            //                                                 .column(i)
            //                                                 .data()
            //                                                 .reduce( function (a, b) {
            //                                                     if(isHTML(b)){
            //                                                         b = $(b).html()
            //                                                         if(isHTML(b)){
            //                                                             b = $(b).html()
            //                                                         }
            //                                                     }
            //                                                     return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                                 },0);
            // //                                                console.log('total', total);
            //
            //                                     pageTotal = api
            //                                         .column( i, { page: 'current'} )
            //                                         .data()
            //                                         .reduce( function (a, b) {
            //                                             if(isHTML(b)){
            //                                                 b = $(b).html()
            //                                                 if(isHTML(b)){
            //                                                     b = $(b).html()
            //                                                 }
            //                                             }
            //                                             return intVal(removeCommas(a)) + intVal(removeCommas(b));
            //                                         }, 0 );
            //
            //                                         if(parseFloat(pageTotal)>0 && i>1 || parseFloat(pageTotal)<0 && i>1){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                             $( api.column( i ).footer() ).addClass('text-right');
            //                                             $( api.column( i ).footer() ).addClass('text-bold');
            //                                         }
            //                                         else if(parseFloat(pageTotal)==0){
            //                                             $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            //                                         }
            //                                         else{
            //                                             $( api.column(1).footer() ).html('T O T A L');
            //                                         }
            //
            //                                             }
            //                                         })
            //                                     }
            //
            // //                                jQuery.each(data[0], function(i,bs){
            // //                                    total = api
            // //                                        .column( i )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //
            // //                                        }, 0 );
            // //                                    pageTotal = api
            // //                                        .column( i, { page: 'current'} )
            // //                                        .data()
            // //                                        .reduce( function (a, b) {
            // //                                            b = $(b).html() ? b : '<a>'+b+'</a>';
            // //                                            return intVal(removeCommas(a)) + intVal(removeCommas($(b).html()));
            // //                                        }, 0 );
            // //
            // //                                        if(parseFloat(pageTotal)>0 && i>0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                            $( api.column( i ).footer() ).addClass('text-right');
            // //                                            $( api.column( i ).footer() ).addClass('text-bold');
            // //                                        }
            // //                                        else if(parseFloat(pageTotal)==0){
            // //                                            $( api.column( i ).footer() ).html('' + addCommas(pageTotal) );
            // //                                        }else{
            // //
            // //                                        }
            // //                                })
            //                             }
            //                         });
            //                         $(\".table-responsive.main\").scroll(function () {
            //                             setTimeout(function () {
            //                                 $('#main').DataTable().fixedHeader.adjust();
            //                             }, 400);
            //                         });
            //                     </script>";


        }
        else {
            $content .= ("<div class=\"box-body\">");
            $content .= ("no report found.<br>");
            $content .= ("to go back to index, you can click BACK button<br>");
            $content .= ("</div class=\"box-body\">");
        }

        //endregion


        //        arrprint($totalV);

        if (is_array($addLink)) {
            $addLinkStr = "<a href='" . $addLink['link'] . "' class='btn btn-success'>" . $addLink['label'] . "</a>";
        }
        else {
            $addLinkStr = "";
        }
        $addLink = "";
        $p = New Layout("", "", "application/template/abReport.html");
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $content2 = "";
        $p->addTags(array(
            "jenisTr" => "",
            "trName" => "",
            "menu_left" => callMenuLeft(),
            //                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $content,
            // "content2"         => $content2,
            "content2" => "",
            "profile_name" => $this->session->login['nama'],
            "add_link" => $addLinkStr,
            "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
            "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            "nav_top" => $nav_top,
        ));
        $p->render();

        break;

    case "viewPembelians":
        // $thn = '2021';
        // $jml_bln = 4;

        // $getDate = "$thn-4";
        $regMonthYear = $getDate;

        /* ------------------------------
         * navigasi
         * ------------------------------*/
        $link_lap_vendor = base_url() . "ActivityReport/viewPembelian";
        $link_lap_produk = base_url() . "ActivityReport/viewPembelianProduk/$thn?date=$regMonthYear";
        $btn_data = "";
        $btn_data .= "<div class='col-md-4 pull-right'>";
        $btn_data .= "<div class='btn-group'>";
        // $btn_data .= "<button type='button' class='btn btn-danger text-uppercase' onclick=\"location.href='$link_lap_vendor'\">by vendor</button>";
        // $btn_data .= "<button type='button' class='btn btn-warning text-uppercase' onclick=\"location.href='$link_lap_produk'\">by produk</button>";
        $btn_data .= "<button type='button' class='btn btn-warning text-uppercase' onclick=\"document.getElementById('result').src = '$link_lap_produk'\"><i class='fa fa-download'></i> pembelian bulanan per produk $thn</button>";
        $btn_data .= "</div>";
        $btn_data .= "</div>";

        $datePicker = "<div class='row'>";
        $datePicker .= "<div class='col-md-2'>";
        //region date picker
        $datePicker .= "<form method='get'>";
        $datePicker .= "<div class='input-group date' id='datepicker'  data-date='$regMonthYear' data-date-format='yyyy'>";
        $datePicker .= "<div class='input-group-addon'>";
        $datePicker .= "<span class='add-on'><i class='fa fa-calendar' data-toggle='tooltip' data-placement='right' title='pilih bulan'></i></span>";
        $datePicker .= "</div>";
        $datePicker .= "<input type='text' autocomplete-off readonly class='form-control text-center' name='date' value='$regMonthYear'>";
        $datePicker .= "<span class='input-group-btn'>";
        $datePicker .= "<button type='submit' id='date_submit' style='display: none' class='btn btn-primary'><i class='fa fa-send-o'></i></button>";
        $datePicker .= "</span>";
        $datePicker .= "</div>";
        $datePicker .= "</form>";
        //endregion
        $datePicker .= "</div>";

        // if (ipadd() == "202.65.117.72") {
        $datePicker .= $btn_data;
        // }
        // else {
        //
        // }


        $datePicker .= "</div>";


        $p = New Layout("$title", "$subTitle", "application/template/reports.html");
        $headers = "";
        //region header satu
        $headers .= "<tr>";
        $headers .= "<th class='bg-info v-al' rowspan='2'>no</th>";
        $headers .= "<th class='bg-info' rowspan='2'>nama</th>";
        // $headers .= "<th colspan='12'>2021</th>";
        for ($i = 1; $i <= $jml_bln; $i++) {
            $headers .= "<th class='bg-info' colspan='3'>$thn-$i</th>";

        }
        $headers .= "<th class='bg-info' colspan='3'>sub total</th>";
        $headers .= "</tr>";
        //endregion

        //region header dua
        $headers .= "<tr class='bg-info'>";
        for ($i = 1; $i <= $jml_bln; $i++) {

            $headers .= "<th>beli</th>";
            $headers .= "<th>return</th>";
            $headers .= "<th>netto</th>";
        }

        $headers .= "<th>beli</th>";
        $headers .= "<th>return</th>";
        $headers .= "<th>netto</th>";
        $headers .= "</tr>";
        //endregion

        // $kolom = "suppliers_id";
        $trJenis = str_replace("=", "", blobEncode($trJenis));
        $trJenisContra = str_replace("=", "", blobEncode($trJenisContra));
        $bodies = "";
        $no = 0;
        foreach ($src_vendor as $item) {
            $no++;
            $vend_id = $item->id;
            $vend_nama = $item->nama;
            $bodies .= "<tr>";
            $bodies .= "<td class='text-right'>$no</td>";
            $bodies .= "<td title='$vend_id'>$vend_nama</td>";

            /* -----------------------
             * data utama
             * ----------------------*/
            $sub_nilai_p = 0;
            $sub_nilai_pr = 0;
            $sub_nilai_pn = 0;
            for ($i = 1; $i <= $jml_bln; $i++) {
                switch ($report_mode) {
                    case "on_the_fly":
                        $nilai_p = isset($src_pembelians[$vend_id][$thn][$i]->nilai_af) ? $src_pembelians[$vend_id][$thn][$i]->nilai_af : 0;
                        $nilai_pr = isset($src_pembelian_returns[$vend_id][$thn][$i]->nilai_af) ? $src_pembelian_returns[$vend_id][$thn][$i]->nilai_af : 0;
                        $nilai_pn = $nilai_p - $nilai_pr;
                        break;
                    case "db_report":
                        $nilai_p = isset($src_pembelians[$vend_id][$thn][$i]->$kolomNilai) ? $src_pembelians[$vend_id][$thn][$i]->$kolomNilai : 0;
                        $nilai_pr = isset($src_pembelians[$vend_id][$thn][$i]->$kolomNilaiContra) ? $src_pembelians[$vend_id][$thn][$i]->$kolomNilaiContra : 0;
                        $nilai_pn = $nilai_p - $nilai_pr;
                        break;
                }


                $nilai_p_f = formatField('debet', $nilai_p);
                $nilai_pr_f = formatField('debet', $nilai_pr);
                $nilai_pn_f = formatField('debet', $nilai_pn);

                $head_modal = strtoupper("$vend_nama ($thn/$i)");
                $link_tr = base_url() . "ActivityReport/viewDetile/$kolom/$vend_id/$trJenis/$thn/$i";
                $target = modalDialogBtn("$head_modal", $link_tr);
                $nilai_p_l = "<a href='javascript:void(0);' onclick=\"$target\">$nilai_p_f</a>";
                /* -----------------------
                 * pembelian
                 * ----------------------*/
                $bodies .= "<td>$nilai_p_l</td>";

                $link_tr = base_url() . "ActivityReport/viewDetile/$kolom/$vend_id/$trJenisContra/$thn/$i";
                $target = modalDialogBtn("$head_modal", $link_tr);
                $nilai_pr_l = "<a href='javascript:void(0);' onclick=\"$target\">$nilai_pr_f</a>";
                /* -----------------------
                 * pembelian return
                 * ----------------------*/
                $bodies .= "<td>$nilai_pr_l</td>";

                /* -----------------------
                 * pembelian netto
                 * ----------------------*/
                $bodies .= "<td class='bg-success'>$nilai_pn_f</td>";

                $sub_nilai_p += $nilai_p;
                $sub_nilai_pr += $nilai_pr;
                $sub_nilai_pn += $nilai_pn;

                if (!isset($total[$thn][$i]['pembelian'])) {
                    $total[$thn][$i]['pembelian'] = 0;
                }
                $total[$thn][$i]['pembelian'] += $nilai_p;
                if (!isset($total[$thn][$i]['pembelian_return'])) {
                    $total[$thn][$i]['pembelian_return'] = 0;
                }
                $total[$thn][$i]['pembelian_return'] += $nilai_pr;
                if (!isset($total[$thn][$i]['pembelian_netto'])) {
                    $total[$thn][$i]['pembelian_netto'] = 0;
                }
                $total[$thn][$i]['pembelian_netto'] += $nilai_pn;
            }

            /* -----------------------
             * summary kanan
             * ----------------------*/
            $sub_nilai_p_f = formatField('debet', $sub_nilai_p);
            $sub_nilai_pr_f = formatField('debet', $sub_nilai_pr);
            $sub_nilai_pn_f = formatField('debet', $sub_nilai_pn);
            /* -----------------------
             * pembelian
             * ----------------------*/
            $bodies .= "<td>$sub_nilai_p_f</td>";
            /* -----------------------
             * pembelian return
             * ----------------------*/
            $bodies .= "<td>$sub_nilai_pr_f</td>";
            /* -----------------------
             * pembelian netto
             * ----------------------*/
            $bodies .= "<td class='bg-success'>$sub_nilai_pn_f</td>";

            if (!isset($total[$thn]['sub']['pembelian'])) {
                $total[$thn]['sub']['pembelian'] = 0;
            }
            $total[$thn]['sub']['pembelian'] += $sub_nilai_p;
            if (!isset($total[$thn]['sub']['pembelian_return'])) {
                $total[$thn]['sub']['pembelian_return'] = 0;
            }
            $total[$thn]['sub']['pembelian_return'] += $sub_nilai_pr;
            if (!isset($total[$thn]['sub']['pembelian_netto'])) {
                $total[$thn]['sub']['pembelian_netto'] = 0;
            }
            $total[$thn]['sub']['pembelian_netto'] += $sub_nilai_pn;

            $bodies .= "</tr>";
        }

        // arrPrint($total);
        $footies = "";
        //region footer
        $footies .= "<tr class='bg-info'>";
        $footies .= "<th colspan='2' class='text-uppercase text-right'>total</th>";

        foreach ($total as $thn => $bln_items) {
            foreach ($bln_items as $bln_item) {

                $tpembelian = $bln_item['pembelian'];
                $tpembelian_return = $bln_item['pembelian_return'];
                $tpembelian_netto = $bln_item['pembelian_netto'];

                $total_pembelian_sub_f = formatField('debet', $tpembelian);
                $total_pembelian_return_sub_f = formatField('debet', $tpembelian_return);
                $total_pembelian_netto_sub_f = formatField('debet', $tpembelian_netto);

                $footies .= "<th>$total_pembelian_sub_f</th>";
                $footies .= "<th>$total_pembelian_return_sub_f</th>";
                $footies .= "<th class='bg-success'>$total_pembelian_netto_sub_f</th>";
            }
        }

        $footies .= "</tr>";
        //endregion

        $str_tbl = "";
        // $str_tbl .= "<div class='border-cek'>$thn</div>";
        $str_tbl .= "<div class='table-responsive'>";
        $str_tbl .= "<table id='dataTabel' class='table table-condensed table-bordered table-striped table-hover'>";
        $str_tbl .= "<thead>";
        $str_tbl .= $headers;
        $str_tbl .= "</thead>";
        $str_tbl .= "<tbody>";
        $str_tbl .= $bodies;
        $str_tbl .= "</tbody>";
        $str_tbl .= "<tfoot>";
        $str_tbl .= $footies;
        $str_tbl .= "</tfoot>";
        $str_tbl .= "</table>";
        $str_tbl .= "<script>
                    $(document).ready( function(){
                        var table = $('table#dataTabel').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: 50,
                            paging: true,
                            buttons: [
                                        {
                                            extend: 'print',
                                            footer: true,
                                            text: 'CETAK',
                                        },
                                        {
                                            extend: 'copyHtml5',
                                            footer: true,
                                            text: 'COPY',
                                        },
                                        {
                                            extend: 'csvHtml5',
                                            footer: true,
                                            text: 'CSV',
                                        },
                                        {
                                            extend: 'excelHtml5',
                                            footer: true,
                                            text: 'EXCEL',
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            footer: true,
                                            text: 'PDF',
                                        },
                                    ],
                            // buttons: [
                            //   {extend: 'print', footer: true },
                            //   {extend: 'excel', text: 'Excel',
                            //      exportOptions: {
                            //          modifier: {
                            //             page: 'current'
                            //          }
                            //      }
                            //   }
                            // ],


//                                     footerCallback: function ( row, data, start, end, display ) {
//                                                 var api = this.api(), data;
//
//                                                 // Remove the formatting to get integer data for summation
//                                                 var intVal = function ( i ) {
//                                                     return typeof i === 'string' ?
//                                                         i.replace(/[$,()]/g, '')*1 :
//                                                         typeof i === 'number' ?
//                                                             i : 0;
//                                                 };
//
//                                                 var arrayFooterTmp = $('tfoot>tr>th');
//                                                 var dpageTotal = [];
//
//                                                 var arrBlackList = [5,6,7,8,9,10];
// //                                                var arrColSpan = [0,1,2,3,4];
//                                                 var arrSaldo = [10];
//                                                 var arrSaldoQty = [9];
//
//                                                 // jQuery.each(arrayFooterTmp, function(i,d){
//                                                 //     if(arrBlackList.includes(i)){
//                                                 //         var id_n_index = parseFloat(i);
//                                                 //         dpageTotal[id_n_index] = 0;
//                                                 //         jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
//                                                 //
//                                                 //             obj = intVal(obj)>0 ? intVal(obj) : intVal($(obj).html())>0 ? intVal($(obj).html()) : 0;
//                                                 //
//                                                 //               dpageTotal[id_n_index] += intVal( obj );
//                                                 //
//                                                 //         });
//                                                 //         if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
//                                                 //             $( api.column(id_n_index).footer() ).html(
//                                                 //                \" < div class='text-right text-primary text-bold' > \"+addCommas(dpageTotal[id_n_index])+\" </div > \"
//                                                 //             );
//                                                 //         }else{
//                                                 //             $( api.column(id_n_index).footer() ).html(
//                                                 //                 \" < div class='text-right text-primary text-bold' > 0</div > \"
//                                                 //             );
//                                                 //         }
//                                                 //     }
//                                                 // });
//                                                 //
//
//        
//                                             }
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

        $str_tbl .= "</div>";

        $content = "";
        $content .= $datePicker;
        $content .= "<hr style='margin: 5px;'>";
        $content .= $str_tbl;

        $p->addTags(
            array(
                // "jenisTr" => $jenisTr,
                // "trName"=>$trName,
                "menu_left" => callMenuLeft(),
                // "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $content,
                // "profile_name" => $this->session->login['nama'],
                // // "add_link"           => $addLinkStr,
                // "newTrTarget" => isset($addLink['link']) ? $addLink['link'] : "javascript:void(0)",
                // "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            )
        );

        $p->render();
        break;
}