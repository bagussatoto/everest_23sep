<?php

class PenjualanPeriode extends MX_Controller
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

    public function getRaw($date1, $date2)
    {
        $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__4010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $transaksi_jenis = array(
            "9822", "5822spd"
        );
        $this->db->where_in("jenis", $transaksi_jenis);
        $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        return $tmpA;
    }

    public function viewHarian()
    {
        $title_now = isset($aktivitas) ? "Sales Order" : "Penjualan";
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');


        $master_data = $this->cekHarian();
        $arrHeaders = array(
            "dtime"             => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                // "format_key" => "fulldate",
                "format_key" => "dtime",
                "summary"    => false,
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => false,
                //     "target" => "laporan/Penjualan/ceknotaHarian",
                //     // "target" => "laporan/Crm/produkoutstandingbln",
                // ),
                // "links"      => array(
                //     "target"  => "laporan/Penjualan/ceknotaHarian",
                //     "title"   => "Transaksi hari",
                //     // "key"     => false,
                //     "key"     => "fulldate",
                //     "heading" => "fulldate",
                // ),
            ),
            "transaksi_no"      => array(
                "label"   => "nomer transaksi",
                "format"  => "formatField_he_format",
                // "format_key" => "fulldate",
                "summary" => false,
                // "links"   => array(
                //     "target"  => "laporan/Penjualan/ceknotaHarian",
                //     "title"   => "Transaksi hari",
                //     // "key"     => false,
                //     "key"     => "fulldate",
                //     "heading" => "fulldate",
                // ),
            ),
            "sales_admin_nama"  => array(
                "label"   => "PIC",
                "summary" => false,
            ),
            "salesman_nama"     => array(
                "label"   => "salesman",
                "summary" => false,
            ),
            "pihak_nama"        => array(
                "label"   => "konsumen",
                "summary" => false,
            ),
            "pembayaran_nama"   => array(
                "label"   => "cara pembayaran",
                "summary" => false,
            ),
            "delivery_nama"     => array(
                "label"   => "logistik",
                "summary" => false,
            ),
            "gudang_nama_kirim" => array(
                "label"   => "gudang",
                "summary" => false,
            ),
            // "description"         => array(
            //     "label"      => "catanan pembayaran",
            //     "summary"    => false,
            // ),

            "kategori_nama"         => array(
                "label"   => "kategori",
                "summary" => false,
            ),
            "produk_nama"           => array(
                "label"   => "produk",
                "summary" => false,
            ),
            // "outdoor_nama"         => array(
            //     "label"      => "outdoor",
            //     "summary"    => false,
            // ),
            // "indoor_nama_1"         => array(
            //     "label"      => "indoor",
            //     "summary"    => false,
            // ),
            "qty_kredit"            => array(
                "label"      => "kuantiti",
                "summary"    => true,
                "format"     => "formatField_he_format",
                "format_key" => "harga",
            ),
            "harga_include_ppn"     => array(
                "label"      => "harga satuan",
                "summary"    => true,
                "format"     => "formatField_he_format",
                "format_key" => "harga",
            ),
            "sub_harga_include_ppn" => array(
                "label"      => "sub total harga",
                "summary"    => true,
                "format"     => "formatField_he_format",
                "format_key" => "harga",
            ),
            // "new_sisa"         => array(
            //     "label"      => "kurang",
            //     "summary"    => true,
            // ),
        );
        $masterHeaders = array(
            "dtime" => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
                "summary"    => false,
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => false,
                //     "target" => "laporan/Penjualan/ceknotaHarian",
                //     // "target" => "laporan/Crm/produkoutstandingbln",
                // ),
                "links"      => array(
                    "target"  => "laporan/Penjualan/ceknotaHarian",
                    "title"   => "Transaksi hari",
                    // "key"     => false,
                    "key"     => "fulldate",
                    "heading" => "fulldate",
                ),
            ),
        );

        //----------------------summary
        $this->setNilaiKey("sub_harga_include_ppn");
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
                    "counter_jenis"         => array(
                        "label" => "counter"
                    ),
                    "transaksi_tanggal"     => array(
                        "label"  => "tanggal",
                        "format" => "formatField_he_format",
                    ),
                    "transaksi_jam"         => array(
                        "label" => "jam",
                    ),
                    "nomer_counter"         => array(
                        "label" => "no. invoice"
                    ),
                    "oleh_nama"             => array(
                        "label" => "pic",
                    ),
                    "pihak_nama"            => array(
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
                    "sub_harga_include_ppn" => array(
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
                    "merek_nama"            => array(
                        "label" => "merek"
                    ),
                    "qty_kredit"            => array(
                        "label"   => "qty",
                        "summary" => true,
                    ),
                    "sub_harga_include_ppn" => array(
                        "label"   => "nilai",
                        "summary" => true,
                    ),
                )
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($master_data);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];
        $summary_on_top = array(
            "nota"      => array(
                "label"     => "Jumlah Nota",
                "icon_fa"   => "paperclip",
                "nilai"     => "jml_nota",
                "nilai_tpl" => "{nilai} <small style=\'font-weight: lighter;\'>Lembar</small>",
            ),
            "penjualan" => array(
                "label"     => "nilai transaksi",
                "icon_fa"   => "dollar",
                "icon_bg"   => "bg-lime-active",

                // "icon_fa" => "money",
                "nilai"     => "nilai_transaksi",
                "nilai_tpl" => "{nilai} <small style=\'font-weight: lighter;\'>Rupiah</small>",
            ),
            "info"      => array(
                "label"     => "KLIK pada tanggal",
                "icon_fa"   => "info-circle",
                "icon_bg"   => "bg-gray-light",
                "col_lebar" => "col-xxl-3",

                // "icon_fa" => "money",
                // "nilai"     => "nilai_transaksi",
                "nilai_tpl" => "<small style=\'font-weight: lighter;\'>Untuk menampilkan data secara lebih detil</small>",
            ),
        );

        $subjek_0 = "";
        $judul_lap = "produk";
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1']) && ($date1 != $date2)) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        elseif ($date1 == $date2) {
            $strDate .= formatField_he_format("fulldate", $get_date1);
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }

        $data = array(
            // "mode"        => "produk_harian",
            "mode"                 => "langsung_simple",
            "title"                => "Data $title_now Per $subjek_0 $strDate ",
            "subTitle"             => $subTitlelap,
            "title_now"            => $title_now,
            "judul_lap"            => $judul_lap,
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data"          => $master_data,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            // "summary_on_top"  => $summary_on_top,
            // "margin_data" => isset($src_margins) ? $src_margins : array(),
            // "margin_avg"  => $margin_avg,
            // "qty_data"    => isset($src_qty) ? $src_qty : array(),
            // "total_bawah" => $total_bawah,
            "arrHeaders"           => $arrHeaders,
            "masterHeaders"        => $masterHeaders,
            // navigasi
            // "url"           => base_url(uri_string()) . "$strGet",
            // "aktivitas"     => $aktivitas,
            "subjek"               => $subjek_0,
            "strGet"               => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_periode", $data);
    }

    public function cekRow()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__4010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $transaksi_jenis = array(
            "9822", "5822spd"
        );
        $this->db->where_in("jenis", $transaksi_jenis);
        $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $tagihans = $this->callPaymentSource();
        $tagihans = array();
        $counterSpd = $this->callTransaksiCounterJenis($jenis);
        // arrPrintKuning($tagihans);

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $spekProduks = $pr->callSpecs();


        $itemtambahan = array();
        foreach ($tmpA as $item) {
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
            $counter_jenis = $counters["_company_stepCode"];
            $itemtambahan['counter_jenis'] = $counter_jenis;
            $itemtambahan['nomer_counter'] = $transaksi_no_1 . "-$counter_jenis";
            $produk_speks = $spekProduks[$transaksi_produk_id];
            $itemtambahan['merek_nama'] = $produk_speks->merek_nama;
            $itemtambahan['kategori_nama'] = $produk_speks->kategori_nama;
            // arrPrint($produk_speks);

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            "counter_jenis"     => array(
                "label" => "counter",
                // "format"     => "formatField_he_format",
            ),
            "transaksi_id"      => array(
                "label" => "trid",
            ),
            "transaksi_tanggal" => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "transaksi_jam"     => array(
                "label" => "jam",
                // "format"     => "formatField_he_format",
            ),
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
            "nomer_counter"   => array(
                "label" => "no. invoice",
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
            "merek_nama"      => array(
                "label" => "merek",
                "type"  => "string",
                "attr"  => "class='bg-warning'",
            ),
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
            "qty_kredit"      => array(
                "label" => "jumlah",
                "type"  => "integer",
                "attr"  => "class='text-right bg-warning'",
            ),
            "sub_diskon"      => array(
                "label"      => "diskon",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),

            "harga"  => array(
                "label"      => "harga per unit",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            "kredit" => array(
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

            "sub_ppn_nilai" => array(
                "label"      => "pajak",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            // ---------------------
            "c_sub_total"   => array(
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
            "transaksi_id"   => array(
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
            "sales_admin_id" => false,
            "sales_man_id"   => false,
            "kategori_id"    => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
            "merek_nama"     => array(
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
            "title"                => "Laporan pengiriman " . $judul_lap,
            "subTitle"             => "Raw data penjualan",
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
        $this->load->view("penjualanPeriode", $data);
    }

    public function cekSumRow()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $this->db->group_by("transaksi_id");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        $tmpA = $this->getRaw($date1, $date2);

        // showLast_query("biru");
        // arrPrint(array_slice($tmpA,1,2));
        // $tagihans = $this->callPaymentSource();
        // $tagihanDuedates = $this->callTransaksiDueDate();
        $counterSpd = $this->callTransaksiCounterJenis($jenis);
        // arrPrintKuning(array_slice($tagihans,2));
        // arrPrintKuning(array_slice($tagihanDuedates,2));
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $spekProduks = $pr->callSpecs();

        foreach ($tmpA as $item) {
            $transaksi_produk_id = $item['produk_id'];
            $transaksi_id = $item['transaksi_id'];
            $transaksi_id_so = $item['transaksi_id_2']; // cash
            $transaksi_dtime = $item['dtime'];
            $transaksi_jam = formatTanggal($transaksi_dtime, "H:i");
            $transaksi_tanggal = formatTanggal($transaksi_dtime, "d F Y");
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

            $counters = $counterSpd[$transaksi_id];
            $itemtambahan['counter_spd'] = $counters["_company_stepCode"];
            // $itemtambahan['total_ppn'] = isset($tagihan['24103']) ? $tagihan['ppn'] : 0;;
            $produk_speks = $spekProduks[$transaksi_produk_id];
            $itemtambahan['merek_id'] = $produk_speks->merek_id;
            $itemtambahan['merek_nama'] = $produk_speks->merek_nama;
            $itemtambahan['kategori_nama'] = $produk_speks->kategori_nama;


            // cekHijau("$pembayaran_nama $transaksi_id");
            // $tempos = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
            // arrPrintPink($tempos);

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintPink(array_slice($masterData, 1, 1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));

        $arrHeaders = array(
            "label" => array(
                "label" => "merek",
                // "format"     => "formatField_he_format",
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "master_id",
                //     "target" => "laporan/PenjualanPeriode/produkSub",
                // ),
            ),

            "total_qty_kredit"      => array(
                "label"      => "total qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sub_harga_include_ppn" => array(
                "label"      => "total nilai",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "rincian"               => array(
                "label" => "type",
                "sub"   => array(
                    "label"     => array(// "label" => 'produk'
                    ),
                    "total"     => array(
                        "label"      => 'qty',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    ),
                    "sub_nilai" => array(
                        "label"      => 'sub nilai',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    )
                )
            ),
            // "sub" => array(
            //     "label"  => "qty",
            //     "sub" => array(
            //
            //     )
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // "transaksi_id"      => array(
            //     "label" => "trid",
            // ),
            // "pihak_nama"        => array(
            //     "label" => "konsumen",
            // ),
            // "due_date"          => array(
            //     "label"      => "tanggal jatuh tempo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "fulldate",
            // ),
            // "tagihan_status"    => array(
            //     "label" => "tagihan",
            //     // "format"     => "formatField_he_format",
            //     // "format_key" => "fulldate",
            // ),
            // "umur_status"       => array(
            //     "label" => "overdue",
            // ),
            // "transaksi_no_1"    => array(
            //     "label" => "no. spo",
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
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // // ----------
            // // "produk_kode"           => array(
            // //     "label" => "produk sku",
            // //     "type"  => "string",
            // // ),
            // // "produk_nama"           => array(
            // //     "label" => "produk",
            // //     "type"  => "string",
            // // ),
            // // "outdoor_nama"          => array(
            // //     "label" => "outdoor",
            // //     "type"  => "string",
            // // ),
            // // "indoor_nama_1"         => array(
            // //     "label" => "intdoor",
            // //     "type"  => "string",
            // // ),
            // // "qty_kredit"            => array(
            // //     "label" => "jumlah",
            // //     "type"  => "integer",
            // // ),
            // // "harga_include_ppn"     => array(
            // //     "label"      => "harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // // "sub_harga_include_ppn" => array(
            // //     "label"      => "sub harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // //-----------------
            // "dpp_ppn"           => array(
            //     "label"      => "jml kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_ppn"         => array(
            //     "label"      => "total pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_tagihan"     => array(
            //     "label"      => "total",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_terbayar"    => array(
            //     "label"      => "pembayaran",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "sisa_tagihan"      => array(
            //     "label"      => "sisa tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
        );

        $pivotDatas = $this->creatPivot($masterData);

        // arrPrintHijau($pivotDatas);

        // matiHere(__LINE__);
        /* ---------------------------------------------
        * summary per-peran
        * ---------------------------------------------*/
        $this->setNilaiKey("sub_harga_include_ppn");
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
            "mode"                 => "pivot",
            "title"                => "Laporan penjualan versi packinglist " . $judul_lap,
            "subTitle"             => "Raw data pembelian",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            // "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            "pivotDatas"           => $pivotDatas,
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
        // $this->load->view("laporan", $data);
        $this->load->view("penjualanPeriode", $data);
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

    public function creatPivot($data)
    {
        // Fungsi untuk membuat laporan pivot

        $pivot = array();

        foreach ($data as $row) {
            $transaksi_id = $row['transaksi_id'];
            $transaksi_no = $row['transaksi_no'];
            $merek_nama = $row['merek_nama'];
            $produk_nama = $row['produk_nama'];
            $produk_id = $row['produk_id'];
            $qty_kredit = $row['qty_kredit'];
            $sub_harga_include_ppn = $row['sub_harga_include_ppn'];
            $harga = $row['harga'];
            $harga = $row['c_sub_total'];

            if (!isset($pivot[$merek_nama])) {
                $pivot[$merek_nama] = array(
                    // 'total_'.$produk_nama => 0,
                    'total_qty_kredit'      => 0,
                    'total_harga'           => 0,
                    'count'                 => 0,
                    'sub_harga_include_ppn' => 0,
                );
            }

            $pivot[$merek_nama]['sub_harga_include_ppn'] += $sub_harga_include_ppn;
            $pivot[$merek_nama]['total_qty_kredit'] += $qty_kredit;
            $pivot[$merek_nama]['total_harga'] += $harga;
            $pivot[$merek_nama]['count']++;

            $pivot[$merek_nama]['label'] = $merek_nama;

            // if (!isset($pivot[$merek_nama]['total_' . $produk_id]['total'])) {
            //     $pivot[$merek_nama]['total_' . $produk_id]['total'] = 0;
            // }
            // $pivot[$merek_nama]['total_' . $produk_id]['total'] += $qty_kredit;
            // $pivot[$merek_nama]['total_' . $produk_id]['label'] = $produk_nama;
            // $pivot[$merek_nama]['total_' . $produk_id]['transaksi'][$transaksi_no]['qty_kredit'] = $qty_kredit;
            // $pivot[$merek_nama]['total_' . $produk_id]['transaksi'][$transaksi_no]['transaksi_id'] = $transaksi_id;

            if (!isset($pivot[$merek_nama]['rincian'][$produk_id]['sub_nilai'])) {
                $pivot[$merek_nama]['rincian'][$produk_id]['sub_nilai'] = 0;
            }
            $pivot[$merek_nama]['rincian'][$produk_id]['sub_nilai'] += $sub_harga_include_ppn;

            if (!isset($pivot[$merek_nama]['rincian'][$produk_id]['total'])) {
                $pivot[$merek_nama]['rincian'][$produk_id]['total'] = 0;
            }
            $pivot[$merek_nama]['rincian'][$produk_id]['total'] += $qty_kredit;
            $pivot[$merek_nama]['rincian'][$produk_id]['label'] = $produk_nama;
            $pivot[$merek_nama]['rincian'][$produk_id]['transaksi'][$transaksi_no]['qty_kredit'] = $qty_kredit;
            $pivot[$merek_nama]['rincian'][$produk_id]['transaksi'][$transaksi_no]['transaksi_id'] = $transaksi_id;

        }

        return $pivot;

    }

    public function cekHarian()
    {
        $dtime_now = $dtime_now_1 = dtimeNow('Y-m-d');
        $jenis = $this->uri->segment(3);
        $jenis = '5822spo';

        $old = $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $date_1 = isset($_GET['date1']) && (strlen($_GET['date1']) > 0) ? $_GET['date1'] : $dtime_now_1;
        $date_2 = isset($_GET['date2']) && (strlen($_GET['date2']) > 0) ? $_GET['date2'] : $dtime_now;


        $tbl_1 = "__raw_rek_pembantu__4010";
        $transaksi_jenis = array(
            "9822", "5822spd"
        );
        $this->db->where_in("jenis", $transaksi_jenis);
        $condites = array(
            "date(dtime)>=" => $date_1,
            "date(dtime)<=" => $date_2,
            // "jenis" => "5822spd",
        );

        $this->db->where($condites);
        $src = $this->db->get($tbl_1)->result_array();
        // showLast_query("merah");
        // arrPrintKuning($src);

        return $src;
    }

    public function cekHarian_registry()
    {
        $dtime_now = $dtime_now_1 = dtimeNow('Y-m-d');
        $jenis = $this->uri->segment(3);
        $jenis = '5822spo';
        $old = $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $date_1 = isset($_GET['date1']) && (strlen($_GET['date1']) > 0) ? $_GET['date1'] : $dtime_now_1;
        $date_2 = isset($_GET['date2']) && (strlen($_GET['date2']) > 0) ? $_GET['date2'] : $dtime_now;


        //region init Y == produk
        // $this->load->model("Mdls/MdlTransaksi");
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();


        // $tr->addFilter("dtime>='" . $date_1 . "'");
        // $tr->addFilter("dtime<='" . $date_2 . "'");

        $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date_1' AND DATE(dtime) <= '$date_2'";
        $tmpA = $tr->lookupByCondition($wheres)->result();
        cekLime($this->db->last_query());
        cekHere(count($tmpA));
        // matiHere();
        $aKoloms = array(
            "nomer",
            "dtime",
            "oleh_nama",
            "trash_4",
        );
        $trIds = array();
        if (sizeof($tmpA) > 0) {
            $trIds_0 = array();
            foreach ($tmpA as $item) {
                $params = array();
                foreach ($aKoloms as $aKolom) {
                    $$aKolom = $item->$aKolom;
                    if ($old == "old") {
                        if ($aKolom == "nomer") {
                            $params[$aKolom] = formatTransNomer($item->$aKolom, $jenis, -1);
                        }
                        else {
                            $params[$aKolom] = formatField2($aKolom, $item->$aKolom);
                        }
                    }
                    else {

                        $params[$aKolom] = formatField2($aKolom, $item->$aKolom);
                    }
                }
                $trIds_0[$item->id] = 1;

                $trIdParams[$item->id] = $params;
            }
            $trIds = array_keys($trIds_0);
        }

        $trIdList = implode("','", $trIds);
        switch ($old) {
            default:
                /* ===================================================
                 * untuk menapilkan data tambahan::
                 * 1) tambahan pada array kolom
                 * 2) tambahakan padaa header excel
                 * ----------------------------------------*/

                $bKoloms = array(
                    // "transaksi_id",
                    "jml",
                    "sub_harga",
                    "sub_nett1",
                    "sub_ppn",
                    "sub_nett2",
                    "nama",
                    "produk_kode",
                    "pihakName",
                );
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id in ('$trIdList')");
                //                $tr->addFilter("param='items'");
                $tr->setJointSelectFields("items, transaksi_id");
                //                $tmpB = $tr->lookupRegistries()->result();
                $tmpB = $tr->lookupDataRegistries()->result();
                cekMerah($this->db->last_query());

                $cKoloms = array(
                    "customerDetails__kabupaten",
                    "salesmanDetails__nama",
                    // "billingDetails__propinsi",
                );
                //                $tr->addFilter("param='main'");
                $tr->setJointSelectFields("main, transaksi_id");
                //                $tmpC = $tr->lookupRegistries()->result();
                $tmpC = $tr->lookupDataRegistries()->result();
                showLast_query("kuning");
                foreach ($tmpC as $cItems) {
                    // arrPrint($cItems);
                    $cTransaksi_id = $cItems->transaksi_id;
                    $cValues = blobDecode($cItems->main);
                    // arrPrint($cValues);
                    // matiHere();
                    foreach ($cKoloms as $cKolom) {
                        $$cKolom = $cValues[$cKolom];
                        $main_params[$cTransaksi_id][$cKolom] = $cValues[$cKolom];
                    }
                    // $cValues = $cItems;
                }

                $trIdItems = array();
                foreach ($tmpB as $items) {
                    $transaksi_id = $items->transaksi_id;
                    $values = blobDecode($items->items);
                    // arrPrintPink($values);

                    foreach ($values as $value) {
                        $params = array();
                        foreach ($cKoloms as $cKolom) {
                            $params[$cKolom] = $main_params[$transaksi_id][$cKolom];
                        }
                        foreach ($bKoloms as $bKolom) {
                            $$bKolom = $value[$bKolom];
                            $params[$bKolom] = $value[$bKolom];
                        }
                        // cekHijau($transaksi_id);
                        foreach ($aKoloms as $aKolom) {

                            // $params[$aKolom] = 0;
                            $params[$aKolom] = $trIdParams[$transaksi_id][$aKolom];
                        }


                        $trIdItems[] = $params;
                    }
                    //                     arrPrint($main_params);
                    //                    mati_disini();
                    $headers = array(
                        // "no"          => array(
                        //     "label" => "No",
                        //     "type"  => "integer",
                        // ),
                        "nomer"                      => array(
                            "label" => "INV",
                            "type"  => "string",
                        ),
                        "dtime"                      => array(
                            "label" => "tanggal",
                            "type"  => "string",
                        ),
                        "nama"                       => array(
                            "label" => "Model",
                            "type"  => "string",
                        ),
                        "produk_kode"                => array(
                            "label" => "type",
                            "type"  => "string",
                        ),
                        "pihakName"                  => array(
                            "label" => "Customer",
                            "type"  => "string",
                        ),
                        "customerDetails__kabupaten" => array(
                            "label" => "Kota",
                            "type"  => "string",
                        ),
                        "oleh_nama"                  => array(
                            "label" => "Person",
                            "type"  => "string",
                        ),
                        "salesmanDetails__nama"      => array(
                            "label" => "salesman",
                            "type"  => "string",
                        ),
                        // "billingDetails__propinsi"        => array(
                        //     "label" => "Prop",
                        //     "type"  => "string",
                        // ),
                        "jml"                        => array(
                            "label" => "Qty",
                            "type"  => "integer",
                        ),
                        "sub_harga"                  => array(
                            "label" => "Price",
                            "type"  => "integer",
                        ),
                        "sub_nett1"                  => array(
                            "label" => "DPP",
                            "type"  => "integer",
                        ),
                        //                        "sub_ppn" => array(
                        //                            "label" => "PPN",
                        //                            "type" => "integer",
                        //                        ),
                        //                        "sub_nett2" => array(
                        //                            "label" => "TOTAL",
                        //                            "type" => "integer",
                        //                        ),
                        "trash_4"                    => array(
                            "label" => "STATUS",
                            "type"  => "text",
                        ),
                    );
                }

                arrPrint($trIdItems);


                return $trIdItems;
                //--
                //                 $tbl_1 = "transaksi";
                //                 $tbl_2 = "transaksi_data_registry";
                //                 $selectedKoloms = array(
                //                     "$tbl_1.id",
                //                     "$tbl_1.dtime",
                //                     "$tbl_2.main",
                //                     "$tbl_2.items"
                //                 );
                //                 // $this->db->select("$tbl_1.*, $tbl_2.main, $tbl_2.items");
                //                 $this->db->select($selectedKoloms);
                //                 $this->db->from($tbl_1);
                //                 $this->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id");
                //                 $wheres = "$tbl_1.jenis='$jenis' AND DATE($tbl_1.dtime) >= '$date_1' AND DATE($tbl_1.dtime) <= '$date_2'";
                //                 $this->db->where($wheres); // Ganti $id dengan nilai yang sesuai
                //                 $query = $this->db->get();
                //
                //                 $result = $query->result();
                //                 showLast_query("merah");
                //                 cekHijau(count($result));
                //                 // arrPrintWebs($result);
                //                 foreach ($result as $item) {
                //
                //                     $main = blobDecode($item->main);
                //                     $items = blobDecode($item->items);
                // cekHere($item->id);
                //                     arrPrint($main);
                //                     arrPrintHijau($items);
                //                 }
                //--
                break;
            case "old":
                $bKoloms = array(
                    "transaksi_id",
                    "produk_ord_jml",
                    "produk_hrg_ori",
                    "produk_ord_diskon",
                    "produk_ord_hrg",
                    "sub_nett1",
                    "ppn",
                    "sub_nett2",
                    "produk_nama",
                    "produk_kode",
                    // "pihakName",
                );
                $tr->setTableName("transaksi_data");
                $tr->setFilters(array());
                // $this->db->order_by('id');
                $tmpDatas = $tr->lookupByCondition("produk_jenis='produk' AND
                transaksi_id in ('" . $trIdList . "') AND trash='0' AND produk_ord_jml>'0'")->result();
                // cekMerah($this->db->last_query());
                // arrPrint($tmpDatas);
                foreach ($tmpDatas as $tmpData) {
                    $params = array();
                    foreach ($bKoloms as $bKolom) {
                        $$bKolom = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;

                        $sParams[$bKolom] = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;
                    }
                    $rParams['ppn'] = ($produk_ord_jml * $ppn);
                    $rParams['sub_nett1'] = ($produk_ord_jml * $produk_ord_hrg);
                    $rParams['sub_nett2'] = ($produk_ord_jml * $produk_ord_hrg) + ($produk_ord_jml * $ppn);
                    $params = array_replace($sParams, $rParams);
                    // $params[$bKolom] = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;

                    foreach ($aKoloms as $aKolom) {

                        // $params[$aKolom] = 0;
                        $params[$aKolom] = $trIdParams[$transaksi_id][$aKolom];
                    }
                    $trIdItems[] = $params;
                }
                $headers = array(
                    // "no"          => array(
                    //     "label" => "No",
                    //     "type"  => "integer",
                    // ),
                    "nomer"          => array(
                        "label" => "INV",
                        "type"  => "string",
                    ),
                    "dtime"          => array(
                        "label" => "tanggal",
                        "type"  => "string",
                    ),
                    "produk_nama"    => array(
                        "label" => "Model",
                        "type"  => "string",
                    ),
                    "produk_kode"    => array(
                        "label" => "type",
                        "type"  => "string",
                    ),
                    // "pihakName"   => array(
                    //     "label" => "Customer",
                    //     "type"  => "string",
                    // ),
                    "produk_ord_jml" => array(
                        "label" => "Qty",
                        "type"  => "integer",
                    ),
                    "produk_ord_hrg" => array(
                        "label" => "Price",
                        "type"  => "integer",
                    ),
                    "sub_nett1"      => array(
                        "label" => "DPP",
                        "type"  => "integer",
                    ),
                    "ppn"            => array(
                        "label" => "PPN",
                        "type"  => "integer",
                    ),
                    "sub_nett2"      => array(
                        "label" => "TOTAL",
                        "type"  => "integer",
                    ),
                );
                // cekHitam($trIdList);
                break;
        }


    }

    public function produkSub()
    {

        arrPrintPink($_GET);
        echo "data ";
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
    *
    * ----------------------------------------------------*/
    // rendering
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
            // "sum_satu"    => base_url() . "laporan/PenjualanPeriode/viewHarian" . "$strGet",
            "sum_satu"    => base_url() . "laporan/PenjualanPeriode/cekRow" . "$strGet",
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

    // ------------------penjualan tunai----------------------
    public function cekPenjualanTunai()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "4464";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $this->db->group_by("transaksi_id");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        $tmps = $this->getPenjualanTunai($date1, $date2);

        foreach ($tmps as $datas) {
            $tmpA = $datas['items'];
            $tmpM = $datas['main'];
            foreach ($tmpA as $so_id => $item) {
                $itemtambahan = array();
                $itemtambahan = addPrefixKeyM_he_format($tmpM);

                $masterData[] = $item + $itemtambahan;
            }
        }

        // arrPrintKuning($masterData);
        // matiDisini(__LINE__);
        $pivotDatas = $this->creatPivot($masterData);

        /* ---------------------------------------------
        * summary per-peran
        * ---------------------------------------------*/
        $this->setNilaiKey("tagihan");
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
            "kategori_id"     => false,
            "transaksi_id"    => false,
            "pembayaran_nama" => false,
            "sales_admin_id"  => false,
            "salesman_id"     => false,
            "pihak_id"        => false,
            "produk_id"       => false,
            "nama"            => array(
                "label" => "no so",
                "kolom" => "nama",
            ),
            "pihakID"         => array(
                "label" => "konsumen",
                "kolom" => "pihakName",
            ),
            // "nama" => array(
            //     "label" => "konsumen",
            //     "kolom" => "pihakName",
            // ),
            // "tagihan" => array(
            //     "label" => "tagihan",
            //     "kolom" => "pihakName",
            // ),
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $arrHeaders = array(
            // "transaksi_id" => array(
            //     "label" => "trid",
            //     // "format"     => "formatField_he_format",
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "master_id",
            //     //     "target" => "laporan/PenjualanPeriode/produkSub",
            //     // ),
            // ),
            "m_dtime"      => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "master_id",
                //     "target" => "laporan/PenjualanPeriode/produkSub",
                // ),
            ),
            "pihakName"    => array(
                "label" => "konsumen",
            ),
            "olehName"     => array(
                "label" => "pic",
            ),
            "placeName"    => array(
                "label" => "cabang",
            ),
            "nomer"        => array(
                "label" => "nomer",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                "data_order" => "transaksi_id",
                "attr"  => "class='text-right'",
                // "summary"    => true,
            ),
            "name"         => array(
                "label" => "referensi",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                "attr"  => "class='text-right'",
                // "summary"    => true,
            ),
            "tagihan"      => array(
                "label"      => "tagihan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                // "summary"    => true,
            ),
            "terbayar"      => array(
                "label"      => "terbayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sisa"      => array(
                "label"      => "sisa tagihan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "nilai_bayar"  => array(
                "label"      => "nilai_bayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "new_sisa"     => array(
                "label"      => "sisa",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            // "rincian"               => array(
            //     "label" => "type",
            //     "sub"   => array(
            //         "label"     => array(// "label" => 'produk'
            //         ),
            //         "total"     => array(
            //             "label"      => 'qty',
            //             "format"     => "formatField_he_format",
            //             "format_key" => "harga",
            //             "summary"    => true,
            //         ),
            //         "sub_nilai" => array(
            //             "label"      => 'sub nilai',
            //             "format"     => "formatField_he_format",
            //             "format_key" => "harga",
            //             "summary"    => true,
            //         )
            //     )
            // ),
            // "sub" => array(
            //     "label"  => "qty",
            //     "sub" => array(
            //
            //     )
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // "transaksi_id"      => array(
            //     "label" => "trid",
            // ),
            // "pihak_nama"        => array(
            //     "label" => "konsumen",
            // ),
            // "due_date"          => array(
            //     "label"      => "tanggal jatuh tempo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "fulldate",
            // ),
            // "tagihan_status"    => array(
            //     "label" => "tagihan",
            //     // "format"     => "formatField_he_format",
            //     // "format_key" => "fulldate",
            // ),
            // "umur_status"       => array(
            //     "label" => "overdue",
            // ),
            // "transaksi_no_1"    => array(
            //     "label" => "no. spo",
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
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // // ----------
            // // "produk_kode"           => array(
            // //     "label" => "produk sku",
            // //     "type"  => "string",
            // // ),
            // // "produk_nama"           => array(
            // //     "label" => "produk",
            // //     "type"  => "string",
            // // ),
            // // "outdoor_nama"          => array(
            // //     "label" => "outdoor",
            // //     "type"  => "string",
            // // ),
            // // "indoor_nama_1"         => array(
            // //     "label" => "intdoor",
            // //     "type"  => "string",
            // // ),
            // // "qty_kredit"            => array(
            // //     "label" => "jumlah",
            // //     "type"  => "integer",
            // // ),
            // // "harga_include_ppn"     => array(
            // //     "label"      => "harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // // "sub_harga_include_ppn" => array(
            // //     "label"      => "sub harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // //-----------------
            // "dpp_ppn"           => array(
            //     "label"      => "jml kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_ppn"         => array(
            //     "label"      => "total pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_tagihan"     => array(
            //     "label"      => "total",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_terbayar"    => array(
            //     "label"      => "pembayaran",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "sisa_tagihan"      => array(
            //     "label"      => "sisa tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
        );
        // arrPrintKuning($masterData);

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
        // arrPrintKuning($masterData);
        $data = array(
            "mode"                 => "langsung_indek",
            // "mode"       => "pivot",
            "title"                => "Laporan penjualan tunai " . $judul_lap,
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
            "pivotDatas"           => $pivotDatas,
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
        // $this->load->view("laporan", $data);
        $this->load->view("penjualanPeriode", $data);
    }

    public function getPenjualanTunai($date1, $date2)
    {
        $jenis = "4464";
        $tbl_1 = "transaksi";
        $tbl_2 = "transaksi_data_registry";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        // $transaksi_jenis = array(
        //     "4464", "5822spd"
        // );
        // $this->db->where_in("jenis", $transaksi_jenis);

        // $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $wheres = array(
        //     "DATE(dtime)>=" => $date1,
        //     "DATE(dtime)<=" => $date2,
        //     "jenis"         => $jenis,
        // );
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        $selectedKoloms = array(
            "$tbl_1.id",
            "$tbl_1.dtime",
            "$tbl_1.oleh_nama",
            "$tbl_1.customers_nama",
            "$tbl_1.nomer",
            "$tbl_1.cabang_nama",
            // "$tbl_2.produk_id",
            // "$tbl_2.produk_nama",
            "$tbl_2.main",
            "$tbl_2.items"
        );
        // $this->db->select("$tbl_1.*, $tbl_2.main, $tbl_2.items");
        $this->db->select($selectedKoloms);
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id");
        $wheres = "$tbl_1.jenis='$jenis' AND DATE($tbl_1.dtime) >= '$date1' AND DATE($tbl_1.dtime) <= '$date2'";
        $this->db->where($wheres); // Ganti $id dengan nilai yang sesuai
        $query = $this->db->get();

        $result = $query->result();
        showLast_query("merah");
        // cekHijau(count($result));
        // arrPrint($result);

        $kolom_main = array(
            "cabangID",
            "cabangName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "pihakName",
            "jenisTr",
            "jenisTrName",
            "dtime",
            "tagihan",
        );
        $tmpA = array();
        foreach ($result as $ritem) {

            $main = blobDecode($ritem->main);
            foreach ($kolom_main as $kolom) {
                $datas[$kolom] = $main[$kolom];
            }
            $items = blobDecode($ritem->items);
            // cekHere($item->id);
            // arrPrint($main);
            // arrPrintHijau($items);
            $mains = (array)$ritem;
            $mains['main'] = $main;
            $mains['items'] = $items;
            $tmpA[] = $mains;
            // $tmpA[] = $datas;
        }

        // arrPrintKuning($tmpA);
        return $tmpA;
    }
}