<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

switch ($mode) {

    case "scaner":
        // cekHere();
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH ."template/scaner.html");
        $cabang_id = my_cabang_id();
        $list_data = "";
        // $ismobile = ismo
        $linkAddRak = base_url() . "Data/add/RakCabang";
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
        $list_data .= "<div class='col-xs-12 text-center' onclick=\"getScan();\"><i class='fa fa-qrcode thumnail-image' style='font-size: 60px;'></i> <label class='table cell'> Scan QRcode pada produk yang akan dipersiapkan</label></div>";
        $list_data .= "<input id='qr_scaner' type='hidden' readonly class='form-control' value='' placeholder='kode produk' onkeyup=\"getData('" . $do_scane . "?str='+encodeURI(this.value), 'hasil')\">";
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