<?php

class RealisasiDiskon extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "pembelian/";
        $this->default_limit = 200;
        $this->jenisTr = "467";
        // $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("467", "460", "461", "967");
    }

    public function produk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $this->db->limit(100);
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "asc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTrs);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"              => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "extern_id"          => array(
                "label" => "iD",
            ),
            "kode"               => array(
                "label" => "kode",
            ),
            "nama"               => array(
                "label" => "produk",
            ),
            "no_part"            => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"              => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama"     => array(
                "label" => "vendor",
            ),
            "mata_uang"          => array(
                "label" => "mata uang",
                "attr"  => "class='bg-success'",
            ),
            "mata_uang_kurs"     => array(
                "label"  => "kurs",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-danger'",
            ),
            "i_ppv_index__nilai" => array(
                "label" => "indeks ppv",
                "attr"  => "class='text-right'",
            ),
            // --
            // "i_harga"          => array(
            //     "label"  => "hpp IDR/USD",
            //     "format" => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "i_ppv"          => array(
            //     "label"  => "ppv",
            //     "format" => "formatField_he_format",
            //     "attr" => "class='text-right'",
            // ),
            // "i_hpp_nppv"          => array(
            //     "label"  => "i_hpp_nppv",
            //     "format" => "formatField_he_format",
            // ),
            // "i_exchange__hpp_nppv"          => array(
            //     "label"  => "hpp IDR",
            //     "format" => "formatField_he_format",
            // ),
            "qty_debet"          => array(
                "label"  => "qty",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-warning'",
            ),
            "i_harga"            => array(
                "label"  => "cost",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-info'",
            ),
            "i_sub_harga"        => array(
                "label"  => "cost value",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-success'",
            ),
            "i_ppv"              => array(
                "label"  => "ppv",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-info'",
            ),
            "i_sub_ppv"          => array(
                "label"  => "ppv value",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-success'",
            ),
            "i_hpp_nppv"         => array(
                "label"  => "hpp",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-info'",
            ),
            "harga"              => array(
                "label"  => "hpp (IDR)",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-danger'",
            ),
            "i_sub_hpp_nppv"     => array(
                "label"  => "hpp value",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-success'",
            ),

            // "mata_uang_kurs"          => array(
            //     "label"  => "kurs",
            //     "format" => "formatField_he_format",
            //     "attr" => "class='text-right bg-danger'",
            // ),
            // "harga"          => array(
            //     "label"  => "hpp (IDR)",
            //     "format" => "formatField_he_format",
            // ),
            "debet"              => array(
                "label"  => "hpp value (IDR)",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-danger'",
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "default",
            "title"       => "Aktifitas pembelian $strDate",
            "subTitle"    => "Aktifitas pembelian $strDate",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "467",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            "sum_satu"    => base_url() . "laporan/Pembelian/produkperproduk" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Pembelian/produkvendor" . "$strGet",
            "sum_tiga"    => base_url() . "laporan/Pembelian/produkpertransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkraw()
    {
        // arrPrintHijau($_REQUEST);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $masterData_ori = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // cekBiru($masterData_ori);
        if (isset($_GET['suppliers_id'])) {
            $hasilOlahan_bysupplier = array();
            foreach ($masterData_ori as $item) {
                if (isset($_GET['suppliers_id']) && $item['suppliers_id'] == $_GET['suppliers_id']) {
                    $hasilOlahan_bysupplier[] = $item;
                }
            }
            $masterData = $hasilOlahan_bysupplier;
        }
        else {
            $masterData = $masterData_ori;
        }
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // $arrHeaders = array(
        //     "kode"           => array(
        //         "label" => "kode",
        //     ),
        //     "nama"           => array(
        //         "label" => "produk",
        //     ),
        //     "no_part"        => array(
        //         "label" => "no part",
        //     ),
        //     // "kendaraan_nama" => array(
        //     //     "label" => "kendaraan",
        //     // ),
        //     "nomer"          => array(
        //         "label"  => "nomer",
        //         "format" => "formatField_he_format",
        //     ),
        //     "dtime"          => array(
        //         "label"  => "tanggal",
        //         "format" => "formatField_he_format",
        //     ),
        //     // "suppliers_nama" => array(
        //     //     "label" => "vendor",
        //     // ),
        //     // "keterangan"     => array(
        //     //     "label" => "note",
        //     // ),
        //     // --
        //     "harga"          => array(
        //         "label"  => "hpp",
        //         "format" => "formatField_he_format",
        //     ),
        //     "qty_debet"      => array(
        //         "label"  => "jumlah",
        //         "format" => "formatField_he_format",
        //     ),
        //     "debet"          => array(
        //         "label"  => "nilai",
        //         "format" => "formatField_he_format",
        //         "summary"    => true,
        //     ),
        //
        // );
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "keterangan"     => array(
                "label" => "note",
            ),
            "kode"           => array(
                "label" => "kode",
            ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            "kendaraan_nama" => array(
                "label" => "kendaraan",
            ),
            "satuan"         => array(
                "label" => "satuan",
            ),
            // --
            "qty_debet"      => array(
                "label"  => "qty",
                "format" => "formatField_he_format",
            ),
            "i_harga"        => array(
                "label"  => "hpp bruto",
                "format" => "formatField_he_format",
            ),
            "i_disc"         => array(
                "label"  => "disc",
                "format" => "formatField_he_format",
            ),
            "i_discPersen"   => array(
                "label"  => "disc %",
                "format" => "formatField_he_format",
            ),
            "harga"          => array(
                "label"  => "hpp netto",
                "format" => "formatField_he_format",
            ),


            "debet" => array(
                "label"   => "jumlah",
                "format"  => "formatField_he_format",
                "summary" => true,
            ),

        );
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "raw data $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "rawdata_" . randomNumber(1),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Pembelian/produkpertransaksi" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Pembelian/produkvendor" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkvendor()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTrs);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];

            $olahan[$transaksi_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan = array();
        foreach ($olahan as $tr_id => $itemParam) {
            // arrPrintWebs($itemParam);
            $customer_id = $itemParam['m_pihakID'];

            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai = $itemParam['debet'];
            if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                $hasilOlahan[$customer_id]['sumBruto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai_2 = $itemParam['m_harga'];
            if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
                $hasilOlahan[$customer_id]['sumNetto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
            // //---------------------------------------------------------------------------------
            // $sub_total_disc = $itemParam['m_disc'];
            // if (!isset($hasilOlahan[$customer_id]['sumTotalDisc'])) {
            //     $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
            //---------------------------------------------------------------------------------
            $sub_ppn = $itemParam['m_ppn'];
            if (!isset($hasilOlahan[$customer_id]['sumPpn'])) {
                $hasilOlahan[$customer_id]['sumPpn'] = 0;
            }
            $hasilOlahan[$customer_id]['sumPpn'] += $sub_ppn;
            //---------------------------------------------------------------------------------
            $sub_ppv = $itemParam['i_sub_ppv'];
            if (!isset($hasilOlahan[$customer_id]['sumPpv'])) {
                $hasilOlahan[$customer_id]['sumPpv'] = 0;
            }
            $hasilOlahan[$customer_id]['sumPpv'] += $sub_ppv;
            //-------------------------------------------------------------------------------
            $sub_harga = $itemParam['i_sub_harga'];
            if (!isset($hasilOlahan[$customer_id]['sumHarga'])) {
                $hasilOlahan[$customer_id]['sumHarga'] = 0;
            }
            $hasilOlahan[$customer_id]['sumHarga'] += $sub_harga;
            //---------------------------------------------------------------------------------
            $sub_hpp_ppv = $itemParam['i_sub_hpp_nppv'];
            if (!isset($hasilOlahan[$customer_id]['sumHppPpv'])) {
                $hasilOlahan[$customer_id]['sumHppPpv'] = 0;
            }
            $hasilOlahan[$customer_id]['sumHppPpv'] += $sub_hpp_ppv;
            //---------------------------------------------------------------------------------

        }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_pihakID'];
            $hasilOlahan_1[$customer_id] = $itemParam + $hasilOlahan[$customer_id];
            // $hasilOlahan_1[$customer_id] = $itemParam;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            // "dtime"          => array(
            //     "label"  => "tanggal",
            //     "format" => "formatField_he_format",
            //     "summary" => false,
            // ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            // "nama"           => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),
            "suppliers_nama" => array(
                "label" => "vendor",
                "links" => array(
                    // "target" => "laporan/Pembelian/produkpertransaksi",
                    "target" => "laporan/Pembelian/produkraw",
                    "title"  => "Transaksi per vendor",
                    "key"    => "suppliers_id",
                ),
            ),
            "mata_uang"      => array(
                "label" => "mata uang",
            ),
            // --
            // "sumNetto"          => array(
            //     "label"  => "bruto",
            //     "format" => "formatField_he_format",
            //     "summary"    => true,
            // ),
            "sumHarga"       => array(
                "label"     => "cost",
                "format"    => "formatField_he_format",
                "attr"      => "class='text-right bg-success'",
                "attr_head" => "class='text-right'",
                // "summary"    => true,
            ),
            "sumPpv"         => array(
                "label"     => "ppv",
                "format"    => "formatField_he_format",
                // "summary" => true,
                "attr"      => "class='text-right bg-success'",
                "attr_head" => "class='text-right'",
            ),
            "sumHppPpv"      => array(
                "label"     => "hpp",
                "format"    => "formatField_he_format",
                // "summary"    => true,
                "attr"      => "class='text-right bg-success'",
                "attr_head" => "class='text-right'",
            ),
            // "sumPpn"      => array(
            //     "label"  => "PPN",
            //     "format" => "formatField_he_format",
            //     "summary"    => true,
            // ),
            "mata_uang_kurs" => array(
                "label"      => "kurs",
                "format"     => "formatField_he_format",
                "format_key" => "netto",
            ),
            "sumBruto"       => array(
                "label"      => "hpp (IDR)",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "summary pembelian per vendor $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkperproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTrs);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        $hasilOlahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];
            $extern_id = $masterDatum['extern_id'];
            $customer_id = $extern_id;
            $jenis = $masterDatum['jenis'];
            $harga = $masterDatum['harga'];
            $debet = $masterDatum['debet'];
            $i_harga = $masterDatum['i_harga'];
            $i_sub_harga = $masterDatum['i_sub_harga'];
            $i_hpp_nppv = $masterDatum['i_hpp_nppv'];
            $i_sub_hpp_nppv = $masterDatum['i_sub_hpp_nppv'];
            $i_ppv = $masterDatum['i_ppv'];
            $i_jml = $masterDatum['i_jml'];
            $i_disc = isset($masterDatum['i_sub_disc']) ? $masterDatum['i_sub_disc'] : 0;
            $i_sub_ppn = $masterDatum['i_sub_ppn'];
            $i_subHppNppn = $masterDatum['i_sub_hpp_nppn'];
            $sub_transaksi_nilai = $masterDatum['i_sub_harga'];

            $olahan[$extern_id] = $masterDatum;
            $jenis = $masterDatum['jenis'];
            $harga = $masterDatum['harga'];
            switch ($jenis) {
                case "967":
                    break;
                default:
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                        $hasilOlahan[$customer_id]['sumBruto'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumJml'])) {
                        $hasilOlahan[$customer_id]['sumJml'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumJml'] += $i_jml;
                    // //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumDisc'])) {
                        $hasilOlahan[$customer_id]['sumDisc'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumDisc'] += $i_disc;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumPpn'])) {
                        $hasilOlahan[$customer_id]['sumPpn'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumPpn'] += $i_sub_ppn;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
                        $hasilOlahan[$customer_id]['sumNetto'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumNetto'] += $i_subHppNppn;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumDebet'])) {
                        $hasilOlahan[$customer_id]['sumDebet'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumDebet'] += $debet;
                    //---------------------------------------------------------------------------------
                    break;
            }
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan = array();
        // foreach ($olahan as $tr_id => $itemParam) {
        //     // arrPrintWebs($itemParam);
        //     $customer_id = $itemParam['m_pihakName'];
        //     $jenis = $itemParam['jenis'];
        //     $harga = $itemParam['harga'];
        //     $i_jml = $itemParam['i_jml'];
        //     $i_disc = $itemParam['i_disc'];
        //     $i_subHppNppn = $itemParam['i_subHppNppn'];
        //
        //     // if (!isset($hasilOlahan[$customer_id]['harga'])) {
        //     //     $hasilOlahan[$customer_id]['sumJml'] = 0;
        //     // }
        //     // $hasilOlahan[$customer_id]['sumJml'] += $i_jml;
        // }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $tr_id;
            $hasilOlahan_1[$customer_id] = $itemParam + $hasilOlahan[$customer_id];
            // $hasilOlahan_1[$customer_id] = $itemParam;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            // "dtime"          => array(
            //     "label"  => "tanggal",
            //     "format" => "formatField_he_format",
            //     "summary" => false,
            // ),
            "kode"        => array(
                "label" => "kode",
            ),
            "extern_nama" => array(
                "label" => "produk",
                // "links" => array(
                //     // "target" => "laporan/Pembelian/produkpertransaksi",
                //     "target" => "laporan/Pembelian/produkraw",
                //     "title"  => "Transaksi per vendor",
                //     "key"    => "suppliers_id",
                // ),
            ),
            "no_part"     => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),

            // "kode" => array(
            //     "label" => "kode",
            // ),
            // --
            // "i_harga"          => array(
            //     "label"  => "hpp",
            //     "format" => "formatField_he_format",
            // ),
            // "i_ppv"      => array(
            //     "label"  => "ppv",
            //     "format" => "formatField_he_format",
            // ),
            // "i_hpp_nppv"      => array(
            //     "label"  => "hpp ppv",
            //     "format" => "formatField_he_format",
            // ),
            "sumJml"      => array(
                "label"  => "Qty",
                "format" => "formatField_he_format",
            ),
            // "sumBruto"       => array(
            //     "label"      => "nilai bruto",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "bruto",
            //     "summary"    => true,
            // ),
            // "sumDisc"       => array(
            //     "label"      => "diskon",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "bruto",
            //     "summary"    => true,
            // ),
            // "sumPpn"       => array(
            //     "label"      => "ppn",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "bruto",
            //     "summary"    => true,
            // ),
            "sumDebet"    => array(
                "label"      => "nilai",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "summary pembelian produk $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "perproduk",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkpertransaksi()
    {
        // arrPrintHijau(url_segment());
        // arrPrintHijau($_REQUEST);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // elseif (isset($_GET['suppliers_id'])){
        //     $condites = array(
        //         "suppliers_id" => $_GET['suppliers_id'],
        //     );
        //     $this->db->where($condites);
        // }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];

            $olahan[$transaksi_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan = array();
        foreach ($olahan as $tr_id => $itemParam) {
            // arrPrintWebs($itemParam);
            $customer_id = $itemParam['m_pihakID'];

            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai = $itemParam['m_hpp_nppn'];
            if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                $hasilOlahan[$customer_id]['sumBruto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
            //---------------------------------------------------------------------------------
            // $sub_transaksi_nilai_2 = $itemParam['m_harga_nett3'];
            // if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
            //     $hasilOlahan[$customer_id]['sumNetto'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
            // //---------------------------------------------------------------------------------
            // $sub_total_disc = $itemParam['m_total_disc'];
            // if (!isset($hasilOlahan[$customer_id]['sumTotalDisc'])) {
            //     $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
            //---------------------------------------------------------------------------------

        }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_pihakID'];

            $hasilOlahan_1[$tr_id] = $itemParam + $hasilOlahan[$customer_id];

            if (isset($_GET['suppliers_id']) && $customer_id == $_GET['suppliers_id']) {
                // $hasilOlahan_1 = array();
                $hasilOlahan_bysupplier[$tr_id] = $itemParam + $hasilOlahan[$customer_id];
            }
        }

        if (isset($_GET['suppliers_id'])) {
            // cekHijau(__LINE__);
            $masterData = $hasilOlahan_bysupplier;
        }
        else {
            $masterData = $hasilOlahan_1;
        }

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"   => "tanggal",
                "format"  => "formatField_he_format",
                "summary" => false,
            ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
                // "links" => array(
                //     "target" => "laporan/Pembelian/",
                //     "title" => "laporan Pembelian",
                // ),
            ),
            "keterangan"     => array(
                "label" => "note",
            ),
            // --
            "m_harga"        => array(
                "label"      => "bruto",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_disc"         => array(
                "label"      => "diskon",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_ppn"          => array(
                "label"      => "PPN",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_hpp_nppn"     => array(
                "label"      => "Netto",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "log per transaksi $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "466",
            "data_id"     => "pertransaksi_" . randomNumber(2),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkpermatauang()
    {
        // arrPrintHijau(url_segment());
        // arrPrintHijau($_REQUEST);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // elseif (isset($_GET['suppliers_id'])){
        //     $condites = array(
        //         "suppliers_id" => $_GET['suppliers_id'],
        //     );
        //     $this->db->where($condites);
        // }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];

            $olahan[$transaksi_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan = array();
        foreach ($olahan as $tr_id => $itemParam) {
            // arrPrintWebs($itemParam);
            $customer_id = $itemParam['m_pihakID'];

            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai = $itemParam['m_hpp_nppn'];
            if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                $hasilOlahan[$customer_id]['sumBruto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
            //---------------------------------------------------------------------------------
            // $sub_transaksi_nilai_2 = $itemParam['m_harga_nett3'];
            // if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
            //     $hasilOlahan[$customer_id]['sumNetto'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
            // //---------------------------------------------------------------------------------
            // $sub_total_disc = $itemParam['m_total_disc'];
            // if (!isset($hasilOlahan[$customer_id]['sumTotalDisc'])) {
            //     $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
            //---------------------------------------------------------------------------------

        }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_pihakID'];

            $hasilOlahan_1[$tr_id] = $itemParam + $hasilOlahan[$customer_id];

            if (isset($_GET['suppliers_id']) && $customer_id == $_GET['suppliers_id']) {
                // $hasilOlahan_1 = array();
                $hasilOlahan_bysupplier[$tr_id] = $itemParam + $hasilOlahan[$customer_id];
            }
        }

        if (isset($_GET['suppliers_id'])) {
            // cekHijau(__LINE__);
            $masterData = $hasilOlahan_bysupplier;
        }
        else {
            $masterData = $hasilOlahan_1;
        }

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"   => "tanggal",
                "format"  => "formatField_he_format",
                "summary" => false,
            ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
                // "links" => array(
                //     "target" => "laporan/Pembelian/",
                //     "title" => "laporan Pembelian",
                // ),
            ),
            "keterangan"     => array(
                "label" => "note",
            ),
            // --
            "m_harga"        => array(
                "label"      => "bruto",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_disc"         => array(
                "label"      => "diskon",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_ppn"          => array(
                "label"      => "PPN",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_hpp_nppn"     => array(
                "label"      => "Netto",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan per transaksi $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "466",
            "data_id"     => "pertransaksi_" . randomNumber(2),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function outstanding()
    {
        $jenisTr = "466";
        $this->configPath = $configPath = "../../modules/pembelian/config/";
        cekHitam($this->configPath);
        $this->load->config($configPath . "coTransaksiUi");
        $this->configUi = $this->config->item("coTransaksiUi");
        $steps = $this->configUi[$jenisTr]['steps'];

        $this->load->helper("he_session_replacer");
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis_top='466r'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("transaksi_data.trash=0");
        $tr->addFilter("transaksi_data.valid_qty>0");

        $tmpHist = $tr->lookupUndoneEntries_joined(replaceSession())->result();
        showLast_query("hijau");
        cekHijau(sizeof($tmpHist));
        // arrPrintHijau($tmpHist);

        $tr->setFilters(array());
        $master = "transaksi";
        $slave = "transaksi_data";
        $jenies = array(
            "466r",
            // "466"
        );
        $condites = array(
            "link_id"            => 0,
            "div_id"             => 18,
            "$slave.valid_qty >" => 0,
        );
        $this->db->where($condites);
        // $this->db->where_in("jenis",  $jenies);
        $this->db->where_in("jenis_top", $jenies);
        $this->db->group_by("$master.id");
        // $src_order = $tr->lookupAll()->result();
        $this->db->join("$slave", "$slave.transaksi_id = $master.id");
        $src_order = $this->db->get("transaksi")->result();
        showLast_query("kuning");
        cekKuning(sizeof($src_order));
    }

    //     -----------------------
    public function cekbulanan()
    {
        // arrPrintHijau(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas_0 = url_segment(5);
        $aktivitas = isset($aktivitas_0) ? $aktivitas_0 : 1;
        $xid = $_GET['xid'];
        // $title_now = isset($aktivitas) ? "Sales Order" : "Packing List";
        // cekHere("$subjek_0");
        $title_now = isset($aktivitas) ? "Realisasi Diskon" : "-";
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

        $this->load->model("Mdls/MdlRawPembelian");
        $dt = new MdlRawPembelian();

        $tbl_1 = $dt->getTbl1();
        $tbl_2 = $dt->getTbl2();
        // $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
        $condites = array(
            // "fulldate>=" => $date1,
            // "fulldate<=" => $date2,
            "date($tbl_1.dtime)>=" => $date1,
            "date($tbl_1.dtime)<=" => $date2,
            // "produk_id" => "46768",
        );
        // $this->db->where($condites);
        if (my_cabang_id() != CB_ID_PUSAT) {
            $this->db->where("cabang_id", my_cabang_id());
        }
        $condite_cust = array(
            "produk_id >=" => "33",
            "produk_id <=" => "50",
        );
        // $this->db->where($condite_cust);
        switch ($subjek_0) {
            case "kategori":

                $src_00 = $dt->callSummaryKategoriProdukBulanan();

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
                    $thn = $items->thn;
                    $bln = $items->bln;
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
                    $produk_id = $items->merek_id;
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
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->merek_nama;

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
                $this->load->model("Mdls/MdlProduk");
                $pr = new MdlProduk();
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
                break;
            case "produk":
                $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
                // showLast_query("kuning");
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
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
                    $produk_id = $items->produk_id;
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
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->produk_nama;

                    /*
                     * sumbulanan
                     * */
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                // $subjekYgTerlibats = array();

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
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
                // arrPrintHijau($_GET);
                $this->load->model("Mdls/MdlProduk");
                $pr = new MdlProduk();
                // // if(isset($_GET['kategori_id']) ){
                //     $this->db->where("kategori_id", $_GET['kategori_id']);
                //     $subjekYgTerlibats = array();
                // // }
                $masterHeaders = array(
                    "id"   => array(
                        "label" => "cid",
                    ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                    ),
                    "kode" => array(
                        "label" => "SKU",
                        // "attr"       => "style='width=100px;'",
                    ),
                );
                break;
            case "supplier":
                $src_000 = $this->call_rek_saldo_awal($thn, $tambahn);

                // $condite_cust = array(
                //     "produk_id >=" => "33",
                //     "produk_id <=" => "50",
                // );
                // $this->db->where($condite_cust);
                // $src_00 = $dt->callSummaryCustomerBulanan($date1, $date2);
                $src_00 = $this->call_rek_pembantu_subpiutangsupplier_mutasi($thn, $tambahn);
                showLast_query("kuning");
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint($src_00);
                // arrPrint(array_slice($src_00, 0, 5));
                // matiHere(__LINE__);
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $subjek_nama = $items->extern2_nama;
                    $thn = $items->thn;
                    $bln = digit_2($items->bln);
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $ymd = $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_debet - $sum_kredit;
                    $qty_nett = $sum_qty_debet - $sum_qty_kredit;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    // $produk_id = $items->produk_id;
                    $produk_id = $items->extern_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    // $ymd = "$thn-$bln";
                    $saldo_awal = $src_000[$produk_id][$ymd]["saldo_awal"];
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett, "saldo_awal" => $saldo_awal);
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

                    /* ----------------
                     * sumbulanan
                     * ----------------*/
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                $subjek_0 = $subjek_nama;
                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
            // arrPrintKuning($subjekYgTerlibats);

                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
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
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlCustomer");
                $pr = new MdlCustomer();

                $masterHeaders = array(
                    // "id"   => array(
                    //     "label" => "cid",
                    // ),
                    "nama" => array(
                        "label" => "Diskon",
                        // "attr"       => "style='width=100px;'",
                    ),
                );
                break;
            case "seller":
                if (isset($aktivitas)) {
                    $src_00 = $dt->callSummarySellerSoBulanan($date1, $date2);
                }
                else {
                    $condite_cust = array(
                        "produk_id >=" => "33",
                        "produk_id <=" => "50",
                    );
                    // $this->db->where($condite_cust);

                    $src_00 = $dt->callSummarySellerBulanan($date1, $date2);
                }

                showLast_query("kuning");
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
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
                    // $produk_id = $items->produk_id;
                    $produk_id = $oleh_id = $items->oleh_id;
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

                    $subjekYgTerlibats[$oleh_id]["id"] = $oleh_id;
                    $subjekYgTerlibats[$oleh_id]["nama"] = $items->oleh_nama;

                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
                // cekBiru($src_harians);
                // cekHere(count($subjekYgTerlibats));
                // matiHere(__LINE__);
                // arrPrint(array_slice($subjekYgTerlibats,0,3));
                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs($src_harians);
                // matiHere(__LINE__);

                $this->load->model("Mdls/MdlEmployeeCabang");
                $pr = new MdlEmployeeCabang();
                $condite_subj = array(
                    "jenis" => "seller",
                );
                $this->db->where($condite_subj);
                $pr->setFilters(array());
                $subjek_0 = "salesman";
                $masterHeaders = array(
                    "id"   => array(
                        "label" => "pid",
                    ),
                    "nama" => array(
                        "label" => "salesman",
                        // "attr"       => "style='width=100px;'",
                    ),
                    // "stok_locker" => array(
                    //     "label"      => "Qty",
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "attr"       => "class='text-right'",
                    // ),
                    // "satuan"      => array(
                    //     "label" => "unit",
                    // ),
                );
                break;
            case "cabang":
                $condite_cust = array(
                    "produk_id >=" => "33",
                    "produk_id <=" => "50",
                );
                $this->db->where($condite_cust);
                if (isset($aktivitas)) {
                    $src_00 = $dt->callSummaryCabangSoBulanan($date1, $date2);
                }
                else {
                    $src_00 = $dt->callSummaryCabangBulanan($date1, $date2);
                }
                // showLast_query("kuning");
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
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
                    // $produk_id = $items->produk_id;
                    $produk_id = $oleh_id = $items->cabang_id;
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
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->cabang_nama;

                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
                // cekBiru($src_harians);
                // cekHere(count($subjekYgTerlibats));
                // matiHere(__LINE__);
                // arrPrint(array_slice($subjekYgTerlibats,0,3));
                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs($src_harians);
                // matiHere(__LINE__);

                $this->load->model("Mdls/MdlCabang");
                $pr = new MdlCabang();
                $condite_subj = array(
                    "id>" => "0",
                );
                $this->db->where($condite_subj);

                // $title_now =
                $masterHeaders = array(
                    "id"   => array(
                        "label" => "pid",
                    ),
                    "nama" => array(
                        "label" => "cabang",
                        // "attr"       => "style='width=100px;'",
                    ),
                    // "stok_locker" => array(
                    //     "label"      => "Qty",
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "attr"       => "class='text-right'",
                    // ),
                    // "satuan"      => array(
                    //     "label" => "unit",
                    // ),
                );
                break;
            case "tipe":
                $condite_cust = array(
                    "produk_id >=" => "33",
                    "produk_id <=" => "50",
                );
                $this->db->where($condite_cust);
                if (isset($aktivitas)) {
                    // $src_00 = $dt->callSummaryCabangSoBulanan($date1, $date2);
                }
                else {
                    $src_00 = $dt->callSummaryTipePejualanBulanan();
                }
                showLast_query("kuning");
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $src_margins = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
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
                    // $produk_id = $items->produk_id;
                    $produk_id = $oleh_id = $items->tipe_id;
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
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->tipe_nama;

                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
                // cekBiru($src_harians);
                // cekHere(count($subjekYgTerlibats));
                // matiHere(__LINE__);
                // arrPrintHijau($subjekYgTerlibats);
                // arrPrint(array_slice($subjekYgTerlibats,0,3));
                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs($src_harians);
                // matiHere(__LINE__);

                $this->load->model("Mdls/MdlCabang");
                $pr = new MdlCabang();
                $condite_subj = array(
                    "id>" => "0",
                );
                $this->db->where($condite_subj);

                // $subjek_0 = "tipe penjualan";
                $masterHeaders = array(
                    // "id"   => array(
                    //     "label" => "pid",
                    // ),
                    "nama" => array(
                        "label" => "tipe penjualan",
                        // "attr"       => "style='width=100px;'",
                    ),
                    // "stok_locker" => array(
                    //     "label"      => "Qty",
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "attr"       => "class='text-right'",
                    // ),
                    // "satuan"      => array(
                    //     "label" => "unit",
                    // ),
                );
                break;
        }

        // $condites_prod = array();
        // $this->db->where($condites_prod);
        if (ipadd() == "202.65.117.72") {
            if (isset($_GET['limit']) && $_GET['limit'] > 0) {
                $this->db->limit($_GET['limit']);
            }
        }

        $src_produks_0 = $pr->callSpecs();
        showLast_query("biru");
        foreach ($src_produks_0 as $p_id => $item) {

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
        }
        // cekHijau(count($src_produks));
        // arrPrintHijau($src_produks + $subjekYgTerlibats);
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
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "5822",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $src_produks + $subjekYgTerlibats,
            "master_data" => $subjekYgTerlibats,
            "child_data"  => $src_harians,
            "margin_data" => isset($src_margins) ? $src_margins : array(),
            "margin_avg"  => $margin_avg,
            "qty_data"    => isset($src_qty) ? $src_qty : array(),
            "total_bawah" => $total_bawah,

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
        $this->load->view("realisasi_diskon_periode", $data);
        // $this->load->view("penerimaan_periode", $data);
    }

    public function viewbulananper()
    {
        $this->load->helper("he_mass_table");
        arrPrintKuning(url_segment());
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

        $this->load->model("Mdls/MdlSupplier");
        $cu = new MdlSupplier();
        // $this->db->order_by("nama", "asc");
        $srcCus = $cu->callSpecs();
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
        $select_td = "Pilih Supplier <select data-style='btn btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker' onchange=\"location.href='$link_self&xid=' + this.value\">";
        $select_td .= "<option value=''>---pilih supplier----</option>";
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

            $title_now = "realisasi diskon";
        }
        else {
            $btn_disabled_so = "";
            $btn_disabled_pl = "disabled";

            // $title_now = "Packing list";
            $title_now = "Penjualan";
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
            "sum_satu"    => $url,
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanbulanan$subjek$aktivitas" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",

        );
        $this->load->view("realisasi_diskon_periode", $data);
    }

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

    public function call_rek_pembantu_subpiutangsupplier_mutasi($req_thn, $req_bln = "")
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

    public function call_rek_saldo_awal($req_thn, $req_bln = ""){
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
                if(isset($src_harians[$diskon_id][$keyTh]['saldo_awal'])){
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