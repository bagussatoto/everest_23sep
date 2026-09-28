<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    default:
    case "index":
//matiHEre(__LINE__);
        function box02()
        {
            $var = "<div class='col-lg-3 col-6'>";

            $var .= "<div class='small-box bg-info'>";
            $var .= "<div class='inner'>";
            $var .= "<h3>150</h3>";

            $var .= "<p>New Orders</p>";
            $var .= "</div>";
            $var .= "<div class='icon'>";
            $var .= "<i class='ion ion-bag'></i>";
            $var .= "</div>";

            $var .= "<a href='#' class='small-box-footer'>More info <i class='fa fa-arrow-circle-right'></i></a>";
            $var .= "</div>";
            $var .= "</div>";

            return $var;
        }

        function box03($nama, $label, $kode, $keterangan)
        {
            // col-lg-3 col-md-3 col-sm-4 col-xs-12
            $var = "<div class='col-lg-2 col-md-2 col-sm-6 col-6 profile border-cekj'>";

            $var .= "<div class='img-box'>";
            $var .= "<img src='" . base_url() . "public/images/produks/img_blank.png' class='img-responsive' width='100%'>";
            $var .= "<ul class='text-center'>";
            $var .= "<a href='#'><li><i class='fa fa-facebook'></i></li></a>";
            $var .= "<a href='#'><li><i class='fa fa-twitter'></i></li></a>";
            $var .= "<a href='#'><li><i class='fa fa-linkedin'></i></li></a>";
            $var .= "</ul>";
            $var .= "</div>";

            $var .= "<h1>$label $kode</h1>";
            $var .= "<h2>$nama</h2>";
            $var .= "<p>$keterangan</p>";
            $var .= "</div>";

            return $var;
        }

        function box04($nama, $label, $kode, $keterangan)
        {
            $var = "<div class='col-md-2 col-sm-6 col-6'>";
            $var .= "<div class='product-grid4'>";
            $var .= "<div class='product-image4'>";
            $var .= "<a href='#'>";
            $var .= "<img class='pic-1' src='" . base_url() . "public/images/produks/img_blank.png'>";
            $var .= "<img class='pic-2' src='http://bestjquery.com/tutorial/product-grid/demo5/images/img-1.jpg'>";
            $var .= "</a>";
            $var .= "<ul class='social'>";
            $var .= "<li><a href='#' data-tip='Quick View'><i class='fa fa-eye'></i></a></li>";
            $var .= "<li><a href='#' data-tip='Add to Wishlist'><i class='fa fa-shopping-bag'></i></a></li>";
            $var .= "<li><a href='#' data-tip='Add to Cart'><i class='fa fa-shopping-cart'></i></a></li>";
            $var .= "</ul>";
            $var .= "<span class='product-new-label'>New</span>";
            $var .= "<span class='product-discount-label'>10%</span>";
            $var .= "</div>";
            $var .= "<div class='product-content'>";
            $var .= "<h3 class='title'><a href='#'>$label $kode</a></h3>";
            $var .= "<div class='price'>";
            $var .= "$14.40";
            $var .= "<span>$16.00</span>";
            $var .= "</div>";
            $var .= "<a class='add-to-cart' href=''>ADD TO CART</a>";
            $var .= "</div>";
            $var .= "</div>";
            $var .= "</div>";

            return $var;
        }

        // arrPrint($content);
        // mati_disini();
        $p = New LayoutWebs("$title", "application/template/webs.html");
        $strContent = "";
        if (isset($content['produk'])) {
            $hargas = $content['harga'];
            // arrPrint($hargas);
            $strContent .= "<div class=\"row pt-md\">";
            foreach ($content['produk'] as $items) {
                $produk_id = $items->id;
                // $strContent .= box03($items->nama,$items->label,$items->kode,$items->keterangan);
                // $strContent .= box04($items->nama, $items->label, $items->kode, $items->keterangan);
                $datas = array(
                    "nama" => $items->nama,
                    "harga" => isset($hargas->$produk_id->jual) ? $hargas->$produk_id->jual * 1 : 0,
                    "diskon" => isset($hargas->$produk_id->disc) ? $hargas->$produk_id->disc : 0,
                    "kode" => isset($items->kode) ? $items->kode : "",
                    "label" => isset($items->label) ? $items->label : "",
                    "id" => $produk_id,
                );
                $strContent .= $p->produkGrid($datas);
            }
            $strContent .= "</div>";
        }
        // else{
        //     $strContent .= "****";
        // }


        // $arrTags["menu_right_isi"] = callMenuRightIsi();
        // cekHere("t $subTitle");
        $arrCrumb = array(
            $subTitle,
        );
        $arrTags["sub_title"] = $subTitle;
        $arrTags["breadcrumb"] = $p->breadcrumb($arrCrumb);
        $arrTags["menu_top"] = callMenuTopWebs();
        $arrTags["menu_left"] = callMenuleftWebs();
        $arrTags["stop_time"] = "";
        $arrTags["content"] = $strContent;
        $arrTags["pageStr"] = $pageStr;
        // $arrTags["profile_img"] = base_url() . "public/images/profiles/profile-default.png";
        // $arrTags["profile_name"] = "You are off line";
        $p->addTags($arrTags);
        // $p->addTags(array(
        //         "menu_left"        => callMenuLeft(),
        //         "trans_menu"       => callTransMenu(),
        //         "btn_back"         => callBackNav(),
        //         // "start_page"       => $startPage,
        //         // "form_target"      => $formTarget,
        //         // "content"          => $content,
        //         // "profile_name"     => $this->session->login['nama'],
        //         // "self"             => $self,
        //         // "default_key"      => $defaultKey,
        //         // "error_msg"        => $error,
        //         // "submit_btn_label" => $buttonLabel,
        //         "stop_time" => "",
        //         // "page_str"=>$pageStr,
        //
        //         //                "add_link" => $btn_save,
        //     ));

        $p->render();
        break;
    case "keranjang":
        $p = New LayoutWebs("$title", "application/template/webs.html");
        // arrPrint($content);
        $datas = $p->shopingCart($content);
        $p->setProduksSrc($produks);
        // arrPrint(produk_spec_webs());
        $strContent = "<div class='container'>";
        $attrs = array(
            "target" => "result",
        );
        $strContent = form_open(base_url() . 'Home/saveShopingcart', $attrs);

        //region cart head
        $strContent .= "<div class='card shopping-cart'>";

        $strContent .= "<div class='card-header bg-dark text-light'>";
        $strContent .= "<i class='fa fa-shopping-cart' aria-hidden='true'></i>";
        $strContent .= " Shipping cart";
        $strContent .= "<a href='" . base_url() . "Home' class='btn btn-outline-info btn-sm pull-right'>Continiu shopping</a>";
        $strContent .= "<div class='clearfix'></div>";

        $strContent .= "</div>";
        //endregion

        //region cart body
        $strContent .= "<div class='card-body'>";
        $strContent .= $datas['html'];

        $sum_price = isset($datas['sum_price']) ? $datas['sum_price'] : 0;
        // cekHijau("$sum_price");
        $sum_price_f = formatField("curency", $sum_price);
        $strContent .= "<div class='pull-left'>";
        $strContent .= "<button type='button' class='btn btn-danger btn-sm' onclick=\"confirm_alert_result('jdl','benran nih','" . base_url() . "Home/clearShopingcart');\">clear shoping cart</button>";
        $strContent .= "</div>";
        $strContent .= "<div class='pull-right'>";
        $strContent .= "<a href='' class='btn btn-outline-secondary btn-sm pull-right'>";
        $strContent .= "Update shopping cart";
        $strContent .= "</a>";
        $strContent .= "</div>";

        $strContent .= "</div>";
        //endregion

        //region footer
        $strContent .= "<div class='card-footer'>";

        //region cupone atau voucher
        $strContent .= "<div class='coupon col-md-5 col-sm-5 no-padding-left pull-left'>";
        $strContent .= "<div class='row'>";
        $strContent .= "<div class='col-6'>";
        $strContent .= "<input type='text' class='form-control' placeholder='cupone code'>";
        $strContent .= "</div>";
        $strContent .= "<div class='col-6'>";
        $strContent .= "<input type='submit' class='btn btn-default' value='Use cupone'>";
        $strContent .= "</div>";
        $strContent .= "</div>";
        $strContent .= "</div>";
        //endregion

        $strContent .= "<div class='col-12 col-lg-4 pull-right text-right' style='margin: 10px'>";
        $strContent .= "Total price: <b class='text-red'>$sum_price_f</b>";
        $strContent .= "</div>";

        $strContent .= "<div class='col-12 no-padding-left'>";
        // $strContent .= "<a href='' class='btn btn-success btn-block pull-right'>Checkout</a>";
        $strContent .= form_submit("Checkout", "Checkout", "class='btn btn-success btn-block pull-right'");
        $strContent .= "</div>";

        $strContent .= "</div>";
        //endregion
        $strContent .= form_close();
        $strContent .= "</div>";

        $arrCrumb = array(
            $subTitle,
        );

        $p->addTags(array(
            "menu_top" => callMenuTopWebs(),
            "menu_left" => callMenuleftWebs(),
            "breadcrumb" => $p->breadcrumb($arrCrumb),
            // "trans_menu"       => callTransMenu(),
            // "btn_back"         => callBackNav(),
            // "start_page"       => $startPage,
            // "form_target"      => $formTarget,
            "sub_title" => "Shoping cart",
            "content" => $strContent,
            // "profile_name"     => $this->session->login['nama'],
            // "self"             => $self,
            // "default_key"      => $defaultKey,
            // "error_msg"        => $error,
            // "submit_btn_label" => $buttonLabel,
            // "stop_time" => "",
            "pageStr" => "",

            //                "add_link" => $btn_save,
        ));
        $p->render();
        break;
    case "register":
        // $p->addTags($arrTags);
        $p = New LayoutWebs("$title", "application/template/register.html");
        $p->addTags(array(
            // "menu_left"        => callMenuLeft(),
            // "trans_menu"       => callTransMenu(),
            // "btn_back"         => callBackNav(),
            // "start_page"       => $startPage,
            // "form_target"      => $formTarget,
            // "content"          => $content,
            // "profile_name"     => $this->session->login['nama'],
            // "self"             => $self,
            // "default_key"      => $defaultKey,
            // "error_msg"        => $error,
            // "submit_btn_label" => $buttonLabel,
            // "stop_time" => "",
            // "page_str"=>$pageStr,

            //                "add_link" => $btn_save,
        ));
        $p->render();
        break;
    case "viewCoa":
        // <!--// test-->
        $var = "";
        $var .= "<div class='box box-soliddd box-danger collapsed-box'>";
        $var .= "<div class='box-header with-border'>";
        $var .= "<h4 class='box-title'>Cart Of Account</h4>";
        $var .= "<div class='box-tools pull-right'>";
        $var .= "<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-plus'></i>
                        </button>";
        //                 <div class='btn-group'>
        //                   <button type='button' class='btn btn-box-tool dropdown-toggle' data-toggle='dropdown'>
        //                     <i class='fa fa-wrench'></i></button>
        //                   <ul class='dropdown-menu' role='menu'>
        //                     <li><a href='#'>Action</a></li>
        //                     <li><a href='#'>Another action</a></li>
        //                     <li><a href='#'>Something else here</a></li>
        //                     <li class='divider'></li>
        //                     <li><a href='#'>Separated link</a></li>
        //                   </ul>
        //                 </div>
        //                 <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
        $var .= "</div>";
        $var .= "</div>";
        $var .= "<div class='box-body overflow-h' style='padding: 0 10px 20px;display: none;'>";
        $var .= "<div class='coa'>";
        if (sizeof($hirarkies) > 0) {
            $var .= "<table class='table table-condesad'>";
            foreach ($hirarkies as $key_0 => $hirarky_0) {
                // $label_0 = isset($hirarky_0['label']) ? $hirarky_0['label'] : $key_0;
                // $anaks_0 = isset($hirarky_0['anak']) ? $hirarky_0['anak'] : "";
                foreach ($fields as $kol => $params) {
                    $$kol = $hirarky_0->$kol;
                }
                $head_code = "<b class='text-red'>$head_code</b>";

                $var .= "<tr>";
                if ($head_level == '0') {
                    $var .= "<td colspan='4' class='bg-primary'>$head_code $head_name</td>";                                       // ----------
                }
                else {
                    $var .= "<td>-</td>";

                    if ($head_level == '1') {
                        $var .= "<td colspan='3'>$head_code $head_name</td>";                                       // ----------
                    }
                    else {
                        $var .= "<td>-</td>";

                        if ($head_level == '2') {
                            $var .= "<td colspan='2'>$head_code $head_name</td>";                                       // ----------
                        }
                        else {
                            $var .= "<td>-</td>";

                            if ($head_level == '3') {
                                $var .= "<td colspan='1'>$head_code $head_name</td>";                                       // ----------
                            }
                            else {
                                $var .= "<td>-</td>";

                                if ($head_level == '4') {
                                    $var .= "<td>$head_code $head_name</td>";                                       // ----------
                                }
                                else {
                                    $var .= "<td>-</td>";
                                }
                            }
                        }
                    }
                }

                $var .= "</tr>";
                // }
            }
            $var .= "</table>";                            // 1
        }
        else {
            $var .= "kosong";
        }
        // ----------

        // $var .= "<ul>
        //             <li>
        //                 <a href='#'>distribution center <br/>Tetsuo Nakai</a>
        //                 <ul>
        //                     <li>
        //                         <a href='#'>
        //                             KCB. Jakarta
        //                         </a>
        //                         <ul>
        //                             <li>
        //                                 <a href='#'>IT Administrator <br/>Ericson Ginting<br/>Assistant
        //                                     Manager</a>
        //
        //                                 <ul>
        //                                     <li>
        //                                         <a href='#'>IT Engineer <br/>I Wayan Purushottama<br/>Engineer</a>
        //
        //                                         <ul>
        //                                             <li>
        //                                                 <a href='#'>IT Support<br/>Juanda F Butar
        //                                                     Butar<br/>Assistant Engineer</a>
        //                                             </li>
        //                                         </ul>
        //                                     </li>
        //
        //                                     <li>
        //                                         <a href='#' class='just-line'><br/><br/><br/></a>
        //                                         <ul>
        //                                             <li>
        //                                                 <a href='#'>IT Support<br/>David Alwis<br/>Assistant
        //                                                     Engineer</a>
        //
        //                                                 <ul>
        //                                                     <li>
        //                                                         <a href='#'>IT Support<br/>Nico
        //                                                             Simanjuntak<br/>Technician</a>
        //                                                     </li>
        //                                                 </ul>
        //                                             </li>
        //                                         </ul>
        //                                     </li>
        //                                 </ul>
        //                             </li>
        //                         </ul>
        //                     </li>
        //                     <li>
        //                         <a href='#'>
        //                             KCB. Suroboyo
        //                         </a>
        //                     </li>
        //                     <li>
        //                         <a href='#'>
        //                             KCB. Solo
        //                         </a>
        //                         <ul>
        //                             <li><a href='#'>BOM</a></li>
        //                         </ul>
        //                     </li>
        //
        //                 </ul>
        //             </li>
        //         </ul>";
        $var .= "</div>";
        $var .= "</div>";
        $var .= "</div>";

        //                     <!--// test-->
        echo $var;
        break;
    case "viewNeracaTmp":

        /* ------------------------------------------
         *TrnsaksiPindahBuku = viewNeracaTmp
         * --------------------------------------------*/

        $var = "";
        $var .= "<div id='editor'></div>";
        $var .= "<div class='box box-soliddd box-danger collapsed-box'>";
        $var .= "<div class='box-header with-border'>";
        $var .= "<h4 class='box-title'>Neraca Sementara</h4>";
        $var .= "<div class='box-tools pull-right'>";
        $var .= "<button type='button' class='btn btn-sm btn-info cbtn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $var .= "<button type='button' class='btn btn-sm btn-default'><i class='fa fa-refresh'></i></button>";

        //                 <div class='btn-group'>
        //                   <button type='button' class='btn btn-box-tool dropdown-toggle' data-toggle='dropdown'>
        //                     <i class='fa fa-wrench'></i></button>
        //                   <ul class='dropdown-menu' role='menu'>
        //                     <li><a href='#'>Action</a></li>
        //                     <li><a href='#'>Another action</a></li>
        //                     <li><a href='#'>Something else here</a></li>
        //                     <li class='divider'></li>
        //                     <li><a href='#'>Separated link</a></li>
        //                   </ul>
        //                 </div>
        //                 <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>

        $var .= "</div>";
        $var .= "</div>";
        $var .= "<div class='box-body overflow-h' style='padding: 0 10px 20px;display: block;'>";
        $var .= "<div class='coa'>";
        if (sizeof($hirarkies) > 0) {

            // region tabel summary
            if (sizeof($rekAkumulasiGabungan) > 0) {
                $var .= "<table class='table dataTable compact ttable-condensed table-hover table-bordered'>";
                $var .= "<tr>";
                $var .= "<th class='nno-padding xxfont-size-2'>Kode COA</th>";
                $var .= "<th class='nno-padding xxfont-size-2'>Rekening</th>";
                // ----------
                $var .= "<th class='nno-padding text-tebal xxfont-size-1-2'>Debet</th>";
                $var .= "<th class='nno-padding text-tebal xxfont-size-1-2'>Kredit</th>";
                $var .= "</tr>";
                $var .= "<tbody>";
                $total_sum = array();
                foreach ($hirarkies as $key_0 => $hirarky_0) {
                    $head_code_sum = $hirarky_0->head_code;
                    $head_name_sum = $hirarky_0->head_name;
                    if (array_key_exists($head_code_sum, $rekAkumulasiGabungan)) {
                        $head_code_debet_sum = $rekAkumulasiGabungan[$head_code_sum]["debet"];
                        $head_code_kredit_sum = $rekAkumulasiGabungan[$head_code_sum]["kredit"];
                        if(!isset($total_sum["debet"])){
                            $total_sum["debet"] = 0;
                        }
                        $total_sum["debet"] += $head_code_debet_sum;
                        if(!isset($total_sum["kredit"])){
                            $total_sum["kredit"] = 0;
                        }
                        $total_sum["kredit"] += $head_code_kredit_sum;

                        $var .= "<tr>";
                        $var .= "<td class='nno-padding xfont-size-2'>$head_code_sum</td>";
                        $var .= "<td class='nno-padding xfont-size-2'>$head_name_sum</td>";
                        // ----------
                        $var .= "<td class='nno-padding text-tebal text-right xfont-size-1-2'>".formatField_he_format("debet", $head_code_debet_sum)."</td>";
                        $var .= "<td class='nno-padding text-tebal text-right xfont-size-1-2'>".formatField_he_format("kredit", $head_code_kredit_sum)."</td>";
                        $var .= "</tr>";
                    }
                }
                $var .= "<tr>";
                $var .= "<td class='nno-padding xfont-size-2 text-right  text-bold' colspan='2'>Total</td>";
                // ----------
                $var .= "<td class='nno-padding text-tebal text-right xfont-size-1-2 text-bold'>".formatField_he_format("debet", $total_sum["debet"])."</td>";
                $var .= "<td class='nno-padding text-tebal text-right xfont-size-1-2 text-bold'>".formatField_he_format("kredit", $total_sum["kredit"])."</td>";
                $var .= "</tr>";

                $var .= "</tbody>";
                $var .= "</table class='table dataTable compact table-condensed table-hover table-bordered'>";
                $var .= "<br><br>";
            }
            // endregion tabel summary

            // region tabel utama
            $var .= "<table class='table dataTable compact table-condensed table-hover table-bordered'>";
            $predict_head_level = array();
            foreach ($hirarkies as $key_0 => $hirarky_0) {
                extract((array)$hirarky_0);
                $predict_head_level[$head_level] = $head_level;
            }
//            echo json_encode($hirarkies);
            $totalDebet = 0;
            $totalKredit = 0;
            $h0 = 0;
            $var .= "<tbody>";
            foreach ($hirarkies as $key_0 => $hirarky_0) {
                $h0++;
                foreach ($fields as $kol => $params) {
                    $$kol = $hirarky_0->$kol;
                }
//                if($h0==22 || $h0==21){
//                    arrPrint($hirarky_0);
//                    cekHere('$head_code: ' . $hirarky_0->head_code);
//                    cekHere('$p_head_code: ' . $hirarky_0->p_head_code);
//                    cekHere('$is_rekening_pembantu: ' . $hirarky_0->is_rekening_pembantu);
//                    cekHere('$rekening: ' . $hirarky_0->rekening);
//                    cekHere('$rekening: ' . $hirarky_0->rekening);
//                    cekHere('debet: ' . $hirarky_0->debet);
//                    cekHere('kredit: ' . $hirarky_0->kredit);
//                    arrPrint($rekGede[$hirarky_0->head_code]);
//                    arrPrint($rekGede[$hirarky_0->p_head_code]);
//                }
                $max_head_level = max($predict_head_level);
                $head_code_ori = $head_code;
                $p_head_code_ori = $hirarky_0->p_head_code;

                $valueDebet = isset($rekGede[$head_code]['debet']) ? $rekGede[$head_code]['debet'] : 0;
                $valueKredit = isset($rekGede[$head_code]['kredit']) ? $rekGede[$head_code]['kredit'] : 0;

                $totalDebet += $valueDebet * 1;
                $totalKredit += $valueKredit * 1;

                $rekGedeValDebet = isset($rekGede[$head_code]['debet']) ? number_format($valueDebet) : 0;
                $rekGedeValKredit = isset($rekGede[$head_code]['kredit']) ? number_format($valueKredit) : 0;

                $link_editor_debet = base_url() . "TransaksiPindahBuku/prosesEdit/$toko_id/debet/$head_code_ori/";
                $link_editor_kredit = base_url() . "TransaksiPindahBuku/prosesEdit/$toko_id/kredit/$head_code_ori/";


                $global_link = base_url() . "TransaksiPindahBuku/prosesEdit/$toko_id";

                // $link_editor = base_url()."Searching/prosesEdit/MdlHargaProduk/supplier/$toko_id/10685/1877/' + this.defaultValue + '/' + this.value);";
//onblurs=\"$('#editor').load('$link_editor_debet' + this.defaultValue + '/' + this.value);\"

//                $rekGedevalEditorDebet = "<input type='text' class='form-controls text-right exe_input' n_type='debet' head_code_ori='$head_code_ori' value='$valueDebet'>";
                $rekGedevalEditorDebet = "<input type='text' class='no-border bg-yellow form-controls pull-right text-right exe_input' n_type='debet' head_code_ori='$head_code_ori' value='$rekGedeValDebet'>";
//                $rekGedevalEditorDebet = "";

//                $rekGedevalEditorKredit = "<input type='text' class='form-controls text-right exe_input' n_type='kredit' head_code_ori='$head_code_ori' value='$valueKredit'>";
                $rekGedevalEditorKredit = "<input type='text' class='no-border bg-yellow form-controls pull-right text-right exe_input' n_type='kredit' head_code_ori='$head_code_ori' value='$rekGedeValKredit'>";
//                $rekGedevalEditorKredit = "";

                $head_code = "<b class='text-red'>$head_code</b>";
                $strLevel = "$head_code_ori" . "_" . strtolower(str_replace(" ", "_", $head_name));
                $strLevel_0 = $head_level > 0 ? "class='clickshow $strLevel'" : "";
//                echo json_encode($hirarky_0) . "<br><br><br>";


                $visibility = "";
//                if($simple == 1){
//                    if(($valueDebet>0) || ($valueKredit>0)){
//                        $visibility = "";
//                        cekHere(":: $head_level ::");
//                    }
//                    else{
//                        $hh = array(4,5,6,7);
//                        if(in_array($head_level, $hh)){
//                            $visibility = "visibility:collapse;";
//                        }
//                    }
//                }

                $var .= "<tr phc='$p_head_code_ori' hc='$head_code_ori' $strLevel_0 style='$visibility'>";
                $colSpan = ($max_head_level - $head_level) * 1 > 0 ? (($max_head_level - $head_level) * 1) : 0;
                $jmlNambahRow = ($max_head_level - $head_level) * 1 + $colSpan;
                $f_colspan = ((($max_head_level - $head_level) * 1) + 1) > 1 ? ((($max_head_level - $head_level) * 1) + 1) : 0;

                if($simple == 1){
                    if ($head_level == '0') {
                        $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding xfont-size-2'>$head_code $head_name</td>";
                        // ----------
                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                        // ----------
//                    $var .= "<td width=1% class='no-padding text-right xfont-size-2'>$rekGedeValDebet</td>";
//                    $var .= "<td width=1% class='no-padding text-right xfont-size-2'>$rekGedeValKredit</td>";
                    }
                    else {
                        $var .= "<td class='xno-padding'>-</td>";
                        if ($head_level == '1') {
                            $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding text-tebal xfont-size-1-5'>$head_code $head_name</td>";
                            // ----------
                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                            // ----------
//                        $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-5'>$rekGedeValDebet</td>";
//                        $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-5'>$rekGedeValKredit</td>";
                        }
                        else {
                            $var .= "<td class='xno-padding'>-</td>";
                            if ($head_level == '2') {
                                $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding text-tebal xfont-size-1-2'>$head_code $head_name</td>";
                                // ----------
                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                // ----------
//                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                            }
                            else {
                                $var .= "<td class='xno-padding'>-</td>";
                                if ($head_level == '3') {
                                    $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";                                         // ----------
//                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                                }
                                else {
                                    $var .= "<td class='xno-padding'>-</td>";
                                    if ($head_level == '4') {
                                        $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
//                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>0</td>";
                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                        // ----------
//                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                                    }
                                    else {
                                        $var .= "<td class='xno-padding'>-</td>";
                                        if ($head_level == '5') {
                                            $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";

//                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                                        }
                                        else {
                                            $var .= "<td class='xno-padding'>-</td>";
                                            if ($head_level == '6') {
                                                $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                            }
                                            else {
                                                $var .= "<td class='xno-padding'>-</td>";
                                                if ($head_level == '7') {
                                                    $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                }
                                                else {
                                                    $var .= "<td class='xno-padding'>-</td>";
                                                    if ($head_level == '8') {
                                                        $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                    }
                                                    else {
                                                        $var .= "<td class='xno-padding'>-</td>";
                                                        if ($head_level == '9') {
                                                            $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                        }
                                                        else {
                                                            $var .= "<td class='xno-padding'>-</td>";
                                                            if ($head_level == '10') {
                                                                $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                            }
                                                            else {
                                                                $var .= "<td>-</td>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else{
                    if ($head_level == '0') {
                        $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding xfont-size-2'>$head_code $head_name</td>";
                        // ----------
                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                        // ----------
//                    $var .= "<td width=1% class='no-padding text-right xfont-size-2'>$rekGedeValDebet</td>";
//                    $var .= "<td width=1% class='no-padding text-right xfont-size-2'>$rekGedeValKredit</td>";
                    }
                    else {
                        $var .= "<td class='xno-padding'>-</td>";
                        if ($head_level == '1') {
                            $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding text-tebal xfont-size-1-5'>$head_code $head_name</td>";
                            // ----------
                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                            // ----------
//                        $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-5'>$rekGedeValDebet</td>";
//                        $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-5'>$rekGedeValKredit</td>";
                        }
                        else {
                            $var .= "<td class='xno-padding'>-</td>";
                            if ($head_level == '2') {
                                $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding text-tebal xfont-size-1-2'>$head_code $head_name</td>";
                                // ----------
                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                // ----------
//                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                            }
                            else {
                                $var .= "<td class='xno-padding'>-</td>";
                                if ($head_level == '3') {
                                    $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";                                         // ----------
//                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                                }
                                else {
                                    $var .= "<td class='xno-padding'>-</td>";
                                    if ($head_level == '4') {
                                        $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
//                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>0</td>";
                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                        // ----------
//                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                                    }
                                    else {
                                        $var .= "<td class='xno-padding'>-</td>";
                                        if ($head_level == '5') {
                                            $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                            $var .= "<td width=1% class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";

//                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValDebet</td>";
//                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedeValKredit</td>";
                                        }
                                        else {
                                            $var .= "<td class='xno-padding'>-</td>";
                                            if ($head_level == '6') {
                                                $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                            }
                                            else {
                                                $var .= "<td class='xno-padding'>-</td>";
                                                if ($head_level == '7') {
                                                    $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                    $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                }
                                                else {
                                                    $var .= "<td class='xno-padding'>-</td>";
                                                    if ($head_level == '8') {
                                                        $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                        $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                    }
                                                    else {
                                                        $var .= "<td class='xno-padding'>-</td>";
                                                        if ($head_level == '9') {
                                                            $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                            $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                        }
                                                        else {
                                                            $var .= "<td class='xno-padding'>-</td>";
                                                            if ($head_level == '10') {
                                                                $var .= "<td jml_nambah_row='$jmlNambahRow' colspan='$f_colspan' class='no-padding'>$head_code $head_name</td>";                                       // ----------
                                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorDebet</td>";
                                                                $var .= "<td class='no-padding text-tebal text-right xfont-size-1-2'>$rekGedevalEditorKredit</td>";
                                                            }
                                                            else {
                                                                $var .= "<td>-</td>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $var .= "</tr>";

            }

            $var .= "</tbody>";

            if ($totalDebet != $totalKredit) {
                $var .= "<tfoot>";
                $var .= "<tr>";
                $var .= "<th colspan='" . ($max_head_level + 3) . "' class='text-right text-red fa-2x text-bold'>NERACA TIDAK BALANCE</th>";
                $var .= "</tr>";
                $var .= "</tfoot>";
            }

            $var .= "<tfoot>";
            $var .= "<tr>";
            $var .= "<th colspan='" . ($max_head_level + 1) . "' class='text-right fa-2x text-bold'>TOTAL</th>";
            $var .= "<th class='fa-2x text-bold'>" . number_format($totalDebet) . "</th>";
            $var .= "<th class='fa-2x text-bold'>" . number_format($totalKredit) . "</th>";
            $var .= "</tr>";
            $var .= "</tfoot>";

            $var .= "</table>";                            // 1
            // endregion tabel utama



        }
        else {
            $var .= "kosong";
        }
        // ----------

        // arrPrint($cp_data);
// cekOrange($neraca_status);

        $done = "";
        $btn_disabled = "disabled";
//        $neraca_status=1;
        if ($neraca_status == 1) {
            $btn_disabled = "disabled";
            $neraca_dtime = $cp_data->neraca_dtime;
            $done .= "<div class='alert alert-warning'>";
            $done .= "Neraca Awal Pemindahan sudah disetujui pada $neraca_dtime";
            $done .= "</div>";
        }
        else {

//            if($totalDebet!=$totalKredit){
//                $var .= "<button type='button' class='btn btn-md btn-danger btn-block' onclick=\"swal('NERACA BELUM BALANCE, SILAHKAN TINJAU KEMBALI NERACA ANDA.')\">SIMPAN NERACA PEMINDAHBUKUAN</button>";
//            }
//            else{
            $link_aproval_neraca = base_url() . "Cli/executeFileneraca/" . my_toko_id();

            $var .= "<button $btn_disabled type='button' class='btn btn-primary btn-block' onclick=\"confirm_alert_result('Perhatian','Neraca pemindah bukuan akan dipatenkan','$link_aproval_neraca');\">SIMPAN NERACA PEMINDAHBUKUAN</button>";
//            }

        }

        $var .= $done;

        $var .= "</div>";
        $var .= "</div>";
        $var .= "</div>";

        $var .= "<script>

            top.$('.exe_input').on('keyup', delay_v2(function(){
                var head_code = $(this).attr('head_code_ori');
                var n_type = $(this).attr('n_type');
                var value = removeCommas( $(this).val() );
                var defVal = this.defaultValue;
                var url = '$global_link'
                top.$('#editor').load(url + '/' + n_type + '/' + head_code + '/' + removeCommas(defVal) + '/' + value, function(){
                    console.log('berhasil update..???');
                });
                $(this).val( addCommas(value) )
            },1200))

            top.$('.exe_input').on('click', delay_v2(function(){
                $(this).select()
            }, 200))

//            top.$('.clickshow').on('click', delay_v2(function(){
//                var phc = $(this).attr('phc');
//                var hc  = $(this).attr('hc');
//                var childCount = $(\"tr[phc='\"+hc+\"']\");
//                if( $(childCount).length*1 > 0 ){
//                    $(childCount).toggle('fast')
//                }
//            }, 200))

        </script>";

        //                     <!--// test-->
        echo $var;
        break;
}