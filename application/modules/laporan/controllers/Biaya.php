<?php

class Biaya extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "biaya/";
        $this->default_limit = 200;
        $this->jenisTr = "5822";
        // $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("467", "460", "461", "967");

        $this->tbl_umum = "__rek_pembantu_subbiayaumum__6030";
        $this->tbl_usaha = "__rek_pembantu_subbiayausaha__6010";
        $this->tbl_belumditempatkan = "__rek_pembantu_subbiaya__6100010";
        $this->tbl_2 = "transaksi";
    }

    //     -----------------------
    public function cekbulanan()
    {
        // arrPrintHijau(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas_0 = url_segment(5);
        $aktivitas = isset($aktivitas_0) ? $aktivitas_0 : 1;
        $subjek_0 = $xid = $_GET['xid'];
        // $title_now = isset($aktivitas) ? "Sales Order" : "Packing List";
        // cekHere("$subjek_0");
        $title_now = isset($aktivitas) ? "Biaya " : "-";
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $thn = formatTanggal($date1, "Y");
        $tambahn = array(
            "extern2_id" => $xid,
        );
        // $cek = $this->call_rek_pembantu_subpiutangsupplier_cache($thn,$tambahn);
        // showLast_query("here");
        // arrPrintBiru($cek);
        // -------------------------------
        // $cek = $this->call_rek_pembantu_subpiutangsupplier_mutasi($thn,$tambahn);
        // showLast_query("merah");
        // arrPrint($cek);
        // matiHere(__LINE__);

        $url_detil = false;
        $url_bulan = false;
        $condite_cust = array(
            "produk_id >=" => "33",
            "produk_id <=" => "50",
        );
        // $this->db->where($condite_cust);

        switch ($subjek_0) {
            case "umum":
                // $this->load->model("Mdls/MdlRawPembelian");
                // $dt = new MdlRawPembelian();

                // $tbl_1 = $dt->getTbl1();
                // $tbl_2 = $dt->getTbl2();
                // $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
                $condites = array(
                    // "fulldate>=" => $date1,
                    // "fulldate<=" => $date2,
                    // "date($tbl_1.dtime)>=" => $date1,
                    // "date($tbl_1.dtime)<=" => $date2,
                    // "produk_id" => "46768",
                );
                // $this->db->where($condites);
                if (my_cabang_id() != CB_ID_PUSAT) {
                    // $this->db->where("cabang_id", my_cabang_id());
                }
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = array();
                $src_00 = $this->call_rek_pembantu_subbiayaumum($req_thn, $req_bln);

                // showLast_query("kuning");
                // matiHere(__LINE__);
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint($src_00);
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                // matiHere(__LINE__);
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    // arrPrintPink($items);
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    $produk_id = $items->extern_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->extern_nama;

                    /* -----------------------------------------------------------
                     * sumbulanan
                     * -----------------------------------------------------------*/
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                // $subjekYgTerlibats = array();
                // arrPrintHijau(array_slice($subjekYgTerlibats,1,1));
                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = $total_penjualan > 0 ? ((1 - ($total_hpp / $total_penjualan)) * 100) : 0;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * locker stok aktive
                 * --------------------------------------------------------------------------------
                 */
                // $this->load->model("Mdls/MdlLockerStock");
                // $ls = new MdlLockerStock();
                // if (my_cabang_id() != CB_ID_PUSAT) {
                //     $this->db->where("cabang_id", my_cabang_id());
                // }
                // $this->db->select(array(
                //     "produk_id",
                //     "nama",
                //     "sum(jumlah) as 'sum_jumlah'"
                // ));
                // $this->db->group_by("produk_id");
                // $src_ls_00 = $ls->callProdukStok();
                // $src_ls = $src_ls_00['datas'];
                // $src_ls_all = $src_ls_00['all'];
                // // showLast_query("orange");
                // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
                // // arrPrintHijau(array_slice($src_ls, 0, 1));
                // $ls_aktivies = array();
                // foreach ($src_ls as $src_l) {
                //     $ls_produk_id = $src_l->produk_id;
                //
                //     $ls_aktivies[$ls_produk_id] = $src_l;
                // }
                // cekOrange(sizeof($ls_aktivies));

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlDtaBiayaUmum");
                $pr = new MdlDtaBiayaUmum();
                // $this->db->where("kategori_id", $_GET['kategori_id']);
                $masterHeaders = array(
                    // "kategori_id"   => array(
                    //     "label" => "cid",
                    // ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                        // "links"       => array(
                        //     "heading" => "kategori_nama",
                        //     "key" => "kategori_id",
                        //     "target" => "laporan/Penjualan/cekpenjualanbulanan/produk",
                        // ),
                    ),
                );
                $url_detil['jenis_biaya'] = $xid;
                break;
            case "usaha":
                // $this->load->model("Mdls/MdlRawPembelian");
                // $dt = new MdlRawPembelian();

                // $tbl_1 = $dt->getTbl1();
                // $tbl_2 = $dt->getTbl2();
                // $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
                $condites = array(
                    // "fulldate>=" => $date1,
                    // "fulldate<=" => $date2,
                    // "date($tbl_1.dtime)>=" => $date1,
                    // "date($tbl_1.dtime)<=" => $date2,
                    // "produk_id" => "46768",
                );
                // $this->db->where($condites);
                if (my_cabang_id() != CB_ID_PUSAT) {
                    // $this->db->where("cabang_id", my_cabang_id());
                }
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = array();
                $src_00 = $this->call_rek_pembantu_subbiayausaha($req_thn, $req_bln);

                // showLast_query("kuning");
                // matiHere(__LINE__);
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint($src_00);
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                // matiHere(__LINE__);
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    // arrPrintPink($items);
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    $produk_id = $items->extern_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->extern_nama;

                    /* -----------------------------------------------------------
                     * sumbulanan
                     * -----------------------------------------------------------*/
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                // $subjekYgTerlibats = array();
                // arrPrintHijau(array_slice($subjekYgTerlibats,1,1));
                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = $total_penjualan > 0 ? ((1 - ($total_hpp / $total_penjualan)) * 100) : 0;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * locker stok aktive
                 * --------------------------------------------------------------------------------
                 */
                // $this->load->model("Mdls/MdlLockerStock");
                // $ls = new MdlLockerStock();
                // if (my_cabang_id() != CB_ID_PUSAT) {
                //     $this->db->where("cabang_id", my_cabang_id());
                // }
                // $this->db->select(array(
                //     "produk_id",
                //     "nama",
                //     "sum(jumlah) as 'sum_jumlah'"
                // ));
                // $this->db->group_by("produk_id");
                // $src_ls_00 = $ls->callProdukStok();
                // $src_ls = $src_ls_00['datas'];
                // $src_ls_all = $src_ls_00['all'];
                // // showLast_query("orange");
                // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
                // // arrPrintHijau(array_slice($src_ls, 0, 1));
                // $ls_aktivies = array();
                // foreach ($src_ls as $src_l) {
                //     $ls_produk_id = $src_l->produk_id;
                //
                //     $ls_aktivies[$ls_produk_id] = $src_l;
                // }
                // cekOrange(sizeof($ls_aktivies));

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlDtaBiayaUsaha");
                $pr = new MdlDtaBiayaUsaha();
                // $this->db->where("kategori_id", $_GET['kategori_id']);
                $masterHeaders = array(
                    // "kategori_id"   => array(
                    //     "label" => "cid",
                    // ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                        // "links"       => array(
                        //     "heading" => "kategori_nama",
                        //     "key" => "kategori_id",
                        //     "target" => "laporan/Penjualan/cekpenjualanbulanan/produk",
                        // ),
                    ),
                );
                $url_detil['jenis_biaya'] = $xid;
                break;
            case "belumditempatkan":
                // $this->load->model("Mdls/MdlRawPembelian");
                // $dt = new MdlRawPembelian();

                // $tbl_1 = $dt->getTbl1();
                // $tbl_2 = $dt->getTbl2();
                // $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
                $condites = array(
                    // "fulldate>=" => $date1,
                    // "fulldate<=" => $date2,
                    // "date($tbl_1.dtime)>=" => $date1,
                    // "date($tbl_1.dtime)<=" => $date2,
                    // "produk_id" => "46768",
                );
                // $this->db->where($condites);
                if (my_cabang_id() != CB_ID_PUSAT) {
                    // $this->db->where("cabang_id", my_cabang_id());
                }
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = array();
                $src_00 = $this->call_rek_pembantu_subbiaya($req_thn, $req_bln);

                showLast_query("kuning");
                // matiHere(__LINE__);
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint($src_00);
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                // matiHere(__LINE__);
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    // arrPrintPink($items);
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    $produk_id = $items->extern_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->extern_nama;

                    /* -----------------------------------------------------------
                     * sumbulanan
                     * -----------------------------------------------------------*/
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                // $subjekYgTerlibats = array();
                // arrPrintHijau(array_slice($subjekYgTerlibats,1,1));
                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = $total_penjualan > 0 ? ((1 - ($total_hpp / $total_penjualan)) * 100) : 0;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * locker stok aktive
                 * --------------------------------------------------------------------------------
                 */
                // $this->load->model("Mdls/MdlLockerStock");
                // $ls = new MdlLockerStock();
                // if (my_cabang_id() != CB_ID_PUSAT) {
                //     $this->db->where("cabang_id", my_cabang_id());
                // }
                // $this->db->select(array(
                //     "produk_id",
                //     "nama",
                //     "sum(jumlah) as 'sum_jumlah'"
                // ));
                // $this->db->group_by("produk_id");
                // $src_ls_00 = $ls->callProdukStok();
                // $src_ls = $src_ls_00['datas'];
                // $src_ls_all = $src_ls_00['all'];
                // // showLast_query("orange");
                // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
                // // arrPrintHijau(array_slice($src_ls, 0, 1));
                // $ls_aktivies = array();
                // foreach ($src_ls as $src_l) {
                //     $ls_produk_id = $src_l->produk_id;
                //
                //     $ls_aktivies[$ls_produk_id] = $src_l;
                // }
                // cekOrange(sizeof($ls_aktivies));

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlDtaBiayaUsaha");
                $pr = new MdlDtaBiayaUsaha();
                // $this->load->model("Mdls/MdlDtaBiayaUmum");
                // $pr = new MdlDtaBiayaUmum();
                $this->db->where("allowed_ext", "5");
                $this->db->limit(1);
                $masterHeaders = array(
                    // "kategori_id"   => array(
                    //     "label" => "cid",
                    // ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                        // "links"       => array(
                        //     "heading" => "kategori_nama",
                        //     "key" => "kategori_id",
                        //     "target" => "laporan/Penjualan/cekpenjualanbulanan/produk",
                        // ),
                    ),
                );

                $url_detil['jenis_biaya'] = $xid;
                break;
            case "semua":
                if (my_cabang_id() != CB_ID_PUSAT) {
                    // $this->db->where("cabang_id", my_cabang_id());
                }
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = array();
                $src_00 = array();

                $src_umum = $this->call_rek_pembantu_subbiayaumum_sum($req_thn, $req_bln);
                // showLast_query("merah");
                // arrPrintHijau($src_umum);
                foreach ($src_umum as $items) {
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    $thn_bln = "$thn-$bln";

                    $src_00["umum"][$thn_bln] = $items;
                }

                // arrPrintKuning($src_00);
                // matiHere(__LINE__);
                $src_usaha = $this->call_rek_pembantu_subbiayausaha_sum($req_thn, $req_bln);
                foreach ($src_usaha as $items) {
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    $thn_bln = "$thn-$bln";

                    $src_00["usaha"][$thn_bln] = $items;
                }
                // arrPrintKuning($src_00);
                // matiHere(__LINE__);
                $src_belum = $this->call_rek_pembantu_subbiaya_sum($req_thn, $req_bln);
                foreach ($src_belum as $items) {
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    $thn_bln = "$thn-$bln";

                    $src_00["belum"][$thn_bln] = $items;
                }

                // arrPrintKuning($src_00);
                // showLast_query("kuning");
                // matiHere(__LINE__);

                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;

                foreach ($src_00 as $jenis => $items_0) {
                    foreach ($items_0 as $thn_bln => $items) {


                        // arrPrintPink($items);
                        $thn = $items->thn;
                        $bln = digit_2($items->bln);
                        // $tgl = $items->tgl;
                        // $dtime = $items->dtime;
                        $thn_bln = "$thn-$bln";

                        $sum_qty_kredit = $items->sum_qty_kredit;
                        $sum_qty_debet = $items->sum_qty_debet;
                        $sum_kredit = $items->sum_kredit;
                        $sum_debet = $items->sum_debet;
                        $penjualan_nett = $sum_kredit - $sum_debet;
                        $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                        // $penjualan_nett = $sum_kredit;
                        $sum_hpp = $items->sum_hpp;
                        $produk_id = $jenis;
                        // $produk_id = $oleh_id = $items->seller_id;
                        $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                        $ymd = "$thn-$bln";
                        $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                        // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                        $src_harians[$produk_id][$ymd] = $item_data;
                        $src_margins[$produk_id] = $margin_data;

                        if (!isset($src_qty[$produk_id])) {
                            $src_qty[$produk_id] = 0;
                        }
                        $src_qty[$produk_id] += $sum_qty_kredit;

                        $total_hpp += $sum_hpp;
                        $total_penjualan += $penjualan_nett;
                        $total_qty += $sum_qty_kredit;

                        $subjekYgTerlibats[$jenis]["id"] = $jenis;
                        $subjekYgTerlibats[$jenis]["nama"] = $jenis == "belum" ? "belum ditempatakan" : $jenis;

                        /* -----------------------------------------------------------
                         * sumbulanan
                         * -----------------------------------------------------------*/
                        if (!isset($sumbulanan[$thn_bln])) {
                            $sumbulanan[$thn_bln] = 0;
                        }
                        $sumbulanan[$thn_bln] += $penjualan_nett;
                    }
                }
                // $subjekYgTerlibats = array();
                // arrPrintHijau(array_slice($subjekYgTerlibats,1,1));
                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = $total_penjualan > 0 ? ((1 - ($total_hpp / $total_penjualan)) * 100) : 0;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * locker stok aktive
                 * --------------------------------------------------------------------------------
                 */
                // $this->load->model("Mdls/MdlLockerStock");
                // $ls = new MdlLockerStock();
                // if (my_cabang_id() != CB_ID_PUSAT) {
                //     $this->db->where("cabang_id", my_cabang_id());
                // }
                // $this->db->select(array(
                //     "produk_id",
                //     "nama",
                //     "sum(jumlah) as 'sum_jumlah'"
                // ));
                // $this->db->group_by("produk_id");
                // $src_ls_00 = $ls->callProdukStok();
                // $src_ls = $src_ls_00['datas'];
                // $src_ls_all = $src_ls_00['all'];
                // // showLast_query("orange");
                // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
                // // arrPrintHijau(array_slice($src_ls, 0, 1));
                // $ls_aktivies = array();
                // foreach ($src_ls as $src_l) {
                //     $ls_produk_id = $src_l->produk_id;
                //
                //     $ls_aktivies[$ls_produk_id] = $src_l;
                // }
                // cekOrange(sizeof($ls_aktivies));

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlDtaBiayaUsaha");
                $pr = new MdlDtaBiayaUsaha();
                // $this->load->model("Mdls/MdlDtaBiayaUmum");
                // $pr = new MdlDtaBiayaUmum();
                $this->db->where("allowed_ext", "5");
                $this->db->limit(1);
                $masterHeaders = array(
                    // "kategori_id"   => array(
                    //     "label" => "cid",
                    // ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                        // "links"       => array(
                        //     "heading" => "kategori_nama",
                        //     "key" => "kategori_id",
                        //     "target" => "laporan/Penjualan/cekpenjualanbulanan/produk",
                        // ),
                    ),
                );

                // $url_detil['jenis_biaya'] = $xid;
                $url_bulan = true;
                break;
        }

        // $condites_prod = array();
        // $this->db->where($condites_prod);
        if (ipadd() == "202.65.117.72") {
            if (isset($_GET['limit']) && $_GET['limit'] > 0) {
                $this->db->limit($_GET['limit']);
            }
        }

        $src_produks_0 = $pr->lookupAll()->result();
        // showLast_query("biru");
        // arrPrintHijau($src_produks_0);
        $src_produks = array();
        foreach ($src_produks_0 as $i => $item) {
            $p_id = $item->id;
            $stok_locker["stok_locker"] = isset($ls_aktivies[$p_id]) ? $ls_aktivies[$p_id]->sum_jumlah : 0;

            /*
             * */
            if ($subjek_0 == "kategori") {
                $subj_id = $item->kategori_id;
                $src_produks[$subj_id] = (array)$item + $stok_locker;
            }
            elseif (($subjek_0 == "tipe") || ($subjek_0 == "salesman")) {
                $src_produks = array();
            }
            else {
                $src_produks[$p_id] = (array)$item + $stok_locker;
            }

            $src_produks[$p_id] = (array)$item;
        }
        // cekHijau(count($src_produks));
        // arrPrintHijau($subjekYgTerlibats);
        // arrPrintHijau($src_produks + $subjekYgTerlibats);
        // arrPrintHijau($src_produks);
        // arrPrintHijau(array_slice($src_produks, 0, 1));
        // matiHere(__LINE__);
        $arrHeaders = array();


        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "YTD ";
            $subTitlelap = dtimeNow('d F Y H:i');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD ";
            $subTitlelap = dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        /* --------------------------------------------------------------------------------
         * sub_header
         * --------------------------------------------------------------------------------*/
        foreach ($src_harians as $prid => $src_harian_dts) {
            foreach ($src_harian_dts as $ymd => $src_harian_dt) {

            }
        }
        $subHeaders = array(
            "sum_kredit" => array(
                "label" => "penjualan"
            ),
            "sum_hpp"    => array(
                "label" => "hpp"
            ),

        );

        // arrPrintHijau($src_harians);
        // arrPrintHijau($subjekYgTerlibats);
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "produk_bulanan_per",
            "title"       => "Laporan $title_now  $subjek_0 (Year To Date) <small>$subTitlelap</small>",
            "subTitle"    => $subTitlelap,
            "title_now"   => $title_now,
            "judul_lap"   => $judul_lap,
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "5822",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $src_produks + $subjekYgTerlibats,
            // "master_data" => $subjekYgTerlibats,
            "child_data"  => $src_harians,
            "margin_data" => isset($src_margins) ? $src_margins : array(),
            "margin_avg"  => $margin_avg,
            "qty_data"    => isset($src_qty) ? $src_qty : array(),
            "total_bawah" => $total_bawah,
            "url_detil"   => $url_detil,
            "url_bulan"   => $url_bulan,

            "arrHeaders"    => $arrHeaders,
            "masterHeaders" => $masterHeaders,

            // navigasi
            "url"           => base_url(uri_string()) . "$strGet",
            "aktivitas"     => $aktivitas,
            "subjek"        => $subjek_0,
            "strGet"        => $strGet,
            "date1"         => $date1,
            "date2"         => $date2,
            "date_min"      => 1,
            "date_max"      => dtimeNow('Y-m-d'),
            "loader_div"    => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("biaya_periode", $data);
    }

    public function viewbulananper()
    {
        $_GET['xid'] = $subjek_0 = $xid = isset($_GET['xid']) ? $_GET['xid'] : "umum";
        $this->load->helper("he_mass_table");
        // arrPrintKuning(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas_0 = url_segment(5);
        $aktivitas = isset($aktivitas_0) ? "/$aktivitas_0" : "";

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $year_now = dtimeNow('Y');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        // $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousYear($date2) . "-01-01";
        $date_stop_sebelumnya = date("Y-12-31", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("F Y", strtotime($date_start_sebelumnya));
        $str_geters = "";
        foreach (($date_sebelumnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_sebelum = current_url() . $str_geters;
        $ulr_bulan_ini = current_url();
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan setelahnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan setelahnya">
        $date_start_setelahnya = date("Y-01-01", strtotime($date2));
        if (date("Y", strtotime($date_start_setelahnya)) == $year_now) {

            $date_stop_setelahnya = date("Y-m-d", strtotime($date_now));
        }
        else {
            $date_stop_setelahnya = date("Y-12-31", strtotime($date_start_setelahnya));
        }

        $date_setelahnya = array(
            "date1" => $date_start_setelahnya,
            "date2" => $date_stop_setelahnya,
        );
        $nama_bulan_setelah = date("F Y", strtotime($date_start_setelahnya));

        $str_geters = "";
        foreach (($date_setelahnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_setelah = current_url() . $str_geters;
        $nama_bulan_ygtampil = formatTanggal($date2, "Y");
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        $btn_disabled_mtd = "";
        $btn_disabled_ytd = "";
        $btn_disabled_aft = "";
        $btn_active = "";
        if (($date1 == dtimeNow('Y-m-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            $btn_disabled_mtd = "disabled";
            $btn_disabled_aft = "disabled";
            $btn_active = "btn-primary";
        }
        elseif (($date1 == dtimeNow('Y-01-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            $btn_disabled_ytd = "disabled";
            $btn_disabled_aft = "disabled";
        }
        elseif (($date_stop_setelahnya > dtimeNow('Y-m-d'))) {
            $btn_disabled_aft = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "";
        $btn_td .= "<div class='btn-group'>";
        // $btn_td .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i></button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-primary' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\"><i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "</div>";
        // $btn_td .= " <button type='button' class='btn $btn_active btn-success' $btn_disabled_mtd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";

        // $this->load->model("Mdls/MdlSupplier");
        // $cu = new MdlSupplier();
        // $this->db->order_by("nama", "asc");
        // $srcCus = $cu->callSpecs();
        $biayajenis['umum'] = (object)array("id" => "umum", "nama" => "umum");
        $biayajenis['usaha'] = (object)array("id" => "usaha", "nama" => "usaha");
        $biayajenis['belumditempatkan'] = (object)array("id" => "belumditempatkan", "nama" => "belum ditempatkan");
        $biayajenis['semua'] = (object)array("id" => "semua", "nama" => "semua");
        $srcCus = $biayajenis;
        // showLast_query("kuning");
        // arrPrintHijau($srcCus);
        $segmenAsli = $segmen = url_segment();
        $segmenUrlAsli = implode("/", $segmenAsli);

        $gets = $_GET;
        $gets[1] = 1;
        $getCuId = isset($gets['xid']) ? $gets['xid'] : "";
        unset($gets['xid']);
        $xtraHref = http_build_query($gets);

        $link_self = base_url() . $segmenUrlAsli . "?$xtraHref";
        cekLime($link_self);
        $select_td = "Pilih Jenis Biaya <select data-style='btn btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker' onchange=\"location.href='$link_self&xid=' + this.value\">";
        $select_td .= "<option value=''>---pilih jenis biaya----</option>";
        foreach ($srcCus as $cuid => $srcCus) {
            $cunama = $srcCus->nama;
            $tlp_1 = $srcCus->tlp_1;
            $tlp_f = strlen(($tlp_1)) > 3 ? "($tlp_1)" : "";
            $selected = $cuid == $getCuId ? "selected" : "";
            $select_td .= "<option value='$cuid' $selected>$cunama $tlp_f</option>";
        }
        $select_td .= "</select>";
        $select_td .= "<script>
            $('.selectpicker').selectpicker();
        </script>";


        // $add_td = "<td>$btn_td $select_td</td>";
        $btn_td .= "<td>$select_td</td>";
        // -----------------------------------------------------------------------------------------------
        // cekHere(current_url());
        // cekHere($aktivitas_0);
        if (isset($aktivitas_0) && !empty($aktivitas_0)) {
            $btn_disabled_so = "disabled";
            $btn_disabled_pl = "";

            $title_now = "Biaya";
        }
        else {
            $btn_disabled_so = "";
            $btn_disabled_pl = "disabled";

            // $title_now = "Packing list";
            $title_now = "Biaya";
        }

        // $ulr_tahun_ini = base_url() . "laporan/Penjualan/viewpenjualanbulananper$subjek/so?gr=" . $_GET['gr'];
        // $btn_td .= " <button type='button' $btn_disabled_so class='btn $btn_active btn-primary text-uppercase' onclick=\"location . href = '$ulr_tahun_ini'\">Sales Order</button>";
        // $ulr_tahun_ini = base_url() . "laporan/Penjualan/viewpenjualanbulananper$subjek?gr=" . $_GET['gr'];
        // $btn_td .= " <button type='button' $btn_disabled_pl class='btn $btn_active btn-primary text-uppercase' onclick=\"location . href = '$ulr_tahun_ini'\">packing list</button>";


        $add_td = "<td>$btn_td</td>";
        // $add_td = "";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1'])) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $masterData = array();
        $arrHeaders = array();
        // https://everest.mayagrahakencana.com/laporan/RealisasiDiskon/cekbulanan/supplier/1?1=1&date1=2024-01-01&date2=2024-09-12
        $url = base_url() . "laporan/" . get_class($this) . "$subjek$aktivitas" . "$strGet";
        cekHere("$url");
        // matiHere(__LINE__);
        //callMenuLabel_he_menu()
        $data = array(
            "mode"        => "indek",
            "title"       => "Laporan $title_now Bulanan",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            "sum_satu"    => $url,
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanbulanan$subjek$aktivitas" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",

        );
        $this->load->view("realisasi_diskon_periode", $data);
    }

    public function viewDetil()
    {
        // arrPrintHijau(url_segment());
        // arrPrintHijau($_GET);
        $subjek_0 = $_GET['jenis_biaya'];
        $date1 = $_GET['bln'] . "-01";
        $produk_id = $_GET['produk_id'];
        // cekHere($date1);
        switch ($subjek_0) {
            case "umum":
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = formatTanggal($date1, 'm');
                $tbl_1 = "__rek_pembantu_subbiayaumum__6030";
                $req_blns = array("month($tbl_1.dtime)" => $req_bln,
                                  "extern_id"           => $produk_id);
                $src_00 = $this->call_rek_pembantu_subbiayaumum_detil($req_thn, $req_blns);

                $subjek_0 = $src_00[0]->extern_nama;

                break;
            case "usaha":
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = formatTanggal($date1, 'm');
                $tbl_1 = "__rek_pembantu_subbiayausaha__6010";
                $req_blns = array("month($tbl_1.dtime)" => $req_bln,
                                  "extern_id"           => $produk_id);
                $src_00 = $this->call_rek_pembantu_subbiayausaha_detil($req_thn, $req_blns);

                $subjek_0 = $src_00[0]->extern_nama;

                break;
            case "belumditempatkan":
                $req_thn = formatTanggal($date1, 'Y');
                $req_bln = formatTanggal($date1, 'm');
                $tbl_1 = "__rek_pembantu_subbiaya__6100010";
                $req_blns = array("month($tbl_1.dtime)" => $req_bln,
                                  "extern_id"           => $produk_id);
                $src_00 = $this->call_rek_pembantu_subbiaya_detil($req_thn, $req_blns);

                $subjek_0 = $src_00[0]->extern_nama;

                break;


            //        langsung_detail

        }
        foreach ($src_00 as $items) {
            $master_data[] = (array)$items;
        }
        // arrPrintKuning($master_data);
        $arrHeaders = array(
            "dtime"       => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
            ),
            "nomer"       => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "oleh_nama"   => array(
                "label" => "pic"
            ),
            "cabang_nama" => array(
                "label" => "cabang"
            ),
            "debet"       => array(
                "label"   => "nilai biaya",
                "format"  => "formatField_he_format",
                "summary" => true,
            ),
        );

        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Detile $title_now  $subjek_0 <small>$subTitlelap</small>",
            "subTitle"    => $subTitlelap,
            "title_now"   => $title_now,
            "judul_lap"   => $judul_lap,
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "5822",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $src_produks + $subjekYgTerlibats,
            "master_data" => $master_data,
            // "child_data"  => $src_harians,
            // "margin_data" => isset($src_margins) ? $src_margins : array(),
            // "margin_avg"  => $margin_avg,
            // "qty_data"    => isset($src_qty) ? $src_qty : array(),
            // "total_bawah" => $total_bawah,
            // "url_detil" => $url_detil,

            "arrHeaders" => $arrHeaders,
            // "masterHeaders" => $masterHeaders,

            // navigasi
            // "url"           => base_url(uri_string()) . "$strGet",
            // "aktivitas"     => $aktivitas,
            // "subjek"        => $subjek_0,
            // "strGet"        => $strGet,
            // "date1"         => $date1,
            // "date2"         => $date2,
            // "date_min"      => 1,
            // "date_max"      => dtimeNow('Y-m-d'),
            // "loader_div"    => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("biaya_periode", $data);
    }

    public function viewDetilBl()
    {
        if(ipadd() != "202.65.117.72"){
            $img = img_maintenace();
            echo "<img src='$img'>";
            die();
        }
        // arrPrintHijau(url_segment());
        // arrPrintHijau($_GET);
        $subjek_0 = $_GET['jenis_biaya'];
        $req_thn = $th = $_GET['th'];
        $req_bln = $_GET['bl'];
        $date1 = "$th-".digit_2($req_bln) . "-01";
        // cekHere($date1);
        // matiHere(__LINE__);

        // $req_bln = array();
        $src_00 = array();

        $src_umum = $this->call_rek_pembantu_subbiayaumum_detil($req_thn, $req_bln);
        // showLast_query("merah");
        // arrPrintHijau($src_umum);
        foreach ($src_umum as $items) {
            $thn = $items->thn;
            $bln = digit_2($items->bln);
            $thn_bln = "$thn-$bln";

            $src_00["umum"][] = $items;
        }

        // arrPrintKuning($src_00);
        // matiHere(__LINE__);
        $src_usaha = $this->call_rek_pembantu_subbiayausaha_detil($req_thn, $req_bln);
        foreach ($src_usaha as $items) {
            $thn = $items->thn;
            $bln = digit_2($items->bln);
            $thn_bln = "$thn-$bln";

            $src_00["usaha"][] = $items;
        }
        // arrPrintKuning($src_00);
        // matiHere(__LINE__);
        $src_belum = $this->call_rek_pembantu_subbiaya_detil($req_thn, $req_bln);
        foreach ($src_belum as $items) {
            $thn = $items->thn;
            $bln = digit_2($items->bln);
            $thn_bln = "$thn-$bln";

            $src_00["belum"][] = $items;
        }

        $master_data = $src_00;
        $titles["umum"] = "Biaya Umum";
        $titles["usaha"] = "Biaya Usaha";
        $titles["belum"] = "Biaya Belum Ditempatkan";
        // foreach ($src_00 as $items) {
        //     $master_data[] = (array)$items;
        // }
        // arrPrintKuning($master_data);
        $arrHeaders = array(
            "dtime"       => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
            ),
            "nomer"       => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "oleh_nama"   => array(
                "label" => "pic"
            ),
            "cabang_nama" => array(
                "label" => "cabang"
            ),
            "debet"       => array(
                "label"   => "nilai biaya",
                "format"  => "formatField_he_format",
                "summary" => true,
            ),
        );

        // matiHere(__LINE__);
        $data = array(
            "mode"        => "langsung_detail_bl",
            "title"       => "Detile $title_now  $subjek_0 <small>$subTitlelap</small>",
            "titles" => $titles,
            "subTitle"    => $subTitlelap,
            "title_now"   => $title_now,
            "judul_lap"   => $judul_lap,
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "5822",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $src_produks + $subjekYgTerlibats,
            "master_data" => $master_data,
            // "child_data"  => $src_harians,
            // "margin_data" => isset($src_margins) ? $src_margins : array(),
            // "margin_avg"  => $margin_avg,
            // "qty_data"    => isset($src_qty) ? $src_qty : array(),
            // "total_bawah" => $total_bawah,
            // "url_detil" => $url_detil,

            "arrHeaders" => $arrHeaders,
            // "masterHeaders" => $masterHeaders,

            // navigasi
            // "url"           => base_url(uri_string()) . "$strGet",
            // "aktivitas"     => $aktivitas,
            // "subjek"        => $subjek_0,
            // "strGet"        => $strGet,
            // "date1"         => $date1,
            // "date2"         => $date2,
            // "date_min"      => 1,
            // "date_max"      => dtimeNow('Y-m-d'),
            // "loader_div"    => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("biaya_periode", $data);
    }

    // ---sub
    public function call_rek_pembantu_subbiayaumum($req_thn, $req_bln = "")
    {
        $tbl = $this->tbl_umum;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "extern_id",
            "extern_nama",
            "extern2_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->group_by("year(dtime), month(dtime), extern_id");
        $this->db->order_by("extern_id,dtime", "asc");
        $src_datas = $this->db->get($tbl)->result();

        // arrPrintHijau($src_datas);

        // foreach ($src_datas as $src_data) {
        //     $extern_nama = $src_data->extern_nama;
        //     $dtime = $src_data->dtime;
        //     $bln = formatTanggal($dtime, "m");
        //     $datanya['debet'] = $src_data->debet;
        //     $datanya['kredit'] = $src_data->kredit;
        //
        //     $datas[$extern_nama][$bln][] = $datanya;
        // }

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiayaumum_sum($req_thn, $req_bln = "")
    {
        $tbl = $this->tbl_umum;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "extern_id",
            "extern_nama",
            "extern2_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->group_by("year(dtime), month(dtime)");
        $this->db->order_by("extern_id,dtime", "asc");
        $src_datas = $this->db->get($tbl)->result();

        // arrPrintHijau($src_datas);

        // foreach ($src_datas as $src_data) {
        //     $extern_nama = $src_data->extern_nama;
        //     $dtime = $src_data->dtime;
        //     $bln = formatTanggal($dtime, "m");
        //     $datanya['debet'] = $src_data->debet;
        //     $datanya['kredit'] = $src_data->kredit;
        //
        //     $datas[$extern_nama][$bln][] = $datanya;
        // }

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiayausaha($req_thn, $req_bln = "")
    {
        $tbl = $this->tbl_usaha;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "extern_id",
            "extern_nama",
            "extern2_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->group_by("year(dtime), month(dtime), extern_nama");
        $this->db->order_by("extern_id,dtime", "asc");
        $src_datas = $this->db->get($tbl)->result();

        // arrPrintHijau($src_datas);

        // foreach ($src_datas as $src_data) {
        //     $extern_nama = $src_data->extern_nama;
        //     $dtime = $src_data->dtime;
        //     $bln = formatTanggal($dtime, "m");
        //     $datanya['debet'] = $src_data->debet;
        //     $datanya['kredit'] = $src_data->kredit;
        //
        //     $datas[$extern_nama][$bln][] = $datanya;
        // }

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiayausaha_sum($req_thn, $req_bln = "")
    {
        $tbl = $this->tbl_usaha;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "extern_id",
            "extern_nama",
            "extern2_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->group_by("year(dtime), month(dtime)");
        $this->db->order_by("extern_id,dtime", "asc");
        $src_datas = $this->db->get($tbl)->result();

        // arrPrintHijau($src_datas);

        // foreach ($src_datas as $src_data) {
        //     $extern_nama = $src_data->extern_nama;
        //     $dtime = $src_data->dtime;
        //     $bln = formatTanggal($dtime, "m");
        //     $datanya['debet'] = $src_data->debet;
        //     $datanya['kredit'] = $src_data->kredit;
        //
        //     $datas[$extern_nama][$bln][] = $datanya;
        // }

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiaya($req_thn, $req_bln = "")
    {
        $tbl = $this->tbl_belumditempatkan;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "extern_id",
            "extern_nama",
            "extern2_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->group_by("year(dtime), month(dtime), extern_nama");
        $this->db->order_by("extern_id,dtime", "asc");
        $src_datas = $this->db->get($tbl)->result();

        // arrPrintHijau($src_datas);

        // foreach ($src_datas as $src_data) {
        //     $extern_nama = $src_data->extern_nama;
        //     $dtime = $src_data->dtime;
        //     $bln = formatTanggal($dtime, "m");
        //     $datanya['debet'] = $src_data->debet;
        //     $datanya['kredit'] = $src_data->kredit;
        //
        //     $datas[$extern_nama][$bln][] = $datanya;
        // }

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiaya_sum($req_thn, $req_bln = "")
    {
        $tbl = $this->tbl_belumditempatkan;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "extern_id",
            "extern_nama",
            "extern2_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->group_by("year(dtime), month(dtime)");
        $this->db->order_by("extern_id,dtime", "asc");
        $src_datas = $this->db->get($tbl)->result();

        // arrPrintHijau($src_datas);

        // foreach ($src_datas as $src_data) {
        //     $extern_nama = $src_data->extern_nama;
        //     $dtime = $src_data->dtime;
        //     $bln = formatTanggal($dtime, "m");
        //     $datanya['debet'] = $src_data->debet;
        //     $datanya['kredit'] = $src_data->kredit;
        //
        //     $datas[$extern_nama][$bln][] = $datanya;
        // }

        return $src_datas;
        // return $datas;
    }

    // ---detil
    public function call_rek_pembantu_subbiayaumum_detil($req_thn, $req_bln = "")
    {
        $tbl_1 = $this->tbl_umum;
        $tbl_2 = $this->tbl_2;

        // cekHijau($req_bln);
        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "month($tbl_1.dtime)" => $req_bln,
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year($tbl_2.dtime)" => $req_thn,
                // "month(dtime)" => $req_bln,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "$tbl_2.*",
            "$tbl_1.extern_id",
            "$tbl_1.extern_nama",
            "$tbl_1.extern2_nama",
            "$tbl_1.debet",
            "$tbl_1.kredit",

            "month($tbl_1.dtime) as 'bln'",
            "year($tbl_1.dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->from("$tbl_1");
        $this->db->join("$tbl_2", "$tbl_1.transaksi_id = $tbl_2.id");

        $src_datas = $this->db->get()->result();

        // arrPrintHijau($src_datas);

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiayausaha_detil($req_thn, $req_bln = "")
    {
        $tbl_1 = $this->tbl_usaha;
        $tbl_2 = $this->tbl_2;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "month($tbl_1.dtime)" => $req_bln,
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year($tbl_2.dtime)" => $req_thn,
                // "month(dtime)" => $req_bln,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "$tbl_2.*",
            "$tbl_1.extern_id",
            "$tbl_1.extern_nama",
            "$tbl_1.extern2_nama",
            "$tbl_1.debet",
            "$tbl_1.kredit",

            "month($tbl_1.dtime) as 'bln'",
            "year($tbl_1.dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->from("$tbl_1");
        $this->db->join("$tbl_2", "$tbl_1.transaksi_id = $tbl_2.id");

        $src_datas = $this->db->get()->result();

        // arrPrintHijau($src_datas);

        return $src_datas;
        // return $datas;
    }

    public function call_rek_pembantu_subbiaya_detil($req_thn, $req_bln = "")
    {
        $tbl_1 = $this->tbl_belumditempatkan;
        $tbl_2 = $this->tbl_2;

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "month($tbl_1.dtime)" => $req_bln,
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year($tbl_2.dtime)" => $req_thn,
                // "month(dtime)" => $req_bln,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "$tbl_2.*",
            "$tbl_1.extern_id",
            "$tbl_1.extern_nama",
            "$tbl_1.extern2_nama",
            "$tbl_1.debet",
            "$tbl_1.kredit",

            "month($tbl_1.dtime) as 'bln'",
            "year($tbl_1.dtime) as 'thn'",
        );
        $this->db->select($koloms);
        $this->db->from("$tbl_1");
        $this->db->join("$tbl_2", "$tbl_1.transaksi_id = $tbl_2.id");

        $src_datas = $this->db->get()->result();

        // arrPrintHijau($src_datas);

        return $src_datas;
        // return $datas;
    }

    // -----------------------------------------------------------------
    public function call_rek_pembantu_subpiutangsupplier_cache($req_thn, $req_bln = "")
    {
        $tbl = "_rek_pembantu_subpiutangsupplier_cache";

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                "periode" => "bulanan",
                "thn"     => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        $this->db->group_by("extern_nama,thn,bln");
        $koloms = array(
            "thn",
            "bln",
            "extern_nama",
            "sum(debet) as 'sum_debet'",
            "sum(kredit) as 'sum_kredit'",
        );
        $this->db->select($koloms);
        $src_datas = $this->db->get($tbl)->result();
        arrPrint($src_datas);

        foreach ($src_datas as $src_data) {
            $extern_nama = $src_data->extern_nama;
            $bln = $src_data->bln;
            $datanya['debet'] = $src_data->debet;

            $datas[$extern_nama][$bln][] = $datanya;
        }

        return $datas;
    }

    public function call_rek_saldo_awal($req_thn, $req_bln = "")
    {
        $tbl = "__rek_pembantu_subpiutangsupplier__1010020030";

        $reg_bln = array();
        if ($req_bln != "" && !is_array($req_bln)) {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        else {
            $reg_bln = $req_bln;
        }
        $condites = array(
                // "periode" => "bulanan",
                "year(dtime)" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        // $rekenings = array(
        //     "588", "582spo", "382spo"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $koloms = array(
            "id",
            "extern_id",
            "extern_nama",
            "extern2_nama",
            // "sum(debet) as 'sum_debet'",
            // "sum(kredit) as 'sum_kredit'",
            "dtime",
            "month(dtime) as 'bln'",
            "year(dtime) as 'thn'",
            "debet_awal",
            "debet",
            "kredit",
            "debet_akhir",
        );
        $this->db->select($koloms);
        // $this->db->group_by("year(dtime), month(dtime), extern_nama");
        // $this->db->order_by("dtime", "asc");
        $this->db->order_by("id", "asc");
        $src_datas = $this->db->get($tbl)->result();
        // showLast_query("kuning");
        //         arrPrintHijau($src_datas);

        foreach ($src_datas as $items) {
            $subjek_nama = $items->extern2_nama;
            $thn = $items->thn;
            $bln = digit_2($items->bln);
            // $tgl = $items->tgl;
            // $dtime = $items->dtime;
            $ymd = $thn_bln = "$thn-$bln";

            $produk_id = $items->extern_id;
            $item_data["saldo_awal"][] = $items->debet_awal . " " . $items->id;
            $item_data["saldo_akhir"][] = $items->debet_akhir;

            $src_harians[$produk_id][$ymd]["saldo_awal"][] = $items->debet_awal;

            $diskons[$produk_id] = $items->extern_nama;
        }

        // arrPrintKuning($src_harians);
        for ($i = 1; $i <= 12; $i++) {
            $bl = digit_2($i);
            $keyTh = "$req_thn-$bl";

            $saldoAwalNilai = 0;
            foreach ($diskons as $diskon_id => $diskon_nama) {
                if (isset($src_harians[$diskon_id][$keyTh]['saldo_awal'])) {
                    $saldoAwalNilai = $src_harians[$diskon_id][$keyTh]['saldo_awal'][0];
                    // break;
                }
                $cekSaldoAwal[$diskon_id][$keyTh]['saldo_awal'] = $saldoAwalNilai;
            }

        }

        // arrPrintPink($cekSaldoAwal);

        return $cekSaldoAwal;
    }
}