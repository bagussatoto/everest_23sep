<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    case "index":
        // cekHere();
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "/template/barcode.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        // arrPrint($arrayHistory);

        $p->addTags(array(
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "data_active_title" => $strActiveDataTitle,
            "data_active_content" => $arrayHistory,
            "profile_name" => $this->session->login['nama'],
            "link_str" => $linkStr,
            "error_msg" => $error,
            "this_page" => $thisPage,
            "search_str" => isset($_GET['k']) ? $_GET['k'] : "",
            "stop_time" => "",
            "btn_generete" => $btn_gen,
            "navigasi_top" => $navigasi_top,
        ));
        $p->render();
        break;
    case "viewPrint":
        $p = New Layout("--", "--", MODUL_TEMPLATE_PATH . "/template/barcode_print.html");
        $p->addTags(array(
            "content" => $tmp,
            "str_onload" => "window.print();",
            // "str_onload"        => "",
        ));
        $p->render();
        break;

    case "dinamic_code":

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/barcode.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        $p->addTags(array(
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "data_active_title" => "",
            "data_active_content" => $arrayHistory,
            "profile_name" => $this->session->login['nama'],
            "link_str" => "",
            "error_msg" => $error,
            "this_page" => $thisPage,
            "search_str" => "",
            "stop_time" => "",
            "btn_generete" => $btn_gen,
            // "str_onload"        => "window.print();",
        ));

        $p->render();

        break;

    case "scaner":
        // cekHere();
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/scaner.html");
        $cabang_id = my_cabang_id();
        $type_input = $isMobile == true ? "hidden" : "text";
        $list_data = "";
        // $ismobile = ismo
        // $linkAddRak = base_url() . "Data/add/RakCabang";
        // $addRak = modalDialogBtn("Penambahan Rak", $linkAddRak);
        // $list_data .= "<div class='input-group'>";
        // $list_data .= "<div class='input-group-btn'>";
        // $list_data .= "<button class='btn btn-info' id=\"scan_atas\" onclick=\"getScan();\"><i class=\"fa fa-qrcode\"></i></a>";
        // // $list_data .= "<button class='btn btn-info'><i class='fa fa-barcode'></i></button>";
        // $list_data .= "</div>";
        // $list_data .= "<input id='qr_scaner' type='hidden' readonly class='form-control' value='' placeholder='kode produk' onkeyup=\"getData('" . $do_scane . "?str='+encodeURI(this.value), 'hasil')\">";
        // $list_data .= "<div class='input-group-btn'>";
        // // $list_data .= "<button class='btn btn-info' id=\"scan_atas\" onclick=\"getScan();\"><i class=\"fa fa-qrcode\"></i></a>";
        // // $list_data .= "<button class='btn btn-info' onclick=\"$addRak\" title='tambah rak'><i class='fa fa-plus'></i></button>";
        // $list_data .= "</div>";
        // $list_data .= "</div>";
        $list_data .= "<div class='col-xs-12 text-center' onclick=\"getScan();\"><i class='fa fa-qrcode thumnail-image' style='font-size: 60px;'></i> <label class='table cell'> Scan QRcode pada produk</label></div>";
        $list_data .= "<input id='qr_scaner' type='$type_input' class='form-control' value='' placeholder='kode produk' onkeyup=\"getData('" . $do_scane . "?str='+encodeURI(this.value), 'hasil')\">";
        $list_data .= "<div id='hasil'></div>";

        if (sizeof($items) > 0) {
            // cekHijau();
            $list_data .= "<div id='shopingcart_mobile' class='margin-top-50 table-responsive'>";
            $list_data .= "</div>";

            // $list_data .= $shoping;
            $list_data .= "<script>
                $('#shopingcart_mobile').load('$shopingcart_mobile');

            </script>";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "btn_top" => "",
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
            )
        );

        // $p->setContent($contens);
        $p->render();
        break;
}


