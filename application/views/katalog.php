<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    case "Katalog":
        // cekHitam();
        $defaultKey = isset($_GET['q']) ? $q : "";
        $qStr = isset($_GET['q']) ? "?q=" . $q : "";
        $this->load->helper('he_angka');
        $segment_array = $this->uri->segment_array();
        $layout = isset($segment_array[3]) ? $segment_array[3] : "a";
        // arrPrint($segment_array);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $selfSegment = $segment_array[1] . "/" . $segment_array[2];
        $this->uri->segment(2);
        $btnLinks = array(
            base_url() . $selfSegment . "/a/$qStr" => "fa-list",
            base_url() . $selfSegment . "/b/$qStr" => "fa-th",
        );
        //region navigasi btn
        $navBtn = "";
        foreach ($btnLinks as $btnLink => $btnIcon) {
            $navBtn .= "<button class='btn btn-default btn-group active' onclick=\"location . href = '$btnLink'\"><i class='fa $btnIcon'></i></button>";

        }
        //endregion


        // arrPrint($data);
        // $addkoloms = array("hpp", "jual", "jualnppn", "stok", "images");
        $addkoloms = array("dimensi_m", "images");

        // $produlFields
        $koloms = array_merge($produkFields, $addkoloms);
        foreach ($dataChilds as $dataChild_0s) {
            foreach ($dataChild_0s as $dataChild_0) {
            }
        }
        // arrPrint($dataChild_0);
        foreach ($dataChild_0 as $item => $none) {
            $headers_2[$item] = array(
                "attr" => "class='text-center bg-primary'",
            );
        }
        $jmlChilds = sizeof($dataChild_0);

        $headers = array(
            "no" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "-" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            // "pID" => array(
            //     "attr" => "rowspan='2' class='text-center bg-primary'",
            // ),
            "jenis" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "kode" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "kategoris" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "kategori" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "label" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "nama" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "nomer part" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "dimensi (P-L-T)(M)" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "KG" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "M3" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "Satuan" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
        );
        foreach ($cabang as $cabangId => $cabangNama) {
            $headers[$cabangNama] = array("attr" => "colspan='$jmlChilds' class='text-center bg-primary'");
        }
        // arrPrint($dataChilds[CB_ID_PUSAT]);


        // $headers_2 = array(
        //   "stok" => array(
        //       "attr" => "class='text-center'",
        //   ),
        //   // "hpp" => array(
        //   //     "attr" => "class='text-center'",
        //   // ),
        //   "jual" => array(
        //       "attr" => "class='text-center'",
        //   ),
        //   "jualnppn" => array(
        //       "attr" => "class='text-center'",
        //   ),
        // );

        // arrPrint($koloms);
        // arrPrint($headers);
        $content_a = "";
        switch ($layout) {
            default:
                $list = "";
                $list .= "<div class='clearfix'> &nbsp; </div>";
                $list .= "<div class='box box-info'>";
                $list .= "<div class='box-header'></div>";
                $list .= "<div class='box-body table-responsive'>";
                $list .= "<table id='tabel_katalog' class='table datatable table-bordered table-hover table-condensed'>";
                // header 1
                $list .= "<thead>";
                $list .= "<tr>";
                foreach ($headers as $header => $attrs) {
                    if (is_array($attrs)) {
                        $list .= "<th " . $attrs['attr'] . ">$header</th>";
                    } else {
                        $list .= "<th>$attrs</th>";
                    }
                }
                $list .= "<th class='text-center bg-primary'>Total</th>";
                $list .= "</tr>";
                // header 2
                $list .= "<tr>";
                foreach ($cabang as $cabangId => $cabangNama) {
                    foreach ($headers_2 as $header => $attrs) {
                        if (is_array($attrs)) {
                            // arrPrint($attrs['attr']);
                            $list .= "<th " . $attrs['attr'] . ">$header</th>";
                        } else {
                            $list .= "<th>$attrs</th>";
                        }
                    }
                }
                $list .= "<th " . $attrs['attr'] . ">Stok</th>";
                $list .= "</tr>";
                $list .= "</thead>";

                $script = "";
                $script .= "<script>";

// arrPrint($dataChilds);
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }

                        $lg = formatField("number", conv_mm_m($lebar_gross));
                        $pg = formatField("number", conv_mm_m($panjang_gross));
                        $tg = formatField("number", conv_mm_m($tinggi_gross));

                        $dimensi = "$pg x $lg x $tg";
                        // if($stok == 0){
                        //
                        //     $custWarna = " class='text-red'";
                        // }
                        // else{
                        //     $custWarna = "";
                        // }

//cekHere($jenis);
                        switch ($jenis) {
                            case "item":
                                $ctrlName = "Produk";
                                break;
                            case "item_rakitan":
                                $ctrlName = "ProdukRakitan";
                                break;
                            case "item_komposit":
                                $ctrlName = "ProdukKomposit";
                                break;
                        }
                        $linkHist = base_url() . "Data/viewHistories/$ctrlName/$id";
                        $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName change histories $kode $nama ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                        $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' 
                                onclick='linkHistory_$id()'>
                                <span class='glyphicon glyphicon-time'></span>
                                </a>";

                        $script .= "function linkHistory_$id(){ $historyClick }\n";

                        $list .= "<tr style='white-space: nowrap;'>";
                        // $list .= "<td>" . formatField('angka', $no) . "</td>";
                        $list .= "<td>" . formatField("jual", $no) . "</td>";
                        $list .= "<td>$link_history_data</td>";
                        // $list .= "<td class='text-center'>$id</td>";
                        $list .= "<td>$jenis</td>";
                        $list .= "<td>$kode</td>";
                        $list .= "<td>$kategori_nama</td>";
                        $list .= "<td>$folders_nama</td>";
                        $list .= "<td>$label</td>";
                        $list .= "<td>$nama</td>";
                        $list .= "<td>$no_part</td>";

                        $list .= "<td class='text-center'>$dimensi</td>";
                        $list .= "<td>" . formatField("angka", conv_g_kg($berat_gross)) . "</td>";
                        $list .= "<td>" . formatField("angka", conv_mmc_mc($volume_gross)) . "</td>";
                        $list .= "<td class='text-center'>$satuan</td>";
                        $tStok = 0;

                        foreach ($cabang as $cabangId => $cabangNama) {
                            $stokX = isset($sumStokCabang[$cabangId][$id]) ? $sumStokCabang[$cabangId][$id] : 0;
                            foreach ($headers_2 as $header => $attrs) {
                                $vData = $dataChilds[$cabangId][$id][$header];
                                // $vData_f = $vData < 0 ? "<span class='text-red'>" . $vData * -1 . "</span>" : $vData;
                                $colorCb = $cabangId == $cabangID ? " class='bg-success text-right'" : " class='text-right'";
//                                $list .= "<td" . $colorCb . ">" . formatField($header, $vData) . "</td>";
                                $list .= "<td" . $colorCb . ">" . $vData . "</td>";

                                if ($header == "stok avail") {
//                                    cekBiru(":: $datum[id] $header $vData");
                                    $tStok += $vData;
//                                    $sData = $dataChilds[$cabangId][$datum['id']][$header];
//                                    if(!isset($tStok[$datum['id']])){
//                                        $tStok[$datum['id']] = 0;
//                                    }
//                                    $tStok[$datum['id']] += $sData;
                                }
//                            cekMerah($tStok);
                            }
                        }
//                        arrPrint($tStok);
//                         $list .= "<td class='bg-info'>" . formatField("jual", $tStok) . "</td>";
                        $list .= "<td class='bg-info'>" . formatField("jual", $stokX) . "</td>";
//                        $list .= "<td class='bg-info'>" . $tStok . "</td>";
                        $list .= "</tr>";
                    }
                } else {
                    $list .= "<tr>";
                    $list .= "<td colspan='6' class='text-center'>Tidak ditemukan relevansi data pada pencarian dengan keyword(s) <div class='text-red font-size-2'>$q</div> </td>";
                    $list .= "</tr>";
                }

                $list .= "</table>";
                $list .= "</div>";
                $list .= "</div>";

                $content_a .= "<div class='table-responsive padding-top-0'>";
                $content_a .= $list;
                $content_a .= "</div>";
                break;
            case "b":
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    $box = "";
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }
                        // arrPrint($images);

                        $caption = "Dimensi P.L.T(M): $dimensi_m,";
                        $caption .= " Weight (KG): " . number_format(conv_g_kg($berat_gross)) . ",";
                        $caption .= " Capacity (M3): " . number_format(conv_g_kg($volume_gross));
                        // $caption .= " Weight: " . formatField('angka', conv_mmc_mc($berat_gross));
                        // $caption .= " Capacity: " . formatField('angka', conv_mmc_mc($volume_gross));
                        $image = isset($images[0]['files']) ? $images[0]['files'] : img_blank();
                        $images_e = blobEncode($images);

                        $datum_e = str_replace("=", "", blobEncode($datum));
                        $pic = "<div class='border-cekk' style='height: 200px;background-image: url($image);background-size: cover;background-repeat: no-repeat;background-position: center;'>";
                        $pic .= "</div>";
                        $kodeNama_e = str_replace("=", "", base64_encode($kode . " " . $nama));

                        // $modal_l = base_url()."Katalog/modal/$images_e/$kodeNama_e";
                        // $modal_l = base_url() . "Katalog/modal/$datum_e";


                        $fileImages = array();
                        foreach ($images as $image) {
                            $fileImages[] = $image['files'];
                        }
                        $modals = array(
                            "title" => $kode . " " . $nama,
                            "body" => $fileImages,
                            // "caption" => array($file),
                            "caption" => $caption,
                        );
                        $modal_e = urlencode(blobEncode($modals));
                        $modal_l = base_url() . "Katalog/modal/$modal_e";
                        $box .= "<div style='padding: 5px!important;' class='col-md-3 col-xs-12'>";
                        $box .= "<div class='panel' style='height: 425px;'>";
                        $box .= "<a href='$modal_l' data-toggle='modal' data-target='#myModal'>$pic</a>";

                        // $data = "<ul class='list-group list-group-unbordered'>";
                        $data = "<p class='border-bottom-grey no-margin'>Kode<span class='pull-right'>$kode</span></br>";
                        $data .= "<p class='border-bottom-grey no-margin'>Weight (Kg)<span class='pull-right'>" . formatField('jual', conv_g_kg($berat_gross)) . "</span></p>";
                        $data .= "<p class='border-bottom-grey no-margin'>Capacity (M3)<span class='pull-right'>" . formatField('angka', conv_mmc_mc($volume_gross)) . "</span></p>";
                        $data .= "<p class='no-padding'>Dimensi (M)<span class='pull-right'>$dimensi_m</span></p>";
                        $vStok = 0;
                        $vJual = $vJualnppn = 0;
                        $jmlCb = sizeof($cabang);

                        foreach ($cabang as $cabangId => $cabangNama) {
                            $vStok += $dataChilds[$cabangId][$id]['stok avail'];

                            if ($cabangId == $cabangID) {
                                $vJualnppn = $dataChilds[$cabangId][$id]['jualnppn'];
                                $vJual = $dataChilds[$cabangId][$id]['jual'];
                                $vHpp = $dataChilds[$cabangId][$id]['hpp'];
                            }
                        }
                        if ($cabangID == CB_ID_PUSAT) {
                            $data .= "<p class='border-bottom-grey no-margin'>Hpp<span class='pull-right'>" . formatField('curency', ($vHpp)) . "</p>";
                        }

                        $data .= "<p class='border-bottom-grey no-margin'>Harga<span class='pull-right'>" . formatField('curency', ($vJual)) . "</p>";
                        $data .= "<p class='border-bottom-grey no-margin'>Harga & VAT<span class='pull-right'>" . formatField('curency', ($vJualnppn)) . "</p>";
                        $data .= "<p class='border-bottom-grey no-margin text-grey-3'>Stok (all)<span class='pull-right'>$vStok</span></br>";

                        // $data .= "</ul>";

                        $box .= "<div class='col-md-12'>";
                        $box .= "<p class='tebal margin-top-10 no-padding text-center'>$nama</p>";
                        $box .= $data;
                        $box .= "</div>";

                        $box .= "</div>";
                        $box .= "</div>";
                    }

                    $content_a .= "<div class='row margin-top-20'>";
                    $content_a .= $box;
                    $content_a .= "</div>";
                }
                break;
        }


        $content = "";
        $content .= $content_a;

        $script .= "</script>";

        $pageStr = "";
        // $self = $startPage = base_url() . "Katalog/viewProduk";
        $self = $startPage = base_url() . $selfSegment;

        $p = New Layout("$title", "$subTitle", "application/template/katalog.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "navigasi_btn" => $navBtn,
            "start_page" => $startPage,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "content" => $content,
            // "form_target"      => $formTarget,
            // "error_msg"        => $error,
            // "submit_btn_label" => "TEST",
            "stop_time" => "",
            "script_bottom" => "$script",
            "isi_modal" => "",
            "page_str" => $pageStr,
            "add_btn" => isset($add_btn) ? $add_btn : "",
        ));

        $p->render();
        break;
    case "KatalogGudang":
        $class_hidden = "";
        if ($isMob == true) {
            $class_hidden = "hidden-xs hidden-sm";
        }
        // arrPrint($gudangData);
        $defaultKey = isset($_GET['q']) ? $q : "";
        $qStr = isset($_GET['q']) ? "?q=" . $q : "";
        $this->load->helper('he_angka');
        $segment_array = $this->uri->segment_array();
        $layout = isset($segment_array[3]) ? $segment_array[3] : "a";
        // arrPrint($segment_array);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $selfSegment = $segment_array[1] . "/" . $segment_array[2];
        $this->uri->segment(2);
        $btnLinks = array(
            base_url() . $selfSegment . "/a/$qStr" => "fa-list",
            base_url() . $selfSegment . "/b/$qStr" => "fa-th",
        );
        //region navigasi btn
        $navBtn = "";
        foreach ($btnLinks as $btnLink => $btnIcon) {
            $navBtn .= "<button class='btn btn-default btn-group active' onclick=\"location . href = '$btnLink'\"><i class='fa $btnIcon'></i></button>";

        }
        //endregion

        $addkoloms = array("dimensi_m", "images");

        $koloms = array_merge($produkFields, $addkoloms);
        foreach ($dataChilds["-1"] as $dataChild_0s) {
            foreach ($dataChild_0s as $dataChild_0) {
            }
        }

        foreach ($dataChild_0 as $item => $none) {
            $headers_2[$item] = array(
                "attr" => "class='text-center bg-primary $class_hidden'",
            );
        }
        $jmlChilds = sizeof($dataChild_0);
//arrPrint($headers_2);
        /*---header katalog diatur disini----*/
        $headers = array(
            "no" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "-" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),
            "pID" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),
            "kode" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "jenis" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),
            "dimensi (P-L-T)(M)" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "KG" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "M3" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "kategoris" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),
            "kategori" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),
            "label" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "nama" => array(
                "attr" => "rowspan='3' class='text-center bg-primary'",
            ),
            "nomer part" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),


            "Satuan" => array(
                "attr" => "rowspan='3' class='text-center bg-primary $class_hidden'",
            ),
        );

        $headerGudang = array();
        foreach ($cabang as $cabangId => $cabangNama) {
            $col = (sizeof($gudangData[$cabangId]) * $jmlChilds) + 1;
            // cekHitam($cabangNama)*$jmlChilds;
            $headers[$cabangNama] = array("attr" => "colspan='$col' class='text-center bg-primary $class_hidden'");
            if (isset($gudangData[$cabangId])) {
                foreach ($gudangData[$cabangId] as $i => $temp) {
                    // arrPrint($temp);
                    $headerGudang[$cabangId][$temp["nama"]] = array("attr" => "colspan='$jmlChilds' class='text-center bg-primary $class_hidden'");
                }

            }

        }
        // arrPrint($headers);
        // arrPrint($gudangData);
        // matiHere();
        // $headers_2 = array(
        //   "stok" => array(
        //       "attr" => "class='text-center'",
        //   ),
        //   // "hpp" => array(
        //   //     "attr" => "class='text-center'",
        //   // ),
        //   "jual" => array(
        //       "attr" => "class='text-center'",
        //   ),
        //   "jualnppn" => array(
        //       "attr" => "class='text-center'",
        //   ),
        // );

        $content_a = "";
        $array_content = array();
        switch ($layout) {
            default:

                //region header
                $list = "";
                $list .= "<div class='clearfix'> &nbsp; </div>";
                $list .= "<div class='box box-info'>";
                $list .= "<div class='box-body table-responsive'>";
                $list .= "<table layout='$layout' id='tabel_katalog' class='table datatable table-bordered table-hover table-condensed'>";

                // header 1
                $list .= "<thead>";

                $list .= "<tr>";
                foreach ($headers as $header => $attrs) {
                    if (is_array($attrs)) {
                        $list .= "<th " . $attrs['attr'] . ">$header</th>";
                    } else {
                        $list .= "<th>$attrs</th>";
                    }
                }
                $list .= "<th rowspan=3 class='text-center bg-primary'>Total</th>";
                $list .= "</tr>";

                // header 2
                $list .= "<tr>";
                foreach ($cabang as $cabangId => $cabangNama) {
                    foreach ($headerGudang[$cabangId] as $header => $attrs) {
                        if (is_array($attrs)) {
                            $list .= "<th " . $attrs['attr'] . ">$header</th>";
                        } else {
                            $list .= "<th>$attrs</th>";
                        }
                    }
                    $list .= "<th rowspan='2' class='text-center bg-primary $class_hidden'>subtotal stok</th>";
                }
                $list .= "</tr>";

                $list .= "<tr>";
                foreach ($cabang as $cabangId => $cabangNama) {
                    foreach ($gudangData[$cabangId] as $gID => $cabangdata) {
                        foreach ($headers_2 as $header => $attrs) {
                            if (is_array($attrs)) {
                                $list .= "<th " . $attrs['attr'] . ">$header</th>";
                            } else {
                                $list .= "<th>$attrs</th>";
                            }
                        }
                    }

                }
                $list .= "</tr>";

                $list .= "</thead>";

                $script = "";
                $script .= "<script>";
                //endregion

                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }

                        $lg = formatField("number", conv_mm_m($lebar_gross));
                        $pg = formatField("number", conv_mm_m($panjang_gross));
                        $tg = formatField("number", conv_mm_m($tinggi_gross));

                        $dimensi = "$pg x $lg x $tg";
                        // if($stok == 0){
                        //
                        //     $custWarna = " class='text-red'";
                        // }
                        // else{
                        //     $custWarna = "";
                        // }

                        //cekHere($jenis);
                        switch ($jenis) {
                            case "item":
                                $ctrlName = "Produk";
                                break;
                            case "item_rakitan":
                                $ctrlName = "ProdukRakitan";
                                break;
                            case "item_komposit":
                                $ctrlName = "ProdukKomposit";
                                break;
                        }
                        $linkHist = base_url() . "Data/viewHistories/$ctrlName/$id";
                        $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName change histories $kode $nama ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                        $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' 
                                onclick='linkHistory_$id()'>
                                <span class='glyphicon glyphicon-time'></span>
                                </a>";

                        $script .= "function linkHistory_$id(){ $historyClick }\n";

                        $list .= "<tr style='white-space: nowrap;'>";

                        // $list .= "<td>" . formatField('angka', $no) . "</td>";
                        $list .= "<td>" . formatField("jual", $no) . "</td>";
                        $list .= "<td class='$class_hidden'>$link_history_data</td>";
                        $list .= "<td class='text-center $class_hidden'>$id</td>";
                        $list .= "<td>$kode</td>";
                        $list .= "<td class='$class_hidden'>$jenis</td>";
                        $list .= "<td class='text-center'>$dimensi</td>";
                        $list .= "<td>" . formatField("angka", conv_g_kg($berat_gross)) . "</td>";
                        $list .= "<td>" . formatField("angka", conv_mmc_mc($volume_gross)) . "</td>";
                        $list .= "<td class='$class_hidden'>$kategori_nama</td>";
                        $list .= "<td class='$class_hidden'>$folders_nama</td>";
                        $list .= "<td class='$class_hiddenn'>$label</td>";
                        $list .= "<td>$nama</td>";
                        $list .= "<td class='$class_hidden'>$no_part</td>";
                        $list .= "<td class='text-center $class_hidden'>$satuan</td>";
                        // foreach ($headers as $headerKy => $hAttr) {
                        //     $list .= "<td>" . formatField("jual", $no) . "</td>";
                        // }

                        $tStok = 0;
                        foreach ($cabang as $cabangId => $cabangNama) {
                            $colorCb = $cabangId == $cabangID ? " class='bg-success text-right $class_hidden'" : " class='text-right $class_hidden'";
                            foreach ($gudangData[$cabangId] as $gID => $gidLabel) {
                                foreach ($headers_2 as $header => $attrs) {
                                    $vData = $dataChilds[$cabangId][$gID][$id][$header];
                                    // $vData_f = $vData < 0 ? "<span class='text-red'>" . $vData * -1 . "</span>" : $vData;

                                    //                                $list .= "<td" . $colorCb . ">" . formatField($header, $vData) . "</td>";
                                    $list .= "<td" . $colorCb . ">" . $vData . "</td>";

                                    if ($header == "stok avail") {
                                        $tStok += $vData;

                                    }

                                }
                            }
                            $list .= "<td" . $colorCb . ">" . formatField("total", $sumStokCabang[$cabangId][$id]) . "</td>";

                        }

                        $list .= "<td class='bg-info total'>" . formatField("jual", $tStok) . "</td>";
                        //                        $list .= "<td class='bg-info'>" . $tStok . "</td>";
                        $list .= "</tr>";
                    }
                } else {
                    $list .= "<tr>";
                    $list .= "<td colspan='6' class='text-center'>Tidak ditemukan relevansi data pada pencarian dengan keyword(s) <div class='text-red font-size-2'>$q</div> </td>";
                    $list .= "</tr>";
                }

                $list .= "</table>";
                $list .= "</div>";
                $list .= "</div>";

                $content_a .= "<div class='table-responsive padding-top-0'>";
                $content_a .= $list;
                $content_a .= "</div>";

                break;
            case "ori defaul":
                $list = "";
                $list .= "<div class='clearfix'> &nbsp; </div>";
                $list .= "<div class='box box-info'>";
                $list .= "<div class='box-header'></div>";
                $list .= "<div class='box-body table-responsive'>";
                $list .= "<table layout='$layout' id='tabel_katalog' class='table datatable table-bordered table-hover table-condensed'>";
                // header 1
                $list .= "<thead>";
                $list .= "<tr>";
                foreach ($headers as $header => $attrs) {
                    if (is_array($attrs)) {
                        $list .= "<th " . $attrs['attr'] . ">$header</th>";
                    } else {
                        $list .= "<th>$attrs</th>";
                    }
                }
                $list .= "<th class='text-center bg-primary'>Total</th>";
                $list .= "</tr>";
                // header 2
                $list .= "<tr>";
                foreach ($cabang as $cabangId => $cabangNama) {
                    foreach ($headers_2 as $header => $attrs) {
                        if (is_array($attrs)) {
                            // arrPrint($attrs['attr']);
                            $list .= "<th " . $attrs['attr'] . ">$header</th>";
                        } else {
                            $list .= "<th>$attrs</th>";
                        }
                    }
                }
                $list .= "<th " . $attrs['attr'] . ">Stok</th>";
                $list .= "</tr>";
                $list .= "</thead>";

                $script = "";
                $script .= "<script>";

                // arrPrint($dataChilds);
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }

                        $lg = formatField("number", conv_mm_m($lebar_gross));
                        $pg = formatField("number", conv_mm_m($panjang_gross));
                        $tg = formatField("number", conv_mm_m($tinggi_gross));

                        $dimensi = "$pg x $lg x $tg";
                        // if($stok == 0){
                        //
                        //     $custWarna = " class='text-red'";
                        // }
                        // else{
                        //     $custWarna = "";
                        // }

                        //cekHere($jenis);
                        switch ($jenis) {
                            case "item":
                                $ctrlName = "Produk";
                                break;
                            case "item_rakitan":
                                $ctrlName = "ProdukRakitan";
                                break;
                            case "item_komposit":
                                $ctrlName = "ProdukKomposit";
                                break;
                        }
                        $linkHist = base_url() . "Data/viewHistories/$ctrlName/$id";
                        $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName change histories $kode $nama ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                        $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' 
                                onclick='linkHistory_$id()'>
                                <span class='glyphicon glyphicon-time'></span>
                                </a>";

                        $script .= "function linkHistory_$id(){ $historyClick }\n";

                        $list .= "<tr style='white-space: nowrap;'>";
                        // $list .= "<td>" . formatField('angka', $no) . "</td>";
                        $list .= "<td>" . formatField("jual", $no) . "</td>";
                        $list .= "<td>$link_history_data</td>";
                        $list .= "<td class='text-center'>$id</td>";
                        $list .= "<td>$jenis</td>";
                        $list .= "<td>$kode</td>";
                        $list .= "<td>$kategori_nama</td>";
                        $list .= "<td>$folders_nama</td>";
                        $list .= "<td>$label</td>";
                        $list .= "<td>$nama</td>";
                        $list .= "<td>$no_part</td>";

                        $list .= "<td class='text-center'>$dimensi</td>";
                        $list .= "<td>" . formatField("angka", conv_g_kg($berat_gross)) . "</td>";
                        $list .= "<td>" . formatField("angka", conv_mmc_mc($volume_gross)) . "</td>";
                        $list .= "<td class='text-center'>$satuan</td>";
                        $tStok = 0;
                        foreach ($cabang as $cabangId => $cabangNama) {
                            foreach ($headers_2 as $header => $attrs) {
                                $vData = $dataChilds[$cabangId][$id][$header];
                                // $vData_f = $vData < 0 ? "<span class='text-red'>" . $vData * -1 . "</span>" : $vData;
                                $colorCb = $cabangId == $cabangID ? " class='bg-success text-right'" : " class='text-right'";
                                //                                $list .= "<td" . $colorCb . ">" . formatField($header, $vData) . "</td>";
                                $list .= "<td" . $colorCb . ">" . $vData . "</td>";

                                if ($header == "stok avail") {
                                    //                                    cekBiru(":: $datum[id] $header $vData");
                                    $tStok += $vData;
                                    //                                    $sData = $dataChilds[$cabangId][$datum['id']][$header];
                                    //                                    if(!isset($tStok[$datum['id']])){
                                    //                                        $tStok[$datum['id']] = 0;
                                    //                                    }
                                    //                                    $tStok[$datum['id']] += $sData;
                                }
                                //                            cekMerah($tStok);
                            }
                        }
                        //                        arrPrint($tStok);
                        $list .= "<td class='bg-info'>" . formatField("jual", $tStok) . "</td>";
                        //                        $list .= "<td class='bg-info'>" . $tStok . "</td>";
                        $list .= "</tr>";
                    }
                } else {
                    $list .= "<tr>";
                    $list .= "<td colspan='6' class='text-center'>Tidak ditemukan relevansi data pada pencarian dengan keyword(s) <div class='text-red font-size-2'>$q</div> </td>";
                    $list .= "</tr>";
                }

                $list .= "</table>";
                $list .= "</div>";
                $list .= "</div>";

                $content_a .= "<div class='table-responsive padding-top-0'>";
                $content_a .= $list;
                $content_a .= "</div>";
                break;
            case "b":
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    $box = "";
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }
                        // arrPrint($images);

                        $caption = "Dimensi P.L.T(M): $dimensi_m,";
                        $caption .= " Weight (KG): " . number_format(conv_g_kg($berat_gross)) . ",";
                        $caption .= " Capacity (M3): " . number_format(conv_g_kg($volume_gross));
                        // $caption .= " Weight: " . formatField('angka', conv_mmc_mc($berat_gross));
                        // $caption .= " Capacity: " . formatField('angka', conv_mmc_mc($volume_gross));
                        $image = isset($images[0]['files']) ? $images[0]['files'] : img_blank();
                        $images_e = blobEncode($images);

                        $datum_e = str_replace("=", "", blobEncode($datum));
                        $pic = "<div class='border-cekk' style='height: 200px;background-image: url($image);background-size: cover;background-repeat: no-repeat;background-position: center;'>";
                        $pic .= "</div>";
                        $kodeNama_e = str_replace("=", "", base64_encode($kode . " " . $nama));

                        // $modal_l = base_url()."Katalog/modal/$images_e/$kodeNama_e";
                        // $modal_l = base_url() . "Katalog/modal/$datum_e";


                        $fileImages = array();
                        foreach ($images as $image) {
                            $fileImages[] = $image['files'];
                        }
                        $modals = array(
                            "title" => $kode . " " . $nama,
                            "body" => $fileImages,
                            // "caption" => array($file),
                            "caption" => $caption,
                        );
                        $modal_e = urlencode(blobEncode($modals));
                        $modal_l = base_url() . "Katalog/modal/$modal_e";
                        $box .= "<div style='padding: 5px!important;' class='col-md-3 col-xs-12'>";
                        $box .= "<div class='panel' style='height: 425px;'>";
                        $box .= "<a href='$modal_l' data-toggle='modal' data-target='#myModal'>$pic</a>";

                        // $data = "<ul class='list-group list-group-unbordered'>";
                        $data = "<p class='border-bottom-grey no-margin'>Kode<span class='pull-right'>$kode</span></br>";
                        $data .= "<p class='border-bottom-grey no-margin'>Weight (Kg)<span class='pull-right'>" . formatField('jual', conv_g_kg($berat_gross)) . "</span></p>";
                        $data .= "<p class='border-bottom-grey no-margin'>Capacity (M3)<span class='pull-right'>" . formatField('angka', conv_mmc_mc($volume_gross)) . "</span></p>";
                        $data .= "<p class='no-padding'>Dimensi (M)<span class='pull-right'>$dimensi_m</span></p>";
                        $vStok = 0;
                        $vJual = $vJualnppn = 0;
                        $jmlCb = sizeof($cabang);

                        foreach ($cabang as $cabangId => $cabangNama) {
                            $vStok += $dataChilds[$cabangId][$id]['stok avail'];

                            if ($cabangId == $cabangID) {
                                $vJualnppn = $dataChilds[$cabangId][$id]['jualnppn'];
                                $vJual = $dataChilds[$cabangId][$id]['jual'];
                                $vHpp = $dataChilds[$cabangId][$id]['hpp'];
                            }
                        }
                        if ($cabangID == CB_ID_PUSAT) {
                            $data .= "<p class='border-bottom-grey no-margin'>Hpp<span class='pull-right'>" . formatField('curency', ($vHpp)) . "</p>";
                        }

                        $data .= "<p class='border-bottom-grey no-margin'>Harga<span class='pull-right'>" . formatField('curency', ($vJual)) . "</p>";
                        $data .= "<p class='border-bottom-grey no-margin'>Harga & VAT<span class='pull-right'>" . formatField('curency', ($vJualnppn)) . "</p>";
                        $data .= "<p class='border-bottom-grey no-margin text-grey-3'>Stok (all)<span class='pull-right'>$vStok</span></br>";

                        // $data .= "</ul>";

                        $box .= "<div class='col-md-12'>";
                        $box .= "<p class='tebal margin-top-10 no-padding text-center'>$nama*</p>";
                        $box .= $data;
                        $box .= "</div>";

                        $box .= "</div>";
                        $box .= "</div>";
                    }
                    $content_a .= "<div class='row margin-top-20'>";
                    $content_a .= $box;
                    $content_a .= "</div>";
                }
                break;
        }
        $content = "";
        $content .= $content_a;
        $script .= "</script>";

        $pageStr = "";
        // $self = $startPage = base_url() . "Katalog/viewProduk";
        $self = $startPage = base_url() . $selfSegment;

        $p = New Layout("$title", "$subTitle", "application/template/katalog.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "navigasi_btn" => $navBtn,
            "start_page" => $startPage,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "content" => $content,
            // "form_target"      => $formTarget,
            // "error_msg"        => $error,
            // "submit_btn_label" => "TEST",
            "stop_time" => "",
            "script_bottom" => "$script",
            "isi_modal" => "",
            "page_str" => $pageStr,
            "add_btn" => isset($add_btn) ? $add_btn : "",
        ));

        $p->render();
        break;
    case "KatalogAktif":
        // cekHitam();
        $defaultKey = isset($_GET['q']) ? $q : "";
        $qStr = isset($_GET['q']) ? "?q=" . $q : "";
        $this->load->helper('he_angka');
        $segment_array = $this->uri->segment_array();
        $layout = isset($segment_array[3]) ? $segment_array[3] : "a";
        // arrPrint($segment_array);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $selfSegment = $segment_array[1] . "/" . $segment_array[2];
        $this->uri->segment(2);
        $btnLinks = array(
            base_url() . $selfSegment . "/a/$qStr" => "fa-list",
            base_url() . $selfSegment . "/b/$qStr" => "fa-th",
        );
        //region navigasi btn
        $navBtn = "";
        foreach ($btnLinks as $btnLink => $btnIcon) {
            $navBtn .= "<button class='btn btn-default btn-group active' onclick=\"location . href = '$btnLink'\"><i class='fa $btnIcon'></i></button>";

        }
        //endregion


        // arrPrint($data);
        // $addkoloms = array("hpp", "jual", "jualnppn", "stok", "images");
        $addkoloms = array("dimensi_m", "images");

        // $produlFields
        $koloms = array_merge($produkFields, $addkoloms);
        foreach ($dataChilds as $dataChild_0s) {
            foreach ($dataChild_0s as $dataChild_0) {
            }
        }
        // arrPrint($dataChild_0);
        foreach ($dataChild_0 as $item => $none) {
            $headers_2[$item] = array(
                "attr" => "class='text-center bg-primary'",
            );
        }
        $jmlChilds = sizeof($dataChild_0);

        $headers = array(
            "no" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "-" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "pID" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "jenis" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "kode" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "kategoris" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "kategori" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "label" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "nama" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "nomer part" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "dimensi (P-L-T)(M)" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "KG" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "M3" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "Satuan" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
        );
        foreach ($cabang as $cabangId => $cabangNama) {
            $headers[$cabangNama] = array("attr" => "colspan='$jmlChilds' class='text-center bg-primary'");
        }
        // arrPrint($dataChilds[CB_ID_PUSAT]);


        // $headers_2 = array(
        //   "stok" => array(
        //       "attr" => "class='text-center'",
        //   ),
        //   // "hpp" => array(
        //   //     "attr" => "class='text-center'",
        //   // ),
        //   "jual" => array(
        //       "attr" => "class='text-center'",
        //   ),
        //   "jualnppn" => array(
        //       "attr" => "class='text-center'",
        //   ),
        // );

        // arrPrint($koloms);
        // arrPrint($headers);
        $content_a = "";
        switch ($layout) {
            default:
                $list = "";
                $list .= "<div class='clearfix'> &nbsp; </div>";
                $list .= "<div class='box box-info'>";
                $list .= "<div class='box-header'></div>";
                $list .= "<div class='box-body table-responsive'>";
                $list .= "<table id='tabel_katalog' class='table datatable table-bordered table-hover table-condensed'>";
                // header 1
                $list .= "<thead>";
                $list .= "<tr>";
                foreach ($headers as $header => $attrs) {
                    if (is_array($attrs)) {
                        $list .= "<th " . $attrs['attr'] . ">$header</th>";
                    } else {
                        $list .= "<th>$attrs</th>";
                    }
                }
                $list .= "<th class='text-center bg-primary'>Total</th>";
                $list .= "</tr>";
                // header 2
                $list .= "<tr>";
                foreach ($cabang as $cabangId => $cabangNama) {
                    foreach ($headers_2 as $header => $attrs) {
                        if (is_array($attrs)) {
                            // arrPrint($attrs['attr']);
                            $list .= "<th " . $attrs['attr'] . ">$header</th>";
                        } else {
                            $list .= "<th>$attrs</th>";
                        }
                    }
                }
                $list .= "<th " . $attrs['attr'] . ">Stok</th>";
                $list .= "</tr>";
                $list .= "</thead>";

                $script = "";
                $script .= "<script>";

//arrPrint($dataChilds);
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }

                        $lg = formatField("number", conv_mm_m($lebar_gross));
                        $pg = formatField("number", conv_mm_m($panjang_gross));
                        $tg = formatField("number", conv_mm_m($tinggi_gross));

                        $dimensi = "$pg x $lg x $tg";
                        // if($stok == 0){
                        //
                        //     $custWarna = " class='text-red'";
                        // }
                        // else{
                        //     $custWarna = "";
                        // }

//cekHere($jenis);
                        switch ($jenis) {
                            case "item":
                                $ctrlName = "Produk";
                                break;
                            case "item_rakitan":
                                $ctrlName = "ProdukRakitan";
                                break;
                            case "item_komposit":
                                $ctrlName = "ProdukKomposit";
                                break;
                        }
                        $linkHist = base_url() . "Data/viewHistories/$ctrlName/$id";
                        $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName change histories $kode $nama ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                        $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' 
                                onclick='linkHistory_$id()'>
                                <span class='glyphicon glyphicon-time'></span>
                                </a>";

                        $script .= "function linkHistory_$id(){ $historyClick }\n";

                        $list .= "<tr style='white-space: nowrap;'>";
                        // $list .= "<td>" . formatField('angka', $no) . "</td>";
                        $list .= "<td>" . formatField("jual", $no) . "</td>";
                        $list .= "<td>$link_history_data</td>";
                        $list .= "<td class='text-center'>$id</td>";
                        $list .= "<td>$jenis</td>";
                        $list .= "<td>$kode</td>";
                        $list .= "<td>$kategori_nama</td>";
                        $list .= "<td>$folders_nama</td>";
                        $list .= "<td>$label</td>";
                        $list .= "<td>$nama</td>";
                        $list .= "<td>$no_part</td>";

                        $list .= "<td class='text-center'>$dimensi</td>";
                        $list .= "<td>" . formatField("angka", conv_g_kg($berat_gross)) . "</td>";
                        $list .= "<td>" . formatField("angka", conv_mmc_mc($volume_gross)) . "</td>";
                        $list .= "<td class='text-center'>$satuan</td>";
                        $tStok = 0;
                        foreach ($cabang as $cabangId => $cabangNama) {
                            foreach ($headers_2 as $header => $attrs) {
                                $vData = $dataChilds[$cabangId][$id][$header];
                                // $vData_f = $vData < 0 ? "<span class='text-red'>" . $vData * -1 . "</span>" : $vData;
                                $colorCb = $cabangId == $cabangID ? " class='bg-success text-right'" : " class='text-right'";
//                                $list .= "<td" . $colorCb . ">" . formatField($header, $vData) . "</td>";
                                $list .= "<td" . $colorCb . ">" . $vData . "</td>";

                                if ($header == "stok") {
//                                    cekBiru(":: $datum[id] $header $vData");
                                    $tStok += $vData;
//                                    $sData = $dataChilds[$cabangId][$datum['id']][$header];
//                                    if(!isset($tStok[$datum['id']])){
//                                        $tStok[$datum['id']] = 0;
//                                    }
//                                    $tStok[$datum['id']] += $sData;
                                }
//                            cekMerah($tStok);
                            }
                        }
//                        arrPrint($tStok);
                        $list .= "<td class='bg-info'>" . formatField("jual", $tStok) . "</td>";
//                        $list .= "<td class='bg-info'>" . $tStok . "</td>";
                        $list .= "</tr>";
                    }
                } else {
                    $list .= "<tr>";
                    $list .= "<td colspan='6' class='text-center'>Tidak ditemukan relevansi data pada pencarian dengan keyword(s) <div class='text-red font-size-2'>$q</div> </td>";
                    $list .= "</tr>";
                }

                $list .= "</table>";
                $list .= "</div>";
                $list .= "</div>";

                $content_a .= "<div class='table-responsive padding-top-0'>";
                $content_a .= $list;
                $content_a .= "</div>";
                break;
            case "b":
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    $box = "";
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }
                        // arrPrint($images);

                        $caption = "Dimensi P.L.T(M): $dimensi_m,";
                        $caption .= " Weight (KG): " . number_format(conv_g_kg($berat_gross)) . ",";
                        $caption .= " Capacity (M3): " . number_format(conv_g_kg($volume_gross));
                        // $caption .= " Weight: " . formatField('angka', conv_mmc_mc($berat_gross));
                        // $caption .= " Capacity: " . formatField('angka', conv_mmc_mc($volume_gross));
                        $image = isset($images[0]['files']) ? $images[0]['files'] : img_blank();
                        $images_e = blobEncode($images);

                        $datum_e = str_replace("=", "", blobEncode($datum));
                        $pic = "<div class='border-cekk' style='height: 200px;background-image: url($image);background-size: cover;background-repeat: no-repeat;background-position: center;'>";
                        $pic .= "</div>";
                        $kodeNama_e = str_replace("=", "", base64_encode($kode . " " . $nama));

                        // $modal_l = base_url()."Katalog/modal/$images_e/$kodeNama_e";
                        // $modal_l = base_url() . "Katalog/modal/$datum_e";


                        $fileImages = array();
                        foreach ($images as $image) {
                            $fileImages[] = $image['files'];
                        }
                        $modals = array(
                            "title" => $kode . " " . $nama,
                            "body" => $fileImages,
                            // "caption" => array($file),
                            "caption" => $caption,
                        );
                        $modal_e = urlencode(blobEncode($modals));
                        $modal_l = base_url() . "Katalog/modal/$modal_e";
                        $box .= "<div style='padding: 5px!important;' class='col-md-3 col-xs-12'>";
                        $box .= "<div class='panel' style='height: 425px;'>";
                        $box .= "<a href='$modal_l' data-toggle='modal' data-target='#myModal'>$pic</a>";

                        // $data = "<ul class='list-group list-group-unbordered'>";
                        $data = "<p class='border-bottom-grey no-margin'>Kode<span class='pull-right'>$kode</span></br>";
                        $data .= "<p class='border-bottom-grey no-margin'>Weight (Kg)<span class='pull-right'>" . formatField('jual', conv_g_kg($berat_gross)) . "</span></p>";
                        $data .= "<p class='border-bottom-grey no-margin'>Capacity (M3)<span class='pull-right'>" . formatField('angka', conv_mmc_mc($volume_gross)) . "</span></p>";
                        $data .= "<p class='no-padding'>Dimensi (M)<span class='pull-right'>$dimensi_m</span></p>";
                        $vStok = 0;
                        $vJual = $vJualnppn = 0;
                        $jmlCb = sizeof($cabang);

                        foreach ($cabang as $cabangId => $cabangNama) {
                            $vStok += $dataChilds[$cabangId][$id]['stok'];

                            if ($cabangId == $cabangID) {
                                $vJualnppn = $dataChilds[$cabangId][$id]['jualnppn'];
                                $vJual = $dataChilds[$cabangId][$id]['jual'];
                                $vHpp = $dataChilds[$cabangId][$id]['hpp'];
                            }
                        }
                        if ($cabangID == CB_ID_PUSAT) {
                            $data .= "<p class='border-bottom-grey no-margin'>Hpp<span class='pull-right'>" . formatField('curency', ($vHpp)) . "</p>";
                        }

                        $data .= "<p class='border-bottom-grey no-margin'>Harga<span class='pull-right'>" . formatField('curency', ($vJual)) . "</p>";
                        $data .= "<p class='border-bottom-grey no-margin'>Harga & VAT<span class='pull-right'>" . formatField('curency', ($vJualnppn)) . "</p>";
                        $data .= "<p class='border-bottom-grey no-margin text-grey-3'>Stok (all)<span class='pull-right'>$vStok</span></br>";

                        // $data .= "</ul>";

                        $box .= "<div class='col-md-12'>";
                        $box .= "<p class='tebal margin-top-10 no-padding text-center'>$nama</p>";
                        $box .= $data;
                        $box .= "</div>";

                        $box .= "</div>";
                        $box .= "</div>";
                    }

                    $content_a .= "<div class='row margin-top-20'>";
                    $content_a .= $box;
                    $content_a .= "</div>";
                }
                break;
        }


        $content = "";
        $content .= $content_a;

        $script .= "</script>";

        $pageStr = "";
        // $self = $startPage = base_url() . "Katalog/viewProduk";
        $self = $startPage = base_url() . $selfSegment;

        $p = New Layout("$title", "$subTitle", "application/template/katalog.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "navigasi_btn" => $navBtn,
            "start_page" => $startPage,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "content" => $content,
            // "form_target"      => $formTarget,
            // "error_msg"        => $error,
            // "submit_btn_label" => "TEST",
            "stop_time" => "",
            "script_bottom" => "$script",
            "isi_modal" => "",
            "page_str" => $pageStr,
            "add_btn" => isset($add_btn) ? $add_btn : "",
        ));

        $p->render();
        break;
    case "Modal":
        $ly = new Layout();
        // cekHijau($footer);
        $ly->setLayoutModalHeader("$heading", true);
        $ly->setLayoutModalBody("$forms");
        $ly->setLayoutModalFooter("$footer");
        // $att = array(
        //     "target" => $target,
        // );
        $actions = isset($actions) ? $actions : "";
        $att = isset($att) ? $att : "";
        $mdl = "";
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";


        echo $mdl;
        break;
    case "KatalogSupplies":
        // cekHitam();
        $defaultKey = isset($_GET['q']) ? $q : "";
        $qStr = isset($_GET['q']) ? "?q=" . $q : "";
        $this->load->helper('he_angka');
        $segment_array = $this->uri->segment_array();
        $layout = isset($segment_array[3]) ? $segment_array[3] : "a";
        // arrPrint($segment_array);
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $selfSegment = $segment_array[1] . "/" . $segment_array[2];
        $this->uri->segment(2);
        $btnLinks = array(
            base_url() . $selfSegment . "/a/$qStr" => "fa-list",
            base_url() . $selfSegment . "/b/$qStr" => "fa-th",
        );
        //region navigasi btn
        $navBtn = "";
        foreach ($btnLinks as $btnLink => $btnIcon) {
            $navBtn .= "<button class='btn btn-default btn-group active' onclick=\"location . href = '$btnLink'\"><i class='fa $btnIcon'></i></button>";

        }
        //endregion


        // arrPrint($data);
        $addkoloms = array("hpp", "jual", "jualnppn", "stok", "images");

        // $produlFields
        $koloms = array_merge($produkFields, $addkoloms);
        foreach ($dataChilds as $dataChild_0s) {
            foreach ($dataChild_0s as $dataChild_0) {
            }
        }
        // arrPrint($dataChild_0);
        foreach ($dataChild_0 as $item => $none) {
            $headers_2[$item] = array(
                "attr" => "class='text-center bg-primary'",
            );
        }
        $jmlChilds = sizeof($dataChild_0);

        $headers = array(
            "no" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "-" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "pID" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "nama" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
            "Satuan" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
        );
        foreach ($cabang as $cabangId => $cabangNama) {
            $headers[$cabangNama] = array("attr" => "colspan='$jmlChilds' class='text-center bg-primary'");
        }
        // arrPrint($koloms);
        // arrPrint($data);
        $content_a = "";
        switch ($layout) {
            default:
                $list = "";
                $list .= "<div class='clearfix'> &nbsp; </div>";
                $list .= "<table id='tabel_katalog' class='table datatable table-bordered table-hover table-condensed'>";

                $list .= "<thead>";
                $list .= "<tr>";
                foreach ($headers as $header => $attrs) {
                    if (is_array($attrs)) {
                        // arrPrint($attrs['attr']);

                        $list .= "<th " . $attrs['attr'] . ">$header</th>";
                    } else {
                        $list .= "<th>$attrs</th>";
                    }
                }
                $list .= "<th " . $attrs['attr'] . ">Total</th>";
                $list .= "</tr>";
                // header 2
                $list .= "<tr>";
                foreach ($cabang as $cabangId => $cabangNama) {
                    foreach ($headers_2 as $header => $attrs) {
                        if (is_array($attrs)) {
                            // arrPrint($attrs['attr']);

                            $list .= "<th " . $attrs['attr'] . ">$header</th>";
                        } else {
                            $list .= "<th>$attrs</th>";
                        }
                    }
                }
                $list .= "<th " . $attrs['attr'] . ">Stok</th>";
                $list .= "</tr>";
                $list .= "</thead>";

                $script = "";
                $script .= "<script>";

                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }

                        // $lg = formatField("number", conv_mm_m($lebar_gross));
                        // $pg = formatField("number", conv_mm_m($panjang_gross));
                        // $tg = formatField("number", conv_mm_m($tinggi_gross));
                        // $dimensi = "$pg x $lg x $tg";
                        switch ($jenis) {
                            case "item":
                                $ctrlName = "Supplies";
                                break;

                        }
                        $linkHist = base_url() . "Data/viewHistories/$ctrlName/$id";
                        $historyClick = "BootstrapDialog.closeAll();BootstrapDialog.show({title:'$ctrlName change histories $nama',message:$('<div></div>').load('" . $linkHist . "'),size:BootstrapDialog.SIZE_WIDE,draggable:true,closable:true});";
                        $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick='linkHistory_$id()'><span class='glyphicon glyphicon-time'></span></a>";
//                        $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";

                        $script .= "function linkHistory_$id(){ $historyClick }\n";

                        $list .= "<tr style='white-space: nowarp;'>";
                        $list .= "<td>" . formatField("jual", $no) . "</td>";
                        $list .= "<td>$link_history_data</td>";
                        $list .= "<td class='text-center'>$id</td>";
                        $list .= "<td>$nama</td>";
                        $list .= "<td class='text-center'>$satuan</td>";

                        // $list .= "<td>" . formatField("jual", $hpp) . "</td>";
                        // $list .= "<td>" . formatField("jual", $stok) . "</td>";

                        $tStok = 0;
                        foreach ($cabang as $cabangId => $cabangNama) {
                            foreach ($headers_2 as $header => $attrs) {
                                $vData = $dataChilds[$cabangId][$id][$header];
                                $colorCb = $cabangId == $cabangID ? " class='bg-success'" : "";
                                $list .= "<td" . $colorCb . ">" . formatField("jual", $vData) . "</td>";

                                if ($header == "stok") {
                                    $tStok += $vData;
                                }
                            }
                        }
                        $list .= "<td class='bg-info'>" . formatField("jual", $tStok) . "</td>";

                        $list .= "</tr>";
                    }
                } else {
                    $list .= "<tr>";
                    $list .= "<td colspan='6' class='text-center'>Tidak ditemukan relevansi data pada pencarian dengan keyword(s) <div class='text-red font-size-2'>$q</div> </td>";
                    $list .= "</tr>";
                }
                $list .= "</table>";

                $content_a .= "<div class='table-responsive padding-top-0'>";
                $content_a .= $list;
                $content_a .= "</div>";
                break;
            case "b":
                if (sizeof($dataParents) > 0) {
                    $no = 0;
                    $box = "";
                    foreach ($dataParents as $datum) {
                        $no++;
                        foreach ($koloms as $kolom) {
                            $$kolom = $datum[$kolom];
                        }
                        // arrPrint($images);

                        // $lg = formatField("number", conv_mm_m($lebar_gross));
                        // $pg = formatField("number", conv_mm_m($panjang_gross));
                        // $tg = formatField("number", conv_mm_m($tinggi_gross));
                        // $dimensi = "$pg x $lg x $tg";

                        $image = isset($images[0]['files']) ? $images[0]['files'] : img_blank();
                        $images_e = blobEncode($images);
                        $datum_e = str_replace("=", "", blobEncode($datum));
                        $pic = "<div class='border-cekk' style='height: 200px;background-image: url($image);background-size: cover;background-repeat: no-repeat;background-position: center;'>";
                        $pic .= "</div>";
                        $kodeNama_e = str_replace("=", "", base64_encode($nama));

                        // $modal_l = base_url()."Katalog/modal/$images_e/$kodeNama_e";
                        $modal_l = base_url() . "Katalog/modal/$datum_e";
                        $box .= "<div style='padding: 5px!important;' class='col-md-3 col-sm-6 col-xs-12'>";
                        $box .= "<div class='panel' style='height: 400px;'>";
                        $box .= "<a href='$modal_l' data-toggle='modal' data-target='#myModal'>$pic</a>";

                        $data = "";
                        // $data = "<p class='border-bottom-grey no-margin'>Harga<span class='pull-right'>" . formatField('curency', $hpp) . "</p>";
                        // $data .= "<p class='border-bottom-grey no-margin'>Stok<span class='pull-right'>$stok</span></br>";
                        $vStok = 0;
                        $vJual = $vJualnppn = 0;
                        $jmlCb = sizeof($cabang);

                        foreach ($cabang as $cabangId => $cabangNama) {
                            $vStok += $dataChilds[$cabangId][$id]['stok'];

                            if ($cabangId == $cabangID) {
                                // $vJualnppn = $dataChilds[$cabangId][$id]['jualnppn'];
                                // $vJual = $dataChilds[$cabangId][$id]['jual'];
                                $vHpp = $dataChilds[$cabangId][$id]['hpp'];
                            }
                        }
                        if ($cabangID == CB_ID_PUSAT) {
                            $data .= "<p class='border-bottom-grey no-margin'>Hpp<span class='pull-right'>" . formatField('curency', ($vHpp)) . "</p>";
                        }
                        $data .= "<p class='border-bottom-grey no-margin text-grey-3'>Stok (all)<span class='pull-right'>$vStok</span></br>";

                        $box .= "<div class='col-md-12'>";
                        $box .= "<p class='tebal margin-top-10 no-padding text-center'>$nama</p>";
                        $box .= $data;
                        $box .= "</div>";

                        $box .= "</div>";
                        $box .= "</div>";
                    }

                    $content_a .= "<div class='row margin-top-20'>";
                    $content_a .= $box;
                    $content_a .= "</div>";
                }
                break;
        }


        $content = "";
        $content .= $content_a;


        $script .= "</script>";

        $pageStr = "";
        $self = $startPage = base_url() . $selfSegment;

        $p = New Layout("$title", "$subTitle", "application/template/katalog.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "navigasi_btn" => $navBtn,
            "start_page" => $startPage,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "content" => $content,
            // "form_target"      => $formTarget,
            // "error_msg"        => $error,
            // "submit_btn_label" => "TEST",
            "stop_time" => "",
            "script_bottom" => "$script",
            "isi_modal" => "",
            "page_str" => $pageStr,
            "add_btn" => isset($add_btn) ? $add_btn : "",
        ));

        $p->render();
        break;
    case "Data":
        // cekHitam();
        $defaultKey = isset($_GET['q']) ? $q : "";
        $qStr = isset($_GET['q']) ? "?q=" . $q : "";
        $this->load->helper('he_angka');
        $segment_array = $this->uri->segment_array();
        $layout = isset($segment_array[3]) ? $segment_array[3] : "a";
        // arrPrint($segment_array);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $optionSorting = "<select class='btn btn-default btn-group active' style='font-size: 0.85em;'>";
        $optionSorting .= "<option>--pilih urutan--</option>";
        $optionSorting .= "<option>urut atas</option>";
        $optionSorting .= "<option>urut bawah</option>";
        $optionSorting .= "</select>";
        $selfSegment = $segment_array[1] . "/" . $segment_array[2];
        $this->uri->segment(2);
        $btnLinks = array(
            // $optionSorting => "fa-sort",
            base_url() . $selfSegment . "/a/$qStr" => "fa-list",
            base_url() . $selfSegment . "/b/$qStr" => "fa-th",
        );
        //region navigasi btn
        $navBtn = "";
        $navBtn .= $optionSorting;
        foreach ($btnLinks as $btnLink => $btnIcon) {
            $navBtn .= "<button class='btn btn-default btn-group active' onclick=\"location . href = '$btnLink'\"><i class='fa $btnIcon'></i></button>";

        }
        $navBtn = "";
        //endregion

        $content = "";
        $content .= "<div class='table-responsive margin-top-10'>";
        $content .= "<table class='table table-bordered table-hover table-condensed anu'>";
        $content .= "<thead>";
        $content .= "<tr class='text-uppercase valign-m'>";
        foreach ($headers as $header => $hAttr) {
            $content .= "<th $hAttr>$header</th>";
        }
        $content .= "</tr>";
        $content .= "</thead>";

// arrPrint($bodies);
        $content .= "<tbody>";
        foreach ($bodies as $body => $datas) {

            $content .= "<tr>";
            foreach ($datas as $data) {
// arrPrint($data);
                $value = $data["value"];
                $attr = isset($data["attr"]) ? $data["attr"] : "";
                $value_f = isset($data["format"]) ? $data["format"]("hpp", $value) : $value;

                $content .= "<td $attr>$value_f</td>";
            }
            $content .= "</tr>";
        }
        $content .= "</tbody>";

        $content .= "</table>";
        $content .= "</div>";

        // $script_bottom = "";
        $script_bottom = "<script>
                            $(document).ready( function(){
                                var table = $('table.anu').DataTable({
                                    dom: 'lBfrtip',
                                    stateSave: true,
                                    colReorder: true,
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                            ],
                                   buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                                                  
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
                                                    });
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
        
        
                                                });
                                            }
                                        });
        
                                    $('.table-responsive').floatingScroll();
                                    });
        
                                    
                                    
                                    $('.table-responsive').scroll(
                                        delay_v2(function () {
                                            $('table.anu').DataTable().fixedHeader.adjust();
                                        }, 200)
                                    );
                            </script>";

        $p = New Layout("$title", "$subTitle", "application/template/katalog.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "navigasi_btn" => $navBtn,
            "start_page" => "",
            // "profile_name" => $this->session->login['nama'],
            // "self" => $self,
            // "default_key" => $defaultKey,
            "content" => $content,
            // "form_target"      => $formTarget,
            // "error_msg"        => $error,
            "default_key" => $q,
            "stop_time" => "",
            "script_bottom" => $script_bottom,
            "isi_modal" => "",
            "page_str" => "", // penempatan halaman
            "add_btn" => isset($add_btn) ? $add_btn : "",
        ));

        $p->render();
        break;

    case "ModalFifo":

        if (isset($items) && sizeof($items) > 0) {
            $str = "";
            $str .= "<div class='table-responsive'>";
            $str .= "<table  class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $str .= "<tr bgcolor='#f5f5f5'>";
            $str .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($header as $cKol => $cAlias) {
                $str .= "<th class='text-muted' style='font-weight:bold;'>";
                $str .= $cAlias;
                $str .= "</th>";
            }
            $str .= "</tr>";

            $n = 0;
            $sumFooter = array();
            foreach ($items as $itemsSrc_0) {
                $n++;
                $str .= "<tr line=" . __LINE__ . ">";
                $str .= "<td align='right'>$n</td>";
                foreach ($header as $cKol => $cAlias) {
                    $str .= "<td>";
//                    $str .= formatField($cKol, $itemsSrc_0[$cKol]);
//                    $str .= formatField_he_format($cKol, $itemsSrc_0[$cKol]);
                    $str .= formatField_he_format($cKol, $itemsSrc_0[$cKol], $itemsSrc_0["jenisTr"], $itemsSrc_0["modul_path"]);
                    $str .= "</td>";

                    if (is_numeric($itemsSrc_0[$cKol])) {
                        if (!isset($sumFooter[$cKol])) {
                            $sumFooter[$cKol] = 0;
                        }
                        $sumFooter[$cKol] += $itemsSrc_0[$cKol];
                    }
                }
                $str .= "</tr>";
            }


            $str .= "<tr bgcolor='#f5f5f5'>";
            $str .= "<th class='text-muted' style='font-weight:bold;' align='center' width='5'>-</th>";
            foreach ($header as $cKol => $cAlias) {
                $str .= "<th class='text-muted' style='font-weight:bold;'>";
                if (isset($footer)) {
                    if (array_key_exists($cKol, $footer)) {
                        $vals = formatField($cKol, $sumFooter[$cKol]);
                    } else {
                        $vals = "-";
                    }
                } else {
                    $vals = "-";
                }
                $str .= $vals;

                $str .= "</th>";
            }
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "</div>";
        }

        echo $str;
        break;

    case "KatalogGudangAktiva":
        $class_hidden = "";
        if ($isMob == true) {
            $class_hidden = "hidden-xs hidden-sm";
        }
        // arrPrint($gudangData);
        $defaultKey = isset($_GET['q']) ? $q : "";
        $qStr = isset($_GET['q']) ? "?q=" . $q : "";
        $this->load->helper('he_angka');
        $segment_array = $this->uri->segment_array();
        $layout = isset($segment_array[3]) ? $segment_array[3] : "a";


        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $selfSegment = $segment_array[1] . "/" . $segment_array[2];
        $this->uri->segment(2);
        $btnLinks = array(
            base_url() . $selfSegment . "/a/$qStr" => "fa-list",
            base_url() . $selfSegment . "/b/$qStr" => "fa-th",
        );
        //region navigasi btn
        $navBtn = "";
        foreach ($btnLinks as $btnLink => $btnIcon) {
            $navBtn .= "<button class='btn btn-default btn-group active' onclick=\"location . href = '$btnLink'\"><i class='fa $btnIcon'></i></button>";

        }
        //endregion

//        $addkoloms = array("dimensi_m", "images");

        $koloms = $produkFields;
        foreach ($dataChilds["-1"] as $dataChild_0s) {
            foreach ($dataChild_0s as $dataChild_0) {
            }
        }


        $jmlChilds = sizeof($dataChild_0);

        /*---header katalog diatur disini----*/
        $headers = array(
            "no" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),
//            "-" => array(
//                "attr" => "rowspan='2' class='text-center bg-primary $class_hidden'",
//            ),
            "pID" => array(
                "attr" => "rowspan='2' class='text-center bg-primary $class_hidden'",
            ),
            "kode" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),

            "nama" => array(
                "attr" => "rowspan='2' class='text-center bg-primary'",
            ),

        );

        $headerGudang = array();
        foreach ($cabang as $cabangId => $cabangNama) {
            $col = (sizeof($gudangData[$cabangId]) * 1);
            // cekHitam($cabangNama)*$jmlChilds;
            $headers[$cabangNama] = array("attr" => "colspan='$col' class='text-center bg-primary $class_hidden'");
            if (isset($gudangData[$cabangId])) {
                foreach ($gudangData[$cabangId] as $i => $temp) {
                    $headerGudang[$cabangId][$temp["nama"]] = array("attr" => "colspan='' class='text-center bg-primary $class_hidden'");
                }
            }

        }

        $content_a = "";
        $array_content = array();
        //region header
        $list = "";
        $list .= "<div class='clearfix'> &nbsp; </div>";
        $list .= "<div class='box box-info'>";
        $list .= "<div class='box-body table-responsive'>";
        $list .= "<table  layout=".$layout." id='tabel_katalog' class='table table-bordered table-hover table-condensedx'>";

        // header 1
        $list .= "<thead>";

        $list .= "<tr>";

        foreach ($headers as $header => $attrs) {
            if (is_array($attrs)) {
                $list .= "<th " . $attrs['attr'] . ">$header</th>";
            } else {
                $list .= "<th>$attrs</th>";
            }
        }
        $list .= "<th rowspan='2' class='text-center bg-primary'>TOTAL</th>";
        $list .= "</tr>";

        // header 2
        $list .= "<tr>";
//        $list .= "<th>*</th>";
//        $list .= "<th>*</th>";
//        $list .= "<th>*</th>";
//        $list .= "<th>*</th>";
        foreach ($cabang as $cabangId => $cabangNama) {
            foreach ($headerGudang[$cabangId] as $header => $attrs) {
                if (is_array($attrs)) {
                    $list .= "<th " . $attrs['attr'] . ">$header</th>";
                } else {
                    $list .= "<th>$attrs</th>";
                }
            }
//                    $list .= "<th rowspan='' class='text-center bg-primary $class_hidden'>subtotal stok</th>";
        }
//        $list .= "<th rowspann='' class='text-center bg-primary'>Total</th>";
        $list .= "</tr>";

//        $list .= "<tr>";
//        foreach ($cabang as $cabangId => $cabangNama) {
//            foreach ($gudangData[$cabangId] as $gID => $cabangdata) {
//                // arrPrint($iindex);
//                foreach ($headers_2 as $header => $attrs) {
//                    if (is_array($attrs)) {
//                        $list .= "<th " . $attrs['attr'] . ">$header</th>";
//                    } else {
//                        $list .= "<th>$attrs</th>";
//                    }
//                }
//            }
//
//        }

//        $list .= "</tr>";

        $list .= "</thead>";

        $script = "";
        $script .= "<script>";
        //endregion

        if (sizeof($dataParents) > 0) {
            $no = 0;
            foreach ($dataParents as $datum) {
                $no++;
                foreach ($koloms as $kolom) {
                    $$kolom = $datum[$kolom];
                }
//                        cekHere($nama);
                $ctrlName = "Produk";
                switch ($jenis) {
                    case "item":
                        $ctrlName = "Produk";
                        break;
                    case "item_rakitan":
                        $ctrlName = "ProdukRakitan";
                        break;
                    case "item_komposit":
                        $ctrlName = "ProdukKomposit";
                        break;
                }

                $linkHist = base_url() . "Data/viewHistories/$ctrlName/$id";
                $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName change histories $kode $nama ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                $link_history_data = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' 
                                onclick='linkHistory_$id()'>
                                <span class='glyphicon glyphicon-time'></span>
                                </a>";

                $script .= "function linkHistory_$id(){ $historyClick }\n";

                $list .= "<tr style='white-space: nowrap;'>";

                // $list .= "<td>" . formatField('angka', $no) . "</td>";
                $list .= "<td>" . formatField("jual", $no) . "</td>";
//                $list .= "<td class='$class_hidden'>$link_history_data</td>";
                $list .= "<td class='text-center $class_hidden'>$id</td>";
                $list .= "<td>$kode</td>";
                $list .= "<td>$nama</td>";

                $tStok = 0;
                foreach ($cabang as $cabangId => $cabangNama) {
                    $colorCb = $cabangId == $cabangID ? " class='bg-success text-right $class_hidden'" : " class='text-right $class_hidden'";
                    foreach ($gudangData[$cabangId] as $gID => $gidLabel) {
                        $jml = isset($dataChilds[$cabangId][$gID][$id]["qty_debet"]) ? $dataChilds[$cabangId][$gID][$id]["qty_debet"]:0;
                        $tStok +=$jml;
                        $list .= "<td>" . formatField("total", $jml) . "</td>";
                    }
//                    $list .= "<td" . $colorCb . ">" . formatField("total", $dataChilds[$cabangId][$id]) . "**</td>";

                }

                $list .= "<td class='bg-info total'>" . formatField("total", $tStok) . "</td>";
                //                        $list .= "<td class='bg-info'>" . $tStok . "</td>";
                $list .= "</tr>";
            }
        } else {
            $list .= "<tr>";
            $list .= "<td colspan='6' class='text-center'>Tidak ditemukan relevansi data pada pencarian dengan keyword(s) <div class='text-red font-size-2'>$q</div> </td>";
            $list .= "</tr>";
        }

        $list .= "</table>";
        $list .= "</div>";
        $list .= "</div>";

        $content_a .= "<div class='table-responsive padding-top-0'>";
        $content_a .= $list;
        $content_a .= "</div>";

        $content = "";
        $content .= $content_a;
        $script .= "</script>";

        $pageStr = "";
        // $self = $startPage = base_url() . "Katalog/viewProduk";
        $self = $startPage = base_url() . $selfSegment;

        $p = New Layout("$title", "$subTitle", "application/template/katalog_aset.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "navigasi_btn" => $navBtn,
            "start_page" => $startPage,
            "profile_name" => $this->session->login['nama'],
            "self" => $self,
            "default_key" => $defaultKey,
            "content" => $content,
            // "form_target"      => $formTarget,
            // "error_msg"        => $error,
            // "submit_btn_label" => "TEST",
            "stop_time" => "",
            "script_bottom" => "$script",
            "isi_modal" => "",
            "page_str" => $pageStr,
            "add_btn" => isset($add_btn) ? $add_btn : "",
        ));

        $p->render();
        break;
}