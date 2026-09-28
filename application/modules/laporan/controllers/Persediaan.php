<?php

class Persediaan extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->default_limit = 200;
        $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("582spd", "982", "382spd");
    }

    public function produk()
    {
        /* ----------------------------------------------------------------------------------------------------------
         * untuk memasang master data dari persediaan harus update Coms/ComRekeningPembantuProduk
         * di dlmnya terjadi pairingan dengan data produk dan transaksi da ditampilkan dl satu array
         * ----------------------------------------------------------------------------------------------------------*/
        $this->load->config("heTransaksi_ui");
        $configUi = $this->config->item("heTransaksi_ui");
        $modules = array();
        foreach ($configUi as $mr_jenis => $itemUi) {
            $modul = $itemUi['modul'];

            $modules[$mr_jenis] = $modul;
        }

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = dtimeNow('Y-m') . '-01';
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');
        $jenisTr = $this->jenisTrs;

        $strDate = "";
        if (isset($_GET['date1'])) {
            // $condites = array(
            //     "date(dtime)>=" => $get_date1,
            //     "date(dtime)<=" => $get_date2,
            // );
            // $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            // $this->db->limit($this->default_limit);
        }


        $ps->setJenisTr($jenisTr);
        // $srcAwal = $ps->getLastMoves($get_date1);
        $srcDataStok = $ps->callGlobalStokMovemen($get_date1, $get_date2);

        // arrPrintKuning($srcDataStok);
        $dtStokAwal = $srcDataStok['awal'];
        $dtStokAkhir = $srcDataStok['akhir'];
        $dtStokmasuk = $srcDataStok['masuk'];
        $dtStokKeluar = $srcDataStok['keluar'];
        $dtStokKosong = $srcDataStok['kosong'];
        $dtStokPenjualan = $srcDataStok['penjualan'];
        $dtStokReturnPenjualan = $srcDataStok['return_penjualan'];
        $dtStokOpnamePlus = $srcDataStok['opname_plus'];
        $dtStokOpnameMinus = $srcDataStok['opname_minus'];
        $dtStokReturnPurchase = $srcDataStok['return_purchase'];

        // cekBiru(sizeof($dtStokAkhir));
        //         arrPrintPink($dtStokReturnPenjualan);
        //         arrPrintWebs($dtStokOpnameMinus);
        //         arrPrintWebs($dtStokAkhir);
        //mati_disini();
        $arr_prod_jenis_master = array();
        foreach ($dtStokAkhir as $prod_id_00 => $item_00) {
            $tr_id_00 = $item_00['transaksi_id'];

            $tbl_00 = "transaksi";
            $kolom_00 = array(
              "jenis_master"
            );
            $this->db->select($kolom_00);
            $condites = array(
                "id" => $tr_id_00,
            );
            $this->db->where($condites);
            // $this->db->order_by("extern_id,id", "asc");
            $sources_00 = $this->db->get($tbl_00)->row();
            // showLast_query("hijau");
// arrPrintPink($sources_00);
            $arr_prod_jenis_master[$prod_id_00]["jenis_master"] = $sources_00->jenis_master;
        }

        // cekMerah(sizeof($arr_prod_jenis_master));

        /* ----------------------------------------------------------------------------------
         * MdlProduk2 termasuk produk rakitan
         * kalau hanya menghendaki produk dari beli gunakan MdlProduk
         * ----------------------------------------------------------------------------------*/
        $this->load->model("Mdls/MdlProduk2");
        $pr = new MdlProduk2();
        // $this->db->limit(10);
        $srcProduks = $pr->lookupAll()->result();
        // showLast_query("merah");
        $jenisAliasings = arrCodeAliasing(my_cabang_id());

        /* ----------------------------------------------------------------------------------
         * data yg harus define persatu karena sourche data membawa key yg sama spt pada data utama
         * ---------------------------------------------------------------------------------*/
        foreach ($srcProduks as $srcProduk) {
            $produk_id = $srcProduk->id;

            $dtAwals = isset($dtStokAwal[$produk_id]) ? $dtStokAwal[$produk_id] : array();
            $jenisAwal = isset($dtAwals['jenis']) ? $dtAwals['jenis'] : "";
            $stokAwals['qty_awal'] = isset($dtAwals['qty_debet_akhir_global']) ? $dtAwals['qty_debet_akhir_global'] : 0;
            $stokAwals['dtime_awal'] = isset($dtAwals['dtime']) ? $dtAwals['dtime'] : "";
            $stokAwals['jenis_awal'] = isset($dtAwals['jenis']) ? (isset($jenisAliasings[$jenisAwal]) ? $jenisAliasings[$jenisAwal] : $jenisAwal) : "";
            $stokAwals['trid_awal'] = isset($dtAwals['transaksi_id']) ? $dtAwals['transaksi_id'] : "";
            $stokAwals['nomer_awal'] = isset($dtAwals['transaksi_no']) ? $dtAwals['transaksi_no'] : "";

            $dtMasuks = isset($dtStokmasuk[$produk_id]) ? $dtStokmasuk[$produk_id] : array();
            $dtKeluars = isset($dtStokKeluar[$produk_id]) ? $dtStokKeluar[$produk_id] : array();
            $dtPenjualans = isset($dtStokPenjualan[$produk_id]) ? $dtStokPenjualan[$produk_id] : array();
            $dtReturnPenjualans = isset($dtStokReturnPenjualan[$produk_id]) ? $dtStokReturnPenjualan[$produk_id] : array();
            $dtReturnPurchase = isset($dtStokReturnPurchase[$produk_id]) ? $dtStokReturnPurchase[$produk_id] : array();

            $dtAkhir = isset($dtStokAkhir[$produk_id]) ? $dtStokAkhir[$produk_id] : array();
            $stokAwals['qty_akhir'] = isset($dtAkhir['qty_debet_akhir_global']) ? $dtAkhir['qty_debet_akhir_global'] : 0;

            $dtKosong = isset($dtStokKosong[$produk_id]) ? $dtStokKosong[$produk_id] : array();
            $stokEnols['dtime_kosong'] = isset($dtKosong['dtime']) ? $dtKosong['dtime'] : "-";
            //------
            $dtOpnamePlus = isset($dtStokOpnamePlus[$produk_id]) ? $dtStokOpnamePlus[$produk_id] : array();
            $dtOpnameMinus = isset($dtStokOpnameMinus[$produk_id]) ? $dtStokOpnameMinus[$produk_id] : array();
            $opnamePlus['qty_opname_debet'] = isset($dtOpnamePlus['qty_opname_debet']) ? $dtOpnamePlus['qty_opname_debet'] : "-";
            $opnameMinus['qty_opname_kredit'] = isset($dtOpnameMinus['qty_opname_kredit']) ? $dtOpnameMinus['qty_opname_kredit'] : "-";
            //------
            $trJenies = isset($arr_prod_jenis_master[$produk_id]) ? $arr_prod_jenis_master[$produk_id] : array();
            // arrPrintPink();
            // arrPrintWebs($dtReturnPurchase);
            //            $masterData[$produk_id] = (array)$srcProduk + $stokAwals + $dtMasuks + $dtKeluars + $dtPenjualans + $stokEnols;
            $masterData[$produk_id] = (array)$srcProduk + $stokAwals + $dtMasuks + $dtKeluars + $dtPenjualans + $stokEnols + $opnamePlus + $opnameMinus + $dtReturnPenjualans + $dtReturnPurchase + $trJenies;


            //--------------------------------------
            $stok_awal = isset($stokAwals['qty_awal']) ? $stokAwals['qty_awal'] : 0;
            $stok_masuk = isset($dtMasuks['qty_debet_global']) ? $dtMasuks['qty_debet_global'] : 0;
            $stok_akhir = isset($stokAwals['qty_akhir']) ? $stokAwals['qty_akhir'] : 0;
            $penjualan = isset($dtPenjualans['qty_penjualan_global']) ? $dtPenjualans['qty_penjualan_global'] : 0;
            $return_penjualan = isset($dtReturnPenjualans['qty_return_penjualan_global']) ? $dtReturnPenjualans['qty_return_penjualan_global'] : 0;
            $return_purchase = isset($dtReturnPurchase['qty_return_purchase_global']) ? $dtReturnPurchase['qty_return_purchase_global'] : 0;
            $opname_plus = isset($opnamePlus['qty_opname_debet']) ? $opnamePlus['qty_opname_debet'] : 0;
            $opname_minus = isset($opnameMinus['qty_opname_kredit']) ? $opnameMinus['qty_opname_kredit'] : 0;

            $kiri = $stok_awal + $stok_masuk + $opname_plus - $opname_minus - $return_purchase;
            $kanan = $stok_awal + $penjualan + $stok_akhir - $return_penjualan;
            //--------------------------------------
            if ($kiri != $kanan) {
                // cekMerah("[$produk_id] :: $kiri :: $kanan ::");
            }

        }
        //arrPrintWebs($masterData);
        //         matiHere(__LINE__);
        // $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         *  -stok awal BRP
            -Stok akhir BRP?
            -Laku terakhir kapan?
            -Total laku selama 1 periode ?
            -Terakhir kosong kapan?
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "id"                          => array(
                "label" => "iD",
            ),
            "kode"                        => array(
                "label" => "kode",
            ),
            "nama"                        => array(
                "label" => "produk",
            ),
            "no_part"                     => array(
                "label" => "no part",
            ),
            "qty_awal"                    => array(
                "label"       => "stok Awal (konsolidasi)",
                "attr"        => "class='bg-warning text-right'",
                "attr_footer" => "class='bg-danger'",
            ),
            "jenis_awal"                  => array(
                "label"       => "transaksi terakhir",
                "attr"        => "class='bg-warning'",
                "attr_footer" => "class='bg-danger'",
            ),
            "nomer_awal"                  => array(
                "label"       => "nomer",
                "format"      => "formatField_he_format",
                "format_key"  => "nomer",
                "attr"        => "class='bg-warning'",
                "attr_footer" => "class='bg-danger'",
            ),
            "dtime_awal"                  => array(
                "label"       => "tanggal",
                "attr"        => "class='bg-warning'",
                "attr_footer" => "class='bg-danger'",
                "format"      => "formatField_he_format",
                "format_key"  => "fulldate_m",
            ),
            "qty_debet_global"            => array(
                "label"       => "stok masuk (kumulatif)",
                "attr"        => "class='text-right'",
                "attr_footer" => "class='bg-danger'",
            ),
            "qty_kredit_global"           => array(
                "label"       => "stok keluar (kumulatif)",
                "attr"        => "class='text-right'",
                "attr_footer" => "class='bg-danger'",
            ),
            "qty_akhir"                   => array(
                "label"       => "stok akhir (konsolidasi)",
                "attr"        => "class='text-right bg-info'",
                "attr_footer" => "class='bg-danger'",
                "links"       => array(
                    "target"         => "laporan/Persediaan/showStok",
                    "title_head_key" => "nama",
                    "title"          => "Posisi stok",
                    "key"            => "id",
                    "modal_size"     => "modal-xl",
                ),
            ),
            //------------
            "qty_opname_debet"            => array(
                "label"       => "opname (+)",
                "attr"        => "class='text-right bg-info'",
                "attr_footer" => "class='bg-danger'",
            ),
            "qty_opname_kredit"           => array(
                "label"       => "opname (-)",
                "attr"        => "class='text-right bg-info'",
                "attr_footer" => "class='bg-danger'",
            ),
            //------------
            "qty_penjualan_global"        => array(
                "label"       => "qty penjualan (kumulatif)",
                "attr"        => "class='bg-success text-right'",
                "attr_footer" => "class='bg-danger'",
            ),
            "penjualan_global"            => array(
                "label"       => "nilai penjualan (kumulatif)",
                "format"      => "formatField_he_format",
                "format_key"  => "harga",
                "attr"        => "class='bg-success'",
                "attr_footer" => "class='bg-danger'",
                "summary"     => true,
            ),
            //------------
            "jml_nota"                    => array(
                "label"       => "jml invoice",
                "format"      => "formatField_he_format",
                "format_key"  => "harga",
                "attr"        => "class='bg-success text-right'",
                "attr_footer" => "class='bg-danger text-right'",
                "summary"     => true,
            ),
            "dtime_spd"                   => array(
                "label"       => "pejualan terakhir",
                "format"      => "formatField_he_format",
                "format_key"  => "fulldate_m",
                "attr"        => "class='bg-success'",
                "attr_footer" => "class='bg-danger'",
            ),
            //------------
            "qty_return_penjualan_global" => array(
                "label"       => "qty return penjualan (kumulatif)",
                "attr"        => "class='bg-success text-right'",
                "attr_footer" => "class='bg-danger'",
            ),
            "return_penjualan_global"     => array(
                "label"       => "nilai return penjualan (kumulatif)",
                "format"      => "formatField_he_format",
                "format_key"  => "harga",
                "attr"        => "class='bg-success'",
                "attr_footer" => "class='bg-danger'",
                "summary"     => true,
            ),
            //------------
            "dtime_kosong"                => array(
                "label"      => "terakhir kosong",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate_m",
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
            "title"       => "KONSOLIDASI status persediaan produk $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenistr"     => $this->jenisTr,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "strGet"      => $strGet,
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            "modules"    => $modules,
            // "sum_satu" => base_url() . "laporan/Penjualan/produkperproduk" . "$strGet",
            // "sum_dua" => base_url() . "laporan/Penjualan/produkpertransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function showStok()
    {
        // arrPrintPink($_GET);
        $produk_id = $_GET['id'];
        $date1 = $_GET['date1'];
        $date2 = $_GET['date2'];

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m') . '-01';
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');
        $modal_size = isset($_GET['modalSize']) ? $_GET['modalSize'] : "";
        $jenisTr = $this->jenisTrs;

        $strDate = "";
        if (isset($_GET['date1'])) {
            // $condites = array(
            //     "date(dtime)>=" => $get_date1,
            //     "date(dtime)<=" => $get_date2,
            // );
            // $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            // $this->db->limit($this->default_limit);
        }


        $ps->setJenisTr($jenisTr);
        // $srcAwal = $ps->getLastMoves($get_date1);
        $condites = array(
            "extern_id" => $produk_id,
        );
        $this->db->where($condites);
        $srcDataStok = $ps->fetchLastMovemenPersediaan($date2);
        // showLast_query("Lime");
        // arrPrintKuning($srcDataStok);

        foreach ($srcDataStok as $cb_id => $itemDatas) {
            foreach ($itemDatas as $itemData) {
                $stokCabangs[$cb_id] = $itemData;
            }
        }
        // arrPrintWebs($stokCabangs);
        /* --------------------------------------------------------------------------------------------
         * produk speks
         * --------------------------------------------------------------------------------------------*/
        $this->load->model("Mdls/MdlProduk2");
        $pr = new MdlProduk2();

        $proSpeks = $pr->callSpecs($produk_id);
        // showLast_query("kuning");
        // arrPrintKuning($proSpeks);

        /* --------------------------------------------------------------------------------------------
         * cabang
         * --------------------------------------------------------------------------------------------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();

        $srcCabangs = $cb->lookupAll()->result();
        // showLast_query("biru");
        // arrPrintPink($srcCabangs);

        foreach ($srcCabangs as $srcCabang) {
            $cab_id = $srcCabang->id;

            $cabDatas[$cab_id] = $srcCabang;

            /* -----------------------------------------------------------------
             * header pada kolom cabang2 yg aktive
             * -----------------------------------------------------------------*/
            $kol_cab['cab_' . $cab_id]['label'] = $srcCabang->nama;
            $kol_cab['cab_' . $cab_id]['attr'] = "class='text-right bg-success'";
            $kol_cab['cab_' . $cab_id]['attr_footer'] = "class='text-right bg-danger'";
        }
        // arrPrintPink($cabDatas);

        /* ----------------------------------------------------------------------------------
         * pengabungan data
         * ----------------------------------------------------------------------------------*/
        foreach ($proSpeks as $prod_id => $prodData) {

            $prod_nama = $prodData->nama;
            $olahans['kode'] = $prodData->kode;
            $olahans['satuan'] = $prodData->satuan;
            $olahans['no_part'] = $prodData->no_part;
            $olahans['nama'] = $prod_nama;

            /* -----------------------------------------------------------------
             * data pada kolom cabang2 yg aktive
             * -----------------------------------------------------------------*/
            foreach ($cabDatas as $cabId => $cabData) {
                $cab_nama = $cabData->nama;
                $cab_stok = isset($stokCabangs[$cabId]['qty_debet_akhir']) ? $stokCabangs[$cabId]['qty_debet_akhir'] : 0;

                $olahans_2['cab_' . $cabId] = $cab_stok;
            }

            $masterData[] = $olahans + $olahans_2;
        }

        // $masterData = $hasilOlahan_1;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
                "kode"    => array(
                    "label"  => "kode",
                    "format" => "formatField_he_format",
                ),
                "nama"    => array(
                    "label"  => "produk",
                    "format" => "formatField_he_format",
                ),
                "satuan"  => array(
                    "label"  => "satuan",
                    "format" => "formatField_he_format",
                ),
                "no_part" => array(
                    "label"  => "no part",
                    "format" => "formatField_he_format",
                ),
                // "cab_-1" => array(
                //     "label"   => "qty",
                //     "format"  => "formatField_he_format",
                //     "summary" => true,
                //     "attr"    => "class='text-right'",
                // ),
                //
                // "sumPenjualan" => array(
                //     "label"   => "nilai penjualan",
                //     "format"  => "formatField_he_format",
                //     "attr"    => "class='text-right'",
                //     "summary" => true,
                // ),

            ) + $kol_cab;

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
            "title"       => "Lokasi persediaan",
            "subTitle"    => "all",
            "modal_size"  => $modal_size,
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $jenisTr,
            "data_id"     => "perproduk",
            "color_bar"   => "box-info",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        // $this->load->view("laporan", $data);
        $this->load->view("laporan", $data);
    }

    public function produkperproduk()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTr_penjualan;
        // $this->default_limit =2;
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
        $srcMasterData = $masterData = $src['data'];
        $masterDataJml = $src['data_jml'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // arrPrintPink($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
         *peparasi data harus 3 step
         * #1 pengumpulan data transaksi (main)
         * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            // $transaksi_id = $masterDatum['transaksi_id'];
            $extern_id = $masterDatum['extern_id'];

            $olahan_src[$extern_id] = $masterDatum;

            //---------------------------------------------------------------------------------
            $sum_jml = $masterDatum['qty_kredit'];
            if (!isset($olahan[$extern_id]['sumJml'])) {
                $olahan[$extern_id]['sumJml'] = 0;
            }
            $olahan[$extern_id]['sumJml'] += $sum_jml;
            //---------------------------------------------------------------------------------
            $sum_jual_nppn = $masterDatum['harga'];
            // if(!isset($olahan[$extern_id]['sumPenjualan'])){
            //     $olahan[$extern_id]['sumPenjualan'] = 0;
            // }
            $olahan[$extern_id]['harga'] = $sum_jual_nppn;
            //---------------------------------------------------------------------------------
            $sum_sub_jual_nppn = $masterDatum['i_sub_nett1'];
            if (!isset($olahan[$extern_id]['sumPenjualan'])) {
                $olahan[$extern_id]['sumPenjualan'] = 0;
            }
            $olahan[$extern_id]['sumPenjualan'] += $sum_sub_jual_nppn;

        }

        // arrPrintWebs($olahan);
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan = array();
        // foreach ($olahan as $tr_id => $itemParam) {
        //     // arrPrintWebs($itemParam);
        //     $customer_id = $itemParam['m_customerID'];
        //
        //     //---------------------------------------------------------------------------------
        //     $sub_transaksi_nilai = $itemParam['m_harga_nett1'];
        //     if(!isset($hasilOlahan[$customer_id]['sumBruto'])){
        //         $hasilOlahan[$customer_id]['sumBruto'] = 0;
        //     }
        //     $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
        //     //---------------------------------------------------------------------------------
        //     $sub_transaksi_nilai_2 = $itemParam['m_harga_nett3'];
        //     if(!isset($hasilOlahan[$customer_id]['sumNetto'])){
        //         $hasilOlahan[$customer_id]['sumNetto'] = 0;
        //     }
        //     $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
        //     //---------------------------------------------------------------------------------
        //     $sub_total_disc = $itemParam['m_total_disc'];
        //     if(!isset($hasilOlahan[$customer_id]['sumTotalDisc'])){
        //         $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
        //     }
        //     $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
        //     //---------------------------------------------------------------------------------
        //
        // }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $prod_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[$prod_id] = $itemParam + $olahan_src[$prod_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "kode"        => array(
                "label"  => "kode",
                "format" => "formatField_he_format",
            ),
            "extern_nama" => array(
                "label"  => "Produk",
                "format" => "formatField_he_format",
            ),
            "no_part"     => array(
                "label"  => "no part",
                "format" => "formatField_he_format",
            ),
            // "merek_nama"     => array(
            //     "label"  => "merek",
            //     "format" => "formatField_he_format",
            // ),
            // "harga" => array(
            //     "label"  => "hpp",
            //     "format" => "formatField_he_format",
            // ),
            // "disc"    => array(
            //     "label"   => "disc",
            //     "format"  => "formatField_he_format",
            //     "summary" => true,
            //     "attr"    => "class='text-right'",
            // ),
            // "nett1"      => array(
            //     "label"   => "harga nett",
            //     "format"  => "formatField_he_format",
            //     // "summary" => true,
            //     "attr"    => "class='text-right'",
            // ),

            "sumJml" => array(
                "label"   => "qty",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),

            "sumPenjualan" => array(
                "label"   => "nilai penjualan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
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
            "title"       => "Laporan penjualan by produk" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $jenisTr,
            "data_id"     => "perproduk",
            "color_bar"   => "box-info",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        // $this->load->view("laporan", $data);
        $this->load->view("laporan", $data);
    }

    public function produkpertransaksi()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
                // "transaksi_id" => '142585',
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $this->db->limit($this->default_limit);
        }
        $jenisTr = $this->jenisTrs;
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);

        $ps->setJenisTr($jenisTr);
        $src = $ps->callMovementProduk("persediaan_produk");
        $srcMasterData = $src['data'];
        $srcMasterDataJml = $src['data_jml'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // arrPrintPink($srcMasterData);
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
            $customer_id = $itemParam['m_customerID'];
            $jenis = $itemParam['jenis'];

            switch ($jenis) {
                case "982":
                    // arrPrintWebs($itemParam);
                    $sub_transaksi_nilai = $itemParam['m_nett1'];
                    // cekKuning($sub_transaksi_nilai . " $tr_id");
                    if (!isset($hasilOlahan[$tr_id]['sumReturn'])) {
                        $hasilOlahan[$tr_id]['sumReturn'] = 0;
                    }
                    $hasilOlahan[$tr_id]['sumReturn'] += $sub_transaksi_nilai;

                    $sub_transaksi_nilai_2 = $itemParam['m_nett2'] * -1;
                    if (!isset($hasilOlahan[$tr_id]['sumNetto'])) {
                        $hasilOlahan[$tr_id]['sumNetto'] = 0;
                    }
                    $hasilOlahan[$tr_id]['sumNetto'] += $sub_transaksi_nilai_2;
                    break;
                default:
                    //---------------------------------------------------------------------------------
                    $sub_transaksi_nilai = $itemParam['m_nett1'];
                    if (!isset($hasilOlahan[$tr_id]['sumBruto'])) {
                        $hasilOlahan[$tr_id]['sumBruto'] = 0;
                    }
                    $hasilOlahan[$tr_id]['sumBruto'] += $sub_transaksi_nilai;
                    //---------------------------------------------------------------------------------
                    $sub_transaksi_nilai_2 = isset($itemParam['m_nett2']) ? $itemParam['m_nett2'] : 0;
                    if (!isset($hasilOlahan[$tr_id]['sumNetto'])) {
                        $hasilOlahan[$tr_id]['sumNetto'] = 0;
                    }
                    $hasilOlahan[$tr_id]['sumNetto'] += $sub_transaksi_nilai_2;
                    //---------------------------------------------------------------------------------
                    $sub_total_disc = $itemParam['m_disc'];
                    if (!isset($hasilOlahan[$tr_id]['sumTotalDisc'])) {
                        $hasilOlahan[$tr_id]['sumTotalDisc'] = 0;
                    }
                    $hasilOlahan[$tr_id]['sumTotalDisc'] += $sub_total_disc;
                    //---------------------------------------------------------------------------------
                    break;
            }
        }
        // cekBiru($hasilOlahan);
        // matiHere();
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[$tr_id] = $itemParam + $hasilOlahan[$tr_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "m_customerName" => array(
                "label"  => "Customer",
                "format" => "formatField_he_format",
            ),
            "sumBruto"       => array(
                "label"   => "Penjualan bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumReturn"      => array(
                "label"   => "return bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumTotalDisc"   => array(
                "label"   => "diskon",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            // "sumCancel"    => array(
            //     "label"   => "pre SO cancel",
            //     "format"  => "formatField_he_format",
            //     "summary" => true,
            //     "attr"    => "class='text-right'",
            // ),
            "sumNetto"       => array(
                "label"   => "penjualan netto",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
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
            "title"       => "Laporan per Transaksi" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $this->jenisTr,
            "data_id"     => "pertransaksi",
            "color_bar"   => "box-success",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
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