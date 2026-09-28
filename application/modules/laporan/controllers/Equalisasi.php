<?php

class Equalisasi extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->jenisTr = "4666";
        $this->jenisTrs = array("582spo", "382spo");
        $this->default_limit = 100;

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
    }

    public function index()
    {
        $reg_tahun = "2021";
        // cekHere();
        /* ----------------------------------------------------------
         * pembelian __rek_master__hutang_dagang/kredit u/ nambah -> __rek_master__piutang_pembelian debet
         * lokal produk
         * 467-3463-343-1463 return-967
         * ppn diterbitkan __rek_master__ppn_in_realisasi
         *  lokal 111-113 supples 112-3113
         * ppn disetor
         *  ppn_in + pip - ppn_dibayar_bendahara negara
         * lokal supplies
         * 461 961
         * lokal import
         * 460 960
         *
         * ----------------------------------------------------------*/
        $piutang_dagang = array();
        //region hutang_dagang
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "hutang dagang",
            "thn"      => "2021",
        );
        $this->db->where($condites);
        $arrJenis = array(
            "467", "3463", "343", "1463"
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__hutang_dagang";
        $hutang_dagang = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($hutang_dagang);

        //region piutang_pembelian
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "hutang dagang",
            "jenis"    => "967",
            "thn"      => "2021",
        );
        $this->db->where($condites);
        $tableName = "__rek_master__piutang_pembelian";
        $piutang_pembelian = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($piutang_pembelian);
        $pembelian_netto = $hutang_dagang->kredit - $piutang_pembelian->debet;
        $pembelian["nama"] = "pembelian";
        $pembelian["nilai_transaksi"] = $pembelian_netto;

        //region ppn_diterbitkan
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "ppn in realisasi",
            "thn"      => "2021",
        );
        $this->db->where($condites);
        $arrJenis = array(
            "111", "113", "112", "3113"
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__ppn_in_realisasi";
        $ppn_diterbitkan = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($ppn_diterbitkan);
        $ppn_diterbitkan_pembelian = $ppn_diterbitkan->debet;
        $pembelian["ppn_diterbitkan"] = $ppn_diterbitkan_pembelian;

        // ppn dibayar bendahara negara __rek_master__ppn_dibayar_bendahara_negara
        //region ppn dibayar bendahara negara
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening"  => "ppn dibayar bendahara negara",
            "thn"       => "2021",
            "cabang_id" => "-1",
        );
        $this->db->where($condites);
        $arrJenis = array(
            "749"
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__ppn_dibayar_bendahara_negara";
        $ppn_dibayar_bendahara_negara = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($ppn_dibayar_bendahara_negara );
        $ppn_dibayar_bendahara_negara_pembelian = $ppn_dibayar_bendahara_negara->debet;
        $pembelian["ppn_disetor"] = $ppn_dibayar_bendahara_negara_pembelian;
        // __rek_master__ppn_out 114

        $pembelian["ppn_saldo"] = $ppn_diterbitkan->debet - $ppn_dibayar_bendahara_negara->debet;
        // --
        //region import
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "hutang dagang",
            "jenis"    => "460",
            "thn"      => "2021",
        );
        $this->db->where($condites);
        $tableName = "__rek_master__hutang_dagang";
        $hutang_dagng_import = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($hutang_dagng_import);
        $import_netto = $hutang_dagng_import->kredit * 1;
        $import["nama"] = "import";
        $import["nilai_transaksi"] = $import_netto;

        //__rek_master__pib
        //region pib_diterbitkan
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "pib",
            "thn"      => "2021",
        );
        $this->db->where($condites);
        $arrJenis = array(
            "682"
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__pib";
        $ppn_import_diterbitkan = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($ppn_diterbitkan);
        $import["ppn_diterbitkan"] = $ppn_import_diterbitkan->debet;
        $import["ppn_disetor"] = 0;
        $import["ppn_saldo"] = $ppn_import_diterbitkan->debet - 0;

        $total_transaksi["nama"] = "total pembelian";
        $total_transaksi["nilai_transaksi"] = $pembelian_netto + $import_netto;

        $total_ppn_diterbitkan["nama"] = "total faktur diterima";
        $total_ppn_diterbitkan["nilai_transaksi"] = 0;
        $total_ppn_diterbitkan["ppn_diterbitkan"] = $ppn_diterbitkan_pembelian;

        $total_ppn_disetor["nama"] = "total faktur disetor";
        $total_ppn_disetor["nilai_transaksi"] = 0;
        $total_ppn_disetor["ppn_diterbitkan"] = 0;
        $total_ppn_disetor["ppn_disetor"] = $ppn_dibayar_bendahara_negara_pembelian;

        $spacers["nama"] = "";
        $spacers["nilai_transaksi"] = 0;
        $spacers["ppn_diterbitkan"] = 0;
        $spacers["ppn_disetor"] = "";

        $masterData[] = $pembelian;
        $masterData[] = $import;
        $masterData[] = $total_transaksi;
        $masterData[] = $total_ppn_diterbitkan;
        $masterData[] = $total_ppn_disetor;
        $masterData[] = $spacers;

        /* ----------------------------------------------------------
         * penjualan netto __rek_master__penjualan __rek_master__return_penjualan
         * lokal
         * export
         * ----------------------------------------------------------*/
        $penjualan_nilai = $this->penjualan_netto($reg_tahun);
        $penjualan["nama"] = "penjualan reguler";
        $penjualan["nilai_transaksi"] = $penjualan_nilai;
        $masterData[] = $penjualan;

        $export_nilai = $this->export($reg_tahun);
        $export["nama"] = "export";
        $export["nilai_transaksi"] = $export_nilai;
        $masterData[] = $export;

        $total_transaksi_penjualan["nama"] = "total penjualan";
        $total_transaksi_penjualan["nilai_transaksi"] = $penjualan_nilai + $export_nilai;
        $total_transaksi_penjualan["ppn_diterbitkan"] = "-";
        $total_transaksi_penjualan["ppn_disetor"] = "-";
        $masterData[] = $total_transaksi_penjualan;

        $total_ppn_penjualan_diterbitkan["nama"] = "total faktur dibuat";
        $total_ppn_penjualan_diterbitkan["nilai_transaksi"] = "-";
        $total_ppn_penjualan_diterbitkan["ppn_diterbitkan"] = 0;
        $total_ppn_penjualan_diterbitkan["ppn_disetor"] = "-";
        $masterData[] = $total_ppn_penjualan_diterbitkan;

        $total_ppn_penjualan_disetor["nama"] = "total faktur dilaporkan";
        $total_ppn_penjualan_disetor["nilai_transaksi"] = "-";
        $total_ppn_penjualan_disetor["ppn_diterbitkan"] = "-";
        $total_ppn_penjualan_disetor["ppn_disetor"] = 0;
        $masterData[] = $total_ppn_penjualan_disetor;
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "nama"            => array(
                "label" => "nama",
            ),
            "nilai_transaksi" => array(
                "label"      => "nilai transaksi",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "ppn_default"     => array(
                "label"      => "default ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "ppn_diterbitkan" => array(
                "label"      => "Diterbitkan/diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "ppn_disetor"     => array(
                "label"      => "disetor",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "ppn_saldo"       => array(
                "label"      => "saldo",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
        );

        /* ----------------------------------------------------------
         *
         * ----------------------------------------------------------*/

        /* ----------------------------------------------------------
         *
         * ----------------------------------------------------------*/

        $data = array(
            "mode"        => "langsung",
            "title"       => "Equalisasi PPN -PIB -PEB 2021",
            "subTitle"    => "",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            // "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    private function penjualan_netto($tahun){
        //region penjualan reguler
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "penjualan",
            "thn"      => $tahun,
        );
        $this->db->where($condites);
        $arrJenis = array(
            "582spd",
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__penjualan";
        $penjualans = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($penjualans);

        //region return penjualan
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "return penjualan",
            "thn"      => "2021",
        );
        $this->db->where($condites);
        $arrJenis = array(
            "982",
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__return_penjualan";
        $returns= $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($returns);

        $penjualan_netto = $penjualans->kredit - $returns->debet;

        return $penjualan_netto;
    }

    private function ppn_default_penjualan($tahun){
        //region penjualan reguler
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "penjualan",
            "thn"      => $tahun,
        );
        $this->db->where($condites);
        $arrJenis = array(
            "582spd",
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__penjualan";
        $penjualans = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($penjualans);


        $penjualan_netto = $penjualans->kredit;

        return $penjualan_netto;
    }

    private function export($tahun){
        //region hutang_dagang
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "penjualan",
            "thn"      => $tahun,
        );
        $this->db->where($condites);
        $arrJenis = array(
            "382spd",
        );
        $this->db->where_in("jenis", $arrJenis);
        $tableName = "__rek_master__penjualan";
        $penjualans = $this->db->get($tableName)->row();
        //endregion
        // showLast_query("kuning");
        // arrPrint($penjualans);

        $penjualan_netto = $penjualans->kredit;

        return $penjualan_netto;
    }

    /* ----------------------------------------------------
     * pengambilan data sudah dengan library
     * ----------------------------------------------------*/
    // rendering complite
    public function vieweoutstanding()
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
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
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

        // cekMerah("$date_stop_setelahnya / $date_start_setelahnya > " . dtimeNow('Y-m-d'));
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
        //region jobloader untuk syncron data non akunting
        $url_target = base_url() . "Tool/";
        $loaders = array(
            // "auto_satu"  => $url_target . "generateNonAkuntingAllSales",
            // "auto_dua"   => $url_target . "generateNonAkuntingAllBatal",
            // "auto_tiga"  => $url_target . "generateNonAkuntingAllSalesExport",
            // "auto_empat" => $url_target . "generateNonAkuntingAllSalesReject",
        );
        //endregion
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => "Outstanding Sales Order Penjualan",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => "2022-01-01",
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/perindekshow/produkoutstandingbln" . "$strGet",
            "sum_satu"    => base_url() . "laporan/Crm/cekoutstandingseller" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/cekoutstanding" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
            "loader"      => $loaders,
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------
    // perender per-peran detile dari loader bisa dipakai umum
    public function perindekshow()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $month_now = dtimeNow('Y-m');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        // $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = $date_start;
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');
        // cekBiru("$date1 $date2");

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
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
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

        // cekMerah("$date_stop_setelahnya / $date_start_setelahnya > " . dtimeNow('Y-m-d'));
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
        $data = array(
            "mode"        => "indek",
            "title"       => "Outstanding Penjualan",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => "2022-01-01",
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/Equalisasi/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------
}