<?php

class HutangKeKonsumen extends MX_Controller
{
    protected $transaksi_nilai;

    public function getTransaksiNilai()
    {
        return $this->transaksi_nilai;
    }

    public function setTransaksiNilai($transaksi_nilai)
    {
        $this->transaksi_nilai = $transaksi_nilai;
    }

    protected $nilai_key;

    public function getNilaiKey()
    {
        return $this->nilai_key;
    }

    public function setNilaiKey($nilai_key)
    {
        $this->nilai_key = $nilai_key;
    }

    protected $kolom_nilai;

    public function getKolomNilai()
    {
        return $this->kolom_nilai;
    }

    public function setKolomNilai($kolom_nilai)
    {
        $this->kolom_nilai = $kolom_nilai;
    }


    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("582spd", "982", "382spd");
    }

    public function callPaymentSource()
    {
        $tbl_1 = "_rek_pembantu_subcustomer_cache";
        $wheres = array(
            // "sisa>" => "1000",
            "periode"  => "forever",
            "rekening" => "2010050",
            // "extern2_id" => "2010050050",
        );
        $this->db->where($wheres);
        $extern2_ids = array(
            "2010050050", "2010050040",
        );
        $this->db->where_in("extern2_id", $extern2_ids);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        // arrPrintHijau($srcs);
        foreach ($srcs as $src) {
            $tr_id = $src['transaksi_id'];
            $sisa = $src['sisa'];

            // $tagihan[$tr_id] = $src;
            $tagihan[] = $src;
        }

        return $tagihan;

        // rek 2010050 hutangkekonsumen
    }

    public function callDetailSource()
    {
        $tbl_1 = "__rek_pembantu_subcustomer__2010050";
        $wheres = array(
            // "sisa>" => "1000",
            // "periode" => "forever",
            "rekening"   => "2010050",
            "extern2_id" => "2010050050",
        );
        $this->db->where($wheres);
        $this->db->order_by("extern_id,dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        // arrPrintHijau($srcs);
        foreach ($srcs as $src) {
            $tr_id = $src['transaksi_id'];
            $sisa = $src['sisa'];

            // $tagihan[$tr_id] = $src;
            $tagihan[] = $src;
        }

        return $tagihan;

        // rek 2010050 hutangkekonsumen
    }

    public function callTransaksiDueDate()
    {
        $tbl_1 = "transaksi_due_date";
        $wheres = array(// "sisa>" => 0,
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        // arrPrintHijau($srcs);
        foreach ($srcs as $src) {
            $tr_id = $src['transaksi_id'];
            $sisa = $src['sisa'];

            // $tagihan[$tr_id] = addPrefixKeyT_he_format($src);
            $tagihan[$tr_id] = $src;
        }

        return $tagihan;
    }

    public function callTransaksiCounterSpd()
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
        );
        $this->db->select($coloms);
        $wheres = array(
            "jenis" => "5822spd",
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

    public function callTransaksiInvoice()
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "nomer",
            "nomer2",
            "reference_id",
            "_company_stepCode",
            "_company_jenisTr",
        );
        $this->db->select($coloms);
        $wheres = array(
            "jenis" => "4822",
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['reference_id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

    public function cekRow_base_spd()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__4010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        $tagihans = $this->callPaymentSource();
        $counterSpd = $this->callTransaksiCounterSpd();

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $spekProduks = $pr->callSpecs();

        // arrPrintKuning($tagihans);
        $itemtambahan = array();
        foreach ($tmpA as $item) {
            $transaksi_produk_id = $item['produk_id'];
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

            if ($pembayaran_nama == "cash") {
                $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
                // $itemtambahan['due_date'] = $transaksi_dtime;
                // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
                $umur_d = 0;
                $itemtambahan['due_date'] = "-";
                $itemtambahan['umur_now'] = "-";

            }
            else {
                $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
                // $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
                // $dueDate = $tagihanDuedate['due_date'];
                // $umur_d = umurDay($dueDate);
                // $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
                // $itemtambahan['umur_now'] = $umur_d;
            }
            if ($transaksi_id == '27137') {

                // cekLime($transaksi_id);
                // arrPrintPink($tagihan['sisa']);
            }
            // break;
            $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;
            $itemtambahan['sisa_tagihan'] = $tagNilai;
            $itemtambahan['total_tagihan'] = isset($tagihan['tagihan']) ? $tagihan['tagihan'] : 0;;
            $itemtambahan['total_terbayar'] = isset($tagihan['terbayar']) ? $tagihan['terbayar'] : 0;;
            $itemtambahan['transaksi_tanggal'] = $transaksi_tanggal;
            $itemtambahan['transaksi_jam'] = $transaksi_jam;

            $itemtambahan['c_ppn'] = $transaksi_ppn;
            $itemtambahan['c_sub_total'] = $transaksi_inc_ppn;
            $counters = $counterSpd[$transaksi_id];
            $itemtambahan['counter_spd'] = $counters["_company_stepCode"];
            $produk_speks = $spekProduks[$transaksi_produk_id];
            $itemtambahan['merek_nama'] = $produk_speks->merek_nama;

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            // "dtime"                 => array(
            //     "label" => "tanggal",
            // ),
            "counter_spd"       => array(
                "label" => "counter",
                // "format"     => "formatField_he_format",
            ),
            "transaksi_tanggal" => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "transaksi_jam"     => array(
                "label" => "jam",
                // "format"     => "formatField_he_format",
            ),

            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),
            "pihak_nama"        => array(
                "label" => "konsumen",
            ),
            "transaksi_no_1"    => array(
                "label" => "no. spo",
            ),
            "transaksi_no_2"    => array(
                "label" => "no. SO",
                "type"  => "string",
            ),
            "transaksi_no_3"    => array(
                "label" => "no. PPL",
                "type"  => "string",
            ),
            "transaksi_no_4"    => array(
                "label" => "no. PL",
                "type"  => "string",
                // "attr"       => "class='text-right bg-warning'",
            ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            "pembayaran_nama"   => array(
                "label" => "pembayaran",
            ),
            // -------------------------------------------
            "merek_nama"        => array(
                "label" => "nama",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
            "produk_kode"       => array(
                "label" => "produk sku",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
            "produk_nama"       => array(
                "label" => "produk",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
            // "outdoor_nama"          => array(
            //     "label" => "outdoor",
            //     "type"  => "string",
            // ),
            // "indoor_nama_1"         => array(
            //     "label" => "intdoor",
            //     "type"  => "string",
            // ),
            "qty_kredit"        => array(
                "label" => "jumlah",
                "type"  => "integer",
                "attr"  => "class='text-right bg-warning'",
            ),
            "harga"             => array(
                "label"      => "harga per unit",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            "kredit"            => array(
                "label"      => "jumlah kena pajak",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            // "ppn_nilai"     => array(
            //     "label"      => "pajak nilai",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),

            /*----pajak ppn---*/
            // "c_ppn"     => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            "sub_ppn_nilai"  => array(
                "label"      => "pajak",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            // ---------------------
            "c_sub_total"    => array(
                "label"      => "total penjualan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),

            // "harga_include_ppn"     => array(
            //     "label"      => "harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "sub_harga_include_ppn" => array(
            //     "label"      => "sub harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            "total_tagihan"  => array(
                "label"      => "tagihan inv.",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "summary"    => false,
                // "attr"       => "class='text-right bg-warning'",
            ),
            "total_terbayar" => array(
                "label"      => "dibayar inv.",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "sisa_tagihan"   => array(
                "label"      => "tagihan inv.",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
        );

        /* ---------------------------------------------
         * summary per-peran
         * ---------------------------------------------*/
        $this->setNilaiKey("c_sub_total");
        $kolomNilais = array(
            // "transaksi_id"   => array(
            //     "label" => "transaksi",
            //     "kolom" => array(
            //         "counter_spd"   => array(
            //             "label" => "counter"
            //         ),
            //         "transaksi_no"   => array(
            //             "label" => "no. pakinglist"
            //         ),
            //         "pihak_nama"     => array(
            //             "label" => "konsumen",
            //         ),
            //         "total_tagihan" => array(
            //             "label"   => "nilai penjualan",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //         "total_terbayar" => array(
            //             "label"   => "nilai",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //         "sisa_tagihan" => array(
            //             "label"   => "hutang",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //     ),
            // ),
            "transaksi_id"  => array(
                "label" => "transaksi",
                "kolom" => array(
                    "counter_jenis"     => array(
                        "label" => "counter"
                    ),
                    "transaksi_tanggal" => array(
                        "label"  => "tanggal",
                        "format" => "formatField_he_format",
                    ),
                    "transaksi_jam"     => array(
                        "label" => "jam",
                    ),
                    "nomer_counter"     => array(
                        "label" => "no. invoice"
                    ),
                    "oleh_nama"         => array(
                        "label" => "pic",
                    ),
                    "pihak_nama"        => array(
                        "label" => "konsumen",
                    ),
                    // "total_tagihan" => array(
                    //     "label"   => "nilai penjualan",
                    //     "summary" => true,
                    //     "attr"    => "class='text-right'",
                    // ),
                    // "total_terbayar" => array(
                    //     "label"   => "nilai",
                    //     "summary" => true,
                    //     "attr"    => "class='text-right'",
                    // ),
                    "c_sub_total"       => array(
                        "label"   => "nilai penjualan",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                ),
            ),
            // "sales_admin_id" => false,
            // "sales_man_id" => false,
            "kategori_nama" => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
            "merek_nama"    => array(
                "label" => "merek",
                "kolom" => array(
                    "merek_nama"  => array(
                        "label" => "merek"
                    ),
                    "qty_kredit"  => array(
                        "label"   => "qty",
                        "summary" => true,
                    ),
                    "c_sub_total" => array(
                        "label"   => "nilai",
                        "summary" => true,
                    ),
                )
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "" . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        $data = array(
            "mode"                 => "langsung_simple",
            "title"                => "Laporan status " . $judul_lap,
            "subTitle"             => "Raw data pembelian",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("piutang", $data);
    }

    public function cekRow()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "4467";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $invoicies = $this->callTransaksiInvoice();
        // $counterJenis = $this->callTransaksiCounterJenis($jenis);
        // $tagihans = $this->callPaymentSource();
        $showObject = true;
        $key = isset($_GET['ky']) ? $_GET['ky'] : "id";
        if (isset($_GET[$key]) && $_GET[$key] > 0) {
            $condites = array(
                $key => $_GET[$key],
            );
            $this->db->where($condites);

            $showObject = false;
        }
        $tagihans = $this->callDetailSource();
        // showLast_query("kuning");
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // showLast_query("merah");

        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $spekProduks = $pr->callSpecs();

        // arrPrintKuning($tagihans);
        // matiHere(__LINE__);
        $itemtambahan = array();
        foreach ($tagihans as $item) {
            $transaksi_nomer = $item['nomer'];
            $transaksi_produk_id = $item['produk_id'];
            $transaksi_id = $item['transaksi_id'];
            $transaksi_id_so = $item['transaksi_id_2'];
            $transaksi_sub_ppn_nilai = $item['sub_ppn_nilai'];
            $transaksi_dtime = $item['dtime'];
            $transaksi_jam = formatTanggal($transaksi_dtime, "H:i");
            $transaksi_tanggal = formatTanggal($transaksi_dtime, "d F Y");
            $namaObjek = $showObject == false ? $item['extern_nama'] : "";
            $transaksi_kredit = $item['kredit'];
            // $transaksi_ppn = $transaksi_kredit * (11/100);
            // $transaksi_inc_ppn = $transaksi_kredit + $transaksi_ppn;
            $transaksi_inc_ppn = $transaksi_kredit + $transaksi_sub_ppn_nilai;

            // $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            $pembayaran_nama = $item['pembayaran_nama'];

            if ($pembayaran_nama == "cash") {
                $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
                // $itemtambahan['due_date'] = $transaksi_dtime;
                // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
                $umur_d = 0;
                $itemtambahan['due_date'] = "-";
                $itemtambahan['umur_now'] = "-";

            }
            else {
                $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
                $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
                $dueDate = $tagihanDuedate['due_date'];
                $umur_d = umurDay($dueDate);
                $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
                $itemtambahan['umur_now'] = $umur_d;
            }
            if ($transaksi_id == '27137') {

                // cekLime($transaksi_id);
                // arrPrintPink($tagihan['sisa']);
            }
            // break;
            $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;

            if ($umur_d == 0) {
                $umur_status = "0 hari";
            }
            elseif ($umur_d < 0) {
                $umur_status = "<g>" . ($umur_d * -1) . " hari</g>";
            }
            elseif ($umur_d > 0 && $tagNilai > 100) {
                $umur_status = "<r>telat " . ($umur_d) . " hari</r>";
            }
            else {
                $umur_status = "-";
            }
            $itemtambahan['umur_status'] = $umur_status;


            $itemtambahan['sisa_tagihan'] = $tagNilai;
            $itemtambahan['total_tagihan'] = isset($tagihan['tagihan']) ? $tagihan['tagihan'] : 0;;
            $itemtambahan['total_terbayar'] = isset($tagihan['terbayar']) ? $tagihan['terbayar'] : 0;;
            $itemtambahan['transaksi_tanggal'] = $transaksi_tanggal;
            $itemtambahan['transaksi_jam'] = $transaksi_jam;

            // $itemtambahan['c_ppn'] = $transaksi_ppn;
            // $itemtambahan['c_sub_total'] = $transaksi_inc_ppn;
            // $counters = $counterJenis[$transaksi_id];
            // $counterNum = $counters["_company_stepCode"];
            // $itemtambahan['counter_spd'] = $counterNum;
            // $itemtambahan['nomer_counter'] = $transaksi_nomer."-".$counterNum;
            // $produk_speks = $spekProduks[$transaksi_produk_id];
            // $itemtambahan['merek_nama'] = $produk_speks->merek_nama;

            // $invoice_data = $invoicies[$transaksi_id];
            // arrPrintHijau($invoice_data);
            // $count_inv = $invoice_data['_company_stepCode'];
            // $itemtambahan['nomer_invoice'] = $invoice_data['nomer2'] . "-$count_inv";

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders_0 = array();
        if ($showObject == true) {
            $arrHeaders_0 = array(
                "extern_id"   => array(
                    "label" => "id",
                ),
                "extern_nama" => array(
                    "label" => "konsumen",
                ),
            );
        }
        $arrHeaders = $arrHeaders_0 + array(
                // "counter_spd"       => array(
                //     "label" => "counter",
                //     // "format"     => "formatField_he_format",
                // ),
                // "transaksi_tanggal" => array(
                //     "label"  => "tanggal transaksi",
                //     "format" => "formatField_he_format",
                // ),
                // "transaksi_jam"     => array(
                //     "label" => "jam",
                //     // "format"     => "formatField_he_format",
                // ),
                // "tagihan_status"    => array(
                //     "label" => "tagihan",
                // ),
                "dtime" => array(
                    "label"      => "tanggal",
                    "format"     => "formatField_he_format",
                    "format_key" => "fulldate",
                ),
                // "jenis" => array(
                //     "label" => "jenis",
                //     // "data_order" => "due_date",
                //
                // ),
                // "transaksi_id"          => array(
                //     "label" => "trid",
                // ),

                // "nomer_counter" => array(
                //     "label" => "dr transaksi",
                // ),

                "transaksi_no" => array(
                    "label" => "nomer tr",

                ),
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
                // "pembayaran_nama"   => array(
                //     "label" => "pembayaran",
                // ),
                // // -------------------------------------------
                // "merek_nama"       => array(
                //     "label" => "nama",
                //     "type"  => "string",
                //     "attr"  => "class='bg-warning'",
                // ),
                // "produk_kode"       => array(
                //     "label" => "produk sku",
                //     "type"  => "string",
                //     "attr"  => "class='bg-warning'",
                // ),
                // "produk_nama"       => array(
                //     "label" => "produk",
                //     "type"  => "string",
                //     "attr"  => "class='bg-warning'",
                // ),
                // "outdoor_nama"          => array(
                //     "label" => "outdoor",
                //     "type"  => "string",
                // ),
                // "indoor_nama_1"         => array(
                //     "label" => "intdoor",
                //     "type"  => "string",
                // ),
                // "qty_kredit"        => array(
                //     "label" => "jumlah",
                //     "type"  => "integer",
                //     "attr"  => "class='text-right bg-warning'",
                // ),
                // "harga"             => array(
                //     "label"      => "harga per unit",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                //     "attr"       => "class='text-right bg-warning'",
                // ),
                // "kredit"            => array(
                //     "label"      => "jumlah kena pajak",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                //     "attr"       => "class='text-right bg-warning'",
                // ),
                // "ppn_nilai"     => array(
                //     "label"      => "pajak nilai",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                //     "attr"       => "class='text-right bg-danger'",
                // ),

                /*----pajak ppn---*/
                "debet"        => array(
                    "label"      => "debet",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "attr"       => "class='text-right bg-warning'",
                    "summary"    => true,
                ),

                "kredit"       => array(
                    "label"      => "kredit",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "attr"       => "class='text-right bg-warning'",
                    "summary"    => true,
                ),
                // // ---------------------
                // "c_sub_total"    => array(
                //     "label"      => "total penjualan",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                //     "attr"       => "class='text-right bg-warning'",
                // ),

                // "harga_include_ppn"     => array(
                //     "label"      => "harga jual",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                // ),
                // "sub_harga_include_ppn" => array(
                //     "label"      => "sub harga jual",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                // ),
                "kredit_akhir" => array(
                    "label"      => "saldo berjalan",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "summary"    => false,
                    // "attr"       => "class='text-right bg-warning'",
                ),
                // "total_terbayar" => array(
                //     "label"      => "dibayar",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                //     // "attr"       => "class='text-right bg-warning'",
                // ),
                // "sisa_tagihan"   => array(
                //     "label"      => "sisa tagihan",
                //     "format"     => "formatField_he_format",
                //     "format_key" => "harga",
                //     // "attr"       => "class='text-right bg-warning'",
                // ),
                "keterangan"   => array(
                    "label" => "keterangan",
                    // "format"     => "formatField_he_format",
                    // "format_key" => "harga",
                    // "attr"       => "class='text-right bg-warning'",
                ),
            );

        /* ---------------------------------------------
         * summary per-peran
         * ---------------------------------------------*/
        $this->setNilaiKey("c_sub_total");
        $kolomNilais = array(
            // "transaksi_id"   => array(
            //     "label" => "transaksi",
            //     "kolom" => array(
            //         "counter_spd"   => array(
            //             "label" => "counter"
            //         ),
            //         "transaksi_no"   => array(
            //             "label" => "no. pakinglist"
            //         ),
            //         "pihak_nama"     => array(
            //             "label" => "konsumen",
            //         ),
            //         "total_tagihan" => array(
            //             "label"   => "nilai penjualan",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //         "total_terbayar" => array(
            //             "label"   => "nilai",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //         "sisa_tagihan" => array(
            //             "label"   => "hutang",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //     ),
            // ),
            "transaksi_id"  => array(
                "label" => "transaksi",
                "kolom" => array(
                    "counter_jenis"     => array(
                        "label" => "counter"
                    ),
                    "transaksi_tanggal" => array(
                        "label"  => "tanggal",
                        "format" => "formatField_he_format",
                    ),
                    "transaksi_jam"     => array(
                        "label" => "jam",
                    ),
                    "nomer_counter"     => array(
                        "label" => "no. invoice"
                    ),
                    "oleh_nama"         => array(
                        "label" => "pic",
                    ),
                    "pihak_nama"        => array(
                        "label" => "konsumen",
                    ),
                    // "total_tagihan" => array(
                    //     "label"   => "nilai penjualan",
                    //     "summary" => true,
                    //     "attr"    => "class='text-right'",
                    // ),
                    // "total_terbayar" => array(
                    //     "label"   => "nilai",
                    //     "summary" => true,
                    //     "attr"    => "class='text-right'",
                    // ),
                    "c_sub_total"       => array(
                        "label"   => "nilai penjualan",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                ),
            ),
            // "sales_admin_id" => false,
            // "sales_man_id" => false,
            "kategori_nama" => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
            "merek_nama"    => array(
                "label" => "merek",
                "kolom" => array(
                    "merek_nama"  => array(
                        "label" => "merek"
                    ),
                    "qty_kredit"  => array(
                        "label"   => "qty",
                        "summary" => true,
                    ),
                    "c_sub_total" => array(
                        "label"   => "nilai",
                        "summary" => true,
                    ),
                )
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "" . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"        => "langsung_simple",
            "title"       => "Mutasi Hutang Ke Konsumen (Uang Muka/Lebih Bayar) <r>$namaObjek</r> " . $judul_lap,
            "subTitle"    => "Raw data",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
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
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("piutang", $data);
    }

    public function cekSumRow()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__4010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $this->db->group_by("transaksi_id");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        $tmpA = $this->callDetailSource();
        foreach ($tmpA as $item) {
            // arrPrintKuning($item);
            // matiHere(__LINE__);
            $extern_id = $item["extern_id"];

            if (!isset($jmlRow[$extern_id])) {
                $jmlRow[$extern_id] = 0;
            }
            $jmlRow[$extern_id] += 1;

        }
        // arrPrintPink($jmlRow);
        // matiHere(__LINE__);
        $tagihans = $this->callPaymentSource();
        // showLast_query("kuning");
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // $counterSpd = $this->callTransaksiCounterSpd();
        // arrPrintKuning(array_slice($tagihans,2));
        // arrPrintKuning(array_slice($tagihanDuedates,2));
        // arrPrintPink($tagihans);
        foreach ($tagihans as $item) {
            $transaksi_id = $item['transaksi_id'];
            $transaksi_id_so = $item['transaksi_id_2']; // cash
            $transaksi_dtime = $item['dtime'];
            $transaksi_jam = formatTanggal($transaksi_dtime, "H:i");
            $transaksi_tanggal = formatTanggal($transaksi_dtime, "d F Y");
            $pembayaran_nama = $item['pembayaran_nama'];
            $extern_id = $item["extern_id"];
            if ($pembayaran_nama == "cash") {
                $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
                // $itemtambahan['due_date'] = $transaksi_dtime;
                // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
                $umur_d = 0;
                $itemtambahan['due_date'] = "-";
                $itemtambahan['umur_now'] = "-";

            }
            else {
                $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
                $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
                $dueDate = $tagihanDuedate['due_date'];
                $umur_d = umurDay($dueDate);
                $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
                $itemtambahan['umur_now'] = $umur_d;
            }

            if ($umur_d == 0) {
                $umur_status = "0 hari";
            }
            elseif ($umur_d < 0) {
                $umur_status = "<g>" . ($umur_d * -1) . " hari</g>";
            }
            elseif ($umur_d > 0) {
                $umur_status = "<r>telat " . ($umur_d) . " hari</r>";
            }
            else {
                $umur_status = "-";
            }
            $itemtambahan['umur_status'] = $umur_status;

            if ($transaksi_id == '27137') {

                // cekLime($transaksi_id);
                // arrPrintPink($tagihan['sisa']);
            }
            // break;
            $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;
            $itemtambahan['sisa_tagihan'] = $tagNilai;
            $itemtambahan['total_tagihan'] = isset($tagihan['tagihan']) ? $tagihan['tagihan'] : 0;;
            $itemtambahan['total_terbayar'] = isset($tagihan['terbayar']) ? $tagihan['terbayar'] : 0;;
            $itemtambahan['dpp_ppn'] = isset($tagihan['dpp_ppn']) ? $tagihan['dpp_ppn'] : 0;;
            $itemtambahan['total_ppn'] = isset($tagihan['ppn']) ? $tagihan['ppn'] : 0;;
            $itemtambahan['transaksi_tanggal'] = $transaksi_tanggal;
            $itemtambahan['transaksi_jam'] = $transaksi_jam;
            $tagihan_status = $tagNilai < 1 ? "<g>lunas</g>" : "<r>belum bayar</r>";
            $itemtambahan['tagihan_status'] = $tagihan_status;
            $itemtambahan['jml_row'] = $jmlRow[$extern_id];

            $counters = $counterSpd[$transaksi_id];
            $itemtambahan['counter_spd'] = $counters["_company_stepCode"];
            // $itemtambahan['total_ppn'] = isset($tagihan['24103']) ? $tagihan['ppn'] : 0;;


            // cekHijau("$pembayaran_nama $transaksi_id");
            // $tempos = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
            // arrPrintPink($tempos);

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintHijau(array_slice($masterData,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));
        // cekBiru(blobDecode("czozNToiIFVhbmcgTXVrYSBLb25zdW1lbiBUYW5wYSBQcG4gQUxJTkUiOw=="));
        $arrHeaders = array(
            "extern_id"    => array(
                "label"   => "id",
                "summary" => true,
            ),
            "extern_nama"  => array(
                "label" => "konsumen",
                "links" => array(
                    //Ledger/viewMoveDetails/RekeningPembantuCustomerDetail/2010050/641/2010050050
                    //?o=1
                    //&main_ext2_id=641
                    //&blob_ext=czozNToiIFVhbmcgTXVrYSBLb25zdW1lbiBUYW5wYSBQcG4gQUxJTkUiOw==
                    // "target"  => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/2010050/{extern_id}/2010050050",
                    "target"    => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/2010050/{extern_id}/{extern2_id}",
                    // "target"  => "laporan/HutangKeKonsumen/cekRow",
                    "heading"   => "extern_nama",
                    "title"     => "anu",
                    "key"       => "extern_id",
                    "key_alt"   => "main_ext2_id",
                    "key_2"     => "extern2_id",
                    "key_alt_2" => "extern2_nama",
                ),
            ),
            "jml_row"      => array(
                "label" => "baris mutasi",
                // "summary" => true,
                "attr"  => "class='text-right'",
            ),
            "saldo_kredit" => array(
                "label"      => "saldo",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "data_order" => "saldo_kredit",
                "summary"    => true,
                // "collapsible" => "collapsible",

            ),
        );

        /* ---------------------------------------------
        * summary per-peran
        * ---------------------------------------------*/
        $this->setNilaiKey("total_terbayar");
        $kolomNilais = array(
            // "transaksi_id"   => array(
            //     "label" => "transaksi",
            //     "kolom" => array(
            //         "counter_spd"   => array(
            //             "label" => "counter"
            //         ),
            //         "transaksi_no"   => array(
            //             "label" => "no. pakinglist"
            //         ),
            //         "pihak_nama"     => array(
            //             "label" => "konsumen",
            //         ),
            //         "total_tagihan" => array(
            //             "label"   => "nilai penjualan",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //         "total_terbayar" => array(
            //             "label"   => "nilai",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //         "sisa_tagihan" => array(
            //             "label"   => "hutang",
            //             "summary" => true,
            //             "attr"    => "class='text-right'",
            //         ),
            //     ),
            // ),
            "transaksi_id"   => false,
            "tagihan_status" => array(
                "label" => "status",
                "kolom" => "tagihan_status",
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "" . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        // $judul_lap = "so";
        $data = array(
            // "mode"        => "langsung_indek",
            "mode"                 => "langsung_simple",
            "title"                => "Laporan Hutang Ke Konsumen (Uang Muka/Lebih Bayar/CN Pembatalan) " . $judul_lap,
            "subTitle"             => "Raw data pembelian",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            /* ----------------------------------------------------------------------------------------------
             * dimatikan untuk tidak menampilkan summary
             * ----------------------------------------------------------------------------------------------*/
            // "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            "top_navigasi"         => false,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        // $this->load->view("laporan", $data);
        $this->load->view("piutang", $data);
    }

    public function viewSummary($row_datas)
    {
        // arrPrint($row_datas);
        $arrSubjects = array(
            "pembayaran_nama" => "pembayaran_nama",
            "salesman_id"     => "salesman_nama",
            "pihak_id"        => "pihak_nama",
            "produk_id"       => "produk_nama",
        );

        $kolom_transaki_nilai = isset($this->nilai_key) ? $this->nilai_key : matiHere("tolong diset nilai_key");
        // $kolom_nilai = isset($this->kolom_nilai) ? $this->kolom_nilai : matiHere("tolong diset kolom_nilai");
        $kolom_nilai = isset($this->kolom_nilai) ? $this->kolom_nilai : array();

        $arrSubjects = array(
            "kategori_id"     => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
            "pembayaran_nama" => array(
                "label" => "cara pembayaran",
                "kolom" => "pembayaran_nama",
            ),
            "sales_admin_id"  => array(
                "label" => "sales admin",
                "kolom" => "sales_admin_nama",
            ),
            "salesman_id"     => array(
                "label" => "salesman",
                "kolom" => "salesman_nama",
            ),
            "pihak_id"        => array(
                "label" => "konsumen",
                "kolom" => "pihak_nama",
            ),
            "transaksi_id"    => array(
                "label" => "transaksi",
                "kolom" => array(
                    "transaksi_no"        => array(
                        "label" => "no. pakinglist"
                    ),
                    "pihak_nama"          => array(
                        "label" => "konsumen",
                    ),
                    $kolom_transaki_nilai => array(
                        "label"   => "nilai",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                ),
            ),
            "produk_id"       => array(
                "label" => "produk",
                "kolom" => array(
                    "produk_nama"         => array(
                        "label" => "produk"
                    ),
                    "qty_kredit"          => array(
                        "label"   => "jml",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                    $kolom_transaki_nilai => array(
                        "label"   => "nilai",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                )
            ),
        );

        if (isset($this->kolom_nilai)) {
            foreach ($kolom_nilai as $kolom => $nilai) {
                if (is_array($nilai)) {
                    $arrSubjects[$kolom] = $nilai;
                }
                else {
                    unset($arrSubjects[$kolom]);
                }
            }
        }


        if (ipadd() == "202.65.117.72") {
            $arrSubjects["jenis"] =
                array(
                    "label" => "jenis",
                    "kolom" => "jenis",
                );
        }
        foreach ($row_datas as $row_data) {
            // arrPrint($row_data);
            // break;
            // $sub_harga_include_ppn = $row_data['sub_harga_include_ppn'];
            // $sub_harga_include_ppn = $row_data['c_sub_total'];
            $sub_harga_include_ppn = $row_data[$kolom_transaki_nilai];

            foreach ($arrSubjects as $keySubject => $valSubject) {
                // arrPrint($valSubject);
                $subject = $row_data[$keySubject];

                $koloms = $valSubject['kolom'];
                $strSubject = $row_data[$koloms];
                // arrPrintKuning($koloms);
                if (is_array($koloms)) {
                    // arrPrint($koloms);
                    $strSubjectLabel[$keySubject] = $valSubject['label'];
                    foreach ($koloms as $strSubject => $kolomParams) {
                        // cekHere("$keySubject||$strSubject" );
                        $label = $kolomParams['label'];
                        $sub_strSubject = $row_data[$strSubject];
                        if (isset($kolomParams['summary']) && $kolomParams['summary'] == true) {


                            if (!isset($summary[$keySubject][$subject][$strSubject])) {
                                $summary[$keySubject][$subject][$strSubject] = 0;
                            }
                            $summary[$keySubject][$subject][$strSubject] += $sub_strSubject;
                        }
                        else {
                            $summary[$keySubject][$subject][$strSubject] = $sub_strSubject;
                        }

                        $strSummary[$keySubject]['header'][$strSubject] = $kolomParams;
                    }
                    // if (!isset($summary[$keySubject][$subject])) {
                    //     $summary[$keySubject][$subject] = 0;
                    // }
                    // $summary[$keySubject][$subject] += $sub_harga_include_ppn;

                }
                else {
                    if (!isset($summary[$keySubject][$subject])) {
                        $summary[$keySubject][$subject] = 0;
                    }
                    $summary[$keySubject][$subject] += $sub_harga_include_ppn;

                    $strSummary[$keySubject][$subject] = $strSubject;
                    $strSubjectLabel[$keySubject] = $valSubject['label'];
                }

            }
        }

        // arrPrintKuning($strSummary);
        // arrPrintKuning($summary);
        $summari_datas = array();
        $summari_datas['nilai'] = $summary;
        $summari_datas['label'] = $strSummary;
        $summari_datas['kolom_key'] = $strSubjectLabel;

        return $summari_datas;
        // $data = array(
        //     "mode"        => "viewSummary",
        //     "summary"        => $summary,
        //     "strSummary"        => $strSummary,
        // );
        //
        // $this->load->view("laporan_periode", $data);
    }

    // -----------------------------------------------------------------------
    // perender per-peran detile dari loader bisa dipakai umum
    public function perindekshow()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $month_now = dtimeNow('Y-m');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        if ($day_now < 14) {
            $date_start = $datemin;
        }
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
        if (dtimeToSecond($date2) <= dtimeToSecond($date_start_setelahnya)) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            // $btn_disabled = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= "<button type='button' class='btn btn-danger' $btn_disabled onclick=\"location . href = '$ulr_bulan_ini'\">bulan ini</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
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
            "title"       => callMenuLabel_he_menu(),
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/" . get_class($this) . "/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------

    /* ----------------------------------------------------
    * pengambilan data masih dr com
    * ----------------------------------------------------*/
    public function view()
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-d');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

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


        $add_td = "<td>$btn_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
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
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => "Penjualan $strDate",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            "sum_satu"    => base_url() . "laporan/PenjualanPeriode/viewHarian" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/cekpenjualancustomer" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",

        );
        $this->load->view("laporan", $data);
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
        $data = array(
            "mode"        => "indek",
            "title"       => "Laporan Aktifitas Penjualan",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanseller" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",

        );
        $this->load->view("laporan", $data);
    }

    public function test()
    {
        $jenisTr = $this->jenisTr_penjualan;
        $this->load->library("Bigdata");
        $bd = new Bigdata();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $bd->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $bd->setLimit($this->default_limit);
        }
        $bd->setJenistr($jenisTr);
        $src = $bd->callBdProdukAkunting();
        $masterData = $src['data'];
        $masterDataJml = $src['data_jml'];

        cekMerah(sizeof($masterData));
        // arrPrintKuning($masterData);
    }
}