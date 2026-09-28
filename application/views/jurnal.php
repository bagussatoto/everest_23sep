<?php
//cekHere($mode);
switch ($mode) {

    default:

        break;
    case "jurnal":
        $p = New Layout("$title", "$subTitle", "application/template/jurnal.html");

//arrPrintWebs($jenisAlias);
        $transaksies = array();
        $transaksiJenies = array();
        $ktransaksies = array();
        $jenies = array();
        if (sizeof($djurnalDatas) > 0) {

            foreach ($djurnalDatas as $jenis => $datas_0) {

                $jenies[$jenis] = isset($jenisAlias[$jenis]) ? $jenisAlias[$jenis] : $jenis;
                foreach ($datas_0 as $transaksi_id => $datas_2) {

                    foreach ($datas_2 as $urut => $item) {

                        $transaksies[$transaksi_id][$urut] = $item;

                        $transaksiJenies[$transaksi_id] = isset($jenisAlias[$jenis]) ? $jenisAlias[$jenis] : $jenis;
                    }
                }
            }


            foreach ($kjurnalDatas as $jenis => $datas_0) {
                $jenies[$jenis] = isset($jenisAlias[$jenis]) ? $jenisAlias[$jenis] : $jenis;
                foreach ($datas_0 as $transaksi_id => $datas_2) {
                    foreach ($datas_2 as $urut => $item) {

                        $ktransaksies[$transaksi_id][$urut] = $item;
                    }

                }
            }
        }

//        arrPrintWebs($addDatas);
        $isi = "";
        $isi_f = "";
        foreach ($transaksies as $tr_id => $trDatas_0) {
            $jnAlias = $transaksiJenies[$tr_id];
//arrPrintWebs($trDatas_0);

            $trNomer = isset($transaksiDatas[$tr_id]) ? $transaksiDatas[$tr_id]->nomer : "-";
            $trJenis = isset($transaksiDatas[$tr_id]) ? $transaksiDatas[$tr_id]->jenis : "--";

            $dCounter = isset($transaksiDatas[$tr_id]) ? blobDecode($transaksiDatas[$tr_id]->counters) : "";
            $ids_his = isset($transaksiDatas[$tr_id]) ? $transaksiDatas[$tr_id]->ids_his : "";
            $trIdh = isset($transaksiDatas[$tr_id]) ? blobDecode($transaksiDatas[$tr_id]->ids_his) : "";
            $idHis_jml = sizeof($trIdh);
            // arrPrintPink($dCounter);
            // $idhCounter = blobDecode($trIdh[$idHis_jml]["counters"]);
            $myNumber = showHistoriGlobalNumbers($ids_his, $idHis_jml, true, $trJenis);
//cekHere($myNumber);
            $trDtime = isset($transaksiDatas[$tr_id]) ? $transaksiDatas[$tr_id]->dtime : "";
            $trOlehNama = isset($transaksiDatas[$tr_id]) ? $transaksiDatas[$tr_id]->oleh_nama : "";
            $trNomer = isset($transaksiDatas[$tr_id]) ? $transaksiDatas[$tr_id]->nomer : "";

            $judul = "$jnAlias <span class='text-white pull-right'>&nbsp;$tr_id</span>";

            // $isi .= "<div class='box box-info'>";
            $isi_box = "";
            $isi_box .= "<p class='no-padding no-margin'>Date : $trDtime</p>";
            $isi_box .= "<p class='no-padding no-margin'>Nomer : $myNumber</p>";
            $isi_box .= "<p class='no-margin no-padding'>PIC : $trOlehNama</p>";


            ksort($trDatas_0);
            foreach ($trDatas_0 as $urut => $data_0) {

                $data_1 = $kredits = isset($ktransaksies[$tr_id][$urut]) ? $ktransaksies[$tr_id][$urut] : array();
//                $trDatas = array_merge($data_0, $data_1);
                $trDatas = $data_0 + $data_1;
//arrPrintWebs($data_0);
//arrPrintKuning($data_1);

                $isi_box .= "<table class='table table-bordered table-condensed'>";

                $isi_box .= "<thead>";
                $isi_box .= "<tr class='bg-grey-1'>";
                $isi_box .= "<th>Rekening</th>";
                $isi_box .= "<th>Nomor Referensi</th>";
                $isi_box .= "<th>Debet</th>";
                $isi_box .= "<th>Kredit</th>";
                $isi_box .= "</tr>";
                $isi_box .= "</thead>";
//arrPrintPink($trDatas);
                $isi_box .= "<tbody>";
                foreach ($trDatas as $rek_nama => $trDk) {
                    // arrPrint($trDk);
                    $debet = isset($trDk['debet']) ? $trDk['debet'] : "0";
                    $kredit = isset($trDk['kredit']) ? $trDk['kredit'] : "0";
                    $debet_f = formatField("debet", $debet);
                    $kredit_f = formatField("kredit", $kredit);
                    $text_padding = $kredit > 0 ? "style='padding-left:25px;'" : "";
                    if (isset($rekeningAlias[$rek_nama])) {
                        $rek_nama_f = $rekeningAlias[$rek_nama];
                    }
                    else {
                        $rek_nama_f = $rek_nama;
                    }

                    $link = isset($addDatas[$trJenis][$tr_id][$rek_nama]['link']) ? $addDatas[$trJenis][$tr_id][$rek_nama]['link'] : "";
                    $referensi = isset($addDatas[$trJenis][$tr_id][$rek_nama]['referensi']) ? $addDatas[$trJenis][$tr_id][$rek_nama]['referensi'] : "-";

                    $isi_box .= "<tr>";
                    $isi_box .= "<td class='col-md-4' $text_padding><a href='$link' title='klik untuk melihat mutasi $rek_nama_f'>$rek_nama_f</a></td>";
                    $isi_box .= "<td>$referensi</td>";
                    $isi_box .= "<td class='col-md-2 text-right'>$debet_f</td>";
                    $isi_box .= "<td class='col-md-2 text-right'>$kredit_f</td>";
                    $isi_box .= "</tr>";

                    if(!isset($totals['debet'])){
                        $totals['debet'] = 0;
                    }
                    $totals['debet'] += $debet;
                    if(!isset($totals['kredit'])){
                        $totals['kredit'] = 0;
                    }
                    $totals['kredit'] += $kredit;
                }
                $isi_box .= "</tbody>";

                $isi_box .= "<thead>";
                $isi_box .= "<tr class='bg-grey-1'>";
                $isi_box .= "<th colspan='2' class='text-right text-renggang-5'>cross balance cek</th>";
                foreach ($totals as $posisi => $total) {

                    $sumTotal_f = $debet_f = formatField($posisi, $total);
                    $isi_box .= "<th class='col-md-1 text-right'>$sumTotal_f</th>";
                }
                $isi_box .= "</tr>";
                $isi_box .= "</thead>";

                $isi_box .= "</table>";


            }


            $isi_f .= "<div class='col-md-12'>";
            $p->setLayoutBoxCss("box-solid");
            $p->setLayoutBoxHeading($judul);
            $p->setLayoutBoxBody(true);
            $isi_f .= $p->layout_box($isi_box);
            $isi_f .= "</div>";
        }


        //region navigasi
        $vd_start = $beginDate;
        $vd_stop = $endDate;
        $vfx = $jenisJurnal;

        $content_nav = "<div class='row margin-bottom-10'>";
        $content_nav .= "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline overflow-h'>";

        $content_nav .= "<form method='get' action=''>";
        //region tanggal mulai star
        $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                            <label>start: </label>
                            <input name='d_start' id='d_start' class='form-control' type='date' value='$vd_start'>
                        </div>";
        //endregion
        //region tanggal stop
        $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                            <label> stop: </label>
                            <input name='d_stop' id='s_stop' class='form-control' type='date' value='$vd_stop'>
                        </div>";
        //endregion
        //region selector jurnal
        $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                            <label>jurnal yang ditampilkan: </label>";
        $content_nav .= "<select class='form-control' name='fx' onchange=\"\">";
        $pilihSemua = $vfx == "semua" ? "selected" : "";
        $content_nav .= "<option value='semua'>--pilih fungsi--</option>";
        $content_nav .= "<option value='semua' $pilihSemua>semua</option>";
        // cekHitam(sizeof($jenisAlias));
        // arrPrint($jenisAlias);
        // cekHitam(sizeof($jenisAliasing));
        // arrPrintWebs($jenisAliasing);
        // $jenisJurnalAliasings = array_intersect_key($jenisAlias,$jenisAliasing);
        // cekHitam(sizeof($jenisJurnalAliasings));
        // arrPrint($jenisJurnalAliasings);
        asort($jenisAlias);
        // foreach ($jenisJurnalAliasings as $jKey => $jLabel) {
        foreach ($jenisAlias as $jKey => $jLabel) {
            // foreach ($jenies as $jKey => $jLabel) {
            $pilihan = $jKey == $vfx ? "selected" : "";
            $content_nav .= "<option value='$jKey' $pilihan>$jLabel</option>";

        }
        $content_nav .= "</select>";
        $content_nav .= "</div>";
        //endregion

        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>                                        
                                    <i class='fa fa-anchor'></i> terapkan filter tanggal</button>";

        // $content_nav .= "<button type='button' class='btn btn-danger' style='float: right;' onclick=\"location.href='$excel_link'\"><i class='fa fa-download'></i> EXCEL</button>";
        $content_nav .= "<button type='button' class='btn btn-danger' style='float: right;' onclick=\"download_excel();\"><i class='fa fa-download'></i> EXCEL</button>";
        $content_nav .= "</form>";

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "</div>";

        $content_nav .= downloadXlsx($excel_link, $excel_data, $excel_nama);

        //endregion

        // $isi .= $isi_nav;
        $isi .= $content_nav;
        $isi .= "<div class='row'>";
        $isi .= $isi_f;
        $isi .= "</div>";


        $str = $isi;
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
}