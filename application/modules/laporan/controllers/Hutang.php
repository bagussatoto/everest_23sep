<?php

class Hutang extends MX_Controller
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

        $this->tbl1 = "__rek_pembantu_supplier__2010010";
        $this->tbl2 = "__rek_pembantu_supplier__2010040";
        $this->tbl3 = "_rek_pembantu_supplier_cache";
        $this->tbl4 = "transaksi_payment_source";
        $this->tbl5 = "transaksi";
        $this->tbl6 = "__rek_master__2010010";
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

    public function callPaymentSource($jenis)
    {
        $tbl_1 = "transaksi_payment_source";
        if (empty($jenis)) {
            $jenis = [
                "1467",
                "467",
            ];
        }

        if (is_array($jenis)) {

            $this->db->where_in("jenis", $jenis);
        }
        else {

            $this->db->where("jenis", $jenis);
        }
        $wheres = array(
            "sisa>" => "1000",
            // "jenis" => "467"
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        // arrPrintHijau($srcs);
        foreach ($srcs as $src) {
            $tr_id = $src['transaksi_id'];
            $sisa = $src['sisa'];

            $tagihan[$tr_id] = $src;
        }

        return $tagihan;
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
        // $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

//     public function get_saldo_awal($common_filters)
//     {
//         $tbl1 = $this->tbl1;
//         $tbl2 = $this->tbl2;
//         $tahun = "2025";
//
//         $sql = "
//     SELECT
//         extern_id,
//         SUM(saldo_awal_kredit) AS saldo_awal
//     FROM (
//         SELECT
//             t1.extern_id,
//             t1.saldo_awal_kredit
//         FROM $tbl1 t1
//         LEFT JOIN $tbl2 t2 ON t1.extern_id = t2.extern_id
//          WHERE YEAR(t1.dtime) >= '$tahun' and t1.extern_id='54'
//
//         UNION ALL
//
//         SELECT
//             t2.extern_id,
//             t2.saldo_awal_kredit
//         FROM $tbl2 t2
//         LEFT JOIN $tbl1 t1 ON t2.extern_id = t1.extern_id
//         WHERE t1.extern_id IS NULL and year(t2.dtime) >= '$tahun' and t2.extern_id='54'
//     ) AS gabungan
//     GROUP BY extern_id
// ";
//
//         $query = $this->db->query($sql);
//         $result = $query->result();
//
//         showLast_query("hijau");
//         arrPrintHijau($result);
//
//         return $result;
//     }
//
//     public function get_barang_masuk($common_filters)
//     {
//         $tbl1 = $this->tbl1;
//         $tbl2 = $this->tbl2;
//         $tahun = "2025";
//
//         $sql = "
//     SELECT
//         extern_id,
//         SUM(debet) AS total_debet,
//         SUM(kredit) AS total_kredit
//     FROM (
//         SELECT
//             t1.extern_id,
//             t1.debet,
//             t1.kredit
//         FROM $tbl1 t1
//         LEFT JOIN $tbl2 t2 ON t1.extern_id = t2.extern_id
//         WHERE YEAR(t1.dtime) >= '$tahun' and t1.extern_id='54'
//
//         UNION ALL
//
//         SELECT
//             t2.extern_id,
//             t2.debet,
//             t2.kredit
//         FROM $tbl2 t2
//         LEFT JOIN $tbl1 t1 ON t2.extern_id = t1.extern_id
//         WHERE t1.extern_id IS NULL AND YEAR(t2.dtime) >= '$tahun' and t2.extern_id='54'
//     ) AS gabungan
//     GROUP BY extern_id
// ";
//
//         $query = $this->db->query($sql);
//         $result = $query->result();
//         // showLast_query("kuning");
//         // arrPrintKuning($result);
//
//         // $satu = $this->get_satu();
//         // $satu = $this->gabungan();
//
//         $satu = $this->coalesceAll();
//         showLast_query("biru");
//         cekBiru($satu);
//
//         return $result;
//
//     }

    // -------------------------------------------------------

    public function cekRow()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = ["467", "1467", "7778",
            "462",
            "463",
            "461",
            "423",
            "425",
            "3463",
        ];
        $target = [

        ];
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
        $counterJenis = $this->callTransaksiCounterJenis(false);
        $tagihans = $this->callPaymentSource($jenis);
        showLast_query("kuning");
        $tagihanDuedates = $this->callTransaksiDueDate();
        showLast_query("merah");

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
                if (strtotime($dueDate) !== false) {
                    $umur_d = umurDay($dueDate);
                    $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
                    $itemtambahan['umur_now'] = $umur_d;
                }
                else {
                    $umur_d = '';
                    $itemtambahan['due_date'] = null;
                    $itemtambahan['umur_now'] = "";
                }
            }
            if ($transaksi_id == '27137') {

                // cekLime($transaksi_id);
                // arrPrintPink($tagihan['sisa']);
            }
            // break;
            $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;

            if ($umur_d === 0) {
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
            $counters = $counterJenis[$transaksi_id];
            $counterNum = $counters["_company_stepCode"];
            $itemtambahan['counter_spd'] = $counterNum;
            $itemtambahan['nomer_counter'] = $transaksi_nomer . "-" . $counterNum;
            // $produk_speks = $spekProduks[$transaksi_produk_id];
            // $itemtambahan['merek_nama'] = $produk_speks->merek_nama;

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            "extern_nama"       => array(
                "label" => "supplier",
            ),
            // "label"                 => array(
            //     "label" => "label",
            // ),
            // "counter_spd"       => array(
            //     "label" => "counter",
            //     // "format"     => "formatField_he_format",
            // ),
            "transaksi_tanggal" => array(
                "label"  => "tanggal grn",
                "format" => "formatField_he_format",
            ),
            "nomer_counter"     => array(
                "label" => "no grn",
            ),
            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            // "tagihan_status"    => array(
            //     "label" => "tagihan",
            // ),
            "due_date"          => array(
                "label"      => "tanggal jatuh tempo",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
            ),
            "umur_status"       => array(
                "label"      => "overdue",
                "data_order" => "due_date",

            ),
            // "transaksi_id"          => array(
            //     "label" => "trid",
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
            // "c_ppn"     => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "sub_ppn_nilai"  => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
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
            "total_tagihan"     => array(
                "label"      => "nilai invoice pembelian",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "summary"    => false,
                // "attr"       => "class='text-right bg-warning'",
            ),
            "total_terbayar"    => array(
                "label"      => "dibayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "sisa_tagihan"      => array(
                "label"      => "sisa hutang",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "extern_label2"     => array(
                "label"      => "e-Faktur",
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
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"        => "langsung_simple",
            "title"       => "Histori pembelian " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
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
        $this->load->view("hutang", $data);
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
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $this->db->group_by("transaksi_id");
        $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        $tagihans = $this->callPaymentSource();
        $tagihanDuedates = $this->callTransaksiDueDate();
        $counterJenis = $this->callTransaksiCounterJenis(false);
        // arrPrintKuning(array_slice($tagihans,2));
        // arrPrintKuning(array_slice($tagihanDuedates,2));

        foreach ($tagihans as $item) {
            $transaksi_nomer = $item['nomer'];
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
                $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
                $dueDate = $tagihanDuedate['due_date'];
                if (strtotime($dueDate) !== false) {
                    $umur_d = umurDay($dueDate);
                    $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
                    $itemtambahan['umur_now'] = $umur_d;
                }
                else {
                    $umur_d = '';
                    $itemtambahan['due_date'] = null;
                    $itemtambahan['umur_now'] = "";
                }
            }
            if ($transaksi_id == '27137') {

                // cekLime($transaksi_id);
                // arrPrintPink($tagihan['sisa']);
            }
            // break;
            $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;

            if ($umur_d === 0) {
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
            $counters = $counterJenis[$transaksi_id];
            $counterNum = $counters["_company_stepCode"];
            $itemtambahan['counter_spd'] = $counterNum;
            $itemtambahan['nomer_counter'] = $transaksi_nomer . "-" . $counterNum;
            // $produk_speks = $spekProduks[$transaksi_produk_id];
            // $itemtambahan['merek_nama'] = $produk_speks->merek_nama;

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));

        $arrHeaders = array(
            "counter_spd"       => array(
                "label" => "counter",
                // "format"     => "formatField_he_format",
            ),
            "transaksi_tanggal" => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            "pembayaran_nama"   => array(
                "label" => "pembayaran",
            ),
            "transaksi_id"      => array(
                "label" => "trid",
            ),
            "pihak_nama"        => array(
                "label" => "konsumen",
            ),
            "due_date"          => array(
                "label"      => "tanggal jatuh tempo",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
            ),
            "tagihan_status"    => array(
                "label" => "tagihan",
                // "format"     => "formatField_he_format",
                // "format_key" => "fulldate",
            ),
            "umur_status"       => array(
                "label" => "overdue",
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
            ),
            "transaksi_no_inv"  => array(
                "label" => "no. invoice",
                "type"  => "string",
            ),
            // ----------
            // "produk_kode"           => array(
            //     "label" => "produk sku",
            //     "type"  => "string",
            // ),
            // "produk_nama"           => array(
            //     "label" => "produk",
            //     "type"  => "string",
            // ),
            // "outdoor_nama"          => array(
            //     "label" => "outdoor",
            //     "type"  => "string",
            // ),
            // "indoor_nama_1"         => array(
            //     "label" => "intdoor",
            //     "type"  => "string",
            // ),
            // "qty_kredit"            => array(
            //     "label" => "jumlah",
            //     "type"  => "integer",
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
            //-----------------
            "dpp_ppn"           => array(
                "label"      => "jml kena pajak",
                "format"     => "formatField_he_format",
                "format_key" => "harga",

            ),
            "total_ppn"         => array(
                "label"      => "total pajak",
                "format"     => "formatField_he_format",
                "format_key" => "harga",

            ),
            "total_tagihan"     => array(
                "label"      => "total",
                "format"     => "formatField_he_format",
                "format_key" => "harga",

            ),
            "total_terbayar"    => array(
                "label"      => "pembayaran",
                "format"     => "formatField_he_format",
                "format_key" => "harga",

            ),
            "sisa_tagihan"      => array(
                "label"      => "sisa tagihan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",

            ),
        );

        $arrHeaders = array();
        $pivotDatas = $this->creatPivot($masterData);
        // arrPrintHijau(array_slice($pivotDatas,1,1));
        $arrHeaders = array(
            "label" => array(
                "label" => "supplier",
                "attr"  => "class='font-size-1-5'",
                // "format"     => "formatField_he_format",
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "master_id",
                //     "target" => "laporan/PenjualanPeriode/produkSub",
                // ),
            ),

            "total_harga"        => array(
                "label"      => "total nilai invoice pembelian<br>incl. ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right font-size-1-5'",
                "summary"    => true,
            ),
            "total_qty_kredit"   => array(
                "label"      => "total dibayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right font-size-1-5'",
                "summary"    => true,
            ),
            "total_sisa_tagihan" => array(
                "label"      => "total sisa tagihan<br>w/o ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right font-size-1-5'",
                "summary"    => true,
            ),
            // "total_ppn" => array(
            //     "label"      => "total ppn belum faktur",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr" => "class='text-right font-size-1-5'",
            //     "summary" => true,
            // ),
            "rincian"            => array(
                // "label" => "no. PO",
                "label" => "inv. supplier",
                "sub"   => array(
                    // "no_po" => array(/*----------------------------
                    //      * label akan mengunakan label pada key rincian
                    //      * --------------------------------------------*/
                    // ),

                    "note"  => array(// "label" => 'inv. supplier',
                    ),
                    // "transaksi_id" => array(
                    //     "label" => 'trId',
                    // ),
                    "label" => array(
                        "label" => 'no. grn',
                    ),

                    "no_efaktur"       => array(
                        "label" => 'no. e-faktur',
                    ),
                    "total"            => array(
                        "label"      => 'nilai invoice<br>incl. ppn',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    ),
                    "sub_nilai"        => array(
                        "label"      => 'dibayar',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    ),
                    "sub_sisa_tagihan" => array(
                        "label"      => 'sisa tagihan<br>w/o ppn',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    ),
                    // "sub_ppn" => array(
                    //     "label"      => 'ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary" => true,
                    // ),


                )
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
            "mode"       => "pivot",
            "title"      => "Laporan hutang dagang " . $judul_lap,
            "subTitle"   => "Raw data pembelian",
            "modul_path" => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"    => "582",
            "data_id"    => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"     => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders" => $arrHeaders,
            // "master_data"          => $masterData,
            // "summariNilais"        => $summariNilais,
            // "summariLabels"        => $summariLabels,
            // "summariSubjectLabels" => $summariSubjectLabels,
            "pivotDatas" => $pivotDatas,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"      => $date1,
            "date2"      => $date2,
            "date_min"   => 1,
            "date_max"   => dtimeNow('Y-m-d'),
            "loader_div" => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        // $this->load->view("laporan", $data);
        $this->load->view("hutang", $data);
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
            // $arrSubjects["jenis"] =
            //     array(
            //         // "label" => "jenis",
            //         // "kolom" => "jenis",
            //     );
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

    public function viewSummaryKolom($row_datas){
        foreach ($row_datas as $row_data) {
            // arrPrint($row_data);
            foreach ($row_data as $key => $row_datum) {
                if(!isset($summary[$key])){
                    $summary[$key] = 0;
                }
                $summary[$key] += $row_datum;
            }
        }

        return $summary;
    }

    public function creatPivot($data)
    {
        // Fungsi untuk membuat laporan pivot

        $pivot = array();

        foreach ($data as $row) {
            $transaksi_id = $row['transaksi_id'];
            $transaksi_no = $row['transaksi_no'];
            $extern_nama = $row['extern_nama'];
            // $merek_nama = $row['merek_nama'];
            $produk_nama = $row['nomer_counter'];
            $transaksi_description = $row['extern2_nama'];
            $transaksi_description_note = $row['extern_label2'];
            $ppn = $row['ppn'];
            $qty_kredit = $row['total_terbayar'];
            $sisa_tagihan = $row['sisa_tagihan'];
            $sub_harga_include_ppn = $row['sub_harga_include_ppn'];
            $harga = $row['harga'];
            $harga = $row['total_tagihan'];

            /* -------------------------------
             * menjadi konci pembentuk data pivot
             * --------------------- ----------------------*/
            $label_1 = $extern_nama;
            $label_2 = $produk_id = $transaksi_id;
            // ---------------------------------------------

            if (!isset($pivot[$label_1])) {
                $pivot[$label_1] = array(
                    // 'total_'.$produk_nama => 0,
                    'total_qty_kredit'      => 0,
                    'total_harga'           => 0,
                    'count'                 => 0,
                    'sub_harga_include_ppn' => 0,
                );
            }

            $pivot[$label_1]['sub_harga_include_ppn'] += $sub_harga_include_ppn;
            $pivot[$label_1]['total_harga'] += $harga;
            $pivot[$label_1]['total_qty_kredit'] += $qty_kredit;
            $pivot[$label_1]['total_sisa_tagihan'] += $sisa_tagihan;
            $pivot[$label_1]['total_ppn'] += $ppn;
            $pivot[$label_1]['count']++;

            /* -----------------------------------
             * angkor untuk layer utama (objek)
             * ------------------------------------*/
            $pivot[$label_1]['label'] = $label_1;
            // --------------------------------------------------------------------layer kedua--------
            if (!isset($pivot[$label_1]['rincian'][$label_2]['sub_nilai'])) {
                $pivot[$label_1]['rincian'][$label_2]['sub_nilai'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['sub_nilai'] += $qty_kredit;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['total'])) {
                $pivot[$label_1]['rincian'][$label_2]['total'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['total'] += $harga;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['sub_sisa_tagihan'])) {
                $pivot[$label_1]['rincian'][$label_2]['sub_sisa_tagihan'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['sub_sisa_tagihan'] += $sisa_tagihan;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['sub_ppn'])) {
                $pivot[$label_1]['rincian'][$label_2]['sub_ppn'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['sub_ppn'] += $ppn;
            // ----------------------------------------------
            $pivot[$label_1]['rincian'][$label_2]['label'] = $produk_nama;
            $pivot[$label_1]['rincian'][$label_2]['note'] = $transaksi_description;
            $pivot[$label_1]['rincian'][$label_2]['transaksi_id'] = $transaksi_id;
        }

        return $pivot;

    }

    // ---------------------------------
    public function viewhr()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

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
            "sum_null"    => base_url() . "laporan/" . get_class($this) . "/$ygditampilkan" . "$strGet",

        );
        $this->load->view("kas", $data);
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

    // --------------------------------------------------------
    public function breakdownSaldoawal()
    {
        $tbl1 = $this->tbl1;
        $tbl2 = $this->tbl2;
        $tahun = 2025;
        $extern_id = '1';

        $sql1 = "
    SELECT 
        extern_id, 
        SUM(saldo_awal_kredit) AS saldo_awal
    FROM $tbl1
    WHERE YEAR(dtime) >= $tahun 
      AND extern_id = '$extern_id'
    GROUP BY extern_id
";

        $query1 = $this->db->query($sql1);
        $result1 = $query1->result();
        showLast_query("kuning");
        arrPrintKuning($result1);

        $sql1 = "
    SELECT 
        extern_id, 
        SUM(saldo_awal_kredit) AS saldo_awal
    FROM $tbl2
    WHERE YEAR(dtime) >= $tahun 
      AND extern_id = '$extern_id'
    GROUP BY extern_id
";

        $query1 = $this->db->query($sql1);
        $result1 = $query1->result();
        showLast_query("kuning");
        arrPrintKuning($result1);

        $sql = "
    SELECT 
        extern_id,
        SUM(saldo_awal_kredit) AS saldo_awal
    FROM (
        SELECT extern_id, saldo_awal_kredit 
        FROM $tbl1
        WHERE YEAR(dtime) >= $tahun 
          AND extern_id = '$extern_id'

        UNION ALL

        SELECT extern_id, saldo_awal_kredit 
        FROM $tbl2
        WHERE YEAR(dtime) >= $tahun 
          AND extern_id = '$extern_id'
    ) AS gabungan
    GROUP BY extern_id
";

        $query = $this->db->query($sql);
        $result = $query->result();
        showLast_query("kuning");
        arrPrintKuning($result);
    }

    public function coalesce()
    {
        $tbl1 = $this->tbl1;
        $tbl2 = $this->tbl2;
        $tahun = 2025;
        $extern_id = '1';

        $sql = "
            SELECT 
                gab.extern_id,
                COALESCE(saldo_awal, 0) AS saldo_awal,
                COALESCE(total_debet, 0) AS total_debet,
                COALESCE(total_kredit, 0) AS total_kredit,
                (COALESCE(saldo_awal, 0) + COALESCE(total_kredit, 0)) - COALESCE(total_debet, 0) AS saldo_akhir
            FROM (
                SELECT 
                    extern_id, 
                    SUM(debet) AS total_debet, 
                    SUM(kredit) AS total_kredit
                FROM (
                    SELECT extern_id, debet, kredit 
                    FROM $tbl2 
                    WHERE YEAR(dtime) >= $tahun AND extern_id = '$extern_id'
            
                    UNION ALL
            
                    SELECT extern_id, debet, kredit 
                    FROM $tbl1 
                    WHERE YEAR(dtime) >= $tahun AND extern_id = '$extern_id'
                ) AS trans
                GROUP BY extern_id
            ) AS gab
            LEFT JOIN (
                SELECT 
        extern_id,
        SUM(saldo_awal_kredit) AS saldo_awal
    FROM (
        SELECT extern_id, saldo_awal_kredit 
        FROM $tbl1
        WHERE YEAR(dtime) >= $tahun 
          AND extern_id = '$extern_id'

        UNION ALL

        SELECT extern_id, saldo_awal_kredit 
        FROM $tbl2
        WHERE YEAR(dtime) >= $tahun 
          AND extern_id = '$extern_id'
                ) AS saldo
                GROUP BY extern_id
            ) AS awal ON gab.extern_id = awal.extern_id
            ";

        $query = $this->db->query($sql);
        $result = $query->result();
        arrPrintPink($result);

        $sss = $this->breakdownSaldoawal();


        return $result;
    }

    private function coalesceAll()
    {
        $tbl1 = $this->tbl1;
        $tbl2 = $this->tbl2;
        $tahun = 2025;
        $extern_id = '54';

        $sql = "
SELECT 
    gab.extern_id,gab.extern_nama,
    COALESCE(saldo_awal, 0) AS saldo_awal,
    COALESCE(total_debet, 0) AS total_debet,
    COALESCE(total_kredit, 0) AS total_kredit,
    (COALESCE(saldo_awal, 0) + COALESCE(total_kredit, 0)) - COALESCE(total_debet, 0) AS saldo_akhir
FROM (
    SELECT 
        extern_id,extern_nama, 
        SUM(debet) AS total_debet, 
        SUM(kredit) AS total_kredit
    FROM (
        SELECT extern_id, extern_nama, debet, kredit 
        FROM $tbl2 
        WHERE YEAR(dtime) >= $tahun 

        UNION ALL

        SELECT extern_id,extern_nama, debet, kredit 
        FROM $tbl1 
        WHERE YEAR(dtime) >= $tahun
    ) AS trans
    GROUP BY extern_id
) AS gab
LEFT JOIN (
     SELECT 
        extern_id,
        SUM(saldo_awal_kredit) AS saldo_awal
     FROM (
        SELECT extern_id, saldo_awal_kredit 
        FROM $tbl1
        WHERE YEAR(dtime) >= $tahun 

        UNION ALL

        SELECT extern_id, saldo_awal_kredit 
        FROM $tbl2
        WHERE YEAR(dtime) >= $tahun 
    ) AS saldo
    GROUP BY extern_id
) AS awal ON gab.extern_id = awal.extern_id
";

        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    /**
     * mendapatkan saldo akhir dari acuan saldo awal tahun 2025
     * */
    public function callPembantuSupplier()
    {
        $tbl1 = $this->tbl1;
        $tbl2 = $this->tbl2;
        $tahun = "2025";

        $sql = "
        SELECT 
            extern_id,
            extern_nama,
            SUM(saldo_awal_kredit) AS saldo_awal,
            SUM(debet) AS total_debet,
            SUM(kredit) AS total_kredit,
            (SUM(saldo_awal_kredit) + SUM(kredit) - SUM(debet)) AS saldo_akhir
        FROM (
            SELECT 
                t1.extern_id,
                t1.extern_nama,
                t1.saldo_awal_kredit,
                t1.debet,
                t1.kredit
            FROM $tbl1 t1
            LEFT JOIN $tbl2 t2 ON t1.extern_id = t2.extern_id
            WHERE YEAR(t1.dtime) >= '$tahun'

            UNION ALL

            SELECT 
                t2.extern_id,
                t2.extern_nama,
                t2.saldo_awal_kredit,
                t2.debet,
                t2.kredit
            FROM $tbl2 t2
            LEFT JOIN $tbl1 t1 ON t2.extern_id = t1.extern_id
            WHERE t1.extern_id IS NULL AND YEAR(t2.dtime) >= '$tahun'
        ) AS gabungan
        GROUP BY extern_id
    ";

        $query = $this->db->query($sql);
        $result = $query->result();

        // showLast_query("merah");
        // arrPrintKuning($result);

        return $result;
    }

    public function callPembantuSupplierCache()
    {
        $tbl3 = $this->tbl3;

        $this->db->select('extern_id, extern_nama, debet, kredit');
        $koloms = [
            "extern_id, 
            extern_nama, 
            sum(debet) as sum_debet, 
            sum(kredit) as sum_kredit"
        ];
        $this->db->select($koloms);
        $this->db->from($tbl3);
        $this->db->where('periode', 'forever');
        $this->db->where_in('rekening', ["2010010", "2010040"]);
        $this->db->group_by("extern_id");

        $query = $this->db->get();
        $result = $query->result();

        showLast_query("kuning");
        // arrPrintHijau($result);

        foreach ($result as $item) {
            $hasil[$item->extern_id] = (array)$item;
        }

        return $hasil;

    }

    public function callPembantuPaymentSource($jenis)
    {
        $tbl_1 = $this->tbl4;

        $koloms = array(
            "jenis,
            target_jenis, 
            label,
            extern_id,
            extern_nama,
            sum(sisa) as sisa"
        );
        $this->db->select($koloms);
        if (empty($jenis)) {
            // $jenis = [
            //     '467', '1467', '7778', '462', '463', '461', '423', '425', '3463'
            // ];
            $jenis = ['489','462','483'];
        }

        if (is_array($jenis)) {

            $this->db->where_in("target_jenis", $jenis);
        }
        else {

            $this->db->where("target_jenis", $jenis);
        }
        $labels = array(
            'hutang dagang', 'hutang biaya'
        );
        $this->db->where_in("label", $labels);
        $wheres = array(
            "sisa>" => "1000",
            // "jenis" => "467"
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $this->db->group_by("extern_id");
        $srcs = $this->db->get($tbl_1)->result_array();
        showLast_query("biru", '@' . __LINE__);

        // arrPrintHijau($srcs);
        foreach ($srcs as $src) {
            $extern_id = $src['extern_id'];
            $sisa = $src['sisa'];

            $tagihan[$extern_id] = $src;
        }

        return $tagihan;
    }

    /**
     * coalesceAll && callPembantuSupplierCache && callPembantuPaymentSource
     * */
    public function cekSumMutasi()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');


        // $dataUtamas = $this->callPembantuSupplier();
        $dataUtamas = $this->coalesceAll();
        $dataCache = $this->callPembantuSupplierCache();
        $dataPaymentSrc = $this->callPembantuPaymentSource();
        // arrPrintKuning($tagihans);
        // matiHere(__LINE__);
        $arrTambahan = array();
        foreach ($dataUtamas as $item) {
            $mid = $item->extern_id;
            $saldo_akhir = $item->saldo_akhir;
            $cache = $dataCache[$mid];
            $arrTambahan["kredit_cache"] = $cache["sum_kredit"];
            $arrTambahan["selisih_cache_sum"] = $cache["sum_kredit"] - $saldo_akhir;

            $paymentSrc = $dataPaymentSrc[$mid];
            $arrTambahan["sisa"] = $paymentSrc["sisa"];
            $arrTambahan["selisih_dg_payment_src"] = $paymentSrc["sisa"] - $saldo_akhir;
            $arrTambahan["selisih_dg_payment_src_view"] = $paymentSrc["sisa"] - $saldo_akhir;

            $arrItem = (array)$item;

            $masterData[] = $arrItem + $arrTambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders_1 = array(
            "extern_id"         => array(
                "label" => "id",
            ),
            "extern_nama"       => array(
                "label" => "supplier",
                "links" => array(
                    // Ledger/viewMoveDetails/RekeningPembantuSupplier/2010010/4/?o=-1&date1=2024-01-01&date2=2025-04-16&o=-1&main_ext2_id=4&blob_ext=czoyOToiIFBULlBBTkFTT05JQyBHT0JFTCBJTkRPTkVTSUEiOw%3D%3D
                    "target" => "Ledger/viewMoveDetails/RekeningPembantuSupplier/2010010",
                    // "target" => "laporan/Hutang/viewHr/cekMutasiSupplier",
                    "link_type" => "newwindow_custom",
                    "key" => "extern_id",
                ),
            ),
            // "label"                 => array(
            //     "label" => "label",
            // ),
            // "counter_spd"       => array(
            //     "label" => "counter",
            //     // "format"     => "formatField_he_format",
            // ),
            // "transaksi_tanggal" => array(
            //     "label"  => "tanggal grn",
            //     "format" => "formatField_he_format",
            // ),

            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            // "tagihan_status"    => array(
            //     "label" => "tagihan",
            // ),
            // "due_date"          => array(
            //     "label"      => "tanggal jatuh tempo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "fulldate",
            // ),
            // "umur_status"       => array(
            //     "label"      => "overdue",
            //     "data_order" => "due_date",
            //
            // ),
            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),
            "saldo_awal"        => array(
                "label"      => "Saldo awal",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "summary"    => false,
                // "attr"       => "class='text-right bg-warning'",
            ),
            "total_kredit"      => array(
                "label"      => "nilai invoice pembelian",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "summary"    => false,
                // "attr"       => "class='text-right bg-warning'",
            ),
            "total_debet"       => array(
                "label"      => "nilai dibayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "saldo_akhir"       => array(
                "label"      => "nilai sisa hutang",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
        );
        $arrHeaders_2 = array(
            "kredit_cache"      => array(
                "label"      => "sisa hutang dr cache",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "selisih_cache_sum" => array(
                "label"      => "sisa hutang <br>(mutasi-cache)",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),

            "sisa"                   => array(
                "label"      => "sisa hutang dr payment src",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "selisih_dg_payment_src_view" => array(
                "label"      => "sisa hurang <br>(mutasi-payment src)",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
            ),
            "selisih_dg_payment_src" => array(
                "label"      => "sisa hurang <br>(mutasi-payment src)",
                // "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
        );
        $arrHeaders_2 = array();

        $arrHeaders = $arrHeaders_1 + $arrHeaders_2;

        /* ---------------------------------------------
        * summary per-peran
        * ---------------------------------------------*/
        $this->setNilaiKey("saldo_akhir");
        $kolomNilais = array(

            "extern_id"  => array(
                "label" => "total hutang",
                "kolom" => "total_hutang",
            ),
            "total_kredit"  => array(
                "label" => "total pembelian",
                "kolom" => "total_kredit",
            ),
            "kategori_id"     => false,
            "pembayaran_nama" => false,
            "sales_admin_id" => false,
            "salesman_id" => false,
            "pihak_id" => false,
            "transaksi_id" => false,
            "produk_id" => false,
            "kategori_nama" =>false,
            "merek_nama"    => false,
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummaryKolom($masterData);
        arrPrintPink($arrSummary);
        // $summariNilais = $arrSummary['nilai'];
        // $summariLabels = $arrSummary['label'];
        // $summariSubjectLabels = $arrSummary['kolom_key'];
        $arrSummaries = array(
            "saldo_awal" => array(
                "label" => "Nilai hutang akhir " . (dtimeNow('Y') - 1),
                "nilai" => $arrSummary["saldo_awal"],
                "icon" => "fa-money",
            ),
            "total_kredit" => array(
                "label" => "Total pembelian",
                "nilai" => $arrSummary["total_kredit"],
                "icon" => "fa-money",
            ),
            "total_debet" => array(
                "label" => "Total pembayaran",
                "nilai" => $arrSummary["total_debet"],
                "icon" => "fa-money",
            ),
          "saldo_akhir" => array(
              "label" => "Total Hutang " . dtimeNow('Y'),
              "nilai" => $arrSummary["saldo_akhir"],
              "icon" => "fa-money",
          ),
        );
        $top_str = "";
        foreach ($arrSummaries as $ky => $params){
            $label = $params['label'];
            $nilai = $params['nilai'];
            $nilai_f = formatField_he_format("harga",$nilai);

            $top_str .= "<div class='col-md-3 col-sm-6 col-xs-12'>";
            $top_str .= "<div class='info-box'>";
            $top_str .= "<span class='info-box-icon bg-aqua'><i class='fa fa-money'></i></span>";
            $top_str .= "<div class='info-box-content'>";
            $top_str .= "<span class='info-box-text'>$label</span>";
            $top_str .= "<span class='info-box-number font-size-2'>$nilai_f</span>";
            $top_str .= "</div>";
            $top_str .= "</div>";
            $top_str .= "</div>";
        }

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
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"        => "langsung_simple",
            "title"       => "Hutang Usaha pada " . $judul_lap,
            "subTitle"    => "koparasi mutasi-cache-payment src",
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
            // "wadah_atas" => $top_str,
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
        $this->load->view("hutang", $data);
    }

    // ---------------------------------------------------------------per extern_id
    public function callPembantuPaymentSourceSupplier($supplier_id, $jenis)
    {
        $tbl_1 = $this->tbl4;
        $tbl5 = $this->tbl5;
        if (empty($supplier_id)) {
            $supplier_id = $_GET['s'];
        }

        $koloms = array(
            "$tbl_1.dtime",
            "$tbl_1.jenis",
            "$tbl_1.transaksi_id",
            "$tbl_1.target_jenis",
            "$tbl_1.label",
            "$tbl_1.extern_id",
            "$tbl_1.extern_nama",
            "$tbl_1.sisa",
            "$tbl_1.terbayar",
            "$tbl_1.tagihan",
            "$tbl5.*",
        );
        $this->db->select($koloms);
        if (empty($jenis)) {
            // $jenis = [
            //     '467', '1467', '7778', '462', '463', '461', '423', '425', '3463'
            // ];
            $jenis = ['489','462','483'];
        }

        if (is_array($jenis)) {

            $this->db->where_in("$tbl_1.target_jenis", $jenis);
        }
        else {

            $this->db->where("$tbl_1.target_jenis", $jenis);
        }
        $labels = array(
            'hutang dagang', 'hutang biaya'
        );
        $this->db->where_in("$tbl_1.label", $labels);
        $wheres = array(
            "$tbl_1.sisa>"     => "1000",
            // "jenis" => "467"
            "$tbl_1.extern_id" => $supplier_id
        );
        $this->db->where($wheres);
        $this->db->order_by("$tbl_1.dtime", "asc");
        // $this->db->group_by("extern_id");

        // JOIN setup
        // $this->db->select("$tbl_1.*, $tbl5.*"); // Atau sesuaikan kolom yang ingin kamu ambil
        $this->db->from($tbl_1);
        $this->db->join($tbl5, "$tbl_1.transaksi_id = $tbl5.id", 'left');
        $srcs = $this->db->get()->result_array();
        showLast_query("biru");

        // arrPrintHijau($srcs);
        foreach ($srcs as $src) {
            $extern_id = $src['extern_id'];
            $sisa = $src['sisa'];

            $tagihan[$extern_id] = $src;
        }

        return $srcs;
    }

    public function callMutasiSupplier($supplier_id)
    {
        $tbl1 = $this->tbl1;
        $tbl2 = $this->tbl2;
        $tbl3 = $this->tbl6;
        $tahun = "2025";
        if (empty($supplier_id)) {
            $supplier_id = $_GET['s'];
        }

        $koloms = [
            "rekening",
            "extern_id",
            "extern_nama",
            "transaksi_id",
            "debet",
            "kredit",
        ];
        $condites = [
            "extern_id"     => $supplier_id,
            "year(dtime)>=" => $tahun
        ];
        // -------------------------------------------------------
        $this->db->select($koloms);
        $this->db->where($condites);
        $query = $this->db->get($tbl1);
        $result1 = $query->result();

        showLast_query("merah",count($result1));
        // arrPrintKuning($result1);

        // -------------------------------------------------------
        $this->db->select($koloms);
        $this->db->where($condites);
        $query = $this->db->get($tbl2);
        $result2 = $query->result();

        showLast_query("biru", count($result2));
        // arrPrintHijau($result2);
        // -------------------------------------------------------
        // $this->db->select($koloms);
        // $this->db->where($condites);
        $query = $this->db->get($tbl3);
        $result3 = $query->result();

        showLast_query("kuning", count($result3));
        // arrPrintHijau($result2);
        // -------------------------------------------------------
        $hasil = [];
        $in = 0;
        foreach ($result1 as $item) {
            $in++;
            $transaksi_id = $item->transaksi_id;

            $arrItem = (array)$item;

            $hasil[$transaksi_id]["10"][] = $arrItem;
            $hasil10[$transaksi_id] = $arrItem;
        }
        $in = 0;
        foreach ($result2 as $item) {
            $in++;
            $transaksi_id = $item->transaksi_id;

            $arrItem = (array)$item;

            $hasil40[$transaksi_id] = $arrItem;
            // $hasil[$transaksi_id]["40"][] = $arrItem;
        }
        $in = 0;
        foreach ($result3 as $item) {
            $in++;
            $transaksi_id = $item->transaksi_id;

            $arrItem = (array)$item;

            $hasil40010[$transaksi_id] = $arrItem;
            // $hasil[$transaksi_id]["40"][] = $arrItem;
        }

        $arrmerger = array_merge($result1,$result2);
        cekPink(count($arrmerger));


        // arrPrintKuning($hasil);
        $result = array();
        $result[10] = $hasil10;
        $result[40] = $hasil40;
        $result[40010] = $hasil40010;
        $result[3] = $arrmerger;
        $result["hasil"] = $hasil;

        return $result;
    }

    /**
     * callMutasiSupplier && callPembantuPaymentSourceSupplier
     * */
    public function cekMutasiSupplier()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $supplier_id = $_GET['extern_id'];

        // $dataUtamas = $this->callPembantuSupplier();
        $dataPaymentSrc = $this->callPembantuPaymentSourceSupplier($supplier_id);

        $dataMutasi = $this->callMutasiSupplier($supplier_id);
        // showLast_query("kuning");

        $dataMutasi10 = $dataMutasi["10"];
        $dataMutasi40 = $dataMutasi["40"];
        $dataMutasi40010 = $dataMutasi["40010"];
        $dataMutasiHasil = $dataMutasi["hasil"];
        // arrPrintKuning($dataPaymentSrc);
        // matiHere(__LINE__);
        $arrTambahan = array();
        foreach ($dataPaymentSrc as $item) {
            // $mid = $item->extern_id;
            $transaksi_id = $item["transaksi_id"];
            // ----------------------------------------------------------------
            $kredit_10 = 0;
            $debet_10 = 0;
            if(isset($dataMutasi10[$transaksi_id])){
                $data10 = $dataMutasi10[$transaksi_id];

                $kredit_10 = $data10["kredit"];
                $debet_10 = $data10["debet"];
            }
            $arrTambahan["debet_10"] = $debet_10;
            $arrTambahan["kredit_10"] = $kredit_10;
            // ----------------------------------------------------------------
            $kredit_40 = 0;
            $debet_40 = 0;
            if(isset($dataMutasi40[$transaksi_id])){
                $data40 = $dataMutasi40[$transaksi_id];

                $kredit_40 = $data40["kredit"];
                $debet_40 = $data40["debet"];
            }
            $arrTambahan["debet_40"] = $debet_40;
            $arrTambahan["kredit_40"] = $kredit_40;
            // --------------------
            $kredit_40010 = 0;
            $debet_40010 = 0;
            if(isset($dataMutasi40010[$transaksi_id])){
                $data40 = $dataMutasi40[$transaksi_id];

                $kredit_40010 = $data40["kredit"];
                $debet_40010 = $data40["debet"];
            }
            $arrTambahan["debet_40010"] = $debet_40010;
            $arrTambahan["kredit_40010"] = $kredit_40010;
            // ----------------------------------------------------------------

            unset($item["ids_his_intext"]);
            $arrItem = (array)$item;

            $masterData[] = $arrItem + $arrTambahan;
        }
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            "extern_id"         => array(
                "label" => "id",
            ),
            // "extern_nama"       => array(
            //     "label" => "supplier",
            // ),
            "transaksi_id"                 => array(
                "label" => "trid",
            ),
            // "counter_spd"       => array(
            //     "label" => "counter",
            //     // "format"     => "formatField_he_format",
            // ),
            "dtime" => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),

            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            "nomer"    => array(
                "label" => "nomer",
                    "format"     => "formatField_he_format",
                    "format_key" => "nomer",
            ),
            "oleh_nama"          => array(
                "label"      => "PIC",
                // "format"     => "formatField_he_format",
                // "format_key" => "fulldate",
            ),
            // "umur_status"       => array(
            //     "label"      => "overdue",
            //     "data_order" => "due_date",
            //
            // ),
            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),
            // "saldo_awal"        => array(
            //     "label"      => "Saldo awal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "total_kredit"      => array(
            //     "label"      => "nilai invoice pembelian",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "total_debet"       => array(
            //     "label"      => "dibayar",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "saldo_akhir"       => array(
            //     "label"      => "sisa hutang dr mutasi",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "tagihan"      => array(
                "label"      => "tagihan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "terbayar" => array(
                "label"      => "terbayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),

            "sisa"                   => array(
                "label"      => "sisa hutang dr payment src",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "kredit_10" => array(
                "label"      => "kredit 10",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "kredit_40" => array(
                "label"      => "kredit 40",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "kredit_400100" => array(
                "label"      => "kredit 40010",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
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
        //     "transaksi_id"  => array(
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
        //     // "sales_admin_id" => false,
        //     // "sales_man_id" => false,
        //     "kategori_nama" => array(
        //         "label" => "kategori",
        //         "kolom" => "kategori_nama",
        //     ),
        //     "merek_nama"    => array(
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
        // arrPrintPink($arrSummary);
        // $summariNilais = $arrSummary['nilai'];
        // $summariLabels = $arrSummary['label'];
        // $summariSubjectLabels = $arrSummary['kolom_key'];

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
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"        => "langsung_simple",
            "title"       => "komparasi mutasi-cache-payment src " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
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
        $this->load->view("hutang", $data);
    }

    public function cekMutasiSupplier2()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $supplier_id = $_GET['extern_id'];

        $tbl1 = $this->tbl1;
        $tbl2 = $this->tbl6;
        $koloms = [
            "rekening",
            "extern_id",
            "extern_nama",
            "transaksi_id",
            "debet",
            "kredit",
        ];
        $condites = [
            "extern_id"     => $supplier_id,
            "year(dtime)>=" => $tahun
        ];
        // -------------------------------------------------------
        $this->db->select($koloms);
        // $this->db->where($condites);
        $query = $this->db->get($tbl1);
        $result1 = $query->result();
        showLast_query("kuning");
        $hasil = [];
        $in = 0;
        foreach ($result1 as $item) {
            $in++;
            $transaksi_id = $item->transaksi_id;

            $arrItem = (array)$item;

            // $hasil[$transaksi_id]["10"][] = $arrItem;
            $hasil10[$transaksi_id] = $arrItem;
        }
        // ---------------------------------------------------------
        // $this->db->where($condites);
        $query = $this->db->get($tbl2);
        $result1 = $query->result();
        $hasil = [];
        $in = 0;
        foreach ($result1 as $item) {
            $in++;
            $transaksi_id = $item->transaksi_id;

            $arrItem = (array)$item;

            // $hasil[$transaksi_id]["10"][] = $arrItem;
            $hasil40010[$transaksi_id] = $arrItem;
        }
        // ------------------------------------------------------------
        showLast_query("merah",count($result1));

        $dataMutasi10 = $hasil10;
        $dataMutasi40010 = $hasil40010;

        // arrPrintKuning($dataMutasi40010);
        // matiHere(__LINE__);
        $arrTambahan = array();
        foreach ($dataMutasi10 as $transaksi_id => $item) {
            // $mid = $item->extern_id;
            $transaksi_id = $item["transaksi_id"];
            // ----------------------------------------------------------------
            $kredit_10 = 0;
            $debet_10 = 0;
            if(isset($dataMutasi10[$transaksi_id])){
                $data10 = $dataMutasi10[$transaksi_id];

                $kredit_10 = $data10["kredit"];
                $debet_10 = $data10["debet"];
            }
            $arrTambahan["debet_10"] = $debet_10;
            $arrTambahan["kredit_10"] = $kredit_10;

            // --------------------
            $kredit_40010 = 0;
            $debet_40010 = 0;
            if(isset($dataMutasi40010[$transaksi_id])){
                $data40 = $dataMutasi40010[$transaksi_id];

                $kredit_40010 = $data40["kredit"];
                $debet_40010 = $data40["debet"];
            }
            $arrTambahan["debet_40010"] = $debet_40010;
            $arrTambahan["kredit_40010"] = $kredit_40010;
            $arrTambahan["selisih"] = $kredit_10 - $kredit_40010;
            // ----------------------------------------------------------------

            unset($item["ids_his_intext"]);
            $arrItem = (array)$item;

            $masterData[] = $arrItem + $arrTambahan;
        }
        arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            "extern_id"         => array(
                "label" => "id",
            ),
            "extern_nama"       => array(
                "label" => "supplier",
            ),
            "transaksi_id"                 => array(
                "label" => "trid",
            ),
            // "counter_spd"       => array(
            //     "label" => "counter",
            //     // "format"     => "formatField_he_format",
            // ),
            // "dtime" => array(
            //     "label"  => "tanggal",
            //     "format" => "formatField_he_format",
            // ),

            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            // "nomer"    => array(
            //     "label" => "nomer",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "oleh_nama"          => array(
            //     "label"      => "PIC",
            //     // "format"     => "formatField_he_format",
            //     // "format_key" => "fulldate",
            // ),
            // "umur_status"       => array(
            //     "label"      => "overdue",
            //     "data_order" => "due_date",
            //
            // ),
            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),
            // "saldo_awal"        => array(
            //     "label"      => "Saldo awal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "total_kredit"      => array(
            //     "label"      => "nilai invoice pembelian",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "total_debet"       => array(
            //     "label"      => "dibayar",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "saldo_akhir"       => array(
            //     "label"      => "sisa hutang dr mutasi",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "tagihan"      => array(
            //     "label"      => "tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "terbayar" => array(
            //     "label"      => "terbayar",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            //
            // "sisa"                   => array(
            //     "label"      => "sisa hutang dr payment src",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "kredit_10" => array(
                "label"      => "kredit 10",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            // "kredit_40" => array(
            //     "label"      => "kredit 40",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),

            "kredit_40010" => array(
                "label"      => "kredit 40010",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "selisih" => array(
                "label"      => "selisih",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
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
        //     "transaksi_id"  => array(
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
        //     // "sales_admin_id" => false,
        //     // "sales_man_id" => false,
        //     "kategori_nama" => array(
        //         "label" => "kategori",
        //         "kolom" => "kategori_nama",
        //     ),
        //     "merek_nama"    => array(
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
        // arrPrintPink($arrSummary);
        // $summariNilais = $arrSummary['nilai'];
        // $summariLabels = $arrSummary['label'];
        // $summariSubjectLabels = $arrSummary['kolom_key'];

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
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"        => "langsung_simple",
            "title"       => "komparasi mutasi-cache-payment src " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
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
        $this->load->view("hutang", $data);
    }
}