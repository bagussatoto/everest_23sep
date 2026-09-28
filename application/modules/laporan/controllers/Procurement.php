<?php

class Procurement extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "pembelian/";
        $this->jenisTr = "4666";
        $this->default_limit = 100;
    }

    public function requestproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

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
        $src = $ps->callMovementProduk("persediaan_produk");
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal approve",
                "format" => "formatField_he_format",
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
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            "m_shippingDate" => array(
                "label" => "tgl request",
            ),
            // "m_shippingDateOn" => array(
            //     "label" => "tgl grn",
            // ),
            // --
            "i_hpp"          => array(
                "label"      => "hpp",
                "format"     => "formatField_he_format",
                "format_key" => "hpp",
                "attr"       => "class='text-right'",
            ),
            "i_jml"          => array(
                "label"  => "jumlah request",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            // "i_jml_grn"      => array(
            //     "label"  => "jumlah grn",
            //     "format" => "formatField_he_format",
            // ),
            "i_sub_hpp"      => array(
                "label"  => "nilai",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
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
            "title"       => "laporan pembelian $strDate",
            "subTitle"    => "Raw Data request pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function grnproduk()
    {
        $this->jenisTr = "4667";
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
        $src = $ps->callMovementProduk("persediaan_produk");
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
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
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            // --
            "harga"          => array(
                "label"  => "hpp",
                "format" => "formatField_he_format",
            ),
            "qty_debet"      => array(
                "label"  => "jumlah",
                "format" => "formatField_he_format",
            ),
            "debet"          => array(
                "label"  => "nilai",
                "format" => "formatField_he_format",
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
            "title"       => "laporan pembelian $strDate",
            "subTitle"    => "raw data GRN",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = $this->default_limit;
            $maxLimit = 20;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = array(
            "4666r",
            "4666",
            "4667",
            "967r",
            "967"
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callMovementProduk("persediaan_produk");
        $reqData = $src['data'];
        showLast_query("kuning");
        // cekBiru(sizeof($reqData[0]));
        // arrPrintKuning($reqData);

        foreach ($reqData as $reqDatum) {
            $d_id = $reqDatum['id'];
            $req_id = $reqDatum['transaksi_id'];
            $prod_id = $reqDatum['extern_id'];
            $master_id = $reqDatum['master_id'];
            $rekening = $reqDatum['rekening'];
            $debet = $reqDatum['debet'];
            $kredit = $reqDatum['kredit'];
            $qty_debet = $reqDatum['qty_debet'];
            $qty_kredit = $reqDatum['qty_kredit'];

            $kolomTambahan['debet_' . $rekening] = $debet;
            $kolomTambahan['kredit_' . $rekening] = $kredit;
            $kolomTambahan['debet_' . $rekening] = $debet;
            $kolomTambahan['debet_' . $rekening] = $debet;

            // $req_datas[$master_id][$req_id][$prod_id] = $reqDatum + $kolomTambahan;
            // $req_datas[$master_id][$d_id][$prod_id] = $reqDatum + $kolomTambahan;
            $req_datas[$master_id][$d_id][$prod_id] = $reqDatum;
        }

        // arrPrintPink($req_datas);
        // matiHere();

        $arr = array();
        foreach ($req_datas as $mst_id => $req_data_1) {
            // cekBiru($mst_id);
            foreach ($req_data_1 as $reke => $req_data_2) {
                // cekKuning($reke);
                foreach ($req_data_2 as $pro_id => $item) {

                    $q_mainDatas[$mst_id][$pro_id] = $item;
                    $q_debet = $item['qty_debet_lap'];
                    $q_kredit = $item['qty_kredit_lap'];
                    $q_rek = $item['rekening'];
                    // $q_transaksi_tipe = $item['transaksi_tipe'];

                    if (!isset($arr[$mst_id][$pro_id]['debet_' . $q_rek])) {
                        $arr[$mst_id][$pro_id]['debet_' . $q_rek] = 0;
                    }
                    $arr[$mst_id][$pro_id]['debet_' . $q_rek] += $q_debet;

                    if (!isset($arr[$mst_id][$pro_id]['kredit_' . $q_rek])) {
                        $arr[$mst_id][$pro_id]['kredit_' . $q_rek] = 0;
                    }
                    $arr[$mst_id][$pro_id]['kredit_' . $q_rek] += $q_kredit;

                    // cekMerah("$q_rek : $pro_id - $q_debet - $q_kredit");
                }
            }

        }
        $grnData = $arr;
        // arrPrintWebs($arr);

        /* -----------------------------------------------------------------------------------------------------------
         * data penerimaan
         * -----------------------------------------------------------------------------------------------------------*/
        // $this->jenisTr = "4667";
        // // $this->load->helper("he_mass_table");
        // // $this->load->model("Coms/ComRekeningPembantuProduk");
        // // $ps = new ComRekeningPembantuProduk();
        //
        // $strDate = "";
        // if (isset($_GET['date1'])) {
        //     $condites = array(
        //         "date(dtime)>=" => $get_date1,
        //         "date(dtime)<=" => $get_date2,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - ".formatField_he_format("fulldate", $get_date2);
        // }
        // else {
        //     $maxLimit = $this->default_limit;
        //     $this->db->limit($maxLimit);
        //
        //     $strDate = "$maxLimit data terakhir";
        // }
        //
        // $sortings = array(
        //     "kolom" => "id",
        //     "mode" => "desc",
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($this->jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        // $grnData = $src['data'];
        // cekBiru(sizeof($grnData[0]));
        // // arrPrintPink($grnData);

        //
        foreach ($grnData as $mst_id => $item_0) {
            // $ref_id = $item['referensi_id'];
            // $prod_id = $item['extern_id'];
            // $grn_id = $item['transaksi_id'];
            foreach ($item_0 as $prd_id => $item) {

                $req_data = isset($q_mainDatas[$mst_id][$prd_id]) ? $q_mainDatas[$mst_id][$prd_id] : array();
                $gabunganData[] = $req_data + $item;
            }
        }
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        $masterData = $gabunganData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal approve",
                "format" => "formatField_he_format",
            ),
            "kode"           => array(
                "label" => "kode",
            ),
            "nama"           => array(
                "label" => "produk",
            ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            "m_shippingDate" => array(
                "label" => "tgl request",
            ),
            "dtime_terima"   => array(
                "label" => "tgl grn",
            ),

            // --
            // "i_hpp"          => array(
            //     "label"      => "hpp",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "hpp",
            //     "attr"       => "class='text-right'",
            // ),
            // "i_jml"          => array(
            //     "label"  => "jumlah request",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            // "i_jml_grn"      => array(
            //     "label"  => "jumlah grn",
            //     "format" => "formatField_he_format",
            // ),
            // "i_sub_hpp"      => array(
            //     "label"  => "nilai",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            ///-----------------
            ///

            "debet_4666r" => array(
                "label"  => "request",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            // "reguler_kredit_4666r"      => array(
            //     "label"  => "446r_",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            "kredit_4666" => array(
                "label"  => "menunggu approve",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "debet_4666"  => array(
                "label"  => "approve",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "kredit_4667" => array(
                "label"  => "outstanding",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "debet_4667"  => array(
                "label"  => "sudah grn",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "rejected"    => array(
                "label"  => "cancel",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "closed"      => array(
                "label"  => "fullfill",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "debet_967r"  => array(
                "label"  => "request return",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "debet_967"   => array(
                "label"  => "approve return",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
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
            "title"       => "laporan pembelian $strDate",
            "subTitle"    => "Raw Data procurement",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstanding()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        // $reqData_000 = $src_000['produk'];
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "Tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "kategori_nama"     => array(
                "label" => "kategori",
            ),
            "kode"              => array(
                "label" => "kode",
            ),
            "extern_nama"       => array(
                "label" => "produk",
            ),
            "oleh_nama"         => array(
                "label" => "PO",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "supplier_nama"     => array(
                "label" => "vendor",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"     => array(
                "label" => "satuan",
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "spo_debet_lap"     => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_debet_lap"     => array(
                "label"      => "sudah diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "nilai diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_kredit_lap"    => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "nilai outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
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
            "title"       => "outstanding order pembelian",
            "subTitle"    => "Raw Data outstanding order pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Procurement/produkoutstandingtransaksi" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Procurement/produkoutstandingproduk" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Procurement/produkoutstandingsales" . "$strGet",
            "sum_empat"   => base_url() . "laporan/Procurement/produkoutstandingsupplier" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Procurement/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingraw()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // cekBiru($masterData_ori);
        if (isset($_GET['ky'])) {
            $req_key = $_GET['ky'];
            switch ($req_key) {
                case "cabang_id":
                case "extern_id":
                case "master_id":
                case "customer_id":
                case "seller_id":
                    $hasilOlahan_bysupplier = array();
                    foreach ($reqData_000 as $item) {
                        if (isset($_GET[$req_key]) && $item[$req_key] == $_GET[$req_key]) {
                            $hasilOlahan_bysupplier[] = $item;
                        }
                    }
                    $masterData = $hasilOlahan_bysupplier;
                    break;
            }

        }
        else {
            $masterData = $reqData_000;
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
            "fulldate"          => array(
                "label" => "Tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "kategori_nama"     => array(
                "label" => "kategori",
            ),
            "kode"              => array(
                "label" => "kode",
            ),
            "extern_nama"       => array(
                "label" => "produk",
            ),
            "seller_nama"       => array(
                "label" => "salesman",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "customer_nama"     => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"     => array(
                "label" => "satuan",
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "spo_debet_lap"     => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_debet_lap"     => array(
                "label"      => "sudah diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "nilai diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_kredit_lap"    => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "nilai outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
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
            "title"       => "detil raw data ",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
            "sum_satu"    => base_url() . "laporan/Procurement/produkoutstandingproduk" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Procurement/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingtransaksi()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['master_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "seller_nama"       => array(
                "label" => "oleh",
            ),
            "customer_nama"     => array(
                "label" => "customer",
            ),

            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Procurement/produkoutstandingraw",
                    "title"   => "Transaksi per vendor",
                    "key"     => "master_id",
                    "heading" => "_step_1_nomer",
                ),
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
            "title"       => "laporan order outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingmaster_" . dtimeToSecond(dtimeNow()),
            "loader_div"  => "sum_satu",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");

        /* ---------------------------------------------------------------------
         * filter khusus
         * ---------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            $this->db->where($get_condites);
        }

        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['extern_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "kode"   => array(
                "label" => "kode",
            ),
            "nama"   => array(
                "label" => "produk",
            ),
            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            "satuan" => array(
                "label" => "satuan",
            ),

            "sum_spo_qty_debet_lap" => array(
                "label"   => "qty order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spo_debet_lap"     => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"   => "qty dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"         => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"        => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Procurement/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "extern_id",
                    "heading" => "extern_nama",
                ),
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
            "title"       => "laporan produk outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-warning",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingproduk_" . dtimeToSecond(dtimeNow()),
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingsupplier()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['supplier_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "supplier_id"   => array(
                "label" => "cuID",
            ),
            "supplier_nama" => array(
                "label" => "vendor",
            ),
            // "seller_nama"   => array(
            //     "label" => "salesman",
            // ),

            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Procurement/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "customer_id",
                    "heading" => "customer_nama",
                ),
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
            "title"       => "laporan vendor outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingcustomer",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }


    public function produkordernetto()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        // $reqData_000 = $src_000['produk'];
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "Tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "kategori_nama"     => array(
                "label" => "kategori",
            ),
            "kode"              => array(
                "label" => "kode",
            ),
            "extern_nama"       => array(
                "label" => "produk",
            ),
            "oleh_nama"         => array(
                "label" => "PO",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "supplier_nama"     => array(
                "label" => "vendor",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"     => array(
                "label" => "satuan",
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "spo_debet_lap"     => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_debet_lap"     => array(
                "label"      => "sudah diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "nilai diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_kredit_lap"    => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "nilai outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
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
            "title"       => "NETTO order pembelian",
            "subTitle"    => "Raw Data NETTO order pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Procurement/produkordernettotransaksi" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Procurement/produkordernettoproduk" . "$strGet",
            "sum_empat"   => base_url() . "laporan/Procurement/produkordernettosupplier" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkordernettoraw()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // cekBiru($masterData_ori);
        if (isset($_GET['ky'])) {
            $req_key = $_GET['ky'];
            switch ($req_key) {
                case "cabang_id":
                case "extern_id":
                case "master_id":
                case "customer_id":
                case "seller_id":
                    $hasilOlahan_bysupplier = array();
                    foreach ($reqData_000 as $item) {
                        if (isset($_GET[$req_key]) && $item[$req_key] == $_GET[$req_key]) {
                            $hasilOlahan_bysupplier[] = $item;
                        }
                    }
                    $masterData = $hasilOlahan_bysupplier;
                    break;
            }

        }
        else {
            $masterData = $reqData_000;
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
            "fulldate"          => array(
                "label" => "Tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "kategori_nama"     => array(
                "label" => "kategori",
            ),
            "kode"              => array(
                "label" => "kode",
            ),
            "extern_nama"       => array(
                "label" => "produk",
            ),
            "seller_nama"       => array(
                "label" => "salesman",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "customer_nama"     => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"     => array(
                "label" => "satuan",
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "spo_debet_lap"     => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_debet_lap"     => array(
                "label"      => "sudah diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "nilai diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_kredit_lap"    => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "nilai outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
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
            "title"       => "detil raw data ",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
            "sum_satu"    => base_url() . "laporan/Procurement/produkoutstandingproduk" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Procurement/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkordernettotransaksi()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['master_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "seller_nama"       => array(
                "label" => "oleh",
            ),
            "customer_nama"     => array(
                "label" => "customer",
            ),

            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Procurement/produkoutstandingraw",
                    "title"   => "Transaksi per vendor",
                    "key"     => "master_id",
                    "heading" => "_step_1_nomer",
                ),
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
            "title"       => "laporan order netto",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingmaster_" . dtimeToSecond(dtimeNow()),
            "loader_div"  => "sum_satu",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkordernettoproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");

        /* ---------------------------------------------------------------------
         * filter khusus
         * ---------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            $this->db->where($get_condites);
        }

        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['extern_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "kode"   => array(
                "label" => "kode",
            ),
            "nama"   => array(
                "label" => "produk",
            ),
            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            "satuan" => array(
                "label" => "satuan",
            ),

            "sum_spo_qty_debet_lap" => array(
                "label"   => "qty order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spo_debet_lap"     => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"   => "qty dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"         => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"        => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Procurement/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "extern_id",
                    "heading" => "extern_nama",
                ),
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
            "title"       => "laporan produk netto",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-warning",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingproduk_" . dtimeToSecond(dtimeNow()),
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkordernettosupplier()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
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
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstandingPembelian("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['supplier_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "supplier_id"   => array(
                "label" => "cuID",
            ),
            "supplier_nama" => array(
                "label" => "vendor",
            ),
            // "seller_nama"   => array(
            //     "label" => "salesman",
            // ),

            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Procurement/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "customer_id",
                    "heading" => "customer_nama",
                ),
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
            "title"       => "laporan vendor netto",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingcustomer",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    // --------------------------------------------------
    public function cekoutstandingvendor()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            "188" => array("qty" => "1", "nilai" => "1",),
            "705" => array("qty" => "2500", "nilai" => "790000",),
            "167" => array("qty" => "200", "nilai" => "790000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataProcurement");
        $dt = new DataProcurement();

        $src_00 = $dt->callProduk($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // cekBiru(sizeof($src_mtd));
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['supplier_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['supplier_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_466);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466)");
            $qty_return_kirim = ($qty_debet_rejected_467 + $qty_debet_closed_467);
            $qty_pembatalan_kirim = ($qty_debet_batal_9911);
            $qty_order = ($qty_debet_reguler_466 + $qty_debet_reguler_460a) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("$qty_order = ($qty_debet_reguler_466 + $qty_debet_reguler_460a) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            // $qty_kirim = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_return_kirim;
            $qty_kirim = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;

            $return_order = ($debet_rejected_466 + $debet_closed_466 + $kredit_rejected_466);
            $return_kirim = ($debet_rejected_467 + $debet_closed_467);
            $pembatalan_kirim = ($debet_batal_9911);
            $order = ($debet_reguler_466 + $debet_reguler_460a) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_467 + $debet_reguler_460) - $return_kirim;
            $kirim = ($debet_reguler_467 + $debet_reguler_460) - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_466 + $prev_qty_debet_closed_466 + $prev_qty_kredit_closed_466 + $prev_qty_kredit_rejected_466);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_466 + $prev_qty_debet_closed_466 + $prev_qty_kredit_closed_466);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_467 + $prev_qty_debet_closed_467);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9911);
            $prev_qty_order = ($prev_qty_debet_reguler_466 + $prev_qty_debet_reguler_460a) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_466 + $prev_qty_debet_reguler_460a) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_467 + $prev_qty_debet_reguler_460) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_467 + $prev_qty_debet_reguler_460) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim $prev_qty_kirim = ($prev_qty_debet_reguler_467 + $prev_qty_debet_reguler_460) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_466 + $prev_debet_closed_466 + $prev_kredit_closed_466 + $prev_kredit_rejected_466);
            $prev_return_kirim = ($prev_debet_rejected_467 + $prev_debet_closed_467);
            $prev_pembatalan_kirim = ($prev_debet_batal_9911);
            $prev_order = ($prev_debet_reguler_466 + $prev_debet_reguler_460a) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_467 + $prev_debet_reguler_460) - $prev_pembatalan_kirim;
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_466 + $prev_debet_460a) - $prev_debet_982) - ($prev_debet_467 + $prev_debet_460);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9911");

            $qty_return_order_mtd = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_466");
            $qty_return_kirim_mtd = ($qty_debet_rejected_467 + $qty_debet_closed_467);
            $qty_batal_kirim_mtd = $qty_debet_batal_9911;
            $qty_order_mtd = ($qty_debet_reguler_466 + $qty_debet_reguler_460r) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_466_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_466']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466 || $qty_kredit_rejected_466_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_466 + $qty_debet_reguler_460r) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_466 + $debet_closed_466 + $kredit_rejected_466);
            $return_kirim_mtd = ($debet_rejected_467 + $debet_closed_467);
            $batal_kirim_mtd = $debet_batal_9911;
            $order_mtd = ($debet_reguler_466 + $debet_reguler_460r) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_467 + $debet_reguler_460) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_467 + $debet_reguler_460) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_466 + $mtd_460r - $mtd_982) - ($mtd_467 + $mtd_460);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            // $sub_outstanding["ytd_outstanding"] = $outstandingSubjek[$subj_id]["prev_outstanding"] + $outstandingSubjek[$subj_id]["outstanding"];
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "supplier_id"          => array(
                "label" => "vid",
            ),
            "supplier_nama"        => array(
                "label" => "vendor",
            ),
            /*prevous outstanding*/
            "prev_qty_outstanding" => array(
                "label"      => "previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_outstanding"     => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // // "debet_reguler_582so"          => array(
            // //     "label"      => "new order value",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // //     "attr"       => "class='text-right'",
            // //     "summary"    => true,
            // // ),
            // "qty_debet_reguler_382so"      => array(
            //     "label"      => "new order export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_382so"          => array(
            //     "label"      => "new order export  value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582so"      => array(
            //     "label"      => "new order reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"      => array(
            //     "label"      => "new kirim reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order new netto*/
            "qty_order_netto"      => array(
                "label"      => "new netto order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "order_netto"          => array(
                "label"      => "new netto order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*RETURNAN*/
            // "qty_debet_982"        => array(
            //     "label"      => "new return qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_982"            => array(
            //     "label"      => "new return value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "grn qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "grn local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "grn export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "grn export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "grn balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "grn export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "grn cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            "qty_kirim_netto"      => array(
                "label"      => "new netto grn qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_netto"          => array(
                "label"      => "new netto grn value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
            "qty_outstanding"      => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "outstanding"          => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "netto",
                "attr"       => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    // "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            /*last outstanding*/
            "ytd_qty_outstanding"  => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_outstanding"      => array(
                "label"      => "total outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );

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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan Vendor Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function cekoutstandingraw()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            "188" => array("qty" => "1", "nilai" => "1",),
            "705" => array("qty" => "2500", "nilai" => "790000",),
            "167" => array("qty" => "200", "nilai" => "790000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataProcurement");
        $dt = new DataProcurement();

        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        arrPrint($_GET);
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            if(method_exists($dt, "setSupplierId")){

                $dt->setSupplierId('22');
            }
        }

        $src_00 = $dt->callProduk($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_ytd = $src_00['ytd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // cekBiru(sizeof($src_mtd));
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['supplier_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['supplier_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_466);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466)");
            $qty_return_kirim = ($qty_debet_rejected_467 + $qty_debet_closed_467);
            $qty_pembatalan_kirim = ($qty_debet_batal_9911);
            $qty_order = ($qty_debet_reguler_466 + $qty_debet_reguler_460a) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("$qty_order = ($qty_debet_reguler_466 + $qty_debet_reguler_460a) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            // $qty_kirim = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_return_kirim;
            $qty_kirim = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;

            $return_order = ($debet_rejected_466 + $debet_closed_466 + $kredit_rejected_466);
            $return_kirim = ($debet_rejected_467 + $debet_closed_467);
            $pembatalan_kirim = ($debet_batal_9911);
            $order = ($debet_reguler_466 + $debet_reguler_460a) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_467 + $debet_reguler_460) - $return_kirim;
            $kirim = ($debet_reguler_467 + $debet_reguler_460) - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_466 + $prev_qty_debet_closed_466 + $prev_qty_kredit_closed_466 + $prev_qty_kredit_rejected_466);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_466 + $prev_qty_debet_closed_466 + $prev_qty_kredit_closed_466);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_467 + $prev_qty_debet_closed_467);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9911);
            $prev_qty_order = ($prev_qty_debet_reguler_466 + $prev_qty_debet_reguler_460a) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_466 + $prev_qty_debet_reguler_460a) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_467 + $prev_qty_debet_reguler_460) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_467 + $prev_qty_debet_reguler_460) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim $prev_qty_kirim = ($prev_qty_debet_reguler_467 + $prev_qty_debet_reguler_460) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_466 + $prev_debet_closed_466 + $prev_kredit_closed_466 + $prev_kredit_rejected_466);
            $prev_return_kirim = ($prev_debet_rejected_467 + $prev_debet_closed_467);
            $prev_pembatalan_kirim = ($prev_debet_batal_9911);
            $prev_order = ($prev_debet_reguler_466 + $prev_debet_reguler_460a) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_467 + $prev_debet_reguler_460) - $prev_pembatalan_kirim;
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_466 + $prev_debet_460a) - $prev_debet_982) - ($prev_debet_467 + $prev_debet_460);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9911");

            $qty_return_order_mtd = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_466");
            $qty_return_kirim_mtd = ($qty_debet_rejected_467 + $qty_debet_closed_467);
            $qty_batal_kirim_mtd = $qty_debet_batal_9911;
            $qty_order_mtd = ($qty_debet_reguler_466 + $qty_debet_reguler_460r) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_467 + $qty_debet_reguler_460) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_466_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_466']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_466 + $qty_debet_closed_466 + $qty_kredit_rejected_466 || $qty_kredit_rejected_466_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_466 + $qty_debet_reguler_460r) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_466 + $debet_closed_466 + $kredit_rejected_466);
            $return_kirim_mtd = ($debet_rejected_467 + $debet_closed_467);
            $batal_kirim_mtd = $debet_batal_9911;
            $order_mtd = ($debet_reguler_466 + $debet_reguler_460r) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_467 + $debet_reguler_460) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_467 + $debet_reguler_460) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_466 + $mtd_460r - $mtd_982) - ($mtd_467 + $mtd_460);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            // $sub_outstanding["ytd_outstanding"] = $outstandingSubjek[$subj_id]["prev_outstanding"] + $outstandingSubjek[$subj_id]["outstanding"];
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $src_ytd;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "master_id"          => array(
                "label" => "mid",
            ),
            "supplier_nama"      => array(
                "label" => "vendor",
            ),
            "_step_1_nomer"      => array(
                "label" => "nomer PO",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "extern_id"      => array(
                "label" => "pid",
            ),
            "extern_nama"      => array(
                "label" => "produk",
            ),
            /*prevous outstanding*/
            "qty_debet_reguler"  => array(
                "label"      => "debet qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "summary"    => true,
            ),
            "debet_reguler"      => array(
                "label"      => "debet value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "summary"    => true,
            ),
            "qty_debet_rejected"  => array(
                "label"      => "debet reject qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "debet_rejected"      => array(
                "label"      => "debet reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "qty_kredit_reguler" => array(
                "label"      => "kredit qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "summary"    => true,
            ),
            "kredit_reguler"     => array(
                "label"      => "kredit value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "summary"    => true,
            ),
            "qty_kredit_rejected" => array(
                "label"      => "kredit reject qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "kredit_rejected"     => array(
                "label"      => "kredit reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
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
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan Vendor Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function cekoutstandingvendorsupplies()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            "611" => array("qty" => "25000", "nilai" => "2190000",),
            "628" => array("qty" => "464", "nilai" => "44210384",),
            "92"  => array("qty" => "3200", "nilai" => "72140000",),
            "717" => array("qty" => "200", "nilai" => "11075000",),
            "661" => array("qty" => "2", "nilai" => "35000000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataProcurement");
        $dt = new DataProcurement();

        $src_00 = $dt->callSupplies($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // cekBiru(sizeof($src_mtd));
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['supplier_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['supplier_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_461r);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_461r + $qty_debet_closed_461r + $qty_kredit_rejected_461r);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_461r + $qty_debet_closed_461r + $qty_kredit_rejected_461r)");
            $qty_return_kirim = ($qty_debet_rejected_461 + $qty_debet_closed_461);
            $qty_pembatalan_kirim = ($qty_debet_batal_9911);
            $qty_order = ($qty_debet_reguler_461r + $qty_debet_reguler_460a) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("$qty_order = ($qty_debet_reguler_461r + $qty_debet_reguler_460a) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            // $qty_kirim = ($qty_debet_reguler_461 + $qty_debet_reguler_460) - $qty_return_kirim;
            $qty_kirim = ($qty_debet_reguler_461 + $qty_debet_reguler_460) - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_461 + $qty_debet_reguler_460) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;

            $return_order = ($debet_rejected_461r + $debet_closed_461r + $kredit_rejected_461r);
            $return_kirim = ($debet_rejected_461 + $debet_closed_461);
            $pembatalan_kirim = ($debet_batal_9911);
            $order = ($debet_reguler_461r + $debet_reguler_460a) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_461 + $debet_reguler_460) - $return_kirim;
            $kirim = ($debet_reguler_461 + $debet_reguler_460) - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_461r + $prev_qty_debet_closed_461r + $prev_qty_kredit_closed_461r + $prev_qty_kredit_rejected_461r);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_461r + $prev_qty_debet_closed_461r + $prev_qty_kredit_closed_461r);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_461 + $prev_qty_debet_closed_461);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9911);
            $prev_qty_order = ($prev_qty_debet_reguler_461r + $prev_qty_debet_reguler_460a) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_461r + $prev_qty_debet_reguler_460a) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_461 + $prev_qty_debet_reguler_460) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_461 + $prev_qty_debet_reguler_460) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim $prev_qty_kirim = ($prev_qty_debet_reguler_461 + $prev_qty_debet_reguler_460) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_461r + $prev_debet_closed_461r + $prev_kredit_closed_461r + $prev_kredit_rejected_461r);
            $prev_return_kirim = ($prev_debet_rejected_461 + $prev_debet_closed_461);
            $prev_pembatalan_kirim = ($prev_debet_batal_9911);
            $prev_order = ($prev_debet_reguler_461r + $prev_debet_reguler_460a) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_461 + $prev_debet_reguler_460) - $prev_pembatalan_kirim;
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_461r + $prev_debet_460a) - $prev_debet_982) - ($prev_debet_461 + $prev_debet_460);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9911");

            $qty_return_order_mtd = ($qty_debet_rejected_461r + $qty_debet_closed_461r + $qty_kredit_rejected_461r);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_461r");
            $qty_return_kirim_mtd = ($qty_debet_rejected_461 + $qty_debet_closed_461);
            $qty_batal_kirim_mtd = $qty_debet_batal_9911;
            $qty_order_mtd = ($qty_debet_reguler_461r + $qty_debet_reguler_460r) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_461 + $qty_debet_reguler_460) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_461 + $qty_debet_reguler_460) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_461r_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_461r']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_461r + $qty_debet_closed_461r + $qty_kredit_rejected_461r || $qty_kredit_rejected_461r_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_461r + $qty_debet_reguler_460r) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_461r + $debet_closed_461r + $kredit_rejected_461r);
            $return_kirim_mtd = ($debet_rejected_461 + $debet_closed_461);
            $batal_kirim_mtd = $debet_batal_9911;
            $order_mtd = ($debet_reguler_461r + $debet_reguler_460r) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_461 + $debet_reguler_460) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_461 + $debet_reguler_460) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_461r + $mtd_460r - $mtd_982) - ($mtd_461 + $mtd_460);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            // $sub_outstanding["ytd_outstanding"] = $outstandingSubjek[$subj_id]["prev_outstanding"] + $outstandingSubjek[$subj_id]["outstanding"];
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "supplier_id"          => array(
                "label" => "vid",
            ),
            "supplier_nama"        => array(
                "label" => "vendor",
            ),
            /*prevous outstanding*/
            "prev_qty_outstanding" => array(
                "label"      => "previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_outstanding"     => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // // "debet_reguler_582so"          => array(
            // //     "label"      => "new order value",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // //     "attr"       => "class='text-right'",
            // //     "summary"    => true,
            // // ),
            // "qty_debet_reguler_382so"      => array(
            //     "label"      => "new order export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_382so"          => array(
            //     "label"      => "new order export  value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582so"      => array(
            //     "label"      => "new order reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"      => array(
            //     "label"      => "new kirim reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order new netto*/
            "qty_order_netto"      => array(
                "label"      => "new netto order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "order_netto"          => array(
                "label"      => "new netto order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*RETURNAN*/
            // "qty_debet_982"        => array(
            //     "label"      => "new return qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_982"            => array(
            //     "label"      => "new return value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "grn qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "grn local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "grn export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "grn export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "grn balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "grn export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "grn cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            "qty_kirim_netto"      => array(
                "label"      => "new netto grn qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_netto"          => array(
                "label"      => "new netto grn value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
            "qty_outstanding"      => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "outstanding"          => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "netto",
                "attr"       => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    // "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            /*last outstanding*/
            "ytd_qty_outstanding"  => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_outstanding"      => array(
                "label"      => "total outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );

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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan Vendor supplies Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "rawdatasupplies_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_dua",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
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
        $url_target = base_url();
        $loaders = array(
            // "auto_satu" => $url_target . "Tool/generateNonAkuntingAllSales",
            // "auto_dua" => $url_target . "Tool/generateNonAkuntingAllBatal",
            // "auto_tiga" => $url_target . "Tool/generateNonAkuntingAllSalesExport",
            // "auto_empat" => $url_target . "Tool/generateNonAkuntingAllSalesReject",
        );
        //endregion
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => "Outstanding Sales Order Penjualan",
            "subTitle"    => "Raw Data Purchasing Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
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
            "sum_satu"    => base_url() . "laporan/Procurement/cekoutstandingvendor" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Procurement/cekoutstandingvendorsupplies" . "$strGet",
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
            "title"       => "Outstanding Penjualan",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
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
            "sum_null"    => base_url() . "laporan/Procurement/$ygditampilkan" . "$strGet",
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