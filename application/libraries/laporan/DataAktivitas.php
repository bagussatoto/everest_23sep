<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DataAktivitas
{
    protected $seller_id;

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;
    }

    protected $master_id;

    public function setMasterId($master_id)
    {
        $this->master_id = $master_id;
    }

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper("he_mass_table");
        //
        // $this->modul_path = base_url() . "penjualan/";
        // $this->jenisTr = "4666";
        // $this->jenisTrs = array("582spo", "382spo");
        // $this->default_limit = 100;

        // mati_disini(__LINE__ . __FILE__ . __DIR__);
    }

    /* ---------------------
     * tahunan
     ---------------------*/
    public function getSaldoSellerTahun($get_date1, $get_date2)
    {
        $seller_id = isset($this->seller_id) ? array("seller_id" => $this->seller_id) : array();
        $master_id = isset($this->master_id) ? array("master_id" => $this->master_id) : array();
        $transaksi_id = isset($this->master_id) ? array("id" => $this->master_id) : array();
        $req_tahun = formatTanggal($get_date1, "Y");
        $req_bulan_ini = formatTanggal($get_date1, "m");

        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();
$kolom_values = array(
    /*--reguler*/
    "582spo" => "saldo_order",
    "582so" => "saldo_order",
    "582spd" => "saldo_kirim",
    // // "982",
    // /*--project*/
    "588spo" => "saldo_order",
    "588so" => "saldo_order",
    "7499" => "saldo_kirim",
    // /*--eksport*/
    "382so" => "saldo_order",
    "382spd" => "saldo_order",
    "9912" => "saldo_kirim",
);

        $koloms = array(
            "debet",
            "kredit",
            "saldo_debet",
            "saldo_kredit",
            "saldo_qty_debet",
            "saldo_qty_kredit",
            "saldo_reject",
            "saldo_closed",
            "saldo_qty_reject",
            "saldo_qty_closed",
            "saldo_edit",
            "saldo_qty_edit",
            "saldo_order",
            "saldo_kirim",
            "saldo_qty_order",
            "saldo_qty_kirim",
        );
        $condite_rekening = array(
            /*--reguler*/
            // "582spo",
            "582so",
            // "582spd",
            // "582pkd",
            // // "982",
            // /*--project*/
            // "588spo",
            // "588so",
            // "7499",
            // /*--eksport*/
            // "382so",
            // "382spd",
            // // "9912",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        $this->ci->db->where_in("rekening", $condite_rekening);
        $condites = array(
            // "month(dtime)" => $req_bulan_ini,
            "year(dtime)"  => $req_tahun,
            // "date(dtime)<=" => $get_date2,
            // "seller_id"    => "65",
            // "seller_id"    => "69",
            // "seller_id"     => "663",
            // "master_id"     => "130358",
            "periode"      => "bulanan",
        );
        $condites_main = array(
                // "qty_debet>" => 0,
                // "master_id" => "100788",
            ) + $seller_id + $master_id;
        $this->ci->db->where($condites + $condites_main);
        $this->ci->db->order_by("id", "asc");
        $tbl = "z_sales_salesman_cache";
        // $srcs_0 = $ps->fetchMovement(true);
        $srcs_0 = $this->ci->db->get($tbl)->result();
        showLast_query("kuning");
        cekMerah(sizeof($srcs_0));
        // arrPrintPink($condites);
        // arrPrintPink($srcs_0);
        $src_bln_now = array();
        $src_mtd = array();
        foreach ($srcs_0 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            $rekening_db = $item->rekening;
            $seller_id = $item->seller_id;
            // $transaksi_tipe_db = isset($item->transaksi_tipe) ? $item->transaksi_tipe : "";
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;

            // $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
            // $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
            // $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
            // $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            foreach ($condite_rekening as $transaksi_rekening) {
                // $kolom_baru["qty_debet_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $qty_debet : 0;
                // $kolom_baru["debet_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $debet : 0;
                // $kolom_baru["qty_kredit_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $qty_kredit : 0;
                // $kolom_baru["kredit_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $kredit : 0;

            }

            // $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
            // $src_mtd[] = (array)$item + $kolom_baru;
            $src_bln_now[$yearBln][] = (array)$item;
            $src_mtd[] = (array)$item;
        }
        // arrPrintKuning($srcs_0);
        // arrPrintKuning($src_bln_now);
        // arrPrintKuning($src_mtd);
        // mati_disini(__LINE__);



        /* --------------------------------------------------------------
         * ngecek jml data per bulan
         * --------------------------------------------------------------*/
        $this->ci->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr_field = $tr->getFields();
        $this->ci->db->select($tr_field['main']);
        $condite_tr = array(
            "date(dtime)>=" => "2021-01-01",
            "date(dtime)<=" => dtimeNow('Y-m-d'),
        );
        $this->ci->db->where($condite_tr);
        $src_tr = $tr->lookupAll()->result();
        $tr_datas = array();
        foreach ($src_tr as $item) {
            $tr_datas[$item->id] = (array)$item;
        }

        $src_ytd_pluss = array();
        // foreach ($src_ytd as $item) {
        //     // $item_trs = isset($tr_datas[$item['master_id']]) ? $tr_datas[$item['master_id']] : array();
        //     $item_trs = isset($tr_datas[$item['transaksi_id']]) ? $tr_datas[$item['transaksi_id']] : array();
        //     $src_ytd_pluss[] = $item + $item_trs;
        // }
        // showLast_query("merah");
        // matiHere(__LINE__);

        // cekHijau(sizeof($src_002));
        // foreach ($src_bln_yang_lalu as $ybl => $item) {
        //     cekBiru("$ybl " . sizeof($item));
        // }
        // arrPrintKuning($src_ytd);
        $datas = array();
        $datas['mtd'] = $src_mtd;
        // $datas['ytd'] = $src_ytd;
        // $datas['ytd_pluss'] = $src_ytd_pluss;
        // $datas['ytd_previous'] = $src_yang_lalu;
        // $datas['bulanan_previous'] = $src_bln_yang_lalu;
        $datas['rekening'] = $condite_rekening;
        $datas['transaksi_tipe'] = $transaksi_tipes;
        $datas['transaksi'] = $tr_datas;
        $datas['kolom'] = $koloms;

        return $datas;

    }
    /*--layer ke 2--*/
    public function callPerSellerTahun($date1, $date2){

        $src_00 = $this->getSaldoSellerTahun($date1, $date2);
        $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_00);
        // matiHere(__LINE__ . __FILE__);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $sumSubjek = array();
        $arrSubjek = array();
        foreach ($src_mtd as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];
            $bln = $item['bln'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
            // //
            // // // ---------------------------------------------------------
            // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // // }
            // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;


            //---------------------------------------------------------
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id][$bln] = $item;
        }

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        // foreach ($src_yang_lalu as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['seller_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     foreach ($src_koloms as $src_kolom) {
        //         if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
        //             $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
        //         }
        //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
        //     //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
        //     // }
        //     // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
        //     //
        //     // // ---------------------------------------------------------
        //     // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
        //     //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
        //     // }
        //     // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;
        //
        //
        //     //---------------------------------------------------------
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        // $qty_kirim = 0;
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     $rekening = $sbjDatas['rekening'];
        //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
        //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
        //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
        //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_reject_7499 = isset($sbjDatas['now_saldo_reject_7499']) ? $sbjDatas['now_saldo_reject_7499'] : "0";
        //     $now_saldo_reject_382spd = isset($sbjDatas['now_saldo_reject_382spd']) ? $sbjDatas['now_saldo_reject_382spd'] : "0";
        //     $now_saldo_reject_all = $now_saldo_reject_582spd + $now_saldo_reject_7499 + $now_saldo_reject_382spd;
        //
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //     $now_saldo_closed_7499 = isset($sbjDatas['now_saldo_closed_7499']) ? $sbjDatas['now_saldo_closed_7499'] : "0";
        //     $now_saldo_closed_382spd = isset($sbjDatas['now_saldo_closed_382spd']) ? $sbjDatas['now_saldo_closed_382spd'] : "0";
        //     $now_saldo_closed_all = $now_saldo_closed_582spd + $now_saldo_closed_7499 + $now_saldo_closed_382spd;
        //
        //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
        //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
        //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
        //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
        //
        //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
        //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
        //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
        //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //
        //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
        //
        //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
        //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
        //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
        //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
        //     $now_saldo_order_netto_all = $now_saldo_order_all - $now_saldo_reject_all - $now_saldo_closed_all;
        //     // $now_saldo_order_netto_all = $now_saldo_order_all;
        //
        //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
        //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_reject_all"] = $now_saldo_reject_all;
        //     $sumSubjek[$sbj_id]["now_saldo_closed_all"] = $now_saldo_closed_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_netto_all"] = $now_saldo_order_netto_all;
        //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
        //     if ($now_saldo_order_582so > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
        //     }
        //     if ($now_saldo_order_all > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $now_kredit_all;
        //     }
        //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
        //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
        //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
        //     $last_kredit_582spd = 0;
        //     if ($now_kredit_582spd > 0) {
        //         $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
        //     }
        //     else {
        //         //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
        //         // cekHijau("$now_saldo_order_582so");
        //         $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
        //     }
        //     $last_kredit_7499 = $prev_kredit_7499 + $now_kredit_7499;
        //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_allspd"] = $last_kredit_7499 + $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_all"] = $prev_kredit_all + $now_saldo_order_all - $now_saldo_closed_582spd - $now_saldo_reject_582spd - $now_saldo_kirim_all;
        // }
        //
        // // arrPrintWebs($sumSubjekSeller2);
        // // arrPrintWebs($sumSubjekSeller);
        // // arrPrintPink($sumSubjek);
        // // test_table($sumSubjek);
        // // arrPrintPink($sumSubjek);
        // // arrPrintHijau($arrSubjek);
        // //   matiHere(__LINE__);
        // // /* --------------------------------------------------------------------------------------------------
        // //   * #3 pengumpulan data menjadi data siap tempur
        // //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     $sumParams = $sumSubjek[$subj_id];
        //
        //
        //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
        //     $hasilOlahan_1[] = $itemParam + $sumParams;
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $arrSeller;

        return $masterData;
    }

    /*---z_sales_salesman_transaksi_cache---*/
    public function getSaldoSellerTransaksiTahun($get_date1, $get_date2)
    {
        $seller_id = isset($this->seller_id) ? array("seller_id" => $this->seller_id) : array();
        $master_id = isset($this->master_id) ? array("master_id" => $this->master_id) : array();
        $transaksi_id = isset($this->master_id) ? array("id" => $this->master_id) : array();
        $req_tahun = formatTanggal($get_date1, "Y");
        $req_bulan_ini = formatTanggal($get_date1, "m");

        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $koloms = array(
            // "dtime",
            "debet",
            "kredit",
            "saldo_debet",
            "saldo_kredit",
            "saldo_qty_debet",
            "saldo_qty_kredit",
            "saldo_reject",
            "saldo_closed",
            "saldo_qty_reject",
            "saldo_qty_closed",
            "saldo_edit",
            "saldo_qty_edit",
            "saldo_order",
            "saldo_kirim",
            "saldo_qty_order",
            "saldo_qty_kirim",
        );
        $condite_rekening = array(
            /*--reguler*/
            "582so",
            "582spd",
            // "982",
            /*--project*/
            // "5888spo",
            "588so",
            "7499",
            /*--eksport*/
            "382so",
            "382spd",
            // "9912",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        $this->ci->db->where_in("rekening", $condite_rekening);
        $condites = array(
            // "month(dtime)" => $req_bulan_ini,
            "year(dtime)"  => $req_tahun,
            // "date(dtime)<=" => $get_date2,
            // "seller_id"    => "65",
            // "seller_id"    => "69",
            // "seller_id"     => "663",
            // "master_id"     => "130358",
            "periode"      => "tahunan",
        );
        $condites_main = array(
                // "qty_debet>" => 0,
                // "master_id" => "100788",
            ) + $seller_id + $master_id;
        $this->ci->db->where($condites + $condites_main);
        $this->ci->db->order_by("id", "asc");
        $tbl = "z_sales_salesman_transaksi_cache";
        // $srcs_0 = $ps->fetchMovement(true);
        $srcs_0 = $this->ci->db->get($tbl)->result();
        // showLast_query("kuning");
        // cekMerah(sizeof($srcs_0));
        // arrPrintPink($condites);
        // arrPrintPink($srcs_0);
        $src_bln_now = array();
        $src_mtd = array();
        foreach ($srcs_0 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            $rekening_db = $item->rekening;
            // $transaksi_tipe_db = isset($item->transaksi_tipe) ? $item->transaksi_tipe : "";
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;

            // $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
            // $src_mtd[] = (array)$item + $kolom_baru;
            $src_bln_now[$yearBln][] = (array)$item;
            $src_mtd[] = (array)$item;
        }
        // arrPrintKuning($srcs_0);
        // arrPrintKuning($src_bln_now);
        // arrPrintKuning($src_mtd);
        // mati_disini(__LINE__);

        /* -------------------------------------------------------
        * data awal tahun sampai akhir bulan lalu
        * -------------------------------------------------------*/
        $awal_tahun = formatTanggal($get_date1, 'Y-01-01');
        $awal_tahun = "2021-01-01";
        $akhir_tahun = formatTanggal($get_date1, 'Y');
        // $akhir_bulan_lalu = formatTanggal(previousMonth($get_date1), 'Y-m-t');
        $akhir_bulan_lalu = previousDate($get_date1);
        // cekMerah("$get_date1 $akhir_bulan_lalu " . previousDate($get_date1));
        $condite_previous = array(
            // "year(dtime)" => $req_tahun,
            "year(dtime)>=" => $awal_tahun,
            "year(dtime)<"  => $akhir_tahun,
            // "seller_id"    => "65",
            // "seller_id"     => "69",
            // "kredit>" => 0,
            "periode"       => "tahunan",
        );
        $condite_previous_main = $condites_main;
        $this->ci->db->where($condite_previous + $condite_previous_main);
        $this->ci->db->order_by("id", "asc");
        $this->ci->db->where_in("rekening", $condite_rekening);
        // $src_002 = $ps->fetchMovement("persediaan_produk");
        $src_002 = $this->ci->db->get($tbl)->result();
        // showLast_query("here");
        // cekHere(sizeof($src_002));
        // arrPrintKuning($condite_previous);

        $kolom_baru = array();
        $src_bln_yang_lalu = array();
        $src_yang_lalu = array();
        foreach ($src_002 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            // $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;
            // foreach ($transaksi_tipes as $transaksi_tipe) {
            //     $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
            //     $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
            //     $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
            //     $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            // }

            // $src_bln_yang_lalu[$yearBln][] = (array)$item + $kolom_baru;
            // $src_yang_lalu[] = (array)$item + $kolom_baru;
            $src_bln_yang_lalu[$yearBln][] = (array)$item;
            $src_yang_lalu[] = (array)$item;
        }

        $src_ytd = array_merge($src_yang_lalu, $src_mtd);
        /* --------------------------------------------------------------
         * ngecek jml data per bulan
         * --------------------------------------------------------------*/
        $this->ci->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr_field = $tr->getFields();
        $this->ci->db->select($tr_field['main']);
        $condite_tr = array(
            "date(dtime)>=" => "2021-01-01",
            "date(dtime)<=" => dtimeNow('Y-m-d'),
            "jenis" => "582spo",
        );
        $this->ci->db->where($condite_tr);
        $src_tr = $tr->lookupAll()->result();
        // showLast_query("hijau");
        $tr_datas = array();
        foreach ($src_tr as $item) {
            $tr_data = addPrefixKeyT_he_format((array)$item);
            $tr_datas[$item->id] = $tr_data;
        }
        // arrPrint($tr_datas);
        $src_ytd_pluss = array();
        foreach ($src_ytd as $item) {
            // $item_trs = isset($tr_datas[$item['master_id']]) ? $tr_datas[$item['master_id']] : array();
            $item_trs = isset($tr_datas[$item['master_id']]) ? $tr_datas[$item['master_id']] : array();
            $src_ytd_pluss[] = $item + $item_trs;
        }
        // showLast_query("merah");
        // matiHere(__LINE__);

        // cekHijau(sizeof($src_002));
        // foreach ($src_bln_yang_lalu as $ybl => $item) {
        //     cekBiru("$ybl " . sizeof($item));
        // }
        // arrPrintKuning($src_ytd);
        $datas = array();
        $datas['mtd'] = $src_mtd;
        $datas['ytd'] = $src_ytd;
        $datas['ytd_pluss'] = $src_ytd_pluss;
        $datas['ytd_previous'] = $src_yang_lalu;
        $datas['bulanan_previous'] = $src_bln_yang_lalu;
        $datas['rekening'] = $condite_rekening;
        $datas['transaksi_tipe'] = $transaksi_tipes;
        $datas['transaksi'] = $tr_datas;
        $datas['kolom'] = $koloms;

        return $datas;

    }

    public function callPerTransaksiTahun($date1, $date2){
        $src_00 = $this->getSaldoSellerTransaksiTahun($date1, $date2);
        //        arrPrintKuning($src_00);
        //        showLast_query("kuning");
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $src_ytd = $src_00['ytd'];
        $src_ytd_pluss = $src_00['ytd_pluss'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_tr = $src_00['transaksi'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_yang_lalu);
        // arrPrintPink($src_ytd);
        // arrPrint($src_ytd_pluss);
        foreach ($src_ytd_pluss as $src_ytd_pluss) {
            $subjek_id = $src_ytd_pluss['master_id'];
            $tr_datas[$subjek_id] = $src_ytd_pluss;
        }
        // matiDisini(__LINE__);
        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $arrSubjek = array();
        $sumSubjek = array();
        $arrDateNow = array();
        foreach ($src_mtd as $item) {

            $subjek_id = $item['master_id'];
            $transaksi_id = $item['transaksi_id'];

            $seller_id = $item['seller_id'];
            $seller_nama = $item['seller_nama'];
            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // cekBiru("$rekening");
            // cekBiru($sumSubjek);

            //---------------------------------------------------------
            // $arrSubjek[$subjek_id]['seller_id'] = $seller_id;
            // $arrSubjek[$subjek_id]['seller_nama'] = $seller_nama;
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
            $arrDatas[$subjek_id] = $item;
            $arrDateNow[$subjek_id]['now_dtime'] = $item['dtime'];
            $arrDateNow[$subjek_id]['now_seller_nama'] = $item['seller_nama'];
        }

        // arrPrintHijau($arrSubjek);
        // arrPrint($sumSubjek);
        // matiHere(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        $rekening_subjek_2 = array(
            "582spd", "7499",
            "382spd"
        );
        $sumSubjek2 = array();
        foreach ($src_yang_lalu as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['master_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $nilai = $item[$src_kolom] * 1;

                // if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                //     $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                // }
                $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;

                $sumSubjek2[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
            }


            //---------------------------------------------------------
            $sumSubjek[$subjek_id]['rekening'] = $rekening;

            // $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            // $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];

            // $arrSubjek[$subjek_id] = $item;
            // if($rekening == "582spd"){
            if (in_array($rekening, $rekening_subjek_2)) {
                $arrSubjek_2[$subjek_id][] = $item;
            }

            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }

        // cekHijau(sizeof($sumSubjek));
        // arrPrintHijau($sumSubjek);
        // arrPrint($arrSubjek_2);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------
         * filter untuk membuang prevous outstandinf yg dibawah nilai 1
         * ----------------------------------------------------------------*/
        // arrPrintKuning($sumSubjek2);
        foreach ($arrSubjek_2 as $mst_id => $item) {
            $arrSubjek_3[$mst_id] = end($item);
        }
        // arrPrint($arrSubjek_3);
        foreach ($arrSubjek_3 as $mast_id => $item) {
            // if ($item['rekening'] == "582spd" && $item["kredit"] >= 1) {
            //     $arrSubjek[$mast_id] = $item;
            // }
            // if ($item['rekening'] == "7499" && $item["kredit"] >= 1) {
            //     $arrSubjek[$mast_id] = $item;
            // }
            if (in_array($item['rekening'], $rekening_subjek_2) && ($item["kredit"] >= 1)) {
                $arrSubjek[$mast_id] = $item;
            }
        }
        // $arrSubjek = $arrSubjek_2;

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        $qty_kirim = 0;
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            $rekening = $sbjDatas['rekening'];
            $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
            $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
            $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
            $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;

            $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
            $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
            $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
            $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;

            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
            $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
            $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;

            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";

            $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";

            $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
            $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
            $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
            $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;

            $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
            $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
            $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
            $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
            /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
            if ($now_saldo_order_582so > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
            }
            if ($now_saldo_order_all > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = $prev_kredit_all - $now_saldo_kirim_all;
            }
            // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
            // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
            $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
            $last_kredit_582spd = 0;
            // if ($now_kredit_582spd > 0) {
            //     $last_kredit_582spd = ($now_kredit_582spd * 1);
            // }
            // else {
            // $last_kredit_582spd = $prev_kredit_582spd - $now_saldo_kirim_582spd_old - 0 + $now_kredit_582spd;
            $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);
            // }

            // if($last_kredit_582spd > 0){

            // cekMerah("$sbj_id || $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);");
            // }
            $last_kredit_7499 = $prev_kredit_all + $now_saldo_order_all - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_all;
            $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
            $sumSubjek[$sbj_id]["last_kredit_all"] = $last_kredit_7499;

        }

        // arrPrintWebs($sumSubjekSeller2);
        // arrPrintWebs($sumSubjekSeller);
        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // arrPrintHijau($arrSubjek);
        // cekBiru($arrDateNow);
        //   matiHere(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            $transParams = $tr_datas[$subj_id];
            $now_date = isset($arrDateNow[$subj_id]) ? $arrDateNow[$subj_id] : array();

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumParams + $transParams + $now_date;
            // $hasilOlahan_1[] = $itemParam;
        }
        // arrPrintKuning($hasilOlahan_1);
        $masterData = $hasilOlahan_1;

        return $masterData;
    }

    /* ---------------------
     * bulanan
     ---------------------*/
    public function getSaldoSellerBulan($get_date1, $get_date2)
    {
        $seller_id = isset($this->seller_id) ? array("seller_id" => $this->seller_id) : array();
        $master_id = isset($this->master_id) ? array("master_id" => $this->master_id) : array();
        $transaksi_id = isset($this->master_id) ? array("id" => $this->master_id) : array();
        $req_tahun = formatTanggal($get_date1, "Y");
        $req_bulan_ini = formatTanggal($get_date1, "m");

        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();


        $koloms = array(
            "debet",
            "kredit",
            "saldo_debet",
            "saldo_kredit",
            "saldo_qty_debet",
            "saldo_qty_kredit",
            "saldo_reject",
            "saldo_closed",
            "saldo_qty_reject",
            "saldo_qty_closed",
            "saldo_edit",
            "saldo_qty_edit",
            "saldo_order",
            "saldo_kirim",
            "saldo_qty_order",
            "saldo_qty_kirim",
        );
        $condite_rekening = array(
            /*--reguler*/
            "582so",
            "582spd",
            // "982",
            /*--project*/
            // "5888spo",
            "588so",
            "7499",
            /*--eksport*/
            "382so",
            "382spd",
            // "9912",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        $this->ci->db->where_in("rekening", $condite_rekening);
        $condites = array(
            // "month(dtime)" => $req_bulan_ini,
            "year(dtime)"  => $req_tahun,
            // "date(dtime)<=" => $get_date2,
            // "seller_id"    => "65",
            // "seller_id"    => "69",
            // "seller_id"     => "663",
            // "master_id"     => "130358",
            "periode"      => "tahunan",
        );
        $condites_main = array(
                // "qty_debet>" => 0,
                // "master_id" => "100788",
            ) + $seller_id + $master_id;
        $this->ci->db->where($condites + $condites_main);
        $this->ci->db->order_by("id", "asc");
        $tbl = "z_sales_salesman_cache";
        // $srcs_0 = $ps->fetchMovement(true);
        $srcs_0 = $this->ci->db->get($tbl)->result();
        // showLast_query("kuning");
        // cekMerah(sizeof($srcs_0));
        // arrPrintPink($condites);
        // arrPrintPink($srcs_0);
        $src_bln_now = array();
        $src_mtd = array();
        foreach ($srcs_0 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            $rekening_db = $item->rekening;
            // $transaksi_tipe_db = isset($item->transaksi_tipe) ? $item->transaksi_tipe : "";
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;

            // $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
            // $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
            // $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
            // $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            foreach ($condite_rekening as $transaksi_rekening) {
                // $kolom_baru["qty_debet_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $qty_debet : 0;
                // $kolom_baru["debet_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $debet : 0;
                // $kolom_baru["qty_kredit_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $qty_kredit : 0;
                // $kolom_baru["kredit_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $kredit : 0;

            }

            // $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
            // $src_mtd[] = (array)$item + $kolom_baru;
            $src_bln_now[$yearBln][] = (array)$item;
            $src_mtd[] = (array)$item;
        }
        // arrPrintKuning($srcs_0);
        // arrPrintKuning($src_bln_now);
        // arrPrintKuning($src_mtd);
        // mati_disini(__LINE__);

        /*nganbil data yg reject*/
        // $condites_reject = array(
        //     // "master_id"      => "130358",
        //     "transaksi_tipe" => "rejected",
        // );
        // $this->ci->db->where($condites + $condites_reject);
        // $this->ci->db->where_in("rekening", $condite_rekening);
        // $this->ci->db->order_by("id", "asc");
        // $srcs_00 = $ps->fetchMovement(true);
        // // showLast_query("pink");
        // // cekPink(sizeof($srcs_00));
        // // arrPrintPink($srcs_00);
        // // $src_bln_now = array();
        // // $src_mtd = array();
        // foreach ($srcs_00 as $item) {
        //     $dtime = $item->dtime;
        //     $year = formatTanggal($dtime, 'Y');
        //     $bulan = formatTanggal($dtime, 'm');
        //     $yearBln = formatTanggal($dtime, 'Y-m');
        //     $rekening_db = $item->rekening;
        //     $transaksi_tipe_db = $item->transaksi_tipe;
        //     $qty_kredit = $item->qty_kredit;
        //     $kredit = $item->kredit;
        //     foreach ($transaksi_tipes as $transaksi_tipe) {
        //         $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
        //         $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
        //         foreach ($condite_rekening as $transaksi_rekening) {
        //             $kolom_baru["qty_kredit_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $qty_debet : 0;
        //             $kolom_baru["kredit_$transaksi_rekening" . "_$transaksi_tipe"] = $rekening_db == $transaksi_rekening ? $debet : 0;
        //         }
        //     }
        //
        //     // $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
        //     // $src_mtd[] = (array)$item + $kolom_baru;
        // }


        /* -------------------------------------------------------
        * data awal tahun sampai akhir bulan lalu
        * -------------------------------------------------------*/
        $awal_tahun = formatTanggal($get_date1, 'Y-01-01');
        $awal_tahun = "2021";
        // $akhir_bulan_lalu = formatTanggal(previousMonth($get_date1), 'Y-m-t');
        $akhir_bulan_lalu = previousDate($get_date1);
        // cekMerah("$get_date1 $akhir_bulan_lalu " . previousDate($get_date1));
        $condite_previous = array(
            // "year(dtime)" => $req_tahun,
            "year(dtime)>=" => $awal_tahun,
            "year(dtime)<"  => $req_tahun,
            // "seller_id"    => "65",
            // "seller_id"     => "69",
            // "seller_id"     => "663",
            "periode"       => "tahunan",
        );
        $condite_previous_main = $condites_main;
        $this->ci->db->where($condite_previous + $condite_previous_main);
        $this->ci->db->order_by("id", "asc");
        $this->ci->db->where_in("rekening", $condite_rekening);
        // $src_002 = $ps->fetchMovement("persediaan_produk");
        $src_002 = $this->ci->db->get($tbl)->result();
        // showLast_query("here");
        // arrPrintKuning($condite_previous);
        // arrPrintKuning(sizeof($src_002));

        $kolom_baru = array();
        $src_bln_yang_lalu = array();
        $src_yang_lalu = array();
        foreach ($src_002 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            // $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;
            // foreach ($transaksi_tipes as $transaksi_tipe) {
            //     $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
            //     $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
            //     $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
            //     $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            // }

            // $src_bln_yang_lalu[$yearBln][] = (array)$item + $kolom_baru;
            // $src_yang_lalu[] = (array)$item + $kolom_baru;
            $src_bln_yang_lalu[$yearBln][] = (array)$item;
            $src_yang_lalu[] = (array)$item;
        }

        /*rejeck yg lalu*/
        // $condite_previous_reject = array(
        //     "transaksi_tipe" => "rejected",
        //     // "seller_id"    => "65",
        // );
        // $this->ci->db->where($condite_previous + $condite_previous_reject);
        // $this->ci->db->order_by("id", "asc");
        // // $this->ci->db->where_in("rekening", $condite_rekening);
        // $src_002 = $ps->fetchMovement("persediaan_produk");
        // // showLast_query("kuning");
        // // arrPrintKuning($condite_previous);
        //
        // $kolom_baru = array();
        // foreach ($src_002 as $item) {
        //     $dtime = $item->dtime;
        //     $year = formatTanggal($dtime, 'Y');
        //     $bulan = formatTanggal($dtime, 'm');
        //     $yearBln = formatTanggal($dtime, 'Y-m');
        //     $transaksi_tipe_db = $item->transaksi_tipe;
        //     $qty_kredit = $item->qty_kredit;
        //     $kredit = $item->kredit;
        //     foreach ($transaksi_tipes as $transaksi_tipe) {
        //         $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
        //         $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
        //     }
        //
        //     // $src_bln_yang_lalu[$yearBln][] = (array)$item + $kolom_baru;
        //     // $src_yang_lalu[] = (array)$item + $kolom_baru;
        // }

        $src_ytd = array_merge($src_yang_lalu, $src_mtd);
        /* --------------------------------------------------------------
         * ngecek jml data per bulan
         * --------------------------------------------------------------*/
        $this->ci->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr_field = $tr->getFields();
        $this->ci->db->select($tr_field['main']);
        $condite_tr = array(
            "date(dtime)>=" => "2021-01-01",
            "date(dtime)<=" => dtimeNow('Y-m-d'),
        );
        $this->ci->db->where($condite_tr);
        $src_tr = $tr->lookupAll()->result();
        $tr_datas = array();
        foreach ($src_tr as $item) {
            $tr_datas[$item->id] = (array)$item;
        }

        $src_ytd_pluss = array();
        foreach ($src_ytd as $item) {
            // $item_trs = isset($tr_datas[$item['master_id']]) ? $tr_datas[$item['master_id']] : array();
            $item_trs = isset($tr_datas[$item['transaksi_id']]) ? $tr_datas[$item['transaksi_id']] : array();
            $src_ytd_pluss[] = $item + $item_trs;
        }
        // showLast_query("merah");
        // matiHere(__LINE__);

        // cekHijau(sizeof($src_002));
        // foreach ($src_bln_yang_lalu as $ybl => $item) {
        //     cekBiru("$ybl " . sizeof($item));
        // }
        // arrPrintKuning($src_ytd);
        $datas = array();
        $datas['mtd'] = $src_mtd;
        $datas['ytd'] = $src_ytd;
        $datas['ytd_pluss'] = $src_ytd_pluss;
        $datas['ytd_previous'] = $src_yang_lalu;
        $datas['bulanan_previous'] = $src_bln_yang_lalu;
        $datas['rekening'] = $condite_rekening;
        $datas['transaksi_tipe'] = $transaksi_tipes;
        $datas['transaksi'] = $tr_datas;
        $datas['kolom'] = $koloms;

        return $datas;

    }
    /*--layer ke 2--*/
    public function callPerSellerBulan($date1, $date2){

        $src_00 = $this->getSaldoSellerTahun($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_00);
        // matiHere(__LINE__ . __FILE__);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $sumSubjek = array();
        $arrSubjek = array();
        foreach ($src_mtd as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
            // //
            // // // ---------------------------------------------------------
            // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // // }
            // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;


            //---------------------------------------------------------
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            foreach ($src_koloms as $src_kolom) {
                if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                    $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                }
                $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
            //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
            // }
            // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
            //
            // // ---------------------------------------------------------
            // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // }
            // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;


            //---------------------------------------------------------
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        $qty_kirim = 0;
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            $rekening = $sbjDatas['rekening'];
            $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
            $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
            $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
            $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;

            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_saldo_reject_7499 = isset($sbjDatas['now_saldo_reject_7499']) ? $sbjDatas['now_saldo_reject_7499'] : "0";
            $now_saldo_reject_382spd = isset($sbjDatas['now_saldo_reject_382spd']) ? $sbjDatas['now_saldo_reject_382spd'] : "0";
            $now_saldo_reject_all = $now_saldo_reject_582spd + $now_saldo_reject_7499 + $now_saldo_reject_382spd;

            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
            $now_saldo_closed_7499 = isset($sbjDatas['now_saldo_closed_7499']) ? $sbjDatas['now_saldo_closed_7499'] : "0";
            $now_saldo_closed_382spd = isset($sbjDatas['now_saldo_closed_382spd']) ? $sbjDatas['now_saldo_closed_382spd'] : "0";
            $now_saldo_closed_all = $now_saldo_closed_582spd + $now_saldo_closed_7499 + $now_saldo_closed_382spd;

            $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
            $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
            $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
            $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;

            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
            $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
            $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;

            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";

            $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";

            $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
            $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
            $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
            $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
            $now_saldo_order_netto_all = $now_saldo_order_all - $now_saldo_reject_all - $now_saldo_closed_all;
            // $now_saldo_order_netto_all = $now_saldo_order_all;

            $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
            $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
            $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
            $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
            $sumSubjek[$sbj_id]["now_saldo_reject_all"] = $now_saldo_reject_all;
            $sumSubjek[$sbj_id]["now_saldo_closed_all"] = $now_saldo_closed_all;
            $sumSubjek[$sbj_id]["now_saldo_order_netto_all"] = $now_saldo_order_netto_all;
            /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
            if ($now_saldo_order_582so > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
            }
            if ($now_saldo_order_all > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = $now_kredit_all;
            }
            // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
            // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
            $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
            $last_kredit_582spd = 0;
            if ($now_kredit_582spd > 0) {
                $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
            }
            else {
                //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
                // cekHijau("$now_saldo_order_582so");
                $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
            }
            $last_kredit_7499 = $prev_kredit_7499 + $now_kredit_7499;
            $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
            $sumSubjek[$sbj_id]["last_kredit_allspd"] = $last_kredit_7499 + $last_kredit_582spd;
            $sumSubjek[$sbj_id]["last_kredit_all"] = $prev_kredit_all + $now_saldo_order_all - $now_saldo_closed_582spd - $now_saldo_reject_582spd - $now_saldo_kirim_all;
        }

        // arrPrintWebs($sumSubjekSeller2);
        // arrPrintWebs($sumSubjekSeller);
        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // arrPrintHijau($arrSubjek);
        //   matiHere(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];


            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumParams;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;

        return $masterData;
    }

    /*---z_sales_salesman_transaksi_cache---*/
    public function getSaldoSellerTransaksiBulan($get_date1, $get_date2)
    {
        $seller_id = isset($this->seller_id) ? array("seller_id" => $this->seller_id) : array();
        $master_id = isset($this->master_id) ? array("master_id" => $this->master_id) : array();
        $transaksi_id = isset($this->master_id) ? array("id" => $this->master_id) : array();
        $req_tahun = formatTanggal($get_date1, "Y");
        $req_bulan_ini = formatTanggal($get_date1, "m");

        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $koloms = array(
            // "dtime",
            "debet",
            "kredit",
            "saldo_debet",
            "saldo_kredit",
            "saldo_qty_debet",
            "saldo_qty_kredit",
            "saldo_reject",
            "saldo_closed",
            "saldo_qty_reject",
            "saldo_qty_closed",
            "saldo_edit",
            "saldo_qty_edit",
            "saldo_order",
            "saldo_kirim",
            "saldo_qty_order",
            "saldo_qty_kirim",
        );
        $condite_rekening = array(
            /*--reguler*/
            "582so",
            "582spd",
            // "982",
            /*--project*/
            // "5888spo",
            "588so",
            "7499",
            /*--eksport*/
            "382so",
            "382spd",
            // "9912",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        $this->ci->db->where_in("rekening", $condite_rekening);
        $condites = array(
            // "month(dtime)" => $req_bulan_ini,
            "year(dtime)"  => $req_tahun,
            // "date(dtime)<=" => $get_date2,
            // "seller_id"    => "65",
            // "seller_id"    => "69",
            // "seller_id"     => "663",
            // "master_id"     => "130358",
            "periode"      => "tahunan",
        );
        $condites_main = array(
                // "qty_debet>" => 0,
                // "master_id" => "100788",
            ) + $seller_id + $master_id;
        $this->ci->db->where($condites + $condites_main);
        $this->ci->db->order_by("id", "asc");
        $tbl = "z_sales_salesman_transaksi_cache";
        // $srcs_0 = $ps->fetchMovement(true);
        $srcs_0 = $this->ci->db->get($tbl)->result();
        // showLast_query("kuning");
        // cekMerah(sizeof($srcs_0));
        // arrPrintPink($condites);
        // arrPrintPink($srcs_0);
        $src_bln_now = array();
        $src_mtd = array();
        foreach ($srcs_0 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            $rekening_db = $item->rekening;
            // $transaksi_tipe_db = isset($item->transaksi_tipe) ? $item->transaksi_tipe : "";
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;

            // $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
            // $src_mtd[] = (array)$item + $kolom_baru;
            $src_bln_now[$yearBln][] = (array)$item;
            $src_mtd[] = (array)$item;
        }
        // arrPrintKuning($srcs_0);
        // arrPrintKuning($src_bln_now);
        // arrPrintKuning($src_mtd);
        // mati_disini(__LINE__);

        /* -------------------------------------------------------
        * data awal tahun sampai akhir bulan lalu
        * -------------------------------------------------------*/
        $awal_tahun = formatTanggal($get_date1, 'Y-01-01');
        $awal_tahun = "2021-01-01";
        $akhir_tahun = formatTanggal($get_date1, 'Y');
        // $akhir_bulan_lalu = formatTanggal(previousMonth($get_date1), 'Y-m-t');
        $akhir_bulan_lalu = previousDate($get_date1);
        // cekMerah("$get_date1 $akhir_bulan_lalu " . previousDate($get_date1));
        $condite_previous = array(
            // "year(dtime)" => $req_tahun,
            "year(dtime)>=" => $awal_tahun,
            "year(dtime)<"  => $akhir_tahun,
            // "seller_id"    => "65",
            // "seller_id"     => "69",
            // "kredit>" => 0,
            "periode"       => "tahunan",
        );
        $condite_previous_main = $condites_main;
        $this->ci->db->where($condite_previous + $condite_previous_main);
        $this->ci->db->order_by("id", "asc");
        $this->ci->db->where_in("rekening", $condite_rekening);
        // $src_002 = $ps->fetchMovement("persediaan_produk");
        $src_002 = $this->ci->db->get($tbl)->result();
        // showLast_query("here");
        // cekHere(sizeof($src_002));
        // arrPrintKuning($condite_previous);

        $kolom_baru = array();
        $src_bln_yang_lalu = array();
        $src_yang_lalu = array();
        foreach ($src_002 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            // $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet;
            $debet = $item->debet;
            $qty_kredit = $item->qty_kredit;
            $kredit = $item->kredit;
            // foreach ($transaksi_tipes as $transaksi_tipe) {
            //     $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
            //     $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
            //     $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
            //     $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            // }

            // $src_bln_yang_lalu[$yearBln][] = (array)$item + $kolom_baru;
            // $src_yang_lalu[] = (array)$item + $kolom_baru;
            $src_bln_yang_lalu[$yearBln][] = (array)$item;
            $src_yang_lalu[] = (array)$item;
        }

        $src_ytd = array_merge($src_yang_lalu, $src_mtd);
        /* --------------------------------------------------------------
         * ngecek jml data per bulan
         * --------------------------------------------------------------*/
        $this->ci->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr_field = $tr->getFields();
        $this->ci->db->select($tr_field['main']);
        $condite_tr = array(
            "date(dtime)>=" => "2021-01-01",
            "date(dtime)<=" => dtimeNow('Y-m-d'),
            "jenis" => "582spo",
        );
        $this->ci->db->where($condite_tr);
        $src_tr = $tr->lookupAll()->result();
        // showLast_query("hijau");
        $tr_datas = array();
        foreach ($src_tr as $item) {
            $tr_data = addPrefixKeyT_he_format((array)$item);
            $tr_datas[$item->id] = $tr_data;
        }
        // arrPrint($tr_datas);
        $src_ytd_pluss = array();
        foreach ($src_ytd as $item) {
            // $item_trs = isset($tr_datas[$item['master_id']]) ? $tr_datas[$item['master_id']] : array();
            $item_trs = isset($tr_datas[$item['master_id']]) ? $tr_datas[$item['master_id']] : array();
            $src_ytd_pluss[] = $item + $item_trs;
        }
        // showLast_query("merah");
        // matiHere(__LINE__);

        // cekHijau(sizeof($src_002));
        // foreach ($src_bln_yang_lalu as $ybl => $item) {
        //     cekBiru("$ybl " . sizeof($item));
        // }
        // arrPrintKuning($src_ytd);
        $datas = array();
        $datas['mtd'] = $src_mtd;
        $datas['ytd'] = $src_ytd;
        $datas['ytd_pluss'] = $src_ytd_pluss;
        $datas['ytd_previous'] = $src_yang_lalu;
        $datas['bulanan_previous'] = $src_bln_yang_lalu;
        $datas['rekening'] = $condite_rekening;
        $datas['transaksi_tipe'] = $transaksi_tipes;
        $datas['transaksi'] = $tr_datas;
        $datas['kolom'] = $koloms;

        return $datas;

    }

    public function callPerTransaksiBulan($date1, $date2){
        $src_00 = $this->getSaldoSellerTransaksiTahun($date1, $date2);
        //        arrPrintKuning($src_00);
        //        showLast_query("kuning");
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $src_ytd = $src_00['ytd'];
        $src_ytd_pluss = $src_00['ytd_pluss'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_tr = $src_00['transaksi'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_yang_lalu);
        // arrPrintPink($src_ytd);
        // arrPrint($src_ytd_pluss);
        foreach ($src_ytd_pluss as $src_ytd_pluss) {
            $subjek_id = $src_ytd_pluss['master_id'];
            $tr_datas[$subjek_id] = $src_ytd_pluss;
        }
        // matiDisini(__LINE__);
        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $arrSubjek = array();
        $sumSubjek = array();
        $arrDateNow = array();
        foreach ($src_mtd as $item) {

            $subjek_id = $item['master_id'];
            $transaksi_id = $item['transaksi_id'];

            $seller_id = $item['seller_id'];
            $seller_nama = $item['seller_nama'];
            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // cekBiru("$rekening");
            // cekBiru($sumSubjek);

            //---------------------------------------------------------
            // $arrSubjek[$subjek_id]['seller_id'] = $seller_id;
            // $arrSubjek[$subjek_id]['seller_nama'] = $seller_nama;
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
            $arrDatas[$subjek_id] = $item;
            $arrDateNow[$subjek_id]['now_dtime'] = $item['dtime'];
            $arrDateNow[$subjek_id]['now_seller_nama'] = $item['seller_nama'];
        }

        // arrPrintHijau($arrSubjek);
        // arrPrint($sumSubjek);
        // matiHere(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        $rekening_subjek_2 = array(
            "582spd", "7499",
            "382spd"
        );
        $sumSubjek2 = array();
        foreach ($src_yang_lalu as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['master_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $nilai = $item[$src_kolom] * 1;

                // if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                //     $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                // }
                $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;

                $sumSubjek2[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
            }


            //---------------------------------------------------------
            $sumSubjek[$subjek_id]['rekening'] = $rekening;

            // $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            // $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];

            // $arrSubjek[$subjek_id] = $item;
            // if($rekening == "582spd"){
            if (in_array($rekening, $rekening_subjek_2)) {
                $arrSubjek_2[$subjek_id][] = $item;
            }

            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }

        // cekHijau(sizeof($sumSubjek));
        // arrPrintHijau($sumSubjek);
        // arrPrint($arrSubjek_2);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------
         * filter untuk membuang prevous outstandinf yg dibawah nilai 1
         * ----------------------------------------------------------------*/
        // arrPrintKuning($sumSubjek2);
        foreach ($arrSubjek_2 as $mst_id => $item) {
            $arrSubjek_3[$mst_id] = end($item);
        }
        // arrPrint($arrSubjek_3);
        foreach ($arrSubjek_3 as $mast_id => $item) {
            // if ($item['rekening'] == "582spd" && $item["kredit"] >= 1) {
            //     $arrSubjek[$mast_id] = $item;
            // }
            // if ($item['rekening'] == "7499" && $item["kredit"] >= 1) {
            //     $arrSubjek[$mast_id] = $item;
            // }
            if (in_array($item['rekening'], $rekening_subjek_2) && ($item["kredit"] >= 1)) {
                $arrSubjek[$mast_id] = $item;
            }
        }
        // $arrSubjek = $arrSubjek_2;

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        $qty_kirim = 0;
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            $rekening = $sbjDatas['rekening'];
            $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
            $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
            $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
            $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;

            $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
            $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
            $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
            $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;

            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
            $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
            $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;

            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";

            $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";

            $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
            $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
            $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
            $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;

            $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
            $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
            $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
            $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
            /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
            if ($now_saldo_order_582so > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
            }
            if ($now_saldo_order_all > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = $prev_kredit_all - $now_saldo_kirim_all;
            }
            // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
            // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
            $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
            $last_kredit_582spd = 0;
            // if ($now_kredit_582spd > 0) {
            //     $last_kredit_582spd = ($now_kredit_582spd * 1);
            // }
            // else {
            // $last_kredit_582spd = $prev_kredit_582spd - $now_saldo_kirim_582spd_old - 0 + $now_kredit_582spd;
            $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);
            // }

            // if($last_kredit_582spd > 0){

            // cekMerah("$sbj_id || $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);");
            // }
            $last_kredit_7499 = $prev_kredit_all + $now_saldo_order_all - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_all;
            $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
            $sumSubjek[$sbj_id]["last_kredit_all"] = $last_kredit_7499;

        }

        // arrPrintWebs($sumSubjekSeller2);
        // arrPrintWebs($sumSubjekSeller);
        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // arrPrintHijau($arrSubjek);
        // cekBiru($arrDateNow);
        //   matiHere(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            $transParams = $tr_datas[$subj_id];
            $now_date = isset($arrDateNow[$subj_id]) ? $arrDateNow[$subj_id] : array();

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumParams + $transParams + $now_date;
            // $hasilOlahan_1[] = $itemParam;
        }
        // arrPrintKuning($hasilOlahan_1);
        $masterData = $hasilOlahan_1;

        return $masterData;
    }
}