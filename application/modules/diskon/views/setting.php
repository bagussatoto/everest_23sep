<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "index":
        // cekHere();
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $content = "";
        $content .= "<style type='text/css'>
                .nav-tabs-custom > .nav-tabs > li.active a {
                    background-color: coral !important;
                }
        </style>";
        $loader_indikator = "<div class='loader-5 center'><span></span></div>";

        $link_viewProdukKategori = base_url() . "diskon/Setting/viewProdukKategori";
        $content_00 = "<div id='enol'>Loading Produk Kategori Diskon Data... <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $content_00 .= "<script>$('#enol').load('$link_viewProdukKategori');</script>";

        $link_viewProdukHarga = base_url() . "diskon/Setting/viewProdukHarga";
        $content_0 = "<div id='satu'>Loading Produk Harga dan Diskon Data... <span>$loader_indikator</span></div>";

        $content_0 .= " <script>
                            var fid = localStorage.getItem('fid');
                            var fkolom = localStorage.getItem('fkolom');
                            if(!fid){
                                $('#satu').load('$link_viewProdukHarga');
                            }
                            else {
                                $('#satu').load('$link_viewProdukHarga?f='+fkolom+'&v='+fid);
                            }
                        </script>";

        // $content_0 .= "<script>var loadProdukHarga = function(){ $('#satu').html(\"<div class='text-center text-bold'><img width='15%' src='//cdn.mayagrahakencana.com/assets/images/d60eb1v-79212624-e842-4e55-8d58-4ac7514ca8e4.gif'><br/><h3>MOHON TUNGGU, SYSTEM SEDANG MEMUAT DATA PRODUK...</h3></div>\").load('$link_viewProdukHarga') }; loadProdukHarga()</script>";

        $link_viewProdukRebate = base_url() . "diskon/Setting/viewProdukRebate";
        $content_03 = "<div id='satu-dua'>Loading Produk Rebate Data... <span>$loader_indikator</span></div>";
        $content_03 .= "<script>
                var fid = localStorage.getItem('fidr');
                var fkolom = localStorage.getItem('fkolomr');
                
                if(!fid){
                    $('#satu-dua').load('$link_viewProdukRebate');                 
                }
                else {                   
                    $('#satu-dua').load('$link_viewProdukRebate?f='+fkolom+'&v='+fid);
                }
                
            </script>";

        $link_viewMember = base_url() . "diskon/setting/viewMember";
        $member = "<div id='dua'>Loading Member Data...</div>";
        $member .= "<script>$('#dua').load('$link_viewMember');</script>";

        //viewCashBackMember
        $link_viewCashBackMember = base_url() . "diskon/setting/viewCashBackMember";
        $cashBack = "<div id='tiga'>Loading Cashback Member Data...</div>";
        $cashBack .= "<script>$('#tiga').load('$link_viewCashBackMember');</script>";

        $link_viewSupplier = base_url() . "diskon/Setting/viewSupplier";
        $content_02 = "<div id='satu-dua'>Loading Produk Kategori Diskon Data... <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $content_02 .= "<script>$('#satu-dua').load('$link_viewSupplier');</script>";

        //viewPointMember
        $link_viewPointMember = base_url() . "diskon/setting/viewPointMember";
        $point = "<div id='enam'>Loading Point Member Data...<div style='margin-left:50%;'>$loader_indikator</div></div>";
        $point .= "<script>$('#enam').load('$link_viewPointMember');</script>";
        // tebusmurah
        $link_viewTebusMurah = base_url() . "diskon/setting/viewTebusMurah";
        $link_viewTebusMurah = base_url() . "diskon/setting/viewUnvalable";
        $tebusmurah = "<div id='lima'>Loading Tebus murah Data...  <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $tebusmurah .= "<script>$('#lima').load('$link_viewTebusMurah');</script>";

        $link_viewDiskonFreeProduk = base_url() . "diskon/setting/viewDiskonFreeProduk";
        $freeproduk = "<div id='empat'>Loading Diskon Free Produk Data... $loader_indikator</div>";
        $freeproduk .= "<script>$('#empat').load('$link_viewDiskonFreeProduk');</script>";

        /*---------------TAB-TAB--------------‎‎*/
        $isi_tab = array();
        $isi_tab["kategori"] = array(
            "label" => "Produk Kategori diskon",
            // "active" => true,
            "data"  => $content_00,
            "css"   => "bg-aqua",
            "class" => "bg-aaaaa",
        );
        $isi_tab["produk"] = array(
            "label"  => "Produk Harga & diskon",
            // "active" => true,
            "data"   => $content_0,
            "css"    => "bg-aqua",
            "class"  => "bg-aaaaa",
        );
        $isi_tab["produk_rebate"] = array(
            "label"  => "Produk rebate",
            "active" => true,
            "data"   => $content_03,
            "css"    => "bg-aqua",
            "class"  => "bg-aaaaa",
        );
        // $isi_tab["supplier"] = array(
        //     "label"  => "Cadangan diskon",
        //     // "active" => true,
        //     "data"   => $content_02,
        //     "css"    => "bg-aqua",
        //     "class"  => "bg-aaaaa",
        // );
        $isi_tab["member"] = array(
            "label" => "member",
            // "active" => true,
            "data"  => $member,
            "css"   => "bg-aqua",
        );
        // $isi_tab["cashback"] = array(
        //     "label" => "cash Back",
        //     // "active" => true,
        //     "data"  => $cashBack,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["point"] = array(
        //     "label" => "point",
        //     // "active" => true,
        //     "data"  => $point,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["getfreeproduk"] = array(
        //     "label" => "<i class='fa fa-gift'></i>&nbsp;&nbsp;diskon free produk",
        //     // "active" => true,
        //     "data"  => $freeproduk,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["tebusmurah"] = array(
        //     "label" => "<i class='fa fa-gift'></i>&nbsp;&nbsp;tebus murah",
        //     // "active" => true,
        //     "data"  => $tebusmurah,
        //     "css"   => "bg-aqua",
        // );
        $content .= $p->layout_tabs($isi_tab);


        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "add_pihak"        => "",
                "pihak_label"      => "",
                "add_item"         => "",
                "selector_label"   => "",
                "tmp_request"      => "",
                "mobile_scan"      => "",
                "ext_tool"         => "",
                "submit_button"    => "",
                "content"          => $content,
            )
        );
        $p->render();
        break;
    case "index":
        // cekHere();
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $content = "";
        $content .= "<style type='text/css'>
                .nav-tabs-custom > .nav-tabs > li.active a {
                    background-color: coral !important;
                }
        </style>";
        $loader_indikator = "<div class='loader-5 center'><span></span></div>";

        $link_viewProdukKategori = base_url() . "diskon/Setting/viewProdukKategori";
        $content_00 = "<div id='enol'>Loading Produk Kategori Diskon Data... <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $content_00 .= "<script>$('#enol').load('$link_viewProdukKategori');</script>";

        $link_viewProdukHarga = base_url() . "diskon/Setting/viewProdukHarga";
        $content_0 = "<div id='satu'>Loading Produk Harga dan Diskon Data... <span>$loader_indikator</span></div>";

        $content_0 .= " <script>
                            var fid = localStorage.getItem('fid');
                            var fkolom = localStorage.getItem('fkolom');
                            if(!fid){
                                $('#satu').load('$link_viewProdukHarga');
                            }
                            else {
                                $('#satu').load('$link_viewProdukHarga?f='+fkolom+'&v='+fid);
                            }
                        </script>";

        // $content_0 .= "<script>var loadProdukHarga = function(){ $('#satu').html(\"<div class='text-center text-bold'><img width='15%' src='//cdn.mayagrahakencana.com/assets/images/d60eb1v-79212624-e842-4e55-8d58-4ac7514ca8e4.gif'><br/><h3>MOHON TUNGGU, SYSTEM SEDANG MEMUAT DATA PRODUK...</h3></div>\").load('$link_viewProdukHarga') }; loadProdukHarga()</script>";

        $link_viewProdukRebate = base_url() . "diskon/Setting/viewProdukRebate";
        $content_03 = "<div id='satu-dua'>Loading Produk Rebate Data... <span>$loader_indikator</span></div>";
        $content_03 .= "<script>
                var fid = localStorage.getItem('fidr');
                var fkolom = localStorage.getItem('fkolomr');
                
                if(!fid){
                    $('#satu-dua').load('$link_viewProdukRebate');                 
                }
                else {                   
                    $('#satu-dua').load('$link_viewProdukRebate?f='+fkolom+'&v='+fid);
                }
                
            </script>";

        $link_viewMember = base_url() . "diskon/setting/viewMember";
        $member = "<div id='dua'>Loading Member Data...</div>";
        $member .= "<script>$('#dua').load('$link_viewMember');</script>";

        //viewCashBackMember
        $link_viewCashBackMember = base_url() . "diskon/setting/viewCashBackMember";
        $cashBack = "<div id='tiga'>Loading Cashback Member Data...</div>";
        $cashBack .= "<script>$('#tiga').load('$link_viewCashBackMember');</script>";

        $link_viewSupplier = base_url() . "diskon/Setting/viewSupplier";
        $content_02 = "<div id='satu-dua'>Loading Produk Kategori Diskon Data... <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $content_02 .= "<script>$('#satu-dua').load('$link_viewSupplier');</script>";

        //viewPointMember
        $link_viewPointMember = base_url() . "diskon/setting/viewPointMember";
        $point = "<div id='enam'>Loading Point Member Data...<div style='margin-left:50%;'>$loader_indikator</div></div>";
        $point .= "<script>$('#enam').load('$link_viewPointMember');</script>";
        // tebusmurah
        $link_viewTebusMurah = base_url() . "diskon/setting/viewTebusMurah";
        $link_viewTebusMurah = base_url() . "diskon/setting/viewUnvalable";
        $tebusmurah = "<div id='lima'>Loading Tebus murah Data...  <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $tebusmurah .= "<script>$('#lima').load('$link_viewTebusMurah');</script>";

        $link_viewDiskonFreeProduk = base_url() . "diskon/setting/viewDiskonFreeProduk";
        $freeproduk = "<div id='empat'>Loading Diskon Free Produk Data... $loader_indikator</div>";
        $freeproduk .= "<script>$('#empat').load('$link_viewDiskonFreeProduk');</script>";

        /*---------------TAB-TAB--------------‎‎*/
        $isi_tab = array();
        $isi_tab["kategori"] = array(
            "label" => "Produk Kategori diskon",
            // "active" => true,
            "data"  => $content_00,
            "css"   => "bg-aqua",
            "class" => "bg-aaaaa",
        );
        $isi_tab["produk"] = array(
            "label"  => "Produk Harga & diskon",
            // "active" => true,
            "data"   => $content_0,
            "css"    => "bg-aqua",
            "class"  => "bg-aaaaa",
        );
        $isi_tab["produk_rebate"] = array(
            "label"  => "Produk rebate",
            "active" => true,
            "data"   => $content_03,
            "css"    => "bg-aqua",
            "class"  => "bg-aaaaa",
        );
        // $isi_tab["supplier"] = array(
        //     "label"  => "Cadangan diskon",
        //     // "active" => true,
        //     "data"   => $content_02,
        //     "css"    => "bg-aqua",
        //     "class"  => "bg-aaaaa",
        // );
        $isi_tab["member"] = array(
            "label" => "member",
            // "active" => true,
            "data"  => $member,
            "css"   => "bg-aqua",
        );
        // $isi_tab["cashback"] = array(
        //     "label" => "cash Back",
        //     // "active" => true,
        //     "data"  => $cashBack,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["point"] = array(
        //     "label" => "point",
        //     // "active" => true,
        //     "data"  => $point,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["getfreeproduk"] = array(
        //     "label" => "<i class='fa fa-gift'></i>&nbsp;&nbsp;diskon free produk",
        //     // "active" => true,
        //     "data"  => $freeproduk,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["tebusmurah"] = array(
        //     "label" => "<i class='fa fa-gift'></i>&nbsp;&nbsp;tebus murah",
        //     // "active" => true,
        //     "data"  => $tebusmurah,
        //     "css"   => "bg-aqua",
        // );
        $content .= $p->layout_tabs($isi_tab);


        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "add_pihak"        => "",
                "pihak_label"      => "",
                "add_item"         => "",
                "selector_label"   => "",
                "tmp_request"      => "",
                "mobile_scan"      => "",
                "ext_tool"         => "",
                "submit_button"    => "",
                "content"          => $content,
            )
        );
        $p->render();
        break;

    case "createForm":

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        //region baca atribut, keterangan dari config
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $jenisTr = isset($jenisTr) ? $jenisTr : "";
        $jenisTransaksi = isset($jenisTransaksi) ? $jenisTransaksi : "";
        $pihakCaller = isset($pihakCaller) ? $pihakCaller : "";
        $pihakCaller2 = isset($pihakCaller2) ? $pihakCaller2 : "";
        $selectorCaller = isset($selectorCaller) ? $selectorCaller : "";
        $selectorCaller2 = isset($selectorCaller2) ? $selectorCaller2 : "";
        $selectorCallerForm = ''; // link shopping_cart pilih multi item
        $pihakCallerDelete = isset($pihakCallerDelete) ? $pihakCallerDelete : "";
        $pihakLabel = isset($pihakLabel) ? $pihakLabel : 'pilih';
        $pihakLabel2 = isset($pihakLabel2) ? $pihakLabel2 : 'pilih';
        $selectorLabel = isset($selectorLabel) ? $selectorLabel : 'pilih';
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $template = isset($template) ? $template : "";
        $setSubmitButton = isset($setSubmitButton) ? $setSubmitButton : "LANJUTKAN";
        $submitLabel = "Continue " . $subTitle;
        //endregion baca atribut, keterangan dari config

        $p = New Layout("$title", "$subTitle", "$template");

        $strOnprog = "";
        $strOnprogFooter = "";

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($arrayOnProgress2) > 0) {
                //region onprogress2
                if (sizeof($arrayOnProgress2) > 0) {
                    $strOnprog .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    switch ($viewMode) {
                        case "list":
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                            $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                            if (sizeof($arrayProgress2Labels) > 0) {
                                foreach ($arrayProgress2Labels as $key => $label) {
                                    $strOnprog .= "<td class='text-muted'>";
                                    $strOnprog .= $label;
                                    $strOnprog .= "</td>";
                                }
                            }
                            $strOnprog .= "</tr>";
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<td>";
                                        $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                        $strOnprog .= "</td>";
                                    }
                                }
                                $strOnprog .= "</tr>";
                            }
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td class='alert alert-warning' colspan='" . sizeof($arrayProgress2Labels) . "' align='center'>to process <strong>by request</strong> entries, you need to clear the list above from selected items.</td>";
                                $strOnprog .= "</tr>";
                            }
                            else {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td colspan='" . sizeof($arrayProgress2Labels) . "' align='right'><button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\">followup as new $title</button></td>";
                                $strOnprog .= "</tr>";
                            }
                            $strOnprog .= "</table>";
                            break;
                        case "thumbnail":
                            $strOnprog .= "<div class='panel-body' style='background:#e5e5e0;border:2px #cccccc dashed;'>";
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered' cellspacing='4'>";
                            $strOnprog .= "<tr line=" . __LINE__ . ">";
                            $no = 0;
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $no++;
                                $strOnprog .= "<td bgcolor='#f0f0f0' align='center'>";
                                $strOnprog .= "<label for='select_" . $no . "'>";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<div class='text-center'>";
                                        $strVal = isset($val[$key]) ? ($val[$key]) : "";
                                        $strVal = is_numeric($strVal) ? number_format($strVal) : $strVal;
                                        $strOnprog .= $strVal;
                                        $strOnprog .= "</div>";
                                    }
                                }
                                $strOnprog .= "</label>";
                                $strOnprog .= "</td>";
                                if ($no % 5 == 0) {
                                    $strOnprog .= "</tr><tr line=" . __LINE__ . ">";
                                }
                            }
                            $strOnprog .= "</tr>";
                            $strOnprog .= "</table class='table table-condensed table-bordered no-padding'>";

                            $strOnprog .= "<div class='row'>";
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6'>";
                                $strOnprog .= "to process <strong>by request</strong> entries, you need to clear the list above from selected items.";
                                $strOnprog .= "</div>";
                            }
                            else {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6 text-right'>";
                                $strOnprog .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary btn-block' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup as new $title</button>";
                                $strOnprog .= "</div>";
                            }
                            $strOnprog .= "</div>";
                            $strOnprog .= "</div>";

                            break;
                    }

                    $strOnprog .= "</form>";
                }
                //endregion
            }

            if (sizeof($arrayOnProgress) > 0) {
                //region onprogress
                if (sizeof($arrayOnProgress) > 0) {
                    $strOnprog .= "<div class='panel-body'>";
                    $strOnprog .= "<h4>action needed #1</h4>";
                    $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    if (sizeof($arrayProgressLabels) > 0) {
                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<td class='text-muted'>";
                            $strOnprog .= $label;
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";

                    foreach ($arrayOnProgress as $key => $val) {
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        if (sizeof($arrayProgressLabels) > 0) {
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }

                    $strOnprog .= "</table>";
                    $strOnprog .= "<div class='text-right'>";
                    $strOnprog .= "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
                    $strOnprog .= "</div class='text-right'>";
                    $strOnprog .= "</div class='panel-body'>";


                }
                else {
                    if (isset($arrayOnProgress2) && sizeof($arrayOnProgress2) > 0) {
                        $strOnprog = "";
                        $strOnprogFooter = "";
                    }
                    else {
                        $strOnprog = "-the item you specified has no entry-";
                        $strOnprogFooter = "";
                    }

                }
                //endregion
            }

        }
        $uploadData = "";
        if (sizeof($uploadConfig) > 0) {
            $jenisTransaksi = $this->uri->segment(3);
            $labelUpload = $uploadConfig['label'];
            $uploadAction = base_url() . $uploadConfig['action'];
            $uploadData .= "<form id='uplodXls' method='post' enctype='multipart/form-data' action='$uploadAction' target='result'>";
            $uploadData .= "<input type='file' name='fileExcel' class='form-control'>";
            $uploadData .= "<input type='submit' value='upload' class='btn btn-primary'>";
            $uploadData .= "</form>";
            $uploadData .= "<script>
                    function insertItem(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        var totalProduk = parseFloat(Object.keys(dTemp).length);
                        top.$('#result').load('" . base_url() . "/Selectors/_processSelectProduct/selectNoQty/" . $jenisTransaksi . "?noview=1&id='+data.id+'&minValue=0', null, function(){
                            setTimeout( function(){ changeUnit(ls_urut, ls_concated) }, 1200);
                            var ls_urut_tt = parseFloat(totalProduk) - parseFloat(ls_urut);
                            if(parseFloat(ls_urut_tt) != parseFloat(totalProduk)){
                                top.$('#totalProduk').html(parseFloat(totalProduk));
                                top.$('#progressProduk').html(parseFloat(ls_urut_tt));
                                console.log('totalProduk: ' + totalProduk);
                                console.log('ls_urut_tt: ' + ls_urut_tt);
                            }
                            else if(parseFloat(ls_urut_tt) === parseFloat(totalProduk)){
                                top.$('#totalProduk').html(parseFloat(totalProduk));
                                top.$('#progressProduk').html(parseFloat(ls_urut_tt));
                                HoldOn.close();
                                swal('selesai upload '+parseFloat(totalProduk)+' PRODUK, silahkan diperiksa kembali sebelum disimpan')
                                window.location.reload();
                                console.log('selesai');
                                console.error('totalProduk: ' + totalProduk);
                                console.error('ls_urut_tt: ' + ls_urut_tt);
                            }
                            else{
                                console.log('selesai **');
                            }
                        })
                    }
                    function changeUnit(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        top.$('#result').load('" . base_url() . "/Selectors/_processSelectProduct/selectNoQty/" . $jenisTransaksi . "?noview=1&id='+data.id+'&newQty=&qty_opname='+data.qty, null, function(){
                            rolling(ls_urut, ls_concated);
                        })
                    }
                    function rolling(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        var rl_ls_urut = (ls_urut-1);
                        if(rl_ls_urut>=0){
                            setTimeout( function(){ insertItem(rl_ls_urut, ls_concated) }, 500);
                        }
                        else{
                        }
                    }

$('#uplodXls').on('submit',function() {
    localStorage.clear();
    var setInt= setInterval(function() {
    var arrProduk = JSON.parse(localStorage.getItem('items'));
        if(null!=arrProduk){
            var options = {
                theme:\"custom\",
                // If theme == \"custom\" , the content option will be available to customize the logo
                content:'<img style=\"width:80px;\" src=\"https://www.google.de/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png\" class=\"center-block\">',
                message:' <h4>SEDANG PROSES UPLOAD PRODUK<br>MOHON UNTUK TIDAK MEREFRESH BROWSER ANDA.</h4><br><br><h1>PROGRESS... <span class=\"text-bold text-red\" id=\"progressProduk\"></span> Produk, DARI TOTAL <span id=\"totalProduk\" class=\"text-bold text-orange\"></span> PRODUK </h1><br> <input type=\"button\" value=\"Close this Cover\" onclick=\"HoldOn.close();\">',
                backgroundColor:\"#1847B1\",
                textColor:\"white\"
            };
            top.HoldOn.open(options);
            clearInterval(setInt);
            var arrProduk = JSON.parse(localStorage.getItem('items'));
            var totalProduk = Object.keys(arrProduk).length;
            var urut = 1;
            var concated = [];
            var arrays = [];
            arrProduk = Object.keys(arrProduk).map(function(k){
                arrProduk[k] = arrProduk[k]
                arrProduk[k]['key'] = k*1
                if(arrProduk[k]['id']*1>0){
                    return arrProduk[k]
                }
            });
            arrProduk.sort(function (a, b) {
                return a.key*1 - b.key*1;
            });
            jQuery.each(arrProduk, function(id,data){
                arrays = data;
                arrays['id'] = data.id;
                concated[data.key] = arrays;
                urut++;
            });
            concated = concated.reverse()
            localStorage.setItem('urut', '');
            localStorage.setItem('concat', '');
            localStorage.setItem('urut', (urut-2));
            localStorage.setItem('concat', JSON.stringify(concated).replace('null,', '') );
            var ls_urut = localStorage.getItem('urut');
            var ls_concated = localStorage.getItem('concat');
            insertItem(ls_urut, ls_concated);
        }
    },500);
})";

            $uploadData .= "</script>";

        }

        //region onprogressView Doank
        $strOnprogView = "";
        if (is_array($arrayOnProgressView) && sizeof($arrayOnProgressView) > 0) {
            $strOnprogView .= "<table class='table table-condensed table-bordered no-padding'>";
            $strOnprogView .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($stepHistoryFields) > 0) {
                foreach ($stepHistoryFields as $key => $label) {
                    $strOnprogView .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strOnprogView .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strOnprogView .= $label;
                    }
                    $strOnprogView .= "</td>";
                }
            }
            $strOnprogView .= "</tr>";
            foreach ($arrayOnProgressView as $key => $val) {
                $strOnprogView .= "<tr line=" . __LINE__ . ">";
                if (sizeof($stepHistoryFields) > 0) {
                    foreach ($stepHistoryFields as $key => $label) {
                        $strOnprogView .= "<td>";
                        $strOnprogView .= isset($val[$key]) ? $val[$key] : "";
                        $strOnprogView .= "</td>";
                    }
                }
                $strOnprogView .= "</tr>";
            }
            $strOnprogView .= "</table>";
            //            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";

            $onpropDisplayView = "block";
        }
        else {
            $strOnprogView .= "-the item you specified has no entry-";
            $strOnprogFooter = "";
            $onpropDisplayView = "none";
        }
        //endregion


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table class='table table-condensed table-bordered no-padding'>";

            $strHist .= "<tr bgcolor='#f0f0f0'>";
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

            foreach ($arrayHistory as $key => $val) {
                // print_r($val);
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


            $strHist .= "</table>";

            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

        //        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
        //            $propDisplay = "block";
        //            $altDisplay = "none";
        //        } else {
        //            $propDisplay = "none";
        //            $altDisplay = "block";
        //        }

        $propDisplay = "block";
        $altDisplay = "none";

        //        cekkuning($strOnprog);die();
        //        die("allowTmpSave:".$allowTmpSave);

        if (isset($barcodeSettings['srcModel'])) {
            $barcodeProcessor = "document.getElementById('result').src='" . base_url() . "Addons/BarcodeReader/readCode?jenisTr=$jenisTr&srcModel=" . $barcodeSettings['srcModel'] . "&srcColumn=" . $barcodeSettings['srcColumn'] . "&proc=" . blobEncode($selectorProcessor) . "&code='+this.value;";
        }
        else {
            $barcodeProcessor = "return false;";
        }
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(
            array(
                "error_msg"                      => $error,
                "alt_display"                    => $altDisplay,
                "modeedit"                       => $modeedit,
                "modeeditopt"                    => "$modeeditopt",
                "prop_display"                   => $propDisplay,
                "tmpsave_display"                => $allowTmpSave == true ? "block" : "none",
                "menu_left"                      => callMenuLeft(),
                "trans_menu"                     => callTransMenu(),
                "float_menu_atas"                => callFloatMenu('atas'),
                "float_menu_bawah"               => callFloatMenu(),
                "menu_taskbar"                   => callMenuTaskbar(),
                "btn_back"                       => callBackNav(),
                "jenisTr"                        => $jenisTr . $str_group,
                "trName"                         => $trName,
                "pihak_caller"                   => $pihakCaller,
                "pihak_caller2"                  => $pihakCaller2,
                "pihak_caller_rules"             => $pihakMainCallerRules,
                "pihak_caller3"                  => $pihakCaller3,
                "pihak_callerExtern"             => $pihakExternCaller,
                "selector_caller"                => $selectorCaller,
                "selector_callerExtern"          => $pihakExternCaller,
                "selector_caller2"               => isset($selectorCaller2) ? $selectorCaller2 : "",
                "selector_caller_rules"          => isset($selectorCalleRules) ? $selectorCallerRules : "",
                "selector_caller3"               => isset($selectorCaller3) ? $selectorCaller3 : "",
                "pihak_caller_delete"            => $pihakCallerDelete,
                "pihak_main_caller_delete"       => $pihakMainCallerDelete,
                "pihak_main_caller_rules_delete" => $pihakMainCallerRulesDelete,
                "selector_caller_form"           => $selectorCallerForm,
                "pihak_label"                    => $pihakLabel,
                "pihak_label2"                   => isset($pihakLabel2) ? $pihakLabel2 : "",
                "pihak_label3"                   => isset($pihakLabel3) ? $pihakLabel3 : "",
                "selector_label"                 => $selectorLabel,
                "selector_label2"                => isset($selectorLabel2) ? $selectorLabel2 : "",
                "selector_rules_label"           => isset($selectorLabelRules) ? $selectorLabelRules : "",
                "selector_label3"                => isset($selectorLabel3) ? $selectorLabel3 : "",
                "submit_button"                  => $submitLabel,
                "pihak_main_label"               => $pihakMainLabel,
                "pihak_rules_label"              => $pihakMainLabelRules,
                "pihak_main_caller"              => $pihakMainCaller,
                "pihakExternLabel"               => $pihakExternLabel,
                //                "clear_shopping_cart" => $setClearShoppingCart,
                //                "action_shopping_cart" => $setActionShoppingCart,
                "onprogress_content"             => $strOnprog,
                "onprogress_footer"              => $strOnprogFooter,
                "history_content"                => $strHist,
                "history_footer"                 => $strHistFooter,
                //                "payment_str"          => $strPaymentMethod,
                "ext_tool"                       => $extTool,
                "column_recorder"                => $columnRecorderTarget,
                "default_description"            => $defaultDescription,
                "profile_name"                   => $this->session->login['nama'],
                "add_pihak"                      => $addPihakStr,
                "add_pihak_rules"                => (isset($addPihakRulesStr) ? $addPihakRulesStr : ""),
                "add_item"                       => $addItemStr,
                "this_page"                      => $thisPage,
                "view_mode_switch"               => $viewModeSwitch,
                "barcode_action"                 => $barcodeProcessor,
                "mobile_scan"                    => $isMobile ? $mobScanStr : "",
                "newTrTarget"                    => isset($addLink['link']) ? $addLink['link'] . $str_group : "JavaScript:void(0)",
                "newTrDisp"                      => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom"                   => isset($scriptBottom) ? $scriptBottom : "",

                "onprogressView_title"    => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content"  => $strOnprogView,
                "onprop_display_view"     => $onpropDisplayView,
                "globalTemplate"          => $globalTemplate,
                "upload_item"             => "$uploadData",

            )
        );

        $p->render();
        break;

    case "viewProdukHarga_1":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");

        $headerTop = array();
        foreach ($arrHeaders as $kolom => $arrHeader) {
            if (isset($arrHeader['span_header'])) {
                $headerTop[$kolom] = $arrHeader;
            }
        }
        // arrPrintKuning($headerTop);
        //region prduk
        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-danger'>";
        $strHead .= "<td rowspan='2'>no</td>";
        foreach ($headerTop as $ktop => $kparams) {
            $klabel = isset($kparams['label']) ? $kparams['label'] : $ktop;

            $strHead .= "<th rowspan='2'>$klabel</th>";
        }
        // $strHead .= "<td rowspan='2'>pid</td>";
        // $strHead .= "<th rowspan='2'>grosir</th>";
        // $strHead .= "<th rowspan='2'>barcode</th>";
        // $strHead .= "<th rowspan='2'>produk</th>";
        // $strHead .= "<th rowspan='2'>hargaList</th>";
        $strHead .= "<td colspan='1'>pembelian</td>";
        $strHead .= "<td colspan='5'>penjualan</td>";
        $strHead .= "</tr>";
        $strHead .= "<tr class='bg-danger'>";

        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hAttr = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";
            $hSpan = isset($arrHeader['span_header']) ? $arrHeader['span_header'] : "";
            if (!isset($arrHeader['span_header'])) {
                $strHead .= "<th $hAttr title='$kolom'>$hLabel</th>";
            }
        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $row_id = 999;
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";

        foreach ($master_data as $master_datum) {
            $row_id++;
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                $td_id = $kolom . "_" . $row_id;
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                if (isset($attrs['links'])) {
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
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
                //                 $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;

                //                if($kolom=="grosir"){
                $strBody .= "<td $attr id='$td_id' data-column='$kolom' data-order=''>$nilai_link</td>";
                //                }
                //                else{
                //                    $strBody .= "<td $attr id='$td_id' data-column='$kolom' data-order='$nilai'>$nilai_link</td>";
                //                }

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

        //        arrPrint($strBody);
        //        matiHere();

        $tbl_id = "data_ok";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th, .table>tfoot>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th, .table>tfoot>tr>td {
                            padding : 3px 10px !important;
                        }
                        .form-control {
                            height: 20px;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div classs='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<div class=''></div>";
        $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx' id='$tbl_id'>";
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
        $strTbl .= "<div id='anu'></div>";
        //$link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);
        $strTbl .= "<script>                

                function penghitung(){
                    // console.log('penghitung berhasil dijalankan');
                }

                // $('.wrapper').prepend(\"<div id='overlay' class='overlay' style='display: block;'></div>\")


                $('.btn-satuan').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('viewSatuan?id='+pid),
                        title: 'Satuan '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                $('.btn-grosir').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('viewGrosir?id='+pid),
                        title: 'Grosir '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                $('.btn-hadiah').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('viewHadiah?id='+pid),
                        title: 'Hadiah '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                $('.btn-scheduler').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('viewScheduler?id='+pid),
                        title: 'Scheduler '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    });
                });

                $(document).ready( setTimeout( function(){

                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        var nilai ='';
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"width: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview = top.$('table#$tbl_id').DataTable({
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
//                                        $('#overlay').remove()
                                            },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    processing: true,

//                                    columnDefs: [{
//                                            targets: [0],
//                                            orderable: false,
//                                            width: 5
//                                        },
//                                        {
//                                            render: function (data, type, row) {
//                                                return data;
//                                            },
//                                            targets: [1],
//                                            data: 'nama',
//                                            className: 'text-left nowrap',
//                                            width: 200,
//                                        },
//                                        {
////                                            render: function (data, type, row) {
////                                                datas = $(data).val();
////                                                return \"<input class='form-control text-right' value='\"+datas+\"'>\";
////                                            },
//                                            targets: [3],
//                                            data: 'harga_list',
//                                            className: 'text-right',
//                                            width: 50,
//                                        },
//                                        {
//                                            targets: [6],
//                                            className: 'text-left',
//                                            width: 200,
//                                        },
//                                        {
//                                            render: function (data, type, row) {
//                                                var tmb_select = \"<span onclick=`pilihMember('`+data+`');swal('kamu memilih `+row[1]+`')` class='btn btn-sm btn-flat btn-info'>PILIH</span>\"
//                                                return tmb_select;
//                                            },
//                                            targets: [11],
//                                            className: 'text-center'
//                                        }
//                                    ],

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
                            ],
                                    
                            drawCallback: function( settings ) {
                                //bikin penghitung
                                // console.log('table berubah');
                                // console.log('menjalankan penghitung');
                                top.penghitung();

                                var arrForm = top.$('input.form-edit');
                                var arrBtn  = top.$('button.tombol-action');

                                // console.log( arrForm );
                                // console.log( arrBtn  );
                            }

                                        });
                    
                                    $('.table-responsive.tblid_$tbl_id').floatingScroll();
                                        $('.table-responsive.tblid_$tbl_id').scroll(
//                            delay_v2(function () {
//                                $('table#$tbl_id').DataTable().fixedHeader.adjust();
//                            }, 200)
                                        );
                    
                                    }, 500));                
                </script>";

        /* ---------------------------------------------------------------------------------------------
         * penampil di browser ROW DATA perproduk transaksi
         * ---------------------------------------------------------------------------------------------*/
        // $p->setLayoutBoxCss("box-info");
        // $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        // $p->setLayoutBoxHeading("$subTitle", $btn_colaps);
        // $p->setLayoutBoxBody(true);
        // $content_0 = $p->layout_box($strTbl);
        // $content_0 .= "<div id='anu'></div>";
        //endregion

        echo $strTbl;
        break;

    case "viewProdukHarga":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");

        $headerTop = array();
        foreach ($arrHeaders as $kolom => $arrHeader) {
            if (isset($arrHeader['span_header'])) {
                $headerTop[$kolom] = $arrHeader;
            }

            if (isset($arrHeader['top_parent'])) {
                $top_parent = $arrHeader['top_parent'];
                if (!isset($headerTop_ky[$top_parent])) {

                    $headerTop_ky[$top_parent] = 0;
                }
                $headerTop_ky[$top_parent] += 1;
            }

            if (isset($arrHeader['sub_top_parent'])) {
                $top_parent = $arrHeader['sub_top_parent'];
                if (!isset($headerSubTop_ky[$top_parent])) {

                    $headerSubTop_ky[$top_parent] = 0;
                }
                $headerSubTop_ky[$top_parent] += 1;
            }
        }
        // arrPrintKuning($headerTop_ky);
        // arrPrintHijau($headerSubTop_ky);
        //region prduk

        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-danger' line='" . __LINE__ . "'>";
        $strHead .= "<td rowspan='2'>no</td>";
        foreach ($headerTop as $ktop => $kparams) {
            $klabel = isset($kparams['label']) ? $kparams['label'] : $ktop;

            $strHead .= "<th rowspan='2'>$klabel</th>";
        }
        // --------------- parent header ---------------------
        foreach ($headerTop_ky as $parent_ky => $top_jml) {
            $arrHeaderParent = $arrHeaderParents[$parent_ky];
            // foreach ($arrHeaderParents as $parent_ky => $arrHeaderParent) {
            $pLabel = isset($arrHeaderParent["label"]) ? $arrHeaderParent["label"] : $parent_ky;
            $pAttrHeader = isset($arrHeaderParent["attr_header"]) ? $arrHeaderParent["attr_header"] : "";
            $colspan_parent = $headerTop_ky[$parent_ky];

            $strHead .= "<td colspan='$colspan_parent' $pAttrHeader>$pLabel</td>";
        }
        // --------------- grosir berjenjang  ---------------------------
        // $colspan_grosir = isset($headerTop_ky["grosir"]) ? $headerTop_ky["grosir"] : 0;
        // if ($colspan_grosir > 0) {
        //     $strHead .= "<td colspan='$colspan_grosir' rowspan='1' class='bg-success'>diskon penjualan berjenjang</td>";
        // }
        // --------------- button ---------------------------
        $strHead .= "<td rowspan='1'>button</td>";

        $strHead .= "</tr>";

        /*---subhead_1----*/
        // $strHead .= "<tr class='bg-danger'>";
        // // $strHead .= "<td colspan='1' class='bg-blue'>hl reseller</td>";
        // $strHead .= "<td colspan='2' class='bg-aqua'>harga list</td>";
        //
        //
        // $strHead .= "<td colspan='2' class='bg-purple'>diskon</td>";
        // $strHead .= "<td colspan='2' class='bg-teal'>premi</td>";
        // $strHead .= "<td colspan='1' class='bg-danger'></td>";
        // $strHead .= "<td colspan='1' class='bg-danger'></td>";
        //
        // for ($i = 1; $i <= 3; $i++) {
        //     // $strHead .= "<td colspan='1' class='bg-success'></td>";
        // }
        //
        // // $strHead .= "<td colspan='1' class='bg-danger'></td>";
        // $strHead .= "</tr>";

        /*---subhead_2----*/
        $strHead .= "<tr class='bg-danger'>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hAttr = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";
            $hSpan = isset($arrHeader['span_header']) ? $arrHeader['span_header'] : "";
            if (!isset($arrHeader['span_header'])) {
                $strHead .= "<th $hAttr title='$kolom'>$hLabel</th>";
            }
        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $row_id = 999;
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";

        //        arrPrint($master_data);
        foreach ($master_data as $master_datum) {
            $row_id++;
            $no++;
            $diskon_cek = $master_datum['diskon_cek'];

            $color_cek = "";
            if ($diskon_cek == 1) {

                // $color_cek = "style='color: red';";
            }
            else {
                // $color_cek = "style='color: blue';";
                // $color_cek = "";
            }

            $strBody .= "<tr id='tr_$row_id'>";
            $strBody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                $td_id = $kolom . "_" . $row_id;
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $data_order = isset($attrs['data_order']) && ($attrs['data_order'] == false) ? "" : ((isset($attrs['data_order']) && ($attrs['data_order'] != false)) ? $master_datum[$attrs['data_order']] : (isset($master_datum[$kolom]) ? $master_datum[$kolom] : '0'));

                if (isset($attrs['links'])) {
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    $nilai_link = $nilai_f;
                }

                $strBody .= "<td $color_cek $attr id='$td_id' data-row='$row_id' data-column='$kolom' data-order='$data_order' title='$kolom'> $nilai_link</td>";;

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

        //        arrPrint($strBody);
        //        matiHere();

        $tbl_id = "data_ok";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th, .table>tfoot>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th, .table>tfoot>tr>td {
                            padding : 3px 10px !important;
                        }
                        .form-control {
                            height: 20px;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        /* --------------------------------------------------
         * aler legenda kolom keterangan
         * --------------------------------------------------*/
        $strTbl .= "<div class='alert alert-warning' id='alert_legenda'>";
        $strTbl .= "<button type='button' id='close_alert' class='close' data-show='alert' aria-hidden='true'>x</button>";
        $strTbl .= "<h4>Legenda:</h4>";
        $strTbl .= "<div class='text-uppercase'>";
        $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>DPP</b> : Hraga list - diskon 0 | ";
        $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>DISKON/KOMPONEN PEMBENTUK HARGA TANDAS</b> 
                    <i class='fa fa-stop' style='color: #69dc39;'></i> Nilai diskon";
        $strTbl .= "<i class='fa fa-stop' style='color: #ffeb3b;'></i> Nilai diskon setelah PPH23 | ";
        $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>harga tandas w/o ppn</b> : HPP w/o. PPN (dpp) - total rebate st. pph23";
        $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>harga tandas inc. ppn</b> : HPP inc. PPN - total rebate st. pph23<br>";
        $strTbl .= "</div>";
        $strTbl .= "</div>";
        // ----------------------------end alert
        if ($is_po == false) {
            $strTbl .= "<div class='panel no-margin'>";
            $strTbl .= "Pilih Merek <i class='blink fa fa-angle-right text-red'></i> <select class='btn btn-info' id='filter_kolom' onchange=\"loadFilter('merek_id',this.value);\">";
            $strTbl .= "<option >---pilih merek---</option>";
            $strTbl .= "<option value='null'>semua merek</option>";
            $strTbl .= "<option value='0'>no merek</option>";

            foreach ($srcMereks as $mr_id => $srcMerek) {
                $mr_nama = $srcMerek->nama;
                $strTbl .= "<option value='$mr_id' mrnama='$mr_nama'>$mr_nama</option>";
            }
            $strTbl .= "</select>";
            $strTbl .= "</div>";
        }
        $strTbl .= "<div classs='table-responsivex xxtblid_$tbl_id'>";
        //        $strTbl .= "<div class=''>================================================</div>";
        if ($is_po == true) {
            $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx'>";
        }
        else {

            $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx' id='$tbl_id'>";
        }
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
        $strTbl .= "<div id='anu'></div>";
        //$link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);

        // $link_update =
        if (isset($cCode) && ($cCode != "")) {
            //            cekHitam($urlBack);
            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian&cCode=$cCode&urlBack=$urlBack";
        }
        else {
            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian";
        }
        $link_gerbangBuilder = base_url() . "diskon/Setting/iterasiGerbangItem/_tr_466";
        $link_update_hrg_list = base_url() . "diskon/Setting/do_update?ky=diskon_persen";
        $link_update_premi_jual = base_url() . "diskon/Setting/do_update?ky=premi_persen";
        $link_update_harga_jual = base_url() . "diskon/Setting/do_update?ky=jual_bawah";
        $this_domain = base_url();
        $strTbl .= "<script>
            function trigger_nilai(x,y,z,a) {
                console.log('x:', x);
                console.log('y:', y);
                console.log('z:', z);
                console.log('a:', a);
                
                var pph23 = '$pph23';
                var ppn = '$ppn';
                // var hrg_list = $('#harga_list_' + z).val();
                var hrg_list = $('#harga_list_reseller_' + z).val();
                
                var diskon_persen = $('#diskon_persen_' + z);
                var diskon_nilai = $('#diskon_nilai_' + z);
                var premi_persen = $('#premi_jual_' + z);
                var premi_nilai = $('#premi_jual_nilai_' + z);                
                var harga_aft = $('#harga_aft_' + a);
                var hpp_nppn = $('#harga_beli_af_tax_' + z);
                
                var new_diskon_persen = (y / hrg_list) * 100;
                var new_diskon_nilai = (y / 100) * hrg_list;
                var new_harga_aft_diskon = hrg_list - new_diskon_nilai;
                // ---------------------
                var new_premi_persen = (y / hrg_list) * 100;
                var new_premi_nilai = (y / 100) * hrg_list;
                var new_harga_aft_premi = (hrg_list * 1) + Number(new_premi_nilai);
                
                // console.log('hlist: ' + hrg_list + ' nY:' + y);
                // console.log(new_harga_aft_premi);
                // console.log(new_diskon_persen);
               // console.log(x + ' :td id: ' + a + ' potongan :' + new_harga_aft_diskon);
                let diskon_0_persen = removeCommas($('#diskon_0_persen_' + z).val());
                let diskon_0_nilai = 0;
                let su = 0;
                let new_dpp = 0;
                let dpp_berjalan = false;
                let harga_beli_be_tax = 0;
                switch (x) {
                  case 'diskon_nilai':                                                                                                      
                        // premi_persen.val(0).trigger('blur').prop('disabled', true);
                        premi_persen.val(0).prop('disabled', true).load('$link_update_premi_jual&id='+ z +'&nilai=0');
                        harga_aft.text(new_harga_aft_diskon);  
                        diskon_persen.val(new_diskon_persen.toFixed(2)).trigger('blur');  
                        if(y == 0){
                            premi_persen.prop('disabled', false);
                            premi_nilai.prop('disabled', false);
                        }
                      break;
                  case 'diskon_persen':                                                
                        diskon_nilai.val(RoundTo(new_diskon_nilai, 100));
                        harga_aft.text(new_harga_aft_diskon);
                        premi_nilai.val(0).prop('disabled', true);
                        premi_persen.val(0).prop('disabled', true).load('$link_update_premi_jual&id=' + z + '&nilai=0');
                        if(y == 0){
                            premi_persen.prop('disabled', false);
                            premi_nilai.prop('disabled', false);
                        }
                      break;
                  // ------------------------
                  case 'premi_jual_nilai':
                        diskon_persen.val(0).prop('disabled', true).load('$link_update_hrg_list&id='+ z +'&nilai=0');
                        harga_aft.text(new_harga_aft_premi);
                        premi_persen.val(new_premi_persen.toFixed(2)).trigger('blur');
                        if(y == 0){
                            diskon_persen.prop('disabled', false);
                            diskon_nilai.prop('disabled', false);
                        }
                     break;
                  case 'premi_jual':                                                   
                        premi_nilai.val(RoundTo(new_premi_nilai,100));
                        harga_aft.text(new_harga_aft_premi);
                        diskon_nilai.val(0).prop('disabled', true);
                        diskon_persen.val(0).prop('disabled', true).load('$link_update_hrg_list&id='+ z +'&nilai=0');
                        if(y == 0){
                            diskon_persen.prop('disabled', false);
                            diskon_nilai.prop('disabled', false);
                        }
                     break;
                  // ----------------------
                  case 'hpp_supplier':
                      dpp_berjalan = false;
                      let harga_beli_list = $('#harga_beli_0_' + z).val();
                      var hpp_nppn_new = (ppn/100 * y) + Number(y);
                      $(hpp_nppn).val(addCommas(hpp_nppn_new));
                      
                      diskon_0_nilai = diskon_0_persen / 100 * harga_beli_list;
                      harga_beli_be_tax = harga_beli_list - diskon_0_nilai;
                     
                      var summDiskon = 0;   
                      su =0;
                      new_dpp = harga_beli_be_tax;
                      for (var i = 1; i <= 7; i++) {
                           let pu = $('#diskon_'+ i + '_persen_' + z).val();
                           let sube = $('#diskon_'+ i + '_nilai_' + z).val();
                           let dpp = $('#diskon_'+ i + '_dpp_' + z).val();
                          
                           if(dpp_berjalan === true){
                                if(i == 1){   
                                   su = pu /100 * hpp_nppn_new;
                                   new_dpp = hpp_nppn_new - su;
                                }
                                else {
                                    su = pu /100 * new_dpp;
                                    new_dpp = new_dpp - su;
                                }   
                           }
                           else {
                                  console.log('dpp_berjalan:', dpp_berjalan);
                                  su = pu /100 * harga_beli_be_tax;
                                  new_dpp = harga_beli_be_tax - su;
                           }
                                                      
                           // console.log('i:', i);                           
                           // console.log('harga_beli_be_tax:', harga_beli_be_tax);
                           // console.log('pu:', pu);
                           // console.log('su:', su);
                           // console.log('dpp:', dpp);
                           // console.log('new_dpp:', new_dpp);

                           $('#diskon_'+ i + '_nilai_' + z ).val(Math.round(su));
                           if(su != sube){
                                $('#anu').load('$link_update_diskon_pembelian&nilai=' + pu + '&nilaidk=' + su + '&id='+ z + '&basik=' + new_dpp + '&jenis=diskon_' + i + '&diskonid=' + i).load('$link_gerbangBuilder');
                           }
                           summDiskon += parseFloat(su);
                      }
                      

                      console.log('summDiskon:', summDiskon)
                      console.log('harga_beli_list:', harga_beli_list)
                      
                        // let diskon_0_persen = removeCommas($('#diskon_0_persen_' + z).val());
                        // let diskon_0_nilai = diskon_0_persen / 100 * harga_beli_list;
                        // let harga_beli_be_tax = harga_beli_list - diskon_0_nilai;
                        let harga_beli_af_tax = harga_beli_be_tax * ((100 + Number(ppn)) / 100);
                        let pph23_nilai = pph23 / 100 * summDiskon;
                        let total_nilai_dp_af_tax = summDiskon - pph23_nilai;
                        let harga_beline = harga_beli_be_tax*1 - (total_nilai_dp_af_tax*1);
                        let harga_beline_tax = harga_beline * ((100 + Number(ppn)) / 100);
                        
                        console.log('diskon_0_nilai: ', diskon_0_nilai);
                        console.log('pph23_nilai: ', pph23_nilai);
                        console.log('harga_beli_be_tax: ', harga_beli_be_tax);
                        console.log('harga_beli_af_tax: ', harga_beli_af_tax);
    
                        $('#diskon_0_nilai_'+ z ).val(addCommas(Math.round(diskon_0_nilai)));
                        $('#harga_beli_be_tax_'+ z ).val(harga_beli_be_tax);
                        $('#harga_beli_af_tax_'+ z ).val(harga_beli_af_tax.toFixed(0));
                        $('#total_nilai_dp_'+ a ).html(addCommas(Math.round(summDiskon)) );
                        $('#harga_pajak_beline_'+ a ).html(addCommas(Math.round(pph23_nilai)) );
                        $('#total_nilai_dp_af_tax_'+ a ).html(addCommas(Math.round(total_nilai_dp_af_tax)) );                        
                        $('#harga_beline_'+ a ).html(addCommas(Math.round(harga_beline)) );
                        $('#harga_beline_af_tax_'+ a ).html(addCommas(Math.round(harga_beline_tax)));

                     break;
                  case 'hpp_supplier_0':
                      diskon_0_nilai = $('#diskon_0_nilai_' + z) . val();
                      let harga_beli_0 = y * (100 / (100 - diskon_0_persen)); 
                      
                      console.log('diskon_0_nilai: ', diskon_0_nilai);
                                                                  
                      $('#harga_beli_0_' + z). val(Math.round(harga_beli_0)).trigger('blur');
                      break;
                  case 'hpp_nppn_supplier':
                      var hpp_new = y*100/(100+Number(ppn));
                      $('#harga_beli_be_tax_'+z).val(addCommas(hpp_new)).trigger('blur');
                        console.log('hpp_new: '+hpp_new);

                     break;
                }
            }
            
        </script>";

        /*------------nilai_bawah----------------*/
        $strTbl .= "<script>
            function trigger_nilai_bawah(x,y,z,a) {
                console.log('x:', x);
                console.log('y:', y);
                console.log('z:', z);
                console.log('a:', a);
                
                var pph23 = '$pph23';
                var ppn = '$ppn';
                // var hrg_list = $('#harga_list_' + z).val();
                var hrg_list = $('#harga_list_reseller_' + z).val();
                
                var diskon_persen = $('#diskon_persen_' + z);
                var diskon_nilai = $('#diskon_nilai_' + z);
                var premi_persen = $('#premi_jual_' + z);
                var premi_nilai = $('#premi_jual_nilai_' + z);                
                var harga_aft = $('#harga_aft_' + a);
                var hpp_nppn = $('#harga_beli_af_tax_' + z);
                var idini = $('#' + x +'_' + z);
                
                console.log('idini: ', idini);                
               
                let diskon_0_nilai = 0;
                let su = 0;
                let new_dpp = 0;
                let dpp_berjalan = false;
                let harga_beli_be_tax = 0;
                let harga_beli_af_tax = removeCommas(hpp_nppn.val());
                
                if(harga_beli_af_tax > y){
                    swal({
                        title: 'Perhatian.. !!',
                        html: 'Harga Batas harus diatas ' + addCommas(harga_beli_af_tax) + ' sekarang ' + addCommas(y)
                    });
                    idini.css('background-color','pink').val(addCommas(y));
                }
                else {
                    idini.css('background-color','yellow').val(addCommas(y));
                    
                    $('#anu').load('$link_update_harga_jual&nilai=' + y + '&id=' + z);
                }

            }
            
        </script>";

        /* -------------------------------------------------------------------------------------------------------
         * trigger_hpp(x,y,z,a,b,c,d)
         * x= nama_kolom    a= row_id       d= key
         * y= value         b= no_kolom
         * z= produk_id     c= diskon_id
         * -------------------------------------------------------------------------------------------------------*/
        $strTbl .= "<script>
            var url = '$this_domain';
            function trigger_hpp(x,y,z,a,b,c,d) {
                var pph23 = '$pph23';
                var ppn = '$ppn';
                let idini = x + '_' + d + '_' + z;
                let defVal = document.getElementById(idini).defaultValue;
                let be_kolom = b - 1;
                let harga_beli_list = $('#harga_beli_0_' + z).val();
                let harga_beli_be_tax = $('#harga_beli_be_tax_' + z).val()!='' ? removeCommas($('#harga_beli_be_tax_' + z).val()) : 0;
                let harga_beli_berjalan = $('#diskon_' + be_kolom + '_dpp_' + z).val();
                let harga_beli_af_tax = removeCommas($('#harga_beli_af_tax_' + z).val());
                let dpp = 0;
                if(harga_beli_berjalan == undefined){
                   // dpp = harga_beli_af_tax;
                   dpp = harga_beli_be_tax;
                   // dpp = harga_beli_list;
                }
                else {
                   dpp = removeCommas(harga_beli_berjalan);
                }
                dpp = harga_beli_be_tax;
                // let harga_pajak_beline = removeCommas($('#harga_pajak_beline_' + a).text());
                let diskon_nilai_npph = 0;
                let diskon_nilai = 0;
                let nilai_persen = 0;
                let max_diskon_nilai = (dpp * 20 / 100);
                
                console.log('idini:', idini);
                console.log('c:', c);
                console.log('defVal:', defVal);
                switch (d) {
                  case 'persen':
                        nilai_persen = y;
                        diskon_nilai = (y/100) * dpp;
                        $('#'+ x + '_nilai_' + z ).val(diskon_nilai.toFixed(0));                        
                        $('input#diskon_'+b+'_dpp_'+z).val( addCommas(dpp-diskon_nilai) );
                        
                        if(c == '0'){
                            diskon_nilai = (y/100) * harga_beli_list;
                            let harga_beli_be_tax_0 = harga_beli_list-diskon_nilai;
                            let harga_beli_af_tax_0 = harga_beli_be_tax_0 * ((100 + Number(ppn)) / 100); 
                        
                            $('#'+ x + '_nilai_' + z ).val(Math.round(diskon_nilai));
                            $('input#diskon_'+b+'_dpp_'+z).val(harga_beli_list-diskon_nilai);
                            $('#harga_beli_be_tax_' + z).val(Math.round(harga_beli_be_tax_0)) . trigger('blur');    
                            $('#harga_beli_af_tax_' + z).val(Math.round(harga_beli_af_tax_0));    
                        }
                        
                        console.log('hasil persen: ', diskon_nilai);
                    break;
                    case 'nilai':
                        var yfn =  dpp - y;
                        var yff = 100 - (yfn / dpp * 100);
                        nilai_persen = yff;
                        diskon_nilai = y;
                        diskon_nilai_npph = y * ((100 - pph23) / 100);
                         console.log('hasil nilai: ', nilai_persen);
                         $('#'+ x + '_nilainpph_' + z ).val(diskon_nilai_npph.toFixed(0));
                         $('#'+ x + '_persen_' + z ).val(nilai_persen.toFixed(2));
                         $('input#diskon_'+b+'_dpp_'+z).val( addCommas(dpp-diskon_nilai) );
                    break;
                    case 'nilainpph':
                        diskon_nilai_npph = y;     
                        diskon_nilai = y * (100 / (100 - pph23));
                        nilai_persen = 100 - ((dpp - diskon_nilai) / dpp * 100);
                        
                        $('#'+ x + '_nilai_' + z ).val(diskon_nilai.toFixed(0));
                         $('#'+ x + '_persen_' + z ).val(nilai_persen.toFixed(2));
                        break;
                }
                
                if(diskon_nilai > max_diskon_nilai){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'maximal diskon ' + addCommas(max_diskon_nilai) + ', sekarang ' + addCommas(diskon_nilai) + ' dari ' + addCommas(dpp)
                    });
                }
                
               var summDiskon = 0;
               // for (var i = 1; i < b; i++) {
               for (var i = 1; i <= 7; i++) {
                   var su = $('#diskon_'+ i + '_nilai_' + z).val() > 0 ? $('#diskon_'+ i + '_nilai_' + z).val()*1 : 0;
                   summDiskon += parseFloat(su);
               }
                   
               let pph23_nilai = pph23 / 100 * summDiskon;
               let summDiskon_af_pph = summDiskon - pph23_nilai; 
               var harga_beline_be_pph = Math.round(summDiskon*1);
               var harga_beline_af_pph = harga_beli_be_tax*1 - (summDiskon*1);
               var harga_beline = dpp*1 - (summDiskon*1) + pph23_nilai;
               let harga_beline_be_tax = harga_beli_be_tax - summDiskon_af_pph;
               let harga_beline_af_tax = harga_beline_be_tax * ((100 + Number(ppn)) / 100);
               var harga_beline_tax = harga_beli_af_tax - summDiskon + pph23_nilai;

                console.log('====================================')
                console.log('x:', x)
                console.log('y:', y)
                console.log('z:', z)
                console.log('a:', a)
                console.log('b:', b)
                console.log('c:', c)
                console.log('d:', d)
                console.log('harga_beli_af_tax:', harga_beli_af_tax)
                console.log('summDiskon:', summDiskon)
                console.log('pph23_nilai:', pph23_nilai)
                console.log('summDiskon_af_pph:', summDiskon_af_pph)
                console.log('harga_beline_be_tax:', harga_beline_be_tax)
                console.log('ppn:', ((100 + Number(ppn)) / 100))
                console.log('harga_beline_af_tax:', harga_beline_af_tax)
                console.log('harga_beline_tax:', harga_beline_tax)

                console.log('harga_beline:', harga_beline)
                console.log('harga_beli_be_tax:', harga_beli_be_tax)
                console.log('harga_beli_berjalan:', harga_beli_berjalan)
                console.log('dpp:', dpp)
                console.log('diskon_nilai:', diskon_nilai)

                //    console.log(harga_beli_be_tax)
                ////  console.log(diskon_nilai)
                ////  console.log(nilai_persen)

                // if (harga_beli_be_tax === undefined) {
                //     console.log('Elemen tidak ditemukan atau nilai tidak dapat diambil.');
                // }
                // $('#harga_beline_'+ a ).html('Tax: '+addCommas(harga_beline_tax) + '<br>' + 'Non: ' + addCommas(harga_beline) );

           //PPH 23 (harga_pajak_beline_1000)
                $('#harga_pajak_beline_'+ a ).html(addCommas(Math.round(pph23_nilai)) );

           //BE PPH (harga_beline_be_pph_)
                $('#harga_beline_be_pph_'+ a ).html(addCommas(Math.round(harga_beline_be_pph)));
                
                // total rebate af tax
                $('#total_nilai_dp_af_tax_'+ a ).html(addCommas(Math.round(summDiskon_af_pph)));

           //HARGA TANDAS (harga_beline_1000)
                $('#harga_beline_'+ a ).html(addCommas(Math.round(harga_beline_be_tax)) );

           //HARGA TANDAS + TAX (harga_beline_af_tax_1000)
                $('#harga_beline_af_tax_'+ a ).html(addCommas(Math.round(harga_beline_af_tax)));


                switch (d) {
                  case 'persen':
                        nilai_persen = y;
                    break;
                    case 'nilai':
                        var yfn =  dpp - y;
//                        var yff = yfn / harga_beli_be_tax;
                        var yff = 100 - (yfn / dpp * 100);
                        nilai_persen = yff;
                    break;
                    case 'nilainpph':
                        console.log('nilainpph', 9999)
                        break;
                }
                
                if(defVal != y){
                    console.log('update karena ' + defVal + '><' + y);
                    $('#anu').load('$link_update_diskon_pembelian&nilai=' + nilai_persen + '&nilaidk=' + diskon_nilai + '&nilaidknpph=' + diskon_nilai_npph + '&id='+ z + '&basik=' + dpp + '&jenis=' + x + '&diskonid=' + c).load('$link_gerbangBuilder');
                }
            }
           
           </script>";
        /*-----filter select--------------*/
        if ($is_po == false) {
            $strTbl .= "<script>                     
                function loadFilter(kolom,id) {
                  let mrnama = $('#filter_kolom').find('option:selected').attr('mrnama');
                  
                  // console.log('kolom:', kolom);
                  // console.log('id:', id);              
                  // console.log('mrnama:', mrnama);
                  // swal('Cek Kembali', 'nilai yang Anda input tidak sama dengan nilai yang seharusnya.', 'warning');
                  swal({
                    title: 'Harap menunggu',
                    text: 'sedang menerapkan filter ...',
                    showLoaderOnConfirm: true,
                    showConfirmButton: false,
                    // timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                  });
                  localStorage.setItem('fid', id);
                  localStorage.setItem('fkolom', kolom);
                  
                  // console.log(localStorage.getItem('fid'));
                  
                  $('#satu').load(url + 'diskon/Setting/viewProdukHarga?f=' + kolom + '&v=' + id);              
                }
                
                var fid = localStorage.getItem('fid');
                var fkolom = localStorage.getItem('fkolom');
                                
                // console.log('fkolom:', fkolom);
                if(fid){                
                    $('#filter_kolom').val(fid);
                }
                else {
                    $('#tr_1000').html('<td colspan=33 style=text-align:center;text-transform:capitalize;><h1>harap memilih merek terlebih dahulu</h1></td>');
                }          

            </script>";
        }

        $strTbl .= "<script>
            function trigger_diskon_00(x,y,z,a,b,c,d) {
              console.log('x:' + x);
              console.log('y:' + y);
              console.log('z:' + z);
              console.log('a:' + a);
              console.log('b:' + b);
              console.log('c:' + c);
              console.log('d:' + d);
              
               let be_kolom = b - 1;
               let harga_beli_be_tax = $('#harga_beli_be_tax_' + z).val()!='' ? removeCommas($('#harga_beli_be_tax_' + z).val()) : 0;
               let harga_beli_berjalan = $('#diskon_' + be_kolom + '_dpp_' + z).val();
               let harga_beli_af_tax = removeCommas($('#harga_beli_af_tax_' + z).val());
                
              switch (x) {
                case 'diskon_00':
                    console.log('widi')
                    break;
                    case 'diskon_0':
                        console.log('tms');
                        break;
              }
              
              
            }
            
            $('#close_alert').click(function(){
                console.log(this);
               // $('p#44.test').css('background-color', 'yellow');
               $('#alert_legenda').fadeOut();
            });
            
            function kirim_tanda(x) {
              console.log(x);
               // $('#tr_' + x).css('background-color', 'yellow !important');
               // $('#tr_' + x).attr('style', 'background-color: yellow !important');
               $('#diskon_00_' + x).attr('style', 'background-color: yellow !important');
            }                        
        </script>";

        $strTbl .= "<script>                
                function penghitung(){
                    // console.log('penghitung berhasil dijalankan');
                }

                // $('.wrapper').prepend(\"<div id='overlay' class='overlay' style='display: block;'></div>\")
                $('.btn-satuan').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('" . base_url() . "diskon/Setting/viewSatuan?id='+pid),
                        title: 'Satuan '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                $('.btn-grosir').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('" . base_url() . "diskon/Setting/viewGrosir?id='+pid),
                        title: 'Grosir '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                $('.btn-hadiah').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('" . base_url() . "diskon/Setting/viewHadiah?id='+pid),
                        title: 'Hadiah '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                $('.btn-scheduler').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('viewScheduler?id='+pid),
                        title: 'Scheduler '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    });
                });
                $('.btn-hadiah_penjualan').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('" . base_url() . "diskon/Setting/formHadiahPenjualan?id='+pid),
                        title: 'Satuan '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    })
                });
                
                // $('.chart').addClass('loading_2');
                $(document).ready( setTimeout( function(){

                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        var nilai ='';
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"width: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview = top.$('table#$tbl_id').DataTable({
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
//                                        $('#overlay').remove()
                                        $(\".chart\").removeClass(\"loading_2\");
                                        swal.close();
                                     },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
//                                    serverSide: true,
//                                    ajax: 'viewProdukHarga',
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
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
                                            // {
                                            //     text: 'Hanya tampilkan yang ada diskon',
                                            //     action: function ( e, dt, node, config ) {
                                            //         $('#produk').load('$this_domain/diskon/Setting/viewProdukHarga/grosir');
                                            //     }
                                            // },
                                            // {
                                            //     text: 'Hanya tampilkan yang tidak ada diskon',
                                            //     action: function ( e, dt, node, config ) {
                                            //         $('#produk').load('$this_domain/diskon/Setting/viewProdukHarga/non_diskon');
                                            //     }
                                            // },
                                            // {
                                            //     text: 'Tampilkan seluruh data',
                                            //     action: function ( e, dt, node, config ) {
                                            //        $('#produk').load('$this_domain/diskon/Setting/viewProdukHarga/semua');
                                            //     }
                                            // },
                                            {
                                                text: 'Tampilkan/Sembunyikan legenda',
                                                action: function ( e, dt, node, config ) {
                                                    console.log(e);
                                                    console.log('dt', dt);
                                                   $('#alert_legenda').fadeToggle();
                                                }
                                            },
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
                                        }
                                    ],                                    
                                    drawCallback: function( settings ) {
                                //bikin penghitung
                                // console.log('table berubah');
                                // console.log('menjalankan penghitung');
                                top.penghitung();

                                var arrForm = top.$('input.form-edit');
                                var arrBtn  = top.$('button.tombol-action');

                                // console.log( arrForm );
                                // console.log( arrBtn  );
                            }

                        });
                    
                        datareview.on('order.dt search.dt', function () {
                        let i = 1;
                        datareview.cells(null, 0, {
                            search: 'applied', order: 'applied'
                            }).every(function (cell) {
                                this.data(i++);
                            });
                        }).draw();

                        $('#data_ok_wrapper').addClass('table-responsive tblzd_$tbl_id');
                                    $('.table-responsive.tblzd_$tbl_id').floatingScroll();
                                        $('.table-responsive.tblzd_$tbl_id').scroll(
                            delay_v2(function () {
                                $('table#$tbl_id').DataTable().fixedHeader.adjust();
                            }, 200)
                        );
                                    }, 500));
                </script>";


        /* ---------------------------------------------------------------------------------------------
         * penampil di browser ROW DATA perproduk transaksi
         * ---------------------------------------------------------------------------------------------*/
        // $p->setLayoutBoxCss("box-info");
        // $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        // $p->setLayoutBoxHeading("$subTitle", $btn_colaps);
        // $p->setLayoutBoxBody(true);
        // $content_0 = $p->layout_box($strTbl);
        // $content_0 .= "<div id='anu'></div>";
        //endregion

        echo $strTbl;
        break;

    case "viewProdukRebate":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");

        $headerTop = array();
        foreach ($arrHeaders as $kolom => $arrHeader) {
            if (isset($arrHeader['span_header'])) {
                $headerTop[$kolom] = $arrHeader;
            }

            if (isset($arrHeader['top_parent'])) {
                $top_parent = $arrHeader['top_parent'];
                if (!isset($headerTop_ky[$top_parent])) {

                    $headerTop_ky[$top_parent] = 0;
                }
                $headerTop_ky[$top_parent] += 1;
            }

            if (isset($arrHeader['sub_top_parent'])) {
                $top_parent = $arrHeader['sub_top_parent'];
                if (!isset($headerSubTop_ky[$top_parent])) {

                    $headerSubTop_ky[$top_parent] = 0;
                }
                $headerSubTop_ky[$top_parent] += 1;
            }
        }
        // arrPrintKuning($headerTop_ky);
        // arrPrintHijau($headerSubTop_ky);
        //region prduk

        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-danger' line='" . __LINE__ . "'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($headerTop as $ktop => $kparams) {
            $klabel = isset($kparams['label']) ? $kparams['label'] : $ktop;

            $strHead .= "<th rowspan='2'>$klabel</th>";
        }
        // --------------- parent header ---------------------
        foreach ($headerTop_ky as $parent_ky => $top_jml) {
            $arrHeaderParent = $arrHeaderParents[$parent_ky];
            // foreach ($arrHeaderParents as $parent_ky => $arrHeaderParent) {
            $pLabel = isset($arrHeaderParent["label"]) ? $arrHeaderParent["label"] : $parent_ky;
            $pAttrHeader = isset($arrHeaderParent["attr_header"]) ? $arrHeaderParent["attr_header"] : "";
            $colspan_parent = $headerTop_ky[$parent_ky];

            $strHead .= "<td colspan='$colspan_parent' $pAttrHeader>$pLabel</td>";
        }
        // --------------- grosir berjenjang  ---------------------------
        // $colspan_grosir = isset($headerTop_ky["grosir"]) ? $headerTop_ky["grosir"] : 0;
        // if ($colspan_grosir > 0) {
        //     $strHead .= "<td colspan='$colspan_grosir' rowspan='1' class='bg-success'>diskon penjualan berjenjang</td>";
        // }
        // --------------- button ---------------------------
        // $strHead .= "<td rowspan='1'>button</td>";

        $strHead .= "</tr>";

        /*---subhead_1----*/
        // $strHead .= "<tr class='bg-danger'>";
        // // $strHead .= "<td colspan='1' class='bg-blue'>hl reseller</td>";
        // $strHead .= "<td colspan='2' class='bg-aqua'>harga list</td>";
        //
        //
        // $strHead .= "<td colspan='2' class='bg-purple'>diskon</td>";
        // $strHead .= "<td colspan='2' class='bg-teal'>premi</td>";
        // $strHead .= "<td colspan='1' class='bg-danger'></td>";
        // $strHead .= "<td colspan='1' class='bg-danger'></td>";
        //
        // for ($i = 1; $i <= 3; $i++) {
        //     // $strHead .= "<td colspan='1' class='bg-success'></td>";
        // }
        //
        // // $strHead .= "<td colspan='1' class='bg-danger'></td>";
        // $strHead .= "</tr>";

        /*---subhead_2----*/
        $strHead .= "<tr class='bg-danger'>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hAttr = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";
            $hSpan = isset($arrHeader['span_header']) ? $arrHeader['span_header'] : "";
            if (!isset($arrHeader['span_header'])) {
                $strHead .= "<th $hAttr title='$kolom'>$hLabel</th>";
            }
        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $row_id = 998;
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";

        //        arrPrint($master_data);
        foreach ($master_data as $master_datum) {
            $row_id++;
            $no++;
            $diskon_cek = $master_datum['diskon_cek'];

            $color_cek = "";
            if ($diskon_cek == 1) {

                // $color_cek = "style='color: red';";
            }
            else {
                // $color_cek = "style='color: blue';";
                // $color_cek = "";
            }

            $strBody .= "<tr id='tr_$row_id'>";
            $strBody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $attrs) {
                $td_id = $kolom . "_" . $row_id;
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $data_order = isset($attrs['data_order']) && ($attrs['data_order'] == false) ? "" : ((isset($attrs['data_order']) && ($attrs['data_order'] != false)) ? $master_datum[$attrs['data_order']] : (isset($master_datum[$kolom]) ? $master_datum[$kolom] : '0'));

                if (isset($attrs['links'])) {
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    $nilai_link = $nilai_f;
                }

                $strBody .= "<td $color_cek $attr id='$td_id' data-row='$row_id' data-column='$kolom' data-order='$data_order' title='$kolom'> $nilai_link</td>";;

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

        //        arrPrint($strBody);
        //        matiHere();

        $tbl_id = "data_rebate";
        $strTbl = "";

        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th, .table>tfoot>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th, .table>tfoot>tr>td {
                            padding : 3px 10px !important;
                        }
                        .form-control {
                            height: 20px;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $legenda = true;
        /* --------------------------------------------------
         * aler legenda kolom keterangan
         * --------------------------------------------------*/
        if($legenda === true){
            $strTbl .= "<div class='alert alert-warning' id='alert_legenda'>";
            $strTbl .= "<button type='button' id='close_alert' class='close' data-show='alert' aria-hidden='true'>x</button>";
            $strTbl .= "<h4>Legenda:</h4>";
            $strTbl .= "<div class='text-uppercase'>";
            $strTbl .= "<i class='fa fa-stop  text-red'style='color: #63ff5a;' ></i> <b>total absolut value</b> : seting rebate berdasarkan total nilai/akumulasi pembelian dalam satu invoice jika mencapai nominal tertentu<br>";
            $strTbl .= "<i class='fa fa-stop text-blue'></i> <b>seting kelompok</b> : seting rebate berdasarkan kelompok produk<br>";

            $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>DISKON/KOMPONEN PEMBENTUK HARGA TANDAS</b> 
                    <i class='fa fa-stop' style='color: #63ff5a;'></i> Campaign Qty | ";
            $strTbl .= "<i class='fa fa-stop' style='color: #ffa74a;'></i> Campaign Nilai  ";
            $strTbl .= "<i class='fa fa-stop' style='color: #97ebff;'></i> rebate %  ";
            // $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>harga tandas w/o ppn</b> : HPP w/o. PPN (dpp) - total rebate st. pph23";
            // $strTbl .= "<i class='fa fa-check-square-o text-red'></i> <b>harga tandas inc. ppn</b> : HPP inc. PPN - total rebate st. pph23<br>";
            $strTbl .= "</div>";
            $strTbl .= "</div>";
        }
        // ----------------------------end alert
// arrPrintHijau($_GET);
//     cekLime("is_po : $is_po");
        if ($is_po == false) {
            $strTbl .= "<div class='panel no-margin'>";
            $strTbl .= "Pilih Supplier <i class='blink fa fa-angle-right text-red'></i>";
            $strTbl .= "<select data-style=\"btn-success\" data-live-search=\"true\" title=\"pilih supplier\" data-headers=\"pilih supplier\" data-size=\"20\" data-container=\"body\" class='btn btn-md btn-infox selectpicker select2' id='filter_kolom_rebate' onchange=\"loadFilterSupplier('supplier_id',this.value);\">";
            // $strTbl .= "<select  idd='filter_kolom_rebate' onchange=\"loadFilterSupplier('supplier_id',this.value);\">";
//            $strTbl .= "<option >---pilih supplier---</option>";
//            $strTbl .= "<option value='null'>semua supplier</option>";
            // $strTbl .= "<option value='0'>no merek</option>";

            foreach ($srcMereks as $mr_id => $srcMerek) {
                $mr_nama = $srcMerek->nama;
                $strTbl .= "<option value='$mr_id' mrnama='$mr_nama'>$mr_id $mr_nama</option>";
            }
            $strTbl .= "</select>";
            $strTbl .= "</div>";

            // $strTbl .= "<div style='margin: 10px 0 10px 0;'>";
            $strTbl .= "<div style='display: flex; align-items: center; gap: 10px; margin: 10px 0;text-transform: capitalize;'>";
            $strTbl .= "<button id='btn-absolut' pid='20' nm='chang' type='button' class='btn btn-md btn-danger text-capitalize'>total absolut value</button>";
            $strTbl .= "<button style='margin-left: 3px;' id='btn-kelompok' pid='20' nm='chihu' type='button' class='btn btn-md btn-primary text-capitalize'>setting kelompok</button>";


            $strTbl .= "<div style='border: 0px solid red; padding: 5px; display: flex; align-items: center;'>";
            // $strTbl .= "<input type='radio'> include";
            $keField = "pilihan_sumber";
            $id_toggle = "sumber";
            $dpp_rebate = $srcMereks[$supplier_id]->dpp_rebate;
            $checked_1 = $dpp_rebate == "include" ? "checked" : "";
            $checked_2 = $dpp_rebate == "exclude" ? "checked" : "";
            // arrPrintKuning($checked_1);
            $strTbl .= "Basik perhitungan rebate &nbsp;";
            $strTbl .= "<div class='wrapper-radio'>";
            $strTbl .= "<input id='toggle-1-$id_toggle' $checked_1 type='radio' name='$keField' mid='include' vl='include' value='1' class='toggle-radio toggle-left'> <label for='toggle-1-$id_toggle' class='btn-radio rad-l'>Harga beli inclunde PPN</label>";
            $strTbl .= "<input id='toggle-2-$id_toggle' $checked_2 type='radio' name='$keField' mid='exclude' vl='exclude' value='2' class='toggle-radio toggle-right'> <label for='toggle-2-$id_toggle' class='btn-radio rad-r'>Harga beli exclunde PPN</label>";
            $strTbl .= "</div>";
            $strTbl .= "</div>";

            $strTbl .= "</div>";
        }

        $strTbl .= "<div classs='table-responsivex xxtblid_$tbl_id'>";
        //        $strTbl .= "<div class=''>================================================</div>";
        if ($is_po == true) {
            $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx'>";
        }
        else {
            $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx' id='$tbl_id'>";
        }
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
        $strTbl .= "<div id='anu'></div>";
        //$link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);

        // $link_update =
        $link_update_diskon_pembelian = "";
        if (isset($cCode) && ($cCode != "")) {
            //            cekHitam($urlBack);
            // $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian&cCode=$cCode&urlBack=$urlBack";
        }
        else {
            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=rebate_qty";
        }
        $link_gerbangBuilder = base_url() . "diskon/Setting/iterasiGerbangItem/_tr_466";
        $link_update_hrg_list = base_url() . "diskon/Setting/do_update?ky=diskon_persen";
        $link_update_premi_jual = base_url() . "diskon/Setting/do_update?ky=premi_persen";
        $this_domain = base_url();
        $strTbl .= "<script>
            function trigger_nilai(x,y,z,a) {
                console.log('x:', x)
                console.log('y:', y)
                console.log('z:', z)
                console.log('a:', a)
                
                var pph23 = '$pph23';
                var ppn = '$ppn';
                // var hrg_list = $('#harga_list_' + z).val();
                var hrg_list = $('#harga_list_reseller_' + z).val();
                
                var diskon_persen = $('#diskon_persen_' + z);
                var diskon_nilai = $('#diskon_nilai_' + z);
                var premi_persen = $('#premi_jual_' + z);
                var premi_nilai = $('#premi_jual_nilai_' + z);                
                var harga_aft = $('#harga_aft_' + a);
                var hpp_nppn = $('#harga_beli_af_tax_' + z);
                
                var new_diskon_persen = (y / hrg_list) * 100;
                var new_diskon_nilai = (y / 100) * hrg_list;
                var new_harga_aft_diskon = hrg_list - new_diskon_nilai;
                // ---------------------
                var new_premi_persen = (y / hrg_list) * 100;
                var new_premi_nilai = (y / 100) * hrg_list;
                var new_harga_aft_premi = (hrg_list * 1) + Number(new_premi_nilai);
                
                // console.log('hlist: ' + hrg_list + ' nY:' + y);
                // console.log(new_harga_aft_premi);
                // console.log(new_diskon_persen);
               // console.log(x + ' :td id: ' + a + ' potongan :' + new_harga_aft_diskon);
                let diskon_0_persen = removeCommas($('#diskon_0_persen_' + z).val());
                let diskon_0_nilai = 0;
                let su = 0;
                let new_dpp = 0;
                let dpp_berjalan = false;
                let harga_beli_be_tax = 0;
                switch (x) {
                  case 'diskon_nilai':                                                                                                      
                        // premi_persen.val(0).trigger('blur').prop('disabled', true);
                        premi_persen.val(0).prop('disabled', true).load('$link_update_premi_jual&id='+ z +'&nilai=0');
                        harga_aft.text(new_harga_aft_diskon);  
                        diskon_persen.val(new_diskon_persen.toFixed(2)).trigger('blur');  
                        if(y == 0){
                            premi_persen.prop('disabled', false);
                            premi_nilai.prop('disabled', false);
                        }
                      break;
                  case 'diskon_persen':                                                
                        diskon_nilai.val(RoundTo(new_diskon_nilai, 100));
                        harga_aft.text(new_harga_aft_diskon);
                        premi_nilai.val(0).prop('disabled', true);
                        premi_persen.val(0).prop('disabled', true).load('$link_update_premi_jual&id=' + z + '&nilai=0');
                        if(y == 0){
                            premi_persen.prop('disabled', false);
                            premi_nilai.prop('disabled', false);
                        }
                      break;
                  // ------------------------
                  case 'premi_jual_nilai':
                        diskon_persen.val(0).prop('disabled', true).load('$link_update_hrg_list&id='+ z +'&nilai=0');
                        harga_aft.text(new_harga_aft_premi);
                        premi_persen.val(new_premi_persen.toFixed(2)).trigger('blur');
                        if(y == 0){
                            diskon_persen.prop('disabled', false);
                            diskon_nilai.prop('disabled', false);
                        }
                     break;
                  case 'premi_jual':                                                   
                        premi_nilai.val(RoundTo(new_premi_nilai,100));
                        harga_aft.text(new_harga_aft_premi);
                        diskon_nilai.val(0).prop('disabled', true);
                        diskon_persen.val(0).prop('disabled', true).load('$link_update_hrg_list&id='+ z +'&nilai=0');
                        if(y == 0){
                            diskon_persen.prop('disabled', false);
                            diskon_nilai.prop('disabled', false);
                        }
                     break;
                  // ----------------------
                  case 'hpp_supplier':
                      dpp_berjalan = false;
                      let harga_beli_list = $('#harga_beli_0_' + z).val();
                      var hpp_nppn_new = (ppn/100 * y) + Number(y);
                      $(hpp_nppn).val(addCommas(hpp_nppn_new));
                      
                      diskon_0_nilai = diskon_0_persen / 100 * harga_beli_list;
                      harga_beli_be_tax = harga_beli_list - diskon_0_nilai;
                     
                      var summDiskon = 0;   
                      su =0;
                      new_dpp = harga_beli_be_tax;
                      for (var i = 1; i <= 7; i++) {
                           let pu = $('#diskon_'+ i + '_persen_' + z).val();
                           let sube = $('#diskon_'+ i + '_nilai_' + z).val();
                           let dpp = $('#diskon_'+ i + '_dpp_' + z).val();
                          
                           if(dpp_berjalan === true){
                                if(i == 1){   
                                   su = pu /100 * hpp_nppn_new;
                                   new_dpp = hpp_nppn_new - su;
                                }
                                else {
                                    su = pu /100 * new_dpp;
                                    new_dpp = new_dpp - su;
                                }   
                           }
                           else {
                                  console.log('dpp_berjalan:', dpp_berjalan);
                                  su = pu /100 * harga_beli_be_tax;
                                  new_dpp = harga_beli_be_tax - su;
                           }
                                                      
                           // console.log('i:', i);                           
                           // console.log('harga_beli_be_tax:', harga_beli_be_tax);
                           // console.log('pu:', pu);
                           // console.log('su:', su);
                           // console.log('dpp:', dpp);
                           // console.log('new_dpp:', new_dpp);

                           $('#diskon_'+ i + '_nilai_' + z ).val(Math.round(su));
                           if(su != sube){
                                $('#anu').load('$link_update_diskon_pembelian&nilai=' + pu + '&nilaidk=' + su + '&id='+ z + '&basik=' + new_dpp + '&jenis=diskon_' + i + '&diskonid=' + i).load('$link_gerbangBuilder');
                           }
                           summDiskon += parseFloat(su);
                      }
                      

                      console.log('summDiskon:', summDiskon)
                      console.log('harga_beli_list:', harga_beli_list)
                      
                        // let diskon_0_persen = removeCommas($('#diskon_0_persen_' + z).val());
                        // let diskon_0_nilai = diskon_0_persen / 100 * harga_beli_list;
                        // let harga_beli_be_tax = harga_beli_list - diskon_0_nilai;
                        let harga_beli_af_tax = harga_beli_be_tax * ((100 + Number(ppn)) / 100);
                        let pph23_nilai = pph23 / 100 * summDiskon;
                        let total_nilai_dp_af_tax = summDiskon - pph23_nilai;
                        let harga_beline = harga_beli_be_tax*1 - (total_nilai_dp_af_tax*1);
                        let harga_beline_tax = harga_beline * ((100 + Number(ppn)) / 100);
                        
                        console.log('diskon_0_nilai: ', diskon_0_nilai);
                        console.log('pph23_nilai: ', pph23_nilai);
                        console.log('harga_beli_be_tax: ', harga_beli_be_tax);
                        console.log('harga_beli_af_tax: ', harga_beli_af_tax);
    
                        $('#diskon_0_nilai_'+ z ).val(addCommas(Math.round(diskon_0_nilai)));
                        $('#harga_beli_be_tax_'+ z ).val(harga_beli_be_tax);
                        $('#harga_beli_af_tax_'+ z ).val(harga_beli_af_tax.toFixed(0));
                        $('#total_nilai_dp_'+ a ).html(addCommas(Math.round(summDiskon)) );
                        $('#harga_pajak_beline_'+ a ).html(addCommas(Math.round(pph23_nilai)) );
                        $('#total_nilai_dp_af_tax_'+ a ).html(addCommas(Math.round(total_nilai_dp_af_tax)) );                        
                        $('#harga_beline_'+ a ).html(addCommas(Math.round(harga_beline)) );
                        $('#harga_beline_af_tax_'+ a ).html(addCommas(Math.round(harga_beline_tax)));

                     break;
                  case 'hpp_supplier_0':
                      diskon_0_nilai = $('#diskon_0_nilai_' + z) . val();
                      let harga_beli_0 = y * (100 / (100 - diskon_0_persen)); 
                      
                      console.log('diskon_0_nilai: ', diskon_0_nilai);
                                                                  
                      $('#harga_beli_0_' + z). val(Math.round(harga_beli_0)).trigger('blur');
                      break;
                  case 'hpp_nppn_supplier':
                      var hpp_new = y*100/(100+Number(ppn));
                      $('#harga_beli_be_tax_'+z).val(addCommas(hpp_new)).trigger('blur');
                        console.log('hpp_new: '+hpp_new);

                     break;
                }
            }
            
        </script>";
        /* -------------------------------------------------------------------------------------------------------
         * trigger_hpp(x,y,z,a,b,c,d)
         * x= nama_kolom    a= row_id       d= key
         * y= value         b= no_kolom
         * z= produk_id     c= diskon_id
         * -------------------------------------------------------------------------------------------------------*/
        $strTbl .= "<script>
            var url = '$this_domain';
            function trigger_rebate_qty(x,y,z,a,b,c,d) {
                // var pph23 = '$pph23';
                // var ppn = '$ppn';
                let idaftax = 'harga_beli_af_tax_' + z;
                let hargabeli_aftax = removeCommas($('#' + idaftax).val());
                let idini = x + '_' + d + '_' + z;
                let defVal = document.getElementById(idini).defaultValue;
                let be_kolom = b - 1;
                let harga_beli_list = $('#harga_beli_0_' + z).val();
                let harga_beli_be_tax = $('#harga_beli_be_tax_' + z).val()!='' ? removeCommas($('#harga_beli_be_tax_' + z).val()) : 0;
                // let harga_beli_berjalan = $('#diskon_' + be_kolom + '_dpp_' + z).val();
                // let harga_beli_af_tax = removeCommas($('#harga_beli_af_tax_' + z).val());

                let diskon_nilai_npph = 0;
                let diskon_nilai = 0;
                let nilai_persen = 0;
                let update_data = true;
                // let max_diskon_nilai = (dpp * 20 / 100);
                
                console.log('idini:', idini);
                console.log('defVal:', defVal);
                if(harga_beli_be_tax == 0){
                    update_data = false;
                    $('#' + idaftax).css('background-color', 'pink');
                    swal({
                        title: 'Perhatian',
                        html: 'Harap melakukan setting Harga Beli terlebih dahulu'
                    });
                }

                switch (d) {
                  case 'persen':
                        nilai_persen = y;
                        // diskon_nilai = (y/100) * dpp;
                        var idlawan = x + '_nilai_' + z;
                        var max_diskon_nilai = 50;
                        if(y > max_diskon_nilai){
                            $('#' + idini).css('background-color', 'pink');
                            update_data = false;
                            swal({
                                title: 'Opsss.. !!',
                                html: 'maximal diskon ' + addCommas(max_diskon_nilai) + '%, sekarang ' + addCommas(y) + '%'
                            });
                        }
                        else {
                            $('#' + idini).css('background-color', 'yellow');
                        }
                        // console.log('hasil persen: ', diskon_nilai);

                    break;
                    case 'nilai':
                        nilai_persen = y;
                        if(y >= harga_beli_be_tax){
                            update_data = false;
                            swal({
                                title: 'Tunggu Dulu !!',
                                html: 'maximal diskon ' + addCommas(harga_beli_be_tax) + ', sekarang ' + addCommas(y) + ''
                            });
                        }
                    break;
                        case 'maxim':
                            nilai_persen = y;
                        break;
                }
                
                $('#' + idlawan).val(0).css('background-color', 'yellow');
                
                // if(diskon_nilai > max_diskon_nilai){
                //     swal({
                //         title: 'Opsss.. !!',
                //         html: 'maximal diskon ' + addCommas(max_diskon_nilai) + ', sekarang ' + addCommas(diskon_nilai) + ' dari ' + addCommas(dpp)
                //     });
                // }

               var summDiskon = 0;
               // // for (var i = 1; i < b; i++) {
               // for (var i = 1; i <= 7; i++) {
               //     var su = $('#diskon_'+ i + '_nilai_' + z).val() > 0 ? $('#diskon_'+ i + '_nilai_' + z).val()*1 : 0;
               //     summDiskon += parseFloat(su);
               // }
                   
               // let pph23_nilai = pph23 / 100 * summDiskon;
               // let summDiskon_af_pph = summDiskon - pph23_nilai;
               // var harga_beline_be_pph = Math.round(summDiskon*1);
               // var harga_beline_af_pph = harga_beli_be_tax*1 - (summDiskon*1);
               // var harga_beline = dpp*1 - (summDiskon*1) + pph23_nilai;
               // let harga_beline_be_tax = harga_beli_be_tax - summDiskon_af_pph;
               // let harga_beline_af_tax = harga_beline_be_tax * ((100 + Number(ppn)) / 100);
               // var harga_beline_tax = harga_beli_af_tax - summDiskon + pph23_nilai;

                console.log('====================================');
                console.log('x:', x);
                console.log('y:', y);
                console.log('z:', z);
                console.log('a:', a);
                console.log('b:', b);
                console.log('c:', c);
                console.log('d:', d);
                // console.log('harga_beli_af_tax:', harga_beli_af_tax)
                // console.log('summDiskon:', summDiskon)
                // console.log('pph23_nilai:', pph23_nilai)
                // console.log('summDiskon_af_pph:', summDiskon_af_pph)
                // console.log('harga_beline_be_tax:', harga_beline_be_tax)
                // console.log('ppn:', ((100 + Number(ppn)) / 100))
                // console.log('harga_beline_af_tax:', harga_beline_af_tax)
                // console.log('harga_beline_tax:', harga_beline_tax)
                //
                // console.log('harga_beline:', harga_beline)
                // console.log('harga_beli_be_tax:', harga_beli_be_tax)
                // console.log('harga_beli_berjalan:', harga_beli_berjalan)
                // console.log('dpp:', dpp)
                // console.log('diskon_nilai:', diskon_nilai)

                //    console.log(harga_beli_be_tax)
                ////  console.log(diskon_nilai)
                ////  console.log(nilai_persen)

                // if (harga_beli_be_tax === undefined) {
                //     console.log('Elemen tidak ditemukan atau nilai tidak dapat diambil.');
                // }
                // $('#harga_beline_'+ a ).html('Tax: '+addCommas(harga_beline_tax) + '<br>' + 'Non: ' + addCommas(harga_beline) );

           //PPH 23 (harga_pajak_beline_1000)
           //      $('#harga_pajak_beline_'+ a ).html(addCommas(Math.round(pph23_nilai)) );

           //BE PPH (harga_beline_be_pph_)
           //      $('#harga_beline_be_pph_'+ a ).html(addCommas(Math.round(harga_beline_be_pph)));
                
                // total rebate af tax
                // $('#total_nilai_dp_af_tax_'+ a ).html(addCommas(Math.round(summDiskon_af_pph)));

           //HARGA TANDAS (harga_beline_1000)
           //      $('#harga_beline_'+ a ).html(addCommas(Math.round(harga_beline_be_tax)) );

           //HARGA TANDAS + TAX (harga_beline_af_tax_1000)
           //      $('#harga_beline_af_tax_'+ a ).html(addCommas(Math.round(harga_beline_af_tax)));


                if((defVal != y) && (update_data === true)){
                    console.log('update karena ' + defVal + '><' + y);
                    $('#anu').load('$link_update_diskon_pembelian&nilai=' + nilai_persen + '&nilaidk=' + diskon_nilai + '&nilaidknpph=' + diskon_nilai_npph + '&id='+ z + '&basik=' + d + '&jenis=' + x + '&diskonid=' + c).load('$link_gerbangBuilder');
                }
                else {
                    console.log('diam saja ' + defVal + '><' + y);
            }
            }

           </script>";
        /*-----filter select--------------*/
        if ($is_po == false) {
            $strTbl .= "<script>                     
                function loadFilterSupplier(kolom,id) {
                  let mrnama = $('#filter_kolom_rebate').find('option:selected').attr('mrnama');
                  
                  // console.log('kolom:', kolom);
                  // console.log('id:', id);              
                  console.log('mrnama:', mrnama);
                  // swal('Cek Kembali', 'nilai yang Anda input tidak sama dengan nilai yang seharusnya.', 'warning');
                  swal({
                    title: 'Harap menunggu',
                    text: 'sedang menerapkan filter ...',
                    showLoaderOnConfirm: true,
                    showConfirmButton: false,
                    // timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                  });
                  localStorage.setItem('fidr', id);
                  localStorage.setItem('fnamar', mrnama);
                  localStorage.setItem('fkolomr', kolom);
                  
                  // console.log(localStorage.getItem('fid'));
                  
                  $('#satu-dua').load(url + 'diskon/Setting/viewProdukRebate?f=' + kolom + '&v=' + id);              
                }
                
                var fid = localStorage.getItem('fidr');
                var fnama = localStorage.getItem('fnamar');
                var fkolom = localStorage.getItem('fkolomr');
                                
                // console.log('fkolom:', fkolom);
                top.$('#filter_kolom_rebate').selectpicker({ dropdownParent: $('div') }).selectpicker('val', [fid]);
                if(fid){
                    $('#filter_kolom_rebate').val(fid);
                    // top.$('#filter_kolom_rebate').selectpicker({ dropdownParent: $('div') }).selectpicker('val', [fid]);
                }
                else {
                    $('#tr_1000').html('<td colspan=33 style=text-align:center;text-transform:capitalize;><h1>harap memilih merek terlebih dahulu</h1></td>');
                }          

            </script>";
        }

        $strTbl .= "<script>
            function trigger_diskon_00(x,y,z,a,b,c,d) {
              console.log('x:' + x);
              console.log('y:' + y);
              console.log('z:' + z);
              console.log('a:' + a);
              console.log('b:' + b);
              console.log('c:' + c);
              console.log('d:' + d);
              
               let be_kolom = b - 1;
               let harga_beli_be_tax = $('#harga_beli_be_tax_' + z).val()!='' ? removeCommas($('#harga_beli_be_tax_' + z).val()) : 0;
               let harga_beli_berjalan = $('#diskon_' + be_kolom + '_dpp_' + z).val();
               let harga_beli_af_tax = removeCommas($('#harga_beli_af_tax_' + z).val());
                
              switch (x) {
                case 'diskon_00':
                    console.log('widi')
                    break;
                    case 'diskon_0':
                        console.log('tms');
                        break;
              }
              
              
            }
            
            $('#close_alert').click(function(){
                console.log(this);
               // $('p#44.test').css('background-color', 'yellow');
               $('#alert_legenda').fadeOut();
            });
            
            function kirim_tanda(x) {
              console.log(x);
               // $('#tr_' + x).css('background-color', 'yellow !important');
               // $('#tr_' + x).attr('style', 'background-color: yellow !important');
               $('#diskon_00_' + x).attr('style', 'background-color: yellow !important');
            }                        
        </script>";

        $strTbl .= "<script>                
                function penghitung(){
                    // console.log('penghitung berhasil dijalankan');
                }

                // $('.wrapper').prepend(\"<div id='overlay' class='overlay' style='display: block;'></div>\")
                $('#btn-absolut').on('click', function(){
                    var pid = localStorage.getItem('fidr');
                    var nama = localStorage.getItem('fnamar');
                    var fkolom = localStorage.getItem('fkolomr');
                    
                    BootstrapDialog.show({
                        message: $('<div></div>').load('" . base_url() . "diskon/Setting/viewRebate/absolut?id='+pid),
                        title: 'Absolut '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {
                
                        }
                    })
                });
                $('#btn-kelompok').on('click', function(){                    
                    // var pid = $(this).attr('pid');
                    // var nama = $(this).attr('nm');
                    var pid = localStorage.getItem('fidr');
                    var nama = localStorage.getItem('fnamar');
                    var fkolom = localStorage.getItem('fkolomr');
                    
                    BootstrapDialog.show({
                        message: $('<div></div>').load('" . base_url() . "diskon/Setting/viewRebate/kelompok?id='+pid),
                        title: 'Kelompok '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {
                
                        }
                    })
                });
                
                $('.btn-scheduler').on('click', function(){
                    var pid = $(this).attr('pid');
                    var nama = $(this).attr('nm');
                    BootstrapDialog.show({
                        message: $('<div></div>').load('viewScheduler?id='+pid),
                        title: 'Scheduler '+nama,
                        size: 'custom-width',
                        onshown: function(dialog) {

                        }
                    });
                });
                // $('.chart').addClass('loading_2');
                $(document).ready( setTimeout( function(){

                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        var nilai ='';
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"width: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview = top.$('table#$tbl_id').DataTable({
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
//                                        $('#overlay').remove()
                                        $(\".chart\").removeClass(\"loading_2\");
                                        swal.close();
                                     },
                                        stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
//                                    serverSide: true,
//                                    ajax: 'viewProdukHarga',
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    processing: true,
                                    language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
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
                                            // {
                                            //     text: 'Hanya tampilkan yang ada diskon',
                                            //     action: function ( e, dt, node, config ) {
                                            //         $('#produk').load('$this_domain/diskon/Setting/viewProdukHarga/grosir');
                                            //     }
                                            // },
                                            // {
                                            //     text: 'Hanya tampilkan yang tidak ada diskon',
                                            //     action: function ( e, dt, node, config ) {
                                            //         $('#produk').load('$this_domain/diskon/Setting/viewProdukHarga/non_diskon');
                                            //     }
                                            // },
                                            // {
                                            //     text: 'Tampilkan seluruh data',
                                            //     action: function ( e, dt, node, config ) {
                                            //        $('#produk').load('$this_domain/diskon/Setting/viewProdukHarga/semua');
                                            //     }
                                            // },
                                            {
                                                text: 'Tampilkan/Sembunyikan legenda',
                                                action: function ( e, dt, node, config ) {
                                                    console.log(e);
                                                    console.log('dt', dt);
                                                   $('#alert_legenda').fadeToggle();
                                                }
                                            },
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
                                        }
                                    ],                                    
                                    drawCallback: function( settings ) {
                                //bikin penghitung
                                // console.log('table berubah');
                                // console.log('menjalankan penghitung');
                                top.penghitung();

                                var arrForm = top.$('input.form-edit');
                                var arrBtn  = top.$('button.tombol-action');

                                // console.log( arrForm );
                                // console.log( arrBtn  );
                            }

                        });
                    
                        datareview.on('order.dt search.dt', function () {
                        let i = 1;
                        datareview.cells(null, 0, {
                            search: 'applied', order: 'applied'
                            }).every(function (cell) {
                                this.data(i++);
                            });
                        }).draw();

                        $('#data_ok_wrapper').addClass('table-responsive tblzd_$tbl_id');
                                    $('.table-responsive.tblzd_$tbl_id').floatingScroll();
                                        $('.table-responsive.tblzd_$tbl_id').scroll(
                            delay_v2(function () {
                                $('table#$tbl_id').DataTable().fixedHeader.adjust();
                            }, 200)
                        );
                                    }, 500));
                </script>";

        $link_toggle_serial = base_url() . "statik/Data/doUpdate/Supplier";
        $link_toggle_serial = base_url() . "statik/Data/doToggleSerial/Supplier";
        $strTbl .= "<script>
            let previousValues = {};
            $('input[type=\"radio\"].toggle-radio:checked').each(function () {
                const groupName = $(this).attr('name');
                previousValues[groupName] = $(this).val();
            });
            
             $('input[type=\"radio\"].toggle-radio').on('change', function() {
                    var pid = localStorage.getItem('fidr');
                    var nama = localStorage.getItem('fnamar');
                    var fkolom = localStorage.getItem('fkolomr');
                    // const selectedVal = localStorage;
                    const selectedMid = $(this).attr('mid');
                    const selectedName = $(this).attr('name');
                    const selectedId = $(this).attr('id');
                    const selectedValue = $(this).val();
                    const labelText = $(this).next('label').text();
                    let labelTextLc = labelText.toLowerCase();
                    // console.log('selectedVal:', selectedVal);
                    const previousValue = previousValues[selectedName];
                    console.log('previousValue:', previousValue);
                    console.log('selectedName:', selectedName);
        
                    swal({
                        title: 'Konfirmasi Pilihan',
                        html: `Anda memilih basik perhitungan <r>` + labelText + `</r> Apakah Anda yakin? <br><r>Perubahan akan berefek setelah login ulang</r>`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then(
                        function(result) {
                            $('#result_bottom').load('$link_toggle_serial?ky=dpp_rebate&dpp_rebate='+selectedMid+'&vl='+selectedMid+'&id='+pid+'&nama='+encodeURI(nama));                                                                                        
                        },
                        function(dismiss) {
                            $('#toggle-' + previousValue + '-sumber').prop('checked', true);
                            
                            swal({
                                title: 'Tidak Jadi',
                                text: `Tidak terjadi perubahan data`,
                                type: 'warning',                                                
                                showConfirmButton: false,
                                timer: 1000                                                    
                            });
                        }
                    );
             });
        </script>";

        /* ---------------------------------------------------------------------------------------------
         * penampil di browser ROW DATA perproduk transaksi
         * ---------------------------------------------------------------------------------------------*/
        // $p->setLayoutBoxCss("box-info");
        // $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        // $p->setLayoutBoxHeading("$subTitle", $btn_colaps);
        // $p->setLayoutBoxBody(true);
        // $content_0 = $p->layout_box($strTbl);
        // $content_0 .= "<div id='anu'></div>";
        //endregion

        echo $strTbl;
        break;

    case "viewSupplierRebate":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon_content.html");

        // arrPrint($arrDiskonPersupplier);
        $headerTop = array();
        foreach ($arrHeaders as $kolom => $arrHeader) {
            if (isset($arrHeader['span_header'])) {
                $headerTop[$kolom] = $arrHeader;
            }
            if (isset($arrHeader['top_parent'])) {
                $top_parent = $arrHeader['top_parent'];
                if (!isset($headerTop_ky[$top_parent])) {
                    $headerTop_ky[$top_parent] = 0;
                }
                $headerTop_ky[$top_parent] += 1;
            }
            if (isset($arrHeader['sub_top_parent'])) {
                $top_parent = $arrHeader['sub_top_parent'];
                if (!isset($headerSubTop_ky[$top_parent])) {
                    $headerSubTop_ky[$top_parent] = 0;
                }
                $headerSubTop_ky[$top_parent] += 1;
            }
        }

        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr>";
        //        $strHead .= "<th rowspan='3'>no</th>";
        foreach ($headerTop as $ktop => $kparams) {
            $klabel = isset($kparams['label']) ? $kparams['label'] : $ktop;
            $strHead .= "<th rowspan='3'>$klabel</th>";
        }
        // --------------- parent header ---------------------
        if (!empty($headerTop_ky)) {
            foreach ($headerTop_ky as $parent_ky => $top_jml) {
                $arrHeaderParent = $arrHeaderParents[$parent_ky];
                // foreach ($arrHeaderParents as $parent_ky => $arrHeaderParent) {
                $pLabel = isset($arrHeaderParent["label"]) ? $arrHeaderParent["label"] : $parent_ky;
                $pAttrHeader = isset($arrHeaderParent["attr_header"]) ? $arrHeaderParent["attr_header"] : "";
                $colspan_parent = $headerTop_ky[$parent_ky] * 2;
                $strHead .= "<td colspan='$colspan_parent' $pAttrHeader>$pLabel</td>";
            }
        }

        // --------------- grosir berjenjang  ---------------------------
        // $colspan_grosir = isset($headerTop_ky["grosir"]) ? $headerTop_ky["grosir"] : 0;
        // if ($colspan_grosir > 0) {
        //     $strHead .= "<td colspan='$colspan_grosir' rowspan='1' class='bg-success'>diskon penjualan berjenjang</td>";
        // }
        // --------------- button ---------------------------
        //        $strHead .= "<td rowspan='1'>button</td>";

        $strHead .= "</tr>";
        /*---subhead_1----*/

        /*---subhead_2----*/
        $strHead .= "<tr class='sub_head2 bg-danger'>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hAttr = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";
            $hSpan = isset($arrHeader['span_header']) ? $arrHeader['span_header'] : "";
            if (!isset($arrHeader['span_header'])) {
                $subColSpan = 0;
                if (isset($arrSubHeaderDiskonPembelian[$kolom])) {
                    $subColSpan = count($arrSubHeaderDiskonPembelian[$kolom]);
                }
                $subColSpanStr = $subColSpan > 0 ? "colspan='$subColSpan'" : "";
                $strHead .= "<th $subColSpanStr $hAttr title='$kolom'>$hLabel</th>";
            }

        }
        $strHead .= "</tr>";

        $strHead .= "<tr class='sub_head3 bg-danger'>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            if (isset($arrSubHeaderDiskonPembelian[$kolom])) {
                foreach ($arrSubHeaderDiskonPembelian[$kolom] as $subKolom) {
                    $sKolom = $subKolom['label'];
                    $hAttr_sub = isset($subKolom['attr_header']) ? $subKolom['attr_header'] : "";
                    $strHead .= "<th $hAttr_sub title=''>$sKolom</th>";
                }
            }
        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $row_id = 999;
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";

        foreach ($master_data as $master_datum) {
            $row_id++;
            $no++;
            $diskon_cek = isset($master_datum['diskon_cek']) ? $master_datum['diskon_cek'] : 0;
            $color_cek = "";
            if ($diskon_cek == 1) {

                // $color_cek = "style='color: red';";
            }
            else {
                // $color_cek = "style='color: blue';";
                // $color_cek = "";
            }
            $sup_id = $master_datum['id'];
            $strBody .= "<tr>";
            //            $strBody .= "<td>$no</td>";
            //             arrPrintHijau($arrSubHeaderDiskonPembelian);
            foreach ($arrHeaders as $kolom => $attrs) {
                $td_id = $kolom . "_" . $row_id;
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $data_order = isset($attrs['data_order']) && ($attrs['data_order'] == false) ? "" : ((isset($attrs['data_order']) && ($attrs['data_order'] != false)) ? $master_datum[$attrs['data_order']] : (isset($master_datum[$kolom]) ? $master_datum[$kolom] : '0'));

                if (isset($attrs['links'])) {
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    $nilai_link = $nilai_f;
                }

                if (isset($arrSubHeaderDiskonPembelian[$kolom])) {
                    $sInput = "";
                    foreach ($arrSubHeaderDiskonPembelian[$kolom] as $subKolom => $subAttr) {
                        // arrPrintKuning($subAttr);
                        $sKolom = $subAttr['label'];
                        $disc_id = $subAttr['diskon_id'];
                        $valDef = isset($arrDiskonPersupplier[$sup_id]) && $arrDiskonPersupplier[$sup_id][$kolom][$subKolom] * 1 > 0 ? number_format($arrDiskonPersupplier[$sup_id][$kolom][$subKolom] * 1) : 0;
                        $strBody .= "<td style='padding:1px!important;align: center;' id='$subKolom$td_id' data-column='' data-order='' title=''>
                            <input value='$valDef' sup_id='$sup_id' id='$kolom$subKolom' pid='$kolom' pida='$subKolom' disc_id='$disc_id' placeholder='$subKolom' class='form-control text-center text-bold form_sup_disc' size='3'>
                        </td>";
                    }
                }
                else {
                    $strBody .= "<td $color_cek $attr id='$td_id' data-column='$kolom' data-order='$data_order' title='$kolom'> $nilai_link</td>";;
                }
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
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        $tbl_id = "data_ok";

        $strTbl = "";
        $strTbl .= "<div classs='table-responsive tblid_$tbl_id'>";

        if ($is_po == true) {
            $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx'>";
        }
        else {
            $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx' id='$tbl_id'>";
        }

        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";

        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        //        $strTbl .= "<tfoot>";
        //        $strTbl .= $strFoot;
        //        $strTbl .= "</tfoot>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $strTbl .= "<div class='box box-solid box-danger box-header'>
        <b>Ketentuan:</b><br>
        1. Semua produk yang terelasi dengan supplier ini akan di set diskon nya sesuai dengan yang di setting.<br>
        2. Produk yang belum di relasi setelah melakukan setting, maka harus digenerate diskonnya dengan tombol biru dibawah.<br>
        3. Produk yang di tandai lock/di kunci dari settingan sebelumnya, tidak akan diupdate saat dilakukan setting pada menu ini.<br>
        4. untuk melakukan settingan by produk, bisa melalui link ini <a onclick=\"top.window.open('" . base_url() . "diskon/Setting/index')\" href='javascript:void(0)'>Set Diskon By Produk</a><br>
        </div>";

        // <button id='btn_generate' class='btn btn-md btn-info pull-right'>GENERATE ALL</button>
        $strTbl .= "<div style='padding-right: 10px;'>
            <button id='btn_save' class='btn btn-md btn-warning pull-right'>SIMPAN / SAVE</button>            
        </div>";
        $strTbl .= "<div>
        </div>";

        if (isset($cCode) && ($cCode != "")) {
            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian&cCode=$cCode&urlBack=$urlBack";
        }
        else {
            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian";
        }

        $link_gerbangBuilder = base_url() . "diskon/Setting/iterasiGerbangItem/_tr_466";
        $link_update_hrg_list = base_url() . "diskon/Setting/do_update?ky=diskon_persen";
        $link_update_premi_jual = base_url() . "diskon/Setting/do_update?ky=premi_persen";
        $this_domain = base_url();

        $strTbl .= "
            <script>
                $('.form_sup_disc').on('keyup', function(){
                    var sup_id = $(this).attr('sup_id');
                    var disc = $(this).attr('pid');
                    var col = $(this).attr('pida');
                    var ids = $(this).attr('id');
                    var val = removeCommas($(this).val());
                    if(col=='nilai'){
                        
                        if(val == 0){                            
                            $('#'+disc+'persen').val(0).prop('disabled', false);
                        }
                        else{
                            $('#'+disc+'persen').val(0).prop('disabled', true);    
                        }
                    }
                    else{
                        
                        if(val == 0){                            
                            $('#'+disc+'nilai').val(0).prop('disabled', false);
                        }
                        else {
                            $('#'+disc+'nilai').val(0).prop('disabled', true);    
                        }
                    }
                    $(this).val( addCommas(val) );
                });

                $('.form_sup_disc').on('focus', function(){
                    $(this).select();
                });

                $('#btn_save').on('click', function(){
                    var arrInput = $('.form_sup_disc');
                    var arrTmp = {}
                    var arrData = []
                    jQuery.each(arrInput, function(a, b){
                        var diskon_id = $(this).attr('disc_id');
                        var sup = $(this).attr('sup_id');
                        var pid = $(this).attr('pid');
                        var pida = $(this).attr('pida');
                        var val = removeCommas($(this).val());
                        arrTmp = {
                            diskon_id:diskon_id,
                            sup:sup,
                            pid:pid,
                            pida:pida,
                            val:val
                        }
                        arrData.push(arrTmp)
                    });

                    swal({
                        title: 'SIMPAN DISKON',
                        html: 'DISKON AKAN DISIMPAN KE SEMUA PRODU KYANG TERELASI DENGAN SUPPLIER INI, LANJUTKAN?',
                        type: 'question'
                    })
                    .then((res)=>{
                        if(res){
                            $.ajax({
                                url: '" . base_url() . "diskon/Setting/saveDiskonSupplier',
                                data: {data: JSON.stringify(arrData)},
                                method: 'POST',
                                success: function(a){
                                    console.log(a);
                                }
                            })
                        }
                    })
                });

                $('#btn_generate').on('click', function(){
                    var arrInput = $('.form_sup_disc');
                    var arrTmp = {}
                    var arrData = []
                    jQuery.each(arrInput, function(a, b){
                        var sup = $(this).attr('sup_id');
                        var pid = $(this).attr('pid');
                        var pida = $(this).attr('pida');
                        var val = removeCommas($(this).val());
                        arrTmp = {
                            sup:sup,
                            pid:pid,
                            pida:pida,
                            val:val
                        }
                        arrData.push(arrTmp)
                    });

                    swal({
                        title: 'GENERATE DISKON',
                        html: 'DISKON AKAN DI GENERATE KE SEMUA PRODU KYANG TERELASI DENGAN SUPPLIER INI, LANJUTKAN??',
                        type: 'question'
                    })
                    .then((res)=>{
                        if(res){
                            $.ajax({
                                url: '" . base_url() . "diskon/Setting/saveDiskonSupplier',
                                data: {data: JSON.stringify(arrData)},
                                method: 'POST',
                                success: function(a){
                                    var arrData = JSON.parse(a)
                                    if(arrData.status){
                                        swal('sukses');
                                        swal.enableLoading();
                                        setTimeout( function(){
                                            swal.close();
//                                            top.$('iframe#result2').attr('src', top.$('iframe#result2').attr('src') );
                                        }, 2000)
                                    }
                                    console.log(arrData);
                                }
                            })
                        }
                    })
                });

            </script>
        ";

        $p->addTags(array("content" => $strTbl));
        $p->render();

        break;

    case "viewSupplier":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");

        // arrPrint($arrDiskonPersupplier);
        $headerTop = array();
        foreach ($arrHeaders as $kolom => $arrHeader) {
            if (isset($arrHeader['span_header'])) {
                $headerTop[$kolom] = $arrHeader;
            }
            if (isset($arrHeader['top_parent'])) {
                $top_parent = $arrHeader['top_parent'];
                if (!isset($headerTop_ky[$top_parent])) {
                    $headerTop_ky[$top_parent] = 0;
                }
                $headerTop_ky[$top_parent] += 1;
            }
            if (isset($arrHeader['sub_top_parent'])) {
                $top_parent = $arrHeader['sub_top_parent'];
                if (!isset($headerSubTop_ky[$top_parent])) {
                    $headerSubTop_ky[$top_parent] = 0;
                }
                $headerSubTop_ky[$top_parent] += 1;
            }
        }

        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";

        $strHead .= "<tr class='sub_head3 bg-danger'>";
        $strHead .= "<th title='' width='50px'>No</th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hAttr = isset($arrHeader['attr_h']) ? $arrHeader['attr_h'] : "";
            $kolom_label = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th $hAttr title=''>$kolom_label</th>";

        }
        $strHead .= "</tr>";

        /* --------------------------------------------------------------------
         * TBODY
         * --------------------------------------------------------------------*/
        $strBody = "";
        $row_id = 8999;
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";

        foreach ($master_data as $master_datum) {
            $row_id++;
            $no++;
            $diskon_pembelian = $master_datum['diskon_pembelian'];
            $sup_id = $master_datum['id'];
            $sup_nama = $master_datum['nama'];
            $diskon_cek = isset($master_datum['diskon_cek']) ? $master_datum['diskon_cek'] : 0;
            $color_cek = "";
            if ($diskon_cek == 1) {

                // $color_cek = "style='color: red';";
            }
            else {
                // $color_cek = "style='color: blue';";
                // $color_cek = "";
            }
            $sup_id = $master_datum['id'];
            $strBody .= "<tr>";
            //            $strBody .= "<td>$no</td>";
            //             arrPrintHijau($arrSubHeaderDiskonPembelian);
            $strBody .= "<td> $no</td>";;
            foreach ($arrHeaders as $kolom => $attrs) {
                $td_id = $kolom . "_" . $row_id;
                $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f0 = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $nilai_f = isset($attrs['nilai_replacer']) ? ($nilai > 0 ? $attrs['nilai_replacer'] : "-") : $nilai_f0;
                $data_order = isset($attrs['data_order']) && ($attrs['data_order'] == false) ? "" : ((isset($attrs['data_order']) && ($attrs['data_order'] != false)) ? $master_datum[$attrs['data_order']] : (isset($master_datum[$kolom]) ? $master_datum[$kolom] : '0'));
                $type = isset($attrs['type']) ? $attrs['type'] : "";

                if (isset($attrs['links'])) {
                    $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                    $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                    $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $strTitle_head = urlencode(trim("$link_title $title_head"));
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                else {
                    switch ($type){
                        default:
                            $nilai_link = $nilai_f;
                            break;
                        case "number":
                        case "text":

                            $disabled = $diskon_pembelian > 0 ? "disabled" : "";
                            $nilai_link = "<input type='$type' $disabled sup_id='$sup_id' nm='$sup_nama' class='form-control form_sup_disc' onclick=\"select(this);\" value='$nilai'>";
                            break;
                    }
                }

                $strBody .= "<td $color_cek $attr id='$td_id' data-column='$kolom' data-order='$data_order' title='$kolom'> $nilai_link </td>";;

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
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        $tbl_id = "data_cadangan_diskon";

        $strTbl = "";
        // $strTbl .= "<style type='text/css'>
        //     .wrapper{
        //         background-color: unset !important;
        //     }
        // </style>";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";

        // if ($is_po == true) {
        //     $strTbl .= "<table width='100%' class='table display compact table-condensed nowrapx'>";
        // }
        // else {
            $strTbl .= "<table class='table display compact table-condensed table-hover-color-red' id='$tbl_id'>";
        // }

        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";

        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        //        $strTbl .= "<tfoot>";
        //        $strTbl .= $strFoot;
        //        $strTbl .= "</tfoot>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";

        // $strTbl .= "<div class='box box-solid box-danger box-header'>
        // <b>Ketentuan:</b><br>
        // 1. Semua produk yang terelasi dengan supplier ini akan di set diskon nya sesuai dengan yang di setting.<br>
        // 2. Produk yang belum di relasi setelah melakukan setting, maka harus digenerate diskonnya dengan tombol biru dibawah.<br>
        // 3. Produk yang di tandai lock/di kunci dari settingan sebelumnya, tidak akan diupdate saat dilakukan setting pada menu ini.<br>
        // 4. untuk melakukan settingan by produk, bisa melalui link ini <a onclick=\"top.window.open('" . base_url() . "diskon/Setting/index')\" href='javascript:void(0)'>Set Diskon By Produk</a><br>
        // </div>";

        // <button id='btn_generate' class='btn btn-md btn-info pull-right'>GENERATE ALL</button>
        // $strTbl .= "<div style='padding-right: 10px;'>
        //     <button id='btn_save' class='btn btn-md btn-warning pull-right'>SIMPAN / SAVE</button>
        // </div>";
        $strTbl .= "<div>
        </div>";

        // if (isset($cCode) && ($cCode != "")) {
        //     $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian&cCode=$cCode&urlBack=$urlBack";
        // }
        // else {
        //     $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?ky=diskon_pembelian";
        // }
        //
        // $link_gerbangBuilder = base_url() . "diskon/Setting/iterasiGerbangItem/_tr_466";
        // $link_update_hrg_list = base_url() . "diskon/Setting/do_update?ky=diskon_persen";
        // $link_update_premi_jual = base_url() . "diskon/Setting/do_update?ky=premi_persen";
        // $this_domain = base_url();

        $strTbl .= "<script>
                function penghitung(){
                    // console.log('penghitung berhasil dijalankan');
                }
             $(document).ready( setTimeout( function(){

                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        var nilai ='';
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"width: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview = top.$('table#$tbl_id').DataTable({
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
//                                        $('#overlay').remove()
                                        $(\".chart\").removeClass(\"loading_2\");
                                        swal.close();
                                     },
                                        // stateLoadCallback: function(settings) {
                                        //     return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        // },
//                                    serverSide: true,
//                                    ajax: 'viewProdukHarga',
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    // processing: true,
                                    // language: {processing: \"Mempersiapkan data ... <br><i style='font-size:0.7em;color:red;'>Harap menunggu</i><div id='loader'></div>\"},
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
                                            // {
                                            //     text: 'Tampilkan/Sembunyikan legenda',
                                            //     action: function ( e, dt, node, config ) {
                                            //         console.log(e);
                                            //         console.log('dt', dt);
                                            //        $('#alert_legenda').fadeToggle();
                                            //     }
                                            // },
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
                                        }
                                    ],                                    
                                    drawCallback: function( settings ) {
                                //bikin penghitung
                                // console.log('table berubah');
                                // console.log('menjalankan penghitung');
                                top.penghitung();

                                var arrForm = top.$('input.form-edit');
                                var arrBtn  = top.$('button.tombol-action');

                                // console.log( arrForm );
                                // console.log( arrBtn  );
                            }

                        });
                    
                        datareview.on('order.dt search.dt', function () {
                        let i = 1;
                        datareview.cells(null, 0, {
                            search: 'applied', order: 'applied'
                            }).every(function (cell) {
                                this.data(i++);
                            });
                        }).draw();

                        // $('#data_ok_wrapper').addClass('table-responsive tblzd_$tbl_id');
                        //             $('.table-responsive.tblzd_$tbl_id').floatingScroll();
                        //                 $('.table-responsive.tblzd_$tbl_id').scroll(
                        //     delay_v2(function () {
                        //         $('table#$tbl_id').DataTable().fixedHeader.adjust();
                        //     }, 200)
                        // );
                                    }, 500));
        </script>";
        $strTbl .= "
            <script>
                $('.form_sup_disc').on('focus', function() {
                    $(this).data('previousValue', $(this).val());
                });
                let debounceTimeout;
                $('.form_sup_disc').on('keyup', function() {
                    clearTimeout(debounceTimeout); // Reset timer setiap kali pengguna mengetik
                
                    const inputElement = $(this); // Simpan referensi elemen untuk digunakan nanti
                
                    debounceTimeout = setTimeout(function() {
                        var sup_id = inputElement.attr('sup_id');
                        var val = removeCommas(inputElement.val());
                        var previousValue = removeCommas(inputElement.data('previousValue'));
                        var sup_nama = inputElement.attr('nm');
                
                        // Periksa jika nilai berubah
                        if (val !== previousValue) {
                            inputElement.data('previousValue', val); // Perbarui nilai sebelumnya
                            // console.log('sup_nama', sup_nama);
                            // console.log('sup_id', sup_id);
                            // console.log('val', val);
                            // console.log('previousValue', previousValue);                
                            inputElement.val(addCommas(val));
                
                            // console.log('Aksi dijalankan karena pengguna idle!');
                            $('#result_bottom').load('$link_save?sid=' +  sup_id + '&snm=' + encodeURI(sup_nama) + '&val=' + val)         
                        }
                    }, 500); // Waktu debounce (500 ms, bisa disesuaikan)
                });                                
            </script>
        ";

        echo $strTbl;
        // $p->addTags(array("content" => $strTbl));
        // $p->render();

        break;

    case "viewMember":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;
        // arrPrintHijau($level_data_0);
        $childs = array();
        foreach ($level_header as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";

            /* ----------------------------------------------------------------------
             * yg menjadi parent
             * ----------------------------------------------------------------------
             * */
            if ($child != true) {

                $level_header_0[$kolom] = $arrHeader;
            }

            if (!isset($arrHeader['parent'])) {

                $level_header_00[$kolom] = $arrHeader;
            }

            /* ----------------------------------------------------------------------
             * yg mnejadi anak
             * ----------------------------------------------------------------------
             * */
            if (isset($arrHeader['parent_ky'])) {

                $childs[$parent_ky][$kolom] = $arrHeader;
            }
        }

        // region customer level
        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($level_header_0 as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";
            $attr_header = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 2;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
        }
        $strHead .= "</tr>";

        /* -----------------------------------------------------------------------------------
         * THEAD
         * header anakan
         * -----------------------------------------------------------------------------------
         * */
        $strHead .= "<tr class='bg-info'>";
        foreach ($childs as $ky => $childrens) {
            foreach ($childrens as $kolom => $children) {
                $hLabel = isset($children['label']) ? $children['label'] : $kolom;
                $hattr_header = isset($children['attr_header']) ? $children['attr_header'] : "";

                $strHead .= "<th colspan='$colspan' $hattr_header>$hLabel</th>";
            }
        }
        $strHead .= "</tr>";
        $row_id = "";
        if (count($level_data_0) > 0) {
            foreach ($level_data_0 as $jenis_kdata => $level_data) {
                $pluss = $jenis_kdata;
                // arrPrintPink($level_data);

                /* --------------------------------------------------------------------
                 * TBODY
                 * --------------------------------------------------------------------*/
                $strBody = "";
                $no = 0;
                $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
                $jenistr = isset($jenisTr) ? $jenisTr : "582";
                // matiHere($jenistr);
                $count_id = 777;
                $row_id = "";
                // arrPrintKuning($level_data);
                // arrPrintKuning($level_header_00);
                foreach ($level_data as $master_datum) {
                    $no++;
                    $count_id++;
                    $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                    $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                    $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                    $row_id = "row_" . $jenis_kdata . "_$count_id";
                    $strBody .= "<tr id='$row_id'>";
                    $strBody .= "<td>$no</td>";
                    foreach ($level_header_00 as $kolom => $attrs) {
                        $td_id = $kolom . "_" . $count_id;
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

                        if (isset($attrs['tipe_input'])) {
                            $tipe_input = $attrs['tipe_input'];
                            $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";
                            // arrPrintKuning($master_datum);
                            // $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                            switch ($tipe_input) {
                                case "checkbox":
                                    // if ($kolom == "status") {
                                    $link_hapus = "";
                                    $checked = $nilai == 1 ? "checked" : "";
                                    // $strBody .= "<td $attr id='$td_id'>";

                                    // $strBody .= "<div class='funkyradio'>";
                                    $nilai_link = "<div class='funkyradio-success'>";
                                    $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                    $nilai_link .= "</div>";
                                    // $strBody .= "</div>";

                                    // $strBody .= "</td>";
                                    // }
                                    break;
                            }
                        }
                        // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                        // $linkDetile = base_url() . $linking . "";
                        // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                        // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;


                        if ($kolom == "action") {
                            $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                            $strBody .= "<td $attr id='$td_id'>";
                            $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                            $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                            $strBody .= "</td>";
                        }
                        else {
                            $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                        }

                        if (isset($attrs['summary'])) {
                            if (!isset($totals[$kolom])) {
                                $totals[$kolom] = 0;
                            }
                            $totals[$kolom] += $nilai;
                        }
                    }
                    $strBody .= "</tr>";

                }

            }
        }
        else {
            $strBody = "";
            $nilai = "transaksi";
        }
        /* --------------------------------------------------------------------
        * TFOOD
        * --------------------------------------------------------------------*/
        // arrPrint($level_header);
        // arrPrint($level_header_00);
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>";
        foreach ($level_header_00 as $kolom => $arrHeader) {
            $attrs = $arrHeader;

            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $attrs['default_data'] : $jenis_kdata) : "";
            $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        $tbl_id = "member";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
        $strTbl .= "<lable class='text-uppercase'>setting untuk diskon $jenis_kdata</lable>";
        $strTbl .= "<table class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<form>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        // $strTbl .= "</form>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";
        $strTbl .= "<script>
        
               function btn_edit_$pluss(r) {
                   // console.log(r);
                    var row_sumber = $('td',$('#'+r));
            
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                          var nilai = $(b).html()
                
                          objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_$pluss'));
                    var last_key = row_target.length - 1;
                    // console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        $('#minim_value_$pluss').prop('readonly', true);
                    }

               }
               
               //   minim_values
            $('#minim_value_$pluss').blur(function() {
                // var row_sumber = $row_id;
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()
            
                    objek[a] = nilai;
                })
                
                console.log('row_id :: $row_id');
                console.log(objek);
                var last_minim = objek['2'];
                var now_minim = $('#minim_value_$pluss').val();
                
                if(Number(now_minim) <= Number(last_minim)){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                    });
                    
                    $('#minim_value_$pluss').css('background-color','#fff700ad');
                }
                else {
                    $('#minim_value_$pluss').css('background-color','');
                    $('#maxim_value_$pluss').val(last_minim);
                }
            });

                $('#level_1_value_$pluss').blur(function() {
                    var row_id = Number($row_id);
                    var row_sumber = $('td',$('#'+(row_id-1)));
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()

                        objek[a] = nilai;
                    })

                    console.log('row_id :: $row_id || ' + row_sumber + row_id);
                    console.log(objek);

            });
            </script>";
        $member = "";
        $member .= $strTbl;
        // endregion


        echo $member;

        break;

    case "viewCashBackMember_00":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");

        // region customer level
        /* --------------------------------------------------------------------
           * THEAD
           * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th>no</th>";
        foreach ($level_header as $kolom => $arrHeader) {
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
        $count_id = 888;
        // arrPrintKuning($level_data);
        foreach ($level_data as $master_datum) {
            $no++;
            $count_id++;
            $jenis = $master_datum['jenis'];
            $minim = $master_datum['minim'];
            $row_id = "row_$count_id";
            $strBody .= "<tr id='$row_id'>";
            $strBody .= "<td>$no</td>";
            foreach ($level_header as $kolom => $attrs) {
                $td_id = $kolom . "_" . $count_id;
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


                if ($kolom == "action") {
                    $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim";
                    $strBody .= "<td $attr id='$td_id'>";
                    $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_cashback('$row_id');\"><i class='fa fa-pencil'></i></button>";
                    $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                    $strBody .= "</td>";
                }
                else {
                    $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                }

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
        // cekHijau("$my_controler");
        // arrPrint($level_header);
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_cashback' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_cashback'>";
        $strFoot .= "<th></th>";
        foreach ($level_header as $kolom => $arrHeader) {
            $attrs = $arrHeader;
            // if (strstr($kolom, 'level')) {
            //
            //     $kolom_nama = "$kolom[8]";
            // }
            // else {
            //     $kolom_nama = $kolom;
            // }
            $kolom_id = $kolom . "_values";
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";
            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_cashback' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_cashback' id='$kolom_id' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        $strFoot .= "<input type='hidden' form='my_form_cashback' name='tipe' id='tipe_values' value='$tipe'><input type='hidden' form='my_form_cashback' name='minim_be' id='maxim_values' value=''>
<input type='hidden' form='my_form_cashback' name='my_controler' value='$my_controler'>
<input type='hidden' form='my_form_cashback' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        $tbl_id = "cashback";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
        $strTbl .= "<table class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<form>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        // $strTbl .= "</form>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";
        $strTbl .= "<script>
        
               function btn_edit_cashback(r) {
                    var row_sumber = $('td',$('#'+r));
            console.log(row_sumber);
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                        var nilai = $(b).html()
                
                        objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_cashback'));
                    var last_key = row_target.length - 1;
                    // console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_values').prop('readonly', true);
                        $('#minim_values').prop('readonly', true);
                    }
               }
               
            //   minim_values
            $('#minim_values').blur(function() {
                // var row_sumber = $row_id;
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()
            
                    objek[a] = nilai;
                })
                
                var last_minim = objek['2'];
                var now_minim = $('#minim_values').val();
                
                if(Number(now_minim) <= Number(last_minim)){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                    });
                    
                    $('#minim_values').css('background-color','#fff700ad');
                }
                else {
                    $('#minim_values').css('background-color','');
                    $('#maxim_values').val(last_minim);
                }
            });
            </script>";
        $member = "";
        $member .= $strTbl;
        // endregion

        echo $member;

        break;

    case "viewCashBackMember":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = "";
        $childs = array();
        foreach ($level_header as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";

            /* ----------------------------------------------------------------------
             * yg menjadi parent
             * ----------------------------------------------------------------------
             * */
            if ($child != true) {

                $level_header_0[$kolom] = $arrHeader;
            }

            if (!isset($arrHeader['parent'])) {

                $level_header_00[$kolom] = $arrHeader;
            }

            /* ----------------------------------------------------------------------
             * yg mnejadi anak
             * ----------------------------------------------------------------------
             * */
            if (isset($arrHeader['parent_ky'])) {

                $childs[$parent_ky][$kolom] = $arrHeader;
            }
        }

        // region customer level
        /* --------------------------------------------------------------------
           * THEAD
           * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($level_header_0 as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";
            $attr_header = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 2;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";

        }
        $strHead .= "</tr>";

        /* -----------------------------------------------------------------------------------
         * THEAD
         * header anakan
         * -----------------------------------------------------------------------------------
         * */
        $strHead .= "<tr class='bg-info'>";
        foreach ($childs as $ky => $childrens) {
            foreach ($childrens as $kolom => $children) {
                $hLabel = isset($children['label']) ? $children['label'] : $kolom;
                $hattr_header = isset($children['attr_header']) ? $children['attr_header'] : "";

                $strHead .= "<th colspan='$colspan' $hattr_header>$hLabel</th>";
            }
        }
        $strHead .= "</tr>";
        $strBody = "";
        $row_id = "";
        foreach ($level_data_0 as $jenis_kdata => $level_data) {
            $pluss = $jenis_kdata;
            // arrPrintPink($level_data);
            // arrPrintPink($level_header);


            // arrPrintHijau($childs);
            // arrPrintKuning($level_header_0);
            // arrPrintKuning($level_header_00);
            /* --------------------------------------------------------------------
             * TBODY
             * --------------------------------------------------------------------*/
            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            // matiHere($jenistr);
            $count_id = 888;
            $row_id = "";
            // arrPrintKuning($level_data);
            foreach ($level_data as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no</td>";
                foreach ($level_header_00 as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
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
                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";
                        // arrPrintKuning($master_datum);
                        // $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                // if ($kolom == "status") {
                                $link_hapus = "";
                                $checked = $nilai == 1 ? "checked" : "";
                                // $strBody .= "<td $attr id='$td_id'>";

                                // $strBody .= "<div class='funkyradio'>";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                // $strBody .= "</div>";

                                // $strBody .= "</td>";
                                // }
                                break;
                        }
                    }
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                    // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;


                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                        $strBody .= "</td>";
                    }
                    else {
                        $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                    }

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }
                $strBody .= "</tr>";

            }


            // }
            // $jenis_kdata = "";
            if (isset($level_data) && count($level_data) == 0) {
                $jenis_kdata = $jenis_kdata;
            }

            /* --------------------------------------------------------------------
                        * TFOOD
                        * --------------------------------------------------------------------*/
            // arrPrint($level_header);
            $link_save = base_url() . "diskon/Setting/do_save_member";
            $strFoot = "";
            $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
            $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
            $strFoot .= "<th></th>";
            foreach ($level_header_00 as $kolom => $arrHeader) {
                $attrs = $arrHeader;

                $kolom_id = $kolom . "_value_" . $pluss;
                $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
                $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
                $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
                $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
                // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
                $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

                switch ($hTipe) {
                    default:
                    case "text":
                        $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                        break;
                    case "select":
                        $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                        $str_input .= "<option value=''>------</option>";
                        foreach ($data_srcs as $data_src) {
                            $str_input .= "<option>$data_src</option>";
                        }
                        $str_input .= "</select>";
                        break;
                }

                $strFoot .= "<th>$str_input</th>";
            }
            $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
            $strFoot .= "</tr>";
            $strFoot .= "</form>";

            $tbl_id = "member";
            $strTbl = "";
            $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
            $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
            $strTbl .= "<lable class='text-uppercase'>setting untuk $jenis_kdata</lable>";
            $strTbl .= "<table class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
            $strTbl .= "<thead class='text-uppercase'>";
            $strTbl .= $strHead;
            $strTbl .= "</thead>";
            $strTbl .= "<tbody>";
            $strTbl .= $strBody;
            $strTbl .= "</tbody>";

            // $strTbl .= "<form>";
            $strTbl .= "<tfoot>";
            $strTbl .= $strFoot;
            $strTbl .= "</tfoot>";
            // $strTbl .= "</form>";

            $strTbl .= "</table>";
            $strTbl .= "</div>";
            $link_status = base_url() . "diskon/Setting/do_status_cek/MdlDiskonCustomer";
            $strTbl .= "<script>
        
               function btn_edit_$pluss(r) {
                   console.log(r);
                    var row_sumber = $('td',$('#'+r));
            
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                          var nilai = $(b).html()
                
                          objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_$pluss'));
                    var last_key = row_target.length - 1;
                    console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        $('#minim_value_$pluss').prop('readonly', true);
                    }

               }
               
               //   minim_values
            $('#minim_value_$pluss').blur(function() {
                // var row_sumber = $row_id;
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()
            
                    objek[a] = nilai;
                })
                
                var last_minim = objek['2'];
                var now_minim = $('#minim_value_$pluss').val();
                
                if(Number(now_minim) <= Number(last_minim)){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                    });
                    
                    $('#minim_value_$pluss').css('background-color','#fff700ad');
                }
                else {
                    $('#minim_value_$pluss').css('background-color','');
                    $('#maxim_value_$pluss').val(last_minim);
                }
            });
               
               // cek status
                function status_cek(d,r) {

                    $('#result_bottom').load('$link_status?id='+d);
                  
                  // console.log(db_id);
                  
                    $('tr').css('background-color','');
                    $('#'+r).css('background-color','#f1e7e7');
                }
            </script>";
            $member = "";
            $member .= $strTbl;
            // endregion


            echo $member;
        }
        if (count($level_data_0) == 0) {

            /* --------------------------------------------------------------------
                       * TFOOD
                       * --------------------------------------------------------------------*/
            // arrPrint($level_header);
            $link_save = base_url() . "diskon/Setting/do_save_member";
            $strFoot = "";
            $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
            $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
            $strFoot .= "<th></th>";
            foreach ($level_header_00 as $kolom => $arrHeader) {
                $attrs = $arrHeader;

                $kolom_id = $kolom . "_value_" . $pluss;
                $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
                $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
                $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
                $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
                // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
                $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

                switch ($hTipe) {
                    default:
                    case "text":
                        $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                        break;
                    case "select":
                        $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                        $str_input .= "<option value=''>------</option>";
                        foreach ($data_srcs as $data_src) {
                            $str_input .= "<option>$data_src</option>";
                        }
                        $str_input .= "</select>";
                        break;
                }

                $strFoot .= "<th>$str_input</th>";
            }
            $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
            $strFoot .= "</tr>";
            $strFoot .= "</form>";

            $tbl_id = "member";
            $strTbl = "";
            $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
            $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
            $strTbl .= "<lable class='text-uppercase'>setting untuk $jenis_kdata</lable>";
            $strTbl .= "<table class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
            $strTbl .= "<thead class='text-uppercase'>";
            $strTbl .= $strHead;
            $strTbl .= "</thead>";
            $strTbl .= "<tbody>";
            $strTbl .= $strBody;
            $strTbl .= "</tbody>";

            // $strTbl .= "<form>";
            $strTbl .= "<tfoot>";
            $strTbl .= $strFoot;
            $strTbl .= "</tfoot>";
            // $strTbl .= "</form>";

            $strTbl .= "</table>";
            $strTbl .= "</div>";
            $link_status = base_url() . "diskon/Setting/do_status_cek/MdlDiskonCustomer";
            $strTbl .= "<script>
        
               function btn_edit_$pluss(r) {
                   console.log(r);
                    var row_sumber = $('td',$('#'+r));
            
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                          var nilai = $(b).html()
                
                          objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_$pluss'));
                    var last_key = row_target.length - 1;
                    console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        $('#minim_value_$pluss').prop('readonly', true);
                    }

               }
               
               //   minim_values
            $('#minim_value_$pluss').blur(function() {
                // var row_sumber = $row_id;
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()
            
                    objek[a] = nilai;
                })
                
                var last_minim = objek['2'];
                var now_minim = $('#minim_value_$pluss').val();
                
                if(Number(now_minim) <= Number(last_minim)){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                    });
                    
                    $('#minim_value_$pluss').css('background-color','#fff700ad');
                }
                else {
                    $('#minim_value_$pluss').css('background-color','');
                    $('#maxim_value_$pluss').val(last_minim);
                }
            });
               
               // cek status
                function status_cek(d,r) {

                    $('#result_bottom').load('$link_status?id='+d);
                  
                  // console.log(db_id);
                  
                    $('tr').css('background-color','');
                    $('#'+r).css('background-color','#f1e7e7');
                }
            </script>";
            $member = "";
            $member .= $strTbl;
            // endregion


            echo $member;
        }
        break;

    case "viewDiskonFreeProduk":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;
        /* --------------------------------------------------------------------
               * THEAD
               * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th>no</th>";
        foreach ($level_header as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th>$hLabel</th>";
        }
        $strHead .= "</tr>";
        $jenis_kdata = "";
        $strBody = "";
        // ----------
        foreach ($level_data_0 as $jenis_kdata => $level_data) {
            $pluss = $jenis_kdata;
            // arrPrintPink($level_data);
            /* --------------------------------------------------------------------
             * TBODY
             * --------------------------------------------------------------------*/
            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            // matiHere($jenistr);
            $count_id = 777;
            $row_id = "";
            //             arrPrintWebs($level_header);
            //             arrPrintKuning($level_data);
            //             arrPrintKuning($produk_harga);
            /*--body--*/
            foreach ($level_data as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $produk_id = isset($master_datum['produk_id']) ? $master_datum['produk_id'] : "none";
                $free_produk_id = isset($master_datum['free_produk_id']) ? $master_datum['free_produk_id'] : "none";
                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $db_id = $master_datum['id'];

                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no</td>";
                foreach ($level_header as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    // $nilai = $master_datum[$kolom];
                    $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                    // cekHijau("$kolom");
                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;

                    switch ($kolom) {
                        case "tanggal_start":
                        case "tanggal_stop":
                            $nilai_0 = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";
                            $nilai_00 = strtotime($nilai_0);
                            // if ($nilai_00 < 0) {
                            //                         //     cekBiru("$nilai_0 || $nilai_00");
                            //                         //
                            //                         // }
                            //                         // else {
                            //                         //     cekKuning("$nilai_0 || $nilai_00");
                            //                         //     $nilai = $nilai_0;
                            //                         // }
                            // cekBiru($nilai);
                            $nilai = $nilai_00 < 0 ? "~" : $nilai_0;
                            $nilai_f = isset($attrs['format']) && $nilai_00 > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai;

                            break;
                        case "hour_start":
                        case "hour_end":
                            $nilai_0 = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";
                            $nilai_f = formatTanggal($nilai_0, "H:i");
                            break;
                        default:
                            $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                            $nilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai;
                            break;
                    }


                    // $nilai_f = isset($attrs['format']) ? ($nilai != 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                    $sub_keys = isset($attrs['sub_key']) ? $attrs['sub_key'] : "";

                    $sub_body = "";
                    if (isset($attrs['sub_key'])) {
                        $sub_params = $attrs['sub_key']['harga'];
                        $nilai_key = isset($sub_params['nilai_key']) ? $sub_params['nilai_key'] : "none";
                        $produk_sub_id = isset($master_datum[$nilai_key]) ? $master_datum[$nilai_key] : "none";

                        $sub_nilai = $produk_harga[$produk_sub_id];
                        $sub_nilai_f = isset($sub_params['format']) ? ($sub_nilai > 0 ? $sub_params['format']('harga', $sub_nilai, $jenistr, $modul_path) : $sub_nilai) : $sub_nilai;

                        // $sub_body .= "<div>";
                        $sub_body .= "<span class='text-red'>$sub_nilai_f</span>";
                        // $sub_body .= "</div>";
                    }

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
                        $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : "<span>" . $nilai_f . "</span>";
                    }
                    else {
                        $nilai_link = "<span>" . $nilai_f . "</span>";
                    }

                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                // if ($kolom == "status") {
                                $link_hapus = "";
                                $checked = $nilai == 1 ? "checked" : "";
                                // $strBody .= "<td $attr id='$td_id'>";

                                // $strBody .= "<div class='funkyradio'>";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                // $strBody .= "</div>";

                                // $strBody .= "</td>";
                                // }
                                break;
                        }
                    }

                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_free_produk?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                        $strBody .= "</td>";
                    }
                    // elseif ($kolom == "status") {
                    //     $link_hapus = "";
                    //     $checked = $nilai == 1 ? "checked" : "";
                    //     $strBody .= "<td $attr id='$td_id'>";
                    //
                    //     // $strBody .= "<div class='funkyradio'>";
                    //     $strBody .= "<div class='funkyradio-success'>";
                    //     $strBody .= "<input type='checkbox' $checked onclick=\"status_cek('$db_id', '$row_id');\">";
                    //     $strBody .= "</div>";
                    //     // $strBody .= "</div>";
                    //
                    //     $strBody .= "</td>";
                    // }
                    else {
                        $strBody .= "<td $attr id='$td_id' title='id:$produk_id'>$nilai_link $sub_body</td>";
                    }

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }

                } // data body
                $strBody .= "</tr>";

            }


        }
        // -------------
        /* --------------------------------------------------------------------
            * TFOOD
            * --------------------------------------------------------------------*/
        // arrPrint($level_header);
        $link_save = base_url() . "diskon/Setting/do_save_free_produk";
        $strFoot = "";
        //            $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        // $strFoot .= "<th><button type='reset' class='btn btn-danger'>clear</button></th>";
        $strFoot .= "<th><input type='reset' class='btn btn-danger' value='clear'></th>";
        foreach ($level_header as $kolom => $arrHeader) {
            $attrs = $arrHeader;
            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
            $link_srcs = isset($attrs['link_srcs']) ? base_url() . $attrs['link_srcs'] . "?id=$kolom_id" : "";

            switch ($hTipe) {
                default:
                case "text":
                    if (isset($attrs['link_srcs'])) {
                        // $str_input = "<input type='$hTipe' form='my_form_$pluss' style='background-color: lightyellow;' data-toggle='popover' data-content='test' autocomplete='off' onclick=\"this.select();sswal({html: 'ketikan nama produk yang dicari'});\" onkeyup=\"$('#produk_src_$kolom').load(encodeURI('$link_srcs&key='+ this.value));\" name='$kolom' id='$kolom_id' $attr value='$nilai'><div id='produk_src_$kolom'></div>";
                        $lookup_str = "$('#produk_src_$kolom').load(encodeURI('$link_srcs&key='+ this.value));";
                        $str_input = "<input type='$hTipe' form='my_form_$pluss' style='background-color: lightyellow;' autocomplete='off' onclick=\"this.select();$('#produk_src_$kolom').text('ketikan nama produk');$lookup_str\" onkeyup=\"$lookup_str\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                        $str_input .= "<input type='text' autocomplete='off' disabled id='harga_$kolom_id'>";
                        $str_input .= "<div id='produk_src_$kolom'></div>";
                    }
                    else {
                        $str_input = "<input type='$hTipe' form='my_form_$pluss' autocomplete='off' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    }
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                    $str_default = "Pilih Produk";
                    $str_input .= "<option value=''>$str_default</option>";
                    foreach ($data_srcs as $kid => $data_src) {
                        $str_input .= "<option text='$data_src' value='$kid'>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
                case "checkbox":
                    // $str_input = "<div $attr><input type='checkbox' name='$kolom' id='$kolom_id'></div>";
                    $str_input = "<input type='checkbox' name='$kolom' id='$kolom_id'  style='vertical-align: middle;'>";
                    break;
            }

            $strFoot .= "<th style='vertical-align: top;'>$str_input</th>";
        }
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'>
                         <input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                         <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                         <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        //            $strFoot .= "</form>";

        $tbl_id = "free_produk";

        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control,
                        .table>tfoot>tr>th>div.form-control,
                        .table>tfoot>tr>th>input.form-control,
                        .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";

        $strTbl .= "<lable class='text-uppercase'>setting untuk diskon $jenis_kdata</lable>";
        $link_f5 = base_url() . "diskon/setting/viewDiskonFreeProduk";
        $strTbl .= " <button type='button' class='btn btn-info' onclick=\"$('#empat').load('$link_f5');\">f5</button>";

        $strTbl .= "<form method='post' class='' id='my_form_$pluss' action='$link_save' target='result'>";

        $strTbl .= "<table class='table table-condensade table-striped compact' style='margin=0' id='$tbl_id'>";
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
        $strTbl .= "</form>";

        $link_status = base_url() . "diskon/Setting/do_status_cek";
        $link_kelipatan = base_url() . "diskon/Setting/do_kelipatan_cek";
        $strTbl .= "<script>

                function isValidDate(s) {
                  var bits = s.split('-');
                  var d = new Date(bits[0] + '-' + bits[1] + '-' + bits[2]);
                  return !!(d && (d.getMonth() + 1) == bits[1] && d.getDate() == Number(bits[2]));
                }

               function btn_edit_$pluss(r) {
                    var row_sumber = $('td',$('#'+r));
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                        var nilai = $(b).html()
                        objek[a] = nilai;
                    })

                    var row_target = $('th', $('#form_input_$pluss'));
                    var last_key = row_target.length - 1;

                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input', $(d));
                        var nilai_select = $('select', $(d));
                        if(c != last_key){ //jika bukan kolom terakhir, karena kolom terahkhir pada row adalah tombol2 tools edit dll
                            if( $(objek[c], 'span').length > 2 ){
                                $(nilai).val( $( $(objek[c], 'span')[0]).html() );
                                $(nilai)[1].value = removeCommas( $( $( $(objek[c], 'span')[2]).html() ).html() );
                            }
                            else{
                                if( $( $( $(objek[c], 'span')[0]).html() , 'span').length > 0 ){
                                    $(nilai).val( removeCommas( $( $( $(objek[c], 'span')[0]).html() , 'span').html() ) );
                                }
                                else{
                                    //detect dateTime
                                    if( Date.parse( $( $(objek[c], 'span')[0]).html() ) * 1 > 0 && $( $(objek[c], 'span')[0]).html().length > 10 ){
                                        console.log('ini date time: '+  $( $(objek[c], 'span')[0]).html());
                                    }
                                    else{
                                        if( $( $(objek[c], 'span')[0]).html() != '00:00:00' && $( $(objek[c], 'span')[0]).html() != '0000-00-00 00:00:00'){
                                            //jika bukan date format yang salah
                                            $(nilai).val( $( $(objek[c], 'span')[0]).html() );
                                        }
                                        else{
                                            console.error('date format salah => ' + $( $(objek[c], 'span')[0]).html() );
                                        }
                                    }
                                }
                            }
                            $(nilai_select).val(objek[c]);
                            var valSelect = $(nilai_select).find('option[text=\"'+objek[c]+'\"]').val();
                            var simul = objek[c].split(' ')
                            if( isValidDate(simul[0]) ){
                                var date = new Date(simul[0]);
                                var newDate = date.getFullYear() + '-' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1)))
                                $(nilai).val( newDate );

                                console.log( 'newDate: ' + newDate )
                            }
                            $(nilai_select).val(valSelect);
                        }
                    })

                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        // $('#minim_value_$pluss').prop('readonly', true);
                        $('#produk_value_$pluss').prop('readonly', true);
                        $('#label_diskon_value_$pluss').prop('readonly', true);
                    }

                    exeFreeProdukKeyup();
                    exeMinimValueKeyup();
               }

                $('#produk_value_free_produk').selectpicker({ dropdownParent: $('div') }).selectpicker('valx', ['1']);
                $('#free_produk_value_free_produk').selectpicker({ dropdownParent: $('div') }).selectpicker('valx', ['1']);

               // ngitung diskon dr qty free_produk
               var exeFreeProdukKeyup = function(){
                    $('#qty_free_produk_value_$pluss').keyup(function() {
                      var harga = $('#harga_produk_value_$pluss').val();
                      // console.log(harga);
                      var qty = $('#minim_value_$pluss').val();
                      var qty_free = $('#qty_free_produk_value_free_produk_$pluss').val();
                      var harga_free = $('#harga_free_produk_value_$pluss').val();
                      var harga_total = harga * qty;
                      var harga_free_total = harga_free * qty_free;
                      var diskon = harga_free_total / harga_total *100;

                      $('#persen_value_free_produk').val(diskon);
                        console.log(harga);
                        console.log(harga_free);
                        console.log('keyup #qty_free_produk_value_$pluss || ' + diskon)
                    })
                    // console.log('init #qty_free_produk_value_$pluss')
               }


                // ngitung diskon dr qty master_produk
                var exeMinimValueKeyup = function(){
                    $('#minim_value_$pluss').keyup(function() {
                      var harga = $('#harga_produk_value_$pluss').val();
                      // console.log(harga);
                      var qty = $('#minim_value_$pluss').val();
                      var qty_free = $('#qty_free_produk_value_free_produk_$pluss').val();
                      var harga_free = $('#harga_free_produk_value_$pluss').val();
                      var harga_total = harga * qty;
                      var diskon = harga_free / harga_total *100;

                      $('#persen_value_free_produk').val(diskon);

                      // console.log('keyup #minim_value_$pluss')
                    })
                  // console.log('init #minim_value_$pluss')
                }

                exeFreeProdukKeyup();
                exeMinimValueKeyup();
                
                // cek status
                function status_cek(d,r) {
                  $('#result_bottom').load('$link_status?id='+d);
                  
                  // console.log(db_id);
                  
                  $('tr').css('background-color','');
                  $('#'+r).css('background-color','#f1e7e7');
                }
                
                // cek status
                function kelipatan_cek(d,r) {
                  $('#result_bottom').load('$link_kelipatan?id='+d);
                  
                  // console.log(db_id);
                  
                  $('tr').css('background-color','');
                  $('#'+r).css('background-color','#f1e7e7');
                }
                
                


</script>";
        $member = "";
        $member .= $strTbl;


        echo $member;
        break;

    case "viewTebusMurah":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = "";
        $strGet = "1";
        /* --------------------------------------------------------------------
   * THEAD
   * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th>no</th>";
        foreach ($level_header as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th>$hLabel</th>";
        }
        $strHead .= "</tr>";

        // ------------
        foreach ($level_data_0 as $jenis_kdata => $level_data) {
            $pluss = $jenis_kdata;
            // arrPrintPink($level_data);

            /* --------------------------------------------------------------------
             * TBODY
             * --------------------------------------------------------------------*/
            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            // matiHere($jenistr);
            $count_id = 888;
            $row_id = "";
            // arrPrintKuning($level_data);
            foreach ($level_data as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $persen = isset($master_datum['persen']) ? $master_datum['persen'] : 0;
                $nilai_db = isset($master_datum['nilai']) ? $master_datum['nilai'] : 0;
                $produk_id = isset($master_datum['produk_id']) ? $master_datum['produk_id'] : 0;
                $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                $minim_f = number_format($minim, '0', "", ".");
                $nilai_db_f = number_format($nilai_db, '0', "", ".");

                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no*</td>";
                foreach ($level_header as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    // $nilai = $master_datum[$kolom];
                    // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                    $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                    $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                    $strGet = "id=$produk_id";
                    if (isset($attrs['links'])) {
                        // matiHere();
                        $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                        $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                        $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                        $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                        // $strTitle_head = urlencode(trim("$link_title $minim"));
                        $strTitle_head = (trim("$link_title $minim"));
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
                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                // if ($kolom == "status") {
                                $link_hapus = "";
                                $checked = $nilai == 1 ? "checked" : "";
                                // $strBody .= "<td $attr id='$td_id'>";

                                // $strBody .= "<div class='funkyradio'>";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                // $strBody .= "</div>";

                                // $strBody .= "</td>";
                                // }
                                break;
                        }
                    }
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                    // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;


                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";

                        $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                        $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                        $strTitle_head = "Produk tebus Murah Minimal Transaki Rp. $minim_f Max. Diskon : Rp. $nilai_db_f";
                        $linking = "diskon/Setting/viewProdukMurah?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size";
                        $linkDetile = base_url() . $linking . "";
                        $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                        $strBody .= "<button type='button' class='btn btn-info' onclick=\"$linkModal\">produk murah</button>";
                        $strBody .= "</td>";
                    }
                    else {
                        $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                    }

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }
                $strBody .= "</tr>";

            }

        }
        if (count($level_data) == 0) {
            $jenis_kdata = "Tebus Murah";
        }
        // ---------------------
        /* --------------------------------------------------------------------
     * TFOOD
     * --------------------------------------------------------------------*/
        // arrPrint($level_header);
        $link_save = base_url() . "diskon/Setting/do_save_tebus_murah";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>";
        foreach ($level_header as $kolom => $arrHeader) {
            $attrs = $arrHeader;

            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
            $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";

            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        $tbl_id = "tebus_murah";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
        $strTbl .= "<lable class='text-uppercase'>setting untuk $jenis_kdata</lable>";
        $strTbl .= "<table class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<form>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        // $strTbl .= "</form>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";
        $link_status = base_url() . "diskon/Setting/do_status_cek/MdlDiskonCustomer";
        // matiHere(__LINE__ . " $link_status");
        $strTbl .= "<script>
        
               function btn_edit_$pluss(r) {
                   console.log(r);
                    var row_sumber = $('td',$('#'+r));
            
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                          var nilai = $(b).html()
                
                          objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_$pluss'));
                    var last_key = row_target.length - 1;
                    console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        $('#minim_value_$pluss').prop('readonly', true);
                    }

               }
               
               //   minim_values
                $('#minim_value_$pluss').blur(function() {
                    // var row_sumber = $row_id;
                    var row_sumber = $('td',$('#$row_id'));
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                        var nilai = $(b).html()
                
                        objek[a] = nilai;
                    })
                    
                    var last_minim = objek['2'];
                    var now_minim = $('#minim_value_$pluss').val();
                    
                    if(Number(now_minim) <= Number(last_minim)){
                        swal({
                            title: 'Opsss.. !!',
                            html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                        });
                        
                        $('#minim_value_$pluss').css('background-color','#fff700ad');
                    }
                    else {
                        $('#minim_value_$pluss').css('background-color','');
                        $('#maxim_value_$pluss').val(last_minim);
                    }
                });
            
                $('#persen_value_$pluss').blur(function() {
                    var now_minim = $('#minim_value_$pluss').val();
                    var now_persen = $('#persen_value_$pluss').val();
                    var new_nilai = now_persen / 100 * now_minim;
                    
                   $('#nilai_value_$pluss').val(new_nilai);
                });
                
                $('#nilai_value_$pluss').blur(function() {
                    var now_minim = $('#minim_value_$pluss').val();
                    var now_nilai = $('#nilai_value_$pluss').val();
                    var new_persen = now_nilai / now_minim * 100;
                    
                   $('#persen_value_$pluss').val(new_persen);
                });
               
               // cek status
                function status_cek(d,r) {

                    $('#result_bottom').load('$link_status?id='+d);
                  
                  // console.log(db_id);
                  
                    $('tr').css('background-color','');
                    $('#'+r).css('background-color','#f1e7e7');
                }
            </script>";
        $member = "";
        $member .= $strTbl;

        echo $member;

        break;
}