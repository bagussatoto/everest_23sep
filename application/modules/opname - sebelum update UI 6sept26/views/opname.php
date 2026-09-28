<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */
//arrPrint ($style);
//cekHere($mode);
switch ($mode) {

    case "view":

        $p = New Layout("$title", "$subTitle", "application/template/default.html");
        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                //                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                //                "menu_sub" => callSubMEnu(),
                "content" => "",
                "profile_name" => $this->session->login['nama'],
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        $contents = isset($contens) ? $contens : "";
        $contents .= isset($scriptLoad) ? $scriptLoad : "";

        $p->setContent($contents);
        $p->render();
        break;
    case "index" :
        // arrPrint($content);
        // cekHere(MODUL_TEMPLATE_PATH);
        //        $p = New Layout("", "", "application/template/pages2.html");
        // $p = New Layout("", "", MODUL_TEMPLATE_PATH ."application/template/default.html");
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/opname.html");
        $template = array(
            'table_open' => '<table id="table" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="" style="text-align: center;">',
        );
        $this->table->set_template($template);
        $content = "";
        if (is_array($arrayHeader) && sizeof($arrayHeader) > 0) {
            $header_f = array();
            //            $header_f[] = array('data' => "No", 'class' => 'text-center text-muted');
            foreach ($arrayHeader as $kolom => $label) {
                $header_f[] = array('data' => $label, 'class' => 'text-center text-muted');
            }
            $this->table->set_heading($header_f);
            if (sizeof($items) > 0) {
                //arrPrint($items);
                foreach ($items as $key => $data) {
                    //                    arrPrint($data);
                    $isi = array();
                    foreach ($arrayHeader as $kolom => $label) {
                        $value = $data[$kolom];
                        $isi[] = array('data' => $value);
                    }
                    //                    arrPRint($isi);
                    $this->table->add_row($isi);

                }
                //                die();

            }
            else {
                $this->table->add_row(array(
                    'data' => "no category found for ",
                    'colspan' => count($arrayHeader) + 2,
                    'class' => 'text-center',
                ));
            }

            $content .= ($this->table->generate());
        }
        $content = "";
        $content .= "harap menunggu sedang mempersiapkan data ";
        //        $content .= "<br><a href='JavaScript:Void(0);' class='btn btn-success' onclick='$btnClick'>Opname</a>";
        // $content .= "<br><a href='JavaScript:Void(0);' class='btn btn-success' onclick=\"$btnClick\">Opname by kategori</a>";
        // $content .= "<a href='JavaScript:Void(0);' class='btn btn-success' onclick=\"$btnClick_2\">Opname by rak</a>";
        // cekHere("$link_option");
        $script_bottom = "";
        $script_bottom .= "<script>";
        // $link_option = MODUL_PATH . get_class($this) . "view/Produk/FolderProduk/persediaan_produk/kategori";
        $script_bottom .= "$('#content_body').load('$link_option');";

        // arrPrintHijau(url_segment());

        $script_bottom .= "$('#undoneList').load('$link_undoneList');";
        $script_bottom .= "</script>";
        $script_bottom .= $scriptLoad;
        // cekHere("$link_option");
        $p->addTags(
            array(
                "title" => $title,
                "sub_title" => "Opname $sub_title",
                "menu_left" => callMenuLeft(),
                //                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                //                "menu_sub"     => callSubMenu(),
                "content" => $content,
                "profile_name" => $this->session->login['nama'],
                "script_bottom" => $script_bottom,
                // "scriptLoad"    => $script_bottom,
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        //        $p->setContent($contens);
        $p->render();

        break;

    case "doPrint":
        //        arrPrint($fixedElements);
        $title_x = urldecode($this->uri->segment(4));
        $p = New Layout("", "", "application/template/opname.html");
        $p->addTags(
            array(
                "content" => $content,
                "title" => "stok opname $title_x",
                "companyProfile" => $companyProfile['companyProfile']['contents'][0],
                //                "fixedElements" => $fixedElements,

            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->render();
        break;
    case "cekTransaksiGantung":
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/viewdetails.html");

        // arrPrintPink($mLabel);
        // arrPrintPink($stepModul);
        // arrPrintPink($kelompokMaster);
        $bodies = "";
        $bodies = "";
        $bodies .= "<div class='row'>";
        foreach ($kelompokMaster as $master_jenis => $items) {
            $master_label = $masterLabel[$master_jenis];
            $bodies .= "<div class='col-md-6'>";
            $bodies .= "<b title='$master_jenis' class='text-uppercase'>$master_label</b>";
            foreach ($items as $jenis_tr => $jenies) {
                $jenis_label = $stepLabel[$jenis_tr];
                $jenis_jml = sizeof($jenies);
                $jenis_modul = base_url() . $stepModul[$jenis_tr] . "/";
                $bodies .= "<div class='row text-capitalize text-red' id='$jenis_tr' title='trj_$jenis_tr' style='margin-left: 10px;cursor: pointer;'>$jenis_label <span class='badge bg-red'>$jenis_jml</span></div>";
                $bodies .= "<div id='ch_$jenis_tr' class='hidden'>";
                foreach ($jenies as $jeny) {

                    // arrPrintPink($jenies);
                    $nomer = $jeny->nomer;
                    $dtime = $jeny->dtime;
                    $oleh_nm = $jeny->oleh_nama;
                    $cabang_nm = $jeny->cabang_nama;
                    $nomer_f = formatField_he_format("nomer", $nomer, $jenis_tr, $jenis_modul);
                    $dtime_f = formatField_he_format("fulldate", $dtime, $jenis_tr, $jenis_modul);

                    $bodies .= "<div class='row' style='margin-left: 0;'>";
                    $bodies .= "<div class='col-md-4'>$dtime_f</div>";
                    $bodies .= "<div class='col-md-3'>$nomer_f</div>";
                    $bodies .= "<div class='col-md-3 text-uppercase'>$cabang_nm</div>";
                    // $bodies .= "<div class='col-md-3'>$oleh_nm</div>";
                    $bodies .= "</div>";

                }
                $bodies .= "</div>";
            }
            $bodies .= "</div>";
        }
        $bodies .= "</div>";
        $bodies .= "<div class='alert alert-danger text-center' style='margin-top: 20px'>";
        $bodies .= "<h4 class='no-margin'>Opname tidak bisa dilakukan sebelum list transaksi diselesaikan</h4>";
        $bodies .= "</div>";

        $bodies .= "<script>
            $('.text-red').click(function() {
              let id = $(this).attr('id');
              
              // console.log('id', id);
              $('#ch_' + id).toggle().removeClass('hidden');
            });


        </script>";

        echo $content = $bodies;
        // return $bodies;
        $script_bottom = "";


        // echo $content;

        // $p->addTags(
        //     array(
        //         "title"         => $title,
        //         "sub_title"     => "$sub_title",
        //         // "menu_left"        => callMenuLeft(),
        //         //                                "trans_menu" => callTransMenu(),
        //         // "float_menu_atas"  => callFloatMenu('atas'),
        //         // "float_menu_bawah" => callFloatMenu(),
        //         // "menu_taskbar"     => callMenuTaskbar(),
        //         // "btn_back"         => callBackNav(),
        //         //                "menu_sub"     => callSubMenu(),
        //         "content"       => $content,
        //         "profile_name"  => $this->session->login['nama'],
        //         "script_bottom" => $script_bottom,
        //     )
        // );

        // if (isset($lebar_modal)) {
        //     $p->setLebarModal($lebar_modal);
        // }
        //
        // //        $p->setContent($contens);
        // $p->render();
        break;
    case "viewOpnameAktive":

        // $p = New Layout("", "", "application/template/opname.html");
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/viewdetails.html");
        // cekMerah(MODUL_TEMPLATE_PATH . "template/viewdetails.html");

        $header = "";
        $header .= "<tr class='text-uppercase bg-header'>";
        $header .= "<th rowspan='3' class='text-center'>no</th>";
        $header .= "<th rowspan='3' class='text-center' valign='middle'>cabang</th>";
        $jml_header_0 = sizeof($header_0);
        $jml_header_00 = sizeof($header_00);
        $jml_header_000 = sizeof($header_000);
        $colspan_000 = $jml_header_0 * $jml_header_00;
        foreach ($header_000 as $ky => $item) {
            $label_000 = $item['label'];
            $header .= "<th class='text-center text-uppercase' colspan='$colspan_000'>$label_000</th>";
        }
        $header .= "</tr>";


        $header .= "<tr class='text-uppercase  bg-header'>";
        for ($i = 1; $i <= $jml_header_000; $i++) {

            foreach ($header_00 as $ky => $item) {
                $label_00 = $item['label'];
                $header .= "<th class='text-center' colspan='$jml_header_0'>$label_00</th>";
            }
        }
        $header .= "</tr>";

        $header .= "<tr class='bg-header text-capitalize'>";
        for ($i = 1; $i <= ($jml_header_00 * $jml_header_000); $i++) {

            foreach ($header_0 as $ky => $item) {
                $label_0 = $item['label'];
                // $anakan_0 = $item['anakan'];
                // $jml_header = sizeof($anakan_0);
                $header .= "<th class='text-center'>$label_0</th>";
            }
        }
        $header .= "</tr>";

//arrPrint($dt_opname);
        $ok = "<i class='fa fa-check text-green'></i>";
        $noneed = "<i class='fa fa-times text-grey-1'></i>";
        $labelWarning = array();
        $body = "";
        $no = 0;
        foreach ($dataCb as $cb_id => $cb_nama) {
            $no++;
            $body .= "<tr>";
            $body .= "<td>$no</td>";
            $body .= "<td title='$cb_id'>$cb_nama</td>";
            foreach ($header_000 as $ky_000 => $item_000) {
                $cb_allowed = isset($item_000['cabang']) ? $item_000['cabang'] : $cb_id;
                foreach ($header_00 as $ky_00 => $item_00) {
                    foreach ($header_0 as $ky_0 => $item_0) {
                        $nope = $cb_id == $cb_allowed ? "-" : $noneed;
                        $c_nilai = isset($dt_opname[$cb_id][$ky_000][$ky_00][$ky_0]) ? $dt_opname[$cb_id][$ky_000][$ky_00][$ky_0] : $nope;
                        $str_ky = "$cb_id $ky_000 $ky_00 $ky_0";
                        if ($c_nilai == "ok") {
                            $c_nilai_f = $ok;
                        }
                        else {
                            $c_nilai_f = $c_nilai;

                        }
                        $body .= "<td title='$str_ky' align='center'>$c_nilai_f</td>";

                        if(isset($dt_opname[$cb_id][$ky_000][$ky_00])){
                            if(!isset($dt_opname[$cb_id][$ky_000][$ky_00][$ky_0])){
                                $gudang = $item_00["label"];
                                $label_acc = $item_0["label"];
//                                cekHere("anu [$cb_nama][Opname $ky_000][$gudang][$label_acc]");
                                $labelWarning[$cb_nama][] = "<span class='text-uppercase'>Opname $ky_000 di $gudang ($label_acc)</span>";
                            }
                        }

                    }
                }
            }
            $body .= "</tr>";
        }
//arrPrintHitam($labelWarning);
        $stamp = dtimeNow();
        $footer = "";
        $footer .= "<tr>";
        $footer .= "<th></th>";
        $footer .= "<th>$stamp</th>";
        foreach ($header_000 as $ky => $item) {
            $label_000 = $item['label'];
            $link_op_data = $link_opname_data . "$ky";
            $btn_show = "<button type='button' disabled class='btn btn-info btn-block text-uppercase' onclick=\"location.href='$link_op_data'\" >ke halaman opname $label_000</button>";
            $footer .= "<th class='text-center text-uppercase' colspan='$colspan_000'>$btn_show</th>";
        }

        $footer .= "</tr>";

        $btn_confirm = "<button type='button' class='btn btn-success btn-block' onclick=\"confirm_alert_result('Peringatan','Dengan mengeklik button OK, Stok Opname akan dinyatakan selesai','$link_opname_confirm');\">stok opname dinyatakan selesai</button>";
        // $footer .= "<tr>";
        // if(isset($link_opname_data)){
        //     $colspan_0000 = ($colspan_000 * 3) +2;
        //     $footer .= "<th class='text-center text-uppercase' colspan='$colspan_0000'>$btn_confirm </th>";
        // }
        // $footer .= "</tr>";

        $content = "";
        $content .= "<style type='text/css'>
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                vertical-align: middle !important;
            }
            .bg-header{
                background-color: coral !important;
            }
            // .table-striped>tbody>tr:nth-of-type(odd) {
            //     background-color: unset !important;
            // }
        </style>";
        $content .= "<table class='table table-bordered table-hover-color-red table-striped'>";
        $content .= $header;
        $content .= $body;
        $content .= $footer;
        $content .= "</table>";

        if (isset($link_opname_confirm)) {
            $content .= "$btn_confirm";
            $content .= "<div>&nbsp;</div>";
        }


        $script_bottom = "";
        if(sizeof($labelWarning)>0){
//            if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72") {
                $msg = "<div class='text-left'>";
                $msg .= "Daftar Opname yang belum selesai:<br>";
                foreach ($labelWarning as $gg => $ggSpec){
                    $msg .= "<span class='text-bold'>$gg</span><br>";
                    $no = 0;
                    foreach ($ggSpec as $ggLabel){
                        $no++;
                        $msg .= "$no. <span class='text-left'>$ggLabel</span><br>";
                    }
                }
                $msg .= "</div>";
                opnameWhiteboard("Peringatan...", $msg);
//            }
        }


        $p->addTags(
            array(
                "title" => $title,
                "sub_title" => "Opname ",
                //                "menu_sub"     => callSubMenu(),
                "content" => $content,
                "profile_name" => $this->session->login['nama'],
                "script_bottom" => $script_bottom,
                "scriptLoad" => $script_bottom,
            )
        );
        // if (isset($lebar_modal)) {
        //     $p->setLebarModal($lebar_modal);
        // }

        $p->render();
        break;
    case "viewOpnameData":
        // $p = New Layout("", "", "application/template/opname.html");
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/viewdetails.html");
        // cekMerah(MODUL_TEMPLATE_PATH . "template/viewdetails.html");

        $header = "";
        $header .= "<tr class='text-uppercase bg-header'>";
        $header .= "<th rowspan='3' class='text-center'>no</th>";
        $header .= "<th rowspan='3' class='text-center' valign='middle'>cabang</th>";
        $jml_header_0 = sizeof($header_0);
        $jml_header_00 = sizeof($header_00);
        $jml_header_000 = sizeof($header_000);
        $colspan_000 = $jml_header_0 * $jml_header_00;
        foreach ($header_000 as $ky => $item) {
            $label_000 = $item['label'];
            $header .= "<th class='text-center text-uppercase' colspan='$colspan_000'>$label_000</th>";
        }
        $header .= "</tr>";


        $header .= "<tr class='text-uppercase  bg-header'>";
        for ($i = 1; $i <= $jml_header_000; $i++) {

            foreach ($header_00 as $ky => $item) {
                $label_00 = $item['label'];
                $header .= "<th class='text-center' colspan='$jml_header_0'>$label_00</th>";
            }
        }
        $header .= "</tr>";

        $header .= "<tr class='bg-header text-capitalize'>";
        for ($i = 1; $i <= ($jml_header_00 * $jml_header_000); $i++) {

            foreach ($header_0 as $ky => $item) {
                $label_0 = $item['label'];
                // $anakan_0 = $item['anakan'];
                // $jml_header = sizeof($anakan_0);
                $header .= "<th class='text-center'>$label_0</th>";
            }
        }
        $header .= "</tr>";


        $ok = "<i class='fa fa-check text-green'></i>";
        $noneed = "<i class='fa fa-times text-grey-1'></i>";
        $body = "";
        $no = 0;
        foreach ($data_produk as $cb_id => $cb_nama) {
            $no++;
            $body .= "<tr>";
            $body .= "<td>$no</td>";
            $body .= "<td>$cb_nama</td>";
            foreach ($header_000 as $ky_000 => $item_000) {
                $cb_allowed = isset($item_000['cabang']) ? $item_000['cabang'] : $cb_id;
                foreach ($header_00 as $ky_00 => $item_00) {
                    foreach ($header_0 as $ky_0 => $item_0) {
                        $nope = $cb_id == $cb_allowed ? "-" : $noneed;
                        $c_nilai = isset($dt_opname[$cb_id][$ky_000][$ky_00][$ky_0]) ? $dt_opname[$cb_id][$ky_000][$ky_00][$ky_0] : $nope;
                        $str_ky = "$cb_id $ky_000 $ky_00 $ky_0";
                        if ($c_nilai == "ok") {
                            $c_nilai_f = $ok;
                        }
                        else {
                            $c_nilai_f = $c_nilai;
                        }

                        $body .= "<td title='$str_ky' align='center'>$c_nilai_f</td>";
                    }
                }
            }
            $body .= "</tr>";
        }

        $footer = "";
        $footer .= "<tr>";
        $footer .= "<th></th>";
        $footer .= "<th></th>";
        foreach ($header_000 as $ky => $item) {
            $label_000 = $item['label'];
            $link_op_data = $link_opname_data . "$ky";
            $btn_show = "<button type='button' class='btn btn-info btn-block text-uppercase' onclick=\"location.href='$link_op_data'\" >ke halaman opname $label_000</button>";
            $footer .= "<th class='text-center text-uppercase' colspan='$colspan_000'>$btn_show</th>";
        }
        $footer .= "</tr";

        $content = "";
        $content .= "<style type='text/css'>
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                vertical-align: middle !important;
            }
            .bg-header{
                background-color: coral !important;
            }
            // .table-striped>tbody>tr:nth-of-type(odd) {
            //     background-color: unset !important;
            // }
        </style>";
        $content .= "<table class='table table-bordered table-hover-color-red table-striped'>";
        $content .= $header;
        $content .= $body;
        $content .= $footer;
        $content .= "</table>";

        $script_bottom = "";
        $p->addTags(
            array(
                "title" => $title,
                "sub_title" => "Opname ",
                //                "menu_sub"     => callSubMenu(),
                "content" => $content,
                "profile_name" => $this->session->login['nama'],
                "script_bottom" => $script_bottom,
                "scriptLoad" => $script_bottom,
            )
        );
        // if (isset($lebar_modal)) {
        //     $p->setLebarModal($lebar_modal);
        // }

        $p->render();
        break;

}