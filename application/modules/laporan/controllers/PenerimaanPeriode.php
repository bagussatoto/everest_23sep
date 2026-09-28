<?php

class PenerimaanPeriode extends MX_Controller
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
        $this->modul_path = base_url() . "penerimaan/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("749", "982", "382spd");
    }

    public function callTransaksiCounterJenis($jenis = "")
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
            "jenis",
            "nomer",
        );
        $this->db->select($coloms);
        $wheres = array(
            "4464", "464", "749", "489", "4467"
        );
        // $this->db->where_in("jenis",$wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }
        // showLast_query("hijau");
        // arrPrintHijau($src_datas);
        return $src_datas;
    }

    public function getRaw($date1, $date2)
    {
        $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__1010010010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $transaksi_jenis_masuk = array(
            "4464",
            "749",
            "4467",
        );
        // $transaksi_jenis_keluar = array(
        //     "464", "489"
        // );
        $this->db->where_in("jenis", $transaksi_jenis_masuk);
        $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        showLast_query("biru");

        return $tmpA;
    }

    public function getReferencesData()
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
            // "jenis",
            // "nomer",
        );
        $this->db->select($coloms);
        $wheres = array(
            "4464", "464", "749", "489", "4467"
        );
        // $this->db->where_in("jenis",$wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }
        showLast_query("hijau");
        arrPrintHijau($src_datas);
        return $src_datas;
    }

    public function creatPivot($data)
    {
        // Fungsi untuk membuat laporan pivot

        $pivot = array();

        foreach ($data as $row) {
            $transaksi_id = $row['transaksi_id'];
            $transaksi_no = $row['transaksi_no'];
            // $merek_nama = $row['merek_nama'];
            $merek_nama = $row['extern2_nama'];
            // $produk_nama = $row['produk_nama'];
            $produk_nama = $row['pihak_nama'];
            // $produk_id = $row['produk_id'];
            $produk_id = $row['pihak_id'];
            $qty_kredit = $row['qty_kredit'];
            $sub_harga_include_ppn = $row['dibayar'];
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

            $pivot[$merek_nama]['dibayar'] += $sub_harga_include_ppn;
            // $pivot[$merek_nama]['total_qty_kredit'] += $qty_kredit;
            // $pivot[$merek_nama]['total_harga'] += $harga;
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

    // -----------------------------------
    public function cekHarian()
    {
        $dtime_now = $dtime_now_1 = dtimeNow('Y-m-d');
        $jenis = $this->uri->segment(3);
        $jenis = '5822spo';

        $old = $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $date_1 = isset($_GET['date1']) && (strlen($_GET['date1']) > 0) ? $_GET['date1'] : $dtime_now_1;
        $date_2 = isset($_GET['date2']) && (strlen($_GET['date2']) > 0) ? $_GET['date2'] : $dtime_now;


        $src = $this->getRaw($date_1, $date_2);
        // showLast_query("merah");
        // arrPrintKuning($src);

        return $src;
    }

    public function cekPenjualan($ar_datas)
    {
        $referenceId = array();
        foreach ($ar_datas as $ar_data) {
            $transaksi_id = $ar_data['transaksi_id'];
            $referenceId[$transaksi_id] = explode(",", $ar_data['references_data']);
            $arDatas[$transaksi_id] = $ar_data;

        }
        // arrPrint($referenceId);
        // arrPrint($ar_datas);

        $tbl_1 = "__raw_rek_pembantu__4010";
        foreach ($referenceId as $arId => $penjualanIds) {
            foreach ($penjualanIds as $penjualanId) {
                $condites = array(
                    "transaksi_id" => $penjualanId,
                );
                $this->db->where($condites);
                $src = $this->db->get($tbl_1)->result_array();
                // showLast_query("kuning");
                // cekKuning("$arId");

                $m_arDatas = addPrefixKeyM_he_format($arDatas[$arId]);
                // arrPrintHijau($m_arDatas);
                // arrPrint($src);

                foreach ($src as $item) {
                    $mix_srcs = $m_arDatas + $item;

                    $mixDatas[] = $mix_srcs;
                }

                // arrPrintKuning($mix_srcs);

            }
        }

        return $mixDatas;
    }

    public function viewHarian()
    {
        $title_now = isset($aktivitas) ? "Sales Order" : "Penerimaan AR";
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');


        $ar_datas = $this->cekHarian();
        $master_data = $this->cekpenjualan($ar_datas);

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
            "jenis"          => array(
                "label" => "jenis",
                "kolom" => "jenis",
            ),
            "kategori_id"    => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($master_data);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        // $arrHeaders = array(
        //     "dtime"                      => array(
        //         "label"      => "tanggal",
        //         "format"     => "formatField_he_format",
        //         "format_key" => "fulldate",
        //         "summary"    => false,
        //         // "collapsible" => array(
        //         //     "layout" => false,
        //         //     "key"    => false,
        //         //     "target" => "laporan/Penjualan/ceknotaHarian",
        //         //     // "target" => "laporan/Crm/produkoutstandingbln",
        //         // ),
        //         "links"      => array(
        //             "target"  => "laporan/Penjualan/ceknotaHarian",
        //             "title"   => "Transaksi hari",
        //             // "key"     => false,
        //             "key"     => "fulldate",
        //             "heading" => "fulldate",
        //         ),
        //     ),
        //     "nomer"                      => array(
        //         "label"   => "nomer transaksi",
        //         "format"  => "formatField_he_format",
        //         // "format_key" => "fulldate",
        //         "summary" => false,
        //         "links"   => array(
        //             "target"  => "laporan/Penjualan/ceknotaHarian",
        //             "title"   => "Transaksi hari",
        //             // "key"     => false,
        //             "key"     => "fulldate",
        //             "heading" => "fulldate",
        //         ),
        //     ),
        //     "olehName"                   => array(
        //         "label"   => "PIC",
        //         "summary" => false,
        //     ),
        //     "pihakName"                  => array(
        //         "label"   => "konsumen",
        //         "summary" => false,
        //     ),
        //     "paymentMethod"              => array(
        //         "label"   => "cara pembayaran",
        //         "summary" => false,
        //     ),
        //     "cash_account__folders_nama" => array(
        //         "label"   => "bank",
        //         "summary" => false,
        //     ),
        //     "cash_account__label"        => array(
        //         "label"   => "rekening bank",
        //         "summary" => false,
        //     ),
        //     "description"                => array(
        //         "label"   => "catanan pembayaran",
        //         "summary" => false,
        //     ),
        //     "tagihan"                    => array(
        //         "label"      => "tagihan",
        //         "format"     => "formatField_he_format",
        //         "format_key" => "harga",
        //         "summary"    => true,
        //     ),
        //     "nilai_bayar"                => array(
        //         "label"      => "bayar",
        //         "format"     => "formatField_he_format",
        //         "format_key" => "harga",
        //         "summary"    => true,
        //     ),
        //     "new_sisa"                   => array(
        //         "label"      => "kurang",
        //         "format"     => "formatField_he_format",
        //         "format_key" => "harga",
        //         "logics"     => array(
        //             ".nilai<0" => ""
        //         ),
        //         "summary"    => true,
        //     ),
        // );
        $arrHeaders = array(
            "m_dtime"           => array(
                "label"      => "tanggal ar",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
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
            "dtime"             => array(
                "label"      => "tanggal jual",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
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
            "m_transaksi_no"    => array(
                "label"   => "nomer ar",
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
            "transaksi_no"      => array(
                "label"   => "nomer so",
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
            // "m_references_data" => array(
            //     "label" => "ref id",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "logics"     => array(
            //         ".nilai<0" => ""
            //     ),
            //     "summary"    => false,
            // ),
            "m_oleh_nama"       => array(
                "label"   => "PIC",
                "summary" => false,
            ),
            "pihak_nama"        => array(
                "label"   => "konsumen",
                "summary" => false,
            ),
            "pembayaran_nama"   => array(
                "label"   => "penjualan",
                "summary" => false,
            ),
            "m_pembayaran_nama" => array(
                "label"   => "cara pembayaran",
                "summary" => false,
            ),
            "m_produk_kode"     => array(
                "label"   => "bank",
                "summary" => false,
            ),
            // "pembayaran_nama" => array(
            //     "label"   => "metode",
            //     "summary" => false,
            // ),
            "m_produk_nama"     => array(
                "label"   => "rekening",
                "summary" => false,
            ),

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

            "m_tagihan" => array(
                "label"      => "tagihan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "summary"    => true,
            ),
            "m_dibayar" => array(
                "label"      => "bayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "summary"    => true,
            ),
            "m_sisa"    => array(
                "label"      => "kurang",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "logics"     => array(
                    ".nilai<0" => ""
                ),
                "summary"    => true,
            ),
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
            "title"                => "Data $title_now Per $subjek_0 $strDate",
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
            // "child_data"  => $src_harians,
            // "margin_data" => isset($src_margins) ? $src_margins : array(),
            // "margin_avg"  => $margin_avg,
            // "qty_data"    => isset($src_qty) ? $src_qty : array(),
            // "total_bawah" => $total_bawah,

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
        // $this->load->view("penerimaan_periode", $data);
        $this->load->view("penjualanPeriode", $data);
    }

    //-------------------------------------

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
            // "kategori_id"     => array(
            //     "label" => "kategori",
            //     "kolom" => "kategori_nama",
            // ),
            "pembayaran_nama" => array(
                "label" => "cara pembayaran",
                "kolom" => "pembayaran_nama",
            ),
            // "sales_admin_id"  => array(
            //     "label" => "sales admin",
            //     "kolom" => "sales_admin_nama",
            // ),
            // "salesman_id"     => array(
            //     "label" => "salesman",
            //     "kolom" => "salesman_nama",
            // ),
            "pihak_id"        => array(
                "label" => "konsumen",
                "kolom" => "pihak_nama",
            ),
            "transaksi_id"    => array(
                "label" => "transaksi",
                "kolom" => array(
                    "nomer_counter"       => array(
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
                "label" => "rekening",
                "kolom" => array(
                    "produk_nama"         => array(
                        "label" => "rekening"
                    ),
                    // "qty_kredit"          => array(
                    //     "label"   => "jml",
                    //     "summary" => true,
                    //     "attr"    => "class='text-right'",
                    // ),
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

    public function cekRow()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        // $jenis = "5822spd";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $transaksi_jenis = array(
        //     "9822", "5822spd"
        // );
        // $this->db->where_in("jenis", $transaksi_jenis);
        // $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        $ar_datas = $tmpA = $this->getRaw($date1, $date2);
        //        showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $tagihans = $this->callPaymentSource();
        $tagihans = array();
        $counterSpd = array();
        $counterSpd = $this->callTransaksiCounterJenis();
        // arrPrintKuning($tagihans);

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $spekProduks = $pr->callSpecs();


        $itemtambahan = array();
        foreach ($tmpA as $item) {
            $transaksi_references_data = $item['references_data'];
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

            // $itemtambahan['c_ppn'] = $transaksi_ppn;
            // $itemtambahan['c_sub_total'] = $transaksi_inc_ppn;

            $counters = $counterSpd[$transaksi_id];
            $counter_jenis = $counters["_company_stepCode"];
            $itemtambahan['counter_jenis'] = $counter_jenis;
            $itemtambahan['nomer_counter'] = $transaksi_no_1 . "-$counter_jenis";
            $ref_nomer = $counterSpd[$transaksi_references_data * 1]['nomer'];
            $itemtambahan['ref_nomer'] = $ref_nomer;

            $produk_speks = $spekProduks[$transaksi_produk_id];
            $itemtambahan['merek_nama'] = $produk_speks->merek_nama;
            $itemtambahan['kategori_nama'] = $produk_speks->kategori_nama;
            // arrPrint($produk_speks);

            $masterData[] = $item + $itemtambahan;
        }
        // $masterData = $this->cekpenjualan($ar_datas);
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            // "counter_jenis"     => array(
            //     "label" => "counter",
            //     // "format"     => "formatField_he_format",
            // ),
            // "transaksi_id"      => array(
            //     "label" => "trid",
            // ),
            "transaksi_tanggal" => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "transaksi_jam"     => array(
                "label" => "jam",
                // "format"     => "formatField_he_format",
            ),

            "oleh_nama"       => array(
                "label" => "pic",
            ),
            "pihak_nama"      => array(
                "label" => "konsumen",
            ),
            // "pihak_tipe"        => array(
            //     "label" => "tipe",
            // ),
            "nomer_counter"   => array(
                "label" => "no. penerimaan",
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
            // "jenis"             => array(
            //     "label" => "jenis",
            // ),
            "extern2_nama"    => array(
                "label" => "keterangan",
            ),
            // "references_data"      => array(
            //     "label" => "data",
            //     // "type"  => "string",
            //     // "attr"  => "class='bg-warning'",
            // ),
            "ref_nomer"       => array(
                "label"      => "no. referensi",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                // "type"  => "string",
                // "attr"  => "class='bg-warning'",
            ),
            "produk_kode"     => array(
                "label" => "bank",
                // "type"  => "string",
                // "attr"  => "class='bg-warning'",
            ),
            "produk_nama"     => array(
                "label" => "rekening",
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

            // "sub_diskon"      => array(
            //     "label"      => "diskon",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),

            // "harga"  => array(
            //     "label"      => "harga per unit",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "debet"             => array(
            //     "label"      => "debet",
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

            // "sub_ppn_nilai" => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // ---------------------
            // "c_sub_total"   => array(
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
            /*-----------------------------------------------------------------------------------------------------*/
            // "tagihan"         => array(
            //     "label"      => "tagihan inv. incl. ppn",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "dibayar"         => array(
                "label"      => "diterima incl. ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            "sisa"            => array(
                "label"      => "sisa tagihan incl. ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "ppn_nilai"            => array(
                "label"      => "ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "pph23"            => array(
                "label"      => "pph-23",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "pph22_nilai"            => array(
                "label"      => "pph-22",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
        );

        /* ---------------------------------------------
         * summary per-peran
         * ---------------------------------------------*/
        $this->setNilaiKey("dibayar");
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
            "jenis"          => array(
                "label" => "jenis",
                "kolom" => "jenis",
            ),
            "kategori_id"    => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
        );
        // $this->setKolomNilai($kolomNilais);
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
            //            "title" => "Laporan pengiriman " . $judul_lap,
            "title"                => "History Penerimaan Kas (timeline) " . $judul_lap,
            "subTitle"             => "Raw data penjualan",
            "modul_path"           => base_url() . 'penjualan/',
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
        // $this->load->view("penjualanPeriode", $data);
        $this->load->view("penerimaan_periode", $data);
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
        // $counterSpd = $this->callTransaksiCounterJenis($jenis);
        // arrPrintKuning(array_slice($tagihans,2));
        // arrPrintKuning(array_slice($tagihanDuedates,2));
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $spekProduks = $pr->callSpecs();

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

            // $counters = $counterSpd[$transaksi_id];
            // $itemtambahan['counter_spd'] = $counters["_company_stepCode"];
            // $itemtambahan['total_ppn'] = isset($tagihan['24103']) ? $tagihan['ppn'] : 0;;
            // $produk_speks = $spekProduks[$transaksi_produk_id];
            // $itemtambahan['merek_id'] = $produk_speks->merek_id;
            // $itemtambahan['merek_nama'] = $produk_speks->merek_nama;
            // $itemtambahan['kategori_nama'] = $produk_speks->kategori_nama;


            // cekHijau("$pembayaran_nama $transaksi_id");
            // $tempos = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
            // arrPrintPink($tempos);

            $masterData[] = $item + $itemtambahan;
        }
        // arrPrintPink(array_slice($masterData, 1, 1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));

        $arrHeaders = array(
            "label"   => array(
                "label" => "Jenis penerimaan",
                "attr"  => "class='font-size-1-5 text-capitalize'",
            ),

            // "total_qty_kredit"      => array(
            //     "label"      => "total qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr" => "class='text-right'",
            //     "summary" => true,
            // ),
            "dibayar" => array(
                "label"      => "total nilai",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right font-size-1-5'",
                "summary"    => true,
            ),
            "rincian" => array(
                "label" => "Nama konsumen",
                "sub"   => array(
                    "label"     => array(// "label" => 'produk'
                    ),
                    // "total"     => array(
                    //     "label"      => 'qty',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary" => true,
                    // ),
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
        $this->setNilaiKey("dibayar");
        $kolomNilais = array(
            "pembayaran_nama" => false,
            "transaksi_id"    => false,
            // "tagihan_status" => array(
            //     "label" => "status",
            //     "kolom" => "tagihan_status",
            // ),
            "extern2_nama"    => array(
                "label" => "Jenis pembayaran",
                "kolom" => "extern2_nama",
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
            if ($date1 == $date2) {
                $judul_lap = formatTanggal($get_date1, 'd F Y');
            }
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
            "title"                => "Laporan Penerimaan Kas " . $judul_lap,// isinya ada 749 (penerimaan ar), 4464 (penjualan tunai)
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
        $this->load->view("penerimaan_periode", $data);
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
        $date_start_setelahnya = aftersMonth($date2);
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
        // $add_td = "<td></td>";
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
            "title"       => "Penerimaan kas $strDate",
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
            // "sum_satu" => base_url() . "laporan/penerimaanPeriode/viewHarian" . "$strGet",
            "sum_satu"    => base_url() . "laporan/penerimaanPeriode/cekSumRow" . "$strGet",
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