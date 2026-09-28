<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Mutasi extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlCurrency");
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        $this->configUiModul = loadConfigUiModul_he_misc();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->bgTransaksiColor = bgTransaksiColor();
    }

    public function index()
    {
    }

    public function showData()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 20;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        //arrPrint($extHistoryKeterangan);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $gets = $_GET;
        // arrPrintHijau($gets);

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        // ---------------------------------------------------------------------main query
        $tbl_1 = "__raw_rek_pembantu__4010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        if ($gets['xid']) {
            $pihak_id = $gets['xid'];
            $condites = array(
                "pihak_id" => $pihak_id
            );
            $this->db->where($condites);
        }
        else{
            matiAlert("pilih salah satu konsumen");
        }

        $transaksi_jenis = array(
            "9822", "5822spd"
        );
        $this->db->where_in("jenis", $transaksi_jenis);

        if (isset($gets['date1']) && $gets['date1'] != "") {
            $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
            $this->db->where($wheres);
        }

        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $tagihans = $this->callPaymentSource();

        // -----------------------------------------------------------------------------------------------
        $tagihans = array();
        $counterSpd = $this->callTransaksiCounterJenis($jenis);
        // arrPrintKuning($tagihans);

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $spekProduks = $pr->callSpecs();


        $itemtambahan = array();
        $pihak_nama = "";
        foreach ($tmpA as $item) {
            $sub_nett1_include_ppn = $item['sub_nett1_include_ppn'];
            $id = $item['id'];


            $transaksi_produk_id = $item['produk_id'];
            $transaksi_no_1 = $item['transaksi_no_1'];
            $transaksi_id = $item['transaksi_id'];
            $transaksi_id_so = $item['transaksi_id_2'];
            $transaksi_sub_ppn_nilai = $item['sub_ppn_nilai'];
            $transaksi_dtime = $item['dtime'];
            $transaksi_jam = formatTanggal($transaksi_dtime, "H:i");
            $transaksi_tanggal = formatTanggal($transaksi_dtime, "d F Y");

            $transaksi_kredit = $item['kredit'];
            // $transaksi_ppn = $transaksi_kredit * (11/100);
            // $transaksi_inc_ppn = $transaksi_kredit + $transaksi_ppn;
            $transaksi_inc_ppn = $transaksi_kredit + $transaksi_sub_ppn_nilai;

            // $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            $pembayaran_nama = $item['pembayaran_nama'];
            $total_jendral = $item['kredit'] + $item['sub_ppn_nilai'] + $item['debet'];
            $itemtambahan['nett1_ppn'] = $item['harga'] + $item['ppn_nilai'];
            $datas[$id] = $total_jendral;
            // $itemtambahan['sub_nett1_include_ppn'] = $sub_nett1_include_ppn <= 0 ?  : "";


            $masterData[] = $item + $itemtambahan;
            if ($gets['xid']) {
                $pihak_nama = $item['pihak_nama'];
            }

        }

        // arrPrintHijau($datas);
        foreach ($datas as $idDb => $dataNew) {
            $where = array(
              "id" => $idDb,
                "sub_nett1_include_ppn" => 0
            );
            $dataNew = array(
               "sub_nett1_include_ppn" => $dataNew
            );
            $this->db->where($where);
            $this->db->update($tbl_1, $dataNew);
            // showLast_query("kuning");
        }

        // arrPrintHijau($masterData);

        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link"  => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        $arrHeaders = array(
            // "counter_jenis"     => array(
            //     "label" => "counter",
            //     // "format"     => "formatField_he_format",
            // ),
            "transaksi_id"      => array(
                "label" => "trid",
            ),
            "dtime"             => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            "gudang_nama_kirim" => array(
                "label" => "dikirim dari",
                // "format"     => "formatField_he_format",
            ),
            "delivery_nama"     => array(
                "label" => "logistik",
                // "format"     => "formatField_he_format",
            ),


            "oleh_nama"       => array(
                "label" => "pic",
            ),
            "pihak_nama"      => array(
                "label" => "konsumen",
            ),
            "transaksi_no"    => array(
                "label"      => "referensi",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "transaksi_no_1"    => array(
            //     "label" => "no. invoice",
            // ),
            // "transaksi_no_2"    => array(
            //     "label" => "no. SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"    => array(
            //     "label" => "no. PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"    => array(
            //     "label" => "no. PL",
            //     "type"  => "string",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            "pembayaran_nama" => array(
                "label" => "pembayaran",
            ),
            "kategori_nama"   => array(
                "label" => "kategori",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
            // "merek_nama"      => array(
            //     "label" => "merek",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            "produk_kode"     => array(
                "label" => "produk sku",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
            "produk_nama"     => array(
                "label" => "produk",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
            // "produk_id"          => array(
            //     "label" => "pID",
            // ),
            // "outdoor_nama"          => array(
            //     "label" => "outdoor",
            //     "type"  => "string",
            // ),
            // "indoor_nama_1"         => array(
            //     "label" => "intdoor",
            //     "type"  => "string",
            // ),

            "sub_diskon" => array(
                "label"      => "diskon",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            // -----------------------------
            "qty_kredit" => array(
                "label" => "jumlah jual",
                "type"  => "integer",
                "attr"  => "class='text-right bg-warning'",
            ),
            "qty_debet" => array(
                "label" => "jumlah return",
                "type"  => "integer",
                "attr"  => "class='text-right bg-warning'",
            ),
            // "hpp"     => array(
            //     "label"      => "hpp",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),
            "harga"      => array(
                "label"      => "harga per unit",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),

            // "harga_include_ppn"     => array(
            //     "label"      => "harga ppn",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            //
            //
            //

            "ppn_nilai" => array(
                "label"      => "ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
            ),
            "nett1_ppn" => array(
                "label"      => "harga inc. ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
            ),
            "kredit"    => array(
                "label"      => "nilai penjualan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            "debet"     => array(
                "label"      => "nilai return",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),

            "sub_ppn_nilai" => array(
                "label"      => "sub ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
            ),
            // ---------------------


            // "sub_harga_include_ppn"   => array(
            //     "label"      => "penjualan inc. ppn",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            "sub_nett1_include_ppn" => array(
                "label"      => "nilai total",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
            ),
            // "sub_harga_include_ppn" => array(
            //     "label"      => "sub harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "total_tagihan"  => array(
            //     "label"      => "tagihan inv.",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "total_terbayar" => array(
            //     "label"      => "dibayar inv.",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "sisa_tagihan"   => array(
            //     "label"      => "tagihan inv.",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
        );

        /* ---------------------------------------------
         * summary per-peran
         * ---------------------------------------------*/
        // $this->setNilaiKey("c_sub_total");
        // $kolomNilais = array(
        //     // "transaksi_id"   => array(
        //     //     "label" => "transaksi",
        //     //     "kolom" => array(
        //     //         "counter_spd"   => array(
        //     //             "label" => "counter"
        //     //         ),
        //     //         "transaksi_no"   => array(
        //     //             "label" => "no. pakinglist"
        //     //         ),
        //     //         "pihak_nama"     => array(
        //     //             "label" => "konsumen",
        //     //         ),
        //     //         "total_tagihan" => array(
        //     //             "label"   => "nilai penjualan",
        //     //             "summary" => true,
        //     //             "attr"    => "class='text-right'",
        //     //         ),
        //     //         "total_terbayar" => array(
        //     //             "label"   => "nilai",
        //     //             "summary" => true,
        //     //             "attr"    => "class='text-right'",
        //     //         ),
        //     //         "sisa_tagihan" => array(
        //     //             "label"   => "hutang",
        //     //             "summary" => true,
        //     //             "attr"    => "class='text-right'",
        //     //         ),
        //     //     ),
        //     // ),
        //     "transaksi_id"   => array(
        //         "label" => "transaksi",
        //         "kolom" => array(
        //             "counter_jenis"     => array(
        //                 "label" => "counter"
        //             ),
        //             "transaksi_tanggal" => array(
        //                 "label"  => "tanggal",
        //                 "format" => "formatField_he_format",
        //             ),
        //             "transaksi_jam"     => array(
        //                 "label" => "jam",
        //             ),
        //             "nomer_counter"     => array(
        //                 "label" => "no. invoice"
        //             ),
        //             "oleh_nama"         => array(
        //                 "label" => "pic",
        //             ),
        //             "pihak_nama"        => array(
        //                 "label" => "konsumen",
        //             ),
        //             // "total_tagihan" => array(
        //             //     "label"   => "nilai penjualan",
        //             //     "summary" => true,
        //             //     "attr"    => "class='text-right'",
        //             // ),
        //             // "total_terbayar" => array(
        //             //     "label"   => "nilai",
        //             //     "summary" => true,
        //             //     "attr"    => "class='text-right'",
        //             // ),
        //             "c_sub_total"       => array(
        //                 "label"   => "nilai penjualan",
        //                 "summary" => true,
        //                 "attr"    => "class='text-right'",
        //             ),
        //         ),
        //     ),
        //     "sales_admin_id" => false,
        //     "sales_man_id"   => false,
        //     "kategori_id"    => array(
        //         "label" => "kategori",
        //         "kolom" => "kategori_nama",
        //     ),
        //     "merek_nama"     => array(
        //         "label" => "merek",
        //         "kolom" => array(
        //             "merek_nama"  => array(
        //                 "label" => "merek"
        //             ),
        //             "qty_kredit"  => array(
        //                 "label"   => "qty",
        //                 "summary" => true,
        //             ),
        //             "c_sub_total" => array(
        //                 "label"   => "nilai",
        //                 "summary" => true,
        //             ),
        //         )
        //     )
        // );
        // $this->setKolomNilai($kolomNilais);
        // $arrSummary = $this->viewSummary($masterData);
        // // arrPrintPink($arrSummary);
        // $summariNilais = $arrSummary['nilai'];
        // $summariLabels = $arrSummary['label'];
        // $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = " " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        $data = array(
            // "mode"                 => "showData",
            "mode"        => "langsung_simple",
            "title"       => "Mutasi $pihak_nama " . $judul_lap,
            "subTitle"    => "Raw data penjualan",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"  => $arrHeaders,
            "master_data" => $masterData,
            // "summariNilais"        => $summariNilais,
            // "summariLabels"        => $summariLabels,
            // "summariSubjectLabels" => $summariSubjectLabels,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_satu",
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("mutasi", $data);
    }

    public function callTransaksiCounterJenis($jenis)
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
        );
        $this->db->select($coloms);
        $wheres = array(
            // "jenis" => "4822",
            "jenis" => $jenis,
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

    // rendering
    public function view()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $jenis = url_segment(4);
        $ygditampilkan = url_segment(5);
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = "";

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousDate($date2);
        // $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
        $date_stop_sebelumnya = date("Y-m-d", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("d F Y", strtotime($date_start_sebelumnya));
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
        $nama_bulan_setelah = date("d F Y", strtotime($date_start_setelahnya));

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
        $nama_bulan_ygtampil = formatTanggal($date2, "d F Y");
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
        // $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";


        $this->load->model("Mdls/MdlCustomer");
        $cu = new MdlCustomer();
        $this->db->order_by("nama", "asc");
        $srcCus = $cu->callSpecs();
        // arrPrintHijau($srcCus);
        $segmenAsli = $segmen = url_segment();
        $segmenUrlAsli = implode("/", $segmenAsli);

        $gets = $_GET;
        $gets[1] = 1;
        $getCuId = isset($gets['xid']) ? $gets['xid'] : "";
        unset($gets['xid']);
        $xtraHref = http_build_query($gets);

        $link_self = base_url() . $segmenUrlAsli . "?$xtraHref";
        $select_td = "&nbsp;<select data-style='btn btn-sm btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker' onchange=\"location.href='$link_self&xid=' + this.value\">";
        $select_td .= "<option value=''>---pilih customer----</option>";
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
        $add_td = "<td>$select_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strgetTotpl = $strget = $_GET;
        unset($strgetTotpl['date1']);
        unset($strgetTotpl['date2']);
        $strDate = "";
        if (isset($_GET['date1']) && ($date1 != $date2)) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        elseif ($date1 == $date2) {

            $strDate .= formatTanggal($get_date1, 'd F Y');
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $url = base_url(uri_string());
        $urlget = "?" . http_build_query($strgetTotpl);
        // cekHere($url);
        // cekHere($urlget);
        $urlData = base_url() . "penjualan/" . get_class($this) . "/$ygditampilkan/" . $jenis . "$strGet";
        // cekHere("$urlData");
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => callMenuLabel_he_menu(),
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => $url,
            "urlget"      => $urlget,
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => $urlData,
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("mutasi", $data);
    }
}
