<?php

class PenjualanCompare2 extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("582spd", "982", "382spd");
    }

    public function cekSellerBl()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $req_thn = "2022";

        // -------------------------------------------------------------regular export project
        $tbl = "z_sales_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        // showLast_query("merah");
        // arrPrint($src_datas);
        $bln_datas = array();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;

            // $bln_datas[$seller_id][$bln] = $src_data;

            if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
                $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
                $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
            if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
                $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;

            $seller_data[$seller_id] = $seller_nama;
        }

        // ---------------------------------------------------------------batal
        $tbl = "z_sales_batal_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "583spd"
        );
        $this->db->where_in("master_jenis", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;

            if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
                $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;

            $seller_data[$seller_id] = $seller_nama;
        }

        // ---------------------------------------------------------------return
        $tbl = "z_sales_return_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "982",
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;

            if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
                $bln_datas[$seller_id][$bln]["sum_return"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;

            $seller_data[$seller_id] = $seller_nama;
        }

        // arrPrint($bln_datas[77]);

        /*SELLER*/
        $this->load->model("Mdls/MdlEmployee");
        $cs = new MdlEmployee();

        $cs->setFilters(array());
        $em_condites = array(
            "jenis" => "seller",
            "trash" => "0",
            "status" => "1",
        );
        $src_sales = $cs->lookupByCondition($em_condites)->result();
        // showLast_query("kuning");

        // $seller_datas = array();
        foreach ($src_sales as $item) {
            $cu_id = $item->id;
            $cu_nama = $item->nama;
            $seller_data[$cu_id] = $cu_nama;
        }
        // $sellers = $seller_data + $customer_datas;
        // arrPrint($seller_data);
        $sellers = $seller_data;
        $headers = array(
            // "no" => array(),
            "nama" => array(
                "label" => "PIC",
            ),
        );

        $data = array(
            "mode" => "langsung_indek",
            "title" => "Laporan penjualan per salesman $req_thn",
            "subTitle" => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // // "color_bar"   => "box-info",
            // // "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "582",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(4),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $headers,
            "customer_datas" => $sellers,
            "bln_datas" => $bln_datas,
            "bln_nama" => namaBulan2(),
            "link_detil" => base_url() . "laporan/PenjualanCompare/viewDetil?thn=$req_thn",

            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            // "date_min"    => 1,
            // "date_max"    => dtimeNow('Y-m-d'),
            // "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_compare", $data);
    }

    public function cekSellerBl2()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $req_thn = isset($_GET['thn']) ? $_GET['thn'] : dtimeNow('Y');;

        // // -------------------------------------------------------------regular export project
        // $tbl = "z_sales_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "382spd"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // // showLast_query("merah");
        // // arrPrint($src_datas);
        // $bln_datas = array();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     // $bln_datas[$seller_id][$bln] = $src_data;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
        //         $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
        //         $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
        //         $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------batal
        // $tbl = "z_sales_batal_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "583spd"
        // );
        // $this->db->where_in("master_jenis", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
        //         $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------return
        // $tbl = "z_sales_return_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "982",
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
        //         $bln_datas[$seller_id][$bln]["sum_return"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }

        $src_penjualans = $this->callPenjualans($req_thn);
        $seller_data = $src_penjualans['sellers'];
        $bln_datas = $src_penjualans['penjualan'];

        // arrPrint($bln_datas[77]);

        /*SELLER*/
        $this->load->model("Mdls/MdlEmployee");
        $cs = new MdlEmployee();

        $cs->setFilters(array());
        $em_condites = array(
            "jenis" => "seller",
            "trash" => "0",
            "status" => "1",
        );
        $src_sales = $cs->lookupByCondition($em_condites)->result();
        // showLast_query("kuning");

        // $seller_datas = array();
        foreach ($src_sales as $item) {
            $cu_id = $item->id;
            $cu_nama = $item->nama;
            $seller_data[$cu_id] = $cu_nama;
        }
        // $sellers = $seller_data + $customer_datas;
        // arrPrint($seller_data);
        $sellers = $seller_data;
        $headers = array(
            // "no" => array(),
            "nama" => array(
                "label" => "PIC",
            ),
        );

        $data = array(
            "mode" => "langsung_indek2",
            "title" => "Laporan penjualan per salesman Tahun $req_thn",
            "subTitle" => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // // "color_bar"   => "box-info",
            // // "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "582",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(4),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $headers,
            "customer_datas" => $sellers,
            "bln_datas" => $bln_datas,
            "bln_nama" => namaBulan2(),
            "link_detil" => base_url() . "laporan/PenjualanCompare/viewDetil?thn=$req_thn",

            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            // "date_min"    => 1,
            // "date_max"    => dtimeNow('Y-m-d'),
            // "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_compare", $data);
    }

    /* ----------------------------------------------------
    * pengambilan data masih dr com
    * ----------------------------------------------------*/
    // rendering complite
    public function viewepenjualan1()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
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
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
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
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
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
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $add_td = "<td>$btn_td</td>";
        $add_td = "";
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
        $scriptBottom = "<script>$('#top_navigasi').fadeOut();</script>";
        $data = array(
            "mode" => "indek",
            "title" => "Laporan Penjualan ",
            "subTitle" => "Raw Data Sales Order",
            "modul_path" => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // navigasi
            "url" => base_url(uri_string()) . "$strGet",
            "data_id" => "permaster",
            "add_td" => $add_td,
            "date1" => $date1,
            "date2" => $date2,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            "sum_satu" => base_url() . "laporan/PenjualanCompare/cekSellerBl" . "$strGet",
            "sum_satu_2" => base_url() . "laporan/PenjualanCompare/cekSellerBl2" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/cekpenjualancustomer" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",
            "scriptBottom" => $scriptBottom,
        );
        $this->load->view("laporan_compare", $data);
    }

    public function viewmypenjualan()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
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
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
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
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
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
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $add_td = "<td>$btn_td</td>";
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
        $scriptBottom = "<script>$('#top_navigasi').fadeOut();</script>";
        $data = array(
            "mode" => "indek",
            "title" => "Laporan Aktifitas Penjualan",
            "subTitle" => "Raw Data Sales Order",
            "modul_path" => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // navigasi
            "url" => base_url(uri_string()) . "$strGet",
            "data_id" => "permaster",
            "add_td" => $add_td,
            "date1" => $date1,
            "date2" => $date2,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanseller" . "$strGet",
            "sum_dua" => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",


        );
        $this->load->view("laporan", $data);
    }

    public function viewepenjualan()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $year_now = dtimeNow('Y');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));
        $strTahun = isset($_GET['thn']) ? $_GET['thn'] : 0;
        // $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
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
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
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
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
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

        /*-------------------------------------------------------*/
        for ($i = 1; $i <= 3; $i++) {
            // echo $i;
            // echo "$thini  ";
            $thnya = $year_now--;
            $arrTahun[] = $thnya;
        }
        // arrPrint($arrTahun);
        $action = base_url() . "laporan/" . get_class($this) . "/viewepenjualan";
        $key = "?thn";
        $var = "";
        $var .= "<select id='tahun' class='btn btn-danger' onchange=\"location.href='$action" . $key . "='+this.value\">";
        $var .= "<option value=''>--pilih tahun--</option>";
        foreach ($arrTahun as $th) {
            $selected = $th == $strTahun ? "" : "";
            $var .= "<option value='$th' $selected>$th</option>";
        }
        $var .= "</select>";
        // $var .= "<span class='input-group-btn'>";
        // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='$action?year='+document.getElementById('bulan').value\"><i class='fa fa-send-o'></i></button>";
        // // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='jjj?='+get.value\"><i class='fa fa-send-o'></i></button>";
        // $var .= $tambahan;
        // $var .= "</span>";
        // $var .= "</div>";
        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger'disabled onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        // $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "&nbsp;" . $var;
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
        // $scriptBottom = "<script>$('#top_navigasi').fadeOut();</script>";
        $data = array(
            "mode" => "indek",
            "title" => "Laporan Penjualan ",
            "subTitle" => "Raw Data Sales Order",
            "modul_path" => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // navigasi
            "url" => base_url(uri_string()) . "$strGet",
            "data_id" => "permaster",
            "add_td" => $add_td,
            "date1" => $date1,
            "date2" => $date2,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            "sum_satu" => base_url() . "laporan/PenjualanCompare/cekSellerBl" . "$strGet",
            "sum_satu_2" => base_url() . "laporan/PenjualanCompare/cekSellerBl2" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/cekpenjualancustomer" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",
            "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
        );
        $this->load->view("laporan_compare", $data);
    }

    public function viewDetil()
    {
        // arrPrint(url_segment());
        // arrPrint($_GET);
        $seller_id = $_GET['exid'];
        $thn = $_GET['thn'];
        $bln = $_GET['bln'];

        $bln_nama = namaBulan()[$bln];
        // arrPrintKuning($bln_nama);
        $id_tr = "_" . $seller_id;
        $id_td = $seller_id . "_" . $bln;

        $src_datas = $this->callPenjualans($thn);
        // arrPrintHijau($src_datas);
        $seller_nama = $src_datas["sellers"][$seller_id];
        $datas = $src_datas["penjualan"][$seller_id][$bln];
        $detil_datas = $src_datas["penjualan_jenis"][$seller_id][$bln];
        // arrPrint($src_datas["penjualan"][$seller_id][$bln]);
        // -------------------------------------------------------------------------------------------------------
        $detile_headers = array(
            "582spd" => array(
                "label" => "reguler"
            ),
            "382spd" => array(
                "label" => "export"
            ),
            "7499" => array(
                "label" => "project"
            ),
            // "9912_582spd" => array(
            //     "label" => "batal"
            // ),
            "9912" => array(
                "label" => "batal"
            ),
            "982" => array(
                "label" => "return"
            ),
        );
        // arrPrintKuning($detil_datas);
        $dtl = "";
        $dtl .= "<table class='table table-bordered no-margin'>";
        // -------------------------------------------------------------------------------------------------------
        $dtl .= "<tr class='text-uppercase bg-info'>";
        foreach ($detile_headers as $ky_header => $detile_header) {
            $label = isset($detile_header['label']) ? $detile_header['label'] : $ky_header;
            $dtl .= "<th>$label</th>";
        }
        $dtl .= "<th>netto</th>";
        $dtl .= "</tr>";
        // -------------------------------------------------------------------------------------------------------
        // arrPrintPink($detil_datas);
        $dtl .= "<tr>";
        foreach ($detile_headers as $ky_header => $detile_header) {
            $var_key = "_$ky_header";
            $label = isset($detile_header['label']) ? $detile_header['label'] : $ky_header;
            $$var_key = $nilai = isset($detil_datas[$ky_header]) ? $detil_datas[$ky_header] : 0;
            $nilai_f = formatField_he_format("harga", $nilai);

            $link_detile = base_url() . "laporan/PenjualanCompare/test?bln=$bln&exid=$seller_id&thn=$thn";
            $nilai_l = "<a href='$link_detile'  onclick=\"return hs.htmlExpand(this, { outlineType: 'rounded-white',wrapperClassName: 'draggable-header', objectType: 'ajax',} )\" class='text-link'>$nilai_f</a>";
            $dtl .= "<td>$nilai_f</td>";
        }
        $nilai_netto = ($_582spd + $_382spd + $_7499) - $_9912 - $_982;
        $nilai_netto_f = formatField_he_format("harga", $nilai_netto);
        $dtl .= "<td>$nilai_netto_f</td>";

        $dtl .= "</tr>";
        // -------------------------------------------------------------------------------------------------------
        $dtl .= "</table>";
        // -------------------------------------------------------------------------------------------------------
        $judul = "Penjualan <b>$seller_nama</b> $bln_nama $thn";
        $var = "<div class='font-size-1-5'>$judul</div>";
        $summary_keys = array(
            "sum_debet" => array(
                "label" => "bruto"
            ),
            "sum_batal" => array(
                "label" => "dibatalkan"
            ),
            "sum_return" => array(
                "label" => "direturn"
            ),
            // "sum_netto",
        );
        $isi = "";
        $isi .= "<div class='row'>";
        foreach ($summary_keys as $summary_key => $summ_params) {
            $label = isset($summ_params['label']) ? $summ_params['label'] : $summary_key;
            $isi .= "<div class='col-md-3 bg-info text-uppercase'>$label</div>";
        }
        $isi .= "<div class='col-md-3 bg-info text-uppercase''>netto</div>";
        $isi .= "</div>";

        $isi .= "<div class='row'>";
        foreach ($summary_keys as $summary_key => $summ_params) {
            $isi_value = isset($datas[$summary_key]) ? $datas[$summary_key] : 0;
            $isi_value_f = formatField_he_format("harga", $isi_value);
            $isi .= "<div class='col-md-3'>$isi_value_f</div>";
        }
        $bruto = isset($datas["sum_debet"]) ? $datas["sum_debet"] : 0;
        $batal = isset($datas["sum_batal"]) ? $datas["sum_batal"] : 0;
        $return = isset($datas["sum_return"]) ? $datas["sum_return"] : 0;
        $netto = $bruto - $batal - $return;
        $netto_f = formatField_he_format("harga", $netto);

        $isi .= "<div class='col-md-3'>$netto_f</div>";
        $isi .= "</div>";

        // $var .= "<div class='overflow-h'>$isi</div>";
        $var .= "<div class='overflow-h'>$dtl</div>";
        $var .= "<div class='overflow-h' id='rincian'></div>";

        $link_rincian = base_url() . "laporan/PenjualanCompare/test?thn=$thn&bln=$bln&exid=$seller_id";
        $var .= "<script>
                    top.$('#$id_td').css('background-color','yellow');
                    top.$('#$id_tr').css('background-color','##9fff78');
                    top.$('#rincian').load('$link_rincian');
                </script>";

        echo $var;
        echo "<hr>";
        echo $this->test();

    }

    public function test()
    {
        $seller_id = $_GET['exid'];
        $thn = $_GET['thn'];
        $bln = $_GET['bln'];
        $this->db->where("seller_id", $seller_id);
        // $this->db->where("seller_id","719");
        $src_datas = $this->callpenjualanTransaksi($thn, $bln);
        // cekHijau(sizeof($src_datas));
        // arrPrintPink($src_datas);

        // -------------------------------------------------------------------------------------------------------
        $detile_headers = array(
            "extern_nama" => array(
                "label" => "nomer"
            ),
            "dtime" => array(
                "label" => "tanggal",
                "format" => "formatField_he_format",
                "format_key" => "fulldate",
            ),
            "customer_nama" => array(
                "label" => "customer"
            ),
            "saldo_kirim" => array(
                "label" => "penjualan",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "summary" => true,
            ),
        );
        // arrPrintKuning($detil_datas);
        $dtl = "";
        $dtl .= "<h3 class='no-margin no-padding'>Transaksi</h3>";
        $dtl .= "<table class='table table-bordered no-margin'>";
        // -------------------------------------------------------------------------------------------------------
        $dtl .= "<tr class='text-uppercase bg-info'>";
        foreach ($detile_headers as $ky_header => $detile_header) {
            $label = isset($detile_header['label']) ? $detile_header['label'] : $ky_header;
            $dtl .= "<th>$label</th>";
        }
        // $dtl .= "<th>netto</th>";
        $dtl .= "</tr>";
        // -------------------------------------------------------------------------------------------------------
        // arrPrintPink($detil_datas);
        $jenistr = "582";
        $modul_path = "penjualan";
        foreach ($src_datas as $detil_datas) {
            // arrPrintPink($detil_datas);
            $dtl .= "<tr>";

            foreach ($detile_headers as $ky_header => $attrs) {
                $var_key = "_$ky_header";
                // $label = isset($detile_header['label']) ? $detile_header['label'] : $ky_header;
                $$var_key = $nilai = isset($detil_datas->$ky_header) ? $detil_datas->$ky_header : 0;
                $nilai_f = formatField_he_format($ky_header, $nilai);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $ky_header;
                $nilai_f = isset($attrs['format']) ? ($nilai != 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                $link_detile = base_url() . "laporan/PenjualanCompare/test?bln=$bln&exid=$seller_id&thn=$thn";
                $nilai_l = "<a href='$link_detile'  onclick=\"return hs.htmlExpand(this, { outlineType: 'rounded-white',wrapperClassName: 'draggable-header', objectType: 'ajax',} )\" class='text-link'>$nilai_f</a>";

                $dtl .= "<td>$nilai_f</td>";

                if (!isset($sum_kolom[$ky_header])) {
                    $sum_kolom[$ky_header] = 0;
                }

                $sum_kolom[$ky_header] += $nilai;
            }
            // $nilai_netto = ($_582spd + $_382spd + $_7499) - $_9912 - $_982;
            // $nilai_netto_f = formatField_he_format("harga", $nilai_netto);
            // $dtl .= "<td>$nilai_netto_f</td>";
            $dtl .= "</tr>";
        }

        $dtl .= "<tr class='bg-info'>";
        foreach ($detile_headers as $ky_header => $detile_header) {
            $nilai = isset($sum_kolom[$ky_header]) ? $sum_kolom[$ky_header] : 0;
            $dtl .= "<th>$nilai</th>";
        }
        $dtl .= "</tr>";
        // -------------------------------------------------------------------------------------------------------
        $dtl .= "</table>";
        // -------------------------------------------------------------------------------------------------------

        echo $dtl;
    }

    protected function callPenjualans($req_thn, $req_bln = "")
    {

        $reg_bln = array();
        if ($req_bln != "") {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        // -------------------------------------------------------------regular export project
        $tbl = "z_sales_salesman_cache";
        $condites = array(
                "periode" => "bulanan",
                "thn" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        // showLast_query("merah");
        // arrPrint($src_datas);
        $bln_datas = array();
        $bln_rekening_datas = array();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $rekening = $src_data->rekening;

            // $bln_datas[$seller_id][$bln] = $src_data;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
                $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
                $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
            if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
                $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;

            $seller_data[$seller_id] = $seller_nama;
        }

        // ---------------------------------------------------------------batal
        $tbl = "z_sales_batal_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("master_jenis", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        // arrPrintKuning($src_datas);
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $master_jenis = $src_data->master_jenis;
            $rekening_0 = $src_data->rekening;

            // $rekening = $rekening_0 . "_" . $master_jenis;
            $rekening = $rekening_0;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->batal_nilai;

            if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
                $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;

            $seller_data[$seller_id] = $seller_nama;
        }

        // ---------------------------------------------------------------return
        $tbl = "z_sales_return_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "982",
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        // arrPrintPink($src_datas);
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $rekening = $src_data->rekening;

            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
                $bln_datas[$seller_id][$bln]["sum_return"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;

            $seller_data[$seller_id] = $seller_nama;
        }
        // arrPrintWebs($bln_rekening_datas);
        $datas = array();
        $datas["sellers"] = $seller_data;
        $datas["penjualan"] = $bln_datas;
        $datas["penjualan_jenis"] = $bln_rekening_datas;

        return $datas;
    }

    protected function callpenjualanTransaksi($req_thn, $req_bln = "")
    {
        $reg_bln = array();
        if ($req_bln != "") {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        $tbl = "z_sales_salesman_transaksi_cache";
        $condites = array(
                "periode" => "bulanan",
                "thn" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        // showLast_query("merah");
        // arrPrint($src_datas);
        $bln_datas = array();
        $bln_rekening_datas = array();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $rekening = $src_data->rekening;

            // $bln_datas[$seller_id][$bln] = $src_data;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
                $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
                $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
            if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
                $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;

            $seller_data[$seller_id] = $seller_nama;
        }

        return $src_datas;
    }


    // order penjualan------------------------------------
    protected function callOrderPenjualans($req_thn, $req_bln = "")
    {

        $reg_bln = array();
        if ($req_bln != "") {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        // -------------------------------------------------------------regular export project
        $tbl = "z_sales_salesman_cache";
        $condites = array(
                "periode" => "bulanan",
                "thn" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        $rekenings = array(
//            "588spo", "582spo", "382spo"
            "588so", "582so", "382so"
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
//        showLast_query("merah");
        // arrPrint($src_datas);
        $bln_datas = array();
        $bln_rekening_datas = array();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $rekening = $src_data->rekening;

            // $bln_datas[$seller_id][$bln] = $src_data;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_order;

            if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
                $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_order;

            if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
                $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;

            if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
                $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;

            $seller_data[$seller_id] = $seller_nama;
        }

        // ---------------------------------------------------------------batal

//        $tbl = "z_sales_batal_salesman_cache";
//        $condites = array(
//            "periode" => "bulanan",
//            "thn" => $req_thn,
//        );
//        $this->db->where($condites);
//        $rekenings = array(
//            "7499", "582spd", "382spd"
//        );
//        $this->db->where_in("master_jenis", $rekenings);
//        // $this->db->group_by("seller_id,bln,thn");
//        $src_datas = $this->db->get($tbl)->result();
////        showLast_query("merah");
//        // arrPrintKuning($src_datas);
//        foreach ($src_datas as $src_data) {
//            $seller_id = $src_data->seller_id;
//            $seller_nama = $src_data->seller_nama;
//            $bln = $src_data->bln;
//            $master_jenis = $src_data->master_jenis;
//            $rekening_0 = $src_data->rekening;
//
//            // $rekening = $rekening_0 . "_" . $master_jenis;
//            $rekening = $rekening_0;
//            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
//                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
//            }
//            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->batal_nilai;
//
//            if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
//                $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
//            }
//            $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;
//
//            $seller_data[$seller_id] = $seller_nama;
//        }

        // ---------------------------------------------------------------return

//        $tbl = "z_sales_return_salesman_cache";
//        $condites = array(
//            "periode" => "bulanan",
//            "thn" => $req_thn,
//        );
//        $this->db->where($condites);
//        $rekenings = array(
//            "982",
//        );
//        $this->db->where_in("rekening", $rekenings);
//        // $this->db->group_by("seller_id,bln,thn");
//        $src_datas = $this->db->get($tbl)->result();
////        showLast_query("merah");
//        // arrPrintPink($src_datas);
//        foreach ($src_datas as $src_data) {
//            $seller_id = $src_data->seller_id;
//            $seller_nama = $src_data->seller_nama;
//            $bln = $src_data->bln;
//            $rekening = $src_data->rekening;
//
//            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
//                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
//            }
//            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_kirim;
//
//            if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
//                $bln_datas[$seller_id][$bln]["sum_return"] = 0;
//            }
//            $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;
//
//            $seller_data[$seller_id] = $seller_nama;
//        }
//        // arrPrintWebs($bln_rekening_datas);

//arrPrintHijau($bln_datas);
        $datas = array();
        $datas["sellers"] = $seller_data;
        $datas["penjualan"] = $bln_datas;
        $datas["penjualan_jenis"] = $bln_rekening_datas;

        return $datas;
    }

    public function cekSellerOrderBl()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $req_thn = isset($_GET['thn']) ? $_GET['thn'] : dtimeNow('Y');;

        // // -------------------------------------------------------------regular export project
        // $tbl = "z_sales_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "382spd"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // // showLast_query("merah");
        // // arrPrint($src_datas);
        // $bln_datas = array();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     // $bln_datas[$seller_id][$bln] = $src_data;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
        //         $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
        //         $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
        //         $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------batal
        // $tbl = "z_sales_batal_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "583spd"
        // );
        // $this->db->where_in("master_jenis", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
        //         $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------return
        // $tbl = "z_sales_return_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "982",
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
        //         $bln_datas[$seller_id][$bln]["sum_return"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }

        $src_penjualans = $this->callOrderPenjualans($req_thn);
//        showLast_query("biru");
        $seller_data = $src_penjualans['sellers'];
        $bln_datas = $src_penjualans['penjualan'];

        // arrPrint($bln_datas[77]);
//        matiHere(__LINE__);
        /*SELLER*/
        $this->load->model("Mdls/MdlEmployee");
        $cs = new MdlEmployee();

        $cs->setFilters(array());
        $em_condites = array(
            "jenis" => "seller",
            "trash" => "0",
            "status" => "1",
        );
        $src_sales = $cs->lookupByCondition($em_condites)->result();
        // showLast_query("kuning");

        // $seller_datas = array();
        foreach ($src_sales as $item) {
            $cu_id = $item->id;
            $cu_nama = $item->nama;
            $seller_data[$cu_id] = $cu_nama;
        }
        // $sellers = $seller_data + $customer_datas;
        // arrPrint($seller_data);
        $sellers = $seller_data;
        $headers = array(
            // "no" => array(),
            "nama" => array(
                "label" => "PIC",
            ),
        );

        $data = array(
            "mode" => "langsung_indek2",
            "title" => "Laporan order penjualan per salesman Tahun $req_thn",
            "subTitle" => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // // "color_bar"   => "box-info",
            // // "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "582",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(4),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $headers,
            "customer_datas" => $sellers,
            "bln_datas" => $bln_datas,
            "bln_nama" => namaBulan2(),
            "link_detil" => base_url() . "laporan/PenjualanCompare/viewDetil?thn=$req_thn",

            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            // "date_min"    => 1,
            // "date_max"    => dtimeNow('Y-m-d'),
            // "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_compare", $data);
    }

    public function vieworderpenjualan()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $year_now = dtimeNow('Y');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));
        $strTahun = isset($_GET['thn']) ? $_GET['thn'] : 0;
        // $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
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
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
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
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
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

        /*-------------------------------------------------------*/
        for ($i = 1; $i <= 3; $i++) {
            // echo $i;
            // echo "$thini  ";
            $thnya = $year_now--;
            $arrTahun[] = $thnya;
        }
        // arrPrint($arrTahun);
        $action = base_url() . "laporan/" . get_class($this) . "/vieworderpenjualan";
        $key = "?thn";
        $var = "";
        $var .= "<select id='tahun' class='btn btn-danger' onchange=\"location.href='$action" . $key . "='+this.value\">";
        $var .= "<option value=''>--pilih tahun--</option>";
        foreach ($arrTahun as $th) {
            $selected = $th == $strTahun ? "" : "";
            $var .= "<option value='$th' $selected>$th</option>";
        }
        $var .= "</select>";
        // $var .= " < klik unutk pilih tahun";
        // $var .= "<span class='input-group-btn'>";
        // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='$action?year='+document.getElementById('bulan').value\"><i class='fa fa-send-o'></i></button>";
        // // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='jjj?='+get.value\"><i class='fa fa-send-o'></i></button>";
        // $var .= $tambahan;
        // $var .= "</span>";
        // $var .= "</div>";
        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "";
        // $btn_td .= "<button type='button' class='btn btn-danger'disabled onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        // $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "&nbsp;" . $var;
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
        // $scriptBottom = "<script>$('#top_navigasi').fadeOut();</script>";
        $data = array(
            "mode" => "indek",
            "title" => "Laporan Order Penjualan ",
            "subTitle" => "Raw Data Sales Order",
            "modul_path" => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // navigasi
            "url" => base_url(uri_string()) . "$strGet",
            "data_id" => "permaster",
            "add_td" => $add_td,
            "date1" => $date1,
            "date2" => $date2,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
//            "sum_satu" => base_url() . "laporan/PenjualanCompare/cekSellerOrderBl" . "$strGet",
            "sum_satu" => base_url() . "laporan/PenjualanCompare2/cekSellerOrderBl" . "$strGet",
            // "sum_satu_2"   => base_url() . "laporan/PenjualanCompare/cekSellerBl2" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/cekpenjualancustomer" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",
            // "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
        );
        $this->load->view("laporan_compare", $data);
    }


    // order penjualan vs kirim------------------------------------
    protected function callOrderPacking($req_thn, $req_bln = "")
    {

        $reg_bln = array();
        if ($req_bln != "") {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }

        // ORDER PENJUALAN regular export project
        $tbl = "z_sales_salesman_cache";
        $condites = array(
                "periode" => "bulanan",
                "thn" => $req_thn,
            ) + $reg_bln;
        $this->db->where($condites);
        $rekenings = array(
//            "588spo", "582spo", "382spo"
            "588so", "582so", "382so"
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        showLast_query("merah");
        // arrPrint($src_datas);
        $order_bln_datas = array();
        $order_bln_rekening_datas = array();
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $rekening = $src_data->rekening;

            // $order_bln_datas[$seller_id][$bln] = $src_data;
            if (!isset($order_bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $order_bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $order_bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_order;

            if (!isset($order_bln_datas[$seller_id][$bln]["sum_debet"])) {
                $order_bln_datas[$seller_id][$bln]["sum_debet"] = 0;
            }
            $order_bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_order;

            if (!isset($order_bln_datas[$seller_id][$bln]["sum_closed"])) {
                $order_bln_datas[$seller_id][$bln]["sum_closed"] = 0;
            }
            $order_bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;

            if (!isset($order_bln_datas[$seller_id][$bln]["sum_reject"])) {
                $order_bln_datas[$seller_id][$bln]["sum_reject"] = 0;
            }
            $order_bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;

            $seller_data[$seller_id] = $seller_nama;
        }

        // ---------------------------------------------------------------

        //region PENJUALAN/PACKINGLIST
        $tbl = "z_sales_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("rekening", $rekenings);
        $src_datas = $this->db->get($tbl)->result();
        showLast_query("merah");
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $master_jenis = $src_data->master_jenis;
            $rekening_0 = $src_data->rekening;

            $rekening = $rekening_0;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
                $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;

            $seller_data[$seller_id] = $seller_nama;
        }
        //endregion


        // region PEMBATALAN PENJUALAN/PACKINGLIST
        $tbl = "z_sales_batal_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("master_jenis", $rekenings);
        $src_datas = $this->db->get($tbl)->result();
        showLast_query("merah");
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $master_jenis = $src_data->master_jenis;
            $rekening_0 = $src_data->rekening;


            $rekening = $rekening_0;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->batal_nilai;

            if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
                $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;

            $seller_data[$seller_id] = $seller_nama;
        }
        // endregion

        // ---------------------------------------------------------------

        $tbl = "z_sales_return_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "982",
        );
        $this->db->where_in("rekening", $rekenings);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
        showLast_query("merah");
        // arrPrintPink($src_datas);
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $rekening = $src_data->rekening;

            if (!isset($bln_rekening_datas[$seller_id][$bln][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
                $bln_datas[$seller_id][$bln]["sum_return"] = 0;
            }
            $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;

            $seller_data[$seller_id] = $seller_nama;
        }
        // arrPrintWebs($bln_rekening_datas);


        // menettokan order penjualan, masing-masing periode
        if (sizeof($order_bln_datas) > 0) {
            foreach ($order_bln_datas as $sellerID => $sellerSpec) {
                foreach ($sellerSpec as $bln => $blnSpec) {
                    $sum_debet = isset($blnSpec["sum_debet"]) ? $blnSpec["sum_debet"] : 0;
                    $sum_closed = isset($blnSpec["sum_closed"]) ? $blnSpec["sum_closed"] : 0;
                    $sum_reject = isset($blnSpec["sum_reject"]) ? $blnSpec["sum_reject"] : 0;
                    $sum_netto = $sum_debet - $sum_closed - $sum_reject;
                    $order_bln_datas[$sellerID][$bln]["sum_netto"] = $sum_netto;
                }
            }
        }

        // menettokan penjualan/packinglist, masing-masing periode
        if (sizeof($bln_datas) > 0) {
            foreach ($bln_datas as $sellerID => $sellerSpec) {
                foreach ($sellerSpec as $bln => $blnSpec) {
                    $sum_debet = isset($blnSpec["sum_debet"]) ? $blnSpec["sum_debet"] : 0;
                    $sum_batal = isset($blnSpec["sum_batal"]) ? $blnSpec["sum_batal"] : 0;
                    $sum_return = isset($blnSpec["sum_return"]) ? $blnSpec["sum_return"] : 0;
                    $sum_netto = $sum_debet - $sum_batal - $sum_return;
                    $bln_datas[$sellerID][$bln]["sum_netto"] = $sum_netto;
                }
            }
        }


//arrPrintHijau($order_bln_datas);
//arrPrintKuning($bln_datas);
//arrPrint($bln_rekening_datas);
//

        $datas = array();
        $datas["sellers"] = $seller_data;
        $datas["order_penjualan"] = $order_bln_datas;
        $datas["penjualan"] = $bln_datas;
        $datas["penjualan_jenis"] = $bln_rekening_datas;

        return $datas;
    }

    public function cekSellerOrderPackingBl()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $req_thn = isset($_GET['thn']) ? $_GET['thn'] : dtimeNow('Y');;

        // // -------------------------------------------------------------regular export project
        // $tbl = "z_sales_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "382spd"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // // showLast_query("merah");
        // // arrPrint($src_datas);
        // $bln_datas = array();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     // $bln_datas[$seller_id][$bln] = $src_data;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
        //         $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
        //         $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
        //         $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------batal
        // $tbl = "z_sales_batal_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "583spd"
        // );
        // $this->db->where_in("master_jenis", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
        //         $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------return
        // $tbl = "z_sales_return_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "982",
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
        //         $bln_datas[$seller_id][$bln]["sum_return"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }

        $src_penjualans = $this->callOrderPacking($req_thn);
//        showLast_query("biru");
        $seller_data = $src_penjualans['sellers'];
        $bln_datas = $src_penjualans['penjualan'];

        // arrPrint($bln_datas[77]);
//        matiHere(__LINE__);
        /*SELLER*/
        $this->load->model("Mdls/MdlEmployee");
        $cs = new MdlEmployee();

        $cs->setFilters(array());
        $em_condites = array(
            "jenis" => "seller",
            "trash" => "0",
            "status" => "1",
        );
        $src_sales = $cs->lookupByCondition($em_condites)->result();
        // showLast_query("kuning");

        // $seller_datas = array();
        foreach ($src_sales as $item) {
            $cu_id = $item->id;
            $cu_nama = $item->nama;
            $seller_data[$cu_id] = $cu_nama;
        }
        // $sellers = $seller_data + $customer_datas;
        // arrPrint($seller_data);
        $sellers = $seller_data;
        $headers = array(
            // "no" => array(),
            "nama" => array(
                "label" => "PIC",
            ),
        );

        $data = array(
            "mode" => "langsung_indek2",
            "title" => "Laporan order penjualan per salesman Tahun $req_thn",
            "subTitle" => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // // "color_bar"   => "box-info",
            // // "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "582",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(4),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $headers,
            "customer_datas" => $sellers,
            "bln_datas" => $bln_datas,
            "bln_nama" => namaBulan2(),
            "link_detil" => base_url() . "laporan/PenjualanCompare/viewDetil?thn=$req_thn",

            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            // "date_min"    => 1,
            // "date_max"    => dtimeNow('Y-m-d'),
            // "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_compare", $data);
    }

    // kirim side by side (bulan) misal 01-2021 vs 01-2022------------------------------------
    protected function callPackingCompared($req_thn, $req_bln = "")
    {

        $reg_bln = array();
        if ($req_bln != "") {
            $reg_bln = array(
                "bln" => $req_bln
            );
        }
        $req_thn_last = $req_thn -1;

        // ---------------------------------------------------------------

        //region PENJUALAN/PACKINGLIST
        $tbl = "z_sales_salesman_cache";
        $arrTahun = array(
            "$req_thn_last", // mundur 1 tahun
            "$req_thn" // tahun dipilih
        );
        $condites = array(
            "periode" => "bulanan",
//            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("rekening", $rekenings);
        $this->db->where_in("thn", $arrTahun);
        $src_datas = $this->db->get($tbl)->result();
//        showLast_query("merah");
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $thn = $src_data->thn;
            $master_jenis = $src_data->master_jenis;
            $rekening_0 = $src_data->rekening;

            $rekening = $rekening_0;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$thn][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$thn][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$thn][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln][$thn]["sum_debet"])) {
                $bln_datas[$seller_id][$bln][$thn]["sum_debet"] = 0;
            }
            $bln_datas[$seller_id][$bln][$thn]["sum_debet"] += $src_data->saldo_kirim;

            $seller_data[$seller_id] = $seller_nama;
        }
        //endregion
//arrPrintPink($src_datas);
//mati_disini(__LINE__ . " --- " . sizeof($src_datas));

        // region PEMBATALAN PENJUALAN/PACKINGLIST
        $tbl = "z_sales_batal_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
//            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "7499", "582spd", "382spd"
        );
        $this->db->where_in("master_jenis", $rekenings);
        $this->db->where_in("thn", $arrTahun);
        $src_datas = $this->db->get($tbl)->result();
//        showLast_query("merah");
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $thn = $src_data->thn;
            $master_jenis = $src_data->master_jenis;
            $rekening_0 = $src_data->rekening;


            $rekening = $rekening_0;
            if (!isset($bln_rekening_datas[$seller_id][$bln][$thn][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$thn][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$thn][$rekening] += $src_data->batal_nilai;

            if (!isset($bln_datas[$seller_id][$bln][$thn]["sum_batal"])) {
                $bln_datas[$seller_id][$bln][$thn]["sum_batal"] = 0;
            }
            $bln_datas[$seller_id][$bln][$thn]["sum_batal"] += $src_data->batal_nilai;

            $seller_data[$seller_id] = $seller_nama;
        }
        // endregion

        // ---------------------------------------------------------------

        $tbl = "z_sales_return_salesman_cache";
        $condites = array(
            "periode" => "bulanan",
//            "thn" => $req_thn,
        );
        $this->db->where($condites);
        $rekenings = array(
            "982",
        );
        $this->db->where_in("rekening", $rekenings);
        $this->db->where_in("thn", $arrTahun);
        // $this->db->group_by("seller_id,bln,thn");
        $src_datas = $this->db->get($tbl)->result();
//        showLast_query("merah");
        // arrPrintPink($src_datas);
        foreach ($src_datas as $src_data) {
            $seller_id = $src_data->seller_id;
            $seller_nama = $src_data->seller_nama;
            $bln = $src_data->bln;
            $thn = $src_data->thn;
            $rekening = $src_data->rekening;

            if (!isset($bln_rekening_datas[$seller_id][$bln][$thn][$rekening])) {
                $bln_rekening_datas[$seller_id][$bln][$thn][$rekening] = 0;
            }
            $bln_rekening_datas[$seller_id][$bln][$thn][$rekening] += $src_data->saldo_kirim;

            if (!isset($bln_datas[$seller_id][$bln][$thn]["sum_return"])) {
                $bln_datas[$seller_id][$bln][$thn]["sum_return"] = 0;
            }
            $bln_datas[$seller_id][$bln][$thn]["sum_return"] += $src_data->saldo_kirim;

            $seller_data[$seller_id] = $seller_nama;
        }
        // arrPrintWebs($bln_rekening_datas);


        // menettokan penjualan/packinglist, masing-masing periode
        if (sizeof($bln_datas) > 0) {
            foreach ($bln_datas as $sellerID => $sellerSpec) {
                foreach ($sellerSpec as $bln => $blnSpec) {
                    foreach($blnSpec as $thn => $thnSpec){
                        $sum_debet = isset($thnSpec["sum_debet"]) ? $thnSpec["sum_debet"] : 0;
                        $sum_batal = isset($thnSpec["sum_batal"]) ? $thnSpec["sum_batal"] : 0;
                        $sum_return = isset($thnSpec["sum_return"]) ? $thnSpec["sum_return"] : 0;
                        $sum_netto = $sum_debet - $sum_batal - $sum_return;
                        $bln_datas[$sellerID][$bln][$thn]["sum_netto"] = $sum_netto;
                    }
                }
            }
        }
//        arrPrintPink($bln_datas);

//arrPrintHijau($order_bln_datas);
//arrPrintKuning($bln_datas);
//arrPrint($bln_rekening_datas);
//

        $datas = array();
        $datas["sellers"] = $seller_data;
//        $datas["order_penjualan"] = $order_bln_datas;
        $datas["penjualan"] = $bln_datas;
        $datas["penjualan_jenis"] = $bln_rekening_datas;

        return $datas;
    }

    public function cekSellerPackingBlCompared()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $req_thn = isset($_GET['thn']) ? $_GET['thn'] : dtimeNow('Y');;
        $req_thn_last = $req_thn -1;

        // // -------------------------------------------------------------regular export project
        // $tbl = "z_sales_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "382spd"
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // // showLast_query("merah");
        // // arrPrint($src_datas);
        // $bln_datas = array();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     // $bln_datas[$seller_id][$bln] = $src_data;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_debet"])) {
        //         $bln_datas[$seller_id][$bln]["sum_debet"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_debet"] += $src_data->saldo_kirim;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_closed"])) {
        //         $bln_datas[$seller_id][$bln]["sum_closed"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_closed"] += $src_data->saldo_closed;
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_reject"])) {
        //         $bln_datas[$seller_id][$bln]["sum_reject"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_reject"] += $src_data->saldo_reject;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------batal
        // $tbl = "z_sales_batal_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "7499", "582spd", "583spd"
        // );
        // $this->db->where_in("master_jenis", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_batal"])) {
        //         $bln_datas[$seller_id][$bln]["sum_batal"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_batal"] += $src_data->batal_nilai;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }
        //
        // // ---------------------------------------------------------------return
        // $tbl = "z_sales_return_salesman_cache";
        // $condites = array(
        //     "periode" => "bulanan",
        //     "thn"     => $req_thn,
        // );
        // $this->db->where($condites);
        // $rekenings = array(
        //     "982",
        // );
        // $this->db->where_in("rekening", $rekenings);
        // // $this->db->group_by("seller_id,bln,thn");
        // $src_datas = $this->db->get($tbl)->result();
        // foreach ($src_datas as $src_data) {
        //     $seller_id = $src_data->seller_id;
        //     $seller_nama = $src_data->seller_nama;
        //     $bln = $src_data->bln;
        //
        //     if (!isset($bln_datas[$seller_id][$bln]["sum_return"])) {
        //         $bln_datas[$seller_id][$bln]["sum_return"] = 0;
        //     }
        //     $bln_datas[$seller_id][$bln]["sum_return"] += $src_data->saldo_kirim;
        //
        //     $seller_data[$seller_id] = $seller_nama;
        // }

        $src_penjualans = $this->callPackingCompared($req_thn);
//        showLast_query("biru");
        $seller_data = $src_penjualans['sellers'];
        $bln_datas = $src_penjualans['penjualan'];

        // arrPrint($bln_datas[77]);
//        matiHere(__LINE__);
        /*SELLER*/
        $this->load->model("Mdls/MdlEmployee");
        $cs = new MdlEmployee();

        $cs->setFilters(array());
        $em_condites = array(
            "jenis" => "seller",
            "trash" => "0",
            "status" => "1",
        );
        $src_sales = $cs->lookupByCondition($em_condites)->result();
        // showLast_query("kuning");

        // $seller_datas = array();
        foreach ($src_sales as $item) {
            $cu_id = $item->id;
            $cu_nama = $item->nama;
            $seller_data[$cu_id] = $cu_nama;
        }
        // $sellers = $seller_data + $customer_datas;
        // arrPrint($seller_data);
        $sellers = $seller_data;
        $headers = array(
            // "no" => array(),
            "nama" => array(
                "label" => "PIC",
            ),
        );
        $subHeaders = array(
            $req_thn_last, $req_thn
        );
        $data = array(
            "mode" => "langsung_indek2_compared",
            "title" => "Laporan order penjualan per salesman Tahun $req_thn",
            "subTitle" => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // // "color_bar"   => "box-info",
            // // "jenisTr"     => $this->jenisTr,
            // "jenisTr"     => "582",
            // "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(4),
            // "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $headers,
            "arrSubHeaders" => $subHeaders,
            "customer_datas" => $sellers,
            "bln_datas" => $bln_datas,
            "bln_nama" => namaBulan2(),
            "link_detil" => base_url() . "laporan/PenjualanCompare/viewDetil?thn=$req_thn",

            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            // "date_min"    => 1,
            // "date_max"    => dtimeNow('Y-m-d'),
            // "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_compare", $data);
    }

    // kirim side by side (tahun) misal 01-2021 vs 01-2022------------------------------------

}