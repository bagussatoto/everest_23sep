<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DataProcurement
{
    protected $supplier_id;

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
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

    public function callProduk($get_date1, $get_date2)
    {
        $seller_id = isset($this->seller_id) ? array("seller_id" => $this->seller_id) : array();

        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();


        $condite_rekening = array(
            "466",
            "467",
            "460",
            "460a",
            "460r",
            "9911",
            // "461r",
            // "461",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        $this->ci->db->where_in("rekening", $condite_rekening);
        $condites = array(
            "date(dtime)>=" => $get_date1,
            "date(dtime)<=" => $get_date2,
            // "seller_id"    => "65",
            // "seller_id"    => "69",
            // "seller_id"     => "663",
            // "master_id"     => "130358",
        );
        $condites_main = array(
                // "qty_debet>" => 0,
                // "transaksi_tipe" => "reguler",
            ) + $seller_id;
        $this->ci->db->where($condites + $condites_main);
        $this->ci->db->order_by("id", "asc");
        $srcs_0 = $ps->fetchMovement(true);
        // showLast_query("merah");
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
            $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet * 1;
            $debet = $item->debet * 1;
            $qty_kredit = $item->qty_kredit * 1;
            $kredit = $item->kredit * 1;
            foreach ($transaksi_tipes as $transaksi_tipe) {
                $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
                $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
                $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
                $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            }

            $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
            $src_mtd[] = (array)$item + $kolom_baru;
        }
        // arrPrintKuning($srcs_0);
        // arrPrintKuning($src_bln_now);
        // arrPrintKuning($src_mtd);
        // mati_disini(__LINE__);

        /* -------------------------------------------------------
        * data awal tahun sampai akhir bulan lalu
        * -------------------------------------------------------*/
        $awal_tahun = formatTanggal($get_date1, 'Y-01-01');
        // $akhir_bulan_lalu = formatTanggal(previousMonth($get_date1), 'Y-m-t');
        $akhir_bulan_lalu = previousDate($get_date1);
        // cekMerah("$get_date1 $akhir_bulan_lalu " . previousDate($get_date1));
        $condite_previous = array(
            "date(dtime)>=" => $awal_tahun,
            "date(dtime)<=" => $akhir_bulan_lalu,
            // "seller_id"    => "65",
            // "seller_id"     => "69",
            // "seller_id"     => "663",
        );
        $condite_previous_main = $condites_main;
        $this->ci->db->where($condite_previous + $condite_previous_main);
        $this->ci->db->order_by("id", "asc");
        $this->ci->db->where_in("rekening", $condite_rekening);
        $src_002 = $ps->fetchMovement("persediaan_produk");
        // showLast_query("kuning");
        // arrPrintKuning($condite_previous);

        $kolom_baru = array();
        $src_bln_yang_lalu = array();
        $src_yang_lalu = array();
        foreach ($src_002 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet * 1;
            $debet = $item->debet * 1;
            $qty_kredit = $item->qty_kredit * 1;
            $kredit = $item->kredit * 1;
            foreach ($transaksi_tipes as $transaksi_tipe) {
                $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
                $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
                $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
                $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            }

            $src_bln_yang_lalu[$yearBln][] = (array)$item + $kolom_baru;
            $src_yang_lalu[] = (array)$item + $kolom_baru;
        }

        $src_ytd = array_merge($src_yang_lalu, $src_mtd);
        /* --------------------------------------------------------------
         * ngecek jml data per bulan
         * --------------------------------------------------------------*/
        // cekHijau(sizeof($src_002));
        // foreach ($src_bln_yang_lalu as $ybl => $item) {
        //     cekBiru("$ybl " . sizeof($item));
        // }
        // arrPrintKuning($src_ytd);
        $datas = array();
        $datas['mtd'] = $src_mtd;
        $datas['ytd'] = $src_ytd;
        $datas['ytd_previous'] = $src_yang_lalu;
        $datas['bulanan_previous'] = $src_bln_yang_lalu;
        $datas['rekening'] = $condite_rekening;
        $datas['transaksi_tipe'] = $transaksi_tipes;

        return $datas;

    }

    public function callSupplies($get_date1, $get_date2)
    {
        $supplier_id = isset($this->supplier_id) ? array("supplier_id" => $this->supplier_id) : array();

        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();


        $condite_rekening = array(
            // "466",
            // "467",
            // "460",
            // "460a",
            // "460r",
            "9911",
            "461r",
            "461",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        $this->ci->db->where_in("rekening", $condite_rekening);
        $condites = array(
            "date(dtime)>=" => $get_date1,
            "date(dtime)<=" => $get_date2,
            // "seller_id"    => "65",
            // "seller_id"    => "69",
            // "seller_id"     => "663",
            // "master_id"     => "130358",
        );
        $condites_main = array(
                // "qty_debet>" => 0,
                // "transaksi_tipe" => "reguler",
            ) + $supplier_id;
        $this->ci->db->where($condites + $condites_main);
        $this->ci->db->order_by("id", "asc");
        $srcs_0 = $ps->fetchMovement(true);
        // showLast_query("merah");
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
            $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet * 1;
            $debet = $item->debet * 1;
            $qty_kredit = $item->qty_kredit * 1;
            $kredit = $item->kredit * 1;
            foreach ($transaksi_tipes as $transaksi_tipe) {
                $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
                $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
                $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
                $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            }

            $src_bln_now[$yearBln][] = (array)$item + $kolom_baru;
            $src_mtd[] = (array)$item + $kolom_baru;
        }
        // arrPrintKuning($srcs_0);
        // arrPrintKuning($src_bln_now);
        // arrPrintKuning($src_mtd);
        // mati_disini(__LINE__);

        /* -------------------------------------------------------
        * data awal tahun sampai akhir bulan lalu
        * -------------------------------------------------------*/
        $awal_tahun = formatTanggal($get_date1, 'Y-01-01');
        // $akhir_bulan_lalu = formatTanggal(previousMonth($get_date1), 'Y-m-t');
        $akhir_bulan_lalu = previousDate($get_date1);
        // cekMerah("$get_date1 $akhir_bulan_lalu " . previousDate($get_date1));
        $condite_previous = array(
            "date(dtime)>=" => $awal_tahun,
            "date(dtime)<=" => $akhir_bulan_lalu,
            // "seller_id"    => "65",
            // "seller_id"     => "69",
            // "seller_id"     => "663",
        );
        $condite_previous_main = $condites_main;
        $this->ci->db->where($condite_previous + $condite_previous_main);
        $this->ci->db->order_by("id", "asc");
        $this->ci->db->where_in("rekening", $condite_rekening);
        $src_002 = $ps->fetchMovement("persediaan_produk");
        // showLast_query("kuning");
        // arrPrintKuning($condite_previous);

        $kolom_baru = array();
        $src_bln_yang_lalu = array();
        $src_yang_lalu = array();
        foreach ($src_002 as $item) {
            $dtime = $item->dtime;
            $year = formatTanggal($dtime, 'Y');
            $bulan = formatTanggal($dtime, 'm');
            $yearBln = formatTanggal($dtime, 'Y-m');
            $transaksi_tipe_db = $item->transaksi_tipe;
            $qty_debet = $item->qty_debet * 1;
            $debet = $item->debet * 1;
            $qty_kredit = $item->qty_kredit * 1;
            $kredit = $item->kredit * 1;
            foreach ($transaksi_tipes as $transaksi_tipe) {
                $kolom_baru["qty_debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_debet : 0;
                $kolom_baru["debet_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $debet : 0;
                $kolom_baru["qty_kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $qty_kredit : 0;
                $kolom_baru["kredit_$transaksi_tipe"] = $transaksi_tipe_db == $transaksi_tipe ? $kredit : 0;
            }

            $src_bln_yang_lalu[$yearBln][] = (array)$item + $kolom_baru;
            $src_yang_lalu[] = (array)$item + $kolom_baru;
        }

        $src_ytd = array_merge($src_yang_lalu, $src_mtd);
        /* --------------------------------------------------------------
         * ngecek jml data per bulan
         * --------------------------------------------------------------*/
        // cekHijau(sizeof($src_002));
        // foreach ($src_bln_yang_lalu as $ybl => $item) {
        //     cekBiru("$ybl " . sizeof($item));
        // }
        // arrPrintKuning($src_ytd);
        $datas = array();
        $datas['mtd'] = $src_mtd;
        $datas['ytd'] = $src_ytd;
        $datas['ytd_previous'] = $src_yang_lalu;
        $datas['bulanan_previous'] = $src_bln_yang_lalu;
        $datas['rekening'] = $condite_rekening;
        $datas['transaksi_tipe'] = $transaksi_tipes;

        return $datas;

    }
}