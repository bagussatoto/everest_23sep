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
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/scaner.html");
        $cabang_id = my_cabang_id();
        $list_data = "";
        $isMobile = false;
        $tipe_input = $isMobile == true ? "hidden" : "text";
        $pengirim = isset($pengirim) ? $pengirim : "";

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

//        if ($pengirim_id > 0) {
//        $label_scan = "Scan QRcode pada produk yang akan dipersiapkan";
//        $label_placeholder = "kode produk";
//        }
//        else {
//            $label_scan = "Scan QRcode pada Sales Order untuk memasukkan anda sebagai pengirim barang.";
//            $label_placeholder = "scan sales order...";
//        }

//        $list_data .= "<div class='col-xs-12 text-center' onclick=\"getScan();\"><i class='fa fa-qrcode thumnail-image' style='font-size: 60px;'></i> <label class='table cell'> $label_scan </label></div>";
//        $list_data .= "<input id='qr_scaner' type='$tipe_input'  class='form-control' value='' placeholder='$label_placeholder' onkeyup=\"getData('" . $do_scane . "?pengirim=$pengirim&str='+encodeURI(this.value), 'hasil')\">";
//        $list_data .= "<div id='hasil'></div>";


        $list_data .= "
            <div class='col-xs-12 text-center hidden-lg hidden-md hidden-sm' onclick=\"getScan();\">
                <i class='fa fa-qrcode thumnail-image' style='font-size: 60px;'></i>
                <div class='meta'> klik icon QR/Barcode untuk membuka scanner </div>
                <label class='table cell'> Scan QRcode pada produk yang akan dipersiapkan</label>
            </div>
        ";

        $list_data .= "<div class='text-bold text-center text-primary margin fa-2x hidden-xs'>Pastikan Cursor mengarah ke form Input Barcode/QRCode sebelum melakukan scan</div>";

        $list_data .= "
            <div class='text-center margin'>
                <label>
                    <input type='checkbox' id='multi_mode_toggle'> Gunakan Multi Serial
                </label>
            </div>
        ";

        $list_data .= "
            <div class='row hidden-xs'>
                <div class='container-fluid'>
                    <div id='input-group-qr' class='input-group' style='display:block!important;'>
                        <input id='qr_scaner' type='$tipe_input' onclick='select()' class='form-control text-center text-bold' value='' placeholder='kode produk'>
                        <span id='qr_scaner_go_group' class='input-group-btn hidden'>
                            <button id='qr_scaner_go' type='button' class='btn btn-info btn-flat'><i class='fa fa-send'></i> GO!</button>
                        </span>
                    </div>
                </div>
            </div>
        ";

        $list_data .= "
            <div class='row text-center margin hidden'>
                <div onclick='openBulkMode()' class='btn btn-xs btn-warning'>Open Scanner Bulk Mode</div>
            </div>
        ";

        $list_data .= "<div id='hasil'></div>";


        if (sizeof($items) > 0) {
            // cekHijau();
            $list_data .= "<div id='shopingcart_mobile' class='margin-top-50 table-responsive'>";
            $list_data .= "</div>";


            $list_data .= "<script>
                $('#shopingcart_mobile').load('$shopingcart_mobile');

//                $('#qr_scaner_go').bind('click', function(e){
//                    getData('" . $do_scane . "?pengirim=$pengirim&str='+encodeURI($('#qr_scaner').val()), 'hasil')
//                    $('#qr_scaner').val('').focus();
//                    $('#qr_scaner_go_group').removeClass('hidden').addClass('hidden');
//                    $('#input-group-qr').css('display', 'block')
//                });
//                $('#qr_scaner').bind('keyup click', delay_v2(function(e){
//                    e.preventDefault();
//                    if(e.key=='Enter'){
//                        getData('" . $do_scane . "?pengirim=$pengirim&str='+encodeURI(this.value), 'hasil')
//                        $(this).val('').focus();
//                        $('#qr_scaner_go_group').removeClass('hidden').addClass('hidden');
//                        $('#input-group-qr').css('display', 'block')
//                    }
//                    if( $(this).val().length >= 4 ){
//                        $('#qr_scaner_go_group').removeClass('hidden');
//                        $('#input-group-qr').css('display', 'table')
//                    }
//                    else{
//                        $('#qr_scaner_go_group').removeClass('hidden').addClass('hidden');
//                        $('#input-group-qr').css('display', 'block')
//                    }
//                }, 250));


                let isMultiMode = false;
                const urlSingle = '$do_scane';
                const urlMulti  = '$do_scane_multi';

                $('#multi_mode_toggle').on('change', function () {
                    isMultiMode = this.checked;
                    $('#qr_scaner').attr('placeholder', isMultiMode ? 'masukkan banyak serial (pisah koma)' : 'kode produk');
                });

                function submitScaner(value) {

//                    top.swal({
//                        title: 'Memproses...',
//                        html: 'Mohon tunggu sebentar.',
//                        allowOutsideClick: false,
//                        allowEscapeKey: false,
//                        allowEnterKey: false,
//                        onOpen: () => {
//                            top.swal.showLoading();
//                        }
//                    });

                    const urlTarget = isMultiMode ? urlMulti : urlSingle;
                    getData(urlTarget + \"?pengirim=$pengirim&str=\" + encodeURI(value), 'hasil')
                    $('#qr_scaner').val('').focus();
                        $('#qr_scaner_go_group').addClass('hidden');
                        $('#input-group-qr').css('display', 'block');
                        console.log('getData done');

                }

                $('#qr_scaner_go').on('click', function () {
                    submitScaner($('#qr_scaner').val());
                });

                $('#qr_scaner').on('keyup click', delay_v2(function (e) {
                    e.preventDefault();
                    if (e.key === 'Enter') {
                        submitScaner(this.value);
                    }

                    if( $(this).val().length >= 4 ){
                        $('#qr_scaner_go_group').removeClass('hidden');
                        $('#input-group-qr').css('display', 'table');
                    }
                    else{
                        $('#qr_scaner_go_group').addClass('hidden');
                        $('#input-group-qr').css('display', 'block');
                    }
                }, 250));

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

    case "scanerPengirim":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/scaner.html");
        $cabang_id = my_cabang_id();
        $list_data = "";
        $isMobile = false;
        $tipe_input = $isMobile == true ? "hidden" : "text";
        $pengirim = isset($pengirim) ? $pengirim : "";

        if ($pengirim_id > 0) {
            $label_scan = "Scan QRcode pada produk yang akan dipersiapkan";
            $label_placeholder = "kode produk";
        }
        else {
            $label_scan = "Scan QRcode pada Sales Order/Pre Packinglist untuk memasukkan anda sebagai pengirim barang.";
            $label_placeholder = "scan sales order/pre packinglist...";
        }

        $list_data .= "<div class='col-xs-12 text-center' onclick=\"getScan();\"><i class='fa fa-qrcode thumnail-image' style='font-size: 60px;'></i> <label class='table cell'> $label_scan </label></div>";
        $list_data .= "<input id='qr_scaner' type='$tipe_input'  class='form-control' value='' placeholder='$label_placeholder' onkeyup=\"getData('" . $do_scane . "?pengirim=$pengirim&str='+encodeURI(this.value), 'hasil')\">";
        $list_data .= "<div id='hasil'></div>";

//        if (sizeof($items) > 0) {
//            // cekHijau();
//            $list_data .= "<div id='shopingcart_mobile' class='margin-top-50 table-responsive'>";
//            $list_data .= "</div>";
//
//
//            $list_data .= "<script>
//                $('#shopingcart_mobile').load('$shopingcart_mobile');
//
//            </script>";
//        }

        $list_data .= "<div>";
        $list_data .= "<button type='button'
            class='btn btn-default' 
            onclick=\"location.href='$go_back'\">Kembali";
        $list_data .= "</button>";
        $list_data .= "</div>";

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