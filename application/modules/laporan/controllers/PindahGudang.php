<?php

class PindahGudang extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "pindahgudang/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        // $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("1587",);

    }
    // ----------------------sales order-------------
    // rendering complite


    public function produkso()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
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
            "fulldate"           => array(
                "label" => "Tanggal",
            ),
            "master_id"          => array(
                "label" => "mID",
            ),
            "kode"               => array(
                "label" => "kode",
            ),
            "extern_nama"        => array(
                "label" => "produk",
            ),
            "_company_olehID"    => array(
                "label" => "urut salesman",
            ),
            "seller_nama"        => array(
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
            "cabang_nama"        => array(
                "label" => "cabang",
            ),
            "_company_stepCode"  => array(
                "label" => "urut order",
            ),
            "_step_1_nomer"      => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "_company_customerID" => array(
            //     "label" => "urut customer",
            //     "attr"  => "width='100px'",
            // ),
            "customer_nama"      => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"      => array(
                "label" => "satuan",
            ),
            // "spo_qty_debet_lap" => array(
            //     "label"      => "qty order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            // "spo_debet_lap"     => array(
            //     "label"      => "nilai order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            "qty_debet_lap"      => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"          => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "pkd_rejected"       => array(
                "label"      => "qty dibatalkan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_closed"         => array(
                "label"      => "qty fulfill",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_qty_debet_lap"  => array(
                "label"      => "qty dikirim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_debet_lap"      => array(
                "label"      => "nilai dikirim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_qty_kredit_lap" => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_kredit_lap"     => array(
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
            // "mode"        => "default",
            "mode"        => "langsung_indek",
            // "title"       => "Sales Order Penjualan",
            "title"       => "Raw Data Sales Order $strDate",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "order_" . dtimeToSecond(dtimeNow()),
            "modal_size"  => "xl",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingcustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    /*
     * per per
     */
    public function produksosales()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['seller_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

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
            "seller_id"   => array(
                "label" => "sID",
            ),
            "seller_nama" => array(
                "label" => "salesman",
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
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

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
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Penjualan/produksotransaksipersales",
                    "title"   => "Transaksi per salesman",
                    "key"     => "seller_id",
                    "heading" => "seller_nama",
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
            "title"       => "Summary Order by salesman $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkso_" . dtimeToSecond(dtimeNow()),
            "link_detile" => base_url() . "laporan/Penjualan/perindekshow/produksotransaksipersales",
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produksotransaksi()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        $arrSubjek = array();
        $seller_nama = "";
        $seller_id = "";

        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['master_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            $arrSubjek[$subjek_id] = $item;

            // $seller_id = $item['seller_id'];
            // $seller_nama = $item['seller_nama'];
        }

        /* ------------------------------------------------------------------
         * untuk kepentingan pembuatan title
         * ------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {

            switch ($_GET['ky']) {
                case "seller_id":
                    $this->load->model("Mdls/MdlEmployee");
                    $sl = new MdlEmployee();
                    $this->db->where("id", $_GET[$_GET['ky']]);
                    $sellers = $sl->callAllSeller();
                    $seller_datas = $sellers['data'];
                    // arrPrintPink($seller_datas);
                    $seller_nama = $seller_datas[$_GET[$_GET['ky']]]['nama'];
                    break;
            }
            // cekMerah($seller_id);
        }

        // cekBiru($seller_nama);
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();

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
                "label"  => "Tanggal",
                "format" => "formatField_he_format",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            // "_company_olehID" => array(
            //     "label" => "urut salesman",
            // ),
            "_company_sellerID" => array(
                "label" => "urut salesman",
            ),
            "seller_nama"       => array(
                "label" => "salesman",
            ),
            // "seller_id"   => array(
            //     "label" => "sID",
            // ),

            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_company_stepCode" => array(
                "label" => "urut order",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "_company_customerID" => array(
            //     "label" => "urut customer",
            //     "attr"  => "width='100px'",
            // ),
            "customer_nama"     => array(
                "label"       => "customer",
                "attr"        => "class='dt-nama-{pengenal}'",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => "master_id",
                    "target" => "laporan/Penjualan/produksoproduk",
                ),
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
                "links"   => array(
                    "target"  => "laporan/Penjualan/produkso",
                    "title"   => "Produk per salesman",
                    "key"     => "master_id",
                    "heading" => "_step_1_nomer",
                ),
            ),
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Penjualan/produksoproduk",
                    "title"   => "Produk per salesman",
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
        $i = isset($i) ? $i++ : 0;
        $title_str = isset($_GET['ky']) ? "Laporan SO oleh <b>" . $seller_nama . "</b>" : 'laporan Transaksi SO';
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "$title_str $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produksotransaksi_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "modal_size"  => "xl",
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produksoproduk()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['extern_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

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
            "extern_id"   => array(
                "label" => "pID",
            ),
            "kode"        => array(
                "label" => "kode",
            ),
            "extern_nama" => array(
                "label" => "produk",
            ),

            // "seller_nama" => array(
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
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Penjualan/produksotransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
        );
        // die("testing");
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
            "title"       => "Summary per Produk SO $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produksoproduk_" . dtimeToSecond(dtimeNow()),
            "modal_size"  => "xl",
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produksocustomer()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['customer_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

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
            "customer_id"   => array(
                "label" => "pID",
            ),
            // "kode"                => array(
            //     "label" => "kode",
            // ),
            "customer_nama" => array(
                "label" => "produk",
                "links" => array(
                    "target"  => "laporan/Penjualan/produksotransaksi",
                    "title"   => "Transaksi per konsumen",
                    "key"     => "customer_id",
                    "heading" => "customer_nama",
                ),
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
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Penjualan/produksotransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
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
            "title"       => "Summary per konsumen SO $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produksokonsumen_" . dtimeToSecond(dtimeNow()),
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    /*
     * end per per
     */

    // loader dowang, isi ngeload dari methode produksotransaksi
    public function produksotransaksipersales()
    {
        if (isset($_GET['ky'])) {
            $seller_datas[$_GET[$_GET['ky']]]['id'] = $_GET[$_GET['ky']];
        }
        else {
            $this->load->model("Mdls/MdlEmployee");
            $sl = new MdlEmployee();
            $sellers = $sl->callAllSeller();
            $seller_datas = $sellers['data'];
        }

        // showLast_query("kuning");
        // cekHijau(sizeof($seller_datas));
        // arrPrintPink($seller_datas);

        /* -------------------------------------------------------------------------------------------------
         * menampilkan data
         * -------------------------------------------------------------------------------------------------*/
        foreach ($seller_datas as $seller_id => $seller_speks) {
            $_GET['ky'] = "seller_id";
            $_GET[$_GET['ky']] = $seller_id;
            $seller_nama = $seller_speks['nama'];
            $div_id = "show_$seller_id";
            $strget = $_GET;
            $strGet = "?1=1";
            foreach ($strget as $kget => $vget) {
                $strGet .= "&$kget=$vget";
            }

            // mati_disini(__LINE__);
            $link = base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet";
            echo "<div id='$div_id'>$div_id</div>";
            echo "<script>$('#$div_id').load('$link')</script>";
        }
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
            "title"       => "Sales Order Penjualan",
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
            "sum_null"    => base_url() . "laporan/Penjualan/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------

    // ----------------------------------------------penjualan
    /*
     * rombongan pindah gudang
     */
    public function produk()
    {
        /* ----------------------------------------------------------------------------------------------------------
         * untuk memasang master data dari persediaan harus update Coms/ComRekeningPembantuProduk
         * di dlmnya terjadi pairingan dengan data produk dan transaksi da ditampilkan dl satu array
         * ----------------------------------------------------------------------------------------------------------*/
        $this->load->helper("he_mass_table");
        $this->load->library("laporan/DataPindahGudang");
        $ps = new DataPindahGudang();


        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m") . "-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        // $jenisTr = $this->jenisTr_penjualan;
        $jenisTr = $this->jenisTrs;

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            // $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // else {
        //     $this->db->limit($this->default_limit);
        // }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        // $ps->setSortBy($sortings);

        // $ps->setJenisTr($jenisTr);
        // cekMErah($date1." ".$date2);
        $temp = $ps->produkMoved($date1, $date2,$this->session->login['cabang_id']);
        // cekMErah($this->db->last_query());
        $masterData = $temp['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"         => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "kode"          => array(
                "label" => "kode",
            ),
            "nama"          => array(
                "label" => "produk",
            ),
            "no_part"       => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "placeName" => array(
            //     "label" => "cb",
            //     // "format" => "formatField_he_format",
            // ),
            "nomer_top"     => array(
                "label"  => "nomer pre",
                "format" => "formatField_he_format",
            ),
            "nomer"         => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "gudang_id"     => array(
                "label" => "gudang tkp",
            ),
            "m_gudangName"  => array(
                "label" => "asal",
            ),

            // "gudang_id" => array(
            //     "label" => "Gid tujuan",
            // ),
            "m_gudang2Name" => array(
                "label" => "tujuan",
            ),
            "m_sellerName"  => array(
                "label" => "dibuat",
            ),
            "m_olehName"    => array(
                "label" => "diterima",
            ),
            // --
            // "harga"          => array(
            //     "label"  => "hpp",
            //     "format" => "formatField_he_format",
            // ),
            // "i_jual" => array(
            //     "label" => "bruto",
            //     "format" => "formatField_he_format",
            // ),
            // "m_disc" => array(
            //     "label" => "diskon",
            //     "format" => "formatField_he_format",
            // ),

            // "i_nett1" => array(
            //     "label" => "netto",
            //     "format" => "formatField_he_format",
            // ),
            "qty_debet"     => array(
                "label"  => "in",
                "format" => "formatField_he_format",
            ),
            "qty_kredit"    => array(
                "label"  => "out",
                "format" => "formatField_he_format",
            ),

            // "ppn"     => array(
            //     "label"  => "ppn",
            //     "format" => "formatField_he_format",
            // ),
            // "kredit"         => array(
            //     "label"  => "persediaan",
            //     "format" => "formatField_he_format",
            // ),
            // "i_subtotal" => array(
            //     "label" => "penjualan",
            //     "format" => "formatField_he_format",
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
            "mode"        => "langsung_indek",
            "title"       => "Aktifitas pindah gudang $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $this->jenisTr_penjualan,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            // "sum_satu" => base_url() . "laporan/Penjualan/produkperproduk" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function viewPindahGudang()
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

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m") . "-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

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
            "title"       => "Pindah gudang",
            "subTitle"    => "Raw Data pindah gudang",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "1587",
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
            "sum_null"    => base_url() . "laporan/PindahGudang/produk/" . "$strGet",
            "sum_satu"    => base_url() . "laporan/PindahGudang/viewProdukGudang" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function viewProdukGudang()
    {
        $allGudandactive = loadAllActiveGudang($this->session->login["cabang_id"]);
        $fieldProduk = array(
            "extern_id"   => "extern_id",
            "extern_nama" => "extern_nama",
            "kode"        => "kode",
            "label"       => "label",
            "no_part"     => "no_part",
        );

        $this->load->helper("he_mass_table");
        $this->load->library("laporan/DataPindahGudang");
        $ps = new DataPindahGudang();


        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m") . "-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        // $jenisTr = $this->jenisTr_penjualan;
        $jenisTr = $this->jenisTrs;

        $strDate = "";
        $conditional = array();
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            // $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // else {
        //     $limit = $this->default_limit;
        //     $conditional =array(
        //         "limit"=>$this->default_limit,
        //     );
        // }
        $limit = $this->default_limit;
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        // $ps->setSortBy($sortings);

        // $ps->setJenisTr($jenisTr);
        // cekMErah($date1 . " " . $date2);
        $temp = $ps->produkGudangMoved($date1, $date2,$this->session->login['cabang_id']);
        //         arrPrint($temp);
        // matiHere();
        $masterData = $temp['produkData'];
        $masterDataGudang = $temp['gudangData'];
        // cekHere(sizeof($main_datas));
        // arrPrint($masterData);

        /*
         *
         * -----------------------------------------------------------*/
        // $daftarGudang = array();
        // $produkGudang = array();
        // $arrProduk = array();
        // foreach ($masterData as $item) {
        //     foreach($fieldProduk as $key =>$keyLabel ){
        //         $arrProduk[$item["extern_id"]][$key]=$item[$key];
        //     }
        //     // arrPrint($item);
        //     // $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
        //     // $spo_debet_lap = $item['spo_debet_lap'];
        //     // $qty_debet_lap = $item['qty_debet'];
        //     // $debet_lap = $item['debet'];
        //     //
        //     // $qty_kredit_lap = $item['qty_kredit_lap'];
        //     // $kredit_lap = $item['kredit_lap'];
        //     //
        //     // $prev_qty_kredit = $item['prev_qty_kredit'];
        //     // $prev_kredit = $item['prev_kredit'];
        //     // $now_qty_kredit = $item['qty_kredit'];
        //     // $now_kredit = $item['kredit'];
        //     $subjek_id = $item['gudang_id'];
        //     $produkGudang[$subjek_id][$item["extern_id"]][]=$item;
        //
        //     // $daftarGudang[$item['gudang_id']] = $item['gudang_nama'];
        //
        //     // $subjek_id = $item['extern_id'];
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] += $prev_qty_kredit;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_prev_kredit'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_prev_kredit'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_prev_kredit'] += $prev_kredit;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;
        //     //
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_now_qty_kredit'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] += $now_qty_kredit;
        //     // if (!isset($arrSumSubjek[$subjek_id]['sum_now_kredit'])) {
        //     //     $arrSumSubjek[$subjek_id]['sum_now_kredit'] = 0;
        //     // }
        //     // $arrSumSubjek[$subjek_id]['sum_now_kredit'] += $now_kredit;
        //     //
        //     // $arrSubjek[$subjek_id] = $item;
        // }
        // arrPrintHijau($arrProduk);
        // // arrPrintHijau($daftarGudang);
        // mati_disini(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        // $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // mati_disini(__LINE__);

        $header2 = array();

        $arrHeaders = array(

            // "dtime"         => array(
            //     "label" => "Tanggal",
            // ),
            // "master_id"     => array(
            //     "label" => "mID",
            // ),
            "kode"        => array(
                "label" => "kode",
            ),
            "extern_nama" => array(
                "label" => "produk",
            ),
            // "extern_nama"        => array(
            //     "label"       => "produk",
            //     "attr"        => "class='dt-nama-{pengenal}'",
            //     "collapsible" => array(
            //         "layout" => false,
            //         "key"    => "seller_id",
            //         // "target" => "laporan/Crm/produkoutstandingblnproduk",
            //         "target" => "laporan/PindahGudang/viewPindahGudang",
            //     ),
            // ),
            // "extern_nama" => array(
            //     "label"       => "produk",
            //     "attr"        => "class='dt-nama-{pengenal}'",
            //     "collapsible" => array(
            //         "layout" => false,
            //         "key"    => "seller_id",
            //         // "target" => "laporan/Crm/produkoutstandingblnproduk",
            //         "target" => "laporan/PindahGudang/viewPindahGudang",
            //     ),
            // ),
            // "seller_id"   => array(
            //     "label" => "sID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            // "cabang_nama"   => array(
            //     "label" => "cabang",
            // ),
            // "_step_1_nomer" => array(
            //     "label"      => "nomer order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "customer_nama" => array(
            //     "label" => "customer",
            //     "attr"  => "width='100px'",
            // ),
            // "produk_satuan" => array(
            //     "label" => "satuan",
            // ),

            // "sum_prev_qty_kredit"   => array(
            //     "label"      => "Previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),
            // "sum_prev_kredit"       => array(
            //     "label"       => "Previous outstanding value",
            //     "format"      => "formatField_he_format",
            //     "format_key"  => "harga",
            //     // "attr"       => "class='text-right bg-danger'",
            //     "summary"     => true,
            //     "attr"        => "class='dt-nama-{pengenal} text-right bg-danger'",
            //     "collapsible" => array(
            //         "layout" => false,
            //         "key"    => "seller_id",
            //         "target" => "laporan/Crm/produkoutstandingblnproduk",
            //         // "target" => "laporan/Crm/produkoutstandingbln",
            //     ),
            // ),
            // "sum_spo_qty_debet_lap" => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            // "sum_spo_debet_lap"     => array(
            //     "label"      => "new order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),

            // "sum_qty_kredit_lap"    => array(
            //     "label"      => "total last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            // "sum_kredit_lap"        => array(
            //     "label"      => "total last outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            "no_part"     => array(
                "label" => "no part",

            ),
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
        $header2 = array(
            "sum_qty_debet_awal"  => array(
                "label"      => "awal",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_qty_debet"       => array(
                "label"      => "in",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_kredit"      => array(
                "label"      => "out",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_debet_akhir" => array(
                "label"      => "akhir",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            // "sum_now_qty_kredit" => array(
            //     "label"      => "out qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            // "sum_now_kredit"     => array(
            //     "label"      => "out value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
        );
        $arrHeader_add = array();
        foreach ($allGudandactive as $ii => $gData) {
            $arrHeader_add[$gData['nama']] = array(
                "label_id" => $gData['id'],
                "label"    => $gData['nama'],
                "attr"        => "class='dt-nama-{pengenal}' colspan='" . sizeof($header2) . "'",
                "srcKey"      => "extern_id",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => $gData["id"],

                    // "target" => "laporan/Crm/produkoutstandingblnproduk",
                    // "target" => "laporan/PindahGudang/viewPindahGudang",
                ),
            );
        }
        //         arrPrint($arrHeader_add);
        // matiHEre();
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
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"               => "langsung_2_header",
            "title"              => "Laporan Pindah produk per gudang " . $judul_lap,
            "subTitle"           => "Raw data pembelian",
            "modul_path"         => $this->modul_path,
            "color_bar"          => "box-orange",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"            => "1587",
            "data_id"            => "cabang_" . randomNumber(1),
            "master_data"        => $masterData,
            "master_data_gudang" => $masterDataGudang,
            "arrHeaders"         => $arrHeaders,
            "arrHeader_add"      => $arrHeader_add,
            "header2"            => $header2,
            // navigasi
            "url"                => base_url(uri_string()) . "$strGet",
            "strGet"             => $strGet,
            "date1"              => $date1,
            "date2"              => $date2,
            "date_min"           => 1,
            "date_max"           => dtimeNow('Y-m-d'),
            "data_gudang"        => $allGudandactive,
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public
    function produkperproduk()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTrs;
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
            $sum_sub_jual_nppn = $masterDatum['i_subtotal'];
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

    public
    function produkpercustomer()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTrs;
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();

        $strDate = "";
        $ps->setJenisTr("582spo");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit
         * ----------------------------------------------------------------------------------------------------------*/
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(transaksi.dtime)>=" => $get_date1,
                "date(transaksi.dtime)<=" => $get_date2,
                // "trash_4" => 1,

            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $ps->setLimit($this->default_limit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);


        $src = $ps->callBdProdukNonAkunting();
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // arrPrintHijau($masterData);
        foreach ($masterData as $masterDatum) {
            $sellerID = $masterDatum['oleh_id'];
            $cabangID = $masterDatum['cabang_id'];
            $customerID = $masterDatum['customerID'];;
            $sub_nett1 = $masterDatum['sub_nett1'];

            $olahan[$customerID] = $masterDatum;

            if (!isset($olahan0[$customerID]['sumPso'])) {
                $olahan0[$customerID]['sumPso'] = 0;
            }
            $olahan0[$customerID]['sumPso'] += $sub_nett1;


            // cekMerah($customerID);
            if (!isset($olahan2[$customerID]['sumCancel'])) {
                $olahan2[$customerID]['sumCancel'] = 0;
            }
            if ($masterDatum['trash_4'] == 1) {
                $olahan2[$customerID]['sumCancel'] += $sub_nett1;
            }
            // $olahan[$customerID] = $datas;
        }

        foreach ($olahan as $customer_id => $itemParam) {
            $total = $olahan0[$customer_id]['sumPso'];
            $total2 = $olahan2[$customer_id]['sumCancel'];

            $subtotal['subTotal'] = $total - $total2;
            $hasilOlahan[$customer_id] = $itemParam + $olahan0[$customer_id] + $olahan2[$customer_id] + $subtotal;
        }

        $masterData = $hasilOlahan;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "customerName" => array(
                "label"  => "Customer",
                "format" => "formatField_he_format",
            ),
            "sumPso"       => array(
                "label"   => "pre SO bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumCancel"    => array(
                "label"   => "pre SO cancel",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "subTotal"     => array(
                "label"   => "pre SO netto",
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
            "title"       => "Laporan By Customer" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenistr"     => $jenisTr,
            "data_id"     => "presopercustomer",
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

    /*
     * end rombomngan
     */


    public
    function test()
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